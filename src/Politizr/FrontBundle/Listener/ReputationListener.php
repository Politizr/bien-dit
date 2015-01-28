<?php

namespace Politizr\FrontBundle\Listener;

use Symfony\Component\EventDispatcher\GenericEvent;

use Politizr\Model\PRAction;
use Politizr\Model\PRBadge;
use Politizr\Model\PDocument;
use Politizr\Model\PUReputationRA;

/**
 * 	Gestion des actions mettant à jour la réputation
 *
 *  @author Lionel Bouzonville
 */
class ReputationListener {

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
     * Publication d'un débat
     *
     * @param GenericEvent
     */
    public function onRDebatePublish(GenericEvent $event) {
        $this->logger->info('*** onRDebatePublish');

        $subject = $event->getSubject();
        $userId = $event->getArgument('user_id');
        $prActionId = PRAction::ID_D_DEBATE_PUBLISH;
        $objectName = get_class($subject);
        $objectId = $subject->getId();

        $this->insertPUReputationRA($userId, $prActionId, $objectName, $objectId);
    }

    /**
     * Publication d'une réaction
     *
     * @param GenericEvent
     */
    public function onRReactionPublish(GenericEvent $event) {
        $this->logger->info('*** onRReactionPublish');

        // Réaction de la réaction
        $subject = $event->getSubject();

        $userId = $event->getArgument('user_id');
        $prActionId = PRAction::ID_D_REACTION_PUBLISH;
        $objectName = get_class($subject);
        $objectId = $subject->getId();

        $this->insertPUReputationRA($userId, $prActionId, $objectName, $objectId);

        // Débat associé à la réaction
        $debate = $subject->getPDDebate();

        $debateUserId = $debate->getPUserId();
        $prActionId = PRAction::ID_D_TARGET_DEBATE_REACTION_PUBLISH;
        $objectName = get_class($debate);
        $objectId = $debate->getId();

        $this->insertPUReputationRA($debateUserId, $prActionId, $objectName, $objectId);


        // Réaction associée à la réaction
        if ($subject->getTreeLevel() > 1) {
            $parent = $subject->getParent();

            $parentUserId = $parent->getPUserId();
            $prActionId = PRAction::ID_D_TARGET_REACTION_REACTION_PUBLISH;
            $objectName = get_class($parent);
            $objectId = $parent->getId();

            $this->insertPUReputationRA($parentUserId, $prActionId, $objectName, $objectId);
        } else {
            $parentUserId = $debate->getPUserId();
        }

        // Badges associés
        $event = new GenericEvent($subject, array('author_user_id' => $userId, 'parent_user_id' => $parentUserId));
        $dispatcher = $this->eventDispatcher->dispatch('b_reaction_publish', $event);        
    }

    /**
     * Publication d'un commentaire
     *
     * @param GenericEvent
     */
    public function onRCommentPublish(GenericEvent $event) {
        $this->logger->info('*** onRCommentPublish');

        $subject = $event->getSubject();
        $userId = $event->getArgument('user_id');
        $prActionId = PRAction::ID_D_COMMENT_PUBLISH;
        $objectName = get_class($subject);
        $objectId = $subject->getId();

        $this->insertPUReputationRA($userId, $prActionId, $objectName, $objectId);

        // Document associé au commentaire
        $document = $subject->getPDocument();

        $targetUserId = $document->getPUserId();
        switch ($document->getType()) {
            case PDocument::TYPE_DEBATE:
                $prActionId = PRAction::ID_D_TARGET_DEBATE_COMMENT_PUBLISH;
                $this->insertPUReputationRA($targetUserId, $prActionId, $objectName, $objectId);
                break;
            case PDocument::TYPE_REACTION:
                $prActionId = PRAction::ID_D_TARGET_REACTION_COMMENT_PUBLISH;
                $this->insertPUReputationRA($targetUserId, $prActionId, $objectName, $objectId);
                break;
        }

        // Badges associés
        $event = new GenericEvent($subject, array('author_user_id' => $userId));
        $dispatcher = $this->eventDispatcher->dispatch('b_comment_publish', $event);
    }


