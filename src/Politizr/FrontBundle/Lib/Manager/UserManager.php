<?php
namespace Politizr\FrontBundle\Lib\Manager;

use Symfony\Component\Security\Core\User\UserInterface;

use Politizr\Constant\ObjectTypeConstants;
use Politizr\Constant\ReputationConstants;

use Politizr\Model\PUser;
use Politizr\Model\PUFollowDD;
use Politizr\Model\PUFollowU;
use Politizr\Model\PUMandate;
use Politizr\Model\PUSubscribeEmail;

use Politizr\Model\PUFollowDDQuery;
use Politizr\Model\PUFollowUQuery;
use Politizr\Model\PNotificationQuery;

/**
 * DB manager service for user.
 *
 * @author Lionel Bouzonville
 */
class UserManager
{
    private $encoderFactory;
    private $usernameCanonicalizer;
    private $emailCanonicalizer;
    private $globalTools;

    private $logger;

    /**
     *
     * @param @security.encoder_factory
     * @param @fos_user.util.username_canonicalizer
     * @param @fos_user.util.email_canonicalizer
     * @param @politizr.tools.global
     * @param @logger
     */
    public function __construct($encoderFactory, $usernameCanonicalizer, $emailCanonicalizer, $globalTools, $logger)
    {
        $this->encoderFactory = $encoderFactory;
        $this->usernameCanonicalizer = $usernameCanonicalizer;
        $this->emailCanonicalizer = $emailCanonicalizer;

        $this->globalTools = $globalTools;

        $this->logger = $logger;
    }

    /* ######################################################################################################## */
    /*                                                  RAW SQL                                                 */
    /* ######################################################################################################## */

