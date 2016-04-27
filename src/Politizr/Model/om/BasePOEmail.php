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
use Politizr\Model\POEmail;
use Politizr\Model\POEmailPeer;
use Politizr\Model\POEmailQuery;
use Politizr\Model\POOrderState;
use Politizr\Model\POOrderStateQuery;
use Politizr\Model\POPaymentState;
use Politizr\Model\POPaymentStateQuery;
use Politizr\Model\POPaymentType;
use Politizr\Model\POPaymentTypeQuery;
use Politizr\Model\POSubscription;
use Politizr\Model\POSubscriptionQuery;
use Politizr\Model\POrder;
use Politizr\Model\POrderQuery;

abstract class BasePOEmail extends BaseObject implements Persistent
{
    /**
     * Peer class name
     */
    const PEER = 'Politizr\\Model\\POEmailPeer';

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        POEmailPeer
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
     * The value for the p_order_id field.
     * @var        int
     */
    protected $p_order_id;

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
     * The value for the send field.
     * @var        string
     */
    protected $send;

    /**
     * The value for the subject field.
     * @var        string
     */
    protected $subject;

    /**
     * The value for the html_body field.
     * @var        string
     */
    protected $html_body;

    /**
     * The value for the txt_body field.
     * @var        string
     */
    protected $txt_body;

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
     * @var        POrder
     */
    protected $aPOrder;

    /**
     * @var        POOrderState
     */
    protected $aPOOrderState;

    /**
     * @var        POPaymentState
     */
    protected $aPOPaymentState;

    /**
     * @var        POPaymentType
     */
    protected $aPOPaymentType;

    /**
     * @var        POSubscription
     */
    protected $aPOSubscription;

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

    /**
     * Get the [p_order_id] column value.
     *
     * @return int
     */
    public function getPOrderId()
    {

        return $this->p_order_id;
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
     * Get the [send] column value.
     *
     * @return string
     */
    public function getSend()
    {

        return $this->send;
    }

    /**
     * Get the [subject] column value.
     *
     * @return string
     */
    public function getSubject()
    {

        return $this->subject;
    }

    /**
     * Get the [html_body] column value.
     *
     * @return string
     */
    public function getHtmlBody()
    {

        return $this->html_body;
    }

    /**
     * Get the [txt_body] column value.
     *
     * @return string
     */
    public function getTxtBody()
    {

        return $this->txt_body;
    }

    /**
     * Get the [optionally formatted] temporal [created_at] column value.
     *
     *
     * @param string $format The date/time format string (either date()-style or strftime()-style).
     *				 If format is null, then the raw DateTime object will be returned.
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
     *				 If format is null, then the raw DateTime object will be returned.
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
     * Set the value of [id] column.
     *
     * @param  int $v new value
     * @return POEmail The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[] = POEmailPeer::ID;
        }


        return $this;
    } // setId()

    /**
     * Set the value of [p_order_id] column.
     *
     * @param  int $v new value
     * @return POEmail The current object (for fluent API support)
     */
    public function setPOrderId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->p_order_id !== $v) {
            $this->p_order_id = $v;
            $this->modifiedColumns[] = POEmailPeer::P_ORDER_ID;
        }

        if ($this->aPOrder !== null && $this->aPOrder->getId() !== $v) {
            $this->aPOrder = null;
        }


