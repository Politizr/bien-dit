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
use \PropelQuery;
use Politizr\Model\PDComment;
use Politizr\Model\PDCommentQuery;
use Politizr\Model\PDDebate;
use Politizr\Model\PDDebateQuery;
use Politizr\Model\PDReaction;
use Politizr\Model\PDReactionQuery;
use Politizr\Model\PDocument;
use Politizr\Model\PDocumentArchive;
use Politizr\Model\PDocumentArchiveQuery;
use Politizr\Model\PDocumentPeer;
use Politizr\Model\PDocumentQuery;
use Politizr\Model\PUser;
use Politizr\Model\PUserQuery;

abstract class BasePDocument extends BaseObject implements Persistent
{
    /**
     * Peer class name
     */
    const PEER = 'Politizr\\Model\\PDocumentPeer';

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        PDocumentPeer
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
     * The value for the more_info field.
     * @var        string
     */
    protected $more_info;

    /**
     * The value for the note_pos field.
     * @var        int
     */
    protected $note_pos;

    /**
     * The value for the note_neg field.
     * @var        int
     */
    protected $note_neg;

    /**
     * The value for the published field.
     * @var        boolean
     */
    protected $published;

    /**
     * The value for the published_at field.
     * @var        string
     */
    protected $published_at;

    /**
     * The value for the published_by field.
     * @var        string
     */
    protected $published_by;

    /**
     * The value for the online field.
     * @var        boolean
     */
    protected $online;

    /**
     * The value for the descendant_class field.
     * @var        string
     */
    protected $descendant_class;

    /**
     * @var        PUser
     */
    protected $aPUser;

    /**
     * @var        PropelObjectCollection|PDComment[] Collection to store aggregation of PDComment objects.
     */
    protected $collPDComments;
    protected $collPDCommentsPartial;

    /**
     * @var        PDDebate one-to-one related PDDebate object
     */
    protected $singlePDDebate;

    /**
     * @var        PDReaction one-to-one related PDReaction object
     */
    protected $singlePDReaction;

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
    protected $pDCommentsScheduledForDeletion = null;

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
     * Get the [id] column value.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
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
     * Get the [more_info] column value.
     *
     * @return string
     */
    public function getMoreInfo()
    {
        return $this->more_info;
    }

    /**
     * Get the [note_pos] column value.
     *
     * @return int
     */
    public function getNotePos()
    {
        return $this->note_pos;
    }

    /**
     * Get the [note_neg] column value.
     *
     * @return int
     */
    public function getNoteNeg()
    {
        return $this->note_neg;
    }

    /**
     * Get the [published] column value.
     *
     * @return boolean
     */
    public function getPublished()
    {
        return $this->published;
    }

    /**
     * Get the [optionally formatted] temporal [published_at] column value.
     *
     *
     * @param string $format The date/time format string (either date()-style or strftime()-style).
     *				 If format is null, then the raw DateTime object will be returned.
     * @return mixed Formatted date/time value as string or DateTime object (if format is null), null if column is null, and 0 if column value is 0000-00-00 00:00:00
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getPublishedAt($format = null)
    {
        if ($this->published_at === null) {
            return null;
        }

        if ($this->published_at === '0000-00-00 00:00:00') {
            // while technically this is not a default value of null,
            // this seems to be closest in meaning.
            return null;
        }

        try {
            $dt = new DateTime($this->published_at);
        } catch (Exception $x) {
            throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->published_at, true), $x);
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
     * Get the [published_by] column value.
     *
     * @return string
     */
    public function getPublishedBy()
    {
        return $this->published_by;
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
     * Get the [descendant_class] column value.
     *
     * @return string
     */
    public function getDescendantClass()
    {
        return $this->descendant_class;
    }

    /**
     * Set the value of [id] column.
     *
     * @param int $v new value
     * @return PDocument The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[] = PDocumentPeer::ID;
        }


        return $this;
    } // setId()

    /**
     * Set the value of [p_user_id] column.
     *
     * @param int $v new value
     * @return PDocument The current object (for fluent API support)
     */
    public function setPUserId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->p_user_id !== $v) {
            $this->p_user_id = $v;
            $this->modifiedColumns[] = PDocumentPeer::P_USER_ID;
        }

        if ($this->aPUser !== null && $this->aPUser->getId() !== $v) {
            $this->aPUser = null;
        }


