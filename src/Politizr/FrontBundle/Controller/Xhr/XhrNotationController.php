<?php

namespace Politizr\FrontBundle\Controller\Xhr;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;

/**
 *  Gestion des notations / appels XHR
 *
 *  @author Lionel Bouzonville
 */
class XhrNotationController extends Controller
{
    /**
     *  Note débat / réaction / commentaire
     */
    public function noteAction(Request $request)
    {
        $logger = $this->get('logger');
        $logger->info('*** noteAction');

        $jsonResponse = $this->get('politizr.routing.xhr')->createJsonHtmlResponse(
            $request,
            'politizr.xhr.document',
            'note'
        );

        return $jsonResponse;
    }
}
