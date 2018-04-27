<?php

namespace Politizr\Model\om;

use \BaseObject;
use \BasePeer;
use \Criteria;
use \DateTime;
use \Exception;
use \NestedSetRecursiveIterator;
use \PDO;
use \Persistent;
use \Propel;
use \PropelCollection;
use \PropelDateTime;
use \PropelException;
use \PropelObjectCollection;
use \PropelPDO;
use Politizr\Model\PCTopic;
use Politizr\Model\PCTopicQuery;
use Politizr\Model\PDDebate;
use Politizr\Model\PDDebateQuery;
use Politizr\Model\PDMedia;
use Politizr\Model\PDMediaQuery;
use Politizr\Model\PDRComment;
use Politizr\Model\PDRCommentQuery;
use Politizr\Model\PDRTaggedT;
use Politizr\Model\PDRTaggedTQuery;
use Politizr\Model\PDReaction;
use Politizr\Model\PDReactionArchive;
use Politizr\Model\PDReactionArchiveQuery;
use Politizr\Model\PDReactionPeer;
use Politizr\Model\PDReactionQuery;
use Politizr\Model\PLCity;
use Politizr\Model\PLCityQuery;
use Politizr\Model\PLCountry;
use Politizr\Model\PLCountryQuery;
use Politizr\Model\PLDepartment;
use Politizr\Model\PLDepartmentQuery;
use Politizr\Model\PLRegion;
use Politizr\Model\PLRegionQuery;
use Politizr\Model\PMReactionHistoric;
use Politizr\Model\PMReactionHistoricQuery;
use Politizr\Model\PTag;
use Politizr\Model\PTagQuery;
use Politizr\Model\PUBookmarkDR;
use Politizr\Model\PUBookmarkDRQuery;
use Politizr\Model\PUTrackDR;
use Politizr\Model\PUTrackDRQuery;
use Politizr\Model\PUser;
use Politizr\Model\PUserQuery;

