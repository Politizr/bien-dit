<?php

namespace Politizr\Model\om;

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
use Politizr\Model\PNEmail;
use Politizr\Model\PUSubscribePNE;
use Politizr\Model\PUSubscribePNEPeer;
use Politizr\Model\PUSubscribePNEQuery;
use Politizr\Model\PUser;

/**
 * @method PUSubscribePNEQuery orderByPUserId($order = Criteria::ASC) Order by the p_user_id column
 * @method PUSubscribePNEQuery orderByPNEmailId($order = Criteria::ASC) Order by the p_n_email_id column
 * @method PUSubscribePNEQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method PUSubscribePNEQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method PUSubscribePNEQuery groupByPUserId() Group by the p_user_id column
 * @method PUSubscribePNEQuery groupByPNEmailId() Group by the p_n_email_id column
 * @method PUSubscribePNEQuery groupByCreatedAt() Group by the created_at column
 * @method PUSubscribePNEQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method PUSubscribePNEQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method PUSubscribePNEQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method PUSubscribePNEQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method PUSubscribePNEQuery leftJoinPUser($relationAlias = null) Adds a LEFT JOIN clause to the query using the PUser relation
 * @method PUSubscribePNEQuery rightJoinPUser($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PUser relation
 * @method PUSubscribePNEQuery innerJoinPUser($relationAlias = null) Adds a INNER JOIN clause to the query using the PUser relation
 *
 * @method PUSubscribePNEQuery leftJoinPNEmail($relationAlias = null) Adds a LEFT JOIN clause to the query using the PNEmail relation
 * @method PUSubscribePNEQuery rightJoinPNEmail($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PNEmail relation
 * @method PUSubscribePNEQuery innerJoinPNEmail($relationAlias = null) Adds a INNER JOIN clause to the query using the PNEmail relation
 *
 * @method PUSubscribePNE findOne(PropelPDO $con = null) Return the first PUSubscribePNE matching the query
 * @method PUSubscribePNE findOneOrCreate(PropelPDO $con = null) Return the first PUSubscribePNE matching the query, or a new PUSubscribePNE object populated from the query conditions when no match is found
 *
 * @method PUSubscribePNE findOneByPUserId(int $p_user_id) Return the first PUSubscribePNE filtered by the p_user_id column
 * @method PUSubscribePNE findOneByPNEmailId(int $p_n_email_id) Return the first PUSubscribePNE filtered by the p_n_email_id column
 * @method PUSubscribePNE findOneByCreatedAt(string $created_at) Return the first PUSubscribePNE filtered by the created_at column
 * @method PUSubscribePNE findOneByUpdatedAt(string $updated_at) Return the first PUSubscribePNE filtered by the updated_at column
 *
 * @method array findByPUserId(int $p_user_id) Return PUSubscribePNE objects filtered by the p_user_id column
 * @method array findByPNEmailId(int $p_n_email_id) Return PUSubscribePNE objects filtered by the p_n_email_id column
 * @method array findByCreatedAt(string $created_at) Return PUSubscribePNE objects filtered by the created_at column
 * @method array findByUpdatedAt(string $updated_at) Return PUSubscribePNE objects filtered by the updated_at column
 */
