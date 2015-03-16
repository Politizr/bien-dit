<?php

namespace Politizr\Model\om;

use \BaseObject;
use \BasePeer;
use \Criteria;
use \DateTime;
use \Exception;
use \PDO;
use \Persistent;
use \Propel;
use \PropelDateTime;
use \PropelException;
use \PropelPDO;
use Glorpen\Propel\PropelBundle\Dispatcher\EventDispatcherProxy;
use Glorpen\Propel\PropelBundle\Events\ModelEvent;
use Politizr\Model\POrderArchive;
use Politizr\Model\POrderArchivePeer;
use Politizr\Model\POrderArchiveQuery;

abstract class BasePOrderArchive extends BaseObject implements Persistent
{
    /**
     * Peer class name
     */
    const PEER = 'Politizr\\Model\\POrderArchivePeer';

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        POrderArchivePeer
     */
    protected static $peer;

    /**
     * The flag var to prevent infinite loop in deep copy
     * @var       boolean
     */
    protected $startCopy = false;

    /**
     * The value for the id field.
     * @var        int
     */
    protected $id;

    /**
     * The value for the p_user_id field.
     * @var        int
     */
    protected $p_user_id;

    /**
     * The value for the p_o_order_state_id field.
     * @var        int
     */
    protected $p_o_order_state_id;

    /**
     * The value for the p_o_payment_state_id field.
     * @var        int
     */
    protected $p_o_payment_state_id;

    /**
     * The value for the p_o_payment_type_id field.
     * @var        int
     */
    protected $p_o_payment_type_id;

    /**
     * The value for the p_o_subscription_id field.
     * @var        int
     */
    protected $p_o_subscription_id;

    /**
     * The value for the subscription_title field.
     * @var        string
     */
    protected $subscription_title;

    /**
     * The value for the subscription_description field.
     * @var        string
     */
    protected $subscription_description;

    /**
     * The value for the subscription_begin_at field.
     * @var        string
     */
    protected $subscription_begin_at;

    /**
     * The value for the subscription_end_at field.
     * @var        string
     */
    protected $subscription_end_at;

    /**
     * The value for the information field.
     * @var        string
     */
    protected $information;

    /**
     * The value for the price field.
     * @var        string
     */
    protected $price;

    /**
     * The value for the promotion field.
     * @var        string
     */
    protected $promotion;

    /**
     * The value for the total field.
     * @var        string
     */
    protected $total;

    /**
     * The value for the gender field.
     * @var        int
     */
    protected $gender;

    /**
     * The value for the name field.
     * @var        string
     */
    protected $name;

    /**
     * The value for the firstname field.
     * @var        string
     */
    protected $firstname;

    /**
     * The value for the phone field.
     * @var        string
     */
    protected $phone;

    /**
     * The value for the email field.
     * @var        string
     */
    protected $email;

    /**
     * The value for the invoice_ref field.
     * @var        string
     */
    protected $invoice_ref;

    /**
     * The value for the invoice_at field.
     * @var        string
     */
    protected $invoice_at;

    /**
     * The value for the invoice_filename field.
     * @var        string
     */
    protected $invoice_filename;

    /**
     * The value for the supporting_document field.
     * @var        string
     */
    protected $supporting_document;

    /**
     * The value for the elective_mandates field.
     * @var        string
     */
    protected $elective_mandates;

    /**
     * The value for the created_at field.
     * @var        string
     */
    protected $created_at;

    /**
     * The value for the updated_at field.
     * @var        string
     */
    protected $updated_at;

    /**
     * The value for the archived_at field.
     * @var        string
     */
    protected $archived_at;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     * @var        boolean
     */
    protected $alreadyInSave = false;

    /**
     * Flag to prevent endless validation loop, if this object is referenced
     * by another object which falls in this transaction.
     * @var        boolean
     */
    protected $alreadyInValidation = false;

    /**
     * Flag to prevent endless clearAllReferences($deep=true) loop, if this object is referenced
     * @var        boolean
     */
    protected $alreadyInClearAllReferencesDeep = false;

    /**
     * Get the [id] column value.
     *
     * @return int
     */
    public function getId()
    {

        return $this->id;
    }

    public function __construct(){
        parent::__construct();
        EventDispatcherProxy::trigger(array('construct','model.construct'), new ModelEvent($this));
    }

    /**
     * Get the [p_user_id] column value.
     *
     * @return int
     */
    public function getPUserId()
    {

        return $this->p_user_id;
    }

    /**
     * Get the [p_o_order_state_id] column value.
     *
     * @return int
     */
    public function getPOOrderStateId()
    {

        return $this->p_o_order_state_id;
    }

    /**
     * Get the [p_o_payment_state_id] column value.
     *
     * @return int
     */
    public function getPOPaymentStateId()
    {

        return $this->p_o_payment_state_id;
    }

    /**
     * Get the [p_o_payment_type_id] column value.
     *
     * @return int
     */
    public function getPOPaymentTypeId()
    {

        return $this->p_o_payment_type_id;
    }

    /**
     * Get the [p_o_subscription_id] column value.
     *
     * @return int
     */
    public function getPOSubscriptionId()
    {

        return $this->p_o_subscription_id;
    }

    /**
     * Get the [subscription_title] column value.
     *
     * @return string
     */
    public function getSubscriptionTitle()
    {

        return $this->subscription_title;
    }

    /**
     * Get the [subscription_description] column value.
     *
     * @return string
     */
    public function getSubscriptionDescription()
    {

        return $this->subscription_description;
    }

    /**
     * Get the [optionally formatted] temporal [subscription_begin_at] column value.
     *
     *
     * @param string $format The date/time format string (either date()-style or strftime()-style).
     *                 If format is null, then the raw DateTime object will be returned.
     * @return mixed Formatted date/time value as string or DateTime object (if format is null), null if column is null, and 0 if column value is 0000-00-00
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getSubscriptionBeginAt($format = null)
    {
        if ($this->subscription_begin_at === null) {
            return null;
        }

        if ($this->subscription_begin_at === '0000-00-00') {
            // while technically this is not a default value of null,
            // this seems to be closest in meaning.
            return null;
        }

        try {
            $dt = new DateTime($this->subscription_begin_at);
        } catch (Exception $x) {
            throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->subscription_begin_at, true), $x);
        }

        if ($format === null) {
            // Because propel.useDateTimeClass is true, we return a DateTime object.
            return $dt;
        }

        if (strpos($format, '%') !== false) {
            return strftime($format, $dt->format('U'));
        }

        return $dt->format($format);

    }

    /**
     * Get the [optionally formatted] temporal [subscription_end_at] column value.
     *
     *
     * @param string $format The date/time format string (either date()-style or strftime()-style).
     *                 If format is null, then the raw DateTime object will be returned.
     * @return mixed Formatted date/time value as string or DateTime object (if format is null), null if column is null, and 0 if column value is 0000-00-00
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getSubscriptionEndAt($format = null)
    {
        if ($this->subscription_end_at === null) {
            return null;
        }

        if ($this->subscription_end_at === '0000-00-00') {
            // while technically this is not a default value of null,
            // this seems to be closest in meaning.
            return null;
        }

        try {
            $dt = new DateTime($this->subscription_end_at);
        } catch (Exception $x) {
            throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->subscription_end_at, true), $x);
        }

        if ($format === null) {
            // Because propel.useDateTimeClass is true, we return a DateTime object.
            return $dt;
        }

        if (strpos($format, '%') !== false) {
            return strftime($format, $dt->format('U'));
        }

        return $dt->format($format);

    }

    /**
     * Get the [information] column value.
     *
     * @return string
     */
    public function getInformation()
    {

        return $this->information;
    }

    /**
     * Get the [price] column value.
     *
     * @return string
     */
    public function getPrice()
    {

        return $this->price;
    }

    /**
     * Get the [promotion] column value.
     *
     * @return string
     */
    public function getPromotion()
    {

        return $this->promotion;
    }

    /**
     * Get the [total] column value.
     *
     * @return string
     */
    public function getTotal()
    {

        return $this->total;
    }

    /**
     * Get the [gender] column value.
     *
     * @return int
     * @throws PropelException - if the stored enum key is unknown.
     */
    public function getGender()
    {
        if (null === $this->gender) {
            return null;
        }
        $valueSet = POrderArchivePeer::getValueSet(POrderArchivePeer::GENDER);
        if (!isset($valueSet[$this->gender])) {
            throw new PropelException('Unknown stored enum key: ' . $this->gender);
        }

        return $valueSet[$this->gender];
    }

