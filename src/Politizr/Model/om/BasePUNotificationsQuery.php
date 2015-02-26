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
use Glorpen\Propel\PropelBundle\Dispatcher\EventDispatcherProxy;
use Glorpen\Propel\PropelBundle\Events\QueryEvent;
use Politizr\Model\PNotification;
use Politizr\Model\PUNotifications;
use Politizr\Model\PUNotificationsPeer;
use Politizr\Model\PUNotificationsQuery;
use Politizr\Model\PUser;

/**
 * @method PUNotificationsQuery orderById($order = Criteria::ASC) Order by the id column
 * @method PUNotificationsQuery orderByPUserId($order = Criteria::ASC) Order by the p_user_id column
 * @method PUNotificationsQuery orderByPNotificationId($order = Criteria::ASC) Order by the p_notification_id column
 * @method PUNotificationsQuery orderByPObjectName($order = Criteria::ASC) Order by the p_object_name column
 * @method PUNotificationsQuery orderByPObjectId($order = Criteria::ASC) Order by the p_object_id column
 * @method PUNotificationsQuery orderByPAuthorUserId($order = Criteria::ASC) Order by the p_author_user_id column
 * @method PUNotificationsQuery orderByChecked($order = Criteria::ASC) Order by the checked column
 * @method PUNotificationsQuery orderByCheckedAt($order = Criteria::ASC) Order by the checked_at column
 * @method PUNotificationsQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method PUNotificationsQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method PUNotificationsQuery groupById() Group by the id column
 * @method PUNotificationsQuery groupByPUserId() Group by the p_user_id column
 * @method PUNotificationsQuery groupByPNotificationId() Group by the p_notification_id column
 * @method PUNotificationsQuery groupByPObjectName() Group by the p_object_name column
 * @method PUNotificationsQuery groupByPObjectId() Group by the p_object_id column
 * @method PUNotificationsQuery groupByPAuthorUserId() Group by the p_author_user_id column
 * @method PUNotificationsQuery groupByChecked() Group by the checked column
 * @method PUNotificationsQuery groupByCheckedAt() Group by the checked_at column
 * @method PUNotificationsQuery groupByCreatedAt() Group by the created_at column
 * @method PUNotificationsQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method PUNotificationsQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method PUNotificationsQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method PUNotificationsQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method PUNotificationsQuery leftJoinPUNotificationsPUser($relationAlias = null) Adds a LEFT JOIN clause to the query using the PUNotificationsPUser relation
 * @method PUNotificationsQuery rightJoinPUNotificationsPUser($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PUNotificationsPUser relation
 * @method PUNotificationsQuery innerJoinPUNotificationsPUser($relationAlias = null) Adds a INNER JOIN clause to the query using the PUNotificationsPUser relation
 *
 * @method PUNotificationsQuery leftJoinPUNotificationsPNotification($relationAlias = null) Adds a LEFT JOIN clause to the query using the PUNotificationsPNotification relation
 * @method PUNotificationsQuery rightJoinPUNotificationsPNotification($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PUNotificationsPNotification relation
 * @method PUNotificationsQuery innerJoinPUNotificationsPNotification($relationAlias = null) Adds a INNER JOIN clause to the query using the PUNotificationsPNotification relation
 *
 * @method PUNotifications findOne(PropelPDO $con = null) Return the first PUNotifications matching the query
 * @method PUNotifications findOneOrCreate(PropelPDO $con = null) Return the first PUNotifications matching the query, or a new PUNotifications object populated from the query conditions when no match is found
 *
 * @method PUNotifications findOneByPUserId(int $p_user_id) Return the first PUNotifications filtered by the p_user_id column
 * @method PUNotifications findOneByPNotificationId(int $p_notification_id) Return the first PUNotifications filtered by the p_notification_id column
 * @method PUNotifications findOneByPObjectName(string $p_object_name) Return the first PUNotifications filtered by the p_object_name column
 * @method PUNotifications findOneByPObjectId(int $p_object_id) Return the first PUNotifications filtered by the p_object_id column
 * @method PUNotifications findOneByPAuthorUserId(int $p_author_user_id) Return the first PUNotifications filtered by the p_author_user_id column
 * @method PUNotifications findOneByChecked(boolean $checked) Return the first PUNotifications filtered by the checked column
 * @method PUNotifications findOneByCheckedAt(string $checked_at) Return the first PUNotifications filtered by the checked_at column
 * @method PUNotifications findOneByCreatedAt(string $created_at) Return the first PUNotifications filtered by the created_at column
 * @method PUNotifications findOneByUpdatedAt(string $updated_at) Return the first PUNotifications filtered by the updated_at column
 *
 * @method array findById(int $id) Return PUNotifications objects filtered by the id column
 * @method array findByPUserId(int $p_user_id) Return PUNotifications objects filtered by the p_user_id column
 * @method array findByPNotificationId(int $p_notification_id) Return PUNotifications objects filtered by the p_notification_id column
 * @method array findByPObjectName(string $p_object_name) Return PUNotifications objects filtered by the p_object_name column
 * @method array findByPObjectId(int $p_object_id) Return PUNotifications objects filtered by the p_object_id column
 * @method array findByPAuthorUserId(int $p_author_user_id) Return PUNotifications objects filtered by the p_author_user_id column
 * @method array findByChecked(boolean $checked) Return PUNotifications objects filtered by the checked column
 * @method array findByCheckedAt(string $checked_at) Return PUNotifications objects filtered by the checked_at column
 * @method array findByCreatedAt(string $created_at) Return PUNotifications objects filtered by the created_at column
 * @method array findByUpdatedAt(string $updated_at) Return PUNotifications objects filtered by the updated_at column
 */
