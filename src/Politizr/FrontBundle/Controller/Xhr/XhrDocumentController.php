<?php

namespace Politizr\FrontBundle\Controller\Xhr;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;

/**
 *  Gestion des documents / appels XHR
 *
 *  @author Lionel Bouzonville
 */
class XhrDocumentController extends Controller
{
    /* ######################################################################################################## */
    /*                                             GESTION DEBAT + REACTION                                     */
    /* ######################################################################################################## */

    /**
     *  Upload d'une photo
     */
    public function documentPhotoUploadAction(Request $request)
    {
        $logger = $this->get('logger');
        $logger->info('*** documentPhotoUploadAction');

        $jsonResponse = $this->get('politizr.routing.xhr')->createJsonHtmlResponse(
            'politizr.xhr.document',
            'documentPhotoUpload'
        );

        return $jsonResponse;
    }

    /**
     *  Suppression d'une photo
     */
    public function documentPhotoDeleteAction(Request $request)
    {
        $logger = $this->get('logger');
        $logger->info('*** documentPhotoDeleteAction');

        $jsonResponse = $this->get('politizr.routing.xhr')->createJsonHtmlResponse(
            'politizr.xhr.document',
            'documentPhotoDelete'
        );

        return $jsonResponse;
    }

    /* ######################################################################################################## */
    /*                                                  GESTION DEBAT                                           */
    /* ######################################################################################################## */

    /**
     *  Enregistre le débat
     */
    public function debateUpdateAction(Request $request)
    {
        $logger = $this->get('logger');
        $logger->info('*** debateUpdateAction');

        $jsonResponse = $this->get('politizr.routing.xhr')->createJsonResponse(
            'politizr.xhr.document',
            'debateUpdate'
        );

        return $jsonResponse;
    }

    /**
     *  Update debate photo info
     */
    public function debatePhotoInfoUpdateAction(Request $request)
    {
        $logger = $this->get('logger');
        $logger->info('*** debatePhotoInfoUpdateAction');

        $jsonResponse = $this->get('politizr.routing.xhr')->createJsonHtmlResponse(
            'politizr.xhr.document',
            'debatePhotoInfoUpdate'
        );

        return $jsonResponse;
    }

    /**
     *  Publication du débat
     */
    public function debatePublishAction(Request $request)
    {
        $logger = $this->get('logger');
        $logger->info('*** debatePublishAction');

        $jsonResponse = $this->get('politizr.routing.xhr')->createJsonRedirectResponse(
            'politizr.xhr.document',
            'debatePublish'
        );

        return $jsonResponse;
    }

    /**
     *  Suppression du débat
     */
    public function debateDeleteAction(Request $request)
    {
        $logger = $this->get('logger');
        $logger->info('*** debateDeleteAction');

        $jsonResponse = $this->get('politizr.routing.xhr')->createJsonRedirectResponse(
            'politizr.xhr.document',
            'debateDelete'
        );

        return $jsonResponse;
    }

    /* ######################################################################################################## */
    /*                                               GESTION RÉACTION                                           */
    /* ######################################################################################################## */

    /**
     *  Enregistre le débat
     */
    public function reactionUpdateAction(Request $request)
    {
        $logger = $this->get('logger');
        $logger->info('*** reactionUpdateAction');

        $jsonResponse = $this->get('politizr.routing.xhr')->createJsonResponse(
            'politizr.xhr.document',
            'reactionUpdate'
        );

        return $jsonResponse;
    }

    /**
     *  Update reaction photo info
     */
    public function reactionPhotoInfoUpdateAction(Request $request)
    {
        $logger = $this->get('logger');
        $logger->info('*** reactionPhotoInfoUpdateAction');

        $jsonResponse = $this->get('politizr.routing.xhr')->createJsonHtmlResponse(
            'politizr.xhr.document',
            'reactionPhotoInfoUpdate'
        );

        return $jsonResponse;
    }

    /**
     *  Publication de la réaction
     */
    public function reactionPublishAction(Request $request)
    {
        $logger = $this->get('logger');
        $logger->info('*** reactionPublishAction');

        $jsonResponse = $this->get('politizr.routing.xhr')->createJsonRedirectResponse(
            'politizr.xhr.document',
            'reactionPublish'
        );

        return $jsonResponse;
    }


    /**
     *  Suppression de la réaction
     */
    public function reactionDeleteAction(Request $request)
    {
        $logger = $this->get('logger');
        $logger->info('*** reactionDeleteAction');

        $jsonResponse = $this->get('politizr.routing.xhr')->createJsonRedirectResponse(
            'politizr.xhr.document',
            'reactionDelete'
        );

        return $jsonResponse;
    }

    /* ######################################################################################################## */
    /*                                            GESTION COMMENTAIRE                                           */
    /* ######################################################################################################## */

    /**
     *  Enregistre un nouveau commentaire
     */
    public function commentNewAction(Request $request)
    {
        $logger = $this->get('logger');
        $logger->info('*** commentNewAction');

        $jsonResponse = $this->get('politizr.routing.xhr')->createJsonHtmlResponse(
            'politizr.xhr.document',
            'commentNew'
        );

        return $jsonResponse;
    }

    /**
     *  Commentaires d'un document
     */
    public function commentsAction(Request $request)
    {
        $logger = $this->get('logger');
        $logger->info('*** commentsAction');

        $jsonResponse = $this->get('politizr.routing.xhr')->createJsonHtmlResponse(
            'politizr.xhr.document',
            'comments'
        );

        return $jsonResponse;
    }
}
