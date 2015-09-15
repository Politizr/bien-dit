<?php
namespace Politizr\FrontBundle\Lib\Manager;

use Politizr\Exception\InconsistentDataException;
use Politizr\Exception\FormValidationException;

use Politizr\FrontBundle\Lib\Notification;

use Politizr\Model\PUNotification;
use Politizr\Model\PUSubscribeEmail;

use Politizr\Model\PUNotificationQuery;
use Politizr\Model\PUSubscribeEmailQuery;

/**
 * DB manager service for notification.
 *
 * @author Lionel Bouzonville
 */
class NotificationManager
{
    private $logger;

    /**
     *
     * @param @logger
     */
    public function __construct($logger)
    {
        $this->logger = $logger;
    }

    /* ######################################################################################################## */
    /*                                                  RAW SQL                                                 */
    /* ######################################################################################################## */

   /**
     * Get distinct [objectName+objectId+authorUserID]' rows from PUNotifications
     *
     * @see app/sql/notifications.sql
     *
     * @param integer $userId
     * @param DateTime $dateTime
     * @return string
     */
    public function createDistinctUserNotificationsRawSql($debateId, $inQueryUserIds)
    {
        // Préparation requête SQL
        $sql = "
SELECT DISTINCT p_object_name, p_object_id, p_author_user_id
FROM `p_u_notification` 
WHERE p_u_notification.p_user_id=".$userId." AND (p_u_notification.created_at>='".$dateTime."' OR p_u_notification.checked=0) 
ORDER BY p_u_notification.created_at DESC
    ";

        return $sql;
    }

    /*
     * Execute SQL and hydrate Notification model
     *
     * @param string $sql
     * @return array[Notification]
     */
    private function hydrateNotificationRows($sql)
    {
        $this->logger->info('*** hydrateNotificationRows');

        $timeline = array();

        if ($sql) {
            $con = \Propel::getConnection('default', \Propel::CONNECTION_READ);

            // dump($sql);

            $stmt = $con->prepare($sql);
            $stmt->execute();

            $result = $stmt->fetchAll();

            // dump($result);

            foreach ($result as $row) {
                $timelineRow = new TimelineRow();

                $timelineRow->setId($row['id']);
                $timelineRow->setTitle($row['title']);
                $timelineRow->setPublishedAt($row['published_at']);
                $timelineRow->setType($row['type']);

                $timeline[] = $timelineRow;
            }
        }

        return $timeline;
    }


    /* ######################################################################################################## */
    /*                                            CRUD OPERATIONS                                               */
    /* ######################################################################################################## */


    /**
     * Get user's notifications
     *
     * @param integer $userId
     * @param string $modifyAt  get notifications from current date applying this string - has to be DateTime.modify compatible
     * @return PropelCollection|PUNotification[]
     */
    public function getUserNotifications($userId, $modifyAt = '-7 day')
    {
        // Requête notifs
        $minAt = new \DateTime();
        $minAt->modify($modifyAt);

        // Notifications de moins d'une semaine ou non checkées
        $notifications = PUNotificationQuery::create()
                            ->filterByPUserId($userId)
                            ->filterByCreatedAt(array('min' => $minAt))
                            ->_or()
                            ->filterByChecked(false)
                            ->orderByCreatedAt('desc')
                            ->find();

        return $notifications;
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
     * Create a new PUSubscribeEmail
     *
     * @param integer $userId
     * @param integer $notificationId
     * @return PUSubscribeEmail
     */
    public function createUserSubscribeEmail($userId, $notificationId)
    {
        $subscribeEmail = new PUSubscribeEmail();

        $subscribeEmail->setPUserId($userId);
        $subscribeEmail->setPNotificationId($notificationId);

        $subscribeEmail->save();

        return $subscribeEmail;
    }

    /**
     * Delete user's email notification subscribe PUSubscribeEmail
     *
     * @param integer $userId
     * @param integer $notificationId
     * @return integer
     */
    public function deleteUserSubscribeEmail($userId, $notificationId)
    {
        $result = PUSubscribeEmailQuery::create()
            ->filterByPNotificationId($notificationId)
            ->filterByPUserId($userId)
            ->delete();

        return $result;
    }
}
