<?php

namespace Politizr\FrontBundle\Controller\Xhr;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;

/**
 *  Gestion des tags / appels XHR
 *
 *  @author Lionel Bouzonville
 */
class XhrTagController extends Controller
{
    /**
     *  Association d'un tag à un débat
     */
    public function debateAddTagAction(Request $request)
    {
        $logger = $this->get('logger');
        $logger->info('*** debateAddTagAction');

        $jsonResponse = $this->get('politizr.routing.xhr')->createJsonHtmlResponse(
            $request,
            'politizr.xhr.tag',
            'debateAddTag'
        );

        return $jsonResponse;
    }

    /**
     *  Suppression de l'association d'un tag à un débat
     */
    public function debateDeleteTagAction(Request $request)
    {
        $logger = $this->get('logger');
        $logger->info('*** debateDeleteTagAction');

        $jsonResponse = $this->get('politizr.routing.xhr')->createJsonResponse(
            $request,
            'politizr.xhr.tag',
            'debateDeleteTag'
        );

        return $jsonResponse;
    }

    /**
     *  Association d'un tag suivi d'un user
     */
    public function userFollowAddTagAction(Request $request)
    {
        $logger = $this->get('logger');
        $logger->info('*** userFollowAddTagAction');

        $jsonResponse = $this->get('politizr.routing.xhr')->createJsonHtmlResponse(
            $request,
            'politizr.xhr.tag',
            'userFollowAddTag'
        );

        return $jsonResponse;
    }

    /**
     *  Suppression de l'association d'un tag suivi d'un user
     */
    public function userFollowDeleteTagAction(Request $request)
    {
        $logger = $this->get('logger');
        $logger->info('*** userFollowDeleteTagAction');

        $jsonResponse = $this->get('politizr.routing.xhr')->createJsonResponse(
            $request,
            'politizr.xhr.tag',
            'userFollowDeleteTag'
        );

        return $jsonResponse;
    }

    /**
     *  Association d'un tag caractérisant un user
     */
    public function userTaggedAddTagAction(Request $request)
    {
        $logger = $this->get('logger');
        $logger->info('*** userTaggedAddTagAction');

        $jsonResponse = $this->get('politizr.routing.xhr')->createJsonHtmlResponse(
            $request,
            'politizr.xhr.tag',
            'userTaggedAddTag'
        );

        return $jsonResponse;
    }

    /**
     *  Suppression de l'association d'un tag caractérisant un user
     */
    public function userTaggedDeleteTagAction(Request $request)
    {
        $logger = $this->get('logger');
        $logger->info('*** userTaggedDeleteTagAction');

        $jsonResponse = $this->get('politizr.routing.xhr')->createJsonResponse(
            $request,
            'politizr.xhr.tag',
            'userTaggedDeleteTag'
        );

        return $jsonResponse;
    }
}
