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
use Politizr\Model\PDReactionArchive;
use Politizr\Model\PDReactionArchivePeer;
use Politizr\Model\PDReactionArchiveQuery;

abstract class BasePDReactionArchive extends BaseObject implements Persistent
{
    /**
     * Peer class name
     */
    const PEER = 'Politizr\\Model\\PDReactionArchivePeer';

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        PDReactionArchivePeer
     */
    protected static $peer;

    /**
     * The flag var to prevent infinite loop in deep copy
     * @var       boolean
     */
    protected $startCopy = false;

    /**
     * The value for the p_d_debate_id field.
     * @var        int
     */
    protected $p_d_debate_id;

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
     * The value for the tree_left field.
     * @var        int
     */
    protected $tree_left;

    /**
     * The value for the tree_right field.
     * @var        int
     */
    protected $tree_right;

    /**
     * The value for the tree_level field.
     * @var        int
     */
    protected $tree_level;

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
     * The value for the online field.
     * @var        boolean
     */
    protected $online;

    /**
     * The value for the broadcast field.
     * @var        boolean
     */
    protected $broadcast;

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
     * Get the [p_d_debate_id] column value.
     *
     * @return int
     */
    public function getPDDebateId()
    {

        return $this->p_d_debate_id;
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
     * Get the [tree_left] column value.
     *
     * @return int
     */
    public function getTreeLeft()
    {

        return $this->tree_left;
    }

    /**
     * Get the [tree_right] column value.
     *
     * @return int
     */
    public function getTreeRight()
    {

        return $this->tree_right;
    }

    /**
     * Get the [tree_level] column value.
     *
     * @return int
     */
    public function getTreeLevel()
    {

        return $this->tree_level;
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
     * Get the [online] column value.
     *
     * @return boolean
     */
    public function getOnline()
    {

        return $this->online;
    }

    /**
     * Get the [broadcast] column value.
     *
     * @return boolean
     */
    public function getBroadcast()
    {

        return $this->broadcast;
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
     * Set the value of [p_d_debate_id] column.
     *
     * @param  int $v new value
     * @return PDReactionArchive The current object (for fluent API support)
     */
    public function setPDDebateId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->p_d_debate_id !== $v) {
            $this->p_d_debate_id = $v;
            $this->modifiedColumns[] = PDReactionArchivePeer::P_D_DEBATE_ID;
        }


        return $this;
    } // setPDDebateId()

    /**
     * Sets the value of [created_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return PDReactionArchive The current object (for fluent API support)
     */
    public function setCreatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->created_at !== null || $dt !== null) {
            $currentDateAsString = ($this->created_at !== null && $tmpDt = new DateTime($this->created_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->created_at = $newDateAsString;
                $this->modifiedColumns[] = PDReactionArchivePeer::CREATED_AT;
            }
        } // if either are not null


        return $this;
    } // setCreatedAt()

