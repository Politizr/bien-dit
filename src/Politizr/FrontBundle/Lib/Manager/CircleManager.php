<?php
namespace Politizr\FrontBundle\Lib\Manager;

use Politizr\Model\PUInPC;

use Politizr\Model\PUInPCQuery;

use Politizr\Exception\InconsistentDataException;


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
     * Update users to add role associated with circle
     *
     * @param PropelCollection[PUser] $users
     * @param integer $circleId
     */
    public function addUsersCircleRole($users, $circleId)
    {
        if(!$users || !$circleId) {
            return null;
        }
        
        $circleRole = 'ROLE_CIRCLE_' . $circleId;

        $con = \Propel::getConnection('default');
        $con->beginTransaction();
        try {
            foreach ($users as $user) {
                if (!$user->hasRole($circleRole)) {
                    $user->addRole($circleRole);
                    $user->save();
                }
            }

            $con->commit();
        } catch (\Exception $e) {
            $con->rollback();
            throw new InconsistentDataException(sprintf('Rollback addUsersCircleRole user id-%s.', $user->getId()));
        }
    }

    /**
     * Create list of PUInPC
     *
     * @param PropelCollection[PUser] $users
     * @param integer $circleId
     */
    public function insertUsersInCircle($users, $circleId)
    {
        if(!$users || !$circleId) {
            return null;
        }

        $con = \Propel::getConnection('default');
        $con->beginTransaction();
        try {
            foreach ($users as $user) {
                $this->createUserInCircle($user->getId(), $circleId);
            }

            $con->commit();
        } catch (\Exception $e) {
            $con->rollback();
            throw new InconsistentDataException(sprintf('Rollback insertUsersInCircle user id-%s.', $user->getId()));
        }
    }

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

    /**
     * Update "is_authorized_reaction" attribute for a user/circle in PUInPC
     *
     * @param integer $userId
     * @param integer $circleId
     * @param boolean $isAuthorizedReaction
     * @return PDDTaggedT
     */
    public function updateUserIsAuthorizedReactionInCircle($userId, $circleId, $isAuthorizedReaction)
    {
        $puInPc = PUInPCQuery::create()
            ->filterByPUserId($userId)
            ->filterByPCircleId($circleId)
            ->findOne();

        if ($puInPc) {
            $puInPc->setIsAuthorizedReaction($isAuthorizedReaction);
            $puInPc->save();

            return $puInPc;
        }

        return null;
    }
}