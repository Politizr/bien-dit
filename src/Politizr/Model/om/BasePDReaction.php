<?php

namespace Politizr\Model\om;

use \BasePeer;
use \Criteria;
use \DateTime;
use \Exception;
use \PDO;
use \Persistent;
use \Propel;
use \PropelDateTime;
use \PropelException;
use \PropelObjectCollection;
use \PropelPDO;
use Politizr\Model\PDDebate;
use Politizr\Model\PDDebateQuery;
use Politizr\Model\PDReaction;
use Politizr\Model\PDReactionArchive;
use Politizr\Model\PDReactionArchiveQuery;
use Politizr\Model\PDReactionPeer;
use Politizr\Model\PDReactionQuery;
use Politizr\Model\PDocument;
use Politizr\Model\PDocumentQuery;
use Politizr\Model\PUser;
use Politizr\Model\PUserQuery;

abstract class BasePDReaction extends PDocument implements Persistent
{
    /**
     * Peer class name
     */
    const PEER = 'Politizr\\Model\\PDReactionPeer';

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        PDReactionPeer
     */
    protected static $peer;

    /**
     * The flag var to prevent infinit loop in deep copy
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
     * @var        PDDebate
     */
    protected $aPDDebate;

    /**
     * @var        PDocument
     */
    protected $aPDocument;

    /**
     * @var        PUser
     */
    protected $aPUser;

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

    // nested_set behavior

    /**
     * Queries to be executed in the save transaction
     * @var        array
     */
    protected $nestedSetQueries = array();

    /**
     * Internal cache for children nodes
     * @var        null|PropelObjectCollection
     */
    protected $collNestedSetChildren = null;

    /**
     * Internal cache for parent node
     * @var        null|PDReaction
     */
    protected $aNestedSetParent = null;


