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
use Politizr\Model\PUAffinityUPP;
use Politizr\Model\PUAffinityUPPPeer;
use Politizr\Model\PUAffinityUPPQuery;
use Politizr\Model\PUPoliticalParty;
use Politizr\Model\PUser;

/**
 * @method PUAffinityUPPQuery orderById($order = Criteria::ASC) Order by the id column
 * @method PUAffinityUPPQuery orderByPUserId($order = Criteria::ASC) Order by the p_user_id column
 * @method PUAffinityUPPQuery orderByPUPoliticalPartyId($order = Criteria::ASC) Order by the p_u_political_party_id column
 * @method PUAffinityUPPQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method PUAffinityUPPQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method PUAffinityUPPQuery groupById() Group by the id column
 * @method PUAffinityUPPQuery groupByPUserId() Group by the p_user_id column
 * @method PUAffinityUPPQuery groupByPUPoliticalPartyId() Group by the p_u_political_party_id column
 * @method PUAffinityUPPQuery groupByCreatedAt() Group by the created_at column
 * @method PUAffinityUPPQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method PUAffinityUPPQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method PUAffinityUPPQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method PUAffinityUPPQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method PUAffinityUPPQuery leftJoinPuAffinityUppPUser($relationAlias = null) Adds a LEFT JOIN clause to the query using the PuAffinityUppPUser relation
 * @method PUAffinityUPPQuery rightJoinPuAffinityUppPUser($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PuAffinityUppPUser relation
 * @method PUAffinityUPPQuery innerJoinPuAffinityUppPUser($relationAlias = null) Adds a INNER JOIN clause to the query using the PuAffinityUppPUser relation
 *
 * @method PUAffinityUPPQuery leftJoinPuAffinityUppPUPoliticalParty($relationAlias = null) Adds a LEFT JOIN clause to the query using the PuAffinityUppPUPoliticalParty relation
 * @method PUAffinityUPPQuery rightJoinPuAffinityUppPUPoliticalParty($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PuAffinityUppPUPoliticalParty relation
 * @method PUAffinityUPPQuery innerJoinPuAffinityUppPUPoliticalParty($relationAlias = null) Adds a INNER JOIN clause to the query using the PuAffinityUppPUPoliticalParty relation
 *
 * @method PUAffinityUPP findOne(PropelPDO $con = null) Return the first PUAffinityUPP matching the query
 * @method PUAffinityUPP findOneOrCreate(PropelPDO $con = null) Return the first PUAffinityUPP matching the query, or a new PUAffinityUPP object populated from the query conditions when no match is found
 *
 * @method PUAffinityUPP findOneByPUserId(int $p_user_id) Return the first PUAffinityUPP filtered by the p_user_id column
 * @method PUAffinityUPP findOneByPUPoliticalPartyId(int $p_u_political_party_id) Return the first PUAffinityUPP filtered by the p_u_political_party_id column
 * @method PUAffinityUPP findOneByCreatedAt(string $created_at) Return the first PUAffinityUPP filtered by the created_at column
 * @method PUAffinityUPP findOneByUpdatedAt(string $updated_at) Return the first PUAffinityUPP filtered by the updated_at column
 *
 * @method array findById(int $id) Return PUAffinityUPP objects filtered by the id column
 * @method array findByPUserId(int $p_user_id) Return PUAffinityUPP objects filtered by the p_user_id column
 * @method array findByPUPoliticalPartyId(int $p_u_political_party_id) Return PUAffinityUPP objects filtered by the p_u_political_party_id column
 * @method array findByCreatedAt(string $created_at) Return PUAffinityUPP objects filtered by the created_at column
 * @method array findByUpdatedAt(string $updated_at) Return PUAffinityUPP objects filtered by the updated_at column
 */
abstract class BasePUAffinityUPPQuery extends ModelCriteria
{
    // query_cache behavior
    protected $queryKey = '';