    /**
     * Get the [name] column value.
     *
     * @return string
     */
    public function getName()
    {

        return $this->name;
    }

    /**
     * Get the [firstname] column value.
     *
     * @return string
     */
    public function getFirstname()
    {

        return $this->firstname;
    }

    /**
     * Get the [phone] column value.
     *
     * @return string
     */
    public function getPhone()
    {

        return $this->phone;
    }

    /**
     * Get the [email] column value.
     *
     * @return string
     */
    public function getEmail()
    {

        return $this->email;
    }

    /**
     * Get the [invoice_ref] column value.
     *
     * @return string
     */
    public function getInvoiceRef()
    {

        return $this->invoice_ref;
    }

    /**
     * Get the [optionally formatted] temporal [invoice_at] column value.
     *
     *
     * @param string $format The date/time format string (either date()-style or strftime()-style).
     *                 If format is null, then the raw DateTime object will be returned.
     * @return mixed Formatted date/time value as string or DateTime object (if format is null), null if column is null, and 0 if column value is 0000-00-00 00:00:00
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getInvoiceAt($format = null)
    {
        if ($this->invoice_at === null) {
            return null;
        }

        if ($this->invoice_at === '0000-00-00 00:00:00') {
            // while technically this is not a default value of null,
            // this seems to be closest in meaning.
            return null;
        }

        try {
            $dt = new DateTime($this->invoice_at);
        } catch (Exception $x) {
            throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->invoice_at, true), $x);
        }

        if ($format === null) {
            // Because propel.useDateTimeClass is true, we return a DateTime object.
            return $dt;
        }

        if (strpos($format, '%') !== false) {
            return strftime($format, $dt->format('U'));
        }

        return $dt->format($format);

    }

    /**
     * Get the [invoice_filename] column value.
     *
     * @return string
     */
    public function getInvoiceFilename()
    {

        return $this->invoice_filename;
    }

    /**
     * Get the [supporting_document] column value.
     *
     * @return string
     */
    public function getSupportingDocument()
    {

        return $this->supporting_document;
    }

    /**
     * Get the [elective_mandates] column value.
     *
     * @return string
     */
    public function getElectiveMandates()
    {

        return $this->elective_mandates;
    }

