<?php

namespace Politizr\FrontBundle\Controller\Xhr;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;

/**
 *  Gestion des listings / appels XHR
 *
 *  @author Lionel Bouzonville
 */
class XhrListingController extends Controller
{
    /**
     *  Chargement d'une liste type "timeline"
     */
    public function timelinePaginatedAction(Request $request)
    {
        $logger = $this->get('logger');
        $logger->info('*** timelinePaginatedAction');

        $jsonResponse = $this->get('politizr.routing.ajax')->createJsonHtmlResponse(
            'politizr.service.timeline',
            'timelinePaginated'
        );

        return $jsonResponse;
    }

    /**
     *  Chargement d'une liste de debats "actualité"
     */
    public function dailyDebateListAction(Request $request)
    {
        $logger = $this->get('logger');
        $logger->info('*** dailyDebateListAction');

        $jsonResponse = $this->get('politizr.routing.ajax')->createJsonHtmlResponse(
            'politizr.service.document',
            'dailyDebateList'
        );

        return $jsonResponse;
    }

    /**
     *  Chargement d'une liste de users
     */
    public function dailyUserListAction(Request $request)
    {
        $logger = $this->get('logger');
        $logger->info('*** dailyUserListAction');

        $jsonResponse = $this->get('politizr.routing.ajax')->createJsonHtmlResponse(
            'politizr.service.user',
            'dailyUserList'
        );

        return $jsonResponse;
    }

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

    /**
     * Chargement d'une liste de debats suivis par le user
     */
    public function followedDebateListAction(Request $request)
    {
        $logger = $this->get('logger');
        $logger->info('*** followedDebateListAction');

        $jsonResponse = $this->get('politizr.routing.ajax')->createJsonHtmlResponse(
            'politizr.service.document',
            'followedDebateList'
        );

        return $jsonResponse;
    }

    /**
     * Chargement d'une liste de users
     */
    public function followedUserListAction(Request $request)
    {
        $logger = $this->get('logger');
        $logger->info('*** followedUserListAction');

        $jsonResponse = $this->get('politizr.routing.ajax')->createJsonHtmlResponse(
            'politizr.service.user',
            'followedUserList'
        );

        return $jsonResponse;
    }
}
