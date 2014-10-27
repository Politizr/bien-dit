<?php

namespace Politizr\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use StudioEcho\Lib\StudioEchoUtils;

use Politizr\Exception\InconsistentDataException;

use Politizr\FrontBundle\Lib\SimpleImage;

use Politizr\Model\PDDebateQuery;
use Politizr\Model\PDocumentQuery;
use Politizr\Model\PTagQuery;
use Politizr\Model\PDDTaggedTQuery;
use Politizr\Model\PUTaggedTQuery;
use Politizr\Model\PUserQuery;

use Politizr\Model\PUser;
use Politizr\Model\PDDebate;

use Politizr\FrontBundle\Form\Type\PDDebateType;
use Politizr\FrontBundle\Form\Type\PUserPerso1Type;
use Politizr\FrontBundle\Form\Type\PUserPerso2Type;
use Politizr\FrontBundle\Form\Type\PUserPerso3Type;
use Politizr\FrontBundle\Form\Type\PUserPerso4Type;

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
        $user = $this->getUser();

        // Création d'un nouvel objet et redirection vers l'édition
        $debate = new PDDebate();
        
        $debate->setTitle('Un nouveau débat');
        
        $debate->setPUserId($user->getId());

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
        $user = $this->getUser();

        // *********************************** //
        //      Récupération objets vue
        // *********************************** //
        $document = PDocumentQuery::create()->findPk($id);
        if (!$document) {
            throw new InconsistentDataException('Document n°'.$id.' not found.');
        }
        if (!$document->isOwner($user->getId())) {
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
        return $this->render('PolitizrFrontBundle:CRUD:debateEdit.html.twig', array(
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
		        $user = $this->getUser();

		        // Récupération id objet édité
				$id = $request->get('debate')['id'];
		        $document = PDocumentQuery::create()->findPk($id);
		        if (!$document) {
		            throw new InconsistentDataException('Document n°'.$id.' not found.');
		        }
		        if (!$document->isOwner($user->getId())) {
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
		        $user = $this->getUser();

		        // Récupération id objet édité
				$id = $request->get('id');
		        $document = PDocumentQuery::create()->findPk($id);
		        if (!$document) {
		            throw new InconsistentDataException('Document n°'.$id.' not found.');
		        }
		        if (!$document->isOwner($user->getId())) {
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
		        $user = $this->getUser();

		        // Récupération id objet édité
				$id = $request->get('id');
		        $document = PDocumentQuery::create()->findPk($id);
		        if (!$document) {
		            throw new InconsistentDataException('Document n°'.$id.' not found.');
		        }
		        if ($document->getPublished()) {
		            throw new InconsistentDataException('Document n°'.$id.' is published and cannot be edited anymore.');
		        }
		        if (!$document->isOwner($user->getId())) {
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

    /**
     *      Upload d'une photo
     */
    public function debatePhotoUploadAction(Request $request) {
        $logger = $this->get('logger');
        $logger->info('*** debatePhotoUploadAction');
        
        try {
            // if ($request->isXmlHttpRequest()) {
                // Récupération débat courant
                $id = $request->get('id');
                $logger->info(print_r($id, true));
                $debate = PDDebateQuery::create()->findPk($id);

                // Chemin des images
                $path = $this->get('kernel')->getRootDir() . '/../web' . PDDebate::UPLOAD_WEB_PATH;

                // Taille max 5Mo
                $sizeLimit = 5 * 1024 * 1024;

                $myRequestedFile = $request->files->get('file-name');
                // $logger->info(print_r($myRequestedFile, true));

                if ($myRequestedFile == null) {
                    throw new \Exception('Request file null.');
                } else if ($myRequestedFile->getError() > 0) {
                    throw new \Exception('Erreur upload n°'.$myRequestedFile->getError(), 1);
                } else {
                    // Contrôle extension
                    $allowedExtensions = array('jpg', 'jpeg', 'png');
                    $ext = $myRequestedFile->guessExtension();
                    if ($allowedExtensions && !in_array(strtolower($ext), $allowedExtensions)) {
                        throw new \Exception('Type de fichier non autorisé.');
                    }

                    // Construction du nom du fichier
                    $destName = md5(uniqid()) . '.' . $ext;

                    //move the uploaded file to uploads folder;
                    // $move = move_uploaded_file($pathNameTmp, $path . $destName);
                    $movedFile = $myRequestedFile->move($path, $destName);
                    $logger->info('$movedFile = '.print_r($movedFile, true));
                }

                // Suppression photo déjà uploadée
                $filename = $debate->getFilename();
                if ($filename && $fileExists = file_exists($path . $filename)) {
                    unlink($path . $filename);
                }

                // TODO > ajout d'une contrainte sur une taille minimum
                // Resize de la photo 1024*1024px max
                $resized = false;                
                $image = new SimpleImage();
                $image->load($path . $destName);
                if ($width = $image->getWidth() > 1024) {
                    $image->resizeToWidth(1024);
                    $resized = true;
                }
                if ($height = $image->getHeight() > 1024) {
                    $image->resizeToHeight(1024);
                    $resized = true;
                }
                if ($resized) {
                    $image->save($path . $destName);
                }

                // MAJ du modèle
                $debate->setFilename($destName);
                $debate->save();

                // Construction de la réponse
                $jsonResponse = array (
                    'success' => true,
                    'filename' => $destName
                );
            // } else {
            //     throw $this->createNotFoundException('Not a XHR request');
            // }
        } catch (NotFoundHttpException $e) {
            $logger->info('Exception = ' . print_r($e->getMessage(), true));
            $jsonResponse = array('error' => $e->getMessage());
        } catch (FileException $e) {
            $logger->info('FileException = ' . print_r($e->getMessage(), true));
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
     *      Suppression d'une photo
     */
    public function debatePhotoDeleteAction(Request $request) {
        $logger = $this->get('logger');
        $logger->info('*** debatePhotoDeleteAction');
        
        try {
            // if ($request->isXmlHttpRequest()) {
                // Récupération débat courant
                $id = $request->get('id');
                $logger->info(print_r($id, true));
                $debate = PDDebateQuery::create()->findPk($id);

                // Chemin des images
                $path = $this->get('kernel')->getRootDir() . '/../web' . PDDebate::UPLOAD_WEB_PATH;

                // Suppression photo déjà uploadée
                $filename = $debate->getFilename();
                if ($filename && $fileExists = file_exists($path . $filename)) {
                    unlink($path . $filename);
                }

                // MAJ du modèle
                $debate->setFilename(null);
                $debate->save();

                // Construction de la réponse
                $jsonResponse = array (
                    'success' => true
                );
            // } else {
            //     throw $this->createNotFoundException('Not a XHR request');
            // }
        } catch (NotFoundHttpException $e) {
            $logger->info('Exception = ' . print_r($e->getMessage(), true));
            $jsonResponse = array('error' => $e->getMessage());
        } catch (FileException $e) {
            $logger->info('FileException = ' . print_r($e->getMessage(), true));
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
    /*                                                  GESTION USER                                            */
    /* ######################################################################################################## */

    /**
     *  Met à jour les informations personnelles du user
     *
     */
    public function userPersoUpdateAction(Request $request) {
        $logger = $this->get('logger');
        $logger->info('*** userPersoUpdateAction');
        
        try {
            if ($request->isXmlHttpRequest()) {
                // Récupération user courant
                $user = $this->getUser();

                $formTypeId = $request->get('pUser')['form_type_id'];
                $logger->info('$formTypeId = '.print_r($formTypeId, true));

                // Création du formulaire soumis
                if ($formTypeId == 1) {
                    $formPerso = $this->createForm(new PUserPerso1Type(), $user);
                } elseif($formTypeId == 2) {
                    $formPerso = $this->createForm(new PUserPerso2Type(), $user);
                } elseif($formTypeId == 3) {
                    $formPerso = $this->createForm(new PUserPerso3Type(), $user);
                } elseif($formTypeId == 4) {
                    $formPerso = $this->createForm(new PUserPerso4Type(), $user);
                }

                // *********************************** //
                //      Traitement du POST
                // *********************************** //
                $formPerso->bind($request);
                if ($formPerso->isValid()) {
                    $userPerso = $formPerso->getData();
                    $logger->info('userPerso = '.print_r($userPerso, true));
                    if ($formTypeId == 1) {
                    } elseif($formTypeId == 2) {
                        // MAJ email?
                        $email = $userPerso->getEmail();
                        $current = $user->getEmail();
                        if ($email && $email != $current) {
                            $user->setEmail($email);

                            // Canonicalization
                            $canonicalizeEmail = $this->get('fos_user.util.email_canonicalizer');
                            $user->setEmailCanonical($canonicalizeEmail->canonicalize($email));

                            $user->save();
                        }

                        // MAJ abonnement newsletter?
                        $newsletter = $userPerso->getNewsletter();
                        $current = $user->getNewsletter();
                        if ($newsletter != $current) {
                            $user->setNewsletter($newsletter);

                            $user->save();
                        }
                    } elseif($formTypeId == 3) {
                        // MAJ biography?
                        $biography = $userPerso->getBiography();
                        $current = $user->getBiography();
                        if ($biography != $current) {
                            $user->setBiography($biography);
        
                            $user->save();
                        }

                        // MAJ website?
                        $website = $userPerso->getWebsite();
                        $current = $user->getWebsite();
                        if ($website != $current) {
                            $user->setWebsite($website);

                            $user->save();
                        }

                        // MAJ twitter?
                        $twitter = $userPerso->getTwitter();
                        $current = $user->getTwitter();
                        if ($twitter != $current) {
                            $user->setTwitter($twitter);

                            $user->save();
                        }

                        // MAJ facebook?
                        $facebook = $userPerso->getFacebook();
                        $current = $user->getFacebook();
                        if ($facebook != $current) {
                            $user->setFacebook($facebook);

                            $user->save();
                        }

                        // MAJ phone?
                        $phone = $userPerso->getPhone();
                        $current = $user->getPhone();
                        if ($phone != $current) {
                            $user->setPhone($phone);

                            $user->save();
                        }
                    } elseif($formTypeId == 4) {
                        // MAJ username?
                        $username = $userPerso->getUsername();
                        $current = $user->getUsername();
                        if ($username && $username != $current) {
                            $user->setUsername($username);
                            
                            $user->save();
                        }

                        // Génération d'un nouveau mot de passe?
                        $password = $userPerso->getPassword();
                        $logger->info('password = '.print_r($password, true));
                        if ($password) {
                            // Encodage MDP
                            $encoderFactory = $this->get('security.encoder_factory');

                            $encoder = $encoderFactory->getEncoder($user);
                            $user->setPassword($encoder->encodePassword($password, $user->getSalt()));
                            // $user->eraseCredentials();

                            $user->save();

                            // Envoi email
                            $mailer = $this->get('mailer');
                            $templating = $this->get('templating');

                            $htmlBody = $templating->render(
                                                'PolitizrFrontBundle:Email:updatePassword.html.twig', array('username' => $user->getUsername(), 'password' => $password)
                                        );
                            $txtBody = $templating->render(
                                                'PolitizrFrontBundle:Email:updatePassword.txt.twig', array('username' => $user->getUsername(), 'password' => $password)
                                        );

                            $message = \Swift_Message::newInstance()
                                    ->setSubject('Politizr - MAJ de votre mot de passe')
                                    ->setFrom('admin@politizr.fr')
                                    ->setTo($user->getEmail())
                                    // ->setBcc(array('lionel.bouzonville@gmail.com'))
                                    ->setBody($htmlBody, 'text/html', 'utf-8')
                                    ->addPart($txtBody, 'text/plain', 'utf-8')
                            ;

                            $send = $mailer->send($message);
                            $logger->info('$send = '.print_r($send, true));
                            if (!$send) {
                                throw new \Exception('Erreur dans l\'envoi de l\'email');
                            }
                        }
                    }

                    // Construction de la réponse
                    $jsonResponse = array (
                        'success' => true
                    );
                } else {
                    $errors = StudioEchoUtils::getErrorMessages($formPerso);
                    $jsonResponse = array(
                        'error' => $errors
                        );
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
     *      Upload d'une photo
     */
    public function userPhotoUploadAction(Request $request) {
        $logger = $this->get('logger');
        $logger->info('*** userPhotoUploadAction');
        
        try {
            // if ($request->isXmlHttpRequest()) {
                // Récupération user courant
                $user = $this->getUser();

                // Chemin des images
                $path = $this->get('kernel')->getRootDir() . '/../web' . PUser::UPLOAD_WEB_PATH;

                // Taille max 5Mo
                $sizeLimit = 5 * 1024 * 1024;

                $myRequestedFile = $request->files->get('file-name');
                // $logger->info(print_r($myRequestedFile, true));

                if ($myRequestedFile == null) {
                    throw new \Exception('Request file null.');
                } else if ($myRequestedFile->getError() > 0) {
                    throw new \Exception('Erreur upload n°'.$myRequestedFile->getError(), 1);
                } else {
                    // Contrôle extension
                    $allowedExtensions = array('jpg', 'jpeg', 'png');
                    $ext = $myRequestedFile->guessExtension();
                    if ($allowedExtensions && !in_array(strtolower($ext), $allowedExtensions)) {
                        throw new \Exception('Type de fichier non autorisé.');
                    }

                    // Construction du nom du fichier
                    $destName = md5(uniqid()) . '.' . $ext;

                    //move the uploaded file to uploads folder;
                    // $move = move_uploaded_file($pathNameTmp, $path . $destName);
                    $movedFile = $myRequestedFile->move($path, $destName);
                    $logger->info('$movedFile = '.print_r($movedFile, true));
                }

                // Suppression photo déjà uploadée
                $filename = $user->getFilename();
                if ($filename && $fileExists = file_exists($path . $filename)) {
                    unlink($path . $filename);
                }

                // Resize de la photo 640*640px max
                $resized = false;                
                $image = new SimpleImage();
                $image->load($path . $destName);
                if ($width = $image->getWidth() > 150) {
                    $image->resizeToWidth(150);
                    $resized = true;
                }
                if ($height = $image->getHeight() > 150) {
                    $image->resizeToHeight(150);
                    $resized = true;
                }
                if ($resized) {
                    $image->save($path . $destName);
                }

                // MAJ du modèle
                $user->setFilename($destName);
                $user->save();

                    // Construction de la réponse
                    $jsonResponse = array (
                        'success' => true,
                        'filename' => $destName
                    );
            // } else {
            //     throw $this->createNotFoundException('Not a XHR request');
            // }
        } catch (NotFoundHttpException $e) {
            $logger->info('Exception = ' . print_r($e->getMessage(), true));
            $jsonResponse = array('error' => $e->getMessage());
        } catch (FileException $e) {
            $logger->info('FileException = ' . print_r($e->getMessage(), true));
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
     *      Suppression d'une photo
     */
    public function userPhotoDeleteAction(Request $request) {
        $logger = $this->get('logger');
        $logger->info('*** userPhotoDeleteAction');
        
        try {
            // if ($request->isXmlHttpRequest()) {
                // Récupération user courant
                $user = $this->getUser();

                // Chemin des images
                $path = $this->get('kernel')->getRootDir() . '/../web' . PUser::UPLOAD_WEB_PATH;

                // Suppression photo déjà uploadée
                $filename = $user->getFilename();
                if ($filename && $fileExists = file_exists($path . $filename)) {
                    unlink($path . $filename);
                }

                // MAJ du modèle
                $user->setFilename(null);
                $user->save();

                // Construction de la réponse
                $jsonResponse = array (
                    'success' => true
                );
            // } else {
            //     throw $this->createNotFoundException('Not a XHR request');
            // }
        } catch (NotFoundHttpException $e) {
            $logger->info('Exception = ' . print_r($e->getMessage(), true));
            $jsonResponse = array('error' => $e->getMessage());
        } catch (FileException $e) {
            $logger->info('FileException = ' . print_r($e->getMessage(), true));
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
     *  Gestion des différents cas de figure suite à l'ajout d'un tag: tag sélectionné depuis la liste, tag existant mais non sélectionné, tag non existant.
     *
     *  @param $tagId       integer
     *  @param $tagtTitle   string
     *  @param $tagTypeId   integer
     *  @param $newTag      boolean     création de tag autorisée ou pas
     *
     *  @return integer     id du tag sélectionné / retrouvé / créé
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