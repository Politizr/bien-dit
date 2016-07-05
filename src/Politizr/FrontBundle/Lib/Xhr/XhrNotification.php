<?php
namespace Politizr\FrontBundle\Lib\Xhr;

use Symfony\Component\HttpFoundation\Request;

use Politizr\Exception\InconsistentDataException;

use Politizr\Model\PNotificationQuery;
use Politizr\Model\PUNotificationQuery;

/**
 * XHR service for user's notification management.
 * beta
 *
 * @author Lionel Bouzonville
 */
class XhrNotification
{
    private $securityTokenStorage;
    private $templating;
    private $userManager;
    private $notificationManager;
    private $logger;

    /**
     *
     * @param @security.token_storage
     * @param @templating
     * @param @politizr.manager.user
     * @param @politizr.manager.notification
     * @param @logger
     */
    public function __construct(
        $securityTokenStorage,
        $templating,
        $userManager,
        $notificationManager,
        $logger
    ) {
        $this->securityTokenStorage = $securityTokenStorage;

        $this->templating = $templating;

        $this->userManager = $userManager;
        $this->notificationManager = $notificationManager;

        $this->logger = $logger;
    }

    /* ######################################################################################################## */
    /*                                                      NOTIFICATIONS                                       */
    /* ######################################################################################################## */

    /**
     * Notifications loading
     * beta
     */
    public function notificationsLoad(Request $request)
    {
        // $this->logger->info('*** notificationsLoad');

        // get current user
        $user = $this->securityTokenStorage->getToken()->getUser();
        
        // UC session out > manage this case to avoid multiple useless exceptions
        if ($user === null) {
            $notifs = [];
            $counterNotifs = 0;
        } else {
            $notifs = $this->notificationManager->getScreenUserNotifications($user->getId());
            $counterNotifs = $this->notificationManager->countScreenUserNotifications($user->getId());
        }

        // Rendering
        $html = $this->templating->render(
            'PolitizrFrontBundle:Notification:_list.html.twig',
            array(
                'notifs' => $notifs,
            )
        );

        return array(
            'html' => $html,
            'counterNotifs' => $counterNotifs > 0 ? $counterNotifs:'-',
        );
    }

    /**
     * Notification check
     * beta
     */
    public function notificationCheck(Request $request)
    {
        // $this->logger->info('*** notificationChek');

        // Request arguments
        $uuid = $request->get('uuid');
        // $this->logger->info('$uuid = ' . print_r($uuid, true));

        // get current user
        $user = $this->securityTokenStorage->getToken()->getUser();

        $notification = PUNotificationQuery::create()->filterByUuid($uuid)->findOne();
        if ($notification->getPUserId() != $user->getId()) {
            throw new InconsistentDataException(sprintf('User id-%s tries to check PUNotification id-%s', $user->getId(), $notification->getId()));
        }

        $this->notificationManager->checkUserNotification($notification);

        return true;
    }

    /**
     * Notification check all
     * beta
     */
    public function notificationsCheckAll(Request $request)
    {
        // $this->logger->info('*** notificationsLoad');
        
        // get current user
        $user = $this->securityTokenStorage->getToken()->getUser();

        $notifs = PUNotificationQuery::create()
                    ->filterByPUserId($user->getId())
                    ->filterByChecked(false)
                    ->find();
        foreach ($notifs as $notif) {
            $this->notificationManager->checkUserNotification($notif);
        }

        return true;
    }


    /* ######################################################################################################## */
    /*                                      NOTIFICATIONS' SUBSCRIPTIONS                                        */
    /* ######################################################################################################## */

    /**
     * Notification email subscription
     * beta
     */
    public function notifEmailSubscribe(Request $request)
    {
        // $this->logger->info('*** notifEmailSubscribe');

        // Request arguments
        $uuid = $request->get('uuid');
        // $this->logger->info('$uuid = ' . print_r($uuid, true));

        // get current user
        $user = $this->securityTokenStorage->getToken()->getUser();

        // Retrieve subject
        $notification = PNotificationQuery::create()->filterByUuid($uuid)->findOne();

        $this->notificationManager->createUserSubscribeEmail($user->getId(), $notification->getId());

        return true;
    }

    /**
     * Notification email unsubscription
     * beta
     */
    public function notifEmailUnsubscribe(Request $request)
    {
        // $this->logger->info('*** notifEmailUnsubscribe');

        // Request arguments
        $uuid = $request->get('uuid');
        // $this->logger->info('$uuid = ' . print_r($uuid, true));

        // get current user
        $user = $this->securityTokenStorage->getToken()->getUser();

        // Retrieve subject
        $notification = PNotificationQuery::create()->filterByUuid($uuid)->findOne();

        $this->notificationManager->deleteUserSubscribeEmail($user->getId(), $notification->getId());

        return true;
    }
}
