<?php
namespace Politizr\FrontBundle\Lib\Routing;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Politizr\Exception\FormValidationException;

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
     * XHR request rendering html blocks
     */
    public function createJsonHtmlResponse(Request $request, $serviceName, $methodCallback)
    {
        $logger = $this->sc->get('logger');
        $logger->info('*** createJsonHtmlResponse');
        
        try {
            if ($request->isXmlHttpRequest()) {
                // service call
                $service = $this->sc->get($serviceName);
                $htmlRenders = $service->$methodCallback($request);

                $jsonSuccess = array (
                    'success' => true
                );

                $jsonResponse = array_merge($jsonSuccess, $htmlRenders);
            } else {
                throw new NotFoundHttpException('Not a XHR request');
            }
        } catch (FormValidationException $e) {
            $logger->info('Exception = ' . print_r($e->getMessage(), true));
            $jsonResponse = array('error' => $e->getMessage());
        } catch (\Exception $e) {
            throw $e;
        }

        // JSON formatted success/error message
        $response = new Response(json_encode($jsonResponse));
        return $response;
    }


    /**
     * XHR request rendering true/false response
     */
    public function createJsonResponse(Request $request, $serviceName, $methodCallback)
    {
        $logger = $this->sc->get('logger');
        $logger->info('*** createJsonResponse');
        
        try {
            if ($request->isXmlHttpRequest()) {
                // service call
                $service = $this->sc->get($serviceName);
                $success = $service->$methodCallback($request);

                $jsonResponse = array (
                    'success' => true
                );
            } else {
                throw new NotFoundHttpException('Not a XHR request');
            }
        } catch (FormValidationException $e) {
            $logger->info('Exception = ' . print_r($e->getMessage(), true));
            $jsonResponse = array('error' => $e->getMessage());
        } catch (\Exception $e) {
            throw $e;
        }

        // JSON formatted success/error message
        $response = new Response(json_encode($jsonResponse));
        return $response;
    }


    /**
     * XHR request rendering redirection URL
     */
    public function createJsonRedirectResponse(Request $request, $serviceName, $methodCallback)
    {
        $logger = $this->sc->get('logger');
        $logger->info('*** createJsonRedirectResponse');
        
        try {
            if ($request->isXmlHttpRequest()) {
                // service call
                $service = $this->sc->get($serviceName);
                $url = $service->$methodCallback($request);

                $jsonSuccess = array (
                    'success' => true
                );

                $jsonResponse = array_merge($jsonSuccess, $url);
            } else {
                throw new NotFoundHttpException('Not a XHR request');
            }
        } catch (FormValidationException $e) {
            $logger->info('Exception = ' . print_r($e->getMessage(), true));
            $jsonResponse = array('error' => $e->getMessage());
        } catch (\Exception $e) {
            $logger->info('Exception = ' . print_r($e->getMessage(), true));
            $jsonResponse = array('error' => $e->getMessage());
            throw $e;
        }

        // JSON formatted success/error message
        $response = new Response(json_encode($jsonResponse));
        return $response;
    }
}
