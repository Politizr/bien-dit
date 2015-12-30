<?php
namespace Politizr\FrontBundle\Lib\Manager;

/**
 * DB manager service for reputation.
 *
 * @author Lionel Bouzonville
 */
class ReputationManager
{
    private $logger;

    /**
     *
     * @param @logger
     */
    public function __construct($logger)
    {
        $this->logger = $logger;
    }

    /* ######################################################################################################## */
    /*                                            RAW SQL FUNCTIONS                                             */
    /* ######################################################################################################## */

    /**
     * User's scores evolution groupe by date
     *
     * @see app/sql/reputation.sql
     *
     * @return string
     */
    private function createUserScoresByDateRawSql()
    {
        $sql = "
SELECT DATE(p_u_reputation.created_at) as DATE, p_u_reputation.id, SUM(p_r_action.score_evolution) as SUM_SCORE
FROM `p_u_reputation` LEFT JOIN `p_r_action` ON p_u_reputation.p_r_action_id = p_r_action.id
WHERE
`p_user_id` = :p_user_id
AND p_u_reputation.created_at >= :startAt
AND p_u_reputation.created_at < :endAt
GROUP BY DATE(p_u_reputation.created_at)
        ";

        return $sql;
    }

    /**
     * Document's notes evolution group by date
     *
     * @see app/sql/stats.sql
     *
     * @return string
     */
    private function createDocumentNotesByDateRawSql()
    {
        $sql = "
SELECT
    DATE(p_u_reputation.id) as ID,
    p_u_reputation.id,
    DATE(p_u_reputation.created_at) as DATE,
    COUNT(CASE WHEN p_u_reputation.p_r_action_id = :notePosReputationId THEN 1 END) AS COUNT_NOTE_POS,
    COUNT(CASE WHEN p_u_reputation.p_r_action_id = :noteNegReputationId THEN 1 END) AS COUNT_NOTE_NEG
FROM `p_u_reputation` LEFT JOIN `p_r_action` ON p_u_reputation.p_r_action_id = p_r_action.id
WHERE
p_u_reputation.p_object_name = :documentType
AND p_u_reputation.p_object_id = :documentId
AND (p_u_reputation.p_r_action_id = :notePosReputationId2 OR p_u_reputation.p_r_action_id = :noteNegReputationId2)
AND p_u_reputation.created_at >= :startAt
AND p_u_reputation.created_at < :endAt
GROUP BY DATE(p_u_reputation.created_at)
        ";

        return $sql;
    }

    /**
     * Document's notes sum until date
     *
     * @see app/sql/stats.sql
     *
     * @return string
     */
    private function createDocumentSumOfNotesRawSql()
    {
        $sql = "
SELECT
    COUNT(CASE WHEN p_u_reputation.p_r_action_id = :notePosReputationId THEN 1 END) AS COUNT_NOTE_POS,
    COUNT(CASE WHEN p_u_reputation.p_r_action_id = :noteNegReputationId THEN 1 END) AS COUNT_NOTE_NEG
FROM `p_u_reputation` LEFT JOIN `p_r_action` ON p_u_reputation.p_r_action_id = p_r_action.id
WHERE
p_u_reputation.p_object_name = :documentType
AND p_u_reputation.p_object_id = :documentId
AND (p_u_reputation.p_r_action_id = :notePosReputationId2 OR p_u_reputation.p_r_action_id = :noteNegReputationId2)
AND p_u_reputation.created_at < :untilAt
        ";

        return $sql;
    }

    /* ######################################################################################################## */
    /*                                            RAW SQL OPERATIONS                                            */
    /* ######################################################################################################## */

    /**
     * Get user's scores evolution as array of (id, created_at, sum_notes)
     *
     * @param int $userId
     * @param string $documentType
     * @param int $notePosReputationId
     * @param int $noteNegReputationId
     * @param string $startAt
     * @param string $endAt
     * @return array
     */
    public function generateUserScoresByDate($userId, $startAt, $endAt)
    {
        $this->logger->info('*** generateUserScoresByDate');
        $this->logger->info('$userId = ' . print_r($userId, true));
        $this->logger->info('$startAt = ' . print_r($startAt, true));
        $this->logger->info('$endAt = ' . print_r($endAt, true));

        $con = \Propel::getConnection('default', \Propel::CONNECTION_READ);
        $stmt = $con->prepare($this->createUserScoresByDateRawSql());

        $stmt->bindValue(':p_user_id', $userId, \PDO::PARAM_INT);
        $stmt->bindValue(':startAt', $startAt, \PDO::PARAM_STR);
        $stmt->bindValue(':endAt', $endAt, \PDO::PARAM_STR);

        $stmt->execute();
        $result = $stmt->fetchAll();

        $scoresByDates = array();
        foreach ($result as $row) {
            $scoreByDate = array();

            $scoreByDate['id'] = $row['id'];
            $scoreByDate['created_at'] = $row['DATE'];
            $scoreByDate['sum_score'] = $row['SUM_SCORE'];

            $scoresByDates[] = $scoreByDate;
        }

        return $scoresByDates;
    }

