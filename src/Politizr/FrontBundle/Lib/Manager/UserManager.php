<?php
namespace Politizr\FrontBundle\Lib\Manager;

use Symfony\Component\Security\Core\User\UserInterface;

use Politizr\Constant\ObjectTypeConstants;
use Politizr\Constant\ReputationConstants;
use Politizr\Constant\UserConstants;

use Politizr\Model\PUser;
use Politizr\Model\PUFollowDD;
use Politizr\Model\PUFollowU;
use Politizr\Model\PUMandate;
use Politizr\Model\PUSubscribePNE;

use Politizr\Model\PUserQuery;
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
     * Homepage userse
     *
     * @see app/sql/homeUsers.sql
     *
     * @return string
     */
    private function createHomepageUsersRawSql()
    {
        // Requête SQL
        $sql = "
SELECT p_user.id as id
FROM p_user
WHERE
    p_user.online = 1
    AND p_user.homepage = 1
ORDER BY rand()
LIMIT :offset, :limit
";

        return $sql;
    }

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
     * @param array $inQueryReputationIds2 IN stmt values
     * @param array $inQueryReputationIds3 IN stmt values
     * @param array $inQueryReputationIds4 IN stmt values
     * @param array $inQueryReputationIds5 IN stmt values
     * @return string
     */
    public function createMyTimelineRawSql(
        $inQueryDebateIds,
        $inQueryUserIds,
        $inQueryMyDebateIds,
        $inQueryMyReactionIds,
        $inQueryReputationIds,
        $inQueryReputationIds2,
        $inQueryReputationIds3,
        $inQueryReputationIds4,
        $inQueryReputationIds5
    ) {
        $sql = "
#  Débats publiés
( SELECT p_d_debate.id as id, null as target_id, null as target_user_id, null as target_object_name, p_d_debate.title as title, p_d_debate.published_at as published_at, 'Politizr\\\Model\\\PDDebate' as type
FROM p_d_debate
WHERE
    p_d_debate.published = 1
    AND p_d_debate.online = 1
    AND p_d_debate.p_user_id = :p_user_id )

UNION DISTINCT

#  Réactions publiés
( SELECT p_d_reaction.id as id, null as target_id, null as target_user_id, null as target_object_name, p_d_reaction.title as title, p_d_reaction.published_at as published_at, 'Politizr\\\Model\\\PDReaction' as type
FROM p_d_reaction
WHERE
    p_d_reaction.published = 1
    AND p_d_reaction.online = 1
    AND p_d_reaction.p_user_id = :p_user_id2
    AND p_d_reaction.tree_level > 0 )

UNION DISTINCT

#  Débats suivis
( SELECT p_d_debate.id as id, null as target_id, null as target_user_id, null as target_object_name, p_d_debate.title as title, p_d_debate.published_at as published_at, 'Politizr\\\Model\\\PDDebate' as type
FROM p_d_debate
WHERE
    p_d_debate.published = 1
    AND p_d_debate.online = 1
    AND p_d_debate.id IN ($inQueryDebateIds) )

UNION DISTINCT

#  Réactions aux débats suivis
( SELECT p_d_reaction.id as id, null as target_id, null as target_user_id, null as target_object_name, p_d_reaction.title as title, p_d_reaction.published_at as published_at, 'Politizr\\\Model\\\PDReaction' as type
FROM p_d_reaction
WHERE
    p_d_reaction.published = 1
    AND p_d_reaction.online = 1
    AND p_d_reaction.p_d_debate_id IN ($inQueryDebateIds)
    AND p_d_reaction.tree_level > 0 )

UNION DISTINCT

# Débats des users suivis
( SELECT p_d_debate.id as id, null as target_id, null as target_user_id, null as target_object_name, p_d_debate.title as title, p_d_debate.published_at as published_at, 'Politizr\\\Model\\\PDDebate' as type
FROM p_d_debate
WHERE
    p_d_debate.published = 1
    AND p_d_debate.online = 1
    AND p_d_debate.p_user_id IN ($inQueryUserIds) )

UNION DISTINCT

# Réactions des users suivis
( SELECT p_d_reaction.id as id, null as target_id, null as target_user_id, null as target_object_name, p_d_reaction.title as title, p_d_reaction.published_at as published_at, 'Politizr\\\Model\\\PDReaction' as type
FROM p_d_reaction
WHERE
    p_d_reaction.published = 1
    AND p_d_reaction.online = 1
    AND p_d_reaction.p_user_id IN ($inQueryUserIds) )

UNION DISTINCT

# Réactions sur mes débats
( SELECT p_d_reaction.id as id, null as target_id, null as target_user_id, null as target_object_name, p_d_reaction.title as title, p_d_reaction.published_at as published_at, 'Politizr\\\Model\\\PDReaction' as type
FROM p_d_reaction
    LEFT JOIN p_d_debate
        ON p_d_reaction.p_d_debate_id = p_d_debate.id
WHERE
    p_d_reaction.published = 1
    AND p_d_reaction.online = 1
    AND p_d_debate.p_user_id = :p_user_id3
    AND p_d_reaction.tree_level > 0 )

UNION DISTINCT

# Réactions sur mes réactions
( SELECT p_d_reaction.id as id, null as target_id, null as target_user_id, null as target_object_name, p_d_reaction.title as title, p_d_reaction.published_at as published_at, 'Politizr\\\Model\\\PDReaction' as type
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

# Commentaires débats publiés
( SELECT p_d_d_comment.id as id, null as target_id, null as target_user_id, null as target_object_name, \"commentaire\" as title, p_d_d_comment.published_at as published_at, 'Politizr\\\Model\\\PDDComment' as type
FROM p_d_d_comment
WHERE
    p_d_d_comment.online = 1
    AND p_d_d_comment.p_user_id = :p_user_id4 )

UNION DISTINCT

# Commentaires réactions publiés
( SELECT p_d_r_comment.id as id, null as target_id, null as target_user_id, null as target_object_name, \"commentaire\" as title, p_d_r_comment.published_at as published_at, 'Politizr\\\Model\\\PDRComment' as type
FROM p_d_r_comment
WHERE
    p_d_r_comment.online = 1
    AND p_d_r_comment.p_user_id = :p_user_id5 )

UNION DISTINCT

# Commentaires débats des users suivis
( SELECT p_d_d_comment.id as id, null as target_id, null as target_user_id, null as target_object_name, \"commentaire\" as title, p_d_d_comment.published_at as published_at, 'Politizr\\\Model\\\PDDComment' as type
FROM p_d_d_comment
WHERE
    p_d_d_comment.online = 1
    AND p_d_d_comment.p_user_id IN ($inQueryUserIds) )

UNION DISTINCT

# Commentaires réactions des users suivis
( SELECT p_d_r_comment.id as id, null as target_id, null as target_user_id, null as target_object_name, \"commentaire\" as title, p_d_r_comment.published_at as published_at, 'Politizr\\\Model\\\PDRComment' as type
FROM p_d_r_comment
WHERE
    p_d_r_comment.online = 1
    AND p_d_r_comment.p_user_id IN ($inQueryUserIds) )

UNION DISTINCT

# Commentaires débats des débats suivis
( SELECT p_d_d_comment.id as id, null as target_id, null as target_user_id, null as target_object_name, \"commentaire\" as title, p_d_d_comment.published_at as published_at, 'Politizr\\\Model\\\PDDComment' as type
FROM p_d_d_comment
WHERE
    p_d_d_comment.online = 1
    AND p_d_d_comment.p_d_debate_id IN ($inQueryDebateIds) )

UNION DISTINCT

# Commentaires réactions des débats suivis
( SELECT p_d_r_comment.id as id, null as target_id, null as target_user_id, null as target_object_name, \"commentaire\" as title, p_d_r_comment.published_at as published_at, 'Politizr\\\Model\\\PDRComment' as type
FROM p_d_r_comment
    LEFT JOIN p_d_reaction
        ON p_d_r_comment.p_d_reaction_id = p_d_reaction.id
WHERE
    p_d_r_comment.online = 1
    AND p_d_reaction.p_d_debate_id IN ($inQueryDebateIds) )

UNION DISTINCT

# Commentaires sur mes débats
( SELECT p_d_d_comment.id as id, null as target_id, null as target_user_id, null as target_object_name, \"commentaire\" as title, p_d_d_comment.published_at as published_at, 'Politizr\\\Model\\\PDDComment' as type
FROM p_d_d_comment
WHERE
    p_d_d_comment.online = 1
    AND p_d_d_comment.p_d_debate_id IN ($inQueryMyDebateIds) )

UNION DISTINCT

# Commentaires sur mes réactions
( SELECT p_d_r_comment.id as id, null as target_id, null as target_user_id, null as target_object_name, \"commentaire\" as title, p_d_r_comment.published_at as published_at, 'Politizr\\\Model\\\PDRComment' as type
FROM p_d_r_comment
WHERE
    p_d_r_comment.online = 1
    AND p_d_r_comment.p_d_reaction_id IN ($inQueryMyReactionIds) )

UNION DISTINCT

#  Actions réputation: note +/- comment / sujet / reponse, suivre un utilisateur, être suivi par un utilisateur
( SELECT p_u_reputation.p_r_action_id as id, p_u_reputation.p_object_id as target_id, null as target_user_id, p_u_reputation.p_object_name as target_object_name, p_r_action.title as title, p_u_reputation.created_at as published_at, 'Politizr\\\Model\\\PRAction' as type
FROM p_r_action
    LEFT JOIN p_u_reputation
        ON p_r_action.id = p_u_reputation.p_r_action_id

WHERE
    p_u_reputation.p_user_id = :p_user_id6
    AND p_r_action.id IN ($inQueryReputationIds)
)

UNION DISTINCT

# Actions réputation: recevoir une note +/- pour son débat, être suivi sur son débat
( SELECT 
    CASE p_u_reputation.p_r_action_id
    WHEN :id_author_debate_note_pos THEN :id_target_debate_note_pos
    WHEN :id_author_debate_note_neg THEN :id_target_debate_note_neg
    WHEN :id_author_debate_follow THEN :id_author_target_follow
    ELSE p_u_reputation.p_r_action_id
    END as id,
    p_u_reputation.p_object_id as target_id, p_u_reputation.p_user_id as target_user_id, p_u_reputation.p_object_name as target_object_name, p_r_action.title as title, p_u_reputation.created_at as published_at, 'Politizr\\\Model\\\PRAction' as type
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
    AND p_r_action.id IN ($inQueryReputationIds2)
)

UNION DISTINCT

# Actions réputation: recevoir une note +/- sur sa réaction
( SELECT 
    CASE p_u_reputation.p_r_action_id
    WHEN :id_author_reaction_note_pos THEN :id_target_reaction_note_pos
    WHEN :id_author_reaction_note_neg THEN :id_target_reaction_note_neg
    ELSE p_u_reputation.p_r_action_id
    END as id,
    p_u_reputation.p_object_id as target_id, p_u_reputation.p_user_id as target_user_id, p_u_reputation.p_object_name as target_object_name, p_r_action.title as title, p_u_reputation.created_at as published_at, 'Politizr\\\Model\\\PRAction' as type
FROM p_r_action
    LEFT JOIN p_u_reputation
        ON p_r_action.id = p_u_reputation.p_r_action_id

WHERE
   p_u_reputation.p_object_id IN (
        ( SELECT p_d_reaction.id as id
        FROM p_d_reaction
        WHERE
            p_d_reaction.published = 1
            AND p_d_reaction.online = 1
            AND p_d_reaction.p_user_id = :p_user_id8
            AND p_d_reaction.tree_level > 0 )
    )
    AND p_r_action.id IN ($inQueryReputationIds3)
)

UNION DISTINCT

# Actions réputation: recevoir une note +/- sur son commentaire de débat
( SELECT 
    CASE p_u_reputation.p_r_action_id
    WHEN :id_author_comment_note_pos THEN :id_target_comment_note_pos
    WHEN :id_author_comment_note_neg THEN :id_target_comment_note_neg
    ELSE p_u_reputation.p_r_action_id
    END as id,
    p_u_reputation.p_object_id as target_id, p_u_reputation.p_user_id as target_user_id, p_u_reputation.p_object_name as target_object_name, p_r_action.title as title, p_u_reputation.created_at as published_at, 'Politizr\\\Model\\\PRAction' as type
FROM p_r_action
    LEFT JOIN p_u_reputation
        ON p_r_action.id = p_u_reputation.p_r_action_id

WHERE
   p_u_reputation.p_object_id IN (
        ( SELECT p_d_d_comment.id as id
        FROM p_d_d_comment
        WHERE
            p_d_d_comment.online = 1
            AND p_d_d_comment.p_user_id = :p_user_id9
        )
    )
    AND p_u_reputation.p_object_name = 'Politizr\\\Model\\\PDDComment'
    AND p_r_action.id IN ($inQueryReputationIds4)
)

UNION DISTINCT

( SELECT 
    CASE p_u_reputation.p_r_action_id
    WHEN :id_author_comment_note_pos2 THEN :id_target_comment_note_pos2
    WHEN :id_author_comment_note_neg2 THEN :id_target_comment_note_neg2
    ELSE p_u_reputation.p_r_action_id
    END as id,
    p_u_reputation.p_object_id as target_id, p_u_reputation.p_user_id as target_user_id, p_u_reputation.p_object_name as target_object_name, p_r_action.title as title, p_u_reputation.created_at as published_at, 'Politizr\\\Model\\\PRAction' as type
FROM p_r_action
    LEFT JOIN p_u_reputation
        ON p_r_action.id = p_u_reputation.p_r_action_id

WHERE
   p_u_reputation.p_object_id IN (
        ( SELECT p_d_r_comment.id as id
        FROM p_d_r_comment
        WHERE
            p_d_r_comment.online = 1
            AND p_d_r_comment.p_user_id = :p_user_id10
        )
    )
    AND p_u_reputation.p_object_name = 'Politizr\\\Model\\\PDRComment'
    AND p_r_action.id IN ($inQueryReputationIds4)
)

UNION DISTINCT

# Badges
( SELECT p_u_badge.p_r_badge_id as id, null as target_id, null as target_user_id, null as target_object_name, p_r_badge.title as title, p_u_badge.created_at as published_at, 'Politizr\\\Model\\\PRBadge' as type
FROM p_r_badge
    LEFT JOIN p_u_badge
        ON p_r_badge.id = p_u_badge.p_r_badge_id

WHERE
    p_u_badge.p_user_id = :p_user_id11
)

UNION DISTINCT

# Création profil
( SELECT p_user.id as id, null as target_id, null as target_user_id, null as target_object_name, p_user.name as title, p_user.created_at as published_at, 'Politizr\\\Model\\\PUser' as type
FROM p_user
WHERE
    p_user.id = :p_user_id12
)

UNION DISTINCT

#  Actions réputation des users suivis: note + comment / sujet / reponse, suivre un utilisateur
( SELECT p_u_reputation.p_r_action_id as id, p_u_reputation.p_object_id as target_id, p_user_id as target_user_id, p_u_reputation.p_object_name as target_object_name, p_r_action.title as title, p_u_reputation.created_at as published_at, 'Politizr\\\Model\\\PRAction' as type
FROM p_r_action
    LEFT JOIN p_u_reputation
        ON p_r_action.id = p_u_reputation.p_r_action_id

WHERE
    p_u_reputation.p_user_id IN ($inQueryUserIds)
    AND p_r_action.id IN ($inQueryReputationIds5)
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
".ObjectTypeConstants::SQL_P_USER_COLUMNS."
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
     * Users homepage listing
     *
     * @param integer $limit
     * @return PropelCollection
     */
    public function generateHomepageUsers($limit)
    {
        // $this->logger->info('*** generateHomepageUsers');

        $con = \Propel::getConnection('default', \Propel::CONNECTION_READ);

        $stmt = $con->prepare($this->createHomepageUsersRawSql($limit));
        $stmt->bindValue(':offset', 0, \PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);

        $stmt->execute();

        $result = $stmt->fetchAll();

        $users = new \PropelCollection();
        $i = 0;
        foreach ($result as $row) {
            $user = PUserQuery::create()->findPk($row['id']);
            $users->set($i, $user);
            $i++;
        }

        return $users;
    }
    

    /**
     * My timeline paginated listing
     *
     * @param integer $userId
     * @param string $inQueryDebateIds
     * @param string $inQueryUserIds
     * @param string $inQueryMyDebateIds
     * @param string $inQueryMyReactionIds
     * @param string $inQueryReputationIds
     * @param string $inQueryReputationIds2
     * @param string $inQueryReputationIds3
     * @param string $inQueryReputationIds4
     * @param string $inQueryReputationIds5
     * @param integer $offset
     * @param integer $count
     * @return string
     */
    public function generateMyTimelinePaginatedListing(
        $userId,
        $inQueryDebateIds,
        $inQueryUserIds,
        $inQueryMyDebateIds,
        $inQueryMyReactionIds,
        $inQueryReputationIds,
        $inQueryReputationIds2,
        $inQueryReputationIds3,
        $inQueryReputationIds4,
        $inQueryReputationIds5,
        $offset,
        $count
    ) {
        // $this->logger->info('*** generateMyTimelinePaginatedListing');
        // $this->logger->info('$userId = ' . print_r($userId, true));
        // $this->logger->info('$inQueryDebateIds = ' . print_r($inQueryDebateIds, true));
        // $this->logger->info('$inQueryUserIds = ' . print_r($inQueryUserIds, true));
        // $this->logger->info('$inQueryMyDebateIds = ' . print_r($inQueryMyDebateIds, true));
        // $this->logger->info('$inQueryMyReactionIds = ' . print_r($inQueryMyReactionIds, true));
        // $this->logger->info('$inQueryReputationIds = ' . print_r($inQueryReputationIds, true));
        // $this->logger->info('$inQueryReputationIds2 = ' . print_r($inQueryReputationIds2, true));
        // $this->logger->info('$inQueryReputationIds3 = ' . print_r($inQueryReputationIds3, true));
        // $this->logger->info('$inQueryReputationIds4 = ' . print_r($inQueryReputationIds4, true));
        // $this->logger->info('$inQueryReputationIds5 = ' . print_r($inQueryReputationIds5, true));
        // $this->logger->info('$offset = ' . print_r($offset, true));
        // $this->logger->info('$count = ' . print_r($count, true));

        $con = \Propel::getConnection('default', \Propel::CONNECTION_READ);
        $stmt = $con->prepare($this->createMyTimelineRawSql(
            $inQueryDebateIds,
            $inQueryUserIds,
            $inQueryMyDebateIds,
            $inQueryMyReactionIds,
            $inQueryReputationIds,
            $inQueryReputationIds2,
            $inQueryReputationIds3,
            $inQueryReputationIds4,
            $inQueryReputationIds5
        ));

        $stmt->bindValue(':p_user_id', $userId, \PDO::PARAM_INT);
        $stmt->bindValue(':p_user_id2', $userId, \PDO::PARAM_INT);
        $stmt->bindValue(':p_user_id3', $userId, \PDO::PARAM_INT);
        $stmt->bindValue(':p_user_id4', $userId, \PDO::PARAM_INT);
        $stmt->bindValue(':p_user_id5', $userId, \PDO::PARAM_INT);
        $stmt->bindValue(':p_user_id6', $userId, \PDO::PARAM_INT);
        $stmt->bindValue(':p_user_id7', $userId, \PDO::PARAM_INT);
        $stmt->bindValue(':p_user_id8', $userId, \PDO::PARAM_INT);
        $stmt->bindValue(':p_user_id9', $userId, \PDO::PARAM_INT);
        $stmt->bindValue(':p_user_id10', $userId, \PDO::PARAM_INT);
        $stmt->bindValue(':p_user_id11', $userId, \PDO::PARAM_INT);
        $stmt->bindValue(':p_user_id12', $userId, \PDO::PARAM_INT);

        $stmt->bindValue(':id_author_debate_note_pos', ReputationConstants::ACTION_ID_D_AUTHOR_DEBATE_NOTE_POS, \PDO::PARAM_INT);
        $stmt->bindValue(':id_target_debate_note_pos', ReputationConstants::ACTION_ID_D_TARGET_DEBATE_NOTE_POS, \PDO::PARAM_INT);
        $stmt->bindValue(':id_author_debate_note_neg', ReputationConstants::ACTION_ID_D_AUTHOR_DEBATE_NOTE_NEG, \PDO::PARAM_INT);
        $stmt->bindValue(':id_target_debate_note_neg', ReputationConstants::ACTION_ID_D_TARGET_DEBATE_NOTE_NEG, \PDO::PARAM_INT);
        $stmt->bindValue(':id_author_debate_follow', ReputationConstants::ACTION_ID_D_AUTHOR_DEBATE_FOLLOW, \PDO::PARAM_INT);
        $stmt->bindValue(':id_author_target_follow', ReputationConstants::ACTION_ID_D_TARGET_DEBATE_FOLLOW, \PDO::PARAM_INT);
        $stmt->bindValue(':id_author_reaction_note_pos', ReputationConstants::ACTION_ID_D_AUTHOR_REACTION_NOTE_POS, \PDO::PARAM_INT);
        $stmt->bindValue(':id_target_reaction_note_pos', ReputationConstants::ACTION_ID_D_TARGET_REACTION_NOTE_POS, \PDO::PARAM_INT);
        $stmt->bindValue(':id_author_reaction_note_neg', ReputationConstants::ACTION_ID_D_AUTHOR_REACTION_NOTE_NEG, \PDO::PARAM_INT);
        $stmt->bindValue(':id_target_reaction_note_neg', ReputationConstants::ACTION_ID_D_TARGET_REACTION_NOTE_NEG, \PDO::PARAM_INT);
        $stmt->bindValue(':id_author_comment_note_pos', ReputationConstants::ACTION_ID_D_AUTHOR_COMMENT_NOTE_POS, \PDO::PARAM_INT);
        $stmt->bindValue(':id_target_comment_note_pos', ReputationConstants::ACTION_ID_D_TARGET_COMMENT_NOTE_POS, \PDO::PARAM_INT);
        $stmt->bindValue(':id_author_comment_note_neg', ReputationConstants::ACTION_ID_D_AUTHOR_COMMENT_NOTE_NEG, \PDO::PARAM_INT);
        $stmt->bindValue(':id_target_comment_note_neg', ReputationConstants::ACTION_ID_D_TARGET_COMMENT_NOTE_NEG, \PDO::PARAM_INT);
        $stmt->bindValue(':id_author_comment_note_pos2', ReputationConstants::ACTION_ID_D_AUTHOR_COMMENT_NOTE_POS, \PDO::PARAM_INT);
        $stmt->bindValue(':id_target_comment_note_pos2', ReputationConstants::ACTION_ID_D_TARGET_COMMENT_NOTE_POS, \PDO::PARAM_INT);
        $stmt->bindValue(':id_author_comment_note_neg2', ReputationConstants::ACTION_ID_D_AUTHOR_COMMENT_NOTE_NEG, \PDO::PARAM_INT);
        $stmt->bindValue(':id_target_comment_note_neg2', ReputationConstants::ACTION_ID_D_TARGET_COMMENT_NOTE_NEG, \PDO::PARAM_INT);

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
        // $this->logger->info('*** generateUserDetailTimelinePaginatedListing');
        // $this->logger->info('$userId = ' . print_r($userId, true));
        // $this->logger->info('$offset = ' . print_r($offset, true));
        // $this->logger->info('$count = ' . print_r($count, true));

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
        // $this->logger->info('*** generateUserSuggestedUsersPaginatedListing');
        // $this->logger->info('$userId = ' . print_r($userId, true));
        // $this->logger->info('$offset = ' . print_r($offset, true));
        // $this->logger->info('$limit = ' . print_r($limit, true));

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
            $user->setQualified(false);
            $user->setOnline(false);

            // remove existing roles
            foreach ($existingRoles = $user->getRoles() as $role) {
                $user->removeRole($role);
            }
            foreach ($roles as $role) {
                $user->addRole($role);
            }
            $user->setUsernameCanonical($canonicalUsername);
            $user->setPassword($encodedPassword);
            $user->setPUStatusId(UserConstants::STATUS_INSCRIPTION_PROCESS);
            
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
        $puFollowDD = PUFollowDDQuery::create()
            ->filterByPUserId($userId)
            ->filterByPDDebateId($debateId)
            ->findOne();

        if (!$puFollowDD) {
            $puFollowDD = new PUFollowDD();

            $puFollowDD->setPUserId($userId);
            $puFollowDD->setPDDebateId($debateId);
            $puFollowDD->save();
            
            return $puFollowDD;
        }

        return null;
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
        $puFollowU = PUFollowUQuery::create()
            ->filterByPUserId($targetId)
            ->filterByPUserFollowerId($sourceId)
            ->findOne();

        if (!$puFollowU) {
            $puFollowU = new PUFollowU();

            $puFollowU->setPUserFollowerId($sourceId);
            $puFollowU->setPUserId($targetId);
            $puFollowU->save();
           return $puFollowU;
        }

        return null;
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
     * Create PUSubscribePNE
     *
     * @param integer $userId
     * @param array $emailIds
     */
    public function createUserSubscribeNotifEmail($userId, $emailIds)
    {
        foreach ($emailIds as $emailId) {
            $puSubscribePne = new PUSubscribePNE();

            $puSubscribePne->setPUserId($userId);
            $puSubscribePne->setPNEmailId($emailId);

            $puSubscribePne->save();
        }
    }
}
