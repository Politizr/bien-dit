<?php

namespace Politizr\FrontBundle\Listener;

use Symfony\Component\EventDispatcher\GenericEvent;

use Politizr\Exception\SendEmailException;

/**
 *  Envoi des emails
 *
 *  @author Lionel Bouzonville
 */
class EmailListener
{

    protected $mailer;
    protected $templating;
    protected $monitoringManager;
    protected $logger;

    protected $senderEmail;
    protected $contactEmail;
    protected $supportEmail;
    protected $clientName;

    /**
     *
     * @param @mailer $mailer
     * @param @templating
     * @param @politizr.manager.monitoring
     * @param @logger
     * @param %sender_email%
     * @param %contact_email%
     * @param %support_email%
     * @param %client_name%
     */
    public function __construct(
        $mailer,
        $templating,
        $monitoringManager,
        $logger,
        $senderEmail,
        $contactEmail,
        $supportEmail,
        $clientName
    ) {
        $this->mailer = $mailer;
        $this->templating = $templating;
        $this->monitoringManager = $monitoringManager;
        $this->logger = $logger;

        $this->senderEmail = $senderEmail;
        $this->contactEmail = $contactEmail;
        $this->supportEmail = $supportEmail;
        $this->clientName = $clientName;
    }

    /**
     * Mot de passe de connexion perdu.
     *
     * @param GenericEvent
     */
    public function onLostPasswordEmail(GenericEvent $event)
    {
        // $this->logger->info('*** onLostPasswordEmail');

        $user = $event->getSubject();
        try {
            $htmlBody = $this->templating->render(
                'PolitizrFrontBundle:Email:lostPassword.html.twig',
                array(
                    'username' => $user->getUsername(),
                    'password' => $user->getPlainPassword()
                )
            );
            $txtBody = $this->templating->render(
                'PolitizrFrontBundle:Email:lostPassword.txt.twig',
                array(
                    'username' => $user->getUsername(),
                    'password' => $user->getPlainPassword()
                )
            );

            $message = \Swift_Message::newInstance()
                    ->setSubject(sprintf('[ %s ] Réinitialisation du mot de passe', $this->clientName))
                    ->setFrom(array($this->senderEmail => sprintf('%s', $this->clientName)))
                    ->setTo($user->getEmail())
                    // ->setBcc(array('lionel@politizr.com'))
                    ->setBody($htmlBody, 'text/html', 'utf-8')
                    ->addPart($txtBody, 'text/plain', 'utf-8')
            ;
            $message->getHeaders()->addTextHeader('X-CMail-GroupName', 'Lost password');

            // Envoi email
            $failedRecipients = array();
            $send = $this->mailer->send($message, $failedRecipients);

            // $this->logger->info('send = '.print_r($send, true));
            if (!$send) {
                throw new \Exception('email non envoyé - code retour = '.$send.' - adresse(s) en échec = '.print_r($failedRecipients, true));
            }
        } catch (\Exception $e) {
            if (null !== $this->logger) {
                $this->logger->err('Exception - message = '.$e->getMessage());
            }
            
            throw new SendEmailException($e->getMessage(), $e);
        }
    }

    /**
     * Mise à jour du mot de passe.
     *
     * @param GenericEvent
     */
    public function onUpdPasswordEmail(GenericEvent $event)
    {
        // $this->logger->info('*** onUpdPasswordEmail');

        $user = $event->getSubject();
        try {
            $htmlBody = $this->templating->render(
                'PolitizrFrontBundle:Email:updatePassword.html.twig'
            );
            $txtBody = $this->templating->render(
                'PolitizrFrontBundle:Email:updatePassword.txt.twig'
            );

            $message = \Swift_Message::newInstance()
                    ->setSubject(sprintf('[ %s ] Mise à jour de sécurité', $this->clientName))
                    ->setFrom(array($this->senderEmail => sprintf('%s', $this->clientName)))
                    ->setTo($user->getEmail())
                    // ->setBcc(array('lionel@politizr.com'))
                    ->setBody($htmlBody, 'text/html', 'utf-8')
                    ->addPart($txtBody, 'text/plain', 'utf-8')
            ;
            $message->getHeaders()->addTextHeader('X-CMail-GroupName', 'Update password');

            // Envoi email
            $failedRecipients = array();
            $send = $this->mailer->send($message, $failedRecipients);

            // $this->logger->info('send = '.print_r($send, true));
            if (!$send) {
                throw new \Exception('email non envoyé - code retour = '.$send.' - adresse(s) en échec = '.print_r($failedRecipients, true));
            }
        } catch (\Exception $e) {
            if (null !== $this->logger) {
                $this->logger->err('Exception - message = '.$e->getMessage());
            }
            
            throw new SendEmailException($e->getMessage(), $e);
        }
    }