    /**
     * User's "My Politizr" timeline
     *
     * @see app/sql/timeline.sql
     *
     * @param array $inQueryDebateIds IN stmt values
     * @param array $inQueryUserIds IN stmt values
     * @param array $inQueryMyDebateIds IN stmt values
     * @param array $inQueryMyReactionIds IN stmt values
     * @param array $inQueryReputationIds IN stmt values
     * @return string
     */
    public function createMyTimelineRawSql($inQueryDebateIds, $inQueryUserIds, $inQueryMyDebateIds, $inQueryMyReactionIds, $inQueryReputationIds)
    {
        $sql = "
( SELECT p_d_debate.id as id, 'null' as target_id, 'null' as target_user_id, 'null' as target_object_name, p_d_debate.title as title, p_d_debate.published_at as published_at, 'Politizr\\\Model\\\PDDebate' as type
FROM p_d_debate
WHERE
    p_d_debate.published = 1
    AND p_d_debate.online = 1
    AND p_d_debate.p_user_id = :p_user_id )

UNION DISTINCT

( SELECT p_d_reaction.id as id, 'null' as target_id, 'null' as target_user_id, 'null' as target_object_name, p_d_reaction.title as title, p_d_reaction.published_at as published_at, 'Politizr\\\Model\\\PDReaction' as type
FROM p_d_reaction
WHERE
    p_d_reaction.published = 1
    AND p_d_reaction.online = 1
    AND p_d_reaction.p_user_id = :p_user_id2
    AND p_d_reaction.tree_level > 0 )

UNION DISTINCT

( SELECT p_d_debate.id as id, 'null' as target_id, 'null' as target_user_id, 'null' as target_object_name, p_d_debate.title as title, p_d_debate.published_at as published_at, 'Politizr\\\Model\\\PDDebate' as type
FROM p_d_debate
WHERE
    p_d_debate.published = 1
    AND p_d_debate.online = 1
    AND p_d_debate.id IN ($inQueryDebateIds) )

UNION DISTINCT

( SELECT p_d_reaction.id as id, 'null' as target_id, 'null' as target_user_id, 'null' as target_object_name, p_d_reaction.title as title, p_d_reaction.published_at as published_at, 'Politizr\\\Model\\\PDReaction' as type
FROM p_d_reaction
WHERE
    p_d_reaction.published = 1
    AND p_d_reaction.online = 1
    AND p_d_reaction.p_d_debate_id IN ($inQueryDebateIds)
    AND p_d_reaction.tree_level > 0 )

UNION DISTINCT

( SELECT p_d_debate.id as id, 'null' as target_id, 'null' as target_user_id, 'null' as target_object_name, p_d_debate.title as title, p_d_debate.published_at as published_at, 'Politizr\\\Model\\\PDDebate' as type
FROM p_d_debate
WHERE
    p_d_debate.published = 1
    AND p_d_debate.online = 1
    AND p_d_debate.p_user_id IN ($inQueryUserIds) )

UNION DISTINCT

( SELECT p_d_reaction.id as id, 'null' as target_id, 'null' as target_user_id, 'null' as target_object_name, p_d_reaction.title as title, p_d_reaction.published_at as published_at, 'Politizr\\\Model\\\PDReaction' as type
FROM p_d_reaction
WHERE
    p_d_reaction.published = 1
    AND p_d_reaction.online = 1
    AND p_d_reaction.p_user_id IN ($inQueryUserIds) )

UNION DISTINCT

( SELECT p_d_reaction.id as id, 'null' as target_id, 'null' as target_user_id, 'null' as target_object_name, p_d_reaction.title as title, p_d_reaction.published_at as published_at, 'Politizr\\\Model\\\PDReaction' as type
FROM p_d_reaction
    LEFT JOIN p_d_debate
        ON p_d_reaction.p_d_debate_id = p_d_debate.id
WHERE
    p_d_reaction.published = 1
    AND p_d_reaction.online = 1
    AND p_d_debate.p_user_id = :p_user_id3
    AND p_d_reaction.tree_level > 0 )

UNION DISTINCT

( SELECT p_d_reaction.id as id, 'null' as target_id, 'null' as target_user_id, 'null' as target_object_name, p_d_reaction.title as title, p_d_reaction.published_at as published_at, 'Politizr\\\Model\\\PDReaction' as type
FROM p_d_reaction as p_d_reaction
    LEFT JOIN p_d_reaction as my_reaction
        ON p_d_reaction.p_d_debate_id = my_reaction.p_d_debate_id
WHERE
    p_d_reaction.published = 1
    AND p_d_reaction.online = 1
    AND my_reaction.id IN ($inQueryMyReactionIds)
    AND p_d_reaction.tree_left > my_reaction.tree_left
    AND p_d_reaction.tree_left < my_reaction.tree_right
    AND p_d_reaction.tree_level > my_reaction.tree_level
    AND p_d_reaction.tree_level > 1 )

UNION DISTINCT

( SELECT p_d_d_comment.id as id, 'null' as target_id, 'null' as target_user_id, 'null' as target_object_name, \"commentaire\" as title, p_d_d_comment.published_at as published_at, 'Politizr\\\Model\\\PDDComment' as type
FROM p_d_d_comment
WHERE
    p_d_d_comment.online = 1
    AND p_d_d_comment.p_user_id = :p_user_id4 )

UNION DISTINCT

( SELECT p_d_r_comment.id as id, 'null' as target_id, 'null' as target_user_id, 'null' as target_object_name, \"commentaire\" as title, p_d_r_comment.published_at as published_at, 'Politizr\\\Model\\\PDRComment' as type
FROM p_d_r_comment
WHERE
    p_d_r_comment.online = 1
    AND p_d_r_comment.p_user_id = :p_user_id5 )

UNION DISTINCT

( SELECT p_d_d_comment.id as id, 'null' as target_id, 'null' as target_user_id, 'null' as target_object_name, \"commentaire\" as title, p_d_d_comment.published_at as published_at, 'Politizr\\\Model\\\PDDComment' as type
FROM p_d_d_comment
WHERE
    p_d_d_comment.online = 1
    AND p_d_d_comment.p_user_id IN ($inQueryUserIds) )

UNION DISTINCT

( SELECT p_d_r_comment.id as id, 'null' as target_id, 'null' as target_user_id, 'null' as target_object_name, \"commentaire\" as title, p_d_r_comment.published_at as published_at, 'Politizr\\\Model\\\PDRComment' as type
FROM p_d_r_comment
WHERE
    p_d_r_comment.online = 1
    AND p_d_r_comment.p_user_id IN ($inQueryUserIds) )

UNION DISTINCT

( SELECT p_d_d_comment.id as id, 'null' as target_id, 'null' as target_user_id, 'null' as target_object_name, \"commentaire\" as title, p_d_d_comment.published_at as published_at, 'Politizr\\\Model\\\PDDComment' as type
FROM p_d_d_comment
WHERE
    p_d_d_comment.online = 1
    AND p_d_d_comment.p_d_debate_id IN ($inQueryMyDebateIds) )

UNION DISTINCT

( SELECT p_d_r_comment.id as id, 'null' as target_id, 'null' as target_user_id, 'null' as target_object_name, \"commentaire\" as title, p_d_r_comment.published_at as published_at, 'Politizr\\\Model\\\PDRComment' as type
FROM p_d_r_comment
WHERE
    p_d_r_comment.online = 1
    AND p_d_r_comment.p_d_reaction_id IN ($inQueryMyReactionIds) )

UNION DISTINCT

( SELECT p_u_reputation.p_r_action_id as id, p_u_reputation.p_object_id as target_id, 'null' as target_user_id, p_u_reputation.p_object_name as target_object_name, p_r_action.title as title, p_u_reputation.created_at as published_at, 'Politizr\\\Model\\\PRAction' as type
FROM p_r_action
    LEFT JOIN p_u_reputation
        ON p_r_action.id = p_u_reputation.p_r_action_id

WHERE
    p_u_reputation.p_user_id = :p_user_id6
    AND p_r_action.id IN ($inQueryReputationIds)
)

UNION DISTINCT

( SELECT p_u_reputation.p_r_action_id as id, p_u_reputation.p_object_id as target_id, p_u_reputation.p_user_id as target_user_id, p_u_reputation.p_object_name as target_object_name, p_r_action.title as title, p_u_reputation.created_at as published_at, 'Politizr\\\Model\\\PRAction' as type
FROM p_r_action
    LEFT JOIN p_u_reputation
        ON p_r_action.id = p_u_reputation.p_r_action_id

WHERE
   p_u_reputation.p_object_id IN (
        ( SELECT p_d_debate.id as id
        FROM p_d_debate
        WHERE
            p_d_debate.published = 1
            AND p_d_debate.online = 1
            AND p_d_debate.p_user_id = :p_user_id7 )
    )
    AND p_r_action.id = :p_r_action_id
)

ORDER BY published_at DESC
LIMIT :offset, :count
        ";

        return $sql;
    }

