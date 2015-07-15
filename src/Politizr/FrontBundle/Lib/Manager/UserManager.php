<?php
namespace Politizr\FrontBundle\Lib\Manager;

use Politizr\Exception\InconsistentDataException;
use Politizr\Exception\FormValidationException;

use Politizr\Model\PDocumentInterface;
use Politizr\Model\PDDebate;
use Politizr\Model\PDReaction;
use Politizr\Model\PUFollowDD;
use Politizr\Model\PUFollowU;
use Politizr\Model\PDDComment;
use Politizr\Model\PDRComment;

use Politizr\Model\PDDebateQuery;
use Politizr\Model\PDReactionQuery;
use Politizr\Model\PUFollowDDQuery;
use Politizr\Model\PDDCommentQuery;
use Politizr\Model\PDRCommentQuery;

/**
 * DB manager service for user.
 *
 * @author Lionel Bouzonville
 */
class UserManager
{
    private $sc;

    /**
     *
     */
    public function __construct($serviceContainer)
    {
        $this->sc = $serviceContainer;
    }

    /**
     * Create a new PUFollowDD assocation
     *
     * @param integer $userId
     * @param integer $debateId
     * @return PUFollowDD
     */
    public function createUserFollowDebate($userId, $debateId)
    {
        $puFollowDD = new PUFollowDD();

        $puFollowDD->setPUserId($userId);
        $puFollowDD->setPDDebateId($debateId);
        $puFollowDD->save();

        return $puFollowDD;
    }

    /**
     * Delete PUFollowDD
     *
     * @param integer $userId
     * @param integer $debateId
     * @return integer
     */
    public function deleteUserFollowDebate($userId, $debateId)
    {
        // Suppression élément(s)
        $result = PUFollowDDQuery::create()
            ->filterByPUserId($userId)
            ->filterByPDDebateId($debateId)
            ->delete();

        return $result;
    }

    /**
     * Create a new PUFollowU assocation
     *
     * @param integer $sourceId
     * @param integer $targetId
     * @return PUFollowDD
     */
    public function createUserFollowUser($sourceId, $targetId)
    {
        $puFollowU = new PUFollowU();

        $puFollowU->setPUserFollowerId($sourceId);
        $puFollowU->setPUserId($targetId);
        $puFollowU->save();

        return $puFollowU;
    }

    /**
     * Delete PUFollowU
     *
     * @param integer $sourceId
     * @param integer $targetId
     * @return integer
     */
    public function deleteUserFollowUser($sourceId, $targetId)
    {
        // Suppression élément(s)
        $result = PUFollowUQuery::create()
            ->filterByPUserId($targetId)
            ->filterByPUserFollowerId($sourceId)
            ->delete();

        return $result;
    }

    /**
     * Update PUFollowU contextual email subscription
     *
     * @param integer $sourceId
     * @param integer $targetId
     * @param boolean $isNotif
     * @param string $context
     * @return PUFollowU
     */
    public function updateFollowUserContextEmailNotification($sourceId, $targetId, $isNotif, $context)
    {
        $puFollowU = PUFollowUQuery::create()
            ->filterByPUserFollowerId($sourceId)
            ->filterByPUserId($targetId)
            ->findOne();

        // @todo refactor constant management
        if ($puFollowU && $context == 'debate') {
            $puFollowU->setNotifDebate($isNotif);
            $puFollowU->save();
        } elseif ($puFollowU && $context == 'reaction') {
            $puFollowU->setNotifReaction($isNotif);
            $puFollowU->save();
        } elseif ($puFollowU && $context == 'comment') {
            $puFollowU->setNotifComment($isNotif);
            $puFollowU->save();
        }

        return $puFollowU;
    }

    /**
     * Update PUFollowU contextual email subscription
     *
     * @param integer $userId
     * @param integer $debateId
     * @param boolean $isNotif
     * @param string $context
     * @return PUFollowDD
     */
    public function updateFollowDebateContextEmailNotification($userId, $debateId, $isNotif, $context)
    {
        $puFollowDD = PUFollowDDQuery::create()
            ->filterByPUserId($userId)
            ->filterByPDDebateId($debateId)
            ->findOne();

        if ($puFollowDD && $context == 'reaction') {
            $puFollowDD->setNotifReaction($isNotif);
            $puFollowDD->save();
        }

        return $puFollowDD;
    }

    /**
     * Delete user's mandate
     *
     * @param PUMandate $mandate
     * @return integer
     */
    public function deleteMandate(PUMandate $mandate)
    {
        $result = $mandate->delete();

        return $result;
    }
}
