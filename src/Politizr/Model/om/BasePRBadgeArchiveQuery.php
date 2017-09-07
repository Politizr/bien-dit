<?php

namespace Politizr\Model\om;

use \Criteria;
use \Exception;
use \ModelCriteria;
use \PDO;
use \Propel;
use \PropelException;
use \PropelObjectCollection;
use \PropelPDO;
use Politizr\Model\PRBadgeArchive;
use Politizr\Model\PRBadgeArchivePeer;
use Politizr\Model\PRBadgeArchiveQuery;

/**
 * @method PRBadgeArchiveQuery orderById($order = Criteria::ASC) Order by the id column
 * @method PRBadgeArchiveQuery orderByUuid($order = Criteria::ASC) Order by the uuid column
 * @method PRBadgeArchiveQuery orderByPRMetalTypeId($order = Criteria::ASC) Order by the p_r_metal_type_id column
 * @method PRBadgeArchiveQuery orderByPRBadgeFamilyId($order = Criteria::ASC) Order by the p_r_badge_family_id column
 * @method PRBadgeArchiveQuery orderByTitle($order = Criteria::ASC) Order by the title column
 * @method PRBadgeArchiveQuery orderByFileName($order = Criteria::ASC) Order by the file_name column
 * @method PRBadgeArchiveQuery orderByOnline($order = Criteria::ASC) Order by the online column
 * @method PRBadgeArchiveQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method PRBadgeArchiveQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 * @method PRBadgeArchiveQuery orderBySlug($order = Criteria::ASC) Order by the slug column
 * @method PRBadgeArchiveQuery orderBySortableRank($order = Criteria::ASC) Order by the sortable_rank column
 * @method PRBadgeArchiveQuery orderByArchivedAt($order = Criteria::ASC) Order by the archived_at column
 *
 * @method PRBadgeArchiveQuery groupById() Group by the id column
 * @method PRBadgeArchiveQuery groupByUuid() Group by the uuid column
 * @method PRBadgeArchiveQuery groupByPRMetalTypeId() Group by the p_r_metal_type_id column
 * @method PRBadgeArchiveQuery groupByPRBadgeFamilyId() Group by the p_r_badge_family_id column
 * @method PRBadgeArchiveQuery groupByTitle() Group by the title column
 * @method PRBadgeArchiveQuery groupByFileName() Group by the file_name column
 * @method PRBadgeArchiveQuery groupByOnline() Group by the online column
 * @method PRBadgeArchiveQuery groupByCreatedAt() Group by the created_at column
 * @method PRBadgeArchiveQuery groupByUpdatedAt() Group by the updated_at column
 * @method PRBadgeArchiveQuery groupBySlug() Group by the slug column
 * @method PRBadgeArchiveQuery groupBySortableRank() Group by the sortable_rank column
 * @method PRBadgeArchiveQuery groupByArchivedAt() Group by the archived_at column
 *
 * @method PRBadgeArchiveQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method PRBadgeArchiveQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method PRBadgeArchiveQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method PRBadgeArchive findOne(PropelPDO $con = null) Return the first PRBadgeArchive matching the query
 * @method PRBadgeArchive findOneOrCreate(PropelPDO $con = null) Return the first PRBadgeArchive matching the query, or a new PRBadgeArchive object populated from the query conditions when no match is found
 *
 * @method PRBadgeArchive findOneByUuid(string $uuid) Return the first PRBadgeArchive filtered by the uuid column
 * @method PRBadgeArchive findOneByPRMetalTypeId(int $p_r_metal_type_id) Return the first PRBadgeArchive filtered by the p_r_metal_type_id column
 * @method PRBadgeArchive findOneByPRBadgeFamilyId(int $p_r_badge_family_id) Return the first PRBadgeArchive filtered by the p_r_badge_family_id column
 * @method PRBadgeArchive findOneByTitle(string $title) Return the first PRBadgeArchive filtered by the title column
 * @method PRBadgeArchive findOneByFileName(string $file_name) Return the first PRBadgeArchive filtered by the file_name column
 * @method PRBadgeArchive findOneByOnline(boolean $online) Return the first PRBadgeArchive filtered by the online column
 * @method PRBadgeArchive findOneByCreatedAt(string $created_at) Return the first PRBadgeArchive filtered by the created_at column
 * @method PRBadgeArchive findOneByUpdatedAt(string $updated_at) Return the first PRBadgeArchive filtered by the updated_at column
 * @method PRBadgeArchive findOneBySlug(string $slug) Return the first PRBadgeArchive filtered by the slug column
 * @method PRBadgeArchive findOneBySortableRank(int $sortable_rank) Return the first PRBadgeArchive filtered by the sortable_rank column
 * @method PRBadgeArchive findOneByArchivedAt(string $archived_at) Return the first PRBadgeArchive filtered by the archived_at column
 *
 * @method array findById(int $id) Return PRBadgeArchive objects filtered by the id column
 * @method array findByUuid(string $uuid) Return PRBadgeArchive objects filtered by the uuid column
 * @method array findByPRMetalTypeId(int $p_r_metal_type_id) Return PRBadgeArchive objects filtered by the p_r_metal_type_id column
 * @method array findByPRBadgeFamilyId(int $p_r_badge_family_id) Return PRBadgeArchive objects filtered by the p_r_badge_family_id column
 * @method array findByTitle(string $title) Return PRBadgeArchive objects filtered by the title column
 * @method array findByFileName(string $file_name) Return PRBadgeArchive objects filtered by the file_name column
 * @method array findByOnline(boolean $online) Return PRBadgeArchive objects filtered by the online column
 * @method array findByCreatedAt(string $created_at) Return PRBadgeArchive objects filtered by the created_at column
 * @method array findByUpdatedAt(string $updated_at) Return PRBadgeArchive objects filtered by the updated_at column
 * @method array findBySlug(string $slug) Return PRBadgeArchive objects filtered by the slug column
 * @method array findBySortableRank(int $sortable_rank) Return PRBadgeArchive objects filtered by the sortable_rank column
 * @method array findByArchivedAt(string $archived_at) Return PRBadgeArchive objects filtered by the archived_at column
 */
abstract class BasePRBadgeArchiveQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BasePRBadgeArchiveQuery object.
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
            $modelName = 'Politizr\\Model\\PRBadgeArchive';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new PRBadgeArchiveQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   PRBadgeArchiveQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return PRBadgeArchiveQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof PRBadgeArchiveQuery) {
            return $criteria;
        }
        $query = new PRBadgeArchiveQuery(null, null, $modelAlias);

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
     * @return   PRBadgeArchive|PRBadgeArchive[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = PRBadgeArchivePeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(PRBadgeArchivePeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 PRBadgeArchive A model object, or null if the key is not found
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
     * @return                 PRBadgeArchive A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `uuid`, `p_r_metal_type_id`, `p_r_badge_family_id`, `title`, `file_name`, `online`, `created_at`, `updated_at`, `slug`, `sortable_rank`, `archived_at` FROM `p_r_badge_archive` WHERE `id` = :p0';
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
            $obj = new PRBadgeArchive();
            $obj->hydrate($row);
            PRBadgeArchivePeer::addInstanceToPool($obj, (string) $key);
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
     * @return PRBadgeArchive|PRBadgeArchive[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|PRBadgeArchive[]|mixed the list of results, formatted by the current formatter
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
     * @return PRBadgeArchiveQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(PRBadgeArchivePeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return PRBadgeArchiveQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(PRBadgeArchivePeer::ID, $keys, Criteria::IN);
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
     * @return PRBadgeArchiveQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(PRBadgeArchivePeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(PRBadgeArchivePeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PRBadgeArchivePeer::ID, $id, $comparison);
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
     * @return PRBadgeArchiveQuery The current query, for fluid interface
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

        return $this->addUsingAlias(PRBadgeArchivePeer::UUID, $uuid, $comparison);
    }

    /**
     * Filter the query on the p_r_metal_type_id column
     *
     * Example usage:
     * <code>
     * $query->filterByPRMetalTypeId(1234); // WHERE p_r_metal_type_id = 1234
     * $query->filterByPRMetalTypeId(array(12, 34)); // WHERE p_r_metal_type_id IN (12, 34)
     * $query->filterByPRMetalTypeId(array('min' => 12)); // WHERE p_r_metal_type_id >= 12
     * $query->filterByPRMetalTypeId(array('max' => 12)); // WHERE p_r_metal_type_id <= 12
     * </code>
     *
     * @param     mixed $pRMetalTypeId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PRBadgeArchiveQuery The current query, for fluid interface
     */
    public function filterByPRMetalTypeId($pRMetalTypeId = null, $comparison = null)
    {
        if (is_array($pRMetalTypeId)) {
            $useMinMax = false;
            if (isset($pRMetalTypeId['min'])) {
                $this->addUsingAlias(PRBadgeArchivePeer::P_R_METAL_TYPE_ID, $pRMetalTypeId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($pRMetalTypeId['max'])) {
                $this->addUsingAlias(PRBadgeArchivePeer::P_R_METAL_TYPE_ID, $pRMetalTypeId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PRBadgeArchivePeer::P_R_METAL_TYPE_ID, $pRMetalTypeId, $comparison);
    }

    /**
     * Filter the query on the p_r_badge_family_id column
     *
     * Example usage:
     * <code>
     * $query->filterByPRBadgeFamilyId(1234); // WHERE p_r_badge_family_id = 1234
     * $query->filterByPRBadgeFamilyId(array(12, 34)); // WHERE p_r_badge_family_id IN (12, 34)
     * $query->filterByPRBadgeFamilyId(array('min' => 12)); // WHERE p_r_badge_family_id >= 12
     * $query->filterByPRBadgeFamilyId(array('max' => 12)); // WHERE p_r_badge_family_id <= 12
     * </code>
     *
     * @param     mixed $pRBadgeFamilyId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PRBadgeArchiveQuery The current query, for fluid interface
     */
    public function filterByPRBadgeFamilyId($pRBadgeFamilyId = null, $comparison = null)
    {
        if (is_array($pRBadgeFamilyId)) {
            $useMinMax = false;
            if (isset($pRBadgeFamilyId['min'])) {
                $this->addUsingAlias(PRBadgeArchivePeer::P_R_BADGE_FAMILY_ID, $pRBadgeFamilyId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($pRBadgeFamilyId['max'])) {
                $this->addUsingAlias(PRBadgeArchivePeer::P_R_BADGE_FAMILY_ID, $pRBadgeFamilyId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PRBadgeArchivePeer::P_R_BADGE_FAMILY_ID, $pRBadgeFamilyId, $comparison);
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
     * @return PRBadgeArchiveQuery The current query, for fluid interface
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

        return $this->addUsingAlias(PRBadgeArchivePeer::TITLE, $title, $comparison);
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
     * @return PRBadgeArchiveQuery The current query, for fluid interface
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

        return $this->addUsingAlias(PRBadgeArchivePeer::FILE_NAME, $fileName, $comparison);
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
     * @return PRBadgeArchiveQuery The current query, for fluid interface
     */
    public function filterByOnline($online = null, $comparison = null)
    {
        if (is_string($online)) {
            $online = in_array(strtolower($online), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(PRBadgeArchivePeer::ONLINE, $online, $comparison);
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
     * @return PRBadgeArchiveQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(PRBadgeArchivePeer::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(PRBadgeArchivePeer::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PRBadgeArchivePeer::CREATED_AT, $createdAt, $comparison);
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
     * @return PRBadgeArchiveQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(PRBadgeArchivePeer::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(PRBadgeArchivePeer::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PRBadgeArchivePeer::UPDATED_AT, $updatedAt, $comparison);
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
     * @return PRBadgeArchiveQuery The current query, for fluid interface
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

        return $this->addUsingAlias(PRBadgeArchivePeer::SLUG, $slug, $comparison);
    }

    /**
     * Filter the query on the sortable_rank column
     *
     * Example usage:
     * <code>
     * $query->filterBySortableRank(1234); // WHERE sortable_rank = 1234
     * $query->filterBySortableRank(array(12, 34)); // WHERE sortable_rank IN (12, 34)
     * $query->filterBySortableRank(array('min' => 12)); // WHERE sortable_rank >= 12
     * $query->filterBySortableRank(array('max' => 12)); // WHERE sortable_rank <= 12
     * </code>
     *
     * @param     mixed $sortableRank The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PRBadgeArchiveQuery The current query, for fluid interface
     */
    public function filterBySortableRank($sortableRank = null, $comparison = null)
    {
        if (is_array($sortableRank)) {
            $useMinMax = false;
            if (isset($sortableRank['min'])) {
                $this->addUsingAlias(PRBadgeArchivePeer::SORTABLE_RANK, $sortableRank['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($sortableRank['max'])) {
                $this->addUsingAlias(PRBadgeArchivePeer::SORTABLE_RANK, $sortableRank['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PRBadgeArchivePeer::SORTABLE_RANK, $sortableRank, $comparison);
    }

    /**
     * Filter the query on the archived_at column
     *
     * Example usage:
     * <code>
     * $query->filterByArchivedAt('2011-03-14'); // WHERE archived_at = '2011-03-14'
     * $query->filterByArchivedAt('now'); // WHERE archived_at = '2011-03-14'
     * $query->filterByArchivedAt(array('max' => 'yesterday')); // WHERE archived_at < '2011-03-13'
     * </code>
     *
     * @param     mixed $archivedAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PRBadgeArchiveQuery The current query, for fluid interface
     */
    public function filterByArchivedAt($archivedAt = null, $comparison = null)
    {
        if (is_array($archivedAt)) {
            $useMinMax = false;
            if (isset($archivedAt['min'])) {
                $this->addUsingAlias(PRBadgeArchivePeer::ARCHIVED_AT, $archivedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($archivedAt['max'])) {
                $this->addUsingAlias(PRBadgeArchivePeer::ARCHIVED_AT, $archivedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PRBadgeArchivePeer::ARCHIVED_AT, $archivedAt, $comparison);
    }

    /**
     * Exclude object from result
     *
     * @param   PRBadgeArchive $pRBadgeArchive Object to remove from the list of results
     *
     * @return PRBadgeArchiveQuery The current query, for fluid interface
     */
    public function prune($pRBadgeArchive = null)
    {
        if ($pRBadgeArchive) {
            $this->addUsingAlias(PRBadgeArchivePeer::ID, $pRBadgeArchive->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

}
