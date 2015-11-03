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
use \PropelCollection;
use \PropelDateTime;
use \PropelException;
use \PropelObjectCollection;
use \PropelPDO;
use Glorpen\Propel\PropelBundle\Dispatcher\EventDispatcherProxy;
use Glorpen\Propel\PropelBundle\Events\ModelEvent;
use Politizr\Model\PNType;
use Politizr\Model\PNTypeQuery;
use Politizr\Model\PNotification;
use Politizr\Model\PNotificationPeer;
use Politizr\Model\PNotificationQuery;
use Politizr\Model\PUNotification;
use Politizr\Model\PUNotificationQuery;
use Politizr\Model\PUSubscribeEmail;
use Politizr\Model\PUSubscribeEmailQuery;
use Politizr\Model\PUSubscribeScreen;
use Politizr\Model\PUSubscribeScreenQuery;
use Politizr\Model\PUser;
use Politizr\Model\PUserQuery;

abstract class BasePNotification extends BaseObject implements Persistent
{
    /**
     * Peer class name
     */
    const PEER = 'Politizr\\Model\\PNotificationPeer';

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        PNotificationPeer
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
     * The value for the p_n_type_id field.
     * @var        int
     */
    protected $p_n_type_id;

    /**
     * The value for the title field.
     * @var        string
     */
    protected $title;

    /**
     * The value for the description field.
     * @var        string
     */
    protected $description;

    /**
     * The value for the online field.
     * @var        boolean
     */
    protected $online;

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
     * @var        PNType
     */
    protected $aPNType;

    /**
     * @var        PropelObjectCollection|PUNotification[] Collection to store aggregation of PUNotification objects.
     */
    protected $collPUNotificationPNotifications;
    protected $collPUNotificationPNotificationsPartial;

    /**
     * @var        PropelObjectCollection|PUSubscribeEmail[] Collection to store aggregation of PUSubscribeEmail objects.
     */
    protected $collPUSubscribeEmailPNotifications;
    protected $collPUSubscribeEmailPNotificationsPartial;

    /**
     * @var        PropelObjectCollection|PUSubscribeScreen[] Collection to store aggregation of PUSubscribeScreen objects.
     */
    protected $collPUSubscribeScreenPNotifications;
    protected $collPUSubscribeScreenPNotificationsPartial;

    /**
     * @var        PropelObjectCollection|PUser[] Collection to store aggregation of PUser objects.
     */
    protected $collPUNotificationPUsers;

    /**
     * @var        PropelObjectCollection|PUser[] Collection to store aggregation of PUser objects.
     */
    protected $collPUSubscribeEmailPUsers;

    /**
     * @var        PropelObjectCollection|PUser[] Collection to store aggregation of PUser objects.
     */
    protected $collPUSubscribeScreenPUsers;

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
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $pUNotificationPUsersScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $pUSubscribeEmailPUsersScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $pUSubscribeScreenPUsersScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $pUNotificationPNotificationsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $pUSubscribeEmailPNotificationsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $pUSubscribeScreenPNotificationsScheduledForDeletion = null;

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
     * Get the [p_n_type_id] column value.
     *
     * @return int
     */
    public function getPNTypeId()
    {

        return $this->p_n_type_id;
    }

    /**
     * Get the [title] column value.
     *
     * @return string
     */
    public function getTitle()
    {

        return $this->title;
    }

    /**
     * Get the [description] column value.
     *
     * @return string
     */
    public function getDescription()
    {

        return $this->description;
    }

    /**
     * Get the [online] column value.
     *
     * @return boolean
     */
    public function getOnline()
    {

        return $this->online;
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
     * @return PNotification The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[] = PNotificationPeer::ID;
        }


