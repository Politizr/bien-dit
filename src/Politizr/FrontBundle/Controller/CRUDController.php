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
        if ($document->getPublished()) {
            throw new InconsistentDataException('Document n°'.$id.' is published and cannot be edited anymore.');
        }
        if (!$document->isOwner($pUser->getId())) {
            return $this->redirect('MyDraftsC');
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

    // /**
    //  * 	Mise à jour d'un débat
    //  *  DEPRECATED > post via ajax / medium-editor
    //  *
    //  */
    // public function debateUpdateAction($id)
    // {
    //     $logger = $this->get('logger');
    //     $logger->info('*** debateEditAction');
    //     $logger->info('$id = '.print_r($id, true));
// 
    //     // Récupération user courant
    //     $pUser = $this->getUser();
// 
    //     // *********************************** //
    //     //      Récupération objets vue
    //     // *********************************** //
    //     $document = PDocumentQuery::create()->findPk($id);
    //     if (!$document) {
    //         throw new InconsistentDataException('Document n°'.$id.' not found.');
    //     }
    //     if ($document->getPublished()) {
    //         throw new InconsistentDataException('Document n°'.$id.' is published and cannot be edited anymore.');
    //     }
    //     if (!$document->isOwner($pUser->getId())) {
    //         return $this->redirect('MyDraftsC');
    //     }
// 
    //     $debate = PDDebateQuery::create()->findPk($id);
    //     $debateFormType = new PDDebateType();
    //     $form = $this->createForm($debateFormType, $debate);
// 
    //     $form->bind($this->get('request'));
    //     if ($form->isValid()) {
    //         try {
    //         	// Gestion file upload
	// 	        $file = $form['uploadedFileName']->getData();
	// 	        if ($file) {
	// 	          $debate->removeUpload(true);
	// 	          $fileName = $debate->upload($file);
	// 	          $debate->setFileName($fileName);
	// 	        }
// 
	// 	        // Sauvegarde
    //             $debate->save();
// 
    //             // Redirection
    //             $this->get('session')->getFlashBag()->add('success', 'Objet sauvegardé avec succès.');
    //             return $this->redirect($this->generateUrl('MyDraftsEditC', array('id' => $debate->getId())));
    //         } catch (\Exception $e) {
    //             throw $e;
    //         }
    //     } else {
    //         $this->get('session')->getFlashBag()->add('error',  'Formulaire non valide');
    //     }
// 
    //     return $this->render('PolitizrFrontBundle:Profile\CRUD:debateEdit.html.twig', array(
    //         'debate' => $debate,
    //         'form' => $form->createView(),
    //         ));
    // }

    /* ######################################################################################################## */
    /*                                                  FONCTIONS AJAX                                          */
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
		        if ($document->getPublished()) {
		            throw new InconsistentDataException('Document n°'.$id.' is published and cannot be edited anymore.');
		        }
		        if (!$document->isOwner($pUser->getId())) {
		            throw new InconsistentDataException('Document n°'.$id.' is not yours.');
		        }

		        $debate = PDDebateQuery::create()->findPk($id);
		        $form = $this->createForm(new PDDebateType(), $debate);

                $form->bind($request);
                if ($form->isValid()) {
                    $debate = $form->getData();

                    $logger->info('*** debateUpdateAction');

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
		        if ($document->getPublished()) {
		            throw new InconsistentDataException('Document n°'.$id.' is published and cannot be edited anymore.');
		        }
		        if (!$document->isOwner($pUser->getId())) {
		            throw new InconsistentDataException('Document n°'.$id.' is not yours.');
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


}