<?php

namespace Politizr\FrontBundle\Controller\Xhr;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;

/**
 * XHR notification
 *
 * @author Lionel Bouzonville
 */
class XhrNotificationController extends Controller
{
    /**
     *  Liste des notifications
     */
    public function notificationsLoadAction(Request $request)
    {
        $logger = $this->get('logger');
        $logger->info('*** notificationsLoadAction');

        $jsonResponse = $this->get('politizr.routing.xhr')->createJsonHtmlResponse(
            'politizr.xhr.notification',
            'notificationsLoad'
        );

        return $jsonResponse;
    }

    /**
     *  Notification checkée
     */
    public function notificationCheckAction(Request $request)
    {
        $logger = $this->get('logger');
        $logger->info('*** notificationCheckAction');

        $jsonResponse = $this->get('politizr.routing.xhr')->createJsonResponse(
            'politizr.xhr.notification',
            'notificationCheck'
        );

        return $jsonResponse;
    }

    /**
     *  Check/Uncheck de toutes les notifications
     */
    public function notificationsCheckAllAction(Request $request)
    {
        $logger = $this->get('logger');
        $logger->info('*** notificationsCheckAllAction');

        $jsonResponse = $this->get('politizr.routing.xhr')->createJsonResponse(
            'politizr.xhr.notification',
            'notificationsCheckAll'
        );

        return $jsonResponse;
    }


    /**
     *  Souscrit une notification par email
     */
    public function notifEmailSubscribeAction(Request $request)
    {
        $logger = $this->get('logger');
        $logger->info('*** notifEmailSubscribeAction');

        $jsonResponse = $this->get('politizr.routing.xhr')->createJsonResponse(
            'politizr.xhr.notification',
            'notifEmailSubscribe'
        );

        return $jsonResponse;
    }
   

    /**
     *  Désouscrit une notification par email
     */
    public function notifEmailUnsubscribeAction(Request $request)
    {
        $logger = $this->get('logger');
        $logger->info('*** notifEmailUnsubscribeAction');

        $jsonResponse = $this->get('politizr.routing.xhr')->createJsonResponse(
            'politizr.xhr.notification',
            'notifEmailUnsubscribe'
        );

        return $jsonResponse;
    }

    /**
     *  Souscrit une notification contextuelle à un profil
     */
    public function notifUserContextSubscribeAction(Request $request)
    {
        $logger = $this->get('logger');
        $logger->info('*** notifUserContextSubscribeAction');

        $jsonResponse = $this->get('politizr.routing.xhr')->createJsonResponse(
            'politizr.xhr.notification',
            'notifUserContextSubscribe'
        );

        return $jsonResponse;
    }
   

    /**
     *  Désouscrit une notification contextuelle à un profil
     */
    public function notifUserContextUnsubscribeAction(Request $request)
    {
        $logger = $this->get('logger');
        $logger->info('*** notifUserContextUnsubscribeAction');

        $jsonResponse = $this->get('politizr.routing.xhr')->createJsonResponse(
            'politizr.xhr.notification',
            'notifUserContextUnsubscribe'
        );

        return $jsonResponse;
    }

    /**
     *  Souscrit une notification contextuelle à un débat
     */
    public function notifDebateContextSubscribeAction(Request $request)
    {
        $logger = $this->get('logger');
        $logger->info('*** notifDebateContextSubscribeAction');

        $jsonResponse = $this->get('politizr.routing.xhr')->createJsonResponse(
            'politizr.xhr.notification',
            'notifDebateContextSubscribe'
        );

        return $jsonResponse;
    }
   
    /**
     *  Désouscrit une notification contextuelle à un débat
     */
    public function notifDebateContextUnsubscribeAction(Request $request)
    {
        $logger = $this->get('logger');
        $logger->info('*** notifDebateContextUnsubscribeAction');

        $jsonResponse = $this->get('politizr.routing.xhr')->createJsonResponse(
            'politizr.xhr.notification',
            'notifDebateContextUnsubscribe'
        );

        return $jsonResponse;
    }
}
