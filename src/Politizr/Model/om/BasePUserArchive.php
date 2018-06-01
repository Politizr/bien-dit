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
use Politizr\Model\PUserArchive;
use Politizr\Model\PUserArchivePeer;
use Politizr\Model\PUserArchiveQuery;

abstract class BasePUserArchive extends BaseObject implements Persistent
{
    /**
     * Peer class name
     */
    const PEER = 'Politizr\\Model\\PUserArchivePeer';

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        PUserArchivePeer
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
     * The value for the p_u_status_id field.
     * @var        int
     */
    protected $p_u_status_id;

    /**
     * The value for the p_l_city_id field.
     * @var        int
     */
    protected $p_l_city_id;

    /**
     * The value for the provider field.
     * @var        string
     */
    protected $provider;

    /**
     * The value for the provider_id field.
     * @var        string
     */
    protected $provider_id;

    /**
     * The value for the nickname field.
     * @var        string
     */
    protected $nickname;

    /**
     * The value for the realname field.
     * @var        string
     */
    protected $realname;

    /**
     * The value for the username field.
     * @var        string
     */
    protected $username;

    /**
     * The value for the username_canonical field.
     * @var        string
     */
    protected $username_canonical;

    /**
     * The value for the email field.
     * @var        string
     */
    protected $email;

    /**
     * The value for the email_canonical field.
     * @var        string
     */
    protected $email_canonical;

    /**
     * The value for the enabled field.
     * Note: this column has a database default value of: false
     * @var        boolean
     */
    protected $enabled;

    /**
     * The value for the salt field.
     * @var        string
     */
    protected $salt;

    /**
     * The value for the password field.
     * @var        string
     */
    protected $password;

    /**
     * The value for the last_login field.
     * @var        string
     */
    protected $last_login;

    /**
     * The value for the locked field.
     * Note: this column has a database default value of: false
     * @var        boolean
     */
    protected $locked;

    /**
     * The value for the expired field.
     * Note: this column has a database default value of: false
     * @var        boolean
     */
    protected $expired;

    /**
     * The value for the expires_at field.
     * @var        string
     */
    protected $expires_at;

    /**
     * The value for the confirmation_token field.
     * @var        string
     */
    protected $confirmation_token;

    /**
     * The value for the password_requested_at field.
     * @var        string
     */
    protected $password_requested_at;

    /**
     * The value for the credentials_expired field.
     * Note: this column has a database default value of: false
     * @var        boolean
     */
    protected $credentials_expired;

    /**
     * The value for the credentials_expire_at field.
     * @var        string
     */
    protected $credentials_expire_at;

    /**
     * The value for the roles field.
     * @var        array
     */
    protected $roles;

    /**
     * The unserialized $roles value - i.e. the persisted object.
     * This is necessary to avoid repeated calls to unserialize() at runtime.
     * @var        object
     */
    protected $roles_unserialized;

    /**
     * The value for the last_activity field.
     * @var        string
     */
    protected $last_activity;

    /**
     * The value for the file_name field.
     * @var        string
     */
    protected $file_name;

    /**
     * The value for the back_file_name field.
     * @var        string
     */
    protected $back_file_name;

    /**
     * The value for the copyright field.
     * @var        string
     */
    protected $copyright;

    /**
     * The value for the gender field.
     * @var        int
     */
    protected $gender;

    /**
     * The value for the firstname field.
     * @var        string
     */
    protected $firstname;

    /**
     * The value for the name field.
     * @var        string
     */
    protected $name;

    /**
     * The value for the birthday field.
     * @var        string
     */
    protected $birthday;

    /**
     * The value for the subtitle field.
     * @var        string
     */
    protected $subtitle;

    /**
     * The value for the biography field.
     * @var        string
     */
    protected $biography;

    /**
     * The value for the website field.
     * @var        string
     */
    protected $website;

    /**
     * The value for the twitter field.
     * @var        string
     */
    protected $twitter;

    /**
     * The value for the facebook field.
     * @var        string
     */
    protected $facebook;

    /**
     * The value for the phone field.
     * @var        string
     */
    protected $phone;

    /**
     * The value for the newsletter field.
     * @var        boolean
     */
    protected $newsletter;

    /**
     * The value for the last_connect field.
     * @var        string
     */
    protected $last_connect;

    /**
     * The value for the nb_connected_days field.
     * Note: this column has a database default value of: 0
     * @var        int
     */
    protected $nb_connected_days;

    /**
     * The value for the indexed_at field.
     * @var        string
     */
    protected $indexed_at;

    /**
     * The value for the nb_views field.
     * @var        int
     */
    protected $nb_views;

    /**
     * The value for the qualified field.
     * @var        boolean
     */
    protected $qualified;

    /**
     * The value for the validated field.
     * Note: this column has a database default value of: false
     * @var        boolean
     */
    protected $validated;

    /**
     * The value for the nb_id_check field.
     * @var        int
     */
    protected $nb_id_check;

    /**
     * The value for the organization field.
     * Note: this column has a database default value of: false
     * @var        boolean
     */
    protected $organization;

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
     * The value for the support_group field.
     * @var        boolean
     */
    protected $support_group;

    /**
     * The value for the banned field.
     * @var        boolean
     */
    protected $banned;

    /**
     * The value for the banned_nb_days_left field.
     * @var        int
     */
    protected $banned_nb_days_left;

    /**
     * The value for the banned_nb_total field.
     * @var        int
     */
    protected $banned_nb_total;

