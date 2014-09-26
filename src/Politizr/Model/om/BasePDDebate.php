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
use Politizr\Model\PDDebateArchive;
use Politizr\Model\PDDebateArchiveQuery;
use Politizr\Model\PDDebatePeer;
use Politizr\Model\PDDebateQuery;
use Politizr\Model\PDReaction;
use Politizr\Model\PDReactionQuery;
use Politizr\Model\PDocument;
use Politizr\Model\PDocumentQuery;
use Politizr\Model\PTag;
use Politizr\Model\PTagQuery;
use Politizr\Model\PUFollowDD;
use Politizr\Model\PUFollowDDQuery;
use Politizr\Model\PUser;
use Politizr\Model\PUserQuery;

abstract class BasePDDebate extends BaseObject implements Persistent
{
    /**
     * Peer class name
     */
    const PEER = 'Politizr\\Model\\PDDebatePeer';

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        PDDebatePeer
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
     * The value for the file_name field.
     * @var        string
     */
    protected $file_name;

    /**
     * The value for the p_document_id field.
     * @var        int
     */
    protected $p_document_id;

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
     * @var        PDocument
     */
    protected $aPDocument;

    /**
     * @var        PropelObjectCollection|PUFollowDD[] Collection to store aggregation of PUFollowDD objects.
     */
    protected $collPuFollowDdPDDebates;
    protected $collPuFollowDdPDDebatesPartial;

    /**
     * @var        PropelObjectCollection|PDReaction[] Collection to store aggregation of PDReaction objects.
     */
    protected $collPDReactions;
    protected $collPDReactionsPartial;

    /**
     * @var        PropelObjectCollection|PDDTaggedT[] Collection to store aggregation of PDDTaggedT objects.
     */
    protected $collPddTaggedTPDDebates;
    protected $collPddTaggedTPDDebatesPartial;

    /**
     * @var        PropelObjectCollection|PUser[] Collection to store aggregation of PUser objects.
     */
    protected $collPuFollowDdPUsers;

    /**
     * @var        PropelObjectCollection|PTag[] Collection to store aggregation of PTag objects.
     */
    protected $collPddTaggedTPTags;

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
    protected $puFollowDdPUsersScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $pddTaggedTPTagsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $puFollowDdPDDebatesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $pDReactionsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $pddTaggedTPDDebatesScheduledForDeletion = null;

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
     * Get the [file_name] column value.
     *
     * @return string
     */
    public function getFileName()
    {
        return $this->file_name;
    }

    /**
     * Get the [p_document_id] column value.
     *
     * @return int
     */
    public function getPDocumentId()
    {
        return $this->p_document_id;
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
     * @return PDDebate The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[] = PDDebatePeer::ID;
        }


