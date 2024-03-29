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
use Politizr\Model\PNType;
use Politizr\Model\PNTypeQuery;
use Politizr\Model\PNotification;
use Politizr\Model\PNotificationPeer;
use Politizr\Model\PNotificationQuery;
use Politizr\Model\PUNotification;
use Politizr\Model\PUNotificationQuery;
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
     * The value for the uuid field.
     * @var        string
     */
    protected $uuid;

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
     * @var        PropelObjectCollection|PUser[] Collection to store aggregation of PUser objects.
     */
    protected $collPUNotificationPUsers;

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
    protected $pUNotificationPNotificationsScheduledForDeletion = null;

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
     * Get the [uuid] column value.
     *
     * @return string
     */
    public function getUuid()
    {

        return $this->uuid;
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
     * Set the value of [uuid] column.
     *
     * @param  string $v new value
     * @return PNotification The current object (for fluent API support)
     */
    public function setUuid($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->uuid !== $v) {
            $this->uuid = $v;
            $this->modifiedColumns[] = PNotificationPeer::UUID;
        }


        return $this;
    } // setUuid()

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
            $this->uuid = ($row[$startcol + 1] !== null) ? (string) $row[$startcol + 1] : null;
            $this->p_n_type_id = ($row[$startcol + 2] !== null) ? (int) $row[$startcol + 2] : null;
            $this->title = ($row[$startcol + 3] !== null) ? (string) $row[$startcol + 3] : null;
            $this->description = ($row[$startcol + 4] !== null) ? (string) $row[$startcol + 4] : null;
            $this->online = ($row[$startcol + 5] !== null) ? (boolean) $row[$startcol + 5] : null;
            $this->created_at = ($row[$startcol + 6] !== null) ? (string) $row[$startcol + 6] : null;
            $this->updated_at = ($row[$startcol + 7] !== null) ? (string) $row[$startcol + 7] : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }
            $this->postHydrate($row, $startcol, $rehydrate);

            return $startcol + 8; // 8 = PNotificationPeer::NUM_HYDRATE_COLUMNS.

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

            $this->collPUNotificationPUsers = null;
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
            $deleteQuery = PNotificationQuery::create()
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
            $con = Propel::getConnection(PNotificationPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        $isInsert = $this->isNew();
        try {
            $ret = $this->preSave($con);
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
                // timestampable behavior
                if (!$this->isColumnModified(PNotificationPeer::CREATED_AT)) {
                    $this->setCreatedAt(time());
                }
                if (!$this->isColumnModified(PNotificationPeer::UPDATED_AT)) {
                    $this->setUpdatedAt(time());
                }
            } else {
                $ret = $ret && $this->preUpdate($con);
                // timestampable behavior
                if ($this->isModified() && !$this->isColumnModified(PNotificationPeer::UPDATED_AT)) {
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
        if ($this->isColumnModified(PNotificationPeer::UUID)) {
            $modifiedColumns[':p' . $index++]  = '`uuid`';
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
                    case '`uuid`':
                        $stmt->bindValue($identifier, $this->uuid, PDO::PARAM_STR);
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
                return $this->getUuid();
                break;
            case 2:
                return $this->getPNTypeId();
                break;
            case 3:
                return $this->getTitle();
                break;
            case 4:
                return $this->getDescription();
                break;
            case 5:
                return $this->getOnline();
                break;
            case 6:
                return $this->getCreatedAt();
                break;
            case 7:
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
            $keys[1] => $this->getUuid(),
            $keys[2] => $this->getPNTypeId(),
            $keys[3] => $this->getTitle(),
            $keys[4] => $this->getDescription(),
            $keys[5] => $this->getOnline(),
            $keys[6] => $this->getCreatedAt(),
            $keys[7] => $this->getUpdatedAt(),
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
                $this->setUuid($value);
                break;
            case 2:
                $this->setPNTypeId($value);
                break;
            case 3:
                $this->setTitle($value);
                break;
            case 4:
                $this->setDescription($value);
                break;
            case 5:
                $this->setOnline($value);
                break;
            case 6:
                $this->setCreatedAt($value);
                break;
            case 7:
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
        if (array_key_exists($keys[1], $arr)) $this->setUuid($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setPNTypeId($arr[$keys[2]]);
        if (array_key_exists($keys[3], $arr)) $this->setTitle($arr[$keys[3]]);
        if (array_key_exists($keys[4], $arr)) $this->setDescription($arr[$keys[4]]);
        if (array_key_exists($keys[5], $arr)) $this->setOnline($arr[$keys[5]]);
        if (array_key_exists($keys[6], $arr)) $this->setCreatedAt($arr[$keys[6]]);
        if (array_key_exists($keys[7], $arr)) $this->setUpdatedAt($arr[$keys[7]]);
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
        if ($this->isColumnModified(PNotificationPeer::UUID)) $criteria->add(PNotificationPeer::UUID, $this->uuid);
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
        $copyObj->setUuid($this->getUuid());
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
     * Clears the current object and sets all attributes to their default values
     */
    public function clear()
    {
        $this->id = null;
        $this->uuid = null;
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
            if ($this->collPUNotificationPUsers) {
                foreach ($this->collPUNotificationPUsers as $o) {
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
        if ($this->collPUNotificationPUsers instanceof PropelCollection) {
            $this->collPUNotificationPUsers->clearIterator();
        }
        $this->collPUNotificationPUsers = null;
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

    // uuid behavior
    /**
    * Create UUID if is NULL Uuid*/
    public function preInsert(PropelPDO $con = NULL) {

        if(is_null($this->getUuid())) {
            $this->setUuid(\Ramsey\Uuid\Uuid::uuid4()->__toString());
        } else {
            $uuid = $this->getUuid();
            if(!\Ramsey\Uuid\Uuid::isValid($uuid)) {
                throw new \InvalidArgumentException('UUID: ' . $uuid . ' in not valid');
                return false;
            }
        }
        return true;
    }
    /**
    * If permanent UUID, throw exception p_notification.uuid*/
    public function preUpdate(PropelPDO $con = NULL) {
            $uuid = $this->getUuid();
        if(!is_null($uuid) && !\Ramsey\Uuid\Uuid::isValid($uuid)) {
            throw new \InvalidArgumentException("UUID: $uuid in not valid");
        }
            return true;
    }

}
