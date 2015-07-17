<?php

namespace Politizr\FrontBundle\Controller\Xhr;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;

/**
 *  Gestion de la recherche / appels XHR
 *
 *  @author Lionel Bouzonville
 */
class XhrSearchController extends Controller
{
    /**
     *  Pagination ES
     *
     */
    public function searchPageAction(Request $request)
    {
        $logger = $this->get('logger');
        $logger->info('*** searchPageAction');

        $jsonResponse = $this->get('politizr.routing.xhr')->createJsonHtmlResponse(
            $request,
            'politizr.xhr.search',
            'search'
        );

        return $jsonResponse;
    }
}
