<?php
namespace Politizr\FrontBundle\Lib\Xhr;

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
    private $sc;

    /**
     *
     */
    public function __construct($serviceContainer)
    {
        $this->sc = $serviceContainer;
    }

    /* ######################################################################################################## */
    /*                                                      NOTIFICATIONS                                       */
    /* ######################################################################################################## */

    /**
     * Notifications loading
     */
    public function notificationsLoad()
    {
        $logger = $this->sc->get('logger');
        $logger->info('*** notificationsLoad');

        // Retrieve used services
        $securityContext = $this->sc->get('security.context');
        $notificationManager = $this->sc->get('politizr.manager.notification');
        $templating = $this->sc->get('templating');

        // Function process
        $user = $securityContext->getToken()->getUser();

        $notifs = $notificationManager->getUserNotifications($user->getId());
        $nbNotifs = count($notifs);

        // Rendering
        $html = $templating->render(
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
    public function notificationCheck()
    {
        $logger = $this->sc->get('logger');
        $logger->info('*** notificationChek');

        // Retrieve used services
        $request = $this->sc->get('request');
        $securityContext = $this->sc->get('security.context');
        $notificationManager = $this->sc->get('politizr.manager.notification');

        // Request arguments
        $id = $request->get('subjectId');
        $logger->info('$id = ' . print_r($id, true));

        // Function process
        $user = $securityContext->getToken()->getUser();

        $notification = PUNotificationQuery::create()->findPk($id);
        $notificationManager->checkUserNotification($id);

        return true;
    }

    /**
     * Notification check all
     */
    public function notificationsCheckAll()
    {
        $logger = $this->sc->get('logger');
        $logger->info('*** notificationsLoad');
        
        // Retrieve used services
        $securityContext = $this->sc->get('security.context');
        $notificationManager = $this->sc->get('politizr.manager.notification');

        // Function process
        $user = $securityContext->getToken()->getUser();

        $notifs = PUNotificationQuery::create()
                            ->filterByPUserId($user->getId())
                            ->find();
        foreach ($notifs as $notif) {
            $notificationManager->checkUserNotification($notif);
        }

        return true;
    }


    /* ######################################################################################################## */
    /*                                      NOTIFICATIONS' SUBSCRIPTIONS                                        */
    /* ######################################################################################################## */

    /**
     * Notification email subscription
     */
    public function notifEmailSubscribe()
    {
        $logger = $this->sc->get('logger');
        $logger->info('*** notifEmailSubscribe');

        // Retrieve used services
        $request = $this->sc->get('request');
        $securityContext = $this->sc->get('security.context');
        $notificationManager = $this->sc->get('politizr.manager.notification');

        // Request arguments
        $id = $request->get('subjectId');
        $logger->info('$id = ' . print_r($id, true));

        // Function process
        $user = $securityContext->getToken()->getUser();

        $notificationManager->createUserSubscribeEmail($user->getId(), $id);

        return true;
    }


    /**
     * Notification email unsubscription
     */
    public function notifEmailUnsubscribe()
    {
        $logger = $this->sc->get('logger');
        $logger->info('*** notifEmailUnsubscribe');

        // Retrieve used services
        $request = $this->sc->get('request');
        $securityContext = $this->sc->get('security.context');
        $notificationManager = $this->sc->get('politizr.manager.notification');

        // Request arguments
        $id = $request->get('subjectId');
        $logger->info('$id = ' . print_r($id, true));

        // Function process
        $user = $securityContext->getToken()->getUser();

        $notificationManager->deleteUserSubscribeEmail($user->getId(), $id);

        return true;
    }

    /**
     * Contextual user notification email subscription
     */
    public function notifUserContextSubscribe()
    {
        $logger = $this->sc->get('logger');
        $logger->info('*** notifUserContextSubscribe');

        // Retrieve used services
        $request = $this->sc->get('request');
        $securityContext = $this->sc->get('security.context');
        $userManager = $this->sc->get('politizr.manager.user');

        // Request arguments
        $id = $request->get('subjectId');
        $logger->info('$id = ' . print_r($id, true));
        $context = $request->get('context');
        $logger->info('$context = ' . print_r($context, true));

        // Function process
        $user = $securityContext->getToken()->getUser();

        $userManager->updateFollowUserContextEmailNotification($user->getId(), $id, true, $context);

        return true;
    }

    /**
     * Contextual user notification email unsubscription
     */
    public function notifUserContextUnsubscribe()
    {
        $logger = $this->sc->get('logger');
        $logger->info('*** notifUserContextUnsubscribe');

        // Retrieve used services
        $request = $this->sc->get('request');
        $securityContext = $this->sc->get('security.context');
        $userManager = $this->sc->get('politizr.manager.user');

        // Request arguments
        $id = $request->get('subjectId');
        $logger->info('$id = ' . print_r($id, true));
        $context = $request->get('context');
        $logger->info('$context = ' . print_r($context, true));

        // Function process
        $user = $securityContext->getToken()->getUser();

        $userManager->updateFollowUserContextEmailNotification($user->getId(), $id, false, $context);

        return true;
    }

    /**
     * Contextual user's debate notification email subscription
     */
    public function notifDebateContextSubscribe()
    {
        $logger = $this->sc->get('logger');
        $logger->info('*** notifDebateContextSubscribe');

        // Retrieve used services
        $request = $this->sc->get('request');
        $securityContext = $this->sc->get('security.context');
        $userManager = $this->sc->get('politizr.manager.user');

        // Request arguments
        $id = $request->get('subjectId');
        $logger->info('$id = ' . print_r($id, true));
        $context = $request->get('context');
        $logger->info('$context = ' . print_r($context, true));

        // Function process
        $user = $securityContext->getToken()->getUser();

        $userManager->updateFollowDebateContextEmailNotification($user->getId(), $id, true, $context);

        return true;
    }

    /**
     *  Désouscrit une notification contextuelle à un débat
     *
     */
    public function notifDebateContextUnsubscribe()
    {
        $logger = $this->sc->get('logger');
        $logger->info('*** notifDebateContextUnsubscribe');

        // Retrieve used services
        $request = $this->sc->get('request');
        $securityContext = $this->sc->get('security.context');
        $userManager = $this->sc->get('politizr.manager.user');

        // Request arguments
        $id = $request->get('subjectId');
        $logger->info('$id = ' . print_r($id, true));
        $context = $request->get('context');
        $logger->info('$context = ' . print_r($context, true));

        // Function process
        $user = $securityContext->getToken()->getUser();

        $userManager->updateFollowDebateContextEmailNotification($user->getId(), $id, false, $context);

        return true;
    }
}
