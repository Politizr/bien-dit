<?php

namespace Politizr\Model\om;

use \Criteria;
use \Exception;
use \ModelCriteria;
use \PDO;
use \Propel;
use \PropelException;
use \PropelObjectCollection;
use \PropelPDO;
use Glorpen\Propel\PropelBundle\Dispatcher\EventDispatcherProxy;
use Glorpen\Propel\PropelBundle\Events\QueryEvent;
use Politizr\Model\POrderArchive;
use Politizr\Model\POrderArchivePeer;
use Politizr\Model\POrderArchiveQuery;

/**
 * @method POrderArchiveQuery orderById($order = Criteria::ASC) Order by the id column
 * @method POrderArchiveQuery orderByPUserId($order = Criteria::ASC) Order by the p_user_id column
 * @method POrderArchiveQuery orderByPOOrderStateId($order = Criteria::ASC) Order by the p_o_order_state_id column
 * @method POrderArchiveQuery orderByPOPaymentStateId($order = Criteria::ASC) Order by the p_o_payment_state_id column
 * @method POrderArchiveQuery orderByPOPaymentTypeId($order = Criteria::ASC) Order by the p_o_payment_type_id column
 * @method POrderArchiveQuery orderByPOSubscriptionId($order = Criteria::ASC) Order by the p_o_subscription_id column
 * @method POrderArchiveQuery orderBySubscriptionTitle($order = Criteria::ASC) Order by the subscription_title column
 * @method POrderArchiveQuery orderBySubscriptionDescription($order = Criteria::ASC) Order by the subscription_description column
 * @method POrderArchiveQuery orderBySubscriptionBeginAt($order = Criteria::ASC) Order by the subscription_begin_at column
 * @method POrderArchiveQuery orderBySubscriptionEndAt($order = Criteria::ASC) Order by the subscription_end_at column
 * @method POrderArchiveQuery orderByInformation($order = Criteria::ASC) Order by the information column
 * @method POrderArchiveQuery orderByPrice($order = Criteria::ASC) Order by the price column
 * @method POrderArchiveQuery orderByPromotion($order = Criteria::ASC) Order by the promotion column
 * @method POrderArchiveQuery orderByTotal($order = Criteria::ASC) Order by the total column
 * @method POrderArchiveQuery orderByGender($order = Criteria::ASC) Order by the gender column
 * @method POrderArchiveQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method POrderArchiveQuery orderByFirstname($order = Criteria::ASC) Order by the firstname column
 * @method POrderArchiveQuery orderByPhone($order = Criteria::ASC) Order by the phone column
 * @method POrderArchiveQuery orderByEmail($order = Criteria::ASC) Order by the email column
 * @method POrderArchiveQuery orderByInvoiceRef($order = Criteria::ASC) Order by the invoice_ref column
 * @method POrderArchiveQuery orderByInvoiceAt($order = Criteria::ASC) Order by the invoice_at column
 * @method POrderArchiveQuery orderByInvoiceFilename($order = Criteria::ASC) Order by the invoice_filename column
 * @method POrderArchiveQuery orderBySupportingDocument($order = Criteria::ASC) Order by the supporting_document column
 * @method POrderArchiveQuery orderByElectiveMandates($order = Criteria::ASC) Order by the elective_mandates column
 * @method POrderArchiveQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method POrderArchiveQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 * @method POrderArchiveQuery orderByArchivedAt($order = Criteria::ASC) Order by the archived_at column
 *
 * @method POrderArchiveQuery groupById() Group by the id column
 * @method POrderArchiveQuery groupByPUserId() Group by the p_user_id column
 * @method POrderArchiveQuery groupByPOOrderStateId() Group by the p_o_order_state_id column
 * @method POrderArchiveQuery groupByPOPaymentStateId() Group by the p_o_payment_state_id column
 * @method POrderArchiveQuery groupByPOPaymentTypeId() Group by the p_o_payment_type_id column
 * @method POrderArchiveQuery groupByPOSubscriptionId() Group by the p_o_subscription_id column
 * @method POrderArchiveQuery groupBySubscriptionTitle() Group by the subscription_title column
 * @method POrderArchiveQuery groupBySubscriptionDescription() Group by the subscription_description column
 * @method POrderArchiveQuery groupBySubscriptionBeginAt() Group by the subscription_begin_at column
 * @method POrderArchiveQuery groupBySubscriptionEndAt() Group by the subscription_end_at column
 * @method POrderArchiveQuery groupByInformation() Group by the information column
 * @method POrderArchiveQuery groupByPrice() Group by the price column
 * @method POrderArchiveQuery groupByPromotion() Group by the promotion column
 * @method POrderArchiveQuery groupByTotal() Group by the total column
 * @method POrderArchiveQuery groupByGender() Group by the gender column
 * @method POrderArchiveQuery groupByName() Group by the name column
 * @method POrderArchiveQuery groupByFirstname() Group by the firstname column
 * @method POrderArchiveQuery groupByPhone() Group by the phone column
 * @method POrderArchiveQuery groupByEmail() Group by the email column
 * @method POrderArchiveQuery groupByInvoiceRef() Group by the invoice_ref column
 * @method POrderArchiveQuery groupByInvoiceAt() Group by the invoice_at column
 * @method POrderArchiveQuery groupByInvoiceFilename() Group by the invoice_filename column
 * @method POrderArchiveQuery groupBySupportingDocument() Group by the supporting_document column
 * @method POrderArchiveQuery groupByElectiveMandates() Group by the elective_mandates column
 * @method POrderArchiveQuery groupByCreatedAt() Group by the created_at column
 * @method POrderArchiveQuery groupByUpdatedAt() Group by the updated_at column
 * @method POrderArchiveQuery groupByArchivedAt() Group by the archived_at column
 *
 * @method POrderArchiveQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method POrderArchiveQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method POrderArchiveQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method POrderArchive findOne(PropelPDO $con = null) Return the first POrderArchive matching the query
 * @method POrderArchive findOneOrCreate(PropelPDO $con = null) Return the first POrderArchive matching the query, or a new POrderArchive object populated from the query conditions when no match is found
 *
 * @method POrderArchive findOneByPUserId(int $p_user_id) Return the first POrderArchive filtered by the p_user_id column
 * @method POrderArchive findOneByPOOrderStateId(int $p_o_order_state_id) Return the first POrderArchive filtered by the p_o_order_state_id column
 * @method POrderArchive findOneByPOPaymentStateId(int $p_o_payment_state_id) Return the first POrderArchive filtered by the p_o_payment_state_id column
 * @method POrderArchive findOneByPOPaymentTypeId(int $p_o_payment_type_id) Return the first POrderArchive filtered by the p_o_payment_type_id column
 * @method POrderArchive findOneByPOSubscriptionId(int $p_o_subscription_id) Return the first POrderArchive filtered by the p_o_subscription_id column
 * @method POrderArchive findOneBySubscriptionTitle(string $subscription_title) Return the first POrderArchive filtered by the subscription_title column
 * @method POrderArchive findOneBySubscriptionDescription(string $subscription_description) Return the first POrderArchive filtered by the subscription_description column
 * @method POrderArchive findOneBySubscriptionBeginAt(string $subscription_begin_at) Return the first POrderArchive filtered by the subscription_begin_at column
 * @method POrderArchive findOneBySubscriptionEndAt(string $subscription_end_at) Return the first POrderArchive filtered by the subscription_end_at column
 * @method POrderArchive findOneByInformation(string $information) Return the first POrderArchive filtered by the information column
 * @method POrderArchive findOneByPrice(string $price) Return the first POrderArchive filtered by the price column
 * @method POrderArchive findOneByPromotion(string $promotion) Return the first POrderArchive filtered by the promotion column
 * @method POrderArchive findOneByTotal(string $total) Return the first POrderArchive filtered by the total column
 * @method POrderArchive findOneByGender(int $gender) Return the first POrderArchive filtered by the gender column
 * @method POrderArchive findOneByName(string $name) Return the first POrderArchive filtered by the name column
 * @method POrderArchive findOneByFirstname(string $firstname) Return the first POrderArchive filtered by the firstname column
 * @method POrderArchive findOneByPhone(string $phone) Return the first POrderArchive filtered by the phone column
 * @method POrderArchive findOneByEmail(string $email) Return the first POrderArchive filtered by the email column
 * @method POrderArchive findOneByInvoiceRef(string $invoice_ref) Return the first POrderArchive filtered by the invoice_ref column
 * @method POrderArchive findOneByInvoiceAt(string $invoice_at) Return the first POrderArchive filtered by the invoice_at column
 * @method POrderArchive findOneByInvoiceFilename(string $invoice_filename) Return the first POrderArchive filtered by the invoice_filename column
 * @method POrderArchive findOneBySupportingDocument(string $supporting_document) Return the first POrderArchive filtered by the supporting_document column
 * @method POrderArchive findOneByElectiveMandates(string $elective_mandates) Return the first POrderArchive filtered by the elective_mandates column
 * @method POrderArchive findOneByCreatedAt(string $created_at) Return the first POrderArchive filtered by the created_at column
 * @method POrderArchive findOneByUpdatedAt(string $updated_at) Return the first POrderArchive filtered by the updated_at column
 * @method POrderArchive findOneByArchivedAt(string $archived_at) Return the first POrderArchive filtered by the archived_at column
 *
 * @method array findById(int $id) Return POrderArchive objects filtered by the id column
 * @method array findByPUserId(int $p_user_id) Return POrderArchive objects filtered by the p_user_id column
 * @method array findByPOOrderStateId(int $p_o_order_state_id) Return POrderArchive objects filtered by the p_o_order_state_id column
 * @method array findByPOPaymentStateId(int $p_o_payment_state_id) Return POrderArchive objects filtered by the p_o_payment_state_id column
 * @method array findByPOPaymentTypeId(int $p_o_payment_type_id) Return POrderArchive objects filtered by the p_o_payment_type_id column
 * @method array findByPOSubscriptionId(int $p_o_subscription_id) Return POrderArchive objects filtered by the p_o_subscription_id column
 * @method array findBySubscriptionTitle(string $subscription_title) Return POrderArchive objects filtered by the subscription_title column
 * @method array findBySubscriptionDescription(string $subscription_description) Return POrderArchive objects filtered by the subscription_description column
 * @method array findBySubscriptionBeginAt(string $subscription_begin_at) Return POrderArchive objects filtered by the subscription_begin_at column
 * @method array findBySubscriptionEndAt(string $subscription_end_at) Return POrderArchive objects filtered by the subscription_end_at column
 * @method array findByInformation(string $information) Return POrderArchive objects filtered by the information column
 * @method array findByPrice(string $price) Return POrderArchive objects filtered by the price column
 * @method array findByPromotion(string $promotion) Return POrderArchive objects filtered by the promotion column
 * @method array findByTotal(string $total) Return POrderArchive objects filtered by the total column
 * @method array findByGender(int $gender) Return POrderArchive objects filtered by the gender column
 * @method array findByName(string $name) Return POrderArchive objects filtered by the name column
 * @method array findByFirstname(string $firstname) Return POrderArchive objects filtered by the firstname column
 * @method array findByPhone(string $phone) Return POrderArchive objects filtered by the phone column
 * @method array findByEmail(string $email) Return POrderArchive objects filtered by the email column
 * @method array findByInvoiceRef(string $invoice_ref) Return POrderArchive objects filtered by the invoice_ref column
 * @method array findByInvoiceAt(string $invoice_at) Return POrderArchive objects filtered by the invoice_at column
 * @method array findByInvoiceFilename(string $invoice_filename) Return POrderArchive objects filtered by the invoice_filename column
 * @method array findBySupportingDocument(string $supporting_document) Return POrderArchive objects filtered by the supporting_document column
 * @method array findByElectiveMandates(string $elective_mandates) Return POrderArchive objects filtered by the elective_mandates column
 * @method array findByCreatedAt(string $created_at) Return POrderArchive objects filtered by the created_at column
 * @method array findByUpdatedAt(string $updated_at) Return POrderArchive objects filtered by the updated_at column
 * @method array findByArchivedAt(string $archived_at) Return POrderArchive objects filtered by the archived_at column
 */
