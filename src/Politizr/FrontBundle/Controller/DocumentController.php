<?php

namespace Politizr\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Politizr\Model\PDDebateQuery;
use Politizr\Model\PDReactionQuery;
use Politizr\Model\PDocumentQuery;
use Politizr\Model\PUserQuery;
use Politizr\Model\PQOrganizationQuery;
use Politizr\Model\PTagQuery;

use Politizr\FrontBundle\Form\Type\PDDebateType;

/**
 * Gestion des documents: débats, réactions, commentaires.
 *
 * @author Lionel Bouzonville
 */
class DocumentController extends Controller
{

    /* ######################################################################################################## */
    /*                                        AFFICHAGE DÉBAT & RÉACTION                                        */
    /* ######################################################################################################## */

    /**
     * Fil débat
     */
    public function debateFeedAction($slug)
    {
        $logger = $this->get('logger');
        $logger->info('*** debateFeedAction');
        $logger->info('$slug = '.print_r($slug, true));

        $debate = PDDebateQuery::create()->filterBySlug($slug)->findOne();
        if (!$debate) {
            throw new NotFoundHttpException('Debate "'.$slug.'" not found.');
        }
        if (!$debate->getOnline()) {
            throw new NotFoundHttpException('Debate "'.$slug.'" not online.');
        }

        return $this->render('PolitizrFrontBundle:Document:debateFeed.html.twig', array(
                    'debate' => $debate
        ));
    }

    /**
     * Détail débat
     */
    public function debateDetailAction($slug)
    {
        $logger = $this->get('logger');
        $logger->info('*** debateDetailAction');
        $logger->info('$slug = '.print_r($slug, true));

        $debate = PDDebateQuery::create()->filterBySlug($slug)->findOne();
        if (!$debate) {
            throw new NotFoundHttpException('Debate "'.$slug.'" not found.');
        }
        if (!$debate->getOnline()) {
            throw new NotFoundHttpException('Debate "'.$slug.'" not online.');
        }
        if (!$debate->getPublished()) {
            throw new NotFoundHttpException('Debate "'.$slug.'" not published.');
        }

        $debate->setNbViews($debate->getNbViews() + 1);
        $debate->save();

        // Explosion des paragraphes / http://stackoverflow.com/questions/8757826/i-need-to-split-text-delimited-by-paragraph-tag
        $description = str_replace('</p>', '', $debate->getDescription());
        $paragraphs = explode('<p>', $description);
        array_shift($paragraphs);

        return $this->render('PolitizrFrontBundle:Document:debateDetail.html.twig', array(
                    'debate' => $debate,
                    'paragraphs' => $paragraphs,
        ));
    }

    /**
     * Détail réaction
     */
    public function reactionDetailAction($slug)
    {
        $logger = $this->get('logger');
        $logger->info('*** reactionDetailAction');
        $logger->info('$slug = '.print_r($slug, true));

        $reaction = PDReactionQuery::create()->filterBySlug($slug)->findOne();
        if (!$reaction) {
            throw new NotFoundHttpException('Reaction "'.$slug.'" not found.');
        }
        if (!$reaction->getOnline()) {
            throw new NotFoundHttpException('Reaction "'.$slug.'" not online.');
        }

        $debate = $reaction ->getDebate();
        if (!$debate) {
            throw new NotFoundHttpException('Debate of reaction "'.$slug.'" not found.');
        }
        if (!$debate->getOnline()) {
            throw new NotFoundHttpException('Debate of reaction "'.$slug.'" not online.');
        }

        $reaction->setNbViews($reaction->getNbViews() + 1);
        $reaction->save();

        // Explosion des paragraphes / http://stackoverflow.com/questions/8757826/i-need-to-split-text-delimited-by-paragraph-tag
        $description = str_replace('</p>', '', $reaction->getDescription());
        $paragraphs = explode('<p>', $description);
        array_shift($paragraphs);

        return $this->render('PolitizrFrontBundle:Document:reactionDetail.html.twig', array(
                    'reaction' => $reaction,
                    'debate' => $debate,
                    'paragraphs' => $paragraphs,
        ));
    }

