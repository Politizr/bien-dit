<?php

namespace Politizr\Model\om;

use \BasePeer;
use \Criteria;
use \PDO;
use \PDOStatement;
use \Propel;
use \PropelException;
use \PropelPDO;
use Politizr\Model\PDCommentPeer;
use Politizr\Model\PDDebatePeer;
use Politizr\Model\PDReactionPeer;
use Politizr\Model\PDocumentPeer;
use Politizr\Model\POrderPeer;
use Politizr\Model\PTagPeer;
use Politizr\Model\PUAffinityQOPeer;
use Politizr\Model\PUBadgesPeer;
use Politizr\Model\PUFollowDDPeer;
use Politizr\Model\PUFollowTPeer;
use Politizr\Model\PUFollowUPeer;
use Politizr\Model\PUMandatePeer;
use Politizr\Model\PUNotificationsPeer;
use Politizr\Model\PUReputationPeer;
use Politizr\Model\PURoleQPeer;
use Politizr\Model\PUStatusPeer;
use Politizr\Model\PUSubscribeEmailPeer;
use Politizr\Model\PUSubscribeScreenPeer;
use Politizr\Model\PUTaggedTPeer;
use Politizr\Model\PUser;
use Politizr\Model\PUserPeer;
use Politizr\Model\map\PUserTableMap;

abstract class BasePUserPeer
{

    /** the default database name for this class */
    const DATABASE_NAME = 'default';

    /** the table name for this class */
    const TABLE_NAME = 'p_user';

    /** the related Propel class for this table */
    const OM_CLASS = 'Politizr\\Model\\PUser';

    /** the related TableMap class for this table */
    const TM_CLASS = 'Politizr\\Model\\map\\PUserTableMap';

    /** The total number of columns. */
    const NUM_COLUMNS = 42;

    /** The number of lazy-loaded columns. */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /** The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS) */
    const NUM_HYDRATE_COLUMNS = 42;

    /** the column name for the id field */
    const ID = 'p_user.id';

    /** the column name for the provider field */
    const PROVIDER = 'p_user.provider';

    /** the column name for the provider_id field */
    const PROVIDER_ID = 'p_user.provider_id';

    /** the column name for the nickname field */
    const NICKNAME = 'p_user.nickname';

    /** the column name for the realname field */
    const REALNAME = 'p_user.realname';

    /** the column name for the username field */
    const USERNAME = 'p_user.username';

    /** the column name for the username_canonical field */
    const USERNAME_CANONICAL = 'p_user.username_canonical';

    /** the column name for the email field */
    const EMAIL = 'p_user.email';

    /** the column name for the email_canonical field */
    const EMAIL_CANONICAL = 'p_user.email_canonical';

    /** the column name for the enabled field */
    const ENABLED = 'p_user.enabled';

    /** the column name for the salt field */
    const SALT = 'p_user.salt';

    /** the column name for the password field */
    const PASSWORD = 'p_user.password';

    /** the column name for the last_login field */
    const LAST_LOGIN = 'p_user.last_login';

    /** the column name for the locked field */
    const LOCKED = 'p_user.locked';

    /** the column name for the expired field */
    const EXPIRED = 'p_user.expired';

    /** the column name for the expires_at field */
    const EXPIRES_AT = 'p_user.expires_at';

    /** the column name for the confirmation_token field */
    const CONFIRMATION_TOKEN = 'p_user.confirmation_token';

    /** the column name for the password_requested_at field */
    const PASSWORD_REQUESTED_AT = 'p_user.password_requested_at';

    /** the column name for the credentials_expired field */
    const CREDENTIALS_EXPIRED = 'p_user.credentials_expired';

    /** the column name for the credentials_expire_at field */
    const CREDENTIALS_EXPIRE_AT = 'p_user.credentials_expire_at';

    /** the column name for the roles field */
    const ROLES = 'p_user.roles';

    /** the column name for the p_u_status_id field */
    const P_U_STATUS_ID = 'p_user.p_u_status_id';

    /** the column name for the file_name field */
    const FILE_NAME = 'p_user.file_name';

    /** the column name for the gender field */
    const GENDER = 'p_user.gender';

    /** the column name for the firstname field */
    const FIRSTNAME = 'p_user.firstname';

    /** the column name for the name field */
    const NAME = 'p_user.name';

    /** the column name for the birthday field */
    const BIRTHDAY = 'p_user.birthday';

    /** the column name for the biography field */
    const BIOGRAPHY = 'p_user.biography';

    /** the column name for the website field */
    const WEBSITE = 'p_user.website';

    /** the column name for the twitter field */
    const TWITTER = 'p_user.twitter';

    /** the column name for the facebook field */
    const FACEBOOK = 'p_user.facebook';

    /** the column name for the phone field */
    const PHONE = 'p_user.phone';

    /** the column name for the newsletter field */
    const NEWSLETTER = 'p_user.newsletter';

    /** the column name for the last_connect field */
    const LAST_CONNECT = 'p_user.last_connect';

    /** the column name for the nb_connected_days field */
    const NB_CONNECTED_DAYS = 'p_user.nb_connected_days';

    /** the column name for the nb_views field */
    const NB_VIEWS = 'p_user.nb_views';

    /** the column name for the qualified field */
    const QUALIFIED = 'p_user.qualified';

    /** the column name for the validated field */
    const VALIDATED = 'p_user.validated';

    /** the column name for the online field */
    const ONLINE = 'p_user.online';

    /** the column name for the created_at field */
    const CREATED_AT = 'p_user.created_at';

    /** the column name for the updated_at field */
    const UPDATED_AT = 'p_user.updated_at';

    /** the column name for the slug field */
    const SLUG = 'p_user.slug';

    /** The enumerated values for the gender field */
    const GENDER_MADAME = 'Madame';
    const GENDER_MONSIEUR = 'Monsieur';

    /** The default string format for model objects of the related table **/
    const DEFAULT_STRING_FORMAT = 'YAML';

