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
use Politizr\Model\PDComment;
use Politizr\Model\PDCommentQuery;
use Politizr\Model\PDDebate;
use Politizr\Model\PDDebateQuery;
use Politizr\Model\PDReaction;
use Politizr\Model\PDReactionQuery;
use Politizr\Model\PDocument;
use Politizr\Model\PDocumentQuery;
use Politizr\Model\PNEmail;
use Politizr\Model\PNEmailQuery;
use Politizr\Model\PNotification;
use Politizr\Model\PNotificationQuery;
use Politizr\Model\POrder;
use Politizr\Model\POrderQuery;
use Politizr\Model\PQOrganization;
use Politizr\Model\PQOrganizationQuery;
use Politizr\Model\PQualification;
use Politizr\Model\PQualificationQuery;
use Politizr\Model\PRAction;
use Politizr\Model\PRActionQuery;
use Politizr\Model\PRBadge;
use Politizr\Model\PRBadgeQuery;
use Politizr\Model\PTag;
use Politizr\Model\PTagQuery;
use Politizr\Model\PUAffinityQO;
use Politizr\Model\PUAffinityQOQuery;
use Politizr\Model\PUBadges;
use Politizr\Model\PUBadgesQuery;
use Politizr\Model\PUFollowDD;
use Politizr\Model\PUFollowDDQuery;
use Politizr\Model\PUFollowT;
use Politizr\Model\PUFollowTQuery;
use Politizr\Model\PUFollowU;
use Politizr\Model\PUFollowUPeer;
use Politizr\Model\PUFollowUQuery;
use Politizr\Model\PUMandate;
use Politizr\Model\PUMandateQuery;
use Politizr\Model\PUNotifiedPN;
use Politizr\Model\PUNotifiedPNQuery;
use Politizr\Model\PUReputation;
use Politizr\Model\PUReputationQuery;
use Politizr\Model\PURoleQ;
use Politizr\Model\PURoleQQuery;
use Politizr\Model\PUStatus;
use Politizr\Model\PUStatusQuery;
use Politizr\Model\PUSubscribeNO;
use Politizr\Model\PUSubscribeNOQuery;
use Politizr\Model\PUTaggedT;
use Politizr\Model\PUTaggedTQuery;
use Politizr\Model\PUser;
use Politizr\Model\PUserArchive;
use Politizr\Model\PUserArchiveQuery;
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
     * The value for the p_u_status_id field.
     * @var        int
     */
    protected $p_u_status_id;

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
     * @var        PUStatus
     */
    protected $aPUStatus;

    /**
     * @var        PropelObjectCollection|PTag[] Collection to store aggregation of PTag objects.
     */
    protected $collPTags;
    protected $collPTagsPartial;

    /**
     * @var        PropelObjectCollection|POrder[] Collection to store aggregation of POrder objects.
     */
    protected $collPOrders;
    protected $collPOrdersPartial;

    /**
     * @var        PropelObjectCollection|PUFollowDD[] Collection to store aggregation of PUFollowDD objects.
     */
    protected $collPuFollowDdPUsers;
    protected $collPuFollowDdPUsersPartial;

    /**
     * @var        PropelObjectCollection|PUBadges[] Collection to store aggregation of PUBadges objects.
     */
    protected $collPUBadgess;
    protected $collPUBadgessPartial;

    /**
     * @var        PropelObjectCollection|PUReputation[] Collection to store aggregation of PUReputation objects.
     */
    protected $collPUReputations;
    protected $collPUReputationsPartial;

    /**
     * @var        PropelObjectCollection|PUTaggedT[] Collection to store aggregation of PUTaggedT objects.
     */
    protected $collPuTaggedTPUsers;
    protected $collPuTaggedTPUsersPartial;

    /**
     * @var        PropelObjectCollection|PUFollowT[] Collection to store aggregation of PUFollowT objects.
     */
    protected $collPuFollowTPUsers;
    protected $collPuFollowTPUsersPartial;

    /**
     * @var        PropelObjectCollection|PURoleQ[] Collection to store aggregation of PURoleQ objects.
     */
    protected $collPURoleQs;
    protected $collPURoleQsPartial;

    /**
     * @var        PropelObjectCollection|PUMandate[] Collection to store aggregation of PUMandate objects.
     */
    protected $collPUMandates;
    protected $collPUMandatesPartial;

    /**
     * @var        PropelObjectCollection|PUAffinityQO[] Collection to store aggregation of PUAffinityQO objects.
     */
    protected $collPUAffinityQos;
    protected $collPUAffinityQosPartial;

    /**
     * @var        PropelObjectCollection|PUNotifiedPN[] Collection to store aggregation of PUNotifiedPN objects.
     */
    protected $collPUNotifiedPNs;
    protected $collPUNotifiedPNsPartial;

    /**
     * @var        PropelObjectCollection|PUSubscribeNO[] Collection to store aggregation of PUSubscribeNO objects.
     */
    protected $collPUSubscribeNos;
    protected $collPUSubscribeNosPartial;

    /**
     * @var        PropelObjectCollection|PDocument[] Collection to store aggregation of PDocument objects.
     */
    protected $collPDocuments;
    protected $collPDocumentsPartial;

    /**
     * @var        PropelObjectCollection|PDComment[] Collection to store aggregation of PDComment objects.
     */
    protected $collPDComments;
    protected $collPDCommentsPartial;

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
    protected $collPDDebates;
    protected $collPDDebatesPartial;

    /**
     * @var        PropelObjectCollection|PDReaction[] Collection to store aggregation of PDReaction objects.
     */
    protected $collPDReactions;
    protected $collPDReactionsPartial;

    /**
     * @var        PropelObjectCollection|PDDebate[] Collection to store aggregation of PDDebate objects.
     */
    protected $collPuFollowDdPDDebates;

    /**
     * @var        PropelObjectCollection|PRBadge[] Collection to store aggregation of PRBadge objects.
     */
    protected $collPRBadges;

    /**
     * @var        PropelObjectCollection|PRAction[] Collection to store aggregation of PRAction objects.
     */
    protected $collPRActions;

    /**
     * @var        PropelObjectCollection|PTag[] Collection to store aggregation of PTag objects.
     */
    protected $collPuTaggedTPTags;

    /**
     * @var        PropelObjectCollection|PTag[] Collection to store aggregation of PTag objects.
     */
    protected $collPuFollowTPTags;

    /**
     * @var        PropelObjectCollection|PQualification[] Collection to store aggregation of PQualification objects.
     */
    protected $collPQualifications;

    /**
     * @var        PropelObjectCollection|PQOrganization[] Collection to store aggregation of PQOrganization objects.
     */
    protected $collPQOrganizations;

    /**
     * @var        PropelObjectCollection|PNotification[] Collection to store aggregation of PNotification objects.
     */
    protected $collPNotifications;

    /**
     * @var        PropelObjectCollection|PNEmail[] Collection to store aggregation of PNEmail objects.
     */
    protected $collPNEmails;

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
    protected $pRBadgesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $pRActionsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $puTaggedTPTagsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $puFollowTPTagsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $pQualificationsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $pQOrganizationsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $pNotificationsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $pNEmailsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $pTagsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $pOrdersScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $puFollowDdPUsersScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $pUBadgessScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $pUReputationsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $puTaggedTPUsersScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $puFollowTPUsersScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $pURoleQsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $pUMandatesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $pUAffinityQosScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $pUNotifiedPNsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $pUSubscribeNosScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $pDocumentsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $pDCommentsScheduledForDeletion = null;

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
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $pDDebatesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $pDReactionsScheduledForDeletion = null;

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
     * Get the [p_u_status_id] column value.
     *
     * @return int
     */
    public function getPUStatusId()
    {

        return $this->p_u_status_id;
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
     * @param  int $v new value
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
     * @param  string $v new value
     * @return PUser The current object (for fluent API support)
     */
    public function setProvider($v)
    {
        if ($v !== null) {
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
     * @param  string $v new value
     * @return PUser The current object (for fluent API support)
     */
    public function setProviderId($v)
    {
        if ($v !== null) {
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
     * @param  string $v new value
     * @return PUser The current object (for fluent API support)
     */
    public function setNickname($v)
    {
        if ($v !== null) {
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
     * @param  string $v new value
     * @return PUser The current object (for fluent API support)
     */
    public function setRealname($v)
    {
        if ($v !== null) {
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
     * @param  string $v new value
     * @return PUser The current object (for fluent API support)
     */
    public function setUsername($v)
    {
        if ($v !== null) {
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
     * @param  string $v new value
     * @return PUser The current object (for fluent API support)
     */
    public function setUsernameCanonical($v)
    {
        if ($v !== null) {
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
     * @param  string $v new value
     * @return PUser The current object (for fluent API support)
     */
    public function setEmail($v)
    {
        if ($v !== null) {
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
     * @param  string $v new value
     * @return PUser The current object (for fluent API support)
     */
    public function setEmailCanonical($v)
    {
        if ($v !== null) {
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
     * @param  string $v new value
     * @return PUser The current object (for fluent API support)
     */
    public function setSalt($v)
    {
        if ($v !== null) {
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
     * @param  string $v new value
     * @return PUser The current object (for fluent API support)
     */
    public function setPassword($v)
    {
        if ($v !== null) {
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
     * @param  string $v new value
     * @return PUser The current object (for fluent API support)
     */
    public function setConfirmationToken($v)
    {
        if ($v !== null) {
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
     * @param  array $v new value
     * @return PUser The current object (for fluent API support)
     */
    public function setRoles($v)
    {
        if ($this->roles_unserialized !== $v) {
            $this->roles_unserialized = $v;
            $this->roles = '| ' . implode(' | ', (array) $v) . ' |';
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
     * Set the value of [p_u_status_id] column.
     *
     * @param  int $v new value
     * @return PUser The current object (for fluent API support)
     */
    public function setPUStatusId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->p_u_status_id !== $v) {
            $this->p_u_status_id = $v;
            $this->modifiedColumns[] = PUserPeer::P_U_STATUS_ID;
        }

        if ($this->aPUStatus !== null && $this->aPUStatus->getId() !== $v) {
            $this->aPUStatus = null;
        }


        return $this;
    } // setPUStatusId()

    /**
     * Set the value of [file_name] column.
     *
     * @param  string $v new value
     * @return PUser The current object (for fluent API support)
     */
    public function setFileName($v)
    {
        if ($v !== null) {
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
     * @param  int $v new value
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
     * @param  string $v new value
     * @return PUser The current object (for fluent API support)
     */
    public function setFirstname($v)
    {
        if ($v !== null) {
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
     * @param  string $v new value
     * @return PUser The current object (for fluent API support)
     */
    public function setName($v)
    {
        if ($v !== null) {
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
     * Set the value of [biography] column.
     *
     * @param  string $v new value
     * @return PUser The current object (for fluent API support)
     */
    public function setBiography($v)
    {
        if ($v !== null) {
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
     * @param  string $v new value
     * @return PUser The current object (for fluent API support)
     */
    public function setWebsite($v)
    {
        if ($v !== null) {
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
     * @param  string $v new value
     * @return PUser The current object (for fluent API support)
     */
    public function setTwitter($v)
    {
        if ($v !== null) {
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
     * @param  string $v new value
     * @return PUser The current object (for fluent API support)
     */
    public function setFacebook($v)
    {
        if ($v !== null) {
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
     * @param  string $v new value
     * @return PUser The current object (for fluent API support)
     */
    public function setPhone($v)
    {
        if ($v !== null) {
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
     * Set the value of [nb_connected_days] column.
     *
     * @param  int $v new value
     * @return PUser The current object (for fluent API support)
     */
    public function setNbConnectedDays($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->nb_connected_days !== $v) {
            $this->nb_connected_days = $v;
            $this->modifiedColumns[] = PUserPeer::NB_CONNECTED_DAYS;
        }


        return $this;
    } // setNbConnectedDays()

    /**
     * Set the value of [nb_views] column.
     *
     * @param  int $v new value
     * @return PUser The current object (for fluent API support)
     */
    public function setNbViews($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->nb_views !== $v) {
            $this->nb_views = $v;
            $this->modifiedColumns[] = PUserPeer::NB_VIEWS;
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
     * @return PUser The current object (for fluent API support)
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
            $this->modifiedColumns[] = PUserPeer::QUALIFIED;
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
     * @return PUser The current object (for fluent API support)
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
            $this->modifiedColumns[] = PUserPeer::VALIDATED;
        }


        return $this;
    } // setValidated()

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
     * @param  string $v new value
     * @return PUser The current object (for fluent API support)
     */
    public function setSlug($v)
    {
        if ($v !== null) {
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

            if ($this->nb_connected_days !== 0) {
                return false;
            }

            if ($this->validated !== false) {
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
            $this->p_u_status_id = ($row[$startcol + 21] !== null) ? (int) $row[$startcol + 21] : null;
            $this->file_name = ($row[$startcol + 22] !== null) ? (string) $row[$startcol + 22] : null;
            $this->gender = ($row[$startcol + 23] !== null) ? (int) $row[$startcol + 23] : null;
            $this->firstname = ($row[$startcol + 24] !== null) ? (string) $row[$startcol + 24] : null;
            $this->name = ($row[$startcol + 25] !== null) ? (string) $row[$startcol + 25] : null;
            $this->birthday = ($row[$startcol + 26] !== null) ? (string) $row[$startcol + 26] : null;
            $this->biography = ($row[$startcol + 27] !== null) ? (string) $row[$startcol + 27] : null;
            $this->website = ($row[$startcol + 28] !== null) ? (string) $row[$startcol + 28] : null;
            $this->twitter = ($row[$startcol + 29] !== null) ? (string) $row[$startcol + 29] : null;
            $this->facebook = ($row[$startcol + 30] !== null) ? (string) $row[$startcol + 30] : null;
            $this->phone = ($row[$startcol + 31] !== null) ? (string) $row[$startcol + 31] : null;
            $this->newsletter = ($row[$startcol + 32] !== null) ? (boolean) $row[$startcol + 32] : null;
            $this->last_connect = ($row[$startcol + 33] !== null) ? (string) $row[$startcol + 33] : null;
            $this->nb_connected_days = ($row[$startcol + 34] !== null) ? (int) $row[$startcol + 34] : null;
            $this->nb_views = ($row[$startcol + 35] !== null) ? (int) $row[$startcol + 35] : null;
            $this->qualified = ($row[$startcol + 36] !== null) ? (boolean) $row[$startcol + 36] : null;
            $this->validated = ($row[$startcol + 37] !== null) ? (boolean) $row[$startcol + 37] : null;
            $this->online = ($row[$startcol + 38] !== null) ? (boolean) $row[$startcol + 38] : null;
            $this->created_at = ($row[$startcol + 39] !== null) ? (string) $row[$startcol + 39] : null;
            $this->updated_at = ($row[$startcol + 40] !== null) ? (string) $row[$startcol + 40] : null;
            $this->slug = ($row[$startcol + 41] !== null) ? (string) $row[$startcol + 41] : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }
            $this->postHydrate($row, $startcol, $rehydrate);

            return $startcol + 42; // 42 = PUserPeer::NUM_HYDRATE_COLUMNS.

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

        if ($this->aPUStatus !== null && $this->p_u_status_id !== $this->aPUStatus->getId()) {
            $this->aPUStatus = null;
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

            $this->aPUStatus = null;
            $this->collPTags = null;

            $this->collPOrders = null;

            $this->collPuFollowDdPUsers = null;

            $this->collPUBadgess = null;

            $this->collPUReputations = null;

            $this->collPuTaggedTPUsers = null;

            $this->collPuFollowTPUsers = null;

            $this->collPURoleQs = null;

            $this->collPUMandates = null;

            $this->collPUAffinityQos = null;

            $this->collPUNotifiedPNs = null;

            $this->collPUSubscribeNos = null;

            $this->collPDocuments = null;

            $this->collPDComments = null;

            $this->collPUFollowUsRelatedByPUserId = null;

            $this->collPUFollowUsRelatedByPUserFollowerId = null;

            $this->collPDDebates = null;

            $this->collPDReactions = null;

            $this->collPuFollowDdPDDebates = null;
            $this->collPRBadges = null;
            $this->collPRActions = null;
            $this->collPuTaggedTPTags = null;
            $this->collPuFollowTPTags = null;
            $this->collPQualifications = null;
            $this->collPQOrganizations = null;
            $this->collPNotifications = null;
            $this->collPNEmails = null;
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
            // archivable behavior
            if ($ret) {
                if ($this->archiveOnDelete) {
                    // do nothing yet. The object will be archived later when calling PUserQuery::delete().
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
            $con = Propel::getConnection(PUserPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        $isInsert = $this->isNew();
        try {
            $ret = $this->preSave($con);
            // sluggable behavior

            if ($this->isColumnModified(PUserPeer::SLUG) && $this->getSlug()) {
                $this->setSlug($this->makeSlugUnique($this->getSlug()));
            } else {
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

            // We call the save method on the following object(s) if they
            // were passed to this object by their corresponding set
            // method.  This object relates to these object(s) by a
            // foreign key reference.

            if ($this->aPUStatus !== null) {
                if ($this->aPUStatus->isModified() || $this->aPUStatus->isNew()) {
                    $affectedRows += $this->aPUStatus->save($con);
                }
                $this->setPUStatus($this->aPUStatus);
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

            if ($this->puFollowDdPDDebatesScheduledForDeletion !== null) {
                if (!$this->puFollowDdPDDebatesScheduledForDeletion->isEmpty()) {
                    $pks = array();
                    $pk = $this->getPrimaryKey();
                    foreach ($this->puFollowDdPDDebatesScheduledForDeletion->getPrimaryKeys(false) as $remotePk) {
                        $pks[] = array($pk, $remotePk);
                    }
                    PUFollowDDQuery::create()
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

            if ($this->pRBadgesScheduledForDeletion !== null) {
                if (!$this->pRBadgesScheduledForDeletion->isEmpty()) {
                    $pks = array();
                    $pk = $this->getPrimaryKey();
                    foreach ($this->pRBadgesScheduledForDeletion->getPrimaryKeys(false) as $remotePk) {
                        $pks[] = array($pk, $remotePk);
                    }
                    PUBadgesQuery::create()
                        ->filterByPrimaryKeys($pks)
                        ->delete($con);
                    $this->pRBadgesScheduledForDeletion = null;
                }

                foreach ($this->getPRBadges() as $pRBadge) {
                    if ($pRBadge->isModified()) {
                        $pRBadge->save($con);
                    }
                }
            } elseif ($this->collPRBadges) {
                foreach ($this->collPRBadges as $pRBadge) {
                    if ($pRBadge->isModified()) {
                        $pRBadge->save($con);
                    }
                }
            }

            if ($this->pRActionsScheduledForDeletion !== null) {
                if (!$this->pRActionsScheduledForDeletion->isEmpty()) {
                    $pks = array();
                    $pk = $this->getPrimaryKey();
                    foreach ($this->pRActionsScheduledForDeletion->getPrimaryKeys(false) as $remotePk) {
                        $pks[] = array($pk, $remotePk);
                    }
                    PUReputationQuery::create()
                        ->filterByPrimaryKeys($pks)
                        ->delete($con);
                    $this->pRActionsScheduledForDeletion = null;
                }

                foreach ($this->getPRActions() as $pRAction) {
                    if ($pRAction->isModified()) {
                        $pRAction->save($con);
                    }
                }
            } elseif ($this->collPRActions) {
                foreach ($this->collPRActions as $pRAction) {
                    if ($pRAction->isModified()) {
                        $pRAction->save($con);
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
                    PUTaggedTQuery::create()
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

            if ($this->puFollowTPTagsScheduledForDeletion !== null) {
                if (!$this->puFollowTPTagsScheduledForDeletion->isEmpty()) {
                    $pks = array();
                    $pk = $this->getPrimaryKey();
                    foreach ($this->puFollowTPTagsScheduledForDeletion->getPrimaryKeys(false) as $remotePk) {
                        $pks[] = array($pk, $remotePk);
                    }
                    PUFollowTQuery::create()
                        ->filterByPrimaryKeys($pks)
                        ->delete($con);
                    $this->puFollowTPTagsScheduledForDeletion = null;
                }

                foreach ($this->getPuFollowTPTags() as $puFollowTPTag) {
                    if ($puFollowTPTag->isModified()) {
                        $puFollowTPTag->save($con);
                    }
                }
            } elseif ($this->collPuFollowTPTags) {
                foreach ($this->collPuFollowTPTags as $puFollowTPTag) {
                    if ($puFollowTPTag->isModified()) {
                        $puFollowTPTag->save($con);
                    }
                }
            }

            if ($this->pQualificationsScheduledForDeletion !== null) {
                if (!$this->pQualificationsScheduledForDeletion->isEmpty()) {
                    $pks = array();
                    $pk = $this->getPrimaryKey();
                    foreach ($this->pQualificationsScheduledForDeletion->getPrimaryKeys(false) as $remotePk) {
                        $pks[] = array($pk, $remotePk);
                    }
                    PURoleQQuery::create()
                        ->filterByPrimaryKeys($pks)
                        ->delete($con);
                    $this->pQualificationsScheduledForDeletion = null;
                }

                foreach ($this->getPQualifications() as $pQualification) {
                    if ($pQualification->isModified()) {
                        $pQualification->save($con);
                    }
                }
            } elseif ($this->collPQualifications) {
                foreach ($this->collPQualifications as $pQualification) {
                    if ($pQualification->isModified()) {
                        $pQualification->save($con);
                    }
                }
            }

            if ($this->pQOrganizationsScheduledForDeletion !== null) {
                if (!$this->pQOrganizationsScheduledForDeletion->isEmpty()) {
                    $pks = array();
                    $pk = $this->getPrimaryKey();
                    foreach ($this->pQOrganizationsScheduledForDeletion->getPrimaryKeys(false) as $remotePk) {
                        $pks[] = array($pk, $remotePk);
                    }
                    PUAffinityQOQuery::create()
                        ->filterByPrimaryKeys($pks)
                        ->delete($con);
                    $this->pQOrganizationsScheduledForDeletion = null;
                }

                foreach ($this->getPQOrganizations() as $pQOrganization) {
                    if ($pQOrganization->isModified()) {
                        $pQOrganization->save($con);
                    }
                }
            } elseif ($this->collPQOrganizations) {
                foreach ($this->collPQOrganizations as $pQOrganization) {
                    if ($pQOrganization->isModified()) {
                        $pQOrganization->save($con);
                    }
                }
            }

            if ($this->pNotificationsScheduledForDeletion !== null) {
                if (!$this->pNotificationsScheduledForDeletion->isEmpty()) {
                    $pks = array();
                    $pk = $this->getPrimaryKey();
                    foreach ($this->pNotificationsScheduledForDeletion->getPrimaryKeys(false) as $remotePk) {
                        $pks[] = array($pk, $remotePk);
                    }
                    PUNotifiedPNQuery::create()
                        ->filterByPrimaryKeys($pks)
                        ->delete($con);
                    $this->pNotificationsScheduledForDeletion = null;
                }

                foreach ($this->getPNotifications() as $pNotification) {
                    if ($pNotification->isModified()) {
                        $pNotification->save($con);
                    }
                }
            } elseif ($this->collPNotifications) {
                foreach ($this->collPNotifications as $pNotification) {
                    if ($pNotification->isModified()) {
                        $pNotification->save($con);
                    }
                }
            }

            if ($this->pNEmailsScheduledForDeletion !== null) {
                if (!$this->pNEmailsScheduledForDeletion->isEmpty()) {
                    $pks = array();
                    $pk = $this->getPrimaryKey();
                    foreach ($this->pNEmailsScheduledForDeletion->getPrimaryKeys(false) as $remotePk) {
                        $pks[] = array($pk, $remotePk);
                    }
                    PUSubscribeNOQuery::create()
                        ->filterByPrimaryKeys($pks)
                        ->delete($con);
                    $this->pNEmailsScheduledForDeletion = null;
                }

                foreach ($this->getPNEmails() as $pNEmail) {
                    if ($pNEmail->isModified()) {
                        $pNEmail->save($con);
                    }
                }
            } elseif ($this->collPNEmails) {
                foreach ($this->collPNEmails as $pNEmail) {
                    if ($pNEmail->isModified()) {
                        $pNEmail->save($con);
                    }
                }
            }

            if ($this->pTagsScheduledForDeletion !== null) {
                if (!$this->pTagsScheduledForDeletion->isEmpty()) {
                    foreach ($this->pTagsScheduledForDeletion as $pTag) {
                        // need to save related object because we set the relation to null
                        $pTag->save($con);
                    }
                    $this->pTagsScheduledForDeletion = null;
                }
            }

            if ($this->collPTags !== null) {
                foreach ($this->collPTags as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
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

            if ($this->pUBadgessScheduledForDeletion !== null) {
                if (!$this->pUBadgessScheduledForDeletion->isEmpty()) {
                    PUBadgesQuery::create()
                        ->filterByPrimaryKeys($this->pUBadgessScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->pUBadgessScheduledForDeletion = null;
                }
            }

            if ($this->collPUBadgess !== null) {
                foreach ($this->collPUBadgess as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->pUReputationsScheduledForDeletion !== null) {
                if (!$this->pUReputationsScheduledForDeletion->isEmpty()) {
                    PUReputationQuery::create()
                        ->filterByPrimaryKeys($this->pUReputationsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->pUReputationsScheduledForDeletion = null;
                }
            }

            if ($this->collPUReputations !== null) {
                foreach ($this->collPUReputations as $referrerFK) {
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

            if ($this->puFollowTPUsersScheduledForDeletion !== null) {
                if (!$this->puFollowTPUsersScheduledForDeletion->isEmpty()) {
                    PUFollowTQuery::create()
                        ->filterByPrimaryKeys($this->puFollowTPUsersScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->puFollowTPUsersScheduledForDeletion = null;
                }
            }

            if ($this->collPuFollowTPUsers !== null) {
                foreach ($this->collPuFollowTPUsers as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->pURoleQsScheduledForDeletion !== null) {
                if (!$this->pURoleQsScheduledForDeletion->isEmpty()) {
                    PURoleQQuery::create()
                        ->filterByPrimaryKeys($this->pURoleQsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->pURoleQsScheduledForDeletion = null;
                }
            }

            if ($this->collPURoleQs !== null) {
                foreach ($this->collPURoleQs as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->pUMandatesScheduledForDeletion !== null) {
                if (!$this->pUMandatesScheduledForDeletion->isEmpty()) {
                    PUMandateQuery::create()
                        ->filterByPrimaryKeys($this->pUMandatesScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->pUMandatesScheduledForDeletion = null;
                }
            }

            if ($this->collPUMandates !== null) {
                foreach ($this->collPUMandates as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->pUAffinityQosScheduledForDeletion !== null) {
                if (!$this->pUAffinityQosScheduledForDeletion->isEmpty()) {
                    PUAffinityQOQuery::create()
                        ->filterByPrimaryKeys($this->pUAffinityQosScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->pUAffinityQosScheduledForDeletion = null;
                }
            }

            if ($this->collPUAffinityQos !== null) {
                foreach ($this->collPUAffinityQos as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->pUNotifiedPNsScheduledForDeletion !== null) {
                if (!$this->pUNotifiedPNsScheduledForDeletion->isEmpty()) {
                    PUNotifiedPNQuery::create()
                        ->filterByPrimaryKeys($this->pUNotifiedPNsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->pUNotifiedPNsScheduledForDeletion = null;
                }
            }

            if ($this->collPUNotifiedPNs !== null) {
                foreach ($this->collPUNotifiedPNs as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->pUSubscribeNosScheduledForDeletion !== null) {
                if (!$this->pUSubscribeNosScheduledForDeletion->isEmpty()) {
                    PUSubscribeNOQuery::create()
                        ->filterByPrimaryKeys($this->pUSubscribeNosScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->pUSubscribeNosScheduledForDeletion = null;
                }
            }

            if ($this->collPUSubscribeNos !== null) {
                foreach ($this->collPUSubscribeNos as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->pDocumentsScheduledForDeletion !== null) {
                if (!$this->pDocumentsScheduledForDeletion->isEmpty()) {
                    foreach ($this->pDocumentsScheduledForDeletion as $pDocument) {
                        // need to save related object because we set the relation to null
                        $pDocument->save($con);
                    }
                    $this->pDocumentsScheduledForDeletion = null;
                }
            }

            if ($this->collPDocuments !== null) {
                foreach ($this->collPDocuments as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->pDCommentsScheduledForDeletion !== null) {
                if (!$this->pDCommentsScheduledForDeletion->isEmpty()) {
                    foreach ($this->pDCommentsScheduledForDeletion as $pDComment) {
                        // need to save related object because we set the relation to null
                        $pDComment->save($con);
                    }
                    $this->pDCommentsScheduledForDeletion = null;
                }
            }

            if ($this->collPDComments !== null) {
                foreach ($this->collPDComments as $referrerFK) {
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
        if ($this->isColumnModified(PUserPeer::P_U_STATUS_ID)) {
            $modifiedColumns[':p' . $index++]  = '`p_u_status_id`';
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
        if ($this->isColumnModified(PUserPeer::NB_CONNECTED_DAYS)) {
            $modifiedColumns[':p' . $index++]  = '`nb_connected_days`';
        }
        if ($this->isColumnModified(PUserPeer::NB_VIEWS)) {
            $modifiedColumns[':p' . $index++]  = '`nb_views`';
        }
        if ($this->isColumnModified(PUserPeer::QUALIFIED)) {
            $modifiedColumns[':p' . $index++]  = '`qualified`';
        }
        if ($this->isColumnModified(PUserPeer::VALIDATED)) {
            $modifiedColumns[':p' . $index++]  = '`validated`';
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
                    case '`p_u_status_id`':
                        $stmt->bindValue($identifier, $this->p_u_status_id, PDO::PARAM_INT);
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
                    case '`nb_views`':
                        $stmt->bindValue($identifier, $this->nb_views, PDO::PARAM_INT);
                        break;
                    case '`qualified`':
                        $stmt->bindValue($identifier, (int) $this->qualified, PDO::PARAM_INT);
                        break;
                    case '`validated`':
                        $stmt->bindValue($identifier, (int) $this->validated, PDO::PARAM_INT);
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

            if ($this->aPUStatus !== null) {
                if (!$this->aPUStatus->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aPUStatus->getValidationFailures());
                }
            }


            if (($retval = PUserPeer::doValidate($this, $columns)) !== true) {
                $failureMap = array_merge($failureMap, $retval);
            }


                if ($this->collPTags !== null) {
                    foreach ($this->collPTags as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collPOrders !== null) {
                    foreach ($this->collPOrders as $referrerFK) {
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

                if ($this->collPUBadgess !== null) {
                    foreach ($this->collPUBadgess as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collPUReputations !== null) {
                    foreach ($this->collPUReputations as $referrerFK) {
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

                if ($this->collPuFollowTPUsers !== null) {
                    foreach ($this->collPuFollowTPUsers as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collPURoleQs !== null) {
                    foreach ($this->collPURoleQs as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collPUMandates !== null) {
                    foreach ($this->collPUMandates as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collPUAffinityQos !== null) {
                    foreach ($this->collPUAffinityQos as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collPUNotifiedPNs !== null) {
                    foreach ($this->collPUNotifiedPNs as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collPUSubscribeNos !== null) {
                    foreach ($this->collPUSubscribeNos as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collPDocuments !== null) {
                    foreach ($this->collPDocuments as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collPDComments !== null) {
                    foreach ($this->collPDComments as $referrerFK) {
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

                if ($this->collPDDebates !== null) {
                    foreach ($this->collPDDebates as $referrerFK) {
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
                return $this->getPUStatusId();
                break;
            case 22:
                return $this->getFileName();
                break;
            case 23:
                return $this->getGender();
                break;
            case 24:
                return $this->getFirstname();
                break;
            case 25:
                return $this->getName();
                break;
            case 26:
                return $this->getBirthday();
                break;
            case 27:
                return $this->getBiography();
                break;
            case 28:
                return $this->getWebsite();
                break;
            case 29:
                return $this->getTwitter();
                break;
            case 30:
                return $this->getFacebook();
                break;
            case 31:
                return $this->getPhone();
                break;
            case 32:
                return $this->getNewsletter();
                break;
            case 33:
                return $this->getLastConnect();
                break;
            case 34:
                return $this->getNbConnectedDays();
                break;
            case 35:
                return $this->getNbViews();
                break;
            case 36:
                return $this->getQualified();
                break;
            case 37:
                return $this->getValidated();
                break;
            case 38:
                return $this->getOnline();
                break;
            case 39:
                return $this->getCreatedAt();
                break;
            case 40:
                return $this->getUpdatedAt();
                break;
            case 41:
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
            $keys[21] => $this->getPUStatusId(),
            $keys[22] => $this->getFileName(),
            $keys[23] => $this->getGender(),
            $keys[24] => $this->getFirstname(),
            $keys[25] => $this->getName(),
            $keys[26] => $this->getBirthday(),
            $keys[27] => $this->getBiography(),
            $keys[28] => $this->getWebsite(),
            $keys[29] => $this->getTwitter(),
            $keys[30] => $this->getFacebook(),
            $keys[31] => $this->getPhone(),
            $keys[32] => $this->getNewsletter(),
            $keys[33] => $this->getLastConnect(),
            $keys[34] => $this->getNbConnectedDays(),
            $keys[35] => $this->getNbViews(),
            $keys[36] => $this->getQualified(),
            $keys[37] => $this->getValidated(),
            $keys[38] => $this->getOnline(),
            $keys[39] => $this->getCreatedAt(),
            $keys[40] => $this->getUpdatedAt(),
            $keys[41] => $this->getSlug(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aPUStatus) {
                $result['PUStatus'] = $this->aPUStatus->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->collPTags) {
                $result['PTags'] = $this->collPTags->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPOrders) {
                $result['POrders'] = $this->collPOrders->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPuFollowDdPUsers) {
                $result['PuFollowDdPUsers'] = $this->collPuFollowDdPUsers->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPUBadgess) {
                $result['PUBadgess'] = $this->collPUBadgess->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPUReputations) {
                $result['PUReputations'] = $this->collPUReputations->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPuTaggedTPUsers) {
                $result['PuTaggedTPUsers'] = $this->collPuTaggedTPUsers->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPuFollowTPUsers) {
                $result['PuFollowTPUsers'] = $this->collPuFollowTPUsers->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPURoleQs) {
                $result['PURoleQs'] = $this->collPURoleQs->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPUMandates) {
                $result['PUMandates'] = $this->collPUMandates->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPUAffinityQos) {
                $result['PUAffinityQos'] = $this->collPUAffinityQos->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPUNotifiedPNs) {
                $result['PUNotifiedPNs'] = $this->collPUNotifiedPNs->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPUSubscribeNos) {
                $result['PUSubscribeNos'] = $this->collPUSubscribeNos->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPDocuments) {
                $result['PDocuments'] = $this->collPDocuments->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPDComments) {
                $result['PDComments'] = $this->collPDComments->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPUFollowUsRelatedByPUserId) {
                $result['PUFollowUsRelatedByPUserId'] = $this->collPUFollowUsRelatedByPUserId->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPUFollowUsRelatedByPUserFollowerId) {
                $result['PUFollowUsRelatedByPUserFollowerId'] = $this->collPUFollowUsRelatedByPUserFollowerId->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPDDebates) {
                $result['PDDebates'] = $this->collPDDebates->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPDReactions) {
                $result['PDReactions'] = $this->collPDReactions->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
                $this->setPUStatusId($value);
                break;
            case 22:
                $this->setFileName($value);
                break;
            case 23:
                $valueSet = PUserPeer::getValueSet(PUserPeer::GENDER);
                if (isset($valueSet[$value])) {
                    $value = $valueSet[$value];
                }
                $this->setGender($value);
                break;
            case 24:
                $this->setFirstname($value);
                break;
            case 25:
                $this->setName($value);
                break;
            case 26:
                $this->setBirthday($value);
                break;
            case 27:
                $this->setBiography($value);
                break;
            case 28:
                $this->setWebsite($value);
                break;
            case 29:
                $this->setTwitter($value);
                break;
            case 30:
                $this->setFacebook($value);
                break;
            case 31:
                $this->setPhone($value);
                break;
            case 32:
                $this->setNewsletter($value);
                break;
            case 33:
                $this->setLastConnect($value);
                break;
            case 34:
                $this->setNbConnectedDays($value);
                break;
            case 35:
                $this->setNbViews($value);
                break;
            case 36:
                $this->setQualified($value);
                break;
            case 37:
                $this->setValidated($value);
                break;
            case 38:
                $this->setOnline($value);
                break;
            case 39:
                $this->setCreatedAt($value);
                break;
            case 40:
                $this->setUpdatedAt($value);
                break;
            case 41:
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
        if (array_key_exists($keys[21], $arr)) $this->setPUStatusId($arr[$keys[21]]);
        if (array_key_exists($keys[22], $arr)) $this->setFileName($arr[$keys[22]]);
        if (array_key_exists($keys[23], $arr)) $this->setGender($arr[$keys[23]]);
        if (array_key_exists($keys[24], $arr)) $this->setFirstname($arr[$keys[24]]);
        if (array_key_exists($keys[25], $arr)) $this->setName($arr[$keys[25]]);
        if (array_key_exists($keys[26], $arr)) $this->setBirthday($arr[$keys[26]]);
        if (array_key_exists($keys[27], $arr)) $this->setBiography($arr[$keys[27]]);
        if (array_key_exists($keys[28], $arr)) $this->setWebsite($arr[$keys[28]]);
        if (array_key_exists($keys[29], $arr)) $this->setTwitter($arr[$keys[29]]);
        if (array_key_exists($keys[30], $arr)) $this->setFacebook($arr[$keys[30]]);
        if (array_key_exists($keys[31], $arr)) $this->setPhone($arr[$keys[31]]);
        if (array_key_exists($keys[32], $arr)) $this->setNewsletter($arr[$keys[32]]);
        if (array_key_exists($keys[33], $arr)) $this->setLastConnect($arr[$keys[33]]);
        if (array_key_exists($keys[34], $arr)) $this->setNbConnectedDays($arr[$keys[34]]);
        if (array_key_exists($keys[35], $arr)) $this->setNbViews($arr[$keys[35]]);
        if (array_key_exists($keys[36], $arr)) $this->setQualified($arr[$keys[36]]);
        if (array_key_exists($keys[37], $arr)) $this->setValidated($arr[$keys[37]]);
        if (array_key_exists($keys[38], $arr)) $this->setOnline($arr[$keys[38]]);
        if (array_key_exists($keys[39], $arr)) $this->setCreatedAt($arr[$keys[39]]);
        if (array_key_exists($keys[40], $arr)) $this->setUpdatedAt($arr[$keys[40]]);
        if (array_key_exists($keys[41], $arr)) $this->setSlug($arr[$keys[41]]);
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
        if ($this->isColumnModified(PUserPeer::P_U_STATUS_ID)) $criteria->add(PUserPeer::P_U_STATUS_ID, $this->p_u_status_id);
        if ($this->isColumnModified(PUserPeer::FILE_NAME)) $criteria->add(PUserPeer::FILE_NAME, $this->file_name);
        if ($this->isColumnModified(PUserPeer::GENDER)) $criteria->add(PUserPeer::GENDER, $this->gender);
        if ($this->isColumnModified(PUserPeer::FIRSTNAME)) $criteria->add(PUserPeer::FIRSTNAME, $this->firstname);
        if ($this->isColumnModified(PUserPeer::NAME)) $criteria->add(PUserPeer::NAME, $this->name);
        if ($this->isColumnModified(PUserPeer::BIRTHDAY)) $criteria->add(PUserPeer::BIRTHDAY, $this->birthday);
        if ($this->isColumnModified(PUserPeer::BIOGRAPHY)) $criteria->add(PUserPeer::BIOGRAPHY, $this->biography);
        if ($this->isColumnModified(PUserPeer::WEBSITE)) $criteria->add(PUserPeer::WEBSITE, $this->website);
        if ($this->isColumnModified(PUserPeer::TWITTER)) $criteria->add(PUserPeer::TWITTER, $this->twitter);
        if ($this->isColumnModified(PUserPeer::FACEBOOK)) $criteria->add(PUserPeer::FACEBOOK, $this->facebook);
        if ($this->isColumnModified(PUserPeer::PHONE)) $criteria->add(PUserPeer::PHONE, $this->phone);
        if ($this->isColumnModified(PUserPeer::NEWSLETTER)) $criteria->add(PUserPeer::NEWSLETTER, $this->newsletter);
        if ($this->isColumnModified(PUserPeer::LAST_CONNECT)) $criteria->add(PUserPeer::LAST_CONNECT, $this->last_connect);
        if ($this->isColumnModified(PUserPeer::NB_CONNECTED_DAYS)) $criteria->add(PUserPeer::NB_CONNECTED_DAYS, $this->nb_connected_days);
        if ($this->isColumnModified(PUserPeer::NB_VIEWS)) $criteria->add(PUserPeer::NB_VIEWS, $this->nb_views);
        if ($this->isColumnModified(PUserPeer::QUALIFIED)) $criteria->add(PUserPeer::QUALIFIED, $this->qualified);
        if ($this->isColumnModified(PUserPeer::VALIDATED)) $criteria->add(PUserPeer::VALIDATED, $this->validated);
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
        $copyObj->setPUStatusId($this->getPUStatusId());
        $copyObj->setFileName($this->getFileName());
        $copyObj->setGender($this->getGender());
        $copyObj->setFirstname($this->getFirstname());
        $copyObj->setName($this->getName());
        $copyObj->setBirthday($this->getBirthday());
        $copyObj->setBiography($this->getBiography());
        $copyObj->setWebsite($this->getWebsite());
        $copyObj->setTwitter($this->getTwitter());
        $copyObj->setFacebook($this->getFacebook());
        $copyObj->setPhone($this->getPhone());
        $copyObj->setNewsletter($this->getNewsletter());
        $copyObj->setLastConnect($this->getLastConnect());
        $copyObj->setNbConnectedDays($this->getNbConnectedDays());
        $copyObj->setNbViews($this->getNbViews());
        $copyObj->setQualified($this->getQualified());
        $copyObj->setValidated($this->getValidated());
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

            foreach ($this->getPTags() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPTag($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPOrders() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPOrder($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPuFollowDdPUsers() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPuFollowDdPUser($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPUBadgess() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPUBadges($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPUReputations() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPUReputation($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPuTaggedTPUsers() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPuTaggedTPUser($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPuFollowTPUsers() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPuFollowTPUser($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPURoleQs() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPURoleQ($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPUMandates() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPUMandate($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPUAffinityQos() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPUAffinityQO($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPUNotifiedPNs() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPUNotifiedPN($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPUSubscribeNos() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPUSubscribeNO($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPDocuments() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPDocument($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPDComments() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPDComment($relObj->copy($deepCopy));
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

            foreach ($this->getPDDebates() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPDDebate($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPDReactions() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPDReaction($relObj->copy($deepCopy));
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
     * Declares an association between this object and a PUStatus object.
     *
     * @param                  PUStatus $v
     * @return PUser The current object (for fluent API support)
     * @throws PropelException
     */
    public function setPUStatus(PUStatus $v = null)
    {
        if ($v === null) {
            $this->setPUStatusId(NULL);
        } else {
            $this->setPUStatusId($v->getId());
        }

        $this->aPUStatus = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the PUStatus object, it will not be re-added.
        if ($v !== null) {
            $v->addPUser($this);
        }


        return $this;
    }


    /**
     * Get the associated PUStatus object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return PUStatus The associated PUStatus object.
     * @throws PropelException
     */
    public function getPUStatus(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aPUStatus === null && ($this->p_u_status_id !== null) && $doQuery) {
            $this->aPUStatus = PUStatusQuery::create()->findPk($this->p_u_status_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aPUStatus->addPUsers($this);
             */
        }

        return $this->aPUStatus;
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
        if ('PTag' == $relationName) {
            $this->initPTags();
        }
        if ('POrder' == $relationName) {
            $this->initPOrders();
        }
        if ('PuFollowDdPUser' == $relationName) {
            $this->initPuFollowDdPUsers();
        }
        if ('PUBadges' == $relationName) {
            $this->initPUBadgess();
        }
        if ('PUReputation' == $relationName) {
            $this->initPUReputations();
        }
        if ('PuTaggedTPUser' == $relationName) {
            $this->initPuTaggedTPUsers();
        }
        if ('PuFollowTPUser' == $relationName) {
            $this->initPuFollowTPUsers();
        }
        if ('PURoleQ' == $relationName) {
            $this->initPURoleQs();
        }
        if ('PUMandate' == $relationName) {
            $this->initPUMandates();
        }
        if ('PUAffinityQO' == $relationName) {
            $this->initPUAffinityQos();
        }
        if ('PUNotifiedPN' == $relationName) {
            $this->initPUNotifiedPNs();
        }
        if ('PUSubscribeNO' == $relationName) {
            $this->initPUSubscribeNos();
        }
        if ('PDocument' == $relationName) {
            $this->initPDocuments();
        }
        if ('PDComment' == $relationName) {
            $this->initPDComments();
        }
        if ('PUFollowURelatedByPUserId' == $relationName) {
            $this->initPUFollowUsRelatedByPUserId();
        }
        if ('PUFollowURelatedByPUserFollowerId' == $relationName) {
            $this->initPUFollowUsRelatedByPUserFollowerId();
        }
        if ('PDDebate' == $relationName) {
            $this->initPDDebates();
        }
        if ('PDReaction' == $relationName) {
            $this->initPDReactions();
        }
    }

    /**
     * Clears out the collPTags collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return PUser The current object (for fluent API support)
     * @see        addPTags()
     */
    public function clearPTags()
    {
        $this->collPTags = null; // important to set this to null since that means it is uninitialized
        $this->collPTagsPartial = null;

        return $this;
    }

    /**
     * reset is the collPTags collection loaded partially
     *
     * @return void
     */
    public function resetPartialPTags($v = true)
    {
        $this->collPTagsPartial = $v;
    }

    /**
     * Initializes the collPTags collection.
     *
     * By default this just sets the collPTags collection to an empty array (like clearcollPTags());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPTags($overrideExisting = true)
    {
        if (null !== $this->collPTags && !$overrideExisting) {
            return;
        }
        $this->collPTags = new PropelObjectCollection();
        $this->collPTags->setModel('PTag');
    }

    /**
     * Gets an array of PTag objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this PUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|PTag[] List of PTag objects
     * @throws PropelException
     */
    public function getPTags($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collPTagsPartial && !$this->isNew();
        if (null === $this->collPTags || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPTags) {
                // return empty collection
                $this->initPTags();
            } else {
                $collPTags = PTagQuery::create(null, $criteria)
                    ->filterByPUser($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collPTagsPartial && count($collPTags)) {
                      $this->initPTags(false);

                      foreach ($collPTags as $obj) {
                        if (false == $this->collPTags->contains($obj)) {
                          $this->collPTags->append($obj);
                        }
                      }

                      $this->collPTagsPartial = true;
                    }

                    $collPTags->getInternalIterator()->rewind();

                    return $collPTags;
                }

                if ($partial && $this->collPTags) {
                    foreach ($this->collPTags as $obj) {
                        if ($obj->isNew()) {
                            $collPTags[] = $obj;
                        }
                    }
                }

                $this->collPTags = $collPTags;
                $this->collPTagsPartial = false;
            }
        }

        return $this->collPTags;
    }

    /**
     * Sets a collection of PTag objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $pTags A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return PUser The current object (for fluent API support)
     */
    public function setPTags(PropelCollection $pTags, PropelPDO $con = null)
    {
        $pTagsToDelete = $this->getPTags(new Criteria(), $con)->diff($pTags);


        $this->pTagsScheduledForDeletion = $pTagsToDelete;

        foreach ($pTagsToDelete as $pTagRemoved) {
            $pTagRemoved->setPUser(null);
        }

        $this->collPTags = null;
        foreach ($pTags as $pTag) {
            $this->addPTag($pTag);
        }

        $this->collPTags = $pTags;
        $this->collPTagsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related PTag objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related PTag objects.
     * @throws PropelException
     */
    public function countPTags(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collPTagsPartial && !$this->isNew();
        if (null === $this->collPTags || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPTags) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPTags());
            }
            $query = PTagQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPUser($this)
                ->count($con);
        }

        return count($this->collPTags);
    }

    /**
     * Method called to associate a PTag object to this object
     * through the PTag foreign key attribute.
     *
     * @param    PTag $l PTag
     * @return PUser The current object (for fluent API support)
     */
    public function addPTag(PTag $l)
    {
        if ($this->collPTags === null) {
            $this->initPTags();
            $this->collPTagsPartial = true;
        }

        if (!in_array($l, $this->collPTags->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddPTag($l);

            if ($this->pTagsScheduledForDeletion and $this->pTagsScheduledForDeletion->contains($l)) {
                $this->pTagsScheduledForDeletion->remove($this->pTagsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	PTag $pTag The pTag object to add.
     */
    protected function doAddPTag($pTag)
    {
        $this->collPTags[]= $pTag;
        $pTag->setPUser($this);
    }

    /**
     * @param	PTag $pTag The pTag object to remove.
     * @return PUser The current object (for fluent API support)
     */
    public function removePTag($pTag)
    {
        if ($this->getPTags()->contains($pTag)) {
            $this->collPTags->remove($this->collPTags->search($pTag));
            if (null === $this->pTagsScheduledForDeletion) {
                $this->pTagsScheduledForDeletion = clone $this->collPTags;
                $this->pTagsScheduledForDeletion->clear();
            }
            $this->pTagsScheduledForDeletion[]= $pTag;
            $pTag->setPUser(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this PUser is new, it will return
     * an empty collection; or if this PUser has previously
     * been saved, it will retrieve related PTags from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in PUser.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|PTag[] List of PTag objects
     */
    public function getPTagsJoinPTTagType($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PTagQuery::create(null, $criteria);
        $query->joinWith('PTTagType', $join_behavior);

        return $this->getPTags($query, $con);
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

                      foreach ($collPOrders as $obj) {
                        if (false == $this->collPOrders->contains($obj)) {
                          $this->collPOrders->append($obj);
                        }
                      }

                      $this->collPOrdersPartial = true;
                    }

                    $collPOrders->getInternalIterator()->rewind();

                    return $collPOrders;
                }

                if ($partial && $this->collPOrders) {
                    foreach ($this->collPOrders as $obj) {
                        if ($obj->isNew()) {
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


        $this->pOrdersScheduledForDeletion = $pOrdersToDelete;

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

            if ($partial && !$criteria) {
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

            if ($this->pOrdersScheduledForDeletion and $this->pOrdersScheduledForDeletion->contains($l)) {
                $this->pOrdersScheduledForDeletion->remove($this->pOrdersScheduledForDeletion->search($l));
            }
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

                      foreach ($collPuFollowDdPUsers as $obj) {
                        if (false == $this->collPuFollowDdPUsers->contains($obj)) {
                          $this->collPuFollowDdPUsers->append($obj);
                        }
                      }

                      $this->collPuFollowDdPUsersPartial = true;
                    }

                    $collPuFollowDdPUsers->getInternalIterator()->rewind();

                    return $collPuFollowDdPUsers;
                }

                if ($partial && $this->collPuFollowDdPUsers) {
                    foreach ($this->collPuFollowDdPUsers as $obj) {
                        if ($obj->isNew()) {
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


        $this->puFollowDdPUsersScheduledForDeletion = $puFollowDdPUsersToDelete;

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

            if ($partial && !$criteria) {
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

            if ($this->puFollowDdPUsersScheduledForDeletion and $this->puFollowDdPUsersScheduledForDeletion->contains($l)) {
                $this->puFollowDdPUsersScheduledForDeletion->remove($this->puFollowDdPUsersScheduledForDeletion->search($l));
            }
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
     * Clears out the collPUBadgess collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return PUser The current object (for fluent API support)
     * @see        addPUBadgess()
     */
    public function clearPUBadgess()
    {
        $this->collPUBadgess = null; // important to set this to null since that means it is uninitialized
        $this->collPUBadgessPartial = null;

        return $this;
    }

    /**
     * reset is the collPUBadgess collection loaded partially
     *
     * @return void
     */
    public function resetPartialPUBadgess($v = true)
    {
        $this->collPUBadgessPartial = $v;
    }

    /**
     * Initializes the collPUBadgess collection.
     *
     * By default this just sets the collPUBadgess collection to an empty array (like clearcollPUBadgess());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPUBadgess($overrideExisting = true)
    {
        if (null !== $this->collPUBadgess && !$overrideExisting) {
            return;
        }
        $this->collPUBadgess = new PropelObjectCollection();
        $this->collPUBadgess->setModel('PUBadges');
    }

    /**
     * Gets an array of PUBadges objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this PUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|PUBadges[] List of PUBadges objects
     * @throws PropelException
     */
    public function getPUBadgess($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collPUBadgessPartial && !$this->isNew();
        if (null === $this->collPUBadgess || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPUBadgess) {
                // return empty collection
                $this->initPUBadgess();
            } else {
                $collPUBadgess = PUBadgesQuery::create(null, $criteria)
                    ->filterByPUser($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collPUBadgessPartial && count($collPUBadgess)) {
                      $this->initPUBadgess(false);

                      foreach ($collPUBadgess as $obj) {
                        if (false == $this->collPUBadgess->contains($obj)) {
                          $this->collPUBadgess->append($obj);
                        }
                      }

                      $this->collPUBadgessPartial = true;
                    }

                    $collPUBadgess->getInternalIterator()->rewind();

                    return $collPUBadgess;
                }

                if ($partial && $this->collPUBadgess) {
                    foreach ($this->collPUBadgess as $obj) {
                        if ($obj->isNew()) {
                            $collPUBadgess[] = $obj;
                        }
                    }
                }

                $this->collPUBadgess = $collPUBadgess;
                $this->collPUBadgessPartial = false;
            }
        }

        return $this->collPUBadgess;
    }

    /**
     * Sets a collection of PUBadges objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $pUBadgess A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return PUser The current object (for fluent API support)
     */
    public function setPUBadgess(PropelCollection $pUBadgess, PropelPDO $con = null)
    {
        $pUBadgessToDelete = $this->getPUBadgess(new Criteria(), $con)->diff($pUBadgess);


        $this->pUBadgessScheduledForDeletion = $pUBadgessToDelete;

        foreach ($pUBadgessToDelete as $pUBadgesRemoved) {
            $pUBadgesRemoved->setPUser(null);
        }

        $this->collPUBadgess = null;
        foreach ($pUBadgess as $pUBadges) {
            $this->addPUBadges($pUBadges);
        }

        $this->collPUBadgess = $pUBadgess;
        $this->collPUBadgessPartial = false;

        return $this;
    }

    /**
     * Returns the number of related PUBadges objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related PUBadges objects.
     * @throws PropelException
     */
    public function countPUBadgess(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collPUBadgessPartial && !$this->isNew();
        if (null === $this->collPUBadgess || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPUBadgess) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPUBadgess());
            }
            $query = PUBadgesQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPUser($this)
                ->count($con);
        }

        return count($this->collPUBadgess);
    }

    /**
     * Method called to associate a PUBadges object to this object
     * through the PUBadges foreign key attribute.
     *
     * @param    PUBadges $l PUBadges
     * @return PUser The current object (for fluent API support)
     */
    public function addPUBadges(PUBadges $l)
    {
        if ($this->collPUBadgess === null) {
            $this->initPUBadgess();
            $this->collPUBadgessPartial = true;
        }

        if (!in_array($l, $this->collPUBadgess->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddPUBadges($l);

            if ($this->pUBadgessScheduledForDeletion and $this->pUBadgessScheduledForDeletion->contains($l)) {
                $this->pUBadgessScheduledForDeletion->remove($this->pUBadgessScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	PUBadges $pUBadges The pUBadges object to add.
     */
    protected function doAddPUBadges($pUBadges)
    {
        $this->collPUBadgess[]= $pUBadges;
        $pUBadges->setPUser($this);
    }

    /**
     * @param	PUBadges $pUBadges The pUBadges object to remove.
     * @return PUser The current object (for fluent API support)
     */
    public function removePUBadges($pUBadges)
    {
        if ($this->getPUBadgess()->contains($pUBadges)) {
            $this->collPUBadgess->remove($this->collPUBadgess->search($pUBadges));
            if (null === $this->pUBadgessScheduledForDeletion) {
                $this->pUBadgessScheduledForDeletion = clone $this->collPUBadgess;
                $this->pUBadgessScheduledForDeletion->clear();
            }
            $this->pUBadgessScheduledForDeletion[]= clone $pUBadges;
            $pUBadges->setPUser(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this PUser is new, it will return
     * an empty collection; or if this PUser has previously
     * been saved, it will retrieve related PUBadgess from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in PUser.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|PUBadges[] List of PUBadges objects
     */
    public function getPUBadgessJoinPRBadge($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PUBadgesQuery::create(null, $criteria);
        $query->joinWith('PRBadge', $join_behavior);

        return $this->getPUBadgess($query, $con);
    }

    /**
     * Clears out the collPUReputations collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return PUser The current object (for fluent API support)
     * @see        addPUReputations()
     */
    public function clearPUReputations()
    {
        $this->collPUReputations = null; // important to set this to null since that means it is uninitialized
        $this->collPUReputationsPartial = null;

        return $this;
    }

    /**
     * reset is the collPUReputations collection loaded partially
     *
     * @return void
     */
    public function resetPartialPUReputations($v = true)
    {
        $this->collPUReputationsPartial = $v;
    }

    /**
     * Initializes the collPUReputations collection.
     *
     * By default this just sets the collPUReputations collection to an empty array (like clearcollPUReputations());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPUReputations($overrideExisting = true)
    {
        if (null !== $this->collPUReputations && !$overrideExisting) {
            return;
        }
        $this->collPUReputations = new PropelObjectCollection();
        $this->collPUReputations->setModel('PUReputation');
    }

    /**
     * Gets an array of PUReputation objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this PUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|PUReputation[] List of PUReputation objects
     * @throws PropelException
     */
    public function getPUReputations($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collPUReputationsPartial && !$this->isNew();
        if (null === $this->collPUReputations || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPUReputations) {
                // return empty collection
                $this->initPUReputations();
            } else {
                $collPUReputations = PUReputationQuery::create(null, $criteria)
                    ->filterByPUser($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collPUReputationsPartial && count($collPUReputations)) {
                      $this->initPUReputations(false);

                      foreach ($collPUReputations as $obj) {
                        if (false == $this->collPUReputations->contains($obj)) {
                          $this->collPUReputations->append($obj);
                        }
                      }

                      $this->collPUReputationsPartial = true;
                    }

                    $collPUReputations->getInternalIterator()->rewind();

                    return $collPUReputations;
                }

                if ($partial && $this->collPUReputations) {
                    foreach ($this->collPUReputations as $obj) {
                        if ($obj->isNew()) {
                            $collPUReputations[] = $obj;
                        }
                    }
                }

                $this->collPUReputations = $collPUReputations;
                $this->collPUReputationsPartial = false;
            }
        }

        return $this->collPUReputations;
    }

    /**
     * Sets a collection of PUReputation objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $pUReputations A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return PUser The current object (for fluent API support)
     */
    public function setPUReputations(PropelCollection $pUReputations, PropelPDO $con = null)
    {
        $pUReputationsToDelete = $this->getPUReputations(new Criteria(), $con)->diff($pUReputations);


        $this->pUReputationsScheduledForDeletion = $pUReputationsToDelete;

        foreach ($pUReputationsToDelete as $pUReputationRemoved) {
            $pUReputationRemoved->setPUser(null);
        }

        $this->collPUReputations = null;
        foreach ($pUReputations as $pUReputation) {
            $this->addPUReputation($pUReputation);
        }

        $this->collPUReputations = $pUReputations;
        $this->collPUReputationsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related PUReputation objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related PUReputation objects.
     * @throws PropelException
     */
    public function countPUReputations(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collPUReputationsPartial && !$this->isNew();
        if (null === $this->collPUReputations || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPUReputations) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPUReputations());
            }
            $query = PUReputationQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPUser($this)
                ->count($con);
        }

        return count($this->collPUReputations);
    }

    /**
     * Method called to associate a PUReputation object to this object
     * through the PUReputation foreign key attribute.
     *
     * @param    PUReputation $l PUReputation
     * @return PUser The current object (for fluent API support)
     */
    public function addPUReputation(PUReputation $l)
    {
        if ($this->collPUReputations === null) {
            $this->initPUReputations();
            $this->collPUReputationsPartial = true;
        }

        if (!in_array($l, $this->collPUReputations->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddPUReputation($l);

            if ($this->pUReputationsScheduledForDeletion and $this->pUReputationsScheduledForDeletion->contains($l)) {
                $this->pUReputationsScheduledForDeletion->remove($this->pUReputationsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	PUReputation $pUReputation The pUReputation object to add.
     */
    protected function doAddPUReputation($pUReputation)
    {
        $this->collPUReputations[]= $pUReputation;
        $pUReputation->setPUser($this);
    }

    /**
     * @param	PUReputation $pUReputation The pUReputation object to remove.
     * @return PUser The current object (for fluent API support)
     */
    public function removePUReputation($pUReputation)
    {
        if ($this->getPUReputations()->contains($pUReputation)) {
            $this->collPUReputations->remove($this->collPUReputations->search($pUReputation));
            if (null === $this->pUReputationsScheduledForDeletion) {
                $this->pUReputationsScheduledForDeletion = clone $this->collPUReputations;
                $this->pUReputationsScheduledForDeletion->clear();
            }
            $this->pUReputationsScheduledForDeletion[]= clone $pUReputation;
            $pUReputation->setPUser(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this PUser is new, it will return
     * an empty collection; or if this PUser has previously
     * been saved, it will retrieve related PUReputations from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in PUser.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|PUReputation[] List of PUReputation objects
     */
    public function getPUReputationsJoinPRAction($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PUReputationQuery::create(null, $criteria);
        $query->joinWith('PRAction', $join_behavior);

        return $this->getPUReputations($query, $con);
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

                      foreach ($collPuTaggedTPUsers as $obj) {
                        if (false == $this->collPuTaggedTPUsers->contains($obj)) {
                          $this->collPuTaggedTPUsers->append($obj);
                        }
                      }

                      $this->collPuTaggedTPUsersPartial = true;
                    }

                    $collPuTaggedTPUsers->getInternalIterator()->rewind();

                    return $collPuTaggedTPUsers;
                }

                if ($partial && $this->collPuTaggedTPUsers) {
                    foreach ($this->collPuTaggedTPUsers as $obj) {
                        if ($obj->isNew()) {
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


        $this->puTaggedTPUsersScheduledForDeletion = $puTaggedTPUsersToDelete;

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

            if ($partial && !$criteria) {
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

            if ($this->puTaggedTPUsersScheduledForDeletion and $this->puTaggedTPUsersScheduledForDeletion->contains($l)) {
                $this->puTaggedTPUsersScheduledForDeletion->remove($this->puTaggedTPUsersScheduledForDeletion->search($l));
            }
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
     * Clears out the collPuFollowTPUsers collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return PUser The current object (for fluent API support)
     * @see        addPuFollowTPUsers()
     */
    public function clearPuFollowTPUsers()
    {
        $this->collPuFollowTPUsers = null; // important to set this to null since that means it is uninitialized
        $this->collPuFollowTPUsersPartial = null;

        return $this;
    }

    /**
     * reset is the collPuFollowTPUsers collection loaded partially
     *
     * @return void
     */
    public function resetPartialPuFollowTPUsers($v = true)
    {
        $this->collPuFollowTPUsersPartial = $v;
    }

    /**
     * Initializes the collPuFollowTPUsers collection.
     *
     * By default this just sets the collPuFollowTPUsers collection to an empty array (like clearcollPuFollowTPUsers());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPuFollowTPUsers($overrideExisting = true)
    {
        if (null !== $this->collPuFollowTPUsers && !$overrideExisting) {
            return;
        }
        $this->collPuFollowTPUsers = new PropelObjectCollection();
        $this->collPuFollowTPUsers->setModel('PUFollowT');
    }

    /**
     * Gets an array of PUFollowT objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this PUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|PUFollowT[] List of PUFollowT objects
     * @throws PropelException
     */
    public function getPuFollowTPUsers($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collPuFollowTPUsersPartial && !$this->isNew();
        if (null === $this->collPuFollowTPUsers || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPuFollowTPUsers) {
                // return empty collection
                $this->initPuFollowTPUsers();
            } else {
                $collPuFollowTPUsers = PUFollowTQuery::create(null, $criteria)
                    ->filterByPuFollowTPUser($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collPuFollowTPUsersPartial && count($collPuFollowTPUsers)) {
                      $this->initPuFollowTPUsers(false);

                      foreach ($collPuFollowTPUsers as $obj) {
                        if (false == $this->collPuFollowTPUsers->contains($obj)) {
                          $this->collPuFollowTPUsers->append($obj);
                        }
                      }

                      $this->collPuFollowTPUsersPartial = true;
                    }

                    $collPuFollowTPUsers->getInternalIterator()->rewind();

                    return $collPuFollowTPUsers;
                }

                if ($partial && $this->collPuFollowTPUsers) {
                    foreach ($this->collPuFollowTPUsers as $obj) {
                        if ($obj->isNew()) {
                            $collPuFollowTPUsers[] = $obj;
                        }
                    }
                }

                $this->collPuFollowTPUsers = $collPuFollowTPUsers;
                $this->collPuFollowTPUsersPartial = false;
            }
        }

        return $this->collPuFollowTPUsers;
    }

    /**
     * Sets a collection of PuFollowTPUser objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $puFollowTPUsers A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return PUser The current object (for fluent API support)
     */
    public function setPuFollowTPUsers(PropelCollection $puFollowTPUsers, PropelPDO $con = null)
    {
        $puFollowTPUsersToDelete = $this->getPuFollowTPUsers(new Criteria(), $con)->diff($puFollowTPUsers);


        $this->puFollowTPUsersScheduledForDeletion = $puFollowTPUsersToDelete;

        foreach ($puFollowTPUsersToDelete as $puFollowTPUserRemoved) {
            $puFollowTPUserRemoved->setPuFollowTPUser(null);
        }

        $this->collPuFollowTPUsers = null;
        foreach ($puFollowTPUsers as $puFollowTPUser) {
            $this->addPuFollowTPUser($puFollowTPUser);
        }

        $this->collPuFollowTPUsers = $puFollowTPUsers;
        $this->collPuFollowTPUsersPartial = false;

        return $this;
    }

    /**
     * Returns the number of related PUFollowT objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related PUFollowT objects.
     * @throws PropelException
     */
    public function countPuFollowTPUsers(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collPuFollowTPUsersPartial && !$this->isNew();
        if (null === $this->collPuFollowTPUsers || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPuFollowTPUsers) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPuFollowTPUsers());
            }
            $query = PUFollowTQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPuFollowTPUser($this)
                ->count($con);
        }

        return count($this->collPuFollowTPUsers);
    }

    /**
     * Method called to associate a PUFollowT object to this object
     * through the PUFollowT foreign key attribute.
     *
     * @param    PUFollowT $l PUFollowT
     * @return PUser The current object (for fluent API support)
     */
    public function addPuFollowTPUser(PUFollowT $l)
    {
        if ($this->collPuFollowTPUsers === null) {
            $this->initPuFollowTPUsers();
            $this->collPuFollowTPUsersPartial = true;
        }

        if (!in_array($l, $this->collPuFollowTPUsers->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddPuFollowTPUser($l);

            if ($this->puFollowTPUsersScheduledForDeletion and $this->puFollowTPUsersScheduledForDeletion->contains($l)) {
                $this->puFollowTPUsersScheduledForDeletion->remove($this->puFollowTPUsersScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	PuFollowTPUser $puFollowTPUser The puFollowTPUser object to add.
     */
    protected function doAddPuFollowTPUser($puFollowTPUser)
    {
        $this->collPuFollowTPUsers[]= $puFollowTPUser;
        $puFollowTPUser->setPuFollowTPUser($this);
    }

    /**
     * @param	PuFollowTPUser $puFollowTPUser The puFollowTPUser object to remove.
     * @return PUser The current object (for fluent API support)
     */
    public function removePuFollowTPUser($puFollowTPUser)
    {
        if ($this->getPuFollowTPUsers()->contains($puFollowTPUser)) {
            $this->collPuFollowTPUsers->remove($this->collPuFollowTPUsers->search($puFollowTPUser));
            if (null === $this->puFollowTPUsersScheduledForDeletion) {
                $this->puFollowTPUsersScheduledForDeletion = clone $this->collPuFollowTPUsers;
                $this->puFollowTPUsersScheduledForDeletion->clear();
            }
            $this->puFollowTPUsersScheduledForDeletion[]= clone $puFollowTPUser;
            $puFollowTPUser->setPuFollowTPUser(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this PUser is new, it will return
     * an empty collection; or if this PUser has previously
     * been saved, it will retrieve related PuFollowTPUsers from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in PUser.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|PUFollowT[] List of PUFollowT objects
     */
    public function getPuFollowTPUsersJoinPuFollowTPTag($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PUFollowTQuery::create(null, $criteria);
        $query->joinWith('PuFollowTPTag', $join_behavior);

        return $this->getPuFollowTPUsers($query, $con);
    }

    /**
     * Clears out the collPURoleQs collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return PUser The current object (for fluent API support)
     * @see        addPURoleQs()
     */
    public function clearPURoleQs()
    {
        $this->collPURoleQs = null; // important to set this to null since that means it is uninitialized
        $this->collPURoleQsPartial = null;

        return $this;
    }

    /**
     * reset is the collPURoleQs collection loaded partially
     *
     * @return void
     */
    public function resetPartialPURoleQs($v = true)
    {
        $this->collPURoleQsPartial = $v;
    }

    /**
     * Initializes the collPURoleQs collection.
     *
     * By default this just sets the collPURoleQs collection to an empty array (like clearcollPURoleQs());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPURoleQs($overrideExisting = true)
    {
        if (null !== $this->collPURoleQs && !$overrideExisting) {
            return;
        }
        $this->collPURoleQs = new PropelObjectCollection();
        $this->collPURoleQs->setModel('PURoleQ');
    }

    /**
     * Gets an array of PURoleQ objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this PUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|PURoleQ[] List of PURoleQ objects
     * @throws PropelException
     */
    public function getPURoleQs($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collPURoleQsPartial && !$this->isNew();
        if (null === $this->collPURoleQs || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPURoleQs) {
                // return empty collection
                $this->initPURoleQs();
            } else {
                $collPURoleQs = PURoleQQuery::create(null, $criteria)
                    ->filterByPUser($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collPURoleQsPartial && count($collPURoleQs)) {
                      $this->initPURoleQs(false);

                      foreach ($collPURoleQs as $obj) {
                        if (false == $this->collPURoleQs->contains($obj)) {
                          $this->collPURoleQs->append($obj);
                        }
                      }

                      $this->collPURoleQsPartial = true;
                    }

                    $collPURoleQs->getInternalIterator()->rewind();

                    return $collPURoleQs;
                }

                if ($partial && $this->collPURoleQs) {
                    foreach ($this->collPURoleQs as $obj) {
                        if ($obj->isNew()) {
                            $collPURoleQs[] = $obj;
                        }
                    }
                }

                $this->collPURoleQs = $collPURoleQs;
                $this->collPURoleQsPartial = false;
            }
        }

        return $this->collPURoleQs;
    }

    /**
     * Sets a collection of PURoleQ objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $pURoleQs A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return PUser The current object (for fluent API support)
     */
    public function setPURoleQs(PropelCollection $pURoleQs, PropelPDO $con = null)
    {
        $pURoleQsToDelete = $this->getPURoleQs(new Criteria(), $con)->diff($pURoleQs);


        $this->pURoleQsScheduledForDeletion = $pURoleQsToDelete;

        foreach ($pURoleQsToDelete as $pURoleQRemoved) {
            $pURoleQRemoved->setPUser(null);
        }

        $this->collPURoleQs = null;
        foreach ($pURoleQs as $pURoleQ) {
            $this->addPURoleQ($pURoleQ);
        }

        $this->collPURoleQs = $pURoleQs;
        $this->collPURoleQsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related PURoleQ objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related PURoleQ objects.
     * @throws PropelException
     */
    public function countPURoleQs(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collPURoleQsPartial && !$this->isNew();
        if (null === $this->collPURoleQs || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPURoleQs) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPURoleQs());
            }
            $query = PURoleQQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPUser($this)
                ->count($con);
        }

        return count($this->collPURoleQs);
    }

    /**
     * Method called to associate a PURoleQ object to this object
     * through the PURoleQ foreign key attribute.
     *
     * @param    PURoleQ $l PURoleQ
     * @return PUser The current object (for fluent API support)
     */
    public function addPURoleQ(PURoleQ $l)
    {
        if ($this->collPURoleQs === null) {
            $this->initPURoleQs();
            $this->collPURoleQsPartial = true;
        }

        if (!in_array($l, $this->collPURoleQs->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddPURoleQ($l);

            if ($this->pURoleQsScheduledForDeletion and $this->pURoleQsScheduledForDeletion->contains($l)) {
                $this->pURoleQsScheduledForDeletion->remove($this->pURoleQsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	PURoleQ $pURoleQ The pURoleQ object to add.
     */
    protected function doAddPURoleQ($pURoleQ)
    {
        $this->collPURoleQs[]= $pURoleQ;
        $pURoleQ->setPUser($this);
    }

    /**
     * @param	PURoleQ $pURoleQ The pURoleQ object to remove.
     * @return PUser The current object (for fluent API support)
     */
    public function removePURoleQ($pURoleQ)
    {
        if ($this->getPURoleQs()->contains($pURoleQ)) {
            $this->collPURoleQs->remove($this->collPURoleQs->search($pURoleQ));
            if (null === $this->pURoleQsScheduledForDeletion) {
                $this->pURoleQsScheduledForDeletion = clone $this->collPURoleQs;
                $this->pURoleQsScheduledForDeletion->clear();
            }
            $this->pURoleQsScheduledForDeletion[]= clone $pURoleQ;
            $pURoleQ->setPUser(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this PUser is new, it will return
     * an empty collection; or if this PUser has previously
     * been saved, it will retrieve related PURoleQs from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in PUser.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|PURoleQ[] List of PURoleQ objects
     */
    public function getPURoleQsJoinPQualification($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PURoleQQuery::create(null, $criteria);
        $query->joinWith('PQualification', $join_behavior);

        return $this->getPURoleQs($query, $con);
    }

    /**
     * Clears out the collPUMandates collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return PUser The current object (for fluent API support)
     * @see        addPUMandates()
     */
    public function clearPUMandates()
    {
        $this->collPUMandates = null; // important to set this to null since that means it is uninitialized
        $this->collPUMandatesPartial = null;

        return $this;
    }

    /**
     * reset is the collPUMandates collection loaded partially
     *
     * @return void
     */
    public function resetPartialPUMandates($v = true)
    {
        $this->collPUMandatesPartial = $v;
    }

    /**
     * Initializes the collPUMandates collection.
     *
     * By default this just sets the collPUMandates collection to an empty array (like clearcollPUMandates());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPUMandates($overrideExisting = true)
    {
        if (null !== $this->collPUMandates && !$overrideExisting) {
            return;
        }
        $this->collPUMandates = new PropelObjectCollection();
        $this->collPUMandates->setModel('PUMandate');
    }

    /**
     * Gets an array of PUMandate objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this PUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|PUMandate[] List of PUMandate objects
     * @throws PropelException
     */
    public function getPUMandates($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collPUMandatesPartial && !$this->isNew();
        if (null === $this->collPUMandates || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPUMandates) {
                // return empty collection
                $this->initPUMandates();
            } else {
                $collPUMandates = PUMandateQuery::create(null, $criteria)
                    ->filterByPUser($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collPUMandatesPartial && count($collPUMandates)) {
                      $this->initPUMandates(false);

                      foreach ($collPUMandates as $obj) {
                        if (false == $this->collPUMandates->contains($obj)) {
                          $this->collPUMandates->append($obj);
                        }
                      }

                      $this->collPUMandatesPartial = true;
                    }

                    $collPUMandates->getInternalIterator()->rewind();

                    return $collPUMandates;
                }

                if ($partial && $this->collPUMandates) {
                    foreach ($this->collPUMandates as $obj) {
                        if ($obj->isNew()) {
                            $collPUMandates[] = $obj;
                        }
                    }
                }

                $this->collPUMandates = $collPUMandates;
                $this->collPUMandatesPartial = false;
            }
        }

        return $this->collPUMandates;
    }

    /**
     * Sets a collection of PUMandate objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $pUMandates A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return PUser The current object (for fluent API support)
     */
    public function setPUMandates(PropelCollection $pUMandates, PropelPDO $con = null)
    {
        $pUMandatesToDelete = $this->getPUMandates(new Criteria(), $con)->diff($pUMandates);


        $this->pUMandatesScheduledForDeletion = $pUMandatesToDelete;

        foreach ($pUMandatesToDelete as $pUMandateRemoved) {
            $pUMandateRemoved->setPUser(null);
        }

        $this->collPUMandates = null;
        foreach ($pUMandates as $pUMandate) {
            $this->addPUMandate($pUMandate);
        }

        $this->collPUMandates = $pUMandates;
        $this->collPUMandatesPartial = false;

        return $this;
    }

    /**
     * Returns the number of related PUMandate objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related PUMandate objects.
     * @throws PropelException
     */
    public function countPUMandates(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collPUMandatesPartial && !$this->isNew();
        if (null === $this->collPUMandates || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPUMandates) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPUMandates());
            }
            $query = PUMandateQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPUser($this)
                ->count($con);
        }

        return count($this->collPUMandates);
    }

    /**
     * Method called to associate a PUMandate object to this object
     * through the PUMandate foreign key attribute.
     *
     * @param    PUMandate $l PUMandate
     * @return PUser The current object (for fluent API support)
     */
    public function addPUMandate(PUMandate $l)
    {
        if ($this->collPUMandates === null) {
            $this->initPUMandates();
            $this->collPUMandatesPartial = true;
        }

        if (!in_array($l, $this->collPUMandates->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddPUMandate($l);

            if ($this->pUMandatesScheduledForDeletion and $this->pUMandatesScheduledForDeletion->contains($l)) {
                $this->pUMandatesScheduledForDeletion->remove($this->pUMandatesScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	PUMandate $pUMandate The pUMandate object to add.
     */
    protected function doAddPUMandate($pUMandate)
    {
        $this->collPUMandates[]= $pUMandate;
        $pUMandate->setPUser($this);
    }

    /**
     * @param	PUMandate $pUMandate The pUMandate object to remove.
     * @return PUser The current object (for fluent API support)
     */
    public function removePUMandate($pUMandate)
    {
        if ($this->getPUMandates()->contains($pUMandate)) {
            $this->collPUMandates->remove($this->collPUMandates->search($pUMandate));
            if (null === $this->pUMandatesScheduledForDeletion) {
                $this->pUMandatesScheduledForDeletion = clone $this->collPUMandates;
                $this->pUMandatesScheduledForDeletion->clear();
            }
            $this->pUMandatesScheduledForDeletion[]= clone $pUMandate;
            $pUMandate->setPUser(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this PUser is new, it will return
     * an empty collection; or if this PUser has previously
     * been saved, it will retrieve related PUMandates from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in PUser.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|PUMandate[] List of PUMandate objects
     */
    public function getPUMandatesJoinPQType($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PUMandateQuery::create(null, $criteria);
        $query->joinWith('PQType', $join_behavior);

        return $this->getPUMandates($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this PUser is new, it will return
     * an empty collection; or if this PUser has previously
     * been saved, it will retrieve related PUMandates from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in PUser.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|PUMandate[] List of PUMandate objects
     */
    public function getPUMandatesJoinPQMandate($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PUMandateQuery::create(null, $criteria);
        $query->joinWith('PQMandate', $join_behavior);

        return $this->getPUMandates($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this PUser is new, it will return
     * an empty collection; or if this PUser has previously
     * been saved, it will retrieve related PUMandates from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in PUser.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|PUMandate[] List of PUMandate objects
     */
    public function getPUMandatesJoinPQOrganization($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PUMandateQuery::create(null, $criteria);
        $query->joinWith('PQOrganization', $join_behavior);

        return $this->getPUMandates($query, $con);
    }

    /**
     * Clears out the collPUAffinityQos collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return PUser The current object (for fluent API support)
     * @see        addPUAffinityQos()
     */
    public function clearPUAffinityQos()
    {
        $this->collPUAffinityQos = null; // important to set this to null since that means it is uninitialized
        $this->collPUAffinityQosPartial = null;

        return $this;
    }

    /**
     * reset is the collPUAffinityQos collection loaded partially
     *
     * @return void
     */
    public function resetPartialPUAffinityQos($v = true)
    {
        $this->collPUAffinityQosPartial = $v;
    }

    /**
     * Initializes the collPUAffinityQos collection.
     *
     * By default this just sets the collPUAffinityQos collection to an empty array (like clearcollPUAffinityQos());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPUAffinityQos($overrideExisting = true)
    {
        if (null !== $this->collPUAffinityQos && !$overrideExisting) {
            return;
        }
        $this->collPUAffinityQos = new PropelObjectCollection();
        $this->collPUAffinityQos->setModel('PUAffinityQO');
    }

    /**
     * Gets an array of PUAffinityQO objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this PUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|PUAffinityQO[] List of PUAffinityQO objects
     * @throws PropelException
     */
    public function getPUAffinityQos($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collPUAffinityQosPartial && !$this->isNew();
        if (null === $this->collPUAffinityQos || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPUAffinityQos) {
                // return empty collection
                $this->initPUAffinityQos();
            } else {
                $collPUAffinityQos = PUAffinityQOQuery::create(null, $criteria)
                    ->filterByPUser($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collPUAffinityQosPartial && count($collPUAffinityQos)) {
                      $this->initPUAffinityQos(false);

                      foreach ($collPUAffinityQos as $obj) {
                        if (false == $this->collPUAffinityQos->contains($obj)) {
                          $this->collPUAffinityQos->append($obj);
                        }
                      }

                      $this->collPUAffinityQosPartial = true;
                    }

                    $collPUAffinityQos->getInternalIterator()->rewind();

                    return $collPUAffinityQos;
                }

                if ($partial && $this->collPUAffinityQos) {
                    foreach ($this->collPUAffinityQos as $obj) {
                        if ($obj->isNew()) {
                            $collPUAffinityQos[] = $obj;
                        }
                    }
                }

                $this->collPUAffinityQos = $collPUAffinityQos;
                $this->collPUAffinityQosPartial = false;
            }
        }

        return $this->collPUAffinityQos;
    }

    /**
     * Sets a collection of PUAffinityQO objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $pUAffinityQos A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return PUser The current object (for fluent API support)
     */
    public function setPUAffinityQos(PropelCollection $pUAffinityQos, PropelPDO $con = null)
    {
        $pUAffinityQosToDelete = $this->getPUAffinityQos(new Criteria(), $con)->diff($pUAffinityQos);


        $this->pUAffinityQosScheduledForDeletion = $pUAffinityQosToDelete;

        foreach ($pUAffinityQosToDelete as $pUAffinityQORemoved) {
            $pUAffinityQORemoved->setPUser(null);
        }

        $this->collPUAffinityQos = null;
        foreach ($pUAffinityQos as $pUAffinityQO) {
            $this->addPUAffinityQO($pUAffinityQO);
        }

        $this->collPUAffinityQos = $pUAffinityQos;
        $this->collPUAffinityQosPartial = false;

        return $this;
    }

    /**
     * Returns the number of related PUAffinityQO objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related PUAffinityQO objects.
     * @throws PropelException
     */
    public function countPUAffinityQos(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collPUAffinityQosPartial && !$this->isNew();
        if (null === $this->collPUAffinityQos || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPUAffinityQos) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPUAffinityQos());
            }
            $query = PUAffinityQOQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPUser($this)
                ->count($con);
        }

        return count($this->collPUAffinityQos);
    }

    /**
     * Method called to associate a PUAffinityQO object to this object
     * through the PUAffinityQO foreign key attribute.
     *
     * @param    PUAffinityQO $l PUAffinityQO
     * @return PUser The current object (for fluent API support)
     */
    public function addPUAffinityQO(PUAffinityQO $l)
    {
        if ($this->collPUAffinityQos === null) {
            $this->initPUAffinityQos();
            $this->collPUAffinityQosPartial = true;
        }

        if (!in_array($l, $this->collPUAffinityQos->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddPUAffinityQO($l);

            if ($this->pUAffinityQosScheduledForDeletion and $this->pUAffinityQosScheduledForDeletion->contains($l)) {
                $this->pUAffinityQosScheduledForDeletion->remove($this->pUAffinityQosScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	PUAffinityQO $pUAffinityQO The pUAffinityQO object to add.
     */
    protected function doAddPUAffinityQO($pUAffinityQO)
    {
        $this->collPUAffinityQos[]= $pUAffinityQO;
        $pUAffinityQO->setPUser($this);
    }

    /**
     * @param	PUAffinityQO $pUAffinityQO The pUAffinityQO object to remove.
     * @return PUser The current object (for fluent API support)
     */
    public function removePUAffinityQO($pUAffinityQO)
    {
        if ($this->getPUAffinityQos()->contains($pUAffinityQO)) {
            $this->collPUAffinityQos->remove($this->collPUAffinityQos->search($pUAffinityQO));
            if (null === $this->pUAffinityQosScheduledForDeletion) {
                $this->pUAffinityQosScheduledForDeletion = clone $this->collPUAffinityQos;
                $this->pUAffinityQosScheduledForDeletion->clear();
            }
            $this->pUAffinityQosScheduledForDeletion[]= clone $pUAffinityQO;
            $pUAffinityQO->setPUser(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this PUser is new, it will return
     * an empty collection; or if this PUser has previously
     * been saved, it will retrieve related PUAffinityQos from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in PUser.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|PUAffinityQO[] List of PUAffinityQO objects
     */
    public function getPUAffinityQosJoinPQOrganization($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PUAffinityQOQuery::create(null, $criteria);
        $query->joinWith('PQOrganization', $join_behavior);

        return $this->getPUAffinityQos($query, $con);
    }

    /**
     * Clears out the collPUNotifiedPNs collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return PUser The current object (for fluent API support)
     * @see        addPUNotifiedPNs()
     */
    public function clearPUNotifiedPNs()
    {
        $this->collPUNotifiedPNs = null; // important to set this to null since that means it is uninitialized
        $this->collPUNotifiedPNsPartial = null;

        return $this;
    }

    /**
     * reset is the collPUNotifiedPNs collection loaded partially
     *
     * @return void
     */
    public function resetPartialPUNotifiedPNs($v = true)
    {
        $this->collPUNotifiedPNsPartial = $v;
    }

    /**
     * Initializes the collPUNotifiedPNs collection.
     *
     * By default this just sets the collPUNotifiedPNs collection to an empty array (like clearcollPUNotifiedPNs());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPUNotifiedPNs($overrideExisting = true)
    {
        if (null !== $this->collPUNotifiedPNs && !$overrideExisting) {
            return;
        }
        $this->collPUNotifiedPNs = new PropelObjectCollection();
        $this->collPUNotifiedPNs->setModel('PUNotifiedPN');
    }

    /**
     * Gets an array of PUNotifiedPN objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this PUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|PUNotifiedPN[] List of PUNotifiedPN objects
     * @throws PropelException
     */
    public function getPUNotifiedPNs($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collPUNotifiedPNsPartial && !$this->isNew();
        if (null === $this->collPUNotifiedPNs || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPUNotifiedPNs) {
                // return empty collection
                $this->initPUNotifiedPNs();
            } else {
                $collPUNotifiedPNs = PUNotifiedPNQuery::create(null, $criteria)
                    ->filterByPUser($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collPUNotifiedPNsPartial && count($collPUNotifiedPNs)) {
                      $this->initPUNotifiedPNs(false);

                      foreach ($collPUNotifiedPNs as $obj) {
                        if (false == $this->collPUNotifiedPNs->contains($obj)) {
                          $this->collPUNotifiedPNs->append($obj);
                        }
                      }

                      $this->collPUNotifiedPNsPartial = true;
                    }

                    $collPUNotifiedPNs->getInternalIterator()->rewind();

                    return $collPUNotifiedPNs;
                }

                if ($partial && $this->collPUNotifiedPNs) {
                    foreach ($this->collPUNotifiedPNs as $obj) {
                        if ($obj->isNew()) {
                            $collPUNotifiedPNs[] = $obj;
                        }
                    }
                }

                $this->collPUNotifiedPNs = $collPUNotifiedPNs;
                $this->collPUNotifiedPNsPartial = false;
            }
        }

        return $this->collPUNotifiedPNs;
    }

    /**
     * Sets a collection of PUNotifiedPN objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $pUNotifiedPNs A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return PUser The current object (for fluent API support)
     */
    public function setPUNotifiedPNs(PropelCollection $pUNotifiedPNs, PropelPDO $con = null)
    {
        $pUNotifiedPNsToDelete = $this->getPUNotifiedPNs(new Criteria(), $con)->diff($pUNotifiedPNs);


        $this->pUNotifiedPNsScheduledForDeletion = $pUNotifiedPNsToDelete;

        foreach ($pUNotifiedPNsToDelete as $pUNotifiedPNRemoved) {
            $pUNotifiedPNRemoved->setPUser(null);
        }

        $this->collPUNotifiedPNs = null;
        foreach ($pUNotifiedPNs as $pUNotifiedPN) {
            $this->addPUNotifiedPN($pUNotifiedPN);
        }

        $this->collPUNotifiedPNs = $pUNotifiedPNs;
        $this->collPUNotifiedPNsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related PUNotifiedPN objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related PUNotifiedPN objects.
     * @throws PropelException
     */
    public function countPUNotifiedPNs(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collPUNotifiedPNsPartial && !$this->isNew();
        if (null === $this->collPUNotifiedPNs || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPUNotifiedPNs) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPUNotifiedPNs());
            }
            $query = PUNotifiedPNQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPUser($this)
                ->count($con);
        }

        return count($this->collPUNotifiedPNs);
    }

    /**
     * Method called to associate a PUNotifiedPN object to this object
     * through the PUNotifiedPN foreign key attribute.
     *
     * @param    PUNotifiedPN $l PUNotifiedPN
     * @return PUser The current object (for fluent API support)
     */
    public function addPUNotifiedPN(PUNotifiedPN $l)
    {
        if ($this->collPUNotifiedPNs === null) {
            $this->initPUNotifiedPNs();
            $this->collPUNotifiedPNsPartial = true;
        }

        if (!in_array($l, $this->collPUNotifiedPNs->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddPUNotifiedPN($l);

            if ($this->pUNotifiedPNsScheduledForDeletion and $this->pUNotifiedPNsScheduledForDeletion->contains($l)) {
                $this->pUNotifiedPNsScheduledForDeletion->remove($this->pUNotifiedPNsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	PUNotifiedPN $pUNotifiedPN The pUNotifiedPN object to add.
     */
    protected function doAddPUNotifiedPN($pUNotifiedPN)
    {
        $this->collPUNotifiedPNs[]= $pUNotifiedPN;
        $pUNotifiedPN->setPUser($this);
    }

    /**
     * @param	PUNotifiedPN $pUNotifiedPN The pUNotifiedPN object to remove.
     * @return PUser The current object (for fluent API support)
     */
    public function removePUNotifiedPN($pUNotifiedPN)
    {
        if ($this->getPUNotifiedPNs()->contains($pUNotifiedPN)) {
            $this->collPUNotifiedPNs->remove($this->collPUNotifiedPNs->search($pUNotifiedPN));
            if (null === $this->pUNotifiedPNsScheduledForDeletion) {
                $this->pUNotifiedPNsScheduledForDeletion = clone $this->collPUNotifiedPNs;
                $this->pUNotifiedPNsScheduledForDeletion->clear();
            }
            $this->pUNotifiedPNsScheduledForDeletion[]= clone $pUNotifiedPN;
            $pUNotifiedPN->setPUser(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this PUser is new, it will return
     * an empty collection; or if this PUser has previously
     * been saved, it will retrieve related PUNotifiedPNs from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in PUser.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|PUNotifiedPN[] List of PUNotifiedPN objects
     */
    public function getPUNotifiedPNsJoinPNotification($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PUNotifiedPNQuery::create(null, $criteria);
        $query->joinWith('PNotification', $join_behavior);

        return $this->getPUNotifiedPNs($query, $con);
    }

    /**
     * Clears out the collPUSubscribeNos collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return PUser The current object (for fluent API support)
     * @see        addPUSubscribeNos()
     */
    public function clearPUSubscribeNos()
    {
        $this->collPUSubscribeNos = null; // important to set this to null since that means it is uninitialized
        $this->collPUSubscribeNosPartial = null;

        return $this;
    }

    /**
     * reset is the collPUSubscribeNos collection loaded partially
     *
     * @return void
     */
    public function resetPartialPUSubscribeNos($v = true)
    {
        $this->collPUSubscribeNosPartial = $v;
    }

    /**
     * Initializes the collPUSubscribeNos collection.
     *
     * By default this just sets the collPUSubscribeNos collection to an empty array (like clearcollPUSubscribeNos());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPUSubscribeNos($overrideExisting = true)
    {
        if (null !== $this->collPUSubscribeNos && !$overrideExisting) {
            return;
        }
        $this->collPUSubscribeNos = new PropelObjectCollection();
        $this->collPUSubscribeNos->setModel('PUSubscribeNO');
    }

    /**
     * Gets an array of PUSubscribeNO objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this PUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|PUSubscribeNO[] List of PUSubscribeNO objects
     * @throws PropelException
     */
    public function getPUSubscribeNos($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collPUSubscribeNosPartial && !$this->isNew();
        if (null === $this->collPUSubscribeNos || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPUSubscribeNos) {
                // return empty collection
                $this->initPUSubscribeNos();
            } else {
                $collPUSubscribeNos = PUSubscribeNOQuery::create(null, $criteria)
                    ->filterByPUser($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collPUSubscribeNosPartial && count($collPUSubscribeNos)) {
                      $this->initPUSubscribeNos(false);

                      foreach ($collPUSubscribeNos as $obj) {
                        if (false == $this->collPUSubscribeNos->contains($obj)) {
                          $this->collPUSubscribeNos->append($obj);
                        }
                      }

                      $this->collPUSubscribeNosPartial = true;
                    }

                    $collPUSubscribeNos->getInternalIterator()->rewind();

                    return $collPUSubscribeNos;
                }

                if ($partial && $this->collPUSubscribeNos) {
                    foreach ($this->collPUSubscribeNos as $obj) {
                        if ($obj->isNew()) {
                            $collPUSubscribeNos[] = $obj;
                        }
                    }
                }

                $this->collPUSubscribeNos = $collPUSubscribeNos;
                $this->collPUSubscribeNosPartial = false;
            }
        }

        return $this->collPUSubscribeNos;
    }

    /**
     * Sets a collection of PUSubscribeNO objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $pUSubscribeNos A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return PUser The current object (for fluent API support)
     */
    public function setPUSubscribeNos(PropelCollection $pUSubscribeNos, PropelPDO $con = null)
    {
        $pUSubscribeNosToDelete = $this->getPUSubscribeNos(new Criteria(), $con)->diff($pUSubscribeNos);


        $this->pUSubscribeNosScheduledForDeletion = $pUSubscribeNosToDelete;

        foreach ($pUSubscribeNosToDelete as $pUSubscribeNORemoved) {
            $pUSubscribeNORemoved->setPUser(null);
        }

        $this->collPUSubscribeNos = null;
        foreach ($pUSubscribeNos as $pUSubscribeNO) {
            $this->addPUSubscribeNO($pUSubscribeNO);
        }

        $this->collPUSubscribeNos = $pUSubscribeNos;
        $this->collPUSubscribeNosPartial = false;

        return $this;
    }

    /**
     * Returns the number of related PUSubscribeNO objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related PUSubscribeNO objects.
     * @throws PropelException
     */
    public function countPUSubscribeNos(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collPUSubscribeNosPartial && !$this->isNew();
        if (null === $this->collPUSubscribeNos || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPUSubscribeNos) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPUSubscribeNos());
            }
            $query = PUSubscribeNOQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPUser($this)
                ->count($con);
        }

        return count($this->collPUSubscribeNos);
    }

    /**
     * Method called to associate a PUSubscribeNO object to this object
     * through the PUSubscribeNO foreign key attribute.
     *
     * @param    PUSubscribeNO $l PUSubscribeNO
     * @return PUser The current object (for fluent API support)
     */
    public function addPUSubscribeNO(PUSubscribeNO $l)
    {
        if ($this->collPUSubscribeNos === null) {
            $this->initPUSubscribeNos();
            $this->collPUSubscribeNosPartial = true;
        }

        if (!in_array($l, $this->collPUSubscribeNos->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddPUSubscribeNO($l);

            if ($this->pUSubscribeNosScheduledForDeletion and $this->pUSubscribeNosScheduledForDeletion->contains($l)) {
                $this->pUSubscribeNosScheduledForDeletion->remove($this->pUSubscribeNosScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	PUSubscribeNO $pUSubscribeNO The pUSubscribeNO object to add.
     */
    protected function doAddPUSubscribeNO($pUSubscribeNO)
    {
        $this->collPUSubscribeNos[]= $pUSubscribeNO;
        $pUSubscribeNO->setPUser($this);
    }

    /**
     * @param	PUSubscribeNO $pUSubscribeNO The pUSubscribeNO object to remove.
     * @return PUser The current object (for fluent API support)
     */
    public function removePUSubscribeNO($pUSubscribeNO)
    {
        if ($this->getPUSubscribeNos()->contains($pUSubscribeNO)) {
            $this->collPUSubscribeNos->remove($this->collPUSubscribeNos->search($pUSubscribeNO));
            if (null === $this->pUSubscribeNosScheduledForDeletion) {
                $this->pUSubscribeNosScheduledForDeletion = clone $this->collPUSubscribeNos;
                $this->pUSubscribeNosScheduledForDeletion->clear();
            }
            $this->pUSubscribeNosScheduledForDeletion[]= clone $pUSubscribeNO;
            $pUSubscribeNO->setPUser(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this PUser is new, it will return
     * an empty collection; or if this PUser has previously
     * been saved, it will retrieve related PUSubscribeNos from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in PUser.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|PUSubscribeNO[] List of PUSubscribeNO objects
     */
    public function getPUSubscribeNosJoinPNEmail($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PUSubscribeNOQuery::create(null, $criteria);
        $query->joinWith('PNEmail', $join_behavior);

        return $this->getPUSubscribeNos($query, $con);
    }

    /**
     * Clears out the collPDocuments collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return PUser The current object (for fluent API support)
     * @see        addPDocuments()
     */
    public function clearPDocuments()
    {
        $this->collPDocuments = null; // important to set this to null since that means it is uninitialized
        $this->collPDocumentsPartial = null;

        return $this;
    }

    /**
     * reset is the collPDocuments collection loaded partially
     *
     * @return void
     */
    public function resetPartialPDocuments($v = true)
    {
        $this->collPDocumentsPartial = $v;
    }

    /**
     * Initializes the collPDocuments collection.
     *
     * By default this just sets the collPDocuments collection to an empty array (like clearcollPDocuments());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPDocuments($overrideExisting = true)
    {
        if (null !== $this->collPDocuments && !$overrideExisting) {
            return;
        }
        $this->collPDocuments = new PropelObjectCollection();
        $this->collPDocuments->setModel('PDocument');
    }

    /**
     * Gets an array of PDocument objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this PUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|PDocument[] List of PDocument objects
     * @throws PropelException
     */
    public function getPDocuments($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collPDocumentsPartial && !$this->isNew();
        if (null === $this->collPDocuments || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPDocuments) {
                // return empty collection
                $this->initPDocuments();
            } else {
                $collPDocuments = PDocumentQuery::create(null, $criteria)
                    ->filterByPUser($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collPDocumentsPartial && count($collPDocuments)) {
                      $this->initPDocuments(false);

                      foreach ($collPDocuments as $obj) {
                        if (false == $this->collPDocuments->contains($obj)) {
                          $this->collPDocuments->append($obj);
                        }
                      }

                      $this->collPDocumentsPartial = true;
                    }

                    $collPDocuments->getInternalIterator()->rewind();

                    return $collPDocuments;
                }

                if ($partial && $this->collPDocuments) {
                    foreach ($this->collPDocuments as $obj) {
                        if ($obj->isNew()) {
                            $collPDocuments[] = $obj;
                        }
                    }
                }

                $this->collPDocuments = $collPDocuments;
                $this->collPDocumentsPartial = false;
            }
        }

        return $this->collPDocuments;
    }

    /**
     * Sets a collection of PDocument objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $pDocuments A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return PUser The current object (for fluent API support)
     */
    public function setPDocuments(PropelCollection $pDocuments, PropelPDO $con = null)
    {
        $pDocumentsToDelete = $this->getPDocuments(new Criteria(), $con)->diff($pDocuments);


        $this->pDocumentsScheduledForDeletion = $pDocumentsToDelete;

        foreach ($pDocumentsToDelete as $pDocumentRemoved) {
            $pDocumentRemoved->setPUser(null);
        }

        $this->collPDocuments = null;
        foreach ($pDocuments as $pDocument) {
            $this->addPDocument($pDocument);
        }

        $this->collPDocuments = $pDocuments;
        $this->collPDocumentsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related PDocument objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related PDocument objects.
     * @throws PropelException
     */
    public function countPDocuments(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collPDocumentsPartial && !$this->isNew();
        if (null === $this->collPDocuments || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPDocuments) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPDocuments());
            }
            $query = PDocumentQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPUser($this)
                ->count($con);
        }

        return count($this->collPDocuments);
    }

    /**
     * Method called to associate a PDocument object to this object
     * through the PDocument foreign key attribute.
     *
     * @param    PDocument $l PDocument
     * @return PUser The current object (for fluent API support)
     */
    public function addPDocument(PDocument $l)
    {
        if ($this->collPDocuments === null) {
            $this->initPDocuments();
            $this->collPDocumentsPartial = true;
        }

        if (!in_array($l, $this->collPDocuments->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddPDocument($l);

            if ($this->pDocumentsScheduledForDeletion and $this->pDocumentsScheduledForDeletion->contains($l)) {
                $this->pDocumentsScheduledForDeletion->remove($this->pDocumentsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	PDocument $pDocument The pDocument object to add.
     */
    protected function doAddPDocument($pDocument)
    {
        $this->collPDocuments[]= $pDocument;
        $pDocument->setPUser($this);
    }

    /**
     * @param	PDocument $pDocument The pDocument object to remove.
     * @return PUser The current object (for fluent API support)
     */
    public function removePDocument($pDocument)
    {
        if ($this->getPDocuments()->contains($pDocument)) {
            $this->collPDocuments->remove($this->collPDocuments->search($pDocument));
            if (null === $this->pDocumentsScheduledForDeletion) {
                $this->pDocumentsScheduledForDeletion = clone $this->collPDocuments;
                $this->pDocumentsScheduledForDeletion->clear();
            }
            $this->pDocumentsScheduledForDeletion[]= $pDocument;
            $pDocument->setPUser(null);
        }

        return $this;
    }

    /**
     * Clears out the collPDComments collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return PUser The current object (for fluent API support)
     * @see        addPDComments()
     */
    public function clearPDComments()
    {
        $this->collPDComments = null; // important to set this to null since that means it is uninitialized
        $this->collPDCommentsPartial = null;

        return $this;
    }

    /**
     * reset is the collPDComments collection loaded partially
     *
     * @return void
     */
    public function resetPartialPDComments($v = true)
    {
        $this->collPDCommentsPartial = $v;
    }

    /**
     * Initializes the collPDComments collection.
     *
     * By default this just sets the collPDComments collection to an empty array (like clearcollPDComments());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPDComments($overrideExisting = true)
    {
        if (null !== $this->collPDComments && !$overrideExisting) {
            return;
        }
        $this->collPDComments = new PropelObjectCollection();
        $this->collPDComments->setModel('PDComment');
    }

    /**
     * Gets an array of PDComment objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this PUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|PDComment[] List of PDComment objects
     * @throws PropelException
     */
    public function getPDComments($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collPDCommentsPartial && !$this->isNew();
        if (null === $this->collPDComments || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPDComments) {
                // return empty collection
                $this->initPDComments();
            } else {
                $collPDComments = PDCommentQuery::create(null, $criteria)
                    ->filterByPUser($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collPDCommentsPartial && count($collPDComments)) {
                      $this->initPDComments(false);

                      foreach ($collPDComments as $obj) {
                        if (false == $this->collPDComments->contains($obj)) {
                          $this->collPDComments->append($obj);
                        }
                      }

                      $this->collPDCommentsPartial = true;
                    }

                    $collPDComments->getInternalIterator()->rewind();

                    return $collPDComments;
                }

                if ($partial && $this->collPDComments) {
                    foreach ($this->collPDComments as $obj) {
                        if ($obj->isNew()) {
                            $collPDComments[] = $obj;
                        }
                    }
                }

                $this->collPDComments = $collPDComments;
                $this->collPDCommentsPartial = false;
            }
        }

        return $this->collPDComments;
    }

    /**
     * Sets a collection of PDComment objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $pDComments A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return PUser The current object (for fluent API support)
     */
    public function setPDComments(PropelCollection $pDComments, PropelPDO $con = null)
    {
        $pDCommentsToDelete = $this->getPDComments(new Criteria(), $con)->diff($pDComments);


        $this->pDCommentsScheduledForDeletion = $pDCommentsToDelete;

        foreach ($pDCommentsToDelete as $pDCommentRemoved) {
            $pDCommentRemoved->setPUser(null);
        }

        $this->collPDComments = null;
        foreach ($pDComments as $pDComment) {
            $this->addPDComment($pDComment);
        }

        $this->collPDComments = $pDComments;
        $this->collPDCommentsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related PDComment objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related PDComment objects.
     * @throws PropelException
     */
    public function countPDComments(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collPDCommentsPartial && !$this->isNew();
        if (null === $this->collPDComments || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPDComments) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPDComments());
            }
            $query = PDCommentQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPUser($this)
                ->count($con);
        }

        return count($this->collPDComments);
    }

    /**
     * Method called to associate a PDComment object to this object
     * through the PDComment foreign key attribute.
     *
     * @param    PDComment $l PDComment
     * @return PUser The current object (for fluent API support)
     */
    public function addPDComment(PDComment $l)
    {
        if ($this->collPDComments === null) {
            $this->initPDComments();
            $this->collPDCommentsPartial = true;
        }

        if (!in_array($l, $this->collPDComments->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddPDComment($l);

            if ($this->pDCommentsScheduledForDeletion and $this->pDCommentsScheduledForDeletion->contains($l)) {
                $this->pDCommentsScheduledForDeletion->remove($this->pDCommentsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	PDComment $pDComment The pDComment object to add.
     */
    protected function doAddPDComment($pDComment)
    {
        $this->collPDComments[]= $pDComment;
        $pDComment->setPUser($this);
    }

    /**
     * @param	PDComment $pDComment The pDComment object to remove.
     * @return PUser The current object (for fluent API support)
     */
    public function removePDComment($pDComment)
    {
        if ($this->getPDComments()->contains($pDComment)) {
            $this->collPDComments->remove($this->collPDComments->search($pDComment));
            if (null === $this->pDCommentsScheduledForDeletion) {
                $this->pDCommentsScheduledForDeletion = clone $this->collPDComments;
                $this->pDCommentsScheduledForDeletion->clear();
            }
            $this->pDCommentsScheduledForDeletion[]= $pDComment;
            $pDComment->setPUser(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this PUser is new, it will return
     * an empty collection; or if this PUser has previously
     * been saved, it will retrieve related PDComments from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in PUser.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|PDComment[] List of PDComment objects
     */
    public function getPDCommentsJoinPDocument($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PDCommentQuery::create(null, $criteria);
        $query->joinWith('PDocument', $join_behavior);

        return $this->getPDComments($query, $con);
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

                      foreach ($collPUFollowUsRelatedByPUserId as $obj) {
                        if (false == $this->collPUFollowUsRelatedByPUserId->contains($obj)) {
                          $this->collPUFollowUsRelatedByPUserId->append($obj);
                        }
                      }

                      $this->collPUFollowUsRelatedByPUserIdPartial = true;
                    }

                    $collPUFollowUsRelatedByPUserId->getInternalIterator()->rewind();

                    return $collPUFollowUsRelatedByPUserId;
                }

                if ($partial && $this->collPUFollowUsRelatedByPUserId) {
                    foreach ($this->collPUFollowUsRelatedByPUserId as $obj) {
                        if ($obj->isNew()) {
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


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->pUFollowUsRelatedByPUserIdScheduledForDeletion = clone $pUFollowUsRelatedByPUserIdToDelete;

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

            if ($partial && !$criteria) {
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

            if ($this->pUFollowUsRelatedByPUserIdScheduledForDeletion and $this->pUFollowUsRelatedByPUserIdScheduledForDeletion->contains($l)) {
                $this->pUFollowUsRelatedByPUserIdScheduledForDeletion->remove($this->pUFollowUsRelatedByPUserIdScheduledForDeletion->search($l));
            }
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

                      foreach ($collPUFollowUsRelatedByPUserFollowerId as $obj) {
                        if (false == $this->collPUFollowUsRelatedByPUserFollowerId->contains($obj)) {
                          $this->collPUFollowUsRelatedByPUserFollowerId->append($obj);
                        }
                      }

                      $this->collPUFollowUsRelatedByPUserFollowerIdPartial = true;
                    }

                    $collPUFollowUsRelatedByPUserFollowerId->getInternalIterator()->rewind();

                    return $collPUFollowUsRelatedByPUserFollowerId;
                }

                if ($partial && $this->collPUFollowUsRelatedByPUserFollowerId) {
                    foreach ($this->collPUFollowUsRelatedByPUserFollowerId as $obj) {
                        if ($obj->isNew()) {
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


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->pUFollowUsRelatedByPUserFollowerIdScheduledForDeletion = clone $pUFollowUsRelatedByPUserFollowerIdToDelete;

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

            if ($partial && !$criteria) {
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

            if ($this->pUFollowUsRelatedByPUserFollowerIdScheduledForDeletion and $this->pUFollowUsRelatedByPUserFollowerIdScheduledForDeletion->contains($l)) {
                $this->pUFollowUsRelatedByPUserFollowerIdScheduledForDeletion->remove($this->pUFollowUsRelatedByPUserFollowerIdScheduledForDeletion->search($l));
            }
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

                      foreach ($collPDDebates as $obj) {
                        if (false == $this->collPDDebates->contains($obj)) {
                          $this->collPDDebates->append($obj);
                        }
                      }

                      $this->collPDDebatesPartial = true;
                    }

                    $collPDDebates->getInternalIterator()->rewind();

                    return $collPDDebates;
                }

                if ($partial && $this->collPDDebates) {
                    foreach ($this->collPDDebates as $obj) {
                        if ($obj->isNew()) {
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


        $this->pDDebatesScheduledForDeletion = $pDDebatesToDelete;

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

            if ($partial && !$criteria) {
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

            if ($this->pDDebatesScheduledForDeletion and $this->pDDebatesScheduledForDeletion->contains($l)) {
                $this->pDDebatesScheduledForDeletion->remove($this->pDDebatesScheduledForDeletion->search($l));
            }
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
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this PUser is new, it will return
     * an empty collection; or if this PUser has previously
     * been saved, it will retrieve related PDDebates from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in PUser.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|PDDebate[] List of PDDebate objects
     */
    public function getPDDebatesJoinPDocument($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PDDebateQuery::create(null, $criteria);
        $query->joinWith('PDocument', $join_behavior);

        return $this->getPDDebates($query, $con);
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
     * @return PUser The current object (for fluent API support)
     */
    public function setPDReactions(PropelCollection $pDReactions, PropelPDO $con = null)
    {
        $pDReactionsToDelete = $this->getPDReactions(new Criteria(), $con)->diff($pDReactions);


        $this->pDReactionsScheduledForDeletion = $pDReactionsToDelete;

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

            if ($partial && !$criteria) {
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
    public function getPDReactionsJoinPDocument($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PDReactionQuery::create(null, $criteria);
        $query->joinWith('PDocument', $join_behavior);

        return $this->getPDReactions($query, $con);
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
        $currentPuFollowDdPDDebates = $this->getPuFollowDdPDDebates(null, $con);

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
            $this->collPuFollowDdPDDebates[] = $pDDebate;

            if ($this->puFollowDdPDDebatesScheduledForDeletion and $this->puFollowDdPDDebatesScheduledForDeletion->contains($pDDebate)) {
                $this->puFollowDdPDDebatesScheduledForDeletion->remove($this->puFollowDdPDDebatesScheduledForDeletion->search($pDDebate));
            }
        }

        return $this;
    }

    /**
     * @param	PuFollowDdPDDebate $puFollowDdPDDebate The puFollowDdPDDebate object to add.
     */
    protected function doAddPuFollowDdPDDebate(PDDebate $puFollowDdPDDebate)
    {
        // set the back reference to this object directly as using provided method either results
        // in endless loop or in multiple relations
        if (!$puFollowDdPDDebate->getPuFollowDdPUsers()->contains($this)) { $pUFollowDD = new PUFollowDD();
            $pUFollowDD->setPuFollowDdPDDebate($puFollowDdPDDebate);
            $this->addPuFollowDdPUser($pUFollowDD);

            $foreignCollection = $puFollowDdPDDebate->getPuFollowDdPUsers();
            $foreignCollection[] = $this;
        }
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
     * Clears out the collPRBadges collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return PUser The current object (for fluent API support)
     * @see        addPRBadges()
     */
    public function clearPRBadges()
    {
        $this->collPRBadges = null; // important to set this to null since that means it is uninitialized
        $this->collPRBadgesPartial = null;

        return $this;
    }

    /**
     * Initializes the collPRBadges collection.
     *
     * By default this just sets the collPRBadges collection to an empty collection (like clearPRBadges());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @return void
     */
    public function initPRBadges()
    {
        $this->collPRBadges = new PropelObjectCollection();
        $this->collPRBadges->setModel('PRBadge');
    }

    /**
     * Gets a collection of PRBadge objects related by a many-to-many relationship
     * to the current object by way of the p_u_badges cross-reference table.
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
    public function getPRBadges($criteria = null, PropelPDO $con = null)
    {
        if (null === $this->collPRBadges || null !== $criteria) {
            if ($this->isNew() && null === $this->collPRBadges) {
                // return empty collection
                $this->initPRBadges();
            } else {
                $collPRBadges = PRBadgeQuery::create(null, $criteria)
                    ->filterByPUser($this)
                    ->find($con);
                if (null !== $criteria) {
                    return $collPRBadges;
                }
                $this->collPRBadges = $collPRBadges;
            }
        }

        return $this->collPRBadges;
    }

    /**
     * Sets a collection of PRBadge objects related by a many-to-many relationship
     * to the current object by way of the p_u_badges cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $pRBadges A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return PUser The current object (for fluent API support)
     */
    public function setPRBadges(PropelCollection $pRBadges, PropelPDO $con = null)
    {
        $this->clearPRBadges();
        $currentPRBadges = $this->getPRBadges(null, $con);

        $this->pRBadgesScheduledForDeletion = $currentPRBadges->diff($pRBadges);

        foreach ($pRBadges as $pRBadge) {
            if (!$currentPRBadges->contains($pRBadge)) {
                $this->doAddPRBadge($pRBadge);
            }
        }

        $this->collPRBadges = $pRBadges;

        return $this;
    }

    /**
     * Gets the number of PRBadge objects related by a many-to-many relationship
     * to the current object by way of the p_u_badges cross-reference table.
     *
     * @param Criteria $criteria Optional query object to filter the query
     * @param boolean $distinct Set to true to force count distinct
     * @param PropelPDO $con Optional connection object
     *
     * @return int the number of related PRBadge objects
     */
    public function countPRBadges($criteria = null, $distinct = false, PropelPDO $con = null)
    {
        if (null === $this->collPRBadges || null !== $criteria) {
            if ($this->isNew() && null === $this->collPRBadges) {
                return 0;
            } else {
                $query = PRBadgeQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByPUser($this)
                    ->count($con);
            }
        } else {
            return count($this->collPRBadges);
        }
    }

    /**
     * Associate a PRBadge object to this object
     * through the p_u_badges cross reference table.
     *
     * @param  PRBadge $pRBadge The PUBadges object to relate
     * @return PUser The current object (for fluent API support)
     */
    public function addPRBadge(PRBadge $pRBadge)
    {
        if ($this->collPRBadges === null) {
            $this->initPRBadges();
        }

        if (!$this->collPRBadges->contains($pRBadge)) { // only add it if the **same** object is not already associated
            $this->doAddPRBadge($pRBadge);
            $this->collPRBadges[] = $pRBadge;

            if ($this->pRBadgesScheduledForDeletion and $this->pRBadgesScheduledForDeletion->contains($pRBadge)) {
                $this->pRBadgesScheduledForDeletion->remove($this->pRBadgesScheduledForDeletion->search($pRBadge));
            }
        }

        return $this;
    }

    /**
     * @param	PRBadge $pRBadge The pRBadge object to add.
     */
    protected function doAddPRBadge(PRBadge $pRBadge)
    {
        // set the back reference to this object directly as using provided method either results
        // in endless loop or in multiple relations
        if (!$pRBadge->getPUsers()->contains($this)) { $pUBadges = new PUBadges();
            $pUBadges->setPRBadge($pRBadge);
            $this->addPUBadges($pUBadges);

            $foreignCollection = $pRBadge->getPUsers();
            $foreignCollection[] = $this;
        }
    }

    /**
     * Remove a PRBadge object to this object
     * through the p_u_badges cross reference table.
     *
     * @param PRBadge $pRBadge The PUBadges object to relate
     * @return PUser The current object (for fluent API support)
     */
    public function removePRBadge(PRBadge $pRBadge)
    {
        if ($this->getPRBadges()->contains($pRBadge)) {
            $this->collPRBadges->remove($this->collPRBadges->search($pRBadge));
            if (null === $this->pRBadgesScheduledForDeletion) {
                $this->pRBadgesScheduledForDeletion = clone $this->collPRBadges;
                $this->pRBadgesScheduledForDeletion->clear();
            }
            $this->pRBadgesScheduledForDeletion[]= $pRBadge;
        }

        return $this;
    }

    /**
     * Clears out the collPRActions collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return PUser The current object (for fluent API support)
     * @see        addPRActions()
     */
    public function clearPRActions()
    {
        $this->collPRActions = null; // important to set this to null since that means it is uninitialized
        $this->collPRActionsPartial = null;

        return $this;
    }

    /**
     * Initializes the collPRActions collection.
     *
     * By default this just sets the collPRActions collection to an empty collection (like clearPRActions());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @return void
     */
    public function initPRActions()
    {
        $this->collPRActions = new PropelObjectCollection();
        $this->collPRActions->setModel('PRAction');
    }

    /**
     * Gets a collection of PRAction objects related by a many-to-many relationship
     * to the current object by way of the p_u_reputation cross-reference table.
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
    public function getPRActions($criteria = null, PropelPDO $con = null)
    {
        if (null === $this->collPRActions || null !== $criteria) {
            if ($this->isNew() && null === $this->collPRActions) {
                // return empty collection
                $this->initPRActions();
            } else {
                $collPRActions = PRActionQuery::create(null, $criteria)
                    ->filterByPUser($this)
                    ->find($con);
                if (null !== $criteria) {
                    return $collPRActions;
                }
                $this->collPRActions = $collPRActions;
            }
        }

        return $this->collPRActions;
    }

    /**
     * Sets a collection of PRAction objects related by a many-to-many relationship
     * to the current object by way of the p_u_reputation cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $pRActions A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return PUser The current object (for fluent API support)
     */
    public function setPRActions(PropelCollection $pRActions, PropelPDO $con = null)
    {
        $this->clearPRActions();
        $currentPRActions = $this->getPRActions(null, $con);

        $this->pRActionsScheduledForDeletion = $currentPRActions->diff($pRActions);

        foreach ($pRActions as $pRAction) {
            if (!$currentPRActions->contains($pRAction)) {
                $this->doAddPRAction($pRAction);
            }
        }

        $this->collPRActions = $pRActions;

        return $this;
    }

    /**
     * Gets the number of PRAction objects related by a many-to-many relationship
     * to the current object by way of the p_u_reputation cross-reference table.
     *
     * @param Criteria $criteria Optional query object to filter the query
     * @param boolean $distinct Set to true to force count distinct
     * @param PropelPDO $con Optional connection object
     *
     * @return int the number of related PRAction objects
     */
    public function countPRActions($criteria = null, $distinct = false, PropelPDO $con = null)
    {
        if (null === $this->collPRActions || null !== $criteria) {
            if ($this->isNew() && null === $this->collPRActions) {
                return 0;
            } else {
                $query = PRActionQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByPUser($this)
                    ->count($con);
            }
        } else {
            return count($this->collPRActions);
        }
    }

    /**
     * Associate a PRAction object to this object
     * through the p_u_reputation cross reference table.
     *
     * @param  PRAction $pRAction The PUReputation object to relate
     * @return PUser The current object (for fluent API support)
     */
    public function addPRAction(PRAction $pRAction)
    {
        if ($this->collPRActions === null) {
            $this->initPRActions();
        }

        if (!$this->collPRActions->contains($pRAction)) { // only add it if the **same** object is not already associated
            $this->doAddPRAction($pRAction);
            $this->collPRActions[] = $pRAction;

            if ($this->pRActionsScheduledForDeletion and $this->pRActionsScheduledForDeletion->contains($pRAction)) {
                $this->pRActionsScheduledForDeletion->remove($this->pRActionsScheduledForDeletion->search($pRAction));
            }
        }

        return $this;
    }

    /**
     * @param	PRAction $pRAction The pRAction object to add.
     */
    protected function doAddPRAction(PRAction $pRAction)
    {
        // set the back reference to this object directly as using provided method either results
        // in endless loop or in multiple relations
        if (!$pRAction->getPUsers()->contains($this)) { $pUReputation = new PUReputation();
            $pUReputation->setPRAction($pRAction);
            $this->addPUReputation($pUReputation);

            $foreignCollection = $pRAction->getPUsers();
            $foreignCollection[] = $this;
        }
    }

    /**
     * Remove a PRAction object to this object
     * through the p_u_reputation cross reference table.
     *
     * @param PRAction $pRAction The PUReputation object to relate
     * @return PUser The current object (for fluent API support)
     */
    public function removePRAction(PRAction $pRAction)
    {
        if ($this->getPRActions()->contains($pRAction)) {
            $this->collPRActions->remove($this->collPRActions->search($pRAction));
            if (null === $this->pRActionsScheduledForDeletion) {
                $this->pRActionsScheduledForDeletion = clone $this->collPRActions;
                $this->pRActionsScheduledForDeletion->clear();
            }
            $this->pRActionsScheduledForDeletion[]= $pRAction;
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
        $currentPuTaggedTPTags = $this->getPuTaggedTPTags(null, $con);

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
            $this->collPuTaggedTPTags[] = $pTag;

            if ($this->puTaggedTPTagsScheduledForDeletion and $this->puTaggedTPTagsScheduledForDeletion->contains($pTag)) {
                $this->puTaggedTPTagsScheduledForDeletion->remove($this->puTaggedTPTagsScheduledForDeletion->search($pTag));
            }
        }

        return $this;
    }

    /**
     * @param	PuTaggedTPTag $puTaggedTPTag The puTaggedTPTag object to add.
     */
    protected function doAddPuTaggedTPTag(PTag $puTaggedTPTag)
    {
        // set the back reference to this object directly as using provided method either results
        // in endless loop or in multiple relations
        if (!$puTaggedTPTag->getPuTaggedTPUsers()->contains($this)) { $pUTaggedT = new PUTaggedT();
            $pUTaggedT->setPuTaggedTPTag($puTaggedTPTag);
            $this->addPuTaggedTPUser($pUTaggedT);

            $foreignCollection = $puTaggedTPTag->getPuTaggedTPUsers();
            $foreignCollection[] = $this;
        }
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
     * Clears out the collPuFollowTPTags collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return PUser The current object (for fluent API support)
     * @see        addPuFollowTPTags()
     */
    public function clearPuFollowTPTags()
    {
        $this->collPuFollowTPTags = null; // important to set this to null since that means it is uninitialized
        $this->collPuFollowTPTagsPartial = null;

        return $this;
    }

    /**
     * Initializes the collPuFollowTPTags collection.
     *
     * By default this just sets the collPuFollowTPTags collection to an empty collection (like clearPuFollowTPTags());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @return void
     */
    public function initPuFollowTPTags()
    {
        $this->collPuFollowTPTags = new PropelObjectCollection();
        $this->collPuFollowTPTags->setModel('PTag');
    }

    /**
     * Gets a collection of PTag objects related by a many-to-many relationship
     * to the current object by way of the p_u_follow_t cross-reference table.
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
    public function getPuFollowTPTags($criteria = null, PropelPDO $con = null)
    {
        if (null === $this->collPuFollowTPTags || null !== $criteria) {
            if ($this->isNew() && null === $this->collPuFollowTPTags) {
                // return empty collection
                $this->initPuFollowTPTags();
            } else {
                $collPuFollowTPTags = PTagQuery::create(null, $criteria)
                    ->filterByPuFollowTPUser($this)
                    ->find($con);
                if (null !== $criteria) {
                    return $collPuFollowTPTags;
                }
                $this->collPuFollowTPTags = $collPuFollowTPTags;
            }
        }

        return $this->collPuFollowTPTags;
    }

    /**
     * Sets a collection of PTag objects related by a many-to-many relationship
     * to the current object by way of the p_u_follow_t cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $puFollowTPTags A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return PUser The current object (for fluent API support)
     */
    public function setPuFollowTPTags(PropelCollection $puFollowTPTags, PropelPDO $con = null)
    {
        $this->clearPuFollowTPTags();
        $currentPuFollowTPTags = $this->getPuFollowTPTags(null, $con);

        $this->puFollowTPTagsScheduledForDeletion = $currentPuFollowTPTags->diff($puFollowTPTags);

        foreach ($puFollowTPTags as $puFollowTPTag) {
            if (!$currentPuFollowTPTags->contains($puFollowTPTag)) {
                $this->doAddPuFollowTPTag($puFollowTPTag);
            }
        }

        $this->collPuFollowTPTags = $puFollowTPTags;

        return $this;
    }

    /**
     * Gets the number of PTag objects related by a many-to-many relationship
     * to the current object by way of the p_u_follow_t cross-reference table.
     *
     * @param Criteria $criteria Optional query object to filter the query
     * @param boolean $distinct Set to true to force count distinct
     * @param PropelPDO $con Optional connection object
     *
     * @return int the number of related PTag objects
     */
    public function countPuFollowTPTags($criteria = null, $distinct = false, PropelPDO $con = null)
    {
        if (null === $this->collPuFollowTPTags || null !== $criteria) {
            if ($this->isNew() && null === $this->collPuFollowTPTags) {
                return 0;
            } else {
                $query = PTagQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByPuFollowTPUser($this)
                    ->count($con);
            }
        } else {
            return count($this->collPuFollowTPTags);
        }
    }

    /**
     * Associate a PTag object to this object
     * through the p_u_follow_t cross reference table.
     *
     * @param  PTag $pTag The PUFollowT object to relate
     * @return PUser The current object (for fluent API support)
     */
    public function addPuFollowTPTag(PTag $pTag)
    {
        if ($this->collPuFollowTPTags === null) {
            $this->initPuFollowTPTags();
        }

        if (!$this->collPuFollowTPTags->contains($pTag)) { // only add it if the **same** object is not already associated
            $this->doAddPuFollowTPTag($pTag);
            $this->collPuFollowTPTags[] = $pTag;

            if ($this->puFollowTPTagsScheduledForDeletion and $this->puFollowTPTagsScheduledForDeletion->contains($pTag)) {
                $this->puFollowTPTagsScheduledForDeletion->remove($this->puFollowTPTagsScheduledForDeletion->search($pTag));
            }
        }

        return $this;
    }

    /**
     * @param	PuFollowTPTag $puFollowTPTag The puFollowTPTag object to add.
     */
    protected function doAddPuFollowTPTag(PTag $puFollowTPTag)
    {
        // set the back reference to this object directly as using provided method either results
        // in endless loop or in multiple relations
        if (!$puFollowTPTag->getPuFollowTPUsers()->contains($this)) { $pUFollowT = new PUFollowT();
            $pUFollowT->setPuFollowTPTag($puFollowTPTag);
            $this->addPuFollowTPUser($pUFollowT);

            $foreignCollection = $puFollowTPTag->getPuFollowTPUsers();
            $foreignCollection[] = $this;
        }
    }

    /**
     * Remove a PTag object to this object
     * through the p_u_follow_t cross reference table.
     *
     * @param PTag $pTag The PUFollowT object to relate
     * @return PUser The current object (for fluent API support)
     */
    public function removePuFollowTPTag(PTag $pTag)
    {
        if ($this->getPuFollowTPTags()->contains($pTag)) {
            $this->collPuFollowTPTags->remove($this->collPuFollowTPTags->search($pTag));
            if (null === $this->puFollowTPTagsScheduledForDeletion) {
                $this->puFollowTPTagsScheduledForDeletion = clone $this->collPuFollowTPTags;
                $this->puFollowTPTagsScheduledForDeletion->clear();
            }
            $this->puFollowTPTagsScheduledForDeletion[]= $pTag;
        }

        return $this;
    }

    /**
     * Clears out the collPQualifications collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return PUser The current object (for fluent API support)
     * @see        addPQualifications()
     */
    public function clearPQualifications()
    {
        $this->collPQualifications = null; // important to set this to null since that means it is uninitialized
        $this->collPQualificationsPartial = null;

        return $this;
    }

    /**
     * Initializes the collPQualifications collection.
     *
     * By default this just sets the collPQualifications collection to an empty collection (like clearPQualifications());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @return void
     */
    public function initPQualifications()
    {
        $this->collPQualifications = new PropelObjectCollection();
        $this->collPQualifications->setModel('PQualification');
    }

    /**
     * Gets a collection of PQualification objects related by a many-to-many relationship
     * to the current object by way of the p_u_role_q cross-reference table.
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
     * @return PropelObjectCollection|PQualification[] List of PQualification objects
     */
    public function getPQualifications($criteria = null, PropelPDO $con = null)
    {
        if (null === $this->collPQualifications || null !== $criteria) {
            if ($this->isNew() && null === $this->collPQualifications) {
                // return empty collection
                $this->initPQualifications();
            } else {
                $collPQualifications = PQualificationQuery::create(null, $criteria)
                    ->filterByPUser($this)
                    ->find($con);
                if (null !== $criteria) {
                    return $collPQualifications;
                }
                $this->collPQualifications = $collPQualifications;
            }
        }

        return $this->collPQualifications;
    }

    /**
     * Sets a collection of PQualification objects related by a many-to-many relationship
     * to the current object by way of the p_u_role_q cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $pQualifications A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return PUser The current object (for fluent API support)
     */
    public function setPQualifications(PropelCollection $pQualifications, PropelPDO $con = null)
    {
        $this->clearPQualifications();
        $currentPQualifications = $this->getPQualifications(null, $con);

        $this->pQualificationsScheduledForDeletion = $currentPQualifications->diff($pQualifications);

        foreach ($pQualifications as $pQualification) {
            if (!$currentPQualifications->contains($pQualification)) {
                $this->doAddPQualification($pQualification);
            }
        }

        $this->collPQualifications = $pQualifications;

        return $this;
    }

    /**
     * Gets the number of PQualification objects related by a many-to-many relationship
     * to the current object by way of the p_u_role_q cross-reference table.
     *
     * @param Criteria $criteria Optional query object to filter the query
     * @param boolean $distinct Set to true to force count distinct
     * @param PropelPDO $con Optional connection object
     *
     * @return int the number of related PQualification objects
     */
    public function countPQualifications($criteria = null, $distinct = false, PropelPDO $con = null)
    {
        if (null === $this->collPQualifications || null !== $criteria) {
            if ($this->isNew() && null === $this->collPQualifications) {
                return 0;
            } else {
                $query = PQualificationQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByPUser($this)
                    ->count($con);
            }
        } else {
            return count($this->collPQualifications);
        }
    }

    /**
     * Associate a PQualification object to this object
     * through the p_u_role_q cross reference table.
     *
     * @param  PQualification $pQualification The PURoleQ object to relate
     * @return PUser The current object (for fluent API support)
     */
    public function addPQualification(PQualification $pQualification)
    {
        if ($this->collPQualifications === null) {
            $this->initPQualifications();
        }

        if (!$this->collPQualifications->contains($pQualification)) { // only add it if the **same** object is not already associated
            $this->doAddPQualification($pQualification);
            $this->collPQualifications[] = $pQualification;

            if ($this->pQualificationsScheduledForDeletion and $this->pQualificationsScheduledForDeletion->contains($pQualification)) {
                $this->pQualificationsScheduledForDeletion->remove($this->pQualificationsScheduledForDeletion->search($pQualification));
            }
        }

        return $this;
    }

    /**
     * @param	PQualification $pQualification The pQualification object to add.
     */
    protected function doAddPQualification(PQualification $pQualification)
    {
        // set the back reference to this object directly as using provided method either results
        // in endless loop or in multiple relations
        if (!$pQualification->getPUsers()->contains($this)) { $pURoleQ = new PURoleQ();
            $pURoleQ->setPQualification($pQualification);
            $this->addPURoleQ($pURoleQ);

            $foreignCollection = $pQualification->getPUsers();
            $foreignCollection[] = $this;
        }
    }

    /**
     * Remove a PQualification object to this object
     * through the p_u_role_q cross reference table.
     *
     * @param PQualification $pQualification The PURoleQ object to relate
     * @return PUser The current object (for fluent API support)
     */
    public function removePQualification(PQualification $pQualification)
    {
        if ($this->getPQualifications()->contains($pQualification)) {
            $this->collPQualifications->remove($this->collPQualifications->search($pQualification));
            if (null === $this->pQualificationsScheduledForDeletion) {
                $this->pQualificationsScheduledForDeletion = clone $this->collPQualifications;
                $this->pQualificationsScheduledForDeletion->clear();
            }
            $this->pQualificationsScheduledForDeletion[]= $pQualification;
        }

        return $this;
    }

    /**
     * Clears out the collPQOrganizations collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return PUser The current object (for fluent API support)
     * @see        addPQOrganizations()
     */
    public function clearPQOrganizations()
    {
        $this->collPQOrganizations = null; // important to set this to null since that means it is uninitialized
        $this->collPQOrganizationsPartial = null;

        return $this;
    }

    /**
     * Initializes the collPQOrganizations collection.
     *
     * By default this just sets the collPQOrganizations collection to an empty collection (like clearPQOrganizations());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @return void
     */
    public function initPQOrganizations()
    {
        $this->collPQOrganizations = new PropelObjectCollection();
        $this->collPQOrganizations->setModel('PQOrganization');
    }

    /**
     * Gets a collection of PQOrganization objects related by a many-to-many relationship
     * to the current object by way of the p_u_affinity_q_o cross-reference table.
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
     * @return PropelObjectCollection|PQOrganization[] List of PQOrganization objects
     */
    public function getPQOrganizations($criteria = null, PropelPDO $con = null)
    {
        if (null === $this->collPQOrganizations || null !== $criteria) {
            if ($this->isNew() && null === $this->collPQOrganizations) {
                // return empty collection
                $this->initPQOrganizations();
            } else {
                $collPQOrganizations = PQOrganizationQuery::create(null, $criteria)
                    ->filterByPUser($this)
                    ->find($con);
                if (null !== $criteria) {
                    return $collPQOrganizations;
                }
                $this->collPQOrganizations = $collPQOrganizations;
            }
        }

        return $this->collPQOrganizations;
    }

    /**
     * Sets a collection of PQOrganization objects related by a many-to-many relationship
     * to the current object by way of the p_u_affinity_q_o cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $pQOrganizations A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return PUser The current object (for fluent API support)
     */
    public function setPQOrganizations(PropelCollection $pQOrganizations, PropelPDO $con = null)
    {
        $this->clearPQOrganizations();
        $currentPQOrganizations = $this->getPQOrganizations(null, $con);

        $this->pQOrganizationsScheduledForDeletion = $currentPQOrganizations->diff($pQOrganizations);

        foreach ($pQOrganizations as $pQOrganization) {
            if (!$currentPQOrganizations->contains($pQOrganization)) {
                $this->doAddPQOrganization($pQOrganization);
            }
        }

        $this->collPQOrganizations = $pQOrganizations;

        return $this;
    }

    /**
     * Gets the number of PQOrganization objects related by a many-to-many relationship
     * to the current object by way of the p_u_affinity_q_o cross-reference table.
     *
     * @param Criteria $criteria Optional query object to filter the query
     * @param boolean $distinct Set to true to force count distinct
     * @param PropelPDO $con Optional connection object
     *
     * @return int the number of related PQOrganization objects
     */
    public function countPQOrganizations($criteria = null, $distinct = false, PropelPDO $con = null)
    {
        if (null === $this->collPQOrganizations || null !== $criteria) {
            if ($this->isNew() && null === $this->collPQOrganizations) {
                return 0;
            } else {
                $query = PQOrganizationQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByPUser($this)
                    ->count($con);
            }
        } else {
            return count($this->collPQOrganizations);
        }
    }

    /**
     * Associate a PQOrganization object to this object
     * through the p_u_affinity_q_o cross reference table.
     *
     * @param  PQOrganization $pQOrganization The PUAffinityQO object to relate
     * @return PUser The current object (for fluent API support)
     */
    public function addPQOrganization(PQOrganization $pQOrganization)
    {
        if ($this->collPQOrganizations === null) {
            $this->initPQOrganizations();
        }

        if (!$this->collPQOrganizations->contains($pQOrganization)) { // only add it if the **same** object is not already associated
            $this->doAddPQOrganization($pQOrganization);
            $this->collPQOrganizations[] = $pQOrganization;

            if ($this->pQOrganizationsScheduledForDeletion and $this->pQOrganizationsScheduledForDeletion->contains($pQOrganization)) {
                $this->pQOrganizationsScheduledForDeletion->remove($this->pQOrganizationsScheduledForDeletion->search($pQOrganization));
            }
        }

        return $this;
    }

    /**
     * @param	PQOrganization $pQOrganization The pQOrganization object to add.
     */
    protected function doAddPQOrganization(PQOrganization $pQOrganization)
    {
        // set the back reference to this object directly as using provided method either results
        // in endless loop or in multiple relations
        if (!$pQOrganization->getPUsers()->contains($this)) { $pUAffinityQO = new PUAffinityQO();
            $pUAffinityQO->setPQOrganization($pQOrganization);
            $this->addPUAffinityQO($pUAffinityQO);

            $foreignCollection = $pQOrganization->getPUsers();
            $foreignCollection[] = $this;
        }
    }

    /**
     * Remove a PQOrganization object to this object
     * through the p_u_affinity_q_o cross reference table.
     *
     * @param PQOrganization $pQOrganization The PUAffinityQO object to relate
     * @return PUser The current object (for fluent API support)
     */
    public function removePQOrganization(PQOrganization $pQOrganization)
    {
        if ($this->getPQOrganizations()->contains($pQOrganization)) {
            $this->collPQOrganizations->remove($this->collPQOrganizations->search($pQOrganization));
            if (null === $this->pQOrganizationsScheduledForDeletion) {
                $this->pQOrganizationsScheduledForDeletion = clone $this->collPQOrganizations;
                $this->pQOrganizationsScheduledForDeletion->clear();
            }
            $this->pQOrganizationsScheduledForDeletion[]= $pQOrganization;
        }

        return $this;
    }

    /**
     * Clears out the collPNotifications collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return PUser The current object (for fluent API support)
     * @see        addPNotifications()
     */
    public function clearPNotifications()
    {
        $this->collPNotifications = null; // important to set this to null since that means it is uninitialized
        $this->collPNotificationsPartial = null;

        return $this;
    }

    /**
     * Initializes the collPNotifications collection.
     *
     * By default this just sets the collPNotifications collection to an empty collection (like clearPNotifications());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @return void
     */
    public function initPNotifications()
    {
        $this->collPNotifications = new PropelObjectCollection();
        $this->collPNotifications->setModel('PNotification');
    }

    /**
     * Gets a collection of PNotification objects related by a many-to-many relationship
     * to the current object by way of the p_u_notified_p_n cross-reference table.
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
     * @return PropelObjectCollection|PNotification[] List of PNotification objects
     */
    public function getPNotifications($criteria = null, PropelPDO $con = null)
    {
        if (null === $this->collPNotifications || null !== $criteria) {
            if ($this->isNew() && null === $this->collPNotifications) {
                // return empty collection
                $this->initPNotifications();
            } else {
                $collPNotifications = PNotificationQuery::create(null, $criteria)
                    ->filterByPUser($this)
                    ->find($con);
                if (null !== $criteria) {
                    return $collPNotifications;
                }
                $this->collPNotifications = $collPNotifications;
            }
        }

        return $this->collPNotifications;
    }

    /**
     * Sets a collection of PNotification objects related by a many-to-many relationship
     * to the current object by way of the p_u_notified_p_n cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $pNotifications A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return PUser The current object (for fluent API support)
     */
    public function setPNotifications(PropelCollection $pNotifications, PropelPDO $con = null)
    {
        $this->clearPNotifications();
        $currentPNotifications = $this->getPNotifications(null, $con);

        $this->pNotificationsScheduledForDeletion = $currentPNotifications->diff($pNotifications);

        foreach ($pNotifications as $pNotification) {
            if (!$currentPNotifications->contains($pNotification)) {
                $this->doAddPNotification($pNotification);
            }
        }

        $this->collPNotifications = $pNotifications;

        return $this;
    }

    /**
     * Gets the number of PNotification objects related by a many-to-many relationship
     * to the current object by way of the p_u_notified_p_n cross-reference table.
     *
     * @param Criteria $criteria Optional query object to filter the query
     * @param boolean $distinct Set to true to force count distinct
     * @param PropelPDO $con Optional connection object
     *
     * @return int the number of related PNotification objects
     */
    public function countPNotifications($criteria = null, $distinct = false, PropelPDO $con = null)
    {
        if (null === $this->collPNotifications || null !== $criteria) {
            if ($this->isNew() && null === $this->collPNotifications) {
                return 0;
            } else {
                $query = PNotificationQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByPUser($this)
                    ->count($con);
            }
        } else {
            return count($this->collPNotifications);
        }
    }

    /**
     * Associate a PNotification object to this object
     * through the p_u_notified_p_n cross reference table.
     *
     * @param  PNotification $pNotification The PUNotifiedPN object to relate
     * @return PUser The current object (for fluent API support)
     */
    public function addPNotification(PNotification $pNotification)
    {
        if ($this->collPNotifications === null) {
            $this->initPNotifications();
        }

        if (!$this->collPNotifications->contains($pNotification)) { // only add it if the **same** object is not already associated
            $this->doAddPNotification($pNotification);
            $this->collPNotifications[] = $pNotification;

            if ($this->pNotificationsScheduledForDeletion and $this->pNotificationsScheduledForDeletion->contains($pNotification)) {
                $this->pNotificationsScheduledForDeletion->remove($this->pNotificationsScheduledForDeletion->search($pNotification));
            }
        }

        return $this;
    }

    /**
     * @param	PNotification $pNotification The pNotification object to add.
     */
    protected function doAddPNotification(PNotification $pNotification)
    {
        // set the back reference to this object directly as using provided method either results
        // in endless loop or in multiple relations
        if (!$pNotification->getPUsers()->contains($this)) { $pUNotifiedPN = new PUNotifiedPN();
            $pUNotifiedPN->setPNotification($pNotification);
            $this->addPUNotifiedPN($pUNotifiedPN);

            $foreignCollection = $pNotification->getPUsers();
            $foreignCollection[] = $this;
        }
    }

    /**
     * Remove a PNotification object to this object
     * through the p_u_notified_p_n cross reference table.
     *
     * @param PNotification $pNotification The PUNotifiedPN object to relate
     * @return PUser The current object (for fluent API support)
     */
    public function removePNotification(PNotification $pNotification)
    {
        if ($this->getPNotifications()->contains($pNotification)) {
            $this->collPNotifications->remove($this->collPNotifications->search($pNotification));
            if (null === $this->pNotificationsScheduledForDeletion) {
                $this->pNotificationsScheduledForDeletion = clone $this->collPNotifications;
                $this->pNotificationsScheduledForDeletion->clear();
            }
            $this->pNotificationsScheduledForDeletion[]= $pNotification;
        }

        return $this;
    }

    /**
     * Clears out the collPNEmails collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return PUser The current object (for fluent API support)
     * @see        addPNEmails()
     */
    public function clearPNEmails()
    {
        $this->collPNEmails = null; // important to set this to null since that means it is uninitialized
        $this->collPNEmailsPartial = null;

        return $this;
    }

    /**
     * Initializes the collPNEmails collection.
     *
     * By default this just sets the collPNEmails collection to an empty collection (like clearPNEmails());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @return void
     */
    public function initPNEmails()
    {
        $this->collPNEmails = new PropelObjectCollection();
        $this->collPNEmails->setModel('PNEmail');
    }

    /**
     * Gets a collection of PNEmail objects related by a many-to-many relationship
     * to the current object by way of the p_u_subscribe_n_o cross-reference table.
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
     * @return PropelObjectCollection|PNEmail[] List of PNEmail objects
     */
    public function getPNEmails($criteria = null, PropelPDO $con = null)
    {
        if (null === $this->collPNEmails || null !== $criteria) {
            if ($this->isNew() && null === $this->collPNEmails) {
                // return empty collection
                $this->initPNEmails();
            } else {
                $collPNEmails = PNEmailQuery::create(null, $criteria)
                    ->filterByPUser($this)
                    ->find($con);
                if (null !== $criteria) {
                    return $collPNEmails;
                }
                $this->collPNEmails = $collPNEmails;
            }
        }

        return $this->collPNEmails;
    }

    /**
     * Sets a collection of PNEmail objects related by a many-to-many relationship
     * to the current object by way of the p_u_subscribe_n_o cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $pNEmails A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return PUser The current object (for fluent API support)
     */
    public function setPNEmails(PropelCollection $pNEmails, PropelPDO $con = null)
    {
        $this->clearPNEmails();
        $currentPNEmails = $this->getPNEmails(null, $con);

        $this->pNEmailsScheduledForDeletion = $currentPNEmails->diff($pNEmails);

        foreach ($pNEmails as $pNEmail) {
            if (!$currentPNEmails->contains($pNEmail)) {
                $this->doAddPNEmail($pNEmail);
            }
        }

        $this->collPNEmails = $pNEmails;

        return $this;
    }

    /**
     * Gets the number of PNEmail objects related by a many-to-many relationship
     * to the current object by way of the p_u_subscribe_n_o cross-reference table.
     *
     * @param Criteria $criteria Optional query object to filter the query
     * @param boolean $distinct Set to true to force count distinct
     * @param PropelPDO $con Optional connection object
     *
     * @return int the number of related PNEmail objects
     */
    public function countPNEmails($criteria = null, $distinct = false, PropelPDO $con = null)
    {
        if (null === $this->collPNEmails || null !== $criteria) {
            if ($this->isNew() && null === $this->collPNEmails) {
                return 0;
            } else {
                $query = PNEmailQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByPUser($this)
                    ->count($con);
            }
        } else {
            return count($this->collPNEmails);
        }
    }

    /**
     * Associate a PNEmail object to this object
     * through the p_u_subscribe_n_o cross reference table.
     *
     * @param  PNEmail $pNEmail The PUSubscribeNO object to relate
     * @return PUser The current object (for fluent API support)
     */
    public function addPNEmail(PNEmail $pNEmail)
    {
        if ($this->collPNEmails === null) {
            $this->initPNEmails();
        }

        if (!$this->collPNEmails->contains($pNEmail)) { // only add it if the **same** object is not already associated
            $this->doAddPNEmail($pNEmail);
            $this->collPNEmails[] = $pNEmail;

            if ($this->pNEmailsScheduledForDeletion and $this->pNEmailsScheduledForDeletion->contains($pNEmail)) {
                $this->pNEmailsScheduledForDeletion->remove($this->pNEmailsScheduledForDeletion->search($pNEmail));
            }
        }

        return $this;
    }

    /**
     * @param	PNEmail $pNEmail The pNEmail object to add.
     */
    protected function doAddPNEmail(PNEmail $pNEmail)
    {
        // set the back reference to this object directly as using provided method either results
        // in endless loop or in multiple relations
        if (!$pNEmail->getPUsers()->contains($this)) { $pUSubscribeNO = new PUSubscribeNO();
            $pUSubscribeNO->setPNEmail($pNEmail);
            $this->addPUSubscribeNO($pUSubscribeNO);

            $foreignCollection = $pNEmail->getPUsers();
            $foreignCollection[] = $this;
        }
    }

    /**
     * Remove a PNEmail object to this object
     * through the p_u_subscribe_n_o cross reference table.
     *
     * @param PNEmail $pNEmail The PUSubscribeNO object to relate
     * @return PUser The current object (for fluent API support)
     */
    public function removePNEmail(PNEmail $pNEmail)
    {
        if ($this->getPNEmails()->contains($pNEmail)) {
            $this->collPNEmails->remove($this->collPNEmails->search($pNEmail));
            if (null === $this->pNEmailsScheduledForDeletion) {
                $this->pNEmailsScheduledForDeletion = clone $this->collPNEmails;
                $this->pNEmailsScheduledForDeletion->clear();
            }
            $this->pNEmailsScheduledForDeletion[]= $pNEmail;
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
        $this->p_u_status_id = null;
        $this->file_name = null;
        $this->gender = null;
        $this->firstname = null;
        $this->name = null;
        $this->birthday = null;
        $this->biography = null;
        $this->website = null;
        $this->twitter = null;
        $this->facebook = null;
        $this->phone = null;
        $this->newsletter = null;
        $this->last_connect = null;
        $this->nb_connected_days = null;
        $this->nb_views = null;
        $this->qualified = null;
        $this->validated = null;
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
     * when using Propel in certain daemon or large-volume/high-memory operations.
     *
     * @param boolean $deep Whether to also clear the references on all referrer objects.
     */
    public function clearAllReferences($deep = false)
    {
        if ($deep && !$this->alreadyInClearAllReferencesDeep) {
            $this->alreadyInClearAllReferencesDeep = true;
            if ($this->collPTags) {
                foreach ($this->collPTags as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPOrders) {
                foreach ($this->collPOrders as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPuFollowDdPUsers) {
                foreach ($this->collPuFollowDdPUsers as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPUBadgess) {
                foreach ($this->collPUBadgess as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPUReputations) {
                foreach ($this->collPUReputations as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPuTaggedTPUsers) {
                foreach ($this->collPuTaggedTPUsers as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPuFollowTPUsers) {
                foreach ($this->collPuFollowTPUsers as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPURoleQs) {
                foreach ($this->collPURoleQs as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPUMandates) {
                foreach ($this->collPUMandates as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPUAffinityQos) {
                foreach ($this->collPUAffinityQos as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPUNotifiedPNs) {
                foreach ($this->collPUNotifiedPNs as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPUSubscribeNos) {
                foreach ($this->collPUSubscribeNos as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPDocuments) {
                foreach ($this->collPDocuments as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPDComments) {
                foreach ($this->collPDComments as $o) {
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
            if ($this->collPDDebates) {
                foreach ($this->collPDDebates as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPDReactions) {
                foreach ($this->collPDReactions as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPuFollowDdPDDebates) {
                foreach ($this->collPuFollowDdPDDebates as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPRBadges) {
                foreach ($this->collPRBadges as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPRActions) {
                foreach ($this->collPRActions as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPuTaggedTPTags) {
                foreach ($this->collPuTaggedTPTags as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPuFollowTPTags) {
                foreach ($this->collPuFollowTPTags as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPQualifications) {
                foreach ($this->collPQualifications as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPQOrganizations) {
                foreach ($this->collPQOrganizations as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPNotifications) {
                foreach ($this->collPNotifications as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPNEmails) {
                foreach ($this->collPNEmails as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->aPUStatus instanceof Persistent) {
              $this->aPUStatus->clearAllReferences($deep);
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

        if ($this->collPTags instanceof PropelCollection) {
            $this->collPTags->clearIterator();
        }
        $this->collPTags = null;
        if ($this->collPOrders instanceof PropelCollection) {
            $this->collPOrders->clearIterator();
        }
        $this->collPOrders = null;
        if ($this->collPuFollowDdPUsers instanceof PropelCollection) {
            $this->collPuFollowDdPUsers->clearIterator();
        }
        $this->collPuFollowDdPUsers = null;
        if ($this->collPUBadgess instanceof PropelCollection) {
            $this->collPUBadgess->clearIterator();
        }
        $this->collPUBadgess = null;
        if ($this->collPUReputations instanceof PropelCollection) {
            $this->collPUReputations->clearIterator();
        }
        $this->collPUReputations = null;
        if ($this->collPuTaggedTPUsers instanceof PropelCollection) {
            $this->collPuTaggedTPUsers->clearIterator();
        }
        $this->collPuTaggedTPUsers = null;
        if ($this->collPuFollowTPUsers instanceof PropelCollection) {
            $this->collPuFollowTPUsers->clearIterator();
        }
        $this->collPuFollowTPUsers = null;
        if ($this->collPURoleQs instanceof PropelCollection) {
            $this->collPURoleQs->clearIterator();
        }
        $this->collPURoleQs = null;
        if ($this->collPUMandates instanceof PropelCollection) {
            $this->collPUMandates->clearIterator();
        }
        $this->collPUMandates = null;
        if ($this->collPUAffinityQos instanceof PropelCollection) {
            $this->collPUAffinityQos->clearIterator();
        }
        $this->collPUAffinityQos = null;
        if ($this->collPUNotifiedPNs instanceof PropelCollection) {
            $this->collPUNotifiedPNs->clearIterator();
        }
        $this->collPUNotifiedPNs = null;
        if ($this->collPUSubscribeNos instanceof PropelCollection) {
            $this->collPUSubscribeNos->clearIterator();
        }
        $this->collPUSubscribeNos = null;
        if ($this->collPDocuments instanceof PropelCollection) {
            $this->collPDocuments->clearIterator();
        }
        $this->collPDocuments = null;
        if ($this->collPDComments instanceof PropelCollection) {
            $this->collPDComments->clearIterator();
        }
        $this->collPDComments = null;
        if ($this->collPUFollowUsRelatedByPUserId instanceof PropelCollection) {
            $this->collPUFollowUsRelatedByPUserId->clearIterator();
        }
        $this->collPUFollowUsRelatedByPUserId = null;
        if ($this->collPUFollowUsRelatedByPUserFollowerId instanceof PropelCollection) {
            $this->collPUFollowUsRelatedByPUserFollowerId->clearIterator();
        }
        $this->collPUFollowUsRelatedByPUserFollowerId = null;
        if ($this->collPDDebates instanceof PropelCollection) {
            $this->collPDDebates->clearIterator();
        }
        $this->collPDDebates = null;
        if ($this->collPDReactions instanceof PropelCollection) {
            $this->collPDReactions->clearIterator();
        }
        $this->collPDReactions = null;
        if ($this->collPuFollowDdPDDebates instanceof PropelCollection) {
            $this->collPuFollowDdPDDebates->clearIterator();
        }
        $this->collPuFollowDdPDDebates = null;
        if ($this->collPRBadges instanceof PropelCollection) {
            $this->collPRBadges->clearIterator();
        }
        $this->collPRBadges = null;
        if ($this->collPRActions instanceof PropelCollection) {
            $this->collPRActions->clearIterator();
        }
        $this->collPRActions = null;
        if ($this->collPuTaggedTPTags instanceof PropelCollection) {
            $this->collPuTaggedTPTags->clearIterator();
        }
        $this->collPuTaggedTPTags = null;
        if ($this->collPuFollowTPTags instanceof PropelCollection) {
            $this->collPuFollowTPTags->clearIterator();
        }
        $this->collPuFollowTPTags = null;
        if ($this->collPQualifications instanceof PropelCollection) {
            $this->collPQualifications->clearIterator();
        }
        $this->collPQualifications = null;
        if ($this->collPQOrganizations instanceof PropelCollection) {
            $this->collPQOrganizations->clearIterator();
        }
        $this->collPQOrganizations = null;
        if ($this->collPNotifications instanceof PropelCollection) {
            $this->collPNotifications->clearIterator();
        }
        $this->collPNotifications = null;
        if ($this->collPNEmails instanceof PropelCollection) {
            $this->collPNEmails->clearIterator();
        }
        $this->collPNEmails = null;
        $this->aPUStatus = null;
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
        return $this->cleanupSlugPart($this->__toString());
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

            $count = PUserQuery::create()
                ->filterBySlug($this->getSlug())
                ->filterByPrimaryKey($this->getPrimaryKey())
            ->count();

            if (1 == $count) {
                return $this->getSlug();
            }
        }

         $query = PUserQuery::create('q')
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

    // archivable behavior

    /**
     * Get an archived version of the current object.
     *
     * @param PropelPDO $con Optional connection object
     *
     * @return     PUserArchive An archive object, or null if the current object was never archived
     */
    public function getArchive(PropelPDO $con = null)
    {
        if ($this->isNew()) {
            return null;
        }
        $archive = PUserArchiveQuery::create()
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
     * @return     PUserArchive The archive object based on this object
     */
    public function archive(PropelPDO $con = null)
    {
        if ($this->isNew()) {
            throw new PropelException('New objects cannot be archived. You must save the current object before calling archive().');
        }
        if (!$archive = $this->getArchive($con)) {
            $archive = new PUserArchive();
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
     * @return PUser The current object (for fluent API support)
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
     * @param      PUserArchive $archive An archived object based on the same class
      * @param      Boolean $populateAutoIncrementPrimaryKeys
     *               If true, autoincrement columns are copied from the archive object.
     *               If false, autoincrement columns are left intact.
      *
     * @return     PUser The current object (for fluent API support)
     */
    public function populateFromArchive($archive, $populateAutoIncrementPrimaryKeys = false) {
        if ($populateAutoIncrementPrimaryKeys) {
            $this->setId($archive->getId());
        }
        $this->setProvider($archive->getProvider());
        $this->setProviderId($archive->getProviderId());
        $this->setNickname($archive->getNickname());
        $this->setRealname($archive->getRealname());
        $this->setUsername($archive->getUsername());
        $this->setUsernameCanonical($archive->getUsernameCanonical());
        $this->setEmail($archive->getEmail());
        $this->setEmailCanonical($archive->getEmailCanonical());
        $this->setEnabled($archive->getEnabled());
        $this->setSalt($archive->getSalt());
        $this->setPassword($archive->getPassword());
        $this->setLastLogin($archive->getLastLogin());
        $this->setLocked($archive->getLocked());
        $this->setExpired($archive->getExpired());
        $this->setExpiresAt($archive->getExpiresAt());
        $this->setConfirmationToken($archive->getConfirmationToken());
        $this->setPasswordRequestedAt($archive->getPasswordRequestedAt());
        $this->setCredentialsExpired($archive->getCredentialsExpired());
        $this->setCredentialsExpireAt($archive->getCredentialsExpireAt());
        $this->setRoles($archive->getRoles());
        $this->setPUStatusId($archive->getPUStatusId());
        $this->setFileName($archive->getFileName());
        $this->setGender($archive->getGender());
        $this->setFirstname($archive->getFirstname());
        $this->setName($archive->getName());
        $this->setBirthday($archive->getBirthday());
        $this->setBiography($archive->getBiography());
        $this->setWebsite($archive->getWebsite());
        $this->setTwitter($archive->getTwitter());
        $this->setFacebook($archive->getFacebook());
        $this->setPhone($archive->getPhone());
        $this->setNewsletter($archive->getNewsletter());
        $this->setLastConnect($archive->getLastConnect());
        $this->setNbConnectedDays($archive->getNbConnectedDays());
        $this->setNbViews($archive->getNbViews());
        $this->setQualified($archive->getQualified());
        $this->setValidated($archive->getValidated());
        $this->setOnline($archive->getOnline());
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
     * @return     PUser The current object (for fluent API support)
     */
    public function deleteWithoutArchive(PropelPDO $con = null)
    {
        $this->archiveOnDelete = false;

        return $this->delete($con);
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
