<?php
namespace Politizr\FrontBundle\Lib\Manager;

use Politizr\Constant\NotificationConstants;
use Politizr\Constant\ObjectTypeConstants;
use Politizr\Constant\EmailConstants;

use Politizr\Model\PUNotification;
use Politizr\Model\PUSubscribePNE;

use Politizr\Model\PUser;
use Politizr\Model\PDDebate;

use Politizr\Model\PUNotificationQuery;
use Politizr\Model\PUSubscribePNEQuery;

/**
 * DB manager service for notification.
 *
 * @author Lionel Bouzonville
 */
class NotificationManager
{
    private $globalTools;

    private $logger;

    /**
     *
     * @param @politizr.tools.global
     * @param @logger
     */
    public function __construct(
        $globalTools,
        $logger
    ) {
        $this->globalTools = $globalTools;

        $this->logger = $logger;
    }

    /* ######################################################################################################## */
    /*                                           USEFUL FUNCTIONS                                               */
    /* ######################################################################################################## */

    /**
     * Array of screen notifications ids
     *
     * @return array
     */
    public function getScreenNotifIds()
    {
        $notifIds = array(
            NotificationConstants::ID_D_COMMENT_PUBLISH,
            NotificationConstants::ID_D_NOTE_POS,
            NotificationConstants::ID_D_NOTE_NEG,
            NotificationConstants::ID_D_D_REACTION_PUBLISH,
            NotificationConstants::ID_D_D_FOLLOWED,
            NotificationConstants::ID_D_R_REACTION_PUBLISH,
            NotificationConstants::ID_D_CIRCLE_DEBATE_PUBLISH,
            NotificationConstants::ID_D_C_NOTE_POS,
            NotificationConstants::ID_D_C_NOTE_NEG,
            NotificationConstants::ID_U_FOLLOWED,
            NotificationConstants::ID_U_BADGE,
            NotificationConstants::ID_S_T_USER,
            NotificationConstants::ID_S_T_DOCUMENT,
            NotificationConstants::ID_ADM_MESSAGE,
            NotificationConstants::ID_L_U_CITY,
            NotificationConstants::ID_L_U_DEPARTMENT,
            NotificationConstants::ID_L_U_REGION,
            NotificationConstants::ID_L_D_CITY,
            NotificationConstants::ID_L_D_DEPARTMENT,
            NotificationConstants::ID_L_D_REGION,
        );

        return $notifIds;
    }

    /* ######################################################################################################## */
    /*                                                  RAW SQL                                                 */
    /* ######################################################################################################## */


    /**
     * Screen user notifications
     *
     * @see app/sql/notifications.sql
     *
     * @param string $inQueryNotificationIds
     * @param string $inQueryTopicIds
     * @return string
     */
    private function createScreenUserNotificationsRawSql($inQueryNotificationIds, $inQueryTopicIds)
    {
        // Topic subrequest
        $subrequestTopic = "AND p_u_notification.p_c_topic_id is NULL";
        if ($inQueryTopicIds) {
            $subrequestTopic = "AND (p_u_notification.p_c_topic_id is NULL OR p_u_notification.p_c_topic_id IN ($inQueryTopicIds))";
        }

        $sql = "
SELECT *
FROM p_u_notification 
WHERE
p_u_notification.p_user_id = :p_user_id
AND (p_u_notification.created_at >= :created_at_min OR p_u_notification.checked = 0)
AND p_u_notification.p_notification_id IN ($inQueryNotificationIds)
$subrequestTopic

ORDER BY p_u_notification.created_at DESC
";

        return $sql;
    }

    /**
     * Count screen user notifications
     *
     * @see app/sql/notifications.sql
     *
     * @param string $inQueryNotificationIds
     * @param string $inQueryTopicIds
     * @return string
     */
    private function createCountScreenUserNotificationsRawSql($inQueryNotificationIds, $inQueryTopicIds)
    {
        // Topic subrequest
        $subrequestTopic = "AND p_u_notification.p_c_topic_id is NULL";
        if ($inQueryTopicIds) {
            $subrequestTopic = "AND (p_u_notification.p_c_topic_id is NULL OR p_u_notification.p_c_topic_id IN ($inQueryTopicIds))";
        }

        $sql = "
SELECT COUNT(id) as 'nb'
FROM p_u_notification 
WHERE
p_u_notification.p_user_id = :p_user_id
AND p_u_notification.checked = 0
AND p_u_notification.p_notification_id IN ($inQueryNotificationIds)
$subrequestTopic
";

        return $sql;
    }

    /**
     * User notifications used in emailing
     *
     * @see app/sql/notifications.sql
     *
     * @param string $inQueryTopicIds
     * @return string
     */
    private function createUserNotificationsForEmailingRawSql($inQueryTopicIds)
    {
        // Topic subrequest
        $subrequestTopic = "AND p_u_notification.p_c_topic_id is NULL";
        if ($inQueryTopicIds) {
            $subrequestTopic = "AND (p_u_notification.p_c_topic_id is NULL OR p_u_notification.p_c_topic_id IN ($inQueryTopicIds))";
        }

        $sql = "
SELECT *
FROM p_u_notification 
WHERE
p_u_notification.p_user_id = :p_user_id
AND p_u_notification.created_at >= :begin_at
AND p_u_notification.created_at <= :end_at
$subrequestTopic

ORDER BY p_u_notification.id ASC, p_u_notification.p_object_name ASC, p_u_notification.p_object_id ASC
";
        return $sql;
    }


