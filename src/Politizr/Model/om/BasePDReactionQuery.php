<?php

namespace Politizr\Model\om;

use \BasePeer;
use \Criteria;
use \Exception;
use \ModelCriteria;
use \ModelJoin;
use \PDO;
use \Propel;
use \PropelCollection;
use \PropelException;
use \PropelObjectCollection;
use \PropelPDO;
use Politizr\Model\PCTopic;
use Politizr\Model\PDDebate;
use Politizr\Model\PDMedia;
use Politizr\Model\PDRComment;
use Politizr\Model\PDRTaggedT;
use Politizr\Model\PDReaction;
use Politizr\Model\PDReactionPeer;
use Politizr\Model\PDReactionQuery;
use Politizr\Model\PLCity;
use Politizr\Model\PLCountry;
use Politizr\Model\PLDepartment;
use Politizr\Model\PLRegion;
use Politizr\Model\PMReactionHistoric;
use Politizr\Model\PTag;
use Politizr\Model\PUBookmarkDR;
use Politizr\Model\PUTrackDR;
use Politizr\Model\PUser;

/**
 * @method PDReactionQuery orderById($order = Criteria::ASC) Order by the id column
 * @method PDReactionQuery orderByUuid($order = Criteria::ASC) Order by the uuid column
 * @method PDReactionQuery orderByPUserId($order = Criteria::ASC) Order by the p_user_id column
 * @method PDReactionQuery orderByPDDebateId($order = Criteria::ASC) Order by the p_d_debate_id column
 * @method PDReactionQuery orderByParentReactionId($order = Criteria::ASC) Order by the parent_reaction_id column
 * @method PDReactionQuery orderByPLCityId($order = Criteria::ASC) Order by the p_l_city_id column
 * @method PDReactionQuery orderByPLDepartmentId($order = Criteria::ASC) Order by the p_l_department_id column
 * @method PDReactionQuery orderByPLRegionId($order = Criteria::ASC) Order by the p_l_region_id column
 * @method PDReactionQuery orderByPLCountryId($order = Criteria::ASC) Order by the p_l_country_id column
 * @method PDReactionQuery orderByPCTopicId($order = Criteria::ASC) Order by the p_c_topic_id column
 * @method PDReactionQuery orderByFbAdId($order = Criteria::ASC) Order by the fb_ad_id column
 * @method PDReactionQuery orderByTitle($order = Criteria::ASC) Order by the title column
 * @method PDReactionQuery orderByFileName($order = Criteria::ASC) Order by the file_name column
 * @method PDReactionQuery orderByCopyright($order = Criteria::ASC) Order by the copyright column
 * @method PDReactionQuery orderByDescription($order = Criteria::ASC) Order by the description column
 * @method PDReactionQuery orderByNotePos($order = Criteria::ASC) Order by the note_pos column
 * @method PDReactionQuery orderByNoteNeg($order = Criteria::ASC) Order by the note_neg column
 * @method PDReactionQuery orderByNbViews($order = Criteria::ASC) Order by the nb_views column
 * @method PDReactionQuery orderByWantBoost($order = Criteria::ASC) Order by the want_boost column
 * @method PDReactionQuery orderByPublished($order = Criteria::ASC) Order by the published column
 * @method PDReactionQuery orderByPublishedAt($order = Criteria::ASC) Order by the published_at column
 * @method PDReactionQuery orderByPublishedBy($order = Criteria::ASC) Order by the published_by column
 * @method PDReactionQuery orderByFavorite($order = Criteria::ASC) Order by the favorite column
 * @method PDReactionQuery orderByOnline($order = Criteria::ASC) Order by the online column
 * @method PDReactionQuery orderByHomepage($order = Criteria::ASC) Order by the homepage column
 * @method PDReactionQuery orderByModerated($order = Criteria::ASC) Order by the moderated column
 * @method PDReactionQuery orderByModeratedPartial($order = Criteria::ASC) Order by the moderated_partial column
 * @method PDReactionQuery orderByModeratedAt($order = Criteria::ASC) Order by the moderated_at column
 * @method PDReactionQuery orderByIndexedAt($order = Criteria::ASC) Order by the indexed_at column
 * @method PDReactionQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method PDReactionQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 * @method PDReactionQuery orderBySlug($order = Criteria::ASC) Order by the slug column
 * @method PDReactionQuery orderByTreeLeft($order = Criteria::ASC) Order by the tree_left column
 * @method PDReactionQuery orderByTreeRight($order = Criteria::ASC) Order by the tree_right column
 * @method PDReactionQuery orderByTreeLevel($order = Criteria::ASC) Order by the tree_level column
 *
 * @method PDReactionQuery groupById() Group by the id column
 * @method PDReactionQuery groupByUuid() Group by the uuid column
 * @method PDReactionQuery groupByPUserId() Group by the p_user_id column
 * @method PDReactionQuery groupByPDDebateId() Group by the p_d_debate_id column
 * @method PDReactionQuery groupByParentReactionId() Group by the parent_reaction_id column
 * @method PDReactionQuery groupByPLCityId() Group by the p_l_city_id column
 * @method PDReactionQuery groupByPLDepartmentId() Group by the p_l_department_id column
 * @method PDReactionQuery groupByPLRegionId() Group by the p_l_region_id column
 * @method PDReactionQuery groupByPLCountryId() Group by the p_l_country_id column
 * @method PDReactionQuery groupByPCTopicId() Group by the p_c_topic_id column
 * @method PDReactionQuery groupByFbAdId() Group by the fb_ad_id column
 * @method PDReactionQuery groupByTitle() Group by the title column
 * @method PDReactionQuery groupByFileName() Group by the file_name column
 * @method PDReactionQuery groupByCopyright() Group by the copyright column
 * @method PDReactionQuery groupByDescription() Group by the description column
 * @method PDReactionQuery groupByNotePos() Group by the note_pos column
 * @method PDReactionQuery groupByNoteNeg() Group by the note_neg column
 * @method PDReactionQuery groupByNbViews() Group by the nb_views column
 * @method PDReactionQuery groupByWantBoost() Group by the want_boost column
 * @method PDReactionQuery groupByPublished() Group by the published column
 * @method PDReactionQuery groupByPublishedAt() Group by the published_at column
 * @method PDReactionQuery groupByPublishedBy() Group by the published_by column
 * @method PDReactionQuery groupByFavorite() Group by the favorite column
 * @method PDReactionQuery groupByOnline() Group by the online column
 * @method PDReactionQuery groupByHomepage() Group by the homepage column
 * @method PDReactionQuery groupByModerated() Group by the moderated column
 * @method PDReactionQuery groupByModeratedPartial() Group by the moderated_partial column
 * @method PDReactionQuery groupByModeratedAt() Group by the moderated_at column
 * @method PDReactionQuery groupByIndexedAt() Group by the indexed_at column
 * @method PDReactionQuery groupByCreatedAt() Group by the created_at column
 * @method PDReactionQuery groupByUpdatedAt() Group by the updated_at column
 * @method PDReactionQuery groupBySlug() Group by the slug column
 * @method PDReactionQuery groupByTreeLeft() Group by the tree_left column
 * @method PDReactionQuery groupByTreeRight() Group by the tree_right column
 * @method PDReactionQuery groupByTreeLevel() Group by the tree_level column
 *
 * @method PDReactionQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method PDReactionQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method PDReactionQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method PDReactionQuery leftJoinPUser($relationAlias = null) Adds a LEFT JOIN clause to the query using the PUser relation
 * @method PDReactionQuery rightJoinPUser($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PUser relation
 * @method PDReactionQuery innerJoinPUser($relationAlias = null) Adds a INNER JOIN clause to the query using the PUser relation
 *
 * @method PDReactionQuery leftJoinPDDebate($relationAlias = null) Adds a LEFT JOIN clause to the query using the PDDebate relation
 * @method PDReactionQuery rightJoinPDDebate($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PDDebate relation
 * @method PDReactionQuery innerJoinPDDebate($relationAlias = null) Adds a INNER JOIN clause to the query using the PDDebate relation
 *
 * @method PDReactionQuery leftJoinPLCity($relationAlias = null) Adds a LEFT JOIN clause to the query using the PLCity relation
 * @method PDReactionQuery rightJoinPLCity($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PLCity relation
 * @method PDReactionQuery innerJoinPLCity($relationAlias = null) Adds a INNER JOIN clause to the query using the PLCity relation
 *
 * @method PDReactionQuery leftJoinPLDepartment($relationAlias = null) Adds a LEFT JOIN clause to the query using the PLDepartment relation
 * @method PDReactionQuery rightJoinPLDepartment($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PLDepartment relation
 * @method PDReactionQuery innerJoinPLDepartment($relationAlias = null) Adds a INNER JOIN clause to the query using the PLDepartment relation
 *
 * @method PDReactionQuery leftJoinPLRegion($relationAlias = null) Adds a LEFT JOIN clause to the query using the PLRegion relation
 * @method PDReactionQuery rightJoinPLRegion($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PLRegion relation
 * @method PDReactionQuery innerJoinPLRegion($relationAlias = null) Adds a INNER JOIN clause to the query using the PLRegion relation
 *
 * @method PDReactionQuery leftJoinPLCountry($relationAlias = null) Adds a LEFT JOIN clause to the query using the PLCountry relation
 * @method PDReactionQuery rightJoinPLCountry($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PLCountry relation
 * @method PDReactionQuery innerJoinPLCountry($relationAlias = null) Adds a INNER JOIN clause to the query using the PLCountry relation
 *
 * @method PDReactionQuery leftJoinPCTopic($relationAlias = null) Adds a LEFT JOIN clause to the query using the PCTopic relation
 * @method PDReactionQuery rightJoinPCTopic($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PCTopic relation
 * @method PDReactionQuery innerJoinPCTopic($relationAlias = null) Adds a INNER JOIN clause to the query using the PCTopic relation
 *
 * @method PDReactionQuery leftJoinPuBookmarkDrPDReaction($relationAlias = null) Adds a LEFT JOIN clause to the query using the PuBookmarkDrPDReaction relation
 * @method PDReactionQuery rightJoinPuBookmarkDrPDReaction($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PuBookmarkDrPDReaction relation
 * @method PDReactionQuery innerJoinPuBookmarkDrPDReaction($relationAlias = null) Adds a INNER JOIN clause to the query using the PuBookmarkDrPDReaction relation
 *
 * @method PDReactionQuery leftJoinPuTrackDrPDReaction($relationAlias = null) Adds a LEFT JOIN clause to the query using the PuTrackDrPDReaction relation
 * @method PDReactionQuery rightJoinPuTrackDrPDReaction($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PuTrackDrPDReaction relation
 * @method PDReactionQuery innerJoinPuTrackDrPDReaction($relationAlias = null) Adds a INNER JOIN clause to the query using the PuTrackDrPDReaction relation
 *
 * @method PDReactionQuery leftJoinPDRComment($relationAlias = null) Adds a LEFT JOIN clause to the query using the PDRComment relation
 * @method PDReactionQuery rightJoinPDRComment($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PDRComment relation
 * @method PDReactionQuery innerJoinPDRComment($relationAlias = null) Adds a INNER JOIN clause to the query using the PDRComment relation
 *
 * @method PDReactionQuery leftJoinPDRTaggedT($relationAlias = null) Adds a LEFT JOIN clause to the query using the PDRTaggedT relation
 * @method PDReactionQuery rightJoinPDRTaggedT($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PDRTaggedT relation
 * @method PDReactionQuery innerJoinPDRTaggedT($relationAlias = null) Adds a INNER JOIN clause to the query using the PDRTaggedT relation
 *
 * @method PDReactionQuery leftJoinPDMedia($relationAlias = null) Adds a LEFT JOIN clause to the query using the PDMedia relation
 * @method PDReactionQuery rightJoinPDMedia($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PDMedia relation
 * @method PDReactionQuery innerJoinPDMedia($relationAlias = null) Adds a INNER JOIN clause to the query using the PDMedia relation
 *
 * @method PDReactionQuery leftJoinPMReactionHistoric($relationAlias = null) Adds a LEFT JOIN clause to the query using the PMReactionHistoric relation
 * @method PDReactionQuery rightJoinPMReactionHistoric($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PMReactionHistoric relation
 * @method PDReactionQuery innerJoinPMReactionHistoric($relationAlias = null) Adds a INNER JOIN clause to the query using the PMReactionHistoric relation
 *
 * @method PDReaction findOne(PropelPDO $con = null) Return the first PDReaction matching the query
 * @method PDReaction findOneOrCreate(PropelPDO $con = null) Return the first PDReaction matching the query, or a new PDReaction object populated from the query conditions when no match is found
 *
 * @method PDReaction findOneByUuid(string $uuid) Return the first PDReaction filtered by the uuid column
 * @method PDReaction findOneByPUserId(int $p_user_id) Return the first PDReaction filtered by the p_user_id column
 * @method PDReaction findOneByPDDebateId(int $p_d_debate_id) Return the first PDReaction filtered by the p_d_debate_id column
 * @method PDReaction findOneByParentReactionId(int $parent_reaction_id) Return the first PDReaction filtered by the parent_reaction_id column
 * @method PDReaction findOneByPLCityId(int $p_l_city_id) Return the first PDReaction filtered by the p_l_city_id column
 * @method PDReaction findOneByPLDepartmentId(int $p_l_department_id) Return the first PDReaction filtered by the p_l_department_id column
 * @method PDReaction findOneByPLRegionId(int $p_l_region_id) Return the first PDReaction filtered by the p_l_region_id column
 * @method PDReaction findOneByPLCountryId(int $p_l_country_id) Return the first PDReaction filtered by the p_l_country_id column
 * @method PDReaction findOneByPCTopicId(int $p_c_topic_id) Return the first PDReaction filtered by the p_c_topic_id column
 * @method PDReaction findOneByFbAdId(string $fb_ad_id) Return the first PDReaction filtered by the fb_ad_id column
 * @method PDReaction findOneByTitle(string $title) Return the first PDReaction filtered by the title column
 * @method PDReaction findOneByFileName(string $file_name) Return the first PDReaction filtered by the file_name column
 * @method PDReaction findOneByCopyright(string $copyright) Return the first PDReaction filtered by the copyright column
 * @method PDReaction findOneByDescription(string $description) Return the first PDReaction filtered by the description column
 * @method PDReaction findOneByNotePos(int $note_pos) Return the first PDReaction filtered by the note_pos column
 * @method PDReaction findOneByNoteNeg(int $note_neg) Return the first PDReaction filtered by the note_neg column
 * @method PDReaction findOneByNbViews(int $nb_views) Return the first PDReaction filtered by the nb_views column
 * @method PDReaction findOneByWantBoost(int $want_boost) Return the first PDReaction filtered by the want_boost column
 * @method PDReaction findOneByPublished(boolean $published) Return the first PDReaction filtered by the published column
 * @method PDReaction findOneByPublishedAt(string $published_at) Return the first PDReaction filtered by the published_at column
 * @method PDReaction findOneByPublishedBy(string $published_by) Return the first PDReaction filtered by the published_by column
 * @method PDReaction findOneByFavorite(boolean $favorite) Return the first PDReaction filtered by the favorite column
 * @method PDReaction findOneByOnline(boolean $online) Return the first PDReaction filtered by the online column
 * @method PDReaction findOneByHomepage(boolean $homepage) Return the first PDReaction filtered by the homepage column
 * @method PDReaction findOneByModerated(boolean $moderated) Return the first PDReaction filtered by the moderated column
 * @method PDReaction findOneByModeratedPartial(boolean $moderated_partial) Return the first PDReaction filtered by the moderated_partial column
 * @method PDReaction findOneByModeratedAt(string $moderated_at) Return the first PDReaction filtered by the moderated_at column
 * @method PDReaction findOneByIndexedAt(string $indexed_at) Return the first PDReaction filtered by the indexed_at column
 * @method PDReaction findOneByCreatedAt(string $created_at) Return the first PDReaction filtered by the created_at column
 * @method PDReaction findOneByUpdatedAt(string $updated_at) Return the first PDReaction filtered by the updated_at column
 * @method PDReaction findOneBySlug(string $slug) Return the first PDReaction filtered by the slug column
 * @method PDReaction findOneByTreeLeft(int $tree_left) Return the first PDReaction filtered by the tree_left column
 * @method PDReaction findOneByTreeRight(int $tree_right) Return the first PDReaction filtered by the tree_right column
 * @method PDReaction findOneByTreeLevel(int $tree_level) Return the first PDReaction filtered by the tree_level column
 *
 * @method array findById(int $id) Return PDReaction objects filtered by the id column
 * @method array findByUuid(string $uuid) Return PDReaction objects filtered by the uuid column
 * @method array findByPUserId(int $p_user_id) Return PDReaction objects filtered by the p_user_id column
 * @method array findByPDDebateId(int $p_d_debate_id) Return PDReaction objects filtered by the p_d_debate_id column
 * @method array findByParentReactionId(int $parent_reaction_id) Return PDReaction objects filtered by the parent_reaction_id column
 * @method array findByPLCityId(int $p_l_city_id) Return PDReaction objects filtered by the p_l_city_id column
 * @method array findByPLDepartmentId(int $p_l_department_id) Return PDReaction objects filtered by the p_l_department_id column
 * @method array findByPLRegionId(int $p_l_region_id) Return PDReaction objects filtered by the p_l_region_id column
 * @method array findByPLCountryId(int $p_l_country_id) Return PDReaction objects filtered by the p_l_country_id column
 * @method array findByPCTopicId(int $p_c_topic_id) Return PDReaction objects filtered by the p_c_topic_id column
 * @method array findByFbAdId(string $fb_ad_id) Return PDReaction objects filtered by the fb_ad_id column
 * @method array findByTitle(string $title) Return PDReaction objects filtered by the title column
 * @method array findByFileName(string $file_name) Return PDReaction objects filtered by the file_name column
 * @method array findByCopyright(string $copyright) Return PDReaction objects filtered by the copyright column
 * @method array findByDescription(string $description) Return PDReaction objects filtered by the description column
 * @method array findByNotePos(int $note_pos) Return PDReaction objects filtered by the note_pos column
 * @method array findByNoteNeg(int $note_neg) Return PDReaction objects filtered by the note_neg column
 * @method array findByNbViews(int $nb_views) Return PDReaction objects filtered by the nb_views column
 * @method array findByWantBoost(int $want_boost) Return PDReaction objects filtered by the want_boost column
 * @method array findByPublished(boolean $published) Return PDReaction objects filtered by the published column
 * @method array findByPublishedAt(string $published_at) Return PDReaction objects filtered by the published_at column
 * @method array findByPublishedBy(string $published_by) Return PDReaction objects filtered by the published_by column
 * @method array findByFavorite(boolean $favorite) Return PDReaction objects filtered by the favorite column
 * @method array findByOnline(boolean $online) Return PDReaction objects filtered by the online column
 * @method array findByHomepage(boolean $homepage) Return PDReaction objects filtered by the homepage column
 * @method array findByModerated(boolean $moderated) Return PDReaction objects filtered by the moderated column
 * @method array findByModeratedPartial(boolean $moderated_partial) Return PDReaction objects filtered by the moderated_partial column
 * @method array findByModeratedAt(string $moderated_at) Return PDReaction objects filtered by the moderated_at column
 * @method array findByIndexedAt(string $indexed_at) Return PDReaction objects filtered by the indexed_at column
 * @method array findByCreatedAt(string $created_at) Return PDReaction objects filtered by the created_at column
 * @method array findByUpdatedAt(string $updated_at) Return PDReaction objects filtered by the updated_at column
 * @method array findBySlug(string $slug) Return PDReaction objects filtered by the slug column
 * @method array findByTreeLeft(int $tree_left) Return PDReaction objects filtered by the tree_left column
 * @method array findByTreeRight(int $tree_right) Return PDReaction objects filtered by the tree_right column
 * @method array findByTreeLevel(int $tree_level) Return PDReaction objects filtered by the tree_level column
 */
