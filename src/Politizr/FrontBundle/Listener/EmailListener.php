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

    protected $contactEmail;
    protected $supportEmail;


    /**
     *
     * @param @mailer $mailer
     * @param @templating
     * @param @politizr.manager.monitoring
     * @param @logger
     * @param string $contactEmail
     * @param string $supportEmail
     */
    public function __construct($mailer, $templating, $monitoringManager, $logger, $contactEmail, $supportEmail)
    {
        $this->mailer = $mailer;
        $this->templating = $templating;
        $this->monitoringManager = $monitoringManager;
        $this->logger = $logger;

        $this->contactEmail = $contactEmail;
        $this->supportEmail = $supportEmail;
    }

    /**
     * Mot de passe de connexion perdu.
     *
     * @param GenericEvent
     */
    public function onLostPasswordEmail(GenericEvent $event)
    {
        $this->logger->info('*** onLostPasswordEmail');

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
                    ->setSubject('[ Politizr ] Réinitialisation du mot de passe')
                    ->setFrom(array($this->supportEmail => 'Support@Politizr'))
                    ->setTo($user->getEmail())
                    // ->setBcc(array('lionel@politizr.com'))
                    ->setBody($htmlBody, 'text/html', 'utf-8')
                    ->addPart($txtBody, 'text/plain', 'utf-8')
            ;
            $message->getHeaders()->addTextHeader('X-CMail-GroupName', 'Lost password');

            // Envoi email
            $failedRecipients = array();
            $send = $this->mailer->send($message, $failedRecipients);

            $this->logger->info('send = '.print_r($send, true));
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
        $this->logger->info('*** onUpdPasswordEmail');

        $user = $event->getSubject();
        try {
            $htmlBody = $this->templating->render(
                'PolitizrFrontBundle:Email:updatePassword.html.twig'
            );
            $txtBody = $this->templating->render(
                'PolitizrFrontBundle:Email:updatePassword.txt.twig'
            );

            $message = \Swift_Message::newInstance()
                    ->setSubject('[ Politizr ] Mise à jour de sécurité')
                    ->setFrom(array($this->supportEmail => 'Support@Politizr'))
                    ->setTo($user->getEmail())
                    // ->setBcc(array('lionel@politizr.com'))
                    ->setBody($htmlBody, 'text/html', 'utf-8')
                    ->addPart($txtBody, 'text/plain', 'utf-8')
            ;
            $message->getHeaders()->addTextHeader('X-CMail-GroupName', 'Update password');

            // Envoi email
            $failedRecipients = array();
            $send = $this->mailer->send($message, $failedRecipients);

            $this->logger->info('send = '.print_r($send, true));
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
        $this->logger->info('*** onMonitoringEmail');

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
                    ->setSubject('[ Politizr ] Monitoring')
                    ->setFrom(array($this->supportEmail => 'Support@Politizr'))
                    ->setTo(array($this->supportEmail => 'Support@Politizr'))
                    ->setBody($htmlBody, 'text/html', 'utf-8')
                    ->addPart($txtBody, 'text/plain', 'utf-8')
            ;
            $message->getHeaders()->addTextHeader('X-CMail-GroupName', 'Monitoring');

            // Envoi email
            $failedRecipients = array();
            $send = $this->mailer->send($message, $failedRecipients);

            $this->logger->info('send = '.print_r($send, true));
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
        $this->logger->info('*** onNotificationEmail');

        $puNotifications = $event->getSubject();
        $userEmail = $event->getArgument('user_email');

        try {
            $subject = $this->templating->render(
                'PolitizrFrontBundle:Email:_notifSubject.html.twig',
                array(
                    'notif' => $puNotifications,
                )
            );
            if (!$subject) {
                $subject = 'Nouvelle notification';
            }
            
            $htmlBody = $this->templating->render(
                'PolitizrFrontBundle:Email:notification.html.twig',
                array(
                    'notif' => $puNotifications,
                )
            );
            $txtBody = $this->templating->render(
                'PolitizrFrontBundle:Email:notification.txt.twig',
                array(
                    'notif' => $puNotifications,
                )
            );

            $message = \Swift_Message::newInstance()
                    ->setSubject($subject)
                    ->setFrom(array($this->supportEmail => 'Support@Politizr'))
                    ->setTo($userEmail)
                    // ->setBcc(array('lionel@politizr.com'))
                    ->setBody($htmlBody, 'text/html', 'utf-8')
                    ->addPart($txtBody, 'text/plain', 'utf-8')
            ;
            $message->getHeaders()->addTextHeader('X-CMail-GroupName', 'Notification');

            // Envoi email
            $failedRecipients = array();
            $send = $this->mailer->send($message, $failedRecipients);

            $this->logger->info('send = '.print_r($send, true));
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
        $this->logger->info('*** onWelcomeEmail');

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
                    ->setSubject('Bienvenue chez Politizr!')
                    ->setFrom(array($this->supportEmail => 'Support@Politizr'))
                    ->setTo($user->getEmail())
                    // ->setBcc(array('lionel@politizr.com'))
                    ->setBody($htmlBody, 'text/html', 'utf-8')
                    ->addPart($txtBody, 'text/plain', 'utf-8')
            ;
            $message->getHeaders()->addTextHeader('X-CMail-GroupName', 'Welcome');

            // Envoi email
            $failedRecipients = array();
            $send = $this->mailer->send($message, $failedRecipients);

            $this->logger->info('send = '.print_r($send, true));
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
        $this->logger->info('*** onIdcheckTraceEmail');

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
                    ->setFrom(array($this->supportEmail => 'Support@Politizr'))
                    ->setTo(array($this->supportEmail => 'Support@Politizr'))
                    // ->setBcc(array('lionel@politizr.com'))
                    ->setBody($htmlBody, 'text/html', 'utf-8')
            ;
            $message->getHeaders()->addTextHeader('X-CMail-GroupName', 'IdCheck');

            // Envoi email
            $failedRecipients = array();
            $send = $this->mailer->send($message, $failedRecipients);

            $this->logger->info('send = '.print_r($send, true));
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
