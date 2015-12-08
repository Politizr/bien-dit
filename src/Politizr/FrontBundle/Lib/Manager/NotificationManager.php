<?php
namespace Politizr\FrontBundle\Lib\Manager;

use Politizr\Exception\InconsistentDataException;
use Politizr\Exception\BoxErrorException;

use Politizr\Constant\NotificationConstants;

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
        );

        return $notifIds;
    }

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
