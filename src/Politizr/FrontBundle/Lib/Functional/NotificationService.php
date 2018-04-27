<?php
namespace Politizr\FrontBundle\Lib\Functional;

use Politizr\Exception\InconsistentDataException;

use Politizr\Constant\NotificationConstants;

use Politizr\Model\PUser;

use Politizr\Model\PUNotificationQuery;
use Politizr\Model\PUFollowUQuery;
use Politizr\Model\PUFollowDDQuery;
use Politizr\Model\PUTaggedTQuery;
use Politizr\Model\PCTopicQuery;

/**
 * Functional service for notification management.
 * beta
 *
 * @author Lionel Bouzonville
 */
class NotificationService
{
    private $notificationManager;

    private $circleService;

    private $globalTools;

    private $logger;

    /**
     *
     * @param @politizr.manager.notification
     * @param @politizr.functional.circle
     * @param @politizr.tools.global
     * @param @logger
     */
    public function __construct(
        $notificationManager,
        $circleService,
        $globalTools,
        $logger
    ) {
        $this->notificationManager = $notificationManager;

        $this->circleService = $circleService;

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

    /**
     * Get array of circle's topic ids
     *
     * @param int $circleId
     * @return array
     */
    private function getTopicIdsArray($circleId)
    {
        $topicIds = PCTopicQuery::create()
            ->select('Id')
            ->filterByPCircleId($circleId)
            ->find()
            ->toArray();

        return $topicIds;
    }

    /* ######################################################################################################## */
    /*                                              SPECIFIC LISTING                                            */
    /* ######################################################################################################## */

    /**
     * Get notifications for user
     *
     * @param PUser $user
     * @return \PropelCollection PUNotification
     */
    public function getScreenUserNotifications($user, $notifIds = null)
    {
        if (!$user) {
            throw new InconsistentDataException('User null');
        }

        // Topics
        $topicIds = $this->circleService->getTopicIdsByUserId($user->getId());
        $inQueryTopicIds = null;
        if (!empty($topicIds)) {
            $inQueryTopicIds = $this->globalTools->getInQuery($topicIds);
        }

        // Notifications
        $notifIds = $this->notificationManager->getScreenNotifIds();
        $inQueryNotificationsIds = $this->globalTools->getInQuery($notifIds);

        return $this->notificationManager->getScreenUserNotifications($user->getId(), $inQueryNotificationsIds, $inQueryTopicIds);
    }

    /**
     * Count active screen notifications for user
     *
     * @param PUser $user
     * @return int
     */
    public function countScreenUserNotifications($user)
    {
        if (!$user) {
            throw new InconsistentDataException('User null');
        }

        // Topics
        $topicIds = $this->circleService->getTopicIdsByUserId($user->getId());
        $inQueryTopicIds = null;
        if (!empty($topicIds)) {
            $inQueryTopicIds = $this->globalTools->getInQuery($topicIds);
        }

        // Notifications
        $notifIds = $this->notificationManager->getScreenNotifIds();
        $inQueryNotificationsIds = $this->globalTools->getInQuery($notifIds);

        return $this->notificationManager->countScreenUserNotifications($user->getId(), $inQueryNotificationsIds, $inQueryTopicIds);
    }

    /**
     * Retrieve ids of object type from InteractedPublications listing.
     *
     * @param array $interactedPublications
     * @param string $objectType
     * @return array[int]
     */
    public function getObjectTypeIdsFromInteractedPublications($interactedPublications, $objectType)
    {
        $ids = [];
        foreach ($interactedPublications as $publication) {
            if ($publication->getType() == $objectType) {
                $ids[] = $publication->getId();
            }
        }
        return $ids;
    }

    /**
     * Get user notifications from begin to end date, used in emailing
     *
     * @param PUser $user
     * @param DateTime $beginAt
     * @param DateTime $endAt
     * @return PropelCollection[PUNotification]
     */
    public function getUserNotificationsForEmailing(PUser $user, $beginAt, $endAt)
    {
        $puNotifications = null;

        if (!$user) {
            throw new InconsistentDataException('Can get user notifications - user null');
        }

        // Topics
        $topicIds = $this->circleService->getTopicIdsByUserId($user->getId());
        $inQueryTopicIds = null;
        if (!empty($topicIds)) {
            $inQueryTopicIds = $this->globalTools->getInQuery($topicIds);
        }

        return $this->notificationManager->getUserNotificationsForEmailing($user->getId(), $inQueryTopicIds, $beginAt, $endAt);
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

        // Topics
        $topicIds = $this->circleService->getTopicIdsByUserId($user->getId());
        $inQueryTopicIds = null;
        if (!empty($topicIds)) {
            $inQueryTopicIds = $this->globalTools->getInQuery($topicIds);
        }

        $publications = $this->notificationManager->generateMostInteractedUserPublications($inQueryTopicIds, $user->getId(), $beginAt->format('Y-m-d H:i:s'), $endAt->format('Y-m-d H:i:s'), $limit);

        return $publications;
    }

    /**
     * Get publications from followed user
     *
     * @param PUser $user
     * @param DateTime $beginAt
     * @param DateTime $endAt
     * @param int $limit
     * @param array $notInDebateIds found debates' publications not in these ids
     * @param array $notInReactionIds found reactions' publications not in these ids
     * @param array $notInCommentDebateIds found debate's comments' publications not in these ids
     * @param array $notInCommentReactionIds found reaction's comments' publications not in these ids
     * @return array[InteractedPublication]
     */
    public function getMostInteractedFollowedUsersPublications(PUser $user, \DateTime $beginAt, \DateTime $endAt, $limit, $notInDebateIds = [], $notInReactionIds = [], $notInCommentDebateIds = [], $notInCommentReactionIds = [])
    {
        if (!$user) {
            throw new InconsistentDataException('Can get user followed publications - user null');
        }

        // Topics
        $topicIds = $this->circleService->getTopicIdsByUserId($user->getId());
        $inQueryTopicIds = null;
        if (!empty($topicIds)) {
            $inQueryTopicIds = $this->globalTools->getInQuery($topicIds);
        }

        // Compute followed users ids
        $userIds = $this->getFollowedUsersIdsArray($user->getId());
        $inQueryUserIds = $this->globalTools->getInQuery($userIds);

        // Construct "not in" strings
        $inQueryNotInPDDebateIds = $this->globalTools->getInQuery($notInDebateIds);
        $inQueryNotInPDReactionIds = $this->globalTools->getInQuery($notInReactionIds);
        $inQueryNotInPDDCommentIds = $this->globalTools->getInQuery($notInCommentDebateIds);
        $inQueryNotInPDRCommentIds = $this->globalTools->getInQuery($notInCommentReactionIds);

        $publications = $this->notificationManager->generateMostInteractedFollowedUserPublications($inQueryUserIds, $inQueryTopicIds, $inQueryNotInPDDebateIds, $inQueryNotInPDReactionIds, $inQueryNotInPDDCommentIds, $inQueryNotInPDRCommentIds, $beginAt->format('Y-m-d H:i:s'), $endAt->format('Y-m-d H:i:s'), $limit);

        return $publications;
    }

    /**
     * Get publications from followed debates
     *
     * @param PUser $user
     * @param DateTime $beginAt
     * @param DateTime $endAt
     * @param int $limit
     * @return array[InteractedPublication]
     */
    public function getMostInteractedFollowedDebatesPublications(PUser $user, \DateTime $beginAt, \DateTime $endAt, $limit)
    {
        if (!$user) {
            throw new InconsistentDataException('Can get user followed publications - user null');
        }

        // Topics
        $topicIds = $this->circleService->getTopicIdsByUserId($user->getId());
        $inQueryTopicIds = null;
        if (!empty($topicIds)) {
            $inQueryTopicIds = $this->globalTools->getInQuery($topicIds);
        }

        // Récupération d'un tableau des ids des débats suivis
        $debateIds = $this->getFollowedDebatesIdsArray($user->getId());
        $inQueryDebateIds = $this->globalTools->getInQuery($debateIds);

        $publications = $this->notificationManager->generateMostInteractedFollowedDebatesPublications($inQueryDebateIds, $inQueryTopicIds, $user->getId(), $beginAt->format('Y-m-d H:i:s'), $endAt->format('Y-m-d H:i:s'), $limit);

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

        // Topics
        $topicIds = $this->circleService->getTopicIdsByUserId($user->getId());
        $inQueryTopicIds = null;
        if (!empty($topicIds)) {
            $inQueryTopicIds = $this->globalTools->getInQuery($topicIds);
        }

        // Retrieve debates
        $debates = $this->notificationManager->generateNearestDebates(
            $inQueryDebateIds,
            $inQueryUserIds,
            $inQueryTagIds, 
            $inQueryTopicIds,
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