    /**
     * Most interacted user publications (reactions/comments/note+) during period
     *
     * @see app/sql/accountNotifications.sql
     *
     * @param string $inQueryTopicIds
     * @return string
     */
    private function createMostInteractedUserPublicationsRawSql($inQueryTopicIds)
    {
        // Topic subrequest
        $subrequestTopic1 = "AND p_d_debate.p_c_topic_id is NULL";
        $subrequestTopic2 = "AND p_d_reaction.p_c_topic_id is NULL";
        if ($inQueryTopicIds) {
            $subrequestTopic1 = "AND (p_d_debate.p_c_topic_id is NULL OR p_d_debate.p_c_topic_id IN ($inQueryTopicIds))";
            $subrequestTopic2 = "AND (p_d_reaction.p_c_topic_id is NULL OR p_d_reaction.p_c_topic_id IN ($inQueryTopicIds))";
        }

        // Requête SQL
        $sql = "
( SELECT DISTINCT p_d_debate.id as id, p_d_debate.p_user_id as author_id, p_d_debate.title as title, p_d_debate.description as description, p_d_debate.note_pos as note_pos, p_d_debate.note_neg as note_neg, COUNT(distinct p_d_reaction_child.id) as nb_reactions, COUNT(distinct p_d_comment_child.id) as nb_comments, COUNT(distinct p_u_notification.id) as nb_notifications, p_d_debate.published_at as published_at, p_d_debate.updated_at as updated_at, 'Politizr\\\Model\\\PDDebate' as type
FROM p_d_debate
    LEFT JOIN p_d_reaction as p_d_reaction_child
        ON p_d_debate.id = p_d_reaction_child.p_d_debate_id
        AND p_d_reaction_child.published = 1
        AND p_d_reaction_child.online = 1
        AND p_d_reaction_child.published_at > :begin_at
        AND p_d_reaction_child.published_at < :end_at
    LEFT JOIN p_d_d_comment as p_d_comment_child
        ON p_d_debate.id = p_d_comment_child.p_d_debate_id
        AND p_d_comment_child.online = 1
        AND p_d_comment_child.created_at > :begin_at2
        AND p_d_comment_child.created_at < :end_at2
    LEFT JOIN p_u_notification as p_u_notification
        ON p_d_debate.id = p_u_notification.p_object_id
        AND p_u_notification.p_notification_id = :p_notification_id
        AND p_u_notification.p_object_name = 'Politizr\\\Model\\\PDDebate'
        AND p_u_notification.created_at > :begin_at3
        AND p_u_notification.created_at < :end_at3
WHERE
    p_d_debate.published = 1
    $subrequestTopic1
    AND p_d_debate.online = 1
    AND p_d_debate.p_user_id = :p_user_id

GROUP BY id
HAVING nb_reactions > 0 OR nb_comments > 0 OR nb_notifications > 0
)

UNION DISTINCT

( SELECT DISTINCT p_d_reaction.id as id, p_d_reaction.p_user_id as author_id, p_d_reaction.title as title, p_d_reaction.description as description, p_d_reaction.note_pos as note_pos, p_d_reaction.note_neg as note_neg, COUNT(distinct p_d_reaction_child.id) as nb_reactions, COUNT(distinct p_d_comment_child.id) as nb_comments, COUNT(distinct p_u_notification.id) as nb_notifications, p_d_reaction.published_at as published_at, p_d_reaction.updated_at as updated_at,'Politizr\\\Model\\\PDReaction' as type
FROM p_d_reaction
    LEFT JOIN p_d_reaction as p_d_reaction_child
        ON p_d_reaction.id = p_d_reaction_child.parent_reaction_id
        AND p_d_reaction_child.published = 1
        AND p_d_reaction_child.online = 1
        AND p_d_reaction_child.published_at > :begin_at4
        AND p_d_reaction_child.published_at < :end_at4
    LEFT JOIN p_d_r_comment as p_d_comment_child
        ON p_d_reaction.id = p_d_comment_child.p_d_reaction_id
        AND p_d_comment_child.online = 1
        AND p_d_comment_child.created_at > :begin_at5
        AND p_d_comment_child.created_at < :end_at5
    LEFT JOIN p_u_notification as p_u_notification
        ON p_d_reaction.id = p_u_notification.p_object_id
        AND p_u_notification.p_notification_id = :p_notification_id2
        AND p_u_notification.p_object_name = 'Politizr\\\Model\\\PDReaction'
        AND p_u_notification.created_at > :begin_at6
        AND p_u_notification.created_at < :end_at6
WHERE
    p_d_reaction.published = 1
    AND p_d_reaction.online = 1
    AND p_d_reaction.tree_level > 0
    $subrequestTopic2
    AND p_d_reaction.p_user_id = :p_user_id2

GROUP BY id
HAVING nb_reactions > 0 OR nb_comments > 0 OR nb_notifications > 0
)

ORDER BY nb_reactions DESC, nb_comments DESC, note_pos DESC, nb_notifications DESC, note_neg ASC

LIMIT :limit
";

        return $sql;
    }

    /**
     * Most followed user publications (reactions>subjects>comments by note+) during period
     *
     * @see app/sql/accountNotifications.sql
     *
     * @param string $inQueryUserIds
     * @param string $inQueryTopicIds
     * @param string $inQueryNotInPDDebateIds
     * @param string $inQueryNotInPDReactionIds
     * @param string $inQueryNotInPDDCommentIds
     * @param string $inQueryNotInPDRCommentIds
     * @return string
     */
    private function createMostInteractedFollowedUserPublicationsRawSql($inQueryUserIds, $inQueryTopicIds, $inQueryNotInPDDebateIds, $inQueryNotInPDReactionIds, $inQueryNotInPDDCommentIds, $inQueryNotInPDRCommentIds)
    {
        // Topic subrequest
        $subrequestTopic1 = "AND p_d_debate.p_c_topic_id is NULL";
        $subrequestTopic2 = "AND p_d_reaction.p_c_topic_id is NULL";
        if ($inQueryTopicIds) {
            $subrequestTopic1 = "AND (p_d_debate.p_c_topic_id is NULL OR p_d_debate.p_c_topic_id IN ($inQueryTopicIds))";
            $subrequestTopic2 = "AND (p_d_reaction.p_c_topic_id is NULL OR p_d_reaction.p_c_topic_id IN ($inQueryTopicIds))";
        }

        // Requête SQL
        $sql = "
( SELECT p_d_debate.id as id, p_d_debate.p_user_id as author_id, p_d_debate.title as title, p_d_debate.description as description, p_d_debate.published_at as published_at, p_d_debate.note_pos as note_pos, p_d_debate.note_neg as note_neg, COUNT(distinct id) as nb_subjects, 0 as nb_reactions, 0 as nb_comments, 'Politizr\\\Model\\\PDDebate' as type
FROM p_d_debate
WHERE
    p_d_debate.published = 1
    AND p_d_debate.online = 1
    $subrequestTopic1
    AND p_d_debate.id NOT IN ($inQueryNotInPDDebateIds)
    AND p_d_debate.p_user_id IN ($inQueryUserIds)
    AND p_d_debate.published_at > :begin_at
    AND p_d_debate.published_at < :end_at

GROUP BY id
)

UNION DISTINCT

# Réactions des users suivis
( SELECT p_d_reaction.id as id, p_d_reaction.p_user_id as author_id, p_d_reaction.title as title, p_d_reaction.description as description, p_d_reaction.published_at as published_at, p_d_reaction.note_pos as note_pos, p_d_reaction.note_neg as note_neg, 0 as nb_subjects, COUNT(distinct id) as nb_reactions, 0 as nb_comments, 'Politizr\\\Model\\\PDReaction' as type
FROM p_d_reaction
WHERE
    p_d_reaction.published = 1
    AND p_d_reaction.online = 1
    $subrequestTopic2
    AND p_d_reaction.id NOT IN ($inQueryNotInPDReactionIds)
    AND p_d_reaction.p_user_id IN ($inQueryUserIds)
    AND p_d_reaction.published_at > :begin_at2
    AND p_d_reaction.published_at < :end_at2

GROUP BY id
)

UNION DISTINCT

# Commentaires débats des users suivis
( SELECT p_d_d_comment.id as id, p_d_d_comment.p_user_id as author_id, \"commentaire\" as title, p_d_d_comment.description as description, p_d_d_comment.published_at as published_at, p_d_d_comment.note_pos as note_pos, p_d_d_comment.note_neg as note_neg, 0 as nb_subjects, 0 as nb_reactions, COUNT(distinct p_d_d_comment.id) as nb_comments, 'Politizr\\\Model\\\PDDComment' as type
FROM p_d_d_comment
    LEFT JOIN p_d_debate
        ON p_d_d_comment.p_d_debate_id = p_d_debate.id
WHERE
    p_d_d_comment.online = 1
    AND p_d_debate.published = 1
    AND p_d_debate.online = 1
    $subrequestTopic1
    AND p_d_d_comment.id NOT IN ($inQueryNotInPDDCommentIds)
    AND p_d_d_comment.p_user_id IN ($inQueryUserIds)
    AND p_d_d_comment.published_at > :begin_at3
    AND p_d_d_comment.published_at < :end_at3

GROUP BY id
)

UNION DISTINCT

# Commentaires réactions des users suivis
( SELECT p_d_r_comment.id as id, p_d_r_comment.p_user_id as author_id, \"commentaire\" as title, p_d_r_comment.description as description, p_d_r_comment.published_at as published_at, p_d_r_comment.note_pos as note_pos, p_d_r_comment.note_neg as note_neg, 0 as nb_subjects, 0 as nb_reactions, COUNT(distinct p_d_r_comment.id) as nb_comments, 'Politizr\\\Model\\\PDRComment' as type
FROM p_d_r_comment
    LEFT JOIN p_d_reaction
        ON p_d_r_comment.p_d_reaction_id = p_d_reaction.id
WHERE
    p_d_r_comment.online = 1
    AND p_d_reaction.published = 1
    AND p_d_reaction.online = 1
    $subrequestTopic2
    AND p_d_r_comment.id NOT IN ($inQueryNotInPDRCommentIds)
    AND p_d_r_comment.p_user_id IN ($inQueryUserIds)
    AND p_d_r_comment.published_at > :begin_at4
    AND p_d_r_comment.published_at < :end_at4

GROUP BY id
)

ORDER BY nb_reactions DESC, nb_subjects DESC, nb_comments DESC, note_pos DESC, note_neg ASC

LIMIT :limit
";

        return $sql;
    }