    /**
     * Monitoring.
     *
     * @param GenericEvent
     */
    public function onMonitoringEmail(GenericEvent $event)
    {
        // $this->logger->info('*** onMonitoringEmail');

        // subject implements PMonitoredInterface
        $monitored = $event->getSubject();

        try {
            $htmlBody = $this->templating->render(
                'PolitizrFrontBundle:Email:monitoring.html.twig',
                array(
                    'monitored' => $monitored,
                )
            );
            $txtBody = $this->templating->render(
                'PolitizrFrontBundle:Email:monitoring.txt.twig',
                array(
                    'monitored' => $monitored,
                )
            );

            $message = \Swift_Message::newInstance()
                    ->setSubject(sprintf('[ %s ] Monitoring', $this->clientName))
                    ->setFrom(array($this->senderEmail => sprintf('%s', $this->clientName)))
                    ->setTo(array($this->contactEmail => sprintf('%s', $this->clientName)))
                    ->setBody($htmlBody, 'text/html', 'utf-8')
                    ->addPart($txtBody, 'text/plain', 'utf-8')
            ;
            $message->getHeaders()->addTextHeader('X-CMail-GroupName', 'Monitoring');

            // Envoi email
            $failedRecipients = array();
            $send = $this->mailer->send($message, $failedRecipients);

            // $this->logger->info('send = '.print_r($send, true));
            if (!$send) {
                throw new \Exception('email non envoyé - code retour = '.$send.' - adresse(s) en échec = '.print_r($failedRecipients, true));
            }
        } catch (\Exception $e) {
            $this->logger->err('Exception - message = '.$e->getMessage());
            $pmAppException = $this->monitoringManager->createAppException($e);
        }
    }

    /**
     * Notification.
     *
     * @param GenericEvent
     */
    public function onNotificationEmail(GenericEvent $event)
    {
        // $this->logger->info('*** onNotificationEmail');

        $puNotifications = $event->getSubject();
        $user = $event->getArgument('user');
        $pnEmailId = $event->getArgument('p_n_email_id');

        $userEmail = $user->getEmail();

        try {
            $subject = $this->templating->render(
                'PolitizrFrontBundle:Email:_notifSubject.html.twig',
                array(
                    'notif' => $puNotifications,
                    'pnEmailId' => $pnEmailId,
                )
            );
            if (!$subject) {
                $subject = 'Nouvelle notification';
            }
            
            $htmlBody = $this->templating->render(
                'PolitizrFrontBundle:Email:notification.html.twig',
                array(
                    'notif' => $puNotifications,
                    'pnEmailId' => $pnEmailId,
                    'qualified' => $user->isQualified(),
                    'user' => $user,
                )
            );
            $txtBody = $this->templating->render(
                'PolitizrFrontBundle:Email:notification.txt.twig',
                array(
                    'notif' => $puNotifications,
                    'pnEmailId' => $pnEmailId,
                    'qualified' => $user->isQualified(),
                    'user' => $user,
                )
            );

            $message = \Swift_Message::newInstance()
                    ->setSubject($subject)
                    ->setFrom(array($this->senderEmail => sprintf('%s', $this->clientName)))
                    ->setTo($userEmail)
                    // ->setBcc(array('lionel@politizr.com'))
                    ->setBody($htmlBody, 'text/html', 'utf-8')
                    ->addPart($txtBody, 'text/plain', 'utf-8')
            ;
            $message->getHeaders()->addTextHeader('X-CMail-GroupName', 'Notification');

            // Envoi email
            $failedRecipients = array();
            $send = $this->mailer->send($message, $failedRecipients);

            // $this->logger->info('send = '.print_r($send, true));
            if (!$send) {
                throw new \Exception('email non envoyé - code retour = '.$send.' - adresse(s) en échec = '.print_r($failedRecipients, true));
            }
        } catch (\Exception $e) {
            $this->logger->err('Exception - message = '.$e->getMessage());

            $pmAppException = $this->monitoringManager->createAppException($e);
        }
    }

