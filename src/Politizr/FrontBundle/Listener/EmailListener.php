<?php

namespace Politizr\FrontBundle\Listener;

use Politizr\Exception\SendEmailException;

use Symfony\Component\EventDispatcher\GenericEvent;

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
    protected $noreplyEmail;


    /**
     *
     * @param @mailer $mailer
     * @param @templating
     * @param @politizr.manager.monitoring
     * @param @logger
     * @param string $contactEmail
     * @param string $noreplyEmail
     */
    public function __construct($mailer, $templating, $monitoringManager, $logger, $contactEmail, $noreplyEmail)
    {
        $this->mailer = $mailer;
        $this->templating = $templating;
        $this->monitoringManager = $monitoringManager;
        $this->logger = $logger;

        $this->contactEmail = $contactEmail;
        $this->noreplyEmail = $noreplyEmail;
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
                    ->setSubject('Politizr - Réinitialisation du mot de passe')
                    ->setFrom(array($this->noreplyEmail => 'Politizr'))
                    ->setTo($user->getEmail())
                    // ->setBcc(array('lionel@politizr.com'))
                    ->setBody($htmlBody, 'text/html', 'utf-8')
                    ->addPart($txtBody, 'text/plain', 'utf-8')
            ;

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
                'PolitizrFrontBundle:Email:updatePassword.html.twig',
                array(
                    'username' => $user->getUsername(),
                    'password' => $user->getPlainPassword()
                )
            );
            $txtBody = $this->templating->render(
                'PolitizrFrontBundle:Email:updatePassword.txt.twig',
                array(
                    'username' => $user->getUsername(),
                    'password' => $user->getPlainPassword()
                )
            );

            $message = \Swift_Message::newInstance()
                    ->setSubject('Politizr - Mise à jour de sécurité')
                    ->setFrom(array($this->noreplyEmail => 'Politizr'))
                    ->setTo($user->getEmail())
                    // ->setBcc(array('lionel@politizr.com'))
                    ->setBody($htmlBody, 'text/html', 'utf-8')
                    ->addPart($txtBody, 'text/plain', 'utf-8')
            ;

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
            $htmlBody = $this->templating->render(
                'PolitizrFrontBundle:Email:notification.html.twig',
                array(
                    'notif' => $puNotifications,
                )
            );
            // $txtBody = $this->templating->render(
            //     'PolitizrFrontBundle:Email:notification.html.twig',
            //     array(
            //         'notif' => $puNotifications,
            //     )
            // );

            $message = \Swift_Message::newInstance()
                    ->setSubject('Vous avez une notification!')
                    ->setFrom(array($this->noreplyEmail => 'Politizr'))
                    ->setTo($userEmail)
                    // ->setBcc(array('lionel@politizr.com'))
                    ->setBody($htmlBody, 'text/html', 'utf-8')
                    // ->addPart($txtBody, 'text/plain', 'utf-8')
            ;

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
}
