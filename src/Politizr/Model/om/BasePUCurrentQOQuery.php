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
use Politizr\Model\PQOrganization;
use Politizr\Model\PUCurrentQO;
use Politizr\Model\PUCurrentQOPeer;
use Politizr\Model\PUCurrentQOQuery;
use Politizr\Model\PUser;

/**
 * @method PUCurrentQOQuery orderByPUserId($order = Criteria::ASC) Order by the p_user_id column
 * @method PUCurrentQOQuery orderByPQOrganizationId($order = Criteria::ASC) Order by the p_q_organization_id column
 * @method PUCurrentQOQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method PUCurrentQOQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method PUCurrentQOQuery groupByPUserId() Group by the p_user_id column
 * @method PUCurrentQOQuery groupByPQOrganizationId() Group by the p_q_organization_id column
 * @method PUCurrentQOQuery groupByCreatedAt() Group by the created_at column
 * @method PUCurrentQOQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method PUCurrentQOQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method PUCurrentQOQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method PUCurrentQOQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method PUCurrentQOQuery leftJoinPUCurrentQOPUser($relationAlias = null) Adds a LEFT JOIN clause to the query using the PUCurrentQOPUser relation
 * @method PUCurrentQOQuery rightJoinPUCurrentQOPUser($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PUCurrentQOPUser relation
 * @method PUCurrentQOQuery innerJoinPUCurrentQOPUser($relationAlias = null) Adds a INNER JOIN clause to the query using the PUCurrentQOPUser relation
 *
 * @method PUCurrentQOQuery leftJoinPUCurrentQOPQOrganization($relationAlias = null) Adds a LEFT JOIN clause to the query using the PUCurrentQOPQOrganization relation
 * @method PUCurrentQOQuery rightJoinPUCurrentQOPQOrganization($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PUCurrentQOPQOrganization relation
 * @method PUCurrentQOQuery innerJoinPUCurrentQOPQOrganization($relationAlias = null) Adds a INNER JOIN clause to the query using the PUCurrentQOPQOrganization relation
 *
 * @method PUCurrentQO findOne(PropelPDO $con = null) Return the first PUCurrentQO matching the query
 * @method PUCurrentQO findOneOrCreate(PropelPDO $con = null) Return the first PUCurrentQO matching the query, or a new PUCurrentQO object populated from the query conditions when no match is found
 *
 * @method PUCurrentQO findOneByPUserId(int $p_user_id) Return the first PUCurrentQO filtered by the p_user_id column
 * @method PUCurrentQO findOneByPQOrganizationId(int $p_q_organization_id) Return the first PUCurrentQO filtered by the p_q_organization_id column
 * @method PUCurrentQO findOneByCreatedAt(string $created_at) Return the first PUCurrentQO filtered by the created_at column
 * @method PUCurrentQO findOneByUpdatedAt(string $updated_at) Return the first PUCurrentQO filtered by the updated_at column
 *
 * @method array findByPUserId(int $p_user_id) Return PUCurrentQO objects filtered by the p_user_id column
 * @method array findByPQOrganizationId(int $p_q_organization_id) Return PUCurrentQO objects filtered by the p_q_organization_id column
 * @method array findByCreatedAt(string $created_at) Return PUCurrentQO objects filtered by the created_at column
 * @method array findByUpdatedAt(string $updated_at) Return PUCurrentQO objects filtered by the updated_at column
 */
abstract class BasePUCurrentQOQuery extends ModelCriteria
{
    // query_cache behavior
    protected $queryKey = '';

