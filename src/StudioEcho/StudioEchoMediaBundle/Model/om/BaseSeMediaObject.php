<?php

namespace StudioEcho\StudioEchoMediaBundle\Model\om;

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
use StudioEcho\StudioEchoMediaBundle\Model\SeMediaFile;
use StudioEcho\StudioEchoMediaBundle\Model\SeMediaFileQuery;
use StudioEcho\StudioEchoMediaBundle\Model\SeMediaObject;
use StudioEcho\StudioEchoMediaBundle\Model\SeMediaObjectPeer;
use StudioEcho\StudioEchoMediaBundle\Model\SeMediaObjectQuery;
use StudioEcho\StudioEchoMediaBundle\Model\SeObjectHasFile;
use StudioEcho\StudioEchoMediaBundle\Model\SeObjectHasFileQuery;

abstract class BaseSeMediaObject extends BaseObject implements Persistent
{
    /**
     * Peer class name
     */
    const PEER = 'StudioEcho\\StudioEchoMediaBundle\\Model\\SeMediaObjectPeer';

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        SeMediaObjectPeer
     */
    protected static $peer;

    /**
     * The flag var to prevent infinit loop in deep copy
     * @var       boolean
     */
    protected $startCopy = false;

    /**
     * The value for the id field.
     * @var        int
     */
    protected $id;

    /**
     * The value for the object_id field.
     * @var        int
     */
    protected $object_id;

    /**
     * The value for the object_classname field.
     * @var        string
     */
    protected $object_classname;

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
     * @var        PropelObjectCollection|SeObjectHasFile[] Collection to store aggregation of SeObjectHasFile objects.
     */
    protected $collSeObjectHasFiles;
    protected $collSeObjectHasFilesPartial;

    /**
     * @var        PropelObjectCollection|SeMediaFile[] Collection to store aggregation of SeMediaFile objects.
     */
    protected $collSeMediaFiles;

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
    protected $seMediaFilesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $seObjectHasFilesScheduledForDeletion = null;

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
     * Get the [object_id] column value.
     *
     * @return int
     */
    public function getObjectId()
    {
        return $this->object_id;
    }

    /**
     * Get the [object_classname] column value.
     *
     * @return string
     */
    public function getObjectClassname()
    {
        return $this->object_classname;
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
     * @param int $v new value
     * @return SeMediaObject The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[] = SeMediaObjectPeer::ID;
        }