abstract class BasePOrderArchiveQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BasePOrderArchiveQuery object.
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
            $modelName = 'Politizr\\Model\\POrderArchive';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
        EventDispatcherProxy::trigger(array('construct','query.construct'), new QueryEvent($this));
    }

    /**
     * Returns a new POrderArchiveQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   POrderArchiveQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return POrderArchiveQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof POrderArchiveQuery) {
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
     * @return   POrderArchive|POrderArchive[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = POrderArchivePeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(POrderArchivePeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 POrderArchive A model object, or null if the key is not found
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
     * @return                 POrderArchive A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `p_user_id`, `p_o_order_state_id`, `p_o_payment_state_id`, `p_o_payment_type_id`, `p_o_subscription_id`, `subscription_title`, `subscription_description`, `subscription_begin_at`, `subscription_end_at`, `information`, `price`, `promotion`, `total`, `gender`, `name`, `firstname`, `phone`, `email`, `invoice_ref`, `invoice_at`, `invoice_filename`, `supporting_document`, `elective_mandates`, `created_at`, `updated_at`, `archived_at` FROM `p_order_archive` WHERE `id` = :p0';
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
            $cls = POrderArchivePeer::getOMClass();
            $obj = new $cls;
            $obj->hydrate($row);
            POrderArchivePeer::addInstanceToPool($obj, (string) $key);
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
     * @return POrderArchive|POrderArchive[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|POrderArchive[]|mixed the list of results, formatted by the current formatter
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
     * @return POrderArchiveQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(POrderArchivePeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return POrderArchiveQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(POrderArchivePeer::ID, $keys, Criteria::IN);
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
     * @return POrderArchiveQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(POrderArchivePeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(POrderArchivePeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(POrderArchivePeer::ID, $id, $comparison);
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
     * @param     mixed $pUserId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return POrderArchiveQuery The current query, for fluid interface
     */
    public function filterByPUserId($pUserId = null, $comparison = null)
    {
        if (is_array($pUserId)) {
            $useMinMax = false;
            if (isset($pUserId['min'])) {
                $this->addUsingAlias(POrderArchivePeer::P_USER_ID, $pUserId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($pUserId['max'])) {
                $this->addUsingAlias(POrderArchivePeer::P_USER_ID, $pUserId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(POrderArchivePeer::P_USER_ID, $pUserId, $comparison);
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
     * @param     mixed $pOOrderStateId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return POrderArchiveQuery The current query, for fluid interface
     */
    public function filterByPOOrderStateId($pOOrderStateId = null, $comparison = null)
    {
        if (is_array($pOOrderStateId)) {
            $useMinMax = false;
            if (isset($pOOrderStateId['min'])) {
                $this->addUsingAlias(POrderArchivePeer::P_O_ORDER_STATE_ID, $pOOrderStateId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($pOOrderStateId['max'])) {
                $this->addUsingAlias(POrderArchivePeer::P_O_ORDER_STATE_ID, $pOOrderStateId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(POrderArchivePeer::P_O_ORDER_STATE_ID, $pOOrderStateId, $comparison);
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
     * @param     mixed $pOPaymentStateId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return POrderArchiveQuery The current query, for fluid interface
     */
    public function filterByPOPaymentStateId($pOPaymentStateId = null, $comparison = null)
    {
        if (is_array($pOPaymentStateId)) {
            $useMinMax = false;
            if (isset($pOPaymentStateId['min'])) {
                $this->addUsingAlias(POrderArchivePeer::P_O_PAYMENT_STATE_ID, $pOPaymentStateId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($pOPaymentStateId['max'])) {
                $this->addUsingAlias(POrderArchivePeer::P_O_PAYMENT_STATE_ID, $pOPaymentStateId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(POrderArchivePeer::P_O_PAYMENT_STATE_ID, $pOPaymentStateId, $comparison);
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
     * @param     mixed $pOPaymentTypeId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return POrderArchiveQuery The current query, for fluid interface
     */
    public function filterByPOPaymentTypeId($pOPaymentTypeId = null, $comparison = null)
    {
        if (is_array($pOPaymentTypeId)) {
            $useMinMax = false;
            if (isset($pOPaymentTypeId['min'])) {
                $this->addUsingAlias(POrderArchivePeer::P_O_PAYMENT_TYPE_ID, $pOPaymentTypeId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($pOPaymentTypeId['max'])) {
                $this->addUsingAlias(POrderArchivePeer::P_O_PAYMENT_TYPE_ID, $pOPaymentTypeId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(POrderArchivePeer::P_O_PAYMENT_TYPE_ID, $pOPaymentTypeId, $comparison);
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
     * @param     mixed $pOSubscriptionId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return POrderArchiveQuery The current query, for fluid interface
     */
    public function filterByPOSubscriptionId($pOSubscriptionId = null, $comparison = null)
    {
        if (is_array($pOSubscriptionId)) {
            $useMinMax = false;
            if (isset($pOSubscriptionId['min'])) {
                $this->addUsingAlias(POrderArchivePeer::P_O_SUBSCRIPTION_ID, $pOSubscriptionId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($pOSubscriptionId['max'])) {
                $this->addUsingAlias(POrderArchivePeer::P_O_SUBSCRIPTION_ID, $pOSubscriptionId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(POrderArchivePeer::P_O_SUBSCRIPTION_ID, $pOSubscriptionId, $comparison);
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
     * @return POrderArchiveQuery The current query, for fluid interface
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

        return $this->addUsingAlias(POrderArchivePeer::SUBSCRIPTION_TITLE, $subscriptionTitle, $comparison);
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
     * @return POrderArchiveQuery The current query, for fluid interface
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

        return $this->addUsingAlias(POrderArchivePeer::SUBSCRIPTION_DESCRIPTION, $subscriptionDescription, $comparison);
    }

    /**
     * Filter the query on the subscription_begin_at column
     *
     * Example usage:
     * <code>
     * $query->filterBySubscriptionBeginAt('2011-03-14'); // WHERE subscription_begin_at = '2011-03-14'
     * $query->filterBySubscriptionBeginAt('now'); // WHERE subscription_begin_at = '2011-03-14'
     * $query->filterBySubscriptionBeginAt(array('max' => 'yesterday')); // WHERE subscription_begin_at < '2011-03-13'
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
     * @return POrderArchiveQuery The current query, for fluid interface
     */
    public function filterBySubscriptionBeginAt($subscriptionBeginAt = null, $comparison = null)
    {
        if (is_array($subscriptionBeginAt)) {
            $useMinMax = false;
            if (isset($subscriptionBeginAt['min'])) {
                $this->addUsingAlias(POrderArchivePeer::SUBSCRIPTION_BEGIN_AT, $subscriptionBeginAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($subscriptionBeginAt['max'])) {
                $this->addUsingAlias(POrderArchivePeer::SUBSCRIPTION_BEGIN_AT, $subscriptionBeginAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(POrderArchivePeer::SUBSCRIPTION_BEGIN_AT, $subscriptionBeginAt, $comparison);
    }

    /**
     * Filter the query on the subscription_end_at column
     *
     * Example usage:
     * <code>
     * $query->filterBySubscriptionEndAt('2011-03-14'); // WHERE subscription_end_at = '2011-03-14'
     * $query->filterBySubscriptionEndAt('now'); // WHERE subscription_end_at = '2011-03-14'
     * $query->filterBySubscriptionEndAt(array('max' => 'yesterday')); // WHERE subscription_end_at < '2011-03-13'
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
     * @return POrderArchiveQuery The current query, for fluid interface
     */
    public function filterBySubscriptionEndAt($subscriptionEndAt = null, $comparison = null)
    {
        if (is_array($subscriptionEndAt)) {
            $useMinMax = false;
            if (isset($subscriptionEndAt['min'])) {
                $this->addUsingAlias(POrderArchivePeer::SUBSCRIPTION_END_AT, $subscriptionEndAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($subscriptionEndAt['max'])) {
                $this->addUsingAlias(POrderArchivePeer::SUBSCRIPTION_END_AT, $subscriptionEndAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(POrderArchivePeer::SUBSCRIPTION_END_AT, $subscriptionEndAt, $comparison);
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
     * @return POrderArchiveQuery The current query, for fluid interface
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

        return $this->addUsingAlias(POrderArchivePeer::INFORMATION, $information, $comparison);
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
     * @return POrderArchiveQuery The current query, for fluid interface
     */
    public function filterByPrice($price = null, $comparison = null)
    {
        if (is_array($price)) {
            $useMinMax = false;
            if (isset($price['min'])) {
                $this->addUsingAlias(POrderArchivePeer::PRICE, $price['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($price['max'])) {
                $this->addUsingAlias(POrderArchivePeer::PRICE, $price['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(POrderArchivePeer::PRICE, $price, $comparison);
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
     * @return POrderArchiveQuery The current query, for fluid interface
     */
    public function filterByPromotion($promotion = null, $comparison = null)
    {
        if (is_array($promotion)) {
            $useMinMax = false;
            if (isset($promotion['min'])) {
                $this->addUsingAlias(POrderArchivePeer::PROMOTION, $promotion['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($promotion['max'])) {
                $this->addUsingAlias(POrderArchivePeer::PROMOTION, $promotion['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(POrderArchivePeer::PROMOTION, $promotion, $comparison);
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
     * @return POrderArchiveQuery The current query, for fluid interface
     */
    public function filterByTotal($total = null, $comparison = null)
    {
        if (is_array($total)) {
            $useMinMax = false;
            if (isset($total['min'])) {
                $this->addUsingAlias(POrderArchivePeer::TOTAL, $total['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($total['max'])) {
                $this->addUsingAlias(POrderArchivePeer::TOTAL, $total['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(POrderArchivePeer::TOTAL, $total, $comparison);
    }

    /**
     * Filter the query on the gender column
     *
     * @param     mixed $gender The value to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return POrderArchiveQuery The current query, for fluid interface
     * @throws PropelException - if the value is not accepted by the enum.
     */
    public function filterByGender($gender = null, $comparison = null)
    {
        if (is_scalar($gender)) {
            $gender = POrderArchivePeer::getSqlValueForEnum(POrderArchivePeer::GENDER, $gender);
        } elseif (is_array($gender)) {
            $convertedValues = array();
            foreach ($gender as $value) {
                $convertedValues[] = POrderArchivePeer::getSqlValueForEnum(POrderArchivePeer::GENDER, $value);
            }
            $gender = $convertedValues;
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(POrderArchivePeer::GENDER, $gender, $comparison);
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
     * @return POrderArchiveQuery The current query, for fluid interface
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

        return $this->addUsingAlias(POrderArchivePeer::NAME, $name, $comparison);
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
     * @return POrderArchiveQuery The current query, for fluid interface
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

        return $this->addUsingAlias(POrderArchivePeer::FIRSTNAME, $firstname, $comparison);
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
     * @return POrderArchiveQuery The current query, for fluid interface
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

        return $this->addUsingAlias(POrderArchivePeer::PHONE, $phone, $comparison);
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
     * @return POrderArchiveQuery The current query, for fluid interface
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

        return $this->addUsingAlias(POrderArchivePeer::EMAIL, $email, $comparison);
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
     * @return POrderArchiveQuery The current query, for fluid interface
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

        return $this->addUsingAlias(POrderArchivePeer::INVOICE_REF, $invoiceRef, $comparison);
    }

    /**
     * Filter the query on the invoice_at column
     *
     * Example usage:
     * <code>
     * $query->filterByInvoiceAt('2011-03-14'); // WHERE invoice_at = '2011-03-14'
     * $query->filterByInvoiceAt('now'); // WHERE invoice_at = '2011-03-14'
     * $query->filterByInvoiceAt(array('max' => 'yesterday')); // WHERE invoice_at < '2011-03-13'
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
     * @return POrderArchiveQuery The current query, for fluid interface
     */
    public function filterByInvoiceAt($invoiceAt = null, $comparison = null)
    {
        if (is_array($invoiceAt)) {
            $useMinMax = false;
            if (isset($invoiceAt['min'])) {
                $this->addUsingAlias(POrderArchivePeer::INVOICE_AT, $invoiceAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($invoiceAt['max'])) {
                $this->addUsingAlias(POrderArchivePeer::INVOICE_AT, $invoiceAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(POrderArchivePeer::INVOICE_AT, $invoiceAt, $comparison);
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
     * @return POrderArchiveQuery The current query, for fluid interface
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

        return $this->addUsingAlias(POrderArchivePeer::INVOICE_FILENAME, $invoiceFilename, $comparison);
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
     * @return POrderArchiveQuery The current query, for fluid interface
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

        return $this->addUsingAlias(POrderArchivePeer::SUPPORTING_DOCUMENT, $supportingDocument, $comparison);
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
     * @return POrderArchiveQuery The current query, for fluid interface
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

        return $this->addUsingAlias(POrderArchivePeer::ELECTIVE_MANDATES, $electiveMandates, $comparison);
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
     * @return POrderArchiveQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(POrderArchivePeer::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(POrderArchivePeer::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(POrderArchivePeer::CREATED_AT, $createdAt, $comparison);
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
     * @return POrderArchiveQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(POrderArchivePeer::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(POrderArchivePeer::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(POrderArchivePeer::UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query on the archived_at column
     *
     * Example usage:
     * <code>
     * $query->filterByArchivedAt('2011-03-14'); // WHERE archived_at = '2011-03-14'
     * $query->filterByArchivedAt('now'); // WHERE archived_at = '2011-03-14'
     * $query->filterByArchivedAt(array('max' => 'yesterday')); // WHERE archived_at < '2011-03-13'
     * </code>
     *
     * @param     mixed $archivedAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return POrderArchiveQuery The current query, for fluid interface
     */
    public function filterByArchivedAt($archivedAt = null, $comparison = null)
    {
        if (is_array($archivedAt)) {
            $useMinMax = false;
            if (isset($archivedAt['min'])) {
                $this->addUsingAlias(POrderArchivePeer::ARCHIVED_AT, $archivedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($archivedAt['max'])) {
                $this->addUsingAlias(POrderArchivePeer::ARCHIVED_AT, $archivedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(POrderArchivePeer::ARCHIVED_AT, $archivedAt, $comparison);
    }

    /**
     * Exclude object from result
     *
     * @param   POrderArchive $pOrderArchive Object to remove from the list of results
     *
     * @return POrderArchiveQuery The current query, for fluid interface
     */
    public function prune($pOrderArchive = null)
    {
        if ($pOrderArchive) {
            $this->addUsingAlias(POrderArchivePeer::ID, $pOrderArchive->getId(), Criteria::NOT_EQUAL);
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

    // extend behavior
    public function setFormatter($formatter)
    {
        if (is_string($formatter) && $formatter === \ModelCriteria::FORMAT_ON_DEMAND) {
            $formatter = '\Glorpen\Propel\PropelBundle\Formatter\PropelOnDemandFormatter';
        }

        return parent::setFormatter($formatter);
    }
}
