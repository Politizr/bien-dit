<?php
namespace Politizr\FrontBundle\Lib\Manager;

use Politizr\Model\PUInPC;

use Politizr\Model\PUInPCQuery;

/**
 * DB manager service for circle.
 *
 * @author Lionel Bouzonville
 */
class CircleManager
{
    private $globalTools;

    private $logger;

    /**
     *
     * @param @politizr.tools.global
     * @param @logger
     */
    public function __construct(
        $globalTools,
        $logger
    ) {
        $this->globalTools = $globalTools;

        $this->logger = $logger;
    }

    /* ######################################################################################################## */
    /*                                            CRUD OPERATIONS                                               */
    /* ######################################################################################################## */


    /* ######################################################################################################## */
    /*                                    RELATED TABLES OPERATIONS                                             */
    /* ######################################################################################################## */

    /**
     * Create a new PUInPC
     *
     * @param integer $userId
     * @param integer $circleId
     * @return PDDTaggedT
     */
    public function createUserInCircle($userId, $circleId)
    {
        $puInPc = PUInPCQuery::create()
            ->filterByPUserId($userId)
            ->filterByPCircleId($circleId)
            ->findOne();

        if (!$puInPc) {
            $puInPc = new PUInPC();

            $puInPc->setPUserId($userId);
            $puInPc->setPCircleId($circleId);

            $puInPc->save();

            return $puInPc;
        }

        return null;
    }

    /**
     * Create a new PUInPC
     *
     * @param integer $userId
     * @param integer $circleId
     * @return PDDTaggedT
     */
    public function deleteUserInCircle($userId, $circleId)
    {
        $result = PUInPCQuery::create()
            ->filterByPUserId($userId)
            ->filterByPCircleId($circleId)
            ->delete();
        
        return $result;
    }
}