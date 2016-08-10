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
use Politizr\Model\PDDTaggedT;
use Politizr\Model\PDDTaggedTQuery;
use Politizr\Model\PDDebate;
use Politizr\Model\PDDebateQuery;
use Politizr\Model\PDRTaggedT;
use Politizr\Model\PDRTaggedTQuery;
use Politizr\Model\PDReaction;
use Politizr\Model\PDReactionQuery;
use Politizr\Model\PLDepartment;
use Politizr\Model\PLDepartmentQuery;
use Politizr\Model\PLRegion;
use Politizr\Model\PLRegionQuery;
use Politizr\Model\PTTagType;
use Politizr\Model\PTTagTypeQuery;
use Politizr\Model\PTag;
use Politizr\Model\PTagArchive;
use Politizr\Model\PTagArchiveQuery;
use Politizr\Model\PTagPeer;
use Politizr\Model\PTagQuery;
use Politizr\Model\PUTaggedT;
use Politizr\Model\PUTaggedTQuery;
use Politizr\Model\PUser;
use Politizr\Model\PUserQuery;

abstract class BasePTag extends BaseObject implements Persistent
{
    /**
     * Peer class name
     */
    const PEER = 'Politizr\\Model\\PTagPeer';

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        PTagPeer
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
     * The value for the p_t_tag_type_id field.
     * @var        int
     */
    protected $p_t_tag_type_id;

    /**
     * The value for the p_t_parent_id field.
     * @var        int
     */
    protected $p_t_parent_id;

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
     * The value for the moderated field.
     * @var        boolean
     */
    protected $moderated;

    /**
     * The value for the moderated_at field.
     * @var        string
     */
    protected $moderated_at;

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
     * @var        PTTagType
     */
    protected $aPTTagType;

    /**
     * @var        PTag
     */
    protected $aPTagRelatedByPTParentId;

    /**
     * @var        PUser
     */
    protected $aPUser;

    /**
     * @var        PropelObjectCollection|PTag[] Collection to store aggregation of PTag objects.
     */
    protected $collPTagsRelatedById;
    protected $collPTagsRelatedByIdPartial;

    /**
     * @var        PropelObjectCollection|PUTaggedT[] Collection to store aggregation of PUTaggedT objects.
     */
    protected $collPuTaggedTPTags;
    protected $collPuTaggedTPTagsPartial;

    /**
     * @var        PropelObjectCollection|PDDTaggedT[] Collection to store aggregation of PDDTaggedT objects.
     */
    protected $collPDDTaggedTs;
    protected $collPDDTaggedTsPartial;

    /**
     * @var        PropelObjectCollection|PDRTaggedT[] Collection to store aggregation of PDRTaggedT objects.
     */
    protected $collPDRTaggedTs;
    protected $collPDRTaggedTsPartial;

    /**
     * @var        PropelObjectCollection|PLRegion[] Collection to store aggregation of PLRegion objects.
     */
    protected $collPLRegions;
    protected $collPLRegionsPartial;

    /**
     * @var        PropelObjectCollection|PLDepartment[] Collection to store aggregation of PLDepartment objects.
     */
    protected $collPLDepartments;
    protected $collPLDepartmentsPartial;

    /**
     * @var        PropelObjectCollection|PUser[] Collection to store aggregation of PUser objects.
     */
    protected $collPuTaggedTPUsers;

    /**
     * @var        PropelObjectCollection|PDDebate[] Collection to store aggregation of PDDebate objects.
     */
    protected $collPDDebates;

    /**
     * @var        PropelObjectCollection|PDReaction[] Collection to store aggregation of PDReaction objects.
     */
    protected $collPDReactions;

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
    protected $puTaggedTPUsersScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $pDDebatesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $pDReactionsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $pTagsRelatedByIdScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $puTaggedTPTagsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $pDDTaggedTsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $pDRTaggedTsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $pLRegionsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $pLDepartmentsScheduledForDeletion = null;

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
     * Get the [p_t_tag_type_id] column value.
     *
     * @return int
     */
    public function getPTTagTypeId()
    {

        return $this->p_t_tag_type_id;
    }

    /**
     * Get the [p_t_parent_id] column value.
     *
     * @return int
     */
    public function getPTParentId()
    {

        return $this->p_t_parent_id;
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
     * Get the [moderated] column value.
     *
     * @return boolean
     */
    public function getModerated()
    {

        return $this->moderated;
    }

    /**
     * Get the [optionally formatted] temporal [moderated_at] column value.
     *
     *
     * @param string $format The date/time format string (either date()-style or strftime()-style).
     *				 If format is null, then the raw DateTime object will be returned.
     * @return mixed Formatted date/time value as string or DateTime object (if format is null), null if column is null, and 0 if column value is 0000-00-00 00:00:00
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getModeratedAt($format = null)
    {
        if ($this->moderated_at === null) {
            return null;
        }

        if ($this->moderated_at === '0000-00-00 00:00:00') {
            // while technically this is not a default value of null,
            // this seems to be closest in meaning.
            return null;
        }

        try {
            $dt = new DateTime($this->moderated_at);
        } catch (Exception $x) {
            throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->moderated_at, true), $x);
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
     * Set the value of [id] column.
     *
     * @param  int $v new value
     * @return PTag The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[] = PTagPeer::ID;
        }


        return $this;
    } // setId()

    /**
     * Set the value of [uuid] column.
     *
     * @param  string $v new value
     * @return PTag The current object (for fluent API support)
     */
    public function setUuid($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->uuid !== $v) {
            $this->uuid = $v;
            $this->modifiedColumns[] = PTagPeer::UUID;
        }


        return $this;
    } // setUuid()

