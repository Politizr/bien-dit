<?php
namespace Politizr\FrontBundle\Lib\Manager;

use Politizr\Exception\InconsistentDataException;
use Politizr\Exception\FormValidationException;

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

    /**
     * Get user's reputation evolution as array of (created_at, score_evolution)
     *
     * @param int $userId
     * @return array
     */
    public function getUserReputationEvolution($userId)
    {
        // SELECT p_u_reputation.created_at, p_r_action.score_evolution
        // FROM `p_u_reputation` LEFT JOIN `p_r_action` ON p_u_reputation.p_r_action_id = p_r_action.id
        // WHERE
        // `p_user_id` = '1'
        // LIMIT 30

        // http://stackoverflow.com/questions/19524589/propel-selecting-columns-from-aliased-join-tables
        $evolution = PUReputationQuery::create()
            ->usePRActionQuery('action', 'left join')
            ->endUse()
            ->select(array('CreatedAt', 'action.ScoreEvolution'))
            ->filterByPUserId($userId)
            ->orderByCreatedAt()
            ->limit(30)
            ->find()
            ->toArray();

        return $evolution;
    }
}
