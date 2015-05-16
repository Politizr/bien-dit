<?php

namespace Politizr\FrontBundle\Controller\Xhr;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;

/**
 *  Gestion des modals / appels XHR
 *
 *  @author Lionel Bouzonville
 */
class XhrModalController extends Controller
{
    /**
     *  Chargement d'une box modal
     */
    public function modalPaginatedListAction(Request $request)
    {
        $logger = $this->get('logger');
        $logger->info('*** modalPaginatedListAction');

        $jsonResponse = $this->get('politizr.routing.ajax')->createJsonHtmlResponse(
            'politizr.service.modal',
            'modalPaginatedList'
        );

        return $jsonResponse;
    }

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
            'politizr.service.modal',
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
            'politizr.service.modal',
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
            'politizr.service.modal',
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
            'politizr.service.modal',
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
            'politizr.service.modal',
            'followedUserList'
        );

        return $jsonResponse;
    }

    /**
     *  Chargement d'une liste de users d'une organisation
     */
    public function organizationUserListAction(Request $request)
    {
        $logger = $this->get('logger');
        $logger->info('*** organizationUserListAction');

        $jsonResponse = $this->get('politizr.routing.ajax')->createJsonHtmlResponse(
            'politizr.service.modal',
            'organizationUserList'
        );

        return $jsonResponse;
    }

    /**
     *  Chargement d'une liste de debats par tag
     */
    public function tagDebateListAction(Request $request)
    {
        $logger = $this->get('logger');
        $logger->info('*** tagDebateListAction');

        $jsonResponse = $this->get('politizr.routing.ajax')->createJsonHtmlResponse(
            'politizr.service.modal',
            'tagDebateList'
        );

        return $jsonResponse;
    }

    /**
     *  Chargement d'une liste de users par tag
     */
    public function tagUserListAction(Request $request)
    {
        $logger = $this->get('logger');
        $logger->info('*** tagUserListAction');

        $jsonResponse = $this->get('politizr.routing.ajax')->createJsonHtmlResponse(
            'politizr.service.modal',
            'tagUserList'
        );

        return $jsonResponse;
    }
}
