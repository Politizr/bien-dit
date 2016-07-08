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
use Politizr\Model\PUTrackU;
use Politizr\Model\PUTrackUPeer;
use Politizr\Model\PUTrackUQuery;
use Politizr\Model\PUser;

/**
 * @method PUTrackUQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method PUTrackUQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 * @method PUTrackUQuery orderByPUserIdSource($order = Criteria::ASC) Order by the p_user_id_source column
 * @method PUTrackUQuery orderByPUserIdDest($order = Criteria::ASC) Order by the p_user_id_dest column
 *
 * @method PUTrackUQuery groupByCreatedAt() Group by the created_at column
 * @method PUTrackUQuery groupByUpdatedAt() Group by the updated_at column
 * @method PUTrackUQuery groupByPUserIdSource() Group by the p_user_id_source column
 * @method PUTrackUQuery groupByPUserIdDest() Group by the p_user_id_dest column
 *
 * @method PUTrackUQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method PUTrackUQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method PUTrackUQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method PUTrackUQuery leftJoinPUserRelatedByPUserIdSource($relationAlias = null) Adds a LEFT JOIN clause to the query using the PUserRelatedByPUserIdSource relation
 * @method PUTrackUQuery rightJoinPUserRelatedByPUserIdSource($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PUserRelatedByPUserIdSource relation
 * @method PUTrackUQuery innerJoinPUserRelatedByPUserIdSource($relationAlias = null) Adds a INNER JOIN clause to the query using the PUserRelatedByPUserIdSource relation
 *
 * @method PUTrackUQuery leftJoinPUserRelatedByPUserIdDest($relationAlias = null) Adds a LEFT JOIN clause to the query using the PUserRelatedByPUserIdDest relation
 * @method PUTrackUQuery rightJoinPUserRelatedByPUserIdDest($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PUserRelatedByPUserIdDest relation
 * @method PUTrackUQuery innerJoinPUserRelatedByPUserIdDest($relationAlias = null) Adds a INNER JOIN clause to the query using the PUserRelatedByPUserIdDest relation
 *
 * @method PUTrackU findOne(PropelPDO $con = null) Return the first PUTrackU matching the query
 * @method PUTrackU findOneOrCreate(PropelPDO $con = null) Return the first PUTrackU matching the query, or a new PUTrackU object populated from the query conditions when no match is found
 *
 * @method PUTrackU findOneByCreatedAt(string $created_at) Return the first PUTrackU filtered by the created_at column
 * @method PUTrackU findOneByUpdatedAt(string $updated_at) Return the first PUTrackU filtered by the updated_at column
 * @method PUTrackU findOneByPUserIdSource(int $p_user_id_source) Return the first PUTrackU filtered by the p_user_id_source column
 * @method PUTrackU findOneByPUserIdDest(int $p_user_id_dest) Return the first PUTrackU filtered by the p_user_id_dest column
 *
 * @method array findByCreatedAt(string $created_at) Return PUTrackU objects filtered by the created_at column
 * @method array findByUpdatedAt(string $updated_at) Return PUTrackU objects filtered by the updated_at column
 * @method array findByPUserIdSource(int $p_user_id_source) Return PUTrackU objects filtered by the p_user_id_source column
 * @method array findByPUserIdDest(int $p_user_id_dest) Return PUTrackU objects filtered by the p_user_id_dest column
 */
abstract class BasePUTrackUQuery extends ModelCriteria
{
    // query_cache behavior
    protected $queryKey = '';