    /**
     * Post connection email
     *
     * @param GenericEvent
     */
    public function onWelcomeEmail(GenericEvent $event)
    {
        // $this->logger->info('*** onWelcomeEmail');

        $user = $event->getSubject();
        try {
            $htmlBody = $this->templating->render(
                'PolitizrFrontBundle:Email:welcome.html.twig',
                array(
                    'user' => $user,
                )
            );
            $txtBody = $this->templating->render(
                'PolitizrFrontBundle:Email:welcome.txt.twig',
                array(
                    'user' => $user,
                )
            );

            $message = \Swift_Message::newInstance()
                    ->setSubject(sprintf('Bienvenue sur notre site de consultation', $this->clientName))
                    ->setFrom(array($this->senderEmail => sprintf('%s', $this->clientName)))
                    ->setTo($user->getEmail())
                    // ->setBcc(array('lionel@politizr.com'))
                    ->setBody($htmlBody, 'text/html', 'utf-8')
                    ->addPart($txtBody, 'text/plain', 'utf-8')
            ;
            $message->getHeaders()->addTextHeader('X-CMail-GroupName', 'Welcome');

            // Envoi email
            $failedRecipients = array();
            $send = $this->mailer->send($message, $failedRecipients);

            // $this->logger->info('send = '.print_r($send, true));
            if (!$send) {
                throw new \Exception('email non envoyé - code retour = '.$send.' - adresse(s) en échec = '.print_r($failedRecipients, true));
            }
        } catch (\Exception $e) {
            if (null !== $this->logger) {
                $this->logger->err('Exception - message = '.$e->getMessage());
            }
            
            throw new SendEmailException($e->getMessage(), $e);
        }
    }

    /**
     * Post idcheck trace email
     *
     * @param GenericEvent
     */
    public function onIdcheckTraceEmail(GenericEvent $event)
    {
        // $this->logger->info('*** onIdcheckTraceEmail');

        $lastResult = $event->getSubject();
        try {
            $htmlBody = $this->templating->render(
                'PolitizrFrontBundle:Email:idcheckTrace.html.twig',
                array(
                    'trace' => var_export($lastResult, true),
                )
            );

            $message = \Swift_Message::newInstance()
                    ->setSubject('Trace ID Check')
                    ->setFrom(array($this->senderEmail => sprintf('%s', $this->clientName)))
                    ->setTo(array($this->senderEmail => sprintf('%s', $this->clientName)))
                    // ->setBcc(array('lionel@politizr.com'))
                    ->setBody($htmlBody, 'text/html', 'utf-8')
            ;
            $message->getHeaders()->addTextHeader('X-CMail-GroupName', 'IdCheck');

            // Envoi email
            $failedRecipients = array();
            $send = $this->mailer->send($message, $failedRecipients);

            // $this->logger->info('send = '.print_r($send, true));
            if (!$send) {
                throw new \Exception('email non envoyé - code retour = '.$send.' - adresse(s) en échec = '.print_r($failedRecipients, true));
            }
        } catch (\Exception $e) {
            if (null !== $this->logger) {
                $this->logger->err('Exception - message = '.$e->getMessage());
            }
            
            throw new SendEmailException($e->getMessage(), $e);
        }
    }