    /**
     * Sets the value of [updated_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return PDReactionArchive The current object (for fluent API support)
     */
    public function setUpdatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->updated_at !== null || $dt !== null) {
            $currentDateAsString = ($this->updated_at !== null && $tmpDt = new DateTime($this->updated_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->updated_at = $newDateAsString;
                $this->modifiedColumns[] = PDReactionArchivePeer::UPDATED_AT;
            }
        } // if either are not null


        return $this;
    } // setUpdatedAt()

    /**
     * Set the value of [slug] column.
     *
     * @param  string $v new value
     * @return PDReactionArchive The current object (for fluent API support)
     */
    public function setSlug($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->slug !== $v) {
            $this->slug = $v;
            $this->modifiedColumns[] = PDReactionArchivePeer::SLUG;
        }


        return $this;
    } // setSlug()

    /**
     * Set the value of [tree_left] column.
     *
     * @param  int $v new value
     * @return PDReactionArchive The current object (for fluent API support)
     */
    public function setTreeLeft($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->tree_left !== $v) {
            $this->tree_left = $v;
            $this->modifiedColumns[] = PDReactionArchivePeer::TREE_LEFT;
        }


        return $this;
    } // setTreeLeft()

    /**
     * Set the value of [tree_right] column.
     *
     * @param  int $v new value
     * @return PDReactionArchive The current object (for fluent API support)
     */
    public function setTreeRight($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->tree_right !== $v) {
            $this->tree_right = $v;
            $this->modifiedColumns[] = PDReactionArchivePeer::TREE_RIGHT;
        }


        return $this;
    } // setTreeRight()

    /**
     * Set the value of [tree_level] column.
     *
     * @param  int $v new value
     * @return PDReactionArchive The current object (for fluent API support)
     */
    public function setTreeLevel($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->tree_level !== $v) {
            $this->tree_level = $v;
            $this->modifiedColumns[] = PDReactionArchivePeer::TREE_LEVEL;
        }


        return $this;
    } // setTreeLevel()

    /**
     * Set the value of [id] column.
     *
     * @param  int $v new value
     * @return PDReactionArchive The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[] = PDReactionArchivePeer::ID;
        }


        return $this;
    } // setId()

    /**
     * Set the value of [p_user_id] column.
     *
     * @param  int $v new value
     * @return PDReactionArchive The current object (for fluent API support)
     */
    public function setPUserId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->p_user_id !== $v) {
            $this->p_user_id = $v;
            $this->modifiedColumns[] = PDReactionArchivePeer::P_USER_ID;
        }


        return $this;
    } // setPUserId()

    /**
     * Set the value of [title] column.
     *
     * @param  string $v new value
     * @return PDReactionArchive The current object (for fluent API support)
     */
    public function setTitle($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->title !== $v) {
            $this->title = $v;
            $this->modifiedColumns[] = PDReactionArchivePeer::TITLE;
        }


        return $this;
    } // setTitle()

    /**
     * Set the value of [summary] column.
     *
     * @param  string $v new value
     * @return PDReactionArchive The current object (for fluent API support)
     */
    public function setSummary($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->summary !== $v) {
            $this->summary = $v;
            $this->modifiedColumns[] = PDReactionArchivePeer::SUMMARY;
        }


        return $this;
    } // setSummary()

    /**
     * Set the value of [description] column.
     *
     * @param  string $v new value
     * @return PDReactionArchive The current object (for fluent API support)
     */
    public function setDescription($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->description !== $v) {
            $this->description = $v;
            $this->modifiedColumns[] = PDReactionArchivePeer::DESCRIPTION;
        }


        return $this;
    } // setDescription()

    /**
     * Set the value of [more_info] column.
     *
     * @param  string $v new value
     * @return PDReactionArchive The current object (for fluent API support)
     */
    public function setMoreInfo($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->more_info !== $v) {
            $this->more_info = $v;
            $this->modifiedColumns[] = PDReactionArchivePeer::MORE_INFO;
        }


        return $this;
    } // setMoreInfo()

    /**
     * Set the value of [note_pos] column.
     *
     * @param  int $v new value
     * @return PDReactionArchive The current object (for fluent API support)
     */
    public function setNotePos($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->note_pos !== $v) {
            $this->note_pos = $v;
            $this->modifiedColumns[] = PDReactionArchivePeer::NOTE_POS;
        }


        return $this;
    } // setNotePos()

    /**
     * Set the value of [note_neg] column.
     *
     * @param  int $v new value
     * @return PDReactionArchive The current object (for fluent API support)
     */
    public function setNoteNeg($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->note_neg !== $v) {
            $this->note_neg = $v;
            $this->modifiedColumns[] = PDReactionArchivePeer::NOTE_NEG;
        }


        return $this;
    } // setNoteNeg()

    /**
     * Set the value of [nb_views] column.
     *
     * @param  int $v new value
     * @return PDReactionArchive The current object (for fluent API support)
     */
    public function setNbViews($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->nb_views !== $v) {
            $this->nb_views = $v;
            $this->modifiedColumns[] = PDReactionArchivePeer::NB_VIEWS;
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
     * @return PDReactionArchive The current object (for fluent API support)
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
            $this->modifiedColumns[] = PDReactionArchivePeer::PUBLISHED;
        }


        return $this;
    } // setPublished()

    /**
     * Sets the value of [published_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return PDReactionArchive The current object (for fluent API support)
     */
    public function setPublishedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->published_at !== null || $dt !== null) {
            $currentDateAsString = ($this->published_at !== null && $tmpDt = new DateTime($this->published_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->published_at = $newDateAsString;
                $this->modifiedColumns[] = PDReactionArchivePeer::PUBLISHED_AT;
            }
        } // if either are not null


        return $this;
    } // setPublishedAt()

    /**
     * Set the value of [published_by] column.
     *
     * @param  string $v new value
     * @return PDReactionArchive The current object (for fluent API support)
     */
    public function setPublishedBy($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->published_by !== $v) {
            $this->published_by = $v;
            $this->modifiedColumns[] = PDReactionArchivePeer::PUBLISHED_BY;
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
     * @return PDReactionArchive The current object (for fluent API support)
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
            $this->modifiedColumns[] = PDReactionArchivePeer::ONLINE;
        }


        return $this;
    } // setOnline()

    /**
     * Sets the value of the [broadcast] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param boolean|integer|string $v The new value
     * @return PDReactionArchive The current object (for fluent API support)
     */
    public function setBroadcast($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->broadcast !== $v) {
            $this->broadcast = $v;
            $this->modifiedColumns[] = PDReactionArchivePeer::BROADCAST;
        }


        return $this;
    } // setBroadcast()

    /**
     * Sets the value of [archived_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return PDReactionArchive The current object (for fluent API support)
     */
    public function setArchivedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->archived_at !== null || $dt !== null) {
            $currentDateAsString = ($this->archived_at !== null && $tmpDt = new DateTime($this->archived_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->archived_at = $newDateAsString;
                $this->modifiedColumns[] = PDReactionArchivePeer::ARCHIVED_AT;
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

            $this->p_d_debate_id = ($row[$startcol + 0] !== null) ? (int) $row[$startcol + 0] : null;
            $this->created_at = ($row[$startcol + 1] !== null) ? (string) $row[$startcol + 1] : null;
            $this->updated_at = ($row[$startcol + 2] !== null) ? (string) $row[$startcol + 2] : null;
            $this->slug = ($row[$startcol + 3] !== null) ? (string) $row[$startcol + 3] : null;
            $this->tree_left = ($row[$startcol + 4] !== null) ? (int) $row[$startcol + 4] : null;
            $this->tree_right = ($row[$startcol + 5] !== null) ? (int) $row[$startcol + 5] : null;
            $this->tree_level = ($row[$startcol + 6] !== null) ? (int) $row[$startcol + 6] : null;
            $this->id = ($row[$startcol + 7] !== null) ? (int) $row[$startcol + 7] : null;
            $this->p_user_id = ($row[$startcol + 8] !== null) ? (int) $row[$startcol + 8] : null;
            $this->title = ($row[$startcol + 9] !== null) ? (string) $row[$startcol + 9] : null;
            $this->summary = ($row[$startcol + 10] !== null) ? (string) $row[$startcol + 10] : null;
            $this->description = ($row[$startcol + 11] !== null) ? (string) $row[$startcol + 11] : null;
            $this->more_info = ($row[$startcol + 12] !== null) ? (string) $row[$startcol + 12] : null;
            $this->note_pos = ($row[$startcol + 13] !== null) ? (int) $row[$startcol + 13] : null;
            $this->note_neg = ($row[$startcol + 14] !== null) ? (int) $row[$startcol + 14] : null;
            $this->nb_views = ($row[$startcol + 15] !== null) ? (int) $row[$startcol + 15] : null;
            $this->published = ($row[$startcol + 16] !== null) ? (boolean) $row[$startcol + 16] : null;
            $this->published_at = ($row[$startcol + 17] !== null) ? (string) $row[$startcol + 17] : null;
            $this->published_by = ($row[$startcol + 18] !== null) ? (string) $row[$startcol + 18] : null;
            $this->online = ($row[$startcol + 19] !== null) ? (boolean) $row[$startcol + 19] : null;
            $this->broadcast = ($row[$startcol + 20] !== null) ? (boolean) $row[$startcol + 20] : null;
            $this->archived_at = ($row[$startcol + 21] !== null) ? (string) $row[$startcol + 21] : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }
            $this->postHydrate($row, $startcol, $rehydrate);

            return $startcol + 22; // 22 = PDReactionArchivePeer::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating PDReactionArchive object", $e);
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
            $con = Propel::getConnection(PDReactionArchivePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $stmt = PDReactionArchivePeer::doSelectStmt($this->buildPkeyCriteria(), $con);
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
            $con = Propel::getConnection(PDReactionArchivePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        try {
            $deleteQuery = PDReactionArchiveQuery::create()
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
            $con = Propel::getConnection(PDReactionArchivePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
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
                PDReactionArchivePeer::addInstanceToPool($this);
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
        if ($this->isColumnModified(PDReactionArchivePeer::P_D_DEBATE_ID)) {
            $modifiedColumns[':p' . $index++]  = '`p_d_debate_id`';
        }
        if ($this->isColumnModified(PDReactionArchivePeer::CREATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`created_at`';
        }
        if ($this->isColumnModified(PDReactionArchivePeer::UPDATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`updated_at`';
        }
        if ($this->isColumnModified(PDReactionArchivePeer::SLUG)) {
            $modifiedColumns[':p' . $index++]  = '`slug`';
        }
        if ($this->isColumnModified(PDReactionArchivePeer::TREE_LEFT)) {
            $modifiedColumns[':p' . $index++]  = '`tree_left`';
        }
        if ($this->isColumnModified(PDReactionArchivePeer::TREE_RIGHT)) {
            $modifiedColumns[':p' . $index++]  = '`tree_right`';
        }
        if ($this->isColumnModified(PDReactionArchivePeer::TREE_LEVEL)) {
            $modifiedColumns[':p' . $index++]  = '`tree_level`';
        }
        if ($this->isColumnModified(PDReactionArchivePeer::ID)) {
            $modifiedColumns[':p' . $index++]  = '`id`';
        }
        if ($this->isColumnModified(PDReactionArchivePeer::P_USER_ID)) {
            $modifiedColumns[':p' . $index++]  = '`p_user_id`';
        }
        if ($this->isColumnModified(PDReactionArchivePeer::TITLE)) {
            $modifiedColumns[':p' . $index++]  = '`title`';
        }
        if ($this->isColumnModified(PDReactionArchivePeer::SUMMARY)) {
            $modifiedColumns[':p' . $index++]  = '`summary`';
        }
        if ($this->isColumnModified(PDReactionArchivePeer::DESCRIPTION)) {
            $modifiedColumns[':p' . $index++]  = '`description`';
        }
        if ($this->isColumnModified(PDReactionArchivePeer::MORE_INFO)) {
            $modifiedColumns[':p' . $index++]  = '`more_info`';
        }
        if ($this->isColumnModified(PDReactionArchivePeer::NOTE_POS)) {
            $modifiedColumns[':p' . $index++]  = '`note_pos`';
        }
        if ($this->isColumnModified(PDReactionArchivePeer::NOTE_NEG)) {
            $modifiedColumns[':p' . $index++]  = '`note_neg`';
        }
        if ($this->isColumnModified(PDReactionArchivePeer::NB_VIEWS)) {
            $modifiedColumns[':p' . $index++]  = '`nb_views`';
        }
        if ($this->isColumnModified(PDReactionArchivePeer::PUBLISHED)) {
            $modifiedColumns[':p' . $index++]  = '`published`';
        }
        if ($this->isColumnModified(PDReactionArchivePeer::PUBLISHED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`published_at`';
        }
        if ($this->isColumnModified(PDReactionArchivePeer::PUBLISHED_BY)) {
            $modifiedColumns[':p' . $index++]  = '`published_by`';
        }
        if ($this->isColumnModified(PDReactionArchivePeer::ONLINE)) {
            $modifiedColumns[':p' . $index++]  = '`online`';
        }
        if ($this->isColumnModified(PDReactionArchivePeer::BROADCAST)) {
            $modifiedColumns[':p' . $index++]  = '`broadcast`';
        }
        if ($this->isColumnModified(PDReactionArchivePeer::ARCHIVED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`archived_at`';
        }

        $sql = sprintf(
            'INSERT INTO `p_d_reaction_archive` (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case '`p_d_debate_id`':
                        $stmt->bindValue($identifier, $this->p_d_debate_id, PDO::PARAM_INT);
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
                    case '`tree_left`':
                        $stmt->bindValue($identifier, $this->tree_left, PDO::PARAM_INT);
                        break;
                    case '`tree_right`':
                        $stmt->bindValue($identifier, $this->tree_right, PDO::PARAM_INT);
                        break;
                    case '`tree_level`':
                        $stmt->bindValue($identifier, $this->tree_level, PDO::PARAM_INT);
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
                    case '`online`':
                        $stmt->bindValue($identifier, (int) $this->online, PDO::PARAM_INT);
                        break;
                    case '`broadcast`':
                        $stmt->bindValue($identifier, (int) $this->broadcast, PDO::PARAM_INT);
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


            if (($retval = PDReactionArchivePeer::doValidate($this, $columns)) !== true) {
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
        $pos = PDReactionArchivePeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                return $this->getPDDebateId();
                break;
            case 1:
                return $this->getCreatedAt();
                break;
            case 2:
                return $this->getUpdatedAt();
                break;
            case 3:
                return $this->getSlug();
                break;
            case 4:
                return $this->getTreeLeft();
                break;
            case 5:
                return $this->getTreeRight();
                break;
            case 6:
                return $this->getTreeLevel();
                break;
            case 7:
                return $this->getId();
                break;
            case 8:
                return $this->getPUserId();
                break;
            case 9:
                return $this->getTitle();
                break;
            case 10:
                return $this->getSummary();
                break;
            case 11:
                return $this->getDescription();
                break;
            case 12:
                return $this->getMoreInfo();
                break;
            case 13:
                return $this->getNotePos();
                break;
            case 14:
                return $this->getNoteNeg();
                break;
            case 15:
                return $this->getNbViews();
                break;
            case 16:
                return $this->getPublished();
                break;
            case 17:
                return $this->getPublishedAt();
                break;
            case 18:
                return $this->getPublishedBy();
                break;
            case 19:
                return $this->getOnline();
                break;
            case 20:
                return $this->getBroadcast();
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
        if (isset($alreadyDumpedObjects['PDReactionArchive'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['PDReactionArchive'][$this->getPrimaryKey()] = true;
        $keys = PDReactionArchivePeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getPDDebateId(),
            $keys[1] => $this->getCreatedAt(),
            $keys[2] => $this->getUpdatedAt(),
            $keys[3] => $this->getSlug(),
            $keys[4] => $this->getTreeLeft(),
            $keys[5] => $this->getTreeRight(),
            $keys[6] => $this->getTreeLevel(),
            $keys[7] => $this->getId(),
            $keys[8] => $this->getPUserId(),
            $keys[9] => $this->getTitle(),
            $keys[10] => $this->getSummary(),
            $keys[11] => $this->getDescription(),
            $keys[12] => $this->getMoreInfo(),
            $keys[13] => $this->getNotePos(),
            $keys[14] => $this->getNoteNeg(),
            $keys[15] => $this->getNbViews(),
            $keys[16] => $this->getPublished(),
            $keys[17] => $this->getPublishedAt(),
            $keys[18] => $this->getPublishedBy(),
            $keys[19] => $this->getOnline(),
            $keys[20] => $this->getBroadcast(),
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
        $pos = PDReactionArchivePeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);

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
                $this->setPDDebateId($value);
                break;
            case 1:
                $this->setCreatedAt($value);
                break;
            case 2:
                $this->setUpdatedAt($value);
                break;
            case 3:
                $this->setSlug($value);
                break;
            case 4:
                $this->setTreeLeft($value);
                break;
            case 5:
                $this->setTreeRight($value);
                break;
            case 6:
                $this->setTreeLevel($value);
                break;
            case 7:
                $this->setId($value);
                break;
            case 8:
                $this->setPUserId($value);
                break;
            case 9:
                $this->setTitle($value);
                break;
            case 10:
                $this->setSummary($value);
                break;
            case 11:
                $this->setDescription($value);
                break;
            case 12:
                $this->setMoreInfo($value);
                break;
            case 13:
                $this->setNotePos($value);
                break;
            case 14:
                $this->setNoteNeg($value);
                break;
            case 15:
                $this->setNbViews($value);
                break;
            case 16:
                $this->setPublished($value);
                break;
            case 17:
                $this->setPublishedAt($value);
                break;
            case 18:
                $this->setPublishedBy($value);
                break;
            case 19:
                $this->setOnline($value);
                break;
            case 20:
                $this->setBroadcast($value);
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
        $keys = PDReactionArchivePeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) $this->setPDDebateId($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setCreatedAt($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setUpdatedAt($arr[$keys[2]]);
        if (array_key_exists($keys[3], $arr)) $this->setSlug($arr[$keys[3]]);
        if (array_key_exists($keys[4], $arr)) $this->setTreeLeft($arr[$keys[4]]);
        if (array_key_exists($keys[5], $arr)) $this->setTreeRight($arr[$keys[5]]);
        if (array_key_exists($keys[6], $arr)) $this->setTreeLevel($arr[$keys[6]]);
        if (array_key_exists($keys[7], $arr)) $this->setId($arr[$keys[7]]);
        if (array_key_exists($keys[8], $arr)) $this->setPUserId($arr[$keys[8]]);
        if (array_key_exists($keys[9], $arr)) $this->setTitle($arr[$keys[9]]);
        if (array_key_exists($keys[10], $arr)) $this->setSummary($arr[$keys[10]]);
        if (array_key_exists($keys[11], $arr)) $this->setDescription($arr[$keys[11]]);
        if (array_key_exists($keys[12], $arr)) $this->setMoreInfo($arr[$keys[12]]);
        if (array_key_exists($keys[13], $arr)) $this->setNotePos($arr[$keys[13]]);
        if (array_key_exists($keys[14], $arr)) $this->setNoteNeg($arr[$keys[14]]);
        if (array_key_exists($keys[15], $arr)) $this->setNbViews($arr[$keys[15]]);
        if (array_key_exists($keys[16], $arr)) $this->setPublished($arr[$keys[16]]);
        if (array_key_exists($keys[17], $arr)) $this->setPublishedAt($arr[$keys[17]]);
        if (array_key_exists($keys[18], $arr)) $this->setPublishedBy($arr[$keys[18]]);
        if (array_key_exists($keys[19], $arr)) $this->setOnline($arr[$keys[19]]);
        if (array_key_exists($keys[20], $arr)) $this->setBroadcast($arr[$keys[20]]);
        if (array_key_exists($keys[21], $arr)) $this->setArchivedAt($arr[$keys[21]]);
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(PDReactionArchivePeer::DATABASE_NAME);

        if ($this->isColumnModified(PDReactionArchivePeer::P_D_DEBATE_ID)) $criteria->add(PDReactionArchivePeer::P_D_DEBATE_ID, $this->p_d_debate_id);
        if ($this->isColumnModified(PDReactionArchivePeer::CREATED_AT)) $criteria->add(PDReactionArchivePeer::CREATED_AT, $this->created_at);
        if ($this->isColumnModified(PDReactionArchivePeer::UPDATED_AT)) $criteria->add(PDReactionArchivePeer::UPDATED_AT, $this->updated_at);
        if ($this->isColumnModified(PDReactionArchivePeer::SLUG)) $criteria->add(PDReactionArchivePeer::SLUG, $this->slug);
        if ($this->isColumnModified(PDReactionArchivePeer::TREE_LEFT)) $criteria->add(PDReactionArchivePeer::TREE_LEFT, $this->tree_left);
        if ($this->isColumnModified(PDReactionArchivePeer::TREE_RIGHT)) $criteria->add(PDReactionArchivePeer::TREE_RIGHT, $this->tree_right);
        if ($this->isColumnModified(PDReactionArchivePeer::TREE_LEVEL)) $criteria->add(PDReactionArchivePeer::TREE_LEVEL, $this->tree_level);
        if ($this->isColumnModified(PDReactionArchivePeer::ID)) $criteria->add(PDReactionArchivePeer::ID, $this->id);
        if ($this->isColumnModified(PDReactionArchivePeer::P_USER_ID)) $criteria->add(PDReactionArchivePeer::P_USER_ID, $this->p_user_id);
        if ($this->isColumnModified(PDReactionArchivePeer::TITLE)) $criteria->add(PDReactionArchivePeer::TITLE, $this->title);
        if ($this->isColumnModified(PDReactionArchivePeer::SUMMARY)) $criteria->add(PDReactionArchivePeer::SUMMARY, $this->summary);
        if ($this->isColumnModified(PDReactionArchivePeer::DESCRIPTION)) $criteria->add(PDReactionArchivePeer::DESCRIPTION, $this->description);
        if ($this->isColumnModified(PDReactionArchivePeer::MORE_INFO)) $criteria->add(PDReactionArchivePeer::MORE_INFO, $this->more_info);
        if ($this->isColumnModified(PDReactionArchivePeer::NOTE_POS)) $criteria->add(PDReactionArchivePeer::NOTE_POS, $this->note_pos);
        if ($this->isColumnModified(PDReactionArchivePeer::NOTE_NEG)) $criteria->add(PDReactionArchivePeer::NOTE_NEG, $this->note_neg);
        if ($this->isColumnModified(PDReactionArchivePeer::NB_VIEWS)) $criteria->add(PDReactionArchivePeer::NB_VIEWS, $this->nb_views);
        if ($this->isColumnModified(PDReactionArchivePeer::PUBLISHED)) $criteria->add(PDReactionArchivePeer::PUBLISHED, $this->published);
        if ($this->isColumnModified(PDReactionArchivePeer::PUBLISHED_AT)) $criteria->add(PDReactionArchivePeer::PUBLISHED_AT, $this->published_at);
        if ($this->isColumnModified(PDReactionArchivePeer::PUBLISHED_BY)) $criteria->add(PDReactionArchivePeer::PUBLISHED_BY, $this->published_by);
        if ($this->isColumnModified(PDReactionArchivePeer::ONLINE)) $criteria->add(PDReactionArchivePeer::ONLINE, $this->online);
        if ($this->isColumnModified(PDReactionArchivePeer::BROADCAST)) $criteria->add(PDReactionArchivePeer::BROADCAST, $this->broadcast);
        if ($this->isColumnModified(PDReactionArchivePeer::ARCHIVED_AT)) $criteria->add(PDReactionArchivePeer::ARCHIVED_AT, $this->archived_at);

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
        $criteria = new Criteria(PDReactionArchivePeer::DATABASE_NAME);
        $criteria->add(PDReactionArchivePeer::ID, $this->id);

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
     * @param object $copyObj An object of PDReactionArchive (or compatible) type.
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setPDDebateId($this->getPDDebateId());
        $copyObj->setCreatedAt($this->getCreatedAt());
        $copyObj->setUpdatedAt($this->getUpdatedAt());
        $copyObj->setSlug($this->getSlug());
        $copyObj->setTreeLeft($this->getTreeLeft());
        $copyObj->setTreeRight($this->getTreeRight());
        $copyObj->setTreeLevel($this->getTreeLevel());
        $copyObj->setPUserId($this->getPUserId());
        $copyObj->setTitle($this->getTitle());
        $copyObj->setSummary($this->getSummary());
        $copyObj->setDescription($this->getDescription());
        $copyObj->setMoreInfo($this->getMoreInfo());
        $copyObj->setNotePos($this->getNotePos());
        $copyObj->setNoteNeg($this->getNoteNeg());
        $copyObj->setNbViews($this->getNbViews());
        $copyObj->setPublished($this->getPublished());
        $copyObj->setPublishedAt($this->getPublishedAt());
        $copyObj->setPublishedBy($this->getPublishedBy());
        $copyObj->setOnline($this->getOnline());
        $copyObj->setBroadcast($this->getBroadcast());
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
     * @return PDReactionArchive Clone of current object.
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
     * @return PDReactionArchivePeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new PDReactionArchivePeer();
        }

        return self::$peer;
    }

    /**
     * Clears the current object and sets all attributes to their default values
     */
    public function clear()
    {
        $this->p_d_debate_id = null;
        $this->created_at = null;
        $this->updated_at = null;
        $this->slug = null;
        $this->tree_left = null;
        $this->tree_right = null;
        $this->tree_level = null;
        $this->id = null;
        $this->p_user_id = null;
        $this->title = null;
        $this->summary = null;
        $this->description = null;
        $this->more_info = null;
        $this->note_pos = null;
        $this->note_neg = null;
        $this->nb_views = null;
        $this->published = null;
        $this->published_at = null;
        $this->published_by = null;
        $this->online = null;
        $this->broadcast = null;
        $this->archived_at = null;
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
        return (string) $this->exportTo(PDReactionArchivePeer::DEFAULT_STRING_FORMAT);
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
