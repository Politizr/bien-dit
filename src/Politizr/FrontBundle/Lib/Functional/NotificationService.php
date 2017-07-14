<?php
namespace Politizr\FrontBundle\Lib\Functional;

use Politizr\Exception\InconsistentDataException;

use Politizr\Constant\NotificationConstants;

use Politizr\Model\PUser;

use Politizr\Model\PUNotificationQuery;
use Politizr\Model\PUFollowUQuery;
use Politizr\Model\PUFollowDDQuery;
use Politizr\Model\PUTaggedTQuery;

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
     * Get array of user's followed user's ids
     * @todo refactoring duplicate w. TimelineService
     *
     * @param integer $userId
     * @return array
     */
    private function getFollowedUsersIdsArray($userId)
    {
        $userIds = PUFollowUQuery::create()
            ->select('PUserId')
            ->filterByPUserFollowerId($userId)
            ->find()
            ->toArray();

        return $userIds;
    }

    /**
     * Get array of user's followed debates ids
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

    /**
     * Get array of user's tags ids
     *
     * @param integer $userId
     * @return array
     */
    private function getFollowedTagsIdsArray($userId)
    {
        $userIds = PUTaggedTQuery::create()
            ->select('PTagId')
            ->filterByPUserId($userId)
            ->find()
            ->toArray();

        return $userIds;
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

        // Récupération d'un tableau des ids des users suivis
        $userIds = $this->getFollowedUsersIdsArray($user->getId());
        $inQueryUserIds = $this->globalTools->getInQuery($userIds);

        $publications = $this->notificationManager->generateMostInteractedFollowedUserPublications($inQueryUserIds, $beginAt->format('Y-m-d H:i:s'), $endAt->format('Y-m-d H:i:s'), $limit);

        return $publications;
    }

    /**
     * Get publications from followed debates
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

        $publications = $this->notificationManager->generateMostInteractedFollowedDebatesPublications($inQueryDebateIds, $user->getId(), $beginAt->format('Y-m-d H:i:s'), $endAt->format('Y-m-d H:i:s'), $limit);

        return $publications;
    }

    /**
     * Get nearest qualified users from user
     *
     * @param PUser $user
     * @param DateTime $beginAt
     * @param DateTime $endAt
     * @param int $limit
     */
    public function getNearestQualifiedUsers(PUser $user, \DateTime $beginAt, \DateTime $endAt, $limit)
    {
        if (!$user) {
            throw new InconsistentDataException('Can get most nearest qualified users - user null');
        }

        // Get user's localization data
        $city = $user->getCity();
        $department = $user->getDepartment();
        $region = $user->getRegion();

        if (!$city || !$department || !$region) {
            return null;
        }

        $users = $this->notificationManager->generateNearestQualifiedUsers($user->getId(), $city->getId(), $department->getId(), $region->getId(), $beginAt->format('Y-m-d H:i:s'), $endAt->format('Y-m-d H:i:s'), $limit);

        return $users;
    }

    /**
     * Get nearest debates from user
     *
     * @param PUser $user
     * @param DateTime $beginAt
     * @param DateTime $endAt
     * @param int $limit
     */
    public function getNearestDebates(PUser $user, \DateTime $beginAt, \DateTime $endAt, $limit)
    {
        if (!$user) {
            throw new InconsistentDataException('Can get most nearest debates - user null');
        }

        // Get user's localization data
        $city = $user->getCity();
        $department = $user->getDepartment();
        $region = $user->getRegion();

        if (!$city || !$department || !$region) {
            return null;
        }

        // Compute IN string for SQL query
        $debateIds = $this->getFollowedDebatesIdsArray($user->getId());
        $inQueryDebateIds = $this->globalTools->getInQuery($debateIds);

        $userIds = $this->getFollowedUsersIdsArray($user->getId());
        $inQueryUserIds = $this->globalTools->getInQuery($userIds);
        
        $tagIds = $this->getFollowedTagsIdsArray($user->getId());
        $inQueryTagIds = $this->globalTools->getInQuery($tagIds);

        // Retrieve debates
        $debates = $this->notificationManager->generateNearestDebates(
            $inQueryDebateIds,
            $inQueryUserIds,
            $inQueryTagIds, 
            $user->getId(),
            $city->getId(),
            $department->getId(),
            $region->getId(),
            $beginAt->format('Y-m-d H:i:s'),
            $endAt->format('Y-m-d H:i:s'),
            $limit
        );

        return $debates;
    }
}
