<?php

namespace Politizr\Model\om;

use \BasePeer;
use \Criteria;
use \PDO;
use \PDOStatement;
use \Propel;
use \PropelException;
use \PropelPDO;
use Glorpen\Propel\PropelBundle\Dispatcher\EventDispatcherProxy;
use Glorpen\Propel\PropelBundle\Events\DetectOMClassEvent;
use Glorpen\Propel\PropelBundle\Events\PeerEvent;
use Politizr\Model\PUserArchive;
use Politizr\Model\PUserArchivePeer;
use Politizr\Model\map\PUserArchiveTableMap;

abstract class BasePUserArchivePeer
{

    /** the default database name for this class */
    const DATABASE_NAME = 'default';

    /** the table name for this class */
    const TABLE_NAME = 'p_user_archive';

    /** the related Propel class for this table */
    const OM_CLASS = 'Politizr\\Model\\PUserArchive';

    /** the related TableMap class for this table */
    const TM_CLASS = 'Politizr\\Model\\map\\PUserArchiveTableMap';

    /** The total number of columns. */
    const NUM_COLUMNS = 45;

    /** The number of lazy-loaded columns. */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /** The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS) */
    const NUM_HYDRATE_COLUMNS = 45;

    /** the column name for the id field */
    const ID = 'p_user_archive.id';

    /** the column name for the provider field */
    const PROVIDER = 'p_user_archive.provider';

    /** the column name for the provider_id field */
    const PROVIDER_ID = 'p_user_archive.provider_id';

    /** the column name for the nickname field */
    const NICKNAME = 'p_user_archive.nickname';

    /** the column name for the realname field */
    const REALNAME = 'p_user_archive.realname';

    /** the column name for the username field */
    const USERNAME = 'p_user_archive.username';

    /** the column name for the username_canonical field */
    const USERNAME_CANONICAL = 'p_user_archive.username_canonical';

    /** the column name for the email field */
    const EMAIL = 'p_user_archive.email';

    /** the column name for the email_canonical field */
    const EMAIL_CANONICAL = 'p_user_archive.email_canonical';

    /** the column name for the enabled field */
    const ENABLED = 'p_user_archive.enabled';

    /** the column name for the salt field */
    const SALT = 'p_user_archive.salt';

    /** the column name for the password field */
    const PASSWORD = 'p_user_archive.password';

    /** the column name for the last_login field */
    const LAST_LOGIN = 'p_user_archive.last_login';

    /** the column name for the locked field */
    const LOCKED = 'p_user_archive.locked';

    /** the column name for the expired field */
    const EXPIRED = 'p_user_archive.expired';

    /** the column name for the expires_at field */
    const EXPIRES_AT = 'p_user_archive.expires_at';

    /** the column name for the confirmation_token field */
    const CONFIRMATION_TOKEN = 'p_user_archive.confirmation_token';

    /** the column name for the password_requested_at field */
    const PASSWORD_REQUESTED_AT = 'p_user_archive.password_requested_at';

    /** the column name for the credentials_expired field */
    const CREDENTIALS_EXPIRED = 'p_user_archive.credentials_expired';

    /** the column name for the credentials_expire_at field */
    const CREDENTIALS_EXPIRE_AT = 'p_user_archive.credentials_expire_at';

    /** the column name for the roles field */
    const ROLES = 'p_user_archive.roles';

    /** the column name for the p_u_status_id field */
    const P_U_STATUS_ID = 'p_user_archive.p_u_status_id';

    /** the column name for the file_name field */
    const FILE_NAME = 'p_user_archive.file_name';

    /** the column name for the back_file_name field */
    const BACK_FILE_NAME = 'p_user_archive.back_file_name';

    /** the column name for the gender field */
    const GENDER = 'p_user_archive.gender';

    /** the column name for the firstname field */
    const FIRSTNAME = 'p_user_archive.firstname';

    /** the column name for the name field */
    const NAME = 'p_user_archive.name';

    /** the column name for the birthday field */
    const BIRTHDAY = 'p_user_archive.birthday';

    /** the column name for the subtitle field */
    const SUBTITLE = 'p_user_archive.subtitle';

    /** the column name for the biography field */
    const BIOGRAPHY = 'p_user_archive.biography';

    /** the column name for the website field */
    const WEBSITE = 'p_user_archive.website';

    /** the column name for the twitter field */
    const TWITTER = 'p_user_archive.twitter';

    /** the column name for the facebook field */
    const FACEBOOK = 'p_user_archive.facebook';

    /** the column name for the phone field */
    const PHONE = 'p_user_archive.phone';

    /** the column name for the newsletter field */
    const NEWSLETTER = 'p_user_archive.newsletter';

