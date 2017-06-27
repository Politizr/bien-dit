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

use Politizr\Constant\EmailConstants;
use Politizr\Constant\NotificationConstants;

/**
 * Politizr email sending
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
            $notePosPublications = $this->notificationService->getMostNotePosUserPublications($user, $beginAt, $endAt, EmailConstants::NB_MAX_INTERACTED_PUBLICATIONS);

            $userPublicationsPart = $this->getUserPublicationsRendering($interactedPublications, $notePosPublications, $output);
            $userPublicationsPartHtml = $userPublicationsPartTxt = null;
            if ($userPublicationsPart) {
                $userPublicationsPartHtml = $userPublicationsPart[0];
                $userPublicationsPartTxt = $userPublicationsPart[1];
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


            if ($badgesPart || $userPublicationsPart || $followersPart) {
                $message = $this->accountNotificationEmail($user, $badgesPartHtml, $badgesPartTxt, $userPublicationsPartHtml, $userPublicationsPartTxt, $followersPartHtml, $followersPartTxt);
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
        foreach ($badgeNotifications as $puNotification) {
            // Update attributes depending of context
            $attr = $this->documentService->computeDocumentContextAttributes(
                $puNotification->getPObjectName(),
                $puNotification->getPObjectId()
            );

            // $subject = $attr['subject'];
            // $title = $attr['title'];
            // $url = $attr['url'];
            // $document = $attr['document'];
            // $documentUrl = $attr['documentUrl'];

            $badges[]['title'] = $attr['title'];
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
     * @param array $interactedPublications
     * @param array $notePosPublications
     */
    private function getUserPublicationsRendering($interactedPublications, $notePosPublications, $output)
    {
        $userPublications = array();
        $userPublicationsPartHtml = null;
        $userPublicationsPartTxt = null;

        if (count($interactedPublications) > 0 || count($notePosPublications) > 0) {
            $userPublicationsPartHtml = $this->templating->render(
                'PolitizrFrontBundle:Notification\\MessageEmail:_accountUserPublications.html.twig',
                array(
                    'interactedPublications' => $interactedPublications,
                    'notePosPublications' => $notePosPublications,
                )
            );
            $userPublicationsPartTxt = $this->templating->render(
                'PolitizrFrontBundle:Notification\\MessageEmail\\Txt:_accountUserPublications.txt.twig',
                array(
                    'interactedPublications' => $interactedPublications,
                    'notePosPublications' => $notePosPublications,
                )
            );
            return [$userPublicationsPartHtml, $userPublicationsPartTxt];
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
        foreach ($followerNotifications as $puNotification) {
            // Update attributes depending of context
            $attr = $this->documentService->computeDocumentContextAttributes(
                $puNotification->getPObjectName(),
                $puNotification->getPObjectId()
            );

            // $subject = $attr['subject'];
            // $title = $attr['title'];
            // $url = $attr['url'];
            // $document = $attr['document'];
            // $documentUrl = $attr['documentUrl'];

            $followers[]['title'] = $attr['title'];
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
     * @param string $userPublicationsPartHtml
     * @param string $userPublicationsPartTxt
     */
    private function accountNotificationEmail($user, $badgesPartHtml, $badgesPartTxt, $userPublicationsPartHtml, $userPublicationsPartTxt, $followersPartHtml, $followersPartTxt)
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
                    'followersPart' => $followersPartHtml,
                )
            );
            $txtBody = $this->templating->render(
                'PolitizrFrontBundle:Email:accountNotification.txt.twig',
                array(
                    'qualified' => $user->isQualified(),
                    'badgesPart' => $badgesPartTxt,
                    'userPublicationsPart' => $userPublicationsPartTxt,
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
