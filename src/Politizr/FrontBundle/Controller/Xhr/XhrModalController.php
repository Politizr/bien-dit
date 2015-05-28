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
     *  Chargement des filtres du classement
     */
    public function filtersAction(Request $request)
    {
        $logger = $this->get('logger');
        $logger->info('*** filtersAction');

        $jsonResponse = $this->get('politizr.routing.ajax')->createJsonHtmlResponse(
            'politizr.service.modal',
            'filters'
        );

        return $jsonResponse;
    }

    /**
     * Liste des débats type "classement"
     */
    public function rankingDebateListAction(Request $request)
    {
        $logger = $this->get('logger');
        $logger->info('*** rankingDebateListAction');

        $jsonResponse = $this->get('politizr.routing.ajax')->createJsonHtmlResponse(
            'politizr.service.modal',
            'rankingDebateList'
        );

        return $jsonResponse;
    }

    /**
     * Liste des profils type "classement"
     */
    public function rankingUserListAction(Request $request)
    {
        $logger = $this->get('logger');
        $logger->info('*** rankingUserListAction');

        $jsonResponse = $this->get('politizr.routing.ajax')->createJsonHtmlResponse(
            'politizr.service.modal',
            'rankingUserList'
        );

        return $jsonResponse;
    }

    /**
     * Liste des débats type "suggestion"
     */
    public function suggestionDebateListAction(Request $request)
    {
        $logger = $this->get('logger');
        $logger->info('*** suggestionDebateListAction');

        $jsonResponse = $this->get('politizr.routing.ajax')->createJsonHtmlResponse(
            'politizr.service.modal',
            'suggestionDebateList'
        );

        return $jsonResponse;
    }

    /**
     * Liste des profils type "suggestion"
     */
    public function suggestionUserListAction(Request $request)
    {
        $logger = $this->get('logger');
        $logger->info('*** suggestionUserListAction');

        $jsonResponse = $this->get('politizr.routing.ajax')->createJsonHtmlResponse(
            'politizr.service.modal',
            'suggestionUserList'
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
}