abstract class BasePUNotificationsQuery extends ModelCriteria
{
    // query_cache behavior
    protected $queryKey = '';

    /**
     * Initializes internal state of BasePUNotificationsQuery object.
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
            $modelName = 'Politizr\\Model\\PUNotifications';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
        EventDispatcherProxy::trigger(array('construct','query.construct'), new QueryEvent($this));
    }

    /**
     * Returns a new PUNotificationsQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   PUNotificationsQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return PUNotificationsQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof PUNotificationsQuery) {
            return $criteria;
        }
        $query = new static(null, null, $modelAlias);

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
     * @return   PUNotifications|PUNotifications[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = PUNotificationsPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(PUNotificationsPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 PUNotifications A model object, or null if the key is not found
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
     * @return                 PUNotifications A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `p_user_id`, `p_notification_id`, `p_object_name`, `p_object_id`, `p_author_user_id`, `checked`, `checked_at`, `created_at`, `updated_at` FROM `p_u_notifications` WHERE `id` = :p0';
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
            $cls = PUNotificationsPeer::getOMClass();
            $obj = new $cls;
            $obj->hydrate($row);
            PUNotificationsPeer::addInstanceToPool($obj, (string) $key);
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
     * @return PUNotifications|PUNotifications[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|PUNotifications[]|mixed the list of results, formatted by the current formatter
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
     * @return PUNotificationsQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(PUNotificationsPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return PUNotificationsQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(PUNotificationsPeer::ID, $keys, Criteria::IN);
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
     * @return PUNotificationsQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(PUNotificationsPeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(PUNotificationsPeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PUNotificationsPeer::ID, $id, $comparison);
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
     * @see       filterByPUNotificationsPUser()
     *
     * @param     mixed $pUserId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PUNotificationsQuery The current query, for fluid interface
     */
    public function filterByPUserId($pUserId = null, $comparison = null)
    {
        if (is_array($pUserId)) {
            $useMinMax = false;
            if (isset($pUserId['min'])) {
                $this->addUsingAlias(PUNotificationsPeer::P_USER_ID, $pUserId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($pUserId['max'])) {
                $this->addUsingAlias(PUNotificationsPeer::P_USER_ID, $pUserId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PUNotificationsPeer::P_USER_ID, $pUserId, $comparison);
    }

    /**
     * Filter the query on the p_notification_id column
     *
     * Example usage:
     * <code>
     * $query->filterByPNotificationId(1234); // WHERE p_notification_id = 1234
     * $query->filterByPNotificationId(array(12, 34)); // WHERE p_notification_id IN (12, 34)
     * $query->filterByPNotificationId(array('min' => 12)); // WHERE p_notification_id >= 12
     * $query->filterByPNotificationId(array('max' => 12)); // WHERE p_notification_id <= 12
     * </code>
     *
     * @see       filterByPUNotificationsPNotification()
     *
     * @param     mixed $pNotificationId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PUNotificationsQuery The current query, for fluid interface
     */
    public function filterByPNotificationId($pNotificationId = null, $comparison = null)
    {
        if (is_array($pNotificationId)) {
            $useMinMax = false;
            if (isset($pNotificationId['min'])) {
                $this->addUsingAlias(PUNotificationsPeer::P_NOTIFICATION_ID, $pNotificationId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($pNotificationId['max'])) {
                $this->addUsingAlias(PUNotificationsPeer::P_NOTIFICATION_ID, $pNotificationId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PUNotificationsPeer::P_NOTIFICATION_ID, $pNotificationId, $comparison);
    }

    /**
     * Filter the query on the p_object_name column
     *
     * Example usage:
     * <code>
     * $query->filterByPObjectName('fooValue');   // WHERE p_object_name = 'fooValue'
     * $query->filterByPObjectName('%fooValue%'); // WHERE p_object_name LIKE '%fooValue%'
     * </code>
     *
     * @param     string $pObjectName The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PUNotificationsQuery The current query, for fluid interface
     */
    public function filterByPObjectName($pObjectName = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($pObjectName)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $pObjectName)) {
                $pObjectName = str_replace('*', '%', $pObjectName);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(PUNotificationsPeer::P_OBJECT_NAME, $pObjectName, $comparison);
    }

    /**
     * Filter the query on the p_object_id column
     *
     * Example usage:
     * <code>
     * $query->filterByPObjectId(1234); // WHERE p_object_id = 1234
     * $query->filterByPObjectId(array(12, 34)); // WHERE p_object_id IN (12, 34)
     * $query->filterByPObjectId(array('min' => 12)); // WHERE p_object_id >= 12
     * $query->filterByPObjectId(array('max' => 12)); // WHERE p_object_id <= 12
     * </code>
     *
     * @param     mixed $pObjectId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PUNotificationsQuery The current query, for fluid interface
     */
    public function filterByPObjectId($pObjectId = null, $comparison = null)
    {
        if (is_array($pObjectId)) {
            $useMinMax = false;
            if (isset($pObjectId['min'])) {
                $this->addUsingAlias(PUNotificationsPeer::P_OBJECT_ID, $pObjectId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($pObjectId['max'])) {
                $this->addUsingAlias(PUNotificationsPeer::P_OBJECT_ID, $pObjectId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PUNotificationsPeer::P_OBJECT_ID, $pObjectId, $comparison);
    }

    /**
     * Filter the query on the p_author_user_id column
     *
     * Example usage:
     * <code>
     * $query->filterByPAuthorUserId(1234); // WHERE p_author_user_id = 1234
     * $query->filterByPAuthorUserId(array(12, 34)); // WHERE p_author_user_id IN (12, 34)
     * $query->filterByPAuthorUserId(array('min' => 12)); // WHERE p_author_user_id >= 12
     * $query->filterByPAuthorUserId(array('max' => 12)); // WHERE p_author_user_id <= 12
     * </code>
     *
     * @param     mixed $pAuthorUserId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PUNotificationsQuery The current query, for fluid interface
     */
    public function filterByPAuthorUserId($pAuthorUserId = null, $comparison = null)
    {
        if (is_array($pAuthorUserId)) {
            $useMinMax = false;
            if (isset($pAuthorUserId['min'])) {
                $this->addUsingAlias(PUNotificationsPeer::P_AUTHOR_USER_ID, $pAuthorUserId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($pAuthorUserId['max'])) {
                $this->addUsingAlias(PUNotificationsPeer::P_AUTHOR_USER_ID, $pAuthorUserId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PUNotificationsPeer::P_AUTHOR_USER_ID, $pAuthorUserId, $comparison);
    }

    /**
     * Filter the query on the checked column
     *
     * Example usage:
     * <code>
     * $query->filterByChecked(true); // WHERE checked = true
     * $query->filterByChecked('yes'); // WHERE checked = true
     * </code>
     *
     * @param     boolean|string $checked The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PUNotificationsQuery The current query, for fluid interface
     */
    public function filterByChecked($checked = null, $comparison = null)
    {
        if (is_string($checked)) {
            $checked = in_array(strtolower($checked), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(PUNotificationsPeer::CHECKED, $checked, $comparison);
    }

    /**
     * Filter the query on the checked_at column
     *
     * Example usage:
     * <code>
     * $query->filterByCheckedAt('2011-03-14'); // WHERE checked_at = '2011-03-14'
     * $query->filterByCheckedAt('now'); // WHERE checked_at = '2011-03-14'
     * $query->filterByCheckedAt(array('max' => 'yesterday')); // WHERE checked_at < '2011-03-13'
     * </code>
     *
     * @param     mixed $checkedAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PUNotificationsQuery The current query, for fluid interface
     */
    public function filterByCheckedAt($checkedAt = null, $comparison = null)
    {
        if (is_array($checkedAt)) {
            $useMinMax = false;
            if (isset($checkedAt['min'])) {
                $this->addUsingAlias(PUNotificationsPeer::CHECKED_AT, $checkedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($checkedAt['max'])) {
                $this->addUsingAlias(PUNotificationsPeer::CHECKED_AT, $checkedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PUNotificationsPeer::CHECKED_AT, $checkedAt, $comparison);
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
     * @return PUNotificationsQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(PUNotificationsPeer::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(PUNotificationsPeer::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PUNotificationsPeer::CREATED_AT, $createdAt, $comparison);
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
     * @return PUNotificationsQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(PUNotificationsPeer::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(PUNotificationsPeer::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PUNotificationsPeer::UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query by a related PUser object
     *
     * @param   PUser|PropelObjectCollection $pUser The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PUNotificationsQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPUNotificationsPUser($pUser, $comparison = null)
    {
        if ($pUser instanceof PUser) {
            return $this
                ->addUsingAlias(PUNotificationsPeer::P_USER_ID, $pUser->getId(), $comparison);
        } elseif ($pUser instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PUNotificationsPeer::P_USER_ID, $pUser->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByPUNotificationsPUser() only accepts arguments of type PUser or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PUNotificationsPUser relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PUNotificationsQuery The current query, for fluid interface
     */
    public function joinPUNotificationsPUser($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PUNotificationsPUser');

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
            $this->addJoinObject($join, 'PUNotificationsPUser');
        }

        return $this;
    }

    /**
     * Use the PUNotificationsPUser relation PUser object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Politizr\Model\PUserQuery A secondary query class using the current class as primary query
     */
    public function usePUNotificationsPUserQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPUNotificationsPUser($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PUNotificationsPUser', '\Politizr\Model\PUserQuery');
    }

    /**
     * Filter the query by a related PNotification object
     *
     * @param   PNotification|PropelObjectCollection $pNotification The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PUNotificationsQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPUNotificationsPNotification($pNotification, $comparison = null)
    {
        if ($pNotification instanceof PNotification) {
            return $this
                ->addUsingAlias(PUNotificationsPeer::P_NOTIFICATION_ID, $pNotification->getId(), $comparison);
        } elseif ($pNotification instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PUNotificationsPeer::P_NOTIFICATION_ID, $pNotification->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByPUNotificationsPNotification() only accepts arguments of type PNotification or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PUNotificationsPNotification relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PUNotificationsQuery The current query, for fluid interface
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
     * Use the PUNotificationsPNotification relation PNotification object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Politizr\Model\PNotificationQuery A secondary query class using the current class as primary query
     */
    public function usePUNotificationsPNotificationQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPUNotificationsPNotification($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PUNotificationsPNotification', '\Politizr\Model\PNotificationQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   PUNotifications $pUNotifications Object to remove from the list of results
     *
     * @return PUNotificationsQuery The current query, for fluid interface
     */
    public function prune($pUNotifications = null)
    {
        if ($pUNotifications) {
            $this->addUsingAlias(PUNotificationsPeer::ID, $pUNotifications->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Code to execute before every SELECT statement
     *
     * @param     PropelPDO $con The connection object used by the query
     */
    protected function basePreSelect(PropelPDO $con)
    {
        // event behavior
        EventDispatcherProxy::trigger('query.select.pre', new QueryEvent($this));

        return $this->preSelect($con);
    }

    /**
     * Code to execute before every DELETE statement
     *
     * @param     PropelPDO $con The connection object used by the query
     */
    protected function basePreDelete(PropelPDO $con)
    {
        EventDispatcherProxy::trigger(array('delete.pre','query.delete.pre'), new QueryEvent($this));
        // event behavior
        // placeholder, issue #5

        return $this->preDelete($con);
    }

    /**
     * Code to execute after every DELETE statement
     *
     * @param     int $affectedRows the number of deleted rows
     * @param     PropelPDO $con The connection object used by the query
     */
    protected function basePostDelete($affectedRows, PropelPDO $con)
    {
        // event behavior
        EventDispatcherProxy::trigger(array('delete.post','query.delete.post'), new QueryEvent($this));

        return $this->postDelete($affectedRows, $con);
    }

    /**
     * Code to execute before every UPDATE statement
     *
     * @param     array $values The associative array of columns and values for the update
     * @param     PropelPDO $con The connection object used by the query
     * @param     boolean $forceIndividualSaves If false (default), the resulting call is a BasePeer::doUpdate(), otherwise it is a series of save() calls on all the found objects
     */
    protected function basePreUpdate(&$values, PropelPDO $con, $forceIndividualSaves = false)
    {
        // event behavior
        EventDispatcherProxy::trigger(array('update.pre', 'query.update.pre'), new QueryEvent($this));

        return $this->preUpdate($values, $con, $forceIndividualSaves);
    }

    /**
     * Code to execute after every UPDATE statement
     *
     * @param     int $affectedRows the number of updated rows
     * @param     PropelPDO $con The connection object used by the query
     */
    protected function basePostUpdate($affectedRows, PropelPDO $con)
    {
        // event behavior
        EventDispatcherProxy::trigger(array('update.post', 'query.update.post'), new QueryEvent($this));

        return $this->postUpdate($affectedRows, $con);
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     PUNotificationsQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(PUNotificationsPeer::UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     PUNotificationsQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(PUNotificationsPeer::UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     PUNotificationsQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(PUNotificationsPeer::UPDATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     PUNotificationsQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(PUNotificationsPeer::CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date desc
     *
     * @return     PUNotificationsQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(PUNotificationsPeer::CREATED_AT);
    }

    /**
     * Order by create date asc
     *
     * @return     PUNotificationsQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(PUNotificationsPeer::CREATED_AT);
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

        $dbMap = Propel::getDatabaseMap(PUNotificationsPeer::DATABASE_NAME);
        $db = Propel::getDB(PUNotificationsPeer::DATABASE_NAME);

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

    // extend behavior
    public function setFormatter($formatter)
    {
        if (is_string($formatter) && $formatter === \ModelCriteria::FORMAT_ON_DEMAND) {
            $formatter = '\Glorpen\Propel\PropelBundle\Formatter\PropelOnDemandFormatter';
        }

        return parent::setFormatter($formatter);
    }
}
