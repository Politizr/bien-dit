<?php

namespace Politizr\FrontBundle\Controller\Xhr;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;

use Politizr\Constant\XhrConstants;

/**
 * Generic XHR controller
 *
 * @author Lionel Bouzonville
 */
class XhrGenericController extends Controller
{
    /**
     * Manage every POST XHR request
     *
     * @param Request $request
     * @param string $xhrRoute url rewriting
     * @param string $xhrService politizr.xhr.<name of the service to call>
     * @param string $xhrMethod service's method to call
     * @param integer $xhrType json response method to call from "politizr.routing.xhr" service
     */
    public function xhrAction(Request $request, $xhrRoute, $xhrService, $xhrMethod, $xhrType)
    {
        switch ($xhrType) {
            case XhrConstants::RETURN_BOOLEAN:
                $jsonResponseMethod = 'createJsonResponse';
                break;
            case XhrConstants::RETURN_HTML:
                $jsonResponseMethod = 'createJsonHtmlResponse';
                break;
            case XhrConstants::RETURN_URL:
                $jsonResponseMethod = 'createJsonRedirectResponse';
                break;
            default:
                throw InconsistentDataException(sprintf('XHR type %s is not managed.', $xhrType));
        }

        $jsonResponse = $this->get('politizr.routing.xhr')->$jsonResponseMethod(
            $request,
            sprintf('politizr.xhr.%s', $xhrService),
            $xhrMethod
        );

        return $jsonResponse;
    }
}
