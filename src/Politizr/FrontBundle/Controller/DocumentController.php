<?php

namespace Politizr\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Politizr\Exception\InconsistentDataException;

use Politizr\Model\PDocumentInterface;
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
    /**
     * Common document "check" validity
     *
     * @param PDocument
     * @param boolean $online
     * @param boolean $published
     * @param boolean
     */
    private function checkDocument(PDocumentInterface $document, $online = true, $published = true)
    {
        if (!$document) {
            throw new NotFoundHttpException(sprintf('Document not found.'));
        }
        if ($online && !$document->getOnline()) {
            throw new NotFoundHttpException(sprintf('Document not online.'));
        }
        if ($published && !$document->getPublished()) {
            throw new NotFoundHttpException(sprintf('Document not published.'));
        }

        return true;
    }

    /**
     * Common document edit "check" validity
     *
     * @param PDocument
     * @param integer $userId
     * @param boolean
     */
    private function checkDocumentEditable(PDocumentInterface $document, $userId)
    {
        if (!$document) {
            throw new NotFoundHttpException(sprintf('Document not found.'));
        }
        if (!$document->isOwner($userId)) {
            throw new InconsistentDataException(sprintf('Document not found.'));
        }
        if ($document->getPublished()) {
            throw new InconsistentDataException(sprintf('Document already published.'));
        }
    }

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

        $debate = PDDebateQuery::create()->filterBySlug($slug)->findOne();
        $this->checkDocument($debate);

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
        $this->checkDocument($reaction);

        $debate = $reaction ->getDebate();
        $this->checkDocument($debate, false, false);

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
        $this->checkDocument($debate);

        // get debate feed
        $timelineService = $this->get('politizr.functional.timeline');

        $timeline = $timelineService->generateDebateFeedTimeline($debate->getId());
        $timelineDateKey = $timelineService->generateTimelineDateKey($timeline);

        $noResult = false;
        if (count($timeline) == 0) {
            $noResult = true;
        }

        return $this->render('PolitizrFrontBundle:Debate:feed.html.twig', array(
            'profileSuffix' => $this->get('politizr.tools.global')->computeProfileSuffix(),
            'debate' => $debate,
            'timelineDateKey' => $timelineDateKey,
            'noResult' => $noResult,
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

        $user = $this->getUser();
        if (!$user) {
            throw new InconsistentDataException('Current user not found.');
        }

        // search "as new" already created debate
        $debate = PDDebateQuery::create()
                    ->filterByPUserId($user->getId())
                    ->where('p_d_debate.created_at = p_d_debate.updated_at')
                    ->findOne();

        if (!$debate) {
            $debate = $this->get('politizr.functional.document')->createDebate();
        }

        return $this->redirect($this->generateUrl('DebateDraftEdit'.$this->get('politizr.tools.global')->computeProfileSuffix(), array(
            'uuid' => $debate->getUuid()
        )));
    }

    /**
     * Debate edition
     *
     * @param $uuid
     */
    public function debateEditAction($uuid)
    {
        $logger = $this->get('logger');
        $logger->info('*** debateEditAction');
        $logger->info('$uuid = '.print_r($uuid, true));

        $user = $this->getUser();

        $debate = PDDebateQuery::create()->filterByUuid($uuid)->findOne();
        $this->checkDocumentEditable($debate, $user->getId());
        
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
    public function reactionNewAction($debateUuid, $parentUuid)
    {
        $logger = $this->get('logger');
        $logger->info('*** reactionNewAction');

        $user = $this->getUser();
        if (!$user) {
            throw new InconsistentDataException('Current user not found.');
        }

        // associated objects
        $debate = PDDebateQuery::create()->filterByUuid($debateUuid)->findOne();
        $parent = null;
        $parentId = null;
        if ($parentUuid && !empty($parentUuid)) {
            $parent = PDReactionQuery::create()->filterByUuid($parentUuid)->findOne();
            if (!$parent) {
                throw new InconsistentDataException('Parent\'s reaction not found.');
            }
            $parentId = $parent->getId();
        }
        if (!$debate) {
            throw new InconsistentDataException('Debate\'s reaction not found.');
        }

        // search "as new" already created reaction
        $reaction = PDReactionQuery::create()
                    ->filterByPUserId($user->getId())
                    ->filterByPDDebateId($debate->getId())
                    ->_if($parentId)
                        ->filterByParentReactionId($parentId)
                    ->_endif()
                    ->where('p_d_reaction.created_at = p_d_reaction.updated_at')
                    ->findOne();

        if (!$reaction) {
            $reaction = $this->get('politizr.functional.document')->createReaction($debate, $parent);
        }

        return $this->redirect($this->generateUrl('ReactionDraftEdit'.$this->get('politizr.tools.global')->computeProfileSuffix(), array(
            'uuid' => $reaction->getUuid()
        )));
    }

    /**
     * Reaction edition
     *
     * @param $uuid
     */
    public function reactionEditAction($uuid)
    {
        $logger = $this->get('logger');
        $logger->info('*** reactionEditAction');
        $logger->info('$uuid = '.print_r($uuid, true));

        $user = $this->getUser();

        $reaction = PDReactionQuery::create()->filterByUuid($uuid)->findOne();
        $this->checkDocumentEditable($reaction, $user->getId());

        // parent document for compared edition
        if (null === $reaction->getParentReactionId()) {
            $parent = $reaction->getDebate();
        } else {
            $parent = PDReactionQuery::create()->findPk($reaction->getParentReactionId());
        }

        // Paragraphs explode
        $utilsManager = $this->get('politizr.tools.global');
        $paragraphs = $utilsManager->explodeParagraphs($parent->getDescription());

        // forms
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

        return $this->render('PolitizrFrontBundle:Document:drafts.html.twig', array(
            'profileSuffix' => $this->get('politizr.tools.global')->computeProfileSuffix(),
        ));
    }

    /* ######################################################################################################## */
    /*                                                 CONTRIBUTIONS                                            */
    /* ######################################################################################################## */

    /**
     * Debates & Reactions
     */
    public function myPublicationsAction()
    {
        $logger = $this->get('logger');
        $logger->info('*** myPublicationsAction');

        return $this->render('PolitizrFrontBundle:Document:myPublications.html.twig', array(
            'profileSuffix' => $this->get('politizr.tools.global')->computeProfileSuffix(),
        ));
    }
}
