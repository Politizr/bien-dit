<?php

namespace Politizr\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Politizr\Model\PDDebateQuery;

use Politizr\Model\PDDebate;


/**
 * Gestion des documents: débats, réactions, commentaires.
 *
 * TODO:
 *  - 
 *
 * @author Lionel Bouzonville
 */
class DocumentController extends Controller {

    /* ######################################################################################################## */
    /*                                                 ROUTING CLASSIQUE                                        */
    /* ######################################################################################################## */

    /**
     * Connexion
     */
    public function loginAction()
    {
        $logger = $this->get('logger');
        $logger->info('*** loginAction');

        // *********************************** //
        //      Formulaires
        // *********************************** //
        $formLogin = $this->createForm(new LoginType());
        $formLostPassword = $this->createForm(new LostPasswordType());

        // *********************************** //
        //      Affichage de la vue
        // *********************************** //
        return $this->render('PolitizrFrontBundle:Navigation:login.html.twig', array(
                        'formLogin' => $formLogin->createView(),
                        'formLostPassword' => $formLostPassword->createView()
            ));
    }

