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
use Glorpen\Propel\PropelBundle\Dispatcher\EventDispatcherProxy;
use Glorpen\Propel\PropelBundle\Events\ModelEvent;
use Politizr\Model\PDDComment;
use Politizr\Model\PDDCommentArchive;
use Politizr\Model\PDDCommentArchiveQuery;
use Politizr\Model\PDDCommentPeer;
use Politizr\Model\PDDCommentQuery;
use Politizr\Model\PDDebate;
use Politizr\Model\PDDebateQuery;
use Politizr\Model\PMDCommentHistoric;
use Politizr\Model\PMDCommentHistoricQuery;
use Politizr\Model\PUser;
use Politizr\Model\PUserQuery;

abstract class BasePDDComment extends BaseObject implements Persistent
{
    /**
     * Peer class name
     */
    const PEER = 'Politizr\\Model\\PDDCommentPeer';

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        PDDCommentPeer
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
     * The value for the p_d_debate_id field.
     * @var        int
     */
    protected $p_d_debate_id;

    /**
     * The value for the description field.
     * @var        string
     */
    protected $description;

    /**
     * The value for the paragraph_no field.
     * @var        int
     */
    protected $paragraph_no;

    /**
     * The value for the note_pos field.
     * Note: this column has a database default value of: 0
     * @var        int
     */
    protected $note_pos;

    /**
     * The value for the note_neg field.
     * Note: this column has a database default value of: 0
     * @var        int
     */
    protected $note_neg;

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
     * The value for the moderated field.
     * @var        boolean
     */
    protected $moderated;

    /**
     * The value for the moderated_partial field.
     * @var        boolean
     */
    protected $moderated_partial;

    /**
     * The value for the moderated_at field.
     * @var        string
     */
    protected $moderated_at;

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
     * @var        PUser
     */
    protected $aPUser;

    /**
     * @var        PDDebate
     */
    protected $aPDDebate;

    /**
     * @var        PropelObjectCollection|PMDCommentHistoric[] Collection to store aggregation of PMDCommentHistoric objects.
     */
    protected $collPMDCommentHistorics;
    protected $collPMDCommentHistoricsPartial;

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
    protected $pMDCommentHistoricsScheduledForDeletion = null;

    /**
     * Applies default values to this object.
     * This method should be called from the object's constructor (or
     * equivalent initialization method).
     * @see        __construct()
     */
    public function applyDefaultValues()
    {
        $this->note_pos = 0;
        $this->note_neg = 0;
    }

    /**
     * Initializes internal state of BasePDDComment object.
     * @see        applyDefaults()
     */
    public function __construct()
    {
        parent::__construct();
        $this->applyDefaultValues();
        EventDispatcherProxy::trigger(array('construct','model.construct'), new ModelEvent($this));
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
     * Get the [p_d_debate_id] column value.
     *
     * @return int
     */
    public function getPDDebateId()
    {

        return $this->p_d_debate_id;
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
     * Get the [paragraph_no] column value.
     *
     * @return int
     */
    public function getParagraphNo()
    {

        return $this->paragraph_no;
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
     * Get the [moderated] column value.
     *
     * @return boolean
     */
    public function getModerated()
    {

        return $this->moderated;
    }

    /**
     * Get the [moderated_partial] column value.
     *
     * @return boolean
     */
    public function getModeratedPartial()
    {

        return $this->moderated_partial;
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
     * @return PDDComment The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[] = PDDCommentPeer::ID;
        }


        return $this;
    } // setId()

    /**
     * Set the value of [uuid] column.
     *
     * @param  string $v new value
     * @return PDDComment The current object (for fluent API support)
     */
    public function setUuid($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->uuid !== $v) {
            $this->uuid = $v;
            $this->modifiedColumns[] = PDDCommentPeer::UUID;
        }


        return $this;
    } // setUuid()

    /**
     * Set the value of [p_user_id] column.
     *
     * @param  int $v new value
     * @return PDDComment The current object (for fluent API support)
     */
    public function setPUserId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->p_user_id !== $v) {
            $this->p_user_id = $v;
            $this->modifiedColumns[] = PDDCommentPeer::P_USER_ID;
        }

        if ($this->aPUser !== null && $this->aPUser->getId() !== $v) {
            $this->aPUser = null;
        }