    /** the column name for the last_connect field */
    const LAST_CONNECT = 'p_user_archive.last_connect';

    /** the column name for the nb_connected_days field */
    const NB_CONNECTED_DAYS = 'p_user_archive.nb_connected_days';

    /** the column name for the nb_views field */
    const NB_VIEWS = 'p_user_archive.nb_views';

    /** the column name for the qualified field */
    const QUALIFIED = 'p_user_archive.qualified';

    /** the column name for the validated field */
    const VALIDATED = 'p_user_archive.validated';

    /** the column name for the online field */
    const ONLINE = 'p_user_archive.online';

    /** the column name for the created_at field */
    const CREATED_AT = 'p_user_archive.created_at';

    /** the column name for the updated_at field */
    const UPDATED_AT = 'p_user_archive.updated_at';

    /** the column name for the slug field */
    const SLUG = 'p_user_archive.slug';

    /** the column name for the archived_at field */
    const ARCHIVED_AT = 'p_user_archive.archived_at';

    /** The enumerated values for the gender field */
    const GENDER_MADAME = 'Madame';
    const GENDER_MONSIEUR = 'Monsieur';

    /** The default string format for model objects of the related table **/
    const DEFAULT_STRING_FORMAT = 'YAML';

    /**
     * An identity map to hold any loaded instances of PUserArchive objects.
     * This must be public so that other peer classes can access this when hydrating from JOIN
     * queries.
     * @var        array PUserArchive[]
     */
    public static $instances = array();


    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. PUserArchivePeer::$fieldNames[PUserArchivePeer::TYPE_PHPNAME][0] = 'Id'
     */
    protected static $fieldNames = array (
        BasePeer::TYPE_PHPNAME => array ('Id', 'Provider', 'ProviderId', 'Nickname', 'Realname', 'Username', 'UsernameCanonical', 'Email', 'EmailCanonical', 'Enabled', 'Salt', 'Password', 'LastLogin', 'Locked', 'Expired', 'ExpiresAt', 'ConfirmationToken', 'PasswordRequestedAt', 'CredentialsExpired', 'CredentialsExpireAt', 'Roles', 'PUStatusId', 'FileName', 'BackFileName', 'Gender', 'Firstname', 'Name', 'Birthday', 'Subtitle', 'Biography', 'Website', 'Twitter', 'Facebook', 'Phone', 'Newsletter', 'LastConnect', 'NbConnectedDays', 'NbViews', 'Qualified', 'Validated', 'Online', 'CreatedAt', 'UpdatedAt', 'Slug', 'ArchivedAt', ),
        BasePeer::TYPE_STUDLYPHPNAME => array ('id', 'provider', 'providerId', 'nickname', 'realname', 'username', 'usernameCanonical', 'email', 'emailCanonical', 'enabled', 'salt', 'password', 'lastLogin', 'locked', 'expired', 'expiresAt', 'confirmationToken', 'passwordRequestedAt', 'credentialsExpired', 'credentialsExpireAt', 'roles', 'pUStatusId', 'fileName', 'backFileName', 'gender', 'firstname', 'name', 'birthday', 'subtitle', 'biography', 'website', 'twitter', 'facebook', 'phone', 'newsletter', 'lastConnect', 'nbConnectedDays', 'nbViews', 'qualified', 'validated', 'online', 'createdAt', 'updatedAt', 'slug', 'archivedAt', ),
        BasePeer::TYPE_COLNAME => array (PUserArchivePeer::ID, PUserArchivePeer::PROVIDER, PUserArchivePeer::PROVIDER_ID, PUserArchivePeer::NICKNAME, PUserArchivePeer::REALNAME, PUserArchivePeer::USERNAME, PUserArchivePeer::USERNAME_CANONICAL, PUserArchivePeer::EMAIL, PUserArchivePeer::EMAIL_CANONICAL, PUserArchivePeer::ENABLED, PUserArchivePeer::SALT, PUserArchivePeer::PASSWORD, PUserArchivePeer::LAST_LOGIN, PUserArchivePeer::LOCKED, PUserArchivePeer::EXPIRED, PUserArchivePeer::EXPIRES_AT, PUserArchivePeer::CONFIRMATION_TOKEN, PUserArchivePeer::PASSWORD_REQUESTED_AT, PUserArchivePeer::CREDENTIALS_EXPIRED, PUserArchivePeer::CREDENTIALS_EXPIRE_AT, PUserArchivePeer::ROLES, PUserArchivePeer::P_U_STATUS_ID, PUserArchivePeer::FILE_NAME, PUserArchivePeer::BACK_FILE_NAME, PUserArchivePeer::GENDER, PUserArchivePeer::FIRSTNAME, PUserArchivePeer::NAME, PUserArchivePeer::BIRTHDAY, PUserArchivePeer::SUBTITLE, PUserArchivePeer::BIOGRAPHY, PUserArchivePeer::WEBSITE, PUserArchivePeer::TWITTER, PUserArchivePeer::FACEBOOK, PUserArchivePeer::PHONE, PUserArchivePeer::NEWSLETTER, PUserArchivePeer::LAST_CONNECT, PUserArchivePeer::NB_CONNECTED_DAYS, PUserArchivePeer::NB_VIEWS, PUserArchivePeer::QUALIFIED, PUserArchivePeer::VALIDATED, PUserArchivePeer::ONLINE, PUserArchivePeer::CREATED_AT, PUserArchivePeer::UPDATED_AT, PUserArchivePeer::SLUG, PUserArchivePeer::ARCHIVED_AT, ),
        BasePeer::TYPE_RAW_COLNAME => array ('ID', 'PROVIDER', 'PROVIDER_ID', 'NICKNAME', 'REALNAME', 'USERNAME', 'USERNAME_CANONICAL', 'EMAIL', 'EMAIL_CANONICAL', 'ENABLED', 'SALT', 'PASSWORD', 'LAST_LOGIN', 'LOCKED', 'EXPIRED', 'EXPIRES_AT', 'CONFIRMATION_TOKEN', 'PASSWORD_REQUESTED_AT', 'CREDENTIALS_EXPIRED', 'CREDENTIALS_EXPIRE_AT', 'ROLES', 'P_U_STATUS_ID', 'FILE_NAME', 'BACK_FILE_NAME', 'GENDER', 'FIRSTNAME', 'NAME', 'BIRTHDAY', 'SUBTITLE', 'BIOGRAPHY', 'WEBSITE', 'TWITTER', 'FACEBOOK', 'PHONE', 'NEWSLETTER', 'LAST_CONNECT', 'NB_CONNECTED_DAYS', 'NB_VIEWS', 'QUALIFIED', 'VALIDATED', 'ONLINE', 'CREATED_AT', 'UPDATED_AT', 'SLUG', 'ARCHIVED_AT', ),
        BasePeer::TYPE_FIELDNAME => array ('id', 'provider', 'provider_id', 'nickname', 'realname', 'username', 'username_canonical', 'email', 'email_canonical', 'enabled', 'salt', 'password', 'last_login', 'locked', 'expired', 'expires_at', 'confirmation_token', 'password_requested_at', 'credentials_expired', 'credentials_expire_at', 'roles', 'p_u_status_id', 'file_name', 'back_file_name', 'gender', 'firstname', 'name', 'birthday', 'subtitle', 'biography', 'website', 'twitter', 'facebook', 'phone', 'newsletter', 'last_connect', 'nb_connected_days', 'nb_views', 'qualified', 'validated', 'online', 'created_at', 'updated_at', 'slug', 'archived_at', ),
        BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38, 39, 40, 41, 42, 43, 44, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. PUserArchivePeer::$fieldNames[BasePeer::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        BasePeer::TYPE_PHPNAME => array ('Id' => 0, 'Provider' => 1, 'ProviderId' => 2, 'Nickname' => 3, 'Realname' => 4, 'Username' => 5, 'UsernameCanonical' => 6, 'Email' => 7, 'EmailCanonical' => 8, 'Enabled' => 9, 'Salt' => 10, 'Password' => 11, 'LastLogin' => 12, 'Locked' => 13, 'Expired' => 14, 'ExpiresAt' => 15, 'ConfirmationToken' => 16, 'PasswordRequestedAt' => 17, 'CredentialsExpired' => 18, 'CredentialsExpireAt' => 19, 'Roles' => 20, 'PUStatusId' => 21, 'FileName' => 22, 'BackFileName' => 23, 'Gender' => 24, 'Firstname' => 25, 'Name' => 26, 'Birthday' => 27, 'Subtitle' => 28, 'Biography' => 29, 'Website' => 30, 'Twitter' => 31, 'Facebook' => 32, 'Phone' => 33, 'Newsletter' => 34, 'LastConnect' => 35, 'NbConnectedDays' => 36, 'NbViews' => 37, 'Qualified' => 38, 'Validated' => 39, 'Online' => 40, 'CreatedAt' => 41, 'UpdatedAt' => 42, 'Slug' => 43, 'ArchivedAt' => 44, ),
        BasePeer::TYPE_STUDLYPHPNAME => array ('id' => 0, 'provider' => 1, 'providerId' => 2, 'nickname' => 3, 'realname' => 4, 'username' => 5, 'usernameCanonical' => 6, 'email' => 7, 'emailCanonical' => 8, 'enabled' => 9, 'salt' => 10, 'password' => 11, 'lastLogin' => 12, 'locked' => 13, 'expired' => 14, 'expiresAt' => 15, 'confirmationToken' => 16, 'passwordRequestedAt' => 17, 'credentialsExpired' => 18, 'credentialsExpireAt' => 19, 'roles' => 20, 'pUStatusId' => 21, 'fileName' => 22, 'backFileName' => 23, 'gender' => 24, 'firstname' => 25, 'name' => 26, 'birthday' => 27, 'subtitle' => 28, 'biography' => 29, 'website' => 30, 'twitter' => 31, 'facebook' => 32, 'phone' => 33, 'newsletter' => 34, 'lastConnect' => 35, 'nbConnectedDays' => 36, 'nbViews' => 37, 'qualified' => 38, 'validated' => 39, 'online' => 40, 'createdAt' => 41, 'updatedAt' => 42, 'slug' => 43, 'archivedAt' => 44, ),
        BasePeer::TYPE_COLNAME => array (PUserArchivePeer::ID => 0, PUserArchivePeer::PROVIDER => 1, PUserArchivePeer::PROVIDER_ID => 2, PUserArchivePeer::NICKNAME => 3, PUserArchivePeer::REALNAME => 4, PUserArchivePeer::USERNAME => 5, PUserArchivePeer::USERNAME_CANONICAL => 6, PUserArchivePeer::EMAIL => 7, PUserArchivePeer::EMAIL_CANONICAL => 8, PUserArchivePeer::ENABLED => 9, PUserArchivePeer::SALT => 10, PUserArchivePeer::PASSWORD => 11, PUserArchivePeer::LAST_LOGIN => 12, PUserArchivePeer::LOCKED => 13, PUserArchivePeer::EXPIRED => 14, PUserArchivePeer::EXPIRES_AT => 15, PUserArchivePeer::CONFIRMATION_TOKEN => 16, PUserArchivePeer::PASSWORD_REQUESTED_AT => 17, PUserArchivePeer::CREDENTIALS_EXPIRED => 18, PUserArchivePeer::CREDENTIALS_EXPIRE_AT => 19, PUserArchivePeer::ROLES => 20, PUserArchivePeer::P_U_STATUS_ID => 21, PUserArchivePeer::FILE_NAME => 22, PUserArchivePeer::BACK_FILE_NAME => 23, PUserArchivePeer::GENDER => 24, PUserArchivePeer::FIRSTNAME => 25, PUserArchivePeer::NAME => 26, PUserArchivePeer::BIRTHDAY => 27, PUserArchivePeer::SUBTITLE => 28, PUserArchivePeer::BIOGRAPHY => 29, PUserArchivePeer::WEBSITE => 30, PUserArchivePeer::TWITTER => 31, PUserArchivePeer::FACEBOOK => 32, PUserArchivePeer::PHONE => 33, PUserArchivePeer::NEWSLETTER => 34, PUserArchivePeer::LAST_CONNECT => 35, PUserArchivePeer::NB_CONNECTED_DAYS => 36, PUserArchivePeer::NB_VIEWS => 37, PUserArchivePeer::QUALIFIED => 38, PUserArchivePeer::VALIDATED => 39, PUserArchivePeer::ONLINE => 40, PUserArchivePeer::CREATED_AT => 41, PUserArchivePeer::UPDATED_AT => 42, PUserArchivePeer::SLUG => 43, PUserArchivePeer::ARCHIVED_AT => 44, ),
        BasePeer::TYPE_RAW_COLNAME => array ('ID' => 0, 'PROVIDER' => 1, 'PROVIDER_ID' => 2, 'NICKNAME' => 3, 'REALNAME' => 4, 'USERNAME' => 5, 'USERNAME_CANONICAL' => 6, 'EMAIL' => 7, 'EMAIL_CANONICAL' => 8, 'ENABLED' => 9, 'SALT' => 10, 'PASSWORD' => 11, 'LAST_LOGIN' => 12, 'LOCKED' => 13, 'EXPIRED' => 14, 'EXPIRES_AT' => 15, 'CONFIRMATION_TOKEN' => 16, 'PASSWORD_REQUESTED_AT' => 17, 'CREDENTIALS_EXPIRED' => 18, 'CREDENTIALS_EXPIRE_AT' => 19, 'ROLES' => 20, 'P_U_STATUS_ID' => 21, 'FILE_NAME' => 22, 'BACK_FILE_NAME' => 23, 'GENDER' => 24, 'FIRSTNAME' => 25, 'NAME' => 26, 'BIRTHDAY' => 27, 'SUBTITLE' => 28, 'BIOGRAPHY' => 29, 'WEBSITE' => 30, 'TWITTER' => 31, 'FACEBOOK' => 32, 'PHONE' => 33, 'NEWSLETTER' => 34, 'LAST_CONNECT' => 35, 'NB_CONNECTED_DAYS' => 36, 'NB_VIEWS' => 37, 'QUALIFIED' => 38, 'VALIDATED' => 39, 'ONLINE' => 40, 'CREATED_AT' => 41, 'UPDATED_AT' => 42, 'SLUG' => 43, 'ARCHIVED_AT' => 44, ),
        BasePeer::TYPE_FIELDNAME => array ('id' => 0, 'provider' => 1, 'provider_id' => 2, 'nickname' => 3, 'realname' => 4, 'username' => 5, 'username_canonical' => 6, 'email' => 7, 'email_canonical' => 8, 'enabled' => 9, 'salt' => 10, 'password' => 11, 'last_login' => 12, 'locked' => 13, 'expired' => 14, 'expires_at' => 15, 'confirmation_token' => 16, 'password_requested_at' => 17, 'credentials_expired' => 18, 'credentials_expire_at' => 19, 'roles' => 20, 'p_u_status_id' => 21, 'file_name' => 22, 'back_file_name' => 23, 'gender' => 24, 'firstname' => 25, 'name' => 26, 'birthday' => 27, 'subtitle' => 28, 'biography' => 29, 'website' => 30, 'twitter' => 31, 'facebook' => 32, 'phone' => 33, 'newsletter' => 34, 'last_connect' => 35, 'nb_connected_days' => 36, 'nb_views' => 37, 'qualified' => 38, 'validated' => 39, 'online' => 40, 'created_at' => 41, 'updated_at' => 42, 'slug' => 43, 'archived_at' => 44, ),
        BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38, 39, 40, 41, 42, 43, 44, )
    );

