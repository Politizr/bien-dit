<?php

namespace Politizr\Model\om;

use \BasePeer;
use \Criteria;
use \Exception;
use \ModelCriteria;
use \ModelJoin;
use \PDO;
use \Propel;
use \PropelCollection;
use \PropelException;
use \PropelObjectCollection;
use \PropelPDO;
use Glorpen\Propel\PropelBundle\Dispatcher\EventDispatcherProxy;
use Glorpen\Propel\PropelBundle\Events\QueryEvent;
use Politizr\Model\PDDComment;
use Politizr\Model\PDDebate;
use Politizr\Model\PDRComment;
use Politizr\Model\PDReaction;
use Politizr\Model\PMAbuseReporting;
use Politizr\Model\PMAppException;
use Politizr\Model\PMAskForUpdate;
use Politizr\Model\PMModerationType;
use Politizr\Model\PMUserMessage;
use Politizr\Model\PMUserModerated;
use Politizr\Model\PNotification;
use Politizr\Model\POrder;
use Politizr\Model\PQOrganization;
use Politizr\Model\PQualification;
use Politizr\Model\PRAction;
use Politizr\Model\PRBadge;
use Politizr\Model\PTag;
use Politizr\Model\PUAffinityQO;
use Politizr\Model\PUBadge;
use Politizr\Model\PUCurrentQO;
use Politizr\Model\PUFollowDD;
use Politizr\Model\PUFollowT;
use Politizr\Model\PUFollowU;
use Politizr\Model\PUMandate;
use Politizr\Model\PUNotification;
use Politizr\Model\PUReputation;
use Politizr\Model\PURoleQ;
use Politizr\Model\PUStatus;
use Politizr\Model\PUSubscribeEmail;
use Politizr\Model\PUSubscribeScreen;
use Politizr\Model\PUTaggedT;
use Politizr\Model\PUser;
use Politizr\Model\PUserPeer;
use Politizr\Model\PUserQuery;

