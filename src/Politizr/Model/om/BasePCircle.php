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
use Politizr\Model\PCGroupLC;
use Politizr\Model\PCGroupLCQuery;
use Politizr\Model\PCTopic;
use Politizr\Model\PCTopicQuery;
use Politizr\Model\PCircle;
use Politizr\Model\PCircleArchive;
use Politizr\Model\PCircleArchiveQuery;
use Politizr\Model\PCirclePeer;
use Politizr\Model\PCircleQuery;
use Politizr\Model\PLCity;
use Politizr\Model\PLCityQuery;

abstract class BasePCircle extends BaseObject implements Persistent
{
    /**
     * Peer class name
     */
    const PEER = 'Politizr\\Model\\PCirclePeer';

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        PCirclePeer
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
     * The value for the summary field.
     * @var        string
     */
    protected $summary;

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
     * The value for the only_elected field.
     * @var        boolean
     */
    protected $only_elected;

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
     * @var        PropelObjectCollection|PCTopic[] Collection to store aggregation of PCTopic objects.
     */
    protected $collPCTopics;
    protected $collPCTopicsPartial;

    /**
     * @var        PropelObjectCollection|PCGroupLC[] Collection to store aggregation of PCGroupLC objects.
     */
    protected $collPCGroupLCs;
    protected $collPCGroupLCsPartial;

    /**
     * @var        PropelObjectCollection|PLCity[] Collection to store aggregation of PLCity objects.
     */
    protected $collPLCities;

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
    protected $pCTopicsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $pCGroupLCsScheduledForDeletion = null;

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
     * Get the [summary] column value.
     *
     * @return string
     */
    public function getSummary()
    {

        return $this->summary;
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
     * Get the [only_elected] column value.
     *
     * @return boolean
     */
    public function getOnlyElected()
    {

        return $this->only_elected;
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
     * @return PCircle The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[] = PCirclePeer::ID;
        }


        return $this;
    } // setId()

    /**
     * Set the value of [uuid] column.
     *
     * @param  string $v new value
     * @return PCircle The current object (for fluent API support)
     */
    public function setUuid($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->uuid !== $v) {
            $this->uuid = $v;
            $this->modifiedColumns[] = PCirclePeer::UUID;
        }


