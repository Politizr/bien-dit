<?php
namespace Politizr\CommandBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

// use Symfony\Component\Console\Command\Command as BaseCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;

use Symfony\Component\EventDispatcher\GenericEvent;

use Politizr\Exception\InconsistentDataException;

use Politizr\Model\PUserQuery;

use Politizr\Model\PMEmailing;

use Politizr\Constant\ObjectTypeConstants;
use Politizr\Constant\EmailConstants;
use Politizr\Constant\NotificationConstants;

/**
 * Politizr email sending
 *
 * https://github.com/Politizr/Politizr/wiki/Liste-des-notifications-mail
 * @todo
 * - + d'aggregations pour les 3. et 4.
 * - suppr user inactifs / supprimés des envois
 * 
 * @author Lionel Bouzonville
 */
class EmailAccountNotificationsCommand extends ContainerAwareCommand
{
    private $logger;
    private $notificationService;
    private $documentService;
    private $mailer;
    private $transport;
    private $templating;

//     /**
//      *
//      */
//     public function setLogger($logger) {
//         $this->logger = $logger;
//     }
// 
//     /**
//      *
//      */
//     public function setEventDispatcher($eventDispatcher) {
//         $this->eventDispatcher = $eventDispatcher;
//     }
// 
//     /**
//      *
//      */
//     public function setNotificationService($notificationService) {
//         $this->notificationService = $notificationService;
//     }

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
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $isVerbose = (OutputInterface::VERBOSITY_VERBOSE <= $output->getVerbosity());

        // services
        $this->logger = $this->getContainer()->get('logger');
        $this->notificationService = $this->getContainer()->get('politizr.functional.notification');
        $this->documentService = $this->getContainer()->get('politizr.functional.document');
        $this->mailer = $this->getContainer()->get('mailer');
        $this->transport = $this->getContainer()->get('swiftmailer.transport.real');
        $this->templating = $this->getContainer()->get('templating');

        // dates
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

        $users = PUserQuery::create()
            ->distinct()
            ->online()
            ->joinPUSubscribePNE()
            ->find();