    /**
     * Note positive sur un document
     *
     * @param GenericEvent
     */
    public function onRNotePos(GenericEvent $event) {
        $this->logger->info('*** onRNotePos');

        $subject = $event->getSubject();
        $userId = $event->getArgument('user_id');

        $objectName = get_class($subject);
        $objectId = $subject->getId();

        switch($objectName) {
            case 'Politizr\Model\PDDebate':
                $prActionId = PRAction::ID_D_AUTHOR_DEBATE_NOTE_POS;

                // Auteur associé
                $userIdAuthor = $subject->getPUserId();
                $prActionIdAuthor = PRAction::ID_D_TARGET_DEBATE_NOTE_POS;

                $this->insertPUReputationRA($userId, $prActionId, $objectName, $objectId);
                $this->insertPUReputationRA($userIdAuthor, $prActionIdAuthor, $objectName, $objectId);

                // Badges associés
                $event = new GenericEvent($subject, array('author_user_id' => $userId, 'target_user_id' => $subject->getPUserId()));
                $dispatcher = $this->eventDispatcher->dispatch('b_document_note_pos', $event);

                break;
            case 'Politizr\Model\PDReaction':
                $prActionId = PRAction::ID_D_AUTHOR_REACTION_NOTE_POS;

                // Auteur associé
                $userIdAuthor = $subject->getPUserId();
                $prActionIdAuthor = PRAction::ID_D_TARGET_REACTION_NOTE_POS;

                $this->insertPUReputationRA($userId, $prActionId, $objectName, $objectId);
                $this->insertPUReputationRA($userIdAuthor, $prActionIdAuthor, $objectName, $objectId);

                // Badges associés
                $event = new GenericEvent($subject, array('author_user_id' => $userId, 'target_user_id' => $subject->getPUserId()));
                $dispatcher = $this->eventDispatcher->dispatch('b_document_note_pos', $event);

                break;
            case 'Politizr\Model\PDComment':
                $prActionId = PRAction::ID_D_AUTHOR_COMMENT_NOTE_POS;

                // Auteur associé
                $userIdAuthor = $subject->getPUserId();
                $prActionIdAuthor = PRAction::ID_D_TARGET_COMMENT_NOTE_POS;

                $this->insertPUReputationRA($userId, $prActionId, $objectName, $objectId);
                $this->insertPUReputationRA($userIdAuthor, $prActionIdAuthor, $objectName, $objectId);

                // Badges associés
                $event = new GenericEvent($subject, array('author_user_id' => $userId, 'target_user_id' => $subject->getPUserId()));
                $dispatcher = $this->eventDispatcher->dispatch('b_comment_note_pos', $event);
                
                break;
        } 
    }

    /**
     * Note négative sur un document
     *
     * @param GenericEvent
     */
    public function onRNoteNeg(GenericEvent $event) {
        $this->logger->info('*** onRNoteNeg');

        $subject = $event->getSubject();
        $userId = $event->getArgument('user_id');

        $objectName = get_class($subject);
        $objectId = $subject->getId();

        switch(get_class($subject)) {
            case 'Politizr\Model\PDDebate':
                $prActionId = PRAction::ID_D_AUTHOR_DEBATE_NOTE_NEG;

                // Auteur associé
                $userIdAuthor = $subject->getPUserId();
                $prActionIdAuthor = PRAction::ID_D_TARGET_DEBATE_NOTE_NEG;

                $this->insertPUReputationRA($userId, $prActionId, $objectName, $objectId);
                $this->insertPUReputationRA($userIdAuthor, $prActionIdAuthor, $objectName, $objectId);

                // Badges associés
                $event = new GenericEvent($subject, array('author_user_id' => $userId, 'target_user_id' => $subject->getPUserId()));
                $dispatcher = $this->eventDispatcher->dispatch('b_document_note_neg', $event);

                break;
            case 'Politizr\Model\PDReaction':
                $prActionId = PRAction::ID_D_AUTHOR_REACTION_NOTE_NEG;

                // Auteur associé
                $userIdAuthor = $subject->getPUserId();
                $prActionIdAuthor = PRAction::ID_D_TARGET_REACTION_NOTE_NEG;

                $this->insertPUReputationRA($userId, $prActionId, $objectName, $objectId);
                $this->insertPUReputationRA($userIdAuthor, $prActionIdAuthor, $objectName, $objectId);

                // Badges associés
                $event = new GenericEvent($subject, array('author_user_id' => $userId, 'target_user_id' => $subject->getPUserId()));
                $dispatcher = $this->eventDispatcher->dispatch('b_document_note_neg', $event);

                break;
            case 'Politizr\Model\PDComment':
                $prActionId = PRAction::ID_D_AUTHOR_COMMENT_NOTE_NEG;

                // Auteur associé
                $userIdAuthor = $subject->getPUserId();
                $prActionIdAuthor = PRAction::ID_D_TARGET_COMMENT_NOTE_NEG;

                $this->insertPUReputationRA($userId, $prActionId, $objectName, $objectId);
                $this->insertPUReputationRA($userIdAuthor, $prActionIdAuthor, $objectName, $objectId);

                // Badges associés
                $event = new GenericEvent($subject, array('author_user_id' => $userId, 'target_user_id' => $subject->getPUserId()));
                $dispatcher = $this->eventDispatcher->dispatch('b_comment_note_neg', $event);
                
                break;
        } 
    }


