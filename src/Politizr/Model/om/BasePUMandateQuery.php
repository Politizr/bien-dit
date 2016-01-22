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
use Politizr\Model\PQMandate;
use Politizr\Model\PQOrganization;
use Politizr\Model\PQType;
use Politizr\Model\PUMandate;
use Politizr\Model\PUMandatePeer;
use Politizr\Model\PUMandateQuery;
use Politizr\Model\PUser;

/**
 * @method PUMandateQuery orderById($order = Criteria::ASC) Order by the id column
 * @method PUMandateQuery orderByUuid($order = Criteria::ASC) Order by the uuid column
 * @method PUMandateQuery orderByPUserId($order = Criteria::ASC) Order by the p_user_id column
 * @method PUMandateQuery orderByPQTypeId($order = Criteria::ASC) Order by the p_q_type_id column
 * @method PUMandateQuery orderByPQMandateId($order = Criteria::ASC) Order by the p_q_mandate_id column
 * @method PUMandateQuery orderByPQOrganizationId($order = Criteria::ASC) Order by the p_q_organization_id column
 * @method PUMandateQuery orderByLocalization($order = Criteria::ASC) Order by the localization column
 * @method PUMandateQuery orderByBeginAt($order = Criteria::ASC) Order by the begin_at column
 * @method PUMandateQuery orderByEndAt($order = Criteria::ASC) Order by the end_at column
 * @method PUMandateQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method PUMandateQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method PUMandateQuery groupById() Group by the id column
 * @method PUMandateQuery groupByUuid() Group by the uuid column
 * @method PUMandateQuery groupByPUserId() Group by the p_user_id column
 * @method PUMandateQuery groupByPQTypeId() Group by the p_q_type_id column
 * @method PUMandateQuery groupByPQMandateId() Group by the p_q_mandate_id column
 * @method PUMandateQuery groupByPQOrganizationId() Group by the p_q_organization_id column
 * @method PUMandateQuery groupByLocalization() Group by the localization column
 * @method PUMandateQuery groupByBeginAt() Group by the begin_at column
 * @method PUMandateQuery groupByEndAt() Group by the end_at column
 * @method PUMandateQuery groupByCreatedAt() Group by the created_at column
 * @method PUMandateQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method PUMandateQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method PUMandateQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method PUMandateQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method PUMandateQuery leftJoinPUser($relationAlias = null) Adds a LEFT JOIN clause to the query using the PUser relation
 * @method PUMandateQuery rightJoinPUser($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PUser relation
 * @method PUMandateQuery innerJoinPUser($relationAlias = null) Adds a INNER JOIN clause to the query using the PUser relation
 *
 * @method PUMandateQuery leftJoinPQType($relationAlias = null) Adds a LEFT JOIN clause to the query using the PQType relation
 * @method PUMandateQuery rightJoinPQType($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PQType relation
 * @method PUMandateQuery innerJoinPQType($relationAlias = null) Adds a INNER JOIN clause to the query using the PQType relation
 *
 * @method PUMandateQuery leftJoinPQMandate($relationAlias = null) Adds a LEFT JOIN clause to the query using the PQMandate relation
 * @method PUMandateQuery rightJoinPQMandate($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PQMandate relation
 * @method PUMandateQuery innerJoinPQMandate($relationAlias = null) Adds a INNER JOIN clause to the query using the PQMandate relation
 *
 * @method PUMandateQuery leftJoinPQOrganization($relationAlias = null) Adds a LEFT JOIN clause to the query using the PQOrganization relation
 * @method PUMandateQuery rightJoinPQOrganization($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PQOrganization relation
 * @method PUMandateQuery innerJoinPQOrganization($relationAlias = null) Adds a INNER JOIN clause to the query using the PQOrganization relation
 *
 * @method PUMandate findOne(PropelPDO $con = null) Return the first PUMandate matching the query
 * @method PUMandate findOneOrCreate(PropelPDO $con = null) Return the first PUMandate matching the query, or a new PUMandate object populated from the query conditions when no match is found
 *
 * @method PUMandate findOneByUuid(string $uuid) Return the first PUMandate filtered by the uuid column
 * @method PUMandate findOneByPUserId(int $p_user_id) Return the first PUMandate filtered by the p_user_id column
 * @method PUMandate findOneByPQTypeId(int $p_q_type_id) Return the first PUMandate filtered by the p_q_type_id column
 * @method PUMandate findOneByPQMandateId(int $p_q_mandate_id) Return the first PUMandate filtered by the p_q_mandate_id column
 * @method PUMandate findOneByPQOrganizationId(int $p_q_organization_id) Return the first PUMandate filtered by the p_q_organization_id column
 * @method PUMandate findOneByLocalization(string $localization) Return the first PUMandate filtered by the localization column
 * @method PUMandate findOneByBeginAt(string $begin_at) Return the first PUMandate filtered by the begin_at column
 * @method PUMandate findOneByEndAt(string $end_at) Return the first PUMandate filtered by the end_at column
 * @method PUMandate findOneByCreatedAt(string $created_at) Return the first PUMandate filtered by the created_at column
 * @method PUMandate findOneByUpdatedAt(string $updated_at) Return the first PUMandate filtered by the updated_at column
 *
 * @method array findById(int $id) Return PUMandate objects filtered by the id column
 * @method array findByUuid(string $uuid) Return PUMandate objects filtered by the uuid column
 * @method array findByPUserId(int $p_user_id) Return PUMandate objects filtered by the p_user_id column
 * @method array findByPQTypeId(int $p_q_type_id) Return PUMandate objects filtered by the p_q_type_id column
 * @method array findByPQMandateId(int $p_q_mandate_id) Return PUMandate objects filtered by the p_q_mandate_id column
 * @method array findByPQOrganizationId(int $p_q_organization_id) Return PUMandate objects filtered by the p_q_organization_id column
 * @method array findByLocalization(string $localization) Return PUMandate objects filtered by the localization column
 * @method array findByBeginAt(string $begin_at) Return PUMandate objects filtered by the begin_at column
 * @method array findByEndAt(string $end_at) Return PUMandate objects filtered by the end_at column
 * @method array findByCreatedAt(string $created_at) Return PUMandate objects filtered by the created_at column
 * @method array findByUpdatedAt(string $updated_at) Return PUMandate objects filtered by the updated_at column
 */
