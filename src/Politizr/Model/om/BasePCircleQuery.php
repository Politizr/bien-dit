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
use Politizr\Model\PCGroupLC;
use Politizr\Model\PCOwner;
use Politizr\Model\PCTopic;
use Politizr\Model\PCircle;
use Politizr\Model\PCirclePeer;
use Politizr\Model\PCircleQuery;
use Politizr\Model\PCircleType;
use Politizr\Model\PLCity;
use Politizr\Model\PMCharte;
use Politizr\Model\PUInPC;
use Politizr\Model\PUser;

/**
 * @method PCircleQuery orderById($order = Criteria::ASC) Order by the id column
 * @method PCircleQuery orderByUuid($order = Criteria::ASC) Order by the uuid column
 * @method PCircleQuery orderByPCOwnerId($order = Criteria::ASC) Order by the p_c_owner_id column
 * @method PCircleQuery orderByPCircleTypeId($order = Criteria::ASC) Order by the p_circle_type_id column
 * @method PCircleQuery orderByTitle($order = Criteria::ASC) Order by the title column
 * @method PCircleQuery orderBySummary($order = Criteria::ASC) Order by the summary column
 * @method PCircleQuery orderByDescription($order = Criteria::ASC) Order by the description column
 * @method PCircleQuery orderByLogoFileName($order = Criteria::ASC) Order by the logo_file_name column
 * @method PCircleQuery orderByUrl($order = Criteria::ASC) Order by the url column
 * @method PCircleQuery orderByOnline($order = Criteria::ASC) Order by the online column
 * @method PCircleQuery orderByReadOnly($order = Criteria::ASC) Order by the read_only column
 * @method PCircleQuery orderByPrivateAccess($order = Criteria::ASC) Order by the private_access column
 * @method PCircleQuery orderByPublicCircle($order = Criteria::ASC) Order by the public_circle column
 * @method PCircleQuery orderByOpenReaction($order = Criteria::ASC) Order by the open_reaction column
 * @method PCircleQuery orderByStep($order = Criteria::ASC) Order by the step column
 * @method PCircleQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method PCircleQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 * @method PCircleQuery orderBySlug($order = Criteria::ASC) Order by the slug column
 * @method PCircleQuery orderBySortableRank($order = Criteria::ASC) Order by the sortable_rank column
 *
 * @method PCircleQuery groupById() Group by the id column
 * @method PCircleQuery groupByUuid() Group by the uuid column
 * @method PCircleQuery groupByPCOwnerId() Group by the p_c_owner_id column
 * @method PCircleQuery groupByPCircleTypeId() Group by the p_circle_type_id column
 * @method PCircleQuery groupByTitle() Group by the title column
 * @method PCircleQuery groupBySummary() Group by the summary column
 * @method PCircleQuery groupByDescription() Group by the description column
 * @method PCircleQuery groupByLogoFileName() Group by the logo_file_name column
 * @method PCircleQuery groupByUrl() Group by the url column
 * @method PCircleQuery groupByOnline() Group by the online column
 * @method PCircleQuery groupByReadOnly() Group by the read_only column
 * @method PCircleQuery groupByPrivateAccess() Group by the private_access column
 * @method PCircleQuery groupByPublicCircle() Group by the public_circle column
 * @method PCircleQuery groupByOpenReaction() Group by the open_reaction column
 * @method PCircleQuery groupByStep() Group by the step column
 * @method PCircleQuery groupByCreatedAt() Group by the created_at column
 * @method PCircleQuery groupByUpdatedAt() Group by the updated_at column
 * @method PCircleQuery groupBySlug() Group by the slug column
 * @method PCircleQuery groupBySortableRank() Group by the sortable_rank column
 *
 * @method PCircleQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method PCircleQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method PCircleQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method PCircleQuery leftJoinPCOwner($relationAlias = null) Adds a LEFT JOIN clause to the query using the PCOwner relation
 * @method PCircleQuery rightJoinPCOwner($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PCOwner relation
 * @method PCircleQuery innerJoinPCOwner($relationAlias = null) Adds a INNER JOIN clause to the query using the PCOwner relation
 *
 * @method PCircleQuery leftJoinPCircleType($relationAlias = null) Adds a LEFT JOIN clause to the query using the PCircleType relation
 * @method PCircleQuery rightJoinPCircleType($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PCircleType relation
 * @method PCircleQuery innerJoinPCircleType($relationAlias = null) Adds a INNER JOIN clause to the query using the PCircleType relation
 *
 * @method PCircleQuery leftJoinPCTopic($relationAlias = null) Adds a LEFT JOIN clause to the query using the PCTopic relation
 * @method PCircleQuery rightJoinPCTopic($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PCTopic relation
 * @method PCircleQuery innerJoinPCTopic($relationAlias = null) Adds a INNER JOIN clause to the query using the PCTopic relation
 *
 * @method PCircleQuery leftJoinPCGroupLC($relationAlias = null) Adds a LEFT JOIN clause to the query using the PCGroupLC relation
 * @method PCircleQuery rightJoinPCGroupLC($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PCGroupLC relation
 * @method PCircleQuery innerJoinPCGroupLC($relationAlias = null) Adds a INNER JOIN clause to the query using the PCGroupLC relation
 *
 * @method PCircleQuery leftJoinPUInPC($relationAlias = null) Adds a LEFT JOIN clause to the query using the PUInPC relation
 * @method PCircleQuery rightJoinPUInPC($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PUInPC relation
 * @method PCircleQuery innerJoinPUInPC($relationAlias = null) Adds a INNER JOIN clause to the query using the PUInPC relation
 *
 * @method PCircleQuery leftJoinPMCharte($relationAlias = null) Adds a LEFT JOIN clause to the query using the PMCharte relation
 * @method PCircleQuery rightJoinPMCharte($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PMCharte relation
 * @method PCircleQuery innerJoinPMCharte($relationAlias = null) Adds a INNER JOIN clause to the query using the PMCharte relation
 *
 * @method PCircle findOne(PropelPDO $con = null) Return the first PCircle matching the query
 * @method PCircle findOneOrCreate(PropelPDO $con = null) Return the first PCircle matching the query, or a new PCircle object populated from the query conditions when no match is found
 *
 * @method PCircle findOneByUuid(string $uuid) Return the first PCircle filtered by the uuid column
 * @method PCircle findOneByPCOwnerId(int $p_c_owner_id) Return the first PCircle filtered by the p_c_owner_id column
 * @method PCircle findOneByPCircleTypeId(int $p_circle_type_id) Return the first PCircle filtered by the p_circle_type_id column
 * @method PCircle findOneByTitle(string $title) Return the first PCircle filtered by the title column
 * @method PCircle findOneBySummary(string $summary) Return the first PCircle filtered by the summary column
 * @method PCircle findOneByDescription(string $description) Return the first PCircle filtered by the description column
 * @method PCircle findOneByLogoFileName(string $logo_file_name) Return the first PCircle filtered by the logo_file_name column
 * @method PCircle findOneByUrl(string $url) Return the first PCircle filtered by the url column
 * @method PCircle findOneByOnline(boolean $online) Return the first PCircle filtered by the online column
 * @method PCircle findOneByReadOnly(boolean $read_only) Return the first PCircle filtered by the read_only column
 * @method PCircle findOneByPrivateAccess(boolean $private_access) Return the first PCircle filtered by the private_access column
 * @method PCircle findOneByPublicCircle(boolean $public_circle) Return the first PCircle filtered by the public_circle column
 * @method PCircle findOneByOpenReaction(boolean $open_reaction) Return the first PCircle filtered by the open_reaction column
 * @method PCircle findOneByStep(int $step) Return the first PCircle filtered by the step column
 * @method PCircle findOneByCreatedAt(string $created_at) Return the first PCircle filtered by the created_at column
 * @method PCircle findOneByUpdatedAt(string $updated_at) Return the first PCircle filtered by the updated_at column
 * @method PCircle findOneBySlug(string $slug) Return the first PCircle filtered by the slug column
 * @method PCircle findOneBySortableRank(int $sortable_rank) Return the first PCircle filtered by the sortable_rank column
 *
 * @method array findById(int $id) Return PCircle objects filtered by the id column
 * @method array findByUuid(string $uuid) Return PCircle objects filtered by the uuid column
 * @method array findByPCOwnerId(int $p_c_owner_id) Return PCircle objects filtered by the p_c_owner_id column
 * @method array findByPCircleTypeId(int $p_circle_type_id) Return PCircle objects filtered by the p_circle_type_id column
 * @method array findByTitle(string $title) Return PCircle objects filtered by the title column
 * @method array findBySummary(string $summary) Return PCircle objects filtered by the summary column
 * @method array findByDescription(string $description) Return PCircle objects filtered by the description column
 * @method array findByLogoFileName(string $logo_file_name) Return PCircle objects filtered by the logo_file_name column
 * @method array findByUrl(string $url) Return PCircle objects filtered by the url column
 * @method array findByOnline(boolean $online) Return PCircle objects filtered by the online column
 * @method array findByReadOnly(boolean $read_only) Return PCircle objects filtered by the read_only column
 * @method array findByPrivateAccess(boolean $private_access) Return PCircle objects filtered by the private_access column
 * @method array findByPublicCircle(boolean $public_circle) Return PCircle objects filtered by the public_circle column
 * @method array findByOpenReaction(boolean $open_reaction) Return PCircle objects filtered by the open_reaction column
 * @method array findByStep(int $step) Return PCircle objects filtered by the step column
 * @method array findByCreatedAt(string $created_at) Return PCircle objects filtered by the created_at column
 * @method array findByUpdatedAt(string $updated_at) Return PCircle objects filtered by the updated_at column
 * @method array findBySlug(string $slug) Return PCircle objects filtered by the slug column
 * @method array findBySortableRank(int $sortable_rank) Return PCircle objects filtered by the sortable_rank column
 */