    /**
     * Most interacted followed debates publications (reactions/comments) during period
     *
     * @see app/sql/accountNotifications.sql
     *
     * @param string $inQueryDebateIds
     * @param string $inQueryTopicIds
     * @return string
     */
    private function createMostInteractedFollowedDebatesPublicationsRawSql($inQueryDebateIds, $inQueryTopicIds)
    {
        // Topic subrequest
        $subrequestTopic1 = "AND p_d_debate.p_c_topic_id is NULL";
        $subrequestTopic2 = "AND p_d_reaction.p_c_topic_id is NULL";
        if ($inQueryTopicIds) {
            $subrequestTopic1 = "AND (p_d_debate.p_c_topic_id is NULL OR p_d_debate.p_c_topic_id IN ($inQueryTopicIds))";
            $subrequestTopic2 = "AND (p_d_reaction.p_c_topic_id is NULL OR p_d_reaction.p_c_topic_id IN ($inQueryTopicIds))";
        }

        // Requête SQL
        $sql = "
( SELECT p_d_reaction.id as id, p_d_reaction.p_user_id as author_id, p_d_reaction.title as title, p_d_reaction.description as description, p_d_reaction.published_at as published_at, p_d_reaction.note_pos as note_pos, p_d_reaction.note_neg as note_neg, 0 as nb_subjects, COUNT(distinct p_d_reaction.id) as nb_reactions, 0 as nb_comments, 'Politizr\\\Model\\\PDReaction' as type
FROM p_d_reaction
WHERE
    p_d_reaction.published = 1
    AND p_d_reaction.online = 1
    AND p_d_reaction.p_d_debate_id IN ($inQueryDebateIds)
    AND p_d_reaction.tree_level > 0
    $subrequestTopic2
    AND p_d_reaction.p_user_id <> :p_user_id
    AND p_d_reaction.published_at > :begin_at
    AND p_d_reaction.published_at < :end_at

GROUP BY p_d_reaction.id
)

UNION DISTINCT

( SELECT p_d_d_comment.id as id, p_d_d_comment.p_user_id as author_id, \"commentaire\" as title, p_d_d_comment.description as description, p_d_d_comment.published_at as published_at, p_d_d_comment.note_pos as note_pos, p_d_d_comment.note_neg as note_neg, 0 as nb_subjects, 0 as nb_reactions, COUNT(distinct p_d_d_comment.id) as nb_comments, 'Politizr\\\Model\\\PDDComment' as type
FROM p_d_d_comment
    LEFT JOIN p_d_debate
        ON p_d_d_comment.p_d_debate_id = p_d_debate.id
WHERE
    p_d_d_comment.online = 1
    AND p_d_debate.published = 1
    AND p_d_debate.online = 1
    $subrequestTopic1
    AND p_d_d_comment.p_d_debate_id IN ($inQueryDebateIds)
    AND p_d_d_comment.p_user_id <> :p_user_id2
    AND p_d_d_comment.published_at > :begin_at2
    AND p_d_d_comment.published_at < :end_at2

GROUP BY p_d_d_comment.id
)

UNION DISTINCT

( SELECT p_d_r_comment.id as id, p_d_r_comment.p_user_id as author_id, \"commentaire\" as title, p_d_r_comment.description as description, p_d_r_comment.published_at as published_at, p_d_r_comment.note_pos as note_pos, p_d_r_comment.note_neg as note_neg, 0 as nb_subjects, 0 as nb_reactions, COUNT(distinct p_d_r_comment.id) as nb_comments, 'Politizr\\\Model\\\PDRComment' as type
FROM p_d_r_comment
    LEFT JOIN p_d_reaction
        ON p_d_r_comment.p_d_reaction_id = p_d_reaction.id
WHERE
    p_d_r_comment.online = 1
    AND p_d_reaction.published = 1
    AND p_d_reaction.online = 1
    $subrequestTopic2
    AND p_d_reaction.p_d_debate_id IN ($inQueryDebateIds)
    AND p_d_r_comment.p_user_id <> :p_user_id3
    AND p_d_r_comment.published_at > :begin_at3
    AND p_d_r_comment.published_at < :end_at3

GROUP BY p_d_r_comment.id
)

ORDER BY nb_reactions DESC, nb_subjects DESC, nb_comments DESC, note_pos DESC, note_neg ASC

LIMIT :limit
";

        return $sql;
    }

