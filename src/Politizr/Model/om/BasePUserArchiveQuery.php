<?php

namespace Politizr\Model\om;

use \Criteria;
use \Exception;
use \ModelCriteria;
use \PDO;
use \Propel;
use \PropelException;
use \PropelObjectCollection;
use \PropelPDO;
use Politizr\Model\PUserArchive;
use Politizr\Model\PUserArchivePeer;
use Politizr\Model\PUserArchiveQuery;

/**
 * @method PUserArchiveQuery orderById($order = Criteria::ASC) Order by the id column
 * @method PUserArchiveQuery orderByUuid($order = Criteria::ASC) Order by the uuid column
 * @method PUserArchiveQuery orderByPUStatusId($order = Criteria::ASC) Order by the p_u_status_id column
 * @method PUserArchiveQuery orderByPLCityId($order = Criteria::ASC) Order by the p_l_city_id column
 * @method PUserArchiveQuery orderByProvider($order = Criteria::ASC) Order by the provider column
 * @method PUserArchiveQuery orderByProviderId($order = Criteria::ASC) Order by the provider_id column
 * @method PUserArchiveQuery orderByNickname($order = Criteria::ASC) Order by the nickname column
 * @method PUserArchiveQuery orderByRealname($order = Criteria::ASC) Order by the realname column
 * @method PUserArchiveQuery orderByUsername($order = Criteria::ASC) Order by the username column
 * @method PUserArchiveQuery orderByUsernameCanonical($order = Criteria::ASC) Order by the username_canonical column
 * @method PUserArchiveQuery orderByEmail($order = Criteria::ASC) Order by the email column
 * @method PUserArchiveQuery orderByEmailCanonical($order = Criteria::ASC) Order by the email_canonical column
 * @method PUserArchiveQuery orderByEnabled($order = Criteria::ASC) Order by the enabled column
 * @method PUserArchiveQuery orderBySalt($order = Criteria::ASC) Order by the salt column
 * @method PUserArchiveQuery orderByPassword($order = Criteria::ASC) Order by the password column
 * @method PUserArchiveQuery orderByLastLogin($order = Criteria::ASC) Order by the last_login column
 * @method PUserArchiveQuery orderByLocked($order = Criteria::ASC) Order by the locked column
 * @method PUserArchiveQuery orderByExpired($order = Criteria::ASC) Order by the expired column
 * @method PUserArchiveQuery orderByExpiresAt($order = Criteria::ASC) Order by the expires_at column
 * @method PUserArchiveQuery orderByConfirmationToken($order = Criteria::ASC) Order by the confirmation_token column
 * @method PUserArchiveQuery orderByPasswordRequestedAt($order = Criteria::ASC) Order by the password_requested_at column
 * @method PUserArchiveQuery orderByCredentialsExpired($order = Criteria::ASC) Order by the credentials_expired column
 * @method PUserArchiveQuery orderByCredentialsExpireAt($order = Criteria::ASC) Order by the credentials_expire_at column
 * @method PUserArchiveQuery orderByRoles($order = Criteria::ASC) Order by the roles column
 * @method PUserArchiveQuery orderByLastActivity($order = Criteria::ASC) Order by the last_activity column
 * @method PUserArchiveQuery orderByFileName($order = Criteria::ASC) Order by the file_name column
 * @method PUserArchiveQuery orderByBackFileName($order = Criteria::ASC) Order by the back_file_name column
 * @method PUserArchiveQuery orderByCopyright($order = Criteria::ASC) Order by the copyright column
 * @method PUserArchiveQuery orderByGender($order = Criteria::ASC) Order by the gender column
 * @method PUserArchiveQuery orderByFirstname($order = Criteria::ASC) Order by the firstname column
 * @method PUserArchiveQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method PUserArchiveQuery orderByBirthday($order = Criteria::ASC) Order by the birthday column
 * @method PUserArchiveQuery orderBySubtitle($order = Criteria::ASC) Order by the subtitle column
 * @method PUserArchiveQuery orderByBiography($order = Criteria::ASC) Order by the biography column
 * @method PUserArchiveQuery orderByWebsite($order = Criteria::ASC) Order by the website column
 * @method PUserArchiveQuery orderByTwitter($order = Criteria::ASC) Order by the twitter column
 * @method PUserArchiveQuery orderByFacebook($order = Criteria::ASC) Order by the facebook column
 * @method PUserArchiveQuery orderByPhone($order = Criteria::ASC) Order by the phone column
 * @method PUserArchiveQuery orderByNewsletter($order = Criteria::ASC) Order by the newsletter column
 * @method PUserArchiveQuery orderByLastConnect($order = Criteria::ASC) Order by the last_connect column
 * @method PUserArchiveQuery orderByNbConnectedDays($order = Criteria::ASC) Order by the nb_connected_days column
 * @method PUserArchiveQuery orderByIndexedAt($order = Criteria::ASC) Order by the indexed_at column
 * @method PUserArchiveQuery orderByNbViews($order = Criteria::ASC) Order by the nb_views column
 * @method PUserArchiveQuery orderByQualified($order = Criteria::ASC) Order by the qualified column
 * @method PUserArchiveQuery orderByValidated($order = Criteria::ASC) Order by the validated column
 * @method PUserArchiveQuery orderByNbIdCheck($order = Criteria::ASC) Order by the nb_id_check column
 * @method PUserArchiveQuery orderByOrganization($order = Criteria::ASC) Order by the organization column
 * @method PUserArchiveQuery orderByOnline($order = Criteria::ASC) Order by the online column
 * @method PUserArchiveQuery orderByHomepage($order = Criteria::ASC) Order by the homepage column
 * @method PUserArchiveQuery orderBySupportGroup($order = Criteria::ASC) Order by the support_group column
 * @method PUserArchiveQuery orderByBanned($order = Criteria::ASC) Order by the banned column
 * @method PUserArchiveQuery orderByBannedNbDaysLeft($order = Criteria::ASC) Order by the banned_nb_days_left column
 * @method PUserArchiveQuery orderByBannedNbTotal($order = Criteria::ASC) Order by the banned_nb_total column
 * @method PUserArchiveQuery orderByAbuseLevel($order = Criteria::ASC) Order by the abuse_level column
 * @method PUserArchiveQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method PUserArchiveQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 * @method PUserArchiveQuery orderBySlug($order = Criteria::ASC) Order by the slug column
 * @method PUserArchiveQuery orderByArchivedAt($order = Criteria::ASC) Order by the archived_at column
 *
 * @method PUserArchiveQuery groupById() Group by the id column
 * @method PUserArchiveQuery groupByUuid() Group by the uuid column
 * @method PUserArchiveQuery groupByPUStatusId() Group by the p_u_status_id column
 * @method PUserArchiveQuery groupByPLCityId() Group by the p_l_city_id column
 * @method PUserArchiveQuery groupByProvider() Group by the provider column
 * @method PUserArchiveQuery groupByProviderId() Group by the provider_id column
 * @method PUserArchiveQuery groupByNickname() Group by the nickname column
 * @method PUserArchiveQuery groupByRealname() Group by the realname column
 * @method PUserArchiveQuery groupByUsername() Group by the username column
 * @method PUserArchiveQuery groupByUsernameCanonical() Group by the username_canonical column
 * @method PUserArchiveQuery groupByEmail() Group by the email column
 * @method PUserArchiveQuery groupByEmailCanonical() Group by the email_canonical column
 * @method PUserArchiveQuery groupByEnabled() Group by the enabled column
 * @method PUserArchiveQuery groupBySalt() Group by the salt column
 * @method PUserArchiveQuery groupByPassword() Group by the password column
 * @method PUserArchiveQuery groupByLastLogin() Group by the last_login column
 * @method PUserArchiveQuery groupByLocked() Group by the locked column
 * @method PUserArchiveQuery groupByExpired() Group by the expired column
 * @method PUserArchiveQuery groupByExpiresAt() Group by the expires_at column
 * @method PUserArchiveQuery groupByConfirmationToken() Group by the confirmation_token column
 * @method PUserArchiveQuery groupByPasswordRequestedAt() Group by the password_requested_at column
 * @method PUserArchiveQuery groupByCredentialsExpired() Group by the credentials_expired column
 * @method PUserArchiveQuery groupByCredentialsExpireAt() Group by the credentials_expire_at column
 * @method PUserArchiveQuery groupByRoles() Group by the roles column
 * @method PUserArchiveQuery groupByLastActivity() Group by the last_activity column
 * @method PUserArchiveQuery groupByFileName() Group by the file_name column
 * @method PUserArchiveQuery groupByBackFileName() Group by the back_file_name column
 * @method PUserArchiveQuery groupByCopyright() Group by the copyright column
 * @method PUserArchiveQuery groupByGender() Group by the gender column
 * @method PUserArchiveQuery groupByFirstname() Group by the firstname column
 * @method PUserArchiveQuery groupByName() Group by the name column
 * @method PUserArchiveQuery groupByBirthday() Group by the birthday column
 * @method PUserArchiveQuery groupBySubtitle() Group by the subtitle column
 * @method PUserArchiveQuery groupByBiography() Group by the biography column
 * @method PUserArchiveQuery groupByWebsite() Group by the website column
 * @method PUserArchiveQuery groupByTwitter() Group by the twitter column
 * @method PUserArchiveQuery groupByFacebook() Group by the facebook column
 * @method PUserArchiveQuery groupByPhone() Group by the phone column
 * @method PUserArchiveQuery groupByNewsletter() Group by the newsletter column
 * @method PUserArchiveQuery groupByLastConnect() Group by the last_connect column
 * @method PUserArchiveQuery groupByNbConnectedDays() Group by the nb_connected_days column
 * @method PUserArchiveQuery groupByIndexedAt() Group by the indexed_at column
 * @method PUserArchiveQuery groupByNbViews() Group by the nb_views column
 * @method PUserArchiveQuery groupByQualified() Group by the qualified column
 * @method PUserArchiveQuery groupByValidated() Group by the validated column
 * @method PUserArchiveQuery groupByNbIdCheck() Group by the nb_id_check column
 * @method PUserArchiveQuery groupByOrganization() Group by the organization column
 * @method PUserArchiveQuery groupByOnline() Group by the online column
 * @method PUserArchiveQuery groupByHomepage() Group by the homepage column
 * @method PUserArchiveQuery groupBySupportGroup() Group by the support_group column
 * @method PUserArchiveQuery groupByBanned() Group by the banned column
 * @method PUserArchiveQuery groupByBannedNbDaysLeft() Group by the banned_nb_days_left column
 * @method PUserArchiveQuery groupByBannedNbTotal() Group by the banned_nb_total column
 * @method PUserArchiveQuery groupByAbuseLevel() Group by the abuse_level column
 * @method PUserArchiveQuery groupByCreatedAt() Group by the created_at column
 * @method PUserArchiveQuery groupByUpdatedAt() Group by the updated_at column
 * @method PUserArchiveQuery groupBySlug() Group by the slug column
 * @method PUserArchiveQuery groupByArchivedAt() Group by the archived_at column
 *
 * @method PUserArchiveQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method PUserArchiveQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method PUserArchiveQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method PUserArchive findOne(PropelPDO $con = null) Return the first PUserArchive matching the query
 * @method PUserArchive findOneOrCreate(PropelPDO $con = null) Return the first PUserArchive matching the query, or a new PUserArchive object populated from the query conditions when no match is found
 *
 * @method PUserArchive findOneByUuid(string $uuid) Return the first PUserArchive filtered by the uuid column
 * @method PUserArchive findOneByPUStatusId(int $p_u_status_id) Return the first PUserArchive filtered by the p_u_status_id column
 * @method PUserArchive findOneByPLCityId(int $p_l_city_id) Return the first PUserArchive filtered by the p_l_city_id column
 * @method PUserArchive findOneByProvider(string $provider) Return the first PUserArchive filtered by the provider column
 * @method PUserArchive findOneByProviderId(string $provider_id) Return the first PUserArchive filtered by the provider_id column
 * @method PUserArchive findOneByNickname(string $nickname) Return the first PUserArchive filtered by the nickname column
 * @method PUserArchive findOneByRealname(string $realname) Return the first PUserArchive filtered by the realname column
 * @method PUserArchive findOneByUsername(string $username) Return the first PUserArchive filtered by the username column
 * @method PUserArchive findOneByUsernameCanonical(string $username_canonical) Return the first PUserArchive filtered by the username_canonical column
 * @method PUserArchive findOneByEmail(string $email) Return the first PUserArchive filtered by the email column
 * @method PUserArchive findOneByEmailCanonical(string $email_canonical) Return the first PUserArchive filtered by the email_canonical column
 * @method PUserArchive findOneByEnabled(boolean $enabled) Return the first PUserArchive filtered by the enabled column
 * @method PUserArchive findOneBySalt(string $salt) Return the first PUserArchive filtered by the salt column
 * @method PUserArchive findOneByPassword(string $password) Return the first PUserArchive filtered by the password column
 * @method PUserArchive findOneByLastLogin(string $last_login) Return the first PUserArchive filtered by the last_login column
 * @method PUserArchive findOneByLocked(boolean $locked) Return the first PUserArchive filtered by the locked column
 * @method PUserArchive findOneByExpired(boolean $expired) Return the first PUserArchive filtered by the expired column
 * @method PUserArchive findOneByExpiresAt(string $expires_at) Return the first PUserArchive filtered by the expires_at column
 * @method PUserArchive findOneByConfirmationToken(string $confirmation_token) Return the first PUserArchive filtered by the confirmation_token column
 * @method PUserArchive findOneByPasswordRequestedAt(string $password_requested_at) Return the first PUserArchive filtered by the password_requested_at column
 * @method PUserArchive findOneByCredentialsExpired(boolean $credentials_expired) Return the first PUserArchive filtered by the credentials_expired column
 * @method PUserArchive findOneByCredentialsExpireAt(string $credentials_expire_at) Return the first PUserArchive filtered by the credentials_expire_at column
 * @method PUserArchive findOneByRoles(array $roles) Return the first PUserArchive filtered by the roles column
 * @method PUserArchive findOneByLastActivity(string $last_activity) Return the first PUserArchive filtered by the last_activity column
 * @method PUserArchive findOneByFileName(string $file_name) Return the first PUserArchive filtered by the file_name column
 * @method PUserArchive findOneByBackFileName(string $back_file_name) Return the first PUserArchive filtered by the back_file_name column
 * @method PUserArchive findOneByCopyright(string $copyright) Return the first PUserArchive filtered by the copyright column
 * @method PUserArchive findOneByGender(int $gender) Return the first PUserArchive filtered by the gender column
 * @method PUserArchive findOneByFirstname(string $firstname) Return the first PUserArchive filtered by the firstname column
 * @method PUserArchive findOneByName(string $name) Return the first PUserArchive filtered by the name column
 * @method PUserArchive findOneByBirthday(string $birthday) Return the first PUserArchive filtered by the birthday column
 * @method PUserArchive findOneBySubtitle(string $subtitle) Return the first PUserArchive filtered by the subtitle column
 * @method PUserArchive findOneByBiography(string $biography) Return the first PUserArchive filtered by the biography column
 * @method PUserArchive findOneByWebsite(string $website) Return the first PUserArchive filtered by the website column
 * @method PUserArchive findOneByTwitter(string $twitter) Return the first PUserArchive filtered by the twitter column
 * @method PUserArchive findOneByFacebook(string $facebook) Return the first PUserArchive filtered by the facebook column
 * @method PUserArchive findOneByPhone(string $phone) Return the first PUserArchive filtered by the phone column
 * @method PUserArchive findOneByNewsletter(boolean $newsletter) Return the first PUserArchive filtered by the newsletter column
 * @method PUserArchive findOneByLastConnect(string $last_connect) Return the first PUserArchive filtered by the last_connect column
 * @method PUserArchive findOneByNbConnectedDays(int $nb_connected_days) Return the first PUserArchive filtered by the nb_connected_days column
 * @method PUserArchive findOneByIndexedAt(string $indexed_at) Return the first PUserArchive filtered by the indexed_at column
 * @method PUserArchive findOneByNbViews(int $nb_views) Return the first PUserArchive filtered by the nb_views column
 * @method PUserArchive findOneByQualified(boolean $qualified) Return the first PUserArchive filtered by the qualified column
 * @method PUserArchive findOneByValidated(boolean $validated) Return the first PUserArchive filtered by the validated column
 * @method PUserArchive findOneByNbIdCheck(int $nb_id_check) Return the first PUserArchive filtered by the nb_id_check column
 * @method PUserArchive findOneByOrganization(boolean $organization) Return the first PUserArchive filtered by the organization column
 * @method PUserArchive findOneByOnline(boolean $online) Return the first PUserArchive filtered by the online column
 * @method PUserArchive findOneByHomepage(boolean $homepage) Return the first PUserArchive filtered by the homepage column
 * @method PUserArchive findOneBySupportGroup(boolean $support_group) Return the first PUserArchive filtered by the support_group column
 * @method PUserArchive findOneByBanned(boolean $banned) Return the first PUserArchive filtered by the banned column
 * @method PUserArchive findOneByBannedNbDaysLeft(int $banned_nb_days_left) Return the first PUserArchive filtered by the banned_nb_days_left column
 * @method PUserArchive findOneByBannedNbTotal(int $banned_nb_total) Return the first PUserArchive filtered by the banned_nb_total column
 * @method PUserArchive findOneByAbuseLevel(int $abuse_level) Return the first PUserArchive filtered by the abuse_level column
 * @method PUserArchive findOneByCreatedAt(string $created_at) Return the first PUserArchive filtered by the created_at column
 * @method PUserArchive findOneByUpdatedAt(string $updated_at) Return the first PUserArchive filtered by the updated_at column
 * @method PUserArchive findOneBySlug(string $slug) Return the first PUserArchive filtered by the slug column
 * @method PUserArchive findOneByArchivedAt(string $archived_at) Return the first PUserArchive filtered by the archived_at column
 *
 * @method array findById(int $id) Return PUserArchive objects filtered by the id column
 * @method array findByUuid(string $uuid) Return PUserArchive objects filtered by the uuid column
 * @method array findByPUStatusId(int $p_u_status_id) Return PUserArchive objects filtered by the p_u_status_id column
 * @method array findByPLCityId(int $p_l_city_id) Return PUserArchive objects filtered by the p_l_city_id column
 * @method array findByProvider(string $provider) Return PUserArchive objects filtered by the provider column
 * @method array findByProviderId(string $provider_id) Return PUserArchive objects filtered by the provider_id column
 * @method array findByNickname(string $nickname) Return PUserArchive objects filtered by the nickname column
 * @method array findByRealname(string $realname) Return PUserArchive objects filtered by the realname column
 * @method array findByUsername(string $username) Return PUserArchive objects filtered by the username column
 * @method array findByUsernameCanonical(string $username_canonical) Return PUserArchive objects filtered by the username_canonical column
 * @method array findByEmail(string $email) Return PUserArchive objects filtered by the email column
 * @method array findByEmailCanonical(string $email_canonical) Return PUserArchive objects filtered by the email_canonical column
 * @method array findByEnabled(boolean $enabled) Return PUserArchive objects filtered by the enabled column
 * @method array findBySalt(string $salt) Return PUserArchive objects filtered by the salt column
 * @method array findByPassword(string $password) Return PUserArchive objects filtered by the password column
 * @method array findByLastLogin(string $last_login) Return PUserArchive objects filtered by the last_login column
 * @method array findByLocked(boolean $locked) Return PUserArchive objects filtered by the locked column
 * @method array findByExpired(boolean $expired) Return PUserArchive objects filtered by the expired column
 * @method array findByExpiresAt(string $expires_at) Return PUserArchive objects filtered by the expires_at column
 * @method array findByConfirmationToken(string $confirmation_token) Return PUserArchive objects filtered by the confirmation_token column
 * @method array findByPasswordRequestedAt(string $password_requested_at) Return PUserArchive objects filtered by the password_requested_at column
 * @method array findByCredentialsExpired(boolean $credentials_expired) Return PUserArchive objects filtered by the credentials_expired column
 * @method array findByCredentialsExpireAt(string $credentials_expire_at) Return PUserArchive objects filtered by the credentials_expire_at column
 * @method array findByRoles(array $roles) Return PUserArchive objects filtered by the roles column
 * @method array findByLastActivity(string $last_activity) Return PUserArchive objects filtered by the last_activity column
 * @method array findByFileName(string $file_name) Return PUserArchive objects filtered by the file_name column
 * @method array findByBackFileName(string $back_file_name) Return PUserArchive objects filtered by the back_file_name column
 * @method array findByCopyright(string $copyright) Return PUserArchive objects filtered by the copyright column
 * @method array findByGender(int $gender) Return PUserArchive objects filtered by the gender column
 * @method array findByFirstname(string $firstname) Return PUserArchive objects filtered by the firstname column
 * @method array findByName(string $name) Return PUserArchive objects filtered by the name column
 * @method array findByBirthday(string $birthday) Return PUserArchive objects filtered by the birthday column
 * @method array findBySubtitle(string $subtitle) Return PUserArchive objects filtered by the subtitle column
 * @method array findByBiography(string $biography) Return PUserArchive objects filtered by the biography column
 * @method array findByWebsite(string $website) Return PUserArchive objects filtered by the website column
 * @method array findByTwitter(string $twitter) Return PUserArchive objects filtered by the twitter column
 * @method array findByFacebook(string $facebook) Return PUserArchive objects filtered by the facebook column
 * @method array findByPhone(string $phone) Return PUserArchive objects filtered by the phone column
 * @method array findByNewsletter(boolean $newsletter) Return PUserArchive objects filtered by the newsletter column
 * @method array findByLastConnect(string $last_connect) Return PUserArchive objects filtered by the last_connect column
 * @method array findByNbConnectedDays(int $nb_connected_days) Return PUserArchive objects filtered by the nb_connected_days column
 * @method array findByIndexedAt(string $indexed_at) Return PUserArchive objects filtered by the indexed_at column
 * @method array findByNbViews(int $nb_views) Return PUserArchive objects filtered by the nb_views column
 * @method array findByQualified(boolean $qualified) Return PUserArchive objects filtered by the qualified column
 * @method array findByValidated(boolean $validated) Return PUserArchive objects filtered by the validated column
 * @method array findByNbIdCheck(int $nb_id_check) Return PUserArchive objects filtered by the nb_id_check column
 * @method array findByOrganization(boolean $organization) Return PUserArchive objects filtered by the organization column
 * @method array findByOnline(boolean $online) Return PUserArchive objects filtered by the online column
 * @method array findByHomepage(boolean $homepage) Return PUserArchive objects filtered by the homepage column
 * @method array findBySupportGroup(boolean $support_group) Return PUserArchive objects filtered by the support_group column
 * @method array findByBanned(boolean $banned) Return PUserArchive objects filtered by the banned column
 * @method array findByBannedNbDaysLeft(int $banned_nb_days_left) Return PUserArchive objects filtered by the banned_nb_days_left column
 * @method array findByBannedNbTotal(int $banned_nb_total) Return PUserArchive objects filtered by the banned_nb_total column
 * @method array findByAbuseLevel(int $abuse_level) Return PUserArchive objects filtered by the abuse_level column
 * @method array findByCreatedAt(string $created_at) Return PUserArchive objects filtered by the created_at column
 * @method array findByUpdatedAt(string $updated_at) Return PUserArchive objects filtered by the updated_at column
 * @method array findBySlug(string $slug) Return PUserArchive objects filtered by the slug column
 * @method array findByArchivedAt(string $archived_at) Return PUserArchive objects filtered by the archived_at column
 */