    /**
     * User's detail timeline
     *
     * @see app/sql/userDetail.sql
     *
     * @return string
     */
    public function createUserDetailTimelineRawSql()
    {
        $sql = "
# Débats rédigés
( SELECT p_d_debate.id as id, p_d_debate.title as title, p_d_debate.published_at as published_at, 'Politizr\\\Model\\\PDDebate' as type
FROM p_d_debate
WHERE
    p_d_debate.published = 1
    AND p_d_debate.online = 1
    AND p_d_debate.p_user_id = :p_user_id )

UNION DISTINCT

#  Réactions rédigées
( SELECT p_d_reaction.id as id, p_d_reaction.title as title, p_d_reaction.published_at as published_at, 'Politizr\\\Model\\\PDReaction' as type
FROM p_d_reaction
WHERE
    p_d_reaction.published = 1
    AND p_d_reaction.online = 1
    AND p_d_reaction.tree_level > 0
    AND p_d_reaction.p_user_id = :p_user_id2 )

UNION DISTINCT

# Commentaires débats rédigés
( SELECT p_d_d_comment.id as id, \"commentaire\" as title, p_d_d_comment.published_at as published_at, 'Politizr\\\Model\\\PDDComment' as type
FROM p_d_d_comment
WHERE
    p_d_d_comment.online = 1
    AND p_d_d_comment.p_user_id = :p_user_id3 )

UNION DISTINCT

# Commentaires réactions rédigés
( SELECT p_d_r_comment.id as id, \"commentaire\" as title, p_d_r_comment.published_at as published_at, 'Politizr\\\Model\\\PDRComment' as type
FROM p_d_r_comment
WHERE
    p_d_r_comment.online = 1
    AND p_d_r_comment.p_user_id = :p_user_id4 )

ORDER BY published_at DESC

LIMIT :offset, :count
        ";

        return $sql;
    }

