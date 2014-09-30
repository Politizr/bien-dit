<?php

namespace Politizr\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Politizr\Exception\InconsistentDataException;

use Politizr\Model\PDDebateQuery;
use Politizr\Model\PDocumentQuery;
use Politizr\Model\PTagQuery;
use Politizr\Model\PDDTaggedTQuery;
use Politizr\Model\PUTaggedTQuery;
use Politizr\Model\PUFollowTQuery;

use Politizr\Model\PDDebate;

use Politizr\FrontBundle\Form\Type\PDDebateType;

/**
 * Gestion des CRUD lié aux objets Politizr: débat, réaction, ...
 *
 * @author Lionel Bouzonville
 */
class CRUDController extends Controller {

    /* ######################################################################################################## */
    /*                                                 ROUTING CLASSIQUE                                        */
    /* ######################################################################################################## */

    /* ######################################################################################################## */
    /*                                                      DEBAT                                               */
    /* ######################################################################################################## */

    /**
     *  Création d'un nouveau débat
     */
    public function debateNewAction()
    {
        $logger = $this->get('logger');
        $logger->info('*** debateNewAction');

        // Récupération user courant
        $pUser = $this->getUser();

        // Création d'un nouvel objet et redirection vers l'édition
        $debate = new PDDebate();
        
        $debate->setTitle('Un nouveau débat');
        
        $debate->setPUserId($pUser->getId());

        $debate->setNotePos(0);
        $debate->setNoteNeg(0);
        
        $debate->setOnline(true);
        $debate->setPublished(false);
        
        $debate->save();

        return $this->redirect($this->generateUrl('MyDraftsEditC', array('id' => $debate->getId())));
    }

    /**
     *  Edition d'un débat
     */
    public function debateEditAction($id)
    {
        $logger = $this->get('logger');
        $logger->info('*** debateEditAction');
        $logger->info('$id = '.print_r($id, true));

        // Récupération user courant
        $pUser = $this->getUser();

        // *********************************** //
        //      Récupération objets vue
        // *********************************** //
        $document = PDocumentQuery::create()->findPk($id);
        if (!$document) {
            throw new InconsistentDataException('Document n°'.$id.' not found.');
        }
        if (!$document->isOwner($pUser->getId())) {
            throw new InconsistentDataException('Document n°'.$id.' is not yours.');
        }
        if ($document->getPublished()) {
            throw new InconsistentDataException('Document n°'.$id.' is published and cannot be edited anymore.');
        }

        $debate = PDDebateQuery::create()->findPk($id);
        $form = $this->createForm(new PDDebateType(), $debate);

        // *********************************** //
        //      Affichage de la vue
        // *********************************** //
        return $this->render('PolitizrFrontBundle:Profile\CRUD:debateEdit.html.twig', array(
            'debate' => $debate,
            'form' => $form->createView(),
            ));
    }

    /* ######################################################################################################## */
    /*                                                  FONCTIONS AJAX                                          */
    /* ######################################################################################################## */

    /* ######################################################################################################## */
    /*                                                  GESTION DEBAT                                           */
    /* ######################################################################################################## */

