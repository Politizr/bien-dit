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
use \PropelDateTime;
use \PropelException;
use \PropelPDO;
use Politizr\Model\PDDebateArchive;
use Politizr\Model\PDDebateArchivePeer;
use Politizr\Model\PDDebateArchiveQuery;

abstract class BasePDDebateArchive extends BaseObject implements Persistent
{
    /**
     * Peer class name
     */
    const PEER = 'Politizr\\Model\\PDDebateArchivePeer';

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        PDDebateArchivePeer
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
     * The value for the file_name field.
     * @var        string
     */
    protected $file_name;

    /**
     * The value for the copyright field.
     * @var        string
     */
    protected $copyright;

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
     * The value for the slug field.
     * @var        string
     */
    protected $slug;

    /**
     * The value for the archived_at field.
     * @var        string
     */
    protected $archived_at;

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
     * Initializes internal state of BasePDDebateArchive object.
     * @see        applyDefaults()
     */
    public function __construct()
    {
        parent::__construct();
        $this->applyDefaultValues();
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
     * Get the [copyright] column value.
     *
     * @return string
     */
    public function getCopyright()
    {

        return $this->copyright;
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
     * Get the [slug] column value.
     *
     * @return string
     */
    public function getSlug()
    {

        return $this->slug;
    }

    /**
     * Get the [optionally formatted] temporal [archived_at] column value.
     *
     *
     * @param string $format The date/time format string (either date()-style or strftime()-style).
     *				 If format is null, then the raw DateTime object will be returned.
     * @return mixed Formatted date/time value as string or DateTime object (if format is null), null if column is null, and 0 if column value is 0000-00-00 00:00:00
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getArchivedAt($format = null)
    {
        if ($this->archived_at === null) {
            return null;
        }

        if ($this->archived_at === '0000-00-00 00:00:00') {
            // while technically this is not a default value of null,
            // this seems to be closest in meaning.
            return null;
        }

        try {
            $dt = new DateTime($this->archived_at);
        } catch (Exception $x) {
            throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->archived_at, true), $x);
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
     * @return PDDebateArchive The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[] = PDDebateArchivePeer::ID;
        }


        return $this;
    } // setId()

    /**
     * Set the value of [uuid] column.
     *
     * @param  string $v new value
     * @return PDDebateArchive The current object (for fluent API support)
     */
    public function setUuid($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->uuid !== $v) {
            $this->uuid = $v;
            $this->modifiedColumns[] = PDDebateArchivePeer::UUID;
        }


        return $this;
    } // setUuid()

    /**
     * Set the value of [p_user_id] column.
     *
     * @param  int $v new value
     * @return PDDebateArchive The current object (for fluent API support)
     */
    public function setPUserId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->p_user_id !== $v) {
            $this->p_user_id = $v;
            $this->modifiedColumns[] = PDDebateArchivePeer::P_USER_ID;
        }


