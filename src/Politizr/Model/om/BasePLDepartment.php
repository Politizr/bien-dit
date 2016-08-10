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
use Politizr\Model\PLCity;
use Politizr\Model\PLCityQuery;
use Politizr\Model\PLDepartment;
use Politizr\Model\PLDepartmentPeer;
use Politizr\Model\PLDepartmentQuery;
use Politizr\Model\PLRegion;
use Politizr\Model\PLRegionQuery;
use Politizr\Model\PTag;
use Politizr\Model\PTagQuery;

abstract class BasePLDepartment extends BaseObject implements Persistent
{
    /**
     * Peer class name
     */
    const PEER = 'Politizr\\Model\\PLDepartmentPeer';

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        PLDepartmentPeer
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
     * The value for the p_l_region_id field.
     * @var        int
     */
    protected $p_l_region_id;

    /**
     * The value for the p_tag_id field.
     * @var        int
     */
    protected $p_tag_id;

    /**
     * The value for the code field.
     * @var        string
     */
    protected $code;

    /**
     * The value for the uuid field.
     * @var        string
     */
    protected $uuid;

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
     * @var        PLRegion
     */
    protected $aPLRegion;

    /**
     * @var        PTag
     */
    protected $aPTag;

    /**
     * @var        PropelObjectCollection|PLCity[] Collection to store aggregation of PLCity objects.
     */
    protected $collPLCities;
    protected $collPLCitiesPartial;

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
    protected $pLCitiesScheduledForDeletion = null;

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
     * Get the [p_l_region_id] column value.
     *
     * @return int
     */
    public function getPLRegionId()
    {

        return $this->p_l_region_id;
    }

    /**
     * Get the [p_tag_id] column value.
     *
     * @return int
     */
    public function getPTagId()
    {

        return $this->p_tag_id;
    }

    /**
     * Get the [code] column value.
     *
     * @return string
     */
    public function getCode()
    {

        return $this->code;
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
     * @return PLDepartment The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[] = PLDepartmentPeer::ID;
        }


