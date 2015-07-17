<?php

namespace Politizr\FrontBundle\Controller\Xhr;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;

/**
 *  Gestion des follow / appels XHR
 *
 *  @author Lionel Bouzonville
 */
class XhrFollowController extends Controller
{
    /**
     *  Suivre / Ne plus suivre user
     */
    public function followUserAction(Request $request)
    {
        $logger = $this->get('logger');
        $logger->info('*** followAction');

        $jsonResponse = $this->get('politizr.routing.xhr')->createJsonHtmlResponse(
            $request,
            'politizr.xhr.user',
            'follow'
        );

        return $jsonResponse;
    }

    /**
     *  Suivre / Ne plus suivre debat
     */
    public function followDebateAction(Request $request)
    {
        $logger = $this->get('logger');
        $logger->info('*** followAction');

        $jsonResponse = $this->get('politizr.routing.xhr')->createJsonHtmlResponse(
            $request,
            'politizr.xhr.document',
            'follow'
        );

        return $jsonResponse;
    }
}