abstract class BasePUMandateQuery extends ModelCriteria
{
    // query_cache behavior
    protected $queryKey = '';

    // archivable behavior
    protected $archiveOnDelete = true;

    /**
     * Initializes internal state of BasePUMandateQuery object.
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
            $modelName = 'Politizr\\Model\\PUMandate';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new PUMandateQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   PUMandateQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return PUMandateQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof PUMandateQuery) {
            return $criteria;
        }
        $query = new PUMandateQuery(null, null, $modelAlias);

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
     * @return   PUMandate|PUMandate[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = PUMandatePeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(PUMandatePeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 PUMandate A model object, or null if the key is not found
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
     * @return                 PUMandate A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `uuid`, `p_user_id`, `p_q_type_id`, `p_q_mandate_id`, `p_q_organization_id`, `localization`, `begin_at`, `end_at`, `created_at`, `updated_at` FROM `p_u_mandate` WHERE `id` = :p0';
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
            $obj = new PUMandate();
            $obj->hydrate($row);
            PUMandatePeer::addInstanceToPool($obj, (string) $key);
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
     * @return PUMandate|PUMandate[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|PUMandate[]|mixed the list of results, formatted by the current formatter
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
     * @return PUMandateQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(PUMandatePeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return PUMandateQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(PUMandatePeer::ID, $keys, Criteria::IN);
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
     * @return PUMandateQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(PUMandatePeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(PUMandatePeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PUMandatePeer::ID, $id, $comparison);
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
     * @return PUMandateQuery The current query, for fluid interface
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

        return $this->addUsingAlias(PUMandatePeer::UUID, $uuid, $comparison);
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
     * @return PUMandateQuery The current query, for fluid interface
     */
    public function filterByPUserId($pUserId = null, $comparison = null)
    {
        if (is_array($pUserId)) {
            $useMinMax = false;
            if (isset($pUserId['min'])) {
                $this->addUsingAlias(PUMandatePeer::P_USER_ID, $pUserId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($pUserId['max'])) {
                $this->addUsingAlias(PUMandatePeer::P_USER_ID, $pUserId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PUMandatePeer::P_USER_ID, $pUserId, $comparison);
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
     * @see       filterByPQType()
     *
     * @param     mixed $pQTypeId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PUMandateQuery The current query, for fluid interface
     */
    public function filterByPQTypeId($pQTypeId = null, $comparison = null)
    {
        if (is_array($pQTypeId)) {
            $useMinMax = false;
            if (isset($pQTypeId['min'])) {
                $this->addUsingAlias(PUMandatePeer::P_Q_TYPE_ID, $pQTypeId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($pQTypeId['max'])) {
                $this->addUsingAlias(PUMandatePeer::P_Q_TYPE_ID, $pQTypeId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PUMandatePeer::P_Q_TYPE_ID, $pQTypeId, $comparison);
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
     * @see       filterByPQMandate()
     *
     * @param     mixed $pQMandateId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PUMandateQuery The current query, for fluid interface
     */
    public function filterByPQMandateId($pQMandateId = null, $comparison = null)
    {
        if (is_array($pQMandateId)) {
            $useMinMax = false;
            if (isset($pQMandateId['min'])) {
                $this->addUsingAlias(PUMandatePeer::P_Q_MANDATE_ID, $pQMandateId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($pQMandateId['max'])) {
                $this->addUsingAlias(PUMandatePeer::P_Q_MANDATE_ID, $pQMandateId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PUMandatePeer::P_Q_MANDATE_ID, $pQMandateId, $comparison);
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
     * @see       filterByPQOrganization()
     *
     * @param     mixed $pQOrganizationId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PUMandateQuery The current query, for fluid interface
     */
    public function filterByPQOrganizationId($pQOrganizationId = null, $comparison = null)
    {
        if (is_array($pQOrganizationId)) {
            $useMinMax = false;
            if (isset($pQOrganizationId['min'])) {
                $this->addUsingAlias(PUMandatePeer::P_Q_ORGANIZATION_ID, $pQOrganizationId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($pQOrganizationId['max'])) {
                $this->addUsingAlias(PUMandatePeer::P_Q_ORGANIZATION_ID, $pQOrganizationId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PUMandatePeer::P_Q_ORGANIZATION_ID, $pQOrganizationId, $comparison);
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
     * @return PUMandateQuery The current query, for fluid interface
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

        return $this->addUsingAlias(PUMandatePeer::LOCALIZATION, $localization, $comparison);
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
     * @return PUMandateQuery The current query, for fluid interface
     */
    public function filterByBeginAt($beginAt = null, $comparison = null)
    {
        if (is_array($beginAt)) {
            $useMinMax = false;
            if (isset($beginAt['min'])) {
                $this->addUsingAlias(PUMandatePeer::BEGIN_AT, $beginAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($beginAt['max'])) {
                $this->addUsingAlias(PUMandatePeer::BEGIN_AT, $beginAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PUMandatePeer::BEGIN_AT, $beginAt, $comparison);
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
     * @return PUMandateQuery The current query, for fluid interface
     */
    public function filterByEndAt($endAt = null, $comparison = null)
    {
        if (is_array($endAt)) {
            $useMinMax = false;
            if (isset($endAt['min'])) {
                $this->addUsingAlias(PUMandatePeer::END_AT, $endAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($endAt['max'])) {
                $this->addUsingAlias(PUMandatePeer::END_AT, $endAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PUMandatePeer::END_AT, $endAt, $comparison);
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
     * @return PUMandateQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(PUMandatePeer::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(PUMandatePeer::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PUMandatePeer::CREATED_AT, $createdAt, $comparison);
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
     * @return PUMandateQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(PUMandatePeer::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(PUMandatePeer::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PUMandatePeer::UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query by a related PUser object
     *
     * @param   PUser|PropelObjectCollection $pUser The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PUMandateQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPUser($pUser, $comparison = null)
    {
        if ($pUser instanceof PUser) {
            return $this
                ->addUsingAlias(PUMandatePeer::P_USER_ID, $pUser->getId(), $comparison);
        } elseif ($pUser instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PUMandatePeer::P_USER_ID, $pUser->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return PUMandateQuery The current query, for fluid interface
     */
    public function joinPUser($relationAlias = null, $joinType = Criteria::INNER_JOIN)
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
    public function usePUserQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPUser($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PUser', '\Politizr\Model\PUserQuery');
    }

    /**
     * Filter the query by a related PQType object
     *
     * @param   PQType|PropelObjectCollection $pQType The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PUMandateQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPQType($pQType, $comparison = null)
    {
        if ($pQType instanceof PQType) {
            return $this
                ->addUsingAlias(PUMandatePeer::P_Q_TYPE_ID, $pQType->getId(), $comparison);
        } elseif ($pQType instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PUMandatePeer::P_Q_TYPE_ID, $pQType->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByPQType() only accepts arguments of type PQType or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PQType relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PUMandateQuery The current query, for fluid interface
     */
    public function joinPQType($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PQType');

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
            $this->addJoinObject($join, 'PQType');
        }

        return $this;
    }

    /**
     * Use the PQType relation PQType object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Politizr\Model\PQTypeQuery A secondary query class using the current class as primary query
     */
    public function usePQTypeQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPQType($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PQType', '\Politizr\Model\PQTypeQuery');
    }

    /**
     * Filter the query by a related PQMandate object
     *
     * @param   PQMandate|PropelObjectCollection $pQMandate The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PUMandateQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPQMandate($pQMandate, $comparison = null)
    {
        if ($pQMandate instanceof PQMandate) {
            return $this
                ->addUsingAlias(PUMandatePeer::P_Q_MANDATE_ID, $pQMandate->getId(), $comparison);
        } elseif ($pQMandate instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PUMandatePeer::P_Q_MANDATE_ID, $pQMandate->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByPQMandate() only accepts arguments of type PQMandate or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PQMandate relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PUMandateQuery The current query, for fluid interface
     */
    public function joinPQMandate($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PQMandate');

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
            $this->addJoinObject($join, 'PQMandate');
        }

        return $this;
    }

    /**
     * Use the PQMandate relation PQMandate object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Politizr\Model\PQMandateQuery A secondary query class using the current class as primary query
     */
    public function usePQMandateQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPQMandate($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PQMandate', '\Politizr\Model\PQMandateQuery');
    }

    /**
     * Filter the query by a related PQOrganization object
     *
     * @param   PQOrganization|PropelObjectCollection $pQOrganization The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PUMandateQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPQOrganization($pQOrganization, $comparison = null)
    {
        if ($pQOrganization instanceof PQOrganization) {
            return $this
                ->addUsingAlias(PUMandatePeer::P_Q_ORGANIZATION_ID, $pQOrganization->getId(), $comparison);
        } elseif ($pQOrganization instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PUMandatePeer::P_Q_ORGANIZATION_ID, $pQOrganization->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByPQOrganization() only accepts arguments of type PQOrganization or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PQOrganization relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PUMandateQuery The current query, for fluid interface
     */
    public function joinPQOrganization($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PQOrganization');

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
            $this->addJoinObject($join, 'PQOrganization');
        }

        return $this;
    }

    /**
     * Use the PQOrganization relation PQOrganization object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Politizr\Model\PQOrganizationQuery A secondary query class using the current class as primary query
     */
    public function usePQOrganizationQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinPQOrganization($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PQOrganization', '\Politizr\Model\PQOrganizationQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   PUMandate $pUMandate Object to remove from the list of results
     *
     * @return PUMandateQuery The current query, for fluid interface
     */
    public function prune($pUMandate = null)
    {
        if ($pUMandate) {
            $this->addUsingAlias(PUMandatePeer::ID, $pUMandate->getId(), Criteria::NOT_EQUAL);
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
     * @return     PUMandateQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(PUMandatePeer::UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     PUMandateQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(PUMandatePeer::UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     PUMandateQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(PUMandatePeer::UPDATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     PUMandateQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(PUMandatePeer::CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date desc
     *
     * @return     PUMandateQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(PUMandatePeer::CREATED_AT);
    }

    /**
     * Order by create date asc
     *
     * @return     PUMandateQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(PUMandatePeer::CREATED_AT);
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

        $dbMap = Propel::getDatabaseMap(PUMandatePeer::DATABASE_NAME);
        $db = Propel::getDB(PUMandatePeer::DATABASE_NAME);

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

    // archivable behavior

    /**
     * Copy the data of the objects satisfying the query into PUMandateArchive archive objects.
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
            $con = Propel::getConnection(PUMandatePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
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
