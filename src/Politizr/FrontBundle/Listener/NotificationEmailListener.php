<?php

namespace Politizr\FrontBundle\Listener;

use Symfony\Component\EventDispatcher\GenericEvent;

use Politizr\Exception\InconsistentDataException;

use Politizr\Model\PNotification;
use Politizr\Model\PUNotifications;

use Politizr\Model\PUserQuery;
use Politizr\Model\PRBadgeQuery;
use Politizr\Model\PUSubscribeEmailQuery;

/**
 *  Gestion des notifications par email
 *
 *  @author Lionel Bouzonville
 */
class NotificationEmailListener
{

    protected $logger;
    protected $eventDispatcher;

    /**
     *
     */
    public function __construct($logger, \Symfony\Component\EventDispatcher\EventDispatcher $eventDispatcher)
    {
        $this->logger = $logger;
        $this->eventDispatcher = $eventDispatcher;
    }


    /**
     * Evènement de notification: test pour envoi d'un email.
     *
     * @param GenericEvent
     */
    public function onNECheck(GenericEvent $event)
    {
        $this->logger->info('*** onNECheck');

        $puNotifications = $event->getSubject();

        // Récupération du user destinataire de l'email
        $user = $this->getDestPUser($puNotifications);

        // Contrôle user courant en ligne
        $isOnline = $this->isOnline();

        // Contrôle user courant abonné à cette notification
        $isSubscriber = $this->isSubscriber($puNotifications, $user);

        // Envoi de l'email
        if (!$isOnline && $isSubscriber) {
            $event = new GenericEvent($puNotifications, array('user_email' => $user->getEmail(),));
            $dispatcher =  $this->eventDispatcher->dispatch('notification_email', $event);
        }
    }


    // ******************************************************** //
    //                      Méthodes privées                    //
    // ******************************************************** //

    /**
     * Renvoit le PUser destinataire de l'email de notification.
     *
     * @param  PUNotifications  $puNotifications
     *
     * @return PUser
     */
    private function getDestPUser($puNotifications)
    {
        // Récupération du user destinataire de l'email
        $user = PUserQuery::create()->findPk($puNotifications->getPUserId());
        if (null === $user) {
            throw new InconsistentDataException('L\'utilisateur associé à la notification '.$puNotifications->getId().'n\'existe pas.');
        }

        return $user;
    }

    /**
     * Renvoit si l'utilisateur courant est connecté
     *
     * @return boolean
     */
    private function isOnline()
    {
        $isOnline = false;

        return $isOnline;
    }

    /**
     * Renvoit si l'utilisateur courant est abonné à cette notification.
     *
     * @param  PUNotifications      $puNotifications
     * @param  PUser                $user
     *
     * @return boolean
     */
    private function isSubscriber($puNotifications, $user)
    {
        $isSubscriber = PUSubscribeEmailQuery::create()
            ->filterByPNotificationId($puNotifications->getPNotificationId())
            ->filterByPUserId($user->getId())
            ->findOne();

        return $isSubscriber;
    }
}