        return $this;
    } // setPUserId()

    /**
     * Set the value of [p_d_debate_id] column.
     *
     * @param  int $v new value
     * @return PDDComment The current object (for fluent API support)
     */
    public function setPDDebateId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->p_d_debate_id !== $v) {
            $this->p_d_debate_id = $v;
            $this->modifiedColumns[] = PDDCommentPeer::P_D_DEBATE_ID;
        }

        if ($this->aPDDebate !== null && $this->aPDDebate->getId() !== $v) {
            $this->aPDDebate = null;
        }


        return $this;
    } // setPDDebateId()

    /**
     * Set the value of [description] column.
     *
     * @param  string $v new value
     * @return PDDComment The current object (for fluent API support)
     */
    public function setDescription($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->description !== $v) {
            $this->description = $v;
            $this->modifiedColumns[] = PDDCommentPeer::DESCRIPTION;
        }


        return $this;
    } // setDescription()

    /**
     * Set the value of [paragraph_no] column.
     *
     * @param  int $v new value
     * @return PDDComment The current object (for fluent API support)
     */
    public function setParagraphNo($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->paragraph_no !== $v) {
            $this->paragraph_no = $v;
            $this->modifiedColumns[] = PDDCommentPeer::PARAGRAPH_NO;
        }


        return $this;
    } // setParagraphNo()

    /**
     * Set the value of [note_pos] column.
     *
     * @param  int $v new value
     * @return PDDComment The current object (for fluent API support)
     */
    public function setNotePos($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->note_pos !== $v) {
            $this->note_pos = $v;
            $this->modifiedColumns[] = PDDCommentPeer::NOTE_POS;
        }


        return $this;
    } // setNotePos()

    /**
     * Set the value of [note_neg] column.
     *
     * @param  int $v new value
     * @return PDDComment The current object (for fluent API support)
     */
    public function setNoteNeg($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->note_neg !== $v) {
            $this->note_neg = $v;
            $this->modifiedColumns[] = PDDCommentPeer::NOTE_NEG;
        }


        return $this;
    } // setNoteNeg()

    /**
     * Sets the value of [published_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return PDDComment The current object (for fluent API support)
     */
    public function setPublishedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->published_at !== null || $dt !== null) {
            $currentDateAsString = ($this->published_at !== null && $tmpDt = new DateTime($this->published_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->published_at = $newDateAsString;
                $this->modifiedColumns[] = PDDCommentPeer::PUBLISHED_AT;
            }
        } // if either are not null


        return $this;
    } // setPublishedAt()

    /**
     * Set the value of [published_by] column.
     *
     * @param  string $v new value
     * @return PDDComment The current object (for fluent API support)
     */
    public function setPublishedBy($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->published_by !== $v) {
            $this->published_by = $v;
            $this->modifiedColumns[] = PDDCommentPeer::PUBLISHED_BY;
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
     * @return PDDComment The current object (for fluent API support)
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
            $this->modifiedColumns[] = PDDCommentPeer::ONLINE;
        }


        return $this;
    } // setOnline()

    /**
     * Sets the value of the [moderated] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param boolean|integer|string $v The new value
     * @return PDDComment The current object (for fluent API support)
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
            $this->modifiedColumns[] = PDDCommentPeer::MODERATED;
        }


        return $this;
    } // setModerated()

    /**
     * Sets the value of the [moderated_partial] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param boolean|integer|string $v The new value
     * @return PDDComment The current object (for fluent API support)
     */
    public function setModeratedPartial($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->moderated_partial !== $v) {
            $this->moderated_partial = $v;
            $this->modifiedColumns[] = PDDCommentPeer::MODERATED_PARTIAL;
        }


        return $this;
    } // setModeratedPartial()

    /**
     * Sets the value of [moderated_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return PDDComment The current object (for fluent API support)
     */
    public function setModeratedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->moderated_at !== null || $dt !== null) {
            $currentDateAsString = ($this->moderated_at !== null && $tmpDt = new DateTime($this->moderated_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->moderated_at = $newDateAsString;
                $this->modifiedColumns[] = PDDCommentPeer::MODERATED_AT;
            }
        } // if either are not null


        return $this;
    } // setModeratedAt()

    /**
     * Sets the value of [created_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return PDDComment The current object (for fluent API support)
     */
    public function setCreatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->created_at !== null || $dt !== null) {
            $currentDateAsString = ($this->created_at !== null && $tmpDt = new DateTime($this->created_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->created_at = $newDateAsString;
                $this->modifiedColumns[] = PDDCommentPeer::CREATED_AT;
            }
        } // if either are not null


        return $this;
    } // setCreatedAt()

    /**
     * Sets the value of [updated_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return PDDComment The current object (for fluent API support)
     */
    public function setUpdatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->updated_at !== null || $dt !== null) {
            $currentDateAsString = ($this->updated_at !== null && $tmpDt = new DateTime($this->updated_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->updated_at = $newDateAsString;
                $this->modifiedColumns[] = PDDCommentPeer::UPDATED_AT;
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
            if ($this->note_pos !== 0) {
                return false;
            }

            if ($this->note_neg !== 0) {
                return false;
            }

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
            $this->p_d_debate_id = ($row[$startcol + 3] !== null) ? (int) $row[$startcol + 3] : null;
            $this->description = ($row[$startcol + 4] !== null) ? (string) $row[$startcol + 4] : null;
            $this->paragraph_no = ($row[$startcol + 5] !== null) ? (int) $row[$startcol + 5] : null;
            $this->note_pos = ($row[$startcol + 6] !== null) ? (int) $row[$startcol + 6] : null;
            $this->note_neg = ($row[$startcol + 7] !== null) ? (int) $row[$startcol + 7] : null;
            $this->published_at = ($row[$startcol + 8] !== null) ? (string) $row[$startcol + 8] : null;
            $this->published_by = ($row[$startcol + 9] !== null) ? (string) $row[$startcol + 9] : null;
            $this->online = ($row[$startcol + 10] !== null) ? (boolean) $row[$startcol + 10] : null;
            $this->moderated = ($row[$startcol + 11] !== null) ? (boolean) $row[$startcol + 11] : null;
            $this->moderated_partial = ($row[$startcol + 12] !== null) ? (boolean) $row[$startcol + 12] : null;
            $this->moderated_at = ($row[$startcol + 13] !== null) ? (string) $row[$startcol + 13] : null;
            $this->created_at = ($row[$startcol + 14] !== null) ? (string) $row[$startcol + 14] : null;
            $this->updated_at = ($row[$startcol + 15] !== null) ? (string) $row[$startcol + 15] : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }
            $this->postHydrate($row, $startcol, $rehydrate);

            return $startcol + 16; // 16 = PDDCommentPeer::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating PDDComment object", $e);
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
        if ($this->aPDDebate !== null && $this->p_d_debate_id !== $this->aPDDebate->getId()) {
            $this->aPDDebate = null;
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
            $con = Propel::getConnection(PDDCommentPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $stmt = PDDCommentPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
        $row = $stmt->fetch(PDO::FETCH_NUM);
        $stmt->closeCursor();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aPUser = null;
            $this->aPDDebate = null;
            $this->collPMDCommentHistorics = null;

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
            $con = Propel::getConnection(PDDCommentPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        try {
            EventDispatcherProxy::trigger(array('delete.pre','model.delete.pre'), new ModelEvent($this));
            $deleteQuery = PDDCommentQuery::create()
                ->filterByPrimaryKey($this->getPrimaryKey());
            $ret = $this->preDelete($con);
            // archivable behavior
            if ($ret) {
                if ($this->archiveOnDelete) {
                    // do nothing yet. The object will be archived later when calling PDDCommentQuery::delete().
                } else {
                    $deleteQuery->setArchiveOnDelete(false);
                    $this->archiveOnDelete = true;
                }
            }

            if ($ret) {
                $deleteQuery->delete($con);
                $this->postDelete($con);
                // event behavior
                EventDispatcherProxy::trigger(array('delete.post', 'model.delete.post'), new ModelEvent($this));
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
            $con = Propel::getConnection(PDDCommentPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        $isInsert = $this->isNew();
        try {
            $ret = $this->preSave($con);
            // event behavior
            EventDispatcherProxy::trigger('model.save.pre', new ModelEvent($this));
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
                // timestampable behavior
                if (!$this->isColumnModified(PDDCommentPeer::CREATED_AT)) {
                    $this->setCreatedAt(time());
                }
                if (!$this->isColumnModified(PDDCommentPeer::UPDATED_AT)) {
                    $this->setUpdatedAt(time());
                }
                // event behavior
                EventDispatcherProxy::trigger('model.insert.pre', new ModelEvent($this));
            } else {
                $ret = $ret && $this->preUpdate($con);
                // timestampable behavior
                if ($this->isModified() && !$this->isColumnModified(PDDCommentPeer::UPDATED_AT)) {
                    $this->setUpdatedAt(time());
                }
                // event behavior
                EventDispatcherProxy::trigger(array('update.pre', 'model.update.pre'), new ModelEvent($this));
            }
            if ($ret) {
                $affectedRows = $this->doSave($con);
                if ($isInsert) {
                    $this->postInsert($con);
                    // event behavior
                    EventDispatcherProxy::trigger('model.insert.post', new ModelEvent($this));
                } else {
                    $this->postUpdate($con);
                    // event behavior
                    EventDispatcherProxy::trigger(array('update.post', 'model.update.post'), new ModelEvent($this));
                }
                $this->postSave($con);
                // event behavior
                EventDispatcherProxy::trigger('model.save.post', new ModelEvent($this));
                PDDCommentPeer::addInstanceToPool($this);
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

            if ($this->aPDDebate !== null) {
                if ($this->aPDDebate->isModified() || $this->aPDDebate->isNew()) {
                    $affectedRows += $this->aPDDebate->save($con);
                }
                $this->setPDDebate($this->aPDDebate);
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

            if ($this->pMDCommentHistoricsScheduledForDeletion !== null) {
                if (!$this->pMDCommentHistoricsScheduledForDeletion->isEmpty()) {
                    foreach ($this->pMDCommentHistoricsScheduledForDeletion as $pMDCommentHistoric) {
                        // need to save related object because we set the relation to null
                        $pMDCommentHistoric->save($con);
                    }
                    $this->pMDCommentHistoricsScheduledForDeletion = null;
                }
            }

            if ($this->collPMDCommentHistorics !== null) {
                foreach ($this->collPMDCommentHistorics as $referrerFK) {
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

        $this->modifiedColumns[] = PDDCommentPeer::ID;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . PDDCommentPeer::ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(PDDCommentPeer::ID)) {
            $modifiedColumns[':p' . $index++]  = '`id`';
        }
        if ($this->isColumnModified(PDDCommentPeer::UUID)) {
            $modifiedColumns[':p' . $index++]  = '`uuid`';
        }
        if ($this->isColumnModified(PDDCommentPeer::P_USER_ID)) {
            $modifiedColumns[':p' . $index++]  = '`p_user_id`';
        }
        if ($this->isColumnModified(PDDCommentPeer::P_D_DEBATE_ID)) {
            $modifiedColumns[':p' . $index++]  = '`p_d_debate_id`';
        }
        if ($this->isColumnModified(PDDCommentPeer::DESCRIPTION)) {
            $modifiedColumns[':p' . $index++]  = '`description`';
        }
        if ($this->isColumnModified(PDDCommentPeer::PARAGRAPH_NO)) {
            $modifiedColumns[':p' . $index++]  = '`paragraph_no`';
        }
        if ($this->isColumnModified(PDDCommentPeer::NOTE_POS)) {
            $modifiedColumns[':p' . $index++]  = '`note_pos`';
        }
        if ($this->isColumnModified(PDDCommentPeer::NOTE_NEG)) {
            $modifiedColumns[':p' . $index++]  = '`note_neg`';
        }
        if ($this->isColumnModified(PDDCommentPeer::PUBLISHED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`published_at`';
        }
        if ($this->isColumnModified(PDDCommentPeer::PUBLISHED_BY)) {
            $modifiedColumns[':p' . $index++]  = '`published_by`';
        }
        if ($this->isColumnModified(PDDCommentPeer::ONLINE)) {
            $modifiedColumns[':p' . $index++]  = '`online`';
        }
        if ($this->isColumnModified(PDDCommentPeer::MODERATED)) {
            $modifiedColumns[':p' . $index++]  = '`moderated`';
        }
        if ($this->isColumnModified(PDDCommentPeer::MODERATED_PARTIAL)) {
            $modifiedColumns[':p' . $index++]  = '`moderated_partial`';
        }
        if ($this->isColumnModified(PDDCommentPeer::MODERATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`moderated_at`';
        }
        if ($this->isColumnModified(PDDCommentPeer::CREATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`created_at`';
        }
        if ($this->isColumnModified(PDDCommentPeer::UPDATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`updated_at`';
        }

        $sql = sprintf(
            'INSERT INTO `p_d_d_comment` (%s) VALUES (%s)',
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
                    case '`p_d_debate_id`':
                        $stmt->bindValue($identifier, $this->p_d_debate_id, PDO::PARAM_INT);
                        break;
                    case '`description`':
                        $stmt->bindValue($identifier, $this->description, PDO::PARAM_STR);
                        break;
                    case '`paragraph_no`':
                        $stmt->bindValue($identifier, $this->paragraph_no, PDO::PARAM_INT);
                        break;
                    case '`note_pos`':
                        $stmt->bindValue($identifier, $this->note_pos, PDO::PARAM_INT);
                        break;
                    case '`note_neg`':
                        $stmt->bindValue($identifier, $this->note_neg, PDO::PARAM_INT);
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
                    case '`moderated`':
                        $stmt->bindValue($identifier, (int) $this->moderated, PDO::PARAM_INT);
                        break;
                    case '`moderated_partial`':
                        $stmt->bindValue($identifier, (int) $this->moderated_partial, PDO::PARAM_INT);
                        break;
                    case '`moderated_at`':
                        $stmt->bindValue($identifier, $this->moderated_at, PDO::PARAM_STR);
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
     * an aggregated array of ValidationFailed objects will be returned.
     *
     * @param array $columns Array of column names to validate.
     * @return mixed <code>true</code> if all validations pass; array of <code>ValidationFailed</code> objects otherwise.
     */
    protected function doValidate($columns = null)
    {
        if (!$this->alreadyInValidation) {
            $this->alreadyInValidation = true;
            $retval = null;

            $failureMap = array();


            // We call the validate method on the following object(s) if they
            // were passed to this object by their corresponding set
            // method.  This object relates to these object(s) by a
            // foreign key reference.

            if ($this->aPUser !== null) {
                if (!$this->aPUser->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aPUser->getValidationFailures());
                }
            }

            if ($this->aPDDebate !== null) {
                if (!$this->aPDDebate->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aPDDebate->getValidationFailures());
                }
            }


            if (($retval = PDDCommentPeer::doValidate($this, $columns)) !== true) {
                $failureMap = array_merge($failureMap, $retval);
            }


                if ($this->collPMDCommentHistorics !== null) {
                    foreach ($this->collPMDCommentHistorics as $referrerFK) {
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
        $pos = PDDCommentPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                return $this->getPDDebateId();
                break;
            case 4:
                return $this->getDescription();
                break;
            case 5:
                return $this->getParagraphNo();
                break;
            case 6:
                return $this->getNotePos();
                break;
            case 7:
                return $this->getNoteNeg();
                break;
            case 8:
                return $this->getPublishedAt();
                break;
            case 9:
                return $this->getPublishedBy();
                break;
            case 10:
                return $this->getOnline();
                break;
            case 11:
                return $this->getModerated();
                break;
            case 12:
                return $this->getModeratedPartial();
                break;
            case 13:
                return $this->getModeratedAt();
                break;
            case 14:
                return $this->getCreatedAt();
                break;
            case 15:
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
        if (isset($alreadyDumpedObjects['PDDComment'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['PDDComment'][$this->getPrimaryKey()] = true;
        $keys = PDDCommentPeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getUuid(),
            $keys[2] => $this->getPUserId(),
            $keys[3] => $this->getPDDebateId(),
            $keys[4] => $this->getDescription(),
            $keys[5] => $this->getParagraphNo(),
            $keys[6] => $this->getNotePos(),
            $keys[7] => $this->getNoteNeg(),
            $keys[8] => $this->getPublishedAt(),
            $keys[9] => $this->getPublishedBy(),
            $keys[10] => $this->getOnline(),
            $keys[11] => $this->getModerated(),
            $keys[12] => $this->getModeratedPartial(),
            $keys[13] => $this->getModeratedAt(),
            $keys[14] => $this->getCreatedAt(),
            $keys[15] => $this->getUpdatedAt(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aPUser) {
                $result['PUser'] = $this->aPUser->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aPDDebate) {
                $result['PDDebate'] = $this->aPDDebate->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->collPMDCommentHistorics) {
                $result['PMDCommentHistorics'] = $this->collPMDCommentHistorics->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
        $pos = PDDCommentPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);

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
                $this->setPDDebateId($value);
                break;
            case 4:
                $this->setDescription($value);
                break;
            case 5:
                $this->setParagraphNo($value);
                break;
            case 6:
                $this->setNotePos($value);
                break;
            case 7:
                $this->setNoteNeg($value);
                break;
            case 8:
                $this->setPublishedAt($value);
                break;
            case 9:
                $this->setPublishedBy($value);
                break;
            case 10:
                $this->setOnline($value);
                break;
            case 11:
                $this->setModerated($value);
                break;
            case 12:
                $this->setModeratedPartial($value);
                break;
            case 13:
                $this->setModeratedAt($value);
                break;
            case 14:
                $this->setCreatedAt($value);
                break;
            case 15:
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
        $keys = PDDCommentPeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setUuid($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setPUserId($arr[$keys[2]]);
        if (array_key_exists($keys[3], $arr)) $this->setPDDebateId($arr[$keys[3]]);
        if (array_key_exists($keys[4], $arr)) $this->setDescription($arr[$keys[4]]);
        if (array_key_exists($keys[5], $arr)) $this->setParagraphNo($arr[$keys[5]]);
        if (array_key_exists($keys[6], $arr)) $this->setNotePos($arr[$keys[6]]);
        if (array_key_exists($keys[7], $arr)) $this->setNoteNeg($arr[$keys[7]]);
        if (array_key_exists($keys[8], $arr)) $this->setPublishedAt($arr[$keys[8]]);
        if (array_key_exists($keys[9], $arr)) $this->setPublishedBy($arr[$keys[9]]);
        if (array_key_exists($keys[10], $arr)) $this->setOnline($arr[$keys[10]]);
        if (array_key_exists($keys[11], $arr)) $this->setModerated($arr[$keys[11]]);
        if (array_key_exists($keys[12], $arr)) $this->setModeratedPartial($arr[$keys[12]]);
        if (array_key_exists($keys[13], $arr)) $this->setModeratedAt($arr[$keys[13]]);
        if (array_key_exists($keys[14], $arr)) $this->setCreatedAt($arr[$keys[14]]);
        if (array_key_exists($keys[15], $arr)) $this->setUpdatedAt($arr[$keys[15]]);
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(PDDCommentPeer::DATABASE_NAME);

        if ($this->isColumnModified(PDDCommentPeer::ID)) $criteria->add(PDDCommentPeer::ID, $this->id);
        if ($this->isColumnModified(PDDCommentPeer::UUID)) $criteria->add(PDDCommentPeer::UUID, $this->uuid);
        if ($this->isColumnModified(PDDCommentPeer::P_USER_ID)) $criteria->add(PDDCommentPeer::P_USER_ID, $this->p_user_id);
        if ($this->isColumnModified(PDDCommentPeer::P_D_DEBATE_ID)) $criteria->add(PDDCommentPeer::P_D_DEBATE_ID, $this->p_d_debate_id);
        if ($this->isColumnModified(PDDCommentPeer::DESCRIPTION)) $criteria->add(PDDCommentPeer::DESCRIPTION, $this->description);
        if ($this->isColumnModified(PDDCommentPeer::PARAGRAPH_NO)) $criteria->add(PDDCommentPeer::PARAGRAPH_NO, $this->paragraph_no);
        if ($this->isColumnModified(PDDCommentPeer::NOTE_POS)) $criteria->add(PDDCommentPeer::NOTE_POS, $this->note_pos);
        if ($this->isColumnModified(PDDCommentPeer::NOTE_NEG)) $criteria->add(PDDCommentPeer::NOTE_NEG, $this->note_neg);
        if ($this->isColumnModified(PDDCommentPeer::PUBLISHED_AT)) $criteria->add(PDDCommentPeer::PUBLISHED_AT, $this->published_at);
        if ($this->isColumnModified(PDDCommentPeer::PUBLISHED_BY)) $criteria->add(PDDCommentPeer::PUBLISHED_BY, $this->published_by);
        if ($this->isColumnModified(PDDCommentPeer::ONLINE)) $criteria->add(PDDCommentPeer::ONLINE, $this->online);
        if ($this->isColumnModified(PDDCommentPeer::MODERATED)) $criteria->add(PDDCommentPeer::MODERATED, $this->moderated);
        if ($this->isColumnModified(PDDCommentPeer::MODERATED_PARTIAL)) $criteria->add(PDDCommentPeer::MODERATED_PARTIAL, $this->moderated_partial);
        if ($this->isColumnModified(PDDCommentPeer::MODERATED_AT)) $criteria->add(PDDCommentPeer::MODERATED_AT, $this->moderated_at);
        if ($this->isColumnModified(PDDCommentPeer::CREATED_AT)) $criteria->add(PDDCommentPeer::CREATED_AT, $this->created_at);
        if ($this->isColumnModified(PDDCommentPeer::UPDATED_AT)) $criteria->add(PDDCommentPeer::UPDATED_AT, $this->updated_at);

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
        $criteria = new Criteria(PDDCommentPeer::DATABASE_NAME);
        $criteria->add(PDDCommentPeer::ID, $this->id);

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
     * @param object $copyObj An object of PDDComment (or compatible) type.
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setUuid($this->getUuid());
        $copyObj->setPUserId($this->getPUserId());
        $copyObj->setPDDebateId($this->getPDDebateId());
        $copyObj->setDescription($this->getDescription());
        $copyObj->setParagraphNo($this->getParagraphNo());
        $copyObj->setNotePos($this->getNotePos());
        $copyObj->setNoteNeg($this->getNoteNeg());
        $copyObj->setPublishedAt($this->getPublishedAt());
        $copyObj->setPublishedBy($this->getPublishedBy());
        $copyObj->setOnline($this->getOnline());
        $copyObj->setModerated($this->getModerated());
        $copyObj->setModeratedPartial($this->getModeratedPartial());
        $copyObj->setModeratedAt($this->getModeratedAt());
        $copyObj->setCreatedAt($this->getCreatedAt());
        $copyObj->setUpdatedAt($this->getUpdatedAt());

        if ($deepCopy && !$this->startCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);
            // store object hash to prevent cycle
            $this->startCopy = true;

            foreach ($this->getPMDCommentHistorics() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPMDCommentHistoric($relObj->copy($deepCopy));
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
     * @return PDDComment Clone of current object.
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
     * @return PDDCommentPeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new PDDCommentPeer();
        }

        return self::$peer;
    }

    /**
     * Declares an association between this object and a PUser object.
     *
     * @param                  PUser $v
     * @return PDDComment The current object (for fluent API support)
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
            $v->addPDDComment($this);
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
                $this->aPUser->addPDDComments($this);
             */
        }

        return $this->aPUser;
    }

    /**
     * Declares an association between this object and a PDDebate object.
     *
     * @param                  PDDebate $v
     * @return PDDComment The current object (for fluent API support)
     * @throws PropelException
     */
    public function setPDDebate(PDDebate $v = null)
    {
        if ($v === null) {
            $this->setPDDebateId(NULL);
        } else {
            $this->setPDDebateId($v->getId());
        }

        $this->aPDDebate = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the PDDebate object, it will not be re-added.
        if ($v !== null) {
            $v->addPDDComment($this);
        }


        return $this;
    }


    /**
     * Get the associated PDDebate object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return PDDebate The associated PDDebate object.
     * @throws PropelException
     */
    public function getPDDebate(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aPDDebate === null && ($this->p_d_debate_id !== null) && $doQuery) {
            $this->aPDDebate = PDDebateQuery::create()->findPk($this->p_d_debate_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aPDDebate->addPDDComments($this);
             */
        }

        return $this->aPDDebate;
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
        if ('PMDCommentHistoric' == $relationName) {
            $this->initPMDCommentHistorics();
        }
    }

    /**
     * Clears out the collPMDCommentHistorics collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return PDDComment The current object (for fluent API support)
     * @see        addPMDCommentHistorics()
     */
    public function clearPMDCommentHistorics()
    {
        $this->collPMDCommentHistorics = null; // important to set this to null since that means it is uninitialized
        $this->collPMDCommentHistoricsPartial = null;

        return $this;
    }

    /**
     * reset is the collPMDCommentHistorics collection loaded partially
     *
     * @return void
     */
    public function resetPartialPMDCommentHistorics($v = true)
    {
        $this->collPMDCommentHistoricsPartial = $v;
    }

    /**
     * Initializes the collPMDCommentHistorics collection.
     *
     * By default this just sets the collPMDCommentHistorics collection to an empty array (like clearcollPMDCommentHistorics());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPMDCommentHistorics($overrideExisting = true)
    {
        if (null !== $this->collPMDCommentHistorics && !$overrideExisting) {
            return;
        }
        $this->collPMDCommentHistorics = new PropelObjectCollection();
        $this->collPMDCommentHistorics->setModel('PMDCommentHistoric');
    }

    /**
     * Gets an array of PMDCommentHistoric objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this PDDComment is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|PMDCommentHistoric[] List of PMDCommentHistoric objects
     * @throws PropelException
     */
    public function getPMDCommentHistorics($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collPMDCommentHistoricsPartial && !$this->isNew();
        if (null === $this->collPMDCommentHistorics || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPMDCommentHistorics) {
                // return empty collection
                $this->initPMDCommentHistorics();
            } else {
                $collPMDCommentHistorics = PMDCommentHistoricQuery::create(null, $criteria)
                    ->filterByPDDComment($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collPMDCommentHistoricsPartial && count($collPMDCommentHistorics)) {
                      $this->initPMDCommentHistorics(false);

                      foreach ($collPMDCommentHistorics as $obj) {
                        if (false == $this->collPMDCommentHistorics->contains($obj)) {
                          $this->collPMDCommentHistorics->append($obj);
                        }
                      }

                      $this->collPMDCommentHistoricsPartial = true;
                    }

                    $collPMDCommentHistorics->getInternalIterator()->rewind();

                    return $collPMDCommentHistorics;
                }

                if ($partial && $this->collPMDCommentHistorics) {
                    foreach ($this->collPMDCommentHistorics as $obj) {
                        if ($obj->isNew()) {
                            $collPMDCommentHistorics[] = $obj;
                        }
                    }
                }

                $this->collPMDCommentHistorics = $collPMDCommentHistorics;
                $this->collPMDCommentHistoricsPartial = false;
            }
        }

        return $this->collPMDCommentHistorics;
    }

    /**
     * Sets a collection of PMDCommentHistoric objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $pMDCommentHistorics A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return PDDComment The current object (for fluent API support)
     */
    public function setPMDCommentHistorics(PropelCollection $pMDCommentHistorics, PropelPDO $con = null)
    {
        $pMDCommentHistoricsToDelete = $this->getPMDCommentHistorics(new Criteria(), $con)->diff($pMDCommentHistorics);


        $this->pMDCommentHistoricsScheduledForDeletion = $pMDCommentHistoricsToDelete;

        foreach ($pMDCommentHistoricsToDelete as $pMDCommentHistoricRemoved) {
            $pMDCommentHistoricRemoved->setPDDComment(null);
        }

        $this->collPMDCommentHistorics = null;
        foreach ($pMDCommentHistorics as $pMDCommentHistoric) {
            $this->addPMDCommentHistoric($pMDCommentHistoric);
        }

        $this->collPMDCommentHistorics = $pMDCommentHistorics;
        $this->collPMDCommentHistoricsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related PMDCommentHistoric objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related PMDCommentHistoric objects.
     * @throws PropelException
     */
    public function countPMDCommentHistorics(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collPMDCommentHistoricsPartial && !$this->isNew();
        if (null === $this->collPMDCommentHistorics || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPMDCommentHistorics) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPMDCommentHistorics());
            }
            $query = PMDCommentHistoricQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPDDComment($this)
                ->count($con);
        }

        return count($this->collPMDCommentHistorics);
    }

    /**
     * Method called to associate a PMDCommentHistoric object to this object
     * through the PMDCommentHistoric foreign key attribute.
     *
     * @param    PMDCommentHistoric $l PMDCommentHistoric
     * @return PDDComment The current object (for fluent API support)
     */
    public function addPMDCommentHistoric(PMDCommentHistoric $l)
    {
        if ($this->collPMDCommentHistorics === null) {
            $this->initPMDCommentHistorics();
            $this->collPMDCommentHistoricsPartial = true;
        }

        if (!in_array($l, $this->collPMDCommentHistorics->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddPMDCommentHistoric($l);

            if ($this->pMDCommentHistoricsScheduledForDeletion and $this->pMDCommentHistoricsScheduledForDeletion->contains($l)) {
                $this->pMDCommentHistoricsScheduledForDeletion->remove($this->pMDCommentHistoricsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	PMDCommentHistoric $pMDCommentHistoric The pMDCommentHistoric object to add.
     */
    protected function doAddPMDCommentHistoric($pMDCommentHistoric)
    {
        $this->collPMDCommentHistorics[]= $pMDCommentHistoric;
        $pMDCommentHistoric->setPDDComment($this);
    }

    /**
     * @param	PMDCommentHistoric $pMDCommentHistoric The pMDCommentHistoric object to remove.
     * @return PDDComment The current object (for fluent API support)
     */
    public function removePMDCommentHistoric($pMDCommentHistoric)
    {
        if ($this->getPMDCommentHistorics()->contains($pMDCommentHistoric)) {
            $this->collPMDCommentHistorics->remove($this->collPMDCommentHistorics->search($pMDCommentHistoric));
            if (null === $this->pMDCommentHistoricsScheduledForDeletion) {
                $this->pMDCommentHistoricsScheduledForDeletion = clone $this->collPMDCommentHistorics;
                $this->pMDCommentHistoricsScheduledForDeletion->clear();
            }
            $this->pMDCommentHistoricsScheduledForDeletion[]= $pMDCommentHistoric;
            $pMDCommentHistoric->setPDDComment(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this PDDComment is new, it will return
     * an empty collection; or if this PDDComment has previously
     * been saved, it will retrieve related PMDCommentHistorics from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in PDDComment.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|PMDCommentHistoric[] List of PMDCommentHistoric objects
     */
    public function getPMDCommentHistoricsJoinPUser($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PMDCommentHistoricQuery::create(null, $criteria);
        $query->joinWith('PUser', $join_behavior);

        return $this->getPMDCommentHistorics($query, $con);
    }

    /**
     * Clears the current object and sets all attributes to their default values
     */
    public function clear()
    {
        $this->id = null;
        $this->uuid = null;
        $this->p_user_id = null;
        $this->p_d_debate_id = null;
        $this->description = null;
        $this->paragraph_no = null;
        $this->note_pos = null;
        $this->note_neg = null;
        $this->published_at = null;
        $this->published_by = null;
        $this->online = null;
        $this->moderated = null;
        $this->moderated_partial = null;
        $this->moderated_at = null;
        $this->created_at = null;
        $this->updated_at = null;
        $this->alreadyInSave = false;
        $this->alreadyInValidation = false;
        $this->alreadyInClearAllReferencesDeep = false;
        $this->clearAllReferences();
        $this->applyDefaultValues();
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
            if ($this->collPMDCommentHistorics) {
                foreach ($this->collPMDCommentHistorics as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->aPUser instanceof Persistent) {
              $this->aPUser->clearAllReferences($deep);
            }
            if ($this->aPDDebate instanceof Persistent) {
              $this->aPDDebate->clearAllReferences($deep);
            }

            $this->alreadyInClearAllReferencesDeep = false;
        } // if ($deep)

        if ($this->collPMDCommentHistorics instanceof PropelCollection) {
            $this->collPMDCommentHistorics->clearIterator();
        }
        $this->collPMDCommentHistorics = null;
        $this->aPUser = null;
        $this->aPDDebate = null;
    }

    /**
     * return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(PDDCommentPeer::DEFAULT_STRING_FORMAT);
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
     * @return     PDDComment The current object (for fluent API support)
     */
    public function keepUpdateDateUnchanged()
    {
        $this->modifiedColumns[] = PDDCommentPeer::UPDATED_AT;

        return $this;
    }

    // archivable behavior

    /**
     * Get an archived version of the current object.
     *
     * @param PropelPDO $con Optional connection object
     *
     * @return     PDDCommentArchive An archive object, or null if the current object was never archived
     */
    public function getArchive(PropelPDO $con = null)
    {
        if ($this->isNew()) {
            return null;
        }
        $archive = PDDCommentArchiveQuery::create()
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
     * @return     PDDCommentArchive The archive object based on this object
     */
    public function archive(PropelPDO $con = null)
    {
        if ($this->isNew()) {
            throw new PropelException('New objects cannot be archived. You must save the current object before calling archive().');
        }
        if (!$archive = $this->getArchive($con)) {
            $archive = new PDDCommentArchive();
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
     * @return PDDComment The current object (for fluent API support)
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
     * @param      PDDCommentArchive $archive An archived object based on the same class
      * @param      Boolean $populateAutoIncrementPrimaryKeys
     *               If true, autoincrement columns are copied from the archive object.
     *               If false, autoincrement columns are left intact.
      *
     * @return     PDDComment The current object (for fluent API support)
     */
    public function populateFromArchive($archive, $populateAutoIncrementPrimaryKeys = false) {
        if ($populateAutoIncrementPrimaryKeys) {
            $this->setId($archive->getId());
        }
        $this->setUuid($archive->getUuid());
        $this->setPUserId($archive->getPUserId());
        $this->setPDDebateId($archive->getPDDebateId());
        $this->setDescription($archive->getDescription());
        $this->setParagraphNo($archive->getParagraphNo());
        $this->setNotePos($archive->getNotePos());
        $this->setNoteNeg($archive->getNoteNeg());
        $this->setPublishedAt($archive->getPublishedAt());
        $this->setPublishedBy($archive->getPublishedBy());
        $this->setOnline($archive->getOnline());
        $this->setModerated($archive->getModerated());
        $this->setModeratedPartial($archive->getModeratedPartial());
        $this->setModeratedAt($archive->getModeratedAt());
        $this->setCreatedAt($archive->getCreatedAt());
        $this->setUpdatedAt($archive->getUpdatedAt());

        return $this;
    }

    /**
     * Removes the object from the database without archiving it.
     *
     * @param PropelPDO $con Optional connection object
     *
     * @return     PDDComment The current object (for fluent API support)
     */
    public function deleteWithoutArchive(PropelPDO $con = null)
    {
        $this->archiveOnDelete = false;

        return $this->delete($con);
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
    * If permanent UUID, throw exception p_d_d_comment.uuid*/
    public function preUpdate(PropelPDO $con = NULL) {
            $uuid = $this->getUuid();
        if(!is_null($uuid) && !\Ramsey\Uuid\Uuid::isValid($uuid)) {
            throw new \InvalidArgumentException("UUID: $uuid in not valid");
        }
            return true;
    }

    // event behavior
    public function preCommit(\PropelPDO $con = null){}
    public function preCommitSave(\PropelPDO $con = null){}
    public function preCommitDelete(\PropelPDO $con = null){}
    public function preCommitUpdate(\PropelPDO $con = null){}
    public function preCommitInsert(\PropelPDO $con = null){}
    public function preRollback(\PropelPDO $con = null){}
    public function preRollbackSave(\PropelPDO $con = null){}
    public function preRollbackDelete(\PropelPDO $con = null){}
    public function preRollbackUpdate(\PropelPDO $con = null){}
    public function preRollbackInsert(\PropelPDO $con = null){}

}