    /**
     *	Enregistre le débat
     *
     */
    public function debateUpdateAction(Request $request) {
        $logger = $this->get('logger');
        $logger->info('*** debateUpdateAction');
        
        try {
            if ($request->isXmlHttpRequest()) {
		        // Récupération user courant
		        $pUser = $this->getUser();

		        // Récupération id objet édité
				$id = $request->get('debate')['id'];
		        $document = PDocumentQuery::create()->findPk($id);
		        if (!$document) {
		            throw new InconsistentDataException('Document n°'.$id.' not found.');
		        }
		        if (!$document->isOwner($pUser->getId())) {
		            throw new InconsistentDataException('Document n°'.$id.' is not yours.');
		        }
		        if ($document->getPublished()) {
		            throw new InconsistentDataException('Document n°'.$id.' is published and cannot be edited anymore.');
		        }

		        $debate = PDDebateQuery::create()->findPk($id);
		        $form = $this->createForm(new PDDebateType(), $debate);

                $form->bind($request);
                if ($form->isValid()) {
                    $debate = $form->getData();
					$debate->save();

	                // Construction de la réponse
	                $jsonResponse = array (
	                    'success' => true,
	                );
                } else {
                	// TODO > affichage des erreurs form à virer
				    $errors = array();

			        foreach ($form->getErrors() as $key => $error) {
			            $errors[$key] = $error->getMessage();
			        }

                    throw new \Exception('Form not valid: '.print_r($errors, true));
                }
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
     *	Publication du débat
     *
     */
    public function debatePublishAction(Request $request) {
        $logger = $this->get('logger');
        $logger->info('*** debatePublishAction');
        
        try {
            if ($request->isXmlHttpRequest()) {
		        // Récupération user courant
		        $pUser = $this->getUser();

		        // Récupération id objet édité
				$id = $request->get('id');
		        $document = PDocumentQuery::create()->findPk($id);
		        if (!$document) {
		            throw new InconsistentDataException('Document n°'.$id.' not found.');
		        }
		        if (!$document->isOwner($pUser->getId())) {
		            throw new InconsistentDataException('Document n°'.$id.' is not yours.');
		        }
		        if ($document->getPublished()) {
		            throw new InconsistentDataException('Document n°'.$id.' is published and cannot be edited anymore.');
		        }

		        // MAJ de l'objet
		        $debate = PDDebateQuery::create()->findPk($id);
		        $debate->setPublished(true);
		        $debate->setPublishedAt(time());
                $debate->save();

                $this->get('session')->getFlashBag()->add('success', 'Objet publié avec succès.');

	            // Construction de la réponse
	            $jsonResponse = array (
	                'success' => true,
	                'redirectUrl' => $this->generateUrl('MyDebatesC'),
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
     *	Suppression du débat
     *
     */
    public function debateDeleteAction(Request $request) {
        $logger = $this->get('logger');
        $logger->info('*** debateDeleteAction');
        
        try {
            if ($request->isXmlHttpRequest()) {
		        // Récupération user courant
		        $pUser = $this->getUser();

		        // Récupération id objet édité
				$id = $request->get('id');
		        $document = PDocumentQuery::create()->findPk($id);
		        if (!$document) {
		            throw new InconsistentDataException('Document n°'.$id.' not found.');
		        }
		        if ($document->getPublished()) {
		            throw new InconsistentDataException('Document n°'.$id.' is published and cannot be edited anymore.');
		        }
		        if (!$document->isOwner($pUser->getId())) {
		            throw new InconsistentDataException('Document n°'.$id.' is not yours.');
		        }

		        // MAJ de l'objet
		        $debate = PDDebateQuery::create()->findPk($id);
                $debate->delete(); // TODO > on garde une archive même sur les brouillons? http://propelorm.org/documentation/behaviors/archivable.html

                $this->get('session')->getFlashBag()->add('success', 'Objet supprimé avec succès.');

	            // Construction de la réponse
	            $jsonResponse = array (
	                'success' => true,
	                'redirectUrl' => $this->generateUrl('MyDebatesC'),
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

    /* ######################################################################################################## */
    /*                                                  GESTION TAGS                                            */
    /* ######################################################################################################## */


    /**
     *  Ajoute un tag à un débat.
     *
     */
    public function debateAddTagAction(Request $request) {
        $logger = $this->get('logger');
        $logger->info('*** debateAddTagAction');
        
        try {
            if ($request->isXmlHttpRequest()) {
                // Récupération args
                $tagTitle = $request->get('tagTitle');
                $tagId = $request->get('tagId');
                $tagTypeId = $request->get('tagTypeId');
                $objectId = $request->get('objectId');
                $newTag = $request->get('newTag');

                // Gestion tag non existant
                $tagId = $this->retrieveOrCreateTag($tagId, $tagTitle, $tagTypeId, $newTag);

                // Association du tag au debat
                $created = PDDTaggedTQuery::create()->addElement($objectId, $tagId);

                if (!$created) {
                    $htmlTag = null;
                } else  {
                    // Construction du rendu du tag
                    $tag = PTagQuery::create()->findPk($tagId);
                    $templating = $this->get('templating');
                    $htmlTag = $templating->render(
                                        'PolitizrFrontBundle:Fragment:Tag.html.twig', array(
                                            'objectId' => $objectId,
                                            'tag' => $tag,
                                            'deleteUrl' => $this->container->get('router')->generate('DebateDeleteTag')
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
     *  Supprime un tag associé au débat courant et renvoit le rendu associé
     */
    public function debateDeleteTagAction(Request $request) {
        $logger = $this->get('logger');
        $logger->info('*** debateDeleteTagAction');
        
        try {
            if ($request->isXmlHttpRequest()) {
                // Récupération args
                $tagId = $request->get('tagId');
                $objectId = $request->get('objectId');

                // Suppression du tag / profil
                $deleted = PDDTaggedTQuery::create()->deleteElement($objectId, $tagId);

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
     *	Ajoute un tag au taggage d'un utilisateur.
     *
     */
    public function userFollowAddTagAction(Request $request) {
        $logger = $this->get('logger');
        $logger->info('*** userFollowAddTagAction');
        
        try {
            if ($request->isXmlHttpRequest()) {
                // Récupération args
                $tagTitle = $request->get('tagTitle');
                $tagId = $request->get('tagId');
                $tagTypeId = $request->get('tagTypeId');
                $objectId = $request->get('objectId');
                $newTag = $request->get('newTag');

                // Gestion tag non existant
                $tagId = $this->retrieveOrCreateTag($tagId, $tagTitle, $tagTypeId, $newTag);

                // Association du tag au user
                $created = PUFollowTQuery::create()->addElement($objectId, $tagId);

                if (!$created) {
                    $htmlTag = null;
                } else  {
                    // Construction du rendu du tag
                    $tag = PTagQuery::create()->findPk($tagId);
                    $templating = $this->get('templating');
                    $htmlTag = $templating->render(
                                        'PolitizrFrontBundle:Fragment:Tag.html.twig', array(
                                            'objectId' => $objectId,
                                            'tag' => $tag,
                                            'deleteUrl' => $this->container->get('router')->generate('UserFollowDeleteTag')
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
    public function userFollowDeleteTagAction(Request $request) {
        $logger = $this->get('logger');
        $logger->info('*** userFollowDeleteTagAction');
        
        try {
            if ($request->isXmlHttpRequest()) {
                // Récupération args
                $tagId = $request->get('tagId');
                $objectId = $request->get('objectId');

                // Suppression du tag / profil
                $deleted = PUTaggedTQuery::create()->deleteElement($objectId, $tagId);

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
     *	Ajoute un tag au taggage d'un utilisateur.
     *
     */
    public function userTaggedAddTagAction(Request $request) {
        $logger = $this->get('logger');
        $logger->info('*** userTaggedAddTagAction');
        
        try {
            if ($request->isXmlHttpRequest()) {
                // Récupération args
                $tagTitle = $request->get('tagTitle');
                $tagId = $request->get('tagId');
                $tagTypeId = $request->get('tagTypeId');
                $objectId = $request->get('objectId');
                $newTag = $request->get('newTag');

                // Gestion tag non existant
                $tagId = $this->retrieveOrCreateTag($tagId, $tagTitle, $tagTypeId, $newTag);

                // Association du tag au user
                $created = PUTaggedTQuery::create()->addElement($objectId, $tagId);

                if (!$created) {
                    $htmlTag = null;
                } else  {
                    // Construction du rendu du tag
                    $tag = PTagQuery::create()->findPk($tagId);
                    $templating = $this->get('templating');
                    $htmlTag = $templating->render(
                                        'PolitizrFrontBundle:Fragment:Tag.html.twig', array(
                                            'objectId' => $objectId,
                                            'tag' => $tag,
                                            'deleteUrl' => $this->container->get('router')->generate('UserFollowDeleteTag')
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
    public function userTaggedDeleteTagAction(Request $request) {
        $logger = $this->get('logger');
        $logger->info('*** userTaggedDeleteTagAction');
        
        try {
            if ($request->isXmlHttpRequest()) {
                // Récupération args
                $tagId = $request->get('tagId');
                $objectId = $request->get('objectId');

                // Suppression du tag / profil
                $deleted = PUTaggedTQuery::create()->deleteElement($objectId, $tagId);

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

    /* ######################################################################################################## */
    /*                                               FONCTIONS PRIVÉES                                          */
    /* ######################################################################################################## */

    /**
     * 	Gestion des différents cas de figure suite à l'ajout d'un tag: tag sélectionné depuis la liste, tag existant mais non sélectionné, tag non existant.
     *
     * 	@param $tagId 		integer
	 *	@param $tagtTitle	string
	 *	@param $tagTypeId 	integer
	 * 	@param $newTag 		boolean 	création de tag autorisée ou pas
	 *
	 * 	@return integer 	id du tag sélectionné / retrouvé / créé
     */
    private function retrieveOrCreateTag($tagId, $tagTitle, $tagTypeId, $newTag = false) {
        if (!$tagId) {
        	// Récupération via slug
        	$slug = \StudioEcho\Lib\StudioEchoUtils::generateSlug($tagTitle);
        	$tag = PTagQuery::create()->filterByPTTagTypeId($tagTypeId)->filterBySlug($slug)->findOne();

        	if ($tag) {
        		$tagId = $tag->getId();
            } elseif($newTag) {
            	$tagId = PTagQuery::create()->addTag($tagTitle, $tagTypeId, true);
            	
            } else {
            	throw new \Exception('Création de nouveaux tags non autorisés, merci d\'en choisir un depuis la liste contextuelle proposée.');
            }
        }

        return $tagId;
    }

}