    /**
     * Users' suggestion for user.
     *
     * @see app/sql/suggestions.sql
     *
     * @return string
     */
    private function createUserSuggestedUsersRawSql()
    {
        // Requête SQL
        $sql = "
#  Concordance des tags suivis / tags caractérisant des users
SELECT DISTINCT
    id,
    uuid,
    provider,
    provider_id,
    nickname,
    realname,
    username,
    username_canonical,
    email,
    email_canonical,
    enabled,
    salt,
    password,
    last_login,
    locked,
    expired,
    expires_at,
    confirmation_token,
    password_requested_at,
    credentials_expired,
    credentials_expire_at,
    roles,
    last_activity,
    p_u_status_id,
    file_name,
    back_file_name,
    copyright,
    gender,
    firstname,
    name,
    birthday,
    subtitle,
    biography,
    website,
    twitter,
    facebook,
    phone,
    newsletter,
    last_connect,
    nb_connected_days,
    nb_views,
    qualified,
    validated,
    online,
    banned,
    banned_nb_days_left,
    banned_nb_total,
    abuse_level,
    created_at,
    updated_at,
    slug
FROM (
( SELECT p_user.*, COUNT(p_user.id) as nb_users, 1 as unionsorting
FROM p_user
    LEFT JOIN p_u_tagged_t
        ON p_user.id = p_u_tagged_t.p_user_id
WHERE
    p_u_tagged_t.p_tag_id IN (
                SELECT p_tag.id
                FROM p_tag
                    LEFT JOIN p_u_tagged_t
                        ON p_tag.id = p_u_tagged_t.p_tag_id
                WHERE
                    p_tag.online = true
                    AND p_u_tagged_t.p_user_id = :p_user_id
    )
    AND p_user.online = 1
    AND p_user.id NOT IN (SELECT p_user_id FROM p_u_follow_u WHERE p_user_follower_id = :p_user_id2)
    AND p_user.id <> :p_user_id3
)

UNION DISTINCT

#  Users les plus populaires
( SELECT p_user.*, COUNT(p_u_follow_u.p_user_id) as nb_users, 2 as unionsorting
FROM p_user
    LEFT JOIN p_u_follow_u
        ON p_user.id = p_u_follow_u.p_user_id
WHERE
    p_user.online = 1
    AND p_user.id NOT IN (SELECT p_user_id FROM p_u_follow_u WHERE p_user_follower_id = :p_user_id4)
    AND p_user.id <> :p_user_id5
GROUP BY p_user.id
ORDER BY nb_users DESC
)

ORDER BY unionsorting ASC
) unionsorting

LIMIT :offset, :limit
        ";

        return $sql;
    }

    /* ######################################################################################################## */
    /*                                            RAW SQL OPERATIONS                                            */
    /* ######################################################################################################## */

