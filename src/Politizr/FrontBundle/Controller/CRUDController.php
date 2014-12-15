<?php

namespace Politizr\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Symfony\Component\EventDispatcher\GenericEvent;

use StudioEcho\Lib\StudioEchoUtils;

use Politizr\Exception\InconsistentDataException;

use Politizr\FrontBundle\Lib\SimpleImage;

use Politizr\Model\PDDebateQuery;
use Politizr\Model\PDReactionQuery;
use Politizr\Model\PDocumentQuery;
use Politizr\Model\PDCommentQuery;
use Politizr\Model\PTagQuery;
use Politizr\Model\PDDTaggedTQuery;
use Politizr\Model\PUTaggedTQuery;
use Politizr\Model\PUFollowTQuery;
use Politizr\Model\PUserQuery;

use Politizr\Model\PUser;
use Politizr\Model\PDDebate;
use Politizr\Model\PDReaction;
use Politizr\Model\PDComment;
use Politizr\Model\PRAction;

use Politizr\FrontBundle\Form\Type\PDDebateType;
use Politizr\FrontBundle\Form\Type\PDReactionType;
use Politizr\FrontBundle\Form\Type\PUserIdentityType;
use Politizr\FrontBundle\Form\Type\PUserEmailType;
use Politizr\FrontBundle\Form\Type\PUserBiographyType;
use Politizr\FrontBundle\Form\Type\PUserConnectionType;
use Politizr\FrontBundle\Form\Type\PDCommentType;

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

        return $this->redirect($this->generateUrl('DebateDraftEdit', array('id' => $debate->getId())));
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
    /*                                                   REACTION                                               */
    /* ######################################################################################################## */

    /**
     *  Création d'une nouvelle réaction
     */
    public function reactionNewAction($debateId, $parentId)
    {
        $logger = $this->get('logger');
        $logger->info('*** reactionNewAction');

        // Récupération user courant
        $user = $this->getUser();

        // Récupération du débat sur lequel la réaction a lieu
        $debate = PDDebateQuery::create()->findPk($debateId);
        if (!$debate) {
            throw new InconsistentDataException('Debate n°'.$debateId.' not found.');
        }

        // Récupération de la réaction parente sur laquelle la réaction a lieu
        $parent = null;
        if ($parentId) {
            $parent = PDReactionQuery::create()->findPk($parentId);
            if (!$parent) {
                throw new InconsistentDataException('Parent reaction n°'.$parentId.' not found.');
            }
        }

        // Création d'un nouvel objet et redirection vers l'édition
        $reaction = new PDReaction();

        $reaction->setPDDebateId($debate->getId());
        
        $reaction->setTitle('Une nouvelle réaction');
        
        $reaction->setPUserId($user->getId());

        $reaction->setNotePos(0);
        $reaction->setNoteNeg(0);
        
        $reaction->setOnline(true);
        $reaction->setPublished(false);

        // Gestion nested set
        if ($parent) {
            $reaction->insertAsLastChildOf($parent);
        } else {
            $rootNode = PDReactionQuery::create()->findOrCreateRoot($debate->getId());
            if ($nbReactions = $debate->countReactions() == 0) {
                $reaction->insertAsFirstChildOf($rootNode); // pas de niveau 0
            } else {
                $reaction->insertAsNextSiblingOf($debate->getLastReaction(1));
            }
        }
        
        $reaction->save();

        return $this->redirect($this->generateUrl('ReactionDraftEdit', array('id' => $reaction->getId())));
    }

    /**
     *  Edition d'une réaction
     */
    public function reactionEditAction($id)
    {
        $logger = $this->get('logger');
        $logger->info('*** reactionEditAction');
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

        $reaction = PDReactionQuery::create()->findPk($id);
        $form = $this->createForm(new PDReactionType(), $reaction);

        // *********************************** //
        //      Affichage de la vue
        // *********************************** //
        return $this->render('PolitizrFrontBundle:CRUD:reactionEdit.html.twig', array(
            'reaction' => $reaction,
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
     *  Enregistre le débat
     */
    public function debateUpdateAction(Request $request) {
        $logger = $this->get('logger');
        $logger->info('*** debateUpdateAction');

        $jsonResponse = $this->get('politizr.routing.ajax')->createJsonResponse(
            'politizr.service.document',
            'debateUpdate'
        );

        return $jsonResponse;
    }

    /**
     *  Publication du débat
     */
    public function debatePublishAction(Request $request) {
        $logger = $this->get('logger');
        $logger->info('*** debatePublishAction');

        $jsonResponse = $this->get('politizr.routing.ajax')->createJsonRedirectResponse(
            'politizr.service.document',
            'debatePublish'
        );

        return $jsonResponse;
    }

    /**
     *  Suppression du débat
     */
    public function debateDeleteAction(Request $request) {
        $logger = $this->get('logger');
        $logger->info('*** debateDeleteAction');

        $jsonResponse = $this->get('politizr.routing.ajax')->createJsonRedirectResponse(
            'politizr.service.document',
            'debateDelete'
        );

        return $jsonResponse;
    }

    /**
     *  Upload d'une photo
     */
    public function debatePhotoUploadAction(Request $request) {
        $logger = $this->get('logger');
        $logger->info('*** debatePhotoUploadAction');

        $jsonResponse = $this->get('politizr.routing.ajax')->createJsonHtmlResponse(
            'politizr.service.document',
            'debatePhotoUpload'
        );

        return $jsonResponse;
    }

    /**
     *  Suppression d'une photo
     */
    public function debatePhotoDeleteAction(Request $request) {
        $logger = $this->get('logger');
        $logger->info('*** debatePhotoDeleteAction');

        $jsonResponse = $this->get('politizr.routing.ajax')->createJsonResponse(
            'politizr.service.document',
            'debatePhotoDelete'
        );

        return $jsonResponse;
    }

    /* ######################################################################################################## */
    /*                                               GESTION RÉACTION                                           */
    /* ######################################################################################################## */

    /**
     *  Enregistre le débat
     */
    public function reactionUpdateAction(Request $request) {
        $logger = $this->get('logger');
        $logger->info('*** reactionUpdateAction');

        $jsonResponse = $this->get('politizr.routing.ajax')->createJsonResponse(
            'politizr.service.document',
            'reactionUpdate'
        );

        return $jsonResponse;
    }

    /**
     *  Publication de la réaction
     */
    public function reactionPublishAction(Request $request) {
        $logger = $this->get('logger');
        $logger->info('*** reactionPublishAction');

        $jsonResponse = $this->get('politizr.routing.ajax')->createJsonRedirectResponse(
            'politizr.service.document',
            'reactionPublish'
        );

        return $jsonResponse;
    }


    /**
     *  Suppression de la réaction
     */
    public function reactionDeleteAction(Request $request) {
        $logger = $this->get('logger');
        $logger->info('*** reactionDeleteAction');

        $jsonResponse = $this->get('politizr.routing.ajax')->createJsonRedirectResponse(
            'politizr.service.document',
            'reactionDelete'
        );

        return $jsonResponse;
    }

    /* ######################################################################################################## */
    /*                                               GESTION COMMENTAIRE                                           */
    /* ######################################################################################################## */

    /**
     *  Enregistre un nouveau commentaire
     */
    public function commentNewAction(Request $request) {
        $logger = $this->get('logger');
        $logger->info('*** commentNewAction');

        $jsonResponse = $this->get('politizr.routing.ajax')->createJsonHtmlResponse(
            'politizr.service.document',
            'commentNew'
        );

        return $jsonResponse;
    }


    /* ######################################################################################################## */
    /*                                                  GESTION TAGS                                            */
    /* ######################################################################################################## */

    /**
     *  Association d'un tag à un débat
     */
    public function debateAddTagAction(Request $request) {
        $logger = $this->get('logger');
        $logger->info('*** debateAddTagAction');

        $jsonResponse = $this->get('politizr.routing.ajax')->createJsonHtmlResponse(
            'politizr.service.tag',
            'debateAddTag'
        );

        return $jsonResponse;
    }

    /**
     *  Suppression de l'association d'un tag à un débat
     */
    public function debateDeleteTagAction(Request $request) {
        $logger = $this->get('logger');
        $logger->info('*** debateDeleteTagAction');

        $jsonResponse = $this->get('politizr.routing.ajax')->createJsonResponse(
            'politizr.service.tag',
            'debateDeleteTag'
        );

        return $jsonResponse;
    }

    /**
     *  Association d'un tag suivi d'un user
     */
    public function userFollowAddTagAction(Request $request) {
        $logger = $this->get('logger');
        $logger->info('*** userFollowAddTagAction');

        $jsonResponse = $this->get('politizr.routing.ajax')->createJsonHtmlResponse(
            'politizr.service.tag',
            'userFollowAddTag'
        );

        return $jsonResponse;
    }

    /**
     *  Suppression de l'association d'un tag suivi d'un user
     */
    public function userFollowDeleteTagAction(Request $request) {
        $logger = $this->get('logger');
        $logger->info('*** userFollowDeleteTagAction');

        $jsonResponse = $this->get('politizr.routing.ajax')->createJsonResponse(
            'politizr.service.tag',
            'userFollowDeleteTag'
        );

        return $jsonResponse;
    }

    /**
     *  Association d'un tag caractérisant un user
     */
    public function userTaggedAddTagAction(Request $request) {
        $logger = $this->get('logger');
        $logger->info('*** userTaggedAddTagAction');

        $jsonResponse = $this->get('politizr.routing.ajax')->createJsonHtmlResponse(
            'politizr.service.tag',
            'userTaggedAddTag'
        );

        return $jsonResponse;
    }

    /**
     *  Suppression de l'association d'un tag caractérisant un user
     */
    public function userTaggedDeleteTagAction(Request $request) {
        $logger = $this->get('logger');
        $logger->info('*** userTaggedDeleteTagAction');

        $jsonResponse = $this->get('politizr.routing.ajax')->createJsonResponse(
            'politizr.service.tag',
            'userTaggedDeleteTag'
        );

        return $jsonResponse;
    }

    /* ######################################################################################################## */
    /*                                                  GESTION USER                                            */
    /* ######################################################################################################## */

    /**
     *  Mise à jour des informations personnelles du user
     *  TODO > + gestion affinités politiques
     */
    public function userPersoUpdateAction(Request $request) {
        $logger = $this->get('logger');
        $logger->info('*** userPersoUpdateAction');

        $jsonResponse = $this->get('politizr.routing.ajax')->createJsonResponse(
            'politizr.service.user',
            'userPersoUpdate'
        );

        return $jsonResponse;
    }

    /**
     *  Upload de la photo de profil du user
     */
    public function userPhotoUploadAction(Request $request) {
        $logger = $this->get('logger');
        $logger->info('*** userPhotoUploadAction');

        $jsonResponse = $this->get('politizr.routing.ajax')->createJsonHtmlResponse(
            'politizr.service.user',
            'userPhotoUpload'
        );

        return $jsonResponse;
    }

    /**
     *  Suppression de la photo de profil du user
     */
    public function userPhotoDeleteAction(Request $request) {
        $logger = $this->get('logger');
        $logger->info('*** userPhotoDeleteAction');

        $jsonResponse = $this->get('politizr.routing.ajax')->createJsonResponse(
            'politizr.service.user',
            'userPhotoDelete'
        );

        return $jsonResponse;
    }

}