    /**
     * An identity map to hold any loaded instances of PUser objects.
     * This must be public so that other peer classes can access this when hydrating from JOIN
     * queries.
     * @var        array PUser[]
     */
    public static $instances = array();


    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. PUserPeer::$fieldNames[PUserPeer::TYPE_PHPNAME][0] = 'Id'
     */
    protected static $fieldNames = array (
        BasePeer::TYPE_PHPNAME => array ('Id', 'Provider', 'ProviderId', 'Nickname', 'Realname', 'Username', 'UsernameCanonical', 'Email', 'EmailCanonical', 'Enabled', 'Salt', 'Password', 'LastLogin', 'Locked', 'Expired', 'ExpiresAt', 'ConfirmationToken', 'PasswordRequestedAt', 'CredentialsExpired', 'CredentialsExpireAt', 'Roles', 'PUStatusId', 'FileName', 'Gender', 'Firstname', 'Name', 'Birthday', 'Biography', 'Website', 'Twitter', 'Facebook', 'Phone', 'Newsletter', 'LastConnect', 'NbConnectedDays', 'NbViews', 'Qualified', 'Validated', 'Online', 'CreatedAt', 'UpdatedAt', 'Slug', ),
        BasePeer::TYPE_STUDLYPHPNAME => array ('id', 'provider', 'providerId', 'nickname', 'realname', 'username', 'usernameCanonical', 'email', 'emailCanonical', 'enabled', 'salt', 'password', 'lastLogin', 'locked', 'expired', 'expiresAt', 'confirmationToken', 'passwordRequestedAt', 'credentialsExpired', 'credentialsExpireAt', 'roles', 'pUStatusId', 'fileName', 'gender', 'firstname', 'name', 'birthday', 'biography', 'website', 'twitter', 'facebook', 'phone', 'newsletter', 'lastConnect', 'nbConnectedDays', 'nbViews', 'qualified', 'validated', 'online', 'createdAt', 'updatedAt', 'slug', ),
        BasePeer::TYPE_COLNAME => array (PUserPeer::ID, PUserPeer::PROVIDER, PUserPeer::PROVIDER_ID, PUserPeer::NICKNAME, PUserPeer::REALNAME, PUserPeer::USERNAME, PUserPeer::USERNAME_CANONICAL, PUserPeer::EMAIL, PUserPeer::EMAIL_CANONICAL, PUserPeer::ENABLED, PUserPeer::SALT, PUserPeer::PASSWORD, PUserPeer::LAST_LOGIN, PUserPeer::LOCKED, PUserPeer::EXPIRED, PUserPeer::EXPIRES_AT, PUserPeer::CONFIRMATION_TOKEN, PUserPeer::PASSWORD_REQUESTED_AT, PUserPeer::CREDENTIALS_EXPIRED, PUserPeer::CREDENTIALS_EXPIRE_AT, PUserPeer::ROLES, PUserPeer::P_U_STATUS_ID, PUserPeer::FILE_NAME, PUserPeer::GENDER, PUserPeer::FIRSTNAME, PUserPeer::NAME, PUserPeer::BIRTHDAY, PUserPeer::BIOGRAPHY, PUserPeer::WEBSITE, PUserPeer::TWITTER, PUserPeer::FACEBOOK, PUserPeer::PHONE, PUserPeer::NEWSLETTER, PUserPeer::LAST_CONNECT, PUserPeer::NB_CONNECTED_DAYS, PUserPeer::NB_VIEWS, PUserPeer::QUALIFIED, PUserPeer::VALIDATED, PUserPeer::ONLINE, PUserPeer::CREATED_AT, PUserPeer::UPDATED_AT, PUserPeer::SLUG, ),
        BasePeer::TYPE_RAW_COLNAME => array ('ID', 'PROVIDER', 'PROVIDER_ID', 'NICKNAME', 'REALNAME', 'USERNAME', 'USERNAME_CANONICAL', 'EMAIL', 'EMAIL_CANONICAL', 'ENABLED', 'SALT', 'PASSWORD', 'LAST_LOGIN', 'LOCKED', 'EXPIRED', 'EXPIRES_AT', 'CONFIRMATION_TOKEN', 'PASSWORD_REQUESTED_AT', 'CREDENTIALS_EXPIRED', 'CREDENTIALS_EXPIRE_AT', 'ROLES', 'P_U_STATUS_ID', 'FILE_NAME', 'GENDER', 'FIRSTNAME', 'NAME', 'BIRTHDAY', 'BIOGRAPHY', 'WEBSITE', 'TWITTER', 'FACEBOOK', 'PHONE', 'NEWSLETTER', 'LAST_CONNECT', 'NB_CONNECTED_DAYS', 'NB_VIEWS', 'QUALIFIED', 'VALIDATED', 'ONLINE', 'CREATED_AT', 'UPDATED_AT', 'SLUG', ),
        BasePeer::TYPE_FIELDNAME => array ('id', 'provider', 'provider_id', 'nickname', 'realname', 'username', 'username_canonical', 'email', 'email_canonical', 'enabled', 'salt', 'password', 'last_login', 'locked', 'expired', 'expires_at', 'confirmation_token', 'password_requested_at', 'credentials_expired', 'credentials_expire_at', 'roles', 'p_u_status_id', 'file_name', 'gender', 'firstname', 'name', 'birthday', 'biography', 'website', 'twitter', 'facebook', 'phone', 'newsletter', 'last_connect', 'nb_connected_days', 'nb_views', 'qualified', 'validated', 'online', 'created_at', 'updated_at', 'slug', ),
        BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38, 39, 40, 41, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. PUserPeer::$fieldNames[BasePeer::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        BasePeer::TYPE_PHPNAME => array ('Id' => 0, 'Provider' => 1, 'ProviderId' => 2, 'Nickname' => 3, 'Realname' => 4, 'Username' => 5, 'UsernameCanonical' => 6, 'Email' => 7, 'EmailCanonical' => 8, 'Enabled' => 9, 'Salt' => 10, 'Password' => 11, 'LastLogin' => 12, 'Locked' => 13, 'Expired' => 14, 'ExpiresAt' => 15, 'ConfirmationToken' => 16, 'PasswordRequestedAt' => 17, 'CredentialsExpired' => 18, 'CredentialsExpireAt' => 19, 'Roles' => 20, 'PUStatusId' => 21, 'FileName' => 22, 'Gender' => 23, 'Firstname' => 24, 'Name' => 25, 'Birthday' => 26, 'Biography' => 27, 'Website' => 28, 'Twitter' => 29, 'Facebook' => 30, 'Phone' => 31, 'Newsletter' => 32, 'LastConnect' => 33, 'NbConnectedDays' => 34, 'NbViews' => 35, 'Qualified' => 36, 'Validated' => 37, 'Online' => 38, 'CreatedAt' => 39, 'UpdatedAt' => 40, 'Slug' => 41, ),
        BasePeer::TYPE_STUDLYPHPNAME => array ('id' => 0, 'provider' => 1, 'providerId' => 2, 'nickname' => 3, 'realname' => 4, 'username' => 5, 'usernameCanonical' => 6, 'email' => 7, 'emailCanonical' => 8, 'enabled' => 9, 'salt' => 10, 'password' => 11, 'lastLogin' => 12, 'locked' => 13, 'expired' => 14, 'expiresAt' => 15, 'confirmationToken' => 16, 'passwordRequestedAt' => 17, 'credentialsExpired' => 18, 'credentialsExpireAt' => 19, 'roles' => 20, 'pUStatusId' => 21, 'fileName' => 22, 'gender' => 23, 'firstname' => 24, 'name' => 25, 'birthday' => 26, 'biography' => 27, 'website' => 28, 'twitter' => 29, 'facebook' => 30, 'phone' => 31, 'newsletter' => 32, 'lastConnect' => 33, 'nbConnectedDays' => 34, 'nbViews' => 35, 'qualified' => 36, 'validated' => 37, 'online' => 38, 'createdAt' => 39, 'updatedAt' => 40, 'slug' => 41, ),
        BasePeer::TYPE_COLNAME => array (PUserPeer::ID => 0, PUserPeer::PROVIDER => 1, PUserPeer::PROVIDER_ID => 2, PUserPeer::NICKNAME => 3, PUserPeer::REALNAME => 4, PUserPeer::USERNAME => 5, PUserPeer::USERNAME_CANONICAL => 6, PUserPeer::EMAIL => 7, PUserPeer::EMAIL_CANONICAL => 8, PUserPeer::ENABLED => 9, PUserPeer::SALT => 10, PUserPeer::PASSWORD => 11, PUserPeer::LAST_LOGIN => 12, PUserPeer::LOCKED => 13, PUserPeer::EXPIRED => 14, PUserPeer::EXPIRES_AT => 15, PUserPeer::CONFIRMATION_TOKEN => 16, PUserPeer::PASSWORD_REQUESTED_AT => 17, PUserPeer::CREDENTIALS_EXPIRED => 18, PUserPeer::CREDENTIALS_EXPIRE_AT => 19, PUserPeer::ROLES => 20, PUserPeer::P_U_STATUS_ID => 21, PUserPeer::FILE_NAME => 22, PUserPeer::GENDER => 23, PUserPeer::FIRSTNAME => 24, PUserPeer::NAME => 25, PUserPeer::BIRTHDAY => 26, PUserPeer::BIOGRAPHY => 27, PUserPeer::WEBSITE => 28, PUserPeer::TWITTER => 29, PUserPeer::FACEBOOK => 30, PUserPeer::PHONE => 31, PUserPeer::NEWSLETTER => 32, PUserPeer::LAST_CONNECT => 33, PUserPeer::NB_CONNECTED_DAYS => 34, PUserPeer::NB_VIEWS => 35, PUserPeer::QUALIFIED => 36, PUserPeer::VALIDATED => 37, PUserPeer::ONLINE => 38, PUserPeer::CREATED_AT => 39, PUserPeer::UPDATED_AT => 40, PUserPeer::SLUG => 41, ),
        BasePeer::TYPE_RAW_COLNAME => array ('ID' => 0, 'PROVIDER' => 1, 'PROVIDER_ID' => 2, 'NICKNAME' => 3, 'REALNAME' => 4, 'USERNAME' => 5, 'USERNAME_CANONICAL' => 6, 'EMAIL' => 7, 'EMAIL_CANONICAL' => 8, 'ENABLED' => 9, 'SALT' => 10, 'PASSWORD' => 11, 'LAST_LOGIN' => 12, 'LOCKED' => 13, 'EXPIRED' => 14, 'EXPIRES_AT' => 15, 'CONFIRMATION_TOKEN' => 16, 'PASSWORD_REQUESTED_AT' => 17, 'CREDENTIALS_EXPIRED' => 18, 'CREDENTIALS_EXPIRE_AT' => 19, 'ROLES' => 20, 'P_U_STATUS_ID' => 21, 'FILE_NAME' => 22, 'GENDER' => 23, 'FIRSTNAME' => 24, 'NAME' => 25, 'BIRTHDAY' => 26, 'BIOGRAPHY' => 27, 'WEBSITE' => 28, 'TWITTER' => 29, 'FACEBOOK' => 30, 'PHONE' => 31, 'NEWSLETTER' => 32, 'LAST_CONNECT' => 33, 'NB_CONNECTED_DAYS' => 34, 'NB_VIEWS' => 35, 'QUALIFIED' => 36, 'VALIDATED' => 37, 'ONLINE' => 38, 'CREATED_AT' => 39, 'UPDATED_AT' => 40, 'SLUG' => 41, ),
        BasePeer::TYPE_FIELDNAME => array ('id' => 0, 'provider' => 1, 'provider_id' => 2, 'nickname' => 3, 'realname' => 4, 'username' => 5, 'username_canonical' => 6, 'email' => 7, 'email_canonical' => 8, 'enabled' => 9, 'salt' => 10, 'password' => 11, 'last_login' => 12, 'locked' => 13, 'expired' => 14, 'expires_at' => 15, 'confirmation_token' => 16, 'password_requested_at' => 17, 'credentials_expired' => 18, 'credentials_expire_at' => 19, 'roles' => 20, 'p_u_status_id' => 21, 'file_name' => 22, 'gender' => 23, 'firstname' => 24, 'name' => 25, 'birthday' => 26, 'biography' => 27, 'website' => 28, 'twitter' => 29, 'facebook' => 30, 'phone' => 31, 'newsletter' => 32, 'last_connect' => 33, 'nb_connected_days' => 34, 'nb_views' => 35, 'qualified' => 36, 'validated' => 37, 'online' => 38, 'created_at' => 39, 'updated_at' => 40, 'slug' => 41, ),
        BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38, 39, 40, 41, )
    );

