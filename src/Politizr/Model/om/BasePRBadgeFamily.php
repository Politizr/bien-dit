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
use Politizr\Model\PRBadgeFamily;
use Politizr\Model\PRBadgeFamilyArchive;
use Politizr\Model\PRBadgeFamilyArchiveQuery;
use Politizr\Model\PRBadgeFamilyPeer;
use Politizr\Model\PRBadgeFamilyQuery;
use Politizr\Model\PRBadgeQuery;
use Politizr\Model\PRBadgeType;
use Politizr\Model\PRBadgeTypeQuery;

abstract class BasePRBadgeFamily extends BaseObject implements Persistent
{
    /**
     * Peer class name
     */
    const PEER = 'Politizr\\Model\\PRBadgeFamilyPeer';

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        PRBadgeFamilyPeer
     */
    protected static $peer;

    /**
     * The flag var to prevent infinite loop in deep copy
     * @var       boolean
     */
    protected $startCopy = false;

    /**
     * The value for the p_r_badge_type_id field.
     * @var        int
     */
    protected $p_r_badge_type_id;

    /**
     * The value for the id field.
     * @var        int
     */
    protected $id;

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
     * The value for the sortable_rank field.
     * @var        int
     */
    protected $sortable_rank;

    /**
     * @var        PRBadgeType
     */
    protected $aPRBadgeType;

    /**
     * @var        PropelObjectCollection|PRBadge[] Collection to store aggregation of PRBadge objects.
     */
    protected $collPRBadges;
    protected $collPRBadgesPartial;

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
    protected $pRBadgesScheduledForDeletion = null;

    /**
     * Get the [p_r_badge_type_id] column value.
     *
     * @return int
     */
    public function getPRBadgeTypeId()
    {

        return $this->p_r_badge_type_id;
    }

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
     * Get the [sortable_rank] column value.
     *
     * @return int
     */
    public function getSortableRank()
    {

        return $this->sortable_rank;
    }

