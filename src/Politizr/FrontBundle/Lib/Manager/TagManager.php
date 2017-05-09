<?php
namespace Politizr\FrontBundle\Lib\Manager;

use Politizr\Constant\TagConstants;

use Politizr\Model\PTag;
use Politizr\Model\PDDTaggedT;
use Politizr\Model\PDRTaggedT;
use Politizr\Model\PUTaggedT;
use Politizr\Model\PTScopePLC;
use Politizr\Model\PEOPresetPT;

use Politizr\Model\PTagQuery;
use Politizr\Model\PDDTaggedTQuery;
use Politizr\Model\PDRTaggedTQuery;
use Politizr\Model\PUTaggedTQuery;
use Politizr\Model\PTScopePLCQuery;
use Politizr\Model\PEOPresetPTQuery;

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
     * @param integer $tagTypeId
     * @param integer $interval
     * @return string
     */
    public function createMostPopularTagIdsRawSql($tagTypeId = null, $interval = null)
    {
        $tagTypeDebateSql = '';
        $tagTypeReactionSql = '';
        $tagTypeUserSql = '';

        if ($tagTypeId) {
            $tagTypeDebateSql = "AND p_tag.p_t_tag_type_id = :tagTypeId";
            $tagTypeReactionSql = "AND p_tag.p_t_tag_type_id = :tagTypeId2";
            $tagTypeUserSql = "AND p_tag.p_t_tag_type_id = :tagTypeId3";
        }

        $intervalDebateSql = '';
        $intervalReactionSql = '';
        $intervalUserSql = '';

        if ($interval) {
            $intervalDebateSql = "AND p_d_debate.published_at BETWEEN DATE_SUB(NOW(), INTERVAL :interval DAY) AND NOW()";
            $intervalReactionSql = "AND p_d_reaction.published_at BETWEEN DATE_SUB(NOW(), INTERVAL :interval2 DAY) AND NOW()";
            $intervalUserSql = "AND p_user.created_at BETWEEN DATE_SUB(NOW(), INTERVAL :interval3 DAY) AND NOW()";
        }

        // Préparation requête SQL
        $sql = "
SELECT p_tag_id, count(p_tag_id) AS nb_tagged_objects
FROM
(
SELECT p_tag_id
FROM p_d_d_tagged_t
LEFT JOIN p_d_debate ON p_d_d_tagged_t.p_d_debate_id = p_d_debate.id
LEFT JOIN p_tag ON p_d_d_tagged_t.p_tag_id = p_tag.id
WHERE p_d_debate.online = 1
AND p_d_debate.published = 1
$tagTypeDebateSql
$intervalDebateSql

UNION ALL

SELECT p_tag_id
FROM p_d_r_tagged_t
LEFT JOIN p_d_reaction ON p_d_r_tagged_t.p_d_reaction_id = p_d_reaction.id
LEFT JOIN p_tag ON p_d_r_tagged_t.p_tag_id = p_tag.id
WHERE p_d_reaction.online = 1
AND p_d_reaction.published = 1
$tagTypeReactionSql
$intervalReactionSql

UNION ALL

SELECT p_tag_id
FROM p_u_tagged_t
LEFT JOIN p_user ON p_u_tagged_t.p_user_id = p_user.id
LEFT JOIN p_tag ON p_u_tagged_t.p_tag_id = p_tag.id
WHERE p_user.online = 1
AND p_user.p_u_status_id = 1
$tagTypeUserSql
$intervalUserSql

) tables

GROUP BY p_tag_id
ORDER BY nb_tagged_objects desc
    ";

        return $sql;
    }

   /**
     * Alphabetical tags w. at least one publication
     *
     * @see app/sql/alphabeticalTags.sql
     *
     * @return string
     */
    public function createAlphabeticalTagsRawSql()
    {

        // Préparation requête SQL
        $sql = "
SELECT p_tag.id as id, p_tag.uuid as uuid, p_tag.p_t_tag_type_id as p_t_tag_type_id, p_tag.p_t_parent_id as p_t_parent_id, p_tag.p_user_id as p_user_id, p_tag.p_owner_id as p_owner_id, p_tag.title as title, p_tag.moderated as moderated, p_tag.moderated_at as moderated_at, p_tag.online as online, p_tag.created_at as created_at, p_tag.updated_at as updated_at, p_tag.slug as slug 
FROM `p_tag` 
LEFT JOIN `p_d_d_tagged_t` ON (p_tag.id=p_d_d_tagged_t.p_tag_id) 
LEFT JOIN `p_d_debate` ON (p_d_d_tagged_t.p_d_debate_id=p_d_debate.id) 
WHERE p_tag.online=1 AND p_tag.p_t_tag_type_id=:p_t_tag_type_id1 AND p_d_debate.online=1 AND p_d_debate.published=1

UNION DISTINCT

SELECT p_tag.id as id, p_tag.uuid as uuid, p_tag.p_t_tag_type_id as p_t_tag_type_id, p_tag.p_t_parent_id as p_t_parent_id, p_tag.p_user_id as p_user_id, p_tag.p_owner_id as p_owner_id, p_tag.title as title, p_tag.moderated as moderated, p_tag.moderated_at as moderated_at, p_tag.online as online, p_tag.created_at as created_at, p_tag.updated_at as updated_at, p_tag.slug as slug
FROM `p_tag` 
LEFT JOIN `p_d_r_tagged_t` ON (p_tag.id=p_d_r_tagged_t.p_tag_id) 
LEFT JOIN `p_d_reaction` ON (p_d_r_tagged_t.p_d_reaction_id=p_d_reaction.id) 
WHERE p_tag.online=1 AND p_tag.p_t_tag_type_id=:p_t_tag_type_id2 AND p_d_reaction.online=1 AND p_d_reaction.published=1 

ORDER BY title ASC
    ";

        return $sql;
    }

    /* ######################################################################################################## */
    /*                                            RAW SQL OPERATIONS                                            */
    /* ######################################################################################################## */

    /**
     * Get user's scores evolution as array of (id, created_at, sum_notes)
     *
     * @param int $tagTypeId
     * @param int $interval
     * @return array
     */
    public function generateMostPopularTagIds($tagTypeId, $interval)
    {
        // $this->logger->info('*** generateMostPopularTagIds');
        // $this->logger->info('$tagTypeId = ' . print_r($tagTypeId, true));
        // $this->logger->info('$interval = ' . print_r($interval, true));

        $con = \Propel::getConnection('default', \Propel::CONNECTION_READ);
        $stmt = $con->prepare($this->createMostPopularTagIdsRawSql($tagTypeId, $interval));


        if ($tagTypeId) {
            $stmt->bindValue(':tagTypeId', $tagTypeId, \PDO::PARAM_INT);
            $stmt->bindValue(':tagTypeId2', $tagTypeId, \PDO::PARAM_INT);
            $stmt->bindValue(':tagTypeId3', $tagTypeId, \PDO::PARAM_INT);
        }
        
        if ($interval) {
            $stmt->bindValue(':interval', $interval, \PDO::PARAM_INT);
            $stmt->bindValue(':interval2', $interval, \PDO::PARAM_INT);
            $stmt->bindValue(':interval3', $interval, \PDO::PARAM_INT);
        }

        $stmt->execute();
        $result = $stmt->fetchAll();

        $tagIds = array();
        foreach ($result as $row) {
            $tagIds[] = $row['p_tag_id'];
        }

        return $tagIds;
    }


    /**
     * Get tags alphabetical listing containing at least one publication (subject or reaction)
     *
     * @return PropelCollection[PTag]
     */
    public function generateAlphabeticalTags()
    {
        // $this->logger->info('*** generateAlphabeticalTags');

        $con = \Propel::getConnection('default', \Propel::CONNECTION_READ);
        $stmt = $con->prepare($this->createAlphabeticalTagsRawSql());


        $stmt->bindValue(':p_t_tag_type_id1', TagConstants::TAG_TYPE_THEME, \PDO::PARAM_INT);
        $stmt->bindValue(':p_t_tag_type_id2', TagConstants::TAG_TYPE_THEME, \PDO::PARAM_INT);

        $stmt->execute();
        $result = $stmt->fetchAll();

        $tags = new \PropelCollection();
        foreach ($result as $row) {
            $tag = new PTag();
            $tag->hydrate($row);

            $tags->append($tag);
        }

        return $tags;
    }

    /* ######################################################################################################## */
    /*                                            CRUD OPERATIONS                                               */
    /* ######################################################################################################## */


    /**
     * Get array of [uuid, tag]
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

    /* ######################################################################################################## */
    /*                                    RELATED TABLES OPERATIONS                                             */
    /* ######################################################################################################## */

    /**
     * Create a new PDDTaggedT
     *
     * @param integer $debateId
     * @param integer $tagId
     * @return PDDTaggedT
     */
    public function createDebateTag($debateId, $tagId)
    {
        $debateTag = PDDTaggedTQuery::create()
            ->filterByPTagId($tagId)
            ->filterByPDDebateId($debateId)
            ->findOne();

        if (!$debateTag) {
            $pddTaggedT = new PDDTaggedT();

            $pddTaggedT->setPDDebateId($debateId);
            $pddTaggedT->setPTagId($tagId);

            $pddTaggedT->save();

            return $pddTaggedT;
        }

        return null;
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
        $reactionTag = PDRTaggedTQuery::create()
            ->filterByPTagId($tagId)
            ->filterByPDReactionId($reactionId)
            ->findOne();

        if (!$reactionTag) {
            $pdrTaggedT = new PDRTaggedT();

            $pdrTaggedT->setPDReactionId($reactionId);
            $pdrTaggedT->setPTagId($tagId);

            $pdrTaggedT->save();

            return $pdrTaggedT;
        }

        return null;
    }

    /**
     * Create a new PEOPresetPT
     *
     * @param integer $debateId
     * @param integer $tagId
     * @return PDDTaggedT
     */
    public function createOperationTag($operationId, $tagId)
    {
        $presetTag = PEOPresetPTQuery::create()
            ->filterByPTagId($tagId)
            ->filterByPEOperationId($operationId)
            ->findOne();

        if (!$presetTag) {
            $presetPT = new PEOPresetPT();

            $presetPT->setPEOperationId($operationId);
            $presetPT->setPTagId($tagId);

            $presetPT->save();

            return $presetPT;
        }

        return null;
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
     * Delete a PEOPresetPT
     *
     * @param integer $operationId
     * @param integer $tagId
     * @return integer
     */
    public function deleteOperationTag($operationId, $tagId)
    {
        $result = PEOPresetPTQuery::create()
            ->filterByPEOperationId($operationId)
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

    /**
     * Create a new PTScopePLC association
     *
     * @param integer $tagId
     * @param integer $cityId
     * @return PTScopePLC
     */
    public function createTagCityScope($tagId, $cityId)
    {
        $scope = PTScopePLCQuery::create()
            ->filterByPTagId($tagId)
            ->filterByPLCityId($cityId)
            ->findOne();

        if (!$scope) {
            $scope = new PTScopePLC();

            $scope->setPTagId($tagId);
            $scope->setPLCityId($cityId);
            $scope->save();
            
            return $scope;
        }

        return null;
    }

    /**
     * Delete PTScopePLC
     *
     * @param integer $tagId
     * @param integer $cityId
     * @return integer
     */
    public function deleteTagCityScope($tagId, $cityId)
    {
        // Suppression élément(s)
        $result = PTScopePLCQuery::create()
            ->filterByPTagId($tagId)
            ->filterByPLCityId($cityId)
            ->delete();

        return $result;
    }
}
