<?php
namespace Politizr\FrontBundle\Lib\Routing;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Encapsulate the call of an XHR request: manage exception and call the service associated with the xhr request.
 *
 * @todo
 * - manage different actions depending of sort of throwned exceptions
 *
 * @author Lionel Bouzonville
 */
class XhrCreateResponse
{
    private $sc;

    /**
     *
     */
    public function __construct($serviceContainer)
    {
        $this->sc = $serviceContainer;
    }


    /**
     *    Construction d'une réponse Ajax effectuant des rendu de blocs HTML.
     *
     */
    public function createJsonHtmlResponse($serviceName, $methodCallback)
    {
        $logger = $this->sc->get('logger');
        $logger->info('*** createJsonHtmlResponse');
        
        $request = $this->sc->get('request');
        try {
            if ($request->isXmlHttpRequest()) {
                // Appel du service métier
                $service = $this->sc->get($serviceName);
                $htmlRenders = $service->$methodCallback();

                // Construction de la réponse
                $jsonSuccess = array (
                    'success' => true
                );

                $jsonResponse = array_merge($jsonSuccess, $htmlRenders);
            } else {
                throw new NotFoundHttpException('Not a XHR request');
            }
        } catch (\Exception $e) {
            $logger->info('Exception = ' . print_r($e->getMessage(), true));
            $jsonResponse = array('error' => $e->getMessage());
        }

        // JSON formatted success/error message
        $response = new Response(json_encode($jsonResponse));
        return $response;
    }


    /**
     *    Construction d'une réponse Ajax effectuant un retour success simple.
     *
     */
    public function createJsonResponse($serviceName, $methodCallback)
    {
        $logger = $this->sc->get('logger');
        $logger->info('*** createJsonResponse');
        
        $request = $this->sc->get('request');
        try {
            if ($request->isXmlHttpRequest()) {
                // Appel du service métier
                $service = $this->sc->get($serviceName);
                $success = $service->$methodCallback();

                // Construction de la réponse
                $jsonResponse = array (
                    'success' => true
                );
            } else {
                throw new NotFoundHttpException('Not a XHR request');
            }
        } catch (\Exception $e) {
            $logger->info('Exception = ' . print_r($e->getMessage(), true));
            $jsonResponse = array('error' => $e->getMessage());
        }

        // JSON formatted success/error message
        $response = new Response(json_encode($jsonResponse));
        return $response;
    }


    /**
     *    Construction d'une réponse Ajax effectuant un retour possédant une URL de redirection.
     *
     */
    public function createJsonRedirectResponse($serviceName, $methodCallback)
    {
        $logger = $this->sc->get('logger');
        $logger->info('*** createJsonRedirectResponse');
        
        $request = $this->sc->get('request');
        try {
            if ($request->isXmlHttpRequest()) {
                // Appel du service métier
                $service = $this->sc->get($serviceName);
                $url = $service->$methodCallback();

                // Construction de la réponse
                $jsonSuccess = array (
                    'success' => true
                );

                $jsonResponse = array_merge($jsonSuccess, $url);
            } else {
                throw new NotFoundHttpException('Not a XHR request');
            }
        } catch (\Exception $e) {
            $logger->info('Exception = ' . print_r($e->getMessage(), true));
            $jsonResponse = array('error' => $e->getMessage());
        }

        // JSON formatted success/error message
        $response = new Response(json_encode($jsonResponse));
        return $response;
    }
}
