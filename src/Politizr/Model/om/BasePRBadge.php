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
use Politizr\Model\PRBadge;
use Politizr\Model\PRBadgeArchive;
use Politizr\Model\PRBadgeArchiveQuery;
use Politizr\Model\PRBadgeFamily;
use Politizr\Model\PRBadgeFamilyQuery;
use Politizr\Model\PRBadgePeer;
use Politizr\Model\PRBadgeQuery;
use Politizr\Model\PRMetalType;
use Politizr\Model\PRMetalTypeQuery;
use Politizr\Model\PUBadge;
use Politizr\Model\PUBadgeQuery;
use Politizr\Model\PUser;
use Politizr\Model\PUserQuery;

abstract class BasePRBadge extends BaseObject implements Persistent
{
    /**
     * Peer class name
     */
    const PEER = 'Politizr\\Model\\PRBadgePeer';

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        PRBadgePeer
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
     * The value for the p_r_metal_type_id field.
     * @var        int
     */
    protected $p_r_metal_type_id;

    /**
     * The value for the p_r_badge_family_id field.
     * @var        int
     */
    protected $p_r_badge_family_id;

    /**
     * The value for the title field.
     * @var        string
     */
    protected $title;

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
     * @var        PRMetalType
     */
    protected $aPRMetalType;

    /**
     * @var        PRBadgeFamily
     */
    protected $aPRBadgeFamily;

    /**
     * @var        PropelObjectCollection|PUBadge[] Collection to store aggregation of PUBadge objects.
     */
    protected $collPUBadges;
    protected $collPUBadgesPartial;

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

    // archivable behavior
    protected $archiveOnDelete = true;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $pUsersScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $pUBadgesScheduledForDeletion = null;

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
     * Get the [p_r_metal_type_id] column value.
     *
     * @return int
     */
    public function getPRMetalTypeId()
    {

        return $this->p_r_metal_type_id;
    }

    /**
     * Get the [p_r_badge_family_id] column value.
     *
     * @return int
     */
    public function getPRBadgeFamilyId()
    {

        return $this->p_r_badge_family_id;
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
     * @return PRBadge The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[] = PRBadgePeer::ID;
        }