    /**
     * Détail auteur
     */
    public function userDetailAction($slug)
    {
        $logger = $this->get('logger');
        $logger->info('*** userDetailAction');
        $logger->info('$slug = '.print_r($slug, true));

        $user = PUserQuery::create()->filterBySlug($slug)->findOne();
        if (!$user) {
            throw new NotFoundHttpException('User "'.$slug.'" not found.');
        }
        if (!$user->getOnline()) {
            throw new NotFoundHttpException('User "'.$slug.'" not online.');
        }

        $user->setNbViews($user->getNbViews() + 1);
        $user->save();

        // PDDebate (collection)
        $debates = $user->getDebates();

        // PDReaction (collection)
        $reactions = $user->getReactions();

        return $this->render('PolitizrFrontBundle:Document:userDetail.html.twig', array(
                    'user' => $user,
                    'debates' => $debates,
                    'reactions' => $reactions
            ));
    }

    /* ######################################################################################################## */
    /*                                              ÉDITION DEBAT                                               */
    /* ######################################################################################################## */

    /**
     *  Création d'un nouveau débat
     */
    public function debateNewAction()
    {
        $logger = $this->get('logger');
        $logger->info('*** debateNewAction');

        // Service associé a la création d'une réaction
        $debate = $this->get('politizr.service.document')->debateNew();

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

        return $this->render('PolitizrFrontBundle:Document:debateEdit.html.twig', array(
            'debate' => $debate,
            'form' => $form->createView(),
            ));
    }

    /* ######################################################################################################## */
    /*                                           ÉDITION REACTION                                               */
    /* ######################################################################################################## */

    /**
     *  Création d'une nouvelle réaction
     */
    public function reactionNewAction($debateId, $parentId)
    {
        $logger = $this->get('logger');
        $logger->info('*** reactionNewAction');

        // Service associé a la création d'une réaction
        $reaction = $this->get('politizr.service.document')->reactionNew($debateId, $parentId);

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

        return $this->render('PolitizrFrontBundle:Document:reactionEdit.html.twig', array(
            'reaction' => $reaction,
            'form' => $form->createView(),
            ));
    }

    /* ######################################################################################################## */
    /*                             PAGE ORGANISATION / PARTI POLITIQUE                                          */
    /* ######################################################################################################## */

    /**
     *  Détail d'une organisation
     */
    public function organizationAction($slug)
    {
        $logger = $this->get('logger');
        $logger->info('*** organizationAction');
        $logger->info('$slug = '.print_r($slug, true));

        $organization = PQOrganizationQuery::create()->filterBySlug($slug)->findOne();
        if (!$organization) {
            throw new NotFoundHttpException('Organization "'.$slug.'" not found.');
        }
        if (!$organization->getOnline()) {
            throw new NotFoundHttpException('Organization "'.$slug.'" not online.');
        }

        return $this->render('PolitizrFrontBundle:Document:organization.html.twig', array(
            'organization' => $organization,
            ));
    }

    /* ######################################################################################################## */
    /*                                              PAGE TAG                                                    */
    /* ######################################################################################################## */

    /**
     *  Détail d'un tag
     */
    public function tagAction($slug)
    {
        $logger = $this->get('logger');
        $logger->info('*** tagAction');
        $logger->info('$slug = '.print_r($slug, true));

        $tag = PTagQuery::create()->filterBySlug($slug)->findOne();
        if (!$tag) {
            throw new NotFoundHttpException('Tag "'.$slug.'" not found.');
        }
        if (!$tag->getOnline()) {
            throw new NotFoundHttpException('Tag "'.$slug.'" not online.');
        }

        return $this->render('PolitizrFrontBundle:Document:tag.html.twig', array(
            'tag' => $tag,
            ));
    }
}