        return $this;
    } // setUuid()

    /**
     * Set the value of [title] column.
     *
     * @param  string $v new value
     * @return PCircle The current object (for fluent API support)
     */
    public function setTitle($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->title !== $v) {
            $this->title = $v;
            $this->modifiedColumns[] = PCirclePeer::TITLE;
        }


        return $this;
    } // setTitle()

    /**
     * Set the value of [summary] column.
     *
     * @param  string $v new value
     * @return PCircle The current object (for fluent API support)
     */
    public function setSummary($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->summary !== $v) {
            $this->summary = $v;
            $this->modifiedColumns[] = PCirclePeer::SUMMARY;
        }


        return $this;
    } // setSummary()

    /**
     * Set the value of [description] column.
     *
     * @param  string $v new value
     * @return PCircle The current object (for fluent API support)
     */
    public function setDescription($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->description !== $v) {
            $this->description = $v;
            $this->modifiedColumns[] = PCirclePeer::DESCRIPTION;
        }


        return $this;
    } // setDescription()

    /**
     * Set the value of [url] column.
     *
     * @param  string $v new value
     * @return PCircle The current object (for fluent API support)
     */
    public function setUrl($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->url !== $v) {
            $this->url = $v;
            $this->modifiedColumns[] = PCirclePeer::URL;
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
     * @return PCircle The current object (for fluent API support)
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
            $this->modifiedColumns[] = PCirclePeer::ONLINE;
        }


        return $this;
    } // setOnline()

    /**
     * Sets the value of the [only_elected] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param boolean|integer|string $v The new value
     * @return PCircle The current object (for fluent API support)
     */
    public function setOnlyElected($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->only_elected !== $v) {
            $this->only_elected = $v;
            $this->modifiedColumns[] = PCirclePeer::ONLY_ELECTED;
        }


        return $this;
    } // setOnlyElected()

    /**
     * Sets the value of [created_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return PCircle The current object (for fluent API support)
     */
    public function setCreatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->created_at !== null || $dt !== null) {
            $currentDateAsString = ($this->created_at !== null && $tmpDt = new DateTime($this->created_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->created_at = $newDateAsString;
                $this->modifiedColumns[] = PCirclePeer::CREATED_AT;
            }
        } // if either are not null


        return $this;
    } // setCreatedAt()

    /**
     * Sets the value of [updated_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return PCircle The current object (for fluent API support)
     */
    public function setUpdatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->updated_at !== null || $dt !== null) {
            $currentDateAsString = ($this->updated_at !== null && $tmpDt = new DateTime($this->updated_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->updated_at = $newDateAsString;
                $this->modifiedColumns[] = PCirclePeer::UPDATED_AT;
            }
        } // if either are not null


        return $this;
    } // setUpdatedAt()

    /**
     * Set the value of [slug] column.
     *
     * @param  string $v new value
     * @return PCircle The current object (for fluent API support)
     */
    public function setSlug($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->slug !== $v) {
            $this->slug = $v;
            $this->modifiedColumns[] = PCirclePeer::SLUG;
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
            $this->title = ($row[$startcol + 2] !== null) ? (string) $row[$startcol + 2] : null;
            $this->summary = ($row[$startcol + 3] !== null) ? (string) $row[$startcol + 3] : null;
            $this->description = ($row[$startcol + 4] !== null) ? (string) $row[$startcol + 4] : null;
            $this->url = ($row[$startcol + 5] !== null) ? (string) $row[$startcol + 5] : null;
            $this->online = ($row[$startcol + 6] !== null) ? (boolean) $row[$startcol + 6] : null;
            $this->only_elected = ($row[$startcol + 7] !== null) ? (boolean) $row[$startcol + 7] : null;
            $this->created_at = ($row[$startcol + 8] !== null) ? (string) $row[$startcol + 8] : null;
            $this->updated_at = ($row[$startcol + 9] !== null) ? (string) $row[$startcol + 9] : null;
            $this->slug = ($row[$startcol + 10] !== null) ? (string) $row[$startcol + 10] : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }
            $this->postHydrate($row, $startcol, $rehydrate);

            return $startcol + 11; // 11 = PCirclePeer::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating PCircle object", $e);
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
            $con = Propel::getConnection(PCirclePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $stmt = PCirclePeer::doSelectStmt($this->buildPkeyCriteria(), $con);
        $row = $stmt->fetch(PDO::FETCH_NUM);
        $stmt->closeCursor();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->collPCTopics = null;

            $this->collPCGroupLCs = null;

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
            $con = Propel::getConnection(PCirclePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        try {
            $deleteQuery = PCircleQuery::create()
                ->filterByPrimaryKey($this->getPrimaryKey());
            $ret = $this->preDelete($con);
            // archivable behavior
            if ($ret) {
                if ($this->archiveOnDelete) {
                    // do nothing yet. The object will be archived later when calling PCircleQuery::delete().
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
            $con = Propel::getConnection(PCirclePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        $isInsert = $this->isNew();
        try {
            $ret = $this->preSave($con);
            // sluggable behavior

            if ($this->isColumnModified(PCirclePeer::SLUG) && $this->getSlug()) {
                $this->setSlug($this->makeSlugUnique($this->getSlug()));
            } elseif ($this->isColumnModified(PCirclePeer::TITLE)) {
                $this->setSlug($this->createSlug());
            } elseif (!$this->getSlug()) {
                $this->setSlug($this->createSlug());
            }
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
                // timestampable behavior
                if (!$this->isColumnModified(PCirclePeer::CREATED_AT)) {
                    $this->setCreatedAt(time());
                }
                if (!$this->isColumnModified(PCirclePeer::UPDATED_AT)) {
                    $this->setUpdatedAt(time());
                }
            } else {
                $ret = $ret && $this->preUpdate($con);
                // timestampable behavior
                if ($this->isModified() && !$this->isColumnModified(PCirclePeer::UPDATED_AT)) {
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
                PCirclePeer::addInstanceToPool($this);
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

            if ($this->pLCitiesScheduledForDeletion !== null) {
                if (!$this->pLCitiesScheduledForDeletion->isEmpty()) {
                    $pks = array();
                    $pk = $this->getPrimaryKey();
                    foreach ($this->pLCitiesScheduledForDeletion->getPrimaryKeys(false) as $remotePk) {
                        $pks[] = array($pk, $remotePk);
                    }
                    PCGroupLCQuery::create()
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

            if ($this->pCTopicsScheduledForDeletion !== null) {
                if (!$this->pCTopicsScheduledForDeletion->isEmpty()) {
                    PCTopicQuery::create()
                        ->filterByPrimaryKeys($this->pCTopicsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->pCTopicsScheduledForDeletion = null;
                }
            }

            if ($this->collPCTopics !== null) {
                foreach ($this->collPCTopics as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->pCGroupLCsScheduledForDeletion !== null) {
                if (!$this->pCGroupLCsScheduledForDeletion->isEmpty()) {
                    PCGroupLCQuery::create()
                        ->filterByPrimaryKeys($this->pCGroupLCsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->pCGroupLCsScheduledForDeletion = null;
                }
            }

            if ($this->collPCGroupLCs !== null) {
                foreach ($this->collPCGroupLCs as $referrerFK) {
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

        $this->modifiedColumns[] = PCirclePeer::ID;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . PCirclePeer::ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(PCirclePeer::ID)) {
            $modifiedColumns[':p' . $index++]  = '`id`';
        }
        if ($this->isColumnModified(PCirclePeer::UUID)) {
            $modifiedColumns[':p' . $index++]  = '`uuid`';
        }
        if ($this->isColumnModified(PCirclePeer::TITLE)) {
            $modifiedColumns[':p' . $index++]  = '`title`';
        }
        if ($this->isColumnModified(PCirclePeer::SUMMARY)) {
            $modifiedColumns[':p' . $index++]  = '`summary`';
        }
        if ($this->isColumnModified(PCirclePeer::DESCRIPTION)) {
            $modifiedColumns[':p' . $index++]  = '`description`';
        }
        if ($this->isColumnModified(PCirclePeer::URL)) {
            $modifiedColumns[':p' . $index++]  = '`url`';
        }
        if ($this->isColumnModified(PCirclePeer::ONLINE)) {
            $modifiedColumns[':p' . $index++]  = '`online`';
        }
        if ($this->isColumnModified(PCirclePeer::ONLY_ELECTED)) {
            $modifiedColumns[':p' . $index++]  = '`only_elected`';
        }
        if ($this->isColumnModified(PCirclePeer::CREATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`created_at`';
        }
        if ($this->isColumnModified(PCirclePeer::UPDATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`updated_at`';
        }
        if ($this->isColumnModified(PCirclePeer::SLUG)) {
            $modifiedColumns[':p' . $index++]  = '`slug`';
        }

        $sql = sprintf(
            'INSERT INTO `p_circle` (%s) VALUES (%s)',
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
                    case '`summary`':
                        $stmt->bindValue($identifier, $this->summary, PDO::PARAM_STR);
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
                    case '`only_elected`':
                        $stmt->bindValue($identifier, (int) $this->only_elected, PDO::PARAM_INT);
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
        $pos = PCirclePeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                return $this->getSummary();
                break;
            case 4:
                return $this->getDescription();
                break;
            case 5:
                return $this->getUrl();
                break;
            case 6:
                return $this->getOnline();
                break;
            case 7:
                return $this->getOnlyElected();
                break;
            case 8:
                return $this->getCreatedAt();
                break;
            case 9:
                return $this->getUpdatedAt();
                break;
            case 10:
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
        if (isset($alreadyDumpedObjects['PCircle'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['PCircle'][$this->getPrimaryKey()] = true;
        $keys = PCirclePeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getUuid(),
            $keys[2] => $this->getTitle(),
            $keys[3] => $this->getSummary(),
            $keys[4] => $this->getDescription(),
            $keys[5] => $this->getUrl(),
            $keys[6] => $this->getOnline(),
            $keys[7] => $this->getOnlyElected(),
            $keys[8] => $this->getCreatedAt(),
            $keys[9] => $this->getUpdatedAt(),
            $keys[10] => $this->getSlug(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->collPCTopics) {
                $result['PCTopics'] = $this->collPCTopics->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPCGroupLCs) {
                $result['PCGroupLCs'] = $this->collPCGroupLCs->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
        $pos = PCirclePeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);

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
                $this->setSummary($value);
                break;
            case 4:
                $this->setDescription($value);
                break;
            case 5:
                $this->setUrl($value);
                break;
            case 6:
                $this->setOnline($value);
                break;
            case 7:
                $this->setOnlyElected($value);
                break;
            case 8:
                $this->setCreatedAt($value);
                break;
            case 9:
                $this->setUpdatedAt($value);
                break;
            case 10:
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
        $keys = PCirclePeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setUuid($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setTitle($arr[$keys[2]]);
        if (array_key_exists($keys[3], $arr)) $this->setSummary($arr[$keys[3]]);
        if (array_key_exists($keys[4], $arr)) $this->setDescription($arr[$keys[4]]);
        if (array_key_exists($keys[5], $arr)) $this->setUrl($arr[$keys[5]]);
        if (array_key_exists($keys[6], $arr)) $this->setOnline($arr[$keys[6]]);
        if (array_key_exists($keys[7], $arr)) $this->setOnlyElected($arr[$keys[7]]);
        if (array_key_exists($keys[8], $arr)) $this->setCreatedAt($arr[$keys[8]]);
        if (array_key_exists($keys[9], $arr)) $this->setUpdatedAt($arr[$keys[9]]);
        if (array_key_exists($keys[10], $arr)) $this->setSlug($arr[$keys[10]]);
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(PCirclePeer::DATABASE_NAME);

        if ($this->isColumnModified(PCirclePeer::ID)) $criteria->add(PCirclePeer::ID, $this->id);
        if ($this->isColumnModified(PCirclePeer::UUID)) $criteria->add(PCirclePeer::UUID, $this->uuid);
        if ($this->isColumnModified(PCirclePeer::TITLE)) $criteria->add(PCirclePeer::TITLE, $this->title);
        if ($this->isColumnModified(PCirclePeer::SUMMARY)) $criteria->add(PCirclePeer::SUMMARY, $this->summary);
        if ($this->isColumnModified(PCirclePeer::DESCRIPTION)) $criteria->add(PCirclePeer::DESCRIPTION, $this->description);
        if ($this->isColumnModified(PCirclePeer::URL)) $criteria->add(PCirclePeer::URL, $this->url);
        if ($this->isColumnModified(PCirclePeer::ONLINE)) $criteria->add(PCirclePeer::ONLINE, $this->online);
        if ($this->isColumnModified(PCirclePeer::ONLY_ELECTED)) $criteria->add(PCirclePeer::ONLY_ELECTED, $this->only_elected);
        if ($this->isColumnModified(PCirclePeer::CREATED_AT)) $criteria->add(PCirclePeer::CREATED_AT, $this->created_at);
        if ($this->isColumnModified(PCirclePeer::UPDATED_AT)) $criteria->add(PCirclePeer::UPDATED_AT, $this->updated_at);
        if ($this->isColumnModified(PCirclePeer::SLUG)) $criteria->add(PCirclePeer::SLUG, $this->slug);

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
        $criteria = new Criteria(PCirclePeer::DATABASE_NAME);
        $criteria->add(PCirclePeer::ID, $this->id);

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
     * @param object $copyObj An object of PCircle (or compatible) type.
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setUuid($this->getUuid());
        $copyObj->setTitle($this->getTitle());
        $copyObj->setSummary($this->getSummary());
        $copyObj->setDescription($this->getDescription());
        $copyObj->setUrl($this->getUrl());
        $copyObj->setOnline($this->getOnline());
        $copyObj->setOnlyElected($this->getOnlyElected());
        $copyObj->setCreatedAt($this->getCreatedAt());
        $copyObj->setUpdatedAt($this->getUpdatedAt());
        $copyObj->setSlug($this->getSlug());

        if ($deepCopy && !$this->startCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);
            // store object hash to prevent cycle
            $this->startCopy = true;

            foreach ($this->getPCTopics() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPCTopic($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPCGroupLCs() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPCGroupLC($relObj->copy($deepCopy));
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
     * @return PCircle Clone of current object.
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
     * @return PCirclePeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new PCirclePeer();
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
        if ('PCTopic' == $relationName) {
            $this->initPCTopics();
        }
        if ('PCGroupLC' == $relationName) {
            $this->initPCGroupLCs();
        }
    }

    /**
     * Clears out the collPCTopics collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return PCircle The current object (for fluent API support)
     * @see        addPCTopics()
     */
    public function clearPCTopics()
    {
        $this->collPCTopics = null; // important to set this to null since that means it is uninitialized
        $this->collPCTopicsPartial = null;

        return $this;
    }

    /**
     * reset is the collPCTopics collection loaded partially
     *
     * @return void
     */
    public function resetPartialPCTopics($v = true)
    {
        $this->collPCTopicsPartial = $v;
    }

    /**
     * Initializes the collPCTopics collection.
     *
     * By default this just sets the collPCTopics collection to an empty array (like clearcollPCTopics());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPCTopics($overrideExisting = true)
    {
        if (null !== $this->collPCTopics && !$overrideExisting) {
            return;
        }
        $this->collPCTopics = new PropelObjectCollection();
        $this->collPCTopics->setModel('PCTopic');
    }

    /**
     * Gets an array of PCTopic objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this PCircle is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|PCTopic[] List of PCTopic objects
     * @throws PropelException
     */
    public function getPCTopics($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collPCTopicsPartial && !$this->isNew();
        if (null === $this->collPCTopics || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPCTopics) {
                // return empty collection
                $this->initPCTopics();
            } else {
                $collPCTopics = PCTopicQuery::create(null, $criteria)
                    ->filterByPCircle($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collPCTopicsPartial && count($collPCTopics)) {
                      $this->initPCTopics(false);

                      foreach ($collPCTopics as $obj) {
                        if (false == $this->collPCTopics->contains($obj)) {
                          $this->collPCTopics->append($obj);
                        }
                      }

                      $this->collPCTopicsPartial = true;
                    }

                    $collPCTopics->getInternalIterator()->rewind();

                    return $collPCTopics;
                }

                if ($partial && $this->collPCTopics) {
                    foreach ($this->collPCTopics as $obj) {
                        if ($obj->isNew()) {
                            $collPCTopics[] = $obj;
                        }
                    }
                }

                $this->collPCTopics = $collPCTopics;
                $this->collPCTopicsPartial = false;
            }
        }

        return $this->collPCTopics;
    }

    /**
     * Sets a collection of PCTopic objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $pCTopics A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return PCircle The current object (for fluent API support)
     */
    public function setPCTopics(PropelCollection $pCTopics, PropelPDO $con = null)
    {
        $pCTopicsToDelete = $this->getPCTopics(new Criteria(), $con)->diff($pCTopics);


        $this->pCTopicsScheduledForDeletion = $pCTopicsToDelete;

        foreach ($pCTopicsToDelete as $pCTopicRemoved) {
            $pCTopicRemoved->setPCircle(null);
        }

        $this->collPCTopics = null;
        foreach ($pCTopics as $pCTopic) {
            $this->addPCTopic($pCTopic);
        }

        $this->collPCTopics = $pCTopics;
        $this->collPCTopicsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related PCTopic objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related PCTopic objects.
     * @throws PropelException
     */
    public function countPCTopics(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collPCTopicsPartial && !$this->isNew();
        if (null === $this->collPCTopics || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPCTopics) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPCTopics());
            }
            $query = PCTopicQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPCircle($this)
                ->count($con);
        }

        return count($this->collPCTopics);
    }

    /**
     * Method called to associate a PCTopic object to this object
     * through the PCTopic foreign key attribute.
     *
     * @param    PCTopic $l PCTopic
     * @return PCircle The current object (for fluent API support)
     */
    public function addPCTopic(PCTopic $l)
    {
        if ($this->collPCTopics === null) {
            $this->initPCTopics();
            $this->collPCTopicsPartial = true;
        }

        if (!in_array($l, $this->collPCTopics->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddPCTopic($l);

            if ($this->pCTopicsScheduledForDeletion and $this->pCTopicsScheduledForDeletion->contains($l)) {
                $this->pCTopicsScheduledForDeletion->remove($this->pCTopicsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	PCTopic $pCTopic The pCTopic object to add.
     */
    protected function doAddPCTopic($pCTopic)
    {
        $this->collPCTopics[]= $pCTopic;
        $pCTopic->setPCircle($this);
    }

    /**
     * @param	PCTopic $pCTopic The pCTopic object to remove.
     * @return PCircle The current object (for fluent API support)
     */
    public function removePCTopic($pCTopic)
    {
        if ($this->getPCTopics()->contains($pCTopic)) {
            $this->collPCTopics->remove($this->collPCTopics->search($pCTopic));
            if (null === $this->pCTopicsScheduledForDeletion) {
                $this->pCTopicsScheduledForDeletion = clone $this->collPCTopics;
                $this->pCTopicsScheduledForDeletion->clear();
            }
            $this->pCTopicsScheduledForDeletion[]= clone $pCTopic;
            $pCTopic->setPCircle(null);
        }

        return $this;
    }

    /**
     * Clears out the collPCGroupLCs collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return PCircle The current object (for fluent API support)
     * @see        addPCGroupLCs()
     */
    public function clearPCGroupLCs()
    {
        $this->collPCGroupLCs = null; // important to set this to null since that means it is uninitialized
        $this->collPCGroupLCsPartial = null;

        return $this;
    }

    /**
     * reset is the collPCGroupLCs collection loaded partially
     *
     * @return void
     */
    public function resetPartialPCGroupLCs($v = true)
    {
        $this->collPCGroupLCsPartial = $v;
    }

    /**
     * Initializes the collPCGroupLCs collection.
     *
     * By default this just sets the collPCGroupLCs collection to an empty array (like clearcollPCGroupLCs());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPCGroupLCs($overrideExisting = true)
    {
        if (null !== $this->collPCGroupLCs && !$overrideExisting) {
            return;
        }
        $this->collPCGroupLCs = new PropelObjectCollection();
        $this->collPCGroupLCs->setModel('PCGroupLC');
    }

    /**
     * Gets an array of PCGroupLC objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this PCircle is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|PCGroupLC[] List of PCGroupLC objects
     * @throws PropelException
     */
    public function getPCGroupLCs($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collPCGroupLCsPartial && !$this->isNew();
        if (null === $this->collPCGroupLCs || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPCGroupLCs) {
                // return empty collection
                $this->initPCGroupLCs();
            } else {
                $collPCGroupLCs = PCGroupLCQuery::create(null, $criteria)
                    ->filterByPCircle($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collPCGroupLCsPartial && count($collPCGroupLCs)) {
                      $this->initPCGroupLCs(false);

                      foreach ($collPCGroupLCs as $obj) {
                        if (false == $this->collPCGroupLCs->contains($obj)) {
                          $this->collPCGroupLCs->append($obj);
                        }
                      }

                      $this->collPCGroupLCsPartial = true;
                    }

                    $collPCGroupLCs->getInternalIterator()->rewind();

                    return $collPCGroupLCs;
                }

                if ($partial && $this->collPCGroupLCs) {
                    foreach ($this->collPCGroupLCs as $obj) {
                        if ($obj->isNew()) {
                            $collPCGroupLCs[] = $obj;
                        }
                    }
                }

                $this->collPCGroupLCs = $collPCGroupLCs;
                $this->collPCGroupLCsPartial = false;
            }
        }

        return $this->collPCGroupLCs;
    }

    /**
     * Sets a collection of PCGroupLC objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $pCGroupLCs A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return PCircle The current object (for fluent API support)
     */
    public function setPCGroupLCs(PropelCollection $pCGroupLCs, PropelPDO $con = null)
    {
        $pCGroupLCsToDelete = $this->getPCGroupLCs(new Criteria(), $con)->diff($pCGroupLCs);


        $this->pCGroupLCsScheduledForDeletion = $pCGroupLCsToDelete;

        foreach ($pCGroupLCsToDelete as $pCGroupLCRemoved) {
            $pCGroupLCRemoved->setPCircle(null);
        }

        $this->collPCGroupLCs = null;
        foreach ($pCGroupLCs as $pCGroupLC) {
            $this->addPCGroupLC($pCGroupLC);
        }

        $this->collPCGroupLCs = $pCGroupLCs;
        $this->collPCGroupLCsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related PCGroupLC objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related PCGroupLC objects.
     * @throws PropelException
     */
    public function countPCGroupLCs(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collPCGroupLCsPartial && !$this->isNew();
        if (null === $this->collPCGroupLCs || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPCGroupLCs) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPCGroupLCs());
            }
            $query = PCGroupLCQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPCircle($this)
                ->count($con);
        }

        return count($this->collPCGroupLCs);
    }

    /**
     * Method called to associate a PCGroupLC object to this object
     * through the PCGroupLC foreign key attribute.
     *
     * @param    PCGroupLC $l PCGroupLC
     * @return PCircle The current object (for fluent API support)
     */
    public function addPCGroupLC(PCGroupLC $l)
    {
        if ($this->collPCGroupLCs === null) {
            $this->initPCGroupLCs();
            $this->collPCGroupLCsPartial = true;
        }

        if (!in_array($l, $this->collPCGroupLCs->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddPCGroupLC($l);

            if ($this->pCGroupLCsScheduledForDeletion and $this->pCGroupLCsScheduledForDeletion->contains($l)) {
                $this->pCGroupLCsScheduledForDeletion->remove($this->pCGroupLCsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	PCGroupLC $pCGroupLC The pCGroupLC object to add.
     */
    protected function doAddPCGroupLC($pCGroupLC)
    {
        $this->collPCGroupLCs[]= $pCGroupLC;
        $pCGroupLC->setPCircle($this);
    }

    /**
     * @param	PCGroupLC $pCGroupLC The pCGroupLC object to remove.
     * @return PCircle The current object (for fluent API support)
     */
    public function removePCGroupLC($pCGroupLC)
    {
        if ($this->getPCGroupLCs()->contains($pCGroupLC)) {
            $this->collPCGroupLCs->remove($this->collPCGroupLCs->search($pCGroupLC));
            if (null === $this->pCGroupLCsScheduledForDeletion) {
                $this->pCGroupLCsScheduledForDeletion = clone $this->collPCGroupLCs;
                $this->pCGroupLCsScheduledForDeletion->clear();
            }
            $this->pCGroupLCsScheduledForDeletion[]= clone $pCGroupLC;
            $pCGroupLC->setPCircle(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this PCircle is new, it will return
     * an empty collection; or if this PCircle has previously
     * been saved, it will retrieve related PCGroupLCs from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in PCircle.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|PCGroupLC[] List of PCGroupLC objects
     */
    public function getPCGroupLCsJoinPLCity($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PCGroupLCQuery::create(null, $criteria);
        $query->joinWith('PLCity', $join_behavior);

        return $this->getPCGroupLCs($query, $con);
    }

    /**
     * Clears out the collPLCities collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return PCircle The current object (for fluent API support)
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
     * to the current object by way of the p_c_group_l_c cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this PCircle is new, it will return
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
                    ->filterByPCircle($this)
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
     * to the current object by way of the p_c_group_l_c cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $pLCities A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return PCircle The current object (for fluent API support)
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
     * to the current object by way of the p_c_group_l_c cross-reference table.
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
                    ->filterByPCircle($this)
                    ->count($con);
            }
        } else {
            return count($this->collPLCities);
        }
    }

    /**
     * Associate a PLCity object to this object
     * through the p_c_group_l_c cross reference table.
     *
     * @param  PLCity $pLCity The PCGroupLC object to relate
     * @return PCircle The current object (for fluent API support)
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
        if (!$pLCity->getPCircles()->contains($this)) { $pCGroupLC = new PCGroupLC();
            $pCGroupLC->setPLCity($pLCity);
            $this->addPCGroupLC($pCGroupLC);

            $foreignCollection = $pLCity->getPCircles();
            $foreignCollection[] = $this;
        }
    }

    /**
     * Remove a PLCity object to this object
     * through the p_c_group_l_c cross reference table.
     *
     * @param PLCity $pLCity The PCGroupLC object to relate
     * @return PCircle The current object (for fluent API support)
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
     * Clears the current object and sets all attributes to their default values
     */
    public function clear()
    {
        $this->id = null;
        $this->uuid = null;
        $this->title = null;
        $this->summary = null;
        $this->description = null;
        $this->url = null;
        $this->online = null;
        $this->only_elected = null;
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
            if ($this->collPCTopics) {
                foreach ($this->collPCTopics as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPCGroupLCs) {
                foreach ($this->collPCGroupLCs as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPLCities) {
                foreach ($this->collPLCities as $o) {
                    $o->clearAllReferences($deep);
                }
            }

            $this->alreadyInClearAllReferencesDeep = false;
        } // if ($deep)

        if ($this->collPCTopics instanceof PropelCollection) {
            $this->collPCTopics->clearIterator();
        }
        $this->collPCTopics = null;
        if ($this->collPCGroupLCs instanceof PropelCollection) {
            $this->collPCGroupLCs->clearIterator();
        }
        $this->collPCGroupLCs = null;
        if ($this->collPLCities instanceof PropelCollection) {
            $this->collPLCities->clearIterator();
        }
        $this->collPLCities = null;
    }

    /**
     * return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(PCirclePeer::DEFAULT_STRING_FORMAT);
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
     * @return     PCircle The current object (for fluent API support)
     */
    public function keepUpdateDateUnchanged()
    {
        $this->modifiedColumns[] = PCirclePeer::UPDATED_AT;

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

         $query = PCircleQuery::create('q')
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
    * If permanent UUID, throw exception p_circle.uuid*/
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
     * @return     PCircleArchive An archive object, or null if the current object was never archived
     */
    public function getArchive(PropelPDO $con = null)
    {
        if ($this->isNew()) {
            return null;
        }
        $archive = PCircleArchiveQuery::create()
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
     * @return     PCircleArchive The archive object based on this object
     */
    public function archive(PropelPDO $con = null)
    {
        if ($this->isNew()) {
            throw new PropelException('New objects cannot be archived. You must save the current object before calling archive().');
        }
        if (!$archive = $this->getArchive($con)) {
            $archive = new PCircleArchive();
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
     * @return PCircle The current object (for fluent API support)
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
     * @param      PCircleArchive $archive An archived object based on the same class
      * @param      Boolean $populateAutoIncrementPrimaryKeys
     *               If true, autoincrement columns are copied from the archive object.
     *               If false, autoincrement columns are left intact.
      *
     * @return     PCircle The current object (for fluent API support)
     */
    public function populateFromArchive($archive, $populateAutoIncrementPrimaryKeys = false) {
        if ($populateAutoIncrementPrimaryKeys) {
            $this->setId($archive->getId());
        }
        $this->setUuid($archive->getUuid());
        $this->setTitle($archive->getTitle());
        $this->setSummary($archive->getSummary());
        $this->setDescription($archive->getDescription());
        $this->setUrl($archive->getUrl());
        $this->setOnline($archive->getOnline());
        $this->setOnlyElected($archive->getOnlyElected());
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
     * @return     PCircle The current object (for fluent API support)
     */
    public function deleteWithoutArchive(PropelPDO $con = null)
    {
        $this->archiveOnDelete = false;

        return $this->delete($con);
    }

}
