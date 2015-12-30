<?php
namespace Politizr\FrontBundle\Lib\Functional;

use Politizr\Exception\InconsistentDataException;

use Politizr\Constant\ReputationConstants;
use Politizr\Constant\ObjectTypeConstants;

/**
 * Functional service for reputation management.
 *
 * @author Lionel Bouzonville
 */
class ReputationService
{
    private $reputationManager;

    private $logger;

    /**
     *
     * @param @politizr.manager.reputation
     * @param @logger
     */
    public function __construct(
        $reputationManager,
        $logger
    ) {
        $this->reputationManager = $reputationManager;

        $this->logger = $logger;
    }


    /* ######################################################################################################## */
    /*                                                USERS STATS                                               */
    /* ######################################################################################################## */
    
    /**
     * Get user score evolution indexed by date
     *
     * @param int $userId
     * @param DateTime $startAt
     * @param DateTime $endAt
     * @return array
     */
    public function getUserScoresByDate($userId, \DateTime $startAt, \DateTime $endAt)
    {
        $scoresByDate = $this->reputationManager->generateUserScoresByDate($userId, $startAt->format('Y-m-d H:i:s'), $endAt->format('Y-m-d H:i:s'));
        return $scoresByDate;
    }

    /* ######################################################################################################## */
    /*                                              DOCUMENTS STATS                                             */
    /* ######################################################################################################## */
    
    /**
     * Get document notes evolution indexed by date
     *
     * @param int $documentId
     * @param string $documentType
     * @param DateTime $startAt
     * @param DateTime $endAt
     * @return array
     */
    public function getDocumentNotesByDate($documentId, $documentType, \DateTime $startAt, \DateTime $endAt)
    {
        // Function process
        switch($documentType) {
            case ObjectTypeConstants::TYPE_DEBATE:
                $notePosReputationId = ReputationConstants::ACTION_ID_D_AUTHOR_DEBATE_NOTE_POS;
                $noteNegReputationId = ReputationConstants::ACTION_ID_D_AUTHOR_DEBATE_NOTE_NEG;
                break;
            case ObjectTypeConstants::TYPE_REACTION:
                $notePosReputationId = ReputationConstants::ACTION_ID_D_AUTHOR_REACTION_NOTE_POS;
                $noteNegReputationId = ReputationConstants::ACTION_ID_D_AUTHOR_REACTION_NOTE_NEG;
                break;
            default:
                throw new InconsistentDataException(sprintf('Invalid document type %s', $documentType));
        }

        $notesByDate = $this->reputationManager->generateDocumentNotesByDate($documentId, $documentType, $notePosReputationId, $noteNegReputationId, $startAt->format('Y-m-d H:i:s'), $endAt->format('Y-m-d H:i:s'));
        return $notesByDate;
    }

    /**
     * Get document's sum of positive - negative notes until a given date
     *
     * @param int $documentId
     * @param string $documentType
     * @param DateTime $untilAt
     * @return array
     */
    public function getDocumentSumOfNotes($documentId, $documentType, \DateTime $untilAt)
    {
        // Function process
        switch($documentType) {
            case ObjectTypeConstants::TYPE_DEBATE:
                $notePosReputationId = ReputationConstants::ACTION_ID_D_AUTHOR_DEBATE_NOTE_POS;
                $noteNegReputationId = ReputationConstants::ACTION_ID_D_AUTHOR_DEBATE_NOTE_NEG;
                break;
            case ObjectTypeConstants::TYPE_REACTION:
                $notePosReputationId = ReputationConstants::ACTION_ID_D_AUTHOR_REACTION_NOTE_POS;
                $noteNegReputationId = ReputationConstants::ACTION_ID_D_AUTHOR_REACTION_NOTE_NEG;
                break;
            default:
                throw new InconsistentDataException(sprintf('Invalid document type %s', $documentType));
        }

        $sumOfNotes = $this->reputationManager->generateDocumentSumOfNotes($documentId, $documentType, $notePosReputationId, $noteNegReputationId, $untilAt->format('Y-m-d H:i:s'));

        return $sumOfNotes;
    }
}