        return $this;
    } // setId()

    /**
     * Set the value of [p_n_type_id] column.
     *
     * @param  int $v new value
     * @return PNotification The current object (for fluent API support)
     */
    public function setPNTypeId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->p_n_type_id !== $v) {
            $this->p_n_type_id = $v;
            $this->modifiedColumns[] = PNotificationPeer::P_N_TYPE_ID;
        }

        if ($this->aPNType !== null && $this->aPNType->getId() !== $v) {
            $this->aPNType = null;
        }


        return $this;
    } // setPNTypeId()

    /**
     * Set the value of [title] column.
     *
     * @param  string $v new value
     * @return PNotification The current object (for fluent API support)
     */
    public function setTitle($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->title !== $v) {
            $this->title = $v;
            $this->modifiedColumns[] = PNotificationPeer::TITLE;
        }


        return $this;
    } // setTitle()

    /**
     * Set the value of [description] column.
     *
     * @param  string $v new value
     * @return PNotification The current object (for fluent API support)
     */
    public function setDescription($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->description !== $v) {
            $this->description = $v;
            $this->modifiedColumns[] = PNotificationPeer::DESCRIPTION;
        }


        return $this;
    } // setDescription()

    /**
     * Sets the value of the [online] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param boolean|integer|string $v The new value
     * @return PNotification The current object (for fluent API support)
     */
    public function setOnline($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->online !== $v) {
            $this->online = $v;
            $this->modifiedColumns[] = PNotificationPeer::ONLINE;
        }


        return $this;
    } // setOnline()

    /**
     * Sets the value of [created_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return PNotification The current object (for fluent API support)
     */
    public function setCreatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->created_at !== null || $dt !== null) {
            $currentDateAsString = ($this->created_at !== null && $tmpDt = new DateTime($this->created_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->created_at = $newDateAsString;
                $this->modifiedColumns[] = PNotificationPeer::CREATED_AT;
            }
        } // if either are not null


        return $this;
    } // setCreatedAt()

    /**
     * Sets the value of [updated_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return PNotification The current object (for fluent API support)
     */
    public function setUpdatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->updated_at !== null || $dt !== null) {
            $currentDateAsString = ($this->updated_at !== null && $tmpDt = new DateTime($this->updated_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->updated_at = $newDateAsString;
                $this->modifiedColumns[] = PNotificationPeer::UPDATED_AT;
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
            $this->p_n_type_id = ($row[$startcol + 1] !== null) ? (int) $row[$startcol + 1] : null;
            $this->title = ($row[$startcol + 2] !== null) ? (string) $row[$startcol + 2] : null;
            $this->description = ($row[$startcol + 3] !== null) ? (string) $row[$startcol + 3] : null;
            $this->online = ($row[$startcol + 4] !== null) ? (boolean) $row[$startcol + 4] : null;
            $this->created_at = ($row[$startcol + 5] !== null) ? (string) $row[$startcol + 5] : null;
            $this->updated_at = ($row[$startcol + 6] !== null) ? (string) $row[$startcol + 6] : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }
            $this->postHydrate($row, $startcol, $rehydrate);

            return $startcol + 7; // 7 = PNotificationPeer::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating PNotification object", $e);
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

        if ($this->aPNType !== null && $this->p_n_type_id !== $this->aPNType->getId()) {
            $this->aPNType = null;
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
            $con = Propel::getConnection(PNotificationPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $stmt = PNotificationPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
        $row = $stmt->fetch(PDO::FETCH_NUM);
        $stmt->closeCursor();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aPNType = null;
            $this->collPUNotificationPNotifications = null;

            $this->collPUSubscribeEmailPNotifications = null;

            $this->collPUSubscribeScreenPNotifications = null;

            $this->collPUNotificationPUsers = null;
            $this->collPUSubscribeEmailPUsers = null;
            $this->collPUSubscribeScreenPUsers = null;
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
            $con = Propel::getConnection(PNotificationPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        try {
            EventDispatcherProxy::trigger(array('delete.pre','model.delete.pre'), new ModelEvent($this));
            $deleteQuery = PNotificationQuery::create()
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
            $con = Propel::getConnection(PNotificationPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        $isInsert = $this->isNew();
        try {
            $ret = $this->preSave($con);
            // event behavior
            EventDispatcherProxy::trigger('model.save.pre', new ModelEvent($this));
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
                // timestampable behavior
                if (!$this->isColumnModified(PNotificationPeer::CREATED_AT)) {
                    $this->setCreatedAt(time());
                }
                if (!$this->isColumnModified(PNotificationPeer::UPDATED_AT)) {
                    $this->setUpdatedAt(time());
                }
                // event behavior
                EventDispatcherProxy::trigger('model.insert.pre', new ModelEvent($this));
            } else {
                $ret = $ret && $this->preUpdate($con);
                // timestampable behavior
                if ($this->isModified() && !$this->isColumnModified(PNotificationPeer::UPDATED_AT)) {
                    $this->setUpdatedAt(time());
                }
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
                PNotificationPeer::addInstanceToPool($this);
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

            if ($this->aPNType !== null) {
                if ($this->aPNType->isModified() || $this->aPNType->isNew()) {
                    $affectedRows += $this->aPNType->save($con);
                }
                $this->setPNType($this->aPNType);
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

            if ($this->pUNotificationPUsersScheduledForDeletion !== null) {
                if (!$this->pUNotificationPUsersScheduledForDeletion->isEmpty()) {
                    $pks = array();
                    $pk = $this->getPrimaryKey();
                    foreach ($this->pUNotificationPUsersScheduledForDeletion->getPrimaryKeys(false) as $remotePk) {
                        $pks[] = array($remotePk, $pk);
                    }
                    PUNotificationQuery::create()
                        ->filterByPrimaryKeys($pks)
                        ->delete($con);
                    $this->pUNotificationPUsersScheduledForDeletion = null;
                }

                foreach ($this->getPUNotificationPUsers() as $pUNotificationPUser) {
                    if ($pUNotificationPUser->isModified()) {
                        $pUNotificationPUser->save($con);
                    }
                }
            } elseif ($this->collPUNotificationPUsers) {
                foreach ($this->collPUNotificationPUsers as $pUNotificationPUser) {
                    if ($pUNotificationPUser->isModified()) {
                        $pUNotificationPUser->save($con);
                    }
                }
            }

            if ($this->pUSubscribeEmailPUsersScheduledForDeletion !== null) {
                if (!$this->pUSubscribeEmailPUsersScheduledForDeletion->isEmpty()) {
                    $pks = array();
                    $pk = $this->getPrimaryKey();
                    foreach ($this->pUSubscribeEmailPUsersScheduledForDeletion->getPrimaryKeys(false) as $remotePk) {
                        $pks[] = array($remotePk, $pk);
                    }
                    PUSubscribeEmailQuery::create()
                        ->filterByPrimaryKeys($pks)
                        ->delete($con);
                    $this->pUSubscribeEmailPUsersScheduledForDeletion = null;
                }

                foreach ($this->getPUSubscribeEmailPUsers() as $pUSubscribeEmailPUser) {
                    if ($pUSubscribeEmailPUser->isModified()) {
                        $pUSubscribeEmailPUser->save($con);
                    }
                }
            } elseif ($this->collPUSubscribeEmailPUsers) {
                foreach ($this->collPUSubscribeEmailPUsers as $pUSubscribeEmailPUser) {
                    if ($pUSubscribeEmailPUser->isModified()) {
                        $pUSubscribeEmailPUser->save($con);
                    }
                }
            }

            if ($this->pUSubscribeScreenPUsersScheduledForDeletion !== null) {
                if (!$this->pUSubscribeScreenPUsersScheduledForDeletion->isEmpty()) {
                    $pks = array();
                    $pk = $this->getPrimaryKey();
                    foreach ($this->pUSubscribeScreenPUsersScheduledForDeletion->getPrimaryKeys(false) as $remotePk) {
                        $pks[] = array($remotePk, $pk);
                    }
                    PUSubscribeScreenQuery::create()
                        ->filterByPrimaryKeys($pks)
                        ->delete($con);
                    $this->pUSubscribeScreenPUsersScheduledForDeletion = null;
                }

                foreach ($this->getPUSubscribeScreenPUsers() as $pUSubscribeScreenPUser) {
                    if ($pUSubscribeScreenPUser->isModified()) {
                        $pUSubscribeScreenPUser->save($con);
                    }
                }
            } elseif ($this->collPUSubscribeScreenPUsers) {
                foreach ($this->collPUSubscribeScreenPUsers as $pUSubscribeScreenPUser) {
                    if ($pUSubscribeScreenPUser->isModified()) {
                        $pUSubscribeScreenPUser->save($con);
                    }
                }
            }

            if ($this->pUNotificationPNotificationsScheduledForDeletion !== null) {
                if (!$this->pUNotificationPNotificationsScheduledForDeletion->isEmpty()) {
                    PUNotificationQuery::create()
                        ->filterByPrimaryKeys($this->pUNotificationPNotificationsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->pUNotificationPNotificationsScheduledForDeletion = null;
                }
            }

            if ($this->collPUNotificationPNotifications !== null) {
                foreach ($this->collPUNotificationPNotifications as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->pUSubscribeEmailPNotificationsScheduledForDeletion !== null) {
                if (!$this->pUSubscribeEmailPNotificationsScheduledForDeletion->isEmpty()) {
                    PUSubscribeEmailQuery::create()
                        ->filterByPrimaryKeys($this->pUSubscribeEmailPNotificationsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->pUSubscribeEmailPNotificationsScheduledForDeletion = null;
                }
            }

            if ($this->collPUSubscribeEmailPNotifications !== null) {
                foreach ($this->collPUSubscribeEmailPNotifications as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->pUSubscribeScreenPNotificationsScheduledForDeletion !== null) {
                if (!$this->pUSubscribeScreenPNotificationsScheduledForDeletion->isEmpty()) {
                    PUSubscribeScreenQuery::create()
                        ->filterByPrimaryKeys($this->pUSubscribeScreenPNotificationsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->pUSubscribeScreenPNotificationsScheduledForDeletion = null;
                }
            }

            if ($this->collPUSubscribeScreenPNotifications !== null) {
                foreach ($this->collPUSubscribeScreenPNotifications as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
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

        $this->modifiedColumns[] = PNotificationPeer::ID;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . PNotificationPeer::ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(PNotificationPeer::ID)) {
            $modifiedColumns[':p' . $index++]  = '`id`';
        }
        if ($this->isColumnModified(PNotificationPeer::P_N_TYPE_ID)) {
            $modifiedColumns[':p' . $index++]  = '`p_n_type_id`';
        }
        if ($this->isColumnModified(PNotificationPeer::TITLE)) {
            $modifiedColumns[':p' . $index++]  = '`title`';
        }
        if ($this->isColumnModified(PNotificationPeer::DESCRIPTION)) {
            $modifiedColumns[':p' . $index++]  = '`description`';
        }
        if ($this->isColumnModified(PNotificationPeer::ONLINE)) {
            $modifiedColumns[':p' . $index++]  = '`online`';
        }
        if ($this->isColumnModified(PNotificationPeer::CREATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`created_at`';
        }
        if ($this->isColumnModified(PNotificationPeer::UPDATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`updated_at`';
        }

        $sql = sprintf(
            'INSERT INTO `p_notification` (%s) VALUES (%s)',
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
                    case '`p_n_type_id`':
                        $stmt->bindValue($identifier, $this->p_n_type_id, PDO::PARAM_INT);
                        break;
                    case '`title`':
                        $stmt->bindValue($identifier, $this->title, PDO::PARAM_STR);
                        break;
                    case '`description`':
                        $stmt->bindValue($identifier, $this->description, PDO::PARAM_STR);
                        break;
                    case '`online`':
                        $stmt->bindValue($identifier, (int) $this->online, PDO::PARAM_INT);
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


            // We call the validate method on the following object(s) if they
            // were passed to this object by their corresponding set
            // method.  This object relates to these object(s) by a
            // foreign key reference.

            if ($this->aPNType !== null) {
                if (!$this->aPNType->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aPNType->getValidationFailures());
                }
            }


            if (($retval = PNotificationPeer::doValidate($this, $columns)) !== true) {
                $failureMap = array_merge($failureMap, $retval);
            }


                if ($this->collPUNotificationPNotifications !== null) {
                    foreach ($this->collPUNotificationPNotifications as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collPUSubscribeEmailPNotifications !== null) {
                    foreach ($this->collPUSubscribeEmailPNotifications as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collPUSubscribeScreenPNotifications !== null) {
                    foreach ($this->collPUSubscribeScreenPNotifications as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
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
        $pos = PNotificationPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                return $this->getPNTypeId();
                break;
            case 2:
                return $this->getTitle();
                break;
            case 3:
                return $this->getDescription();
                break;
            case 4:
                return $this->getOnline();
                break;
            case 5:
                return $this->getCreatedAt();
                break;
            case 6:
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
        if (isset($alreadyDumpedObjects['PNotification'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['PNotification'][$this->getPrimaryKey()] = true;
        $keys = PNotificationPeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getPNTypeId(),
            $keys[2] => $this->getTitle(),
            $keys[3] => $this->getDescription(),
            $keys[4] => $this->getOnline(),
            $keys[5] => $this->getCreatedAt(),
            $keys[6] => $this->getUpdatedAt(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aPNType) {
                $result['PNType'] = $this->aPNType->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->collPUNotificationPNotifications) {
                $result['PUNotificationPNotifications'] = $this->collPUNotificationPNotifications->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPUSubscribeEmailPNotifications) {
                $result['PUSubscribeEmailPNotifications'] = $this->collPUSubscribeEmailPNotifications->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPUSubscribeScreenPNotifications) {
                $result['PUSubscribeScreenPNotifications'] = $this->collPUSubscribeScreenPNotifications->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
        $pos = PNotificationPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);

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
                $this->setPNTypeId($value);
                break;
            case 2:
                $this->setTitle($value);
                break;
            case 3:
                $this->setDescription($value);
                break;
            case 4:
                $this->setOnline($value);
                break;
            case 5:
                $this->setCreatedAt($value);
                break;
            case 6:
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
        $keys = PNotificationPeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setPNTypeId($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setTitle($arr[$keys[2]]);
        if (array_key_exists($keys[3], $arr)) $this->setDescription($arr[$keys[3]]);
        if (array_key_exists($keys[4], $arr)) $this->setOnline($arr[$keys[4]]);
        if (array_key_exists($keys[5], $arr)) $this->setCreatedAt($arr[$keys[5]]);
        if (array_key_exists($keys[6], $arr)) $this->setUpdatedAt($arr[$keys[6]]);
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(PNotificationPeer::DATABASE_NAME);

        if ($this->isColumnModified(PNotificationPeer::ID)) $criteria->add(PNotificationPeer::ID, $this->id);
        if ($this->isColumnModified(PNotificationPeer::P_N_TYPE_ID)) $criteria->add(PNotificationPeer::P_N_TYPE_ID, $this->p_n_type_id);
        if ($this->isColumnModified(PNotificationPeer::TITLE)) $criteria->add(PNotificationPeer::TITLE, $this->title);
        if ($this->isColumnModified(PNotificationPeer::DESCRIPTION)) $criteria->add(PNotificationPeer::DESCRIPTION, $this->description);
        if ($this->isColumnModified(PNotificationPeer::ONLINE)) $criteria->add(PNotificationPeer::ONLINE, $this->online);
        if ($this->isColumnModified(PNotificationPeer::CREATED_AT)) $criteria->add(PNotificationPeer::CREATED_AT, $this->created_at);
        if ($this->isColumnModified(PNotificationPeer::UPDATED_AT)) $criteria->add(PNotificationPeer::UPDATED_AT, $this->updated_at);

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
        $criteria = new Criteria(PNotificationPeer::DATABASE_NAME);
        $criteria->add(PNotificationPeer::ID, $this->id);

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
     * @param object $copyObj An object of PNotification (or compatible) type.
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setPNTypeId($this->getPNTypeId());
        $copyObj->setTitle($this->getTitle());
        $copyObj->setDescription($this->getDescription());
        $copyObj->setOnline($this->getOnline());
        $copyObj->setCreatedAt($this->getCreatedAt());
        $copyObj->setUpdatedAt($this->getUpdatedAt());

        if ($deepCopy && !$this->startCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);
            // store object hash to prevent cycle
            $this->startCopy = true;

            foreach ($this->getPUNotificationPNotifications() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPUNotificationPNotification($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPUSubscribeEmailPNotifications() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPUSubscribeEmailPNotification($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPUSubscribeScreenPNotifications() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPUSubscribeScreenPNotification($relObj->copy($deepCopy));
                }
            }

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
     * @return PNotification Clone of current object.
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
     * @return PNotificationPeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new PNotificationPeer();
        }

        return self::$peer;
    }

    /**
     * Declares an association between this object and a PNType object.
     *
     * @param                  PNType $v
     * @return PNotification The current object (for fluent API support)
     * @throws PropelException
     */
    public function setPNType(PNType $v = null)
    {
        if ($v === null) {
            $this->setPNTypeId(NULL);
        } else {
            $this->setPNTypeId($v->getId());
        }

        $this->aPNType = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the PNType object, it will not be re-added.
        if ($v !== null) {
            $v->addPNotification($this);
        }


        return $this;
    }


    /**
     * Get the associated PNType object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return PNType The associated PNType object.
     * @throws PropelException
     */
    public function getPNType(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aPNType === null && ($this->p_n_type_id !== null) && $doQuery) {
            $this->aPNType = PNTypeQuery::create()->findPk($this->p_n_type_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aPNType->addPNotifications($this);
             */
        }

        return $this->aPNType;
    }


    /**
     * Initializes a collection based on the name of a relation.
     * Avoids crafting an 'init[$relationName]s' method name
     * that wouldn't work when StandardEnglishPluralizer is used.
     *
     * @param string $relationName The name of the relation to initialize
     * @return void
     */
    public function initRelation($relationName)
    {
        if ('PUNotificationPNotification' == $relationName) {
            $this->initPUNotificationPNotifications();
        }
        if ('PUSubscribeEmailPNotification' == $relationName) {
            $this->initPUSubscribeEmailPNotifications();
        }
        if ('PUSubscribeScreenPNotification' == $relationName) {
            $this->initPUSubscribeScreenPNotifications();
        }
    }

    /**
     * Clears out the collPUNotificationPNotifications collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return PNotification The current object (for fluent API support)
     * @see        addPUNotificationPNotifications()
     */
    public function clearPUNotificationPNotifications()
    {
        $this->collPUNotificationPNotifications = null; // important to set this to null since that means it is uninitialized
        $this->collPUNotificationPNotificationsPartial = null;

        return $this;
    }

    /**
     * reset is the collPUNotificationPNotifications collection loaded partially
     *
     * @return void
     */
    public function resetPartialPUNotificationPNotifications($v = true)
    {
        $this->collPUNotificationPNotificationsPartial = $v;
    }

    /**
     * Initializes the collPUNotificationPNotifications collection.
     *
     * By default this just sets the collPUNotificationPNotifications collection to an empty array (like clearcollPUNotificationPNotifications());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPUNotificationPNotifications($overrideExisting = true)
    {
        if (null !== $this->collPUNotificationPNotifications && !$overrideExisting) {
            return;
        }
        $this->collPUNotificationPNotifications = new PropelObjectCollection();
        $this->collPUNotificationPNotifications->setModel('PUNotification');
    }

    /**
     * Gets an array of PUNotification objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this PNotification is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|PUNotification[] List of PUNotification objects
     * @throws PropelException
     */
    public function getPUNotificationPNotifications($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collPUNotificationPNotificationsPartial && !$this->isNew();
        if (null === $this->collPUNotificationPNotifications || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPUNotificationPNotifications) {
                // return empty collection
                $this->initPUNotificationPNotifications();
            } else {
                $collPUNotificationPNotifications = PUNotificationQuery::create(null, $criteria)
                    ->filterByPUNotificationPNotification($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collPUNotificationPNotificationsPartial && count($collPUNotificationPNotifications)) {
                      $this->initPUNotificationPNotifications(false);

                      foreach ($collPUNotificationPNotifications as $obj) {
                        if (false == $this->collPUNotificationPNotifications->contains($obj)) {
                          $this->collPUNotificationPNotifications->append($obj);
                        }
                      }

                      $this->collPUNotificationPNotificationsPartial = true;
                    }

                    $collPUNotificationPNotifications->getInternalIterator()->rewind();

                    return $collPUNotificationPNotifications;
                }

                if ($partial && $this->collPUNotificationPNotifications) {
                    foreach ($this->collPUNotificationPNotifications as $obj) {
                        if ($obj->isNew()) {
                            $collPUNotificationPNotifications[] = $obj;
                        }
                    }
                }

                $this->collPUNotificationPNotifications = $collPUNotificationPNotifications;
                $this->collPUNotificationPNotificationsPartial = false;
            }
        }

        return $this->collPUNotificationPNotifications;
    }

    /**
     * Sets a collection of PUNotificationPNotification objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $pUNotificationPNotifications A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return PNotification The current object (for fluent API support)
     */
    public function setPUNotificationPNotifications(PropelCollection $pUNotificationPNotifications, PropelPDO $con = null)
    {
        $pUNotificationPNotificationsToDelete = $this->getPUNotificationPNotifications(new Criteria(), $con)->diff($pUNotificationPNotifications);


        $this->pUNotificationPNotificationsScheduledForDeletion = $pUNotificationPNotificationsToDelete;

        foreach ($pUNotificationPNotificationsToDelete as $pUNotificationPNotificationRemoved) {
            $pUNotificationPNotificationRemoved->setPUNotificationPNotification(null);
        }

        $this->collPUNotificationPNotifications = null;
        foreach ($pUNotificationPNotifications as $pUNotificationPNotification) {
            $this->addPUNotificationPNotification($pUNotificationPNotification);
        }

        $this->collPUNotificationPNotifications = $pUNotificationPNotifications;
        $this->collPUNotificationPNotificationsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related PUNotification objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related PUNotification objects.
     * @throws PropelException
     */
    public function countPUNotificationPNotifications(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collPUNotificationPNotificationsPartial && !$this->isNew();
        if (null === $this->collPUNotificationPNotifications || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPUNotificationPNotifications) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPUNotificationPNotifications());
            }
            $query = PUNotificationQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPUNotificationPNotification($this)
                ->count($con);
        }

        return count($this->collPUNotificationPNotifications);
    }

    /**
     * Method called to associate a PUNotification object to this object
     * through the PUNotification foreign key attribute.
     *
     * @param    PUNotification $l PUNotification
     * @return PNotification The current object (for fluent API support)
     */
    public function addPUNotificationPNotification(PUNotification $l)
    {
        if ($this->collPUNotificationPNotifications === null) {
            $this->initPUNotificationPNotifications();
            $this->collPUNotificationPNotificationsPartial = true;
        }

        if (!in_array($l, $this->collPUNotificationPNotifications->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddPUNotificationPNotification($l);

            if ($this->pUNotificationPNotificationsScheduledForDeletion and $this->pUNotificationPNotificationsScheduledForDeletion->contains($l)) {
                $this->pUNotificationPNotificationsScheduledForDeletion->remove($this->pUNotificationPNotificationsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	PUNotificationPNotification $pUNotificationPNotification The pUNotificationPNotification object to add.
     */
    protected function doAddPUNotificationPNotification($pUNotificationPNotification)
    {
        $this->collPUNotificationPNotifications[]= $pUNotificationPNotification;
        $pUNotificationPNotification->setPUNotificationPNotification($this);
    }

    /**
     * @param	PUNotificationPNotification $pUNotificationPNotification The pUNotificationPNotification object to remove.
     * @return PNotification The current object (for fluent API support)
     */
    public function removePUNotificationPNotification($pUNotificationPNotification)
    {
        if ($this->getPUNotificationPNotifications()->contains($pUNotificationPNotification)) {
            $this->collPUNotificationPNotifications->remove($this->collPUNotificationPNotifications->search($pUNotificationPNotification));
            if (null === $this->pUNotificationPNotificationsScheduledForDeletion) {
                $this->pUNotificationPNotificationsScheduledForDeletion = clone $this->collPUNotificationPNotifications;
                $this->pUNotificationPNotificationsScheduledForDeletion->clear();
            }
            $this->pUNotificationPNotificationsScheduledForDeletion[]= clone $pUNotificationPNotification;
            $pUNotificationPNotification->setPUNotificationPNotification(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this PNotification is new, it will return
     * an empty collection; or if this PNotification has previously
     * been saved, it will retrieve related PUNotificationPNotifications from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in PNotification.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|PUNotification[] List of PUNotification objects
     */
    public function getPUNotificationPNotificationsJoinPUNotificationPUser($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PUNotificationQuery::create(null, $criteria);
        $query->joinWith('PUNotificationPUser', $join_behavior);

        return $this->getPUNotificationPNotifications($query, $con);
    }

    /**
     * Clears out the collPUSubscribeEmailPNotifications collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return PNotification The current object (for fluent API support)
     * @see        addPUSubscribeEmailPNotifications()
     */
    public function clearPUSubscribeEmailPNotifications()
    {
        $this->collPUSubscribeEmailPNotifications = null; // important to set this to null since that means it is uninitialized
        $this->collPUSubscribeEmailPNotificationsPartial = null;

        return $this;
    }

    /**
     * reset is the collPUSubscribeEmailPNotifications collection loaded partially
     *
     * @return void
     */
    public function resetPartialPUSubscribeEmailPNotifications($v = true)
    {
        $this->collPUSubscribeEmailPNotificationsPartial = $v;
    }

    /**
     * Initializes the collPUSubscribeEmailPNotifications collection.
     *
     * By default this just sets the collPUSubscribeEmailPNotifications collection to an empty array (like clearcollPUSubscribeEmailPNotifications());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPUSubscribeEmailPNotifications($overrideExisting = true)
    {
        if (null !== $this->collPUSubscribeEmailPNotifications && !$overrideExisting) {
            return;
        }
        $this->collPUSubscribeEmailPNotifications = new PropelObjectCollection();
        $this->collPUSubscribeEmailPNotifications->setModel('PUSubscribeEmail');
    }

    /**
     * Gets an array of PUSubscribeEmail objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this PNotification is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|PUSubscribeEmail[] List of PUSubscribeEmail objects
     * @throws PropelException
     */
    public function getPUSubscribeEmailPNotifications($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collPUSubscribeEmailPNotificationsPartial && !$this->isNew();
        if (null === $this->collPUSubscribeEmailPNotifications || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPUSubscribeEmailPNotifications) {
                // return empty collection
                $this->initPUSubscribeEmailPNotifications();
            } else {
                $collPUSubscribeEmailPNotifications = PUSubscribeEmailQuery::create(null, $criteria)
                    ->filterByPUSubscribeEmailPNotification($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collPUSubscribeEmailPNotificationsPartial && count($collPUSubscribeEmailPNotifications)) {
                      $this->initPUSubscribeEmailPNotifications(false);

                      foreach ($collPUSubscribeEmailPNotifications as $obj) {
                        if (false == $this->collPUSubscribeEmailPNotifications->contains($obj)) {
                          $this->collPUSubscribeEmailPNotifications->append($obj);
                        }
                      }

                      $this->collPUSubscribeEmailPNotificationsPartial = true;
                    }

                    $collPUSubscribeEmailPNotifications->getInternalIterator()->rewind();

                    return $collPUSubscribeEmailPNotifications;
                }

                if ($partial && $this->collPUSubscribeEmailPNotifications) {
                    foreach ($this->collPUSubscribeEmailPNotifications as $obj) {
                        if ($obj->isNew()) {
                            $collPUSubscribeEmailPNotifications[] = $obj;
                        }
                    }
                }

                $this->collPUSubscribeEmailPNotifications = $collPUSubscribeEmailPNotifications;
                $this->collPUSubscribeEmailPNotificationsPartial = false;
            }
        }

        return $this->collPUSubscribeEmailPNotifications;
    }

    /**
     * Sets a collection of PUSubscribeEmailPNotification objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $pUSubscribeEmailPNotifications A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return PNotification The current object (for fluent API support)
     */
    public function setPUSubscribeEmailPNotifications(PropelCollection $pUSubscribeEmailPNotifications, PropelPDO $con = null)
    {
        $pUSubscribeEmailPNotificationsToDelete = $this->getPUSubscribeEmailPNotifications(new Criteria(), $con)->diff($pUSubscribeEmailPNotifications);


        $this->pUSubscribeEmailPNotificationsScheduledForDeletion = $pUSubscribeEmailPNotificationsToDelete;

        foreach ($pUSubscribeEmailPNotificationsToDelete as $pUSubscribeEmailPNotificationRemoved) {
            $pUSubscribeEmailPNotificationRemoved->setPUSubscribeEmailPNotification(null);
        }

        $this->collPUSubscribeEmailPNotifications = null;
        foreach ($pUSubscribeEmailPNotifications as $pUSubscribeEmailPNotification) {
            $this->addPUSubscribeEmailPNotification($pUSubscribeEmailPNotification);
        }

        $this->collPUSubscribeEmailPNotifications = $pUSubscribeEmailPNotifications;
        $this->collPUSubscribeEmailPNotificationsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related PUSubscribeEmail objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related PUSubscribeEmail objects.
     * @throws PropelException
     */
    public function countPUSubscribeEmailPNotifications(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collPUSubscribeEmailPNotificationsPartial && !$this->isNew();
        if (null === $this->collPUSubscribeEmailPNotifications || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPUSubscribeEmailPNotifications) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPUSubscribeEmailPNotifications());
            }
            $query = PUSubscribeEmailQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPUSubscribeEmailPNotification($this)
                ->count($con);
        }

        return count($this->collPUSubscribeEmailPNotifications);
    }

    /**
     * Method called to associate a PUSubscribeEmail object to this object
     * through the PUSubscribeEmail foreign key attribute.
     *
     * @param    PUSubscribeEmail $l PUSubscribeEmail
     * @return PNotification The current object (for fluent API support)
     */
    public function addPUSubscribeEmailPNotification(PUSubscribeEmail $l)
    {
        if ($this->collPUSubscribeEmailPNotifications === null) {
            $this->initPUSubscribeEmailPNotifications();
            $this->collPUSubscribeEmailPNotificationsPartial = true;
        }

        if (!in_array($l, $this->collPUSubscribeEmailPNotifications->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddPUSubscribeEmailPNotification($l);

            if ($this->pUSubscribeEmailPNotificationsScheduledForDeletion and $this->pUSubscribeEmailPNotificationsScheduledForDeletion->contains($l)) {
                $this->pUSubscribeEmailPNotificationsScheduledForDeletion->remove($this->pUSubscribeEmailPNotificationsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	PUSubscribeEmailPNotification $pUSubscribeEmailPNotification The pUSubscribeEmailPNotification object to add.
     */
    protected function doAddPUSubscribeEmailPNotification($pUSubscribeEmailPNotification)
    {
        $this->collPUSubscribeEmailPNotifications[]= $pUSubscribeEmailPNotification;
        $pUSubscribeEmailPNotification->setPUSubscribeEmailPNotification($this);
    }

    /**
     * @param	PUSubscribeEmailPNotification $pUSubscribeEmailPNotification The pUSubscribeEmailPNotification object to remove.
     * @return PNotification The current object (for fluent API support)
     */
    public function removePUSubscribeEmailPNotification($pUSubscribeEmailPNotification)
    {
        if ($this->getPUSubscribeEmailPNotifications()->contains($pUSubscribeEmailPNotification)) {
            $this->collPUSubscribeEmailPNotifications->remove($this->collPUSubscribeEmailPNotifications->search($pUSubscribeEmailPNotification));
            if (null === $this->pUSubscribeEmailPNotificationsScheduledForDeletion) {
                $this->pUSubscribeEmailPNotificationsScheduledForDeletion = clone $this->collPUSubscribeEmailPNotifications;
                $this->pUSubscribeEmailPNotificationsScheduledForDeletion->clear();
            }
            $this->pUSubscribeEmailPNotificationsScheduledForDeletion[]= clone $pUSubscribeEmailPNotification;
            $pUSubscribeEmailPNotification->setPUSubscribeEmailPNotification(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this PNotification is new, it will return
     * an empty collection; or if this PNotification has previously
     * been saved, it will retrieve related PUSubscribeEmailPNotifications from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in PNotification.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|PUSubscribeEmail[] List of PUSubscribeEmail objects
     */
    public function getPUSubscribeEmailPNotificationsJoinPUSubscribeEmailPUser($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PUSubscribeEmailQuery::create(null, $criteria);
        $query->joinWith('PUSubscribeEmailPUser', $join_behavior);

        return $this->getPUSubscribeEmailPNotifications($query, $con);
    }

    /**
     * Clears out the collPUSubscribeScreenPNotifications collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return PNotification The current object (for fluent API support)
     * @see        addPUSubscribeScreenPNotifications()
     */
    public function clearPUSubscribeScreenPNotifications()
    {
        $this->collPUSubscribeScreenPNotifications = null; // important to set this to null since that means it is uninitialized
        $this->collPUSubscribeScreenPNotificationsPartial = null;

        return $this;
    }

    /**
     * reset is the collPUSubscribeScreenPNotifications collection loaded partially
     *
     * @return void
     */
    public function resetPartialPUSubscribeScreenPNotifications($v = true)
    {
        $this->collPUSubscribeScreenPNotificationsPartial = $v;
    }

    /**
     * Initializes the collPUSubscribeScreenPNotifications collection.
     *
     * By default this just sets the collPUSubscribeScreenPNotifications collection to an empty array (like clearcollPUSubscribeScreenPNotifications());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPUSubscribeScreenPNotifications($overrideExisting = true)
    {
        if (null !== $this->collPUSubscribeScreenPNotifications && !$overrideExisting) {
            return;
        }
        $this->collPUSubscribeScreenPNotifications = new PropelObjectCollection();
        $this->collPUSubscribeScreenPNotifications->setModel('PUSubscribeScreen');
    }

    /**
     * Gets an array of PUSubscribeScreen objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this PNotification is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|PUSubscribeScreen[] List of PUSubscribeScreen objects
     * @throws PropelException
     */
    public function getPUSubscribeScreenPNotifications($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collPUSubscribeScreenPNotificationsPartial && !$this->isNew();
        if (null === $this->collPUSubscribeScreenPNotifications || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPUSubscribeScreenPNotifications) {
                // return empty collection
                $this->initPUSubscribeScreenPNotifications();
            } else {
                $collPUSubscribeScreenPNotifications = PUSubscribeScreenQuery::create(null, $criteria)
                    ->filterByPUSubscribeScreenPNotification($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collPUSubscribeScreenPNotificationsPartial && count($collPUSubscribeScreenPNotifications)) {
                      $this->initPUSubscribeScreenPNotifications(false);

                      foreach ($collPUSubscribeScreenPNotifications as $obj) {
                        if (false == $this->collPUSubscribeScreenPNotifications->contains($obj)) {
                          $this->collPUSubscribeScreenPNotifications->append($obj);
                        }
                      }

                      $this->collPUSubscribeScreenPNotificationsPartial = true;
                    }

                    $collPUSubscribeScreenPNotifications->getInternalIterator()->rewind();

                    return $collPUSubscribeScreenPNotifications;
                }

                if ($partial && $this->collPUSubscribeScreenPNotifications) {
                    foreach ($this->collPUSubscribeScreenPNotifications as $obj) {
                        if ($obj->isNew()) {
                            $collPUSubscribeScreenPNotifications[] = $obj;
                        }
                    }
                }

                $this->collPUSubscribeScreenPNotifications = $collPUSubscribeScreenPNotifications;
                $this->collPUSubscribeScreenPNotificationsPartial = false;
            }
        }

        return $this->collPUSubscribeScreenPNotifications;
    }

    /**
     * Sets a collection of PUSubscribeScreenPNotification objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $pUSubscribeScreenPNotifications A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return PNotification The current object (for fluent API support)
     */
    public function setPUSubscribeScreenPNotifications(PropelCollection $pUSubscribeScreenPNotifications, PropelPDO $con = null)
    {
        $pUSubscribeScreenPNotificationsToDelete = $this->getPUSubscribeScreenPNotifications(new Criteria(), $con)->diff($pUSubscribeScreenPNotifications);


        $this->pUSubscribeScreenPNotificationsScheduledForDeletion = $pUSubscribeScreenPNotificationsToDelete;

        foreach ($pUSubscribeScreenPNotificationsToDelete as $pUSubscribeScreenPNotificationRemoved) {
            $pUSubscribeScreenPNotificationRemoved->setPUSubscribeScreenPNotification(null);
        }

        $this->collPUSubscribeScreenPNotifications = null;
        foreach ($pUSubscribeScreenPNotifications as $pUSubscribeScreenPNotification) {
            $this->addPUSubscribeScreenPNotification($pUSubscribeScreenPNotification);
        }

        $this->collPUSubscribeScreenPNotifications = $pUSubscribeScreenPNotifications;
        $this->collPUSubscribeScreenPNotificationsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related PUSubscribeScreen objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related PUSubscribeScreen objects.
     * @throws PropelException
     */
    public function countPUSubscribeScreenPNotifications(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collPUSubscribeScreenPNotificationsPartial && !$this->isNew();
        if (null === $this->collPUSubscribeScreenPNotifications || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPUSubscribeScreenPNotifications) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPUSubscribeScreenPNotifications());
            }
            $query = PUSubscribeScreenQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPUSubscribeScreenPNotification($this)
                ->count($con);
        }

        return count($this->collPUSubscribeScreenPNotifications);
    }

    /**
     * Method called to associate a PUSubscribeScreen object to this object
     * through the PUSubscribeScreen foreign key attribute.
     *
     * @param    PUSubscribeScreen $l PUSubscribeScreen
     * @return PNotification The current object (for fluent API support)
     */
    public function addPUSubscribeScreenPNotification(PUSubscribeScreen $l)
    {
        if ($this->collPUSubscribeScreenPNotifications === null) {
            $this->initPUSubscribeScreenPNotifications();
            $this->collPUSubscribeScreenPNotificationsPartial = true;
        }

        if (!in_array($l, $this->collPUSubscribeScreenPNotifications->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddPUSubscribeScreenPNotification($l);

            if ($this->pUSubscribeScreenPNotificationsScheduledForDeletion and $this->pUSubscribeScreenPNotificationsScheduledForDeletion->contains($l)) {
                $this->pUSubscribeScreenPNotificationsScheduledForDeletion->remove($this->pUSubscribeScreenPNotificationsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	PUSubscribeScreenPNotification $pUSubscribeScreenPNotification The pUSubscribeScreenPNotification object to add.
     */
    protected function doAddPUSubscribeScreenPNotification($pUSubscribeScreenPNotification)
    {
        $this->collPUSubscribeScreenPNotifications[]= $pUSubscribeScreenPNotification;
        $pUSubscribeScreenPNotification->setPUSubscribeScreenPNotification($this);
    }

    /**
     * @param	PUSubscribeScreenPNotification $pUSubscribeScreenPNotification The pUSubscribeScreenPNotification object to remove.
     * @return PNotification The current object (for fluent API support)
     */
    public function removePUSubscribeScreenPNotification($pUSubscribeScreenPNotification)
    {
        if ($this->getPUSubscribeScreenPNotifications()->contains($pUSubscribeScreenPNotification)) {
            $this->collPUSubscribeScreenPNotifications->remove($this->collPUSubscribeScreenPNotifications->search($pUSubscribeScreenPNotification));
            if (null === $this->pUSubscribeScreenPNotificationsScheduledForDeletion) {
                $this->pUSubscribeScreenPNotificationsScheduledForDeletion = clone $this->collPUSubscribeScreenPNotifications;
                $this->pUSubscribeScreenPNotificationsScheduledForDeletion->clear();
            }
            $this->pUSubscribeScreenPNotificationsScheduledForDeletion[]= clone $pUSubscribeScreenPNotification;
            $pUSubscribeScreenPNotification->setPUSubscribeScreenPNotification(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this PNotification is new, it will return
     * an empty collection; or if this PNotification has previously
     * been saved, it will retrieve related PUSubscribeScreenPNotifications from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in PNotification.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|PUSubscribeScreen[] List of PUSubscribeScreen objects
     */
    public function getPUSubscribeScreenPNotificationsJoinPUSubscribeScreenPUser($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PUSubscribeScreenQuery::create(null, $criteria);
        $query->joinWith('PUSubscribeScreenPUser', $join_behavior);

        return $this->getPUSubscribeScreenPNotifications($query, $con);
    }

    /**
     * Clears out the collPUNotificationPUsers collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return PNotification The current object (for fluent API support)
     * @see        addPUNotificationPUsers()
     */
    public function clearPUNotificationPUsers()
    {
        $this->collPUNotificationPUsers = null; // important to set this to null since that means it is uninitialized
        $this->collPUNotificationPUsersPartial = null;

        return $this;
    }

    /**
     * Initializes the collPUNotificationPUsers collection.
     *
     * By default this just sets the collPUNotificationPUsers collection to an empty collection (like clearPUNotificationPUsers());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @return void
     */
    public function initPUNotificationPUsers()
    {
        $this->collPUNotificationPUsers = new PropelObjectCollection();
        $this->collPUNotificationPUsers->setModel('PUser');
    }

    /**
     * Gets a collection of PUser objects related by a many-to-many relationship
     * to the current object by way of the p_u_notification cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this PNotification is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria Optional query object to filter the query
     * @param PropelPDO $con Optional connection object
     *
     * @return PropelObjectCollection|PUser[] List of PUser objects
     */
    public function getPUNotificationPUsers($criteria = null, PropelPDO $con = null)
    {
        if (null === $this->collPUNotificationPUsers || null !== $criteria) {
            if ($this->isNew() && null === $this->collPUNotificationPUsers) {
                // return empty collection
                $this->initPUNotificationPUsers();
            } else {
                $collPUNotificationPUsers = PUserQuery::create(null, $criteria)
                    ->filterByPUNotificationPNotification($this)
                    ->find($con);
                if (null !== $criteria) {
                    return $collPUNotificationPUsers;
                }
                $this->collPUNotificationPUsers = $collPUNotificationPUsers;
            }
        }

        return $this->collPUNotificationPUsers;
    }

    /**
     * Sets a collection of PUser objects related by a many-to-many relationship
     * to the current object by way of the p_u_notification cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $pUNotificationPUsers A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return PNotification The current object (for fluent API support)
     */
    public function setPUNotificationPUsers(PropelCollection $pUNotificationPUsers, PropelPDO $con = null)
    {
        $this->clearPUNotificationPUsers();
        $currentPUNotificationPUsers = $this->getPUNotificationPUsers(null, $con);

        $this->pUNotificationPUsersScheduledForDeletion = $currentPUNotificationPUsers->diff($pUNotificationPUsers);

        foreach ($pUNotificationPUsers as $pUNotificationPUser) {
            if (!$currentPUNotificationPUsers->contains($pUNotificationPUser)) {
                $this->doAddPUNotificationPUser($pUNotificationPUser);
            }
        }

        $this->collPUNotificationPUsers = $pUNotificationPUsers;

        return $this;
    }

    /**
     * Gets the number of PUser objects related by a many-to-many relationship
     * to the current object by way of the p_u_notification cross-reference table.
     *
     * @param Criteria $criteria Optional query object to filter the query
     * @param boolean $distinct Set to true to force count distinct
     * @param PropelPDO $con Optional connection object
     *
     * @return int the number of related PUser objects
     */
    public function countPUNotificationPUsers($criteria = null, $distinct = false, PropelPDO $con = null)
    {
        if (null === $this->collPUNotificationPUsers || null !== $criteria) {
            if ($this->isNew() && null === $this->collPUNotificationPUsers) {
                return 0;
            } else {
                $query = PUserQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByPUNotificationPNotification($this)
                    ->count($con);
            }
        } else {
            return count($this->collPUNotificationPUsers);
        }
    }

    /**
     * Associate a PUser object to this object
     * through the p_u_notification cross reference table.
     *
     * @param  PUser $pUser The PUNotification object to relate
     * @return PNotification The current object (for fluent API support)
     */
    public function addPUNotificationPUser(PUser $pUser)
    {
        if ($this->collPUNotificationPUsers === null) {
            $this->initPUNotificationPUsers();
        }

        if (!$this->collPUNotificationPUsers->contains($pUser)) { // only add it if the **same** object is not already associated
            $this->doAddPUNotificationPUser($pUser);
            $this->collPUNotificationPUsers[] = $pUser;

            if ($this->pUNotificationPUsersScheduledForDeletion and $this->pUNotificationPUsersScheduledForDeletion->contains($pUser)) {
                $this->pUNotificationPUsersScheduledForDeletion->remove($this->pUNotificationPUsersScheduledForDeletion->search($pUser));
            }
        }

        return $this;
    }

    /**
     * @param	PUNotificationPUser $pUNotificationPUser The pUNotificationPUser object to add.
     */
    protected function doAddPUNotificationPUser(PUser $pUNotificationPUser)
    {
        // set the back reference to this object directly as using provided method either results
        // in endless loop or in multiple relations
        if (!$pUNotificationPUser->getPUNotificationPNotifications()->contains($this)) { $pUNotification = new PUNotification();
            $pUNotification->setPUNotificationPUser($pUNotificationPUser);
            $this->addPUNotificationPNotification($pUNotification);

            $foreignCollection = $pUNotificationPUser->getPUNotificationPNotifications();
            $foreignCollection[] = $this;
        }
    }

    /**
     * Remove a PUser object to this object
     * through the p_u_notification cross reference table.
     *
     * @param PUser $pUser The PUNotification object to relate
     * @return PNotification The current object (for fluent API support)
     */
    public function removePUNotificationPUser(PUser $pUser)
    {
        if ($this->getPUNotificationPUsers()->contains($pUser)) {
            $this->collPUNotificationPUsers->remove($this->collPUNotificationPUsers->search($pUser));
            if (null === $this->pUNotificationPUsersScheduledForDeletion) {
                $this->pUNotificationPUsersScheduledForDeletion = clone $this->collPUNotificationPUsers;
                $this->pUNotificationPUsersScheduledForDeletion->clear();
            }
            $this->pUNotificationPUsersScheduledForDeletion[]= $pUser;
        }

        return $this;
    }

    /**
     * Clears out the collPUSubscribeEmailPUsers collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return PNotification The current object (for fluent API support)
     * @see        addPUSubscribeEmailPUsers()
     */
    public function clearPUSubscribeEmailPUsers()
    {
        $this->collPUSubscribeEmailPUsers = null; // important to set this to null since that means it is uninitialized
        $this->collPUSubscribeEmailPUsersPartial = null;

        return $this;
    }

    /**
     * Initializes the collPUSubscribeEmailPUsers collection.
     *
     * By default this just sets the collPUSubscribeEmailPUsers collection to an empty collection (like clearPUSubscribeEmailPUsers());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @return void
     */
    public function initPUSubscribeEmailPUsers()
    {
        $this->collPUSubscribeEmailPUsers = new PropelObjectCollection();
        $this->collPUSubscribeEmailPUsers->setModel('PUser');
    }

    /**
     * Gets a collection of PUser objects related by a many-to-many relationship
     * to the current object by way of the p_u_subscribe_email cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this PNotification is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria Optional query object to filter the query
     * @param PropelPDO $con Optional connection object
     *
     * @return PropelObjectCollection|PUser[] List of PUser objects
     */
    public function getPUSubscribeEmailPUsers($criteria = null, PropelPDO $con = null)
    {
        if (null === $this->collPUSubscribeEmailPUsers || null !== $criteria) {
            if ($this->isNew() && null === $this->collPUSubscribeEmailPUsers) {
                // return empty collection
                $this->initPUSubscribeEmailPUsers();
            } else {
                $collPUSubscribeEmailPUsers = PUserQuery::create(null, $criteria)
                    ->filterByPUSubscribeEmailPNotification($this)
                    ->find($con);
                if (null !== $criteria) {
                    return $collPUSubscribeEmailPUsers;
                }
                $this->collPUSubscribeEmailPUsers = $collPUSubscribeEmailPUsers;
            }
        }

        return $this->collPUSubscribeEmailPUsers;
    }

    /**
     * Sets a collection of PUser objects related by a many-to-many relationship
     * to the current object by way of the p_u_subscribe_email cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $pUSubscribeEmailPUsers A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return PNotification The current object (for fluent API support)
     */
    public function setPUSubscribeEmailPUsers(PropelCollection $pUSubscribeEmailPUsers, PropelPDO $con = null)
    {
        $this->clearPUSubscribeEmailPUsers();
        $currentPUSubscribeEmailPUsers = $this->getPUSubscribeEmailPUsers(null, $con);

        $this->pUSubscribeEmailPUsersScheduledForDeletion = $currentPUSubscribeEmailPUsers->diff($pUSubscribeEmailPUsers);

        foreach ($pUSubscribeEmailPUsers as $pUSubscribeEmailPUser) {
            if (!$currentPUSubscribeEmailPUsers->contains($pUSubscribeEmailPUser)) {
                $this->doAddPUSubscribeEmailPUser($pUSubscribeEmailPUser);
            }
        }

        $this->collPUSubscribeEmailPUsers = $pUSubscribeEmailPUsers;

        return $this;
    }

    /**
     * Gets the number of PUser objects related by a many-to-many relationship
     * to the current object by way of the p_u_subscribe_email cross-reference table.
     *
     * @param Criteria $criteria Optional query object to filter the query
     * @param boolean $distinct Set to true to force count distinct
     * @param PropelPDO $con Optional connection object
     *
     * @return int the number of related PUser objects
     */
    public function countPUSubscribeEmailPUsers($criteria = null, $distinct = false, PropelPDO $con = null)
    {
        if (null === $this->collPUSubscribeEmailPUsers || null !== $criteria) {
            if ($this->isNew() && null === $this->collPUSubscribeEmailPUsers) {
                return 0;
            } else {
                $query = PUserQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByPUSubscribeEmailPNotification($this)
                    ->count($con);
            }
        } else {
            return count($this->collPUSubscribeEmailPUsers);
        }
    }

    /**
     * Associate a PUser object to this object
     * through the p_u_subscribe_email cross reference table.
     *
     * @param  PUser $pUser The PUSubscribeEmail object to relate
     * @return PNotification The current object (for fluent API support)
     */
    public function addPUSubscribeEmailPUser(PUser $pUser)
    {
        if ($this->collPUSubscribeEmailPUsers === null) {
            $this->initPUSubscribeEmailPUsers();
        }

        if (!$this->collPUSubscribeEmailPUsers->contains($pUser)) { // only add it if the **same** object is not already associated
            $this->doAddPUSubscribeEmailPUser($pUser);
            $this->collPUSubscribeEmailPUsers[] = $pUser;

            if ($this->pUSubscribeEmailPUsersScheduledForDeletion and $this->pUSubscribeEmailPUsersScheduledForDeletion->contains($pUser)) {
                $this->pUSubscribeEmailPUsersScheduledForDeletion->remove($this->pUSubscribeEmailPUsersScheduledForDeletion->search($pUser));
            }
        }

        return $this;
    }

    /**
     * @param	PUSubscribeEmailPUser $pUSubscribeEmailPUser The pUSubscribeEmailPUser object to add.
     */
    protected function doAddPUSubscribeEmailPUser(PUser $pUSubscribeEmailPUser)
    {
        // set the back reference to this object directly as using provided method either results
        // in endless loop or in multiple relations
        if (!$pUSubscribeEmailPUser->getPUSubscribeEmailPNotifications()->contains($this)) { $pUSubscribeEmail = new PUSubscribeEmail();
            $pUSubscribeEmail->setPUSubscribeEmailPUser($pUSubscribeEmailPUser);
            $this->addPUSubscribeEmailPNotification($pUSubscribeEmail);

            $foreignCollection = $pUSubscribeEmailPUser->getPUSubscribeEmailPNotifications();
            $foreignCollection[] = $this;
        }
    }

    /**
     * Remove a PUser object to this object
     * through the p_u_subscribe_email cross reference table.
     *
     * @param PUser $pUser The PUSubscribeEmail object to relate
     * @return PNotification The current object (for fluent API support)
     */
    public function removePUSubscribeEmailPUser(PUser $pUser)
    {
        if ($this->getPUSubscribeEmailPUsers()->contains($pUser)) {
            $this->collPUSubscribeEmailPUsers->remove($this->collPUSubscribeEmailPUsers->search($pUser));
            if (null === $this->pUSubscribeEmailPUsersScheduledForDeletion) {
                $this->pUSubscribeEmailPUsersScheduledForDeletion = clone $this->collPUSubscribeEmailPUsers;
                $this->pUSubscribeEmailPUsersScheduledForDeletion->clear();
            }
            $this->pUSubscribeEmailPUsersScheduledForDeletion[]= $pUser;
        }

        return $this;
    }

    /**
     * Clears out the collPUSubscribeScreenPUsers collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return PNotification The current object (for fluent API support)
     * @see        addPUSubscribeScreenPUsers()
     */
    public function clearPUSubscribeScreenPUsers()
    {
        $this->collPUSubscribeScreenPUsers = null; // important to set this to null since that means it is uninitialized
        $this->collPUSubscribeScreenPUsersPartial = null;

        return $this;
    }

    /**
     * Initializes the collPUSubscribeScreenPUsers collection.
     *
     * By default this just sets the collPUSubscribeScreenPUsers collection to an empty collection (like clearPUSubscribeScreenPUsers());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @return void
     */
    public function initPUSubscribeScreenPUsers()
    {
        $this->collPUSubscribeScreenPUsers = new PropelObjectCollection();
        $this->collPUSubscribeScreenPUsers->setModel('PUser');
    }

    /**
     * Gets a collection of PUser objects related by a many-to-many relationship
     * to the current object by way of the p_u_subscribe_screen cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this PNotification is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria Optional query object to filter the query
     * @param PropelPDO $con Optional connection object
     *
     * @return PropelObjectCollection|PUser[] List of PUser objects
     */
    public function getPUSubscribeScreenPUsers($criteria = null, PropelPDO $con = null)
    {
        if (null === $this->collPUSubscribeScreenPUsers || null !== $criteria) {
            if ($this->isNew() && null === $this->collPUSubscribeScreenPUsers) {
                // return empty collection
                $this->initPUSubscribeScreenPUsers();
            } else {
                $collPUSubscribeScreenPUsers = PUserQuery::create(null, $criteria)
                    ->filterByPUSubscribeScreenPNotification($this)
                    ->find($con);
                if (null !== $criteria) {
                    return $collPUSubscribeScreenPUsers;
                }
                $this->collPUSubscribeScreenPUsers = $collPUSubscribeScreenPUsers;
            }
        }

        return $this->collPUSubscribeScreenPUsers;
    }

    /**
     * Sets a collection of PUser objects related by a many-to-many relationship
     * to the current object by way of the p_u_subscribe_screen cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $pUSubscribeScreenPUsers A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return PNotification The current object (for fluent API support)
     */
    public function setPUSubscribeScreenPUsers(PropelCollection $pUSubscribeScreenPUsers, PropelPDO $con = null)
    {
        $this->clearPUSubscribeScreenPUsers();
        $currentPUSubscribeScreenPUsers = $this->getPUSubscribeScreenPUsers(null, $con);

        $this->pUSubscribeScreenPUsersScheduledForDeletion = $currentPUSubscribeScreenPUsers->diff($pUSubscribeScreenPUsers);

        foreach ($pUSubscribeScreenPUsers as $pUSubscribeScreenPUser) {
            if (!$currentPUSubscribeScreenPUsers->contains($pUSubscribeScreenPUser)) {
                $this->doAddPUSubscribeScreenPUser($pUSubscribeScreenPUser);
            }
        }

        $this->collPUSubscribeScreenPUsers = $pUSubscribeScreenPUsers;

        return $this;
    }

    /**
     * Gets the number of PUser objects related by a many-to-many relationship
     * to the current object by way of the p_u_subscribe_screen cross-reference table.
     *
     * @param Criteria $criteria Optional query object to filter the query
     * @param boolean $distinct Set to true to force count distinct
     * @param PropelPDO $con Optional connection object
     *
     * @return int the number of related PUser objects
     */
    public function countPUSubscribeScreenPUsers($criteria = null, $distinct = false, PropelPDO $con = null)
    {
        if (null === $this->collPUSubscribeScreenPUsers || null !== $criteria) {
            if ($this->isNew() && null === $this->collPUSubscribeScreenPUsers) {
                return 0;
            } else {
                $query = PUserQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByPUSubscribeScreenPNotification($this)
                    ->count($con);
            }
        } else {
            return count($this->collPUSubscribeScreenPUsers);
        }
    }

    /**
     * Associate a PUser object to this object
     * through the p_u_subscribe_screen cross reference table.
     *
     * @param  PUser $pUser The PUSubscribeScreen object to relate
     * @return PNotification The current object (for fluent API support)
     */
    public function addPUSubscribeScreenPUser(PUser $pUser)
    {
        if ($this->collPUSubscribeScreenPUsers === null) {
            $this->initPUSubscribeScreenPUsers();
        }

        if (!$this->collPUSubscribeScreenPUsers->contains($pUser)) { // only add it if the **same** object is not already associated
            $this->doAddPUSubscribeScreenPUser($pUser);
            $this->collPUSubscribeScreenPUsers[] = $pUser;

            if ($this->pUSubscribeScreenPUsersScheduledForDeletion and $this->pUSubscribeScreenPUsersScheduledForDeletion->contains($pUser)) {
                $this->pUSubscribeScreenPUsersScheduledForDeletion->remove($this->pUSubscribeScreenPUsersScheduledForDeletion->search($pUser));
            }
        }

        return $this;
    }

    /**
     * @param	PUSubscribeScreenPUser $pUSubscribeScreenPUser The pUSubscribeScreenPUser object to add.
     */
    protected function doAddPUSubscribeScreenPUser(PUser $pUSubscribeScreenPUser)
    {
        // set the back reference to this object directly as using provided method either results
        // in endless loop or in multiple relations
        if (!$pUSubscribeScreenPUser->getPUSubscribeScreenPNotifications()->contains($this)) { $pUSubscribeScreen = new PUSubscribeScreen();
            $pUSubscribeScreen->setPUSubscribeScreenPUser($pUSubscribeScreenPUser);
            $this->addPUSubscribeScreenPNotification($pUSubscribeScreen);

            $foreignCollection = $pUSubscribeScreenPUser->getPUSubscribeScreenPNotifications();
            $foreignCollection[] = $this;
        }
    }

    /**
     * Remove a PUser object to this object
     * through the p_u_subscribe_screen cross reference table.
     *
     * @param PUser $pUser The PUSubscribeScreen object to relate
     * @return PNotification The current object (for fluent API support)
     */
    public function removePUSubscribeScreenPUser(PUser $pUser)
    {
        if ($this->getPUSubscribeScreenPUsers()->contains($pUser)) {
            $this->collPUSubscribeScreenPUsers->remove($this->collPUSubscribeScreenPUsers->search($pUser));
            if (null === $this->pUSubscribeScreenPUsersScheduledForDeletion) {
                $this->pUSubscribeScreenPUsersScheduledForDeletion = clone $this->collPUSubscribeScreenPUsers;
                $this->pUSubscribeScreenPUsersScheduledForDeletion->clear();
            }
            $this->pUSubscribeScreenPUsersScheduledForDeletion[]= $pUser;
        }

        return $this;
    }

    /**
     * Clears the current object and sets all attributes to their default values
     */
    public function clear()
    {
        $this->id = null;
        $this->p_n_type_id = null;
        $this->title = null;
        $this->description = null;
        $this->online = null;
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
            if ($this->collPUNotificationPNotifications) {
                foreach ($this->collPUNotificationPNotifications as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPUSubscribeEmailPNotifications) {
                foreach ($this->collPUSubscribeEmailPNotifications as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPUSubscribeScreenPNotifications) {
                foreach ($this->collPUSubscribeScreenPNotifications as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPUNotificationPUsers) {
                foreach ($this->collPUNotificationPUsers as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPUSubscribeEmailPUsers) {
                foreach ($this->collPUSubscribeEmailPUsers as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPUSubscribeScreenPUsers) {
                foreach ($this->collPUSubscribeScreenPUsers as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->aPNType instanceof Persistent) {
              $this->aPNType->clearAllReferences($deep);
            }

            $this->alreadyInClearAllReferencesDeep = false;
        } // if ($deep)

        if ($this->collPUNotificationPNotifications instanceof PropelCollection) {
            $this->collPUNotificationPNotifications->clearIterator();
        }
        $this->collPUNotificationPNotifications = null;
        if ($this->collPUSubscribeEmailPNotifications instanceof PropelCollection) {
            $this->collPUSubscribeEmailPNotifications->clearIterator();
        }
        $this->collPUSubscribeEmailPNotifications = null;
        if ($this->collPUSubscribeScreenPNotifications instanceof PropelCollection) {
            $this->collPUSubscribeScreenPNotifications->clearIterator();
        }
        $this->collPUSubscribeScreenPNotifications = null;
        if ($this->collPUNotificationPUsers instanceof PropelCollection) {
            $this->collPUNotificationPUsers->clearIterator();
        }
        $this->collPUNotificationPUsers = null;
        if ($this->collPUSubscribeEmailPUsers instanceof PropelCollection) {
            $this->collPUSubscribeEmailPUsers->clearIterator();
        }
        $this->collPUSubscribeEmailPUsers = null;
        if ($this->collPUSubscribeScreenPUsers instanceof PropelCollection) {
            $this->collPUSubscribeScreenPUsers->clearIterator();
        }
        $this->collPUSubscribeScreenPUsers = null;
        $this->aPNType = null;
    }

    /**
     * return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(PNotificationPeer::DEFAULT_STRING_FORMAT);
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
     * @return     PNotification The current object (for fluent API support)
     */
    public function keepUpdateDateUnchanged()
    {
        $this->modifiedColumns[] = PNotificationPeer::UPDATED_AT;

        return $this;
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
