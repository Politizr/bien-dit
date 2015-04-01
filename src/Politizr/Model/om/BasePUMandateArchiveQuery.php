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
use Glorpen\Propel\PropelBundle\Dispatcher\EventDispatcherProxy;
use Glorpen\Propel\PropelBundle\Events\QueryEvent;
use Politizr\Model\PUMandateArchive;
use Politizr\Model\PUMandateArchivePeer;
use Politizr\Model\PUMandateArchiveQuery;

/**
 * @method PUMandateArchiveQuery orderById($order = Criteria::ASC) Order by the id column
 * @method PUMandateArchiveQuery orderByPUserId($order = Criteria::ASC) Order by the p_user_id column
 * @method PUMandateArchiveQuery orderByPQTypeId($order = Criteria::ASC) Order by the p_q_type_id column
 * @method PUMandateArchiveQuery orderByPQMandateId($order = Criteria::ASC) Order by the p_q_mandate_id column
 * @method PUMandateArchiveQuery orderByPQOrganizationId($order = Criteria::ASC) Order by the p_q_organization_id column
 * @method PUMandateArchiveQuery orderByLocalization($order = Criteria::ASC) Order by the localization column
 * @method PUMandateArchiveQuery orderByBeginAt($order = Criteria::ASC) Order by the begin_at column
 * @method PUMandateArchiveQuery orderByEndAt($order = Criteria::ASC) Order by the end_at column
 * @method PUMandateArchiveQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method PUMandateArchiveQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 * @method PUMandateArchiveQuery orderByArchivedAt($order = Criteria::ASC) Order by the archived_at column
 *
 * @method PUMandateArchiveQuery groupById() Group by the id column
 * @method PUMandateArchiveQuery groupByPUserId() Group by the p_user_id column
 * @method PUMandateArchiveQuery groupByPQTypeId() Group by the p_q_type_id column
 * @method PUMandateArchiveQuery groupByPQMandateId() Group by the p_q_mandate_id column
 * @method PUMandateArchiveQuery groupByPQOrganizationId() Group by the p_q_organization_id column
 * @method PUMandateArchiveQuery groupByLocalization() Group by the localization column
 * @method PUMandateArchiveQuery groupByBeginAt() Group by the begin_at column
 * @method PUMandateArchiveQuery groupByEndAt() Group by the end_at column
 * @method PUMandateArchiveQuery groupByCreatedAt() Group by the created_at column
 * @method PUMandateArchiveQuery groupByUpdatedAt() Group by the updated_at column
 * @method PUMandateArchiveQuery groupByArchivedAt() Group by the archived_at column
 *
 * @method PUMandateArchiveQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method PUMandateArchiveQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method PUMandateArchiveQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method PUMandateArchive findOne(PropelPDO $con = null) Return the first PUMandateArchive matching the query
 * @method PUMandateArchive findOneOrCreate(PropelPDO $con = null) Return the first PUMandateArchive matching the query, or a new PUMandateArchive object populated from the query conditions when no match is found
 *
 * @method PUMandateArchive findOneByPUserId(int $p_user_id) Return the first PUMandateArchive filtered by the p_user_id column
 * @method PUMandateArchive findOneByPQTypeId(int $p_q_type_id) Return the first PUMandateArchive filtered by the p_q_type_id column
 * @method PUMandateArchive findOneByPQMandateId(int $p_q_mandate_id) Return the first PUMandateArchive filtered by the p_q_mandate_id column
 * @method PUMandateArchive findOneByPQOrganizationId(int $p_q_organization_id) Return the first PUMandateArchive filtered by the p_q_organization_id column
 * @method PUMandateArchive findOneByLocalization(string $localization) Return the first PUMandateArchive filtered by the localization column
 * @method PUMandateArchive findOneByBeginAt(string $begin_at) Return the first PUMandateArchive filtered by the begin_at column
 * @method PUMandateArchive findOneByEndAt(string $end_at) Return the first PUMandateArchive filtered by the end_at column
 * @method PUMandateArchive findOneByCreatedAt(string $created_at) Return the first PUMandateArchive filtered by the created_at column
 * @method PUMandateArchive findOneByUpdatedAt(string $updated_at) Return the first PUMandateArchive filtered by the updated_at column
 * @method PUMandateArchive findOneByArchivedAt(string $archived_at) Return the first PUMandateArchive filtered by the archived_at column
 *
 * @method array findById(int $id) Return PUMandateArchive objects filtered by the id column
 * @method array findByPUserId(int $p_user_id) Return PUMandateArchive objects filtered by the p_user_id column
 * @method array findByPQTypeId(int $p_q_type_id) Return PUMandateArchive objects filtered by the p_q_type_id column
 * @method array findByPQMandateId(int $p_q_mandate_id) Return PUMandateArchive objects filtered by the p_q_mandate_id column
 * @method array findByPQOrganizationId(int $p_q_organization_id) Return PUMandateArchive objects filtered by the p_q_organization_id column
 * @method array findByLocalization(string $localization) Return PUMandateArchive objects filtered by the localization column
 * @method array findByBeginAt(string $begin_at) Return PUMandateArchive objects filtered by the begin_at column
 * @method array findByEndAt(string $end_at) Return PUMandateArchive objects filtered by the end_at column
 * @method array findByCreatedAt(string $created_at) Return PUMandateArchive objects filtered by the created_at column
 * @method array findByUpdatedAt(string $updated_at) Return PUMandateArchive objects filtered by the updated_at column
 * @method array findByArchivedAt(string $archived_at) Return PUMandateArchive objects filtered by the archived_at column
 */
