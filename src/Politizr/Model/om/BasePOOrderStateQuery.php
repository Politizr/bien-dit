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
use Politizr\Model\POEmail;
use Politizr\Model\POOrderState;
use Politizr\Model\POOrderStatePeer;
use Politizr\Model\POOrderStateQuery;
use Politizr\Model\POrder;

/**
 * @method POOrderStateQuery orderById($order = Criteria::ASC) Order by the id column
 * @method POOrderStateQuery orderByTitle($order = Criteria::ASC) Order by the title column
 * @method POOrderStateQuery orderByDescription($order = Criteria::ASC) Order by the description column
 * @method POOrderStateQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method POOrderStateQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method POOrderStateQuery groupById() Group by the id column
 * @method POOrderStateQuery groupByTitle() Group by the title column
 * @method POOrderStateQuery groupByDescription() Group by the description column
 * @method POOrderStateQuery groupByCreatedAt() Group by the created_at column
 * @method POOrderStateQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method POOrderStateQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method POOrderStateQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method POOrderStateQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method POOrderStateQuery leftJoinPOrder($relationAlias = null) Adds a LEFT JOIN clause to the query using the POrder relation
 * @method POOrderStateQuery rightJoinPOrder($relationAlias = null) Adds a RIGHT JOIN clause to the query using the POrder relation
 * @method POOrderStateQuery innerJoinPOrder($relationAlias = null) Adds a INNER JOIN clause to the query using the POrder relation
 *
 * @method POOrderStateQuery leftJoinPOEmail($relationAlias = null) Adds a LEFT JOIN clause to the query using the POEmail relation
 * @method POOrderStateQuery rightJoinPOEmail($relationAlias = null) Adds a RIGHT JOIN clause to the query using the POEmail relation
 * @method POOrderStateQuery innerJoinPOEmail($relationAlias = null) Adds a INNER JOIN clause to the query using the POEmail relation
 *
 * @method POOrderState findOne(PropelPDO $con = null) Return the first POOrderState matching the query
 * @method POOrderState findOneOrCreate(PropelPDO $con = null) Return the first POOrderState matching the query, or a new POOrderState object populated from the query conditions when no match is found
 *
 * @method POOrderState findOneByTitle(string $title) Return the first POOrderState filtered by the title column
 * @method POOrderState findOneByDescription(string $description) Return the first POOrderState filtered by the description column
 * @method POOrderState findOneByCreatedAt(string $created_at) Return the first POOrderState filtered by the created_at column
 * @method POOrderState findOneByUpdatedAt(string $updated_at) Return the first POOrderState filtered by the updated_at column
 *
 * @method array findById(int $id) Return POOrderState objects filtered by the id column
 * @method array findByTitle(string $title) Return POOrderState objects filtered by the title column
 * @method array findByDescription(string $description) Return POOrderState objects filtered by the description column
 * @method array findByCreatedAt(string $created_at) Return POOrderState objects filtered by the created_at column
 * @method array findByUpdatedAt(string $updated_at) Return POOrderState objects filtered by the updated_at column
 */