abstract class BasePDReactionQuery extends ModelCriteria
{
    // query_cache behavior
    protected $queryKey = '';

    // archivable behavior
    protected $archiveOnDelete = true;

    /**
     * Initializes internal state of BasePDReactionQuery object.
     *
     * @param     string $dbName The dabase name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = null, $modelName = null, $modelAlias = null)
    {
        if (null === $dbName) {
            $dbName = 'default';
        }
        if (null === $modelName) {
            $modelName = 'Politizr\\Model\\PDReaction';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new PDReactionQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   PDReactionQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return PDReactionQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof PDReactionQuery) {
            return $criteria;
        }
        $query = new PDReactionQuery(null, null, $modelAlias);

        if ($criteria instanceof Criteria) {
            $query->mergeWith($criteria);
        }

        return $query;
    }

    /**
     * Find object by primary key.
     * Propel uses the instance pool to skip the database if the object exists.
     * Go fast if the query is untouched.
     *
     * <code>
     * $obj  = $c->findPk(12, $con);
     * </code>
     *
     * @param mixed $key Primary key to use for the query
     * @param     PropelPDO $con an optional connection object
     *
     * @return   PDReaction|PDReaction[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = PDReactionPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(PDReactionPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }
        $this->basePreSelect($con);
        if ($this->formatter || $this->modelAlias || $this->with || $this->select
         || $this->selectColumns || $this->asColumns || $this->selectModifiers
         || $this->map || $this->having || $this->joins) {
            return $this->findPkComplex($key, $con);
        } else {
            return $this->findPkSimple($key, $con);
        }
    }

    /**
     * Alias of findPk to use instance pooling
     *
     * @param     mixed $key Primary key to use for the query
     * @param     PropelPDO $con A connection object
     *
     * @return                 PDReaction A model object, or null if the key is not found
     * @throws PropelException
     */
     public function findOneById($key, $con = null)
     {
        return $this->findPk($key, $con);
     }

