<?php

namespace Politizr\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Politizr\Exception\InconsistentDataException;

use Politizr\Model\PDocumentPeer;

use Politizr\Model\PUserQuery;
use Politizr\Model\PDocumentQuery;
use Politizr\Model\PDDebateQuery;
use Politizr\Model\PTagQuery;
use Politizr\Model\PUTaggedTQuery;
use Politizr\Model\PUFollowTQuery;
use Politizr\Model\PUFollowDDQuery;
use Politizr\Model\PUFollowUQuery;
use Politizr\Model\PDCommentQuery;

use Politizr\Model\PUser;
use Politizr\Model\PUType;
use Politizr\Model\PTag;
use Politizr\Model\PDDebate;
use Politizr\Model\PTTagType;
use Politizr\Model\PUTaggedT;
use Politizr\Model\PUFollowT;

use Politizr\FrontBundle\Form\Type\PDDebateType;

/**
 * Gestion profil élu
 *
 * TODO:
 *	- gestion des erreurs / levés d'exceptions à revoir/blindés pour les appels Ajax
 *  - refactorisation pour réduire les doublons de code entre les tables PUTaggedT et PUFollowT
 *  - refactoring gestion des tags > gestion des doublons / admin + externalisation logique métier dans les *Query class
 *
 * @author Lionel Bouzonville
 */
class ProfileEController extends Controller {

    /* ######################################################################################################## */
    /*                                                 ROUTING CLASSIQUE                                        */
    /* ######################################################################################################## */

    /* ######################################################################################################## */
    /*                                                      ÉLU                                                 */
    /* ######################################################################################################## */

    /**
     *  Accueil
     */
    public function homepageEAction()
    {
        $logger = $this->get('logger');
        $logger->info('*** homepageEAction');

        // Récupération user courant
        $pUser = $this->getUser();

        // *********************************** //
        //      Récupération objets vue
        // *********************************** //


        // *********************************** //
        //      Affichage de la vue
        // *********************************** //

        return $this->render('PolitizrFrontBundle:ProfileE:homepageE.html.twig', array(
            ));
    }


}