/**
 * @method PUserQuery orderById($order = Criteria::ASC) Order by the id column
 * @method PUserQuery orderByProvider($order = Criteria::ASC) Order by the provider column
 * @method PUserQuery orderByProviderId($order = Criteria::ASC) Order by the provider_id column
 * @method PUserQuery orderByNickname($order = Criteria::ASC) Order by the nickname column
 * @method PUserQuery orderByRealname($order = Criteria::ASC) Order by the realname column
 * @method PUserQuery orderByUsername($order = Criteria::ASC) Order by the username column
 * @method PUserQuery orderByUsernameCanonical($order = Criteria::ASC) Order by the username_canonical column
 * @method PUserQuery orderByEmail($order = Criteria::ASC) Order by the email column
 * @method PUserQuery orderByEmailCanonical($order = Criteria::ASC) Order by the email_canonical column
 * @method PUserQuery orderByEnabled($order = Criteria::ASC) Order by the enabled column
 * @method PUserQuery orderBySalt($order = Criteria::ASC) Order by the salt column
 * @method PUserQuery orderByPassword($order = Criteria::ASC) Order by the password column
 * @method PUserQuery orderByLastLogin($order = Criteria::ASC) Order by the last_login column
 * @method PUserQuery orderByLocked($order = Criteria::ASC) Order by the locked column
 * @method PUserQuery orderByExpired($order = Criteria::ASC) Order by the expired column
 * @method PUserQuery orderByExpiresAt($order = Criteria::ASC) Order by the expires_at column
 * @method PUserQuery orderByConfirmationToken($order = Criteria::ASC) Order by the confirmation_token column
 * @method PUserQuery orderByPasswordRequestedAt($order = Criteria::ASC) Order by the password_requested_at column
 * @method PUserQuery orderByCredentialsExpired($order = Criteria::ASC) Order by the credentials_expired column
 * @method PUserQuery orderByCredentialsExpireAt($order = Criteria::ASC) Order by the credentials_expire_at column
 * @method PUserQuery orderByRoles($order = Criteria::ASC) Order by the roles column
 * @method PUserQuery orderByLastActivity($order = Criteria::ASC) Order by the last_activity column
 * @method PUserQuery orderByPUStatusId($order = Criteria::ASC) Order by the p_u_status_id column
 * @method PUserQuery orderByFileName($order = Criteria::ASC) Order by the file_name column
 * @method PUserQuery orderByBackFileName($order = Criteria::ASC) Order by the back_file_name column
 * @method PUserQuery orderByCopyright($order = Criteria::ASC) Order by the copyright column
 * @method PUserQuery orderByGender($order = Criteria::ASC) Order by the gender column
 * @method PUserQuery orderByFirstname($order = Criteria::ASC) Order by the firstname column
 * @method PUserQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method PUserQuery orderByBirthday($order = Criteria::ASC) Order by the birthday column
 * @method PUserQuery orderBySubtitle($order = Criteria::ASC) Order by the subtitle column
 * @method PUserQuery orderByBiography($order = Criteria::ASC) Order by the biography column
 * @method PUserQuery orderByWebsite($order = Criteria::ASC) Order by the website column
 * @method PUserQuery orderByTwitter($order = Criteria::ASC) Order by the twitter column
 * @method PUserQuery orderByFacebook($order = Criteria::ASC) Order by the facebook column
 * @method PUserQuery orderByPhone($order = Criteria::ASC) Order by the phone column
 * @method PUserQuery orderByNewsletter($order = Criteria::ASC) Order by the newsletter column
 * @method PUserQuery orderByLastConnect($order = Criteria::ASC) Order by the last_connect column
 * @method PUserQuery orderByNbConnectedDays($order = Criteria::ASC) Order by the nb_connected_days column
 * @method PUserQuery orderByNbViews($order = Criteria::ASC) Order by the nb_views column
 * @method PUserQuery orderByQualified($order = Criteria::ASC) Order by the qualified column
 * @method PUserQuery orderByValidated($order = Criteria::ASC) Order by the validated column
 * @method PUserQuery orderByOnline($order = Criteria::ASC) Order by the online column
 * @method PUserQuery orderByBanned($order = Criteria::ASC) Order by the banned column
 * @method PUserQuery orderByBannedAt($order = Criteria::ASC) Order by the banned_at column
 * @method PUserQuery orderByAbuseLevel($order = Criteria::ASC) Order by the abuse_level column
 * @method PUserQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method PUserQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 * @method PUserQuery orderBySlug($order = Criteria::ASC) Order by the slug column
 *
 * @method PUserQuery groupById() Group by the id column
 * @method PUserQuery groupByProvider() Group by the provider column
 * @method PUserQuery groupByProviderId() Group by the provider_id column
 * @method PUserQuery groupByNickname() Group by the nickname column
 * @method PUserQuery groupByRealname() Group by the realname column
 * @method PUserQuery groupByUsername() Group by the username column
 * @method PUserQuery groupByUsernameCanonical() Group by the username_canonical column
 * @method PUserQuery groupByEmail() Group by the email column
 * @method PUserQuery groupByEmailCanonical() Group by the email_canonical column
 * @method PUserQuery groupByEnabled() Group by the enabled column
 * @method PUserQuery groupBySalt() Group by the salt column
 * @method PUserQuery groupByPassword() Group by the password column
 * @method PUserQuery groupByLastLogin() Group by the last_login column
 * @method PUserQuery groupByLocked() Group by the locked column
 * @method PUserQuery groupByExpired() Group by the expired column
 * @method PUserQuery groupByExpiresAt() Group by the expires_at column
 * @method PUserQuery groupByConfirmationToken() Group by the confirmation_token column
 * @method PUserQuery groupByPasswordRequestedAt() Group by the password_requested_at column
 * @method PUserQuery groupByCredentialsExpired() Group by the credentials_expired column
 * @method PUserQuery groupByCredentialsExpireAt() Group by the credentials_expire_at column
 * @method PUserQuery groupByRoles() Group by the roles column
 * @method PUserQuery groupByLastActivity() Group by the last_activity column
 * @method PUserQuery groupByPUStatusId() Group by the p_u_status_id column
 * @method PUserQuery groupByFileName() Group by the file_name column
 * @method PUserQuery groupByBackFileName() Group by the back_file_name column
 * @method PUserQuery groupByCopyright() Group by the copyright column
 * @method PUserQuery groupByGender() Group by the gender column
 * @method PUserQuery groupByFirstname() Group by the firstname column
 * @method PUserQuery groupByName() Group by the name column
 * @method PUserQuery groupByBirthday() Group by the birthday column
 * @method PUserQuery groupBySubtitle() Group by the subtitle column
 * @method PUserQuery groupByBiography() Group by the biography column
 * @method PUserQuery groupByWebsite() Group by the website column
 * @method PUserQuery groupByTwitter() Group by the twitter column
 * @method PUserQuery groupByFacebook() Group by the facebook column
 * @method PUserQuery groupByPhone() Group by the phone column
 * @method PUserQuery groupByNewsletter() Group by the newsletter column
 * @method PUserQuery groupByLastConnect() Group by the last_connect column
 * @method PUserQuery groupByNbConnectedDays() Group by the nb_connected_days column
 * @method PUserQuery groupByNbViews() Group by the nb_views column
 * @method PUserQuery groupByQualified() Group by the qualified column
 * @method PUserQuery groupByValidated() Group by the validated column
 * @method PUserQuery groupByOnline() Group by the online column
 * @method PUserQuery groupByBanned() Group by the banned column
 * @method PUserQuery groupByBannedAt() Group by the banned_at column
 * @method PUserQuery groupByAbuseLevel() Group by the abuse_level column
 * @method PUserQuery groupByCreatedAt() Group by the created_at column
 * @method PUserQuery groupByUpdatedAt() Group by the updated_at column
 * @method PUserQuery groupBySlug() Group by the slug column
 *
 * @method PUserQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method PUserQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method PUserQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method PUserQuery leftJoinPUStatus($relationAlias = null) Adds a LEFT JOIN clause to the query using the PUStatus relation
 * @method PUserQuery rightJoinPUStatus($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PUStatus relation
 * @method PUserQuery innerJoinPUStatus($relationAlias = null) Adds a INNER JOIN clause to the query using the PUStatus relation
 *
 * @method PUserQuery leftJoinPTag($relationAlias = null) Adds a LEFT JOIN clause to the query using the PTag relation
 * @method PUserQuery rightJoinPTag($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PTag relation
 * @method PUserQuery innerJoinPTag($relationAlias = null) Adds a INNER JOIN clause to the query using the PTag relation
 *
 * @method PUserQuery leftJoinPOrder($relationAlias = null) Adds a LEFT JOIN clause to the query using the POrder relation
 * @method PUserQuery rightJoinPOrder($relationAlias = null) Adds a RIGHT JOIN clause to the query using the POrder relation
 * @method PUserQuery innerJoinPOrder($relationAlias = null) Adds a INNER JOIN clause to the query using the POrder relation
 *
 * @method PUserQuery leftJoinPuFollowDdPUser($relationAlias = null) Adds a LEFT JOIN clause to the query using the PuFollowDdPUser relation
 * @method PUserQuery rightJoinPuFollowDdPUser($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PuFollowDdPUser relation
 * @method PUserQuery innerJoinPuFollowDdPUser($relationAlias = null) Adds a INNER JOIN clause to the query using the PuFollowDdPUser relation
 *
 * @method PUserQuery leftJoinPUBadge($relationAlias = null) Adds a LEFT JOIN clause to the query using the PUBadge relation
 * @method PUserQuery rightJoinPUBadge($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PUBadge relation
 * @method PUserQuery innerJoinPUBadge($relationAlias = null) Adds a INNER JOIN clause to the query using the PUBadge relation
 *
 * @method PUserQuery leftJoinPUReputation($relationAlias = null) Adds a LEFT JOIN clause to the query using the PUReputation relation
 * @method PUserQuery rightJoinPUReputation($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PUReputation relation
 * @method PUserQuery innerJoinPUReputation($relationAlias = null) Adds a INNER JOIN clause to the query using the PUReputation relation
 *
 * @method PUserQuery leftJoinPuTaggedTPUser($relationAlias = null) Adds a LEFT JOIN clause to the query using the PuTaggedTPUser relation
 * @method PUserQuery rightJoinPuTaggedTPUser($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PuTaggedTPUser relation
 * @method PUserQuery innerJoinPuTaggedTPUser($relationAlias = null) Adds a INNER JOIN clause to the query using the PuTaggedTPUser relation
 *
 * @method PUserQuery leftJoinPuFollowTPUser($relationAlias = null) Adds a LEFT JOIN clause to the query using the PuFollowTPUser relation
 * @method PUserQuery rightJoinPuFollowTPUser($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PuFollowTPUser relation
 * @method PUserQuery innerJoinPuFollowTPUser($relationAlias = null) Adds a INNER JOIN clause to the query using the PuFollowTPUser relation
 *
 * @method PUserQuery leftJoinPURoleQ($relationAlias = null) Adds a LEFT JOIN clause to the query using the PURoleQ relation
 * @method PUserQuery rightJoinPURoleQ($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PURoleQ relation
 * @method PUserQuery innerJoinPURoleQ($relationAlias = null) Adds a INNER JOIN clause to the query using the PURoleQ relation
 *
 * @method PUserQuery leftJoinPUMandate($relationAlias = null) Adds a LEFT JOIN clause to the query using the PUMandate relation
 * @method PUserQuery rightJoinPUMandate($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PUMandate relation
 * @method PUserQuery innerJoinPUMandate($relationAlias = null) Adds a INNER JOIN clause to the query using the PUMandate relation
 *
 * @method PUserQuery leftJoinPUAffinityQOPUser($relationAlias = null) Adds a LEFT JOIN clause to the query using the PUAffinityQOPUser relation
 * @method PUserQuery rightJoinPUAffinityQOPUser($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PUAffinityQOPUser relation
 * @method PUserQuery innerJoinPUAffinityQOPUser($relationAlias = null) Adds a INNER JOIN clause to the query using the PUAffinityQOPUser relation
 *
 * @method PUserQuery leftJoinPUCurrentQOPUser($relationAlias = null) Adds a LEFT JOIN clause to the query using the PUCurrentQOPUser relation
 * @method PUserQuery rightJoinPUCurrentQOPUser($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PUCurrentQOPUser relation
 * @method PUserQuery innerJoinPUCurrentQOPUser($relationAlias = null) Adds a INNER JOIN clause to the query using the PUCurrentQOPUser relation
 *
 * @method PUserQuery leftJoinPUNotificationPUser($relationAlias = null) Adds a LEFT JOIN clause to the query using the PUNotificationPUser relation
 * @method PUserQuery rightJoinPUNotificationPUser($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PUNotificationPUser relation
 * @method PUserQuery innerJoinPUNotificationPUser($relationAlias = null) Adds a INNER JOIN clause to the query using the PUNotificationPUser relation
 *
 * @method PUserQuery leftJoinPUSubscribeEmailPUser($relationAlias = null) Adds a LEFT JOIN clause to the query using the PUSubscribeEmailPUser relation
 * @method PUserQuery rightJoinPUSubscribeEmailPUser($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PUSubscribeEmailPUser relation
 * @method PUserQuery innerJoinPUSubscribeEmailPUser($relationAlias = null) Adds a INNER JOIN clause to the query using the PUSubscribeEmailPUser relation
 *
 * @method PUserQuery leftJoinPUSubscribeScreenPUser($relationAlias = null) Adds a LEFT JOIN clause to the query using the PUSubscribeScreenPUser relation
 * @method PUserQuery rightJoinPUSubscribeScreenPUser($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PUSubscribeScreenPUser relation
 * @method PUserQuery innerJoinPUSubscribeScreenPUser($relationAlias = null) Adds a INNER JOIN clause to the query using the PUSubscribeScreenPUser relation
 *
 * @method PUserQuery leftJoinPDDebate($relationAlias = null) Adds a LEFT JOIN clause to the query using the PDDebate relation
 * @method PUserQuery rightJoinPDDebate($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PDDebate relation
 * @method PUserQuery innerJoinPDDebate($relationAlias = null) Adds a INNER JOIN clause to the query using the PDDebate relation
 *
 * @method PUserQuery leftJoinPDReaction($relationAlias = null) Adds a LEFT JOIN clause to the query using the PDReaction relation
 * @method PUserQuery rightJoinPDReaction($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PDReaction relation
 * @method PUserQuery innerJoinPDReaction($relationAlias = null) Adds a INNER JOIN clause to the query using the PDReaction relation
 *
 * @method PUserQuery leftJoinPDDComment($relationAlias = null) Adds a LEFT JOIN clause to the query using the PDDComment relation
 * @method PUserQuery rightJoinPDDComment($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PDDComment relation
 * @method PUserQuery innerJoinPDDComment($relationAlias = null) Adds a INNER JOIN clause to the query using the PDDComment relation
 *
 * @method PUserQuery leftJoinPDRComment($relationAlias = null) Adds a LEFT JOIN clause to the query using the PDRComment relation
 * @method PUserQuery rightJoinPDRComment($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PDRComment relation
 * @method PUserQuery innerJoinPDRComment($relationAlias = null) Adds a INNER JOIN clause to the query using the PDRComment relation
 *
 * @method PUserQuery leftJoinPMUserModerated($relationAlias = null) Adds a LEFT JOIN clause to the query using the PMUserModerated relation
 * @method PUserQuery rightJoinPMUserModerated($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PMUserModerated relation
 * @method PUserQuery innerJoinPMUserModerated($relationAlias = null) Adds a INNER JOIN clause to the query using the PMUserModerated relation
 *
 * @method PUserQuery leftJoinPMUserMessage($relationAlias = null) Adds a LEFT JOIN clause to the query using the PMUserMessage relation
 * @method PUserQuery rightJoinPMUserMessage($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PMUserMessage relation
 * @method PUserQuery innerJoinPMUserMessage($relationAlias = null) Adds a INNER JOIN clause to the query using the PMUserMessage relation
 *
 * @method PUserQuery leftJoinPMAskForUpdate($relationAlias = null) Adds a LEFT JOIN clause to the query using the PMAskForUpdate relation
 * @method PUserQuery rightJoinPMAskForUpdate($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PMAskForUpdate relation
 * @method PUserQuery innerJoinPMAskForUpdate($relationAlias = null) Adds a INNER JOIN clause to the query using the PMAskForUpdate relation
 *
 * @method PUserQuery leftJoinPMAbuseReporting($relationAlias = null) Adds a LEFT JOIN clause to the query using the PMAbuseReporting relation
 * @method PUserQuery rightJoinPMAbuseReporting($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PMAbuseReporting relation
 * @method PUserQuery innerJoinPMAbuseReporting($relationAlias = null) Adds a INNER JOIN clause to the query using the PMAbuseReporting relation
 *
 * @method PUserQuery leftJoinPMAppException($relationAlias = null) Adds a LEFT JOIN clause to the query using the PMAppException relation
 * @method PUserQuery rightJoinPMAppException($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PMAppException relation
 * @method PUserQuery innerJoinPMAppException($relationAlias = null) Adds a INNER JOIN clause to the query using the PMAppException relation
 *
 * @method PUserQuery leftJoinPUFollowURelatedByPUserId($relationAlias = null) Adds a LEFT JOIN clause to the query using the PUFollowURelatedByPUserId relation
 * @method PUserQuery rightJoinPUFollowURelatedByPUserId($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PUFollowURelatedByPUserId relation
 * @method PUserQuery innerJoinPUFollowURelatedByPUserId($relationAlias = null) Adds a INNER JOIN clause to the query using the PUFollowURelatedByPUserId relation
 *
 * @method PUserQuery leftJoinPUFollowURelatedByPUserFollowerId($relationAlias = null) Adds a LEFT JOIN clause to the query using the PUFollowURelatedByPUserFollowerId relation
 * @method PUserQuery rightJoinPUFollowURelatedByPUserFollowerId($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PUFollowURelatedByPUserFollowerId relation
 * @method PUserQuery innerJoinPUFollowURelatedByPUserFollowerId($relationAlias = null) Adds a INNER JOIN clause to the query using the PUFollowURelatedByPUserFollowerId relation
 *
 * @method PUser findOne(PropelPDO $con = null) Return the first PUser matching the query
 * @method PUser findOneOrCreate(PropelPDO $con = null) Return the first PUser matching the query, or a new PUser object populated from the query conditions when no match is found
 *
 * @method PUser findOneByProvider(string $provider) Return the first PUser filtered by the provider column
 * @method PUser findOneByProviderId(string $provider_id) Return the first PUser filtered by the provider_id column
 * @method PUser findOneByNickname(string $nickname) Return the first PUser filtered by the nickname column
 * @method PUser findOneByRealname(string $realname) Return the first PUser filtered by the realname column
 * @method PUser findOneByUsername(string $username) Return the first PUser filtered by the username column
 * @method PUser findOneByUsernameCanonical(string $username_canonical) Return the first PUser filtered by the username_canonical column
 * @method PUser findOneByEmail(string $email) Return the first PUser filtered by the email column
 * @method PUser findOneByEmailCanonical(string $email_canonical) Return the first PUser filtered by the email_canonical column
 * @method PUser findOneByEnabled(boolean $enabled) Return the first PUser filtered by the enabled column
 * @method PUser findOneBySalt(string $salt) Return the first PUser filtered by the salt column
 * @method PUser findOneByPassword(string $password) Return the first PUser filtered by the password column
 * @method PUser findOneByLastLogin(string $last_login) Return the first PUser filtered by the last_login column
 * @method PUser findOneByLocked(boolean $locked) Return the first PUser filtered by the locked column
 * @method PUser findOneByExpired(boolean $expired) Return the first PUser filtered by the expired column
 * @method PUser findOneByExpiresAt(string $expires_at) Return the first PUser filtered by the expires_at column
 * @method PUser findOneByConfirmationToken(string $confirmation_token) Return the first PUser filtered by the confirmation_token column
 * @method PUser findOneByPasswordRequestedAt(string $password_requested_at) Return the first PUser filtered by the password_requested_at column
 * @method PUser findOneByCredentialsExpired(boolean $credentials_expired) Return the first PUser filtered by the credentials_expired column
 * @method PUser findOneByCredentialsExpireAt(string $credentials_expire_at) Return the first PUser filtered by the credentials_expire_at column
 * @method PUser findOneByRoles(array $roles) Return the first PUser filtered by the roles column
 * @method PUser findOneByLastActivity(string $last_activity) Return the first PUser filtered by the last_activity column
 * @method PUser findOneByPUStatusId(int $p_u_status_id) Return the first PUser filtered by the p_u_status_id column
 * @method PUser findOneByFileName(string $file_name) Return the first PUser filtered by the file_name column
 * @method PUser findOneByBackFileName(string $back_file_name) Return the first PUser filtered by the back_file_name column
 * @method PUser findOneByCopyright(string $copyright) Return the first PUser filtered by the copyright column
 * @method PUser findOneByGender(int $gender) Return the first PUser filtered by the gender column
 * @method PUser findOneByFirstname(string $firstname) Return the first PUser filtered by the firstname column
 * @method PUser findOneByName(string $name) Return the first PUser filtered by the name column
 * @method PUser findOneByBirthday(string $birthday) Return the first PUser filtered by the birthday column
 * @method PUser findOneBySubtitle(string $subtitle) Return the first PUser filtered by the subtitle column
 * @method PUser findOneByBiography(string $biography) Return the first PUser filtered by the biography column
 * @method PUser findOneByWebsite(string $website) Return the first PUser filtered by the website column
 * @method PUser findOneByTwitter(string $twitter) Return the first PUser filtered by the twitter column
 * @method PUser findOneByFacebook(string $facebook) Return the first PUser filtered by the facebook column
 * @method PUser findOneByPhone(string $phone) Return the first PUser filtered by the phone column
 * @method PUser findOneByNewsletter(boolean $newsletter) Return the first PUser filtered by the newsletter column
 * @method PUser findOneByLastConnect(string $last_connect) Return the first PUser filtered by the last_connect column
 * @method PUser findOneByNbConnectedDays(int $nb_connected_days) Return the first PUser filtered by the nb_connected_days column
 * @method PUser findOneByNbViews(int $nb_views) Return the first PUser filtered by the nb_views column
 * @method PUser findOneByQualified(boolean $qualified) Return the first PUser filtered by the qualified column
 * @method PUser findOneByValidated(boolean $validated) Return the first PUser filtered by the validated column
 * @method PUser findOneByOnline(boolean $online) Return the first PUser filtered by the online column
 * @method PUser findOneByBanned(boolean $banned) Return the first PUser filtered by the banned column
 * @method PUser findOneByBannedAt(string $banned_at) Return the first PUser filtered by the banned_at column
 * @method PUser findOneByAbuseLevel(int $abuse_level) Return the first PUser filtered by the abuse_level column
 * @method PUser findOneByCreatedAt(string $created_at) Return the first PUser filtered by the created_at column
 * @method PUser findOneByUpdatedAt(string $updated_at) Return the first PUser filtered by the updated_at column
 * @method PUser findOneBySlug(string $slug) Return the first PUser filtered by the slug column
 *
 * @method array findById(int $id) Return PUser objects filtered by the id column
 * @method array findByProvider(string $provider) Return PUser objects filtered by the provider column
 * @method array findByProviderId(string $provider_id) Return PUser objects filtered by the provider_id column
 * @method array findByNickname(string $nickname) Return PUser objects filtered by the nickname column
 * @method array findByRealname(string $realname) Return PUser objects filtered by the realname column
 * @method array findByUsername(string $username) Return PUser objects filtered by the username column
 * @method array findByUsernameCanonical(string $username_canonical) Return PUser objects filtered by the username_canonical column
 * @method array findByEmail(string $email) Return PUser objects filtered by the email column
 * @method array findByEmailCanonical(string $email_canonical) Return PUser objects filtered by the email_canonical column
 * @method array findByEnabled(boolean $enabled) Return PUser objects filtered by the enabled column
 * @method array findBySalt(string $salt) Return PUser objects filtered by the salt column
 * @method array findByPassword(string $password) Return PUser objects filtered by the password column
 * @method array findByLastLogin(string $last_login) Return PUser objects filtered by the last_login column
 * @method array findByLocked(boolean $locked) Return PUser objects filtered by the locked column
 * @method array findByExpired(boolean $expired) Return PUser objects filtered by the expired column
 * @method array findByExpiresAt(string $expires_at) Return PUser objects filtered by the expires_at column
 * @method array findByConfirmationToken(string $confirmation_token) Return PUser objects filtered by the confirmation_token column
 * @method array findByPasswordRequestedAt(string $password_requested_at) Return PUser objects filtered by the password_requested_at column
 * @method array findByCredentialsExpired(boolean $credentials_expired) Return PUser objects filtered by the credentials_expired column
 * @method array findByCredentialsExpireAt(string $credentials_expire_at) Return PUser objects filtered by the credentials_expire_at column
 * @method array findByRoles(array $roles) Return PUser objects filtered by the roles column
 * @method array findByLastActivity(string $last_activity) Return PUser objects filtered by the last_activity column
 * @method array findByPUStatusId(int $p_u_status_id) Return PUser objects filtered by the p_u_status_id column
 * @method array findByFileName(string $file_name) Return PUser objects filtered by the file_name column
 * @method array findByBackFileName(string $back_file_name) Return PUser objects filtered by the back_file_name column
 * @method array findByCopyright(string $copyright) Return PUser objects filtered by the copyright column
 * @method array findByGender(int $gender) Return PUser objects filtered by the gender column
 * @method array findByFirstname(string $firstname) Return PUser objects filtered by the firstname column
 * @method array findByName(string $name) Return PUser objects filtered by the name column
 * @method array findByBirthday(string $birthday) Return PUser objects filtered by the birthday column
 * @method array findBySubtitle(string $subtitle) Return PUser objects filtered by the subtitle column
 * @method array findByBiography(string $biography) Return PUser objects filtered by the biography column
 * @method array findByWebsite(string $website) Return PUser objects filtered by the website column
 * @method array findByTwitter(string $twitter) Return PUser objects filtered by the twitter column
 * @method array findByFacebook(string $facebook) Return PUser objects filtered by the facebook column
 * @method array findByPhone(string $phone) Return PUser objects filtered by the phone column
 * @method array findByNewsletter(boolean $newsletter) Return PUser objects filtered by the newsletter column
 * @method array findByLastConnect(string $last_connect) Return PUser objects filtered by the last_connect column
 * @method array findByNbConnectedDays(int $nb_connected_days) Return PUser objects filtered by the nb_connected_days column
 * @method array findByNbViews(int $nb_views) Return PUser objects filtered by the nb_views column
 * @method array findByQualified(boolean $qualified) Return PUser objects filtered by the qualified column
 * @method array findByValidated(boolean $validated) Return PUser objects filtered by the validated column
 * @method array findByOnline(boolean $online) Return PUser objects filtered by the online column
 * @method array findByBanned(boolean $banned) Return PUser objects filtered by the banned column
 * @method array findByBannedAt(string $banned_at) Return PUser objects filtered by the banned_at column
 * @method array findByAbuseLevel(int $abuse_level) Return PUser objects filtered by the abuse_level column
 * @method array findByCreatedAt(string $created_at) Return PUser objects filtered by the created_at column
 * @method array findByUpdatedAt(string $updated_at) Return PUser objects filtered by the updated_at column
 * @method array findBySlug(string $slug) Return PUser objects filtered by the slug column
 */
