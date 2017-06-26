<?php
namespace Politizr\FrontBundle\Lib\Functional;

use Politizr\Exception\InconsistentDataException;

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
    private $logger;

    /**
     *
     * @param @logger
     */
    public function __construct(
        $logger
    ) {
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
     * @return PropelCollection[Publication]
     */
    public function getUserNotifications(PUser $user, $beginAt, $endAt)
    {
        $puNotifications = null;

        if (!$user) {
            throw new InconsistentDataException('Can get user notifications, user null');
        }

        $puNotifications = PUNotificationQuery::create()
            ->filterByPUserId($user->getId())
            ->filterByCreatedAt(array('min' => $beginAt, 'max' => $endAt))
            ->find();

        return $puNotifications;
    }
}
