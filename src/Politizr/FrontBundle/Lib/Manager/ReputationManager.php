<?php
namespace Politizr\FrontBundle\Lib\Manager;

use Politizr\Exception\InconsistentDataException;
use Politizr\Exception\BoxErrorException;

use Politizr\Model\PUReputationQuery;

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

    // **************************************************************************** //
    //                             PRIVATE FUNCTIONS                                //
    // **************************************************************************** //

    /**
     * User's reputation evolution between two dates
     *
     * @see app/sql/reputation.sql
     *
     * @param integer $userId
     * @param DateTime $startAt
     * @param DateTime $endAt
     * @return string
     */
    public function createReputationByDatesRawSql($userId, $startAt, $endAt)
    {
        $sql = "
SELECT DATE(p_u_reputation.created_at) as DATE, p_u_reputation.id, SUM(p_r_action.score_evolution) as SUM_SCORE
FROM `p_u_reputation` LEFT JOIN `p_r_action` ON p_u_reputation.p_r_action_id = p_r_action.id
WHERE
`p_user_id` = ".$userId."
AND p_u_reputation.created_at >= '".$startAt."'
AND p_u_reputation.created_at < '".$endAt."'
GROUP BY DATE(p_u_reputation.created_at)
        ";

        return $sql;
    }

    /**
     * Execute SQL and hydrate an array
     *
     * @param string $sql
     * @return array
     */
    private function hydrateReputationByDatesRows($sql)
    {
        $this->logger->info('*** hydrateReputationByDatesRows');

        $reputationEvolution = array();

        if ($sql) {
            $con = \Propel::getConnection('default', \Propel::CONNECTION_READ);

            $stmt = $con->prepare($sql);
            $stmt->execute();

            $result = $stmt->fetchAll();

            foreach ($result as $row) {
                $reputationByDates = array();

                $reputationByDates['id'] = $row['id'];
                $reputationByDates['created_at'] = $row['DATE'];
                $reputationByDates['sum_score'] = $row['SUM_SCORE'];

                $reputationEvolution[] = $reputationByDates;
            }
        }

        return $reputationEvolution;
    }



    // **************************************************************************** //
    //                              PUBLIC FUNCTIONS                                //
    // **************************************************************************** //

    /**
     * Get user's reputation evolution as array of (created_at, score_evolution)
     *
     * @param int $userId
     * @param DateTime $startAt
     * @param DateTime $endAt
     * @return array
     */
    public function getReputationByDates($userId, $startAt, $endAt)
    {
        $sql = $this->createReputationByDatesRawSql($userId, $startAt->format('Y-m-d H:i:s'), $endAt->format('Y-m-d H:i:s'));
        $reputationEvolution = $this->hydrateReputationByDatesRows($sql);

        return $reputationEvolution;
    }
}