    /**
     * Get the [optionally formatted] temporal [created_at] column value.
     *
     *
     * @param string $format The date/time format string (either date()-style or strftime()-style).
     *                 If format is null, then the raw DateTime object will be returned.
     * @return mixed Formatted date/time value as string or DateTime object (if format is null), null if column is null, and 0 if column value is 0000-00-00 00:00:00
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getCreatedAt($format = null)
    {
        if ($this->created_at === null) {
            return null;
        }

        if ($this->created_at === '0000-00-00 00:00:00') {
            // while technically this is not a default value of null,
            // this seems to be closest in meaning.
            return null;
        }

        try {
            $dt = new DateTime($this->created_at);
        } catch (Exception $x) {
            throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->created_at, true), $x);
        }

        if ($format === null) {
            // Because propel.useDateTimeClass is true, we return a DateTime object.
            return $dt;
        }

        if (strpos($format, '%') !== false) {
            return strftime($format, $dt->format('U'));
        }

        return $dt->format($format);

    }

    /**
     * Get the [optionally formatted] temporal [updated_at] column value.
     *
     *
     * @param string $format The date/time format string (either date()-style or strftime()-style).
     *                 If format is null, then the raw DateTime object will be returned.
     * @return mixed Formatted date/time value as string or DateTime object (if format is null), null if column is null, and 0 if column value is 0000-00-00 00:00:00
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getUpdatedAt($format = null)
    {
        if ($this->updated_at === null) {
            return null;
        }

        if ($this->updated_at === '0000-00-00 00:00:00') {
            // while technically this is not a default value of null,
            // this seems to be closest in meaning.
            return null;
        }

        try {
            $dt = new DateTime($this->updated_at);
        } catch (Exception $x) {
            throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->updated_at, true), $x);
        }

        if ($format === null) {
            // Because propel.useDateTimeClass is true, we return a DateTime object.
            return $dt;
        }

        if (strpos($format, '%') !== false) {
            return strftime($format, $dt->format('U'));
        }

        return $dt->format($format);

    }

    /**
     * Get the [optionally formatted] temporal [archived_at] column value.
     *
     *
     * @param string $format The date/time format string (either date()-style or strftime()-style).
     *                 If format is null, then the raw DateTime object will be returned.
     * @return mixed Formatted date/time value as string or DateTime object (if format is null), null if column is null, and 0 if column value is 0000-00-00 00:00:00
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getArchivedAt($format = null)
    {
        if ($this->archived_at === null) {
            return null;
        }

        if ($this->archived_at === '0000-00-00 00:00:00') {
            // while technically this is not a default value of null,
            // this seems to be closest in meaning.
            return null;
        }

        try {
            $dt = new DateTime($this->archived_at);
        } catch (Exception $x) {
            throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->archived_at, true), $x);
        }

        if ($format === null) {
            // Because propel.useDateTimeClass is true, we return a DateTime object.
            return $dt;
        }

        if (strpos($format, '%') !== false) {
            return strftime($format, $dt->format('U'));
        }

        return $dt->format($format);

    }

    /**
     * Set the value of [id] column.
     *
     * @param  int $v new value
     * @return POrderArchive The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[] = POrderArchivePeer::ID;
        }


        return $this;
    } // setId()

    /**
     * Set the value of [p_user_id] column.
     *
     * @param  int $v new value
     * @return POrderArchive The current object (for fluent API support)
     */
    public function setPUserId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->p_user_id !== $v) {
            $this->p_user_id = $v;
            $this->modifiedColumns[] = POrderArchivePeer::P_USER_ID;
        }


        return $this;
    } // setPUserId()

    /**
     * Set the value of [p_o_order_state_id] column.
     *
     * @param  int $v new value
     * @return POrderArchive The current object (for fluent API support)
     */
    public function setPOOrderStateId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->p_o_order_state_id !== $v) {
            $this->p_o_order_state_id = $v;
            $this->modifiedColumns[] = POrderArchivePeer::P_O_ORDER_STATE_ID;
        }


        return $this;
    } // setPOOrderStateId()

    /**
     * Set the value of [p_o_payment_state_id] column.
     *
     * @param  int $v new value
     * @return POrderArchive The current object (for fluent API support)
     */
    public function setPOPaymentStateId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->p_o_payment_state_id !== $v) {
            $this->p_o_payment_state_id = $v;
            $this->modifiedColumns[] = POrderArchivePeer::P_O_PAYMENT_STATE_ID;
        }


        return $this;
    } // setPOPaymentStateId()

    /**
     * Set the value of [p_o_payment_type_id] column.
     *
     * @param  int $v new value
     * @return POrderArchive The current object (for fluent API support)
     */
    public function setPOPaymentTypeId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->p_o_payment_type_id !== $v) {
            $this->p_o_payment_type_id = $v;
            $this->modifiedColumns[] = POrderArchivePeer::P_O_PAYMENT_TYPE_ID;
        }


        return $this;
    } // setPOPaymentTypeId()

    /**
     * Set the value of [p_o_subscription_id] column.
     *
     * @param  int $v new value
     * @return POrderArchive The current object (for fluent API support)
     */
    public function setPOSubscriptionId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->p_o_subscription_id !== $v) {
            $this->p_o_subscription_id = $v;
            $this->modifiedColumns[] = POrderArchivePeer::P_O_SUBSCRIPTION_ID;
        }


        return $this;
    } // setPOSubscriptionId()

    /**
     * Set the value of [subscription_title] column.
     *
     * @param  string $v new value
     * @return POrderArchive The current object (for fluent API support)
     */
    public function setSubscriptionTitle($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->subscription_title !== $v) {
            $this->subscription_title = $v;
            $this->modifiedColumns[] = POrderArchivePeer::SUBSCRIPTION_TITLE;
        }


        return $this;
    } // setSubscriptionTitle()

    /**
     * Set the value of [subscription_description] column.
     *
     * @param  string $v new value
     * @return POrderArchive The current object (for fluent API support)
     */
    public function setSubscriptionDescription($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->subscription_description !== $v) {
            $this->subscription_description = $v;
            $this->modifiedColumns[] = POrderArchivePeer::SUBSCRIPTION_DESCRIPTION;
        }


        return $this;
    } // setSubscriptionDescription()

    /**
     * Sets the value of [subscription_begin_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return POrderArchive The current object (for fluent API support)
     */
    public function setSubscriptionBeginAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->subscription_begin_at !== null || $dt !== null) {
            $currentDateAsString = ($this->subscription_begin_at !== null && $tmpDt = new DateTime($this->subscription_begin_at)) ? $tmpDt->format('Y-m-d') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->subscription_begin_at = $newDateAsString;
                $this->modifiedColumns[] = POrderArchivePeer::SUBSCRIPTION_BEGIN_AT;
            }
        } // if either are not null


        return $this;
    } // setSubscriptionBeginAt()

    /**
     * Sets the value of [subscription_end_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return POrderArchive The current object (for fluent API support)
     */
    public function setSubscriptionEndAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->subscription_end_at !== null || $dt !== null) {
            $currentDateAsString = ($this->subscription_end_at !== null && $tmpDt = new DateTime($this->subscription_end_at)) ? $tmpDt->format('Y-m-d') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->subscription_end_at = $newDateAsString;
                $this->modifiedColumns[] = POrderArchivePeer::SUBSCRIPTION_END_AT;
            }
        } // if either are not null


        return $this;
    } // setSubscriptionEndAt()

    /**
     * Set the value of [information] column.
     *
     * @param  string $v new value
     * @return POrderArchive The current object (for fluent API support)
     */
    public function setInformation($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->information !== $v) {
            $this->information = $v;
            $this->modifiedColumns[] = POrderArchivePeer::INFORMATION;
        }


        return $this;
    } // setInformation()

    /**
     * Set the value of [price] column.
     *
     * @param  string $v new value
     * @return POrderArchive The current object (for fluent API support)
     */
    public function setPrice($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (string) $v;
        }

        if ($this->price !== $v) {
            $this->price = $v;
            $this->modifiedColumns[] = POrderArchivePeer::PRICE;
        }


        return $this;
    } // setPrice()

    /**
     * Set the value of [promotion] column.
     *
     * @param  string $v new value
     * @return POrderArchive The current object (for fluent API support)
     */
    public function setPromotion($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (string) $v;
        }

        if ($this->promotion !== $v) {
            $this->promotion = $v;
            $this->modifiedColumns[] = POrderArchivePeer::PROMOTION;
        }


        return $this;
    } // setPromotion()

    /**
     * Set the value of [total] column.
     *
     * @param  string $v new value
     * @return POrderArchive The current object (for fluent API support)
     */
    public function setTotal($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (string) $v;
        }

        if ($this->total !== $v) {
            $this->total = $v;
            $this->modifiedColumns[] = POrderArchivePeer::TOTAL;
        }


        return $this;
    } // setTotal()

    /**
     * Set the value of [gender] column.
     *
     * @param  int $v new value
     * @return POrderArchive The current object (for fluent API support)
     * @throws PropelException - if the value is not accepted by this enum.
     */
    public function setGender($v)
    {
        if ($v !== null) {
            $valueSet = POrderArchivePeer::getValueSet(POrderArchivePeer::GENDER);
            if (!in_array($v, $valueSet)) {
                throw new PropelException(sprintf('Value "%s" is not accepted in this enumerated column', $v));
            }
            $v = array_search($v, $valueSet);
        }

        if ($this->gender !== $v) {
            $this->gender = $v;
            $this->modifiedColumns[] = POrderArchivePeer::GENDER;
        }


        return $this;
    } // setGender()

    /**
     * Set the value of [name] column.
     *
     * @param  string $v new value
     * @return POrderArchive The current object (for fluent API support)
     */
    public function setName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->name !== $v) {
            $this->name = $v;
            $this->modifiedColumns[] = POrderArchivePeer::NAME;
        }


        return $this;
    } // setName()

    /**
     * Set the value of [firstname] column.
     *
     * @param  string $v new value
     * @return POrderArchive The current object (for fluent API support)
     */
    public function setFirstname($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->firstname !== $v) {
            $this->firstname = $v;
            $this->modifiedColumns[] = POrderArchivePeer::FIRSTNAME;
        }


        return $this;
    } // setFirstname()

    /**
     * Set the value of [phone] column.
     *
     * @param  string $v new value
     * @return POrderArchive The current object (for fluent API support)
     */
    public function setPhone($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->phone !== $v) {
            $this->phone = $v;
            $this->modifiedColumns[] = POrderArchivePeer::PHONE;
        }


        return $this;
    } // setPhone()

    /**
     * Set the value of [email] column.
     *
     * @param  string $v new value
     * @return POrderArchive The current object (for fluent API support)
     */
    public function setEmail($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->email !== $v) {
            $this->email = $v;
            $this->modifiedColumns[] = POrderArchivePeer::EMAIL;
        }


        return $this;
    } // setEmail()

    /**
     * Set the value of [invoice_ref] column.
     *
     * @param  string $v new value
     * @return POrderArchive The current object (for fluent API support)
     */
    public function setInvoiceRef($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->invoice_ref !== $v) {
            $this->invoice_ref = $v;
            $this->modifiedColumns[] = POrderArchivePeer::INVOICE_REF;
        }


        return $this;
    } // setInvoiceRef()

    /**
     * Sets the value of [invoice_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return POrderArchive The current object (for fluent API support)
     */
    public function setInvoiceAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->invoice_at !== null || $dt !== null) {
            $currentDateAsString = ($this->invoice_at !== null && $tmpDt = new DateTime($this->invoice_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->invoice_at = $newDateAsString;
                $this->modifiedColumns[] = POrderArchivePeer::INVOICE_AT;
            }
        } // if either are not null


        return $this;
    } // setInvoiceAt()

    /**
     * Set the value of [invoice_filename] column.
     *
     * @param  string $v new value
     * @return POrderArchive The current object (for fluent API support)
     */
    public function setInvoiceFilename($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->invoice_filename !== $v) {
            $this->invoice_filename = $v;
            $this->modifiedColumns[] = POrderArchivePeer::INVOICE_FILENAME;
        }


        return $this;
    } // setInvoiceFilename()

    /**
     * Set the value of [supporting_document] column.
     *
     * @param  string $v new value
     * @return POrderArchive The current object (for fluent API support)
     */
    public function setSupportingDocument($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->supporting_document !== $v) {
            $this->supporting_document = $v;
            $this->modifiedColumns[] = POrderArchivePeer::SUPPORTING_DOCUMENT;
        }


        return $this;
    } // setSupportingDocument()

    /**
     * Set the value of [elective_mandates] column.
     *
     * @param  string $v new value
     * @return POrderArchive The current object (for fluent API support)
     */
    public function setElectiveMandates($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->elective_mandates !== $v) {
            $this->elective_mandates = $v;
            $this->modifiedColumns[] = POrderArchivePeer::ELECTIVE_MANDATES;
        }


        return $this;
    } // setElectiveMandates()

    /**
     * Sets the value of [created_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return POrderArchive The current object (for fluent API support)
     */
    public function setCreatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->created_at !== null || $dt !== null) {
            $currentDateAsString = ($this->created_at !== null && $tmpDt = new DateTime($this->created_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->created_at = $newDateAsString;
                $this->modifiedColumns[] = POrderArchivePeer::CREATED_AT;
            }
        } // if either are not null


        return $this;
    } // setCreatedAt()

    /**
     * Sets the value of [updated_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return POrderArchive The current object (for fluent API support)
     */
    public function setUpdatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->updated_at !== null || $dt !== null) {
            $currentDateAsString = ($this->updated_at !== null && $tmpDt = new DateTime($this->updated_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->updated_at = $newDateAsString;
                $this->modifiedColumns[] = POrderArchivePeer::UPDATED_AT;
            }
        } // if either are not null


        return $this;
    } // setUpdatedAt()

    /**
     * Sets the value of [archived_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return POrderArchive The current object (for fluent API support)
     */
    public function setArchivedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->archived_at !== null || $dt !== null) {
            $currentDateAsString = ($this->archived_at !== null && $tmpDt = new DateTime($this->archived_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->archived_at = $newDateAsString;
                $this->modifiedColumns[] = POrderArchivePeer::ARCHIVED_AT;
            }
        } // if either are not null


        return $this;
    } // setArchivedAt()

    /**
     * Indicates whether the columns in this object are only set to default values.
     *
     * This method can be used in conjunction with isModified() to indicate whether an object is both
     * modified _and_ has some values set which are non-default.
     *
     * @return boolean Whether the columns in this object are only been set with default values.
     */
    public function hasOnlyDefaultValues()
    {
        // otherwise, everything was equal, so return true
        return true;
    } // hasOnlyDefaultValues()

    /**
     * Hydrates (populates) the object variables with values from the database resultset.
     *
     * An offset (0-based "start column") is specified so that objects can be hydrated
     * with a subset of the columns in the resultset rows.  This is needed, for example,
     * for results of JOIN queries where the resultset row includes columns from two or
     * more tables.
     *
     * @param array $row The row returned by PDOStatement->fetch(PDO::FETCH_NUM)
     * @param int $startcol 0-based offset column which indicates which resultset column to start with.
     * @param boolean $rehydrate Whether this object is being re-hydrated from the database.
     * @return int             next starting column
     * @throws PropelException - Any caught Exception will be rewrapped as a PropelException.
     */
    public function hydrate($row, $startcol = 0, $rehydrate = false)
    {
        try {

            $this->id = ($row[$startcol + 0] !== null) ? (int) $row[$startcol + 0] : null;
            $this->p_user_id = ($row[$startcol + 1] !== null) ? (int) $row[$startcol + 1] : null;
            $this->p_o_order_state_id = ($row[$startcol + 2] !== null) ? (int) $row[$startcol + 2] : null;
            $this->p_o_payment_state_id = ($row[$startcol + 3] !== null) ? (int) $row[$startcol + 3] : null;
            $this->p_o_payment_type_id = ($row[$startcol + 4] !== null) ? (int) $row[$startcol + 4] : null;
            $this->p_o_subscription_id = ($row[$startcol + 5] !== null) ? (int) $row[$startcol + 5] : null;
            $this->subscription_title = ($row[$startcol + 6] !== null) ? (string) $row[$startcol + 6] : null;
            $this->subscription_description = ($row[$startcol + 7] !== null) ? (string) $row[$startcol + 7] : null;
            $this->subscription_begin_at = ($row[$startcol + 8] !== null) ? (string) $row[$startcol + 8] : null;
            $this->subscription_end_at = ($row[$startcol + 9] !== null) ? (string) $row[$startcol + 9] : null;
            $this->information = ($row[$startcol + 10] !== null) ? (string) $row[$startcol + 10] : null;
            $this->price = ($row[$startcol + 11] !== null) ? (string) $row[$startcol + 11] : null;
            $this->promotion = ($row[$startcol + 12] !== null) ? (string) $row[$startcol + 12] : null;
            $this->total = ($row[$startcol + 13] !== null) ? (string) $row[$startcol + 13] : null;
            $this->gender = ($row[$startcol + 14] !== null) ? (int) $row[$startcol + 14] : null;
            $this->name = ($row[$startcol + 15] !== null) ? (string) $row[$startcol + 15] : null;
            $this->firstname = ($row[$startcol + 16] !== null) ? (string) $row[$startcol + 16] : null;
            $this->phone = ($row[$startcol + 17] !== null) ? (string) $row[$startcol + 17] : null;
            $this->email = ($row[$startcol + 18] !== null) ? (string) $row[$startcol + 18] : null;
            $this->invoice_ref = ($row[$startcol + 19] !== null) ? (string) $row[$startcol + 19] : null;
            $this->invoice_at = ($row[$startcol + 20] !== null) ? (string) $row[$startcol + 20] : null;
            $this->invoice_filename = ($row[$startcol + 21] !== null) ? (string) $row[$startcol + 21] : null;
            $this->supporting_document = ($row[$startcol + 22] !== null) ? (string) $row[$startcol + 22] : null;
            $this->elective_mandates = ($row[$startcol + 23] !== null) ? (string) $row[$startcol + 23] : null;
            $this->created_at = ($row[$startcol + 24] !== null) ? (string) $row[$startcol + 24] : null;
            $this->updated_at = ($row[$startcol + 25] !== null) ? (string) $row[$startcol + 25] : null;
            $this->archived_at = ($row[$startcol + 26] !== null) ? (string) $row[$startcol + 26] : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }
            $this->postHydrate($row, $startcol, $rehydrate);

            return $startcol + 27; // 27 = POrderArchivePeer::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating POrderArchive object", $e);
        }
    }

    /**
     * Checks and repairs the internal consistency of the object.
     *
     * This method is executed after an already-instantiated object is re-hydrated
     * from the database.  It exists to check any foreign keys to make sure that
     * the objects related to the current object are correct based on foreign key.
     *
     * You can override this method in the stub class, but you should always invoke
     * the base method from the overridden method (i.e. parent::ensureConsistency()),
     * in case your model changes.
     *
     * @throws PropelException
     */
    public function ensureConsistency()
    {

    } // ensureConsistency

    /**
     * Reloads this object from datastore based on primary key and (optionally) resets all associated objects.
     *
     * This will only work if the object has been saved and has a valid primary key set.
     *
     * @param boolean $deep (optional) Whether to also de-associated any related objects.
     * @param PropelPDO $con (optional) The PropelPDO connection to use.
     * @return void
     * @throws PropelException - if this object is deleted, unsaved or doesn't have pk match in db
     */
    public function reload($deep = false, PropelPDO $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("Cannot reload a deleted object.");
        }

        if ($this->isNew()) {
            throw new PropelException("Cannot reload an unsaved object.");
        }

        if ($con === null) {
            $con = Propel::getConnection(POrderArchivePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $stmt = POrderArchivePeer::doSelectStmt($this->buildPkeyCriteria(), $con);
        $row = $stmt->fetch(PDO::FETCH_NUM);
        $stmt->closeCursor();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true); // rehydrate

        if ($deep) {  // also de-associate any related objects?

        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param PropelPDO $con
     * @return void
     * @throws PropelException
     * @throws Exception
     * @see        BaseObject::setDeleted()
     * @see        BaseObject::isDeleted()
     */
    public function delete(PropelPDO $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getConnection(POrderArchivePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        try {
            EventDispatcherProxy::trigger(array('delete.pre','model.delete.pre'), new ModelEvent($this));
            $deleteQuery = POrderArchiveQuery::create()
                ->filterByPrimaryKey($this->getPrimaryKey());
            $ret = $this->preDelete($con);
            if ($ret) {
                $deleteQuery->delete($con);
                $this->postDelete($con);
                // event behavior
                EventDispatcherProxy::trigger(array('delete.post', 'model.delete.post'), new ModelEvent($this));
                $con->commit();
                $this->setDeleted(true);
            } else {
                $con->commit();
            }
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Persists this object to the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All modified related objects will also be persisted in the doSave()
     * method.  This method wraps all precipitate database operations in a
     * single transaction.
     *
     * @param PropelPDO $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @throws Exception
     * @see        doSave()
     */
    public function save(PropelPDO $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("You cannot save an object that has been deleted.");
        }

        if ($con === null) {
            $con = Propel::getConnection(POrderArchivePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        $isInsert = $this->isNew();
        try {
            $ret = $this->preSave($con);
            // event behavior
            EventDispatcherProxy::trigger('model.save.pre', new ModelEvent($this));
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
                // event behavior
                EventDispatcherProxy::trigger('model.insert.pre', new ModelEvent($this));
            } else {
                $ret = $ret && $this->preUpdate($con);
                // event behavior
                EventDispatcherProxy::trigger(array('update.pre', 'model.update.pre'), new ModelEvent($this));
            }
            if ($ret) {
                $affectedRows = $this->doSave($con);
                if ($isInsert) {
                    $this->postInsert($con);
                    // event behavior
                    EventDispatcherProxy::trigger('model.insert.post', new ModelEvent($this));
                } else {
                    $this->postUpdate($con);
                    // event behavior
                    EventDispatcherProxy::trigger(array('update.post', 'model.update.post'), new ModelEvent($this));
                }
                $this->postSave($con);
                // event behavior
                EventDispatcherProxy::trigger('model.save.post', new ModelEvent($this));
                POrderArchivePeer::addInstanceToPool($this);
            } else {
                $affectedRows = 0;
            }
            $con->commit();

            return $affectedRows;
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Performs the work of inserting or updating the row in the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All related objects are also updated in this method.
     *
     * @param PropelPDO $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @see        save()
     */
    protected function doSave(PropelPDO $con)
    {
        $affectedRows = 0; // initialize var to track total num of affected rows
        if (!$this->alreadyInSave) {
            $this->alreadyInSave = true;

            if ($this->isNew() || $this->isModified()) {
                // persist changes
                if ($this->isNew()) {
                    $this->doInsert($con);
                } else {
                    $this->doUpdate($con);
                }
                $affectedRows += 1;
                $this->resetModified();
            }

            $this->alreadyInSave = false;

        }

        return $affectedRows;
    } // doSave()

    /**
     * Insert the row in the database.
     *
     * @param PropelPDO $con
     *
     * @throws PropelException
     * @see        doSave()
     */
    protected function doInsert(PropelPDO $con)
    {
        $modifiedColumns = array();
        $index = 0;


         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(POrderArchivePeer::ID)) {
            $modifiedColumns[':p' . $index++]  = '`id`';
        }
        if ($this->isColumnModified(POrderArchivePeer::P_USER_ID)) {
            $modifiedColumns[':p' . $index++]  = '`p_user_id`';
        }
        if ($this->isColumnModified(POrderArchivePeer::P_O_ORDER_STATE_ID)) {
            $modifiedColumns[':p' . $index++]  = '`p_o_order_state_id`';
        }
        if ($this->isColumnModified(POrderArchivePeer::P_O_PAYMENT_STATE_ID)) {
            $modifiedColumns[':p' . $index++]  = '`p_o_payment_state_id`';
        }
        if ($this->isColumnModified(POrderArchivePeer::P_O_PAYMENT_TYPE_ID)) {
            $modifiedColumns[':p' . $index++]  = '`p_o_payment_type_id`';
        }
        if ($this->isColumnModified(POrderArchivePeer::P_O_SUBSCRIPTION_ID)) {
            $modifiedColumns[':p' . $index++]  = '`p_o_subscription_id`';
        }
        if ($this->isColumnModified(POrderArchivePeer::SUBSCRIPTION_TITLE)) {
            $modifiedColumns[':p' . $index++]  = '`subscription_title`';
        }
        if ($this->isColumnModified(POrderArchivePeer::SUBSCRIPTION_DESCRIPTION)) {
            $modifiedColumns[':p' . $index++]  = '`subscription_description`';
        }
        if ($this->isColumnModified(POrderArchivePeer::SUBSCRIPTION_BEGIN_AT)) {
            $modifiedColumns[':p' . $index++]  = '`subscription_begin_at`';
        }
        if ($this->isColumnModified(POrderArchivePeer::SUBSCRIPTION_END_AT)) {
            $modifiedColumns[':p' . $index++]  = '`subscription_end_at`';
        }
        if ($this->isColumnModified(POrderArchivePeer::INFORMATION)) {
            $modifiedColumns[':p' . $index++]  = '`information`';
        }
        if ($this->isColumnModified(POrderArchivePeer::PRICE)) {
            $modifiedColumns[':p' . $index++]  = '`price`';
        }
        if ($this->isColumnModified(POrderArchivePeer::PROMOTION)) {
            $modifiedColumns[':p' . $index++]  = '`promotion`';
        }
        if ($this->isColumnModified(POrderArchivePeer::TOTAL)) {
            $modifiedColumns[':p' . $index++]  = '`total`';
        }
        if ($this->isColumnModified(POrderArchivePeer::GENDER)) {
            $modifiedColumns[':p' . $index++]  = '`gender`';
        }
        if ($this->isColumnModified(POrderArchivePeer::NAME)) {
            $modifiedColumns[':p' . $index++]  = '`name`';
        }
        if ($this->isColumnModified(POrderArchivePeer::FIRSTNAME)) {
            $modifiedColumns[':p' . $index++]  = '`firstname`';
        }
        if ($this->isColumnModified(POrderArchivePeer::PHONE)) {
            $modifiedColumns[':p' . $index++]  = '`phone`';
        }
        if ($this->isColumnModified(POrderArchivePeer::EMAIL)) {
            $modifiedColumns[':p' . $index++]  = '`email`';
        }
        if ($this->isColumnModified(POrderArchivePeer::INVOICE_REF)) {
            $modifiedColumns[':p' . $index++]  = '`invoice_ref`';
        }
        if ($this->isColumnModified(POrderArchivePeer::INVOICE_AT)) {
            $modifiedColumns[':p' . $index++]  = '`invoice_at`';
        }
        if ($this->isColumnModified(POrderArchivePeer::INVOICE_FILENAME)) {
            $modifiedColumns[':p' . $index++]  = '`invoice_filename`';
        }
        if ($this->isColumnModified(POrderArchivePeer::SUPPORTING_DOCUMENT)) {
            $modifiedColumns[':p' . $index++]  = '`supporting_document`';
        }
        if ($this->isColumnModified(POrderArchivePeer::ELECTIVE_MANDATES)) {
            $modifiedColumns[':p' . $index++]  = '`elective_mandates`';
        }
        if ($this->isColumnModified(POrderArchivePeer::CREATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`created_at`';
        }
        if ($this->isColumnModified(POrderArchivePeer::UPDATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`updated_at`';
        }
        if ($this->isColumnModified(POrderArchivePeer::ARCHIVED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`archived_at`';
        }

        $sql = sprintf(
            'INSERT INTO `p_order_archive` (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case '`id`':
                        $stmt->bindValue($identifier, $this->id, PDO::PARAM_INT);
                        break;
                    case '`p_user_id`':
                        $stmt->bindValue($identifier, $this->p_user_id, PDO::PARAM_INT);
                        break;
                    case '`p_o_order_state_id`':
                        $stmt->bindValue($identifier, $this->p_o_order_state_id, PDO::PARAM_INT);
                        break;
                    case '`p_o_payment_state_id`':
                        $stmt->bindValue($identifier, $this->p_o_payment_state_id, PDO::PARAM_INT);
                        break;
                    case '`p_o_payment_type_id`':
                        $stmt->bindValue($identifier, $this->p_o_payment_type_id, PDO::PARAM_INT);
                        break;
                    case '`p_o_subscription_id`':
                        $stmt->bindValue($identifier, $this->p_o_subscription_id, PDO::PARAM_INT);
                        break;
                    case '`subscription_title`':
                        $stmt->bindValue($identifier, $this->subscription_title, PDO::PARAM_STR);
                        break;
                    case '`subscription_description`':
                        $stmt->bindValue($identifier, $this->subscription_description, PDO::PARAM_STR);
                        break;
                    case '`subscription_begin_at`':
                        $stmt->bindValue($identifier, $this->subscription_begin_at, PDO::PARAM_STR);
                        break;
                    case '`subscription_end_at`':
                        $stmt->bindValue($identifier, $this->subscription_end_at, PDO::PARAM_STR);
                        break;
                    case '`information`':
                        $stmt->bindValue($identifier, $this->information, PDO::PARAM_STR);
                        break;
                    case '`price`':
                        $stmt->bindValue($identifier, $this->price, PDO::PARAM_STR);
                        break;
                    case '`promotion`':
                        $stmt->bindValue($identifier, $this->promotion, PDO::PARAM_STR);
                        break;
                    case '`total`':
                        $stmt->bindValue($identifier, $this->total, PDO::PARAM_STR);
                        break;
                    case '`gender`':
                        $stmt->bindValue($identifier, $this->gender, PDO::PARAM_INT);
                        break;
                    case '`name`':
                        $stmt->bindValue($identifier, $this->name, PDO::PARAM_STR);
                        break;
                    case '`firstname`':
                        $stmt->bindValue($identifier, $this->firstname, PDO::PARAM_STR);
                        break;
                    case '`phone`':
                        $stmt->bindValue($identifier, $this->phone, PDO::PARAM_STR);
                        break;
                    case '`email`':
                        $stmt->bindValue($identifier, $this->email, PDO::PARAM_STR);
                        break;
                    case '`invoice_ref`':
                        $stmt->bindValue($identifier, $this->invoice_ref, PDO::PARAM_STR);
                        break;
                    case '`invoice_at`':
                        $stmt->bindValue($identifier, $this->invoice_at, PDO::PARAM_STR);
                        break;
                    case '`invoice_filename`':
                        $stmt->bindValue($identifier, $this->invoice_filename, PDO::PARAM_STR);
                        break;
                    case '`supporting_document`':
                        $stmt->bindValue($identifier, $this->supporting_document, PDO::PARAM_STR);
                        break;
                    case '`elective_mandates`':
                        $stmt->bindValue($identifier, $this->elective_mandates, PDO::PARAM_STR);
                        break;
                    case '`created_at`':
                        $stmt->bindValue($identifier, $this->created_at, PDO::PARAM_STR);
                        break;
                    case '`updated_at`':
                        $stmt->bindValue($identifier, $this->updated_at, PDO::PARAM_STR);
                        break;
                    case '`archived_at`':
                        $stmt->bindValue($identifier, $this->archived_at, PDO::PARAM_STR);
                        break;
                }
            }
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute INSERT statement [%s]', $sql), $e);
        }

        $this->setNew(false);
    }

    /**
     * Update the row in the database.
     *
     * @param PropelPDO $con
     *
     * @see        doSave()
     */
    protected function doUpdate(PropelPDO $con)
    {
        $selectCriteria = $this->buildPkeyCriteria();
        $valuesCriteria = $this->buildCriteria();
        BasePeer::doUpdate($selectCriteria, $valuesCriteria, $con);
    }

    /**
     * Array of ValidationFailed objects.
     * @var        array ValidationFailed[]
     */
    protected $validationFailures = array();

    /**
     * Gets any ValidationFailed objects that resulted from last call to validate().
     *
     *
     * @return array ValidationFailed[]
     * @see        validate()
     */
    public function getValidationFailures()
    {
        return $this->validationFailures;
    }

    /**
     * Validates the objects modified field values and all objects related to this table.
     *
     * If $columns is either a column name or an array of column names
     * only those columns are validated.
     *
     * @param mixed $columns Column name or an array of column names.
     * @return boolean Whether all columns pass validation.
     * @see        doValidate()
     * @see        getValidationFailures()
     */
    public function validate($columns = null)
    {
        $res = $this->doValidate($columns);
        if ($res === true) {
            $this->validationFailures = array();

            return true;
        }

        $this->validationFailures = $res;

        return false;
    }

    /**
     * This function performs the validation work for complex object models.
     *
     * In addition to checking the current object, all related objects will
     * also be validated.  If all pass then <code>true</code> is returned; otherwise
     * an aggregated array of ValidationFailed objects will be returned.
     *
     * @param array $columns Array of column names to validate.
     * @return mixed <code>true</code> if all validations pass; array of <code>ValidationFailed</code> objects otherwise.
     */
    protected function doValidate($columns = null)
    {
        if (!$this->alreadyInValidation) {
            $this->alreadyInValidation = true;
            $retval = null;

            $failureMap = array();


            if (($retval = POrderArchivePeer::doValidate($this, $columns)) !== true) {
                $failureMap = array_merge($failureMap, $retval);
            }



            $this->alreadyInValidation = false;
        }

        return (!empty($failureMap) ? $failureMap : true);
    }

    /**
     * Retrieves a field from the object by name passed in as a string.
     *
     * @param string $name name
     * @param string $type The type of fieldname the $name is of:
     *               one of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME
     *               BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM.
     *               Defaults to BasePeer::TYPE_PHPNAME
     * @return mixed Value of field.
     */
    public function getByName($name, $type = BasePeer::TYPE_PHPNAME)
    {
        $pos = POrderArchivePeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
        $field = $this->getByPosition($pos);

        return $field;
    }

    /**
     * Retrieves a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param int $pos position in xml schema
     * @return mixed Value of field at $pos
     */
    public function getByPosition($pos)
    {
        switch ($pos) {
            case 0:
                return $this->getId();
                break;
            case 1:
                return $this->getPUserId();
                break;
            case 2:
                return $this->getPOOrderStateId();
                break;
            case 3:
                return $this->getPOPaymentStateId();
                break;
            case 4:
                return $this->getPOPaymentTypeId();
                break;
            case 5:
                return $this->getPOSubscriptionId();
                break;
            case 6:
                return $this->getSubscriptionTitle();
                break;
            case 7:
                return $this->getSubscriptionDescription();
                break;
            case 8:
                return $this->getSubscriptionBeginAt();
                break;
            case 9:
                return $this->getSubscriptionEndAt();
                break;
            case 10:
                return $this->getInformation();
                break;
            case 11:
                return $this->getPrice();
                break;
            case 12:
                return $this->getPromotion();
                break;
            case 13:
                return $this->getTotal();
                break;
            case 14:
                return $this->getGender();
                break;
            case 15:
                return $this->getName();
                break;
            case 16:
                return $this->getFirstname();
                break;
            case 17:
                return $this->getPhone();
                break;
            case 18:
                return $this->getEmail();
                break;
            case 19:
                return $this->getInvoiceRef();
                break;
            case 20:
                return $this->getInvoiceAt();
                break;
            case 21:
                return $this->getInvoiceFilename();
                break;
            case 22:
                return $this->getSupportingDocument();
                break;
            case 23:
                return $this->getElectiveMandates();
                break;
            case 24:
                return $this->getCreatedAt();
                break;
            case 25:
                return $this->getUpdatedAt();
                break;
            case 26:
                return $this->getArchivedAt();
                break;
            default:
                return null;
                break;
        } // switch()
    }

    /**
     * Exports the object as an array.
     *
     * You can specify the key type of the array by passing one of the class
     * type constants.
     *
     * @param     string  $keyType (optional) One of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME,
     *                    BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM.
     *                    Defaults to BasePeer::TYPE_PHPNAME.
     * @param     boolean $includeLazyLoadColumns (optional) Whether to include lazy loaded columns. Defaults to true.
     * @param     array $alreadyDumpedObjects List of objects to skip to avoid recursion
     *
     * @return array an associative array containing the field names (as keys) and field values
     */
    public function toArray($keyType = BasePeer::TYPE_PHPNAME, $includeLazyLoadColumns = true, $alreadyDumpedObjects = array())
    {
        if (isset($alreadyDumpedObjects['POrderArchive'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['POrderArchive'][$this->getPrimaryKey()] = true;
        $keys = POrderArchivePeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getPUserId(),
            $keys[2] => $this->getPOOrderStateId(),
            $keys[3] => $this->getPOPaymentStateId(),
            $keys[4] => $this->getPOPaymentTypeId(),
            $keys[5] => $this->getPOSubscriptionId(),
            $keys[6] => $this->getSubscriptionTitle(),
            $keys[7] => $this->getSubscriptionDescription(),
            $keys[8] => $this->getSubscriptionBeginAt(),
            $keys[9] => $this->getSubscriptionEndAt(),
            $keys[10] => $this->getInformation(),
            $keys[11] => $this->getPrice(),
            $keys[12] => $this->getPromotion(),
            $keys[13] => $this->getTotal(),
            $keys[14] => $this->getGender(),
            $keys[15] => $this->getName(),
            $keys[16] => $this->getFirstname(),
            $keys[17] => $this->getPhone(),
            $keys[18] => $this->getEmail(),
            $keys[19] => $this->getInvoiceRef(),
            $keys[20] => $this->getInvoiceAt(),
            $keys[21] => $this->getInvoiceFilename(),
            $keys[22] => $this->getSupportingDocument(),
            $keys[23] => $this->getElectiveMandates(),
            $keys[24] => $this->getCreatedAt(),
            $keys[25] => $this->getUpdatedAt(),
            $keys[26] => $this->getArchivedAt(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }


        return $result;
    }

    /**
     * Sets a field from the object by name passed in as a string.
     *
     * @param string $name peer name
     * @param mixed $value field value
     * @param string $type The type of fieldname the $name is of:
     *                     one of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME
     *                     BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM.
     *                     Defaults to BasePeer::TYPE_PHPNAME
     * @return void
     */
    public function setByName($name, $value, $type = BasePeer::TYPE_PHPNAME)
    {
        $pos = POrderArchivePeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);

        $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param int $pos position in xml schema
     * @param mixed $value field value
     * @return void
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setId($value);
                break;
            case 1:
                $this->setPUserId($value);
                break;
            case 2:
                $this->setPOOrderStateId($value);
                break;
            case 3:
                $this->setPOPaymentStateId($value);
                break;
            case 4:
                $this->setPOPaymentTypeId($value);
                break;
            case 5:
                $this->setPOSubscriptionId($value);
                break;
            case 6:
                $this->setSubscriptionTitle($value);
                break;
            case 7:
                $this->setSubscriptionDescription($value);
                break;
            case 8:
                $this->setSubscriptionBeginAt($value);
                break;
            case 9:
                $this->setSubscriptionEndAt($value);
                break;
            case 10:
                $this->setInformation($value);
                break;
            case 11:
                $this->setPrice($value);
                break;
            case 12:
                $this->setPromotion($value);
                break;
            case 13:
                $this->setTotal($value);
                break;
            case 14:
                $valueSet = POrderArchivePeer::getValueSet(POrderArchivePeer::GENDER);
                if (isset($valueSet[$value])) {
                    $value = $valueSet[$value];
                }
                $this->setGender($value);
                break;
            case 15:
                $this->setName($value);
                break;
            case 16:
                $this->setFirstname($value);
                break;
            case 17:
                $this->setPhone($value);
                break;
            case 18:
                $this->setEmail($value);
                break;
            case 19:
                $this->setInvoiceRef($value);
                break;
            case 20:
                $this->setInvoiceAt($value);
                break;
            case 21:
                $this->setInvoiceFilename($value);
                break;
            case 22:
                $this->setSupportingDocument($value);
                break;
            case 23:
                $this->setElectiveMandates($value);
                break;
            case 24:
                $this->setCreatedAt($value);
                break;
            case 25:
                $this->setUpdatedAt($value);
                break;
            case 26:
                $this->setArchivedAt($value);
                break;
        } // switch()
    }

    /**
     * Populates the object using an array.
     *
     * This is particularly useful when populating an object from one of the
     * request arrays (e.g. $_POST).  This method goes through the column
     * names, checking to see whether a matching key exists in populated
     * array. If so the setByName() method is called for that column.
     *
     * You can specify the key type of the array by additionally passing one
     * of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME,
     * BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM.
     * The default key type is the column's BasePeer::TYPE_PHPNAME
     *
     * @param array  $arr     An array to populate the object from.
     * @param string $keyType The type of keys the array uses.
     * @return void
     */
    public function fromArray($arr, $keyType = BasePeer::TYPE_PHPNAME)
    {
        $keys = POrderArchivePeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setPUserId($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setPOOrderStateId($arr[$keys[2]]);
        if (array_key_exists($keys[3], $arr)) $this->setPOPaymentStateId($arr[$keys[3]]);
        if (array_key_exists($keys[4], $arr)) $this->setPOPaymentTypeId($arr[$keys[4]]);
        if (array_key_exists($keys[5], $arr)) $this->setPOSubscriptionId($arr[$keys[5]]);
        if (array_key_exists($keys[6], $arr)) $this->setSubscriptionTitle($arr[$keys[6]]);
        if (array_key_exists($keys[7], $arr)) $this->setSubscriptionDescription($arr[$keys[7]]);
        if (array_key_exists($keys[8], $arr)) $this->setSubscriptionBeginAt($arr[$keys[8]]);
        if (array_key_exists($keys[9], $arr)) $this->setSubscriptionEndAt($arr[$keys[9]]);
        if (array_key_exists($keys[10], $arr)) $this->setInformation($arr[$keys[10]]);
        if (array_key_exists($keys[11], $arr)) $this->setPrice($arr[$keys[11]]);
        if (array_key_exists($keys[12], $arr)) $this->setPromotion($arr[$keys[12]]);
        if (array_key_exists($keys[13], $arr)) $this->setTotal($arr[$keys[13]]);
        if (array_key_exists($keys[14], $arr)) $this->setGender($arr[$keys[14]]);
        if (array_key_exists($keys[15], $arr)) $this->setName($arr[$keys[15]]);
        if (array_key_exists($keys[16], $arr)) $this->setFirstname($arr[$keys[16]]);
        if (array_key_exists($keys[17], $arr)) $this->setPhone($arr[$keys[17]]);
        if (array_key_exists($keys[18], $arr)) $this->setEmail($arr[$keys[18]]);
        if (array_key_exists($keys[19], $arr)) $this->setInvoiceRef($arr[$keys[19]]);
        if (array_key_exists($keys[20], $arr)) $this->setInvoiceAt($arr[$keys[20]]);
        if (array_key_exists($keys[21], $arr)) $this->setInvoiceFilename($arr[$keys[21]]);
        if (array_key_exists($keys[22], $arr)) $this->setSupportingDocument($arr[$keys[22]]);
        if (array_key_exists($keys[23], $arr)) $this->setElectiveMandates($arr[$keys[23]]);
        if (array_key_exists($keys[24], $arr)) $this->setCreatedAt($arr[$keys[24]]);
        if (array_key_exists($keys[25], $arr)) $this->setUpdatedAt($arr[$keys[25]]);
        if (array_key_exists($keys[26], $arr)) $this->setArchivedAt($arr[$keys[26]]);
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(POrderArchivePeer::DATABASE_NAME);

        if ($this->isColumnModified(POrderArchivePeer::ID)) $criteria->add(POrderArchivePeer::ID, $this->id);
        if ($this->isColumnModified(POrderArchivePeer::P_USER_ID)) $criteria->add(POrderArchivePeer::P_USER_ID, $this->p_user_id);
        if ($this->isColumnModified(POrderArchivePeer::P_O_ORDER_STATE_ID)) $criteria->add(POrderArchivePeer::P_O_ORDER_STATE_ID, $this->p_o_order_state_id);
        if ($this->isColumnModified(POrderArchivePeer::P_O_PAYMENT_STATE_ID)) $criteria->add(POrderArchivePeer::P_O_PAYMENT_STATE_ID, $this->p_o_payment_state_id);
        if ($this->isColumnModified(POrderArchivePeer::P_O_PAYMENT_TYPE_ID)) $criteria->add(POrderArchivePeer::P_O_PAYMENT_TYPE_ID, $this->p_o_payment_type_id);
        if ($this->isColumnModified(POrderArchivePeer::P_O_SUBSCRIPTION_ID)) $criteria->add(POrderArchivePeer::P_O_SUBSCRIPTION_ID, $this->p_o_subscription_id);
        if ($this->isColumnModified(POrderArchivePeer::SUBSCRIPTION_TITLE)) $criteria->add(POrderArchivePeer::SUBSCRIPTION_TITLE, $this->subscription_title);
        if ($this->isColumnModified(POrderArchivePeer::SUBSCRIPTION_DESCRIPTION)) $criteria->add(POrderArchivePeer::SUBSCRIPTION_DESCRIPTION, $this->subscription_description);
        if ($this->isColumnModified(POrderArchivePeer::SUBSCRIPTION_BEGIN_AT)) $criteria->add(POrderArchivePeer::SUBSCRIPTION_BEGIN_AT, $this->subscription_begin_at);
        if ($this->isColumnModified(POrderArchivePeer::SUBSCRIPTION_END_AT)) $criteria->add(POrderArchivePeer::SUBSCRIPTION_END_AT, $this->subscription_end_at);
        if ($this->isColumnModified(POrderArchivePeer::INFORMATION)) $criteria->add(POrderArchivePeer::INFORMATION, $this->information);
        if ($this->isColumnModified(POrderArchivePeer::PRICE)) $criteria->add(POrderArchivePeer::PRICE, $this->price);
        if ($this->isColumnModified(POrderArchivePeer::PROMOTION)) $criteria->add(POrderArchivePeer::PROMOTION, $this->promotion);
        if ($this->isColumnModified(POrderArchivePeer::TOTAL)) $criteria->add(POrderArchivePeer::TOTAL, $this->total);
        if ($this->isColumnModified(POrderArchivePeer::GENDER)) $criteria->add(POrderArchivePeer::GENDER, $this->gender);
        if ($this->isColumnModified(POrderArchivePeer::NAME)) $criteria->add(POrderArchivePeer::NAME, $this->name);
        if ($this->isColumnModified(POrderArchivePeer::FIRSTNAME)) $criteria->add(POrderArchivePeer::FIRSTNAME, $this->firstname);
        if ($this->isColumnModified(POrderArchivePeer::PHONE)) $criteria->add(POrderArchivePeer::PHONE, $this->phone);
        if ($this->isColumnModified(POrderArchivePeer::EMAIL)) $criteria->add(POrderArchivePeer::EMAIL, $this->email);
        if ($this->isColumnModified(POrderArchivePeer::INVOICE_REF)) $criteria->add(POrderArchivePeer::INVOICE_REF, $this->invoice_ref);
        if ($this->isColumnModified(POrderArchivePeer::INVOICE_AT)) $criteria->add(POrderArchivePeer::INVOICE_AT, $this->invoice_at);
        if ($this->isColumnModified(POrderArchivePeer::INVOICE_FILENAME)) $criteria->add(POrderArchivePeer::INVOICE_FILENAME, $this->invoice_filename);
        if ($this->isColumnModified(POrderArchivePeer::SUPPORTING_DOCUMENT)) $criteria->add(POrderArchivePeer::SUPPORTING_DOCUMENT, $this->supporting_document);
        if ($this->isColumnModified(POrderArchivePeer::ELECTIVE_MANDATES)) $criteria->add(POrderArchivePeer::ELECTIVE_MANDATES, $this->elective_mandates);
        if ($this->isColumnModified(POrderArchivePeer::CREATED_AT)) $criteria->add(POrderArchivePeer::CREATED_AT, $this->created_at);
        if ($this->isColumnModified(POrderArchivePeer::UPDATED_AT)) $criteria->add(POrderArchivePeer::UPDATED_AT, $this->updated_at);
        if ($this->isColumnModified(POrderArchivePeer::ARCHIVED_AT)) $criteria->add(POrderArchivePeer::ARCHIVED_AT, $this->archived_at);

        return $criteria;
    }

    /**
     * Builds a Criteria object containing the primary key for this object.
     *
     * Unlike buildCriteria() this method includes the primary key values regardless
     * of whether or not they have been modified.
     *
     * @return Criteria The Criteria object containing value(s) for primary key(s).
     */
    public function buildPkeyCriteria()
    {
        $criteria = new Criteria(POrderArchivePeer::DATABASE_NAME);
        $criteria->add(POrderArchivePeer::ID, $this->id);

        return $criteria;
    }

    /**
     * Returns the primary key for this object (row).
     * @return int
     */
    public function getPrimaryKey()
    {
        return $this->getId();
    }

    /**
     * Generic method to set the primary key (id column).
     *
     * @param  int $key Primary key.
     * @return void
     */
    public function setPrimaryKey($key)
    {
        $this->setId($key);
    }

    /**
     * Returns true if the primary key for this object is null.
     * @return boolean
     */
    public function isPrimaryKeyNull()
    {

        return null === $this->getId();
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param object $copyObj An object of POrderArchive (or compatible) type.
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setPUserId($this->getPUserId());
        $copyObj->setPOOrderStateId($this->getPOOrderStateId());
        $copyObj->setPOPaymentStateId($this->getPOPaymentStateId());
        $copyObj->setPOPaymentTypeId($this->getPOPaymentTypeId());
        $copyObj->setPOSubscriptionId($this->getPOSubscriptionId());
        $copyObj->setSubscriptionTitle($this->getSubscriptionTitle());
        $copyObj->setSubscriptionDescription($this->getSubscriptionDescription());
        $copyObj->setSubscriptionBeginAt($this->getSubscriptionBeginAt());
        $copyObj->setSubscriptionEndAt($this->getSubscriptionEndAt());
        $copyObj->setInformation($this->getInformation());
        $copyObj->setPrice($this->getPrice());
        $copyObj->setPromotion($this->getPromotion());
        $copyObj->setTotal($this->getTotal());
        $copyObj->setGender($this->getGender());
        $copyObj->setName($this->getName());
        $copyObj->setFirstname($this->getFirstname());
        $copyObj->setPhone($this->getPhone());
        $copyObj->setEmail($this->getEmail());
        $copyObj->setInvoiceRef($this->getInvoiceRef());
        $copyObj->setInvoiceAt($this->getInvoiceAt());
        $copyObj->setInvoiceFilename($this->getInvoiceFilename());
        $copyObj->setSupportingDocument($this->getSupportingDocument());
        $copyObj->setElectiveMandates($this->getElectiveMandates());
        $copyObj->setCreatedAt($this->getCreatedAt());
        $copyObj->setUpdatedAt($this->getUpdatedAt());
        $copyObj->setArchivedAt($this->getArchivedAt());
        if ($makeNew) {
            $copyObj->setNew(true);
            $copyObj->setId(NULL); // this is a auto-increment column, so set to default value
        }
    }

    /**
     * Makes a copy of this object that will be inserted as a new row in table when saved.
     * It creates a new object filling in the simple attributes, but skipping any primary
     * keys that are defined for the table.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @return POrderArchive Clone of current object.
     * @throws PropelException
     */
    public function copy($deepCopy = false)
    {
        // we use get_class(), because this might be a subclass
        $clazz = get_class($this);
        $copyObj = new $clazz();
        $this->copyInto($copyObj, $deepCopy);

        return $copyObj;
    }

    /**
     * Returns a peer instance associated with this om.
     *
     * Since Peer classes are not to have any instance attributes, this method returns the
     * same instance for all member of this class. The method could therefore
     * be static, but this would prevent one from overriding the behavior.
     *
     * @return POrderArchivePeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new POrderArchivePeer();
        }

        return self::$peer;
    }

    /**
     * Clears the current object and sets all attributes to their default values
     */
    public function clear()
    {
        $this->id = null;
        $this->p_user_id = null;
        $this->p_o_order_state_id = null;
        $this->p_o_payment_state_id = null;
        $this->p_o_payment_type_id = null;
        $this->p_o_subscription_id = null;
        $this->subscription_title = null;
        $this->subscription_description = null;
        $this->subscription_begin_at = null;
        $this->subscription_end_at = null;
        $this->information = null;
        $this->price = null;
        $this->promotion = null;
        $this->total = null;
        $this->gender = null;
        $this->name = null;
        $this->firstname = null;
        $this->phone = null;
        $this->email = null;
        $this->invoice_ref = null;
        $this->invoice_at = null;
        $this->invoice_filename = null;
        $this->supporting_document = null;
        $this->elective_mandates = null;
        $this->created_at = null;
        $this->updated_at = null;
        $this->archived_at = null;
        $this->alreadyInSave = false;
        $this->alreadyInValidation = false;
        $this->alreadyInClearAllReferencesDeep = false;
        $this->clearAllReferences();
        $this->resetModified();
        $this->setNew(true);
        $this->setDeleted(false);
    }

    /**
     * Resets all references to other model objects or collections of model objects.
     *
     * This method is a user-space workaround for PHP's inability to garbage collect
     * objects with circular references (even in PHP 5.3). This is currently necessary
     * when using Propel in certain daemon or large-volume/high-memory operations.
     *
     * @param boolean $deep Whether to also clear the references on all referrer objects.
     */
    public function clearAllReferences($deep = false)
    {
        if ($deep && !$this->alreadyInClearAllReferencesDeep) {
            $this->alreadyInClearAllReferencesDeep = true;

            $this->alreadyInClearAllReferencesDeep = false;
        } // if ($deep)

    }

    /**
     * return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(POrderArchivePeer::DEFAULT_STRING_FORMAT);
    }

    /**
     * return true is the object is in saving state
     *
     * @return boolean
     */
    public function isAlreadyInSave()
    {
        return $this->alreadyInSave;
    }

    // event behavior
    public function preCommit(\PropelPDO $con = null){}
    public function preCommitSave(\PropelPDO $con = null){}
    public function preCommitDelete(\PropelPDO $con = null){}
    public function preCommitUpdate(\PropelPDO $con = null){}
    public function preCommitInsert(\PropelPDO $con = null){}
    public function preRollback(\PropelPDO $con = null){}
    public function preRollbackSave(\PropelPDO $con = null){}
    public function preRollbackDelete(\PropelPDO $con = null){}
    public function preRollbackUpdate(\PropelPDO $con = null){}
    public function preRollbackInsert(\PropelPDO $con = null){}

}
