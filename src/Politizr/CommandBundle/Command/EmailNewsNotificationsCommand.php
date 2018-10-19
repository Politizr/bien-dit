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
 * Politizr news notifications email sending
 *
 * https://github.com/Politizr/Politizr/wiki/Liste-des-notifications-mail
 * 
 * @author Lionel Bouzonville
 */
class EmailNewsNotificationsCommand extends ContainerAwareCommand
{
    private $logger;
    private $monitoringManager;
    private $notificationService;
    private $documentService;
    private $mailer;
    private $transport;
    private $templating;
    private $senderEmail;
    private $supportEmail;
    private $clientName;
    private $geoActive;

    protected function configure()
    {
        $this
            ->setName('politizr:email:news')
            ->setDescription('Send news notifications')
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
        $this->geoActive = $this->getContainer()->getParameter('geo_active');

        // initialize begin & end dates
        $beginAt = $input->getArgument('beginAt');
        if ($beginAt) {
            $beginAt = new \DateTime($beginAt);
        } else {
            $beginAt = new \DateTime('-1 week');
            $beginAt->setTime(18, 0, 1);
        }
        $endAt = $input->getArgument('endAt');
        if ($endAt) {
            $endAt = new \DateTime($endAt);
        } else {
            $endAt = new \DateTime('now');
            $endAt->setTime(18, 0, 0);
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
                ->filterByPNEmailId(EmailConstants::ID_ACTIVITY_SUMMARY)
            ->endUse()
            ->find();

        foreach ($users as $user) {
            // $output->write('.');

            $puNotifications = $this->notificationService->getUserNotificationsForEmailing($user, $beginAt, $endAt);

            if ($this->geoActive) {
                // Nearest elected users
                $nearestUsers = $this->notificationService->getNearestQualifiedUsers($user, $beginAt, $endAt, EmailConstants::NB_MAX_NEW_ELECTED_USERS);
            } else {
                // Neawest users
                $nearestUsers = $this->notificationService->getNewestUsers($user, $beginAt, $endAt, EmailConstants::NB_MAX_NEW_ELECTED_USERS);                
            }

            $nearestUsersPart = $this->getNearestUsersRendering($user, $nearestUsers);

            if ($this->geoActive) {
                // Nearest debates
                $nearestDebates = $this->notificationService->getNearestDebates($user, $beginAt, $endAt, EmailConstants::NB_MAX_NEW_PUBLICATIONS);
            } else {
                // Interesting debates
                $nearestDebates = $this->notificationService->getMostInterestingDebates($user, $beginAt, $endAt, EmailConstants::NB_MAX_NEW_PUBLICATIONS);
            }

            $nearestDebatesPart = $this->getNearestDebatesRendering($user, $nearestDebates);

            // Emailing
            if (sizeof($nearestUsersPart) > 0
                || sizeof($nearestDebatesPart) > 0
            ) {
                $subjectMessage = $this->newsNotificationEmail($user, $nearestUsersPart, $nearestDebatesPart);

                // monitoring
                if (sizeof($subjectMessage) > 0) {
                    $pmEmailing = new PMEmailing();
                    $pmEmailing->setPUserId($user->getId());
                    $pmEmailing->setPNEmailId(EmailConstants::ID_ACTIVITY_SUMMARY);
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
        $output->writeln(sprintf('<info>%s - Send news notifications completed. %s mails have been sent!</info>', $now->format('Y-m-d H:i:s'), $nbSent));
    }

    /**
     * Get nearest user's qualified users listing rendering
     *
     * @param PUser $user
     * @param PropelCollection $users
     */
    private function getNearestUsersRendering(PUser $user, $users)
    {
        $nearestUsersPartHtml = null;
        $nearestUsersPartTxt = null;

        if ($users && count($users) > 0) {
            $nearestUsersPartHtml = $this->templating->render(
                'PolitizrFrontBundle:Notification\\MessageEmail:_newsNearestUsers.html.twig',
                array(
                    'user' => $user,
                    'users' => $users,
                )
            );
            $nearestUsersPartTxt = $this->templating->render(
                'PolitizrFrontBundle:Notification\\MessageEmail\\Txt:_newsNearestUsers.txt.twig',
                array(
                    'user' => $user,
                    'users' => $users,
                )
            );
            return [$nearestUsersPartHtml, $nearestUsersPartTxt];
        }

        return array();
    }

    /**
     * Get nearest user's debates listing rendering
     *
     * @param PUser $user
     * @param PropelCollection $debates
     */
    private function getNearestDebatesRendering(PUser $user, $debates)
    {
        $nearestDebatesPartHtml = null;
        $nearestDebatesPartTxt = null;

        if ($debates && count($debates) > 0) {
            $nearestDebatesPartHtml = $this->templating->render(
                'PolitizrFrontBundle:Notification\\MessageEmail:_newsNearestDebates.html.twig',
                array(
                    'user' => $user,
                    'debates' => $debates,
                )
            );
            $nearestDebatesPartTxt = $this->templating->render(
                'PolitizrFrontBundle:Notification\\MessageEmail\\Txt:_newsNearestDebates.txt.twig',
                array(
                    'user' => $user,
                    'debates' => $debates,
                )
            );
            return [$nearestDebatesPartHtml, $nearestDebatesPartTxt];
        }

        return array();
    }

    /**
     * News notification.
     *
     * @param PUser $user
     * @param string $nearestUsersPart
     * @param string $nearestDebatesPart
     * @return array[subjectMessage,message,email]
     */
    private function newsNotificationEmail($user, $nearestUsersPart, $nearestDebatesPart)
    {
        $userEmail = $user->getEmail();

        // Initialize and retrieve html & txt templates, compute email subject
        $subject = "";
        $nearestUsersPartHtml = $nearestUsersPartTxt = null;
        if ($nearestUsersPart && sizeof($nearestUsersPart) > 0) {
            $nearestUsersPartHtml = $nearestUsersPart[0];
            $nearestUsersPartTxt = $nearestUsersPart[1];
            $subject = "de nouveaux utilisateurs";
        }

        $nearestDebatesPartHtml = $nearestDebatesPartTxt = null;
        if ($nearestDebatesPart && sizeof($nearestDebatesPart) > 0) {
            $nearestDebatesPartHtml = $nearestDebatesPart[0];
            $nearestDebatesPartTxt = $nearestDebatesPart[1];
            if (!empty($subject)) {
                $subject .= " et ";
            }
            $subject .= "de nouveaux sujets pour vous";
        }

        $subject .= "...";
        $subject = ucfirst($subject);

        // prepare email and sent it
        try {
            $htmlBody = $this->templating->render(
                'PolitizrFrontBundle:Email:newsNotification.html.twig',
                array(
                    'user' => $user,
                    'nearestUsersPart' => $nearestUsersPartHtml,
                    'nearestDebatesPart' => $nearestDebatesPartHtml,
                )
            );
            $txtBody = $this->templating->render(
                'PolitizrFrontBundle:Email:newsNotification.txt.twig',
                array(
                    'user' => $user,
                    'nearestUsersPart' => $nearestUsersPartTxt,
                    'nearestDebatesPart' => $nearestDebatesPartTxt,
                )
            );

            $message = \Swift_Message::newInstance()
                    ->setSubject($subject)
                    ->setFrom(array($this->senderEmail => sprintf('%s', $this->clientName)))
                    ->setTo($userEmail)
                    ->setBody($htmlBody, 'text/html', 'utf-8')
                    ->addPart($txtBody, 'text/plain', 'utf-8')
            ;
            $message->getHeaders()->addTextHeader('X-CMail-GroupName', 'News notification');

            // Envoi email
            $failedRecipients = array();
            $send = $this->mailer->send($message, $failedRecipients);

            return [$subject, $message, $userEmail];
        } catch (\Exception $e) {
            $this->logger->err(sprintf('Exception - EmailNewsNotificationCommand - message = %s', $e->getMessage()));
            $this->monitoringManager->createAppException($e);
        }
    }
}