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
use Politizr\Model\POPaymentState;
use Politizr\Model\POPaymentType;
use Politizr\Model\POSubscription;
use Politizr\Model\POrder;
use Politizr\Model\POrderPeer;
use Politizr\Model\POrderQuery;
use Politizr\Model\PUser;

/**
 * @method POrderQuery orderById($order = Criteria::ASC) Order by the id column
 * @method POrderQuery orderByPUserId($order = Criteria::ASC) Order by the p_user_id column
 * @method POrderQuery orderByPOOrderStateId($order = Criteria::ASC) Order by the p_o_order_state_id column
 * @method POrderQuery orderByPOPaymentStateId($order = Criteria::ASC) Order by the p_o_payment_state_id column
 * @method POrderQuery orderByPOPaymentTypeId($order = Criteria::ASC) Order by the p_o_payment_type_id column
 * @method POrderQuery orderByPOSubscriptionId($order = Criteria::ASC) Order by the p_o_subscription_id column
 * @method POrderQuery orderBySubscriptionTitle($order = Criteria::ASC) Order by the subscription_title column
 * @method POrderQuery orderBySubscriptionDescription($order = Criteria::ASC) Order by the subscription_description column
 * @method POrderQuery orderBySubscriptionBeginAt($order = Criteria::ASC) Order by the subscription_begin_at column
 * @method POrderQuery orderBySubscriptionEndAt($order = Criteria::ASC) Order by the subscription_end_at column
 * @method POrderQuery orderByInformation($order = Criteria::ASC) Order by the information column
 * @method POrderQuery orderByPrice($order = Criteria::ASC) Order by the price column
 * @method POrderQuery orderByPromotion($order = Criteria::ASC) Order by the promotion column
 * @method POrderQuery orderByTotal($order = Criteria::ASC) Order by the total column
 * @method POrderQuery orderByGender($order = Criteria::ASC) Order by the gender column
 * @method POrderQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method POrderQuery orderByFirstname($order = Criteria::ASC) Order by the firstname column
 * @method POrderQuery orderByPhone($order = Criteria::ASC) Order by the phone column
 * @method POrderQuery orderByEmail($order = Criteria::ASC) Order by the email column
 * @method POrderQuery orderByInvoiceRef($order = Criteria::ASC) Order by the invoice_ref column
 * @method POrderQuery orderByInvoiceAt($order = Criteria::ASC) Order by the invoice_at column
 * @method POrderQuery orderByInvoiceFilename($order = Criteria::ASC) Order by the invoice_filename column
 * @method POrderQuery orderBySupportingDocument($order = Criteria::ASC) Order by the supporting_document column
 * @method POrderQuery orderByElectiveMandates($order = Criteria::ASC) Order by the elective_mandates column
 * @method POrderQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method POrderQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method POrderQuery groupById() Group by the id column
 * @method POrderQuery groupByPUserId() Group by the p_user_id column
 * @method POrderQuery groupByPOOrderStateId() Group by the p_o_order_state_id column
 * @method POrderQuery groupByPOPaymentStateId() Group by the p_o_payment_state_id column
 * @method POrderQuery groupByPOPaymentTypeId() Group by the p_o_payment_type_id column
 * @method POrderQuery groupByPOSubscriptionId() Group by the p_o_subscription_id column
 * @method POrderQuery groupBySubscriptionTitle() Group by the subscription_title column
 * @method POrderQuery groupBySubscriptionDescription() Group by the subscription_description column
 * @method POrderQuery groupBySubscriptionBeginAt() Group by the subscription_begin_at column
 * @method POrderQuery groupBySubscriptionEndAt() Group by the subscription_end_at column
 * @method POrderQuery groupByInformation() Group by the information column
 * @method POrderQuery groupByPrice() Group by the price column
 * @method POrderQuery groupByPromotion() Group by the promotion column
 * @method POrderQuery groupByTotal() Group by the total column
 * @method POrderQuery groupByGender() Group by the gender column
 * @method POrderQuery groupByName() Group by the name column
 * @method POrderQuery groupByFirstname() Group by the firstname column
 * @method POrderQuery groupByPhone() Group by the phone column
 * @method POrderQuery groupByEmail() Group by the email column
 * @method POrderQuery groupByInvoiceRef() Group by the invoice_ref column
 * @method POrderQuery groupByInvoiceAt() Group by the invoice_at column
 * @method POrderQuery groupByInvoiceFilename() Group by the invoice_filename column
 * @method POrderQuery groupBySupportingDocument() Group by the supporting_document column
 * @method POrderQuery groupByElectiveMandates() Group by the elective_mandates column
 * @method POrderQuery groupByCreatedAt() Group by the created_at column
 * @method POrderQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method POrderQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method POrderQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method POrderQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method POrderQuery leftJoinPUser($relationAlias = null) Adds a LEFT JOIN clause to the query using the PUser relation
 * @method POrderQuery rightJoinPUser($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PUser relation
 * @method POrderQuery innerJoinPUser($relationAlias = null) Adds a INNER JOIN clause to the query using the PUser relation
 *
 * @method POrderQuery leftJoinPOOrderState($relationAlias = null) Adds a LEFT JOIN clause to the query using the POOrderState relation
 * @method POrderQuery rightJoinPOOrderState($relationAlias = null) Adds a RIGHT JOIN clause to the query using the POOrderState relation
 * @method POrderQuery innerJoinPOOrderState($relationAlias = null) Adds a INNER JOIN clause to the query using the POOrderState relation
 *
 * @method POrderQuery leftJoinPOPaymentState($relationAlias = null) Adds a LEFT JOIN clause to the query using the POPaymentState relation
 * @method POrderQuery rightJoinPOPaymentState($relationAlias = null) Adds a RIGHT JOIN clause to the query using the POPaymentState relation
 * @method POrderQuery innerJoinPOPaymentState($relationAlias = null) Adds a INNER JOIN clause to the query using the POPaymentState relation
 *
 * @method POrderQuery leftJoinPOPaymentType($relationAlias = null) Adds a LEFT JOIN clause to the query using the POPaymentType relation
 * @method POrderQuery rightJoinPOPaymentType($relationAlias = null) Adds a RIGHT JOIN clause to the query using the POPaymentType relation
 * @method POrderQuery innerJoinPOPaymentType($relationAlias = null) Adds a INNER JOIN clause to the query using the POPaymentType relation
 *
 * @method POrderQuery leftJoinPOSubscription($relationAlias = null) Adds a LEFT JOIN clause to the query using the POSubscription relation
 * @method POrderQuery rightJoinPOSubscription($relationAlias = null) Adds a RIGHT JOIN clause to the query using the POSubscription relation
 * @method POrderQuery innerJoinPOSubscription($relationAlias = null) Adds a INNER JOIN clause to the query using the POSubscription relation
 *
 * @method POrderQuery leftJoinPOEmail($relationAlias = null) Adds a LEFT JOIN clause to the query using the POEmail relation
 * @method POrderQuery rightJoinPOEmail($relationAlias = null) Adds a RIGHT JOIN clause to the query using the POEmail relation
 * @method POrderQuery innerJoinPOEmail($relationAlias = null) Adds a INNER JOIN clause to the query using the POEmail relation
 *
 * @method POrder findOne(PropelPDO $con = null) Return the first POrder matching the query
 * @method POrder findOneOrCreate(PropelPDO $con = null) Return the first POrder matching the query, or a new POrder object populated from the query conditions when no match is found
 *
 * @method POrder findOneByPUserId(int $p_user_id) Return the first POrder filtered by the p_user_id column
 * @method POrder findOneByPOOrderStateId(int $p_o_order_state_id) Return the first POrder filtered by the p_o_order_state_id column
 * @method POrder findOneByPOPaymentStateId(int $p_o_payment_state_id) Return the first POrder filtered by the p_o_payment_state_id column
 * @method POrder findOneByPOPaymentTypeId(int $p_o_payment_type_id) Return the first POrder filtered by the p_o_payment_type_id column
 * @method POrder findOneByPOSubscriptionId(int $p_o_subscription_id) Return the first POrder filtered by the p_o_subscription_id column
 * @method POrder findOneBySubscriptionTitle(string $subscription_title) Return the first POrder filtered by the subscription_title column
 * @method POrder findOneBySubscriptionDescription(string $subscription_description) Return the first POrder filtered by the subscription_description column
 * @method POrder findOneBySubscriptionBeginAt(string $subscription_begin_at) Return the first POrder filtered by the subscription_begin_at column
 * @method POrder findOneBySubscriptionEndAt(string $subscription_end_at) Return the first POrder filtered by the subscription_end_at column
 * @method POrder findOneByInformation(string $information) Return the first POrder filtered by the information column
 * @method POrder findOneByPrice(string $price) Return the first POrder filtered by the price column
 * @method POrder findOneByPromotion(string $promotion) Return the first POrder filtered by the promotion column
 * @method POrder findOneByTotal(string $total) Return the first POrder filtered by the total column
 * @method POrder findOneByGender(int $gender) Return the first POrder filtered by the gender column
 * @method POrder findOneByName(string $name) Return the first POrder filtered by the name column
 * @method POrder findOneByFirstname(string $firstname) Return the first POrder filtered by the firstname column
 * @method POrder findOneByPhone(string $phone) Return the first POrder filtered by the phone column
 * @method POrder findOneByEmail(string $email) Return the first POrder filtered by the email column
 * @method POrder findOneByInvoiceRef(string $invoice_ref) Return the first POrder filtered by the invoice_ref column
 * @method POrder findOneByInvoiceAt(string $invoice_at) Return the first POrder filtered by the invoice_at column
 * @method POrder findOneByInvoiceFilename(string $invoice_filename) Return the first POrder filtered by the invoice_filename column
 * @method POrder findOneBySupportingDocument(string $supporting_document) Return the first POrder filtered by the supporting_document column
 * @method POrder findOneByElectiveMandates(string $elective_mandates) Return the first POrder filtered by the elective_mandates column
 * @method POrder findOneByCreatedAt(string $created_at) Return the first POrder filtered by the created_at column
 * @method POrder findOneByUpdatedAt(string $updated_at) Return the first POrder filtered by the updated_at column
 *
 * @method array findById(int $id) Return POrder objects filtered by the id column
 * @method array findByPUserId(int $p_user_id) Return POrder objects filtered by the p_user_id column
 * @method array findByPOOrderStateId(int $p_o_order_state_id) Return POrder objects filtered by the p_o_order_state_id column
 * @method array findByPOPaymentStateId(int $p_o_payment_state_id) Return POrder objects filtered by the p_o_payment_state_id column
 * @method array findByPOPaymentTypeId(int $p_o_payment_type_id) Return POrder objects filtered by the p_o_payment_type_id column
 * @method array findByPOSubscriptionId(int $p_o_subscription_id) Return POrder objects filtered by the p_o_subscription_id column
 * @method array findBySubscriptionTitle(string $subscription_title) Return POrder objects filtered by the subscription_title column
 * @method array findBySubscriptionDescription(string $subscription_description) Return POrder objects filtered by the subscription_description column
 * @method array findBySubscriptionBeginAt(string $subscription_begin_at) Return POrder objects filtered by the subscription_begin_at column
 * @method array findBySubscriptionEndAt(string $subscription_end_at) Return POrder objects filtered by the subscription_end_at column
 * @method array findByInformation(string $information) Return POrder objects filtered by the information column
 * @method array findByPrice(string $price) Return POrder objects filtered by the price column
 * @method array findByPromotion(string $promotion) Return POrder objects filtered by the promotion column
 * @method array findByTotal(string $total) Return POrder objects filtered by the total column
 * @method array findByGender(int $gender) Return POrder objects filtered by the gender column
 * @method array findByName(string $name) Return POrder objects filtered by the name column
 * @method array findByFirstname(string $firstname) Return POrder objects filtered by the firstname column
 * @method array findByPhone(string $phone) Return POrder objects filtered by the phone column
 * @method array findByEmail(string $email) Return POrder objects filtered by the email column
 * @method array findByInvoiceRef(string $invoice_ref) Return POrder objects filtered by the invoice_ref column
 * @method array findByInvoiceAt(string $invoice_at) Return POrder objects filtered by the invoice_at column
 * @method array findByInvoiceFilename(string $invoice_filename) Return POrder objects filtered by the invoice_filename column
 * @method array findBySupportingDocument(string $supporting_document) Return POrder objects filtered by the supporting_document column
 * @method array findByElectiveMandates(string $elective_mandates) Return POrder objects filtered by the elective_mandates column
 * @method array findByCreatedAt(string $created_at) Return POrder objects filtered by the created_at column
 * @method array findByUpdatedAt(string $updated_at) Return POrder objects filtered by the updated_at column
 */