    /**
     * Initializes internal state of BasePUCurrentQOQuery object.
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
            $modelName = 'Politizr\\Model\\PUCurrentQO';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new PUCurrentQOQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   PUCurrentQOQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return PUCurrentQOQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof PUCurrentQOQuery) {
            return $criteria;
        }
        $query = new PUCurrentQOQuery(null, null, $modelAlias);

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
     * $obj = $c->findPk(array(12, 34), $con);
     * </code>
     *
     * @param array $key Primary key to use for the query
                         A Primary key composition: [$p_user_id, $p_q_organization_id]
     * @param     PropelPDO $con an optional connection object
     *
     * @return   PUCurrentQO|PUCurrentQO[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = PUCurrentQOPeer::getInstanceFromPool(serialize(array((string) $key[0], (string) $key[1]))))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(PUCurrentQOPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * Find object by primary key using raw SQL to go fast.
     * Bypass doSelect() and the object formatter by using generated code.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     PropelPDO $con A connection object
     *
     * @return                 PUCurrentQO A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `p_user_id`, `p_q_organization_id`, `created_at`, `updated_at` FROM `p_u_current_q_o` WHERE `p_user_id` = :p0 AND `p_q_organization_id` = :p1';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key[0], PDO::PARAM_INT);
            $stmt->bindValue(':p1', $key[1], PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $obj = new PUCurrentQO();
            $obj->hydrate($row);
            PUCurrentQOPeer::addInstanceToPool($obj, serialize(array((string) $key[0], (string) $key[1])));
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
     * @return PUCurrentQO|PUCurrentQO[]|mixed the result, formatted by the current formatter
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
     * $objs = $c->findPks(array(array(12, 56), array(832, 123), array(123, 456)), $con);
     * </code>
     * @param     array $keys Primary keys to use for the query
     * @param     PropelPDO $con an optional connection object
     *
     * @return PropelObjectCollection|PUCurrentQO[]|mixed the list of results, formatted by the current formatter
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
     * @return PUCurrentQOQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {
        $this->addUsingAlias(PUCurrentQOPeer::P_USER_ID, $key[0], Criteria::EQUAL);
        $this->addUsingAlias(PUCurrentQOPeer::P_Q_ORGANIZATION_ID, $key[1], Criteria::EQUAL);

        return $this;
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return PUCurrentQOQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {
        if (empty($keys)) {
            return $this->add(null, '1<>1', Criteria::CUSTOM);
        }
        foreach ($keys as $key) {
            $cton0 = $this->getNewCriterion(PUCurrentQOPeer::P_USER_ID, $key[0], Criteria::EQUAL);
            $cton1 = $this->getNewCriterion(PUCurrentQOPeer::P_Q_ORGANIZATION_ID, $key[1], Criteria::EQUAL);
            $cton0->addAnd($cton1);
            $this->addOr($cton0);
        }

        return $this;
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
     * @see       filterByPUCurrentQOPUser()
     *
     * @param     mixed $pUserId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PUCurrentQOQuery The current query, for fluid interface
     */
    public function filterByPUserId($pUserId = null, $comparison = null)
    {
        if (is_array($pUserId)) {
            $useMinMax = false;
            if (isset($pUserId['min'])) {
                $this->addUsingAlias(PUCurrentQOPeer::P_USER_ID, $pUserId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($pUserId['max'])) {
                $this->addUsingAlias(PUCurrentQOPeer::P_USER_ID, $pUserId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PUCurrentQOPeer::P_USER_ID, $pUserId, $comparison);
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
     * @see       filterByPUCurrentQOPQOrganization()
     *
     * @param     mixed $pQOrganizationId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PUCurrentQOQuery The current query, for fluid interface
     */
    public function filterByPQOrganizationId($pQOrganizationId = null, $comparison = null)
    {
        if (is_array($pQOrganizationId)) {
            $useMinMax = false;
            if (isset($pQOrganizationId['min'])) {
                $this->addUsingAlias(PUCurrentQOPeer::P_Q_ORGANIZATION_ID, $pQOrganizationId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($pQOrganizationId['max'])) {
                $this->addUsingAlias(PUCurrentQOPeer::P_Q_ORGANIZATION_ID, $pQOrganizationId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PUCurrentQOPeer::P_Q_ORGANIZATION_ID, $pQOrganizationId, $comparison);
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
     * @return PUCurrentQOQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(PUCurrentQOPeer::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(PUCurrentQOPeer::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PUCurrentQOPeer::CREATED_AT, $createdAt, $comparison);
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
     * @return PUCurrentQOQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(PUCurrentQOPeer::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(PUCurrentQOPeer::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PUCurrentQOPeer::UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query by a related PUser object
     *
     * @param   PUser|PropelObjectCollection $pUser The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PUCurrentQOQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPUCurrentQOPUser($pUser, $comparison = null)
    {
        if ($pUser instanceof PUser) {
            return $this
                ->addUsingAlias(PUCurrentQOPeer::P_USER_ID, $pUser->getId(), $comparison);
        } elseif ($pUser instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PUCurrentQOPeer::P_USER_ID, $pUser->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByPUCurrentQOPUser() only accepts arguments of type PUser or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PUCurrentQOPUser relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PUCurrentQOQuery The current query, for fluid interface
     */
    public function joinPUCurrentQOPUser($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PUCurrentQOPUser');

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
            $this->addJoinObject($join, 'PUCurrentQOPUser');
        }

        return $this;
    }

    /**
     * Use the PUCurrentQOPUser relation PUser object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Politizr\Model\PUserQuery A secondary query class using the current class as primary query
     */
    public function usePUCurrentQOPUserQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPUCurrentQOPUser($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PUCurrentQOPUser', '\Politizr\Model\PUserQuery');
    }

    /**
     * Filter the query by a related PQOrganization object
     *
     * @param   PQOrganization|PropelObjectCollection $pQOrganization The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PUCurrentQOQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPUCurrentQOPQOrganization($pQOrganization, $comparison = null)
    {
        if ($pQOrganization instanceof PQOrganization) {
            return $this
                ->addUsingAlias(PUCurrentQOPeer::P_Q_ORGANIZATION_ID, $pQOrganization->getId(), $comparison);
        } elseif ($pQOrganization instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PUCurrentQOPeer::P_Q_ORGANIZATION_ID, $pQOrganization->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByPUCurrentQOPQOrganization() only accepts arguments of type PQOrganization or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PUCurrentQOPQOrganization relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PUCurrentQOQuery The current query, for fluid interface
     */
    public function joinPUCurrentQOPQOrganization($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PUCurrentQOPQOrganization');

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
            $this->addJoinObject($join, 'PUCurrentQOPQOrganization');
        }

        return $this;
    }

    /**
     * Use the PUCurrentQOPQOrganization relation PQOrganization object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Politizr\Model\PQOrganizationQuery A secondary query class using the current class as primary query
     */
    public function usePUCurrentQOPQOrganizationQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPUCurrentQOPQOrganization($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PUCurrentQOPQOrganization', '\Politizr\Model\PQOrganizationQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   PUCurrentQO $pUCurrentQO Object to remove from the list of results
     *
     * @return PUCurrentQOQuery The current query, for fluid interface
     */
    public function prune($pUCurrentQO = null)
    {
        if ($pUCurrentQO) {
            $this->addCond('pruneCond0', $this->getAliasedColName(PUCurrentQOPeer::P_USER_ID), $pUCurrentQO->getPUserId(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond1', $this->getAliasedColName(PUCurrentQOPeer::P_Q_ORGANIZATION_ID), $pUCurrentQO->getPQOrganizationId(), Criteria::NOT_EQUAL);
            $this->combine(array('pruneCond0', 'pruneCond1'), Criteria::LOGICAL_OR);
        }

        return $this;
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     PUCurrentQOQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(PUCurrentQOPeer::UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     PUCurrentQOQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(PUCurrentQOPeer::UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     PUCurrentQOQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(PUCurrentQOPeer::UPDATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     PUCurrentQOQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(PUCurrentQOPeer::CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date desc
     *
     * @return     PUCurrentQOQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(PUCurrentQOPeer::CREATED_AT);
    }

    /**
     * Order by create date asc
     *
     * @return     PUCurrentQOQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(PUCurrentQOPeer::CREATED_AT);
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

        $dbMap = Propel::getDatabaseMap(PUCurrentQOPeer::DATABASE_NAME);
        $db = Propel::getDB(PUCurrentQOPeer::DATABASE_NAME);

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

}
