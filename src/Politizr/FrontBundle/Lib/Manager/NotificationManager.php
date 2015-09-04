<?php
namespace Politizr\FrontBundle\Lib\Manager;

use Politizr\Exception\InconsistentDataException;
use Politizr\Exception\FormValidationException;

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
