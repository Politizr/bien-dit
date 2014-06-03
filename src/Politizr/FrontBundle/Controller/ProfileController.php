<?php

namespace Politizr\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Politizr\Model\PUserQuery;
use Politizr\Model\PTagQuery;

use Politizr\Model\PUser;


/**
 * Gestion des profils
 *
 * TODO:
 *
 * @author Lionel Bouzonville
 */
class ProfileController extends Controller {

    /* ######################################################################################################## */
    /*                                                 ROUTING CLASSIQUE                                        */
    /* ######################################################################################################## */


    /* ######################################################################################################## */
    /*                                                  FONCTIONS AJAX                                          */
    /* ######################################################################################################## */


    /**
     *      Renvoit un tableau contenant les tags
     */
    public function getPTagsAction(Request $request) {
        $logger = $this->get('logger');
        $logger->info('*** getPTagsAction');
        
        try {
            if ($request->isXmlHttpRequest()) {
                // Récupération args
                $pTTagTypeId = $request->get('pTTagTypeId');
                $logger->info('$pTTagTypeId = ' . print_r($pTTagTypeId, true));

                // Récupération des tags
                // require_once dirname(__FILE__) . '/../../../../vendor/propel/propel1/runtime/lib/parser/PropelJSONParser.php';
                $pTags = PTagQuery::create()
                	->select(array('id', 'title'))
                	->filterByOnline(true)
                	->filterByPTTagTypeId($pTTagTypeId)
                	->orderByTitle()
                	->find()
                	->toArray();
                $logger->info('$pTags = ' . print_r($pTags, true));

                // Construction de la réponse
                $jsonResponse = array (
                    'success' => true,
                    'pTags' => $pTags
                );
            } else {
                throw $this->createNotFoundException('Not a XHR request');
            }
        } catch (NotFoundHttpException $e) {
            $logger->info('Exception = ' . print_r($e->getMessage(), true));
            $jsonResponse = array('error' => $e->getMessage());
        } catch (\Exception $e) {
            $logger->info('Exception = ' . print_r($e->getMessage(), true));
            $jsonResponse = array('error' => $e->getMessage());
        }

        // JSON formatted success/error message
        $response = new Response(json_encode($jsonResponse));
        return $response;
    }

}