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
use Politizr\Model\PNotification;
use Politizr\Model\PNotificationPeer;
use Politizr\Model\PNotificationQuery;
use Politizr\Model\PUNotifications;
use Politizr\Model\PUSubscribeEmail;
use Politizr\Model\PUSubscribeScreen;
use Politizr\Model\PUser;

/**
 * @method PNotificationQuery orderById($order = Criteria::ASC) Order by the id column
 * @method PNotificationQuery orderByTitle($order = Criteria::ASC) Order by the title column
 * @method PNotificationQuery orderByDescription($order = Criteria::ASC) Order by the description column
 * @method PNotificationQuery orderByOnline($order = Criteria::ASC) Order by the online column
 * @method PNotificationQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method PNotificationQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method PNotificationQuery groupById() Group by the id column
 * @method PNotificationQuery groupByTitle() Group by the title column
 * @method PNotificationQuery groupByDescription() Group by the description column
 * @method PNotificationQuery groupByOnline() Group by the online column
 * @method PNotificationQuery groupByCreatedAt() Group by the created_at column
 * @method PNotificationQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method PNotificationQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method PNotificationQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method PNotificationQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method PNotificationQuery leftJoinPUNotificationsPNotification($relationAlias = null) Adds a LEFT JOIN clause to the query using the PUNotificationsPNotification relation
 * @method PNotificationQuery rightJoinPUNotificationsPNotification($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PUNotificationsPNotification relation
 * @method PNotificationQuery innerJoinPUNotificationsPNotification($relationAlias = null) Adds a INNER JOIN clause to the query using the PUNotificationsPNotification relation
 *
 * @method PNotificationQuery leftJoinPUSubscribeEmailPNotification($relationAlias = null) Adds a LEFT JOIN clause to the query using the PUSubscribeEmailPNotification relation
 * @method PNotificationQuery rightJoinPUSubscribeEmailPNotification($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PUSubscribeEmailPNotification relation
 * @method PNotificationQuery innerJoinPUSubscribeEmailPNotification($relationAlias = null) Adds a INNER JOIN clause to the query using the PUSubscribeEmailPNotification relation
 *
 * @method PNotificationQuery leftJoinPUSubscribeScreenPNotification($relationAlias = null) Adds a LEFT JOIN clause to the query using the PUSubscribeScreenPNotification relation
 * @method PNotificationQuery rightJoinPUSubscribeScreenPNotification($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PUSubscribeScreenPNotification relation
 * @method PNotificationQuery innerJoinPUSubscribeScreenPNotification($relationAlias = null) Adds a INNER JOIN clause to the query using the PUSubscribeScreenPNotification relation
 *
 * @method PNotification findOne(PropelPDO $con = null) Return the first PNotification matching the query
 * @method PNotification findOneOrCreate(PropelPDO $con = null) Return the first PNotification matching the query, or a new PNotification object populated from the query conditions when no match is found
 *
 * @method PNotification findOneByTitle(string $title) Return the first PNotification filtered by the title column
 * @method PNotification findOneByDescription(string $description) Return the first PNotification filtered by the description column
 * @method PNotification findOneByOnline(boolean $online) Return the first PNotification filtered by the online column
 * @method PNotification findOneByCreatedAt(string $created_at) Return the first PNotification filtered by the created_at column
 * @method PNotification findOneByUpdatedAt(string $updated_at) Return the first PNotification filtered by the updated_at column
 *
 * @method array findById(int $id) Return PNotification objects filtered by the id column
 * @method array findByTitle(string $title) Return PNotification objects filtered by the title column
 * @method array findByDescription(string $description) Return PNotification objects filtered by the description column
 * @method array findByOnline(boolean $online) Return PNotification objects filtered by the online column
 * @method array findByCreatedAt(string $created_at) Return PNotification objects filtered by the created_at column
 * @method array findByUpdatedAt(string $updated_at) Return PNotification objects filtered by the updated_at column
 */
abstract class BasePNotificationQuery extends ModelCriteria
{
    // query_cache behavior
    protected $queryKey = '';

