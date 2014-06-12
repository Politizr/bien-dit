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
use Politizr\Model\PRBadge;
use Politizr\Model\PUReputationRB;
use Politizr\Model\PUReputationRBPeer;
use Politizr\Model\PUReputationRBQuery;
use Politizr\Model\PUser;

/**
 * @method PUReputationRBQuery orderById($order = Criteria::ASC) Order by the id column
 * @method PUReputationRBQuery orderByPUserId($order = Criteria::ASC) Order by the p_user_id column
 * @method PUReputationRBQuery orderByPRBadgeId($order = Criteria::ASC) Order by the p_r_badge_id column
 * @method PUReputationRBQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method PUReputationRBQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method PUReputationRBQuery groupById() Group by the id column
 * @method PUReputationRBQuery groupByPUserId() Group by the p_user_id column
 * @method PUReputationRBQuery groupByPRBadgeId() Group by the p_r_badge_id column
 * @method PUReputationRBQuery groupByCreatedAt() Group by the created_at column
 * @method PUReputationRBQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method PUReputationRBQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method PUReputationRBQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method PUReputationRBQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method PUReputationRBQuery leftJoinPuReputationRbPUser($relationAlias = null) Adds a LEFT JOIN clause to the query using the PuReputationRbPUser relation
 * @method PUReputationRBQuery rightJoinPuReputationRbPUser($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PuReputationRbPUser relation
 * @method PUReputationRBQuery innerJoinPuReputationRbPUser($relationAlias = null) Adds a INNER JOIN clause to the query using the PuReputationRbPUser relation
 *
 * @method PUReputationRBQuery leftJoinPuReputationRbPRBadge($relationAlias = null) Adds a LEFT JOIN clause to the query using the PuReputationRbPRBadge relation
 * @method PUReputationRBQuery rightJoinPuReputationRbPRBadge($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PuReputationRbPRBadge relation
 * @method PUReputationRBQuery innerJoinPuReputationRbPRBadge($relationAlias = null) Adds a INNER JOIN clause to the query using the PuReputationRbPRBadge relation
 *
 * @method PUReputationRB findOne(PropelPDO $con = null) Return the first PUReputationRB matching the query
 * @method PUReputationRB findOneOrCreate(PropelPDO $con = null) Return the first PUReputationRB matching the query, or a new PUReputationRB object populated from the query conditions when no match is found
 *
 * @method PUReputationRB findOneByPUserId(int $p_user_id) Return the first PUReputationRB filtered by the p_user_id column
 * @method PUReputationRB findOneByPRBadgeId(int $p_r_badge_id) Return the first PUReputationRB filtered by the p_r_badge_id column
 * @method PUReputationRB findOneByCreatedAt(string $created_at) Return the first PUReputationRB filtered by the created_at column
 * @method PUReputationRB findOneByUpdatedAt(string $updated_at) Return the first PUReputationRB filtered by the updated_at column
 *
 * @method array findById(int $id) Return PUReputationRB objects filtered by the id column
 * @method array findByPUserId(int $p_user_id) Return PUReputationRB objects filtered by the p_user_id column
 * @method array findByPRBadgeId(int $p_r_badge_id) Return PUReputationRB objects filtered by the p_r_badge_id column
 * @method array findByCreatedAt(string $created_at) Return PUReputationRB objects filtered by the created_at column
 * @method array findByUpdatedAt(string $updated_at) Return PUReputationRB objects filtered by the updated_at column
 */