        return $this;
    } // setPOrderId()

    /**
     * Set the value of [p_o_order_state_id] column.
     *
     * @param  int $v new value
     * @return POEmail The current object (for fluent API support)
     */
    public function setPOOrderStateId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->p_o_order_state_id !== $v) {
            $this->p_o_order_state_id = $v;
            $this->modifiedColumns[] = POEmailPeer::P_O_ORDER_STATE_ID;
        }

        if ($this->aPOOrderState !== null && $this->aPOOrderState->getId() !== $v) {
            $this->aPOOrderState = null;
        }


        return $this;
    } // setPOOrderStateId()

    /**
     * Set the value of [p_o_payment_state_id] column.
     *
     * @param  int $v new value
     * @return POEmail The current object (for fluent API support)
     */
    public function setPOPaymentStateId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->p_o_payment_state_id !== $v) {
            $this->p_o_payment_state_id = $v;
            $this->modifiedColumns[] = POEmailPeer::P_O_PAYMENT_STATE_ID;
        }

        if ($this->aPOPaymentState !== null && $this->aPOPaymentState->getId() !== $v) {
            $this->aPOPaymentState = null;
        }


        return $this;
    } // setPOPaymentStateId()

    /**
     * Set the value of [p_o_payment_type_id] column.
     *
     * @param  int $v new value
     * @return POEmail The current object (for fluent API support)
     */
    public function setPOPaymentTypeId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->p_o_payment_type_id !== $v) {
            $this->p_o_payment_type_id = $v;
            $this->modifiedColumns[] = POEmailPeer::P_O_PAYMENT_TYPE_ID;
        }

        if ($this->aPOPaymentType !== null && $this->aPOPaymentType->getId() !== $v) {
            $this->aPOPaymentType = null;
        }


        return $this;
    } // setPOPaymentTypeId()

    /**
     * Set the value of [p_o_subscription_id] column.
     *
     * @param  int $v new value
     * @return POEmail The current object (for fluent API support)
     */
    public function setPOSubscriptionId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->p_o_subscription_id !== $v) {
            $this->p_o_subscription_id = $v;
            $this->modifiedColumns[] = POEmailPeer::P_O_SUBSCRIPTION_ID;
        }

        if ($this->aPOSubscription !== null && $this->aPOSubscription->getId() !== $v) {
            $this->aPOSubscription = null;
        }


        return $this;
    } // setPOSubscriptionId()

    /**
     * Set the value of [send] column.
     *
     * @param  string $v new value
     * @return POEmail The current object (for fluent API support)
     */
    public function setSend($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->send !== $v) {
            $this->send = $v;
            $this->modifiedColumns[] = POEmailPeer::SEND;
        }


        return $this;
    } // setSend()

    /**
     * Set the value of [subject] column.
     *
     * @param  string $v new value
     * @return POEmail The current object (for fluent API support)
     */
    public function setSubject($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->subject !== $v) {
            $this->subject = $v;
            $this->modifiedColumns[] = POEmailPeer::SUBJECT;
        }


        return $this;
    } // setSubject()

    /**
     * Set the value of [html_body] column.
     *
     * @param  string $v new value
     * @return POEmail The current object (for fluent API support)
     */
    public function setHtmlBody($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->html_body !== $v) {
            $this->html_body = $v;
            $this->modifiedColumns[] = POEmailPeer::HTML_BODY;
        }


        return $this;
    } // setHtmlBody()

    /**
     * Set the value of [txt_body] column.
     *
     * @param  string $v new value
     * @return POEmail The current object (for fluent API support)
     */
    public function setTxtBody($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->txt_body !== $v) {
            $this->txt_body = $v;
            $this->modifiedColumns[] = POEmailPeer::TXT_BODY;
        }


        return $this;
    } // setTxtBody()

    /**
     * Sets the value of [created_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return POEmail The current object (for fluent API support)
     */
    public function setCreatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->created_at !== null || $dt !== null) {
            $currentDateAsString = ($this->created_at !== null && $tmpDt = new DateTime($this->created_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->created_at = $newDateAsString;
                $this->modifiedColumns[] = POEmailPeer::CREATED_AT;
            }
        } // if either are not null


        return $this;
    } // setCreatedAt()

    /**
     * Sets the value of [updated_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return POEmail The current object (for fluent API support)
     */
    public function setUpdatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->updated_at !== null || $dt !== null) {
            $currentDateAsString = ($this->updated_at !== null && $tmpDt = new DateTime($this->updated_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->updated_at = $newDateAsString;
                $this->modifiedColumns[] = POEmailPeer::UPDATED_AT;
            }
        } // if either are not null


        return $this;
    } // setUpdatedAt()

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
            $this->p_order_id = ($row[$startcol + 1] !== null) ? (int) $row[$startcol + 1] : null;
            $this->p_o_order_state_id = ($row[$startcol + 2] !== null) ? (int) $row[$startcol + 2] : null;
            $this->p_o_payment_state_id = ($row[$startcol + 3] !== null) ? (int) $row[$startcol + 3] : null;
            $this->p_o_payment_type_id = ($row[$startcol + 4] !== null) ? (int) $row[$startcol + 4] : null;
            $this->p_o_subscription_id = ($row[$startcol + 5] !== null) ? (int) $row[$startcol + 5] : null;
            $this->send = ($row[$startcol + 6] !== null) ? (string) $row[$startcol + 6] : null;
            $this->subject = ($row[$startcol + 7] !== null) ? (string) $row[$startcol + 7] : null;
            $this->html_body = ($row[$startcol + 8] !== null) ? (string) $row[$startcol + 8] : null;
            $this->txt_body = ($row[$startcol + 9] !== null) ? (string) $row[$startcol + 9] : null;
            $this->created_at = ($row[$startcol + 10] !== null) ? (string) $row[$startcol + 10] : null;
            $this->updated_at = ($row[$startcol + 11] !== null) ? (string) $row[$startcol + 11] : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }
            $this->postHydrate($row, $startcol, $rehydrate);

            return $startcol + 12; // 12 = POEmailPeer::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating POEmail object", $e);
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

        if ($this->aPOrder !== null && $this->p_order_id !== $this->aPOrder->getId()) {
            $this->aPOrder = null;
        }
        if ($this->aPOOrderState !== null && $this->p_o_order_state_id !== $this->aPOOrderState->getId()) {
            $this->aPOOrderState = null;
        }
        if ($this->aPOPaymentState !== null && $this->p_o_payment_state_id !== $this->aPOPaymentState->getId()) {
            $this->aPOPaymentState = null;
        }
        if ($this->aPOPaymentType !== null && $this->p_o_payment_type_id !== $this->aPOPaymentType->getId()) {
            $this->aPOPaymentType = null;
        }
        if ($this->aPOSubscription !== null && $this->p_o_subscription_id !== $this->aPOSubscription->getId()) {
            $this->aPOSubscription = null;
        }
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
            $con = Propel::getConnection(POEmailPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $stmt = POEmailPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
        $row = $stmt->fetch(PDO::FETCH_NUM);
        $stmt->closeCursor();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aPOrder = null;
            $this->aPOOrderState = null;
            $this->aPOPaymentState = null;
            $this->aPOPaymentType = null;
            $this->aPOSubscription = null;
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
            $con = Propel::getConnection(POEmailPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        try {
            $deleteQuery = POEmailQuery::create()
                ->filterByPrimaryKey($this->getPrimaryKey());
            $ret = $this->preDelete($con);
            if ($ret) {
                $deleteQuery->delete($con);
                $this->postDelete($con);
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
            $con = Propel::getConnection(POEmailPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        $isInsert = $this->isNew();
        try {
            $ret = $this->preSave($con);
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
                // timestampable behavior
                if (!$this->isColumnModified(POEmailPeer::CREATED_AT)) {
                    $this->setCreatedAt(time());
                }
                if (!$this->isColumnModified(POEmailPeer::UPDATED_AT)) {
                    $this->setUpdatedAt(time());
                }
            } else {
                $ret = $ret && $this->preUpdate($con);
                // timestampable behavior
                if ($this->isModified() && !$this->isColumnModified(POEmailPeer::UPDATED_AT)) {
                    $this->setUpdatedAt(time());
                }
            }
            if ($ret) {
                $affectedRows = $this->doSave($con);
                if ($isInsert) {
                    $this->postInsert($con);
                } else {
                    $this->postUpdate($con);
                }
                $this->postSave($con);
                POEmailPeer::addInstanceToPool($this);
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

            // We call the save method on the following object(s) if they
            // were passed to this object by their corresponding set
            // method.  This object relates to these object(s) by a
            // foreign key reference.

            if ($this->aPOrder !== null) {
                if ($this->aPOrder->isModified() || $this->aPOrder->isNew()) {
                    $affectedRows += $this->aPOrder->save($con);
                }
                $this->setPOrder($this->aPOrder);
            }

            if ($this->aPOOrderState !== null) {
                if ($this->aPOOrderState->isModified() || $this->aPOOrderState->isNew()) {
                    $affectedRows += $this->aPOOrderState->save($con);
                }
                $this->setPOOrderState($this->aPOOrderState);
            }

            if ($this->aPOPaymentState !== null) {
                if ($this->aPOPaymentState->isModified() || $this->aPOPaymentState->isNew()) {
                    $affectedRows += $this->aPOPaymentState->save($con);
                }
                $this->setPOPaymentState($this->aPOPaymentState);
            }

            if ($this->aPOPaymentType !== null) {
                if ($this->aPOPaymentType->isModified() || $this->aPOPaymentType->isNew()) {
                    $affectedRows += $this->aPOPaymentType->save($con);
                }
                $this->setPOPaymentType($this->aPOPaymentType);
            }

            if ($this->aPOSubscription !== null) {
                if ($this->aPOSubscription->isModified() || $this->aPOSubscription->isNew()) {
                    $affectedRows += $this->aPOSubscription->save($con);
                }
                $this->setPOSubscription($this->aPOSubscription);
            }

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

        $this->modifiedColumns[] = POEmailPeer::ID;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . POEmailPeer::ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(POEmailPeer::ID)) {
            $modifiedColumns[':p' . $index++]  = '`id`';
        }
        if ($this->isColumnModified(POEmailPeer::P_ORDER_ID)) {
            $modifiedColumns[':p' . $index++]  = '`p_order_id`';
        }
        if ($this->isColumnModified(POEmailPeer::P_O_ORDER_STATE_ID)) {
            $modifiedColumns[':p' . $index++]  = '`p_o_order_state_id`';
        }
        if ($this->isColumnModified(POEmailPeer::P_O_PAYMENT_STATE_ID)) {
            $modifiedColumns[':p' . $index++]  = '`p_o_payment_state_id`';
        }
        if ($this->isColumnModified(POEmailPeer::P_O_PAYMENT_TYPE_ID)) {
            $modifiedColumns[':p' . $index++]  = '`p_o_payment_type_id`';
        }
        if ($this->isColumnModified(POEmailPeer::P_O_SUBSCRIPTION_ID)) {
            $modifiedColumns[':p' . $index++]  = '`p_o_subscription_id`';
        }
        if ($this->isColumnModified(POEmailPeer::SEND)) {
            $modifiedColumns[':p' . $index++]  = '`send`';
        }
        if ($this->isColumnModified(POEmailPeer::SUBJECT)) {
            $modifiedColumns[':p' . $index++]  = '`subject`';
        }
        if ($this->isColumnModified(POEmailPeer::HTML_BODY)) {
            $modifiedColumns[':p' . $index++]  = '`html_body`';
        }
        if ($this->isColumnModified(POEmailPeer::TXT_BODY)) {
            $modifiedColumns[':p' . $index++]  = '`txt_body`';
        }
        if ($this->isColumnModified(POEmailPeer::CREATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`created_at`';
        }
        if ($this->isColumnModified(POEmailPeer::UPDATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`updated_at`';
        }

        $sql = sprintf(
            'INSERT INTO `p_o_email` (%s) VALUES (%s)',
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
                    case '`p_order_id`':
                        $stmt->bindValue($identifier, $this->p_order_id, PDO::PARAM_INT);
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
                    case '`send`':
                        $stmt->bindValue($identifier, $this->send, PDO::PARAM_STR);
                        break;
                    case '`subject`':
                        $stmt->bindValue($identifier, $this->subject, PDO::PARAM_STR);
                        break;
                    case '`html_body`':
                        $stmt->bindValue($identifier, $this->html_body, PDO::PARAM_STR);
                        break;
                    case '`txt_body`':
                        $stmt->bindValue($identifier, $this->txt_body, PDO::PARAM_STR);
                        break;
                    case '`created_at`':
                        $stmt->bindValue($identifier, $this->created_at, PDO::PARAM_STR);
                        break;
                    case '`updated_at`':
                        $stmt->bindValue($identifier, $this->updated_at, PDO::PARAM_STR);
                        break;
                }
            }
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute INSERT statement [%s]', $sql), $e);
        }

        try {
            $pk = $con->lastInsertId();
        } catch (Exception $e) {
            throw new PropelException('Unable to get autoincrement id.', $e);
        }
        $this->setId($pk);

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
        $pos = POEmailPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                return $this->getPOrderId();
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
                return $this->getSend();
                break;
            case 7:
                return $this->getSubject();
                break;
            case 8:
                return $this->getHtmlBody();
                break;
            case 9:
                return $this->getTxtBody();
                break;
            case 10:
                return $this->getCreatedAt();
                break;
            case 11:
                return $this->getUpdatedAt();
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
     * @param     boolean $includeForeignObjects (optional) Whether to include hydrated related objects. Default to FALSE.
     *
     * @return array an associative array containing the field names (as keys) and field values
     */
    public function toArray($keyType = BasePeer::TYPE_PHPNAME, $includeLazyLoadColumns = true, $alreadyDumpedObjects = array(), $includeForeignObjects = false)
    {
        if (isset($alreadyDumpedObjects['POEmail'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['POEmail'][$this->getPrimaryKey()] = true;
        $keys = POEmailPeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getPOrderId(),
            $keys[2] => $this->getPOOrderStateId(),
            $keys[3] => $this->getPOPaymentStateId(),
            $keys[4] => $this->getPOPaymentTypeId(),
            $keys[5] => $this->getPOSubscriptionId(),
            $keys[6] => $this->getSend(),
            $keys[7] => $this->getSubject(),
            $keys[8] => $this->getHtmlBody(),
            $keys[9] => $this->getTxtBody(),
            $keys[10] => $this->getCreatedAt(),
            $keys[11] => $this->getUpdatedAt(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aPOrder) {
                $result['POrder'] = $this->aPOrder->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aPOOrderState) {
                $result['POOrderState'] = $this->aPOOrderState->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aPOPaymentState) {
                $result['POPaymentState'] = $this->aPOPaymentState->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aPOPaymentType) {
                $result['POPaymentType'] = $this->aPOPaymentType->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aPOSubscription) {
                $result['POSubscription'] = $this->aPOSubscription->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
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
        $pos = POEmailPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);

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
                $this->setPOrderId($value);
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
                $this->setSend($value);
                break;
            case 7:
                $this->setSubject($value);
                break;
            case 8:
                $this->setHtmlBody($value);
                break;
            case 9:
                $this->setTxtBody($value);
                break;
            case 10:
                $this->setCreatedAt($value);
                break;
            case 11:
                $this->setUpdatedAt($value);
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
        $keys = POEmailPeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setPOrderId($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setPOOrderStateId($arr[$keys[2]]);
        if (array_key_exists($keys[3], $arr)) $this->setPOPaymentStateId($arr[$keys[3]]);
        if (array_key_exists($keys[4], $arr)) $this->setPOPaymentTypeId($arr[$keys[4]]);
        if (array_key_exists($keys[5], $arr)) $this->setPOSubscriptionId($arr[$keys[5]]);
        if (array_key_exists($keys[6], $arr)) $this->setSend($arr[$keys[6]]);
        if (array_key_exists($keys[7], $arr)) $this->setSubject($arr[$keys[7]]);
        if (array_key_exists($keys[8], $arr)) $this->setHtmlBody($arr[$keys[8]]);
        if (array_key_exists($keys[9], $arr)) $this->setTxtBody($arr[$keys[9]]);
        if (array_key_exists($keys[10], $arr)) $this->setCreatedAt($arr[$keys[10]]);
        if (array_key_exists($keys[11], $arr)) $this->setUpdatedAt($arr[$keys[11]]);
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(POEmailPeer::DATABASE_NAME);

        if ($this->isColumnModified(POEmailPeer::ID)) $criteria->add(POEmailPeer::ID, $this->id);
        if ($this->isColumnModified(POEmailPeer::P_ORDER_ID)) $criteria->add(POEmailPeer::P_ORDER_ID, $this->p_order_id);
        if ($this->isColumnModified(POEmailPeer::P_O_ORDER_STATE_ID)) $criteria->add(POEmailPeer::P_O_ORDER_STATE_ID, $this->p_o_order_state_id);
        if ($this->isColumnModified(POEmailPeer::P_O_PAYMENT_STATE_ID)) $criteria->add(POEmailPeer::P_O_PAYMENT_STATE_ID, $this->p_o_payment_state_id);
        if ($this->isColumnModified(POEmailPeer::P_O_PAYMENT_TYPE_ID)) $criteria->add(POEmailPeer::P_O_PAYMENT_TYPE_ID, $this->p_o_payment_type_id);
        if ($this->isColumnModified(POEmailPeer::P_O_SUBSCRIPTION_ID)) $criteria->add(POEmailPeer::P_O_SUBSCRIPTION_ID, $this->p_o_subscription_id);
        if ($this->isColumnModified(POEmailPeer::SEND)) $criteria->add(POEmailPeer::SEND, $this->send);
        if ($this->isColumnModified(POEmailPeer::SUBJECT)) $criteria->add(POEmailPeer::SUBJECT, $this->subject);
        if ($this->isColumnModified(POEmailPeer::HTML_BODY)) $criteria->add(POEmailPeer::HTML_BODY, $this->html_body);
        if ($this->isColumnModified(POEmailPeer::TXT_BODY)) $criteria->add(POEmailPeer::TXT_BODY, $this->txt_body);
        if ($this->isColumnModified(POEmailPeer::CREATED_AT)) $criteria->add(POEmailPeer::CREATED_AT, $this->created_at);
        if ($this->isColumnModified(POEmailPeer::UPDATED_AT)) $criteria->add(POEmailPeer::UPDATED_AT, $this->updated_at);

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
        $criteria = new Criteria(POEmailPeer::DATABASE_NAME);
        $criteria->add(POEmailPeer::ID, $this->id);

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
     * @param object $copyObj An object of POEmail (or compatible) type.
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setPOrderId($this->getPOrderId());
        $copyObj->setPOOrderStateId($this->getPOOrderStateId());
        $copyObj->setPOPaymentStateId($this->getPOPaymentStateId());
        $copyObj->setPOPaymentTypeId($this->getPOPaymentTypeId());
        $copyObj->setPOSubscriptionId($this->getPOSubscriptionId());
        $copyObj->setSend($this->getSend());
        $copyObj->setSubject($this->getSubject());
        $copyObj->setHtmlBody($this->getHtmlBody());
        $copyObj->setTxtBody($this->getTxtBody());
        $copyObj->setCreatedAt($this->getCreatedAt());
        $copyObj->setUpdatedAt($this->getUpdatedAt());

        if ($deepCopy && !$this->startCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);
            // store object hash to prevent cycle
            $this->startCopy = true;

            //unflag object copy
            $this->startCopy = false;
        } // if ($deepCopy)

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
     * @return POEmail Clone of current object.
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
     * @return POEmailPeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new POEmailPeer();
        }

        return self::$peer;
    }

    /**
     * Declares an association between this object and a POrder object.
     *
     * @param                  POrder $v
     * @return POEmail The current object (for fluent API support)
     * @throws PropelException
     */
    public function setPOrder(POrder $v = null)
    {
        if ($v === null) {
            $this->setPOrderId(NULL);
        } else {
            $this->setPOrderId($v->getId());
        }

        $this->aPOrder = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the POrder object, it will not be re-added.
        if ($v !== null) {
            $v->addPOEmail($this);
        }


        return $this;
    }


    /**
     * Get the associated POrder object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return POrder The associated POrder object.
     * @throws PropelException
     */
    public function getPOrder(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aPOrder === null && ($this->p_order_id !== null) && $doQuery) {
            $this->aPOrder = POrderQuery::create()->findPk($this->p_order_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aPOrder->addPOEmails($this);
             */
        }

        return $this->aPOrder;
    }

    /**
     * Declares an association between this object and a POOrderState object.
     *
     * @param                  POOrderState $v
     * @return POEmail The current object (for fluent API support)
     * @throws PropelException
     */
    public function setPOOrderState(POOrderState $v = null)
    {
        if ($v === null) {
            $this->setPOOrderStateId(NULL);
        } else {
            $this->setPOOrderStateId($v->getId());
        }

        $this->aPOOrderState = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the POOrderState object, it will not be re-added.
        if ($v !== null) {
            $v->addPOEmail($this);
        }


        return $this;
    }


    /**
     * Get the associated POOrderState object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return POOrderState The associated POOrderState object.
     * @throws PropelException
     */
    public function getPOOrderState(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aPOOrderState === null && ($this->p_o_order_state_id !== null) && $doQuery) {
            $this->aPOOrderState = POOrderStateQuery::create()->findPk($this->p_o_order_state_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aPOOrderState->addPOEmails($this);
             */
        }

        return $this->aPOOrderState;
    }

    /**
     * Declares an association between this object and a POPaymentState object.
     *
     * @param                  POPaymentState $v
     * @return POEmail The current object (for fluent API support)
     * @throws PropelException
     */
    public function setPOPaymentState(POPaymentState $v = null)
    {
        if ($v === null) {
            $this->setPOPaymentStateId(NULL);
        } else {
            $this->setPOPaymentStateId($v->getId());
        }

        $this->aPOPaymentState = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the POPaymentState object, it will not be re-added.
        if ($v !== null) {
            $v->addPOEmail($this);
        }


        return $this;
    }


    /**
     * Get the associated POPaymentState object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return POPaymentState The associated POPaymentState object.
     * @throws PropelException
     */
    public function getPOPaymentState(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aPOPaymentState === null && ($this->p_o_payment_state_id !== null) && $doQuery) {
            $this->aPOPaymentState = POPaymentStateQuery::create()->findPk($this->p_o_payment_state_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aPOPaymentState->addPOEmails($this);
             */
        }

        return $this->aPOPaymentState;
    }

    /**
     * Declares an association between this object and a POPaymentType object.
     *
     * @param                  POPaymentType $v
     * @return POEmail The current object (for fluent API support)
     * @throws PropelException
     */
    public function setPOPaymentType(POPaymentType $v = null)
    {
        if ($v === null) {
            $this->setPOPaymentTypeId(NULL);
        } else {
            $this->setPOPaymentTypeId($v->getId());
        }

        $this->aPOPaymentType = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the POPaymentType object, it will not be re-added.
        if ($v !== null) {
            $v->addPOEmail($this);
        }


        return $this;
    }


    /**
     * Get the associated POPaymentType object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return POPaymentType The associated POPaymentType object.
     * @throws PropelException
     */
    public function getPOPaymentType(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aPOPaymentType === null && ($this->p_o_payment_type_id !== null) && $doQuery) {
            $this->aPOPaymentType = POPaymentTypeQuery::create()->findPk($this->p_o_payment_type_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aPOPaymentType->addPOEmails($this);
             */
        }

        return $this->aPOPaymentType;
    }

    /**
     * Declares an association between this object and a POSubscription object.
     *
     * @param                  POSubscription $v
     * @return POEmail The current object (for fluent API support)
     * @throws PropelException
     */
    public function setPOSubscription(POSubscription $v = null)
    {
        if ($v === null) {
            $this->setPOSubscriptionId(NULL);
        } else {
            $this->setPOSubscriptionId($v->getId());
        }

        $this->aPOSubscription = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the POSubscription object, it will not be re-added.
        if ($v !== null) {
            $v->addPOEmail($this);
        }


        return $this;
    }


    /**
     * Get the associated POSubscription object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return POSubscription The associated POSubscription object.
     * @throws PropelException
     */
    public function getPOSubscription(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aPOSubscription === null && ($this->p_o_subscription_id !== null) && $doQuery) {
            $this->aPOSubscription = POSubscriptionQuery::create()->findPk($this->p_o_subscription_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aPOSubscription->addPOEmails($this);
             */
        }

        return $this->aPOSubscription;
    }

    /**
     * Clears the current object and sets all attributes to their default values
     */
    public function clear()
    {
        $this->id = null;
        $this->p_order_id = null;
        $this->p_o_order_state_id = null;
        $this->p_o_payment_state_id = null;
        $this->p_o_payment_type_id = null;
        $this->p_o_subscription_id = null;
        $this->send = null;
        $this->subject = null;
        $this->html_body = null;
        $this->txt_body = null;
        $this->created_at = null;
        $this->updated_at = null;
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
            if ($this->aPOrder instanceof Persistent) {
              $this->aPOrder->clearAllReferences($deep);
            }
            if ($this->aPOOrderState instanceof Persistent) {
              $this->aPOOrderState->clearAllReferences($deep);
            }
            if ($this->aPOPaymentState instanceof Persistent) {
              $this->aPOPaymentState->clearAllReferences($deep);
            }
            if ($this->aPOPaymentType instanceof Persistent) {
              $this->aPOPaymentType->clearAllReferences($deep);
            }
            if ($this->aPOSubscription instanceof Persistent) {
              $this->aPOSubscription->clearAllReferences($deep);
            }

            $this->alreadyInClearAllReferencesDeep = false;
        } // if ($deep)

        $this->aPOrder = null;
        $this->aPOOrderState = null;
        $this->aPOPaymentState = null;
        $this->aPOPaymentType = null;
        $this->aPOSubscription = null;
    }

    /**
     * return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(POEmailPeer::DEFAULT_STRING_FORMAT);
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

    // timestampable behavior

    /**
     * Mark the current object so that the update date doesn't get updated during next save
     *
     * @return     POEmail The current object (for fluent API support)
     */
    public function keepUpdateDateUnchanged()
    {
        $this->modifiedColumns[] = POEmailPeer::UPDATED_AT;

        return $this;
    }

}