    /**
     * Nearest user's qualified users during period
     *
     * @see app/sql/newsNotifications.sql
     *
     * @return string
     */
    private function createNearestQualifiedUsersRawSql()
    {
        // Requête SQL
        $sql = "
SELECT DISTINCT
".ObjectTypeConstants::SQL_P_USER_COLUMNS."
FROM ( 

SELECT DISTINCT p_user.*, 1 as unionsorting
FROM p_user
WHERE
    p_user.qualified = 1
    AND p_user.online = 1
    AND p_user.p_u_status_id = 1
    AND p_user.created_at > :begin_at
    AND p_user.created_at < :end_at
    AND p_user.id <> :p_user_id
    AND p_user.p_l_city_id = :p_l_city_id

UNION DISTINCT

SELECT DISTINCT p_user.*, 2 as unionsorting
FROM p_user
    LEFT JOIN p_l_city as p_l_city
        ON p_user.p_l_city_id = p_l_city.id
    LEFT JOIN p_l_department as p_l_department
        ON p_l_city.p_l_department_id = p_l_department.id
WHERE
    p_user.qualified = 1
    AND p_user.online = 1
    AND p_user.p_u_status_id = 1
    AND p_user.created_at > :begin_at2
    AND p_user.created_at < :end_at2
    AND p_user.id <> :p_user_id2
    AND p_l_department.id = :p_l_department_id

UNION DISTINCT

SELECT DISTINCT p_user.*, 3 as unionsorting
FROM p_user
    LEFT JOIN p_l_city as p_l_city
        ON p_user.p_l_city_id = p_l_city.id
    LEFT JOIN p_l_department as p_l_department
        ON p_l_city.p_l_department_id = p_l_department.id
    LEFT JOIN p_l_region as p_l_region
        ON p_l_department.p_l_region_id = p_l_region.id
WHERE
    p_user.qualified = 1
    AND p_user.online = 1
    AND p_user.p_u_status_id = 1
    AND p_user.created_at > :begin_at3
    AND p_user.created_at < :end_at3
    AND p_user.id <> :p_user_id3
    AND p_l_region.id = :p_l_region_id

ORDER BY unionsorting ASC

) unionsorting

LIMIT :limit
";

        return $sql;
    }

    /**
     * Nearest user's debates during period
     *
     * @see app/sql/newsNotifications.sql
     *
     * @param string $inQueryDebateIds
     * @param string $inQueryUserIds
     * @param string $inQueryTagIds
     * @param string $inQueryTopicIds
     * @return string
     */
    private function createNearestDebatesRawSql($inQueryDebateIds, $inQueryUserIds, $inQueryTagIds, $inQueryTopicIds)
    {
        // Topic subrequest
        $subrequestTopic = "AND p_d_debate.p_c_topic_id is NULL";
        if ($inQueryTopicIds) {
            $subrequestTopic = "AND (p_d_debate.p_c_topic_id is NULL OR p_d_debate.p_c_topic_id IN ($inQueryTopicIds))";
        }

        // Requête SQL
        $sql = "
SELECT DISTINCT
".ObjectTypeConstants::SQL_P_D_DEBATE_COLUMNS.",
    nb_users
FROM (
( SELECT DISTINCT p_d_debate.*, COUNT(p_u_follow_d_d.p_d_debate_id) as nb_users, 1 as unionsorting
FROM p_d_debate
    LEFT JOIN p_u_follow_d_d
        ON p_d_debate.id = p_u_follow_d_d.p_d_debate_id
WHERE
    p_d_debate.p_l_city_id = :p_l_city_id
    AND p_d_debate.online = 1
    AND p_d_debate.published = 1
    $subrequestTopic
    AND p_d_debate.created_at > :begin_at
    AND p_d_debate.created_at < :end_at
    AND p_d_debate.id NOT IN ($inQueryDebateIds)
    AND p_d_debate.p_user_id NOT IN ($inQueryUserIds)
    AND p_d_debate.p_user_id <> :p_user_id
GROUP BY p_d_debate.id
)

UNION DISTINCT

( SELECT DISTINCT p_d_debate.*, COUNT(p_u_follow_d_d.p_d_debate_id) as nb_users, 2 as unionsorting
FROM p_d_debate
    LEFT JOIN p_u_follow_d_d
        ON p_d_debate.id = p_u_follow_d_d.p_d_debate_id
WHERE
    p_d_debate.p_l_department_id = :p_l_department_id
    AND p_d_debate.online = 1
    AND p_d_debate.published = 1
    $subrequestTopic
    AND p_d_debate.created_at > :begin_at2
    AND p_d_debate.created_at < :end_at2
    AND p_d_debate.id NOT IN ($inQueryDebateIds)
    AND p_d_debate.p_user_id NOT IN ($inQueryUserIds)
    AND p_d_debate.p_user_id <> :p_user_id2
GROUP BY p_d_debate.id
)

UNION DISTINCT

( SELECT DISTINCT p_d_debate.*, COUNT(p_u_follow_d_d.p_d_debate_id) as nb_users, 3 as unionsorting
FROM p_d_debate
    LEFT JOIN p_u_follow_d_d
        ON p_d_debate.id = p_u_follow_d_d.p_d_debate_id
    LEFT JOIN p_d_d_tagged_t
        ON p_d_debate.id = p_d_d_tagged_t.p_d_debate_id
WHERE
    p_d_debate.online = 1 
    AND p_d_debate.published = 1
    $subrequestTopic
    AND p_d_debate.created_at > :begin_at3
    AND p_d_debate.created_at < :end_at3
    AND p_d_d_tagged_t.p_tag_id IN ($inQueryTagIds)
    AND p_d_debate.id NOT IN ($inQueryDebateIds)
    AND p_d_debate.p_user_id NOT IN ($inQueryUserIds)
    AND p_d_debate.p_user_id <> :p_user_id3
GROUP BY p_d_debate.id
)

UNION DISTINCT

( SELECT DISTINCT p_d_debate.*, COUNT(p_u_follow_d_d.p_d_debate_id) as nb_users, 4 as unionsorting
FROM p_d_debate
    LEFT JOIN p_u_follow_d_d
        ON p_d_debate.id = p_u_follow_d_d.p_d_debate_id
WHERE
    p_d_debate.p_l_region_id = :p_l_region_id
    AND p_d_debate.online = 1
    AND p_d_debate.published = 1
    $subrequestTopic
    AND p_d_debate.created_at > :begin_at4
    AND p_d_debate.created_at < :end_at4
    AND p_d_debate.id NOT IN ($inQueryDebateIds)
    AND p_d_debate.p_user_id NOT IN ($inQueryUserIds)
    AND p_d_debate.p_user_id <> :p_user_id4
GROUP BY p_d_debate.id
)

UNION DISTINCT

( SELECT DISTINCT p_d_debate.*, COUNT(p_u_follow_d_d.p_d_debate_id) as nb_users, 5 as unionsorting
FROM p_d_debate
    LEFT JOIN p_u_follow_d_d
        ON p_d_debate.id = p_u_follow_d_d.p_d_debate_id
WHERE
    p_d_debate.online = 1
    AND p_d_debate.published = 1
    $subrequestTopic
    AND p_d_debate.created_at > :begin_at5
    AND p_d_debate.created_at < :end_at5
    AND p_d_debate.id NOT IN ($inQueryDebateIds)
    AND p_d_debate.p_user_id NOT IN ($inQueryUserIds)
    AND p_d_debate.p_user_id <> :p_user_id5
GROUP BY p_d_debate.id
HAVING nb_users >= :min_nb_followers
)

ORDER BY unionsorting ASC, note_pos DESC, note_neg ASC, nb_users DESC, published_at DESC
) unionsorting

GROUP BY p_user_id
ORDER BY unionsorting ASC, note_pos DESC, note_neg ASC, nb_users DESC, published_at DESC

LIMIT :limit
";

        return $sql;
    }

