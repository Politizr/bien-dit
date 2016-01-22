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
use Politizr\Model\PUFollowU;
use Politizr\Model\PUFollowUPeer;
use Politizr\Model\PUFollowUQuery;
use Politizr\Model\PUser;

/**
 * @method PUFollowUQuery orderByNotifDebate($order = Criteria::ASC) Order by the notif_debate column
 * @method PUFollowUQuery orderByNotifReaction($order = Criteria::ASC) Order by the notif_reaction column
 * @method PUFollowUQuery orderByNotifComment($order = Criteria::ASC) Order by the notif_comment column
 * @method PUFollowUQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method PUFollowUQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 * @method PUFollowUQuery orderByPUserId($order = Criteria::ASC) Order by the p_user_id column
 * @method PUFollowUQuery orderByPUserFollowerId($order = Criteria::ASC) Order by the p_user_follower_id column
 *
 * @method PUFollowUQuery groupByNotifDebate() Group by the notif_debate column
 * @method PUFollowUQuery groupByNotifReaction() Group by the notif_reaction column
 * @method PUFollowUQuery groupByNotifComment() Group by the notif_comment column
 * @method PUFollowUQuery groupByCreatedAt() Group by the created_at column
 * @method PUFollowUQuery groupByUpdatedAt() Group by the updated_at column
 * @method PUFollowUQuery groupByPUserId() Group by the p_user_id column
 * @method PUFollowUQuery groupByPUserFollowerId() Group by the p_user_follower_id column
 *
 * @method PUFollowUQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method PUFollowUQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method PUFollowUQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method PUFollowUQuery leftJoinPUserRelatedByPUserId($relationAlias = null) Adds a LEFT JOIN clause to the query using the PUserRelatedByPUserId relation
 * @method PUFollowUQuery rightJoinPUserRelatedByPUserId($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PUserRelatedByPUserId relation
 * @method PUFollowUQuery innerJoinPUserRelatedByPUserId($relationAlias = null) Adds a INNER JOIN clause to the query using the PUserRelatedByPUserId relation
 *
 * @method PUFollowUQuery leftJoinPUserRelatedByPUserFollowerId($relationAlias = null) Adds a LEFT JOIN clause to the query using the PUserRelatedByPUserFollowerId relation
 * @method PUFollowUQuery rightJoinPUserRelatedByPUserFollowerId($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PUserRelatedByPUserFollowerId relation
 * @method PUFollowUQuery innerJoinPUserRelatedByPUserFollowerId($relationAlias = null) Adds a INNER JOIN clause to the query using the PUserRelatedByPUserFollowerId relation
 *
 * @method PUFollowU findOne(PropelPDO $con = null) Return the first PUFollowU matching the query
 * @method PUFollowU findOneOrCreate(PropelPDO $con = null) Return the first PUFollowU matching the query, or a new PUFollowU object populated from the query conditions when no match is found
 *
 * @method PUFollowU findOneByNotifDebate(boolean $notif_debate) Return the first PUFollowU filtered by the notif_debate column
 * @method PUFollowU findOneByNotifReaction(boolean $notif_reaction) Return the first PUFollowU filtered by the notif_reaction column
 * @method PUFollowU findOneByNotifComment(boolean $notif_comment) Return the first PUFollowU filtered by the notif_comment column
 * @method PUFollowU findOneByCreatedAt(string $created_at) Return the first PUFollowU filtered by the created_at column
 * @method PUFollowU findOneByUpdatedAt(string $updated_at) Return the first PUFollowU filtered by the updated_at column
 * @method PUFollowU findOneByPUserId(int $p_user_id) Return the first PUFollowU filtered by the p_user_id column
 * @method PUFollowU findOneByPUserFollowerId(int $p_user_follower_id) Return the first PUFollowU filtered by the p_user_follower_id column
 *
 * @method array findByNotifDebate(boolean $notif_debate) Return PUFollowU objects filtered by the notif_debate column
 * @method array findByNotifReaction(boolean $notif_reaction) Return PUFollowU objects filtered by the notif_reaction column
 * @method array findByNotifComment(boolean $notif_comment) Return PUFollowU objects filtered by the notif_comment column
 * @method array findByCreatedAt(string $created_at) Return PUFollowU objects filtered by the created_at column
 * @method array findByUpdatedAt(string $updated_at) Return PUFollowU objects filtered by the updated_at column
 * @method array findByPUserId(int $p_user_id) Return PUFollowU objects filtered by the p_user_id column
 * @method array findByPUserFollowerId(int $p_user_follower_id) Return PUFollowU objects filtered by the p_user_follower_id column
 */