    /** The enumerated values for this table */
    protected static $enumValueSets = array(
        PUserPeer::GENDER => array(
            PUserPeer::GENDER_MADAME,
            PUserPeer::GENDER_MONSIEUR,
        ),
    );

    /**
     * Translates a fieldname to another type
     *
     * @param      string $name field name
     * @param      string $fromType One of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME
     *                         BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM
     * @param      string $toType   One of the class type constants
     * @return string          translated name of the field.
     * @throws PropelException - if the specified name could not be found in the fieldname mappings.
     */
    public static function translateFieldName($name, $fromType, $toType)
    {
        $toNames = PUserPeer::getFieldNames($toType);
        $key = isset(PUserPeer::$fieldKeys[$fromType][$name]) ? PUserPeer::$fieldKeys[$fromType][$name] : null;
        if ($key === null) {
            throw new PropelException("'$name' could not be found in the field names of type '$fromType'. These are: " . print_r(PUserPeer::$fieldKeys[$fromType], true));
        }

        return $toNames[$key];
    }

    /**
     * Returns an array of field names.
     *
     * @param      string $type The type of fieldnames to return:
     *                      One of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME
     *                      BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM
     * @return array           A list of field names
     * @throws PropelException - if the type is not valid.
     */
    public static function getFieldNames($type = BasePeer::TYPE_PHPNAME)
    {
        if (!array_key_exists($type, PUserPeer::$fieldNames)) {
            throw new PropelException('Method getFieldNames() expects the parameter $type to be one of the class constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME, BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM. ' . $type . ' was given.');
        }

        return PUserPeer::$fieldNames[$type];
    }

