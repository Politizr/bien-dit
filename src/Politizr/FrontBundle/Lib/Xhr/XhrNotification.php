<?php
namespace Politizr\FrontBundle\Lib\Xhr;

use Symfony\Component\HttpFoundation\Request;

use Politizr\Exception\InconsistentDataException;
use Politizr\Exception\FormValidationException;

use Politizr\Model\PUNotificationQuery;

/**
 * XHR service for user's notification management.
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
     */
    public function notificationsLoad(Request $request)
    {
        $this->logger->info('*** notificationsLoad');

        // Function process
        $user = $this->securityTokenStorage->getToken()->getUser();

        $notifs = $this->notificationManager->getScreenUserNotifications($user->getId());
        $nbNotifs = count($notifs);

        // Rendering
        $html = $this->templating->render(
            'PolitizrFrontBundle:Notification:_list.html.twig',
            array(
                'notifs' => $notifs,
            )
        );

        return array(
            'html' => $html,
            'nb' => $nbNotifs > 0 ? $nbNotifs:'-',
            );
    }

    /**
     * Notification check
     */
    public function notificationCheck(Request $request)
    {
        $this->logger->info('*** notificationChek');

        // Request arguments
        $id = $request->get('subjectId');
        $this->logger->info('$id = ' . print_r($id, true));

        // Function process
        $user = $this->securityTokenStorage->getToken()->getUser();

        $notification = PUNotificationQuery::create()->findPk($id);
        $this->notificationManager->checkUserNotification($notification);

        return true;
    }

    /**
     * Notification check all
     */
    public function notificationsCheckAll(Request $request)
    {
        $this->logger->info('*** notificationsLoad');
        
        // Function process
        $user = $this->securityTokenStorage->getToken()->getUser();

        $notifs = PUNotificationQuery::create()
                            ->filterByPUserId($user->getId())
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
     */
    public function notifEmailSubscribe(Request $request)
    {
        $this->logger->info('*** notifEmailSubscribe');

        // Request arguments
        $id = $request->get('subjectId');
        $this->logger->info('$id = ' . print_r($id, true));

        // Function process
        $user = $this->securityTokenStorage->getToken()->getUser();

        $this->notificationManager->createUserSubscribeEmail($user->getId(), $id);

        return true;
    }


    /**
     * Notification email unsubscription
     */
    public function notifEmailUnsubscribe(Request $request)
    {
        $this->logger->info('*** notifEmailUnsubscribe');

        // Request arguments
        $id = $request->get('subjectId');
        $this->logger->info('$id = ' . print_r($id, true));

        // Function process
        $user = $this->securityTokenStorage->getToken()->getUser();

        $this->notificationManager->deleteUserSubscribeEmail($user->getId(), $id);

        return true;
    }

    /**
     * Contextual user notification email subscription
     */
    public function notifUserContextSubscribe(Request $request)
    {
        $this->logger->info('*** notifUserContextSubscribe');

        // Request arguments
        $id = $request->get('subjectId');
        $this->logger->info('$id = ' . print_r($id, true));
        $context = $request->get('context');
        $this->logger->info('$context = ' . print_r($context, true));

        // Function process
        $user = $this->securityTokenStorage->getToken()->getUser();

        $this->userManager->updateFollowUserContextEmailNotification($user->getId(), $id, true, $context);

        return true;
    }

    /**
     * Contextual user notification email unsubscription
     */
    public function notifUserContextUnsubscribe(Request $request)
    {
        $this->logger->info('*** notifUserContextUnsubscribe');

        // Request arguments
        $id = $request->get('subjectId');
        $this->logger->info('$id = ' . print_r($id, true));
        $context = $request->get('context');
        $this->logger->info('$context = ' . print_r($context, true));

        // Function process
        $user = $this->securityTokenStorage->getToken()->getUser();

        $this->userManager->updateFollowUserContextEmailNotification($user->getId(), $id, false, $context);

        return true;
    }

    /**
     * Contextual user's debate notification email subscription
     */
    public function notifDebateContextSubscribe(Request $request)
    {
        $this->logger->info('*** notifDebateContextSubscribe');

        // Request arguments
        $id = $request->get('subjectId');
        $this->logger->info('$id = ' . print_r($id, true));
        $context = $request->get('context');
        $this->logger->info('$context = ' . print_r($context, true));

        // Function process
        $user = $this->securityTokenStorage->getToken()->getUser();

        $this->userManager->updateFollowDebateContextEmailNotification($user->getId(), $id, true, $context);

        return true;
    }

    /**
     *  Désouscrit une notification contextuelle à un débat
     *
     */
    public function notifDebateContextUnsubscribe(Request $request)
    {
        $this->logger->info('*** notifDebateContextUnsubscribe');

        // Request arguments
        $id = $request->get('subjectId');
        $this->logger->info('$id = ' . print_r($id, true));
        $context = $request->get('context');
        $this->logger->info('$context = ' . print_r($context, true));

        // Function process
        $user = $this->securityTokenStorage->getToken()->getUser();

        $this->userManager->updateFollowDebateContextEmailNotification($user->getId(), $id, false, $context);

        return true;
    }
}
