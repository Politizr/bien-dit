<?php

namespace Politizr\FrontBundle\Listener;

use Symfony\Component\EventDispatcher\GenericEvent;

use Politizr\Exception\InconsistentDataException;

use Politizr\Model\PNotification;
use Politizr\Model\PUNotification;

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

        $puNotification = $event->getSubject();

        // Récupération du user destinataire de l'email
        $user = $this->getDestPUser($puNotification);

        // Contrôle user courant en ligne
        $isOnline = $this->isOnline($user);

        // Contrôle user courant abonné à cette notification
        $isSubscriber = $this->isSubscriber($puNotification, $user);

        // Envoi de l'email
        if (!$isOnline && $isSubscriber) {
            $event = new GenericEvent($puNotification, array('user_email' => $user->getEmail(),));
            $dispatcher =  $this->eventDispatcher->dispatch('notification_email', $event);
        }
    }


    // ******************************************************** //
    //                      Méthodes privées                    //
    // ******************************************************** //

    /**
     * Renvoit le PUser destinataire de l'email de notification.
     *
     * @param  PUNotification  $puNotification
     *
     * @return PUser
     */
    private function getDestPUser($puNotification)
    {
        // Récupération du user destinataire de l'email
        $user = PUserQuery::create()->findPk($puNotification->getPUserId());
        if (null === $user) {
            throw new InconsistentDataException('L\'utilisateur associé à la notification '.$puNotification->getId().'n\'existe pas.');
        }

        return $user;
    }

    /**
     * Renvoit si l'utilisateur courant est connecté
     *
     * @return boolean
     */
    private function isOnline($user)
    {
        return $user->isActiveNow();
    }

    /**
     * Renvoit si l'utilisateur courant est abonné à cette notification.
     *
     * @param  PUNotification      $puNotifications
     * @param  PUser                $user
     *
     * @return boolean
     */
    private function isSubscriber($puNotification, $user)
    {
        $isSubscriber = PUSubscribeEmailQuery::create()
            ->filterByPNotificationId($puNotification->getPNotificationId())
            ->filterByPUserId($user->getId())
            ->findOne();

        return $isSubscriber;
    }
}