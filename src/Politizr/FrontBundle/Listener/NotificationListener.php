<?php

namespace Politizr\FrontBundle\Listener;

use Symfony\Component\EventDispatcher\GenericEvent;

use Politizr\Model\PNotification;
use Politizr\Model\PUNotifications;

use Politizr\Model\PRBadgeQuery;


/**
 * 	Gestion des actions mettant à jour la réputation
 *
 *  @author Lionel Bouzonville
 */
class NotificationListener {

    protected $logger;
    protected $eventDispatcher;

    /**
     *
     */
    public function __construct($logger, \Symfony\Component\EventDispatcher\EventDispatcher $eventDispatcher) {
        $this->logger = $logger;
    	$this->eventDispatcher = $eventDispatcher;
    }


    /**
     * Un commentaire a été publié sur un de vos documents
     *
     * @param GenericEvent
     */
    public function onNCommentPublish(GenericEvent $event) {
        $this->logger->info('*** onNCommentPublish');

        $subject = $event->getSubject();
        $authorUserId = $event->getArgument('author_user_id');
        $pNotificationId = PNotification::ID_D_COMMENT_PUBLISH;
        $objectName = get_class($subject);
        $objectId = $subject->getId();

        // Document associé
        $document = $subject->getPDocument();
        $targetUserId = $document->getPUserId();

        $this->insertPUNotifications($targetUserId, $authorUserId, $pNotificationId, $objectName, $objectId);
    }


    /**
     * Note positive sur un document ou commentaire
     *
     * @param GenericEvent
     */
    public function onNNotePos(GenericEvent $event) {
        $this->logger->info('*** onNNotePos');

        $subject = $event->getSubject();
        $authorUserId = $event->getArgument('author_user_id');

        $objectName = get_class($subject);
        $objectId = $subject->getId();

        // Document associé
        $targetUserId = $subject->getPUserId();

        switch($objectName) {
            case 'Politizr\Model\PDDebate':
            case 'Politizr\Model\PDReaction':
                $pNotificationId = PNotification::ID_D_NOTE_POS;
                break;
            case 'Politizr\Model\PDComment':
                $pNotificationId = PNotification::ID_D_C_NOTE_POS;
                break;
        }

        $this->insertPUNotifications($targetUserId, $authorUserId, $pNotificationId, $objectName, $objectId);        
    }

    /**
     * Note négative sur un document ou commentaire
     *
     * @param GenericEvent
     */
    public function onNNoteNeg(GenericEvent $event) {
        $this->logger->info('*** onNNoteNeg');

        $subject = $event->getSubject();
        $authorUserId = $event->getArgument('author_user_id');

        $objectName = get_class($subject);
        $objectId = $subject->getId();

        // Document associé
        $targetUserId = $subject->getPUserId();

        switch($objectName) {
            case 'Politizr\Model\PDDebate':
            case 'Politizr\Model\PDReaction':
                $pNotificationId = PNotification::ID_D_NOTE_NEG;
                break;
            case 'Politizr\Model\PDComment':
                $pNotificationId = PNotification::ID_D_C_NOTE_NEG;
                break;
        }

        $this->insertPUNotifications($targetUserId, $authorUserId, $pNotificationId, $objectName, $objectId);        
    }

    /**
     * Une réaction a été publiée sur un de vos débats / une de vos réactions
     *
     * @param GenericEvent
     */
    public function onNReactionPublish(GenericEvent $event) {
        $this->logger->info('*** onNDebateReactionPublish');

        $subject = $event->getSubject();
        $authorUserId = $event->getArgument('author_user_id');
        $objectName = get_class($subject);
        $objectId = $subject->getId();

        // Débat associé à la réaction
        $debate = $subject->getPDDebate();
        $debateUserId = $debate->getPUserId();
        $pNotificationId = PNotification::ID_D_D_REACTION_PUBLISH;

        $this->insertPUNotifications($debateUserId, $authorUserId, $pNotificationId, $objectName, $objectId);

        // Réaction associée à la réaction
        if ($subject->getTreeLevel() > 1) {
            $parent = $subject->getParent();

            $targetUserId = $parent->getPUserId();
            $pNotificationId = PNotification::ID_D_R_REACTION_PUBLISH;

            $this->insertPUNotifications($targetUserId, $authorUserId, $pNotificationId, $objectName, $objectId);
        }
    }


    /**
     * Un utilisateur suit un de vos débats
     *
     * @param GenericEvent
     */
    public function onNDebateFollow(GenericEvent $event) {
        $this->logger->info('*** onNDebateFollow');

        $subject = $event->getSubject();
        $authorUserId = $event->getArgument('author_user_id');
        $pNotificationId = PNotification::ID_D_D_FOLLOWED;

        $objectName = get_class($subject);
        $objectId = $subject->getId();

        // Auteur du débat
        $targetUserId = $subject->getPUserId();

        $this->insertPUNotifications($targetUserId, $authorUserId, $pNotificationId, $objectName, $objectId);

    }


    /**
     * Un utilisateur suit votre profil
     *
     * @param GenericEvent
     */
    public function onNUserFollow(GenericEvent $event) {
        $this->logger->info('*** onNUserFollow');

        $subject = $event->getSubject();
        $authorUserId = $event->getArgument('author_user_id');
        $pNotificationId = PNotification::ID_U_FOLLOWED;
        
        $objectName = get_class($subject);
        $objectId = $subject->getId();

        // User suivi
        $targetUserId = $subject->getId();

        $this->insertPUNotifications($targetUserId, $authorUserId, $pNotificationId, $objectName, $objectId);
    }



    /**
     * Vous avez obtenu un badge
     *
     * @param GenericEvent
     */
    public function onNBadgeWin(GenericEvent $event) {
        $this->logger->info('*** onNBadgeWin');

        $subject = $event->getSubject();
        $pNotificationId = PNotification::ID_U_BADGE;
        
        $targetUserId = $subject->getPUserId();
        $authorUserId = $targetUserId;

        // Récupération de l'objet badge gagné
        $badgeId = $subject->getPRBadgeId();
        $badge = PRBadgeQuery::create()->findPk($badgeId);
        $objectName = get_class($badge);
        $objectId = $badge->getId();

        $this->insertPUNotifications($targetUserId, $authorUserId, $pNotificationId, $objectName, $objectId);
    }


    // ******************************************************** //
    //                      Méthodes privées                    //
    // ******************************************************** //

    /**
     * Insertion en BDD
     *
     * @param
     */
    private function insertPUNotifications($userId, $authorUserId, $notificationId, $objectName, $objectId) {
        $this->logger->info('*** insertPUNotifications');
        $this->logger->info('userId = '.print_r($userId, true));
        $this->logger->info('authorUserId = '.print_r($authorUserId, true));
        $this->logger->info('notificationId = '.print_r($notificationId, true));
        $this->logger->info('objectName = '.print_r($objectName, true));
        $this->logger->info('objectId = '.print_r($objectId, true));

        $notif = new PUNotifications();

        $notif->setPUserId($userId);
        $notif->setPNotificationId($notificationId);
        $notif->setPObjectName($objectName);
        $notif->setPObjectId($objectId);
        $notif->setPAuthorUserId($authorUserId);
        $notif->setChecked(false);
        
        $notif->save();
    }
}