    /**
     * Set the value of [p_t_tag_type_id] column.
     *
     * @param  int $v new value
     * @return PTag The current object (for fluent API support)
     */
    public function setPTTagTypeId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->p_t_tag_type_id !== $v) {
            $this->p_t_tag_type_id = $v;
            $this->modifiedColumns[] = PTagPeer::P_T_TAG_TYPE_ID;
        }

        if ($this->aPTTagType !== null && $this->aPTTagType->getId() !== $v) {
            $this->aPTTagType = null;
        }


        return $this;
    } // setPTTagTypeId()

    /**
     * Set the value of [p_t_parent_id] column.
     *
     * @param  int $v new value
     * @return PTag The current object (for fluent API support)
     */
    public function setPTParentId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->p_t_parent_id !== $v) {
            $this->p_t_parent_id = $v;
            $this->modifiedColumns[] = PTagPeer::P_T_PARENT_ID;
        }

        if ($this->aPTagRelatedByPTParentId !== null && $this->aPTagRelatedByPTParentId->getId() !== $v) {
            $this->aPTagRelatedByPTParentId = null;
        }


        return $this;
    } // setPTParentId()

    /**
     * Set the value of [p_user_id] column.
     *
     * @param  int $v new value
     * @return PTag The current object (for fluent API support)
     */
    public function setPUserId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->p_user_id !== $v) {
            $this->p_user_id = $v;
            $this->modifiedColumns[] = PTagPeer::P_USER_ID;
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
     * @return PTag The current object (for fluent API support)
     */
    public function setTitle($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->title !== $v) {
            $this->title = $v;
            $this->modifiedColumns[] = PTagPeer::TITLE;
        }


        return $this;
    } // setTitle()

    /**
     * Sets the value of the [moderated] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param boolean|integer|string $v The new value
     * @return PTag The current object (for fluent API support)
     */
    public function setModerated($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->moderated !== $v) {
            $this->moderated = $v;
            $this->modifiedColumns[] = PTagPeer::MODERATED;
        }


        return $this;
    } // setModerated()

    /**
     * Sets the value of [moderated_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return PTag The current object (for fluent API support)
     */
    public function setModeratedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->moderated_at !== null || $dt !== null) {
            $currentDateAsString = ($this->moderated_at !== null && $tmpDt = new DateTime($this->moderated_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->moderated_at = $newDateAsString;
                $this->modifiedColumns[] = PTagPeer::MODERATED_AT;
            }
        } // if either are not null


        return $this;
    } // setModeratedAt()

    /**
     * Sets the value of the [online] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param boolean|integer|string $v The new value
     * @return PTag The current object (for fluent API support)
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
            $this->modifiedColumns[] = PTagPeer::ONLINE;
        }


        return $this;
    } // setOnline()

    /**
     * Sets the value of [created_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return PTag The current object (for fluent API support)
     */
    public function setCreatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->created_at !== null || $dt !== null) {
            $currentDateAsString = ($this->created_at !== null && $tmpDt = new DateTime($this->created_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->created_at = $newDateAsString;
                $this->modifiedColumns[] = PTagPeer::CREATED_AT;
            }
        } // if either are not null


        return $this;
    } // setCreatedAt()

    /**
     * Sets the value of [updated_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return PTag The current object (for fluent API support)
     */
    public function setUpdatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->updated_at !== null || $dt !== null) {
            $currentDateAsString = ($this->updated_at !== null && $tmpDt = new DateTime($this->updated_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->updated_at = $newDateAsString;
                $this->modifiedColumns[] = PTagPeer::UPDATED_AT;
            }
        } // if either are not null


        return $this;
    } // setUpdatedAt()

    /**
     * Set the value of [slug] column.
     *
     * @param  string $v new value
     * @return PTag The current object (for fluent API support)
     */
    public function setSlug($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->slug !== $v) {
            $this->slug = $v;
            $this->modifiedColumns[] = PTagPeer::SLUG;
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
            $this->p_t_tag_type_id = ($row[$startcol + 2] !== null) ? (int) $row[$startcol + 2] : null;
            $this->p_t_parent_id = ($row[$startcol + 3] !== null) ? (int) $row[$startcol + 3] : null;
            $this->p_user_id = ($row[$startcol + 4] !== null) ? (int) $row[$startcol + 4] : null;
            $this->title = ($row[$startcol + 5] !== null) ? (string) $row[$startcol + 5] : null;
            $this->moderated = ($row[$startcol + 6] !== null) ? (boolean) $row[$startcol + 6] : null;
            $this->moderated_at = ($row[$startcol + 7] !== null) ? (string) $row[$startcol + 7] : null;
            $this->online = ($row[$startcol + 8] !== null) ? (boolean) $row[$startcol + 8] : null;
            $this->created_at = ($row[$startcol + 9] !== null) ? (string) $row[$startcol + 9] : null;
            $this->updated_at = ($row[$startcol + 10] !== null) ? (string) $row[$startcol + 10] : null;
            $this->slug = ($row[$startcol + 11] !== null) ? (string) $row[$startcol + 11] : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }
            $this->postHydrate($row, $startcol, $rehydrate);

            return $startcol + 12; // 12 = PTagPeer::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating PTag object", $e);
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

        if ($this->aPTTagType !== null && $this->p_t_tag_type_id !== $this->aPTTagType->getId()) {
            $this->aPTTagType = null;
        }
        if ($this->aPTagRelatedByPTParentId !== null && $this->p_t_parent_id !== $this->aPTagRelatedByPTParentId->getId()) {
            $this->aPTagRelatedByPTParentId = null;
        }
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
            $con = Propel::getConnection(PTagPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $stmt = PTagPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
        $row = $stmt->fetch(PDO::FETCH_NUM);
        $stmt->closeCursor();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aPTTagType = null;
            $this->aPTagRelatedByPTParentId = null;
            $this->aPUser = null;
            $this->collPTagsRelatedById = null;

            $this->collPuTaggedTPTags = null;

            $this->collPDDTaggedTs = null;

            $this->collPDRTaggedTs = null;

            $this->collPLRegions = null;

            $this->collPLDepartments = null;

            $this->collPuTaggedTPUsers = null;
            $this->collPDDebates = null;
            $this->collPDReactions = null;
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
            $con = Propel::getConnection(PTagPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        try {
            $deleteQuery = PTagQuery::create()
                ->filterByPrimaryKey($this->getPrimaryKey());
            $ret = $this->preDelete($con);
            // archivable behavior
            if ($ret) {
                if ($this->archiveOnDelete) {
                    // do nothing yet. The object will be archived later when calling PTagQuery::delete().
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
            $con = Propel::getConnection(PTagPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        $isInsert = $this->isNew();
        try {
            $ret = $this->preSave($con);
            // sluggable behavior

            if ($this->isColumnModified(PTagPeer::SLUG) && $this->getSlug()) {
                $this->setSlug($this->makeSlugUnique($this->getSlug()));
            } elseif ($this->isColumnModified(PTagPeer::TITLE)) {
                $this->setSlug($this->createSlug());
            } elseif (!$this->getSlug()) {
                $this->setSlug($this->createSlug());
            }
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
                // timestampable behavior
                if (!$this->isColumnModified(PTagPeer::CREATED_AT)) {
                    $this->setCreatedAt(time());
                }
                if (!$this->isColumnModified(PTagPeer::UPDATED_AT)) {
                    $this->setUpdatedAt(time());
                }
            } else {
                $ret = $ret && $this->preUpdate($con);
                // timestampable behavior
                if ($this->isModified() && !$this->isColumnModified(PTagPeer::UPDATED_AT)) {
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
                PTagPeer::addInstanceToPool($this);
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

            if ($this->aPTTagType !== null) {
                if ($this->aPTTagType->isModified() || $this->aPTTagType->isNew()) {
                    $affectedRows += $this->aPTTagType->save($con);
                }
                $this->setPTTagType($this->aPTTagType);
            }

            if ($this->aPTagRelatedByPTParentId !== null) {
                if ($this->aPTagRelatedByPTParentId->isModified() || $this->aPTagRelatedByPTParentId->isNew()) {
                    $affectedRows += $this->aPTagRelatedByPTParentId->save($con);
                }
                $this->setPTagRelatedByPTParentId($this->aPTagRelatedByPTParentId);
            }

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

            if ($this->puTaggedTPUsersScheduledForDeletion !== null) {
                if (!$this->puTaggedTPUsersScheduledForDeletion->isEmpty()) {
                    $pks = array();
                    $pk = $this->getPrimaryKey();
                    foreach ($this->puTaggedTPUsersScheduledForDeletion->getPrimaryKeys(false) as $remotePk) {
                        $pks[] = array($remotePk, $pk);
                    }
                    PUTaggedTQuery::create()
                        ->filterByPrimaryKeys($pks)
                        ->delete($con);
                    $this->puTaggedTPUsersScheduledForDeletion = null;
                }

                foreach ($this->getPuTaggedTPUsers() as $puTaggedTPUser) {
                    if ($puTaggedTPUser->isModified()) {
                        $puTaggedTPUser->save($con);
                    }
                }
            } elseif ($this->collPuTaggedTPUsers) {
                foreach ($this->collPuTaggedTPUsers as $puTaggedTPUser) {
                    if ($puTaggedTPUser->isModified()) {
                        $puTaggedTPUser->save($con);
                    }
                }
            }

            if ($this->pDDebatesScheduledForDeletion !== null) {
                if (!$this->pDDebatesScheduledForDeletion->isEmpty()) {
                    $pks = array();
                    $pk = $this->getPrimaryKey();
                    foreach ($this->pDDebatesScheduledForDeletion->getPrimaryKeys(false) as $remotePk) {
                        $pks[] = array($remotePk, $pk);
                    }
                    PDDTaggedTQuery::create()
                        ->filterByPrimaryKeys($pks)
                        ->delete($con);
                    $this->pDDebatesScheduledForDeletion = null;
                }

                foreach ($this->getPDDebates() as $pDDebate) {
                    if ($pDDebate->isModified()) {
                        $pDDebate->save($con);
                    }
                }
            } elseif ($this->collPDDebates) {
                foreach ($this->collPDDebates as $pDDebate) {
                    if ($pDDebate->isModified()) {
                        $pDDebate->save($con);
                    }
                }
            }

            if ($this->pDReactionsScheduledForDeletion !== null) {
                if (!$this->pDReactionsScheduledForDeletion->isEmpty()) {
                    $pks = array();
                    $pk = $this->getPrimaryKey();
                    foreach ($this->pDReactionsScheduledForDeletion->getPrimaryKeys(false) as $remotePk) {
                        $pks[] = array($remotePk, $pk);
                    }
                    PDRTaggedTQuery::create()
                        ->filterByPrimaryKeys($pks)
                        ->delete($con);
                    $this->pDReactionsScheduledForDeletion = null;
                }

                foreach ($this->getPDReactions() as $pDReaction) {
                    if ($pDReaction->isModified()) {
                        $pDReaction->save($con);
                    }
                }
            } elseif ($this->collPDReactions) {
                foreach ($this->collPDReactions as $pDReaction) {
                    if ($pDReaction->isModified()) {
                        $pDReaction->save($con);
                    }
                }
            }

            if ($this->pTagsRelatedByIdScheduledForDeletion !== null) {
                if (!$this->pTagsRelatedByIdScheduledForDeletion->isEmpty()) {
                    foreach ($this->pTagsRelatedByIdScheduledForDeletion as $pTagRelatedById) {
                        // need to save related object because we set the relation to null
                        $pTagRelatedById->save($con);
                    }
                    $this->pTagsRelatedByIdScheduledForDeletion = null;
                }
            }

            if ($this->collPTagsRelatedById !== null) {
                foreach ($this->collPTagsRelatedById as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->puTaggedTPTagsScheduledForDeletion !== null) {
                if (!$this->puTaggedTPTagsScheduledForDeletion->isEmpty()) {
                    PUTaggedTQuery::create()
                        ->filterByPrimaryKeys($this->puTaggedTPTagsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->puTaggedTPTagsScheduledForDeletion = null;
                }
            }

            if ($this->collPuTaggedTPTags !== null) {
                foreach ($this->collPuTaggedTPTags as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->pDDTaggedTsScheduledForDeletion !== null) {
                if (!$this->pDDTaggedTsScheduledForDeletion->isEmpty()) {
                    PDDTaggedTQuery::create()
                        ->filterByPrimaryKeys($this->pDDTaggedTsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->pDDTaggedTsScheduledForDeletion = null;
                }
            }

            if ($this->collPDDTaggedTs !== null) {
                foreach ($this->collPDDTaggedTs as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->pDRTaggedTsScheduledForDeletion !== null) {
                if (!$this->pDRTaggedTsScheduledForDeletion->isEmpty()) {
                    PDRTaggedTQuery::create()
                        ->filterByPrimaryKeys($this->pDRTaggedTsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->pDRTaggedTsScheduledForDeletion = null;
                }
            }

            if ($this->collPDRTaggedTs !== null) {
                foreach ($this->collPDRTaggedTs as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->pLRegionsScheduledForDeletion !== null) {
                if (!$this->pLRegionsScheduledForDeletion->isEmpty()) {
                    PLRegionQuery::create()
                        ->filterByPrimaryKeys($this->pLRegionsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->pLRegionsScheduledForDeletion = null;
                }
            }

            if ($this->collPLRegions !== null) {
                foreach ($this->collPLRegions as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->pLDepartmentsScheduledForDeletion !== null) {
                if (!$this->pLDepartmentsScheduledForDeletion->isEmpty()) {
                    PLDepartmentQuery::create()
                        ->filterByPrimaryKeys($this->pLDepartmentsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->pLDepartmentsScheduledForDeletion = null;
                }
            }

            if ($this->collPLDepartments !== null) {
                foreach ($this->collPLDepartments as $referrerFK) {
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

        $this->modifiedColumns[] = PTagPeer::ID;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . PTagPeer::ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(PTagPeer::ID)) {
            $modifiedColumns[':p' . $index++]  = '`id`';
        }
        if ($this->isColumnModified(PTagPeer::UUID)) {
            $modifiedColumns[':p' . $index++]  = '`uuid`';
        }
        if ($this->isColumnModified(PTagPeer::P_T_TAG_TYPE_ID)) {
            $modifiedColumns[':p' . $index++]  = '`p_t_tag_type_id`';
        }
        if ($this->isColumnModified(PTagPeer::P_T_PARENT_ID)) {
            $modifiedColumns[':p' . $index++]  = '`p_t_parent_id`';
        }
        if ($this->isColumnModified(PTagPeer::P_USER_ID)) {
            $modifiedColumns[':p' . $index++]  = '`p_user_id`';
        }
        if ($this->isColumnModified(PTagPeer::TITLE)) {
            $modifiedColumns[':p' . $index++]  = '`title`';
        }
        if ($this->isColumnModified(PTagPeer::MODERATED)) {
            $modifiedColumns[':p' . $index++]  = '`moderated`';
        }
        if ($this->isColumnModified(PTagPeer::MODERATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`moderated_at`';
        }
        if ($this->isColumnModified(PTagPeer::ONLINE)) {
            $modifiedColumns[':p' . $index++]  = '`online`';
        }
        if ($this->isColumnModified(PTagPeer::CREATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`created_at`';
        }
        if ($this->isColumnModified(PTagPeer::UPDATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`updated_at`';
        }
        if ($this->isColumnModified(PTagPeer::SLUG)) {
            $modifiedColumns[':p' . $index++]  = '`slug`';
        }

        $sql = sprintf(
            'INSERT INTO `p_tag` (%s) VALUES (%s)',
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
                    case '`p_t_tag_type_id`':
                        $stmt->bindValue($identifier, $this->p_t_tag_type_id, PDO::PARAM_INT);
                        break;
                    case '`p_t_parent_id`':
                        $stmt->bindValue($identifier, $this->p_t_parent_id, PDO::PARAM_INT);
                        break;
                    case '`p_user_id`':
                        $stmt->bindValue($identifier, $this->p_user_id, PDO::PARAM_INT);
                        break;
                    case '`title`':
                        $stmt->bindValue($identifier, $this->title, PDO::PARAM_STR);
                        break;
                    case '`moderated`':
                        $stmt->bindValue($identifier, (int) $this->moderated, PDO::PARAM_INT);
                        break;
                    case '`moderated_at`':
                        $stmt->bindValue($identifier, $this->moderated_at, PDO::PARAM_STR);
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
        $pos = PTagPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                return $this->getPTTagTypeId();
                break;
            case 3:
                return $this->getPTParentId();
                break;
            case 4:
                return $this->getPUserId();
                break;
            case 5:
                return $this->getTitle();
                break;
            case 6:
                return $this->getModerated();
                break;
            case 7:
                return $this->getModeratedAt();
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
        if (isset($alreadyDumpedObjects['PTag'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['PTag'][$this->getPrimaryKey()] = true;
        $keys = PTagPeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getUuid(),
            $keys[2] => $this->getPTTagTypeId(),
            $keys[3] => $this->getPTParentId(),
            $keys[4] => $this->getPUserId(),
            $keys[5] => $this->getTitle(),
            $keys[6] => $this->getModerated(),
            $keys[7] => $this->getModeratedAt(),
            $keys[8] => $this->getOnline(),
            $keys[9] => $this->getCreatedAt(),
            $keys[10] => $this->getUpdatedAt(),
            $keys[11] => $this->getSlug(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aPTTagType) {
                $result['PTTagType'] = $this->aPTTagType->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aPTagRelatedByPTParentId) {
                $result['PTagRelatedByPTParentId'] = $this->aPTagRelatedByPTParentId->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aPUser) {
                $result['PUser'] = $this->aPUser->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->collPTagsRelatedById) {
                $result['PTagsRelatedById'] = $this->collPTagsRelatedById->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPuTaggedTPTags) {
                $result['PuTaggedTPTags'] = $this->collPuTaggedTPTags->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPDDTaggedTs) {
                $result['PDDTaggedTs'] = $this->collPDDTaggedTs->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPDRTaggedTs) {
                $result['PDRTaggedTs'] = $this->collPDRTaggedTs->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPLRegions) {
                $result['PLRegions'] = $this->collPLRegions->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPLDepartments) {
                $result['PLDepartments'] = $this->collPLDepartments->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
        $pos = PTagPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);

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
                $this->setPTTagTypeId($value);
                break;
            case 3:
                $this->setPTParentId($value);
                break;
            case 4:
                $this->setPUserId($value);
                break;
            case 5:
                $this->setTitle($value);
                break;
            case 6:
                $this->setModerated($value);
                break;
            case 7:
                $this->setModeratedAt($value);
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
        $keys = PTagPeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setUuid($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setPTTagTypeId($arr[$keys[2]]);
        if (array_key_exists($keys[3], $arr)) $this->setPTParentId($arr[$keys[3]]);
        if (array_key_exists($keys[4], $arr)) $this->setPUserId($arr[$keys[4]]);
        if (array_key_exists($keys[5], $arr)) $this->setTitle($arr[$keys[5]]);
        if (array_key_exists($keys[6], $arr)) $this->setModerated($arr[$keys[6]]);
        if (array_key_exists($keys[7], $arr)) $this->setModeratedAt($arr[$keys[7]]);
        if (array_key_exists($keys[8], $arr)) $this->setOnline($arr[$keys[8]]);
        if (array_key_exists($keys[9], $arr)) $this->setCreatedAt($arr[$keys[9]]);
        if (array_key_exists($keys[10], $arr)) $this->setUpdatedAt($arr[$keys[10]]);
        if (array_key_exists($keys[11], $arr)) $this->setSlug($arr[$keys[11]]);
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(PTagPeer::DATABASE_NAME);

        if ($this->isColumnModified(PTagPeer::ID)) $criteria->add(PTagPeer::ID, $this->id);
        if ($this->isColumnModified(PTagPeer::UUID)) $criteria->add(PTagPeer::UUID, $this->uuid);
        if ($this->isColumnModified(PTagPeer::P_T_TAG_TYPE_ID)) $criteria->add(PTagPeer::P_T_TAG_TYPE_ID, $this->p_t_tag_type_id);
        if ($this->isColumnModified(PTagPeer::P_T_PARENT_ID)) $criteria->add(PTagPeer::P_T_PARENT_ID, $this->p_t_parent_id);
        if ($this->isColumnModified(PTagPeer::P_USER_ID)) $criteria->add(PTagPeer::P_USER_ID, $this->p_user_id);
        if ($this->isColumnModified(PTagPeer::TITLE)) $criteria->add(PTagPeer::TITLE, $this->title);
        if ($this->isColumnModified(PTagPeer::MODERATED)) $criteria->add(PTagPeer::MODERATED, $this->moderated);
        if ($this->isColumnModified(PTagPeer::MODERATED_AT)) $criteria->add(PTagPeer::MODERATED_AT, $this->moderated_at);
        if ($this->isColumnModified(PTagPeer::ONLINE)) $criteria->add(PTagPeer::ONLINE, $this->online);
        if ($this->isColumnModified(PTagPeer::CREATED_AT)) $criteria->add(PTagPeer::CREATED_AT, $this->created_at);
        if ($this->isColumnModified(PTagPeer::UPDATED_AT)) $criteria->add(PTagPeer::UPDATED_AT, $this->updated_at);
        if ($this->isColumnModified(PTagPeer::SLUG)) $criteria->add(PTagPeer::SLUG, $this->slug);

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
        $criteria = new Criteria(PTagPeer::DATABASE_NAME);
        $criteria->add(PTagPeer::ID, $this->id);

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
     * @param object $copyObj An object of PTag (or compatible) type.
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setUuid($this->getUuid());
        $copyObj->setPTTagTypeId($this->getPTTagTypeId());
        $copyObj->setPTParentId($this->getPTParentId());
        $copyObj->setPUserId($this->getPUserId());
        $copyObj->setTitle($this->getTitle());
        $copyObj->setModerated($this->getModerated());
        $copyObj->setModeratedAt($this->getModeratedAt());
        $copyObj->setOnline($this->getOnline());
        $copyObj->setCreatedAt($this->getCreatedAt());
        $copyObj->setUpdatedAt($this->getUpdatedAt());
        $copyObj->setSlug($this->getSlug());

        if ($deepCopy && !$this->startCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);
            // store object hash to prevent cycle
            $this->startCopy = true;

            foreach ($this->getPTagsRelatedById() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPTagRelatedById($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPuTaggedTPTags() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPuTaggedTPTag($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPDDTaggedTs() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPDDTaggedT($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPDRTaggedTs() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPDRTaggedT($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPLRegions() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPLRegion($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPLDepartments() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPLDepartment($relObj->copy($deepCopy));
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
     * @return PTag Clone of current object.
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
     * @return PTagPeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new PTagPeer();
        }

        return self::$peer;
    }

    /**
     * Declares an association between this object and a PTTagType object.
     *
     * @param                  PTTagType $v
     * @return PTag The current object (for fluent API support)
     * @throws PropelException
     */
    public function setPTTagType(PTTagType $v = null)
    {
        if ($v === null) {
            $this->setPTTagTypeId(NULL);
        } else {
            $this->setPTTagTypeId($v->getId());
        }

        $this->aPTTagType = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the PTTagType object, it will not be re-added.
        if ($v !== null) {
            $v->addPTag($this);
        }


        return $this;
    }


    /**
     * Get the associated PTTagType object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return PTTagType The associated PTTagType object.
     * @throws PropelException
     */
    public function getPTTagType(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aPTTagType === null && ($this->p_t_tag_type_id !== null) && $doQuery) {
            $this->aPTTagType = PTTagTypeQuery::create()->findPk($this->p_t_tag_type_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aPTTagType->addPTags($this);
             */
        }

        return $this->aPTTagType;
    }

    /**
     * Declares an association between this object and a PTag object.
     *
     * @param                  PTag $v
     * @return PTag The current object (for fluent API support)
     * @throws PropelException
     */
    public function setPTagRelatedByPTParentId(PTag $v = null)
    {
        if ($v === null) {
            $this->setPTParentId(NULL);
        } else {
            $this->setPTParentId($v->getId());
        }

        $this->aPTagRelatedByPTParentId = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the PTag object, it will not be re-added.
        if ($v !== null) {
            $v->addPTagRelatedById($this);
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
    public function getPTagRelatedByPTParentId(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aPTagRelatedByPTParentId === null && ($this->p_t_parent_id !== null) && $doQuery) {
            $this->aPTagRelatedByPTParentId = PTagQuery::create()->findPk($this->p_t_parent_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aPTagRelatedByPTParentId->addPTagsRelatedById($this);
             */
        }

        return $this->aPTagRelatedByPTParentId;
    }

    /**
     * Declares an association between this object and a PUser object.
     *
     * @param                  PUser $v
     * @return PTag The current object (for fluent API support)
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
            $v->addPTag($this);
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
                $this->aPUser->addPTags($this);
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
        if ('PTagRelatedById' == $relationName) {
            $this->initPTagsRelatedById();
        }
        if ('PuTaggedTPTag' == $relationName) {
            $this->initPuTaggedTPTags();
        }
        if ('PDDTaggedT' == $relationName) {
            $this->initPDDTaggedTs();
        }
        if ('PDRTaggedT' == $relationName) {
            $this->initPDRTaggedTs();
        }
        if ('PLRegion' == $relationName) {
            $this->initPLRegions();
        }
        if ('PLDepartment' == $relationName) {
            $this->initPLDepartments();
        }
    }

    /**
     * Clears out the collPTagsRelatedById collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return PTag The current object (for fluent API support)
     * @see        addPTagsRelatedById()
     */
    public function clearPTagsRelatedById()
    {
        $this->collPTagsRelatedById = null; // important to set this to null since that means it is uninitialized
        $this->collPTagsRelatedByIdPartial = null;

        return $this;
    }

    /**
     * reset is the collPTagsRelatedById collection loaded partially
     *
     * @return void
     */
    public function resetPartialPTagsRelatedById($v = true)
    {
        $this->collPTagsRelatedByIdPartial = $v;
    }

    /**
     * Initializes the collPTagsRelatedById collection.
     *
     * By default this just sets the collPTagsRelatedById collection to an empty array (like clearcollPTagsRelatedById());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPTagsRelatedById($overrideExisting = true)
    {
        if (null !== $this->collPTagsRelatedById && !$overrideExisting) {
            return;
        }
        $this->collPTagsRelatedById = new PropelObjectCollection();
        $this->collPTagsRelatedById->setModel('PTag');
    }

    /**
     * Gets an array of PTag objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this PTag is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|PTag[] List of PTag objects
     * @throws PropelException
     */
    public function getPTagsRelatedById($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collPTagsRelatedByIdPartial && !$this->isNew();
        if (null === $this->collPTagsRelatedById || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPTagsRelatedById) {
                // return empty collection
                $this->initPTagsRelatedById();
            } else {
                $collPTagsRelatedById = PTagQuery::create(null, $criteria)
                    ->filterByPTagRelatedByPTParentId($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collPTagsRelatedByIdPartial && count($collPTagsRelatedById)) {
                      $this->initPTagsRelatedById(false);

                      foreach ($collPTagsRelatedById as $obj) {
                        if (false == $this->collPTagsRelatedById->contains($obj)) {
                          $this->collPTagsRelatedById->append($obj);
                        }
                      }

                      $this->collPTagsRelatedByIdPartial = true;
                    }

                    $collPTagsRelatedById->getInternalIterator()->rewind();

                    return $collPTagsRelatedById;
                }

                if ($partial && $this->collPTagsRelatedById) {
                    foreach ($this->collPTagsRelatedById as $obj) {
                        if ($obj->isNew()) {
                            $collPTagsRelatedById[] = $obj;
                        }
                    }
                }

                $this->collPTagsRelatedById = $collPTagsRelatedById;
                $this->collPTagsRelatedByIdPartial = false;
            }
        }

        return $this->collPTagsRelatedById;
    }

    /**
     * Sets a collection of PTagRelatedById objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $pTagsRelatedById A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return PTag The current object (for fluent API support)
     */
    public function setPTagsRelatedById(PropelCollection $pTagsRelatedById, PropelPDO $con = null)
    {
        $pTagsRelatedByIdToDelete = $this->getPTagsRelatedById(new Criteria(), $con)->diff($pTagsRelatedById);


        $this->pTagsRelatedByIdScheduledForDeletion = $pTagsRelatedByIdToDelete;

        foreach ($pTagsRelatedByIdToDelete as $pTagRelatedByIdRemoved) {
            $pTagRelatedByIdRemoved->setPTagRelatedByPTParentId(null);
        }

        $this->collPTagsRelatedById = null;
        foreach ($pTagsRelatedById as $pTagRelatedById) {
            $this->addPTagRelatedById($pTagRelatedById);
        }

        $this->collPTagsRelatedById = $pTagsRelatedById;
        $this->collPTagsRelatedByIdPartial = false;

        return $this;
    }

    /**
     * Returns the number of related PTag objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related PTag objects.
     * @throws PropelException
     */
    public function countPTagsRelatedById(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collPTagsRelatedByIdPartial && !$this->isNew();
        if (null === $this->collPTagsRelatedById || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPTagsRelatedById) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPTagsRelatedById());
            }
            $query = PTagQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPTagRelatedByPTParentId($this)
                ->count($con);
        }

        return count($this->collPTagsRelatedById);
    }

    /**
     * Method called to associate a PTag object to this object
     * through the PTag foreign key attribute.
     *
     * @param    PTag $l PTag
     * @return PTag The current object (for fluent API support)
     */
    public function addPTagRelatedById(PTag $l)
    {
        if ($this->collPTagsRelatedById === null) {
            $this->initPTagsRelatedById();
            $this->collPTagsRelatedByIdPartial = true;
        }

        if (!in_array($l, $this->collPTagsRelatedById->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddPTagRelatedById($l);

            if ($this->pTagsRelatedByIdScheduledForDeletion and $this->pTagsRelatedByIdScheduledForDeletion->contains($l)) {
                $this->pTagsRelatedByIdScheduledForDeletion->remove($this->pTagsRelatedByIdScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	PTagRelatedById $pTagRelatedById The pTagRelatedById object to add.
     */
    protected function doAddPTagRelatedById($pTagRelatedById)
    {
        $this->collPTagsRelatedById[]= $pTagRelatedById;
        $pTagRelatedById->setPTagRelatedByPTParentId($this);
    }

    /**
     * @param	PTagRelatedById $pTagRelatedById The pTagRelatedById object to remove.
     * @return PTag The current object (for fluent API support)
     */
    public function removePTagRelatedById($pTagRelatedById)
    {
        if ($this->getPTagsRelatedById()->contains($pTagRelatedById)) {
            $this->collPTagsRelatedById->remove($this->collPTagsRelatedById->search($pTagRelatedById));
            if (null === $this->pTagsRelatedByIdScheduledForDeletion) {
                $this->pTagsRelatedByIdScheduledForDeletion = clone $this->collPTagsRelatedById;
                $this->pTagsRelatedByIdScheduledForDeletion->clear();
            }
            $this->pTagsRelatedByIdScheduledForDeletion[]= $pTagRelatedById;
            $pTagRelatedById->setPTagRelatedByPTParentId(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this PTag is new, it will return
     * an empty collection; or if this PTag has previously
     * been saved, it will retrieve related PTagsRelatedById from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in PTag.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|PTag[] List of PTag objects
     */
    public function getPTagsRelatedByIdJoinPTTagType($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PTagQuery::create(null, $criteria);
        $query->joinWith('PTTagType', $join_behavior);

        return $this->getPTagsRelatedById($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this PTag is new, it will return
     * an empty collection; or if this PTag has previously
     * been saved, it will retrieve related PTagsRelatedById from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in PTag.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|PTag[] List of PTag objects
     */
    public function getPTagsRelatedByIdJoinPUser($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PTagQuery::create(null, $criteria);
        $query->joinWith('PUser', $join_behavior);

        return $this->getPTagsRelatedById($query, $con);
    }

    /**
     * Clears out the collPuTaggedTPTags collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return PTag The current object (for fluent API support)
     * @see        addPuTaggedTPTags()
     */
    public function clearPuTaggedTPTags()
    {
        $this->collPuTaggedTPTags = null; // important to set this to null since that means it is uninitialized
        $this->collPuTaggedTPTagsPartial = null;

        return $this;
    }

    /**
     * reset is the collPuTaggedTPTags collection loaded partially
     *
     * @return void
     */
    public function resetPartialPuTaggedTPTags($v = true)
    {
        $this->collPuTaggedTPTagsPartial = $v;
    }

    /**
     * Initializes the collPuTaggedTPTags collection.
     *
     * By default this just sets the collPuTaggedTPTags collection to an empty array (like clearcollPuTaggedTPTags());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPuTaggedTPTags($overrideExisting = true)
    {
        if (null !== $this->collPuTaggedTPTags && !$overrideExisting) {
            return;
        }
        $this->collPuTaggedTPTags = new PropelObjectCollection();
        $this->collPuTaggedTPTags->setModel('PUTaggedT');
    }

    /**
     * Gets an array of PUTaggedT objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this PTag is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|PUTaggedT[] List of PUTaggedT objects
     * @throws PropelException
     */
    public function getPuTaggedTPTags($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collPuTaggedTPTagsPartial && !$this->isNew();
        if (null === $this->collPuTaggedTPTags || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPuTaggedTPTags) {
                // return empty collection
                $this->initPuTaggedTPTags();
            } else {
                $collPuTaggedTPTags = PUTaggedTQuery::create(null, $criteria)
                    ->filterByPuTaggedTPTag($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collPuTaggedTPTagsPartial && count($collPuTaggedTPTags)) {
                      $this->initPuTaggedTPTags(false);

                      foreach ($collPuTaggedTPTags as $obj) {
                        if (false == $this->collPuTaggedTPTags->contains($obj)) {
                          $this->collPuTaggedTPTags->append($obj);
                        }
                      }

                      $this->collPuTaggedTPTagsPartial = true;
                    }

                    $collPuTaggedTPTags->getInternalIterator()->rewind();

                    return $collPuTaggedTPTags;
                }

                if ($partial && $this->collPuTaggedTPTags) {
                    foreach ($this->collPuTaggedTPTags as $obj) {
                        if ($obj->isNew()) {
                            $collPuTaggedTPTags[] = $obj;
                        }
                    }
                }

                $this->collPuTaggedTPTags = $collPuTaggedTPTags;
                $this->collPuTaggedTPTagsPartial = false;
            }
        }

        return $this->collPuTaggedTPTags;
    }

    /**
     * Sets a collection of PuTaggedTPTag objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $puTaggedTPTags A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return PTag The current object (for fluent API support)
     */
    public function setPuTaggedTPTags(PropelCollection $puTaggedTPTags, PropelPDO $con = null)
    {
        $puTaggedTPTagsToDelete = $this->getPuTaggedTPTags(new Criteria(), $con)->diff($puTaggedTPTags);


        $this->puTaggedTPTagsScheduledForDeletion = $puTaggedTPTagsToDelete;

        foreach ($puTaggedTPTagsToDelete as $puTaggedTPTagRemoved) {
            $puTaggedTPTagRemoved->setPuTaggedTPTag(null);
        }

        $this->collPuTaggedTPTags = null;
        foreach ($puTaggedTPTags as $puTaggedTPTag) {
            $this->addPuTaggedTPTag($puTaggedTPTag);
        }

        $this->collPuTaggedTPTags = $puTaggedTPTags;
        $this->collPuTaggedTPTagsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related PUTaggedT objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related PUTaggedT objects.
     * @throws PropelException
     */
    public function countPuTaggedTPTags(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collPuTaggedTPTagsPartial && !$this->isNew();
        if (null === $this->collPuTaggedTPTags || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPuTaggedTPTags) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPuTaggedTPTags());
            }
            $query = PUTaggedTQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPuTaggedTPTag($this)
                ->count($con);
        }

        return count($this->collPuTaggedTPTags);
    }

    /**
     * Method called to associate a PUTaggedT object to this object
     * through the PUTaggedT foreign key attribute.
     *
     * @param    PUTaggedT $l PUTaggedT
     * @return PTag The current object (for fluent API support)
     */
    public function addPuTaggedTPTag(PUTaggedT $l)
    {
        if ($this->collPuTaggedTPTags === null) {
            $this->initPuTaggedTPTags();
            $this->collPuTaggedTPTagsPartial = true;
        }

        if (!in_array($l, $this->collPuTaggedTPTags->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddPuTaggedTPTag($l);

            if ($this->puTaggedTPTagsScheduledForDeletion and $this->puTaggedTPTagsScheduledForDeletion->contains($l)) {
                $this->puTaggedTPTagsScheduledForDeletion->remove($this->puTaggedTPTagsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	PuTaggedTPTag $puTaggedTPTag The puTaggedTPTag object to add.
     */
    protected function doAddPuTaggedTPTag($puTaggedTPTag)
    {
        $this->collPuTaggedTPTags[]= $puTaggedTPTag;
        $puTaggedTPTag->setPuTaggedTPTag($this);
    }

    /**
     * @param	PuTaggedTPTag $puTaggedTPTag The puTaggedTPTag object to remove.
     * @return PTag The current object (for fluent API support)
     */
    public function removePuTaggedTPTag($puTaggedTPTag)
    {
        if ($this->getPuTaggedTPTags()->contains($puTaggedTPTag)) {
            $this->collPuTaggedTPTags->remove($this->collPuTaggedTPTags->search($puTaggedTPTag));
            if (null === $this->puTaggedTPTagsScheduledForDeletion) {
                $this->puTaggedTPTagsScheduledForDeletion = clone $this->collPuTaggedTPTags;
                $this->puTaggedTPTagsScheduledForDeletion->clear();
            }
            $this->puTaggedTPTagsScheduledForDeletion[]= clone $puTaggedTPTag;
            $puTaggedTPTag->setPuTaggedTPTag(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this PTag is new, it will return
     * an empty collection; or if this PTag has previously
     * been saved, it will retrieve related PuTaggedTPTags from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in PTag.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|PUTaggedT[] List of PUTaggedT objects
     */
    public function getPuTaggedTPTagsJoinPuTaggedTPUser($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PUTaggedTQuery::create(null, $criteria);
        $query->joinWith('PuTaggedTPUser', $join_behavior);

        return $this->getPuTaggedTPTags($query, $con);
    }

    /**
     * Clears out the collPDDTaggedTs collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return PTag The current object (for fluent API support)
     * @see        addPDDTaggedTs()
     */
    public function clearPDDTaggedTs()
    {
        $this->collPDDTaggedTs = null; // important to set this to null since that means it is uninitialized
        $this->collPDDTaggedTsPartial = null;

        return $this;
    }

    /**
     * reset is the collPDDTaggedTs collection loaded partially
     *
     * @return void
     */
    public function resetPartialPDDTaggedTs($v = true)
    {
        $this->collPDDTaggedTsPartial = $v;
    }

    /**
     * Initializes the collPDDTaggedTs collection.
     *
     * By default this just sets the collPDDTaggedTs collection to an empty array (like clearcollPDDTaggedTs());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPDDTaggedTs($overrideExisting = true)
    {
        if (null !== $this->collPDDTaggedTs && !$overrideExisting) {
            return;
        }
        $this->collPDDTaggedTs = new PropelObjectCollection();
        $this->collPDDTaggedTs->setModel('PDDTaggedT');
    }

    /**
     * Gets an array of PDDTaggedT objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this PTag is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|PDDTaggedT[] List of PDDTaggedT objects
     * @throws PropelException
     */
    public function getPDDTaggedTs($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collPDDTaggedTsPartial && !$this->isNew();
        if (null === $this->collPDDTaggedTs || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPDDTaggedTs) {
                // return empty collection
                $this->initPDDTaggedTs();
            } else {
                $collPDDTaggedTs = PDDTaggedTQuery::create(null, $criteria)
                    ->filterByPTag($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collPDDTaggedTsPartial && count($collPDDTaggedTs)) {
                      $this->initPDDTaggedTs(false);

                      foreach ($collPDDTaggedTs as $obj) {
                        if (false == $this->collPDDTaggedTs->contains($obj)) {
                          $this->collPDDTaggedTs->append($obj);
                        }
                      }

                      $this->collPDDTaggedTsPartial = true;
                    }

                    $collPDDTaggedTs->getInternalIterator()->rewind();

                    return $collPDDTaggedTs;
                }

                if ($partial && $this->collPDDTaggedTs) {
                    foreach ($this->collPDDTaggedTs as $obj) {
                        if ($obj->isNew()) {
                            $collPDDTaggedTs[] = $obj;
                        }
                    }
                }

                $this->collPDDTaggedTs = $collPDDTaggedTs;
                $this->collPDDTaggedTsPartial = false;
            }
        }

        return $this->collPDDTaggedTs;
    }

    /**
     * Sets a collection of PDDTaggedT objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $pDDTaggedTs A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return PTag The current object (for fluent API support)
     */
    public function setPDDTaggedTs(PropelCollection $pDDTaggedTs, PropelPDO $con = null)
    {
        $pDDTaggedTsToDelete = $this->getPDDTaggedTs(new Criteria(), $con)->diff($pDDTaggedTs);


        $this->pDDTaggedTsScheduledForDeletion = $pDDTaggedTsToDelete;

        foreach ($pDDTaggedTsToDelete as $pDDTaggedTRemoved) {
            $pDDTaggedTRemoved->setPTag(null);
        }

        $this->collPDDTaggedTs = null;
        foreach ($pDDTaggedTs as $pDDTaggedT) {
            $this->addPDDTaggedT($pDDTaggedT);
        }

        $this->collPDDTaggedTs = $pDDTaggedTs;
        $this->collPDDTaggedTsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related PDDTaggedT objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related PDDTaggedT objects.
     * @throws PropelException
     */
    public function countPDDTaggedTs(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collPDDTaggedTsPartial && !$this->isNew();
        if (null === $this->collPDDTaggedTs || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPDDTaggedTs) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPDDTaggedTs());
            }
            $query = PDDTaggedTQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPTag($this)
                ->count($con);
        }

        return count($this->collPDDTaggedTs);
    }

    /**
     * Method called to associate a PDDTaggedT object to this object
     * through the PDDTaggedT foreign key attribute.
     *
     * @param    PDDTaggedT $l PDDTaggedT
     * @return PTag The current object (for fluent API support)
     */
    public function addPDDTaggedT(PDDTaggedT $l)
    {
        if ($this->collPDDTaggedTs === null) {
            $this->initPDDTaggedTs();
            $this->collPDDTaggedTsPartial = true;
        }

        if (!in_array($l, $this->collPDDTaggedTs->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddPDDTaggedT($l);

            if ($this->pDDTaggedTsScheduledForDeletion and $this->pDDTaggedTsScheduledForDeletion->contains($l)) {
                $this->pDDTaggedTsScheduledForDeletion->remove($this->pDDTaggedTsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	PDDTaggedT $pDDTaggedT The pDDTaggedT object to add.
     */
    protected function doAddPDDTaggedT($pDDTaggedT)
    {
        $this->collPDDTaggedTs[]= $pDDTaggedT;
        $pDDTaggedT->setPTag($this);
    }

    /**
     * @param	PDDTaggedT $pDDTaggedT The pDDTaggedT object to remove.
     * @return PTag The current object (for fluent API support)
     */
    public function removePDDTaggedT($pDDTaggedT)
    {
        if ($this->getPDDTaggedTs()->contains($pDDTaggedT)) {
            $this->collPDDTaggedTs->remove($this->collPDDTaggedTs->search($pDDTaggedT));
            if (null === $this->pDDTaggedTsScheduledForDeletion) {
                $this->pDDTaggedTsScheduledForDeletion = clone $this->collPDDTaggedTs;
                $this->pDDTaggedTsScheduledForDeletion->clear();
            }
            $this->pDDTaggedTsScheduledForDeletion[]= clone $pDDTaggedT;
            $pDDTaggedT->setPTag(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this PTag is new, it will return
     * an empty collection; or if this PTag has previously
     * been saved, it will retrieve related PDDTaggedTs from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in PTag.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|PDDTaggedT[] List of PDDTaggedT objects
     */
    public function getPDDTaggedTsJoinPDDebate($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PDDTaggedTQuery::create(null, $criteria);
        $query->joinWith('PDDebate', $join_behavior);

        return $this->getPDDTaggedTs($query, $con);
    }

    /**
     * Clears out the collPDRTaggedTs collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return PTag The current object (for fluent API support)
     * @see        addPDRTaggedTs()
     */
    public function clearPDRTaggedTs()
    {
        $this->collPDRTaggedTs = null; // important to set this to null since that means it is uninitialized
        $this->collPDRTaggedTsPartial = null;

        return $this;
    }

    /**
     * reset is the collPDRTaggedTs collection loaded partially
     *
     * @return void
     */
    public function resetPartialPDRTaggedTs($v = true)
    {
        $this->collPDRTaggedTsPartial = $v;
    }

    /**
     * Initializes the collPDRTaggedTs collection.
     *
     * By default this just sets the collPDRTaggedTs collection to an empty array (like clearcollPDRTaggedTs());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPDRTaggedTs($overrideExisting = true)
    {
        if (null !== $this->collPDRTaggedTs && !$overrideExisting) {
            return;
        }
        $this->collPDRTaggedTs = new PropelObjectCollection();
        $this->collPDRTaggedTs->setModel('PDRTaggedT');
    }

    /**
     * Gets an array of PDRTaggedT objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this PTag is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|PDRTaggedT[] List of PDRTaggedT objects
     * @throws PropelException
     */
    public function getPDRTaggedTs($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collPDRTaggedTsPartial && !$this->isNew();
        if (null === $this->collPDRTaggedTs || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPDRTaggedTs) {
                // return empty collection
                $this->initPDRTaggedTs();
            } else {
                $collPDRTaggedTs = PDRTaggedTQuery::create(null, $criteria)
                    ->filterByPTag($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collPDRTaggedTsPartial && count($collPDRTaggedTs)) {
                      $this->initPDRTaggedTs(false);

                      foreach ($collPDRTaggedTs as $obj) {
                        if (false == $this->collPDRTaggedTs->contains($obj)) {
                          $this->collPDRTaggedTs->append($obj);
                        }
                      }

                      $this->collPDRTaggedTsPartial = true;
                    }

                    $collPDRTaggedTs->getInternalIterator()->rewind();

                    return $collPDRTaggedTs;
                }

                if ($partial && $this->collPDRTaggedTs) {
                    foreach ($this->collPDRTaggedTs as $obj) {
                        if ($obj->isNew()) {
                            $collPDRTaggedTs[] = $obj;
                        }
                    }
                }

                $this->collPDRTaggedTs = $collPDRTaggedTs;
                $this->collPDRTaggedTsPartial = false;
            }
        }

        return $this->collPDRTaggedTs;
    }

    /**
     * Sets a collection of PDRTaggedT objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $pDRTaggedTs A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return PTag The current object (for fluent API support)
     */
    public function setPDRTaggedTs(PropelCollection $pDRTaggedTs, PropelPDO $con = null)
    {
        $pDRTaggedTsToDelete = $this->getPDRTaggedTs(new Criteria(), $con)->diff($pDRTaggedTs);


        $this->pDRTaggedTsScheduledForDeletion = $pDRTaggedTsToDelete;

        foreach ($pDRTaggedTsToDelete as $pDRTaggedTRemoved) {
            $pDRTaggedTRemoved->setPTag(null);
        }

        $this->collPDRTaggedTs = null;
        foreach ($pDRTaggedTs as $pDRTaggedT) {
            $this->addPDRTaggedT($pDRTaggedT);
        }

        $this->collPDRTaggedTs = $pDRTaggedTs;
        $this->collPDRTaggedTsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related PDRTaggedT objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related PDRTaggedT objects.
     * @throws PropelException
     */
    public function countPDRTaggedTs(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collPDRTaggedTsPartial && !$this->isNew();
        if (null === $this->collPDRTaggedTs || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPDRTaggedTs) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPDRTaggedTs());
            }
            $query = PDRTaggedTQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPTag($this)
                ->count($con);
        }

        return count($this->collPDRTaggedTs);
    }

    /**
     * Method called to associate a PDRTaggedT object to this object
     * through the PDRTaggedT foreign key attribute.
     *
     * @param    PDRTaggedT $l PDRTaggedT
     * @return PTag The current object (for fluent API support)
     */
    public function addPDRTaggedT(PDRTaggedT $l)
    {
        if ($this->collPDRTaggedTs === null) {
            $this->initPDRTaggedTs();
            $this->collPDRTaggedTsPartial = true;
        }

        if (!in_array($l, $this->collPDRTaggedTs->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddPDRTaggedT($l);

            if ($this->pDRTaggedTsScheduledForDeletion and $this->pDRTaggedTsScheduledForDeletion->contains($l)) {
                $this->pDRTaggedTsScheduledForDeletion->remove($this->pDRTaggedTsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	PDRTaggedT $pDRTaggedT The pDRTaggedT object to add.
     */
    protected function doAddPDRTaggedT($pDRTaggedT)
    {
        $this->collPDRTaggedTs[]= $pDRTaggedT;
        $pDRTaggedT->setPTag($this);
    }

    /**
     * @param	PDRTaggedT $pDRTaggedT The pDRTaggedT object to remove.
     * @return PTag The current object (for fluent API support)
     */
    public function removePDRTaggedT($pDRTaggedT)
    {
        if ($this->getPDRTaggedTs()->contains($pDRTaggedT)) {
            $this->collPDRTaggedTs->remove($this->collPDRTaggedTs->search($pDRTaggedT));
            if (null === $this->pDRTaggedTsScheduledForDeletion) {
                $this->pDRTaggedTsScheduledForDeletion = clone $this->collPDRTaggedTs;
                $this->pDRTaggedTsScheduledForDeletion->clear();
            }
            $this->pDRTaggedTsScheduledForDeletion[]= clone $pDRTaggedT;
            $pDRTaggedT->setPTag(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this PTag is new, it will return
     * an empty collection; or if this PTag has previously
     * been saved, it will retrieve related PDRTaggedTs from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in PTag.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|PDRTaggedT[] List of PDRTaggedT objects
     */
    public function getPDRTaggedTsJoinPDReaction($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PDRTaggedTQuery::create(null, $criteria);
        $query->joinWith('PDReaction', $join_behavior);

        return $this->getPDRTaggedTs($query, $con);
    }

    /**
     * Clears out the collPLRegions collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return PTag The current object (for fluent API support)
     * @see        addPLRegions()
     */
    public function clearPLRegions()
    {
        $this->collPLRegions = null; // important to set this to null since that means it is uninitialized
        $this->collPLRegionsPartial = null;

        return $this;
    }

    /**
     * reset is the collPLRegions collection loaded partially
     *
     * @return void
     */
    public function resetPartialPLRegions($v = true)
    {
        $this->collPLRegionsPartial = $v;
    }

    /**
     * Initializes the collPLRegions collection.
     *
     * By default this just sets the collPLRegions collection to an empty array (like clearcollPLRegions());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPLRegions($overrideExisting = true)
    {
        if (null !== $this->collPLRegions && !$overrideExisting) {
            return;
        }
        $this->collPLRegions = new PropelObjectCollection();
        $this->collPLRegions->setModel('PLRegion');
    }

    /**
     * Gets an array of PLRegion objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this PTag is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|PLRegion[] List of PLRegion objects
     * @throws PropelException
     */
    public function getPLRegions($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collPLRegionsPartial && !$this->isNew();
        if (null === $this->collPLRegions || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPLRegions) {
                // return empty collection
                $this->initPLRegions();
            } else {
                $collPLRegions = PLRegionQuery::create(null, $criteria)
                    ->filterByPTag($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collPLRegionsPartial && count($collPLRegions)) {
                      $this->initPLRegions(false);

                      foreach ($collPLRegions as $obj) {
                        if (false == $this->collPLRegions->contains($obj)) {
                          $this->collPLRegions->append($obj);
                        }
                      }

                      $this->collPLRegionsPartial = true;
                    }

                    $collPLRegions->getInternalIterator()->rewind();

                    return $collPLRegions;
                }

                if ($partial && $this->collPLRegions) {
                    foreach ($this->collPLRegions as $obj) {
                        if ($obj->isNew()) {
                            $collPLRegions[] = $obj;
                        }
                    }
                }

                $this->collPLRegions = $collPLRegions;
                $this->collPLRegionsPartial = false;
            }
        }

        return $this->collPLRegions;
    }

    /**
     * Sets a collection of PLRegion objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $pLRegions A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return PTag The current object (for fluent API support)
     */
    public function setPLRegions(PropelCollection $pLRegions, PropelPDO $con = null)
    {
        $pLRegionsToDelete = $this->getPLRegions(new Criteria(), $con)->diff($pLRegions);


        $this->pLRegionsScheduledForDeletion = $pLRegionsToDelete;

        foreach ($pLRegionsToDelete as $pLRegionRemoved) {
            $pLRegionRemoved->setPTag(null);
        }

        $this->collPLRegions = null;
        foreach ($pLRegions as $pLRegion) {
            $this->addPLRegion($pLRegion);
        }

        $this->collPLRegions = $pLRegions;
        $this->collPLRegionsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related PLRegion objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related PLRegion objects.
     * @throws PropelException
     */
    public function countPLRegions(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collPLRegionsPartial && !$this->isNew();
        if (null === $this->collPLRegions || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPLRegions) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPLRegions());
            }
            $query = PLRegionQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPTag($this)
                ->count($con);
        }

        return count($this->collPLRegions);
    }

    /**
     * Method called to associate a PLRegion object to this object
     * through the PLRegion foreign key attribute.
     *
     * @param    PLRegion $l PLRegion
     * @return PTag The current object (for fluent API support)
     */
    public function addPLRegion(PLRegion $l)
    {
        if ($this->collPLRegions === null) {
            $this->initPLRegions();
            $this->collPLRegionsPartial = true;
        }

        if (!in_array($l, $this->collPLRegions->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddPLRegion($l);

            if ($this->pLRegionsScheduledForDeletion and $this->pLRegionsScheduledForDeletion->contains($l)) {
                $this->pLRegionsScheduledForDeletion->remove($this->pLRegionsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	PLRegion $pLRegion The pLRegion object to add.
     */
    protected function doAddPLRegion($pLRegion)
    {
        $this->collPLRegions[]= $pLRegion;
        $pLRegion->setPTag($this);
    }

    /**
     * @param	PLRegion $pLRegion The pLRegion object to remove.
     * @return PTag The current object (for fluent API support)
     */
    public function removePLRegion($pLRegion)
    {
        if ($this->getPLRegions()->contains($pLRegion)) {
            $this->collPLRegions->remove($this->collPLRegions->search($pLRegion));
            if (null === $this->pLRegionsScheduledForDeletion) {
                $this->pLRegionsScheduledForDeletion = clone $this->collPLRegions;
                $this->pLRegionsScheduledForDeletion->clear();
            }
            $this->pLRegionsScheduledForDeletion[]= clone $pLRegion;
            $pLRegion->setPTag(null);
        }

        return $this;
    }

    /**
     * Clears out the collPLDepartments collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return PTag The current object (for fluent API support)
     * @see        addPLDepartments()
     */
    public function clearPLDepartments()
    {
        $this->collPLDepartments = null; // important to set this to null since that means it is uninitialized
        $this->collPLDepartmentsPartial = null;

        return $this;
    }

    /**
     * reset is the collPLDepartments collection loaded partially
     *
     * @return void
     */
    public function resetPartialPLDepartments($v = true)
    {
        $this->collPLDepartmentsPartial = $v;
    }

    /**
     * Initializes the collPLDepartments collection.
     *
     * By default this just sets the collPLDepartments collection to an empty array (like clearcollPLDepartments());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPLDepartments($overrideExisting = true)
    {
        if (null !== $this->collPLDepartments && !$overrideExisting) {
            return;
        }
        $this->collPLDepartments = new PropelObjectCollection();
        $this->collPLDepartments->setModel('PLDepartment');
    }

    /**
     * Gets an array of PLDepartment objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this PTag is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|PLDepartment[] List of PLDepartment objects
     * @throws PropelException
     */
    public function getPLDepartments($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collPLDepartmentsPartial && !$this->isNew();
        if (null === $this->collPLDepartments || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPLDepartments) {
                // return empty collection
                $this->initPLDepartments();
            } else {
                $collPLDepartments = PLDepartmentQuery::create(null, $criteria)
                    ->filterByPTag($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collPLDepartmentsPartial && count($collPLDepartments)) {
                      $this->initPLDepartments(false);

                      foreach ($collPLDepartments as $obj) {
                        if (false == $this->collPLDepartments->contains($obj)) {
                          $this->collPLDepartments->append($obj);
                        }
                      }

                      $this->collPLDepartmentsPartial = true;
                    }

                    $collPLDepartments->getInternalIterator()->rewind();

                    return $collPLDepartments;
                }

                if ($partial && $this->collPLDepartments) {
                    foreach ($this->collPLDepartments as $obj) {
                        if ($obj->isNew()) {
                            $collPLDepartments[] = $obj;
                        }
                    }
                }

                $this->collPLDepartments = $collPLDepartments;
                $this->collPLDepartmentsPartial = false;
            }
        }

        return $this->collPLDepartments;
    }

    /**
     * Sets a collection of PLDepartment objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $pLDepartments A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return PTag The current object (for fluent API support)
     */
    public function setPLDepartments(PropelCollection $pLDepartments, PropelPDO $con = null)
    {
        $pLDepartmentsToDelete = $this->getPLDepartments(new Criteria(), $con)->diff($pLDepartments);


        $this->pLDepartmentsScheduledForDeletion = $pLDepartmentsToDelete;

        foreach ($pLDepartmentsToDelete as $pLDepartmentRemoved) {
            $pLDepartmentRemoved->setPTag(null);
        }

        $this->collPLDepartments = null;
        foreach ($pLDepartments as $pLDepartment) {
            $this->addPLDepartment($pLDepartment);
        }

        $this->collPLDepartments = $pLDepartments;
        $this->collPLDepartmentsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related PLDepartment objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related PLDepartment objects.
     * @throws PropelException
     */
    public function countPLDepartments(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collPLDepartmentsPartial && !$this->isNew();
        if (null === $this->collPLDepartments || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPLDepartments) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPLDepartments());
            }
            $query = PLDepartmentQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPTag($this)
                ->count($con);
        }

        return count($this->collPLDepartments);
    }

    /**
     * Method called to associate a PLDepartment object to this object
     * through the PLDepartment foreign key attribute.
     *
     * @param    PLDepartment $l PLDepartment
     * @return PTag The current object (for fluent API support)
     */
    public function addPLDepartment(PLDepartment $l)
    {
        if ($this->collPLDepartments === null) {
            $this->initPLDepartments();
            $this->collPLDepartmentsPartial = true;
        }

        if (!in_array($l, $this->collPLDepartments->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddPLDepartment($l);

            if ($this->pLDepartmentsScheduledForDeletion and $this->pLDepartmentsScheduledForDeletion->contains($l)) {
                $this->pLDepartmentsScheduledForDeletion->remove($this->pLDepartmentsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	PLDepartment $pLDepartment The pLDepartment object to add.
     */
    protected function doAddPLDepartment($pLDepartment)
    {
        $this->collPLDepartments[]= $pLDepartment;
        $pLDepartment->setPTag($this);
    }

    /**
     * @param	PLDepartment $pLDepartment The pLDepartment object to remove.
     * @return PTag The current object (for fluent API support)
     */
    public function removePLDepartment($pLDepartment)
    {
        if ($this->getPLDepartments()->contains($pLDepartment)) {
            $this->collPLDepartments->remove($this->collPLDepartments->search($pLDepartment));
            if (null === $this->pLDepartmentsScheduledForDeletion) {
                $this->pLDepartmentsScheduledForDeletion = clone $this->collPLDepartments;
                $this->pLDepartmentsScheduledForDeletion->clear();
            }
            $this->pLDepartmentsScheduledForDeletion[]= clone $pLDepartment;
            $pLDepartment->setPTag(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this PTag is new, it will return
     * an empty collection; or if this PTag has previously
     * been saved, it will retrieve related PLDepartments from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in PTag.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|PLDepartment[] List of PLDepartment objects
     */
    public function getPLDepartmentsJoinPLRegion($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PLDepartmentQuery::create(null, $criteria);
        $query->joinWith('PLRegion', $join_behavior);

        return $this->getPLDepartments($query, $con);
    }

    /**
     * Clears out the collPuTaggedTPUsers collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return PTag The current object (for fluent API support)
     * @see        addPuTaggedTPUsers()
     */
    public function clearPuTaggedTPUsers()
    {
        $this->collPuTaggedTPUsers = null; // important to set this to null since that means it is uninitialized
        $this->collPuTaggedTPUsersPartial = null;

        return $this;
    }

    /**
     * Initializes the collPuTaggedTPUsers collection.
     *
     * By default this just sets the collPuTaggedTPUsers collection to an empty collection (like clearPuTaggedTPUsers());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @return void
     */
    public function initPuTaggedTPUsers()
    {
        $this->collPuTaggedTPUsers = new PropelObjectCollection();
        $this->collPuTaggedTPUsers->setModel('PUser');
    }

    /**
     * Gets a collection of PUser objects related by a many-to-many relationship
     * to the current object by way of the p_u_tagged_t cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this PTag is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria Optional query object to filter the query
     * @param PropelPDO $con Optional connection object
     *
     * @return PropelObjectCollection|PUser[] List of PUser objects
     */
    public function getPuTaggedTPUsers($criteria = null, PropelPDO $con = null)
    {
        if (null === $this->collPuTaggedTPUsers || null !== $criteria) {
            if ($this->isNew() && null === $this->collPuTaggedTPUsers) {
                // return empty collection
                $this->initPuTaggedTPUsers();
            } else {
                $collPuTaggedTPUsers = PUserQuery::create(null, $criteria)
                    ->filterByPuTaggedTPTag($this)
                    ->find($con);
                if (null !== $criteria) {
                    return $collPuTaggedTPUsers;
                }
                $this->collPuTaggedTPUsers = $collPuTaggedTPUsers;
            }
        }

        return $this->collPuTaggedTPUsers;
    }

    /**
     * Sets a collection of PUser objects related by a many-to-many relationship
     * to the current object by way of the p_u_tagged_t cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $puTaggedTPUsers A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return PTag The current object (for fluent API support)
     */
    public function setPuTaggedTPUsers(PropelCollection $puTaggedTPUsers, PropelPDO $con = null)
    {
        $this->clearPuTaggedTPUsers();
        $currentPuTaggedTPUsers = $this->getPuTaggedTPUsers(null, $con);

        $this->puTaggedTPUsersScheduledForDeletion = $currentPuTaggedTPUsers->diff($puTaggedTPUsers);

        foreach ($puTaggedTPUsers as $puTaggedTPUser) {
            if (!$currentPuTaggedTPUsers->contains($puTaggedTPUser)) {
                $this->doAddPuTaggedTPUser($puTaggedTPUser);
            }
        }

        $this->collPuTaggedTPUsers = $puTaggedTPUsers;

        return $this;
    }

    /**
     * Gets the number of PUser objects related by a many-to-many relationship
     * to the current object by way of the p_u_tagged_t cross-reference table.
     *
     * @param Criteria $criteria Optional query object to filter the query
     * @param boolean $distinct Set to true to force count distinct
     * @param PropelPDO $con Optional connection object
     *
     * @return int the number of related PUser objects
     */
    public function countPuTaggedTPUsers($criteria = null, $distinct = false, PropelPDO $con = null)
    {
        if (null === $this->collPuTaggedTPUsers || null !== $criteria) {
            if ($this->isNew() && null === $this->collPuTaggedTPUsers) {
                return 0;
            } else {
                $query = PUserQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByPuTaggedTPTag($this)
                    ->count($con);
            }
        } else {
            return count($this->collPuTaggedTPUsers);
        }
    }

    /**
     * Associate a PUser object to this object
     * through the p_u_tagged_t cross reference table.
     *
     * @param  PUser $pUser The PUTaggedT object to relate
     * @return PTag The current object (for fluent API support)
     */
    public function addPuTaggedTPUser(PUser $pUser)
    {
        if ($this->collPuTaggedTPUsers === null) {
            $this->initPuTaggedTPUsers();
        }

        if (!$this->collPuTaggedTPUsers->contains($pUser)) { // only add it if the **same** object is not already associated
            $this->doAddPuTaggedTPUser($pUser);
            $this->collPuTaggedTPUsers[] = $pUser;

            if ($this->puTaggedTPUsersScheduledForDeletion and $this->puTaggedTPUsersScheduledForDeletion->contains($pUser)) {
                $this->puTaggedTPUsersScheduledForDeletion->remove($this->puTaggedTPUsersScheduledForDeletion->search($pUser));
            }
        }

        return $this;
    }

    /**
     * @param	PuTaggedTPUser $puTaggedTPUser The puTaggedTPUser object to add.
     */
    protected function doAddPuTaggedTPUser(PUser $puTaggedTPUser)
    {
        // set the back reference to this object directly as using provided method either results
        // in endless loop or in multiple relations
        if (!$puTaggedTPUser->getPuTaggedTPTags()->contains($this)) { $pUTaggedT = new PUTaggedT();
            $pUTaggedT->setPuTaggedTPUser($puTaggedTPUser);
            $this->addPuTaggedTPTag($pUTaggedT);

            $foreignCollection = $puTaggedTPUser->getPuTaggedTPTags();
            $foreignCollection[] = $this;
        }
    }

    /**
     * Remove a PUser object to this object
     * through the p_u_tagged_t cross reference table.
     *
     * @param PUser $pUser The PUTaggedT object to relate
     * @return PTag The current object (for fluent API support)
     */
    public function removePuTaggedTPUser(PUser $pUser)
    {
        if ($this->getPuTaggedTPUsers()->contains($pUser)) {
            $this->collPuTaggedTPUsers->remove($this->collPuTaggedTPUsers->search($pUser));
            if (null === $this->puTaggedTPUsersScheduledForDeletion) {
                $this->puTaggedTPUsersScheduledForDeletion = clone $this->collPuTaggedTPUsers;
                $this->puTaggedTPUsersScheduledForDeletion->clear();
            }
            $this->puTaggedTPUsersScheduledForDeletion[]= $pUser;
        }

        return $this;
    }

    /**
     * Clears out the collPDDebates collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return PTag The current object (for fluent API support)
     * @see        addPDDebates()
     */
    public function clearPDDebates()
    {
        $this->collPDDebates = null; // important to set this to null since that means it is uninitialized
        $this->collPDDebatesPartial = null;

        return $this;
    }

    /**
     * Initializes the collPDDebates collection.
     *
     * By default this just sets the collPDDebates collection to an empty collection (like clearPDDebates());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @return void
     */
    public function initPDDebates()
    {
        $this->collPDDebates = new PropelObjectCollection();
        $this->collPDDebates->setModel('PDDebate');
    }

    /**
     * Gets a collection of PDDebate objects related by a many-to-many relationship
     * to the current object by way of the p_d_d_tagged_t cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this PTag is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria Optional query object to filter the query
     * @param PropelPDO $con Optional connection object
     *
     * @return PropelObjectCollection|PDDebate[] List of PDDebate objects
     */
    public function getPDDebates($criteria = null, PropelPDO $con = null)
    {
        if (null === $this->collPDDebates || null !== $criteria) {
            if ($this->isNew() && null === $this->collPDDebates) {
                // return empty collection
                $this->initPDDebates();
            } else {
                $collPDDebates = PDDebateQuery::create(null, $criteria)
                    ->filterByPTag($this)
                    ->find($con);
                if (null !== $criteria) {
                    return $collPDDebates;
                }
                $this->collPDDebates = $collPDDebates;
            }
        }

        return $this->collPDDebates;
    }

    /**
     * Sets a collection of PDDebate objects related by a many-to-many relationship
     * to the current object by way of the p_d_d_tagged_t cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $pDDebates A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return PTag The current object (for fluent API support)
     */
    public function setPDDebates(PropelCollection $pDDebates, PropelPDO $con = null)
    {
        $this->clearPDDebates();
        $currentPDDebates = $this->getPDDebates(null, $con);

        $this->pDDebatesScheduledForDeletion = $currentPDDebates->diff($pDDebates);

        foreach ($pDDebates as $pDDebate) {
            if (!$currentPDDebates->contains($pDDebate)) {
                $this->doAddPDDebate($pDDebate);
            }
        }

        $this->collPDDebates = $pDDebates;

        return $this;
    }

    /**
     * Gets the number of PDDebate objects related by a many-to-many relationship
     * to the current object by way of the p_d_d_tagged_t cross-reference table.
     *
     * @param Criteria $criteria Optional query object to filter the query
     * @param boolean $distinct Set to true to force count distinct
     * @param PropelPDO $con Optional connection object
     *
     * @return int the number of related PDDebate objects
     */
    public function countPDDebates($criteria = null, $distinct = false, PropelPDO $con = null)
    {
        if (null === $this->collPDDebates || null !== $criteria) {
            if ($this->isNew() && null === $this->collPDDebates) {
                return 0;
            } else {
                $query = PDDebateQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByPTag($this)
                    ->count($con);
            }
        } else {
            return count($this->collPDDebates);
        }
    }

    /**
     * Associate a PDDebate object to this object
     * through the p_d_d_tagged_t cross reference table.
     *
     * @param  PDDebate $pDDebate The PDDTaggedT object to relate
     * @return PTag The current object (for fluent API support)
     */
    public function addPDDebate(PDDebate $pDDebate)
    {
        if ($this->collPDDebates === null) {
            $this->initPDDebates();
        }

        if (!$this->collPDDebates->contains($pDDebate)) { // only add it if the **same** object is not already associated
            $this->doAddPDDebate($pDDebate);
            $this->collPDDebates[] = $pDDebate;

            if ($this->pDDebatesScheduledForDeletion and $this->pDDebatesScheduledForDeletion->contains($pDDebate)) {
                $this->pDDebatesScheduledForDeletion->remove($this->pDDebatesScheduledForDeletion->search($pDDebate));
            }
        }

        return $this;
    }

    /**
     * @param	PDDebate $pDDebate The pDDebate object to add.
     */
    protected function doAddPDDebate(PDDebate $pDDebate)
    {
        // set the back reference to this object directly as using provided method either results
        // in endless loop or in multiple relations
        if (!$pDDebate->getPTags()->contains($this)) { $pDDTaggedT = new PDDTaggedT();
            $pDDTaggedT->setPDDebate($pDDebate);
            $this->addPDDTaggedT($pDDTaggedT);

            $foreignCollection = $pDDebate->getPTags();
            $foreignCollection[] = $this;
        }
    }

    /**
     * Remove a PDDebate object to this object
     * through the p_d_d_tagged_t cross reference table.
     *
     * @param PDDebate $pDDebate The PDDTaggedT object to relate
     * @return PTag The current object (for fluent API support)
     */
    public function removePDDebate(PDDebate $pDDebate)
    {
        if ($this->getPDDebates()->contains($pDDebate)) {
            $this->collPDDebates->remove($this->collPDDebates->search($pDDebate));
            if (null === $this->pDDebatesScheduledForDeletion) {
                $this->pDDebatesScheduledForDeletion = clone $this->collPDDebates;
                $this->pDDebatesScheduledForDeletion->clear();
            }
            $this->pDDebatesScheduledForDeletion[]= $pDDebate;
        }

        return $this;
    }

    /**
     * Clears out the collPDReactions collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return PTag The current object (for fluent API support)
     * @see        addPDReactions()
     */
    public function clearPDReactions()
    {
        $this->collPDReactions = null; // important to set this to null since that means it is uninitialized
        $this->collPDReactionsPartial = null;

        return $this;
    }

    /**
     * Initializes the collPDReactions collection.
     *
     * By default this just sets the collPDReactions collection to an empty collection (like clearPDReactions());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @return void
     */
    public function initPDReactions()
    {
        $this->collPDReactions = new PropelObjectCollection();
        $this->collPDReactions->setModel('PDReaction');
    }

    /**
     * Gets a collection of PDReaction objects related by a many-to-many relationship
     * to the current object by way of the p_d_r_tagged_t cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this PTag is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria Optional query object to filter the query
     * @param PropelPDO $con Optional connection object
     *
     * @return PropelObjectCollection|PDReaction[] List of PDReaction objects
     */
    public function getPDReactions($criteria = null, PropelPDO $con = null)
    {
        if (null === $this->collPDReactions || null !== $criteria) {
            if ($this->isNew() && null === $this->collPDReactions) {
                // return empty collection
                $this->initPDReactions();
            } else {
                $collPDReactions = PDReactionQuery::create(null, $criteria)
                    ->filterByPTag($this)
                    ->find($con);
                if (null !== $criteria) {
                    return $collPDReactions;
                }
                $this->collPDReactions = $collPDReactions;
            }
        }

        return $this->collPDReactions;
    }

    /**
     * Sets a collection of PDReaction objects related by a many-to-many relationship
     * to the current object by way of the p_d_r_tagged_t cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $pDReactions A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return PTag The current object (for fluent API support)
     */
    public function setPDReactions(PropelCollection $pDReactions, PropelPDO $con = null)
    {
        $this->clearPDReactions();
        $currentPDReactions = $this->getPDReactions(null, $con);

        $this->pDReactionsScheduledForDeletion = $currentPDReactions->diff($pDReactions);

        foreach ($pDReactions as $pDReaction) {
            if (!$currentPDReactions->contains($pDReaction)) {
                $this->doAddPDReaction($pDReaction);
            }
        }

        $this->collPDReactions = $pDReactions;

        return $this;
    }

    /**
     * Gets the number of PDReaction objects related by a many-to-many relationship
     * to the current object by way of the p_d_r_tagged_t cross-reference table.
     *
     * @param Criteria $criteria Optional query object to filter the query
     * @param boolean $distinct Set to true to force count distinct
     * @param PropelPDO $con Optional connection object
     *
     * @return int the number of related PDReaction objects
     */
    public function countPDReactions($criteria = null, $distinct = false, PropelPDO $con = null)
    {
        if (null === $this->collPDReactions || null !== $criteria) {
            if ($this->isNew() && null === $this->collPDReactions) {
                return 0;
            } else {
                $query = PDReactionQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByPTag($this)
                    ->count($con);
            }
        } else {
            return count($this->collPDReactions);
        }
    }

    /**
     * Associate a PDReaction object to this object
     * through the p_d_r_tagged_t cross reference table.
     *
     * @param  PDReaction $pDReaction The PDRTaggedT object to relate
     * @return PTag The current object (for fluent API support)
     */
    public function addPDReaction(PDReaction $pDReaction)
    {
        if ($this->collPDReactions === null) {
            $this->initPDReactions();
        }

        if (!$this->collPDReactions->contains($pDReaction)) { // only add it if the **same** object is not already associated
            $this->doAddPDReaction($pDReaction);
            $this->collPDReactions[] = $pDReaction;

            if ($this->pDReactionsScheduledForDeletion and $this->pDReactionsScheduledForDeletion->contains($pDReaction)) {
                $this->pDReactionsScheduledForDeletion->remove($this->pDReactionsScheduledForDeletion->search($pDReaction));
            }
        }

        return $this;
    }

    /**
     * @param	PDReaction $pDReaction The pDReaction object to add.
     */
    protected function doAddPDReaction(PDReaction $pDReaction)
    {
        // set the back reference to this object directly as using provided method either results
        // in endless loop or in multiple relations
        if (!$pDReaction->getPTags()->contains($this)) { $pDRTaggedT = new PDRTaggedT();
            $pDRTaggedT->setPDReaction($pDReaction);
            $this->addPDRTaggedT($pDRTaggedT);

            $foreignCollection = $pDReaction->getPTags();
            $foreignCollection[] = $this;
        }
    }

    /**
     * Remove a PDReaction object to this object
     * through the p_d_r_tagged_t cross reference table.
     *
     * @param PDReaction $pDReaction The PDRTaggedT object to relate
     * @return PTag The current object (for fluent API support)
     */
    public function removePDReaction(PDReaction $pDReaction)
    {
        if ($this->getPDReactions()->contains($pDReaction)) {
            $this->collPDReactions->remove($this->collPDReactions->search($pDReaction));
            if (null === $this->pDReactionsScheduledForDeletion) {
                $this->pDReactionsScheduledForDeletion = clone $this->collPDReactions;
                $this->pDReactionsScheduledForDeletion->clear();
            }
            $this->pDReactionsScheduledForDeletion[]= $pDReaction;
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
        $this->p_t_tag_type_id = null;
        $this->p_t_parent_id = null;
        $this->p_user_id = null;
        $this->title = null;
        $this->moderated = null;
        $this->moderated_at = null;
        $this->online = null;
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
            if ($this->collPTagsRelatedById) {
                foreach ($this->collPTagsRelatedById as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPuTaggedTPTags) {
                foreach ($this->collPuTaggedTPTags as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPDDTaggedTs) {
                foreach ($this->collPDDTaggedTs as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPDRTaggedTs) {
                foreach ($this->collPDRTaggedTs as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPLRegions) {
                foreach ($this->collPLRegions as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPLDepartments) {
                foreach ($this->collPLDepartments as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPuTaggedTPUsers) {
                foreach ($this->collPuTaggedTPUsers as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPDDebates) {
                foreach ($this->collPDDebates as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPDReactions) {
                foreach ($this->collPDReactions as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->aPTTagType instanceof Persistent) {
              $this->aPTTagType->clearAllReferences($deep);
            }
            if ($this->aPTagRelatedByPTParentId instanceof Persistent) {
              $this->aPTagRelatedByPTParentId->clearAllReferences($deep);
            }
            if ($this->aPUser instanceof Persistent) {
              $this->aPUser->clearAllReferences($deep);
            }

            $this->alreadyInClearAllReferencesDeep = false;
        } // if ($deep)

        if ($this->collPTagsRelatedById instanceof PropelCollection) {
            $this->collPTagsRelatedById->clearIterator();
        }
        $this->collPTagsRelatedById = null;
        if ($this->collPuTaggedTPTags instanceof PropelCollection) {
            $this->collPuTaggedTPTags->clearIterator();
        }
        $this->collPuTaggedTPTags = null;
        if ($this->collPDDTaggedTs instanceof PropelCollection) {
            $this->collPDDTaggedTs->clearIterator();
        }
        $this->collPDDTaggedTs = null;
        if ($this->collPDRTaggedTs instanceof PropelCollection) {
            $this->collPDRTaggedTs->clearIterator();
        }
        $this->collPDRTaggedTs = null;
        if ($this->collPLRegions instanceof PropelCollection) {
            $this->collPLRegions->clearIterator();
        }
        $this->collPLRegions = null;
        if ($this->collPLDepartments instanceof PropelCollection) {
            $this->collPLDepartments->clearIterator();
        }
        $this->collPLDepartments = null;
        if ($this->collPuTaggedTPUsers instanceof PropelCollection) {
            $this->collPuTaggedTPUsers->clearIterator();
        }
        $this->collPuTaggedTPUsers = null;
        if ($this->collPDDebates instanceof PropelCollection) {
            $this->collPDDebates->clearIterator();
        }
        $this->collPDDebates = null;
        if ($this->collPDReactions instanceof PropelCollection) {
            $this->collPDReactions->clearIterator();
        }
        $this->collPDReactions = null;
        $this->aPTTagType = null;
        $this->aPTagRelatedByPTParentId = null;
        $this->aPUser = null;
    }

    /**
     * return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(PTagPeer::DEFAULT_STRING_FORMAT);
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
     * @return     PTag The current object (for fluent API support)
     */
    public function keepUpdateDateUnchanged()
    {
        $this->modifiedColumns[] = PTagPeer::UPDATED_AT;

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

         $query = PTagQuery::create('q')
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
    * If permanent UUID, throw exception p_tag.uuid*/
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
     * @return     PTagArchive An archive object, or null if the current object was never archived
     */
    public function getArchive(PropelPDO $con = null)
    {
        if ($this->isNew()) {
            return null;
        }
        $archive = PTagArchiveQuery::create()
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
     * @return     PTagArchive The archive object based on this object
     */
    public function archive(PropelPDO $con = null)
    {
        if ($this->isNew()) {
            throw new PropelException('New objects cannot be archived. You must save the current object before calling archive().');
        }
        if (!$archive = $this->getArchive($con)) {
            $archive = new PTagArchive();
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
     * @return PTag The current object (for fluent API support)
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
     * @param      PTagArchive $archive An archived object based on the same class
      * @param      Boolean $populateAutoIncrementPrimaryKeys
     *               If true, autoincrement columns are copied from the archive object.
     *               If false, autoincrement columns are left intact.
      *
     * @return     PTag The current object (for fluent API support)
     */
    public function populateFromArchive($archive, $populateAutoIncrementPrimaryKeys = false) {
        if ($populateAutoIncrementPrimaryKeys) {
            $this->setId($archive->getId());
        }
        $this->setUuid($archive->getUuid());
        $this->setPTTagTypeId($archive->getPTTagTypeId());
        $this->setPTParentId($archive->getPTParentId());
        $this->setPUserId($archive->getPUserId());
        $this->setTitle($archive->getTitle());
        $this->setModerated($archive->getModerated());
        $this->setModeratedAt($archive->getModeratedAt());
        $this->setOnline($archive->getOnline());
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
     * @return     PTag The current object (for fluent API support)
     */
    public function deleteWithoutArchive(PropelPDO $con = null)
    {
        $this->archiveOnDelete = false;

        return $this->delete($con);
    }

}
