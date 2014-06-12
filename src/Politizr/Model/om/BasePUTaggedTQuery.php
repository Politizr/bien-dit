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
use Politizr\Model\PTag;
use Politizr\Model\PUTaggedT;
use Politizr\Model\PUTaggedTPeer;
use Politizr\Model\PUTaggedTQuery;
use Politizr\Model\PUser;

/**
 * @method PUTaggedTQuery orderById($order = Criteria::ASC) Order by the id column
 * @method PUTaggedTQuery orderByPUserId($order = Criteria::ASC) Order by the p_user_id column
 * @method PUTaggedTQuery orderByPTagId($order = Criteria::ASC) Order by the p_tag_id column
 * @method PUTaggedTQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method PUTaggedTQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method PUTaggedTQuery groupById() Group by the id column
 * @method PUTaggedTQuery groupByPUserId() Group by the p_user_id column
 * @method PUTaggedTQuery groupByPTagId() Group by the p_tag_id column
 * @method PUTaggedTQuery groupByCreatedAt() Group by the created_at column
 * @method PUTaggedTQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method PUTaggedTQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method PUTaggedTQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method PUTaggedTQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method PUTaggedTQuery leftJoinPuTaggedTPUser($relationAlias = null) Adds a LEFT JOIN clause to the query using the PuTaggedTPUser relation
 * @method PUTaggedTQuery rightJoinPuTaggedTPUser($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PuTaggedTPUser relation
 * @method PUTaggedTQuery innerJoinPuTaggedTPUser($relationAlias = null) Adds a INNER JOIN clause to the query using the PuTaggedTPUser relation
 *
 * @method PUTaggedTQuery leftJoinPuTaggedTPTag($relationAlias = null) Adds a LEFT JOIN clause to the query using the PuTaggedTPTag relation
 * @method PUTaggedTQuery rightJoinPuTaggedTPTag($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PuTaggedTPTag relation
 * @method PUTaggedTQuery innerJoinPuTaggedTPTag($relationAlias = null) Adds a INNER JOIN clause to the query using the PuTaggedTPTag relation
 *
 * @method PUTaggedT findOne(PropelPDO $con = null) Return the first PUTaggedT matching the query
 * @method PUTaggedT findOneOrCreate(PropelPDO $con = null) Return the first PUTaggedT matching the query, or a new PUTaggedT object populated from the query conditions when no match is found
 *
 * @method PUTaggedT findOneByPUserId(int $p_user_id) Return the first PUTaggedT filtered by the p_user_id column
 * @method PUTaggedT findOneByPTagId(int $p_tag_id) Return the first PUTaggedT filtered by the p_tag_id column
 * @method PUTaggedT findOneByCreatedAt(string $created_at) Return the first PUTaggedT filtered by the created_at column
 * @method PUTaggedT findOneByUpdatedAt(string $updated_at) Return the first PUTaggedT filtered by the updated_at column
 *
 * @method array findById(int $id) Return PUTaggedT objects filtered by the id column
 * @method array findByPUserId(int $p_user_id) Return PUTaggedT objects filtered by the p_user_id column
 * @method array findByPTagId(int $p_tag_id) Return PUTaggedT objects filtered by the p_tag_id column
 * @method array findByCreatedAt(string $created_at) Return PUTaggedT objects filtered by the created_at column
 * @method array findByUpdatedAt(string $updated_at) Return PUTaggedT objects filtered by the updated_at column
 */