    /**
     * Find object by primary key using raw SQL to go fast.
     * Bypass doSelect() and the object formatter by using generated code.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     PropelPDO $con A connection object
     *
     * @return                 PDReaction A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `uuid`, `p_user_id`, `p_d_debate_id`, `parent_reaction_id`, `p_l_city_id`, `p_l_department_id`, `p_l_region_id`, `p_l_country_id`, `p_c_topic_id`, `fb_ad_id`, `title`, `file_name`, `copyright`, `description`, `note_pos`, `note_neg`, `nb_views`, `want_boost`, `published`, `published_at`, `published_by`, `favorite`, `online`, `homepage`, `moderated`, `moderated_partial`, `moderated_at`, `indexed_at`, `created_at`, `updated_at`, `slug`, `tree_left`, `tree_right`, `tree_level` FROM `p_d_reaction` WHERE `id` = :p0';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key, PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $obj = new PDReaction();
            $obj->hydrate($row);
            PDReactionPeer::addInstanceToPool($obj, (string) $key);
        }
        $stmt->closeCursor();

        return $obj;
    }

    /**
     * Find object by primary key.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     PropelPDO $con A connection object
     *
     * @return PDReaction|PDReaction[]|mixed the result, formatted by the current formatter
     */
    protected function findPkComplex($key, $con)
    {
        // As the query uses a PK condition, no limit(1) is necessary.
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $stmt = $criteria
            ->filterByPrimaryKey($key)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->formatOne($stmt);
    }

