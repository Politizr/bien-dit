<?php

namespace Politizr\AdminBundle\Controller\AdminAjax;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Politizr\Model\PUserQuery;
use Politizr\Model\PTagQuery;
use Politizr\Model\PUTaggedTQuery;
use Politizr\Model\PUFollowTQuery;
use Politizr\Model\PDDTaggedTQuery;

use Politizr\Model\PUser;
use Politizr\Model\PTag;
use Politizr\Model\PTTagType;
use Politizr\Model\PUTaggedT;
use Politizr\Model\PUFollowT;
use Politizr\Model\PDDTaggedT;


/**
 * Gestion des actions ajax intégrées dans l'admingenerator
 * TODO: réflexion sur le code dupliqué / ProfileController > oui / non à mutualiser?
 *
 * @author Lionel Bouzonville
 */
class AdminAjaxController extends Controller {

    /**
     *      Renvoit un tableau contenant les tags
     *      
     */
    public function getPTagsAction(Request $request) {
        $logger = $this->get('logger');
        $logger->info('*** getPTagsAction');
        
        try {
            if ($request->isXmlHttpRequest()) {
                // Récupération args
                $ptTagTypeId = $request->get('ptTagTypeId');
                $logger->info('$ptTagTypeId = ' . print_r($ptTagTypeId, true));
                $zoneId = $request->get('zoneId');
                $logger->info('$zoneId = ' . print_r($zoneId, true));

                // Récupération des tags
                // require_once dirname(__FILE__) . '/../../../../vendor/propel/propel1/runtime/lib/parser/PropelJSONParser.php';
                $pTags = PTagQuery::create()
                	->select(array('id', 'title'))
                	->filterByOnline(true)
                	->filterByPTTagTypeId($ptTagTypeId)
                	->orderByTitle()
                	->find()
                	->toArray();
                // $logger->info('$pTags = ' . print_r($pTags, true));

                // Construction de la réponse
                $jsonResponse = array (
                    'success' => true,
                    'pTags' => $pTags,
                    'zoneId' => $zoneId,
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

    /**
     *     Ajoute un tag au taggage d'un utilisateur.
     *     Si le tag n'existe pas, il est préalablement créé.
     *
     */
    public function addPUTaggedTPTagAction(Request $request) {
        $logger = $this->get('logger');
        $logger->info('*** addPUTaggedTPTagAction');
        
        try {
            if ($request->isXmlHttpRequest()) {
                // Récupération args
                $pTagTitle = $request->get('pTagTitle');
                $logger->info('$pTagTitle = ' . print_r($pTagTitle, true));
                $pTagId = $request->get('pTagId');
                $logger->info('$pTagId = ' . print_r($pTagId, true));
                $ptTagTypeId = $request->get('ptTagTypeId');
                $logger->info('$ptTagTypeId = ' . print_r($ptTagTypeId, true));
                $pUserId = $request->get('objectId');
                $logger->info('$pUserId = ' . print_r($pUserId, true));

                // Gestion tag non existant
                if (!$pTagId) {
                    $pTagId = PTagQuery::create()->addTag($pTagTitle, $ptTagTypeId, null, true);
                }

                // Association du tag au user
                if ($pTagId) {
                    $created = PUTaggedTQuery::create()->addElement($pUserId, $pTagId);
                }

                if (!$created) {
                    $htmlTag = null;
                } else  {
                    // Construction du rendu du tag
                    $pTag = PTagQuery::create()->findPk($pTagId);
                    $templating = $this->get('templating');
                    $htmlTag = $templating->render(
                                        'PolitizrAdminBundle:Fragment:Tag.html.twig', array(
                                            'objectId' => $pUserId,
                                            'pTagId' => $pTag->getId(),
                                            'pTagTitle' => $pTag->getTitle(),
                                            'deleteUrl' => $this->container->get('router')->generate('AdminDeletePUTaggedTPTag')
                                            )
                                );
                }


                // Construction de la réponse
                $jsonResponse = array (
                    'success' => true,
                    'created' => $created,
                    'htmlTag' => $htmlTag
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

    /**
     *     Supprime un tag associé au puser courant et renvoit le rendu associé
     */
    public function deletePUTaggedTPTagAction(Request $request) {
        $logger = $this->get('logger');
        $logger->info('*** deletePUTaggedTPTagAction');
        
        try {
            if ($request->isXmlHttpRequest()) {
                // Récupération args
                $pTagId = $request->get('pTagId');
                $logger->info('$pTagId = ' . print_r($pTagId, true));
                $pUserId = $request->get('objectId');
                $logger->info('$pUserId = ' . print_r($pUserId, true));

                // Suppression du tag / profil
                $deleted = PUTaggedTQuery::create()->deleteElement($pUserId, $pTagId);
                $logger->info('$deleted = ' . print_r($deleted, true));

                // Construction de la réponse
                $jsonResponse = array (
                    'success' => true,
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

    /**
     *     Ajoute un tag à un débat.
     *     Si le tag n'existe pas, il est préalablement créé.
     *
     */
    public function addPDDTaggedTPTagAction(Request $request) {
        $logger = $this->get('logger');
        $logger->info('*** addPDDTaggedTPTagAction');
        
        try {
            if ($request->isXmlHttpRequest()) {
                // Récupération args
                $pTagTitle = $request->get('pTagTitle');
                $logger->info('$pTagTitle = ' . print_r($pTagTitle, true));
                $pTagId = $request->get('pTagId');
                $logger->info('$pTagId = ' . print_r($pTagId, true));
                $ptTagTypeId = $request->get('ptTagTypeId');
                $logger->info('$ptTagTypeId = ' . print_r($ptTagTypeId, true));
                $pdDebateId = $request->get('objectId');
                $logger->info('$pdDebateId = ' . print_r($pdDebateId, true));

                // Gestion tag non existant
                if (!$pTagId) {
                    $pTagId = PTagQuery::create()->addTag($pTagTitle, $ptTagTypeId, null, true);
                }

                // Association du tag au debat
                if ($pTagId) {
                    $created = PDDTaggedTQuery::create()->addElement($pdDebateId, $pTagId);
                    $logger->info('$created = ' . print_r($created, true));
                }

                if (!$created) {
                    $htmlTag = null;
                } else  {
                    // Construction du rendu du tag
                    $pTag = PTagQuery::create()->findPk($pTagId);
                    $templating = $this->get('templating');
                    $htmlTag = $templating->render(
                                        'PolitizrAdminBundle:Fragment:Tag.html.twig', array(
                                            'objectId' => $pdDebateId,
                                            'pTagId' => $pTag->getId(),
                                            'pTagTitle' => $pTag->getTitle(),
                                            'deleteUrl' => $this->container->get('router')->generate('AdminDeletePDDTaggedTPTag')
                                            )
                                );
                }


                // Construction de la réponse
                $jsonResponse = array (
                    'success' => true,
                    'created' => $created,
                    'htmlTag' => $htmlTag
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

    /**
     *     Supprime un tag associé au débat courant et renvoit le rendu associé
     */
    public function deletePDDTaggedTPTagAction(Request $request) {
        $logger = $this->get('logger');
        $logger->info('*** deletePDDTaggedTPTagAction');
        
        try {
            if ($request->isXmlHttpRequest()) {
                // Récupération args
                $pTagId = $request->get('pTagId');
                $logger->info('$pTagId = ' . print_r($pTagId, true));
                $pdDebateId = $request->get('objectId');
                $logger->info('$pdDebateId = ' . print_r($pdDebateId, true));

                // Suppression du tag / profil
                $deleted = PDDTaggedTQuery::create()->deleteElement($pdDebateId, $pTagId);
                $logger->info('$deleted = ' . print_r($deleted, true));

                // Construction de la réponse
                $jsonResponse = array (
                    'success' => true,
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