abstract class BasePUserQuery extends ModelCriteria
{
    // query_cache behavior
    protected $queryKey = '';

    // archivable behavior
    protected $archiveOnDelete = true;

    /**
     * Initializes internal state of BasePUserQuery object.
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
            $modelName = 'Politizr\\Model\\PUser';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
        EventDispatcherProxy::trigger(array('construct','query.construct'), new QueryEvent($this));
    }

    /**
     * Returns a new PUserQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   PUserQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return PUserQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof PUserQuery) {
            return $criteria;
        }
        $query = new static(null, null, $modelAlias);

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
     * @return   PUser|PUser[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = PUserPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(PUserPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 PUser A model object, or null if the key is not found
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
     * @return                 PUser A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `provider`, `provider_id`, `nickname`, `realname`, `username`, `username_canonical`, `email`, `email_canonical`, `enabled`, `salt`, `password`, `last_login`, `locked`, `expired`, `expires_at`, `confirmation_token`, `password_requested_at`, `credentials_expired`, `credentials_expire_at`, `roles`, `last_activity`, `p_u_status_id`, `file_name`, `back_file_name`, `copyright`, `gender`, `firstname`, `name`, `birthday`, `subtitle`, `biography`, `website`, `twitter`, `facebook`, `phone`, `newsletter`, `last_connect`, `nb_connected_days`, `nb_views`, `qualified`, `validated`, `online`, `banned`, `banned_at`, `abuse_level`, `created_at`, `updated_at`, `slug` FROM `p_user` WHERE `id` = :p0';
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
            $cls = PUserPeer::getOMClass();
            $obj = new $cls;
            $obj->hydrate($row);
            PUserPeer::addInstanceToPool($obj, (string) $key);
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
     * @return PUser|PUser[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|PUser[]|mixed the list of results, formatted by the current formatter
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
     * @return PUserQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(PUserPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return PUserQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(PUserPeer::ID, $keys, Criteria::IN);
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
     * @return PUserQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(PUserPeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(PUserPeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PUserPeer::ID, $id, $comparison);
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
     * @return PUserQuery The current query, for fluid interface
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

        return $this->addUsingAlias(PUserPeer::PROVIDER, $provider, $comparison);
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
     * @return PUserQuery The current query, for fluid interface
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

        return $this->addUsingAlias(PUserPeer::PROVIDER_ID, $providerId, $comparison);
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
     * @return PUserQuery The current query, for fluid interface
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

        return $this->addUsingAlias(PUserPeer::NICKNAME, $nickname, $comparison);
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
     * @return PUserQuery The current query, for fluid interface
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

        return $this->addUsingAlias(PUserPeer::REALNAME, $realname, $comparison);
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
     * @return PUserQuery The current query, for fluid interface
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

        return $this->addUsingAlias(PUserPeer::USERNAME, $username, $comparison);
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
     * @return PUserQuery The current query, for fluid interface
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

        return $this->addUsingAlias(PUserPeer::USERNAME_CANONICAL, $usernameCanonical, $comparison);
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
     * @return PUserQuery The current query, for fluid interface
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

        return $this->addUsingAlias(PUserPeer::EMAIL, $email, $comparison);
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
     * @return PUserQuery The current query, for fluid interface
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

        return $this->addUsingAlias(PUserPeer::EMAIL_CANONICAL, $emailCanonical, $comparison);
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
     * @return PUserQuery The current query, for fluid interface
     */
    public function filterByEnabled($enabled = null, $comparison = null)
    {
        if (is_string($enabled)) {
            $enabled = in_array(strtolower($enabled), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(PUserPeer::ENABLED, $enabled, $comparison);
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
     * @return PUserQuery The current query, for fluid interface
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

        return $this->addUsingAlias(PUserPeer::SALT, $salt, $comparison);
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
     * @return PUserQuery The current query, for fluid interface
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

        return $this->addUsingAlias(PUserPeer::PASSWORD, $password, $comparison);
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
     * @return PUserQuery The current query, for fluid interface
     */
    public function filterByLastLogin($lastLogin = null, $comparison = null)
    {
        if (is_array($lastLogin)) {
            $useMinMax = false;
            if (isset($lastLogin['min'])) {
                $this->addUsingAlias(PUserPeer::LAST_LOGIN, $lastLogin['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($lastLogin['max'])) {
                $this->addUsingAlias(PUserPeer::LAST_LOGIN, $lastLogin['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PUserPeer::LAST_LOGIN, $lastLogin, $comparison);
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
     * @return PUserQuery The current query, for fluid interface
     */
    public function filterByLocked($locked = null, $comparison = null)
    {
        if (is_string($locked)) {
            $locked = in_array(strtolower($locked), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(PUserPeer::LOCKED, $locked, $comparison);
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
     * @return PUserQuery The current query, for fluid interface
     */
    public function filterByExpired($expired = null, $comparison = null)
    {
        if (is_string($expired)) {
            $expired = in_array(strtolower($expired), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(PUserPeer::EXPIRED, $expired, $comparison);
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
     * @return PUserQuery The current query, for fluid interface
     */
    public function filterByExpiresAt($expiresAt = null, $comparison = null)
    {
        if (is_array($expiresAt)) {
            $useMinMax = false;
            if (isset($expiresAt['min'])) {
                $this->addUsingAlias(PUserPeer::EXPIRES_AT, $expiresAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($expiresAt['max'])) {
                $this->addUsingAlias(PUserPeer::EXPIRES_AT, $expiresAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PUserPeer::EXPIRES_AT, $expiresAt, $comparison);
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
     * @return PUserQuery The current query, for fluid interface
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

        return $this->addUsingAlias(PUserPeer::CONFIRMATION_TOKEN, $confirmationToken, $comparison);
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
     * @return PUserQuery The current query, for fluid interface
     */
    public function filterByPasswordRequestedAt($passwordRequestedAt = null, $comparison = null)
    {
        if (is_array($passwordRequestedAt)) {
            $useMinMax = false;
            if (isset($passwordRequestedAt['min'])) {
                $this->addUsingAlias(PUserPeer::PASSWORD_REQUESTED_AT, $passwordRequestedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($passwordRequestedAt['max'])) {
                $this->addUsingAlias(PUserPeer::PASSWORD_REQUESTED_AT, $passwordRequestedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PUserPeer::PASSWORD_REQUESTED_AT, $passwordRequestedAt, $comparison);
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
     * @return PUserQuery The current query, for fluid interface
     */
    public function filterByCredentialsExpired($credentialsExpired = null, $comparison = null)
    {
        if (is_string($credentialsExpired)) {
            $credentialsExpired = in_array(strtolower($credentialsExpired), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(PUserPeer::CREDENTIALS_EXPIRED, $credentialsExpired, $comparison);
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
     * @return PUserQuery The current query, for fluid interface
     */
    public function filterByCredentialsExpireAt($credentialsExpireAt = null, $comparison = null)
    {
        if (is_array($credentialsExpireAt)) {
            $useMinMax = false;
            if (isset($credentialsExpireAt['min'])) {
                $this->addUsingAlias(PUserPeer::CREDENTIALS_EXPIRE_AT, $credentialsExpireAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($credentialsExpireAt['max'])) {
                $this->addUsingAlias(PUserPeer::CREDENTIALS_EXPIRE_AT, $credentialsExpireAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PUserPeer::CREDENTIALS_EXPIRE_AT, $credentialsExpireAt, $comparison);
    }

    /**
     * Filter the query on the roles column
     *
     * @param     array $roles The values to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PUserQuery The current query, for fluid interface
     */
    public function filterByRoles($roles = null, $comparison = null)
    {
        $key = $this->getAliasedColName(PUserPeer::ROLES);
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

        return $this->addUsingAlias(PUserPeer::ROLES, $roles, $comparison);
    }

    /**
     * Filter the query on the roles column
     * @param     mixed $roles The value to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::CONTAINS_ALL
     *
     * @return PUserQuery The current query, for fluid interface
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
            $key = $this->getAliasedColName(PUserPeer::ROLES);
            if ($this->containsKey($key)) {
                $this->addAnd($key, $roles, $comparison);
            } else {
                $this->addAnd($key, $roles, $comparison);
            }
            $this->addOr($key, null, Criteria::ISNULL);

            return $this;
        }

        return $this->addUsingAlias(PUserPeer::ROLES, $roles, $comparison);
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
     * @return PUserQuery The current query, for fluid interface
     */
    public function filterByLastActivity($lastActivity = null, $comparison = null)
    {
        if (is_array($lastActivity)) {
            $useMinMax = false;
            if (isset($lastActivity['min'])) {
                $this->addUsingAlias(PUserPeer::LAST_ACTIVITY, $lastActivity['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($lastActivity['max'])) {
                $this->addUsingAlias(PUserPeer::LAST_ACTIVITY, $lastActivity['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PUserPeer::LAST_ACTIVITY, $lastActivity, $comparison);
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
     * @see       filterByPUStatus()
     *
     * @param     mixed $pUStatusId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PUserQuery The current query, for fluid interface
     */
    public function filterByPUStatusId($pUStatusId = null, $comparison = null)
    {
        if (is_array($pUStatusId)) {
            $useMinMax = false;
            if (isset($pUStatusId['min'])) {
                $this->addUsingAlias(PUserPeer::P_U_STATUS_ID, $pUStatusId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($pUStatusId['max'])) {
                $this->addUsingAlias(PUserPeer::P_U_STATUS_ID, $pUStatusId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PUserPeer::P_U_STATUS_ID, $pUStatusId, $comparison);
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
     * @return PUserQuery The current query, for fluid interface
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

        return $this->addUsingAlias(PUserPeer::FILE_NAME, $fileName, $comparison);
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
     * @return PUserQuery The current query, for fluid interface
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

        return $this->addUsingAlias(PUserPeer::BACK_FILE_NAME, $backFileName, $comparison);
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
     * @return PUserQuery The current query, for fluid interface
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

        return $this->addUsingAlias(PUserPeer::COPYRIGHT, $copyright, $comparison);
    }

    /**
     * Filter the query on the gender column
     *
     * @param     mixed $gender The value to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PUserQuery The current query, for fluid interface
     * @throws PropelException - if the value is not accepted by the enum.
     */
    public function filterByGender($gender = null, $comparison = null)
    {
        if (is_scalar($gender)) {
            $gender = PUserPeer::getSqlValueForEnum(PUserPeer::GENDER, $gender);
        } elseif (is_array($gender)) {
            $convertedValues = array();
            foreach ($gender as $value) {
                $convertedValues[] = PUserPeer::getSqlValueForEnum(PUserPeer::GENDER, $value);
            }
            $gender = $convertedValues;
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PUserPeer::GENDER, $gender, $comparison);
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
     * @return PUserQuery The current query, for fluid interface
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

        return $this->addUsingAlias(PUserPeer::FIRSTNAME, $firstname, $comparison);
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
     * @return PUserQuery The current query, for fluid interface
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

        return $this->addUsingAlias(PUserPeer::NAME, $name, $comparison);
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
     * @return PUserQuery The current query, for fluid interface
     */
    public function filterByBirthday($birthday = null, $comparison = null)
    {
        if (is_array($birthday)) {
            $useMinMax = false;
            if (isset($birthday['min'])) {
                $this->addUsingAlias(PUserPeer::BIRTHDAY, $birthday['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($birthday['max'])) {
                $this->addUsingAlias(PUserPeer::BIRTHDAY, $birthday['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PUserPeer::BIRTHDAY, $birthday, $comparison);
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
     * @return PUserQuery The current query, for fluid interface
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

        return $this->addUsingAlias(PUserPeer::SUBTITLE, $subtitle, $comparison);
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
     * @return PUserQuery The current query, for fluid interface
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

        return $this->addUsingAlias(PUserPeer::BIOGRAPHY, $biography, $comparison);
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
     * @return PUserQuery The current query, for fluid interface
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

        return $this->addUsingAlias(PUserPeer::WEBSITE, $website, $comparison);
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
     * @return PUserQuery The current query, for fluid interface
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

        return $this->addUsingAlias(PUserPeer::TWITTER, $twitter, $comparison);
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
     * @return PUserQuery The current query, for fluid interface
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

        return $this->addUsingAlias(PUserPeer::FACEBOOK, $facebook, $comparison);
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
     * @return PUserQuery The current query, for fluid interface
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

        return $this->addUsingAlias(PUserPeer::PHONE, $phone, $comparison);
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
     * @return PUserQuery The current query, for fluid interface
     */
    public function filterByNewsletter($newsletter = null, $comparison = null)
    {
        if (is_string($newsletter)) {
            $newsletter = in_array(strtolower($newsletter), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(PUserPeer::NEWSLETTER, $newsletter, $comparison);
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
     * @return PUserQuery The current query, for fluid interface
     */
    public function filterByLastConnect($lastConnect = null, $comparison = null)
    {
        if (is_array($lastConnect)) {
            $useMinMax = false;
            if (isset($lastConnect['min'])) {
                $this->addUsingAlias(PUserPeer::LAST_CONNECT, $lastConnect['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($lastConnect['max'])) {
                $this->addUsingAlias(PUserPeer::LAST_CONNECT, $lastConnect['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PUserPeer::LAST_CONNECT, $lastConnect, $comparison);
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
     * @return PUserQuery The current query, for fluid interface
     */
    public function filterByNbConnectedDays($nbConnectedDays = null, $comparison = null)
    {
        if (is_array($nbConnectedDays)) {
            $useMinMax = false;
            if (isset($nbConnectedDays['min'])) {
                $this->addUsingAlias(PUserPeer::NB_CONNECTED_DAYS, $nbConnectedDays['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($nbConnectedDays['max'])) {
                $this->addUsingAlias(PUserPeer::NB_CONNECTED_DAYS, $nbConnectedDays['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PUserPeer::NB_CONNECTED_DAYS, $nbConnectedDays, $comparison);
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
     * @return PUserQuery The current query, for fluid interface
     */
    public function filterByNbViews($nbViews = null, $comparison = null)
    {
        if (is_array($nbViews)) {
            $useMinMax = false;
            if (isset($nbViews['min'])) {
                $this->addUsingAlias(PUserPeer::NB_VIEWS, $nbViews['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($nbViews['max'])) {
                $this->addUsingAlias(PUserPeer::NB_VIEWS, $nbViews['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PUserPeer::NB_VIEWS, $nbViews, $comparison);
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
     * @return PUserQuery The current query, for fluid interface
     */
    public function filterByQualified($qualified = null, $comparison = null)
    {
        if (is_string($qualified)) {
            $qualified = in_array(strtolower($qualified), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(PUserPeer::QUALIFIED, $qualified, $comparison);
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
     * @return PUserQuery The current query, for fluid interface
     */
    public function filterByValidated($validated = null, $comparison = null)
    {
        if (is_string($validated)) {
            $validated = in_array(strtolower($validated), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(PUserPeer::VALIDATED, $validated, $comparison);
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
     * @return PUserQuery The current query, for fluid interface
     */
    public function filterByOnline($online = null, $comparison = null)
    {
        if (is_string($online)) {
            $online = in_array(strtolower($online), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(PUserPeer::ONLINE, $online, $comparison);
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
     * @return PUserQuery The current query, for fluid interface
     */
    public function filterByBanned($banned = null, $comparison = null)
    {
        if (is_string($banned)) {
            $banned = in_array(strtolower($banned), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(PUserPeer::BANNED, $banned, $comparison);
    }

    /**
     * Filter the query on the banned_at column
     *
     * Example usage:
     * <code>
     * $query->filterByBannedAt('2011-03-14'); // WHERE banned_at = '2011-03-14'
     * $query->filterByBannedAt('now'); // WHERE banned_at = '2011-03-14'
     * $query->filterByBannedAt(array('max' => 'yesterday')); // WHERE banned_at < '2011-03-13'
     * </code>
     *
     * @param     mixed $bannedAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PUserQuery The current query, for fluid interface
     */
    public function filterByBannedAt($bannedAt = null, $comparison = null)
    {
        if (is_array($bannedAt)) {
            $useMinMax = false;
            if (isset($bannedAt['min'])) {
                $this->addUsingAlias(PUserPeer::BANNED_AT, $bannedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($bannedAt['max'])) {
                $this->addUsingAlias(PUserPeer::BANNED_AT, $bannedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PUserPeer::BANNED_AT, $bannedAt, $comparison);
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
     * @return PUserQuery The current query, for fluid interface
     */
    public function filterByAbuseLevel($abuseLevel = null, $comparison = null)
    {
        if (is_array($abuseLevel)) {
            $useMinMax = false;
            if (isset($abuseLevel['min'])) {
                $this->addUsingAlias(PUserPeer::ABUSE_LEVEL, $abuseLevel['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($abuseLevel['max'])) {
                $this->addUsingAlias(PUserPeer::ABUSE_LEVEL, $abuseLevel['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PUserPeer::ABUSE_LEVEL, $abuseLevel, $comparison);
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
     * @return PUserQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(PUserPeer::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(PUserPeer::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PUserPeer::CREATED_AT, $createdAt, $comparison);
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
     * @return PUserQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(PUserPeer::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(PUserPeer::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PUserPeer::UPDATED_AT, $updatedAt, $comparison);
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
     * @return PUserQuery The current query, for fluid interface
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

        return $this->addUsingAlias(PUserPeer::SLUG, $slug, $comparison);
    }

    /**
     * Filter the query by a related PUStatus object
     *
     * @param   PUStatus|PropelObjectCollection $pUStatus The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PUserQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPUStatus($pUStatus, $comparison = null)
    {
        if ($pUStatus instanceof PUStatus) {
            return $this
                ->addUsingAlias(PUserPeer::P_U_STATUS_ID, $pUStatus->getId(), $comparison);
        } elseif ($pUStatus instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PUserPeer::P_U_STATUS_ID, $pUStatus->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByPUStatus() only accepts arguments of type PUStatus or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PUStatus relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PUserQuery The current query, for fluid interface
     */
    public function joinPUStatus($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PUStatus');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'PUStatus');
        }

        return $this;
    }

    /**
     * Use the PUStatus relation PUStatus object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Politizr\Model\PUStatusQuery A secondary query class using the current class as primary query
     */
    public function usePUStatusQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPUStatus($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PUStatus', '\Politizr\Model\PUStatusQuery');
    }

    /**
     * Filter the query by a related PTag object
     *
     * @param   PTag|PropelObjectCollection $pTag  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PUserQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPTag($pTag, $comparison = null)
    {
        if ($pTag instanceof PTag) {
            return $this
                ->addUsingAlias(PUserPeer::ID, $pTag->getPUserId(), $comparison);
        } elseif ($pTag instanceof PropelObjectCollection) {
            return $this
                ->usePTagQuery()
                ->filterByPrimaryKeys($pTag->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByPTag() only accepts arguments of type PTag or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PTag relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PUserQuery The current query, for fluid interface
     */
    public function joinPTag($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PTag');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'PTag');
        }

        return $this;
    }

    /**
     * Use the PTag relation PTag object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Politizr\Model\PTagQuery A secondary query class using the current class as primary query
     */
    public function usePTagQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinPTag($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PTag', '\Politizr\Model\PTagQuery');
    }

    /**
     * Filter the query by a related POrder object
     *
     * @param   POrder|PropelObjectCollection $pOrder  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PUserQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPOrder($pOrder, $comparison = null)
    {
        if ($pOrder instanceof POrder) {
            return $this
                ->addUsingAlias(PUserPeer::ID, $pOrder->getPUserId(), $comparison);
        } elseif ($pOrder instanceof PropelObjectCollection) {
            return $this
                ->usePOrderQuery()
                ->filterByPrimaryKeys($pOrder->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByPOrder() only accepts arguments of type POrder or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the POrder relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PUserQuery The current query, for fluid interface
     */
    public function joinPOrder($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('POrder');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'POrder');
        }

        return $this;
    }

    /**
     * Use the POrder relation POrder object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Politizr\Model\POrderQuery A secondary query class using the current class as primary query
     */
    public function usePOrderQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinPOrder($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'POrder', '\Politizr\Model\POrderQuery');
    }

    /**
     * Filter the query by a related PUFollowDD object
     *
     * @param   PUFollowDD|PropelObjectCollection $pUFollowDD  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PUserQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPuFollowDdPUser($pUFollowDD, $comparison = null)
    {
        if ($pUFollowDD instanceof PUFollowDD) {
            return $this
                ->addUsingAlias(PUserPeer::ID, $pUFollowDD->getPUserId(), $comparison);
        } elseif ($pUFollowDD instanceof PropelObjectCollection) {
            return $this
                ->usePuFollowDdPUserQuery()
                ->filterByPrimaryKeys($pUFollowDD->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByPuFollowDdPUser() only accepts arguments of type PUFollowDD or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PuFollowDdPUser relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PUserQuery The current query, for fluid interface
     */
    public function joinPuFollowDdPUser($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PuFollowDdPUser');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'PuFollowDdPUser');
        }

        return $this;
    }

    /**
     * Use the PuFollowDdPUser relation PUFollowDD object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Politizr\Model\PUFollowDDQuery A secondary query class using the current class as primary query
     */
    public function usePuFollowDdPUserQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPuFollowDdPUser($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PuFollowDdPUser', '\Politizr\Model\PUFollowDDQuery');
    }

    /**
     * Filter the query by a related PUBadge object
     *
     * @param   PUBadge|PropelObjectCollection $pUBadge  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PUserQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPUBadge($pUBadge, $comparison = null)
    {
        if ($pUBadge instanceof PUBadge) {
            return $this
                ->addUsingAlias(PUserPeer::ID, $pUBadge->getPUserId(), $comparison);
        } elseif ($pUBadge instanceof PropelObjectCollection) {
            return $this
                ->usePUBadgeQuery()
                ->filterByPrimaryKeys($pUBadge->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByPUBadge() only accepts arguments of type PUBadge or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PUBadge relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PUserQuery The current query, for fluid interface
     */
    public function joinPUBadge($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PUBadge');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'PUBadge');
        }

        return $this;
    }

    /**
     * Use the PUBadge relation PUBadge object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Politizr\Model\PUBadgeQuery A secondary query class using the current class as primary query
     */
    public function usePUBadgeQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPUBadge($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PUBadge', '\Politizr\Model\PUBadgeQuery');
    }

    /**
     * Filter the query by a related PUReputation object
     *
     * @param   PUReputation|PropelObjectCollection $pUReputation  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PUserQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPUReputation($pUReputation, $comparison = null)
    {
        if ($pUReputation instanceof PUReputation) {
            return $this
                ->addUsingAlias(PUserPeer::ID, $pUReputation->getPUserId(), $comparison);
        } elseif ($pUReputation instanceof PropelObjectCollection) {
            return $this
                ->usePUReputationQuery()
                ->filterByPrimaryKeys($pUReputation->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByPUReputation() only accepts arguments of type PUReputation or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PUReputation relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PUserQuery The current query, for fluid interface
     */
    public function joinPUReputation($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PUReputation');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'PUReputation');
        }

        return $this;
    }

    /**
     * Use the PUReputation relation PUReputation object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Politizr\Model\PUReputationQuery A secondary query class using the current class as primary query
     */
    public function usePUReputationQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPUReputation($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PUReputation', '\Politizr\Model\PUReputationQuery');
    }

    /**
     * Filter the query by a related PUTaggedT object
     *
     * @param   PUTaggedT|PropelObjectCollection $pUTaggedT  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PUserQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPuTaggedTPUser($pUTaggedT, $comparison = null)
    {
        if ($pUTaggedT instanceof PUTaggedT) {
            return $this
                ->addUsingAlias(PUserPeer::ID, $pUTaggedT->getPUserId(), $comparison);
        } elseif ($pUTaggedT instanceof PropelObjectCollection) {
            return $this
                ->usePuTaggedTPUserQuery()
                ->filterByPrimaryKeys($pUTaggedT->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByPuTaggedTPUser() only accepts arguments of type PUTaggedT or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PuTaggedTPUser relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PUserQuery The current query, for fluid interface
     */
    public function joinPuTaggedTPUser($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PuTaggedTPUser');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'PuTaggedTPUser');
        }

        return $this;
    }

    /**
     * Use the PuTaggedTPUser relation PUTaggedT object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Politizr\Model\PUTaggedTQuery A secondary query class using the current class as primary query
     */
    public function usePuTaggedTPUserQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPuTaggedTPUser($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PuTaggedTPUser', '\Politizr\Model\PUTaggedTQuery');
    }

    /**
     * Filter the query by a related PUFollowT object
     *
     * @param   PUFollowT|PropelObjectCollection $pUFollowT  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PUserQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPuFollowTPUser($pUFollowT, $comparison = null)
    {
        if ($pUFollowT instanceof PUFollowT) {
            return $this
                ->addUsingAlias(PUserPeer::ID, $pUFollowT->getPUserId(), $comparison);
        } elseif ($pUFollowT instanceof PropelObjectCollection) {
            return $this
                ->usePuFollowTPUserQuery()
                ->filterByPrimaryKeys($pUFollowT->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByPuFollowTPUser() only accepts arguments of type PUFollowT or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PuFollowTPUser relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PUserQuery The current query, for fluid interface
     */
    public function joinPuFollowTPUser($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PuFollowTPUser');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'PuFollowTPUser');
        }

        return $this;
    }

    /**
     * Use the PuFollowTPUser relation PUFollowT object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Politizr\Model\PUFollowTQuery A secondary query class using the current class as primary query
     */
    public function usePuFollowTPUserQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPuFollowTPUser($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PuFollowTPUser', '\Politizr\Model\PUFollowTQuery');
    }

    /**
     * Filter the query by a related PURoleQ object
     *
     * @param   PURoleQ|PropelObjectCollection $pURoleQ  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PUserQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPURoleQ($pURoleQ, $comparison = null)
    {
        if ($pURoleQ instanceof PURoleQ) {
            return $this
                ->addUsingAlias(PUserPeer::ID, $pURoleQ->getPUserId(), $comparison);
        } elseif ($pURoleQ instanceof PropelObjectCollection) {
            return $this
                ->usePURoleQQuery()
                ->filterByPrimaryKeys($pURoleQ->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByPURoleQ() only accepts arguments of type PURoleQ or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PURoleQ relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PUserQuery The current query, for fluid interface
     */
    public function joinPURoleQ($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PURoleQ');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'PURoleQ');
        }

        return $this;
    }

    /**
     * Use the PURoleQ relation PURoleQ object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Politizr\Model\PURoleQQuery A secondary query class using the current class as primary query
     */
    public function usePURoleQQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPURoleQ($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PURoleQ', '\Politizr\Model\PURoleQQuery');
    }

    /**
     * Filter the query by a related PUMandate object
     *
     * @param   PUMandate|PropelObjectCollection $pUMandate  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PUserQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPUMandate($pUMandate, $comparison = null)
    {
        if ($pUMandate instanceof PUMandate) {
            return $this
                ->addUsingAlias(PUserPeer::ID, $pUMandate->getPUserId(), $comparison);
        } elseif ($pUMandate instanceof PropelObjectCollection) {
            return $this
                ->usePUMandateQuery()
                ->filterByPrimaryKeys($pUMandate->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByPUMandate() only accepts arguments of type PUMandate or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PUMandate relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PUserQuery The current query, for fluid interface
     */
    public function joinPUMandate($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PUMandate');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'PUMandate');
        }

        return $this;
    }

    /**
     * Use the PUMandate relation PUMandate object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Politizr\Model\PUMandateQuery A secondary query class using the current class as primary query
     */
    public function usePUMandateQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPUMandate($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PUMandate', '\Politizr\Model\PUMandateQuery');
    }

    /**
     * Filter the query by a related PUAffinityQO object
     *
     * @param   PUAffinityQO|PropelObjectCollection $pUAffinityQO  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PUserQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPUAffinityQOPUser($pUAffinityQO, $comparison = null)
    {
        if ($pUAffinityQO instanceof PUAffinityQO) {
            return $this
                ->addUsingAlias(PUserPeer::ID, $pUAffinityQO->getPUserId(), $comparison);
        } elseif ($pUAffinityQO instanceof PropelObjectCollection) {
            return $this
                ->usePUAffinityQOPUserQuery()
                ->filterByPrimaryKeys($pUAffinityQO->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByPUAffinityQOPUser() only accepts arguments of type PUAffinityQO or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PUAffinityQOPUser relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PUserQuery The current query, for fluid interface
     */
    public function joinPUAffinityQOPUser($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PUAffinityQOPUser');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'PUAffinityQOPUser');
        }

        return $this;
    }

    /**
     * Use the PUAffinityQOPUser relation PUAffinityQO object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Politizr\Model\PUAffinityQOQuery A secondary query class using the current class as primary query
     */
    public function usePUAffinityQOPUserQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPUAffinityQOPUser($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PUAffinityQOPUser', '\Politizr\Model\PUAffinityQOQuery');
    }

    /**
     * Filter the query by a related PUCurrentQO object
     *
     * @param   PUCurrentQO|PropelObjectCollection $pUCurrentQO  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PUserQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPUCurrentQOPUser($pUCurrentQO, $comparison = null)
    {
        if ($pUCurrentQO instanceof PUCurrentQO) {
            return $this
                ->addUsingAlias(PUserPeer::ID, $pUCurrentQO->getPUserId(), $comparison);
        } elseif ($pUCurrentQO instanceof PropelObjectCollection) {
            return $this
                ->usePUCurrentQOPUserQuery()
                ->filterByPrimaryKeys($pUCurrentQO->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByPUCurrentQOPUser() only accepts arguments of type PUCurrentQO or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PUCurrentQOPUser relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PUserQuery The current query, for fluid interface
     */
    public function joinPUCurrentQOPUser($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PUCurrentQOPUser');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'PUCurrentQOPUser');
        }

        return $this;
    }

    /**
     * Use the PUCurrentQOPUser relation PUCurrentQO object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Politizr\Model\PUCurrentQOQuery A secondary query class using the current class as primary query
     */
    public function usePUCurrentQOPUserQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPUCurrentQOPUser($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PUCurrentQOPUser', '\Politizr\Model\PUCurrentQOQuery');
    }

    /**
     * Filter the query by a related PUNotification object
     *
     * @param   PUNotification|PropelObjectCollection $pUNotification  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PUserQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPUNotificationPUser($pUNotification, $comparison = null)
    {
        if ($pUNotification instanceof PUNotification) {
            return $this
                ->addUsingAlias(PUserPeer::ID, $pUNotification->getPUserId(), $comparison);
        } elseif ($pUNotification instanceof PropelObjectCollection) {
            return $this
                ->usePUNotificationPUserQuery()
                ->filterByPrimaryKeys($pUNotification->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByPUNotificationPUser() only accepts arguments of type PUNotification or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PUNotificationPUser relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PUserQuery The current query, for fluid interface
     */
    public function joinPUNotificationPUser($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PUNotificationPUser');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'PUNotificationPUser');
        }

        return $this;
    }

    /**
     * Use the PUNotificationPUser relation PUNotification object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Politizr\Model\PUNotificationQuery A secondary query class using the current class as primary query
     */
    public function usePUNotificationPUserQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPUNotificationPUser($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PUNotificationPUser', '\Politizr\Model\PUNotificationQuery');
    }

    /**
     * Filter the query by a related PUSubscribeEmail object
     *
     * @param   PUSubscribeEmail|PropelObjectCollection $pUSubscribeEmail  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PUserQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPUSubscribeEmailPUser($pUSubscribeEmail, $comparison = null)
    {
        if ($pUSubscribeEmail instanceof PUSubscribeEmail) {
            return $this
                ->addUsingAlias(PUserPeer::ID, $pUSubscribeEmail->getPUserId(), $comparison);
        } elseif ($pUSubscribeEmail instanceof PropelObjectCollection) {
            return $this
                ->usePUSubscribeEmailPUserQuery()
                ->filterByPrimaryKeys($pUSubscribeEmail->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByPUSubscribeEmailPUser() only accepts arguments of type PUSubscribeEmail or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PUSubscribeEmailPUser relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PUserQuery The current query, for fluid interface
     */
    public function joinPUSubscribeEmailPUser($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PUSubscribeEmailPUser');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'PUSubscribeEmailPUser');
        }

        return $this;
    }

    /**
     * Use the PUSubscribeEmailPUser relation PUSubscribeEmail object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Politizr\Model\PUSubscribeEmailQuery A secondary query class using the current class as primary query
     */
    public function usePUSubscribeEmailPUserQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPUSubscribeEmailPUser($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PUSubscribeEmailPUser', '\Politizr\Model\PUSubscribeEmailQuery');
    }

    /**
     * Filter the query by a related PUSubscribeScreen object
     *
     * @param   PUSubscribeScreen|PropelObjectCollection $pUSubscribeScreen  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PUserQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPUSubscribeScreenPUser($pUSubscribeScreen, $comparison = null)
    {
        if ($pUSubscribeScreen instanceof PUSubscribeScreen) {
            return $this
                ->addUsingAlias(PUserPeer::ID, $pUSubscribeScreen->getPUserId(), $comparison);
        } elseif ($pUSubscribeScreen instanceof PropelObjectCollection) {
            return $this
                ->usePUSubscribeScreenPUserQuery()
                ->filterByPrimaryKeys($pUSubscribeScreen->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByPUSubscribeScreenPUser() only accepts arguments of type PUSubscribeScreen or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PUSubscribeScreenPUser relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PUserQuery The current query, for fluid interface
     */
    public function joinPUSubscribeScreenPUser($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PUSubscribeScreenPUser');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'PUSubscribeScreenPUser');
        }

        return $this;
    }

    /**
     * Use the PUSubscribeScreenPUser relation PUSubscribeScreen object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Politizr\Model\PUSubscribeScreenQuery A secondary query class using the current class as primary query
     */
    public function usePUSubscribeScreenPUserQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPUSubscribeScreenPUser($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PUSubscribeScreenPUser', '\Politizr\Model\PUSubscribeScreenQuery');
    }

    /**
     * Filter the query by a related PDDebate object
     *
     * @param   PDDebate|PropelObjectCollection $pDDebate  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PUserQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPDDebate($pDDebate, $comparison = null)
    {
        if ($pDDebate instanceof PDDebate) {
            return $this
                ->addUsingAlias(PUserPeer::ID, $pDDebate->getPUserId(), $comparison);
        } elseif ($pDDebate instanceof PropelObjectCollection) {
            return $this
                ->usePDDebateQuery()
                ->filterByPrimaryKeys($pDDebate->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByPDDebate() only accepts arguments of type PDDebate or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PDDebate relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PUserQuery The current query, for fluid interface
     */
    public function joinPDDebate($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PDDebate');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'PDDebate');
        }

        return $this;
    }

    /**
     * Use the PDDebate relation PDDebate object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Politizr\Model\PDDebateQuery A secondary query class using the current class as primary query
     */
    public function usePDDebateQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinPDDebate($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PDDebate', '\Politizr\Model\PDDebateQuery');
    }

    /**
     * Filter the query by a related PDReaction object
     *
     * @param   PDReaction|PropelObjectCollection $pDReaction  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PUserQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPDReaction($pDReaction, $comparison = null)
    {
        if ($pDReaction instanceof PDReaction) {
            return $this
                ->addUsingAlias(PUserPeer::ID, $pDReaction->getPUserId(), $comparison);
        } elseif ($pDReaction instanceof PropelObjectCollection) {
            return $this
                ->usePDReactionQuery()
                ->filterByPrimaryKeys($pDReaction->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByPDReaction() only accepts arguments of type PDReaction or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PDReaction relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PUserQuery The current query, for fluid interface
     */
    public function joinPDReaction($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PDReaction');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'PDReaction');
        }

        return $this;
    }

    /**
     * Use the PDReaction relation PDReaction object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Politizr\Model\PDReactionQuery A secondary query class using the current class as primary query
     */
    public function usePDReactionQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinPDReaction($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PDReaction', '\Politizr\Model\PDReactionQuery');
    }

    /**
     * Filter the query by a related PDDComment object
     *
     * @param   PDDComment|PropelObjectCollection $pDDComment  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PUserQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPDDComment($pDDComment, $comparison = null)
    {
        if ($pDDComment instanceof PDDComment) {
            return $this
                ->addUsingAlias(PUserPeer::ID, $pDDComment->getPUserId(), $comparison);
        } elseif ($pDDComment instanceof PropelObjectCollection) {
            return $this
                ->usePDDCommentQuery()
                ->filterByPrimaryKeys($pDDComment->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByPDDComment() only accepts arguments of type PDDComment or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PDDComment relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PUserQuery The current query, for fluid interface
     */
    public function joinPDDComment($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PDDComment');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'PDDComment');
        }

        return $this;
    }

    /**
     * Use the PDDComment relation PDDComment object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Politizr\Model\PDDCommentQuery A secondary query class using the current class as primary query
     */
    public function usePDDCommentQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinPDDComment($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PDDComment', '\Politizr\Model\PDDCommentQuery');
    }

    /**
     * Filter the query by a related PDRComment object
     *
     * @param   PDRComment|PropelObjectCollection $pDRComment  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PUserQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPDRComment($pDRComment, $comparison = null)
    {
        if ($pDRComment instanceof PDRComment) {
            return $this
                ->addUsingAlias(PUserPeer::ID, $pDRComment->getPUserId(), $comparison);
        } elseif ($pDRComment instanceof PropelObjectCollection) {
            return $this
                ->usePDRCommentQuery()
                ->filterByPrimaryKeys($pDRComment->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByPDRComment() only accepts arguments of type PDRComment or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PDRComment relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PUserQuery The current query, for fluid interface
     */
    public function joinPDRComment($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PDRComment');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'PDRComment');
        }

        return $this;
    }

    /**
     * Use the PDRComment relation PDRComment object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Politizr\Model\PDRCommentQuery A secondary query class using the current class as primary query
     */
    public function usePDRCommentQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinPDRComment($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PDRComment', '\Politizr\Model\PDRCommentQuery');
    }

    /**
     * Filter the query by a related PMUserModerated object
     *
     * @param   PMUserModerated|PropelObjectCollection $pMUserModerated  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PUserQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPMUserModerated($pMUserModerated, $comparison = null)
    {
        if ($pMUserModerated instanceof PMUserModerated) {
            return $this
                ->addUsingAlias(PUserPeer::ID, $pMUserModerated->getPUserId(), $comparison);
        } elseif ($pMUserModerated instanceof PropelObjectCollection) {
            return $this
                ->usePMUserModeratedQuery()
                ->filterByPrimaryKeys($pMUserModerated->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByPMUserModerated() only accepts arguments of type PMUserModerated or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PMUserModerated relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PUserQuery The current query, for fluid interface
     */
    public function joinPMUserModerated($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PMUserModerated');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'PMUserModerated');
        }

        return $this;
    }

    /**
     * Use the PMUserModerated relation PMUserModerated object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Politizr\Model\PMUserModeratedQuery A secondary query class using the current class as primary query
     */
    public function usePMUserModeratedQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPMUserModerated($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PMUserModerated', '\Politizr\Model\PMUserModeratedQuery');
    }

    /**
     * Filter the query by a related PMUserMessage object
     *
     * @param   PMUserMessage|PropelObjectCollection $pMUserMessage  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PUserQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPMUserMessage($pMUserMessage, $comparison = null)
    {
        if ($pMUserMessage instanceof PMUserMessage) {
            return $this
                ->addUsingAlias(PUserPeer::ID, $pMUserMessage->getPUserId(), $comparison);
        } elseif ($pMUserMessage instanceof PropelObjectCollection) {
            return $this
                ->usePMUserMessageQuery()
                ->filterByPrimaryKeys($pMUserMessage->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByPMUserMessage() only accepts arguments of type PMUserMessage or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PMUserMessage relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PUserQuery The current query, for fluid interface
     */
    public function joinPMUserMessage($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PMUserMessage');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'PMUserMessage');
        }

        return $this;
    }

    /**
     * Use the PMUserMessage relation PMUserMessage object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Politizr\Model\PMUserMessageQuery A secondary query class using the current class as primary query
     */
    public function usePMUserMessageQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinPMUserMessage($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PMUserMessage', '\Politizr\Model\PMUserMessageQuery');
    }

    /**
     * Filter the query by a related PMAskForUpdate object
     *
     * @param   PMAskForUpdate|PropelObjectCollection $pMAskForUpdate  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PUserQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPMAskForUpdate($pMAskForUpdate, $comparison = null)
    {
        if ($pMAskForUpdate instanceof PMAskForUpdate) {
            return $this
                ->addUsingAlias(PUserPeer::ID, $pMAskForUpdate->getPUserId(), $comparison);
        } elseif ($pMAskForUpdate instanceof PropelObjectCollection) {
            return $this
                ->usePMAskForUpdateQuery()
                ->filterByPrimaryKeys($pMAskForUpdate->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByPMAskForUpdate() only accepts arguments of type PMAskForUpdate or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PMAskForUpdate relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PUserQuery The current query, for fluid interface
     */
    public function joinPMAskForUpdate($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PMAskForUpdate');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'PMAskForUpdate');
        }

        return $this;
    }

    /**
     * Use the PMAskForUpdate relation PMAskForUpdate object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Politizr\Model\PMAskForUpdateQuery A secondary query class using the current class as primary query
     */
    public function usePMAskForUpdateQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinPMAskForUpdate($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PMAskForUpdate', '\Politizr\Model\PMAskForUpdateQuery');
    }

    /**
     * Filter the query by a related PMAbuseReporting object
     *
     * @param   PMAbuseReporting|PropelObjectCollection $pMAbuseReporting  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PUserQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPMAbuseReporting($pMAbuseReporting, $comparison = null)
    {
        if ($pMAbuseReporting instanceof PMAbuseReporting) {
            return $this
                ->addUsingAlias(PUserPeer::ID, $pMAbuseReporting->getPUserId(), $comparison);
        } elseif ($pMAbuseReporting instanceof PropelObjectCollection) {
            return $this
                ->usePMAbuseReportingQuery()
                ->filterByPrimaryKeys($pMAbuseReporting->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByPMAbuseReporting() only accepts arguments of type PMAbuseReporting or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PMAbuseReporting relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PUserQuery The current query, for fluid interface
     */
    public function joinPMAbuseReporting($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PMAbuseReporting');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'PMAbuseReporting');
        }

        return $this;
    }

    /**
     * Use the PMAbuseReporting relation PMAbuseReporting object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Politizr\Model\PMAbuseReportingQuery A secondary query class using the current class as primary query
     */
    public function usePMAbuseReportingQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinPMAbuseReporting($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PMAbuseReporting', '\Politizr\Model\PMAbuseReportingQuery');
    }

    /**
     * Filter the query by a related PMAppException object
     *
     * @param   PMAppException|PropelObjectCollection $pMAppException  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PUserQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPMAppException($pMAppException, $comparison = null)
    {
        if ($pMAppException instanceof PMAppException) {
            return $this
                ->addUsingAlias(PUserPeer::ID, $pMAppException->getPUserId(), $comparison);
        } elseif ($pMAppException instanceof PropelObjectCollection) {
            return $this
                ->usePMAppExceptionQuery()
                ->filterByPrimaryKeys($pMAppException->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByPMAppException() only accepts arguments of type PMAppException or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PMAppException relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PUserQuery The current query, for fluid interface
     */
    public function joinPMAppException($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PMAppException');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'PMAppException');
        }

        return $this;
    }

    /**
     * Use the PMAppException relation PMAppException object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Politizr\Model\PMAppExceptionQuery A secondary query class using the current class as primary query
     */
    public function usePMAppExceptionQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinPMAppException($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PMAppException', '\Politizr\Model\PMAppExceptionQuery');
    }

    /**
     * Filter the query by a related PUFollowU object
     *
     * @param   PUFollowU|PropelObjectCollection $pUFollowU  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PUserQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPUFollowURelatedByPUserId($pUFollowU, $comparison = null)
    {
        if ($pUFollowU instanceof PUFollowU) {
            return $this
                ->addUsingAlias(PUserPeer::ID, $pUFollowU->getPUserId(), $comparison);
        } elseif ($pUFollowU instanceof PropelObjectCollection) {
            return $this
                ->usePUFollowURelatedByPUserIdQuery()
                ->filterByPrimaryKeys($pUFollowU->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByPUFollowURelatedByPUserId() only accepts arguments of type PUFollowU or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PUFollowURelatedByPUserId relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PUserQuery The current query, for fluid interface
     */
    public function joinPUFollowURelatedByPUserId($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PUFollowURelatedByPUserId');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'PUFollowURelatedByPUserId');
        }

        return $this;
    }

    /**
     * Use the PUFollowURelatedByPUserId relation PUFollowU object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Politizr\Model\PUFollowUQuery A secondary query class using the current class as primary query
     */
    public function usePUFollowURelatedByPUserIdQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPUFollowURelatedByPUserId($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PUFollowURelatedByPUserId', '\Politizr\Model\PUFollowUQuery');
    }

    /**
     * Filter the query by a related PUFollowU object
     *
     * @param   PUFollowU|PropelObjectCollection $pUFollowU  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PUserQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPUFollowURelatedByPUserFollowerId($pUFollowU, $comparison = null)
    {
        if ($pUFollowU instanceof PUFollowU) {
            return $this
                ->addUsingAlias(PUserPeer::ID, $pUFollowU->getPUserFollowerId(), $comparison);
        } elseif ($pUFollowU instanceof PropelObjectCollection) {
            return $this
                ->usePUFollowURelatedByPUserFollowerIdQuery()
                ->filterByPrimaryKeys($pUFollowU->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByPUFollowURelatedByPUserFollowerId() only accepts arguments of type PUFollowU or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PUFollowURelatedByPUserFollowerId relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PUserQuery The current query, for fluid interface
     */
    public function joinPUFollowURelatedByPUserFollowerId($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PUFollowURelatedByPUserFollowerId');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'PUFollowURelatedByPUserFollowerId');
        }

        return $this;
    }

    /**
     * Use the PUFollowURelatedByPUserFollowerId relation PUFollowU object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Politizr\Model\PUFollowUQuery A secondary query class using the current class as primary query
     */
    public function usePUFollowURelatedByPUserFollowerIdQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPUFollowURelatedByPUserFollowerId($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PUFollowURelatedByPUserFollowerId', '\Politizr\Model\PUFollowUQuery');
    }

    /**
     * Filter the query by a related PDDebate object
     * using the p_u_follow_d_d table as cross reference
     *
     * @param   PDDebate $pDDebate the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   PUserQuery The current query, for fluid interface
     */
    public function filterByPuFollowDdPDDebate($pDDebate, $comparison = Criteria::EQUAL)
    {
        return $this
            ->usePuFollowDdPUserQuery()
            ->filterByPuFollowDdPDDebate($pDDebate, $comparison)
            ->endUse();
    }

    /**
     * Filter the query by a related PRBadge object
     * using the p_u_badge table as cross reference
     *
     * @param   PRBadge $pRBadge the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   PUserQuery The current query, for fluid interface
     */
    public function filterByPRBadge($pRBadge, $comparison = Criteria::EQUAL)
    {
        return $this
            ->usePUBadgeQuery()
            ->filterByPRBadge($pRBadge, $comparison)
            ->endUse();
    }

    /**
     * Filter the query by a related PRAction object
     * using the p_u_reputation table as cross reference
     *
     * @param   PRAction $pRAction the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   PUserQuery The current query, for fluid interface
     */
    public function filterByPRAction($pRAction, $comparison = Criteria::EQUAL)
    {
        return $this
            ->usePUReputationQuery()
            ->filterByPRAction($pRAction, $comparison)
            ->endUse();
    }

    /**
     * Filter the query by a related PTag object
     * using the p_u_tagged_t table as cross reference
     *
     * @param   PTag $pTag the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   PUserQuery The current query, for fluid interface
     */
    public function filterByPuTaggedTPTag($pTag, $comparison = Criteria::EQUAL)
    {
        return $this
            ->usePuTaggedTPUserQuery()
            ->filterByPuTaggedTPTag($pTag, $comparison)
            ->endUse();
    }

    /**
     * Filter the query by a related PTag object
     * using the p_u_follow_t table as cross reference
     *
     * @param   PTag $pTag the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   PUserQuery The current query, for fluid interface
     */
    public function filterByPuFollowTPTag($pTag, $comparison = Criteria::EQUAL)
    {
        return $this
            ->usePuFollowTPUserQuery()
            ->filterByPuFollowTPTag($pTag, $comparison)
            ->endUse();
    }

    /**
     * Filter the query by a related PQualification object
     * using the p_u_role_q table as cross reference
     *
     * @param   PQualification $pQualification the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   PUserQuery The current query, for fluid interface
     */
    public function filterByPQualification($pQualification, $comparison = Criteria::EQUAL)
    {
        return $this
            ->usePURoleQQuery()
            ->filterByPQualification($pQualification, $comparison)
            ->endUse();
    }

    /**
     * Filter the query by a related PQOrganization object
     * using the p_u_affinity_q_o table as cross reference
     *
     * @param   PQOrganization $pQOrganization the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   PUserQuery The current query, for fluid interface
     */
    public function filterByPUAffinityQOPQOrganization($pQOrganization, $comparison = Criteria::EQUAL)
    {
        return $this
            ->usePUAffinityQOPUserQuery()
            ->filterByPUAffinityQOPQOrganization($pQOrganization, $comparison)
            ->endUse();
    }

    /**
     * Filter the query by a related PQOrganization object
     * using the p_u_current_q_o table as cross reference
     *
     * @param   PQOrganization $pQOrganization the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   PUserQuery The current query, for fluid interface
     */
    public function filterByPUCurrentQOPQOrganization($pQOrganization, $comparison = Criteria::EQUAL)
    {
        return $this
            ->usePUCurrentQOPUserQuery()
            ->filterByPUCurrentQOPQOrganization($pQOrganization, $comparison)
            ->endUse();
    }

    /**
     * Filter the query by a related PNotification object
     * using the p_u_notification table as cross reference
     *
     * @param   PNotification $pNotification the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   PUserQuery The current query, for fluid interface
     */
    public function filterByPUNotificationPNotification($pNotification, $comparison = Criteria::EQUAL)
    {
        return $this
            ->usePUNotificationPUserQuery()
            ->filterByPUNotificationPNotification($pNotification, $comparison)
            ->endUse();
    }

    /**
     * Filter the query by a related PNotification object
     * using the p_u_subscribe_email table as cross reference
     *
     * @param   PNotification $pNotification the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   PUserQuery The current query, for fluid interface
     */
    public function filterByPUSubscribeEmailPNotification($pNotification, $comparison = Criteria::EQUAL)
    {
        return $this
            ->usePUSubscribeEmailPUserQuery()
            ->filterByPUSubscribeEmailPNotification($pNotification, $comparison)
            ->endUse();
    }

    /**
     * Filter the query by a related PNotification object
     * using the p_u_subscribe_screen table as cross reference
     *
     * @param   PNotification $pNotification the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   PUserQuery The current query, for fluid interface
     */
    public function filterByPUSubscribeScreenPNotification($pNotification, $comparison = Criteria::EQUAL)
    {
        return $this
            ->usePUSubscribeScreenPUserQuery()
            ->filterByPUSubscribeScreenPNotification($pNotification, $comparison)
            ->endUse();
    }

    /**
     * Filter the query by a related PMModerationType object
     * using the p_m_user_moderated table as cross reference
     *
     * @param   PMModerationType $pMModerationType the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   PUserQuery The current query, for fluid interface
     */
    public function filterByPMModerationType($pMModerationType, $comparison = Criteria::EQUAL)
    {
        return $this
            ->usePMUserModeratedQuery()
            ->filterByPMModerationType($pMModerationType, $comparison)
            ->endUse();
    }

    /**
     * Exclude object from result
     *
     * @param   PUser $pUser Object to remove from the list of results
     *
     * @return PUserQuery The current query, for fluid interface
     */
    public function prune($pUser = null)
    {
        if ($pUser) {
            $this->addUsingAlias(PUserPeer::ID, $pUser->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Code to execute before every SELECT statement
     *
     * @param     PropelPDO $con The connection object used by the query
     */
    protected function basePreSelect(PropelPDO $con)
    {
        // event behavior
        EventDispatcherProxy::trigger('query.select.pre', new QueryEvent($this));

        return $this->preSelect($con);
    }

    /**
     * Code to execute before every DELETE statement
     *
     * @param     PropelPDO $con The connection object used by the query
     */
    protected function basePreDelete(PropelPDO $con)
    {
        EventDispatcherProxy::trigger(array('delete.pre','query.delete.pre'), new QueryEvent($this));
        // archivable behavior

        if ($this->archiveOnDelete) {
            $this->archive($con);
        } else {
            $this->archiveOnDelete = true;
        }

        // event behavior
        // placeholder, issue #5

        return $this->preDelete($con);
    }

    /**
     * Code to execute after every DELETE statement
     *
     * @param     int $affectedRows the number of deleted rows
     * @param     PropelPDO $con The connection object used by the query
     */
    protected function basePostDelete($affectedRows, PropelPDO $con)
    {
        // event behavior
        EventDispatcherProxy::trigger(array('delete.post','query.delete.post'), new QueryEvent($this));

        return $this->postDelete($affectedRows, $con);
    }

    /**
     * Code to execute before every UPDATE statement
     *
     * @param     array $values The associative array of columns and values for the update
     * @param     PropelPDO $con The connection object used by the query
     * @param     boolean $forceIndividualSaves If false (default), the resulting call is a BasePeer::doUpdate(), otherwise it is a series of save() calls on all the found objects
     */
    protected function basePreUpdate(&$values, PropelPDO $con, $forceIndividualSaves = false)
    {
        // event behavior
        EventDispatcherProxy::trigger(array('update.pre', 'query.update.pre'), new QueryEvent($this));

        return $this->preUpdate($values, $con, $forceIndividualSaves);
    }

    /**
     * Code to execute after every UPDATE statement
     *
     * @param     int $affectedRows the number of updated rows
     * @param     PropelPDO $con The connection object used by the query
     */
    protected function basePostUpdate($affectedRows, PropelPDO $con)
    {
        // event behavior
        EventDispatcherProxy::trigger(array('update.post', 'query.update.post'), new QueryEvent($this));

        return $this->postUpdate($affectedRows, $con);
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     PUserQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(PUserPeer::UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     PUserQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(PUserPeer::UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     PUserQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(PUserPeer::UPDATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     PUserQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(PUserPeer::CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date desc
     *
     * @return     PUserQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(PUserPeer::CREATED_AT);
    }

    /**
     * Order by create date asc
     *
     * @return     PUserQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(PUserPeer::CREATED_AT);
    }
    // query_cache behavior

    public function setQueryKey($key)
    {
        $this->queryKey = $key;

        return $this;
    }

    public function getQueryKey()
    {
        return $this->queryKey;
    }

    public function cacheContains($key)
    {

        return apc_fetch($key);
    }

    public function cacheFetch($key)
    {

        return apc_fetch($key);
    }

    public function cacheStore($key, $value, $lifetime = 3600)
    {
        apc_store($key, $value, $lifetime);
    }

    protected function doSelect($con)
    {
        // check that the columns of the main class are already added (if this is the primary ModelCriteria)
        if (!$this->hasSelectClause() && !$this->getPrimaryCriteria()) {
            $this->addSelfSelectColumns();
        }
        $this->configureSelectColumns();

        $dbMap = Propel::getDatabaseMap(PUserPeer::DATABASE_NAME);
        $db = Propel::getDB(PUserPeer::DATABASE_NAME);

        $key = $this->getQueryKey();
        if ($key && $this->cacheContains($key)) {
            $params = $this->getParams();
            $sql = $this->cacheFetch($key);
        } else {
            $params = array();
            $sql = BasePeer::createSelectSql($this, $params);
            if ($key) {
                $this->cacheStore($key, $sql);
            }
        }

        try {
            $stmt = $con->prepare($sql);
            $db->bindValues($stmt, $params, $dbMap);
            $stmt->execute();
            } catch (Exception $e) {
                Propel::log($e->getMessage(), Propel::LOG_ERR);
                throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), $e);
            }

        return $stmt;
    }

    protected function doCount($con)
    {
        $dbMap = Propel::getDatabaseMap($this->getDbName());
        $db = Propel::getDB($this->getDbName());

        $key = $this->getQueryKey();
        if ($key && $this->cacheContains($key)) {
            $params = $this->getParams();
            $sql = $this->cacheFetch($key);
        } else {
            // check that the columns of the main class are already added (if this is the primary ModelCriteria)
            if (!$this->hasSelectClause() && !$this->getPrimaryCriteria()) {
                $this->addSelfSelectColumns();
            }

            $this->configureSelectColumns();

            $needsComplexCount = $this->getGroupByColumns()
                || $this->getOffset()
                || $this->getLimit()
                || $this->getHaving()
                || in_array(Criteria::DISTINCT, $this->getSelectModifiers());

            $params = array();
            if ($needsComplexCount) {
                if (BasePeer::needsSelectAliases($this)) {
                    if ($this->getHaving()) {
                        throw new PropelException('Propel cannot create a COUNT query when using HAVING and  duplicate column names in the SELECT part');
                    }
                    $db->turnSelectColumnsToAliases($this);
                }
                $selectSql = BasePeer::createSelectSql($this, $params);
                $sql = 'SELECT COUNT(*) FROM (' . $selectSql . ') propelmatch4cnt';
            } else {
                // Replace SELECT columns with COUNT(*)
                $this->clearSelectColumns()->addSelectColumn('COUNT(*)');
                $sql = BasePeer::createSelectSql($this, $params);
            }

            if ($key) {
                $this->cacheStore($key, $sql);
            }
        }

        try {
            $stmt = $con->prepare($sql);
            $db->bindValues($stmt, $params, $dbMap);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute COUNT statement [%s]', $sql), $e);
        }

        return $stmt;
    }

    // archivable behavior

    /**
     * Copy the data of the objects satisfying the query into PUserArchive archive objects.
     * The archived objects are then saved.
     * If any of the objects has already been archived, the archived object
     * is updated and not duplicated.
     * Warning: This termination methods issues 2n+1 queries.
     *
     * @param      PropelPDO $con	Connection to use.
     * @param      Boolean $useLittleMemory	Whether or not to use PropelOnDemandFormatter to retrieve objects.
     *               Set to false if the identity map matters.
     *               Set to true (default) to use less memory.
     *
     * @return     int the number of archived objects
     * @throws     PropelException
     */
    public function archive($con = null, $useLittleMemory = true)
    {
        $totalArchivedObjects = 0;
        $criteria = clone $this;
        // prepare the query
        $criteria->setWith(array());
        if ($useLittleMemory) {
            $criteria->setFormatter(ModelCriteria::FORMAT_ON_DEMAND);
        }
        if ($con === null) {
            $con = Propel::getConnection(PUserPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }
        $con->beginTransaction();
        try {
            // archive all results one by one
            foreach ($criteria->find($con) as $object) {
                $object->archive($con);
                $totalArchivedObjects++;
            }
            $con->commit();
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }

        return $totalArchivedObjects;
    }

    /**
     * Enable/disable auto-archiving on delete for the next query.
     *
     * @param boolean $archiveOnDelete True if the query must archive deleted objects, false otherwise.
     */
    public function setArchiveOnDelete($archiveOnDelete)
    {
        $this->archiveOnDelete = $archiveOnDelete;
    }

    /**
     * Delete records matching the current query without archiving them.
     *
     * @param      PropelPDO $con	Connection to use.
     *
     * @return integer the number of deleted rows
     */
    public function deleteWithoutArchive($con = null)
    {
        $this->archiveOnDelete = false;

        return $this->delete($con);
    }

    /**
     * Delete all records without archiving them.
     *
     * @param      PropelPDO $con	Connection to use.
     *
     * @return integer the number of deleted rows
     */
    public function deleteAllWithoutArchive($con = null)
    {
        $this->archiveOnDelete = false;

        return $this->deleteAll($con);
    }

    // extend behavior
    public function setFormatter($formatter)
    {
        if (is_string($formatter) && $formatter === \ModelCriteria::FORMAT_ON_DEMAND) {
            $formatter = '\Glorpen\Propel\PropelBundle\Formatter\PropelOnDemandFormatter';
        }

        return parent::setFormatter($formatter);
    }
    // equal_nest_parent behavior

    /**
     * Find equal nest PUFollowUs of the supplied PUser object
     *
     * @param  PUser $pUser * @param  PropelPDO $con
     * @return PUser[]|PropelObjectCollection
     */
    public function findPUFollowUsOf(PUser $pUser, $con = null)
    {
        $obj = clone $pUser;
        $obj->clearListPUFollowUsPKs();
        $obj->clearPUFollowUs();

        return $obj->getPUFollowUs($this, $con);
    }

    /**
     * Count equal nest PUFollowUs of the supplied PUser object
     *
     * @param  PUser $pUser * @param  PropelPDO $con
     * @return integer
     */
    public function countPUFollowUsOf(PUser $pUser, PropelPDO $con = null)
    {
        return $pUser->getPUFollowUs()->count();
    }

}
