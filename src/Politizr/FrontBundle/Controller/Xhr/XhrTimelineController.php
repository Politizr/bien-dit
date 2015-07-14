<?php

namespace Politizr\FrontBundle\Controller\Xhr;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;

/**
 *  Gestion de la timeline / appels XHR
 *
 *  @author Lionel Bouzonville
 */
class XhrTimelineController extends Controller
{
    /**
     *  Chargement d'une box modal
     */
    public function paginatedListAction(Request $request)
    {
        $logger = $this->get('logger');
        $logger->info('*** paginatedListAction');

        $jsonResponse = $this->get('politizr.routing.xhr')->createJsonHtmlResponse(
            'politizr.xhr.timeline',
            'timelinePaginated'
        );

        return $jsonResponse;
    }
}
