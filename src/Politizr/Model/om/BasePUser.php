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
use Politizr\Model\PDDebate;
use Politizr\Model\PDDebateQuery;
use Politizr\Model\PDRComment;
use Politizr\Model\PDRCommentQuery;
use Politizr\Model\PDReaction;
use Politizr\Model\PDReactionQuery;
use Politizr\Model\POrder;
use Politizr\Model\POrderQuery;
use Politizr\Model\PRAction;
use Politizr\Model\PRActionQuery;
use Politizr\Model\PRBadge;
use Politizr\Model\PRBadgeQuery;
use Politizr\Model\PTag;
use Politizr\Model\PTagQuery;
use Politizr\Model\PUFollowDD;
use Politizr\Model\PUFollowDDQuery;
use Politizr\Model\PUFollowU;
use Politizr\Model\PUFollowUPeer;
use Politizr\Model\PUFollowUQuery;
use Politizr\Model\PUQualification;
use Politizr\Model\PUQualificationQuery;
use Politizr\Model\PUReputationRA;
use Politizr\Model\PUReputationRAQuery;
use Politizr\Model\PUReputationRB;
use Politizr\Model\PUReputationRBQuery;
use Politizr\Model\PUTaggedT;
use Politizr\Model\PUTaggedTQuery;
use Politizr\Model\PUser;
use Politizr\Model\PUserPeer;
use Politizr\Model\PUserQuery;

abstract class BasePUser extends BaseObject implements Persistent
{
    /**
     * Peer class name
     */
    const PEER = 'Politizr\\Model\\PUserPeer';

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        PUserPeer
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
     * The value for the type field.
     * @var        int
     */
    protected $type;

    /**
     * The value for the status field.
     * @var        int
     */
    protected $status;

    /**
     * The value for the file_name field.
     * @var        string
     */
    protected $file_name;

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
     * The value for the summary field.
     * @var        string
     */
    protected $summary;

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
     * The value for the online field.
     * @var        boolean
     */
    protected $online;

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
     * @var        PropelObjectCollection|POrder[] Collection to store aggregation of POrder objects.
     */
    protected $collPOrders;
    protected $collPOrdersPartial;

    /**
     * @var        PropelObjectCollection|PUQualification[] Collection to store aggregation of PUQualification objects.
     */
    protected $collPUQualifications;
    protected $collPUQualificationsPartial;

    /**
     * @var        PropelObjectCollection|PUFollowDD[] Collection to store aggregation of PUFollowDD objects.
     */
    protected $collPuFollowDdPUsers;
    protected $collPuFollowDdPUsersPartial;

    /**
     * @var        PropelObjectCollection|PUReputationRB[] Collection to store aggregation of PUReputationRB objects.
     */
    protected $collPuReputationRbPUsers;
    protected $collPuReputationRbPUsersPartial;

    /**
     * @var        PropelObjectCollection|PUReputationRA[] Collection to store aggregation of PUReputationRA objects.
     */
    protected $collPuReputationRaPUsers;
    protected $collPuReputationRaPUsersPartial;

    /**
     * @var        PropelObjectCollection|PUTaggedT[] Collection to store aggregation of PUTaggedT objects.
     */
    protected $collPuTaggedTPUsers;
    protected $collPuTaggedTPUsersPartial;

    /**
     * @var        PropelObjectCollection|PDDebate[] Collection to store aggregation of PDDebate objects.
     */
    protected $collPDDebates;
    protected $collPDDebatesPartial;

    /**
     * @var        PropelObjectCollection|PDDComment[] Collection to store aggregation of PDDComment objects.
     */
    protected $collPDDComments;
    protected $collPDDCommentsPartial;

    /**
     * @var        PropelObjectCollection|PDReaction[] Collection to store aggregation of PDReaction objects.
     */
    protected $collPDReactions;
    protected $collPDReactionsPartial;

    /**
     * @var        PropelObjectCollection|PDRComment[] Collection to store aggregation of PDRComment objects.
     */
    protected $collPDRComments;
    protected $collPDRCommentsPartial;

    /**
     * @var        PropelObjectCollection|PUFollowU[] Collection to store aggregation of PUFollowU objects.
     */
    protected $collPUFollowUsRelatedByPUserId;
    protected $collPUFollowUsRelatedByPUserIdPartial;

    /**
     * @var        PropelObjectCollection|PUFollowU[] Collection to store aggregation of PUFollowU objects.
     */
    protected $collPUFollowUsRelatedByPUserFollowerId;
    protected $collPUFollowUsRelatedByPUserFollowerIdPartial;

    /**
     * @var        PropelObjectCollection|PDDebate[] Collection to store aggregation of PDDebate objects.
     */
    protected $collPuFollowDdPDDebates;

    /**
     * @var        PropelObjectCollection|PRBadge[] Collection to store aggregation of PRBadge objects.
     */
    protected $collPuReputationRbPRBadges;

    /**
     * @var        PropelObjectCollection|PRAction[] Collection to store aggregation of PRAction objects.
     */
    protected $collPuReputationRaPRBadges;

    /**
     * @var        PropelObjectCollection|PTag[] Collection to store aggregation of PTag objects.
     */
    protected $collPuTaggedTPTags;

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

    // equal_nest_parent behavior

    /**
     * @var array List of PKs of PUFollowU for this PUser */
    protected $listEqualNestPUFollowUsPKs;

    /**
     * @var PropelObjectCollection PUser[] Collection to store Equal Nest PUFollowU of this PUser */
    protected $collEqualNestPUFollowUs;

