<?php
namespace Politizr\FrontBundle\Lib\Manager;

use Politizr\Constant\NotificationConstants;

use Politizr\Model\PUNotification;
use Politizr\Model\PUSubscribePNE;

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
    /*                                            CRUD OPERATIONS                                               */
    /* ######################################################################################################## */

    private function getScreenNotifIds()
    {
        $notifIds = array(
            NotificationConstants::ID_D_COMMENT_PUBLISH,
            NotificationConstants::ID_D_NOTE_POS,
            NotificationConstants::ID_D_NOTE_NEG,
            NotificationConstants::ID_D_D_REACTION_PUBLISH,
            NotificationConstants::ID_D_D_FOLLOWED,
            NotificationConstants::ID_D_R_REACTION_PUBLISH,
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
     * Most interacted user publications (reactions/comments)
     *
     * @see app/sql/accountNotifications.sql
     *
     * @return string
     */
    private function createMostInteractedUserPublicationsRawSql()
    {
        // Requête SQL
        $sql = "
( SELECT DISTINCT p_d_debate.id as id, p_d_debate.title as title, p_d_debate.file_name as fileName, p_d_debate.description as description, p_d_debate.slug as slug, p_d_debate.note_pos as note_pos, p_d_debate.note_neg as note_neg, COUNT(distinct p_d_reaction_child.id) as nb_reactions, COUNT(distinct p_d_comment_child.id) as nb_comments, p_d_debate.published_at as published_at, p_d_debate.updated_at as updated_at, 'Politizr\\\Model\\\PDDebate' as type
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
WHERE
    p_d_debate.published = 1
    AND p_d_debate.online = 1
    AND p_d_debate.p_user_id = :p_user_id

GROUP BY id
HAVING nb_reactions > 0 OR nb_comments > 0
)

UNION DISTINCT

( SELECT DISTINCT p_d_reaction.id as id, p_d_reaction.title as title, p_d_reaction.file_name as fileName, p_d_reaction.description as description, p_d_reaction.slug as slug, p_d_reaction.note_pos as note_pos, p_d_reaction.note_neg as note_neg, COUNT(distinct p_d_reaction_child.id) as nb_reactions, COUNT(distinct p_d_comment_child.id) as nb_comments, p_d_reaction.published_at as published_at, p_d_reaction.updated_at as updated_at,'Politizr\\\Model\\\PDReaction' as type
FROM p_d_reaction
    LEFT JOIN p_d_reaction as p_d_reaction_child
        ON p_d_reaction.id = p_d_reaction_child.parent_reaction_id
        AND p_d_reaction_child.published = 1
        AND p_d_reaction_child.online = 1
        AND p_d_reaction_child.published_at > :begin_at3
        AND p_d_reaction_child.published_at < :end_at3
    LEFT JOIN p_d_r_comment as p_d_comment_child
        ON p_d_reaction.id = p_d_comment_child.p_d_reaction_id
        AND p_d_comment_child.online = 1
        AND p_d_comment_child.created_at > :begin_at4
        AND p_d_comment_child.created_at < :end_at4
WHERE
    p_d_reaction.published = 1
    AND p_d_reaction.online = 1
    AND p_d_reaction.tree_level > 0
    AND p_d_reaction.p_user_id = :p_user_id2

GROUP BY id
HAVING nb_reactions > 0 OR nb_comments > 0
)

ORDER BY nb_reactions DESC, nb_comments DESC, note_pos DESC, note_neg ASC

LIMIT :limit
";

        return $sql;
    }

    /**
     * Most interacted user publications (notes+)
     *
     * @see app/sql/accountNotifications.sql
     *
     * @return string
     */
    private function createMostNotePosUserPublicationsRawSql()
    {
        // Requête SQL
        $sql = "
( SELECT DISTINCT p_d_debate.id as id, p_d_debate.title as title, p_d_debate.file_name as fileName, p_d_debate.description as description, p_d_debate.slug as slug, p_d_debate.note_pos as note_pos, p_d_debate.note_neg as note_neg, COUNT(distinct p_u_notification.id) as nb_notifications, p_d_debate.published_at as published_at, p_d_debate.updated_at as updated_at, 'Politizr\\\Model\\\PDDebate' as type
FROM p_d_debate
    LEFT JOIN p_u_notification as p_u_notification
        ON p_d_debate.id = p_u_notification.p_object_id

WHERE
    p_d_debate.published = 1
    AND p_d_debate.online = 1
    AND p_d_debate.p_user_id = :p_user_id
    AND p_u_notification.p_notification_id = :p_notification_id
    AND p_u_notification.p_object_name = 'Politizr\\\Model\\\PDDebate'
    AND p_u_notification.created_at > :begin_at
    AND p_u_notification.created_at < :end_at

GROUP BY id
)


UNION DISTINCT


# Réactions publiés
( SELECT DISTINCT p_d_reaction.id as id, p_d_reaction.title as title, p_d_reaction.file_name as fileName, p_d_reaction.description as description, p_d_reaction.slug as slug, p_d_reaction.note_pos as note_pos, p_d_reaction.note_neg as note_neg, COUNT(distinct p_u_notification.id) as nb_notifications, p_d_reaction.published_at as published_at, p_d_reaction.updated_at as updated_at,'Politizr\\\Model\\\PDReaction' as type
FROM p_d_reaction
    LEFT JOIN p_u_notification as p_u_notification
        ON p_d_reaction.id = p_u_notification.p_object_id

WHERE
    p_d_reaction.published = 1
    AND p_d_reaction.online = 1
    AND p_d_reaction.tree_level > 0
    AND p_d_reaction.p_user_id = :p_user_id2
    AND p_u_notification.p_notification_id = :p_notification_id2
    AND p_u_notification.p_object_name = 'Politizr\\\Model\\\PDReaction'
    AND p_u_notification.created_at > :begin_at2
    AND p_u_notification.created_at < :end_at2

GROUP BY id
)

ORDER BY nb_notifications DESC

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
     * @param int $userId
     * @param string $beginAt
     * @param string $endAt
     * @param integer $limit
     * @return PropelCollection
     */
    public function generateMostInteractedUserPublications($userId, $beginAt, $endAt, $limit)
    {
        $this->logger->info('*** generateMostInteractedUserPublications');
        $this->logger->info('$userId = ' . print_r($userId, true));
        $this->logger->info('$beginAt = ' . print_r($beginAt, true));
        $this->logger->info('$endAt = ' . print_r($endAt, true));
        $this->logger->info('$limit = ' . print_r($limit, true));

        $con = \Propel::getConnection('default', \Propel::CONNECTION_READ);

        $stmt = $con->prepare($this->createMostInteractedUserPublicationsRawSql());

        $stmt->bindValue(':p_user_id', $userId, \PDO::PARAM_INT);
        $stmt->bindValue(':p_user_id2', $userId, \PDO::PARAM_INT);
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
     * Interacted note pos user documents listing
     *
     * @param int $userId
     * @param string $beginAt
     * @param string $endAt
     * @param integer $limit
     * @return PropelCollection
     */
    public function generateMostNotePosUserPublications($userId, $beginAt, $endAt, $limit)
    {
        $this->logger->info('*** generateMostInteractedUserPublications');
        $this->logger->info('$userId = ' . print_r($userId, true));
        $this->logger->info('$beginAt = ' . print_r($beginAt, true));
        $this->logger->info('$endAt = ' . print_r($endAt, true));
        $this->logger->info('$limit = ' . print_r($limit, true));

        $con = \Propel::getConnection('default', \Propel::CONNECTION_READ);

        $stmt = $con->prepare($this->createMostNotePosUserPublicationsRawSql());

        $stmt->bindValue(':p_user_id', $userId, \PDO::PARAM_INT);
        $stmt->bindValue(':p_user_id2', $userId, \PDO::PARAM_INT);
        $stmt->bindValue(':p_notification_id', NotificationConstants::ID_D_NOTE_POS, \PDO::PARAM_INT);
        $stmt->bindValue(':p_notification_id2', NotificationConstants::ID_D_NOTE_POS, \PDO::PARAM_INT);
        $stmt->bindValue(':begin_at', $beginAt, \PDO::PARAM_STR);
        $stmt->bindValue(':begin_at2', $beginAt, \PDO::PARAM_STR);
        $stmt->bindValue(':end_at', $endAt, \PDO::PARAM_STR);
        $stmt->bindValue(':end_at2', $endAt, \PDO::PARAM_STR);
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_STR);

        $stmt->execute();

        $result = $stmt->fetchAll();

        $publications = $this->globalTools->hydrateInteractedPublication($result, $beginAt, $endAt);

        return $publications;
    }

    /* ######################################################################################################## */
    /*                                               CRUD OPERATIONS                                            */
    /* ######################################################################################################## */

    /**
     * Get user's screen notifications
     *
     * @param integer $userId
     * @param string $modifyAt  get notifications from current date applying this string - has to be DateTime.modify compatible
     * @return PropelCollection|PUNotification[]
     */
    public function getScreenUserNotifications($userId, $modifyAt = '-7 day')
    {
        // Requête notifs
        $minAt = new \DateTime();
        $minAt->modify($modifyAt);

        $notifIds = $this->getScreenNotifIds();

        // Notifications de moins d'une semaine ou non checkées
        $notifications = PUNotificationQuery::create()
                            ->filterByPUserId($userId)
                            ->filterByCreatedAt(array('min' => $minAt))
                            ->_or()
                            ->filterByChecked(false)
                            ->filterByPNotificationId($notifIds)
                            ->orderByCreatedAt('desc')
                            ->find();

        return $notifications;
    }

    /**
     * Count user's screen notifications - count only unchecked notif
     *
     * @param integer $userId
     * @return int
     */
    public function countScreenUserNotifications($userId)
    {
        $notifIds = $this->getScreenNotifIds();

        // Notifications de moins d'une semaine ou non checkées
        $nbNotifications = PUNotificationQuery::create()
                            ->filterByPUserId($userId)
                            ->filterByChecked(false)
                            ->filterByPNotificationId($notifIds)
                            ->count();

        return $nbNotifications;
    }

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