    /**
     * Suivi d'un débat
     *
     * @param GenericEvent
     */
    public function onRDebateFollow(GenericEvent $event) {
        $this->logger->info('*** onRDebateFollow');

        $subject = $event->getSubject();
        $userId = $event->getArgument('user_id');
        $prActionId = PRAction::ID_D_AUTHOR_DEBATE_FOLLOW;

        $objectName = get_class($subject);
        $objectId = $subject->getId();

        $this->insertPUReputationRA($userId, $prActionId, $objectName, $objectId);

        // Auteur du débat
        $userId = $subject->getPUserId();
        $prActionId = PRAction::ID_D_TARGET_DEBATE_FOLLOW;

        $this->insertPUReputationRA($userId, $prActionId, $objectName, $objectId);

    }

    /**
     * Suivi d'un user
     *
     * @param GenericEvent
     */
    public function onRUserFollow(GenericEvent $event) {
        $this->logger->info('*** onRUserFollow');

        $subject = $event->getSubject();
        $userId = $event->getArgument('user_id');
        $prActionId = PRAction::ID_U_AUTHOR_USER_FOLLOW;
        
        $objectName = get_class($subject);
        $objectId = $subject->getId();

        $this->insertPUReputationRA($userId, $prActionId, $objectName, $objectId);

        // User suivi
        $userId = $subject->getId();
        $prActionId = PRAction::ID_U_TARGET_USER_FOLLOW;

        $this->insertPUReputationRA($userId, $prActionId, $objectName, $objectId);
    }

    /**
     * Ne plus suivre un débat
     *
     * @param GenericEvent
     */
    public function onRDebateUnfollow(GenericEvent $event) {
        $this->logger->info('*** onRDebateUnfollow');

        $subject = $event->getSubject();
        $userId = $event->getArgument('user_id');
        $prActionId = PRAction::ID_D_AUTHOR_DEBATE_UNFOLLOW;

        $objectName = get_class($subject);
        $objectId = $subject->getId();

        $this->insertPUReputationRA($userId, $prActionId, $objectName, $objectId);

        // Auteur du débat
        $userId = $subject->getPUserId();
        $prActionId = PRAction::ID_D_TARGET_DEBATE_UNFOLLOW;

        $this->insertPUReputationRA($userId, $prActionId, $objectName, $objectId);

    }

    /**
     * Ne plus suivre un user
     *
     * @param GenericEvent
     */
    public function onRUserUnfollow(GenericEvent $event) {
        $this->logger->info('*** onRUserUnfollow');

        $subject = $event->getSubject();
        $userId = $event->getArgument('user_id');
        $prActionId = PRAction::ID_U_AUTHOR_USER_UNFOLLOW;
        
        $objectName = get_class($subject);
        $objectId = $subject->getId();

        $this->insertPUReputationRA($userId, $prActionId, $objectName, $objectId);

        // User suivi
        $userId = $subject->getId();
        $prActionId = PRAction::ID_U_TARGET_USER_UNFOLLOW;

        $this->insertPUReputationRA($userId, $prActionId, $objectName, $objectId);
    }


    // ******************************************************** //
    //                      Méthodes privées                    //
    // ******************************************************** //


    /**
     * Insertion en BDD
     *
     * @param
     */
    private function insertPUReputationRA($userId, $prActionId, $objectName, $objectId) {
        $this->logger->info('*** insertPUReputationRA');
        $this->logger->info('userId = '.print_r($userId, true));
        $this->logger->info('prActionId = '.print_r($prActionId, true));
        $this->logger->info('objectName = '.print_r($objectName, true));
        $this->logger->info('objectId = '.print_r($objectId, true));

        $userRepAction = new PUReputationRA();

        $userRepAction->setPUserId($userId);
        $userRepAction->setPRActionId($prActionId);
        $userRepAction->setPObjectName($objectName);
        $userRepAction->setPObjectId($objectId);
        
        $userRepAction->save();
    }
}