    /**
     * My timeline paginated listing
     *
     * @param integer $userId
     * @param string $inQueryDebateIds
     * @param string $inQueryUserIds
     * @param string $inQueryMyDebateIds
     * @param string $inQueryMyReactionIds
     * @param string $inQueryReputationIds
     * @param integer $offset
     * @param integer $count
     * @return string
     */
    public function generateMyTimelinePaginatedListing($userId, $inQueryDebateIds, $inQueryUserIds, $inQueryMyDebateIds, $inQueryMyReactionIds, $inQueryReputationIds, $offset, $count)
    {
        $this->logger->info('*** generateMyTimelinePaginatedListing');
        $this->logger->info('$userId = ' . print_r($userId, true));
        $this->logger->info('$inQueryDebateIds = ' . print_r($inQueryDebateIds, true));
        $this->logger->info('$inQueryUserIds = ' . print_r($inQueryUserIds, true));
        $this->logger->info('$inQueryMyDebateIds = ' . print_r($inQueryMyDebateIds, true));
        $this->logger->info('$inQueryMyReactionIds = ' . print_r($inQueryMyReactionIds, true));
        $this->logger->info('$inQueryReputationIds = ' . print_r($inQueryReputationIds, true));
        $this->logger->info('$offset = ' . print_r($offset, true));
        $this->logger->info('$count = ' . print_r($count, true));

        $con = \Propel::getConnection('default', \Propel::CONNECTION_READ);
        $stmt = $con->prepare($this->createMyTimelineRawSql($inQueryDebateIds, $inQueryUserIds, $inQueryMyDebateIds, $inQueryMyReactionIds, $inQueryReputationIds));

        $stmt->bindValue(':p_user_id', $userId, \PDO::PARAM_INT);
        $stmt->bindValue(':p_user_id2', $userId, \PDO::PARAM_INT);
        $stmt->bindValue(':p_user_id3', $userId, \PDO::PARAM_INT);
        $stmt->bindValue(':p_user_id4', $userId, \PDO::PARAM_INT);
        $stmt->bindValue(':p_user_id5', $userId, \PDO::PARAM_INT);
        $stmt->bindValue(':p_user_id6', $userId, \PDO::PARAM_INT);
        $stmt->bindValue(':p_user_id7', $userId, \PDO::PARAM_INT);
        $stmt->bindValue(':p_r_action_id', ReputationConstants::ACTION_ID_D_AUTHOR_DEBATE_FOLLOW, \PDO::PARAM_INT);
        
        $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $stmt->bindValue(':count', $count, \PDO::PARAM_INT);

        $stmt->execute();

        $result = $stmt->fetchAll();

        $timeline = $this->globalTools->hydrateTimelineRows($result);

        return $timeline;
    }

