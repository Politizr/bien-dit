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
use Politizr\Model\PQOrganization;
use Politizr\Model\PQOrganizationPeer;
use Politizr\Model\PQOrganizationQuery;
use Politizr\Model\PQType;
use Politizr\Model\PQTypeQuery;
use Politizr\Model\PUCurrentQO;
use Politizr\Model\PUCurrentQOQuery;
use Politizr\Model\PUMandate;
use Politizr\Model\PUMandateQuery;
use Politizr\Model\PUser;
use Politizr\Model\PUserQuery;

abstract class BasePQOrganization extends BaseObject implements Persistent
{
    /**
     * Peer class name
     */
    const PEER = 'Politizr\\Model\\PQOrganizationPeer';

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        PQOrganizationPeer
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
     * The value for the p_q_type_id field.
     * @var        int
     */
    protected $p_q_type_id;

    /**
     * The value for the title field.
     * @var        string
     */
    protected $title;

    /**
     * The value for the initials field.
     * @var        string
     */
    protected $initials;

    /**
     * The value for the file_name field.
     * @var        string
     */
    protected $file_name;

    /**
     * The value for the description field.
     * @var        string
     */
    protected $description;

    /**
     * The value for the url field.
     * @var        string
     */
    protected $url;

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
     * The value for the slug field.
     * @var        string
     */
    protected $slug;

    /**
     * The value for the sortable_rank field.
     * @var        int
     */
    protected $sortable_rank;

    /**
     * @var        PQType
     */
    protected $aPQType;

    /**
     * @var        PropelObjectCollection|PUMandate[] Collection to store aggregation of PUMandate objects.
     */
    protected $collPUMandates;
    protected $collPUMandatesPartial;

    /**
     * @var        PropelObjectCollection|PUCurrentQO[] Collection to store aggregation of PUCurrentQO objects.
     */
    protected $collPUCurrentQOPQOrganizations;
    protected $collPUCurrentQOPQOrganizationsPartial;

    /**
     * @var        PropelObjectCollection|PUser[] Collection to store aggregation of PUser objects.
     */
    protected $collPUCurrentQOPUsers;

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

    // sortable behavior

    /**
     * Queries to be executed in the save transaction
     * @var        array
     */
    protected $sortableQueries = array();

    /**
     * The old scope value.
     * @var        int
     */
    protected $oldScope;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $pUCurrentQOPUsersScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $pUMandatesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $pUCurrentQOPQOrganizationsScheduledForDeletion = null;

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
     * Get the [p_q_type_id] column value.
     *
     * @return int
     */
    public function getPQTypeId()
    {

        return $this->p_q_type_id;
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
     * Get the [initials] column value.
     *
     * @return string
     */
    public function getInitials()
    {

        return $this->initials;
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
     * Get the [description] column value.
     *
     * @return string
     */
    public function getDescription()
    {

        return $this->description;
    }

    /**
     * Get the [url] column value.
     *
     * @return string
     */
    public function getUrl()
    {

        return $this->url;
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
     * Get the [slug] column value.
     *
     * @return string
     */
    public function getSlug()
    {

        return $this->slug;
    }

    /**
     * Get the [sortable_rank] column value.
     *
     * @return int
     */
    public function getSortableRank()
    {

        return $this->sortable_rank;
    }

    /**
     * Set the value of [id] column.
     *
     * @param  int $v new value
     * @return PQOrganization The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[] = PQOrganizationPeer::ID;
        }


        return $this;
    } // setId()

    /**
     * Set the value of [uuid] column.
     *
     * @param  string $v new value
     * @return PQOrganization The current object (for fluent API support)
     */
    public function setUuid($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->uuid !== $v) {
            $this->uuid = $v;
            $this->modifiedColumns[] = PQOrganizationPeer::UUID;
        }


        return $this;
    } // setUuid()

    /**
     * Set the value of [p_q_type_id] column.
     *
     * @param  int $v new value
     * @return PQOrganization The current object (for fluent API support)
     */
    public function setPQTypeId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->p_q_type_id !== $v) {
            // sortable behavior
            $this->oldScope = $this->p_q_type_id;

            $this->p_q_type_id = $v;
            $this->modifiedColumns[] = PQOrganizationPeer::P_Q_TYPE_ID;
        }

        if ($this->aPQType !== null && $this->aPQType->getId() !== $v) {
            $this->aPQType = null;
        }


        return $this;
    } // setPQTypeId()

    /**
     * Set the value of [title] column.
     *
     * @param  string $v new value
     * @return PQOrganization The current object (for fluent API support)
     */
    public function setTitle($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->title !== $v) {
            $this->title = $v;
            $this->modifiedColumns[] = PQOrganizationPeer::TITLE;
        }


