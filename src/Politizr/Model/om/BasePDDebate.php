<?php

namespace Politizr\Model\om;

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

abstract class BasePDDebate extends PDocument implements Persistent
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
     * The flag var to prevent infinite loop in deep copy
     * @var       boolean
     */
    protected $startCopy = false;

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
     * The value for the file_name field.
     * @var        string
     */
    protected $file_name;

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
     * The value for the nb_views field.
     * @var        int
     */
    protected $nb_views;

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
     * The value for the favorite field.
     * @var        boolean
     */
    protected $favorite;

    /**
     * The value for the online field.
     * @var        boolean
     */
    protected $online;

    /**
     * @var        PDocument
     */
    protected $aPDocument;

    /**
     * @var        PUser
     */
    protected $aPUser;

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
    protected $collPDDTaggedTs;
    protected $collPDDTaggedTsPartial;

    /**
     * @var        PropelObjectCollection|PUser[] Collection to store aggregation of PUser objects.
     */
    protected $collPuFollowDdPUsers;

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
    protected $puFollowDdPUsersScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $pTagsScheduledForDeletion = null;

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
    protected $pDDTaggedTsScheduledForDeletion = null;

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
     * Initializes internal state of BasePDDebate object.
     * @see        applyDefaults()
     */
    public function __construct()
    {
        parent::__construct();
        $this->applyDefaultValues();
        EventDispatcherProxy::trigger(array('construct','model.construct'), new ModelEvent($this));
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
     * Get the [file_name] column value.
     *
     * @return string
     */
    public function getFileName()
    {

        return $this->file_name;
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
     * Get the [nb_views] column value.
     *
     * @return int
     */
    public function getNbViews()
    {

        return $this->nb_views;
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
     * Get the [favorite] column value.
     *
     * @return boolean
     */
    public function getFavorite()
    {

        return $this->favorite;
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
     * Set the value of [slug] column.
     *
     * @param  string $v new value
     * @return PDDebate The current object (for fluent API support)
     */
    public function setSlug($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->slug !== $v) {
            $this->slug = $v;
            $this->modifiedColumns[] = PDDebatePeer::SLUG;
        }


        return $this;
    } // setSlug()

    /**
     * Set the value of [id] column.
     *
     * @param  int $v new value
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

        if ($this->aPDocument !== null && $this->aPDocument->getId() !== $v) {
            $this->aPDocument = null;
        }


        return $this;
    } // setId()

    /**
     * Set the value of [p_user_id] column.
     *
     * @param  int $v new value
     * @return PDDebate The current object (for fluent API support)
     */
    public function setPUserId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->p_user_id !== $v) {
            $this->p_user_id = $v;
            $this->modifiedColumns[] = PDDebatePeer::P_USER_ID;
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
     * @return PDDebate The current object (for fluent API support)
     */
    public function setTitle($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->title !== $v) {
            $this->title = $v;
            $this->modifiedColumns[] = PDDebatePeer::TITLE;
        }


        return $this;
    } // setTitle()

    /**
     * Set the value of [file_name] column.
     *
     * @param  string $v new value
     * @return PDDebate The current object (for fluent API support)
     */
    public function setFileName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->file_name !== $v) {
            $this->file_name = $v;
            $this->modifiedColumns[] = PDDebatePeer::FILE_NAME;
        }


        return $this;
    } // setFileName()

    /**
     * Set the value of [summary] column.
     *
     * @param  string $v new value
     * @return PDDebate The current object (for fluent API support)
     */
    public function setSummary($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->summary !== $v) {
            $this->summary = $v;
            $this->modifiedColumns[] = PDDebatePeer::SUMMARY;
        }


        return $this;
    } // setSummary()

    /**
     * Set the value of [description] column.
     *
     * @param  string $v new value
     * @return PDDebate The current object (for fluent API support)
     */
    public function setDescription($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->description !== $v) {
            $this->description = $v;
            $this->modifiedColumns[] = PDDebatePeer::DESCRIPTION;
        }


        return $this;
    } // setDescription()

    /**
     * Set the value of [note_pos] column.
     *
     * @param  int $v new value
     * @return PDDebate The current object (for fluent API support)
     */
    public function setNotePos($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->note_pos !== $v) {
            $this->note_pos = $v;
            $this->modifiedColumns[] = PDDebatePeer::NOTE_POS;
        }


        return $this;
    } // setNotePos()

    /**
     * Set the value of [note_neg] column.
     *
     * @param  int $v new value
     * @return PDDebate The current object (for fluent API support)
     */
    public function setNoteNeg($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->note_neg !== $v) {
            $this->note_neg = $v;
            $this->modifiedColumns[] = PDDebatePeer::NOTE_NEG;
        }


        return $this;
    } // setNoteNeg()

    /**
     * Set the value of [nb_views] column.
     *
     * @param  int $v new value
     * @return PDDebate The current object (for fluent API support)
     */
    public function setNbViews($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->nb_views !== $v) {
            $this->nb_views = $v;
            $this->modifiedColumns[] = PDDebatePeer::NB_VIEWS;
        }


        return $this;
    } // setNbViews()

    /**
     * Sets the value of the [published] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param boolean|integer|string $v The new value
     * @return PDDebate The current object (for fluent API support)
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
            $this->modifiedColumns[] = PDDebatePeer::PUBLISHED;
        }


        return $this;
    } // setPublished()

    /**
     * Sets the value of [published_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return PDDebate The current object (for fluent API support)
     */
    public function setPublishedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->published_at !== null || $dt !== null) {
            $currentDateAsString = ($this->published_at !== null && $tmpDt = new DateTime($this->published_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->published_at = $newDateAsString;
                $this->modifiedColumns[] = PDDebatePeer::PUBLISHED_AT;
            }
        } // if either are not null


        return $this;
    } // setPublishedAt()

    /**
     * Set the value of [published_by] column.
     *
     * @param  string $v new value
     * @return PDDebate The current object (for fluent API support)
     */
    public function setPublishedBy($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->published_by !== $v) {
            $this->published_by = $v;
            $this->modifiedColumns[] = PDDebatePeer::PUBLISHED_BY;
        }


        return $this;
    } // setPublishedBy()

    /**
     * Sets the value of the [favorite] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param boolean|integer|string $v The new value
     * @return PDDebate The current object (for fluent API support)
     */
    public function setFavorite($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->favorite !== $v) {
            $this->favorite = $v;
            $this->modifiedColumns[] = PDDebatePeer::FAVORITE;
        }


        return $this;
    } // setFavorite()

    /**
     * Sets the value of the [online] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param boolean|integer|string $v The new value
     * @return PDDebate The current object (for fluent API support)
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
            $this->modifiedColumns[] = PDDebatePeer::ONLINE;
        }


        return $this;
    } // setOnline()

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

            $this->created_at = ($row[$startcol + 0] !== null) ? (string) $row[$startcol + 0] : null;
            $this->updated_at = ($row[$startcol + 1] !== null) ? (string) $row[$startcol + 1] : null;
            $this->slug = ($row[$startcol + 2] !== null) ? (string) $row[$startcol + 2] : null;
            $this->id = ($row[$startcol + 3] !== null) ? (int) $row[$startcol + 3] : null;
            $this->p_user_id = ($row[$startcol + 4] !== null) ? (int) $row[$startcol + 4] : null;
            $this->title = ($row[$startcol + 5] !== null) ? (string) $row[$startcol + 5] : null;
            $this->file_name = ($row[$startcol + 6] !== null) ? (string) $row[$startcol + 6] : null;
            $this->summary = ($row[$startcol + 7] !== null) ? (string) $row[$startcol + 7] : null;
            $this->description = ($row[$startcol + 8] !== null) ? (string) $row[$startcol + 8] : null;
            $this->note_pos = ($row[$startcol + 9] !== null) ? (int) $row[$startcol + 9] : null;
            $this->note_neg = ($row[$startcol + 10] !== null) ? (int) $row[$startcol + 10] : null;
            $this->nb_views = ($row[$startcol + 11] !== null) ? (int) $row[$startcol + 11] : null;
            $this->published = ($row[$startcol + 12] !== null) ? (boolean) $row[$startcol + 12] : null;
            $this->published_at = ($row[$startcol + 13] !== null) ? (string) $row[$startcol + 13] : null;
            $this->published_by = ($row[$startcol + 14] !== null) ? (string) $row[$startcol + 14] : null;
            $this->favorite = ($row[$startcol + 15] !== null) ? (boolean) $row[$startcol + 15] : null;
            $this->online = ($row[$startcol + 16] !== null) ? (boolean) $row[$startcol + 16] : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }
            $this->postHydrate($row, $startcol, $rehydrate);

            return $startcol + 17; // 17 = PDDebatePeer::NUM_HYDRATE_COLUMNS.

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

        if ($this->aPDocument !== null && $this->id !== $this->aPDocument->getId()) {
            $this->aPDocument = null;
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
            $this->aPUser = null;
            $this->collPuFollowDdPDDebates = null;

            $this->collPDReactions = null;

            $this->collPDDTaggedTs = null;

            $this->collPuFollowDdPUsers = null;
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
            $con = Propel::getConnection(PDDebatePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        try {
            EventDispatcherProxy::trigger(array('delete.pre','model.delete.pre'), new ModelEvent($this));
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
                // concrete_inheritance behavior
                $this->getParentOrCreate($con)->delete($con);

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
            $con = Propel::getConnection(PDDebatePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        $isInsert = $this->isNew();
        try {
            $ret = $this->preSave($con);
            // sluggable behavior

            if ($this->isColumnModified(PDDebatePeer::SLUG) && $this->getSlug()) {
                $this->setSlug($this->makeSlugUnique($this->getSlug()));
            } elseif ($this->isColumnModified(PDDebatePeer::TITLE)) {
                $this->setSlug($this->createSlug());
            } elseif (!$this->getSlug()) {
                $this->setSlug($this->createSlug());
            }
            // concrete_inheritance behavior
            $parent = $this->getSyncParent($con);
            $parent->save($con);
            $this->setPrimaryKey($parent->getPrimaryKey());

            // event behavior
            EventDispatcherProxy::trigger('model.save.pre', new ModelEvent($this));
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
                // timestampable behavior
                if (!$this->isColumnModified(PDDebatePeer::CREATED_AT)) {
                    $this->setCreatedAt(time());
                }
                if (!$this->isColumnModified(PDDebatePeer::UPDATED_AT)) {
                    $this->setUpdatedAt(time());
                }
                // event behavior
                EventDispatcherProxy::trigger('model.insert.pre', new ModelEvent($this));
            } else {
                $ret = $ret && $this->preUpdate($con);
                // timestampable behavior
                if ($this->isModified() && !$this->isColumnModified(PDDebatePeer::UPDATED_AT)) {
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
            // were passed to this object by their corresponding set
            // method.  This object relates to these object(s) by a
            // foreign key reference.

            if ($this->aPDocument !== null) {
                if ($this->aPDocument->isModified() || $this->aPDocument->isNew()) {
                    $affectedRows += $this->aPDocument->save($con);
                }
                $this->setPDocument($this->aPDocument);
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

            if ($this->puFollowDdPUsersScheduledForDeletion !== null) {
                if (!$this->puFollowDdPUsersScheduledForDeletion->isEmpty()) {
                    $pks = array();
                    $pk = $this->getPrimaryKey();
                    foreach ($this->puFollowDdPUsersScheduledForDeletion->getPrimaryKeys(false) as $remotePk) {
                        $pks[] = array($remotePk, $pk);
                    }
                    PUFollowDDQuery::create()
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

            if ($this->pTagsScheduledForDeletion !== null) {
                if (!$this->pTagsScheduledForDeletion->isEmpty()) {
                    $pks = array();
                    $pk = $this->getPrimaryKey();
                    foreach ($this->pTagsScheduledForDeletion->getPrimaryKeys(false) as $remotePk) {
                        $pks[] = array($pk, $remotePk);
                    }
                    PDDTaggedTQuery::create()
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


         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(PDDebatePeer::CREATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`created_at`';
        }
        if ($this->isColumnModified(PDDebatePeer::UPDATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`updated_at`';
        }
        if ($this->isColumnModified(PDDebatePeer::SLUG)) {
            $modifiedColumns[':p' . $index++]  = '`slug`';
        }
        if ($this->isColumnModified(PDDebatePeer::ID)) {
            $modifiedColumns[':p' . $index++]  = '`id`';
        }
        if ($this->isColumnModified(PDDebatePeer::P_USER_ID)) {
            $modifiedColumns[':p' . $index++]  = '`p_user_id`';
        }
        if ($this->isColumnModified(PDDebatePeer::TITLE)) {
            $modifiedColumns[':p' . $index++]  = '`title`';
        }
        if ($this->isColumnModified(PDDebatePeer::FILE_NAME)) {
            $modifiedColumns[':p' . $index++]  = '`file_name`';
        }
        if ($this->isColumnModified(PDDebatePeer::SUMMARY)) {
            $modifiedColumns[':p' . $index++]  = '`summary`';
        }
        if ($this->isColumnModified(PDDebatePeer::DESCRIPTION)) {
            $modifiedColumns[':p' . $index++]  = '`description`';
        }
        if ($this->isColumnModified(PDDebatePeer::NOTE_POS)) {
            $modifiedColumns[':p' . $index++]  = '`note_pos`';
        }
        if ($this->isColumnModified(PDDebatePeer::NOTE_NEG)) {
            $modifiedColumns[':p' . $index++]  = '`note_neg`';
        }
        if ($this->isColumnModified(PDDebatePeer::NB_VIEWS)) {
            $modifiedColumns[':p' . $index++]  = '`nb_views`';
        }
        if ($this->isColumnModified(PDDebatePeer::PUBLISHED)) {
            $modifiedColumns[':p' . $index++]  = '`published`';
        }
        if ($this->isColumnModified(PDDebatePeer::PUBLISHED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`published_at`';
        }
        if ($this->isColumnModified(PDDebatePeer::PUBLISHED_BY)) {
            $modifiedColumns[':p' . $index++]  = '`published_by`';
        }
        if ($this->isColumnModified(PDDebatePeer::FAVORITE)) {
            $modifiedColumns[':p' . $index++]  = '`favorite`';
        }
        if ($this->isColumnModified(PDDebatePeer::ONLINE)) {
            $modifiedColumns[':p' . $index++]  = '`online`';
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
                    case '`created_at`':
                        $stmt->bindValue($identifier, $this->created_at, PDO::PARAM_STR);
                        break;
                    case '`updated_at`':
                        $stmt->bindValue($identifier, $this->updated_at, PDO::PARAM_STR);
                        break;
                    case '`slug`':
                        $stmt->bindValue($identifier, $this->slug, PDO::PARAM_STR);
                        break;
                    case '`id`':
                        $stmt->bindValue($identifier, $this->id, PDO::PARAM_INT);
                        break;
                    case '`p_user_id`':
                        $stmt->bindValue($identifier, $this->p_user_id, PDO::PARAM_INT);
                        break;
                    case '`title`':
                        $stmt->bindValue($identifier, $this->title, PDO::PARAM_STR);
                        break;
                    case '`file_name`':
                        $stmt->bindValue($identifier, $this->file_name, PDO::PARAM_STR);
                        break;
                    case '`summary`':
                        $stmt->bindValue($identifier, $this->summary, PDO::PARAM_STR);
                        break;
                    case '`description`':
                        $stmt->bindValue($identifier, $this->description, PDO::PARAM_STR);
                        break;
                    case '`note_pos`':
                        $stmt->bindValue($identifier, $this->note_pos, PDO::PARAM_INT);
                        break;
                    case '`note_neg`':
                        $stmt->bindValue($identifier, $this->note_neg, PDO::PARAM_INT);
                        break;
                    case '`nb_views`':
                        $stmt->bindValue($identifier, $this->nb_views, PDO::PARAM_INT);
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
                    case '`favorite`':
                        $stmt->bindValue($identifier, (int) $this->favorite, PDO::PARAM_INT);
                        break;
                    case '`online`':
                        $stmt->bindValue($identifier, (int) $this->online, PDO::PARAM_INT);
                        break;
                }
            }
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute INSERT statement [%s]', $sql), $e);
        }

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

            if ($this->aPDocument !== null) {
                if (!$this->aPDocument->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aPDocument->getValidationFailures());
                }
            }

            if ($this->aPUser !== null) {
                if (!$this->aPUser->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aPUser->getValidationFailures());
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

                if ($this->collPDDTaggedTs !== null) {
                    foreach ($this->collPDDTaggedTs as $referrerFK) {
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
                return $this->getCreatedAt();
                break;
            case 1:
                return $this->getUpdatedAt();
                break;
            case 2:
                return $this->getSlug();
                break;
            case 3:
                return $this->getId();
                break;
            case 4:
                return $this->getPUserId();
                break;
            case 5:
                return $this->getTitle();
                break;
            case 6:
                return $this->getFileName();
                break;
            case 7:
                return $this->getSummary();
                break;
            case 8:
                return $this->getDescription();
                break;
            case 9:
                return $this->getNotePos();
                break;
            case 10:
                return $this->getNoteNeg();
                break;
            case 11:
                return $this->getNbViews();
                break;
            case 12:
                return $this->getPublished();
                break;
            case 13:
                return $this->getPublishedAt();
                break;
            case 14:
                return $this->getPublishedBy();
                break;
            case 15:
                return $this->getFavorite();
                break;
            case 16:
                return $this->getOnline();
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
            $keys[0] => $this->getCreatedAt(),
            $keys[1] => $this->getUpdatedAt(),
            $keys[2] => $this->getSlug(),
            $keys[3] => $this->getId(),
            $keys[4] => $this->getPUserId(),
            $keys[5] => $this->getTitle(),
            $keys[6] => $this->getFileName(),
            $keys[7] => $this->getSummary(),
            $keys[8] => $this->getDescription(),
            $keys[9] => $this->getNotePos(),
            $keys[10] => $this->getNoteNeg(),
            $keys[11] => $this->getNbViews(),
            $keys[12] => $this->getPublished(),
            $keys[13] => $this->getPublishedAt(),
            $keys[14] => $this->getPublishedBy(),
            $keys[15] => $this->getFavorite(),
            $keys[16] => $this->getOnline(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aPDocument) {
                $result['PDocument'] = $this->aPDocument->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aPUser) {
                $result['PUser'] = $this->aPUser->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->collPuFollowDdPDDebates) {
                $result['PuFollowDdPDDebates'] = $this->collPuFollowDdPDDebates->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPDReactions) {
                $result['PDReactions'] = $this->collPDReactions->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPDDTaggedTs) {
                $result['PDDTaggedTs'] = $this->collPDDTaggedTs->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
                $this->setCreatedAt($value);
                break;
            case 1:
                $this->setUpdatedAt($value);
                break;
            case 2:
                $this->setSlug($value);
                break;
            case 3:
                $this->setId($value);
                break;
            case 4:
                $this->setPUserId($value);
                break;
            case 5:
                $this->setTitle($value);
                break;
            case 6:
                $this->setFileName($value);
                break;
            case 7:
                $this->setSummary($value);
                break;
            case 8:
                $this->setDescription($value);
                break;
            case 9:
                $this->setNotePos($value);
                break;
            case 10:
                $this->setNoteNeg($value);
                break;
            case 11:
                $this->setNbViews($value);
                break;
            case 12:
                $this->setPublished($value);
                break;
            case 13:
                $this->setPublishedAt($value);
                break;
            case 14:
                $this->setPublishedBy($value);
                break;
            case 15:
                $this->setFavorite($value);
                break;
            case 16:
                $this->setOnline($value);
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

        if (array_key_exists($keys[0], $arr)) $this->setCreatedAt($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setUpdatedAt($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setSlug($arr[$keys[2]]);
        if (array_key_exists($keys[3], $arr)) $this->setId($arr[$keys[3]]);
        if (array_key_exists($keys[4], $arr)) $this->setPUserId($arr[$keys[4]]);
        if (array_key_exists($keys[5], $arr)) $this->setTitle($arr[$keys[5]]);
        if (array_key_exists($keys[6], $arr)) $this->setFileName($arr[$keys[6]]);
        if (array_key_exists($keys[7], $arr)) $this->setSummary($arr[$keys[7]]);
        if (array_key_exists($keys[8], $arr)) $this->setDescription($arr[$keys[8]]);
        if (array_key_exists($keys[9], $arr)) $this->setNotePos($arr[$keys[9]]);
        if (array_key_exists($keys[10], $arr)) $this->setNoteNeg($arr[$keys[10]]);
        if (array_key_exists($keys[11], $arr)) $this->setNbViews($arr[$keys[11]]);
        if (array_key_exists($keys[12], $arr)) $this->setPublished($arr[$keys[12]]);
        if (array_key_exists($keys[13], $arr)) $this->setPublishedAt($arr[$keys[13]]);
        if (array_key_exists($keys[14], $arr)) $this->setPublishedBy($arr[$keys[14]]);
        if (array_key_exists($keys[15], $arr)) $this->setFavorite($arr[$keys[15]]);
        if (array_key_exists($keys[16], $arr)) $this->setOnline($arr[$keys[16]]);
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(PDDebatePeer::DATABASE_NAME);

        if ($this->isColumnModified(PDDebatePeer::CREATED_AT)) $criteria->add(PDDebatePeer::CREATED_AT, $this->created_at);
        if ($this->isColumnModified(PDDebatePeer::UPDATED_AT)) $criteria->add(PDDebatePeer::UPDATED_AT, $this->updated_at);
        if ($this->isColumnModified(PDDebatePeer::SLUG)) $criteria->add(PDDebatePeer::SLUG, $this->slug);
        if ($this->isColumnModified(PDDebatePeer::ID)) $criteria->add(PDDebatePeer::ID, $this->id);
        if ($this->isColumnModified(PDDebatePeer::P_USER_ID)) $criteria->add(PDDebatePeer::P_USER_ID, $this->p_user_id);
        if ($this->isColumnModified(PDDebatePeer::TITLE)) $criteria->add(PDDebatePeer::TITLE, $this->title);
        if ($this->isColumnModified(PDDebatePeer::FILE_NAME)) $criteria->add(PDDebatePeer::FILE_NAME, $this->file_name);
        if ($this->isColumnModified(PDDebatePeer::SUMMARY)) $criteria->add(PDDebatePeer::SUMMARY, $this->summary);
        if ($this->isColumnModified(PDDebatePeer::DESCRIPTION)) $criteria->add(PDDebatePeer::DESCRIPTION, $this->description);
        if ($this->isColumnModified(PDDebatePeer::NOTE_POS)) $criteria->add(PDDebatePeer::NOTE_POS, $this->note_pos);
        if ($this->isColumnModified(PDDebatePeer::NOTE_NEG)) $criteria->add(PDDebatePeer::NOTE_NEG, $this->note_neg);
        if ($this->isColumnModified(PDDebatePeer::NB_VIEWS)) $criteria->add(PDDebatePeer::NB_VIEWS, $this->nb_views);
        if ($this->isColumnModified(PDDebatePeer::PUBLISHED)) $criteria->add(PDDebatePeer::PUBLISHED, $this->published);
        if ($this->isColumnModified(PDDebatePeer::PUBLISHED_AT)) $criteria->add(PDDebatePeer::PUBLISHED_AT, $this->published_at);
        if ($this->isColumnModified(PDDebatePeer::PUBLISHED_BY)) $criteria->add(PDDebatePeer::PUBLISHED_BY, $this->published_by);
        if ($this->isColumnModified(PDDebatePeer::FAVORITE)) $criteria->add(PDDebatePeer::FAVORITE, $this->favorite);
        if ($this->isColumnModified(PDDebatePeer::ONLINE)) $criteria->add(PDDebatePeer::ONLINE, $this->online);

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
        $copyObj->setCreatedAt($this->getCreatedAt());
        $copyObj->setUpdatedAt($this->getUpdatedAt());
        $copyObj->setSlug($this->getSlug());
        $copyObj->setPUserId($this->getPUserId());
        $copyObj->setTitle($this->getTitle());
        $copyObj->setFileName($this->getFileName());
        $copyObj->setSummary($this->getSummary());
        $copyObj->setDescription($this->getDescription());
        $copyObj->setNotePos($this->getNotePos());
        $copyObj->setNoteNeg($this->getNoteNeg());
        $copyObj->setNbViews($this->getNbViews());
        $copyObj->setPublished($this->getPublished());
        $copyObj->setPublishedAt($this->getPublishedAt());
        $copyObj->setPublishedBy($this->getPublishedBy());
        $copyObj->setFavorite($this->getFavorite());
        $copyObj->setOnline($this->getOnline());

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

            foreach ($this->getPDDTaggedTs() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPDDTaggedT($relObj->copy($deepCopy));
                }
            }

            $relObj = $this->getPDocument();
            if ($relObj) {
                $copyObj->setPDocument($relObj->copy($deepCopy));
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
     * @param                  PDocument $v
     * @return PDDebate The current object (for fluent API support)
     * @throws PropelException
     */
    public function setPDocument(PDocument $v = null)
    {
        if ($v === null) {
            $this->setId(NULL);
        } else {
            $this->setId($v->getId());
        }

        $this->aPDocument = $v;

        // Add binding for other direction of this 1:1 relationship.
        if ($v !== null) {
            $v->setPDDebate($this);
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
        if ($this->aPDocument === null && ($this->id !== null) && $doQuery) {
            $this->aPDocument = PDocumentQuery::create()->findPk($this->id, $con);
            // Because this foreign key represents a one-to-one relationship, we will create a bi-directional association.
            $this->aPDocument->setPDDebate($this);
        }

        return $this->aPDocument;
    }

    /**
     * Declares an association between this object and a PUser object.
     *
     * @param                  PUser $v
     * @return PDDebate The current object (for fluent API support)
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
            $v->addPDDebate($this);
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
                $this->aPUser->addPDDebates($this);
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
        if ('PuFollowDdPDDebate' == $relationName) {
            $this->initPuFollowDdPDDebates();
        }
        if ('PDReaction' == $relationName) {
            $this->initPDReactions();
        }
        if ('PDDTaggedT' == $relationName) {
            $this->initPDDTaggedTs();
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

                      foreach ($collPuFollowDdPDDebates as $obj) {
                        if (false == $this->collPuFollowDdPDDebates->contains($obj)) {
                          $this->collPuFollowDdPDDebates->append($obj);
                        }
                      }

                      $this->collPuFollowDdPDDebatesPartial = true;
                    }

                    $collPuFollowDdPDDebates->getInternalIterator()->rewind();

                    return $collPuFollowDdPDDebates;
                }

                if ($partial && $this->collPuFollowDdPDDebates) {
                    foreach ($this->collPuFollowDdPDDebates as $obj) {
                        if ($obj->isNew()) {
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


        $this->puFollowDdPDDebatesScheduledForDeletion = $puFollowDdPDDebatesToDelete;

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

            if ($partial && !$criteria) {
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

            if ($this->puFollowDdPDDebatesScheduledForDeletion and $this->puFollowDdPDDebatesScheduledForDeletion->contains($l)) {
                $this->puFollowDdPDDebatesScheduledForDeletion->remove($this->puFollowDdPDDebatesScheduledForDeletion->search($l));
            }
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

                      foreach ($collPDReactions as $obj) {
                        if (false == $this->collPDReactions->contains($obj)) {
                          $this->collPDReactions->append($obj);
                        }
                      }

                      $this->collPDReactionsPartial = true;
                    }

                    $collPDReactions->getInternalIterator()->rewind();

                    return $collPDReactions;
                }

                if ($partial && $this->collPDReactions) {
                    foreach ($this->collPDReactions as $obj) {
                        if ($obj->isNew()) {
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


        $this->pDReactionsScheduledForDeletion = $pDReactionsToDelete;

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

            if ($partial && !$criteria) {
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

            if ($this->pDReactionsScheduledForDeletion and $this->pDReactionsScheduledForDeletion->contains($l)) {
                $this->pDReactionsScheduledForDeletion->remove($this->pDReactionsScheduledForDeletion->search($l));
            }
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
    public function getPDReactionsJoinPUser($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PDReactionQuery::create(null, $criteria);
        $query->joinWith('PUser', $join_behavior);

        return $this->getPDReactions($query, $con);
    }

    /**
     * Clears out the collPDDTaggedTs collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return PDDebate The current object (for fluent API support)
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
     * If this PDDebate is new, it will return
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
                    ->filterByPDDebate($this)
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
     * @return PDDebate The current object (for fluent API support)
     */
    public function setPDDTaggedTs(PropelCollection $pDDTaggedTs, PropelPDO $con = null)
    {
        $pDDTaggedTsToDelete = $this->getPDDTaggedTs(new Criteria(), $con)->diff($pDDTaggedTs);


        $this->pDDTaggedTsScheduledForDeletion = $pDDTaggedTsToDelete;

        foreach ($pDDTaggedTsToDelete as $pDDTaggedTRemoved) {
            $pDDTaggedTRemoved->setPDDebate(null);
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
                ->filterByPDDebate($this)
                ->count($con);
        }

        return count($this->collPDDTaggedTs);
    }

    /**
     * Method called to associate a PDDTaggedT object to this object
     * through the PDDTaggedT foreign key attribute.
     *
     * @param    PDDTaggedT $l PDDTaggedT
     * @return PDDebate The current object (for fluent API support)
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
        $pDDTaggedT->setPDDebate($this);
    }

    /**
     * @param	PDDTaggedT $pDDTaggedT The pDDTaggedT object to remove.
     * @return PDDebate The current object (for fluent API support)
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
            $pDDTaggedT->setPDDebate(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this PDDebate is new, it will return
     * an empty collection; or if this PDDebate has previously
     * been saved, it will retrieve related PDDTaggedTs from storage.
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
    public function getPDDTaggedTsJoinPTag($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PDDTaggedTQuery::create(null, $criteria);
        $query->joinWith('PTag', $join_behavior);

        return $this->getPDDTaggedTs($query, $con);
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
        $currentPuFollowDdPUsers = $this->getPuFollowDdPUsers(null, $con);

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
            $this->collPuFollowDdPUsers[] = $pUser;

            if ($this->puFollowDdPUsersScheduledForDeletion and $this->puFollowDdPUsersScheduledForDeletion->contains($pUser)) {
                $this->puFollowDdPUsersScheduledForDeletion->remove($this->puFollowDdPUsersScheduledForDeletion->search($pUser));
            }
        }

        return $this;
    }

    /**
     * @param	PuFollowDdPUser $puFollowDdPUser The puFollowDdPUser object to add.
     */
    protected function doAddPuFollowDdPUser(PUser $puFollowDdPUser)
    {
        // set the back reference to this object directly as using provided method either results
        // in endless loop or in multiple relations
        if (!$puFollowDdPUser->getPuFollowDdPDDebates()->contains($this)) { $pUFollowDD = new PUFollowDD();
            $pUFollowDD->setPuFollowDdPUser($puFollowDdPUser);
            $this->addPuFollowDdPDDebate($pUFollowDD);

            $foreignCollection = $puFollowDdPUser->getPuFollowDdPDDebates();
            $foreignCollection[] = $this;
        }
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
     * Clears out the collPTags collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return PDDebate The current object (for fluent API support)
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
    public function getPTags($criteria = null, PropelPDO $con = null)
    {
        if (null === $this->collPTags || null !== $criteria) {
            if ($this->isNew() && null === $this->collPTags) {
                // return empty collection
                $this->initPTags();
            } else {
                $collPTags = PTagQuery::create(null, $criteria)
                    ->filterByPDDebate($this)
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
     * to the current object by way of the p_d_d_tagged_t cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $pTags A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return PDDebate The current object (for fluent API support)
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
     * to the current object by way of the p_d_d_tagged_t cross-reference table.
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
                    ->filterByPDDebate($this)
                    ->count($con);
            }
        } else {
            return count($this->collPTags);
        }
    }

    /**
     * Associate a PTag object to this object
     * through the p_d_d_tagged_t cross reference table.
     *
     * @param  PTag $pTag The PDDTaggedT object to relate
     * @return PDDebate The current object (for fluent API support)
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
        if (!$pTag->getPDDebates()->contains($this)) { $pDDTaggedT = new PDDTaggedT();
            $pDDTaggedT->setPTag($pTag);
            $this->addPDDTaggedT($pDDTaggedT);

            $foreignCollection = $pTag->getPDDebates();
            $foreignCollection[] = $this;
        }
    }

    /**
     * Remove a PTag object to this object
     * through the p_d_d_tagged_t cross reference table.
     *
     * @param PTag $pTag The PDDTaggedT object to relate
     * @return PDDebate The current object (for fluent API support)
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
        $this->created_at = null;
        $this->updated_at = null;
        $this->slug = null;
        $this->id = null;
        $this->p_user_id = null;
        $this->title = null;
        $this->file_name = null;
        $this->summary = null;
        $this->description = null;
        $this->note_pos = null;
        $this->note_neg = null;
        $this->nb_views = null;
        $this->published = null;
        $this->published_at = null;
        $this->published_by = null;
        $this->favorite = null;
        $this->online = null;
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
            if ($this->collPDDTaggedTs) {
                foreach ($this->collPDDTaggedTs as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPuFollowDdPUsers) {
                foreach ($this->collPuFollowDdPUsers as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPTags) {
                foreach ($this->collPTags as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->aPDocument instanceof Persistent) {
              $this->aPDocument->clearAllReferences($deep);
            }
            if ($this->aPUser instanceof Persistent) {
              $this->aPUser->clearAllReferences($deep);
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
        if ($this->collPDDTaggedTs instanceof PropelCollection) {
            $this->collPDDTaggedTs->clearIterator();
        }
        $this->collPDDTaggedTs = null;
        if ($this->collPuFollowDdPUsers instanceof PropelCollection) {
            $this->collPuFollowDdPUsers->clearIterator();
        }
        $this->collPuFollowDdPUsers = null;
        if ($this->collPTags instanceof PropelCollection) {
            $this->collPTags->clearIterator();
        }
        $this->collPTags = null;
        $this->aPDocument = null;
        $this->aPUser = null;
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

         $query = PDDebateQuery::create('q')
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

    // concrete_inheritance behavior

    /**
     * Get or Create the parent PDocument object of the current object
     *
     * @return    PDocument The parent object
     */
    public function getParentOrCreate($con = null)
    {
        if ($this->isNew()) {
            if ($this->isPrimaryKeyNull()) {
                //this prevent issue with deep copy & save parent object
                if (null === ($parent = $this->getPDocument($con))) {
                    $parent = new PDocument();
                }
                $parent->setDescendantClass('Politizr\Model\PDDebate');

                return $parent;
            } else {
                $parent = PDocumentQuery::create()->findPk($this->getPrimaryKey(), $con);
                if (null === $parent || null !== $parent->getDescendantClass()) {
                    $parent = new PDocument();
                    $parent->setPrimaryKey($this->getPrimaryKey());
                    $parent->setDescendantClass('Politizr\Model\PDDebate');
                }

                return $parent;
            }
        }

        return PDocumentQuery::create()->findPk($this->getPrimaryKey(), $con);
    }

    /**
     * Create or Update the parent PDocument object
     * And return its primary key
     *
     * @return    int The primary key of the parent object
     */
    public function getSyncParent($con = null)
    {
        $parent = $this->getParentOrCreate($con);
        $parent->setPUserId($this->getPUserId());
        $parent->setTitle($this->getTitle());
        $parent->setFileName($this->getFileName());
        $parent->setSummary($this->getSummary());
        $parent->setDescription($this->getDescription());
        $parent->setNotePos($this->getNotePos());
        $parent->setNoteNeg($this->getNoteNeg());
        $parent->setNbViews($this->getNbViews());
        $parent->setPublished($this->getPublished());
        $parent->setPublishedAt($this->getPublishedAt());
        $parent->setPublishedBy($this->getPublishedBy());
        $parent->setFavorite($this->getFavorite());
        $parent->setOnline($this->getOnline());
        if ($this->getPUser() && $this->getPUser()->isNew()) {
            $parent->setPUser($this->getPUser());
        }

        return $parent;
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
      * @param      Boolean $populateAutoIncrementPrimaryKeys Not used! Defined to comply with php strict standards
      *
     * @return     PDDebate The current object (for fluent API support)
     */
    public function populateFromArchive($archive, $populateAutoIncrementPrimaryKeys = false) {
        $this->setCreatedAt($archive->getCreatedAt());
        $this->setUpdatedAt($archive->getUpdatedAt());
        $this->setSlug($archive->getSlug());
        $this->setId($archive->getId());
        $this->setPUserId($archive->getPUserId());
        $this->setTitle($archive->getTitle());
        $this->setFileName($archive->getFileName());
        $this->setSummary($archive->getSummary());
        $this->setDescription($archive->getDescription());
        $this->setNotePos($archive->getNotePos());
        $this->setNoteNeg($archive->getNoteNeg());
        $this->setNbViews($archive->getNbViews());
        $this->setPublished($archive->getPublished());
        $this->setPublishedAt($archive->getPublishedAt());
        $this->setPublishedBy($archive->getPublishedBy());
        $this->setFavorite($archive->getFavorite());
        $this->setOnline($archive->getOnline());

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

}