    /**
     * Set the value of [p_r_badge_type_id] column.
     *
     * @param  int $v new value
     * @return PRBadgeFamily The current object (for fluent API support)
     */
    public function setPRBadgeTypeId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->p_r_badge_type_id !== $v) {
            // sortable behavior
            $this->oldScope = $this->p_r_badge_type_id;

            $this->p_r_badge_type_id = $v;
            $this->modifiedColumns[] = PRBadgeFamilyPeer::P_R_BADGE_TYPE_ID;
        }

        if ($this->aPRBadgeType !== null && $this->aPRBadgeType->getId() !== $v) {
            $this->aPRBadgeType = null;
        }


        return $this;
    } // setPRBadgeTypeId()

    /**
     * Set the value of [id] column.
     *
     * @param  int $v new value
     * @return PRBadgeFamily The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[] = PRBadgeFamilyPeer::ID;
        }


        return $this;
    } // setId()

    /**
     * Set the value of [title] column.
     *
     * @param  string $v new value
     * @return PRBadgeFamily The current object (for fluent API support)
     */
    public function setTitle($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->title !== $v) {
            $this->title = $v;
            $this->modifiedColumns[] = PRBadgeFamilyPeer::TITLE;
        }


        return $this;
    } // setTitle()

    /**
     * Set the value of [description] column.
     *
     * @param  string $v new value
     * @return PRBadgeFamily The current object (for fluent API support)
     */
    public function setDescription($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->description !== $v) {
            $this->description = $v;
            $this->modifiedColumns[] = PRBadgeFamilyPeer::DESCRIPTION;
        }


        return $this;
    } // setDescription()

    /**
     * Sets the value of [created_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return PRBadgeFamily The current object (for fluent API support)
     */
    public function setCreatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->created_at !== null || $dt !== null) {
            $currentDateAsString = ($this->created_at !== null && $tmpDt = new DateTime($this->created_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->created_at = $newDateAsString;
                $this->modifiedColumns[] = PRBadgeFamilyPeer::CREATED_AT;
            }
        } // if either are not null


        return $this;
    } // setCreatedAt()

    /**
     * Sets the value of [updated_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return PRBadgeFamily The current object (for fluent API support)
     */
    public function setUpdatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->updated_at !== null || $dt !== null) {
            $currentDateAsString = ($this->updated_at !== null && $tmpDt = new DateTime($this->updated_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->updated_at = $newDateAsString;
                $this->modifiedColumns[] = PRBadgeFamilyPeer::UPDATED_AT;
            }
        } // if either are not null


        return $this;
    } // setUpdatedAt()

    /**
     * Set the value of [sortable_rank] column.
     *
     * @param  int $v new value
     * @return PRBadgeFamily The current object (for fluent API support)
     */
    public function setSortableRank($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->sortable_rank !== $v) {
            $this->sortable_rank = $v;
            $this->modifiedColumns[] = PRBadgeFamilyPeer::SORTABLE_RANK;
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

            $this->p_r_badge_type_id = ($row[$startcol + 0] !== null) ? (int) $row[$startcol + 0] : null;
            $this->id = ($row[$startcol + 1] !== null) ? (int) $row[$startcol + 1] : null;
            $this->title = ($row[$startcol + 2] !== null) ? (string) $row[$startcol + 2] : null;
            $this->description = ($row[$startcol + 3] !== null) ? (string) $row[$startcol + 3] : null;
            $this->created_at = ($row[$startcol + 4] !== null) ? (string) $row[$startcol + 4] : null;
            $this->updated_at = ($row[$startcol + 5] !== null) ? (string) $row[$startcol + 5] : null;
            $this->sortable_rank = ($row[$startcol + 6] !== null) ? (int) $row[$startcol + 6] : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }
            $this->postHydrate($row, $startcol, $rehydrate);

            return $startcol + 7; // 7 = PRBadgeFamilyPeer::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating PRBadgeFamily object", $e);
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

        if ($this->aPRBadgeType !== null && $this->p_r_badge_type_id !== $this->aPRBadgeType->getId()) {
            $this->aPRBadgeType = null;
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
            $con = Propel::getConnection(PRBadgeFamilyPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $stmt = PRBadgeFamilyPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
        $row = $stmt->fetch(PDO::FETCH_NUM);
        $stmt->closeCursor();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aPRBadgeType = null;
            $this->collPRBadges = null;

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
            $con = Propel::getConnection(PRBadgeFamilyPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        try {
            $deleteQuery = PRBadgeFamilyQuery::create()
                ->filterByPrimaryKey($this->getPrimaryKey());
            $ret = $this->preDelete($con);
            // sortable behavior

            PRBadgeFamilyPeer::shiftRank(-1, $this->getSortableRank() + 1, null, $this->getScopeValue(), $con);
            PRBadgeFamilyPeer::clearInstancePool();

            // archivable behavior
            if ($ret) {
                if ($this->archiveOnDelete) {
                    // do nothing yet. The object will be archived later when calling PRBadgeFamilyQuery::delete().
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
            $con = Propel::getConnection(PRBadgeFamilyPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        $isInsert = $this->isNew();
        try {
            $ret = $this->preSave($con);
            // sortable behavior
            $this->processSortableQueries($con);
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
                // timestampable behavior
                if (!$this->isColumnModified(PRBadgeFamilyPeer::CREATED_AT)) {
                    $this->setCreatedAt(time());
                }
                if (!$this->isColumnModified(PRBadgeFamilyPeer::UPDATED_AT)) {
                    $this->setUpdatedAt(time());
                }
                // sortable behavior
                if (!$this->isColumnModified(PRBadgeFamilyPeer::RANK_COL)) {
                    $this->setSortableRank(PRBadgeFamilyQuery::create()->getMaxRankArray($this->getScopeValue(), $con) + 1);
                }

            } else {
                $ret = $ret && $this->preUpdate($con);
                // timestampable behavior
                if ($this->isModified() && !$this->isColumnModified(PRBadgeFamilyPeer::UPDATED_AT)) {
                    $this->setUpdatedAt(time());
                }
                // sortable behavior
                // if scope has changed and rank was not modified (if yes, assuming superior action)
                // insert object to the end of new scope and cleanup old one
                if (($this->isColumnModified(PRBadgeFamilyPeer::P_R_BADGE_TYPE_ID)) && !$this->isColumnModified(PRBadgeFamilyPeer::RANK_COL)) { PRBadgeFamilyPeer::shiftRank(-1, $this->getSortableRank() + 1, null, $this->oldScope, $con);
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
                PRBadgeFamilyPeer::addInstanceToPool($this);
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

            if ($this->aPRBadgeType !== null) {
                if ($this->aPRBadgeType->isModified() || $this->aPRBadgeType->isNew()) {
                    $affectedRows += $this->aPRBadgeType->save($con);
                }
                $this->setPRBadgeType($this->aPRBadgeType);
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

            if ($this->pRBadgesScheduledForDeletion !== null) {
                if (!$this->pRBadgesScheduledForDeletion->isEmpty()) {
                    PRBadgeQuery::create()
                        ->filterByPrimaryKeys($this->pRBadgesScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->pRBadgesScheduledForDeletion = null;
                }
            }

            if ($this->collPRBadges !== null) {
                foreach ($this->collPRBadges as $referrerFK) {
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

        $this->modifiedColumns[] = PRBadgeFamilyPeer::ID;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . PRBadgeFamilyPeer::ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(PRBadgeFamilyPeer::P_R_BADGE_TYPE_ID)) {
            $modifiedColumns[':p' . $index++]  = '`p_r_badge_type_id`';
        }
        if ($this->isColumnModified(PRBadgeFamilyPeer::ID)) {
            $modifiedColumns[':p' . $index++]  = '`id`';
        }
        if ($this->isColumnModified(PRBadgeFamilyPeer::TITLE)) {
            $modifiedColumns[':p' . $index++]  = '`title`';
        }
        if ($this->isColumnModified(PRBadgeFamilyPeer::DESCRIPTION)) {
            $modifiedColumns[':p' . $index++]  = '`description`';
        }
        if ($this->isColumnModified(PRBadgeFamilyPeer::CREATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`created_at`';
        }
        if ($this->isColumnModified(PRBadgeFamilyPeer::UPDATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`updated_at`';
        }
        if ($this->isColumnModified(PRBadgeFamilyPeer::SORTABLE_RANK)) {
            $modifiedColumns[':p' . $index++]  = '`sortable_rank`';
        }

        $sql = sprintf(
            'INSERT INTO `p_r_badge_family` (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case '`p_r_badge_type_id`':
                        $stmt->bindValue($identifier, $this->p_r_badge_type_id, PDO::PARAM_INT);
                        break;
                    case '`id`':
                        $stmt->bindValue($identifier, $this->id, PDO::PARAM_INT);
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
        $pos = PRBadgeFamilyPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                return $this->getPRBadgeTypeId();
                break;
            case 1:
                return $this->getId();
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
            case 6:
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
        if (isset($alreadyDumpedObjects['PRBadgeFamily'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['PRBadgeFamily'][$this->getPrimaryKey()] = true;
        $keys = PRBadgeFamilyPeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getPRBadgeTypeId(),
            $keys[1] => $this->getId(),
            $keys[2] => $this->getTitle(),
            $keys[3] => $this->getDescription(),
            $keys[4] => $this->getCreatedAt(),
            $keys[5] => $this->getUpdatedAt(),
            $keys[6] => $this->getSortableRank(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aPRBadgeType) {
                $result['PRBadgeType'] = $this->aPRBadgeType->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->collPRBadges) {
                $result['PRBadges'] = $this->collPRBadges->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
        $pos = PRBadgeFamilyPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);

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
                $this->setPRBadgeTypeId($value);
                break;
            case 1:
                $this->setId($value);
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
            case 6:
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
        $keys = PRBadgeFamilyPeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) $this->setPRBadgeTypeId($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setId($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setTitle($arr[$keys[2]]);
        if (array_key_exists($keys[3], $arr)) $this->setDescription($arr[$keys[3]]);
        if (array_key_exists($keys[4], $arr)) $this->setCreatedAt($arr[$keys[4]]);
        if (array_key_exists($keys[5], $arr)) $this->setUpdatedAt($arr[$keys[5]]);
        if (array_key_exists($keys[6], $arr)) $this->setSortableRank($arr[$keys[6]]);
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(PRBadgeFamilyPeer::DATABASE_NAME);

        if ($this->isColumnModified(PRBadgeFamilyPeer::P_R_BADGE_TYPE_ID)) $criteria->add(PRBadgeFamilyPeer::P_R_BADGE_TYPE_ID, $this->p_r_badge_type_id);
        if ($this->isColumnModified(PRBadgeFamilyPeer::ID)) $criteria->add(PRBadgeFamilyPeer::ID, $this->id);
        if ($this->isColumnModified(PRBadgeFamilyPeer::TITLE)) $criteria->add(PRBadgeFamilyPeer::TITLE, $this->title);
        if ($this->isColumnModified(PRBadgeFamilyPeer::DESCRIPTION)) $criteria->add(PRBadgeFamilyPeer::DESCRIPTION, $this->description);
        if ($this->isColumnModified(PRBadgeFamilyPeer::CREATED_AT)) $criteria->add(PRBadgeFamilyPeer::CREATED_AT, $this->created_at);
        if ($this->isColumnModified(PRBadgeFamilyPeer::UPDATED_AT)) $criteria->add(PRBadgeFamilyPeer::UPDATED_AT, $this->updated_at);
        if ($this->isColumnModified(PRBadgeFamilyPeer::SORTABLE_RANK)) $criteria->add(PRBadgeFamilyPeer::SORTABLE_RANK, $this->sortable_rank);

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
        $criteria = new Criteria(PRBadgeFamilyPeer::DATABASE_NAME);
        $criteria->add(PRBadgeFamilyPeer::ID, $this->id);

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
     * @param object $copyObj An object of PRBadgeFamily (or compatible) type.
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setPRBadgeTypeId($this->getPRBadgeTypeId());
        $copyObj->setTitle($this->getTitle());
        $copyObj->setDescription($this->getDescription());
        $copyObj->setCreatedAt($this->getCreatedAt());
        $copyObj->setUpdatedAt($this->getUpdatedAt());
        $copyObj->setSortableRank($this->getSortableRank());

        if ($deepCopy && !$this->startCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);
            // store object hash to prevent cycle
            $this->startCopy = true;

            foreach ($this->getPRBadges() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPRBadge($relObj->copy($deepCopy));
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
     * @return PRBadgeFamily Clone of current object.
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
     * @return PRBadgeFamilyPeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new PRBadgeFamilyPeer();
        }

        return self::$peer;
    }

    /**
     * Declares an association between this object and a PRBadgeType object.
     *
     * @param                  PRBadgeType $v
     * @return PRBadgeFamily The current object (for fluent API support)
     * @throws PropelException
     */
    public function setPRBadgeType(PRBadgeType $v = null)
    {
        if ($v === null) {
            $this->setPRBadgeTypeId(NULL);
        } else {
            $this->setPRBadgeTypeId($v->getId());
        }

        $this->aPRBadgeType = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the PRBadgeType object, it will not be re-added.
        if ($v !== null) {
            $v->addPRBadgeFamily($this);
        }


        return $this;
    }


    /**
     * Get the associated PRBadgeType object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return PRBadgeType The associated PRBadgeType object.
     * @throws PropelException
     */
    public function getPRBadgeType(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aPRBadgeType === null && ($this->p_r_badge_type_id !== null) && $doQuery) {
            $this->aPRBadgeType = PRBadgeTypeQuery::create()->findPk($this->p_r_badge_type_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aPRBadgeType->addPRBadgeFamilies($this);
             */
        }

        return $this->aPRBadgeType;
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
        if ('PRBadge' == $relationName) {
            $this->initPRBadges();
        }
    }

    /**
     * Clears out the collPRBadges collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return PRBadgeFamily The current object (for fluent API support)
     * @see        addPRBadges()
     */
    public function clearPRBadges()
    {
        $this->collPRBadges = null; // important to set this to null since that means it is uninitialized
        $this->collPRBadgesPartial = null;

        return $this;
    }

    /**
     * reset is the collPRBadges collection loaded partially
     *
     * @return void
     */
    public function resetPartialPRBadges($v = true)
    {
        $this->collPRBadgesPartial = $v;
    }

    /**
     * Initializes the collPRBadges collection.
     *
     * By default this just sets the collPRBadges collection to an empty array (like clearcollPRBadges());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPRBadges($overrideExisting = true)
    {
        if (null !== $this->collPRBadges && !$overrideExisting) {
            return;
        }
        $this->collPRBadges = new PropelObjectCollection();
        $this->collPRBadges->setModel('PRBadge');
    }

    /**
     * Gets an array of PRBadge objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this PRBadgeFamily is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|PRBadge[] List of PRBadge objects
     * @throws PropelException
     */
    public function getPRBadges($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collPRBadgesPartial && !$this->isNew();
        if (null === $this->collPRBadges || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPRBadges) {
                // return empty collection
                $this->initPRBadges();
            } else {
                $collPRBadges = PRBadgeQuery::create(null, $criteria)
                    ->filterByPRBadgeFamily($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collPRBadgesPartial && count($collPRBadges)) {
                      $this->initPRBadges(false);

                      foreach ($collPRBadges as $obj) {
                        if (false == $this->collPRBadges->contains($obj)) {
                          $this->collPRBadges->append($obj);
                        }
                      }

                      $this->collPRBadgesPartial = true;
                    }

                    $collPRBadges->getInternalIterator()->rewind();

                    return $collPRBadges;
                }

                if ($partial && $this->collPRBadges) {
                    foreach ($this->collPRBadges as $obj) {
                        if ($obj->isNew()) {
                            $collPRBadges[] = $obj;
                        }
                    }
                }

                $this->collPRBadges = $collPRBadges;
                $this->collPRBadgesPartial = false;
            }
        }

        return $this->collPRBadges;
    }

    /**
     * Sets a collection of PRBadge objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $pRBadges A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return PRBadgeFamily The current object (for fluent API support)
     */
    public function setPRBadges(PropelCollection $pRBadges, PropelPDO $con = null)
    {
        $pRBadgesToDelete = $this->getPRBadges(new Criteria(), $con)->diff($pRBadges);


        $this->pRBadgesScheduledForDeletion = $pRBadgesToDelete;

        foreach ($pRBadgesToDelete as $pRBadgeRemoved) {
            $pRBadgeRemoved->setPRBadgeFamily(null);
        }

        $this->collPRBadges = null;
        foreach ($pRBadges as $pRBadge) {
            $this->addPRBadge($pRBadge);
        }

        $this->collPRBadges = $pRBadges;
        $this->collPRBadgesPartial = false;

        return $this;
    }

    /**
     * Returns the number of related PRBadge objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related PRBadge objects.
     * @throws PropelException
     */
    public function countPRBadges(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collPRBadgesPartial && !$this->isNew();
        if (null === $this->collPRBadges || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPRBadges) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPRBadges());
            }
            $query = PRBadgeQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPRBadgeFamily($this)
                ->count($con);
        }

        return count($this->collPRBadges);
    }

    /**
     * Method called to associate a PRBadge object to this object
     * through the PRBadge foreign key attribute.
     *
     * @param    PRBadge $l PRBadge
     * @return PRBadgeFamily The current object (for fluent API support)
     */
    public function addPRBadge(PRBadge $l)
    {
        if ($this->collPRBadges === null) {
            $this->initPRBadges();
            $this->collPRBadgesPartial = true;
        }

        if (!in_array($l, $this->collPRBadges->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddPRBadge($l);

            if ($this->pRBadgesScheduledForDeletion and $this->pRBadgesScheduledForDeletion->contains($l)) {
                $this->pRBadgesScheduledForDeletion->remove($this->pRBadgesScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	PRBadge $pRBadge The pRBadge object to add.
     */
    protected function doAddPRBadge($pRBadge)
    {
        $this->collPRBadges[]= $pRBadge;
        $pRBadge->setPRBadgeFamily($this);
    }

    /**
     * @param	PRBadge $pRBadge The pRBadge object to remove.
     * @return PRBadgeFamily The current object (for fluent API support)
     */
    public function removePRBadge($pRBadge)
    {
        if ($this->getPRBadges()->contains($pRBadge)) {
            $this->collPRBadges->remove($this->collPRBadges->search($pRBadge));
            if (null === $this->pRBadgesScheduledForDeletion) {
                $this->pRBadgesScheduledForDeletion = clone $this->collPRBadges;
                $this->pRBadgesScheduledForDeletion->clear();
            }
            $this->pRBadgesScheduledForDeletion[]= clone $pRBadge;
            $pRBadge->setPRBadgeFamily(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this PRBadgeFamily is new, it will return
     * an empty collection; or if this PRBadgeFamily has previously
     * been saved, it will retrieve related PRBadges from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in PRBadgeFamily.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|PRBadge[] List of PRBadge objects
     */
    public function getPRBadgesJoinPRMetalType($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PRBadgeQuery::create(null, $criteria);
        $query->joinWith('PRMetalType', $join_behavior);

        return $this->getPRBadges($query, $con);
    }

    /**
     * Clears the current object and sets all attributes to their default values
     */
    public function clear()
    {
        $this->p_r_badge_type_id = null;
        $this->id = null;
        $this->title = null;
        $this->description = null;
        $this->created_at = null;
        $this->updated_at = null;
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
            if ($this->collPRBadges) {
                foreach ($this->collPRBadges as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->aPRBadgeType instanceof Persistent) {
              $this->aPRBadgeType->clearAllReferences($deep);
            }

            $this->alreadyInClearAllReferencesDeep = false;
        } // if ($deep)

        if ($this->collPRBadges instanceof PropelCollection) {
            $this->collPRBadges->clearIterator();
        }
        $this->collPRBadges = null;
        $this->aPRBadgeType = null;
    }

    /**
     * return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(PRBadgeFamilyPeer::DEFAULT_STRING_FORMAT);
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
     * @return     PRBadgeFamily The current object (for fluent API support)
     */
    public function keepUpdateDateUnchanged()
    {
        $this->modifiedColumns[] = PRBadgeFamilyPeer::UPDATED_AT;

        return $this;
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
     * @return    PRBadgeFamily
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


        return $this->getPRBadgeTypeId();

    }

    /**
     * Wrap the setter for scope value
     *
     * @param     mixed A array or a native type
     * @return    PRBadgeFamily
     */
    public function setScopeValue($v)
    {


        return $this->setPRBadgeTypeId($v);

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
        return $this->getSortableRank() == PRBadgeFamilyQuery::create()->getMaxRankArray($this->getScopeValue(), $con);
    }

    /**
     * Get the next item in the list, i.e. the one for which rank is immediately higher
     *
     * @param     PropelPDO  $con      optional connection
     *
     * @return    PRBadgeFamily
     */
    public function getNext(PropelPDO $con = null)
    {

        $query = PRBadgeFamilyQuery::create();

        $scope = $this->getScopeValue();

        $query->filterByRank($this->getSortableRank() + 1, $scope);


        return $query->findOne($con);
    }

    /**
     * Get the previous item in the list, i.e. the one for which rank is immediately lower
     *
     * @param     PropelPDO  $con      optional connection
     *
     * @return    PRBadgeFamily
     */
    public function getPrevious(PropelPDO $con = null)
    {

        $query = PRBadgeFamilyQuery::create();

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
     * @return    PRBadgeFamily the current object
     *
     * @throws    PropelException
     */
    public function insertAtRank($rank, PropelPDO $con = null)
    {
        $maxRank = PRBadgeFamilyQuery::create()->getMaxRankArray($this->getScopeValue(), $con);
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
     * @return    PRBadgeFamily the current object
     *
     * @throws    PropelException
     */
    public function insertAtBottom(PropelPDO $con = null)
    {
        $this->setSortableRank(PRBadgeFamilyQuery::create()->getMaxRankArray($this->getScopeValue(), $con) + 1);

        return $this;
    }

    /**
     * Insert in the first rank
     * The modifications are not persisted until the object is saved.
     *
     * @return    PRBadgeFamily the current object
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
     * @return    PRBadgeFamily the current object
     *
     * @throws    PropelException
     */
    public function moveToRank($newRank, PropelPDO $con = null)
    {
        if ($this->isNew()) {
            throw new PropelException('New objects cannot be moved. Please use insertAtRank() instead');
        }
        if ($con === null) {
            $con = Propel::getConnection(PRBadgeFamilyPeer::DATABASE_NAME);
        }
        if ($newRank < 1 || $newRank > PRBadgeFamilyQuery::create()->getMaxRankArray($this->getScopeValue(), $con)) {
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
            PRBadgeFamilyPeer::shiftRank($delta, min($oldRank, $newRank), max($oldRank, $newRank), $this->getScopeValue(), $con);

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
     * @param     PRBadgeFamily $object
     * @param     PropelPDO $con optional connection
     *
     * @return    PRBadgeFamily the current object
     *
     * @throws Exception if the database cannot execute the two updates
     */
    public function swapWith($object, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(PRBadgeFamilyPeer::DATABASE_NAME);
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
     * @return    PRBadgeFamily the current object
     */
    public function moveUp(PropelPDO $con = null)
    {
        if ($this->isFirst()) {
            return $this;
        }
        if ($con === null) {
            $con = Propel::getConnection(PRBadgeFamilyPeer::DATABASE_NAME);
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
     * @return    PRBadgeFamily the current object
     */
    public function moveDown(PropelPDO $con = null)
    {
        if ($this->isLast($con)) {
            return $this;
        }
        if ($con === null) {
            $con = Propel::getConnection(PRBadgeFamilyPeer::DATABASE_NAME);
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
     * @return    PRBadgeFamily the current object
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
            $con = Propel::getConnection(PRBadgeFamilyPeer::DATABASE_NAME);
        }
        $con->beginTransaction();
        try {
            $bottom = PRBadgeFamilyQuery::create()->getMaxRankArray($this->getScopeValue(), $con);
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
     * @return    PRBadgeFamily the current object
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
     * @return     PRBadgeFamilyArchive An archive object, or null if the current object was never archived
     */
    public function getArchive(PropelPDO $con = null)
    {
        if ($this->isNew()) {
            return null;
        }
        $archive = PRBadgeFamilyArchiveQuery::create()
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
     * @return     PRBadgeFamilyArchive The archive object based on this object
     */
    public function archive(PropelPDO $con = null)
    {
        if ($this->isNew()) {
            throw new PropelException('New objects cannot be archived. You must save the current object before calling archive().');
        }
        if (!$archive = $this->getArchive($con)) {
            $archive = new PRBadgeFamilyArchive();
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
     * @return PRBadgeFamily The current object (for fluent API support)
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
     * @param      PRBadgeFamilyArchive $archive An archived object based on the same class
      * @param      Boolean $populateAutoIncrementPrimaryKeys
     *               If true, autoincrement columns are copied from the archive object.
     *               If false, autoincrement columns are left intact.
      *
     * @return     PRBadgeFamily The current object (for fluent API support)
     */
    public function populateFromArchive($archive, $populateAutoIncrementPrimaryKeys = false) {
        if ($populateAutoIncrementPrimaryKeys) {
            $this->setId($archive->getId());
        }
        $this->setPRBadgeTypeId($archive->getPRBadgeTypeId());
        $this->setTitle($archive->getTitle());
        $this->setDescription($archive->getDescription());
        $this->setCreatedAt($archive->getCreatedAt());
        $this->setUpdatedAt($archive->getUpdatedAt());
        $this->setSortableRank($archive->getSortableRank());

        return $this;
    }

    /**
     * Removes the object from the database without archiving it.
     *
     * @param PropelPDO $con Optional connection object
     *
     * @return     PRBadgeFamily The current object (for fluent API support)
     */
    public function deleteWithoutArchive(PropelPDO $con = null)
    {
        $this->archiveOnDelete = false;

        return $this->delete($con);
    }

}