abstract class BasePUserArchiveQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BasePUserArchiveQuery object.
     *
     * @param     string $dbName The dabase name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = null, $modelName = null, $modelAlias = null)
    {
        if (null === $dbName) {
            $dbName = 'default';
        }
        if (null === $modelName) {
            $modelName = 'Politizr\\Model\\PUserArchive';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new PUserArchiveQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   PUserArchiveQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return PUserArchiveQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof PUserArchiveQuery) {
            return $criteria;
        }
        $query = new PUserArchiveQuery(null, null, $modelAlias);

        if ($criteria instanceof Criteria) {
            $query->mergeWith($criteria);
        }

        return $query;
    }

    /**
     * Find object by primary key.
     * Propel uses the instance pool to skip the database if the object exists.
     * Go fast if the query is untouched.
     *
     * <code>
     * $obj  = $c->findPk(12, $con);
     * </code>
     *
     * @param mixed $key Primary key to use for the query
     * @param     PropelPDO $con an optional connection object
     *
     * @return   PUserArchive|PUserArchive[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = PUserArchivePeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(PUserArchivePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }
        $this->basePreSelect($con);
        if ($this->formatter || $this->modelAlias || $this->with || $this->select
         || $this->selectColumns || $this->asColumns || $this->selectModifiers
         || $this->map || $this->having || $this->joins) {
            return $this->findPkComplex($key, $con);
        } else {
            return $this->findPkSimple($key, $con);
        }
    }

    /**
     * Alias of findPk to use instance pooling
     *
     * @param     mixed $key Primary key to use for the query
     * @param     PropelPDO $con A connection object
     *
     * @return                 PUserArchive A model object, or null if the key is not found
     * @throws PropelException
     */
     public function findOneById($key, $con = null)
     {
        return $this->findPk($key, $con);
     }

    /**
     * Find object by primary key using raw SQL to go fast.
     * Bypass doSelect() and the object formatter by using generated code.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     PropelPDO $con A connection object
     *
     * @return                 PUserArchive A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `uuid`, `p_u_status_id`, `p_l_city_id`, `provider`, `provider_id`, `nickname`, `realname`, `username`, `username_canonical`, `email`, `email_canonical`, `enabled`, `salt`, `password`, `last_login`, `locked`, `expired`, `expires_at`, `confirmation_token`, `password_requested_at`, `credentials_expired`, `credentials_expire_at`, `roles`, `last_activity`, `file_name`, `back_file_name`, `copyright`, `gender`, `firstname`, `name`, `birthday`, `subtitle`, `biography`, `website`, `twitter`, `facebook`, `phone`, `newsletter`, `last_connect`, `nb_connected_days`, `indexed_at`, `nb_views`, `qualified`, `validated`, `nb_id_check`, `organization`, `online`, `homepage`, `support_group`, `banned`, `banned_nb_days_left`, `banned_nb_total`, `abuse_level`, `created_at`, `updated_at`, `slug`, `archived_at` FROM `p_user_archive` WHERE `id` = :p0';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key, PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $obj = new PUserArchive();
            $obj->hydrate($row);
            PUserArchivePeer::addInstanceToPool($obj, (string) $key);
        }
        $stmt->closeCursor();

        return $obj;
    }

    /**
     * Find object by primary key.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     PropelPDO $con A connection object
     *
     * @return PUserArchive|PUserArchive[]|mixed the result, formatted by the current formatter
     */
    protected function findPkComplex($key, $con)
    {
        // As the query uses a PK condition, no limit(1) is necessary.
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $stmt = $criteria
            ->filterByPrimaryKey($key)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->formatOne($stmt);
    }

    /**
     * Find objects by primary key
     * <code>
     * $objs = $c->findPks(array(12, 56, 832), $con);
     * </code>
     * @param     array $keys Primary keys to use for the query
     * @param     PropelPDO $con an optional connection object
     *
     * @return PropelObjectCollection|PUserArchive[]|mixed the list of results, formatted by the current formatter
     */
    public function findPks($keys, $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection($this->getDbName(), Propel::CONNECTION_READ);
        }
        $this->basePreSelect($con);
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $stmt = $criteria
            ->filterByPrimaryKeys($keys)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->format($stmt);
    }

    /**
     * Filter the query by primary key
     *
     * @param     mixed $key Primary key to use for the query
     *
     * @return PUserArchiveQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(PUserArchivePeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return PUserArchiveQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(PUserArchivePeer::ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE id = 1234
     * $query->filterById(array(12, 34)); // WHERE id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE id >= 12
     * $query->filterById(array('max' => 12)); // WHERE id <= 12
     * </code>
     *
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PUserArchiveQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(PUserArchivePeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(PUserArchivePeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PUserArchivePeer::ID, $id, $comparison);
    }

    /**
     * Filter the query on the uuid column
     *
     * Example usage:
     * <code>
     * $query->filterByUuid('fooValue');   // WHERE uuid = 'fooValue'
     * $query->filterByUuid('%fooValue%'); // WHERE uuid LIKE '%fooValue%'
     * </code>
     *
     * @param     string $uuid The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PUserArchiveQuery The current query, for fluid interface
     */
    public function filterByUuid($uuid = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($uuid)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $uuid)) {
                $uuid = str_replace('*', '%', $uuid);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(PUserArchivePeer::UUID, $uuid, $comparison);
    }

    /**
     * Filter the query on the p_u_status_id column
     *
     * Example usage:
     * <code>
     * $query->filterByPUStatusId(1234); // WHERE p_u_status_id = 1234
     * $query->filterByPUStatusId(array(12, 34)); // WHERE p_u_status_id IN (12, 34)
     * $query->filterByPUStatusId(array('min' => 12)); // WHERE p_u_status_id >= 12
     * $query->filterByPUStatusId(array('max' => 12)); // WHERE p_u_status_id <= 12
     * </code>
     *
     * @param     mixed $pUStatusId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PUserArchiveQuery The current query, for fluid interface
     */
    public function filterByPUStatusId($pUStatusId = null, $comparison = null)
    {
        if (is_array($pUStatusId)) {
            $useMinMax = false;
            if (isset($pUStatusId['min'])) {
                $this->addUsingAlias(PUserArchivePeer::P_U_STATUS_ID, $pUStatusId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($pUStatusId['max'])) {
                $this->addUsingAlias(PUserArchivePeer::P_U_STATUS_ID, $pUStatusId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PUserArchivePeer::P_U_STATUS_ID, $pUStatusId, $comparison);
    }

    /**
     * Filter the query on the p_l_city_id column
     *
     * Example usage:
     * <code>
     * $query->filterByPLCityId(1234); // WHERE p_l_city_id = 1234
     * $query->filterByPLCityId(array(12, 34)); // WHERE p_l_city_id IN (12, 34)
     * $query->filterByPLCityId(array('min' => 12)); // WHERE p_l_city_id >= 12
     * $query->filterByPLCityId(array('max' => 12)); // WHERE p_l_city_id <= 12
     * </code>
     *
     * @param     mixed $pLCityId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PUserArchiveQuery The current query, for fluid interface
     */
    public function filterByPLCityId($pLCityId = null, $comparison = null)
    {
        if (is_array($pLCityId)) {
            $useMinMax = false;
            if (isset($pLCityId['min'])) {
                $this->addUsingAlias(PUserArchivePeer::P_L_CITY_ID, $pLCityId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($pLCityId['max'])) {
                $this->addUsingAlias(PUserArchivePeer::P_L_CITY_ID, $pLCityId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PUserArchivePeer::P_L_CITY_ID, $pLCityId, $comparison);
    }

    /**
     * Filter the query on the provider column
     *
     * Example usage:
     * <code>
     * $query->filterByProvider('fooValue');   // WHERE provider = 'fooValue'
     * $query->filterByProvider('%fooValue%'); // WHERE provider LIKE '%fooValue%'
     * </code>
     *
     * @param     string $provider The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PUserArchiveQuery The current query, for fluid interface
     */
    public function filterByProvider($provider = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($provider)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $provider)) {
                $provider = str_replace('*', '%', $provider);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(PUserArchivePeer::PROVIDER, $provider, $comparison);
    }

    /**
     * Filter the query on the provider_id column
     *
     * Example usage:
     * <code>
     * $query->filterByProviderId('fooValue');   // WHERE provider_id = 'fooValue'
     * $query->filterByProviderId('%fooValue%'); // WHERE provider_id LIKE '%fooValue%'
     * </code>
     *
     * @param     string $providerId The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PUserArchiveQuery The current query, for fluid interface
     */
    public function filterByProviderId($providerId = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($providerId)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $providerId)) {
                $providerId = str_replace('*', '%', $providerId);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(PUserArchivePeer::PROVIDER_ID, $providerId, $comparison);
    }

    /**
     * Filter the query on the nickname column
     *
     * Example usage:
     * <code>
     * $query->filterByNickname('fooValue');   // WHERE nickname = 'fooValue'
     * $query->filterByNickname('%fooValue%'); // WHERE nickname LIKE '%fooValue%'
     * </code>
     *
     * @param     string $nickname The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PUserArchiveQuery The current query, for fluid interface
     */
    public function filterByNickname($nickname = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($nickname)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $nickname)) {
                $nickname = str_replace('*', '%', $nickname);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(PUserArchivePeer::NICKNAME, $nickname, $comparison);
    }

    /**
     * Filter the query on the realname column
     *
     * Example usage:
     * <code>
     * $query->filterByRealname('fooValue');   // WHERE realname = 'fooValue'
     * $query->filterByRealname('%fooValue%'); // WHERE realname LIKE '%fooValue%'
     * </code>
     *
     * @param     string $realname The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PUserArchiveQuery The current query, for fluid interface
     */
    public function filterByRealname($realname = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($realname)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $realname)) {
                $realname = str_replace('*', '%', $realname);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(PUserArchivePeer::REALNAME, $realname, $comparison);
    }

    /**
     * Filter the query on the username column
     *
     * Example usage:
     * <code>
     * $query->filterByUsername('fooValue');   // WHERE username = 'fooValue'
     * $query->filterByUsername('%fooValue%'); // WHERE username LIKE '%fooValue%'
     * </code>
     *
     * @param     string $username The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PUserArchiveQuery The current query, for fluid interface
     */
    public function filterByUsername($username = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($username)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $username)) {
                $username = str_replace('*', '%', $username);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(PUserArchivePeer::USERNAME, $username, $comparison);
    }

    /**
     * Filter the query on the username_canonical column
     *
     * Example usage:
     * <code>
     * $query->filterByUsernameCanonical('fooValue');   // WHERE username_canonical = 'fooValue'
     * $query->filterByUsernameCanonical('%fooValue%'); // WHERE username_canonical LIKE '%fooValue%'
     * </code>
     *
     * @param     string $usernameCanonical The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PUserArchiveQuery The current query, for fluid interface
     */
    public function filterByUsernameCanonical($usernameCanonical = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($usernameCanonical)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $usernameCanonical)) {
                $usernameCanonical = str_replace('*', '%', $usernameCanonical);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(PUserArchivePeer::USERNAME_CANONICAL, $usernameCanonical, $comparison);
    }

    /**
     * Filter the query on the email column
     *
     * Example usage:
     * <code>
     * $query->filterByEmail('fooValue');   // WHERE email = 'fooValue'
     * $query->filterByEmail('%fooValue%'); // WHERE email LIKE '%fooValue%'
     * </code>
     *
     * @param     string $email The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PUserArchiveQuery The current query, for fluid interface
     */
    public function filterByEmail($email = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($email)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $email)) {
                $email = str_replace('*', '%', $email);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(PUserArchivePeer::EMAIL, $email, $comparison);
    }

    /**
     * Filter the query on the email_canonical column
     *
     * Example usage:
     * <code>
     * $query->filterByEmailCanonical('fooValue');   // WHERE email_canonical = 'fooValue'
     * $query->filterByEmailCanonical('%fooValue%'); // WHERE email_canonical LIKE '%fooValue%'
     * </code>
     *
     * @param     string $emailCanonical The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PUserArchiveQuery The current query, for fluid interface
     */
    public function filterByEmailCanonical($emailCanonical = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($emailCanonical)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $emailCanonical)) {
                $emailCanonical = str_replace('*', '%', $emailCanonical);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(PUserArchivePeer::EMAIL_CANONICAL, $emailCanonical, $comparison);
    }

    /**
     * Filter the query on the enabled column
     *
     * Example usage:
     * <code>
     * $query->filterByEnabled(true); // WHERE enabled = true
     * $query->filterByEnabled('yes'); // WHERE enabled = true
     * </code>
     *
     * @param     boolean|string $enabled The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PUserArchiveQuery The current query, for fluid interface
     */
    public function filterByEnabled($enabled = null, $comparison = null)
    {
        if (is_string($enabled)) {
            $enabled = in_array(strtolower($enabled), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(PUserArchivePeer::ENABLED, $enabled, $comparison);
    }

    /**
     * Filter the query on the salt column
     *
     * Example usage:
     * <code>
     * $query->filterBySalt('fooValue');   // WHERE salt = 'fooValue'
     * $query->filterBySalt('%fooValue%'); // WHERE salt LIKE '%fooValue%'
     * </code>
     *
     * @param     string $salt The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PUserArchiveQuery The current query, for fluid interface
     */
    public function filterBySalt($salt = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($salt)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $salt)) {
                $salt = str_replace('*', '%', $salt);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(PUserArchivePeer::SALT, $salt, $comparison);
    }

    /**
     * Filter the query on the password column
     *
     * Example usage:
     * <code>
     * $query->filterByPassword('fooValue');   // WHERE password = 'fooValue'
     * $query->filterByPassword('%fooValue%'); // WHERE password LIKE '%fooValue%'
     * </code>
     *
     * @param     string $password The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PUserArchiveQuery The current query, for fluid interface
     */
    public function filterByPassword($password = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($password)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $password)) {
                $password = str_replace('*', '%', $password);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(PUserArchivePeer::PASSWORD, $password, $comparison);
    }

    /**
     * Filter the query on the last_login column
     *
     * Example usage:
     * <code>
     * $query->filterByLastLogin('2011-03-14'); // WHERE last_login = '2011-03-14'
     * $query->filterByLastLogin('now'); // WHERE last_login = '2011-03-14'
     * $query->filterByLastLogin(array('max' => 'yesterday')); // WHERE last_login < '2011-03-13'
     * </code>
     *
     * @param     mixed $lastLogin The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PUserArchiveQuery The current query, for fluid interface
     */
    public function filterByLastLogin($lastLogin = null, $comparison = null)
    {
        if (is_array($lastLogin)) {
            $useMinMax = false;
            if (isset($lastLogin['min'])) {
                $this->addUsingAlias(PUserArchivePeer::LAST_LOGIN, $lastLogin['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($lastLogin['max'])) {
                $this->addUsingAlias(PUserArchivePeer::LAST_LOGIN, $lastLogin['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PUserArchivePeer::LAST_LOGIN, $lastLogin, $comparison);
    }

    /**
     * Filter the query on the locked column
     *
     * Example usage:
     * <code>
     * $query->filterByLocked(true); // WHERE locked = true
     * $query->filterByLocked('yes'); // WHERE locked = true
     * </code>
     *
     * @param     boolean|string $locked The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PUserArchiveQuery The current query, for fluid interface
     */
    public function filterByLocked($locked = null, $comparison = null)
    {
        if (is_string($locked)) {
            $locked = in_array(strtolower($locked), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(PUserArchivePeer::LOCKED, $locked, $comparison);
    }

    /**
     * Filter the query on the expired column
     *
     * Example usage:
     * <code>
     * $query->filterByExpired(true); // WHERE expired = true
     * $query->filterByExpired('yes'); // WHERE expired = true
     * </code>
     *
     * @param     boolean|string $expired The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PUserArchiveQuery The current query, for fluid interface
     */
    public function filterByExpired($expired = null, $comparison = null)
    {
        if (is_string($expired)) {
            $expired = in_array(strtolower($expired), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(PUserArchivePeer::EXPIRED, $expired, $comparison);
    }

    /**
     * Filter the query on the expires_at column
     *
     * Example usage:
     * <code>
     * $query->filterByExpiresAt('2011-03-14'); // WHERE expires_at = '2011-03-14'
     * $query->filterByExpiresAt('now'); // WHERE expires_at = '2011-03-14'
     * $query->filterByExpiresAt(array('max' => 'yesterday')); // WHERE expires_at < '2011-03-13'
     * </code>
     *
     * @param     mixed $expiresAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PUserArchiveQuery The current query, for fluid interface
     */
    public function filterByExpiresAt($expiresAt = null, $comparison = null)
    {
        if (is_array($expiresAt)) {
            $useMinMax = false;
            if (isset($expiresAt['min'])) {
                $this->addUsingAlias(PUserArchivePeer::EXPIRES_AT, $expiresAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($expiresAt['max'])) {
                $this->addUsingAlias(PUserArchivePeer::EXPIRES_AT, $expiresAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PUserArchivePeer::EXPIRES_AT, $expiresAt, $comparison);
    }

    /**
     * Filter the query on the confirmation_token column
     *
     * Example usage:
     * <code>
     * $query->filterByConfirmationToken('fooValue');   // WHERE confirmation_token = 'fooValue'
     * $query->filterByConfirmationToken('%fooValue%'); // WHERE confirmation_token LIKE '%fooValue%'
     * </code>
     *
     * @param     string $confirmationToken The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PUserArchiveQuery The current query, for fluid interface
     */
    public function filterByConfirmationToken($confirmationToken = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($confirmationToken)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $confirmationToken)) {
                $confirmationToken = str_replace('*', '%', $confirmationToken);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(PUserArchivePeer::CONFIRMATION_TOKEN, $confirmationToken, $comparison);
    }

    /**
     * Filter the query on the password_requested_at column
     *
     * Example usage:
     * <code>
     * $query->filterByPasswordRequestedAt('2011-03-14'); // WHERE password_requested_at = '2011-03-14'
     * $query->filterByPasswordRequestedAt('now'); // WHERE password_requested_at = '2011-03-14'
     * $query->filterByPasswordRequestedAt(array('max' => 'yesterday')); // WHERE password_requested_at < '2011-03-13'
     * </code>
     *
     * @param     mixed $passwordRequestedAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PUserArchiveQuery The current query, for fluid interface
     */
    public function filterByPasswordRequestedAt($passwordRequestedAt = null, $comparison = null)
    {
        if (is_array($passwordRequestedAt)) {
            $useMinMax = false;
            if (isset($passwordRequestedAt['min'])) {
                $this->addUsingAlias(PUserArchivePeer::PASSWORD_REQUESTED_AT, $passwordRequestedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($passwordRequestedAt['max'])) {
                $this->addUsingAlias(PUserArchivePeer::PASSWORD_REQUESTED_AT, $passwordRequestedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PUserArchivePeer::PASSWORD_REQUESTED_AT, $passwordRequestedAt, $comparison);
    }

    /**
     * Filter the query on the credentials_expired column
     *
     * Example usage:
     * <code>
     * $query->filterByCredentialsExpired(true); // WHERE credentials_expired = true
     * $query->filterByCredentialsExpired('yes'); // WHERE credentials_expired = true
     * </code>
     *
     * @param     boolean|string $credentialsExpired The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PUserArchiveQuery The current query, for fluid interface
     */
    public function filterByCredentialsExpired($credentialsExpired = null, $comparison = null)
    {
        if (is_string($credentialsExpired)) {
            $credentialsExpired = in_array(strtolower($credentialsExpired), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(PUserArchivePeer::CREDENTIALS_EXPIRED, $credentialsExpired, $comparison);
    }

    /**
     * Filter the query on the credentials_expire_at column
     *
     * Example usage:
     * <code>
     * $query->filterByCredentialsExpireAt('2011-03-14'); // WHERE credentials_expire_at = '2011-03-14'
     * $query->filterByCredentialsExpireAt('now'); // WHERE credentials_expire_at = '2011-03-14'
     * $query->filterByCredentialsExpireAt(array('max' => 'yesterday')); // WHERE credentials_expire_at < '2011-03-13'
     * </code>
     *
     * @param     mixed $credentialsExpireAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PUserArchiveQuery The current query, for fluid interface
     */
    public function filterByCredentialsExpireAt($credentialsExpireAt = null, $comparison = null)
    {
        if (is_array($credentialsExpireAt)) {
            $useMinMax = false;
            if (isset($credentialsExpireAt['min'])) {
                $this->addUsingAlias(PUserArchivePeer::CREDENTIALS_EXPIRE_AT, $credentialsExpireAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($credentialsExpireAt['max'])) {
                $this->addUsingAlias(PUserArchivePeer::CREDENTIALS_EXPIRE_AT, $credentialsExpireAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PUserArchivePeer::CREDENTIALS_EXPIRE_AT, $credentialsExpireAt, $comparison);
    }

    /**
     * Filter the query on the roles column
     *
     * @param     array $roles The values to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PUserArchiveQuery The current query, for fluid interface
     */
    public function filterByRoles($roles = null, $comparison = null)
    {
        $key = $this->getAliasedColName(PUserArchivePeer::ROLES);
        if (null === $comparison || $comparison == Criteria::CONTAINS_ALL) {
            foreach ($roles as $value) {
                $value = '%| ' . $value . ' |%';
                if ($this->containsKey($key)) {
                    $this->addAnd($key, $value, Criteria::LIKE);
                } else {
                    $this->add($key, $value, Criteria::LIKE);
                }
            }

            return $this;
        } elseif ($comparison == Criteria::CONTAINS_SOME) {
            foreach ($roles as $value) {
                $value = '%| ' . $value . ' |%';
                if ($this->containsKey($key)) {
                    $this->addOr($key, $value, Criteria::LIKE);
                } else {
                    $this->add($key, $value, Criteria::LIKE);
                }
            }

            return $this;
        } elseif ($comparison == Criteria::CONTAINS_NONE) {
            foreach ($roles as $value) {
                $value = '%| ' . $value . ' |%';
                if ($this->containsKey($key)) {
                    $this->addAnd($key, $value, Criteria::NOT_LIKE);
                } else {
                    $this->add($key, $value, Criteria::NOT_LIKE);
                }
            }
            $this->addOr($key, null, Criteria::ISNULL);

            return $this;
        }

        return $this->addUsingAlias(PUserArchivePeer::ROLES, $roles, $comparison);
    }

    /**
     * Filter the query on the roles column
     * @param     mixed $roles The value to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::CONTAINS_ALL
     *
     * @return PUserArchiveQuery The current query, for fluid interface
     */
    public function filterByRole($roles = null, $comparison = null)
    {
        if (null === $comparison || $comparison == Criteria::CONTAINS_ALL) {
            if (is_scalar($roles)) {
                $roles = '%| ' . $roles . ' |%';
                $comparison = Criteria::LIKE;
            }
        } elseif ($comparison == Criteria::CONTAINS_NONE) {
            $roles = '%| ' . $roles . ' |%';
            $comparison = Criteria::NOT_LIKE;
            $key = $this->getAliasedColName(PUserArchivePeer::ROLES);
            if ($this->containsKey($key)) {
                $this->addAnd($key, $roles, $comparison);
            } else {
                $this->addAnd($key, $roles, $comparison);
            }
            $this->addOr($key, null, Criteria::ISNULL);

            return $this;
        }

        return $this->addUsingAlias(PUserArchivePeer::ROLES, $roles, $comparison);
    }

    /**
     * Filter the query on the last_activity column
     *
     * Example usage:
     * <code>
     * $query->filterByLastActivity('2011-03-14'); // WHERE last_activity = '2011-03-14'
     * $query->filterByLastActivity('now'); // WHERE last_activity = '2011-03-14'
     * $query->filterByLastActivity(array('max' => 'yesterday')); // WHERE last_activity < '2011-03-13'
     * </code>
     *
     * @param     mixed $lastActivity The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PUserArchiveQuery The current query, for fluid interface
     */
    public function filterByLastActivity($lastActivity = null, $comparison = null)
    {
        if (is_array($lastActivity)) {
            $useMinMax = false;
            if (isset($lastActivity['min'])) {
                $this->addUsingAlias(PUserArchivePeer::LAST_ACTIVITY, $lastActivity['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($lastActivity['max'])) {
                $this->addUsingAlias(PUserArchivePeer::LAST_ACTIVITY, $lastActivity['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PUserArchivePeer::LAST_ACTIVITY, $lastActivity, $comparison);
    }

    /**
     * Filter the query on the file_name column
     *
     * Example usage:
     * <code>
     * $query->filterByFileName('fooValue');   // WHERE file_name = 'fooValue'
     * $query->filterByFileName('%fooValue%'); // WHERE file_name LIKE '%fooValue%'
     * </code>
     *
     * @param     string $fileName The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PUserArchiveQuery The current query, for fluid interface
     */
    public function filterByFileName($fileName = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($fileName)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $fileName)) {
                $fileName = str_replace('*', '%', $fileName);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(PUserArchivePeer::FILE_NAME, $fileName, $comparison);
    }

    /**
     * Filter the query on the back_file_name column
     *
     * Example usage:
     * <code>
     * $query->filterByBackFileName('fooValue');   // WHERE back_file_name = 'fooValue'
     * $query->filterByBackFileName('%fooValue%'); // WHERE back_file_name LIKE '%fooValue%'
     * </code>
     *
     * @param     string $backFileName The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PUserArchiveQuery The current query, for fluid interface
     */
    public function filterByBackFileName($backFileName = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($backFileName)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $backFileName)) {
                $backFileName = str_replace('*', '%', $backFileName);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(PUserArchivePeer::BACK_FILE_NAME, $backFileName, $comparison);
    }

    /**
     * Filter the query on the copyright column
     *
     * Example usage:
     * <code>
     * $query->filterByCopyright('fooValue');   // WHERE copyright = 'fooValue'
     * $query->filterByCopyright('%fooValue%'); // WHERE copyright LIKE '%fooValue%'
     * </code>
     *
     * @param     string $copyright The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PUserArchiveQuery The current query, for fluid interface
     */
    public function filterByCopyright($copyright = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($copyright)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $copyright)) {
                $copyright = str_replace('*', '%', $copyright);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(PUserArchivePeer::COPYRIGHT, $copyright, $comparison);
    }

    /**
     * Filter the query on the gender column
     *
     * @param     mixed $gender The value to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PUserArchiveQuery The current query, for fluid interface
     * @throws PropelException - if the value is not accepted by the enum.
     */
    public function filterByGender($gender = null, $comparison = null)
    {
        if (is_scalar($gender)) {
            $gender = PUserArchivePeer::getSqlValueForEnum(PUserArchivePeer::GENDER, $gender);
        } elseif (is_array($gender)) {
            $convertedValues = array();
            foreach ($gender as $value) {
                $convertedValues[] = PUserArchivePeer::getSqlValueForEnum(PUserArchivePeer::GENDER, $value);
            }
            $gender = $convertedValues;
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PUserArchivePeer::GENDER, $gender, $comparison);
    }

    /**
     * Filter the query on the firstname column
     *
     * Example usage:
     * <code>
     * $query->filterByFirstname('fooValue');   // WHERE firstname = 'fooValue'
     * $query->filterByFirstname('%fooValue%'); // WHERE firstname LIKE '%fooValue%'
     * </code>
     *
     * @param     string $firstname The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PUserArchiveQuery The current query, for fluid interface
     */
    public function filterByFirstname($firstname = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($firstname)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $firstname)) {
                $firstname = str_replace('*', '%', $firstname);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(PUserArchivePeer::FIRSTNAME, $firstname, $comparison);
    }

    /**
     * Filter the query on the name column
     *
     * Example usage:
     * <code>
     * $query->filterByName('fooValue');   // WHERE name = 'fooValue'
     * $query->filterByName('%fooValue%'); // WHERE name LIKE '%fooValue%'
     * </code>
     *
     * @param     string $name The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PUserArchiveQuery The current query, for fluid interface
     */
    public function filterByName($name = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($name)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $name)) {
                $name = str_replace('*', '%', $name);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(PUserArchivePeer::NAME, $name, $comparison);
    }

    /**
     * Filter the query on the birthday column
     *
     * Example usage:
     * <code>
     * $query->filterByBirthday('2011-03-14'); // WHERE birthday = '2011-03-14'
     * $query->filterByBirthday('now'); // WHERE birthday = '2011-03-14'
     * $query->filterByBirthday(array('max' => 'yesterday')); // WHERE birthday < '2011-03-13'
     * </code>
     *
     * @param     mixed $birthday The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PUserArchiveQuery The current query, for fluid interface
     */
    public function filterByBirthday($birthday = null, $comparison = null)
    {
        if (is_array($birthday)) {
            $useMinMax = false;
            if (isset($birthday['min'])) {
                $this->addUsingAlias(PUserArchivePeer::BIRTHDAY, $birthday['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($birthday['max'])) {
                $this->addUsingAlias(PUserArchivePeer::BIRTHDAY, $birthday['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PUserArchivePeer::BIRTHDAY, $birthday, $comparison);
    }

    /**
     * Filter the query on the subtitle column
     *
     * Example usage:
     * <code>
     * $query->filterBySubtitle('fooValue');   // WHERE subtitle = 'fooValue'
     * $query->filterBySubtitle('%fooValue%'); // WHERE subtitle LIKE '%fooValue%'
     * </code>
     *
     * @param     string $subtitle The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PUserArchiveQuery The current query, for fluid interface
     */
    public function filterBySubtitle($subtitle = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($subtitle)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $subtitle)) {
                $subtitle = str_replace('*', '%', $subtitle);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(PUserArchivePeer::SUBTITLE, $subtitle, $comparison);
    }

    /**
     * Filter the query on the biography column
     *
     * Example usage:
     * <code>
     * $query->filterByBiography('fooValue');   // WHERE biography = 'fooValue'
     * $query->filterByBiography('%fooValue%'); // WHERE biography LIKE '%fooValue%'
     * </code>
     *
     * @param     string $biography The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PUserArchiveQuery The current query, for fluid interface
     */
    public function filterByBiography($biography = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($biography)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $biography)) {
                $biography = str_replace('*', '%', $biography);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(PUserArchivePeer::BIOGRAPHY, $biography, $comparison);
    }

    /**
     * Filter the query on the website column
     *
     * Example usage:
     * <code>
     * $query->filterByWebsite('fooValue');   // WHERE website = 'fooValue'
     * $query->filterByWebsite('%fooValue%'); // WHERE website LIKE '%fooValue%'
     * </code>
     *
     * @param     string $website The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PUserArchiveQuery The current query, for fluid interface
     */
    public function filterByWebsite($website = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($website)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $website)) {
                $website = str_replace('*', '%', $website);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(PUserArchivePeer::WEBSITE, $website, $comparison);
    }

    /**
     * Filter the query on the twitter column
     *
     * Example usage:
     * <code>
     * $query->filterByTwitter('fooValue');   // WHERE twitter = 'fooValue'
     * $query->filterByTwitter('%fooValue%'); // WHERE twitter LIKE '%fooValue%'
     * </code>
     *
     * @param     string $twitter The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PUserArchiveQuery The current query, for fluid interface
     */
    public function filterByTwitter($twitter = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($twitter)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $twitter)) {
                $twitter = str_replace('*', '%', $twitter);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(PUserArchivePeer::TWITTER, $twitter, $comparison);
    }

    /**
     * Filter the query on the facebook column
     *
     * Example usage:
     * <code>
     * $query->filterByFacebook('fooValue');   // WHERE facebook = 'fooValue'
     * $query->filterByFacebook('%fooValue%'); // WHERE facebook LIKE '%fooValue%'
     * </code>
     *
     * @param     string $facebook The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PUserArchiveQuery The current query, for fluid interface
     */
    public function filterByFacebook($facebook = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($facebook)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $facebook)) {
                $facebook = str_replace('*', '%', $facebook);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(PUserArchivePeer::FACEBOOK, $facebook, $comparison);
    }

    /**
     * Filter the query on the phone column
     *
     * Example usage:
     * <code>
     * $query->filterByPhone('fooValue');   // WHERE phone = 'fooValue'
     * $query->filterByPhone('%fooValue%'); // WHERE phone LIKE '%fooValue%'
     * </code>
     *
     * @param     string $phone The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PUserArchiveQuery The current query, for fluid interface
     */
    public function filterByPhone($phone = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($phone)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $phone)) {
                $phone = str_replace('*', '%', $phone);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(PUserArchivePeer::PHONE, $phone, $comparison);
    }

    /**
     * Filter the query on the newsletter column
     *
     * Example usage:
     * <code>
     * $query->filterByNewsletter(true); // WHERE newsletter = true
     * $query->filterByNewsletter('yes'); // WHERE newsletter = true
     * </code>
     *
     * @param     boolean|string $newsletter The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PUserArchiveQuery The current query, for fluid interface
     */
    public function filterByNewsletter($newsletter = null, $comparison = null)
    {
        if (is_string($newsletter)) {
            $newsletter = in_array(strtolower($newsletter), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(PUserArchivePeer::NEWSLETTER, $newsletter, $comparison);
    }

    /**
     * Filter the query on the last_connect column
     *
     * Example usage:
     * <code>
     * $query->filterByLastConnect('2011-03-14'); // WHERE last_connect = '2011-03-14'
     * $query->filterByLastConnect('now'); // WHERE last_connect = '2011-03-14'
     * $query->filterByLastConnect(array('max' => 'yesterday')); // WHERE last_connect < '2011-03-13'
     * </code>
     *
     * @param     mixed $lastConnect The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PUserArchiveQuery The current query, for fluid interface
     */
    public function filterByLastConnect($lastConnect = null, $comparison = null)
    {
        if (is_array($lastConnect)) {
            $useMinMax = false;
            if (isset($lastConnect['min'])) {
                $this->addUsingAlias(PUserArchivePeer::LAST_CONNECT, $lastConnect['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($lastConnect['max'])) {
                $this->addUsingAlias(PUserArchivePeer::LAST_CONNECT, $lastConnect['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PUserArchivePeer::LAST_CONNECT, $lastConnect, $comparison);
    }

    /**
     * Filter the query on the nb_connected_days column
     *
     * Example usage:
     * <code>
     * $query->filterByNbConnectedDays(1234); // WHERE nb_connected_days = 1234
     * $query->filterByNbConnectedDays(array(12, 34)); // WHERE nb_connected_days IN (12, 34)
     * $query->filterByNbConnectedDays(array('min' => 12)); // WHERE nb_connected_days >= 12
     * $query->filterByNbConnectedDays(array('max' => 12)); // WHERE nb_connected_days <= 12
     * </code>
     *
     * @param     mixed $nbConnectedDays The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PUserArchiveQuery The current query, for fluid interface
     */
    public function filterByNbConnectedDays($nbConnectedDays = null, $comparison = null)
    {
        if (is_array($nbConnectedDays)) {
            $useMinMax = false;
            if (isset($nbConnectedDays['min'])) {
                $this->addUsingAlias(PUserArchivePeer::NB_CONNECTED_DAYS, $nbConnectedDays['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($nbConnectedDays['max'])) {
                $this->addUsingAlias(PUserArchivePeer::NB_CONNECTED_DAYS, $nbConnectedDays['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PUserArchivePeer::NB_CONNECTED_DAYS, $nbConnectedDays, $comparison);
    }

    /**
     * Filter the query on the indexed_at column
     *
     * Example usage:
     * <code>
     * $query->filterByIndexedAt('2011-03-14'); // WHERE indexed_at = '2011-03-14'
     * $query->filterByIndexedAt('now'); // WHERE indexed_at = '2011-03-14'
     * $query->filterByIndexedAt(array('max' => 'yesterday')); // WHERE indexed_at < '2011-03-13'
     * </code>
     *
     * @param     mixed $indexedAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PUserArchiveQuery The current query, for fluid interface
     */
    public function filterByIndexedAt($indexedAt = null, $comparison = null)
    {
        if (is_array($indexedAt)) {
            $useMinMax = false;
            if (isset($indexedAt['min'])) {
                $this->addUsingAlias(PUserArchivePeer::INDEXED_AT, $indexedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($indexedAt['max'])) {
                $this->addUsingAlias(PUserArchivePeer::INDEXED_AT, $indexedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PUserArchivePeer::INDEXED_AT, $indexedAt, $comparison);
    }

    /**
     * Filter the query on the nb_views column
     *
     * Example usage:
     * <code>
     * $query->filterByNbViews(1234); // WHERE nb_views = 1234
     * $query->filterByNbViews(array(12, 34)); // WHERE nb_views IN (12, 34)
     * $query->filterByNbViews(array('min' => 12)); // WHERE nb_views >= 12
     * $query->filterByNbViews(array('max' => 12)); // WHERE nb_views <= 12
     * </code>
     *
     * @param     mixed $nbViews The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PUserArchiveQuery The current query, for fluid interface
     */
    public function filterByNbViews($nbViews = null, $comparison = null)
    {
        if (is_array($nbViews)) {
            $useMinMax = false;
            if (isset($nbViews['min'])) {
                $this->addUsingAlias(PUserArchivePeer::NB_VIEWS, $nbViews['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($nbViews['max'])) {
                $this->addUsingAlias(PUserArchivePeer::NB_VIEWS, $nbViews['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PUserArchivePeer::NB_VIEWS, $nbViews, $comparison);
    }

    /**
     * Filter the query on the qualified column
     *
     * Example usage:
     * <code>
     * $query->filterByQualified(true); // WHERE qualified = true
     * $query->filterByQualified('yes'); // WHERE qualified = true
     * </code>
     *
     * @param     boolean|string $qualified The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PUserArchiveQuery The current query, for fluid interface
     */
    public function filterByQualified($qualified = null, $comparison = null)
    {
        if (is_string($qualified)) {
            $qualified = in_array(strtolower($qualified), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(PUserArchivePeer::QUALIFIED, $qualified, $comparison);
    }

    /**
     * Filter the query on the validated column
     *
     * Example usage:
     * <code>
     * $query->filterByValidated(true); // WHERE validated = true
     * $query->filterByValidated('yes'); // WHERE validated = true
     * </code>
     *
     * @param     boolean|string $validated The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PUserArchiveQuery The current query, for fluid interface
     */
    public function filterByValidated($validated = null, $comparison = null)
    {
        if (is_string($validated)) {
            $validated = in_array(strtolower($validated), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(PUserArchivePeer::VALIDATED, $validated, $comparison);
    }

    /**
     * Filter the query on the nb_id_check column
     *
     * Example usage:
     * <code>
     * $query->filterByNbIdCheck(1234); // WHERE nb_id_check = 1234
     * $query->filterByNbIdCheck(array(12, 34)); // WHERE nb_id_check IN (12, 34)
     * $query->filterByNbIdCheck(array('min' => 12)); // WHERE nb_id_check >= 12
     * $query->filterByNbIdCheck(array('max' => 12)); // WHERE nb_id_check <= 12
     * </code>
     *
     * @param     mixed $nbIdCheck The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PUserArchiveQuery The current query, for fluid interface
     */
    public function filterByNbIdCheck($nbIdCheck = null, $comparison = null)
    {
        if (is_array($nbIdCheck)) {
            $useMinMax = false;
            if (isset($nbIdCheck['min'])) {
                $this->addUsingAlias(PUserArchivePeer::NB_ID_CHECK, $nbIdCheck['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($nbIdCheck['max'])) {
                $this->addUsingAlias(PUserArchivePeer::NB_ID_CHECK, $nbIdCheck['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PUserArchivePeer::NB_ID_CHECK, $nbIdCheck, $comparison);
    }

    /**
     * Filter the query on the organization column
     *
     * Example usage:
     * <code>
     * $query->filterByOrganization(true); // WHERE organization = true
     * $query->filterByOrganization('yes'); // WHERE organization = true
     * </code>
     *
     * @param     boolean|string $organization The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PUserArchiveQuery The current query, for fluid interface
     */
    public function filterByOrganization($organization = null, $comparison = null)
    {
        if (is_string($organization)) {
            $organization = in_array(strtolower($organization), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(PUserArchivePeer::ORGANIZATION, $organization, $comparison);
    }

    /**
     * Filter the query on the online column
     *
     * Example usage:
     * <code>
     * $query->filterByOnline(true); // WHERE online = true
     * $query->filterByOnline('yes'); // WHERE online = true
     * </code>
     *
     * @param     boolean|string $online The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PUserArchiveQuery The current query, for fluid interface
     */
    public function filterByOnline($online = null, $comparison = null)
    {
        if (is_string($online)) {
            $online = in_array(strtolower($online), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(PUserArchivePeer::ONLINE, $online, $comparison);
    }

    /**
     * Filter the query on the homepage column
     *
     * Example usage:
     * <code>
     * $query->filterByHomepage(true); // WHERE homepage = true
     * $query->filterByHomepage('yes'); // WHERE homepage = true
     * </code>
     *
     * @param     boolean|string $homepage The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PUserArchiveQuery The current query, for fluid interface
     */
    public function filterByHomepage($homepage = null, $comparison = null)
    {
        if (is_string($homepage)) {
            $homepage = in_array(strtolower($homepage), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(PUserArchivePeer::HOMEPAGE, $homepage, $comparison);
    }

    /**
     * Filter the query on the support_group column
     *
     * Example usage:
     * <code>
     * $query->filterBySupportGroup(true); // WHERE support_group = true
     * $query->filterBySupportGroup('yes'); // WHERE support_group = true
     * </code>
     *
     * @param     boolean|string $supportGroup The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PUserArchiveQuery The current query, for fluid interface
     */
    public function filterBySupportGroup($supportGroup = null, $comparison = null)
    {
        if (is_string($supportGroup)) {
            $supportGroup = in_array(strtolower($supportGroup), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(PUserArchivePeer::SUPPORT_GROUP, $supportGroup, $comparison);
    }

    /**
     * Filter the query on the banned column
     *
     * Example usage:
     * <code>
     * $query->filterByBanned(true); // WHERE banned = true
     * $query->filterByBanned('yes'); // WHERE banned = true
     * </code>
     *
     * @param     boolean|string $banned The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PUserArchiveQuery The current query, for fluid interface
     */
    public function filterByBanned($banned = null, $comparison = null)
    {
        if (is_string($banned)) {
            $banned = in_array(strtolower($banned), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(PUserArchivePeer::BANNED, $banned, $comparison);
    }

    /**
     * Filter the query on the banned_nb_days_left column
     *
     * Example usage:
     * <code>
     * $query->filterByBannedNbDaysLeft(1234); // WHERE banned_nb_days_left = 1234
     * $query->filterByBannedNbDaysLeft(array(12, 34)); // WHERE banned_nb_days_left IN (12, 34)
     * $query->filterByBannedNbDaysLeft(array('min' => 12)); // WHERE banned_nb_days_left >= 12
     * $query->filterByBannedNbDaysLeft(array('max' => 12)); // WHERE banned_nb_days_left <= 12
     * </code>
     *
     * @param     mixed $bannedNbDaysLeft The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PUserArchiveQuery The current query, for fluid interface
     */
    public function filterByBannedNbDaysLeft($bannedNbDaysLeft = null, $comparison = null)
    {
        if (is_array($bannedNbDaysLeft)) {
            $useMinMax = false;
            if (isset($bannedNbDaysLeft['min'])) {
                $this->addUsingAlias(PUserArchivePeer::BANNED_NB_DAYS_LEFT, $bannedNbDaysLeft['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($bannedNbDaysLeft['max'])) {
                $this->addUsingAlias(PUserArchivePeer::BANNED_NB_DAYS_LEFT, $bannedNbDaysLeft['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PUserArchivePeer::BANNED_NB_DAYS_LEFT, $bannedNbDaysLeft, $comparison);
    }

    /**
     * Filter the query on the banned_nb_total column
     *
     * Example usage:
     * <code>
     * $query->filterByBannedNbTotal(1234); // WHERE banned_nb_total = 1234
     * $query->filterByBannedNbTotal(array(12, 34)); // WHERE banned_nb_total IN (12, 34)
     * $query->filterByBannedNbTotal(array('min' => 12)); // WHERE banned_nb_total >= 12
     * $query->filterByBannedNbTotal(array('max' => 12)); // WHERE banned_nb_total <= 12
     * </code>
     *
     * @param     mixed $bannedNbTotal The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PUserArchiveQuery The current query, for fluid interface
     */
    public function filterByBannedNbTotal($bannedNbTotal = null, $comparison = null)
    {
        if (is_array($bannedNbTotal)) {
            $useMinMax = false;
            if (isset($bannedNbTotal['min'])) {
                $this->addUsingAlias(PUserArchivePeer::BANNED_NB_TOTAL, $bannedNbTotal['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($bannedNbTotal['max'])) {
                $this->addUsingAlias(PUserArchivePeer::BANNED_NB_TOTAL, $bannedNbTotal['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PUserArchivePeer::BANNED_NB_TOTAL, $bannedNbTotal, $comparison);
    }

    /**
     * Filter the query on the abuse_level column
     *
     * Example usage:
     * <code>
     * $query->filterByAbuseLevel(1234); // WHERE abuse_level = 1234
     * $query->filterByAbuseLevel(array(12, 34)); // WHERE abuse_level IN (12, 34)
     * $query->filterByAbuseLevel(array('min' => 12)); // WHERE abuse_level >= 12
     * $query->filterByAbuseLevel(array('max' => 12)); // WHERE abuse_level <= 12
     * </code>
     *
     * @param     mixed $abuseLevel The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PUserArchiveQuery The current query, for fluid interface
     */
    public function filterByAbuseLevel($abuseLevel = null, $comparison = null)
    {
        if (is_array($abuseLevel)) {
            $useMinMax = false;
            if (isset($abuseLevel['min'])) {
                $this->addUsingAlias(PUserArchivePeer::ABUSE_LEVEL, $abuseLevel['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($abuseLevel['max'])) {
                $this->addUsingAlias(PUserArchivePeer::ABUSE_LEVEL, $abuseLevel['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PUserArchivePeer::ABUSE_LEVEL, $abuseLevel, $comparison);
    }

    /**
     * Filter the query on the created_at column
     *
     * Example usage:
     * <code>
     * $query->filterByCreatedAt('2011-03-14'); // WHERE created_at = '2011-03-14'
     * $query->filterByCreatedAt('now'); // WHERE created_at = '2011-03-14'
     * $query->filterByCreatedAt(array('max' => 'yesterday')); // WHERE created_at < '2011-03-13'
     * </code>
     *
     * @param     mixed $createdAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PUserArchiveQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(PUserArchivePeer::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(PUserArchivePeer::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PUserArchivePeer::CREATED_AT, $createdAt, $comparison);
    }

    /**
     * Filter the query on the updated_at column
     *
     * Example usage:
     * <code>
     * $query->filterByUpdatedAt('2011-03-14'); // WHERE updated_at = '2011-03-14'
     * $query->filterByUpdatedAt('now'); // WHERE updated_at = '2011-03-14'
     * $query->filterByUpdatedAt(array('max' => 'yesterday')); // WHERE updated_at < '2011-03-13'
     * </code>
     *
     * @param     mixed $updatedAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PUserArchiveQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(PUserArchivePeer::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(PUserArchivePeer::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PUserArchivePeer::UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query on the slug column
     *
     * Example usage:
     * <code>
     * $query->filterBySlug('fooValue');   // WHERE slug = 'fooValue'
     * $query->filterBySlug('%fooValue%'); // WHERE slug LIKE '%fooValue%'
     * </code>
     *
     * @param     string $slug The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PUserArchiveQuery The current query, for fluid interface
     */
    public function filterBySlug($slug = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($slug)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $slug)) {
                $slug = str_replace('*', '%', $slug);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(PUserArchivePeer::SLUG, $slug, $comparison);
    }

    /**
     * Filter the query on the archived_at column
     *
     * Example usage:
     * <code>
     * $query->filterByArchivedAt('2011-03-14'); // WHERE archived_at = '2011-03-14'
     * $query->filterByArchivedAt('now'); // WHERE archived_at = '2011-03-14'
     * $query->filterByArchivedAt(array('max' => 'yesterday')); // WHERE archived_at < '2011-03-13'
     * </code>
     *
     * @param     mixed $archivedAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PUserArchiveQuery The current query, for fluid interface
     */
    public function filterByArchivedAt($archivedAt = null, $comparison = null)
    {
        if (is_array($archivedAt)) {
            $useMinMax = false;
            if (isset($archivedAt['min'])) {
                $this->addUsingAlias(PUserArchivePeer::ARCHIVED_AT, $archivedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($archivedAt['max'])) {
                $this->addUsingAlias(PUserArchivePeer::ARCHIVED_AT, $archivedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PUserArchivePeer::ARCHIVED_AT, $archivedAt, $comparison);
    }

    /**
     * Exclude object from result
     *
     * @param   PUserArchive $pUserArchive Object to remove from the list of results
     *
     * @return PUserArchiveQuery The current query, for fluid interface
     */
    public function prune($pUserArchive = null)
    {
        if ($pUserArchive) {
            $this->addUsingAlias(PUserArchivePeer::ID, $pUserArchive->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

}