    /**
     * The value for the abuse_level field.
     * @var        int
     */
    protected $abuse_level;

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
        $this->enabled = false;
        $this->locked = false;
        $this->expired = false;
        $this->credentials_expired = false;
        $this->nb_connected_days = 0;
        $this->validated = false;
        $this->organization = false;
    }

    /**
     * Initializes internal state of BasePUserArchive object.
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
     * Get the [p_u_status_id] column value.
     *
     * @return int
     */
    public function getPUStatusId()
    {

        return $this->p_u_status_id;
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
     * Get the [provider] column value.
     *
     * @return string
     */
    public function getProvider()
    {

        return $this->provider;
    }

    /**
     * Get the [provider_id] column value.
     *
     * @return string
     */
    public function getProviderId()
    {

        return $this->provider_id;
    }

    /**
     * Get the [nickname] column value.
     *
     * @return string
     */
    public function getNickname()
    {

        return $this->nickname;
    }

    /**
     * Get the [realname] column value.
     *
     * @return string
     */
    public function getRealname()
    {

        return $this->realname;
    }

    /**
     * Get the [username] column value.
     *
     * @return string
     */
    public function getUsername()
    {

        return $this->username;
    }

    /**
     * Get the [username_canonical] column value.
     *
     * @return string
     */
    public function getUsernameCanonical()
    {

        return $this->username_canonical;
    }

    /**
     * Get the [email] column value.
     *
     * @return string
     */
    public function getEmail()
    {

        return $this->email;
    }

    /**
     * Get the [email_canonical] column value.
     *
     * @return string
     */
    public function getEmailCanonical()
    {

        return $this->email_canonical;
    }

    /**
     * Get the [enabled] column value.
     *
     * @return boolean
     */
    public function getEnabled()
    {

        return $this->enabled;
    }

    /**
     * Get the [salt] column value.
     *
     * @return string
     */
    public function getSalt()
    {

        return $this->salt;
    }

    /**
     * Get the [password] column value.
     *
     * @return string
     */
    public function getPassword()
    {

        return $this->password;
    }

    /**
     * Get the [optionally formatted] temporal [last_login] column value.
     *
     *
     * @param string $format The date/time format string (either date()-style or strftime()-style).
     *				 If format is null, then the raw DateTime object will be returned.
     * @return mixed Formatted date/time value as string or DateTime object (if format is null), null if column is null, and 0 if column value is 0000-00-00 00:00:00
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getLastLogin($format = null)
    {
        if ($this->last_login === null) {
            return null;
        }

        if ($this->last_login === '0000-00-00 00:00:00') {
            // while technically this is not a default value of null,
            // this seems to be closest in meaning.
            return null;
        }

        try {
            $dt = new DateTime($this->last_login);
        } catch (Exception $x) {
            throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->last_login, true), $x);
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
     * Get the [locked] column value.
     *
     * @return boolean
     */
    public function getLocked()
    {

        return $this->locked;
    }

    /**
     * Get the [expired] column value.
     *
     * @return boolean
     */
    public function getExpired()
    {

        return $this->expired;
    }

    /**
     * Get the [optionally formatted] temporal [expires_at] column value.
     *
     *
     * @param string $format The date/time format string (either date()-style or strftime()-style).
     *				 If format is null, then the raw DateTime object will be returned.
     * @return mixed Formatted date/time value as string or DateTime object (if format is null), null if column is null, and 0 if column value is 0000-00-00 00:00:00
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getExpiresAt($format = null)
    {
        if ($this->expires_at === null) {
            return null;
        }

        if ($this->expires_at === '0000-00-00 00:00:00') {
            // while technically this is not a default value of null,
            // this seems to be closest in meaning.
            return null;
        }

        try {
            $dt = new DateTime($this->expires_at);
        } catch (Exception $x) {
            throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->expires_at, true), $x);
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
     * Get the [confirmation_token] column value.
     *
     * @return string
     */
    public function getConfirmationToken()
    {

        return $this->confirmation_token;
    }

    /**
     * Get the [optionally formatted] temporal [password_requested_at] column value.
     *
     *
     * @param string $format The date/time format string (either date()-style or strftime()-style).
     *				 If format is null, then the raw DateTime object will be returned.
     * @return mixed Formatted date/time value as string or DateTime object (if format is null), null if column is null, and 0 if column value is 0000-00-00 00:00:00
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getPasswordRequestedAt($format = null)
    {
        if ($this->password_requested_at === null) {
            return null;
        }

        if ($this->password_requested_at === '0000-00-00 00:00:00') {
            // while technically this is not a default value of null,
            // this seems to be closest in meaning.
            return null;
        }

        try {
            $dt = new DateTime($this->password_requested_at);
        } catch (Exception $x) {
            throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->password_requested_at, true), $x);
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
     * Get the [credentials_expired] column value.
     *
     * @return boolean
     */
    public function getCredentialsExpired()
    {

        return $this->credentials_expired;
    }

    /**
     * Get the [optionally formatted] temporal [credentials_expire_at] column value.
     *
     *
     * @param string $format The date/time format string (either date()-style or strftime()-style).
     *				 If format is null, then the raw DateTime object will be returned.
     * @return mixed Formatted date/time value as string or DateTime object (if format is null), null if column is null, and 0 if column value is 0000-00-00 00:00:00
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getCredentialsExpireAt($format = null)
    {
        if ($this->credentials_expire_at === null) {
            return null;
        }

        if ($this->credentials_expire_at === '0000-00-00 00:00:00') {
            // while technically this is not a default value of null,
            // this seems to be closest in meaning.
            return null;
        }

        try {
            $dt = new DateTime($this->credentials_expire_at);
        } catch (Exception $x) {
            throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->credentials_expire_at, true), $x);
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
     * Get the [roles] column value.
     *
     * @return array
     */
    public function getRoles()
    {
        if (null === $this->roles_unserialized) {
            $this->roles_unserialized = array();
        }
        if (!$this->roles_unserialized && null !== $this->roles) {
            $roles_unserialized = substr($this->roles, 2, -2);
            $this->roles_unserialized = $roles_unserialized ? explode(' | ', $roles_unserialized) : array();
        }

        return $this->roles_unserialized;
    }

    /**
     * Test the presence of a value in the [roles] array column value.
     * @param mixed $value
     *
     * @return boolean
     */
    public function hasRole($value)
    {
        return in_array($value, $this->getRoles());
    } // hasRole()

    /**
     * Get the [optionally formatted] temporal [last_activity] column value.
     *
     *
     * @param string $format The date/time format string (either date()-style or strftime()-style).
     *				 If format is null, then the raw DateTime object will be returned.
     * @return mixed Formatted date/time value as string or DateTime object (if format is null), null if column is null, and 0 if column value is 0000-00-00 00:00:00
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getLastActivity($format = null)
    {
        if ($this->last_activity === null) {
            return null;
        }

        if ($this->last_activity === '0000-00-00 00:00:00') {
            // while technically this is not a default value of null,
            // this seems to be closest in meaning.
            return null;
        }

        try {
            $dt = new DateTime($this->last_activity);
        } catch (Exception $x) {
            throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->last_activity, true), $x);
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
     * Get the [file_name] column value.
     *
     * @return string
     */
    public function getFileName()
    {

        return $this->file_name;
    }

    /**
     * Get the [back_file_name] column value.
     *
     * @return string
     */
    public function getBackFileName()
    {

        return $this->back_file_name;
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
     * Get the [gender] column value.
     *
     * @return int
     * @throws PropelException - if the stored enum key is unknown.
     */
    public function getGender()
    {
        if (null === $this->gender) {
            return null;
        }
        $valueSet = PUserArchivePeer::getValueSet(PUserArchivePeer::GENDER);
        if (!isset($valueSet[$this->gender])) {
            throw new PropelException('Unknown stored enum key: ' . $this->gender);
        }

        return $valueSet[$this->gender];
    }

    /**
     * Get the [firstname] column value.
     *
     * @return string
     */
    public function getFirstname()
    {

        return $this->firstname;
    }

    /**
     * Get the [name] column value.
     *
     * @return string
     */
    public function getName()
    {

        return $this->name;
    }

    /**
     * Get the [optionally formatted] temporal [birthday] column value.
     *
     *
     * @param string $format The date/time format string (either date()-style or strftime()-style).
     *				 If format is null, then the raw DateTime object will be returned.
     * @return mixed Formatted date/time value as string or DateTime object (if format is null), null if column is null, and 0 if column value is 0000-00-00
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getBirthday($format = null)
    {
        if ($this->birthday === null) {
            return null;
        }

        if ($this->birthday === '0000-00-00') {
            // while technically this is not a default value of null,
            // this seems to be closest in meaning.
            return null;
        }

        try {
            $dt = new DateTime($this->birthday);
        } catch (Exception $x) {
            throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->birthday, true), $x);
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
     * Get the [subtitle] column value.
     *
     * @return string
     */
    public function getSubtitle()
    {

        return $this->subtitle;
    }

    /**
     * Get the [biography] column value.
     *
     * @return string
     */
    public function getBiography()
    {

        return $this->biography;
    }

    /**
     * Get the [website] column value.
     *
     * @return string
     */
    public function getWebsite()
    {

        return $this->website;
    }

    /**
     * Get the [twitter] column value.
     *
     * @return string
     */
    public function getTwitter()
    {

        return $this->twitter;
    }

    /**
     * Get the [facebook] column value.
     *
     * @return string
     */
    public function getFacebook()
    {

        return $this->facebook;
    }

    /**
     * Get the [phone] column value.
     *
     * @return string
     */
    public function getPhone()
    {

        return $this->phone;
    }

    /**
     * Get the [newsletter] column value.
     *
     * @return boolean
     */
    public function getNewsletter()
    {

        return $this->newsletter;
    }

    /**
     * Get the [optionally formatted] temporal [last_connect] column value.
     *
     *
     * @param string $format The date/time format string (either date()-style or strftime()-style).
     *				 If format is null, then the raw DateTime object will be returned.
     * @return mixed Formatted date/time value as string or DateTime object (if format is null), null if column is null, and 0 if column value is 0000-00-00 00:00:00
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getLastConnect($format = null)
    {
        if ($this->last_connect === null) {
            return null;
        }

        if ($this->last_connect === '0000-00-00 00:00:00') {
            // while technically this is not a default value of null,
            // this seems to be closest in meaning.
            return null;
        }

        try {
            $dt = new DateTime($this->last_connect);
        } catch (Exception $x) {
            throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->last_connect, true), $x);
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
     * Get the [nb_connected_days] column value.
     *
     * @return int
     */
    public function getNbConnectedDays()
    {

        return $this->nb_connected_days;
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
     * Get the [nb_views] column value.
     *
     * @return int
     */
    public function getNbViews()
    {

        return $this->nb_views;
    }

    /**
     * Get the [qualified] column value.
     *
     * @return boolean
     */
    public function getQualified()
    {

        return $this->qualified;
    }

    /**
     * Get the [validated] column value.
     *
     * @return boolean
     */
    public function getValidated()
    {

        return $this->validated;
    }

    /**
     * Get the [nb_id_check] column value.
     *
     * @return int
     */
    public function getNbIdCheck()
    {

        return $this->nb_id_check;
    }

    /**
     * Get the [organization] column value.
     *
     * @return boolean
     */
    public function getOrganization()
    {

        return $this->organization;
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
     * Get the [support_group] column value.
     *
     * @return boolean
     */
    public function getSupportGroup()
    {

        return $this->support_group;
    }

    /**
     * Get the [banned] column value.
     *
     * @return boolean
     */
    public function getBanned()
    {

        return $this->banned;
    }

    /**
     * Get the [banned_nb_days_left] column value.
     *
     * @return int
     */
    public function getBannedNbDaysLeft()
    {

        return $this->banned_nb_days_left;
    }

    /**
     * Get the [banned_nb_total] column value.
     *
     * @return int
     */
    public function getBannedNbTotal()
    {

        return $this->banned_nb_total;
    }

    /**
     * Get the [abuse_level] column value.
     *
     * @return int
     */
    public function getAbuseLevel()
    {

        return $this->abuse_level;
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
     * @return PUserArchive The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[] = PUserArchivePeer::ID;
        }


        return $this;
    } // setId()

    /**
     * Set the value of [uuid] column.
     *
     * @param  string $v new value
     * @return PUserArchive The current object (for fluent API support)
     */
    public function setUuid($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->uuid !== $v) {
            $this->uuid = $v;
            $this->modifiedColumns[] = PUserArchivePeer::UUID;
        }


        return $this;
    } // setUuid()

    /**
     * Set the value of [p_u_status_id] column.
     *
     * @param  int $v new value
     * @return PUserArchive The current object (for fluent API support)
     */
    public function setPUStatusId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->p_u_status_id !== $v) {
            $this->p_u_status_id = $v;
            $this->modifiedColumns[] = PUserArchivePeer::P_U_STATUS_ID;
        }


        return $this;
    } // setPUStatusId()

    /**
     * Set the value of [p_l_city_id] column.
     *
     * @param  int $v new value
     * @return PUserArchive The current object (for fluent API support)
     */
    public function setPLCityId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->p_l_city_id !== $v) {
            $this->p_l_city_id = $v;
            $this->modifiedColumns[] = PUserArchivePeer::P_L_CITY_ID;
        }


        return $this;
    } // setPLCityId()

    /**
     * Set the value of [provider] column.
     *
     * @param  string $v new value
     * @return PUserArchive The current object (for fluent API support)
     */
    public function setProvider($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->provider !== $v) {
            $this->provider = $v;
            $this->modifiedColumns[] = PUserArchivePeer::PROVIDER;
        }


        return $this;
    } // setProvider()

    /**
     * Set the value of [provider_id] column.
     *
     * @param  string $v new value
     * @return PUserArchive The current object (for fluent API support)
     */
    public function setProviderId($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->provider_id !== $v) {
            $this->provider_id = $v;
            $this->modifiedColumns[] = PUserArchivePeer::PROVIDER_ID;
        }


        return $this;
    } // setProviderId()

    /**
     * Set the value of [nickname] column.
     *
     * @param  string $v new value
     * @return PUserArchive The current object (for fluent API support)
     */
    public function setNickname($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->nickname !== $v) {
            $this->nickname = $v;
            $this->modifiedColumns[] = PUserArchivePeer::NICKNAME;
        }


        return $this;
    } // setNickname()

    /**
     * Set the value of [realname] column.
     *
     * @param  string $v new value
     * @return PUserArchive The current object (for fluent API support)
     */
    public function setRealname($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->realname !== $v) {
            $this->realname = $v;
            $this->modifiedColumns[] = PUserArchivePeer::REALNAME;
        }


        return $this;
    } // setRealname()

    /**
     * Set the value of [username] column.
     *
     * @param  string $v new value
     * @return PUserArchive The current object (for fluent API support)
     */
    public function setUsername($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->username !== $v) {
            $this->username = $v;
            $this->modifiedColumns[] = PUserArchivePeer::USERNAME;
        }


        return $this;
    } // setUsername()

    /**
     * Set the value of [username_canonical] column.
     *
     * @param  string $v new value
     * @return PUserArchive The current object (for fluent API support)
     */
    public function setUsernameCanonical($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->username_canonical !== $v) {
            $this->username_canonical = $v;
            $this->modifiedColumns[] = PUserArchivePeer::USERNAME_CANONICAL;
        }


        return $this;
    } // setUsernameCanonical()

    /**
     * Set the value of [email] column.
     *
     * @param  string $v new value
     * @return PUserArchive The current object (for fluent API support)
     */
    public function setEmail($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->email !== $v) {
            $this->email = $v;
            $this->modifiedColumns[] = PUserArchivePeer::EMAIL;
        }


        return $this;
    } // setEmail()

    /**
     * Set the value of [email_canonical] column.
     *
     * @param  string $v new value
     * @return PUserArchive The current object (for fluent API support)
     */
    public function setEmailCanonical($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->email_canonical !== $v) {
            $this->email_canonical = $v;
            $this->modifiedColumns[] = PUserArchivePeer::EMAIL_CANONICAL;
        }


        return $this;
    } // setEmailCanonical()

    /**
     * Sets the value of the [enabled] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param boolean|integer|string $v The new value
     * @return PUserArchive The current object (for fluent API support)
     */
    public function setEnabled($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->enabled !== $v) {
            $this->enabled = $v;
            $this->modifiedColumns[] = PUserArchivePeer::ENABLED;
        }


        return $this;
    } // setEnabled()

    /**
     * Set the value of [salt] column.
     *
     * @param  string $v new value
     * @return PUserArchive The current object (for fluent API support)
     */
    public function setSalt($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->salt !== $v) {
            $this->salt = $v;
            $this->modifiedColumns[] = PUserArchivePeer::SALT;
        }


        return $this;
    } // setSalt()

    /**
     * Set the value of [password] column.
     *
     * @param  string $v new value
     * @return PUserArchive The current object (for fluent API support)
     */
    public function setPassword($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->password !== $v) {
            $this->password = $v;
            $this->modifiedColumns[] = PUserArchivePeer::PASSWORD;
        }


        return $this;
    } // setPassword()

    /**
     * Sets the value of [last_login] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return PUserArchive The current object (for fluent API support)
     */
    public function setLastLogin($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->last_login !== null || $dt !== null) {
            $currentDateAsString = ($this->last_login !== null && $tmpDt = new DateTime($this->last_login)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->last_login = $newDateAsString;
                $this->modifiedColumns[] = PUserArchivePeer::LAST_LOGIN;
            }
        } // if either are not null


        return $this;
    } // setLastLogin()

    /**
     * Sets the value of the [locked] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param boolean|integer|string $v The new value
     * @return PUserArchive The current object (for fluent API support)
     */
    public function setLocked($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->locked !== $v) {
            $this->locked = $v;
            $this->modifiedColumns[] = PUserArchivePeer::LOCKED;
        }


        return $this;
    } // setLocked()

    /**
     * Sets the value of the [expired] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param boolean|integer|string $v The new value
     * @return PUserArchive The current object (for fluent API support)
     */
    public function setExpired($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->expired !== $v) {
            $this->expired = $v;
            $this->modifiedColumns[] = PUserArchivePeer::EXPIRED;
        }


        return $this;
    } // setExpired()

    /**
     * Sets the value of [expires_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return PUserArchive The current object (for fluent API support)
     */
    public function setExpiresAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->expires_at !== null || $dt !== null) {
            $currentDateAsString = ($this->expires_at !== null && $tmpDt = new DateTime($this->expires_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->expires_at = $newDateAsString;
                $this->modifiedColumns[] = PUserArchivePeer::EXPIRES_AT;
            }
        } // if either are not null


        return $this;
    } // setExpiresAt()

    /**
     * Set the value of [confirmation_token] column.
     *
     * @param  string $v new value
     * @return PUserArchive The current object (for fluent API support)
     */
    public function setConfirmationToken($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->confirmation_token !== $v) {
            $this->confirmation_token = $v;
            $this->modifiedColumns[] = PUserArchivePeer::CONFIRMATION_TOKEN;
        }


        return $this;
    } // setConfirmationToken()

    /**
     * Sets the value of [password_requested_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return PUserArchive The current object (for fluent API support)
     */
    public function setPasswordRequestedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->password_requested_at !== null || $dt !== null) {
            $currentDateAsString = ($this->password_requested_at !== null && $tmpDt = new DateTime($this->password_requested_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->password_requested_at = $newDateAsString;
                $this->modifiedColumns[] = PUserArchivePeer::PASSWORD_REQUESTED_AT;
            }
        } // if either are not null


        return $this;
    } // setPasswordRequestedAt()

    /**
     * Sets the value of the [credentials_expired] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param boolean|integer|string $v The new value
     * @return PUserArchive The current object (for fluent API support)
     */
    public function setCredentialsExpired($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->credentials_expired !== $v) {
            $this->credentials_expired = $v;
            $this->modifiedColumns[] = PUserArchivePeer::CREDENTIALS_EXPIRED;
        }


        return $this;
    } // setCredentialsExpired()

    /**
     * Sets the value of [credentials_expire_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return PUserArchive The current object (for fluent API support)
     */
    public function setCredentialsExpireAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->credentials_expire_at !== null || $dt !== null) {
            $currentDateAsString = ($this->credentials_expire_at !== null && $tmpDt = new DateTime($this->credentials_expire_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->credentials_expire_at = $newDateAsString;
                $this->modifiedColumns[] = PUserArchivePeer::CREDENTIALS_EXPIRE_AT;
            }
        } // if either are not null


        return $this;
    } // setCredentialsExpireAt()

    /**
     * Set the value of [roles] column.
     *
     * @param  array $v new value
     * @return PUserArchive The current object (for fluent API support)
     */
    public function setRoles($v)
    {
        if ($this->roles_unserialized !== $v) {
            $this->roles_unserialized = $v;
            $this->roles = '| ' . implode(' | ', (array) $v) . ' |';
            $this->modifiedColumns[] = PUserArchivePeer::ROLES;
        }


        return $this;
    } // setRoles()

    /**
     * Adds a value to the [roles] array column value.
     * @param mixed $value
     *
     * @return PUserArchive The current object (for fluent API support)
     */
    public function addRole($value)
    {
        $currentArray = $this->getRoles();
        $currentArray []= $value;
        $this->setRoles($currentArray);

        return $this;
    } // addRole()

    /**
     * Removes a value from the [roles] array column value.
     * @param mixed $value
     *
     * @return PUserArchive The current object (for fluent API support)
     */
    public function removeRole($value)
    {
        $targetArray = array();
        foreach ($this->getRoles() as $element) {
            if ($element != $value) {
                $targetArray []= $element;
            }
        }
        $this->setRoles($targetArray);

        return $this;
    } // removeRole()

    /**
     * Sets the value of [last_activity] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return PUserArchive The current object (for fluent API support)
     */
    public function setLastActivity($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->last_activity !== null || $dt !== null) {
            $currentDateAsString = ($this->last_activity !== null && $tmpDt = new DateTime($this->last_activity)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->last_activity = $newDateAsString;
                $this->modifiedColumns[] = PUserArchivePeer::LAST_ACTIVITY;
            }
        } // if either are not null


        return $this;
    } // setLastActivity()

    /**
     * Set the value of [file_name] column.
     *
     * @param  string $v new value
     * @return PUserArchive The current object (for fluent API support)
     */
    public function setFileName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->file_name !== $v) {
            $this->file_name = $v;
            $this->modifiedColumns[] = PUserArchivePeer::FILE_NAME;
        }


        return $this;
    } // setFileName()

    /**
     * Set the value of [back_file_name] column.
     *
     * @param  string $v new value
     * @return PUserArchive The current object (for fluent API support)
     */
    public function setBackFileName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->back_file_name !== $v) {
            $this->back_file_name = $v;
            $this->modifiedColumns[] = PUserArchivePeer::BACK_FILE_NAME;
        }


        return $this;
    } // setBackFileName()

    /**
     * Set the value of [copyright] column.
     *
     * @param  string $v new value
     * @return PUserArchive The current object (for fluent API support)
     */
    public function setCopyright($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->copyright !== $v) {
            $this->copyright = $v;
            $this->modifiedColumns[] = PUserArchivePeer::COPYRIGHT;
        }


        return $this;
    } // setCopyright()

    /**
     * Set the value of [gender] column.
     *
     * @param  int $v new value
     * @return PUserArchive The current object (for fluent API support)
     * @throws PropelException - if the value is not accepted by this enum.
     */
    public function setGender($v)
    {
        if ($v !== null) {
            $valueSet = PUserArchivePeer::getValueSet(PUserArchivePeer::GENDER);
            if (!in_array($v, $valueSet)) {
                throw new PropelException(sprintf('Value "%s" is not accepted in this enumerated column', $v));
            }
            $v = array_search($v, $valueSet);
        }

        if ($this->gender !== $v) {
            $this->gender = $v;
            $this->modifiedColumns[] = PUserArchivePeer::GENDER;
        }


        return $this;
    } // setGender()

    /**
     * Set the value of [firstname] column.
     *
     * @param  string $v new value
     * @return PUserArchive The current object (for fluent API support)
     */
    public function setFirstname($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->firstname !== $v) {
            $this->firstname = $v;
            $this->modifiedColumns[] = PUserArchivePeer::FIRSTNAME;
        }


        return $this;
    } // setFirstname()

    /**
     * Set the value of [name] column.
     *
     * @param  string $v new value
     * @return PUserArchive The current object (for fluent API support)
     */
    public function setName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->name !== $v) {
            $this->name = $v;
            $this->modifiedColumns[] = PUserArchivePeer::NAME;
        }


        return $this;
    } // setName()

    /**
     * Sets the value of [birthday] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return PUserArchive The current object (for fluent API support)
     */
    public function setBirthday($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->birthday !== null || $dt !== null) {
            $currentDateAsString = ($this->birthday !== null && $tmpDt = new DateTime($this->birthday)) ? $tmpDt->format('Y-m-d') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->birthday = $newDateAsString;
                $this->modifiedColumns[] = PUserArchivePeer::BIRTHDAY;
            }
        } // if either are not null


        return $this;
    } // setBirthday()

    /**
     * Set the value of [subtitle] column.
     *
     * @param  string $v new value
     * @return PUserArchive The current object (for fluent API support)
     */
    public function setSubtitle($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->subtitle !== $v) {
            $this->subtitle = $v;
            $this->modifiedColumns[] = PUserArchivePeer::SUBTITLE;
        }


        return $this;
    } // setSubtitle()

    /**
     * Set the value of [biography] column.
     *
     * @param  string $v new value
     * @return PUserArchive The current object (for fluent API support)
     */
    public function setBiography($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->biography !== $v) {
            $this->biography = $v;
            $this->modifiedColumns[] = PUserArchivePeer::BIOGRAPHY;
        }


        return $this;
    } // setBiography()

    /**
     * Set the value of [website] column.
     *
     * @param  string $v new value
     * @return PUserArchive The current object (for fluent API support)
     */
    public function setWebsite($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->website !== $v) {
            $this->website = $v;
            $this->modifiedColumns[] = PUserArchivePeer::WEBSITE;
        }


        return $this;
    } // setWebsite()

    /**
     * Set the value of [twitter] column.
     *
     * @param  string $v new value
     * @return PUserArchive The current object (for fluent API support)
     */
    public function setTwitter($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->twitter !== $v) {
            $this->twitter = $v;
            $this->modifiedColumns[] = PUserArchivePeer::TWITTER;
        }


        return $this;
    } // setTwitter()

    /**
     * Set the value of [facebook] column.
     *
     * @param  string $v new value
     * @return PUserArchive The current object (for fluent API support)
     */
    public function setFacebook($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->facebook !== $v) {
            $this->facebook = $v;
            $this->modifiedColumns[] = PUserArchivePeer::FACEBOOK;
        }


        return $this;
    } // setFacebook()

    /**
     * Set the value of [phone] column.
     *
     * @param  string $v new value
     * @return PUserArchive The current object (for fluent API support)
     */
    public function setPhone($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->phone !== $v) {
            $this->phone = $v;
            $this->modifiedColumns[] = PUserArchivePeer::PHONE;
        }


        return $this;
    } // setPhone()

    /**
     * Sets the value of the [newsletter] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param boolean|integer|string $v The new value
     * @return PUserArchive The current object (for fluent API support)
     */
    public function setNewsletter($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->newsletter !== $v) {
            $this->newsletter = $v;
            $this->modifiedColumns[] = PUserArchivePeer::NEWSLETTER;
        }


        return $this;
    } // setNewsletter()

    /**
     * Sets the value of [last_connect] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return PUserArchive The current object (for fluent API support)
     */
    public function setLastConnect($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->last_connect !== null || $dt !== null) {
            $currentDateAsString = ($this->last_connect !== null && $tmpDt = new DateTime($this->last_connect)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->last_connect = $newDateAsString;
                $this->modifiedColumns[] = PUserArchivePeer::LAST_CONNECT;
            }
        } // if either are not null


        return $this;
    } // setLastConnect()

    /**
     * Set the value of [nb_connected_days] column.
     *
     * @param  int $v new value
     * @return PUserArchive The current object (for fluent API support)
     */
    public function setNbConnectedDays($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->nb_connected_days !== $v) {
            $this->nb_connected_days = $v;
            $this->modifiedColumns[] = PUserArchivePeer::NB_CONNECTED_DAYS;
        }


        return $this;
    } // setNbConnectedDays()

    /**
     * Sets the value of [indexed_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return PUserArchive The current object (for fluent API support)
     */
    public function setIndexedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->indexed_at !== null || $dt !== null) {
            $currentDateAsString = ($this->indexed_at !== null && $tmpDt = new DateTime($this->indexed_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->indexed_at = $newDateAsString;
                $this->modifiedColumns[] = PUserArchivePeer::INDEXED_AT;
            }
        } // if either are not null


        return $this;
    } // setIndexedAt()

    /**
     * Set the value of [nb_views] column.
     *
     * @param  int $v new value
     * @return PUserArchive The current object (for fluent API support)
     */
    public function setNbViews($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->nb_views !== $v) {
            $this->nb_views = $v;
            $this->modifiedColumns[] = PUserArchivePeer::NB_VIEWS;
        }


        return $this;
    } // setNbViews()

    /**
     * Sets the value of the [qualified] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param boolean|integer|string $v The new value
     * @return PUserArchive The current object (for fluent API support)
     */
    public function setQualified($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->qualified !== $v) {
            $this->qualified = $v;
            $this->modifiedColumns[] = PUserArchivePeer::QUALIFIED;
        }


        return $this;
    } // setQualified()

    /**
     * Sets the value of the [validated] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param boolean|integer|string $v The new value
     * @return PUserArchive The current object (for fluent API support)
     */
    public function setValidated($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->validated !== $v) {
            $this->validated = $v;
            $this->modifiedColumns[] = PUserArchivePeer::VALIDATED;
        }


        return $this;
    } // setValidated()

    /**
     * Set the value of [nb_id_check] column.
     *
     * @param  int $v new value
     * @return PUserArchive The current object (for fluent API support)
     */
    public function setNbIdCheck($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->nb_id_check !== $v) {
            $this->nb_id_check = $v;
            $this->modifiedColumns[] = PUserArchivePeer::NB_ID_CHECK;
        }


        return $this;
    } // setNbIdCheck()

    /**
     * Sets the value of the [organization] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param boolean|integer|string $v The new value
     * @return PUserArchive The current object (for fluent API support)
     */
    public function setOrganization($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->organization !== $v) {
            $this->organization = $v;
            $this->modifiedColumns[] = PUserArchivePeer::ORGANIZATION;
        }


        return $this;
    } // setOrganization()

    /**
     * Sets the value of the [online] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param boolean|integer|string $v The new value
     * @return PUserArchive The current object (for fluent API support)
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
            $this->modifiedColumns[] = PUserArchivePeer::ONLINE;
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
     * @return PUserArchive The current object (for fluent API support)
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
            $this->modifiedColumns[] = PUserArchivePeer::HOMEPAGE;
        }


        return $this;
    } // setHomepage()

    /**
     * Sets the value of the [support_group] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param boolean|integer|string $v The new value
     * @return PUserArchive The current object (for fluent API support)
     */
    public function setSupportGroup($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->support_group !== $v) {
            $this->support_group = $v;
            $this->modifiedColumns[] = PUserArchivePeer::SUPPORT_GROUP;
        }


        return $this;
    } // setSupportGroup()

    /**
     * Sets the value of the [banned] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param boolean|integer|string $v The new value
     * @return PUserArchive The current object (for fluent API support)
     */
    public function setBanned($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->banned !== $v) {
            $this->banned = $v;
            $this->modifiedColumns[] = PUserArchivePeer::BANNED;
        }


        return $this;
    } // setBanned()

    /**
     * Set the value of [banned_nb_days_left] column.
     *
     * @param  int $v new value
     * @return PUserArchive The current object (for fluent API support)
     */
    public function setBannedNbDaysLeft($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->banned_nb_days_left !== $v) {
            $this->banned_nb_days_left = $v;
            $this->modifiedColumns[] = PUserArchivePeer::BANNED_NB_DAYS_LEFT;
        }


        return $this;
    } // setBannedNbDaysLeft()

    /**
     * Set the value of [banned_nb_total] column.
     *
     * @param  int $v new value
     * @return PUserArchive The current object (for fluent API support)
     */
    public function setBannedNbTotal($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->banned_nb_total !== $v) {
            $this->banned_nb_total = $v;
            $this->modifiedColumns[] = PUserArchivePeer::BANNED_NB_TOTAL;
        }


        return $this;
    } // setBannedNbTotal()

    /**
     * Set the value of [abuse_level] column.
     *
     * @param  int $v new value
     * @return PUserArchive The current object (for fluent API support)
     */
    public function setAbuseLevel($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->abuse_level !== $v) {
            $this->abuse_level = $v;
            $this->modifiedColumns[] = PUserArchivePeer::ABUSE_LEVEL;
        }


        return $this;
    } // setAbuseLevel()

    /**
     * Sets the value of [created_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return PUserArchive The current object (for fluent API support)
     */
    public function setCreatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->created_at !== null || $dt !== null) {
            $currentDateAsString = ($this->created_at !== null && $tmpDt = new DateTime($this->created_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->created_at = $newDateAsString;
                $this->modifiedColumns[] = PUserArchivePeer::CREATED_AT;
            }
        } // if either are not null


        return $this;
    } // setCreatedAt()

    /**
     * Sets the value of [updated_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return PUserArchive The current object (for fluent API support)
     */
    public function setUpdatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->updated_at !== null || $dt !== null) {
            $currentDateAsString = ($this->updated_at !== null && $tmpDt = new DateTime($this->updated_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->updated_at = $newDateAsString;
                $this->modifiedColumns[] = PUserArchivePeer::UPDATED_AT;
            }
        } // if either are not null


        return $this;
    } // setUpdatedAt()

    /**
     * Set the value of [slug] column.
     *
     * @param  string $v new value
     * @return PUserArchive The current object (for fluent API support)
     */
    public function setSlug($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->slug !== $v) {
            $this->slug = $v;
            $this->modifiedColumns[] = PUserArchivePeer::SLUG;
        }


        return $this;
    } // setSlug()

    /**
     * Sets the value of [archived_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return PUserArchive The current object (for fluent API support)
     */
    public function setArchivedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->archived_at !== null || $dt !== null) {
            $currentDateAsString = ($this->archived_at !== null && $tmpDt = new DateTime($this->archived_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->archived_at = $newDateAsString;
                $this->modifiedColumns[] = PUserArchivePeer::ARCHIVED_AT;
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
            if ($this->enabled !== false) {
                return false;
            }

            if ($this->locked !== false) {
                return false;
            }

            if ($this->expired !== false) {
                return false;
            }

            if ($this->credentials_expired !== false) {
                return false;
            }

            if ($this->nb_connected_days !== 0) {
                return false;
            }

            if ($this->validated !== false) {
                return false;
            }

            if ($this->organization !== false) {
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
            $this->p_u_status_id = ($row[$startcol + 2] !== null) ? (int) $row[$startcol + 2] : null;
            $this->p_l_city_id = ($row[$startcol + 3] !== null) ? (int) $row[$startcol + 3] : null;
            $this->provider = ($row[$startcol + 4] !== null) ? (string) $row[$startcol + 4] : null;
            $this->provider_id = ($row[$startcol + 5] !== null) ? (string) $row[$startcol + 5] : null;
            $this->nickname = ($row[$startcol + 6] !== null) ? (string) $row[$startcol + 6] : null;
            $this->realname = ($row[$startcol + 7] !== null) ? (string) $row[$startcol + 7] : null;
            $this->username = ($row[$startcol + 8] !== null) ? (string) $row[$startcol + 8] : null;
            $this->username_canonical = ($row[$startcol + 9] !== null) ? (string) $row[$startcol + 9] : null;
            $this->email = ($row[$startcol + 10] !== null) ? (string) $row[$startcol + 10] : null;
            $this->email_canonical = ($row[$startcol + 11] !== null) ? (string) $row[$startcol + 11] : null;
            $this->enabled = ($row[$startcol + 12] !== null) ? (boolean) $row[$startcol + 12] : null;
            $this->salt = ($row[$startcol + 13] !== null) ? (string) $row[$startcol + 13] : null;
            $this->password = ($row[$startcol + 14] !== null) ? (string) $row[$startcol + 14] : null;
            $this->last_login = ($row[$startcol + 15] !== null) ? (string) $row[$startcol + 15] : null;
            $this->locked = ($row[$startcol + 16] !== null) ? (boolean) $row[$startcol + 16] : null;
            $this->expired = ($row[$startcol + 17] !== null) ? (boolean) $row[$startcol + 17] : null;
            $this->expires_at = ($row[$startcol + 18] !== null) ? (string) $row[$startcol + 18] : null;
            $this->confirmation_token = ($row[$startcol + 19] !== null) ? (string) $row[$startcol + 19] : null;
            $this->password_requested_at = ($row[$startcol + 20] !== null) ? (string) $row[$startcol + 20] : null;
            $this->credentials_expired = ($row[$startcol + 21] !== null) ? (boolean) $row[$startcol + 21] : null;
            $this->credentials_expire_at = ($row[$startcol + 22] !== null) ? (string) $row[$startcol + 22] : null;
            $this->roles = $row[$startcol + 23];
            $this->roles_unserialized = null;
            $this->last_activity = ($row[$startcol + 24] !== null) ? (string) $row[$startcol + 24] : null;
            $this->file_name = ($row[$startcol + 25] !== null) ? (string) $row[$startcol + 25] : null;
            $this->back_file_name = ($row[$startcol + 26] !== null) ? (string) $row[$startcol + 26] : null;
            $this->copyright = ($row[$startcol + 27] !== null) ? (string) $row[$startcol + 27] : null;
            $this->gender = ($row[$startcol + 28] !== null) ? (int) $row[$startcol + 28] : null;
            $this->firstname = ($row[$startcol + 29] !== null) ? (string) $row[$startcol + 29] : null;
            $this->name = ($row[$startcol + 30] !== null) ? (string) $row[$startcol + 30] : null;
            $this->birthday = ($row[$startcol + 31] !== null) ? (string) $row[$startcol + 31] : null;
            $this->subtitle = ($row[$startcol + 32] !== null) ? (string) $row[$startcol + 32] : null;
            $this->biography = ($row[$startcol + 33] !== null) ? (string) $row[$startcol + 33] : null;
            $this->website = ($row[$startcol + 34] !== null) ? (string) $row[$startcol + 34] : null;
            $this->twitter = ($row[$startcol + 35] !== null) ? (string) $row[$startcol + 35] : null;
            $this->facebook = ($row[$startcol + 36] !== null) ? (string) $row[$startcol + 36] : null;
            $this->phone = ($row[$startcol + 37] !== null) ? (string) $row[$startcol + 37] : null;
            $this->newsletter = ($row[$startcol + 38] !== null) ? (boolean) $row[$startcol + 38] : null;
            $this->last_connect = ($row[$startcol + 39] !== null) ? (string) $row[$startcol + 39] : null;
            $this->nb_connected_days = ($row[$startcol + 40] !== null) ? (int) $row[$startcol + 40] : null;
            $this->indexed_at = ($row[$startcol + 41] !== null) ? (string) $row[$startcol + 41] : null;
            $this->nb_views = ($row[$startcol + 42] !== null) ? (int) $row[$startcol + 42] : null;
            $this->qualified = ($row[$startcol + 43] !== null) ? (boolean) $row[$startcol + 43] : null;
            $this->validated = ($row[$startcol + 44] !== null) ? (boolean) $row[$startcol + 44] : null;
            $this->nb_id_check = ($row[$startcol + 45] !== null) ? (int) $row[$startcol + 45] : null;
            $this->organization = ($row[$startcol + 46] !== null) ? (boolean) $row[$startcol + 46] : null;
            $this->online = ($row[$startcol + 47] !== null) ? (boolean) $row[$startcol + 47] : null;
            $this->homepage = ($row[$startcol + 48] !== null) ? (boolean) $row[$startcol + 48] : null;
            $this->support_group = ($row[$startcol + 49] !== null) ? (boolean) $row[$startcol + 49] : null;
            $this->banned = ($row[$startcol + 50] !== null) ? (boolean) $row[$startcol + 50] : null;
            $this->banned_nb_days_left = ($row[$startcol + 51] !== null) ? (int) $row[$startcol + 51] : null;
            $this->banned_nb_total = ($row[$startcol + 52] !== null) ? (int) $row[$startcol + 52] : null;
            $this->abuse_level = ($row[$startcol + 53] !== null) ? (int) $row[$startcol + 53] : null;
            $this->created_at = ($row[$startcol + 54] !== null) ? (string) $row[$startcol + 54] : null;
            $this->updated_at = ($row[$startcol + 55] !== null) ? (string) $row[$startcol + 55] : null;
            $this->slug = ($row[$startcol + 56] !== null) ? (string) $row[$startcol + 56] : null;
            $this->archived_at = ($row[$startcol + 57] !== null) ? (string) $row[$startcol + 57] : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }
            $this->postHydrate($row, $startcol, $rehydrate);

            return $startcol + 58; // 58 = PUserArchivePeer::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating PUserArchive object", $e);
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
            $con = Propel::getConnection(PUserArchivePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $stmt = PUserArchivePeer::doSelectStmt($this->buildPkeyCriteria(), $con);
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
            $con = Propel::getConnection(PUserArchivePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        try {
            $deleteQuery = PUserArchiveQuery::create()
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
            $con = Propel::getConnection(PUserArchivePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
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
                PUserArchivePeer::addInstanceToPool($this);
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
        if ($this->isColumnModified(PUserArchivePeer::ID)) {
            $modifiedColumns[':p' . $index++]  = '`id`';
        }
        if ($this->isColumnModified(PUserArchivePeer::UUID)) {
            $modifiedColumns[':p' . $index++]  = '`uuid`';
        }
        if ($this->isColumnModified(PUserArchivePeer::P_U_STATUS_ID)) {
            $modifiedColumns[':p' . $index++]  = '`p_u_status_id`';
        }
        if ($this->isColumnModified(PUserArchivePeer::P_L_CITY_ID)) {
            $modifiedColumns[':p' . $index++]  = '`p_l_city_id`';
        }
        if ($this->isColumnModified(PUserArchivePeer::PROVIDER)) {
            $modifiedColumns[':p' . $index++]  = '`provider`';
        }
        if ($this->isColumnModified(PUserArchivePeer::PROVIDER_ID)) {
            $modifiedColumns[':p' . $index++]  = '`provider_id`';
        }
        if ($this->isColumnModified(PUserArchivePeer::NICKNAME)) {
            $modifiedColumns[':p' . $index++]  = '`nickname`';
        }
        if ($this->isColumnModified(PUserArchivePeer::REALNAME)) {
            $modifiedColumns[':p' . $index++]  = '`realname`';
        }
        if ($this->isColumnModified(PUserArchivePeer::USERNAME)) {
            $modifiedColumns[':p' . $index++]  = '`username`';
        }
        if ($this->isColumnModified(PUserArchivePeer::USERNAME_CANONICAL)) {
            $modifiedColumns[':p' . $index++]  = '`username_canonical`';
        }
        if ($this->isColumnModified(PUserArchivePeer::EMAIL)) {
            $modifiedColumns[':p' . $index++]  = '`email`';
        }
        if ($this->isColumnModified(PUserArchivePeer::EMAIL_CANONICAL)) {
            $modifiedColumns[':p' . $index++]  = '`email_canonical`';
        }
        if ($this->isColumnModified(PUserArchivePeer::ENABLED)) {
            $modifiedColumns[':p' . $index++]  = '`enabled`';
        }
        if ($this->isColumnModified(PUserArchivePeer::SALT)) {
            $modifiedColumns[':p' . $index++]  = '`salt`';
        }
        if ($this->isColumnModified(PUserArchivePeer::PASSWORD)) {
            $modifiedColumns[':p' . $index++]  = '`password`';
        }
        if ($this->isColumnModified(PUserArchivePeer::LAST_LOGIN)) {
            $modifiedColumns[':p' . $index++]  = '`last_login`';
        }
        if ($this->isColumnModified(PUserArchivePeer::LOCKED)) {
            $modifiedColumns[':p' . $index++]  = '`locked`';
        }
        if ($this->isColumnModified(PUserArchivePeer::EXPIRED)) {
            $modifiedColumns[':p' . $index++]  = '`expired`';
        }
        if ($this->isColumnModified(PUserArchivePeer::EXPIRES_AT)) {
            $modifiedColumns[':p' . $index++]  = '`expires_at`';
        }
        if ($this->isColumnModified(PUserArchivePeer::CONFIRMATION_TOKEN)) {
            $modifiedColumns[':p' . $index++]  = '`confirmation_token`';
        }
        if ($this->isColumnModified(PUserArchivePeer::PASSWORD_REQUESTED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`password_requested_at`';
        }
        if ($this->isColumnModified(PUserArchivePeer::CREDENTIALS_EXPIRED)) {
            $modifiedColumns[':p' . $index++]  = '`credentials_expired`';
        }
        if ($this->isColumnModified(PUserArchivePeer::CREDENTIALS_EXPIRE_AT)) {
            $modifiedColumns[':p' . $index++]  = '`credentials_expire_at`';
        }
        if ($this->isColumnModified(PUserArchivePeer::ROLES)) {
            $modifiedColumns[':p' . $index++]  = '`roles`';
        }
        if ($this->isColumnModified(PUserArchivePeer::LAST_ACTIVITY)) {
            $modifiedColumns[':p' . $index++]  = '`last_activity`';
        }
        if ($this->isColumnModified(PUserArchivePeer::FILE_NAME)) {
            $modifiedColumns[':p' . $index++]  = '`file_name`';
        }
        if ($this->isColumnModified(PUserArchivePeer::BACK_FILE_NAME)) {
            $modifiedColumns[':p' . $index++]  = '`back_file_name`';
        }
        if ($this->isColumnModified(PUserArchivePeer::COPYRIGHT)) {
            $modifiedColumns[':p' . $index++]  = '`copyright`';
        }
        if ($this->isColumnModified(PUserArchivePeer::GENDER)) {
            $modifiedColumns[':p' . $index++]  = '`gender`';
        }
        if ($this->isColumnModified(PUserArchivePeer::FIRSTNAME)) {
            $modifiedColumns[':p' . $index++]  = '`firstname`';
        }
        if ($this->isColumnModified(PUserArchivePeer::NAME)) {
            $modifiedColumns[':p' . $index++]  = '`name`';
        }
        if ($this->isColumnModified(PUserArchivePeer::BIRTHDAY)) {
            $modifiedColumns[':p' . $index++]  = '`birthday`';
        }
        if ($this->isColumnModified(PUserArchivePeer::SUBTITLE)) {
            $modifiedColumns[':p' . $index++]  = '`subtitle`';
        }
        if ($this->isColumnModified(PUserArchivePeer::BIOGRAPHY)) {
            $modifiedColumns[':p' . $index++]  = '`biography`';
        }
        if ($this->isColumnModified(PUserArchivePeer::WEBSITE)) {
            $modifiedColumns[':p' . $index++]  = '`website`';
        }
        if ($this->isColumnModified(PUserArchivePeer::TWITTER)) {
            $modifiedColumns[':p' . $index++]  = '`twitter`';
        }
        if ($this->isColumnModified(PUserArchivePeer::FACEBOOK)) {
            $modifiedColumns[':p' . $index++]  = '`facebook`';
        }
        if ($this->isColumnModified(PUserArchivePeer::PHONE)) {
            $modifiedColumns[':p' . $index++]  = '`phone`';
        }
        if ($this->isColumnModified(PUserArchivePeer::NEWSLETTER)) {
            $modifiedColumns[':p' . $index++]  = '`newsletter`';
        }
        if ($this->isColumnModified(PUserArchivePeer::LAST_CONNECT)) {
            $modifiedColumns[':p' . $index++]  = '`last_connect`';
        }
        if ($this->isColumnModified(PUserArchivePeer::NB_CONNECTED_DAYS)) {
            $modifiedColumns[':p' . $index++]  = '`nb_connected_days`';
        }
        if ($this->isColumnModified(PUserArchivePeer::INDEXED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`indexed_at`';
        }
        if ($this->isColumnModified(PUserArchivePeer::NB_VIEWS)) {
            $modifiedColumns[':p' . $index++]  = '`nb_views`';
        }
        if ($this->isColumnModified(PUserArchivePeer::QUALIFIED)) {
            $modifiedColumns[':p' . $index++]  = '`qualified`';
        }
        if ($this->isColumnModified(PUserArchivePeer::VALIDATED)) {
            $modifiedColumns[':p' . $index++]  = '`validated`';
        }
        if ($this->isColumnModified(PUserArchivePeer::NB_ID_CHECK)) {
            $modifiedColumns[':p' . $index++]  = '`nb_id_check`';
        }
        if ($this->isColumnModified(PUserArchivePeer::ORGANIZATION)) {
            $modifiedColumns[':p' . $index++]  = '`organization`';
        }
        if ($this->isColumnModified(PUserArchivePeer::ONLINE)) {
            $modifiedColumns[':p' . $index++]  = '`online`';
        }
        if ($this->isColumnModified(PUserArchivePeer::HOMEPAGE)) {
            $modifiedColumns[':p' . $index++]  = '`homepage`';
        }
        if ($this->isColumnModified(PUserArchivePeer::SUPPORT_GROUP)) {
            $modifiedColumns[':p' . $index++]  = '`support_group`';
        }
        if ($this->isColumnModified(PUserArchivePeer::BANNED)) {
            $modifiedColumns[':p' . $index++]  = '`banned`';
        }
        if ($this->isColumnModified(PUserArchivePeer::BANNED_NB_DAYS_LEFT)) {
            $modifiedColumns[':p' . $index++]  = '`banned_nb_days_left`';
        }
        if ($this->isColumnModified(PUserArchivePeer::BANNED_NB_TOTAL)) {
            $modifiedColumns[':p' . $index++]  = '`banned_nb_total`';
        }
        if ($this->isColumnModified(PUserArchivePeer::ABUSE_LEVEL)) {
            $modifiedColumns[':p' . $index++]  = '`abuse_level`';
        }
        if ($this->isColumnModified(PUserArchivePeer::CREATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`created_at`';
        }
        if ($this->isColumnModified(PUserArchivePeer::UPDATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`updated_at`';
        }
        if ($this->isColumnModified(PUserArchivePeer::SLUG)) {
            $modifiedColumns[':p' . $index++]  = '`slug`';
        }
        if ($this->isColumnModified(PUserArchivePeer::ARCHIVED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`archived_at`';
        }

        $sql = sprintf(
            'INSERT INTO `p_user_archive` (%s) VALUES (%s)',
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
                    case '`p_u_status_id`':
                        $stmt->bindValue($identifier, $this->p_u_status_id, PDO::PARAM_INT);
                        break;
                    case '`p_l_city_id`':
                        $stmt->bindValue($identifier, $this->p_l_city_id, PDO::PARAM_INT);
                        break;
                    case '`provider`':
                        $stmt->bindValue($identifier, $this->provider, PDO::PARAM_STR);
                        break;
                    case '`provider_id`':
                        $stmt->bindValue($identifier, $this->provider_id, PDO::PARAM_STR);
                        break;
                    case '`nickname`':
                        $stmt->bindValue($identifier, $this->nickname, PDO::PARAM_STR);
                        break;
                    case '`realname`':
                        $stmt->bindValue($identifier, $this->realname, PDO::PARAM_STR);
                        break;
                    case '`username`':
                        $stmt->bindValue($identifier, $this->username, PDO::PARAM_STR);
                        break;
                    case '`username_canonical`':
                        $stmt->bindValue($identifier, $this->username_canonical, PDO::PARAM_STR);
                        break;
                    case '`email`':
                        $stmt->bindValue($identifier, $this->email, PDO::PARAM_STR);
                        break;
                    case '`email_canonical`':
                        $stmt->bindValue($identifier, $this->email_canonical, PDO::PARAM_STR);
                        break;
                    case '`enabled`':
                        $stmt->bindValue($identifier, (int) $this->enabled, PDO::PARAM_INT);
                        break;
                    case '`salt`':
                        $stmt->bindValue($identifier, $this->salt, PDO::PARAM_STR);
                        break;
                    case '`password`':
                        $stmt->bindValue($identifier, $this->password, PDO::PARAM_STR);
                        break;
                    case '`last_login`':
                        $stmt->bindValue($identifier, $this->last_login, PDO::PARAM_STR);
                        break;
                    case '`locked`':
                        $stmt->bindValue($identifier, (int) $this->locked, PDO::PARAM_INT);
                        break;
                    case '`expired`':
                        $stmt->bindValue($identifier, (int) $this->expired, PDO::PARAM_INT);
                        break;
                    case '`expires_at`':
                        $stmt->bindValue($identifier, $this->expires_at, PDO::PARAM_STR);
                        break;
                    case '`confirmation_token`':
                        $stmt->bindValue($identifier, $this->confirmation_token, PDO::PARAM_STR);
                        break;
                    case '`password_requested_at`':
                        $stmt->bindValue($identifier, $this->password_requested_at, PDO::PARAM_STR);
                        break;
                    case '`credentials_expired`':
                        $stmt->bindValue($identifier, (int) $this->credentials_expired, PDO::PARAM_INT);
                        break;
                    case '`credentials_expire_at`':
                        $stmt->bindValue($identifier, $this->credentials_expire_at, PDO::PARAM_STR);
                        break;
                    case '`roles`':
                        $stmt->bindValue($identifier, $this->roles, PDO::PARAM_STR);
                        break;
                    case '`last_activity`':
                        $stmt->bindValue($identifier, $this->last_activity, PDO::PARAM_STR);
                        break;
                    case '`file_name`':
                        $stmt->bindValue($identifier, $this->file_name, PDO::PARAM_STR);
                        break;
                    case '`back_file_name`':
                        $stmt->bindValue($identifier, $this->back_file_name, PDO::PARAM_STR);
                        break;
                    case '`copyright`':
                        $stmt->bindValue($identifier, $this->copyright, PDO::PARAM_STR);
                        break;
                    case '`gender`':
                        $stmt->bindValue($identifier, $this->gender, PDO::PARAM_INT);
                        break;
                    case '`firstname`':
                        $stmt->bindValue($identifier, $this->firstname, PDO::PARAM_STR);
                        break;
                    case '`name`':
                        $stmt->bindValue($identifier, $this->name, PDO::PARAM_STR);
                        break;
                    case '`birthday`':
                        $stmt->bindValue($identifier, $this->birthday, PDO::PARAM_STR);
                        break;
                    case '`subtitle`':
                        $stmt->bindValue($identifier, $this->subtitle, PDO::PARAM_STR);
                        break;
                    case '`biography`':
                        $stmt->bindValue($identifier, $this->biography, PDO::PARAM_STR);
                        break;
                    case '`website`':
                        $stmt->bindValue($identifier, $this->website, PDO::PARAM_STR);
                        break;
                    case '`twitter`':
                        $stmt->bindValue($identifier, $this->twitter, PDO::PARAM_STR);
                        break;
                    case '`facebook`':
                        $stmt->bindValue($identifier, $this->facebook, PDO::PARAM_STR);
                        break;
                    case '`phone`':
                        $stmt->bindValue($identifier, $this->phone, PDO::PARAM_STR);
                        break;
                    case '`newsletter`':
                        $stmt->bindValue($identifier, (int) $this->newsletter, PDO::PARAM_INT);
                        break;
                    case '`last_connect`':
                        $stmt->bindValue($identifier, $this->last_connect, PDO::PARAM_STR);
                        break;
                    case '`nb_connected_days`':
                        $stmt->bindValue($identifier, $this->nb_connected_days, PDO::PARAM_INT);
                        break;
                    case '`indexed_at`':
                        $stmt->bindValue($identifier, $this->indexed_at, PDO::PARAM_STR);
                        break;
                    case '`nb_views`':
                        $stmt->bindValue($identifier, $this->nb_views, PDO::PARAM_INT);
                        break;
                    case '`qualified`':
                        $stmt->bindValue($identifier, (int) $this->qualified, PDO::PARAM_INT);
                        break;
                    case '`validated`':
                        $stmt->bindValue($identifier, (int) $this->validated, PDO::PARAM_INT);
                        break;
                    case '`nb_id_check`':
                        $stmt->bindValue($identifier, $this->nb_id_check, PDO::PARAM_INT);
                        break;
                    case '`organization`':
                        $stmt->bindValue($identifier, (int) $this->organization, PDO::PARAM_INT);
                        break;
                    case '`online`':
                        $stmt->bindValue($identifier, (int) $this->online, PDO::PARAM_INT);
                        break;
                    case '`homepage`':
                        $stmt->bindValue($identifier, (int) $this->homepage, PDO::PARAM_INT);
                        break;
                    case '`support_group`':
                        $stmt->bindValue($identifier, (int) $this->support_group, PDO::PARAM_INT);
                        break;
                    case '`banned`':
                        $stmt->bindValue($identifier, (int) $this->banned, PDO::PARAM_INT);
                        break;
                    case '`banned_nb_days_left`':
                        $stmt->bindValue($identifier, $this->banned_nb_days_left, PDO::PARAM_INT);
                        break;
                    case '`banned_nb_total`':
                        $stmt->bindValue($identifier, $this->banned_nb_total, PDO::PARAM_INT);
                        break;
                    case '`abuse_level`':
                        $stmt->bindValue($identifier, $this->abuse_level, PDO::PARAM_INT);
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
        $pos = PUserArchivePeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                return $this->getPUStatusId();
                break;
            case 3:
                return $this->getPLCityId();
                break;
            case 4:
                return $this->getProvider();
                break;
            case 5:
                return $this->getProviderId();
                break;
            case 6:
                return $this->getNickname();
                break;
            case 7:
                return $this->getRealname();
                break;
            case 8:
                return $this->getUsername();
                break;
            case 9:
                return $this->getUsernameCanonical();
                break;
            case 10:
                return $this->getEmail();
                break;
            case 11:
                return $this->getEmailCanonical();
                break;
            case 12:
                return $this->getEnabled();
                break;
            case 13:
                return $this->getSalt();
                break;
            case 14:
                return $this->getPassword();
                break;
            case 15:
                return $this->getLastLogin();
                break;
            case 16:
                return $this->getLocked();
                break;
            case 17:
                return $this->getExpired();
                break;
            case 18:
                return $this->getExpiresAt();
                break;
            case 19:
                return $this->getConfirmationToken();
                break;
            case 20:
                return $this->getPasswordRequestedAt();
                break;
            case 21:
                return $this->getCredentialsExpired();
                break;
            case 22:
                return $this->getCredentialsExpireAt();
                break;
            case 23:
                return $this->getRoles();
                break;
            case 24:
                return $this->getLastActivity();
                break;
            case 25:
                return $this->getFileName();
                break;
            case 26:
                return $this->getBackFileName();
                break;
            case 27:
                return $this->getCopyright();
                break;
            case 28:
                return $this->getGender();
                break;
            case 29:
                return $this->getFirstname();
                break;
            case 30:
                return $this->getName();
                break;
            case 31:
                return $this->getBirthday();
                break;
            case 32:
                return $this->getSubtitle();
                break;
            case 33:
                return $this->getBiography();
                break;
            case 34:
                return $this->getWebsite();
                break;
            case 35:
                return $this->getTwitter();
                break;
            case 36:
                return $this->getFacebook();
                break;
            case 37:
                return $this->getPhone();
                break;
            case 38:
                return $this->getNewsletter();
                break;
            case 39:
                return $this->getLastConnect();
                break;
            case 40:
                return $this->getNbConnectedDays();
                break;
            case 41:
                return $this->getIndexedAt();
                break;
            case 42:
                return $this->getNbViews();
                break;
            case 43:
                return $this->getQualified();
                break;
            case 44:
                return $this->getValidated();
                break;
            case 45:
                return $this->getNbIdCheck();
                break;
            case 46:
                return $this->getOrganization();
                break;
            case 47:
                return $this->getOnline();
                break;
            case 48:
                return $this->getHomepage();
                break;
            case 49:
                return $this->getSupportGroup();
                break;
            case 50:
                return $this->getBanned();
                break;
            case 51:
                return $this->getBannedNbDaysLeft();
                break;
            case 52:
                return $this->getBannedNbTotal();
                break;
            case 53:
                return $this->getAbuseLevel();
                break;
            case 54:
                return $this->getCreatedAt();
                break;
            case 55:
                return $this->getUpdatedAt();
                break;
            case 56:
                return $this->getSlug();
                break;
            case 57:
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
        if (isset($alreadyDumpedObjects['PUserArchive'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['PUserArchive'][$this->getPrimaryKey()] = true;
        $keys = PUserArchivePeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getUuid(),
            $keys[2] => $this->getPUStatusId(),
            $keys[3] => $this->getPLCityId(),
            $keys[4] => $this->getProvider(),
            $keys[5] => $this->getProviderId(),
            $keys[6] => $this->getNickname(),
            $keys[7] => $this->getRealname(),
            $keys[8] => $this->getUsername(),
            $keys[9] => $this->getUsernameCanonical(),
            $keys[10] => $this->getEmail(),
            $keys[11] => $this->getEmailCanonical(),
            $keys[12] => $this->getEnabled(),
            $keys[13] => $this->getSalt(),
            $keys[14] => $this->getPassword(),
            $keys[15] => $this->getLastLogin(),
            $keys[16] => $this->getLocked(),
            $keys[17] => $this->getExpired(),
            $keys[18] => $this->getExpiresAt(),
            $keys[19] => $this->getConfirmationToken(),
            $keys[20] => $this->getPasswordRequestedAt(),
            $keys[21] => $this->getCredentialsExpired(),
            $keys[22] => $this->getCredentialsExpireAt(),
            $keys[23] => $this->getRoles(),
            $keys[24] => $this->getLastActivity(),
            $keys[25] => $this->getFileName(),
            $keys[26] => $this->getBackFileName(),
            $keys[27] => $this->getCopyright(),
            $keys[28] => $this->getGender(),
            $keys[29] => $this->getFirstname(),
            $keys[30] => $this->getName(),
            $keys[31] => $this->getBirthday(),
            $keys[32] => $this->getSubtitle(),
            $keys[33] => $this->getBiography(),
            $keys[34] => $this->getWebsite(),
            $keys[35] => $this->getTwitter(),
            $keys[36] => $this->getFacebook(),
            $keys[37] => $this->getPhone(),
            $keys[38] => $this->getNewsletter(),
            $keys[39] => $this->getLastConnect(),
            $keys[40] => $this->getNbConnectedDays(),
            $keys[41] => $this->getIndexedAt(),
            $keys[42] => $this->getNbViews(),
            $keys[43] => $this->getQualified(),
            $keys[44] => $this->getValidated(),
            $keys[45] => $this->getNbIdCheck(),
            $keys[46] => $this->getOrganization(),
            $keys[47] => $this->getOnline(),
            $keys[48] => $this->getHomepage(),
            $keys[49] => $this->getSupportGroup(),
            $keys[50] => $this->getBanned(),
            $keys[51] => $this->getBannedNbDaysLeft(),
            $keys[52] => $this->getBannedNbTotal(),
            $keys[53] => $this->getAbuseLevel(),
            $keys[54] => $this->getCreatedAt(),
            $keys[55] => $this->getUpdatedAt(),
            $keys[56] => $this->getSlug(),
            $keys[57] => $this->getArchivedAt(),
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
        $pos = PUserArchivePeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);

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
                $this->setPUStatusId($value);
                break;
            case 3:
                $this->setPLCityId($value);
                break;
            case 4:
                $this->setProvider($value);
                break;
            case 5:
                $this->setProviderId($value);
                break;
            case 6:
                $this->setNickname($value);
                break;
            case 7:
                $this->setRealname($value);
                break;
            case 8:
                $this->setUsername($value);
                break;
            case 9:
                $this->setUsernameCanonical($value);
                break;
            case 10:
                $this->setEmail($value);
                break;
            case 11:
                $this->setEmailCanonical($value);
                break;
            case 12:
                $this->setEnabled($value);
                break;
            case 13:
                $this->setSalt($value);
                break;
            case 14:
                $this->setPassword($value);
                break;
            case 15:
                $this->setLastLogin($value);
                break;
            case 16:
                $this->setLocked($value);
                break;
            case 17:
                $this->setExpired($value);
                break;
            case 18:
                $this->setExpiresAt($value);
                break;
            case 19:
                $this->setConfirmationToken($value);
                break;
            case 20:
                $this->setPasswordRequestedAt($value);
                break;
            case 21:
                $this->setCredentialsExpired($value);
                break;
            case 22:
                $this->setCredentialsExpireAt($value);
                break;
            case 23:
                if (!is_array($value)) {
                    $v = trim(substr($value, 2, -2));
                    $value = $v ? explode(' | ', $v) : array();
                }
                $this->setRoles($value);
                break;
            case 24:
                $this->setLastActivity($value);
                break;
            case 25:
                $this->setFileName($value);
                break;
            case 26:
                $this->setBackFileName($value);
                break;
            case 27:
                $this->setCopyright($value);
                break;
            case 28:
                $valueSet = PUserArchivePeer::getValueSet(PUserArchivePeer::GENDER);
                if (isset($valueSet[$value])) {
                    $value = $valueSet[$value];
                }
                $this->setGender($value);
                break;
            case 29:
                $this->setFirstname($value);
                break;
            case 30:
                $this->setName($value);
                break;
            case 31:
                $this->setBirthday($value);
                break;
            case 32:
                $this->setSubtitle($value);
                break;
            case 33:
                $this->setBiography($value);
                break;
            case 34:
                $this->setWebsite($value);
                break;
            case 35:
                $this->setTwitter($value);
                break;
            case 36:
                $this->setFacebook($value);
                break;
            case 37:
                $this->setPhone($value);
                break;
            case 38:
                $this->setNewsletter($value);
                break;
            case 39:
                $this->setLastConnect($value);
                break;
            case 40:
                $this->setNbConnectedDays($value);
                break;
            case 41:
                $this->setIndexedAt($value);
                break;
            case 42:
                $this->setNbViews($value);
                break;
            case 43:
                $this->setQualified($value);
                break;
            case 44:
                $this->setValidated($value);
                break;
            case 45:
                $this->setNbIdCheck($value);
                break;
            case 46:
                $this->setOrganization($value);
                break;
            case 47:
                $this->setOnline($value);
                break;
            case 48:
                $this->setHomepage($value);
                break;
            case 49:
                $this->setSupportGroup($value);
                break;
            case 50:
                $this->setBanned($value);
                break;
            case 51:
                $this->setBannedNbDaysLeft($value);
                break;
            case 52:
                $this->setBannedNbTotal($value);
                break;
            case 53:
                $this->setAbuseLevel($value);
                break;
            case 54:
                $this->setCreatedAt($value);
                break;
            case 55:
                $this->setUpdatedAt($value);
                break;
            case 56:
                $this->setSlug($value);
                break;
            case 57:
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
        $keys = PUserArchivePeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setUuid($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setPUStatusId($arr[$keys[2]]);
        if (array_key_exists($keys[3], $arr)) $this->setPLCityId($arr[$keys[3]]);
        if (array_key_exists($keys[4], $arr)) $this->setProvider($arr[$keys[4]]);
        if (array_key_exists($keys[5], $arr)) $this->setProviderId($arr[$keys[5]]);
        if (array_key_exists($keys[6], $arr)) $this->setNickname($arr[$keys[6]]);
        if (array_key_exists($keys[7], $arr)) $this->setRealname($arr[$keys[7]]);
        if (array_key_exists($keys[8], $arr)) $this->setUsername($arr[$keys[8]]);
        if (array_key_exists($keys[9], $arr)) $this->setUsernameCanonical($arr[$keys[9]]);
        if (array_key_exists($keys[10], $arr)) $this->setEmail($arr[$keys[10]]);
        if (array_key_exists($keys[11], $arr)) $this->setEmailCanonical($arr[$keys[11]]);
        if (array_key_exists($keys[12], $arr)) $this->setEnabled($arr[$keys[12]]);
        if (array_key_exists($keys[13], $arr)) $this->setSalt($arr[$keys[13]]);
        if (array_key_exists($keys[14], $arr)) $this->setPassword($arr[$keys[14]]);
        if (array_key_exists($keys[15], $arr)) $this->setLastLogin($arr[$keys[15]]);
        if (array_key_exists($keys[16], $arr)) $this->setLocked($arr[$keys[16]]);
        if (array_key_exists($keys[17], $arr)) $this->setExpired($arr[$keys[17]]);
        if (array_key_exists($keys[18], $arr)) $this->setExpiresAt($arr[$keys[18]]);
        if (array_key_exists($keys[19], $arr)) $this->setConfirmationToken($arr[$keys[19]]);
        if (array_key_exists($keys[20], $arr)) $this->setPasswordRequestedAt($arr[$keys[20]]);
        if (array_key_exists($keys[21], $arr)) $this->setCredentialsExpired($arr[$keys[21]]);
        if (array_key_exists($keys[22], $arr)) $this->setCredentialsExpireAt($arr[$keys[22]]);
        if (array_key_exists($keys[23], $arr)) $this->setRoles($arr[$keys[23]]);
        if (array_key_exists($keys[24], $arr)) $this->setLastActivity($arr[$keys[24]]);
        if (array_key_exists($keys[25], $arr)) $this->setFileName($arr[$keys[25]]);
        if (array_key_exists($keys[26], $arr)) $this->setBackFileName($arr[$keys[26]]);
        if (array_key_exists($keys[27], $arr)) $this->setCopyright($arr[$keys[27]]);
        if (array_key_exists($keys[28], $arr)) $this->setGender($arr[$keys[28]]);
        if (array_key_exists($keys[29], $arr)) $this->setFirstname($arr[$keys[29]]);
        if (array_key_exists($keys[30], $arr)) $this->setName($arr[$keys[30]]);
        if (array_key_exists($keys[31], $arr)) $this->setBirthday($arr[$keys[31]]);
        if (array_key_exists($keys[32], $arr)) $this->setSubtitle($arr[$keys[32]]);
        if (array_key_exists($keys[33], $arr)) $this->setBiography($arr[$keys[33]]);
        if (array_key_exists($keys[34], $arr)) $this->setWebsite($arr[$keys[34]]);
        if (array_key_exists($keys[35], $arr)) $this->setTwitter($arr[$keys[35]]);
        if (array_key_exists($keys[36], $arr)) $this->setFacebook($arr[$keys[36]]);
        if (array_key_exists($keys[37], $arr)) $this->setPhone($arr[$keys[37]]);
        if (array_key_exists($keys[38], $arr)) $this->setNewsletter($arr[$keys[38]]);
        if (array_key_exists($keys[39], $arr)) $this->setLastConnect($arr[$keys[39]]);
        if (array_key_exists($keys[40], $arr)) $this->setNbConnectedDays($arr[$keys[40]]);
        if (array_key_exists($keys[41], $arr)) $this->setIndexedAt($arr[$keys[41]]);
        if (array_key_exists($keys[42], $arr)) $this->setNbViews($arr[$keys[42]]);
        if (array_key_exists($keys[43], $arr)) $this->setQualified($arr[$keys[43]]);
        if (array_key_exists($keys[44], $arr)) $this->setValidated($arr[$keys[44]]);
        if (array_key_exists($keys[45], $arr)) $this->setNbIdCheck($arr[$keys[45]]);
        if (array_key_exists($keys[46], $arr)) $this->setOrganization($arr[$keys[46]]);
        if (array_key_exists($keys[47], $arr)) $this->setOnline($arr[$keys[47]]);
        if (array_key_exists($keys[48], $arr)) $this->setHomepage($arr[$keys[48]]);
        if (array_key_exists($keys[49], $arr)) $this->setSupportGroup($arr[$keys[49]]);
        if (array_key_exists($keys[50], $arr)) $this->setBanned($arr[$keys[50]]);
        if (array_key_exists($keys[51], $arr)) $this->setBannedNbDaysLeft($arr[$keys[51]]);
        if (array_key_exists($keys[52], $arr)) $this->setBannedNbTotal($arr[$keys[52]]);
        if (array_key_exists($keys[53], $arr)) $this->setAbuseLevel($arr[$keys[53]]);
        if (array_key_exists($keys[54], $arr)) $this->setCreatedAt($arr[$keys[54]]);
        if (array_key_exists($keys[55], $arr)) $this->setUpdatedAt($arr[$keys[55]]);
        if (array_key_exists($keys[56], $arr)) $this->setSlug($arr[$keys[56]]);
        if (array_key_exists($keys[57], $arr)) $this->setArchivedAt($arr[$keys[57]]);
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(PUserArchivePeer::DATABASE_NAME);

        if ($this->isColumnModified(PUserArchivePeer::ID)) $criteria->add(PUserArchivePeer::ID, $this->id);
        if ($this->isColumnModified(PUserArchivePeer::UUID)) $criteria->add(PUserArchivePeer::UUID, $this->uuid);
        if ($this->isColumnModified(PUserArchivePeer::P_U_STATUS_ID)) $criteria->add(PUserArchivePeer::P_U_STATUS_ID, $this->p_u_status_id);
        if ($this->isColumnModified(PUserArchivePeer::P_L_CITY_ID)) $criteria->add(PUserArchivePeer::P_L_CITY_ID, $this->p_l_city_id);
        if ($this->isColumnModified(PUserArchivePeer::PROVIDER)) $criteria->add(PUserArchivePeer::PROVIDER, $this->provider);
        if ($this->isColumnModified(PUserArchivePeer::PROVIDER_ID)) $criteria->add(PUserArchivePeer::PROVIDER_ID, $this->provider_id);
        if ($this->isColumnModified(PUserArchivePeer::NICKNAME)) $criteria->add(PUserArchivePeer::NICKNAME, $this->nickname);
        if ($this->isColumnModified(PUserArchivePeer::REALNAME)) $criteria->add(PUserArchivePeer::REALNAME, $this->realname);
        if ($this->isColumnModified(PUserArchivePeer::USERNAME)) $criteria->add(PUserArchivePeer::USERNAME, $this->username);
        if ($this->isColumnModified(PUserArchivePeer::USERNAME_CANONICAL)) $criteria->add(PUserArchivePeer::USERNAME_CANONICAL, $this->username_canonical);
        if ($this->isColumnModified(PUserArchivePeer::EMAIL)) $criteria->add(PUserArchivePeer::EMAIL, $this->email);
        if ($this->isColumnModified(PUserArchivePeer::EMAIL_CANONICAL)) $criteria->add(PUserArchivePeer::EMAIL_CANONICAL, $this->email_canonical);
        if ($this->isColumnModified(PUserArchivePeer::ENABLED)) $criteria->add(PUserArchivePeer::ENABLED, $this->enabled);
        if ($this->isColumnModified(PUserArchivePeer::SALT)) $criteria->add(PUserArchivePeer::SALT, $this->salt);
        if ($this->isColumnModified(PUserArchivePeer::PASSWORD)) $criteria->add(PUserArchivePeer::PASSWORD, $this->password);
        if ($this->isColumnModified(PUserArchivePeer::LAST_LOGIN)) $criteria->add(PUserArchivePeer::LAST_LOGIN, $this->last_login);
        if ($this->isColumnModified(PUserArchivePeer::LOCKED)) $criteria->add(PUserArchivePeer::LOCKED, $this->locked);
        if ($this->isColumnModified(PUserArchivePeer::EXPIRED)) $criteria->add(PUserArchivePeer::EXPIRED, $this->expired);
        if ($this->isColumnModified(PUserArchivePeer::EXPIRES_AT)) $criteria->add(PUserArchivePeer::EXPIRES_AT, $this->expires_at);
        if ($this->isColumnModified(PUserArchivePeer::CONFIRMATION_TOKEN)) $criteria->add(PUserArchivePeer::CONFIRMATION_TOKEN, $this->confirmation_token);
        if ($this->isColumnModified(PUserArchivePeer::PASSWORD_REQUESTED_AT)) $criteria->add(PUserArchivePeer::PASSWORD_REQUESTED_AT, $this->password_requested_at);
        if ($this->isColumnModified(PUserArchivePeer::CREDENTIALS_EXPIRED)) $criteria->add(PUserArchivePeer::CREDENTIALS_EXPIRED, $this->credentials_expired);
        if ($this->isColumnModified(PUserArchivePeer::CREDENTIALS_EXPIRE_AT)) $criteria->add(PUserArchivePeer::CREDENTIALS_EXPIRE_AT, $this->credentials_expire_at);
        if ($this->isColumnModified(PUserArchivePeer::ROLES)) $criteria->add(PUserArchivePeer::ROLES, $this->roles);
        if ($this->isColumnModified(PUserArchivePeer::LAST_ACTIVITY)) $criteria->add(PUserArchivePeer::LAST_ACTIVITY, $this->last_activity);
        if ($this->isColumnModified(PUserArchivePeer::FILE_NAME)) $criteria->add(PUserArchivePeer::FILE_NAME, $this->file_name);
        if ($this->isColumnModified(PUserArchivePeer::BACK_FILE_NAME)) $criteria->add(PUserArchivePeer::BACK_FILE_NAME, $this->back_file_name);
        if ($this->isColumnModified(PUserArchivePeer::COPYRIGHT)) $criteria->add(PUserArchivePeer::COPYRIGHT, $this->copyright);
        if ($this->isColumnModified(PUserArchivePeer::GENDER)) $criteria->add(PUserArchivePeer::GENDER, $this->gender);
        if ($this->isColumnModified(PUserArchivePeer::FIRSTNAME)) $criteria->add(PUserArchivePeer::FIRSTNAME, $this->firstname);
        if ($this->isColumnModified(PUserArchivePeer::NAME)) $criteria->add(PUserArchivePeer::NAME, $this->name);
        if ($this->isColumnModified(PUserArchivePeer::BIRTHDAY)) $criteria->add(PUserArchivePeer::BIRTHDAY, $this->birthday);
        if ($this->isColumnModified(PUserArchivePeer::SUBTITLE)) $criteria->add(PUserArchivePeer::SUBTITLE, $this->subtitle);
        if ($this->isColumnModified(PUserArchivePeer::BIOGRAPHY)) $criteria->add(PUserArchivePeer::BIOGRAPHY, $this->biography);
        if ($this->isColumnModified(PUserArchivePeer::WEBSITE)) $criteria->add(PUserArchivePeer::WEBSITE, $this->website);
        if ($this->isColumnModified(PUserArchivePeer::TWITTER)) $criteria->add(PUserArchivePeer::TWITTER, $this->twitter);
        if ($this->isColumnModified(PUserArchivePeer::FACEBOOK)) $criteria->add(PUserArchivePeer::FACEBOOK, $this->facebook);
        if ($this->isColumnModified(PUserArchivePeer::PHONE)) $criteria->add(PUserArchivePeer::PHONE, $this->phone);
        if ($this->isColumnModified(PUserArchivePeer::NEWSLETTER)) $criteria->add(PUserArchivePeer::NEWSLETTER, $this->newsletter);
        if ($this->isColumnModified(PUserArchivePeer::LAST_CONNECT)) $criteria->add(PUserArchivePeer::LAST_CONNECT, $this->last_connect);
        if ($this->isColumnModified(PUserArchivePeer::NB_CONNECTED_DAYS)) $criteria->add(PUserArchivePeer::NB_CONNECTED_DAYS, $this->nb_connected_days);
        if ($this->isColumnModified(PUserArchivePeer::INDEXED_AT)) $criteria->add(PUserArchivePeer::INDEXED_AT, $this->indexed_at);
        if ($this->isColumnModified(PUserArchivePeer::NB_VIEWS)) $criteria->add(PUserArchivePeer::NB_VIEWS, $this->nb_views);
        if ($this->isColumnModified(PUserArchivePeer::QUALIFIED)) $criteria->add(PUserArchivePeer::QUALIFIED, $this->qualified);
        if ($this->isColumnModified(PUserArchivePeer::VALIDATED)) $criteria->add(PUserArchivePeer::VALIDATED, $this->validated);
        if ($this->isColumnModified(PUserArchivePeer::NB_ID_CHECK)) $criteria->add(PUserArchivePeer::NB_ID_CHECK, $this->nb_id_check);
        if ($this->isColumnModified(PUserArchivePeer::ORGANIZATION)) $criteria->add(PUserArchivePeer::ORGANIZATION, $this->organization);
        if ($this->isColumnModified(PUserArchivePeer::ONLINE)) $criteria->add(PUserArchivePeer::ONLINE, $this->online);
        if ($this->isColumnModified(PUserArchivePeer::HOMEPAGE)) $criteria->add(PUserArchivePeer::HOMEPAGE, $this->homepage);
        if ($this->isColumnModified(PUserArchivePeer::SUPPORT_GROUP)) $criteria->add(PUserArchivePeer::SUPPORT_GROUP, $this->support_group);
        if ($this->isColumnModified(PUserArchivePeer::BANNED)) $criteria->add(PUserArchivePeer::BANNED, $this->banned);
        if ($this->isColumnModified(PUserArchivePeer::BANNED_NB_DAYS_LEFT)) $criteria->add(PUserArchivePeer::BANNED_NB_DAYS_LEFT, $this->banned_nb_days_left);
        if ($this->isColumnModified(PUserArchivePeer::BANNED_NB_TOTAL)) $criteria->add(PUserArchivePeer::BANNED_NB_TOTAL, $this->banned_nb_total);
        if ($this->isColumnModified(PUserArchivePeer::ABUSE_LEVEL)) $criteria->add(PUserArchivePeer::ABUSE_LEVEL, $this->abuse_level);
        if ($this->isColumnModified(PUserArchivePeer::CREATED_AT)) $criteria->add(PUserArchivePeer::CREATED_AT, $this->created_at);
        if ($this->isColumnModified(PUserArchivePeer::UPDATED_AT)) $criteria->add(PUserArchivePeer::UPDATED_AT, $this->updated_at);
        if ($this->isColumnModified(PUserArchivePeer::SLUG)) $criteria->add(PUserArchivePeer::SLUG, $this->slug);
        if ($this->isColumnModified(PUserArchivePeer::ARCHIVED_AT)) $criteria->add(PUserArchivePeer::ARCHIVED_AT, $this->archived_at);

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
        $criteria = new Criteria(PUserArchivePeer::DATABASE_NAME);
        $criteria->add(PUserArchivePeer::ID, $this->id);

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
     * @param object $copyObj An object of PUserArchive (or compatible) type.
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setUuid($this->getUuid());
        $copyObj->setPUStatusId($this->getPUStatusId());
        $copyObj->setPLCityId($this->getPLCityId());
        $copyObj->setProvider($this->getProvider());
        $copyObj->setProviderId($this->getProviderId());
        $copyObj->setNickname($this->getNickname());
        $copyObj->setRealname($this->getRealname());
        $copyObj->setUsername($this->getUsername());
        $copyObj->setUsernameCanonical($this->getUsernameCanonical());
        $copyObj->setEmail($this->getEmail());
        $copyObj->setEmailCanonical($this->getEmailCanonical());
        $copyObj->setEnabled($this->getEnabled());
        $copyObj->setSalt($this->getSalt());
        $copyObj->setPassword($this->getPassword());
        $copyObj->setLastLogin($this->getLastLogin());
        $copyObj->setLocked($this->getLocked());
        $copyObj->setExpired($this->getExpired());
        $copyObj->setExpiresAt($this->getExpiresAt());
        $copyObj->setConfirmationToken($this->getConfirmationToken());
        $copyObj->setPasswordRequestedAt($this->getPasswordRequestedAt());
        $copyObj->setCredentialsExpired($this->getCredentialsExpired());
        $copyObj->setCredentialsExpireAt($this->getCredentialsExpireAt());
        $copyObj->setRoles($this->getRoles());
        $copyObj->setLastActivity($this->getLastActivity());
        $copyObj->setFileName($this->getFileName());
        $copyObj->setBackFileName($this->getBackFileName());
        $copyObj->setCopyright($this->getCopyright());
        $copyObj->setGender($this->getGender());
        $copyObj->setFirstname($this->getFirstname());
        $copyObj->setName($this->getName());
        $copyObj->setBirthday($this->getBirthday());
        $copyObj->setSubtitle($this->getSubtitle());
        $copyObj->setBiography($this->getBiography());
        $copyObj->setWebsite($this->getWebsite());
        $copyObj->setTwitter($this->getTwitter());
        $copyObj->setFacebook($this->getFacebook());
        $copyObj->setPhone($this->getPhone());
        $copyObj->setNewsletter($this->getNewsletter());
        $copyObj->setLastConnect($this->getLastConnect());
        $copyObj->setNbConnectedDays($this->getNbConnectedDays());
        $copyObj->setIndexedAt($this->getIndexedAt());
        $copyObj->setNbViews($this->getNbViews());
        $copyObj->setQualified($this->getQualified());
        $copyObj->setValidated($this->getValidated());
        $copyObj->setNbIdCheck($this->getNbIdCheck());
        $copyObj->setOrganization($this->getOrganization());
        $copyObj->setOnline($this->getOnline());
        $copyObj->setHomepage($this->getHomepage());
        $copyObj->setSupportGroup($this->getSupportGroup());
        $copyObj->setBanned($this->getBanned());
        $copyObj->setBannedNbDaysLeft($this->getBannedNbDaysLeft());
        $copyObj->setBannedNbTotal($this->getBannedNbTotal());
        $copyObj->setAbuseLevel($this->getAbuseLevel());
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
     * @return PUserArchive Clone of current object.
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
     * @return PUserArchivePeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new PUserArchivePeer();
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
        $this->p_u_status_id = null;
        $this->p_l_city_id = null;
        $this->provider = null;
        $this->provider_id = null;
        $this->nickname = null;
        $this->realname = null;
        $this->username = null;
        $this->username_canonical = null;
        $this->email = null;
        $this->email_canonical = null;
        $this->enabled = null;
        $this->salt = null;
        $this->password = null;
        $this->last_login = null;
        $this->locked = null;
        $this->expired = null;
        $this->expires_at = null;
        $this->confirmation_token = null;
        $this->password_requested_at = null;
        $this->credentials_expired = null;
        $this->credentials_expire_at = null;
        $this->roles = null;
        $this->roles_unserialized = null;
        $this->last_activity = null;
        $this->file_name = null;
        $this->back_file_name = null;
        $this->copyright = null;
        $this->gender = null;
        $this->firstname = null;
        $this->name = null;
        $this->birthday = null;
        $this->subtitle = null;
        $this->biography = null;
        $this->website = null;
        $this->twitter = null;
        $this->facebook = null;
        $this->phone = null;
        $this->newsletter = null;
        $this->last_connect = null;
        $this->nb_connected_days = null;
        $this->indexed_at = null;
        $this->nb_views = null;
        $this->qualified = null;
        $this->validated = null;
        $this->nb_id_check = null;
        $this->organization = null;
        $this->online = null;
        $this->homepage = null;
        $this->support_group = null;
        $this->banned = null;
        $this->banned_nb_days_left = null;
        $this->banned_nb_total = null;
        $this->abuse_level = null;
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
        return (string) $this->exportTo(PUserArchivePeer::DEFAULT_STRING_FORMAT);
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