        return $this;
    } // setId()

    /**
     * Set the value of [p_l_region_id] column.
     *
     * @param  int $v new value
     * @return PLDepartment The current object (for fluent API support)
     */
    public function setPLRegionId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->p_l_region_id !== $v) {
            $this->p_l_region_id = $v;
            $this->modifiedColumns[] = PLDepartmentPeer::P_L_REGION_ID;
        }

        if ($this->aPLRegion !== null && $this->aPLRegion->getId() !== $v) {
            $this->aPLRegion = null;
        }


        return $this;
    } // setPLRegionId()

    /**
     * Set the value of [p_tag_id] column.
     *
     * @param  int $v new value
     * @return PLDepartment The current object (for fluent API support)
     */
    public function setPTagId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->p_tag_id !== $v) {
            $this->p_tag_id = $v;
            $this->modifiedColumns[] = PLDepartmentPeer::P_TAG_ID;
        }

        if ($this->aPTag !== null && $this->aPTag->getId() !== $v) {
            $this->aPTag = null;
        }


        return $this;
    } // setPTagId()

    /**
     * Set the value of [code] column.
     *
     * @param  string $v new value
     * @return PLDepartment The current object (for fluent API support)
     */
    public function setCode($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->code !== $v) {
            $this->code = $v;
            $this->modifiedColumns[] = PLDepartmentPeer::CODE;
        }


        return $this;
    } // setCode()

    /**
     * Set the value of [uuid] column.
     *
     * @param  string $v new value
     * @return PLDepartment The current object (for fluent API support)
     */
    public function setUuid($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->uuid !== $v) {
            $this->uuid = $v;
            $this->modifiedColumns[] = PLDepartmentPeer::UUID;
        }


        return $this;
    } // setUuid()

    /**
     * Sets the value of [created_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return PLDepartment The current object (for fluent API support)
     */
    public function setCreatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->created_at !== null || $dt !== null) {
            $currentDateAsString = ($this->created_at !== null && $tmpDt = new DateTime($this->created_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->created_at = $newDateAsString;
                $this->modifiedColumns[] = PLDepartmentPeer::CREATED_AT;
            }
        } // if either are not null


        return $this;
    } // setCreatedAt()

    /**
     * Sets the value of [updated_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return PLDepartment The current object (for fluent API support)
     */
    public function setUpdatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->updated_at !== null || $dt !== null) {
            $currentDateAsString = ($this->updated_at !== null && $tmpDt = new DateTime($this->updated_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->updated_at = $newDateAsString;
                $this->modifiedColumns[] = PLDepartmentPeer::UPDATED_AT;
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
            $this->p_l_region_id = ($row[$startcol + 1] !== null) ? (int) $row[$startcol + 1] : null;
            $this->p_tag_id = ($row[$startcol + 2] !== null) ? (int) $row[$startcol + 2] : null;
            $this->code = ($row[$startcol + 3] !== null) ? (string) $row[$startcol + 3] : null;
            $this->uuid = ($row[$startcol + 4] !== null) ? (string) $row[$startcol + 4] : null;
            $this->created_at = ($row[$startcol + 5] !== null) ? (string) $row[$startcol + 5] : null;
            $this->updated_at = ($row[$startcol + 6] !== null) ? (string) $row[$startcol + 6] : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }
            $this->postHydrate($row, $startcol, $rehydrate);

            return $startcol + 7; // 7 = PLDepartmentPeer::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating PLDepartment object", $e);
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

        if ($this->aPLRegion !== null && $this->p_l_region_id !== $this->aPLRegion->getId()) {
            $this->aPLRegion = null;
        }
        if ($this->aPTag !== null && $this->p_tag_id !== $this->aPTag->getId()) {
            $this->aPTag = null;
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
            $con = Propel::getConnection(PLDepartmentPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $stmt = PLDepartmentPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
        $row = $stmt->fetch(PDO::FETCH_NUM);
        $stmt->closeCursor();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aPLRegion = null;
            $this->aPTag = null;
            $this->collPLCities = null;

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
            $con = Propel::getConnection(PLDepartmentPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        try {
            $deleteQuery = PLDepartmentQuery::create()
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
            $con = Propel::getConnection(PLDepartmentPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        $isInsert = $this->isNew();
        try {
            $ret = $this->preSave($con);
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
                // timestampable behavior
                if (!$this->isColumnModified(PLDepartmentPeer::CREATED_AT)) {
                    $this->setCreatedAt(time());
                }
                if (!$this->isColumnModified(PLDepartmentPeer::UPDATED_AT)) {
                    $this->setUpdatedAt(time());
                }
            } else {
                $ret = $ret && $this->preUpdate($con);
                // timestampable behavior
                if ($this->isModified() && !$this->isColumnModified(PLDepartmentPeer::UPDATED_AT)) {
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
                PLDepartmentPeer::addInstanceToPool($this);
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

            if ($this->aPLRegion !== null) {
                if ($this->aPLRegion->isModified() || $this->aPLRegion->isNew()) {
                    $affectedRows += $this->aPLRegion->save($con);
                }
                $this->setPLRegion($this->aPLRegion);
            }

            if ($this->aPTag !== null) {
                if ($this->aPTag->isModified() || $this->aPTag->isNew()) {
                    $affectedRows += $this->aPTag->save($con);
                }
                $this->setPTag($this->aPTag);
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

            if ($this->pLCitiesScheduledForDeletion !== null) {
                if (!$this->pLCitiesScheduledForDeletion->isEmpty()) {
                    foreach ($this->pLCitiesScheduledForDeletion as $pLCity) {
                        // need to save related object because we set the relation to null
                        $pLCity->save($con);
                    }
                    $this->pLCitiesScheduledForDeletion = null;
                }
            }

            if ($this->collPLCities !== null) {
                foreach ($this->collPLCities as $referrerFK) {
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

        $this->modifiedColumns[] = PLDepartmentPeer::ID;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . PLDepartmentPeer::ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(PLDepartmentPeer::ID)) {
            $modifiedColumns[':p' . $index++]  = '`id`';
        }
        if ($this->isColumnModified(PLDepartmentPeer::P_L_REGION_ID)) {
            $modifiedColumns[':p' . $index++]  = '`p_l_region_id`';
        }
        if ($this->isColumnModified(PLDepartmentPeer::P_TAG_ID)) {
            $modifiedColumns[':p' . $index++]  = '`p_tag_id`';
        }
        if ($this->isColumnModified(PLDepartmentPeer::CODE)) {
            $modifiedColumns[':p' . $index++]  = '`code`';
        }
        if ($this->isColumnModified(PLDepartmentPeer::UUID)) {
            $modifiedColumns[':p' . $index++]  = '`uuid`';
        }
        if ($this->isColumnModified(PLDepartmentPeer::CREATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`created_at`';
        }
        if ($this->isColumnModified(PLDepartmentPeer::UPDATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`updated_at`';
        }

        $sql = sprintf(
            'INSERT INTO `p_l_department` (%s) VALUES (%s)',
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
                    case '`p_l_region_id`':
                        $stmt->bindValue($identifier, $this->p_l_region_id, PDO::PARAM_INT);
                        break;
                    case '`p_tag_id`':
                        $stmt->bindValue($identifier, $this->p_tag_id, PDO::PARAM_INT);
                        break;
                    case '`code`':
                        $stmt->bindValue($identifier, $this->code, PDO::PARAM_STR);
                        break;
                    case '`uuid`':
                        $stmt->bindValue($identifier, $this->uuid, PDO::PARAM_STR);
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
        $pos = PLDepartmentPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                return $this->getPLRegionId();
                break;
            case 2:
                return $this->getPTagId();
                break;
            case 3:
                return $this->getCode();
                break;
            case 4:
                return $this->getUuid();
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
        if (isset($alreadyDumpedObjects['PLDepartment'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['PLDepartment'][$this->getPrimaryKey()] = true;
        $keys = PLDepartmentPeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getPLRegionId(),
            $keys[2] => $this->getPTagId(),
            $keys[3] => $this->getCode(),
            $keys[4] => $this->getUuid(),
            $keys[5] => $this->getCreatedAt(),
            $keys[6] => $this->getUpdatedAt(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aPLRegion) {
                $result['PLRegion'] = $this->aPLRegion->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aPTag) {
                $result['PTag'] = $this->aPTag->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->collPLCities) {
                $result['PLCities'] = $this->collPLCities->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
        $pos = PLDepartmentPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);

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
                $this->setPLRegionId($value);
                break;
            case 2:
                $this->setPTagId($value);
                break;
            case 3:
                $this->setCode($value);
                break;
            case 4:
                $this->setUuid($value);
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
        $keys = PLDepartmentPeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setPLRegionId($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setPTagId($arr[$keys[2]]);
        if (array_key_exists($keys[3], $arr)) $this->setCode($arr[$keys[3]]);
        if (array_key_exists($keys[4], $arr)) $this->setUuid($arr[$keys[4]]);
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
        $criteria = new Criteria(PLDepartmentPeer::DATABASE_NAME);

        if ($this->isColumnModified(PLDepartmentPeer::ID)) $criteria->add(PLDepartmentPeer::ID, $this->id);
        if ($this->isColumnModified(PLDepartmentPeer::P_L_REGION_ID)) $criteria->add(PLDepartmentPeer::P_L_REGION_ID, $this->p_l_region_id);
        if ($this->isColumnModified(PLDepartmentPeer::P_TAG_ID)) $criteria->add(PLDepartmentPeer::P_TAG_ID, $this->p_tag_id);
        if ($this->isColumnModified(PLDepartmentPeer::CODE)) $criteria->add(PLDepartmentPeer::CODE, $this->code);
        if ($this->isColumnModified(PLDepartmentPeer::UUID)) $criteria->add(PLDepartmentPeer::UUID, $this->uuid);
        if ($this->isColumnModified(PLDepartmentPeer::CREATED_AT)) $criteria->add(PLDepartmentPeer::CREATED_AT, $this->created_at);
        if ($this->isColumnModified(PLDepartmentPeer::UPDATED_AT)) $criteria->add(PLDepartmentPeer::UPDATED_AT, $this->updated_at);

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
        $criteria = new Criteria(PLDepartmentPeer::DATABASE_NAME);
        $criteria->add(PLDepartmentPeer::ID, $this->id);

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
     * @param object $copyObj An object of PLDepartment (or compatible) type.
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setPLRegionId($this->getPLRegionId());
        $copyObj->setPTagId($this->getPTagId());
        $copyObj->setCode($this->getCode());
        $copyObj->setUuid($this->getUuid());
        $copyObj->setCreatedAt($this->getCreatedAt());
        $copyObj->setUpdatedAt($this->getUpdatedAt());

        if ($deepCopy && !$this->startCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);
            // store object hash to prevent cycle
            $this->startCopy = true;

            foreach ($this->getPLCities() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPLCity($relObj->copy($deepCopy));
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
     * @return PLDepartment Clone of current object.
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
     * @return PLDepartmentPeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new PLDepartmentPeer();
        }

        return self::$peer;
    }

    /**
     * Declares an association between this object and a PLRegion object.
     *
     * @param                  PLRegion $v
     * @return PLDepartment The current object (for fluent API support)
     * @throws PropelException
     */
    public function setPLRegion(PLRegion $v = null)
    {
        if ($v === null) {
            $this->setPLRegionId(NULL);
        } else {
            $this->setPLRegionId($v->getId());
        }

        $this->aPLRegion = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the PLRegion object, it will not be re-added.
        if ($v !== null) {
            $v->addPLDepartment($this);
        }


        return $this;
    }


    /**
     * Get the associated PLRegion object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return PLRegion The associated PLRegion object.
     * @throws PropelException
     */
    public function getPLRegion(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aPLRegion === null && ($this->p_l_region_id !== null) && $doQuery) {
            $this->aPLRegion = PLRegionQuery::create()->findPk($this->p_l_region_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aPLRegion->addPLDepartments($this);
             */
        }

        return $this->aPLRegion;
    }

    /**
     * Declares an association between this object and a PTag object.
     *
     * @param                  PTag $v
     * @return PLDepartment The current object (for fluent API support)
     * @throws PropelException
     */
    public function setPTag(PTag $v = null)
    {
        if ($v === null) {
            $this->setPTagId(NULL);
        } else {
            $this->setPTagId($v->getId());
        }

        $this->aPTag = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the PTag object, it will not be re-added.
        if ($v !== null) {
            $v->addPLDepartment($this);
        }


        return $this;
    }


    /**
     * Get the associated PTag object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return PTag The associated PTag object.
     * @throws PropelException
     */
    public function getPTag(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aPTag === null && ($this->p_tag_id !== null) && $doQuery) {
            $this->aPTag = PTagQuery::create()->findPk($this->p_tag_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aPTag->addPLDepartments($this);
             */
        }

        return $this->aPTag;
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
        if ('PLCity' == $relationName) {
            $this->initPLCities();
        }
    }

    /**
     * Clears out the collPLCities collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return PLDepartment The current object (for fluent API support)
     * @see        addPLCities()
     */
    public function clearPLCities()
    {
        $this->collPLCities = null; // important to set this to null since that means it is uninitialized
        $this->collPLCitiesPartial = null;

        return $this;
    }

    /**
     * reset is the collPLCities collection loaded partially
     *
     * @return void
     */
    public function resetPartialPLCities($v = true)
    {
        $this->collPLCitiesPartial = $v;
    }

    /**
     * Initializes the collPLCities collection.
     *
     * By default this just sets the collPLCities collection to an empty array (like clearcollPLCities());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPLCities($overrideExisting = true)
    {
        if (null !== $this->collPLCities && !$overrideExisting) {
            return;
        }
        $this->collPLCities = new PropelObjectCollection();
        $this->collPLCities->setModel('PLCity');
    }

    /**
     * Gets an array of PLCity objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this PLDepartment is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|PLCity[] List of PLCity objects
     * @throws PropelException
     */
    public function getPLCities($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collPLCitiesPartial && !$this->isNew();
        if (null === $this->collPLCities || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPLCities) {
                // return empty collection
                $this->initPLCities();
            } else {
                $collPLCities = PLCityQuery::create(null, $criteria)
                    ->filterByPLDepartment($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collPLCitiesPartial && count($collPLCities)) {
                      $this->initPLCities(false);

                      foreach ($collPLCities as $obj) {
                        if (false == $this->collPLCities->contains($obj)) {
                          $this->collPLCities->append($obj);
                        }
                      }

                      $this->collPLCitiesPartial = true;
                    }

                    $collPLCities->getInternalIterator()->rewind();

                    return $collPLCities;
                }

                if ($partial && $this->collPLCities) {
                    foreach ($this->collPLCities as $obj) {
                        if ($obj->isNew()) {
                            $collPLCities[] = $obj;
                        }
                    }
                }

                $this->collPLCities = $collPLCities;
                $this->collPLCitiesPartial = false;
            }
        }

        return $this->collPLCities;
    }

    /**
     * Sets a collection of PLCity objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $pLCities A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return PLDepartment The current object (for fluent API support)
     */
    public function setPLCities(PropelCollection $pLCities, PropelPDO $con = null)
    {
        $pLCitiesToDelete = $this->getPLCities(new Criteria(), $con)->diff($pLCities);


        $this->pLCitiesScheduledForDeletion = $pLCitiesToDelete;

        foreach ($pLCitiesToDelete as $pLCityRemoved) {
            $pLCityRemoved->setPLDepartment(null);
        }

        $this->collPLCities = null;
        foreach ($pLCities as $pLCity) {
            $this->addPLCity($pLCity);
        }

        $this->collPLCities = $pLCities;
        $this->collPLCitiesPartial = false;

        return $this;
    }

    /**
     * Returns the number of related PLCity objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related PLCity objects.
     * @throws PropelException
     */
    public function countPLCities(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collPLCitiesPartial && !$this->isNew();
        if (null === $this->collPLCities || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPLCities) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPLCities());
            }
            $query = PLCityQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPLDepartment($this)
                ->count($con);
        }

        return count($this->collPLCities);
    }

    /**
     * Method called to associate a PLCity object to this object
     * through the PLCity foreign key attribute.
     *
     * @param    PLCity $l PLCity
     * @return PLDepartment The current object (for fluent API support)
     */
    public function addPLCity(PLCity $l)
    {
        if ($this->collPLCities === null) {
            $this->initPLCities();
            $this->collPLCitiesPartial = true;
        }

        if (!in_array($l, $this->collPLCities->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddPLCity($l);

            if ($this->pLCitiesScheduledForDeletion and $this->pLCitiesScheduledForDeletion->contains($l)) {
                $this->pLCitiesScheduledForDeletion->remove($this->pLCitiesScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	PLCity $pLCity The pLCity object to add.
     */
    protected function doAddPLCity($pLCity)
    {
        $this->collPLCities[]= $pLCity;
        $pLCity->setPLDepartment($this);
    }

    /**
     * @param	PLCity $pLCity The pLCity object to remove.
     * @return PLDepartment The current object (for fluent API support)
     */
    public function removePLCity($pLCity)
    {
        if ($this->getPLCities()->contains($pLCity)) {
            $this->collPLCities->remove($this->collPLCities->search($pLCity));
            if (null === $this->pLCitiesScheduledForDeletion) {
                $this->pLCitiesScheduledForDeletion = clone $this->collPLCities;
                $this->pLCitiesScheduledForDeletion->clear();
            }
            $this->pLCitiesScheduledForDeletion[]= $pLCity;
            $pLCity->setPLDepartment(null);
        }

        return $this;
    }

    /**
     * Clears the current object and sets all attributes to their default values
     */
    public function clear()
    {
        $this->id = null;
        $this->p_l_region_id = null;
        $this->p_tag_id = null;
        $this->code = null;
        $this->uuid = null;
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
            if ($this->collPLCities) {
                foreach ($this->collPLCities as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->aPLRegion instanceof Persistent) {
              $this->aPLRegion->clearAllReferences($deep);
            }
            if ($this->aPTag instanceof Persistent) {
              $this->aPTag->clearAllReferences($deep);
            }

            $this->alreadyInClearAllReferencesDeep = false;
        } // if ($deep)

        if ($this->collPLCities instanceof PropelCollection) {
            $this->collPLCities->clearIterator();
        }
        $this->collPLCities = null;
        $this->aPLRegion = null;
        $this->aPTag = null;
    }

    /**
     * return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(PLDepartmentPeer::DEFAULT_STRING_FORMAT);
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
     * @return     PLDepartment The current object (for fluent API support)
     */
    public function keepUpdateDateUnchanged()
    {
        $this->modifiedColumns[] = PLDepartmentPeer::UPDATED_AT;

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
    * If permanent UUID, throw exception p_l_department.uuid*/
    public function preUpdate(PropelPDO $con = NULL) {
            $uuid = $this->getUuid();
        if(!is_null($uuid) && !\Ramsey\Uuid\Uuid::isValid($uuid)) {
            throw new \InvalidArgumentException("UUID: $uuid in not valid");
        }
            return true;
    }

}
