<?php

namespace Politizr\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Symfony\Component\EventDispatcher\GenericEvent;

use Politizr\Model\PDDebateQuery;
use Politizr\Model\PDReactionQuery;
use Politizr\Model\PDocumentQuery;
use Politizr\Model\PDCommentQuery;
use Politizr\Model\PUserQuery;
use Politizr\Model\PUFollowDDQuery;
use Politizr\Model\PUFollowUQuery;

use Politizr\Model\PDDebate;
use Politizr\Model\PDReaction;
use Politizr\Model\PDocument;
use Politizr\Model\PDComment;
use Politizr\Model\PUser;
use Politizr\Model\PUFollowDD;
use Politizr\Model\PUFollowU;

use Politizr\FrontBundle\Form\Type\PDCommentType;


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
     * Fil débat
     */
    public function debateFeedAction($id, $slug)
    {
        $logger = $this->get('logger');
        $logger->info('*** debateFeedAction');
        $logger->info('$id = '.print_r($id, true));
        $logger->info('$slug = '.print_r($slug, true));

        // *********************************** //
        //      Récupération objet
        // *********************************** //
        $debate = PDDebateQuery::create()->findPk($id);
        if (!$debate) {
            throw new NotFoundHttpException('pDDebate n°'.$id.' not found.');
        }
        if (!$debate->getOnline()) {
            throw new NotFoundHttpException('pDDebate n°'.$id.' not online.');
        }

        // *********************************** //
        //      Affichage de la vue
        // *********************************** //
        return $this->render('PolitizrFrontBundle:Document:debateFeed.html.twig', array(
                    'debate' => $debate
        ));
    }

    /**
     * Détail débat
     */
    public function debateDetailAction($id, $slug)
    {
        $logger = $this->get('logger');
        $logger->info('*** debateDetailAction');
        $logger->info('$id = '.print_r($id, true));
        $logger->info('$slug = '.print_r($slug, true));

        // *********************************** //
        //      Récupération objet
        // *********************************** //
        $debate = PDDebateQuery::create()->findPk($id);
        if (!$debate) {
            throw new NotFoundHttpException('Debate n°'.$id.' not found.');
        }
        if (!$debate->getOnline()) {
            throw new NotFoundHttpException('Debate n°'.$id.' not online.');
        }
        if (!$debate->getPublished()) {
            throw new NotFoundHttpException('Debate n°'.$id.' not published.');
        }

        $debate->setNbViews($debate->getNbViews() + 1);
        $debate->save();

        // Explosion des paragraphes / http://stackoverflow.com/questions/8757826/i-need-to-split-text-delimited-by-paragraph-tag
        $description = str_replace('</p>', '', $debate->getDescription());
        $paragraphs = explode('<p>', $description);
        array_shift($paragraphs);

        // *********************************** //
        //      Affichage de la vue
        // *********************************** //
        return $this->render('PolitizrFrontBundle:Document:debateDetail.html.twig', array(
        			'debate' => $debate,
                    'paragraphs' => $paragraphs,
        ));
    }


    /**
     * Détail brouillon débat
     */
    public function debateDraftAction($id)
    {
        $logger = $this->get('logger');
        $logger->info('*** debateDraftAction');
        $logger->info('$id = '.print_r($id, true));

        // *********************************** //
        //      Récupération objet
        // *********************************** //
        $debate = PDDebateQuery::create()->findPk($id);
        if (!$debate) {
            throw new NotFoundHttpException('Debate n°'.$id.' not found.');
        }
        if (!$debate->getOnline()) {
            throw new NotFoundHttpException('Debate n°'.$id.' not online.');
        }
        if ($debate->getPublished()) {
            throw new NotFoundHttpException('Debate n°'.$id.' has been published.');
        }

        // Explosion des paragraphes / http://stackoverflow.com/questions/8757826/i-need-to-split-text-delimited-by-paragraph-tag
        $description = str_replace('</p>', '', $debate->getDescription());
        $paragraphs = explode('<p>', $description);
        array_shift($paragraphs);

        // *********************************** //
        //      Affichage de la vue
        // *********************************** //
        return $this->render('PolitizrFrontBundle:Document:debateDraft.html.twig', array(
                    'debate' => $debate,
                    'paragraphs' => $paragraphs,
        ));
    }

    /**
     * Détail réaction
     */
    public function reactionDetailAction($id, $slug)
    {
        $logger = $this->get('logger');
        $logger->info('*** reactionDetailAction');
        $logger->info('$id = '.print_r($id, true));
        $logger->info('$slug = '.print_r($slug, true));

        // *********************************** //
        //      Récupération objet
        // *********************************** //
        $reaction = PDReactionQuery::create()->findPk($id);
        if (!$reaction) {
            throw new NotFoundHttpException('Reaction n°'.$id.' not found.');
        }
        if (!$reaction->getOnline()) {
            throw new NotFoundHttpException('Reaction n°'.$id.' not online.');
        }

        $debate = $reaction ->getDebate();
        if (!$debate) {
            throw new NotFoundHttpException('Debate n°'.$id.' not found.');
        }
        if (!$debate->getOnline()) {
            throw new NotFoundHttpException('Debate n°'.$id.' not online.');
        }

        $reaction->setNbViews($reaction->getNbViews() + 1);
        $reaction->save();

        // Explosion des paragraphes / http://stackoverflow.com/questions/8757826/i-need-to-split-text-delimited-by-paragraph-tag
        $description = str_replace('</p>', '', $reaction->getDescription());
        $paragraphs = explode('<p>', $description);
        array_shift($paragraphs);

        // *********************************** //
        //      Affichage de la vue
        // *********************************** //
        return $this->render('PolitizrFrontBundle:Document:reactionDetail.html.twig', array(
                    'reaction' => $reaction,
                    'debate' => $debate,
                    'paragraphs' => $paragraphs,
        ));
    }

    /**
     * Détail réaction
     */
    public function reactionDraftAction($id)
    {
        $logger = $this->get('logger');
        $logger->info('*** reactionDraftAction');
        $logger->info('$id = '.print_r($id, true));

        // *********************************** //
        //      Récupération objet
        // *********************************** //
        $reaction = PDReactionQuery::create()->findPk($id);
        if (!$reaction) {
            throw new NotFoundHttpException('Reaction n°'.$id.' not found.');
        }
        if (!$reaction->getOnline()) {
            throw new NotFoundHttpException('Reaction n°'.$id.' not online.');
        }
        if ($reaction->getPublished()) {
            throw new NotFoundHttpException('Debate n°'.$id.' has been published.');
        }

        // TODO > UseCase à gérer = suppression d'un débat pour lesquels des réaction sont en cours de rédaction => via event sur suppression debat?
        $debate = $reaction ->getDebate();
        if (!$debate) {
            throw new NotFoundHttpException('Debate n°'.$id.' not found.');
        }
        if (!$debate->getOnline()) {
            throw new NotFoundHttpException('Debate n°'.$id.' not online.');
        }


        // Explosion des paragraphes / http://stackoverflow.com/questions/8757826/i-need-to-split-text-delimited-by-paragraph-tag
        $description = str_replace('</p>', '', $reaction->getDescription());
        $paragraphs = explode('<p>', $description);
        array_shift($paragraphs);


        // *********************************** //
        //      Affichage de la vue
        // *********************************** //
        return $this->render('PolitizrFrontBundle:Document:reactionDraft.html.twig', array(
                    'reaction' => $reaction,
                    'debate' => $debate,
                    'paragraphs' => $paragraphs,
        ));
    }

    /**
     * Détail auteur
     */
    public function userDetailAction($id, $slug)
    {
        $logger = $this->get('logger');
        $logger->info('*** userDetailAction');
        $logger->info('$id = '.print_r($id, true));
        $logger->info('$slug = '.print_r($slug, true));

        // *********************************** //
        //      Récupération objet
        // *********************************** //
        $user = PUserQuery::create()->findPk($id);
        if (!$user) {
            throw new NotFoundHttpException('User n°'.$id.' not found.');
        }
        if (!$user->getOnline()) {
            throw new NotFoundHttpException('User n°'.$id.' not online.');
        }

        $user->setNbViews($user->getNbViews() + 1);
        $user->save();


        // *********************************** //
        //      Récupération des objets associés
        // *********************************** //

        // PDDebate (collection)
        $debates = $user->getDebates();

        // PDReaction (collection)
        $reactions = $user->getReactions();

        // *********************************** //
        //      Affichage de la vue
        // *********************************** //
        return $this->render('PolitizrFrontBundle:Document:userDetail.html.twig', array(
                    'user' => $user,
                    'debates' => $debates,
                    'reactions' => $reactions
            ));
    }

    /* ######################################################################################################## */
    /*                                                  FONCTIONS AJAX                                          */
    /* ######################################################################################################## */

    /**
     *  Suivre / Ne plus suivre debat / user
     */
    public function followAction(Request $request) {
        $logger = $this->get('logger');
        $logger->info('*** followAction');

        $type = $request->get('type');
        $logger->info('$type = ' . print_r($type, true));

        switch($type) {
            case PDocument::TYPE_DEBATE:
                $jsonResponse = $this->get('politizr.routing.ajax')->createJsonHtmlResponse(
                    'politizr.service.document',
                    'follow'
                );

                break;
            case PDocument::TYPE_USER:
                $jsonResponse = $this->get('politizr.routing.ajax')->createJsonHtmlResponse(
                    'politizr.service.user',
                    'follow'
                );

                break;
        }

        return $jsonResponse;
    }

    /**
     *  Note débat / réaction / commentaire
     */
    public function noteAction(Request $request) {
        $logger = $this->get('logger');
        $logger->info('*** noteAction');

        $jsonResponse = $this->get('politizr.routing.ajax')->createJsonHtmlResponse(
            'politizr.service.document',
            'note'
        );

        return $jsonResponse;
    }

    /**
     *  Commentaires d'un document
     */
    public function commentsAction(Request $request) {
        $logger = $this->get('logger');
        $logger->info('*** commentsAction');

        $jsonResponse = $this->get('politizr.routing.ajax')->createJsonHtmlResponse(
            'politizr.service.document',
            'comments'
        );

        return $jsonResponse;
    }
}