        return $this;
    } // setId()

    /**
     * Set the value of [uuid] column.
     *
     * @param  string $v new value
     * @return PRBadge The current object (for fluent API support)
     */
    public function setUuid($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->uuid !== $v) {
            $this->uuid = $v;
            $this->modifiedColumns[] = PRBadgePeer::UUID;
        }


        return $this;
    } // setUuid()

    /**
     * Set the value of [p_r_metal_type_id] column.
     *
     * @param  int $v new value
     * @return PRBadge The current object (for fluent API support)
     */
    public function setPRMetalTypeId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->p_r_metal_type_id !== $v) {
            $this->p_r_metal_type_id = $v;
            $this->modifiedColumns[] = PRBadgePeer::P_R_METAL_TYPE_ID;
        }

        if ($this->aPRMetalType !== null && $this->aPRMetalType->getId() !== $v) {
            $this->aPRMetalType = null;
        }


        return $this;
    } // setPRMetalTypeId()

    /**
     * Set the value of [p_r_badge_family_id] column.
     *
     * @param  int $v new value
     * @return PRBadge The current object (for fluent API support)
     */
    public function setPRBadgeFamilyId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->p_r_badge_family_id !== $v) {
            // sortable behavior
            $this->oldScope = $this->p_r_badge_family_id;

            $this->p_r_badge_family_id = $v;
            $this->modifiedColumns[] = PRBadgePeer::P_R_BADGE_FAMILY_ID;
        }

        if ($this->aPRBadgeFamily !== null && $this->aPRBadgeFamily->getId() !== $v) {
            $this->aPRBadgeFamily = null;
        }


        return $this;
    } // setPRBadgeFamilyId()

    /**
     * Set the value of [title] column.
     *
     * @param  string $v new value
     * @return PRBadge The current object (for fluent API support)
     */
    public function setTitle($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->title !== $v) {
            $this->title = $v;
            $this->modifiedColumns[] = PRBadgePeer::TITLE;
        }


        return $this;
    } // setTitle()

    /**
     * Sets the value of the [online] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param boolean|integer|string $v The new value
     * @return PRBadge The current object (for fluent API support)
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
            $this->modifiedColumns[] = PRBadgePeer::ONLINE;
        }


        return $this;
    } // setOnline()

    /**
     * Sets the value of [created_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return PRBadge The current object (for fluent API support)
     */
    public function setCreatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->created_at !== null || $dt !== null) {
            $currentDateAsString = ($this->created_at !== null && $tmpDt = new DateTime($this->created_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->created_at = $newDateAsString;
                $this->modifiedColumns[] = PRBadgePeer::CREATED_AT;
            }
        } // if either are not null


        return $this;
    } // setCreatedAt()

    /**
     * Sets the value of [updated_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return PRBadge The current object (for fluent API support)
     */
    public function setUpdatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->updated_at !== null || $dt !== null) {
            $currentDateAsString = ($this->updated_at !== null && $tmpDt = new DateTime($this->updated_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->updated_at = $newDateAsString;
                $this->modifiedColumns[] = PRBadgePeer::UPDATED_AT;
            }
        } // if either are not null


        return $this;
    } // setUpdatedAt()

    /**
     * Set the value of [slug] column.
     *
     * @param  string $v new value
     * @return PRBadge The current object (for fluent API support)
     */
    public function setSlug($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->slug !== $v) {
            $this->slug = $v;
            $this->modifiedColumns[] = PRBadgePeer::SLUG;
        }


        return $this;
    } // setSlug()

    /**
     * Set the value of [sortable_rank] column.
     *
     * @param  int $v new value
     * @return PRBadge The current object (for fluent API support)
     */
    public function setSortableRank($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->sortable_rank !== $v) {
            $this->sortable_rank = $v;
            $this->modifiedColumns[] = PRBadgePeer::SORTABLE_RANK;
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
            $this->p_r_metal_type_id = ($row[$startcol + 2] !== null) ? (int) $row[$startcol + 2] : null;
            $this->p_r_badge_family_id = ($row[$startcol + 3] !== null) ? (int) $row[$startcol + 3] : null;
            $this->title = ($row[$startcol + 4] !== null) ? (string) $row[$startcol + 4] : null;
            $this->online = ($row[$startcol + 5] !== null) ? (boolean) $row[$startcol + 5] : null;
            $this->created_at = ($row[$startcol + 6] !== null) ? (string) $row[$startcol + 6] : null;
            $this->updated_at = ($row[$startcol + 7] !== null) ? (string) $row[$startcol + 7] : null;
            $this->slug = ($row[$startcol + 8] !== null) ? (string) $row[$startcol + 8] : null;
            $this->sortable_rank = ($row[$startcol + 9] !== null) ? (int) $row[$startcol + 9] : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }
            $this->postHydrate($row, $startcol, $rehydrate);

            return $startcol + 10; // 10 = PRBadgePeer::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating PRBadge object", $e);
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

        if ($this->aPRMetalType !== null && $this->p_r_metal_type_id !== $this->aPRMetalType->getId()) {
            $this->aPRMetalType = null;
        }
        if ($this->aPRBadgeFamily !== null && $this->p_r_badge_family_id !== $this->aPRBadgeFamily->getId()) {
            $this->aPRBadgeFamily = null;
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
            $con = Propel::getConnection(PRBadgePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $stmt = PRBadgePeer::doSelectStmt($this->buildPkeyCriteria(), $con);
        $row = $stmt->fetch(PDO::FETCH_NUM);
        $stmt->closeCursor();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aPRMetalType = null;
            $this->aPRBadgeFamily = null;
            $this->collPUBadges = null;

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
            $con = Propel::getConnection(PRBadgePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        try {
            $deleteQuery = PRBadgeQuery::create()
                ->filterByPrimaryKey($this->getPrimaryKey());
            $ret = $this->preDelete($con);
            // sortable behavior

            PRBadgePeer::shiftRank(-1, $this->getSortableRank() + 1, null, $this->getScopeValue(), $con);
            PRBadgePeer::clearInstancePool();

            // archivable behavior
            if ($ret) {
                if ($this->archiveOnDelete) {
                    // do nothing yet. The object will be archived later when calling PRBadgeQuery::delete().
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
            $con = Propel::getConnection(PRBadgePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        $isInsert = $this->isNew();
        try {
            $ret = $this->preSave($con);
            // sluggable behavior

            if ($this->isColumnModified(PRBadgePeer::SLUG) && $this->getSlug()) {
                $this->setSlug($this->makeSlugUnique($this->getSlug()));
            } elseif ($this->isColumnModified(PRBadgePeer::TITLE)) {
                $this->setSlug($this->createSlug());
            } elseif (!$this->getSlug()) {
                $this->setSlug($this->createSlug());
            }
            // sortable behavior
            $this->processSortableQueries($con);
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
                // timestampable behavior
                if (!$this->isColumnModified(PRBadgePeer::CREATED_AT)) {
                    $this->setCreatedAt(time());
                }
                if (!$this->isColumnModified(PRBadgePeer::UPDATED_AT)) {
                    $this->setUpdatedAt(time());
                }
                // sortable behavior
                if (!$this->isColumnModified(PRBadgePeer::RANK_COL)) {
                    $this->setSortableRank(PRBadgeQuery::create()->getMaxRankArray($this->getScopeValue(), $con) + 1);
                }

            } else {
                $ret = $ret && $this->preUpdate($con);
                // timestampable behavior
                if ($this->isModified() && !$this->isColumnModified(PRBadgePeer::UPDATED_AT)) {
                    $this->setUpdatedAt(time());
                }
                // sortable behavior
                // if scope has changed and rank was not modified (if yes, assuming superior action)
                // insert object to the end of new scope and cleanup old one
                if (($this->isColumnModified(PRBadgePeer::P_R_BADGE_FAMILY_ID)) && !$this->isColumnModified(PRBadgePeer::RANK_COL)) { PRBadgePeer::shiftRank(-1, $this->getSortableRank() + 1, null, $this->oldScope, $con);
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
                PRBadgePeer::addInstanceToPool($this);
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

            if ($this->aPRMetalType !== null) {
                if ($this->aPRMetalType->isModified() || $this->aPRMetalType->isNew()) {
                    $affectedRows += $this->aPRMetalType->save($con);
                }
                $this->setPRMetalType($this->aPRMetalType);
            }

            if ($this->aPRBadgeFamily !== null) {
                if ($this->aPRBadgeFamily->isModified() || $this->aPRBadgeFamily->isNew()) {
                    $affectedRows += $this->aPRBadgeFamily->save($con);
                }
                $this->setPRBadgeFamily($this->aPRBadgeFamily);
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

            if ($this->pUsersScheduledForDeletion !== null) {
                if (!$this->pUsersScheduledForDeletion->isEmpty()) {
                    $pks = array();
                    $pk = $this->getPrimaryKey();
                    foreach ($this->pUsersScheduledForDeletion->getPrimaryKeys(false) as $remotePk) {
                        $pks[] = array($remotePk, $pk);
                    }
                    PUBadgeQuery::create()
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

            if ($this->pUBadgesScheduledForDeletion !== null) {
                if (!$this->pUBadgesScheduledForDeletion->isEmpty()) {
                    PUBadgeQuery::create()
                        ->filterByPrimaryKeys($this->pUBadgesScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->pUBadgesScheduledForDeletion = null;
                }
            }

            if ($this->collPUBadges !== null) {
                foreach ($this->collPUBadges as $referrerFK) {
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

        $this->modifiedColumns[] = PRBadgePeer::ID;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . PRBadgePeer::ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(PRBadgePeer::ID)) {
            $modifiedColumns[':p' . $index++]  = '`id`';
        }
        if ($this->isColumnModified(PRBadgePeer::UUID)) {
            $modifiedColumns[':p' . $index++]  = '`uuid`';
        }
        if ($this->isColumnModified(PRBadgePeer::P_R_METAL_TYPE_ID)) {
            $modifiedColumns[':p' . $index++]  = '`p_r_metal_type_id`';
        }
        if ($this->isColumnModified(PRBadgePeer::P_R_BADGE_FAMILY_ID)) {
            $modifiedColumns[':p' . $index++]  = '`p_r_badge_family_id`';
        }
        if ($this->isColumnModified(PRBadgePeer::TITLE)) {
            $modifiedColumns[':p' . $index++]  = '`title`';
        }
        if ($this->isColumnModified(PRBadgePeer::ONLINE)) {
            $modifiedColumns[':p' . $index++]  = '`online`';
        }
        if ($this->isColumnModified(PRBadgePeer::CREATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`created_at`';
        }
        if ($this->isColumnModified(PRBadgePeer::UPDATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`updated_at`';
        }
        if ($this->isColumnModified(PRBadgePeer::SLUG)) {
            $modifiedColumns[':p' . $index++]  = '`slug`';
        }
        if ($this->isColumnModified(PRBadgePeer::SORTABLE_RANK)) {
            $modifiedColumns[':p' . $index++]  = '`sortable_rank`';
        }

        $sql = sprintf(
            'INSERT INTO `p_r_badge` (%s) VALUES (%s)',
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
                    case '`p_r_metal_type_id`':
                        $stmt->bindValue($identifier, $this->p_r_metal_type_id, PDO::PARAM_INT);
                        break;
                    case '`p_r_badge_family_id`':
                        $stmt->bindValue($identifier, $this->p_r_badge_family_id, PDO::PARAM_INT);
                        break;
                    case '`title`':
                        $stmt->bindValue($identifier, $this->title, PDO::PARAM_STR);
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
        $pos = PRBadgePeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                return $this->getPRMetalTypeId();
                break;
            case 3:
                return $this->getPRBadgeFamilyId();
                break;
            case 4:
                return $this->getTitle();
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
            case 8:
                return $this->getSlug();
                break;
            case 9:
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
        if (isset($alreadyDumpedObjects['PRBadge'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['PRBadge'][$this->getPrimaryKey()] = true;
        $keys = PRBadgePeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getUuid(),
            $keys[2] => $this->getPRMetalTypeId(),
            $keys[3] => $this->getPRBadgeFamilyId(),
            $keys[4] => $this->getTitle(),
            $keys[5] => $this->getOnline(),
            $keys[6] => $this->getCreatedAt(),
            $keys[7] => $this->getUpdatedAt(),
            $keys[8] => $this->getSlug(),
            $keys[9] => $this->getSortableRank(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aPRMetalType) {
                $result['PRMetalType'] = $this->aPRMetalType->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aPRBadgeFamily) {
                $result['PRBadgeFamily'] = $this->aPRBadgeFamily->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->collPUBadges) {
                $result['PUBadges'] = $this->collPUBadges->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
        $pos = PRBadgePeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);

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
                $this->setPRMetalTypeId($value);
                break;
            case 3:
                $this->setPRBadgeFamilyId($value);
                break;
            case 4:
                $this->setTitle($value);
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
            case 8:
                $this->setSlug($value);
                break;
            case 9:
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
        $keys = PRBadgePeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setUuid($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setPRMetalTypeId($arr[$keys[2]]);
        if (array_key_exists($keys[3], $arr)) $this->setPRBadgeFamilyId($arr[$keys[3]]);
        if (array_key_exists($keys[4], $arr)) $this->setTitle($arr[$keys[4]]);
        if (array_key_exists($keys[5], $arr)) $this->setOnline($arr[$keys[5]]);
        if (array_key_exists($keys[6], $arr)) $this->setCreatedAt($arr[$keys[6]]);
        if (array_key_exists($keys[7], $arr)) $this->setUpdatedAt($arr[$keys[7]]);
        if (array_key_exists($keys[8], $arr)) $this->setSlug($arr[$keys[8]]);
        if (array_key_exists($keys[9], $arr)) $this->setSortableRank($arr[$keys[9]]);
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(PRBadgePeer::DATABASE_NAME);

        if ($this->isColumnModified(PRBadgePeer::ID)) $criteria->add(PRBadgePeer::ID, $this->id);
        if ($this->isColumnModified(PRBadgePeer::UUID)) $criteria->add(PRBadgePeer::UUID, $this->uuid);
        if ($this->isColumnModified(PRBadgePeer::P_R_METAL_TYPE_ID)) $criteria->add(PRBadgePeer::P_R_METAL_TYPE_ID, $this->p_r_metal_type_id);
        if ($this->isColumnModified(PRBadgePeer::P_R_BADGE_FAMILY_ID)) $criteria->add(PRBadgePeer::P_R_BADGE_FAMILY_ID, $this->p_r_badge_family_id);
        if ($this->isColumnModified(PRBadgePeer::TITLE)) $criteria->add(PRBadgePeer::TITLE, $this->title);
        if ($this->isColumnModified(PRBadgePeer::ONLINE)) $criteria->add(PRBadgePeer::ONLINE, $this->online);
        if ($this->isColumnModified(PRBadgePeer::CREATED_AT)) $criteria->add(PRBadgePeer::CREATED_AT, $this->created_at);
        if ($this->isColumnModified(PRBadgePeer::UPDATED_AT)) $criteria->add(PRBadgePeer::UPDATED_AT, $this->updated_at);
        if ($this->isColumnModified(PRBadgePeer::SLUG)) $criteria->add(PRBadgePeer::SLUG, $this->slug);
        if ($this->isColumnModified(PRBadgePeer::SORTABLE_RANK)) $criteria->add(PRBadgePeer::SORTABLE_RANK, $this->sortable_rank);

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
        $criteria = new Criteria(PRBadgePeer::DATABASE_NAME);
        $criteria->add(PRBadgePeer::ID, $this->id);

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
     * @param object $copyObj An object of PRBadge (or compatible) type.
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setUuid($this->getUuid());
        $copyObj->setPRMetalTypeId($this->getPRMetalTypeId());
        $copyObj->setPRBadgeFamilyId($this->getPRBadgeFamilyId());
        $copyObj->setTitle($this->getTitle());
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

            foreach ($this->getPUBadges() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPUBadge($relObj->copy($deepCopy));
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
     * @return PRBadge Clone of current object.
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
     * @return PRBadgePeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new PRBadgePeer();
        }

        return self::$peer;
    }

    /**
     * Declares an association between this object and a PRMetalType object.
     *
     * @param                  PRMetalType $v
     * @return PRBadge The current object (for fluent API support)
     * @throws PropelException
     */
    public function setPRMetalType(PRMetalType $v = null)
    {
        if ($v === null) {
            $this->setPRMetalTypeId(NULL);
        } else {
            $this->setPRMetalTypeId($v->getId());
        }

        $this->aPRMetalType = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the PRMetalType object, it will not be re-added.
        if ($v !== null) {
            $v->addPRBadge($this);
        }


        return $this;
    }


    /**
     * Get the associated PRMetalType object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return PRMetalType The associated PRMetalType object.
     * @throws PropelException
     */
    public function getPRMetalType(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aPRMetalType === null && ($this->p_r_metal_type_id !== null) && $doQuery) {
            $this->aPRMetalType = PRMetalTypeQuery::create()->findPk($this->p_r_metal_type_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aPRMetalType->addPRBadges($this);
             */
        }

        return $this->aPRMetalType;
    }

    /**
     * Declares an association between this object and a PRBadgeFamily object.
     *
     * @param                  PRBadgeFamily $v
     * @return PRBadge The current object (for fluent API support)
     * @throws PropelException
     */
    public function setPRBadgeFamily(PRBadgeFamily $v = null)
    {
        if ($v === null) {
            $this->setPRBadgeFamilyId(NULL);
        } else {
            $this->setPRBadgeFamilyId($v->getId());
        }

        $this->aPRBadgeFamily = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the PRBadgeFamily object, it will not be re-added.
        if ($v !== null) {
            $v->addPRBadge($this);
        }


        return $this;
    }


    /**
     * Get the associated PRBadgeFamily object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return PRBadgeFamily The associated PRBadgeFamily object.
     * @throws PropelException
     */
    public function getPRBadgeFamily(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aPRBadgeFamily === null && ($this->p_r_badge_family_id !== null) && $doQuery) {
            $this->aPRBadgeFamily = PRBadgeFamilyQuery::create()->findPk($this->p_r_badge_family_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aPRBadgeFamily->addPRBadges($this);
             */
        }

        return $this->aPRBadgeFamily;
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
        if ('PUBadge' == $relationName) {
            $this->initPUBadges();
        }
    }

    /**
     * Clears out the collPUBadges collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return PRBadge The current object (for fluent API support)
     * @see        addPUBadges()
     */
    public function clearPUBadges()
    {
        $this->collPUBadges = null; // important to set this to null since that means it is uninitialized
        $this->collPUBadgesPartial = null;

        return $this;
    }

    /**
     * reset is the collPUBadges collection loaded partially
     *
     * @return void
     */
    public function resetPartialPUBadges($v = true)
    {
        $this->collPUBadgesPartial = $v;
    }

    /**
     * Initializes the collPUBadges collection.
     *
     * By default this just sets the collPUBadges collection to an empty array (like clearcollPUBadges());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPUBadges($overrideExisting = true)
    {
        if (null !== $this->collPUBadges && !$overrideExisting) {
            return;
        }
        $this->collPUBadges = new PropelObjectCollection();
        $this->collPUBadges->setModel('PUBadge');
    }

    /**
     * Gets an array of PUBadge objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this PRBadge is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|PUBadge[] List of PUBadge objects
     * @throws PropelException
     */
    public function getPUBadges($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collPUBadgesPartial && !$this->isNew();
        if (null === $this->collPUBadges || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPUBadges) {
                // return empty collection
                $this->initPUBadges();
            } else {
                $collPUBadges = PUBadgeQuery::create(null, $criteria)
                    ->filterByPRBadge($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collPUBadgesPartial && count($collPUBadges)) {
                      $this->initPUBadges(false);

                      foreach ($collPUBadges as $obj) {
                        if (false == $this->collPUBadges->contains($obj)) {
                          $this->collPUBadges->append($obj);
                        }
                      }

                      $this->collPUBadgesPartial = true;
                    }

                    $collPUBadges->getInternalIterator()->rewind();

                    return $collPUBadges;
                }

                if ($partial && $this->collPUBadges) {
                    foreach ($this->collPUBadges as $obj) {
                        if ($obj->isNew()) {
                            $collPUBadges[] = $obj;
                        }
                    }
                }

                $this->collPUBadges = $collPUBadges;
                $this->collPUBadgesPartial = false;
            }
        }

        return $this->collPUBadges;
    }

    /**
     * Sets a collection of PUBadge objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $pUBadges A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return PRBadge The current object (for fluent API support)
     */
    public function setPUBadges(PropelCollection $pUBadges, PropelPDO $con = null)
    {
        $pUBadgesToDelete = $this->getPUBadges(new Criteria(), $con)->diff($pUBadges);


        $this->pUBadgesScheduledForDeletion = $pUBadgesToDelete;

        foreach ($pUBadgesToDelete as $pUBadgeRemoved) {
            $pUBadgeRemoved->setPRBadge(null);
        }

        $this->collPUBadges = null;
        foreach ($pUBadges as $pUBadge) {
            $this->addPUBadge($pUBadge);
        }

        $this->collPUBadges = $pUBadges;
        $this->collPUBadgesPartial = false;

        return $this;
    }

    /**
     * Returns the number of related PUBadge objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related PUBadge objects.
     * @throws PropelException
     */
    public function countPUBadges(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collPUBadgesPartial && !$this->isNew();
        if (null === $this->collPUBadges || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPUBadges) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPUBadges());
            }
            $query = PUBadgeQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPRBadge($this)
                ->count($con);
        }

        return count($this->collPUBadges);
    }

    /**
     * Method called to associate a PUBadge object to this object
     * through the PUBadge foreign key attribute.
     *
     * @param    PUBadge $l PUBadge
     * @return PRBadge The current object (for fluent API support)
     */
    public function addPUBadge(PUBadge $l)
    {
        if ($this->collPUBadges === null) {
            $this->initPUBadges();
            $this->collPUBadgesPartial = true;
        }

        if (!in_array($l, $this->collPUBadges->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddPUBadge($l);

            if ($this->pUBadgesScheduledForDeletion and $this->pUBadgesScheduledForDeletion->contains($l)) {
                $this->pUBadgesScheduledForDeletion->remove($this->pUBadgesScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	PUBadge $pUBadge The pUBadge object to add.
     */
    protected function doAddPUBadge($pUBadge)
    {
        $this->collPUBadges[]= $pUBadge;
        $pUBadge->setPRBadge($this);
    }

    /**
     * @param	PUBadge $pUBadge The pUBadge object to remove.
     * @return PRBadge The current object (for fluent API support)
     */
    public function removePUBadge($pUBadge)
    {
        if ($this->getPUBadges()->contains($pUBadge)) {
            $this->collPUBadges->remove($this->collPUBadges->search($pUBadge));
            if (null === $this->pUBadgesScheduledForDeletion) {
                $this->pUBadgesScheduledForDeletion = clone $this->collPUBadges;
                $this->pUBadgesScheduledForDeletion->clear();
            }
            $this->pUBadgesScheduledForDeletion[]= clone $pUBadge;
            $pUBadge->setPRBadge(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this PRBadge is new, it will return
     * an empty collection; or if this PRBadge has previously
     * been saved, it will retrieve related PUBadges from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in PRBadge.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|PUBadge[] List of PUBadge objects
     */
    public function getPUBadgesJoinPUser($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PUBadgeQuery::create(null, $criteria);
        $query->joinWith('PUser', $join_behavior);

        return $this->getPUBadges($query, $con);
    }

    /**
     * Clears out the collPUsers collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return PRBadge The current object (for fluent API support)
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
     * to the current object by way of the p_u_badge cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this PRBadge is new, it will return
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
                    ->filterByPRBadge($this)
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
     * to the current object by way of the p_u_badge cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $pUsers A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return PRBadge The current object (for fluent API support)
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
     * to the current object by way of the p_u_badge cross-reference table.
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
                    ->filterByPRBadge($this)
                    ->count($con);
            }
        } else {
            return count($this->collPUsers);
        }
    }

    /**
     * Associate a PUser object to this object
     * through the p_u_badge cross reference table.
     *
     * @param  PUser $pUser The PUBadge object to relate
     * @return PRBadge The current object (for fluent API support)
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
        if (!$pUser->getPRBadges()->contains($this)) { $pUBadge = new PUBadge();
            $pUBadge->setPUser($pUser);
            $this->addPUBadge($pUBadge);

            $foreignCollection = $pUser->getPRBadges();
            $foreignCollection[] = $this;
        }
    }

    /**
     * Remove a PUser object to this object
     * through the p_u_badge cross reference table.
     *
     * @param PUser $pUser The PUBadge object to relate
     * @return PRBadge The current object (for fluent API support)
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
        $this->p_r_metal_type_id = null;
        $this->p_r_badge_family_id = null;
        $this->title = null;
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
            if ($this->collPUBadges) {
                foreach ($this->collPUBadges as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPUsers) {
                foreach ($this->collPUsers as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->aPRMetalType instanceof Persistent) {
              $this->aPRMetalType->clearAllReferences($deep);
            }
            if ($this->aPRBadgeFamily instanceof Persistent) {
              $this->aPRBadgeFamily->clearAllReferences($deep);
            }

            $this->alreadyInClearAllReferencesDeep = false;
        } // if ($deep)

        if ($this->collPUBadges instanceof PropelCollection) {
            $this->collPUBadges->clearIterator();
        }
        $this->collPUBadges = null;
        if ($this->collPUsers instanceof PropelCollection) {
            $this->collPUsers->clearIterator();
        }
        $this->collPUsers = null;
        $this->aPRMetalType = null;
        $this->aPRBadgeFamily = null;
    }

    /**
     * return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(PRBadgePeer::DEFAULT_STRING_FORMAT);
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
     * @return     PRBadge The current object (for fluent API support)
     */
    public function keepUpdateDateUnchanged()
    {
        $this->modifiedColumns[] = PRBadgePeer::UPDATED_AT;

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

         $query = PRBadgeQuery::create('q')
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
    * If permanent UUID, throw exception p_r_badge.uuid*/
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
     * @return    PRBadge
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


        return $this->getPRBadgeFamilyId();

    }

    /**
     * Wrap the setter for scope value
     *
     * @param     mixed A array or a native type
     * @return    PRBadge
     */
    public function setScopeValue($v)
    {


        return $this->setPRBadgeFamilyId($v);

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
        return $this->getSortableRank() == PRBadgeQuery::create()->getMaxRankArray($this->getScopeValue(), $con);
    }

    /**
     * Get the next item in the list, i.e. the one for which rank is immediately higher
     *
     * @param     PropelPDO  $con      optional connection
     *
     * @return    PRBadge
     */
    public function getNext(PropelPDO $con = null)
    {

        $query = PRBadgeQuery::create();

        $scope = $this->getScopeValue();

        $query->filterByRank($this->getSortableRank() + 1, $scope);


        return $query->findOne($con);
    }

    /**
     * Get the previous item in the list, i.e. the one for which rank is immediately lower
     *
     * @param     PropelPDO  $con      optional connection
     *
     * @return    PRBadge
     */
    public function getPrevious(PropelPDO $con = null)
    {

        $query = PRBadgeQuery::create();

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
     * @return    PRBadge the current object
     *
     * @throws    PropelException
     */
    public function insertAtRank($rank, PropelPDO $con = null)
    {
        $maxRank = PRBadgeQuery::create()->getMaxRankArray($this->getScopeValue(), $con);
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
     * @return    PRBadge the current object
     *
     * @throws    PropelException
     */
    public function insertAtBottom(PropelPDO $con = null)
    {
        $this->setSortableRank(PRBadgeQuery::create()->getMaxRankArray($this->getScopeValue(), $con) + 1);

        return $this;
    }

    /**
     * Insert in the first rank
     * The modifications are not persisted until the object is saved.
     *
     * @return    PRBadge the current object
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
     * @return    PRBadge the current object
     *
     * @throws    PropelException
     */
    public function moveToRank($newRank, PropelPDO $con = null)
    {
        if ($this->isNew()) {
            throw new PropelException('New objects cannot be moved. Please use insertAtRank() instead');
        }
        if ($con === null) {
            $con = Propel::getConnection(PRBadgePeer::DATABASE_NAME);
        }
        if ($newRank < 1 || $newRank > PRBadgeQuery::create()->getMaxRankArray($this->getScopeValue(), $con)) {
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
            PRBadgePeer::shiftRank($delta, min($oldRank, $newRank), max($oldRank, $newRank), $this->getScopeValue(), $con);

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
     * @param     PRBadge $object
     * @param     PropelPDO $con optional connection
     *
     * @return    PRBadge the current object
     *
     * @throws Exception if the database cannot execute the two updates
     */
    public function swapWith($object, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(PRBadgePeer::DATABASE_NAME);
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
     * @return    PRBadge the current object
     */
    public function moveUp(PropelPDO $con = null)
    {
        if ($this->isFirst()) {
            return $this;
        }
        if ($con === null) {
            $con = Propel::getConnection(PRBadgePeer::DATABASE_NAME);
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
     * @return    PRBadge the current object
     */
    public function moveDown(PropelPDO $con = null)
    {
        if ($this->isLast($con)) {
            return $this;
        }
        if ($con === null) {
            $con = Propel::getConnection(PRBadgePeer::DATABASE_NAME);
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
     * @return    PRBadge the current object
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
            $con = Propel::getConnection(PRBadgePeer::DATABASE_NAME);
        }
        $con->beginTransaction();
        try {
            $bottom = PRBadgeQuery::create()->getMaxRankArray($this->getScopeValue(), $con);
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
     * @return    PRBadge the current object
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

    // archivable behavior

    /**
     * Get an archived version of the current object.
     *
     * @param PropelPDO $con Optional connection object
     *
     * @return     PRBadgeArchive An archive object, or null if the current object was never archived
     */
    public function getArchive(PropelPDO $con = null)
    {
        if ($this->isNew()) {
            return null;
        }
        $archive = PRBadgeArchiveQuery::create()
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
     * @return     PRBadgeArchive The archive object based on this object
     */
    public function archive(PropelPDO $con = null)
    {
        if ($this->isNew()) {
            throw new PropelException('New objects cannot be archived. You must save the current object before calling archive().');
        }
        if (!$archive = $this->getArchive($con)) {
            $archive = new PRBadgeArchive();
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
     * @return PRBadge The current object (for fluent API support)
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
     * @param      PRBadgeArchive $archive An archived object based on the same class
      * @param      Boolean $populateAutoIncrementPrimaryKeys
     *               If true, autoincrement columns are copied from the archive object.
     *               If false, autoincrement columns are left intact.
      *
     * @return     PRBadge The current object (for fluent API support)
     */
    public function populateFromArchive($archive, $populateAutoIncrementPrimaryKeys = false) {
        if ($populateAutoIncrementPrimaryKeys) {
            $this->setId($archive->getId());
        }
        $this->setUuid($archive->getUuid());
        $this->setPRMetalTypeId($archive->getPRMetalTypeId());
        $this->setPRBadgeFamilyId($archive->getPRBadgeFamilyId());
        $this->setTitle($archive->getTitle());
        $this->setOnline($archive->getOnline());
        $this->setCreatedAt($archive->getCreatedAt());
        $this->setUpdatedAt($archive->getUpdatedAt());
        $this->setSlug($archive->getSlug());
        $this->setSortableRank($archive->getSortableRank());

        return $this;
    }

    /**
     * Removes the object from the database without archiving it.
     *
     * @param PropelPDO $con Optional connection object
     *
     * @return     PRBadge The current object (for fluent API support)
     */
    public function deleteWithoutArchive(PropelPDO $con = null)
    {
        $this->archiveOnDelete = false;

        return $this->delete($con);
    }

}