    /**
     * Gets the list of values for all ENUM columns
     * @return array
     */
    public static function getValueSets()
    {
      return PUserPeer::$enumValueSets;
    }

    /**
     * Gets the list of values for an ENUM column
     *
     * @param string $colname The ENUM column name.
     *
     * @return array list of possible values for the column
     */
    public static function getValueSet($colname)
    {
        $valueSets = PUserPeer::getValueSets();

        if (!isset($valueSets[$colname])) {
            throw new PropelException(sprintf('Column "%s" has no ValueSet.', $colname));
        }

        return $valueSets[$colname];
    }

    /**
     * Gets the SQL value for the ENUM column value
     *
     * @param string $colname ENUM column name.
     * @param string $enumVal ENUM value.
     *
     * @return int SQL value
     */
    public static function getSqlValueForEnum($colname, $enumVal)
    {
        $values = PUserPeer::getValueSet($colname);
        if (!in_array($enumVal, $values)) {
            throw new PropelException(sprintf('Value "%s" is not accepted in this enumerated column', $colname));
        }

        return array_search($enumVal, $values);
    }

    /**
     * Convenience method which changes table.column to alias.column.
     *
     * Using this method you can maintain SQL abstraction while using column aliases.
     * <code>
     *		$c->addAlias("alias1", TablePeer::TABLE_NAME);
     *		$c->addJoin(TablePeer::alias("alias1", TablePeer::PRIMARY_KEY_COLUMN), TablePeer::PRIMARY_KEY_COLUMN);
     * </code>
     * @param      string $alias The alias for the current table.
     * @param      string $column The column name for current table. (i.e. PUserPeer::COLUMN_NAME).
     * @return string
     */
    public static function alias($alias, $column)
    {
        return str_replace(PUserPeer::TABLE_NAME.'.', $alias.'.', $column);
    }