abstract class BasePCircleQuery extends ModelCriteria
{
    // query_cache behavior
    protected $queryKey = '';

    // archivable behavior
    protected $archiveOnDelete = true;

    /**
     * Initializes internal state of BasePCircleQuery object.
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
            $modelName = 'Politizr\\Model\\PCircle';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new PCircleQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   PCircleQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return PCircleQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof PCircleQuery) {
            return $criteria;
        }
        $query = new PCircleQuery(null, null, $modelAlias);

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
     * @return   PCircle|PCircle[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = PCirclePeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(PCirclePeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 PCircle A model object, or null if the key is not found
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
     * @return                 PCircle A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `uuid`, `p_c_owner_id`, `p_circle_type_id`, `title`, `summary`, `description`, `logo_file_name`, `url`, `online`, `read_only`, `private_access`, `public_circle`, `open_reaction`, `step`, `created_at`, `updated_at`, `slug`, `sortable_rank` FROM `p_circle` WHERE `id` = :p0';
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
            $obj = new PCircle();
            $obj->hydrate($row);
            PCirclePeer::addInstanceToPool($obj, (string) $key);
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
     * @return PCircle|PCircle[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|PCircle[]|mixed the list of results, formatted by the current formatter
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
     * @return PCircleQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(PCirclePeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return PCircleQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(PCirclePeer::ID, $keys, Criteria::IN);
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
     * @return PCircleQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(PCirclePeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(PCirclePeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PCirclePeer::ID, $id, $comparison);
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
     * @return PCircleQuery The current query, for fluid interface
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

        return $this->addUsingAlias(PCirclePeer::UUID, $uuid, $comparison);
    }

    /**
     * Filter the query on the p_c_owner_id column
     *
     * Example usage:
     * <code>
     * $query->filterByPCOwnerId(1234); // WHERE p_c_owner_id = 1234
     * $query->filterByPCOwnerId(array(12, 34)); // WHERE p_c_owner_id IN (12, 34)
     * $query->filterByPCOwnerId(array('min' => 12)); // WHERE p_c_owner_id >= 12
     * $query->filterByPCOwnerId(array('max' => 12)); // WHERE p_c_owner_id <= 12
     * </code>
     *
     * @see       filterByPCOwner()
     *
     * @param     mixed $pCOwnerId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PCircleQuery The current query, for fluid interface
     */
    public function filterByPCOwnerId($pCOwnerId = null, $comparison = null)
    {
        if (is_array($pCOwnerId)) {
            $useMinMax = false;
            if (isset($pCOwnerId['min'])) {
                $this->addUsingAlias(PCirclePeer::P_C_OWNER_ID, $pCOwnerId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($pCOwnerId['max'])) {
                $this->addUsingAlias(PCirclePeer::P_C_OWNER_ID, $pCOwnerId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PCirclePeer::P_C_OWNER_ID, $pCOwnerId, $comparison);
    }

    /**
     * Filter the query on the p_circle_type_id column
     *
     * Example usage:
     * <code>
     * $query->filterByPCircleTypeId(1234); // WHERE p_circle_type_id = 1234
     * $query->filterByPCircleTypeId(array(12, 34)); // WHERE p_circle_type_id IN (12, 34)
     * $query->filterByPCircleTypeId(array('min' => 12)); // WHERE p_circle_type_id >= 12
     * $query->filterByPCircleTypeId(array('max' => 12)); // WHERE p_circle_type_id <= 12
     * </code>
     *
     * @see       filterByPCircleType()
     *
     * @param     mixed $pCircleTypeId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PCircleQuery The current query, for fluid interface
     */
    public function filterByPCircleTypeId($pCircleTypeId = null, $comparison = null)
    {
        if (is_array($pCircleTypeId)) {
            $useMinMax = false;
            if (isset($pCircleTypeId['min'])) {
                $this->addUsingAlias(PCirclePeer::P_CIRCLE_TYPE_ID, $pCircleTypeId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($pCircleTypeId['max'])) {
                $this->addUsingAlias(PCirclePeer::P_CIRCLE_TYPE_ID, $pCircleTypeId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PCirclePeer::P_CIRCLE_TYPE_ID, $pCircleTypeId, $comparison);
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
     * @return PCircleQuery The current query, for fluid interface
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

        return $this->addUsingAlias(PCirclePeer::TITLE, $title, $comparison);
    }

    /**
     * Filter the query on the summary column
     *
     * Example usage:
     * <code>
     * $query->filterBySummary('fooValue');   // WHERE summary = 'fooValue'
     * $query->filterBySummary('%fooValue%'); // WHERE summary LIKE '%fooValue%'
     * </code>
     *
     * @param     string $summary The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PCircleQuery The current query, for fluid interface
     */
    public function filterBySummary($summary = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($summary)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $summary)) {
                $summary = str_replace('*', '%', $summary);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(PCirclePeer::SUMMARY, $summary, $comparison);
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
     * @return PCircleQuery The current query, for fluid interface
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

        return $this->addUsingAlias(PCirclePeer::DESCRIPTION, $description, $comparison);
    }

    /**
     * Filter the query on the logo_file_name column
     *
     * Example usage:
     * <code>
     * $query->filterByLogoFileName('fooValue');   // WHERE logo_file_name = 'fooValue'
     * $query->filterByLogoFileName('%fooValue%'); // WHERE logo_file_name LIKE '%fooValue%'
     * </code>
     *
     * @param     string $logoFileName The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PCircleQuery The current query, for fluid interface
     */
    public function filterByLogoFileName($logoFileName = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($logoFileName)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $logoFileName)) {
                $logoFileName = str_replace('*', '%', $logoFileName);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(PCirclePeer::LOGO_FILE_NAME, $logoFileName, $comparison);
    }

    /**
     * Filter the query on the url column
     *
     * Example usage:
     * <code>
     * $query->filterByUrl('fooValue');   // WHERE url = 'fooValue'
     * $query->filterByUrl('%fooValue%'); // WHERE url LIKE '%fooValue%'
     * </code>
     *
     * @param     string $url The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PCircleQuery The current query, for fluid interface
     */
    public function filterByUrl($url = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($url)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $url)) {
                $url = str_replace('*', '%', $url);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(PCirclePeer::URL, $url, $comparison);
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
     * @return PCircleQuery The current query, for fluid interface
     */
    public function filterByOnline($online = null, $comparison = null)
    {
        if (is_string($online)) {
            $online = in_array(strtolower($online), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(PCirclePeer::ONLINE, $online, $comparison);
    }

    /**
     * Filter the query on the read_only column
     *
     * Example usage:
     * <code>
     * $query->filterByReadOnly(true); // WHERE read_only = true
     * $query->filterByReadOnly('yes'); // WHERE read_only = true
     * </code>
     *
     * @param     boolean|string $readOnly The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PCircleQuery The current query, for fluid interface
     */
    public function filterByReadOnly($readOnly = null, $comparison = null)
    {
        if (is_string($readOnly)) {
            $readOnly = in_array(strtolower($readOnly), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(PCirclePeer::READ_ONLY, $readOnly, $comparison);
    }

    /**
     * Filter the query on the private_access column
     *
     * Example usage:
     * <code>
     * $query->filterByPrivateAccess(true); // WHERE private_access = true
     * $query->filterByPrivateAccess('yes'); // WHERE private_access = true
     * </code>
     *
     * @param     boolean|string $privateAccess The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PCircleQuery The current query, for fluid interface
     */
    public function filterByPrivateAccess($privateAccess = null, $comparison = null)
    {
        if (is_string($privateAccess)) {
            $privateAccess = in_array(strtolower($privateAccess), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(PCirclePeer::PRIVATE_ACCESS, $privateAccess, $comparison);
    }

    /**
     * Filter the query on the public_circle column
     *
     * Example usage:
     * <code>
     * $query->filterByPublicCircle(true); // WHERE public_circle = true
     * $query->filterByPublicCircle('yes'); // WHERE public_circle = true
     * </code>
     *
     * @param     boolean|string $publicCircle The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PCircleQuery The current query, for fluid interface
     */
    public function filterByPublicCircle($publicCircle = null, $comparison = null)
    {
        if (is_string($publicCircle)) {
            $publicCircle = in_array(strtolower($publicCircle), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(PCirclePeer::PUBLIC_CIRCLE, $publicCircle, $comparison);
    }

    /**
     * Filter the query on the open_reaction column
     *
     * Example usage:
     * <code>
     * $query->filterByOpenReaction(true); // WHERE open_reaction = true
     * $query->filterByOpenReaction('yes'); // WHERE open_reaction = true
     * </code>
     *
     * @param     boolean|string $openReaction The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PCircleQuery The current query, for fluid interface
     */
    public function filterByOpenReaction($openReaction = null, $comparison = null)
    {
        if (is_string($openReaction)) {
            $openReaction = in_array(strtolower($openReaction), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(PCirclePeer::OPEN_REACTION, $openReaction, $comparison);
    }

    /**
     * Filter the query on the step column
     *
     * Example usage:
     * <code>
     * $query->filterByStep(1234); // WHERE step = 1234
     * $query->filterByStep(array(12, 34)); // WHERE step IN (12, 34)
     * $query->filterByStep(array('min' => 12)); // WHERE step >= 12
     * $query->filterByStep(array('max' => 12)); // WHERE step <= 12
     * </code>
     *
     * @param     mixed $step The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PCircleQuery The current query, for fluid interface
     */
    public function filterByStep($step = null, $comparison = null)
    {
        if (is_array($step)) {
            $useMinMax = false;
            if (isset($step['min'])) {
                $this->addUsingAlias(PCirclePeer::STEP, $step['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($step['max'])) {
                $this->addUsingAlias(PCirclePeer::STEP, $step['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PCirclePeer::STEP, $step, $comparison);
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
     * @return PCircleQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(PCirclePeer::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(PCirclePeer::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PCirclePeer::CREATED_AT, $createdAt, $comparison);
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
     * @return PCircleQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(PCirclePeer::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(PCirclePeer::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PCirclePeer::UPDATED_AT, $updatedAt, $comparison);
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
     * @return PCircleQuery The current query, for fluid interface
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

        return $this->addUsingAlias(PCirclePeer::SLUG, $slug, $comparison);
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
     * @return PCircleQuery The current query, for fluid interface
     */
    public function filterBySortableRank($sortableRank = null, $comparison = null)
    {
        if (is_array($sortableRank)) {
            $useMinMax = false;
            if (isset($sortableRank['min'])) {
                $this->addUsingAlias(PCirclePeer::SORTABLE_RANK, $sortableRank['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($sortableRank['max'])) {
                $this->addUsingAlias(PCirclePeer::SORTABLE_RANK, $sortableRank['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PCirclePeer::SORTABLE_RANK, $sortableRank, $comparison);
    }

    /**
     * Filter the query by a related PCOwner object
     *
     * @param   PCOwner|PropelObjectCollection $pCOwner The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PCircleQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPCOwner($pCOwner, $comparison = null)
    {
        if ($pCOwner instanceof PCOwner) {
            return $this
                ->addUsingAlias(PCirclePeer::P_C_OWNER_ID, $pCOwner->getId(), $comparison);
        } elseif ($pCOwner instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PCirclePeer::P_C_OWNER_ID, $pCOwner->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByPCOwner() only accepts arguments of type PCOwner or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PCOwner relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PCircleQuery The current query, for fluid interface
     */
    public function joinPCOwner($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PCOwner');

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
            $this->addJoinObject($join, 'PCOwner');
        }

        return $this;
    }

    /**
     * Use the PCOwner relation PCOwner object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Politizr\Model\PCOwnerQuery A secondary query class using the current class as primary query
     */
    public function usePCOwnerQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPCOwner($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PCOwner', '\Politizr\Model\PCOwnerQuery');
    }

    /**
     * Filter the query by a related PCircleType object
     *
     * @param   PCircleType|PropelObjectCollection $pCircleType The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PCircleQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPCircleType($pCircleType, $comparison = null)
    {
        if ($pCircleType instanceof PCircleType) {
            return $this
                ->addUsingAlias(PCirclePeer::P_CIRCLE_TYPE_ID, $pCircleType->getId(), $comparison);
        } elseif ($pCircleType instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PCirclePeer::P_CIRCLE_TYPE_ID, $pCircleType->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByPCircleType() only accepts arguments of type PCircleType or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PCircleType relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PCircleQuery The current query, for fluid interface
     */
    public function joinPCircleType($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PCircleType');

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
            $this->addJoinObject($join, 'PCircleType');
        }

        return $this;
    }

    /**
     * Use the PCircleType relation PCircleType object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Politizr\Model\PCircleTypeQuery A secondary query class using the current class as primary query
     */
    public function usePCircleTypeQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinPCircleType($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PCircleType', '\Politizr\Model\PCircleTypeQuery');
    }

    /**
     * Filter the query by a related PCTopic object
     *
     * @param   PCTopic|PropelObjectCollection $pCTopic  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PCircleQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPCTopic($pCTopic, $comparison = null)
    {
        if ($pCTopic instanceof PCTopic) {
            return $this
                ->addUsingAlias(PCirclePeer::ID, $pCTopic->getPCircleId(), $comparison);
        } elseif ($pCTopic instanceof PropelObjectCollection) {
            return $this
                ->usePCTopicQuery()
                ->filterByPrimaryKeys($pCTopic->getPrimaryKeys())
                ->endUse();
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
     * @return PCircleQuery The current query, for fluid interface
     */
    public function joinPCTopic($relationAlias = null, $joinType = Criteria::INNER_JOIN)
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
    public function usePCTopicQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPCTopic($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PCTopic', '\Politizr\Model\PCTopicQuery');
    }

    /**
     * Filter the query by a related PCGroupLC object
     *
     * @param   PCGroupLC|PropelObjectCollection $pCGroupLC  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PCircleQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPCGroupLC($pCGroupLC, $comparison = null)
    {
        if ($pCGroupLC instanceof PCGroupLC) {
            return $this
                ->addUsingAlias(PCirclePeer::ID, $pCGroupLC->getPCircleId(), $comparison);
        } elseif ($pCGroupLC instanceof PropelObjectCollection) {
            return $this
                ->usePCGroupLCQuery()
                ->filterByPrimaryKeys($pCGroupLC->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByPCGroupLC() only accepts arguments of type PCGroupLC or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PCGroupLC relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PCircleQuery The current query, for fluid interface
     */
    public function joinPCGroupLC($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PCGroupLC');

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
            $this->addJoinObject($join, 'PCGroupLC');
        }

        return $this;
    }

    /**
     * Use the PCGroupLC relation PCGroupLC object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Politizr\Model\PCGroupLCQuery A secondary query class using the current class as primary query
     */
    public function usePCGroupLCQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPCGroupLC($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PCGroupLC', '\Politizr\Model\PCGroupLCQuery');
    }

    /**
     * Filter the query by a related PUInPC object
     *
     * @param   PUInPC|PropelObjectCollection $pUInPC  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PCircleQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPUInPC($pUInPC, $comparison = null)
    {
        if ($pUInPC instanceof PUInPC) {
            return $this
                ->addUsingAlias(PCirclePeer::ID, $pUInPC->getPCircleId(), $comparison);
        } elseif ($pUInPC instanceof PropelObjectCollection) {
            return $this
                ->usePUInPCQuery()
                ->filterByPrimaryKeys($pUInPC->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByPUInPC() only accepts arguments of type PUInPC or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PUInPC relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PCircleQuery The current query, for fluid interface
     */
    public function joinPUInPC($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PUInPC');

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
            $this->addJoinObject($join, 'PUInPC');
        }

        return $this;
    }

    /**
     * Use the PUInPC relation PUInPC object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Politizr\Model\PUInPCQuery A secondary query class using the current class as primary query
     */
    public function usePUInPCQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPUInPC($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PUInPC', '\Politizr\Model\PUInPCQuery');
    }

    /**
     * Filter the query by a related PMCharte object
     *
     * @param   PMCharte|PropelObjectCollection $pMCharte  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PCircleQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPMCharte($pMCharte, $comparison = null)
    {
        if ($pMCharte instanceof PMCharte) {
            return $this
                ->addUsingAlias(PCirclePeer::ID, $pMCharte->getPCircleId(), $comparison);
        } elseif ($pMCharte instanceof PropelObjectCollection) {
            return $this
                ->usePMCharteQuery()
                ->filterByPrimaryKeys($pMCharte->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByPMCharte() only accepts arguments of type PMCharte or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PMCharte relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PCircleQuery The current query, for fluid interface
     */
    public function joinPMCharte($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PMCharte');

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
            $this->addJoinObject($join, 'PMCharte');
        }

        return $this;
    }

    /**
     * Use the PMCharte relation PMCharte object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Politizr\Model\PMCharteQuery A secondary query class using the current class as primary query
     */
    public function usePMCharteQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinPMCharte($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PMCharte', '\Politizr\Model\PMCharteQuery');
    }

    /**
     * Filter the query by a related PLCity object
     * using the p_c_group_l_c table as cross reference
     *
     * @param   PLCity $pLCity the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   PCircleQuery The current query, for fluid interface
     */
    public function filterByPLCity($pLCity, $comparison = Criteria::EQUAL)
    {
        return $this
            ->usePCGroupLCQuery()
            ->filterByPLCity($pLCity, $comparison)
            ->endUse();
    }

    /**
     * Filter the query by a related PUser object
     * using the p_u_in_p_c table as cross reference
     *
     * @param   PUser $pUser the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   PCircleQuery The current query, for fluid interface
     */
    public function filterByPUser($pUser, $comparison = Criteria::EQUAL)
    {
        return $this
            ->usePUInPCQuery()
            ->filterByPUser($pUser, $comparison)
            ->endUse();
    }

    /**
     * Exclude object from result
     *
     * @param   PCircle $pCircle Object to remove from the list of results
     *
     * @return PCircleQuery The current query, for fluid interface
     */
    public function prune($pCircle = null)
    {
        if ($pCircle) {
            $this->addUsingAlias(PCirclePeer::ID, $pCircle->getId(), Criteria::NOT_EQUAL);
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
     * @return     PCircleQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(PCirclePeer::UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     PCircleQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(PCirclePeer::UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     PCircleQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(PCirclePeer::UPDATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     PCircleQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(PCirclePeer::CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date desc
     *
     * @return     PCircleQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(PCirclePeer::CREATED_AT);
    }

    /**
     * Order by create date asc
     *
     * @return     PCircleQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(PCirclePeer::CREATED_AT);
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

        $dbMap = Propel::getDatabaseMap(PCirclePeer::DATABASE_NAME);
        $db = Propel::getDB(PCirclePeer::DATABASE_NAME);

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
     * @return PCircleQuery The current query, for fluid interface
     */
    public function inList($scope)
    {

        PCirclePeer::sortableApplyScopeCriteria($this, $scope, 'addUsingAlias');

        return $this;
    }

    /**
     * Filter the query based on a rank in the list
     *
     * @param     integer   $rank rank
     * @param int $scope Scope to determine which objects node to return

     *
     * @return    PCircleQuery The current query, for fluid interface
     */
    public function filterByRank($rank, $scope)
    {


        return $this
            ->inList($scope)
            ->addUsingAlias(PCirclePeer::RANK_COL, $rank, Criteria::EQUAL);
    }

    /**
     * Order the query based on the rank in the list.
     * Using the default $order, returns the item with the lowest rank first
     *
     * @param     string $order either Criteria::ASC (default) or Criteria::DESC
     *
     * @return    PCircleQuery The current query, for fluid interface
     */
    public function orderByRank($order = Criteria::ASC)
    {
        $order = strtoupper($order);
        switch ($order) {
            case Criteria::ASC:
                return $this->addAscendingOrderByColumn($this->getAliasedColName(PCirclePeer::RANK_COL));
                break;
            case Criteria::DESC:
                return $this->addDescendingOrderByColumn($this->getAliasedColName(PCirclePeer::RANK_COL));
                break;
            default:
                throw new PropelException('PCircleQuery::orderBy() only accepts "asc" or "desc" as argument');
        }
    }

    /**
     * Get an item from the list based on its rank
     *
     * @param     integer   $rank rank
     * @param int $scope Scope to determine which objects node to return
     * @param     PropelPDO $con optional connection
     *
     * @return    PCircle
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
            $con = Propel::getConnection(PCirclePeer::DATABASE_NAME);
        }
        // shift the objects with a position lower than the one of object
        $this->addSelectColumn('MAX(' . PCirclePeer::RANK_COL . ')');

        PCirclePeer::sortableApplyScopeCriteria($this, $scope);
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
            $con = Propel::getConnection(PCirclePeer::DATABASE_NAME);
        }
        // shift the objects with a position lower than the one of object
        $this->addSelectColumn('MAX(' . PCirclePeer::RANK_COL . ')');
        PCirclePeer::sortableApplyScopeCriteria($this, $scope);
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
            $con = Propel::getConnection(PCirclePeer::DATABASE_NAME);
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
     * Copy the data of the objects satisfying the query into PCircleArchive archive objects.
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
            $con = Propel::getConnection(PCirclePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
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