abstract class BasePUTaggedTQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BasePUTaggedTQuery object.
     *
     * @param     string $dbName The dabase name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = 'Politizr\\Model\\PUTaggedT', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new PUTaggedTQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   PUTaggedTQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return PUTaggedTQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof PUTaggedTQuery) {
            return $criteria;
        }
        $query = new PUTaggedTQuery();
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
     * @return   PUTaggedT|PUTaggedT[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = PUTaggedTPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is alredy in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(PUTaggedTPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 PUTaggedT A model object, or null if the key is not found
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
     * @return                 PUTaggedT A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `p_user_id`, `p_tag_id`, `created_at`, `updated_at` FROM `p_u_tagged_t` WHERE `id` = :p0';
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
            $obj = new PUTaggedT();
            $obj->hydrate($row);
            PUTaggedTPeer::addInstanceToPool($obj, (string) $key);
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
     * @return PUTaggedT|PUTaggedT[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|PUTaggedT[]|mixed the list of results, formatted by the current formatter
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
     * @return PUTaggedTQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(PUTaggedTPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return PUTaggedTQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(PUTaggedTPeer::ID, $keys, Criteria::IN);
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
     * @return PUTaggedTQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(PUTaggedTPeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(PUTaggedTPeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PUTaggedTPeer::ID, $id, $comparison);
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
     * @see       filterByPuTaggedTPUser()
     *
     * @param     mixed $pUserId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PUTaggedTQuery The current query, for fluid interface
     */
    public function filterByPUserId($pUserId = null, $comparison = null)
    {
        if (is_array($pUserId)) {
            $useMinMax = false;
            if (isset($pUserId['min'])) {
                $this->addUsingAlias(PUTaggedTPeer::P_USER_ID, $pUserId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($pUserId['max'])) {
                $this->addUsingAlias(PUTaggedTPeer::P_USER_ID, $pUserId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PUTaggedTPeer::P_USER_ID, $pUserId, $comparison);
    }

    /**
     * Filter the query on the p_tag_id column
     *
     * Example usage:
     * <code>
     * $query->filterByPTagId(1234); // WHERE p_tag_id = 1234
     * $query->filterByPTagId(array(12, 34)); // WHERE p_tag_id IN (12, 34)
     * $query->filterByPTagId(array('min' => 12)); // WHERE p_tag_id >= 12
     * $query->filterByPTagId(array('max' => 12)); // WHERE p_tag_id <= 12
     * </code>
     *
     * @see       filterByPuTaggedTPTag()
     *
     * @param     mixed $pTagId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PUTaggedTQuery The current query, for fluid interface
     */
    public function filterByPTagId($pTagId = null, $comparison = null)
    {
        if (is_array($pTagId)) {
            $useMinMax = false;
            if (isset($pTagId['min'])) {
                $this->addUsingAlias(PUTaggedTPeer::P_TAG_ID, $pTagId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($pTagId['max'])) {
                $this->addUsingAlias(PUTaggedTPeer::P_TAG_ID, $pTagId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PUTaggedTPeer::P_TAG_ID, $pTagId, $comparison);
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
     * @return PUTaggedTQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(PUTaggedTPeer::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(PUTaggedTPeer::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PUTaggedTPeer::CREATED_AT, $createdAt, $comparison);
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
     * @return PUTaggedTQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(PUTaggedTPeer::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(PUTaggedTPeer::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PUTaggedTPeer::UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query by a related PUser object
     *
     * @param   PUser|PropelObjectCollection $pUser The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PUTaggedTQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPuTaggedTPUser($pUser, $comparison = null)
    {
        if ($pUser instanceof PUser) {
            return $this
                ->addUsingAlias(PUTaggedTPeer::P_USER_ID, $pUser->getId(), $comparison);
        } elseif ($pUser instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PUTaggedTPeer::P_USER_ID, $pUser->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByPuTaggedTPUser() only accepts arguments of type PUser or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PuTaggedTPUser relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PUTaggedTQuery The current query, for fluid interface
     */
    public function joinPuTaggedTPUser($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PuTaggedTPUser');

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
            $this->addJoinObject($join, 'PuTaggedTPUser');
        }

        return $this;
    }

    /**
     * Use the PuTaggedTPUser relation PUser object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Politizr\Model\PUserQuery A secondary query class using the current class as primary query
     */
    public function usePuTaggedTPUserQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPuTaggedTPUser($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PuTaggedTPUser', '\Politizr\Model\PUserQuery');
    }

    /**
     * Filter the query by a related PTag object
     *
     * @param   PTag|PropelObjectCollection $pTag The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PUTaggedTQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPuTaggedTPTag($pTag, $comparison = null)
    {
        if ($pTag instanceof PTag) {
            return $this
                ->addUsingAlias(PUTaggedTPeer::P_TAG_ID, $pTag->getId(), $comparison);
        } elseif ($pTag instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PUTaggedTPeer::P_TAG_ID, $pTag->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByPuTaggedTPTag() only accepts arguments of type PTag or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PuTaggedTPTag relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PUTaggedTQuery The current query, for fluid interface
     */
    public function joinPuTaggedTPTag($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PuTaggedTPTag');

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
            $this->addJoinObject($join, 'PuTaggedTPTag');
        }

        return $this;
    }

    /**
     * Use the PuTaggedTPTag relation PTag object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Politizr\Model\PTagQuery A secondary query class using the current class as primary query
     */
    public function usePuTaggedTPTagQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPuTaggedTPTag($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PuTaggedTPTag', '\Politizr\Model\PTagQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   PUTaggedT $pUTaggedT Object to remove from the list of results
     *
     * @return PUTaggedTQuery The current query, for fluid interface
     */
    public function prune($pUTaggedT = null)
    {
        if ($pUTaggedT) {
            $this->addUsingAlias(PUTaggedTPeer::ID, $pUTaggedT->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     PUTaggedTQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(PUTaggedTPeer::UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     PUTaggedTQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(PUTaggedTPeer::UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     PUTaggedTQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(PUTaggedTPeer::UPDATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     PUTaggedTQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(PUTaggedTPeer::CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date desc
     *
     * @return     PUTaggedTQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(PUTaggedTPeer::CREATED_AT);
    }

    /**
     * Order by create date asc
     *
     * @return     PUTaggedTQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(PUTaggedTPeer::CREATED_AT);
    }
}
