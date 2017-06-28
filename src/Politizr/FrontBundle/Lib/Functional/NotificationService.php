<?php
namespace Politizr\FrontBundle\Lib\Functional;

use Politizr\Exception\InconsistentDataException;

use Politizr\Constant\NotificationConstants;

use Politizr\Model\PUser;

use Politizr\Model\PUNotificationQuery;
use Politizr\Model\PUFollowUQuery;
use Politizr\Model\PUFollowDDQuery;

/**
 * Functional service for notification management.
 * beta
 *
 * @author Lionel Bouzonville
 */
class NotificationService
{
    private $notificationManager;
    private $globalTools;
    private $logger;

    /**
     *
     * @param @politizr.manager.notification
     * @param @logger
     */
    public function __construct(
        $notificationManager,
        $globalTools,
        $logger
    ) {
        $this->notificationManager = $notificationManager;
        $this->globalTools = $globalTools;
        $this->logger = $logger;
    }

    /* ######################################################################################################## */
    /*                                               PRIVATE FUNCTIONS                                          */
    /* ######################################################################################################## */

    /**
     * Get array of user's followers ids
     *
     * @param integer $userId
     * @return array
     */
    private function getFollowersIdsArray($userId)
    {
        $userIds = PUFollowUQuery::create()
            ->select('PUserFollowerId')
            ->filterByPUserId($userId)
            ->find()
            ->toArray();

        return $userIds;
    }

    /**
     * Get array of user's PUFollowDD's ids
     * @todo refactoring duplicate w. TimelineService
     *
     * @param integer $userId
     * @return array
     */
    private function getFollowedDebatesIdsArray($userId)
    {
        $debateIds = PUFollowDDQuery::create()
            ->select('PDDebateId')
            ->filterByPUserId($userId)
            ->find()
            ->toArray();

        return $debateIds;
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
     * Get publications from user with most interactions (reactions, comments, note pos)
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
     * Get publications from followed user
     *
     * @param PUser $user
     * @param DateTime $beginAt
     * @param DateTime $endAt
     * @param int $limit
     */
    public function getMostInteractedFollowedUsersPublications(PUser $user, \DateTime $beginAt, \DateTime $endAt, $limit)
    {
        if (!$user) {
            throw new InconsistentDataException('Can get user followed publications - user null');
        }

        // Récupération d'un tableau des ids des débats suivis
        $userIds = $this->getFollowersIdsArray($user->getId());
        $inQueryUserIds = $this->globalTools->getInQuery($userIds);

        $publications = $this->notificationManager->generateMostInteractedFollowedUserPublications($inQueryUserIds, $beginAt->format('Y-m-d H:i:s'), $endAt->format('Y-m-d H:i:s'), $limit);

        return $publications;
    }

    /**
     * Get publications from followed user
     *
     * @param PUser $user
     * @param DateTime $beginAt
     * @param DateTime $endAt
     * @param int $limit
     */
    public function getMostInteractedFollowedDebatesPublications(PUser $user, \DateTime $beginAt, \DateTime $endAt, $limit)
    {
        if (!$user) {
            throw new InconsistentDataException('Can get user followed publications - user null');
        }

        // Récupération d'un tableau des ids des débats suivis
        $debateIds = $this->getFollowedDebatesIdsArray($user->getId());
        $inQueryDebateIds = $this->globalTools->getInQuery($debateIds);

        $publications = $this->notificationManager->generateMostInteractedFollowedDebatesPublications($inQueryDebateIds, $beginAt->format('Y-m-d H:i:s'), $endAt->format('Y-m-d H:i:s'), $limit);

        return $publications;
    }
}