abstract class BasePOOrderStateQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BasePOOrderStateQuery object.
     *
     * @param     string $dbName The dabase name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = 'Politizr\\Model\\POOrderState', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new POOrderStateQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   POOrderStateQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return POOrderStateQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof POOrderStateQuery) {
            return $criteria;
        }
        $query = new POOrderStateQuery();
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
     * @return   POOrderState|POOrderState[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = POOrderStatePeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is alredy in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(POOrderStatePeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 POOrderState A model object, or null if the key is not found
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
     * @return                 POOrderState A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `title`, `description`, `created_at`, `updated_at` FROM `p_o_order_state` WHERE `id` = :p0';
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
            $obj = new POOrderState();
            $obj->hydrate($row);
            POOrderStatePeer::addInstanceToPool($obj, (string) $key);
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
     * @return POOrderState|POOrderState[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|POOrderState[]|mixed the list of results, formatted by the current formatter
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
     * @return POOrderStateQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(POOrderStatePeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return POOrderStateQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(POOrderStatePeer::ID, $keys, Criteria::IN);
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
     * @return POOrderStateQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(POOrderStatePeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(POOrderStatePeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(POOrderStatePeer::ID, $id, $comparison);
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
     * @return POOrderStateQuery The current query, for fluid interface
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

        return $this->addUsingAlias(POOrderStatePeer::TITLE, $title, $comparison);
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
     * @return POOrderStateQuery The current query, for fluid interface
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

        return $this->addUsingAlias(POOrderStatePeer::DESCRIPTION, $description, $comparison);
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
     * @return POOrderStateQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(POOrderStatePeer::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(POOrderStatePeer::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(POOrderStatePeer::CREATED_AT, $createdAt, $comparison);
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
     * @return POOrderStateQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(POOrderStatePeer::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(POOrderStatePeer::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(POOrderStatePeer::UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query by a related POrder object
     *
     * @param   POrder|PropelObjectCollection $pOrder  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 POOrderStateQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPOrder($pOrder, $comparison = null)
    {
        if ($pOrder instanceof POrder) {
            return $this
                ->addUsingAlias(POOrderStatePeer::ID, $pOrder->getPOOrderStateId(), $comparison);
        } elseif ($pOrder instanceof PropelObjectCollection) {
            return $this
                ->usePOrderQuery()
                ->filterByPrimaryKeys($pOrder->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByPOrder() only accepts arguments of type POrder or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the POrder relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return POOrderStateQuery The current query, for fluid interface
     */
    public function joinPOrder($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('POrder');

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
            $this->addJoinObject($join, 'POrder');
        }

        return $this;
    }

    /**
     * Use the POrder relation POrder object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Politizr\Model\POrderQuery A secondary query class using the current class as primary query
     */
    public function usePOrderQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinPOrder($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'POrder', '\Politizr\Model\POrderQuery');
    }

    /**
     * Filter the query by a related POEmail object
     *
     * @param   POEmail|PropelObjectCollection $pOEmail  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 POOrderStateQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPOEmail($pOEmail, $comparison = null)
    {
        if ($pOEmail instanceof POEmail) {
            return $this
                ->addUsingAlias(POOrderStatePeer::ID, $pOEmail->getPOOrderStateId(), $comparison);
        } elseif ($pOEmail instanceof PropelObjectCollection) {
            return $this
                ->usePOEmailQuery()
                ->filterByPrimaryKeys($pOEmail->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByPOEmail() only accepts arguments of type POEmail or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the POEmail relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return POOrderStateQuery The current query, for fluid interface
     */
    public function joinPOEmail($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('POEmail');

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
            $this->addJoinObject($join, 'POEmail');
        }

        return $this;
    }

    /**
     * Use the POEmail relation POEmail object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Politizr\Model\POEmailQuery A secondary query class using the current class as primary query
     */
    public function usePOEmailQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinPOEmail($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'POEmail', '\Politizr\Model\POEmailQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   POOrderState $pOOrderState Object to remove from the list of results
     *
     * @return POOrderStateQuery The current query, for fluid interface
     */
    public function prune($pOOrderState = null)
    {
        if ($pOOrderState) {
            $this->addUsingAlias(POOrderStatePeer::ID, $pOOrderState->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     POOrderStateQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(POOrderStatePeer::UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     POOrderStateQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(POOrderStatePeer::UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     POOrderStateQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(POOrderStatePeer::UPDATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     POOrderStateQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(POOrderStatePeer::CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date desc
     *
     * @return     POOrderStateQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(POOrderStatePeer::CREATED_AT);
    }

    /**
     * Order by create date asc
     *
     * @return     POOrderStateQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(POOrderStatePeer::CREATED_AT);
    }
}