abstract class BasePUFollowUQuery extends ModelCriteria
{
    // query_cache behavior
    protected $queryKey = '';

    /**
     * Initializes internal state of BasePUFollowUQuery object.
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
            $modelName = 'Politizr\\Model\\PUFollowU';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new PUFollowUQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   PUFollowUQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return PUFollowUQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof PUFollowUQuery) {
            return $criteria;
        }
        $query = new PUFollowUQuery(null, null, $modelAlias);

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
                         A Primary key composition: [$p_user_id, $p_user_follower_id]
     * @param     PropelPDO $con an optional connection object
     *
     * @return   PUFollowU|PUFollowU[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = PUFollowUPeer::getInstanceFromPool(serialize(array((string) $key[0], (string) $key[1]))))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(PUFollowUPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 PUFollowU A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `notif_debate`, `notif_reaction`, `notif_comment`, `created_at`, `updated_at`, `p_user_id`, `p_user_follower_id` FROM `p_u_follow_u` WHERE `p_user_id` = :p0 AND `p_user_follower_id` = :p1';
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
            $obj = new PUFollowU();
            $obj->hydrate($row);
            PUFollowUPeer::addInstanceToPool($obj, serialize(array((string) $key[0], (string) $key[1])));
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
     * @return PUFollowU|PUFollowU[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|PUFollowU[]|mixed the list of results, formatted by the current formatter
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
     * @return PUFollowUQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {
        $this->addUsingAlias(PUFollowUPeer::P_USER_ID, $key[0], Criteria::EQUAL);
        $this->addUsingAlias(PUFollowUPeer::P_USER_FOLLOWER_ID, $key[1], Criteria::EQUAL);

        return $this;
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return PUFollowUQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {
        if (empty($keys)) {
            return $this->add(null, '1<>1', Criteria::CUSTOM);
        }
        foreach ($keys as $key) {
            $cton0 = $this->getNewCriterion(PUFollowUPeer::P_USER_ID, $key[0], Criteria::EQUAL);
            $cton1 = $this->getNewCriterion(PUFollowUPeer::P_USER_FOLLOWER_ID, $key[1], Criteria::EQUAL);
            $cton0->addAnd($cton1);
            $this->addOr($cton0);
        }

        return $this;
    }

    /**
     * Filter the query on the notif_debate column
     *
     * Example usage:
     * <code>
     * $query->filterByNotifDebate(true); // WHERE notif_debate = true
     * $query->filterByNotifDebate('yes'); // WHERE notif_debate = true
     * </code>
     *
     * @param     boolean|string $notifDebate The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PUFollowUQuery The current query, for fluid interface
     */
    public function filterByNotifDebate($notifDebate = null, $comparison = null)
    {
        if (is_string($notifDebate)) {
            $notifDebate = in_array(strtolower($notifDebate), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(PUFollowUPeer::NOTIF_DEBATE, $notifDebate, $comparison);
    }

    /**
     * Filter the query on the notif_reaction column
     *
     * Example usage:
     * <code>
     * $query->filterByNotifReaction(true); // WHERE notif_reaction = true
     * $query->filterByNotifReaction('yes'); // WHERE notif_reaction = true
     * </code>
     *
     * @param     boolean|string $notifReaction The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PUFollowUQuery The current query, for fluid interface
     */
    public function filterByNotifReaction($notifReaction = null, $comparison = null)
    {
        if (is_string($notifReaction)) {
            $notifReaction = in_array(strtolower($notifReaction), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(PUFollowUPeer::NOTIF_REACTION, $notifReaction, $comparison);
    }

    /**
     * Filter the query on the notif_comment column
     *
     * Example usage:
     * <code>
     * $query->filterByNotifComment(true); // WHERE notif_comment = true
     * $query->filterByNotifComment('yes'); // WHERE notif_comment = true
     * </code>
     *
     * @param     boolean|string $notifComment The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PUFollowUQuery The current query, for fluid interface
     */
    public function filterByNotifComment($notifComment = null, $comparison = null)
    {
        if (is_string($notifComment)) {
            $notifComment = in_array(strtolower($notifComment), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(PUFollowUPeer::NOTIF_COMMENT, $notifComment, $comparison);
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
     * @return PUFollowUQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(PUFollowUPeer::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(PUFollowUPeer::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PUFollowUPeer::CREATED_AT, $createdAt, $comparison);
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
     * @return PUFollowUQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(PUFollowUPeer::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(PUFollowUPeer::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PUFollowUPeer::UPDATED_AT, $updatedAt, $comparison);
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
     * @see       filterByPUserRelatedByPUserId()
     *
     * @param     mixed $pUserId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PUFollowUQuery The current query, for fluid interface
     */
    public function filterByPUserId($pUserId = null, $comparison = null)
    {
        if (is_array($pUserId)) {
            $useMinMax = false;
            if (isset($pUserId['min'])) {
                $this->addUsingAlias(PUFollowUPeer::P_USER_ID, $pUserId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($pUserId['max'])) {
                $this->addUsingAlias(PUFollowUPeer::P_USER_ID, $pUserId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PUFollowUPeer::P_USER_ID, $pUserId, $comparison);
    }

    /**
     * Filter the query on the p_user_follower_id column
     *
     * Example usage:
     * <code>
     * $query->filterByPUserFollowerId(1234); // WHERE p_user_follower_id = 1234
     * $query->filterByPUserFollowerId(array(12, 34)); // WHERE p_user_follower_id IN (12, 34)
     * $query->filterByPUserFollowerId(array('min' => 12)); // WHERE p_user_follower_id >= 12
     * $query->filterByPUserFollowerId(array('max' => 12)); // WHERE p_user_follower_id <= 12
     * </code>
     *
     * @see       filterByPUserRelatedByPUserFollowerId()
     *
     * @param     mixed $pUserFollowerId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PUFollowUQuery The current query, for fluid interface
     */
    public function filterByPUserFollowerId($pUserFollowerId = null, $comparison = null)
    {
        if (is_array($pUserFollowerId)) {
            $useMinMax = false;
            if (isset($pUserFollowerId['min'])) {
                $this->addUsingAlias(PUFollowUPeer::P_USER_FOLLOWER_ID, $pUserFollowerId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($pUserFollowerId['max'])) {
                $this->addUsingAlias(PUFollowUPeer::P_USER_FOLLOWER_ID, $pUserFollowerId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PUFollowUPeer::P_USER_FOLLOWER_ID, $pUserFollowerId, $comparison);
    }

    /**
     * Filter the query by a related PUser object
     *
     * @param   PUser|PropelObjectCollection $pUser The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PUFollowUQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPUserRelatedByPUserId($pUser, $comparison = null)
    {
        if ($pUser instanceof PUser) {
            return $this
                ->addUsingAlias(PUFollowUPeer::P_USER_ID, $pUser->getId(), $comparison);
        } elseif ($pUser instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PUFollowUPeer::P_USER_ID, $pUser->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByPUserRelatedByPUserId() only accepts arguments of type PUser or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PUserRelatedByPUserId relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PUFollowUQuery The current query, for fluid interface
     */
    public function joinPUserRelatedByPUserId($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PUserRelatedByPUserId');

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
            $this->addJoinObject($join, 'PUserRelatedByPUserId');
        }

        return $this;
    }

    /**
     * Use the PUserRelatedByPUserId relation PUser object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Politizr\Model\PUserQuery A secondary query class using the current class as primary query
     */
    public function usePUserRelatedByPUserIdQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPUserRelatedByPUserId($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PUserRelatedByPUserId', '\Politizr\Model\PUserQuery');
    }

    /**
     * Filter the query by a related PUser object
     *
     * @param   PUser|PropelObjectCollection $pUser The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PUFollowUQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPUserRelatedByPUserFollowerId($pUser, $comparison = null)
    {
        if ($pUser instanceof PUser) {
            return $this
                ->addUsingAlias(PUFollowUPeer::P_USER_FOLLOWER_ID, $pUser->getId(), $comparison);
        } elseif ($pUser instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PUFollowUPeer::P_USER_FOLLOWER_ID, $pUser->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByPUserRelatedByPUserFollowerId() only accepts arguments of type PUser or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PUserRelatedByPUserFollowerId relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PUFollowUQuery The current query, for fluid interface
     */
    public function joinPUserRelatedByPUserFollowerId($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PUserRelatedByPUserFollowerId');

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
            $this->addJoinObject($join, 'PUserRelatedByPUserFollowerId');
        }

        return $this;
    }

    /**
     * Use the PUserRelatedByPUserFollowerId relation PUser object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Politizr\Model\PUserQuery A secondary query class using the current class as primary query
     */
    public function usePUserRelatedByPUserFollowerIdQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPUserRelatedByPUserFollowerId($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PUserRelatedByPUserFollowerId', '\Politizr\Model\PUserQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   PUFollowU $pUFollowU Object to remove from the list of results
     *
     * @return PUFollowUQuery The current query, for fluid interface
     */
    public function prune($pUFollowU = null)
    {
        if ($pUFollowU) {
            $this->addCond('pruneCond0', $this->getAliasedColName(PUFollowUPeer::P_USER_ID), $pUFollowU->getPUserId(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond1', $this->getAliasedColName(PUFollowUPeer::P_USER_FOLLOWER_ID), $pUFollowU->getPUserFollowerId(), Criteria::NOT_EQUAL);
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
     * @return     PUFollowUQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(PUFollowUPeer::UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     PUFollowUQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(PUFollowUPeer::UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     PUFollowUQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(PUFollowUPeer::UPDATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     PUFollowUQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(PUFollowUPeer::CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date desc
     *
     * @return     PUFollowUQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(PUFollowUPeer::CREATED_AT);
    }

    /**
     * Order by create date asc
     *
     * @return     PUFollowUQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(PUFollowUPeer::CREATED_AT);
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

        $dbMap = Propel::getDatabaseMap(PUFollowUPeer::DATABASE_NAME);
        $db = Propel::getDB(PUFollowUPeer::DATABASE_NAME);

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
     * Filter the query by 2 PUser objects for a Equal Nest PUFollowU relation
     *
     * @param      PUser|integer $object1
     * @param      PUser|integer $object2
     * @return     PUFollowUQuery Fluent API
     */
    public function filterByPUsers($object1, $object2)
    {
        return $this
            ->condition('first-one', 'Politizr\Model\PUFollowU.PUserId = ?', is_object($object1) ? $object1->getPrimaryKey() : $object1)
            ->condition('first-two', 'Politizr\Model\PUFollowU.PUserFollowerId = ?', is_object($object2) ? $object2->getPrimaryKey() : $object2)
            ->condition('second-one', 'Politizr\Model\PUFollowU.PUserFollowerId = ?', is_object($object1) ? $object1->getPrimaryKey() : $object1)
            ->condition('second-two', 'Politizr\Model\PUFollowU.PUserId = ?', is_object($object2) ? $object2->getPrimaryKey() : $object2)
            ->combine(array('first-one',  'first-two'),  'AND', 'first')
            ->combine(array('second-one', 'second-two'), 'AND', 'second')
            ->where(array('first', 'second'), 'OR');
    }

}
