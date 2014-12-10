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
use Politizr\Model\PUQualificationArchive;
use Politizr\Model\PUQualificationArchivePeer;
use Politizr\Model\PUQualificationArchiveQuery;

/**
 * @method PUQualificationArchiveQuery orderById($order = Criteria::ASC) Order by the id column
 * @method PUQualificationArchiveQuery orderByPUserId($order = Criteria::ASC) Order by the p_user_id column
 * @method PUQualificationArchiveQuery orderByPUPoliticalPartyId($order = Criteria::ASC) Order by the p_u_political_party_id column
 * @method PUQualificationArchiveQuery orderByPUMandateTypeId($order = Criteria::ASC) Order by the p_u_mandate_type_id column
 * @method PUQualificationArchiveQuery orderByDescription($order = Criteria::ASC) Order by the description column
 * @method PUQualificationArchiveQuery orderByBeginAt($order = Criteria::ASC) Order by the begin_at column
 * @method PUQualificationArchiveQuery orderByEndAt($order = Criteria::ASC) Order by the end_at column
 * @method PUQualificationArchiveQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method PUQualificationArchiveQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 * @method PUQualificationArchiveQuery orderByArchivedAt($order = Criteria::ASC) Order by the archived_at column
 *
 * @method PUQualificationArchiveQuery groupById() Group by the id column
 * @method PUQualificationArchiveQuery groupByPUserId() Group by the p_user_id column
 * @method PUQualificationArchiveQuery groupByPUPoliticalPartyId() Group by the p_u_political_party_id column
 * @method PUQualificationArchiveQuery groupByPUMandateTypeId() Group by the p_u_mandate_type_id column
 * @method PUQualificationArchiveQuery groupByDescription() Group by the description column
 * @method PUQualificationArchiveQuery groupByBeginAt() Group by the begin_at column
 * @method PUQualificationArchiveQuery groupByEndAt() Group by the end_at column
 * @method PUQualificationArchiveQuery groupByCreatedAt() Group by the created_at column
 * @method PUQualificationArchiveQuery groupByUpdatedAt() Group by the updated_at column
 * @method PUQualificationArchiveQuery groupByArchivedAt() Group by the archived_at column
 *
 * @method PUQualificationArchiveQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method PUQualificationArchiveQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method PUQualificationArchiveQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method PUQualificationArchive findOne(PropelPDO $con = null) Return the first PUQualificationArchive matching the query
 * @method PUQualificationArchive findOneOrCreate(PropelPDO $con = null) Return the first PUQualificationArchive matching the query, or a new PUQualificationArchive object populated from the query conditions when no match is found
 *
 * @method PUQualificationArchive findOneByPUserId(int $p_user_id) Return the first PUQualificationArchive filtered by the p_user_id column
 * @method PUQualificationArchive findOneByPUPoliticalPartyId(int $p_u_political_party_id) Return the first PUQualificationArchive filtered by the p_u_political_party_id column
 * @method PUQualificationArchive findOneByPUMandateTypeId(int $p_u_mandate_type_id) Return the first PUQualificationArchive filtered by the p_u_mandate_type_id column
 * @method PUQualificationArchive findOneByDescription(string $description) Return the first PUQualificationArchive filtered by the description column
 * @method PUQualificationArchive findOneByBeginAt(string $begin_at) Return the first PUQualificationArchive filtered by the begin_at column
 * @method PUQualificationArchive findOneByEndAt(string $end_at) Return the first PUQualificationArchive filtered by the end_at column
 * @method PUQualificationArchive findOneByCreatedAt(string $created_at) Return the first PUQualificationArchive filtered by the created_at column
 * @method PUQualificationArchive findOneByUpdatedAt(string $updated_at) Return the first PUQualificationArchive filtered by the updated_at column
 * @method PUQualificationArchive findOneByArchivedAt(string $archived_at) Return the first PUQualificationArchive filtered by the archived_at column
 *
 * @method array findById(int $id) Return PUQualificationArchive objects filtered by the id column
 * @method array findByPUserId(int $p_user_id) Return PUQualificationArchive objects filtered by the p_user_id column
 * @method array findByPUPoliticalPartyId(int $p_u_political_party_id) Return PUQualificationArchive objects filtered by the p_u_political_party_id column
 * @method array findByPUMandateTypeId(int $p_u_mandate_type_id) Return PUQualificationArchive objects filtered by the p_u_mandate_type_id column
 * @method array findByDescription(string $description) Return PUQualificationArchive objects filtered by the description column
 * @method array findByBeginAt(string $begin_at) Return PUQualificationArchive objects filtered by the begin_at column
 * @method array findByEndAt(string $end_at) Return PUQualificationArchive objects filtered by the end_at column
 * @method array findByCreatedAt(string $created_at) Return PUQualificationArchive objects filtered by the created_at column
 * @method array findByUpdatedAt(string $updated_at) Return PUQualificationArchive objects filtered by the updated_at column
 * @method array findByArchivedAt(string $archived_at) Return PUQualificationArchive objects filtered by the archived_at column
 */
abstract class BasePUQualificationArchiveQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BasePUQualificationArchiveQuery object.
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
            $modelName = 'Politizr\\Model\\PUQualificationArchive';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new PUQualificationArchiveQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   PUQualificationArchiveQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return PUQualificationArchiveQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof PUQualificationArchiveQuery) {
            return $criteria;
        }
        $query = new PUQualificationArchiveQuery(null, null, $modelAlias);

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
     * @return   PUQualificationArchive|PUQualificationArchive[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = PUQualificationArchivePeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(PUQualificationArchivePeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 PUQualificationArchive A model object, or null if the key is not found
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
     * @return                 PUQualificationArchive A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `p_user_id`, `p_u_political_party_id`, `p_u_mandate_type_id`, `description`, `begin_at`, `end_at`, `created_at`, `updated_at`, `archived_at` FROM `p_u_qualification_archive` WHERE `id` = :p0';
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
            $obj = new PUQualificationArchive();
            $obj->hydrate($row);
            PUQualificationArchivePeer::addInstanceToPool($obj, (string) $key);
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
     * @return PUQualificationArchive|PUQualificationArchive[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|PUQualificationArchive[]|mixed the list of results, formatted by the current formatter
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
     * @return PUQualificationArchiveQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(PUQualificationArchivePeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return PUQualificationArchiveQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(PUQualificationArchivePeer::ID, $keys, Criteria::IN);
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
     * @return PUQualificationArchiveQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(PUQualificationArchivePeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(PUQualificationArchivePeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PUQualificationArchivePeer::ID, $id, $comparison);
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
     * @param     mixed $pUserId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PUQualificationArchiveQuery The current query, for fluid interface
     */
    public function filterByPUserId($pUserId = null, $comparison = null)
    {
        if (is_array($pUserId)) {
            $useMinMax = false;
            if (isset($pUserId['min'])) {
                $this->addUsingAlias(PUQualificationArchivePeer::P_USER_ID, $pUserId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($pUserId['max'])) {
                $this->addUsingAlias(PUQualificationArchivePeer::P_USER_ID, $pUserId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PUQualificationArchivePeer::P_USER_ID, $pUserId, $comparison);
    }

    /**
     * Filter the query on the p_u_political_party_id column
     *
     * Example usage:
     * <code>
     * $query->filterByPUPoliticalPartyId(1234); // WHERE p_u_political_party_id = 1234
     * $query->filterByPUPoliticalPartyId(array(12, 34)); // WHERE p_u_political_party_id IN (12, 34)
     * $query->filterByPUPoliticalPartyId(array('min' => 12)); // WHERE p_u_political_party_id >= 12
     * $query->filterByPUPoliticalPartyId(array('max' => 12)); // WHERE p_u_political_party_id <= 12
     * </code>
     *
     * @param     mixed $pUPoliticalPartyId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PUQualificationArchiveQuery The current query, for fluid interface
     */
    public function filterByPUPoliticalPartyId($pUPoliticalPartyId = null, $comparison = null)
    {
        if (is_array($pUPoliticalPartyId)) {
            $useMinMax = false;
            if (isset($pUPoliticalPartyId['min'])) {
                $this->addUsingAlias(PUQualificationArchivePeer::P_U_POLITICAL_PARTY_ID, $pUPoliticalPartyId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($pUPoliticalPartyId['max'])) {
                $this->addUsingAlias(PUQualificationArchivePeer::P_U_POLITICAL_PARTY_ID, $pUPoliticalPartyId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PUQualificationArchivePeer::P_U_POLITICAL_PARTY_ID, $pUPoliticalPartyId, $comparison);
    }

    /**
     * Filter the query on the p_u_mandate_type_id column
     *
     * Example usage:
     * <code>
     * $query->filterByPUMandateTypeId(1234); // WHERE p_u_mandate_type_id = 1234
     * $query->filterByPUMandateTypeId(array(12, 34)); // WHERE p_u_mandate_type_id IN (12, 34)
     * $query->filterByPUMandateTypeId(array('min' => 12)); // WHERE p_u_mandate_type_id >= 12
     * $query->filterByPUMandateTypeId(array('max' => 12)); // WHERE p_u_mandate_type_id <= 12
     * </code>
     *
     * @param     mixed $pUMandateTypeId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PUQualificationArchiveQuery The current query, for fluid interface
     */
    public function filterByPUMandateTypeId($pUMandateTypeId = null, $comparison = null)
    {
        if (is_array($pUMandateTypeId)) {
            $useMinMax = false;
            if (isset($pUMandateTypeId['min'])) {
                $this->addUsingAlias(PUQualificationArchivePeer::P_U_MANDATE_TYPE_ID, $pUMandateTypeId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($pUMandateTypeId['max'])) {
                $this->addUsingAlias(PUQualificationArchivePeer::P_U_MANDATE_TYPE_ID, $pUMandateTypeId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PUQualificationArchivePeer::P_U_MANDATE_TYPE_ID, $pUMandateTypeId, $comparison);
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
     * @return PUQualificationArchiveQuery The current query, for fluid interface
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

        return $this->addUsingAlias(PUQualificationArchivePeer::DESCRIPTION, $description, $comparison);
    }

    /**
     * Filter the query on the begin_at column
     *
     * Example usage:
     * <code>
     * $query->filterByBeginAt('2011-03-14'); // WHERE begin_at = '2011-03-14'
     * $query->filterByBeginAt('now'); // WHERE begin_at = '2011-03-14'
     * $query->filterByBeginAt(array('max' => 'yesterday')); // WHERE begin_at < '2011-03-13'
     * </code>
     *
     * @param     mixed $beginAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PUQualificationArchiveQuery The current query, for fluid interface
     */
    public function filterByBeginAt($beginAt = null, $comparison = null)
    {
        if (is_array($beginAt)) {
            $useMinMax = false;
            if (isset($beginAt['min'])) {
                $this->addUsingAlias(PUQualificationArchivePeer::BEGIN_AT, $beginAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($beginAt['max'])) {
                $this->addUsingAlias(PUQualificationArchivePeer::BEGIN_AT, $beginAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PUQualificationArchivePeer::BEGIN_AT, $beginAt, $comparison);
    }

    /**
     * Filter the query on the end_at column
     *
     * Example usage:
     * <code>
     * $query->filterByEndAt('2011-03-14'); // WHERE end_at = '2011-03-14'
     * $query->filterByEndAt('now'); // WHERE end_at = '2011-03-14'
     * $query->filterByEndAt(array('max' => 'yesterday')); // WHERE end_at < '2011-03-13'
     * </code>
     *
     * @param     mixed $endAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PUQualificationArchiveQuery The current query, for fluid interface
     */
    public function filterByEndAt($endAt = null, $comparison = null)
    {
        if (is_array($endAt)) {
            $useMinMax = false;
            if (isset($endAt['min'])) {
                $this->addUsingAlias(PUQualificationArchivePeer::END_AT, $endAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($endAt['max'])) {
                $this->addUsingAlias(PUQualificationArchivePeer::END_AT, $endAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PUQualificationArchivePeer::END_AT, $endAt, $comparison);
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
     * @return PUQualificationArchiveQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(PUQualificationArchivePeer::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(PUQualificationArchivePeer::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PUQualificationArchivePeer::CREATED_AT, $createdAt, $comparison);
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
     * @return PUQualificationArchiveQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(PUQualificationArchivePeer::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(PUQualificationArchivePeer::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PUQualificationArchivePeer::UPDATED_AT, $updatedAt, $comparison);
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
     * @return PUQualificationArchiveQuery The current query, for fluid interface
     */
    public function filterByArchivedAt($archivedAt = null, $comparison = null)
    {
        if (is_array($archivedAt)) {
            $useMinMax = false;
            if (isset($archivedAt['min'])) {
                $this->addUsingAlias(PUQualificationArchivePeer::ARCHIVED_AT, $archivedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($archivedAt['max'])) {
                $this->addUsingAlias(PUQualificationArchivePeer::ARCHIVED_AT, $archivedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PUQualificationArchivePeer::ARCHIVED_AT, $archivedAt, $comparison);
    }

    /**
     * Exclude object from result
     *
     * @param   PUQualificationArchive $pUQualificationArchive Object to remove from the list of results
     *
     * @return PUQualificationArchiveQuery The current query, for fluid interface
     */
    public function prune($pUQualificationArchive = null)
    {
        if ($pUQualificationArchive) {
            $this->addUsingAlias(PUQualificationArchivePeer::ID, $pUQualificationArchive->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

}