    /**
     * User's detail timeline paginated listing
     *
     * @param integer $userId
     * @param integer $offset
     * @param integer $count
     * @return string
     */
    public function generateUserDetailTimelinePaginatedListing($userId, $offset, $count)
    {
        $this->logger->info('*** generateUserDetailTimelinePaginatedListing');
        $this->logger->info('$userId = ' . print_r($userId, true));
        $this->logger->info('$offset = ' . print_r($offset, true));
        $this->logger->info('$count = ' . print_r($count, true));

        $con = \Propel::getConnection('default', \Propel::CONNECTION_READ);
        $stmt = $con->prepare($this->createUserDetailTimelineRawSql());

        $stmt->bindValue(':p_user_id', $userId, \PDO::PARAM_INT);
        $stmt->bindValue(':p_user_id2', $userId, \PDO::PARAM_INT);
        $stmt->bindValue(':p_user_id3', $userId, \PDO::PARAM_INT);
        $stmt->bindValue(':p_user_id4', $userId, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $stmt->bindValue(':count', $count, \PDO::PARAM_INT);

        $stmt->execute();

        $result = $stmt->fetchAll();

        $timeline = $this->globalTools->hydrateTimelineRows($result);

        return $timeline;
    }

    /**
     * User's users' suggestions paginated listing
     *
     * @param integer $userId
     * @param integer $offset
     * @param integer $limit
     * @return PropelCollection[PUser]
     */
    public function generateUserSuggestedUsersPaginatedListing($userId, $offset, $limit)
    {
        $this->logger->info('*** generateUserSuggestedUsersPaginatedListing');
        $this->logger->info('$userId = ' . print_r($userId, true));
        $this->logger->info('$offset = ' . print_r($offset, true));
        $this->logger->info('$limit = ' . print_r($limit, true));

        $con = \Propel::getConnection('default', \Propel::CONNECTION_READ);
        $stmt = $con->prepare($this->createUserSuggestedUsersRawSql());

        $stmt->bindValue(':p_user_id', $userId, \PDO::PARAM_INT);
        $stmt->bindValue(':p_user_id2', $userId, \PDO::PARAM_INT);
        $stmt->bindValue(':p_user_id3', $userId, \PDO::PARAM_INT);
        $stmt->bindValue(':p_user_id4', $userId, \PDO::PARAM_INT);
        $stmt->bindValue(':p_user_id5', $userId, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);

        $stmt->execute();

        $result = $stmt->fetchAll();

        $users = new \PropelCollection();
        foreach ($result as $row) {
            $user = new PUser();
            $user->hydrate($row);

            $users->append($user);
        }

        return $users;
    }

    /* ######################################################################################################## */
    /*                                        SECURITY OPERATIONS                                               */
    /* ######################################################################################################## */

    /**
     * {@inheritDoc}
     */
    public function updateCanonicalFields(UserInterface $user)
    {
        $user->setUsernameCanonical($this->canonicalizeUsername($user->getUsername()));
        $user->setEmailCanonical($this->canonicalizeEmail($user->getEmail()));
    }

    /**
     * {@inheritDoc}
     */
    public function updatePassword(UserInterface $user)
    {
        if (0 !== strlen($password = $user->getPlainPassword())) {
            $encoder = $this->getEncoder($user);
            $user->setPassword($encoder->encodePassword($password, $user->getSalt()));
            $user->eraseCredentials();
        }
    }

    /**
     * Canonicalizes an email
     *
     * @param string $email
     *
     * @return string
     */
    protected function canonicalizeEmail($email)
    {
        return $this->emailCanonicalizer->canonicalize($email);
    }

    /**
     * Canonicalizes a username
     *
     * @param string $username
     *
     * @return string
     */
    protected function canonicalizeUsername($username)
    {
        return $this->usernameCanonicalizer->canonicalize($username);
    }

    protected function getEncoder(UserInterface $user)
    {
        return $this->encoderFactory->getEncoder($user);
    }


    /* ######################################################################################################## */
    /*                                            CRUD OPERATIONS                                               */
    /* ######################################################################################################## */

    /**
     * Update a user for inscription process start
     *
     * @param PUser $user
     * @param array|string $roles
     * @param string $canonicalUsername
     * @param string $encodedPassword
     * @return $user
     */
    public function updateForInscriptionStart(PUser $user, $roles, $canonicalUsername, $encodedPassword)
    {
        if ($user) {
            foreach ($roles as $role) {
                $user->addRole($role);
            }
            $user->setUsernameCanonical($canonicalUsername);
            $user->setPassword($encodedPassword);
            
            $user->eraseCredentials();
        }

        return $user;
    }

    /**
     * Update a user for inscription process finish
     *
     * @param PUser $user
     * @param array|string $roles
     * @param integer $statusId
     * @param boolean $isQualified
     * @return $user
     */
    public function updateForInscriptionFinish(PUser $user, $roles, $statusId, $isQualified)
    {
        if ($user) {
            foreach ($roles as $role) {
                $user->addRole($role);
            }

            $user->setOnline(true);
            $user->setPUStatusId($statusId);
            $user->setQualified($isQualified);
            $user->setLastLogin(new \DateTime());

            $user->removeRole('ROLE_CITIZEN_INSCRIPTION');
            $user->removeRole('ROLE_ELECTED_INSCRIPTION');
        }

        return $user;
    }

    /**
     * Update user with oauth data
     *
     * @param PUser $user
     * @param array|string[] $oAuthData
     * @return PUser
     */
    public function updateOAuthData(PUser $user, $oAuthData)
    {
        if ($user) {
            if (isset($oAuthData['provider'])) {
                $user->setProvider($oAuthData['provider']);
            }
            if (isset($oAuthData['providerId'])) {
                $user->setProviderId($oAuthData['providerId']);
            }
            if (isset($oAuthData['nickname'])) {
                $user->setNickname($oAuthData['nickname']);
            }
            if (isset($oAuthData['realname'])) {
                $user->setRealname($oAuthData['realname']);
            }
            if (isset($oAuthData['email'])) {
                $user->setEmail($oAuthData['email']);
            }
            if (isset($oAuthData['accessToken'])) {
                $user->setConfirmationToken($oAuthData['accessToken']);
            }
        }

        return $user;
    }

    /* ######################################################################################################## */
    /*                                    RELATED TABLES OPERATIONS                                             */
    /* ######################################################################################################## */


    /**
     * Delete user's mandate
     *
     * @param PUMandate $mandate
     * @return integer
     */
    public function deleteMandate(PUMandate $mandate)
    {
        $result = $mandate->delete();

        return $result;
    }

    /**
     * Create a new PUFollowDD assocation
     *
     * @param integer $userId
     * @param integer $debateId
     * @return PUFollowDD
     */
    public function createUserFollowDebate($userId, $debateId)
    {
        $puFollowDD = new PUFollowDD();

        $puFollowDD->setPUserId($userId);
        $puFollowDD->setPDDebateId($debateId);
        $puFollowDD->save();

        return $puFollowDD;
    }

    /**
     * Delete PUFollowDD
     *
     * @param integer $userId
     * @param integer $debateId
     * @return integer
     */
    public function deleteUserFollowDebate($userId, $debateId)
    {
        // Suppression élément(s)
        $result = PUFollowDDQuery::create()
            ->filterByPUserId($userId)
            ->filterByPDDebateId($debateId)
            ->delete();

        return $result;
    }

    /**
     * Create a new PUFollowU assocation
     *
     * @param integer $sourceId
     * @param integer $targetId
     * @return PUFollowDD
     */
    public function createUserFollowUser($sourceId, $targetId)
    {
        $puFollowU = new PUFollowU();

        $puFollowU->setPUserFollowerId($sourceId);
        $puFollowU->setPUserId($targetId);
        $puFollowU->save();

        return $puFollowU;
    }

    /**
     * Delete PUFollowU
     *
     * @param integer $sourceId
     * @param integer $targetId
     * @return integer
     */
    public function deleteUserFollowUser($sourceId, $targetId)
    {
        // Suppression élément(s)
        $result = PUFollowUQuery::create()
            ->filterByPUserId($targetId)
            ->filterByPUserFollowerId($sourceId)
            ->delete();

        return $result;
    }



    /**
     * Update PUFollowU contextual email subscription
     *
     * @param integer $sourceId
     * @param integer $targetId
     * @param boolean $isNotif
     * @param string $context
     * @return PUFollowU
     */
    public function updateFollowUserContextEmailNotification($sourceId, $targetId, $isNotif, $context)
    {
        $puFollowU = PUFollowUQuery::create()
            ->filterByPUserFollowerId($sourceId)
            ->filterByPUserId($targetId)
            ->findOne();

        if ($puFollowU && $context == ObjectTypeConstants::CONTEXT_DEBATE) {
            $puFollowU->setNotifDebate($isNotif);
            $puFollowU->save();
        } elseif ($puFollowU && $context == ObjectTypeConstants::CONTEXT_REACTION) {
            $puFollowU->setNotifReaction($isNotif);
            $puFollowU->save();
        } elseif ($puFollowU && $context == ObjectTypeConstants::CONTEXT_COMMENT) {
            $puFollowU->setNotifComment($isNotif);
            $puFollowU->save();
        }

        return $puFollowU;
    }

    /**
     * Update PUFollowU contextual email subscription
     *
     * @param integer $userId
     * @param integer $debateId
     * @param boolean $isNotif
     * @param string $context
     * @return PUFollowDD
     */
    public function updateFollowDebateContextEmailNotification($userId, $debateId, $isNotif, $context)
    {
        $puFollowDD = PUFollowDDQuery::create()
            ->filterByPUserId($userId)
            ->filterByPDDebateId($debateId)
            ->findOne();

        if ($puFollowDD && $context == ObjectTypeConstants::CONTEXT_REACTION) {
            $puFollowDD->setNotifReaction($isNotif);
            $puFollowDD->save();
        }

        return $puFollowDD;
    }

    /**
     * Create PUSubscribeEmail between a user and every PNotification
     *
     * @param integer $userId
     * @param integer $debateId
     * @return PUFollowDD
     */
    public function createAllUserSubscribeEmail($userId)
    {
        $notifications = PNotificationQuery::create()->find();

        foreach ($notifications as $notif) {
            $puSubscribeEmail = new PUSubscribeEmail();

            $puSubscribeEmail->setPUserId($userId);
            $puSubscribeEmail->setPNotificationId($notif->getId());

            $puSubscribeEmail->save();
        }
    }
}
