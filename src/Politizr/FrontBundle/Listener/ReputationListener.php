<?php

namespace Politizr\FrontBundle\Listener;

use Symfony\Component\EventDispatcher\GenericEvent;

use Politizr\Model\PRAction;
use Politizr\Model\PUReputationRA;

/**
 * 	Gestion des actions mettant à jour la réputation
 *
 *  @author Lionel Bouzonville
 */
class ReputationListener {

    protected $logger;

    /**
     *
     */
    public function __construct($logger) {
    	$this->logger = $logger;
    }


    /**
     * Publication d'un débat
     *
     * @param GenericEvent
     */
    public function onDebatePublish(GenericEvent $event) {
        $this->logger->info('*** onDebatePublish');

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
    public function onReactionPublish(GenericEvent $event) {
        $this->logger->info('*** onReactionPublish');

        // Réaction de la réaction
        $subject = $event->getSubject();

        $userId = $event->getArgument('user_id');
        $prActionId = PRAction::ID_D_REACTION_PUBLISH;
        $objectName = get_class($subject);
        $objectId = $subject->getId();

        $this->insertPUReputationRA($userId, $prActionId, $objectName, $objectId);

        // Débat associé à la réaction
        $debate = $subject->getPDDebate();

        $userId = $debate->getPUserId();
        $prActionId = PRAction::ID_D_TARGET_DEBATE_REACTION_PUBLISH;
        $objectName = get_class($debate);
        $objectId = $debate->getId();

        $this->insertPUReputationRA($userId, $prActionId, $objectName, $objectId);


        // Réaction associée à la réaction
        if ($subject->getTreeLevel() > 1) {
            $parent = $subject->getParent();

            $userId = $parent->getPUserId();
            $prActionId = PRAction::ID_D_TARGET_REACTION_REACTION_PUBLISH;
            $objectName = get_class($parent);
            $objectId = $parent->getId();

            $this->insertPUReputationRA($userId, $prActionId, $objectName, $objectId);
        }
    }

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
