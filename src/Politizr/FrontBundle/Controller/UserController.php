<?php

namespace Politizr\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Politizr\Exception\InconsistentDataException;

/**
 * Gestion user
 *
 * @author Lionel Bouzonville
 */
class UserController extends Controller
{

    /* ######################################################################################################## */
    /*                                                  FONCTIONS AJAX                                          */
    /* ######################################################################################################## */


    /**
     *  Suivre / Ne plus suivre user
     */
    public function followAction(Request $request)
    {
        $logger = $this->get('logger');
        $logger->info('*** followAction');

        $jsonResponse = $this->get('politizr.routing.ajax')->createJsonHtmlResponse(
            'politizr.service.user',
            'follow'
        );

        return $jsonResponse;
    }
}
