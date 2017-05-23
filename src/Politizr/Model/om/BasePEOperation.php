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
use Politizr\Model\PDDebate;
use Politizr\Model\PDDebateQuery;
use Politizr\Model\PEOPresetPT;
use Politizr\Model\PEOPresetPTQuery;
use Politizr\Model\PEOScopePLC;
use Politizr\Model\PEOScopePLCQuery;
use Politizr\Model\PEOperation;
use Politizr\Model\PEOperationArchive;
use Politizr\Model\PEOperationArchiveQuery;
use Politizr\Model\PEOperationPeer;
use Politizr\Model\PEOperationQuery;
use Politizr\Model\PLCity;
use Politizr\Model\PLCityQuery;
use Politizr\Model\PTag;
use Politizr\Model\PTagQuery;
use Politizr\Model\PUser;
use Politizr\Model\PUserQuery;

abstract class BasePEOperation extends BaseObject implements Persistent
{
    /**
     * Peer class name
     */
    const PEER = 'Politizr\\Model\\PEOperationPeer';

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        PEOperationPeer
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
     * The value for the p_user_id field.
     * @var        int
     */
    protected $p_user_id;

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
     * The value for the editing_description field.
     * @var        string
     */
    protected $editing_description;

    /**
     * The value for the file_name field.
     * @var        string
     */
    protected $file_name;

    /**
     * The value for the geo_scoped field.
     * @var        boolean
     */
    protected $geo_scoped;

    /**
     * The value for the online field.
     * @var        boolean
     */
    protected $online;

    /**
     * The value for the timeline field.
     * @var        boolean
     */
    protected $timeline;

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
     * The value for the slug field.
     * @var        string
     */
    protected $slug;

    /**
     * @var        PUser
     */
    protected $aPUser;

    /**
     * @var        PropelObjectCollection|PEOScopePLC[] Collection to store aggregation of PEOScopePLC objects.
     */
    protected $collPEOScopePLCs;
    protected $collPEOScopePLCsPartial;

    /**
     * @var        PropelObjectCollection|PEOPresetPT[] Collection to store aggregation of PEOPresetPT objects.
     */
    protected $collPEOPresetPTs;
    protected $collPEOPresetPTsPartial;

    /**
     * @var        PropelObjectCollection|PDDebate[] Collection to store aggregation of PDDebate objects.
     */
    protected $collPDDebates;
    protected $collPDDebatesPartial;

    /**
     * @var        PropelObjectCollection|PLCity[] Collection to store aggregation of PLCity objects.
     */
    protected $collPLCities;

    /**
     * @var        PropelObjectCollection|PTag[] Collection to store aggregation of PTag objects.
     */
    protected $collPTags;

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

    // archivable behavior
    protected $archiveOnDelete = true;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $pLCitiesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $pTagsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $pEOScopePLCsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $pEOPresetPTsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $pDDebatesScheduledForDeletion = null;

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
     * Get the [p_user_id] column value.
     *
     * @return int
     */
    public function getPUserId()
    {

        return $this->p_user_id;
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
     * Get the [editing_description] column value.
     *
     * @return string
     */
    public function getEditingDescription()
    {

        return $this->editing_description;
    }

    /**
     * Get the [file_name] column value.
     *
     * @return string
     */
    public function getFileName()
    {

        return $this->file_name;
    }

    /**
     * Get the [geo_scoped] column value.
     *
     * @return boolean
     */
    public function getGeoScoped()
    {

        return $this->geo_scoped;
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
     * Get the [timeline] column value.
     *
     * @return boolean
     */
    public function getTimeline()
    {

        return $this->timeline;
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
     * Get the [slug] column value.
     *
     * @return string
     */
    public function getSlug()
    {

        return $this->slug;
    }

    /**
     * Set the value of [id] column.
     *
     * @param  int $v new value
     * @return PEOperation The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[] = PEOperationPeer::ID;
        }


        return $this;
    } // setId()

    /**
     * Set the value of [uuid] column.
     *
     * @param  string $v new value
     * @return PEOperation The current object (for fluent API support)
     */
    public function setUuid($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->uuid !== $v) {
            $this->uuid = $v;
            $this->modifiedColumns[] = PEOperationPeer::UUID;
        }


        return $this;
    } // setUuid()

    /**
     * Set the value of [p_user_id] column.
     *
     * @param  int $v new value
     * @return PEOperation The current object (for fluent API support)
     */
    public function setPUserId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->p_user_id !== $v) {
            $this->p_user_id = $v;
            $this->modifiedColumns[] = PEOperationPeer::P_USER_ID;
        }

        if ($this->aPUser !== null && $this->aPUser->getId() !== $v) {
            $this->aPUser = null;
        }


        return $this;
    } // setPUserId()

    /**
     * Set the value of [title] column.
     *
     * @param  string $v new value
     * @return PEOperation The current object (for fluent API support)
     */
    public function setTitle($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->title !== $v) {
            $this->title = $v;
            $this->modifiedColumns[] = PEOperationPeer::TITLE;
        }


