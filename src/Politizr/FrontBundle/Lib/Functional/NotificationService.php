<?php
namespace Politizr\FrontBundle\Lib\Functional;

use Politizr\Exception\InconsistentDataException;

use Politizr\Constant\NotificationConstants;

use Politizr\Model\PUser;

use Politizr\Model\PUNotificationQuery;

/**
 * Functional service for notification management.
 * beta
 *
 * @author Lionel Bouzonville
 */
class NotificationService
{
    private $notificationManager;
    private $logger;

    /**
     *
     * @param @politizr.manager.notification
     * @param @logger
     */
    public function __construct(
        $notificationManager,
        $logger
    ) {
        $this->notificationManager = $notificationManager;
        $this->logger = $logger;
    }


    /* ######################################################################################################## */
    /*                                              SPECIFIC LISTING                                            */
    /* ######################################################################################################## */

    /**
     * Get user notifications from begin to end date
     *
     * @param PUser $user
     * @param DateTime $beginAt
     * @param DateTime $endAt
     * @return PropelCollection[PUNotification]
     */
    public function getUserNotifications(PUser $user, $beginAt, $endAt)
    {
        $puNotifications = null;

        if (!$user) {
            throw new InconsistentDataException('Can get user notifications - user null');
        }

        $puNotifications = PUNotificationQuery::create()
            ->filterByPUserId($user->getId())
            ->filterByCreatedAt(array('min' => $beginAt, 'max' => $endAt))
            ->orderByPNotificationId('asc')
            ->orderByPObjectName('asc')
            ->orderByPObjectId('asc')
            ->find();

        return $puNotifications;
    }

    /**
     * Extract notifications type from list of PUNotification
     *
     * @param array $puNotificationsInput
     * @param array $notificationsId
     * @return array
     */
    public function extractPUNotifications($puNotificationsInput, $notificationsId)
    {
        $puNotifications = array();
        foreach ($puNotificationsInput as $puNotification) {
            if (in_array($puNotification->getPNotificationId(), $notificationsId)) {
                $puNotifications[] = $puNotification;
            }
        }

        return $puNotifications;
    }

    /**
     * Get publications from user with most interactions (reactions, comments)
     *
     * @param PUser $user
     * @param DateTime $beginAt
     * @param DateTime $endAt
     * @param int $limit
     */
    public function getMostInteractedUserPublications(PUser $user, \DateTime $beginAt, \DateTime $endAt, $limit)
    {
        if (!$user) {
            throw new InconsistentDataException('Can get user most interacted publications - user null');
        }

        $publications = $this->notificationManager->generateMostInteractedUserPublications($user->getId(), $beginAt->format('Y-m-d H:i:s'), $endAt->format('Y-m-d H:i:s'), $limit);

        return $publications;
    }

    /**
     * Get publications from user with most note pos
     *
     * @param PUser $user
     * @param DateTime $beginAt
     * @param DateTime $endAt
     * @param int $limit
     */
    public function getMostNotePosUserPublications(PUser $user, \DateTime $beginAt, \DateTime $endAt, $limit)
    {
        if (!$user) {
            throw new InconsistentDataException('Can get user most note pos publications - user null');
        }

        $publications = $this->notificationManager->generateMostNotePosUserPublications($user->getId(), $beginAt->format('Y-m-d H:i:s'), $endAt->format('Y-m-d H:i:s'), $limit);

        return $publications;
    }
}