        return $this;
    } // setTitle()

    /**
     * Set the value of [initials] column.
     *
     * @param  string $v new value
     * @return PQOrganization The current object (for fluent API support)
     */
    public function setInitials($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->initials !== $v) {
            $this->initials = $v;
            $this->modifiedColumns[] = PQOrganizationPeer::INITIALS;
        }


        return $this;
    } // setInitials()

    /**
     * Set the value of [file_name] column.
     *
     * @param  string $v new value
     * @return PQOrganization The current object (for fluent API support)
     */
    public function setFileName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->file_name !== $v) {
            $this->file_name = $v;
            $this->modifiedColumns[] = PQOrganizationPeer::FILE_NAME;
        }


        return $this;
    } // setFileName()

    /**
     * Set the value of [description] column.
     *
     * @param  string $v new value
     * @return PQOrganization The current object (for fluent API support)
     */
    public function setDescription($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->description !== $v) {
            $this->description = $v;
            $this->modifiedColumns[] = PQOrganizationPeer::DESCRIPTION;
        }


        return $this;
    } // setDescription()

    /**
     * Set the value of [url] column.
     *
     * @param  string $v new value
     * @return PQOrganization The current object (for fluent API support)
     */
    public function setUrl($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->url !== $v) {
            $this->url = $v;
            $this->modifiedColumns[] = PQOrganizationPeer::URL;
        }


        return $this;
    } // setUrl()

    /**
     * Sets the value of the [online] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param boolean|integer|string $v The new value
     * @return PQOrganization The current object (for fluent API support)
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
            $this->modifiedColumns[] = PQOrganizationPeer::ONLINE;
        }


        return $this;
    } // setOnline()

    /**
     * Sets the value of [created_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return PQOrganization The current object (for fluent API support)
     */
    public function setCreatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->created_at !== null || $dt !== null) {
            $currentDateAsString = ($this->created_at !== null && $tmpDt = new DateTime($this->created_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->created_at = $newDateAsString;
                $this->modifiedColumns[] = PQOrganizationPeer::CREATED_AT;
            }
        } // if either are not null


        return $this;
    } // setCreatedAt()

    /**
     * Sets the value of [updated_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return PQOrganization The current object (for fluent API support)
     */
    public function setUpdatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->updated_at !== null || $dt !== null) {
            $currentDateAsString = ($this->updated_at !== null && $tmpDt = new DateTime($this->updated_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->updated_at = $newDateAsString;
                $this->modifiedColumns[] = PQOrganizationPeer::UPDATED_AT;
            }
        } // if either are not null


        return $this;
    } // setUpdatedAt()

    /**
     * Set the value of [slug] column.
     *
     * @param  string $v new value
     * @return PQOrganization The current object (for fluent API support)
     */
    public function setSlug($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->slug !== $v) {
            $this->slug = $v;
            $this->modifiedColumns[] = PQOrganizationPeer::SLUG;
        }


        return $this;
    } // setSlug()

    /**
     * Set the value of [sortable_rank] column.
     *
     * @param  int $v new value
     * @return PQOrganization The current object (for fluent API support)
     */
    public function setSortableRank($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->sortable_rank !== $v) {
            $this->sortable_rank = $v;
            $this->modifiedColumns[] = PQOrganizationPeer::SORTABLE_RANK;
        }


        return $this;
    } // setSortableRank()

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
            $this->p_q_type_id = ($row[$startcol + 2] !== null) ? (int) $row[$startcol + 2] : null;
            $this->title = ($row[$startcol + 3] !== null) ? (string) $row[$startcol + 3] : null;
            $this->initials = ($row[$startcol + 4] !== null) ? (string) $row[$startcol + 4] : null;
            $this->file_name = ($row[$startcol + 5] !== null) ? (string) $row[$startcol + 5] : null;
            $this->description = ($row[$startcol + 6] !== null) ? (string) $row[$startcol + 6] : null;
            $this->url = ($row[$startcol + 7] !== null) ? (string) $row[$startcol + 7] : null;
            $this->online = ($row[$startcol + 8] !== null) ? (boolean) $row[$startcol + 8] : null;
            $this->created_at = ($row[$startcol + 9] !== null) ? (string) $row[$startcol + 9] : null;
            $this->updated_at = ($row[$startcol + 10] !== null) ? (string) $row[$startcol + 10] : null;
            $this->slug = ($row[$startcol + 11] !== null) ? (string) $row[$startcol + 11] : null;
            $this->sortable_rank = ($row[$startcol + 12] !== null) ? (int) $row[$startcol + 12] : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }
            $this->postHydrate($row, $startcol, $rehydrate);

            return $startcol + 13; // 13 = PQOrganizationPeer::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating PQOrganization object", $e);
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

        if ($this->aPQType !== null && $this->p_q_type_id !== $this->aPQType->getId()) {
            $this->aPQType = null;
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
            $con = Propel::getConnection(PQOrganizationPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $stmt = PQOrganizationPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
        $row = $stmt->fetch(PDO::FETCH_NUM);
        $stmt->closeCursor();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aPQType = null;
            $this->collPUMandates = null;

            $this->collPUCurrentQOPQOrganizations = null;

            $this->collPUCurrentQOPUsers = null;
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
            $con = Propel::getConnection(PQOrganizationPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        try {
            $deleteQuery = PQOrganizationQuery::create()
                ->filterByPrimaryKey($this->getPrimaryKey());
            $ret = $this->preDelete($con);
            // sortable behavior

            PQOrganizationPeer::shiftRank(-1, $this->getSortableRank() + 1, null, $this->getScopeValue(), $con);
            PQOrganizationPeer::clearInstancePool();

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
            $con = Propel::getConnection(PQOrganizationPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        $isInsert = $this->isNew();
        try {
            $ret = $this->preSave($con);
            // sluggable behavior

            if ($this->isColumnModified(PQOrganizationPeer::SLUG) && $this->getSlug()) {
                $this->setSlug($this->makeSlugUnique($this->getSlug()));
            } elseif ($this->isColumnModified(PQOrganizationPeer::TITLE)) {
                $this->setSlug($this->createSlug());
            } elseif (!$this->getSlug()) {
                $this->setSlug($this->createSlug());
            }
            // sortable behavior
            $this->processSortableQueries($con);
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
                // timestampable behavior
                if (!$this->isColumnModified(PQOrganizationPeer::CREATED_AT)) {
                    $this->setCreatedAt(time());
                }
                if (!$this->isColumnModified(PQOrganizationPeer::UPDATED_AT)) {
                    $this->setUpdatedAt(time());
                }
                // sortable behavior
                if (!$this->isColumnModified(PQOrganizationPeer::RANK_COL)) {
                    $this->setSortableRank(PQOrganizationQuery::create()->getMaxRankArray($this->getScopeValue(), $con) + 1);
                }

            } else {
                $ret = $ret && $this->preUpdate($con);
                // timestampable behavior
                if ($this->isModified() && !$this->isColumnModified(PQOrganizationPeer::UPDATED_AT)) {
                    $this->setUpdatedAt(time());
                }
                // sortable behavior
                // if scope has changed and rank was not modified (if yes, assuming superior action)
                // insert object to the end of new scope and cleanup old one
                if (($this->isColumnModified(PQOrganizationPeer::P_Q_TYPE_ID)) && !$this->isColumnModified(PQOrganizationPeer::RANK_COL)) { PQOrganizationPeer::shiftRank(-1, $this->getSortableRank() + 1, null, $this->oldScope, $con);
                    $this->insertAtBottom($con);
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
                PQOrganizationPeer::addInstanceToPool($this);
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

            if ($this->aPQType !== null) {
                if ($this->aPQType->isModified() || $this->aPQType->isNew()) {
                    $affectedRows += $this->aPQType->save($con);
                }
                $this->setPQType($this->aPQType);
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

            if ($this->pUCurrentQOPUsersScheduledForDeletion !== null) {
                if (!$this->pUCurrentQOPUsersScheduledForDeletion->isEmpty()) {
                    $pks = array();
                    $pk = $this->getPrimaryKey();
                    foreach ($this->pUCurrentQOPUsersScheduledForDeletion->getPrimaryKeys(false) as $remotePk) {
                        $pks[] = array($remotePk, $pk);
                    }
                    PUCurrentQOQuery::create()
                        ->filterByPrimaryKeys($pks)
                        ->delete($con);
                    $this->pUCurrentQOPUsersScheduledForDeletion = null;
                }

                foreach ($this->getPUCurrentQOPUsers() as $pUCurrentQOPUser) {
                    if ($pUCurrentQOPUser->isModified()) {
                        $pUCurrentQOPUser->save($con);
                    }
                }
            } elseif ($this->collPUCurrentQOPUsers) {
                foreach ($this->collPUCurrentQOPUsers as $pUCurrentQOPUser) {
                    if ($pUCurrentQOPUser->isModified()) {
                        $pUCurrentQOPUser->save($con);
                    }
                }
            }

            if ($this->pUMandatesScheduledForDeletion !== null) {
                if (!$this->pUMandatesScheduledForDeletion->isEmpty()) {
                    foreach ($this->pUMandatesScheduledForDeletion as $pUMandate) {
                        // need to save related object because we set the relation to null
                        $pUMandate->save($con);
                    }
                    $this->pUMandatesScheduledForDeletion = null;
                }
            }

            if ($this->collPUMandates !== null) {
                foreach ($this->collPUMandates as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->pUCurrentQOPQOrganizationsScheduledForDeletion !== null) {
                if (!$this->pUCurrentQOPQOrganizationsScheduledForDeletion->isEmpty()) {
                    PUCurrentQOQuery::create()
                        ->filterByPrimaryKeys($this->pUCurrentQOPQOrganizationsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->pUCurrentQOPQOrganizationsScheduledForDeletion = null;
                }
            }

            if ($this->collPUCurrentQOPQOrganizations !== null) {
                foreach ($this->collPUCurrentQOPQOrganizations as $referrerFK) {
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

        $this->modifiedColumns[] = PQOrganizationPeer::ID;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . PQOrganizationPeer::ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(PQOrganizationPeer::ID)) {
            $modifiedColumns[':p' . $index++]  = '`id`';
        }
        if ($this->isColumnModified(PQOrganizationPeer::UUID)) {
            $modifiedColumns[':p' . $index++]  = '`uuid`';
        }
        if ($this->isColumnModified(PQOrganizationPeer::P_Q_TYPE_ID)) {
            $modifiedColumns[':p' . $index++]  = '`p_q_type_id`';
        }
        if ($this->isColumnModified(PQOrganizationPeer::TITLE)) {
            $modifiedColumns[':p' . $index++]  = '`title`';
        }
        if ($this->isColumnModified(PQOrganizationPeer::INITIALS)) {
            $modifiedColumns[':p' . $index++]  = '`initials`';
        }
        if ($this->isColumnModified(PQOrganizationPeer::FILE_NAME)) {
            $modifiedColumns[':p' . $index++]  = '`file_name`';
        }
        if ($this->isColumnModified(PQOrganizationPeer::DESCRIPTION)) {
            $modifiedColumns[':p' . $index++]  = '`description`';
        }
        if ($this->isColumnModified(PQOrganizationPeer::URL)) {
            $modifiedColumns[':p' . $index++]  = '`url`';
        }
        if ($this->isColumnModified(PQOrganizationPeer::ONLINE)) {
            $modifiedColumns[':p' . $index++]  = '`online`';
        }
        if ($this->isColumnModified(PQOrganizationPeer::CREATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`created_at`';
        }
        if ($this->isColumnModified(PQOrganizationPeer::UPDATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`updated_at`';
        }
        if ($this->isColumnModified(PQOrganizationPeer::SLUG)) {
            $modifiedColumns[':p' . $index++]  = '`slug`';
        }
        if ($this->isColumnModified(PQOrganizationPeer::SORTABLE_RANK)) {
            $modifiedColumns[':p' . $index++]  = '`sortable_rank`';
        }

        $sql = sprintf(
            'INSERT INTO `p_q_organization` (%s) VALUES (%s)',
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
                    case '`p_q_type_id`':
                        $stmt->bindValue($identifier, $this->p_q_type_id, PDO::PARAM_INT);
                        break;
                    case '`title`':
                        $stmt->bindValue($identifier, $this->title, PDO::PARAM_STR);
                        break;
                    case '`initials`':
                        $stmt->bindValue($identifier, $this->initials, PDO::PARAM_STR);
                        break;
                    case '`file_name`':
                        $stmt->bindValue($identifier, $this->file_name, PDO::PARAM_STR);
                        break;
                    case '`description`':
                        $stmt->bindValue($identifier, $this->description, PDO::PARAM_STR);
                        break;
                    case '`url`':
                        $stmt->bindValue($identifier, $this->url, PDO::PARAM_STR);
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
                    case '`slug`':
                        $stmt->bindValue($identifier, $this->slug, PDO::PARAM_STR);
                        break;
                    case '`sortable_rank`':
                        $stmt->bindValue($identifier, $this->sortable_rank, PDO::PARAM_INT);
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
        $pos = PQOrganizationPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                return $this->getPQTypeId();
                break;
            case 3:
                return $this->getTitle();
                break;
            case 4:
                return $this->getInitials();
                break;
            case 5:
                return $this->getFileName();
                break;
            case 6:
                return $this->getDescription();
                break;
            case 7:
                return $this->getUrl();
                break;
            case 8:
                return $this->getOnline();
                break;
            case 9:
                return $this->getCreatedAt();
                break;
            case 10:
                return $this->getUpdatedAt();
                break;
            case 11:
                return $this->getSlug();
                break;
            case 12:
                return $this->getSortableRank();
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
        if (isset($alreadyDumpedObjects['PQOrganization'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['PQOrganization'][$this->getPrimaryKey()] = true;
        $keys = PQOrganizationPeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getUuid(),
            $keys[2] => $this->getPQTypeId(),
            $keys[3] => $this->getTitle(),
            $keys[4] => $this->getInitials(),
            $keys[5] => $this->getFileName(),
            $keys[6] => $this->getDescription(),
            $keys[7] => $this->getUrl(),
            $keys[8] => $this->getOnline(),
            $keys[9] => $this->getCreatedAt(),
            $keys[10] => $this->getUpdatedAt(),
            $keys[11] => $this->getSlug(),
            $keys[12] => $this->getSortableRank(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aPQType) {
                $result['PQType'] = $this->aPQType->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->collPUMandates) {
                $result['PUMandates'] = $this->collPUMandates->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPUCurrentQOPQOrganizations) {
                $result['PUCurrentQOPQOrganizations'] = $this->collPUCurrentQOPQOrganizations->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
        $pos = PQOrganizationPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);

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
                $this->setPQTypeId($value);
                break;
            case 3:
                $this->setTitle($value);
                break;
            case 4:
                $this->setInitials($value);
                break;
            case 5:
                $this->setFileName($value);
                break;
            case 6:
                $this->setDescription($value);
                break;
            case 7:
                $this->setUrl($value);
                break;
            case 8:
                $this->setOnline($value);
                break;
            case 9:
                $this->setCreatedAt($value);
                break;
            case 10:
                $this->setUpdatedAt($value);
                break;
            case 11:
                $this->setSlug($value);
                break;
            case 12:
                $this->setSortableRank($value);
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
        $keys = PQOrganizationPeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setUuid($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setPQTypeId($arr[$keys[2]]);
        if (array_key_exists($keys[3], $arr)) $this->setTitle($arr[$keys[3]]);
        if (array_key_exists($keys[4], $arr)) $this->setInitials($arr[$keys[4]]);
        if (array_key_exists($keys[5], $arr)) $this->setFileName($arr[$keys[5]]);
        if (array_key_exists($keys[6], $arr)) $this->setDescription($arr[$keys[6]]);
        if (array_key_exists($keys[7], $arr)) $this->setUrl($arr[$keys[7]]);
        if (array_key_exists($keys[8], $arr)) $this->setOnline($arr[$keys[8]]);
        if (array_key_exists($keys[9], $arr)) $this->setCreatedAt($arr[$keys[9]]);
        if (array_key_exists($keys[10], $arr)) $this->setUpdatedAt($arr[$keys[10]]);
        if (array_key_exists($keys[11], $arr)) $this->setSlug($arr[$keys[11]]);
        if (array_key_exists($keys[12], $arr)) $this->setSortableRank($arr[$keys[12]]);
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(PQOrganizationPeer::DATABASE_NAME);

        if ($this->isColumnModified(PQOrganizationPeer::ID)) $criteria->add(PQOrganizationPeer::ID, $this->id);
        if ($this->isColumnModified(PQOrganizationPeer::UUID)) $criteria->add(PQOrganizationPeer::UUID, $this->uuid);
        if ($this->isColumnModified(PQOrganizationPeer::P_Q_TYPE_ID)) $criteria->add(PQOrganizationPeer::P_Q_TYPE_ID, $this->p_q_type_id);
        if ($this->isColumnModified(PQOrganizationPeer::TITLE)) $criteria->add(PQOrganizationPeer::TITLE, $this->title);
        if ($this->isColumnModified(PQOrganizationPeer::INITIALS)) $criteria->add(PQOrganizationPeer::INITIALS, $this->initials);
        if ($this->isColumnModified(PQOrganizationPeer::FILE_NAME)) $criteria->add(PQOrganizationPeer::FILE_NAME, $this->file_name);
        if ($this->isColumnModified(PQOrganizationPeer::DESCRIPTION)) $criteria->add(PQOrganizationPeer::DESCRIPTION, $this->description);
        if ($this->isColumnModified(PQOrganizationPeer::URL)) $criteria->add(PQOrganizationPeer::URL, $this->url);
        if ($this->isColumnModified(PQOrganizationPeer::ONLINE)) $criteria->add(PQOrganizationPeer::ONLINE, $this->online);
        if ($this->isColumnModified(PQOrganizationPeer::CREATED_AT)) $criteria->add(PQOrganizationPeer::CREATED_AT, $this->created_at);
        if ($this->isColumnModified(PQOrganizationPeer::UPDATED_AT)) $criteria->add(PQOrganizationPeer::UPDATED_AT, $this->updated_at);
        if ($this->isColumnModified(PQOrganizationPeer::SLUG)) $criteria->add(PQOrganizationPeer::SLUG, $this->slug);
        if ($this->isColumnModified(PQOrganizationPeer::SORTABLE_RANK)) $criteria->add(PQOrganizationPeer::SORTABLE_RANK, $this->sortable_rank);

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
        $criteria = new Criteria(PQOrganizationPeer::DATABASE_NAME);
        $criteria->add(PQOrganizationPeer::ID, $this->id);

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
     * @param object $copyObj An object of PQOrganization (or compatible) type.
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setUuid($this->getUuid());
        $copyObj->setPQTypeId($this->getPQTypeId());
        $copyObj->setTitle($this->getTitle());
        $copyObj->setInitials($this->getInitials());
        $copyObj->setFileName($this->getFileName());
        $copyObj->setDescription($this->getDescription());
        $copyObj->setUrl($this->getUrl());
        $copyObj->setOnline($this->getOnline());
        $copyObj->setCreatedAt($this->getCreatedAt());
        $copyObj->setUpdatedAt($this->getUpdatedAt());
        $copyObj->setSlug($this->getSlug());
        $copyObj->setSortableRank($this->getSortableRank());

        if ($deepCopy && !$this->startCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);
            // store object hash to prevent cycle
            $this->startCopy = true;

            foreach ($this->getPUMandates() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPUMandate($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPUCurrentQOPQOrganizations() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPUCurrentQOPQOrganization($relObj->copy($deepCopy));
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
     * @return PQOrganization Clone of current object.
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
     * @return PQOrganizationPeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new PQOrganizationPeer();
        }

        return self::$peer;
    }

    /**
     * Declares an association between this object and a PQType object.
     *
     * @param                  PQType $v
     * @return PQOrganization The current object (for fluent API support)
     * @throws PropelException
     */
    public function setPQType(PQType $v = null)
    {
        if ($v === null) {
            $this->setPQTypeId(NULL);
        } else {
            $this->setPQTypeId($v->getId());
        }

        $this->aPQType = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the PQType object, it will not be re-added.
        if ($v !== null) {
            $v->addPQOrganization($this);
        }


        return $this;
    }


    /**
     * Get the associated PQType object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return PQType The associated PQType object.
     * @throws PropelException
     */
    public function getPQType(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aPQType === null && ($this->p_q_type_id !== null) && $doQuery) {
            $this->aPQType = PQTypeQuery::create()->findPk($this->p_q_type_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aPQType->addPQOrganizations($this);
             */
        }

        return $this->aPQType;
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
        if ('PUMandate' == $relationName) {
            $this->initPUMandates();
        }
        if ('PUCurrentQOPQOrganization' == $relationName) {
            $this->initPUCurrentQOPQOrganizations();
        }
    }

    /**
     * Clears out the collPUMandates collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return PQOrganization The current object (for fluent API support)
     * @see        addPUMandates()
     */
    public function clearPUMandates()
    {
        $this->collPUMandates = null; // important to set this to null since that means it is uninitialized
        $this->collPUMandatesPartial = null;

        return $this;
    }

    /**
     * reset is the collPUMandates collection loaded partially
     *
     * @return void
     */
    public function resetPartialPUMandates($v = true)
    {
        $this->collPUMandatesPartial = $v;
    }

    /**
     * Initializes the collPUMandates collection.
     *
     * By default this just sets the collPUMandates collection to an empty array (like clearcollPUMandates());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPUMandates($overrideExisting = true)
    {
        if (null !== $this->collPUMandates && !$overrideExisting) {
            return;
        }
        $this->collPUMandates = new PropelObjectCollection();
        $this->collPUMandates->setModel('PUMandate');
    }

    /**
     * Gets an array of PUMandate objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this PQOrganization is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|PUMandate[] List of PUMandate objects
     * @throws PropelException
     */
    public function getPUMandates($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collPUMandatesPartial && !$this->isNew();
        if (null === $this->collPUMandates || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPUMandates) {
                // return empty collection
                $this->initPUMandates();
            } else {
                $collPUMandates = PUMandateQuery::create(null, $criteria)
                    ->filterByPQOrganization($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collPUMandatesPartial && count($collPUMandates)) {
                      $this->initPUMandates(false);

                      foreach ($collPUMandates as $obj) {
                        if (false == $this->collPUMandates->contains($obj)) {
                          $this->collPUMandates->append($obj);
                        }
                      }

                      $this->collPUMandatesPartial = true;
                    }

                    $collPUMandates->getInternalIterator()->rewind();

                    return $collPUMandates;
                }

                if ($partial && $this->collPUMandates) {
                    foreach ($this->collPUMandates as $obj) {
                        if ($obj->isNew()) {
                            $collPUMandates[] = $obj;
                        }
                    }
                }

                $this->collPUMandates = $collPUMandates;
                $this->collPUMandatesPartial = false;
            }
        }

        return $this->collPUMandates;
    }

    /**
     * Sets a collection of PUMandate objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $pUMandates A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return PQOrganization The current object (for fluent API support)
     */
    public function setPUMandates(PropelCollection $pUMandates, PropelPDO $con = null)
    {
        $pUMandatesToDelete = $this->getPUMandates(new Criteria(), $con)->diff($pUMandates);


        $this->pUMandatesScheduledForDeletion = $pUMandatesToDelete;

        foreach ($pUMandatesToDelete as $pUMandateRemoved) {
            $pUMandateRemoved->setPQOrganization(null);
        }

        $this->collPUMandates = null;
        foreach ($pUMandates as $pUMandate) {
            $this->addPUMandate($pUMandate);
        }

        $this->collPUMandates = $pUMandates;
        $this->collPUMandatesPartial = false;

        return $this;
    }

    /**
     * Returns the number of related PUMandate objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related PUMandate objects.
     * @throws PropelException
     */
    public function countPUMandates(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collPUMandatesPartial && !$this->isNew();
        if (null === $this->collPUMandates || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPUMandates) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPUMandates());
            }
            $query = PUMandateQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPQOrganization($this)
                ->count($con);
        }

        return count($this->collPUMandates);
    }

    /**
     * Method called to associate a PUMandate object to this object
     * through the PUMandate foreign key attribute.
     *
     * @param    PUMandate $l PUMandate
     * @return PQOrganization The current object (for fluent API support)
     */
    public function addPUMandate(PUMandate $l)
    {
        if ($this->collPUMandates === null) {
            $this->initPUMandates();
            $this->collPUMandatesPartial = true;
        }

        if (!in_array($l, $this->collPUMandates->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddPUMandate($l);

            if ($this->pUMandatesScheduledForDeletion and $this->pUMandatesScheduledForDeletion->contains($l)) {
                $this->pUMandatesScheduledForDeletion->remove($this->pUMandatesScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	PUMandate $pUMandate The pUMandate object to add.
     */
    protected function doAddPUMandate($pUMandate)
    {
        $this->collPUMandates[]= $pUMandate;
        $pUMandate->setPQOrganization($this);
    }

    /**
     * @param	PUMandate $pUMandate The pUMandate object to remove.
     * @return PQOrganization The current object (for fluent API support)
     */
    public function removePUMandate($pUMandate)
    {
        if ($this->getPUMandates()->contains($pUMandate)) {
            $this->collPUMandates->remove($this->collPUMandates->search($pUMandate));
            if (null === $this->pUMandatesScheduledForDeletion) {
                $this->pUMandatesScheduledForDeletion = clone $this->collPUMandates;
                $this->pUMandatesScheduledForDeletion->clear();
            }
            $this->pUMandatesScheduledForDeletion[]= $pUMandate;
            $pUMandate->setPQOrganization(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this PQOrganization is new, it will return
     * an empty collection; or if this PQOrganization has previously
     * been saved, it will retrieve related PUMandates from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in PQOrganization.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|PUMandate[] List of PUMandate objects
     */
    public function getPUMandatesJoinPUser($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PUMandateQuery::create(null, $criteria);
        $query->joinWith('PUser', $join_behavior);

        return $this->getPUMandates($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this PQOrganization is new, it will return
     * an empty collection; or if this PQOrganization has previously
     * been saved, it will retrieve related PUMandates from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in PQOrganization.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|PUMandate[] List of PUMandate objects
     */
    public function getPUMandatesJoinPQType($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PUMandateQuery::create(null, $criteria);
        $query->joinWith('PQType', $join_behavior);

        return $this->getPUMandates($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this PQOrganization is new, it will return
     * an empty collection; or if this PQOrganization has previously
     * been saved, it will retrieve related PUMandates from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in PQOrganization.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|PUMandate[] List of PUMandate objects
     */
    public function getPUMandatesJoinPQMandate($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PUMandateQuery::create(null, $criteria);
        $query->joinWith('PQMandate', $join_behavior);

        return $this->getPUMandates($query, $con);
    }

    /**
     * Clears out the collPUCurrentQOPQOrganizations collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return PQOrganization The current object (for fluent API support)
     * @see        addPUCurrentQOPQOrganizations()
     */
    public function clearPUCurrentQOPQOrganizations()
    {
        $this->collPUCurrentQOPQOrganizations = null; // important to set this to null since that means it is uninitialized
        $this->collPUCurrentQOPQOrganizationsPartial = null;

        return $this;
    }

    /**
     * reset is the collPUCurrentQOPQOrganizations collection loaded partially
     *
     * @return void
     */
    public function resetPartialPUCurrentQOPQOrganizations($v = true)
    {
        $this->collPUCurrentQOPQOrganizationsPartial = $v;
    }

    /**
     * Initializes the collPUCurrentQOPQOrganizations collection.
     *
     * By default this just sets the collPUCurrentQOPQOrganizations collection to an empty array (like clearcollPUCurrentQOPQOrganizations());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPUCurrentQOPQOrganizations($overrideExisting = true)
    {
        if (null !== $this->collPUCurrentQOPQOrganizations && !$overrideExisting) {
            return;
        }
        $this->collPUCurrentQOPQOrganizations = new PropelObjectCollection();
        $this->collPUCurrentQOPQOrganizations->setModel('PUCurrentQO');
    }

    /**
     * Gets an array of PUCurrentQO objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this PQOrganization is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|PUCurrentQO[] List of PUCurrentQO objects
     * @throws PropelException
     */
    public function getPUCurrentQOPQOrganizations($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collPUCurrentQOPQOrganizationsPartial && !$this->isNew();
        if (null === $this->collPUCurrentQOPQOrganizations || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPUCurrentQOPQOrganizations) {
                // return empty collection
                $this->initPUCurrentQOPQOrganizations();
            } else {
                $collPUCurrentQOPQOrganizations = PUCurrentQOQuery::create(null, $criteria)
                    ->filterByPUCurrentQOPQOrganization($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collPUCurrentQOPQOrganizationsPartial && count($collPUCurrentQOPQOrganizations)) {
                      $this->initPUCurrentQOPQOrganizations(false);

                      foreach ($collPUCurrentQOPQOrganizations as $obj) {
                        if (false == $this->collPUCurrentQOPQOrganizations->contains($obj)) {
                          $this->collPUCurrentQOPQOrganizations->append($obj);
                        }
                      }

                      $this->collPUCurrentQOPQOrganizationsPartial = true;
                    }

                    $collPUCurrentQOPQOrganizations->getInternalIterator()->rewind();

                    return $collPUCurrentQOPQOrganizations;
                }

                if ($partial && $this->collPUCurrentQOPQOrganizations) {
                    foreach ($this->collPUCurrentQOPQOrganizations as $obj) {
                        if ($obj->isNew()) {
                            $collPUCurrentQOPQOrganizations[] = $obj;
                        }
                    }
                }

                $this->collPUCurrentQOPQOrganizations = $collPUCurrentQOPQOrganizations;
                $this->collPUCurrentQOPQOrganizationsPartial = false;
            }
        }

        return $this->collPUCurrentQOPQOrganizations;
    }

    /**
     * Sets a collection of PUCurrentQOPQOrganization objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $pUCurrentQOPQOrganizations A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return PQOrganization The current object (for fluent API support)
     */
    public function setPUCurrentQOPQOrganizations(PropelCollection $pUCurrentQOPQOrganizations, PropelPDO $con = null)
    {
        $pUCurrentQOPQOrganizationsToDelete = $this->getPUCurrentQOPQOrganizations(new Criteria(), $con)->diff($pUCurrentQOPQOrganizations);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->pUCurrentQOPQOrganizationsScheduledForDeletion = clone $pUCurrentQOPQOrganizationsToDelete;

        foreach ($pUCurrentQOPQOrganizationsToDelete as $pUCurrentQOPQOrganizationRemoved) {
            $pUCurrentQOPQOrganizationRemoved->setPUCurrentQOPQOrganization(null);
        }

        $this->collPUCurrentQOPQOrganizations = null;
        foreach ($pUCurrentQOPQOrganizations as $pUCurrentQOPQOrganization) {
            $this->addPUCurrentQOPQOrganization($pUCurrentQOPQOrganization);
        }

        $this->collPUCurrentQOPQOrganizations = $pUCurrentQOPQOrganizations;
        $this->collPUCurrentQOPQOrganizationsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related PUCurrentQO objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related PUCurrentQO objects.
     * @throws PropelException
     */
    public function countPUCurrentQOPQOrganizations(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collPUCurrentQOPQOrganizationsPartial && !$this->isNew();
        if (null === $this->collPUCurrentQOPQOrganizations || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPUCurrentQOPQOrganizations) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPUCurrentQOPQOrganizations());
            }
            $query = PUCurrentQOQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPUCurrentQOPQOrganization($this)
                ->count($con);
        }

        return count($this->collPUCurrentQOPQOrganizations);
    }

    /**
     * Method called to associate a PUCurrentQO object to this object
     * through the PUCurrentQO foreign key attribute.
     *
     * @param    PUCurrentQO $l PUCurrentQO
     * @return PQOrganization The current object (for fluent API support)
     */
    public function addPUCurrentQOPQOrganization(PUCurrentQO $l)
    {
        if ($this->collPUCurrentQOPQOrganizations === null) {
            $this->initPUCurrentQOPQOrganizations();
            $this->collPUCurrentQOPQOrganizationsPartial = true;
        }

        if (!in_array($l, $this->collPUCurrentQOPQOrganizations->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddPUCurrentQOPQOrganization($l);

            if ($this->pUCurrentQOPQOrganizationsScheduledForDeletion and $this->pUCurrentQOPQOrganizationsScheduledForDeletion->contains($l)) {
                $this->pUCurrentQOPQOrganizationsScheduledForDeletion->remove($this->pUCurrentQOPQOrganizationsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	PUCurrentQOPQOrganization $pUCurrentQOPQOrganization The pUCurrentQOPQOrganization object to add.
     */
    protected function doAddPUCurrentQOPQOrganization($pUCurrentQOPQOrganization)
    {
        $this->collPUCurrentQOPQOrganizations[]= $pUCurrentQOPQOrganization;
        $pUCurrentQOPQOrganization->setPUCurrentQOPQOrganization($this);
    }

    /**
     * @param	PUCurrentQOPQOrganization $pUCurrentQOPQOrganization The pUCurrentQOPQOrganization object to remove.
     * @return PQOrganization The current object (for fluent API support)
     */
    public function removePUCurrentQOPQOrganization($pUCurrentQOPQOrganization)
    {
        if ($this->getPUCurrentQOPQOrganizations()->contains($pUCurrentQOPQOrganization)) {
            $this->collPUCurrentQOPQOrganizations->remove($this->collPUCurrentQOPQOrganizations->search($pUCurrentQOPQOrganization));
            if (null === $this->pUCurrentQOPQOrganizationsScheduledForDeletion) {
                $this->pUCurrentQOPQOrganizationsScheduledForDeletion = clone $this->collPUCurrentQOPQOrganizations;
                $this->pUCurrentQOPQOrganizationsScheduledForDeletion->clear();
            }
            $this->pUCurrentQOPQOrganizationsScheduledForDeletion[]= clone $pUCurrentQOPQOrganization;
            $pUCurrentQOPQOrganization->setPUCurrentQOPQOrganization(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this PQOrganization is new, it will return
     * an empty collection; or if this PQOrganization has previously
     * been saved, it will retrieve related PUCurrentQOPQOrganizations from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in PQOrganization.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|PUCurrentQO[] List of PUCurrentQO objects
     */
    public function getPUCurrentQOPQOrganizationsJoinPUCurrentQOPUser($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PUCurrentQOQuery::create(null, $criteria);
        $query->joinWith('PUCurrentQOPUser', $join_behavior);

        return $this->getPUCurrentQOPQOrganizations($query, $con);
    }

    /**
     * Clears out the collPUCurrentQOPUsers collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return PQOrganization The current object (for fluent API support)
     * @see        addPUCurrentQOPUsers()
     */
    public function clearPUCurrentQOPUsers()
    {
        $this->collPUCurrentQOPUsers = null; // important to set this to null since that means it is uninitialized
        $this->collPUCurrentQOPUsersPartial = null;

        return $this;
    }

    /**
     * Initializes the collPUCurrentQOPUsers collection.
     *
     * By default this just sets the collPUCurrentQOPUsers collection to an empty collection (like clearPUCurrentQOPUsers());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @return void
     */
    public function initPUCurrentQOPUsers()
    {
        $this->collPUCurrentQOPUsers = new PropelObjectCollection();
        $this->collPUCurrentQOPUsers->setModel('PUser');
    }

    /**
     * Gets a collection of PUser objects related by a many-to-many relationship
     * to the current object by way of the p_u_current_q_o cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this PQOrganization is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria Optional query object to filter the query
     * @param PropelPDO $con Optional connection object
     *
     * @return PropelObjectCollection|PUser[] List of PUser objects
     */
    public function getPUCurrentQOPUsers($criteria = null, PropelPDO $con = null)
    {
        if (null === $this->collPUCurrentQOPUsers || null !== $criteria) {
            if ($this->isNew() && null === $this->collPUCurrentQOPUsers) {
                // return empty collection
                $this->initPUCurrentQOPUsers();
            } else {
                $collPUCurrentQOPUsers = PUserQuery::create(null, $criteria)
                    ->filterByPUCurrentQOPQOrganization($this)
                    ->find($con);
                if (null !== $criteria) {
                    return $collPUCurrentQOPUsers;
                }
                $this->collPUCurrentQOPUsers = $collPUCurrentQOPUsers;
            }
        }

        return $this->collPUCurrentQOPUsers;
    }

    /**
     * Sets a collection of PUser objects related by a many-to-many relationship
     * to the current object by way of the p_u_current_q_o cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $pUCurrentQOPUsers A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return PQOrganization The current object (for fluent API support)
     */
    public function setPUCurrentQOPUsers(PropelCollection $pUCurrentQOPUsers, PropelPDO $con = null)
    {
        $this->clearPUCurrentQOPUsers();
        $currentPUCurrentQOPUsers = $this->getPUCurrentQOPUsers(null, $con);

        $this->pUCurrentQOPUsersScheduledForDeletion = $currentPUCurrentQOPUsers->diff($pUCurrentQOPUsers);

        foreach ($pUCurrentQOPUsers as $pUCurrentQOPUser) {
            if (!$currentPUCurrentQOPUsers->contains($pUCurrentQOPUser)) {
                $this->doAddPUCurrentQOPUser($pUCurrentQOPUser);
            }
        }

        $this->collPUCurrentQOPUsers = $pUCurrentQOPUsers;

        return $this;
    }

    /**
     * Gets the number of PUser objects related by a many-to-many relationship
     * to the current object by way of the p_u_current_q_o cross-reference table.
     *
     * @param Criteria $criteria Optional query object to filter the query
     * @param boolean $distinct Set to true to force count distinct
     * @param PropelPDO $con Optional connection object
     *
     * @return int the number of related PUser objects
     */
    public function countPUCurrentQOPUsers($criteria = null, $distinct = false, PropelPDO $con = null)
    {
        if (null === $this->collPUCurrentQOPUsers || null !== $criteria) {
            if ($this->isNew() && null === $this->collPUCurrentQOPUsers) {
                return 0;
            } else {
                $query = PUserQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByPUCurrentQOPQOrganization($this)
                    ->count($con);
            }
        } else {
            return count($this->collPUCurrentQOPUsers);
        }
    }

    /**
     * Associate a PUser object to this object
     * through the p_u_current_q_o cross reference table.
     *
     * @param  PUser $pUser The PUCurrentQO object to relate
     * @return PQOrganization The current object (for fluent API support)
     */
    public function addPUCurrentQOPUser(PUser $pUser)
    {
        if ($this->collPUCurrentQOPUsers === null) {
            $this->initPUCurrentQOPUsers();
        }

        if (!$this->collPUCurrentQOPUsers->contains($pUser)) { // only add it if the **same** object is not already associated
            $this->doAddPUCurrentQOPUser($pUser);
            $this->collPUCurrentQOPUsers[] = $pUser;

            if ($this->pUCurrentQOPUsersScheduledForDeletion and $this->pUCurrentQOPUsersScheduledForDeletion->contains($pUser)) {
                $this->pUCurrentQOPUsersScheduledForDeletion->remove($this->pUCurrentQOPUsersScheduledForDeletion->search($pUser));
            }
        }

        return $this;
    }

    /**
     * @param	PUCurrentQOPUser $pUCurrentQOPUser The pUCurrentQOPUser object to add.
     */
    protected function doAddPUCurrentQOPUser(PUser $pUCurrentQOPUser)
    {
        // set the back reference to this object directly as using provided method either results
        // in endless loop or in multiple relations
        if (!$pUCurrentQOPUser->getPUCurrentQOPQOrganizations()->contains($this)) { $pUCurrentQO = new PUCurrentQO();
            $pUCurrentQO->setPUCurrentQOPUser($pUCurrentQOPUser);
            $this->addPUCurrentQOPQOrganization($pUCurrentQO);

            $foreignCollection = $pUCurrentQOPUser->getPUCurrentQOPQOrganizations();
            $foreignCollection[] = $this;
        }
    }

    /**
     * Remove a PUser object to this object
     * through the p_u_current_q_o cross reference table.
     *
     * @param PUser $pUser The PUCurrentQO object to relate
     * @return PQOrganization The current object (for fluent API support)
     */
    public function removePUCurrentQOPUser(PUser $pUser)
    {
        if ($this->getPUCurrentQOPUsers()->contains($pUser)) {
            $this->collPUCurrentQOPUsers->remove($this->collPUCurrentQOPUsers->search($pUser));
            if (null === $this->pUCurrentQOPUsersScheduledForDeletion) {
                $this->pUCurrentQOPUsersScheduledForDeletion = clone $this->collPUCurrentQOPUsers;
                $this->pUCurrentQOPUsersScheduledForDeletion->clear();
            }
            $this->pUCurrentQOPUsersScheduledForDeletion[]= $pUser;
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
        $this->p_q_type_id = null;
        $this->title = null;
        $this->initials = null;
        $this->file_name = null;
        $this->description = null;
        $this->url = null;
        $this->online = null;
        $this->created_at = null;
        $this->updated_at = null;
        $this->slug = null;
        $this->sortable_rank = null;
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
            if ($this->collPUMandates) {
                foreach ($this->collPUMandates as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPUCurrentQOPQOrganizations) {
                foreach ($this->collPUCurrentQOPQOrganizations as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPUCurrentQOPUsers) {
                foreach ($this->collPUCurrentQOPUsers as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->aPQType instanceof Persistent) {
              $this->aPQType->clearAllReferences($deep);
            }

            $this->alreadyInClearAllReferencesDeep = false;
        } // if ($deep)

        if ($this->collPUMandates instanceof PropelCollection) {
            $this->collPUMandates->clearIterator();
        }
        $this->collPUMandates = null;
        if ($this->collPUCurrentQOPQOrganizations instanceof PropelCollection) {
            $this->collPUCurrentQOPQOrganizations->clearIterator();
        }
        $this->collPUCurrentQOPQOrganizations = null;
        if ($this->collPUCurrentQOPUsers instanceof PropelCollection) {
            $this->collPUCurrentQOPUsers->clearIterator();
        }
        $this->collPUCurrentQOPUsers = null;
        $this->aPQType = null;
    }

    /**
     * return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(PQOrganizationPeer::DEFAULT_STRING_FORMAT);
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
     * @return     PQOrganization The current object (for fluent API support)
     */
    public function keepUpdateDateUnchanged()
    {
        $this->modifiedColumns[] = PQOrganizationPeer::UPDATED_AT;

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

         $query = PQOrganizationQuery::create('q')
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
    * If permanent UUID, throw exception p_q_organization.uuid*/
    public function preUpdate(PropelPDO $con = NULL) {
            $uuid = $this->getUuid();
        if(!is_null($uuid) && !\Ramsey\Uuid\Uuid::isValid($uuid)) {
            throw new \InvalidArgumentException("UUID: $uuid in not valid");
        }
            return true;
    }

    // sortable behavior

    /**
     * Wrap the getter for rank value
     *
     * @return    int
     */
    public function getRank()
    {
        return $this->sortable_rank;
    }

    /**
     * Wrap the setter for rank value
     *
     * @param     int
     * @return    PQOrganization
     */
    public function setRank($v)
    {
        return $this->setSortableRank($v);
    }


    /**
     * Wrap the getter for scope value
     *
     * @param boolean $returnNulls If true and all scope values are null, this will return null instead of a array full with nulls
     *
     * @return    mixed A array or a native type
     */
    public function getScopeValue($returnNulls = true)
    {


        return $this->getPQTypeId();

    }

    /**
     * Wrap the setter for scope value
     *
     * @param     mixed A array or a native type
     * @return    PQOrganization
     */
    public function setScopeValue($v)
    {


        return $this->setPQTypeId($v);

    }

    /**
     * Check if the object is first in the list, i.e. if it has 1 for rank
     *
     * @return    boolean
     */
    public function isFirst()
    {
        return $this->getSortableRank() == 1;
    }

    /**
     * Check if the object is last in the list, i.e. if its rank is the highest rank
     *
     * @param     PropelPDO  $con      optional connection
     *
     * @return    boolean
     */
    public function isLast(PropelPDO $con = null)
    {
        return $this->getSortableRank() == PQOrganizationQuery::create()->getMaxRankArray($this->getScopeValue(), $con);
    }

    /**
     * Get the next item in the list, i.e. the one for which rank is immediately higher
     *
     * @param     PropelPDO  $con      optional connection
     *
     * @return    PQOrganization
     */
    public function getNext(PropelPDO $con = null)
    {

        $query = PQOrganizationQuery::create();

        $scope = $this->getScopeValue();

        $query->filterByRank($this->getSortableRank() + 1, $scope);


        return $query->findOne($con);
    }

    /**
     * Get the previous item in the list, i.e. the one for which rank is immediately lower
     *
     * @param     PropelPDO  $con      optional connection
     *
     * @return    PQOrganization
     */
    public function getPrevious(PropelPDO $con = null)
    {

        $query = PQOrganizationQuery::create();

        $scope = $this->getScopeValue();

        $query->filterByRank($this->getSortableRank() - 1, $scope);


        return $query->findOne($con);
    }

    /**
     * Insert at specified rank
     * The modifications are not persisted until the object is saved.
     *
     * @param     integer    $rank rank value
     * @param     PropelPDO  $con      optional connection
     *
     * @return    PQOrganization the current object
     *
     * @throws    PropelException
     */
    public function insertAtRank($rank, PropelPDO $con = null)
    {
        $maxRank = PQOrganizationQuery::create()->getMaxRankArray($this->getScopeValue(), $con);
        if ($rank < 1 || $rank > $maxRank + 1) {
            throw new PropelException('Invalid rank ' . $rank);
        }
        // move the object in the list, at the given rank
        $this->setSortableRank($rank);
        if ($rank != $maxRank + 1) {
            // Keep the list modification query for the save() transaction
            $this->sortableQueries []= array(
                'callable'  => array(self::PEER, 'shiftRank'),
                'arguments' => array(1, $rank, null, $this->getScopeValue())
            );
        }

        return $this;
    }

    /**
     * Insert in the last rank
     * The modifications are not persisted until the object is saved.
     *
     * @param PropelPDO $con optional connection
     *
     * @return    PQOrganization the current object
     *
     * @throws    PropelException
     */
    public function insertAtBottom(PropelPDO $con = null)
    {
        $this->setSortableRank(PQOrganizationQuery::create()->getMaxRankArray($this->getScopeValue(), $con) + 1);

        return $this;
    }

    /**
     * Insert in the first rank
     * The modifications are not persisted until the object is saved.
     *
     * @return    PQOrganization the current object
     */
    public function insertAtTop()
    {
        return $this->insertAtRank(1);
    }

    /**
     * Move the object to a new rank, and shifts the rank
     * Of the objects inbetween the old and new rank accordingly
     *
     * @param     integer   $newRank rank value
     * @param     PropelPDO $con optional connection
     *
     * @return    PQOrganization the current object
     *
     * @throws    PropelException
     */
    public function moveToRank($newRank, PropelPDO $con = null)
    {
        if ($this->isNew()) {
            throw new PropelException('New objects cannot be moved. Please use insertAtRank() instead');
        }
        if ($con === null) {
            $con = Propel::getConnection(PQOrganizationPeer::DATABASE_NAME);
        }
        if ($newRank < 1 || $newRank > PQOrganizationQuery::create()->getMaxRankArray($this->getScopeValue(), $con)) {
            throw new PropelException('Invalid rank ' . $newRank);
        }

        $oldRank = $this->getSortableRank();
        if ($oldRank == $newRank) {
            return $this;
        }

        $con->beginTransaction();
        try {
            // shift the objects between the old and the new rank
            $delta = ($oldRank < $newRank) ? -1 : 1;
            PQOrganizationPeer::shiftRank($delta, min($oldRank, $newRank), max($oldRank, $newRank), $this->getScopeValue(), $con);

            // move the object to its new rank
            $this->setSortableRank($newRank);
            $this->save($con);

            $con->commit();

            return $this;
        } catch (Exception $e) {
            $con->rollback();
            throw $e;
        }
    }

    /**
     * Exchange the rank of the object with the one passed as argument, and saves both objects
     *
     * @param     PQOrganization $object
     * @param     PropelPDO $con optional connection
     *
     * @return    PQOrganization the current object
     *
     * @throws Exception if the database cannot execute the two updates
     */
    public function swapWith($object, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(PQOrganizationPeer::DATABASE_NAME);
        }
        $con->beginTransaction();
        try {
            $oldScope = $this->getScopeValue();
            $newScope = $object->getScopeValue();
            if ($oldScope != $newScope) {
                $this->setScopeValue($newScope);
                $object->setScopeValue($oldScope);
            }
            $oldRank = $this->getSortableRank();
            $newRank = $object->getSortableRank();
            $this->setSortableRank($newRank);
            $this->save($con);
            $object->setSortableRank($oldRank);
            $object->save($con);
            $con->commit();

            return $this;
        } catch (Exception $e) {
            $con->rollback();
            throw $e;
        }
    }

    /**
     * Move the object higher in the list, i.e. exchanges its rank with the one of the previous object
     *
     * @param     PropelPDO $con optional connection
     *
     * @return    PQOrganization the current object
     */
    public function moveUp(PropelPDO $con = null)
    {
        if ($this->isFirst()) {
            return $this;
        }
        if ($con === null) {
            $con = Propel::getConnection(PQOrganizationPeer::DATABASE_NAME);
        }
        $con->beginTransaction();
        try {
            $prev = $this->getPrevious($con);
            $this->swapWith($prev, $con);
            $con->commit();

            return $this;
        } catch (Exception $e) {
            $con->rollback();
            throw $e;
        }
    }

    /**
     * Move the object higher in the list, i.e. exchanges its rank with the one of the next object
     *
     * @param     PropelPDO $con optional connection
     *
     * @return    PQOrganization the current object
     */
    public function moveDown(PropelPDO $con = null)
    {
        if ($this->isLast($con)) {
            return $this;
        }
        if ($con === null) {
            $con = Propel::getConnection(PQOrganizationPeer::DATABASE_NAME);
        }
        $con->beginTransaction();
        try {
            $next = $this->getNext($con);
            $this->swapWith($next, $con);
            $con->commit();

            return $this;
        } catch (Exception $e) {
            $con->rollback();
            throw $e;
        }
    }

    /**
     * Move the object to the top of the list
     *
     * @param     PropelPDO $con optional connection
     *
     * @return    PQOrganization the current object
     */
    public function moveToTop(PropelPDO $con = null)
    {
        if ($this->isFirst()) {
            return $this;
        }

        return $this->moveToRank(1, $con);
    }

    /**
     * Move the object to the bottom of the list
     *
     * @param     PropelPDO $con optional connection
     *
     * @return integer the old object's rank
     */
    public function moveToBottom(PropelPDO $con = null)
    {
        if ($this->isLast($con)) {
            return false;
        }
        if ($con === null) {
            $con = Propel::getConnection(PQOrganizationPeer::DATABASE_NAME);
        }
        $con->beginTransaction();
        try {
            $bottom = PQOrganizationQuery::create()->getMaxRankArray($this->getScopeValue(), $con);
            $res = $this->moveToRank($bottom, $con);
            $con->commit();

            return $res;
        } catch (Exception $e) {
            $con->rollback();
            throw $e;
        }
    }

    /**
     * Removes the current object from the list (moves it to the null scope).
     * The modifications are not persisted until the object is saved.
     *
     * @param     PropelPDO $con optional connection
     *
     * @return    PQOrganization the current object
     */
    public function removeFromList(PropelPDO $con = null)
    {
        // check if object is already removed
        if ($this->getScopeValue() === null) {
            throw new PropelException('Object is already removed (has null scope)');
        }

        // move the object to the end of null scope
        $this->setScopeValue(null);
    //    $this->insertAtBottom($con);

        return $this;
    }

    /**
     * Execute queries that were saved to be run inside the save transaction
     */
    protected function processSortableQueries($con)
    {
        foreach ($this->sortableQueries as $query) {
            $query['arguments'][]= $con;
            call_user_func_array($query['callable'], $query['arguments']);
        }
        $this->sortableQueries = array();
    }

}