    /**
     * Initializes internal state of BasePNotificationQuery object.
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
            $modelName = 'Politizr\\Model\\PNotification';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new PNotificationQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   PNotificationQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return PNotificationQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof PNotificationQuery) {
            return $criteria;
        }
        $query = new PNotificationQuery(null, null, $modelAlias);

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
     * @return   PNotification|PNotification[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = PNotificationPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(PNotificationPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 PNotification A model object, or null if the key is not found
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
     * @return                 PNotification A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `title`, `description`, `online`, `created_at`, `updated_at` FROM `p_notification` WHERE `id` = :p0';
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
            $obj = new PNotification();
            $obj->hydrate($row);
            PNotificationPeer::addInstanceToPool($obj, (string) $key);
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
     * @return PNotification|PNotification[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|PNotification[]|mixed the list of results, formatted by the current formatter
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
     * @return PNotificationQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(PNotificationPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return PNotificationQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(PNotificationPeer::ID, $keys, Criteria::IN);
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
     * @return PNotificationQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(PNotificationPeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(PNotificationPeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PNotificationPeer::ID, $id, $comparison);
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
     * @return PNotificationQuery The current query, for fluid interface
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

        return $this->addUsingAlias(PNotificationPeer::TITLE, $title, $comparison);
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
     * @return PNotificationQuery The current query, for fluid interface
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

        return $this->addUsingAlias(PNotificationPeer::DESCRIPTION, $description, $comparison);
    }

    /**
     * Filter the query on the online column
     *
     * Example usage:
     * <code>
     * $query->filterByOnline(true); // WHERE online = true
     * $query->filterByOnline('yes'); // WHERE online = true
     * </code>
     *
     * @param     boolean|string $online The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PNotificationQuery The current query, for fluid interface
     */
    public function filterByOnline($online = null, $comparison = null)
    {
        if (is_string($online)) {
            $online = in_array(strtolower($online), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(PNotificationPeer::ONLINE, $online, $comparison);
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
     * @return PNotificationQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(PNotificationPeer::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(PNotificationPeer::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PNotificationPeer::CREATED_AT, $createdAt, $comparison);
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
     * @return PNotificationQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(PNotificationPeer::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(PNotificationPeer::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PNotificationPeer::UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query by a related PUNotifications object
     *
     * @param   PUNotifications|PropelObjectCollection $pUNotifications  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PNotificationQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPUNotificationsPNotification($pUNotifications, $comparison = null)
    {
        if ($pUNotifications instanceof PUNotifications) {
            return $this
                ->addUsingAlias(PNotificationPeer::ID, $pUNotifications->getPNotificationId(), $comparison);
        } elseif ($pUNotifications instanceof PropelObjectCollection) {
            return $this
                ->usePUNotificationsPNotificationQuery()
                ->filterByPrimaryKeys($pUNotifications->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByPUNotificationsPNotification() only accepts arguments of type PUNotifications or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PUNotificationsPNotification relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PNotificationQuery The current query, for fluid interface
     */
    public function joinPUNotificationsPNotification($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PUNotificationsPNotification');

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
            $this->addJoinObject($join, 'PUNotificationsPNotification');
        }

        return $this;
    }

    /**
     * Use the PUNotificationsPNotification relation PUNotifications object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Politizr\Model\PUNotificationsQuery A secondary query class using the current class as primary query
     */
    public function usePUNotificationsPNotificationQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPUNotificationsPNotification($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PUNotificationsPNotification', '\Politizr\Model\PUNotificationsQuery');
    }

    /**
     * Filter the query by a related PUSubscribeEmail object
     *
     * @param   PUSubscribeEmail|PropelObjectCollection $pUSubscribeEmail  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PNotificationQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPUSubscribeEmailPNotification($pUSubscribeEmail, $comparison = null)
    {
        if ($pUSubscribeEmail instanceof PUSubscribeEmail) {
            return $this
                ->addUsingAlias(PNotificationPeer::ID, $pUSubscribeEmail->getPNotificationId(), $comparison);
        } elseif ($pUSubscribeEmail instanceof PropelObjectCollection) {
            return $this
                ->usePUSubscribeEmailPNotificationQuery()
                ->filterByPrimaryKeys($pUSubscribeEmail->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByPUSubscribeEmailPNotification() only accepts arguments of type PUSubscribeEmail or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PUSubscribeEmailPNotification relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PNotificationQuery The current query, for fluid interface
     */
    public function joinPUSubscribeEmailPNotification($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PUSubscribeEmailPNotification');

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
            $this->addJoinObject($join, 'PUSubscribeEmailPNotification');
        }

        return $this;
    }

    /**
     * Use the PUSubscribeEmailPNotification relation PUSubscribeEmail object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Politizr\Model\PUSubscribeEmailQuery A secondary query class using the current class as primary query
     */
    public function usePUSubscribeEmailPNotificationQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPUSubscribeEmailPNotification($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PUSubscribeEmailPNotification', '\Politizr\Model\PUSubscribeEmailQuery');
    }

    /**
     * Filter the query by a related PUSubscribeScreen object
     *
     * @param   PUSubscribeScreen|PropelObjectCollection $pUSubscribeScreen  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PNotificationQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPUSubscribeScreenPNotification($pUSubscribeScreen, $comparison = null)
    {
        if ($pUSubscribeScreen instanceof PUSubscribeScreen) {
            return $this
                ->addUsingAlias(PNotificationPeer::ID, $pUSubscribeScreen->getPNotificationId(), $comparison);
        } elseif ($pUSubscribeScreen instanceof PropelObjectCollection) {
            return $this
                ->usePUSubscribeScreenPNotificationQuery()
                ->filterByPrimaryKeys($pUSubscribeScreen->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByPUSubscribeScreenPNotification() only accepts arguments of type PUSubscribeScreen or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PUSubscribeScreenPNotification relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PNotificationQuery The current query, for fluid interface
     */
    public function joinPUSubscribeScreenPNotification($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PUSubscribeScreenPNotification');

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
            $this->addJoinObject($join, 'PUSubscribeScreenPNotification');
        }

        return $this;
    }

    /**
     * Use the PUSubscribeScreenPNotification relation PUSubscribeScreen object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Politizr\Model\PUSubscribeScreenQuery A secondary query class using the current class as primary query
     */
    public function usePUSubscribeScreenPNotificationQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPUSubscribeScreenPNotification($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PUSubscribeScreenPNotification', '\Politizr\Model\PUSubscribeScreenQuery');
    }

    /**
     * Filter the query by a related PUser object
     * using the p_u_notifications table as cross reference
     *
     * @param   PUser $pUser the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   PNotificationQuery The current query, for fluid interface
     */
    public function filterByPUNotificationsPUser($pUser, $comparison = Criteria::EQUAL)
    {
        return $this
            ->usePUNotificationsPNotificationQuery()
            ->filterByPUNotificationsPUser($pUser, $comparison)
            ->endUse();
    }

    /**
     * Filter the query by a related PUser object
     * using the p_u_subscribe_email table as cross reference
     *
     * @param   PUser $pUser the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   PNotificationQuery The current query, for fluid interface
     */
    public function filterByPUSubscribeEmailPUser($pUser, $comparison = Criteria::EQUAL)
    {
        return $this
            ->usePUSubscribeEmailPNotificationQuery()
            ->filterByPUSubscribeEmailPUser($pUser, $comparison)
            ->endUse();
    }

    /**
     * Filter the query by a related PUser object
     * using the p_u_subscribe_screen table as cross reference
     *
     * @param   PUser $pUser the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   PNotificationQuery The current query, for fluid interface
     */
    public function filterByPUSubscribeScreenPUser($pUser, $comparison = Criteria::EQUAL)
    {
        return $this
            ->usePUSubscribeScreenPNotificationQuery()
            ->filterByPUSubscribeScreenPUser($pUser, $comparison)
            ->endUse();
    }

    /**
     * Exclude object from result
     *
     * @param   PNotification $pNotification Object to remove from the list of results
     *
     * @return PNotificationQuery The current query, for fluid interface
     */
    public function prune($pNotification = null)
    {
        if ($pNotification) {
            $this->addUsingAlias(PNotificationPeer::ID, $pNotification->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     PNotificationQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(PNotificationPeer::UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     PNotificationQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(PNotificationPeer::UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     PNotificationQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(PNotificationPeer::UPDATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     PNotificationQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(PNotificationPeer::CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date desc
     *
     * @return     PNotificationQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(PNotificationPeer::CREATED_AT);
    }

    /**
     * Order by create date asc
     *
     * @return     PNotificationQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(PNotificationPeer::CREATED_AT);
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

        $dbMap = Propel::getDatabaseMap(PNotificationPeer::DATABASE_NAME);
        $db = Propel::getDB(PNotificationPeer::DATABASE_NAME);

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
