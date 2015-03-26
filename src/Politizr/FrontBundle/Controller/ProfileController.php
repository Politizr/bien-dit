<?php

namespace Politizr\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Politizr\Exception\InconsistentDataException;
use Politizr\Model\PUserQuery;

/**
 * Gestion profil fonctions communes citoyens / débatteurs
 *
 * @author Lionel Bouzonville
 */
class ProfileController extends Controller
{

    /* ######################################################################################################## */
    /*                                                  FONCTIONS AJAX                                          */
    /* ######################################################################################################## */


    /* ######################################################################################################## */
    /*                                                  NOTIFICATIONS                                           */
    /* ######################################################################################################## */

    /**
     *  Liste des notifications
     */
    public function notificationsLoadAction(Request $request)
    {
        $logger = $this->get('logger');
        $logger->info('*** notificationsLoadAction');

        $jsonResponse = $this->get('politizr.routing.ajax')->createJsonHtmlResponse(
            'politizr.service.user',
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

        $jsonResponse = $this->get('politizr.routing.ajax')->createJsonResponse(
            'politizr.service.user',
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

        $jsonResponse = $this->get('politizr.routing.ajax')->createJsonResponse(
            'politizr.service.user',
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

        $jsonResponse = $this->get('politizr.routing.ajax')->createJsonResponse(
            'politizr.service.user',
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

        $jsonResponse = $this->get('politizr.routing.ajax')->createJsonResponse(
            'politizr.service.user',
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

        $jsonResponse = $this->get('politizr.routing.ajax')->createJsonResponse(
            'politizr.service.user',
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

        $jsonResponse = $this->get('politizr.routing.ajax')->createJsonResponse(
            'politizr.service.user',
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

        $jsonResponse = $this->get('politizr.routing.ajax')->createJsonResponse(
            'politizr.service.user',
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

        $jsonResponse = $this->get('politizr.routing.ajax')->createJsonResponse(
            'politizr.service.user',
            'notifDebateContextUnsubscribe'
        );

        return $jsonResponse;
    }

    /* ######################################################################################################## */
    /*                                                     REPUTATION                                           */
    /* ######################################################################################################## */

    /**
     *  Historique des actions
     */
    public function historyActionsListAction(Request $request)
    {
        $logger = $this->get('logger');
        $logger->info('*** historyActionsList');

        $jsonResponse = $this->get('politizr.routing.ajax')->createJsonHtmlResponse(
            'politizr.service.user',
            'historyActionsList'
        );

        return $jsonResponse;
    }
}
