<?php

namespace Politizr\FrontBundle\Controller\Xhr;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;

/**
 *  Gestion du processus d'inscription / appels XHR
 *
 *  @author Lionel Bouzonville
 */
class XhrInscriptionController extends Controller
{
    /**
     *  Validation de la connexion
     */
    public function loginCheckAction(Request $request)
    {
        $logger = $this->get('logger');
        $logger->info('*** loginCheckAction');

        $jsonResponse = $this->get('politizr.routing.ajax')->createJsonHtmlResponse(
            'politizr.service.security',
            'loginCheck'
        );

        return $jsonResponse;
    }

    /**
     *  Validation mot de passe oublié
     */
    public function lostPasswordCheckAction(Request $request)
    {
        $logger = $this->get('logger');
        $logger->info('*** lostPasswordCheckAction');

        $jsonResponse = $this->get('politizr.routing.ajax')->createJsonResponse(
            'politizr.service.security',
            'lostPasswordCheck'
        );

        return $jsonResponse;
    }

    /**
     *      Action "Procéder au paiement":
     *          1/  génération de la commande
     *          2/  suivant le type de paiement > création des formulaires (ATOS. Paypal)
     *          3/  construction de la réponse
     *
     *      TODO / + de contrôles exceptions
     *
     */
    public function paymentProcessAction(Request $request)
    {
        $logger = $this->get('logger');
        $logger->info('*** paymentProcessAction');

        $jsonResponse = $this->get('politizr.routing.ajax')->createJsonHtmlResponse(
            'politizr.service.security',
            'paymentProcess'
        );

        return $jsonResponse;
    }
}
