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
     * Liste des débats type "classement"
     */
    public function rankingDebateListAction(Request $request)
    {
        $logger = $this->get('logger');
        $logger->info('*** rankingDebateListAction');

        $jsonResponse = $this->get('politizr.routing.xhr')->createJsonHtmlResponse(
            $request,
            'politizr.xhr.modal',
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

        $jsonResponse = $this->get('politizr.routing.xhr')->createJsonHtmlResponse(
            $request,
            'politizr.xhr.modal',
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

        $jsonResponse = $this->get('politizr.routing.xhr')->createJsonHtmlResponse(
            $request,
            'politizr.xhr.modal',
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

        $jsonResponse = $this->get('politizr.routing.xhr')->createJsonHtmlResponse(
            $request,
            'politizr.xhr.modal',
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

        $jsonResponse = $this->get('politizr.routing.xhr')->createJsonHtmlResponse(
            $request,
            'politizr.xhr.modal',
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

        $jsonResponse = $this->get('politizr.routing.xhr')->createJsonHtmlResponse(
            $request,
            'politizr.xhr.modal',
            'tagUserList'
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

        $jsonResponse = $this->get('politizr.routing.xhr')->createJsonHtmlResponse(
            $request,
            'politizr.xhr.modal',
            'organizationUserList'
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

        $jsonResponse = $this->get('politizr.routing.xhr')->createJsonHtmlResponse(
            $request,
            'politizr.xhr.modal',
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

        $jsonResponse = $this->get('politizr.routing.xhr')->createJsonHtmlResponse(
            $request,
            'politizr.xhr.modal',
            'followedUserList'
        );

        return $jsonResponse;
    }
}