abstract class BasePOrderQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BasePOrderQuery object.
     *
     * @param     string $dbName The dabase name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = 'Politizr\\Model\\POrder', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new POrderQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   POrderQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return POrderQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof POrderQuery) {
            return $criteria;
        }
        $query = new POrderQuery();
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
     * @return   POrder|POrder[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = POrderPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is alredy in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(POrderPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 POrder A model object, or null if the key is not found
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
     * @return                 POrder A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `p_user_id`, `p_o_order_state_id`, `p_o_payment_state_id`, `p_o_payment_type_id`, `p_o_subscription_id`, `subscription_title`, `subscription_description`, `subscription_begin_at`, `subscription_end_at`, `information`, `price`, `promotion`, `total`, `gender`, `name`, `firstname`, `phone`, `email`, `invoice_ref`, `invoice_at`, `invoice_filename`, `supporting_document`, `elective_mandates`, `created_at`, `updated_at` FROM `p_order` WHERE `id` = :p0';
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
            $obj = new POrder();
            $obj->hydrate($row);
            POrderPeer::addInstanceToPool($obj, (string) $key);
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
     * @return POrder|POrder[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|POrder[]|mixed the list of results, formatted by the current formatter
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
     * @return POrderQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(POrderPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return POrderQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(POrderPeer::ID, $keys, Criteria::IN);
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
     * @return POrderQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(POrderPeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(POrderPeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(POrderPeer::ID, $id, $comparison);
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
     * @see       filterByPUser()
     *
     * @param     mixed $pUserId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return POrderQuery The current query, for fluid interface
     */
    public function filterByPUserId($pUserId = null, $comparison = null)
    {
        if (is_array($pUserId)) {
            $useMinMax = false;
            if (isset($pUserId['min'])) {
                $this->addUsingAlias(POrderPeer::P_USER_ID, $pUserId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($pUserId['max'])) {
                $this->addUsingAlias(POrderPeer::P_USER_ID, $pUserId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(POrderPeer::P_USER_ID, $pUserId, $comparison);
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
     * @return POrderQuery The current query, for fluid interface
     */
    public function filterByPOOrderStateId($pOOrderStateId = null, $comparison = null)
    {
        if (is_array($pOOrderStateId)) {
            $useMinMax = false;
            if (isset($pOOrderStateId['min'])) {
                $this->addUsingAlias(POrderPeer::P_O_ORDER_STATE_ID, $pOOrderStateId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($pOOrderStateId['max'])) {
                $this->addUsingAlias(POrderPeer::P_O_ORDER_STATE_ID, $pOOrderStateId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(POrderPeer::P_O_ORDER_STATE_ID, $pOOrderStateId, $comparison);
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
     * @return POrderQuery The current query, for fluid interface
     */
    public function filterByPOPaymentStateId($pOPaymentStateId = null, $comparison = null)
    {
        if (is_array($pOPaymentStateId)) {
            $useMinMax = false;
            if (isset($pOPaymentStateId['min'])) {
                $this->addUsingAlias(POrderPeer::P_O_PAYMENT_STATE_ID, $pOPaymentStateId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($pOPaymentStateId['max'])) {
                $this->addUsingAlias(POrderPeer::P_O_PAYMENT_STATE_ID, $pOPaymentStateId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(POrderPeer::P_O_PAYMENT_STATE_ID, $pOPaymentStateId, $comparison);
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
     * @return POrderQuery The current query, for fluid interface
     */
    public function filterByPOPaymentTypeId($pOPaymentTypeId = null, $comparison = null)
    {
        if (is_array($pOPaymentTypeId)) {
            $useMinMax = false;
            if (isset($pOPaymentTypeId['min'])) {
                $this->addUsingAlias(POrderPeer::P_O_PAYMENT_TYPE_ID, $pOPaymentTypeId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($pOPaymentTypeId['max'])) {
                $this->addUsingAlias(POrderPeer::P_O_PAYMENT_TYPE_ID, $pOPaymentTypeId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(POrderPeer::P_O_PAYMENT_TYPE_ID, $pOPaymentTypeId, $comparison);
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
     * @return POrderQuery The current query, for fluid interface
     */
    public function filterByPOSubscriptionId($pOSubscriptionId = null, $comparison = null)
    {
        if (is_array($pOSubscriptionId)) {
            $useMinMax = false;
            if (isset($pOSubscriptionId['min'])) {
                $this->addUsingAlias(POrderPeer::P_O_SUBSCRIPTION_ID, $pOSubscriptionId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($pOSubscriptionId['max'])) {
                $this->addUsingAlias(POrderPeer::P_O_SUBSCRIPTION_ID, $pOSubscriptionId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(POrderPeer::P_O_SUBSCRIPTION_ID, $pOSubscriptionId, $comparison);
    }

    /**
     * Filter the query on the subscription_title column
     *
     * Example usage:
     * <code>
     * $query->filterBySubscriptionTitle('fooValue');   // WHERE subscription_title = 'fooValue'
     * $query->filterBySubscriptionTitle('%fooValue%'); // WHERE subscription_title LIKE '%fooValue%'
     * </code>
     *
     * @param     string $subscriptionTitle The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return POrderQuery The current query, for fluid interface
     */
    public function filterBySubscriptionTitle($subscriptionTitle = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($subscriptionTitle)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $subscriptionTitle)) {
                $subscriptionTitle = str_replace('*', '%', $subscriptionTitle);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(POrderPeer::SUBSCRIPTION_TITLE, $subscriptionTitle, $comparison);
    }

    /**
     * Filter the query on the subscription_description column
     *
     * Example usage:
     * <code>
     * $query->filterBySubscriptionDescription('fooValue');   // WHERE subscription_description = 'fooValue'
     * $query->filterBySubscriptionDescription('%fooValue%'); // WHERE subscription_description LIKE '%fooValue%'
     * </code>
     *
     * @param     string $subscriptionDescription The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return POrderQuery The current query, for fluid interface
     */
    public function filterBySubscriptionDescription($subscriptionDescription = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($subscriptionDescription)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $subscriptionDescription)) {
                $subscriptionDescription = str_replace('*', '%', $subscriptionDescription);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(POrderPeer::SUBSCRIPTION_DESCRIPTION, $subscriptionDescription, $comparison);
    }

    /**
     * Filter the query on the subscription_begin_at column
     *
     * Example usage:
     * <code>
     * $query->filterBySubscriptionBeginAt('2011-03-14'); // WHERE subscription_begin_at = '2011-03-14'
     * $query->filterBySubscriptionBeginAt('now'); // WHERE subscription_begin_at = '2011-03-14'
     * $query->filterBySubscriptionBeginAt(array('max' => 'yesterday')); // WHERE subscription_begin_at > '2011-03-13'
     * </code>
     *
     * @param     mixed $subscriptionBeginAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return POrderQuery The current query, for fluid interface
     */
    public function filterBySubscriptionBeginAt($subscriptionBeginAt = null, $comparison = null)
    {
        if (is_array($subscriptionBeginAt)) {
            $useMinMax = false;
            if (isset($subscriptionBeginAt['min'])) {
                $this->addUsingAlias(POrderPeer::SUBSCRIPTION_BEGIN_AT, $subscriptionBeginAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($subscriptionBeginAt['max'])) {
                $this->addUsingAlias(POrderPeer::SUBSCRIPTION_BEGIN_AT, $subscriptionBeginAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(POrderPeer::SUBSCRIPTION_BEGIN_AT, $subscriptionBeginAt, $comparison);
    }

    /**
     * Filter the query on the subscription_end_at column
     *
     * Example usage:
     * <code>
     * $query->filterBySubscriptionEndAt('2011-03-14'); // WHERE subscription_end_at = '2011-03-14'
     * $query->filterBySubscriptionEndAt('now'); // WHERE subscription_end_at = '2011-03-14'
     * $query->filterBySubscriptionEndAt(array('max' => 'yesterday')); // WHERE subscription_end_at > '2011-03-13'
     * </code>
     *
     * @param     mixed $subscriptionEndAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return POrderQuery The current query, for fluid interface
     */
    public function filterBySubscriptionEndAt($subscriptionEndAt = null, $comparison = null)
    {
        if (is_array($subscriptionEndAt)) {
            $useMinMax = false;
            if (isset($subscriptionEndAt['min'])) {
                $this->addUsingAlias(POrderPeer::SUBSCRIPTION_END_AT, $subscriptionEndAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($subscriptionEndAt['max'])) {
                $this->addUsingAlias(POrderPeer::SUBSCRIPTION_END_AT, $subscriptionEndAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(POrderPeer::SUBSCRIPTION_END_AT, $subscriptionEndAt, $comparison);
    }

    /**
     * Filter the query on the information column
     *
     * Example usage:
     * <code>
     * $query->filterByInformation('fooValue');   // WHERE information = 'fooValue'
     * $query->filterByInformation('%fooValue%'); // WHERE information LIKE '%fooValue%'
     * </code>
     *
     * @param     string $information The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return POrderQuery The current query, for fluid interface
     */
    public function filterByInformation($information = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($information)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $information)) {
                $information = str_replace('*', '%', $information);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(POrderPeer::INFORMATION, $information, $comparison);
    }

    /**
     * Filter the query on the price column
     *
     * Example usage:
     * <code>
     * $query->filterByPrice(1234); // WHERE price = 1234
     * $query->filterByPrice(array(12, 34)); // WHERE price IN (12, 34)
     * $query->filterByPrice(array('min' => 12)); // WHERE price >= 12
     * $query->filterByPrice(array('max' => 12)); // WHERE price <= 12
     * </code>
     *
     * @param     mixed $price The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return POrderQuery The current query, for fluid interface
     */
    public function filterByPrice($price = null, $comparison = null)
    {
        if (is_array($price)) {
            $useMinMax = false;
            if (isset($price['min'])) {
                $this->addUsingAlias(POrderPeer::PRICE, $price['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($price['max'])) {
                $this->addUsingAlias(POrderPeer::PRICE, $price['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(POrderPeer::PRICE, $price, $comparison);
    }

    /**
     * Filter the query on the promotion column
     *
     * Example usage:
     * <code>
     * $query->filterByPromotion(1234); // WHERE promotion = 1234
     * $query->filterByPromotion(array(12, 34)); // WHERE promotion IN (12, 34)
     * $query->filterByPromotion(array('min' => 12)); // WHERE promotion >= 12
     * $query->filterByPromotion(array('max' => 12)); // WHERE promotion <= 12
     * </code>
     *
     * @param     mixed $promotion The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return POrderQuery The current query, for fluid interface
     */
    public function filterByPromotion($promotion = null, $comparison = null)
    {
        if (is_array($promotion)) {
            $useMinMax = false;
            if (isset($promotion['min'])) {
                $this->addUsingAlias(POrderPeer::PROMOTION, $promotion['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($promotion['max'])) {
                $this->addUsingAlias(POrderPeer::PROMOTION, $promotion['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(POrderPeer::PROMOTION, $promotion, $comparison);
    }

    /**
     * Filter the query on the total column
     *
     * Example usage:
     * <code>
     * $query->filterByTotal(1234); // WHERE total = 1234
     * $query->filterByTotal(array(12, 34)); // WHERE total IN (12, 34)
     * $query->filterByTotal(array('min' => 12)); // WHERE total >= 12
     * $query->filterByTotal(array('max' => 12)); // WHERE total <= 12
     * </code>
     *
     * @param     mixed $total The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return POrderQuery The current query, for fluid interface
     */
    public function filterByTotal($total = null, $comparison = null)
    {
        if (is_array($total)) {
            $useMinMax = false;
            if (isset($total['min'])) {
                $this->addUsingAlias(POrderPeer::TOTAL, $total['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($total['max'])) {
                $this->addUsingAlias(POrderPeer::TOTAL, $total['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(POrderPeer::TOTAL, $total, $comparison);
    }

    /**
     * Filter the query on the gender column
     *
     * @param     mixed $gender The value to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return POrderQuery The current query, for fluid interface
     * @throws PropelException - if the value is not accepted by the enum.
     */
    public function filterByGender($gender = null, $comparison = null)
    {
        if (is_scalar($gender)) {
            $gender = POrderPeer::getSqlValueForEnum(POrderPeer::GENDER, $gender);
        } elseif (is_array($gender)) {
            $convertedValues = array();
            foreach ($gender as $value) {
                $convertedValues[] = POrderPeer::getSqlValueForEnum(POrderPeer::GENDER, $value);
            }
            $gender = $convertedValues;
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(POrderPeer::GENDER, $gender, $comparison);
    }

    /**
     * Filter the query on the name column
     *
     * Example usage:
     * <code>
     * $query->filterByName('fooValue');   // WHERE name = 'fooValue'
     * $query->filterByName('%fooValue%'); // WHERE name LIKE '%fooValue%'
     * </code>
     *
     * @param     string $name The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return POrderQuery The current query, for fluid interface
     */
    public function filterByName($name = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($name)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $name)) {
                $name = str_replace('*', '%', $name);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(POrderPeer::NAME, $name, $comparison);
    }

    /**
     * Filter the query on the firstname column
     *
     * Example usage:
     * <code>
     * $query->filterByFirstname('fooValue');   // WHERE firstname = 'fooValue'
     * $query->filterByFirstname('%fooValue%'); // WHERE firstname LIKE '%fooValue%'
     * </code>
     *
     * @param     string $firstname The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return POrderQuery The current query, for fluid interface
     */
    public function filterByFirstname($firstname = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($firstname)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $firstname)) {
                $firstname = str_replace('*', '%', $firstname);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(POrderPeer::FIRSTNAME, $firstname, $comparison);
    }

    /**
     * Filter the query on the phone column
     *
     * Example usage:
     * <code>
     * $query->filterByPhone('fooValue');   // WHERE phone = 'fooValue'
     * $query->filterByPhone('%fooValue%'); // WHERE phone LIKE '%fooValue%'
     * </code>
     *
     * @param     string $phone The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return POrderQuery The current query, for fluid interface
     */
    public function filterByPhone($phone = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($phone)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $phone)) {
                $phone = str_replace('*', '%', $phone);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(POrderPeer::PHONE, $phone, $comparison);
    }

    /**
     * Filter the query on the email column
     *
     * Example usage:
     * <code>
     * $query->filterByEmail('fooValue');   // WHERE email = 'fooValue'
     * $query->filterByEmail('%fooValue%'); // WHERE email LIKE '%fooValue%'
     * </code>
     *
     * @param     string $email The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return POrderQuery The current query, for fluid interface
     */
    public function filterByEmail($email = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($email)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $email)) {
                $email = str_replace('*', '%', $email);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(POrderPeer::EMAIL, $email, $comparison);
    }

    /**
     * Filter the query on the invoice_ref column
     *
     * Example usage:
     * <code>
     * $query->filterByInvoiceRef('fooValue');   // WHERE invoice_ref = 'fooValue'
     * $query->filterByInvoiceRef('%fooValue%'); // WHERE invoice_ref LIKE '%fooValue%'
     * </code>
     *
     * @param     string $invoiceRef The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return POrderQuery The current query, for fluid interface
     */
    public function filterByInvoiceRef($invoiceRef = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($invoiceRef)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $invoiceRef)) {
                $invoiceRef = str_replace('*', '%', $invoiceRef);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(POrderPeer::INVOICE_REF, $invoiceRef, $comparison);
    }

    /**
     * Filter the query on the invoice_at column
     *
     * Example usage:
     * <code>
     * $query->filterByInvoiceAt('2011-03-14'); // WHERE invoice_at = '2011-03-14'
     * $query->filterByInvoiceAt('now'); // WHERE invoice_at = '2011-03-14'
     * $query->filterByInvoiceAt(array('max' => 'yesterday')); // WHERE invoice_at > '2011-03-13'
     * </code>
     *
     * @param     mixed $invoiceAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return POrderQuery The current query, for fluid interface
     */
    public function filterByInvoiceAt($invoiceAt = null, $comparison = null)
    {
        if (is_array($invoiceAt)) {
            $useMinMax = false;
            if (isset($invoiceAt['min'])) {
                $this->addUsingAlias(POrderPeer::INVOICE_AT, $invoiceAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($invoiceAt['max'])) {
                $this->addUsingAlias(POrderPeer::INVOICE_AT, $invoiceAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(POrderPeer::INVOICE_AT, $invoiceAt, $comparison);
    }

    /**
     * Filter the query on the invoice_filename column
     *
     * Example usage:
     * <code>
     * $query->filterByInvoiceFilename('fooValue');   // WHERE invoice_filename = 'fooValue'
     * $query->filterByInvoiceFilename('%fooValue%'); // WHERE invoice_filename LIKE '%fooValue%'
     * </code>
     *
     * @param     string $invoiceFilename The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return POrderQuery The current query, for fluid interface
     */
    public function filterByInvoiceFilename($invoiceFilename = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($invoiceFilename)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $invoiceFilename)) {
                $invoiceFilename = str_replace('*', '%', $invoiceFilename);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(POrderPeer::INVOICE_FILENAME, $invoiceFilename, $comparison);
    }

    /**
     * Filter the query on the supporting_document column
     *
     * Example usage:
     * <code>
     * $query->filterBySupportingDocument('fooValue');   // WHERE supporting_document = 'fooValue'
     * $query->filterBySupportingDocument('%fooValue%'); // WHERE supporting_document LIKE '%fooValue%'
     * </code>
     *
     * @param     string $supportingDocument The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return POrderQuery The current query, for fluid interface
     */
    public function filterBySupportingDocument($supportingDocument = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($supportingDocument)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $supportingDocument)) {
                $supportingDocument = str_replace('*', '%', $supportingDocument);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(POrderPeer::SUPPORTING_DOCUMENT, $supportingDocument, $comparison);
    }

    /**
     * Filter the query on the elective_mandates column
     *
     * Example usage:
     * <code>
     * $query->filterByElectiveMandates('fooValue');   // WHERE elective_mandates = 'fooValue'
     * $query->filterByElectiveMandates('%fooValue%'); // WHERE elective_mandates LIKE '%fooValue%'
     * </code>
     *
     * @param     string $electiveMandates The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return POrderQuery The current query, for fluid interface
     */
    public function filterByElectiveMandates($electiveMandates = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($electiveMandates)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $electiveMandates)) {
                $electiveMandates = str_replace('*', '%', $electiveMandates);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(POrderPeer::ELECTIVE_MANDATES, $electiveMandates, $comparison);
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
     * @return POrderQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(POrderPeer::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(POrderPeer::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(POrderPeer::CREATED_AT, $createdAt, $comparison);
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
     * @return POrderQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(POrderPeer::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(POrderPeer::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(POrderPeer::UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query by a related PUser object
     *
     * @param   PUser|PropelObjectCollection $pUser The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 POrderQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPUser($pUser, $comparison = null)
    {
        if ($pUser instanceof PUser) {
            return $this
                ->addUsingAlias(POrderPeer::P_USER_ID, $pUser->getId(), $comparison);
        } elseif ($pUser instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(POrderPeer::P_USER_ID, $pUser->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByPUser() only accepts arguments of type PUser or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PUser relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return POrderQuery The current query, for fluid interface
     */
    public function joinPUser($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PUser');

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
            $this->addJoinObject($join, 'PUser');
        }

        return $this;
    }

    /**
     * Use the PUser relation PUser object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Politizr\Model\PUserQuery A secondary query class using the current class as primary query
     */
    public function usePUserQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinPUser($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PUser', '\Politizr\Model\PUserQuery');
    }

    /**
     * Filter the query by a related POOrderState object
     *
     * @param   POOrderState|PropelObjectCollection $pOOrderState The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 POrderQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPOOrderState($pOOrderState, $comparison = null)
    {
        if ($pOOrderState instanceof POOrderState) {
            return $this
                ->addUsingAlias(POrderPeer::P_O_ORDER_STATE_ID, $pOOrderState->getId(), $comparison);
        } elseif ($pOOrderState instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(POrderPeer::P_O_ORDER_STATE_ID, $pOOrderState->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return POrderQuery The current query, for fluid interface
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
     * @return                 POrderQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPOPaymentState($pOPaymentState, $comparison = null)
    {
        if ($pOPaymentState instanceof POPaymentState) {
            return $this
                ->addUsingAlias(POrderPeer::P_O_PAYMENT_STATE_ID, $pOPaymentState->getId(), $comparison);
        } elseif ($pOPaymentState instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(POrderPeer::P_O_PAYMENT_STATE_ID, $pOPaymentState->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return POrderQuery The current query, for fluid interface
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
     * @return                 POrderQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPOPaymentType($pOPaymentType, $comparison = null)
    {
        if ($pOPaymentType instanceof POPaymentType) {
            return $this
                ->addUsingAlias(POrderPeer::P_O_PAYMENT_TYPE_ID, $pOPaymentType->getId(), $comparison);
        } elseif ($pOPaymentType instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(POrderPeer::P_O_PAYMENT_TYPE_ID, $pOPaymentType->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return POrderQuery The current query, for fluid interface
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
     * @return                 POrderQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPOSubscription($pOSubscription, $comparison = null)
    {
        if ($pOSubscription instanceof POSubscription) {
            return $this
                ->addUsingAlias(POrderPeer::P_O_SUBSCRIPTION_ID, $pOSubscription->getId(), $comparison);
        } elseif ($pOSubscription instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(POrderPeer::P_O_SUBSCRIPTION_ID, $pOSubscription->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return POrderQuery The current query, for fluid interface
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
     * Filter the query by a related POEmail object
     *
     * @param   POEmail|PropelObjectCollection $pOEmail  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 POrderQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPOEmail($pOEmail, $comparison = null)
    {
        if ($pOEmail instanceof POEmail) {
            return $this
                ->addUsingAlias(POrderPeer::ID, $pOEmail->getPOrderId(), $comparison);
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
     * @return POrderQuery The current query, for fluid interface
     */
    public function joinPOEmail($relationAlias = null, $joinType = Criteria::INNER_JOIN)
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
    public function usePOEmailQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPOEmail($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'POEmail', '\Politizr\Model\POEmailQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   POrder $pOrder Object to remove from the list of results
     *
     * @return POrderQuery The current query, for fluid interface
     */
    public function prune($pOrder = null)
    {
        if ($pOrder) {
            $this->addUsingAlias(POrderPeer::ID, $pOrder->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     POrderQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(POrderPeer::UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     POrderQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(POrderPeer::UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     POrderQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(POrderPeer::UPDATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     POrderQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(POrderPeer::CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date desc
     *
     * @return     POrderQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(POrderPeer::CREATED_AT);
    }

    /**
     * Order by create date asc
     *
     * @return     POrderQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(POrderPeer::CREATED_AT);
    }
}