        foreach ($users as $user) {
            $output->write('.');

            $puNotifications = $this->notificationService->getUserNotifications($user, $beginAt, $endAt);
            
            /*
            10 > badges

            1 - 6 > interact sujet / réponse
            1 > new comment
            2 > note +
            3 > note -
            4 > new réponse sujet
            5 > new follower
            6 > new réponse réponse

            7 - 8 > interact comment
            7 > note +
            8 > note -

            9 > new followers
            */

            // Badges
            $badgeNotifications = $this->notificationService->extractPUNotifications($puNotifications, [NotificationConstants::ID_U_BADGE]);

            $badgesPart = $this->getBadgesRendering($badgeNotifications);
            $badgesPartHtml = $badgesPartTxt = null;
            if ($badgesPart) {
                $badgesPartHtml = $badgesPart[0];
                $badgesPartTxt = $badgesPart[1];
            }

            // User Publications
            $interactedPublications = $this->notificationService->getMostInteractedUserPublications($user, $beginAt, $endAt, EmailConstants::NB_MAX_INTERACTED_PUBLICATIONS);

            $userPublicationsPart = $this->getUserPublicationsRendering($interactedPublications, $output);
            $userPublicationsPartHtml = $userPublicationsPartTxt = null;
            if ($userPublicationsPart) {
                $userPublicationsPartHtml = $userPublicationsPart[0];
                $userPublicationsPartTxt = $userPublicationsPart[1];
            }

            // Users followed Debates Publications
            $followedDebatesPublications = $this->notificationService->getMostInteractedFollowedDebatesPublications($user, $beginAt, $endAt, EmailConstants::NB_MAX_INTERACTED_PUBLICATIONS);

            $followedDebatesPublicationsPart = $this->getFollowedDebatesRendering($followedDebatesPublications, $output);
            $followedDebatesPublicationsPartHtml = $followedDebatesPublicationsPartTxt = null;
            if ($followedDebatesPublicationsPart) {
                $followedDebatesPublicationsPartHtml = $followedDebatesPublicationsPart[0];
                $followedDebatesPublicationsPartTxt = $followedDebatesPublicationsPart[1];
            }

            // Users followed Publications
            $followedUsersPublications = $this->notificationService->getMostInteractedFollowedUsersPublications($user, $beginAt, $endAt, EmailConstants::NB_MAX_INTERACTED_PUBLICATIONS);

            $followedUsersPublicationsPart = $this->getFollowedUsersRendering($followedUsersPublications, $output);
            $followedUsersPublicationsPartHtml = $followedUsersPublicationsPartTxt = null;
            if ($followedUsersPublicationsPart) {
                $followedUsersPublicationsPartHtml = $followedUsersPublicationsPart[0];
                $followedUsersPublicationsPartTxt = $followedUsersPublicationsPart[1];
            }

            // Followers
            $followerNotifications = $this->notificationService->extractPUNotifications($puNotifications, [NotificationConstants::ID_U_FOLLOWED]);
            // $output->writeln(print_r($followerNotifications, true));
            // exit;

            $followersPart = $this->getFollowersRendering($followerNotifications);
            $followersPartHtml = $followersPartTxt = null;
            if ($followersPart) {
                $followersPartHtml = $followersPart[0];
                $followersPartTxt = $followersPart[1];
            }


            if ($badgesPart || $userPublicationsPart || $followersPart || $followedUsersPublicationsPart || $followedDebatesPublicationsPart) {
                $message = $this->accountNotificationEmail($user, $badgesPartHtml, $badgesPartTxt, $userPublicationsPartHtml, $userPublicationsPartTxt, $followedDebatesPublicationsPartHtml, $followedDebatesPublicationsPartTxt, $followedUsersPublicationsPartHtml, $followedUsersPublicationsPartTxt, $followersPartHtml, $followersPartTxt);
                // $output->writeln(print_r($message->getBody(), true));

                // monitoring
                $pmEmailing = new PMEmailing();
                $pmEmailing->setPUserId($user->getId());
                $pmEmailing->setPNEmailId(EmailConstants::ID_PROFILE_SUMMARY);
                $pmEmailing->setTitle('De nouvelles actualités sur votre POLITIZR aujourd\'hui');
                $pmEmailing->setHtmlBody($message->getBody());
                $pmEmailing->save();
            }

        }

        $spool = $this->mailer->getTransport()->getSpool();
        $nbSent = $spool->flushQueue($this->transport);

