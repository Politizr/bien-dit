<?php

namespace Politizr\Model\om;

use \BasePeer;
use \Criteria;
use \PDO;
use \PDOStatement;
use \Propel;
use \PropelException;
use \PropelPDO;
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
    const NUM_COLUMNS = 56;

    /** The number of lazy-loaded columns. */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /** The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS) */
    const NUM_HYDRATE_COLUMNS = 56;

    /** the column name for the id field */
    const ID = 'p_user_archive.id';

    /** the column name for the uuid field */
    const UUID = 'p_user_archive.uuid';

    /** the column name for the p_u_status_id field */
    const P_U_STATUS_ID = 'p_user_archive.p_u_status_id';

    /** the column name for the p_l_city_id field */
    const P_L_CITY_ID = 'p_user_archive.p_l_city_id';

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

    /** the column name for the last_activity field */
    const LAST_ACTIVITY = 'p_user_archive.last_activity';

    /** the column name for the file_name field */
    const FILE_NAME = 'p_user_archive.file_name';

    /** the column name for the back_file_name field */
    const BACK_FILE_NAME = 'p_user_archive.back_file_name';

    /** the column name for the copyright field */
    const COPYRIGHT = 'p_user_archive.copyright';

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

    /** the column name for the indexed_at field */
    const INDEXED_AT = 'p_user_archive.indexed_at';

    /** the column name for the nb_views field */
    const NB_VIEWS = 'p_user_archive.nb_views';

    /** the column name for the qualified field */
    const QUALIFIED = 'p_user_archive.qualified';

    /** the column name for the validated field */
    const VALIDATED = 'p_user_archive.validated';

    /** the column name for the nb_id_check field */
    const NB_ID_CHECK = 'p_user_archive.nb_id_check';

    /** the column name for the online field */
    const ONLINE = 'p_user_archive.online';

    /** the column name for the homepage field */
    const HOMEPAGE = 'p_user_archive.homepage';

    /** the column name for the banned field */
    const BANNED = 'p_user_archive.banned';

    /** the column name for the banned_nb_days_left field */
    const BANNED_NB_DAYS_LEFT = 'p_user_archive.banned_nb_days_left';

    /** the column name for the banned_nb_total field */
    const BANNED_NB_TOTAL = 'p_user_archive.banned_nb_total';

    /** the column name for the abuse_level field */
    const ABUSE_LEVEL = 'p_user_archive.abuse_level';

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
        BasePeer::TYPE_PHPNAME => array ('Id', 'Uuid', 'PUStatusId', 'PLCityId', 'Provider', 'ProviderId', 'Nickname', 'Realname', 'Username', 'UsernameCanonical', 'Email', 'EmailCanonical', 'Enabled', 'Salt', 'Password', 'LastLogin', 'Locked', 'Expired', 'ExpiresAt', 'ConfirmationToken', 'PasswordRequestedAt', 'CredentialsExpired', 'CredentialsExpireAt', 'Roles', 'LastActivity', 'FileName', 'BackFileName', 'Copyright', 'Gender', 'Firstname', 'Name', 'Birthday', 'Subtitle', 'Biography', 'Website', 'Twitter', 'Facebook', 'Phone', 'Newsletter', 'LastConnect', 'NbConnectedDays', 'IndexedAt', 'NbViews', 'Qualified', 'Validated', 'NbIdCheck', 'Online', 'Homepage', 'Banned', 'BannedNbDaysLeft', 'BannedNbTotal', 'AbuseLevel', 'CreatedAt', 'UpdatedAt', 'Slug', 'ArchivedAt', ),
        BasePeer::TYPE_STUDLYPHPNAME => array ('id', 'uuid', 'pUStatusId', 'pLCityId', 'provider', 'providerId', 'nickname', 'realname', 'username', 'usernameCanonical', 'email', 'emailCanonical', 'enabled', 'salt', 'password', 'lastLogin', 'locked', 'expired', 'expiresAt', 'confirmationToken', 'passwordRequestedAt', 'credentialsExpired', 'credentialsExpireAt', 'roles', 'lastActivity', 'fileName', 'backFileName', 'copyright', 'gender', 'firstname', 'name', 'birthday', 'subtitle', 'biography', 'website', 'twitter', 'facebook', 'phone', 'newsletter', 'lastConnect', 'nbConnectedDays', 'indexedAt', 'nbViews', 'qualified', 'validated', 'nbIdCheck', 'online', 'homepage', 'banned', 'bannedNbDaysLeft', 'bannedNbTotal', 'abuseLevel', 'createdAt', 'updatedAt', 'slug', 'archivedAt', ),
        BasePeer::TYPE_COLNAME => array (PUserArchivePeer::ID, PUserArchivePeer::UUID, PUserArchivePeer::P_U_STATUS_ID, PUserArchivePeer::P_L_CITY_ID, PUserArchivePeer::PROVIDER, PUserArchivePeer::PROVIDER_ID, PUserArchivePeer::NICKNAME, PUserArchivePeer::REALNAME, PUserArchivePeer::USERNAME, PUserArchivePeer::USERNAME_CANONICAL, PUserArchivePeer::EMAIL, PUserArchivePeer::EMAIL_CANONICAL, PUserArchivePeer::ENABLED, PUserArchivePeer::SALT, PUserArchivePeer::PASSWORD, PUserArchivePeer::LAST_LOGIN, PUserArchivePeer::LOCKED, PUserArchivePeer::EXPIRED, PUserArchivePeer::EXPIRES_AT, PUserArchivePeer::CONFIRMATION_TOKEN, PUserArchivePeer::PASSWORD_REQUESTED_AT, PUserArchivePeer::CREDENTIALS_EXPIRED, PUserArchivePeer::CREDENTIALS_EXPIRE_AT, PUserArchivePeer::ROLES, PUserArchivePeer::LAST_ACTIVITY, PUserArchivePeer::FILE_NAME, PUserArchivePeer::BACK_FILE_NAME, PUserArchivePeer::COPYRIGHT, PUserArchivePeer::GENDER, PUserArchivePeer::FIRSTNAME, PUserArchivePeer::NAME, PUserArchivePeer::BIRTHDAY, PUserArchivePeer::SUBTITLE, PUserArchivePeer::BIOGRAPHY, PUserArchivePeer::WEBSITE, PUserArchivePeer::TWITTER, PUserArchivePeer::FACEBOOK, PUserArchivePeer::PHONE, PUserArchivePeer::NEWSLETTER, PUserArchivePeer::LAST_CONNECT, PUserArchivePeer::NB_CONNECTED_DAYS, PUserArchivePeer::INDEXED_AT, PUserArchivePeer::NB_VIEWS, PUserArchivePeer::QUALIFIED, PUserArchivePeer::VALIDATED, PUserArchivePeer::NB_ID_CHECK, PUserArchivePeer::ONLINE, PUserArchivePeer::HOMEPAGE, PUserArchivePeer::BANNED, PUserArchivePeer::BANNED_NB_DAYS_LEFT, PUserArchivePeer::BANNED_NB_TOTAL, PUserArchivePeer::ABUSE_LEVEL, PUserArchivePeer::CREATED_AT, PUserArchivePeer::UPDATED_AT, PUserArchivePeer::SLUG, PUserArchivePeer::ARCHIVED_AT, ),
        BasePeer::TYPE_RAW_COLNAME => array ('ID', 'UUID', 'P_U_STATUS_ID', 'P_L_CITY_ID', 'PROVIDER', 'PROVIDER_ID', 'NICKNAME', 'REALNAME', 'USERNAME', 'USERNAME_CANONICAL', 'EMAIL', 'EMAIL_CANONICAL', 'ENABLED', 'SALT', 'PASSWORD', 'LAST_LOGIN', 'LOCKED', 'EXPIRED', 'EXPIRES_AT', 'CONFIRMATION_TOKEN', 'PASSWORD_REQUESTED_AT', 'CREDENTIALS_EXPIRED', 'CREDENTIALS_EXPIRE_AT', 'ROLES', 'LAST_ACTIVITY', 'FILE_NAME', 'BACK_FILE_NAME', 'COPYRIGHT', 'GENDER', 'FIRSTNAME', 'NAME', 'BIRTHDAY', 'SUBTITLE', 'BIOGRAPHY', 'WEBSITE', 'TWITTER', 'FACEBOOK', 'PHONE', 'NEWSLETTER', 'LAST_CONNECT', 'NB_CONNECTED_DAYS', 'INDEXED_AT', 'NB_VIEWS', 'QUALIFIED', 'VALIDATED', 'NB_ID_CHECK', 'ONLINE', 'HOMEPAGE', 'BANNED', 'BANNED_NB_DAYS_LEFT', 'BANNED_NB_TOTAL', 'ABUSE_LEVEL', 'CREATED_AT', 'UPDATED_AT', 'SLUG', 'ARCHIVED_AT', ),
        BasePeer::TYPE_FIELDNAME => array ('id', 'uuid', 'p_u_status_id', 'p_l_city_id', 'provider', 'provider_id', 'nickname', 'realname', 'username', 'username_canonical', 'email', 'email_canonical', 'enabled', 'salt', 'password', 'last_login', 'locked', 'expired', 'expires_at', 'confirmation_token', 'password_requested_at', 'credentials_expired', 'credentials_expire_at', 'roles', 'last_activity', 'file_name', 'back_file_name', 'copyright', 'gender', 'firstname', 'name', 'birthday', 'subtitle', 'biography', 'website', 'twitter', 'facebook', 'phone', 'newsletter', 'last_connect', 'nb_connected_days', 'indexed_at', 'nb_views', 'qualified', 'validated', 'nb_id_check', 'online', 'homepage', 'banned', 'banned_nb_days_left', 'banned_nb_total', 'abuse_level', 'created_at', 'updated_at', 'slug', 'archived_at', ),
        BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38, 39, 40, 41, 42, 43, 44, 45, 46, 47, 48, 49, 50, 51, 52, 53, 54, 55, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. PUserArchivePeer::$fieldNames[BasePeer::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        BasePeer::TYPE_PHPNAME => array ('Id' => 0, 'Uuid' => 1, 'PUStatusId' => 2, 'PLCityId' => 3, 'Provider' => 4, 'ProviderId' => 5, 'Nickname' => 6, 'Realname' => 7, 'Username' => 8, 'UsernameCanonical' => 9, 'Email' => 10, 'EmailCanonical' => 11, 'Enabled' => 12, 'Salt' => 13, 'Password' => 14, 'LastLogin' => 15, 'Locked' => 16, 'Expired' => 17, 'ExpiresAt' => 18, 'ConfirmationToken' => 19, 'PasswordRequestedAt' => 20, 'CredentialsExpired' => 21, 'CredentialsExpireAt' => 22, 'Roles' => 23, 'LastActivity' => 24, 'FileName' => 25, 'BackFileName' => 26, 'Copyright' => 27, 'Gender' => 28, 'Firstname' => 29, 'Name' => 30, 'Birthday' => 31, 'Subtitle' => 32, 'Biography' => 33, 'Website' => 34, 'Twitter' => 35, 'Facebook' => 36, 'Phone' => 37, 'Newsletter' => 38, 'LastConnect' => 39, 'NbConnectedDays' => 40, 'IndexedAt' => 41, 'NbViews' => 42, 'Qualified' => 43, 'Validated' => 44, 'NbIdCheck' => 45, 'Online' => 46, 'Homepage' => 47, 'Banned' => 48, 'BannedNbDaysLeft' => 49, 'BannedNbTotal' => 50, 'AbuseLevel' => 51, 'CreatedAt' => 52, 'UpdatedAt' => 53, 'Slug' => 54, 'ArchivedAt' => 55, ),
        BasePeer::TYPE_STUDLYPHPNAME => array ('id' => 0, 'uuid' => 1, 'pUStatusId' => 2, 'pLCityId' => 3, 'provider' => 4, 'providerId' => 5, 'nickname' => 6, 'realname' => 7, 'username' => 8, 'usernameCanonical' => 9, 'email' => 10, 'emailCanonical' => 11, 'enabled' => 12, 'salt' => 13, 'password' => 14, 'lastLogin' => 15, 'locked' => 16, 'expired' => 17, 'expiresAt' => 18, 'confirmationToken' => 19, 'passwordRequestedAt' => 20, 'credentialsExpired' => 21, 'credentialsExpireAt' => 22, 'roles' => 23, 'lastActivity' => 24, 'fileName' => 25, 'backFileName' => 26, 'copyright' => 27, 'gender' => 28, 'firstname' => 29, 'name' => 30, 'birthday' => 31, 'subtitle' => 32, 'biography' => 33, 'website' => 34, 'twitter' => 35, 'facebook' => 36, 'phone' => 37, 'newsletter' => 38, 'lastConnect' => 39, 'nbConnectedDays' => 40, 'indexedAt' => 41, 'nbViews' => 42, 'qualified' => 43, 'validated' => 44, 'nbIdCheck' => 45, 'online' => 46, 'homepage' => 47, 'banned' => 48, 'bannedNbDaysLeft' => 49, 'bannedNbTotal' => 50, 'abuseLevel' => 51, 'createdAt' => 52, 'updatedAt' => 53, 'slug' => 54, 'archivedAt' => 55, ),
        BasePeer::TYPE_COLNAME => array (PUserArchivePeer::ID => 0, PUserArchivePeer::UUID => 1, PUserArchivePeer::P_U_STATUS_ID => 2, PUserArchivePeer::P_L_CITY_ID => 3, PUserArchivePeer::PROVIDER => 4, PUserArchivePeer::PROVIDER_ID => 5, PUserArchivePeer::NICKNAME => 6, PUserArchivePeer::REALNAME => 7, PUserArchivePeer::USERNAME => 8, PUserArchivePeer::USERNAME_CANONICAL => 9, PUserArchivePeer::EMAIL => 10, PUserArchivePeer::EMAIL_CANONICAL => 11, PUserArchivePeer::ENABLED => 12, PUserArchivePeer::SALT => 13, PUserArchivePeer::PASSWORD => 14, PUserArchivePeer::LAST_LOGIN => 15, PUserArchivePeer::LOCKED => 16, PUserArchivePeer::EXPIRED => 17, PUserArchivePeer::EXPIRES_AT => 18, PUserArchivePeer::CONFIRMATION_TOKEN => 19, PUserArchivePeer::PASSWORD_REQUESTED_AT => 20, PUserArchivePeer::CREDENTIALS_EXPIRED => 21, PUserArchivePeer::CREDENTIALS_EXPIRE_AT => 22, PUserArchivePeer::ROLES => 23, PUserArchivePeer::LAST_ACTIVITY => 24, PUserArchivePeer::FILE_NAME => 25, PUserArchivePeer::BACK_FILE_NAME => 26, PUserArchivePeer::COPYRIGHT => 27, PUserArchivePeer::GENDER => 28, PUserArchivePeer::FIRSTNAME => 29, PUserArchivePeer::NAME => 30, PUserArchivePeer::BIRTHDAY => 31, PUserArchivePeer::SUBTITLE => 32, PUserArchivePeer::BIOGRAPHY => 33, PUserArchivePeer::WEBSITE => 34, PUserArchivePeer::TWITTER => 35, PUserArchivePeer::FACEBOOK => 36, PUserArchivePeer::PHONE => 37, PUserArchivePeer::NEWSLETTER => 38, PUserArchivePeer::LAST_CONNECT => 39, PUserArchivePeer::NB_CONNECTED_DAYS => 40, PUserArchivePeer::INDEXED_AT => 41, PUserArchivePeer::NB_VIEWS => 42, PUserArchivePeer::QUALIFIED => 43, PUserArchivePeer::VALIDATED => 44, PUserArchivePeer::NB_ID_CHECK => 45, PUserArchivePeer::ONLINE => 46, PUserArchivePeer::HOMEPAGE => 47, PUserArchivePeer::BANNED => 48, PUserArchivePeer::BANNED_NB_DAYS_LEFT => 49, PUserArchivePeer::BANNED_NB_TOTAL => 50, PUserArchivePeer::ABUSE_LEVEL => 51, PUserArchivePeer::CREATED_AT => 52, PUserArchivePeer::UPDATED_AT => 53, PUserArchivePeer::SLUG => 54, PUserArchivePeer::ARCHIVED_AT => 55, ),
        BasePeer::TYPE_RAW_COLNAME => array ('ID' => 0, 'UUID' => 1, 'P_U_STATUS_ID' => 2, 'P_L_CITY_ID' => 3, 'PROVIDER' => 4, 'PROVIDER_ID' => 5, 'NICKNAME' => 6, 'REALNAME' => 7, 'USERNAME' => 8, 'USERNAME_CANONICAL' => 9, 'EMAIL' => 10, 'EMAIL_CANONICAL' => 11, 'ENABLED' => 12, 'SALT' => 13, 'PASSWORD' => 14, 'LAST_LOGIN' => 15, 'LOCKED' => 16, 'EXPIRED' => 17, 'EXPIRES_AT' => 18, 'CONFIRMATION_TOKEN' => 19, 'PASSWORD_REQUESTED_AT' => 20, 'CREDENTIALS_EXPIRED' => 21, 'CREDENTIALS_EXPIRE_AT' => 22, 'ROLES' => 23, 'LAST_ACTIVITY' => 24, 'FILE_NAME' => 25, 'BACK_FILE_NAME' => 26, 'COPYRIGHT' => 27, 'GENDER' => 28, 'FIRSTNAME' => 29, 'NAME' => 30, 'BIRTHDAY' => 31, 'SUBTITLE' => 32, 'BIOGRAPHY' => 33, 'WEBSITE' => 34, 'TWITTER' => 35, 'FACEBOOK' => 36, 'PHONE' => 37, 'NEWSLETTER' => 38, 'LAST_CONNECT' => 39, 'NB_CONNECTED_DAYS' => 40, 'INDEXED_AT' => 41, 'NB_VIEWS' => 42, 'QUALIFIED' => 43, 'VALIDATED' => 44, 'NB_ID_CHECK' => 45, 'ONLINE' => 46, 'HOMEPAGE' => 47, 'BANNED' => 48, 'BANNED_NB_DAYS_LEFT' => 49, 'BANNED_NB_TOTAL' => 50, 'ABUSE_LEVEL' => 51, 'CREATED_AT' => 52, 'UPDATED_AT' => 53, 'SLUG' => 54, 'ARCHIVED_AT' => 55, ),
        BasePeer::TYPE_FIELDNAME => array ('id' => 0, 'uuid' => 1, 'p_u_status_id' => 2, 'p_l_city_id' => 3, 'provider' => 4, 'provider_id' => 5, 'nickname' => 6, 'realname' => 7, 'username' => 8, 'username_canonical' => 9, 'email' => 10, 'email_canonical' => 11, 'enabled' => 12, 'salt' => 13, 'password' => 14, 'last_login' => 15, 'locked' => 16, 'expired' => 17, 'expires_at' => 18, 'confirmation_token' => 19, 'password_requested_at' => 20, 'credentials_expired' => 21, 'credentials_expire_at' => 22, 'roles' => 23, 'last_activity' => 24, 'file_name' => 25, 'back_file_name' => 26, 'copyright' => 27, 'gender' => 28, 'firstname' => 29, 'name' => 30, 'birthday' => 31, 'subtitle' => 32, 'biography' => 33, 'website' => 34, 'twitter' => 35, 'facebook' => 36, 'phone' => 37, 'newsletter' => 38, 'last_connect' => 39, 'nb_connected_days' => 40, 'indexed_at' => 41, 'nb_views' => 42, 'qualified' => 43, 'validated' => 44, 'nb_id_check' => 45, 'online' => 46, 'homepage' => 47, 'banned' => 48, 'banned_nb_days_left' => 49, 'banned_nb_total' => 50, 'abuse_level' => 51, 'created_at' => 52, 'updated_at' => 53, 'slug' => 54, 'archived_at' => 55, ),
        BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38, 39, 40, 41, 42, 43, 44, 45, 46, 47, 48, 49, 50, 51, 52, 53, 54, 55, )
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
            $criteria->addSelectColumn(PUserArchivePeer::UUID);
            $criteria->addSelectColumn(PUserArchivePeer::P_U_STATUS_ID);
            $criteria->addSelectColumn(PUserArchivePeer::P_L_CITY_ID);
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
            $criteria->addSelectColumn(PUserArchivePeer::LAST_ACTIVITY);
            $criteria->addSelectColumn(PUserArchivePeer::FILE_NAME);
            $criteria->addSelectColumn(PUserArchivePeer::BACK_FILE_NAME);
            $criteria->addSelectColumn(PUserArchivePeer::COPYRIGHT);
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
            $criteria->addSelectColumn(PUserArchivePeer::INDEXED_AT);
            $criteria->addSelectColumn(PUserArchivePeer::NB_VIEWS);
            $criteria->addSelectColumn(PUserArchivePeer::QUALIFIED);
            $criteria->addSelectColumn(PUserArchivePeer::VALIDATED);
            $criteria->addSelectColumn(PUserArchivePeer::NB_ID_CHECK);
            $criteria->addSelectColumn(PUserArchivePeer::ONLINE);
            $criteria->addSelectColumn(PUserArchivePeer::HOMEPAGE);
            $criteria->addSelectColumn(PUserArchivePeer::BANNED);
            $criteria->addSelectColumn(PUserArchivePeer::BANNED_NB_DAYS_LEFT);
            $criteria->addSelectColumn(PUserArchivePeer::BANNED_NB_TOTAL);
            $criteria->addSelectColumn(PUserArchivePeer::ABUSE_LEVEL);
            $criteria->addSelectColumn(PUserArchivePeer::CREATED_AT);
            $criteria->addSelectColumn(PUserArchivePeer::UPDATED_AT);
            $criteria->addSelectColumn(PUserArchivePeer::SLUG);
            $criteria->addSelectColumn(PUserArchivePeer::ARCHIVED_AT);
        } else {
            $criteria->addSelectColumn($alias . '.id');
            $criteria->addSelectColumn($alias . '.uuid');
            $criteria->addSelectColumn($alias . '.p_u_status_id');
            $criteria->addSelectColumn($alias . '.p_l_city_id');
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
            $criteria->addSelectColumn($alias . '.last_activity');
            $criteria->addSelectColumn($alias . '.file_name');
            $criteria->addSelectColumn($alias . '.back_file_name');
            $criteria->addSelectColumn($alias . '.copyright');
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
            $criteria->addSelectColumn($alias . '.indexed_at');
            $criteria->addSelectColumn($alias . '.nb_views');
            $criteria->addSelectColumn($alias . '.qualified');
            $criteria->addSelectColumn($alias . '.validated');
            $criteria->addSelectColumn($alias . '.nb_id_check');
            $criteria->addSelectColumn($alias . '.online');
            $criteria->addSelectColumn($alias . '.homepage');
            $criteria->addSelectColumn($alias . '.banned');
            $criteria->addSelectColumn($alias . '.banned_nb_days_left');
            $criteria->addSelectColumn($alias . '.banned_nb_total');
            $criteria->addSelectColumn($alias . '.abuse_level');
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
            $cls = PUserArchivePeer::OM_CLASS;
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