    /**
     * Find objects by primary key
     * <code>
     * $objs = $c->findPks(array(12, 56, 832), $con);
     * </code>
     * @param     array $keys Primary keys to use for the query
     * @param     PropelPDO $con an optional connection object
     *
     * @return PropelObjectCollection|PDReaction[]|mixed the list of results, formatted by the current formatter
     */
    public function findPks($keys, $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection($this->getDbName(), Propel::CONNECTION_READ);
        }
        $this->basePreSelect($con);
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $stmt = $criteria
            ->filterByPrimaryKeys($keys)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->format($stmt);
    }

    /**
     * Filter the query by primary key
     *
     * @param     mixed $key Primary key to use for the query
     *
     * @return PDReactionQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(PDReactionPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return PDReactionQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(PDReactionPeer::ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE id = 1234
     * $query->filterById(array(12, 34)); // WHERE id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE id >= 12
     * $query->filterById(array('max' => 12)); // WHERE id <= 12
     * </code>
     *
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PDReactionQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(PDReactionPeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(PDReactionPeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PDReactionPeer::ID, $id, $comparison);
    }

    /**
     * Filter the query on the uuid column
     *
     * Example usage:
     * <code>
     * $query->filterByUuid('fooValue');   // WHERE uuid = 'fooValue'
     * $query->filterByUuid('%fooValue%'); // WHERE uuid LIKE '%fooValue%'
     * </code>
     *
     * @param     string $uuid The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PDReactionQuery The current query, for fluid interface
     */
    public function filterByUuid($uuid = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($uuid)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $uuid)) {
                $uuid = str_replace('*', '%', $uuid);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(PDReactionPeer::UUID, $uuid, $comparison);
    }

    /**
     * Filter the query on the p_user_id column
     *
     * Example usage:
     * <code>
     * $query->filterByPUserId(1234); // WHERE p_user_id = 1234
     * $query->filterByPUserId(array(12, 34)); // WHERE p_user_id IN (12, 34)
     * $query->filterByPUserId(array('min' => 12)); // WHERE p_user_id >= 12
     * $query->filterByPUserId(array('max' => 12)); // WHERE p_user_id <= 12
     * </code>
     *
     * @see       filterByPUser()
     *
     * @param     mixed $pUserId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PDReactionQuery The current query, for fluid interface
     */
    public function filterByPUserId($pUserId = null, $comparison = null)
    {
        if (is_array($pUserId)) {
            $useMinMax = false;
            if (isset($pUserId['min'])) {
                $this->addUsingAlias(PDReactionPeer::P_USER_ID, $pUserId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($pUserId['max'])) {
                $this->addUsingAlias(PDReactionPeer::P_USER_ID, $pUserId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PDReactionPeer::P_USER_ID, $pUserId, $comparison);
    }

    /**
     * Filter the query on the p_d_debate_id column
     *
     * Example usage:
     * <code>
     * $query->filterByPDDebateId(1234); // WHERE p_d_debate_id = 1234
     * $query->filterByPDDebateId(array(12, 34)); // WHERE p_d_debate_id IN (12, 34)
     * $query->filterByPDDebateId(array('min' => 12)); // WHERE p_d_debate_id >= 12
     * $query->filterByPDDebateId(array('max' => 12)); // WHERE p_d_debate_id <= 12
     * </code>
     *
     * @see       filterByPDDebate()
     *
     * @param     mixed $pDDebateId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PDReactionQuery The current query, for fluid interface
     */
    public function filterByPDDebateId($pDDebateId = null, $comparison = null)
    {
        if (is_array($pDDebateId)) {
            $useMinMax = false;
            if (isset($pDDebateId['min'])) {
                $this->addUsingAlias(PDReactionPeer::P_D_DEBATE_ID, $pDDebateId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($pDDebateId['max'])) {
                $this->addUsingAlias(PDReactionPeer::P_D_DEBATE_ID, $pDDebateId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PDReactionPeer::P_D_DEBATE_ID, $pDDebateId, $comparison);
    }

    /**
     * Filter the query on the parent_reaction_id column
     *
     * Example usage:
     * <code>
     * $query->filterByParentReactionId(1234); // WHERE parent_reaction_id = 1234
     * $query->filterByParentReactionId(array(12, 34)); // WHERE parent_reaction_id IN (12, 34)
     * $query->filterByParentReactionId(array('min' => 12)); // WHERE parent_reaction_id >= 12
     * $query->filterByParentReactionId(array('max' => 12)); // WHERE parent_reaction_id <= 12
     * </code>
     *
     * @param     mixed $parentReactionId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PDReactionQuery The current query, for fluid interface
     */
    public function filterByParentReactionId($parentReactionId = null, $comparison = null)
    {
        if (is_array($parentReactionId)) {
            $useMinMax = false;
            if (isset($parentReactionId['min'])) {
                $this->addUsingAlias(PDReactionPeer::PARENT_REACTION_ID, $parentReactionId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($parentReactionId['max'])) {
                $this->addUsingAlias(PDReactionPeer::PARENT_REACTION_ID, $parentReactionId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PDReactionPeer::PARENT_REACTION_ID, $parentReactionId, $comparison);
    }

    /**
     * Filter the query on the p_l_city_id column
     *
     * Example usage:
     * <code>
     * $query->filterByPLCityId(1234); // WHERE p_l_city_id = 1234
     * $query->filterByPLCityId(array(12, 34)); // WHERE p_l_city_id IN (12, 34)
     * $query->filterByPLCityId(array('min' => 12)); // WHERE p_l_city_id >= 12
     * $query->filterByPLCityId(array('max' => 12)); // WHERE p_l_city_id <= 12
     * </code>
     *
     * @see       filterByPLCity()
     *
     * @param     mixed $pLCityId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PDReactionQuery The current query, for fluid interface
     */
    public function filterByPLCityId($pLCityId = null, $comparison = null)
    {
        if (is_array($pLCityId)) {
            $useMinMax = false;
            if (isset($pLCityId['min'])) {
                $this->addUsingAlias(PDReactionPeer::P_L_CITY_ID, $pLCityId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($pLCityId['max'])) {
                $this->addUsingAlias(PDReactionPeer::P_L_CITY_ID, $pLCityId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PDReactionPeer::P_L_CITY_ID, $pLCityId, $comparison);
    }

    /**
     * Filter the query on the p_l_department_id column
     *
     * Example usage:
     * <code>
     * $query->filterByPLDepartmentId(1234); // WHERE p_l_department_id = 1234
     * $query->filterByPLDepartmentId(array(12, 34)); // WHERE p_l_department_id IN (12, 34)
     * $query->filterByPLDepartmentId(array('min' => 12)); // WHERE p_l_department_id >= 12
     * $query->filterByPLDepartmentId(array('max' => 12)); // WHERE p_l_department_id <= 12
     * </code>
     *
     * @see       filterByPLDepartment()
     *
     * @param     mixed $pLDepartmentId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PDReactionQuery The current query, for fluid interface
     */
    public function filterByPLDepartmentId($pLDepartmentId = null, $comparison = null)
    {
        if (is_array($pLDepartmentId)) {
            $useMinMax = false;
            if (isset($pLDepartmentId['min'])) {
                $this->addUsingAlias(PDReactionPeer::P_L_DEPARTMENT_ID, $pLDepartmentId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($pLDepartmentId['max'])) {
                $this->addUsingAlias(PDReactionPeer::P_L_DEPARTMENT_ID, $pLDepartmentId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PDReactionPeer::P_L_DEPARTMENT_ID, $pLDepartmentId, $comparison);
    }

    /**
     * Filter the query on the p_l_region_id column
     *
     * Example usage:
     * <code>
     * $query->filterByPLRegionId(1234); // WHERE p_l_region_id = 1234
     * $query->filterByPLRegionId(array(12, 34)); // WHERE p_l_region_id IN (12, 34)
     * $query->filterByPLRegionId(array('min' => 12)); // WHERE p_l_region_id >= 12
     * $query->filterByPLRegionId(array('max' => 12)); // WHERE p_l_region_id <= 12
     * </code>
     *
     * @see       filterByPLRegion()
     *
     * @param     mixed $pLRegionId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PDReactionQuery The current query, for fluid interface
     */
    public function filterByPLRegionId($pLRegionId = null, $comparison = null)
    {
        if (is_array($pLRegionId)) {
            $useMinMax = false;
            if (isset($pLRegionId['min'])) {
                $this->addUsingAlias(PDReactionPeer::P_L_REGION_ID, $pLRegionId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($pLRegionId['max'])) {
                $this->addUsingAlias(PDReactionPeer::P_L_REGION_ID, $pLRegionId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PDReactionPeer::P_L_REGION_ID, $pLRegionId, $comparison);
    }

    /**
     * Filter the query on the p_l_country_id column
     *
     * Example usage:
     * <code>
     * $query->filterByPLCountryId(1234); // WHERE p_l_country_id = 1234
     * $query->filterByPLCountryId(array(12, 34)); // WHERE p_l_country_id IN (12, 34)
     * $query->filterByPLCountryId(array('min' => 12)); // WHERE p_l_country_id >= 12
     * $query->filterByPLCountryId(array('max' => 12)); // WHERE p_l_country_id <= 12
     * </code>
     *
     * @see       filterByPLCountry()
     *
     * @param     mixed $pLCountryId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PDReactionQuery The current query, for fluid interface
     */
    public function filterByPLCountryId($pLCountryId = null, $comparison = null)
    {
        if (is_array($pLCountryId)) {
            $useMinMax = false;
            if (isset($pLCountryId['min'])) {
                $this->addUsingAlias(PDReactionPeer::P_L_COUNTRY_ID, $pLCountryId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($pLCountryId['max'])) {
                $this->addUsingAlias(PDReactionPeer::P_L_COUNTRY_ID, $pLCountryId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PDReactionPeer::P_L_COUNTRY_ID, $pLCountryId, $comparison);
    }

    /**
     * Filter the query on the p_c_topic_id column
     *
     * Example usage:
     * <code>
     * $query->filterByPCTopicId(1234); // WHERE p_c_topic_id = 1234
     * $query->filterByPCTopicId(array(12, 34)); // WHERE p_c_topic_id IN (12, 34)
     * $query->filterByPCTopicId(array('min' => 12)); // WHERE p_c_topic_id >= 12
     * $query->filterByPCTopicId(array('max' => 12)); // WHERE p_c_topic_id <= 12
     * </code>
     *
     * @see       filterByPCTopic()
     *
     * @param     mixed $pCTopicId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PDReactionQuery The current query, for fluid interface
     */
    public function filterByPCTopicId($pCTopicId = null, $comparison = null)
    {
        if (is_array($pCTopicId)) {
            $useMinMax = false;
            if (isset($pCTopicId['min'])) {
                $this->addUsingAlias(PDReactionPeer::P_C_TOPIC_ID, $pCTopicId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($pCTopicId['max'])) {
                $this->addUsingAlias(PDReactionPeer::P_C_TOPIC_ID, $pCTopicId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PDReactionPeer::P_C_TOPIC_ID, $pCTopicId, $comparison);
    }

    /**
     * Filter the query on the fb_ad_id column
     *
     * Example usage:
     * <code>
     * $query->filterByFbAdId('fooValue');   // WHERE fb_ad_id = 'fooValue'
     * $query->filterByFbAdId('%fooValue%'); // WHERE fb_ad_id LIKE '%fooValue%'
     * </code>
     *
     * @param     string $fbAdId The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PDReactionQuery The current query, for fluid interface
     */
    public function filterByFbAdId($fbAdId = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($fbAdId)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $fbAdId)) {
                $fbAdId = str_replace('*', '%', $fbAdId);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(PDReactionPeer::FB_AD_ID, $fbAdId, $comparison);
    }

    /**
     * Filter the query on the title column
     *
     * Example usage:
     * <code>
     * $query->filterByTitle('fooValue');   // WHERE title = 'fooValue'
     * $query->filterByTitle('%fooValue%'); // WHERE title LIKE '%fooValue%'
     * </code>
     *
     * @param     string $title The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PDReactionQuery The current query, for fluid interface
     */
    public function filterByTitle($title = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($title)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $title)) {
                $title = str_replace('*', '%', $title);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(PDReactionPeer::TITLE, $title, $comparison);
    }

    /**
     * Filter the query on the file_name column
     *
     * Example usage:
     * <code>
     * $query->filterByFileName('fooValue');   // WHERE file_name = 'fooValue'
     * $query->filterByFileName('%fooValue%'); // WHERE file_name LIKE '%fooValue%'
     * </code>
     *
     * @param     string $fileName The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PDReactionQuery The current query, for fluid interface
     */
    public function filterByFileName($fileName = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($fileName)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $fileName)) {
                $fileName = str_replace('*', '%', $fileName);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(PDReactionPeer::FILE_NAME, $fileName, $comparison);
    }

    /**
     * Filter the query on the copyright column
     *
     * Example usage:
     * <code>
     * $query->filterByCopyright('fooValue');   // WHERE copyright = 'fooValue'
     * $query->filterByCopyright('%fooValue%'); // WHERE copyright LIKE '%fooValue%'
     * </code>
     *
     * @param     string $copyright The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PDReactionQuery The current query, for fluid interface
     */
    public function filterByCopyright($copyright = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($copyright)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $copyright)) {
                $copyright = str_replace('*', '%', $copyright);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(PDReactionPeer::COPYRIGHT, $copyright, $comparison);
    }

    /**
     * Filter the query on the description column
     *
     * Example usage:
     * <code>
     * $query->filterByDescription('fooValue');   // WHERE description = 'fooValue'
     * $query->filterByDescription('%fooValue%'); // WHERE description LIKE '%fooValue%'
     * </code>
     *
     * @param     string $description The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PDReactionQuery The current query, for fluid interface
     */
    public function filterByDescription($description = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($description)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $description)) {
                $description = str_replace('*', '%', $description);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(PDReactionPeer::DESCRIPTION, $description, $comparison);
    }

    /**
     * Filter the query on the note_pos column
     *
     * Example usage:
     * <code>
     * $query->filterByNotePos(1234); // WHERE note_pos = 1234
     * $query->filterByNotePos(array(12, 34)); // WHERE note_pos IN (12, 34)
     * $query->filterByNotePos(array('min' => 12)); // WHERE note_pos >= 12
     * $query->filterByNotePos(array('max' => 12)); // WHERE note_pos <= 12
     * </code>
     *
     * @param     mixed $notePos The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PDReactionQuery The current query, for fluid interface
     */
    public function filterByNotePos($notePos = null, $comparison = null)
    {
        if (is_array($notePos)) {
            $useMinMax = false;
            if (isset($notePos['min'])) {
                $this->addUsingAlias(PDReactionPeer::NOTE_POS, $notePos['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($notePos['max'])) {
                $this->addUsingAlias(PDReactionPeer::NOTE_POS, $notePos['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PDReactionPeer::NOTE_POS, $notePos, $comparison);
    }

    /**
     * Filter the query on the note_neg column
     *
     * Example usage:
     * <code>
     * $query->filterByNoteNeg(1234); // WHERE note_neg = 1234
     * $query->filterByNoteNeg(array(12, 34)); // WHERE note_neg IN (12, 34)
     * $query->filterByNoteNeg(array('min' => 12)); // WHERE note_neg >= 12
     * $query->filterByNoteNeg(array('max' => 12)); // WHERE note_neg <= 12
     * </code>
     *
     * @param     mixed $noteNeg The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PDReactionQuery The current query, for fluid interface
     */
    public function filterByNoteNeg($noteNeg = null, $comparison = null)
    {
        if (is_array($noteNeg)) {
            $useMinMax = false;
            if (isset($noteNeg['min'])) {
                $this->addUsingAlias(PDReactionPeer::NOTE_NEG, $noteNeg['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($noteNeg['max'])) {
                $this->addUsingAlias(PDReactionPeer::NOTE_NEG, $noteNeg['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PDReactionPeer::NOTE_NEG, $noteNeg, $comparison);
    }

    /**
     * Filter the query on the nb_views column
     *
     * Example usage:
     * <code>
     * $query->filterByNbViews(1234); // WHERE nb_views = 1234
     * $query->filterByNbViews(array(12, 34)); // WHERE nb_views IN (12, 34)
     * $query->filterByNbViews(array('min' => 12)); // WHERE nb_views >= 12
     * $query->filterByNbViews(array('max' => 12)); // WHERE nb_views <= 12
     * </code>
     *
     * @param     mixed $nbViews The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PDReactionQuery The current query, for fluid interface
     */
    public function filterByNbViews($nbViews = null, $comparison = null)
    {
        if (is_array($nbViews)) {
            $useMinMax = false;
            if (isset($nbViews['min'])) {
                $this->addUsingAlias(PDReactionPeer::NB_VIEWS, $nbViews['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($nbViews['max'])) {
                $this->addUsingAlias(PDReactionPeer::NB_VIEWS, $nbViews['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PDReactionPeer::NB_VIEWS, $nbViews, $comparison);
    }

    /**
     * Filter the query on the want_boost column
     *
     * Example usage:
     * <code>
     * $query->filterByWantBoost(1234); // WHERE want_boost = 1234
     * $query->filterByWantBoost(array(12, 34)); // WHERE want_boost IN (12, 34)
     * $query->filterByWantBoost(array('min' => 12)); // WHERE want_boost >= 12
     * $query->filterByWantBoost(array('max' => 12)); // WHERE want_boost <= 12
     * </code>
     *
     * @param     mixed $wantBoost The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PDReactionQuery The current query, for fluid interface
     */
    public function filterByWantBoost($wantBoost = null, $comparison = null)
    {
        if (is_array($wantBoost)) {
            $useMinMax = false;
            if (isset($wantBoost['min'])) {
                $this->addUsingAlias(PDReactionPeer::WANT_BOOST, $wantBoost['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($wantBoost['max'])) {
                $this->addUsingAlias(PDReactionPeer::WANT_BOOST, $wantBoost['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PDReactionPeer::WANT_BOOST, $wantBoost, $comparison);
    }

    /**
     * Filter the query on the published column
     *
     * Example usage:
     * <code>
     * $query->filterByPublished(true); // WHERE published = true
     * $query->filterByPublished('yes'); // WHERE published = true
     * </code>
     *
     * @param     boolean|string $published The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PDReactionQuery The current query, for fluid interface
     */
    public function filterByPublished($published = null, $comparison = null)
    {
        if (is_string($published)) {
            $published = in_array(strtolower($published), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(PDReactionPeer::PUBLISHED, $published, $comparison);
    }

    /**
     * Filter the query on the published_at column
     *
     * Example usage:
     * <code>
     * $query->filterByPublishedAt('2011-03-14'); // WHERE published_at = '2011-03-14'
     * $query->filterByPublishedAt('now'); // WHERE published_at = '2011-03-14'
     * $query->filterByPublishedAt(array('max' => 'yesterday')); // WHERE published_at < '2011-03-13'
     * </code>
     *
     * @param     mixed $publishedAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PDReactionQuery The current query, for fluid interface
     */
    public function filterByPublishedAt($publishedAt = null, $comparison = null)
    {
        if (is_array($publishedAt)) {
            $useMinMax = false;
            if (isset($publishedAt['min'])) {
                $this->addUsingAlias(PDReactionPeer::PUBLISHED_AT, $publishedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($publishedAt['max'])) {
                $this->addUsingAlias(PDReactionPeer::PUBLISHED_AT, $publishedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PDReactionPeer::PUBLISHED_AT, $publishedAt, $comparison);
    }

    /**
     * Filter the query on the published_by column
     *
     * Example usage:
     * <code>
     * $query->filterByPublishedBy('fooValue');   // WHERE published_by = 'fooValue'
     * $query->filterByPublishedBy('%fooValue%'); // WHERE published_by LIKE '%fooValue%'
     * </code>
     *
     * @param     string $publishedBy The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PDReactionQuery The current query, for fluid interface
     */
    public function filterByPublishedBy($publishedBy = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($publishedBy)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $publishedBy)) {
                $publishedBy = str_replace('*', '%', $publishedBy);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(PDReactionPeer::PUBLISHED_BY, $publishedBy, $comparison);
    }

    /**
     * Filter the query on the favorite column
     *
     * Example usage:
     * <code>
     * $query->filterByFavorite(true); // WHERE favorite = true
     * $query->filterByFavorite('yes'); // WHERE favorite = true
     * </code>
     *
     * @param     boolean|string $favorite The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PDReactionQuery The current query, for fluid interface
     */
    public function filterByFavorite($favorite = null, $comparison = null)
    {
        if (is_string($favorite)) {
            $favorite = in_array(strtolower($favorite), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(PDReactionPeer::FAVORITE, $favorite, $comparison);
    }

    /**
     * Filter the query on the online column
     *
     * Example usage:
     * <code>
     * $query->filterByOnline(true); // WHERE online = true
     * $query->filterByOnline('yes'); // WHERE online = true
     * </code>
     *
     * @param     boolean|string $online The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PDReactionQuery The current query, for fluid interface
     */
    public function filterByOnline($online = null, $comparison = null)
    {
        if (is_string($online)) {
            $online = in_array(strtolower($online), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(PDReactionPeer::ONLINE, $online, $comparison);
    }

    /**
     * Filter the query on the homepage column
     *
     * Example usage:
     * <code>
     * $query->filterByHomepage(true); // WHERE homepage = true
     * $query->filterByHomepage('yes'); // WHERE homepage = true
     * </code>
     *
     * @param     boolean|string $homepage The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PDReactionQuery The current query, for fluid interface
     */
    public function filterByHomepage($homepage = null, $comparison = null)
    {
        if (is_string($homepage)) {
            $homepage = in_array(strtolower($homepage), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(PDReactionPeer::HOMEPAGE, $homepage, $comparison);
    }

    /**
     * Filter the query on the moderated column
     *
     * Example usage:
     * <code>
     * $query->filterByModerated(true); // WHERE moderated = true
     * $query->filterByModerated('yes'); // WHERE moderated = true
     * </code>
     *
     * @param     boolean|string $moderated The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PDReactionQuery The current query, for fluid interface
     */
    public function filterByModerated($moderated = null, $comparison = null)
    {
        if (is_string($moderated)) {
            $moderated = in_array(strtolower($moderated), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(PDReactionPeer::MODERATED, $moderated, $comparison);
    }

    /**
     * Filter the query on the moderated_partial column
     *
     * Example usage:
     * <code>
     * $query->filterByModeratedPartial(true); // WHERE moderated_partial = true
     * $query->filterByModeratedPartial('yes'); // WHERE moderated_partial = true
     * </code>
     *
     * @param     boolean|string $moderatedPartial The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PDReactionQuery The current query, for fluid interface
     */
    public function filterByModeratedPartial($moderatedPartial = null, $comparison = null)
    {
        if (is_string($moderatedPartial)) {
            $moderatedPartial = in_array(strtolower($moderatedPartial), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(PDReactionPeer::MODERATED_PARTIAL, $moderatedPartial, $comparison);
    }

    /**
     * Filter the query on the moderated_at column
     *
     * Example usage:
     * <code>
     * $query->filterByModeratedAt('2011-03-14'); // WHERE moderated_at = '2011-03-14'
     * $query->filterByModeratedAt('now'); // WHERE moderated_at = '2011-03-14'
     * $query->filterByModeratedAt(array('max' => 'yesterday')); // WHERE moderated_at < '2011-03-13'
     * </code>
     *
     * @param     mixed $moderatedAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PDReactionQuery The current query, for fluid interface
     */
    public function filterByModeratedAt($moderatedAt = null, $comparison = null)
    {
        if (is_array($moderatedAt)) {
            $useMinMax = false;
            if (isset($moderatedAt['min'])) {
                $this->addUsingAlias(PDReactionPeer::MODERATED_AT, $moderatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($moderatedAt['max'])) {
                $this->addUsingAlias(PDReactionPeer::MODERATED_AT, $moderatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PDReactionPeer::MODERATED_AT, $moderatedAt, $comparison);
    }

    /**
     * Filter the query on the indexed_at column
     *
     * Example usage:
     * <code>
     * $query->filterByIndexedAt('2011-03-14'); // WHERE indexed_at = '2011-03-14'
     * $query->filterByIndexedAt('now'); // WHERE indexed_at = '2011-03-14'
     * $query->filterByIndexedAt(array('max' => 'yesterday')); // WHERE indexed_at < '2011-03-13'
     * </code>
     *
     * @param     mixed $indexedAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PDReactionQuery The current query, for fluid interface
     */
    public function filterByIndexedAt($indexedAt = null, $comparison = null)
    {
        if (is_array($indexedAt)) {
            $useMinMax = false;
            if (isset($indexedAt['min'])) {
                $this->addUsingAlias(PDReactionPeer::INDEXED_AT, $indexedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($indexedAt['max'])) {
                $this->addUsingAlias(PDReactionPeer::INDEXED_AT, $indexedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PDReactionPeer::INDEXED_AT, $indexedAt, $comparison);
    }

    /**
     * Filter the query on the created_at column
     *
     * Example usage:
     * <code>
     * $query->filterByCreatedAt('2011-03-14'); // WHERE created_at = '2011-03-14'
     * $query->filterByCreatedAt('now'); // WHERE created_at = '2011-03-14'
     * $query->filterByCreatedAt(array('max' => 'yesterday')); // WHERE created_at < '2011-03-13'
     * </code>
     *
     * @param     mixed $createdAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PDReactionQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(PDReactionPeer::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(PDReactionPeer::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PDReactionPeer::CREATED_AT, $createdAt, $comparison);
    }

    /**
     * Filter the query on the updated_at column
     *
     * Example usage:
     * <code>
     * $query->filterByUpdatedAt('2011-03-14'); // WHERE updated_at = '2011-03-14'
     * $query->filterByUpdatedAt('now'); // WHERE updated_at = '2011-03-14'
     * $query->filterByUpdatedAt(array('max' => 'yesterday')); // WHERE updated_at < '2011-03-13'
     * </code>
     *
     * @param     mixed $updatedAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PDReactionQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(PDReactionPeer::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(PDReactionPeer::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PDReactionPeer::UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query on the slug column
     *
     * Example usage:
     * <code>
     * $query->filterBySlug('fooValue');   // WHERE slug = 'fooValue'
     * $query->filterBySlug('%fooValue%'); // WHERE slug LIKE '%fooValue%'
     * </code>
     *
     * @param     string $slug The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PDReactionQuery The current query, for fluid interface
     */
    public function filterBySlug($slug = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($slug)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $slug)) {
                $slug = str_replace('*', '%', $slug);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(PDReactionPeer::SLUG, $slug, $comparison);
    }

    /**
     * Filter the query on the tree_left column
     *
     * Example usage:
     * <code>
     * $query->filterByTreeLeft(1234); // WHERE tree_left = 1234
     * $query->filterByTreeLeft(array(12, 34)); // WHERE tree_left IN (12, 34)
     * $query->filterByTreeLeft(array('min' => 12)); // WHERE tree_left >= 12
     * $query->filterByTreeLeft(array('max' => 12)); // WHERE tree_left <= 12
     * </code>
     *
     * @param     mixed $treeLeft The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PDReactionQuery The current query, for fluid interface
     */
    public function filterByTreeLeft($treeLeft = null, $comparison = null)
    {
        if (is_array($treeLeft)) {
            $useMinMax = false;
            if (isset($treeLeft['min'])) {
                $this->addUsingAlias(PDReactionPeer::TREE_LEFT, $treeLeft['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($treeLeft['max'])) {
                $this->addUsingAlias(PDReactionPeer::TREE_LEFT, $treeLeft['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PDReactionPeer::TREE_LEFT, $treeLeft, $comparison);
    }

    /**
     * Filter the query on the tree_right column
     *
     * Example usage:
     * <code>
     * $query->filterByTreeRight(1234); // WHERE tree_right = 1234
     * $query->filterByTreeRight(array(12, 34)); // WHERE tree_right IN (12, 34)
     * $query->filterByTreeRight(array('min' => 12)); // WHERE tree_right >= 12
     * $query->filterByTreeRight(array('max' => 12)); // WHERE tree_right <= 12
     * </code>
     *
     * @param     mixed $treeRight The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PDReactionQuery The current query, for fluid interface
     */
    public function filterByTreeRight($treeRight = null, $comparison = null)
    {
        if (is_array($treeRight)) {
            $useMinMax = false;
            if (isset($treeRight['min'])) {
                $this->addUsingAlias(PDReactionPeer::TREE_RIGHT, $treeRight['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($treeRight['max'])) {
                $this->addUsingAlias(PDReactionPeer::TREE_RIGHT, $treeRight['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PDReactionPeer::TREE_RIGHT, $treeRight, $comparison);
    }

    /**
     * Filter the query on the tree_level column
     *
     * Example usage:
     * <code>
     * $query->filterByTreeLevel(1234); // WHERE tree_level = 1234
     * $query->filterByTreeLevel(array(12, 34)); // WHERE tree_level IN (12, 34)
     * $query->filterByTreeLevel(array('min' => 12)); // WHERE tree_level >= 12
     * $query->filterByTreeLevel(array('max' => 12)); // WHERE tree_level <= 12
     * </code>
     *
     * @param     mixed $treeLevel The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PDReactionQuery The current query, for fluid interface
     */
    public function filterByTreeLevel($treeLevel = null, $comparison = null)
    {
        if (is_array($treeLevel)) {
            $useMinMax = false;
            if (isset($treeLevel['min'])) {
                $this->addUsingAlias(PDReactionPeer::TREE_LEVEL, $treeLevel['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($treeLevel['max'])) {
                $this->addUsingAlias(PDReactionPeer::TREE_LEVEL, $treeLevel['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PDReactionPeer::TREE_LEVEL, $treeLevel, $comparison);
    }

    /**
     * Filter the query by a related PUser object
     *
     * @param   PUser|PropelObjectCollection $pUser The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PDReactionQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPUser($pUser, $comparison = null)
    {
        if ($pUser instanceof PUser) {
            return $this
                ->addUsingAlias(PDReactionPeer::P_USER_ID, $pUser->getId(), $comparison);
        } elseif ($pUser instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PDReactionPeer::P_USER_ID, $pUser->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByPUser() only accepts arguments of type PUser or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PUser relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PDReactionQuery The current query, for fluid interface
     */
    public function joinPUser($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PUser');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'PUser');
        }

        return $this;
    }

    /**
     * Use the PUser relation PUser object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Politizr\Model\PUserQuery A secondary query class using the current class as primary query
     */
    public function usePUserQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinPUser($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PUser', '\Politizr\Model\PUserQuery');
    }

    /**
     * Filter the query by a related PDDebate object
     *
     * @param   PDDebate|PropelObjectCollection $pDDebate The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PDReactionQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPDDebate($pDDebate, $comparison = null)
    {
        if ($pDDebate instanceof PDDebate) {
            return $this
                ->addUsingAlias(PDReactionPeer::P_D_DEBATE_ID, $pDDebate->getId(), $comparison);
        } elseif ($pDDebate instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PDReactionPeer::P_D_DEBATE_ID, $pDDebate->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByPDDebate() only accepts arguments of type PDDebate or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PDDebate relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PDReactionQuery The current query, for fluid interface
     */
    public function joinPDDebate($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PDDebate');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'PDDebate');
        }

        return $this;
    }

    /**
     * Use the PDDebate relation PDDebate object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Politizr\Model\PDDebateQuery A secondary query class using the current class as primary query
     */
    public function usePDDebateQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPDDebate($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PDDebate', '\Politizr\Model\PDDebateQuery');
    }

    /**
     * Filter the query by a related PLCity object
     *
     * @param   PLCity|PropelObjectCollection $pLCity The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PDReactionQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPLCity($pLCity, $comparison = null)
    {
        if ($pLCity instanceof PLCity) {
            return $this
                ->addUsingAlias(PDReactionPeer::P_L_CITY_ID, $pLCity->getId(), $comparison);
        } elseif ($pLCity instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PDReactionPeer::P_L_CITY_ID, $pLCity->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByPLCity() only accepts arguments of type PLCity or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PLCity relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PDReactionQuery The current query, for fluid interface
     */
    public function joinPLCity($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PLCity');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'PLCity');
        }

        return $this;
    }

    /**
     * Use the PLCity relation PLCity object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Politizr\Model\PLCityQuery A secondary query class using the current class as primary query
     */
    public function usePLCityQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinPLCity($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PLCity', '\Politizr\Model\PLCityQuery');
    }

    /**
     * Filter the query by a related PLDepartment object
     *
     * @param   PLDepartment|PropelObjectCollection $pLDepartment The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PDReactionQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPLDepartment($pLDepartment, $comparison = null)
    {
        if ($pLDepartment instanceof PLDepartment) {
            return $this
                ->addUsingAlias(PDReactionPeer::P_L_DEPARTMENT_ID, $pLDepartment->getId(), $comparison);
        } elseif ($pLDepartment instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PDReactionPeer::P_L_DEPARTMENT_ID, $pLDepartment->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByPLDepartment() only accepts arguments of type PLDepartment or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PLDepartment relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PDReactionQuery The current query, for fluid interface
     */
    public function joinPLDepartment($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PLDepartment');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'PLDepartment');
        }

        return $this;
    }

    /**
     * Use the PLDepartment relation PLDepartment object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Politizr\Model\PLDepartmentQuery A secondary query class using the current class as primary query
     */
    public function usePLDepartmentQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinPLDepartment($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PLDepartment', '\Politizr\Model\PLDepartmentQuery');
    }

    /**
     * Filter the query by a related PLRegion object
     *
     * @param   PLRegion|PropelObjectCollection $pLRegion The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PDReactionQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPLRegion($pLRegion, $comparison = null)
    {
        if ($pLRegion instanceof PLRegion) {
            return $this
                ->addUsingAlias(PDReactionPeer::P_L_REGION_ID, $pLRegion->getId(), $comparison);
        } elseif ($pLRegion instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PDReactionPeer::P_L_REGION_ID, $pLRegion->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByPLRegion() only accepts arguments of type PLRegion or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PLRegion relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PDReactionQuery The current query, for fluid interface
     */
    public function joinPLRegion($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PLRegion');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'PLRegion');
        }

        return $this;
    }

    /**
     * Use the PLRegion relation PLRegion object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Politizr\Model\PLRegionQuery A secondary query class using the current class as primary query
     */
    public function usePLRegionQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinPLRegion($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PLRegion', '\Politizr\Model\PLRegionQuery');
    }

    /**
     * Filter the query by a related PLCountry object
     *
     * @param   PLCountry|PropelObjectCollection $pLCountry The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PDReactionQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPLCountry($pLCountry, $comparison = null)
    {
        if ($pLCountry instanceof PLCountry) {
            return $this
                ->addUsingAlias(PDReactionPeer::P_L_COUNTRY_ID, $pLCountry->getId(), $comparison);
        } elseif ($pLCountry instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PDReactionPeer::P_L_COUNTRY_ID, $pLCountry->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByPLCountry() only accepts arguments of type PLCountry or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PLCountry relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PDReactionQuery The current query, for fluid interface
     */
    public function joinPLCountry($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PLCountry');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'PLCountry');
        }

        return $this;
    }

    /**
     * Use the PLCountry relation PLCountry object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Politizr\Model\PLCountryQuery A secondary query class using the current class as primary query
     */
    public function usePLCountryQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinPLCountry($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PLCountry', '\Politizr\Model\PLCountryQuery');
    }

    /**
     * Filter the query by a related PCTopic object
     *
     * @param   PCTopic|PropelObjectCollection $pCTopic The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PDReactionQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPCTopic($pCTopic, $comparison = null)
    {
        if ($pCTopic instanceof PCTopic) {
            return $this
                ->addUsingAlias(PDReactionPeer::P_C_TOPIC_ID, $pCTopic->getId(), $comparison);
        } elseif ($pCTopic instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PDReactionPeer::P_C_TOPIC_ID, $pCTopic->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByPCTopic() only accepts arguments of type PCTopic or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PCTopic relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PDReactionQuery The current query, for fluid interface
     */
    public function joinPCTopic($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PCTopic');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'PCTopic');
        }

        return $this;
    }

    /**
     * Use the PCTopic relation PCTopic object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Politizr\Model\PCTopicQuery A secondary query class using the current class as primary query
     */
    public function usePCTopicQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinPCTopic($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PCTopic', '\Politizr\Model\PCTopicQuery');
    }

    /**
     * Filter the query by a related PUBookmarkDR object
     *
     * @param   PUBookmarkDR|PropelObjectCollection $pUBookmarkDR  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PDReactionQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPuBookmarkDrPDReaction($pUBookmarkDR, $comparison = null)
    {
        if ($pUBookmarkDR instanceof PUBookmarkDR) {
            return $this
                ->addUsingAlias(PDReactionPeer::ID, $pUBookmarkDR->getPDReactionId(), $comparison);
        } elseif ($pUBookmarkDR instanceof PropelObjectCollection) {
            return $this
                ->usePuBookmarkDrPDReactionQuery()
                ->filterByPrimaryKeys($pUBookmarkDR->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByPuBookmarkDrPDReaction() only accepts arguments of type PUBookmarkDR or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PuBookmarkDrPDReaction relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PDReactionQuery The current query, for fluid interface
     */
    public function joinPuBookmarkDrPDReaction($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PuBookmarkDrPDReaction');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'PuBookmarkDrPDReaction');
        }

        return $this;
    }

    /**
     * Use the PuBookmarkDrPDReaction relation PUBookmarkDR object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Politizr\Model\PUBookmarkDRQuery A secondary query class using the current class as primary query
     */
    public function usePuBookmarkDrPDReactionQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPuBookmarkDrPDReaction($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PuBookmarkDrPDReaction', '\Politizr\Model\PUBookmarkDRQuery');
    }

    /**
     * Filter the query by a related PUTrackDR object
     *
     * @param   PUTrackDR|PropelObjectCollection $pUTrackDR  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PDReactionQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPuTrackDrPDReaction($pUTrackDR, $comparison = null)
    {
        if ($pUTrackDR instanceof PUTrackDR) {
            return $this
                ->addUsingAlias(PDReactionPeer::ID, $pUTrackDR->getPDReactionId(), $comparison);
        } elseif ($pUTrackDR instanceof PropelObjectCollection) {
            return $this
                ->usePuTrackDrPDReactionQuery()
                ->filterByPrimaryKeys($pUTrackDR->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByPuTrackDrPDReaction() only accepts arguments of type PUTrackDR or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PuTrackDrPDReaction relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PDReactionQuery The current query, for fluid interface
     */
    public function joinPuTrackDrPDReaction($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PuTrackDrPDReaction');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'PuTrackDrPDReaction');
        }

        return $this;
    }

    /**
     * Use the PuTrackDrPDReaction relation PUTrackDR object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Politizr\Model\PUTrackDRQuery A secondary query class using the current class as primary query
     */
    public function usePuTrackDrPDReactionQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPuTrackDrPDReaction($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PuTrackDrPDReaction', '\Politizr\Model\PUTrackDRQuery');
    }

    /**
     * Filter the query by a related PDRComment object
     *
     * @param   PDRComment|PropelObjectCollection $pDRComment  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PDReactionQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPDRComment($pDRComment, $comparison = null)
    {
        if ($pDRComment instanceof PDRComment) {
            return $this
                ->addUsingAlias(PDReactionPeer::ID, $pDRComment->getPDReactionId(), $comparison);
        } elseif ($pDRComment instanceof PropelObjectCollection) {
            return $this
                ->usePDRCommentQuery()
                ->filterByPrimaryKeys($pDRComment->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByPDRComment() only accepts arguments of type PDRComment or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PDRComment relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PDReactionQuery The current query, for fluid interface
     */
    public function joinPDRComment($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PDRComment');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'PDRComment');
        }

        return $this;
    }

    /**
     * Use the PDRComment relation PDRComment object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Politizr\Model\PDRCommentQuery A secondary query class using the current class as primary query
     */
    public function usePDRCommentQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPDRComment($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PDRComment', '\Politizr\Model\PDRCommentQuery');
    }

    /**
     * Filter the query by a related PDRTaggedT object
     *
     * @param   PDRTaggedT|PropelObjectCollection $pDRTaggedT  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PDReactionQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPDRTaggedT($pDRTaggedT, $comparison = null)
    {
        if ($pDRTaggedT instanceof PDRTaggedT) {
            return $this
                ->addUsingAlias(PDReactionPeer::ID, $pDRTaggedT->getPDReactionId(), $comparison);
        } elseif ($pDRTaggedT instanceof PropelObjectCollection) {
            return $this
                ->usePDRTaggedTQuery()
                ->filterByPrimaryKeys($pDRTaggedT->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByPDRTaggedT() only accepts arguments of type PDRTaggedT or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PDRTaggedT relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PDReactionQuery The current query, for fluid interface
     */
    public function joinPDRTaggedT($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PDRTaggedT');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'PDRTaggedT');
        }

        return $this;
    }

    /**
     * Use the PDRTaggedT relation PDRTaggedT object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Politizr\Model\PDRTaggedTQuery A secondary query class using the current class as primary query
     */
    public function usePDRTaggedTQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPDRTaggedT($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PDRTaggedT', '\Politizr\Model\PDRTaggedTQuery');
    }

    /**
     * Filter the query by a related PDMedia object
     *
     * @param   PDMedia|PropelObjectCollection $pDMedia  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PDReactionQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPDMedia($pDMedia, $comparison = null)
    {
        if ($pDMedia instanceof PDMedia) {
            return $this
                ->addUsingAlias(PDReactionPeer::ID, $pDMedia->getPDReactionId(), $comparison);
        } elseif ($pDMedia instanceof PropelObjectCollection) {
            return $this
                ->usePDMediaQuery()
                ->filterByPrimaryKeys($pDMedia->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByPDMedia() only accepts arguments of type PDMedia or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PDMedia relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PDReactionQuery The current query, for fluid interface
     */
    public function joinPDMedia($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PDMedia');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'PDMedia');
        }

        return $this;
    }

    /**
     * Use the PDMedia relation PDMedia object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Politizr\Model\PDMediaQuery A secondary query class using the current class as primary query
     */
    public function usePDMediaQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinPDMedia($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PDMedia', '\Politizr\Model\PDMediaQuery');
    }

    /**
     * Filter the query by a related PMReactionHistoric object
     *
     * @param   PMReactionHistoric|PropelObjectCollection $pMReactionHistoric  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PDReactionQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPMReactionHistoric($pMReactionHistoric, $comparison = null)
    {
        if ($pMReactionHistoric instanceof PMReactionHistoric) {
            return $this
                ->addUsingAlias(PDReactionPeer::ID, $pMReactionHistoric->getPDReactionId(), $comparison);
        } elseif ($pMReactionHistoric instanceof PropelObjectCollection) {
            return $this
                ->usePMReactionHistoricQuery()
                ->filterByPrimaryKeys($pMReactionHistoric->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByPMReactionHistoric() only accepts arguments of type PMReactionHistoric or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PMReactionHistoric relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PDReactionQuery The current query, for fluid interface
     */
    public function joinPMReactionHistoric($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PMReactionHistoric');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'PMReactionHistoric');
        }

        return $this;
    }

    /**
     * Use the PMReactionHistoric relation PMReactionHistoric object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Politizr\Model\PMReactionHistoricQuery A secondary query class using the current class as primary query
     */
    public function usePMReactionHistoricQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinPMReactionHistoric($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PMReactionHistoric', '\Politizr\Model\PMReactionHistoricQuery');
    }

    /**
     * Filter the query by a related PUser object
     * using the p_u_bookmark_d_r table as cross reference
     *
     * @param   PUser $pUser the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   PDReactionQuery The current query, for fluid interface
     */
    public function filterByPuBookmarkDrPUser($pUser, $comparison = Criteria::EQUAL)
    {
        return $this
            ->usePuBookmarkDrPDReactionQuery()
            ->filterByPuBookmarkDrPUser($pUser, $comparison)
            ->endUse();
    }

    /**
     * Filter the query by a related PUser object
     * using the p_u_track_d_r table as cross reference
     *
     * @param   PUser $pUser the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   PDReactionQuery The current query, for fluid interface
     */
    public function filterByPuTrackDrPUser($pUser, $comparison = Criteria::EQUAL)
    {
        return $this
            ->usePuTrackDrPDReactionQuery()
            ->filterByPuTrackDrPUser($pUser, $comparison)
            ->endUse();
    }

    /**
     * Filter the query by a related PTag object
     * using the p_d_r_tagged_t table as cross reference
     *
     * @param   PTag $pTag the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   PDReactionQuery The current query, for fluid interface
     */
    public function filterByPTag($pTag, $comparison = Criteria::EQUAL)
    {
        return $this
            ->usePDRTaggedTQuery()
            ->filterByPTag($pTag, $comparison)
            ->endUse();
    }

    /**
     * Exclude object from result
     *
     * @param   PDReaction $pDReaction Object to remove from the list of results
     *
     * @return PDReactionQuery The current query, for fluid interface
     */
    public function prune($pDReaction = null)
    {
        if ($pDReaction) {
            $this->addUsingAlias(PDReactionPeer::ID, $pDReaction->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Code to execute before every DELETE statement
     *
     * @param     PropelPDO $con The connection object used by the query
     */
    protected function basePreDelete(PropelPDO $con)
    {
        // archivable behavior

        if ($this->archiveOnDelete) {
            $this->archive($con);
        } else {
            $this->archiveOnDelete = true;
        }


        return $this->preDelete($con);
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     PDReactionQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(PDReactionPeer::UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     PDReactionQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(PDReactionPeer::UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     PDReactionQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(PDReactionPeer::UPDATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     PDReactionQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(PDReactionPeer::CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date desc
     *
     * @return     PDReactionQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(PDReactionPeer::CREATED_AT);
    }

    /**
     * Order by create date asc
     *
     * @return     PDReactionQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(PDReactionPeer::CREATED_AT);
    }
    // query_cache behavior

    public function setQueryKey($key)
    {
        $this->queryKey = $key;

        return $this;
    }

    public function getQueryKey()
    {
        return $this->queryKey;
    }

    public function cacheContains($key)
    {

        return apc_fetch($key);
    }

    public function cacheFetch($key)
    {

        return apc_fetch($key);
    }

    public function cacheStore($key, $value, $lifetime = 3600)
    {
        apc_store($key, $value, $lifetime);
    }

    protected function doSelect($con)
    {
        // check that the columns of the main class are already added (if this is the primary ModelCriteria)
        if (!$this->hasSelectClause() && !$this->getPrimaryCriteria()) {
            $this->addSelfSelectColumns();
        }
        $this->configureSelectColumns();

        $dbMap = Propel::getDatabaseMap(PDReactionPeer::DATABASE_NAME);
        $db = Propel::getDB(PDReactionPeer::DATABASE_NAME);

        $key = $this->getQueryKey();
        if ($key && $this->cacheContains($key)) {
            $params = $this->getParams();
            $sql = $this->cacheFetch($key);
        } else {
            $params = array();
            $sql = BasePeer::createSelectSql($this, $params);
            if ($key) {
                $this->cacheStore($key, $sql);
            }
        }

        try {
            $stmt = $con->prepare($sql);
            $db->bindValues($stmt, $params, $dbMap);
            $stmt->execute();
            } catch (Exception $e) {
                Propel::log($e->getMessage(), Propel::LOG_ERR);
                throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), $e);
            }

        return $stmt;
    }

    protected function doCount($con)
    {
        $dbMap = Propel::getDatabaseMap($this->getDbName());
        $db = Propel::getDB($this->getDbName());

        $key = $this->getQueryKey();
        if ($key && $this->cacheContains($key)) {
            $params = $this->getParams();
            $sql = $this->cacheFetch($key);
        } else {
            // check that the columns of the main class are already added (if this is the primary ModelCriteria)
            if (!$this->hasSelectClause() && !$this->getPrimaryCriteria()) {
                $this->addSelfSelectColumns();
            }

            $this->configureSelectColumns();

            $needsComplexCount = $this->getGroupByColumns()
                || $this->getOffset()
                || $this->getLimit()
                || $this->getHaving()
                || in_array(Criteria::DISTINCT, $this->getSelectModifiers());

            $params = array();
            if ($needsComplexCount) {
                if (BasePeer::needsSelectAliases($this)) {
                    if ($this->getHaving()) {
                        throw new PropelException('Propel cannot create a COUNT query when using HAVING and  duplicate column names in the SELECT part');
                    }
                    $db->turnSelectColumnsToAliases($this);
                }
                $selectSql = BasePeer::createSelectSql($this, $params);
                $sql = 'SELECT COUNT(*) FROM (' . $selectSql . ') propelmatch4cnt';
            } else {
                // Replace SELECT columns with COUNT(*)
                $this->clearSelectColumns()->addSelectColumn('COUNT(*)');
                $sql = BasePeer::createSelectSql($this, $params);
            }

            if ($key) {
                $this->cacheStore($key, $sql);
            }
        }

        try {
            $stmt = $con->prepare($sql);
            $db->bindValues($stmt, $params, $dbMap);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute COUNT statement [%s]', $sql), $e);
        }

        return $stmt;
    }

    // nested_set behavior

    /**
     * Filter the query to restrict the result to root objects
     *
     * @return    PDReactionQuery The current query, for fluid interface
     */
    public function treeRoots()
    {
        return $this->addUsingAlias(PDReactionPeer::LEFT_COL, 1, Criteria::EQUAL);
    }

    /**
     * Returns the objects in a certain tree, from the tree scope
     *
     * @param     int $scope		Scope to determine which objects node to return
     *
     * @return    PDReactionQuery The current query, for fluid interface
     */
    public function inTree($scope = null)
    {
        return $this->addUsingAlias(PDReactionPeer::SCOPE_COL, $scope, Criteria::EQUAL);
    }

    /**
     * Filter the query to restrict the result to descendants of an object
     *
     * @param     PDReaction $pDReaction The object to use for descendant search
     *
     * @return    PDReactionQuery The current query, for fluid interface
     */
    public function descendantsOf($pDReaction)
    {
        return $this
            ->inTree($pDReaction->getScopeValue())
            ->addUsingAlias(PDReactionPeer::LEFT_COL, $pDReaction->getLeftValue(), Criteria::GREATER_THAN)
            ->addUsingAlias(PDReactionPeer::LEFT_COL, $pDReaction->getRightValue(), Criteria::LESS_THAN);
    }

    /**
     * Filter the query to restrict the result to the branch of an object.
     * Same as descendantsOf(), except that it includes the object passed as parameter in the result
     *
     * @param     PDReaction $pDReaction The object to use for branch search
     *
     * @return    PDReactionQuery The current query, for fluid interface
     */
    public function branchOf($pDReaction)
    {
        return $this
            ->inTree($pDReaction->getScopeValue())
            ->addUsingAlias(PDReactionPeer::LEFT_COL, $pDReaction->getLeftValue(), Criteria::GREATER_EQUAL)
            ->addUsingAlias(PDReactionPeer::LEFT_COL, $pDReaction->getRightValue(), Criteria::LESS_EQUAL);
    }

    /**
     * Filter the query to restrict the result to children of an object
     *
     * @param     PDReaction $pDReaction The object to use for child search
     *
     * @return    PDReactionQuery The current query, for fluid interface
     */
    public function childrenOf($pDReaction)
    {
        return $this
            ->descendantsOf($pDReaction)
            ->addUsingAlias(PDReactionPeer::LEVEL_COL, $pDReaction->getLevel() + 1, Criteria::EQUAL);
    }

    /**
     * Filter the query to restrict the result to siblings of an object.
     * The result does not include the object passed as parameter.
     *
     * @param     PDReaction $pDReaction The object to use for sibling search
     * @param      PropelPDO $con Connection to use.
     *
     * @return    PDReactionQuery The current query, for fluid interface
     */
    public function siblingsOf($pDReaction, PropelPDO $con = null)
    {
        if ($pDReaction->isRoot()) {
            return $this->
                add(PDReactionPeer::LEVEL_COL, '1<>1', Criteria::CUSTOM);
        } else {
            return $this
                ->childrenOf($pDReaction->getParent($con))
                ->prune($pDReaction);
        }
    }

    /**
     * Filter the query to restrict the result to ancestors of an object
     *
     * @param     PDReaction $pDReaction The object to use for ancestors search
     *
     * @return    PDReactionQuery The current query, for fluid interface
     */
    public function ancestorsOf($pDReaction)
    {
        return $this
            ->inTree($pDReaction->getScopeValue())
            ->addUsingAlias(PDReactionPeer::LEFT_COL, $pDReaction->getLeftValue(), Criteria::LESS_THAN)
            ->addUsingAlias(PDReactionPeer::RIGHT_COL, $pDReaction->getRightValue(), Criteria::GREATER_THAN);
    }

    /**
     * Filter the query to restrict the result to roots of an object.
     * Same as ancestorsOf(), except that it includes the object passed as parameter in the result
     *
     * @param     PDReaction $pDReaction The object to use for roots search
     *
     * @return    PDReactionQuery The current query, for fluid interface
     */
    public function rootsOf($pDReaction)
    {
        return $this
            ->inTree($pDReaction->getScopeValue())
            ->addUsingAlias(PDReactionPeer::LEFT_COL, $pDReaction->getLeftValue(), Criteria::LESS_EQUAL)
            ->addUsingAlias(PDReactionPeer::RIGHT_COL, $pDReaction->getRightValue(), Criteria::GREATER_EQUAL);
    }

    /**
     * Order the result by branch, i.e. natural tree order
     *
     * @param     bool $reverse if true, reverses the order
     *
     * @return    PDReactionQuery The current query, for fluid interface
     */
    public function orderByBranch($reverse = false)
    {
        if ($reverse) {
            return $this
                ->addDescendingOrderByColumn(PDReactionPeer::LEFT_COL);
        } else {
            return $this
                ->addAscendingOrderByColumn(PDReactionPeer::LEFT_COL);
        }
    }

    /**
     * Order the result by level, the closer to the root first
     *
     * @param     bool $reverse if true, reverses the order
     *
     * @return    PDReactionQuery The current query, for fluid interface
     */
    public function orderByLevel($reverse = false)
    {
        if ($reverse) {
            return $this
                ->addAscendingOrderByColumn(PDReactionPeer::RIGHT_COL);
        } else {
            return $this
                ->addDescendingOrderByColumn(PDReactionPeer::RIGHT_COL);
        }
    }

    /**
     * Returns a root node for the tree
     *
     * @param      int $scope		Scope to determine which root node to return
     * @param      PropelPDO $con	Connection to use.
     *
     * @return     PDReaction The tree root object
     */
    public function findRoot($scope = null, $con = null)
    {
        return $this
            ->addUsingAlias(PDReactionPeer::LEFT_COL, 1, Criteria::EQUAL)
            ->inTree($scope)
            ->findOne($con);
    }

    /**
     * Returns the root objects for all trees.
     *
     * @param      PropelPDO $con	Connection to use.
     *
     * @return    mixed the list of results, formatted by the current formatter
     */
    public function findRoots($con = null)
    {
        return $this
            ->treeRoots()
            ->find($con);
    }

    /**
     * Returns a tree of objects
     *
     * @param      int $scope		Scope to determine which tree node to return
     * @param      PropelPDO $con	Connection to use.
     *
     * @return     mixed the list of results, formatted by the current formatter
     */
    public function findTree($scope = null, $con = null)
    {
        return $this
            ->inTree($scope)
            ->orderByBranch()
            ->find($con);
    }

    // archivable behavior

    /**
     * Copy the data of the objects satisfying the query into PDReactionArchive archive objects.
     * The archived objects are then saved.
     * If any of the objects has already been archived, the archived object
     * is updated and not duplicated.
     * Warning: This termination methods issues 2n+1 queries.
     *
     * @param      PropelPDO $con	Connection to use.
     * @param      Boolean $useLittleMemory	Whether or not to use PropelOnDemandFormatter to retrieve objects.
     *               Set to false if the identity map matters.
     *               Set to true (default) to use less memory.
     *
     * @return     int the number of archived objects
     * @throws     PropelException
     */
    public function archive($con = null, $useLittleMemory = true)
    {
        $totalArchivedObjects = 0;
        $criteria = clone $this;
        // prepare the query
        $criteria->setWith(array());
        if ($useLittleMemory) {
            $criteria->setFormatter(ModelCriteria::FORMAT_ON_DEMAND);
        }
        if ($con === null) {
            $con = Propel::getConnection(PDReactionPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }
        $con->beginTransaction();
        try {
            // archive all results one by one
            foreach ($criteria->find($con) as $object) {
                $object->archive($con);
                $totalArchivedObjects++;
            }
            $con->commit();
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }

        return $totalArchivedObjects;
    }

    /**
     * Enable/disable auto-archiving on delete for the next query.
     *
     * @param boolean $archiveOnDelete True if the query must archive deleted objects, false otherwise.
     */
    public function setArchiveOnDelete($archiveOnDelete)
    {
        $this->archiveOnDelete = $archiveOnDelete;
    }

    /**
     * Delete records matching the current query without archiving them.
     *
     * @param      PropelPDO $con	Connection to use.
     *
     * @return integer the number of deleted rows
     */
    public function deleteWithoutArchive($con = null)
    {
        $this->archiveOnDelete = false;

        return $this->delete($con);
    }

    /**
     * Delete all records without archiving them.
     *
     * @param      PropelPDO $con	Connection to use.
     *
     * @return integer the number of deleted rows
     */
    public function deleteAllWithoutArchive($con = null)
    {
        $this->archiveOnDelete = false;

        return $this->deleteAll($con);
    }

}
