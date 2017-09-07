<?php

namespace Politizr\FrontBundle\Listener;

use Symfony\Component\EventDispatcher\GenericEvent;

use Politizr\Exception\InconsistentDataException;

use Politizr\Model\PUser;
use Politizr\Model\PUNotification;

use Politizr\Model\PUserQuery;
use Politizr\Model\PUSubscribePNEQuery;

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
    public function __construct($logger, $eventDispatcher)
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
        $pnEmailId = $event->getArgument('p_n_email_id');

        if ($puNotification) {
            // Récupération du user destinataire de l'email
            $user = $this->getDestPUser($puNotification);

            if ($user) {
                // Contrôle user courant en ligne
                // $isOnline = $this->isOnline($user);

                // Contrôle user courant abonné à cette notification
                $isSubscriber = $this->isSubscriber($pnEmailId, $user->getId());

                // Envoi de l'email
                if ($isSubscriber) {
                    $event = new GenericEvent($puNotification, array('user' => $user, 'p_n_email_id' => $pnEmailId,));
                    $dispatcher =  $this->eventDispatcher->dispatch('notification_email', $event);
                }
            }
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
    private function getDestPUser(PUNotification $puNotification)
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
     * @param PUser $user
     * @return boolean
     */
    private function isOnline(PUser $user)
    {
        return $user->isActiveNow();
    }

    /**
     * Renvoit si l'utilisateur courant est abonné à cette notification.
     *
     * @param  integer $pnEmailId
     * @param  integer $userId
     * @return boolean
     */
    private function isSubscriber($pnEmailId, $userId)
    {
        $isSubscriber = PUSubscribePNEQuery::create()
            ->filterByPNEmailId($pnEmailId)
            ->filterByPUserId($userId)
            ->findOne();

        return $isSubscriber;
    }
}
