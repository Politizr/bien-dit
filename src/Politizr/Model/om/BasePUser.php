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
use Politizr\Model\PEOperation;
use Politizr\Model\PEOperationQuery;
use Politizr\Model\PLCity;
use Politizr\Model\PLCityQuery;
use Politizr\Model\PMAbuseReporting;
use Politizr\Model\PMAbuseReportingQuery;
use Politizr\Model\PMAppException;
use Politizr\Model\PMAppExceptionQuery;
use Politizr\Model\PMAskForUpdate;
use Politizr\Model\PMAskForUpdateQuery;
use Politizr\Model\PMDCommentHistoric;
use Politizr\Model\PMDCommentHistoricQuery;
use Politizr\Model\PMDebateHistoric;
use Politizr\Model\PMDebateHistoricQuery;
use Politizr\Model\PMEmailing;
use Politizr\Model\PMEmailingQuery;
use Politizr\Model\PMModerationType;
use Politizr\Model\PMModerationTypeQuery;
use Politizr\Model\PMRCommentHistoric;
use Politizr\Model\PMRCommentHistoricQuery;
use Politizr\Model\PMReactionHistoric;
use Politizr\Model\PMReactionHistoricQuery;
use Politizr\Model\PMUserHistoric;
use Politizr\Model\PMUserHistoricQuery;
use Politizr\Model\PMUserMessage;
use Politizr\Model\PMUserMessageQuery;
use Politizr\Model\PMUserModerated;
use Politizr\Model\PMUserModeratedQuery;
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
use Politizr\Model\PUBadge;
use Politizr\Model\PUBadgeQuery;
use Politizr\Model\PUBookmarkDD;
use Politizr\Model\PUBookmarkDDQuery;
use Politizr\Model\PUBookmarkDR;
use Politizr\Model\PUBookmarkDRQuery;
use Politizr\Model\PUCurrentQO;
use Politizr\Model\PUCurrentQOQuery;
use Politizr\Model\PUFollowDD;
use Politizr\Model\PUFollowDDQuery;
use Politizr\Model\PUFollowU;
use Politizr\Model\PUFollowUPeer;
use Politizr\Model\PUFollowUQuery;
use Politizr\Model\PUMandate;
use Politizr\Model\PUMandateQuery;
use Politizr\Model\PUNotification;
use Politizr\Model\PUNotificationQuery;
use Politizr\Model\PUReputation;
use Politizr\Model\PUReputationQuery;
use Politizr\Model\PURoleQ;
use Politizr\Model\PURoleQQuery;
use Politizr\Model\PUStatus;
use Politizr\Model\PUStatusQuery;
use Politizr\Model\PUSubscribePNE;
use Politizr\Model\PUSubscribePNEQuery;
use Politizr\Model\PUTaggedT;
use Politizr\Model\PUTaggedTQuery;
use Politizr\Model\PUTrackDD;
use Politizr\Model\PUTrackDDQuery;
use Politizr\Model\PUTrackDR;
use Politizr\Model\PUTrackDRQuery;
use Politizr\Model\PUTrackU;
use Politizr\Model\PUTrackUQuery;
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
     * @var        PUStatus
     */
    protected $aPUStatus;

    /**
     * @var        PLCity
     */
    protected $aPLCity;

    /**
     * @var        PropelObjectCollection|PTag[] Collection to store aggregation of PTag objects.
     */
    protected $collPUsers;
    protected $collPUsersPartial;

    /**
     * @var        PropelObjectCollection|PTag[] Collection to store aggregation of PTag objects.
     */
    protected $collPOwners;
    protected $collPOwnersPartial;

    /**
     * @var        PropelObjectCollection|PEOperation[] Collection to store aggregation of PEOperation objects.
     */
    protected $collPEOperations;
    protected $collPEOperationsPartial;

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
     * @var        PropelObjectCollection|PUBookmarkDD[] Collection to store aggregation of PUBookmarkDD objects.
     */
    protected $collPuBookmarkDdPUsers;
    protected $collPuBookmarkDdPUsersPartial;

    /**
     * @var        PropelObjectCollection|PUBookmarkDR[] Collection to store aggregation of PUBookmarkDR objects.
     */
    protected $collPuBookmarkDrPUsers;
    protected $collPuBookmarkDrPUsersPartial;

    /**
     * @var        PropelObjectCollection|PUTrackDD[] Collection to store aggregation of PUTrackDD objects.
     */
    protected $collPuTrackDdPUsers;
    protected $collPuTrackDdPUsersPartial;

    /**
     * @var        PropelObjectCollection|PUTrackDR[] Collection to store aggregation of PUTrackDR objects.
     */
    protected $collPuTrackDrPUsers;
    protected $collPuTrackDrPUsersPartial;

    /**
     * @var        PropelObjectCollection|PUBadge[] Collection to store aggregation of PUBadge objects.
     */
    protected $collPUBadges;
    protected $collPUBadgesPartial;

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
    protected $collPUAffinityQOPUsers;
    protected $collPUAffinityQOPUsersPartial;

    /**
     * @var        PropelObjectCollection|PUCurrentQO[] Collection to store aggregation of PUCurrentQO objects.
     */
    protected $collPUCurrentQOPUsers;
    protected $collPUCurrentQOPUsersPartial;

    /**
     * @var        PropelObjectCollection|PUNotification[] Collection to store aggregation of PUNotification objects.
     */
    protected $collPUNotificationPUsers;
    protected $collPUNotificationPUsersPartial;

    /**
     * @var        PropelObjectCollection|PUSubscribePNE[] Collection to store aggregation of PUSubscribePNE objects.
     */
    protected $collPUSubscribePNEs;
    protected $collPUSubscribePNEsPartial;

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
     * @var        PropelObjectCollection|PDDComment[] Collection to store aggregation of PDDComment objects.
     */
    protected $collPDDComments;
    protected $collPDDCommentsPartial;

    /**
     * @var        PropelObjectCollection|PDRComment[] Collection to store aggregation of PDRComment objects.
     */
    protected $collPDRComments;
    protected $collPDRCommentsPartial;

    /**
     * @var        PropelObjectCollection|PMUserModerated[] Collection to store aggregation of PMUserModerated objects.
     */
    protected $collPMUserModerateds;
    protected $collPMUserModeratedsPartial;

    /**
     * @var        PropelObjectCollection|PMUserMessage[] Collection to store aggregation of PMUserMessage objects.
     */
    protected $collPMUserMessages;
    protected $collPMUserMessagesPartial;

    /**
     * @var        PropelObjectCollection|PMUserHistoric[] Collection to store aggregation of PMUserHistoric objects.
     */
    protected $collPMUserHistorics;
    protected $collPMUserHistoricsPartial;

    /**
     * @var        PropelObjectCollection|PMDebateHistoric[] Collection to store aggregation of PMDebateHistoric objects.
     */
    protected $collPMDebateHistorics;
    protected $collPMDebateHistoricsPartial;

    /**
     * @var        PropelObjectCollection|PMReactionHistoric[] Collection to store aggregation of PMReactionHistoric objects.
     */
    protected $collPMReactionHistorics;
    protected $collPMReactionHistoricsPartial;

    /**
     * @var        PropelObjectCollection|PMDCommentHistoric[] Collection to store aggregation of PMDCommentHistoric objects.
     */
    protected $collPMDCommentHistorics;
    protected $collPMDCommentHistoricsPartial;

    /**
     * @var        PropelObjectCollection|PMRCommentHistoric[] Collection to store aggregation of PMRCommentHistoric objects.
     */
    protected $collPMRCommentHistorics;
    protected $collPMRCommentHistoricsPartial;

    /**
     * @var        PropelObjectCollection|PMAskForUpdate[] Collection to store aggregation of PMAskForUpdate objects.
     */
    protected $collPMAskForUpdates;
    protected $collPMAskForUpdatesPartial;

    /**
     * @var        PropelObjectCollection|PMAbuseReporting[] Collection to store aggregation of PMAbuseReporting objects.
     */
    protected $collPMAbuseReportings;
    protected $collPMAbuseReportingsPartial;

    /**
     * @var        PropelObjectCollection|PMAppException[] Collection to store aggregation of PMAppException objects.
     */
    protected $collPMAppExceptions;
    protected $collPMAppExceptionsPartial;

    /**
     * @var        PropelObjectCollection|PMEmailing[] Collection to store aggregation of PMEmailing objects.
     */
    protected $collPMEmailings;
    protected $collPMEmailingsPartial;

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
     * @var        PropelObjectCollection|PUTrackU[] Collection to store aggregation of PUTrackU objects.
     */
    protected $collPUTrackUsRelatedByPUserIdSource;
    protected $collPUTrackUsRelatedByPUserIdSourcePartial;

    /**
     * @var        PropelObjectCollection|PUTrackU[] Collection to store aggregation of PUTrackU objects.
     */
    protected $collPUTrackUsRelatedByPUserIdDest;
    protected $collPUTrackUsRelatedByPUserIdDestPartial;

    /**
     * @var        PropelObjectCollection|PDDebate[] Collection to store aggregation of PDDebate objects.
     */
    protected $collPuFollowDdPDDebates;

    /**
     * @var        PropelObjectCollection|PDDebate[] Collection to store aggregation of PDDebate objects.
     */
    protected $collPuBookmarkDdPDDebates;

    /**
     * @var        PropelObjectCollection|PDReaction[] Collection to store aggregation of PDReaction objects.
     */
    protected $collPuBookmarkDrPDReactions;

    /**
     * @var        PropelObjectCollection|PDDebate[] Collection to store aggregation of PDDebate objects.
     */
    protected $collPuTrackDdPDDebates;

    /**
     * @var        PropelObjectCollection|PDReaction[] Collection to store aggregation of PDReaction objects.
     */
    protected $collPuTrackDrPDReactions;

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
     * @var        PropelObjectCollection|PQualification[] Collection to store aggregation of PQualification objects.
     */
    protected $collPQualifications;

    /**
     * @var        PropelObjectCollection|PQOrganization[] Collection to store aggregation of PQOrganization objects.
     */
    protected $collPUAffinityQOPQOrganizations;

    /**
     * @var        PropelObjectCollection|PQOrganization[] Collection to store aggregation of PQOrganization objects.
     */
    protected $collPUCurrentQOPQOrganizations;

    /**
     * @var        PropelObjectCollection|PNotification[] Collection to store aggregation of PNotification objects.
     */
    protected $collPUNotificationPNotifications;

    /**
     * @var        PropelObjectCollection|PNEmail[] Collection to store aggregation of PNEmail objects.
     */
    protected $collPNEmails;

    /**
     * @var        PropelObjectCollection|PMModerationType[] Collection to store aggregation of PMModerationType objects.
     */
    protected $collPMModerationTypes;

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
    protected $puBookmarkDdPDDebatesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $puBookmarkDrPDReactionsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $puTrackDdPDDebatesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $puTrackDrPDReactionsScheduledForDeletion = null;

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
    protected $pQualificationsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $pUAffinityQOPQOrganizationsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $pUCurrentQOPQOrganizationsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $pUNotificationPNotificationsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $pNEmailsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $pMModerationTypesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $pUsersScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $pOwnersScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $pEOperationsScheduledForDeletion = null;

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
    protected $puBookmarkDdPUsersScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $puBookmarkDrPUsersScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $puTrackDdPUsersScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $puTrackDrPUsersScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $pUBadgesScheduledForDeletion = null;

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
    protected $pUAffinityQOPUsersScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $pUCurrentQOPUsersScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $pUNotificationPUsersScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $pUSubscribePNEsScheduledForDeletion = null;

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
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $pDDCommentsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $pDRCommentsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $pMUserModeratedsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $pMUserMessagesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $pMUserHistoricsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $pMDebateHistoricsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $pMReactionHistoricsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $pMDCommentHistoricsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $pMRCommentHistoricsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $pMAskForUpdatesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $pMAbuseReportingsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $pMAppExceptionsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $pMEmailingsScheduledForDeletion = null;

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
    protected $pUTrackUsRelatedByPUserIdSourceScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $pUTrackUsRelatedByPUserIdDestScheduledForDeletion = null;

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
     * Set the value of [uuid] column.
     *
     * @param  string $v new value
     * @return PUser The current object (for fluent API support)
     */
    public function setUuid($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->uuid !== $v) {
            $this->uuid = $v;
            $this->modifiedColumns[] = PUserPeer::UUID;
        }


        return $this;
    } // setUuid()

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
     * Set the value of [p_l_city_id] column.
     *
     * @param  int $v new value
     * @return PUser The current object (for fluent API support)
     */
    public function setPLCityId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->p_l_city_id !== $v) {
            $this->p_l_city_id = $v;
            $this->modifiedColumns[] = PUserPeer::P_L_CITY_ID;
        }

        if ($this->aPLCity !== null && $this->aPLCity->getId() !== $v) {
            $this->aPLCity = null;
        }


        return $this;
    } // setPLCityId()

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
     * Sets the value of [last_activity] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return PUser The current object (for fluent API support)
     */
    public function setLastActivity($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->last_activity !== null || $dt !== null) {
            $currentDateAsString = ($this->last_activity !== null && $tmpDt = new DateTime($this->last_activity)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->last_activity = $newDateAsString;
                $this->modifiedColumns[] = PUserPeer::LAST_ACTIVITY;
            }
        } // if either are not null


        return $this;
    } // setLastActivity()

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
     * Set the value of [back_file_name] column.
     *
     * @param  string $v new value
     * @return PUser The current object (for fluent API support)
     */
    public function setBackFileName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->back_file_name !== $v) {
            $this->back_file_name = $v;
            $this->modifiedColumns[] = PUserPeer::BACK_FILE_NAME;
        }


        return $this;
    } // setBackFileName()

    /**
     * Set the value of [copyright] column.
     *
     * @param  string $v new value
     * @return PUser The current object (for fluent API support)
     */
    public function setCopyright($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->copyright !== $v) {
            $this->copyright = $v;
            $this->modifiedColumns[] = PUserPeer::COPYRIGHT;
        }


        return $this;
    } // setCopyright()

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
     * Set the value of [subtitle] column.
     *
     * @param  string $v new value
     * @return PUser The current object (for fluent API support)
     */
    public function setSubtitle($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->subtitle !== $v) {
            $this->subtitle = $v;
            $this->modifiedColumns[] = PUserPeer::SUBTITLE;
        }


        return $this;
    } // setSubtitle()

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
     * Sets the value of [indexed_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return PUser The current object (for fluent API support)
     */
    public function setIndexedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->indexed_at !== null || $dt !== null) {
            $currentDateAsString = ($this->indexed_at !== null && $tmpDt = new DateTime($this->indexed_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->indexed_at = $newDateAsString;
                $this->modifiedColumns[] = PUserPeer::INDEXED_AT;
            }
        } // if either are not null


        return $this;
    } // setIndexedAt()

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
     * Set the value of [nb_id_check] column.
     *
     * @param  int $v new value
     * @return PUser The current object (for fluent API support)
     */
    public function setNbIdCheck($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->nb_id_check !== $v) {
            $this->nb_id_check = $v;
            $this->modifiedColumns[] = PUserPeer::NB_ID_CHECK;
        }


        return $this;
    } // setNbIdCheck()

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
     * Sets the value of the [homepage] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param boolean|integer|string $v The new value
     * @return PUser The current object (for fluent API support)
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
            $this->modifiedColumns[] = PUserPeer::HOMEPAGE;
        }


        return $this;
    } // setHomepage()

    /**
     * Sets the value of the [banned] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param boolean|integer|string $v The new value
     * @return PUser The current object (for fluent API support)
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
            $this->modifiedColumns[] = PUserPeer::BANNED;
        }


        return $this;
    } // setBanned()

    /**
     * Set the value of [banned_nb_days_left] column.
     *
     * @param  int $v new value
     * @return PUser The current object (for fluent API support)
     */
    public function setBannedNbDaysLeft($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->banned_nb_days_left !== $v) {
            $this->banned_nb_days_left = $v;
            $this->modifiedColumns[] = PUserPeer::BANNED_NB_DAYS_LEFT;
        }


        return $this;
    } // setBannedNbDaysLeft()

    /**
     * Set the value of [banned_nb_total] column.
     *
     * @param  int $v new value
     * @return PUser The current object (for fluent API support)
     */
    public function setBannedNbTotal($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->banned_nb_total !== $v) {
            $this->banned_nb_total = $v;
            $this->modifiedColumns[] = PUserPeer::BANNED_NB_TOTAL;
        }


        return $this;
    } // setBannedNbTotal()

    /**
     * Set the value of [abuse_level] column.
     *
     * @param  int $v new value
     * @return PUser The current object (for fluent API support)
     */
    public function setAbuseLevel($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->abuse_level !== $v) {
            $this->abuse_level = $v;
            $this->modifiedColumns[] = PUserPeer::ABUSE_LEVEL;
        }


        return $this;
    } // setAbuseLevel()

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
            $this->online = ($row[$startcol + 46] !== null) ? (boolean) $row[$startcol + 46] : null;
            $this->homepage = ($row[$startcol + 47] !== null) ? (boolean) $row[$startcol + 47] : null;
            $this->banned = ($row[$startcol + 48] !== null) ? (boolean) $row[$startcol + 48] : null;
            $this->banned_nb_days_left = ($row[$startcol + 49] !== null) ? (int) $row[$startcol + 49] : null;
            $this->banned_nb_total = ($row[$startcol + 50] !== null) ? (int) $row[$startcol + 50] : null;
            $this->abuse_level = ($row[$startcol + 51] !== null) ? (int) $row[$startcol + 51] : null;
            $this->created_at = ($row[$startcol + 52] !== null) ? (string) $row[$startcol + 52] : null;
            $this->updated_at = ($row[$startcol + 53] !== null) ? (string) $row[$startcol + 53] : null;
            $this->slug = ($row[$startcol + 54] !== null) ? (string) $row[$startcol + 54] : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }
            $this->postHydrate($row, $startcol, $rehydrate);

            return $startcol + 55; // 55 = PUserPeer::NUM_HYDRATE_COLUMNS.

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
        if ($this->aPLCity !== null && $this->p_l_city_id !== $this->aPLCity->getId()) {
            $this->aPLCity = null;
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
            $this->aPLCity = null;
            $this->collPUsers = null;

            $this->collPOwners = null;

            $this->collPEOperations = null;

            $this->collPOrders = null;

            $this->collPuFollowDdPUsers = null;

            $this->collPuBookmarkDdPUsers = null;

            $this->collPuBookmarkDrPUsers = null;

            $this->collPuTrackDdPUsers = null;

            $this->collPuTrackDrPUsers = null;

            $this->collPUBadges = null;

            $this->collPUReputations = null;

            $this->collPuTaggedTPUsers = null;

            $this->collPURoleQs = null;

            $this->collPUMandates = null;

            $this->collPUAffinityQOPUsers = null;

            $this->collPUCurrentQOPUsers = null;

            $this->collPUNotificationPUsers = null;

            $this->collPUSubscribePNEs = null;

            $this->collPDDebates = null;

            $this->collPDReactions = null;

            $this->collPDDComments = null;

            $this->collPDRComments = null;

            $this->collPMUserModerateds = null;

            $this->collPMUserMessages = null;

            $this->collPMUserHistorics = null;

            $this->collPMDebateHistorics = null;

            $this->collPMReactionHistorics = null;

            $this->collPMDCommentHistorics = null;

            $this->collPMRCommentHistorics = null;

            $this->collPMAskForUpdates = null;

            $this->collPMAbuseReportings = null;

            $this->collPMAppExceptions = null;

            $this->collPMEmailings = null;

            $this->collPUFollowUsRelatedByPUserId = null;

            $this->collPUFollowUsRelatedByPUserFollowerId = null;

            $this->collPUTrackUsRelatedByPUserIdSource = null;

            $this->collPUTrackUsRelatedByPUserIdDest = null;

            $this->collPuFollowDdPDDebates = null;
            $this->collPuBookmarkDdPDDebates = null;
            $this->collPuBookmarkDrPDReactions = null;
            $this->collPuTrackDdPDDebates = null;
            $this->collPuTrackDrPDReactions = null;
            $this->collPRBadges = null;
            $this->collPRActions = null;
            $this->collPuTaggedTPTags = null;
            $this->collPQualifications = null;
            $this->collPUAffinityQOPQOrganizations = null;
            $this->collPUCurrentQOPQOrganizations = null;
            $this->collPUNotificationPNotifications = null;
            $this->collPNEmails = null;
            $this->collPMModerationTypes = null;
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

            if ($this->aPLCity !== null) {
                if ($this->aPLCity->isModified() || $this->aPLCity->isNew()) {
                    $affectedRows += $this->aPLCity->save($con);
                }
                $this->setPLCity($this->aPLCity);
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

            if ($this->puBookmarkDdPDDebatesScheduledForDeletion !== null) {
                if (!$this->puBookmarkDdPDDebatesScheduledForDeletion->isEmpty()) {
                    $pks = array();
                    $pk = $this->getPrimaryKey();
                    foreach ($this->puBookmarkDdPDDebatesScheduledForDeletion->getPrimaryKeys(false) as $remotePk) {
                        $pks[] = array($pk, $remotePk);
                    }
                    PUBookmarkDDQuery::create()
                        ->filterByPrimaryKeys($pks)
                        ->delete($con);
                    $this->puBookmarkDdPDDebatesScheduledForDeletion = null;
                }

                foreach ($this->getPuBookmarkDdPDDebates() as $puBookmarkDdPDDebate) {
                    if ($puBookmarkDdPDDebate->isModified()) {
                        $puBookmarkDdPDDebate->save($con);
                    }
                }
            } elseif ($this->collPuBookmarkDdPDDebates) {
                foreach ($this->collPuBookmarkDdPDDebates as $puBookmarkDdPDDebate) {
                    if ($puBookmarkDdPDDebate->isModified()) {
                        $puBookmarkDdPDDebate->save($con);
                    }
                }
            }

            if ($this->puBookmarkDrPDReactionsScheduledForDeletion !== null) {
                if (!$this->puBookmarkDrPDReactionsScheduledForDeletion->isEmpty()) {
                    $pks = array();
                    $pk = $this->getPrimaryKey();
                    foreach ($this->puBookmarkDrPDReactionsScheduledForDeletion->getPrimaryKeys(false) as $remotePk) {
                        $pks[] = array($pk, $remotePk);
                    }
                    PUBookmarkDRQuery::create()
                        ->filterByPrimaryKeys($pks)
                        ->delete($con);
                    $this->puBookmarkDrPDReactionsScheduledForDeletion = null;
                }

                foreach ($this->getPuBookmarkDrPDReactions() as $puBookmarkDrPDReaction) {
                    if ($puBookmarkDrPDReaction->isModified()) {
                        $puBookmarkDrPDReaction->save($con);
                    }
                }
            } elseif ($this->collPuBookmarkDrPDReactions) {
                foreach ($this->collPuBookmarkDrPDReactions as $puBookmarkDrPDReaction) {
                    if ($puBookmarkDrPDReaction->isModified()) {
                        $puBookmarkDrPDReaction->save($con);
                    }
                }
            }

            if ($this->puTrackDdPDDebatesScheduledForDeletion !== null) {
                if (!$this->puTrackDdPDDebatesScheduledForDeletion->isEmpty()) {
                    $pks = array();
                    $pk = $this->getPrimaryKey();
                    foreach ($this->puTrackDdPDDebatesScheduledForDeletion->getPrimaryKeys(false) as $remotePk) {
                        $pks[] = array($pk, $remotePk);
                    }
                    PUTrackDDQuery::create()
                        ->filterByPrimaryKeys($pks)
                        ->delete($con);
                    $this->puTrackDdPDDebatesScheduledForDeletion = null;
                }

                foreach ($this->getPuTrackDdPDDebates() as $puTrackDdPDDebate) {
                    if ($puTrackDdPDDebate->isModified()) {
                        $puTrackDdPDDebate->save($con);
                    }
                }
            } elseif ($this->collPuTrackDdPDDebates) {
                foreach ($this->collPuTrackDdPDDebates as $puTrackDdPDDebate) {
                    if ($puTrackDdPDDebate->isModified()) {
                        $puTrackDdPDDebate->save($con);
                    }
                }
            }

            if ($this->puTrackDrPDReactionsScheduledForDeletion !== null) {
                if (!$this->puTrackDrPDReactionsScheduledForDeletion->isEmpty()) {
                    $pks = array();
                    $pk = $this->getPrimaryKey();
                    foreach ($this->puTrackDrPDReactionsScheduledForDeletion->getPrimaryKeys(false) as $remotePk) {
                        $pks[] = array($pk, $remotePk);
                    }
                    PUTrackDRQuery::create()
                        ->filterByPrimaryKeys($pks)
                        ->delete($con);
                    $this->puTrackDrPDReactionsScheduledForDeletion = null;
                }

                foreach ($this->getPuTrackDrPDReactions() as $puTrackDrPDReaction) {
                    if ($puTrackDrPDReaction->isModified()) {
                        $puTrackDrPDReaction->save($con);
                    }
                }
            } elseif ($this->collPuTrackDrPDReactions) {
                foreach ($this->collPuTrackDrPDReactions as $puTrackDrPDReaction) {
                    if ($puTrackDrPDReaction->isModified()) {
                        $puTrackDrPDReaction->save($con);
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
                    PUBadgeQuery::create()
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

            if ($this->pUAffinityQOPQOrganizationsScheduledForDeletion !== null) {
                if (!$this->pUAffinityQOPQOrganizationsScheduledForDeletion->isEmpty()) {
                    $pks = array();
                    $pk = $this->getPrimaryKey();
                    foreach ($this->pUAffinityQOPQOrganizationsScheduledForDeletion->getPrimaryKeys(false) as $remotePk) {
                        $pks[] = array($pk, $remotePk);
                    }
                    PUAffinityQOQuery::create()
                        ->filterByPrimaryKeys($pks)
                        ->delete($con);
                    $this->pUAffinityQOPQOrganizationsScheduledForDeletion = null;
                }

                foreach ($this->getPUAffinityQOPQOrganizations() as $pUAffinityQOPQOrganization) {
                    if ($pUAffinityQOPQOrganization->isModified()) {
                        $pUAffinityQOPQOrganization->save($con);
                    }
                }
            } elseif ($this->collPUAffinityQOPQOrganizations) {
                foreach ($this->collPUAffinityQOPQOrganizations as $pUAffinityQOPQOrganization) {
                    if ($pUAffinityQOPQOrganization->isModified()) {
                        $pUAffinityQOPQOrganization->save($con);
                    }
                }
            }

            if ($this->pUCurrentQOPQOrganizationsScheduledForDeletion !== null) {
                if (!$this->pUCurrentQOPQOrganizationsScheduledForDeletion->isEmpty()) {
                    $pks = array();
                    $pk = $this->getPrimaryKey();
                    foreach ($this->pUCurrentQOPQOrganizationsScheduledForDeletion->getPrimaryKeys(false) as $remotePk) {
                        $pks[] = array($pk, $remotePk);
                    }
                    PUCurrentQOQuery::create()
                        ->filterByPrimaryKeys($pks)
                        ->delete($con);
                    $this->pUCurrentQOPQOrganizationsScheduledForDeletion = null;
                }

                foreach ($this->getPUCurrentQOPQOrganizations() as $pUCurrentQOPQOrganization) {
                    if ($pUCurrentQOPQOrganization->isModified()) {
                        $pUCurrentQOPQOrganization->save($con);
                    }
                }
            } elseif ($this->collPUCurrentQOPQOrganizations) {
                foreach ($this->collPUCurrentQOPQOrganizations as $pUCurrentQOPQOrganization) {
                    if ($pUCurrentQOPQOrganization->isModified()) {
                        $pUCurrentQOPQOrganization->save($con);
                    }
                }
            }

            if ($this->pUNotificationPNotificationsScheduledForDeletion !== null) {
                if (!$this->pUNotificationPNotificationsScheduledForDeletion->isEmpty()) {
                    $pks = array();
                    $pk = $this->getPrimaryKey();
                    foreach ($this->pUNotificationPNotificationsScheduledForDeletion->getPrimaryKeys(false) as $remotePk) {
                        $pks[] = array($pk, $remotePk);
                    }
                    PUNotificationQuery::create()
                        ->filterByPrimaryKeys($pks)
                        ->delete($con);
                    $this->pUNotificationPNotificationsScheduledForDeletion = null;
                }

                foreach ($this->getPUNotificationPNotifications() as $pUNotificationPNotification) {
                    if ($pUNotificationPNotification->isModified()) {
                        $pUNotificationPNotification->save($con);
                    }
                }
            } elseif ($this->collPUNotificationPNotifications) {
                foreach ($this->collPUNotificationPNotifications as $pUNotificationPNotification) {
                    if ($pUNotificationPNotification->isModified()) {
                        $pUNotificationPNotification->save($con);
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
                    PUSubscribePNEQuery::create()
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

            if ($this->pMModerationTypesScheduledForDeletion !== null) {
                if (!$this->pMModerationTypesScheduledForDeletion->isEmpty()) {
                    $pks = array();
                    $pk = $this->getPrimaryKey();
                    foreach ($this->pMModerationTypesScheduledForDeletion->getPrimaryKeys(false) as $remotePk) {
                        $pks[] = array($pk, $remotePk);
                    }
                    PMUserModeratedQuery::create()
                        ->filterByPrimaryKeys($pks)
                        ->delete($con);
                    $this->pMModerationTypesScheduledForDeletion = null;
                }

                foreach ($this->getPMModerationTypes() as $pMModerationType) {
                    if ($pMModerationType->isModified()) {
                        $pMModerationType->save($con);
                    }
                }
            } elseif ($this->collPMModerationTypes) {
                foreach ($this->collPMModerationTypes as $pMModerationType) {
                    if ($pMModerationType->isModified()) {
                        $pMModerationType->save($con);
                    }
                }
            }

            if ($this->pUsersScheduledForDeletion !== null) {
                if (!$this->pUsersScheduledForDeletion->isEmpty()) {
                    foreach ($this->pUsersScheduledForDeletion as $pUser) {
                        // need to save related object because we set the relation to null
                        $pUser->save($con);
                    }
                    $this->pUsersScheduledForDeletion = null;
                }
            }

            if ($this->collPUsers !== null) {
                foreach ($this->collPUsers as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->pOwnersScheduledForDeletion !== null) {
                if (!$this->pOwnersScheduledForDeletion->isEmpty()) {
                    foreach ($this->pOwnersScheduledForDeletion as $pOwner) {
                        // need to save related object because we set the relation to null
                        $pOwner->save($con);
                    }
                    $this->pOwnersScheduledForDeletion = null;
                }
            }

            if ($this->collPOwners !== null) {
                foreach ($this->collPOwners as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->pEOperationsScheduledForDeletion !== null) {
                if (!$this->pEOperationsScheduledForDeletion->isEmpty()) {
                    PEOperationQuery::create()
                        ->filterByPrimaryKeys($this->pEOperationsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->pEOperationsScheduledForDeletion = null;
                }
            }

            if ($this->collPEOperations !== null) {
                foreach ($this->collPEOperations as $referrerFK) {
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

            if ($this->puBookmarkDdPUsersScheduledForDeletion !== null) {
                if (!$this->puBookmarkDdPUsersScheduledForDeletion->isEmpty()) {
                    PUBookmarkDDQuery::create()
                        ->filterByPrimaryKeys($this->puBookmarkDdPUsersScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->puBookmarkDdPUsersScheduledForDeletion = null;
                }
            }

            if ($this->collPuBookmarkDdPUsers !== null) {
                foreach ($this->collPuBookmarkDdPUsers as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->puBookmarkDrPUsersScheduledForDeletion !== null) {
                if (!$this->puBookmarkDrPUsersScheduledForDeletion->isEmpty()) {
                    PUBookmarkDRQuery::create()
                        ->filterByPrimaryKeys($this->puBookmarkDrPUsersScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->puBookmarkDrPUsersScheduledForDeletion = null;
                }
            }

            if ($this->collPuBookmarkDrPUsers !== null) {
                foreach ($this->collPuBookmarkDrPUsers as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->puTrackDdPUsersScheduledForDeletion !== null) {
                if (!$this->puTrackDdPUsersScheduledForDeletion->isEmpty()) {
                    PUTrackDDQuery::create()
                        ->filterByPrimaryKeys($this->puTrackDdPUsersScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->puTrackDdPUsersScheduledForDeletion = null;
                }
            }

            if ($this->collPuTrackDdPUsers !== null) {
                foreach ($this->collPuTrackDdPUsers as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->puTrackDrPUsersScheduledForDeletion !== null) {
                if (!$this->puTrackDrPUsersScheduledForDeletion->isEmpty()) {
                    PUTrackDRQuery::create()
                        ->filterByPrimaryKeys($this->puTrackDrPUsersScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->puTrackDrPUsersScheduledForDeletion = null;
                }
            }

            if ($this->collPuTrackDrPUsers !== null) {
                foreach ($this->collPuTrackDrPUsers as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->pUBadgesScheduledForDeletion !== null) {
                if (!$this->pUBadgesScheduledForDeletion->isEmpty()) {
                    PUBadgeQuery::create()
                        ->filterByPrimaryKeys($this->pUBadgesScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->pUBadgesScheduledForDeletion = null;
                }
            }

            if ($this->collPUBadges !== null) {
                foreach ($this->collPUBadges as $referrerFK) {
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

            if ($this->pUAffinityQOPUsersScheduledForDeletion !== null) {
                if (!$this->pUAffinityQOPUsersScheduledForDeletion->isEmpty()) {
                    PUAffinityQOQuery::create()
                        ->filterByPrimaryKeys($this->pUAffinityQOPUsersScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->pUAffinityQOPUsersScheduledForDeletion = null;
                }
            }

            if ($this->collPUAffinityQOPUsers !== null) {
                foreach ($this->collPUAffinityQOPUsers as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->pUCurrentQOPUsersScheduledForDeletion !== null) {
                if (!$this->pUCurrentQOPUsersScheduledForDeletion->isEmpty()) {
                    PUCurrentQOQuery::create()
                        ->filterByPrimaryKeys($this->pUCurrentQOPUsersScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->pUCurrentQOPUsersScheduledForDeletion = null;
                }
            }

            if ($this->collPUCurrentQOPUsers !== null) {
                foreach ($this->collPUCurrentQOPUsers as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->pUNotificationPUsersScheduledForDeletion !== null) {
                if (!$this->pUNotificationPUsersScheduledForDeletion->isEmpty()) {
                    PUNotificationQuery::create()
                        ->filterByPrimaryKeys($this->pUNotificationPUsersScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->pUNotificationPUsersScheduledForDeletion = null;
                }
            }

            if ($this->collPUNotificationPUsers !== null) {
                foreach ($this->collPUNotificationPUsers as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->pUSubscribePNEsScheduledForDeletion !== null) {
                if (!$this->pUSubscribePNEsScheduledForDeletion->isEmpty()) {
                    PUSubscribePNEQuery::create()
                        ->filterByPrimaryKeys($this->pUSubscribePNEsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->pUSubscribePNEsScheduledForDeletion = null;
                }
            }

            if ($this->collPUSubscribePNEs !== null) {
                foreach ($this->collPUSubscribePNEs as $referrerFK) {
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

            if ($this->pMUserModeratedsScheduledForDeletion !== null) {
                if (!$this->pMUserModeratedsScheduledForDeletion->isEmpty()) {
                    PMUserModeratedQuery::create()
                        ->filterByPrimaryKeys($this->pMUserModeratedsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->pMUserModeratedsScheduledForDeletion = null;
                }
            }

            if ($this->collPMUserModerateds !== null) {
                foreach ($this->collPMUserModerateds as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->pMUserMessagesScheduledForDeletion !== null) {
                if (!$this->pMUserMessagesScheduledForDeletion->isEmpty()) {
                    foreach ($this->pMUserMessagesScheduledForDeletion as $pMUserMessage) {
                        // need to save related object because we set the relation to null
                        $pMUserMessage->save($con);
                    }
                    $this->pMUserMessagesScheduledForDeletion = null;
                }
            }

            if ($this->collPMUserMessages !== null) {
                foreach ($this->collPMUserMessages as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->pMUserHistoricsScheduledForDeletion !== null) {
                if (!$this->pMUserHistoricsScheduledForDeletion->isEmpty()) {
                    foreach ($this->pMUserHistoricsScheduledForDeletion as $pMUserHistoric) {
                        // need to save related object because we set the relation to null
                        $pMUserHistoric->save($con);
                    }
                    $this->pMUserHistoricsScheduledForDeletion = null;
                }
            }

            if ($this->collPMUserHistorics !== null) {
                foreach ($this->collPMUserHistorics as $referrerFK) {
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

            if ($this->pMRCommentHistoricsScheduledForDeletion !== null) {
                if (!$this->pMRCommentHistoricsScheduledForDeletion->isEmpty()) {
                    foreach ($this->pMRCommentHistoricsScheduledForDeletion as $pMRCommentHistoric) {
                        // need to save related object because we set the relation to null
                        $pMRCommentHistoric->save($con);
                    }
                    $this->pMRCommentHistoricsScheduledForDeletion = null;
                }
            }

            if ($this->collPMRCommentHistorics !== null) {
                foreach ($this->collPMRCommentHistorics as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->pMAskForUpdatesScheduledForDeletion !== null) {
                if (!$this->pMAskForUpdatesScheduledForDeletion->isEmpty()) {
                    foreach ($this->pMAskForUpdatesScheduledForDeletion as $pMAskForUpdate) {
                        // need to save related object because we set the relation to null
                        $pMAskForUpdate->save($con);
                    }
                    $this->pMAskForUpdatesScheduledForDeletion = null;
                }
            }

            if ($this->collPMAskForUpdates !== null) {
                foreach ($this->collPMAskForUpdates as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->pMAbuseReportingsScheduledForDeletion !== null) {
                if (!$this->pMAbuseReportingsScheduledForDeletion->isEmpty()) {
                    foreach ($this->pMAbuseReportingsScheduledForDeletion as $pMAbuseReporting) {
                        // need to save related object because we set the relation to null
                        $pMAbuseReporting->save($con);
                    }
                    $this->pMAbuseReportingsScheduledForDeletion = null;
                }
            }

            if ($this->collPMAbuseReportings !== null) {
                foreach ($this->collPMAbuseReportings as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->pMAppExceptionsScheduledForDeletion !== null) {
                if (!$this->pMAppExceptionsScheduledForDeletion->isEmpty()) {
                    foreach ($this->pMAppExceptionsScheduledForDeletion as $pMAppException) {
                        // need to save related object because we set the relation to null
                        $pMAppException->save($con);
                    }
                    $this->pMAppExceptionsScheduledForDeletion = null;
                }
            }

            if ($this->collPMAppExceptions !== null) {
                foreach ($this->collPMAppExceptions as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->pMEmailingsScheduledForDeletion !== null) {
                if (!$this->pMEmailingsScheduledForDeletion->isEmpty()) {
                    foreach ($this->pMEmailingsScheduledForDeletion as $pMEmailing) {
                        // need to save related object because we set the relation to null
                        $pMEmailing->save($con);
                    }
                    $this->pMEmailingsScheduledForDeletion = null;
                }
            }

            if ($this->collPMEmailings !== null) {
                foreach ($this->collPMEmailings as $referrerFK) {
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

            if ($this->pUTrackUsRelatedByPUserIdSourceScheduledForDeletion !== null) {
                if (!$this->pUTrackUsRelatedByPUserIdSourceScheduledForDeletion->isEmpty()) {
                    PUTrackUQuery::create()
                        ->filterByPrimaryKeys($this->pUTrackUsRelatedByPUserIdSourceScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->pUTrackUsRelatedByPUserIdSourceScheduledForDeletion = null;
                }
            }

            if ($this->collPUTrackUsRelatedByPUserIdSource !== null) {
                foreach ($this->collPUTrackUsRelatedByPUserIdSource as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->pUTrackUsRelatedByPUserIdDestScheduledForDeletion !== null) {
                if (!$this->pUTrackUsRelatedByPUserIdDestScheduledForDeletion->isEmpty()) {
                    PUTrackUQuery::create()
                        ->filterByPrimaryKeys($this->pUTrackUsRelatedByPUserIdDestScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->pUTrackUsRelatedByPUserIdDestScheduledForDeletion = null;
                }
            }

            if ($this->collPUTrackUsRelatedByPUserIdDest !== null) {
                foreach ($this->collPUTrackUsRelatedByPUserIdDest as $referrerFK) {
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
        if ($this->isColumnModified(PUserPeer::UUID)) {
            $modifiedColumns[':p' . $index++]  = '`uuid`';
        }
        if ($this->isColumnModified(PUserPeer::P_U_STATUS_ID)) {
            $modifiedColumns[':p' . $index++]  = '`p_u_status_id`';
        }
        if ($this->isColumnModified(PUserPeer::P_L_CITY_ID)) {
            $modifiedColumns[':p' . $index++]  = '`p_l_city_id`';
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
        if ($this->isColumnModified(PUserPeer::LAST_ACTIVITY)) {
            $modifiedColumns[':p' . $index++]  = '`last_activity`';
        }
        if ($this->isColumnModified(PUserPeer::FILE_NAME)) {
            $modifiedColumns[':p' . $index++]  = '`file_name`';
        }
        if ($this->isColumnModified(PUserPeer::BACK_FILE_NAME)) {
            $modifiedColumns[':p' . $index++]  = '`back_file_name`';
        }
        if ($this->isColumnModified(PUserPeer::COPYRIGHT)) {
            $modifiedColumns[':p' . $index++]  = '`copyright`';
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
        if ($this->isColumnModified(PUserPeer::SUBTITLE)) {
            $modifiedColumns[':p' . $index++]  = '`subtitle`';
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
        if ($this->isColumnModified(PUserPeer::INDEXED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`indexed_at`';
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
        if ($this->isColumnModified(PUserPeer::NB_ID_CHECK)) {
            $modifiedColumns[':p' . $index++]  = '`nb_id_check`';
        }
        if ($this->isColumnModified(PUserPeer::ONLINE)) {
            $modifiedColumns[':p' . $index++]  = '`online`';
        }
        if ($this->isColumnModified(PUserPeer::HOMEPAGE)) {
            $modifiedColumns[':p' . $index++]  = '`homepage`';
        }
        if ($this->isColumnModified(PUserPeer::BANNED)) {
            $modifiedColumns[':p' . $index++]  = '`banned`';
        }
        if ($this->isColumnModified(PUserPeer::BANNED_NB_DAYS_LEFT)) {
            $modifiedColumns[':p' . $index++]  = '`banned_nb_days_left`';
        }
        if ($this->isColumnModified(PUserPeer::BANNED_NB_TOTAL)) {
            $modifiedColumns[':p' . $index++]  = '`banned_nb_total`';
        }
        if ($this->isColumnModified(PUserPeer::ABUSE_LEVEL)) {
            $modifiedColumns[':p' . $index++]  = '`abuse_level`';
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
                    case '`online`':
                        $stmt->bindValue($identifier, (int) $this->online, PDO::PARAM_INT);
                        break;
                    case '`homepage`':
                        $stmt->bindValue($identifier, (int) $this->homepage, PDO::PARAM_INT);
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
                return $this->getOnline();
                break;
            case 47:
                return $this->getHomepage();
                break;
            case 48:
                return $this->getBanned();
                break;
            case 49:
                return $this->getBannedNbDaysLeft();
                break;
            case 50:
                return $this->getBannedNbTotal();
                break;
            case 51:
                return $this->getAbuseLevel();
                break;
            case 52:
                return $this->getCreatedAt();
                break;
            case 53:
                return $this->getUpdatedAt();
                break;
            case 54:
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
            $keys[46] => $this->getOnline(),
            $keys[47] => $this->getHomepage(),
            $keys[48] => $this->getBanned(),
            $keys[49] => $this->getBannedNbDaysLeft(),
            $keys[50] => $this->getBannedNbTotal(),
            $keys[51] => $this->getAbuseLevel(),
            $keys[52] => $this->getCreatedAt(),
            $keys[53] => $this->getUpdatedAt(),
            $keys[54] => $this->getSlug(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aPUStatus) {
                $result['PUStatus'] = $this->aPUStatus->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aPLCity) {
                $result['PLCity'] = $this->aPLCity->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->collPUsers) {
                $result['PUsers'] = $this->collPUsers->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPOwners) {
                $result['POwners'] = $this->collPOwners->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPEOperations) {
                $result['PEOperations'] = $this->collPEOperations->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPOrders) {
                $result['POrders'] = $this->collPOrders->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPuFollowDdPUsers) {
                $result['PuFollowDdPUsers'] = $this->collPuFollowDdPUsers->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPuBookmarkDdPUsers) {
                $result['PuBookmarkDdPUsers'] = $this->collPuBookmarkDdPUsers->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPuBookmarkDrPUsers) {
                $result['PuBookmarkDrPUsers'] = $this->collPuBookmarkDrPUsers->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPuTrackDdPUsers) {
                $result['PuTrackDdPUsers'] = $this->collPuTrackDdPUsers->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPuTrackDrPUsers) {
                $result['PuTrackDrPUsers'] = $this->collPuTrackDrPUsers->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPUBadges) {
                $result['PUBadges'] = $this->collPUBadges->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPUReputations) {
                $result['PUReputations'] = $this->collPUReputations->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPuTaggedTPUsers) {
                $result['PuTaggedTPUsers'] = $this->collPuTaggedTPUsers->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPURoleQs) {
                $result['PURoleQs'] = $this->collPURoleQs->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPUMandates) {
                $result['PUMandates'] = $this->collPUMandates->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPUAffinityQOPUsers) {
                $result['PUAffinityQOPUsers'] = $this->collPUAffinityQOPUsers->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPUCurrentQOPUsers) {
                $result['PUCurrentQOPUsers'] = $this->collPUCurrentQOPUsers->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPUNotificationPUsers) {
                $result['PUNotificationPUsers'] = $this->collPUNotificationPUsers->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPUSubscribePNEs) {
                $result['PUSubscribePNEs'] = $this->collPUSubscribePNEs->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPDDebates) {
                $result['PDDebates'] = $this->collPDDebates->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPDReactions) {
                $result['PDReactions'] = $this->collPDReactions->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPDDComments) {
                $result['PDDComments'] = $this->collPDDComments->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPDRComments) {
                $result['PDRComments'] = $this->collPDRComments->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPMUserModerateds) {
                $result['PMUserModerateds'] = $this->collPMUserModerateds->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPMUserMessages) {
                $result['PMUserMessages'] = $this->collPMUserMessages->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPMUserHistorics) {
                $result['PMUserHistorics'] = $this->collPMUserHistorics->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPMDebateHistorics) {
                $result['PMDebateHistorics'] = $this->collPMDebateHistorics->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPMReactionHistorics) {
                $result['PMReactionHistorics'] = $this->collPMReactionHistorics->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPMDCommentHistorics) {
                $result['PMDCommentHistorics'] = $this->collPMDCommentHistorics->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPMRCommentHistorics) {
                $result['PMRCommentHistorics'] = $this->collPMRCommentHistorics->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPMAskForUpdates) {
                $result['PMAskForUpdates'] = $this->collPMAskForUpdates->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPMAbuseReportings) {
                $result['PMAbuseReportings'] = $this->collPMAbuseReportings->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPMAppExceptions) {
                $result['PMAppExceptions'] = $this->collPMAppExceptions->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPMEmailings) {
                $result['PMEmailings'] = $this->collPMEmailings->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPUFollowUsRelatedByPUserId) {
                $result['PUFollowUsRelatedByPUserId'] = $this->collPUFollowUsRelatedByPUserId->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPUFollowUsRelatedByPUserFollowerId) {
                $result['PUFollowUsRelatedByPUserFollowerId'] = $this->collPUFollowUsRelatedByPUserFollowerId->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPUTrackUsRelatedByPUserIdSource) {
                $result['PUTrackUsRelatedByPUserIdSource'] = $this->collPUTrackUsRelatedByPUserIdSource->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPUTrackUsRelatedByPUserIdDest) {
                $result['PUTrackUsRelatedByPUserIdDest'] = $this->collPUTrackUsRelatedByPUserIdDest->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
                $valueSet = PUserPeer::getValueSet(PUserPeer::GENDER);
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
                $this->setOnline($value);
                break;
            case 47:
                $this->setHomepage($value);
                break;
            case 48:
                $this->setBanned($value);
                break;
            case 49:
                $this->setBannedNbDaysLeft($value);
                break;
            case 50:
                $this->setBannedNbTotal($value);
                break;
            case 51:
                $this->setAbuseLevel($value);
                break;
            case 52:
                $this->setCreatedAt($value);
                break;
            case 53:
                $this->setUpdatedAt($value);
                break;
            case 54:
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
        if (array_key_exists($keys[46], $arr)) $this->setOnline($arr[$keys[46]]);
        if (array_key_exists($keys[47], $arr)) $this->setHomepage($arr[$keys[47]]);
        if (array_key_exists($keys[48], $arr)) $this->setBanned($arr[$keys[48]]);
        if (array_key_exists($keys[49], $arr)) $this->setBannedNbDaysLeft($arr[$keys[49]]);
        if (array_key_exists($keys[50], $arr)) $this->setBannedNbTotal($arr[$keys[50]]);
        if (array_key_exists($keys[51], $arr)) $this->setAbuseLevel($arr[$keys[51]]);
        if (array_key_exists($keys[52], $arr)) $this->setCreatedAt($arr[$keys[52]]);
        if (array_key_exists($keys[53], $arr)) $this->setUpdatedAt($arr[$keys[53]]);
        if (array_key_exists($keys[54], $arr)) $this->setSlug($arr[$keys[54]]);
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
        if ($this->isColumnModified(PUserPeer::UUID)) $criteria->add(PUserPeer::UUID, $this->uuid);
        if ($this->isColumnModified(PUserPeer::P_U_STATUS_ID)) $criteria->add(PUserPeer::P_U_STATUS_ID, $this->p_u_status_id);
        if ($this->isColumnModified(PUserPeer::P_L_CITY_ID)) $criteria->add(PUserPeer::P_L_CITY_ID, $this->p_l_city_id);
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
        if ($this->isColumnModified(PUserPeer::LAST_ACTIVITY)) $criteria->add(PUserPeer::LAST_ACTIVITY, $this->last_activity);
        if ($this->isColumnModified(PUserPeer::FILE_NAME)) $criteria->add(PUserPeer::FILE_NAME, $this->file_name);
        if ($this->isColumnModified(PUserPeer::BACK_FILE_NAME)) $criteria->add(PUserPeer::BACK_FILE_NAME, $this->back_file_name);
        if ($this->isColumnModified(PUserPeer::COPYRIGHT)) $criteria->add(PUserPeer::COPYRIGHT, $this->copyright);
        if ($this->isColumnModified(PUserPeer::GENDER)) $criteria->add(PUserPeer::GENDER, $this->gender);
        if ($this->isColumnModified(PUserPeer::FIRSTNAME)) $criteria->add(PUserPeer::FIRSTNAME, $this->firstname);
        if ($this->isColumnModified(PUserPeer::NAME)) $criteria->add(PUserPeer::NAME, $this->name);
        if ($this->isColumnModified(PUserPeer::BIRTHDAY)) $criteria->add(PUserPeer::BIRTHDAY, $this->birthday);
        if ($this->isColumnModified(PUserPeer::SUBTITLE)) $criteria->add(PUserPeer::SUBTITLE, $this->subtitle);
        if ($this->isColumnModified(PUserPeer::BIOGRAPHY)) $criteria->add(PUserPeer::BIOGRAPHY, $this->biography);
        if ($this->isColumnModified(PUserPeer::WEBSITE)) $criteria->add(PUserPeer::WEBSITE, $this->website);
        if ($this->isColumnModified(PUserPeer::TWITTER)) $criteria->add(PUserPeer::TWITTER, $this->twitter);
        if ($this->isColumnModified(PUserPeer::FACEBOOK)) $criteria->add(PUserPeer::FACEBOOK, $this->facebook);
        if ($this->isColumnModified(PUserPeer::PHONE)) $criteria->add(PUserPeer::PHONE, $this->phone);
        if ($this->isColumnModified(PUserPeer::NEWSLETTER)) $criteria->add(PUserPeer::NEWSLETTER, $this->newsletter);
        if ($this->isColumnModified(PUserPeer::LAST_CONNECT)) $criteria->add(PUserPeer::LAST_CONNECT, $this->last_connect);
        if ($this->isColumnModified(PUserPeer::NB_CONNECTED_DAYS)) $criteria->add(PUserPeer::NB_CONNECTED_DAYS, $this->nb_connected_days);
        if ($this->isColumnModified(PUserPeer::INDEXED_AT)) $criteria->add(PUserPeer::INDEXED_AT, $this->indexed_at);
        if ($this->isColumnModified(PUserPeer::NB_VIEWS)) $criteria->add(PUserPeer::NB_VIEWS, $this->nb_views);
        if ($this->isColumnModified(PUserPeer::QUALIFIED)) $criteria->add(PUserPeer::QUALIFIED, $this->qualified);
        if ($this->isColumnModified(PUserPeer::VALIDATED)) $criteria->add(PUserPeer::VALIDATED, $this->validated);
        if ($this->isColumnModified(PUserPeer::NB_ID_CHECK)) $criteria->add(PUserPeer::NB_ID_CHECK, $this->nb_id_check);
        if ($this->isColumnModified(PUserPeer::ONLINE)) $criteria->add(PUserPeer::ONLINE, $this->online);
        if ($this->isColumnModified(PUserPeer::HOMEPAGE)) $criteria->add(PUserPeer::HOMEPAGE, $this->homepage);
        if ($this->isColumnModified(PUserPeer::BANNED)) $criteria->add(PUserPeer::BANNED, $this->banned);
        if ($this->isColumnModified(PUserPeer::BANNED_NB_DAYS_LEFT)) $criteria->add(PUserPeer::BANNED_NB_DAYS_LEFT, $this->banned_nb_days_left);
        if ($this->isColumnModified(PUserPeer::BANNED_NB_TOTAL)) $criteria->add(PUserPeer::BANNED_NB_TOTAL, $this->banned_nb_total);
        if ($this->isColumnModified(PUserPeer::ABUSE_LEVEL)) $criteria->add(PUserPeer::ABUSE_LEVEL, $this->abuse_level);
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
        $copyObj->setOnline($this->getOnline());
        $copyObj->setHomepage($this->getHomepage());
        $copyObj->setBanned($this->getBanned());
        $copyObj->setBannedNbDaysLeft($this->getBannedNbDaysLeft());
        $copyObj->setBannedNbTotal($this->getBannedNbTotal());
        $copyObj->setAbuseLevel($this->getAbuseLevel());
        $copyObj->setCreatedAt($this->getCreatedAt());
        $copyObj->setUpdatedAt($this->getUpdatedAt());
        $copyObj->setSlug($this->getSlug());

        if ($deepCopy && !$this->startCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);
            // store object hash to prevent cycle
            $this->startCopy = true;

            foreach ($this->getPUsers() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPUser($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPOwners() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPOwner($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPEOperations() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPEOperation($relObj->copy($deepCopy));
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

            foreach ($this->getPuBookmarkDdPUsers() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPuBookmarkDdPUser($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPuBookmarkDrPUsers() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPuBookmarkDrPUser($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPuTrackDdPUsers() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPuTrackDdPUser($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPuTrackDrPUsers() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPuTrackDrPUser($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPUBadges() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPUBadge($relObj->copy($deepCopy));
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

            foreach ($this->getPUAffinityQOPUsers() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPUAffinityQOPUser($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPUCurrentQOPUsers() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPUCurrentQOPUser($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPUNotificationPUsers() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPUNotificationPUser($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPUSubscribePNEs() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPUSubscribePNE($relObj->copy($deepCopy));
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

            foreach ($this->getPDDComments() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPDDComment($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPDRComments() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPDRComment($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPMUserModerateds() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPMUserModerated($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPMUserMessages() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPMUserMessage($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPMUserHistorics() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPMUserHistoric($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPMDebateHistorics() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPMDebateHistoric($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPMReactionHistorics() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPMReactionHistoric($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPMDCommentHistorics() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPMDCommentHistoric($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPMRCommentHistorics() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPMRCommentHistoric($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPMAskForUpdates() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPMAskForUpdate($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPMAbuseReportings() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPMAbuseReporting($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPMAppExceptions() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPMAppException($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPMEmailings() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPMEmailing($relObj->copy($deepCopy));
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

            foreach ($this->getPUTrackUsRelatedByPUserIdSource() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPUTrackURelatedByPUserIdSource($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPUTrackUsRelatedByPUserIdDest() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPUTrackURelatedByPUserIdDest($relObj->copy($deepCopy));
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
     * Declares an association between this object and a PLCity object.
     *
     * @param                  PLCity $v
     * @return PUser The current object (for fluent API support)
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
            $v->addPUser($this);
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
                $this->aPLCity->addPUsers($this);
             */
        }

        return $this->aPLCity;
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
        if ('PUser' == $relationName) {
            $this->initPUsers();
        }
        if ('POwner' == $relationName) {
            $this->initPOwners();
        }
        if ('PEOperation' == $relationName) {
            $this->initPEOperations();
        }
        if ('POrder' == $relationName) {
            $this->initPOrders();
        }
        if ('PuFollowDdPUser' == $relationName) {
            $this->initPuFollowDdPUsers();
        }
        if ('PuBookmarkDdPUser' == $relationName) {
            $this->initPuBookmarkDdPUsers();
        }
        if ('PuBookmarkDrPUser' == $relationName) {
            $this->initPuBookmarkDrPUsers();
        }
        if ('PuTrackDdPUser' == $relationName) {
            $this->initPuTrackDdPUsers();
        }
        if ('PuTrackDrPUser' == $relationName) {
            $this->initPuTrackDrPUsers();
        }
        if ('PUBadge' == $relationName) {
            $this->initPUBadges();
        }
        if ('PUReputation' == $relationName) {
            $this->initPUReputations();
        }
        if ('PuTaggedTPUser' == $relationName) {
            $this->initPuTaggedTPUsers();
        }
        if ('PURoleQ' == $relationName) {
            $this->initPURoleQs();
        }
        if ('PUMandate' == $relationName) {
            $this->initPUMandates();
        }
        if ('PUAffinityQOPUser' == $relationName) {
            $this->initPUAffinityQOPUsers();
        }
        if ('PUCurrentQOPUser' == $relationName) {
            $this->initPUCurrentQOPUsers();
        }
        if ('PUNotificationPUser' == $relationName) {
            $this->initPUNotificationPUsers();
        }
        if ('PUSubscribePNE' == $relationName) {
            $this->initPUSubscribePNEs();
        }
        if ('PDDebate' == $relationName) {
            $this->initPDDebates();
        }
        if ('PDReaction' == $relationName) {
            $this->initPDReactions();
        }
        if ('PDDComment' == $relationName) {
            $this->initPDDComments();
        }
        if ('PDRComment' == $relationName) {
            $this->initPDRComments();
        }
        if ('PMUserModerated' == $relationName) {
            $this->initPMUserModerateds();
        }
        if ('PMUserMessage' == $relationName) {
            $this->initPMUserMessages();
        }
        if ('PMUserHistoric' == $relationName) {
            $this->initPMUserHistorics();
        }
        if ('PMDebateHistoric' == $relationName) {
            $this->initPMDebateHistorics();
        }
        if ('PMReactionHistoric' == $relationName) {
            $this->initPMReactionHistorics();
        }
        if ('PMDCommentHistoric' == $relationName) {
            $this->initPMDCommentHistorics();
        }
        if ('PMRCommentHistoric' == $relationName) {
            $this->initPMRCommentHistorics();
        }
        if ('PMAskForUpdate' == $relationName) {
            $this->initPMAskForUpdates();
        }
        if ('PMAbuseReporting' == $relationName) {
            $this->initPMAbuseReportings();
        }
        if ('PMAppException' == $relationName) {
            $this->initPMAppExceptions();
        }
        if ('PMEmailing' == $relationName) {
            $this->initPMEmailings();
        }
        if ('PUFollowURelatedByPUserId' == $relationName) {
            $this->initPUFollowUsRelatedByPUserId();
        }
        if ('PUFollowURelatedByPUserFollowerId' == $relationName) {
            $this->initPUFollowUsRelatedByPUserFollowerId();
        }
        if ('PUTrackURelatedByPUserIdSource' == $relationName) {
            $this->initPUTrackUsRelatedByPUserIdSource();
        }
        if ('PUTrackURelatedByPUserIdDest' == $relationName) {
            $this->initPUTrackUsRelatedByPUserIdDest();
        }
    }

    /**
     * Clears out the collPUsers collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return PUser The current object (for fluent API support)
     * @see        addPUsers()
     */
    public function clearPUsers()
    {
        $this->collPUsers = null; // important to set this to null since that means it is uninitialized
        $this->collPUsersPartial = null;

        return $this;
    }

    /**
     * reset is the collPUsers collection loaded partially
     *
     * @return void
     */
    public function resetPartialPUsers($v = true)
    {
        $this->collPUsersPartial = $v;
    }

    /**
     * Initializes the collPUsers collection.
     *
     * By default this just sets the collPUsers collection to an empty array (like clearcollPUsers());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPUsers($overrideExisting = true)
    {
        if (null !== $this->collPUsers && !$overrideExisting) {
            return;
        }
        $this->collPUsers = new PropelObjectCollection();
        $this->collPUsers->setModel('PTag');
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
    public function getPUsers($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collPUsersPartial && !$this->isNew();
        if (null === $this->collPUsers || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPUsers) {
                // return empty collection
                $this->initPUsers();
            } else {
                $collPUsers = PTagQuery::create(null, $criteria)
                    ->filterByPUser($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collPUsersPartial && count($collPUsers)) {
                      $this->initPUsers(false);

                      foreach ($collPUsers as $obj) {
                        if (false == $this->collPUsers->contains($obj)) {
                          $this->collPUsers->append($obj);
                        }
                      }

                      $this->collPUsersPartial = true;
                    }

                    $collPUsers->getInternalIterator()->rewind();

                    return $collPUsers;
                }

                if ($partial && $this->collPUsers) {
                    foreach ($this->collPUsers as $obj) {
                        if ($obj->isNew()) {
                            $collPUsers[] = $obj;
                        }
                    }
                }

                $this->collPUsers = $collPUsers;
                $this->collPUsersPartial = false;
            }
        }

        return $this->collPUsers;
    }

    /**
     * Sets a collection of PUser objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $pUsers A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return PUser The current object (for fluent API support)
     */
    public function setPUsers(PropelCollection $pUsers, PropelPDO $con = null)
    {
        $pUsersToDelete = $this->getPUsers(new Criteria(), $con)->diff($pUsers);


        $this->pUsersScheduledForDeletion = $pUsersToDelete;

        foreach ($pUsersToDelete as $pUserRemoved) {
            $pUserRemoved->setPUser(null);
        }

        $this->collPUsers = null;
        foreach ($pUsers as $pUser) {
            $this->addPUser($pUser);
        }

        $this->collPUsers = $pUsers;
        $this->collPUsersPartial = false;

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
    public function countPUsers(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collPUsersPartial && !$this->isNew();
        if (null === $this->collPUsers || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPUsers) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPUsers());
            }
            $query = PTagQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPUser($this)
                ->count($con);
        }

        return count($this->collPUsers);
    }

    /**
     * Method called to associate a PTag object to this object
     * through the PTag foreign key attribute.
     *
     * @param    PTag $l PTag
     * @return PUser The current object (for fluent API support)
     */
    public function addPUser(PTag $l)
    {
        if ($this->collPUsers === null) {
            $this->initPUsers();
            $this->collPUsersPartial = true;
        }

        if (!in_array($l, $this->collPUsers->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddPUser($l);

            if ($this->pUsersScheduledForDeletion and $this->pUsersScheduledForDeletion->contains($l)) {
                $this->pUsersScheduledForDeletion->remove($this->pUsersScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	PUser $pUser The pUser object to add.
     */
    protected function doAddPUser($pUser)
    {
        $this->collPUsers[]= $pUser;
        $pUser->setPUser($this);
    }

    /**
     * @param	PUser $pUser The pUser object to remove.
     * @return PUser The current object (for fluent API support)
     */
    public function removePUser($pUser)
    {
        if ($this->getPUsers()->contains($pUser)) {
            $this->collPUsers->remove($this->collPUsers->search($pUser));
            if (null === $this->pUsersScheduledForDeletion) {
                $this->pUsersScheduledForDeletion = clone $this->collPUsers;
                $this->pUsersScheduledForDeletion->clear();
            }
            $this->pUsersScheduledForDeletion[]= $pUser;
            $pUser->setPUser(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this PUser is new, it will return
     * an empty collection; or if this PUser has previously
     * been saved, it will retrieve related PUsers from storage.
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
    public function getPUsersJoinPTTagType($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PTagQuery::create(null, $criteria);
        $query->joinWith('PTTagType', $join_behavior);

        return $this->getPUsers($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this PUser is new, it will return
     * an empty collection; or if this PUser has previously
     * been saved, it will retrieve related PUsers from storage.
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
    public function getPUsersJoinPTagRelatedByPTParentId($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PTagQuery::create(null, $criteria);
        $query->joinWith('PTagRelatedByPTParentId', $join_behavior);

        return $this->getPUsers($query, $con);
    }

    /**
     * Clears out the collPOwners collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return PUser The current object (for fluent API support)
     * @see        addPOwners()
     */
    public function clearPOwners()
    {
        $this->collPOwners = null; // important to set this to null since that means it is uninitialized
        $this->collPOwnersPartial = null;

        return $this;
    }

    /**
     * reset is the collPOwners collection loaded partially
     *
     * @return void
     */
    public function resetPartialPOwners($v = true)
    {
        $this->collPOwnersPartial = $v;
    }

    /**
     * Initializes the collPOwners collection.
     *
     * By default this just sets the collPOwners collection to an empty array (like clearcollPOwners());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPOwners($overrideExisting = true)
    {
        if (null !== $this->collPOwners && !$overrideExisting) {
            return;
        }
        $this->collPOwners = new PropelObjectCollection();
        $this->collPOwners->setModel('PTag');
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
    public function getPOwners($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collPOwnersPartial && !$this->isNew();
        if (null === $this->collPOwners || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPOwners) {
                // return empty collection
                $this->initPOwners();
            } else {
                $collPOwners = PTagQuery::create(null, $criteria)
                    ->filterByPOwner($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collPOwnersPartial && count($collPOwners)) {
                      $this->initPOwners(false);

                      foreach ($collPOwners as $obj) {
                        if (false == $this->collPOwners->contains($obj)) {
                          $this->collPOwners->append($obj);
                        }
                      }

                      $this->collPOwnersPartial = true;
                    }

                    $collPOwners->getInternalIterator()->rewind();

                    return $collPOwners;
                }

                if ($partial && $this->collPOwners) {
                    foreach ($this->collPOwners as $obj) {
                        if ($obj->isNew()) {
                            $collPOwners[] = $obj;
                        }
                    }
                }

                $this->collPOwners = $collPOwners;
                $this->collPOwnersPartial = false;
            }
        }

        return $this->collPOwners;
    }

    /**
     * Sets a collection of POwner objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $pOwners A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return PUser The current object (for fluent API support)
     */
    public function setPOwners(PropelCollection $pOwners, PropelPDO $con = null)
    {
        $pOwnersToDelete = $this->getPOwners(new Criteria(), $con)->diff($pOwners);


        $this->pOwnersScheduledForDeletion = $pOwnersToDelete;

        foreach ($pOwnersToDelete as $pOwnerRemoved) {
            $pOwnerRemoved->setPOwner(null);
        }

        $this->collPOwners = null;
        foreach ($pOwners as $pOwner) {
            $this->addPOwner($pOwner);
        }

        $this->collPOwners = $pOwners;
        $this->collPOwnersPartial = false;

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
    public function countPOwners(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collPOwnersPartial && !$this->isNew();
        if (null === $this->collPOwners || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPOwners) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPOwners());
            }
            $query = PTagQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPOwner($this)
                ->count($con);
        }

        return count($this->collPOwners);
    }

    /**
     * Method called to associate a PTag object to this object
     * through the PTag foreign key attribute.
     *
     * @param    PTag $l PTag
     * @return PUser The current object (for fluent API support)
     */
    public function addPOwner(PTag $l)
    {
        if ($this->collPOwners === null) {
            $this->initPOwners();
            $this->collPOwnersPartial = true;
        }

        if (!in_array($l, $this->collPOwners->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddPOwner($l);

            if ($this->pOwnersScheduledForDeletion and $this->pOwnersScheduledForDeletion->contains($l)) {
                $this->pOwnersScheduledForDeletion->remove($this->pOwnersScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	POwner $pOwner The pOwner object to add.
     */
    protected function doAddPOwner($pOwner)
    {
        $this->collPOwners[]= $pOwner;
        $pOwner->setPOwner($this);
    }

    /**
     * @param	POwner $pOwner The pOwner object to remove.
     * @return PUser The current object (for fluent API support)
     */
    public function removePOwner($pOwner)
    {
        if ($this->getPOwners()->contains($pOwner)) {
            $this->collPOwners->remove($this->collPOwners->search($pOwner));
            if (null === $this->pOwnersScheduledForDeletion) {
                $this->pOwnersScheduledForDeletion = clone $this->collPOwners;
                $this->pOwnersScheduledForDeletion->clear();
            }
            $this->pOwnersScheduledForDeletion[]= $pOwner;
            $pOwner->setPOwner(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this PUser is new, it will return
     * an empty collection; or if this PUser has previously
     * been saved, it will retrieve related POwners from storage.
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
    public function getPOwnersJoinPTTagType($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PTagQuery::create(null, $criteria);
        $query->joinWith('PTTagType', $join_behavior);

        return $this->getPOwners($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this PUser is new, it will return
     * an empty collection; or if this PUser has previously
     * been saved, it will retrieve related POwners from storage.
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
    public function getPOwnersJoinPTagRelatedByPTParentId($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PTagQuery::create(null, $criteria);
        $query->joinWith('PTagRelatedByPTParentId', $join_behavior);

        return $this->getPOwners($query, $con);
    }

    /**
     * Clears out the collPEOperations collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return PUser The current object (for fluent API support)
     * @see        addPEOperations()
     */
    public function clearPEOperations()
    {
        $this->collPEOperations = null; // important to set this to null since that means it is uninitialized
        $this->collPEOperationsPartial = null;

        return $this;
    }

    /**
     * reset is the collPEOperations collection loaded partially
     *
     * @return void
     */
    public function resetPartialPEOperations($v = true)
    {
        $this->collPEOperationsPartial = $v;
    }

    /**
     * Initializes the collPEOperations collection.
     *
     * By default this just sets the collPEOperations collection to an empty array (like clearcollPEOperations());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPEOperations($overrideExisting = true)
    {
        if (null !== $this->collPEOperations && !$overrideExisting) {
            return;
        }
        $this->collPEOperations = new PropelObjectCollection();
        $this->collPEOperations->setModel('PEOperation');
    }

    /**
     * Gets an array of PEOperation objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this PUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|PEOperation[] List of PEOperation objects
     * @throws PropelException
     */
    public function getPEOperations($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collPEOperationsPartial && !$this->isNew();
        if (null === $this->collPEOperations || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPEOperations) {
                // return empty collection
                $this->initPEOperations();
            } else {
                $collPEOperations = PEOperationQuery::create(null, $criteria)
                    ->filterByPUser($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collPEOperationsPartial && count($collPEOperations)) {
                      $this->initPEOperations(false);

                      foreach ($collPEOperations as $obj) {
                        if (false == $this->collPEOperations->contains($obj)) {
                          $this->collPEOperations->append($obj);
                        }
                      }

                      $this->collPEOperationsPartial = true;
                    }

                    $collPEOperations->getInternalIterator()->rewind();

                    return $collPEOperations;
                }

                if ($partial && $this->collPEOperations) {
                    foreach ($this->collPEOperations as $obj) {
                        if ($obj->isNew()) {
                            $collPEOperations[] = $obj;
                        }
                    }
                }

                $this->collPEOperations = $collPEOperations;
                $this->collPEOperationsPartial = false;
            }
        }

        return $this->collPEOperations;
    }

    /**
     * Sets a collection of PEOperation objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $pEOperations A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return PUser The current object (for fluent API support)
     */
    public function setPEOperations(PropelCollection $pEOperations, PropelPDO $con = null)
    {
        $pEOperationsToDelete = $this->getPEOperations(new Criteria(), $con)->diff($pEOperations);


        $this->pEOperationsScheduledForDeletion = $pEOperationsToDelete;

        foreach ($pEOperationsToDelete as $pEOperationRemoved) {
            $pEOperationRemoved->setPUser(null);
        }

        $this->collPEOperations = null;
        foreach ($pEOperations as $pEOperation) {
            $this->addPEOperation($pEOperation);
        }

        $this->collPEOperations = $pEOperations;
        $this->collPEOperationsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related PEOperation objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related PEOperation objects.
     * @throws PropelException
     */
    public function countPEOperations(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collPEOperationsPartial && !$this->isNew();
        if (null === $this->collPEOperations || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPEOperations) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPEOperations());
            }
            $query = PEOperationQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPUser($this)
                ->count($con);
        }

        return count($this->collPEOperations);
    }

    /**
     * Method called to associate a PEOperation object to this object
     * through the PEOperation foreign key attribute.
     *
     * @param    PEOperation $l PEOperation
     * @return PUser The current object (for fluent API support)
     */
    public function addPEOperation(PEOperation $l)
    {
        if ($this->collPEOperations === null) {
            $this->initPEOperations();
            $this->collPEOperationsPartial = true;
        }

        if (!in_array($l, $this->collPEOperations->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddPEOperation($l);

            if ($this->pEOperationsScheduledForDeletion and $this->pEOperationsScheduledForDeletion->contains($l)) {
                $this->pEOperationsScheduledForDeletion->remove($this->pEOperationsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	PEOperation $pEOperation The pEOperation object to add.
     */
    protected function doAddPEOperation($pEOperation)
    {
        $this->collPEOperations[]= $pEOperation;
        $pEOperation->setPUser($this);
    }

    /**
     * @param	PEOperation $pEOperation The pEOperation object to remove.
     * @return PUser The current object (for fluent API support)
     */
    public function removePEOperation($pEOperation)
    {
        if ($this->getPEOperations()->contains($pEOperation)) {
            $this->collPEOperations->remove($this->collPEOperations->search($pEOperation));
            if (null === $this->pEOperationsScheduledForDeletion) {
                $this->pEOperationsScheduledForDeletion = clone $this->collPEOperations;
                $this->pEOperationsScheduledForDeletion->clear();
            }
            $this->pEOperationsScheduledForDeletion[]= $pEOperation;
            $pEOperation->setPUser(null);
        }

        return $this;
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
     * Clears out the collPuBookmarkDdPUsers collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return PUser The current object (for fluent API support)
     * @see        addPuBookmarkDdPUsers()
     */
    public function clearPuBookmarkDdPUsers()
    {
        $this->collPuBookmarkDdPUsers = null; // important to set this to null since that means it is uninitialized
        $this->collPuBookmarkDdPUsersPartial = null;

        return $this;
    }

    /**
     * reset is the collPuBookmarkDdPUsers collection loaded partially
     *
     * @return void
     */
    public function resetPartialPuBookmarkDdPUsers($v = true)
    {
        $this->collPuBookmarkDdPUsersPartial = $v;
    }

    /**
     * Initializes the collPuBookmarkDdPUsers collection.
     *
     * By default this just sets the collPuBookmarkDdPUsers collection to an empty array (like clearcollPuBookmarkDdPUsers());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPuBookmarkDdPUsers($overrideExisting = true)
    {
        if (null !== $this->collPuBookmarkDdPUsers && !$overrideExisting) {
            return;
        }
        $this->collPuBookmarkDdPUsers = new PropelObjectCollection();
        $this->collPuBookmarkDdPUsers->setModel('PUBookmarkDD');
    }

    /**
     * Gets an array of PUBookmarkDD objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this PUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|PUBookmarkDD[] List of PUBookmarkDD objects
     * @throws PropelException
     */
    public function getPuBookmarkDdPUsers($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collPuBookmarkDdPUsersPartial && !$this->isNew();
        if (null === $this->collPuBookmarkDdPUsers || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPuBookmarkDdPUsers) {
                // return empty collection
                $this->initPuBookmarkDdPUsers();
            } else {
                $collPuBookmarkDdPUsers = PUBookmarkDDQuery::create(null, $criteria)
                    ->filterByPuBookmarkDdPUser($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collPuBookmarkDdPUsersPartial && count($collPuBookmarkDdPUsers)) {
                      $this->initPuBookmarkDdPUsers(false);

                      foreach ($collPuBookmarkDdPUsers as $obj) {
                        if (false == $this->collPuBookmarkDdPUsers->contains($obj)) {
                          $this->collPuBookmarkDdPUsers->append($obj);
                        }
                      }

                      $this->collPuBookmarkDdPUsersPartial = true;
                    }

                    $collPuBookmarkDdPUsers->getInternalIterator()->rewind();

                    return $collPuBookmarkDdPUsers;
                }

                if ($partial && $this->collPuBookmarkDdPUsers) {
                    foreach ($this->collPuBookmarkDdPUsers as $obj) {
                        if ($obj->isNew()) {
                            $collPuBookmarkDdPUsers[] = $obj;
                        }
                    }
                }

                $this->collPuBookmarkDdPUsers = $collPuBookmarkDdPUsers;
                $this->collPuBookmarkDdPUsersPartial = false;
            }
        }

        return $this->collPuBookmarkDdPUsers;
    }

    /**
     * Sets a collection of PuBookmarkDdPUser objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $puBookmarkDdPUsers A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return PUser The current object (for fluent API support)
     */
    public function setPuBookmarkDdPUsers(PropelCollection $puBookmarkDdPUsers, PropelPDO $con = null)
    {
        $puBookmarkDdPUsersToDelete = $this->getPuBookmarkDdPUsers(new Criteria(), $con)->diff($puBookmarkDdPUsers);


        $this->puBookmarkDdPUsersScheduledForDeletion = $puBookmarkDdPUsersToDelete;

        foreach ($puBookmarkDdPUsersToDelete as $puBookmarkDdPUserRemoved) {
            $puBookmarkDdPUserRemoved->setPuBookmarkDdPUser(null);
        }

        $this->collPuBookmarkDdPUsers = null;
        foreach ($puBookmarkDdPUsers as $puBookmarkDdPUser) {
            $this->addPuBookmarkDdPUser($puBookmarkDdPUser);
        }

        $this->collPuBookmarkDdPUsers = $puBookmarkDdPUsers;
        $this->collPuBookmarkDdPUsersPartial = false;

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
    public function countPuBookmarkDdPUsers(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collPuBookmarkDdPUsersPartial && !$this->isNew();
        if (null === $this->collPuBookmarkDdPUsers || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPuBookmarkDdPUsers) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPuBookmarkDdPUsers());
            }
            $query = PUBookmarkDDQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPuBookmarkDdPUser($this)
                ->count($con);
        }

        return count($this->collPuBookmarkDdPUsers);
    }

    /**
     * Method called to associate a PUBookmarkDD object to this object
     * through the PUBookmarkDD foreign key attribute.
     *
     * @param    PUBookmarkDD $l PUBookmarkDD
     * @return PUser The current object (for fluent API support)
     */
    public function addPuBookmarkDdPUser(PUBookmarkDD $l)
    {
        if ($this->collPuBookmarkDdPUsers === null) {
            $this->initPuBookmarkDdPUsers();
            $this->collPuBookmarkDdPUsersPartial = true;
        }

        if (!in_array($l, $this->collPuBookmarkDdPUsers->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddPuBookmarkDdPUser($l);

            if ($this->puBookmarkDdPUsersScheduledForDeletion and $this->puBookmarkDdPUsersScheduledForDeletion->contains($l)) {
                $this->puBookmarkDdPUsersScheduledForDeletion->remove($this->puBookmarkDdPUsersScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	PuBookmarkDdPUser $puBookmarkDdPUser The puBookmarkDdPUser object to add.
     */
    protected function doAddPuBookmarkDdPUser($puBookmarkDdPUser)
    {
        $this->collPuBookmarkDdPUsers[]= $puBookmarkDdPUser;
        $puBookmarkDdPUser->setPuBookmarkDdPUser($this);
    }

    /**
     * @param	PuBookmarkDdPUser $puBookmarkDdPUser The puBookmarkDdPUser object to remove.
     * @return PUser The current object (for fluent API support)
     */
    public function removePuBookmarkDdPUser($puBookmarkDdPUser)
    {
        if ($this->getPuBookmarkDdPUsers()->contains($puBookmarkDdPUser)) {
            $this->collPuBookmarkDdPUsers->remove($this->collPuBookmarkDdPUsers->search($puBookmarkDdPUser));
            if (null === $this->puBookmarkDdPUsersScheduledForDeletion) {
                $this->puBookmarkDdPUsersScheduledForDeletion = clone $this->collPuBookmarkDdPUsers;
                $this->puBookmarkDdPUsersScheduledForDeletion->clear();
            }
            $this->puBookmarkDdPUsersScheduledForDeletion[]= clone $puBookmarkDdPUser;
            $puBookmarkDdPUser->setPuBookmarkDdPUser(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this PUser is new, it will return
     * an empty collection; or if this PUser has previously
     * been saved, it will retrieve related PuBookmarkDdPUsers from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in PUser.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|PUBookmarkDD[] List of PUBookmarkDD objects
     */
    public function getPuBookmarkDdPUsersJoinPuBookmarkDdPDDebate($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PUBookmarkDDQuery::create(null, $criteria);
        $query->joinWith('PuBookmarkDdPDDebate', $join_behavior);

        return $this->getPuBookmarkDdPUsers($query, $con);
    }

    /**
     * Clears out the collPuBookmarkDrPUsers collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return PUser The current object (for fluent API support)
     * @see        addPuBookmarkDrPUsers()
     */
    public function clearPuBookmarkDrPUsers()
    {
        $this->collPuBookmarkDrPUsers = null; // important to set this to null since that means it is uninitialized
        $this->collPuBookmarkDrPUsersPartial = null;

        return $this;
    }

    /**
     * reset is the collPuBookmarkDrPUsers collection loaded partially
     *
     * @return void
     */
    public function resetPartialPuBookmarkDrPUsers($v = true)
    {
        $this->collPuBookmarkDrPUsersPartial = $v;
    }

    /**
     * Initializes the collPuBookmarkDrPUsers collection.
     *
     * By default this just sets the collPuBookmarkDrPUsers collection to an empty array (like clearcollPuBookmarkDrPUsers());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPuBookmarkDrPUsers($overrideExisting = true)
    {
        if (null !== $this->collPuBookmarkDrPUsers && !$overrideExisting) {
            return;
        }
        $this->collPuBookmarkDrPUsers = new PropelObjectCollection();
        $this->collPuBookmarkDrPUsers->setModel('PUBookmarkDR');
    }

    /**
     * Gets an array of PUBookmarkDR objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this PUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|PUBookmarkDR[] List of PUBookmarkDR objects
     * @throws PropelException
     */
    public function getPuBookmarkDrPUsers($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collPuBookmarkDrPUsersPartial && !$this->isNew();
        if (null === $this->collPuBookmarkDrPUsers || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPuBookmarkDrPUsers) {
                // return empty collection
                $this->initPuBookmarkDrPUsers();
            } else {
                $collPuBookmarkDrPUsers = PUBookmarkDRQuery::create(null, $criteria)
                    ->filterByPuBookmarkDrPUser($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collPuBookmarkDrPUsersPartial && count($collPuBookmarkDrPUsers)) {
                      $this->initPuBookmarkDrPUsers(false);

                      foreach ($collPuBookmarkDrPUsers as $obj) {
                        if (false == $this->collPuBookmarkDrPUsers->contains($obj)) {
                          $this->collPuBookmarkDrPUsers->append($obj);
                        }
                      }

                      $this->collPuBookmarkDrPUsersPartial = true;
                    }

                    $collPuBookmarkDrPUsers->getInternalIterator()->rewind();

                    return $collPuBookmarkDrPUsers;
                }

                if ($partial && $this->collPuBookmarkDrPUsers) {
                    foreach ($this->collPuBookmarkDrPUsers as $obj) {
                        if ($obj->isNew()) {
                            $collPuBookmarkDrPUsers[] = $obj;
                        }
                    }
                }

                $this->collPuBookmarkDrPUsers = $collPuBookmarkDrPUsers;
                $this->collPuBookmarkDrPUsersPartial = false;
            }
        }

        return $this->collPuBookmarkDrPUsers;
    }

    /**
     * Sets a collection of PuBookmarkDrPUser objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $puBookmarkDrPUsers A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return PUser The current object (for fluent API support)
     */
    public function setPuBookmarkDrPUsers(PropelCollection $puBookmarkDrPUsers, PropelPDO $con = null)
    {
        $puBookmarkDrPUsersToDelete = $this->getPuBookmarkDrPUsers(new Criteria(), $con)->diff($puBookmarkDrPUsers);


        $this->puBookmarkDrPUsersScheduledForDeletion = $puBookmarkDrPUsersToDelete;

        foreach ($puBookmarkDrPUsersToDelete as $puBookmarkDrPUserRemoved) {
            $puBookmarkDrPUserRemoved->setPuBookmarkDrPUser(null);
        }

        $this->collPuBookmarkDrPUsers = null;
        foreach ($puBookmarkDrPUsers as $puBookmarkDrPUser) {
            $this->addPuBookmarkDrPUser($puBookmarkDrPUser);
        }

        $this->collPuBookmarkDrPUsers = $puBookmarkDrPUsers;
        $this->collPuBookmarkDrPUsersPartial = false;

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
    public function countPuBookmarkDrPUsers(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collPuBookmarkDrPUsersPartial && !$this->isNew();
        if (null === $this->collPuBookmarkDrPUsers || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPuBookmarkDrPUsers) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPuBookmarkDrPUsers());
            }
            $query = PUBookmarkDRQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPuBookmarkDrPUser($this)
                ->count($con);
        }

        return count($this->collPuBookmarkDrPUsers);
    }

    /**
     * Method called to associate a PUBookmarkDR object to this object
     * through the PUBookmarkDR foreign key attribute.
     *
     * @param    PUBookmarkDR $l PUBookmarkDR
     * @return PUser The current object (for fluent API support)
     */
    public function addPuBookmarkDrPUser(PUBookmarkDR $l)
    {
        if ($this->collPuBookmarkDrPUsers === null) {
            $this->initPuBookmarkDrPUsers();
            $this->collPuBookmarkDrPUsersPartial = true;
        }

        if (!in_array($l, $this->collPuBookmarkDrPUsers->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddPuBookmarkDrPUser($l);

            if ($this->puBookmarkDrPUsersScheduledForDeletion and $this->puBookmarkDrPUsersScheduledForDeletion->contains($l)) {
                $this->puBookmarkDrPUsersScheduledForDeletion->remove($this->puBookmarkDrPUsersScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	PuBookmarkDrPUser $puBookmarkDrPUser The puBookmarkDrPUser object to add.
     */
    protected function doAddPuBookmarkDrPUser($puBookmarkDrPUser)
    {
        $this->collPuBookmarkDrPUsers[]= $puBookmarkDrPUser;
        $puBookmarkDrPUser->setPuBookmarkDrPUser($this);
    }

    /**
     * @param	PuBookmarkDrPUser $puBookmarkDrPUser The puBookmarkDrPUser object to remove.
     * @return PUser The current object (for fluent API support)
     */
    public function removePuBookmarkDrPUser($puBookmarkDrPUser)
    {
        if ($this->getPuBookmarkDrPUsers()->contains($puBookmarkDrPUser)) {
            $this->collPuBookmarkDrPUsers->remove($this->collPuBookmarkDrPUsers->search($puBookmarkDrPUser));
            if (null === $this->puBookmarkDrPUsersScheduledForDeletion) {
                $this->puBookmarkDrPUsersScheduledForDeletion = clone $this->collPuBookmarkDrPUsers;
                $this->puBookmarkDrPUsersScheduledForDeletion->clear();
            }
            $this->puBookmarkDrPUsersScheduledForDeletion[]= clone $puBookmarkDrPUser;
            $puBookmarkDrPUser->setPuBookmarkDrPUser(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this PUser is new, it will return
     * an empty collection; or if this PUser has previously
     * been saved, it will retrieve related PuBookmarkDrPUsers from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in PUser.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|PUBookmarkDR[] List of PUBookmarkDR objects
     */
    public function getPuBookmarkDrPUsersJoinPuBookmarkDrPDReaction($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PUBookmarkDRQuery::create(null, $criteria);
        $query->joinWith('PuBookmarkDrPDReaction', $join_behavior);

        return $this->getPuBookmarkDrPUsers($query, $con);
    }

    /**
     * Clears out the collPuTrackDdPUsers collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return PUser The current object (for fluent API support)
     * @see        addPuTrackDdPUsers()
     */
    public function clearPuTrackDdPUsers()
    {
        $this->collPuTrackDdPUsers = null; // important to set this to null since that means it is uninitialized
        $this->collPuTrackDdPUsersPartial = null;

        return $this;
    }

    /**
     * reset is the collPuTrackDdPUsers collection loaded partially
     *
     * @return void
     */
    public function resetPartialPuTrackDdPUsers($v = true)
    {
        $this->collPuTrackDdPUsersPartial = $v;
    }

    /**
     * Initializes the collPuTrackDdPUsers collection.
     *
     * By default this just sets the collPuTrackDdPUsers collection to an empty array (like clearcollPuTrackDdPUsers());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPuTrackDdPUsers($overrideExisting = true)
    {
        if (null !== $this->collPuTrackDdPUsers && !$overrideExisting) {
            return;
        }
        $this->collPuTrackDdPUsers = new PropelObjectCollection();
        $this->collPuTrackDdPUsers->setModel('PUTrackDD');
    }

    /**
     * Gets an array of PUTrackDD objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this PUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|PUTrackDD[] List of PUTrackDD objects
     * @throws PropelException
     */
    public function getPuTrackDdPUsers($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collPuTrackDdPUsersPartial && !$this->isNew();
        if (null === $this->collPuTrackDdPUsers || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPuTrackDdPUsers) {
                // return empty collection
                $this->initPuTrackDdPUsers();
            } else {
                $collPuTrackDdPUsers = PUTrackDDQuery::create(null, $criteria)
                    ->filterByPuTrackDdPUser($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collPuTrackDdPUsersPartial && count($collPuTrackDdPUsers)) {
                      $this->initPuTrackDdPUsers(false);

                      foreach ($collPuTrackDdPUsers as $obj) {
                        if (false == $this->collPuTrackDdPUsers->contains($obj)) {
                          $this->collPuTrackDdPUsers->append($obj);
                        }
                      }

                      $this->collPuTrackDdPUsersPartial = true;
                    }

                    $collPuTrackDdPUsers->getInternalIterator()->rewind();

                    return $collPuTrackDdPUsers;
                }

                if ($partial && $this->collPuTrackDdPUsers) {
                    foreach ($this->collPuTrackDdPUsers as $obj) {
                        if ($obj->isNew()) {
                            $collPuTrackDdPUsers[] = $obj;
                        }
                    }
                }

                $this->collPuTrackDdPUsers = $collPuTrackDdPUsers;
                $this->collPuTrackDdPUsersPartial = false;
            }
        }

        return $this->collPuTrackDdPUsers;
    }

    /**
     * Sets a collection of PuTrackDdPUser objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $puTrackDdPUsers A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return PUser The current object (for fluent API support)
     */
    public function setPuTrackDdPUsers(PropelCollection $puTrackDdPUsers, PropelPDO $con = null)
    {
        $puTrackDdPUsersToDelete = $this->getPuTrackDdPUsers(new Criteria(), $con)->diff($puTrackDdPUsers);


        $this->puTrackDdPUsersScheduledForDeletion = $puTrackDdPUsersToDelete;

        foreach ($puTrackDdPUsersToDelete as $puTrackDdPUserRemoved) {
            $puTrackDdPUserRemoved->setPuTrackDdPUser(null);
        }

        $this->collPuTrackDdPUsers = null;
        foreach ($puTrackDdPUsers as $puTrackDdPUser) {
            $this->addPuTrackDdPUser($puTrackDdPUser);
        }

        $this->collPuTrackDdPUsers = $puTrackDdPUsers;
        $this->collPuTrackDdPUsersPartial = false;

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
    public function countPuTrackDdPUsers(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collPuTrackDdPUsersPartial && !$this->isNew();
        if (null === $this->collPuTrackDdPUsers || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPuTrackDdPUsers) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPuTrackDdPUsers());
            }
            $query = PUTrackDDQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPuTrackDdPUser($this)
                ->count($con);
        }

        return count($this->collPuTrackDdPUsers);
    }

    /**
     * Method called to associate a PUTrackDD object to this object
     * through the PUTrackDD foreign key attribute.
     *
     * @param    PUTrackDD $l PUTrackDD
     * @return PUser The current object (for fluent API support)
     */
    public function addPuTrackDdPUser(PUTrackDD $l)
    {
        if ($this->collPuTrackDdPUsers === null) {
            $this->initPuTrackDdPUsers();
            $this->collPuTrackDdPUsersPartial = true;
        }

        if (!in_array($l, $this->collPuTrackDdPUsers->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddPuTrackDdPUser($l);

            if ($this->puTrackDdPUsersScheduledForDeletion and $this->puTrackDdPUsersScheduledForDeletion->contains($l)) {
                $this->puTrackDdPUsersScheduledForDeletion->remove($this->puTrackDdPUsersScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	PuTrackDdPUser $puTrackDdPUser The puTrackDdPUser object to add.
     */
    protected function doAddPuTrackDdPUser($puTrackDdPUser)
    {
        $this->collPuTrackDdPUsers[]= $puTrackDdPUser;
        $puTrackDdPUser->setPuTrackDdPUser($this);
    }

    /**
     * @param	PuTrackDdPUser $puTrackDdPUser The puTrackDdPUser object to remove.
     * @return PUser The current object (for fluent API support)
     */
    public function removePuTrackDdPUser($puTrackDdPUser)
    {
        if ($this->getPuTrackDdPUsers()->contains($puTrackDdPUser)) {
            $this->collPuTrackDdPUsers->remove($this->collPuTrackDdPUsers->search($puTrackDdPUser));
            if (null === $this->puTrackDdPUsersScheduledForDeletion) {
                $this->puTrackDdPUsersScheduledForDeletion = clone $this->collPuTrackDdPUsers;
                $this->puTrackDdPUsersScheduledForDeletion->clear();
            }
            $this->puTrackDdPUsersScheduledForDeletion[]= clone $puTrackDdPUser;
            $puTrackDdPUser->setPuTrackDdPUser(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this PUser is new, it will return
     * an empty collection; or if this PUser has previously
     * been saved, it will retrieve related PuTrackDdPUsers from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in PUser.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|PUTrackDD[] List of PUTrackDD objects
     */
    public function getPuTrackDdPUsersJoinPuTrackDdPDDebate($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PUTrackDDQuery::create(null, $criteria);
        $query->joinWith('PuTrackDdPDDebate', $join_behavior);

        return $this->getPuTrackDdPUsers($query, $con);
    }

    /**
     * Clears out the collPuTrackDrPUsers collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return PUser The current object (for fluent API support)
     * @see        addPuTrackDrPUsers()
     */
    public function clearPuTrackDrPUsers()
    {
        $this->collPuTrackDrPUsers = null; // important to set this to null since that means it is uninitialized
        $this->collPuTrackDrPUsersPartial = null;

        return $this;
    }

    /**
     * reset is the collPuTrackDrPUsers collection loaded partially
     *
     * @return void
     */
    public function resetPartialPuTrackDrPUsers($v = true)
    {
        $this->collPuTrackDrPUsersPartial = $v;
    }

    /**
     * Initializes the collPuTrackDrPUsers collection.
     *
     * By default this just sets the collPuTrackDrPUsers collection to an empty array (like clearcollPuTrackDrPUsers());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPuTrackDrPUsers($overrideExisting = true)
    {
        if (null !== $this->collPuTrackDrPUsers && !$overrideExisting) {
            return;
        }
        $this->collPuTrackDrPUsers = new PropelObjectCollection();
        $this->collPuTrackDrPUsers->setModel('PUTrackDR');
    }

    /**
     * Gets an array of PUTrackDR objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this PUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|PUTrackDR[] List of PUTrackDR objects
     * @throws PropelException
     */
    public function getPuTrackDrPUsers($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collPuTrackDrPUsersPartial && !$this->isNew();
        if (null === $this->collPuTrackDrPUsers || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPuTrackDrPUsers) {
                // return empty collection
                $this->initPuTrackDrPUsers();
            } else {
                $collPuTrackDrPUsers = PUTrackDRQuery::create(null, $criteria)
                    ->filterByPuTrackDrPUser($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collPuTrackDrPUsersPartial && count($collPuTrackDrPUsers)) {
                      $this->initPuTrackDrPUsers(false);

                      foreach ($collPuTrackDrPUsers as $obj) {
                        if (false == $this->collPuTrackDrPUsers->contains($obj)) {
                          $this->collPuTrackDrPUsers->append($obj);
                        }
                      }

                      $this->collPuTrackDrPUsersPartial = true;
                    }

                    $collPuTrackDrPUsers->getInternalIterator()->rewind();

                    return $collPuTrackDrPUsers;
                }

                if ($partial && $this->collPuTrackDrPUsers) {
                    foreach ($this->collPuTrackDrPUsers as $obj) {
                        if ($obj->isNew()) {
                            $collPuTrackDrPUsers[] = $obj;
                        }
                    }
                }

                $this->collPuTrackDrPUsers = $collPuTrackDrPUsers;
                $this->collPuTrackDrPUsersPartial = false;
            }
        }

        return $this->collPuTrackDrPUsers;
    }

    /**
     * Sets a collection of PuTrackDrPUser objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $puTrackDrPUsers A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return PUser The current object (for fluent API support)
     */
    public function setPuTrackDrPUsers(PropelCollection $puTrackDrPUsers, PropelPDO $con = null)
    {
        $puTrackDrPUsersToDelete = $this->getPuTrackDrPUsers(new Criteria(), $con)->diff($puTrackDrPUsers);


        $this->puTrackDrPUsersScheduledForDeletion = $puTrackDrPUsersToDelete;

        foreach ($puTrackDrPUsersToDelete as $puTrackDrPUserRemoved) {
            $puTrackDrPUserRemoved->setPuTrackDrPUser(null);
        }

        $this->collPuTrackDrPUsers = null;
        foreach ($puTrackDrPUsers as $puTrackDrPUser) {
            $this->addPuTrackDrPUser($puTrackDrPUser);
        }

        $this->collPuTrackDrPUsers = $puTrackDrPUsers;
        $this->collPuTrackDrPUsersPartial = false;

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
    public function countPuTrackDrPUsers(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collPuTrackDrPUsersPartial && !$this->isNew();
        if (null === $this->collPuTrackDrPUsers || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPuTrackDrPUsers) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPuTrackDrPUsers());
            }
            $query = PUTrackDRQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPuTrackDrPUser($this)
                ->count($con);
        }

        return count($this->collPuTrackDrPUsers);
    }

    /**
     * Method called to associate a PUTrackDR object to this object
     * through the PUTrackDR foreign key attribute.
     *
     * @param    PUTrackDR $l PUTrackDR
     * @return PUser The current object (for fluent API support)
     */
    public function addPuTrackDrPUser(PUTrackDR $l)
    {
        if ($this->collPuTrackDrPUsers === null) {
            $this->initPuTrackDrPUsers();
            $this->collPuTrackDrPUsersPartial = true;
        }

        if (!in_array($l, $this->collPuTrackDrPUsers->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddPuTrackDrPUser($l);

            if ($this->puTrackDrPUsersScheduledForDeletion and $this->puTrackDrPUsersScheduledForDeletion->contains($l)) {
                $this->puTrackDrPUsersScheduledForDeletion->remove($this->puTrackDrPUsersScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	PuTrackDrPUser $puTrackDrPUser The puTrackDrPUser object to add.
     */
    protected function doAddPuTrackDrPUser($puTrackDrPUser)
    {
        $this->collPuTrackDrPUsers[]= $puTrackDrPUser;
        $puTrackDrPUser->setPuTrackDrPUser($this);
    }

    /**
     * @param	PuTrackDrPUser $puTrackDrPUser The puTrackDrPUser object to remove.
     * @return PUser The current object (for fluent API support)
     */
    public function removePuTrackDrPUser($puTrackDrPUser)
    {
        if ($this->getPuTrackDrPUsers()->contains($puTrackDrPUser)) {
            $this->collPuTrackDrPUsers->remove($this->collPuTrackDrPUsers->search($puTrackDrPUser));
            if (null === $this->puTrackDrPUsersScheduledForDeletion) {
                $this->puTrackDrPUsersScheduledForDeletion = clone $this->collPuTrackDrPUsers;
                $this->puTrackDrPUsersScheduledForDeletion->clear();
            }
            $this->puTrackDrPUsersScheduledForDeletion[]= clone $puTrackDrPUser;
            $puTrackDrPUser->setPuTrackDrPUser(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this PUser is new, it will return
     * an empty collection; or if this PUser has previously
     * been saved, it will retrieve related PuTrackDrPUsers from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in PUser.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|PUTrackDR[] List of PUTrackDR objects
     */
    public function getPuTrackDrPUsersJoinPuTrackDrPDReaction($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PUTrackDRQuery::create(null, $criteria);
        $query->joinWith('PuTrackDrPDReaction', $join_behavior);

        return $this->getPuTrackDrPUsers($query, $con);
    }

    /**
     * Clears out the collPUBadges collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return PUser The current object (for fluent API support)
     * @see        addPUBadges()
     */
    public function clearPUBadges()
    {
        $this->collPUBadges = null; // important to set this to null since that means it is uninitialized
        $this->collPUBadgesPartial = null;

        return $this;
    }

    /**
     * reset is the collPUBadges collection loaded partially
     *
     * @return void
     */
    public function resetPartialPUBadges($v = true)
    {
        $this->collPUBadgesPartial = $v;
    }

    /**
     * Initializes the collPUBadges collection.
     *
     * By default this just sets the collPUBadges collection to an empty array (like clearcollPUBadges());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPUBadges($overrideExisting = true)
    {
        if (null !== $this->collPUBadges && !$overrideExisting) {
            return;
        }
        $this->collPUBadges = new PropelObjectCollection();
        $this->collPUBadges->setModel('PUBadge');
    }

    /**
     * Gets an array of PUBadge objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this PUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|PUBadge[] List of PUBadge objects
     * @throws PropelException
     */
    public function getPUBadges($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collPUBadgesPartial && !$this->isNew();
        if (null === $this->collPUBadges || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPUBadges) {
                // return empty collection
                $this->initPUBadges();
            } else {
                $collPUBadges = PUBadgeQuery::create(null, $criteria)
                    ->filterByPUser($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collPUBadgesPartial && count($collPUBadges)) {
                      $this->initPUBadges(false);

                      foreach ($collPUBadges as $obj) {
                        if (false == $this->collPUBadges->contains($obj)) {
                          $this->collPUBadges->append($obj);
                        }
                      }

                      $this->collPUBadgesPartial = true;
                    }

                    $collPUBadges->getInternalIterator()->rewind();

                    return $collPUBadges;
                }

                if ($partial && $this->collPUBadges) {
                    foreach ($this->collPUBadges as $obj) {
                        if ($obj->isNew()) {
                            $collPUBadges[] = $obj;
                        }
                    }
                }

                $this->collPUBadges = $collPUBadges;
                $this->collPUBadgesPartial = false;
            }
        }

        return $this->collPUBadges;
    }

    /**
     * Sets a collection of PUBadge objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $pUBadges A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return PUser The current object (for fluent API support)
     */
    public function setPUBadges(PropelCollection $pUBadges, PropelPDO $con = null)
    {
        $pUBadgesToDelete = $this->getPUBadges(new Criteria(), $con)->diff($pUBadges);


        $this->pUBadgesScheduledForDeletion = $pUBadgesToDelete;

        foreach ($pUBadgesToDelete as $pUBadgeRemoved) {
            $pUBadgeRemoved->setPUser(null);
        }

        $this->collPUBadges = null;
        foreach ($pUBadges as $pUBadge) {
            $this->addPUBadge($pUBadge);
        }

        $this->collPUBadges = $pUBadges;
        $this->collPUBadgesPartial = false;

        return $this;
    }

    /**
     * Returns the number of related PUBadge objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related PUBadge objects.
     * @throws PropelException
     */
    public function countPUBadges(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collPUBadgesPartial && !$this->isNew();
        if (null === $this->collPUBadges || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPUBadges) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPUBadges());
            }
            $query = PUBadgeQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPUser($this)
                ->count($con);
        }

        return count($this->collPUBadges);
    }

    /**
     * Method called to associate a PUBadge object to this object
     * through the PUBadge foreign key attribute.
     *
     * @param    PUBadge $l PUBadge
     * @return PUser The current object (for fluent API support)
     */
    public function addPUBadge(PUBadge $l)
    {
        if ($this->collPUBadges === null) {
            $this->initPUBadges();
            $this->collPUBadgesPartial = true;
        }

        if (!in_array($l, $this->collPUBadges->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddPUBadge($l);

            if ($this->pUBadgesScheduledForDeletion and $this->pUBadgesScheduledForDeletion->contains($l)) {
                $this->pUBadgesScheduledForDeletion->remove($this->pUBadgesScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	PUBadge $pUBadge The pUBadge object to add.
     */
    protected function doAddPUBadge($pUBadge)
    {
        $this->collPUBadges[]= $pUBadge;
        $pUBadge->setPUser($this);
    }

    /**
     * @param	PUBadge $pUBadge The pUBadge object to remove.
     * @return PUser The current object (for fluent API support)
     */
    public function removePUBadge($pUBadge)
    {
        if ($this->getPUBadges()->contains($pUBadge)) {
            $this->collPUBadges->remove($this->collPUBadges->search($pUBadge));
            if (null === $this->pUBadgesScheduledForDeletion) {
                $this->pUBadgesScheduledForDeletion = clone $this->collPUBadges;
                $this->pUBadgesScheduledForDeletion->clear();
            }
            $this->pUBadgesScheduledForDeletion[]= clone $pUBadge;
            $pUBadge->setPUser(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this PUser is new, it will return
     * an empty collection; or if this PUser has previously
     * been saved, it will retrieve related PUBadges from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in PUser.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|PUBadge[] List of PUBadge objects
     */
    public function getPUBadgesJoinPRBadge($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PUBadgeQuery::create(null, $criteria);
        $query->joinWith('PRBadge', $join_behavior);

        return $this->getPUBadges($query, $con);
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
     * Clears out the collPUAffinityQOPUsers collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return PUser The current object (for fluent API support)
     * @see        addPUAffinityQOPUsers()
     */
    public function clearPUAffinityQOPUsers()
    {
        $this->collPUAffinityQOPUsers = null; // important to set this to null since that means it is uninitialized
        $this->collPUAffinityQOPUsersPartial = null;

        return $this;
    }

    /**
     * reset is the collPUAffinityQOPUsers collection loaded partially
     *
     * @return void
     */
    public function resetPartialPUAffinityQOPUsers($v = true)
    {
        $this->collPUAffinityQOPUsersPartial = $v;
    }

    /**
     * Initializes the collPUAffinityQOPUsers collection.
     *
     * By default this just sets the collPUAffinityQOPUsers collection to an empty array (like clearcollPUAffinityQOPUsers());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPUAffinityQOPUsers($overrideExisting = true)
    {
        if (null !== $this->collPUAffinityQOPUsers && !$overrideExisting) {
            return;
        }
        $this->collPUAffinityQOPUsers = new PropelObjectCollection();
        $this->collPUAffinityQOPUsers->setModel('PUAffinityQO');
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
    public function getPUAffinityQOPUsers($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collPUAffinityQOPUsersPartial && !$this->isNew();
        if (null === $this->collPUAffinityQOPUsers || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPUAffinityQOPUsers) {
                // return empty collection
                $this->initPUAffinityQOPUsers();
            } else {
                $collPUAffinityQOPUsers = PUAffinityQOQuery::create(null, $criteria)
                    ->filterByPUAffinityQOPUser($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collPUAffinityQOPUsersPartial && count($collPUAffinityQOPUsers)) {
                      $this->initPUAffinityQOPUsers(false);

                      foreach ($collPUAffinityQOPUsers as $obj) {
                        if (false == $this->collPUAffinityQOPUsers->contains($obj)) {
                          $this->collPUAffinityQOPUsers->append($obj);
                        }
                      }

                      $this->collPUAffinityQOPUsersPartial = true;
                    }

                    $collPUAffinityQOPUsers->getInternalIterator()->rewind();

                    return $collPUAffinityQOPUsers;
                }

                if ($partial && $this->collPUAffinityQOPUsers) {
                    foreach ($this->collPUAffinityQOPUsers as $obj) {
                        if ($obj->isNew()) {
                            $collPUAffinityQOPUsers[] = $obj;
                        }
                    }
                }

                $this->collPUAffinityQOPUsers = $collPUAffinityQOPUsers;
                $this->collPUAffinityQOPUsersPartial = false;
            }
        }

        return $this->collPUAffinityQOPUsers;
    }

    /**
     * Sets a collection of PUAffinityQOPUser objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $pUAffinityQOPUsers A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return PUser The current object (for fluent API support)
     */
    public function setPUAffinityQOPUsers(PropelCollection $pUAffinityQOPUsers, PropelPDO $con = null)
    {
        $pUAffinityQOPUsersToDelete = $this->getPUAffinityQOPUsers(new Criteria(), $con)->diff($pUAffinityQOPUsers);


        $this->pUAffinityQOPUsersScheduledForDeletion = $pUAffinityQOPUsersToDelete;

        foreach ($pUAffinityQOPUsersToDelete as $pUAffinityQOPUserRemoved) {
            $pUAffinityQOPUserRemoved->setPUAffinityQOPUser(null);
        }

        $this->collPUAffinityQOPUsers = null;
        foreach ($pUAffinityQOPUsers as $pUAffinityQOPUser) {
            $this->addPUAffinityQOPUser($pUAffinityQOPUser);
        }

        $this->collPUAffinityQOPUsers = $pUAffinityQOPUsers;
        $this->collPUAffinityQOPUsersPartial = false;

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
    public function countPUAffinityQOPUsers(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collPUAffinityQOPUsersPartial && !$this->isNew();
        if (null === $this->collPUAffinityQOPUsers || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPUAffinityQOPUsers) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPUAffinityQOPUsers());
            }
            $query = PUAffinityQOQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPUAffinityQOPUser($this)
                ->count($con);
        }

        return count($this->collPUAffinityQOPUsers);
    }

    /**
     * Method called to associate a PUAffinityQO object to this object
     * through the PUAffinityQO foreign key attribute.
     *
     * @param    PUAffinityQO $l PUAffinityQO
     * @return PUser The current object (for fluent API support)
     */
    public function addPUAffinityQOPUser(PUAffinityQO $l)
    {
        if ($this->collPUAffinityQOPUsers === null) {
            $this->initPUAffinityQOPUsers();
            $this->collPUAffinityQOPUsersPartial = true;
        }

        if (!in_array($l, $this->collPUAffinityQOPUsers->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddPUAffinityQOPUser($l);

            if ($this->pUAffinityQOPUsersScheduledForDeletion and $this->pUAffinityQOPUsersScheduledForDeletion->contains($l)) {
                $this->pUAffinityQOPUsersScheduledForDeletion->remove($this->pUAffinityQOPUsersScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	PUAffinityQOPUser $pUAffinityQOPUser The pUAffinityQOPUser object to add.
     */
    protected function doAddPUAffinityQOPUser($pUAffinityQOPUser)
    {
        $this->collPUAffinityQOPUsers[]= $pUAffinityQOPUser;
        $pUAffinityQOPUser->setPUAffinityQOPUser($this);
    }

    /**
     * @param	PUAffinityQOPUser $pUAffinityQOPUser The pUAffinityQOPUser object to remove.
     * @return PUser The current object (for fluent API support)
     */
    public function removePUAffinityQOPUser($pUAffinityQOPUser)
    {
        if ($this->getPUAffinityQOPUsers()->contains($pUAffinityQOPUser)) {
            $this->collPUAffinityQOPUsers->remove($this->collPUAffinityQOPUsers->search($pUAffinityQOPUser));
            if (null === $this->pUAffinityQOPUsersScheduledForDeletion) {
                $this->pUAffinityQOPUsersScheduledForDeletion = clone $this->collPUAffinityQOPUsers;
                $this->pUAffinityQOPUsersScheduledForDeletion->clear();
            }
            $this->pUAffinityQOPUsersScheduledForDeletion[]= clone $pUAffinityQOPUser;
            $pUAffinityQOPUser->setPUAffinityQOPUser(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this PUser is new, it will return
     * an empty collection; or if this PUser has previously
     * been saved, it will retrieve related PUAffinityQOPUsers from storage.
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
    public function getPUAffinityQOPUsersJoinPUAffinityQOPQOrganization($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PUAffinityQOQuery::create(null, $criteria);
        $query->joinWith('PUAffinityQOPQOrganization', $join_behavior);

        return $this->getPUAffinityQOPUsers($query, $con);
    }

    /**
     * Clears out the collPUCurrentQOPUsers collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return PUser The current object (for fluent API support)
     * @see        addPUCurrentQOPUsers()
     */
    public function clearPUCurrentQOPUsers()
    {
        $this->collPUCurrentQOPUsers = null; // important to set this to null since that means it is uninitialized
        $this->collPUCurrentQOPUsersPartial = null;

        return $this;
    }

    /**
     * reset is the collPUCurrentQOPUsers collection loaded partially
     *
     * @return void
     */
    public function resetPartialPUCurrentQOPUsers($v = true)
    {
        $this->collPUCurrentQOPUsersPartial = $v;
    }

    /**
     * Initializes the collPUCurrentQOPUsers collection.
     *
     * By default this just sets the collPUCurrentQOPUsers collection to an empty array (like clearcollPUCurrentQOPUsers());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPUCurrentQOPUsers($overrideExisting = true)
    {
        if (null !== $this->collPUCurrentQOPUsers && !$overrideExisting) {
            return;
        }
        $this->collPUCurrentQOPUsers = new PropelObjectCollection();
        $this->collPUCurrentQOPUsers->setModel('PUCurrentQO');
    }

    /**
     * Gets an array of PUCurrentQO objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this PUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|PUCurrentQO[] List of PUCurrentQO objects
     * @throws PropelException
     */
    public function getPUCurrentQOPUsers($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collPUCurrentQOPUsersPartial && !$this->isNew();
        if (null === $this->collPUCurrentQOPUsers || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPUCurrentQOPUsers) {
                // return empty collection
                $this->initPUCurrentQOPUsers();
            } else {
                $collPUCurrentQOPUsers = PUCurrentQOQuery::create(null, $criteria)
                    ->filterByPUCurrentQOPUser($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collPUCurrentQOPUsersPartial && count($collPUCurrentQOPUsers)) {
                      $this->initPUCurrentQOPUsers(false);

                      foreach ($collPUCurrentQOPUsers as $obj) {
                        if (false == $this->collPUCurrentQOPUsers->contains($obj)) {
                          $this->collPUCurrentQOPUsers->append($obj);
                        }
                      }

                      $this->collPUCurrentQOPUsersPartial = true;
                    }

                    $collPUCurrentQOPUsers->getInternalIterator()->rewind();

                    return $collPUCurrentQOPUsers;
                }

                if ($partial && $this->collPUCurrentQOPUsers) {
                    foreach ($this->collPUCurrentQOPUsers as $obj) {
                        if ($obj->isNew()) {
                            $collPUCurrentQOPUsers[] = $obj;
                        }
                    }
                }

                $this->collPUCurrentQOPUsers = $collPUCurrentQOPUsers;
                $this->collPUCurrentQOPUsersPartial = false;
            }
        }

        return $this->collPUCurrentQOPUsers;
    }

    /**
     * Sets a collection of PUCurrentQOPUser objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $pUCurrentQOPUsers A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return PUser The current object (for fluent API support)
     */
    public function setPUCurrentQOPUsers(PropelCollection $pUCurrentQOPUsers, PropelPDO $con = null)
    {
        $pUCurrentQOPUsersToDelete = $this->getPUCurrentQOPUsers(new Criteria(), $con)->diff($pUCurrentQOPUsers);


        $this->pUCurrentQOPUsersScheduledForDeletion = $pUCurrentQOPUsersToDelete;

        foreach ($pUCurrentQOPUsersToDelete as $pUCurrentQOPUserRemoved) {
            $pUCurrentQOPUserRemoved->setPUCurrentQOPUser(null);
        }

        $this->collPUCurrentQOPUsers = null;
        foreach ($pUCurrentQOPUsers as $pUCurrentQOPUser) {
            $this->addPUCurrentQOPUser($pUCurrentQOPUser);
        }

        $this->collPUCurrentQOPUsers = $pUCurrentQOPUsers;
        $this->collPUCurrentQOPUsersPartial = false;

        return $this;
    }

    /**
     * Returns the number of related PUCurrentQO objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related PUCurrentQO objects.
     * @throws PropelException
     */
    public function countPUCurrentQOPUsers(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collPUCurrentQOPUsersPartial && !$this->isNew();
        if (null === $this->collPUCurrentQOPUsers || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPUCurrentQOPUsers) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPUCurrentQOPUsers());
            }
            $query = PUCurrentQOQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPUCurrentQOPUser($this)
                ->count($con);
        }

        return count($this->collPUCurrentQOPUsers);
    }

    /**
     * Method called to associate a PUCurrentQO object to this object
     * through the PUCurrentQO foreign key attribute.
     *
     * @param    PUCurrentQO $l PUCurrentQO
     * @return PUser The current object (for fluent API support)
     */
    public function addPUCurrentQOPUser(PUCurrentQO $l)
    {
        if ($this->collPUCurrentQOPUsers === null) {
            $this->initPUCurrentQOPUsers();
            $this->collPUCurrentQOPUsersPartial = true;
        }

        if (!in_array($l, $this->collPUCurrentQOPUsers->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddPUCurrentQOPUser($l);

            if ($this->pUCurrentQOPUsersScheduledForDeletion and $this->pUCurrentQOPUsersScheduledForDeletion->contains($l)) {
                $this->pUCurrentQOPUsersScheduledForDeletion->remove($this->pUCurrentQOPUsersScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	PUCurrentQOPUser $pUCurrentQOPUser The pUCurrentQOPUser object to add.
     */
    protected function doAddPUCurrentQOPUser($pUCurrentQOPUser)
    {
        $this->collPUCurrentQOPUsers[]= $pUCurrentQOPUser;
        $pUCurrentQOPUser->setPUCurrentQOPUser($this);
    }

    /**
     * @param	PUCurrentQOPUser $pUCurrentQOPUser The pUCurrentQOPUser object to remove.
     * @return PUser The current object (for fluent API support)
     */
    public function removePUCurrentQOPUser($pUCurrentQOPUser)
    {
        if ($this->getPUCurrentQOPUsers()->contains($pUCurrentQOPUser)) {
            $this->collPUCurrentQOPUsers->remove($this->collPUCurrentQOPUsers->search($pUCurrentQOPUser));
            if (null === $this->pUCurrentQOPUsersScheduledForDeletion) {
                $this->pUCurrentQOPUsersScheduledForDeletion = clone $this->collPUCurrentQOPUsers;
                $this->pUCurrentQOPUsersScheduledForDeletion->clear();
            }
            $this->pUCurrentQOPUsersScheduledForDeletion[]= clone $pUCurrentQOPUser;
            $pUCurrentQOPUser->setPUCurrentQOPUser(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this PUser is new, it will return
     * an empty collection; or if this PUser has previously
     * been saved, it will retrieve related PUCurrentQOPUsers from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in PUser.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|PUCurrentQO[] List of PUCurrentQO objects
     */
    public function getPUCurrentQOPUsersJoinPUCurrentQOPQOrganization($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PUCurrentQOQuery::create(null, $criteria);
        $query->joinWith('PUCurrentQOPQOrganization', $join_behavior);

        return $this->getPUCurrentQOPUsers($query, $con);
    }

    /**
     * Clears out the collPUNotificationPUsers collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return PUser The current object (for fluent API support)
     * @see        addPUNotificationPUsers()
     */
    public function clearPUNotificationPUsers()
    {
        $this->collPUNotificationPUsers = null; // important to set this to null since that means it is uninitialized
        $this->collPUNotificationPUsersPartial = null;

        return $this;
    }

    /**
     * reset is the collPUNotificationPUsers collection loaded partially
     *
     * @return void
     */
    public function resetPartialPUNotificationPUsers($v = true)
    {
        $this->collPUNotificationPUsersPartial = $v;
    }

    /**
     * Initializes the collPUNotificationPUsers collection.
     *
     * By default this just sets the collPUNotificationPUsers collection to an empty array (like clearcollPUNotificationPUsers());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPUNotificationPUsers($overrideExisting = true)
    {
        if (null !== $this->collPUNotificationPUsers && !$overrideExisting) {
            return;
        }
        $this->collPUNotificationPUsers = new PropelObjectCollection();
        $this->collPUNotificationPUsers->setModel('PUNotification');
    }

    /**
     * Gets an array of PUNotification objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this PUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|PUNotification[] List of PUNotification objects
     * @throws PropelException
     */
    public function getPUNotificationPUsers($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collPUNotificationPUsersPartial && !$this->isNew();
        if (null === $this->collPUNotificationPUsers || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPUNotificationPUsers) {
                // return empty collection
                $this->initPUNotificationPUsers();
            } else {
                $collPUNotificationPUsers = PUNotificationQuery::create(null, $criteria)
                    ->filterByPUNotificationPUser($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collPUNotificationPUsersPartial && count($collPUNotificationPUsers)) {
                      $this->initPUNotificationPUsers(false);

                      foreach ($collPUNotificationPUsers as $obj) {
                        if (false == $this->collPUNotificationPUsers->contains($obj)) {
                          $this->collPUNotificationPUsers->append($obj);
                        }
                      }

                      $this->collPUNotificationPUsersPartial = true;
                    }

                    $collPUNotificationPUsers->getInternalIterator()->rewind();

                    return $collPUNotificationPUsers;
                }

                if ($partial && $this->collPUNotificationPUsers) {
                    foreach ($this->collPUNotificationPUsers as $obj) {
                        if ($obj->isNew()) {
                            $collPUNotificationPUsers[] = $obj;
                        }
                    }
                }

                $this->collPUNotificationPUsers = $collPUNotificationPUsers;
                $this->collPUNotificationPUsersPartial = false;
            }
        }

        return $this->collPUNotificationPUsers;
    }

    /**
     * Sets a collection of PUNotificationPUser objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $pUNotificationPUsers A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return PUser The current object (for fluent API support)
     */
    public function setPUNotificationPUsers(PropelCollection $pUNotificationPUsers, PropelPDO $con = null)
    {
        $pUNotificationPUsersToDelete = $this->getPUNotificationPUsers(new Criteria(), $con)->diff($pUNotificationPUsers);


        $this->pUNotificationPUsersScheduledForDeletion = $pUNotificationPUsersToDelete;

        foreach ($pUNotificationPUsersToDelete as $pUNotificationPUserRemoved) {
            $pUNotificationPUserRemoved->setPUNotificationPUser(null);
        }

        $this->collPUNotificationPUsers = null;
        foreach ($pUNotificationPUsers as $pUNotificationPUser) {
            $this->addPUNotificationPUser($pUNotificationPUser);
        }

        $this->collPUNotificationPUsers = $pUNotificationPUsers;
        $this->collPUNotificationPUsersPartial = false;

        return $this;
    }

    /**
     * Returns the number of related PUNotification objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related PUNotification objects.
     * @throws PropelException
     */
    public function countPUNotificationPUsers(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collPUNotificationPUsersPartial && !$this->isNew();
        if (null === $this->collPUNotificationPUsers || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPUNotificationPUsers) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPUNotificationPUsers());
            }
            $query = PUNotificationQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPUNotificationPUser($this)
                ->count($con);
        }

        return count($this->collPUNotificationPUsers);
    }

    /**
     * Method called to associate a PUNotification object to this object
     * through the PUNotification foreign key attribute.
     *
     * @param    PUNotification $l PUNotification
     * @return PUser The current object (for fluent API support)
     */
    public function addPUNotificationPUser(PUNotification $l)
    {
        if ($this->collPUNotificationPUsers === null) {
            $this->initPUNotificationPUsers();
            $this->collPUNotificationPUsersPartial = true;
        }

        if (!in_array($l, $this->collPUNotificationPUsers->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddPUNotificationPUser($l);

            if ($this->pUNotificationPUsersScheduledForDeletion and $this->pUNotificationPUsersScheduledForDeletion->contains($l)) {
                $this->pUNotificationPUsersScheduledForDeletion->remove($this->pUNotificationPUsersScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	PUNotificationPUser $pUNotificationPUser The pUNotificationPUser object to add.
     */
    protected function doAddPUNotificationPUser($pUNotificationPUser)
    {
        $this->collPUNotificationPUsers[]= $pUNotificationPUser;
        $pUNotificationPUser->setPUNotificationPUser($this);
    }

    /**
     * @param	PUNotificationPUser $pUNotificationPUser The pUNotificationPUser object to remove.
     * @return PUser The current object (for fluent API support)
     */
    public function removePUNotificationPUser($pUNotificationPUser)
    {
        if ($this->getPUNotificationPUsers()->contains($pUNotificationPUser)) {
            $this->collPUNotificationPUsers->remove($this->collPUNotificationPUsers->search($pUNotificationPUser));
            if (null === $this->pUNotificationPUsersScheduledForDeletion) {
                $this->pUNotificationPUsersScheduledForDeletion = clone $this->collPUNotificationPUsers;
                $this->pUNotificationPUsersScheduledForDeletion->clear();
            }
            $this->pUNotificationPUsersScheduledForDeletion[]= clone $pUNotificationPUser;
            $pUNotificationPUser->setPUNotificationPUser(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this PUser is new, it will return
     * an empty collection; or if this PUser has previously
     * been saved, it will retrieve related PUNotificationPUsers from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in PUser.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|PUNotification[] List of PUNotification objects
     */
    public function getPUNotificationPUsersJoinPUNotificationPNotification($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PUNotificationQuery::create(null, $criteria);
        $query->joinWith('PUNotificationPNotification', $join_behavior);

        return $this->getPUNotificationPUsers($query, $con);
    }

    /**
     * Clears out the collPUSubscribePNEs collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return PUser The current object (for fluent API support)
     * @see        addPUSubscribePNEs()
     */
    public function clearPUSubscribePNEs()
    {
        $this->collPUSubscribePNEs = null; // important to set this to null since that means it is uninitialized
        $this->collPUSubscribePNEsPartial = null;

        return $this;
    }

    /**
     * reset is the collPUSubscribePNEs collection loaded partially
     *
     * @return void
     */
    public function resetPartialPUSubscribePNEs($v = true)
    {
        $this->collPUSubscribePNEsPartial = $v;
    }

    /**
     * Initializes the collPUSubscribePNEs collection.
     *
     * By default this just sets the collPUSubscribePNEs collection to an empty array (like clearcollPUSubscribePNEs());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPUSubscribePNEs($overrideExisting = true)
    {
        if (null !== $this->collPUSubscribePNEs && !$overrideExisting) {
            return;
        }
        $this->collPUSubscribePNEs = new PropelObjectCollection();
        $this->collPUSubscribePNEs->setModel('PUSubscribePNE');
    }

    /**
     * Gets an array of PUSubscribePNE objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this PUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|PUSubscribePNE[] List of PUSubscribePNE objects
     * @throws PropelException
     */
    public function getPUSubscribePNEs($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collPUSubscribePNEsPartial && !$this->isNew();
        if (null === $this->collPUSubscribePNEs || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPUSubscribePNEs) {
                // return empty collection
                $this->initPUSubscribePNEs();
            } else {
                $collPUSubscribePNEs = PUSubscribePNEQuery::create(null, $criteria)
                    ->filterByPUser($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collPUSubscribePNEsPartial && count($collPUSubscribePNEs)) {
                      $this->initPUSubscribePNEs(false);

                      foreach ($collPUSubscribePNEs as $obj) {
                        if (false == $this->collPUSubscribePNEs->contains($obj)) {
                          $this->collPUSubscribePNEs->append($obj);
                        }
                      }

                      $this->collPUSubscribePNEsPartial = true;
                    }

                    $collPUSubscribePNEs->getInternalIterator()->rewind();

                    return $collPUSubscribePNEs;
                }

                if ($partial && $this->collPUSubscribePNEs) {
                    foreach ($this->collPUSubscribePNEs as $obj) {
                        if ($obj->isNew()) {
                            $collPUSubscribePNEs[] = $obj;
                        }
                    }
                }

                $this->collPUSubscribePNEs = $collPUSubscribePNEs;
                $this->collPUSubscribePNEsPartial = false;
            }
        }

        return $this->collPUSubscribePNEs;
    }

    /**
     * Sets a collection of PUSubscribePNE objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $pUSubscribePNEs A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return PUser The current object (for fluent API support)
     */
    public function setPUSubscribePNEs(PropelCollection $pUSubscribePNEs, PropelPDO $con = null)
    {
        $pUSubscribePNEsToDelete = $this->getPUSubscribePNEs(new Criteria(), $con)->diff($pUSubscribePNEs);


        $this->pUSubscribePNEsScheduledForDeletion = $pUSubscribePNEsToDelete;

        foreach ($pUSubscribePNEsToDelete as $pUSubscribePNERemoved) {
            $pUSubscribePNERemoved->setPUser(null);
        }

        $this->collPUSubscribePNEs = null;
        foreach ($pUSubscribePNEs as $pUSubscribePNE) {
            $this->addPUSubscribePNE($pUSubscribePNE);
        }

        $this->collPUSubscribePNEs = $pUSubscribePNEs;
        $this->collPUSubscribePNEsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related PUSubscribePNE objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related PUSubscribePNE objects.
     * @throws PropelException
     */
    public function countPUSubscribePNEs(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collPUSubscribePNEsPartial && !$this->isNew();
        if (null === $this->collPUSubscribePNEs || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPUSubscribePNEs) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPUSubscribePNEs());
            }
            $query = PUSubscribePNEQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPUser($this)
                ->count($con);
        }

        return count($this->collPUSubscribePNEs);
    }

    /**
     * Method called to associate a PUSubscribePNE object to this object
     * through the PUSubscribePNE foreign key attribute.
     *
     * @param    PUSubscribePNE $l PUSubscribePNE
     * @return PUser The current object (for fluent API support)
     */
    public function addPUSubscribePNE(PUSubscribePNE $l)
    {
        if ($this->collPUSubscribePNEs === null) {
            $this->initPUSubscribePNEs();
            $this->collPUSubscribePNEsPartial = true;
        }

        if (!in_array($l, $this->collPUSubscribePNEs->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddPUSubscribePNE($l);

            if ($this->pUSubscribePNEsScheduledForDeletion and $this->pUSubscribePNEsScheduledForDeletion->contains($l)) {
                $this->pUSubscribePNEsScheduledForDeletion->remove($this->pUSubscribePNEsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	PUSubscribePNE $pUSubscribePNE The pUSubscribePNE object to add.
     */
    protected function doAddPUSubscribePNE($pUSubscribePNE)
    {
        $this->collPUSubscribePNEs[]= $pUSubscribePNE;
        $pUSubscribePNE->setPUser($this);
    }

    /**
     * @param	PUSubscribePNE $pUSubscribePNE The pUSubscribePNE object to remove.
     * @return PUser The current object (for fluent API support)
     */
    public function removePUSubscribePNE($pUSubscribePNE)
    {
        if ($this->getPUSubscribePNEs()->contains($pUSubscribePNE)) {
            $this->collPUSubscribePNEs->remove($this->collPUSubscribePNEs->search($pUSubscribePNE));
            if (null === $this->pUSubscribePNEsScheduledForDeletion) {
                $this->pUSubscribePNEsScheduledForDeletion = clone $this->collPUSubscribePNEs;
                $this->pUSubscribePNEsScheduledForDeletion->clear();
            }
            $this->pUSubscribePNEsScheduledForDeletion[]= clone $pUSubscribePNE;
            $pUSubscribePNE->setPUser(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this PUser is new, it will return
     * an empty collection; or if this PUser has previously
     * been saved, it will retrieve related PUSubscribePNEs from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in PUser.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|PUSubscribePNE[] List of PUSubscribePNE objects
     */
    public function getPUSubscribePNEsJoinPNEmail($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PUSubscribePNEQuery::create(null, $criteria);
        $query->joinWith('PNEmail', $join_behavior);

        return $this->getPUSubscribePNEs($query, $con);
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
    public function getPDDebatesJoinPLCity($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PDDebateQuery::create(null, $criteria);
        $query->joinWith('PLCity', $join_behavior);

        return $this->getPDDebates($query, $con);
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
    public function getPDDebatesJoinPLDepartment($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PDDebateQuery::create(null, $criteria);
        $query->joinWith('PLDepartment', $join_behavior);

        return $this->getPDDebates($query, $con);
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
    public function getPDDebatesJoinPLRegion($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PDDebateQuery::create(null, $criteria);
        $query->joinWith('PLRegion', $join_behavior);

        return $this->getPDDebates($query, $con);
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
    public function getPDDebatesJoinPLCountry($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PDDebateQuery::create(null, $criteria);
        $query->joinWith('PLCountry', $join_behavior);

        return $this->getPDDebates($query, $con);
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
    public function getPDDebatesJoinPEOperation($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PDDebateQuery::create(null, $criteria);
        $query->joinWith('PEOperation', $join_behavior);

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
    public function getPDReactionsJoinPLCity($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PDReactionQuery::create(null, $criteria);
        $query->joinWith('PLCity', $join_behavior);

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
    public function getPDReactionsJoinPLDepartment($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PDReactionQuery::create(null, $criteria);
        $query->joinWith('PLDepartment', $join_behavior);

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
    public function getPDReactionsJoinPLRegion($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PDReactionQuery::create(null, $criteria);
        $query->joinWith('PLRegion', $join_behavior);

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
     * @return PUser The current object (for fluent API support)
     */
    public function setPDDComments(PropelCollection $pDDComments, PropelPDO $con = null)
    {
        $pDDCommentsToDelete = $this->getPDDComments(new Criteria(), $con)->diff($pDDComments);


        $this->pDDCommentsScheduledForDeletion = $pDDCommentsToDelete;

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

            if ($partial && !$criteria) {
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
     * @return PUser The current object (for fluent API support)
     */
    public function setPDRComments(PropelCollection $pDRComments, PropelPDO $con = null)
    {
        $pDRCommentsToDelete = $this->getPDRComments(new Criteria(), $con)->diff($pDRComments);


        $this->pDRCommentsScheduledForDeletion = $pDRCommentsToDelete;

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

            if ($partial && !$criteria) {
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
     * Clears out the collPMUserModerateds collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return PUser The current object (for fluent API support)
     * @see        addPMUserModerateds()
     */
    public function clearPMUserModerateds()
    {
        $this->collPMUserModerateds = null; // important to set this to null since that means it is uninitialized
        $this->collPMUserModeratedsPartial = null;

        return $this;
    }

    /**
     * reset is the collPMUserModerateds collection loaded partially
     *
     * @return void
     */
    public function resetPartialPMUserModerateds($v = true)
    {
        $this->collPMUserModeratedsPartial = $v;
    }

    /**
     * Initializes the collPMUserModerateds collection.
     *
     * By default this just sets the collPMUserModerateds collection to an empty array (like clearcollPMUserModerateds());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPMUserModerateds($overrideExisting = true)
    {
        if (null !== $this->collPMUserModerateds && !$overrideExisting) {
            return;
        }
        $this->collPMUserModerateds = new PropelObjectCollection();
        $this->collPMUserModerateds->setModel('PMUserModerated');
    }

    /**
     * Gets an array of PMUserModerated objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this PUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|PMUserModerated[] List of PMUserModerated objects
     * @throws PropelException
     */
    public function getPMUserModerateds($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collPMUserModeratedsPartial && !$this->isNew();
        if (null === $this->collPMUserModerateds || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPMUserModerateds) {
                // return empty collection
                $this->initPMUserModerateds();
            } else {
                $collPMUserModerateds = PMUserModeratedQuery::create(null, $criteria)
                    ->filterByPUser($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collPMUserModeratedsPartial && count($collPMUserModerateds)) {
                      $this->initPMUserModerateds(false);

                      foreach ($collPMUserModerateds as $obj) {
                        if (false == $this->collPMUserModerateds->contains($obj)) {
                          $this->collPMUserModerateds->append($obj);
                        }
                      }

                      $this->collPMUserModeratedsPartial = true;
                    }

                    $collPMUserModerateds->getInternalIterator()->rewind();

                    return $collPMUserModerateds;
                }

                if ($partial && $this->collPMUserModerateds) {
                    foreach ($this->collPMUserModerateds as $obj) {
                        if ($obj->isNew()) {
                            $collPMUserModerateds[] = $obj;
                        }
                    }
                }

                $this->collPMUserModerateds = $collPMUserModerateds;
                $this->collPMUserModeratedsPartial = false;
            }
        }

        return $this->collPMUserModerateds;
    }

    /**
     * Sets a collection of PMUserModerated objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $pMUserModerateds A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return PUser The current object (for fluent API support)
     */
    public function setPMUserModerateds(PropelCollection $pMUserModerateds, PropelPDO $con = null)
    {
        $pMUserModeratedsToDelete = $this->getPMUserModerateds(new Criteria(), $con)->diff($pMUserModerateds);


        $this->pMUserModeratedsScheduledForDeletion = $pMUserModeratedsToDelete;

        foreach ($pMUserModeratedsToDelete as $pMUserModeratedRemoved) {
            $pMUserModeratedRemoved->setPUser(null);
        }

        $this->collPMUserModerateds = null;
        foreach ($pMUserModerateds as $pMUserModerated) {
            $this->addPMUserModerated($pMUserModerated);
        }

        $this->collPMUserModerateds = $pMUserModerateds;
        $this->collPMUserModeratedsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related PMUserModerated objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related PMUserModerated objects.
     * @throws PropelException
     */
    public function countPMUserModerateds(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collPMUserModeratedsPartial && !$this->isNew();
        if (null === $this->collPMUserModerateds || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPMUserModerateds) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPMUserModerateds());
            }
            $query = PMUserModeratedQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPUser($this)
                ->count($con);
        }

        return count($this->collPMUserModerateds);
    }

    /**
     * Method called to associate a PMUserModerated object to this object
     * through the PMUserModerated foreign key attribute.
     *
     * @param    PMUserModerated $l PMUserModerated
     * @return PUser The current object (for fluent API support)
     */
    public function addPMUserModerated(PMUserModerated $l)
    {
        if ($this->collPMUserModerateds === null) {
            $this->initPMUserModerateds();
            $this->collPMUserModeratedsPartial = true;
        }

        if (!in_array($l, $this->collPMUserModerateds->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddPMUserModerated($l);

            if ($this->pMUserModeratedsScheduledForDeletion and $this->pMUserModeratedsScheduledForDeletion->contains($l)) {
                $this->pMUserModeratedsScheduledForDeletion->remove($this->pMUserModeratedsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	PMUserModerated $pMUserModerated The pMUserModerated object to add.
     */
    protected function doAddPMUserModerated($pMUserModerated)
    {
        $this->collPMUserModerateds[]= $pMUserModerated;
        $pMUserModerated->setPUser($this);
    }

    /**
     * @param	PMUserModerated $pMUserModerated The pMUserModerated object to remove.
     * @return PUser The current object (for fluent API support)
     */
    public function removePMUserModerated($pMUserModerated)
    {
        if ($this->getPMUserModerateds()->contains($pMUserModerated)) {
            $this->collPMUserModerateds->remove($this->collPMUserModerateds->search($pMUserModerated));
            if (null === $this->pMUserModeratedsScheduledForDeletion) {
                $this->pMUserModeratedsScheduledForDeletion = clone $this->collPMUserModerateds;
                $this->pMUserModeratedsScheduledForDeletion->clear();
            }
            $this->pMUserModeratedsScheduledForDeletion[]= clone $pMUserModerated;
            $pMUserModerated->setPUser(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this PUser is new, it will return
     * an empty collection; or if this PUser has previously
     * been saved, it will retrieve related PMUserModerateds from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in PUser.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|PMUserModerated[] List of PMUserModerated objects
     */
    public function getPMUserModeratedsJoinPMModerationType($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PMUserModeratedQuery::create(null, $criteria);
        $query->joinWith('PMModerationType', $join_behavior);

        return $this->getPMUserModerateds($query, $con);
    }

    /**
     * Clears out the collPMUserMessages collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return PUser The current object (for fluent API support)
     * @see        addPMUserMessages()
     */
    public function clearPMUserMessages()
    {
        $this->collPMUserMessages = null; // important to set this to null since that means it is uninitialized
        $this->collPMUserMessagesPartial = null;

        return $this;
    }

    /**
     * reset is the collPMUserMessages collection loaded partially
     *
     * @return void
     */
    public function resetPartialPMUserMessages($v = true)
    {
        $this->collPMUserMessagesPartial = $v;
    }

    /**
     * Initializes the collPMUserMessages collection.
     *
     * By default this just sets the collPMUserMessages collection to an empty array (like clearcollPMUserMessages());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPMUserMessages($overrideExisting = true)
    {
        if (null !== $this->collPMUserMessages && !$overrideExisting) {
            return;
        }
        $this->collPMUserMessages = new PropelObjectCollection();
        $this->collPMUserMessages->setModel('PMUserMessage');
    }

    /**
     * Gets an array of PMUserMessage objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this PUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|PMUserMessage[] List of PMUserMessage objects
     * @throws PropelException
     */
    public function getPMUserMessages($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collPMUserMessagesPartial && !$this->isNew();
        if (null === $this->collPMUserMessages || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPMUserMessages) {
                // return empty collection
                $this->initPMUserMessages();
            } else {
                $collPMUserMessages = PMUserMessageQuery::create(null, $criteria)
                    ->filterByPUser($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collPMUserMessagesPartial && count($collPMUserMessages)) {
                      $this->initPMUserMessages(false);

                      foreach ($collPMUserMessages as $obj) {
                        if (false == $this->collPMUserMessages->contains($obj)) {
                          $this->collPMUserMessages->append($obj);
                        }
                      }

                      $this->collPMUserMessagesPartial = true;
                    }

                    $collPMUserMessages->getInternalIterator()->rewind();

                    return $collPMUserMessages;
                }

                if ($partial && $this->collPMUserMessages) {
                    foreach ($this->collPMUserMessages as $obj) {
                        if ($obj->isNew()) {
                            $collPMUserMessages[] = $obj;
                        }
                    }
                }

                $this->collPMUserMessages = $collPMUserMessages;
                $this->collPMUserMessagesPartial = false;
            }
        }

        return $this->collPMUserMessages;
    }

    /**
     * Sets a collection of PMUserMessage objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $pMUserMessages A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return PUser The current object (for fluent API support)
     */
    public function setPMUserMessages(PropelCollection $pMUserMessages, PropelPDO $con = null)
    {
        $pMUserMessagesToDelete = $this->getPMUserMessages(new Criteria(), $con)->diff($pMUserMessages);


        $this->pMUserMessagesScheduledForDeletion = $pMUserMessagesToDelete;

        foreach ($pMUserMessagesToDelete as $pMUserMessageRemoved) {
            $pMUserMessageRemoved->setPUser(null);
        }

        $this->collPMUserMessages = null;
        foreach ($pMUserMessages as $pMUserMessage) {
            $this->addPMUserMessage($pMUserMessage);
        }

        $this->collPMUserMessages = $pMUserMessages;
        $this->collPMUserMessagesPartial = false;

        return $this;
    }

    /**
     * Returns the number of related PMUserMessage objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related PMUserMessage objects.
     * @throws PropelException
     */
    public function countPMUserMessages(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collPMUserMessagesPartial && !$this->isNew();
        if (null === $this->collPMUserMessages || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPMUserMessages) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPMUserMessages());
            }
            $query = PMUserMessageQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPUser($this)
                ->count($con);
        }

        return count($this->collPMUserMessages);
    }

    /**
     * Method called to associate a PMUserMessage object to this object
     * through the PMUserMessage foreign key attribute.
     *
     * @param    PMUserMessage $l PMUserMessage
     * @return PUser The current object (for fluent API support)
     */
    public function addPMUserMessage(PMUserMessage $l)
    {
        if ($this->collPMUserMessages === null) {
            $this->initPMUserMessages();
            $this->collPMUserMessagesPartial = true;
        }

        if (!in_array($l, $this->collPMUserMessages->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddPMUserMessage($l);

            if ($this->pMUserMessagesScheduledForDeletion and $this->pMUserMessagesScheduledForDeletion->contains($l)) {
                $this->pMUserMessagesScheduledForDeletion->remove($this->pMUserMessagesScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	PMUserMessage $pMUserMessage The pMUserMessage object to add.
     */
    protected function doAddPMUserMessage($pMUserMessage)
    {
        $this->collPMUserMessages[]= $pMUserMessage;
        $pMUserMessage->setPUser($this);
    }

    /**
     * @param	PMUserMessage $pMUserMessage The pMUserMessage object to remove.
     * @return PUser The current object (for fluent API support)
     */
    public function removePMUserMessage($pMUserMessage)
    {
        if ($this->getPMUserMessages()->contains($pMUserMessage)) {
            $this->collPMUserMessages->remove($this->collPMUserMessages->search($pMUserMessage));
            if (null === $this->pMUserMessagesScheduledForDeletion) {
                $this->pMUserMessagesScheduledForDeletion = clone $this->collPMUserMessages;
                $this->pMUserMessagesScheduledForDeletion->clear();
            }
            $this->pMUserMessagesScheduledForDeletion[]= $pMUserMessage;
            $pMUserMessage->setPUser(null);
        }

        return $this;
    }

    /**
     * Clears out the collPMUserHistorics collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return PUser The current object (for fluent API support)
     * @see        addPMUserHistorics()
     */
    public function clearPMUserHistorics()
    {
        $this->collPMUserHistorics = null; // important to set this to null since that means it is uninitialized
        $this->collPMUserHistoricsPartial = null;

        return $this;
    }

    /**
     * reset is the collPMUserHistorics collection loaded partially
     *
     * @return void
     */
    public function resetPartialPMUserHistorics($v = true)
    {
        $this->collPMUserHistoricsPartial = $v;
    }

    /**
     * Initializes the collPMUserHistorics collection.
     *
     * By default this just sets the collPMUserHistorics collection to an empty array (like clearcollPMUserHistorics());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPMUserHistorics($overrideExisting = true)
    {
        if (null !== $this->collPMUserHistorics && !$overrideExisting) {
            return;
        }
        $this->collPMUserHistorics = new PropelObjectCollection();
        $this->collPMUserHistorics->setModel('PMUserHistoric');
    }

    /**
     * Gets an array of PMUserHistoric objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this PUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|PMUserHistoric[] List of PMUserHistoric objects
     * @throws PropelException
     */
    public function getPMUserHistorics($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collPMUserHistoricsPartial && !$this->isNew();
        if (null === $this->collPMUserHistorics || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPMUserHistorics) {
                // return empty collection
                $this->initPMUserHistorics();
            } else {
                $collPMUserHistorics = PMUserHistoricQuery::create(null, $criteria)
                    ->filterByPUser($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collPMUserHistoricsPartial && count($collPMUserHistorics)) {
                      $this->initPMUserHistorics(false);

                      foreach ($collPMUserHistorics as $obj) {
                        if (false == $this->collPMUserHistorics->contains($obj)) {
                          $this->collPMUserHistorics->append($obj);
                        }
                      }

                      $this->collPMUserHistoricsPartial = true;
                    }

                    $collPMUserHistorics->getInternalIterator()->rewind();

                    return $collPMUserHistorics;
                }

                if ($partial && $this->collPMUserHistorics) {
                    foreach ($this->collPMUserHistorics as $obj) {
                        if ($obj->isNew()) {
                            $collPMUserHistorics[] = $obj;
                        }
                    }
                }

                $this->collPMUserHistorics = $collPMUserHistorics;
                $this->collPMUserHistoricsPartial = false;
            }
        }

        return $this->collPMUserHistorics;
    }

    /**
     * Sets a collection of PMUserHistoric objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $pMUserHistorics A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return PUser The current object (for fluent API support)
     */
    public function setPMUserHistorics(PropelCollection $pMUserHistorics, PropelPDO $con = null)
    {
        $pMUserHistoricsToDelete = $this->getPMUserHistorics(new Criteria(), $con)->diff($pMUserHistorics);


        $this->pMUserHistoricsScheduledForDeletion = $pMUserHistoricsToDelete;

        foreach ($pMUserHistoricsToDelete as $pMUserHistoricRemoved) {
            $pMUserHistoricRemoved->setPUser(null);
        }

        $this->collPMUserHistorics = null;
        foreach ($pMUserHistorics as $pMUserHistoric) {
            $this->addPMUserHistoric($pMUserHistoric);
        }

        $this->collPMUserHistorics = $pMUserHistorics;
        $this->collPMUserHistoricsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related PMUserHistoric objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related PMUserHistoric objects.
     * @throws PropelException
     */
    public function countPMUserHistorics(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collPMUserHistoricsPartial && !$this->isNew();
        if (null === $this->collPMUserHistorics || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPMUserHistorics) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPMUserHistorics());
            }
            $query = PMUserHistoricQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPUser($this)
                ->count($con);
        }

        return count($this->collPMUserHistorics);
    }

    /**
     * Method called to associate a PMUserHistoric object to this object
     * through the PMUserHistoric foreign key attribute.
     *
     * @param    PMUserHistoric $l PMUserHistoric
     * @return PUser The current object (for fluent API support)
     */
    public function addPMUserHistoric(PMUserHistoric $l)
    {
        if ($this->collPMUserHistorics === null) {
            $this->initPMUserHistorics();
            $this->collPMUserHistoricsPartial = true;
        }

        if (!in_array($l, $this->collPMUserHistorics->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddPMUserHistoric($l);

            if ($this->pMUserHistoricsScheduledForDeletion and $this->pMUserHistoricsScheduledForDeletion->contains($l)) {
                $this->pMUserHistoricsScheduledForDeletion->remove($this->pMUserHistoricsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	PMUserHistoric $pMUserHistoric The pMUserHistoric object to add.
     */
    protected function doAddPMUserHistoric($pMUserHistoric)
    {
        $this->collPMUserHistorics[]= $pMUserHistoric;
        $pMUserHistoric->setPUser($this);
    }

    /**
     * @param	PMUserHistoric $pMUserHistoric The pMUserHistoric object to remove.
     * @return PUser The current object (for fluent API support)
     */
    public function removePMUserHistoric($pMUserHistoric)
    {
        if ($this->getPMUserHistorics()->contains($pMUserHistoric)) {
            $this->collPMUserHistorics->remove($this->collPMUserHistorics->search($pMUserHistoric));
            if (null === $this->pMUserHistoricsScheduledForDeletion) {
                $this->pMUserHistoricsScheduledForDeletion = clone $this->collPMUserHistorics;
                $this->pMUserHistoricsScheduledForDeletion->clear();
            }
            $this->pMUserHistoricsScheduledForDeletion[]= $pMUserHistoric;
            $pMUserHistoric->setPUser(null);
        }

        return $this;
    }

    /**
     * Clears out the collPMDebateHistorics collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return PUser The current object (for fluent API support)
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
     * If this PUser is new, it will return
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
                    ->filterByPUser($this)
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
     * @return PUser The current object (for fluent API support)
     */
    public function setPMDebateHistorics(PropelCollection $pMDebateHistorics, PropelPDO $con = null)
    {
        $pMDebateHistoricsToDelete = $this->getPMDebateHistorics(new Criteria(), $con)->diff($pMDebateHistorics);


        $this->pMDebateHistoricsScheduledForDeletion = $pMDebateHistoricsToDelete;

        foreach ($pMDebateHistoricsToDelete as $pMDebateHistoricRemoved) {
            $pMDebateHistoricRemoved->setPUser(null);
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
                ->filterByPUser($this)
                ->count($con);
        }

        return count($this->collPMDebateHistorics);
    }

    /**
     * Method called to associate a PMDebateHistoric object to this object
     * through the PMDebateHistoric foreign key attribute.
     *
     * @param    PMDebateHistoric $l PMDebateHistoric
     * @return PUser The current object (for fluent API support)
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
        $pMDebateHistoric->setPUser($this);
    }

    /**
     * @param	PMDebateHistoric $pMDebateHistoric The pMDebateHistoric object to remove.
     * @return PUser The current object (for fluent API support)
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
            $pMDebateHistoric->setPUser(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this PUser is new, it will return
     * an empty collection; or if this PUser has previously
     * been saved, it will retrieve related PMDebateHistorics from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in PUser.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|PMDebateHistoric[] List of PMDebateHistoric objects
     */
    public function getPMDebateHistoricsJoinPDDebate($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PMDebateHistoricQuery::create(null, $criteria);
        $query->joinWith('PDDebate', $join_behavior);

        return $this->getPMDebateHistorics($query, $con);
    }

    /**
     * Clears out the collPMReactionHistorics collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return PUser The current object (for fluent API support)
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
     * If this PUser is new, it will return
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
                    ->filterByPUser($this)
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
     * @return PUser The current object (for fluent API support)
     */
    public function setPMReactionHistorics(PropelCollection $pMReactionHistorics, PropelPDO $con = null)
    {
        $pMReactionHistoricsToDelete = $this->getPMReactionHistorics(new Criteria(), $con)->diff($pMReactionHistorics);


        $this->pMReactionHistoricsScheduledForDeletion = $pMReactionHistoricsToDelete;

        foreach ($pMReactionHistoricsToDelete as $pMReactionHistoricRemoved) {
            $pMReactionHistoricRemoved->setPUser(null);
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
                ->filterByPUser($this)
                ->count($con);
        }

        return count($this->collPMReactionHistorics);
    }

    /**
     * Method called to associate a PMReactionHistoric object to this object
     * through the PMReactionHistoric foreign key attribute.
     *
     * @param    PMReactionHistoric $l PMReactionHistoric
     * @return PUser The current object (for fluent API support)
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
        $pMReactionHistoric->setPUser($this);
    }

    /**
     * @param	PMReactionHistoric $pMReactionHistoric The pMReactionHistoric object to remove.
     * @return PUser The current object (for fluent API support)
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
            $pMReactionHistoric->setPUser(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this PUser is new, it will return
     * an empty collection; or if this PUser has previously
     * been saved, it will retrieve related PMReactionHistorics from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in PUser.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|PMReactionHistoric[] List of PMReactionHistoric objects
     */
    public function getPMReactionHistoricsJoinPDReaction($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PMReactionHistoricQuery::create(null, $criteria);
        $query->joinWith('PDReaction', $join_behavior);

        return $this->getPMReactionHistorics($query, $con);
    }

    /**
     * Clears out the collPMDCommentHistorics collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return PUser The current object (for fluent API support)
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
     * If this PUser is new, it will return
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
                    ->filterByPUser($this)
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
     * @return PUser The current object (for fluent API support)
     */
    public function setPMDCommentHistorics(PropelCollection $pMDCommentHistorics, PropelPDO $con = null)
    {
        $pMDCommentHistoricsToDelete = $this->getPMDCommentHistorics(new Criteria(), $con)->diff($pMDCommentHistorics);


        $this->pMDCommentHistoricsScheduledForDeletion = $pMDCommentHistoricsToDelete;

        foreach ($pMDCommentHistoricsToDelete as $pMDCommentHistoricRemoved) {
            $pMDCommentHistoricRemoved->setPUser(null);
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
                ->filterByPUser($this)
                ->count($con);
        }

        return count($this->collPMDCommentHistorics);
    }

    /**
     * Method called to associate a PMDCommentHistoric object to this object
     * through the PMDCommentHistoric foreign key attribute.
     *
     * @param    PMDCommentHistoric $l PMDCommentHistoric
     * @return PUser The current object (for fluent API support)
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
        $pMDCommentHistoric->setPUser($this);
    }

    /**
     * @param	PMDCommentHistoric $pMDCommentHistoric The pMDCommentHistoric object to remove.
     * @return PUser The current object (for fluent API support)
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
            $pMDCommentHistoric->setPUser(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this PUser is new, it will return
     * an empty collection; or if this PUser has previously
     * been saved, it will retrieve related PMDCommentHistorics from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in PUser.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|PMDCommentHistoric[] List of PMDCommentHistoric objects
     */
    public function getPMDCommentHistoricsJoinPDDComment($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PMDCommentHistoricQuery::create(null, $criteria);
        $query->joinWith('PDDComment', $join_behavior);

        return $this->getPMDCommentHistorics($query, $con);
    }

    /**
     * Clears out the collPMRCommentHistorics collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return PUser The current object (for fluent API support)
     * @see        addPMRCommentHistorics()
     */
    public function clearPMRCommentHistorics()
    {
        $this->collPMRCommentHistorics = null; // important to set this to null since that means it is uninitialized
        $this->collPMRCommentHistoricsPartial = null;

        return $this;
    }

    /**
     * reset is the collPMRCommentHistorics collection loaded partially
     *
     * @return void
     */
    public function resetPartialPMRCommentHistorics($v = true)
    {
        $this->collPMRCommentHistoricsPartial = $v;
    }

    /**
     * Initializes the collPMRCommentHistorics collection.
     *
     * By default this just sets the collPMRCommentHistorics collection to an empty array (like clearcollPMRCommentHistorics());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPMRCommentHistorics($overrideExisting = true)
    {
        if (null !== $this->collPMRCommentHistorics && !$overrideExisting) {
            return;
        }
        $this->collPMRCommentHistorics = new PropelObjectCollection();
        $this->collPMRCommentHistorics->setModel('PMRCommentHistoric');
    }

    /**
     * Gets an array of PMRCommentHistoric objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this PUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|PMRCommentHistoric[] List of PMRCommentHistoric objects
     * @throws PropelException
     */
    public function getPMRCommentHistorics($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collPMRCommentHistoricsPartial && !$this->isNew();
        if (null === $this->collPMRCommentHistorics || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPMRCommentHistorics) {
                // return empty collection
                $this->initPMRCommentHistorics();
            } else {
                $collPMRCommentHistorics = PMRCommentHistoricQuery::create(null, $criteria)
                    ->filterByPUser($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collPMRCommentHistoricsPartial && count($collPMRCommentHistorics)) {
                      $this->initPMRCommentHistorics(false);

                      foreach ($collPMRCommentHistorics as $obj) {
                        if (false == $this->collPMRCommentHistorics->contains($obj)) {
                          $this->collPMRCommentHistorics->append($obj);
                        }
                      }

                      $this->collPMRCommentHistoricsPartial = true;
                    }

                    $collPMRCommentHistorics->getInternalIterator()->rewind();

                    return $collPMRCommentHistorics;
                }

                if ($partial && $this->collPMRCommentHistorics) {
                    foreach ($this->collPMRCommentHistorics as $obj) {
                        if ($obj->isNew()) {
                            $collPMRCommentHistorics[] = $obj;
                        }
                    }
                }

                $this->collPMRCommentHistorics = $collPMRCommentHistorics;
                $this->collPMRCommentHistoricsPartial = false;
            }
        }

        return $this->collPMRCommentHistorics;
    }

    /**
     * Sets a collection of PMRCommentHistoric objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $pMRCommentHistorics A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return PUser The current object (for fluent API support)
     */
    public function setPMRCommentHistorics(PropelCollection $pMRCommentHistorics, PropelPDO $con = null)
    {
        $pMRCommentHistoricsToDelete = $this->getPMRCommentHistorics(new Criteria(), $con)->diff($pMRCommentHistorics);


        $this->pMRCommentHistoricsScheduledForDeletion = $pMRCommentHistoricsToDelete;

        foreach ($pMRCommentHistoricsToDelete as $pMRCommentHistoricRemoved) {
            $pMRCommentHistoricRemoved->setPUser(null);
        }

        $this->collPMRCommentHistorics = null;
        foreach ($pMRCommentHistorics as $pMRCommentHistoric) {
            $this->addPMRCommentHistoric($pMRCommentHistoric);
        }

        $this->collPMRCommentHistorics = $pMRCommentHistorics;
        $this->collPMRCommentHistoricsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related PMRCommentHistoric objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related PMRCommentHistoric objects.
     * @throws PropelException
     */
    public function countPMRCommentHistorics(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collPMRCommentHistoricsPartial && !$this->isNew();
        if (null === $this->collPMRCommentHistorics || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPMRCommentHistorics) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPMRCommentHistorics());
            }
            $query = PMRCommentHistoricQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPUser($this)
                ->count($con);
        }

        return count($this->collPMRCommentHistorics);
    }

    /**
     * Method called to associate a PMRCommentHistoric object to this object
     * through the PMRCommentHistoric foreign key attribute.
     *
     * @param    PMRCommentHistoric $l PMRCommentHistoric
     * @return PUser The current object (for fluent API support)
     */
    public function addPMRCommentHistoric(PMRCommentHistoric $l)
    {
        if ($this->collPMRCommentHistorics === null) {
            $this->initPMRCommentHistorics();
            $this->collPMRCommentHistoricsPartial = true;
        }

        if (!in_array($l, $this->collPMRCommentHistorics->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddPMRCommentHistoric($l);

            if ($this->pMRCommentHistoricsScheduledForDeletion and $this->pMRCommentHistoricsScheduledForDeletion->contains($l)) {
                $this->pMRCommentHistoricsScheduledForDeletion->remove($this->pMRCommentHistoricsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	PMRCommentHistoric $pMRCommentHistoric The pMRCommentHistoric object to add.
     */
    protected function doAddPMRCommentHistoric($pMRCommentHistoric)
    {
        $this->collPMRCommentHistorics[]= $pMRCommentHistoric;
        $pMRCommentHistoric->setPUser($this);
    }

    /**
     * @param	PMRCommentHistoric $pMRCommentHistoric The pMRCommentHistoric object to remove.
     * @return PUser The current object (for fluent API support)
     */
    public function removePMRCommentHistoric($pMRCommentHistoric)
    {
        if ($this->getPMRCommentHistorics()->contains($pMRCommentHistoric)) {
            $this->collPMRCommentHistorics->remove($this->collPMRCommentHistorics->search($pMRCommentHistoric));
            if (null === $this->pMRCommentHistoricsScheduledForDeletion) {
                $this->pMRCommentHistoricsScheduledForDeletion = clone $this->collPMRCommentHistorics;
                $this->pMRCommentHistoricsScheduledForDeletion->clear();
            }
            $this->pMRCommentHistoricsScheduledForDeletion[]= $pMRCommentHistoric;
            $pMRCommentHistoric->setPUser(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this PUser is new, it will return
     * an empty collection; or if this PUser has previously
     * been saved, it will retrieve related PMRCommentHistorics from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in PUser.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|PMRCommentHistoric[] List of PMRCommentHistoric objects
     */
    public function getPMRCommentHistoricsJoinPDRComment($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PMRCommentHistoricQuery::create(null, $criteria);
        $query->joinWith('PDRComment', $join_behavior);

        return $this->getPMRCommentHistorics($query, $con);
    }

    /**
     * Clears out the collPMAskForUpdates collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return PUser The current object (for fluent API support)
     * @see        addPMAskForUpdates()
     */
    public function clearPMAskForUpdates()
    {
        $this->collPMAskForUpdates = null; // important to set this to null since that means it is uninitialized
        $this->collPMAskForUpdatesPartial = null;

        return $this;
    }

    /**
     * reset is the collPMAskForUpdates collection loaded partially
     *
     * @return void
     */
    public function resetPartialPMAskForUpdates($v = true)
    {
        $this->collPMAskForUpdatesPartial = $v;
    }

    /**
     * Initializes the collPMAskForUpdates collection.
     *
     * By default this just sets the collPMAskForUpdates collection to an empty array (like clearcollPMAskForUpdates());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPMAskForUpdates($overrideExisting = true)
    {
        if (null !== $this->collPMAskForUpdates && !$overrideExisting) {
            return;
        }
        $this->collPMAskForUpdates = new PropelObjectCollection();
        $this->collPMAskForUpdates->setModel('PMAskForUpdate');
    }

    /**
     * Gets an array of PMAskForUpdate objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this PUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|PMAskForUpdate[] List of PMAskForUpdate objects
     * @throws PropelException
     */
    public function getPMAskForUpdates($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collPMAskForUpdatesPartial && !$this->isNew();
        if (null === $this->collPMAskForUpdates || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPMAskForUpdates) {
                // return empty collection
                $this->initPMAskForUpdates();
            } else {
                $collPMAskForUpdates = PMAskForUpdateQuery::create(null, $criteria)
                    ->filterByPUser($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collPMAskForUpdatesPartial && count($collPMAskForUpdates)) {
                      $this->initPMAskForUpdates(false);

                      foreach ($collPMAskForUpdates as $obj) {
                        if (false == $this->collPMAskForUpdates->contains($obj)) {
                          $this->collPMAskForUpdates->append($obj);
                        }
                      }

                      $this->collPMAskForUpdatesPartial = true;
                    }

                    $collPMAskForUpdates->getInternalIterator()->rewind();

                    return $collPMAskForUpdates;
                }

                if ($partial && $this->collPMAskForUpdates) {
                    foreach ($this->collPMAskForUpdates as $obj) {
                        if ($obj->isNew()) {
                            $collPMAskForUpdates[] = $obj;
                        }
                    }
                }

                $this->collPMAskForUpdates = $collPMAskForUpdates;
                $this->collPMAskForUpdatesPartial = false;
            }
        }

        return $this->collPMAskForUpdates;
    }

    /**
     * Sets a collection of PMAskForUpdate objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $pMAskForUpdates A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return PUser The current object (for fluent API support)
     */
    public function setPMAskForUpdates(PropelCollection $pMAskForUpdates, PropelPDO $con = null)
    {
        $pMAskForUpdatesToDelete = $this->getPMAskForUpdates(new Criteria(), $con)->diff($pMAskForUpdates);


        $this->pMAskForUpdatesScheduledForDeletion = $pMAskForUpdatesToDelete;

        foreach ($pMAskForUpdatesToDelete as $pMAskForUpdateRemoved) {
            $pMAskForUpdateRemoved->setPUser(null);
        }

        $this->collPMAskForUpdates = null;
        foreach ($pMAskForUpdates as $pMAskForUpdate) {
            $this->addPMAskForUpdate($pMAskForUpdate);
        }

        $this->collPMAskForUpdates = $pMAskForUpdates;
        $this->collPMAskForUpdatesPartial = false;

        return $this;
    }

    /**
     * Returns the number of related PMAskForUpdate objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related PMAskForUpdate objects.
     * @throws PropelException
     */
    public function countPMAskForUpdates(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collPMAskForUpdatesPartial && !$this->isNew();
        if (null === $this->collPMAskForUpdates || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPMAskForUpdates) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPMAskForUpdates());
            }
            $query = PMAskForUpdateQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPUser($this)
                ->count($con);
        }

        return count($this->collPMAskForUpdates);
    }

    /**
     * Method called to associate a PMAskForUpdate object to this object
     * through the PMAskForUpdate foreign key attribute.
     *
     * @param    PMAskForUpdate $l PMAskForUpdate
     * @return PUser The current object (for fluent API support)
     */
    public function addPMAskForUpdate(PMAskForUpdate $l)
    {
        if ($this->collPMAskForUpdates === null) {
            $this->initPMAskForUpdates();
            $this->collPMAskForUpdatesPartial = true;
        }

        if (!in_array($l, $this->collPMAskForUpdates->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddPMAskForUpdate($l);

            if ($this->pMAskForUpdatesScheduledForDeletion and $this->pMAskForUpdatesScheduledForDeletion->contains($l)) {
                $this->pMAskForUpdatesScheduledForDeletion->remove($this->pMAskForUpdatesScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	PMAskForUpdate $pMAskForUpdate The pMAskForUpdate object to add.
     */
    protected function doAddPMAskForUpdate($pMAskForUpdate)
    {
        $this->collPMAskForUpdates[]= $pMAskForUpdate;
        $pMAskForUpdate->setPUser($this);
    }

    /**
     * @param	PMAskForUpdate $pMAskForUpdate The pMAskForUpdate object to remove.
     * @return PUser The current object (for fluent API support)
     */
    public function removePMAskForUpdate($pMAskForUpdate)
    {
        if ($this->getPMAskForUpdates()->contains($pMAskForUpdate)) {
            $this->collPMAskForUpdates->remove($this->collPMAskForUpdates->search($pMAskForUpdate));
            if (null === $this->pMAskForUpdatesScheduledForDeletion) {
                $this->pMAskForUpdatesScheduledForDeletion = clone $this->collPMAskForUpdates;
                $this->pMAskForUpdatesScheduledForDeletion->clear();
            }
            $this->pMAskForUpdatesScheduledForDeletion[]= $pMAskForUpdate;
            $pMAskForUpdate->setPUser(null);
        }

        return $this;
    }

    /**
     * Clears out the collPMAbuseReportings collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return PUser The current object (for fluent API support)
     * @see        addPMAbuseReportings()
     */
    public function clearPMAbuseReportings()
    {
        $this->collPMAbuseReportings = null; // important to set this to null since that means it is uninitialized
        $this->collPMAbuseReportingsPartial = null;

        return $this;
    }

    /**
     * reset is the collPMAbuseReportings collection loaded partially
     *
     * @return void
     */
    public function resetPartialPMAbuseReportings($v = true)
    {
        $this->collPMAbuseReportingsPartial = $v;
    }

    /**
     * Initializes the collPMAbuseReportings collection.
     *
     * By default this just sets the collPMAbuseReportings collection to an empty array (like clearcollPMAbuseReportings());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPMAbuseReportings($overrideExisting = true)
    {
        if (null !== $this->collPMAbuseReportings && !$overrideExisting) {
            return;
        }
        $this->collPMAbuseReportings = new PropelObjectCollection();
        $this->collPMAbuseReportings->setModel('PMAbuseReporting');
    }

    /**
     * Gets an array of PMAbuseReporting objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this PUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|PMAbuseReporting[] List of PMAbuseReporting objects
     * @throws PropelException
     */
    public function getPMAbuseReportings($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collPMAbuseReportingsPartial && !$this->isNew();
        if (null === $this->collPMAbuseReportings || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPMAbuseReportings) {
                // return empty collection
                $this->initPMAbuseReportings();
            } else {
                $collPMAbuseReportings = PMAbuseReportingQuery::create(null, $criteria)
                    ->filterByPUser($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collPMAbuseReportingsPartial && count($collPMAbuseReportings)) {
                      $this->initPMAbuseReportings(false);

                      foreach ($collPMAbuseReportings as $obj) {
                        if (false == $this->collPMAbuseReportings->contains($obj)) {
                          $this->collPMAbuseReportings->append($obj);
                        }
                      }

                      $this->collPMAbuseReportingsPartial = true;
                    }

                    $collPMAbuseReportings->getInternalIterator()->rewind();

                    return $collPMAbuseReportings;
                }

                if ($partial && $this->collPMAbuseReportings) {
                    foreach ($this->collPMAbuseReportings as $obj) {
                        if ($obj->isNew()) {
                            $collPMAbuseReportings[] = $obj;
                        }
                    }
                }

                $this->collPMAbuseReportings = $collPMAbuseReportings;
                $this->collPMAbuseReportingsPartial = false;
            }
        }

        return $this->collPMAbuseReportings;
    }

    /**
     * Sets a collection of PMAbuseReporting objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $pMAbuseReportings A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return PUser The current object (for fluent API support)
     */
    public function setPMAbuseReportings(PropelCollection $pMAbuseReportings, PropelPDO $con = null)
    {
        $pMAbuseReportingsToDelete = $this->getPMAbuseReportings(new Criteria(), $con)->diff($pMAbuseReportings);


        $this->pMAbuseReportingsScheduledForDeletion = $pMAbuseReportingsToDelete;

        foreach ($pMAbuseReportingsToDelete as $pMAbuseReportingRemoved) {
            $pMAbuseReportingRemoved->setPUser(null);
        }

        $this->collPMAbuseReportings = null;
        foreach ($pMAbuseReportings as $pMAbuseReporting) {
            $this->addPMAbuseReporting($pMAbuseReporting);
        }

        $this->collPMAbuseReportings = $pMAbuseReportings;
        $this->collPMAbuseReportingsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related PMAbuseReporting objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related PMAbuseReporting objects.
     * @throws PropelException
     */
    public function countPMAbuseReportings(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collPMAbuseReportingsPartial && !$this->isNew();
        if (null === $this->collPMAbuseReportings || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPMAbuseReportings) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPMAbuseReportings());
            }
            $query = PMAbuseReportingQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPUser($this)
                ->count($con);
        }

        return count($this->collPMAbuseReportings);
    }

    /**
     * Method called to associate a PMAbuseReporting object to this object
     * through the PMAbuseReporting foreign key attribute.
     *
     * @param    PMAbuseReporting $l PMAbuseReporting
     * @return PUser The current object (for fluent API support)
     */
    public function addPMAbuseReporting(PMAbuseReporting $l)
    {
        if ($this->collPMAbuseReportings === null) {
            $this->initPMAbuseReportings();
            $this->collPMAbuseReportingsPartial = true;
        }

        if (!in_array($l, $this->collPMAbuseReportings->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddPMAbuseReporting($l);

            if ($this->pMAbuseReportingsScheduledForDeletion and $this->pMAbuseReportingsScheduledForDeletion->contains($l)) {
                $this->pMAbuseReportingsScheduledForDeletion->remove($this->pMAbuseReportingsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	PMAbuseReporting $pMAbuseReporting The pMAbuseReporting object to add.
     */
    protected function doAddPMAbuseReporting($pMAbuseReporting)
    {
        $this->collPMAbuseReportings[]= $pMAbuseReporting;
        $pMAbuseReporting->setPUser($this);
    }

    /**
     * @param	PMAbuseReporting $pMAbuseReporting The pMAbuseReporting object to remove.
     * @return PUser The current object (for fluent API support)
     */
    public function removePMAbuseReporting($pMAbuseReporting)
    {
        if ($this->getPMAbuseReportings()->contains($pMAbuseReporting)) {
            $this->collPMAbuseReportings->remove($this->collPMAbuseReportings->search($pMAbuseReporting));
            if (null === $this->pMAbuseReportingsScheduledForDeletion) {
                $this->pMAbuseReportingsScheduledForDeletion = clone $this->collPMAbuseReportings;
                $this->pMAbuseReportingsScheduledForDeletion->clear();
            }
            $this->pMAbuseReportingsScheduledForDeletion[]= $pMAbuseReporting;
            $pMAbuseReporting->setPUser(null);
        }

        return $this;
    }

    /**
     * Clears out the collPMAppExceptions collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return PUser The current object (for fluent API support)
     * @see        addPMAppExceptions()
     */
    public function clearPMAppExceptions()
    {
        $this->collPMAppExceptions = null; // important to set this to null since that means it is uninitialized
        $this->collPMAppExceptionsPartial = null;

        return $this;
    }

    /**
     * reset is the collPMAppExceptions collection loaded partially
     *
     * @return void
     */
    public function resetPartialPMAppExceptions($v = true)
    {
        $this->collPMAppExceptionsPartial = $v;
    }

    /**
     * Initializes the collPMAppExceptions collection.
     *
     * By default this just sets the collPMAppExceptions collection to an empty array (like clearcollPMAppExceptions());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPMAppExceptions($overrideExisting = true)
    {
        if (null !== $this->collPMAppExceptions && !$overrideExisting) {
            return;
        }
        $this->collPMAppExceptions = new PropelObjectCollection();
        $this->collPMAppExceptions->setModel('PMAppException');
    }

    /**
     * Gets an array of PMAppException objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this PUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|PMAppException[] List of PMAppException objects
     * @throws PropelException
     */
    public function getPMAppExceptions($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collPMAppExceptionsPartial && !$this->isNew();
        if (null === $this->collPMAppExceptions || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPMAppExceptions) {
                // return empty collection
                $this->initPMAppExceptions();
            } else {
                $collPMAppExceptions = PMAppExceptionQuery::create(null, $criteria)
                    ->filterByPUser($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collPMAppExceptionsPartial && count($collPMAppExceptions)) {
                      $this->initPMAppExceptions(false);

                      foreach ($collPMAppExceptions as $obj) {
                        if (false == $this->collPMAppExceptions->contains($obj)) {
                          $this->collPMAppExceptions->append($obj);
                        }
                      }

                      $this->collPMAppExceptionsPartial = true;
                    }

                    $collPMAppExceptions->getInternalIterator()->rewind();

                    return $collPMAppExceptions;
                }

                if ($partial && $this->collPMAppExceptions) {
                    foreach ($this->collPMAppExceptions as $obj) {
                        if ($obj->isNew()) {
                            $collPMAppExceptions[] = $obj;
                        }
                    }
                }

                $this->collPMAppExceptions = $collPMAppExceptions;
                $this->collPMAppExceptionsPartial = false;
            }
        }

        return $this->collPMAppExceptions;
    }

    /**
     * Sets a collection of PMAppException objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $pMAppExceptions A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return PUser The current object (for fluent API support)
     */
    public function setPMAppExceptions(PropelCollection $pMAppExceptions, PropelPDO $con = null)
    {
        $pMAppExceptionsToDelete = $this->getPMAppExceptions(new Criteria(), $con)->diff($pMAppExceptions);


        $this->pMAppExceptionsScheduledForDeletion = $pMAppExceptionsToDelete;

        foreach ($pMAppExceptionsToDelete as $pMAppExceptionRemoved) {
            $pMAppExceptionRemoved->setPUser(null);
        }

        $this->collPMAppExceptions = null;
        foreach ($pMAppExceptions as $pMAppException) {
            $this->addPMAppException($pMAppException);
        }

        $this->collPMAppExceptions = $pMAppExceptions;
        $this->collPMAppExceptionsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related PMAppException objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related PMAppException objects.
     * @throws PropelException
     */
    public function countPMAppExceptions(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collPMAppExceptionsPartial && !$this->isNew();
        if (null === $this->collPMAppExceptions || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPMAppExceptions) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPMAppExceptions());
            }
            $query = PMAppExceptionQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPUser($this)
                ->count($con);
        }

        return count($this->collPMAppExceptions);
    }

    /**
     * Method called to associate a PMAppException object to this object
     * through the PMAppException foreign key attribute.
     *
     * @param    PMAppException $l PMAppException
     * @return PUser The current object (for fluent API support)
     */
    public function addPMAppException(PMAppException $l)
    {
        if ($this->collPMAppExceptions === null) {
            $this->initPMAppExceptions();
            $this->collPMAppExceptionsPartial = true;
        }

        if (!in_array($l, $this->collPMAppExceptions->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddPMAppException($l);

            if ($this->pMAppExceptionsScheduledForDeletion and $this->pMAppExceptionsScheduledForDeletion->contains($l)) {
                $this->pMAppExceptionsScheduledForDeletion->remove($this->pMAppExceptionsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	PMAppException $pMAppException The pMAppException object to add.
     */
    protected function doAddPMAppException($pMAppException)
    {
        $this->collPMAppExceptions[]= $pMAppException;
        $pMAppException->setPUser($this);
    }

    /**
     * @param	PMAppException $pMAppException The pMAppException object to remove.
     * @return PUser The current object (for fluent API support)
     */
    public function removePMAppException($pMAppException)
    {
        if ($this->getPMAppExceptions()->contains($pMAppException)) {
            $this->collPMAppExceptions->remove($this->collPMAppExceptions->search($pMAppException));
            if (null === $this->pMAppExceptionsScheduledForDeletion) {
                $this->pMAppExceptionsScheduledForDeletion = clone $this->collPMAppExceptions;
                $this->pMAppExceptionsScheduledForDeletion->clear();
            }
            $this->pMAppExceptionsScheduledForDeletion[]= $pMAppException;
            $pMAppException->setPUser(null);
        }

        return $this;
    }

    /**
     * Clears out the collPMEmailings collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return PUser The current object (for fluent API support)
     * @see        addPMEmailings()
     */
    public function clearPMEmailings()
    {
        $this->collPMEmailings = null; // important to set this to null since that means it is uninitialized
        $this->collPMEmailingsPartial = null;

        return $this;
    }

    /**
     * reset is the collPMEmailings collection loaded partially
     *
     * @return void
     */
    public function resetPartialPMEmailings($v = true)
    {
        $this->collPMEmailingsPartial = $v;
    }

    /**
     * Initializes the collPMEmailings collection.
     *
     * By default this just sets the collPMEmailings collection to an empty array (like clearcollPMEmailings());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPMEmailings($overrideExisting = true)
    {
        if (null !== $this->collPMEmailings && !$overrideExisting) {
            return;
        }
        $this->collPMEmailings = new PropelObjectCollection();
        $this->collPMEmailings->setModel('PMEmailing');
    }

    /**
     * Gets an array of PMEmailing objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this PUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|PMEmailing[] List of PMEmailing objects
     * @throws PropelException
     */
    public function getPMEmailings($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collPMEmailingsPartial && !$this->isNew();
        if (null === $this->collPMEmailings || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPMEmailings) {
                // return empty collection
                $this->initPMEmailings();
            } else {
                $collPMEmailings = PMEmailingQuery::create(null, $criteria)
                    ->filterByPUser($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collPMEmailingsPartial && count($collPMEmailings)) {
                      $this->initPMEmailings(false);

                      foreach ($collPMEmailings as $obj) {
                        if (false == $this->collPMEmailings->contains($obj)) {
                          $this->collPMEmailings->append($obj);
                        }
                      }

                      $this->collPMEmailingsPartial = true;
                    }

                    $collPMEmailings->getInternalIterator()->rewind();

                    return $collPMEmailings;
                }

                if ($partial && $this->collPMEmailings) {
                    foreach ($this->collPMEmailings as $obj) {
                        if ($obj->isNew()) {
                            $collPMEmailings[] = $obj;
                        }
                    }
                }

                $this->collPMEmailings = $collPMEmailings;
                $this->collPMEmailingsPartial = false;
            }
        }

        return $this->collPMEmailings;
    }

    /**
     * Sets a collection of PMEmailing objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $pMEmailings A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return PUser The current object (for fluent API support)
     */
    public function setPMEmailings(PropelCollection $pMEmailings, PropelPDO $con = null)
    {
        $pMEmailingsToDelete = $this->getPMEmailings(new Criteria(), $con)->diff($pMEmailings);


        $this->pMEmailingsScheduledForDeletion = $pMEmailingsToDelete;

        foreach ($pMEmailingsToDelete as $pMEmailingRemoved) {
            $pMEmailingRemoved->setPUser(null);
        }

        $this->collPMEmailings = null;
        foreach ($pMEmailings as $pMEmailing) {
            $this->addPMEmailing($pMEmailing);
        }

        $this->collPMEmailings = $pMEmailings;
        $this->collPMEmailingsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related PMEmailing objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related PMEmailing objects.
     * @throws PropelException
     */
    public function countPMEmailings(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collPMEmailingsPartial && !$this->isNew();
        if (null === $this->collPMEmailings || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPMEmailings) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPMEmailings());
            }
            $query = PMEmailingQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPUser($this)
                ->count($con);
        }

        return count($this->collPMEmailings);
    }

    /**
     * Method called to associate a PMEmailing object to this object
     * through the PMEmailing foreign key attribute.
     *
     * @param    PMEmailing $l PMEmailing
     * @return PUser The current object (for fluent API support)
     */
    public function addPMEmailing(PMEmailing $l)
    {
        if ($this->collPMEmailings === null) {
            $this->initPMEmailings();
            $this->collPMEmailingsPartial = true;
        }

        if (!in_array($l, $this->collPMEmailings->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddPMEmailing($l);

            if ($this->pMEmailingsScheduledForDeletion and $this->pMEmailingsScheduledForDeletion->contains($l)) {
                $this->pMEmailingsScheduledForDeletion->remove($this->pMEmailingsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	PMEmailing $pMEmailing The pMEmailing object to add.
     */
    protected function doAddPMEmailing($pMEmailing)
    {
        $this->collPMEmailings[]= $pMEmailing;
        $pMEmailing->setPUser($this);
    }

    /**
     * @param	PMEmailing $pMEmailing The pMEmailing object to remove.
     * @return PUser The current object (for fluent API support)
     */
    public function removePMEmailing($pMEmailing)
    {
        if ($this->getPMEmailings()->contains($pMEmailing)) {
            $this->collPMEmailings->remove($this->collPMEmailings->search($pMEmailing));
            if (null === $this->pMEmailingsScheduledForDeletion) {
                $this->pMEmailingsScheduledForDeletion = clone $this->collPMEmailings;
                $this->pMEmailingsScheduledForDeletion->clear();
            }
            $this->pMEmailingsScheduledForDeletion[]= $pMEmailing;
            $pMEmailing->setPUser(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this PUser is new, it will return
     * an empty collection; or if this PUser has previously
     * been saved, it will retrieve related PMEmailings from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in PUser.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|PMEmailing[] List of PMEmailing objects
     */
    public function getPMEmailingsJoinPNEmail($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = PMEmailingQuery::create(null, $criteria);
        $query->joinWith('PNEmail', $join_behavior);

        return $this->getPMEmailings($query, $con);
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
     * Clears out the collPUTrackUsRelatedByPUserIdSource collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return PUser The current object (for fluent API support)
     * @see        addPUTrackUsRelatedByPUserIdSource()
     */
    public function clearPUTrackUsRelatedByPUserIdSource()
    {
        $this->collPUTrackUsRelatedByPUserIdSource = null; // important to set this to null since that means it is uninitialized
        $this->collPUTrackUsRelatedByPUserIdSourcePartial = null;

        return $this;
    }

    /**
     * reset is the collPUTrackUsRelatedByPUserIdSource collection loaded partially
     *
     * @return void
     */
    public function resetPartialPUTrackUsRelatedByPUserIdSource($v = true)
    {
        $this->collPUTrackUsRelatedByPUserIdSourcePartial = $v;
    }

    /**
     * Initializes the collPUTrackUsRelatedByPUserIdSource collection.
     *
     * By default this just sets the collPUTrackUsRelatedByPUserIdSource collection to an empty array (like clearcollPUTrackUsRelatedByPUserIdSource());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPUTrackUsRelatedByPUserIdSource($overrideExisting = true)
    {
        if (null !== $this->collPUTrackUsRelatedByPUserIdSource && !$overrideExisting) {
            return;
        }
        $this->collPUTrackUsRelatedByPUserIdSource = new PropelObjectCollection();
        $this->collPUTrackUsRelatedByPUserIdSource->setModel('PUTrackU');
    }

    /**
     * Gets an array of PUTrackU objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this PUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|PUTrackU[] List of PUTrackU objects
     * @throws PropelException
     */
    public function getPUTrackUsRelatedByPUserIdSource($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collPUTrackUsRelatedByPUserIdSourcePartial && !$this->isNew();
        if (null === $this->collPUTrackUsRelatedByPUserIdSource || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPUTrackUsRelatedByPUserIdSource) {
                // return empty collection
                $this->initPUTrackUsRelatedByPUserIdSource();
            } else {
                $collPUTrackUsRelatedByPUserIdSource = PUTrackUQuery::create(null, $criteria)
                    ->filterByPUserRelatedByPUserIdSource($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collPUTrackUsRelatedByPUserIdSourcePartial && count($collPUTrackUsRelatedByPUserIdSource)) {
                      $this->initPUTrackUsRelatedByPUserIdSource(false);

                      foreach ($collPUTrackUsRelatedByPUserIdSource as $obj) {
                        if (false == $this->collPUTrackUsRelatedByPUserIdSource->contains($obj)) {
                          $this->collPUTrackUsRelatedByPUserIdSource->append($obj);
                        }
                      }

                      $this->collPUTrackUsRelatedByPUserIdSourcePartial = true;
                    }

                    $collPUTrackUsRelatedByPUserIdSource->getInternalIterator()->rewind();

                    return $collPUTrackUsRelatedByPUserIdSource;
                }

                if ($partial && $this->collPUTrackUsRelatedByPUserIdSource) {
                    foreach ($this->collPUTrackUsRelatedByPUserIdSource as $obj) {
                        if ($obj->isNew()) {
                            $collPUTrackUsRelatedByPUserIdSource[] = $obj;
                        }
                    }
                }

                $this->collPUTrackUsRelatedByPUserIdSource = $collPUTrackUsRelatedByPUserIdSource;
                $this->collPUTrackUsRelatedByPUserIdSourcePartial = false;
            }
        }

        return $this->collPUTrackUsRelatedByPUserIdSource;
    }

    /**
     * Sets a collection of PUTrackURelatedByPUserIdSource objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $pUTrackUsRelatedByPUserIdSource A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return PUser The current object (for fluent API support)
     */
    public function setPUTrackUsRelatedByPUserIdSource(PropelCollection $pUTrackUsRelatedByPUserIdSource, PropelPDO $con = null)
    {
        $pUTrackUsRelatedByPUserIdSourceToDelete = $this->getPUTrackUsRelatedByPUserIdSource(new Criteria(), $con)->diff($pUTrackUsRelatedByPUserIdSource);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->pUTrackUsRelatedByPUserIdSourceScheduledForDeletion = clone $pUTrackUsRelatedByPUserIdSourceToDelete;

        foreach ($pUTrackUsRelatedByPUserIdSourceToDelete as $pUTrackURelatedByPUserIdSourceRemoved) {
            $pUTrackURelatedByPUserIdSourceRemoved->setPUserRelatedByPUserIdSource(null);
        }

        $this->collPUTrackUsRelatedByPUserIdSource = null;
        foreach ($pUTrackUsRelatedByPUserIdSource as $pUTrackURelatedByPUserIdSource) {
            $this->addPUTrackURelatedByPUserIdSource($pUTrackURelatedByPUserIdSource);
        }

        $this->collPUTrackUsRelatedByPUserIdSource = $pUTrackUsRelatedByPUserIdSource;
        $this->collPUTrackUsRelatedByPUserIdSourcePartial = false;

        return $this;
    }

    /**
     * Returns the number of related PUTrackU objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related PUTrackU objects.
     * @throws PropelException
     */
    public function countPUTrackUsRelatedByPUserIdSource(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collPUTrackUsRelatedByPUserIdSourcePartial && !$this->isNew();
        if (null === $this->collPUTrackUsRelatedByPUserIdSource || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPUTrackUsRelatedByPUserIdSource) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPUTrackUsRelatedByPUserIdSource());
            }
            $query = PUTrackUQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPUserRelatedByPUserIdSource($this)
                ->count($con);
        }

        return count($this->collPUTrackUsRelatedByPUserIdSource);
    }

    /**
     * Method called to associate a PUTrackU object to this object
     * through the PUTrackU foreign key attribute.
     *
     * @param    PUTrackU $l PUTrackU
     * @return PUser The current object (for fluent API support)
     */
    public function addPUTrackURelatedByPUserIdSource(PUTrackU $l)
    {
        if ($this->collPUTrackUsRelatedByPUserIdSource === null) {
            $this->initPUTrackUsRelatedByPUserIdSource();
            $this->collPUTrackUsRelatedByPUserIdSourcePartial = true;
        }

        if (!in_array($l, $this->collPUTrackUsRelatedByPUserIdSource->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddPUTrackURelatedByPUserIdSource($l);

            if ($this->pUTrackUsRelatedByPUserIdSourceScheduledForDeletion and $this->pUTrackUsRelatedByPUserIdSourceScheduledForDeletion->contains($l)) {
                $this->pUTrackUsRelatedByPUserIdSourceScheduledForDeletion->remove($this->pUTrackUsRelatedByPUserIdSourceScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	PUTrackURelatedByPUserIdSource $pUTrackURelatedByPUserIdSource The pUTrackURelatedByPUserIdSource object to add.
     */
    protected function doAddPUTrackURelatedByPUserIdSource($pUTrackURelatedByPUserIdSource)
    {
        $this->collPUTrackUsRelatedByPUserIdSource[]= $pUTrackURelatedByPUserIdSource;
        $pUTrackURelatedByPUserIdSource->setPUserRelatedByPUserIdSource($this);
    }

    /**
     * @param	PUTrackURelatedByPUserIdSource $pUTrackURelatedByPUserIdSource The pUTrackURelatedByPUserIdSource object to remove.
     * @return PUser The current object (for fluent API support)
     */
    public function removePUTrackURelatedByPUserIdSource($pUTrackURelatedByPUserIdSource)
    {
        if ($this->getPUTrackUsRelatedByPUserIdSource()->contains($pUTrackURelatedByPUserIdSource)) {
            $this->collPUTrackUsRelatedByPUserIdSource->remove($this->collPUTrackUsRelatedByPUserIdSource->search($pUTrackURelatedByPUserIdSource));
            if (null === $this->pUTrackUsRelatedByPUserIdSourceScheduledForDeletion) {
                $this->pUTrackUsRelatedByPUserIdSourceScheduledForDeletion = clone $this->collPUTrackUsRelatedByPUserIdSource;
                $this->pUTrackUsRelatedByPUserIdSourceScheduledForDeletion->clear();
            }
            $this->pUTrackUsRelatedByPUserIdSourceScheduledForDeletion[]= clone $pUTrackURelatedByPUserIdSource;
            $pUTrackURelatedByPUserIdSource->setPUserRelatedByPUserIdSource(null);
        }

        return $this;
    }

    /**
     * Clears out the collPUTrackUsRelatedByPUserIdDest collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return PUser The current object (for fluent API support)
     * @see        addPUTrackUsRelatedByPUserIdDest()
     */
    public function clearPUTrackUsRelatedByPUserIdDest()
    {
        $this->collPUTrackUsRelatedByPUserIdDest = null; // important to set this to null since that means it is uninitialized
        $this->collPUTrackUsRelatedByPUserIdDestPartial = null;

        return $this;
    }

    /**
     * reset is the collPUTrackUsRelatedByPUserIdDest collection loaded partially
     *
     * @return void
     */
    public function resetPartialPUTrackUsRelatedByPUserIdDest($v = true)
    {
        $this->collPUTrackUsRelatedByPUserIdDestPartial = $v;
    }

    /**
     * Initializes the collPUTrackUsRelatedByPUserIdDest collection.
     *
     * By default this just sets the collPUTrackUsRelatedByPUserIdDest collection to an empty array (like clearcollPUTrackUsRelatedByPUserIdDest());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPUTrackUsRelatedByPUserIdDest($overrideExisting = true)
    {
        if (null !== $this->collPUTrackUsRelatedByPUserIdDest && !$overrideExisting) {
            return;
        }
        $this->collPUTrackUsRelatedByPUserIdDest = new PropelObjectCollection();
        $this->collPUTrackUsRelatedByPUserIdDest->setModel('PUTrackU');
    }

    /**
     * Gets an array of PUTrackU objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this PUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|PUTrackU[] List of PUTrackU objects
     * @throws PropelException
     */
    public function getPUTrackUsRelatedByPUserIdDest($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collPUTrackUsRelatedByPUserIdDestPartial && !$this->isNew();
        if (null === $this->collPUTrackUsRelatedByPUserIdDest || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPUTrackUsRelatedByPUserIdDest) {
                // return empty collection
                $this->initPUTrackUsRelatedByPUserIdDest();
            } else {
                $collPUTrackUsRelatedByPUserIdDest = PUTrackUQuery::create(null, $criteria)
                    ->filterByPUserRelatedByPUserIdDest($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collPUTrackUsRelatedByPUserIdDestPartial && count($collPUTrackUsRelatedByPUserIdDest)) {
                      $this->initPUTrackUsRelatedByPUserIdDest(false);

                      foreach ($collPUTrackUsRelatedByPUserIdDest as $obj) {
                        if (false == $this->collPUTrackUsRelatedByPUserIdDest->contains($obj)) {
                          $this->collPUTrackUsRelatedByPUserIdDest->append($obj);
                        }
                      }

                      $this->collPUTrackUsRelatedByPUserIdDestPartial = true;
                    }

                    $collPUTrackUsRelatedByPUserIdDest->getInternalIterator()->rewind();

                    return $collPUTrackUsRelatedByPUserIdDest;
                }

                if ($partial && $this->collPUTrackUsRelatedByPUserIdDest) {
                    foreach ($this->collPUTrackUsRelatedByPUserIdDest as $obj) {
                        if ($obj->isNew()) {
                            $collPUTrackUsRelatedByPUserIdDest[] = $obj;
                        }
                    }
                }

                $this->collPUTrackUsRelatedByPUserIdDest = $collPUTrackUsRelatedByPUserIdDest;
                $this->collPUTrackUsRelatedByPUserIdDestPartial = false;
            }
        }

        return $this->collPUTrackUsRelatedByPUserIdDest;
    }

    /**
     * Sets a collection of PUTrackURelatedByPUserIdDest objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $pUTrackUsRelatedByPUserIdDest A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return PUser The current object (for fluent API support)
     */
    public function setPUTrackUsRelatedByPUserIdDest(PropelCollection $pUTrackUsRelatedByPUserIdDest, PropelPDO $con = null)
    {
        $pUTrackUsRelatedByPUserIdDestToDelete = $this->getPUTrackUsRelatedByPUserIdDest(new Criteria(), $con)->diff($pUTrackUsRelatedByPUserIdDest);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->pUTrackUsRelatedByPUserIdDestScheduledForDeletion = clone $pUTrackUsRelatedByPUserIdDestToDelete;

        foreach ($pUTrackUsRelatedByPUserIdDestToDelete as $pUTrackURelatedByPUserIdDestRemoved) {
            $pUTrackURelatedByPUserIdDestRemoved->setPUserRelatedByPUserIdDest(null);
        }

        $this->collPUTrackUsRelatedByPUserIdDest = null;
        foreach ($pUTrackUsRelatedByPUserIdDest as $pUTrackURelatedByPUserIdDest) {
            $this->addPUTrackURelatedByPUserIdDest($pUTrackURelatedByPUserIdDest);
        }

        $this->collPUTrackUsRelatedByPUserIdDest = $pUTrackUsRelatedByPUserIdDest;
        $this->collPUTrackUsRelatedByPUserIdDestPartial = false;

        return $this;
    }

    /**
     * Returns the number of related PUTrackU objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related PUTrackU objects.
     * @throws PropelException
     */
    public function countPUTrackUsRelatedByPUserIdDest(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collPUTrackUsRelatedByPUserIdDestPartial && !$this->isNew();
        if (null === $this->collPUTrackUsRelatedByPUserIdDest || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPUTrackUsRelatedByPUserIdDest) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPUTrackUsRelatedByPUserIdDest());
            }
            $query = PUTrackUQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPUserRelatedByPUserIdDest($this)
                ->count($con);
        }

        return count($this->collPUTrackUsRelatedByPUserIdDest);
    }

    /**
     * Method called to associate a PUTrackU object to this object
     * through the PUTrackU foreign key attribute.
     *
     * @param    PUTrackU $l PUTrackU
     * @return PUser The current object (for fluent API support)
     */
    public function addPUTrackURelatedByPUserIdDest(PUTrackU $l)
    {
        if ($this->collPUTrackUsRelatedByPUserIdDest === null) {
            $this->initPUTrackUsRelatedByPUserIdDest();
            $this->collPUTrackUsRelatedByPUserIdDestPartial = true;
        }

        if (!in_array($l, $this->collPUTrackUsRelatedByPUserIdDest->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddPUTrackURelatedByPUserIdDest($l);

            if ($this->pUTrackUsRelatedByPUserIdDestScheduledForDeletion and $this->pUTrackUsRelatedByPUserIdDestScheduledForDeletion->contains($l)) {
                $this->pUTrackUsRelatedByPUserIdDestScheduledForDeletion->remove($this->pUTrackUsRelatedByPUserIdDestScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	PUTrackURelatedByPUserIdDest $pUTrackURelatedByPUserIdDest The pUTrackURelatedByPUserIdDest object to add.
     */
    protected function doAddPUTrackURelatedByPUserIdDest($pUTrackURelatedByPUserIdDest)
    {
        $this->collPUTrackUsRelatedByPUserIdDest[]= $pUTrackURelatedByPUserIdDest;
        $pUTrackURelatedByPUserIdDest->setPUserRelatedByPUserIdDest($this);
    }

    /**
     * @param	PUTrackURelatedByPUserIdDest $pUTrackURelatedByPUserIdDest The pUTrackURelatedByPUserIdDest object to remove.
     * @return PUser The current object (for fluent API support)
     */
    public function removePUTrackURelatedByPUserIdDest($pUTrackURelatedByPUserIdDest)
    {
        if ($this->getPUTrackUsRelatedByPUserIdDest()->contains($pUTrackURelatedByPUserIdDest)) {
            $this->collPUTrackUsRelatedByPUserIdDest->remove($this->collPUTrackUsRelatedByPUserIdDest->search($pUTrackURelatedByPUserIdDest));
            if (null === $this->pUTrackUsRelatedByPUserIdDestScheduledForDeletion) {
                $this->pUTrackUsRelatedByPUserIdDestScheduledForDeletion = clone $this->collPUTrackUsRelatedByPUserIdDest;
                $this->pUTrackUsRelatedByPUserIdDestScheduledForDeletion->clear();
            }
            $this->pUTrackUsRelatedByPUserIdDestScheduledForDeletion[]= clone $pUTrackURelatedByPUserIdDest;
            $pUTrackURelatedByPUserIdDest->setPUserRelatedByPUserIdDest(null);
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
     * Clears out the collPuBookmarkDdPDDebates collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return PUser The current object (for fluent API support)
     * @see        addPuBookmarkDdPDDebates()
     */
    public function clearPuBookmarkDdPDDebates()
    {
        $this->collPuBookmarkDdPDDebates = null; // important to set this to null since that means it is uninitialized
        $this->collPuBookmarkDdPDDebatesPartial = null;

        return $this;
    }

    /**
     * Initializes the collPuBookmarkDdPDDebates collection.
     *
     * By default this just sets the collPuBookmarkDdPDDebates collection to an empty collection (like clearPuBookmarkDdPDDebates());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @return void
     */
    public function initPuBookmarkDdPDDebates()
    {
        $this->collPuBookmarkDdPDDebates = new PropelObjectCollection();
        $this->collPuBookmarkDdPDDebates->setModel('PDDebate');
    }

    /**
     * Gets a collection of PDDebate objects related by a many-to-many relationship
     * to the current object by way of the p_u_bookmark_d_d cross-reference table.
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
    public function getPuBookmarkDdPDDebates($criteria = null, PropelPDO $con = null)
    {
        if (null === $this->collPuBookmarkDdPDDebates || null !== $criteria) {
            if ($this->isNew() && null === $this->collPuBookmarkDdPDDebates) {
                // return empty collection
                $this->initPuBookmarkDdPDDebates();
            } else {
                $collPuBookmarkDdPDDebates = PDDebateQuery::create(null, $criteria)
                    ->filterByPuBookmarkDdPUser($this)
                    ->find($con);
                if (null !== $criteria) {
                    return $collPuBookmarkDdPDDebates;
                }
                $this->collPuBookmarkDdPDDebates = $collPuBookmarkDdPDDebates;
            }
        }

        return $this->collPuBookmarkDdPDDebates;
    }

    /**
     * Sets a collection of PDDebate objects related by a many-to-many relationship
     * to the current object by way of the p_u_bookmark_d_d cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $puBookmarkDdPDDebates A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return PUser The current object (for fluent API support)
     */
    public function setPuBookmarkDdPDDebates(PropelCollection $puBookmarkDdPDDebates, PropelPDO $con = null)
    {
        $this->clearPuBookmarkDdPDDebates();
        $currentPuBookmarkDdPDDebates = $this->getPuBookmarkDdPDDebates(null, $con);

        $this->puBookmarkDdPDDebatesScheduledForDeletion = $currentPuBookmarkDdPDDebates->diff($puBookmarkDdPDDebates);

        foreach ($puBookmarkDdPDDebates as $puBookmarkDdPDDebate) {
            if (!$currentPuBookmarkDdPDDebates->contains($puBookmarkDdPDDebate)) {
                $this->doAddPuBookmarkDdPDDebate($puBookmarkDdPDDebate);
            }
        }

        $this->collPuBookmarkDdPDDebates = $puBookmarkDdPDDebates;

        return $this;
    }

    /**
     * Gets the number of PDDebate objects related by a many-to-many relationship
     * to the current object by way of the p_u_bookmark_d_d cross-reference table.
     *
     * @param Criteria $criteria Optional query object to filter the query
     * @param boolean $distinct Set to true to force count distinct
     * @param PropelPDO $con Optional connection object
     *
     * @return int the number of related PDDebate objects
     */
    public function countPuBookmarkDdPDDebates($criteria = null, $distinct = false, PropelPDO $con = null)
    {
        if (null === $this->collPuBookmarkDdPDDebates || null !== $criteria) {
            if ($this->isNew() && null === $this->collPuBookmarkDdPDDebates) {
                return 0;
            } else {
                $query = PDDebateQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByPuBookmarkDdPUser($this)
                    ->count($con);
            }
        } else {
            return count($this->collPuBookmarkDdPDDebates);
        }
    }

    /**
     * Associate a PDDebate object to this object
     * through the p_u_bookmark_d_d cross reference table.
     *
     * @param  PDDebate $pDDebate The PUBookmarkDD object to relate
     * @return PUser The current object (for fluent API support)
     */
    public function addPuBookmarkDdPDDebate(PDDebate $pDDebate)
    {
        if ($this->collPuBookmarkDdPDDebates === null) {
            $this->initPuBookmarkDdPDDebates();
        }

        if (!$this->collPuBookmarkDdPDDebates->contains($pDDebate)) { // only add it if the **same** object is not already associated
            $this->doAddPuBookmarkDdPDDebate($pDDebate);
            $this->collPuBookmarkDdPDDebates[] = $pDDebate;

            if ($this->puBookmarkDdPDDebatesScheduledForDeletion and $this->puBookmarkDdPDDebatesScheduledForDeletion->contains($pDDebate)) {
                $this->puBookmarkDdPDDebatesScheduledForDeletion->remove($this->puBookmarkDdPDDebatesScheduledForDeletion->search($pDDebate));
            }
        }

        return $this;
    }

    /**
     * @param	PuBookmarkDdPDDebate $puBookmarkDdPDDebate The puBookmarkDdPDDebate object to add.
     */
    protected function doAddPuBookmarkDdPDDebate(PDDebate $puBookmarkDdPDDebate)
    {
        // set the back reference to this object directly as using provided method either results
        // in endless loop or in multiple relations
        if (!$puBookmarkDdPDDebate->getPuBookmarkDdPUsers()->contains($this)) { $pUBookmarkDD = new PUBookmarkDD();
            $pUBookmarkDD->setPuBookmarkDdPDDebate($puBookmarkDdPDDebate);
            $this->addPuBookmarkDdPUser($pUBookmarkDD);

            $foreignCollection = $puBookmarkDdPDDebate->getPuBookmarkDdPUsers();
            $foreignCollection[] = $this;
        }
    }

    /**
     * Remove a PDDebate object to this object
     * through the p_u_bookmark_d_d cross reference table.
     *
     * @param PDDebate $pDDebate The PUBookmarkDD object to relate
     * @return PUser The current object (for fluent API support)
     */
    public function removePuBookmarkDdPDDebate(PDDebate $pDDebate)
    {
        if ($this->getPuBookmarkDdPDDebates()->contains($pDDebate)) {
            $this->collPuBookmarkDdPDDebates->remove($this->collPuBookmarkDdPDDebates->search($pDDebate));
            if (null === $this->puBookmarkDdPDDebatesScheduledForDeletion) {
                $this->puBookmarkDdPDDebatesScheduledForDeletion = clone $this->collPuBookmarkDdPDDebates;
                $this->puBookmarkDdPDDebatesScheduledForDeletion->clear();
            }
            $this->puBookmarkDdPDDebatesScheduledForDeletion[]= $pDDebate;
        }

        return $this;
    }

    /**
     * Clears out the collPuBookmarkDrPDReactions collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return PUser The current object (for fluent API support)
     * @see        addPuBookmarkDrPDReactions()
     */
    public function clearPuBookmarkDrPDReactions()
    {
        $this->collPuBookmarkDrPDReactions = null; // important to set this to null since that means it is uninitialized
        $this->collPuBookmarkDrPDReactionsPartial = null;

        return $this;
    }

    /**
     * Initializes the collPuBookmarkDrPDReactions collection.
     *
     * By default this just sets the collPuBookmarkDrPDReactions collection to an empty collection (like clearPuBookmarkDrPDReactions());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @return void
     */
    public function initPuBookmarkDrPDReactions()
    {
        $this->collPuBookmarkDrPDReactions = new PropelObjectCollection();
        $this->collPuBookmarkDrPDReactions->setModel('PDReaction');
    }

    /**
     * Gets a collection of PDReaction objects related by a many-to-many relationship
     * to the current object by way of the p_u_bookmark_d_r cross-reference table.
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
     * @return PropelObjectCollection|PDReaction[] List of PDReaction objects
     */
    public function getPuBookmarkDrPDReactions($criteria = null, PropelPDO $con = null)
    {
        if (null === $this->collPuBookmarkDrPDReactions || null !== $criteria) {
            if ($this->isNew() && null === $this->collPuBookmarkDrPDReactions) {
                // return empty collection
                $this->initPuBookmarkDrPDReactions();
            } else {
                $collPuBookmarkDrPDReactions = PDReactionQuery::create(null, $criteria)
                    ->filterByPuBookmarkDrPUser($this)
                    ->find($con);
                if (null !== $criteria) {
                    return $collPuBookmarkDrPDReactions;
                }
                $this->collPuBookmarkDrPDReactions = $collPuBookmarkDrPDReactions;
            }
        }

        return $this->collPuBookmarkDrPDReactions;
    }

    /**
     * Sets a collection of PDReaction objects related by a many-to-many relationship
     * to the current object by way of the p_u_bookmark_d_r cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $puBookmarkDrPDReactions A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return PUser The current object (for fluent API support)
     */
    public function setPuBookmarkDrPDReactions(PropelCollection $puBookmarkDrPDReactions, PropelPDO $con = null)
    {
        $this->clearPuBookmarkDrPDReactions();
        $currentPuBookmarkDrPDReactions = $this->getPuBookmarkDrPDReactions(null, $con);

        $this->puBookmarkDrPDReactionsScheduledForDeletion = $currentPuBookmarkDrPDReactions->diff($puBookmarkDrPDReactions);

        foreach ($puBookmarkDrPDReactions as $puBookmarkDrPDReaction) {
            if (!$currentPuBookmarkDrPDReactions->contains($puBookmarkDrPDReaction)) {
                $this->doAddPuBookmarkDrPDReaction($puBookmarkDrPDReaction);
            }
        }

        $this->collPuBookmarkDrPDReactions = $puBookmarkDrPDReactions;

        return $this;
    }

    /**
     * Gets the number of PDReaction objects related by a many-to-many relationship
     * to the current object by way of the p_u_bookmark_d_r cross-reference table.
     *
     * @param Criteria $criteria Optional query object to filter the query
     * @param boolean $distinct Set to true to force count distinct
     * @param PropelPDO $con Optional connection object
     *
     * @return int the number of related PDReaction objects
     */
    public function countPuBookmarkDrPDReactions($criteria = null, $distinct = false, PropelPDO $con = null)
    {
        if (null === $this->collPuBookmarkDrPDReactions || null !== $criteria) {
            if ($this->isNew() && null === $this->collPuBookmarkDrPDReactions) {
                return 0;
            } else {
                $query = PDReactionQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByPuBookmarkDrPUser($this)
                    ->count($con);
            }
        } else {
            return count($this->collPuBookmarkDrPDReactions);
        }
    }

    /**
     * Associate a PDReaction object to this object
     * through the p_u_bookmark_d_r cross reference table.
     *
     * @param  PDReaction $pDReaction The PUBookmarkDR object to relate
     * @return PUser The current object (for fluent API support)
     */
    public function addPuBookmarkDrPDReaction(PDReaction $pDReaction)
    {
        if ($this->collPuBookmarkDrPDReactions === null) {
            $this->initPuBookmarkDrPDReactions();
        }

        if (!$this->collPuBookmarkDrPDReactions->contains($pDReaction)) { // only add it if the **same** object is not already associated
            $this->doAddPuBookmarkDrPDReaction($pDReaction);
            $this->collPuBookmarkDrPDReactions[] = $pDReaction;

            if ($this->puBookmarkDrPDReactionsScheduledForDeletion and $this->puBookmarkDrPDReactionsScheduledForDeletion->contains($pDReaction)) {
                $this->puBookmarkDrPDReactionsScheduledForDeletion->remove($this->puBookmarkDrPDReactionsScheduledForDeletion->search($pDReaction));
            }
        }

        return $this;
    }

    /**
     * @param	PuBookmarkDrPDReaction $puBookmarkDrPDReaction The puBookmarkDrPDReaction object to add.
     */
    protected function doAddPuBookmarkDrPDReaction(PDReaction $puBookmarkDrPDReaction)
    {
        // set the back reference to this object directly as using provided method either results
        // in endless loop or in multiple relations
        if (!$puBookmarkDrPDReaction->getPuBookmarkDrPUsers()->contains($this)) { $pUBookmarkDR = new PUBookmarkDR();
            $pUBookmarkDR->setPuBookmarkDrPDReaction($puBookmarkDrPDReaction);
            $this->addPuBookmarkDrPUser($pUBookmarkDR);

            $foreignCollection = $puBookmarkDrPDReaction->getPuBookmarkDrPUsers();
            $foreignCollection[] = $this;
        }
    }

    /**
     * Remove a PDReaction object to this object
     * through the p_u_bookmark_d_r cross reference table.
     *
     * @param PDReaction $pDReaction The PUBookmarkDR object to relate
     * @return PUser The current object (for fluent API support)
     */
    public function removePuBookmarkDrPDReaction(PDReaction $pDReaction)
    {
        if ($this->getPuBookmarkDrPDReactions()->contains($pDReaction)) {
            $this->collPuBookmarkDrPDReactions->remove($this->collPuBookmarkDrPDReactions->search($pDReaction));
            if (null === $this->puBookmarkDrPDReactionsScheduledForDeletion) {
                $this->puBookmarkDrPDReactionsScheduledForDeletion = clone $this->collPuBookmarkDrPDReactions;
                $this->puBookmarkDrPDReactionsScheduledForDeletion->clear();
            }
            $this->puBookmarkDrPDReactionsScheduledForDeletion[]= $pDReaction;
        }

        return $this;
    }

    /**
     * Clears out the collPuTrackDdPDDebates collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return PUser The current object (for fluent API support)
     * @see        addPuTrackDdPDDebates()
     */
    public function clearPuTrackDdPDDebates()
    {
        $this->collPuTrackDdPDDebates = null; // important to set this to null since that means it is uninitialized
        $this->collPuTrackDdPDDebatesPartial = null;

        return $this;
    }

    /**
     * Initializes the collPuTrackDdPDDebates collection.
     *
     * By default this just sets the collPuTrackDdPDDebates collection to an empty collection (like clearPuTrackDdPDDebates());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @return void
     */
    public function initPuTrackDdPDDebates()
    {
        $this->collPuTrackDdPDDebates = new PropelObjectCollection();
        $this->collPuTrackDdPDDebates->setModel('PDDebate');
    }

    /**
     * Gets a collection of PDDebate objects related by a many-to-many relationship
     * to the current object by way of the p_u_track_d_d cross-reference table.
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
    public function getPuTrackDdPDDebates($criteria = null, PropelPDO $con = null)
    {
        if (null === $this->collPuTrackDdPDDebates || null !== $criteria) {
            if ($this->isNew() && null === $this->collPuTrackDdPDDebates) {
                // return empty collection
                $this->initPuTrackDdPDDebates();
            } else {
                $collPuTrackDdPDDebates = PDDebateQuery::create(null, $criteria)
                    ->filterByPuTrackDdPUser($this)
                    ->find($con);
                if (null !== $criteria) {
                    return $collPuTrackDdPDDebates;
                }
                $this->collPuTrackDdPDDebates = $collPuTrackDdPDDebates;
            }
        }

        return $this->collPuTrackDdPDDebates;
    }

    /**
     * Sets a collection of PDDebate objects related by a many-to-many relationship
     * to the current object by way of the p_u_track_d_d cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $puTrackDdPDDebates A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return PUser The current object (for fluent API support)
     */
    public function setPuTrackDdPDDebates(PropelCollection $puTrackDdPDDebates, PropelPDO $con = null)
    {
        $this->clearPuTrackDdPDDebates();
        $currentPuTrackDdPDDebates = $this->getPuTrackDdPDDebates(null, $con);

        $this->puTrackDdPDDebatesScheduledForDeletion = $currentPuTrackDdPDDebates->diff($puTrackDdPDDebates);

        foreach ($puTrackDdPDDebates as $puTrackDdPDDebate) {
            if (!$currentPuTrackDdPDDebates->contains($puTrackDdPDDebate)) {
                $this->doAddPuTrackDdPDDebate($puTrackDdPDDebate);
            }
        }

        $this->collPuTrackDdPDDebates = $puTrackDdPDDebates;

        return $this;
    }

    /**
     * Gets the number of PDDebate objects related by a many-to-many relationship
     * to the current object by way of the p_u_track_d_d cross-reference table.
     *
     * @param Criteria $criteria Optional query object to filter the query
     * @param boolean $distinct Set to true to force count distinct
     * @param PropelPDO $con Optional connection object
     *
     * @return int the number of related PDDebate objects
     */
    public function countPuTrackDdPDDebates($criteria = null, $distinct = false, PropelPDO $con = null)
    {
        if (null === $this->collPuTrackDdPDDebates || null !== $criteria) {
            if ($this->isNew() && null === $this->collPuTrackDdPDDebates) {
                return 0;
            } else {
                $query = PDDebateQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByPuTrackDdPUser($this)
                    ->count($con);
            }
        } else {
            return count($this->collPuTrackDdPDDebates);
        }
    }

    /**
     * Associate a PDDebate object to this object
     * through the p_u_track_d_d cross reference table.
     *
     * @param  PDDebate $pDDebate The PUTrackDD object to relate
     * @return PUser The current object (for fluent API support)
     */
    public function addPuTrackDdPDDebate(PDDebate $pDDebate)
    {
        if ($this->collPuTrackDdPDDebates === null) {
            $this->initPuTrackDdPDDebates();
        }

        if (!$this->collPuTrackDdPDDebates->contains($pDDebate)) { // only add it if the **same** object is not already associated
            $this->doAddPuTrackDdPDDebate($pDDebate);
            $this->collPuTrackDdPDDebates[] = $pDDebate;

            if ($this->puTrackDdPDDebatesScheduledForDeletion and $this->puTrackDdPDDebatesScheduledForDeletion->contains($pDDebate)) {
                $this->puTrackDdPDDebatesScheduledForDeletion->remove($this->puTrackDdPDDebatesScheduledForDeletion->search($pDDebate));
            }
        }

        return $this;
    }

    /**
     * @param	PuTrackDdPDDebate $puTrackDdPDDebate The puTrackDdPDDebate object to add.
     */
    protected function doAddPuTrackDdPDDebate(PDDebate $puTrackDdPDDebate)
    {
        // set the back reference to this object directly as using provided method either results
        // in endless loop or in multiple relations
        if (!$puTrackDdPDDebate->getPuTrackDdPUsers()->contains($this)) { $pUTrackDD = new PUTrackDD();
            $pUTrackDD->setPuTrackDdPDDebate($puTrackDdPDDebate);
            $this->addPuTrackDdPUser($pUTrackDD);

            $foreignCollection = $puTrackDdPDDebate->getPuTrackDdPUsers();
            $foreignCollection[] = $this;
        }
    }

    /**
     * Remove a PDDebate object to this object
     * through the p_u_track_d_d cross reference table.
     *
     * @param PDDebate $pDDebate The PUTrackDD object to relate
     * @return PUser The current object (for fluent API support)
     */
    public function removePuTrackDdPDDebate(PDDebate $pDDebate)
    {
        if ($this->getPuTrackDdPDDebates()->contains($pDDebate)) {
            $this->collPuTrackDdPDDebates->remove($this->collPuTrackDdPDDebates->search($pDDebate));
            if (null === $this->puTrackDdPDDebatesScheduledForDeletion) {
                $this->puTrackDdPDDebatesScheduledForDeletion = clone $this->collPuTrackDdPDDebates;
                $this->puTrackDdPDDebatesScheduledForDeletion->clear();
            }
            $this->puTrackDdPDDebatesScheduledForDeletion[]= $pDDebate;
        }

        return $this;
    }

    /**
     * Clears out the collPuTrackDrPDReactions collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return PUser The current object (for fluent API support)
     * @see        addPuTrackDrPDReactions()
     */
    public function clearPuTrackDrPDReactions()
    {
        $this->collPuTrackDrPDReactions = null; // important to set this to null since that means it is uninitialized
        $this->collPuTrackDrPDReactionsPartial = null;

        return $this;
    }

    /**
     * Initializes the collPuTrackDrPDReactions collection.
     *
     * By default this just sets the collPuTrackDrPDReactions collection to an empty collection (like clearPuTrackDrPDReactions());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @return void
     */
    public function initPuTrackDrPDReactions()
    {
        $this->collPuTrackDrPDReactions = new PropelObjectCollection();
        $this->collPuTrackDrPDReactions->setModel('PDReaction');
    }

    /**
     * Gets a collection of PDReaction objects related by a many-to-many relationship
     * to the current object by way of the p_u_track_d_r cross-reference table.
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
     * @return PropelObjectCollection|PDReaction[] List of PDReaction objects
     */
    public function getPuTrackDrPDReactions($criteria = null, PropelPDO $con = null)
    {
        if (null === $this->collPuTrackDrPDReactions || null !== $criteria) {
            if ($this->isNew() && null === $this->collPuTrackDrPDReactions) {
                // return empty collection
                $this->initPuTrackDrPDReactions();
            } else {
                $collPuTrackDrPDReactions = PDReactionQuery::create(null, $criteria)
                    ->filterByPuTrackDrPUser($this)
                    ->find($con);
                if (null !== $criteria) {
                    return $collPuTrackDrPDReactions;
                }
                $this->collPuTrackDrPDReactions = $collPuTrackDrPDReactions;
            }
        }

        return $this->collPuTrackDrPDReactions;
    }

    /**
     * Sets a collection of PDReaction objects related by a many-to-many relationship
     * to the current object by way of the p_u_track_d_r cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $puTrackDrPDReactions A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return PUser The current object (for fluent API support)
     */
    public function setPuTrackDrPDReactions(PropelCollection $puTrackDrPDReactions, PropelPDO $con = null)
    {
        $this->clearPuTrackDrPDReactions();
        $currentPuTrackDrPDReactions = $this->getPuTrackDrPDReactions(null, $con);

        $this->puTrackDrPDReactionsScheduledForDeletion = $currentPuTrackDrPDReactions->diff($puTrackDrPDReactions);

        foreach ($puTrackDrPDReactions as $puTrackDrPDReaction) {
            if (!$currentPuTrackDrPDReactions->contains($puTrackDrPDReaction)) {
                $this->doAddPuTrackDrPDReaction($puTrackDrPDReaction);
            }
        }

        $this->collPuTrackDrPDReactions = $puTrackDrPDReactions;

        return $this;
    }

    /**
     * Gets the number of PDReaction objects related by a many-to-many relationship
     * to the current object by way of the p_u_track_d_r cross-reference table.
     *
     * @param Criteria $criteria Optional query object to filter the query
     * @param boolean $distinct Set to true to force count distinct
     * @param PropelPDO $con Optional connection object
     *
     * @return int the number of related PDReaction objects
     */
    public function countPuTrackDrPDReactions($criteria = null, $distinct = false, PropelPDO $con = null)
    {
        if (null === $this->collPuTrackDrPDReactions || null !== $criteria) {
            if ($this->isNew() && null === $this->collPuTrackDrPDReactions) {
                return 0;
            } else {
                $query = PDReactionQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByPuTrackDrPUser($this)
                    ->count($con);
            }
        } else {
            return count($this->collPuTrackDrPDReactions);
        }
    }

    /**
     * Associate a PDReaction object to this object
     * through the p_u_track_d_r cross reference table.
     *
     * @param  PDReaction $pDReaction The PUTrackDR object to relate
     * @return PUser The current object (for fluent API support)
     */
    public function addPuTrackDrPDReaction(PDReaction $pDReaction)
    {
        if ($this->collPuTrackDrPDReactions === null) {
            $this->initPuTrackDrPDReactions();
        }

        if (!$this->collPuTrackDrPDReactions->contains($pDReaction)) { // only add it if the **same** object is not already associated
            $this->doAddPuTrackDrPDReaction($pDReaction);
            $this->collPuTrackDrPDReactions[] = $pDReaction;

            if ($this->puTrackDrPDReactionsScheduledForDeletion and $this->puTrackDrPDReactionsScheduledForDeletion->contains($pDReaction)) {
                $this->puTrackDrPDReactionsScheduledForDeletion->remove($this->puTrackDrPDReactionsScheduledForDeletion->search($pDReaction));
            }
        }

        return $this;
    }

    /**
     * @param	PuTrackDrPDReaction $puTrackDrPDReaction The puTrackDrPDReaction object to add.
     */
    protected function doAddPuTrackDrPDReaction(PDReaction $puTrackDrPDReaction)
    {
        // set the back reference to this object directly as using provided method either results
        // in endless loop or in multiple relations
        if (!$puTrackDrPDReaction->getPuTrackDrPUsers()->contains($this)) { $pUTrackDR = new PUTrackDR();
            $pUTrackDR->setPuTrackDrPDReaction($puTrackDrPDReaction);
            $this->addPuTrackDrPUser($pUTrackDR);

            $foreignCollection = $puTrackDrPDReaction->getPuTrackDrPUsers();
            $foreignCollection[] = $this;
        }
    }

    /**
     * Remove a PDReaction object to this object
     * through the p_u_track_d_r cross reference table.
     *
     * @param PDReaction $pDReaction The PUTrackDR object to relate
     * @return PUser The current object (for fluent API support)
     */
    public function removePuTrackDrPDReaction(PDReaction $pDReaction)
    {
        if ($this->getPuTrackDrPDReactions()->contains($pDReaction)) {
            $this->collPuTrackDrPDReactions->remove($this->collPuTrackDrPDReactions->search($pDReaction));
            if (null === $this->puTrackDrPDReactionsScheduledForDeletion) {
                $this->puTrackDrPDReactionsScheduledForDeletion = clone $this->collPuTrackDrPDReactions;
                $this->puTrackDrPDReactionsScheduledForDeletion->clear();
            }
            $this->puTrackDrPDReactionsScheduledForDeletion[]= $pDReaction;
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
     * to the current object by way of the p_u_badge cross-reference table.
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
     * to the current object by way of the p_u_badge cross-reference table.
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
     * to the current object by way of the p_u_badge cross-reference table.
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
     * through the p_u_badge cross reference table.
     *
     * @param  PRBadge $pRBadge The PUBadge object to relate
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
        if (!$pRBadge->getPUsers()->contains($this)) { $pUBadge = new PUBadge();
            $pUBadge->setPRBadge($pRBadge);
            $this->addPUBadge($pUBadge);

            $foreignCollection = $pRBadge->getPUsers();
            $foreignCollection[] = $this;
        }
    }

    /**
     * Remove a PRBadge object to this object
     * through the p_u_badge cross reference table.
     *
     * @param PRBadge $pRBadge The PUBadge object to relate
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
     * Clears out the collPUAffinityQOPQOrganizations collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return PUser The current object (for fluent API support)
     * @see        addPUAffinityQOPQOrganizations()
     */
    public function clearPUAffinityQOPQOrganizations()
    {
        $this->collPUAffinityQOPQOrganizations = null; // important to set this to null since that means it is uninitialized
        $this->collPUAffinityQOPQOrganizationsPartial = null;

        return $this;
    }

    /**
     * Initializes the collPUAffinityQOPQOrganizations collection.
     *
     * By default this just sets the collPUAffinityQOPQOrganizations collection to an empty collection (like clearPUAffinityQOPQOrganizations());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @return void
     */
    public function initPUAffinityQOPQOrganizations()
    {
        $this->collPUAffinityQOPQOrganizations = new PropelObjectCollection();
        $this->collPUAffinityQOPQOrganizations->setModel('PQOrganization');
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
    public function getPUAffinityQOPQOrganizations($criteria = null, PropelPDO $con = null)
    {
        if (null === $this->collPUAffinityQOPQOrganizations || null !== $criteria) {
            if ($this->isNew() && null === $this->collPUAffinityQOPQOrganizations) {
                // return empty collection
                $this->initPUAffinityQOPQOrganizations();
            } else {
                $collPUAffinityQOPQOrganizations = PQOrganizationQuery::create(null, $criteria)
                    ->filterByPUAffinityQOPUser($this)
                    ->find($con);
                if (null !== $criteria) {
                    return $collPUAffinityQOPQOrganizations;
                }
                $this->collPUAffinityQOPQOrganizations = $collPUAffinityQOPQOrganizations;
            }
        }

        return $this->collPUAffinityQOPQOrganizations;
    }

    /**
     * Sets a collection of PQOrganization objects related by a many-to-many relationship
     * to the current object by way of the p_u_affinity_q_o cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $pUAffinityQOPQOrganizations A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return PUser The current object (for fluent API support)
     */
    public function setPUAffinityQOPQOrganizations(PropelCollection $pUAffinityQOPQOrganizations, PropelPDO $con = null)
    {
        $this->clearPUAffinityQOPQOrganizations();
        $currentPUAffinityQOPQOrganizations = $this->getPUAffinityQOPQOrganizations(null, $con);

        $this->pUAffinityQOPQOrganizationsScheduledForDeletion = $currentPUAffinityQOPQOrganizations->diff($pUAffinityQOPQOrganizations);

        foreach ($pUAffinityQOPQOrganizations as $pUAffinityQOPQOrganization) {
            if (!$currentPUAffinityQOPQOrganizations->contains($pUAffinityQOPQOrganization)) {
                $this->doAddPUAffinityQOPQOrganization($pUAffinityQOPQOrganization);
            }
        }

        $this->collPUAffinityQOPQOrganizations = $pUAffinityQOPQOrganizations;

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
    public function countPUAffinityQOPQOrganizations($criteria = null, $distinct = false, PropelPDO $con = null)
    {
        if (null === $this->collPUAffinityQOPQOrganizations || null !== $criteria) {
            if ($this->isNew() && null === $this->collPUAffinityQOPQOrganizations) {
                return 0;
            } else {
                $query = PQOrganizationQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByPUAffinityQOPUser($this)
                    ->count($con);
            }
        } else {
            return count($this->collPUAffinityQOPQOrganizations);
        }
    }

    /**
     * Associate a PQOrganization object to this object
     * through the p_u_affinity_q_o cross reference table.
     *
     * @param  PQOrganization $pQOrganization The PUAffinityQO object to relate
     * @return PUser The current object (for fluent API support)
     */
    public function addPUAffinityQOPQOrganization(PQOrganization $pQOrganization)
    {
        if ($this->collPUAffinityQOPQOrganizations === null) {
            $this->initPUAffinityQOPQOrganizations();
        }

        if (!$this->collPUAffinityQOPQOrganizations->contains($pQOrganization)) { // only add it if the **same** object is not already associated
            $this->doAddPUAffinityQOPQOrganization($pQOrganization);
            $this->collPUAffinityQOPQOrganizations[] = $pQOrganization;

            if ($this->pUAffinityQOPQOrganizationsScheduledForDeletion and $this->pUAffinityQOPQOrganizationsScheduledForDeletion->contains($pQOrganization)) {
                $this->pUAffinityQOPQOrganizationsScheduledForDeletion->remove($this->pUAffinityQOPQOrganizationsScheduledForDeletion->search($pQOrganization));
            }
        }

        return $this;
    }

    /**
     * @param	PUAffinityQOPQOrganization $pUAffinityQOPQOrganization The pUAffinityQOPQOrganization object to add.
     */
    protected function doAddPUAffinityQOPQOrganization(PQOrganization $pUAffinityQOPQOrganization)
    {
        // set the back reference to this object directly as using provided method either results
        // in endless loop or in multiple relations
        if (!$pUAffinityQOPQOrganization->getPUAffinityQOPUsers()->contains($this)) { $pUAffinityQO = new PUAffinityQO();
            $pUAffinityQO->setPUAffinityQOPQOrganization($pUAffinityQOPQOrganization);
            $this->addPUAffinityQOPUser($pUAffinityQO);

            $foreignCollection = $pUAffinityQOPQOrganization->getPUAffinityQOPUsers();
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
    public function removePUAffinityQOPQOrganization(PQOrganization $pQOrganization)
    {
        if ($this->getPUAffinityQOPQOrganizations()->contains($pQOrganization)) {
            $this->collPUAffinityQOPQOrganizations->remove($this->collPUAffinityQOPQOrganizations->search($pQOrganization));
            if (null === $this->pUAffinityQOPQOrganizationsScheduledForDeletion) {
                $this->pUAffinityQOPQOrganizationsScheduledForDeletion = clone $this->collPUAffinityQOPQOrganizations;
                $this->pUAffinityQOPQOrganizationsScheduledForDeletion->clear();
            }
            $this->pUAffinityQOPQOrganizationsScheduledForDeletion[]= $pQOrganization;
        }

        return $this;
    }

    /**
     * Clears out the collPUCurrentQOPQOrganizations collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return PUser The current object (for fluent API support)
     * @see        addPUCurrentQOPQOrganizations()
     */
    public function clearPUCurrentQOPQOrganizations()
    {
        $this->collPUCurrentQOPQOrganizations = null; // important to set this to null since that means it is uninitialized
        $this->collPUCurrentQOPQOrganizationsPartial = null;

        return $this;
    }

    /**
     * Initializes the collPUCurrentQOPQOrganizations collection.
     *
     * By default this just sets the collPUCurrentQOPQOrganizations collection to an empty collection (like clearPUCurrentQOPQOrganizations());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @return void
     */
    public function initPUCurrentQOPQOrganizations()
    {
        $this->collPUCurrentQOPQOrganizations = new PropelObjectCollection();
        $this->collPUCurrentQOPQOrganizations->setModel('PQOrganization');
    }

    /**
     * Gets a collection of PQOrganization objects related by a many-to-many relationship
     * to the current object by way of the p_u_current_q_o cross-reference table.
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
    public function getPUCurrentQOPQOrganizations($criteria = null, PropelPDO $con = null)
    {
        if (null === $this->collPUCurrentQOPQOrganizations || null !== $criteria) {
            if ($this->isNew() && null === $this->collPUCurrentQOPQOrganizations) {
                // return empty collection
                $this->initPUCurrentQOPQOrganizations();
            } else {
                $collPUCurrentQOPQOrganizations = PQOrganizationQuery::create(null, $criteria)
                    ->filterByPUCurrentQOPUser($this)
                    ->find($con);
                if (null !== $criteria) {
                    return $collPUCurrentQOPQOrganizations;
                }
                $this->collPUCurrentQOPQOrganizations = $collPUCurrentQOPQOrganizations;
            }
        }

        return $this->collPUCurrentQOPQOrganizations;
    }

    /**
     * Sets a collection of PQOrganization objects related by a many-to-many relationship
     * to the current object by way of the p_u_current_q_o cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $pUCurrentQOPQOrganizations A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return PUser The current object (for fluent API support)
     */
    public function setPUCurrentQOPQOrganizations(PropelCollection $pUCurrentQOPQOrganizations, PropelPDO $con = null)
    {
        $this->clearPUCurrentQOPQOrganizations();
        $currentPUCurrentQOPQOrganizations = $this->getPUCurrentQOPQOrganizations(null, $con);

        $this->pUCurrentQOPQOrganizationsScheduledForDeletion = $currentPUCurrentQOPQOrganizations->diff($pUCurrentQOPQOrganizations);

        foreach ($pUCurrentQOPQOrganizations as $pUCurrentQOPQOrganization) {
            if (!$currentPUCurrentQOPQOrganizations->contains($pUCurrentQOPQOrganization)) {
                $this->doAddPUCurrentQOPQOrganization($pUCurrentQOPQOrganization);
            }
        }

        $this->collPUCurrentQOPQOrganizations = $pUCurrentQOPQOrganizations;

        return $this;
    }

    /**
     * Gets the number of PQOrganization objects related by a many-to-many relationship
     * to the current object by way of the p_u_current_q_o cross-reference table.
     *
     * @param Criteria $criteria Optional query object to filter the query
     * @param boolean $distinct Set to true to force count distinct
     * @param PropelPDO $con Optional connection object
     *
     * @return int the number of related PQOrganization objects
     */
    public function countPUCurrentQOPQOrganizations($criteria = null, $distinct = false, PropelPDO $con = null)
    {
        if (null === $this->collPUCurrentQOPQOrganizations || null !== $criteria) {
            if ($this->isNew() && null === $this->collPUCurrentQOPQOrganizations) {
                return 0;
            } else {
                $query = PQOrganizationQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByPUCurrentQOPUser($this)
                    ->count($con);
            }
        } else {
            return count($this->collPUCurrentQOPQOrganizations);
        }
    }

    /**
     * Associate a PQOrganization object to this object
     * through the p_u_current_q_o cross reference table.
     *
     * @param  PQOrganization $pQOrganization The PUCurrentQO object to relate
     * @return PUser The current object (for fluent API support)
     */
    public function addPUCurrentQOPQOrganization(PQOrganization $pQOrganization)
    {
        if ($this->collPUCurrentQOPQOrganizations === null) {
            $this->initPUCurrentQOPQOrganizations();
        }

        if (!$this->collPUCurrentQOPQOrganizations->contains($pQOrganization)) { // only add it if the **same** object is not already associated
            $this->doAddPUCurrentQOPQOrganization($pQOrganization);
            $this->collPUCurrentQOPQOrganizations[] = $pQOrganization;

            if ($this->pUCurrentQOPQOrganizationsScheduledForDeletion and $this->pUCurrentQOPQOrganizationsScheduledForDeletion->contains($pQOrganization)) {
                $this->pUCurrentQOPQOrganizationsScheduledForDeletion->remove($this->pUCurrentQOPQOrganizationsScheduledForDeletion->search($pQOrganization));
            }
        }

        return $this;
    }

    /**
     * @param	PUCurrentQOPQOrganization $pUCurrentQOPQOrganization The pUCurrentQOPQOrganization object to add.
     */
    protected function doAddPUCurrentQOPQOrganization(PQOrganization $pUCurrentQOPQOrganization)
    {
        // set the back reference to this object directly as using provided method either results
        // in endless loop or in multiple relations
        if (!$pUCurrentQOPQOrganization->getPUCurrentQOPUsers()->contains($this)) { $pUCurrentQO = new PUCurrentQO();
            $pUCurrentQO->setPUCurrentQOPQOrganization($pUCurrentQOPQOrganization);
            $this->addPUCurrentQOPUser($pUCurrentQO);

            $foreignCollection = $pUCurrentQOPQOrganization->getPUCurrentQOPUsers();
            $foreignCollection[] = $this;
        }
    }

    /**
     * Remove a PQOrganization object to this object
     * through the p_u_current_q_o cross reference table.
     *
     * @param PQOrganization $pQOrganization The PUCurrentQO object to relate
     * @return PUser The current object (for fluent API support)
     */
    public function removePUCurrentQOPQOrganization(PQOrganization $pQOrganization)
    {
        if ($this->getPUCurrentQOPQOrganizations()->contains($pQOrganization)) {
            $this->collPUCurrentQOPQOrganizations->remove($this->collPUCurrentQOPQOrganizations->search($pQOrganization));
            if (null === $this->pUCurrentQOPQOrganizationsScheduledForDeletion) {
                $this->pUCurrentQOPQOrganizationsScheduledForDeletion = clone $this->collPUCurrentQOPQOrganizations;
                $this->pUCurrentQOPQOrganizationsScheduledForDeletion->clear();
            }
            $this->pUCurrentQOPQOrganizationsScheduledForDeletion[]= $pQOrganization;
        }

        return $this;
    }

    /**
     * Clears out the collPUNotificationPNotifications collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return PUser The current object (for fluent API support)
     * @see        addPUNotificationPNotifications()
     */
    public function clearPUNotificationPNotifications()
    {
        $this->collPUNotificationPNotifications = null; // important to set this to null since that means it is uninitialized
        $this->collPUNotificationPNotificationsPartial = null;

        return $this;
    }

    /**
     * Initializes the collPUNotificationPNotifications collection.
     *
     * By default this just sets the collPUNotificationPNotifications collection to an empty collection (like clearPUNotificationPNotifications());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @return void
     */
    public function initPUNotificationPNotifications()
    {
        $this->collPUNotificationPNotifications = new PropelObjectCollection();
        $this->collPUNotificationPNotifications->setModel('PNotification');
    }

    /**
     * Gets a collection of PNotification objects related by a many-to-many relationship
     * to the current object by way of the p_u_notification cross-reference table.
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
    public function getPUNotificationPNotifications($criteria = null, PropelPDO $con = null)
    {
        if (null === $this->collPUNotificationPNotifications || null !== $criteria) {
            if ($this->isNew() && null === $this->collPUNotificationPNotifications) {
                // return empty collection
                $this->initPUNotificationPNotifications();
            } else {
                $collPUNotificationPNotifications = PNotificationQuery::create(null, $criteria)
                    ->filterByPUNotificationPUser($this)
                    ->find($con);
                if (null !== $criteria) {
                    return $collPUNotificationPNotifications;
                }
                $this->collPUNotificationPNotifications = $collPUNotificationPNotifications;
            }
        }

        return $this->collPUNotificationPNotifications;
    }

    /**
     * Sets a collection of PNotification objects related by a many-to-many relationship
     * to the current object by way of the p_u_notification cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $pUNotificationPNotifications A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return PUser The current object (for fluent API support)
     */
    public function setPUNotificationPNotifications(PropelCollection $pUNotificationPNotifications, PropelPDO $con = null)
    {
        $this->clearPUNotificationPNotifications();
        $currentPUNotificationPNotifications = $this->getPUNotificationPNotifications(null, $con);

        $this->pUNotificationPNotificationsScheduledForDeletion = $currentPUNotificationPNotifications->diff($pUNotificationPNotifications);

        foreach ($pUNotificationPNotifications as $pUNotificationPNotification) {
            if (!$currentPUNotificationPNotifications->contains($pUNotificationPNotification)) {
                $this->doAddPUNotificationPNotification($pUNotificationPNotification);
            }
        }

        $this->collPUNotificationPNotifications = $pUNotificationPNotifications;

        return $this;
    }

    /**
     * Gets the number of PNotification objects related by a many-to-many relationship
     * to the current object by way of the p_u_notification cross-reference table.
     *
     * @param Criteria $criteria Optional query object to filter the query
     * @param boolean $distinct Set to true to force count distinct
     * @param PropelPDO $con Optional connection object
     *
     * @return int the number of related PNotification objects
     */
    public function countPUNotificationPNotifications($criteria = null, $distinct = false, PropelPDO $con = null)
    {
        if (null === $this->collPUNotificationPNotifications || null !== $criteria) {
            if ($this->isNew() && null === $this->collPUNotificationPNotifications) {
                return 0;
            } else {
                $query = PNotificationQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByPUNotificationPUser($this)
                    ->count($con);
            }
        } else {
            return count($this->collPUNotificationPNotifications);
        }
    }

    /**
     * Associate a PNotification object to this object
     * through the p_u_notification cross reference table.
     *
     * @param  PNotification $pNotification The PUNotification object to relate
     * @return PUser The current object (for fluent API support)
     */
    public function addPUNotificationPNotification(PNotification $pNotification)
    {
        if ($this->collPUNotificationPNotifications === null) {
            $this->initPUNotificationPNotifications();
        }

        if (!$this->collPUNotificationPNotifications->contains($pNotification)) { // only add it if the **same** object is not already associated
            $this->doAddPUNotificationPNotification($pNotification);
            $this->collPUNotificationPNotifications[] = $pNotification;

            if ($this->pUNotificationPNotificationsScheduledForDeletion and $this->pUNotificationPNotificationsScheduledForDeletion->contains($pNotification)) {
                $this->pUNotificationPNotificationsScheduledForDeletion->remove($this->pUNotificationPNotificationsScheduledForDeletion->search($pNotification));
            }
        }

        return $this;
    }

    /**
     * @param	PUNotificationPNotification $pUNotificationPNotification The pUNotificationPNotification object to add.
     */
    protected function doAddPUNotificationPNotification(PNotification $pUNotificationPNotification)
    {
        // set the back reference to this object directly as using provided method either results
        // in endless loop or in multiple relations
        if (!$pUNotificationPNotification->getPUNotificationPUsers()->contains($this)) { $pUNotification = new PUNotification();
            $pUNotification->setPUNotificationPNotification($pUNotificationPNotification);
            $this->addPUNotificationPUser($pUNotification);

            $foreignCollection = $pUNotificationPNotification->getPUNotificationPUsers();
            $foreignCollection[] = $this;
        }
    }

    /**
     * Remove a PNotification object to this object
     * through the p_u_notification cross reference table.
     *
     * @param PNotification $pNotification The PUNotification object to relate
     * @return PUser The current object (for fluent API support)
     */
    public function removePUNotificationPNotification(PNotification $pNotification)
    {
        if ($this->getPUNotificationPNotifications()->contains($pNotification)) {
            $this->collPUNotificationPNotifications->remove($this->collPUNotificationPNotifications->search($pNotification));
            if (null === $this->pUNotificationPNotificationsScheduledForDeletion) {
                $this->pUNotificationPNotificationsScheduledForDeletion = clone $this->collPUNotificationPNotifications;
                $this->pUNotificationPNotificationsScheduledForDeletion->clear();
            }
            $this->pUNotificationPNotificationsScheduledForDeletion[]= $pNotification;
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
     * to the current object by way of the p_u_subscribe_p_n_e cross-reference table.
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
     * to the current object by way of the p_u_subscribe_p_n_e cross-reference table.
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
     * to the current object by way of the p_u_subscribe_p_n_e cross-reference table.
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
     * through the p_u_subscribe_p_n_e cross reference table.
     *
     * @param  PNEmail $pNEmail The PUSubscribePNE object to relate
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
        if (!$pNEmail->getPUsers()->contains($this)) { $pUSubscribePNE = new PUSubscribePNE();
            $pUSubscribePNE->setPNEmail($pNEmail);
            $this->addPUSubscribePNE($pUSubscribePNE);

            $foreignCollection = $pNEmail->getPUsers();
            $foreignCollection[] = $this;
        }
    }

    /**
     * Remove a PNEmail object to this object
     * through the p_u_subscribe_p_n_e cross reference table.
     *
     * @param PNEmail $pNEmail The PUSubscribePNE object to relate
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
     * Clears out the collPMModerationTypes collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return PUser The current object (for fluent API support)
     * @see        addPMModerationTypes()
     */
    public function clearPMModerationTypes()
    {
        $this->collPMModerationTypes = null; // important to set this to null since that means it is uninitialized
        $this->collPMModerationTypesPartial = null;

        return $this;
    }

    /**
     * Initializes the collPMModerationTypes collection.
     *
     * By default this just sets the collPMModerationTypes collection to an empty collection (like clearPMModerationTypes());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @return void
     */
    public function initPMModerationTypes()
    {
        $this->collPMModerationTypes = new PropelObjectCollection();
        $this->collPMModerationTypes->setModel('PMModerationType');
    }

    /**
     * Gets a collection of PMModerationType objects related by a many-to-many relationship
     * to the current object by way of the p_m_user_moderated cross-reference table.
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
     * @return PropelObjectCollection|PMModerationType[] List of PMModerationType objects
     */
    public function getPMModerationTypes($criteria = null, PropelPDO $con = null)
    {
        if (null === $this->collPMModerationTypes || null !== $criteria) {
            if ($this->isNew() && null === $this->collPMModerationTypes) {
                // return empty collection
                $this->initPMModerationTypes();
            } else {
                $collPMModerationTypes = PMModerationTypeQuery::create(null, $criteria)
                    ->filterByPUser($this)
                    ->find($con);
                if (null !== $criteria) {
                    return $collPMModerationTypes;
                }
                $this->collPMModerationTypes = $collPMModerationTypes;
            }
        }

        return $this->collPMModerationTypes;
    }

    /**
     * Sets a collection of PMModerationType objects related by a many-to-many relationship
     * to the current object by way of the p_m_user_moderated cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $pMModerationTypes A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return PUser The current object (for fluent API support)
     */
    public function setPMModerationTypes(PropelCollection $pMModerationTypes, PropelPDO $con = null)
    {
        $this->clearPMModerationTypes();
        $currentPMModerationTypes = $this->getPMModerationTypes(null, $con);

        $this->pMModerationTypesScheduledForDeletion = $currentPMModerationTypes->diff($pMModerationTypes);

        foreach ($pMModerationTypes as $pMModerationType) {
            if (!$currentPMModerationTypes->contains($pMModerationType)) {
                $this->doAddPMModerationType($pMModerationType);
            }
        }

        $this->collPMModerationTypes = $pMModerationTypes;

        return $this;
    }

    /**
     * Gets the number of PMModerationType objects related by a many-to-many relationship
     * to the current object by way of the p_m_user_moderated cross-reference table.
     *
     * @param Criteria $criteria Optional query object to filter the query
     * @param boolean $distinct Set to true to force count distinct
     * @param PropelPDO $con Optional connection object
     *
     * @return int the number of related PMModerationType objects
     */
    public function countPMModerationTypes($criteria = null, $distinct = false, PropelPDO $con = null)
    {
        if (null === $this->collPMModerationTypes || null !== $criteria) {
            if ($this->isNew() && null === $this->collPMModerationTypes) {
                return 0;
            } else {
                $query = PMModerationTypeQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByPUser($this)
                    ->count($con);
            }
        } else {
            return count($this->collPMModerationTypes);
        }
    }

    /**
     * Associate a PMModerationType object to this object
     * through the p_m_user_moderated cross reference table.
     *
     * @param  PMModerationType $pMModerationType The PMUserModerated object to relate
     * @return PUser The current object (for fluent API support)
     */
    public function addPMModerationType(PMModerationType $pMModerationType)
    {
        if ($this->collPMModerationTypes === null) {
            $this->initPMModerationTypes();
        }

        if (!$this->collPMModerationTypes->contains($pMModerationType)) { // only add it if the **same** object is not already associated
            $this->doAddPMModerationType($pMModerationType);
            $this->collPMModerationTypes[] = $pMModerationType;

            if ($this->pMModerationTypesScheduledForDeletion and $this->pMModerationTypesScheduledForDeletion->contains($pMModerationType)) {
                $this->pMModerationTypesScheduledForDeletion->remove($this->pMModerationTypesScheduledForDeletion->search($pMModerationType));
            }
        }

        return $this;
    }

    /**
     * @param	PMModerationType $pMModerationType The pMModerationType object to add.
     */
    protected function doAddPMModerationType(PMModerationType $pMModerationType)
    {
        // set the back reference to this object directly as using provided method either results
        // in endless loop or in multiple relations
        if (!$pMModerationType->getPUsers()->contains($this)) { $pMUserModerated = new PMUserModerated();
            $pMUserModerated->setPMModerationType($pMModerationType);
            $this->addPMUserModerated($pMUserModerated);

            $foreignCollection = $pMModerationType->getPUsers();
            $foreignCollection[] = $this;
        }
    }

    /**
     * Remove a PMModerationType object to this object
     * through the p_m_user_moderated cross reference table.
     *
     * @param PMModerationType $pMModerationType The PMUserModerated object to relate
     * @return PUser The current object (for fluent API support)
     */
    public function removePMModerationType(PMModerationType $pMModerationType)
    {
        if ($this->getPMModerationTypes()->contains($pMModerationType)) {
            $this->collPMModerationTypes->remove($this->collPMModerationTypes->search($pMModerationType));
            if (null === $this->pMModerationTypesScheduledForDeletion) {
                $this->pMModerationTypesScheduledForDeletion = clone $this->collPMModerationTypes;
                $this->pMModerationTypesScheduledForDeletion->clear();
            }
            $this->pMModerationTypesScheduledForDeletion[]= $pMModerationType;
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
        $this->online = null;
        $this->homepage = null;
        $this->banned = null;
        $this->banned_nb_days_left = null;
        $this->banned_nb_total = null;
        $this->abuse_level = null;
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
            if ($this->collPUsers) {
                foreach ($this->collPUsers as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPOwners) {
                foreach ($this->collPOwners as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPEOperations) {
                foreach ($this->collPEOperations as $o) {
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
            if ($this->collPuBookmarkDdPUsers) {
                foreach ($this->collPuBookmarkDdPUsers as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPuBookmarkDrPUsers) {
                foreach ($this->collPuBookmarkDrPUsers as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPuTrackDdPUsers) {
                foreach ($this->collPuTrackDdPUsers as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPuTrackDrPUsers) {
                foreach ($this->collPuTrackDrPUsers as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPUBadges) {
                foreach ($this->collPUBadges as $o) {
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
            if ($this->collPUAffinityQOPUsers) {
                foreach ($this->collPUAffinityQOPUsers as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPUCurrentQOPUsers) {
                foreach ($this->collPUCurrentQOPUsers as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPUNotificationPUsers) {
                foreach ($this->collPUNotificationPUsers as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPUSubscribePNEs) {
                foreach ($this->collPUSubscribePNEs as $o) {
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
            if ($this->collPDDComments) {
                foreach ($this->collPDDComments as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPDRComments) {
                foreach ($this->collPDRComments as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPMUserModerateds) {
                foreach ($this->collPMUserModerateds as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPMUserMessages) {
                foreach ($this->collPMUserMessages as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPMUserHistorics) {
                foreach ($this->collPMUserHistorics as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPMDebateHistorics) {
                foreach ($this->collPMDebateHistorics as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPMReactionHistorics) {
                foreach ($this->collPMReactionHistorics as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPMDCommentHistorics) {
                foreach ($this->collPMDCommentHistorics as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPMRCommentHistorics) {
                foreach ($this->collPMRCommentHistorics as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPMAskForUpdates) {
                foreach ($this->collPMAskForUpdates as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPMAbuseReportings) {
                foreach ($this->collPMAbuseReportings as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPMAppExceptions) {
                foreach ($this->collPMAppExceptions as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPMEmailings) {
                foreach ($this->collPMEmailings as $o) {
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
            if ($this->collPUTrackUsRelatedByPUserIdSource) {
                foreach ($this->collPUTrackUsRelatedByPUserIdSource as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPUTrackUsRelatedByPUserIdDest) {
                foreach ($this->collPUTrackUsRelatedByPUserIdDest as $o) {
                    $o->clearAllReferences($deep);
                }
            }
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
            if ($this->collPuBookmarkDrPDReactions) {
                foreach ($this->collPuBookmarkDrPDReactions as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPuTrackDdPDDebates) {
                foreach ($this->collPuTrackDdPDDebates as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPuTrackDrPDReactions) {
                foreach ($this->collPuTrackDrPDReactions as $o) {
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
            if ($this->collPQualifications) {
                foreach ($this->collPQualifications as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPUAffinityQOPQOrganizations) {
                foreach ($this->collPUAffinityQOPQOrganizations as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPUCurrentQOPQOrganizations) {
                foreach ($this->collPUCurrentQOPQOrganizations as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPUNotificationPNotifications) {
                foreach ($this->collPUNotificationPNotifications as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPNEmails) {
                foreach ($this->collPNEmails as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPMModerationTypes) {
                foreach ($this->collPMModerationTypes as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->aPUStatus instanceof Persistent) {
              $this->aPUStatus->clearAllReferences($deep);
            }
            if ($this->aPLCity instanceof Persistent) {
              $this->aPLCity->clearAllReferences($deep);
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

        if ($this->collPUsers instanceof PropelCollection) {
            $this->collPUsers->clearIterator();
        }
        $this->collPUsers = null;
        if ($this->collPOwners instanceof PropelCollection) {
            $this->collPOwners->clearIterator();
        }
        $this->collPOwners = null;
        if ($this->collPEOperations instanceof PropelCollection) {
            $this->collPEOperations->clearIterator();
        }
        $this->collPEOperations = null;
        if ($this->collPOrders instanceof PropelCollection) {
            $this->collPOrders->clearIterator();
        }
        $this->collPOrders = null;
        if ($this->collPuFollowDdPUsers instanceof PropelCollection) {
            $this->collPuFollowDdPUsers->clearIterator();
        }
        $this->collPuFollowDdPUsers = null;
        if ($this->collPuBookmarkDdPUsers instanceof PropelCollection) {
            $this->collPuBookmarkDdPUsers->clearIterator();
        }
        $this->collPuBookmarkDdPUsers = null;
        if ($this->collPuBookmarkDrPUsers instanceof PropelCollection) {
            $this->collPuBookmarkDrPUsers->clearIterator();
        }
        $this->collPuBookmarkDrPUsers = null;
        if ($this->collPuTrackDdPUsers instanceof PropelCollection) {
            $this->collPuTrackDdPUsers->clearIterator();
        }
        $this->collPuTrackDdPUsers = null;
        if ($this->collPuTrackDrPUsers instanceof PropelCollection) {
            $this->collPuTrackDrPUsers->clearIterator();
        }
        $this->collPuTrackDrPUsers = null;
        if ($this->collPUBadges instanceof PropelCollection) {
            $this->collPUBadges->clearIterator();
        }
        $this->collPUBadges = null;
        if ($this->collPUReputations instanceof PropelCollection) {
            $this->collPUReputations->clearIterator();
        }
        $this->collPUReputations = null;
        if ($this->collPuTaggedTPUsers instanceof PropelCollection) {
            $this->collPuTaggedTPUsers->clearIterator();
        }
        $this->collPuTaggedTPUsers = null;
        if ($this->collPURoleQs instanceof PropelCollection) {
            $this->collPURoleQs->clearIterator();
        }
        $this->collPURoleQs = null;
        if ($this->collPUMandates instanceof PropelCollection) {
            $this->collPUMandates->clearIterator();
        }
        $this->collPUMandates = null;
        if ($this->collPUAffinityQOPUsers instanceof PropelCollection) {
            $this->collPUAffinityQOPUsers->clearIterator();
        }
        $this->collPUAffinityQOPUsers = null;
        if ($this->collPUCurrentQOPUsers instanceof PropelCollection) {
            $this->collPUCurrentQOPUsers->clearIterator();
        }
        $this->collPUCurrentQOPUsers = null;
        if ($this->collPUNotificationPUsers instanceof PropelCollection) {
            $this->collPUNotificationPUsers->clearIterator();
        }
        $this->collPUNotificationPUsers = null;
        if ($this->collPUSubscribePNEs instanceof PropelCollection) {
            $this->collPUSubscribePNEs->clearIterator();
        }
        $this->collPUSubscribePNEs = null;
        if ($this->collPDDebates instanceof PropelCollection) {
            $this->collPDDebates->clearIterator();
        }
        $this->collPDDebates = null;
        if ($this->collPDReactions instanceof PropelCollection) {
            $this->collPDReactions->clearIterator();
        }
        $this->collPDReactions = null;
        if ($this->collPDDComments instanceof PropelCollection) {
            $this->collPDDComments->clearIterator();
        }
        $this->collPDDComments = null;
        if ($this->collPDRComments instanceof PropelCollection) {
            $this->collPDRComments->clearIterator();
        }
        $this->collPDRComments = null;
        if ($this->collPMUserModerateds instanceof PropelCollection) {
            $this->collPMUserModerateds->clearIterator();
        }
        $this->collPMUserModerateds = null;
        if ($this->collPMUserMessages instanceof PropelCollection) {
            $this->collPMUserMessages->clearIterator();
        }
        $this->collPMUserMessages = null;
        if ($this->collPMUserHistorics instanceof PropelCollection) {
            $this->collPMUserHistorics->clearIterator();
        }
        $this->collPMUserHistorics = null;
        if ($this->collPMDebateHistorics instanceof PropelCollection) {
            $this->collPMDebateHistorics->clearIterator();
        }
        $this->collPMDebateHistorics = null;
        if ($this->collPMReactionHistorics instanceof PropelCollection) {
            $this->collPMReactionHistorics->clearIterator();
        }
        $this->collPMReactionHistorics = null;
        if ($this->collPMDCommentHistorics instanceof PropelCollection) {
            $this->collPMDCommentHistorics->clearIterator();
        }
        $this->collPMDCommentHistorics = null;
        if ($this->collPMRCommentHistorics instanceof PropelCollection) {
            $this->collPMRCommentHistorics->clearIterator();
        }
        $this->collPMRCommentHistorics = null;
        if ($this->collPMAskForUpdates instanceof PropelCollection) {
            $this->collPMAskForUpdates->clearIterator();
        }
        $this->collPMAskForUpdates = null;
        if ($this->collPMAbuseReportings instanceof PropelCollection) {
            $this->collPMAbuseReportings->clearIterator();
        }
        $this->collPMAbuseReportings = null;
        if ($this->collPMAppExceptions instanceof PropelCollection) {
            $this->collPMAppExceptions->clearIterator();
        }
        $this->collPMAppExceptions = null;
        if ($this->collPMEmailings instanceof PropelCollection) {
            $this->collPMEmailings->clearIterator();
        }
        $this->collPMEmailings = null;
        if ($this->collPUFollowUsRelatedByPUserId instanceof PropelCollection) {
            $this->collPUFollowUsRelatedByPUserId->clearIterator();
        }
        $this->collPUFollowUsRelatedByPUserId = null;
        if ($this->collPUFollowUsRelatedByPUserFollowerId instanceof PropelCollection) {
            $this->collPUFollowUsRelatedByPUserFollowerId->clearIterator();
        }
        $this->collPUFollowUsRelatedByPUserFollowerId = null;
        if ($this->collPUTrackUsRelatedByPUserIdSource instanceof PropelCollection) {
            $this->collPUTrackUsRelatedByPUserIdSource->clearIterator();
        }
        $this->collPUTrackUsRelatedByPUserIdSource = null;
        if ($this->collPUTrackUsRelatedByPUserIdDest instanceof PropelCollection) {
            $this->collPUTrackUsRelatedByPUserIdDest->clearIterator();
        }
        $this->collPUTrackUsRelatedByPUserIdDest = null;
        if ($this->collPuFollowDdPDDebates instanceof PropelCollection) {
            $this->collPuFollowDdPDDebates->clearIterator();
        }
        $this->collPuFollowDdPDDebates = null;
        if ($this->collPuBookmarkDdPDDebates instanceof PropelCollection) {
            $this->collPuBookmarkDdPDDebates->clearIterator();
        }
        $this->collPuBookmarkDdPDDebates = null;
        if ($this->collPuBookmarkDrPDReactions instanceof PropelCollection) {
            $this->collPuBookmarkDrPDReactions->clearIterator();
        }
        $this->collPuBookmarkDrPDReactions = null;
        if ($this->collPuTrackDdPDDebates instanceof PropelCollection) {
            $this->collPuTrackDdPDDebates->clearIterator();
        }
        $this->collPuTrackDdPDDebates = null;
        if ($this->collPuTrackDrPDReactions instanceof PropelCollection) {
            $this->collPuTrackDrPDReactions->clearIterator();
        }
        $this->collPuTrackDrPDReactions = null;
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
        if ($this->collPQualifications instanceof PropelCollection) {
            $this->collPQualifications->clearIterator();
        }
        $this->collPQualifications = null;
        if ($this->collPUAffinityQOPQOrganizations instanceof PropelCollection) {
            $this->collPUAffinityQOPQOrganizations->clearIterator();
        }
        $this->collPUAffinityQOPQOrganizations = null;
        if ($this->collPUCurrentQOPQOrganizations instanceof PropelCollection) {
            $this->collPUCurrentQOPQOrganizations->clearIterator();
        }
        $this->collPUCurrentQOPQOrganizations = null;
        if ($this->collPUNotificationPNotifications instanceof PropelCollection) {
            $this->collPUNotificationPNotifications->clearIterator();
        }
        $this->collPUNotificationPNotifications = null;
        if ($this->collPNEmails instanceof PropelCollection) {
            $this->collPNEmails->clearIterator();
        }
        $this->collPNEmails = null;
        if ($this->collPMModerationTypes instanceof PropelCollection) {
            $this->collPMModerationTypes->clearIterator();
        }
        $this->collPMModerationTypes = null;
        $this->aPUStatus = null;
        $this->aPLCity = null;
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
    * If permanent UUID, throw exception p_user.uuid*/
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
        $this->setUuid($archive->getUuid());
        $this->setPUStatusId($archive->getPUStatusId());
        $this->setPLCityId($archive->getPLCityId());
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
        $this->setLastActivity($archive->getLastActivity());
        $this->setFileName($archive->getFileName());
        $this->setBackFileName($archive->getBackFileName());
        $this->setCopyright($archive->getCopyright());
        $this->setGender($archive->getGender());
        $this->setFirstname($archive->getFirstname());
        $this->setName($archive->getName());
        $this->setBirthday($archive->getBirthday());
        $this->setSubtitle($archive->getSubtitle());
        $this->setBiography($archive->getBiography());
        $this->setWebsite($archive->getWebsite());
        $this->setTwitter($archive->getTwitter());
        $this->setFacebook($archive->getFacebook());
        $this->setPhone($archive->getPhone());
        $this->setNewsletter($archive->getNewsletter());
        $this->setLastConnect($archive->getLastConnect());
        $this->setNbConnectedDays($archive->getNbConnectedDays());
        $this->setIndexedAt($archive->getIndexedAt());
        $this->setNbViews($archive->getNbViews());
        $this->setQualified($archive->getQualified());
        $this->setValidated($archive->getValidated());
        $this->setNbIdCheck($archive->getNbIdCheck());
        $this->setOnline($archive->getOnline());
        $this->setHomepage($archive->getHomepage());
        $this->setBanned($archive->getBanned());
        $this->setBannedNbDaysLeft($archive->getBannedNbDaysLeft());
        $this->setBannedNbTotal($archive->getBannedNbTotal());
        $this->setAbuseLevel($archive->getAbuseLevel());
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