        return $this;
    } // setPUserId()

    /**
     * Set the value of [title] column.
     *
     * @param  string $v new value
     * @return PDDebateArchive The current object (for fluent API support)
     */
    public function setTitle($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->title !== $v) {
            $this->title = $v;
            $this->modifiedColumns[] = PDDebateArchivePeer::TITLE;
        }


        return $this;
    } // setTitle()

    /**
     * Set the value of [file_name] column.
     *
     * @param  string $v new value
     * @return PDDebateArchive The current object (for fluent API support)
     */
    public function setFileName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->file_name !== $v) {
            $this->file_name = $v;
            $this->modifiedColumns[] = PDDebateArchivePeer::FILE_NAME;
        }


        return $this;
    } // setFileName()

    /**
     * Set the value of [copyright] column.
     *
     * @param  string $v new value
     * @return PDDebateArchive The current object (for fluent API support)
     */
    public function setCopyright($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->copyright !== $v) {
            $this->copyright = $v;
            $this->modifiedColumns[] = PDDebateArchivePeer::COPYRIGHT;
        }


        return $this;
    } // setCopyright()

    /**
     * Set the value of [description] column.
     *
     * @param  string $v new value
     * @return PDDebateArchive The current object (for fluent API support)
     */
    public function setDescription($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->description !== $v) {
            $this->description = $v;
            $this->modifiedColumns[] = PDDebateArchivePeer::DESCRIPTION;
        }


        return $this;
    } // setDescription()

    /**
     * Set the value of [note_pos] column.
     *
     * @param  int $v new value
     * @return PDDebateArchive The current object (for fluent API support)
     */
    public function setNotePos($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->note_pos !== $v) {
            $this->note_pos = $v;
            $this->modifiedColumns[] = PDDebateArchivePeer::NOTE_POS;
        }


        return $this;
    } // setNotePos()

    /**
     * Set the value of [note_neg] column.
     *
     * @param  int $v new value
     * @return PDDebateArchive The current object (for fluent API support)
     */
    public function setNoteNeg($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->note_neg !== $v) {
            $this->note_neg = $v;
            $this->modifiedColumns[] = PDDebateArchivePeer::NOTE_NEG;
        }


        return $this;
    } // setNoteNeg()

    /**
     * Set the value of [nb_views] column.
     *
     * @param  int $v new value
     * @return PDDebateArchive The current object (for fluent API support)
     */
    public function setNbViews($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->nb_views !== $v) {
            $this->nb_views = $v;
            $this->modifiedColumns[] = PDDebateArchivePeer::NB_VIEWS;
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
     * @return PDDebateArchive The current object (for fluent API support)
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
            $this->modifiedColumns[] = PDDebateArchivePeer::PUBLISHED;
        }


        return $this;
    } // setPublished()

    /**
     * Sets the value of [published_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return PDDebateArchive The current object (for fluent API support)
     */
    public function setPublishedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->published_at !== null || $dt !== null) {
            $currentDateAsString = ($this->published_at !== null && $tmpDt = new DateTime($this->published_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->published_at = $newDateAsString;
                $this->modifiedColumns[] = PDDebateArchivePeer::PUBLISHED_AT;
            }
        } // if either are not null


        return $this;
    } // setPublishedAt()

    /**
     * Set the value of [published_by] column.
     *
     * @param  string $v new value
     * @return PDDebateArchive The current object (for fluent API support)
     */
    public function setPublishedBy($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->published_by !== $v) {
            $this->published_by = $v;
            $this->modifiedColumns[] = PDDebateArchivePeer::PUBLISHED_BY;
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
     * @return PDDebateArchive The current object (for fluent API support)
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
            $this->modifiedColumns[] = PDDebateArchivePeer::FAVORITE;
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
     * @return PDDebateArchive The current object (for fluent API support)
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
            $this->modifiedColumns[] = PDDebateArchivePeer::ONLINE;
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
     * @return PDDebateArchive The current object (for fluent API support)
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
            $this->modifiedColumns[] = PDDebateArchivePeer::MODERATED;
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
     * @return PDDebateArchive The current object (for fluent API support)
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
            $this->modifiedColumns[] = PDDebateArchivePeer::MODERATED_PARTIAL;
        }


        return $this;
    } // setModeratedPartial()

    /**
     * Sets the value of [moderated_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return PDDebateArchive The current object (for fluent API support)
     */
    public function setModeratedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->moderated_at !== null || $dt !== null) {
            $currentDateAsString = ($this->moderated_at !== null && $tmpDt = new DateTime($this->moderated_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->moderated_at = $newDateAsString;
                $this->modifiedColumns[] = PDDebateArchivePeer::MODERATED_AT;
            }
        } // if either are not null


        return $this;
    } // setModeratedAt()

    /**
     * Sets the value of [created_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return PDDebateArchive The current object (for fluent API support)
     */
    public function setCreatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->created_at !== null || $dt !== null) {
            $currentDateAsString = ($this->created_at !== null && $tmpDt = new DateTime($this->created_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->created_at = $newDateAsString;
                $this->modifiedColumns[] = PDDebateArchivePeer::CREATED_AT;
            }
        } // if either are not null


        return $this;
    } // setCreatedAt()

    /**
     * Sets the value of [updated_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return PDDebateArchive The current object (for fluent API support)
     */
    public function setUpdatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->updated_at !== null || $dt !== null) {
            $currentDateAsString = ($this->updated_at !== null && $tmpDt = new DateTime($this->updated_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->updated_at = $newDateAsString;
                $this->modifiedColumns[] = PDDebateArchivePeer::UPDATED_AT;
            }
        } // if either are not null


        return $this;
    } // setUpdatedAt()

    /**
     * Set the value of [slug] column.
     *
     * @param  string $v new value
     * @return PDDebateArchive The current object (for fluent API support)
     */
    public function setSlug($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->slug !== $v) {
            $this->slug = $v;
            $this->modifiedColumns[] = PDDebateArchivePeer::SLUG;
        }


        return $this;
    } // setSlug()

    /**
     * Sets the value of [archived_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return PDDebateArchive The current object (for fluent API support)
     */
    public function setArchivedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->archived_at !== null || $dt !== null) {
            $currentDateAsString = ($this->archived_at !== null && $tmpDt = new DateTime($this->archived_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->archived_at = $newDateAsString;
                $this->modifiedColumns[] = PDDebateArchivePeer::ARCHIVED_AT;
            }
        } // if either are not null


        return $this;
    } // setArchivedAt()

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
            $this->title = ($row[$startcol + 3] !== null) ? (string) $row[$startcol + 3] : null;
            $this->file_name = ($row[$startcol + 4] !== null) ? (string) $row[$startcol + 4] : null;
            $this->copyright = ($row[$startcol + 5] !== null) ? (string) $row[$startcol + 5] : null;
            $this->description = ($row[$startcol + 6] !== null) ? (string) $row[$startcol + 6] : null;
            $this->note_pos = ($row[$startcol + 7] !== null) ? (int) $row[$startcol + 7] : null;
            $this->note_neg = ($row[$startcol + 8] !== null) ? (int) $row[$startcol + 8] : null;
            $this->nb_views = ($row[$startcol + 9] !== null) ? (int) $row[$startcol + 9] : null;
            $this->published = ($row[$startcol + 10] !== null) ? (boolean) $row[$startcol + 10] : null;
            $this->published_at = ($row[$startcol + 11] !== null) ? (string) $row[$startcol + 11] : null;
            $this->published_by = ($row[$startcol + 12] !== null) ? (string) $row[$startcol + 12] : null;
            $this->favorite = ($row[$startcol + 13] !== null) ? (boolean) $row[$startcol + 13] : null;
            $this->online = ($row[$startcol + 14] !== null) ? (boolean) $row[$startcol + 14] : null;
            $this->moderated = ($row[$startcol + 15] !== null) ? (boolean) $row[$startcol + 15] : null;
            $this->moderated_partial = ($row[$startcol + 16] !== null) ? (boolean) $row[$startcol + 16] : null;
            $this->moderated_at = ($row[$startcol + 17] !== null) ? (string) $row[$startcol + 17] : null;
            $this->created_at = ($row[$startcol + 18] !== null) ? (string) $row[$startcol + 18] : null;
            $this->updated_at = ($row[$startcol + 19] !== null) ? (string) $row[$startcol + 19] : null;
            $this->slug = ($row[$startcol + 20] !== null) ? (string) $row[$startcol + 20] : null;
            $this->archived_at = ($row[$startcol + 21] !== null) ? (string) $row[$startcol + 21] : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }
            $this->postHydrate($row, $startcol, $rehydrate);

            return $startcol + 22; // 22 = PDDebateArchivePeer::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating PDDebateArchive object", $e);
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
            $con = Propel::getConnection(PDDebateArchivePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $stmt = PDDebateArchivePeer::doSelectStmt($this->buildPkeyCriteria(), $con);
        $row = $stmt->fetch(PDO::FETCH_NUM);
        $stmt->closeCursor();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true); // rehydrate

        if ($deep) {  // also de-associate any related objects?

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
            $con = Propel::getConnection(PDDebateArchivePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        try {
            $deleteQuery = PDDebateArchiveQuery::create()
                ->filterByPrimaryKey($this->getPrimaryKey());
            $ret = $this->preDelete($con);
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
            $con = Propel::getConnection(PDDebateArchivePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
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
                PDDebateArchivePeer::addInstanceToPool($this);
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
        if ($this->isColumnModified(PDDebateArchivePeer::ID)) {
            $modifiedColumns[':p' . $index++]  = '`id`';
        }
        if ($this->isColumnModified(PDDebateArchivePeer::UUID)) {
            $modifiedColumns[':p' . $index++]  = '`uuid`';
        }
        if ($this->isColumnModified(PDDebateArchivePeer::P_USER_ID)) {
            $modifiedColumns[':p' . $index++]  = '`p_user_id`';
        }
        if ($this->isColumnModified(PDDebateArchivePeer::TITLE)) {
            $modifiedColumns[':p' . $index++]  = '`title`';
        }
        if ($this->isColumnModified(PDDebateArchivePeer::FILE_NAME)) {
            $modifiedColumns[':p' . $index++]  = '`file_name`';
        }
        if ($this->isColumnModified(PDDebateArchivePeer::COPYRIGHT)) {
            $modifiedColumns[':p' . $index++]  = '`copyright`';
        }
        if ($this->isColumnModified(PDDebateArchivePeer::DESCRIPTION)) {
            $modifiedColumns[':p' . $index++]  = '`description`';
        }
        if ($this->isColumnModified(PDDebateArchivePeer::NOTE_POS)) {
            $modifiedColumns[':p' . $index++]  = '`note_pos`';
        }
        if ($this->isColumnModified(PDDebateArchivePeer::NOTE_NEG)) {
            $modifiedColumns[':p' . $index++]  = '`note_neg`';
        }
        if ($this->isColumnModified(PDDebateArchivePeer::NB_VIEWS)) {
            $modifiedColumns[':p' . $index++]  = '`nb_views`';
        }
        if ($this->isColumnModified(PDDebateArchivePeer::PUBLISHED)) {
            $modifiedColumns[':p' . $index++]  = '`published`';
        }
        if ($this->isColumnModified(PDDebateArchivePeer::PUBLISHED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`published_at`';
        }
        if ($this->isColumnModified(PDDebateArchivePeer::PUBLISHED_BY)) {
            $modifiedColumns[':p' . $index++]  = '`published_by`';
        }
        if ($this->isColumnModified(PDDebateArchivePeer::FAVORITE)) {
            $modifiedColumns[':p' . $index++]  = '`favorite`';
        }
        if ($this->isColumnModified(PDDebateArchivePeer::ONLINE)) {
            $modifiedColumns[':p' . $index++]  = '`online`';
        }
        if ($this->isColumnModified(PDDebateArchivePeer::MODERATED)) {
            $modifiedColumns[':p' . $index++]  = '`moderated`';
        }
        if ($this->isColumnModified(PDDebateArchivePeer::MODERATED_PARTIAL)) {
            $modifiedColumns[':p' . $index++]  = '`moderated_partial`';
        }
        if ($this->isColumnModified(PDDebateArchivePeer::MODERATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`moderated_at`';
        }
        if ($this->isColumnModified(PDDebateArchivePeer::CREATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`created_at`';
        }
        if ($this->isColumnModified(PDDebateArchivePeer::UPDATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`updated_at`';
        }
        if ($this->isColumnModified(PDDebateArchivePeer::SLUG)) {
            $modifiedColumns[':p' . $index++]  = '`slug`';
        }
        if ($this->isColumnModified(PDDebateArchivePeer::ARCHIVED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`archived_at`';
        }

        $sql = sprintf(
            'INSERT INTO `p_d_debate_archive` (%s) VALUES (%s)',
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
                    case '`file_name`':
                        $stmt->bindValue($identifier, $this->file_name, PDO::PARAM_STR);
                        break;
                    case '`copyright`':
                        $stmt->bindValue($identifier, $this->copyright, PDO::PARAM_STR);
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
                    case '`slug`':
                        $stmt->bindValue($identifier, $this->slug, PDO::PARAM_STR);
                        break;
                    case '`archived_at`':
                        $stmt->bindValue($identifier, $this->archived_at, PDO::PARAM_STR);
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


            if (($retval = PDDebateArchivePeer::doValidate($this, $columns)) !== true) {
                $failureMap = array_merge($failureMap, $retval);
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
        $pos = PDDebateArchivePeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                return $this->getFileName();
                break;
            case 5:
                return $this->getCopyright();
                break;
            case 6:
                return $this->getDescription();
                break;
            case 7:
                return $this->getNotePos();
                break;
            case 8:
                return $this->getNoteNeg();
                break;
            case 9:
                return $this->getNbViews();
                break;
            case 10:
                return $this->getPublished();
                break;
            case 11:
                return $this->getPublishedAt();
                break;
            case 12:
                return $this->getPublishedBy();
                break;
            case 13:
                return $this->getFavorite();
                break;
            case 14:
                return $this->getOnline();
                break;
            case 15:
                return $this->getModerated();
                break;
            case 16:
                return $this->getModeratedPartial();
                break;
            case 17:
                return $this->getModeratedAt();
                break;
            case 18:
                return $this->getCreatedAt();
                break;
            case 19:
                return $this->getUpdatedAt();
                break;
            case 20:
                return $this->getSlug();
                break;
            case 21:
                return $this->getArchivedAt();
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
     *
     * @return array an associative array containing the field names (as keys) and field values
     */
    public function toArray($keyType = BasePeer::TYPE_PHPNAME, $includeLazyLoadColumns = true, $alreadyDumpedObjects = array())
    {
        if (isset($alreadyDumpedObjects['PDDebateArchive'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['PDDebateArchive'][$this->getPrimaryKey()] = true;
        $keys = PDDebateArchivePeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getUuid(),
            $keys[2] => $this->getPUserId(),
            $keys[3] => $this->getTitle(),
            $keys[4] => $this->getFileName(),
            $keys[5] => $this->getCopyright(),
            $keys[6] => $this->getDescription(),
            $keys[7] => $this->getNotePos(),
            $keys[8] => $this->getNoteNeg(),
            $keys[9] => $this->getNbViews(),
            $keys[10] => $this->getPublished(),
            $keys[11] => $this->getPublishedAt(),
            $keys[12] => $this->getPublishedBy(),
            $keys[13] => $this->getFavorite(),
            $keys[14] => $this->getOnline(),
            $keys[15] => $this->getModerated(),
            $keys[16] => $this->getModeratedPartial(),
            $keys[17] => $this->getModeratedAt(),
            $keys[18] => $this->getCreatedAt(),
            $keys[19] => $this->getUpdatedAt(),
            $keys[20] => $this->getSlug(),
            $keys[21] => $this->getArchivedAt(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
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
        $pos = PDDebateArchivePeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);

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
                $this->setFileName($value);
                break;
            case 5:
                $this->setCopyright($value);
                break;
            case 6:
                $this->setDescription($value);
                break;
            case 7:
                $this->setNotePos($value);
                break;
            case 8:
                $this->setNoteNeg($value);
                break;
            case 9:
                $this->setNbViews($value);
                break;
            case 10:
                $this->setPublished($value);
                break;
            case 11:
                $this->setPublishedAt($value);
                break;
            case 12:
                $this->setPublishedBy($value);
                break;
            case 13:
                $this->setFavorite($value);
                break;
            case 14:
                $this->setOnline($value);
                break;
            case 15:
                $this->setModerated($value);
                break;
            case 16:
                $this->setModeratedPartial($value);
                break;
            case 17:
                $this->setModeratedAt($value);
                break;
            case 18:
                $this->setCreatedAt($value);
                break;
            case 19:
                $this->setUpdatedAt($value);
                break;
            case 20:
                $this->setSlug($value);
                break;
            case 21:
                $this->setArchivedAt($value);
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
        $keys = PDDebateArchivePeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setUuid($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setPUserId($arr[$keys[2]]);
        if (array_key_exists($keys[3], $arr)) $this->setTitle($arr[$keys[3]]);
        if (array_key_exists($keys[4], $arr)) $this->setFileName($arr[$keys[4]]);
        if (array_key_exists($keys[5], $arr)) $this->setCopyright($arr[$keys[5]]);
        if (array_key_exists($keys[6], $arr)) $this->setDescription($arr[$keys[6]]);
        if (array_key_exists($keys[7], $arr)) $this->setNotePos($arr[$keys[7]]);
        if (array_key_exists($keys[8], $arr)) $this->setNoteNeg($arr[$keys[8]]);
        if (array_key_exists($keys[9], $arr)) $this->setNbViews($arr[$keys[9]]);
        if (array_key_exists($keys[10], $arr)) $this->setPublished($arr[$keys[10]]);
        if (array_key_exists($keys[11], $arr)) $this->setPublishedAt($arr[$keys[11]]);
        if (array_key_exists($keys[12], $arr)) $this->setPublishedBy($arr[$keys[12]]);
        if (array_key_exists($keys[13], $arr)) $this->setFavorite($arr[$keys[13]]);
        if (array_key_exists($keys[14], $arr)) $this->setOnline($arr[$keys[14]]);
        if (array_key_exists($keys[15], $arr)) $this->setModerated($arr[$keys[15]]);
        if (array_key_exists($keys[16], $arr)) $this->setModeratedPartial($arr[$keys[16]]);
        if (array_key_exists($keys[17], $arr)) $this->setModeratedAt($arr[$keys[17]]);
        if (array_key_exists($keys[18], $arr)) $this->setCreatedAt($arr[$keys[18]]);
        if (array_key_exists($keys[19], $arr)) $this->setUpdatedAt($arr[$keys[19]]);
        if (array_key_exists($keys[20], $arr)) $this->setSlug($arr[$keys[20]]);
        if (array_key_exists($keys[21], $arr)) $this->setArchivedAt($arr[$keys[21]]);
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(PDDebateArchivePeer::DATABASE_NAME);

        if ($this->isColumnModified(PDDebateArchivePeer::ID)) $criteria->add(PDDebateArchivePeer::ID, $this->id);
        if ($this->isColumnModified(PDDebateArchivePeer::UUID)) $criteria->add(PDDebateArchivePeer::UUID, $this->uuid);
        if ($this->isColumnModified(PDDebateArchivePeer::P_USER_ID)) $criteria->add(PDDebateArchivePeer::P_USER_ID, $this->p_user_id);
        if ($this->isColumnModified(PDDebateArchivePeer::TITLE)) $criteria->add(PDDebateArchivePeer::TITLE, $this->title);
        if ($this->isColumnModified(PDDebateArchivePeer::FILE_NAME)) $criteria->add(PDDebateArchivePeer::FILE_NAME, $this->file_name);
        if ($this->isColumnModified(PDDebateArchivePeer::COPYRIGHT)) $criteria->add(PDDebateArchivePeer::COPYRIGHT, $this->copyright);
        if ($this->isColumnModified(PDDebateArchivePeer::DESCRIPTION)) $criteria->add(PDDebateArchivePeer::DESCRIPTION, $this->description);
        if ($this->isColumnModified(PDDebateArchivePeer::NOTE_POS)) $criteria->add(PDDebateArchivePeer::NOTE_POS, $this->note_pos);
        if ($this->isColumnModified(PDDebateArchivePeer::NOTE_NEG)) $criteria->add(PDDebateArchivePeer::NOTE_NEG, $this->note_neg);
        if ($this->isColumnModified(PDDebateArchivePeer::NB_VIEWS)) $criteria->add(PDDebateArchivePeer::NB_VIEWS, $this->nb_views);
        if ($this->isColumnModified(PDDebateArchivePeer::PUBLISHED)) $criteria->add(PDDebateArchivePeer::PUBLISHED, $this->published);
        if ($this->isColumnModified(PDDebateArchivePeer::PUBLISHED_AT)) $criteria->add(PDDebateArchivePeer::PUBLISHED_AT, $this->published_at);
        if ($this->isColumnModified(PDDebateArchivePeer::PUBLISHED_BY)) $criteria->add(PDDebateArchivePeer::PUBLISHED_BY, $this->published_by);
        if ($this->isColumnModified(PDDebateArchivePeer::FAVORITE)) $criteria->add(PDDebateArchivePeer::FAVORITE, $this->favorite);
        if ($this->isColumnModified(PDDebateArchivePeer::ONLINE)) $criteria->add(PDDebateArchivePeer::ONLINE, $this->online);
        if ($this->isColumnModified(PDDebateArchivePeer::MODERATED)) $criteria->add(PDDebateArchivePeer::MODERATED, $this->moderated);
        if ($this->isColumnModified(PDDebateArchivePeer::MODERATED_PARTIAL)) $criteria->add(PDDebateArchivePeer::MODERATED_PARTIAL, $this->moderated_partial);
        if ($this->isColumnModified(PDDebateArchivePeer::MODERATED_AT)) $criteria->add(PDDebateArchivePeer::MODERATED_AT, $this->moderated_at);
        if ($this->isColumnModified(PDDebateArchivePeer::CREATED_AT)) $criteria->add(PDDebateArchivePeer::CREATED_AT, $this->created_at);
        if ($this->isColumnModified(PDDebateArchivePeer::UPDATED_AT)) $criteria->add(PDDebateArchivePeer::UPDATED_AT, $this->updated_at);
        if ($this->isColumnModified(PDDebateArchivePeer::SLUG)) $criteria->add(PDDebateArchivePeer::SLUG, $this->slug);
        if ($this->isColumnModified(PDDebateArchivePeer::ARCHIVED_AT)) $criteria->add(PDDebateArchivePeer::ARCHIVED_AT, $this->archived_at);

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
        $criteria = new Criteria(PDDebateArchivePeer::DATABASE_NAME);
        $criteria->add(PDDebateArchivePeer::ID, $this->id);

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
     * @param object $copyObj An object of PDDebateArchive (or compatible) type.
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setUuid($this->getUuid());
        $copyObj->setPUserId($this->getPUserId());
        $copyObj->setTitle($this->getTitle());
        $copyObj->setFileName($this->getFileName());
        $copyObj->setCopyright($this->getCopyright());
        $copyObj->setDescription($this->getDescription());
        $copyObj->setNotePos($this->getNotePos());
        $copyObj->setNoteNeg($this->getNoteNeg());
        $copyObj->setNbViews($this->getNbViews());
        $copyObj->setPublished($this->getPublished());
        $copyObj->setPublishedAt($this->getPublishedAt());
        $copyObj->setPublishedBy($this->getPublishedBy());
        $copyObj->setFavorite($this->getFavorite());
        $copyObj->setOnline($this->getOnline());
        $copyObj->setModerated($this->getModerated());
        $copyObj->setModeratedPartial($this->getModeratedPartial());
        $copyObj->setModeratedAt($this->getModeratedAt());
        $copyObj->setCreatedAt($this->getCreatedAt());
        $copyObj->setUpdatedAt($this->getUpdatedAt());
        $copyObj->setSlug($this->getSlug());
        $copyObj->setArchivedAt($this->getArchivedAt());
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
     * @return PDDebateArchive Clone of current object.
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
     * @return PDDebateArchivePeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new PDDebateArchivePeer();
        }

        return self::$peer;
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
        $this->file_name = null;
        $this->copyright = null;
        $this->description = null;
        $this->note_pos = null;
        $this->note_neg = null;
        $this->nb_views = null;
        $this->published = null;
        $this->published_at = null;
        $this->published_by = null;
        $this->favorite = null;
        $this->online = null;
        $this->moderated = null;
        $this->moderated_partial = null;
        $this->moderated_at = null;
        $this->created_at = null;
        $this->updated_at = null;
        $this->slug = null;
        $this->archived_at = null;
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

            $this->alreadyInClearAllReferencesDeep = false;
        } // if ($deep)

    }

    /**
     * return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(PDDebateArchivePeer::DEFAULT_STRING_FORMAT);
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

}