    /**
     * @var boolean Flag to prevent endless processing loop which occurs when 2 new objects are set as twins
     */
    protected $alreadyInEqualNestProcessing = false;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $puFollowDdPDDebatesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $puReputationRbPRBadgesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $puReputationRaPRBadgesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $puTaggedTPTagsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $pOrdersScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $pUQualificationsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $puFollowDdPUsersScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $puReputationRbPUsersScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $puReputationRaPUsersScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $puTaggedTPUsersScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $pDDebatesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $pDDCommentsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $pDReactionsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $pDRCommentsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $pUFollowUsRelatedByPUserIdScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $pUFollowUsRelatedByPUserFollowerIdScheduledForDeletion = null;

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
    }

    /**
     * Initializes internal state of BasePUser object.
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
     * Get the [type] column value.
     *
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Get the [status] column value.
     *
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
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
        $valueSet = PUserPeer::getValueSet(PUserPeer::GENDER);
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
     * Get the [summary] column value.
     *
     * @return string
     */
    public function getSummary()
    {
        return $this->summary;
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
     * Get the [online] column value.
     *
     * @return boolean
     */
    public function getOnline()
    {
        return $this->online;
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
     * @param int $v new value
     * @return PUser The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[] = PUserPeer::ID;
        }


        return $this;
    } // setId()

    /**
     * Set the value of [provider] column.
     *
     * @param string $v new value
     * @return PUser The current object (for fluent API support)
     */
    public function setProvider($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (string) $v;
        }

        if ($this->provider !== $v) {
            $this->provider = $v;
            $this->modifiedColumns[] = PUserPeer::PROVIDER;
        }


        return $this;
    } // setProvider()

    /**
     * Set the value of [provider_id] column.
     *
     * @param string $v new value
     * @return PUser The current object (for fluent API support)
     */
    public function setProviderId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (string) $v;
        }

        if ($this->provider_id !== $v) {
            $this->provider_id = $v;
            $this->modifiedColumns[] = PUserPeer::PROVIDER_ID;
        }


        return $this;
    } // setProviderId()

    /**
     * Set the value of [nickname] column.
     *
     * @param string $v new value
     * @return PUser The current object (for fluent API support)
     */
    public function setNickname($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (string) $v;
        }

        if ($this->nickname !== $v) {
            $this->nickname = $v;
            $this->modifiedColumns[] = PUserPeer::NICKNAME;
        }


        return $this;
    } // setNickname()

    /**
     * Set the value of [realname] column.
     *
     * @param string $v new value
     * @return PUser The current object (for fluent API support)
     */
    public function setRealname($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (string) $v;
        }

        if ($this->realname !== $v) {
            $this->realname = $v;
            $this->modifiedColumns[] = PUserPeer::REALNAME;
        }


        return $this;
    } // setRealname()

    /**
     * Set the value of [username] column.
     *
     * @param string $v new value
     * @return PUser The current object (for fluent API support)
     */
    public function setUsername($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (string) $v;
        }

        if ($this->username !== $v) {
            $this->username = $v;
            $this->modifiedColumns[] = PUserPeer::USERNAME;
        }


        return $this;
    } // setUsername()

    /**
     * Set the value of [username_canonical] column.
     *
     * @param string $v new value
     * @return PUser The current object (for fluent API support)
     */
    public function setUsernameCanonical($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (string) $v;
        }

        if ($this->username_canonical !== $v) {
            $this->username_canonical = $v;
            $this->modifiedColumns[] = PUserPeer::USERNAME_CANONICAL;
        }


        return $this;
    } // setUsernameCanonical()

    /**
     * Set the value of [email] column.
     *
     * @param string $v new value
     * @return PUser The current object (for fluent API support)
     */
    public function setEmail($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (string) $v;
        }

        if ($this->email !== $v) {
            $this->email = $v;
            $this->modifiedColumns[] = PUserPeer::EMAIL;
        }


        return $this;
    } // setEmail()

    /**
     * Set the value of [email_canonical] column.
     *
     * @param string $v new value
     * @return PUser The current object (for fluent API support)
     */
    public function setEmailCanonical($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (string) $v;
        }

        if ($this->email_canonical !== $v) {
            $this->email_canonical = $v;
            $this->modifiedColumns[] = PUserPeer::EMAIL_CANONICAL;
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
     * @return PUser The current object (for fluent API support)
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
            $this->modifiedColumns[] = PUserPeer::ENABLED;
        }


        return $this;
    } // setEnabled()

    /**
     * Set the value of [salt] column.
     *
     * @param string $v new value
     * @return PUser The current object (for fluent API support)
     */
    public function setSalt($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (string) $v;
        }

        if ($this->salt !== $v) {
            $this->salt = $v;
            $this->modifiedColumns[] = PUserPeer::SALT;
        }


        return $this;
    } // setSalt()

    /**
     * Set the value of [password] column.
     *
     * @param string $v new value
     * @return PUser The current object (for fluent API support)
     */
    public function setPassword($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (string) $v;
        }

        if ($this->password !== $v) {
            $this->password = $v;
            $this->modifiedColumns[] = PUserPeer::PASSWORD;
        }


        return $this;
    } // setPassword()

    /**
     * Sets the value of [last_login] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return PUser The current object (for fluent API support)
     */
    public function setLastLogin($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->last_login !== null || $dt !== null) {
            $currentDateAsString = ($this->last_login !== null && $tmpDt = new DateTime($this->last_login)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->last_login = $newDateAsString;
                $this->modifiedColumns[] = PUserPeer::LAST_LOGIN;
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
     * @return PUser The current object (for fluent API support)
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
            $this->modifiedColumns[] = PUserPeer::LOCKED;
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
     * @return PUser The current object (for fluent API support)
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
            $this->modifiedColumns[] = PUserPeer::EXPIRED;
        }


        return $this;
    } // setExpired()

    /**
     * Sets the value of [expires_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return PUser The current object (for fluent API support)
     */
    public function setExpiresAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->expires_at !== null || $dt !== null) {
            $currentDateAsString = ($this->expires_at !== null && $tmpDt = new DateTime($this->expires_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->expires_at = $newDateAsString;
                $this->modifiedColumns[] = PUserPeer::EXPIRES_AT;
            }
        } // if either are not null


        return $this;
    } // setExpiresAt()

    /**
     * Set the value of [confirmation_token] column.
     *
     * @param string $v new value
     * @return PUser The current object (for fluent API support)
     */
    public function setConfirmationToken($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (string) $v;
        }

        if ($this->confirmation_token !== $v) {
            $this->confirmation_token = $v;
            $this->modifiedColumns[] = PUserPeer::CONFIRMATION_TOKEN;
        }


        return $this;
    } // setConfirmationToken()

    /**
     * Sets the value of [password_requested_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return PUser The current object (for fluent API support)
     */
    public function setPasswordRequestedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->password_requested_at !== null || $dt !== null) {
            $currentDateAsString = ($this->password_requested_at !== null && $tmpDt = new DateTime($this->password_requested_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->password_requested_at = $newDateAsString;
                $this->modifiedColumns[] = PUserPeer::PASSWORD_REQUESTED_AT;
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
     * @return PUser The current object (for fluent API support)
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
            $this->modifiedColumns[] = PUserPeer::CREDENTIALS_EXPIRED;
        }


        return $this;
    } // setCredentialsExpired()

    /**
     * Sets the value of [credentials_expire_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return PUser The current object (for fluent API support)
     */
    public function setCredentialsExpireAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->credentials_expire_at !== null || $dt !== null) {
            $currentDateAsString = ($this->credentials_expire_at !== null && $tmpDt = new DateTime($this->credentials_expire_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->credentials_expire_at = $newDateAsString;
                $this->modifiedColumns[] = PUserPeer::CREDENTIALS_EXPIRE_AT;
            }
        } // if either are not null


        return $this;
    } // setCredentialsExpireAt()

    /**
     * Set the value of [roles] column.
     *
     * @param array $v new value
     * @return PUser The current object (for fluent API support)
     */
    public function setRoles($v)
    {
        if ($this->roles_unserialized !== $v) {
            $this->roles_unserialized = $v;
            $this->roles = '| ' . implode(' | ', $v) . ' |';
            $this->modifiedColumns[] = PUserPeer::ROLES;
        }


        return $this;
    } // setRoles()

    /**
     * Adds a value to the [roles] array column value.
     * @param mixed $value
     *
     * @return PUser The current object (for fluent API support)
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
     * @return PUser The current object (for fluent API support)
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
     * Set the value of [type] column.
     *
     * @param int $v new value
     * @return PUser The current object (for fluent API support)
     */
    public function setType($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->type !== $v) {
            $this->type = $v;
            $this->modifiedColumns[] = PUserPeer::TYPE;
        }


        return $this;
    } // setType()

    /**
     * Set the value of [status] column.
     *
     * @param int $v new value
     * @return PUser The current object (for fluent API support)
     */
    public function setStatus($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->status !== $v) {
            $this->status = $v;
            $this->modifiedColumns[] = PUserPeer::STATUS;
        }


        return $this;
    } // setStatus()

    /**
     * Set the value of [file_name] column.
     *
     * @param string $v new value
     * @return PUser The current object (for fluent API support)
     */
    public function setFileName($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (string) $v;
        }

        if ($this->file_name !== $v) {
            $this->file_name = $v;
            $this->modifiedColumns[] = PUserPeer::FILE_NAME;
        }


        return $this;
    } // setFileName()

    /**
     * Set the value of [gender] column.
     *
     * @param int $v new value
     * @return PUser The current object (for fluent API support)
     * @throws PropelException - if the value is not accepted by this enum.
     */
    public function setGender($v)
    {
        if ($v !== null) {
            $valueSet = PUserPeer::getValueSet(PUserPeer::GENDER);
            if (!in_array($v, $valueSet)) {
                throw new PropelException(sprintf('Value "%s" is not accepted in this enumerated column', $v));
            }
            $v = array_search($v, $valueSet);
        }

        if ($this->gender !== $v) {
            $this->gender = $v;
            $this->modifiedColumns[] = PUserPeer::GENDER;
        }


        return $this;
    } // setGender()

    /**
     * Set the value of [firstname] column.
     *
     * @param string $v new value
     * @return PUser The current object (for fluent API support)
     */
    public function setFirstname($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (string) $v;
        }

        if ($this->firstname !== $v) {
            $this->firstname = $v;
            $this->modifiedColumns[] = PUserPeer::FIRSTNAME;
        }


        return $this;
    } // setFirstname()

    /**
     * Set the value of [name] column.
     *
     * @param string $v new value
     * @return PUser The current object (for fluent API support)
     */
    public function setName($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (string) $v;
        }

        if ($this->name !== $v) {
            $this->name = $v;
            $this->modifiedColumns[] = PUserPeer::NAME;
        }


        return $this;
    } // setName()

    /**
     * Sets the value of [birthday] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return PUser The current object (for fluent API support)
     */
    public function setBirthday($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->birthday !== null || $dt !== null) {
            $currentDateAsString = ($this->birthday !== null && $tmpDt = new DateTime($this->birthday)) ? $tmpDt->format('Y-m-d') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->birthday = $newDateAsString;
                $this->modifiedColumns[] = PUserPeer::BIRTHDAY;
            }
        } // if either are not null


        return $this;
    } // setBirthday()

    /**
     * Set the value of [summary] column.
     *
     * @param string $v new value
     * @return PUser The current object (for fluent API support)
     */
    public function setSummary($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (string) $v;
        }

        if ($this->summary !== $v) {
            $this->summary = $v;
            $this->modifiedColumns[] = PUserPeer::SUMMARY;
        }


        return $this;
    } // setSummary()

    /**
     * Set the value of [biography] column.
     *
     * @param string $v new value
     * @return PUser The current object (for fluent API support)
     */
    public function setBiography($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (string) $v;
        }

        if ($this->biography !== $v) {
            $this->biography = $v;
            $this->modifiedColumns[] = PUserPeer::BIOGRAPHY;
        }


        return $this;
    } // setBiography()

    /**
     * Set the value of [website] column.
     *
     * @param string $v new value
     * @return PUser The current object (for fluent API support)
     */
    public function setWebsite($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (string) $v;
        }

        if ($this->website !== $v) {
            $this->website = $v;
            $this->modifiedColumns[] = PUserPeer::WEBSITE;
        }


        return $this;
    } // setWebsite()

    /**
     * Set the value of [twitter] column.
     *
     * @param string $v new value
     * @return PUser The current object (for fluent API support)
     */
    public function setTwitter($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (string) $v;
        }

        if ($this->twitter !== $v) {
            $this->twitter = $v;
            $this->modifiedColumns[] = PUserPeer::TWITTER;
        }


        return $this;
    } // setTwitter()

    /**
     * Set the value of [facebook] column.
     *
     * @param string $v new value
     * @return PUser The current object (for fluent API support)
     */
    public function setFacebook($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (string) $v;
        }

        if ($this->facebook !== $v) {
            $this->facebook = $v;
            $this->modifiedColumns[] = PUserPeer::FACEBOOK;
        }


        return $this;
    } // setFacebook()

    /**
     * Set the value of [phone] column.
     *
     * @param string $v new value
     * @return PUser The current object (for fluent API support)
     */
    public function setPhone($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (string) $v;
        }

        if ($this->phone !== $v) {
            $this->phone = $v;
            $this->modifiedColumns[] = PUserPeer::PHONE;
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
     * @return PUser The current object (for fluent API support)
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
            $this->modifiedColumns[] = PUserPeer::NEWSLETTER;
        }


        return $this;
    } // setNewsletter()

    /**
     * Sets the value of [last_connect] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return PUser The current object (for fluent API support)
     */
    public function setLastConnect($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->last_connect !== null || $dt !== null) {
            $currentDateAsString = ($this->last_connect !== null && $tmpDt = new DateTime($this->last_connect)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->last_connect = $newDateAsString;
                $this->modifiedColumns[] = PUserPeer::LAST_CONNECT;
            }
        } // if either are not null


        return $this;
    } // setLastConnect()

    /**
     * Sets the value of the [online] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param boolean|integer|string $v The new value
     * @return PUser The current object (for fluent API support)
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
            $this->modifiedColumns[] = PUserPeer::ONLINE;
        }


        return $this;
    } // setOnline()

    /**
     * Sets the value of [created_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return PUser The current object (for fluent API support)
     */
    public function setCreatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->created_at !== null || $dt !== null) {
            $currentDateAsString = ($this->created_at !== null && $tmpDt = new DateTime($this->created_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->created_at = $newDateAsString;
                $this->modifiedColumns[] = PUserPeer::CREATED_AT;
            }
        } // if either are not null


        return $this;
    } // setCreatedAt()

    /**
     * Sets the value of [updated_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return PUser The current object (for fluent API support)
     */
    public function setUpdatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->updated_at !== null || $dt !== null) {
            $currentDateAsString = ($this->updated_at !== null && $tmpDt = new DateTime($this->updated_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->updated_at = $newDateAsString;
                $this->modifiedColumns[] = PUserPeer::UPDATED_AT;
            }
        } // if either are not null


        return $this;
    } // setUpdatedAt()

    /**
     * Set the value of [slug] column.
     *
     * @param string $v new value
     * @return PUser The current object (for fluent API support)
     */
    public function setSlug($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (string) $v;
        }

        if ($this->slug !== $v) {
            $this->slug = $v;
            $this->modifiedColumns[] = PUserPeer::SLUG;
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
            $this->provider = ($row[$startcol + 1] !== null) ? (string) $row[$startcol + 1] : null;
            $this->provider_id = ($row[$startcol + 2] !== null) ? (string) $row[$startcol + 2] : null;
            $this->nickname = ($row[$startcol + 3] !== null) ? (string) $row[$startcol + 3] : null;
            $this->realname = ($row[$startcol + 4] !== null) ? (string) $row[$startcol + 4] : null;
            $this->username = ($row[$startcol + 5] !== null) ? (string) $row[$startcol + 5] : null;
            $this->username_canonical = ($row[$startcol + 6] !== null) ? (string) $row[$startcol + 6] : null;
            $this->email = ($row[$startcol + 7] !== null) ? (string) $row[$startcol + 7] : null;
            $this->email_canonical = ($row[$startcol + 8] !== null) ? (string) $row[$startcol + 8] : null;
            $this->enabled = ($row[$startcol + 9] !== null) ? (boolean) $row[$startcol + 9] : null;
            $this->salt = ($row[$startcol + 10] !== null) ? (string) $row[$startcol + 10] : null;
            $this->password = ($row[$startcol + 11] !== null) ? (string) $row[$startcol + 11] : null;
            $this->last_login = ($row[$startcol + 12] !== null) ? (string) $row[$startcol + 12] : null;
            $this->locked = ($row[$startcol + 13] !== null) ? (boolean) $row[$startcol + 13] : null;
            $this->expired = ($row[$startcol + 14] !== null) ? (boolean) $row[$startcol + 14] : null;
            $this->expires_at = ($row[$startcol + 15] !== null) ? (string) $row[$startcol + 15] : null;
            $this->confirmation_token = ($row[$startcol + 16] !== null) ? (string) $row[$startcol + 16] : null;
            $this->password_requested_at = ($row[$startcol + 17] !== null) ? (string) $row[$startcol + 17] : null;
            $this->credentials_expired = ($row[$startcol + 18] !== null) ? (boolean) $row[$startcol + 18] : null;
            $this->credentials_expire_at = ($row[$startcol + 19] !== null) ? (string) $row[$startcol + 19] : null;
            $this->roles = $row[$startcol + 20];
            $this->roles_unserialized = null;
            $this->type = ($row[$startcol + 21] !== null) ? (int) $row[$startcol + 21] : null;
            $this->status = ($row[$startcol + 22] !== null) ? (int) $row[$startcol + 22] : null;
            $this->file_name = ($row[$startcol + 23] !== null) ? (string) $row[$startcol + 23] : null;
            $this->gender = ($row[$startcol + 24] !== null) ? (int) $row[$startcol + 24] : null;
            $this->firstname = ($row[$startcol + 25] !== null) ? (string) $row[$startcol + 25] : null;
            $this->name = ($row[$startcol + 26] !== null) ? (string) $row[$startcol + 26] : null;
            $this->birthday = ($row[$startcol + 27] !== null) ? (string) $row[$startcol + 27] : null;
            $this->summary = ($row[$startcol + 28] !== null) ? (string) $row[$startcol + 28] : null;
            $this->biography = ($row[$startcol + 29] !== null) ? (string) $row[$startcol + 29] : null;
            $this->website = ($row[$startcol + 30] !== null) ? (string) $row[$startcol + 30] : null;
            $this->twitter = ($row[$startcol + 31] !== null) ? (string) $row[$startcol + 31] : null;
            $this->facebook = ($row[$startcol + 32] !== null) ? (string) $row[$startcol + 32] : null;
            $this->phone = ($row[$startcol + 33] !== null) ? (string) $row[$startcol + 33] : null;
            $this->newsletter = ($row[$startcol + 34] !== null) ? (boolean) $row[$startcol + 34] : null;
            $this->last_connect = ($row[$startcol + 35] !== null) ? (string) $row[$startcol + 35] : null;
            $this->online = ($row[$startcol + 36] !== null) ? (boolean) $row[$startcol + 36] : null;
            $this->created_at = ($row[$startcol + 37] !== null) ? (string) $row[$startcol + 37] : null;
            $this->updated_at = ($row[$startcol + 38] !== null) ? (string) $row[$startcol + 38] : null;
            $this->slug = ($row[$startcol + 39] !== null) ? (string) $row[$startcol + 39] : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }
            $this->postHydrate($row, $startcol, $rehydrate);
            return $startcol + 40; // 40 = PUserPeer::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating PUser object", $e);
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
            $con = Propel::getConnection(PUserPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $stmt = PUserPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
        $row = $stmt->fetch(PDO::FETCH_NUM);
        $stmt->closeCursor();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->collPOrders = null;

            $this->collPUQualifications = null;

            $this->collPuFollowDdPUsers = null;

            $this->collPuReputationRbPUsers = null;

            $this->collPuReputationRaPUsers = null;

            $this->collPuTaggedTPUsers = null;

            $this->collPDDebates = null;

            $this->collPDDComments = null;

            $this->collPDReactions = null;

            $this->collPDRComments = null;

            $this->collPUFollowUsRelatedByPUserId = null;

            $this->collPUFollowUsRelatedByPUserFollowerId = null;

            $this->collPuFollowDdPDDebates = null;
            $this->collPuReputationRbPRBadges = null;
            $this->collPuReputationRaPRBadges = null;
            $this->collPuTaggedTPTags = null;
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
            $con = Propel::getConnection(PUserPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        try {
            $deleteQuery = PUserQuery::create()
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
            $con = Propel::getConnection(PUserPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        $isInsert = $this->isNew();
        try {
            $ret = $this->preSave($con);
            // sluggable behavior

            if ($this->isColumnModified(PUserPeer::SLUG) && $this->getSlug()) {
                $this->setSlug($this->makeSlugUnique($this->getSlug()));
            } elseif ($this->isColumnModified(PUserPeer::FIRSTNAME) || $this->isColumnModified(PUserPeer::NAME)) {
                $this->setSlug($this->createSlug());
            } elseif (!$this->getSlug()) {
                $this->setSlug($this->createSlug());
            }
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
                // timestampable behavior
                if (!$this->isColumnModified(PUserPeer::CREATED_AT)) {
                    $this->setCreatedAt(time());
                }
                if (!$this->isColumnModified(PUserPeer::UPDATED_AT)) {
                    $this->setUpdatedAt(time());
                }
            } else {
                $ret = $ret && $this->preUpdate($con);
                // timestampable behavior
                if ($this->isModified() && !$this->isColumnModified(PUserPeer::UPDATED_AT)) {
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
                // equal_nest_parent behavior
                $this->processEqualNestQueries($con);

                PUserPeer::addInstanceToPool($this);
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

            if ($this->puFollowDdPDDebatesScheduledForDeletion !== null) {
                if (!$this->puFollowDdPDDebatesScheduledForDeletion->isEmpty()) {
                    $pks = array();
                    $pk = $this->getPrimaryKey();
                    foreach ($this->puFollowDdPDDebatesScheduledForDeletion->getPrimaryKeys(false) as $remotePk) {
                        $pks[] = array($pk, $remotePk);
                    }
                    PuFollowDdPUserQuery::create()
                        ->filterByPrimaryKeys($pks)
                        ->delete($con);
                    $this->puFollowDdPDDebatesScheduledForDeletion = null;
                }

                foreach ($this->getPuFollowDdPDDebates() as $puFollowDdPDDebate) {
                    if ($puFollowDdPDDebate->isModified()) {
                        $puFollowDdPDDebate->save($con);
                    }
                }
            } elseif ($this->collPuFollowDdPDDebates) {
                foreach ($this->collPuFollowDdPDDebates as $puFollowDdPDDebate) {
                    if ($puFollowDdPDDebate->isModified()) {
                        $puFollowDdPDDebate->save($con);
                    }
                }
            }

            if ($this->puReputationRbPRBadgesScheduledForDeletion !== null) {
                if (!$this->puReputationRbPRBadgesScheduledForDeletion->isEmpty()) {
                    $pks = array();
                    $pk = $this->getPrimaryKey();
                    foreach ($this->puReputationRbPRBadgesScheduledForDeletion->getPrimaryKeys(false) as $remotePk) {
                        $pks[] = array($pk, $remotePk);
                    }
                    PuReputationRbPUserQuery::create()
                        ->filterByPrimaryKeys($pks)
                        ->delete($con);
                    $this->puReputationRbPRBadgesScheduledForDeletion = null;
                }

                foreach ($this->getPuReputationRbPRBadges() as $puReputationRbPRBadge) {
                    if ($puReputationRbPRBadge->isModified()) {
                        $puReputationRbPRBadge->save($con);
                    }
                }
            } elseif ($this->collPuReputationRbPRBadges) {
                foreach ($this->collPuReputationRbPRBadges as $puReputationRbPRBadge) {
                    if ($puReputationRbPRBadge->isModified()) {
                        $puReputationRbPRBadge->save($con);
                    }
                }
            }

            if ($this->puReputationRaPRBadgesScheduledForDeletion !== null) {
                if (!$this->puReputationRaPRBadgesScheduledForDeletion->isEmpty()) {
                    $pks = array();
                    $pk = $this->getPrimaryKey();
                    foreach ($this->puReputationRaPRBadgesScheduledForDeletion->getPrimaryKeys(false) as $remotePk) {
                        $pks[] = array($pk, $remotePk);
                    }
                    PuReputationRaPUserQuery::create()
                        ->filterByPrimaryKeys($pks)
                        ->delete($con);
                    $this->puReputationRaPRBadgesScheduledForDeletion = null;
                }

                foreach ($this->getPuReputationRaPRBadges() as $puReputationRaPRBadge) {
                    if ($puReputationRaPRBadge->isModified()) {
                        $puReputationRaPRBadge->save($con);
                    }
                }
            } elseif ($this->collPuReputationRaPRBadges) {
                foreach ($this->collPuReputationRaPRBadges as $puReputationRaPRBadge) {
                    if ($puReputationRaPRBadge->isModified()) {
                        $puReputationRaPRBadge->save($con);
                    }
                }
            }

            if ($this->puTaggedTPTagsScheduledForDeletion !== null) {
                if (!$this->puTaggedTPTagsScheduledForDeletion->isEmpty()) {
                    $pks = array();
                    $pk = $this->getPrimaryKey();
                    foreach ($this->puTaggedTPTagsScheduledForDeletion->getPrimaryKeys(false) as $remotePk) {
                        $pks[] = array($pk, $remotePk);
                    }
                    PuTaggedTPUserQuery::create()
                        ->filterByPrimaryKeys($pks)
                        ->delete($con);
                    $this->puTaggedTPTagsScheduledForDeletion = null;
                }

                foreach ($this->getPuTaggedTPTags() as $puTaggedTPTag) {
                    if ($puTaggedTPTag->isModified()) {
                        $puTaggedTPTag->save($con);
                    }
                }
            } elseif ($this->collPuTaggedTPTags) {
                foreach ($this->collPuTaggedTPTags as $puTaggedTPTag) {
                    if ($puTaggedTPTag->isModified()) {
                        $puTaggedTPTag->save($con);
                    }
                }
            }

            if ($this->pOrdersScheduledForDeletion !== null) {
                if (!$this->pOrdersScheduledForDeletion->isEmpty()) {
                    foreach ($this->pOrdersScheduledForDeletion as $pOrder) {
                        // need to save related object because we set the relation to null
                        $pOrder->save($con);
                    }
                    $this->pOrdersScheduledForDeletion = null;
                }
            }

            if ($this->collPOrders !== null) {
                foreach ($this->collPOrders as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->pUQualificationsScheduledForDeletion !== null) {
                if (!$this->pUQualificationsScheduledForDeletion->isEmpty()) {
                    PUQualificationQuery::create()
                        ->filterByPrimaryKeys($this->pUQualificationsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->pUQualificationsScheduledForDeletion = null;
                }
            }

            if ($this->collPUQualifications !== null) {
                foreach ($this->collPUQualifications as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->puFollowDdPUsersScheduledForDeletion !== null) {
                if (!$this->puFollowDdPUsersScheduledForDeletion->isEmpty()) {
                    PUFollowDDQuery::create()
                        ->filterByPrimaryKeys($this->puFollowDdPUsersScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->puFollowDdPUsersScheduledForDeletion = null;
                }
            }

            if ($this->collPuFollowDdPUsers !== null) {
                foreach ($this->collPuFollowDdPUsers as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->puReputationRbPUsersScheduledForDeletion !== null) {
                if (!$this->puReputationRbPUsersScheduledForDeletion->isEmpty()) {
                    PUReputationRBQuery::create()
                        ->filterByPrimaryKeys($this->puReputationRbPUsersScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->puReputationRbPUsersScheduledForDeletion = null;
                }
            }

            if ($this->collPuReputationRbPUsers !== null) {
                foreach ($this->collPuReputationRbPUsers as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->puReputationRaPUsersScheduledForDeletion !== null) {
                if (!$this->puReputationRaPUsersScheduledForDeletion->isEmpty()) {
                    PUReputationRAQuery::create()
                        ->filterByPrimaryKeys($this->puReputationRaPUsersScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->puReputationRaPUsersScheduledForDeletion = null;
                }
            }

            if ($this->collPuReputationRaPUsers !== null) {
                foreach ($this->collPuReputationRaPUsers as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->puTaggedTPUsersScheduledForDeletion !== null) {
                if (!$this->puTaggedTPUsersScheduledForDeletion->isEmpty()) {
                    PUTaggedTQuery::create()
                        ->filterByPrimaryKeys($this->puTaggedTPUsersScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->puTaggedTPUsersScheduledForDeletion = null;
                }
            }

            if ($this->collPuTaggedTPUsers !== null) {
                foreach ($this->collPuTaggedTPUsers as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->pDDebatesScheduledForDeletion !== null) {
                if (!$this->pDDebatesScheduledForDeletion->isEmpty()) {
                    foreach ($this->pDDebatesScheduledForDeletion as $pDDebate) {
                        // need to save related object because we set the relation to null
                        $pDDebate->save($con);
                    }
                    $this->pDDebatesScheduledForDeletion = null;
                }
            }

            if ($this->collPDDebates !== null) {
                foreach ($this->collPDDebates as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->pDDCommentsScheduledForDeletion !== null) {
                if (!$this->pDDCommentsScheduledForDeletion->isEmpty()) {
                    foreach ($this->pDDCommentsScheduledForDeletion as $pDDComment) {
                        // need to save related object because we set the relation to null
                        $pDDComment->save($con);
                    }
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

            if ($this->pDReactionsScheduledForDeletion !== null) {
                if (!$this->pDReactionsScheduledForDeletion->isEmpty()) {
                    foreach ($this->pDReactionsScheduledForDeletion as $pDReaction) {
                        // need to save related object because we set the relation to null
                        $pDReaction->save($con);
                    }
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

            if ($this->pDRCommentsScheduledForDeletion !== null) {
                if (!$this->pDRCommentsScheduledForDeletion->isEmpty()) {
                    foreach ($this->pDRCommentsScheduledForDeletion as $pDRComment) {
                        // need to save related object because we set the relation to null
                        $pDRComment->save($con);
                    }
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

            if ($this->pUFollowUsRelatedByPUserIdScheduledForDeletion !== null) {
                if (!$this->pUFollowUsRelatedByPUserIdScheduledForDeletion->isEmpty()) {
                    PUFollowUQuery::create()
                        ->filterByPrimaryKeys($this->pUFollowUsRelatedByPUserIdScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->pUFollowUsRelatedByPUserIdScheduledForDeletion = null;
                }
            }

            if ($this->collPUFollowUsRelatedByPUserId !== null) {
                foreach ($this->collPUFollowUsRelatedByPUserId as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->pUFollowUsRelatedByPUserFollowerIdScheduledForDeletion !== null) {
                if (!$this->pUFollowUsRelatedByPUserFollowerIdScheduledForDeletion->isEmpty()) {
                    PUFollowUQuery::create()
                        ->filterByPrimaryKeys($this->pUFollowUsRelatedByPUserFollowerIdScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->pUFollowUsRelatedByPUserFollowerIdScheduledForDeletion = null;
                }
            }

            if ($this->collPUFollowUsRelatedByPUserFollowerId !== null) {
                foreach ($this->collPUFollowUsRelatedByPUserFollowerId as $referrerFK) {
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

        $this->modifiedColumns[] = PUserPeer::ID;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . PUserPeer::ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(PUserPeer::ID)) {
            $modifiedColumns[':p' . $index++]  = '`id`';
        }
        if ($this->isColumnModified(PUserPeer::PROVIDER)) {
            $modifiedColumns[':p' . $index++]  = '`provider`';
        }
        if ($this->isColumnModified(PUserPeer::PROVIDER_ID)) {
            $modifiedColumns[':p' . $index++]  = '`provider_id`';
        }
        if ($this->isColumnModified(PUserPeer::NICKNAME)) {
            $modifiedColumns[':p' . $index++]  = '`nickname`';
        }
        if ($this->isColumnModified(PUserPeer::REALNAME)) {
            $modifiedColumns[':p' . $index++]  = '`realname`';
        }
        if ($this->isColumnModified(PUserPeer::USERNAME)) {
            $modifiedColumns[':p' . $index++]  = '`username`';
        }
        if ($this->isColumnModified(PUserPeer::USERNAME_CANONICAL)) {
            $modifiedColumns[':p' . $index++]  = '`username_canonical`';
        }
        if ($this->isColumnModified(PUserPeer::EMAIL)) {
            $modifiedColumns[':p' . $index++]  = '`email`';
        }
        if ($this->isColumnModified(PUserPeer::EMAIL_CANONICAL)) {
            $modifiedColumns[':p' . $index++]  = '`email_canonical`';
        }
        if ($this->isColumnModified(PUserPeer::ENABLED)) {
            $modifiedColumns[':p' . $index++]  = '`enabled`';
        }
        if ($this->isColumnModified(PUserPeer::SALT)) {
            $modifiedColumns[':p' . $index++]  = '`salt`';
        }
        if ($this->isColumnModified(PUserPeer::PASSWORD)) {
            $modifiedColumns[':p' . $index++]  = '`password`';
        }
        if ($this->isColumnModified(PUserPeer::LAST_LOGIN)) {
            $modifiedColumns[':p' . $index++]  = '`last_login`';
        }
        if ($this->isColumnModified(PUserPeer::LOCKED)) {
            $modifiedColumns[':p' . $index++]  = '`locked`';
        }
        if ($this->isColumnModified(PUserPeer::EXPIRED)) {
            $modifiedColumns[':p' . $index++]  = '`expired`';
        }
        if ($this->isColumnModified(PUserPeer::EXPIRES_AT)) {
            $modifiedColumns[':p' . $index++]  = '`expires_at`';
        }
        if ($this->isColumnModified(PUserPeer::CONFIRMATION_TOKEN)) {
            $modifiedColumns[':p' . $index++]  = '`confirmation_token`';
        }
        if ($this->isColumnModified(PUserPeer::PASSWORD_REQUESTED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`password_requested_at`';
        }
        if ($this->isColumnModified(PUserPeer::CREDENTIALS_EXPIRED)) {
            $modifiedColumns[':p' . $index++]  = '`credentials_expired`';
        }
        if ($this->isColumnModified(PUserPeer::CREDENTIALS_EXPIRE_AT)) {
            $modifiedColumns[':p' . $index++]  = '`credentials_expire_at`';
        }
        if ($this->isColumnModified(PUserPeer::ROLES)) {
            $modifiedColumns[':p' . $index++]  = '`roles`';
        }
        if ($this->isColumnModified(PUserPeer::TYPE)) {
            $modifiedColumns[':p' . $index++]  = '`type`';
        }
        if ($this->isColumnModified(PUserPeer::STATUS)) {
            $modifiedColumns[':p' . $index++]  = '`status`';
        }
        if ($this->isColumnModified(PUserPeer::FILE_NAME)) {
            $modifiedColumns[':p' . $index++]  = '`file_name`';
        }
        if ($this->isColumnModified(PUserPeer::GENDER)) {
            $modifiedColumns[':p' . $index++]  = '`gender`';
        }
        if ($this->isColumnModified(PUserPeer::FIRSTNAME)) {
            $modifiedColumns[':p' . $index++]  = '`firstname`';
        }
        if ($this->isColumnModified(PUserPeer::NAME)) {
            $modifiedColumns[':p' . $index++]  = '`name`';
        }
        if ($this->isColumnModified(PUserPeer::BIRTHDAY)) {
            $modifiedColumns[':p' . $index++]  = '`birthday`';
        }
        if ($this->isColumnModified(PUserPeer::SUMMARY)) {
            $modifiedColumns[':p' . $index++]  = '`summary`';
        }
        if ($this->isColumnModified(PUserPeer::BIOGRAPHY)) {
            $modifiedColumns[':p' . $index++]  = '`biography`';
        }
        if ($this->isColumnModified(PUserPeer::WEBSITE)) {
            $modifiedColumns[':p' . $index++]  = '`website`';
        }
        if ($this->isColumnModified(PUserPeer::TWITTER)) {
            $modifiedColumns[':p' . $index++]  = '`twitter`';
        }
        if ($this->isColumnModified(PUserPeer::FACEBOOK)) {
            $modifiedColumns[':p' . $index++]  = '`facebook`';
        }
        if ($this->isColumnModified(PUserPeer::PHONE)) {
            $modifiedColumns[':p' . $index++]  = '`phone`';
        }
        if ($this->isColumnModified(PUserPeer::NEWSLETTER)) {
            $modifiedColumns[':p' . $index++]  = '`newsletter`';
        }
        if ($this->isColumnModified(PUserPeer::LAST_CONNECT)) {
            $modifiedColumns[':p' . $index++]  = '`last_connect`';
        }
        if ($this->isColumnModified(PUserPeer::ONLINE)) {
            $modifiedColumns[':p' . $index++]  = '`online`';
        }
        if ($this->isColumnModified(PUserPeer::CREATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`created_at`';
        }
        if ($this->isColumnModified(PUserPeer::UPDATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`updated_at`';
        }
        if ($this->isColumnModified(PUserPeer::SLUG)) {
            $modifiedColumns[':p' . $index++]  = '`slug`';
        }

        $sql = sprintf(
            'INSERT INTO `p_user` (%s) VALUES (%s)',
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
                    case '`type`':
                        $stmt->bindValue($identifier, $this->type, PDO::PARAM_INT);
                        break;
                    case '`status`':
                        $stmt->bindValue($identifier, $this->status, PDO::PARAM_INT);
                        break;
                    case '`file_name`':
                        $stmt->bindValue($identifier, $this->file_name, PDO::PARAM_STR);
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
                    case '`summary`':
                        $stmt->bindValue($identifier, $this->summary, PDO::PARAM_STR);
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
                    case '`online`':
                        $stmt->bindValue($identifier, (int) $this->online, PDO::PARAM_INT);
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


            if (($retval = PUserPeer::doValidate($this, $columns)) !== true) {
                $failureMap = array_merge($failureMap, $retval);
            }


                if ($this->collPOrders !== null) {
                    foreach ($this->collPOrders as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collPUQualifications !== null) {
                    foreach ($this->collPUQualifications as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collPuFollowDdPUsers !== null) {
                    foreach ($this->collPuFollowDdPUsers as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collPuReputationRbPUsers !== null) {
                    foreach ($this->collPuReputationRbPUsers as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collPuReputationRaPUsers !== null) {
                    foreach ($this->collPuReputationRaPUsers as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collPuTaggedTPUsers !== null) {
                    foreach ($this->collPuTaggedTPUsers as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collPDDebates !== null) {
                    foreach ($this->collPDDebates as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collPDDComments !== null) {
                    foreach ($this->collPDDComments as $referrerFK) {
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

                if ($this->collPDRComments !== null) {
                    foreach ($this->collPDRComments as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collPUFollowUsRelatedByPUserId !== null) {
                    foreach ($this->collPUFollowUsRelatedByPUserId as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collPUFollowUsRelatedByPUserFollowerId !== null) {
                    foreach ($this->collPUFollowUsRelatedByPUserFollowerId as $referrerFK) {
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
        $pos = PUserPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                return $this->getProvider();
                break;
            case 2:
                return $this->getProviderId();
                break;
            case 3:
                return $this->getNickname();
                break;
            case 4:
                return $this->getRealname();
                break;
            case 5:
                return $this->getUsername();
                break;
            case 6:
                return $this->getUsernameCanonical();
                break;
            case 7:
                return $this->getEmail();
                break;
            case 8:
                return $this->getEmailCanonical();
                break;
            case 9:
                return $this->getEnabled();
                break;
            case 10:
                return $this->getSalt();
                break;
            case 11:
                return $this->getPassword();
                break;
            case 12:
                return $this->getLastLogin();
                break;
            case 13:
                return $this->getLocked();
                break;
            case 14:
                return $this->getExpired();
                break;
            case 15:
                return $this->getExpiresAt();
                break;
            case 16:
                return $this->getConfirmationToken();
                break;
            case 17:
                return $this->getPasswordRequestedAt();
                break;
            case 18:
                return $this->getCredentialsExpired();
                break;
            case 19:
                return $this->getCredentialsExpireAt();
                break;
            case 20:
                return $this->getRoles();
                break;
            case 21:
                return $this->getType();
                break;
            case 22:
                return $this->getStatus();
                break;
            case 23:
                return $this->getFileName();
                break;
            case 24:
                return $this->getGender();
                break;
            case 25:
                return $this->getFirstname();
                break;
            case 26:
                return $this->getName();
                break;
            case 27:
                return $this->getBirthday();
                break;
            case 28:
                return $this->getSummary();
                break;
            case 29:
                return $this->getBiography();
                break;
            case 30:
                return $this->getWebsite();
                break;
            case 31:
                return $this->getTwitter();
                break;
            case 32:
                return $this->getFacebook();
                break;
            case 33:
                return $this->getPhone();
                break;
            case 34:
                return $this->getNewsletter();
                break;
            case 35:
                return $this->getLastConnect();
                break;
            case 36:
                return $this->getOnline();
                break;
            case 37:
                return $this->getCreatedAt();
                break;
            case 38:
                return $this->getUpdatedAt();
                break;
            case 39:
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
        if (isset($alreadyDumpedObjects['PUser'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['PUser'][$this->getPrimaryKey()] = true;
        $keys = PUserPeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getProvider(),
            $keys[2] => $this->getProviderId(),
            $keys[3] => $this->getNickname(),
            $keys[4] => $this->getRealname(),
            $keys[5] => $this->getUsername(),
            $keys[6] => $this->getUsernameCanonical(),
            $keys[7] => $this->getEmail(),
            $keys[8] => $this->getEmailCanonical(),
            $keys[9] => $this->getEnabled(),
            $keys[10] => $this->getSalt(),
            $keys[11] => $this->getPassword(),
            $keys[12] => $this->getLastLogin(),
            $keys[13] => $this->getLocked(),
            $keys[14] => $this->getExpired(),
            $keys[15] => $this->getExpiresAt(),
            $keys[16] => $this->getConfirmationToken(),
            $keys[17] => $this->getPasswordRequestedAt(),
            $keys[18] => $this->getCredentialsExpired(),
            $keys[19] => $this->getCredentialsExpireAt(),
            $keys[20] => $this->getRoles(),
            $keys[21] => $this->getType(),
            $keys[22] => $this->getStatus(),
            $keys[23] => $this->getFileName(),
            $keys[24] => $this->getGender(),
            $keys[25] => $this->getFirstname(),
            $keys[26] => $this->getName(),
            $keys[27] => $this->getBirthday(),
            $keys[28] => $this->getSummary(),
            $keys[29] => $this->getBiography(),
            $keys[30] => $this->getWebsite(),
            $keys[31] => $this->getTwitter(),
            $keys[32] => $this->getFacebook(),
            $keys[33] => $this->getPhone(),
            $keys[34] => $this->getNewsletter(),
            $keys[35] => $this->getLastConnect(),
            $keys[36] => $this->getOnline(),
            $keys[37] => $this->getCreatedAt(),
            $keys[38] => $this->getUpdatedAt(),
            $keys[39] => $this->getSlug(),
        );
        if ($includeForeignObjects) {
            if (null !== $this->collPOrders) {
                $result['POrders'] = $this->collPOrders->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPUQualifications) {
                $result['PUQualifications'] = $this->collPUQualifications->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPuFollowDdPUsers) {
                $result['PuFollowDdPUsers'] = $this->collPuFollowDdPUsers->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPuReputationRbPUsers) {
                $result['PuReputationRbPUsers'] = $this->collPuReputationRbPUsers->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPuReputationRaPUsers) {
                $result['PuReputationRaPUsers'] = $this->collPuReputationRaPUsers->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPuTaggedTPUsers) {
                $result['PuTaggedTPUsers'] = $this->collPuTaggedTPUsers->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPDDebates) {
                $result['PDDebates'] = $this->collPDDebates->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPDDComments) {
                $result['PDDComments'] = $this->collPDDComments->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPDReactions) {
                $result['PDReactions'] = $this->collPDReactions->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPDRComments) {
                $result['PDRComments'] = $this->collPDRComments->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPUFollowUsRelatedByPUserId) {
                $result['PUFollowUsRelatedByPUserId'] = $this->collPUFollowUsRelatedByPUserId->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPUFollowUsRelatedByPUserFollowerId) {
                $result['PUFollowUsRelatedByPUserFollowerId'] = $this->collPUFollowUsRelatedByPUserFollowerId->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
        $pos = PUserPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);

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
                $this->setProvider($value);
                break;
            case 2:
                $this->setProviderId($value);
                break;
            case 3:
                $this->setNickname($value);
                break;
            case 4:
                $this->setRealname($value);
                break;
            case 5:
                $this->setUsername($value);
                break;
            case 6:
                $this->setUsernameCanonical($value);
                break;
            case 7:
                $this->setEmail($value);
                break;
            case 8:
                $this->setEmailCanonical($value);
                break;
            case 9:
                $this->setEnabled($value);
                break;
            case 10:
                $this->setSalt($value);
                break;
            case 11:
                $this->setPassword($value);
                break;
            case 12:
                $this->setLastLogin($value);
                break;
            case 13:
                $this->setLocked($value);
                break;
            case 14:
                $this->setExpired($value);
                break;
            case 15:
                $this->setExpiresAt($value);
                break;
            case 16:
                $this->setConfirmationToken($value);
                break;
            case 17:
                $this->setPasswordRequestedAt($value);
                break;
            case 18:
                $this->setCredentialsExpired($value);
                break;
            case 19:
                $this->setCredentialsExpireAt($value);
                break;
            case 20:
                if (!is_array($value)) {
                    $v = trim(substr($value, 2, -2));
                    $value = $v ? explode(' | ', $v) : array();
                }
                $this->setRoles($value);
                break;
            case 21:
                $this->setType($value);
                break;
            case 22:
                $this->setStatus($value);
                break;
            case 23:
                $this->setFileName($value);
                break;
            case 24:
                $valueSet = PUserPeer::getValueSet(PUserPeer::GENDER);
                if (isset($valueSet[$value])) {
                    $value = $valueSet[$value];
                }
                $this->setGender($value);
                break;
            case 25:
                $this->setFirstname($value);
                break;
            case 26:
                $this->setName($value);
                break;
            case 27:
                $this->setBirthday($value);
                break;
            case 28:
                $this->setSummary($value);
                break;
            case 29:
                $this->setBiography($value);
                break;
            case 30:
                $this->setWebsite($value);
                break;
            case 31:
                $this->setTwitter($value);
                break;
            case 32:
                $this->setFacebook($value);
                break;
            case 33:
                $this->setPhone($value);
                break;
            case 34:
                $this->setNewsletter($value);
                break;
            case 35:
                $this->setLastConnect($value);
                break;
            case 36:
                $this->setOnline($value);
                break;
            case 37:
                $this->setCreatedAt($value);
                break;
            case 38:
                $this->setUpdatedAt($value);
                break;
            case 39:
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
        $keys = PUserPeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setProvider($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setProviderId($arr[$keys[2]]);
        if (array_key_exists($keys[3], $arr)) $this->setNickname($arr[$keys[3]]);
        if (array_key_exists($keys[4], $arr)) $this->setRealname($arr[$keys[4]]);
        if (array_key_exists($keys[5], $arr)) $this->setUsername($arr[$keys[5]]);
        if (array_key_exists($keys[6], $arr)) $this->setUsernameCanonical($arr[$keys[6]]);
        if (array_key_exists($keys[7], $arr)) $this->setEmail($arr[$keys[7]]);
        if (array_key_exists($keys[8], $arr)) $this->setEmailCanonical($arr[$keys[8]]);
        if (array_key_exists($keys[9], $arr)) $this->setEnabled($arr[$keys[9]]);
        if (array_key_exists($keys[10], $arr)) $this->setSalt($arr[$keys[10]]);
        if (array_key_exists($keys[11], $arr)) $this->setPassword($arr[$keys[11]]);
        if (array_key_exists($keys[12], $arr)) $this->setLastLogin($arr[$keys[12]]);
        if (array_key_exists($keys[13], $arr)) $this->setLocked($arr[$keys[13]]);
        if (array_key_exists($keys[14], $arr)) $this->setExpired($arr[$keys[14]]);
        if (array_key_exists($keys[15], $arr)) $this->setExpiresAt($arr[$keys[15]]);
        if (array_key_exists($keys[16], $arr)) $this->setConfirmationToken($arr[$keys[16]]);
        if (array_key_exists($keys[17], $arr)) $this->setPasswordRequestedAt($arr[$keys[17]]);
        if (array_key_exists($keys[18], $arr)) $this->setCredentialsExpired($arr[$keys[18]]);
        if (array_key_exists($keys[19], $arr)) $this->setCredentialsExpireAt($arr[$keys[19]]);
        if (array_key_exists($keys[20], $arr)) $this->setRoles($arr[$keys[20]]);
        if (array_key_exists($keys[21], $arr)) $this->setType($arr[$keys[21]]);
        if (array_key_exists($keys[22], $arr)) $this->setStatus($arr[$keys[22]]);
        if (array_key_exists($keys[23], $arr)) $this->setFileName($arr[$keys[23]]);
        if (array_key_exists($keys[24], $arr)) $this->setGender($arr[$keys[24]]);
        if (array_key_exists($keys[25], $arr)) $this->setFirstname($arr[$keys[25]]);
        if (array_key_exists($keys[26], $arr)) $this->setName($arr[$keys[26]]);
        if (array_key_exists($keys[27], $arr)) $this->setBirthday($arr[$keys[27]]);
        if (array_key_exists($keys[28], $arr)) $this->setSummary($arr[$keys[28]]);
        if (array_key_exists($keys[29], $arr)) $this->setBiography($arr[$keys[29]]);
        if (array_key_exists($keys[30], $arr)) $this->setWebsite($arr[$keys[30]]);
        if (array_key_exists($keys[31], $arr)) $this->setTwitter($arr[$keys[31]]);
        if (array_key_exists($keys[32], $arr)) $this->setFacebook($arr[$keys[32]]);
        if (array_key_exists($keys[33], $arr)) $this->setPhone($arr[$keys[33]]);
        if (array_key_exists($keys[34], $arr)) $this->setNewsletter($arr[$keys[34]]);
        if (array_key_exists($keys[35], $arr)) $this->setLastConnect($arr[$keys[35]]);
        if (array_key_exists($keys[36], $arr)) $this->setOnline($arr[$keys[36]]);
        if (array_key_exists($keys[37], $arr)) $this->setCreatedAt($arr[$keys[37]]);
        if (array_key_exists($keys[38], $arr)) $this->setUpdatedAt($arr[$keys[38]]);
        if (array_key_exists($keys[39], $arr)) $this->setSlug($arr[$keys[39]]);
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(PUserPeer::DATABASE_NAME);

        if ($this->isColumnModified(PUserPeer::ID)) $criteria->add(PUserPeer::ID, $this->id);
        if ($this->isColumnModified(PUserPeer::PROVIDER)) $criteria->add(PUserPeer::PROVIDER, $this->provider);
        if ($this->isColumnModified(PUserPeer::PROVIDER_ID)) $criteria->add(PUserPeer::PROVIDER_ID, $this->provider_id);
        if ($this->isColumnModified(PUserPeer::NICKNAME)) $criteria->add(PUserPeer::NICKNAME, $this->nickname);
        if ($this->isColumnModified(PUserPeer::REALNAME)) $criteria->add(PUserPeer::REALNAME, $this->realname);
        if ($this->isColumnModified(PUserPeer::USERNAME)) $criteria->add(PUserPeer::USERNAME, $this->username);
        if ($this->isColumnModified(PUserPeer::USERNAME_CANONICAL)) $criteria->add(PUserPeer::USERNAME_CANONICAL, $this->username_canonical);
        if ($this->isColumnModified(PUserPeer::EMAIL)) $criteria->add(PUserPeer::EMAIL, $this->email);
        if ($this->isColumnModified(PUserPeer::EMAIL_CANONICAL)) $criteria->add(PUserPeer::EMAIL_CANONICAL, $this->email_canonical);
        if ($this->isColumnModified(PUserPeer::ENABLED)) $criteria->add(PUserPeer::ENABLED, $this->enabled);
        if ($this->isColumnModified(PUserPeer::SALT)) $criteria->add(PUserPeer::SALT, $this->salt);
        if ($this->isColumnModified(PUserPeer::PASSWORD)) $criteria->add(PUserPeer::PASSWORD, $this->password);
        if ($this->isColumnModified(PUserPeer::LAST_LOGIN)) $criteria->add(PUserPeer::LAST_LOGIN, $this->last_login);
        if ($this->isColumnModified(PUserPeer::LOCKED)) $criteria->add(PUserPeer::LOCKED, $this->locked);
        if ($this->isColumnModified(PUserPeer::EXPIRED)) $criteria->add(PUserPeer::EXPIRED, $this->expired);
        if ($this->isColumnModified(PUserPeer::EXPIRES_AT)) $criteria->add(PUserPeer::EXPIRES_AT, $this->expires_at);
        if ($this->isColumnModified(PUserPeer::CONFIRMATION_TOKEN)) $criteria->add(PUserPeer::CONFIRMATION_TOKEN, $this->confirmation_token);
        if ($this->isColumnModified(PUserPeer::PASSWORD_REQUESTED_AT)) $criteria->add(PUserPeer::PASSWORD_REQUESTED_AT, $this->password_requested_at);
        if ($this->isColumnModified(PUserPeer::CREDENTIALS_EXPIRED)) $criteria->add(PUserPeer::CREDENTIALS_EXPIRED, $this->credentials_expired);
        if ($this->isColumnModified(PUserPeer::CREDENTIALS_EXPIRE_AT)) $criteria->add(PUserPeer::CREDENTIALS_EXPIRE_AT, $this->credentials_expire_at);
        if ($this->isColumnModified(PUserPeer::ROLES)) $criteria->add(PUserPeer::ROLES, $this->roles);
        if ($this->isColumnModified(PUserPeer::TYPE)) $criteria->add(PUserPeer::TYPE, $this->type);
        if ($this->isColumnModified(PUserPeer::STATUS)) $criteria->add(PUserPeer::STATUS, $this->status);
        if ($this->isColumnModified(PUserPeer::FILE_NAME)) $criteria->add(PUserPeer::FILE_NAME, $this->file_name);
        if ($this->isColumnModified(PUserPeer::GENDER)) $criteria->add(PUserPeer::GENDER, $this->gender);
        if ($this->isColumnModified(PUserPeer::FIRSTNAME)) $criteria->add(PUserPeer::FIRSTNAME, $this->firstname);
        if ($this->isColumnModified(PUserPeer::NAME)) $criteria->add(PUserPeer::NAME, $this->name);
        if ($this->isColumnModified(PUserPeer::BIRTHDAY)) $criteria->add(PUserPeer::BIRTHDAY, $this->birthday);
        if ($this->isColumnModified(PUserPeer::SUMMARY)) $criteria->add(PUserPeer::SUMMARY, $this->summary);
        if ($this->isColumnModified(PUserPeer::BIOGRAPHY)) $criteria->add(PUserPeer::BIOGRAPHY, $this->biography);
        if ($this->isColumnModified(PUserPeer::WEBSITE)) $criteria->add(PUserPeer::WEBSITE, $this->website);
        if ($this->isColumnModified(PUserPeer::TWITTER)) $criteria->add(PUserPeer::TWITTER, $this->twitter);
        if ($this->isColumnModified(PUserPeer::FACEBOOK)) $criteria->add(PUserPeer::FACEBOOK, $this->facebook);
        if ($this->isColumnModified(PUserPeer::PHONE)) $criteria->add(PUserPeer::PHONE, $this->phone);
        if ($this->isColumnModified(PUserPeer::NEWSLETTER)) $criteria->add(PUserPeer::NEWSLETTER, $this->newsletter);
        if ($this->isColumnModified(PUserPeer::LAST_CONNECT)) $criteria->add(PUserPeer::LAST_CONNECT, $this->last_connect);
        if ($this->isColumnModified(PUserPeer::ONLINE)) $criteria->add(PUserPeer::ONLINE, $this->online);
        if ($this->isColumnModified(PUserPeer::CREATED_AT)) $criteria->add(PUserPeer::CREATED_AT, $this->created_at);
        if ($this->isColumnModified(PUserPeer::UPDATED_AT)) $criteria->add(PUserPeer::UPDATED_AT, $this->updated_at);
        if ($this->isColumnModified(PUserPeer::SLUG)) $criteria->add(PUserPeer::SLUG, $this->slug);

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
        $criteria = new Criteria(PUserPeer::DATABASE_NAME);
        $criteria->add(PUserPeer::ID, $this->id);

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
     * @param object $copyObj An object of PUser (or compatible) type.
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
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
        $copyObj->setType($this->getType());
        $copyObj->setStatus($this->getStatus());
        $copyObj->setFileName($this->getFileName());
        $copyObj->setGender($this->getGender());
        $copyObj->setFirstname($this->getFirstname());
        $copyObj->setName($this->getName());
        $copyObj->setBirthday($this->getBirthday());
        $copyObj->setSummary($this->getSummary());
        $copyObj->setBiography($this->getBiography());
        $copyObj->setWebsite($this->getWebsite());
        $copyObj->setTwitter($this->getTwitter());
        $copyObj->setFacebook($this->getFacebook());
        $copyObj->setPhone($this->getPhone());
        $copyObj->setNewsletter($this->getNewsletter());
        $copyObj->setLastConnect($this->getLastConnect());
        $copyObj->setOnline($this->getOnline());
        $copyObj->setCreatedAt($this->getCreatedAt());
        $copyObj->setUpdatedAt($this->getUpdatedAt());
        $copyObj->setSlug($this->getSlug());

        if ($deepCopy && !$this->startCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);
            // store object hash to prevent cycle
            $this->startCopy = true;

            foreach ($this->getPOrders() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPOrder($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPUQualifications() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPUQualification($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPuFollowDdPUsers() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPuFollowDdPUser($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPuReputationRbPUsers() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPuReputationRbPUser($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPuReputationRaPUsers() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPuReputationRaPUser($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPuTaggedTPUsers() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPuTaggedTPUser($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPDDebates() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPDDebate($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPDDComments() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPDDComment($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPDReactions() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPDReaction($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPDRComments() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPDRComment($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPUFollowUsRelatedByPUserId() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPUFollowURelatedByPUserId($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPUFollowUsRelatedByPUserFollowerId() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPUFollowURelatedByPUserFollowerId($relObj->copy($deepCopy));
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
     * @return PUser Clone of current object.
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
     * @return PUserPeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new PUserPeer();
        }

        return self::$peer;
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
        if ('POrder' == $relationName) {
            $this->initPOrders();
        }
        if ('PUQualification' == $relationName) {
            $this->initPUQualifications();
        }
        if ('PuFollowDdPUser' == $relationName) {
            $this->initPuFollowDdPUsers();
        }
        if ('PuReputationRbPUser' == $relationName) {
            $this->initPuReputationRbPUsers();
        }
        if ('PuReputationRaPUser' == $relationName) {
            $this->initPuReputationRaPUsers();
        }
        if ('PuTaggedTPUser' == $relationName) {
            $this->initPuTaggedTPUsers();
        }
        if ('PDDebate' == $relationName) {
            $this->initPDDebates();
        }
        if ('PDDComment' == $relationName) {
            $this->initPDDComments();
        }
        if ('PDReaction' == $relationName) {
            $this->initPDReactions();
        }
        if ('PDRComment' == $relationName) {
            $this->initPDRComments();
        }
        if ('PUFollowURelatedByPUserId' == $relationName) {
            $this->initPUFollowUsRelatedByPUserId();
        }
        if ('PUFollowURelatedByPUserFollowerId' == $relationName) {
            $this->initPUFollowUsRelatedByPUserFollowerId();
        }
    }

    /**
     * Clears out the collPOrders collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return PUser The current object (for fluent API support)
     * @see        addPOrders()
     */
    public function clearPOrders()
    {
        $this->collPOrders = null; // important to set this to null since that means it is uninitialized
        $this->collPOrdersPartial = null;

        return $this;
    }

    /**
     * reset is the collPOrders collection loaded partially
     *
     * @return void
     */
    public function resetPartialPOrders($v = true)
    {
        $this->collPOrdersPartial = $v;
    }

    /**
     * Initializes the collPOrders collection.
     *
     * By default this just sets the collPOrders collection to an empty array (like clearcollPOrders());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPOrders($overrideExisting = true)
    {
        if (null !== $this->collPOrders && !$overrideExisting) {
            return;
        }
        $this->collPOrders = new PropelObjectCollection();
        $this->collPOrders->setModel('POrder');
    }

    /**
     * Gets an array of POrder objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this PUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|POrder[] List of POrder objects
     * @throws PropelException
     */
    public function getPOrders($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collPOrdersPartial && !$this->isNew();
        if (null === $this->collPOrders || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPOrders) {
                // return empty collection
                $this->initPOrders();
            } else {
                $collPOrders = POrderQuery::create(null, $criteria)
                    ->filterByPUser($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collPOrdersPartial && count($collPOrders)) {
                      $this->initPOrders(false);

                      foreach($collPOrders as $obj) {
                        if (false == $this->collPOrders->contains($obj)) {
                          $this->collPOrders->append($obj);
                        }
                      }

                      $this->collPOrdersPartial = true;
                    }

                    $collPOrders->getInternalIterator()->rewind();
                    return $collPOrders;
                }

                if($partial && $this->collPOrders) {
                    foreach($this->collPOrders as $obj) {
                        if($obj->isNew()) {
                            $collPOrders[] = $obj;
                        }
                    }
                }

                $this->collPOrders = $collPOrders;
                $this->collPOrdersPartial = false;
            }
        }

        return $this->collPOrders;
    }

    /**
     * Sets a collection of POrder objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $pOrders A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return PUser The current object (for fluent API support)
     */
    public function setPOrders(PropelCollection $pOrders, PropelPDO $con = null)
    {
        $pOrdersToDelete = $this->getPOrders(new Criteria(), $con)->diff($pOrders);

        $this->pOrdersScheduledForDeletion = unserialize(serialize($pOrdersToDelete));

        foreach ($pOrdersToDelete as $pOrderRemoved) {
            $pOrderRemoved->setPUser(null);
        }

        $this->collPOrders = null;
        foreach ($pOrders as $pOrder) {
            $this->addPOrder($pOrder);
        }

        $this->collPOrders = $pOrders;
        $this->collPOrdersPartial = false;

        return $this;
    }

    /**
     * Returns the number of related POrder objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related POrder objects.
     * @throws PropelException
     */
    public function countPOrders(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collPOrdersPartial && !$this->isNew();
        if (null === $this->collPOrders || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPOrders) {
                return 0;
            }

            if($partial && !$criteria) {
                return count($this->getPOrders());
            }
            $query = POrderQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPUser($this)
                ->count($con);
        }

        return count($this->collPOrders);
    }

    /**
     * Method called to associate a POrder object to this object
     * through the POrder foreign key attribute.
     *
     * @param    POrder $l POrder
     * @return PUser The current object (for fluent API support)
     */
    public function addPOrder(POrder $l)
    {
        if ($this->collPOrders === null) {
            $this->initPOrders();
            $this->collPOrdersPartial = true;
        }
        if (!in_array($l, $this->collPOrders->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddPOrder($l);
        }

        return $this;
    }

    /**
     * @param	POrder $pOrder The pOrder object to add.
     */
    protected function doAddPOrder($pOrder)
    {
        $this->collPOrders[]= $pOrder;
        $pOrder->setPUser($this);
    }

    /**
     * @param	POrder $pOrder The pOrder object to remove.
     * @return PUser The current object (for fluent API support)
     */
    public function removePOrder($pOrder)
    {
        if ($this->getPOrders()->contains($pOrder)) {
            $this->collPOrders->remove($this->collPOrders->search($pOrder));
            if (null === $this->pOrdersScheduledForDeletion) {
                $this->pOrdersScheduledForDeletion = clone $this->collPOrders;
                $this->pOrdersScheduledForDeletion->clear();
            }
            $this->pOrdersScheduledForDeletion[]= $pOrder;
            $pOrder->setPUser(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this PUser is new, it will return
     * an empty collection; or if this PUser has previously
     * been saved, it will retrieve related POrders from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in PUser.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|POrder[] List of POrder objects
     */
    public function getPOrdersJoinPOOrderState($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = POrderQuery::create(null, $criteria);
        $query->joinWith('POOrderState', $join_behavior);

        return $this->getPOrders($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this PUser is new, it will return
     * an empty collection; or if this PUser has previously
     * been saved, it will retrieve related POrders from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in PUser.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|POrder[] List of POrder objects
     */
    public function getPOrdersJoinPOPaymentState($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = POrderQuery::create(null, $criteria);
        $query->joinWith('POPaymentState', $join_behavior);

        return $this->getPOrders($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this PUser is new, it will return
     * an empty collection; or if this PUser has previously
     * been saved, it will retrieve related POrders from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in PUser.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|POrder[] List of POrder objects
     */
    public function getPOrdersJoinPOPaymentType($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = POrderQuery::create(null, $criteria);
        $query->joinWith('POPaymentType', $join_behavior);

        return $this->getPOrders($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this PUser is new, it will return
     * an empty collection; or if this PUser has previously
     * been saved, it will retrieve related POrders from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in PUser.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|POrder[] List of POrder objects
     */
    public function getPOrdersJoinPOSubscription($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = POrderQuery::create(null, $criteria);
        $query->joinWith('POSubscription', $join_behavior);

        return $this->getPOrders($query, $con);
    }

    /**
     * Clears out the collPUQualifications collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return PUser The current object (for fluent API support)
     * @see        addPUQualifications()
     */
    public function clearPUQualifications()
    {
        $this->collPUQualifications = null; // important to set this to null since that means it is uninitialized
        $this->collPUQualificationsPartial = null;

        return $this;
    }

    /**
     * reset is the collPUQualifications collection loaded partially
     *
     * @return void
     */
    public function resetPartialPUQualifications($v = true)
    {
        $this->collPUQualificationsPartial = $v;
    }

    /**
     * Initializes the collPUQualifications collection.
     *
     * By default this just sets the collPUQualifications collection to an empty array (like clearcollPUQualifications());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPUQualifications($overrideExisting = true)
    {
        if (null !== $this->collPUQualifications && !$overrideExisting) {
            return;
        }
        $this->collPUQualifications = new PropelObjectCollection();
        $this->collPUQualifications->setModel('PUQualification');
    }

    /**
     * Gets an array of PUQualification objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this PUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|PUQualification[] List of PUQualification objects
     * @throws PropelException
     */
    public function getPUQualifications($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collPUQualificationsPartial && !$this->isNew();
        if (null === $this->collPUQualifications || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPUQualifications) {
                // return empty collection
                $this->initPUQualifications();
            } else {
                $collPUQualifications = PUQualificationQuery::create(null, $criteria)
                    ->filterByPUser($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collPUQualificationsPartial && count($collPUQualifications)) {
                      $this->initPUQualifications(false);

                      foreach($collPUQualifications as $obj) {
                        if (false == $this->collPUQualifications->contains($obj)) {
                          $this->collPUQualifications->append($obj);
                        }
                      }

                      $this->collPUQualificationsPartial = true;
                    }

                    $collPUQualifications->getInternalIterator()->rewind();
                    return $collPUQualifications;
                }

                if($partial && $this->collPUQualifications) {
                    foreach($this->collPUQualifications as $obj) {
                        if($obj->isNew()) {
                            $collPUQualifications[] = $obj;
                        }
                    }
                }

                $this->collPUQualifications = $collPUQualifications;
                $this->collPUQualificationsPartial = false;
            }
        }

        return $this->collPUQualifications;
    }

    /**
     * Sets a collection of PUQualification objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $pUQualifications A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return PUser The current object (for fluent API support)
     */
    public function setPUQualifications(PropelCollection $pUQualifications, PropelPDO $con = null)
    {
        $pUQualificationsToDelete = $this->getPUQualifications(new Criteria(), $con)->diff($pUQualifications);

        $this->pUQualificationsScheduledForDeletion = unserialize(serialize($pUQualificationsToDelete));

        foreach ($pUQualificationsToDelete as $pUQualificationRemoved) {
            $pUQualificationRemoved->setPUser(null);
        }

        $this->collPUQualifications = null;
        foreach ($pUQualifications as $pUQualification) {
            $this->addPUQualification($pUQualification);
        }

        $this->collPUQualifications = $pUQualifications;
        $this->collPUQualificationsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related PUQualification objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related PUQualification objects.
     * @throws PropelException
     */
    public function countPUQualifications(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collPUQualificationsPartial && !$this->isNew();
        if (null === $this->collPUQualifications || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPUQualifications) {
                return 0;
            }

            if($partial && !$criteria) {
                return count($this->getPUQualifications());
            }
            $query = PUQualificationQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPUser($this)
                ->count($con);
        }

        return count($this->collPUQualifications);
    }

    /**
     * Method called to associate a PUQualification object to this object
     * through the PUQualification foreign key attribute.
     *
     * @param    PUQualification $l PUQualification
     * @return PUser The current object (for fluent API support)
     */
    public function addPUQualification(PUQualification $l)
    {
        if ($this->collPUQualifications === null) {
            $this->initPUQualifications();
            $this->collPUQualificationsPartial = true;
        }
        if (!in_array($l, $this->collPUQualifications->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddPUQualification($l);
        }

        return $this;
    }

    /**
     * @param	PUQualification $pUQualification The pUQualification object to add.
     */
    protected function doAddPUQualification($pUQualification)
    {
        $this->collPUQualifications[]= $pUQualification;
        $pUQualification->setPUser($this);
    }

    /**
     * @param	PUQualification $pUQualification The pUQualification object to remove.
     * @return PUser The current object (for fluent API support)
     */
    public function removePUQualification($pUQualification)
    {
        if ($this->getPUQualifications()->contains($pUQualification)) {
            $this->collPUQualifications->remove($this->collPUQualifications->search($pUQualification));
            if (null === $this->pUQualificationsScheduledForDeletion) {
                $this->pUQualificationsScheduledForDeletion = clone $this->collPUQualifications;
                $this->pUQualificationsScheduledForDeletion->clear();
            }
            $this->pUQualificationsScheduledForDeletion[]= clone $pUQualification;
            $pUQualification->setPUser(null);
        }

        return $this;
    }

    /**
     * Clears out the collPuFollowDdPUsers collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return PUser The current object (for fluent API support)
     * @see        addPuFollowDdPUsers()
     */
    public function clearPuFollowDdPUsers()
    {
        $this->collPuFollowDdPUsers = null; // important to set this to null since that means it is uninitialized
        $this->collPuFollowDdPUsersPartial = null;

        return $this;
    }

    /**
     * reset is the collPuFollowDdPUsers collection loaded partially
     *
     * @return void
     */
    public function resetPartialPuFollowDdPUsers($v = true)
    {
        $this->collPuFollowDdPUsersPartial = $v;
    }

    /**
     * Initializes the collPuFollowDdPUsers collection.
     *
     * By default this just sets the collPuFollowDdPUsers collection to an empty array (like clearcollPuFollowDdPUsers());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPuFollowDdPUsers($overrideExisting = true)
    {
        if (null !== $this->collPuFollowDdPUsers && !$overrideExisting) {
            return;
        }
        $this->collPuFollowDdPUsers = new PropelObjectCollection();
        $this->collPuFollowDdPUsers->setModel('PUFollowDD');
    }

    /**
     * Gets an array of PUFollowDD objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this PUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|PUFollowDD[] List of PUFollowDD objects
     * @throws PropelException
     */
    public function getPuFollowDdPUsers($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collPuFollowDdPUsersPartial && !$this->isNew();
        if (null === $this->collPuFollowDdPUsers || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPuFollowDdPUsers) {
                // return empty collection
                $this->initPuFollowDdPUsers();
            } else {
                $collPuFollowDdPUsers = PUFollowDDQuery::create(null, $criteria)
                    ->filterByPuFollowDdPUser($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collPuFollowDdPUsersPartial && count($collPuFollowDdPUsers)) {
                      $this->initPuFollowDdPUsers(false);

                      foreach($collPuFollowDdPUsers as $obj) {
                        if (false == $this->collPuFollowDdPUsers->contains($obj)) {
                          $this->collPuFollowDdPUsers->append($obj);
                        }
                      }

                      $this->collPuFollowDdPUsersPartial = true;
                    }

                    $collPuFollowDdPUsers->getInternalIterator()->rewind();
                    return $collPuFollowDdPUsers;
                }

                if($partial && $this->collPuFollowDdPUsers) {
                    foreach($this->collPuFollowDdPUsers as $obj) {
                        if($obj->isNew()) {
                            $collPuFollowDdPUsers[] = $obj;
                        }
                    }
                }

                $this->collPuFollowDdPUsers = $collPuFollowDdPUsers;
                $this->collPuFollowDdPUsersPartial = false;
            }
        }

        return $this->collPuFollowDdPUsers;
    }

    /**
     * Sets a collection of PuFollowDdPUser objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $puFollowDdPUsers A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return PUser The current object (for fluent API support)
     */
    public function setPuFollowDdPUsers(PropelCollection $puFollowDdPUsers, PropelPDO $con = null)
    {
        $puFollowDdPUsersToDelete = $this->getPuFollowDdPUsers(new Criteria(), $con)->diff($puFollowDdPUsers);

        $this->puFollowDdPUsersScheduledForDeletion = unserialize(serialize($puFollowDdPUsersToDelete));

        foreach ($puFollowDdPUsersToDelete as $puFollowDdPUserRemoved) {
            $puFollowDdPUserRemoved->setPuFollowDdPUser(null);
        }

        $this->collPuFollowDdPUsers = null;
        foreach ($puFollowDdPUsers as $puFollowDdPUser) {
            $this->addPuFollowDdPUser($puFollowDdPUser);
        }

        $this->collPuFollowDdPUsers = $puFollowDdPUsers;
        $this->collPuFollowDdPUsersPartial = false;

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
    public function countPuFollowDdPUsers(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collPuFollowDdPUsersPartial && !$this->isNew();
        if (null === $this->collPuFollowDdPUsers || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPuFollowDdPUsers) {
                return 0;
            }

            if($partial && !$criteria) {
                return count($this->getPuFollowDdPUsers());
            }
            $query = PUFollowDDQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPuFollowDdPUser($this)
                ->count($con);
        }

        return count($this->collPuFollowDdPUsers);
    }

    /**
     * Method called to associate a PUFollowDD object to this object
     * through the PUFollowDD foreign key attribute.
     *
     * @param    PUFollowDD $l PUFollowDD
     * @return PUser The current object (for fluent API support)
     */
    public function addPuFollowDdPUser(PUFollowDD $l)
    {
        if ($this->collPuFollowDdPUsers === null) {
            $this->initPuFollowDdPUsers();
            $this->collPuFollowDdPUsersPartial = true;
        }
        if (!in_array($l, $this->collPuFollowDdPUsers->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddPuFollowDdPUser($l);
        }

        return $this;
    }

    /**
     * @param	PuFollowDdPUser $puFollowDdPUser The puFollowDdPUser object to add.
     */
    protected function doAddPuFollowDdPUser($puFollowDdPUser)
    {
        $this->collPuFollowDdPUsers[]= $puFollowDdPUser;
        $puFollowDdPUser->setPuFollowDdPUser($this);
    }

    /**
     * @param	PuFollowDdPUser $puFollowDdPUser The puFollowDdPUser object to remove.
     * @return PUser The current object (for fluent API support)
     */
    public function removePuFollowDdPUser($puFollowDdPUser)
    {
        if ($this->getPuFollowDdPUsers()->contains($puFollowDdPUser)) {
            $this->collPuFollowDdPUsers->remove($this->collPuFollowDdPUsers->search($puFollowDdPUser));
            if (null === $this->puFollowDdPUsersScheduledForDeletion) {
                $this->puFollowDdPUsersScheduledForDeletion = clone $this->collPuFollowDdPUsers;
                $this->puFollowDdPUsersScheduledForDeletion->clear();
            }
            $this->puFollowDdPUsersScheduledForDeletion[]= clone $puFollowDdPUser;
            $puFollowDdPUser->setPuFollowDdPUser(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this PUser is new, it will return
     * an empty collection; or if this PUser has previously
     * been saved, it will retrieve related PuFollowDdPUsers from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in PUser.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|PUFollowDD[] List of PUFollowDD objects
     */
    public function getPuFollowDdPUsersJoinPuFollowDdPDDebate($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PUFollowDDQuery::create(null, $criteria);
        $query->joinWith('PuFollowDdPDDebate', $join_behavior);

        return $this->getPuFollowDdPUsers($query, $con);
    }

    /**
     * Clears out the collPuReputationRbPUsers collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return PUser The current object (for fluent API support)
     * @see        addPuReputationRbPUsers()
     */
    public function clearPuReputationRbPUsers()
    {
        $this->collPuReputationRbPUsers = null; // important to set this to null since that means it is uninitialized
        $this->collPuReputationRbPUsersPartial = null;

        return $this;
    }

    /**
     * reset is the collPuReputationRbPUsers collection loaded partially
     *
     * @return void
     */
    public function resetPartialPuReputationRbPUsers($v = true)
    {
        $this->collPuReputationRbPUsersPartial = $v;
    }

    /**
     * Initializes the collPuReputationRbPUsers collection.
     *
     * By default this just sets the collPuReputationRbPUsers collection to an empty array (like clearcollPuReputationRbPUsers());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPuReputationRbPUsers($overrideExisting = true)
    {
        if (null !== $this->collPuReputationRbPUsers && !$overrideExisting) {
            return;
        }
        $this->collPuReputationRbPUsers = new PropelObjectCollection();
        $this->collPuReputationRbPUsers->setModel('PUReputationRB');
    }

    /**
     * Gets an array of PUReputationRB objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this PUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|PUReputationRB[] List of PUReputationRB objects
     * @throws PropelException
     */
    public function getPuReputationRbPUsers($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collPuReputationRbPUsersPartial && !$this->isNew();
        if (null === $this->collPuReputationRbPUsers || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPuReputationRbPUsers) {
                // return empty collection
                $this->initPuReputationRbPUsers();
            } else {
                $collPuReputationRbPUsers = PUReputationRBQuery::create(null, $criteria)
                    ->filterByPuReputationRbPUser($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collPuReputationRbPUsersPartial && count($collPuReputationRbPUsers)) {
                      $this->initPuReputationRbPUsers(false);

                      foreach($collPuReputationRbPUsers as $obj) {
                        if (false == $this->collPuReputationRbPUsers->contains($obj)) {
                          $this->collPuReputationRbPUsers->append($obj);
                        }
                      }

                      $this->collPuReputationRbPUsersPartial = true;
                    }

                    $collPuReputationRbPUsers->getInternalIterator()->rewind();
                    return $collPuReputationRbPUsers;
                }

                if($partial && $this->collPuReputationRbPUsers) {
                    foreach($this->collPuReputationRbPUsers as $obj) {
                        if($obj->isNew()) {
                            $collPuReputationRbPUsers[] = $obj;
                        }
                    }
                }

                $this->collPuReputationRbPUsers = $collPuReputationRbPUsers;
                $this->collPuReputationRbPUsersPartial = false;
            }
        }

        return $this->collPuReputationRbPUsers;
    }

    /**
     * Sets a collection of PuReputationRbPUser objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $puReputationRbPUsers A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return PUser The current object (for fluent API support)
     */
    public function setPuReputationRbPUsers(PropelCollection $puReputationRbPUsers, PropelPDO $con = null)
    {
        $puReputationRbPUsersToDelete = $this->getPuReputationRbPUsers(new Criteria(), $con)->diff($puReputationRbPUsers);

        $this->puReputationRbPUsersScheduledForDeletion = unserialize(serialize($puReputationRbPUsersToDelete));

        foreach ($puReputationRbPUsersToDelete as $puReputationRbPUserRemoved) {
            $puReputationRbPUserRemoved->setPuReputationRbPUser(null);
        }

        $this->collPuReputationRbPUsers = null;
        foreach ($puReputationRbPUsers as $puReputationRbPUser) {
            $this->addPuReputationRbPUser($puReputationRbPUser);
        }

        $this->collPuReputationRbPUsers = $puReputationRbPUsers;
        $this->collPuReputationRbPUsersPartial = false;

        return $this;
    }

    /**
     * Returns the number of related PUReputationRB objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related PUReputationRB objects.
     * @throws PropelException
     */
    public function countPuReputationRbPUsers(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collPuReputationRbPUsersPartial && !$this->isNew();
        if (null === $this->collPuReputationRbPUsers || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPuReputationRbPUsers) {
                return 0;
            }

            if($partial && !$criteria) {
                return count($this->getPuReputationRbPUsers());
            }
            $query = PUReputationRBQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPuReputationRbPUser($this)
                ->count($con);
        }

        return count($this->collPuReputationRbPUsers);
    }

    /**
     * Method called to associate a PUReputationRB object to this object
     * through the PUReputationRB foreign key attribute.
     *
     * @param    PUReputationRB $l PUReputationRB
     * @return PUser The current object (for fluent API support)
     */
    public function addPuReputationRbPUser(PUReputationRB $l)
    {
        if ($this->collPuReputationRbPUsers === null) {
            $this->initPuReputationRbPUsers();
            $this->collPuReputationRbPUsersPartial = true;
        }
        if (!in_array($l, $this->collPuReputationRbPUsers->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddPuReputationRbPUser($l);
        }

        return $this;
    }

    /**
     * @param	PuReputationRbPUser $puReputationRbPUser The puReputationRbPUser object to add.
     */
    protected function doAddPuReputationRbPUser($puReputationRbPUser)
    {
        $this->collPuReputationRbPUsers[]= $puReputationRbPUser;
        $puReputationRbPUser->setPuReputationRbPUser($this);
    }

    /**
     * @param	PuReputationRbPUser $puReputationRbPUser The puReputationRbPUser object to remove.
     * @return PUser The current object (for fluent API support)
     */
    public function removePuReputationRbPUser($puReputationRbPUser)
    {
        if ($this->getPuReputationRbPUsers()->contains($puReputationRbPUser)) {
            $this->collPuReputationRbPUsers->remove($this->collPuReputationRbPUsers->search($puReputationRbPUser));
            if (null === $this->puReputationRbPUsersScheduledForDeletion) {
                $this->puReputationRbPUsersScheduledForDeletion = clone $this->collPuReputationRbPUsers;
                $this->puReputationRbPUsersScheduledForDeletion->clear();
            }
            $this->puReputationRbPUsersScheduledForDeletion[]= clone $puReputationRbPUser;
            $puReputationRbPUser->setPuReputationRbPUser(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this PUser is new, it will return
     * an empty collection; or if this PUser has previously
     * been saved, it will retrieve related PuReputationRbPUsers from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in PUser.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|PUReputationRB[] List of PUReputationRB objects
     */
    public function getPuReputationRbPUsersJoinPuReputationRbPRBadge($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PUReputationRBQuery::create(null, $criteria);
        $query->joinWith('PuReputationRbPRBadge', $join_behavior);

        return $this->getPuReputationRbPUsers($query, $con);
    }

    /**
     * Clears out the collPuReputationRaPUsers collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return PUser The current object (for fluent API support)
     * @see        addPuReputationRaPUsers()
     */
    public function clearPuReputationRaPUsers()
    {
        $this->collPuReputationRaPUsers = null; // important to set this to null since that means it is uninitialized
        $this->collPuReputationRaPUsersPartial = null;

        return $this;
    }

    /**
     * reset is the collPuReputationRaPUsers collection loaded partially
     *
     * @return void
     */
    public function resetPartialPuReputationRaPUsers($v = true)
    {
        $this->collPuReputationRaPUsersPartial = $v;
    }

    /**
     * Initializes the collPuReputationRaPUsers collection.
     *
     * By default this just sets the collPuReputationRaPUsers collection to an empty array (like clearcollPuReputationRaPUsers());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPuReputationRaPUsers($overrideExisting = true)
    {
        if (null !== $this->collPuReputationRaPUsers && !$overrideExisting) {
            return;
        }
        $this->collPuReputationRaPUsers = new PropelObjectCollection();
        $this->collPuReputationRaPUsers->setModel('PUReputationRA');
    }

    /**
     * Gets an array of PUReputationRA objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this PUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|PUReputationRA[] List of PUReputationRA objects
     * @throws PropelException
     */
    public function getPuReputationRaPUsers($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collPuReputationRaPUsersPartial && !$this->isNew();
        if (null === $this->collPuReputationRaPUsers || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPuReputationRaPUsers) {
                // return empty collection
                $this->initPuReputationRaPUsers();
            } else {
                $collPuReputationRaPUsers = PUReputationRAQuery::create(null, $criteria)
                    ->filterByPuReputationRaPUser($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collPuReputationRaPUsersPartial && count($collPuReputationRaPUsers)) {
                      $this->initPuReputationRaPUsers(false);

                      foreach($collPuReputationRaPUsers as $obj) {
                        if (false == $this->collPuReputationRaPUsers->contains($obj)) {
                          $this->collPuReputationRaPUsers->append($obj);
                        }
                      }

                      $this->collPuReputationRaPUsersPartial = true;
                    }

                    $collPuReputationRaPUsers->getInternalIterator()->rewind();
                    return $collPuReputationRaPUsers;
                }

                if($partial && $this->collPuReputationRaPUsers) {
                    foreach($this->collPuReputationRaPUsers as $obj) {
                        if($obj->isNew()) {
                            $collPuReputationRaPUsers[] = $obj;
                        }
                    }
                }

                $this->collPuReputationRaPUsers = $collPuReputationRaPUsers;
                $this->collPuReputationRaPUsersPartial = false;
            }
        }

        return $this->collPuReputationRaPUsers;
    }

    /**
     * Sets a collection of PuReputationRaPUser objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $puReputationRaPUsers A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return PUser The current object (for fluent API support)
     */
    public function setPuReputationRaPUsers(PropelCollection $puReputationRaPUsers, PropelPDO $con = null)
    {
        $puReputationRaPUsersToDelete = $this->getPuReputationRaPUsers(new Criteria(), $con)->diff($puReputationRaPUsers);

        $this->puReputationRaPUsersScheduledForDeletion = unserialize(serialize($puReputationRaPUsersToDelete));

        foreach ($puReputationRaPUsersToDelete as $puReputationRaPUserRemoved) {
            $puReputationRaPUserRemoved->setPuReputationRaPUser(null);
        }

        $this->collPuReputationRaPUsers = null;
        foreach ($puReputationRaPUsers as $puReputationRaPUser) {
            $this->addPuReputationRaPUser($puReputationRaPUser);
        }

        $this->collPuReputationRaPUsers = $puReputationRaPUsers;
        $this->collPuReputationRaPUsersPartial = false;

        return $this;
    }

    /**
     * Returns the number of related PUReputationRA objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related PUReputationRA objects.
     * @throws PropelException
     */
    public function countPuReputationRaPUsers(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collPuReputationRaPUsersPartial && !$this->isNew();
        if (null === $this->collPuReputationRaPUsers || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPuReputationRaPUsers) {
                return 0;
            }

            if($partial && !$criteria) {
                return count($this->getPuReputationRaPUsers());
            }
            $query = PUReputationRAQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPuReputationRaPUser($this)
                ->count($con);
        }

        return count($this->collPuReputationRaPUsers);
    }

    /**
     * Method called to associate a PUReputationRA object to this object
     * through the PUReputationRA foreign key attribute.
     *
     * @param    PUReputationRA $l PUReputationRA
     * @return PUser The current object (for fluent API support)
     */
    public function addPuReputationRaPUser(PUReputationRA $l)
    {
        if ($this->collPuReputationRaPUsers === null) {
            $this->initPuReputationRaPUsers();
            $this->collPuReputationRaPUsersPartial = true;
        }
        if (!in_array($l, $this->collPuReputationRaPUsers->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddPuReputationRaPUser($l);
        }

        return $this;
    }

    /**
     * @param	PuReputationRaPUser $puReputationRaPUser The puReputationRaPUser object to add.
     */
    protected function doAddPuReputationRaPUser($puReputationRaPUser)
    {
        $this->collPuReputationRaPUsers[]= $puReputationRaPUser;
        $puReputationRaPUser->setPuReputationRaPUser($this);
    }

    /**
     * @param	PuReputationRaPUser $puReputationRaPUser The puReputationRaPUser object to remove.
     * @return PUser The current object (for fluent API support)
     */
    public function removePuReputationRaPUser($puReputationRaPUser)
    {
        if ($this->getPuReputationRaPUsers()->contains($puReputationRaPUser)) {
            $this->collPuReputationRaPUsers->remove($this->collPuReputationRaPUsers->search($puReputationRaPUser));
            if (null === $this->puReputationRaPUsersScheduledForDeletion) {
                $this->puReputationRaPUsersScheduledForDeletion = clone $this->collPuReputationRaPUsers;
                $this->puReputationRaPUsersScheduledForDeletion->clear();
            }
            $this->puReputationRaPUsersScheduledForDeletion[]= clone $puReputationRaPUser;
            $puReputationRaPUser->setPuReputationRaPUser(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this PUser is new, it will return
     * an empty collection; or if this PUser has previously
     * been saved, it will retrieve related PuReputationRaPUsers from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in PUser.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|PUReputationRA[] List of PUReputationRA objects
     */
    public function getPuReputationRaPUsersJoinPuReputationRaPRBadge($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PUReputationRAQuery::create(null, $criteria);
        $query->joinWith('PuReputationRaPRBadge', $join_behavior);

        return $this->getPuReputationRaPUsers($query, $con);
    }

    /**
     * Clears out the collPuTaggedTPUsers collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return PUser The current object (for fluent API support)
     * @see        addPuTaggedTPUsers()
     */
    public function clearPuTaggedTPUsers()
    {
        $this->collPuTaggedTPUsers = null; // important to set this to null since that means it is uninitialized
        $this->collPuTaggedTPUsersPartial = null;

        return $this;
    }

    /**
     * reset is the collPuTaggedTPUsers collection loaded partially
     *
     * @return void
     */
    public function resetPartialPuTaggedTPUsers($v = true)
    {
        $this->collPuTaggedTPUsersPartial = $v;
    }

    /**
     * Initializes the collPuTaggedTPUsers collection.
     *
     * By default this just sets the collPuTaggedTPUsers collection to an empty array (like clearcollPuTaggedTPUsers());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPuTaggedTPUsers($overrideExisting = true)
    {
        if (null !== $this->collPuTaggedTPUsers && !$overrideExisting) {
            return;
        }
        $this->collPuTaggedTPUsers = new PropelObjectCollection();
        $this->collPuTaggedTPUsers->setModel('PUTaggedT');
    }

    /**
     * Gets an array of PUTaggedT objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this PUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|PUTaggedT[] List of PUTaggedT objects
     * @throws PropelException
     */
    public function getPuTaggedTPUsers($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collPuTaggedTPUsersPartial && !$this->isNew();
        if (null === $this->collPuTaggedTPUsers || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPuTaggedTPUsers) {
                // return empty collection
                $this->initPuTaggedTPUsers();
            } else {
                $collPuTaggedTPUsers = PUTaggedTQuery::create(null, $criteria)
                    ->filterByPuTaggedTPUser($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collPuTaggedTPUsersPartial && count($collPuTaggedTPUsers)) {
                      $this->initPuTaggedTPUsers(false);

                      foreach($collPuTaggedTPUsers as $obj) {
                        if (false == $this->collPuTaggedTPUsers->contains($obj)) {
                          $this->collPuTaggedTPUsers->append($obj);
                        }
                      }

                      $this->collPuTaggedTPUsersPartial = true;
                    }

                    $collPuTaggedTPUsers->getInternalIterator()->rewind();
                    return $collPuTaggedTPUsers;
                }

                if($partial && $this->collPuTaggedTPUsers) {
                    foreach($this->collPuTaggedTPUsers as $obj) {
                        if($obj->isNew()) {
                            $collPuTaggedTPUsers[] = $obj;
                        }
                    }
                }

                $this->collPuTaggedTPUsers = $collPuTaggedTPUsers;
                $this->collPuTaggedTPUsersPartial = false;
            }
        }

        return $this->collPuTaggedTPUsers;
    }

    /**
     * Sets a collection of PuTaggedTPUser objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $puTaggedTPUsers A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return PUser The current object (for fluent API support)
     */
    public function setPuTaggedTPUsers(PropelCollection $puTaggedTPUsers, PropelPDO $con = null)
    {
        $puTaggedTPUsersToDelete = $this->getPuTaggedTPUsers(new Criteria(), $con)->diff($puTaggedTPUsers);

        $this->puTaggedTPUsersScheduledForDeletion = unserialize(serialize($puTaggedTPUsersToDelete));

        foreach ($puTaggedTPUsersToDelete as $puTaggedTPUserRemoved) {
            $puTaggedTPUserRemoved->setPuTaggedTPUser(null);
        }

        $this->collPuTaggedTPUsers = null;
        foreach ($puTaggedTPUsers as $puTaggedTPUser) {
            $this->addPuTaggedTPUser($puTaggedTPUser);
        }

        $this->collPuTaggedTPUsers = $puTaggedTPUsers;
        $this->collPuTaggedTPUsersPartial = false;

        return $this;
    }

    /**
     * Returns the number of related PUTaggedT objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related PUTaggedT objects.
     * @throws PropelException
     */
    public function countPuTaggedTPUsers(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collPuTaggedTPUsersPartial && !$this->isNew();
        if (null === $this->collPuTaggedTPUsers || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPuTaggedTPUsers) {
                return 0;
            }

            if($partial && !$criteria) {
                return count($this->getPuTaggedTPUsers());
            }
            $query = PUTaggedTQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPuTaggedTPUser($this)
                ->count($con);
        }

        return count($this->collPuTaggedTPUsers);
    }

    /**
     * Method called to associate a PUTaggedT object to this object
     * through the PUTaggedT foreign key attribute.
     *
     * @param    PUTaggedT $l PUTaggedT
     * @return PUser The current object (for fluent API support)
     */
    public function addPuTaggedTPUser(PUTaggedT $l)
    {
        if ($this->collPuTaggedTPUsers === null) {
            $this->initPuTaggedTPUsers();
            $this->collPuTaggedTPUsersPartial = true;
        }
        if (!in_array($l, $this->collPuTaggedTPUsers->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddPuTaggedTPUser($l);
        }

        return $this;
    }

    /**
     * @param	PuTaggedTPUser $puTaggedTPUser The puTaggedTPUser object to add.
     */
    protected function doAddPuTaggedTPUser($puTaggedTPUser)
    {
        $this->collPuTaggedTPUsers[]= $puTaggedTPUser;
        $puTaggedTPUser->setPuTaggedTPUser($this);
    }

    /**
     * @param	PuTaggedTPUser $puTaggedTPUser The puTaggedTPUser object to remove.
     * @return PUser The current object (for fluent API support)
     */
    public function removePuTaggedTPUser($puTaggedTPUser)
    {
        if ($this->getPuTaggedTPUsers()->contains($puTaggedTPUser)) {
            $this->collPuTaggedTPUsers->remove($this->collPuTaggedTPUsers->search($puTaggedTPUser));
            if (null === $this->puTaggedTPUsersScheduledForDeletion) {
                $this->puTaggedTPUsersScheduledForDeletion = clone $this->collPuTaggedTPUsers;
                $this->puTaggedTPUsersScheduledForDeletion->clear();
            }
            $this->puTaggedTPUsersScheduledForDeletion[]= clone $puTaggedTPUser;
            $puTaggedTPUser->setPuTaggedTPUser(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this PUser is new, it will return
     * an empty collection; or if this PUser has previously
     * been saved, it will retrieve related PuTaggedTPUsers from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in PUser.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|PUTaggedT[] List of PUTaggedT objects
     */
    public function getPuTaggedTPUsersJoinPuTaggedTPTag($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PUTaggedTQuery::create(null, $criteria);
        $query->joinWith('PuTaggedTPTag', $join_behavior);

        return $this->getPuTaggedTPUsers($query, $con);
    }

    /**
     * Clears out the collPDDebates collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return PUser The current object (for fluent API support)
     * @see        addPDDebates()
     */
    public function clearPDDebates()
    {
        $this->collPDDebates = null; // important to set this to null since that means it is uninitialized
        $this->collPDDebatesPartial = null;

        return $this;
    }

    /**
     * reset is the collPDDebates collection loaded partially
     *
     * @return void
     */
    public function resetPartialPDDebates($v = true)
    {
        $this->collPDDebatesPartial = $v;
    }

    /**
     * Initializes the collPDDebates collection.
     *
     * By default this just sets the collPDDebates collection to an empty array (like clearcollPDDebates());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPDDebates($overrideExisting = true)
    {
        if (null !== $this->collPDDebates && !$overrideExisting) {
            return;
        }
        $this->collPDDebates = new PropelObjectCollection();
        $this->collPDDebates->setModel('PDDebate');
    }

    /**
     * Gets an array of PDDebate objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this PUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|PDDebate[] List of PDDebate objects
     * @throws PropelException
     */
    public function getPDDebates($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collPDDebatesPartial && !$this->isNew();
        if (null === $this->collPDDebates || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPDDebates) {
                // return empty collection
                $this->initPDDebates();
            } else {
                $collPDDebates = PDDebateQuery::create(null, $criteria)
                    ->filterByPUser($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collPDDebatesPartial && count($collPDDebates)) {
                      $this->initPDDebates(false);

                      foreach($collPDDebates as $obj) {
                        if (false == $this->collPDDebates->contains($obj)) {
                          $this->collPDDebates->append($obj);
                        }
                      }

                      $this->collPDDebatesPartial = true;
                    }

                    $collPDDebates->getInternalIterator()->rewind();
                    return $collPDDebates;
                }

                if($partial && $this->collPDDebates) {
                    foreach($this->collPDDebates as $obj) {
                        if($obj->isNew()) {
                            $collPDDebates[] = $obj;
                        }
                    }
                }

                $this->collPDDebates = $collPDDebates;
                $this->collPDDebatesPartial = false;
            }
        }

        return $this->collPDDebates;
    }

    /**
     * Sets a collection of PDDebate objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $pDDebates A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return PUser The current object (for fluent API support)
     */
    public function setPDDebates(PropelCollection $pDDebates, PropelPDO $con = null)
    {
        $pDDebatesToDelete = $this->getPDDebates(new Criteria(), $con)->diff($pDDebates);

        $this->pDDebatesScheduledForDeletion = unserialize(serialize($pDDebatesToDelete));

        foreach ($pDDebatesToDelete as $pDDebateRemoved) {
            $pDDebateRemoved->setPUser(null);
        }

        $this->collPDDebates = null;
        foreach ($pDDebates as $pDDebate) {
            $this->addPDDebate($pDDebate);
        }

        $this->collPDDebates = $pDDebates;
        $this->collPDDebatesPartial = false;

        return $this;
    }

    /**
     * Returns the number of related PDDebate objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related PDDebate objects.
     * @throws PropelException
     */
    public function countPDDebates(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collPDDebatesPartial && !$this->isNew();
        if (null === $this->collPDDebates || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPDDebates) {
                return 0;
            }

            if($partial && !$criteria) {
                return count($this->getPDDebates());
            }
            $query = PDDebateQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPUser($this)
                ->count($con);
        }

        return count($this->collPDDebates);
    }

    /**
     * Method called to associate a PDDebate object to this object
     * through the PDDebate foreign key attribute.
     *
     * @param    PDDebate $l PDDebate
     * @return PUser The current object (for fluent API support)
     */
    public function addPDDebate(PDDebate $l)
    {
        if ($this->collPDDebates === null) {
            $this->initPDDebates();
            $this->collPDDebatesPartial = true;
        }
        if (!in_array($l, $this->collPDDebates->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddPDDebate($l);
        }

        return $this;
    }

    /**
     * @param	PDDebate $pDDebate The pDDebate object to add.
     */
    protected function doAddPDDebate($pDDebate)
    {
        $this->collPDDebates[]= $pDDebate;
        $pDDebate->setPUser($this);
    }

    /**
     * @param	PDDebate $pDDebate The pDDebate object to remove.
     * @return PUser The current object (for fluent API support)
     */
    public function removePDDebate($pDDebate)
    {
        if ($this->getPDDebates()->contains($pDDebate)) {
            $this->collPDDebates->remove($this->collPDDebates->search($pDDebate));
            if (null === $this->pDDebatesScheduledForDeletion) {
                $this->pDDebatesScheduledForDeletion = clone $this->collPDDebates;
                $this->pDDebatesScheduledForDeletion->clear();
            }
            $this->pDDebatesScheduledForDeletion[]= $pDDebate;
            $pDDebate->setPUser(null);
        }

        return $this;
    }

    /**
     * Clears out the collPDDComments collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return PUser The current object (for fluent API support)
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
     * If this PUser is new, it will return
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
                    ->filterByPUser($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collPDDCommentsPartial && count($collPDDComments)) {
                      $this->initPDDComments(false);

                      foreach($collPDDComments as $obj) {
                        if (false == $this->collPDDComments->contains($obj)) {
                          $this->collPDDComments->append($obj);
                        }
                      }

                      $this->collPDDCommentsPartial = true;
                    }

                    $collPDDComments->getInternalIterator()->rewind();
                    return $collPDDComments;
                }

                if($partial && $this->collPDDComments) {
                    foreach($this->collPDDComments as $obj) {
                        if($obj->isNew()) {
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
     * @return PUser The current object (for fluent API support)
     */
    public function setPDDComments(PropelCollection $pDDComments, PropelPDO $con = null)
    {
        $pDDCommentsToDelete = $this->getPDDComments(new Criteria(), $con)->diff($pDDComments);

        $this->pDDCommentsScheduledForDeletion = unserialize(serialize($pDDCommentsToDelete));

        foreach ($pDDCommentsToDelete as $pDDCommentRemoved) {
            $pDDCommentRemoved->setPUser(null);
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

            if($partial && !$criteria) {
                return count($this->getPDDComments());
            }
            $query = PDDCommentQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPUser($this)
                ->count($con);
        }

        return count($this->collPDDComments);
    }

    /**
     * Method called to associate a PDDComment object to this object
     * through the PDDComment foreign key attribute.
     *
     * @param    PDDComment $l PDDComment
     * @return PUser The current object (for fluent API support)
     */
    public function addPDDComment(PDDComment $l)
    {
        if ($this->collPDDComments === null) {
            $this->initPDDComments();
            $this->collPDDCommentsPartial = true;
        }
        if (!in_array($l, $this->collPDDComments->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddPDDComment($l);
        }

        return $this;
    }

    /**
     * @param	PDDComment $pDDComment The pDDComment object to add.
     */
    protected function doAddPDDComment($pDDComment)
    {
        $this->collPDDComments[]= $pDDComment;
        $pDDComment->setPUser($this);
    }

    /**
     * @param	PDDComment $pDDComment The pDDComment object to remove.
     * @return PUser The current object (for fluent API support)
     */
    public function removePDDComment($pDDComment)
    {
        if ($this->getPDDComments()->contains($pDDComment)) {
            $this->collPDDComments->remove($this->collPDDComments->search($pDDComment));
            if (null === $this->pDDCommentsScheduledForDeletion) {
                $this->pDDCommentsScheduledForDeletion = clone $this->collPDDComments;
                $this->pDDCommentsScheduledForDeletion->clear();
            }
            $this->pDDCommentsScheduledForDeletion[]= $pDDComment;
            $pDDComment->setPUser(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this PUser is new, it will return
     * an empty collection; or if this PUser has previously
     * been saved, it will retrieve related PDDComments from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in PUser.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|PDDComment[] List of PDDComment objects
     */
    public function getPDDCommentsJoinPDDebate($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PDDCommentQuery::create(null, $criteria);
        $query->joinWith('PDDebate', $join_behavior);

        return $this->getPDDComments($query, $con);
    }

    /**
     * Clears out the collPDReactions collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return PUser The current object (for fluent API support)
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
     * If this PUser is new, it will return
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
                    ->filterByPUser($this)
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
     * @return PUser The current object (for fluent API support)
     */
    public function setPDReactions(PropelCollection $pDReactions, PropelPDO $con = null)
    {
        $pDReactionsToDelete = $this->getPDReactions(new Criteria(), $con)->diff($pDReactions);

        $this->pDReactionsScheduledForDeletion = unserialize(serialize($pDReactionsToDelete));

        foreach ($pDReactionsToDelete as $pDReactionRemoved) {
            $pDReactionRemoved->setPUser(null);
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
                ->filterByPUser($this)
                ->count($con);
        }

        return count($this->collPDReactions);
    }

    /**
     * Method called to associate a PDReaction object to this object
     * through the PDReaction foreign key attribute.
     *
     * @param    PDReaction $l PDReaction
     * @return PUser The current object (for fluent API support)
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
        $pDReaction->setPUser($this);
    }

    /**
     * @param	PDReaction $pDReaction The pDReaction object to remove.
     * @return PUser The current object (for fluent API support)
     */
    public function removePDReaction($pDReaction)
    {
        if ($this->getPDReactions()->contains($pDReaction)) {
            $this->collPDReactions->remove($this->collPDReactions->search($pDReaction));
            if (null === $this->pDReactionsScheduledForDeletion) {
                $this->pDReactionsScheduledForDeletion = clone $this->collPDReactions;
                $this->pDReactionsScheduledForDeletion->clear();
            }
            $this->pDReactionsScheduledForDeletion[]= $pDReaction;
            $pDReaction->setPUser(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this PUser is new, it will return
     * an empty collection; or if this PUser has previously
     * been saved, it will retrieve related PDReactions from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in PUser.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|PDReaction[] List of PDReaction objects
     */
    public function getPDReactionsJoinPDDebate($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PDReactionQuery::create(null, $criteria);
        $query->joinWith('PDDebate', $join_behavior);

        return $this->getPDReactions($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this PUser is new, it will return
     * an empty collection; or if this PUser has previously
     * been saved, it will retrieve related PDReactions from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in PUser.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|PDReaction[] List of PDReaction objects
     */
    public function getPDReactionsJoinPDReactionRelatedByPDReactionId($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PDReactionQuery::create(null, $criteria);
        $query->joinWith('PDReactionRelatedByPDReactionId', $join_behavior);

        return $this->getPDReactions($query, $con);
    }

    /**
     * Clears out the collPDRComments collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return PUser The current object (for fluent API support)
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
     * If this PUser is new, it will return
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
                    ->filterByPUser($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collPDRCommentsPartial && count($collPDRComments)) {
                      $this->initPDRComments(false);

                      foreach($collPDRComments as $obj) {
                        if (false == $this->collPDRComments->contains($obj)) {
                          $this->collPDRComments->append($obj);
                        }
                      }

                      $this->collPDRCommentsPartial = true;
                    }

                    $collPDRComments->getInternalIterator()->rewind();
                    return $collPDRComments;
                }

                if($partial && $this->collPDRComments) {
                    foreach($this->collPDRComments as $obj) {
                        if($obj->isNew()) {
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
     * @return PUser The current object (for fluent API support)
     */
    public function setPDRComments(PropelCollection $pDRComments, PropelPDO $con = null)
    {
        $pDRCommentsToDelete = $this->getPDRComments(new Criteria(), $con)->diff($pDRComments);

        $this->pDRCommentsScheduledForDeletion = unserialize(serialize($pDRCommentsToDelete));

        foreach ($pDRCommentsToDelete as $pDRCommentRemoved) {
            $pDRCommentRemoved->setPUser(null);
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

            if($partial && !$criteria) {
                return count($this->getPDRComments());
            }
            $query = PDRCommentQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPUser($this)
                ->count($con);
        }

        return count($this->collPDRComments);
    }

    /**
     * Method called to associate a PDRComment object to this object
     * through the PDRComment foreign key attribute.
     *
     * @param    PDRComment $l PDRComment
     * @return PUser The current object (for fluent API support)
     */
    public function addPDRComment(PDRComment $l)
    {
        if ($this->collPDRComments === null) {
            $this->initPDRComments();
            $this->collPDRCommentsPartial = true;
        }
        if (!in_array($l, $this->collPDRComments->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddPDRComment($l);
        }

        return $this;
    }

    /**
     * @param	PDRComment $pDRComment The pDRComment object to add.
     */
    protected function doAddPDRComment($pDRComment)
    {
        $this->collPDRComments[]= $pDRComment;
        $pDRComment->setPUser($this);
    }

    /**
     * @param	PDRComment $pDRComment The pDRComment object to remove.
     * @return PUser The current object (for fluent API support)
     */
    public function removePDRComment($pDRComment)
    {
        if ($this->getPDRComments()->contains($pDRComment)) {
            $this->collPDRComments->remove($this->collPDRComments->search($pDRComment));
            if (null === $this->pDRCommentsScheduledForDeletion) {
                $this->pDRCommentsScheduledForDeletion = clone $this->collPDRComments;
                $this->pDRCommentsScheduledForDeletion->clear();
            }
            $this->pDRCommentsScheduledForDeletion[]= $pDRComment;
            $pDRComment->setPUser(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this PUser is new, it will return
     * an empty collection; or if this PUser has previously
     * been saved, it will retrieve related PDRComments from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in PUser.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|PDRComment[] List of PDRComment objects
     */
    public function getPDRCommentsJoinPDReaction($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PDRCommentQuery::create(null, $criteria);
        $query->joinWith('PDReaction', $join_behavior);

        return $this->getPDRComments($query, $con);
    }

    /**
     * Clears out the collPUFollowUsRelatedByPUserId collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return PUser The current object (for fluent API support)
     * @see        addPUFollowUsRelatedByPUserId()
     */
    public function clearPUFollowUsRelatedByPUserId()
    {
        $this->collPUFollowUsRelatedByPUserId = null; // important to set this to null since that means it is uninitialized
        $this->collPUFollowUsRelatedByPUserIdPartial = null;

        return $this;
    }

    /**
     * reset is the collPUFollowUsRelatedByPUserId collection loaded partially
     *
     * @return void
     */
    public function resetPartialPUFollowUsRelatedByPUserId($v = true)
    {
        $this->collPUFollowUsRelatedByPUserIdPartial = $v;
    }

    /**
     * Initializes the collPUFollowUsRelatedByPUserId collection.
     *
     * By default this just sets the collPUFollowUsRelatedByPUserId collection to an empty array (like clearcollPUFollowUsRelatedByPUserId());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPUFollowUsRelatedByPUserId($overrideExisting = true)
    {
        if (null !== $this->collPUFollowUsRelatedByPUserId && !$overrideExisting) {
            return;
        }
        $this->collPUFollowUsRelatedByPUserId = new PropelObjectCollection();
        $this->collPUFollowUsRelatedByPUserId->setModel('PUFollowU');
    }

    /**
     * Gets an array of PUFollowU objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this PUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|PUFollowU[] List of PUFollowU objects
     * @throws PropelException
     */
    public function getPUFollowUsRelatedByPUserId($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collPUFollowUsRelatedByPUserIdPartial && !$this->isNew();
        if (null === $this->collPUFollowUsRelatedByPUserId || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPUFollowUsRelatedByPUserId) {
                // return empty collection
                $this->initPUFollowUsRelatedByPUserId();
            } else {
                $collPUFollowUsRelatedByPUserId = PUFollowUQuery::create(null, $criteria)
                    ->filterByPUserRelatedByPUserId($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collPUFollowUsRelatedByPUserIdPartial && count($collPUFollowUsRelatedByPUserId)) {
                      $this->initPUFollowUsRelatedByPUserId(false);

                      foreach($collPUFollowUsRelatedByPUserId as $obj) {
                        if (false == $this->collPUFollowUsRelatedByPUserId->contains($obj)) {
                          $this->collPUFollowUsRelatedByPUserId->append($obj);
                        }
                      }

                      $this->collPUFollowUsRelatedByPUserIdPartial = true;
                    }

                    $collPUFollowUsRelatedByPUserId->getInternalIterator()->rewind();
                    return $collPUFollowUsRelatedByPUserId;
                }

                if($partial && $this->collPUFollowUsRelatedByPUserId) {
                    foreach($this->collPUFollowUsRelatedByPUserId as $obj) {
                        if($obj->isNew()) {
                            $collPUFollowUsRelatedByPUserId[] = $obj;
                        }
                    }
                }

                $this->collPUFollowUsRelatedByPUserId = $collPUFollowUsRelatedByPUserId;
                $this->collPUFollowUsRelatedByPUserIdPartial = false;
            }
        }

        return $this->collPUFollowUsRelatedByPUserId;
    }

    /**
     * Sets a collection of PUFollowURelatedByPUserId objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $pUFollowUsRelatedByPUserId A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return PUser The current object (for fluent API support)
     */
    public function setPUFollowUsRelatedByPUserId(PropelCollection $pUFollowUsRelatedByPUserId, PropelPDO $con = null)
    {
        $pUFollowUsRelatedByPUserIdToDelete = $this->getPUFollowUsRelatedByPUserId(new Criteria(), $con)->diff($pUFollowUsRelatedByPUserId);

        $this->pUFollowUsRelatedByPUserIdScheduledForDeletion = unserialize(serialize($pUFollowUsRelatedByPUserIdToDelete));

        foreach ($pUFollowUsRelatedByPUserIdToDelete as $pUFollowURelatedByPUserIdRemoved) {
            $pUFollowURelatedByPUserIdRemoved->setPUserRelatedByPUserId(null);
        }

        $this->collPUFollowUsRelatedByPUserId = null;
        foreach ($pUFollowUsRelatedByPUserId as $pUFollowURelatedByPUserId) {
            $this->addPUFollowURelatedByPUserId($pUFollowURelatedByPUserId);
        }

        $this->collPUFollowUsRelatedByPUserId = $pUFollowUsRelatedByPUserId;
        $this->collPUFollowUsRelatedByPUserIdPartial = false;

        return $this;
    }

    /**
     * Returns the number of related PUFollowU objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related PUFollowU objects.
     * @throws PropelException
     */
    public function countPUFollowUsRelatedByPUserId(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collPUFollowUsRelatedByPUserIdPartial && !$this->isNew();
        if (null === $this->collPUFollowUsRelatedByPUserId || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPUFollowUsRelatedByPUserId) {
                return 0;
            }

            if($partial && !$criteria) {
                return count($this->getPUFollowUsRelatedByPUserId());
            }
            $query = PUFollowUQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPUserRelatedByPUserId($this)
                ->count($con);
        }

        return count($this->collPUFollowUsRelatedByPUserId);
    }

    /**
     * Method called to associate a PUFollowU object to this object
     * through the PUFollowU foreign key attribute.
     *
     * @param    PUFollowU $l PUFollowU
     * @return PUser The current object (for fluent API support)
     */
    public function addPUFollowURelatedByPUserId(PUFollowU $l)
    {
        if ($this->collPUFollowUsRelatedByPUserId === null) {
            $this->initPUFollowUsRelatedByPUserId();
            $this->collPUFollowUsRelatedByPUserIdPartial = true;
        }
        if (!in_array($l, $this->collPUFollowUsRelatedByPUserId->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddPUFollowURelatedByPUserId($l);
        }

        return $this;
    }

    /**
     * @param	PUFollowURelatedByPUserId $pUFollowURelatedByPUserId The pUFollowURelatedByPUserId object to add.
     */
    protected function doAddPUFollowURelatedByPUserId($pUFollowURelatedByPUserId)
    {
        $this->collPUFollowUsRelatedByPUserId[]= $pUFollowURelatedByPUserId;
        $pUFollowURelatedByPUserId->setPUserRelatedByPUserId($this);
    }

    /**
     * @param	PUFollowURelatedByPUserId $pUFollowURelatedByPUserId The pUFollowURelatedByPUserId object to remove.
     * @return PUser The current object (for fluent API support)
     */
    public function removePUFollowURelatedByPUserId($pUFollowURelatedByPUserId)
    {
        if ($this->getPUFollowUsRelatedByPUserId()->contains($pUFollowURelatedByPUserId)) {
            $this->collPUFollowUsRelatedByPUserId->remove($this->collPUFollowUsRelatedByPUserId->search($pUFollowURelatedByPUserId));
            if (null === $this->pUFollowUsRelatedByPUserIdScheduledForDeletion) {
                $this->pUFollowUsRelatedByPUserIdScheduledForDeletion = clone $this->collPUFollowUsRelatedByPUserId;
                $this->pUFollowUsRelatedByPUserIdScheduledForDeletion->clear();
            }
            $this->pUFollowUsRelatedByPUserIdScheduledForDeletion[]= clone $pUFollowURelatedByPUserId;
            $pUFollowURelatedByPUserId->setPUserRelatedByPUserId(null);
        }

        return $this;
    }

    /**
     * Clears out the collPUFollowUsRelatedByPUserFollowerId collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return PUser The current object (for fluent API support)
     * @see        addPUFollowUsRelatedByPUserFollowerId()
     */
    public function clearPUFollowUsRelatedByPUserFollowerId()
    {
        $this->collPUFollowUsRelatedByPUserFollowerId = null; // important to set this to null since that means it is uninitialized
        $this->collPUFollowUsRelatedByPUserFollowerIdPartial = null;

        return $this;
    }

    /**
     * reset is the collPUFollowUsRelatedByPUserFollowerId collection loaded partially
     *
     * @return void
     */
    public function resetPartialPUFollowUsRelatedByPUserFollowerId($v = true)
    {
        $this->collPUFollowUsRelatedByPUserFollowerIdPartial = $v;
    }

    /**
     * Initializes the collPUFollowUsRelatedByPUserFollowerId collection.
     *
     * By default this just sets the collPUFollowUsRelatedByPUserFollowerId collection to an empty array (like clearcollPUFollowUsRelatedByPUserFollowerId());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPUFollowUsRelatedByPUserFollowerId($overrideExisting = true)
    {
        if (null !== $this->collPUFollowUsRelatedByPUserFollowerId && !$overrideExisting) {
            return;
        }
        $this->collPUFollowUsRelatedByPUserFollowerId = new PropelObjectCollection();
        $this->collPUFollowUsRelatedByPUserFollowerId->setModel('PUFollowU');
    }

    /**
     * Gets an array of PUFollowU objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this PUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|PUFollowU[] List of PUFollowU objects
     * @throws PropelException
     */
    public function getPUFollowUsRelatedByPUserFollowerId($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collPUFollowUsRelatedByPUserFollowerIdPartial && !$this->isNew();
        if (null === $this->collPUFollowUsRelatedByPUserFollowerId || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPUFollowUsRelatedByPUserFollowerId) {
                // return empty collection
                $this->initPUFollowUsRelatedByPUserFollowerId();
            } else {
                $collPUFollowUsRelatedByPUserFollowerId = PUFollowUQuery::create(null, $criteria)
                    ->filterByPUserRelatedByPUserFollowerId($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collPUFollowUsRelatedByPUserFollowerIdPartial && count($collPUFollowUsRelatedByPUserFollowerId)) {
                      $this->initPUFollowUsRelatedByPUserFollowerId(false);

                      foreach($collPUFollowUsRelatedByPUserFollowerId as $obj) {
                        if (false == $this->collPUFollowUsRelatedByPUserFollowerId->contains($obj)) {
                          $this->collPUFollowUsRelatedByPUserFollowerId->append($obj);
                        }
                      }

                      $this->collPUFollowUsRelatedByPUserFollowerIdPartial = true;
                    }

                    $collPUFollowUsRelatedByPUserFollowerId->getInternalIterator()->rewind();
                    return $collPUFollowUsRelatedByPUserFollowerId;
                }

                if($partial && $this->collPUFollowUsRelatedByPUserFollowerId) {
                    foreach($this->collPUFollowUsRelatedByPUserFollowerId as $obj) {
                        if($obj->isNew()) {
                            $collPUFollowUsRelatedByPUserFollowerId[] = $obj;
                        }
                    }
                }

                $this->collPUFollowUsRelatedByPUserFollowerId = $collPUFollowUsRelatedByPUserFollowerId;
                $this->collPUFollowUsRelatedByPUserFollowerIdPartial = false;
            }
        }

        return $this->collPUFollowUsRelatedByPUserFollowerId;
    }

    /**
     * Sets a collection of PUFollowURelatedByPUserFollowerId objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $pUFollowUsRelatedByPUserFollowerId A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return PUser The current object (for fluent API support)
     */
    public function setPUFollowUsRelatedByPUserFollowerId(PropelCollection $pUFollowUsRelatedByPUserFollowerId, PropelPDO $con = null)
    {
        $pUFollowUsRelatedByPUserFollowerIdToDelete = $this->getPUFollowUsRelatedByPUserFollowerId(new Criteria(), $con)->diff($pUFollowUsRelatedByPUserFollowerId);

        $this->pUFollowUsRelatedByPUserFollowerIdScheduledForDeletion = unserialize(serialize($pUFollowUsRelatedByPUserFollowerIdToDelete));

        foreach ($pUFollowUsRelatedByPUserFollowerIdToDelete as $pUFollowURelatedByPUserFollowerIdRemoved) {
            $pUFollowURelatedByPUserFollowerIdRemoved->setPUserRelatedByPUserFollowerId(null);
        }

        $this->collPUFollowUsRelatedByPUserFollowerId = null;
        foreach ($pUFollowUsRelatedByPUserFollowerId as $pUFollowURelatedByPUserFollowerId) {
            $this->addPUFollowURelatedByPUserFollowerId($pUFollowURelatedByPUserFollowerId);
        }

        $this->collPUFollowUsRelatedByPUserFollowerId = $pUFollowUsRelatedByPUserFollowerId;
        $this->collPUFollowUsRelatedByPUserFollowerIdPartial = false;

        return $this;
    }

    /**
     * Returns the number of related PUFollowU objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related PUFollowU objects.
     * @throws PropelException
     */
    public function countPUFollowUsRelatedByPUserFollowerId(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collPUFollowUsRelatedByPUserFollowerIdPartial && !$this->isNew();
        if (null === $this->collPUFollowUsRelatedByPUserFollowerId || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPUFollowUsRelatedByPUserFollowerId) {
                return 0;
            }

            if($partial && !$criteria) {
                return count($this->getPUFollowUsRelatedByPUserFollowerId());
            }
            $query = PUFollowUQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPUserRelatedByPUserFollowerId($this)
                ->count($con);
        }

        return count($this->collPUFollowUsRelatedByPUserFollowerId);
    }

    /**
     * Method called to associate a PUFollowU object to this object
     * through the PUFollowU foreign key attribute.
     *
     * @param    PUFollowU $l PUFollowU
     * @return PUser The current object (for fluent API support)
     */
    public function addPUFollowURelatedByPUserFollowerId(PUFollowU $l)
    {
        if ($this->collPUFollowUsRelatedByPUserFollowerId === null) {
            $this->initPUFollowUsRelatedByPUserFollowerId();
            $this->collPUFollowUsRelatedByPUserFollowerIdPartial = true;
        }
        if (!in_array($l, $this->collPUFollowUsRelatedByPUserFollowerId->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddPUFollowURelatedByPUserFollowerId($l);
        }

        return $this;
    }

    /**
     * @param	PUFollowURelatedByPUserFollowerId $pUFollowURelatedByPUserFollowerId The pUFollowURelatedByPUserFollowerId object to add.
     */
    protected function doAddPUFollowURelatedByPUserFollowerId($pUFollowURelatedByPUserFollowerId)
    {
        $this->collPUFollowUsRelatedByPUserFollowerId[]= $pUFollowURelatedByPUserFollowerId;
        $pUFollowURelatedByPUserFollowerId->setPUserRelatedByPUserFollowerId($this);
    }

    /**
     * @param	PUFollowURelatedByPUserFollowerId $pUFollowURelatedByPUserFollowerId The pUFollowURelatedByPUserFollowerId object to remove.
     * @return PUser The current object (for fluent API support)
     */
    public function removePUFollowURelatedByPUserFollowerId($pUFollowURelatedByPUserFollowerId)
    {
        if ($this->getPUFollowUsRelatedByPUserFollowerId()->contains($pUFollowURelatedByPUserFollowerId)) {
            $this->collPUFollowUsRelatedByPUserFollowerId->remove($this->collPUFollowUsRelatedByPUserFollowerId->search($pUFollowURelatedByPUserFollowerId));
            if (null === $this->pUFollowUsRelatedByPUserFollowerIdScheduledForDeletion) {
                $this->pUFollowUsRelatedByPUserFollowerIdScheduledForDeletion = clone $this->collPUFollowUsRelatedByPUserFollowerId;
                $this->pUFollowUsRelatedByPUserFollowerIdScheduledForDeletion->clear();
            }
            $this->pUFollowUsRelatedByPUserFollowerIdScheduledForDeletion[]= clone $pUFollowURelatedByPUserFollowerId;
            $pUFollowURelatedByPUserFollowerId->setPUserRelatedByPUserFollowerId(null);
        }

        return $this;
    }

    /**
     * Clears out the collPuFollowDdPDDebates collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return PUser The current object (for fluent API support)
     * @see        addPuFollowDdPDDebates()
     */
    public function clearPuFollowDdPDDebates()
    {
        $this->collPuFollowDdPDDebates = null; // important to set this to null since that means it is uninitialized
        $this->collPuFollowDdPDDebatesPartial = null;

        return $this;
    }

    /**
     * Initializes the collPuFollowDdPDDebates collection.
     *
     * By default this just sets the collPuFollowDdPDDebates collection to an empty collection (like clearPuFollowDdPDDebates());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @return void
     */
    public function initPuFollowDdPDDebates()
    {
        $this->collPuFollowDdPDDebates = new PropelObjectCollection();
        $this->collPuFollowDdPDDebates->setModel('PDDebate');
    }

    /**
     * Gets a collection of PDDebate objects related by a many-to-many relationship
     * to the current object by way of the p_u_follow_d_d cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this PUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria Optional query object to filter the query
     * @param PropelPDO $con Optional connection object
     *
     * @return PropelObjectCollection|PDDebate[] List of PDDebate objects
     */
    public function getPuFollowDdPDDebates($criteria = null, PropelPDO $con = null)
    {
        if (null === $this->collPuFollowDdPDDebates || null !== $criteria) {
            if ($this->isNew() && null === $this->collPuFollowDdPDDebates) {
                // return empty collection
                $this->initPuFollowDdPDDebates();
            } else {
                $collPuFollowDdPDDebates = PDDebateQuery::create(null, $criteria)
                    ->filterByPuFollowDdPUser($this)
                    ->find($con);
                if (null !== $criteria) {
                    return $collPuFollowDdPDDebates;
                }
                $this->collPuFollowDdPDDebates = $collPuFollowDdPDDebates;
            }
        }

        return $this->collPuFollowDdPDDebates;
    }

    /**
     * Sets a collection of PDDebate objects related by a many-to-many relationship
     * to the current object by way of the p_u_follow_d_d cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $puFollowDdPDDebates A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return PUser The current object (for fluent API support)
     */
    public function setPuFollowDdPDDebates(PropelCollection $puFollowDdPDDebates, PropelPDO $con = null)
    {
        $this->clearPuFollowDdPDDebates();
        $currentPuFollowDdPDDebates = $this->getPuFollowDdPDDebates();

        $this->puFollowDdPDDebatesScheduledForDeletion = $currentPuFollowDdPDDebates->diff($puFollowDdPDDebates);

        foreach ($puFollowDdPDDebates as $puFollowDdPDDebate) {
            if (!$currentPuFollowDdPDDebates->contains($puFollowDdPDDebate)) {
                $this->doAddPuFollowDdPDDebate($puFollowDdPDDebate);
            }
        }

        $this->collPuFollowDdPDDebates = $puFollowDdPDDebates;

        return $this;
    }

    /**
     * Gets the number of PDDebate objects related by a many-to-many relationship
     * to the current object by way of the p_u_follow_d_d cross-reference table.
     *
     * @param Criteria $criteria Optional query object to filter the query
     * @param boolean $distinct Set to true to force count distinct
     * @param PropelPDO $con Optional connection object
     *
     * @return int the number of related PDDebate objects
     */
    public function countPuFollowDdPDDebates($criteria = null, $distinct = false, PropelPDO $con = null)
    {
        if (null === $this->collPuFollowDdPDDebates || null !== $criteria) {
            if ($this->isNew() && null === $this->collPuFollowDdPDDebates) {
                return 0;
            } else {
                $query = PDDebateQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByPuFollowDdPUser($this)
                    ->count($con);
            }
        } else {
            return count($this->collPuFollowDdPDDebates);
        }
    }

    /**
     * Associate a PDDebate object to this object
     * through the p_u_follow_d_d cross reference table.
     *
     * @param  PDDebate $pDDebate The PUFollowDD object to relate
     * @return PUser The current object (for fluent API support)
     */
    public function addPuFollowDdPDDebate(PDDebate $pDDebate)
    {
        if ($this->collPuFollowDdPDDebates === null) {
            $this->initPuFollowDdPDDebates();
        }
        if (!$this->collPuFollowDdPDDebates->contains($pDDebate)) { // only add it if the **same** object is not already associated
            $this->doAddPuFollowDdPDDebate($pDDebate);

            $this->collPuFollowDdPDDebates[]= $pDDebate;
        }

        return $this;
    }

    /**
     * @param	PuFollowDdPDDebate $puFollowDdPDDebate The puFollowDdPDDebate object to add.
     */
    protected function doAddPuFollowDdPDDebate($puFollowDdPDDebate)
    {
        $pUFollowDD = new PUFollowDD();
        $pUFollowDD->setPuFollowDdPDDebate($puFollowDdPDDebate);
        $this->addPuFollowDdPUser($pUFollowDD);
    }

    /**
     * Remove a PDDebate object to this object
     * through the p_u_follow_d_d cross reference table.
     *
     * @param PDDebate $pDDebate The PUFollowDD object to relate
     * @return PUser The current object (for fluent API support)
     */
    public function removePuFollowDdPDDebate(PDDebate $pDDebate)
    {
        if ($this->getPuFollowDdPDDebates()->contains($pDDebate)) {
            $this->collPuFollowDdPDDebates->remove($this->collPuFollowDdPDDebates->search($pDDebate));
            if (null === $this->puFollowDdPDDebatesScheduledForDeletion) {
                $this->puFollowDdPDDebatesScheduledForDeletion = clone $this->collPuFollowDdPDDebates;
                $this->puFollowDdPDDebatesScheduledForDeletion->clear();
            }
            $this->puFollowDdPDDebatesScheduledForDeletion[]= $pDDebate;
        }

        return $this;
    }

    /**
     * Clears out the collPuReputationRbPRBadges collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return PUser The current object (for fluent API support)
     * @see        addPuReputationRbPRBadges()
     */
    public function clearPuReputationRbPRBadges()
    {
        $this->collPuReputationRbPRBadges = null; // important to set this to null since that means it is uninitialized
        $this->collPuReputationRbPRBadgesPartial = null;

        return $this;
    }

    /**
     * Initializes the collPuReputationRbPRBadges collection.
     *
     * By default this just sets the collPuReputationRbPRBadges collection to an empty collection (like clearPuReputationRbPRBadges());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @return void
     */
    public function initPuReputationRbPRBadges()
    {
        $this->collPuReputationRbPRBadges = new PropelObjectCollection();
        $this->collPuReputationRbPRBadges->setModel('PRBadge');
    }

    /**
     * Gets a collection of PRBadge objects related by a many-to-many relationship
     * to the current object by way of the p_u_reputation_r_b cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this PUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria Optional query object to filter the query
     * @param PropelPDO $con Optional connection object
     *
     * @return PropelObjectCollection|PRBadge[] List of PRBadge objects
     */
    public function getPuReputationRbPRBadges($criteria = null, PropelPDO $con = null)
    {
        if (null === $this->collPuReputationRbPRBadges || null !== $criteria) {
            if ($this->isNew() && null === $this->collPuReputationRbPRBadges) {
                // return empty collection
                $this->initPuReputationRbPRBadges();
            } else {
                $collPuReputationRbPRBadges = PRBadgeQuery::create(null, $criteria)
                    ->filterByPuReputationRbPUser($this)
                    ->find($con);
                if (null !== $criteria) {
                    return $collPuReputationRbPRBadges;
                }
                $this->collPuReputationRbPRBadges = $collPuReputationRbPRBadges;
            }
        }

        return $this->collPuReputationRbPRBadges;
    }

    /**
     * Sets a collection of PRBadge objects related by a many-to-many relationship
     * to the current object by way of the p_u_reputation_r_b cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $puReputationRbPRBadges A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return PUser The current object (for fluent API support)
     */
    public function setPuReputationRbPRBadges(PropelCollection $puReputationRbPRBadges, PropelPDO $con = null)
    {
        $this->clearPuReputationRbPRBadges();
        $currentPuReputationRbPRBadges = $this->getPuReputationRbPRBadges();

        $this->puReputationRbPRBadgesScheduledForDeletion = $currentPuReputationRbPRBadges->diff($puReputationRbPRBadges);

        foreach ($puReputationRbPRBadges as $puReputationRbPRBadge) {
            if (!$currentPuReputationRbPRBadges->contains($puReputationRbPRBadge)) {
                $this->doAddPuReputationRbPRBadge($puReputationRbPRBadge);
            }
        }

        $this->collPuReputationRbPRBadges = $puReputationRbPRBadges;

        return $this;
    }

    /**
     * Gets the number of PRBadge objects related by a many-to-many relationship
     * to the current object by way of the p_u_reputation_r_b cross-reference table.
     *
     * @param Criteria $criteria Optional query object to filter the query
     * @param boolean $distinct Set to true to force count distinct
     * @param PropelPDO $con Optional connection object
     *
     * @return int the number of related PRBadge objects
     */
    public function countPuReputationRbPRBadges($criteria = null, $distinct = false, PropelPDO $con = null)
    {
        if (null === $this->collPuReputationRbPRBadges || null !== $criteria) {
            if ($this->isNew() && null === $this->collPuReputationRbPRBadges) {
                return 0;
            } else {
                $query = PRBadgeQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByPuReputationRbPUser($this)
                    ->count($con);
            }
        } else {
            return count($this->collPuReputationRbPRBadges);
        }
    }

    /**
     * Associate a PRBadge object to this object
     * through the p_u_reputation_r_b cross reference table.
     *
     * @param  PRBadge $pRBadge The PUReputationRB object to relate
     * @return PUser The current object (for fluent API support)
     */
    public function addPuReputationRbPRBadge(PRBadge $pRBadge)
    {
        if ($this->collPuReputationRbPRBadges === null) {
            $this->initPuReputationRbPRBadges();
        }
        if (!$this->collPuReputationRbPRBadges->contains($pRBadge)) { // only add it if the **same** object is not already associated
            $this->doAddPuReputationRbPRBadge($pRBadge);

            $this->collPuReputationRbPRBadges[]= $pRBadge;
        }

        return $this;
    }

    /**
     * @param	PuReputationRbPRBadge $puReputationRbPRBadge The puReputationRbPRBadge object to add.
     */
    protected function doAddPuReputationRbPRBadge($puReputationRbPRBadge)
    {
        $pUReputationRB = new PUReputationRB();
        $pUReputationRB->setPuReputationRbPRBadge($puReputationRbPRBadge);
        $this->addPuReputationRbPUser($pUReputationRB);
    }

    /**
     * Remove a PRBadge object to this object
     * through the p_u_reputation_r_b cross reference table.
     *
     * @param PRBadge $pRBadge The PUReputationRB object to relate
     * @return PUser The current object (for fluent API support)
     */
    public function removePuReputationRbPRBadge(PRBadge $pRBadge)
    {
        if ($this->getPuReputationRbPRBadges()->contains($pRBadge)) {
            $this->collPuReputationRbPRBadges->remove($this->collPuReputationRbPRBadges->search($pRBadge));
            if (null === $this->puReputationRbPRBadgesScheduledForDeletion) {
                $this->puReputationRbPRBadgesScheduledForDeletion = clone $this->collPuReputationRbPRBadges;
                $this->puReputationRbPRBadgesScheduledForDeletion->clear();
            }
            $this->puReputationRbPRBadgesScheduledForDeletion[]= $pRBadge;
        }

        return $this;
    }

    /**
     * Clears out the collPuReputationRaPRBadges collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return PUser The current object (for fluent API support)
     * @see        addPuReputationRaPRBadges()
     */
    public function clearPuReputationRaPRBadges()
    {
        $this->collPuReputationRaPRBadges = null; // important to set this to null since that means it is uninitialized
        $this->collPuReputationRaPRBadgesPartial = null;

        return $this;
    }

    /**
     * Initializes the collPuReputationRaPRBadges collection.
     *
     * By default this just sets the collPuReputationRaPRBadges collection to an empty collection (like clearPuReputationRaPRBadges());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @return void
     */
    public function initPuReputationRaPRBadges()
    {
        $this->collPuReputationRaPRBadges = new PropelObjectCollection();
        $this->collPuReputationRaPRBadges->setModel('PRAction');
    }

    /**
     * Gets a collection of PRAction objects related by a many-to-many relationship
     * to the current object by way of the p_u_reputation_r_a cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this PUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria Optional query object to filter the query
     * @param PropelPDO $con Optional connection object
     *
     * @return PropelObjectCollection|PRAction[] List of PRAction objects
     */
    public function getPuReputationRaPRBadges($criteria = null, PropelPDO $con = null)
    {
        if (null === $this->collPuReputationRaPRBadges || null !== $criteria) {
            if ($this->isNew() && null === $this->collPuReputationRaPRBadges) {
                // return empty collection
                $this->initPuReputationRaPRBadges();
            } else {
                $collPuReputationRaPRBadges = PRActionQuery::create(null, $criteria)
                    ->filterByPuReputationRaPUser($this)
                    ->find($con);
                if (null !== $criteria) {
                    return $collPuReputationRaPRBadges;
                }
                $this->collPuReputationRaPRBadges = $collPuReputationRaPRBadges;
            }
        }

        return $this->collPuReputationRaPRBadges;
    }

    /**
     * Sets a collection of PRAction objects related by a many-to-many relationship
     * to the current object by way of the p_u_reputation_r_a cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $puReputationRaPRBadges A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return PUser The current object (for fluent API support)
     */
    public function setPuReputationRaPRBadges(PropelCollection $puReputationRaPRBadges, PropelPDO $con = null)
    {
        $this->clearPuReputationRaPRBadges();
        $currentPuReputationRaPRBadges = $this->getPuReputationRaPRBadges();

        $this->puReputationRaPRBadgesScheduledForDeletion = $currentPuReputationRaPRBadges->diff($puReputationRaPRBadges);

        foreach ($puReputationRaPRBadges as $puReputationRaPRBadge) {
            if (!$currentPuReputationRaPRBadges->contains($puReputationRaPRBadge)) {
                $this->doAddPuReputationRaPRBadge($puReputationRaPRBadge);
            }
        }

        $this->collPuReputationRaPRBadges = $puReputationRaPRBadges;

        return $this;
    }

    /**
     * Gets the number of PRAction objects related by a many-to-many relationship
     * to the current object by way of the p_u_reputation_r_a cross-reference table.
     *
     * @param Criteria $criteria Optional query object to filter the query
     * @param boolean $distinct Set to true to force count distinct
     * @param PropelPDO $con Optional connection object
     *
     * @return int the number of related PRAction objects
     */
    public function countPuReputationRaPRBadges($criteria = null, $distinct = false, PropelPDO $con = null)
    {
        if (null === $this->collPuReputationRaPRBadges || null !== $criteria) {
            if ($this->isNew() && null === $this->collPuReputationRaPRBadges) {
                return 0;
            } else {
                $query = PRActionQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByPuReputationRaPUser($this)
                    ->count($con);
            }
        } else {
            return count($this->collPuReputationRaPRBadges);
        }
    }

    /**
     * Associate a PRAction object to this object
     * through the p_u_reputation_r_a cross reference table.
     *
     * @param  PRAction $pRAction The PUReputationRA object to relate
     * @return PUser The current object (for fluent API support)
     */
    public function addPuReputationRaPRBadge(PRAction $pRAction)
    {
        if ($this->collPuReputationRaPRBadges === null) {
            $this->initPuReputationRaPRBadges();
        }
        if (!$this->collPuReputationRaPRBadges->contains($pRAction)) { // only add it if the **same** object is not already associated
            $this->doAddPuReputationRaPRBadge($pRAction);

            $this->collPuReputationRaPRBadges[]= $pRAction;
        }

        return $this;
    }

    /**
     * @param	PuReputationRaPRBadge $puReputationRaPRBadge The puReputationRaPRBadge object to add.
     */
    protected function doAddPuReputationRaPRBadge($puReputationRaPRBadge)
    {
        $pUReputationRA = new PUReputationRA();
        $pUReputationRA->setPuReputationRaPRBadge($puReputationRaPRBadge);
        $this->addPuReputationRaPUser($pUReputationRA);
    }

    /**
     * Remove a PRAction object to this object
     * through the p_u_reputation_r_a cross reference table.
     *
     * @param PRAction $pRAction The PUReputationRA object to relate
     * @return PUser The current object (for fluent API support)
     */
    public function removePuReputationRaPRBadge(PRAction $pRAction)
    {
        if ($this->getPuReputationRaPRBadges()->contains($pRAction)) {
            $this->collPuReputationRaPRBadges->remove($this->collPuReputationRaPRBadges->search($pRAction));
            if (null === $this->puReputationRaPRBadgesScheduledForDeletion) {
                $this->puReputationRaPRBadgesScheduledForDeletion = clone $this->collPuReputationRaPRBadges;
                $this->puReputationRaPRBadgesScheduledForDeletion->clear();
            }
            $this->puReputationRaPRBadgesScheduledForDeletion[]= $pRAction;
        }

        return $this;
    }

    /**
     * Clears out the collPuTaggedTPTags collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return PUser The current object (for fluent API support)
     * @see        addPuTaggedTPTags()
     */
    public function clearPuTaggedTPTags()
    {
        $this->collPuTaggedTPTags = null; // important to set this to null since that means it is uninitialized
        $this->collPuTaggedTPTagsPartial = null;

        return $this;
    }

    /**
     * Initializes the collPuTaggedTPTags collection.
     *
     * By default this just sets the collPuTaggedTPTags collection to an empty collection (like clearPuTaggedTPTags());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @return void
     */
    public function initPuTaggedTPTags()
    {
        $this->collPuTaggedTPTags = new PropelObjectCollection();
        $this->collPuTaggedTPTags->setModel('PTag');
    }

    /**
     * Gets a collection of PTag objects related by a many-to-many relationship
     * to the current object by way of the p_u_tagged_t cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this PUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria Optional query object to filter the query
     * @param PropelPDO $con Optional connection object
     *
     * @return PropelObjectCollection|PTag[] List of PTag objects
     */
    public function getPuTaggedTPTags($criteria = null, PropelPDO $con = null)
    {
        if (null === $this->collPuTaggedTPTags || null !== $criteria) {
            if ($this->isNew() && null === $this->collPuTaggedTPTags) {
                // return empty collection
                $this->initPuTaggedTPTags();
            } else {
                $collPuTaggedTPTags = PTagQuery::create(null, $criteria)
                    ->filterByPuTaggedTPUser($this)
                    ->find($con);
                if (null !== $criteria) {
                    return $collPuTaggedTPTags;
                }
                $this->collPuTaggedTPTags = $collPuTaggedTPTags;
            }
        }

        return $this->collPuTaggedTPTags;
    }

    /**
     * Sets a collection of PTag objects related by a many-to-many relationship
     * to the current object by way of the p_u_tagged_t cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $puTaggedTPTags A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return PUser The current object (for fluent API support)
     */
    public function setPuTaggedTPTags(PropelCollection $puTaggedTPTags, PropelPDO $con = null)
    {
        $this->clearPuTaggedTPTags();
        $currentPuTaggedTPTags = $this->getPuTaggedTPTags();

        $this->puTaggedTPTagsScheduledForDeletion = $currentPuTaggedTPTags->diff($puTaggedTPTags);

        foreach ($puTaggedTPTags as $puTaggedTPTag) {
            if (!$currentPuTaggedTPTags->contains($puTaggedTPTag)) {
                $this->doAddPuTaggedTPTag($puTaggedTPTag);
            }
        }

        $this->collPuTaggedTPTags = $puTaggedTPTags;

        return $this;
    }

    /**
     * Gets the number of PTag objects related by a many-to-many relationship
     * to the current object by way of the p_u_tagged_t cross-reference table.
     *
     * @param Criteria $criteria Optional query object to filter the query
     * @param boolean $distinct Set to true to force count distinct
     * @param PropelPDO $con Optional connection object
     *
     * @return int the number of related PTag objects
     */
    public function countPuTaggedTPTags($criteria = null, $distinct = false, PropelPDO $con = null)
    {
        if (null === $this->collPuTaggedTPTags || null !== $criteria) {
            if ($this->isNew() && null === $this->collPuTaggedTPTags) {
                return 0;
            } else {
                $query = PTagQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByPuTaggedTPUser($this)
                    ->count($con);
            }
        } else {
            return count($this->collPuTaggedTPTags);
        }
    }

    /**
     * Associate a PTag object to this object
     * through the p_u_tagged_t cross reference table.
     *
     * @param  PTag $pTag The PUTaggedT object to relate
     * @return PUser The current object (for fluent API support)
     */
    public function addPuTaggedTPTag(PTag $pTag)
    {
        if ($this->collPuTaggedTPTags === null) {
            $this->initPuTaggedTPTags();
        }
        if (!$this->collPuTaggedTPTags->contains($pTag)) { // only add it if the **same** object is not already associated
            $this->doAddPuTaggedTPTag($pTag);

            $this->collPuTaggedTPTags[]= $pTag;
        }

        return $this;
    }

    /**
     * @param	PuTaggedTPTag $puTaggedTPTag The puTaggedTPTag object to add.
     */
    protected function doAddPuTaggedTPTag($puTaggedTPTag)
    {
        $pUTaggedT = new PUTaggedT();
        $pUTaggedT->setPuTaggedTPTag($puTaggedTPTag);
        $this->addPuTaggedTPUser($pUTaggedT);
    }

    /**
     * Remove a PTag object to this object
     * through the p_u_tagged_t cross reference table.
     *
     * @param PTag $pTag The PUTaggedT object to relate
     * @return PUser The current object (for fluent API support)
     */
    public function removePuTaggedTPTag(PTag $pTag)
    {
        if ($this->getPuTaggedTPTags()->contains($pTag)) {
            $this->collPuTaggedTPTags->remove($this->collPuTaggedTPTags->search($pTag));
            if (null === $this->puTaggedTPTagsScheduledForDeletion) {
                $this->puTaggedTPTagsScheduledForDeletion = clone $this->collPuTaggedTPTags;
                $this->puTaggedTPTagsScheduledForDeletion->clear();
            }
            $this->puTaggedTPTagsScheduledForDeletion[]= $pTag;
        }

        return $this;
    }

    /**
     * Clears the current object and sets all attributes to their default values
     */
    public function clear()
    {
        $this->id = null;
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
        $this->type = null;
        $this->status = null;
        $this->file_name = null;
        $this->gender = null;
        $this->firstname = null;
        $this->name = null;
        $this->birthday = null;
        $this->summary = null;
        $this->biography = null;
        $this->website = null;
        $this->twitter = null;
        $this->facebook = null;
        $this->phone = null;
        $this->newsletter = null;
        $this->last_connect = null;
        $this->online = null;
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
     * when using Propel in certain daemon or large-volumne/high-memory operations.
     *
     * @param boolean $deep Whether to also clear the references on all referrer objects.
     */
    public function clearAllReferences($deep = false)
    {
        if ($deep && !$this->alreadyInClearAllReferencesDeep) {
            $this->alreadyInClearAllReferencesDeep = true;
            if ($this->collPOrders) {
                foreach ($this->collPOrders as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPUQualifications) {
                foreach ($this->collPUQualifications as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPuFollowDdPUsers) {
                foreach ($this->collPuFollowDdPUsers as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPuReputationRbPUsers) {
                foreach ($this->collPuReputationRbPUsers as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPuReputationRaPUsers) {
                foreach ($this->collPuReputationRaPUsers as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPuTaggedTPUsers) {
                foreach ($this->collPuTaggedTPUsers as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPDDebates) {
                foreach ($this->collPDDebates as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPDDComments) {
                foreach ($this->collPDDComments as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPDReactions) {
                foreach ($this->collPDReactions as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPDRComments) {
                foreach ($this->collPDRComments as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPUFollowUsRelatedByPUserId) {
                foreach ($this->collPUFollowUsRelatedByPUserId as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPUFollowUsRelatedByPUserFollowerId) {
                foreach ($this->collPUFollowUsRelatedByPUserFollowerId as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPuFollowDdPDDebates) {
                foreach ($this->collPuFollowDdPDDebates as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPuReputationRbPRBadges) {
                foreach ($this->collPuReputationRbPRBadges as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPuReputationRaPRBadges) {
                foreach ($this->collPuReputationRaPRBadges as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPuTaggedTPTags) {
                foreach ($this->collPuTaggedTPTags as $o) {
                    $o->clearAllReferences($deep);
                }
            }

            $this->alreadyInClearAllReferencesDeep = false;
        } // if ($deep)

        // equal_nest_parent behavior

        if ($deep) {
            if ($this->collEqualNestPUFollowUs) {
                foreach ($this->collEqualNestPUFollowUs as $obj) {
                    $obj->clearAllReferences($deep);
                }
            }
        }

        $this->listEqualNestPUFollowUsPKs = null;
        $this->collEqualNestPUFollowUs = null;

        if ($this->collPOrders instanceof PropelCollection) {
            $this->collPOrders->clearIterator();
        }
        $this->collPOrders = null;
        if ($this->collPUQualifications instanceof PropelCollection) {
            $this->collPUQualifications->clearIterator();
        }
        $this->collPUQualifications = null;
        if ($this->collPuFollowDdPUsers instanceof PropelCollection) {
            $this->collPuFollowDdPUsers->clearIterator();
        }
        $this->collPuFollowDdPUsers = null;
        if ($this->collPuReputationRbPUsers instanceof PropelCollection) {
            $this->collPuReputationRbPUsers->clearIterator();
        }
        $this->collPuReputationRbPUsers = null;
        if ($this->collPuReputationRaPUsers instanceof PropelCollection) {
            $this->collPuReputationRaPUsers->clearIterator();
        }
        $this->collPuReputationRaPUsers = null;
        if ($this->collPuTaggedTPUsers instanceof PropelCollection) {
            $this->collPuTaggedTPUsers->clearIterator();
        }
        $this->collPuTaggedTPUsers = null;
        if ($this->collPDDebates instanceof PropelCollection) {
            $this->collPDDebates->clearIterator();
        }
        $this->collPDDebates = null;
        if ($this->collPDDComments instanceof PropelCollection) {
            $this->collPDDComments->clearIterator();
        }
        $this->collPDDComments = null;
        if ($this->collPDReactions instanceof PropelCollection) {
            $this->collPDReactions->clearIterator();
        }
        $this->collPDReactions = null;
        if ($this->collPDRComments instanceof PropelCollection) {
            $this->collPDRComments->clearIterator();
        }
        $this->collPDRComments = null;
        if ($this->collPUFollowUsRelatedByPUserId instanceof PropelCollection) {
            $this->collPUFollowUsRelatedByPUserId->clearIterator();
        }
        $this->collPUFollowUsRelatedByPUserId = null;
        if ($this->collPUFollowUsRelatedByPUserFollowerId instanceof PropelCollection) {
            $this->collPUFollowUsRelatedByPUserFollowerId->clearIterator();
        }
        $this->collPUFollowUsRelatedByPUserFollowerId = null;
        if ($this->collPuFollowDdPDDebates instanceof PropelCollection) {
            $this->collPuFollowDdPDDebates->clearIterator();
        }
        $this->collPuFollowDdPDDebates = null;
        if ($this->collPuReputationRbPRBadges instanceof PropelCollection) {
            $this->collPuReputationRbPRBadges->clearIterator();
        }
        $this->collPuReputationRbPRBadges = null;
        if ($this->collPuReputationRaPRBadges instanceof PropelCollection) {
            $this->collPuReputationRaPRBadges->clearIterator();
        }
        $this->collPuReputationRaPRBadges = null;
        if ($this->collPuTaggedTPTags instanceof PropelCollection) {
            $this->collPuTaggedTPTags->clearIterator();
        }
        $this->collPuTaggedTPTags = null;
    }

    /**
     * return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(PUserPeer::DEFAULT_STRING_FORMAT);
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
     * @return     PUser The current object (for fluent API support)
     */
    public function keepUpdateDateUnchanged()
    {
        $this->modifiedColumns[] = PUserPeer::UPDATED_AT;

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
        return '' . $this->cleanupSlugPart($this->getfirstname()) . '-' . $this->cleanupSlugPart($this->getname()) . '';
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

        $query = PUserQuery::create('q')
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

    // equal_nest_parent behavior

    /**
     * This function checks the local equal nest collection against the database
     * and creates new relations or deletes ones that have been removed
     *
     * @param PropelPDO $con
     */
    public function processEqualNestQueries(PropelPDO $con = null)
    {
        if (false === $this->alreadyInEqualNestProcessing && null !== $this->collEqualNestPUFollowUs) {
            $this->alreadyInEqualNestProcessing = true;

            if (null === $con) {
                $con = Propel::getConnection(PUserPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
            }

            $this->clearListPUFollowUsPKs();
            $this->initListPUFollowUsPKs($con);

            foreach ($this->collEqualNestPUFollowUs as $aPUFollowU) {
                if (!$aPUFollowU->isDeleted() && ($aPUFollowU->isNew() || $aPUFollowU->isModified())) {
                    $aPUFollowU->save($con);
                }
            }

            $con->beginTransaction();

            try {
                foreach ($this->getPUFollowUs()->getPrimaryKeys($usePrefix = false) as $columnKey => $pk) {
                    if (!in_array($pk, $this->listEqualNestPUFollowUsPKs)) {
                        // save new equal nest relation
                        PUFollowUPeer::buildEqualNestPUFollowURelation($this, $pk, $con);
                        // add this object to the sibling's collection
                        $this->getPUFollowUs()->get($columnKey)->addPUFollowU($this);
                    } else {
                        // remove the pk from the list of db keys
                        unset($this->listEqualNestPUFollowUsPKs[array_search($pk, $this->listEqualNestPUFollowUsPKs)]);
                    }
                }

                // if we have keys still left, this means they are relations that have to be removed
                foreach ($this->listEqualNestPUFollowUsPKs as $oldPk) {
                    PUFollowUPeer::removeEqualNestPUFollowURelation($this, $oldPk, $con);
                }

                $con->commit();
            } catch (PropelException $e) {
                $con->rollBack();
                throw $e;
            }

            $this->alreadyInEqualNestProcessing = false;
        }
    }

    /**
     * Clears out the list of Equal Nest PUFollowUs PKs
     */
    public function clearListPUFollowUsPKs()
    {
        $this->listEqualNestPUFollowUsPKs = null;
    }

    /**
     * Initializes the list of Equal Nest PUFollowUs PKs.
     *
     * This will query the database for Equal Nest PUFollowUs relations to this PUser object.
     * It will set the list to an empty array if the object is newly created.
     *
     * @param PropelPDO $con
     */
    protected function initListPUFollowUsPKs(PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(PUserPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        if (null === $this->listEqualNestPUFollowUsPKs) {
            if ($this->isNew()) {
                $this->listEqualNestPUFollowUsPKs = array();
            } else {
                $sql = "
    SELECT DISTINCT p_user.id
    FROM p_user
    INNER JOIN p_u_follow_u ON
    p_user.id = p_u_follow_u.p_user_id
    OR
    p_user.id = p_u_follow_u.p_user_follower_id
    WHERE
    p_user.id IN (
        SELECT p_u_follow_u.p_user_id
        FROM p_u_follow_u
        WHERE p_u_follow_u.p_user_follower_id = ?
    )
    OR
    p_user.id IN (
        SELECT p_u_follow_u.p_user_follower_id
        FROM p_u_follow_u
        WHERE p_u_follow_u.p_user_id = ?
    )";

                $stmt = $con->prepare($sql);
                $stmt->bindValue(1, $this->getPrimaryKey(), PDO::PARAM_INT);
                $stmt->bindValue(2, $this->getPrimaryKey(), PDO::PARAM_INT);
                $stmt->execute();

                $this->listEqualNestPUFollowUsPKs = $stmt->fetchAll(PDO::FETCH_COLUMN);
            }
        }
    }

    /**
     * Clears out the collection of Equal Nest PUFollowUs *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to the accessor method.
     *
     * @see addPUFollowU()
     * @see setPUFollowUs()
     * @see removePUFollowUs()
     */
    public function clearPUFollowUs()
    {
        $this->collEqualNestPUFollowUs = null;
    }

    /**
     * Initializes the collEqualNestPUFollowUs collection.
     *
     * By default this just sets the collEqualNestPUFollowUs collection to an empty PropelObjectCollection;
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database (ie, calling getPUFollowUs).
     */
    protected function initPUFollowUs()
    {
        $this->collEqualNestPUFollowUs = new PropelObjectCollection();
        $this->collEqualNestPUFollowUs->setModel('Politizr\Model\PUser');
    }

    /**
     * Removes all Equal Nest PUFollowUs relations
     *
     * @see addPUFollowU()
     * @see setPUFollowUs()
     */
    public function removePUFollowUs()
    {
        foreach ($this->getPUFollowUs() as $obj) {
            $obj->removePUFollowU($this);
        }
    }

    /**
     * Gets an array of PUser objects which are Equal Nest PUFollowUs of this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this PUser object is new, it will return an empty collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria
     * @param      PropelPDO $con
     * @return     PropelObjectCollection PUser[] List of Equal Nest PUFollowUs of this PUser.
     * @throws     PropelException
     */
    public function getPUFollowUs(Criteria $criteria = null, PropelPDO $con = null)
    {
        if (null === $this->listEqualNestPUFollowUsPKs) {
            $this->initListPUFollowUsPKs($con);
        }

        if (null === $this->collEqualNestPUFollowUs || null !== $criteria) {
            if (0 === count($this->listEqualNestPUFollowUsPKs) && null === $this->collEqualNestPUFollowUs) {
                // return empty collection
                $this->initPUFollowUs();
            } else {
                $newCollection = PUserQuery::create(null, $criteria)
                    ->addUsingAlias(PUserPeer::ID, $this->listEqualNestPUFollowUsPKs, Criteria::IN)
                    ->find($con);

                if (null !== $criteria) {
                    return $newCollection;
                }

                $this->collEqualNestPUFollowUs = $newCollection;
            }
        }

        return $this->collEqualNestPUFollowUs;
    }

    /**
     * Set an array of PUser objects as PUFollowUs of the this object
     *
     * @param  PUser[] $objects The PUser objects to set as PUFollowUs of the current object
     * @throws PropelException
     * @see    addPUFollowU()
     */
    public function setPUFollowUs($objects)
    {
        $this->clearPUFollowUs();
        foreach ($objects as $aPUFollowU) {
            if (!$aPUFollowU instanceof PUser) {
                throw new PropelException(sprintf(
                    '[Equal Nest] Cannot set object of type %s as PUFollowU, expected PUser',
                    is_object($aPUFollowU) ? get_class($aPUFollowU) : gettype($aPUFollowU)
                ));
            }

            $this->addPUFollowU($aPUFollowU);
        }
    }

    /**
     * Checks for Equal Nest relation
     *
     * @param  PUser $aPUFollowU The object to check for Equal Nest PUFollowU relation to the current object
     * @return boolean
     */
    public function hasPUFollowU(PUser $aPUFollowU)
    {
        if (null === $this->collEqualNestPUFollowUs) {
            $this->getPUFollowUs();
        }

        return $aPUFollowU->isNew() || $this->isNew()
            ? in_array($aPUFollowU, $this->collEqualNestPUFollowUs->getArrayCopy())
            : in_array($aPUFollowU->getPrimaryKey(), $this->collEqualNestPUFollowUs->getPrimaryKeys());
    }

    /**
     * Method called to associate another PUser object as a PUFollowU of this one
     * through the Equal Nest PUFollowUs relation.
     *
     * @param  PUser $aPUFollowU The PUser object to set as Equal Nest PUFollowUs relation of the current object
     * @throws PropelException
     */
    public function addPUFollowU(PUser $aPUFollowU)
    {
        if (!$this->hasPUFollowU($aPUFollowU)) {
            $this->collEqualNestPUFollowUs[] = $aPUFollowU;
            $aPUFollowU->addPUFollowU($this);
        }
    }

    /**
     * Method called to associate multiple PUser objects as Equal Nest PUFollowUs of this one
     *
     * @param   PUser[] PUFollowUs The PUser objects to set as
     *          Equal Nest PUFollowUs relation of the current object.
     * @throws  PropelException
     */
    public function addPUFollowUs($PUFollowUs)
    {
        foreach ($PUFollowUs as $aPUFollowUs) {
            $this->addPUFollowU($aPUFollowUs);
        }
    }

    /**
     * Method called to remove a PUser object from the Equal Nest PUFollowUs relation
     *
     * @param  PUser $pUFollowU The PUser object
     *         to remove as a PUFollowU of the current object
     * @param  PropelPDO $con
     * @throws PropelException
     */
    public function removePUFollowU(PUser $pUFollowU, PropelPDO $con = null)
    {
        if (null === $this->collEqualNestPUFollowUs) {
            $this->getPUFollowUs(null, $con);
        }

        if ($this->collEqualNestPUFollowUs->contains($pUFollowU)) {
            $this->collEqualNestPUFollowUs->remove($this->collEqualNestPUFollowUs->search($pUFollowU));

            $coll = $pUFollowU->getPUFollowUs(null, $con);
            if ($coll->contains($this)) {
                $coll->remove($coll->search($this));
            }
        } else {
            throw new PropelException(sprintf('[Equal Nest] Cannot remove PUFollowU from Equal Nest relation because it is not set as one!'));
        }
    }

    /**
     * Returns the number of Equal Nest PUFollowUs of this object.
     *
     * @param      Criteria   $criteria
     * @param      boolean    $distinct
     * @param      PropelPDO  $con
     * @return     integer    Count of PUFollowUs
     * @throws     PropelException
     */
    public function countPUFollowUs(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        if (null === $this->listEqualNestPUFollowUsPKs) {
            $this->initListPUFollowUsPKs($con);
        }

        if (null === $this->collEqualNestPUFollowUs || null !== $criteria) {
            if ($this->isNew() && null === $this->collEqualNestPUFollowUs) {
                return 0;
            } else {
                $query = PUserQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->addUsingAlias(PUserPeer::ID, $this->listEqualNestPUFollowUsPKs, Criteria::IN)
                    ->count($con);
            }
        } else {
            return count($this->collEqualNestPUFollowUs);
        }
    }

}
