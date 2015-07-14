<?php
namespace Politizr\FrontBundle\Lib\Manager;

use Politizr\Exception\InconsistentDataException;
use Politizr\Exception\FormValidationException;

use Politizr\Model\PDocumentInterface;
use Politizr\Model\PDDebate;
use Politizr\Model\PDReaction;
use Politizr\Model\PUFollowDD;
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
     * @param int $userId
     * @param int $debateId
     * @return PUFollowDD
     */
    public function createUserFollowDebate($userId, $debateId)
    {
        $pUFollowDD = new PUFollowDD();

        $pUFollowDD->setPUserId($userId);
        $pUFollowDD->setPDDebateId($debateId);
        $pUFollowDD->save();

        return $pUFollowDD;
    }

    /**
     * Delete PUFollowDD
     *
     * @param int $userId
     * @param int $debateId
     */
    public function deleteUserFollowDebate($userId, $debateId)
    {
        // Suppression élément(s)
        $pUFollowDDList = PUFollowDDQuery::create()
            ->filterByPUserId($userId)
            ->filterByPDDebateId($debateId)
            ->delete();
    }
}