    /** The enumerated values for this table */
    protected static $enumValueSets = array(
        PUserArchivePeer::GENDER => array(
            PUserArchivePeer::GENDER_MADAME,
            PUserArchivePeer::GENDER_MONSIEUR,
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
        $toNames = PUserArchivePeer::getFieldNames($toType);
        $key = isset(PUserArchivePeer::$fieldKeys[$fromType][$name]) ? PUserArchivePeer::$fieldKeys[$fromType][$name] : null;
        if ($key === null) {
            throw new PropelException("'$name' could not be found in the field names of type '$fromType'. These are: " . print_r(PUserArchivePeer::$fieldKeys[$fromType], true));
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
        if (!array_key_exists($type, PUserArchivePeer::$fieldNames)) {
            throw new PropelException('Method getFieldNames() expects the parameter $type to be one of the class constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME, BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM. ' . $type . ' was given.');
        }

        return PUserArchivePeer::$fieldNames[$type];
    }

    /**
     * Gets the list of values for all ENUM columns
     * @return array
     */
    public static function getValueSets()
    {
      return PUserArchivePeer::$enumValueSets;
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
        $valueSets = PUserArchivePeer::getValueSets();

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
        $values = PUserArchivePeer::getValueSet($colname);
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
     * @param      string $column The column name for current table. (i.e. PUserArchivePeer::COLUMN_NAME).
     * @return string
     */
    public static function alias($alias, $column)
    {
        return str_replace(PUserArchivePeer::TABLE_NAME.'.', $alias.'.', $column);
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
            $criteria->addSelectColumn(PUserArchivePeer::ID);
            $criteria->addSelectColumn(PUserArchivePeer::PROVIDER);
            $criteria->addSelectColumn(PUserArchivePeer::PROVIDER_ID);
            $criteria->addSelectColumn(PUserArchivePeer::NICKNAME);
            $criteria->addSelectColumn(PUserArchivePeer::REALNAME);
            $criteria->addSelectColumn(PUserArchivePeer::USERNAME);
            $criteria->addSelectColumn(PUserArchivePeer::USERNAME_CANONICAL);
            $criteria->addSelectColumn(PUserArchivePeer::EMAIL);
            $criteria->addSelectColumn(PUserArchivePeer::EMAIL_CANONICAL);
            $criteria->addSelectColumn(PUserArchivePeer::ENABLED);
            $criteria->addSelectColumn(PUserArchivePeer::SALT);
            $criteria->addSelectColumn(PUserArchivePeer::PASSWORD);
            $criteria->addSelectColumn(PUserArchivePeer::LAST_LOGIN);
            $criteria->addSelectColumn(PUserArchivePeer::LOCKED);
            $criteria->addSelectColumn(PUserArchivePeer::EXPIRED);
            $criteria->addSelectColumn(PUserArchivePeer::EXPIRES_AT);
            $criteria->addSelectColumn(PUserArchivePeer::CONFIRMATION_TOKEN);
            $criteria->addSelectColumn(PUserArchivePeer::PASSWORD_REQUESTED_AT);
            $criteria->addSelectColumn(PUserArchivePeer::CREDENTIALS_EXPIRED);
            $criteria->addSelectColumn(PUserArchivePeer::CREDENTIALS_EXPIRE_AT);
            $criteria->addSelectColumn(PUserArchivePeer::ROLES);
            $criteria->addSelectColumn(PUserArchivePeer::P_U_STATUS_ID);
            $criteria->addSelectColumn(PUserArchivePeer::FILE_NAME);
            $criteria->addSelectColumn(PUserArchivePeer::BACK_FILE_NAME);
            $criteria->addSelectColumn(PUserArchivePeer::GENDER);
            $criteria->addSelectColumn(PUserArchivePeer::FIRSTNAME);
            $criteria->addSelectColumn(PUserArchivePeer::NAME);
            $criteria->addSelectColumn(PUserArchivePeer::BIRTHDAY);
            $criteria->addSelectColumn(PUserArchivePeer::SUBTITLE);
            $criteria->addSelectColumn(PUserArchivePeer::BIOGRAPHY);
            $criteria->addSelectColumn(PUserArchivePeer::WEBSITE);
            $criteria->addSelectColumn(PUserArchivePeer::TWITTER);
            $criteria->addSelectColumn(PUserArchivePeer::FACEBOOK);
            $criteria->addSelectColumn(PUserArchivePeer::PHONE);
            $criteria->addSelectColumn(PUserArchivePeer::NEWSLETTER);
            $criteria->addSelectColumn(PUserArchivePeer::LAST_CONNECT);
            $criteria->addSelectColumn(PUserArchivePeer::NB_CONNECTED_DAYS);
            $criteria->addSelectColumn(PUserArchivePeer::NB_VIEWS);
            $criteria->addSelectColumn(PUserArchivePeer::QUALIFIED);
            $criteria->addSelectColumn(PUserArchivePeer::VALIDATED);
            $criteria->addSelectColumn(PUserArchivePeer::ONLINE);
            $criteria->addSelectColumn(PUserArchivePeer::CREATED_AT);
            $criteria->addSelectColumn(PUserArchivePeer::UPDATED_AT);
            $criteria->addSelectColumn(PUserArchivePeer::SLUG);
            $criteria->addSelectColumn(PUserArchivePeer::ARCHIVED_AT);
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
            $criteria->addSelectColumn($alias . '.back_file_name');
            $criteria->addSelectColumn($alias . '.gender');
            $criteria->addSelectColumn($alias . '.firstname');
            $criteria->addSelectColumn($alias . '.name');
            $criteria->addSelectColumn($alias . '.birthday');
            $criteria->addSelectColumn($alias . '.subtitle');
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
            $criteria->addSelectColumn($alias . '.archived_at');
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
        $criteria->setPrimaryTableName(PUserArchivePeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            PUserArchivePeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count
        $criteria->setDbName(PUserArchivePeer::DATABASE_NAME); // Set the correct dbName

        if ($con === null) {
            $con = Propel::getConnection(PUserArchivePeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return PUserArchive
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectOne(Criteria $criteria, PropelPDO $con = null)
    {
        $critcopy = clone $criteria;
        $critcopy->setLimit(1);
        $objects = PUserArchivePeer::doSelect($critcopy, $con);
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
        return PUserArchivePeer::populateObjects(PUserArchivePeer::doSelectStmt($criteria, $con));
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
            $con = Propel::getConnection(PUserArchivePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        if (!$criteria->hasSelectClause()) {
            $criteria = clone $criteria;
            PUserArchivePeer::addSelectColumns($criteria);
        }

        // Set the correct dbName
        $criteria->setDbName(PUserArchivePeer::DATABASE_NAME);

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
     * @param PUserArchive $obj A PUserArchive object.
     * @param      string $key (optional) key to use for instance map (for performance boost if key was already calculated externally).
     */
    public static function addInstanceToPool($obj, $key = null)
    {
        if (Propel::isInstancePoolingEnabled()) {
            if ($key === null) {
                $key = (string) $obj->getId();
            } // if key === null
            PUserArchivePeer::$instances[$key] = $obj;
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
     * @param      mixed $value A PUserArchive object or a primary key value.
     *
     * @return void
     * @throws PropelException - if the value is invalid.
     */
    public static function removeInstanceFromPool($value)
    {
        if (Propel::isInstancePoolingEnabled() && $value !== null) {
            if (is_object($value) && $value instanceof PUserArchive) {
                $key = (string) $value->getId();
            } elseif (is_scalar($value)) {
                // assume we've been passed a primary key
                $key = (string) $value;
            } else {
                $e = new PropelException("Invalid value passed to removeInstanceFromPool().  Expected primary key or PUserArchive object; got " . (is_object($value) ? get_class($value) . ' object.' : var_export($value,true)));
                throw $e;
            }

            unset(PUserArchivePeer::$instances[$key]);
        }
    } // removeInstanceFromPool()

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param      string $key The key (@see getPrimaryKeyHash()) for this instance.
     * @return PUserArchive Found object or null if 1) no instance exists for specified key or 2) instance pooling has been disabled.
     * @see        getPrimaryKeyHash()
     */
    public static function getInstanceFromPool($key)
    {
        if (Propel::isInstancePoolingEnabled()) {
            if (isset(PUserArchivePeer::$instances[$key])) {
                return PUserArchivePeer::$instances[$key];
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
        foreach (PUserArchivePeer::$instances as $instance) {
          $instance->clearAllReferences(true);
        }
      }
        PUserArchivePeer::$instances = array();
    }

    /**
     * Method to invalidate the instance pool of all tables related to p_user_archive
     * by a foreign key with ON DELETE CASCADE
     */
    public static function clearRelatedInstancePool()
    {
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
        $cls = PUserArchivePeer::getOMClass();
        // populate the object(s)
        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key = PUserArchivePeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj = PUserArchivePeer::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                PUserArchivePeer::addInstanceToPool($obj, $key);
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
     * @return array (PUserArchive object, last column rank)
     */
    public static function populateObject($row, $startcol = 0)
    {
        $key = PUserArchivePeer::getPrimaryKeyHashFromRow($row, $startcol);
        if (null !== ($obj = PUserArchivePeer::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $startcol, true); // rehydrate
            $col = $startcol + PUserArchivePeer::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = PUserArchivePeer::getOMClass($row, $startcol);
            $obj = new $cls();
            $col = $obj->hydrate($row, $startcol);
            PUserArchivePeer::addInstanceToPool($obj, $key);
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
        return PUserArchivePeer::getSqlValueForEnum(PUserArchivePeer::GENDER, $enumVal);
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
        return Propel::getDatabaseMap(PUserArchivePeer::DATABASE_NAME)->getTable(PUserArchivePeer::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this peer class.
     */
    public static function buildTableMap()
    {
      $dbMap = Propel::getDatabaseMap(BasePUserArchivePeer::DATABASE_NAME);
      if (!$dbMap->hasTable(BasePUserArchivePeer::TABLE_NAME)) {
        $dbMap->addTableObject(new \Politizr\Model\map\PUserArchiveTableMap());
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

        $event = new DetectOMClassEvent(PUserArchivePeer::OM_CLASS, $row, $colnum);
        EventDispatcherProxy::trigger('om.detect', $event);
        if($event->isDetected()){
            return $event->getDetectedClass();
        }

        return PUserArchivePeer::OM_CLASS;
    }

    /**
     * Performs an INSERT on the database, given a PUserArchive or Criteria object.
     *
     * @param      mixed $values Criteria or PUserArchive object containing data that is used to create the INSERT statement.
     * @param      PropelPDO $con the PropelPDO connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doInsert($values, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(PUserArchivePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        if ($values instanceof Criteria) {
            $criteria = clone $values; // rename for clarity
        } else {
            $criteria = $values->buildCriteria(); // build Criteria from PUserArchive object
        }


        // Set the correct dbName
        $criteria->setDbName(PUserArchivePeer::DATABASE_NAME);

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
     * Performs an UPDATE on the database, given a PUserArchive or Criteria object.
     *
     * @param      mixed $values Criteria or PUserArchive object containing data that is used to create the UPDATE statement.
     * @param      PropelPDO $con The connection to use (specify PropelPDO connection object to exert more control over transactions).
     * @return int             The number of affected rows (if supported by underlying database driver).
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doUpdate($values, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(PUserArchivePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $selectCriteria = new Criteria(PUserArchivePeer::DATABASE_NAME);

        if ($values instanceof Criteria) {
            $criteria = clone $values; // rename for clarity

            $comparison = $criteria->getComparison(PUserArchivePeer::ID);
            $value = $criteria->remove(PUserArchivePeer::ID);
            if ($value) {
                $selectCriteria->add(PUserArchivePeer::ID, $value, $comparison);
            } else {
                $selectCriteria->setPrimaryTableName(PUserArchivePeer::TABLE_NAME);
            }

        } else { // $values is PUserArchive object
            $criteria = $values->buildCriteria(); // gets full criteria
            $selectCriteria = $values->buildPkeyCriteria(); // gets criteria w/ primary key(s)
        }

        // set the correct dbName
        $criteria->setDbName(PUserArchivePeer::DATABASE_NAME);

        return BasePeer::doUpdate($selectCriteria, $criteria, $con);
    }

    /**
     * Deletes all rows from the p_user_archive table.
     *
     * @param      PropelPDO $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).
     * @throws PropelException
     */
    public static function doDeleteAll(PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(PUserArchivePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }
        $affectedRows = 0; // initialize var to track total num of affected rows
        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();
            $affectedRows += BasePeer::doDeleteAll(PUserArchivePeer::TABLE_NAME, $con, PUserArchivePeer::DATABASE_NAME);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            PUserArchivePeer::clearInstancePool();
            PUserArchivePeer::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Performs a DELETE on the database, given a PUserArchive or Criteria object OR a primary key value.
     *
     * @param      mixed $values Criteria or PUserArchive object or primary key or array of primary keys
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
            $con = Propel::getConnection(PUserArchivePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        if ($values instanceof Criteria) {
            // invalidate the cache for all objects of this type, since we have no
            // way of knowing (without running a query) what objects should be invalidated
            // from the cache based on this Criteria.
            PUserArchivePeer::clearInstancePool();
            // rename for clarity
            $criteria = clone $values;
        } elseif ($values instanceof PUserArchive) { // it's a model object
            // invalidate the cache for this single object
            PUserArchivePeer::removeInstanceFromPool($values);
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(PUserArchivePeer::DATABASE_NAME);
            $criteria->add(PUserArchivePeer::ID, (array) $values, Criteria::IN);
            // invalidate the cache for this object(s)
            foreach ((array) $values as $singleval) {
                PUserArchivePeer::removeInstanceFromPool($singleval);
            }
        }

        // Set the correct dbName
        $criteria->setDbName(PUserArchivePeer::DATABASE_NAME);

        $affectedRows = 0; // initialize var to track total num of affected rows

        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();

            $affectedRows += BasePeer::doDelete($criteria, $con);
            PUserArchivePeer::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Validates all modified columns of given PUserArchive object.
     * If parameter $columns is either a single column name or an array of column names
     * than only those columns are validated.
     *
     * NOTICE: This does not apply to primary or foreign keys for now.
     *
     * @param PUserArchive $obj The object to validate.
     * @param      mixed $cols Column name or array of column names.
     *
     * @return mixed TRUE if all columns are valid or the error message of the first invalid column.
     */
    public static function doValidate($obj, $cols = null)
    {
        $columns = array();

        if ($cols) {
            $dbMap = Propel::getDatabaseMap(PUserArchivePeer::DATABASE_NAME);
            $tableMap = $dbMap->getTable(PUserArchivePeer::TABLE_NAME);

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

        return BasePeer::doValidate(PUserArchivePeer::DATABASE_NAME, PUserArchivePeer::TABLE_NAME, $columns);
    }

    /**
     * Retrieve a single object by pkey.
     *
     * @param int $pk the primary key.
     * @param      PropelPDO $con the connection to use
     * @return PUserArchive
     */
    public static function retrieveByPK($pk, PropelPDO $con = null)
    {

        if (null !== ($obj = PUserArchivePeer::getInstanceFromPool((string) $pk))) {
            return $obj;
        }

        if ($con === null) {
            $con = Propel::getConnection(PUserArchivePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria = new Criteria(PUserArchivePeer::DATABASE_NAME);
        $criteria->add(PUserArchivePeer::ID, $pk);

        $v = PUserArchivePeer::doSelect($criteria, $con);

        return !empty($v) > 0 ? $v[0] : null;
    }

    /**
     * Retrieve multiple objects by pkey.
     *
     * @param      array $pks List of primary keys
     * @param      PropelPDO $con the connection to use
     * @return PUserArchive[]
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function retrieveByPKs($pks, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(PUserArchivePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $objs = null;
        if (empty($pks)) {
            $objs = array();
        } else {
            $criteria = new Criteria(PUserArchivePeer::DATABASE_NAME);
            $criteria->add(PUserArchivePeer::ID, $pks, Criteria::IN);
            $objs = PUserArchivePeer::doSelect($criteria, $con);
        }

        return $objs;
    }

} // BasePUserArchivePeer

// This is the static code needed to register the TableMap for this table with the main Propel class.
//
BasePUserArchivePeer::buildTableMap();

EventDispatcherProxy::trigger(array('construct','peer.construct'), new PeerEvent('Politizr\Model\om\BasePUserArchivePeer'));