    /**
     * Add all the columns needed to create a new object.
     *
     * Note: any columns that were marked with lazyLoad="true" in the
     * XML schema will not be added to the select list and only loaded
     * on demand.
     *
     * @param      Criteria $criteria object containing the columns to add.
     * @param      string   $alias    optional table alias
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function addSelectColumns(Criteria $criteria, $alias = null)
    {
        if (null === $alias) {
            $criteria->addSelectColumn(PUserPeer::ID);
            $criteria->addSelectColumn(PUserPeer::PROVIDER);
            $criteria->addSelectColumn(PUserPeer::PROVIDER_ID);
            $criteria->addSelectColumn(PUserPeer::NICKNAME);
            $criteria->addSelectColumn(PUserPeer::REALNAME);
            $criteria->addSelectColumn(PUserPeer::USERNAME);
            $criteria->addSelectColumn(PUserPeer::USERNAME_CANONICAL);
            $criteria->addSelectColumn(PUserPeer::EMAIL);
            $criteria->addSelectColumn(PUserPeer::EMAIL_CANONICAL);
            $criteria->addSelectColumn(PUserPeer::ENABLED);
            $criteria->addSelectColumn(PUserPeer::SALT);
            $criteria->addSelectColumn(PUserPeer::PASSWORD);
            $criteria->addSelectColumn(PUserPeer::LAST_LOGIN);
            $criteria->addSelectColumn(PUserPeer::LOCKED);
            $criteria->addSelectColumn(PUserPeer::EXPIRED);
            $criteria->addSelectColumn(PUserPeer::EXPIRES_AT);
            $criteria->addSelectColumn(PUserPeer::CONFIRMATION_TOKEN);
            $criteria->addSelectColumn(PUserPeer::PASSWORD_REQUESTED_AT);
            $criteria->addSelectColumn(PUserPeer::CREDENTIALS_EXPIRED);
            $criteria->addSelectColumn(PUserPeer::CREDENTIALS_EXPIRE_AT);
            $criteria->addSelectColumn(PUserPeer::ROLES);
            $criteria->addSelectColumn(PUserPeer::P_U_STATUS_ID);
            $criteria->addSelectColumn(PUserPeer::FILE_NAME);
            $criteria->addSelectColumn(PUserPeer::GENDER);
            $criteria->addSelectColumn(PUserPeer::FIRSTNAME);
            $criteria->addSelectColumn(PUserPeer::NAME);
            $criteria->addSelectColumn(PUserPeer::BIRTHDAY);
            $criteria->addSelectColumn(PUserPeer::BIOGRAPHY);
            $criteria->addSelectColumn(PUserPeer::WEBSITE);
            $criteria->addSelectColumn(PUserPeer::TWITTER);
            $criteria->addSelectColumn(PUserPeer::FACEBOOK);
            $criteria->addSelectColumn(PUserPeer::PHONE);
            $criteria->addSelectColumn(PUserPeer::NEWSLETTER);
            $criteria->addSelectColumn(PUserPeer::LAST_CONNECT);
            $criteria->addSelectColumn(PUserPeer::NB_CONNECTED_DAYS);
            $criteria->addSelectColumn(PUserPeer::NB_VIEWS);
            $criteria->addSelectColumn(PUserPeer::QUALIFIED);
            $criteria->addSelectColumn(PUserPeer::VALIDATED);
            $criteria->addSelectColumn(PUserPeer::ONLINE);
            $criteria->addSelectColumn(PUserPeer::CREATED_AT);
            $criteria->addSelectColumn(PUserPeer::UPDATED_AT);
            $criteria->addSelectColumn(PUserPeer::SLUG);
        } else {
            $criteria->addSelectColumn($alias . '.id');
            $criteria->addSelectColumn($alias . '.provider');
            $criteria->addSelectColumn($alias . '.provider_id');
            $criteria->addSelectColumn($alias . '.nickname');
            $criteria->addSelectColumn($alias . '.realname');
            $criteria->addSelectColumn($alias . '.username');
            $criteria->addSelectColumn($alias . '.username_canonical');
            $criteria->addSelectColumn($alias . '.email');
            $criteria->addSelectColumn($alias . '.email_canonical');
            $criteria->addSelectColumn($alias . '.enabled');
            $criteria->addSelectColumn($alias . '.salt');
            $criteria->addSelectColumn($alias . '.password');
            $criteria->addSelectColumn($alias . '.last_login');
            $criteria->addSelectColumn($alias . '.locked');
            $criteria->addSelectColumn($alias . '.expired');
            $criteria->addSelectColumn($alias . '.expires_at');
            $criteria->addSelectColumn($alias . '.confirmation_token');
            $criteria->addSelectColumn($alias . '.password_requested_at');
            $criteria->addSelectColumn($alias . '.credentials_expired');
            $criteria->addSelectColumn($alias . '.credentials_expire_at');
            $criteria->addSelectColumn($alias . '.roles');
            $criteria->addSelectColumn($alias . '.p_u_status_id');
            $criteria->addSelectColumn($alias . '.file_name');
            $criteria->addSelectColumn($alias . '.gender');
            $criteria->addSelectColumn($alias . '.firstname');
            $criteria->addSelectColumn($alias . '.name');
            $criteria->addSelectColumn($alias . '.birthday');
            $criteria->addSelectColumn($alias . '.biography');
            $criteria->addSelectColumn($alias . '.website');
            $criteria->addSelectColumn($alias . '.twitter');
            $criteria->addSelectColumn($alias . '.facebook');
            $criteria->addSelectColumn($alias . '.phone');
            $criteria->addSelectColumn($alias . '.newsletter');
            $criteria->addSelectColumn($alias . '.last_connect');
            $criteria->addSelectColumn($alias . '.nb_connected_days');
            $criteria->addSelectColumn($alias . '.nb_views');
            $criteria->addSelectColumn($alias . '.qualified');
            $criteria->addSelectColumn($alias . '.validated');
            $criteria->addSelectColumn($alias . '.online');
            $criteria->addSelectColumn($alias . '.created_at');
            $criteria->addSelectColumn($alias . '.updated_at');
            $criteria->addSelectColumn($alias . '.slug');
        }
    }

    /**
     * Returns the number of rows matching criteria.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @return int Number of matching rows.
     */
    public static function doCount(Criteria $criteria, $distinct = false, PropelPDO $con = null)
    {
        // we may modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(PUserPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            PUserPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count
        $criteria->setDbName(PUserPeer::DATABASE_NAME); // Set the correct dbName

        if ($con === null) {
            $con = Propel::getConnection(PUserPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }
        // BasePeer returns a PDOStatement
        $stmt = BasePeer::doCount($criteria, $con);

        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $count = (int) $row[0];
        } else {
            $count = 0; // no rows returned; we infer that means 0 matches.
        }
        $stmt->closeCursor();

        return $count;
    }
    /**
     * Selects one object from the DB.
     *
     * @param      Criteria $criteria object used to create the SELECT statement.
     * @param      PropelPDO $con
     * @return PUser
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectOne(Criteria $criteria, PropelPDO $con = null)
    {
        $critcopy = clone $criteria;
        $critcopy->setLimit(1);
        $objects = PUserPeer::doSelect($critcopy, $con);
        if ($objects) {
            return $objects[0];
        }

        return null;
    }
    /**
     * Selects several row from the DB.
     *
     * @param      Criteria $criteria The Criteria object used to build the SELECT statement.
     * @param      PropelPDO $con
     * @return array           Array of selected Objects
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelect(Criteria $criteria, PropelPDO $con = null)
    {
        return PUserPeer::populateObjects(PUserPeer::doSelectStmt($criteria, $con));
    }
    /**
     * Prepares the Criteria object and uses the parent doSelect() method to execute a PDOStatement.
     *
     * Use this method directly if you want to work with an executed statement directly (for example
     * to perform your own object hydration).
     *
     * @param      Criteria $criteria The Criteria object used to build the SELECT statement.
     * @param      PropelPDO $con The connection to use
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     * @return PDOStatement The executed PDOStatement object.
     * @see        BasePeer::doSelect()
     */
    public static function doSelectStmt(Criteria $criteria, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(PUserPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        if (!$criteria->hasSelectClause()) {
            $criteria = clone $criteria;
            PUserPeer::addSelectColumns($criteria);
        }

        // Set the correct dbName
        $criteria->setDbName(PUserPeer::DATABASE_NAME);

        // BasePeer returns a PDOStatement
        return BasePeer::doSelect($criteria, $con);
    }
    /**
     * Adds an object to the instance pool.
     *
     * Propel keeps cached copies of objects in an instance pool when they are retrieved
     * from the database.  In some cases -- especially when you override doSelect*()
     * methods in your stub classes -- you may need to explicitly add objects
     * to the cache in order to ensure that the same objects are always returned by doSelect*()
     * and retrieveByPK*() calls.
     *
     * @param PUser $obj A PUser object.
     * @param      string $key (optional) key to use for instance map (for performance boost if key was already calculated externally).
     */
    public static function addInstanceToPool($obj, $key = null)
    {
        if (Propel::isInstancePoolingEnabled()) {
            if ($key === null) {
                $key = (string) $obj->getId();
            } // if key === null
            PUserPeer::$instances[$key] = $obj;
        }
    }

    /**
     * Removes an object from the instance pool.
     *
     * Propel keeps cached copies of objects in an instance pool when they are retrieved
     * from the database.  In some cases -- especially when you override doDelete
     * methods in your stub classes -- you may need to explicitly remove objects
     * from the cache in order to prevent returning objects that no longer exist.
     *
     * @param      mixed $value A PUser object or a primary key value.
     *
     * @return void
     * @throws PropelException - if the value is invalid.
     */
    public static function removeInstanceFromPool($value)
    {
        if (Propel::isInstancePoolingEnabled() && $value !== null) {
            if (is_object($value) && $value instanceof PUser) {
                $key = (string) $value->getId();
            } elseif (is_scalar($value)) {
                // assume we've been passed a primary key
                $key = (string) $value;
            } else {
                $e = new PropelException("Invalid value passed to removeInstanceFromPool().  Expected primary key or PUser object; got " . (is_object($value) ? get_class($value) . ' object.' : var_export($value,true)));
                throw $e;
            }

            unset(PUserPeer::$instances[$key]);
        }
    } // removeInstanceFromPool()

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param      string $key The key (@see getPrimaryKeyHash()) for this instance.
     * @return PUser Found object or null if 1) no instance exists for specified key or 2) instance pooling has been disabled.
     * @see        getPrimaryKeyHash()
     */
    public static function getInstanceFromPool($key)
    {
        if (Propel::isInstancePoolingEnabled()) {
            if (isset(PUserPeer::$instances[$key])) {
                return PUserPeer::$instances[$key];
            }
        }

        return null; // just to be explicit
    }

    /**
     * Clear the instance pool.
     *
     * @return void
     */
    public static function clearInstancePool($and_clear_all_references = false)
    {
      if ($and_clear_all_references) {
        foreach (PUserPeer::$instances as $instance) {
          $instance->clearAllReferences(true);
        }
      }
        PUserPeer::$instances = array();
    }

    /**
     * Method to invalidate the instance pool of all tables related to p_user
     * by a foreign key with ON DELETE CASCADE
     */
    public static function clearRelatedInstancePool()
    {
        // Invalidate objects in PTagPeer instance pool,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
        PTagPeer::clearInstancePool();
        // Invalidate objects in POrderPeer instance pool,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
        POrderPeer::clearInstancePool();
        // Invalidate objects in PUFollowDDPeer instance pool,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
        PUFollowDDPeer::clearInstancePool();
        // Invalidate objects in PUBadgesPeer instance pool,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
        PUBadgesPeer::clearInstancePool();
        // Invalidate objects in PUReputationPeer instance pool,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
        PUReputationPeer::clearInstancePool();
        // Invalidate objects in PUTaggedTPeer instance pool,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
        PUTaggedTPeer::clearInstancePool();
        // Invalidate objects in PUFollowTPeer instance pool,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
        PUFollowTPeer::clearInstancePool();
        // Invalidate objects in PURoleQPeer instance pool,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
        PURoleQPeer::clearInstancePool();
        // Invalidate objects in PUMandatePeer instance pool,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
        PUMandatePeer::clearInstancePool();
        // Invalidate objects in PUAffinityQOPeer instance pool,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
        PUAffinityQOPeer::clearInstancePool();
        // Invalidate objects in PUNotificationsPeer instance pool,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
        PUNotificationsPeer::clearInstancePool();
        // Invalidate objects in PUSubscribeEmailPeer instance pool,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
        PUSubscribeEmailPeer::clearInstancePool();
        // Invalidate objects in PUSubscribeScreenPeer instance pool,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
        PUSubscribeScreenPeer::clearInstancePool();
        // Invalidate objects in PDocumentPeer instance pool,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
        PDocumentPeer::clearInstancePool();
        // Invalidate objects in PDCommentPeer instance pool,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
        PDCommentPeer::clearInstancePool();
        // Invalidate objects in PUFollowUPeer instance pool,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
        PUFollowUPeer::clearInstancePool();
        // Invalidate objects in PUFollowUPeer instance pool,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
        PUFollowUPeer::clearInstancePool();
        // Invalidate objects in PDDebatePeer instance pool,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
        PDDebatePeer::clearInstancePool();
        // Invalidate objects in PDReactionPeer instance pool,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
        PDReactionPeer::clearInstancePool();
    }

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param      array $row PropelPDO resultset row.
     * @param      int $startcol The 0-based offset for reading from the resultset row.
     * @return string A string version of PK or null if the components of primary key in result array are all null.
     */
    public static function getPrimaryKeyHashFromRow($row, $startcol = 0)
    {
        // If the PK cannot be derived from the row, return null.
        if ($row[$startcol] === null) {
            return null;
        }

        return (string) $row[$startcol];
    }

    /**
     * Retrieves the primary key from the DB resultset row
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, an array of the primary key columns will be returned.
     *
     * @param      array $row PropelPDO resultset row.
     * @param      int $startcol The 0-based offset for reading from the resultset row.
     * @return mixed The primary key of the row
     */
    public static function getPrimaryKeyFromRow($row, $startcol = 0)
    {

        return (int) $row[$startcol];
    }

    /**
     * The returned array will contain objects of the default type or
     * objects that inherit from the default.
     *
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function populateObjects(PDOStatement $stmt)
    {
        $results = array();

        // set the class once to avoid overhead in the loop
        $cls = PUserPeer::getOMClass();
        // populate the object(s)
        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key = PUserPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj = PUserPeer::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                PUserPeer::addInstanceToPool($obj, $key);
            } // if key exists
        }
        $stmt->closeCursor();

        return $results;
    }
    /**
     * Populates an object of the default type or an object that inherit from the default.
     *
     * @param      array $row PropelPDO resultset row.
     * @param      int $startcol The 0-based offset for reading from the resultset row.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     * @return array (PUser object, last column rank)
     */
    public static function populateObject($row, $startcol = 0)
    {
        $key = PUserPeer::getPrimaryKeyHashFromRow($row, $startcol);
        if (null !== ($obj = PUserPeer::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $startcol, true); // rehydrate
            $col = $startcol + PUserPeer::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = PUserPeer::OM_CLASS;
            $obj = new $cls();
            $col = $obj->hydrate($row, $startcol);
            PUserPeer::addInstanceToPool($obj, $key);
        }

        return array($obj, $col);
    }

    /**
     * Gets the SQL value for Gender ENUM value
     *
     * @param  string $enumVal ENUM value to get SQL value for
     * @return int SQL value
     */
    public static function getGenderSqlValue($enumVal)
    {
        return PUserPeer::getSqlValueForEnum(PUserPeer::GENDER, $enumVal);
    }


    /**
     * Returns the number of rows matching criteria, joining the related PUStatus table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinPUStatus(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(PUserPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            PUserPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(PUserPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(PUserPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(PUserPeer::P_U_STATUS_ID, PUStatusPeer::ID, $join_behavior);

        $stmt = BasePeer::doCount($criteria, $con);

        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $count = (int) $row[0];
        } else {
            $count = 0; // no rows returned; we infer that means 0 matches.
        }
        $stmt->closeCursor();

        return $count;
    }


    /**
     * Selects a collection of PUser objects pre-filled with their PUStatus objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of PUser objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinPUStatus(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(PUserPeer::DATABASE_NAME);
        }

        PUserPeer::addSelectColumns($criteria);
        $startcol = PUserPeer::NUM_HYDRATE_COLUMNS;
        PUStatusPeer::addSelectColumns($criteria);

        $criteria->addJoin(PUserPeer::P_U_STATUS_ID, PUStatusPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = PUserPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = PUserPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = PUserPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                PUserPeer::addInstanceToPool($obj1, $key1);
            } // if $obj1 already loaded

            $key2 = PUStatusPeer::getPrimaryKeyHashFromRow($row, $startcol);
            if ($key2 !== null) {
                $obj2 = PUStatusPeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = PUStatusPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol);
                    PUStatusPeer::addInstanceToPool($obj2, $key2);
                } // if obj2 already loaded

                // Add the $obj1 (PUser) to $obj2 (PUStatus)
                $obj2->addPUser($obj1);

            } // if joined row was not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Returns the number of rows matching criteria, joining all related tables
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAll(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(PUserPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            PUserPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(PUserPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(PUserPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(PUserPeer::P_U_STATUS_ID, PUStatusPeer::ID, $join_behavior);

        $stmt = BasePeer::doCount($criteria, $con);

        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $count = (int) $row[0];
        } else {
            $count = 0; // no rows returned; we infer that means 0 matches.
        }
        $stmt->closeCursor();

        return $count;
    }

    /**
     * Selects a collection of PUser objects pre-filled with all related objects.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of PUser objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAll(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(PUserPeer::DATABASE_NAME);
        }

        PUserPeer::addSelectColumns($criteria);
        $startcol2 = PUserPeer::NUM_HYDRATE_COLUMNS;

        PUStatusPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + PUStatusPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(PUserPeer::P_U_STATUS_ID, PUStatusPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = PUserPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = PUserPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = PUserPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                PUserPeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

            // Add objects for joined PUStatus rows

            $key2 = PUStatusPeer::getPrimaryKeyHashFromRow($row, $startcol2);
            if ($key2 !== null) {
                $obj2 = PUStatusPeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = PUStatusPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    PUStatusPeer::addInstanceToPool($obj2, $key2);
                } // if obj2 loaded

                // Add the $obj1 (PUser) to the collection in $obj2 (PUStatus)
                $obj2->addPUser($obj1);
            } // if joined row not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }

    /**
     * Returns the TableMap related to this peer.
     * This method is not needed for general use but a specific application could have a need.
     * @return TableMap
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function getTableMap()
    {
        return Propel::getDatabaseMap(PUserPeer::DATABASE_NAME)->getTable(PUserPeer::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this peer class.
     */
    public static function buildTableMap()
    {
      $dbMap = Propel::getDatabaseMap(BasePUserPeer::DATABASE_NAME);
      if (!$dbMap->hasTable(BasePUserPeer::TABLE_NAME)) {
        $dbMap->addTableObject(new \Politizr\Model\map\PUserTableMap());
      }
    }

    /**
     * The class that the Peer will make instances of.
     *
     *
     * @return string ClassName
     */
    public static function getOMClass($row = 0, $colnum = 0)
    {
        return PUserPeer::OM_CLASS;
    }

    /**
     * Performs an INSERT on the database, given a PUser or Criteria object.
     *
     * @param      mixed $values Criteria or PUser object containing data that is used to create the INSERT statement.
     * @param      PropelPDO $con the PropelPDO connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doInsert($values, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(PUserPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        if ($values instanceof Criteria) {
            $criteria = clone $values; // rename for clarity
        } else {
            $criteria = $values->buildCriteria(); // build Criteria from PUser object
        }

        if ($criteria->containsKey(PUserPeer::ID) && $criteria->keyContainsValue(PUserPeer::ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.PUserPeer::ID.')');
        }


        // Set the correct dbName
        $criteria->setDbName(PUserPeer::DATABASE_NAME);

        try {
            // use transaction because $criteria could contain info
            // for more than one table (I guess, conceivably)
            $con->beginTransaction();
            $pk = BasePeer::doInsert($criteria, $con);
            $con->commit();
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }

        return $pk;
    }

    /**
     * Performs an UPDATE on the database, given a PUser or Criteria object.
     *
     * @param      mixed $values Criteria or PUser object containing data that is used to create the UPDATE statement.
     * @param      PropelPDO $con The connection to use (specify PropelPDO connection object to exert more control over transactions).
     * @return int             The number of affected rows (if supported by underlying database driver).
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doUpdate($values, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(PUserPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $selectCriteria = new Criteria(PUserPeer::DATABASE_NAME);

        if ($values instanceof Criteria) {
            $criteria = clone $values; // rename for clarity

            $comparison = $criteria->getComparison(PUserPeer::ID);
            $value = $criteria->remove(PUserPeer::ID);
            if ($value) {
                $selectCriteria->add(PUserPeer::ID, $value, $comparison);
            } else {
                $selectCriteria->setPrimaryTableName(PUserPeer::TABLE_NAME);
            }

        } else { // $values is PUser object
            $criteria = $values->buildCriteria(); // gets full criteria
            $selectCriteria = $values->buildPkeyCriteria(); // gets criteria w/ primary key(s)
        }

        // set the correct dbName
        $criteria->setDbName(PUserPeer::DATABASE_NAME);

        return BasePeer::doUpdate($selectCriteria, $criteria, $con);
    }

    /**
     * Deletes all rows from the p_user table.
     *
     * @param      PropelPDO $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).
     * @throws PropelException
     */
    public static function doDeleteAll(PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(PUserPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }
        $affectedRows = 0; // initialize var to track total num of affected rows
        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();
            $affectedRows += BasePeer::doDeleteAll(PUserPeer::TABLE_NAME, $con, PUserPeer::DATABASE_NAME);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            PUserPeer::clearInstancePool();
            PUserPeer::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Performs a DELETE on the database, given a PUser or Criteria object OR a primary key value.
     *
     * @param      mixed $values Criteria or PUser object or primary key or array of primary keys
     *              which is used to create the DELETE statement
     * @param      PropelPDO $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *				if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
     public static function doDelete($values, PropelPDO $con = null)
     {
        if ($con === null) {
            $con = Propel::getConnection(PUserPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        if ($values instanceof Criteria) {
            // invalidate the cache for all objects of this type, since we have no
            // way of knowing (without running a query) what objects should be invalidated
            // from the cache based on this Criteria.
            PUserPeer::clearInstancePool();
            // rename for clarity
            $criteria = clone $values;
        } elseif ($values instanceof PUser) { // it's a model object
            // invalidate the cache for this single object
            PUserPeer::removeInstanceFromPool($values);
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(PUserPeer::DATABASE_NAME);
            $criteria->add(PUserPeer::ID, (array) $values, Criteria::IN);
            // invalidate the cache for this object(s)
            foreach ((array) $values as $singleval) {
                PUserPeer::removeInstanceFromPool($singleval);
            }
        }

        // Set the correct dbName
        $criteria->setDbName(PUserPeer::DATABASE_NAME);

        $affectedRows = 0; // initialize var to track total num of affected rows

        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();

            $affectedRows += BasePeer::doDelete($criteria, $con);
            PUserPeer::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Validates all modified columns of given PUser object.
     * If parameter $columns is either a single column name or an array of column names
     * than only those columns are validated.
     *
     * NOTICE: This does not apply to primary or foreign keys for now.
     *
     * @param PUser $obj The object to validate.
     * @param      mixed $cols Column name or array of column names.
     *
     * @return mixed TRUE if all columns are valid or the error message of the first invalid column.
     */
    public static function doValidate($obj, $cols = null)
    {
        $columns = array();

        if ($cols) {
            $dbMap = Propel::getDatabaseMap(PUserPeer::DATABASE_NAME);
            $tableMap = $dbMap->getTable(PUserPeer::TABLE_NAME);

            if (! is_array($cols)) {
                $cols = array($cols);
            }

            foreach ($cols as $colName) {
                if ($tableMap->hasColumn($colName)) {
                    $get = 'get' . $tableMap->getColumn($colName)->getPhpName();
                    $columns[$colName] = $obj->$get();
                }
            }
        } else {

        }

        return BasePeer::doValidate(PUserPeer::DATABASE_NAME, PUserPeer::TABLE_NAME, $columns);
    }

    /**
     * Retrieve a single object by pkey.
     *
     * @param int $pk the primary key.
     * @param      PropelPDO $con the connection to use
     * @return PUser
     */
    public static function retrieveByPK($pk, PropelPDO $con = null)
    {

        if (null !== ($obj = PUserPeer::getInstanceFromPool((string) $pk))) {
            return $obj;
        }

        if ($con === null) {
            $con = Propel::getConnection(PUserPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria = new Criteria(PUserPeer::DATABASE_NAME);
        $criteria->add(PUserPeer::ID, $pk);

        $v = PUserPeer::doSelect($criteria, $con);

        return !empty($v) > 0 ? $v[0] : null;
    }

    /**
     * Retrieve multiple objects by pkey.
     *
     * @param      array $pks List of primary keys
     * @param      PropelPDO $con the connection to use
     * @return PUser[]
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function retrieveByPKs($pks, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(PUserPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $objs = null;
        if (empty($pks)) {
            $objs = array();
        } else {
            $criteria = new Criteria(PUserPeer::DATABASE_NAME);
            $criteria->add(PUserPeer::ID, $pks, Criteria::IN);
            $objs = PUserPeer::doSelect($criteria, $con);
        }

        return $objs;
    }

} // BasePUserPeer

// This is the static code needed to register the TableMap for this table with the main Propel class.
//
BasePUserPeer::buildTableMap();