        return $this;
    } // setId()

    /**
     * Set the value of [object_id] column.
     *
     * @param int $v new value
     * @return SeMediaObject The current object (for fluent API support)
     */
    public function setObjectId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->object_id !== $v) {
            $this->object_id = $v;
            $this->modifiedColumns[] = SeMediaObjectPeer::OBJECT_ID;
        }


        return $this;
    } // setObjectId()

    /**
     * Set the value of [object_classname] column.
     *
     * @param string $v new value
     * @return SeMediaObject The current object (for fluent API support)
     */
    public function setObjectClassname($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (string) $v;
        }

        if ($this->object_classname !== $v) {
            $this->object_classname = $v;
            $this->modifiedColumns[] = SeMediaObjectPeer::OBJECT_CLASSNAME;
        }


        return $this;
    } // setObjectClassname()

    /**
     * Sets the value of [created_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return SeMediaObject The current object (for fluent API support)
     */
    public function setCreatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->created_at !== null || $dt !== null) {
            $currentDateAsString = ($this->created_at !== null && $tmpDt = new DateTime($this->created_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->created_at = $newDateAsString;
                $this->modifiedColumns[] = SeMediaObjectPeer::CREATED_AT;
            }
        } // if either are not null


        return $this;
    } // setCreatedAt()

    /**
     * Sets the value of [updated_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return SeMediaObject The current object (for fluent API support)
     */
    public function setUpdatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->updated_at !== null || $dt !== null) {
            $currentDateAsString = ($this->updated_at !== null && $tmpDt = new DateTime($this->updated_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->updated_at = $newDateAsString;
                $this->modifiedColumns[] = SeMediaObjectPeer::UPDATED_AT;
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
     * @param int $startcol 0-based offset column which indicates which restultset column to start with.
     * @param boolean $rehydrate Whether this object is being re-hydrated from the database.
     * @return int             next starting column
     * @throws PropelException - Any caught Exception will be rewrapped as a PropelException.
     */
    public function hydrate($row, $startcol = 0, $rehydrate = false)
    {
        try {

            $this->id = ($row[$startcol + 0] !== null) ? (int) $row[$startcol + 0] : null;
            $this->object_id = ($row[$startcol + 1] !== null) ? (int) $row[$startcol + 1] : null;
            $this->object_classname = ($row[$startcol + 2] !== null) ? (string) $row[$startcol + 2] : null;
            $this->created_at = ($row[$startcol + 3] !== null) ? (string) $row[$startcol + 3] : null;
            $this->updated_at = ($row[$startcol + 4] !== null) ? (string) $row[$startcol + 4] : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }
            $this->postHydrate($row, $startcol, $rehydrate);
            return $startcol + 5; // 5 = SeMediaObjectPeer::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating SeMediaObject object", $e);
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
            $con = Propel::getConnection(SeMediaObjectPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $stmt = SeMediaObjectPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
        $row = $stmt->fetch(PDO::FETCH_NUM);
        $stmt->closeCursor();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->collSeObjectHasFiles = null;

            $this->collSeMediaFiles = null;
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
            $con = Propel::getConnection(SeMediaObjectPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        try {
            $deleteQuery = SeMediaObjectQuery::create()
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
            $con = Propel::getConnection(SeMediaObjectPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        $isInsert = $this->isNew();
        try {
            $ret = $this->preSave($con);
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
                // timestampable behavior
                if (!$this->isColumnModified(SeMediaObjectPeer::CREATED_AT)) {
                    $this->setCreatedAt(time());
                }
                if (!$this->isColumnModified(SeMediaObjectPeer::UPDATED_AT)) {
                    $this->setUpdatedAt(time());
                }
            } else {
                $ret = $ret && $this->preUpdate($con);
                // timestampable behavior
                if ($this->isModified() && !$this->isColumnModified(SeMediaObjectPeer::UPDATED_AT)) {
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
                SeMediaObjectPeer::addInstanceToPool($this);
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

            if ($this->seMediaFilesScheduledForDeletion !== null) {
                if (!$this->seMediaFilesScheduledForDeletion->isEmpty()) {
                    $pks = array();
                    $pk = $this->getPrimaryKey();
                    foreach ($this->seMediaFilesScheduledForDeletion->getPrimaryKeys(false) as $remotePk) {
                        $pks[] = array($pk, $remotePk);
                    }
                    SeObjectHasFileQuery::create()
                        ->filterByPrimaryKeys($pks)
                        ->delete($con);
                    $this->seMediaFilesScheduledForDeletion = null;
                }

                foreach ($this->getSeMediaFiles() as $seMediaFile) {
                    if ($seMediaFile->isModified()) {
                        $seMediaFile->save($con);
                    }
                }
            } elseif ($this->collSeMediaFiles) {
                foreach ($this->collSeMediaFiles as $seMediaFile) {
                    if ($seMediaFile->isModified()) {
                        $seMediaFile->save($con);
                    }
                }
            }

            if ($this->seObjectHasFilesScheduledForDeletion !== null) {
                if (!$this->seObjectHasFilesScheduledForDeletion->isEmpty()) {
                    SeObjectHasFileQuery::create()
                        ->filterByPrimaryKeys($this->seObjectHasFilesScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->seObjectHasFilesScheduledForDeletion = null;
                }
            }

            if ($this->collSeObjectHasFiles !== null) {
                foreach ($this->collSeObjectHasFiles as $referrerFK) {
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

        $this->modifiedColumns[] = SeMediaObjectPeer::ID;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . SeMediaObjectPeer::ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(SeMediaObjectPeer::ID)) {
            $modifiedColumns[':p' . $index++]  = '`id`';
        }
        if ($this->isColumnModified(SeMediaObjectPeer::OBJECT_ID)) {
            $modifiedColumns[':p' . $index++]  = '`object_id`';
        }
        if ($this->isColumnModified(SeMediaObjectPeer::OBJECT_CLASSNAME)) {
            $modifiedColumns[':p' . $index++]  = '`object_classname`';
        }
        if ($this->isColumnModified(SeMediaObjectPeer::CREATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`created_at`';
        }
        if ($this->isColumnModified(SeMediaObjectPeer::UPDATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`updated_at`';
        }

        $sql = sprintf(
            'INSERT INTO `se_media_object` (%s) VALUES (%s)',
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
                    case '`object_id`':
                        $stmt->bindValue($identifier, $this->object_id, PDO::PARAM_INT);
                        break;
                    case '`object_classname`':
                        $stmt->bindValue($identifier, $this->object_classname, PDO::PARAM_STR);
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
     * an aggreagated array of ValidationFailed objects will be returned.
     *
     * @param array $columns Array of column names to validate.
     * @return mixed <code>true</code> if all validations pass; array of <code>ValidationFailed</code> objets otherwise.
     */
    protected function doValidate($columns = null)
    {
        if (!$this->alreadyInValidation) {
            $this->alreadyInValidation = true;
            $retval = null;

            $failureMap = array();


            if (($retval = SeMediaObjectPeer::doValidate($this, $columns)) !== true) {
                $failureMap = array_merge($failureMap, $retval);
            }


                if ($this->collSeObjectHasFiles !== null) {
                    foreach ($this->collSeObjectHasFiles as $referrerFK) {
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
        $pos = SeMediaObjectPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                return $this->getObjectId();
                break;
            case 2:
                return $this->getObjectClassname();
                break;
            case 3:
                return $this->getCreatedAt();
                break;
            case 4:
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
        if (isset($alreadyDumpedObjects['SeMediaObject'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['SeMediaObject'][$this->getPrimaryKey()] = true;
        $keys = SeMediaObjectPeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getObjectId(),
            $keys[2] => $this->getObjectClassname(),
            $keys[3] => $this->getCreatedAt(),
            $keys[4] => $this->getUpdatedAt(),
        );
        if ($includeForeignObjects) {
            if (null !== $this->collSeObjectHasFiles) {
                $result['SeObjectHasFiles'] = $this->collSeObjectHasFiles->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
        $pos = SeMediaObjectPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);

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
                $this->setObjectId($value);
                break;
            case 2:
                $this->setObjectClassname($value);
                break;
            case 3:
                $this->setCreatedAt($value);
                break;
            case 4:
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
        $keys = SeMediaObjectPeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setObjectId($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setObjectClassname($arr[$keys[2]]);
        if (array_key_exists($keys[3], $arr)) $this->setCreatedAt($arr[$keys[3]]);
        if (array_key_exists($keys[4], $arr)) $this->setUpdatedAt($arr[$keys[4]]);
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(SeMediaObjectPeer::DATABASE_NAME);

        if ($this->isColumnModified(SeMediaObjectPeer::ID)) $criteria->add(SeMediaObjectPeer::ID, $this->id);
        if ($this->isColumnModified(SeMediaObjectPeer::OBJECT_ID)) $criteria->add(SeMediaObjectPeer::OBJECT_ID, $this->object_id);
        if ($this->isColumnModified(SeMediaObjectPeer::OBJECT_CLASSNAME)) $criteria->add(SeMediaObjectPeer::OBJECT_CLASSNAME, $this->object_classname);
        if ($this->isColumnModified(SeMediaObjectPeer::CREATED_AT)) $criteria->add(SeMediaObjectPeer::CREATED_AT, $this->created_at);
        if ($this->isColumnModified(SeMediaObjectPeer::UPDATED_AT)) $criteria->add(SeMediaObjectPeer::UPDATED_AT, $this->updated_at);

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
        $criteria = new Criteria(SeMediaObjectPeer::DATABASE_NAME);
        $criteria->add(SeMediaObjectPeer::ID, $this->id);

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
     * @param object $copyObj An object of SeMediaObject (or compatible) type.
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setObjectId($this->getObjectId());
        $copyObj->setObjectClassname($this->getObjectClassname());
        $copyObj->setCreatedAt($this->getCreatedAt());
        $copyObj->setUpdatedAt($this->getUpdatedAt());

        if ($deepCopy && !$this->startCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);
            // store object hash to prevent cycle
            $this->startCopy = true;

            foreach ($this->getSeObjectHasFiles() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addSeObjectHasFile($relObj->copy($deepCopy));
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
     * @return SeMediaObject Clone of current object.
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
     * @return SeMediaObjectPeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new SeMediaObjectPeer();
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
        if ('SeObjectHasFile' == $relationName) {
            $this->initSeObjectHasFiles();
        }
    }

    /**
     * Clears out the collSeObjectHasFiles collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return SeMediaObject The current object (for fluent API support)
     * @see        addSeObjectHasFiles()
     */
    public function clearSeObjectHasFiles()
    {
        $this->collSeObjectHasFiles = null; // important to set this to null since that means it is uninitialized
        $this->collSeObjectHasFilesPartial = null;

        return $this;
    }

    /**
     * reset is the collSeObjectHasFiles collection loaded partially
     *
     * @return void
     */
    public function resetPartialSeObjectHasFiles($v = true)
    {
        $this->collSeObjectHasFilesPartial = $v;
    }

    /**
     * Initializes the collSeObjectHasFiles collection.
     *
     * By default this just sets the collSeObjectHasFiles collection to an empty array (like clearcollSeObjectHasFiles());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initSeObjectHasFiles($overrideExisting = true)
    {
        if (null !== $this->collSeObjectHasFiles && !$overrideExisting) {
            return;
        }
        $this->collSeObjectHasFiles = new PropelObjectCollection();
        $this->collSeObjectHasFiles->setModel('SeObjectHasFile');
    }

    /**
     * Gets an array of SeObjectHasFile objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this SeMediaObject is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|SeObjectHasFile[] List of SeObjectHasFile objects
     * @throws PropelException
     */
    public function getSeObjectHasFiles($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collSeObjectHasFilesPartial && !$this->isNew();
        if (null === $this->collSeObjectHasFiles || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collSeObjectHasFiles) {
                // return empty collection
                $this->initSeObjectHasFiles();
            } else {
                $collSeObjectHasFiles = SeObjectHasFileQuery::create(null, $criteria)
                    ->filterBySeMediaObject($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collSeObjectHasFilesPartial && count($collSeObjectHasFiles)) {
                      $this->initSeObjectHasFiles(false);

                      foreach($collSeObjectHasFiles as $obj) {
                        if (false == $this->collSeObjectHasFiles->contains($obj)) {
                          $this->collSeObjectHasFiles->append($obj);
                        }
                      }

                      $this->collSeObjectHasFilesPartial = true;
                    }

                    $collSeObjectHasFiles->getInternalIterator()->rewind();
                    return $collSeObjectHasFiles;
                }

                if($partial && $this->collSeObjectHasFiles) {
                    foreach($this->collSeObjectHasFiles as $obj) {
                        if($obj->isNew()) {
                            $collSeObjectHasFiles[] = $obj;
                        }
                    }
                }

                $this->collSeObjectHasFiles = $collSeObjectHasFiles;
                $this->collSeObjectHasFilesPartial = false;
            }
        }

        return $this->collSeObjectHasFiles;
    }

    /**
     * Sets a collection of SeObjectHasFile objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $seObjectHasFiles A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return SeMediaObject The current object (for fluent API support)
     */
    public function setSeObjectHasFiles(PropelCollection $seObjectHasFiles, PropelPDO $con = null)
    {
        $seObjectHasFilesToDelete = $this->getSeObjectHasFiles(new Criteria(), $con)->diff($seObjectHasFiles);

        $this->seObjectHasFilesScheduledForDeletion = unserialize(serialize($seObjectHasFilesToDelete));

        foreach ($seObjectHasFilesToDelete as $seObjectHasFileRemoved) {
            $seObjectHasFileRemoved->setSeMediaObject(null);
        }

        $this->collSeObjectHasFiles = null;
        foreach ($seObjectHasFiles as $seObjectHasFile) {
            $this->addSeObjectHasFile($seObjectHasFile);
        }

        $this->collSeObjectHasFiles = $seObjectHasFiles;
        $this->collSeObjectHasFilesPartial = false;

        return $this;
    }

    /**
     * Returns the number of related SeObjectHasFile objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related SeObjectHasFile objects.
     * @throws PropelException
     */
    public function countSeObjectHasFiles(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collSeObjectHasFilesPartial && !$this->isNew();
        if (null === $this->collSeObjectHasFiles || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collSeObjectHasFiles) {
                return 0;
            }

            if($partial && !$criteria) {
                return count($this->getSeObjectHasFiles());
            }
            $query = SeObjectHasFileQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterBySeMediaObject($this)
                ->count($con);
        }

        return count($this->collSeObjectHasFiles);
    }

    /**
     * Method called to associate a SeObjectHasFile object to this object
     * through the SeObjectHasFile foreign key attribute.
     *
     * @param    SeObjectHasFile $l SeObjectHasFile
     * @return SeMediaObject The current object (for fluent API support)
     */
    public function addSeObjectHasFile(SeObjectHasFile $l)
    {
        if ($this->collSeObjectHasFiles === null) {
            $this->initSeObjectHasFiles();
            $this->collSeObjectHasFilesPartial = true;
        }
        if (!in_array($l, $this->collSeObjectHasFiles->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddSeObjectHasFile($l);
        }

        return $this;
    }

    /**
     * @param	SeObjectHasFile $seObjectHasFile The seObjectHasFile object to add.
     */
    protected function doAddSeObjectHasFile($seObjectHasFile)
    {
        $this->collSeObjectHasFiles[]= $seObjectHasFile;
        $seObjectHasFile->setSeMediaObject($this);
    }

    /**
     * @param	SeObjectHasFile $seObjectHasFile The seObjectHasFile object to remove.
     * @return SeMediaObject The current object (for fluent API support)
     */
    public function removeSeObjectHasFile($seObjectHasFile)
    {
        if ($this->getSeObjectHasFiles()->contains($seObjectHasFile)) {
            $this->collSeObjectHasFiles->remove($this->collSeObjectHasFiles->search($seObjectHasFile));
            if (null === $this->seObjectHasFilesScheduledForDeletion) {
                $this->seObjectHasFilesScheduledForDeletion = clone $this->collSeObjectHasFiles;
                $this->seObjectHasFilesScheduledForDeletion->clear();
            }
            $this->seObjectHasFilesScheduledForDeletion[]= clone $seObjectHasFile;
            $seObjectHasFile->setSeMediaObject(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this SeMediaObject is new, it will return
     * an empty collection; or if this SeMediaObject has previously
     * been saved, it will retrieve related SeObjectHasFiles from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in SeMediaObject.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|SeObjectHasFile[] List of SeObjectHasFile objects
     */
    public function getSeObjectHasFilesJoinSeMediaFile($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = SeObjectHasFileQuery::create(null, $criteria);
        $query->joinWith('SeMediaFile', $join_behavior);

        return $this->getSeObjectHasFiles($query, $con);
    }

    /**
     * Clears out the collSeMediaFiles collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return SeMediaObject The current object (for fluent API support)
     * @see        addSeMediaFiles()
     */
    public function clearSeMediaFiles()
    {
        $this->collSeMediaFiles = null; // important to set this to null since that means it is uninitialized
        $this->collSeMediaFilesPartial = null;

        return $this;
    }

    /**
     * Initializes the collSeMediaFiles collection.
     *
     * By default this just sets the collSeMediaFiles collection to an empty collection (like clearSeMediaFiles());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @return void
     */
    public function initSeMediaFiles()
    {
        $this->collSeMediaFiles = new PropelObjectCollection();
        $this->collSeMediaFiles->setModel('SeMediaFile');
    }

    /**
     * Gets a collection of SeMediaFile objects related by a many-to-many relationship
     * to the current object by way of the se_object_has_file cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this SeMediaObject is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria Optional query object to filter the query
     * @param PropelPDO $con Optional connection object
     *
     * @return PropelObjectCollection|SeMediaFile[] List of SeMediaFile objects
     */
    public function getSeMediaFiles($criteria = null, PropelPDO $con = null)
    {
        if (null === $this->collSeMediaFiles || null !== $criteria) {
            if ($this->isNew() && null === $this->collSeMediaFiles) {
                // return empty collection
                $this->initSeMediaFiles();
            } else {
                $collSeMediaFiles = SeMediaFileQuery::create(null, $criteria)
                    ->filterBySeMediaObject($this)
                    ->find($con);
                if (null !== $criteria) {
                    return $collSeMediaFiles;
                }
                $this->collSeMediaFiles = $collSeMediaFiles;
            }
        }

        return $this->collSeMediaFiles;
    }

    /**
     * Sets a collection of SeMediaFile objects related by a many-to-many relationship
     * to the current object by way of the se_object_has_file cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $seMediaFiles A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return SeMediaObject The current object (for fluent API support)
     */
    public function setSeMediaFiles(PropelCollection $seMediaFiles, PropelPDO $con = null)
    {
        $this->clearSeMediaFiles();
        $currentSeMediaFiles = $this->getSeMediaFiles();

        $this->seMediaFilesScheduledForDeletion = $currentSeMediaFiles->diff($seMediaFiles);

        foreach ($seMediaFiles as $seMediaFile) {
            if (!$currentSeMediaFiles->contains($seMediaFile)) {
                $this->doAddSeMediaFile($seMediaFile);
            }
        }

        $this->collSeMediaFiles = $seMediaFiles;

        return $this;
    }

    /**
     * Gets the number of SeMediaFile objects related by a many-to-many relationship
     * to the current object by way of the se_object_has_file cross-reference table.
     *
     * @param Criteria $criteria Optional query object to filter the query
     * @param boolean $distinct Set to true to force count distinct
     * @param PropelPDO $con Optional connection object
     *
     * @return int the number of related SeMediaFile objects
     */
    public function countSeMediaFiles($criteria = null, $distinct = false, PropelPDO $con = null)
    {
        if (null === $this->collSeMediaFiles || null !== $criteria) {
            if ($this->isNew() && null === $this->collSeMediaFiles) {
                return 0;
            } else {
                $query = SeMediaFileQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterBySeMediaObject($this)
                    ->count($con);
            }
        } else {
            return count($this->collSeMediaFiles);
        }
    }

    /**
     * Associate a SeMediaFile object to this object
     * through the se_object_has_file cross reference table.
     *
     * @param  SeMediaFile $seMediaFile The SeObjectHasFile object to relate
     * @return SeMediaObject The current object (for fluent API support)
     */
    public function addSeMediaFile(SeMediaFile $seMediaFile)
    {
        if ($this->collSeMediaFiles === null) {
            $this->initSeMediaFiles();
        }
        if (!$this->collSeMediaFiles->contains($seMediaFile)) { // only add it if the **same** object is not already associated
            $this->doAddSeMediaFile($seMediaFile);

            $this->collSeMediaFiles[]= $seMediaFile;
        }

        return $this;
    }

    /**
     * @param	SeMediaFile $seMediaFile The seMediaFile object to add.
     */
    protected function doAddSeMediaFile($seMediaFile)
    {
        $seObjectHasFile = new SeObjectHasFile();
        $seObjectHasFile->setSeMediaFile($seMediaFile);
        $this->addSeObjectHasFile($seObjectHasFile);
    }

    /**
     * Remove a SeMediaFile object to this object
     * through the se_object_has_file cross reference table.
     *
     * @param SeMediaFile $seMediaFile The SeObjectHasFile object to relate
     * @return SeMediaObject The current object (for fluent API support)
     */
    public function removeSeMediaFile(SeMediaFile $seMediaFile)
    {
        if ($this->getSeMediaFiles()->contains($seMediaFile)) {
            $this->collSeMediaFiles->remove($this->collSeMediaFiles->search($seMediaFile));
            if (null === $this->seMediaFilesScheduledForDeletion) {
                $this->seMediaFilesScheduledForDeletion = clone $this->collSeMediaFiles;
                $this->seMediaFilesScheduledForDeletion->clear();
            }
            $this->seMediaFilesScheduledForDeletion[]= $seMediaFile;
        }

        return $this;
    }

    /**
     * Clears the current object and sets all attributes to their default values
     */
    public function clear()
    {
        $this->id = null;
        $this->object_id = null;
        $this->object_classname = null;
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
     * when using Propel in certain daemon or large-volumne/high-memory operations.
     *
     * @param boolean $deep Whether to also clear the references on all referrer objects.
     */
    public function clearAllReferences($deep = false)
    {
        if ($deep && !$this->alreadyInClearAllReferencesDeep) {
            $this->alreadyInClearAllReferencesDeep = true;
            if ($this->collSeObjectHasFiles) {
                foreach ($this->collSeObjectHasFiles as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collSeMediaFiles) {
                foreach ($this->collSeMediaFiles as $o) {
                    $o->clearAllReferences($deep);
                }
            }

            $this->alreadyInClearAllReferencesDeep = false;
        } // if ($deep)

        if ($this->collSeObjectHasFiles instanceof PropelCollection) {
            $this->collSeObjectHasFiles->clearIterator();
        }
        $this->collSeObjectHasFiles = null;
        if ($this->collSeMediaFiles instanceof PropelCollection) {
            $this->collSeMediaFiles->clearIterator();
        }
        $this->collSeMediaFiles = null;
    }

    /**
     * return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(SeMediaObjectPeer::DEFAULT_STRING_FORMAT);
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
     * @return     SeMediaObject The current object (for fluent API support)
     */
    public function keepUpdateDateUnchanged()
    {
        $this->modifiedColumns[] = SeMediaObjectPeer::UPDATED_AT;

        return $this;
    }

}
