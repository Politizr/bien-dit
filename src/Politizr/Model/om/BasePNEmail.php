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
use Politizr\Model\PMEmailing;
use Politizr\Model\PMEmailingQuery;
use Politizr\Model\PNEmail;
use Politizr\Model\PNEmailPeer;
use Politizr\Model\PNEmailQuery;
use Politizr\Model\PUSubscribePNE;
use Politizr\Model\PUSubscribePNEQuery;
use Politizr\Model\PUser;
use Politizr\Model\PUserQuery;

abstract class BasePNEmail extends BaseObject implements Persistent
{
    /**
     * Peer class name
     */
    const PEER = 'Politizr\\Model\\PNEmailPeer';

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        PNEmailPeer
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
     * @var        PropelObjectCollection|PUSubscribePNE[] Collection to store aggregation of PUSubscribePNE objects.
     */
    protected $collPUSubscribePNEs;
    protected $collPUSubscribePNEsPartial;

    /**
     * @var        PropelObjectCollection|PMEmailing[] Collection to store aggregation of PMEmailing objects.
     */
    protected $collPMEmailings;
    protected $collPMEmailingsPartial;

    /**
     * @var        PropelObjectCollection|PUser[] Collection to store aggregation of PUser objects.
     */
    protected $collPUsers;

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
    protected $pUsersScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $pUSubscribePNEsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $pMEmailingsScheduledForDeletion = null;

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
     * @return PNEmail The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[] = PNEmailPeer::ID;
        }


        return $this;
    } // setId()

    /**
     * Set the value of [uuid] column.
     *
     * @param  string $v new value
     * @return PNEmail The current object (for fluent API support)
     */
    public function setUuid($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->uuid !== $v) {
            $this->uuid = $v;
            $this->modifiedColumns[] = PNEmailPeer::UUID;
        }


        return $this;
    } // setUuid()

    /**
     * Set the value of [title] column.
     *
     * @param  string $v new value
     * @return PNEmail The current object (for fluent API support)
     */
    public function setTitle($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->title !== $v) {
            $this->title = $v;
            $this->modifiedColumns[] = PNEmailPeer::TITLE;
        }


        return $this;
    } // setTitle()

    /**
     * Set the value of [description] column.
     *
     * @param  string $v new value
     * @return PNEmail The current object (for fluent API support)
     */
    public function setDescription($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->description !== $v) {
            $this->description = $v;
            $this->modifiedColumns[] = PNEmailPeer::DESCRIPTION;
        }


        return $this;
    } // setDescription()

    /**
     * Sets the value of [created_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return PNEmail The current object (for fluent API support)
     */
    public function setCreatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->created_at !== null || $dt !== null) {
            $currentDateAsString = ($this->created_at !== null && $tmpDt = new DateTime($this->created_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->created_at = $newDateAsString;
                $this->modifiedColumns[] = PNEmailPeer::CREATED_AT;
            }
        } // if either are not null


        return $this;
    } // setCreatedAt()

    /**
     * Sets the value of [updated_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return PNEmail The current object (for fluent API support)
     */
    public function setUpdatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->updated_at !== null || $dt !== null) {
            $currentDateAsString = ($this->updated_at !== null && $tmpDt = new DateTime($this->updated_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->updated_at = $newDateAsString;
                $this->modifiedColumns[] = PNEmailPeer::UPDATED_AT;
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
            $this->title = ($row[$startcol + 2] !== null) ? (string) $row[$startcol + 2] : null;
            $this->description = ($row[$startcol + 3] !== null) ? (string) $row[$startcol + 3] : null;
            $this->created_at = ($row[$startcol + 4] !== null) ? (string) $row[$startcol + 4] : null;
            $this->updated_at = ($row[$startcol + 5] !== null) ? (string) $row[$startcol + 5] : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }
            $this->postHydrate($row, $startcol, $rehydrate);

            return $startcol + 6; // 6 = PNEmailPeer::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating PNEmail object", $e);
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
            $con = Propel::getConnection(PNEmailPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $stmt = PNEmailPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
        $row = $stmt->fetch(PDO::FETCH_NUM);
        $stmt->closeCursor();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->collPUSubscribePNEs = null;

            $this->collPMEmailings = null;

            $this->collPUsers = null;
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
            $con = Propel::getConnection(PNEmailPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        try {
            $deleteQuery = PNEmailQuery::create()
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
            $con = Propel::getConnection(PNEmailPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        $isInsert = $this->isNew();
        try {
            $ret = $this->preSave($con);
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
                // timestampable behavior
                if (!$this->isColumnModified(PNEmailPeer::CREATED_AT)) {
                    $this->setCreatedAt(time());
                }
                if (!$this->isColumnModified(PNEmailPeer::UPDATED_AT)) {
                    $this->setUpdatedAt(time());
                }
            } else {
                $ret = $ret && $this->preUpdate($con);
                // timestampable behavior
                if ($this->isModified() && !$this->isColumnModified(PNEmailPeer::UPDATED_AT)) {
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
                PNEmailPeer::addInstanceToPool($this);
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

            if ($this->pUsersScheduledForDeletion !== null) {
                if (!$this->pUsersScheduledForDeletion->isEmpty()) {
                    $pks = array();
                    $pk = $this->getPrimaryKey();
                    foreach ($this->pUsersScheduledForDeletion->getPrimaryKeys(false) as $remotePk) {
                        $pks[] = array($remotePk, $pk);
                    }
                    PUSubscribePNEQuery::create()
                        ->filterByPrimaryKeys($pks)
                        ->delete($con);
                    $this->pUsersScheduledForDeletion = null;
                }

                foreach ($this->getPUsers() as $pUser) {
                    if ($pUser->isModified()) {
                        $pUser->save($con);
                    }
                }
            } elseif ($this->collPUsers) {
                foreach ($this->collPUsers as $pUser) {
                    if ($pUser->isModified()) {
                        $pUser->save($con);
                    }
                }
            }

            if ($this->pUSubscribePNEsScheduledForDeletion !== null) {
                if (!$this->pUSubscribePNEsScheduledForDeletion->isEmpty()) {
                    PUSubscribePNEQuery::create()
                        ->filterByPrimaryKeys($this->pUSubscribePNEsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->pUSubscribePNEsScheduledForDeletion = null;
                }
            }

            if ($this->collPUSubscribePNEs !== null) {
                foreach ($this->collPUSubscribePNEs as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->pMEmailingsScheduledForDeletion !== null) {
                if (!$this->pMEmailingsScheduledForDeletion->isEmpty()) {
                    foreach ($this->pMEmailingsScheduledForDeletion as $pMEmailing) {
                        // need to save related object because we set the relation to null
                        $pMEmailing->save($con);
                    }
                    $this->pMEmailingsScheduledForDeletion = null;
                }
            }

            if ($this->collPMEmailings !== null) {
                foreach ($this->collPMEmailings as $referrerFK) {
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

        $this->modifiedColumns[] = PNEmailPeer::ID;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . PNEmailPeer::ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(PNEmailPeer::ID)) {
            $modifiedColumns[':p' . $index++]  = '`id`';
        }
        if ($this->isColumnModified(PNEmailPeer::UUID)) {
            $modifiedColumns[':p' . $index++]  = '`uuid`';
        }
        if ($this->isColumnModified(PNEmailPeer::TITLE)) {
            $modifiedColumns[':p' . $index++]  = '`title`';
        }
        if ($this->isColumnModified(PNEmailPeer::DESCRIPTION)) {
            $modifiedColumns[':p' . $index++]  = '`description`';
        }
        if ($this->isColumnModified(PNEmailPeer::CREATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`created_at`';
        }
        if ($this->isColumnModified(PNEmailPeer::UPDATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`updated_at`';
        }

        $sql = sprintf(
            'INSERT INTO `p_n_email` (%s) VALUES (%s)',
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
                    case '`title`':
                        $stmt->bindValue($identifier, $this->title, PDO::PARAM_STR);
                        break;
                    case '`description`':
                        $stmt->bindValue($identifier, $this->description, PDO::PARAM_STR);
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
        $pos = PNEmailPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                return $this->getTitle();
                break;
            case 3:
                return $this->getDescription();
                break;
            case 4:
                return $this->getCreatedAt();
                break;
            case 5:
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
        if (isset($alreadyDumpedObjects['PNEmail'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['PNEmail'][$this->getPrimaryKey()] = true;
        $keys = PNEmailPeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getUuid(),
            $keys[2] => $this->getTitle(),
            $keys[3] => $this->getDescription(),
            $keys[4] => $this->getCreatedAt(),
            $keys[5] => $this->getUpdatedAt(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->collPUSubscribePNEs) {
                $result['PUSubscribePNEs'] = $this->collPUSubscribePNEs->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPMEmailings) {
                $result['PMEmailings'] = $this->collPMEmailings->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
        $pos = PNEmailPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);

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
                $this->setTitle($value);
                break;
            case 3:
                $this->setDescription($value);
                break;
            case 4:
                $this->setCreatedAt($value);
                break;
            case 5:
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
        $keys = PNEmailPeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setUuid($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setTitle($arr[$keys[2]]);
        if (array_key_exists($keys[3], $arr)) $this->setDescription($arr[$keys[3]]);
        if (array_key_exists($keys[4], $arr)) $this->setCreatedAt($arr[$keys[4]]);
        if (array_key_exists($keys[5], $arr)) $this->setUpdatedAt($arr[$keys[5]]);
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(PNEmailPeer::DATABASE_NAME);

        if ($this->isColumnModified(PNEmailPeer::ID)) $criteria->add(PNEmailPeer::ID, $this->id);
        if ($this->isColumnModified(PNEmailPeer::UUID)) $criteria->add(PNEmailPeer::UUID, $this->uuid);
        if ($this->isColumnModified(PNEmailPeer::TITLE)) $criteria->add(PNEmailPeer::TITLE, $this->title);
        if ($this->isColumnModified(PNEmailPeer::DESCRIPTION)) $criteria->add(PNEmailPeer::DESCRIPTION, $this->description);
        if ($this->isColumnModified(PNEmailPeer::CREATED_AT)) $criteria->add(PNEmailPeer::CREATED_AT, $this->created_at);
        if ($this->isColumnModified(PNEmailPeer::UPDATED_AT)) $criteria->add(PNEmailPeer::UPDATED_AT, $this->updated_at);

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
        $criteria = new Criteria(PNEmailPeer::DATABASE_NAME);
        $criteria->add(PNEmailPeer::ID, $this->id);

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
     * @param object $copyObj An object of PNEmail (or compatible) type.
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setUuid($this->getUuid());
        $copyObj->setTitle($this->getTitle());
        $copyObj->setDescription($this->getDescription());
        $copyObj->setCreatedAt($this->getCreatedAt());
        $copyObj->setUpdatedAt($this->getUpdatedAt());

        if ($deepCopy && !$this->startCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);
            // store object hash to prevent cycle
            $this->startCopy = true;

            foreach ($this->getPUSubscribePNEs() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPUSubscribePNE($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPMEmailings() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPMEmailing($relObj->copy($deepCopy));
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
     * @return PNEmail Clone of current object.
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
     * @return PNEmailPeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new PNEmailPeer();
        }

        return self::$peer;
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
        if ('PUSubscribePNE' == $relationName) {
            $this->initPUSubscribePNEs();
        }
        if ('PMEmailing' == $relationName) {
            $this->initPMEmailings();
        }
    }

    /**
     * Clears out the collPUSubscribePNEs collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return PNEmail The current object (for fluent API support)
     * @see        addPUSubscribePNEs()
     */
    public function clearPUSubscribePNEs()
    {
        $this->collPUSubscribePNEs = null; // important to set this to null since that means it is uninitialized
        $this->collPUSubscribePNEsPartial = null;

        return $this;
    }

    /**
     * reset is the collPUSubscribePNEs collection loaded partially
     *
     * @return void
     */
    public function resetPartialPUSubscribePNEs($v = true)
    {
        $this->collPUSubscribePNEsPartial = $v;
    }

    /**
     * Initializes the collPUSubscribePNEs collection.
     *
     * By default this just sets the collPUSubscribePNEs collection to an empty array (like clearcollPUSubscribePNEs());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPUSubscribePNEs($overrideExisting = true)
    {
        if (null !== $this->collPUSubscribePNEs && !$overrideExisting) {
            return;
        }
        $this->collPUSubscribePNEs = new PropelObjectCollection();
        $this->collPUSubscribePNEs->setModel('PUSubscribePNE');
    }

    /**
     * Gets an array of PUSubscribePNE objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this PNEmail is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|PUSubscribePNE[] List of PUSubscribePNE objects
     * @throws PropelException
     */
    public function getPUSubscribePNEs($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collPUSubscribePNEsPartial && !$this->isNew();
        if (null === $this->collPUSubscribePNEs || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPUSubscribePNEs) {
                // return empty collection
                $this->initPUSubscribePNEs();
            } else {
                $collPUSubscribePNEs = PUSubscribePNEQuery::create(null, $criteria)
                    ->filterByPNEmail($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collPUSubscribePNEsPartial && count($collPUSubscribePNEs)) {
                      $this->initPUSubscribePNEs(false);

                      foreach ($collPUSubscribePNEs as $obj) {
                        if (false == $this->collPUSubscribePNEs->contains($obj)) {
                          $this->collPUSubscribePNEs->append($obj);
                        }
                      }

                      $this->collPUSubscribePNEsPartial = true;
                    }

                    $collPUSubscribePNEs->getInternalIterator()->rewind();

                    return $collPUSubscribePNEs;
                }

                if ($partial && $this->collPUSubscribePNEs) {
                    foreach ($this->collPUSubscribePNEs as $obj) {
                        if ($obj->isNew()) {
                            $collPUSubscribePNEs[] = $obj;
                        }
                    }
                }

                $this->collPUSubscribePNEs = $collPUSubscribePNEs;
                $this->collPUSubscribePNEsPartial = false;
            }
        }

        return $this->collPUSubscribePNEs;
    }

    /**
     * Sets a collection of PUSubscribePNE objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $pUSubscribePNEs A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return PNEmail The current object (for fluent API support)
     */
    public function setPUSubscribePNEs(PropelCollection $pUSubscribePNEs, PropelPDO $con = null)
    {
        $pUSubscribePNEsToDelete = $this->getPUSubscribePNEs(new Criteria(), $con)->diff($pUSubscribePNEs);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->pUSubscribePNEsScheduledForDeletion = clone $pUSubscribePNEsToDelete;

        foreach ($pUSubscribePNEsToDelete as $pUSubscribePNERemoved) {
            $pUSubscribePNERemoved->setPNEmail(null);
        }

        $this->collPUSubscribePNEs = null;
        foreach ($pUSubscribePNEs as $pUSubscribePNE) {
            $this->addPUSubscribePNE($pUSubscribePNE);
        }

        $this->collPUSubscribePNEs = $pUSubscribePNEs;
        $this->collPUSubscribePNEsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related PUSubscribePNE objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related PUSubscribePNE objects.
     * @throws PropelException
     */
    public function countPUSubscribePNEs(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collPUSubscribePNEsPartial && !$this->isNew();
        if (null === $this->collPUSubscribePNEs || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPUSubscribePNEs) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPUSubscribePNEs());
            }
            $query = PUSubscribePNEQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPNEmail($this)
                ->count($con);
        }

        return count($this->collPUSubscribePNEs);
    }

    /**
     * Method called to associate a PUSubscribePNE object to this object
     * through the PUSubscribePNE foreign key attribute.
     *
     * @param    PUSubscribePNE $l PUSubscribePNE
     * @return PNEmail The current object (for fluent API support)
     */
    public function addPUSubscribePNE(PUSubscribePNE $l)
    {
        if ($this->collPUSubscribePNEs === null) {
            $this->initPUSubscribePNEs();
            $this->collPUSubscribePNEsPartial = true;
        }

        if (!in_array($l, $this->collPUSubscribePNEs->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddPUSubscribePNE($l);

            if ($this->pUSubscribePNEsScheduledForDeletion and $this->pUSubscribePNEsScheduledForDeletion->contains($l)) {
                $this->pUSubscribePNEsScheduledForDeletion->remove($this->pUSubscribePNEsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	PUSubscribePNE $pUSubscribePNE The pUSubscribePNE object to add.
     */
    protected function doAddPUSubscribePNE($pUSubscribePNE)
    {
        $this->collPUSubscribePNEs[]= $pUSubscribePNE;
        $pUSubscribePNE->setPNEmail($this);
    }

    /**
     * @param	PUSubscribePNE $pUSubscribePNE The pUSubscribePNE object to remove.
     * @return PNEmail The current object (for fluent API support)
     */
    public function removePUSubscribePNE($pUSubscribePNE)
    {
        if ($this->getPUSubscribePNEs()->contains($pUSubscribePNE)) {
            $this->collPUSubscribePNEs->remove($this->collPUSubscribePNEs->search($pUSubscribePNE));
            if (null === $this->pUSubscribePNEsScheduledForDeletion) {
                $this->pUSubscribePNEsScheduledForDeletion = clone $this->collPUSubscribePNEs;
                $this->pUSubscribePNEsScheduledForDeletion->clear();
            }
            $this->pUSubscribePNEsScheduledForDeletion[]= clone $pUSubscribePNE;
            $pUSubscribePNE->setPNEmail(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this PNEmail is new, it will return
     * an empty collection; or if this PNEmail has previously
     * been saved, it will retrieve related PUSubscribePNEs from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in PNEmail.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|PUSubscribePNE[] List of PUSubscribePNE objects
     */
    public function getPUSubscribePNEsJoinPUser($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PUSubscribePNEQuery::create(null, $criteria);
        $query->joinWith('PUser', $join_behavior);

        return $this->getPUSubscribePNEs($query, $con);
    }

    /**
     * Clears out the collPMEmailings collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return PNEmail The current object (for fluent API support)
     * @see        addPMEmailings()
     */
    public function clearPMEmailings()
    {
        $this->collPMEmailings = null; // important to set this to null since that means it is uninitialized
        $this->collPMEmailingsPartial = null;

        return $this;
    }

    /**
     * reset is the collPMEmailings collection loaded partially
     *
     * @return void
     */
    public function resetPartialPMEmailings($v = true)
    {
        $this->collPMEmailingsPartial = $v;
    }

    /**
     * Initializes the collPMEmailings collection.
     *
     * By default this just sets the collPMEmailings collection to an empty array (like clearcollPMEmailings());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPMEmailings($overrideExisting = true)
    {
        if (null !== $this->collPMEmailings && !$overrideExisting) {
            return;
        }
        $this->collPMEmailings = new PropelObjectCollection();
        $this->collPMEmailings->setModel('PMEmailing');
    }

    /**
     * Gets an array of PMEmailing objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this PNEmail is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|PMEmailing[] List of PMEmailing objects
     * @throws PropelException
     */
    public function getPMEmailings($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collPMEmailingsPartial && !$this->isNew();
        if (null === $this->collPMEmailings || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPMEmailings) {
                // return empty collection
                $this->initPMEmailings();
            } else {
                $collPMEmailings = PMEmailingQuery::create(null, $criteria)
                    ->filterByPNEmail($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collPMEmailingsPartial && count($collPMEmailings)) {
                      $this->initPMEmailings(false);

                      foreach ($collPMEmailings as $obj) {
                        if (false == $this->collPMEmailings->contains($obj)) {
                          $this->collPMEmailings->append($obj);
                        }
                      }

                      $this->collPMEmailingsPartial = true;
                    }

                    $collPMEmailings->getInternalIterator()->rewind();

                    return $collPMEmailings;
                }

                if ($partial && $this->collPMEmailings) {
                    foreach ($this->collPMEmailings as $obj) {
                        if ($obj->isNew()) {
                            $collPMEmailings[] = $obj;
                        }
                    }
                }

                $this->collPMEmailings = $collPMEmailings;
                $this->collPMEmailingsPartial = false;
            }
        }

        return $this->collPMEmailings;
    }

    /**
     * Sets a collection of PMEmailing objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $pMEmailings A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return PNEmail The current object (for fluent API support)
     */
    public function setPMEmailings(PropelCollection $pMEmailings, PropelPDO $con = null)
    {
        $pMEmailingsToDelete = $this->getPMEmailings(new Criteria(), $con)->diff($pMEmailings);


        $this->pMEmailingsScheduledForDeletion = $pMEmailingsToDelete;

        foreach ($pMEmailingsToDelete as $pMEmailingRemoved) {
            $pMEmailingRemoved->setPNEmail(null);
        }

        $this->collPMEmailings = null;
        foreach ($pMEmailings as $pMEmailing) {
            $this->addPMEmailing($pMEmailing);
        }

        $this->collPMEmailings = $pMEmailings;
        $this->collPMEmailingsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related PMEmailing objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related PMEmailing objects.
     * @throws PropelException
     */
    public function countPMEmailings(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collPMEmailingsPartial && !$this->isNew();
        if (null === $this->collPMEmailings || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPMEmailings) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPMEmailings());
            }
            $query = PMEmailingQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPNEmail($this)
                ->count($con);
        }

        return count($this->collPMEmailings);
    }

    /**
     * Method called to associate a PMEmailing object to this object
     * through the PMEmailing foreign key attribute.
     *
     * @param    PMEmailing $l PMEmailing
     * @return PNEmail The current object (for fluent API support)
     */
    public function addPMEmailing(PMEmailing $l)
    {
        if ($this->collPMEmailings === null) {
            $this->initPMEmailings();
            $this->collPMEmailingsPartial = true;
        }

        if (!in_array($l, $this->collPMEmailings->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddPMEmailing($l);

            if ($this->pMEmailingsScheduledForDeletion and $this->pMEmailingsScheduledForDeletion->contains($l)) {
                $this->pMEmailingsScheduledForDeletion->remove($this->pMEmailingsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	PMEmailing $pMEmailing The pMEmailing object to add.
     */
    protected function doAddPMEmailing($pMEmailing)
    {
        $this->collPMEmailings[]= $pMEmailing;
        $pMEmailing->setPNEmail($this);
    }

    /**
     * @param	PMEmailing $pMEmailing The pMEmailing object to remove.
     * @return PNEmail The current object (for fluent API support)
     */
    public function removePMEmailing($pMEmailing)
    {
        if ($this->getPMEmailings()->contains($pMEmailing)) {
            $this->collPMEmailings->remove($this->collPMEmailings->search($pMEmailing));
            if (null === $this->pMEmailingsScheduledForDeletion) {
                $this->pMEmailingsScheduledForDeletion = clone $this->collPMEmailings;
                $this->pMEmailingsScheduledForDeletion->clear();
            }
            $this->pMEmailingsScheduledForDeletion[]= $pMEmailing;
            $pMEmailing->setPNEmail(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this PNEmail is new, it will return
     * an empty collection; or if this PNEmail has previously
     * been saved, it will retrieve related PMEmailings from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in PNEmail.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|PMEmailing[] List of PMEmailing objects
     */
    public function getPMEmailingsJoinPUser($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PMEmailingQuery::create(null, $criteria);
        $query->joinWith('PUser', $join_behavior);

        return $this->getPMEmailings($query, $con);
    }

    /**
     * Clears out the collPUsers collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return PNEmail The current object (for fluent API support)
     * @see        addPUsers()
     */
    public function clearPUsers()
    {
        $this->collPUsers = null; // important to set this to null since that means it is uninitialized
        $this->collPUsersPartial = null;

        return $this;
    }

    /**
     * Initializes the collPUsers collection.
     *
     * By default this just sets the collPUsers collection to an empty collection (like clearPUsers());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @return void
     */
    public function initPUsers()
    {
        $this->collPUsers = new PropelObjectCollection();
        $this->collPUsers->setModel('PUser');
    }

    /**
     * Gets a collection of PUser objects related by a many-to-many relationship
     * to the current object by way of the p_u_subscribe_p_n_e cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this PNEmail is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria Optional query object to filter the query
     * @param PropelPDO $con Optional connection object
     *
     * @return PropelObjectCollection|PUser[] List of PUser objects
     */
    public function getPUsers($criteria = null, PropelPDO $con = null)
    {
        if (null === $this->collPUsers || null !== $criteria) {
            if ($this->isNew() && null === $this->collPUsers) {
                // return empty collection
                $this->initPUsers();
            } else {
                $collPUsers = PUserQuery::create(null, $criteria)
                    ->filterByPNEmail($this)
                    ->find($con);
                if (null !== $criteria) {
                    return $collPUsers;
                }
                $this->collPUsers = $collPUsers;
            }
        }

        return $this->collPUsers;
    }

    /**
     * Sets a collection of PUser objects related by a many-to-many relationship
     * to the current object by way of the p_u_subscribe_p_n_e cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $pUsers A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return PNEmail The current object (for fluent API support)
     */
    public function setPUsers(PropelCollection $pUsers, PropelPDO $con = null)
    {
        $this->clearPUsers();
        $currentPUsers = $this->getPUsers(null, $con);

        $this->pUsersScheduledForDeletion = $currentPUsers->diff($pUsers);

        foreach ($pUsers as $pUser) {
            if (!$currentPUsers->contains($pUser)) {
                $this->doAddPUser($pUser);
            }
        }

        $this->collPUsers = $pUsers;

        return $this;
    }

    /**
     * Gets the number of PUser objects related by a many-to-many relationship
     * to the current object by way of the p_u_subscribe_p_n_e cross-reference table.
     *
     * @param Criteria $criteria Optional query object to filter the query
     * @param boolean $distinct Set to true to force count distinct
     * @param PropelPDO $con Optional connection object
     *
     * @return int the number of related PUser objects
     */
    public function countPUsers($criteria = null, $distinct = false, PropelPDO $con = null)
    {
        if (null === $this->collPUsers || null !== $criteria) {
            if ($this->isNew() && null === $this->collPUsers) {
                return 0;
            } else {
                $query = PUserQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByPNEmail($this)
                    ->count($con);
            }
        } else {
            return count($this->collPUsers);
        }
    }

    /**
     * Associate a PUser object to this object
     * through the p_u_subscribe_p_n_e cross reference table.
     *
     * @param  PUser $pUser The PUSubscribePNE object to relate
     * @return PNEmail The current object (for fluent API support)
     */
    public function addPUser(PUser $pUser)
    {
        if ($this->collPUsers === null) {
            $this->initPUsers();
        }

        if (!$this->collPUsers->contains($pUser)) { // only add it if the **same** object is not already associated
            $this->doAddPUser($pUser);
            $this->collPUsers[] = $pUser;

            if ($this->pUsersScheduledForDeletion and $this->pUsersScheduledForDeletion->contains($pUser)) {
                $this->pUsersScheduledForDeletion->remove($this->pUsersScheduledForDeletion->search($pUser));
            }
        }

        return $this;
    }

    /**
     * @param	PUser $pUser The pUser object to add.
     */
    protected function doAddPUser(PUser $pUser)
    {
        // set the back reference to this object directly as using provided method either results
        // in endless loop or in multiple relations
        if (!$pUser->getPNEmails()->contains($this)) { $pUSubscribePNE = new PUSubscribePNE();
            $pUSubscribePNE->setPUser($pUser);
            $this->addPUSubscribePNE($pUSubscribePNE);

            $foreignCollection = $pUser->getPNEmails();
            $foreignCollection[] = $this;
        }
    }

    /**
     * Remove a PUser object to this object
     * through the p_u_subscribe_p_n_e cross reference table.
     *
     * @param PUser $pUser The PUSubscribePNE object to relate
     * @return PNEmail The current object (for fluent API support)
     */
    public function removePUser(PUser $pUser)
    {
        if ($this->getPUsers()->contains($pUser)) {
            $this->collPUsers->remove($this->collPUsers->search($pUser));
            if (null === $this->pUsersScheduledForDeletion) {
                $this->pUsersScheduledForDeletion = clone $this->collPUsers;
                $this->pUsersScheduledForDeletion->clear();
            }
            $this->pUsersScheduledForDeletion[]= $pUser;
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
        $this->title = null;
        $this->description = null;
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
            if ($this->collPUSubscribePNEs) {
                foreach ($this->collPUSubscribePNEs as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPMEmailings) {
                foreach ($this->collPMEmailings as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPUsers) {
                foreach ($this->collPUsers as $o) {
                    $o->clearAllReferences($deep);
                }
            }

            $this->alreadyInClearAllReferencesDeep = false;
        } // if ($deep)

        if ($this->collPUSubscribePNEs instanceof PropelCollection) {
            $this->collPUSubscribePNEs->clearIterator();
        }
        $this->collPUSubscribePNEs = null;
        if ($this->collPMEmailings instanceof PropelCollection) {
            $this->collPMEmailings->clearIterator();
        }
        $this->collPMEmailings = null;
        if ($this->collPUsers instanceof PropelCollection) {
            $this->collPUsers->clearIterator();
        }
        $this->collPUsers = null;
    }

    /**
     * return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(PNEmailPeer::DEFAULT_STRING_FORMAT);
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
     * @return     PNEmail The current object (for fluent API support)
     */
    public function keepUpdateDateUnchanged()
    {
        $this->modifiedColumns[] = PNEmailPeer::UPDATED_AT;

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
    * If permanent UUID, throw exception p_n_email.uuid*/
    public function preUpdate(PropelPDO $con = NULL) {
            $uuid = $this->getUuid();
        if(!is_null($uuid) && !\Ramsey\Uuid\Uuid::isValid($uuid)) {
            throw new \InvalidArgumentException("UUID: $uuid in not valid");
        }
            return true;
    }

}
