<?php
namespace Politizr\CommandBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

// use Symfony\Component\Console\Command\Command as BaseCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Symfony\Component\EventDispatcher\GenericEvent;

use Politizr\Exception\InconsistentDataException;

use Politizr\Model\PUserQuery;

/**
 * Politizr email sending
 *
 * @author Lionel Bouzonville
 */
class EmailAccountNotificationsCommand extends ContainerAwareCommand
{
    private $logger;
    private $notificationService;
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
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $isVerbose = (OutputInterface::VERBOSITY_VERBOSE <= $output->getVerbosity());

        $this->logger = $this->getContainer()->get('logger');
        // $this->notificationService = $this->getContainer()->get('politizr.functional.notification');
        $this->mailer = $this->getContainer()->get('mailer');
        $this->transport = $this->getContainer()->get('swiftmailer.transport.real');
        $this->templating = $this->getContainer()->get('templating');

        $users = PUserQuery::create()
            ->distinct()
            ->online()
            ->joinPUSubscribePNE()
            ->find();

        foreach ($users as $user) {
            $output->write('.');
            $beginAt = new \DateTime('yesterday');
            $beginAt->setTime(17, 0, 1);
            $endAt = new \DateTime('now');
            $endAt->setTime(17, 0, 0);

            // $puNotifications = $this->notificationService->getUserNotifications($user, $beginAt, $endAt);
            // $this->accountNotificationEmail($user, $puNotifications);
        }

        $spool = $this->mailer->getTransport()->getSpool();
        $test = $spool->flushQueue($this->transport);

        $output->writeln(print_r($test, true));

        $output->writeln('<info>Send account notifications completed.</info>');
    }

    /**
     * Account notification.
     *
     * @param GenericEvent
     */
    private function accountNotificationEmail($user, $puNotifications)
    {
        $userEmail = $user->getEmail();

        try {
            $subject = 'X nouveaux followers, etc.';
            
            $htmlBody = 'Oh yeah';
            $txtBody = 'Oh yeah';

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

            // $this->logger->info('send = '.print_r($send, true));
            if (!$send) {
                throw new \Exception('email non envoyé - code retour = '.$send.' - adresse(s) en échec = '.print_r($failedRecipients, true));
            }
        } catch (\Exception $e) {
            $this->logger->err('Exception - message = '.$e->getMessage());
        }
    }
}