abstract class BasePUMandateArchiveQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BasePUMandateArchiveQuery object.
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
            $modelName = 'Politizr\\Model\\PUMandateArchive';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
        EventDispatcherProxy::trigger(array('construct','query.construct'), new QueryEvent($this));
    }

    /**
     * Returns a new PUMandateArchiveQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   PUMandateArchiveQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return PUMandateArchiveQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof PUMandateArchiveQuery) {
            return $criteria;
        }
        $query = new static(null, null, $modelAlias);

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
     * @return   PUMandateArchive|PUMandateArchive[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = PUMandateArchivePeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(PUMandateArchivePeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 PUMandateArchive A model object, or null if the key is not found
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
     * @return                 PUMandateArchive A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `p_user_id`, `p_q_type_id`, `p_q_mandate_id`, `p_q_organization_id`, `localization`, `begin_at`, `end_at`, `created_at`, `updated_at`, `archived_at` FROM `p_u_mandate_archive` WHERE `id` = :p0';
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
            $cls = PUMandateArchivePeer::getOMClass();
            $obj = new $cls;
            $obj->hydrate($row);
            PUMandateArchivePeer::addInstanceToPool($obj, (string) $key);
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
     * @return PUMandateArchive|PUMandateArchive[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|PUMandateArchive[]|mixed the list of results, formatted by the current formatter
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
     * @return PUMandateArchiveQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(PUMandateArchivePeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return PUMandateArchiveQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(PUMandateArchivePeer::ID, $keys, Criteria::IN);
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
     * @return PUMandateArchiveQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(PUMandateArchivePeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(PUMandateArchivePeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PUMandateArchivePeer::ID, $id, $comparison);
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
     * @return PUMandateArchiveQuery The current query, for fluid interface
     */
    public function filterByPUserId($pUserId = null, $comparison = null)
    {
        if (is_array($pUserId)) {
            $useMinMax = false;
            if (isset($pUserId['min'])) {
                $this->addUsingAlias(PUMandateArchivePeer::P_USER_ID, $pUserId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($pUserId['max'])) {
                $this->addUsingAlias(PUMandateArchivePeer::P_USER_ID, $pUserId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PUMandateArchivePeer::P_USER_ID, $pUserId, $comparison);
    }

    /**
     * Filter the query on the p_q_type_id column
     *
     * Example usage:
     * <code>
     * $query->filterByPQTypeId(1234); // WHERE p_q_type_id = 1234
     * $query->filterByPQTypeId(array(12, 34)); // WHERE p_q_type_id IN (12, 34)
     * $query->filterByPQTypeId(array('min' => 12)); // WHERE p_q_type_id >= 12
     * $query->filterByPQTypeId(array('max' => 12)); // WHERE p_q_type_id <= 12
     * </code>
     *
     * @param     mixed $pQTypeId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PUMandateArchiveQuery The current query, for fluid interface
     */
    public function filterByPQTypeId($pQTypeId = null, $comparison = null)
    {
        if (is_array($pQTypeId)) {
            $useMinMax = false;
            if (isset($pQTypeId['min'])) {
                $this->addUsingAlias(PUMandateArchivePeer::P_Q_TYPE_ID, $pQTypeId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($pQTypeId['max'])) {
                $this->addUsingAlias(PUMandateArchivePeer::P_Q_TYPE_ID, $pQTypeId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PUMandateArchivePeer::P_Q_TYPE_ID, $pQTypeId, $comparison);
    }

    /**
     * Filter the query on the p_q_mandate_id column
     *
     * Example usage:
     * <code>
     * $query->filterByPQMandateId(1234); // WHERE p_q_mandate_id = 1234
     * $query->filterByPQMandateId(array(12, 34)); // WHERE p_q_mandate_id IN (12, 34)
     * $query->filterByPQMandateId(array('min' => 12)); // WHERE p_q_mandate_id >= 12
     * $query->filterByPQMandateId(array('max' => 12)); // WHERE p_q_mandate_id <= 12
     * </code>
     *
     * @param     mixed $pQMandateId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PUMandateArchiveQuery The current query, for fluid interface
     */
    public function filterByPQMandateId($pQMandateId = null, $comparison = null)
    {
        if (is_array($pQMandateId)) {
            $useMinMax = false;
            if (isset($pQMandateId['min'])) {
                $this->addUsingAlias(PUMandateArchivePeer::P_Q_MANDATE_ID, $pQMandateId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($pQMandateId['max'])) {
                $this->addUsingAlias(PUMandateArchivePeer::P_Q_MANDATE_ID, $pQMandateId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PUMandateArchivePeer::P_Q_MANDATE_ID, $pQMandateId, $comparison);
    }

    /**
     * Filter the query on the p_q_organization_id column
     *
     * Example usage:
     * <code>
     * $query->filterByPQOrganizationId(1234); // WHERE p_q_organization_id = 1234
     * $query->filterByPQOrganizationId(array(12, 34)); // WHERE p_q_organization_id IN (12, 34)
     * $query->filterByPQOrganizationId(array('min' => 12)); // WHERE p_q_organization_id >= 12
     * $query->filterByPQOrganizationId(array('max' => 12)); // WHERE p_q_organization_id <= 12
     * </code>
     *
     * @param     mixed $pQOrganizationId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PUMandateArchiveQuery The current query, for fluid interface
     */
    public function filterByPQOrganizationId($pQOrganizationId = null, $comparison = null)
    {
        if (is_array($pQOrganizationId)) {
            $useMinMax = false;
            if (isset($pQOrganizationId['min'])) {
                $this->addUsingAlias(PUMandateArchivePeer::P_Q_ORGANIZATION_ID, $pQOrganizationId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($pQOrganizationId['max'])) {
                $this->addUsingAlias(PUMandateArchivePeer::P_Q_ORGANIZATION_ID, $pQOrganizationId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PUMandateArchivePeer::P_Q_ORGANIZATION_ID, $pQOrganizationId, $comparison);
    }

    /**
     * Filter the query on the localization column
     *
     * Example usage:
     * <code>
     * $query->filterByLocalization('fooValue');   // WHERE localization = 'fooValue'
     * $query->filterByLocalization('%fooValue%'); // WHERE localization LIKE '%fooValue%'
     * </code>
     *
     * @param     string $localization The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PUMandateArchiveQuery The current query, for fluid interface
     */
    public function filterByLocalization($localization = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($localization)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $localization)) {
                $localization = str_replace('*', '%', $localization);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(PUMandateArchivePeer::LOCALIZATION, $localization, $comparison);
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
     * @return PUMandateArchiveQuery The current query, for fluid interface
     */
    public function filterByBeginAt($beginAt = null, $comparison = null)
    {
        if (is_array($beginAt)) {
            $useMinMax = false;
            if (isset($beginAt['min'])) {
                $this->addUsingAlias(PUMandateArchivePeer::BEGIN_AT, $beginAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($beginAt['max'])) {
                $this->addUsingAlias(PUMandateArchivePeer::BEGIN_AT, $beginAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PUMandateArchivePeer::BEGIN_AT, $beginAt, $comparison);
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
     * @return PUMandateArchiveQuery The current query, for fluid interface
     */
    public function filterByEndAt($endAt = null, $comparison = null)
    {
        if (is_array($endAt)) {
            $useMinMax = false;
            if (isset($endAt['min'])) {
                $this->addUsingAlias(PUMandateArchivePeer::END_AT, $endAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($endAt['max'])) {
                $this->addUsingAlias(PUMandateArchivePeer::END_AT, $endAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PUMandateArchivePeer::END_AT, $endAt, $comparison);
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
     * @return PUMandateArchiveQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(PUMandateArchivePeer::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(PUMandateArchivePeer::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PUMandateArchivePeer::CREATED_AT, $createdAt, $comparison);
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
     * @return PUMandateArchiveQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(PUMandateArchivePeer::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(PUMandateArchivePeer::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PUMandateArchivePeer::UPDATED_AT, $updatedAt, $comparison);
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
     * @return PUMandateArchiveQuery The current query, for fluid interface
     */
    public function filterByArchivedAt($archivedAt = null, $comparison = null)
    {
        if (is_array($archivedAt)) {
            $useMinMax = false;
            if (isset($archivedAt['min'])) {
                $this->addUsingAlias(PUMandateArchivePeer::ARCHIVED_AT, $archivedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($archivedAt['max'])) {
                $this->addUsingAlias(PUMandateArchivePeer::ARCHIVED_AT, $archivedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PUMandateArchivePeer::ARCHIVED_AT, $archivedAt, $comparison);
    }

    /**
     * Exclude object from result
     *
     * @param   PUMandateArchive $pUMandateArchive Object to remove from the list of results
     *
     * @return PUMandateArchiveQuery The current query, for fluid interface
     */
    public function prune($pUMandateArchive = null)
    {
        if ($pUMandateArchive) {
            $this->addUsingAlias(PUMandateArchivePeer::ID, $pUMandateArchive->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Code to execute before every SELECT statement
     *
     * @param     PropelPDO $con The connection object used by the query
     */
    protected function basePreSelect(PropelPDO $con)
    {
        // event behavior
        EventDispatcherProxy::trigger('query.select.pre', new QueryEvent($this));

        return $this->preSelect($con);
    }

    /**
     * Code to execute before every DELETE statement
     *
     * @param     PropelPDO $con The connection object used by the query
     */
    protected function basePreDelete(PropelPDO $con)
    {
        EventDispatcherProxy::trigger(array('delete.pre','query.delete.pre'), new QueryEvent($this));
        // event behavior
        // placeholder, issue #5

        return $this->preDelete($con);
    }

    /**
     * Code to execute after every DELETE statement
     *
     * @param     int $affectedRows the number of deleted rows
     * @param     PropelPDO $con The connection object used by the query
     */
    protected function basePostDelete($affectedRows, PropelPDO $con)
    {
        // event behavior
        EventDispatcherProxy::trigger(array('delete.post','query.delete.post'), new QueryEvent($this));

        return $this->postDelete($affectedRows, $con);
    }

    /**
     * Code to execute before every UPDATE statement
     *
     * @param     array $values The associative array of columns and values for the update
     * @param     PropelPDO $con The connection object used by the query
     * @param     boolean $forceIndividualSaves If false (default), the resulting call is a BasePeer::doUpdate(), otherwise it is a series of save() calls on all the found objects
     */
    protected function basePreUpdate(&$values, PropelPDO $con, $forceIndividualSaves = false)
    {
        // event behavior
        EventDispatcherProxy::trigger(array('update.pre', 'query.update.pre'), new QueryEvent($this));

        return $this->preUpdate($values, $con, $forceIndividualSaves);
    }

    /**
     * Code to execute after every UPDATE statement
     *
     * @param     int $affectedRows the number of updated rows
     * @param     PropelPDO $con The connection object used by the query
     */
    protected function basePostUpdate($affectedRows, PropelPDO $con)
    {
        // event behavior
        EventDispatcherProxy::trigger(array('update.post', 'query.update.post'), new QueryEvent($this));

        return $this->postUpdate($affectedRows, $con);
    }

    // extend behavior
    public function setFormatter($formatter)
    {
        if (is_string($formatter) && $formatter === \ModelCriteria::FORMAT_ON_DEMAND) {
            $formatter = '\Glorpen\Propel\PropelBundle\Formatter\PropelOnDemandFormatter';
        }

        return parent::setFormatter($formatter);
    }
}
