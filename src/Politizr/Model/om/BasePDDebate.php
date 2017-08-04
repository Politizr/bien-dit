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
use Politizr\Model\PDDComment;
use Politizr\Model\PDDCommentQuery;
use Politizr\Model\PDDTaggedT;
use Politizr\Model\PDDTaggedTQuery;
use Politizr\Model\PDDebate;
use Politizr\Model\PDDebateArchive;
use Politizr\Model\PDDebateArchiveQuery;
use Politizr\Model\PDDebatePeer;
use Politizr\Model\PDDebateQuery;
use Politizr\Model\PDReaction;
use Politizr\Model\PDReactionQuery;
use Politizr\Model\PEOperation;
use Politizr\Model\PEOperationQuery;
use Politizr\Model\PLCity;
use Politizr\Model\PLCityQuery;
use Politizr\Model\PLCountry;
use Politizr\Model\PLCountryQuery;
use Politizr\Model\PLDepartment;
use Politizr\Model\PLDepartmentQuery;
use Politizr\Model\PLRegion;
use Politizr\Model\PLRegionQuery;
use Politizr\Model\PMDebateHistoric;
use Politizr\Model\PMDebateHistoricQuery;
use Politizr\Model\PTag;
use Politizr\Model\PTagQuery;
use Politizr\Model\PUBookmarkDD;
use Politizr\Model\PUBookmarkDDQuery;
use Politizr\Model\PUFollowDD;
use Politizr\Model\PUFollowDDQuery;
use Politizr\Model\PUTrackDD;
use Politizr\Model\PUTrackDDQuery;
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
     * The value for the p_e_operation_id field.
     * @var        int
     */
    protected $p_e_operation_id;

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
     * @var        PUser
     */
    protected $aPUser;

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
     * @var        PEOperation
     */
    protected $aPEOperation;

    /**
     * @var        PropelObjectCollection|PUFollowDD[] Collection to store aggregation of PUFollowDD objects.
     */
    protected $collPuFollowDdPDDebates;
    protected $collPuFollowDdPDDebatesPartial;

    /**
     * @var        PropelObjectCollection|PUBookmarkDD[] Collection to store aggregation of PUBookmarkDD objects.
     */
    protected $collPuBookmarkDdPDDebates;
    protected $collPuBookmarkDdPDDebatesPartial;

    /**
     * @var        PropelObjectCollection|PUTrackDD[] Collection to store aggregation of PUTrackDD objects.
     */
    protected $collPuTrackDdPDDebates;
    protected $collPuTrackDdPDDebatesPartial;

    /**
     * @var        PropelObjectCollection|PDReaction[] Collection to store aggregation of PDReaction objects.
     */
    protected $collPDReactions;
    protected $collPDReactionsPartial;

    /**
     * @var        PropelObjectCollection|PDDComment[] Collection to store aggregation of PDDComment objects.
     */
    protected $collPDDComments;
    protected $collPDDCommentsPartial;

    /**
     * @var        PropelObjectCollection|PDDTaggedT[] Collection to store aggregation of PDDTaggedT objects.
     */
    protected $collPDDTaggedTs;
    protected $collPDDTaggedTsPartial;

    /**
     * @var        PropelObjectCollection|PMDebateHistoric[] Collection to store aggregation of PMDebateHistoric objects.
     */
    protected $collPMDebateHistorics;
    protected $collPMDebateHistoricsPartial;

    /**
     * @var        PropelObjectCollection|PUser[] Collection to store aggregation of PUser objects.
     */
    protected $collPuFollowDdPUsers;

    /**
     * @var        PropelObjectCollection|PUser[] Collection to store aggregation of PUser objects.
     */
    protected $collPuBookmarkDdPUsers;

    /**
     * @var        PropelObjectCollection|PUser[] Collection to store aggregation of PUser objects.
     */
    protected $collPuTrackDdPUsers;

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
    protected $puBookmarkDdPUsersScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $puTrackDdPUsersScheduledForDeletion = null;

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
    protected $puBookmarkDdPDDebatesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $puTrackDdPDDebatesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $pDReactionsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $pDDCommentsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $pDDTaggedTsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $pMDebateHistoricsScheduledForDeletion = null;

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
     * Get the [p_e_operation_id] column value.
     *
     * @return int
     */
    public function getPEOperationId()
    {

        return $this->p_e_operation_id;
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


        return $this;
    } // setId()

    /**
     * Set the value of [uuid] column.
     *
     * @param  string $v new value
     * @return PDDebate The current object (for fluent API support)
     */
    public function setUuid($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->uuid !== $v) {
            $this->uuid = $v;
            $this->modifiedColumns[] = PDDebatePeer::UUID;
        }


        return $this;
    } // setUuid()

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
     * Set the value of [p_e_operation_id] column.
     *
     * @param  int $v new value
     * @return PDDebate The current object (for fluent API support)
     */
    public function setPEOperationId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->p_e_operation_id !== $v) {
            $this->p_e_operation_id = $v;
            $this->modifiedColumns[] = PDDebatePeer::P_E_OPERATION_ID;
        }

        if ($this->aPEOperation !== null && $this->aPEOperation->getId() !== $v) {
            $this->aPEOperation = null;
        }


        return $this;
    } // setPEOperationId()

    /**
     * Set the value of [p_l_city_id] column.
     *
     * @param  int $v new value
     * @return PDDebate The current object (for fluent API support)
     */
    public function setPLCityId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->p_l_city_id !== $v) {
            $this->p_l_city_id = $v;
            $this->modifiedColumns[] = PDDebatePeer::P_L_CITY_ID;
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
     * @return PDDebate The current object (for fluent API support)
     */
    public function setPLDepartmentId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->p_l_department_id !== $v) {
            $this->p_l_department_id = $v;
            $this->modifiedColumns[] = PDDebatePeer::P_L_DEPARTMENT_ID;
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
     * @return PDDebate The current object (for fluent API support)
     */
    public function setPLRegionId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->p_l_region_id !== $v) {
            $this->p_l_region_id = $v;
            $this->modifiedColumns[] = PDDebatePeer::P_L_REGION_ID;
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
     * @return PDDebate The current object (for fluent API support)
     */
    public function setPLCountryId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->p_l_country_id !== $v) {
            $this->p_l_country_id = $v;
            $this->modifiedColumns[] = PDDebatePeer::P_L_COUNTRY_ID;
        }

        if ($this->aPLCountry !== null && $this->aPLCountry->getId() !== $v) {
            $this->aPLCountry = null;
        }


        return $this;
    } // setPLCountryId()

    /**
     * Set the value of [fb_ad_id] column.
     *
     * @param  string $v new value
     * @return PDDebate The current object (for fluent API support)
     */
    public function setFbAdId($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->fb_ad_id !== $v) {
            $this->fb_ad_id = $v;
            $this->modifiedColumns[] = PDDebatePeer::FB_AD_ID;
        }


        return $this;
    } // setFbAdId()

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
     * Set the value of [copyright] column.
     *
     * @param  string $v new value
     * @return PDDebate The current object (for fluent API support)
     */
    public function setCopyright($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->copyright !== $v) {
            $this->copyright = $v;
            $this->modifiedColumns[] = PDDebatePeer::COPYRIGHT;
        }


        return $this;
    } // setCopyright()

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
     * Sets the value of the [homepage] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param boolean|integer|string $v The new value
     * @return PDDebate The current object (for fluent API support)
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
            $this->modifiedColumns[] = PDDebatePeer::HOMEPAGE;
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
     * @return PDDebate The current object (for fluent API support)
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
            $this->modifiedColumns[] = PDDebatePeer::MODERATED;
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
     * @return PDDebate The current object (for fluent API support)
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
            $this->modifiedColumns[] = PDDebatePeer::MODERATED_PARTIAL;
        }


        return $this;
    } // setModeratedPartial()

    /**
     * Sets the value of [moderated_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return PDDebate The current object (for fluent API support)
     */
    public function setModeratedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->moderated_at !== null || $dt !== null) {
            $currentDateAsString = ($this->moderated_at !== null && $tmpDt = new DateTime($this->moderated_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->moderated_at = $newDateAsString;
                $this->modifiedColumns[] = PDDebatePeer::MODERATED_AT;
            }
        } // if either are not null


        return $this;
    } // setModeratedAt()

    /**
     * Sets the value of [indexed_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return PDDebate The current object (for fluent API support)
     */
    public function setIndexedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->indexed_at !== null || $dt !== null) {
            $currentDateAsString = ($this->indexed_at !== null && $tmpDt = new DateTime($this->indexed_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->indexed_at = $newDateAsString;
                $this->modifiedColumns[] = PDDebatePeer::INDEXED_AT;
            }
        } // if either are not null


        return $this;
    } // setIndexedAt()

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
            $this->p_e_operation_id = ($row[$startcol + 3] !== null) ? (int) $row[$startcol + 3] : null;
            $this->p_l_city_id = ($row[$startcol + 4] !== null) ? (int) $row[$startcol + 4] : null;
            $this->p_l_department_id = ($row[$startcol + 5] !== null) ? (int) $row[$startcol + 5] : null;
            $this->p_l_region_id = ($row[$startcol + 6] !== null) ? (int) $row[$startcol + 6] : null;
            $this->p_l_country_id = ($row[$startcol + 7] !== null) ? (int) $row[$startcol + 7] : null;
            $this->fb_ad_id = ($row[$startcol + 8] !== null) ? (string) $row[$startcol + 8] : null;
            $this->title = ($row[$startcol + 9] !== null) ? (string) $row[$startcol + 9] : null;
            $this->file_name = ($row[$startcol + 10] !== null) ? (string) $row[$startcol + 10] : null;
            $this->copyright = ($row[$startcol + 11] !== null) ? (string) $row[$startcol + 11] : null;
            $this->description = ($row[$startcol + 12] !== null) ? (string) $row[$startcol + 12] : null;
            $this->note_pos = ($row[$startcol + 13] !== null) ? (int) $row[$startcol + 13] : null;
            $this->note_neg = ($row[$startcol + 14] !== null) ? (int) $row[$startcol + 14] : null;
            $this->nb_views = ($row[$startcol + 15] !== null) ? (int) $row[$startcol + 15] : null;
            $this->published = ($row[$startcol + 16] !== null) ? (boolean) $row[$startcol + 16] : null;
            $this->published_at = ($row[$startcol + 17] !== null) ? (string) $row[$startcol + 17] : null;
            $this->published_by = ($row[$startcol + 18] !== null) ? (string) $row[$startcol + 18] : null;
            $this->favorite = ($row[$startcol + 19] !== null) ? (boolean) $row[$startcol + 19] : null;
            $this->online = ($row[$startcol + 20] !== null) ? (boolean) $row[$startcol + 20] : null;
            $this->homepage = ($row[$startcol + 21] !== null) ? (boolean) $row[$startcol + 21] : null;
            $this->moderated = ($row[$startcol + 22] !== null) ? (boolean) $row[$startcol + 22] : null;
            $this->moderated_partial = ($row[$startcol + 23] !== null) ? (boolean) $row[$startcol + 23] : null;
            $this->moderated_at = ($row[$startcol + 24] !== null) ? (string) $row[$startcol + 24] : null;
            $this->indexed_at = ($row[$startcol + 25] !== null) ? (string) $row[$startcol + 25] : null;
            $this->created_at = ($row[$startcol + 26] !== null) ? (string) $row[$startcol + 26] : null;
            $this->updated_at = ($row[$startcol + 27] !== null) ? (string) $row[$startcol + 27] : null;
            $this->slug = ($row[$startcol + 28] !== null) ? (string) $row[$startcol + 28] : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }
            $this->postHydrate($row, $startcol, $rehydrate);

            return $startcol + 29; // 29 = PDDebatePeer::NUM_HYDRATE_COLUMNS.

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

        if ($this->aPUser !== null && $this->p_user_id !== $this->aPUser->getId()) {
            $this->aPUser = null;
        }
        if ($this->aPEOperation !== null && $this->p_e_operation_id !== $this->aPEOperation->getId()) {
            $this->aPEOperation = null;
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

            $this->aPUser = null;
            $this->aPLCity = null;
            $this->aPLDepartment = null;
            $this->aPLRegion = null;
            $this->aPLCountry = null;
            $this->aPEOperation = null;
            $this->collPuFollowDdPDDebates = null;

            $this->collPuBookmarkDdPDDebates = null;

            $this->collPuTrackDdPDDebates = null;

            $this->collPDReactions = null;

            $this->collPDDComments = null;

            $this->collPDDTaggedTs = null;

            $this->collPMDebateHistorics = null;

            $this->collPuFollowDdPUsers = null;
            $this->collPuBookmarkDdPUsers = null;
            $this->collPuTrackDdPUsers = null;
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
            // sluggable behavior

            if ($this->isColumnModified(PDDebatePeer::SLUG) && $this->getSlug()) {
                $this->setSlug($this->makeSlugUnique($this->getSlug()));
            } elseif ($this->isColumnModified(PDDebatePeer::TITLE)) {
                $this->setSlug($this->createSlug());
            } elseif (!$this->getSlug()) {
                $this->setSlug($this->createSlug());
            }
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
            // were passed to this object by their corresponding set
            // method.  This object relates to these object(s) by a
            // foreign key reference.

            if ($this->aPUser !== null) {
                if ($this->aPUser->isModified() || $this->aPUser->isNew()) {
                    $affectedRows += $this->aPUser->save($con);
                }
                $this->setPUser($this->aPUser);
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

            if ($this->aPEOperation !== null) {
                if ($this->aPEOperation->isModified() || $this->aPEOperation->isNew()) {
                    $affectedRows += $this->aPEOperation->save($con);
                }
                $this->setPEOperation($this->aPEOperation);
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

            if ($this->puBookmarkDdPUsersScheduledForDeletion !== null) {
                if (!$this->puBookmarkDdPUsersScheduledForDeletion->isEmpty()) {
                    $pks = array();
                    $pk = $this->getPrimaryKey();
                    foreach ($this->puBookmarkDdPUsersScheduledForDeletion->getPrimaryKeys(false) as $remotePk) {
                        $pks[] = array($remotePk, $pk);
                    }
                    PUBookmarkDDQuery::create()
                        ->filterByPrimaryKeys($pks)
                        ->delete($con);
                    $this->puBookmarkDdPUsersScheduledForDeletion = null;
                }

                foreach ($this->getPuBookmarkDdPUsers() as $puBookmarkDdPUser) {
                    if ($puBookmarkDdPUser->isModified()) {
                        $puBookmarkDdPUser->save($con);
                    }
                }
            } elseif ($this->collPuBookmarkDdPUsers) {
                foreach ($this->collPuBookmarkDdPUsers as $puBookmarkDdPUser) {
                    if ($puBookmarkDdPUser->isModified()) {
                        $puBookmarkDdPUser->save($con);
                    }
                }
            }

            if ($this->puTrackDdPUsersScheduledForDeletion !== null) {
                if (!$this->puTrackDdPUsersScheduledForDeletion->isEmpty()) {
                    $pks = array();
                    $pk = $this->getPrimaryKey();
                    foreach ($this->puTrackDdPUsersScheduledForDeletion->getPrimaryKeys(false) as $remotePk) {
                        $pks[] = array($remotePk, $pk);
                    }
                    PUTrackDDQuery::create()
                        ->filterByPrimaryKeys($pks)
                        ->delete($con);
                    $this->puTrackDdPUsersScheduledForDeletion = null;
                }

                foreach ($this->getPuTrackDdPUsers() as $puTrackDdPUser) {
                    if ($puTrackDdPUser->isModified()) {
                        $puTrackDdPUser->save($con);
                    }
                }
            } elseif ($this->collPuTrackDdPUsers) {
                foreach ($this->collPuTrackDdPUsers as $puTrackDdPUser) {
                    if ($puTrackDdPUser->isModified()) {
                        $puTrackDdPUser->save($con);
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

            if ($this->puBookmarkDdPDDebatesScheduledForDeletion !== null) {
                if (!$this->puBookmarkDdPDDebatesScheduledForDeletion->isEmpty()) {
                    PUBookmarkDDQuery::create()
                        ->filterByPrimaryKeys($this->puBookmarkDdPDDebatesScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->puBookmarkDdPDDebatesScheduledForDeletion = null;
                }
            }

            if ($this->collPuBookmarkDdPDDebates !== null) {
                foreach ($this->collPuBookmarkDdPDDebates as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->puTrackDdPDDebatesScheduledForDeletion !== null) {
                if (!$this->puTrackDdPDDebatesScheduledForDeletion->isEmpty()) {
                    PUTrackDDQuery::create()
                        ->filterByPrimaryKeys($this->puTrackDdPDDebatesScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->puTrackDdPDDebatesScheduledForDeletion = null;
                }
            }

            if ($this->collPuTrackDdPDDebates !== null) {
                foreach ($this->collPuTrackDdPDDebates as $referrerFK) {
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

            if ($this->pDDCommentsScheduledForDeletion !== null) {
                if (!$this->pDDCommentsScheduledForDeletion->isEmpty()) {
                    PDDCommentQuery::create()
                        ->filterByPrimaryKeys($this->pDDCommentsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->pDDCommentsScheduledForDeletion = null;
                }
            }

            if ($this->collPDDComments !== null) {
                foreach ($this->collPDDComments as $referrerFK) {
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

            if ($this->pMDebateHistoricsScheduledForDeletion !== null) {
                if (!$this->pMDebateHistoricsScheduledForDeletion->isEmpty()) {
                    foreach ($this->pMDebateHistoricsScheduledForDeletion as $pMDebateHistoric) {
                        // need to save related object because we set the relation to null
                        $pMDebateHistoric->save($con);
                    }
                    $this->pMDebateHistoricsScheduledForDeletion = null;
                }
            }

            if ($this->collPMDebateHistorics !== null) {
                foreach ($this->collPMDebateHistorics as $referrerFK) {
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
        if ($this->isColumnModified(PDDebatePeer::UUID)) {
            $modifiedColumns[':p' . $index++]  = '`uuid`';
        }
        if ($this->isColumnModified(PDDebatePeer::P_USER_ID)) {
            $modifiedColumns[':p' . $index++]  = '`p_user_id`';
        }
        if ($this->isColumnModified(PDDebatePeer::P_E_OPERATION_ID)) {
            $modifiedColumns[':p' . $index++]  = '`p_e_operation_id`';
        }
        if ($this->isColumnModified(PDDebatePeer::P_L_CITY_ID)) {
            $modifiedColumns[':p' . $index++]  = '`p_l_city_id`';
        }
        if ($this->isColumnModified(PDDebatePeer::P_L_DEPARTMENT_ID)) {
            $modifiedColumns[':p' . $index++]  = '`p_l_department_id`';
        }
        if ($this->isColumnModified(PDDebatePeer::P_L_REGION_ID)) {
            $modifiedColumns[':p' . $index++]  = '`p_l_region_id`';
        }
        if ($this->isColumnModified(PDDebatePeer::P_L_COUNTRY_ID)) {
            $modifiedColumns[':p' . $index++]  = '`p_l_country_id`';
        }
        if ($this->isColumnModified(PDDebatePeer::FB_AD_ID)) {
            $modifiedColumns[':p' . $index++]  = '`fb_ad_id`';
        }
        if ($this->isColumnModified(PDDebatePeer::TITLE)) {
            $modifiedColumns[':p' . $index++]  = '`title`';
        }
        if ($this->isColumnModified(PDDebatePeer::FILE_NAME)) {
            $modifiedColumns[':p' . $index++]  = '`file_name`';
        }
        if ($this->isColumnModified(PDDebatePeer::COPYRIGHT)) {
            $modifiedColumns[':p' . $index++]  = '`copyright`';
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
        if ($this->isColumnModified(PDDebatePeer::HOMEPAGE)) {
            $modifiedColumns[':p' . $index++]  = '`homepage`';
        }
        if ($this->isColumnModified(PDDebatePeer::MODERATED)) {
            $modifiedColumns[':p' . $index++]  = '`moderated`';
        }
        if ($this->isColumnModified(PDDebatePeer::MODERATED_PARTIAL)) {
            $modifiedColumns[':p' . $index++]  = '`moderated_partial`';
        }
        if ($this->isColumnModified(PDDebatePeer::MODERATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`moderated_at`';
        }
        if ($this->isColumnModified(PDDebatePeer::INDEXED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`indexed_at`';
        }
        if ($this->isColumnModified(PDDebatePeer::CREATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`created_at`';
        }
        if ($this->isColumnModified(PDDebatePeer::UPDATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`updated_at`';
        }
        if ($this->isColumnModified(PDDebatePeer::SLUG)) {
            $modifiedColumns[':p' . $index++]  = '`slug`';
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
                    case '`uuid`':
                        $stmt->bindValue($identifier, $this->uuid, PDO::PARAM_STR);
                        break;
                    case '`p_user_id`':
                        $stmt->bindValue($identifier, $this->p_user_id, PDO::PARAM_INT);
                        break;
                    case '`p_e_operation_id`':
                        $stmt->bindValue($identifier, $this->p_e_operation_id, PDO::PARAM_INT);
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
                return $this->getUuid();
                break;
            case 2:
                return $this->getPUserId();
                break;
            case 3:
                return $this->getPEOperationId();
                break;
            case 4:
                return $this->getPLCityId();
                break;
            case 5:
                return $this->getPLDepartmentId();
                break;
            case 6:
                return $this->getPLRegionId();
                break;
            case 7:
                return $this->getPLCountryId();
                break;
            case 8:
                return $this->getFbAdId();
                break;
            case 9:
                return $this->getTitle();
                break;
            case 10:
                return $this->getFileName();
                break;
            case 11:
                return $this->getCopyright();
                break;
            case 12:
                return $this->getDescription();
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
                return $this->getFavorite();
                break;
            case 20:
                return $this->getOnline();
                break;
            case 21:
                return $this->getHomepage();
                break;
            case 22:
                return $this->getModerated();
                break;
            case 23:
                return $this->getModeratedPartial();
                break;
            case 24:
                return $this->getModeratedAt();
                break;
            case 25:
                return $this->getIndexedAt();
                break;
            case 26:
                return $this->getCreatedAt();
                break;
            case 27:
                return $this->getUpdatedAt();
                break;
            case 28:
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
        if (isset($alreadyDumpedObjects['PDDebate'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['PDDebate'][$this->getPrimaryKey()] = true;
        $keys = PDDebatePeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getUuid(),
            $keys[2] => $this->getPUserId(),
            $keys[3] => $this->getPEOperationId(),
            $keys[4] => $this->getPLCityId(),
            $keys[5] => $this->getPLDepartmentId(),
            $keys[6] => $this->getPLRegionId(),
            $keys[7] => $this->getPLCountryId(),
            $keys[8] => $this->getFbAdId(),
            $keys[9] => $this->getTitle(),
            $keys[10] => $this->getFileName(),
            $keys[11] => $this->getCopyright(),
            $keys[12] => $this->getDescription(),
            $keys[13] => $this->getNotePos(),
            $keys[14] => $this->getNoteNeg(),
            $keys[15] => $this->getNbViews(),
            $keys[16] => $this->getPublished(),
            $keys[17] => $this->getPublishedAt(),
            $keys[18] => $this->getPublishedBy(),
            $keys[19] => $this->getFavorite(),
            $keys[20] => $this->getOnline(),
            $keys[21] => $this->getHomepage(),
            $keys[22] => $this->getModerated(),
            $keys[23] => $this->getModeratedPartial(),
            $keys[24] => $this->getModeratedAt(),
            $keys[25] => $this->getIndexedAt(),
            $keys[26] => $this->getCreatedAt(),
            $keys[27] => $this->getUpdatedAt(),
            $keys[28] => $this->getSlug(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aPUser) {
                $result['PUser'] = $this->aPUser->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
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
            if (null !== $this->aPEOperation) {
                $result['PEOperation'] = $this->aPEOperation->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->collPuFollowDdPDDebates) {
                $result['PuFollowDdPDDebates'] = $this->collPuFollowDdPDDebates->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPuBookmarkDdPDDebates) {
                $result['PuBookmarkDdPDDebates'] = $this->collPuBookmarkDdPDDebates->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPuTrackDdPDDebates) {
                $result['PuTrackDdPDDebates'] = $this->collPuTrackDdPDDebates->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPDReactions) {
                $result['PDReactions'] = $this->collPDReactions->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPDDComments) {
                $result['PDDComments'] = $this->collPDDComments->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPDDTaggedTs) {
                $result['PDDTaggedTs'] = $this->collPDDTaggedTs->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPMDebateHistorics) {
                $result['PMDebateHistorics'] = $this->collPMDebateHistorics->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
                $this->setUuid($value);
                break;
            case 2:
                $this->setPUserId($value);
                break;
            case 3:
                $this->setPEOperationId($value);
                break;
            case 4:
                $this->setPLCityId($value);
                break;
            case 5:
                $this->setPLDepartmentId($value);
                break;
            case 6:
                $this->setPLRegionId($value);
                break;
            case 7:
                $this->setPLCountryId($value);
                break;
            case 8:
                $this->setFbAdId($value);
                break;
            case 9:
                $this->setTitle($value);
                break;
            case 10:
                $this->setFileName($value);
                break;
            case 11:
                $this->setCopyright($value);
                break;
            case 12:
                $this->setDescription($value);
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
                $this->setFavorite($value);
                break;
            case 20:
                $this->setOnline($value);
                break;
            case 21:
                $this->setHomepage($value);
                break;
            case 22:
                $this->setModerated($value);
                break;
            case 23:
                $this->setModeratedPartial($value);
                break;
            case 24:
                $this->setModeratedAt($value);
                break;
            case 25:
                $this->setIndexedAt($value);
                break;
            case 26:
                $this->setCreatedAt($value);
                break;
            case 27:
                $this->setUpdatedAt($value);
                break;
            case 28:
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
        $keys = PDDebatePeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setUuid($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setPUserId($arr[$keys[2]]);
        if (array_key_exists($keys[3], $arr)) $this->setPEOperationId($arr[$keys[3]]);
        if (array_key_exists($keys[4], $arr)) $this->setPLCityId($arr[$keys[4]]);
        if (array_key_exists($keys[5], $arr)) $this->setPLDepartmentId($arr[$keys[5]]);
        if (array_key_exists($keys[6], $arr)) $this->setPLRegionId($arr[$keys[6]]);
        if (array_key_exists($keys[7], $arr)) $this->setPLCountryId($arr[$keys[7]]);
        if (array_key_exists($keys[8], $arr)) $this->setFbAdId($arr[$keys[8]]);
        if (array_key_exists($keys[9], $arr)) $this->setTitle($arr[$keys[9]]);
        if (array_key_exists($keys[10], $arr)) $this->setFileName($arr[$keys[10]]);
        if (array_key_exists($keys[11], $arr)) $this->setCopyright($arr[$keys[11]]);
        if (array_key_exists($keys[12], $arr)) $this->setDescription($arr[$keys[12]]);
        if (array_key_exists($keys[13], $arr)) $this->setNotePos($arr[$keys[13]]);
        if (array_key_exists($keys[14], $arr)) $this->setNoteNeg($arr[$keys[14]]);
        if (array_key_exists($keys[15], $arr)) $this->setNbViews($arr[$keys[15]]);
        if (array_key_exists($keys[16], $arr)) $this->setPublished($arr[$keys[16]]);
        if (array_key_exists($keys[17], $arr)) $this->setPublishedAt($arr[$keys[17]]);
        if (array_key_exists($keys[18], $arr)) $this->setPublishedBy($arr[$keys[18]]);
        if (array_key_exists($keys[19], $arr)) $this->setFavorite($arr[$keys[19]]);
        if (array_key_exists($keys[20], $arr)) $this->setOnline($arr[$keys[20]]);
        if (array_key_exists($keys[21], $arr)) $this->setHomepage($arr[$keys[21]]);
        if (array_key_exists($keys[22], $arr)) $this->setModerated($arr[$keys[22]]);
        if (array_key_exists($keys[23], $arr)) $this->setModeratedPartial($arr[$keys[23]]);
        if (array_key_exists($keys[24], $arr)) $this->setModeratedAt($arr[$keys[24]]);
        if (array_key_exists($keys[25], $arr)) $this->setIndexedAt($arr[$keys[25]]);
        if (array_key_exists($keys[26], $arr)) $this->setCreatedAt($arr[$keys[26]]);
        if (array_key_exists($keys[27], $arr)) $this->setUpdatedAt($arr[$keys[27]]);
        if (array_key_exists($keys[28], $arr)) $this->setSlug($arr[$keys[28]]);
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
        if ($this->isColumnModified(PDDebatePeer::UUID)) $criteria->add(PDDebatePeer::UUID, $this->uuid);
        if ($this->isColumnModified(PDDebatePeer::P_USER_ID)) $criteria->add(PDDebatePeer::P_USER_ID, $this->p_user_id);
        if ($this->isColumnModified(PDDebatePeer::P_E_OPERATION_ID)) $criteria->add(PDDebatePeer::P_E_OPERATION_ID, $this->p_e_operation_id);
        if ($this->isColumnModified(PDDebatePeer::P_L_CITY_ID)) $criteria->add(PDDebatePeer::P_L_CITY_ID, $this->p_l_city_id);
        if ($this->isColumnModified(PDDebatePeer::P_L_DEPARTMENT_ID)) $criteria->add(PDDebatePeer::P_L_DEPARTMENT_ID, $this->p_l_department_id);
        if ($this->isColumnModified(PDDebatePeer::P_L_REGION_ID)) $criteria->add(PDDebatePeer::P_L_REGION_ID, $this->p_l_region_id);
        if ($this->isColumnModified(PDDebatePeer::P_L_COUNTRY_ID)) $criteria->add(PDDebatePeer::P_L_COUNTRY_ID, $this->p_l_country_id);
        if ($this->isColumnModified(PDDebatePeer::FB_AD_ID)) $criteria->add(PDDebatePeer::FB_AD_ID, $this->fb_ad_id);
        if ($this->isColumnModified(PDDebatePeer::TITLE)) $criteria->add(PDDebatePeer::TITLE, $this->title);
        if ($this->isColumnModified(PDDebatePeer::FILE_NAME)) $criteria->add(PDDebatePeer::FILE_NAME, $this->file_name);
        if ($this->isColumnModified(PDDebatePeer::COPYRIGHT)) $criteria->add(PDDebatePeer::COPYRIGHT, $this->copyright);
        if ($this->isColumnModified(PDDebatePeer::DESCRIPTION)) $criteria->add(PDDebatePeer::DESCRIPTION, $this->description);
        if ($this->isColumnModified(PDDebatePeer::NOTE_POS)) $criteria->add(PDDebatePeer::NOTE_POS, $this->note_pos);
        if ($this->isColumnModified(PDDebatePeer::NOTE_NEG)) $criteria->add(PDDebatePeer::NOTE_NEG, $this->note_neg);
        if ($this->isColumnModified(PDDebatePeer::NB_VIEWS)) $criteria->add(PDDebatePeer::NB_VIEWS, $this->nb_views);
        if ($this->isColumnModified(PDDebatePeer::PUBLISHED)) $criteria->add(PDDebatePeer::PUBLISHED, $this->published);
        if ($this->isColumnModified(PDDebatePeer::PUBLISHED_AT)) $criteria->add(PDDebatePeer::PUBLISHED_AT, $this->published_at);
        if ($this->isColumnModified(PDDebatePeer::PUBLISHED_BY)) $criteria->add(PDDebatePeer::PUBLISHED_BY, $this->published_by);
        if ($this->isColumnModified(PDDebatePeer::FAVORITE)) $criteria->add(PDDebatePeer::FAVORITE, $this->favorite);
        if ($this->isColumnModified(PDDebatePeer::ONLINE)) $criteria->add(PDDebatePeer::ONLINE, $this->online);
        if ($this->isColumnModified(PDDebatePeer::HOMEPAGE)) $criteria->add(PDDebatePeer::HOMEPAGE, $this->homepage);
        if ($this->isColumnModified(PDDebatePeer::MODERATED)) $criteria->add(PDDebatePeer::MODERATED, $this->moderated);
        if ($this->isColumnModified(PDDebatePeer::MODERATED_PARTIAL)) $criteria->add(PDDebatePeer::MODERATED_PARTIAL, $this->moderated_partial);
        if ($this->isColumnModified(PDDebatePeer::MODERATED_AT)) $criteria->add(PDDebatePeer::MODERATED_AT, $this->moderated_at);
        if ($this->isColumnModified(PDDebatePeer::INDEXED_AT)) $criteria->add(PDDebatePeer::INDEXED_AT, $this->indexed_at);
        if ($this->isColumnModified(PDDebatePeer::CREATED_AT)) $criteria->add(PDDebatePeer::CREATED_AT, $this->created_at);
        if ($this->isColumnModified(PDDebatePeer::UPDATED_AT)) $criteria->add(PDDebatePeer::UPDATED_AT, $this->updated_at);
        if ($this->isColumnModified(PDDebatePeer::SLUG)) $criteria->add(PDDebatePeer::SLUG, $this->slug);

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
        $copyObj->setUuid($this->getUuid());
        $copyObj->setPUserId($this->getPUserId());
        $copyObj->setPEOperationId($this->getPEOperationId());
        $copyObj->setPLCityId($this->getPLCityId());
        $copyObj->setPLDepartmentId($this->getPLDepartmentId());
        $copyObj->setPLRegionId($this->getPLRegionId());
        $copyObj->setPLCountryId($this->getPLCountryId());
        $copyObj->setFbAdId($this->getFbAdId());
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
        $copyObj->setHomepage($this->getHomepage());
        $copyObj->setModerated($this->getModerated());
        $copyObj->setModeratedPartial($this->getModeratedPartial());
        $copyObj->setModeratedAt($this->getModeratedAt());
        $copyObj->setIndexedAt($this->getIndexedAt());
        $copyObj->setCreatedAt($this->getCreatedAt());
        $copyObj->setUpdatedAt($this->getUpdatedAt());
        $copyObj->setSlug($this->getSlug());

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

            foreach ($this->getPuBookmarkDdPDDebates() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPuBookmarkDdPDDebate($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPuTrackDdPDDebates() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPuTrackDdPDDebate($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPDReactions() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPDReaction($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPDDComments() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPDDComment($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPDDTaggedTs() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPDDTaggedT($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPMDebateHistorics() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPMDebateHistoric($relObj->copy($deepCopy));
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
     * Declares an association between this object and a PLCity object.
     *
     * @param                  PLCity $v
     * @return PDDebate The current object (for fluent API support)
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
            $v->addPDDebate($this);
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
                $this->aPLCity->addPDDebates($this);
             */
        }

        return $this->aPLCity;
    }

    /**
     * Declares an association between this object and a PLDepartment object.
     *
     * @param                  PLDepartment $v
     * @return PDDebate The current object (for fluent API support)
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
            $v->addPDDebate($this);
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
                $this->aPLDepartment->addPDDebates($this);
             */
        }

        return $this->aPLDepartment;
    }

    /**
     * Declares an association between this object and a PLRegion object.
     *
     * @param                  PLRegion $v
     * @return PDDebate The current object (for fluent API support)
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
            $v->addPDDebate($this);
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
                $this->aPLRegion->addPDDebates($this);
             */
        }

        return $this->aPLRegion;
    }

    /**
     * Declares an association between this object and a PLCountry object.
     *
     * @param                  PLCountry $v
     * @return PDDebate The current object (for fluent API support)
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
            $v->addPDDebate($this);
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
                $this->aPLCountry->addPDDebates($this);
             */
        }

        return $this->aPLCountry;
    }

    /**
     * Declares an association between this object and a PEOperation object.
     *
     * @param                  PEOperation $v
     * @return PDDebate The current object (for fluent API support)
     * @throws PropelException
     */
    public function setPEOperation(PEOperation $v = null)
    {
        if ($v === null) {
            $this->setPEOperationId(NULL);
        } else {
            $this->setPEOperationId($v->getId());
        }

        $this->aPEOperation = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the PEOperation object, it will not be re-added.
        if ($v !== null) {
            $v->addPDDebate($this);
        }


        return $this;
    }


    /**
     * Get the associated PEOperation object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return PEOperation The associated PEOperation object.
     * @throws PropelException
     */
    public function getPEOperation(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aPEOperation === null && ($this->p_e_operation_id !== null) && $doQuery) {
            $this->aPEOperation = PEOperationQuery::create()->findPk($this->p_e_operation_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aPEOperation->addPDDebates($this);
             */
        }

        return $this->aPEOperation;
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
        if ('PuBookmarkDdPDDebate' == $relationName) {
            $this->initPuBookmarkDdPDDebates();
        }
        if ('PuTrackDdPDDebate' == $relationName) {
            $this->initPuTrackDdPDDebates();
        }
        if ('PDReaction' == $relationName) {
            $this->initPDReactions();
        }
        if ('PDDComment' == $relationName) {
            $this->initPDDComments();
        }
        if ('PDDTaggedT' == $relationName) {
            $this->initPDDTaggedTs();
        }
        if ('PMDebateHistoric' == $relationName) {
            $this->initPMDebateHistorics();
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
     * Clears out the collPuBookmarkDdPDDebates collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return PDDebate The current object (for fluent API support)
     * @see        addPuBookmarkDdPDDebates()
     */
    public function clearPuBookmarkDdPDDebates()
    {
        $this->collPuBookmarkDdPDDebates = null; // important to set this to null since that means it is uninitialized
        $this->collPuBookmarkDdPDDebatesPartial = null;

        return $this;
    }

    /**
     * reset is the collPuBookmarkDdPDDebates collection loaded partially
     *
     * @return void
     */
    public function resetPartialPuBookmarkDdPDDebates($v = true)
    {
        $this->collPuBookmarkDdPDDebatesPartial = $v;
    }

    /**
     * Initializes the collPuBookmarkDdPDDebates collection.
     *
     * By default this just sets the collPuBookmarkDdPDDebates collection to an empty array (like clearcollPuBookmarkDdPDDebates());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPuBookmarkDdPDDebates($overrideExisting = true)
    {
        if (null !== $this->collPuBookmarkDdPDDebates && !$overrideExisting) {
            return;
        }
        $this->collPuBookmarkDdPDDebates = new PropelObjectCollection();
        $this->collPuBookmarkDdPDDebates->setModel('PUBookmarkDD');
    }

    /**
     * Gets an array of PUBookmarkDD objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this PDDebate is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|PUBookmarkDD[] List of PUBookmarkDD objects
     * @throws PropelException
     */
    public function getPuBookmarkDdPDDebates($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collPuBookmarkDdPDDebatesPartial && !$this->isNew();
        if (null === $this->collPuBookmarkDdPDDebates || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPuBookmarkDdPDDebates) {
                // return empty collection
                $this->initPuBookmarkDdPDDebates();
            } else {
                $collPuBookmarkDdPDDebates = PUBookmarkDDQuery::create(null, $criteria)
                    ->filterByPuBookmarkDdPDDebate($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collPuBookmarkDdPDDebatesPartial && count($collPuBookmarkDdPDDebates)) {
                      $this->initPuBookmarkDdPDDebates(false);

                      foreach ($collPuBookmarkDdPDDebates as $obj) {
                        if (false == $this->collPuBookmarkDdPDDebates->contains($obj)) {
                          $this->collPuBookmarkDdPDDebates->append($obj);
                        }
                      }

                      $this->collPuBookmarkDdPDDebatesPartial = true;
                    }

                    $collPuBookmarkDdPDDebates->getInternalIterator()->rewind();

                    return $collPuBookmarkDdPDDebates;
                }

                if ($partial && $this->collPuBookmarkDdPDDebates) {
                    foreach ($this->collPuBookmarkDdPDDebates as $obj) {
                        if ($obj->isNew()) {
                            $collPuBookmarkDdPDDebates[] = $obj;
                        }
                    }
                }

                $this->collPuBookmarkDdPDDebates = $collPuBookmarkDdPDDebates;
                $this->collPuBookmarkDdPDDebatesPartial = false;
            }
        }

        return $this->collPuBookmarkDdPDDebates;
    }

    /**
     * Sets a collection of PuBookmarkDdPDDebate objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $puBookmarkDdPDDebates A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return PDDebate The current object (for fluent API support)
     */
    public function setPuBookmarkDdPDDebates(PropelCollection $puBookmarkDdPDDebates, PropelPDO $con = null)
    {
        $puBookmarkDdPDDebatesToDelete = $this->getPuBookmarkDdPDDebates(new Criteria(), $con)->diff($puBookmarkDdPDDebates);


        $this->puBookmarkDdPDDebatesScheduledForDeletion = $puBookmarkDdPDDebatesToDelete;

        foreach ($puBookmarkDdPDDebatesToDelete as $puBookmarkDdPDDebateRemoved) {
            $puBookmarkDdPDDebateRemoved->setPuBookmarkDdPDDebate(null);
        }

        $this->collPuBookmarkDdPDDebates = null;
        foreach ($puBookmarkDdPDDebates as $puBookmarkDdPDDebate) {
            $this->addPuBookmarkDdPDDebate($puBookmarkDdPDDebate);
        }

        $this->collPuBookmarkDdPDDebates = $puBookmarkDdPDDebates;
        $this->collPuBookmarkDdPDDebatesPartial = false;

        return $this;
    }

    /**
     * Returns the number of related PUBookmarkDD objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related PUBookmarkDD objects.
     * @throws PropelException
     */
    public function countPuBookmarkDdPDDebates(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collPuBookmarkDdPDDebatesPartial && !$this->isNew();
        if (null === $this->collPuBookmarkDdPDDebates || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPuBookmarkDdPDDebates) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPuBookmarkDdPDDebates());
            }
            $query = PUBookmarkDDQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPuBookmarkDdPDDebate($this)
                ->count($con);
        }

        return count($this->collPuBookmarkDdPDDebates);
    }

    /**
     * Method called to associate a PUBookmarkDD object to this object
     * through the PUBookmarkDD foreign key attribute.
     *
     * @param    PUBookmarkDD $l PUBookmarkDD
     * @return PDDebate The current object (for fluent API support)
     */
    public function addPuBookmarkDdPDDebate(PUBookmarkDD $l)
    {
        if ($this->collPuBookmarkDdPDDebates === null) {
            $this->initPuBookmarkDdPDDebates();
            $this->collPuBookmarkDdPDDebatesPartial = true;
        }

        if (!in_array($l, $this->collPuBookmarkDdPDDebates->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddPuBookmarkDdPDDebate($l);

            if ($this->puBookmarkDdPDDebatesScheduledForDeletion and $this->puBookmarkDdPDDebatesScheduledForDeletion->contains($l)) {
                $this->puBookmarkDdPDDebatesScheduledForDeletion->remove($this->puBookmarkDdPDDebatesScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	PuBookmarkDdPDDebate $puBookmarkDdPDDebate The puBookmarkDdPDDebate object to add.
     */
    protected function doAddPuBookmarkDdPDDebate($puBookmarkDdPDDebate)
    {
        $this->collPuBookmarkDdPDDebates[]= $puBookmarkDdPDDebate;
        $puBookmarkDdPDDebate->setPuBookmarkDdPDDebate($this);
    }

    /**
     * @param	PuBookmarkDdPDDebate $puBookmarkDdPDDebate The puBookmarkDdPDDebate object to remove.
     * @return PDDebate The current object (for fluent API support)
     */
    public function removePuBookmarkDdPDDebate($puBookmarkDdPDDebate)
    {
        if ($this->getPuBookmarkDdPDDebates()->contains($puBookmarkDdPDDebate)) {
            $this->collPuBookmarkDdPDDebates->remove($this->collPuBookmarkDdPDDebates->search($puBookmarkDdPDDebate));
            if (null === $this->puBookmarkDdPDDebatesScheduledForDeletion) {
                $this->puBookmarkDdPDDebatesScheduledForDeletion = clone $this->collPuBookmarkDdPDDebates;
                $this->puBookmarkDdPDDebatesScheduledForDeletion->clear();
            }
            $this->puBookmarkDdPDDebatesScheduledForDeletion[]= clone $puBookmarkDdPDDebate;
            $puBookmarkDdPDDebate->setPuBookmarkDdPDDebate(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this PDDebate is new, it will return
     * an empty collection; or if this PDDebate has previously
     * been saved, it will retrieve related PuBookmarkDdPDDebates from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in PDDebate.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|PUBookmarkDD[] List of PUBookmarkDD objects
     */
    public function getPuBookmarkDdPDDebatesJoinPuBookmarkDdPUser($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PUBookmarkDDQuery::create(null, $criteria);
        $query->joinWith('PuBookmarkDdPUser', $join_behavior);

        return $this->getPuBookmarkDdPDDebates($query, $con);
    }

    /**
     * Clears out the collPuTrackDdPDDebates collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return PDDebate The current object (for fluent API support)
     * @see        addPuTrackDdPDDebates()
     */
    public function clearPuTrackDdPDDebates()
    {
        $this->collPuTrackDdPDDebates = null; // important to set this to null since that means it is uninitialized
        $this->collPuTrackDdPDDebatesPartial = null;

        return $this;
    }

    /**
     * reset is the collPuTrackDdPDDebates collection loaded partially
     *
     * @return void
     */
    public function resetPartialPuTrackDdPDDebates($v = true)
    {
        $this->collPuTrackDdPDDebatesPartial = $v;
    }

    /**
     * Initializes the collPuTrackDdPDDebates collection.
     *
     * By default this just sets the collPuTrackDdPDDebates collection to an empty array (like clearcollPuTrackDdPDDebates());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPuTrackDdPDDebates($overrideExisting = true)
    {
        if (null !== $this->collPuTrackDdPDDebates && !$overrideExisting) {
            return;
        }
        $this->collPuTrackDdPDDebates = new PropelObjectCollection();
        $this->collPuTrackDdPDDebates->setModel('PUTrackDD');
    }

    /**
     * Gets an array of PUTrackDD objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this PDDebate is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|PUTrackDD[] List of PUTrackDD objects
     * @throws PropelException
     */
    public function getPuTrackDdPDDebates($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collPuTrackDdPDDebatesPartial && !$this->isNew();
        if (null === $this->collPuTrackDdPDDebates || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPuTrackDdPDDebates) {
                // return empty collection
                $this->initPuTrackDdPDDebates();
            } else {
                $collPuTrackDdPDDebates = PUTrackDDQuery::create(null, $criteria)
                    ->filterByPuTrackDdPDDebate($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collPuTrackDdPDDebatesPartial && count($collPuTrackDdPDDebates)) {
                      $this->initPuTrackDdPDDebates(false);

                      foreach ($collPuTrackDdPDDebates as $obj) {
                        if (false == $this->collPuTrackDdPDDebates->contains($obj)) {
                          $this->collPuTrackDdPDDebates->append($obj);
                        }
                      }

                      $this->collPuTrackDdPDDebatesPartial = true;
                    }

                    $collPuTrackDdPDDebates->getInternalIterator()->rewind();

                    return $collPuTrackDdPDDebates;
                }

                if ($partial && $this->collPuTrackDdPDDebates) {
                    foreach ($this->collPuTrackDdPDDebates as $obj) {
                        if ($obj->isNew()) {
                            $collPuTrackDdPDDebates[] = $obj;
                        }
                    }
                }

                $this->collPuTrackDdPDDebates = $collPuTrackDdPDDebates;
                $this->collPuTrackDdPDDebatesPartial = false;
            }
        }

        return $this->collPuTrackDdPDDebates;
    }

    /**
     * Sets a collection of PuTrackDdPDDebate objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $puTrackDdPDDebates A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return PDDebate The current object (for fluent API support)
     */
    public function setPuTrackDdPDDebates(PropelCollection $puTrackDdPDDebates, PropelPDO $con = null)
    {
        $puTrackDdPDDebatesToDelete = $this->getPuTrackDdPDDebates(new Criteria(), $con)->diff($puTrackDdPDDebates);


        $this->puTrackDdPDDebatesScheduledForDeletion = $puTrackDdPDDebatesToDelete;

        foreach ($puTrackDdPDDebatesToDelete as $puTrackDdPDDebateRemoved) {
            $puTrackDdPDDebateRemoved->setPuTrackDdPDDebate(null);
        }

        $this->collPuTrackDdPDDebates = null;
        foreach ($puTrackDdPDDebates as $puTrackDdPDDebate) {
            $this->addPuTrackDdPDDebate($puTrackDdPDDebate);
        }

        $this->collPuTrackDdPDDebates = $puTrackDdPDDebates;
        $this->collPuTrackDdPDDebatesPartial = false;

        return $this;
    }

    /**
     * Returns the number of related PUTrackDD objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related PUTrackDD objects.
     * @throws PropelException
     */
    public function countPuTrackDdPDDebates(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collPuTrackDdPDDebatesPartial && !$this->isNew();
        if (null === $this->collPuTrackDdPDDebates || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPuTrackDdPDDebates) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPuTrackDdPDDebates());
            }
            $query = PUTrackDDQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPuTrackDdPDDebate($this)
                ->count($con);
        }

        return count($this->collPuTrackDdPDDebates);
    }

    /**
     * Method called to associate a PUTrackDD object to this object
     * through the PUTrackDD foreign key attribute.
     *
     * @param    PUTrackDD $l PUTrackDD
     * @return PDDebate The current object (for fluent API support)
     */
    public function addPuTrackDdPDDebate(PUTrackDD $l)
    {
        if ($this->collPuTrackDdPDDebates === null) {
            $this->initPuTrackDdPDDebates();
            $this->collPuTrackDdPDDebatesPartial = true;
        }

        if (!in_array($l, $this->collPuTrackDdPDDebates->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddPuTrackDdPDDebate($l);

            if ($this->puTrackDdPDDebatesScheduledForDeletion and $this->puTrackDdPDDebatesScheduledForDeletion->contains($l)) {
                $this->puTrackDdPDDebatesScheduledForDeletion->remove($this->puTrackDdPDDebatesScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	PuTrackDdPDDebate $puTrackDdPDDebate The puTrackDdPDDebate object to add.
     */
    protected function doAddPuTrackDdPDDebate($puTrackDdPDDebate)
    {
        $this->collPuTrackDdPDDebates[]= $puTrackDdPDDebate;
        $puTrackDdPDDebate->setPuTrackDdPDDebate($this);
    }

    /**
     * @param	PuTrackDdPDDebate $puTrackDdPDDebate The puTrackDdPDDebate object to remove.
     * @return PDDebate The current object (for fluent API support)
     */
    public function removePuTrackDdPDDebate($puTrackDdPDDebate)
    {
        if ($this->getPuTrackDdPDDebates()->contains($puTrackDdPDDebate)) {
            $this->collPuTrackDdPDDebates->remove($this->collPuTrackDdPDDebates->search($puTrackDdPDDebate));
            if (null === $this->puTrackDdPDDebatesScheduledForDeletion) {
                $this->puTrackDdPDDebatesScheduledForDeletion = clone $this->collPuTrackDdPDDebates;
                $this->puTrackDdPDDebatesScheduledForDeletion->clear();
            }
            $this->puTrackDdPDDebatesScheduledForDeletion[]= clone $puTrackDdPDDebate;
            $puTrackDdPDDebate->setPuTrackDdPDDebate(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this PDDebate is new, it will return
     * an empty collection; or if this PDDebate has previously
     * been saved, it will retrieve related PuTrackDdPDDebates from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in PDDebate.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|PUTrackDD[] List of PUTrackDD objects
     */
    public function getPuTrackDdPDDebatesJoinPuTrackDdPUser($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PUTrackDDQuery::create(null, $criteria);
        $query->joinWith('PuTrackDdPUser', $join_behavior);

        return $this->getPuTrackDdPDDebates($query, $con);
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
    public function getPDReactionsJoinPUser($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PDReactionQuery::create(null, $criteria);
        $query->joinWith('PUser', $join_behavior);

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
    public function getPDReactionsJoinPLCity($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PDReactionQuery::create(null, $criteria);
        $query->joinWith('PLCity', $join_behavior);

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
    public function getPDReactionsJoinPLDepartment($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PDReactionQuery::create(null, $criteria);
        $query->joinWith('PLDepartment', $join_behavior);

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
    public function getPDReactionsJoinPLRegion($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PDReactionQuery::create(null, $criteria);
        $query->joinWith('PLRegion', $join_behavior);

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
    public function getPDReactionsJoinPLCountry($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PDReactionQuery::create(null, $criteria);
        $query->joinWith('PLCountry', $join_behavior);

        return $this->getPDReactions($query, $con);
    }

    /**
     * Clears out the collPDDComments collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return PDDebate The current object (for fluent API support)
     * @see        addPDDComments()
     */
    public function clearPDDComments()
    {
        $this->collPDDComments = null; // important to set this to null since that means it is uninitialized
        $this->collPDDCommentsPartial = null;

        return $this;
    }

    /**
     * reset is the collPDDComments collection loaded partially
     *
     * @return void
     */
    public function resetPartialPDDComments($v = true)
    {
        $this->collPDDCommentsPartial = $v;
    }

    /**
     * Initializes the collPDDComments collection.
     *
     * By default this just sets the collPDDComments collection to an empty array (like clearcollPDDComments());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPDDComments($overrideExisting = true)
    {
        if (null !== $this->collPDDComments && !$overrideExisting) {
            return;
        }
        $this->collPDDComments = new PropelObjectCollection();
        $this->collPDDComments->setModel('PDDComment');
    }

    /**
     * Gets an array of PDDComment objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this PDDebate is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|PDDComment[] List of PDDComment objects
     * @throws PropelException
     */
    public function getPDDComments($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collPDDCommentsPartial && !$this->isNew();
        if (null === $this->collPDDComments || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPDDComments) {
                // return empty collection
                $this->initPDDComments();
            } else {
                $collPDDComments = PDDCommentQuery::create(null, $criteria)
                    ->filterByPDDebate($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collPDDCommentsPartial && count($collPDDComments)) {
                      $this->initPDDComments(false);

                      foreach ($collPDDComments as $obj) {
                        if (false == $this->collPDDComments->contains($obj)) {
                          $this->collPDDComments->append($obj);
                        }
                      }

                      $this->collPDDCommentsPartial = true;
                    }

                    $collPDDComments->getInternalIterator()->rewind();

                    return $collPDDComments;
                }

                if ($partial && $this->collPDDComments) {
                    foreach ($this->collPDDComments as $obj) {
                        if ($obj->isNew()) {
                            $collPDDComments[] = $obj;
                        }
                    }
                }

                $this->collPDDComments = $collPDDComments;
                $this->collPDDCommentsPartial = false;
            }
        }

        return $this->collPDDComments;
    }

    /**
     * Sets a collection of PDDComment objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $pDDComments A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return PDDebate The current object (for fluent API support)
     */
    public function setPDDComments(PropelCollection $pDDComments, PropelPDO $con = null)
    {
        $pDDCommentsToDelete = $this->getPDDComments(new Criteria(), $con)->diff($pDDComments);


        $this->pDDCommentsScheduledForDeletion = $pDDCommentsToDelete;

        foreach ($pDDCommentsToDelete as $pDDCommentRemoved) {
            $pDDCommentRemoved->setPDDebate(null);
        }

        $this->collPDDComments = null;
        foreach ($pDDComments as $pDDComment) {
            $this->addPDDComment($pDDComment);
        }

        $this->collPDDComments = $pDDComments;
        $this->collPDDCommentsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related PDDComment objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related PDDComment objects.
     * @throws PropelException
     */
    public function countPDDComments(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collPDDCommentsPartial && !$this->isNew();
        if (null === $this->collPDDComments || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPDDComments) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPDDComments());
            }
            $query = PDDCommentQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPDDebate($this)
                ->count($con);
        }

        return count($this->collPDDComments);
    }

    /**
     * Method called to associate a PDDComment object to this object
     * through the PDDComment foreign key attribute.
     *
     * @param    PDDComment $l PDDComment
     * @return PDDebate The current object (for fluent API support)
     */
    public function addPDDComment(PDDComment $l)
    {
        if ($this->collPDDComments === null) {
            $this->initPDDComments();
            $this->collPDDCommentsPartial = true;
        }

        if (!in_array($l, $this->collPDDComments->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddPDDComment($l);

            if ($this->pDDCommentsScheduledForDeletion and $this->pDDCommentsScheduledForDeletion->contains($l)) {
                $this->pDDCommentsScheduledForDeletion->remove($this->pDDCommentsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	PDDComment $pDDComment The pDDComment object to add.
     */
    protected function doAddPDDComment($pDDComment)
    {
        $this->collPDDComments[]= $pDDComment;
        $pDDComment->setPDDebate($this);
    }

    /**
     * @param	PDDComment $pDDComment The pDDComment object to remove.
     * @return PDDebate The current object (for fluent API support)
     */
    public function removePDDComment($pDDComment)
    {
        if ($this->getPDDComments()->contains($pDDComment)) {
            $this->collPDDComments->remove($this->collPDDComments->search($pDDComment));
            if (null === $this->pDDCommentsScheduledForDeletion) {
                $this->pDDCommentsScheduledForDeletion = clone $this->collPDDComments;
                $this->pDDCommentsScheduledForDeletion->clear();
            }
            $this->pDDCommentsScheduledForDeletion[]= clone $pDDComment;
            $pDDComment->setPDDebate(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this PDDebate is new, it will return
     * an empty collection; or if this PDDebate has previously
     * been saved, it will retrieve related PDDComments from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in PDDebate.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|PDDComment[] List of PDDComment objects
     */
    public function getPDDCommentsJoinPUser($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PDDCommentQuery::create(null, $criteria);
        $query->joinWith('PUser', $join_behavior);

        return $this->getPDDComments($query, $con);
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
     * Clears out the collPMDebateHistorics collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return PDDebate The current object (for fluent API support)
     * @see        addPMDebateHistorics()
     */
    public function clearPMDebateHistorics()
    {
        $this->collPMDebateHistorics = null; // important to set this to null since that means it is uninitialized
        $this->collPMDebateHistoricsPartial = null;

        return $this;
    }

    /**
     * reset is the collPMDebateHistorics collection loaded partially
     *
     * @return void
     */
    public function resetPartialPMDebateHistorics($v = true)
    {
        $this->collPMDebateHistoricsPartial = $v;
    }

    /**
     * Initializes the collPMDebateHistorics collection.
     *
     * By default this just sets the collPMDebateHistorics collection to an empty array (like clearcollPMDebateHistorics());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPMDebateHistorics($overrideExisting = true)
    {
        if (null !== $this->collPMDebateHistorics && !$overrideExisting) {
            return;
        }
        $this->collPMDebateHistorics = new PropelObjectCollection();
        $this->collPMDebateHistorics->setModel('PMDebateHistoric');
    }

    /**
     * Gets an array of PMDebateHistoric objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this PDDebate is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|PMDebateHistoric[] List of PMDebateHistoric objects
     * @throws PropelException
     */
    public function getPMDebateHistorics($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collPMDebateHistoricsPartial && !$this->isNew();
        if (null === $this->collPMDebateHistorics || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPMDebateHistorics) {
                // return empty collection
                $this->initPMDebateHistorics();
            } else {
                $collPMDebateHistorics = PMDebateHistoricQuery::create(null, $criteria)
                    ->filterByPDDebate($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collPMDebateHistoricsPartial && count($collPMDebateHistorics)) {
                      $this->initPMDebateHistorics(false);

                      foreach ($collPMDebateHistorics as $obj) {
                        if (false == $this->collPMDebateHistorics->contains($obj)) {
                          $this->collPMDebateHistorics->append($obj);
                        }
                      }

                      $this->collPMDebateHistoricsPartial = true;
                    }

                    $collPMDebateHistorics->getInternalIterator()->rewind();

                    return $collPMDebateHistorics;
                }

                if ($partial && $this->collPMDebateHistorics) {
                    foreach ($this->collPMDebateHistorics as $obj) {
                        if ($obj->isNew()) {
                            $collPMDebateHistorics[] = $obj;
                        }
                    }
                }

                $this->collPMDebateHistorics = $collPMDebateHistorics;
                $this->collPMDebateHistoricsPartial = false;
            }
        }

        return $this->collPMDebateHistorics;
    }

    /**
     * Sets a collection of PMDebateHistoric objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $pMDebateHistorics A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return PDDebate The current object (for fluent API support)
     */
    public function setPMDebateHistorics(PropelCollection $pMDebateHistorics, PropelPDO $con = null)
    {
        $pMDebateHistoricsToDelete = $this->getPMDebateHistorics(new Criteria(), $con)->diff($pMDebateHistorics);


        $this->pMDebateHistoricsScheduledForDeletion = $pMDebateHistoricsToDelete;

        foreach ($pMDebateHistoricsToDelete as $pMDebateHistoricRemoved) {
            $pMDebateHistoricRemoved->setPDDebate(null);
        }

        $this->collPMDebateHistorics = null;
        foreach ($pMDebateHistorics as $pMDebateHistoric) {
            $this->addPMDebateHistoric($pMDebateHistoric);
        }

        $this->collPMDebateHistorics = $pMDebateHistorics;
        $this->collPMDebateHistoricsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related PMDebateHistoric objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related PMDebateHistoric objects.
     * @throws PropelException
     */
    public function countPMDebateHistorics(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collPMDebateHistoricsPartial && !$this->isNew();
        if (null === $this->collPMDebateHistorics || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPMDebateHistorics) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPMDebateHistorics());
            }
            $query = PMDebateHistoricQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPDDebate($this)
                ->count($con);
        }

        return count($this->collPMDebateHistorics);
    }

    /**
     * Method called to associate a PMDebateHistoric object to this object
     * through the PMDebateHistoric foreign key attribute.
     *
     * @param    PMDebateHistoric $l PMDebateHistoric
     * @return PDDebate The current object (for fluent API support)
     */
    public function addPMDebateHistoric(PMDebateHistoric $l)
    {
        if ($this->collPMDebateHistorics === null) {
            $this->initPMDebateHistorics();
            $this->collPMDebateHistoricsPartial = true;
        }

        if (!in_array($l, $this->collPMDebateHistorics->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddPMDebateHistoric($l);

            if ($this->pMDebateHistoricsScheduledForDeletion and $this->pMDebateHistoricsScheduledForDeletion->contains($l)) {
                $this->pMDebateHistoricsScheduledForDeletion->remove($this->pMDebateHistoricsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	PMDebateHistoric $pMDebateHistoric The pMDebateHistoric object to add.
     */
    protected function doAddPMDebateHistoric($pMDebateHistoric)
    {
        $this->collPMDebateHistorics[]= $pMDebateHistoric;
        $pMDebateHistoric->setPDDebate($this);
    }

    /**
     * @param	PMDebateHistoric $pMDebateHistoric The pMDebateHistoric object to remove.
     * @return PDDebate The current object (for fluent API support)
     */
    public function removePMDebateHistoric($pMDebateHistoric)
    {
        if ($this->getPMDebateHistorics()->contains($pMDebateHistoric)) {
            $this->collPMDebateHistorics->remove($this->collPMDebateHistorics->search($pMDebateHistoric));
            if (null === $this->pMDebateHistoricsScheduledForDeletion) {
                $this->pMDebateHistoricsScheduledForDeletion = clone $this->collPMDebateHistorics;
                $this->pMDebateHistoricsScheduledForDeletion->clear();
            }
            $this->pMDebateHistoricsScheduledForDeletion[]= $pMDebateHistoric;
            $pMDebateHistoric->setPDDebate(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this PDDebate is new, it will return
     * an empty collection; or if this PDDebate has previously
     * been saved, it will retrieve related PMDebateHistorics from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in PDDebate.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|PMDebateHistoric[] List of PMDebateHistoric objects
     */
    public function getPMDebateHistoricsJoinPUser($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PMDebateHistoricQuery::create(null, $criteria);
        $query->joinWith('PUser', $join_behavior);

        return $this->getPMDebateHistorics($query, $con);
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
     * Clears out the collPuBookmarkDdPUsers collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return PDDebate The current object (for fluent API support)
     * @see        addPuBookmarkDdPUsers()
     */
    public function clearPuBookmarkDdPUsers()
    {
        $this->collPuBookmarkDdPUsers = null; // important to set this to null since that means it is uninitialized
        $this->collPuBookmarkDdPUsersPartial = null;

        return $this;
    }

    /**
     * Initializes the collPuBookmarkDdPUsers collection.
     *
     * By default this just sets the collPuBookmarkDdPUsers collection to an empty collection (like clearPuBookmarkDdPUsers());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @return void
     */
    public function initPuBookmarkDdPUsers()
    {
        $this->collPuBookmarkDdPUsers = new PropelObjectCollection();
        $this->collPuBookmarkDdPUsers->setModel('PUser');
    }

    /**
     * Gets a collection of PUser objects related by a many-to-many relationship
     * to the current object by way of the p_u_bookmark_d_d cross-reference table.
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
    public function getPuBookmarkDdPUsers($criteria = null, PropelPDO $con = null)
    {
        if (null === $this->collPuBookmarkDdPUsers || null !== $criteria) {
            if ($this->isNew() && null === $this->collPuBookmarkDdPUsers) {
                // return empty collection
                $this->initPuBookmarkDdPUsers();
            } else {
                $collPuBookmarkDdPUsers = PUserQuery::create(null, $criteria)
                    ->filterByPuBookmarkDdPDDebate($this)
                    ->find($con);
                if (null !== $criteria) {
                    return $collPuBookmarkDdPUsers;
                }
                $this->collPuBookmarkDdPUsers = $collPuBookmarkDdPUsers;
            }
        }

        return $this->collPuBookmarkDdPUsers;
    }

    /**
     * Sets a collection of PUser objects related by a many-to-many relationship
     * to the current object by way of the p_u_bookmark_d_d cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $puBookmarkDdPUsers A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return PDDebate The current object (for fluent API support)
     */
    public function setPuBookmarkDdPUsers(PropelCollection $puBookmarkDdPUsers, PropelPDO $con = null)
    {
        $this->clearPuBookmarkDdPUsers();
        $currentPuBookmarkDdPUsers = $this->getPuBookmarkDdPUsers(null, $con);

        $this->puBookmarkDdPUsersScheduledForDeletion = $currentPuBookmarkDdPUsers->diff($puBookmarkDdPUsers);

        foreach ($puBookmarkDdPUsers as $puBookmarkDdPUser) {
            if (!$currentPuBookmarkDdPUsers->contains($puBookmarkDdPUser)) {
                $this->doAddPuBookmarkDdPUser($puBookmarkDdPUser);
            }
        }

        $this->collPuBookmarkDdPUsers = $puBookmarkDdPUsers;

        return $this;
    }

    /**
     * Gets the number of PUser objects related by a many-to-many relationship
     * to the current object by way of the p_u_bookmark_d_d cross-reference table.
     *
     * @param Criteria $criteria Optional query object to filter the query
     * @param boolean $distinct Set to true to force count distinct
     * @param PropelPDO $con Optional connection object
     *
     * @return int the number of related PUser objects
     */
    public function countPuBookmarkDdPUsers($criteria = null, $distinct = false, PropelPDO $con = null)
    {
        if (null === $this->collPuBookmarkDdPUsers || null !== $criteria) {
            if ($this->isNew() && null === $this->collPuBookmarkDdPUsers) {
                return 0;
            } else {
                $query = PUserQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByPuBookmarkDdPDDebate($this)
                    ->count($con);
            }
        } else {
            return count($this->collPuBookmarkDdPUsers);
        }
    }

    /**
     * Associate a PUser object to this object
     * through the p_u_bookmark_d_d cross reference table.
     *
     * @param  PUser $pUser The PUBookmarkDD object to relate
     * @return PDDebate The current object (for fluent API support)
     */
    public function addPuBookmarkDdPUser(PUser $pUser)
    {
        if ($this->collPuBookmarkDdPUsers === null) {
            $this->initPuBookmarkDdPUsers();
        }

        if (!$this->collPuBookmarkDdPUsers->contains($pUser)) { // only add it if the **same** object is not already associated
            $this->doAddPuBookmarkDdPUser($pUser);
            $this->collPuBookmarkDdPUsers[] = $pUser;

            if ($this->puBookmarkDdPUsersScheduledForDeletion and $this->puBookmarkDdPUsersScheduledForDeletion->contains($pUser)) {
                $this->puBookmarkDdPUsersScheduledForDeletion->remove($this->puBookmarkDdPUsersScheduledForDeletion->search($pUser));
            }
        }

        return $this;
    }

    /**
     * @param	PuBookmarkDdPUser $puBookmarkDdPUser The puBookmarkDdPUser object to add.
     */
    protected function doAddPuBookmarkDdPUser(PUser $puBookmarkDdPUser)
    {
        // set the back reference to this object directly as using provided method either results
        // in endless loop or in multiple relations
        if (!$puBookmarkDdPUser->getPuBookmarkDdPDDebates()->contains($this)) { $pUBookmarkDD = new PUBookmarkDD();
            $pUBookmarkDD->setPuBookmarkDdPUser($puBookmarkDdPUser);
            $this->addPuBookmarkDdPDDebate($pUBookmarkDD);

            $foreignCollection = $puBookmarkDdPUser->getPuBookmarkDdPDDebates();
            $foreignCollection[] = $this;
        }
    }

    /**
     * Remove a PUser object to this object
     * through the p_u_bookmark_d_d cross reference table.
     *
     * @param PUser $pUser The PUBookmarkDD object to relate
     * @return PDDebate The current object (for fluent API support)
     */
    public function removePuBookmarkDdPUser(PUser $pUser)
    {
        if ($this->getPuBookmarkDdPUsers()->contains($pUser)) {
            $this->collPuBookmarkDdPUsers->remove($this->collPuBookmarkDdPUsers->search($pUser));
            if (null === $this->puBookmarkDdPUsersScheduledForDeletion) {
                $this->puBookmarkDdPUsersScheduledForDeletion = clone $this->collPuBookmarkDdPUsers;
                $this->puBookmarkDdPUsersScheduledForDeletion->clear();
            }
            $this->puBookmarkDdPUsersScheduledForDeletion[]= $pUser;
        }

        return $this;
    }

    /**
     * Clears out the collPuTrackDdPUsers collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return PDDebate The current object (for fluent API support)
     * @see        addPuTrackDdPUsers()
     */
    public function clearPuTrackDdPUsers()
    {
        $this->collPuTrackDdPUsers = null; // important to set this to null since that means it is uninitialized
        $this->collPuTrackDdPUsersPartial = null;

        return $this;
    }

    /**
     * Initializes the collPuTrackDdPUsers collection.
     *
     * By default this just sets the collPuTrackDdPUsers collection to an empty collection (like clearPuTrackDdPUsers());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @return void
     */
    public function initPuTrackDdPUsers()
    {
        $this->collPuTrackDdPUsers = new PropelObjectCollection();
        $this->collPuTrackDdPUsers->setModel('PUser');
    }

    /**
     * Gets a collection of PUser objects related by a many-to-many relationship
     * to the current object by way of the p_u_track_d_d cross-reference table.
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
    public function getPuTrackDdPUsers($criteria = null, PropelPDO $con = null)
    {
        if (null === $this->collPuTrackDdPUsers || null !== $criteria) {
            if ($this->isNew() && null === $this->collPuTrackDdPUsers) {
                // return empty collection
                $this->initPuTrackDdPUsers();
            } else {
                $collPuTrackDdPUsers = PUserQuery::create(null, $criteria)
                    ->filterByPuTrackDdPDDebate($this)
                    ->find($con);
                if (null !== $criteria) {
                    return $collPuTrackDdPUsers;
                }
                $this->collPuTrackDdPUsers = $collPuTrackDdPUsers;
            }
        }

        return $this->collPuTrackDdPUsers;
    }

    /**
     * Sets a collection of PUser objects related by a many-to-many relationship
     * to the current object by way of the p_u_track_d_d cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $puTrackDdPUsers A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return PDDebate The current object (for fluent API support)
     */
    public function setPuTrackDdPUsers(PropelCollection $puTrackDdPUsers, PropelPDO $con = null)
    {
        $this->clearPuTrackDdPUsers();
        $currentPuTrackDdPUsers = $this->getPuTrackDdPUsers(null, $con);

        $this->puTrackDdPUsersScheduledForDeletion = $currentPuTrackDdPUsers->diff($puTrackDdPUsers);

        foreach ($puTrackDdPUsers as $puTrackDdPUser) {
            if (!$currentPuTrackDdPUsers->contains($puTrackDdPUser)) {
                $this->doAddPuTrackDdPUser($puTrackDdPUser);
            }
        }

        $this->collPuTrackDdPUsers = $puTrackDdPUsers;

        return $this;
    }

    /**
     * Gets the number of PUser objects related by a many-to-many relationship
     * to the current object by way of the p_u_track_d_d cross-reference table.
     *
     * @param Criteria $criteria Optional query object to filter the query
     * @param boolean $distinct Set to true to force count distinct
     * @param PropelPDO $con Optional connection object
     *
     * @return int the number of related PUser objects
     */
    public function countPuTrackDdPUsers($criteria = null, $distinct = false, PropelPDO $con = null)
    {
        if (null === $this->collPuTrackDdPUsers || null !== $criteria) {
            if ($this->isNew() && null === $this->collPuTrackDdPUsers) {
                return 0;
            } else {
                $query = PUserQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByPuTrackDdPDDebate($this)
                    ->count($con);
            }
        } else {
            return count($this->collPuTrackDdPUsers);
        }
    }

    /**
     * Associate a PUser object to this object
     * through the p_u_track_d_d cross reference table.
     *
     * @param  PUser $pUser The PUTrackDD object to relate
     * @return PDDebate The current object (for fluent API support)
     */
    public function addPuTrackDdPUser(PUser $pUser)
    {
        if ($this->collPuTrackDdPUsers === null) {
            $this->initPuTrackDdPUsers();
        }

        if (!$this->collPuTrackDdPUsers->contains($pUser)) { // only add it if the **same** object is not already associated
            $this->doAddPuTrackDdPUser($pUser);
            $this->collPuTrackDdPUsers[] = $pUser;

            if ($this->puTrackDdPUsersScheduledForDeletion and $this->puTrackDdPUsersScheduledForDeletion->contains($pUser)) {
                $this->puTrackDdPUsersScheduledForDeletion->remove($this->puTrackDdPUsersScheduledForDeletion->search($pUser));
            }
        }

        return $this;
    }

    /**
     * @param	PuTrackDdPUser $puTrackDdPUser The puTrackDdPUser object to add.
     */
    protected function doAddPuTrackDdPUser(PUser $puTrackDdPUser)
    {
        // set the back reference to this object directly as using provided method either results
        // in endless loop or in multiple relations
        if (!$puTrackDdPUser->getPuTrackDdPDDebates()->contains($this)) { $pUTrackDD = new PUTrackDD();
            $pUTrackDD->setPuTrackDdPUser($puTrackDdPUser);
            $this->addPuTrackDdPDDebate($pUTrackDD);

            $foreignCollection = $puTrackDdPUser->getPuTrackDdPDDebates();
            $foreignCollection[] = $this;
        }
    }

    /**
     * Remove a PUser object to this object
     * through the p_u_track_d_d cross reference table.
     *
     * @param PUser $pUser The PUTrackDD object to relate
     * @return PDDebate The current object (for fluent API support)
     */
    public function removePuTrackDdPUser(PUser $pUser)
    {
        if ($this->getPuTrackDdPUsers()->contains($pUser)) {
            $this->collPuTrackDdPUsers->remove($this->collPuTrackDdPUsers->search($pUser));
            if (null === $this->puTrackDdPUsersScheduledForDeletion) {
                $this->puTrackDdPUsersScheduledForDeletion = clone $this->collPuTrackDdPUsers;
                $this->puTrackDdPUsersScheduledForDeletion->clear();
            }
            $this->puTrackDdPUsersScheduledForDeletion[]= $pUser;
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
        $this->id = null;
        $this->uuid = null;
        $this->p_user_id = null;
        $this->p_e_operation_id = null;
        $this->p_l_city_id = null;
        $this->p_l_department_id = null;
        $this->p_l_region_id = null;
        $this->p_l_country_id = null;
        $this->fb_ad_id = null;
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
        $this->homepage = null;
        $this->moderated = null;
        $this->moderated_partial = null;
        $this->moderated_at = null;
        $this->indexed_at = null;
        $this->created_at = null;
        $this->updated_at = null;
        $this->slug = null;
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
            if ($this->collPuBookmarkDdPDDebates) {
                foreach ($this->collPuBookmarkDdPDDebates as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPuTrackDdPDDebates) {
                foreach ($this->collPuTrackDdPDDebates as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPDReactions) {
                foreach ($this->collPDReactions as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPDDComments) {
                foreach ($this->collPDDComments as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPDDTaggedTs) {
                foreach ($this->collPDDTaggedTs as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPMDebateHistorics) {
                foreach ($this->collPMDebateHistorics as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPuFollowDdPUsers) {
                foreach ($this->collPuFollowDdPUsers as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPuBookmarkDdPUsers) {
                foreach ($this->collPuBookmarkDdPUsers as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPuTrackDdPUsers) {
                foreach ($this->collPuTrackDdPUsers as $o) {
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
            if ($this->aPEOperation instanceof Persistent) {
              $this->aPEOperation->clearAllReferences($deep);
            }

            $this->alreadyInClearAllReferencesDeep = false;
        } // if ($deep)

        if ($this->collPuFollowDdPDDebates instanceof PropelCollection) {
            $this->collPuFollowDdPDDebates->clearIterator();
        }
        $this->collPuFollowDdPDDebates = null;
        if ($this->collPuBookmarkDdPDDebates instanceof PropelCollection) {
            $this->collPuBookmarkDdPDDebates->clearIterator();
        }
        $this->collPuBookmarkDdPDDebates = null;
        if ($this->collPuTrackDdPDDebates instanceof PropelCollection) {
            $this->collPuTrackDdPDDebates->clearIterator();
        }
        $this->collPuTrackDdPDDebates = null;
        if ($this->collPDReactions instanceof PropelCollection) {
            $this->collPDReactions->clearIterator();
        }
        $this->collPDReactions = null;
        if ($this->collPDDComments instanceof PropelCollection) {
            $this->collPDDComments->clearIterator();
        }
        $this->collPDDComments = null;
        if ($this->collPDDTaggedTs instanceof PropelCollection) {
            $this->collPDDTaggedTs->clearIterator();
        }
        $this->collPDDTaggedTs = null;
        if ($this->collPMDebateHistorics instanceof PropelCollection) {
            $this->collPMDebateHistorics->clearIterator();
        }
        $this->collPMDebateHistorics = null;
        if ($this->collPuFollowDdPUsers instanceof PropelCollection) {
            $this->collPuFollowDdPUsers->clearIterator();
        }
        $this->collPuFollowDdPUsers = null;
        if ($this->collPuBookmarkDdPUsers instanceof PropelCollection) {
            $this->collPuBookmarkDdPUsers->clearIterator();
        }
        $this->collPuBookmarkDdPUsers = null;
        if ($this->collPuTrackDdPUsers instanceof PropelCollection) {
            $this->collPuTrackDdPUsers->clearIterator();
        }
        $this->collPuTrackDdPUsers = null;
        if ($this->collPTags instanceof PropelCollection) {
            $this->collPTags->clearIterator();
        }
        $this->collPTags = null;
        $this->aPUser = null;
        $this->aPLCity = null;
        $this->aPLDepartment = null;
        $this->aPLRegion = null;
        $this->aPLCountry = null;
        $this->aPEOperation = null;
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
    * If permanent UUID, throw exception p_d_debate.uuid*/
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
        $this->setUuid($archive->getUuid());
        $this->setPUserId($archive->getPUserId());
        $this->setPEOperationId($archive->getPEOperationId());
        $this->setPLCityId($archive->getPLCityId());
        $this->setPLDepartmentId($archive->getPLDepartmentId());
        $this->setPLRegionId($archive->getPLRegionId());
        $this->setPLCountryId($archive->getPLCountryId());
        $this->setFbAdId($archive->getFbAdId());
        $this->setTitle($archive->getTitle());
        $this->setFileName($archive->getFileName());
        $this->setCopyright($archive->getCopyright());
        $this->setDescription($archive->getDescription());
        $this->setNotePos($archive->getNotePos());
        $this->setNoteNeg($archive->getNoteNeg());
        $this->setNbViews($archive->getNbViews());
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