    /**
     * Most interesting user's debates during period
     *
     * @see app/sql/newsNotifications.sql
     *
     * @param string $inQueryDebateIds
     * @param string $inQueryUserIds
     * @param string $inQueryTagIds
     * @param string $inQueryTopicIds
     * @return string
     */
    private function createMostInterestingDebatesRawSql($inQueryDebateIds, $inQueryUserIds, $inQueryTagIds, $inQueryTopicIds)
    {
        // Topic subrequest
        $subrequestTopic = "AND p_d_debate.p_c_topic_id is NULL";
        if ($inQueryTopicIds) {
            $subrequestTopic = "AND (p_d_debate.p_c_topic_id is NULL OR p_d_debate.p_c_topic_id IN ($inQueryTopicIds))";
        }

        // Requête SQL
        $sql = "
SELECT DISTINCT
".ObjectTypeConstants::SQL_P_D_DEBATE_COLUMNS.",
    nb_users,
    unionsorting
FROM (
( SELECT DISTINCT p_d_debate.*, COUNT(p_u_follow_d_d.p_d_debate_id) as nb_users, 1 as unionsorting
FROM p_d_debate
    LEFT JOIN p_u_follow_d_d
        ON p_d_debate.id = p_u_follow_d_d.p_d_debate_id
    LEFT JOIN p_d_d_tagged_t
        ON p_d_debate.id = p_d_d_tagged_t.p_d_debate_id
WHERE
    p_d_debate.online = 1 
    AND p_d_debate.published = 1
    $subrequestTopic
    AND p_d_debate.created_at > :begin_at
    AND p_d_debate.created_at < :end_at
    AND p_d_d_tagged_t.p_tag_id IN ($inQueryTagIds)
    AND p_d_debate.id NOT IN ($inQueryDebateIds)
    AND p_d_debate.p_user_id NOT IN ($inQueryUserIds)
    AND p_d_debate.p_user_id <> :p_user_id
GROUP BY p_d_debate.id
)

UNION DISTINCT

( SELECT DISTINCT p_d_debate.*, COUNT(p_u_follow_d_d.p_d_debate_id) as nb_users, 2 as unionsorting
FROM p_d_debate
    LEFT JOIN p_u_follow_d_d
        ON p_d_debate.id = p_u_follow_d_d.p_d_debate_id
WHERE
    p_d_debate.online = 1
    AND p_d_debate.published = 1
    $subrequestTopic
    AND p_d_debate.created_at > :begin_at2
    AND p_d_debate.created_at < :end_at2
    AND p_d_debate.id NOT IN ($inQueryDebateIds)
    AND p_d_debate.p_user_id NOT IN ($inQueryUserIds)
    AND p_d_debate.p_user_id <> :p_user_id2
GROUP BY p_d_debate.id
HAVING nb_users >= :min_nb_followers
)

ORDER BY unionsorting ASC, note_pos DESC, note_neg ASC, nb_users DESC, published_at DESC
) unionsorting

GROUP BY p_user_id, ".ObjectTypeConstants::SQL_P_D_DEBATE_COLUMNS."
ORDER BY unionsorting ASC, note_pos DESC, note_neg ASC, nb_users DESC, published_at DESC

LIMIT :limit
";

        return $sql;
    }

    /* ######################################################################################################## */
    /*                                            RAW SQL OPERATIONS                                            */
    /* ######################################################################################################## */

    /**
     * Interacted user documents listing
     *
     * @param string $inQueryTopicIds
     * @param int $userId
     * @param string $beginAt
     * @param string $endAt
     * @param integer $limit
     * @return PropelCollection
     */
    public function generateMostInteractedUserPublications($inQueryTopicIds, $userId, $beginAt, $endAt, $limit)
    {
        $this->logger->info('*** generateMostInteractedUserPublications');
        $this->logger->info('$inQueryTopicIds = ' . print_r($inQueryTopicIds, true));
        $this->logger->info('$userId = ' . print_r($userId, true));
        $this->logger->info('$beginAt = ' . print_r($beginAt, true));
        $this->logger->info('$endAt = ' . print_r($endAt, true));
        $this->logger->info('$limit = ' . print_r($limit, true));

        $con = \Propel::getConnection('default', \Propel::CONNECTION_READ);

        $stmt = $con->prepare($this->createMostInteractedUserPublicationsRawSql($inQueryTopicIds));

        $stmt->bindValue(':p_user_id', $userId, \PDO::PARAM_INT);
        $stmt->bindValue(':p_user_id2', $userId, \PDO::PARAM_INT);
        $stmt->bindValue(':p_notification_id', NotificationConstants::ID_D_NOTE_POS, \PDO::PARAM_INT);
        $stmt->bindValue(':p_notification_id2', NotificationConstants::ID_D_NOTE_POS, \PDO::PARAM_INT);
        $stmt->bindValue(':begin_at', $beginAt, \PDO::PARAM_STR);
        $stmt->bindValue(':begin_at2', $beginAt, \PDO::PARAM_STR);
        $stmt->bindValue(':begin_at3', $beginAt, \PDO::PARAM_STR);
        $stmt->bindValue(':begin_at4', $beginAt, \PDO::PARAM_STR);
        $stmt->bindValue(':begin_at5', $beginAt, \PDO::PARAM_STR);
        $stmt->bindValue(':begin_at6', $beginAt, \PDO::PARAM_STR);
        $stmt->bindValue(':end_at', $endAt, \PDO::PARAM_STR);
        $stmt->bindValue(':end_at2', $endAt, \PDO::PARAM_STR);
        $stmt->bindValue(':end_at3', $endAt, \PDO::PARAM_STR);
        $stmt->bindValue(':end_at4', $endAt, \PDO::PARAM_STR);
        $stmt->bindValue(':end_at5', $endAt, \PDO::PARAM_STR);
        $stmt->bindValue(':end_at6', $endAt, \PDO::PARAM_STR);
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_STR);