    /**
     * Initializes internal state of BasePUTrackUQuery object.
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
            $modelName = 'Politizr\\Model\\PUTrackU';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new PUTrackUQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   PUTrackUQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return PUTrackUQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof PUTrackUQuery) {
            return $criteria;
        }
        $query = new PUTrackUQuery(null, null, $modelAlias);

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
                         A Primary key composition: [$p_user_id_source, $p_user_id_dest]
     * @param     PropelPDO $con an optional connection object
     *
     * @return   PUTrackU|PUTrackU[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = PUTrackUPeer::getInstanceFromPool(serialize(array((string) $key[0], (string) $key[1]))))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(PUTrackUPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 PUTrackU A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `created_at`, `updated_at`, `p_user_id_source`, `p_user_id_dest` FROM `p_u_track_u` WHERE `p_user_id_source` = :p0 AND `p_user_id_dest` = :p1';
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
            $obj = new PUTrackU();
            $obj->hydrate($row);
            PUTrackUPeer::addInstanceToPool($obj, serialize(array((string) $key[0], (string) $key[1])));
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
     * @return PUTrackU|PUTrackU[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|PUTrackU[]|mixed the list of results, formatted by the current formatter
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
     * @return PUTrackUQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {
        $this->addUsingAlias(PUTrackUPeer::P_USER_ID_SOURCE, $key[0], Criteria::EQUAL);
        $this->addUsingAlias(PUTrackUPeer::P_USER_ID_DEST, $key[1], Criteria::EQUAL);

        return $this;
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return PUTrackUQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {
        if (empty($keys)) {
            return $this->add(null, '1<>1', Criteria::CUSTOM);
        }
        foreach ($keys as $key) {
            $cton0 = $this->getNewCriterion(PUTrackUPeer::P_USER_ID_SOURCE, $key[0], Criteria::EQUAL);
            $cton1 = $this->getNewCriterion(PUTrackUPeer::P_USER_ID_DEST, $key[1], Criteria::EQUAL);
            $cton0->addAnd($cton1);
            $this->addOr($cton0);
        }

        return $this;
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
     * @return PUTrackUQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(PUTrackUPeer::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(PUTrackUPeer::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PUTrackUPeer::CREATED_AT, $createdAt, $comparison);
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
     * @return PUTrackUQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(PUTrackUPeer::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(PUTrackUPeer::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PUTrackUPeer::UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query on the p_user_id_source column
     *
     * Example usage:
     * <code>
     * $query->filterByPUserIdSource(1234); // WHERE p_user_id_source = 1234
     * $query->filterByPUserIdSource(array(12, 34)); // WHERE p_user_id_source IN (12, 34)
     * $query->filterByPUserIdSource(array('min' => 12)); // WHERE p_user_id_source >= 12
     * $query->filterByPUserIdSource(array('max' => 12)); // WHERE p_user_id_source <= 12
     * </code>
     *
     * @see       filterByPUserRelatedByPUserIdSource()
     *
     * @param     mixed $pUserIdSource The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PUTrackUQuery The current query, for fluid interface
     */
    public function filterByPUserIdSource($pUserIdSource = null, $comparison = null)
    {
        if (is_array($pUserIdSource)) {
            $useMinMax = false;
            if (isset($pUserIdSource['min'])) {
                $this->addUsingAlias(PUTrackUPeer::P_USER_ID_SOURCE, $pUserIdSource['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($pUserIdSource['max'])) {
                $this->addUsingAlias(PUTrackUPeer::P_USER_ID_SOURCE, $pUserIdSource['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PUTrackUPeer::P_USER_ID_SOURCE, $pUserIdSource, $comparison);
    }

    /**
     * Filter the query on the p_user_id_dest column
     *
     * Example usage:
     * <code>
     * $query->filterByPUserIdDest(1234); // WHERE p_user_id_dest = 1234
     * $query->filterByPUserIdDest(array(12, 34)); // WHERE p_user_id_dest IN (12, 34)
     * $query->filterByPUserIdDest(array('min' => 12)); // WHERE p_user_id_dest >= 12
     * $query->filterByPUserIdDest(array('max' => 12)); // WHERE p_user_id_dest <= 12
     * </code>
     *
     * @see       filterByPUserRelatedByPUserIdDest()
     *
     * @param     mixed $pUserIdDest The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PUTrackUQuery The current query, for fluid interface
     */
    public function filterByPUserIdDest($pUserIdDest = null, $comparison = null)
    {
        if (is_array($pUserIdDest)) {
            $useMinMax = false;
            if (isset($pUserIdDest['min'])) {
                $this->addUsingAlias(PUTrackUPeer::P_USER_ID_DEST, $pUserIdDest['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($pUserIdDest['max'])) {
                $this->addUsingAlias(PUTrackUPeer::P_USER_ID_DEST, $pUserIdDest['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PUTrackUPeer::P_USER_ID_DEST, $pUserIdDest, $comparison);
    }

    /**
     * Filter the query by a related PUser object
     *
     * @param   PUser|PropelObjectCollection $pUser The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PUTrackUQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPUserRelatedByPUserIdSource($pUser, $comparison = null)
    {
        if ($pUser instanceof PUser) {
            return $this
                ->addUsingAlias(PUTrackUPeer::P_USER_ID_SOURCE, $pUser->getId(), $comparison);
        } elseif ($pUser instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PUTrackUPeer::P_USER_ID_SOURCE, $pUser->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByPUserRelatedByPUserIdSource() only accepts arguments of type PUser or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PUserRelatedByPUserIdSource relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PUTrackUQuery The current query, for fluid interface
     */
    public function joinPUserRelatedByPUserIdSource($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PUserRelatedByPUserIdSource');

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
            $this->addJoinObject($join, 'PUserRelatedByPUserIdSource');
        }

        return $this;
    }

    /**
     * Use the PUserRelatedByPUserIdSource relation PUser object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Politizr\Model\PUserQuery A secondary query class using the current class as primary query
     */
    public function usePUserRelatedByPUserIdSourceQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPUserRelatedByPUserIdSource($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PUserRelatedByPUserIdSource', '\Politizr\Model\PUserQuery');
    }

    /**
     * Filter the query by a related PUser object
     *
     * @param   PUser|PropelObjectCollection $pUser The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PUTrackUQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPUserRelatedByPUserIdDest($pUser, $comparison = null)
    {
        if ($pUser instanceof PUser) {
            return $this
                ->addUsingAlias(PUTrackUPeer::P_USER_ID_DEST, $pUser->getId(), $comparison);
        } elseif ($pUser instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PUTrackUPeer::P_USER_ID_DEST, $pUser->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByPUserRelatedByPUserIdDest() only accepts arguments of type PUser or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PUserRelatedByPUserIdDest relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PUTrackUQuery The current query, for fluid interface
     */
    public function joinPUserRelatedByPUserIdDest($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PUserRelatedByPUserIdDest');

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
            $this->addJoinObject($join, 'PUserRelatedByPUserIdDest');
        }

        return $this;
    }

    /**
     * Use the PUserRelatedByPUserIdDest relation PUser object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Politizr\Model\PUserQuery A secondary query class using the current class as primary query
     */
    public function usePUserRelatedByPUserIdDestQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPUserRelatedByPUserIdDest($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PUserRelatedByPUserIdDest', '\Politizr\Model\PUserQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   PUTrackU $pUTrackU Object to remove from the list of results
     *
     * @return PUTrackUQuery The current query, for fluid interface
     */
    public function prune($pUTrackU = null)
    {
        if ($pUTrackU) {
            $this->addCond('pruneCond0', $this->getAliasedColName(PUTrackUPeer::P_USER_ID_SOURCE), $pUTrackU->getPUserIdSource(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond1', $this->getAliasedColName(PUTrackUPeer::P_USER_ID_DEST), $pUTrackU->getPUserIdDest(), Criteria::NOT_EQUAL);
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
     * @return     PUTrackUQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(PUTrackUPeer::UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     PUTrackUQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(PUTrackUPeer::UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     PUTrackUQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(PUTrackUPeer::UPDATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     PUTrackUQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(PUTrackUPeer::CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date desc
     *
     * @return     PUTrackUQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(PUTrackUPeer::CREATED_AT);
    }

    /**
     * Order by create date asc
     *
     * @return     PUTrackUQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(PUTrackUPeer::CREATED_AT);
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

        $dbMap = Propel::getDatabaseMap(PUTrackUPeer::DATABASE_NAME);
        $db = Propel::getDB(PUTrackUPeer::DATABASE_NAME);

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

    // equal_nest behavior

    /**
     * Filter the query by 2 PUser objects for a Equal Nest PUTrackU relation
     *
     * @param      PUser|integer $object1
     * @param      PUser|integer $object2
     * @return     PUTrackUQuery Fluent API
     */
    public function filterByPUsers($object1, $object2)
    {
        return $this
            ->condition('first-one', 'Politizr\Model\PUTrackU.PUserIdSource = ?', is_object($object1) ? $object1->getPrimaryKey() : $object1)
            ->condition('first-two', 'Politizr\Model\PUTrackU.PUserIdDest = ?', is_object($object2) ? $object2->getPrimaryKey() : $object2)
            ->condition('second-one', 'Politizr\Model\PUTrackU.PUserIdDest = ?', is_object($object1) ? $object1->getPrimaryKey() : $object1)
            ->condition('second-two', 'Politizr\Model\PUTrackU.PUserIdSource = ?', is_object($object2) ? $object2->getPrimaryKey() : $object2)
            ->combine(array('first-one',  'first-two'),  'AND', 'first')
            ->combine(array('second-one', 'second-two'), 'AND', 'second')
            ->where(array('first', 'second'), 'OR');
    }

}