    // archivable behavior
    protected $archiveOnDelete = true;

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
     * Set the value of [p_d_debate_id] column.
     *
     * @param int $v new value
     * @return PDReaction The current object (for fluent API support)
     */
    public function setPDDebateId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->p_d_debate_id !== $v) {
            $this->p_d_debate_id = $v;
            $this->modifiedColumns[] = PDReactionPeer::P_D_DEBATE_ID;
        }

        if ($this->aPDDebate !== null && $this->aPDDebate->getId() !== $v) {
            $this->aPDDebate = null;
        }


        return $this;
    } // setPDDebateId()

    /**
     * Sets the value of [created_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return PDReaction The current object (for fluent API support)
     */
    public function setCreatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->created_at !== null || $dt !== null) {
            $currentDateAsString = ($this->created_at !== null && $tmpDt = new DateTime($this->created_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->created_at = $newDateAsString;
                $this->modifiedColumns[] = PDReactionPeer::CREATED_AT;
            }
        } // if either are not null


        return $this;
    } // setCreatedAt()

    /**
     * Sets the value of [updated_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return PDReaction The current object (for fluent API support)
     */
    public function setUpdatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->updated_at !== null || $dt !== null) {
            $currentDateAsString = ($this->updated_at !== null && $tmpDt = new DateTime($this->updated_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->updated_at = $newDateAsString;
                $this->modifiedColumns[] = PDReactionPeer::UPDATED_AT;
            }
        } // if either are not null


        return $this;
    } // setUpdatedAt()

    /**
     * Set the value of [slug] column.
     *
     * @param string $v new value
     * @return PDReaction The current object (for fluent API support)
     */
    public function setSlug($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (string) $v;
        }

        if ($this->slug !== $v) {
            $this->slug = $v;
            $this->modifiedColumns[] = PDReactionPeer::SLUG;
        }


        return $this;
    } // setSlug()

    /**
     * Set the value of [tree_left] column.
     *
     * @param int $v new value
     * @return PDReaction The current object (for fluent API support)
     */
    public function setTreeLeft($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->tree_left !== $v) {
            $this->tree_left = $v;
            $this->modifiedColumns[] = PDReactionPeer::TREE_LEFT;
        }


        return $this;
    } // setTreeLeft()

    /**
     * Set the value of [tree_right] column.
     *
     * @param int $v new value
     * @return PDReaction The current object (for fluent API support)
     */
    public function setTreeRight($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->tree_right !== $v) {
            $this->tree_right = $v;
            $this->modifiedColumns[] = PDReactionPeer::TREE_RIGHT;
        }


        return $this;
    } // setTreeRight()

    /**
     * Set the value of [tree_level] column.
     *
     * @param int $v new value
     * @return PDReaction The current object (for fluent API support)
     */
    public function setTreeLevel($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->tree_level !== $v) {
            $this->tree_level = $v;
            $this->modifiedColumns[] = PDReactionPeer::TREE_LEVEL;
        }


        return $this;
    } // setTreeLevel()

    /**
     * Set the value of [id] column.
     *
     * @param int $v new value
     * @return PDReaction The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[] = PDReactionPeer::ID;
        }

        if ($this->aPDocument !== null && $this->aPDocument->getId() !== $v) {
            $this->aPDocument = null;
        }


        return $this;
    } // setId()

    /**
     * Set the value of [p_user_id] column.
     *
     * @param int $v new value
     * @return PDReaction The current object (for fluent API support)
     */
    public function setPUserId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->p_user_id !== $v) {
            $this->p_user_id = $v;
            $this->modifiedColumns[] = PDReactionPeer::P_USER_ID;
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
     * @return PDReaction The current object (for fluent API support)
     */
    public function setTitle($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (string) $v;
        }

        if ($this->title !== $v) {
            $this->title = $v;
            $this->modifiedColumns[] = PDReactionPeer::TITLE;
        }


        return $this;
    } // setTitle()

    /**
     * Set the value of [summary] column.
     *
     * @param string $v new value
     * @return PDReaction The current object (for fluent API support)
     */
    public function setSummary($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (string) $v;
        }

        if ($this->summary !== $v) {
            $this->summary = $v;
            $this->modifiedColumns[] = PDReactionPeer::SUMMARY;
        }


        return $this;
    } // setSummary()

    /**
     * Set the value of [description] column.
     *
     * @param string $v new value
     * @return PDReaction The current object (for fluent API support)
     */
    public function setDescription($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (string) $v;
        }

        if ($this->description !== $v) {
            $this->description = $v;
            $this->modifiedColumns[] = PDReactionPeer::DESCRIPTION;
        }


        return $this;
    } // setDescription()

    /**
     * Set the value of [more_info] column.
     *
     * @param string $v new value
     * @return PDReaction The current object (for fluent API support)
     */
    public function setMoreInfo($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (string) $v;
        }

        if ($this->more_info !== $v) {
            $this->more_info = $v;
            $this->modifiedColumns[] = PDReactionPeer::MORE_INFO;
        }


        return $this;
    } // setMoreInfo()

    /**
     * Set the value of [note_pos] column.
     *
     * @param int $v new value
     * @return PDReaction The current object (for fluent API support)
     */
    public function setNotePos($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->note_pos !== $v) {
            $this->note_pos = $v;
            $this->modifiedColumns[] = PDReactionPeer::NOTE_POS;
        }


        return $this;
    } // setNotePos()

    /**
     * Set the value of [note_neg] column.
     *
     * @param int $v new value
     * @return PDReaction The current object (for fluent API support)
     */
    public function setNoteNeg($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->note_neg !== $v) {
            $this->note_neg = $v;
            $this->modifiedColumns[] = PDReactionPeer::NOTE_NEG;
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
     * @return PDReaction The current object (for fluent API support)
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
            $this->modifiedColumns[] = PDReactionPeer::PUBLISHED;
        }


        return $this;
    } // setPublished()

    /**
     * Sets the value of [published_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return PDReaction The current object (for fluent API support)
     */
    public function setPublishedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->published_at !== null || $dt !== null) {
            $currentDateAsString = ($this->published_at !== null && $tmpDt = new DateTime($this->published_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->published_at = $newDateAsString;
                $this->modifiedColumns[] = PDReactionPeer::PUBLISHED_AT;
            }
        } // if either are not null


        return $this;
    } // setPublishedAt()

    /**
     * Set the value of [published_by] column.
     *
     * @param string $v new value
     * @return PDReaction The current object (for fluent API support)
     */
    public function setPublishedBy($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (string) $v;
        }

        if ($this->published_by !== $v) {
            $this->published_by = $v;
            $this->modifiedColumns[] = PDReactionPeer::PUBLISHED_BY;
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
     * @return PDReaction The current object (for fluent API support)
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
            $this->modifiedColumns[] = PDReactionPeer::ONLINE;
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
            $this->published = ($row[$startcol + 15] !== null) ? (boolean) $row[$startcol + 15] : null;
            $this->published_at = ($row[$startcol + 16] !== null) ? (string) $row[$startcol + 16] : null;
            $this->published_by = ($row[$startcol + 17] !== null) ? (string) $row[$startcol + 17] : null;
            $this->online = ($row[$startcol + 18] !== null) ? (boolean) $row[$startcol + 18] : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }
            $this->postHydrate($row, $startcol, $rehydrate);
            return $startcol + 19; // 19 = PDReactionPeer::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating PDReaction object", $e);
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

        if ($this->aPDDebate !== null && $this->p_d_debate_id !== $this->aPDDebate->getId()) {
            $this->aPDDebate = null;
        }
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
            $con = Propel::getConnection(PDReactionPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $stmt = PDReactionPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
        $row = $stmt->fetch(PDO::FETCH_NUM);
        $stmt->closeCursor();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aPDDebate = null;
            $this->aPDocument = null;
            $this->aPUser = null;
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
            $con = Propel::getConnection(PDReactionPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        try {
            $deleteQuery = PDReactionQuery::create()
                ->filterByPrimaryKey($this->getPrimaryKey());
            $ret = $this->preDelete($con);
            // nested_set behavior
            if ($this->isRoot()) {
                throw new PropelException('Deletion of a root node is disabled for nested sets. Use PDReactionPeer::deleteTree($scope) instead to delete an entire tree');
            }

            if ($this->isInTree()) {
                $this->deleteDescendants($con);
            }

            // archivable behavior
            if ($ret) {
                if ($this->archiveOnDelete) {
                    // do nothing yet. The object will be archived later when calling PDReactionQuery::delete().
                } else {
                    $deleteQuery->setArchiveOnDelete(false);
                    $this->archiveOnDelete = true;
                }
            }

            if ($ret) {
                $deleteQuery->delete($con);
                $this->postDelete($con);
                // nested_set behavior
                if ($this->isInTree()) {
                    // fill up the room that was used by the node
                    PDReactionPeer::shiftRLValues(-2, $this->getRightValue() + 1, null, $this->getScopeValue(), $con);
                }

                // concrete_inheritance behavior
                $this->getParentOrCreate($con)->delete($con);

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
            $con = Propel::getConnection(PDReactionPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        $isInsert = $this->isNew();
        try {
            $ret = $this->preSave($con);
            // sluggable behavior

            if ($this->isColumnModified(PDReactionPeer::SLUG) && $this->getSlug()) {
                $this->setSlug($this->makeSlugUnique($this->getSlug()));
            } elseif ($this->isColumnModified(PDReactionPeer::TITLE)) {
                $this->setSlug($this->createSlug());
            } elseif (!$this->getSlug()) {
                $this->setSlug($this->createSlug());
            }
            // nested_set behavior
            if ($this->isNew() && $this->isRoot()) {
                // check if no other root exist in, the tree
                $nbRoots = PDReactionQuery::create()
                    ->addUsingAlias(PDReactionPeer::LEFT_COL, 1, Criteria::EQUAL)
                    ->addUsingAlias(PDReactionPeer::SCOPE_COL, $this->getScopeValue(), Criteria::EQUAL)
                    ->count($con);
                if ($nbRoots > 0) {
                        throw new PropelException(sprintf('A root node already exists in this tree with scope "%s".', $this->getScopeValue()));
                }
            }
            $this->processNestedSetQueries($con);
            // concrete_inheritance behavior
            $parent = $this->getSyncParent($con);
            $parent->save($con);
            $this->setPrimaryKey($parent->getPrimaryKey());

            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
                // timestampable behavior
                if (!$this->isColumnModified(PDReactionPeer::CREATED_AT)) {
                    $this->setCreatedAt(time());
                }
                if (!$this->isColumnModified(PDReactionPeer::UPDATED_AT)) {
                    $this->setUpdatedAt(time());
                }
            } else {
                $ret = $ret && $this->preUpdate($con);
                // timestampable behavior
                if ($this->isModified() && !$this->isColumnModified(PDReactionPeer::UPDATED_AT)) {
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
                PDReactionPeer::addInstanceToPool($this);
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

            if ($this->aPDDebate !== null) {
                if ($this->aPDDebate->isModified() || $this->aPDDebate->isNew()) {
                    $affectedRows += $this->aPDDebate->save($con);
                }
                $this->setPDDebate($this->aPDDebate);
            }

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
        if ($this->isColumnModified(PDReactionPeer::P_D_DEBATE_ID)) {
            $modifiedColumns[':p' . $index++]  = '`p_d_debate_id`';
        }
        if ($this->isColumnModified(PDReactionPeer::CREATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`created_at`';
        }
        if ($this->isColumnModified(PDReactionPeer::UPDATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`updated_at`';
        }
        if ($this->isColumnModified(PDReactionPeer::SLUG)) {
            $modifiedColumns[':p' . $index++]  = '`slug`';
        }
        if ($this->isColumnModified(PDReactionPeer::TREE_LEFT)) {
            $modifiedColumns[':p' . $index++]  = '`tree_left`';
        }
        if ($this->isColumnModified(PDReactionPeer::TREE_RIGHT)) {
            $modifiedColumns[':p' . $index++]  = '`tree_right`';
        }
        if ($this->isColumnModified(PDReactionPeer::TREE_LEVEL)) {
            $modifiedColumns[':p' . $index++]  = '`tree_level`';
        }
        if ($this->isColumnModified(PDReactionPeer::ID)) {
            $modifiedColumns[':p' . $index++]  = '`id`';
        }
        if ($this->isColumnModified(PDReactionPeer::P_USER_ID)) {
            $modifiedColumns[':p' . $index++]  = '`p_user_id`';
        }
        if ($this->isColumnModified(PDReactionPeer::TITLE)) {
            $modifiedColumns[':p' . $index++]  = '`title`';
        }
        if ($this->isColumnModified(PDReactionPeer::SUMMARY)) {
            $modifiedColumns[':p' . $index++]  = '`summary`';
        }
        if ($this->isColumnModified(PDReactionPeer::DESCRIPTION)) {
            $modifiedColumns[':p' . $index++]  = '`description`';
        }
        if ($this->isColumnModified(PDReactionPeer::MORE_INFO)) {
            $modifiedColumns[':p' . $index++]  = '`more_info`';
        }
        if ($this->isColumnModified(PDReactionPeer::NOTE_POS)) {
            $modifiedColumns[':p' . $index++]  = '`note_pos`';
        }
        if ($this->isColumnModified(PDReactionPeer::NOTE_NEG)) {
            $modifiedColumns[':p' . $index++]  = '`note_neg`';
        }
        if ($this->isColumnModified(PDReactionPeer::PUBLISHED)) {
            $modifiedColumns[':p' . $index++]  = '`published`';
        }
        if ($this->isColumnModified(PDReactionPeer::PUBLISHED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`published_at`';
        }
        if ($this->isColumnModified(PDReactionPeer::PUBLISHED_BY)) {
            $modifiedColumns[':p' . $index++]  = '`published_by`';
        }
        if ($this->isColumnModified(PDReactionPeer::ONLINE)) {
            $modifiedColumns[':p' . $index++]  = '`online`';
        }

        $sql = sprintf(
            'INSERT INTO `p_d_reaction` (%s) VALUES (%s)',
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

            if ($this->aPDDebate !== null) {
                if (!$this->aPDDebate->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aPDDebate->getValidationFailures());
                }
            }

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


            if (($retval = PDReactionPeer::doValidate($this, $columns)) !== true) {
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
        $pos = PDReactionPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                return $this->getPublished();
                break;
            case 16:
                return $this->getPublishedAt();
                break;
            case 17:
                return $this->getPublishedBy();
                break;
            case 18:
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
        if (isset($alreadyDumpedObjects['PDReaction'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['PDReaction'][$this->getPrimaryKey()] = true;
        $keys = PDReactionPeer::getFieldNames($keyType);
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
            $keys[15] => $this->getPublished(),
            $keys[16] => $this->getPublishedAt(),
            $keys[17] => $this->getPublishedBy(),
            $keys[18] => $this->getOnline(),
        );
        if ($includeForeignObjects) {
            if (null !== $this->aPDDebate) {
                $result['PDDebate'] = $this->aPDDebate->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aPDocument) {
                $result['PDocument'] = $this->aPDocument->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aPUser) {
                $result['PUser'] = $this->aPUser->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
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
        $pos = PDReactionPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);

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
                $this->setPublished($value);
                break;
            case 16:
                $this->setPublishedAt($value);
                break;
            case 17:
                $this->setPublishedBy($value);
                break;
            case 18:
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
        $keys = PDReactionPeer::getFieldNames($keyType);

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
        if (array_key_exists($keys[15], $arr)) $this->setPublished($arr[$keys[15]]);
        if (array_key_exists($keys[16], $arr)) $this->setPublishedAt($arr[$keys[16]]);
        if (array_key_exists($keys[17], $arr)) $this->setPublishedBy($arr[$keys[17]]);
        if (array_key_exists($keys[18], $arr)) $this->setOnline($arr[$keys[18]]);
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(PDReactionPeer::DATABASE_NAME);

        if ($this->isColumnModified(PDReactionPeer::P_D_DEBATE_ID)) $criteria->add(PDReactionPeer::P_D_DEBATE_ID, $this->p_d_debate_id);
        if ($this->isColumnModified(PDReactionPeer::CREATED_AT)) $criteria->add(PDReactionPeer::CREATED_AT, $this->created_at);
        if ($this->isColumnModified(PDReactionPeer::UPDATED_AT)) $criteria->add(PDReactionPeer::UPDATED_AT, $this->updated_at);
        if ($this->isColumnModified(PDReactionPeer::SLUG)) $criteria->add(PDReactionPeer::SLUG, $this->slug);
        if ($this->isColumnModified(PDReactionPeer::TREE_LEFT)) $criteria->add(PDReactionPeer::TREE_LEFT, $this->tree_left);
        if ($this->isColumnModified(PDReactionPeer::TREE_RIGHT)) $criteria->add(PDReactionPeer::TREE_RIGHT, $this->tree_right);
        if ($this->isColumnModified(PDReactionPeer::TREE_LEVEL)) $criteria->add(PDReactionPeer::TREE_LEVEL, $this->tree_level);
        if ($this->isColumnModified(PDReactionPeer::ID)) $criteria->add(PDReactionPeer::ID, $this->id);
        if ($this->isColumnModified(PDReactionPeer::P_USER_ID)) $criteria->add(PDReactionPeer::P_USER_ID, $this->p_user_id);
        if ($this->isColumnModified(PDReactionPeer::TITLE)) $criteria->add(PDReactionPeer::TITLE, $this->title);
        if ($this->isColumnModified(PDReactionPeer::SUMMARY)) $criteria->add(PDReactionPeer::SUMMARY, $this->summary);
        if ($this->isColumnModified(PDReactionPeer::DESCRIPTION)) $criteria->add(PDReactionPeer::DESCRIPTION, $this->description);
        if ($this->isColumnModified(PDReactionPeer::MORE_INFO)) $criteria->add(PDReactionPeer::MORE_INFO, $this->more_info);
        if ($this->isColumnModified(PDReactionPeer::NOTE_POS)) $criteria->add(PDReactionPeer::NOTE_POS, $this->note_pos);
        if ($this->isColumnModified(PDReactionPeer::NOTE_NEG)) $criteria->add(PDReactionPeer::NOTE_NEG, $this->note_neg);
        if ($this->isColumnModified(PDReactionPeer::PUBLISHED)) $criteria->add(PDReactionPeer::PUBLISHED, $this->published);
        if ($this->isColumnModified(PDReactionPeer::PUBLISHED_AT)) $criteria->add(PDReactionPeer::PUBLISHED_AT, $this->published_at);
        if ($this->isColumnModified(PDReactionPeer::PUBLISHED_BY)) $criteria->add(PDReactionPeer::PUBLISHED_BY, $this->published_by);
        if ($this->isColumnModified(PDReactionPeer::ONLINE)) $criteria->add(PDReactionPeer::ONLINE, $this->online);

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
        $criteria = new Criteria(PDReactionPeer::DATABASE_NAME);
        $criteria->add(PDReactionPeer::ID, $this->id);

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
     * @param object $copyObj An object of PDReaction (or compatible) type.
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
        $copyObj->setPublished($this->getPublished());
        $copyObj->setPublishedAt($this->getPublishedAt());
        $copyObj->setPublishedBy($this->getPublishedBy());
        $copyObj->setOnline($this->getOnline());

        if ($deepCopy && !$this->startCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);
            // store object hash to prevent cycle
            $this->startCopy = true;

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
     * @return PDReaction Clone of current object.
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
     * @return PDReactionPeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new PDReactionPeer();
        }

        return self::$peer;
    }

    /**
     * Declares an association between this object and a PDDebate object.
     *
     * @param             PDDebate $v
     * @return PDReaction The current object (for fluent API support)
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
            $v->addPDReaction($this);
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
                $this->aPDDebate->addPDReactions($this);
             */
        }

        return $this->aPDDebate;
    }

    /**
     * Declares an association between this object and a PDocument object.
     *
     * @param             PDocument $v
     * @return PDReaction The current object (for fluent API support)
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
            $v->setPDReaction($this);
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
            $this->aPDocument->setPDReaction($this);
        }

        return $this->aPDocument;
    }

    /**
     * Declares an association between this object and a PUser object.
     *
     * @param             PUser $v
     * @return PDReaction The current object (for fluent API support)
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
            $v->addPDReaction($this);
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
                $this->aPUser->addPDReactions($this);
             */
        }

        return $this->aPUser;
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
        $this->published = null;
        $this->published_at = null;
        $this->published_by = null;
        $this->online = null;
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
            if ($this->aPDDebate instanceof Persistent) {
              $this->aPDDebate->clearAllReferences($deep);
            }
            if ($this->aPDocument instanceof Persistent) {
              $this->aPDocument->clearAllReferences($deep);
            }
            if ($this->aPUser instanceof Persistent) {
              $this->aPUser->clearAllReferences($deep);
            }

            $this->alreadyInClearAllReferencesDeep = false;
        } // if ($deep)

        // nested_set behavior
        $this->collNestedSetChildren = null;
        $this->aNestedSetParent = null;
        $this->aPDDebate = null;
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
        return (string) $this->exportTo(PDReactionPeer::DEFAULT_STRING_FORMAT);
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
     * @return     PDReaction The current object (for fluent API support)
     */
    public function keepUpdateDateUnchanged()
    {
        $this->modifiedColumns[] = PDReactionPeer::UPDATED_AT;

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
     * Make sure the slug is short enough to accomodate the column size
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

        $query = PDReactionQuery::create('q')
            ->where('q.Slug ' . ($alreadyExists ? 'REGEXP' : '=') . ' ?', $alreadyExists ? '^' . $slug2 . '[0-9]+$' : $slug2)
            ->prune($this)
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
        if (0 == $slugNum[0]) {
            $slugNum[0] = 1;
        }

        return $slug2 . ($slugNum + 1);
    }

    // nested_set behavior

    /**
     * Execute queries that were saved to be run inside the save transaction
     */
    protected function processNestedSetQueries($con)
    {
        foreach ($this->nestedSetQueries as $query) {
            $query['arguments'][]= $con;
            call_user_func_array($query['callable'], $query['arguments']);
        }
        $this->nestedSetQueries = array();
    }

    /**
     * Proxy getter method for the left value of the nested set model.
     * It provides a generic way to get the value, whatever the actual column name is.
     *
     * @return     int The nested set left value
     */
    public function getLeftValue()
    {
        return $this->tree_left;
    }

    /**
     * Proxy getter method for the right value of the nested set model.
     * It provides a generic way to get the value, whatever the actual column name is.
     *
     * @return     int The nested set right value
     */
    public function getRightValue()
    {
        return $this->tree_right;
    }

    /**
     * Proxy getter method for the level value of the nested set model.
     * It provides a generic way to get the value, whatever the actual column name is.
     *
     * @return     int The nested set level value
     */
    public function getLevel()
    {
        return $this->tree_level;
    }

    /**
     * Proxy getter method for the scope value of the nested set model.
     * It provides a generic way to get the value, whatever the actual column name is.
     *
     * @return     int The nested set scope value
     */
    public function getScopeValue()
    {
        return $this->p_d_debate_id;
    }

    /**
     * Proxy setter method for the left value of the nested set model.
     * It provides a generic way to set the value, whatever the actual column name is.
     *
     * @param      int $v The nested set left value
     * @return     PDReaction The current object (for fluent API support)
     */
    public function setLeftValue($v)
    {
        return $this->setTreeLeft($v);
    }

    /**
     * Proxy setter method for the right value of the nested set model.
     * It provides a generic way to set the value, whatever the actual column name is.
     *
     * @param      int $v The nested set right value
     * @return     PDReaction The current object (for fluent API support)
     */
    public function setRightValue($v)
    {
        return $this->setTreeRight($v);
    }

    /**
     * Proxy setter method for the level value of the nested set model.
     * It provides a generic way to set the value, whatever the actual column name is.
     *
     * @param      int $v The nested set level value
     * @return     PDReaction The current object (for fluent API support)
     */
    public function setLevel($v)
    {
        return $this->setTreeLevel($v);
    }

    /**
     * Proxy setter method for the scope value of the nested set model.
     * It provides a generic way to set the value, whatever the actual column name is.
     *
     * @param      int $v The nested set scope value
     * @return     PDReaction The current object (for fluent API support)
     */
    public function setScopeValue($v)
    {
        return $this->setPDDebateId($v);
    }

    /**
     * Creates the supplied node as the root node.
     *
     * @return     PDReaction The current object (for fluent API support)
     * @throws     PropelException
     */
    public function makeRoot()
    {
        if ($this->getLeftValue() || $this->getRightValue()) {
            throw new PropelException('Cannot turn an existing node into a root node.');
        }

        $this->setLeftValue(1);
        $this->setRightValue(2);
        $this->setLevel(0);

        return $this;
    }

    /**
     * Tests if onbject is a node, i.e. if it is inserted in the tree
     *
     * @return     bool
     */
    public function isInTree()
    {
        return $this->getLeftValue() > 0 && $this->getRightValue() > $this->getLeftValue();
    }

    /**
     * Tests if node is a root
     *
     * @return     bool
     */
    public function isRoot()
    {
        return $this->isInTree() && $this->getLeftValue() == 1;
    }

    /**
     * Tests if node is a leaf
     *
     * @return     bool
     */
    public function isLeaf()
    {
        return $this->isInTree() &&  ($this->getRightValue() - $this->getLeftValue()) == 1;
    }

    /**
     * Tests if node is a descendant of another node
     *
     * @param      PDReaction $node Propel node object
     * @return     bool
     */
    public function isDescendantOf($parent)
    {
        if ($this->getScopeValue() !== $parent->getScopeValue()) {
            return false; //since the `this` and $parent are in different scopes, there's no way that `this` is be a descendant of $parent.
        }

        return $this->isInTree() && $this->getLeftValue() > $parent->getLeftValue() && $this->getRightValue() < $parent->getRightValue();
    }

    /**
     * Tests if node is a ancestor of another node
     *
     * @param      PDReaction $node Propel node object
     * @return     bool
     */
    public function isAncestorOf($child)
    {
        return $child->isDescendantOf($this);
    }

    /**
     * Tests if object has an ancestor
     *
     * @param      PropelPDO $con Connection to use.
     * @return     bool
     */
    public function hasParent(PropelPDO $con = null)
    {
        return $this->getLevel() > 0;
    }

    /**
     * Sets the cache for parent node of the current object.
     * Warning: this does not move the current object in the tree.
     * Use moveTofirstChildOf() or moveToLastChildOf() for that purpose
     *
     * @param      PDReaction $parent
     * @return     PDReaction The current object, for fluid interface
     */
    public function setParent($parent = null)
    {
        $this->aNestedSetParent = $parent;

        return $this;
    }

    /**
     * Gets parent node for the current object if it exists
     * The result is cached so further calls to the same method don't issue any queries
     *
     * @param      PropelPDO $con Connection to use.
     * @return     mixed 		Propel object if exists else false
     */
    public function getParent(PropelPDO $con = null)
    {
        if ($this->aNestedSetParent === null && $this->hasParent()) {
            $this->aNestedSetParent = PDReactionQuery::create()
                ->ancestorsOf($this)
                ->orderByLevel(true)
                ->findOne($con);
        }

        return $this->aNestedSetParent;
    }

    /**
     * Determines if the node has previous sibling
     *
     * @param      PropelPDO $con Connection to use.
     * @return     bool
     */
    public function hasPrevSibling(PropelPDO $con = null)
    {
        if (!PDReactionPeer::isValid($this)) {
            return false;
        }

        return PDReactionQuery::create()
            ->filterByTreeRight($this->getLeftValue() - 1)
            ->inTree($this->getScopeValue())
            ->count($con) > 0;
    }

    /**
     * Gets previous sibling for the given node if it exists
     *
     * @param      PropelPDO $con Connection to use.
     * @return     mixed 		Propel object if exists else false
     */
    public function getPrevSibling(PropelPDO $con = null)
    {
        return PDReactionQuery::create()
            ->filterByTreeRight($this->getLeftValue() - 1)
            ->inTree($this->getScopeValue())
            ->findOne($con);
    }

    /**
     * Determines if the node has next sibling
     *
     * @param      PropelPDO $con Connection to use.
     * @return     bool
     */
    public function hasNextSibling(PropelPDO $con = null)
    {
        if (!PDReactionPeer::isValid($this)) {
            return false;
        }

        return PDReactionQuery::create()
            ->filterByTreeLeft($this->getRightValue() + 1)
            ->inTree($this->getScopeValue())
            ->count($con) > 0;
    }

    /**
     * Gets next sibling for the given node if it exists
     *
     * @param      PropelPDO $con Connection to use.
     * @return     mixed 		Propel object if exists else false
     */
    public function getNextSibling(PropelPDO $con = null)
    {
        return PDReactionQuery::create()
            ->filterByTreeLeft($this->getRightValue() + 1)
            ->inTree($this->getScopeValue())
            ->findOne($con);
    }

    /**
     * Clears out the $collNestedSetChildren collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return     void
     */
    public function clearNestedSetChildren()
    {
        $this->collNestedSetChildren = null;
    }

    /**
     * Initializes the $collNestedSetChildren collection.
     *
     * @return     void
     */
    public function initNestedSetChildren()
    {
        $this->collNestedSetChildren = new PropelObjectCollection();
        $this->collNestedSetChildren->setModel('PDReaction');
    }

    /**
     * Adds an element to the internal $collNestedSetChildren collection.
     * Beware that this doesn't insert a node in the tree.
     * This method is only used to facilitate children hydration.
     *
     * @param      PDReaction $pDReaction
     *
     * @return     void
     */
    public function addNestedSetChild($pDReaction)
    {
        if ($this->collNestedSetChildren === null) {
            $this->initNestedSetChildren();
        }
        if (!in_array($pDReaction, $this->collNestedSetChildren->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->collNestedSetChildren[]= $pDReaction;
            $pDReaction->setParent($this);
        }
    }

    /**
     * Tests if node has children
     *
     * @return     bool
     */
    public function hasChildren()
    {
        return ($this->getRightValue() - $this->getLeftValue()) > 1;
    }

    /**
     * Gets the children of the given node
     *
     * @param      Criteria  $criteria Criteria to filter results.
     * @param      PropelPDO $con Connection to use.
     * @return     array     List of PDReaction objects
     */
    public function getChildren($criteria = null, PropelPDO $con = null)
    {
        if (null === $this->collNestedSetChildren || null !== $criteria) {
            if ($this->isLeaf() || ($this->isNew() && null === $this->collNestedSetChildren)) {
                // return empty collection
                $this->initNestedSetChildren();
            } else {
                $collNestedSetChildren = PDReactionQuery::create(null, $criteria)
                  ->childrenOf($this)
                  ->orderByBranch()
                    ->find($con);
                if (null !== $criteria) {
                    return $collNestedSetChildren;
                }
                $this->collNestedSetChildren = $collNestedSetChildren;
            }
        }

        return $this->collNestedSetChildren;
    }

    /**
     * Gets number of children for the given node
     *
     * @param      Criteria  $criteria Criteria to filter results.
     * @param      PropelPDO $con Connection to use.
     * @return     int       Number of children
     */
    public function countChildren($criteria = null, PropelPDO $con = null)
    {
        if (null === $this->collNestedSetChildren || null !== $criteria) {
            if ($this->isLeaf() || ($this->isNew() && null === $this->collNestedSetChildren)) {
                return 0;
            } else {
                return PDReactionQuery::create(null, $criteria)
                    ->childrenOf($this)
                    ->count($con);
            }
        } else {
            return count($this->collNestedSetChildren);
        }
    }

    /**
     * Gets the first child of the given node
     *
     * @param      Criteria $query Criteria to filter results.
     * @param      PropelPDO $con Connection to use.
     * @return     array 		List of PDReaction objects
     */
    public function getFirstChild($query = null, PropelPDO $con = null)
    {
        if ($this->isLeaf()) {
            return array();
        } else {
            return PDReactionQuery::create(null, $query)
                ->childrenOf($this)
                ->orderByBranch()
                ->findOne($con);
        }
    }

    /**
     * Gets the last child of the given node
     *
     * @param      Criteria $query Criteria to filter results.
     * @param      PropelPDO $con Connection to use.
     * @return     array 		List of PDReaction objects
     */
    public function getLastChild($query = null, PropelPDO $con = null)
    {
        if ($this->isLeaf()) {
            return array();
        } else {
            return PDReactionQuery::create(null, $query)
                ->childrenOf($this)
                ->orderByBranch(true)
                ->findOne($con);
        }
    }

    /**
     * Gets the siblings of the given node
     *
     * @param      bool			$includeNode Whether to include the current node or not
     * @param      Criteria $query Criteria to filter results.
     * @param      PropelPDO $con Connection to use.
     *
     * @return     array 		List of PDReaction objects
     */
    public function getSiblings($includeNode = false, $query = null, PropelPDO $con = null)
    {
        if ($this->isRoot()) {
            return array();
        } else {
             $query = PDReactionQuery::create(null, $query)
                    ->childrenOf($this->getParent($con))
                    ->orderByBranch();
            if (!$includeNode) {
                $query->prune($this);
            }

            return $query->find($con);
        }
    }

    /**
     * Gets descendants for the given node
     *
     * @param      Criteria $query Criteria to filter results.
     * @param      PropelPDO $con Connection to use.
     * @return     array 		List of PDReaction objects
     */
    public function getDescendants($query = null, PropelPDO $con = null)
    {
        if ($this->isLeaf()) {
            return array();
        } else {
            return PDReactionQuery::create(null, $query)
                ->descendantsOf($this)
                ->orderByBranch()
                ->find($con);
        }
    }

    /**
     * Gets number of descendants for the given node
     *
     * @param      Criteria $query Criteria to filter results.
     * @param      PropelPDO $con Connection to use.
     * @return     int 		Number of descendants
     */
    public function countDescendants($query = null, PropelPDO $con = null)
    {
        if ($this->isLeaf()) {
            // save one query
            return 0;
        } else {
            return PDReactionQuery::create(null, $query)
                ->descendantsOf($this)
                ->count($con);
        }
    }

    /**
     * Gets descendants for the given node, plus the current node
     *
     * @param      Criteria $query Criteria to filter results.
     * @param      PropelPDO $con Connection to use.
     * @return     array 		List of PDReaction objects
     */
    public function getBranch($query = null, PropelPDO $con = null)
    {
        return PDReactionQuery::create(null, $query)
            ->branchOf($this)
            ->orderByBranch()
            ->find($con);
    }

    /**
     * Gets ancestors for the given node, starting with the root node
     * Use it for breadcrumb paths for instance
     *
     * @param      Criteria $query Criteria to filter results.
     * @param      PropelPDO $con Connection to use.
     * @return     array 		List of PDReaction objects
     */
    public function getAncestors($query = null, PropelPDO $con = null)
    {
        if ($this->isRoot()) {
            // save one query
            return array();
        } else {
            return PDReactionQuery::create(null, $query)
                ->ancestorsOf($this)
                ->orderByBranch()
                ->find($con);
        }
    }

    /**
     * Inserts the given $child node as first child of current
     * The modifications in the current object and the tree
     * are not persisted until the child object is saved.
     *
     * @param      PDReaction $child	Propel object for child node
     *
     * @return     PDReaction The current Propel object
     */
    public function addChild(PDReaction $child)
    {
        if ($this->isNew()) {
            throw new PropelException('A PDReaction object must not be new to accept children.');
        }
        $child->insertAsFirstChildOf($this);

        return $this;
    }

    /**
     * Inserts the current node as first child of given $parent node
     * The modifications in the current object and the tree
     * are not persisted until the current object is saved.
     *
     * @param      PDReaction $parent	Propel object for parent node
     *
     * @return     PDReaction The current Propel object
     */
    public function insertAsFirstChildOf($parent)
    {
        if ($this->isInTree()) {
            throw new PropelException('A PDReaction object must not already be in the tree to be inserted. Use the moveToFirstChildOf() instead.');
        }
        $left = $parent->getLeftValue() + 1;
        // Update node properties
        $this->setLeftValue($left);
        $this->setRightValue($left + 1);
        $this->setLevel($parent->getLevel() + 1);
        $scope = $parent->getScopeValue();
        $this->setScopeValue($scope);
        // update the children collection of the parent
        $parent->addNestedSetChild($this);

        // Keep the tree modification query for the save() transaction
        $this->nestedSetQueries []= array(
            'callable'  => array('\\Politizr\Model\\PDReactionPeer', 'makeRoomForLeaf'),
            'arguments' => array($left, $scope, $this->isNew() ? null : $this)
        );

        return $this;
    }

    /**
     * Inserts the current node as last child of given $parent node
     * The modifications in the current object and the tree
     * are not persisted until the current object is saved.
     *
     * @param      PDReaction $parent	Propel object for parent node
     *
     * @return     PDReaction The current Propel object
     */
    public function insertAsLastChildOf($parent)
    {
        if ($this->isInTree()) {
            throw new PropelException('A PDReaction object must not already be in the tree to be inserted. Use the moveToLastChildOf() instead.');
        }
        $left = $parent->getRightValue();
        // Update node properties
        $this->setLeftValue($left);
        $this->setRightValue($left + 1);
        $this->setLevel($parent->getLevel() + 1);
        $scope = $parent->getScopeValue();
        $this->setScopeValue($scope);
        // update the children collection of the parent
        $parent->addNestedSetChild($this);

        // Keep the tree modification query for the save() transaction
        $this->nestedSetQueries []= array(
            'callable'  => array('\\Politizr\Model\\PDReactionPeer', 'makeRoomForLeaf'),
            'arguments' => array($left, $scope, $this->isNew() ? null : $this)
        );

        return $this;
    }

    /**
     * Inserts the current node as prev sibling given $sibling node
     * The modifications in the current object and the tree
     * are not persisted until the current object is saved.
     *
     * @param      PDReaction $sibling	Propel object for parent node
     *
     * @return     PDReaction The current Propel object
     */
    public function insertAsPrevSiblingOf($sibling)
    {
        if ($this->isInTree()) {
            throw new PropelException('A PDReaction object must not already be in the tree to be inserted. Use the moveToPrevSiblingOf() instead.');
        }
        $left = $sibling->getLeftValue();
        // Update node properties
        $this->setLeftValue($left);
        $this->setRightValue($left + 1);
        $this->setLevel($sibling->getLevel());
        $scope = $sibling->getScopeValue();
        $this->setScopeValue($scope);
        // Keep the tree modification query for the save() transaction
        $this->nestedSetQueries []= array(
            'callable'  => array('\\Politizr\Model\\PDReactionPeer', 'makeRoomForLeaf'),
            'arguments' => array($left, $scope, $this->isNew() ? null : $this)
        );

        return $this;
    }

    /**
     * Inserts the current node as next sibling given $sibling node
     * The modifications in the current object and the tree
     * are not persisted until the current object is saved.
     *
     * @param      PDReaction $sibling	Propel object for parent node
     *
     * @return     PDReaction The current Propel object
     */
    public function insertAsNextSiblingOf($sibling)
    {
        if ($this->isInTree()) {
            throw new PropelException('A PDReaction object must not already be in the tree to be inserted. Use the moveToNextSiblingOf() instead.');
        }
        $left = $sibling->getRightValue() + 1;
        // Update node properties
        $this->setLeftValue($left);
        $this->setRightValue($left + 1);
        $this->setLevel($sibling->getLevel());
        $scope = $sibling->getScopeValue();
        $this->setScopeValue($scope);
        // Keep the tree modification query for the save() transaction
        $this->nestedSetQueries []= array(
            'callable'  => array('\\Politizr\Model\\PDReactionPeer', 'makeRoomForLeaf'),
            'arguments' => array($left, $scope, $this->isNew() ? null : $this)
        );

        return $this;
    }

    /**
     * Moves current node and its subtree to be the first child of $parent
     * The modifications in the current object and the tree are immediate
     *
     * @param      PDReaction $parent	Propel object for parent node
     * @param      PropelPDO $con	Connection to use.
     *
     * @return     PDReaction The current Propel object
     */
    public function moveToFirstChildOf($parent, PropelPDO $con = null)
    {
        if (!$this->isInTree()) {
            throw new PropelException('A PDReaction object must be already in the tree to be moved. Use the insertAsFirstChildOf() instead.');
        }
        if ($parent->isDescendantOf($this)) {
            throw new PropelException('Cannot move a node as child of one of its subtree nodes.');
        }

        $this->moveSubtreeTo($parent->getLeftValue() + 1, $parent->getLevel() - $this->getLevel() + 1, $parent->getScopeValue(), $con);

        return $this;
    }

    /**
     * Moves current node and its subtree to be the last child of $parent
     * The modifications in the current object and the tree are immediate
     *
     * @param      PDReaction $parent	Propel object for parent node
     * @param      PropelPDO $con	Connection to use.
     *
     * @return     PDReaction The current Propel object
     */
    public function moveToLastChildOf($parent, PropelPDO $con = null)
    {
        if (!$this->isInTree()) {
            throw new PropelException('A PDReaction object must be already in the tree to be moved. Use the insertAsLastChildOf() instead.');
        }
        if ($parent->isDescendantOf($this)) {
            throw new PropelException('Cannot move a node as child of one of its subtree nodes.');
        }

        $this->moveSubtreeTo($parent->getRightValue(), $parent->getLevel() - $this->getLevel() + 1, $parent->getScopeValue(), $con);

        return $this;
    }

    /**
     * Moves current node and its subtree to be the previous sibling of $sibling
     * The modifications in the current object and the tree are immediate
     *
     * @param      PDReaction $sibling	Propel object for sibling node
     * @param      PropelPDO $con	Connection to use.
     *
     * @return     PDReaction The current Propel object
     */
    public function moveToPrevSiblingOf($sibling, PropelPDO $con = null)
    {
        if (!$this->isInTree()) {
            throw new PropelException('A PDReaction object must be already in the tree to be moved. Use the insertAsPrevSiblingOf() instead.');
        }
        if ($sibling->isRoot()) {
            throw new PropelException('Cannot move to previous sibling of a root node.');
        }
        if ($sibling->isDescendantOf($this)) {
            throw new PropelException('Cannot move a node as sibling of one of its subtree nodes.');
        }

        $this->moveSubtreeTo($sibling->getLeftValue(), $sibling->getLevel() - $this->getLevel(), $sibling->getScopeValue(), $con);

        return $this;
    }

    /**
     * Moves current node and its subtree to be the next sibling of $sibling
     * The modifications in the current object and the tree are immediate
     *
     * @param      PDReaction $sibling	Propel object for sibling node
     * @param      PropelPDO $con	Connection to use.
     *
     * @return     PDReaction The current Propel object
     */
    public function moveToNextSiblingOf($sibling, PropelPDO $con = null)
    {
        if (!$this->isInTree()) {
            throw new PropelException('A PDReaction object must be already in the tree to be moved. Use the insertAsNextSiblingOf() instead.');
        }
        if ($sibling->isRoot()) {
            throw new PropelException('Cannot move to next sibling of a root node.');
        }
        if ($sibling->isDescendantOf($this)) {
            throw new PropelException('Cannot move a node as sibling of one of its subtree nodes.');
        }

        $this->moveSubtreeTo($sibling->getRightValue() + 1, $sibling->getLevel() - $this->getLevel(), $sibling->getScopeValue(), $con);

        return $this;
    }

    /**
     * Move current node and its children to location $destLeft and updates rest of tree
     *
     * @param      int	$destLeft Destination left value
     * @param      int	$levelDelta Delta to add to the levels
     * @param      PropelPDO $con		Connection to use.
     */
    protected function moveSubtreeTo($destLeft, $levelDelta, $targetScope = null, PropelPDO $con = null)
    {
        $preventDefault = false;
        $left  = $this->getLeftValue();
        $right = $this->getRightValue();
        $scope = $this->getScopeValue();

        if ($targetScope === null){
            $targetScope = $scope;
        }


        $treeSize = $right - $left +1;

        if ($con === null) {
            $con = Propel::getConnection(PDReactionPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        try {

            // make room next to the target for the subtree
            PDReactionPeer::shiftRLValues($treeSize, $destLeft, null, $targetScope, $con);



            if ($targetScope != $scope){

                //move subtree to < 0, so the items are out of scope.
                PDReactionPeer::shiftRLValues(-$right, $left, $right, $scope, $con);

                //update scopes
                PDReactionPeer::setNegativeScope($targetScope, $con);

                //update levels
                PDReactionPeer::shiftLevel($levelDelta, $left - $right, 0, $targetScope, $con);

                //move the subtree to the target
                PDReactionPeer::shiftRLValues(($right - $left) + $destLeft, $left - $right, 0, $targetScope, $con);


                $preventDefault = true;
            }


            if (!$preventDefault){


                if ($left >= $destLeft) { // src was shifted too?
                    $left += $treeSize;
                    $right += $treeSize;
                }

                if ($levelDelta) {
                    // update the levels of the subtree
                    PDReactionPeer::shiftLevel($levelDelta, $left, $right, $scope, $con);
                }

                // move the subtree to the target
                PDReactionPeer::shiftRLValues($destLeft - $left, $left, $right, $scope, $con);
            }

            // remove the empty room at the previous location of the subtree
            PDReactionPeer::shiftRLValues(-$treeSize, $right + 1, null, $scope, $con);

            // update all loaded nodes
            PDReactionPeer::updateLoadedNodes(null, $con);

            $con->commit();
        } catch (PropelException $e) {
            $con->rollback();
            throw $e;
        }
    }

    /**
     * Deletes all descendants for the given node
     * Instance pooling is wiped out by this command,
     * so existing PDReaction instances are probably invalid (except for the current one)
     *
     * @param      PropelPDO $con Connection to use.
     *
     * @return     int 		number of deleted nodes
     */
    public function deleteDescendants(PropelPDO $con = null)
    {
        if ($this->isLeaf()) {
            // save one query
            return;
        }
        if ($con === null) {
            $con = Propel::getConnection(PDReactionPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }
        $left = $this->getLeftValue();
        $right = $this->getRightValue();
        $scope = $this->getScopeValue();
        $con->beginTransaction();
        try {
            // delete descendant nodes (will empty the instance pool)
            $ret = PDReactionQuery::create()
                ->descendantsOf($this)
                ->delete($con);

            // fill up the room that was used by descendants
            PDReactionPeer::shiftRLValues($left - $right + 1, $right, null, $scope, $con);

            // fix the right value for the current node, which is now a leaf
            $this->setRightValue($left + 1);

            $con->commit();
        } catch (Exception $e) {
            $con->rollback();
            throw $e;
        }

        return $ret;
    }

    /**
     * Returns a pre-order iterator for this node and its children.
     *
     * @return     RecursiveIterator
     */
    public function getIterator()
    {
        return new NestedSetRecursiveIterator($this);
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
                $parent->setDescendantClass('Politizr\Model\PDReaction');

                return $parent;
            } else {
                $parent = PDocumentQuery::create()->findPk($this->getPrimaryKey(), $con);
                if (null === $parent || null !== $parent->getDescendantClass()) {
                    $parent = new PDocument();
                    $parent->setPrimaryKey($this->getPrimaryKey());
                    $parent->setDescendantClass('Politizr\Model\PDReaction');
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
        $parent->setSummary($this->getSummary());
        $parent->setDescription($this->getDescription());
        $parent->setMoreInfo($this->getMoreInfo());
        $parent->setNotePos($this->getNotePos());
        $parent->setNoteNeg($this->getNoteNeg());
        $parent->setPublished($this->getPublished());
        $parent->setPublishedAt($this->getPublishedAt());
        $parent->setPublishedBy($this->getPublishedBy());
        $parent->setOnline($this->getOnline());
        if ($this->getPUser() && $this->getPUser()->isNew()) {
            $parent->setPUser($this->getPUser());
        }

        return $parent;
    }

    // archivable behavior

    /**
     * Get an archived version of the current object.
     *
     * @param PropelPDO $con Optional connection object
     *
     * @return     PDReactionArchive An archive object, or null if the current object was never archived
     */
    public function getArchive(PropelPDO $con = null)
    {
        if ($this->isNew()) {
            return null;
        }
        $archive = PDReactionArchiveQuery::create()
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
     * @return     PDReactionArchive The archive object based on this object
     */
    public function archive(PropelPDO $con = null)
    {
        if ($this->isNew()) {
            throw new PropelException('New objects cannot be archived. You must save the current object before calling archive().');
        }
        if (!$archive = $this->getArchive($con)) {
            $archive = new PDReactionArchive();
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
     * @return PDReaction The current object (for fluent API support)
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
     * @param      PDReactionArchive $archive An archived object based on the same class
      * @param      Boolean $populateAutoIncrementPrimaryKeys Not used! Defined to comply with php strict standards
      *
     * @return     PDReaction The current object (for fluent API support)
     */
    public function populateFromArchive($archive, $populateAutoIncrementPrimaryKeys = false) {
        $this->setPDDebateId($archive->getPDDebateId());
        $this->setCreatedAt($archive->getCreatedAt());
        $this->setUpdatedAt($archive->getUpdatedAt());
        $this->setSlug($archive->getSlug());
        $this->setTreeLeft($archive->getTreeLeft());
        $this->setTreeRight($archive->getTreeRight());
        $this->setTreeLevel($archive->getTreeLevel());
        $this->setId($archive->getId());
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

        return $this;
    }

    /**
     * Removes the object from the database without archiving it.
     *
     * @param PropelPDO $con Optional connection object
     *
     * @return     PDReaction The current object (for fluent API support)
     */
    public function deleteWithoutArchive(PropelPDO $con = null)
    {
        $this->archiveOnDelete = false;

        return $this->delete($con);
    }

}