    /**
     * Get document's notes evolution as array of (created_at, nb_note_pos, nb_note_neg)
     *
     * @param int $documentId
     * @param string $documentType
     * @param int $notePosReputationId
     * @param int $noteNegReputationId
     * @param string $startAt
     * @param string $endAt
     * @return array
     */
    public function generateDocumentNotesByDate($documentId, $documentType, $notePosReputationId, $noteNegReputationId, $startAt, $endAt)
    {
        $this->logger->info('*** generateDocumentNotesByDate');
        $this->logger->info('$documentId = ' . print_r($documentId, true));
        $this->logger->info('$documentType = ' . print_r($documentType, true));
        $this->logger->info('$notePosReputationId = ' . print_r($notePosReputationId, true));
        $this->logger->info('$noteNegReputationId = ' . print_r($noteNegReputationId, true));
        $this->logger->info('$startAt = ' . print_r($startAt, true));
        $this->logger->info('$endAt = ' . print_r($endAt, true));

        $con = \Propel::getConnection('default', \Propel::CONNECTION_READ);
        $stmt = $con->prepare($this->createDocumentNotesByDateRawSql());

        $stmt->bindValue(':notePosReputationId', $notePosReputationId, \PDO::PARAM_INT);
        $stmt->bindValue(':notePosReputationId2', $notePosReputationId, \PDO::PARAM_INT);
        $stmt->bindValue(':noteNegReputationId', $noteNegReputationId, \PDO::PARAM_INT);
        $stmt->bindValue(':noteNegReputationId2', $noteNegReputationId, \PDO::PARAM_INT);
        $stmt->bindValue(':documentType', $documentType, \PDO::PARAM_STR);
        $stmt->bindValue(':documentId', $documentId, \PDO::PARAM_INT);
        $stmt->bindValue(':startAt', $startAt, \PDO::PARAM_STR);
        $stmt->bindValue(':endAt', $endAt, \PDO::PARAM_STR);

        $stmt->execute();
        $result = $stmt->fetchAll();

        $notesByDates = array();
        foreach ($result as $row) {
            $noteByDate = array();

            $noteByDate['id'] = $row['id'];
            $noteByDate['created_at'] = $row['DATE'];
            $noteByDate['sum_notes'] = $row['COUNT_NOTE_POS'] - $row['COUNT_NOTE_NEG'];

            $notesByDates[] = $noteByDate;
        }

        return $notesByDates;
    }

    /**
     * Get document's sum of positive - negative notes until a date
     *
     * @param int $documentId
     * @param string $documentType
     * @param int $notePosReputationId
     * @param int $noteNegReputationId
     * @param string $untilAt
     * @return int
     */
    public function generateDocumentSumOfNotes($documentId, $documentType, $notePosReputationId, $noteNegReputationId, $untilAt)
    {
        $this->logger->info('*** generateDocumentSumOfNotes');
        $this->logger->info('$documentId = ' . print_r($documentId, true));
        $this->logger->info('$documentType = ' . print_r($documentType, true));
        $this->logger->info('$notePosReputationId = ' . print_r($notePosReputationId, true));
        $this->logger->info('$noteNegReputationId = ' . print_r($noteNegReputationId, true));
        $this->logger->info('$untilAt = ' . print_r($untilAt, true));

        $con = \Propel::getConnection('default', \Propel::CONNECTION_READ);
        $stmt = $con->prepare($this->createDocumentSumOfNotesRawSql());

        $stmt->bindValue(':notePosReputationId', $notePosReputationId, \PDO::PARAM_INT);
        $stmt->bindValue(':notePosReputationId2', $notePosReputationId, \PDO::PARAM_INT);
        $stmt->bindValue(':noteNegReputationId', $noteNegReputationId, \PDO::PARAM_INT);
        $stmt->bindValue(':noteNegReputationId2', $noteNegReputationId, \PDO::PARAM_INT);
        $stmt->bindValue(':documentType', $documentType, \PDO::PARAM_STR);
        $stmt->bindValue(':documentId', $documentId, \PDO::PARAM_INT);
        $stmt->bindValue(':untilAt', $untilAt, \PDO::PARAM_STR);

        $stmt->execute();
        $result = $stmt->fetchAll();

        $sumOfNotes = 0;
        if (isset($result[0])) {
            $sumOfNotes = $result[0]['COUNT_NOTE_POS'] - $result[0]['COUNT_NOTE_NEG'];
        }

        return $sumOfNotes;
    }
}
