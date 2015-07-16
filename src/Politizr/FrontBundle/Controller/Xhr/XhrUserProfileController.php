<?php

namespace Politizr\FrontBundle\Controller\Xhr;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;

/**
 *  Gestion des informations du profil du user / appels XHR
 *
 *  @author Lionel Bouzonville
 */
class XhrUserProfileController extends Controller
{
    /**
     *  Mise à jour des informations personnelles du user
     */
    public function userProfileUpdateAction(Request $request)
    {
        $logger = $this->get('logger');
        $logger->info('*** userProfileUpdateAction');

        $jsonResponse = $this->get('politizr.routing.xhr')->createJsonResponse(
            'politizr.xhr.user',
            'userProfileUpdate'
        );

        return $jsonResponse;
    }

    /**
     *  Upload de la photo de profil du user
     */
    public function userPhotoUploadAction(Request $request)
    {
        $logger = $this->get('logger');
        $logger->info('*** userPhotoUploadAction');

        $jsonResponse = $this->get('politizr.routing.xhr')->createJsonHtmlResponse(
            'politizr.xhr.user',
            'userPhotoUpload'
        );

        return $jsonResponse;
    }

    /**
     *  Suppression de la photo de profil du user
     */
    public function userPhotoDeleteAction(Request $request)
    {
        $logger = $this->get('logger');
        $logger->info('*** userPhotoDeleteAction');

        $jsonResponse = $this->get('politizr.routing.xhr')->createJsonResponse(
            'politizr.xhr.user',
            'userPhotoDelete'
        );

        return $jsonResponse;
    }

    /**
     *  Upload de la photo de fond du profil du user
     */
    public function userBackPhotoUploadAction(Request $request)
    {
        $logger = $this->get('logger');
        $logger->info('*** userBackPhotoUploadAction');

        $jsonResponse = $this->get('politizr.routing.xhr')->createJsonHtmlResponse(
            'politizr.xhr.user',
            'userBackPhotoUpload'
        );

        return $jsonResponse;
    }

    /**
     *  Suppression de la photo de fond du profil du user
     */
    public function userBackPhotoDeleteAction(Request $request)
    {
        $logger = $this->get('logger');
        $logger->info('*** userBackPhotoDeleteAction');

        $jsonResponse = $this->get('politizr.routing.xhr')->createJsonResponse(
            'politizr.xhr.user',
            'userBackPhotoDelete'
        );

        return $jsonResponse;
    }

    /**
     *  Mise à jour des informations "organisation en cours" du user
     */
    public function orgaProfileUpdateAction(Request $request)
    {
        $logger = $this->get('logger');
        $logger->info('*** orgaProfileUpdateAction');

        $jsonResponse = $this->get('politizr.routing.xhr')->createJsonResponse(
            'politizr.xhr.user',
            'orgaProfileUpdate'
        );

        return $jsonResponse;
    }

    /**
     *  Mise à jour des informations "affinités politiques" du user
     */
    public function affinitiesProfileUpdateAction(Request $request)
    {
        $logger = $this->get('logger');
        $logger->info('*** affinitiesProfileUpdateAction');

        $jsonResponse = $this->get('politizr.routing.xhr')->createJsonResponse(
            'politizr.xhr.user',
            'affinitiesProfile'
        );

        return $jsonResponse;
    }

    /**
     *  Création d'un mandat pour un user
     */
    public function mandateProfileCreateAction(Request $request)
    {
        $logger = $this->get('logger');
        $logger->info('*** mandateProfileCreateAction');

        $jsonResponse = $this->get('politizr.routing.xhr')->createJsonHtmlResponse(
            'politizr.xhr.user',
            'mandateProfileCreate'
        );

        return $jsonResponse;
    }

    /**
     *  MAJ d'un mandat pour un user
     */
    public function mandateProfileUpdateAction(Request $request)
    {
        $logger = $this->get('logger');
        $logger->info('*** mandateProfileUpdateAction');

        $jsonResponse = $this->get('politizr.routing.xhr')->createJsonResponse(
            'politizr.xhr.user',
            'mandateProfileUpdate'
        );

        return $jsonResponse;
    }

    /**
     *  Suppression d'un mandat pour un user
     */
    public function mandateProfileDeleteAction(Request $request)
    {
        $logger = $this->get('logger');
        $logger->info('*** mandateProfileDeleteAction');

        $jsonResponse = $this->get('politizr.routing.xhr')->createJsonResponse(
            'politizr.xhr.user',
            'mandateProfileDelete'
        );

        return $jsonResponse;
    }

    /**
     *  Mise à jour des informations personnelles du user
     */
    public function userPersoUpdateAction(Request $request)
    {
        $logger = $this->get('logger');
        $logger->info('*** userPersoUpdateAction');

        $jsonResponse = $this->get('politizr.routing.xhr')->createJsonResponse(
            'politizr.xhr.user',
            'userPersoUpdate'
        );

        return $jsonResponse;
    }
    
    /**
     * Paginated timeline
     */
    public function paginatedListAction(Request $request)
    {
        $logger = $this->get('logger');
        $logger->info('*** paginatedListAction');

        $jsonResponse = $this->get('politizr.routing.xhr')->createJsonHtmlResponse(
            'politizr.xhr.user',
            'timelinePaginated'
        );

        return $jsonResponse;
    }
}
