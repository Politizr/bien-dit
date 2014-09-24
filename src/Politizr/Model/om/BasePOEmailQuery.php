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
use Politizr\Model\POEmail;
use Politizr\Model\POEmailPeer;
use Politizr\Model\POEmailQuery;
use Politizr\Model\POOrderState;
use Politizr\Model\POPaymentState;
use Politizr\Model\POPaymentType;
use Politizr\Model\POSubscription;
use Politizr\Model\POrder;

/**
 * @method POEmailQuery orderById($order = Criteria::ASC) Order by the id column
 * @method POEmailQuery orderByPOrderId($order = Criteria::ASC) Order by the p_order_id column
 * @method POEmailQuery orderByPOOrderStateId($order = Criteria::ASC) Order by the p_o_order_state_id column
 * @method POEmailQuery orderByPOPaymentStateId($order = Criteria::ASC) Order by the p_o_payment_state_id column
 * @method POEmailQuery orderByPOPaymentTypeId($order = Criteria::ASC) Order by the p_o_payment_type_id column
 * @method POEmailQuery orderByPOSubscriptionId($order = Criteria::ASC) Order by the p_o_subscription_id column
 * @method POEmailQuery orderBySubject($order = Criteria::ASC) Order by the subject column
 * @method POEmailQuery orderByHtmlBody($order = Criteria::ASC) Order by the html_body column
 * @method POEmailQuery orderByTxtBody($order = Criteria::ASC) Order by the txt_body column
 * @method POEmailQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method POEmailQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method POEmailQuery groupById() Group by the id column
 * @method POEmailQuery groupByPOrderId() Group by the p_order_id column
 * @method POEmailQuery groupByPOOrderStateId() Group by the p_o_order_state_id column
 * @method POEmailQuery groupByPOPaymentStateId() Group by the p_o_payment_state_id column
 * @method POEmailQuery groupByPOPaymentTypeId() Group by the p_o_payment_type_id column
 * @method POEmailQuery groupByPOSubscriptionId() Group by the p_o_subscription_id column
 * @method POEmailQuery groupBySubject() Group by the subject column
 * @method POEmailQuery groupByHtmlBody() Group by the html_body column
 * @method POEmailQuery groupByTxtBody() Group by the txt_body column
 * @method POEmailQuery groupByCreatedAt() Group by the created_at column
 * @method POEmailQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method POEmailQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method POEmailQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method POEmailQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method POEmailQuery leftJoinPOrder($relationAlias = null) Adds a LEFT JOIN clause to the query using the POrder relation
 * @method POEmailQuery rightJoinPOrder($relationAlias = null) Adds a RIGHT JOIN clause to the query using the POrder relation
 * @method POEmailQuery innerJoinPOrder($relationAlias = null) Adds a INNER JOIN clause to the query using the POrder relation
 *
 * @method POEmailQuery leftJoinPOOrderState($relationAlias = null) Adds a LEFT JOIN clause to the query using the POOrderState relation
 * @method POEmailQuery rightJoinPOOrderState($relationAlias = null) Adds a RIGHT JOIN clause to the query using the POOrderState relation
 * @method POEmailQuery innerJoinPOOrderState($relationAlias = null) Adds a INNER JOIN clause to the query using the POOrderState relation
 *
 * @method POEmailQuery leftJoinPOPaymentState($relationAlias = null) Adds a LEFT JOIN clause to the query using the POPaymentState relation
 * @method POEmailQuery rightJoinPOPaymentState($relationAlias = null) Adds a RIGHT JOIN clause to the query using the POPaymentState relation
 * @method POEmailQuery innerJoinPOPaymentState($relationAlias = null) Adds a INNER JOIN clause to the query using the POPaymentState relation
 *
 * @method POEmailQuery leftJoinPOPaymentType($relationAlias = null) Adds a LEFT JOIN clause to the query using the POPaymentType relation
 * @method POEmailQuery rightJoinPOPaymentType($relationAlias = null) Adds a RIGHT JOIN clause to the query using the POPaymentType relation
 * @method POEmailQuery innerJoinPOPaymentType($relationAlias = null) Adds a INNER JOIN clause to the query using the POPaymentType relation
 *
 * @method POEmailQuery leftJoinPOSubscription($relationAlias = null) Adds a LEFT JOIN clause to the query using the POSubscription relation
 * @method POEmailQuery rightJoinPOSubscription($relationAlias = null) Adds a RIGHT JOIN clause to the query using the POSubscription relation
 * @method POEmailQuery innerJoinPOSubscription($relationAlias = null) Adds a INNER JOIN clause to the query using the POSubscription relation
 *
 * @method POEmail findOne(PropelPDO $con = null) Return the first POEmail matching the query
 * @method POEmail findOneOrCreate(PropelPDO $con = null) Return the first POEmail matching the query, or a new POEmail object populated from the query conditions when no match is found
 *
 * @method POEmail findOneByPOrderId(int $p_order_id) Return the first POEmail filtered by the p_order_id column
 * @method POEmail findOneByPOOrderStateId(int $p_o_order_state_id) Return the first POEmail filtered by the p_o_order_state_id column
 * @method POEmail findOneByPOPaymentStateId(int $p_o_payment_state_id) Return the first POEmail filtered by the p_o_payment_state_id column
 * @method POEmail findOneByPOPaymentTypeId(int $p_o_payment_type_id) Return the first POEmail filtered by the p_o_payment_type_id column
 * @method POEmail findOneByPOSubscriptionId(int $p_o_subscription_id) Return the first POEmail filtered by the p_o_subscription_id column
 * @method POEmail findOneBySubject(string $subject) Return the first POEmail filtered by the subject column
 * @method POEmail findOneByHtmlBody(string $html_body) Return the first POEmail filtered by the html_body column
 * @method POEmail findOneByTxtBody(string $txt_body) Return the first POEmail filtered by the txt_body column
 * @method POEmail findOneByCreatedAt(string $created_at) Return the first POEmail filtered by the created_at column
 * @method POEmail findOneByUpdatedAt(string $updated_at) Return the first POEmail filtered by the updated_at column
 *
 * @method array findById(int $id) Return POEmail objects filtered by the id column
 * @method array findByPOrderId(int $p_order_id) Return POEmail objects filtered by the p_order_id column
 * @method array findByPOOrderStateId(int $p_o_order_state_id) Return POEmail objects filtered by the p_o_order_state_id column
 * @method array findByPOPaymentStateId(int $p_o_payment_state_id) Return POEmail objects filtered by the p_o_payment_state_id column
 * @method array findByPOPaymentTypeId(int $p_o_payment_type_id) Return POEmail objects filtered by the p_o_payment_type_id column
 * @method array findByPOSubscriptionId(int $p_o_subscription_id) Return POEmail objects filtered by the p_o_subscription_id column
 * @method array findBySubject(string $subject) Return POEmail objects filtered by the subject column
 * @method array findByHtmlBody(string $html_body) Return POEmail objects filtered by the html_body column
 * @method array findByTxtBody(string $txt_body) Return POEmail objects filtered by the txt_body column
 * @method array findByCreatedAt(string $created_at) Return POEmail objects filtered by the created_at column
 * @method array findByUpdatedAt(string $updated_at) Return POEmail objects filtered by the updated_at column
 */
