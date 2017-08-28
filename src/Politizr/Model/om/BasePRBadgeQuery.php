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
use Politizr\Model\PRBadge;
use Politizr\Model\PRBadgeFamily;
use Politizr\Model\PRBadgePeer;
use Politizr\Model\PRBadgeQuery;
use Politizr\Model\PRMetalType;
use Politizr\Model\PUBadge;
use Politizr\Model\PUser;

/**
 * @method PRBadgeQuery orderById($order = Criteria::ASC) Order by the id column
 * @method PRBadgeQuery orderByUuid($order = Criteria::ASC) Order by the uuid column
 * @method PRBadgeQuery orderByPRMetalTypeId($order = Criteria::ASC) Order by the p_r_metal_type_id column
 * @method PRBadgeQuery orderByPRBadgeFamilyId($order = Criteria::ASC) Order by the p_r_badge_family_id column
 * @method PRBadgeQuery orderByTitle($order = Criteria::ASC) Order by the title column
 * @method PRBadgeQuery orderByFileName($order = Criteria::ASC) Order by the file_name column
 * @method PRBadgeQuery orderByOnline($order = Criteria::ASC) Order by the online column
 * @method PRBadgeQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method PRBadgeQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 * @method PRBadgeQuery orderBySlug($order = Criteria::ASC) Order by the slug column
 * @method PRBadgeQuery orderBySortableRank($order = Criteria::ASC) Order by the sortable_rank column
 *
 * @method PRBadgeQuery groupById() Group by the id column
 * @method PRBadgeQuery groupByUuid() Group by the uuid column
 * @method PRBadgeQuery groupByPRMetalTypeId() Group by the p_r_metal_type_id column
 * @method PRBadgeQuery groupByPRBadgeFamilyId() Group by the p_r_badge_family_id column
 * @method PRBadgeQuery groupByTitle() Group by the title column
 * @method PRBadgeQuery groupByFileName() Group by the file_name column
 * @method PRBadgeQuery groupByOnline() Group by the online column
 * @method PRBadgeQuery groupByCreatedAt() Group by the created_at column
 * @method PRBadgeQuery groupByUpdatedAt() Group by the updated_at column
 * @method PRBadgeQuery groupBySlug() Group by the slug column
 * @method PRBadgeQuery groupBySortableRank() Group by the sortable_rank column
 *
 * @method PRBadgeQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method PRBadgeQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method PRBadgeQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method PRBadgeQuery leftJoinPRMetalType($relationAlias = null) Adds a LEFT JOIN clause to the query using the PRMetalType relation
 * @method PRBadgeQuery rightJoinPRMetalType($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PRMetalType relation
 * @method PRBadgeQuery innerJoinPRMetalType($relationAlias = null) Adds a INNER JOIN clause to the query using the PRMetalType relation
 *
 * @method PRBadgeQuery leftJoinPRBadgeFamily($relationAlias = null) Adds a LEFT JOIN clause to the query using the PRBadgeFamily relation
 * @method PRBadgeQuery rightJoinPRBadgeFamily($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PRBadgeFamily relation
 * @method PRBadgeQuery innerJoinPRBadgeFamily($relationAlias = null) Adds a INNER JOIN clause to the query using the PRBadgeFamily relation
 *
 * @method PRBadgeQuery leftJoinPUBadge($relationAlias = null) Adds a LEFT JOIN clause to the query using the PUBadge relation
 * @method PRBadgeQuery rightJoinPUBadge($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PUBadge relation
 * @method PRBadgeQuery innerJoinPUBadge($relationAlias = null) Adds a INNER JOIN clause to the query using the PUBadge relation
 *
 * @method PRBadge findOne(PropelPDO $con = null) Return the first PRBadge matching the query
 * @method PRBadge findOneOrCreate(PropelPDO $con = null) Return the first PRBadge matching the query, or a new PRBadge object populated from the query conditions when no match is found
 *
 * @method PRBadge findOneByUuid(string $uuid) Return the first PRBadge filtered by the uuid column
 * @method PRBadge findOneByPRMetalTypeId(int $p_r_metal_type_id) Return the first PRBadge filtered by the p_r_metal_type_id column
 * @method PRBadge findOneByPRBadgeFamilyId(int $p_r_badge_family_id) Return the first PRBadge filtered by the p_r_badge_family_id column
 * @method PRBadge findOneByTitle(string $title) Return the first PRBadge filtered by the title column
 * @method PRBadge findOneByFileName(string $file_name) Return the first PRBadge filtered by the file_name column
 * @method PRBadge findOneByOnline(boolean $online) Return the first PRBadge filtered by the online column
 * @method PRBadge findOneByCreatedAt(string $created_at) Return the first PRBadge filtered by the created_at column
 * @method PRBadge findOneByUpdatedAt(string $updated_at) Return the first PRBadge filtered by the updated_at column
 * @method PRBadge findOneBySlug(string $slug) Return the first PRBadge filtered by the slug column
 * @method PRBadge findOneBySortableRank(int $sortable_rank) Return the first PRBadge filtered by the sortable_rank column
 *
 * @method array findById(int $id) Return PRBadge objects filtered by the id column
 * @method array findByUuid(string $uuid) Return PRBadge objects filtered by the uuid column
 * @method array findByPRMetalTypeId(int $p_r_metal_type_id) Return PRBadge objects filtered by the p_r_metal_type_id column
 * @method array findByPRBadgeFamilyId(int $p_r_badge_family_id) Return PRBadge objects filtered by the p_r_badge_family_id column
 * @method array findByTitle(string $title) Return PRBadge objects filtered by the title column
 * @method array findByFileName(string $file_name) Return PRBadge objects filtered by the file_name column
 * @method array findByOnline(boolean $online) Return PRBadge objects filtered by the online column
 * @method array findByCreatedAt(string $created_at) Return PRBadge objects filtered by the created_at column
 * @method array findByUpdatedAt(string $updated_at) Return PRBadge objects filtered by the updated_at column
 * @method array findBySlug(string $slug) Return PRBadge objects filtered by the slug column
 * @method array findBySortableRank(int $sortable_rank) Return PRBadge objects filtered by the sortable_rank column
 */