        $output->writeln('');
        $output->writeln(sprintf('<info>Send account notifications completed. %s mails have been sent!</info>', $nbSent));
    }

    /**
     * Get Badges rendering
     *
     * @param array $badgeNotifications
     */
    private function getBadgesRendering($badgeNotifications)
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

            // $subject = $attr['subject'];
            // $title = $attr['title'];
            // $url = $attr['url'];
            // $document = $attr['document'];
            // $documentUrl = $attr['documentUrl'];
            // $author = $attr['author'];
            // $authorUrl = $attr['authorUrl'];

            $badges[$loop]['title'] = $attr['title'];

            $loop++;
        }
        if (count($badges) > 0) {
            $badgesPartHtml = $this->templating->render(
                'PolitizrFrontBundle:Notification\\MessageEmail:_accountBadges.html.twig',
                array(
                    'badges' => $badges,
                )
            );
            $badgesPartTxt = $this->templating->render(
                'PolitizrFrontBundle:Notification\\MessageEmail\\Txt:_accountBadges.txt.twig',
                array(
                    'badges' => $badges,
                )
            );
            return [$badgesPartHtml, $badgesPartTxt];
        }

        return null;
    }

    /**
     * Get user publications' interactions rendering
     *
     * @param array $publications
     */
    private function getUserPublicationsRendering($publications, $output)
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

            // $subject = $attr['subject'];
            // $title = $attr['title'];
            // $url = $attr['url'];
            // $document = $attr['document'];
            // $documentUrl = $attr['documentUrl'];
            // $author = $attr['author'];
            // $authorUrl = $attr['authorUrl'];

            $userPublications[$loop]['title'] = $attr['title'];
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
                    'userPublications' => $userPublications,
                )
            );
            $userPublicationsPartTxt = $this->templating->render(
                'PolitizrFrontBundle:Notification\\MessageEmail\\Txt:_accountUserPublications.txt.twig',
                array(
                    'userPublications' => $userPublications,
                )
            );
            return [$userPublicationsPartHtml, $userPublicationsPartTxt];
        }

        return null;
    }

    /**
     * Get users followed debates publications rendering
     *
     * @param array $publications
     */
    private function getFollowedDebatesRendering($publications, $output)
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

            // $subject = $attr['subject'];
            // $title = $attr['title'];
            // $url = $attr['url'];
            // $document = $attr['document'];
            // $documentUrl = $attr['documentUrl'];
            // $author = $attr['author'];
            // $authorUrl = $attr['authorUrl'];

            $followedDebatesPublications[$loop]['title'] = 'un commentaire';
            if ($publication->getType() == ObjectTypeConstants::TYPE_DEBATE || $publication->getType() == ObjectTypeConstants::TYPE_REACTION) {
                $followedDebatesPublications[$loop]['title'] = $attr['title'];    
            }            
            $followedDebatesPublications[$loop]['url'] = $attr['url'];
            $followedDebatesPublications[$loop]['author'] = $attr['author'];
            $followedDebatesPublications[$loop]['authorUrl'] = $attr['authorUrl'];

            $loop++;
        }

        if (count($followedDebatesPublications) > 0) {
            $followedDebatesPublicationsPartHtml = $this->templating->render(
                'PolitizrFrontBundle:Notification\\MessageEmail:_accountFollowedDebatesPublications.html.twig',
                array(
                    'followedDebatesPublications' => $followedDebatesPublications,
                )
            );
            $followedDebatesPublicationsPartTxt = $this->templating->render(
                'PolitizrFrontBundle:Notification\\MessageEmail\\Txt:_accountFollowedDebatesPublications.txt.twig',
                array(
                    'followedDebatesPublications' => $followedDebatesPublications,
                )
            );
            return [$followedDebatesPublicationsPartHtml, $followedDebatesPublicationsPartTxt];
        }

        return null;
    }

    /**
     * Get users followed publications rendering
     *
     * @param array $publications
     */
    private function getFollowedUsersRendering($publications, $output)
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

            // $subject = $attr['subject'];
            // $title = $attr['title'];
            // $url = $attr['url'];
            // $document = $attr['document'];
            // $documentUrl = $attr['documentUrl'];
            // $author = $attr['author'];
            // $authorUrl = $attr['authorUrl'];

            $followedUsersPublications[$loop]['title'] = 'un commentaire';
            if ($publication->getType() == ObjectTypeConstants::TYPE_DEBATE || $publication->getType() == ObjectTypeConstants::TYPE_REACTION) {
                $followedUsersPublications[$loop]['title'] = $attr['title'];    
            }
            $followedUsersPublications[$loop]['url'] = $attr['url'];
            $followedUsersPublications[$loop]['author'] = $attr['author'];
            $followedUsersPublications[$loop]['authorUrl'] = $attr['authorUrl'];

            $loop++;
        }

        if (count($followedUsersPublications) > 0) {
            $followedUsersPublicationsPartHtml = $this->templating->render(
                'PolitizrFrontBundle:Notification\\MessageEmail:_accountFollowedUsersPublications.html.twig',
                array(
                    'followedUsersPublications' => $followedUsersPublications,
                )
            );
            $followedUsersPublicationsPartTxt = $this->templating->render(
                'PolitizrFrontBundle:Notification\\MessageEmail\\Txt:_accountFollowedUsersPublications.txt.twig',
                array(
                    'followedUsersPublications' => $followedUsersPublications,
                )
            );
            return [$followedUsersPublicationsPartHtml, $followedUsersPublicationsPartTxt];
        }

        return null;
    }

    /**
     * Get followers rendering
     *
     * @param array $followerNotifications
     */
    private function getFollowersRendering($followerNotifications)
    {
        $followers = array();
        $followersPartHtml = null;
        $followersPartTxt = null;

        // Badges
        $loop = 0;
        foreach ($followerNotifications as $puNotification) {
            // Update attributes depending of context
            $attr = $this->documentService->computeDocumentContextAttributes(
                $puNotification->getPObjectName(),
                $puNotification->getPObjectId(),
                $puNotification->getPAuthorUserId()
            );

            // $subject = $attr['subject'];
            // $title = $attr['title'];
            // $url = $attr['url'];
            // $document = $attr['document'];
            // $documentUrl = $attr['documentUrl'];
            // $author = $attr['author'];
            // $authorUrl = $attr['authorUrl'];

            $followers[$loop]['author'] = $attr['author'];
            $followers[$loop]['authorUrl'] = $attr['authorUrl'];

            $loop++;
        }
        if (count($followers) > 0) {
            $followersPartHtml = $this->templating->render(
                'PolitizrFrontBundle:Notification\\MessageEmail:_accountFollowers.html.twig',
                array(
                    'followers' => $followers,
                )
            );
            $followersPartTxt = $this->templating->render(
                'PolitizrFrontBundle:Notification\\MessageEmail\\Txt:_accountFollowers.txt.twig',
                array(
                    'followers' => $followers,
                )
            );
            return [$followersPartHtml, $followersPartTxt];
        }

        return null;
    }

    /**
     * Account notification.
     *
     * @param PUser $user
     * @param string $badgesPartHtml
     * @param string $badgesPartTxt
     * @param string $followedDebatesPublicationsPartHtml
     * @param string $followedDebatesPublicationsPartTxt
     * @param string $followedUsersPublicationsPartHtml
     * @param string $followedUsersPublicationsPartTxt
     * @param string $userPublicationsPartHtml
     * @param string $userPublicationsPartTxt
     */
    private function accountNotificationEmail($user, $badgesPartHtml, $badgesPartTxt, $userPublicationsPartHtml, $userPublicationsPartTxt, $followedDebatesPublicationsPartHtml, $followedDebatesPublicationsPartTxt, $followedUsersPublicationsPartHtml, $followedUsersPublicationsPartTxt, $followersPartHtml, $followersPartTxt)
    {
        $userEmail = $user->getEmail();

        // try {
            $subject = 'De nouvelles actualités sur votre POLITIZR aujourd\'hui';
            
            $htmlBody = $this->templating->render(
                'PolitizrFrontBundle:Email:accountNotification.html.twig',
                array(
                    'qualified' => $user->isQualified(),
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
                    'qualified' => $user->isQualified(),
                    'badgesPart' => $badgesPartTxt,
                    'userPublicationsPart' => $userPublicationsPartTxt,
                    'followedDebatesPublicationsPart' => $followedDebatesPublicationsPartTxt,
                    'followedUsersPublicationsPart' => $followedUsersPublicationsPartTxt,
                    'followersPart' => $followersPartTxt,
                )
            );

            $message = \Swift_Message::newInstance()
                    ->setSubject($subject)
                    ->setFrom(array('support@politizr.com' => 'Support@Politizr'))
                    ->setTo($userEmail)
                    ->setBody($htmlBody, 'text/html', 'utf-8')
                    ->addPart($txtBody, 'text/plain', 'utf-8')
            ;
            // $message->getHeaders()->addTextHeader('X-CMail-GroupName', 'Account notification');

            // Envoi email
            $failedRecipients = array();
            $send = $this->mailer->send($message, $failedRecipients);

            return $message;

            // $this->logger->info('send = '.print_r($send, true));
            // if (!$send) {
            //     throw new \Exception('email non envoyé - code retour = '.$send.' - adresse(s) en échec = '.print_r($failedRecipients, true));
            // }
        // } catch (\Exception $e) {
        //     $this->logger->err('Exception - message = '.$e->getMessage());
        // }
    }
}