abstract class BasePOEmailQuery extends ModelCriteria
{
    // query_cache behavior
    protected $queryKey = '';

    /**
     * Initializes internal state of BasePOEmailQuery object.
     *
     * @param     string $dbName The dabase name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = 'Politizr\\Model\\POEmail', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new POEmailQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   POEmailQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return POEmailQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof POEmailQuery) {
            return $criteria;
        }
        $query = new POEmailQuery();
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
     * @return   POEmail|POEmail[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = POEmailPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is alredy in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(POEmailPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 POEmail A model object, or null if the key is not found
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
     * @return                 POEmail A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `p_order_id`, `p_o_order_state_id`, `p_o_payment_state_id`, `p_o_payment_type_id`, `p_o_subscription_id`, `subject`, `html_body`, `txt_body`, `created_at`, `updated_at` FROM `p_o_email` WHERE `id` = :p0';
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
            $obj = new POEmail();
            $obj->hydrate($row);
            POEmailPeer::addInstanceToPool($obj, (string) $key);
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
     * @return POEmail|POEmail[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|POEmail[]|mixed the list of results, formatted by the current formatter
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
     * @return POEmailQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(POEmailPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return POEmailQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(POEmailPeer::ID, $keys, Criteria::IN);
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
     * @return POEmailQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(POEmailPeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(POEmailPeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(POEmailPeer::ID, $id, $comparison);
    }

    /**
     * Filter the query on the p_order_id column
     *
     * Example usage:
     * <code>
     * $query->filterByPOrderId(1234); // WHERE p_order_id = 1234
     * $query->filterByPOrderId(array(12, 34)); // WHERE p_order_id IN (12, 34)
     * $query->filterByPOrderId(array('min' => 12)); // WHERE p_order_id >= 12
     * $query->filterByPOrderId(array('max' => 12)); // WHERE p_order_id <= 12
     * </code>
     *
     * @see       filterByPOrder()
     *
     * @param     mixed $pOrderId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return POEmailQuery The current query, for fluid interface
     */
    public function filterByPOrderId($pOrderId = null, $comparison = null)
    {
        if (is_array($pOrderId)) {
            $useMinMax = false;
            if (isset($pOrderId['min'])) {
                $this->addUsingAlias(POEmailPeer::P_ORDER_ID, $pOrderId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($pOrderId['max'])) {
                $this->addUsingAlias(POEmailPeer::P_ORDER_ID, $pOrderId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(POEmailPeer::P_ORDER_ID, $pOrderId, $comparison);
    }

    /**
     * Filter the query on the p_o_order_state_id column
     *
     * Example usage:
     * <code>
     * $query->filterByPOOrderStateId(1234); // WHERE p_o_order_state_id = 1234
     * $query->filterByPOOrderStateId(array(12, 34)); // WHERE p_o_order_state_id IN (12, 34)
     * $query->filterByPOOrderStateId(array('min' => 12)); // WHERE p_o_order_state_id >= 12
     * $query->filterByPOOrderStateId(array('max' => 12)); // WHERE p_o_order_state_id <= 12
     * </code>
     *
     * @see       filterByPOOrderState()
     *
     * @param     mixed $pOOrderStateId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return POEmailQuery The current query, for fluid interface
     */
    public function filterByPOOrderStateId($pOOrderStateId = null, $comparison = null)
    {
        if (is_array($pOOrderStateId)) {
            $useMinMax = false;
            if (isset($pOOrderStateId['min'])) {
                $this->addUsingAlias(POEmailPeer::P_O_ORDER_STATE_ID, $pOOrderStateId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($pOOrderStateId['max'])) {
                $this->addUsingAlias(POEmailPeer::P_O_ORDER_STATE_ID, $pOOrderStateId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(POEmailPeer::P_O_ORDER_STATE_ID, $pOOrderStateId, $comparison);
    }

    /**
     * Filter the query on the p_o_payment_state_id column
     *
     * Example usage:
     * <code>
     * $query->filterByPOPaymentStateId(1234); // WHERE p_o_payment_state_id = 1234
     * $query->filterByPOPaymentStateId(array(12, 34)); // WHERE p_o_payment_state_id IN (12, 34)
     * $query->filterByPOPaymentStateId(array('min' => 12)); // WHERE p_o_payment_state_id >= 12
     * $query->filterByPOPaymentStateId(array('max' => 12)); // WHERE p_o_payment_state_id <= 12
     * </code>
     *
     * @see       filterByPOPaymentState()
     *
     * @param     mixed $pOPaymentStateId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return POEmailQuery The current query, for fluid interface
     */
    public function filterByPOPaymentStateId($pOPaymentStateId = null, $comparison = null)
    {
        if (is_array($pOPaymentStateId)) {
            $useMinMax = false;
            if (isset($pOPaymentStateId['min'])) {
                $this->addUsingAlias(POEmailPeer::P_O_PAYMENT_STATE_ID, $pOPaymentStateId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($pOPaymentStateId['max'])) {
                $this->addUsingAlias(POEmailPeer::P_O_PAYMENT_STATE_ID, $pOPaymentStateId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(POEmailPeer::P_O_PAYMENT_STATE_ID, $pOPaymentStateId, $comparison);
    }

    /**
     * Filter the query on the p_o_payment_type_id column
     *
     * Example usage:
     * <code>
     * $query->filterByPOPaymentTypeId(1234); // WHERE p_o_payment_type_id = 1234
     * $query->filterByPOPaymentTypeId(array(12, 34)); // WHERE p_o_payment_type_id IN (12, 34)
     * $query->filterByPOPaymentTypeId(array('min' => 12)); // WHERE p_o_payment_type_id >= 12
     * $query->filterByPOPaymentTypeId(array('max' => 12)); // WHERE p_o_payment_type_id <= 12
     * </code>
     *
     * @see       filterByPOPaymentType()
     *
     * @param     mixed $pOPaymentTypeId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return POEmailQuery The current query, for fluid interface
     */
    public function filterByPOPaymentTypeId($pOPaymentTypeId = null, $comparison = null)
    {
        if (is_array($pOPaymentTypeId)) {
            $useMinMax = false;
            if (isset($pOPaymentTypeId['min'])) {
                $this->addUsingAlias(POEmailPeer::P_O_PAYMENT_TYPE_ID, $pOPaymentTypeId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($pOPaymentTypeId['max'])) {
                $this->addUsingAlias(POEmailPeer::P_O_PAYMENT_TYPE_ID, $pOPaymentTypeId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(POEmailPeer::P_O_PAYMENT_TYPE_ID, $pOPaymentTypeId, $comparison);
    }

    /**
     * Filter the query on the p_o_subscription_id column
     *
     * Example usage:
     * <code>
     * $query->filterByPOSubscriptionId(1234); // WHERE p_o_subscription_id = 1234
     * $query->filterByPOSubscriptionId(array(12, 34)); // WHERE p_o_subscription_id IN (12, 34)
     * $query->filterByPOSubscriptionId(array('min' => 12)); // WHERE p_o_subscription_id >= 12
     * $query->filterByPOSubscriptionId(array('max' => 12)); // WHERE p_o_subscription_id <= 12
     * </code>
     *
     * @see       filterByPOSubscription()
     *
     * @param     mixed $pOSubscriptionId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return POEmailQuery The current query, for fluid interface
     */
    public function filterByPOSubscriptionId($pOSubscriptionId = null, $comparison = null)
    {
        if (is_array($pOSubscriptionId)) {
            $useMinMax = false;
            if (isset($pOSubscriptionId['min'])) {
                $this->addUsingAlias(POEmailPeer::P_O_SUBSCRIPTION_ID, $pOSubscriptionId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($pOSubscriptionId['max'])) {
                $this->addUsingAlias(POEmailPeer::P_O_SUBSCRIPTION_ID, $pOSubscriptionId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(POEmailPeer::P_O_SUBSCRIPTION_ID, $pOSubscriptionId, $comparison);
    }

    /**
     * Filter the query on the subject column
     *
     * Example usage:
     * <code>
     * $query->filterBySubject('fooValue');   // WHERE subject = 'fooValue'
     * $query->filterBySubject('%fooValue%'); // WHERE subject LIKE '%fooValue%'
     * </code>
     *
     * @param     string $subject The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return POEmailQuery The current query, for fluid interface
     */
    public function filterBySubject($subject = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($subject)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $subject)) {
                $subject = str_replace('*', '%', $subject);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(POEmailPeer::SUBJECT, $subject, $comparison);
    }

    /**
     * Filter the query on the html_body column
     *
     * Example usage:
     * <code>
     * $query->filterByHtmlBody('fooValue');   // WHERE html_body = 'fooValue'
     * $query->filterByHtmlBody('%fooValue%'); // WHERE html_body LIKE '%fooValue%'
     * </code>
     *
     * @param     string $htmlBody The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return POEmailQuery The current query, for fluid interface
     */
    public function filterByHtmlBody($htmlBody = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($htmlBody)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $htmlBody)) {
                $htmlBody = str_replace('*', '%', $htmlBody);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(POEmailPeer::HTML_BODY, $htmlBody, $comparison);
    }

    /**
     * Filter the query on the txt_body column
     *
     * Example usage:
     * <code>
     * $query->filterByTxtBody('fooValue');   // WHERE txt_body = 'fooValue'
     * $query->filterByTxtBody('%fooValue%'); // WHERE txt_body LIKE '%fooValue%'
     * </code>
     *
     * @param     string $txtBody The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return POEmailQuery The current query, for fluid interface
     */
    public function filterByTxtBody($txtBody = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($txtBody)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $txtBody)) {
                $txtBody = str_replace('*', '%', $txtBody);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(POEmailPeer::TXT_BODY, $txtBody, $comparison);
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
     * @return POEmailQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(POEmailPeer::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(POEmailPeer::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(POEmailPeer::CREATED_AT, $createdAt, $comparison);
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
     * @return POEmailQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(POEmailPeer::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(POEmailPeer::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(POEmailPeer::UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query by a related POrder object
     *
     * @param   POrder|PropelObjectCollection $pOrder The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 POEmailQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPOrder($pOrder, $comparison = null)
    {
        if ($pOrder instanceof POrder) {
            return $this
                ->addUsingAlias(POEmailPeer::P_ORDER_ID, $pOrder->getId(), $comparison);
        } elseif ($pOrder instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(POEmailPeer::P_ORDER_ID, $pOrder->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return POEmailQuery The current query, for fluid interface
     */
    public function joinPOrder($relationAlias = null, $joinType = Criteria::INNER_JOIN)
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
    public function usePOrderQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPOrder($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'POrder', '\Politizr\Model\POrderQuery');
    }

    /**
     * Filter the query by a related POOrderState object
     *
     * @param   POOrderState|PropelObjectCollection $pOOrderState The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 POEmailQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPOOrderState($pOOrderState, $comparison = null)
    {
        if ($pOOrderState instanceof POOrderState) {
            return $this
                ->addUsingAlias(POEmailPeer::P_O_ORDER_STATE_ID, $pOOrderState->getId(), $comparison);
        } elseif ($pOOrderState instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(POEmailPeer::P_O_ORDER_STATE_ID, $pOOrderState->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByPOOrderState() only accepts arguments of type POOrderState or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the POOrderState relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return POEmailQuery The current query, for fluid interface
     */
    public function joinPOOrderState($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('POOrderState');

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
            $this->addJoinObject($join, 'POOrderState');
        }

        return $this;
    }

    /**
     * Use the POOrderState relation POOrderState object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Politizr\Model\POOrderStateQuery A secondary query class using the current class as primary query
     */
    public function usePOOrderStateQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinPOOrderState($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'POOrderState', '\Politizr\Model\POOrderStateQuery');
    }

    /**
     * Filter the query by a related POPaymentState object
     *
     * @param   POPaymentState|PropelObjectCollection $pOPaymentState The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 POEmailQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPOPaymentState($pOPaymentState, $comparison = null)
    {
        if ($pOPaymentState instanceof POPaymentState) {
            return $this
                ->addUsingAlias(POEmailPeer::P_O_PAYMENT_STATE_ID, $pOPaymentState->getId(), $comparison);
        } elseif ($pOPaymentState instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(POEmailPeer::P_O_PAYMENT_STATE_ID, $pOPaymentState->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByPOPaymentState() only accepts arguments of type POPaymentState or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the POPaymentState relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return POEmailQuery The current query, for fluid interface
     */
    public function joinPOPaymentState($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('POPaymentState');

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
            $this->addJoinObject($join, 'POPaymentState');
        }

        return $this;
    }

    /**
     * Use the POPaymentState relation POPaymentState object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Politizr\Model\POPaymentStateQuery A secondary query class using the current class as primary query
     */
    public function usePOPaymentStateQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinPOPaymentState($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'POPaymentState', '\Politizr\Model\POPaymentStateQuery');
    }

    /**
     * Filter the query by a related POPaymentType object
     *
     * @param   POPaymentType|PropelObjectCollection $pOPaymentType The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 POEmailQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPOPaymentType($pOPaymentType, $comparison = null)
    {
        if ($pOPaymentType instanceof POPaymentType) {
            return $this
                ->addUsingAlias(POEmailPeer::P_O_PAYMENT_TYPE_ID, $pOPaymentType->getId(), $comparison);
        } elseif ($pOPaymentType instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(POEmailPeer::P_O_PAYMENT_TYPE_ID, $pOPaymentType->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByPOPaymentType() only accepts arguments of type POPaymentType or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the POPaymentType relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return POEmailQuery The current query, for fluid interface
     */
    public function joinPOPaymentType($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('POPaymentType');

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
            $this->addJoinObject($join, 'POPaymentType');
        }

        return $this;
    }

    /**
     * Use the POPaymentType relation POPaymentType object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Politizr\Model\POPaymentTypeQuery A secondary query class using the current class as primary query
     */
    public function usePOPaymentTypeQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinPOPaymentType($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'POPaymentType', '\Politizr\Model\POPaymentTypeQuery');
    }

    /**
     * Filter the query by a related POSubscription object
     *
     * @param   POSubscription|PropelObjectCollection $pOSubscription The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 POEmailQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPOSubscription($pOSubscription, $comparison = null)
    {
        if ($pOSubscription instanceof POSubscription) {
            return $this
                ->addUsingAlias(POEmailPeer::P_O_SUBSCRIPTION_ID, $pOSubscription->getId(), $comparison);
        } elseif ($pOSubscription instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(POEmailPeer::P_O_SUBSCRIPTION_ID, $pOSubscription->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByPOSubscription() only accepts arguments of type POSubscription or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the POSubscription relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return POEmailQuery The current query, for fluid interface
     */
    public function joinPOSubscription($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('POSubscription');

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
            $this->addJoinObject($join, 'POSubscription');
        }

        return $this;
    }

    /**
     * Use the POSubscription relation POSubscription object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Politizr\Model\POSubscriptionQuery A secondary query class using the current class as primary query
     */
    public function usePOSubscriptionQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinPOSubscription($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'POSubscription', '\Politizr\Model\POSubscriptionQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   POEmail $pOEmail Object to remove from the list of results
     *
     * @return POEmailQuery The current query, for fluid interface
     */
    public function prune($pOEmail = null)
    {
        if ($pOEmail) {
            $this->addUsingAlias(POEmailPeer::ID, $pOEmail->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     POEmailQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(POEmailPeer::UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     POEmailQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(POEmailPeer::UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     POEmailQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(POEmailPeer::UPDATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     POEmailQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(POEmailPeer::CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date desc
     *
     * @return     POEmailQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(POEmailPeer::CREATED_AT);
    }

    /**
     * Order by create date asc
     *
     * @return     POEmailQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(POEmailPeer::CREATED_AT);
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

        $dbMap = Propel::getDatabaseMap(POEmailPeer::DATABASE_NAME);
        $db = Propel::getDB(POEmailPeer::DATABASE_NAME);

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