        return $this;
    } // setTitle()

    /**
     * Set the value of [description] column.
     *
     * @param  string $v new value
     * @return PEOperation The current object (for fluent API support)
     */
    public function setDescription($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->description !== $v) {
            $this->description = $v;
            $this->modifiedColumns[] = PEOperationPeer::DESCRIPTION;
        }


        return $this;
    } // setDescription()

    /**
     * Set the value of [editing_description] column.
     *
     * @param  string $v new value
     * @return PEOperation The current object (for fluent API support)
     */
    public function setEditingDescription($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->editing_description !== $v) {
            $this->editing_description = $v;
            $this->modifiedColumns[] = PEOperationPeer::EDITING_DESCRIPTION;
        }


        return $this;
    } // setEditingDescription()

    /**
     * Set the value of [file_name] column.
     *
     * @param  string $v new value
     * @return PEOperation The current object (for fluent API support)
     */
    public function setFileName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->file_name !== $v) {
            $this->file_name = $v;
            $this->modifiedColumns[] = PEOperationPeer::FILE_NAME;
        }


        return $this;
    } // setFileName()

    /**
     * Sets the value of the [geo_scoped] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param boolean|integer|string $v The new value
     * @return PEOperation The current object (for fluent API support)
     */
    public function setGeoScoped($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->geo_scoped !== $v) {
            $this->geo_scoped = $v;
            $this->modifiedColumns[] = PEOperationPeer::GEO_SCOPED;
        }


        return $this;
    } // setGeoScoped()

    /**
     * Sets the value of the [online] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param boolean|integer|string $v The new value
     * @return PEOperation The current object (for fluent API support)
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
            $this->modifiedColumns[] = PEOperationPeer::ONLINE;
        }


        return $this;
    } // setOnline()

    /**
     * Sets the value of the [timeline] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param boolean|integer|string $v The new value
     * @return PEOperation The current object (for fluent API support)
     */
    public function setTimeline($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->timeline !== $v) {
            $this->timeline = $v;
            $this->modifiedColumns[] = PEOperationPeer::TIMELINE;
        }


        return $this;
    } // setTimeline()

    /**
     * Sets the value of [created_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return PEOperation The current object (for fluent API support)
     */
    public function setCreatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->created_at !== null || $dt !== null) {
            $currentDateAsString = ($this->created_at !== null && $tmpDt = new DateTime($this->created_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->created_at = $newDateAsString;
                $this->modifiedColumns[] = PEOperationPeer::CREATED_AT;
            }
        } // if either are not null


        return $this;
    } // setCreatedAt()

    /**
     * Sets the value of [updated_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return PEOperation The current object (for fluent API support)
     */
    public function setUpdatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->updated_at !== null || $dt !== null) {
            $currentDateAsString = ($this->updated_at !== null && $tmpDt = new DateTime($this->updated_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->updated_at = $newDateAsString;
                $this->modifiedColumns[] = PEOperationPeer::UPDATED_AT;
            }
        } // if either are not null


        return $this;
    } // setUpdatedAt()

    /**
     * Set the value of [slug] column.
     *
     * @param  string $v new value
     * @return PEOperation The current object (for fluent API support)
     */
    public function setSlug($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->slug !== $v) {
            $this->slug = $v;
            $this->modifiedColumns[] = PEOperationPeer::SLUG;
        }


        return $this;
    } // setSlug()

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
            $this->p_user_id = ($row[$startcol + 2] !== null) ? (int) $row[$startcol + 2] : null;
            $this->title = ($row[$startcol + 3] !== null) ? (string) $row[$startcol + 3] : null;
            $this->description = ($row[$startcol + 4] !== null) ? (string) $row[$startcol + 4] : null;
            $this->editing_description = ($row[$startcol + 5] !== null) ? (string) $row[$startcol + 5] : null;
            $this->file_name = ($row[$startcol + 6] !== null) ? (string) $row[$startcol + 6] : null;
            $this->geo_scoped = ($row[$startcol + 7] !== null) ? (boolean) $row[$startcol + 7] : null;
            $this->online = ($row[$startcol + 8] !== null) ? (boolean) $row[$startcol + 8] : null;
            $this->timeline = ($row[$startcol + 9] !== null) ? (boolean) $row[$startcol + 9] : null;
            $this->created_at = ($row[$startcol + 10] !== null) ? (string) $row[$startcol + 10] : null;
            $this->updated_at = ($row[$startcol + 11] !== null) ? (string) $row[$startcol + 11] : null;
            $this->slug = ($row[$startcol + 12] !== null) ? (string) $row[$startcol + 12] : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }
            $this->postHydrate($row, $startcol, $rehydrate);

            return $startcol + 13; // 13 = PEOperationPeer::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating PEOperation object", $e);
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

        if ($this->aPUser !== null && $this->p_user_id !== $this->aPUser->getId()) {
            $this->aPUser = null;
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
            $con = Propel::getConnection(PEOperationPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $stmt = PEOperationPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
        $row = $stmt->fetch(PDO::FETCH_NUM);
        $stmt->closeCursor();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aPUser = null;
            $this->collPEOScopePLCs = null;

            $this->collPEOPresetPTs = null;

            $this->collPDDebates = null;

            $this->collPLCities = null;
            $this->collPTags = null;
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
            $con = Propel::getConnection(PEOperationPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        try {
            $deleteQuery = PEOperationQuery::create()
                ->filterByPrimaryKey($this->getPrimaryKey());
            $ret = $this->preDelete($con);
            // archivable behavior
            if ($ret) {
                if ($this->archiveOnDelete) {
                    // do nothing yet. The object will be archived later when calling PEOperationQuery::delete().
                } else {
                    $deleteQuery->setArchiveOnDelete(false);
                    $this->archiveOnDelete = true;
                }
            }

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
            $con = Propel::getConnection(PEOperationPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        $isInsert = $this->isNew();
        try {
            $ret = $this->preSave($con);
            // sluggable behavior

            if ($this->isColumnModified(PEOperationPeer::SLUG) && $this->getSlug()) {
                $this->setSlug($this->makeSlugUnique($this->getSlug()));
            } elseif ($this->isColumnModified(PEOperationPeer::TITLE)) {
                $this->setSlug($this->createSlug());
            } elseif (!$this->getSlug()) {
                $this->setSlug($this->createSlug());
            }
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
                // timestampable behavior
                if (!$this->isColumnModified(PEOperationPeer::CREATED_AT)) {
                    $this->setCreatedAt(time());
                }
                if (!$this->isColumnModified(PEOperationPeer::UPDATED_AT)) {
                    $this->setUpdatedAt(time());
                }
            } else {
                $ret = $ret && $this->preUpdate($con);
                // timestampable behavior
                if ($this->isModified() && !$this->isColumnModified(PEOperationPeer::UPDATED_AT)) {
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
                PEOperationPeer::addInstanceToPool($this);
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

            if ($this->aPUser !== null) {
                if ($this->aPUser->isModified() || $this->aPUser->isNew()) {
                    $affectedRows += $this->aPUser->save($con);
                }
                $this->setPUser($this->aPUser);
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
                    $pks = array();
                    $pk = $this->getPrimaryKey();
                    foreach ($this->pLCitiesScheduledForDeletion->getPrimaryKeys(false) as $remotePk) {
                        $pks[] = array($pk, $remotePk);
                    }
                    PEOScopePLCQuery::create()
                        ->filterByPrimaryKeys($pks)
                        ->delete($con);
                    $this->pLCitiesScheduledForDeletion = null;
                }

                foreach ($this->getPLCities() as $pLCity) {
                    if ($pLCity->isModified()) {
                        $pLCity->save($con);
                    }
                }
            } elseif ($this->collPLCities) {
                foreach ($this->collPLCities as $pLCity) {
                    if ($pLCity->isModified()) {
                        $pLCity->save($con);
                    }
                }
            }

            if ($this->pTagsScheduledForDeletion !== null) {
                if (!$this->pTagsScheduledForDeletion->isEmpty()) {
                    $pks = array();
                    $pk = $this->getPrimaryKey();
                    foreach ($this->pTagsScheduledForDeletion->getPrimaryKeys(false) as $remotePk) {
                        $pks[] = array($pk, $remotePk);
                    }
                    PEOPresetPTQuery::create()
                        ->filterByPrimaryKeys($pks)
                        ->delete($con);
                    $this->pTagsScheduledForDeletion = null;
                }

                foreach ($this->getPTags() as $pTag) {
                    if ($pTag->isModified()) {
                        $pTag->save($con);
                    }
                }
            } elseif ($this->collPTags) {
                foreach ($this->collPTags as $pTag) {
                    if ($pTag->isModified()) {
                        $pTag->save($con);
                    }
                }
            }

            if ($this->pEOScopePLCsScheduledForDeletion !== null) {
                if (!$this->pEOScopePLCsScheduledForDeletion->isEmpty()) {
                    PEOScopePLCQuery::create()
                        ->filterByPrimaryKeys($this->pEOScopePLCsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->pEOScopePLCsScheduledForDeletion = null;
                }
            }

            if ($this->collPEOScopePLCs !== null) {
                foreach ($this->collPEOScopePLCs as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->pEOPresetPTsScheduledForDeletion !== null) {
                if (!$this->pEOPresetPTsScheduledForDeletion->isEmpty()) {
                    PEOPresetPTQuery::create()
                        ->filterByPrimaryKeys($this->pEOPresetPTsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->pEOPresetPTsScheduledForDeletion = null;
                }
            }

            if ($this->collPEOPresetPTs !== null) {
                foreach ($this->collPEOPresetPTs as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->pDDebatesScheduledForDeletion !== null) {
                if (!$this->pDDebatesScheduledForDeletion->isEmpty()) {
                    foreach ($this->pDDebatesScheduledForDeletion as $pDDebate) {
                        // need to save related object because we set the relation to null
                        $pDDebate->save($con);
                    }
                    $this->pDDebatesScheduledForDeletion = null;
                }
            }

            if ($this->collPDDebates !== null) {
                foreach ($this->collPDDebates as $referrerFK) {
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

        $this->modifiedColumns[] = PEOperationPeer::ID;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . PEOperationPeer::ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(PEOperationPeer::ID)) {
            $modifiedColumns[':p' . $index++]  = '`id`';
        }
        if ($this->isColumnModified(PEOperationPeer::UUID)) {
            $modifiedColumns[':p' . $index++]  = '`uuid`';
        }
        if ($this->isColumnModified(PEOperationPeer::P_USER_ID)) {
            $modifiedColumns[':p' . $index++]  = '`p_user_id`';
        }
        if ($this->isColumnModified(PEOperationPeer::TITLE)) {
            $modifiedColumns[':p' . $index++]  = '`title`';
        }
        if ($this->isColumnModified(PEOperationPeer::DESCRIPTION)) {
            $modifiedColumns[':p' . $index++]  = '`description`';
        }
        if ($this->isColumnModified(PEOperationPeer::EDITING_DESCRIPTION)) {
            $modifiedColumns[':p' . $index++]  = '`editing_description`';
        }
        if ($this->isColumnModified(PEOperationPeer::FILE_NAME)) {
            $modifiedColumns[':p' . $index++]  = '`file_name`';
        }
        if ($this->isColumnModified(PEOperationPeer::GEO_SCOPED)) {
            $modifiedColumns[':p' . $index++]  = '`geo_scoped`';
        }
        if ($this->isColumnModified(PEOperationPeer::ONLINE)) {
            $modifiedColumns[':p' . $index++]  = '`online`';
        }
        if ($this->isColumnModified(PEOperationPeer::TIMELINE)) {
            $modifiedColumns[':p' . $index++]  = '`timeline`';
        }
        if ($this->isColumnModified(PEOperationPeer::CREATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`created_at`';
        }
        if ($this->isColumnModified(PEOperationPeer::UPDATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`updated_at`';
        }
        if ($this->isColumnModified(PEOperationPeer::SLUG)) {
            $modifiedColumns[':p' . $index++]  = '`slug`';
        }

        $sql = sprintf(
            'INSERT INTO `p_e_operation` (%s) VALUES (%s)',
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
                    case '`p_user_id`':
                        $stmt->bindValue($identifier, $this->p_user_id, PDO::PARAM_INT);
                        break;
                    case '`title`':
                        $stmt->bindValue($identifier, $this->title, PDO::PARAM_STR);
                        break;
                    case '`description`':
                        $stmt->bindValue($identifier, $this->description, PDO::PARAM_STR);
                        break;
                    case '`editing_description`':
                        $stmt->bindValue($identifier, $this->editing_description, PDO::PARAM_STR);
                        break;
                    case '`file_name`':
                        $stmt->bindValue($identifier, $this->file_name, PDO::PARAM_STR);
                        break;
                    case '`geo_scoped`':
                        $stmt->bindValue($identifier, (int) $this->geo_scoped, PDO::PARAM_INT);
                        break;
                    case '`online`':
                        $stmt->bindValue($identifier, (int) $this->online, PDO::PARAM_INT);
                        break;
                    case '`timeline`':
                        $stmt->bindValue($identifier, (int) $this->timeline, PDO::PARAM_INT);
                        break;
                    case '`created_at`':
                        $stmt->bindValue($identifier, $this->created_at, PDO::PARAM_STR);
                        break;
                    case '`updated_at`':
                        $stmt->bindValue($identifier, $this->updated_at, PDO::PARAM_STR);
                        break;
                    case '`slug`':
                        $stmt->bindValue($identifier, $this->slug, PDO::PARAM_STR);
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
        $pos = PEOperationPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                return $this->getPUserId();
                break;
            case 3:
                return $this->getTitle();
                break;
            case 4:
                return $this->getDescription();
                break;
            case 5:
                return $this->getEditingDescription();
                break;
            case 6:
                return $this->getFileName();
                break;
            case 7:
                return $this->getGeoScoped();
                break;
            case 8:
                return $this->getOnline();
                break;
            case 9:
                return $this->getTimeline();
                break;
            case 10:
                return $this->getCreatedAt();
                break;
            case 11:
                return $this->getUpdatedAt();
                break;
            case 12:
                return $this->getSlug();
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
        if (isset($alreadyDumpedObjects['PEOperation'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['PEOperation'][$this->getPrimaryKey()] = true;
        $keys = PEOperationPeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getUuid(),
            $keys[2] => $this->getPUserId(),
            $keys[3] => $this->getTitle(),
            $keys[4] => $this->getDescription(),
            $keys[5] => $this->getEditingDescription(),
            $keys[6] => $this->getFileName(),
            $keys[7] => $this->getGeoScoped(),
            $keys[8] => $this->getOnline(),
            $keys[9] => $this->getTimeline(),
            $keys[10] => $this->getCreatedAt(),
            $keys[11] => $this->getUpdatedAt(),
            $keys[12] => $this->getSlug(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aPUser) {
                $result['PUser'] = $this->aPUser->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->collPEOScopePLCs) {
                $result['PEOScopePLCs'] = $this->collPEOScopePLCs->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPEOPresetPTs) {
                $result['PEOPresetPTs'] = $this->collPEOPresetPTs->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPDDebates) {
                $result['PDDebates'] = $this->collPDDebates->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
        $pos = PEOperationPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);

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
                $this->setPUserId($value);
                break;
            case 3:
                $this->setTitle($value);
                break;
            case 4:
                $this->setDescription($value);
                break;
            case 5:
                $this->setEditingDescription($value);
                break;
            case 6:
                $this->setFileName($value);
                break;
            case 7:
                $this->setGeoScoped($value);
                break;
            case 8:
                $this->setOnline($value);
                break;
            case 9:
                $this->setTimeline($value);
                break;
            case 10:
                $this->setCreatedAt($value);
                break;
            case 11:
                $this->setUpdatedAt($value);
                break;
            case 12:
                $this->setSlug($value);
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
        $keys = PEOperationPeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setUuid($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setPUserId($arr[$keys[2]]);
        if (array_key_exists($keys[3], $arr)) $this->setTitle($arr[$keys[3]]);
        if (array_key_exists($keys[4], $arr)) $this->setDescription($arr[$keys[4]]);
        if (array_key_exists($keys[5], $arr)) $this->setEditingDescription($arr[$keys[5]]);
        if (array_key_exists($keys[6], $arr)) $this->setFileName($arr[$keys[6]]);
        if (array_key_exists($keys[7], $arr)) $this->setGeoScoped($arr[$keys[7]]);
        if (array_key_exists($keys[8], $arr)) $this->setOnline($arr[$keys[8]]);
        if (array_key_exists($keys[9], $arr)) $this->setTimeline($arr[$keys[9]]);
        if (array_key_exists($keys[10], $arr)) $this->setCreatedAt($arr[$keys[10]]);
        if (array_key_exists($keys[11], $arr)) $this->setUpdatedAt($arr[$keys[11]]);
        if (array_key_exists($keys[12], $arr)) $this->setSlug($arr[$keys[12]]);
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(PEOperationPeer::DATABASE_NAME);

        if ($this->isColumnModified(PEOperationPeer::ID)) $criteria->add(PEOperationPeer::ID, $this->id);
        if ($this->isColumnModified(PEOperationPeer::UUID)) $criteria->add(PEOperationPeer::UUID, $this->uuid);
        if ($this->isColumnModified(PEOperationPeer::P_USER_ID)) $criteria->add(PEOperationPeer::P_USER_ID, $this->p_user_id);
        if ($this->isColumnModified(PEOperationPeer::TITLE)) $criteria->add(PEOperationPeer::TITLE, $this->title);
        if ($this->isColumnModified(PEOperationPeer::DESCRIPTION)) $criteria->add(PEOperationPeer::DESCRIPTION, $this->description);
        if ($this->isColumnModified(PEOperationPeer::EDITING_DESCRIPTION)) $criteria->add(PEOperationPeer::EDITING_DESCRIPTION, $this->editing_description);
        if ($this->isColumnModified(PEOperationPeer::FILE_NAME)) $criteria->add(PEOperationPeer::FILE_NAME, $this->file_name);
        if ($this->isColumnModified(PEOperationPeer::GEO_SCOPED)) $criteria->add(PEOperationPeer::GEO_SCOPED, $this->geo_scoped);
        if ($this->isColumnModified(PEOperationPeer::ONLINE)) $criteria->add(PEOperationPeer::ONLINE, $this->online);
        if ($this->isColumnModified(PEOperationPeer::TIMELINE)) $criteria->add(PEOperationPeer::TIMELINE, $this->timeline);
        if ($this->isColumnModified(PEOperationPeer::CREATED_AT)) $criteria->add(PEOperationPeer::CREATED_AT, $this->created_at);
        if ($this->isColumnModified(PEOperationPeer::UPDATED_AT)) $criteria->add(PEOperationPeer::UPDATED_AT, $this->updated_at);
        if ($this->isColumnModified(PEOperationPeer::SLUG)) $criteria->add(PEOperationPeer::SLUG, $this->slug);

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
        $criteria = new Criteria(PEOperationPeer::DATABASE_NAME);
        $criteria->add(PEOperationPeer::ID, $this->id);

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
     * @param object $copyObj An object of PEOperation (or compatible) type.
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setUuid($this->getUuid());
        $copyObj->setPUserId($this->getPUserId());
        $copyObj->setTitle($this->getTitle());
        $copyObj->setDescription($this->getDescription());
        $copyObj->setEditingDescription($this->getEditingDescription());
        $copyObj->setFileName($this->getFileName());
        $copyObj->setGeoScoped($this->getGeoScoped());
        $copyObj->setOnline($this->getOnline());
        $copyObj->setTimeline($this->getTimeline());
        $copyObj->setCreatedAt($this->getCreatedAt());
        $copyObj->setUpdatedAt($this->getUpdatedAt());
        $copyObj->setSlug($this->getSlug());

        if ($deepCopy && !$this->startCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);
            // store object hash to prevent cycle
            $this->startCopy = true;

            foreach ($this->getPEOScopePLCs() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPEOScopePLC($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPEOPresetPTs() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPEOPresetPT($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPDDebates() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPDDebate($relObj->copy($deepCopy));
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
     * @return PEOperation Clone of current object.
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
     * @return PEOperationPeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new PEOperationPeer();
        }

        return self::$peer;
    }

    /**
     * Declares an association between this object and a PUser object.
     *
     * @param                  PUser $v
     * @return PEOperation The current object (for fluent API support)
     * @throws PropelException
     */
    public function setPUser(PUser $v = null)
    {
        if ($v === null) {
            $this->setPUserId(NULL);
        } else {
            $this->setPUserId($v->getId());
        }

        $this->aPUser = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the PUser object, it will not be re-added.
        if ($v !== null) {
            $v->addPEOperation($this);
        }


        return $this;
    }


    /**
     * Get the associated PUser object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return PUser The associated PUser object.
     * @throws PropelException
     */
    public function getPUser(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aPUser === null && ($this->p_user_id !== null) && $doQuery) {
            $this->aPUser = PUserQuery::create()->findPk($this->p_user_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aPUser->addPEOperations($this);
             */
        }

        return $this->aPUser;
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
        if ('PEOScopePLC' == $relationName) {
            $this->initPEOScopePLCs();
        }
        if ('PEOPresetPT' == $relationName) {
            $this->initPEOPresetPTs();
        }
        if ('PDDebate' == $relationName) {
            $this->initPDDebates();
        }
    }

    /**
     * Clears out the collPEOScopePLCs collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return PEOperation The current object (for fluent API support)
     * @see        addPEOScopePLCs()
     */
    public function clearPEOScopePLCs()
    {
        $this->collPEOScopePLCs = null; // important to set this to null since that means it is uninitialized
        $this->collPEOScopePLCsPartial = null;

        return $this;
    }

    /**
     * reset is the collPEOScopePLCs collection loaded partially
     *
     * @return void
     */
    public function resetPartialPEOScopePLCs($v = true)
    {
        $this->collPEOScopePLCsPartial = $v;
    }

    /**
     * Initializes the collPEOScopePLCs collection.
     *
     * By default this just sets the collPEOScopePLCs collection to an empty array (like clearcollPEOScopePLCs());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPEOScopePLCs($overrideExisting = true)
    {
        if (null !== $this->collPEOScopePLCs && !$overrideExisting) {
            return;
        }
        $this->collPEOScopePLCs = new PropelObjectCollection();
        $this->collPEOScopePLCs->setModel('PEOScopePLC');
    }

    /**
     * Gets an array of PEOScopePLC objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this PEOperation is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|PEOScopePLC[] List of PEOScopePLC objects
     * @throws PropelException
     */
    public function getPEOScopePLCs($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collPEOScopePLCsPartial && !$this->isNew();
        if (null === $this->collPEOScopePLCs || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPEOScopePLCs) {
                // return empty collection
                $this->initPEOScopePLCs();
            } else {
                $collPEOScopePLCs = PEOScopePLCQuery::create(null, $criteria)
                    ->filterByPEOperation($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collPEOScopePLCsPartial && count($collPEOScopePLCs)) {
                      $this->initPEOScopePLCs(false);

                      foreach ($collPEOScopePLCs as $obj) {
                        if (false == $this->collPEOScopePLCs->contains($obj)) {
                          $this->collPEOScopePLCs->append($obj);
                        }
                      }

                      $this->collPEOScopePLCsPartial = true;
                    }

                    $collPEOScopePLCs->getInternalIterator()->rewind();

                    return $collPEOScopePLCs;
                }

                if ($partial && $this->collPEOScopePLCs) {
                    foreach ($this->collPEOScopePLCs as $obj) {
                        if ($obj->isNew()) {
                            $collPEOScopePLCs[] = $obj;
                        }
                    }
                }

                $this->collPEOScopePLCs = $collPEOScopePLCs;
                $this->collPEOScopePLCsPartial = false;
            }
        }

        return $this->collPEOScopePLCs;
    }

    /**
     * Sets a collection of PEOScopePLC objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $pEOScopePLCs A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return PEOperation The current object (for fluent API support)
     */
    public function setPEOScopePLCs(PropelCollection $pEOScopePLCs, PropelPDO $con = null)
    {
        $pEOScopePLCsToDelete = $this->getPEOScopePLCs(new Criteria(), $con)->diff($pEOScopePLCs);


        $this->pEOScopePLCsScheduledForDeletion = $pEOScopePLCsToDelete;

        foreach ($pEOScopePLCsToDelete as $pEOScopePLCRemoved) {
            $pEOScopePLCRemoved->setPEOperation(null);
        }

        $this->collPEOScopePLCs = null;
        foreach ($pEOScopePLCs as $pEOScopePLC) {
            $this->addPEOScopePLC($pEOScopePLC);
        }

        $this->collPEOScopePLCs = $pEOScopePLCs;
        $this->collPEOScopePLCsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related PEOScopePLC objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related PEOScopePLC objects.
     * @throws PropelException
     */
    public function countPEOScopePLCs(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collPEOScopePLCsPartial && !$this->isNew();
        if (null === $this->collPEOScopePLCs || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPEOScopePLCs) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPEOScopePLCs());
            }
            $query = PEOScopePLCQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPEOperation($this)
                ->count($con);
        }

        return count($this->collPEOScopePLCs);
    }

    /**
     * Method called to associate a PEOScopePLC object to this object
     * through the PEOScopePLC foreign key attribute.
     *
     * @param    PEOScopePLC $l PEOScopePLC
     * @return PEOperation The current object (for fluent API support)
     */
    public function addPEOScopePLC(PEOScopePLC $l)
    {
        if ($this->collPEOScopePLCs === null) {
            $this->initPEOScopePLCs();
            $this->collPEOScopePLCsPartial = true;
        }

        if (!in_array($l, $this->collPEOScopePLCs->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddPEOScopePLC($l);

            if ($this->pEOScopePLCsScheduledForDeletion and $this->pEOScopePLCsScheduledForDeletion->contains($l)) {
                $this->pEOScopePLCsScheduledForDeletion->remove($this->pEOScopePLCsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	PEOScopePLC $pEOScopePLC The pEOScopePLC object to add.
     */
    protected function doAddPEOScopePLC($pEOScopePLC)
    {
        $this->collPEOScopePLCs[]= $pEOScopePLC;
        $pEOScopePLC->setPEOperation($this);
    }

    /**
     * @param	PEOScopePLC $pEOScopePLC The pEOScopePLC object to remove.
     * @return PEOperation The current object (for fluent API support)
     */
    public function removePEOScopePLC($pEOScopePLC)
    {
        if ($this->getPEOScopePLCs()->contains($pEOScopePLC)) {
            $this->collPEOScopePLCs->remove($this->collPEOScopePLCs->search($pEOScopePLC));
            if (null === $this->pEOScopePLCsScheduledForDeletion) {
                $this->pEOScopePLCsScheduledForDeletion = clone $this->collPEOScopePLCs;
                $this->pEOScopePLCsScheduledForDeletion->clear();
            }
            $this->pEOScopePLCsScheduledForDeletion[]= clone $pEOScopePLC;
            $pEOScopePLC->setPEOperation(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this PEOperation is new, it will return
     * an empty collection; or if this PEOperation has previously
     * been saved, it will retrieve related PEOScopePLCs from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in PEOperation.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|PEOScopePLC[] List of PEOScopePLC objects
     */
    public function getPEOScopePLCsJoinPLCity($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PEOScopePLCQuery::create(null, $criteria);
        $query->joinWith('PLCity', $join_behavior);

        return $this->getPEOScopePLCs($query, $con);
    }

    /**
     * Clears out the collPEOPresetPTs collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return PEOperation The current object (for fluent API support)
     * @see        addPEOPresetPTs()
     */
    public function clearPEOPresetPTs()
    {
        $this->collPEOPresetPTs = null; // important to set this to null since that means it is uninitialized
        $this->collPEOPresetPTsPartial = null;

        return $this;
    }

    /**
     * reset is the collPEOPresetPTs collection loaded partially
     *
     * @return void
     */
    public function resetPartialPEOPresetPTs($v = true)
    {
        $this->collPEOPresetPTsPartial = $v;
    }

    /**
     * Initializes the collPEOPresetPTs collection.
     *
     * By default this just sets the collPEOPresetPTs collection to an empty array (like clearcollPEOPresetPTs());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPEOPresetPTs($overrideExisting = true)
    {
        if (null !== $this->collPEOPresetPTs && !$overrideExisting) {
            return;
        }
        $this->collPEOPresetPTs = new PropelObjectCollection();
        $this->collPEOPresetPTs->setModel('PEOPresetPT');
    }

    /**
     * Gets an array of PEOPresetPT objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this PEOperation is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|PEOPresetPT[] List of PEOPresetPT objects
     * @throws PropelException
     */
    public function getPEOPresetPTs($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collPEOPresetPTsPartial && !$this->isNew();
        if (null === $this->collPEOPresetPTs || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPEOPresetPTs) {
                // return empty collection
                $this->initPEOPresetPTs();
            } else {
                $collPEOPresetPTs = PEOPresetPTQuery::create(null, $criteria)
                    ->filterByPEOperation($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collPEOPresetPTsPartial && count($collPEOPresetPTs)) {
                      $this->initPEOPresetPTs(false);

                      foreach ($collPEOPresetPTs as $obj) {
                        if (false == $this->collPEOPresetPTs->contains($obj)) {
                          $this->collPEOPresetPTs->append($obj);
                        }
                      }

                      $this->collPEOPresetPTsPartial = true;
                    }

                    $collPEOPresetPTs->getInternalIterator()->rewind();

                    return $collPEOPresetPTs;
                }

                if ($partial && $this->collPEOPresetPTs) {
                    foreach ($this->collPEOPresetPTs as $obj) {
                        if ($obj->isNew()) {
                            $collPEOPresetPTs[] = $obj;
                        }
                    }
                }

                $this->collPEOPresetPTs = $collPEOPresetPTs;
                $this->collPEOPresetPTsPartial = false;
            }
        }

        return $this->collPEOPresetPTs;
    }

    /**
     * Sets a collection of PEOPresetPT objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $pEOPresetPTs A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return PEOperation The current object (for fluent API support)
     */
    public function setPEOPresetPTs(PropelCollection $pEOPresetPTs, PropelPDO $con = null)
    {
        $pEOPresetPTsToDelete = $this->getPEOPresetPTs(new Criteria(), $con)->diff($pEOPresetPTs);


        $this->pEOPresetPTsScheduledForDeletion = $pEOPresetPTsToDelete;

        foreach ($pEOPresetPTsToDelete as $pEOPresetPTRemoved) {
            $pEOPresetPTRemoved->setPEOperation(null);
        }

        $this->collPEOPresetPTs = null;
        foreach ($pEOPresetPTs as $pEOPresetPT) {
            $this->addPEOPresetPT($pEOPresetPT);
        }

        $this->collPEOPresetPTs = $pEOPresetPTs;
        $this->collPEOPresetPTsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related PEOPresetPT objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related PEOPresetPT objects.
     * @throws PropelException
     */
    public function countPEOPresetPTs(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collPEOPresetPTsPartial && !$this->isNew();
        if (null === $this->collPEOPresetPTs || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPEOPresetPTs) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPEOPresetPTs());
            }
            $query = PEOPresetPTQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPEOperation($this)
                ->count($con);
        }

        return count($this->collPEOPresetPTs);
    }

    /**
     * Method called to associate a PEOPresetPT object to this object
     * through the PEOPresetPT foreign key attribute.
     *
     * @param    PEOPresetPT $l PEOPresetPT
     * @return PEOperation The current object (for fluent API support)
     */
    public function addPEOPresetPT(PEOPresetPT $l)
    {
        if ($this->collPEOPresetPTs === null) {
            $this->initPEOPresetPTs();
            $this->collPEOPresetPTsPartial = true;
        }

        if (!in_array($l, $this->collPEOPresetPTs->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddPEOPresetPT($l);

            if ($this->pEOPresetPTsScheduledForDeletion and $this->pEOPresetPTsScheduledForDeletion->contains($l)) {
                $this->pEOPresetPTsScheduledForDeletion->remove($this->pEOPresetPTsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	PEOPresetPT $pEOPresetPT The pEOPresetPT object to add.
     */
    protected function doAddPEOPresetPT($pEOPresetPT)
    {
        $this->collPEOPresetPTs[]= $pEOPresetPT;
        $pEOPresetPT->setPEOperation($this);
    }

    /**
     * @param	PEOPresetPT $pEOPresetPT The pEOPresetPT object to remove.
     * @return PEOperation The current object (for fluent API support)
     */
    public function removePEOPresetPT($pEOPresetPT)
    {
        if ($this->getPEOPresetPTs()->contains($pEOPresetPT)) {
            $this->collPEOPresetPTs->remove($this->collPEOPresetPTs->search($pEOPresetPT));
            if (null === $this->pEOPresetPTsScheduledForDeletion) {
                $this->pEOPresetPTsScheduledForDeletion = clone $this->collPEOPresetPTs;
                $this->pEOPresetPTsScheduledForDeletion->clear();
            }
            $this->pEOPresetPTsScheduledForDeletion[]= clone $pEOPresetPT;
            $pEOPresetPT->setPEOperation(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this PEOperation is new, it will return
     * an empty collection; or if this PEOperation has previously
     * been saved, it will retrieve related PEOPresetPTs from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in PEOperation.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|PEOPresetPT[] List of PEOPresetPT objects
     */
    public function getPEOPresetPTsJoinPTag($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PEOPresetPTQuery::create(null, $criteria);
        $query->joinWith('PTag', $join_behavior);

        return $this->getPEOPresetPTs($query, $con);
    }

    /**
     * Clears out the collPDDebates collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return PEOperation The current object (for fluent API support)
     * @see        addPDDebates()
     */
    public function clearPDDebates()
    {
        $this->collPDDebates = null; // important to set this to null since that means it is uninitialized
        $this->collPDDebatesPartial = null;

        return $this;
    }

    /**
     * reset is the collPDDebates collection loaded partially
     *
     * @return void
     */
    public function resetPartialPDDebates($v = true)
    {
        $this->collPDDebatesPartial = $v;
    }

    /**
     * Initializes the collPDDebates collection.
     *
     * By default this just sets the collPDDebates collection to an empty array (like clearcollPDDebates());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPDDebates($overrideExisting = true)
    {
        if (null !== $this->collPDDebates && !$overrideExisting) {
            return;
        }
        $this->collPDDebates = new PropelObjectCollection();
        $this->collPDDebates->setModel('PDDebate');
    }

    /**
     * Gets an array of PDDebate objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this PEOperation is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|PDDebate[] List of PDDebate objects
     * @throws PropelException
     */
    public function getPDDebates($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collPDDebatesPartial && !$this->isNew();
        if (null === $this->collPDDebates || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPDDebates) {
                // return empty collection
                $this->initPDDebates();
            } else {
                $collPDDebates = PDDebateQuery::create(null, $criteria)
                    ->filterByPEOperation($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collPDDebatesPartial && count($collPDDebates)) {
                      $this->initPDDebates(false);

                      foreach ($collPDDebates as $obj) {
                        if (false == $this->collPDDebates->contains($obj)) {
                          $this->collPDDebates->append($obj);
                        }
                      }

                      $this->collPDDebatesPartial = true;
                    }

                    $collPDDebates->getInternalIterator()->rewind();

                    return $collPDDebates;
                }

                if ($partial && $this->collPDDebates) {
                    foreach ($this->collPDDebates as $obj) {
                        if ($obj->isNew()) {
                            $collPDDebates[] = $obj;
                        }
                    }
                }

                $this->collPDDebates = $collPDDebates;
                $this->collPDDebatesPartial = false;
            }
        }

        return $this->collPDDebates;
    }

    /**
     * Sets a collection of PDDebate objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $pDDebates A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return PEOperation The current object (for fluent API support)
     */
    public function setPDDebates(PropelCollection $pDDebates, PropelPDO $con = null)
    {
        $pDDebatesToDelete = $this->getPDDebates(new Criteria(), $con)->diff($pDDebates);


        $this->pDDebatesScheduledForDeletion = $pDDebatesToDelete;

        foreach ($pDDebatesToDelete as $pDDebateRemoved) {
            $pDDebateRemoved->setPEOperation(null);
        }

        $this->collPDDebates = null;
        foreach ($pDDebates as $pDDebate) {
            $this->addPDDebate($pDDebate);
        }

        $this->collPDDebates = $pDDebates;
        $this->collPDDebatesPartial = false;

        return $this;
    }

    /**
     * Returns the number of related PDDebate objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related PDDebate objects.
     * @throws PropelException
     */
    public function countPDDebates(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collPDDebatesPartial && !$this->isNew();
        if (null === $this->collPDDebates || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPDDebates) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPDDebates());
            }
            $query = PDDebateQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPEOperation($this)
                ->count($con);
        }

        return count($this->collPDDebates);
    }

    /**
     * Method called to associate a PDDebate object to this object
     * through the PDDebate foreign key attribute.
     *
     * @param    PDDebate $l PDDebate
     * @return PEOperation The current object (for fluent API support)
     */
    public function addPDDebate(PDDebate $l)
    {
        if ($this->collPDDebates === null) {
            $this->initPDDebates();
            $this->collPDDebatesPartial = true;
        }

        if (!in_array($l, $this->collPDDebates->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddPDDebate($l);

            if ($this->pDDebatesScheduledForDeletion and $this->pDDebatesScheduledForDeletion->contains($l)) {
                $this->pDDebatesScheduledForDeletion->remove($this->pDDebatesScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	PDDebate $pDDebate The pDDebate object to add.
     */
    protected function doAddPDDebate($pDDebate)
    {
        $this->collPDDebates[]= $pDDebate;
        $pDDebate->setPEOperation($this);
    }

    /**
     * @param	PDDebate $pDDebate The pDDebate object to remove.
     * @return PEOperation The current object (for fluent API support)
     */
    public function removePDDebate($pDDebate)
    {
        if ($this->getPDDebates()->contains($pDDebate)) {
            $this->collPDDebates->remove($this->collPDDebates->search($pDDebate));
            if (null === $this->pDDebatesScheduledForDeletion) {
                $this->pDDebatesScheduledForDeletion = clone $this->collPDDebates;
                $this->pDDebatesScheduledForDeletion->clear();
            }
            $this->pDDebatesScheduledForDeletion[]= $pDDebate;
            $pDDebate->setPEOperation(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this PEOperation is new, it will return
     * an empty collection; or if this PEOperation has previously
     * been saved, it will retrieve related PDDebates from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in PEOperation.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|PDDebate[] List of PDDebate objects
     */
    public function getPDDebatesJoinPUser($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PDDebateQuery::create(null, $criteria);
        $query->joinWith('PUser', $join_behavior);

        return $this->getPDDebates($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this PEOperation is new, it will return
     * an empty collection; or if this PEOperation has previously
     * been saved, it will retrieve related PDDebates from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in PEOperation.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|PDDebate[] List of PDDebate objects
     */
    public function getPDDebatesJoinPLCity($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PDDebateQuery::create(null, $criteria);
        $query->joinWith('PLCity', $join_behavior);

        return $this->getPDDebates($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this PEOperation is new, it will return
     * an empty collection; or if this PEOperation has previously
     * been saved, it will retrieve related PDDebates from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in PEOperation.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|PDDebate[] List of PDDebate objects
     */
    public function getPDDebatesJoinPLDepartment($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PDDebateQuery::create(null, $criteria);
        $query->joinWith('PLDepartment', $join_behavior);

        return $this->getPDDebates($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this PEOperation is new, it will return
     * an empty collection; or if this PEOperation has previously
     * been saved, it will retrieve related PDDebates from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in PEOperation.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|PDDebate[] List of PDDebate objects
     */
    public function getPDDebatesJoinPLRegion($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PDDebateQuery::create(null, $criteria);
        $query->joinWith('PLRegion', $join_behavior);

        return $this->getPDDebates($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this PEOperation is new, it will return
     * an empty collection; or if this PEOperation has previously
     * been saved, it will retrieve related PDDebates from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in PEOperation.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|PDDebate[] List of PDDebate objects
     */
    public function getPDDebatesJoinPLCountry($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PDDebateQuery::create(null, $criteria);
        $query->joinWith('PLCountry', $join_behavior);

        return $this->getPDDebates($query, $con);
    }

    /**
     * Clears out the collPLCities collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return PEOperation The current object (for fluent API support)
     * @see        addPLCities()
     */
    public function clearPLCities()
    {
        $this->collPLCities = null; // important to set this to null since that means it is uninitialized
        $this->collPLCitiesPartial = null;

        return $this;
    }

    /**
     * Initializes the collPLCities collection.
     *
     * By default this just sets the collPLCities collection to an empty collection (like clearPLCities());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @return void
     */
    public function initPLCities()
    {
        $this->collPLCities = new PropelObjectCollection();
        $this->collPLCities->setModel('PLCity');
    }

    /**
     * Gets a collection of PLCity objects related by a many-to-many relationship
     * to the current object by way of the p_e_o_scope_p_l_c cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this PEOperation is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria Optional query object to filter the query
     * @param PropelPDO $con Optional connection object
     *
     * @return PropelObjectCollection|PLCity[] List of PLCity objects
     */
    public function getPLCities($criteria = null, PropelPDO $con = null)
    {
        if (null === $this->collPLCities || null !== $criteria) {
            if ($this->isNew() && null === $this->collPLCities) {
                // return empty collection
                $this->initPLCities();
            } else {
                $collPLCities = PLCityQuery::create(null, $criteria)
                    ->filterByPEOperation($this)
                    ->find($con);
                if (null !== $criteria) {
                    return $collPLCities;
                }
                $this->collPLCities = $collPLCities;
            }
        }

        return $this->collPLCities;
    }

    /**
     * Sets a collection of PLCity objects related by a many-to-many relationship
     * to the current object by way of the p_e_o_scope_p_l_c cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $pLCities A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return PEOperation The current object (for fluent API support)
     */
    public function setPLCities(PropelCollection $pLCities, PropelPDO $con = null)
    {
        $this->clearPLCities();
        $currentPLCities = $this->getPLCities(null, $con);

        $this->pLCitiesScheduledForDeletion = $currentPLCities->diff($pLCities);

        foreach ($pLCities as $pLCity) {
            if (!$currentPLCities->contains($pLCity)) {
                $this->doAddPLCity($pLCity);
            }
        }

        $this->collPLCities = $pLCities;

        return $this;
    }

    /**
     * Gets the number of PLCity objects related by a many-to-many relationship
     * to the current object by way of the p_e_o_scope_p_l_c cross-reference table.
     *
     * @param Criteria $criteria Optional query object to filter the query
     * @param boolean $distinct Set to true to force count distinct
     * @param PropelPDO $con Optional connection object
     *
     * @return int the number of related PLCity objects
     */
    public function countPLCities($criteria = null, $distinct = false, PropelPDO $con = null)
    {
        if (null === $this->collPLCities || null !== $criteria) {
            if ($this->isNew() && null === $this->collPLCities) {
                return 0;
            } else {
                $query = PLCityQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByPEOperation($this)
                    ->count($con);
            }
        } else {
            return count($this->collPLCities);
        }
    }

    /**
     * Associate a PLCity object to this object
     * through the p_e_o_scope_p_l_c cross reference table.
     *
     * @param  PLCity $pLCity The PEOScopePLC object to relate
     * @return PEOperation The current object (for fluent API support)
     */
    public function addPLCity(PLCity $pLCity)
    {
        if ($this->collPLCities === null) {
            $this->initPLCities();
        }

        if (!$this->collPLCities->contains($pLCity)) { // only add it if the **same** object is not already associated
            $this->doAddPLCity($pLCity);
            $this->collPLCities[] = $pLCity;

            if ($this->pLCitiesScheduledForDeletion and $this->pLCitiesScheduledForDeletion->contains($pLCity)) {
                $this->pLCitiesScheduledForDeletion->remove($this->pLCitiesScheduledForDeletion->search($pLCity));
            }
        }

        return $this;
    }

    /**
     * @param	PLCity $pLCity The pLCity object to add.
     */
    protected function doAddPLCity(PLCity $pLCity)
    {
        // set the back reference to this object directly as using provided method either results
        // in endless loop or in multiple relations
        if (!$pLCity->getPEOperations()->contains($this)) { $pEOScopePLC = new PEOScopePLC();
            $pEOScopePLC->setPLCity($pLCity);
            $this->addPEOScopePLC($pEOScopePLC);

            $foreignCollection = $pLCity->getPEOperations();
            $foreignCollection[] = $this;
        }
    }

    /**
     * Remove a PLCity object to this object
     * through the p_e_o_scope_p_l_c cross reference table.
     *
     * @param PLCity $pLCity The PEOScopePLC object to relate
     * @return PEOperation The current object (for fluent API support)
     */
    public function removePLCity(PLCity $pLCity)
    {
        if ($this->getPLCities()->contains($pLCity)) {
            $this->collPLCities->remove($this->collPLCities->search($pLCity));
            if (null === $this->pLCitiesScheduledForDeletion) {
                $this->pLCitiesScheduledForDeletion = clone $this->collPLCities;
                $this->pLCitiesScheduledForDeletion->clear();
            }
            $this->pLCitiesScheduledForDeletion[]= $pLCity;
        }

        return $this;
    }

    /**
     * Clears out the collPTags collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return PEOperation The current object (for fluent API support)
     * @see        addPTags()
     */
    public function clearPTags()
    {
        $this->collPTags = null; // important to set this to null since that means it is uninitialized
        $this->collPTagsPartial = null;

        return $this;
    }

    /**
     * Initializes the collPTags collection.
     *
     * By default this just sets the collPTags collection to an empty collection (like clearPTags());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @return void
     */
    public function initPTags()
    {
        $this->collPTags = new PropelObjectCollection();
        $this->collPTags->setModel('PTag');
    }

    /**
     * Gets a collection of PTag objects related by a many-to-many relationship
     * to the current object by way of the p_e_o_preset_p_t cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this PEOperation is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria Optional query object to filter the query
     * @param PropelPDO $con Optional connection object
     *
     * @return PropelObjectCollection|PTag[] List of PTag objects
     */
    public function getPTags($criteria = null, PropelPDO $con = null)
    {
        if (null === $this->collPTags || null !== $criteria) {
            if ($this->isNew() && null === $this->collPTags) {
                // return empty collection
                $this->initPTags();
            } else {
                $collPTags = PTagQuery::create(null, $criteria)
                    ->filterByPEOperation($this)
                    ->find($con);
                if (null !== $criteria) {
                    return $collPTags;
                }
                $this->collPTags = $collPTags;
            }
        }

        return $this->collPTags;
    }

    /**
     * Sets a collection of PTag objects related by a many-to-many relationship
     * to the current object by way of the p_e_o_preset_p_t cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $pTags A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return PEOperation The current object (for fluent API support)
     */
    public function setPTags(PropelCollection $pTags, PropelPDO $con = null)
    {
        $this->clearPTags();
        $currentPTags = $this->getPTags(null, $con);

        $this->pTagsScheduledForDeletion = $currentPTags->diff($pTags);

        foreach ($pTags as $pTag) {
            if (!$currentPTags->contains($pTag)) {
                $this->doAddPTag($pTag);
            }
        }

        $this->collPTags = $pTags;

        return $this;
    }

    /**
     * Gets the number of PTag objects related by a many-to-many relationship
     * to the current object by way of the p_e_o_preset_p_t cross-reference table.
     *
     * @param Criteria $criteria Optional query object to filter the query
     * @param boolean $distinct Set to true to force count distinct
     * @param PropelPDO $con Optional connection object
     *
     * @return int the number of related PTag objects
     */
    public function countPTags($criteria = null, $distinct = false, PropelPDO $con = null)
    {
        if (null === $this->collPTags || null !== $criteria) {
            if ($this->isNew() && null === $this->collPTags) {
                return 0;
            } else {
                $query = PTagQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByPEOperation($this)
                    ->count($con);
            }
        } else {
            return count($this->collPTags);
        }
    }

    /**
     * Associate a PTag object to this object
     * through the p_e_o_preset_p_t cross reference table.
     *
     * @param  PTag $pTag The PEOPresetPT object to relate
     * @return PEOperation The current object (for fluent API support)
     */
    public function addPTag(PTag $pTag)
    {
        if ($this->collPTags === null) {
            $this->initPTags();
        }

        if (!$this->collPTags->contains($pTag)) { // only add it if the **same** object is not already associated
            $this->doAddPTag($pTag);
            $this->collPTags[] = $pTag;

            if ($this->pTagsScheduledForDeletion and $this->pTagsScheduledForDeletion->contains($pTag)) {
                $this->pTagsScheduledForDeletion->remove($this->pTagsScheduledForDeletion->search($pTag));
            }
        }

        return $this;
    }

    /**
     * @param	PTag $pTag The pTag object to add.
     */
    protected function doAddPTag(PTag $pTag)
    {
        // set the back reference to this object directly as using provided method either results
        // in endless loop or in multiple relations
        if (!$pTag->getPEOperations()->contains($this)) { $pEOPresetPT = new PEOPresetPT();
            $pEOPresetPT->setPTag($pTag);
            $this->addPEOPresetPT($pEOPresetPT);

            $foreignCollection = $pTag->getPEOperations();
            $foreignCollection[] = $this;
        }
    }

    /**
     * Remove a PTag object to this object
     * through the p_e_o_preset_p_t cross reference table.
     *
     * @param PTag $pTag The PEOPresetPT object to relate
     * @return PEOperation The current object (for fluent API support)
     */
    public function removePTag(PTag $pTag)
    {
        if ($this->getPTags()->contains($pTag)) {
            $this->collPTags->remove($this->collPTags->search($pTag));
            if (null === $this->pTagsScheduledForDeletion) {
                $this->pTagsScheduledForDeletion = clone $this->collPTags;
                $this->pTagsScheduledForDeletion->clear();
            }
            $this->pTagsScheduledForDeletion[]= $pTag;
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
        $this->p_user_id = null;
        $this->title = null;
        $this->description = null;
        $this->editing_description = null;
        $this->file_name = null;
        $this->geo_scoped = null;
        $this->online = null;
        $this->timeline = null;
        $this->created_at = null;
        $this->updated_at = null;
        $this->slug = null;
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
            if ($this->collPEOScopePLCs) {
                foreach ($this->collPEOScopePLCs as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPEOPresetPTs) {
                foreach ($this->collPEOPresetPTs as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPDDebates) {
                foreach ($this->collPDDebates as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPLCities) {
                foreach ($this->collPLCities as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPTags) {
                foreach ($this->collPTags as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->aPUser instanceof Persistent) {
              $this->aPUser->clearAllReferences($deep);
            }

            $this->alreadyInClearAllReferencesDeep = false;
        } // if ($deep)

        if ($this->collPEOScopePLCs instanceof PropelCollection) {
            $this->collPEOScopePLCs->clearIterator();
        }
        $this->collPEOScopePLCs = null;
        if ($this->collPEOPresetPTs instanceof PropelCollection) {
            $this->collPEOPresetPTs->clearIterator();
        }
        $this->collPEOPresetPTs = null;
        if ($this->collPDDebates instanceof PropelCollection) {
            $this->collPDDebates->clearIterator();
        }
        $this->collPDDebates = null;
        if ($this->collPLCities instanceof PropelCollection) {
            $this->collPLCities->clearIterator();
        }
        $this->collPLCities = null;
        if ($this->collPTags instanceof PropelCollection) {
            $this->collPTags->clearIterator();
        }
        $this->collPTags = null;
        $this->aPUser = null;
    }

    /**
     * return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(PEOperationPeer::DEFAULT_STRING_FORMAT);
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
     * @return     PEOperation The current object (for fluent API support)
     */
    public function keepUpdateDateUnchanged()
    {
        $this->modifiedColumns[] = PEOperationPeer::UPDATED_AT;

        return $this;
    }

    // sluggable behavior

    /**
     * Create a unique slug based on the object
     *
     * @return string The object slug
     */
    protected function createSlug()
    {
        $slug = $this->createRawSlug();
        $slug = $this->limitSlugSize($slug);
        $slug = $this->makeSlugUnique($slug);

        return $slug;
    }

    /**
     * Create the slug from the appropriate columns
     *
     * @return string
     */
    protected function createRawSlug()
    {
        return '' . $this->cleanupSlugPart($this->gettitle()) . '';
    }

    /**
     * Cleanup a string to make a slug of it
     * Removes special characters, replaces blanks with a separator, and trim it
     *
     * @param     string $slug        the text to slugify
     * @param     string $replacement the separator used by slug
     * @return    string               the slugified text
     */
    protected static function cleanupSlugPart($slug, $replacement = '-')
    {
        // transliterate
        if (function_exists('iconv')) {
            $slug = iconv('utf-8', 'us-ascii//TRANSLIT', $slug);
        }

        // lowercase
        if (function_exists('mb_strtolower')) {
            $slug = mb_strtolower($slug);
        } else {
            $slug = strtolower($slug);
        }

        // remove accents resulting from OSX's iconv
        $slug = str_replace(array('\'', '`', '^'), '', $slug);

        // replace non letter or digits with separator
        $slug = preg_replace('/\W+/', $replacement, $slug);

        // trim
        $slug = trim($slug, $replacement);

        if (empty($slug)) {
            return 'n-a';
        }

        return $slug;
    }


    /**
     * Make sure the slug is short enough to accommodate the column size
     *
     * @param    string $slug                   the slug to check
     * @param    int    $incrementReservedSpace the number of characters to keep empty
     *
     * @return string                            the truncated slug
     */
    protected static function limitSlugSize($slug, $incrementReservedSpace = 3)
    {
        // check length, as suffix could put it over maximum
        if (strlen($slug) > (255 - $incrementReservedSpace)) {
            $slug = substr($slug, 0, 255 - $incrementReservedSpace);
        }

        return $slug;
    }


    /**
     * Get the slug, ensuring its uniqueness
     *
     * @param    string $slug            the slug to check
     * @param    string $separator       the separator used by slug
     * @param    int    $alreadyExists   false for the first try, true for the second, and take the high count + 1
     * @return   string                   the unique slug
     */
    protected function makeSlugUnique($slug, $separator = '-', $alreadyExists = false)
    {
        if (!$alreadyExists) {
            $slug2 = $slug;
        } else {
            $slug2 = $slug . $separator;
        }

         $query = PEOperationQuery::create('q')
        ->where('q.Slug ' . ($alreadyExists ? 'REGEXP' : '=') . ' ?', $alreadyExists ? '^' . $slug2 . '[0-9]+$' : $slug2)->prune($this)
        ;

        if (!$alreadyExists) {
            $count = $query->count();
            if ($count > 0) {
                return $this->makeSlugUnique($slug, $separator, true);
            }

            return $slug2;
        }

        // Already exists
        $object = $query
            ->addDescendingOrderByColumn('LENGTH(slug)')
            ->addDescendingOrderByColumn('slug')
        ->findOne();

        // First duplicate slug
        if (null == $object) {
            return $slug2 . '1';
        }

        $slugNum = substr($object->getSlug(), strlen($slug) + 1);
        if ('0' === $slugNum[0]) {
            $slugNum[0] = 1;
        }

        return $slug2 . ($slugNum + 1);
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
    * If permanent UUID, throw exception p_e_operation.uuid*/
    public function preUpdate(PropelPDO $con = NULL) {
            $uuid = $this->getUuid();
        if(!is_null($uuid) && !\Ramsey\Uuid\Uuid::isValid($uuid)) {
            throw new \InvalidArgumentException("UUID: $uuid in not valid");
        }
            return true;
    }

    // archivable behavior

    /**
     * Get an archived version of the current object.
     *
     * @param PropelPDO $con Optional connection object
     *
     * @return     PEOperationArchive An archive object, or null if the current object was never archived
     */
    public function getArchive(PropelPDO $con = null)
    {
        if ($this->isNew()) {
            return null;
        }
        $archive = PEOperationArchiveQuery::create()
            ->filterByPrimaryKey($this->getPrimaryKey())
            ->findOne($con);

        return $archive;
    }
    /**
     * Copy the data of the current object into a $archiveTablePhpName archive object.
     * The archived object is then saved.
     * If the current object has already been archived, the archived object
     * is updated and not duplicated.
     *
     * @param PropelPDO $con Optional connection object
     *
     * @throws PropelException If the object is new
     *
     * @return     PEOperationArchive The archive object based on this object
     */
    public function archive(PropelPDO $con = null)
    {
        if ($this->isNew()) {
            throw new PropelException('New objects cannot be archived. You must save the current object before calling archive().');
        }
        if (!$archive = $this->getArchive($con)) {
            $archive = new PEOperationArchive();
            $archive->setPrimaryKey($this->getPrimaryKey());
        }
        $this->copyInto($archive, $deepCopy = false, $makeNew = false);
        $archive->setArchivedAt(time());
        $archive->save($con);

        return $archive;
    }

    /**
     * Revert the the current object to the state it had when it was last archived.
     * The object must be saved afterwards if the changes must persist.
     *
     * @param PropelPDO $con Optional connection object
     *
     * @throws PropelException If the object has no corresponding archive.
     *
     * @return PEOperation The current object (for fluent API support)
     */
    public function restoreFromArchive(PropelPDO $con = null)
    {
        if (!$archive = $this->getArchive($con)) {
            throw new PropelException('The current object has never been archived and cannot be restored');
        }
        $this->populateFromArchive($archive);

        return $this;
    }

    /**
     * Populates the the current object based on a $archiveTablePhpName archive object.
     *
     * @param      PEOperationArchive $archive An archived object based on the same class
      * @param      Boolean $populateAutoIncrementPrimaryKeys
     *               If true, autoincrement columns are copied from the archive object.
     *               If false, autoincrement columns are left intact.
      *
     * @return     PEOperation The current object (for fluent API support)
     */
    public function populateFromArchive($archive, $populateAutoIncrementPrimaryKeys = false) {
        if ($populateAutoIncrementPrimaryKeys) {
            $this->setId($archive->getId());
        }
        $this->setUuid($archive->getUuid());
        $this->setPUserId($archive->getPUserId());
        $this->setTitle($archive->getTitle());
        $this->setDescription($archive->getDescription());
        $this->setEditingDescription($archive->getEditingDescription());
        $this->setFileName($archive->getFileName());
        $this->setGeoScoped($archive->getGeoScoped());
        $this->setOnline($archive->getOnline());
        $this->setTimeline($archive->getTimeline());
        $this->setCreatedAt($archive->getCreatedAt());
        $this->setUpdatedAt($archive->getUpdatedAt());
        $this->setSlug($archive->getSlug());

        return $this;
    }

    /**
     * Removes the object from the database without archiving it.
     *
     * @param PropelPDO $con Optional connection object
     *
     * @return     PEOperation The current object (for fluent API support)
     */
    public function deleteWithoutArchive(PropelPDO $con = null)
    {
        $this->archiveOnDelete = false;

        return $this->delete($con);
    }

}