    /**
     * Initializes internal state of BasePUAffinityUPPQuery object.
     *
     * @param     string $dbName The dabase name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = 'Politizr\\Model\\PUAffinityUPP', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new PUAffinityUPPQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   PUAffinityUPPQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return PUAffinityUPPQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof PUAffinityUPPQuery) {
            return $criteria;
        }
        $query = new PUAffinityUPPQuery();
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
     * @return   PUAffinityUPP|PUAffinityUPP[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = PUAffinityUPPPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is alredy in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(PUAffinityUPPPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 PUAffinityUPP A model object, or null if the key is not found
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
     * @return                 PUAffinityUPP A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `p_user_id`, `p_u_political_party_id`, `created_at`, `updated_at` FROM `p_u_affinity_u_p_p` WHERE `id` = :p0';
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
            $obj = new PUAffinityUPP();
            $obj->hydrate($row);
            PUAffinityUPPPeer::addInstanceToPool($obj, (string) $key);
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
     * @return PUAffinityUPP|PUAffinityUPP[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|PUAffinityUPP[]|mixed the list of results, formatted by the current formatter
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
     * @return PUAffinityUPPQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(PUAffinityUPPPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return PUAffinityUPPQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(PUAffinityUPPPeer::ID, $keys, Criteria::IN);
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
     * @return PUAffinityUPPQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(PUAffinityUPPPeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(PUAffinityUPPPeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PUAffinityUPPPeer::ID, $id, $comparison);
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
     * @see       filterByPuAffinityUppPUser()
     *
     * @param     mixed $pUserId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PUAffinityUPPQuery The current query, for fluid interface
     */
    public function filterByPUserId($pUserId = null, $comparison = null)
    {
        if (is_array($pUserId)) {
            $useMinMax = false;
            if (isset($pUserId['min'])) {
                $this->addUsingAlias(PUAffinityUPPPeer::P_USER_ID, $pUserId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($pUserId['max'])) {
                $this->addUsingAlias(PUAffinityUPPPeer::P_USER_ID, $pUserId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PUAffinityUPPPeer::P_USER_ID, $pUserId, $comparison);
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
     * @see       filterByPuAffinityUppPUPoliticalParty()
     *
     * @param     mixed $pUPoliticalPartyId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PUAffinityUPPQuery The current query, for fluid interface
     */
    public function filterByPUPoliticalPartyId($pUPoliticalPartyId = null, $comparison = null)
    {
        if (is_array($pUPoliticalPartyId)) {
            $useMinMax = false;
            if (isset($pUPoliticalPartyId['min'])) {
                $this->addUsingAlias(PUAffinityUPPPeer::P_U_POLITICAL_PARTY_ID, $pUPoliticalPartyId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($pUPoliticalPartyId['max'])) {
                $this->addUsingAlias(PUAffinityUPPPeer::P_U_POLITICAL_PARTY_ID, $pUPoliticalPartyId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PUAffinityUPPPeer::P_U_POLITICAL_PARTY_ID, $pUPoliticalPartyId, $comparison);
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
     * @return PUAffinityUPPQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(PUAffinityUPPPeer::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(PUAffinityUPPPeer::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PUAffinityUPPPeer::CREATED_AT, $createdAt, $comparison);
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
     * @return PUAffinityUPPQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(PUAffinityUPPPeer::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(PUAffinityUPPPeer::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PUAffinityUPPPeer::UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query by a related PUser object
     *
     * @param   PUser|PropelObjectCollection $pUser The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PUAffinityUPPQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPuAffinityUppPUser($pUser, $comparison = null)
    {
        if ($pUser instanceof PUser) {
            return $this
                ->addUsingAlias(PUAffinityUPPPeer::P_USER_ID, $pUser->getId(), $comparison);
        } elseif ($pUser instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PUAffinityUPPPeer::P_USER_ID, $pUser->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByPuAffinityUppPUser() only accepts arguments of type PUser or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PuAffinityUppPUser relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PUAffinityUPPQuery The current query, for fluid interface
     */
    public function joinPuAffinityUppPUser($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PuAffinityUppPUser');

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
            $this->addJoinObject($join, 'PuAffinityUppPUser');
        }

        return $this;
    }

    /**
     * Use the PuAffinityUppPUser relation PUser object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Politizr\Model\PUserQuery A secondary query class using the current class as primary query
     */
    public function usePuAffinityUppPUserQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPuAffinityUppPUser($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PuAffinityUppPUser', '\Politizr\Model\PUserQuery');
    }

    /**
     * Filter the query by a related PUPoliticalParty object
     *
     * @param   PUPoliticalParty|PropelObjectCollection $pUPoliticalParty The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PUAffinityUPPQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPuAffinityUppPUPoliticalParty($pUPoliticalParty, $comparison = null)
    {
        if ($pUPoliticalParty instanceof PUPoliticalParty) {
            return $this
                ->addUsingAlias(PUAffinityUPPPeer::P_U_POLITICAL_PARTY_ID, $pUPoliticalParty->getId(), $comparison);
        } elseif ($pUPoliticalParty instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PUAffinityUPPPeer::P_U_POLITICAL_PARTY_ID, $pUPoliticalParty->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByPuAffinityUppPUPoliticalParty() only accepts arguments of type PUPoliticalParty or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PuAffinityUppPUPoliticalParty relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PUAffinityUPPQuery The current query, for fluid interface
     */
    public function joinPuAffinityUppPUPoliticalParty($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PuAffinityUppPUPoliticalParty');

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
            $this->addJoinObject($join, 'PuAffinityUppPUPoliticalParty');
        }

        return $this;
    }

    /**
     * Use the PuAffinityUppPUPoliticalParty relation PUPoliticalParty object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Politizr\Model\PUPoliticalPartyQuery A secondary query class using the current class as primary query
     */
    public function usePuAffinityUppPUPoliticalPartyQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPuAffinityUppPUPoliticalParty($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PuAffinityUppPUPoliticalParty', '\Politizr\Model\PUPoliticalPartyQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   PUAffinityUPP $pUAffinityUPP Object to remove from the list of results
     *
     * @return PUAffinityUPPQuery The current query, for fluid interface
     */
    public function prune($pUAffinityUPP = null)
    {
        if ($pUAffinityUPP) {
            $this->addUsingAlias(PUAffinityUPPPeer::ID, $pUAffinityUPP->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     PUAffinityUPPQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(PUAffinityUPPPeer::UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     PUAffinityUPPQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(PUAffinityUPPPeer::UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     PUAffinityUPPQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(PUAffinityUPPPeer::UPDATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     PUAffinityUPPQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(PUAffinityUPPPeer::CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date desc
     *
     * @return     PUAffinityUPPQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(PUAffinityUPPPeer::CREATED_AT);
    }

    /**
     * Order by create date asc
     *
     * @return     PUAffinityUPPQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(PUAffinityUPPPeer::CREATED_AT);
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

        $dbMap = Propel::getDatabaseMap(PUAffinityUPPPeer::DATABASE_NAME);
        $db = Propel::getDB(PUAffinityUPPPeer::DATABASE_NAME);

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