        return $this;
    } // setPUserId()

    /**
     * Set the value of [title] column.
     *
     * @param string $v new value
     * @return PDocument The current object (for fluent API support)
     */
    public function setTitle($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (string) $v;
        }

        if ($this->title !== $v) {
            $this->title = $v;
            $this->modifiedColumns[] = PDocumentPeer::TITLE;
        }


        return $this;
    } // setTitle()

    /**
     * Set the value of [summary] column.
     *
     * @param string $v new value
     * @return PDocument The current object (for fluent API support)
     */
    public function setSummary($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (string) $v;
        }

        if ($this->summary !== $v) {
            $this->summary = $v;
            $this->modifiedColumns[] = PDocumentPeer::SUMMARY;
        }


        return $this;
    } // setSummary()

    /**
     * Set the value of [description] column.
     *
     * @param string $v new value
     * @return PDocument The current object (for fluent API support)
     */
    public function setDescription($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (string) $v;
        }

        if ($this->description !== $v) {
            $this->description = $v;
            $this->modifiedColumns[] = PDocumentPeer::DESCRIPTION;
        }


        return $this;
    } // setDescription()

    /**
     * Set the value of [more_info] column.
     *
     * @param string $v new value
     * @return PDocument The current object (for fluent API support)
     */
    public function setMoreInfo($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (string) $v;
        }

        if ($this->more_info !== $v) {
            $this->more_info = $v;
            $this->modifiedColumns[] = PDocumentPeer::MORE_INFO;
        }


        return $this;
    } // setMoreInfo()

    /**
     * Set the value of [note_pos] column.
     *
     * @param int $v new value
     * @return PDocument The current object (for fluent API support)
     */
    public function setNotePos($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->note_pos !== $v) {
            $this->note_pos = $v;
            $this->modifiedColumns[] = PDocumentPeer::NOTE_POS;
        }


        return $this;
    } // setNotePos()

    /**
     * Set the value of [note_neg] column.
     *
     * @param int $v new value
     * @return PDocument The current object (for fluent API support)
     */
    public function setNoteNeg($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->note_neg !== $v) {
            $this->note_neg = $v;
            $this->modifiedColumns[] = PDocumentPeer::NOTE_NEG;
        }


        return $this;
    } // setNoteNeg()

    /**
     * Sets the value of the [published] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param boolean|integer|string $v The new value
     * @return PDocument The current object (for fluent API support)
     */
    public function setPublished($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->published !== $v) {
            $this->published = $v;
            $this->modifiedColumns[] = PDocumentPeer::PUBLISHED;
        }


        return $this;
    } // setPublished()

    /**
     * Sets the value of [published_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return PDocument The current object (for fluent API support)
     */
    public function setPublishedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->published_at !== null || $dt !== null) {
            $currentDateAsString = ($this->published_at !== null && $tmpDt = new DateTime($this->published_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->published_at = $newDateAsString;
                $this->modifiedColumns[] = PDocumentPeer::PUBLISHED_AT;
            }
        } // if either are not null


        return $this;
    } // setPublishedAt()

    /**
     * Set the value of [published_by] column.
     *
     * @param string $v new value
     * @return PDocument The current object (for fluent API support)
     */
    public function setPublishedBy($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (string) $v;
        }

        if ($this->published_by !== $v) {
            $this->published_by = $v;
            $this->modifiedColumns[] = PDocumentPeer::PUBLISHED_BY;
        }


        return $this;
    } // setPublishedBy()

    /**
     * Sets the value of the [online] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param boolean|integer|string $v The new value
     * @return PDocument The current object (for fluent API support)
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
            $this->modifiedColumns[] = PDocumentPeer::ONLINE;
        }


        return $this;
    } // setOnline()

    /**
     * Set the value of [descendant_class] column.
     *
     * @param string $v new value
     * @return PDocument The current object (for fluent API support)
     */
    public function setDescendantClass($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (string) $v;
        }

        if ($this->descendant_class !== $v) {
            $this->descendant_class = $v;
            $this->modifiedColumns[] = PDocumentPeer::DESCENDANT_CLASS;
        }


        return $this;
    } // setDescendantClass()

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
            $this->p_user_id = ($row[$startcol + 1] !== null) ? (int) $row[$startcol + 1] : null;
            $this->title = ($row[$startcol + 2] !== null) ? (string) $row[$startcol + 2] : null;
            $this->summary = ($row[$startcol + 3] !== null) ? (string) $row[$startcol + 3] : null;
            $this->description = ($row[$startcol + 4] !== null) ? (string) $row[$startcol + 4] : null;
            $this->more_info = ($row[$startcol + 5] !== null) ? (string) $row[$startcol + 5] : null;
            $this->note_pos = ($row[$startcol + 6] !== null) ? (int) $row[$startcol + 6] : null;
            $this->note_neg = ($row[$startcol + 7] !== null) ? (int) $row[$startcol + 7] : null;
            $this->published = ($row[$startcol + 8] !== null) ? (boolean) $row[$startcol + 8] : null;
            $this->published_at = ($row[$startcol + 9] !== null) ? (string) $row[$startcol + 9] : null;
            $this->published_by = ($row[$startcol + 10] !== null) ? (string) $row[$startcol + 10] : null;
            $this->online = ($row[$startcol + 11] !== null) ? (boolean) $row[$startcol + 11] : null;
            $this->descendant_class = ($row[$startcol + 12] !== null) ? (string) $row[$startcol + 12] : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }
            $this->postHydrate($row, $startcol, $rehydrate);
            return $startcol + 13; // 13 = PDocumentPeer::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating PDocument object", $e);
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
            $con = Propel::getConnection(PDocumentPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $stmt = PDocumentPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
        $row = $stmt->fetch(PDO::FETCH_NUM);
        $stmt->closeCursor();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aPUser = null;
            $this->collPDComments = null;

            $this->singlePDDebate = null;

            $this->singlePDReaction = null;

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
            $con = Propel::getConnection(PDocumentPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        try {
            $deleteQuery = PDocumentQuery::create()
                ->filterByPrimaryKey($this->getPrimaryKey());
            $ret = $this->preDelete($con);
            // archivable behavior
            if ($ret) {
                if ($this->archiveOnDelete) {
                    // do nothing yet. The object will be archived later when calling PDocumentQuery::delete().
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
            $con = Propel::getConnection(PDocumentPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        $isInsert = $this->isNew();
        try {
            $ret = $this->preSave($con);
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
            } else {
                $ret = $ret && $this->preUpdate($con);
            }
            if ($ret) {
                $affectedRows = $this->doSave($con);
                if ($isInsert) {
                    $this->postInsert($con);
                } else {
                    $this->postUpdate($con);
                }
                $this->postSave($con);
                PDocumentPeer::addInstanceToPool($this);
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
            // were passed to this object by their coresponding set
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

            if ($this->pDCommentsScheduledForDeletion !== null) {
                if (!$this->pDCommentsScheduledForDeletion->isEmpty()) {
                    PDCommentQuery::create()
                        ->filterByPrimaryKeys($this->pDCommentsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->pDCommentsScheduledForDeletion = null;
                }
            }

            if ($this->collPDComments !== null) {
                foreach ($this->collPDComments as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->pDDebatesScheduledForDeletion !== null) {
                if (!$this->pDDebatesScheduledForDeletion->isEmpty()) {
                    PDDebateQuery::create()
                        ->filterByPrimaryKeys($this->pDDebatesScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->pDDebatesScheduledForDeletion = null;
                }
            }

            if ($this->singlePDDebate !== null) {
                if (!$this->singlePDDebate->isDeleted() && ($this->singlePDDebate->isNew() || $this->singlePDDebate->isModified())) {
                        $affectedRows += $this->singlePDDebate->save($con);
                }
            }

            if ($this->pDReactionsScheduledForDeletion !== null) {
                if (!$this->pDReactionsScheduledForDeletion->isEmpty()) {
                    PDReactionQuery::create()
                        ->filterByPrimaryKeys($this->pDReactionsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->pDReactionsScheduledForDeletion = null;
                }
            }

            if ($this->singlePDReaction !== null) {
                if (!$this->singlePDReaction->isDeleted() && ($this->singlePDReaction->isNew() || $this->singlePDReaction->isModified())) {
                        $affectedRows += $this->singlePDReaction->save($con);
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

        $this->modifiedColumns[] = PDocumentPeer::ID;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . PDocumentPeer::ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(PDocumentPeer::ID)) {
            $modifiedColumns[':p' . $index++]  = '`id`';
        }
        if ($this->isColumnModified(PDocumentPeer::P_USER_ID)) {
            $modifiedColumns[':p' . $index++]  = '`p_user_id`';
        }
        if ($this->isColumnModified(PDocumentPeer::TITLE)) {
            $modifiedColumns[':p' . $index++]  = '`title`';
        }
        if ($this->isColumnModified(PDocumentPeer::SUMMARY)) {
            $modifiedColumns[':p' . $index++]  = '`summary`';
        }
        if ($this->isColumnModified(PDocumentPeer::DESCRIPTION)) {
            $modifiedColumns[':p' . $index++]  = '`description`';
        }
        if ($this->isColumnModified(PDocumentPeer::MORE_INFO)) {
            $modifiedColumns[':p' . $index++]  = '`more_info`';
        }
        if ($this->isColumnModified(PDocumentPeer::NOTE_POS)) {
            $modifiedColumns[':p' . $index++]  = '`note_pos`';
        }
        if ($this->isColumnModified(PDocumentPeer::NOTE_NEG)) {
            $modifiedColumns[':p' . $index++]  = '`note_neg`';
        }
        if ($this->isColumnModified(PDocumentPeer::PUBLISHED)) {
            $modifiedColumns[':p' . $index++]  = '`published`';
        }
        if ($this->isColumnModified(PDocumentPeer::PUBLISHED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`published_at`';
        }
        if ($this->isColumnModified(PDocumentPeer::PUBLISHED_BY)) {
            $modifiedColumns[':p' . $index++]  = '`published_by`';
        }
        if ($this->isColumnModified(PDocumentPeer::ONLINE)) {
            $modifiedColumns[':p' . $index++]  = '`online`';
        }
        if ($this->isColumnModified(PDocumentPeer::DESCENDANT_CLASS)) {
            $modifiedColumns[':p' . $index++]  = '`descendant_class`';
        }

        $sql = sprintf(
            'INSERT INTO `p_document` (%s) VALUES (%s)',
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
                    case '`p_user_id`':
                        $stmt->bindValue($identifier, $this->p_user_id, PDO::PARAM_INT);
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
                    case '`more_info`':
                        $stmt->bindValue($identifier, $this->more_info, PDO::PARAM_STR);
                        break;
                    case '`note_pos`':
                        $stmt->bindValue($identifier, $this->note_pos, PDO::PARAM_INT);
                        break;
                    case '`note_neg`':
                        $stmt->bindValue($identifier, $this->note_neg, PDO::PARAM_INT);
                        break;
                    case '`published`':
                        $stmt->bindValue($identifier, (int) $this->published, PDO::PARAM_INT);
                        break;
                    case '`published_at`':
                        $stmt->bindValue($identifier, $this->published_at, PDO::PARAM_STR);
                        break;
                    case '`published_by`':
                        $stmt->bindValue($identifier, $this->published_by, PDO::PARAM_STR);
                        break;
                    case '`online`':
                        $stmt->bindValue($identifier, (int) $this->online, PDO::PARAM_INT);
                        break;
                    case '`descendant_class`':
                        $stmt->bindValue($identifier, $this->descendant_class, PDO::PARAM_STR);
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


            // We call the validate method on the following object(s) if they
            // were passed to this object by their coresponding set
            // method.  This object relates to these object(s) by a
            // foreign key reference.

            if ($this->aPUser !== null) {
                if (!$this->aPUser->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aPUser->getValidationFailures());
                }
            }


            if (($retval = PDocumentPeer::doValidate($this, $columns)) !== true) {
                $failureMap = array_merge($failureMap, $retval);
            }


                if ($this->collPDComments !== null) {
                    foreach ($this->collPDComments as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->singlePDDebate !== null) {
                    if (!$this->singlePDDebate->validate($columns)) {
                        $failureMap = array_merge($failureMap, $this->singlePDDebate->getValidationFailures());
                    }
                }

                if ($this->singlePDReaction !== null) {
                    if (!$this->singlePDReaction->validate($columns)) {
                        $failureMap = array_merge($failureMap, $this->singlePDReaction->getValidationFailures());
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
        $pos = PDocumentPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                return $this->getPUserId();
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
                return $this->getMoreInfo();
                break;
            case 6:
                return $this->getNotePos();
                break;
            case 7:
                return $this->getNoteNeg();
                break;
            case 8:
                return $this->getPublished();
                break;
            case 9:
                return $this->getPublishedAt();
                break;
            case 10:
                return $this->getPublishedBy();
                break;
            case 11:
                return $this->getOnline();
                break;
            case 12:
                return $this->getDescendantClass();
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
        if (isset($alreadyDumpedObjects['PDocument'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['PDocument'][$this->getPrimaryKey()] = true;
        $keys = PDocumentPeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getPUserId(),
            $keys[2] => $this->getTitle(),
            $keys[3] => $this->getSummary(),
            $keys[4] => $this->getDescription(),
            $keys[5] => $this->getMoreInfo(),
            $keys[6] => $this->getNotePos(),
            $keys[7] => $this->getNoteNeg(),
            $keys[8] => $this->getPublished(),
            $keys[9] => $this->getPublishedAt(),
            $keys[10] => $this->getPublishedBy(),
            $keys[11] => $this->getOnline(),
            $keys[12] => $this->getDescendantClass(),
        );
        if ($includeForeignObjects) {
            if (null !== $this->aPUser) {
                $result['PUser'] = $this->aPUser->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->collPDComments) {
                $result['PDComments'] = $this->collPDComments->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->singlePDDebate) {
                $result['PDDebate'] = $this->singlePDDebate->toArray($keyType, $includeLazyLoadColumns, $alreadyDumpedObjects, true);
            }
            if (null !== $this->singlePDReaction) {
                $result['PDReaction'] = $this->singlePDReaction->toArray($keyType, $includeLazyLoadColumns, $alreadyDumpedObjects, true);
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
        $pos = PDocumentPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);

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
                $this->setPUserId($value);
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
                $this->setMoreInfo($value);
                break;
            case 6:
                $this->setNotePos($value);
                break;
            case 7:
                $this->setNoteNeg($value);
                break;
            case 8:
                $this->setPublished($value);
                break;
            case 9:
                $this->setPublishedAt($value);
                break;
            case 10:
                $this->setPublishedBy($value);
                break;
            case 11:
                $this->setOnline($value);
                break;
            case 12:
                $this->setDescendantClass($value);
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
        $keys = PDocumentPeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setPUserId($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setTitle($arr[$keys[2]]);
        if (array_key_exists($keys[3], $arr)) $this->setSummary($arr[$keys[3]]);
        if (array_key_exists($keys[4], $arr)) $this->setDescription($arr[$keys[4]]);
        if (array_key_exists($keys[5], $arr)) $this->setMoreInfo($arr[$keys[5]]);
        if (array_key_exists($keys[6], $arr)) $this->setNotePos($arr[$keys[6]]);
        if (array_key_exists($keys[7], $arr)) $this->setNoteNeg($arr[$keys[7]]);
        if (array_key_exists($keys[8], $arr)) $this->setPublished($arr[$keys[8]]);
        if (array_key_exists($keys[9], $arr)) $this->setPublishedAt($arr[$keys[9]]);
        if (array_key_exists($keys[10], $arr)) $this->setPublishedBy($arr[$keys[10]]);
        if (array_key_exists($keys[11], $arr)) $this->setOnline($arr[$keys[11]]);
        if (array_key_exists($keys[12], $arr)) $this->setDescendantClass($arr[$keys[12]]);
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(PDocumentPeer::DATABASE_NAME);

        if ($this->isColumnModified(PDocumentPeer::ID)) $criteria->add(PDocumentPeer::ID, $this->id);
        if ($this->isColumnModified(PDocumentPeer::P_USER_ID)) $criteria->add(PDocumentPeer::P_USER_ID, $this->p_user_id);
        if ($this->isColumnModified(PDocumentPeer::TITLE)) $criteria->add(PDocumentPeer::TITLE, $this->title);
        if ($this->isColumnModified(PDocumentPeer::SUMMARY)) $criteria->add(PDocumentPeer::SUMMARY, $this->summary);
        if ($this->isColumnModified(PDocumentPeer::DESCRIPTION)) $criteria->add(PDocumentPeer::DESCRIPTION, $this->description);
        if ($this->isColumnModified(PDocumentPeer::MORE_INFO)) $criteria->add(PDocumentPeer::MORE_INFO, $this->more_info);
        if ($this->isColumnModified(PDocumentPeer::NOTE_POS)) $criteria->add(PDocumentPeer::NOTE_POS, $this->note_pos);
        if ($this->isColumnModified(PDocumentPeer::NOTE_NEG)) $criteria->add(PDocumentPeer::NOTE_NEG, $this->note_neg);
        if ($this->isColumnModified(PDocumentPeer::PUBLISHED)) $criteria->add(PDocumentPeer::PUBLISHED, $this->published);
        if ($this->isColumnModified(PDocumentPeer::PUBLISHED_AT)) $criteria->add(PDocumentPeer::PUBLISHED_AT, $this->published_at);
        if ($this->isColumnModified(PDocumentPeer::PUBLISHED_BY)) $criteria->add(PDocumentPeer::PUBLISHED_BY, $this->published_by);
        if ($this->isColumnModified(PDocumentPeer::ONLINE)) $criteria->add(PDocumentPeer::ONLINE, $this->online);
        if ($this->isColumnModified(PDocumentPeer::DESCENDANT_CLASS)) $criteria->add(PDocumentPeer::DESCENDANT_CLASS, $this->descendant_class);

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
        $criteria = new Criteria(PDocumentPeer::DATABASE_NAME);
        $criteria->add(PDocumentPeer::ID, $this->id);

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
     * @param object $copyObj An object of PDocument (or compatible) type.
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setPUserId($this->getPUserId());
        $copyObj->setTitle($this->getTitle());
        $copyObj->setSummary($this->getSummary());
        $copyObj->setDescription($this->getDescription());
        $copyObj->setMoreInfo($this->getMoreInfo());
        $copyObj->setNotePos($this->getNotePos());
        $copyObj->setNoteNeg($this->getNoteNeg());
        $copyObj->setPublished($this->getPublished());
        $copyObj->setPublishedAt($this->getPublishedAt());
        $copyObj->setPublishedBy($this->getPublishedBy());
        $copyObj->setOnline($this->getOnline());
        $copyObj->setDescendantClass($this->getDescendantClass());

        if ($deepCopy && !$this->startCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);
            // store object hash to prevent cycle
            $this->startCopy = true;

            foreach ($this->getPDComments() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPDComment($relObj->copy($deepCopy));
                }
            }

            $relObj = $this->getPDDebate();
            if ($relObj) {
                $copyObj->setPDDebate($relObj->copy($deepCopy));
            }

            $relObj = $this->getPDReaction();
            if ($relObj) {
                $copyObj->setPDReaction($relObj->copy($deepCopy));
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
     * @return PDocument Clone of current object.
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
     * @return PDocumentPeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new PDocumentPeer();
        }

        return self::$peer;
    }

    /**
     * Declares an association between this object and a PUser object.
     *
     * @param             PUser $v
     * @return PDocument The current object (for fluent API support)
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
            $v->addPDocument($this);
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
                $this->aPUser->addPDocuments($this);
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
        if ('PDComment' == $relationName) {
            $this->initPDComments();
        }
    }

    /**
     * Clears out the collPDComments collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return PDocument The current object (for fluent API support)
     * @see        addPDComments()
     */
    public function clearPDComments()
    {
        $this->collPDComments = null; // important to set this to null since that means it is uninitialized
        $this->collPDCommentsPartial = null;

        return $this;
    }

    /**
     * reset is the collPDComments collection loaded partially
     *
     * @return void
     */
    public function resetPartialPDComments($v = true)
    {
        $this->collPDCommentsPartial = $v;
    }

    /**
     * Initializes the collPDComments collection.
     *
     * By default this just sets the collPDComments collection to an empty array (like clearcollPDComments());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPDComments($overrideExisting = true)
    {
        if (null !== $this->collPDComments && !$overrideExisting) {
            return;
        }
        $this->collPDComments = new PropelObjectCollection();
        $this->collPDComments->setModel('PDComment');
    }

    /**
     * Gets an array of PDComment objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this PDocument is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|PDComment[] List of PDComment objects
     * @throws PropelException
     */
    public function getPDComments($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collPDCommentsPartial && !$this->isNew();
        if (null === $this->collPDComments || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPDComments) {
                // return empty collection
                $this->initPDComments();
            } else {
                $collPDComments = PDCommentQuery::create(null, $criteria)
                    ->filterByPDocument($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collPDCommentsPartial && count($collPDComments)) {
                      $this->initPDComments(false);

                      foreach($collPDComments as $obj) {
                        if (false == $this->collPDComments->contains($obj)) {
                          $this->collPDComments->append($obj);
                        }
                      }

                      $this->collPDCommentsPartial = true;
                    }

                    $collPDComments->getInternalIterator()->rewind();
                    return $collPDComments;
                }

                if($partial && $this->collPDComments) {
                    foreach($this->collPDComments as $obj) {
                        if($obj->isNew()) {
                            $collPDComments[] = $obj;
                        }
                    }
                }

                $this->collPDComments = $collPDComments;
                $this->collPDCommentsPartial = false;
            }
        }

        return $this->collPDComments;
    }

    /**
     * Sets a collection of PDComment objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $pDComments A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return PDocument The current object (for fluent API support)
     */
    public function setPDComments(PropelCollection $pDComments, PropelPDO $con = null)
    {
        $pDCommentsToDelete = $this->getPDComments(new Criteria(), $con)->diff($pDComments);

        $this->pDCommentsScheduledForDeletion = unserialize(serialize($pDCommentsToDelete));

        foreach ($pDCommentsToDelete as $pDCommentRemoved) {
            $pDCommentRemoved->setPDocument(null);
        }

        $this->collPDComments = null;
        foreach ($pDComments as $pDComment) {
            $this->addPDComment($pDComment);
        }

        $this->collPDComments = $pDComments;
        $this->collPDCommentsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related PDComment objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related PDComment objects.
     * @throws PropelException
     */
    public function countPDComments(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collPDCommentsPartial && !$this->isNew();
        if (null === $this->collPDComments || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPDComments) {
                return 0;
            }

            if($partial && !$criteria) {
                return count($this->getPDComments());
            }
            $query = PDCommentQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPDocument($this)
                ->count($con);
        }

        return count($this->collPDComments);
    }

    /**
     * Method called to associate a PDComment object to this object
     * through the PDComment foreign key attribute.
     *
     * @param    PDComment $l PDComment
     * @return PDocument The current object (for fluent API support)
     */
    public function addPDComment(PDComment $l)
    {
        if ($this->collPDComments === null) {
            $this->initPDComments();
            $this->collPDCommentsPartial = true;
        }
        if (!in_array($l, $this->collPDComments->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddPDComment($l);
        }

        return $this;
    }

    /**
     * @param	PDComment $pDComment The pDComment object to add.
     */
    protected function doAddPDComment($pDComment)
    {
        $this->collPDComments[]= $pDComment;
        $pDComment->setPDocument($this);
    }

    /**
     * @param	PDComment $pDComment The pDComment object to remove.
     * @return PDocument The current object (for fluent API support)
     */
    public function removePDComment($pDComment)
    {
        if ($this->getPDComments()->contains($pDComment)) {
            $this->collPDComments->remove($this->collPDComments->search($pDComment));
            if (null === $this->pDCommentsScheduledForDeletion) {
                $this->pDCommentsScheduledForDeletion = clone $this->collPDComments;
                $this->pDCommentsScheduledForDeletion->clear();
            }
            $this->pDCommentsScheduledForDeletion[]= clone $pDComment;
            $pDComment->setPDocument(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this PDocument is new, it will return
     * an empty collection; or if this PDocument has previously
     * been saved, it will retrieve related PDComments from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in PDocument.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|PDComment[] List of PDComment objects
     */
    public function getPDCommentsJoinPUser($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PDCommentQuery::create(null, $criteria);
        $query->joinWith('PUser', $join_behavior);

        return $this->getPDComments($query, $con);
    }

    /**
     * Gets a single PDDebate object, which is related to this object by a one-to-one relationship.
     *
     * @param PropelPDO $con optional connection object
     * @return PDDebate
     * @throws PropelException
     */
    public function getPDDebate(PropelPDO $con = null)
    {

        if ($this->singlePDDebate === null && !$this->isNew()) {
            $this->singlePDDebate = PDDebateQuery::create()->findPk($this->getPrimaryKey(), $con);
        }

        return $this->singlePDDebate;
    }

    /**
     * Sets a single PDDebate object as related to this object by a one-to-one relationship.
     *
     * @param             PDDebate $v PDDebate
     * @return PDocument The current object (for fluent API support)
     * @throws PropelException
     */
    public function setPDDebate(PDDebate $v = null)
    {
        $this->singlePDDebate = $v;

        // Make sure that that the passed-in PDDebate isn't already associated with this object
        if ($v !== null && $v->getPDocument(null, false) === null) {
            $v->setPDocument($this);
        }

        return $this;
    }

    /**
     * Gets a single PDReaction object, which is related to this object by a one-to-one relationship.
     *
     * @param PropelPDO $con optional connection object
     * @return PDReaction
     * @throws PropelException
     */
    public function getPDReaction(PropelPDO $con = null)
    {

        if ($this->singlePDReaction === null && !$this->isNew()) {
            $this->singlePDReaction = PDReactionQuery::create()->findPk($this->getPrimaryKey(), $con);
        }

        return $this->singlePDReaction;
    }

    /**
     * Sets a single PDReaction object as related to this object by a one-to-one relationship.
     *
     * @param             PDReaction $v PDReaction
     * @return PDocument The current object (for fluent API support)
     * @throws PropelException
     */
    public function setPDReaction(PDReaction $v = null)
    {
        $this->singlePDReaction = $v;

        // Make sure that that the passed-in PDReaction isn't already associated with this object
        if ($v !== null && $v->getPDocument(null, false) === null) {
            $v->setPDocument($this);
        }

        return $this;
    }

    /**
     * Clears the current object and sets all attributes to their default values
     */
    public function clear()
    {
        $this->id = null;
        $this->p_user_id = null;
        $this->title = null;
        $this->summary = null;
        $this->description = null;
        $this->more_info = null;
        $this->note_pos = null;
        $this->note_neg = null;
        $this->published = null;
        $this->published_at = null;
        $this->published_by = null;
        $this->online = null;
        $this->descendant_class = null;
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
            if ($this->collPDComments) {
                foreach ($this->collPDComments as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->singlePDDebate) {
                $this->singlePDDebate->clearAllReferences($deep);
            }
            if ($this->singlePDReaction) {
                $this->singlePDReaction->clearAllReferences($deep);
            }
            if ($this->aPUser instanceof Persistent) {
              $this->aPUser->clearAllReferences($deep);
            }

            $this->alreadyInClearAllReferencesDeep = false;
        } // if ($deep)

        if ($this->collPDComments instanceof PropelCollection) {
            $this->collPDComments->clearIterator();
        }
        $this->collPDComments = null;
        if ($this->singlePDDebate instanceof PropelCollection) {
            $this->singlePDDebate->clearIterator();
        }
        $this->singlePDDebate = null;
        if ($this->singlePDReaction instanceof PropelCollection) {
            $this->singlePDReaction->clearIterator();
        }
        $this->singlePDReaction = null;
        $this->aPUser = null;
    }

    /**
     * return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(PDocumentPeer::DEFAULT_STRING_FORMAT);
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

    // archivable behavior

    /**
     * Get an archived version of the current object.
     *
     * @param PropelPDO $con Optional connection object
     *
     * @return     PDocumentArchive An archive object, or null if the current object was never archived
     */
    public function getArchive(PropelPDO $con = null)
    {
        if ($this->isNew()) {
            return null;
        }
        $archive = PDocumentArchiveQuery::create()
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
     * @return     PDocumentArchive The archive object based on this object
     */
    public function archive(PropelPDO $con = null)
    {
        if ($this->isNew()) {
            throw new PropelException('New objects cannot be archived. You must save the current object before calling archive().');
        }
        if (!$archive = $this->getArchive($con)) {
            $archive = new PDocumentArchive();
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
     * @return PDocument The current object (for fluent API support)
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
     * @param      PDocumentArchive $archive An archived object based on the same class
      * @param      Boolean $populateAutoIncrementPrimaryKeys
     *               If true, autoincrement columns are copied from the archive object.
     *               If false, autoincrement columns are left intact.
      *
     * @return     PDocument The current object (for fluent API support)
     */
    public function populateFromArchive($archive, $populateAutoIncrementPrimaryKeys = false) {
        if ($populateAutoIncrementPrimaryKeys) {
            $this->setId($archive->getId());
        }
        $this->setPUserId($archive->getPUserId());
        $this->setTitle($archive->getTitle());
        $this->setSummary($archive->getSummary());
        $this->setDescription($archive->getDescription());
        $this->setMoreInfo($archive->getMoreInfo());
        $this->setNotePos($archive->getNotePos());
        $this->setNoteNeg($archive->getNoteNeg());
        $this->setPublished($archive->getPublished());
        $this->setPublishedAt($archive->getPublishedAt());
        $this->setPublishedBy($archive->getPublishedBy());
        $this->setOnline($archive->getOnline());
        $this->setDescendantClass($archive->getDescendantClass());

        return $this;
    }

    /**
     * Removes the object from the database without archiving it.
     *
     * @param PropelPDO $con Optional connection object
     *
     * @return     PDocument The current object (for fluent API support)
     */
    public function deleteWithoutArchive(PropelPDO $con = null)
    {
        $this->archiveOnDelete = false;

        return $this->delete($con);
    }

    // concrete_inheritance_parent behavior

    /**
     * Whether or not this object is the parent of a child object
     *
     * @return    bool
     */
    public function hasChildObject()
    {
        return $this->getDescendantClass() !== null;
    }

    /**
     * Get the child object of this object
     *
     * @return    mixed
     */
    public function getChildObject()
    {
        if (!$this->hasChildObject()) {
            return null;
        }
        $childObjectClass = $this->getDescendantClass();
        $childObject = PropelQuery::from($childObjectClass)->findPk($this->getPrimaryKey());

        return $childObject->hasChildObject() ? $childObject->getChildObject() : $childObject;
    }

}
