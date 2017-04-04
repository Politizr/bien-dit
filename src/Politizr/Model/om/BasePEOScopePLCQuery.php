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
use Politizr\Model\PEOScopePLC;
use Politizr\Model\PEOScopePLCPeer;
use Politizr\Model\PEOScopePLCQuery;
use Politizr\Model\PEOperation;
use Politizr\Model\PLCity;

/**
 * @method PEOScopePLCQuery orderById($order = Criteria::ASC) Order by the id column
 * @method PEOScopePLCQuery orderByPEOperationId($order = Criteria::ASC) Order by the p_e_operation_id column
 * @method PEOScopePLCQuery orderByPLCityId($order = Criteria::ASC) Order by the p_l_city_id column
 * @method PEOScopePLCQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method PEOScopePLCQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method PEOScopePLCQuery groupById() Group by the id column
 * @method PEOScopePLCQuery groupByPEOperationId() Group by the p_e_operation_id column
 * @method PEOScopePLCQuery groupByPLCityId() Group by the p_l_city_id column
 * @method PEOScopePLCQuery groupByCreatedAt() Group by the created_at column
 * @method PEOScopePLCQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method PEOScopePLCQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method PEOScopePLCQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method PEOScopePLCQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method PEOScopePLCQuery leftJoinPEOperation($relationAlias = null) Adds a LEFT JOIN clause to the query using the PEOperation relation
 * @method PEOScopePLCQuery rightJoinPEOperation($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PEOperation relation
 * @method PEOScopePLCQuery innerJoinPEOperation($relationAlias = null) Adds a INNER JOIN clause to the query using the PEOperation relation
 *
 * @method PEOScopePLCQuery leftJoinPLCity($relationAlias = null) Adds a LEFT JOIN clause to the query using the PLCity relation
 * @method PEOScopePLCQuery rightJoinPLCity($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PLCity relation
 * @method PEOScopePLCQuery innerJoinPLCity($relationAlias = null) Adds a INNER JOIN clause to the query using the PLCity relation
 *
 * @method PEOScopePLC findOne(PropelPDO $con = null) Return the first PEOScopePLC matching the query
 * @method PEOScopePLC findOneOrCreate(PropelPDO $con = null) Return the first PEOScopePLC matching the query, or a new PEOScopePLC object populated from the query conditions when no match is found
 *
 * @method PEOScopePLC findOneByPEOperationId(int $p_e_operation_id) Return the first PEOScopePLC filtered by the p_e_operation_id column
 * @method PEOScopePLC findOneByPLCityId(int $p_l_city_id) Return the first PEOScopePLC filtered by the p_l_city_id column
 * @method PEOScopePLC findOneByCreatedAt(string $created_at) Return the first PEOScopePLC filtered by the created_at column
 * @method PEOScopePLC findOneByUpdatedAt(string $updated_at) Return the first PEOScopePLC filtered by the updated_at column
 *
 * @method array findById(int $id) Return PEOScopePLC objects filtered by the id column
 * @method array findByPEOperationId(int $p_e_operation_id) Return PEOScopePLC objects filtered by the p_e_operation_id column
 * @method array findByPLCityId(int $p_l_city_id) Return PEOScopePLC objects filtered by the p_l_city_id column
 * @method array findByCreatedAt(string $created_at) Return PEOScopePLC objects filtered by the created_at column
 * @method array findByUpdatedAt(string $updated_at) Return PEOScopePLC objects filtered by the updated_at column
 */
abstract class BasePEOScopePLCQuery extends ModelCriteria
{
    // query_cache behavior
    protected $queryKey = '';