        $stmt->execute();

        $result = $stmt->fetchAll();

        $publications = $this->globalTools->hydrateInteractedPublication($result, $beginAt, $endAt);

        return $publications;
    }

    /**
     * Most interacted followed user documents listing
     *
     * @param string $inQueryUserIds
     * @param string $inQueryTopicIds
     * @param string $inQueryNotInPDDebateIds
     * @param string $inQueryNotInPDReactionIds
     * @param string $inQueryNotInPDDCommentIds
     * @param string $inQueryNotInPDRCommentIds
     * @param string $beginAt
     * @param string $endAt
     * @param integer $limit
     * @return array[InteractedPublication]
     */
    public function generateMostInteractedFollowedUserPublications($inQueryUserIds, $inQueryTopicIds, $inQueryNotInPDDebateIds, $inQueryNotInPDReactionIds, $inQueryNotInPDDCommentIds, $inQueryNotInPDRCommentIds, $beginAt, $endAt, $limit)
    {
        $this->logger->info('*** generateMostFollowedUserPublications');
        $this->logger->info('$inQueryUserIds = ' . print_r($inQueryUserIds, true));
        $this->logger->info('$inQueryTopicIds = ' . print_r($inQueryUserIds, true));
        $this->logger->info('$inQueryNotInPDDebateIds = ' . print_r($inQueryUserIds, true));
        $this->logger->info('$inQueryNotInPDReactionIds = ' . print_r($inQueryUserIds, true));
        $this->logger->info('$inQueryNotInPDDCommentIds = ' . print_r($inQueryUserIds, true));
        $this->logger->info('$inQueryNotInPDRCommentIds = ' . print_r($inQueryUserIds, true));
        $this->logger->info('$beginAt = ' . print_r($beginAt, true));
        $this->logger->info('$endAt = ' . print_r($endAt, true));
        $this->logger->info('$limit = ' . print_r($limit, true));

        $con = \Propel::getConnection('default', \Propel::CONNECTION_READ);

        $stmt = $con->prepare($this->createMostInteractedFollowedUserPublicationsRawSql($inQueryUserIds, $inQueryTopicIds, $inQueryNotInPDDebateIds, $inQueryNotInPDReactionIds, $inQueryNotInPDDCommentIds, $inQueryNotInPDRCommentIds));

        $stmt->bindValue(':begin_at', $beginAt, \PDO::PARAM_STR);
        $stmt->bindValue(':begin_at2', $beginAt, \PDO::PARAM_STR);
        $stmt->bindValue(':begin_at3', $beginAt, \PDO::PARAM_STR);
        $stmt->bindValue(':begin_at4', $beginAt, \PDO::PARAM_STR);
        $stmt->bindValue(':end_at', $endAt, \PDO::PARAM_STR);
        $stmt->bindValue(':end_at2', $endAt, \PDO::PARAM_STR);
        $stmt->bindValue(':end_at3', $endAt, \PDO::PARAM_STR);
        $stmt->bindValue(':end_at4', $endAt, \PDO::PARAM_STR);
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_STR);

        $stmt->execute();

        $result = $stmt->fetchAll();

        $publications = $this->globalTools->hydrateInteractedPublication($result, $beginAt, $endAt);

        return $publications;
    }

    /**
     * Most interacted followed debates publications documents listing
     *
     * @param int $inQueryDebateIds
     * @param string $inQueryTopicIds
     * @param int $userId
     * @param string $beginAt
     * @param string $endAt
     * @param integer $limit
     * @return array[InteractedPublication]
     */
    public function generateMostInteractedFollowedDebatesPublications($inQueryDebateIds, $inQueryTopicIds, $userId, $beginAt, $endAt, $limit)
    {
        $this->logger->info('*** generateMostInteractedFollowedDebatesPublications');
        $this->logger->info('$inQueryDebateIds = ' . print_r($inQueryDebateIds, true));
        $this->logger->info('$inQueryTopicIds = ' . print_r($inQueryTopicIds, true));
        $this->logger->info('$userId = ' . print_r($userId, true));
        $this->logger->info('$beginAt = ' . print_r($beginAt, true));
        $this->logger->info('$endAt = ' . print_r($endAt, true));
        $this->logger->info('$limit = ' . print_r($limit, true));

        $con = \Propel::getConnection('default', \Propel::CONNECTION_READ);

        $stmt = $con->prepare($this->createMostInteractedFollowedDebatesPublicationsRawSql($inQueryDebateIds, $inQueryTopicIds));

        $stmt->bindValue(':p_user_id', $userId, \PDO::PARAM_INT);
        $stmt->bindValue(':p_user_id2', $userId, \PDO::PARAM_INT);
        $stmt->bindValue(':p_user_id3', $userId, \PDO::PARAM_INT);
        $stmt->bindValue(':begin_at', $beginAt, \PDO::PARAM_STR);
        $stmt->bindValue(':begin_at2', $beginAt, \PDO::PARAM_STR);
        $stmt->bindValue(':begin_at3', $beginAt, \PDO::PARAM_STR);
        $stmt->bindValue(':end_at', $endAt, \PDO::PARAM_STR);
        $stmt->bindValue(':end_at2', $endAt, \PDO::PARAM_STR);
        $stmt->bindValue(':end_at3', $endAt, \PDO::PARAM_STR);
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_STR);

        $stmt->execute();

        $result = $stmt->fetchAll();

        $publications = $this->globalTools->hydrateInteractedPublication($result, $beginAt, $endAt);

        return $publications;
    }

    /**
     * Nearest user's qualified users listing
     *
     * @param int $userId
     * @param int $cityId
     * @param int $departmentId
     * @param int $regionId
     * @param string $beginAt
     * @param string $endAt
     * @param integer $limit
     * @return PropelCollection
     */
    public function generateNearestQualifiedUsers($userId, $cityId, $departmentId, $regionId, $beginAt, $endAt, $limit)
    {
        $this->logger->info('*** generateNearestQualifiedUsers');
        $this->logger->info('$userId = ' . print_r($userId, true));
        $this->logger->info('$cityId = ' . print_r($cityId, true));
        $this->logger->info('$departmentId = ' . print_r($departmentId, true));
        $this->logger->info('$regionId = ' . print_r($regionId, true));
        $this->logger->info('$beginAt = ' . print_r($beginAt, true));
        $this->logger->info('$endAt = ' . print_r($endAt, true));
        $this->logger->info('$limit = ' . print_r($limit, true));

        $con = \Propel::getConnection('default', \Propel::CONNECTION_READ);

        $stmt = $con->prepare($this->createNearestQualifiedUsersRawSql());

        $stmt->bindValue(':p_user_id', $userId, \PDO::PARAM_INT);
        $stmt->bindValue(':p_user_id2', $userId, \PDO::PARAM_INT);
        $stmt->bindValue(':p_user_id3', $userId, \PDO::PARAM_INT);
        $stmt->bindValue(':p_l_city_id', $cityId, \PDO::PARAM_INT);
        $stmt->bindValue(':p_l_department_id', $departmentId, \PDO::PARAM_INT);
        $stmt->bindValue(':p_l_region_id', $regionId, \PDO::PARAM_INT);
        $stmt->bindValue(':begin_at', $beginAt, \PDO::PARAM_STR);
        $stmt->bindValue(':begin_at2', $beginAt, \PDO::PARAM_STR);
        $stmt->bindValue(':begin_at3', $beginAt, \PDO::PARAM_STR);
        $stmt->bindValue(':end_at', $endAt, \PDO::PARAM_STR);
        $stmt->bindValue(':end_at2', $endAt, \PDO::PARAM_STR);
        $stmt->bindValue(':end_at3', $endAt, \PDO::PARAM_STR);
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_STR);

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

    /**
     * Nearest user's debates listing
     *
     * @param string $inQueryDebateIds
     * @param string $inQueryUserIds
     * @param string $inQueryTagIds
     * @param string $inQueryTopicIds
     * @param int $userId
     * @param int $cityId
     * @param int $departmentId
     * @param int $regionId
     * @param string $beginAt
     * @param string $endAt
     * @param integer $limit
     * @return PropelCollection
     */
    public function generateNearestDebates($inQueryDebateIds, $inQueryUserIds, $inQueryTagIds, $inQueryTopicIds, $userId, $cityId, $departmentId, $regionId, $beginAt, $endAt, $limit)
    {
        $this->logger->info('*** generateNearestQualifiedUsers');
        $this->logger->info('$inQueryDebateIds = ' . print_r($inQueryDebateIds, true));
        $this->logger->info('$inQueryUserIds = ' . print_r($inQueryUserIds, true));
        $this->logger->info('$inQueryTagIds = ' . print_r($inQueryTagIds, true));
        $this->logger->info('$inQueryTopicIds = ' . print_r($inQueryTopicIds, true));
        $this->logger->info('$userId = ' . print_r($userId, true));
        $this->logger->info('$cityId = ' . print_r($cityId, true));
        $this->logger->info('$departmentId = ' . print_r($departmentId, true));
        $this->logger->info('$regionId = ' . print_r($regionId, true));
        $this->logger->info('$beginAt = ' . print_r($beginAt, true));
        $this->logger->info('$endAt = ' . print_r($endAt, true));
        $this->logger->info('$limit = ' . print_r($limit, true));

        $con = \Propel::getConnection('default', \Propel::CONNECTION_READ);

        $stmt = $con->prepare($this->createNearestDebatesRawSql($inQueryDebateIds, $inQueryUserIds, $inQueryTagIds, $inQueryTopicIds));

        $stmt->bindValue(':p_user_id', $userId, \PDO::PARAM_INT);
        $stmt->bindValue(':p_user_id2', $userId, \PDO::PARAM_INT);
        $stmt->bindValue(':p_user_id3', $userId, \PDO::PARAM_INT);
        $stmt->bindValue(':p_user_id4', $userId, \PDO::PARAM_INT);
        $stmt->bindValue(':p_user_id5', $userId, \PDO::PARAM_INT);
        $stmt->bindValue(':p_l_city_id', $cityId, \PDO::PARAM_INT);
        $stmt->bindValue(':p_l_department_id', $departmentId, \PDO::PARAM_INT);
        $stmt->bindValue(':p_l_region_id', $regionId, \PDO::PARAM_INT);
        $stmt->bindValue(':begin_at', $beginAt, \PDO::PARAM_STR);
        $stmt->bindValue(':begin_at2', $beginAt, \PDO::PARAM_STR);
        $stmt->bindValue(':begin_at3', $beginAt, \PDO::PARAM_STR);
        $stmt->bindValue(':begin_at4', $beginAt, \PDO::PARAM_STR);
        $stmt->bindValue(':begin_at5', $beginAt, \PDO::PARAM_STR);
        $stmt->bindValue(':end_at', $endAt, \PDO::PARAM_STR);
        $stmt->bindValue(':end_at2', $endAt, \PDO::PARAM_STR);
        $stmt->bindValue(':end_at3', $endAt, \PDO::PARAM_STR);
        $stmt->bindValue(':end_at4', $endAt, \PDO::PARAM_STR);
        $stmt->bindValue(':end_at5', $endAt, \PDO::PARAM_STR);
        $stmt->bindValue(':min_nb_followers', EmailConstants::NB_MIN_FOLLOWERS, \PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_STR);

        $stmt->execute();

        $result = $stmt->fetchAll();

        $debates = new \PropelCollection();
        foreach ($result as $row) {
            $debate = new PDDebate();
            $debate->hydrate($row);

            $debates->append($debate);
        }


        return $debates;
    }

    /**
     * Most interesting user's debates listing
     *
     * @param string $inQueryDebateIds
     * @param string $inQueryUserIds
     * @param string $inQueryTagIds
     * @param string $inQueryTopicIds
     * @param int $userId
     * @param string $beginAt
     * @param string $endAt
     * @param integer $limit
     * @return PropelCollection
     */
    public function generateMostInterestingDebates($inQueryDebateIds, $inQueryUserIds, $inQueryTagIds, $inQueryTopicIds, $userId, $beginAt, $endAt, $limit)
    {
        $this->logger->info('*** generateNearestQualifiedUsers');
        $this->logger->info('$inQueryDebateIds = ' . print_r($inQueryDebateIds, true));
        $this->logger->info('$inQueryUserIds = ' . print_r($inQueryUserIds, true));
        $this->logger->info('$inQueryTagIds = ' . print_r($inQueryTagIds, true));
        $this->logger->info('$inQueryTopicIds = ' . print_r($inQueryTopicIds, true));
        $this->logger->info('$userId = ' . print_r($userId, true));
        $this->logger->info('$beginAt = ' . print_r($beginAt, true));
        $this->logger->info('$endAt = ' . print_r($endAt, true));
        $this->logger->info('$limit = ' . print_r($limit, true));

        $con = \Propel::getConnection('default', \Propel::CONNECTION_READ);

        $stmt = $con->prepare($this->createMostInterestingDebatesRawSql($inQueryDebateIds, $inQueryUserIds, $inQueryTagIds, $inQueryTopicIds));

        $stmt->bindValue(':p_user_id', $userId, \PDO::PARAM_INT);
        $stmt->bindValue(':p_user_id2', $userId, \PDO::PARAM_INT);
        $stmt->bindValue(':begin_at', $beginAt, \PDO::PARAM_STR);
        $stmt->bindValue(':begin_at2', $beginAt, \PDO::PARAM_STR);
        $stmt->bindValue(':end_at', $endAt, \PDO::PARAM_STR);
        $stmt->bindValue(':end_at2', $endAt, \PDO::PARAM_STR);
        $stmt->bindValue(':min_nb_followers', EmailConstants::NB_MIN_FOLLOWERS, \PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_STR);

        $stmt->execute();

        $result = $stmt->fetchAll();

        $debates = new \PropelCollection();
        foreach ($result as $row) {
            $debate = new PDDebate();
            $debate->hydrate($row);

            $debates->append($debate);
        }


        return $debates;
    }


    /**
     * Get user's screen notifications
     *
     * @param integer $userId
     * @param string $inQueryNotificationIds
     * @param string $inQueryTopicIds
     * @param string $modifyAt  get notifications from current date applying this string - has to be DateTime.modify compatible
     * @return PropelCollection|PUNotification[]
     */
    public function getScreenUserNotifications($userId, $inQueryNotificationIds, $inQueryTopicIds, $modifyAt = '-7 day')
    {
        $this->logger->info('*** getScreenUserNotifications');
        $this->logger->info('$userId = ' . print_r($userId, true));
        $this->logger->info('$inQueryNotificationIds = ' . print_r($inQueryNotificationIds, true));
        $this->logger->info('$inQueryTopicIds = ' . print_r($inQueryTopicIds, true));

        $minAt = new \DateTime();
        $minAt->modify($modifyAt);

        $con = \Propel::getConnection('default', \Propel::CONNECTION_READ);

        $stmt = $con->prepare($this->createScreenUserNotificationsRawSql($inQueryNotificationIds, $inQueryTopicIds));

        $stmt->bindValue(':p_user_id', $userId, \PDO::PARAM_INT);
        $stmt->bindValue(':created_at_min', $minAt->format('Y-m-d'), \PDO::PARAM_STR);

        $stmt->execute();

        $result = $stmt->fetchAll();

        $notifications = new \PropelCollection();
        foreach ($result as $row) {
            $notification = new PUNotification();
            $notification->hydrate($row);

            $notifications->append($notification);
        }

        return $notifications;
    }

    /**
     * Count user's screen notifications - count only unchecked notif
     *
     * @param integer $userId
     * @param string $inQueryNotificationIds
     * @return int
     */
    public function countScreenUserNotifications($userId, $inQueryNotificationIds, $inQueryTopicIds)
    {
        $this->logger->info('*** countScreenUserNotifications');
        $this->logger->info('$userId = ' . print_r($userId, true));
        $this->logger->info('$inQueryNotificationIds = ' . print_r($inQueryNotificationIds, true));
        $this->logger->info('$inQueryTopicIds = ' . print_r($inQueryTopicIds, true));

        $notifIds = $this->getScreenNotifIds();

        $con = \Propel::getConnection('default', \Propel::CONNECTION_READ);

        $stmt = $con->prepare($this->createCountScreenUserNotificationsRawSql($inQueryNotificationIds, $inQueryTopicIds));

        $stmt->bindValue(':p_user_id', $userId, \PDO::PARAM_INT);

        $stmt->execute();

        $result = $stmt->fetchAll();
        
        return $result[0]['nb'];
    }

    
    /**
     * Get user's notifications for emailing use
     *
     * @param integer $userId
     * @param string $inQueryTopicIds
     * @param DateTime $beginAt
     * @param DateTime $endAt
     * @return PropelCollection|PUNotification[]
     */
    public function getUserNotificationsForEmailing($userId, $inQueryTopicIds, $beginAt, $endAt)
    {
        $this->logger->info('*** getUserNotificationsForEmailing');
        $this->logger->info('$userId = ' . print_r($userId, true));
        $this->logger->info('$inQueryTopicIds = ' . print_r($inQueryTopicIds, true));
        $this->logger->info('$beginAt = ' . print_r($beginAt, true));
        $this->logger->info('$endAt = ' . print_r($endAt, true));

        $con = \Propel::getConnection('default', \Propel::CONNECTION_READ);

        $stmt = $con->prepare($this->createUserNotificationsForEmailingRawSql($inQueryTopicIds));

        $stmt->bindValue(':p_user_id', $userId, \PDO::PARAM_INT);
        $stmt->bindValue(':begin_at', $beginAt->format('Y-m-d H:i'), \PDO::PARAM_STR);
        $stmt->bindValue(':end_at', $endAt->format('Y-m-d H:i'), \PDO::PARAM_STR);

        $stmt->execute();

        $result = $stmt->fetchAll();

        $notifications = new \PropelCollection();
        foreach ($result as $row) {
            $notification = new PUNotification();
            $notification->hydrate($row);

            $notifications->append($notification);
        }

        return $notifications;
    }

    /* ######################################################################################################## */
    /*                                               CRUD OPERATIONS                                            */
    /* ######################################################################################################## */

    /**
     * Update a user's notification to check state
     *
     * @param PUNotification $notification
     * @return PUNotification
     */
    public function checkUserNotification(PUNotification $notification)
    {
        if ($notification) {
            $notification->setChecked(true);
            $notification->setCheckedAt(new \DateTime());

            $notification->save();
        }

        return $notification;
    }

    /**
     * Create a new PUSubscribePNE
     *
     * @param integer $userId
     * @param integer $pnEmailId
     * @return PUSubscribePNE
     */
    public function createUserSubscribeEmail($userId, $pnEmailId)
    {
        $subscribeEmail = new PUSubscribePNE();

        $subscribeEmail->setPUserId($userId);
        $subscribeEmail->setPNEmailId($pnEmailId);

        $subscribeEmail->save();

        return $subscribeEmail;
    }

    /**
     * Delete user's email notification subscribe PUSubscribePNE
     *
     * @param integer $userId
     * @param integer $pnEmailId
     * @return integer
     */
    public function deleteUserSubscribeEmail($userId, $pnEmailId)
    {
        $result = PUSubscribePNEQuery::create()
            ->filterByPNEmailId($pnEmailId)
            ->filterByPUserId($userId)
            ->delete();

        return $result;
    }
}
