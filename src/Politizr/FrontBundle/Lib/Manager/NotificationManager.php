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