    /**
     * Direct message email
     *
     * @param GenericEvent
     */
    public function onDirectMessageEmail(GenericEvent $event)
    {
        // $this->logger->info('*** onDirectMessageEmail');

        $directMessage = $event->getSubject();
        try {
            $htmlBody = $this->templating->render(
                'PolitizrFrontBundle:Email:directMessage.html.twig',
                array(
                    'directMessage' => $directMessage,
                )
            );

            $message = \Swift_Message::newInstance()
                    ->setSubject('Message direct')
                    ->setFrom(array($this->senderEmail => sprintf('%s', $this->clientName)))
                    ->setTo(array($this->contactEmail => sprintf('%s', $this->clientName)))
                    // ->setBcc(array('lionel@politizr.com'))
                    ->setBody($htmlBody, 'text/html', 'utf-8')
            ;
            $message->getHeaders()->addTextHeader('X-CMail-GroupName', 'DirectMessage');

            // Envoi email
            $failedRecipients = array();
            $send = $this->mailer->send($message, $failedRecipients);

            // $this->logger->info('send = '.print_r($send, true));
            if (!$send) {
                throw new \Exception('email non envoyé - code retour = '.$send.' - adresse(s) en échec = '.print_r($failedRecipients, true));
            }
        } catch (\Exception $e) {
            if (null !== $this->logger) {
                $this->logger->err('Exception - message = '.$e->getMessage());
            }
            
            throw new SendEmailException($e->getMessage(), $e);
        }
    }

    /**
     * Boost FB message email
     *
     * @param GenericEvent
     */
    public function onBoostFbEmail(GenericEvent $event)
    {
        // $this->logger->info('*** onBoostFbEmail');

        $document = $event->getSubject();
        try {
            $htmlBody = $this->templating->render(
                'PolitizrFrontBundle:Email:boostFb.html.twig',
                array(
                    'document' => $document,
                )
            );

            $message = \Swift_Message::newInstance()
                    ->setSubject('Boost document')
                    ->setFrom(array($this->senderEmail => sprintf('%s', $this->clientName)))
                    ->setTo(array($this->contactEmail => sprintf('%s', $this->clientName)))
                    // ->setBcc(array('lionel@politizr.com'))
                    ->setBody($htmlBody, 'text/html', 'utf-8')
            ;
            $message->getHeaders()->addTextHeader('X-CMail-GroupName', 'BoostDoc');

            // Envoi email
            $failedRecipients = array();
            $send = $this->mailer->send($message, $failedRecipients);

            // $this->logger->info('send = '.print_r($send, true));
            if (!$send) {
                throw new \Exception('email non envoyé - code retour = '.$send.' - adresse(s) en échec = '.print_r($failedRecipients, true));
            }
        } catch (\Exception $e) {
            if (null !== $this->logger) {
                $this->logger->err('Exception - message = '.$e->getMessage());
            }
            
            throw new SendEmailException($e->getMessage(), $e);
        }
    }

    /**
     * Support group message email
     *
     * @param GenericEvent
     */
    public function onSupportGroupEmail(GenericEvent $event)
    {
        // $this->logger->info('*** onSupportGroupEmail');

        $user = $event->getSubject();
        try {
            $htmlBody = $this->templating->render(
                'PolitizrFrontBundle:Email:supportGroup.html.twig',
                array(
                    'user' => $user,
                )
            );

            $message = \Swift_Message::newInstance()
                    ->setSubject('Support groupe')
                    ->setFrom(array($this->senderEmail => sprintf('%s', $this->clientName)))
                    ->setTo(array($this->contactEmail => sprintf('%s', $this->clientName)))
                    // ->setBcc(array('lionel@politizr.com'))
                    ->setBody($htmlBody, 'text/html', 'utf-8')
            ;
            $message->getHeaders()->addTextHeader('X-CMail-GroupName', 'SupportGroup');

            // Envoi email
            $failedRecipients = array();
            $send = $this->mailer->send($message, $failedRecipients);

            // $this->logger->info('send = '.print_r($send, true));
            if (!$send) {
                throw new \Exception('email non envoyé - code retour = '.$send.' - adresse(s) en échec = '.print_r($failedRecipients, true));
            }
        } catch (\Exception $e) {
            if (null !== $this->logger) {
                $this->logger->err('Exception - message = '.$e->getMessage());
            }
            
            throw new SendEmailException($e->getMessage(), $e);
        }
    }
}