    /**
     * Initializes internal state of BasePEOScopePLCQuery object.
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
            $modelName = 'Politizr\\Model\\PEOScopePLC';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new PEOScopePLCQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   PEOScopePLCQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return PEOScopePLCQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof PEOScopePLCQuery) {
            return $criteria;
        }
        $query = new PEOScopePLCQuery(null, null, $modelAlias);

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
     * @return   PEOScopePLC|PEOScopePLC[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = PEOScopePLCPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(PEOScopePLCPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 PEOScopePLC A model object, or null if the key is not found
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
     * @return                 PEOScopePLC A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `p_e_operation_id`, `p_l_city_id`, `created_at`, `updated_at` FROM `p_e_o_scope_p_l_c` WHERE `id` = :p0';
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
            $obj = new PEOScopePLC();
            $obj->hydrate($row);
            PEOScopePLCPeer::addInstanceToPool($obj, (string) $key);
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
     * @return PEOScopePLC|PEOScopePLC[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|PEOScopePLC[]|mixed the list of results, formatted by the current formatter
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
     * @return PEOScopePLCQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(PEOScopePLCPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return PEOScopePLCQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(PEOScopePLCPeer::ID, $keys, Criteria::IN);
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
     * @return PEOScopePLCQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(PEOScopePLCPeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(PEOScopePLCPeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PEOScopePLCPeer::ID, $id, $comparison);
    }

    /**
     * Filter the query on the p_e_operation_id column
     *
     * Example usage:
     * <code>
     * $query->filterByPEOperationId(1234); // WHERE p_e_operation_id = 1234
     * $query->filterByPEOperationId(array(12, 34)); // WHERE p_e_operation_id IN (12, 34)
     * $query->filterByPEOperationId(array('min' => 12)); // WHERE p_e_operation_id >= 12
     * $query->filterByPEOperationId(array('max' => 12)); // WHERE p_e_operation_id <= 12
     * </code>
     *
     * @see       filterByPEOperation()
     *
     * @param     mixed $pEOperationId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PEOScopePLCQuery The current query, for fluid interface
     */
    public function filterByPEOperationId($pEOperationId = null, $comparison = null)
    {
        if (is_array($pEOperationId)) {
            $useMinMax = false;
            if (isset($pEOperationId['min'])) {
                $this->addUsingAlias(PEOScopePLCPeer::P_E_OPERATION_ID, $pEOperationId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($pEOperationId['max'])) {
                $this->addUsingAlias(PEOScopePLCPeer::P_E_OPERATION_ID, $pEOperationId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PEOScopePLCPeer::P_E_OPERATION_ID, $pEOperationId, $comparison);
    }

    /**
     * Filter the query on the p_l_city_id column
     *
     * Example usage:
     * <code>
     * $query->filterByPLCityId(1234); // WHERE p_l_city_id = 1234
     * $query->filterByPLCityId(array(12, 34)); // WHERE p_l_city_id IN (12, 34)
     * $query->filterByPLCityId(array('min' => 12)); // WHERE p_l_city_id >= 12
     * $query->filterByPLCityId(array('max' => 12)); // WHERE p_l_city_id <= 12
     * </code>
     *
     * @see       filterByPLCity()
     *
     * @param     mixed $pLCityId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PEOScopePLCQuery The current query, for fluid interface
     */
    public function filterByPLCityId($pLCityId = null, $comparison = null)
    {
        if (is_array($pLCityId)) {
            $useMinMax = false;
            if (isset($pLCityId['min'])) {
                $this->addUsingAlias(PEOScopePLCPeer::P_L_CITY_ID, $pLCityId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($pLCityId['max'])) {
                $this->addUsingAlias(PEOScopePLCPeer::P_L_CITY_ID, $pLCityId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PEOScopePLCPeer::P_L_CITY_ID, $pLCityId, $comparison);
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
     * @return PEOScopePLCQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(PEOScopePLCPeer::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(PEOScopePLCPeer::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PEOScopePLCPeer::CREATED_AT, $createdAt, $comparison);
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
     * @return PEOScopePLCQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(PEOScopePLCPeer::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(PEOScopePLCPeer::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PEOScopePLCPeer::UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query by a related PEOperation object
     *
     * @param   PEOperation|PropelObjectCollection $pEOperation The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PEOScopePLCQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPEOperation($pEOperation, $comparison = null)
    {
        if ($pEOperation instanceof PEOperation) {
            return $this
                ->addUsingAlias(PEOScopePLCPeer::P_E_OPERATION_ID, $pEOperation->getId(), $comparison);
        } elseif ($pEOperation instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PEOScopePLCPeer::P_E_OPERATION_ID, $pEOperation->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByPEOperation() only accepts arguments of type PEOperation or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PEOperation relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PEOScopePLCQuery The current query, for fluid interface
     */
    public function joinPEOperation($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PEOperation');

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
            $this->addJoinObject($join, 'PEOperation');
        }

        return $this;
    }

    /**
     * Use the PEOperation relation PEOperation object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Politizr\Model\PEOperationQuery A secondary query class using the current class as primary query
     */
    public function usePEOperationQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPEOperation($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PEOperation', '\Politizr\Model\PEOperationQuery');
    }

    /**
     * Filter the query by a related PLCity object
     *
     * @param   PLCity|PropelObjectCollection $pLCity The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PEOScopePLCQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPLCity($pLCity, $comparison = null)
    {
        if ($pLCity instanceof PLCity) {
            return $this
                ->addUsingAlias(PEOScopePLCPeer::P_L_CITY_ID, $pLCity->getId(), $comparison);
        } elseif ($pLCity instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PEOScopePLCPeer::P_L_CITY_ID, $pLCity->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByPLCity() only accepts arguments of type PLCity or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PLCity relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PEOScopePLCQuery The current query, for fluid interface
     */
    public function joinPLCity($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PLCity');

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
            $this->addJoinObject($join, 'PLCity');
        }

        return $this;
    }

    /**
     * Use the PLCity relation PLCity object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Politizr\Model\PLCityQuery A secondary query class using the current class as primary query
     */
    public function usePLCityQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPLCity($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PLCity', '\Politizr\Model\PLCityQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   PEOScopePLC $pEOScopePLC Object to remove from the list of results
     *
     * @return PEOScopePLCQuery The current query, for fluid interface
     */
    public function prune($pEOScopePLC = null)
    {
        if ($pEOScopePLC) {
            $this->addUsingAlias(PEOScopePLCPeer::ID, $pEOScopePLC->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     PEOScopePLCQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(PEOScopePLCPeer::UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     PEOScopePLCQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(PEOScopePLCPeer::UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     PEOScopePLCQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(PEOScopePLCPeer::UPDATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     PEOScopePLCQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(PEOScopePLCPeer::CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date desc
     *
     * @return     PEOScopePLCQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(PEOScopePLCPeer::CREATED_AT);
    }

    /**
     * Order by create date asc
     *
     * @return     PEOScopePLCQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(PEOScopePLCPeer::CREATED_AT);
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

        $dbMap = Propel::getDatabaseMap(PEOScopePLCPeer::DATABASE_NAME);
        $db = Propel::getDB(PEOScopePLCPeer::DATABASE_NAME);

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