abstract class BasePUSubscribePNEQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BasePUSubscribePNEQuery object.
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
            $modelName = 'Politizr\\Model\\PUSubscribePNE';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new PUSubscribePNEQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   PUSubscribePNEQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return PUSubscribePNEQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof PUSubscribePNEQuery) {
            return $criteria;
        }
        $query = new PUSubscribePNEQuery(null, null, $modelAlias);

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
                         A Primary key composition: [$p_user_id, $p_n_email_id]
     * @param     PropelPDO $con an optional connection object
     *
     * @return   PUSubscribePNE|PUSubscribePNE[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = PUSubscribePNEPeer::getInstanceFromPool(serialize(array((string) $key[0], (string) $key[1]))))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(PUSubscribePNEPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 PUSubscribePNE A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `p_user_id`, `p_n_email_id`, `created_at`, `updated_at` FROM `p_u_subscribe_p_n_e` WHERE `p_user_id` = :p0 AND `p_n_email_id` = :p1';
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
            $obj = new PUSubscribePNE();
            $obj->hydrate($row);
            PUSubscribePNEPeer::addInstanceToPool($obj, serialize(array((string) $key[0], (string) $key[1])));
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
     * @return PUSubscribePNE|PUSubscribePNE[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|PUSubscribePNE[]|mixed the list of results, formatted by the current formatter
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
     * @return PUSubscribePNEQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {
        $this->addUsingAlias(PUSubscribePNEPeer::P_USER_ID, $key[0], Criteria::EQUAL);
        $this->addUsingAlias(PUSubscribePNEPeer::P_N_EMAIL_ID, $key[1], Criteria::EQUAL);

        return $this;
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return PUSubscribePNEQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {
        if (empty($keys)) {
            return $this->add(null, '1<>1', Criteria::CUSTOM);
        }
        foreach ($keys as $key) {
            $cton0 = $this->getNewCriterion(PUSubscribePNEPeer::P_USER_ID, $key[0], Criteria::EQUAL);
            $cton1 = $this->getNewCriterion(PUSubscribePNEPeer::P_N_EMAIL_ID, $key[1], Criteria::EQUAL);
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
     * @see       filterByPUser()
     *
     * @param     mixed $pUserId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PUSubscribePNEQuery The current query, for fluid interface
     */
    public function filterByPUserId($pUserId = null, $comparison = null)
    {
        if (is_array($pUserId)) {
            $useMinMax = false;
            if (isset($pUserId['min'])) {
                $this->addUsingAlias(PUSubscribePNEPeer::P_USER_ID, $pUserId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($pUserId['max'])) {
                $this->addUsingAlias(PUSubscribePNEPeer::P_USER_ID, $pUserId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PUSubscribePNEPeer::P_USER_ID, $pUserId, $comparison);
    }

    /**
     * Filter the query on the p_n_email_id column
     *
     * Example usage:
     * <code>
     * $query->filterByPNEmailId(1234); // WHERE p_n_email_id = 1234
     * $query->filterByPNEmailId(array(12, 34)); // WHERE p_n_email_id IN (12, 34)
     * $query->filterByPNEmailId(array('min' => 12)); // WHERE p_n_email_id >= 12
     * $query->filterByPNEmailId(array('max' => 12)); // WHERE p_n_email_id <= 12
     * </code>
     *
     * @see       filterByPNEmail()
     *
     * @param     mixed $pNEmailId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PUSubscribePNEQuery The current query, for fluid interface
     */
    public function filterByPNEmailId($pNEmailId = null, $comparison = null)
    {
        if (is_array($pNEmailId)) {
            $useMinMax = false;
            if (isset($pNEmailId['min'])) {
                $this->addUsingAlias(PUSubscribePNEPeer::P_N_EMAIL_ID, $pNEmailId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($pNEmailId['max'])) {
                $this->addUsingAlias(PUSubscribePNEPeer::P_N_EMAIL_ID, $pNEmailId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PUSubscribePNEPeer::P_N_EMAIL_ID, $pNEmailId, $comparison);
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
     * @return PUSubscribePNEQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(PUSubscribePNEPeer::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(PUSubscribePNEPeer::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PUSubscribePNEPeer::CREATED_AT, $createdAt, $comparison);
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
     * @return PUSubscribePNEQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(PUSubscribePNEPeer::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(PUSubscribePNEPeer::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PUSubscribePNEPeer::UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query by a related PUser object
     *
     * @param   PUser|PropelObjectCollection $pUser The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PUSubscribePNEQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPUser($pUser, $comparison = null)
    {
        if ($pUser instanceof PUser) {
            return $this
                ->addUsingAlias(PUSubscribePNEPeer::P_USER_ID, $pUser->getId(), $comparison);
        } elseif ($pUser instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PUSubscribePNEPeer::P_USER_ID, $pUser->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return PUSubscribePNEQuery The current query, for fluid interface
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
     * Filter the query by a related PNEmail object
     *
     * @param   PNEmail|PropelObjectCollection $pNEmail The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PUSubscribePNEQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPNEmail($pNEmail, $comparison = null)
    {
        if ($pNEmail instanceof PNEmail) {
            return $this
                ->addUsingAlias(PUSubscribePNEPeer::P_N_EMAIL_ID, $pNEmail->getId(), $comparison);
        } elseif ($pNEmail instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PUSubscribePNEPeer::P_N_EMAIL_ID, $pNEmail->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByPNEmail() only accepts arguments of type PNEmail or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PNEmail relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PUSubscribePNEQuery The current query, for fluid interface
     */
    public function joinPNEmail($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PNEmail');

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
            $this->addJoinObject($join, 'PNEmail');
        }

        return $this;
    }

    /**
     * Use the PNEmail relation PNEmail object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Politizr\Model\PNEmailQuery A secondary query class using the current class as primary query
     */
    public function usePNEmailQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPNEmail($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PNEmail', '\Politizr\Model\PNEmailQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   PUSubscribePNE $pUSubscribePNE Object to remove from the list of results
     *
     * @return PUSubscribePNEQuery The current query, for fluid interface
     */
    public function prune($pUSubscribePNE = null)
    {
        if ($pUSubscribePNE) {
            $this->addCond('pruneCond0', $this->getAliasedColName(PUSubscribePNEPeer::P_USER_ID), $pUSubscribePNE->getPUserId(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond1', $this->getAliasedColName(PUSubscribePNEPeer::P_N_EMAIL_ID), $pUSubscribePNE->getPNEmailId(), Criteria::NOT_EQUAL);
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
     * @return     PUSubscribePNEQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(PUSubscribePNEPeer::UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     PUSubscribePNEQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(PUSubscribePNEPeer::UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     PUSubscribePNEQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(PUSubscribePNEPeer::UPDATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     PUSubscribePNEQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(PUSubscribePNEPeer::CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date desc
     *
     * @return     PUSubscribePNEQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(PUSubscribePNEPeer::CREATED_AT);
    }

    /**
     * Order by create date asc
     *
     * @return     PUSubscribePNEQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(PUSubscribePNEPeer::CREATED_AT);
    }
}