abstract class BasePUReputationRBQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BasePUReputationRBQuery object.
     *
     * @param     string $dbName The dabase name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = 'Politizr\\Model\\PUReputationRB', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new PUReputationRBQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   PUReputationRBQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return PUReputationRBQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof PUReputationRBQuery) {
            return $criteria;
        }
        $query = new PUReputationRBQuery();
        if (null !== $modelAlias) {
            $query->setModelAlias($modelAlias);
        }
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
     * @return   PUReputationRB|PUReputationRB[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = PUReputationRBPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is alredy in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(PUReputationRBPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 PUReputationRB A model object, or null if the key is not found
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
     * @return                 PUReputationRB A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `p_user_id`, `p_r_badge_id`, `created_at`, `updated_at` FROM `p_u_reputation_r_b` WHERE `id` = :p0';
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
            $obj = new PUReputationRB();
            $obj->hydrate($row);
            PUReputationRBPeer::addInstanceToPool($obj, (string) $key);
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
     * @return PUReputationRB|PUReputationRB[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|PUReputationRB[]|mixed the list of results, formatted by the current formatter
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
     * @return PUReputationRBQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(PUReputationRBPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return PUReputationRBQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(PUReputationRBPeer::ID, $keys, Criteria::IN);
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
     * @return PUReputationRBQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(PUReputationRBPeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(PUReputationRBPeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PUReputationRBPeer::ID, $id, $comparison);
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
     * @see       filterByPuReputationRbPUser()
     *
     * @param     mixed $pUserId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PUReputationRBQuery The current query, for fluid interface
     */
    public function filterByPUserId($pUserId = null, $comparison = null)
    {
        if (is_array($pUserId)) {
            $useMinMax = false;
            if (isset($pUserId['min'])) {
                $this->addUsingAlias(PUReputationRBPeer::P_USER_ID, $pUserId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($pUserId['max'])) {
                $this->addUsingAlias(PUReputationRBPeer::P_USER_ID, $pUserId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PUReputationRBPeer::P_USER_ID, $pUserId, $comparison);
    }

    /**
     * Filter the query on the p_r_badge_id column
     *
     * Example usage:
     * <code>
     * $query->filterByPRBadgeId(1234); // WHERE p_r_badge_id = 1234
     * $query->filterByPRBadgeId(array(12, 34)); // WHERE p_r_badge_id IN (12, 34)
     * $query->filterByPRBadgeId(array('min' => 12)); // WHERE p_r_badge_id >= 12
     * $query->filterByPRBadgeId(array('max' => 12)); // WHERE p_r_badge_id <= 12
     * </code>
     *
     * @see       filterByPuReputationRbPRBadge()
     *
     * @param     mixed $pRBadgeId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PUReputationRBQuery The current query, for fluid interface
     */
    public function filterByPRBadgeId($pRBadgeId = null, $comparison = null)
    {
        if (is_array($pRBadgeId)) {
            $useMinMax = false;
            if (isset($pRBadgeId['min'])) {
                $this->addUsingAlias(PUReputationRBPeer::P_R_BADGE_ID, $pRBadgeId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($pRBadgeId['max'])) {
                $this->addUsingAlias(PUReputationRBPeer::P_R_BADGE_ID, $pRBadgeId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PUReputationRBPeer::P_R_BADGE_ID, $pRBadgeId, $comparison);
    }

    /**
     * Filter the query on the created_at column
     *
     * Example usage:
     * <code>
     * $query->filterByCreatedAt('2011-03-14'); // WHERE created_at = '2011-03-14'
     * $query->filterByCreatedAt('now'); // WHERE created_at = '2011-03-14'
     * $query->filterByCreatedAt(array('max' => 'yesterday')); // WHERE created_at > '2011-03-13'
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
     * @return PUReputationRBQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(PUReputationRBPeer::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(PUReputationRBPeer::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PUReputationRBPeer::CREATED_AT, $createdAt, $comparison);
    }

    /**
     * Filter the query on the updated_at column
     *
     * Example usage:
     * <code>
     * $query->filterByUpdatedAt('2011-03-14'); // WHERE updated_at = '2011-03-14'
     * $query->filterByUpdatedAt('now'); // WHERE updated_at = '2011-03-14'
     * $query->filterByUpdatedAt(array('max' => 'yesterday')); // WHERE updated_at > '2011-03-13'
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
     * @return PUReputationRBQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(PUReputationRBPeer::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(PUReputationRBPeer::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PUReputationRBPeer::UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query by a related PUser object
     *
     * @param   PUser|PropelObjectCollection $pUser The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PUReputationRBQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPuReputationRbPUser($pUser, $comparison = null)
    {
        if ($pUser instanceof PUser) {
            return $this
                ->addUsingAlias(PUReputationRBPeer::P_USER_ID, $pUser->getId(), $comparison);
        } elseif ($pUser instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PUReputationRBPeer::P_USER_ID, $pUser->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByPuReputationRbPUser() only accepts arguments of type PUser or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PuReputationRbPUser relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PUReputationRBQuery The current query, for fluid interface
     */
    public function joinPuReputationRbPUser($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PuReputationRbPUser');

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
            $this->addJoinObject($join, 'PuReputationRbPUser');
        }

        return $this;
    }

    /**
     * Use the PuReputationRbPUser relation PUser object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Politizr\Model\PUserQuery A secondary query class using the current class as primary query
     */
    public function usePuReputationRbPUserQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPuReputationRbPUser($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PuReputationRbPUser', '\Politizr\Model\PUserQuery');
    }

    /**
     * Filter the query by a related PRBadge object
     *
     * @param   PRBadge|PropelObjectCollection $pRBadge The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PUReputationRBQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPuReputationRbPRBadge($pRBadge, $comparison = null)
    {
        if ($pRBadge instanceof PRBadge) {
            return $this
                ->addUsingAlias(PUReputationRBPeer::P_R_BADGE_ID, $pRBadge->getId(), $comparison);
        } elseif ($pRBadge instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PUReputationRBPeer::P_R_BADGE_ID, $pRBadge->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByPuReputationRbPRBadge() only accepts arguments of type PRBadge or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PuReputationRbPRBadge relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PUReputationRBQuery The current query, for fluid interface
     */
    public function joinPuReputationRbPRBadge($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PuReputationRbPRBadge');

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
            $this->addJoinObject($join, 'PuReputationRbPRBadge');
        }

        return $this;
    }

    /**
     * Use the PuReputationRbPRBadge relation PRBadge object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Politizr\Model\PRBadgeQuery A secondary query class using the current class as primary query
     */
    public function usePuReputationRbPRBadgeQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPuReputationRbPRBadge($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PuReputationRbPRBadge', '\Politizr\Model\PRBadgeQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   PUReputationRB $pUReputationRB Object to remove from the list of results
     *
     * @return PUReputationRBQuery The current query, for fluid interface
     */
    public function prune($pUReputationRB = null)
    {
        if ($pUReputationRB) {
            $this->addUsingAlias(PUReputationRBPeer::ID, $pUReputationRB->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     PUReputationRBQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(PUReputationRBPeer::UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     PUReputationRBQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(PUReputationRBPeer::UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     PUReputationRBQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(PUReputationRBPeer::UPDATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     PUReputationRBQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(PUReputationRBPeer::CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date desc
     *
     * @return     PUReputationRBQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(PUReputationRBPeer::CREATED_AT);
    }

    /**
     * Order by create date asc
     *
     * @return     PUReputationRBQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(PUReputationRBPeer::CREATED_AT);
    }
}
