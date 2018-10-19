<?php
namespace Politizr\CommandBundle\Command;


use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

// use Symfony\Component\Console\Command\Command as BaseCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Politizr\Exception\InconsistentDataException;

use Politizr\Model\PUserQuery;

use Politizr\Model\PUser;
use Politizr\Model\PMEmailing;

use Politizr\Constant\ObjectTypeConstants;
use Politizr\Constant\EmailConstants;
use Politizr\Constant\NotificationConstants;

use Politizr\Exception\PolitizrException;

/**
 * Politizr account notifications email sending
 *
 * https://github.com/Politizr/Politizr/wiki/Liste-des-notifications-mail
 * 
 * @author Lionel Bouzonville
 */
class EmailAccountNotificationsCommand extends ContainerAwareCommand
{
    private $logger;
    private $monitoringManager;
    private $notificationService;
    private $documentService;
    private $mailer;
    private $transport;
    private $templating;
    private $senderEmail;
    private $clientName;

    protected function configure()
    {
        $this
            ->setName('politizr:email:account')
            ->setDescription('Send account notifications')
            ->addArgument(
                'beginAt',
                InputArgument::OPTIONAL,
                'BeginAt (DateTime format)'
            )
            ->addArgument(
                'endAt',
                InputArgument::OPTIONAL,
                'BeginAt (DateTime format)'
            )
            ->addOption(
                'userId',
                null,
                InputOption::VALUE_OPTIONAL,
                'If set, the task will send notification only for this user id'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $isVerbose = (OutputInterface::VERBOSITY_VERBOSE <= $output->getVerbosity());

        // services
        $this->logger = $this->getContainer()->get('logger');
        $this->monitoringManager = $this->getContainer()->get('politizr.manager.monitoring');
        $this->notificationService = $this->getContainer()->get('politizr.functional.notification');
        $this->documentService = $this->getContainer()->get('politizr.functional.document');
        $this->mailer = $this->getContainer()->get('mailer');
        $this->transport = $this->getContainer()->get('swiftmailer.transport.real');
        $this->templating = $this->getContainer()->get('templating');
        $this->senderEmail = $this->getContainer()->getParameter('sender_email');
        $this->clientName = $this->getContainer()->getParameter('client_name');

        // initialize begin & end dates
        $beginAt = $input->getArgument('beginAt');
        if ($beginAt) {
            $beginAt = new \DateTime($beginAt);
        } else {
            $beginAt = new \DateTime('yesterday');
            $beginAt->setTime(17, 0, 1);
        }
        $endAt = $input->getArgument('endAt');
        if ($endAt) {
            $endAt = new \DateTime($endAt);
        } else {
            $endAt = new \DateTime('now');
            $endAt->setTime(17, 0, 0);
        }

        // get option
        $userId = $input->getOption('userId');

        // get list of active & daily subscribed users
        $users = PUserQuery::create()
            ->_if($userId)
                ->filterById($userId)
            ->_endif()
            ->distinct()
            ->online()
            ->usePUSubscribePNEQuery()
                ->filterByPNEmailId(EmailConstants::ID_PROFILE_SUMMARY)
            ->endUse()
            ->find();

        foreach ($users as $user) {
            // $output->write('.');

            $puNotifications = $this->notificationService->getUserNotificationsForEmailing($user, $beginAt, $endAt);

            // Badges
            $badgeNotifications = $this->notificationService->extractPUNotifications($puNotifications, [NotificationConstants::ID_U_BADGE]);

            $badgesPart = $this->getBadgesRendering($user, $badgeNotifications);

            // User Publications
            $interactedPublications = $this->notificationService->getMostInteractedUserPublications($user, $beginAt, $endAt, EmailConstants::NB_MAX_INTERACTED_PUBLICATIONS);

            $userPublicationsPart = $this->getUserPublicationsRendering($user, $interactedPublications);

            // Users followed Debates Publications
            $followedDebatesPublications = $this->notificationService->getMostInteractedFollowedDebatesPublications($user, $beginAt, $endAt, EmailConstants::NB_MAX_INTERACTED_PUBLICATIONS);

            $followedDebatesPublicationsPart = $this->getFollowedDebatesRendering($user, $followedDebatesPublications);

            // Retrieve previous notifieds publications to avoid duplicate notifications
            $notifDebateIds = $this->notificationService->getObjectTypeIdsFromInteractedPublications($followedDebatesPublications, ObjectTypeConstants::TYPE_DEBATE);
            $notifReactionIds = $this->notificationService->getObjectTypeIdsFromInteractedPublications($followedDebatesPublications, ObjectTypeConstants::TYPE_REACTION);
            $notifCommentDebateIds = $this->notificationService->getObjectTypeIdsFromInteractedPublications($followedDebatesPublications, ObjectTypeConstants::TYPE_DEBATE_COMMENT);
            $notifCommentReactionIds = $this->notificationService->getObjectTypeIdsFromInteractedPublications($followedDebatesPublications, ObjectTypeConstants::TYPE_REACTION_COMMENT);

            // Users followed Publications
            $followedUsersPublications = $this->notificationService->getMostInteractedFollowedUsersPublications($user, $beginAt, $endAt, EmailConstants::NB_MAX_INTERACTED_PUBLICATIONS, $notifDebateIds, $notifReactionIds, $notifCommentDebateIds, $notifCommentReactionIds);

            $followedUsersPublicationsPart = $this->getFollowedUsersRendering($user, $followedUsersPublications);

            // Followers
            $followerNotifications = $this->notificationService->extractPUNotifications($puNotifications, [NotificationConstants::ID_U_FOLLOWED]);

            $followersPart = $this->getFollowersRendering($user, $followerNotifications);

            // Emailing
            if (sizeof($badgesPart) > 0 ||
                sizeof($userPublicationsPart) > 0 ||
                sizeof($followersPart) > 0 ||
                sizeof($followedUsersPublicationsPart) > 0 ||
                sizeof($followedDebatesPublicationsPart) > 0
            ) {
                $subjectMessage = $this->accountNotificationEmail($user, $badgesPart, $userPublicationsPart, $followedDebatesPublicationsPart, $followedUsersPublicationsPart, $followersPart);

                // monitoring
                if (sizeof($subjectMessage) > 0) {
                    $pmEmailing = new PMEmailing();
                    $pmEmailing->setPUserId($user->getId());
                    $pmEmailing->setPNEmailId(EmailConstants::ID_PROFILE_SUMMARY);
                    $pmEmailing->setTitle($subjectMessage[0]);
                    $pmEmailing->setHtmlBody($subjectMessage[1]->getBody());
                    $pmEmailing->setTargetEmail($subjectMessage[2]);
                    $pmEmailing->save();
                }
            }
        }

        $spool = $this->mailer->getTransport()->getSpool();
        $nbSent = $spool->flushQueue($this->transport);

        $now = new \DateTime('now');
        // $output->writeln('');
        $output->writeln(sprintf('<info>%s - Send account notifications completed. %s mails have been sent!</info>', $now->format('Y-m-d H:i:s'), $nbSent));
    }

    /**
     * Get Badges rendering
     *
     * @param PUser $user
     * @param array $badgeNotifications
     */
    private function getBadgesRendering(PUser $user, $badgeNotifications)
    {
        $badges = array();
        $badgesPartHtml = null;
        $badgesPartTxt = null;

        // Badges
        $loop = 0;
        foreach ($badgeNotifications as $puNotification) {
            // Update attributes depending of context
            $attr = $this->documentService->computeDocumentContextAttributes(
                $puNotification->getPObjectName(),
                $puNotification->getPObjectId(),
                $puNotification->getPAuthorUserId()
            );

            $badge = $attr['subject'];

            if ($badge) {
                $badges[$loop]['title'] = $badge->getTitle();
                $badges[$loop]['description'] = $badge->getDescription();
                $loop++;
            }

        }
        if (count($badges) > 0) {
            $badgesPartHtml = $this->templating->render(
                'PolitizrFrontBundle:Notification\\MessageEmail:_accountBadges.html.twig',
                array(
                    'user' => $user,
                    'badges' => $badges,
                )
            );
            $badgesPartTxt = $this->templating->render(
                'PolitizrFrontBundle:Notification\\MessageEmail\\Txt:_accountBadges.txt.twig',
                array(
                    'user' => $user,
                    'badges' => $badges,
                )
            );
            return [$badgesPartHtml, $badgesPartTxt];
        }

        return array();
    }

    /**
     * Get user publications' interactions rendering
     *
     * @param PUser $user
     * @param array $publications
     * @return array
     */
    private function getUserPublicationsRendering(PUser $user, $publications)
    {
        $userPublications = array();
        $userPublicationsPartHtml = null;
        $userPublicationsPartTxt = null;

        $loop = 0;
        foreach ($publications as $publication) {
            // Update attributes depending of context
            $attr = $this->documentService->computeDocumentContextAttributes(
                $publication->getType(),
                $publication->getId(),
                $publication->getAuthorId()
            );

            $userPublications[$loop]['title'] = $attr['title'];
            $userPublications[$loop]['type'] = $attr['type'];
            $userPublications[$loop]['url'] = $attr['url'];

            $userPublications[$loop]['nbReactions'] = $publication->getNbReactions();
            $userPublications[$loop]['nbComments'] = $publication->getNbComments();
            $userPublications[$loop]['nbNotifications'] = $publication->getNbNotifications();

            $loop++;
        }

        if (count($userPublications) > 0) {
            $userPublicationsPartHtml = $this->templating->render(
                'PolitizrFrontBundle:Notification\\MessageEmail:_accountUserPublications.html.twig',
                array(
                    'user' => $user,
                    'userPublications' => $userPublications,
                )
            );
            $userPublicationsPartTxt = $this->templating->render(
                'PolitizrFrontBundle:Notification\\MessageEmail\\Txt:_accountUserPublications.txt.twig',
                array(
                    'user' => $user,
                    'userPublications' => $userPublications,
                )
            );
            return [$userPublicationsPartHtml, $userPublicationsPartTxt];
        }

        return array();
    }

    /**
     * Get users followed debates publications rendering
     *
     * @param PUser $user
     * @param array $publications
     * @return array
     */
    private function getFollowedDebatesRendering(PUser $user, $publications)
    {
        $followedDebatesPublications = array();
        $followedDebatesPublicationsPartHtml = null;
        $followedDebatesPublicationsPartTxt = null;

        $loop = 0;
        foreach ($publications as $publication) {
            // Update attributes depending of context
            $attr = $this->documentService->computeDocumentContextAttributes(
                $publication->getType(),
                $publication->getId(),
                $publication->getAuthorId()
            );

            $followedDebatesPublications[$loop]['title'] = $attr['title'];
            $followedDebatesPublications[$loop]['type'] = $attr['type'];
            $followedDebatesPublications[$loop]['url'] = $attr['url'];
            $followedDebatesPublications[$loop]['author'] = $attr['author'];
            $followedDebatesPublications[$loop]['authorUrl'] = $attr['authorUrl'];
            $followedDebatesPublications[$loop]['document'] = $attr['document'];
            $followedDebatesPublications[$loop]['documentUrl'] = $attr['documentUrl'];
            $followedDebatesPublications[$loop]['initialDebate'] = $attr['initialDebate'];

            $loop++;
        }

        if (count($followedDebatesPublications) > 0) {
            $followedDebatesPublicationsPartHtml = $this->templating->render(
                'PolitizrFrontBundle:Notification\\MessageEmail:_accountFollowedDebatesPublications.html.twig',
                array(
                    'user' => $user,
                    'followedDebatesPublications' => $followedDebatesPublications,
                )
            );
            $followedDebatesPublicationsPartTxt = $this->templating->render(
                'PolitizrFrontBundle:Notification\\MessageEmail\\Txt:_accountFollowedDebatesPublications.txt.twig',
                array(
                    'user' => $user,
                    'followedDebatesPublications' => $followedDebatesPublications,
                )
            );
            return [$followedDebatesPublicationsPartHtml, $followedDebatesPublicationsPartTxt];
        }

        return array();
    }

    /**
     * Get users followed publications rendering
     *
     * @param PUser $user
     * @param array $publications
     * @return array
     */
    private function getFollowedUsersRendering(PUser $user, $publications)
    {
        $followedUsersPublications = array();
        $followedUsersPublicationsPartHtml = null;
        $followedUsersPublicationsPartTxt = null;

        $loop = 0;
        foreach ($publications as $publication) {
            // Update attributes depending of context
            $attr = $this->documentService->computeDocumentContextAttributes(
                $publication->getType(),
                $publication->getId(),
                $publication->getAuthorId()
            );

            $followedUsersPublications[$loop]['title'] = $attr['title'];    
            $followedUsersPublications[$loop]['type'] = $attr['type'];
            $followedUsersPublications[$loop]['url'] = $attr['url'];
            $followedUsersPublications[$loop]['author'] = $attr['author'];
            $followedUsersPublications[$loop]['authorUrl'] = $attr['authorUrl'];
            $followedUsersPublications[$loop]['document'] = $attr['document'];
            $followedUsersPublications[$loop]['documentUrl'] = $attr['documentUrl'];

            $loop++;
        }

        if (count($followedUsersPublications) > 0) {
            $followedUsersPublicationsPartHtml = $this->templating->render(
                'PolitizrFrontBundle:Notification\\MessageEmail:_accountFollowedUsersPublications.html.twig',
                array(
                    'user' => $user,
                    'followedUsersPublications' => $followedUsersPublications,
                )
            );
            $followedUsersPublicationsPartTxt = $this->templating->render(
                'PolitizrFrontBundle:Notification\\MessageEmail\\Txt:_accountFollowedUsersPublications.txt.twig',
                array(
                    'user' => $user,
                    'followedUsersPublications' => $followedUsersPublications,
                )
            );
            return [$followedUsersPublicationsPartHtml, $followedUsersPublicationsPartTxt];
        }

        return array();
    }

    /**
     * Get followers rendering
     *
     * @param PUser $user
     * @param array $followerNotifications
     * @return array
     */
    private function getFollowersRendering(PUser $user, $followerNotifications)
    {
        $followers = array();
        $followersPartHtml = null;
        $followersPartTxt = null;

        $loop = 0;
        foreach ($followerNotifications as $puNotification) {
            // Update attributes depending of context
            $attr = $this->documentService->computeDocumentContextAttributes(
                $puNotification->getPObjectName(),
                $puNotification->getPObjectId(),
                $puNotification->getPAuthorUserId()
            );

            $author = $attr['author'];

            if ($author) {
                $followers[$loop]['author'] = $author;
                $followers[$loop]['authorUrl'] = $attr['authorUrl'];

                $loop++;
            }
        }
        if (count($followers) > 0) {
            $followersPartHtml = $this->templating->render(
                'PolitizrFrontBundle:Notification\\MessageEmail:_accountFollowers.html.twig',
                array(
                    'user' => $user,
                    'followers' => $followers,
                )
            );
            $followersPartTxt = $this->templating->render(
                'PolitizrFrontBundle:Notification\\MessageEmail\\Txt:_accountFollowers.txt.twig',
                array(
                    'user' => $user,
                    'followers' => $followers,
                )
            );
            return [$followersPartHtml, $followersPartTxt];
        }

        return array();
    }

    /**
     * Account notification.
     *
     * @param PUser $user
     * @param string $badgesPart
     * @param string $userPublicationsPart
     * @param string $followedDebatesPublicationsPart
     * @param string $followedUsersPublicationsPart
     * @param string $userPublicationsPart
     * @return array[subject,message,email]
     */
    private function accountNotificationEmail($user, $badgesPart, $userPublicationsPart, $followedDebatesPublicationsPart, $followedUsersPublicationsPart, $followersPart)
    {
        $userEmail = $user->getEmail();

        // Initialize and retrieve html & txt templates, compute email subject
        $subject = "";
        $more = false;
        $badgesPartHtml = $badgesPartTxt = null;
        if ($badgesPart && sizeof($badgesPart) > 0) {
            $badgesPartHtml = $badgesPart[0];
            $badgesPartTxt = $badgesPart[1];
            $subject = "vous avez gagné un nouveau badge";
        }

        $userPublicationsPartHtml = $userPublicationsPartTxt = null;
        if ($userPublicationsPart && sizeof($userPublicationsPart) > 0) {
            $userPublicationsPartHtml = $userPublicationsPart[0];
            $userPublicationsPartTxt = $userPublicationsPart[1];
            if (!empty($subject)) {
                if (!$more) {
                    $subject .= " (entre autres)";
                }
                $more = true;
            } else {
                $subject .= "vos publications suscitent des réactions";
            }
        }

        $followedDebatesPublicationsPartHtml = $followedDebatesPublicationsPartTxt = null;
        if ($followedDebatesPublicationsPart && sizeof($followedDebatesPublicationsPart) > 0) {
            $followedDebatesPublicationsPartHtml = $followedDebatesPublicationsPart[0];
            $followedDebatesPublicationsPartTxt = $followedDebatesPublicationsPart[1];
            if (!empty($subject)) {
                if (!$more) {
                    $subject .= " (entre autres)";
                }
                $more = true;
            } else {
                $subject .= "du nouveau sur des sujets suivis";
            }
        }

        $followedUsersPublicationsPartHtml = $followedUsersPublicationsPartTxt = null;
        if ($followedUsersPublicationsPart && sizeof($followedUsersPublicationsPart) > 0) {
            $followedUsersPublicationsPartHtml = $followedUsersPublicationsPart[0];
            $followedUsersPublicationsPartTxt = $followedUsersPublicationsPart[1];
            if (!empty($subject)) {
                if (!$more) {
                    $subject .= " (entre autres)";
                }
                $more = true;
            } else {
                $subject .= "du nouveau chez des utilisateurs que vous suivez";
            }
        }

        $followersPartHtml = $followersPartTxt = null;
        if ($followersPart && sizeof($followersPart) > 0) {
            $followersPartHtml = $followersPart[0];
            $followersPartTxt = $followersPart[1];
            if (!empty($subject)) {
                if (!$more) {
                    $subject .= " (entre autres)";
                }
                $more = true;
            } else {
                $subject .= "des utilisateurs suivent votre profil";
            }
        }
        $subject .= "...";
        $subject = ucfirst($subject);

        // prepare email and sent it
        try {
            $htmlBody = $this->templating->render(
                'PolitizrFrontBundle:Email:accountNotification.html.twig',
                array(
                    'user' => $user,
                    'badgesPart' => $badgesPartHtml,
                    'userPublicationsPart' => $userPublicationsPartHtml,
                    'followedDebatesPublicationsPart' => $followedDebatesPublicationsPartHtml,
                    'followedUsersPublicationsPart' => $followedUsersPublicationsPartHtml,
                    'followersPart' => $followersPartHtml,
                )
            );
            $txtBody = $this->templating->render(
                'PolitizrFrontBundle:Email:accountNotification.txt.twig',
                array(
                    'user' => $user,
                    'badgesPart' => $badgesPartTxt,
                    'userPublicationsPart' => $userPublicationsPartTxt,
                    'followedDebatesPublicationsPart' => $followedDebatesPublicationsPartTxt,
                    'followedUsersPublicationsPart' => $followedUsersPublicationsPartTxt,
                    'followersPart' => $followersPartTxt,
                )
            );

            $message = \Swift_Message::newInstance()
                    ->setSubject($subject)
                    ->setFrom(array($this->senderEmail => sprintf('%s', $this->clientName)))
                    ->setTo($userEmail)
                    ->setBody($htmlBody, 'text/html', 'utf-8')
                    ->addPart($txtBody, 'text/plain', 'utf-8')
            ;
            $message->getHeaders()->addTextHeader('X-CMail-GroupName', 'Account notification');

            // Envoi email
            $failedRecipients = array();
            $send = $this->mailer->send($message, $failedRecipients);

            return [$subject, $message, $userEmail];
        } catch (\Exception $e) {
            $this->logger->err(sprintf('Exception - EmailAccountNotificationCommand - message = %s', $e->getMessage()));
            $this->monitoringManager->createAppException($e);
        }
    }
}