abstract class BasePRBadgeQuery extends ModelCriteria
{
    // query_cache behavior
    protected $queryKey = '';

    // archivable behavior
    protected $archiveOnDelete = true;

    /**
     * Initializes internal state of BasePRBadgeQuery object.
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
            $modelName = 'Politizr\\Model\\PRBadge';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new PRBadgeQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   PRBadgeQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return PRBadgeQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof PRBadgeQuery) {
            return $criteria;
        }
        $query = new PRBadgeQuery(null, null, $modelAlias);

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
     * @return   PRBadge|PRBadge[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = PRBadgePeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(PRBadgePeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 PRBadge A model object, or null if the key is not found
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
     * @return                 PRBadge A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `uuid`, `p_r_metal_type_id`, `p_r_badge_family_id`, `title`, `file_name`, `online`, `created_at`, `updated_at`, `slug`, `sortable_rank` FROM `p_r_badge` WHERE `id` = :p0';
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
            $obj = new PRBadge();
            $obj->hydrate($row);
            PRBadgePeer::addInstanceToPool($obj, (string) $key);
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
     * @return PRBadge|PRBadge[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|PRBadge[]|mixed the list of results, formatted by the current formatter
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
     * @return PRBadgeQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(PRBadgePeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return PRBadgeQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(PRBadgePeer::ID, $keys, Criteria::IN);
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
     * @return PRBadgeQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(PRBadgePeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(PRBadgePeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PRBadgePeer::ID, $id, $comparison);
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
     * @return PRBadgeQuery The current query, for fluid interface
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

        return $this->addUsingAlias(PRBadgePeer::UUID, $uuid, $comparison);
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
     * @see       filterByPRMetalType()
     *
     * @param     mixed $pRMetalTypeId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PRBadgeQuery The current query, for fluid interface
     */
    public function filterByPRMetalTypeId($pRMetalTypeId = null, $comparison = null)
    {
        if (is_array($pRMetalTypeId)) {
            $useMinMax = false;
            if (isset($pRMetalTypeId['min'])) {
                $this->addUsingAlias(PRBadgePeer::P_R_METAL_TYPE_ID, $pRMetalTypeId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($pRMetalTypeId['max'])) {
                $this->addUsingAlias(PRBadgePeer::P_R_METAL_TYPE_ID, $pRMetalTypeId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PRBadgePeer::P_R_METAL_TYPE_ID, $pRMetalTypeId, $comparison);
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
     * @see       filterByPRBadgeFamily()
     *
     * @param     mixed $pRBadgeFamilyId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PRBadgeQuery The current query, for fluid interface
     */
    public function filterByPRBadgeFamilyId($pRBadgeFamilyId = null, $comparison = null)
    {
        if (is_array($pRBadgeFamilyId)) {
            $useMinMax = false;
            if (isset($pRBadgeFamilyId['min'])) {
                $this->addUsingAlias(PRBadgePeer::P_R_BADGE_FAMILY_ID, $pRBadgeFamilyId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($pRBadgeFamilyId['max'])) {
                $this->addUsingAlias(PRBadgePeer::P_R_BADGE_FAMILY_ID, $pRBadgeFamilyId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PRBadgePeer::P_R_BADGE_FAMILY_ID, $pRBadgeFamilyId, $comparison);
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
     * @return PRBadgeQuery The current query, for fluid interface
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

        return $this->addUsingAlias(PRBadgePeer::TITLE, $title, $comparison);
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
     * @return PRBadgeQuery The current query, for fluid interface
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

        return $this->addUsingAlias(PRBadgePeer::FILE_NAME, $fileName, $comparison);
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
     * @return PRBadgeQuery The current query, for fluid interface
     */
    public function filterByOnline($online = null, $comparison = null)
    {
        if (is_string($online)) {
            $online = in_array(strtolower($online), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(PRBadgePeer::ONLINE, $online, $comparison);
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
     * @return PRBadgeQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(PRBadgePeer::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(PRBadgePeer::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PRBadgePeer::CREATED_AT, $createdAt, $comparison);
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
     * @return PRBadgeQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(PRBadgePeer::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(PRBadgePeer::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PRBadgePeer::UPDATED_AT, $updatedAt, $comparison);
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
     * @return PRBadgeQuery The current query, for fluid interface
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

        return $this->addUsingAlias(PRBadgePeer::SLUG, $slug, $comparison);
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
     * @return PRBadgeQuery The current query, for fluid interface
     */
    public function filterBySortableRank($sortableRank = null, $comparison = null)
    {
        if (is_array($sortableRank)) {
            $useMinMax = false;
            if (isset($sortableRank['min'])) {
                $this->addUsingAlias(PRBadgePeer::SORTABLE_RANK, $sortableRank['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($sortableRank['max'])) {
                $this->addUsingAlias(PRBadgePeer::SORTABLE_RANK, $sortableRank['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PRBadgePeer::SORTABLE_RANK, $sortableRank, $comparison);
    }

    /**
     * Filter the query by a related PRMetalType object
     *
     * @param   PRMetalType|PropelObjectCollection $pRMetalType The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PRBadgeQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPRMetalType($pRMetalType, $comparison = null)
    {
        if ($pRMetalType instanceof PRMetalType) {
            return $this
                ->addUsingAlias(PRBadgePeer::P_R_METAL_TYPE_ID, $pRMetalType->getId(), $comparison);
        } elseif ($pRMetalType instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PRBadgePeer::P_R_METAL_TYPE_ID, $pRMetalType->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByPRMetalType() only accepts arguments of type PRMetalType or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PRMetalType relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PRBadgeQuery The current query, for fluid interface
     */
    public function joinPRMetalType($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PRMetalType');

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
            $this->addJoinObject($join, 'PRMetalType');
        }

        return $this;
    }

    /**
     * Use the PRMetalType relation PRMetalType object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Politizr\Model\PRMetalTypeQuery A secondary query class using the current class as primary query
     */
    public function usePRMetalTypeQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPRMetalType($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PRMetalType', '\Politizr\Model\PRMetalTypeQuery');
    }

    /**
     * Filter the query by a related PRBadgeFamily object
     *
     * @param   PRBadgeFamily|PropelObjectCollection $pRBadgeFamily The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PRBadgeQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPRBadgeFamily($pRBadgeFamily, $comparison = null)
    {
        if ($pRBadgeFamily instanceof PRBadgeFamily) {
            return $this
                ->addUsingAlias(PRBadgePeer::P_R_BADGE_FAMILY_ID, $pRBadgeFamily->getId(), $comparison);
        } elseif ($pRBadgeFamily instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PRBadgePeer::P_R_BADGE_FAMILY_ID, $pRBadgeFamily->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByPRBadgeFamily() only accepts arguments of type PRBadgeFamily or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PRBadgeFamily relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PRBadgeQuery The current query, for fluid interface
     */
    public function joinPRBadgeFamily($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PRBadgeFamily');

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
            $this->addJoinObject($join, 'PRBadgeFamily');
        }

        return $this;
    }

    /**
     * Use the PRBadgeFamily relation PRBadgeFamily object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Politizr\Model\PRBadgeFamilyQuery A secondary query class using the current class as primary query
     */
    public function usePRBadgeFamilyQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPRBadgeFamily($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PRBadgeFamily', '\Politizr\Model\PRBadgeFamilyQuery');
    }

    /**
     * Filter the query by a related PUBadge object
     *
     * @param   PUBadge|PropelObjectCollection $pUBadge  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PRBadgeQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPUBadge($pUBadge, $comparison = null)
    {
        if ($pUBadge instanceof PUBadge) {
            return $this
                ->addUsingAlias(PRBadgePeer::ID, $pUBadge->getPRBadgeId(), $comparison);
        } elseif ($pUBadge instanceof PropelObjectCollection) {
            return $this
                ->usePUBadgeQuery()
                ->filterByPrimaryKeys($pUBadge->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByPUBadge() only accepts arguments of type PUBadge or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PUBadge relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PRBadgeQuery The current query, for fluid interface
     */
    public function joinPUBadge($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PUBadge');

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
            $this->addJoinObject($join, 'PUBadge');
        }

        return $this;
    }

    /**
     * Use the PUBadge relation PUBadge object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Politizr\Model\PUBadgeQuery A secondary query class using the current class as primary query
     */
    public function usePUBadgeQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPUBadge($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PUBadge', '\Politizr\Model\PUBadgeQuery');
    }

    /**
     * Filter the query by a related PUser object
     * using the p_u_badge table as cross reference
     *
     * @param   PUser $pUser the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   PRBadgeQuery The current query, for fluid interface
     */
    public function filterByPUser($pUser, $comparison = Criteria::EQUAL)
    {
        return $this
            ->usePUBadgeQuery()
            ->filterByPUser($pUser, $comparison)
            ->endUse();
    }

    /**
     * Exclude object from result
     *
     * @param   PRBadge $pRBadge Object to remove from the list of results
     *
     * @return PRBadgeQuery The current query, for fluid interface
     */
    public function prune($pRBadge = null)
    {
        if ($pRBadge) {
            $this->addUsingAlias(PRBadgePeer::ID, $pRBadge->getId(), Criteria::NOT_EQUAL);
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
     * @return     PRBadgeQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(PRBadgePeer::UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     PRBadgeQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(PRBadgePeer::UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     PRBadgeQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(PRBadgePeer::UPDATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     PRBadgeQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(PRBadgePeer::CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date desc
     *
     * @return     PRBadgeQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(PRBadgePeer::CREATED_AT);
    }

    /**
     * Order by create date asc
     *
     * @return     PRBadgeQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(PRBadgePeer::CREATED_AT);
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

        $dbMap = Propel::getDatabaseMap(PRBadgePeer::DATABASE_NAME);
        $db = Propel::getDB(PRBadgePeer::DATABASE_NAME);

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

    // sortable behavior

    /**
     * Returns the objects in a certain list, from the list scope
     *
     * @param int $scope Scope to determine which objects node to return
     *
     * @return PRBadgeQuery The current query, for fluid interface
     */
    public function inList($scope)
    {

        PRBadgePeer::sortableApplyScopeCriteria($this, $scope, 'addUsingAlias');

        return $this;
    }

    /**
     * Filter the query based on a rank in the list
     *
     * @param     integer   $rank rank
     * @param int $scope Scope to determine which objects node to return

     *
     * @return    PRBadgeQuery The current query, for fluid interface
     */
    public function filterByRank($rank, $scope)
    {


        return $this
            ->inList($scope)
            ->addUsingAlias(PRBadgePeer::RANK_COL, $rank, Criteria::EQUAL);
    }

    /**
     * Order the query based on the rank in the list.
     * Using the default $order, returns the item with the lowest rank first
     *
     * @param     string $order either Criteria::ASC (default) or Criteria::DESC
     *
     * @return    PRBadgeQuery The current query, for fluid interface
     */
    public function orderByRank($order = Criteria::ASC)
    {
        $order = strtoupper($order);
        switch ($order) {
            case Criteria::ASC:
                return $this->addAscendingOrderByColumn($this->getAliasedColName(PRBadgePeer::RANK_COL));
                break;
            case Criteria::DESC:
                return $this->addDescendingOrderByColumn($this->getAliasedColName(PRBadgePeer::RANK_COL));
                break;
            default:
                throw new PropelException('PRBadgeQuery::orderBy() only accepts "asc" or "desc" as argument');
        }
    }

    /**
     * Get an item from the list based on its rank
     *
     * @param     integer   $rank rank
     * @param int $scope Scope to determine which objects node to return
     * @param     PropelPDO $con optional connection
     *
     * @return    PRBadge
     */
    public function findOneByRank($rank, $scope, PropelPDO $con = null)
    {

        return $this
            ->filterByRank($rank, $scope)
            ->findOne($con);
    }

    /**
     * Returns a list of objects
     *
     * @param int $scope Scope to determine which objects node to return

     * @param      PropelPDO $con	Connection to use.
     *
     * @return     mixed the list of results, formatted by the current formatter
     */
    public function findList($scope, $con = null)
    {


        return $this
            ->inList($scope)
            ->orderByRank()
            ->find($con);
    }

    /**
     * Get the highest rank
     *
     * @param int $scope Scope to determine which objects node to return

     * @param     PropelPDO optional connection
     *
     * @return    integer highest position
     */
    public function getMaxRank($scope, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(PRBadgePeer::DATABASE_NAME);
        }
        // shift the objects with a position lower than the one of object
        $this->addSelectColumn('MAX(' . PRBadgePeer::RANK_COL . ')');

        PRBadgePeer::sortableApplyScopeCriteria($this, $scope);
        $stmt = $this->doSelect($con);

        return $stmt->fetchColumn();
    }

    /**
     * Get the highest rank by a scope with a array format.
     *
     * @param     int $scope		The scope value as scalar type or array($value1, ...).

     * @param     PropelPDO optional connection
     *
     * @return    integer highest position
     */
    public function getMaxRankArray($scope, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(PRBadgePeer::DATABASE_NAME);
        }
        // shift the objects with a position lower than the one of object
        $this->addSelectColumn('MAX(' . PRBadgePeer::RANK_COL . ')');
        PRBadgePeer::sortableApplyScopeCriteria($this, $scope);
        $stmt = $this->doSelect($con);

        return $stmt->fetchColumn();
    }

    /**
     * Reorder a set of sortable objects based on a list of id/position
     * Beware that there is no check made on the positions passed
     * So incoherent positions will result in an incoherent list
     *
     * @param     array     $order id => rank pairs
     * @param     PropelPDO $con   optional connection
     *
     * @return    boolean true if the reordering took place, false if a database problem prevented it
     */
    public function reorder(array $order, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(PRBadgePeer::DATABASE_NAME);
        }

        $con->beginTransaction();
        try {
            $ids = array_keys($order);
            $objects = $this->findPks($ids, $con);
            foreach ($objects as $object) {
                $pk = $object->getPrimaryKey();
                if ($object->getSortableRank() != $order[$pk]) {
                    $object->setSortableRank($order[$pk]);
                    $object->save($con);
                }
            }
            $con->commit();

            return true;
        } catch (Exception $e) {
            $con->rollback();
            throw $e;
        }
    }

    // archivable behavior

    /**
     * Copy the data of the objects satisfying the query into PRBadgeArchive archive objects.
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
            $con = Propel::getConnection(PRBadgePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
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
