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
    private $documentManager;

    private $logger;

    /**
     *
     * @param @politizr.manager.document
     * @param @logger
     */
    public function __construct(
        $documentManager,
        $logger
    ) {
        $this->documentManager = $documentManager;

        $this->logger = $logger;
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
    public function getNotesByDate($documentId, $documentType, \DateTime $startAt, \DateTime $endAt)
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

        $notesByDate = $this->documentManager->generateNotesByDate($documentId, $documentType, $notePosReputationId, $noteNegReputationId, $startAt->format('Y-m-d H:i:s'), $endAt->format('Y-m-d H:i:s'));
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
    public function getSumOfNotes($documentId, $documentType, \DateTime $untilAt)
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

        $sumOfNotes = $this->documentManager->generateSumOfNotes($documentId, $documentType, $notePosReputationId, $noteNegReputationId, $untilAt->format('Y-m-d H:i:s'));

        return $sumOfNotes;
    }
}
