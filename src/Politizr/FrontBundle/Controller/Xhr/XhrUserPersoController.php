<?php

namespace Politizr\FrontBundle\Controller\Xhr;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;

/**
 *  Gestion des informations perso du user / appels XHR
 *
 *  @author Lionel Bouzonville
 */
class XhrUserPersoController extends Controller
{
    /**
     *  Mise à jour des informations personnelles du user
     */
    public function userPersoUpdateAction(Request $request)
    {
        $logger = $this->get('logger');
        $logger->info('*** userPersoUpdateAction');

        $jsonResponse = $this->get('politizr.routing.xhr')->createJsonResponse(
            'politizr.xhr.user',
            'userPersoUpdate'
        );

        return $jsonResponse;
    }
}
