<?php
namespace Politizr\FrontBundle\Lib\Manager;

use Politizr\Exception\InconsistentDataException;
use Politizr\Exception\FormValidationException;

use StudioEcho\Lib\StudioEchoUtils;

use Politizr\Model\PTTagType;
use Politizr\Model\PTag;
use Politizr\Model\PDDTaggedT;
use Politizr\Model\PUFollowT;
use Politizr\Model\PUTaggedT;

use Politizr\Model\PTagQuery;
use Politizr\Model\PDDTaggedTQuery;
use Politizr\Model\PUFollowTQuery;
use Politizr\Model\PUTaggedTQuery;

/**
 * DB manager service for tag.
 *
 * @author Lionel Bouzonville
 */
class TagManager
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
     * Get array of [id, tag]
     *
     * @param integer $tagTypeId
     * @param boolean $online
     * @return array
     */
    public function getArrayTags($tagTypeId = null, $online = true)
    {
        $tags = PTagQuery::create()
            ->select(array('id', 'title'))
            ->_if(null !== $online)
                ->filterByOnline($online)
            ->_endif()
            ->_if(null !== $tagTypeId)
                ->filterByPTTagTypeId($tagTypeId)
            ->_endif()
            ->orderByTitle()
            ->find()
            ->toArray();

        return $tags;
    }

    /**
     * Create a new tag by title.
     *
     * @param string $title
     * @param integer $typeId
     * @param integer $userId
     * @param boolean $online
     * @return PTag
     */
    public function createTag($title = '', $typeId = null, $userId = null, $online = true)
    {
        $slug = StudioEchoUtils::generateSlug($title);

        $tag = new PTag();
        if (null === $typeId) {
            $typeId = PTTagType::TYPE_THEME;
        }
        $tag->setTitle($title);
        $tag->setPTTagTypeId($typeId);
        $tag->setPUserId($userId);
        $tag->setOnline($online);

        $tag->save();

        return $tag;
    }

    /**
     * Create a new PDDTaggedT
     *
     * @param integer $debateId
     * @param integer $tagId
     * @return PDDTaggedT
     */
    public function createDebateTag($debateId, $tagId)
    {
        $pddTaggedT = new PDDTaggedT();

        $pddTaggedT->setPDDebateId($debateId);
        $pddTaggedT->setPTagId($tagId);

        $pddTaggedT->save();

        return $pddTaggedT;
    }

    /**
     * Delete a PDDTaggedT
     *
     * @param integer $debateId
     * @param integer $tagId
     * @return integer
     */
    public function deleteDebateTag($debateId, $tagId)
    {
        $result = PDDTaggedTQuery::create()
            ->filterByPDDebateId($debateId)
            ->filterByPTagId($tagId)
            ->delete();

        return $result;
    }

    /**
     * Create a new PUFollowT
     *
     * @param integer $userId
     * @param integer $tagId
     * @return PUFollowT
     */
    public function createUserFollowTag($userId, $tagId)
    {
        $puFollowT = new PUFollowT();

        $puFollowT->setPUserId($userId);
        $puFollowT->setPTagId($tagId);

        $puFollowT->save();

        return $puFollowT;
    }

    /**
     * Delete a PUFollowT
     *
     * @param integer $userId
     * @param integer $tagId
     * @return integer
     */
    public function deleteUserFollowTag($userId = null, $tagId = null)
    {
        $result = PUFollowTQuery::create()
            ->filterByPUserId($userId)
            ->filterByPTagId($tagId)
            ->delete();

        return $result;
    }

    /**
     * Create a new PUTaggedT
     *
     * @param integer $userId
     * @param integer $tagId
     * @return PUTaggedT
     */
    public function createUserTaggedTag($userId, $tagId)
    {
        $puTaggedT = new PUTaggedT();

        $puTaggedT->setPUserId($userId);
        $puTaggedT->setPTagId($tagId);

        $puTaggedT->save();

        return $puTaggedT;
    }

    /**
     * Delete a PUTaggedT
     *
     * @param integer $userId
     * @param integer $tagId
     * @return integer
     */
    public function deleteUserTaggedTag($userId, $tagId)
    {
        $result = PUTaggedTQuery::create()
            ->filterByPUserId($userId)
            ->filterByPTagId($tagId)
            ->delete();
        
        return $result;
    }
}
