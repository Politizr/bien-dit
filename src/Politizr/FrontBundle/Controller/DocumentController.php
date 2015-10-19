<?php

namespace Politizr\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Politizr\Model\PDDebateQuery;
use Politizr\Model\PDReactionQuery;
use Politizr\Model\PUserQuery;
use Politizr\Model\PQOrganizationQuery;
use Politizr\Model\PTagQuery;


use Politizr\FrontBundle\Form\Type\PDDebateType;
use Politizr\FrontBundle\Form\Type\PDDebatePhotoInfoType;
use Politizr\FrontBundle\Form\Type\PDReactionType;
use Politizr\FrontBundle\Form\Type\PDReactionPhotoInfoType;

/**
 * Document controller: debates, reactions, comments
 *
 * @author Lionel Bouzonville
 */
class DocumentController extends Controller
{

    /* ######################################################################################################## */
    /*                                        AFFICHAGE DÉBAT & RÉACTION                                        */
    /* ######################################################################################################## */

    /**
     * Détail débat
     */
    public function debateDetailAction($slug)
    {
        $logger = $this->get('logger');
        $logger->info('*** debateDetailAction');
        $logger->info('$slug = '.print_r($slug, true));

        $suffix = $this->get('politizr.tools.global')->computeProfileSuffix();
        if (null === $suffix) {
            return $this->redirect($this->generateUrl('Login'));
        }

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

        // Paragraphs explode
        $utilsManager = $this->get('politizr.tools.global');
        $paragraphs = $utilsManager->explodeParagraphs($debate->getDescription());

        return $this->render('PolitizrFrontBundle:Debate:detail.html.twig', array(
            'profileSuffix' => $this->get('politizr.tools.global')->computeProfileSuffix(),
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

        $suffix = $this->get('politizr.tools.global')->computeProfileSuffix();
        if (null === $suffix) {
            return $this->redirect($this->generateUrl('Login'));
        }

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

        // Paragraphs explode
        $utilsManager = $this->get('politizr.tools.global');
        $paragraphs = $utilsManager->explodeParagraphs($reaction->getDescription());

        return $this->render('PolitizrFrontBundle:Reaction:detail.html.twig', array(
            'profileSuffix' => $this->get('politizr.tools.global')->computeProfileSuffix(),
            'reaction' => $reaction,
            'debate' => $debate,
            'paragraphs' => $paragraphs,
        ));
    }

    /**
     * Fil débat
     */
    public function debateFeedAction($slug)
    {
        $logger = $this->get('logger');
        $logger->info('*** debateFeedAction');
        $logger->info('$slug = '.print_r($slug, true));

        $suffix = $this->get('politizr.tools.global')->computeProfileSuffix();
        if (null === $suffix) {
            return $this->redirect($this->generateUrl('Login'));
        }

        $debate = PDDebateQuery::create()->filterBySlug($slug)->findOne();
        if (!$debate) {
            throw new NotFoundHttpException('Debate "'.$slug.'" not found.');
        }
        if (!$debate->getOnline()) {
            throw new NotFoundHttpException('Debate "'.$slug.'" not online.');
        }

        // get debate feed
        $timelineService = $this->get('politizr.functional.timeline');

        $timeline = $timelineService->generateDebateFeedTimeline($debate->getId());
        $timelineDateKey = $timelineService->generateTimelineDateKey($timeline);

        return $this->render('PolitizrFrontBundle:Debate:feed.html.twig', array(
            'profileSuffix' => $this->get('politizr.tools.global')->computeProfileSuffix(),
            'debate' => $debate,
            'timelineDateKey' => $timelineDateKey
        ));
    }


    /* ######################################################################################################## */
    /*                                              ÉDITION DEBAT                                               */
    /* ######################################################################################################## */

    /**
     * Création d'un nouveau débat
     */
    public function debateNewAction()
    {
        $logger = $this->get('logger');
        $logger->info('*** debateNewAction');

        // Service associé a la création d'une réaction
        $debate = $this->get('politizr.functional.document')->createDebate();

        return $this->redirect($this->generateUrl('DebateDraftEdit'.$this->get('politizr.tools.global')->computeProfileSuffix(), array(
            'id' => $debate->getId()
        )));
    }

    /**
     * Edition d'un débat
     * @todo remove id to manage with slug > force user to set a title when he creates a new debate?
     */
    public function debateEditAction($id)
    {
        $logger = $this->get('logger');
        $logger->info('*** debateEditAction');
        $logger->info('$id = '.print_r($id, true));

        $user = $this->getUser();

        $debate = PDDebateQuery::create()->findPk($id);
        if (!$debate) {
            throw new InconsistentDataException('Debate n°'.$id.' not found.');
        }
        if (!$debate->isOwner($user->getId())) {
            throw new InconsistentDataException('Debate n°'.$id.' is not yours.');
        }
        if ($debate->getPublished()) {
            throw new InconsistentDataException('Debate n°'.$id.' is published and cannot be edited anymore.');
        }
        
        $form = $this->createForm(new PDDebateType(), $debate);
        $formPhotoInfo = $this->createForm(new PDDebatePhotoInfoType(), $debate);

        return $this->render('PolitizrFrontBundle:Debate:edit.html.twig', array(
            'profileSuffix' => $this->get('politizr.tools.global')->computeProfileSuffix(),
            'debate' => $debate,
            'form' => $form->createView(),
            'formPhotoInfo' => $formPhotoInfo->createView(),
            ));
    }

    /* ######################################################################################################## */
    /*                                           ÉDITION REACTION                                               */
    /* ######################################################################################################## */

    /**
     * Création d'une nouvelle réaction
     */
    public function reactionNewAction($debateId, $parentId)
    {
        $logger = $this->get('logger');
        $logger->info('*** reactionNewAction');

        // Service associé a la création d'une réaction
        $reaction = $this->get('politizr.functional.document')->createReaction($debateId, $parentId);

        return $this->redirect($this->generateUrl('ReactionDraftEdit'.$this->get('politizr.tools.global')->computeProfileSuffix(), array(
            'id' => $reaction->getId()
        )));
    }

    /**
     * Edition d'une réaction
     * @todo remove id to manage with slug > force user to set a title when he creates a new debate?
     */
    public function reactionEditAction($id)
    {
        $logger = $this->get('logger');
        $logger->info('*** reactionEditAction');
        $logger->info('$id = '.print_r($id, true));

        $user = $this->getUser();

        $reaction = PDReactionQuery::create()->findPk($id);
        if (!$reaction) {
            throw new InconsistentDataException('Reaction n°'.$id.' not found.');
        }
        if (!$reaction->isOwner($user->getId())) {
            throw new InconsistentDataException('Reaction n°'.$id.' is not yours.');
        }
        if ($reaction->getPublished()) {
            throw new InconsistentDataException('Reaction n°'.$id.' is published and cannot be edited anymore.');
        }

        // parent document for compared edition
        if (null == $reaction->getParentReactionId()) {
            $parent = $reaction->getDebate();
        } else {
            $parent = PDReactionQuery::create()->findPk($reaction->getParentReactionId());
        }

        // Paragraphs explode
        $utilsManager = $this->get('politizr.tools.global');
        $paragraphs = $utilsManager->explodeParagraphs($parent->getDescription());

        // forms
        $reaction = PDReactionQuery::create()->findPk($id);
        $form = $this->createForm(new PDReactionType(), $reaction);
        $formPhotoInfo = $this->createForm(new PDReactionPhotoInfoType(), $reaction);

        return $this->render('PolitizrFrontBundle:Reaction:edit.html.twig', array(
            'profileSuffix' => $this->get('politizr.tools.global')->computeProfileSuffix(),
            'reaction' => $reaction,
            'parent' => $parent,
            'paragraphs' => $paragraphs,
            'form' => $form->createView(),
            'formPhotoInfo' => $formPhotoInfo->createView(),
            ));
    }

    /* ######################################################################################################## */
    /*                                                  DRAFTS                                                  */
    /* ######################################################################################################## */

    /**
     * Drafts
     */
    public function draftsAction()
    {
        $logger = $this->get('logger');
        $logger->info('*** draftsAction');

        $user = $this->getUser();

        // Nb debate's drafts
        $nbDebateDrafts = PDDebateQuery::create()
            ->filterByPUserId($user->getId())
            ->filterByPublished(false)
            ->count();

        // Nb reaction's drafts
        $nbReactionDrafts = PDReactionQuery::create()
            ->filterByPUserId($user->getId())
            ->filterByPublished(false)
            ->count();

        $nbDrafts = $nbDebateDrafts + $nbReactionDrafts;

        return $this->render('PolitizrFrontBundle:Document:drafts.html.twig', array(
            'profileSuffix' => $this->get('politizr.tools.global')->computeProfileSuffix(),
            'nbDrafts' => $nbDrafts,
        ));
    }

    /* ######################################################################################################## */
    /*                                                 CONTRIBUTIONS                                            */
    /* ######################################################################################################## */

    /**
     * Debates
     */
    public function myDebatesAction()
    {
        $logger = $this->get('logger');
        $logger->info('*** myDebatesAction');

        $user = $this->getUser();
        
        $nbDebates = PDDebateQuery::create()
            ->filterByPUserId($user->getId())
            ->online()
            ->orderByPublishedAt('desc')
            ->count();

        return $this->render('PolitizrFrontBundle:Debate:myDebates.html.twig', array(
            'profileSuffix' => $this->get('politizr.tools.global')->computeProfileSuffix(),
            'nbDebates' => $nbDebates,
        ));
    }

    /**
     * Reactions
     */
    public function myReactionsAction()
    {
        $logger = $this->get('logger');
        $logger->info('*** myReactionsAction');

        $user = $this->getUser();
        
        $nbReactions = PDReactionQuery::create()
            ->filterByPUserId($user->getId())
            ->online()
            ->orderByPublishedAt('desc')
            ->count();

        return $this->render('PolitizrFrontBundle:Reaction:myReactions.html.twig', array(
            'profileSuffix' => $this->get('politizr.tools.global')->computeProfileSuffix(),
            'nbReactions' => $nbReactions,
        ));
    }
}
