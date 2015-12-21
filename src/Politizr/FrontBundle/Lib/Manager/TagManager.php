<?php
namespace Politizr\FrontBundle\Lib\Manager;

use StudioEcho\Lib\StudioEchoUtils;

use Politizr\Constant\TagConstants;

use Politizr\Model\PTag;
use Politizr\Model\PDDTaggedT;
use Politizr\Model\PDRTaggedT;
use Politizr\Model\PUTaggedT;

use Politizr\Model\PTagQuery;
use Politizr\Model\PDDTaggedTQuery;
use Politizr\Model\PDRTaggedTQuery;
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


    /* ######################################################################################################## */
    /*                                                  RAW SQL                                                 */
    /* ######################################################################################################## */

   /**
     * Most popular tags
     *
     * @see app/sql/topTags.sql
     *
     * @param integer $interval
     * @return string
     */
    public function createMostPopularTagsRawSql($interval = null)
    {
        $intervalDebateSql = '';
        $intervalReactionSql = '';
        $intervalUserSql = '';

        if ($interval) {
            $intervalDebateSql = sprintf("AND p_d_debate.published_at BETWEEN DATE_SUB(NOW(), INTERVAL %d DAY) AND NOW()", $interval);
            $intervalReactionSql = sprintf("AND p_d_reaction.published_at BETWEEN DATE_SUB(NOW(), INTERVAL %d DAY) AND NOW()", $interval);
            $intervalUserSql = sprintf("AND p_user.created_at BETWEEN DATE_SUB(NOW(), INTERVAL %d DAY) AND NOW()", $interval);
        }

        // Préparation requête SQL
        $sql = "
SELECT p_tag_id, count(p_tag_id) AS nb_tagged_objects
FROM
(
SELECT p_tag_id
FROM p_d_d_tagged_t
LEFT JOIN p_d_debate ON p_d_d_tagged_t.p_d_debate_id = p_d_debate.id
WHERE p_d_debate.online = 1
AND p_d_debate.published = 1
".$intervalDebateSql."

UNION ALL

SELECT p_tag_id
FROM p_d_r_tagged_t
LEFT JOIN p_d_reaction ON p_d_r_tagged_t.p_d_reaction_id = p_d_reaction.id
WHERE p_d_reaction.online = 1
AND p_d_reaction.published = 1
".$intervalReactionSql."

UNION ALL

SELECT p_tag_id
FROM p_u_tagged_t
LEFT JOIN p_user ON p_u_tagged_t.p_user_id = p_user.id
WHERE p_user.online = 1
AND p_user.p_u_status_id = 1
".$intervalUserSql."

) tables

GROUP BY p_tag_id
ORDER BY nb_tagged_objects desc
    ";

        return $sql;
    }

    /* ######################################################################################################## */
    /*                                            CRUD OPERATIONS                                               */
    /* ######################################################################################################## */


    /**
     * Get array of [id, tag]
     *
     * @param integer $tagTypeId
     * @param boolean $used
     * @param boolean $online
     * @return array
     */
    public function getArrayTags($tagTypeId = null, $used = false, $online = true)
    {
        $usedByUserTagIds = [];
        $usedByReactionTagIds = [];
        $usedByDebateTagIds = [];
        if ($used) {
            $usedByDebateTagIds = PDDTaggedTQuery::create()
                ->select('PTagId')
                ->find()
                ->toArray();

            $usedByReactionTagIds = PDRTaggedTQuery::create()
                ->select('PTagId')
                ->find()
                ->toArray();

            $usedByUserTagIds = PUTaggedTQuery::create()
                ->select('PTagId')
                ->find()
                ->toArray();
        }

        $tags = PTagQuery::create()
            ->select(array('uuid', 'title'))
            ->distinct()
            ->filterIfOnline($online)
            ->filterIfTypeId($tagTypeId)
            ->_if($used)
                ->filterById($usedByDebateTagIds)
                ->_or()
                ->filterById($usedByReactionTagIds)
                ->_or()
                ->filterById($usedByUserTagIds)
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
            $typeId = TagConstants::TAG_TYPE_THEME;
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
     * Create a new PDRTaggedT
     *
     * @param integer $reactionId
     * @param integer $tagId
     * @return PDDTaggedT
     */
    public function createReactionTag($reactionId, $tagId)
    {
        $pdrTaggedT = new PDRTaggedT();

        $pdrTaggedT->setPDReactionId($reactionId);
        $pdrTaggedT->setPTagId($tagId);

        $pdrTaggedT->save();

        return $pdrTaggedT;
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
     * Delete a PDRTaggedT
     *
     * @param integer $reactionId
     * @param integer $tagId
     * @return integer
     */
    public function deleteReactionTag($reactionId, $tagId)
    {
        $result = PDRTaggedTQuery::create()
            ->filterByPDReactionId($reactionId)
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
    public function createUserTag($userId, $tagId)
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
    public function deleteUserTag($userId, $tagId)
    {
        $result = PUTaggedTQuery::create()
            ->filterByPUserId($userId)
            ->filterByPTagId($tagId)
            ->delete();
        
        return $result;
    }
}