abstract class BasePDReaction extends BaseObject implements Persistent
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
     * The value for the parent_reaction_id field.
     * @var        int
     */
    protected $parent_reaction_id;

    /**
     * The value for the p_l_city_id field.
     * @var        int
     */
    protected $p_l_city_id;

    /**
     * The value for the p_l_department_id field.
     * @var        int
     */
    protected $p_l_department_id;

    /**
     * The value for the p_l_region_id field.
     * @var        int
     */
    protected $p_l_region_id;

    /**
     * The value for the p_l_country_id field.
     * @var        int
     */
    protected $p_l_country_id;

    /**
     * The value for the p_c_topic_id field.
     * @var        int
     */
    protected $p_c_topic_id;

    /**
     * The value for the fb_ad_id field.
     * @var        string
     */
    protected $fb_ad_id;

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
     * The value for the want_boost field.
     * Note: this column has a database default value of: 0
     * @var        int
     */
    protected $want_boost;

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
     * The value for the homepage field.
     * @var        boolean
     */
    protected $homepage;

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
     * The value for the indexed_at field.
     * @var        string
     */
    protected $indexed_at;

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
     * @var        PUser
     */
    protected $aPUser;

    /**
     * @var        PDDebate
     */
    protected $aPDDebate;

    /**
     * @var        PLCity
     */
    protected $aPLCity;

    /**
     * @var        PLDepartment
     */
    protected $aPLDepartment;

    /**
     * @var        PLRegion
     */
    protected $aPLRegion;

    /**
     * @var        PLCountry
     */
    protected $aPLCountry;

    /**
     * @var        PCTopic
     */
    protected $aPCTopic;

    /**
     * @var        PropelObjectCollection|PUBookmarkDR[] Collection to store aggregation of PUBookmarkDR objects.
     */
    protected $collPuBookmarkDrPDReactions;
    protected $collPuBookmarkDrPDReactionsPartial;

    /**
     * @var        PropelObjectCollection|PUTrackDR[] Collection to store aggregation of PUTrackDR objects.
     */
    protected $collPuTrackDrPDReactions;
    protected $collPuTrackDrPDReactionsPartial;

    /**
     * @var        PropelObjectCollection|PDRComment[] Collection to store aggregation of PDRComment objects.
     */
    protected $collPDRComments;
    protected $collPDRCommentsPartial;

    /**
     * @var        PropelObjectCollection|PDRTaggedT[] Collection to store aggregation of PDRTaggedT objects.
     */
    protected $collPDRTaggedTs;
    protected $collPDRTaggedTsPartial;

    /**
     * @var        PropelObjectCollection|PDMedia[] Collection to store aggregation of PDMedia objects.
     */
    protected $collPDMedias;
    protected $collPDMediasPartial;

    /**
     * @var        PropelObjectCollection|PMReactionHistoric[] Collection to store aggregation of PMReactionHistoric objects.
     */
    protected $collPMReactionHistorics;
    protected $collPMReactionHistoricsPartial;

    /**
     * @var        PropelObjectCollection|PUser[] Collection to store aggregation of PUser objects.
     */
    protected $collPuBookmarkDrPUsers;

    /**
     * @var        PropelObjectCollection|PUser[] Collection to store aggregation of PUser objects.
     */
    protected $collPuTrackDrPUsers;

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
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $puBookmarkDrPUsersScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $puTrackDrPUsersScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $pTagsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $puBookmarkDrPDReactionsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $puTrackDrPDReactionsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $pDRCommentsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $pDRTaggedTsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $pDMediasScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $pMReactionHistoricsScheduledForDeletion = null;

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
        $this->want_boost = 0;
    }

    /**
     * Initializes internal state of BasePDReaction object.
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
     * Get the [p_d_debate_id] column value.
     *
     * @return int
     */
    public function getPDDebateId()
    {

        return $this->p_d_debate_id;
    }

    /**
     * Get the [parent_reaction_id] column value.
     *
     * @return int
     */
    public function getParentReactionId()
    {

        return $this->parent_reaction_id;
    }

    /**
     * Get the [p_l_city_id] column value.
     *
     * @return int
     */
    public function getPLCityId()
    {

        return $this->p_l_city_id;
    }

    /**
     * Get the [p_l_department_id] column value.
     *
     * @return int
     */
    public function getPLDepartmentId()
    {

        return $this->p_l_department_id;
    }

    /**
     * Get the [p_l_region_id] column value.
     *
     * @return int
     */
    public function getPLRegionId()
    {

        return $this->p_l_region_id;
    }

    /**
     * Get the [p_l_country_id] column value.
     *
     * @return int
     */
    public function getPLCountryId()
    {

        return $this->p_l_country_id;
    }

    /**
     * Get the [p_c_topic_id] column value.
     *
     * @return int
     */
    public function getPCTopicId()
    {

        return $this->p_c_topic_id;
    }

    /**
     * Get the [fb_ad_id] column value.
     *
     * @return string
     */
    public function getFbAdId()
    {

        return $this->fb_ad_id;
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
     * Get the [want_boost] column value.
     *
     * @return int
     */
    public function getWantBoost()
    {

        return $this->want_boost;
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
     * Get the [homepage] column value.
     *
     * @return boolean
     */
    public function getHomepage()
    {

        return $this->homepage;
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
     * Get the [optionally formatted] temporal [indexed_at] column value.
     *
     *
     * @param string $format The date/time format string (either date()-style or strftime()-style).
     *				 If format is null, then the raw DateTime object will be returned.
     * @return mixed Formatted date/time value as string or DateTime object (if format is null), null if column is null, and 0 if column value is 0000-00-00 00:00:00
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getIndexedAt($format = null)
    {
        if ($this->indexed_at === null) {
            return null;
        }

        if ($this->indexed_at === '0000-00-00 00:00:00') {
            // while technically this is not a default value of null,
            // this seems to be closest in meaning.
            return null;
        }

        try {
            $dt = new DateTime($this->indexed_at);
        } catch (Exception $x) {
            throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->indexed_at, true), $x);
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
     * Set the value of [id] column.
     *
     * @param  int $v new value
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


        return $this;
    } // setId()

    /**
     * Set the value of [uuid] column.
     *
     * @param  string $v new value
     * @return PDReaction The current object (for fluent API support)
     */
    public function setUuid($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->uuid !== $v) {
            $this->uuid = $v;
            $this->modifiedColumns[] = PDReactionPeer::UUID;
        }


        return $this;
    } // setUuid()

    /**
     * Set the value of [p_user_id] column.
     *
     * @param  int $v new value
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
     * Set the value of [p_d_debate_id] column.
     *
     * @param  int $v new value
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
     * Set the value of [parent_reaction_id] column.
     *
     * @param  int $v new value
     * @return PDReaction The current object (for fluent API support)
     */
    public function setParentReactionId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->parent_reaction_id !== $v) {
            $this->parent_reaction_id = $v;
            $this->modifiedColumns[] = PDReactionPeer::PARENT_REACTION_ID;
        }


        return $this;
    } // setParentReactionId()

    /**
     * Set the value of [p_l_city_id] column.
     *
     * @param  int $v new value
     * @return PDReaction The current object (for fluent API support)
     */
    public function setPLCityId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->p_l_city_id !== $v) {
            $this->p_l_city_id = $v;
            $this->modifiedColumns[] = PDReactionPeer::P_L_CITY_ID;
        }

        if ($this->aPLCity !== null && $this->aPLCity->getId() !== $v) {
            $this->aPLCity = null;
        }


        return $this;
    } // setPLCityId()

    /**
     * Set the value of [p_l_department_id] column.
     *
     * @param  int $v new value
     * @return PDReaction The current object (for fluent API support)
     */
    public function setPLDepartmentId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->p_l_department_id !== $v) {
            $this->p_l_department_id = $v;
            $this->modifiedColumns[] = PDReactionPeer::P_L_DEPARTMENT_ID;
        }

        if ($this->aPLDepartment !== null && $this->aPLDepartment->getId() !== $v) {
            $this->aPLDepartment = null;
        }


        return $this;
    } // setPLDepartmentId()

    /**
     * Set the value of [p_l_region_id] column.
     *
     * @param  int $v new value
     * @return PDReaction The current object (for fluent API support)
     */
    public function setPLRegionId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->p_l_region_id !== $v) {
            $this->p_l_region_id = $v;
            $this->modifiedColumns[] = PDReactionPeer::P_L_REGION_ID;
        }

        if ($this->aPLRegion !== null && $this->aPLRegion->getId() !== $v) {
            $this->aPLRegion = null;
        }


        return $this;
    } // setPLRegionId()

    /**
     * Set the value of [p_l_country_id] column.
     *
     * @param  int $v new value
     * @return PDReaction The current object (for fluent API support)
     */
    public function setPLCountryId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->p_l_country_id !== $v) {
            $this->p_l_country_id = $v;
            $this->modifiedColumns[] = PDReactionPeer::P_L_COUNTRY_ID;
        }

        if ($this->aPLCountry !== null && $this->aPLCountry->getId() !== $v) {
            $this->aPLCountry = null;
        }


        return $this;
    } // setPLCountryId()

    /**
     * Set the value of [p_c_topic_id] column.
     *
     * @param  int $v new value
     * @return PDReaction The current object (for fluent API support)
     */
    public function setPCTopicId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->p_c_topic_id !== $v) {
            $this->p_c_topic_id = $v;
            $this->modifiedColumns[] = PDReactionPeer::P_C_TOPIC_ID;
        }

        if ($this->aPCTopic !== null && $this->aPCTopic->getId() !== $v) {
            $this->aPCTopic = null;
        }


        return $this;
    } // setPCTopicId()

    /**
     * Set the value of [fb_ad_id] column.
     *
     * @param  string $v new value
     * @return PDReaction The current object (for fluent API support)
     */
    public function setFbAdId($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->fb_ad_id !== $v) {
            $this->fb_ad_id = $v;
            $this->modifiedColumns[] = PDReactionPeer::FB_AD_ID;
        }


        return $this;
    } // setFbAdId()

    /**
     * Set the value of [title] column.
     *
     * @param  string $v new value
     * @return PDReaction The current object (for fluent API support)
     */
    public function setTitle($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->title !== $v) {
            $this->title = $v;
            $this->modifiedColumns[] = PDReactionPeer::TITLE;
        }


        return $this;
    } // setTitle()

    /**
     * Set the value of [file_name] column.
     *
     * @param  string $v new value
     * @return PDReaction The current object (for fluent API support)
     */
    public function setFileName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->file_name !== $v) {
            $this->file_name = $v;
            $this->modifiedColumns[] = PDReactionPeer::FILE_NAME;
        }


        return $this;
    } // setFileName()

    /**
     * Set the value of [copyright] column.
     *
     * @param  string $v new value
     * @return PDReaction The current object (for fluent API support)
     */
    public function setCopyright($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->copyright !== $v) {
            $this->copyright = $v;
            $this->modifiedColumns[] = PDReactionPeer::COPYRIGHT;
        }


        return $this;
    } // setCopyright()

    /**
     * Set the value of [description] column.
     *
     * @param  string $v new value
     * @return PDReaction The current object (for fluent API support)
     */
    public function setDescription($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->description !== $v) {
            $this->description = $v;
            $this->modifiedColumns[] = PDReactionPeer::DESCRIPTION;
        }


        return $this;
    } // setDescription()

    /**
     * Set the value of [note_pos] column.
     *
     * @param  int $v new value
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
     * @param  int $v new value
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
     * Set the value of [nb_views] column.
     *
     * @param  int $v new value
     * @return PDReaction The current object (for fluent API support)
     */
    public function setNbViews($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->nb_views !== $v) {
            $this->nb_views = $v;
            $this->modifiedColumns[] = PDReactionPeer::NB_VIEWS;
        }


        return $this;
    } // setNbViews()

    /**
     * Set the value of [want_boost] column.
     *
     * @param  int $v new value
     * @return PDReaction The current object (for fluent API support)
     */
    public function setWantBoost($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->want_boost !== $v) {
            $this->want_boost = $v;
            $this->modifiedColumns[] = PDReactionPeer::WANT_BOOST;
        }


        return $this;
    } // setWantBoost()

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
     * @param  string $v new value
     * @return PDReaction The current object (for fluent API support)
     */
    public function setPublishedBy($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->published_by !== $v) {
            $this->published_by = $v;
            $this->modifiedColumns[] = PDReactionPeer::PUBLISHED_BY;
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
     * @return PDReaction The current object (for fluent API support)
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
            $this->modifiedColumns[] = PDReactionPeer::FAVORITE;
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
     * Sets the value of the [homepage] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param boolean|integer|string $v The new value
     * @return PDReaction The current object (for fluent API support)
     */
    public function setHomepage($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->homepage !== $v) {
            $this->homepage = $v;
            $this->modifiedColumns[] = PDReactionPeer::HOMEPAGE;
        }


        return $this;
    } // setHomepage()

    /**
     * Sets the value of the [moderated] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param boolean|integer|string $v The new value
     * @return PDReaction The current object (for fluent API support)
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
            $this->modifiedColumns[] = PDReactionPeer::MODERATED;
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
     * @return PDReaction The current object (for fluent API support)
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
            $this->modifiedColumns[] = PDReactionPeer::MODERATED_PARTIAL;
        }


        return $this;
    } // setModeratedPartial()

    /**
     * Sets the value of [moderated_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return PDReaction The current object (for fluent API support)
     */
    public function setModeratedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->moderated_at !== null || $dt !== null) {
            $currentDateAsString = ($this->moderated_at !== null && $tmpDt = new DateTime($this->moderated_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->moderated_at = $newDateAsString;
                $this->modifiedColumns[] = PDReactionPeer::MODERATED_AT;
            }
        } // if either are not null


        return $this;
    } // setModeratedAt()

    /**
     * Sets the value of [indexed_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return PDReaction The current object (for fluent API support)
     */
    public function setIndexedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->indexed_at !== null || $dt !== null) {
            $currentDateAsString = ($this->indexed_at !== null && $tmpDt = new DateTime($this->indexed_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->indexed_at = $newDateAsString;
                $this->modifiedColumns[] = PDReactionPeer::INDEXED_AT;
            }
        } // if either are not null


        return $this;
    } // setIndexedAt()

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
     * @param  string $v new value
     * @return PDReaction The current object (for fluent API support)
     */
    public function setSlug($v)
    {
        if ($v !== null) {
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
     * @param  int $v new value
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
     * @param  int $v new value
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
     * @param  int $v new value
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

            if ($this->want_boost !== 0) {
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
            $this->parent_reaction_id = ($row[$startcol + 4] !== null) ? (int) $row[$startcol + 4] : null;
            $this->p_l_city_id = ($row[$startcol + 5] !== null) ? (int) $row[$startcol + 5] : null;
            $this->p_l_department_id = ($row[$startcol + 6] !== null) ? (int) $row[$startcol + 6] : null;
            $this->p_l_region_id = ($row[$startcol + 7] !== null) ? (int) $row[$startcol + 7] : null;
            $this->p_l_country_id = ($row[$startcol + 8] !== null) ? (int) $row[$startcol + 8] : null;
            $this->p_c_topic_id = ($row[$startcol + 9] !== null) ? (int) $row[$startcol + 9] : null;
            $this->fb_ad_id = ($row[$startcol + 10] !== null) ? (string) $row[$startcol + 10] : null;
            $this->title = ($row[$startcol + 11] !== null) ? (string) $row[$startcol + 11] : null;
            $this->file_name = ($row[$startcol + 12] !== null) ? (string) $row[$startcol + 12] : null;
            $this->copyright = ($row[$startcol + 13] !== null) ? (string) $row[$startcol + 13] : null;
            $this->description = ($row[$startcol + 14] !== null) ? (string) $row[$startcol + 14] : null;
            $this->note_pos = ($row[$startcol + 15] !== null) ? (int) $row[$startcol + 15] : null;
            $this->note_neg = ($row[$startcol + 16] !== null) ? (int) $row[$startcol + 16] : null;
            $this->nb_views = ($row[$startcol + 17] !== null) ? (int) $row[$startcol + 17] : null;
            $this->want_boost = ($row[$startcol + 18] !== null) ? (int) $row[$startcol + 18] : null;
            $this->published = ($row[$startcol + 19] !== null) ? (boolean) $row[$startcol + 19] : null;
            $this->published_at = ($row[$startcol + 20] !== null) ? (string) $row[$startcol + 20] : null;
            $this->published_by = ($row[$startcol + 21] !== null) ? (string) $row[$startcol + 21] : null;
            $this->favorite = ($row[$startcol + 22] !== null) ? (boolean) $row[$startcol + 22] : null;
            $this->online = ($row[$startcol + 23] !== null) ? (boolean) $row[$startcol + 23] : null;
            $this->homepage = ($row[$startcol + 24] !== null) ? (boolean) $row[$startcol + 24] : null;
            $this->moderated = ($row[$startcol + 25] !== null) ? (boolean) $row[$startcol + 25] : null;
            $this->moderated_partial = ($row[$startcol + 26] !== null) ? (boolean) $row[$startcol + 26] : null;
            $this->moderated_at = ($row[$startcol + 27] !== null) ? (string) $row[$startcol + 27] : null;
            $this->indexed_at = ($row[$startcol + 28] !== null) ? (string) $row[$startcol + 28] : null;
            $this->created_at = ($row[$startcol + 29] !== null) ? (string) $row[$startcol + 29] : null;
            $this->updated_at = ($row[$startcol + 30] !== null) ? (string) $row[$startcol + 30] : null;
            $this->slug = ($row[$startcol + 31] !== null) ? (string) $row[$startcol + 31] : null;
            $this->tree_left = ($row[$startcol + 32] !== null) ? (int) $row[$startcol + 32] : null;
            $this->tree_right = ($row[$startcol + 33] !== null) ? (int) $row[$startcol + 33] : null;
            $this->tree_level = ($row[$startcol + 34] !== null) ? (int) $row[$startcol + 34] : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }
            $this->postHydrate($row, $startcol, $rehydrate);

            return $startcol + 35; // 35 = PDReactionPeer::NUM_HYDRATE_COLUMNS.

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

        if ($this->aPUser !== null && $this->p_user_id !== $this->aPUser->getId()) {
            $this->aPUser = null;
        }
        if ($this->aPDDebate !== null && $this->p_d_debate_id !== $this->aPDDebate->getId()) {
            $this->aPDDebate = null;
        }
        if ($this->aPLCity !== null && $this->p_l_city_id !== $this->aPLCity->getId()) {
            $this->aPLCity = null;
        }
        if ($this->aPLDepartment !== null && $this->p_l_department_id !== $this->aPLDepartment->getId()) {
            $this->aPLDepartment = null;
        }
        if ($this->aPLRegion !== null && $this->p_l_region_id !== $this->aPLRegion->getId()) {
            $this->aPLRegion = null;
        }
        if ($this->aPLCountry !== null && $this->p_l_country_id !== $this->aPLCountry->getId()) {
            $this->aPLCountry = null;
        }
        if ($this->aPCTopic !== null && $this->p_c_topic_id !== $this->aPCTopic->getId()) {
            $this->aPCTopic = null;
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

            $this->aPUser = null;
            $this->aPDDebate = null;
            $this->aPLCity = null;
            $this->aPLDepartment = null;
            $this->aPLRegion = null;
            $this->aPLCountry = null;
            $this->aPCTopic = null;
            $this->collPuBookmarkDrPDReactions = null;

            $this->collPuTrackDrPDReactions = null;

            $this->collPDRComments = null;

            $this->collPDRTaggedTs = null;

            $this->collPDMedias = null;

            $this->collPMReactionHistorics = null;

            $this->collPuBookmarkDrPUsers = null;
            $this->collPuTrackDrPUsers = null;
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

            if ($this->aPLCity !== null) {
                if ($this->aPLCity->isModified() || $this->aPLCity->isNew()) {
                    $affectedRows += $this->aPLCity->save($con);
                }
                $this->setPLCity($this->aPLCity);
            }

            if ($this->aPLDepartment !== null) {
                if ($this->aPLDepartment->isModified() || $this->aPLDepartment->isNew()) {
                    $affectedRows += $this->aPLDepartment->save($con);
                }
                $this->setPLDepartment($this->aPLDepartment);
            }

            if ($this->aPLRegion !== null) {
                if ($this->aPLRegion->isModified() || $this->aPLRegion->isNew()) {
                    $affectedRows += $this->aPLRegion->save($con);
                }
                $this->setPLRegion($this->aPLRegion);
            }

            if ($this->aPLCountry !== null) {
                if ($this->aPLCountry->isModified() || $this->aPLCountry->isNew()) {
                    $affectedRows += $this->aPLCountry->save($con);
                }
                $this->setPLCountry($this->aPLCountry);
            }

            if ($this->aPCTopic !== null) {
                if ($this->aPCTopic->isModified() || $this->aPCTopic->isNew()) {
                    $affectedRows += $this->aPCTopic->save($con);
                }
                $this->setPCTopic($this->aPCTopic);
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

            if ($this->puBookmarkDrPUsersScheduledForDeletion !== null) {
                if (!$this->puBookmarkDrPUsersScheduledForDeletion->isEmpty()) {
                    $pks = array();
                    $pk = $this->getPrimaryKey();
                    foreach ($this->puBookmarkDrPUsersScheduledForDeletion->getPrimaryKeys(false) as $remotePk) {
                        $pks[] = array($remotePk, $pk);
                    }
                    PUBookmarkDRQuery::create()
                        ->filterByPrimaryKeys($pks)
                        ->delete($con);
                    $this->puBookmarkDrPUsersScheduledForDeletion = null;
                }

                foreach ($this->getPuBookmarkDrPUsers() as $puBookmarkDrPUser) {
                    if ($puBookmarkDrPUser->isModified()) {
                        $puBookmarkDrPUser->save($con);
                    }
                }
            } elseif ($this->collPuBookmarkDrPUsers) {
                foreach ($this->collPuBookmarkDrPUsers as $puBookmarkDrPUser) {
                    if ($puBookmarkDrPUser->isModified()) {
                        $puBookmarkDrPUser->save($con);
                    }
                }
            }

            if ($this->puTrackDrPUsersScheduledForDeletion !== null) {
                if (!$this->puTrackDrPUsersScheduledForDeletion->isEmpty()) {
                    $pks = array();
                    $pk = $this->getPrimaryKey();
                    foreach ($this->puTrackDrPUsersScheduledForDeletion->getPrimaryKeys(false) as $remotePk) {
                        $pks[] = array($remotePk, $pk);
                    }
                    PUTrackDRQuery::create()
                        ->filterByPrimaryKeys($pks)
                        ->delete($con);
                    $this->puTrackDrPUsersScheduledForDeletion = null;
                }

                foreach ($this->getPuTrackDrPUsers() as $puTrackDrPUser) {
                    if ($puTrackDrPUser->isModified()) {
                        $puTrackDrPUser->save($con);
                    }
                }
            } elseif ($this->collPuTrackDrPUsers) {
                foreach ($this->collPuTrackDrPUsers as $puTrackDrPUser) {
                    if ($puTrackDrPUser->isModified()) {
                        $puTrackDrPUser->save($con);
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
                    PDRTaggedTQuery::create()
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

            if ($this->puBookmarkDrPDReactionsScheduledForDeletion !== null) {
                if (!$this->puBookmarkDrPDReactionsScheduledForDeletion->isEmpty()) {
                    PUBookmarkDRQuery::create()
                        ->filterByPrimaryKeys($this->puBookmarkDrPDReactionsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->puBookmarkDrPDReactionsScheduledForDeletion = null;
                }
            }

            if ($this->collPuBookmarkDrPDReactions !== null) {
                foreach ($this->collPuBookmarkDrPDReactions as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->puTrackDrPDReactionsScheduledForDeletion !== null) {
                if (!$this->puTrackDrPDReactionsScheduledForDeletion->isEmpty()) {
                    PUTrackDRQuery::create()
                        ->filterByPrimaryKeys($this->puTrackDrPDReactionsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->puTrackDrPDReactionsScheduledForDeletion = null;
                }
            }

            if ($this->collPuTrackDrPDReactions !== null) {
                foreach ($this->collPuTrackDrPDReactions as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->pDRCommentsScheduledForDeletion !== null) {
                if (!$this->pDRCommentsScheduledForDeletion->isEmpty()) {
                    PDRCommentQuery::create()
                        ->filterByPrimaryKeys($this->pDRCommentsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->pDRCommentsScheduledForDeletion = null;
                }
            }

            if ($this->collPDRComments !== null) {
                foreach ($this->collPDRComments as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->pDRTaggedTsScheduledForDeletion !== null) {
                if (!$this->pDRTaggedTsScheduledForDeletion->isEmpty()) {
                    PDRTaggedTQuery::create()
                        ->filterByPrimaryKeys($this->pDRTaggedTsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->pDRTaggedTsScheduledForDeletion = null;
                }
            }

            if ($this->collPDRTaggedTs !== null) {
                foreach ($this->collPDRTaggedTs as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->pDMediasScheduledForDeletion !== null) {
                if (!$this->pDMediasScheduledForDeletion->isEmpty()) {
                    PDMediaQuery::create()
                        ->filterByPrimaryKeys($this->pDMediasScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->pDMediasScheduledForDeletion = null;
                }
            }

            if ($this->collPDMedias !== null) {
                foreach ($this->collPDMedias as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->pMReactionHistoricsScheduledForDeletion !== null) {
                if (!$this->pMReactionHistoricsScheduledForDeletion->isEmpty()) {
                    foreach ($this->pMReactionHistoricsScheduledForDeletion as $pMReactionHistoric) {
                        // need to save related object because we set the relation to null
                        $pMReactionHistoric->save($con);
                    }
                    $this->pMReactionHistoricsScheduledForDeletion = null;
                }
            }

            if ($this->collPMReactionHistorics !== null) {
                foreach ($this->collPMReactionHistorics as $referrerFK) {
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

        $this->modifiedColumns[] = PDReactionPeer::ID;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . PDReactionPeer::ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(PDReactionPeer::ID)) {
            $modifiedColumns[':p' . $index++]  = '`id`';
        }
        if ($this->isColumnModified(PDReactionPeer::UUID)) {
            $modifiedColumns[':p' . $index++]  = '`uuid`';
        }
        if ($this->isColumnModified(PDReactionPeer::P_USER_ID)) {
            $modifiedColumns[':p' . $index++]  = '`p_user_id`';
        }
        if ($this->isColumnModified(PDReactionPeer::P_D_DEBATE_ID)) {
            $modifiedColumns[':p' . $index++]  = '`p_d_debate_id`';
        }
        if ($this->isColumnModified(PDReactionPeer::PARENT_REACTION_ID)) {
            $modifiedColumns[':p' . $index++]  = '`parent_reaction_id`';
        }
        if ($this->isColumnModified(PDReactionPeer::P_L_CITY_ID)) {
            $modifiedColumns[':p' . $index++]  = '`p_l_city_id`';
        }
        if ($this->isColumnModified(PDReactionPeer::P_L_DEPARTMENT_ID)) {
            $modifiedColumns[':p' . $index++]  = '`p_l_department_id`';
        }
        if ($this->isColumnModified(PDReactionPeer::P_L_REGION_ID)) {
            $modifiedColumns[':p' . $index++]  = '`p_l_region_id`';
        }
        if ($this->isColumnModified(PDReactionPeer::P_L_COUNTRY_ID)) {
            $modifiedColumns[':p' . $index++]  = '`p_l_country_id`';
        }
        if ($this->isColumnModified(PDReactionPeer::P_C_TOPIC_ID)) {
            $modifiedColumns[':p' . $index++]  = '`p_c_topic_id`';
        }
        if ($this->isColumnModified(PDReactionPeer::FB_AD_ID)) {
            $modifiedColumns[':p' . $index++]  = '`fb_ad_id`';
        }
        if ($this->isColumnModified(PDReactionPeer::TITLE)) {
            $modifiedColumns[':p' . $index++]  = '`title`';
        }
        if ($this->isColumnModified(PDReactionPeer::FILE_NAME)) {
            $modifiedColumns[':p' . $index++]  = '`file_name`';
        }
        if ($this->isColumnModified(PDReactionPeer::COPYRIGHT)) {
            $modifiedColumns[':p' . $index++]  = '`copyright`';
        }
        if ($this->isColumnModified(PDReactionPeer::DESCRIPTION)) {
            $modifiedColumns[':p' . $index++]  = '`description`';
        }
        if ($this->isColumnModified(PDReactionPeer::NOTE_POS)) {
            $modifiedColumns[':p' . $index++]  = '`note_pos`';
        }
        if ($this->isColumnModified(PDReactionPeer::NOTE_NEG)) {
            $modifiedColumns[':p' . $index++]  = '`note_neg`';
        }
        if ($this->isColumnModified(PDReactionPeer::NB_VIEWS)) {
            $modifiedColumns[':p' . $index++]  = '`nb_views`';
        }
        if ($this->isColumnModified(PDReactionPeer::WANT_BOOST)) {
            $modifiedColumns[':p' . $index++]  = '`want_boost`';
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
        if ($this->isColumnModified(PDReactionPeer::FAVORITE)) {
            $modifiedColumns[':p' . $index++]  = '`favorite`';
        }
        if ($this->isColumnModified(PDReactionPeer::ONLINE)) {
            $modifiedColumns[':p' . $index++]  = '`online`';
        }
        if ($this->isColumnModified(PDReactionPeer::HOMEPAGE)) {
            $modifiedColumns[':p' . $index++]  = '`homepage`';
        }
        if ($this->isColumnModified(PDReactionPeer::MODERATED)) {
            $modifiedColumns[':p' . $index++]  = '`moderated`';
        }
        if ($this->isColumnModified(PDReactionPeer::MODERATED_PARTIAL)) {
            $modifiedColumns[':p' . $index++]  = '`moderated_partial`';
        }
        if ($this->isColumnModified(PDReactionPeer::MODERATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`moderated_at`';
        }
        if ($this->isColumnModified(PDReactionPeer::INDEXED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`indexed_at`';
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

        $sql = sprintf(
            'INSERT INTO `p_d_reaction` (%s) VALUES (%s)',
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
                    case '`parent_reaction_id`':
                        $stmt->bindValue($identifier, $this->parent_reaction_id, PDO::PARAM_INT);
                        break;
                    case '`p_l_city_id`':
                        $stmt->bindValue($identifier, $this->p_l_city_id, PDO::PARAM_INT);
                        break;
                    case '`p_l_department_id`':
                        $stmt->bindValue($identifier, $this->p_l_department_id, PDO::PARAM_INT);
                        break;
                    case '`p_l_region_id`':
                        $stmt->bindValue($identifier, $this->p_l_region_id, PDO::PARAM_INT);
                        break;
                    case '`p_l_country_id`':
                        $stmt->bindValue($identifier, $this->p_l_country_id, PDO::PARAM_INT);
                        break;
                    case '`p_c_topic_id`':
                        $stmt->bindValue($identifier, $this->p_c_topic_id, PDO::PARAM_INT);
                        break;
                    case '`fb_ad_id`':
                        $stmt->bindValue($identifier, $this->fb_ad_id, PDO::PARAM_STR);
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
                    case '`want_boost`':
                        $stmt->bindValue($identifier, $this->want_boost, PDO::PARAM_INT);
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
                    case '`homepage`':
                        $stmt->bindValue($identifier, (int) $this->homepage, PDO::PARAM_INT);
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
                    case '`indexed_at`':
                        $stmt->bindValue($identifier, $this->indexed_at, PDO::PARAM_STR);
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
                return $this->getParentReactionId();
                break;
            case 5:
                return $this->getPLCityId();
                break;
            case 6:
                return $this->getPLDepartmentId();
                break;
            case 7:
                return $this->getPLRegionId();
                break;
            case 8:
                return $this->getPLCountryId();
                break;
            case 9:
                return $this->getPCTopicId();
                break;
            case 10:
                return $this->getFbAdId();
                break;
            case 11:
                return $this->getTitle();
                break;
            case 12:
                return $this->getFileName();
                break;
            case 13:
                return $this->getCopyright();
                break;
            case 14:
                return $this->getDescription();
                break;
            case 15:
                return $this->getNotePos();
                break;
            case 16:
                return $this->getNoteNeg();
                break;
            case 17:
                return $this->getNbViews();
                break;
            case 18:
                return $this->getWantBoost();
                break;
            case 19:
                return $this->getPublished();
                break;
            case 20:
                return $this->getPublishedAt();
                break;
            case 21:
                return $this->getPublishedBy();
                break;
            case 22:
                return $this->getFavorite();
                break;
            case 23:
                return $this->getOnline();
                break;
            case 24:
                return $this->getHomepage();
                break;
            case 25:
                return $this->getModerated();
                break;
            case 26:
                return $this->getModeratedPartial();
                break;
            case 27:
                return $this->getModeratedAt();
                break;
            case 28:
                return $this->getIndexedAt();
                break;
            case 29:
                return $this->getCreatedAt();
                break;
            case 30:
                return $this->getUpdatedAt();
                break;
            case 31:
                return $this->getSlug();
                break;
            case 32:
                return $this->getTreeLeft();
                break;
            case 33:
                return $this->getTreeRight();
                break;
            case 34:
                return $this->getTreeLevel();
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
            $keys[0] => $this->getId(),
            $keys[1] => $this->getUuid(),
            $keys[2] => $this->getPUserId(),
            $keys[3] => $this->getPDDebateId(),
            $keys[4] => $this->getParentReactionId(),
            $keys[5] => $this->getPLCityId(),
            $keys[6] => $this->getPLDepartmentId(),
            $keys[7] => $this->getPLRegionId(),
            $keys[8] => $this->getPLCountryId(),
            $keys[9] => $this->getPCTopicId(),
            $keys[10] => $this->getFbAdId(),
            $keys[11] => $this->getTitle(),
            $keys[12] => $this->getFileName(),
            $keys[13] => $this->getCopyright(),
            $keys[14] => $this->getDescription(),
            $keys[15] => $this->getNotePos(),
            $keys[16] => $this->getNoteNeg(),
            $keys[17] => $this->getNbViews(),
            $keys[18] => $this->getWantBoost(),
            $keys[19] => $this->getPublished(),
            $keys[20] => $this->getPublishedAt(),
            $keys[21] => $this->getPublishedBy(),
            $keys[22] => $this->getFavorite(),
            $keys[23] => $this->getOnline(),
            $keys[24] => $this->getHomepage(),
            $keys[25] => $this->getModerated(),
            $keys[26] => $this->getModeratedPartial(),
            $keys[27] => $this->getModeratedAt(),
            $keys[28] => $this->getIndexedAt(),
            $keys[29] => $this->getCreatedAt(),
            $keys[30] => $this->getUpdatedAt(),
            $keys[31] => $this->getSlug(),
            $keys[32] => $this->getTreeLeft(),
            $keys[33] => $this->getTreeRight(),
            $keys[34] => $this->getTreeLevel(),
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
            if (null !== $this->aPLCity) {
                $result['PLCity'] = $this->aPLCity->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aPLDepartment) {
                $result['PLDepartment'] = $this->aPLDepartment->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aPLRegion) {
                $result['PLRegion'] = $this->aPLRegion->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aPLCountry) {
                $result['PLCountry'] = $this->aPLCountry->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aPCTopic) {
                $result['PCTopic'] = $this->aPCTopic->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->collPuBookmarkDrPDReactions) {
                $result['PuBookmarkDrPDReactions'] = $this->collPuBookmarkDrPDReactions->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPuTrackDrPDReactions) {
                $result['PuTrackDrPDReactions'] = $this->collPuTrackDrPDReactions->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPDRComments) {
                $result['PDRComments'] = $this->collPDRComments->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPDRTaggedTs) {
                $result['PDRTaggedTs'] = $this->collPDRTaggedTs->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPDMedias) {
                $result['PDMedias'] = $this->collPDMedias->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPMReactionHistorics) {
                $result['PMReactionHistorics'] = $this->collPMReactionHistorics->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
                $this->setParentReactionId($value);
                break;
            case 5:
                $this->setPLCityId($value);
                break;
            case 6:
                $this->setPLDepartmentId($value);
                break;
            case 7:
                $this->setPLRegionId($value);
                break;
            case 8:
                $this->setPLCountryId($value);
                break;
            case 9:
                $this->setPCTopicId($value);
                break;
            case 10:
                $this->setFbAdId($value);
                break;
            case 11:
                $this->setTitle($value);
                break;
            case 12:
                $this->setFileName($value);
                break;
            case 13:
                $this->setCopyright($value);
                break;
            case 14:
                $this->setDescription($value);
                break;
            case 15:
                $this->setNotePos($value);
                break;
            case 16:
                $this->setNoteNeg($value);
                break;
            case 17:
                $this->setNbViews($value);
                break;
            case 18:
                $this->setWantBoost($value);
                break;
            case 19:
                $this->setPublished($value);
                break;
            case 20:
                $this->setPublishedAt($value);
                break;
            case 21:
                $this->setPublishedBy($value);
                break;
            case 22:
                $this->setFavorite($value);
                break;
            case 23:
                $this->setOnline($value);
                break;
            case 24:
                $this->setHomepage($value);
                break;
            case 25:
                $this->setModerated($value);
                break;
            case 26:
                $this->setModeratedPartial($value);
                break;
            case 27:
                $this->setModeratedAt($value);
                break;
            case 28:
                $this->setIndexedAt($value);
                break;
            case 29:
                $this->setCreatedAt($value);
                break;
            case 30:
                $this->setUpdatedAt($value);
                break;
            case 31:
                $this->setSlug($value);
                break;
            case 32:
                $this->setTreeLeft($value);
                break;
            case 33:
                $this->setTreeRight($value);
                break;
            case 34:
                $this->setTreeLevel($value);
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

        if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setUuid($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setPUserId($arr[$keys[2]]);
        if (array_key_exists($keys[3], $arr)) $this->setPDDebateId($arr[$keys[3]]);
        if (array_key_exists($keys[4], $arr)) $this->setParentReactionId($arr[$keys[4]]);
        if (array_key_exists($keys[5], $arr)) $this->setPLCityId($arr[$keys[5]]);
        if (array_key_exists($keys[6], $arr)) $this->setPLDepartmentId($arr[$keys[6]]);
        if (array_key_exists($keys[7], $arr)) $this->setPLRegionId($arr[$keys[7]]);
        if (array_key_exists($keys[8], $arr)) $this->setPLCountryId($arr[$keys[8]]);
        if (array_key_exists($keys[9], $arr)) $this->setPCTopicId($arr[$keys[9]]);
        if (array_key_exists($keys[10], $arr)) $this->setFbAdId($arr[$keys[10]]);
        if (array_key_exists($keys[11], $arr)) $this->setTitle($arr[$keys[11]]);
        if (array_key_exists($keys[12], $arr)) $this->setFileName($arr[$keys[12]]);
        if (array_key_exists($keys[13], $arr)) $this->setCopyright($arr[$keys[13]]);
        if (array_key_exists($keys[14], $arr)) $this->setDescription($arr[$keys[14]]);
        if (array_key_exists($keys[15], $arr)) $this->setNotePos($arr[$keys[15]]);
        if (array_key_exists($keys[16], $arr)) $this->setNoteNeg($arr[$keys[16]]);
        if (array_key_exists($keys[17], $arr)) $this->setNbViews($arr[$keys[17]]);
        if (array_key_exists($keys[18], $arr)) $this->setWantBoost($arr[$keys[18]]);
        if (array_key_exists($keys[19], $arr)) $this->setPublished($arr[$keys[19]]);
        if (array_key_exists($keys[20], $arr)) $this->setPublishedAt($arr[$keys[20]]);
        if (array_key_exists($keys[21], $arr)) $this->setPublishedBy($arr[$keys[21]]);
        if (array_key_exists($keys[22], $arr)) $this->setFavorite($arr[$keys[22]]);
        if (array_key_exists($keys[23], $arr)) $this->setOnline($arr[$keys[23]]);
        if (array_key_exists($keys[24], $arr)) $this->setHomepage($arr[$keys[24]]);
        if (array_key_exists($keys[25], $arr)) $this->setModerated($arr[$keys[25]]);
        if (array_key_exists($keys[26], $arr)) $this->setModeratedPartial($arr[$keys[26]]);
        if (array_key_exists($keys[27], $arr)) $this->setModeratedAt($arr[$keys[27]]);
        if (array_key_exists($keys[28], $arr)) $this->setIndexedAt($arr[$keys[28]]);
        if (array_key_exists($keys[29], $arr)) $this->setCreatedAt($arr[$keys[29]]);
        if (array_key_exists($keys[30], $arr)) $this->setUpdatedAt($arr[$keys[30]]);
        if (array_key_exists($keys[31], $arr)) $this->setSlug($arr[$keys[31]]);
        if (array_key_exists($keys[32], $arr)) $this->setTreeLeft($arr[$keys[32]]);
        if (array_key_exists($keys[33], $arr)) $this->setTreeRight($arr[$keys[33]]);
        if (array_key_exists($keys[34], $arr)) $this->setTreeLevel($arr[$keys[34]]);
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(PDReactionPeer::DATABASE_NAME);

        if ($this->isColumnModified(PDReactionPeer::ID)) $criteria->add(PDReactionPeer::ID, $this->id);
        if ($this->isColumnModified(PDReactionPeer::UUID)) $criteria->add(PDReactionPeer::UUID, $this->uuid);
        if ($this->isColumnModified(PDReactionPeer::P_USER_ID)) $criteria->add(PDReactionPeer::P_USER_ID, $this->p_user_id);
        if ($this->isColumnModified(PDReactionPeer::P_D_DEBATE_ID)) $criteria->add(PDReactionPeer::P_D_DEBATE_ID, $this->p_d_debate_id);
        if ($this->isColumnModified(PDReactionPeer::PARENT_REACTION_ID)) $criteria->add(PDReactionPeer::PARENT_REACTION_ID, $this->parent_reaction_id);
        if ($this->isColumnModified(PDReactionPeer::P_L_CITY_ID)) $criteria->add(PDReactionPeer::P_L_CITY_ID, $this->p_l_city_id);
        if ($this->isColumnModified(PDReactionPeer::P_L_DEPARTMENT_ID)) $criteria->add(PDReactionPeer::P_L_DEPARTMENT_ID, $this->p_l_department_id);
        if ($this->isColumnModified(PDReactionPeer::P_L_REGION_ID)) $criteria->add(PDReactionPeer::P_L_REGION_ID, $this->p_l_region_id);
        if ($this->isColumnModified(PDReactionPeer::P_L_COUNTRY_ID)) $criteria->add(PDReactionPeer::P_L_COUNTRY_ID, $this->p_l_country_id);
        if ($this->isColumnModified(PDReactionPeer::P_C_TOPIC_ID)) $criteria->add(PDReactionPeer::P_C_TOPIC_ID, $this->p_c_topic_id);
        if ($this->isColumnModified(PDReactionPeer::FB_AD_ID)) $criteria->add(PDReactionPeer::FB_AD_ID, $this->fb_ad_id);
        if ($this->isColumnModified(PDReactionPeer::TITLE)) $criteria->add(PDReactionPeer::TITLE, $this->title);
        if ($this->isColumnModified(PDReactionPeer::FILE_NAME)) $criteria->add(PDReactionPeer::FILE_NAME, $this->file_name);
        if ($this->isColumnModified(PDReactionPeer::COPYRIGHT)) $criteria->add(PDReactionPeer::COPYRIGHT, $this->copyright);
        if ($this->isColumnModified(PDReactionPeer::DESCRIPTION)) $criteria->add(PDReactionPeer::DESCRIPTION, $this->description);
        if ($this->isColumnModified(PDReactionPeer::NOTE_POS)) $criteria->add(PDReactionPeer::NOTE_POS, $this->note_pos);
        if ($this->isColumnModified(PDReactionPeer::NOTE_NEG)) $criteria->add(PDReactionPeer::NOTE_NEG, $this->note_neg);
        if ($this->isColumnModified(PDReactionPeer::NB_VIEWS)) $criteria->add(PDReactionPeer::NB_VIEWS, $this->nb_views);
        if ($this->isColumnModified(PDReactionPeer::WANT_BOOST)) $criteria->add(PDReactionPeer::WANT_BOOST, $this->want_boost);
        if ($this->isColumnModified(PDReactionPeer::PUBLISHED)) $criteria->add(PDReactionPeer::PUBLISHED, $this->published);
        if ($this->isColumnModified(PDReactionPeer::PUBLISHED_AT)) $criteria->add(PDReactionPeer::PUBLISHED_AT, $this->published_at);
        if ($this->isColumnModified(PDReactionPeer::PUBLISHED_BY)) $criteria->add(PDReactionPeer::PUBLISHED_BY, $this->published_by);
        if ($this->isColumnModified(PDReactionPeer::FAVORITE)) $criteria->add(PDReactionPeer::FAVORITE, $this->favorite);
        if ($this->isColumnModified(PDReactionPeer::ONLINE)) $criteria->add(PDReactionPeer::ONLINE, $this->online);
        if ($this->isColumnModified(PDReactionPeer::HOMEPAGE)) $criteria->add(PDReactionPeer::HOMEPAGE, $this->homepage);
        if ($this->isColumnModified(PDReactionPeer::MODERATED)) $criteria->add(PDReactionPeer::MODERATED, $this->moderated);
        if ($this->isColumnModified(PDReactionPeer::MODERATED_PARTIAL)) $criteria->add(PDReactionPeer::MODERATED_PARTIAL, $this->moderated_partial);
        if ($this->isColumnModified(PDReactionPeer::MODERATED_AT)) $criteria->add(PDReactionPeer::MODERATED_AT, $this->moderated_at);
        if ($this->isColumnModified(PDReactionPeer::INDEXED_AT)) $criteria->add(PDReactionPeer::INDEXED_AT, $this->indexed_at);
        if ($this->isColumnModified(PDReactionPeer::CREATED_AT)) $criteria->add(PDReactionPeer::CREATED_AT, $this->created_at);
        if ($this->isColumnModified(PDReactionPeer::UPDATED_AT)) $criteria->add(PDReactionPeer::UPDATED_AT, $this->updated_at);
        if ($this->isColumnModified(PDReactionPeer::SLUG)) $criteria->add(PDReactionPeer::SLUG, $this->slug);
        if ($this->isColumnModified(PDReactionPeer::TREE_LEFT)) $criteria->add(PDReactionPeer::TREE_LEFT, $this->tree_left);
        if ($this->isColumnModified(PDReactionPeer::TREE_RIGHT)) $criteria->add(PDReactionPeer::TREE_RIGHT, $this->tree_right);
        if ($this->isColumnModified(PDReactionPeer::TREE_LEVEL)) $criteria->add(PDReactionPeer::TREE_LEVEL, $this->tree_level);

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
        $copyObj->setUuid($this->getUuid());
        $copyObj->setPUserId($this->getPUserId());
        $copyObj->setPDDebateId($this->getPDDebateId());
        $copyObj->setParentReactionId($this->getParentReactionId());
        $copyObj->setPLCityId($this->getPLCityId());
        $copyObj->setPLDepartmentId($this->getPLDepartmentId());
        $copyObj->setPLRegionId($this->getPLRegionId());
        $copyObj->setPLCountryId($this->getPLCountryId());
        $copyObj->setPCTopicId($this->getPCTopicId());
        $copyObj->setFbAdId($this->getFbAdId());
        $copyObj->setTitle($this->getTitle());
        $copyObj->setFileName($this->getFileName());
        $copyObj->setCopyright($this->getCopyright());
        $copyObj->setDescription($this->getDescription());
        $copyObj->setNotePos($this->getNotePos());
        $copyObj->setNoteNeg($this->getNoteNeg());
        $copyObj->setNbViews($this->getNbViews());
        $copyObj->setWantBoost($this->getWantBoost());
        $copyObj->setPublished($this->getPublished());
        $copyObj->setPublishedAt($this->getPublishedAt());
        $copyObj->setPublishedBy($this->getPublishedBy());
        $copyObj->setFavorite($this->getFavorite());
        $copyObj->setOnline($this->getOnline());
        $copyObj->setHomepage($this->getHomepage());
        $copyObj->setModerated($this->getModerated());
        $copyObj->setModeratedPartial($this->getModeratedPartial());
        $copyObj->setModeratedAt($this->getModeratedAt());
        $copyObj->setIndexedAt($this->getIndexedAt());
        $copyObj->setCreatedAt($this->getCreatedAt());
        $copyObj->setUpdatedAt($this->getUpdatedAt());
        $copyObj->setSlug($this->getSlug());
        $copyObj->setTreeLeft($this->getTreeLeft());
        $copyObj->setTreeRight($this->getTreeRight());
        $copyObj->setTreeLevel($this->getTreeLevel());

        if ($deepCopy && !$this->startCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);
            // store object hash to prevent cycle
            $this->startCopy = true;

            foreach ($this->getPuBookmarkDrPDReactions() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPuBookmarkDrPDReaction($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPuTrackDrPDReactions() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPuTrackDrPDReaction($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPDRComments() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPDRComment($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPDRTaggedTs() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPDRTaggedT($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPDMedias() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPDMedia($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPMReactionHistorics() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPMReactionHistoric($relObj->copy($deepCopy));
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
     * Declares an association between this object and a PUser object.
     *
     * @param                  PUser $v
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
     * Declares an association between this object and a PDDebate object.
     *
     * @param                  PDDebate $v
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
     * Declares an association between this object and a PLCity object.
     *
     * @param                  PLCity $v
     * @return PDReaction The current object (for fluent API support)
     * @throws PropelException
     */
    public function setPLCity(PLCity $v = null)
    {
        if ($v === null) {
            $this->setPLCityId(NULL);
        } else {
            $this->setPLCityId($v->getId());
        }

        $this->aPLCity = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the PLCity object, it will not be re-added.
        if ($v !== null) {
            $v->addPDReaction($this);
        }


        return $this;
    }


    /**
     * Get the associated PLCity object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return PLCity The associated PLCity object.
     * @throws PropelException
     */
    public function getPLCity(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aPLCity === null && ($this->p_l_city_id !== null) && $doQuery) {
            $this->aPLCity = PLCityQuery::create()->findPk($this->p_l_city_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aPLCity->addPDReactions($this);
             */
        }

        return $this->aPLCity;
    }

    /**
     * Declares an association between this object and a PLDepartment object.
     *
     * @param                  PLDepartment $v
     * @return PDReaction The current object (for fluent API support)
     * @throws PropelException
     */
    public function setPLDepartment(PLDepartment $v = null)
    {
        if ($v === null) {
            $this->setPLDepartmentId(NULL);
        } else {
            $this->setPLDepartmentId($v->getId());
        }

        $this->aPLDepartment = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the PLDepartment object, it will not be re-added.
        if ($v !== null) {
            $v->addPDReaction($this);
        }


        return $this;
    }


    /**
     * Get the associated PLDepartment object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return PLDepartment The associated PLDepartment object.
     * @throws PropelException
     */
    public function getPLDepartment(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aPLDepartment === null && ($this->p_l_department_id !== null) && $doQuery) {
            $this->aPLDepartment = PLDepartmentQuery::create()->findPk($this->p_l_department_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aPLDepartment->addPDReactions($this);
             */
        }

        return $this->aPLDepartment;
    }

    /**
     * Declares an association between this object and a PLRegion object.
     *
     * @param                  PLRegion $v
     * @return PDReaction The current object (for fluent API support)
     * @throws PropelException
     */
    public function setPLRegion(PLRegion $v = null)
    {
        if ($v === null) {
            $this->setPLRegionId(NULL);
        } else {
            $this->setPLRegionId($v->getId());
        }

        $this->aPLRegion = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the PLRegion object, it will not be re-added.
        if ($v !== null) {
            $v->addPDReaction($this);
        }


        return $this;
    }


    /**
     * Get the associated PLRegion object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return PLRegion The associated PLRegion object.
     * @throws PropelException
     */
    public function getPLRegion(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aPLRegion === null && ($this->p_l_region_id !== null) && $doQuery) {
            $this->aPLRegion = PLRegionQuery::create()->findPk($this->p_l_region_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aPLRegion->addPDReactions($this);
             */
        }

        return $this->aPLRegion;
    }

    /**
     * Declares an association between this object and a PLCountry object.
     *
     * @param                  PLCountry $v
     * @return PDReaction The current object (for fluent API support)
     * @throws PropelException
     */
    public function setPLCountry(PLCountry $v = null)
    {
        if ($v === null) {
            $this->setPLCountryId(NULL);
        } else {
            $this->setPLCountryId($v->getId());
        }

        $this->aPLCountry = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the PLCountry object, it will not be re-added.
        if ($v !== null) {
            $v->addPDReaction($this);
        }


        return $this;
    }


    /**
     * Get the associated PLCountry object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return PLCountry The associated PLCountry object.
     * @throws PropelException
     */
    public function getPLCountry(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aPLCountry === null && ($this->p_l_country_id !== null) && $doQuery) {
            $this->aPLCountry = PLCountryQuery::create()->findPk($this->p_l_country_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aPLCountry->addPDReactions($this);
             */
        }

        return $this->aPLCountry;
    }

    /**
     * Declares an association between this object and a PCTopic object.
     *
     * @param                  PCTopic $v
     * @return PDReaction The current object (for fluent API support)
     * @throws PropelException
     */
    public function setPCTopic(PCTopic $v = null)
    {
        if ($v === null) {
            $this->setPCTopicId(NULL);
        } else {
            $this->setPCTopicId($v->getId());
        }

        $this->aPCTopic = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the PCTopic object, it will not be re-added.
        if ($v !== null) {
            $v->addPDReaction($this);
        }


        return $this;
    }


    /**
     * Get the associated PCTopic object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return PCTopic The associated PCTopic object.
     * @throws PropelException
     */
    public function getPCTopic(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aPCTopic === null && ($this->p_c_topic_id !== null) && $doQuery) {
            $this->aPCTopic = PCTopicQuery::create()->findPk($this->p_c_topic_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aPCTopic->addPDReactions($this);
             */
        }

        return $this->aPCTopic;
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
        if ('PuBookmarkDrPDReaction' == $relationName) {
            $this->initPuBookmarkDrPDReactions();
        }
        if ('PuTrackDrPDReaction' == $relationName) {
            $this->initPuTrackDrPDReactions();
        }
        if ('PDRComment' == $relationName) {
            $this->initPDRComments();
        }
        if ('PDRTaggedT' == $relationName) {
            $this->initPDRTaggedTs();
        }
        if ('PDMedia' == $relationName) {
            $this->initPDMedias();
        }
        if ('PMReactionHistoric' == $relationName) {
            $this->initPMReactionHistorics();
        }
    }

    /**
     * Clears out the collPuBookmarkDrPDReactions collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return PDReaction The current object (for fluent API support)
     * @see        addPuBookmarkDrPDReactions()
     */
    public function clearPuBookmarkDrPDReactions()
    {
        $this->collPuBookmarkDrPDReactions = null; // important to set this to null since that means it is uninitialized
        $this->collPuBookmarkDrPDReactionsPartial = null;

        return $this;
    }

    /**
     * reset is the collPuBookmarkDrPDReactions collection loaded partially
     *
     * @return void
     */
    public function resetPartialPuBookmarkDrPDReactions($v = true)
    {
        $this->collPuBookmarkDrPDReactionsPartial = $v;
    }

    /**
     * Initializes the collPuBookmarkDrPDReactions collection.
     *
     * By default this just sets the collPuBookmarkDrPDReactions collection to an empty array (like clearcollPuBookmarkDrPDReactions());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPuBookmarkDrPDReactions($overrideExisting = true)
    {
        if (null !== $this->collPuBookmarkDrPDReactions && !$overrideExisting) {
            return;
        }
        $this->collPuBookmarkDrPDReactions = new PropelObjectCollection();
        $this->collPuBookmarkDrPDReactions->setModel('PUBookmarkDR');
    }

    /**
     * Gets an array of PUBookmarkDR objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this PDReaction is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|PUBookmarkDR[] List of PUBookmarkDR objects
     * @throws PropelException
     */
    public function getPuBookmarkDrPDReactions($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collPuBookmarkDrPDReactionsPartial && !$this->isNew();
        if (null === $this->collPuBookmarkDrPDReactions || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPuBookmarkDrPDReactions) {
                // return empty collection
                $this->initPuBookmarkDrPDReactions();
            } else {
                $collPuBookmarkDrPDReactions = PUBookmarkDRQuery::create(null, $criteria)
                    ->filterByPuBookmarkDrPDReaction($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collPuBookmarkDrPDReactionsPartial && count($collPuBookmarkDrPDReactions)) {
                      $this->initPuBookmarkDrPDReactions(false);

                      foreach ($collPuBookmarkDrPDReactions as $obj) {
                        if (false == $this->collPuBookmarkDrPDReactions->contains($obj)) {
                          $this->collPuBookmarkDrPDReactions->append($obj);
                        }
                      }

                      $this->collPuBookmarkDrPDReactionsPartial = true;
                    }

                    $collPuBookmarkDrPDReactions->getInternalIterator()->rewind();

                    return $collPuBookmarkDrPDReactions;
                }

                if ($partial && $this->collPuBookmarkDrPDReactions) {
                    foreach ($this->collPuBookmarkDrPDReactions as $obj) {
                        if ($obj->isNew()) {
                            $collPuBookmarkDrPDReactions[] = $obj;
                        }
                    }
                }

                $this->collPuBookmarkDrPDReactions = $collPuBookmarkDrPDReactions;
                $this->collPuBookmarkDrPDReactionsPartial = false;
            }
        }

        return $this->collPuBookmarkDrPDReactions;
    }

    /**
     * Sets a collection of PuBookmarkDrPDReaction objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $puBookmarkDrPDReactions A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return PDReaction The current object (for fluent API support)
     */
    public function setPuBookmarkDrPDReactions(PropelCollection $puBookmarkDrPDReactions, PropelPDO $con = null)
    {
        $puBookmarkDrPDReactionsToDelete = $this->getPuBookmarkDrPDReactions(new Criteria(), $con)->diff($puBookmarkDrPDReactions);


        $this->puBookmarkDrPDReactionsScheduledForDeletion = $puBookmarkDrPDReactionsToDelete;

        foreach ($puBookmarkDrPDReactionsToDelete as $puBookmarkDrPDReactionRemoved) {
            $puBookmarkDrPDReactionRemoved->setPuBookmarkDrPDReaction(null);
        }

        $this->collPuBookmarkDrPDReactions = null;
        foreach ($puBookmarkDrPDReactions as $puBookmarkDrPDReaction) {
            $this->addPuBookmarkDrPDReaction($puBookmarkDrPDReaction);
        }

        $this->collPuBookmarkDrPDReactions = $puBookmarkDrPDReactions;
        $this->collPuBookmarkDrPDReactionsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related PUBookmarkDR objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related PUBookmarkDR objects.
     * @throws PropelException
     */
    public function countPuBookmarkDrPDReactions(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collPuBookmarkDrPDReactionsPartial && !$this->isNew();
        if (null === $this->collPuBookmarkDrPDReactions || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPuBookmarkDrPDReactions) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPuBookmarkDrPDReactions());
            }
            $query = PUBookmarkDRQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPuBookmarkDrPDReaction($this)
                ->count($con);
        }

        return count($this->collPuBookmarkDrPDReactions);
    }

    /**
     * Method called to associate a PUBookmarkDR object to this object
     * through the PUBookmarkDR foreign key attribute.
     *
     * @param    PUBookmarkDR $l PUBookmarkDR
     * @return PDReaction The current object (for fluent API support)
     */
    public function addPuBookmarkDrPDReaction(PUBookmarkDR $l)
    {
        if ($this->collPuBookmarkDrPDReactions === null) {
            $this->initPuBookmarkDrPDReactions();
            $this->collPuBookmarkDrPDReactionsPartial = true;
        }

        if (!in_array($l, $this->collPuBookmarkDrPDReactions->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddPuBookmarkDrPDReaction($l);

            if ($this->puBookmarkDrPDReactionsScheduledForDeletion and $this->puBookmarkDrPDReactionsScheduledForDeletion->contains($l)) {
                $this->puBookmarkDrPDReactionsScheduledForDeletion->remove($this->puBookmarkDrPDReactionsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	PuBookmarkDrPDReaction $puBookmarkDrPDReaction The puBookmarkDrPDReaction object to add.
     */
    protected function doAddPuBookmarkDrPDReaction($puBookmarkDrPDReaction)
    {
        $this->collPuBookmarkDrPDReactions[]= $puBookmarkDrPDReaction;
        $puBookmarkDrPDReaction->setPuBookmarkDrPDReaction($this);
    }

    /**
     * @param	PuBookmarkDrPDReaction $puBookmarkDrPDReaction The puBookmarkDrPDReaction object to remove.
     * @return PDReaction The current object (for fluent API support)
     */
    public function removePuBookmarkDrPDReaction($puBookmarkDrPDReaction)
    {
        if ($this->getPuBookmarkDrPDReactions()->contains($puBookmarkDrPDReaction)) {
            $this->collPuBookmarkDrPDReactions->remove($this->collPuBookmarkDrPDReactions->search($puBookmarkDrPDReaction));
            if (null === $this->puBookmarkDrPDReactionsScheduledForDeletion) {
                $this->puBookmarkDrPDReactionsScheduledForDeletion = clone $this->collPuBookmarkDrPDReactions;
                $this->puBookmarkDrPDReactionsScheduledForDeletion->clear();
            }
            $this->puBookmarkDrPDReactionsScheduledForDeletion[]= clone $puBookmarkDrPDReaction;
            $puBookmarkDrPDReaction->setPuBookmarkDrPDReaction(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this PDReaction is new, it will return
     * an empty collection; or if this PDReaction has previously
     * been saved, it will retrieve related PuBookmarkDrPDReactions from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in PDReaction.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|PUBookmarkDR[] List of PUBookmarkDR objects
     */
    public function getPuBookmarkDrPDReactionsJoinPuBookmarkDrPUser($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PUBookmarkDRQuery::create(null, $criteria);
        $query->joinWith('PuBookmarkDrPUser', $join_behavior);

        return $this->getPuBookmarkDrPDReactions($query, $con);
    }

    /**
     * Clears out the collPuTrackDrPDReactions collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return PDReaction The current object (for fluent API support)
     * @see        addPuTrackDrPDReactions()
     */
    public function clearPuTrackDrPDReactions()
    {
        $this->collPuTrackDrPDReactions = null; // important to set this to null since that means it is uninitialized
        $this->collPuTrackDrPDReactionsPartial = null;

        return $this;
    }

    /**
     * reset is the collPuTrackDrPDReactions collection loaded partially
     *
     * @return void
     */
    public function resetPartialPuTrackDrPDReactions($v = true)
    {
        $this->collPuTrackDrPDReactionsPartial = $v;
    }

    /**
     * Initializes the collPuTrackDrPDReactions collection.
     *
     * By default this just sets the collPuTrackDrPDReactions collection to an empty array (like clearcollPuTrackDrPDReactions());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPuTrackDrPDReactions($overrideExisting = true)
    {
        if (null !== $this->collPuTrackDrPDReactions && !$overrideExisting) {
            return;
        }
        $this->collPuTrackDrPDReactions = new PropelObjectCollection();
        $this->collPuTrackDrPDReactions->setModel('PUTrackDR');
    }

    /**
     * Gets an array of PUTrackDR objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this PDReaction is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|PUTrackDR[] List of PUTrackDR objects
     * @throws PropelException
     */
    public function getPuTrackDrPDReactions($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collPuTrackDrPDReactionsPartial && !$this->isNew();
        if (null === $this->collPuTrackDrPDReactions || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPuTrackDrPDReactions) {
                // return empty collection
                $this->initPuTrackDrPDReactions();
            } else {
                $collPuTrackDrPDReactions = PUTrackDRQuery::create(null, $criteria)
                    ->filterByPuTrackDrPDReaction($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collPuTrackDrPDReactionsPartial && count($collPuTrackDrPDReactions)) {
                      $this->initPuTrackDrPDReactions(false);

                      foreach ($collPuTrackDrPDReactions as $obj) {
                        if (false == $this->collPuTrackDrPDReactions->contains($obj)) {
                          $this->collPuTrackDrPDReactions->append($obj);
                        }
                      }

                      $this->collPuTrackDrPDReactionsPartial = true;
                    }

                    $collPuTrackDrPDReactions->getInternalIterator()->rewind();

                    return $collPuTrackDrPDReactions;
                }

                if ($partial && $this->collPuTrackDrPDReactions) {
                    foreach ($this->collPuTrackDrPDReactions as $obj) {
                        if ($obj->isNew()) {
                            $collPuTrackDrPDReactions[] = $obj;
                        }
                    }
                }

                $this->collPuTrackDrPDReactions = $collPuTrackDrPDReactions;
                $this->collPuTrackDrPDReactionsPartial = false;
            }
        }

        return $this->collPuTrackDrPDReactions;
    }

    /**
     * Sets a collection of PuTrackDrPDReaction objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $puTrackDrPDReactions A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return PDReaction The current object (for fluent API support)
     */
    public function setPuTrackDrPDReactions(PropelCollection $puTrackDrPDReactions, PropelPDO $con = null)
    {
        $puTrackDrPDReactionsToDelete = $this->getPuTrackDrPDReactions(new Criteria(), $con)->diff($puTrackDrPDReactions);


        $this->puTrackDrPDReactionsScheduledForDeletion = $puTrackDrPDReactionsToDelete;

        foreach ($puTrackDrPDReactionsToDelete as $puTrackDrPDReactionRemoved) {
            $puTrackDrPDReactionRemoved->setPuTrackDrPDReaction(null);
        }

        $this->collPuTrackDrPDReactions = null;
        foreach ($puTrackDrPDReactions as $puTrackDrPDReaction) {
            $this->addPuTrackDrPDReaction($puTrackDrPDReaction);
        }

        $this->collPuTrackDrPDReactions = $puTrackDrPDReactions;
        $this->collPuTrackDrPDReactionsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related PUTrackDR objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related PUTrackDR objects.
     * @throws PropelException
     */
    public function countPuTrackDrPDReactions(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collPuTrackDrPDReactionsPartial && !$this->isNew();
        if (null === $this->collPuTrackDrPDReactions || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPuTrackDrPDReactions) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPuTrackDrPDReactions());
            }
            $query = PUTrackDRQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPuTrackDrPDReaction($this)
                ->count($con);
        }

        return count($this->collPuTrackDrPDReactions);
    }

    /**
     * Method called to associate a PUTrackDR object to this object
     * through the PUTrackDR foreign key attribute.
     *
     * @param    PUTrackDR $l PUTrackDR
     * @return PDReaction The current object (for fluent API support)
     */
    public function addPuTrackDrPDReaction(PUTrackDR $l)
    {
        if ($this->collPuTrackDrPDReactions === null) {
            $this->initPuTrackDrPDReactions();
            $this->collPuTrackDrPDReactionsPartial = true;
        }

        if (!in_array($l, $this->collPuTrackDrPDReactions->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddPuTrackDrPDReaction($l);

            if ($this->puTrackDrPDReactionsScheduledForDeletion and $this->puTrackDrPDReactionsScheduledForDeletion->contains($l)) {
                $this->puTrackDrPDReactionsScheduledForDeletion->remove($this->puTrackDrPDReactionsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	PuTrackDrPDReaction $puTrackDrPDReaction The puTrackDrPDReaction object to add.
     */
    protected function doAddPuTrackDrPDReaction($puTrackDrPDReaction)
    {
        $this->collPuTrackDrPDReactions[]= $puTrackDrPDReaction;
        $puTrackDrPDReaction->setPuTrackDrPDReaction($this);
    }

    /**
     * @param	PuTrackDrPDReaction $puTrackDrPDReaction The puTrackDrPDReaction object to remove.
     * @return PDReaction The current object (for fluent API support)
     */
    public function removePuTrackDrPDReaction($puTrackDrPDReaction)
    {
        if ($this->getPuTrackDrPDReactions()->contains($puTrackDrPDReaction)) {
            $this->collPuTrackDrPDReactions->remove($this->collPuTrackDrPDReactions->search($puTrackDrPDReaction));
            if (null === $this->puTrackDrPDReactionsScheduledForDeletion) {
                $this->puTrackDrPDReactionsScheduledForDeletion = clone $this->collPuTrackDrPDReactions;
                $this->puTrackDrPDReactionsScheduledForDeletion->clear();
            }
            $this->puTrackDrPDReactionsScheduledForDeletion[]= clone $puTrackDrPDReaction;
            $puTrackDrPDReaction->setPuTrackDrPDReaction(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this PDReaction is new, it will return
     * an empty collection; or if this PDReaction has previously
     * been saved, it will retrieve related PuTrackDrPDReactions from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in PDReaction.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|PUTrackDR[] List of PUTrackDR objects
     */
    public function getPuTrackDrPDReactionsJoinPuTrackDrPUser($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PUTrackDRQuery::create(null, $criteria);
        $query->joinWith('PuTrackDrPUser', $join_behavior);

        return $this->getPuTrackDrPDReactions($query, $con);
    }

    /**
     * Clears out the collPDRComments collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return PDReaction The current object (for fluent API support)
     * @see        addPDRComments()
     */
    public function clearPDRComments()
    {
        $this->collPDRComments = null; // important to set this to null since that means it is uninitialized
        $this->collPDRCommentsPartial = null;

        return $this;
    }

    /**
     * reset is the collPDRComments collection loaded partially
     *
     * @return void
     */
    public function resetPartialPDRComments($v = true)
    {
        $this->collPDRCommentsPartial = $v;
    }

    /**
     * Initializes the collPDRComments collection.
     *
     * By default this just sets the collPDRComments collection to an empty array (like clearcollPDRComments());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPDRComments($overrideExisting = true)
    {
        if (null !== $this->collPDRComments && !$overrideExisting) {
            return;
        }
        $this->collPDRComments = new PropelObjectCollection();
        $this->collPDRComments->setModel('PDRComment');
    }

    /**
     * Gets an array of PDRComment objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this PDReaction is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|PDRComment[] List of PDRComment objects
     * @throws PropelException
     */
    public function getPDRComments($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collPDRCommentsPartial && !$this->isNew();
        if (null === $this->collPDRComments || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPDRComments) {
                // return empty collection
                $this->initPDRComments();
            } else {
                $collPDRComments = PDRCommentQuery::create(null, $criteria)
                    ->filterByPDReaction($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collPDRCommentsPartial && count($collPDRComments)) {
                      $this->initPDRComments(false);

                      foreach ($collPDRComments as $obj) {
                        if (false == $this->collPDRComments->contains($obj)) {
                          $this->collPDRComments->append($obj);
                        }
                      }

                      $this->collPDRCommentsPartial = true;
                    }

                    $collPDRComments->getInternalIterator()->rewind();

                    return $collPDRComments;
                }

                if ($partial && $this->collPDRComments) {
                    foreach ($this->collPDRComments as $obj) {
                        if ($obj->isNew()) {
                            $collPDRComments[] = $obj;
                        }
                    }
                }

                $this->collPDRComments = $collPDRComments;
                $this->collPDRCommentsPartial = false;
            }
        }

        return $this->collPDRComments;
    }

    /**
     * Sets a collection of PDRComment objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $pDRComments A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return PDReaction The current object (for fluent API support)
     */
    public function setPDRComments(PropelCollection $pDRComments, PropelPDO $con = null)
    {
        $pDRCommentsToDelete = $this->getPDRComments(new Criteria(), $con)->diff($pDRComments);


        $this->pDRCommentsScheduledForDeletion = $pDRCommentsToDelete;

        foreach ($pDRCommentsToDelete as $pDRCommentRemoved) {
            $pDRCommentRemoved->setPDReaction(null);
        }

        $this->collPDRComments = null;
        foreach ($pDRComments as $pDRComment) {
            $this->addPDRComment($pDRComment);
        }

        $this->collPDRComments = $pDRComments;
        $this->collPDRCommentsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related PDRComment objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related PDRComment objects.
     * @throws PropelException
     */
    public function countPDRComments(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collPDRCommentsPartial && !$this->isNew();
        if (null === $this->collPDRComments || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPDRComments) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPDRComments());
            }
            $query = PDRCommentQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPDReaction($this)
                ->count($con);
        }

        return count($this->collPDRComments);
    }

    /**
     * Method called to associate a PDRComment object to this object
     * through the PDRComment foreign key attribute.
     *
     * @param    PDRComment $l PDRComment
     * @return PDReaction The current object (for fluent API support)
     */
    public function addPDRComment(PDRComment $l)
    {
        if ($this->collPDRComments === null) {
            $this->initPDRComments();
            $this->collPDRCommentsPartial = true;
        }

        if (!in_array($l, $this->collPDRComments->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddPDRComment($l);

            if ($this->pDRCommentsScheduledForDeletion and $this->pDRCommentsScheduledForDeletion->contains($l)) {
                $this->pDRCommentsScheduledForDeletion->remove($this->pDRCommentsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	PDRComment $pDRComment The pDRComment object to add.
     */
    protected function doAddPDRComment($pDRComment)
    {
        $this->collPDRComments[]= $pDRComment;
        $pDRComment->setPDReaction($this);
    }

    /**
     * @param	PDRComment $pDRComment The pDRComment object to remove.
     * @return PDReaction The current object (for fluent API support)
     */
    public function removePDRComment($pDRComment)
    {
        if ($this->getPDRComments()->contains($pDRComment)) {
            $this->collPDRComments->remove($this->collPDRComments->search($pDRComment));
            if (null === $this->pDRCommentsScheduledForDeletion) {
                $this->pDRCommentsScheduledForDeletion = clone $this->collPDRComments;
                $this->pDRCommentsScheduledForDeletion->clear();
            }
            $this->pDRCommentsScheduledForDeletion[]= clone $pDRComment;
            $pDRComment->setPDReaction(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this PDReaction is new, it will return
     * an empty collection; or if this PDReaction has previously
     * been saved, it will retrieve related PDRComments from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in PDReaction.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|PDRComment[] List of PDRComment objects
     */
    public function getPDRCommentsJoinPUser($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PDRCommentQuery::create(null, $criteria);
        $query->joinWith('PUser', $join_behavior);

        return $this->getPDRComments($query, $con);
    }

    /**
     * Clears out the collPDRTaggedTs collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return PDReaction The current object (for fluent API support)
     * @see        addPDRTaggedTs()
     */
    public function clearPDRTaggedTs()
    {
        $this->collPDRTaggedTs = null; // important to set this to null since that means it is uninitialized
        $this->collPDRTaggedTsPartial = null;

        return $this;
    }

    /**
     * reset is the collPDRTaggedTs collection loaded partially
     *
     * @return void
     */
    public function resetPartialPDRTaggedTs($v = true)
    {
        $this->collPDRTaggedTsPartial = $v;
    }

    /**
     * Initializes the collPDRTaggedTs collection.
     *
     * By default this just sets the collPDRTaggedTs collection to an empty array (like clearcollPDRTaggedTs());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPDRTaggedTs($overrideExisting = true)
    {
        if (null !== $this->collPDRTaggedTs && !$overrideExisting) {
            return;
        }
        $this->collPDRTaggedTs = new PropelObjectCollection();
        $this->collPDRTaggedTs->setModel('PDRTaggedT');
    }

    /**
     * Gets an array of PDRTaggedT objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this PDReaction is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|PDRTaggedT[] List of PDRTaggedT objects
     * @throws PropelException
     */
    public function getPDRTaggedTs($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collPDRTaggedTsPartial && !$this->isNew();
        if (null === $this->collPDRTaggedTs || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPDRTaggedTs) {
                // return empty collection
                $this->initPDRTaggedTs();
            } else {
                $collPDRTaggedTs = PDRTaggedTQuery::create(null, $criteria)
                    ->filterByPDReaction($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collPDRTaggedTsPartial && count($collPDRTaggedTs)) {
                      $this->initPDRTaggedTs(false);

                      foreach ($collPDRTaggedTs as $obj) {
                        if (false == $this->collPDRTaggedTs->contains($obj)) {
                          $this->collPDRTaggedTs->append($obj);
                        }
                      }

                      $this->collPDRTaggedTsPartial = true;
                    }

                    $collPDRTaggedTs->getInternalIterator()->rewind();

                    return $collPDRTaggedTs;
                }

                if ($partial && $this->collPDRTaggedTs) {
                    foreach ($this->collPDRTaggedTs as $obj) {
                        if ($obj->isNew()) {
                            $collPDRTaggedTs[] = $obj;
                        }
                    }
                }

                $this->collPDRTaggedTs = $collPDRTaggedTs;
                $this->collPDRTaggedTsPartial = false;
            }
        }

        return $this->collPDRTaggedTs;
    }

    /**
     * Sets a collection of PDRTaggedT objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $pDRTaggedTs A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return PDReaction The current object (for fluent API support)
     */
    public function setPDRTaggedTs(PropelCollection $pDRTaggedTs, PropelPDO $con = null)
    {
        $pDRTaggedTsToDelete = $this->getPDRTaggedTs(new Criteria(), $con)->diff($pDRTaggedTs);


        $this->pDRTaggedTsScheduledForDeletion = $pDRTaggedTsToDelete;

        foreach ($pDRTaggedTsToDelete as $pDRTaggedTRemoved) {
            $pDRTaggedTRemoved->setPDReaction(null);
        }

        $this->collPDRTaggedTs = null;
        foreach ($pDRTaggedTs as $pDRTaggedT) {
            $this->addPDRTaggedT($pDRTaggedT);
        }

        $this->collPDRTaggedTs = $pDRTaggedTs;
        $this->collPDRTaggedTsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related PDRTaggedT objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related PDRTaggedT objects.
     * @throws PropelException
     */
    public function countPDRTaggedTs(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collPDRTaggedTsPartial && !$this->isNew();
        if (null === $this->collPDRTaggedTs || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPDRTaggedTs) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPDRTaggedTs());
            }
            $query = PDRTaggedTQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPDReaction($this)
                ->count($con);
        }

        return count($this->collPDRTaggedTs);
    }

    /**
     * Method called to associate a PDRTaggedT object to this object
     * through the PDRTaggedT foreign key attribute.
     *
     * @param    PDRTaggedT $l PDRTaggedT
     * @return PDReaction The current object (for fluent API support)
     */
    public function addPDRTaggedT(PDRTaggedT $l)
    {
        if ($this->collPDRTaggedTs === null) {
            $this->initPDRTaggedTs();
            $this->collPDRTaggedTsPartial = true;
        }

        if (!in_array($l, $this->collPDRTaggedTs->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddPDRTaggedT($l);

            if ($this->pDRTaggedTsScheduledForDeletion and $this->pDRTaggedTsScheduledForDeletion->contains($l)) {
                $this->pDRTaggedTsScheduledForDeletion->remove($this->pDRTaggedTsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	PDRTaggedT $pDRTaggedT The pDRTaggedT object to add.
     */
    protected function doAddPDRTaggedT($pDRTaggedT)
    {
        $this->collPDRTaggedTs[]= $pDRTaggedT;
        $pDRTaggedT->setPDReaction($this);
    }

    /**
     * @param	PDRTaggedT $pDRTaggedT The pDRTaggedT object to remove.
     * @return PDReaction The current object (for fluent API support)
     */
    public function removePDRTaggedT($pDRTaggedT)
    {
        if ($this->getPDRTaggedTs()->contains($pDRTaggedT)) {
            $this->collPDRTaggedTs->remove($this->collPDRTaggedTs->search($pDRTaggedT));
            if (null === $this->pDRTaggedTsScheduledForDeletion) {
                $this->pDRTaggedTsScheduledForDeletion = clone $this->collPDRTaggedTs;
                $this->pDRTaggedTsScheduledForDeletion->clear();
            }
            $this->pDRTaggedTsScheduledForDeletion[]= clone $pDRTaggedT;
            $pDRTaggedT->setPDReaction(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this PDReaction is new, it will return
     * an empty collection; or if this PDReaction has previously
     * been saved, it will retrieve related PDRTaggedTs from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in PDReaction.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|PDRTaggedT[] List of PDRTaggedT objects
     */
    public function getPDRTaggedTsJoinPTag($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PDRTaggedTQuery::create(null, $criteria);
        $query->joinWith('PTag', $join_behavior);

        return $this->getPDRTaggedTs($query, $con);
    }

    /**
     * Clears out the collPDMedias collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return PDReaction The current object (for fluent API support)
     * @see        addPDMedias()
     */
    public function clearPDMedias()
    {
        $this->collPDMedias = null; // important to set this to null since that means it is uninitialized
        $this->collPDMediasPartial = null;

        return $this;
    }

    /**
     * reset is the collPDMedias collection loaded partially
     *
     * @return void
     */
    public function resetPartialPDMedias($v = true)
    {
        $this->collPDMediasPartial = $v;
    }

    /**
     * Initializes the collPDMedias collection.
     *
     * By default this just sets the collPDMedias collection to an empty array (like clearcollPDMedias());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPDMedias($overrideExisting = true)
    {
        if (null !== $this->collPDMedias && !$overrideExisting) {
            return;
        }
        $this->collPDMedias = new PropelObjectCollection();
        $this->collPDMedias->setModel('PDMedia');
    }

    /**
     * Gets an array of PDMedia objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this PDReaction is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|PDMedia[] List of PDMedia objects
     * @throws PropelException
     */
    public function getPDMedias($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collPDMediasPartial && !$this->isNew();
        if (null === $this->collPDMedias || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPDMedias) {
                // return empty collection
                $this->initPDMedias();
            } else {
                $collPDMedias = PDMediaQuery::create(null, $criteria)
                    ->filterByPDReaction($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collPDMediasPartial && count($collPDMedias)) {
                      $this->initPDMedias(false);

                      foreach ($collPDMedias as $obj) {
                        if (false == $this->collPDMedias->contains($obj)) {
                          $this->collPDMedias->append($obj);
                        }
                      }

                      $this->collPDMediasPartial = true;
                    }

                    $collPDMedias->getInternalIterator()->rewind();

                    return $collPDMedias;
                }

                if ($partial && $this->collPDMedias) {
                    foreach ($this->collPDMedias as $obj) {
                        if ($obj->isNew()) {
                            $collPDMedias[] = $obj;
                        }
                    }
                }

                $this->collPDMedias = $collPDMedias;
                $this->collPDMediasPartial = false;
            }
        }

        return $this->collPDMedias;
    }

    /**
     * Sets a collection of PDMedia objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $pDMedias A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return PDReaction The current object (for fluent API support)
     */
    public function setPDMedias(PropelCollection $pDMedias, PropelPDO $con = null)
    {
        $pDMediasToDelete = $this->getPDMedias(new Criteria(), $con)->diff($pDMedias);


        $this->pDMediasScheduledForDeletion = $pDMediasToDelete;

        foreach ($pDMediasToDelete as $pDMediaRemoved) {
            $pDMediaRemoved->setPDReaction(null);
        }

        $this->collPDMedias = null;
        foreach ($pDMedias as $pDMedia) {
            $this->addPDMedia($pDMedia);
        }

        $this->collPDMedias = $pDMedias;
        $this->collPDMediasPartial = false;

        return $this;
    }

    /**
     * Returns the number of related PDMedia objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related PDMedia objects.
     * @throws PropelException
     */
    public function countPDMedias(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collPDMediasPartial && !$this->isNew();
        if (null === $this->collPDMedias || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPDMedias) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPDMedias());
            }
            $query = PDMediaQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPDReaction($this)
                ->count($con);
        }

        return count($this->collPDMedias);
    }

    /**
     * Method called to associate a PDMedia object to this object
     * through the PDMedia foreign key attribute.
     *
     * @param    PDMedia $l PDMedia
     * @return PDReaction The current object (for fluent API support)
     */
    public function addPDMedia(PDMedia $l)
    {
        if ($this->collPDMedias === null) {
            $this->initPDMedias();
            $this->collPDMediasPartial = true;
        }

        if (!in_array($l, $this->collPDMedias->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddPDMedia($l);

            if ($this->pDMediasScheduledForDeletion and $this->pDMediasScheduledForDeletion->contains($l)) {
                $this->pDMediasScheduledForDeletion->remove($this->pDMediasScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	PDMedia $pDMedia The pDMedia object to add.
     */
    protected function doAddPDMedia($pDMedia)
    {
        $this->collPDMedias[]= $pDMedia;
        $pDMedia->setPDReaction($this);
    }

    /**
     * @param	PDMedia $pDMedia The pDMedia object to remove.
     * @return PDReaction The current object (for fluent API support)
     */
    public function removePDMedia($pDMedia)
    {
        if ($this->getPDMedias()->contains($pDMedia)) {
            $this->collPDMedias->remove($this->collPDMedias->search($pDMedia));
            if (null === $this->pDMediasScheduledForDeletion) {
                $this->pDMediasScheduledForDeletion = clone $this->collPDMedias;
                $this->pDMediasScheduledForDeletion->clear();
            }
            $this->pDMediasScheduledForDeletion[]= $pDMedia;
            $pDMedia->setPDReaction(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this PDReaction is new, it will return
     * an empty collection; or if this PDReaction has previously
     * been saved, it will retrieve related PDMedias from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in PDReaction.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|PDMedia[] List of PDMedia objects
     */
    public function getPDMediasJoinPDDebate($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PDMediaQuery::create(null, $criteria);
        $query->joinWith('PDDebate', $join_behavior);

        return $this->getPDMedias($query, $con);
    }

    /**
     * Clears out the collPMReactionHistorics collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return PDReaction The current object (for fluent API support)
     * @see        addPMReactionHistorics()
     */
    public function clearPMReactionHistorics()
    {
        $this->collPMReactionHistorics = null; // important to set this to null since that means it is uninitialized
        $this->collPMReactionHistoricsPartial = null;

        return $this;
    }

    /**
     * reset is the collPMReactionHistorics collection loaded partially
     *
     * @return void
     */
    public function resetPartialPMReactionHistorics($v = true)
    {
        $this->collPMReactionHistoricsPartial = $v;
    }

    /**
     * Initializes the collPMReactionHistorics collection.
     *
     * By default this just sets the collPMReactionHistorics collection to an empty array (like clearcollPMReactionHistorics());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPMReactionHistorics($overrideExisting = true)
    {
        if (null !== $this->collPMReactionHistorics && !$overrideExisting) {
            return;
        }
        $this->collPMReactionHistorics = new PropelObjectCollection();
        $this->collPMReactionHistorics->setModel('PMReactionHistoric');
    }

    /**
     * Gets an array of PMReactionHistoric objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this PDReaction is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|PMReactionHistoric[] List of PMReactionHistoric objects
     * @throws PropelException
     */
    public function getPMReactionHistorics($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collPMReactionHistoricsPartial && !$this->isNew();
        if (null === $this->collPMReactionHistorics || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPMReactionHistorics) {
                // return empty collection
                $this->initPMReactionHistorics();
            } else {
                $collPMReactionHistorics = PMReactionHistoricQuery::create(null, $criteria)
                    ->filterByPDReaction($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collPMReactionHistoricsPartial && count($collPMReactionHistorics)) {
                      $this->initPMReactionHistorics(false);

                      foreach ($collPMReactionHistorics as $obj) {
                        if (false == $this->collPMReactionHistorics->contains($obj)) {
                          $this->collPMReactionHistorics->append($obj);
                        }
                      }

                      $this->collPMReactionHistoricsPartial = true;
                    }

                    $collPMReactionHistorics->getInternalIterator()->rewind();

                    return $collPMReactionHistorics;
                }

                if ($partial && $this->collPMReactionHistorics) {
                    foreach ($this->collPMReactionHistorics as $obj) {
                        if ($obj->isNew()) {
                            $collPMReactionHistorics[] = $obj;
                        }
                    }
                }

                $this->collPMReactionHistorics = $collPMReactionHistorics;
                $this->collPMReactionHistoricsPartial = false;
            }
        }

        return $this->collPMReactionHistorics;
    }

    /**
     * Sets a collection of PMReactionHistoric objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $pMReactionHistorics A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return PDReaction The current object (for fluent API support)
     */
    public function setPMReactionHistorics(PropelCollection $pMReactionHistorics, PropelPDO $con = null)
    {
        $pMReactionHistoricsToDelete = $this->getPMReactionHistorics(new Criteria(), $con)->diff($pMReactionHistorics);


        $this->pMReactionHistoricsScheduledForDeletion = $pMReactionHistoricsToDelete;

        foreach ($pMReactionHistoricsToDelete as $pMReactionHistoricRemoved) {
            $pMReactionHistoricRemoved->setPDReaction(null);
        }

        $this->collPMReactionHistorics = null;
        foreach ($pMReactionHistorics as $pMReactionHistoric) {
            $this->addPMReactionHistoric($pMReactionHistoric);
        }

        $this->collPMReactionHistorics = $pMReactionHistorics;
        $this->collPMReactionHistoricsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related PMReactionHistoric objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related PMReactionHistoric objects.
     * @throws PropelException
     */
    public function countPMReactionHistorics(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collPMReactionHistoricsPartial && !$this->isNew();
        if (null === $this->collPMReactionHistorics || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPMReactionHistorics) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPMReactionHistorics());
            }
            $query = PMReactionHistoricQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPDReaction($this)
                ->count($con);
        }

        return count($this->collPMReactionHistorics);
    }

    /**
     * Method called to associate a PMReactionHistoric object to this object
     * through the PMReactionHistoric foreign key attribute.
     *
     * @param    PMReactionHistoric $l PMReactionHistoric
     * @return PDReaction The current object (for fluent API support)
     */
    public function addPMReactionHistoric(PMReactionHistoric $l)
    {
        if ($this->collPMReactionHistorics === null) {
            $this->initPMReactionHistorics();
            $this->collPMReactionHistoricsPartial = true;
        }

        if (!in_array($l, $this->collPMReactionHistorics->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddPMReactionHistoric($l);

            if ($this->pMReactionHistoricsScheduledForDeletion and $this->pMReactionHistoricsScheduledForDeletion->contains($l)) {
                $this->pMReactionHistoricsScheduledForDeletion->remove($this->pMReactionHistoricsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	PMReactionHistoric $pMReactionHistoric The pMReactionHistoric object to add.
     */
    protected function doAddPMReactionHistoric($pMReactionHistoric)
    {
        $this->collPMReactionHistorics[]= $pMReactionHistoric;
        $pMReactionHistoric->setPDReaction($this);
    }

    /**
     * @param	PMReactionHistoric $pMReactionHistoric The pMReactionHistoric object to remove.
     * @return PDReaction The current object (for fluent API support)
     */
    public function removePMReactionHistoric($pMReactionHistoric)
    {
        if ($this->getPMReactionHistorics()->contains($pMReactionHistoric)) {
            $this->collPMReactionHistorics->remove($this->collPMReactionHistorics->search($pMReactionHistoric));
            if (null === $this->pMReactionHistoricsScheduledForDeletion) {
                $this->pMReactionHistoricsScheduledForDeletion = clone $this->collPMReactionHistorics;
                $this->pMReactionHistoricsScheduledForDeletion->clear();
            }
            $this->pMReactionHistoricsScheduledForDeletion[]= $pMReactionHistoric;
            $pMReactionHistoric->setPDReaction(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this PDReaction is new, it will return
     * an empty collection; or if this PDReaction has previously
     * been saved, it will retrieve related PMReactionHistorics from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in PDReaction.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|PMReactionHistoric[] List of PMReactionHistoric objects
     */
    public function getPMReactionHistoricsJoinPUser($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PMReactionHistoricQuery::create(null, $criteria);
        $query->joinWith('PUser', $join_behavior);

        return $this->getPMReactionHistorics($query, $con);
    }

    /**
     * Clears out the collPuBookmarkDrPUsers collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return PDReaction The current object (for fluent API support)
     * @see        addPuBookmarkDrPUsers()
     */
    public function clearPuBookmarkDrPUsers()
    {
        $this->collPuBookmarkDrPUsers = null; // important to set this to null since that means it is uninitialized
        $this->collPuBookmarkDrPUsersPartial = null;

        return $this;
    }

    /**
     * Initializes the collPuBookmarkDrPUsers collection.
     *
     * By default this just sets the collPuBookmarkDrPUsers collection to an empty collection (like clearPuBookmarkDrPUsers());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @return void
     */
    public function initPuBookmarkDrPUsers()
    {
        $this->collPuBookmarkDrPUsers = new PropelObjectCollection();
        $this->collPuBookmarkDrPUsers->setModel('PUser');
    }

    /**
     * Gets a collection of PUser objects related by a many-to-many relationship
     * to the current object by way of the p_u_bookmark_d_r cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this PDReaction is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria Optional query object to filter the query
     * @param PropelPDO $con Optional connection object
     *
     * @return PropelObjectCollection|PUser[] List of PUser objects
     */
    public function getPuBookmarkDrPUsers($criteria = null, PropelPDO $con = null)
    {
        if (null === $this->collPuBookmarkDrPUsers || null !== $criteria) {
            if ($this->isNew() && null === $this->collPuBookmarkDrPUsers) {
                // return empty collection
                $this->initPuBookmarkDrPUsers();
            } else {
                $collPuBookmarkDrPUsers = PUserQuery::create(null, $criteria)
                    ->filterByPuBookmarkDrPDReaction($this)
                    ->find($con);
                if (null !== $criteria) {
                    return $collPuBookmarkDrPUsers;
                }
                $this->collPuBookmarkDrPUsers = $collPuBookmarkDrPUsers;
            }
        }

        return $this->collPuBookmarkDrPUsers;
    }

    /**
     * Sets a collection of PUser objects related by a many-to-many relationship
     * to the current object by way of the p_u_bookmark_d_r cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $puBookmarkDrPUsers A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return PDReaction The current object (for fluent API support)
     */
    public function setPuBookmarkDrPUsers(PropelCollection $puBookmarkDrPUsers, PropelPDO $con = null)
    {
        $this->clearPuBookmarkDrPUsers();
        $currentPuBookmarkDrPUsers = $this->getPuBookmarkDrPUsers(null, $con);

        $this->puBookmarkDrPUsersScheduledForDeletion = $currentPuBookmarkDrPUsers->diff($puBookmarkDrPUsers);

        foreach ($puBookmarkDrPUsers as $puBookmarkDrPUser) {
            if (!$currentPuBookmarkDrPUsers->contains($puBookmarkDrPUser)) {
                $this->doAddPuBookmarkDrPUser($puBookmarkDrPUser);
            }
        }

        $this->collPuBookmarkDrPUsers = $puBookmarkDrPUsers;

        return $this;
    }

    /**
     * Gets the number of PUser objects related by a many-to-many relationship
     * to the current object by way of the p_u_bookmark_d_r cross-reference table.
     *
     * @param Criteria $criteria Optional query object to filter the query
     * @param boolean $distinct Set to true to force count distinct
     * @param PropelPDO $con Optional connection object
     *
     * @return int the number of related PUser objects
     */
    public function countPuBookmarkDrPUsers($criteria = null, $distinct = false, PropelPDO $con = null)
    {
        if (null === $this->collPuBookmarkDrPUsers || null !== $criteria) {
            if ($this->isNew() && null === $this->collPuBookmarkDrPUsers) {
                return 0;
            } else {
                $query = PUserQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByPuBookmarkDrPDReaction($this)
                    ->count($con);
            }
        } else {
            return count($this->collPuBookmarkDrPUsers);
        }
    }

    /**
     * Associate a PUser object to this object
     * through the p_u_bookmark_d_r cross reference table.
     *
     * @param  PUser $pUser The PUBookmarkDR object to relate
     * @return PDReaction The current object (for fluent API support)
     */
    public function addPuBookmarkDrPUser(PUser $pUser)
    {
        if ($this->collPuBookmarkDrPUsers === null) {
            $this->initPuBookmarkDrPUsers();
        }

        if (!$this->collPuBookmarkDrPUsers->contains($pUser)) { // only add it if the **same** object is not already associated
            $this->doAddPuBookmarkDrPUser($pUser);
            $this->collPuBookmarkDrPUsers[] = $pUser;

            if ($this->puBookmarkDrPUsersScheduledForDeletion and $this->puBookmarkDrPUsersScheduledForDeletion->contains($pUser)) {
                $this->puBookmarkDrPUsersScheduledForDeletion->remove($this->puBookmarkDrPUsersScheduledForDeletion->search($pUser));
            }
        }

        return $this;
    }

    /**
     * @param	PuBookmarkDrPUser $puBookmarkDrPUser The puBookmarkDrPUser object to add.
     */
    protected function doAddPuBookmarkDrPUser(PUser $puBookmarkDrPUser)
    {
        // set the back reference to this object directly as using provided method either results
        // in endless loop or in multiple relations
        if (!$puBookmarkDrPUser->getPuBookmarkDrPDReactions()->contains($this)) { $pUBookmarkDR = new PUBookmarkDR();
            $pUBookmarkDR->setPuBookmarkDrPUser($puBookmarkDrPUser);
            $this->addPuBookmarkDrPDReaction($pUBookmarkDR);

            $foreignCollection = $puBookmarkDrPUser->getPuBookmarkDrPDReactions();
            $foreignCollection[] = $this;
        }
    }

    /**
     * Remove a PUser object to this object
     * through the p_u_bookmark_d_r cross reference table.
     *
     * @param PUser $pUser The PUBookmarkDR object to relate
     * @return PDReaction The current object (for fluent API support)
     */
    public function removePuBookmarkDrPUser(PUser $pUser)
    {
        if ($this->getPuBookmarkDrPUsers()->contains($pUser)) {
            $this->collPuBookmarkDrPUsers->remove($this->collPuBookmarkDrPUsers->search($pUser));
            if (null === $this->puBookmarkDrPUsersScheduledForDeletion) {
                $this->puBookmarkDrPUsersScheduledForDeletion = clone $this->collPuBookmarkDrPUsers;
                $this->puBookmarkDrPUsersScheduledForDeletion->clear();
            }
            $this->puBookmarkDrPUsersScheduledForDeletion[]= $pUser;
        }

        return $this;
    }

    /**
     * Clears out the collPuTrackDrPUsers collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return PDReaction The current object (for fluent API support)
     * @see        addPuTrackDrPUsers()
     */
    public function clearPuTrackDrPUsers()
    {
        $this->collPuTrackDrPUsers = null; // important to set this to null since that means it is uninitialized
        $this->collPuTrackDrPUsersPartial = null;

        return $this;
    }

    /**
     * Initializes the collPuTrackDrPUsers collection.
     *
     * By default this just sets the collPuTrackDrPUsers collection to an empty collection (like clearPuTrackDrPUsers());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @return void
     */
    public function initPuTrackDrPUsers()
    {
        $this->collPuTrackDrPUsers = new PropelObjectCollection();
        $this->collPuTrackDrPUsers->setModel('PUser');
    }

    /**
     * Gets a collection of PUser objects related by a many-to-many relationship
     * to the current object by way of the p_u_track_d_r cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this PDReaction is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria Optional query object to filter the query
     * @param PropelPDO $con Optional connection object
     *
     * @return PropelObjectCollection|PUser[] List of PUser objects
     */
    public function getPuTrackDrPUsers($criteria = null, PropelPDO $con = null)
    {
        if (null === $this->collPuTrackDrPUsers || null !== $criteria) {
            if ($this->isNew() && null === $this->collPuTrackDrPUsers) {
                // return empty collection
                $this->initPuTrackDrPUsers();
            } else {
                $collPuTrackDrPUsers = PUserQuery::create(null, $criteria)
                    ->filterByPuTrackDrPDReaction($this)
                    ->find($con);
                if (null !== $criteria) {
                    return $collPuTrackDrPUsers;
                }
                $this->collPuTrackDrPUsers = $collPuTrackDrPUsers;
            }
        }

        return $this->collPuTrackDrPUsers;
    }

    /**
     * Sets a collection of PUser objects related by a many-to-many relationship
     * to the current object by way of the p_u_track_d_r cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $puTrackDrPUsers A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return PDReaction The current object (for fluent API support)
     */
    public function setPuTrackDrPUsers(PropelCollection $puTrackDrPUsers, PropelPDO $con = null)
    {
        $this->clearPuTrackDrPUsers();
        $currentPuTrackDrPUsers = $this->getPuTrackDrPUsers(null, $con);

        $this->puTrackDrPUsersScheduledForDeletion = $currentPuTrackDrPUsers->diff($puTrackDrPUsers);

        foreach ($puTrackDrPUsers as $puTrackDrPUser) {
            if (!$currentPuTrackDrPUsers->contains($puTrackDrPUser)) {
                $this->doAddPuTrackDrPUser($puTrackDrPUser);
            }
        }

        $this->collPuTrackDrPUsers = $puTrackDrPUsers;

        return $this;
    }

    /**
     * Gets the number of PUser objects related by a many-to-many relationship
     * to the current object by way of the p_u_track_d_r cross-reference table.
     *
     * @param Criteria $criteria Optional query object to filter the query
     * @param boolean $distinct Set to true to force count distinct
     * @param PropelPDO $con Optional connection object
     *
     * @return int the number of related PUser objects
     */
    public function countPuTrackDrPUsers($criteria = null, $distinct = false, PropelPDO $con = null)
    {
        if (null === $this->collPuTrackDrPUsers || null !== $criteria) {
            if ($this->isNew() && null === $this->collPuTrackDrPUsers) {
                return 0;
            } else {
                $query = PUserQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByPuTrackDrPDReaction($this)
                    ->count($con);
            }
        } else {
            return count($this->collPuTrackDrPUsers);
        }
    }

    /**
     * Associate a PUser object to this object
     * through the p_u_track_d_r cross reference table.
     *
     * @param  PUser $pUser The PUTrackDR object to relate
     * @return PDReaction The current object (for fluent API support)
     */
    public function addPuTrackDrPUser(PUser $pUser)
    {
        if ($this->collPuTrackDrPUsers === null) {
            $this->initPuTrackDrPUsers();
        }

        if (!$this->collPuTrackDrPUsers->contains($pUser)) { // only add it if the **same** object is not already associated
            $this->doAddPuTrackDrPUser($pUser);
            $this->collPuTrackDrPUsers[] = $pUser;

            if ($this->puTrackDrPUsersScheduledForDeletion and $this->puTrackDrPUsersScheduledForDeletion->contains($pUser)) {
                $this->puTrackDrPUsersScheduledForDeletion->remove($this->puTrackDrPUsersScheduledForDeletion->search($pUser));
            }
        }

        return $this;
    }

    /**
     * @param	PuTrackDrPUser $puTrackDrPUser The puTrackDrPUser object to add.
     */
    protected function doAddPuTrackDrPUser(PUser $puTrackDrPUser)
    {
        // set the back reference to this object directly as using provided method either results
        // in endless loop or in multiple relations
        if (!$puTrackDrPUser->getPuTrackDrPDReactions()->contains($this)) { $pUTrackDR = new PUTrackDR();
            $pUTrackDR->setPuTrackDrPUser($puTrackDrPUser);
            $this->addPuTrackDrPDReaction($pUTrackDR);

            $foreignCollection = $puTrackDrPUser->getPuTrackDrPDReactions();
            $foreignCollection[] = $this;
        }
    }

    /**
     * Remove a PUser object to this object
     * through the p_u_track_d_r cross reference table.
     *
     * @param PUser $pUser The PUTrackDR object to relate
     * @return PDReaction The current object (for fluent API support)
     */
    public function removePuTrackDrPUser(PUser $pUser)
    {
        if ($this->getPuTrackDrPUsers()->contains($pUser)) {
            $this->collPuTrackDrPUsers->remove($this->collPuTrackDrPUsers->search($pUser));
            if (null === $this->puTrackDrPUsersScheduledForDeletion) {
                $this->puTrackDrPUsersScheduledForDeletion = clone $this->collPuTrackDrPUsers;
                $this->puTrackDrPUsersScheduledForDeletion->clear();
            }
            $this->puTrackDrPUsersScheduledForDeletion[]= $pUser;
        }

        return $this;
    }

    /**
     * Clears out the collPTags collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return PDReaction The current object (for fluent API support)
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
     * to the current object by way of the p_d_r_tagged_t cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this PDReaction is new, it will return
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
                    ->filterByPDReaction($this)
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
     * to the current object by way of the p_d_r_tagged_t cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $pTags A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return PDReaction The current object (for fluent API support)
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
     * to the current object by way of the p_d_r_tagged_t cross-reference table.
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
                    ->filterByPDReaction($this)
                    ->count($con);
            }
        } else {
            return count($this->collPTags);
        }
    }

    /**
     * Associate a PTag object to this object
     * through the p_d_r_tagged_t cross reference table.
     *
     * @param  PTag $pTag The PDRTaggedT object to relate
     * @return PDReaction The current object (for fluent API support)
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
        if (!$pTag->getPDReactions()->contains($this)) { $pDRTaggedT = new PDRTaggedT();
            $pDRTaggedT->setPTag($pTag);
            $this->addPDRTaggedT($pDRTaggedT);

            $foreignCollection = $pTag->getPDReactions();
            $foreignCollection[] = $this;
        }
    }

    /**
     * Remove a PTag object to this object
     * through the p_d_r_tagged_t cross reference table.
     *
     * @param PTag $pTag The PDRTaggedT object to relate
     * @return PDReaction The current object (for fluent API support)
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
        $this->id = null;
        $this->uuid = null;
        $this->p_user_id = null;
        $this->p_d_debate_id = null;
        $this->parent_reaction_id = null;
        $this->p_l_city_id = null;
        $this->p_l_department_id = null;
        $this->p_l_region_id = null;
        $this->p_l_country_id = null;
        $this->p_c_topic_id = null;
        $this->fb_ad_id = null;
        $this->title = null;
        $this->file_name = null;
        $this->copyright = null;
        $this->description = null;
        $this->note_pos = null;
        $this->note_neg = null;
        $this->nb_views = null;
        $this->want_boost = null;
        $this->published = null;
        $this->published_at = null;
        $this->published_by = null;
        $this->favorite = null;
        $this->online = null;
        $this->homepage = null;
        $this->moderated = null;
        $this->moderated_partial = null;
        $this->moderated_at = null;
        $this->indexed_at = null;
        $this->created_at = null;
        $this->updated_at = null;
        $this->slug = null;
        $this->tree_left = null;
        $this->tree_right = null;
        $this->tree_level = null;
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
            if ($this->collPuBookmarkDrPDReactions) {
                foreach ($this->collPuBookmarkDrPDReactions as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPuTrackDrPDReactions) {
                foreach ($this->collPuTrackDrPDReactions as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPDRComments) {
                foreach ($this->collPDRComments as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPDRTaggedTs) {
                foreach ($this->collPDRTaggedTs as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPDMedias) {
                foreach ($this->collPDMedias as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPMReactionHistorics) {
                foreach ($this->collPMReactionHistorics as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPuBookmarkDrPUsers) {
                foreach ($this->collPuBookmarkDrPUsers as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPuTrackDrPUsers) {
                foreach ($this->collPuTrackDrPUsers as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPTags) {
                foreach ($this->collPTags as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->aPUser instanceof Persistent) {
              $this->aPUser->clearAllReferences($deep);
            }
            if ($this->aPDDebate instanceof Persistent) {
              $this->aPDDebate->clearAllReferences($deep);
            }
            if ($this->aPLCity instanceof Persistent) {
              $this->aPLCity->clearAllReferences($deep);
            }
            if ($this->aPLDepartment instanceof Persistent) {
              $this->aPLDepartment->clearAllReferences($deep);
            }
            if ($this->aPLRegion instanceof Persistent) {
              $this->aPLRegion->clearAllReferences($deep);
            }
            if ($this->aPLCountry instanceof Persistent) {
              $this->aPLCountry->clearAllReferences($deep);
            }
            if ($this->aPCTopic instanceof Persistent) {
              $this->aPCTopic->clearAllReferences($deep);
            }

            $this->alreadyInClearAllReferencesDeep = false;
        } // if ($deep)

        // nested_set behavior
        $this->collNestedSetChildren = null;
        $this->aNestedSetParent = null;
        if ($this->collPuBookmarkDrPDReactions instanceof PropelCollection) {
            $this->collPuBookmarkDrPDReactions->clearIterator();
        }
        $this->collPuBookmarkDrPDReactions = null;
        if ($this->collPuTrackDrPDReactions instanceof PropelCollection) {
            $this->collPuTrackDrPDReactions->clearIterator();
        }
        $this->collPuTrackDrPDReactions = null;
        if ($this->collPDRComments instanceof PropelCollection) {
            $this->collPDRComments->clearIterator();
        }
        $this->collPDRComments = null;
        if ($this->collPDRTaggedTs instanceof PropelCollection) {
            $this->collPDRTaggedTs->clearIterator();
        }
        $this->collPDRTaggedTs = null;
        if ($this->collPDMedias instanceof PropelCollection) {
            $this->collPDMedias->clearIterator();
        }
        $this->collPDMedias = null;
        if ($this->collPMReactionHistorics instanceof PropelCollection) {
            $this->collPMReactionHistorics->clearIterator();
        }
        $this->collPMReactionHistorics = null;
        if ($this->collPuBookmarkDrPUsers instanceof PropelCollection) {
            $this->collPuBookmarkDrPUsers->clearIterator();
        }
        $this->collPuBookmarkDrPUsers = null;
        if ($this->collPuTrackDrPUsers instanceof PropelCollection) {
            $this->collPuTrackDrPUsers->clearIterator();
        }
        $this->collPuTrackDrPUsers = null;
        if ($this->collPTags instanceof PropelCollection) {
            $this->collPTags->clearIterator();
        }
        $this->collPTags = null;
        $this->aPUser = null;
        $this->aPDDebate = null;
        $this->aPLCity = null;
        $this->aPLDepartment = null;
        $this->aPLRegion = null;
        $this->aPLCountry = null;
        $this->aPCTopic = null;
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

         $query = PDReactionQuery::create('q')
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
    * If permanent UUID, throw exception p_d_reaction.uuid*/
    public function preUpdate(PropelPDO $con = NULL) {
            $uuid = $this->getUuid();
        if(!is_null($uuid) && !\Ramsey\Uuid\Uuid::isValid($uuid)) {
            throw new \InvalidArgumentException("UUID: $uuid in not valid");
        }
            return true;
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

        if ($targetScope === null) {
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



            if ($targetScope != $scope) {

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


            if (!$preventDefault) {


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
        } catch (Exception $e) {
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
      * @param      Boolean $populateAutoIncrementPrimaryKeys
     *               If true, autoincrement columns are copied from the archive object.
     *               If false, autoincrement columns are left intact.
      *
     * @return     PDReaction The current object (for fluent API support)
     */
    public function populateFromArchive($archive, $populateAutoIncrementPrimaryKeys = false) {
        if ($populateAutoIncrementPrimaryKeys) {
            $this->setId($archive->getId());
        }
        $this->setUuid($archive->getUuid());
        $this->setPUserId($archive->getPUserId());
        $this->setPDDebateId($archive->getPDDebateId());
        $this->setParentReactionId($archive->getParentReactionId());
        $this->setPLCityId($archive->getPLCityId());
        $this->setPLDepartmentId($archive->getPLDepartmentId());
        $this->setPLRegionId($archive->getPLRegionId());
        $this->setPLCountryId($archive->getPLCountryId());
        $this->setPCTopicId($archive->getPCTopicId());
        $this->setFbAdId($archive->getFbAdId());
        $this->setTitle($archive->getTitle());
        $this->setFileName($archive->getFileName());
        $this->setCopyright($archive->getCopyright());
        $this->setDescription($archive->getDescription());
        $this->setNotePos($archive->getNotePos());
        $this->setNoteNeg($archive->getNoteNeg());
        $this->setNbViews($archive->getNbViews());
        $this->setWantBoost($archive->getWantBoost());
        $this->setPublished($archive->getPublished());
        $this->setPublishedAt($archive->getPublishedAt());
        $this->setPublishedBy($archive->getPublishedBy());
        $this->setFavorite($archive->getFavorite());
        $this->setOnline($archive->getOnline());
        $this->setHomepage($archive->getHomepage());
        $this->setModerated($archive->getModerated());
        $this->setModeratedPartial($archive->getModeratedPartial());
        $this->setModeratedAt($archive->getModeratedAt());
        $this->setIndexedAt($archive->getIndexedAt());
        $this->setCreatedAt($archive->getCreatedAt());
        $this->setUpdatedAt($archive->getUpdatedAt());
        $this->setSlug($archive->getSlug());
        $this->setTreeLeft($archive->getTreeLeft());
        $this->setTreeRight($archive->getTreeRight());
        $this->setTreeLevel($archive->getTreeLevel());

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