        return $this;
    } // setId()

    /**
     * Set the value of [file_name] column.
     *
     * @param string $v new value
     * @return PDDebate The current object (for fluent API support)
     */
    public function setFileName($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (string) $v;
        }

        if ($this->file_name !== $v) {
            $this->file_name = $v;
            $this->modifiedColumns[] = PDDebatePeer::FILE_NAME;
        }


        return $this;
    } // setFileName()

    /**
     * Set the value of [p_document_id] column.
     *
     * @param int $v new value
     * @return PDDebate The current object (for fluent API support)
     */
    public function setPDocumentId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->p_document_id !== $v) {
            $this->p_document_id = $v;
            $this->modifiedColumns[] = PDDebatePeer::P_DOCUMENT_ID;
        }

        if ($this->aPDocument !== null && $this->aPDocument->getId() !== $v) {
            $this->aPDocument = null;
        }


        return $this;
    } // setPDocumentId()

    /**
     * Sets the value of [created_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return PDDebate The current object (for fluent API support)
     */
    public function setCreatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->created_at !== null || $dt !== null) {
            $currentDateAsString = ($this->created_at !== null && $tmpDt = new DateTime($this->created_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->created_at = $newDateAsString;
                $this->modifiedColumns[] = PDDebatePeer::CREATED_AT;
            }
        } // if either are not null


        return $this;
    } // setCreatedAt()

    /**
     * Sets the value of [updated_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return PDDebate The current object (for fluent API support)
     */
    public function setUpdatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->updated_at !== null || $dt !== null) {
            $currentDateAsString = ($this->updated_at !== null && $tmpDt = new DateTime($this->updated_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->updated_at = $newDateAsString;
                $this->modifiedColumns[] = PDDebatePeer::UPDATED_AT;
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
            $this->file_name = ($row[$startcol + 1] !== null) ? (string) $row[$startcol + 1] : null;
            $this->p_document_id = ($row[$startcol + 2] !== null) ? (int) $row[$startcol + 2] : null;
            $this->created_at = ($row[$startcol + 3] !== null) ? (string) $row[$startcol + 3] : null;
            $this->updated_at = ($row[$startcol + 4] !== null) ? (string) $row[$startcol + 4] : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }
            $this->postHydrate($row, $startcol, $rehydrate);
            return $startcol + 5; // 5 = PDDebatePeer::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating PDDebate object", $e);
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

        if ($this->aPDocument !== null && $this->p_document_id !== $this->aPDocument->getId()) {
            $this->aPDocument = null;
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
            $con = Propel::getConnection(PDDebatePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $stmt = PDDebatePeer::doSelectStmt($this->buildPkeyCriteria(), $con);
        $row = $stmt->fetch(PDO::FETCH_NUM);
        $stmt->closeCursor();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aPDocument = null;
            $this->collPuFollowDdPDDebates = null;

            $this->collPDReactions = null;

            $this->collPddTaggedTPDDebates = null;

            $this->collPuFollowDdPUsers = null;
            $this->collPddTaggedTPTags = null;
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
            $con = Propel::getConnection(PDDebatePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        try {
            $deleteQuery = PDDebateQuery::create()
                ->filterByPrimaryKey($this->getPrimaryKey());
            $ret = $this->preDelete($con);
            // archivable behavior
            if ($ret) {
                if ($this->archiveOnDelete) {
                    // do nothing yet. The object will be archived later when calling PDDebateQuery::delete().
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
            $con = Propel::getConnection(PDDebatePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        $isInsert = $this->isNew();
        try {
            $ret = $this->preSave($con);
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
                // timestampable behavior
                if (!$this->isColumnModified(PDDebatePeer::CREATED_AT)) {
                    $this->setCreatedAt(time());
                }
                if (!$this->isColumnModified(PDDebatePeer::UPDATED_AT)) {
                    $this->setUpdatedAt(time());
                }
            } else {
                $ret = $ret && $this->preUpdate($con);
                // timestampable behavior
                if ($this->isModified() && !$this->isColumnModified(PDDebatePeer::UPDATED_AT)) {
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
                PDDebatePeer::addInstanceToPool($this);
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

            if ($this->aPDocument !== null) {
                if ($this->aPDocument->isModified() || $this->aPDocument->isNew()) {
                    $affectedRows += $this->aPDocument->save($con);
                }
                $this->setPDocument($this->aPDocument);
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

            if ($this->puFollowDdPUsersScheduledForDeletion !== null) {
                if (!$this->puFollowDdPUsersScheduledForDeletion->isEmpty()) {
                    $pks = array();
                    $pk = $this->getPrimaryKey();
                    foreach ($this->puFollowDdPUsersScheduledForDeletion->getPrimaryKeys(false) as $remotePk) {
                        $pks[] = array($remotePk, $pk);
                    }
                    PuFollowDdPDDebateQuery::create()
                        ->filterByPrimaryKeys($pks)
                        ->delete($con);
                    $this->puFollowDdPUsersScheduledForDeletion = null;
                }

                foreach ($this->getPuFollowDdPUsers() as $puFollowDdPUser) {
                    if ($puFollowDdPUser->isModified()) {
                        $puFollowDdPUser->save($con);
                    }
                }
            } elseif ($this->collPuFollowDdPUsers) {
                foreach ($this->collPuFollowDdPUsers as $puFollowDdPUser) {
                    if ($puFollowDdPUser->isModified()) {
                        $puFollowDdPUser->save($con);
                    }
                }
            }

            if ($this->pddTaggedTPTagsScheduledForDeletion !== null) {
                if (!$this->pddTaggedTPTagsScheduledForDeletion->isEmpty()) {
                    $pks = array();
                    $pk = $this->getPrimaryKey();
                    foreach ($this->pddTaggedTPTagsScheduledForDeletion->getPrimaryKeys(false) as $remotePk) {
                        $pks[] = array($pk, $remotePk);
                    }
                    PddTaggedTPDDebateQuery::create()
                        ->filterByPrimaryKeys($pks)
                        ->delete($con);
                    $this->pddTaggedTPTagsScheduledForDeletion = null;
                }

                foreach ($this->getPddTaggedTPTags() as $pddTaggedTPTag) {
                    if ($pddTaggedTPTag->isModified()) {
                        $pddTaggedTPTag->save($con);
                    }
                }
            } elseif ($this->collPddTaggedTPTags) {
                foreach ($this->collPddTaggedTPTags as $pddTaggedTPTag) {
                    if ($pddTaggedTPTag->isModified()) {
                        $pddTaggedTPTag->save($con);
                    }
                }
            }

            if ($this->puFollowDdPDDebatesScheduledForDeletion !== null) {
                if (!$this->puFollowDdPDDebatesScheduledForDeletion->isEmpty()) {
                    PUFollowDDQuery::create()
                        ->filterByPrimaryKeys($this->puFollowDdPDDebatesScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->puFollowDdPDDebatesScheduledForDeletion = null;
                }
            }

            if ($this->collPuFollowDdPDDebates !== null) {
                foreach ($this->collPuFollowDdPDDebates as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
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

            if ($this->collPDReactions !== null) {
                foreach ($this->collPDReactions as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->pddTaggedTPDDebatesScheduledForDeletion !== null) {
                if (!$this->pddTaggedTPDDebatesScheduledForDeletion->isEmpty()) {
                    PDDTaggedTQuery::create()
                        ->filterByPrimaryKeys($this->pddTaggedTPDDebatesScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->pddTaggedTPDDebatesScheduledForDeletion = null;
                }
            }

            if ($this->collPddTaggedTPDDebates !== null) {
                foreach ($this->collPddTaggedTPDDebates as $referrerFK) {
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

        $this->modifiedColumns[] = PDDebatePeer::ID;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . PDDebatePeer::ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(PDDebatePeer::ID)) {
            $modifiedColumns[':p' . $index++]  = '`id`';
        }
        if ($this->isColumnModified(PDDebatePeer::FILE_NAME)) {
            $modifiedColumns[':p' . $index++]  = '`file_name`';
        }
        if ($this->isColumnModified(PDDebatePeer::P_DOCUMENT_ID)) {
            $modifiedColumns[':p' . $index++]  = '`p_document_id`';
        }
        if ($this->isColumnModified(PDDebatePeer::CREATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`created_at`';
        }
        if ($this->isColumnModified(PDDebatePeer::UPDATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`updated_at`';
        }

        $sql = sprintf(
            'INSERT INTO `p_d_debate` (%s) VALUES (%s)',
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
                    case '`file_name`':
                        $stmt->bindValue($identifier, $this->file_name, PDO::PARAM_STR);
                        break;
                    case '`p_document_id`':
                        $stmt->bindValue($identifier, $this->p_document_id, PDO::PARAM_INT);
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


            // We call the validate method on the following object(s) if they
            // were passed to this object by their coresponding set
            // method.  This object relates to these object(s) by a
            // foreign key reference.

            if ($this->aPDocument !== null) {
                if (!$this->aPDocument->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aPDocument->getValidationFailures());
                }
            }


            if (($retval = PDDebatePeer::doValidate($this, $columns)) !== true) {
                $failureMap = array_merge($failureMap, $retval);
            }


                if ($this->collPuFollowDdPDDebates !== null) {
                    foreach ($this->collPuFollowDdPDDebates as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collPDReactions !== null) {
                    foreach ($this->collPDReactions as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collPddTaggedTPDDebates !== null) {
                    foreach ($this->collPddTaggedTPDDebates as $referrerFK) {
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
        $pos = PDDebatePeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                return $this->getFileName();
                break;
            case 2:
                return $this->getPDocumentId();
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
        if (isset($alreadyDumpedObjects['PDDebate'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['PDDebate'][$this->getPrimaryKey()] = true;
        $keys = PDDebatePeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getFileName(),
            $keys[2] => $this->getPDocumentId(),
            $keys[3] => $this->getCreatedAt(),
            $keys[4] => $this->getUpdatedAt(),
        );
        if ($includeForeignObjects) {
            if (null !== $this->aPDocument) {
                $result['PDocument'] = $this->aPDocument->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->collPuFollowDdPDDebates) {
                $result['PuFollowDdPDDebates'] = $this->collPuFollowDdPDDebates->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPDReactions) {
                $result['PDReactions'] = $this->collPDReactions->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPddTaggedTPDDebates) {
                $result['PddTaggedTPDDebates'] = $this->collPddTaggedTPDDebates->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
        $pos = PDDebatePeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);

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
                $this->setFileName($value);
                break;
            case 2:
                $this->setPDocumentId($value);
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
        $keys = PDDebatePeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setFileName($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setPDocumentId($arr[$keys[2]]);
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
        $criteria = new Criteria(PDDebatePeer::DATABASE_NAME);

        if ($this->isColumnModified(PDDebatePeer::ID)) $criteria->add(PDDebatePeer::ID, $this->id);
        if ($this->isColumnModified(PDDebatePeer::FILE_NAME)) $criteria->add(PDDebatePeer::FILE_NAME, $this->file_name);
        if ($this->isColumnModified(PDDebatePeer::P_DOCUMENT_ID)) $criteria->add(PDDebatePeer::P_DOCUMENT_ID, $this->p_document_id);
        if ($this->isColumnModified(PDDebatePeer::CREATED_AT)) $criteria->add(PDDebatePeer::CREATED_AT, $this->created_at);
        if ($this->isColumnModified(PDDebatePeer::UPDATED_AT)) $criteria->add(PDDebatePeer::UPDATED_AT, $this->updated_at);

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
        $criteria = new Criteria(PDDebatePeer::DATABASE_NAME);
        $criteria->add(PDDebatePeer::ID, $this->id);

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
     * @param object $copyObj An object of PDDebate (or compatible) type.
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setFileName($this->getFileName());
        $copyObj->setPDocumentId($this->getPDocumentId());
        $copyObj->setCreatedAt($this->getCreatedAt());
        $copyObj->setUpdatedAt($this->getUpdatedAt());

        if ($deepCopy && !$this->startCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);
            // store object hash to prevent cycle
            $this->startCopy = true;

            foreach ($this->getPuFollowDdPDDebates() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPuFollowDdPDDebate($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPDReactions() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPDReaction($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPddTaggedTPDDebates() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPddTaggedTPDDebate($relObj->copy($deepCopy));
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
     * @return PDDebate Clone of current object.
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
     * @return PDDebatePeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new PDDebatePeer();
        }

        return self::$peer;
    }

    /**
     * Declares an association between this object and a PDocument object.
     *
     * @param             PDocument $v
     * @return PDDebate The current object (for fluent API support)
     * @throws PropelException
     */
    public function setPDocument(PDocument $v = null)
    {
        if ($v === null) {
            $this->setPDocumentId(NULL);
        } else {
            $this->setPDocumentId($v->getId());
        }

        $this->aPDocument = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the PDocument object, it will not be re-added.
        if ($v !== null) {
            $v->addPDDebate($this);
        }


        return $this;
    }


    /**
     * Get the associated PDocument object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return PDocument The associated PDocument object.
     * @throws PropelException
     */
    public function getPDocument(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aPDocument === null && ($this->p_document_id !== null) && $doQuery) {
            $this->aPDocument = PDocumentQuery::create()->findPk($this->p_document_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aPDocument->addPDDebates($this);
             */
        }

        return $this->aPDocument;
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
        if ('PuFollowDdPDDebate' == $relationName) {
            $this->initPuFollowDdPDDebates();
        }
        if ('PDReaction' == $relationName) {
            $this->initPDReactions();
        }
        if ('PddTaggedTPDDebate' == $relationName) {
            $this->initPddTaggedTPDDebates();
        }
    }

    /**
     * Clears out the collPuFollowDdPDDebates collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return PDDebate The current object (for fluent API support)
     * @see        addPuFollowDdPDDebates()
     */
    public function clearPuFollowDdPDDebates()
    {
        $this->collPuFollowDdPDDebates = null; // important to set this to null since that means it is uninitialized
        $this->collPuFollowDdPDDebatesPartial = null;

        return $this;
    }

    /**
     * reset is the collPuFollowDdPDDebates collection loaded partially
     *
     * @return void
     */
    public function resetPartialPuFollowDdPDDebates($v = true)
    {
        $this->collPuFollowDdPDDebatesPartial = $v;
    }

    /**
     * Initializes the collPuFollowDdPDDebates collection.
     *
     * By default this just sets the collPuFollowDdPDDebates collection to an empty array (like clearcollPuFollowDdPDDebates());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPuFollowDdPDDebates($overrideExisting = true)
    {
        if (null !== $this->collPuFollowDdPDDebates && !$overrideExisting) {
            return;
        }
        $this->collPuFollowDdPDDebates = new PropelObjectCollection();
        $this->collPuFollowDdPDDebates->setModel('PUFollowDD');
    }

    /**
     * Gets an array of PUFollowDD objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this PDDebate is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|PUFollowDD[] List of PUFollowDD objects
     * @throws PropelException
     */
    public function getPuFollowDdPDDebates($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collPuFollowDdPDDebatesPartial && !$this->isNew();
        if (null === $this->collPuFollowDdPDDebates || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPuFollowDdPDDebates) {
                // return empty collection
                $this->initPuFollowDdPDDebates();
            } else {
                $collPuFollowDdPDDebates = PUFollowDDQuery::create(null, $criteria)
                    ->filterByPuFollowDdPDDebate($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collPuFollowDdPDDebatesPartial && count($collPuFollowDdPDDebates)) {
                      $this->initPuFollowDdPDDebates(false);

                      foreach($collPuFollowDdPDDebates as $obj) {
                        if (false == $this->collPuFollowDdPDDebates->contains($obj)) {
                          $this->collPuFollowDdPDDebates->append($obj);
                        }
                      }

                      $this->collPuFollowDdPDDebatesPartial = true;
                    }

                    $collPuFollowDdPDDebates->getInternalIterator()->rewind();
                    return $collPuFollowDdPDDebates;
                }

                if($partial && $this->collPuFollowDdPDDebates) {
                    foreach($this->collPuFollowDdPDDebates as $obj) {
                        if($obj->isNew()) {
                            $collPuFollowDdPDDebates[] = $obj;
                        }
                    }
                }

                $this->collPuFollowDdPDDebates = $collPuFollowDdPDDebates;
                $this->collPuFollowDdPDDebatesPartial = false;
            }
        }

        return $this->collPuFollowDdPDDebates;
    }

    /**
     * Sets a collection of PuFollowDdPDDebate objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $puFollowDdPDDebates A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return PDDebate The current object (for fluent API support)
     */
    public function setPuFollowDdPDDebates(PropelCollection $puFollowDdPDDebates, PropelPDO $con = null)
    {
        $puFollowDdPDDebatesToDelete = $this->getPuFollowDdPDDebates(new Criteria(), $con)->diff($puFollowDdPDDebates);

        $this->puFollowDdPDDebatesScheduledForDeletion = unserialize(serialize($puFollowDdPDDebatesToDelete));

        foreach ($puFollowDdPDDebatesToDelete as $puFollowDdPDDebateRemoved) {
            $puFollowDdPDDebateRemoved->setPuFollowDdPDDebate(null);
        }

        $this->collPuFollowDdPDDebates = null;
        foreach ($puFollowDdPDDebates as $puFollowDdPDDebate) {
            $this->addPuFollowDdPDDebate($puFollowDdPDDebate);
        }

        $this->collPuFollowDdPDDebates = $puFollowDdPDDebates;
        $this->collPuFollowDdPDDebatesPartial = false;

        return $this;
    }

    /**
     * Returns the number of related PUFollowDD objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related PUFollowDD objects.
     * @throws PropelException
     */
    public function countPuFollowDdPDDebates(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collPuFollowDdPDDebatesPartial && !$this->isNew();
        if (null === $this->collPuFollowDdPDDebates || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPuFollowDdPDDebates) {
                return 0;
            }

            if($partial && !$criteria) {
                return count($this->getPuFollowDdPDDebates());
            }
            $query = PUFollowDDQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPuFollowDdPDDebate($this)
                ->count($con);
        }

        return count($this->collPuFollowDdPDDebates);
    }

    /**
     * Method called to associate a PUFollowDD object to this object
     * through the PUFollowDD foreign key attribute.
     *
     * @param    PUFollowDD $l PUFollowDD
     * @return PDDebate The current object (for fluent API support)
     */
    public function addPuFollowDdPDDebate(PUFollowDD $l)
    {
        if ($this->collPuFollowDdPDDebates === null) {
            $this->initPuFollowDdPDDebates();
            $this->collPuFollowDdPDDebatesPartial = true;
        }
        if (!in_array($l, $this->collPuFollowDdPDDebates->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddPuFollowDdPDDebate($l);
        }

        return $this;
    }

    /**
     * @param	PuFollowDdPDDebate $puFollowDdPDDebate The puFollowDdPDDebate object to add.
     */
    protected function doAddPuFollowDdPDDebate($puFollowDdPDDebate)
    {
        $this->collPuFollowDdPDDebates[]= $puFollowDdPDDebate;
        $puFollowDdPDDebate->setPuFollowDdPDDebate($this);
    }

    /**
     * @param	PuFollowDdPDDebate $puFollowDdPDDebate The puFollowDdPDDebate object to remove.
     * @return PDDebate The current object (for fluent API support)
     */
    public function removePuFollowDdPDDebate($puFollowDdPDDebate)
    {
        if ($this->getPuFollowDdPDDebates()->contains($puFollowDdPDDebate)) {
            $this->collPuFollowDdPDDebates->remove($this->collPuFollowDdPDDebates->search($puFollowDdPDDebate));
            if (null === $this->puFollowDdPDDebatesScheduledForDeletion) {
                $this->puFollowDdPDDebatesScheduledForDeletion = clone $this->collPuFollowDdPDDebates;
                $this->puFollowDdPDDebatesScheduledForDeletion->clear();
            }
            $this->puFollowDdPDDebatesScheduledForDeletion[]= clone $puFollowDdPDDebate;
            $puFollowDdPDDebate->setPuFollowDdPDDebate(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this PDDebate is new, it will return
     * an empty collection; or if this PDDebate has previously
     * been saved, it will retrieve related PuFollowDdPDDebates from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in PDDebate.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|PUFollowDD[] List of PUFollowDD objects
     */
    public function getPuFollowDdPDDebatesJoinPuFollowDdPUser($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PUFollowDDQuery::create(null, $criteria);
        $query->joinWith('PuFollowDdPUser', $join_behavior);

        return $this->getPuFollowDdPDDebates($query, $con);
    }

    /**
     * Clears out the collPDReactions collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return PDDebate The current object (for fluent API support)
     * @see        addPDReactions()
     */
    public function clearPDReactions()
    {
        $this->collPDReactions = null; // important to set this to null since that means it is uninitialized
        $this->collPDReactionsPartial = null;

        return $this;
    }

    /**
     * reset is the collPDReactions collection loaded partially
     *
     * @return void
     */
    public function resetPartialPDReactions($v = true)
    {
        $this->collPDReactionsPartial = $v;
    }

    /**
     * Initializes the collPDReactions collection.
     *
     * By default this just sets the collPDReactions collection to an empty array (like clearcollPDReactions());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPDReactions($overrideExisting = true)
    {
        if (null !== $this->collPDReactions && !$overrideExisting) {
            return;
        }
        $this->collPDReactions = new PropelObjectCollection();
        $this->collPDReactions->setModel('PDReaction');
    }

    /**
     * Gets an array of PDReaction objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this PDDebate is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|PDReaction[] List of PDReaction objects
     * @throws PropelException
     */
    public function getPDReactions($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collPDReactionsPartial && !$this->isNew();
        if (null === $this->collPDReactions || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPDReactions) {
                // return empty collection
                $this->initPDReactions();
            } else {
                $collPDReactions = PDReactionQuery::create(null, $criteria)
                    ->filterByPDDebate($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collPDReactionsPartial && count($collPDReactions)) {
                      $this->initPDReactions(false);

                      foreach($collPDReactions as $obj) {
                        if (false == $this->collPDReactions->contains($obj)) {
                          $this->collPDReactions->append($obj);
                        }
                      }

                      $this->collPDReactionsPartial = true;
                    }

                    $collPDReactions->getInternalIterator()->rewind();
                    return $collPDReactions;
                }

                if($partial && $this->collPDReactions) {
                    foreach($this->collPDReactions as $obj) {
                        if($obj->isNew()) {
                            $collPDReactions[] = $obj;
                        }
                    }
                }

                $this->collPDReactions = $collPDReactions;
                $this->collPDReactionsPartial = false;
            }
        }

        return $this->collPDReactions;
    }

    /**
     * Sets a collection of PDReaction objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $pDReactions A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return PDDebate The current object (for fluent API support)
     */
    public function setPDReactions(PropelCollection $pDReactions, PropelPDO $con = null)
    {
        $pDReactionsToDelete = $this->getPDReactions(new Criteria(), $con)->diff($pDReactions);

        $this->pDReactionsScheduledForDeletion = unserialize(serialize($pDReactionsToDelete));

        foreach ($pDReactionsToDelete as $pDReactionRemoved) {
            $pDReactionRemoved->setPDDebate(null);
        }

        $this->collPDReactions = null;
        foreach ($pDReactions as $pDReaction) {
            $this->addPDReaction($pDReaction);
        }

        $this->collPDReactions = $pDReactions;
        $this->collPDReactionsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related PDReaction objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related PDReaction objects.
     * @throws PropelException
     */
    public function countPDReactions(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collPDReactionsPartial && !$this->isNew();
        if (null === $this->collPDReactions || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPDReactions) {
                return 0;
            }

            if($partial && !$criteria) {
                return count($this->getPDReactions());
            }
            $query = PDReactionQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPDDebate($this)
                ->count($con);
        }

        return count($this->collPDReactions);
    }

    /**
     * Method called to associate a PDReaction object to this object
     * through the PDReaction foreign key attribute.
     *
     * @param    PDReaction $l PDReaction
     * @return PDDebate The current object (for fluent API support)
     */
    public function addPDReaction(PDReaction $l)
    {
        if ($this->collPDReactions === null) {
            $this->initPDReactions();
            $this->collPDReactionsPartial = true;
        }
        if (!in_array($l, $this->collPDReactions->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddPDReaction($l);
        }

        return $this;
    }

    /**
     * @param	PDReaction $pDReaction The pDReaction object to add.
     */
    protected function doAddPDReaction($pDReaction)
    {
        $this->collPDReactions[]= $pDReaction;
        $pDReaction->setPDDebate($this);
    }

    /**
     * @param	PDReaction $pDReaction The pDReaction object to remove.
     * @return PDDebate The current object (for fluent API support)
     */
    public function removePDReaction($pDReaction)
    {
        if ($this->getPDReactions()->contains($pDReaction)) {
            $this->collPDReactions->remove($this->collPDReactions->search($pDReaction));
            if (null === $this->pDReactionsScheduledForDeletion) {
                $this->pDReactionsScheduledForDeletion = clone $this->collPDReactions;
                $this->pDReactionsScheduledForDeletion->clear();
            }
            $this->pDReactionsScheduledForDeletion[]= clone $pDReaction;
            $pDReaction->setPDDebate(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this PDDebate is new, it will return
     * an empty collection; or if this PDDebate has previously
     * been saved, it will retrieve related PDReactions from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in PDDebate.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|PDReaction[] List of PDReaction objects
     */
    public function getPDReactionsJoinPDocument($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PDReactionQuery::create(null, $criteria);
        $query->joinWith('PDocument', $join_behavior);

        return $this->getPDReactions($query, $con);
    }

    /**
     * Clears out the collPddTaggedTPDDebates collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return PDDebate The current object (for fluent API support)
     * @see        addPddTaggedTPDDebates()
     */
    public function clearPddTaggedTPDDebates()
    {
        $this->collPddTaggedTPDDebates = null; // important to set this to null since that means it is uninitialized
        $this->collPddTaggedTPDDebatesPartial = null;

        return $this;
    }

    /**
     * reset is the collPddTaggedTPDDebates collection loaded partially
     *
     * @return void
     */
    public function resetPartialPddTaggedTPDDebates($v = true)
    {
        $this->collPddTaggedTPDDebatesPartial = $v;
    }

    /**
     * Initializes the collPddTaggedTPDDebates collection.
     *
     * By default this just sets the collPddTaggedTPDDebates collection to an empty array (like clearcollPddTaggedTPDDebates());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPddTaggedTPDDebates($overrideExisting = true)
    {
        if (null !== $this->collPddTaggedTPDDebates && !$overrideExisting) {
            return;
        }
        $this->collPddTaggedTPDDebates = new PropelObjectCollection();
        $this->collPddTaggedTPDDebates->setModel('PDDTaggedT');
    }

    /**
     * Gets an array of PDDTaggedT objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this PDDebate is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|PDDTaggedT[] List of PDDTaggedT objects
     * @throws PropelException
     */
    public function getPddTaggedTPDDebates($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collPddTaggedTPDDebatesPartial && !$this->isNew();
        if (null === $this->collPddTaggedTPDDebates || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPddTaggedTPDDebates) {
                // return empty collection
                $this->initPddTaggedTPDDebates();
            } else {
                $collPddTaggedTPDDebates = PDDTaggedTQuery::create(null, $criteria)
                    ->filterByPddTaggedTPDDebate($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collPddTaggedTPDDebatesPartial && count($collPddTaggedTPDDebates)) {
                      $this->initPddTaggedTPDDebates(false);

                      foreach($collPddTaggedTPDDebates as $obj) {
                        if (false == $this->collPddTaggedTPDDebates->contains($obj)) {
                          $this->collPddTaggedTPDDebates->append($obj);
                        }
                      }

                      $this->collPddTaggedTPDDebatesPartial = true;
                    }

                    $collPddTaggedTPDDebates->getInternalIterator()->rewind();
                    return $collPddTaggedTPDDebates;
                }

                if($partial && $this->collPddTaggedTPDDebates) {
                    foreach($this->collPddTaggedTPDDebates as $obj) {
                        if($obj->isNew()) {
                            $collPddTaggedTPDDebates[] = $obj;
                        }
                    }
                }

                $this->collPddTaggedTPDDebates = $collPddTaggedTPDDebates;
                $this->collPddTaggedTPDDebatesPartial = false;
            }
        }

        return $this->collPddTaggedTPDDebates;
    }

    /**
     * Sets a collection of PddTaggedTPDDebate objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $pddTaggedTPDDebates A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return PDDebate The current object (for fluent API support)
     */
    public function setPddTaggedTPDDebates(PropelCollection $pddTaggedTPDDebates, PropelPDO $con = null)
    {
        $pddTaggedTPDDebatesToDelete = $this->getPddTaggedTPDDebates(new Criteria(), $con)->diff($pddTaggedTPDDebates);

        $this->pddTaggedTPDDebatesScheduledForDeletion = unserialize(serialize($pddTaggedTPDDebatesToDelete));

        foreach ($pddTaggedTPDDebatesToDelete as $pddTaggedTPDDebateRemoved) {
            $pddTaggedTPDDebateRemoved->setPddTaggedTPDDebate(null);
        }

        $this->collPddTaggedTPDDebates = null;
        foreach ($pddTaggedTPDDebates as $pddTaggedTPDDebate) {
            $this->addPddTaggedTPDDebate($pddTaggedTPDDebate);
        }

        $this->collPddTaggedTPDDebates = $pddTaggedTPDDebates;
        $this->collPddTaggedTPDDebatesPartial = false;

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
    public function countPddTaggedTPDDebates(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collPddTaggedTPDDebatesPartial && !$this->isNew();
        if (null === $this->collPddTaggedTPDDebates || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPddTaggedTPDDebates) {
                return 0;
            }

            if($partial && !$criteria) {
                return count($this->getPddTaggedTPDDebates());
            }
            $query = PDDTaggedTQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPddTaggedTPDDebate($this)
                ->count($con);
        }

        return count($this->collPddTaggedTPDDebates);
    }

    /**
     * Method called to associate a PDDTaggedT object to this object
     * through the PDDTaggedT foreign key attribute.
     *
     * @param    PDDTaggedT $l PDDTaggedT
     * @return PDDebate The current object (for fluent API support)
     */
    public function addPddTaggedTPDDebate(PDDTaggedT $l)
    {
        if ($this->collPddTaggedTPDDebates === null) {
            $this->initPddTaggedTPDDebates();
            $this->collPddTaggedTPDDebatesPartial = true;
        }
        if (!in_array($l, $this->collPddTaggedTPDDebates->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddPddTaggedTPDDebate($l);
        }

        return $this;
    }

    /**
     * @param	PddTaggedTPDDebate $pddTaggedTPDDebate The pddTaggedTPDDebate object to add.
     */
    protected function doAddPddTaggedTPDDebate($pddTaggedTPDDebate)
    {
        $this->collPddTaggedTPDDebates[]= $pddTaggedTPDDebate;
        $pddTaggedTPDDebate->setPddTaggedTPDDebate($this);
    }

    /**
     * @param	PddTaggedTPDDebate $pddTaggedTPDDebate The pddTaggedTPDDebate object to remove.
     * @return PDDebate The current object (for fluent API support)
     */
    public function removePddTaggedTPDDebate($pddTaggedTPDDebate)
    {
        if ($this->getPddTaggedTPDDebates()->contains($pddTaggedTPDDebate)) {
            $this->collPddTaggedTPDDebates->remove($this->collPddTaggedTPDDebates->search($pddTaggedTPDDebate));
            if (null === $this->pddTaggedTPDDebatesScheduledForDeletion) {
                $this->pddTaggedTPDDebatesScheduledForDeletion = clone $this->collPddTaggedTPDDebates;
                $this->pddTaggedTPDDebatesScheduledForDeletion->clear();
            }
            $this->pddTaggedTPDDebatesScheduledForDeletion[]= clone $pddTaggedTPDDebate;
            $pddTaggedTPDDebate->setPddTaggedTPDDebate(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this PDDebate is new, it will return
     * an empty collection; or if this PDDebate has previously
     * been saved, it will retrieve related PddTaggedTPDDebates from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in PDDebate.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|PDDTaggedT[] List of PDDTaggedT objects
     */
    public function getPddTaggedTPDDebatesJoinPddTaggedTPTag($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PDDTaggedTQuery::create(null, $criteria);
        $query->joinWith('PddTaggedTPTag', $join_behavior);

        return $this->getPddTaggedTPDDebates($query, $con);
    }

    /**
     * Clears out the collPuFollowDdPUsers collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return PDDebate The current object (for fluent API support)
     * @see        addPuFollowDdPUsers()
     */
    public function clearPuFollowDdPUsers()
    {
        $this->collPuFollowDdPUsers = null; // important to set this to null since that means it is uninitialized
        $this->collPuFollowDdPUsersPartial = null;

        return $this;
    }

    /**
     * Initializes the collPuFollowDdPUsers collection.
     *
     * By default this just sets the collPuFollowDdPUsers collection to an empty collection (like clearPuFollowDdPUsers());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @return void
     */
    public function initPuFollowDdPUsers()
    {
        $this->collPuFollowDdPUsers = new PropelObjectCollection();
        $this->collPuFollowDdPUsers->setModel('PUser');
    }

    /**
     * Gets a collection of PUser objects related by a many-to-many relationship
     * to the current object by way of the p_u_follow_d_d cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this PDDebate is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria Optional query object to filter the query
     * @param PropelPDO $con Optional connection object
     *
     * @return PropelObjectCollection|PUser[] List of PUser objects
     */
    public function getPuFollowDdPUsers($criteria = null, PropelPDO $con = null)
    {
        if (null === $this->collPuFollowDdPUsers || null !== $criteria) {
            if ($this->isNew() && null === $this->collPuFollowDdPUsers) {
                // return empty collection
                $this->initPuFollowDdPUsers();
            } else {
                $collPuFollowDdPUsers = PUserQuery::create(null, $criteria)
                    ->filterByPuFollowDdPDDebate($this)
                    ->find($con);
                if (null !== $criteria) {
                    return $collPuFollowDdPUsers;
                }
                $this->collPuFollowDdPUsers = $collPuFollowDdPUsers;
            }
        }

        return $this->collPuFollowDdPUsers;
    }

    /**
     * Sets a collection of PUser objects related by a many-to-many relationship
     * to the current object by way of the p_u_follow_d_d cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $puFollowDdPUsers A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return PDDebate The current object (for fluent API support)
     */
    public function setPuFollowDdPUsers(PropelCollection $puFollowDdPUsers, PropelPDO $con = null)
    {
        $this->clearPuFollowDdPUsers();
        $currentPuFollowDdPUsers = $this->getPuFollowDdPUsers();

        $this->puFollowDdPUsersScheduledForDeletion = $currentPuFollowDdPUsers->diff($puFollowDdPUsers);

        foreach ($puFollowDdPUsers as $puFollowDdPUser) {
            if (!$currentPuFollowDdPUsers->contains($puFollowDdPUser)) {
                $this->doAddPuFollowDdPUser($puFollowDdPUser);
            }
        }

        $this->collPuFollowDdPUsers = $puFollowDdPUsers;

        return $this;
    }

    /**
     * Gets the number of PUser objects related by a many-to-many relationship
     * to the current object by way of the p_u_follow_d_d cross-reference table.
     *
     * @param Criteria $criteria Optional query object to filter the query
     * @param boolean $distinct Set to true to force count distinct
     * @param PropelPDO $con Optional connection object
     *
     * @return int the number of related PUser objects
     */
    public function countPuFollowDdPUsers($criteria = null, $distinct = false, PropelPDO $con = null)
    {
        if (null === $this->collPuFollowDdPUsers || null !== $criteria) {
            if ($this->isNew() && null === $this->collPuFollowDdPUsers) {
                return 0;
            } else {
                $query = PUserQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByPuFollowDdPDDebate($this)
                    ->count($con);
            }
        } else {
            return count($this->collPuFollowDdPUsers);
        }
    }

    /**
     * Associate a PUser object to this object
     * through the p_u_follow_d_d cross reference table.
     *
     * @param  PUser $pUser The PUFollowDD object to relate
     * @return PDDebate The current object (for fluent API support)
     */
    public function addPuFollowDdPUser(PUser $pUser)
    {
        if ($this->collPuFollowDdPUsers === null) {
            $this->initPuFollowDdPUsers();
        }
        if (!$this->collPuFollowDdPUsers->contains($pUser)) { // only add it if the **same** object is not already associated
            $this->doAddPuFollowDdPUser($pUser);

            $this->collPuFollowDdPUsers[]= $pUser;
        }

        return $this;
    }

    /**
     * @param	PuFollowDdPUser $puFollowDdPUser The puFollowDdPUser object to add.
     */
    protected function doAddPuFollowDdPUser($puFollowDdPUser)
    {
        $pUFollowDD = new PUFollowDD();
        $pUFollowDD->setPuFollowDdPUser($puFollowDdPUser);
        $this->addPuFollowDdPDDebate($pUFollowDD);
    }

    /**
     * Remove a PUser object to this object
     * through the p_u_follow_d_d cross reference table.
     *
     * @param PUser $pUser The PUFollowDD object to relate
     * @return PDDebate The current object (for fluent API support)
     */
    public function removePuFollowDdPUser(PUser $pUser)
    {
        if ($this->getPuFollowDdPUsers()->contains($pUser)) {
            $this->collPuFollowDdPUsers->remove($this->collPuFollowDdPUsers->search($pUser));
            if (null === $this->puFollowDdPUsersScheduledForDeletion) {
                $this->puFollowDdPUsersScheduledForDeletion = clone $this->collPuFollowDdPUsers;
                $this->puFollowDdPUsersScheduledForDeletion->clear();
            }
            $this->puFollowDdPUsersScheduledForDeletion[]= $pUser;
        }

        return $this;
    }

    /**
     * Clears out the collPddTaggedTPTags collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return PDDebate The current object (for fluent API support)
     * @see        addPddTaggedTPTags()
     */
    public function clearPddTaggedTPTags()
    {
        $this->collPddTaggedTPTags = null; // important to set this to null since that means it is uninitialized
        $this->collPddTaggedTPTagsPartial = null;

        return $this;
    }

    /**
     * Initializes the collPddTaggedTPTags collection.
     *
     * By default this just sets the collPddTaggedTPTags collection to an empty collection (like clearPddTaggedTPTags());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @return void
     */
    public function initPddTaggedTPTags()
    {
        $this->collPddTaggedTPTags = new PropelObjectCollection();
        $this->collPddTaggedTPTags->setModel('PTag');
    }

    /**
     * Gets a collection of PTag objects related by a many-to-many relationship
     * to the current object by way of the p_d_d_tagged_t cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this PDDebate is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria Optional query object to filter the query
     * @param PropelPDO $con Optional connection object
     *
     * @return PropelObjectCollection|PTag[] List of PTag objects
     */
    public function getPddTaggedTPTags($criteria = null, PropelPDO $con = null)
    {
        if (null === $this->collPddTaggedTPTags || null !== $criteria) {
            if ($this->isNew() && null === $this->collPddTaggedTPTags) {
                // return empty collection
                $this->initPddTaggedTPTags();
            } else {
                $collPddTaggedTPTags = PTagQuery::create(null, $criteria)
                    ->filterByPddTaggedTPDDebate($this)
                    ->find($con);
                if (null !== $criteria) {
                    return $collPddTaggedTPTags;
                }
                $this->collPddTaggedTPTags = $collPddTaggedTPTags;
            }
        }

        return $this->collPddTaggedTPTags;
    }

    /**
     * Sets a collection of PTag objects related by a many-to-many relationship
     * to the current object by way of the p_d_d_tagged_t cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $pddTaggedTPTags A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return PDDebate The current object (for fluent API support)
     */
    public function setPddTaggedTPTags(PropelCollection $pddTaggedTPTags, PropelPDO $con = null)
    {
        $this->clearPddTaggedTPTags();
        $currentPddTaggedTPTags = $this->getPddTaggedTPTags();

        $this->pddTaggedTPTagsScheduledForDeletion = $currentPddTaggedTPTags->diff($pddTaggedTPTags);

        foreach ($pddTaggedTPTags as $pddTaggedTPTag) {
            if (!$currentPddTaggedTPTags->contains($pddTaggedTPTag)) {
                $this->doAddPddTaggedTPTag($pddTaggedTPTag);
            }
        }

        $this->collPddTaggedTPTags = $pddTaggedTPTags;

        return $this;
    }

    /**
     * Gets the number of PTag objects related by a many-to-many relationship
     * to the current object by way of the p_d_d_tagged_t cross-reference table.
     *
     * @param Criteria $criteria Optional query object to filter the query
     * @param boolean $distinct Set to true to force count distinct
     * @param PropelPDO $con Optional connection object
     *
     * @return int the number of related PTag objects
     */
    public function countPddTaggedTPTags($criteria = null, $distinct = false, PropelPDO $con = null)
    {
        if (null === $this->collPddTaggedTPTags || null !== $criteria) {
            if ($this->isNew() && null === $this->collPddTaggedTPTags) {
                return 0;
            } else {
                $query = PTagQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByPddTaggedTPDDebate($this)
                    ->count($con);
            }
        } else {
            return count($this->collPddTaggedTPTags);
        }
    }

    /**
     * Associate a PTag object to this object
     * through the p_d_d_tagged_t cross reference table.
     *
     * @param  PTag $pTag The PDDTaggedT object to relate
     * @return PDDebate The current object (for fluent API support)
     */
    public function addPddTaggedTPTag(PTag $pTag)
    {
        if ($this->collPddTaggedTPTags === null) {
            $this->initPddTaggedTPTags();
        }
        if (!$this->collPddTaggedTPTags->contains($pTag)) { // only add it if the **same** object is not already associated
            $this->doAddPddTaggedTPTag($pTag);

            $this->collPddTaggedTPTags[]= $pTag;
        }

        return $this;
    }

    /**
     * @param	PddTaggedTPTag $pddTaggedTPTag The pddTaggedTPTag object to add.
     */
    protected function doAddPddTaggedTPTag($pddTaggedTPTag)
    {
        $pDDTaggedT = new PDDTaggedT();
        $pDDTaggedT->setPddTaggedTPTag($pddTaggedTPTag);
        $this->addPddTaggedTPDDebate($pDDTaggedT);
    }

    /**
     * Remove a PTag object to this object
     * through the p_d_d_tagged_t cross reference table.
     *
     * @param PTag $pTag The PDDTaggedT object to relate
     * @return PDDebate The current object (for fluent API support)
     */
    public function removePddTaggedTPTag(PTag $pTag)
    {
        if ($this->getPddTaggedTPTags()->contains($pTag)) {
            $this->collPddTaggedTPTags->remove($this->collPddTaggedTPTags->search($pTag));
            if (null === $this->pddTaggedTPTagsScheduledForDeletion) {
                $this->pddTaggedTPTagsScheduledForDeletion = clone $this->collPddTaggedTPTags;
                $this->pddTaggedTPTagsScheduledForDeletion->clear();
            }
            $this->pddTaggedTPTagsScheduledForDeletion[]= $pTag;
        }

        return $this;
    }

    /**
     * Clears the current object and sets all attributes to their default values
     */
    public function clear()
    {
        $this->id = null;
        $this->file_name = null;
        $this->p_document_id = null;
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
            if ($this->collPuFollowDdPDDebates) {
                foreach ($this->collPuFollowDdPDDebates as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPDReactions) {
                foreach ($this->collPDReactions as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPddTaggedTPDDebates) {
                foreach ($this->collPddTaggedTPDDebates as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPuFollowDdPUsers) {
                foreach ($this->collPuFollowDdPUsers as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPddTaggedTPTags) {
                foreach ($this->collPddTaggedTPTags as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->aPDocument instanceof Persistent) {
              $this->aPDocument->clearAllReferences($deep);
            }

            $this->alreadyInClearAllReferencesDeep = false;
        } // if ($deep)

        if ($this->collPuFollowDdPDDebates instanceof PropelCollection) {
            $this->collPuFollowDdPDDebates->clearIterator();
        }
        $this->collPuFollowDdPDDebates = null;
        if ($this->collPDReactions instanceof PropelCollection) {
            $this->collPDReactions->clearIterator();
        }
        $this->collPDReactions = null;
        if ($this->collPddTaggedTPDDebates instanceof PropelCollection) {
            $this->collPddTaggedTPDDebates->clearIterator();
        }
        $this->collPddTaggedTPDDebates = null;
        if ($this->collPuFollowDdPUsers instanceof PropelCollection) {
            $this->collPuFollowDdPUsers->clearIterator();
        }
        $this->collPuFollowDdPUsers = null;
        if ($this->collPddTaggedTPTags instanceof PropelCollection) {
            $this->collPddTaggedTPTags->clearIterator();
        }
        $this->collPddTaggedTPTags = null;
        $this->aPDocument = null;
    }

    /**
     * return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(PDDebatePeer::DEFAULT_STRING_FORMAT);
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
     * @return     PDDebate The current object (for fluent API support)
     */
    public function keepUpdateDateUnchanged()
    {
        $this->modifiedColumns[] = PDDebatePeer::UPDATED_AT;

        return $this;
    }

    // archivable behavior

    /**
     * Get an archived version of the current object.
     *
     * @param PropelPDO $con Optional connection object
     *
     * @return     PDDebateArchive An archive object, or null if the current object was never archived
     */
    public function getArchive(PropelPDO $con = null)
    {
        if ($this->isNew()) {
            return null;
        }
        $archive = PDDebateArchiveQuery::create()
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
     * @return     PDDebateArchive The archive object based on this object
     */
    public function archive(PropelPDO $con = null)
    {
        if ($this->isNew()) {
            throw new PropelException('New objects cannot be archived. You must save the current object before calling archive().');
        }
        if (!$archive = $this->getArchive($con)) {
            $archive = new PDDebateArchive();
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
     * @return PDDebate The current object (for fluent API support)
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
     * @param      PDDebateArchive $archive An archived object based on the same class
      * @param      Boolean $populateAutoIncrementPrimaryKeys
     *               If true, autoincrement columns are copied from the archive object.
     *               If false, autoincrement columns are left intact.
      *
     * @return     PDDebate The current object (for fluent API support)
     */
    public function populateFromArchive($archive, $populateAutoIncrementPrimaryKeys = false) {
        if ($populateAutoIncrementPrimaryKeys) {
            $this->setId($archive->getId());
        }
        $this->setFileName($archive->getFileName());
        $this->setPDocumentId($archive->getPDocumentId());
        $this->setCreatedAt($archive->getCreatedAt());
        $this->setUpdatedAt($archive->getUpdatedAt());

        return $this;
    }

    /**
     * Removes the object from the database without archiving it.
     *
     * @param PropelPDO $con Optional connection object
     *
     * @return     PDDebate The current object (for fluent API support)
     */
    public function deleteWithoutArchive(PropelPDO $con = null)
    {
        $this->archiveOnDelete = false;

        return $this->delete($con);
    }

    /**
     * Catches calls to virtual methods
     */
    public function __call($name, $params)
    {

        // delegate behavior

        if (is_callable(array('Politizr\Model\PDocument', $name))) {
            if (!$delegate = $this->getPDocument()) {
                $delegate = new PDocument();
                $this->setPDocument($delegate);
            }

            return call_user_func_array(array($delegate, $name), $params);
        }

        return parent::__call($name, $params);
    }

}
