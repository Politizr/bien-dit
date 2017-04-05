<?php

namespace Politizr\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Politizr\Exception\InconsistentDataException;

use Politizr\Constant\TagConstants;
use Politizr\Constant\ListingConstants;
use Politizr\Constant\LocalizationConstants;
use Politizr\Constant\ObjectTypeConstants;

use Politizr\Model\PDocumentInterface;
use Politizr\Model\PDDebateQuery;
use Politizr\Model\PDReactionQuery;
use Politizr\Model\PLCountryQuery;
use Politizr\Model\PEOperationQuery;

use Politizr\Model\PDDComment;
use Politizr\Model\PDRComment;
use Politizr\Model\PUTrackDD;
use Politizr\Model\PUTrackDR;

use Politizr\FrontBundle\Form\Type\PDDebateType;
use Politizr\FrontBundle\Form\Type\PDReactionType;
use Politizr\FrontBundle\Form\Type\PDDebateLocalizationType;
use Politizr\FrontBundle\Form\Type\PDDCommentType;
use Politizr\FrontBundle\Form\Type\PDRCommentType;

/**
 * Document controller: debates, reactions, comments
 *
 * @author Lionel Bouzonville
 */
class DocumentController extends Controller
{
    /**
     * Common document "check" validity
     * beta
     *
     * @param PDocument
     * @param boolean $online
     * @param boolean $published
     * @param boolean
     */
    private function checkDocument(PDocumentInterface $document = null, $online = true, $published = true)
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
     * beta
     *
     * @param PDocument
     * @param integer $userId
     * @param boolean
     */
    private function checkDocumentEditable(PDocumentInterface $document = null, $userId)
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
    /*                                         DEBATE & REACTION DETAILS                                        */
    /* ######################################################################################################## */

    /**
     * Détail débat
     * beta
     */
    public function debateDetailAction($slug)
    {
        // $logger = $this->get('logger');
        // $logger->info('*** debateDetailAction');
        // $logger->info('$slug = '.print_r($slug, true));

        $debate = PDDebateQuery::create()->filterBySlug($slug)->findOne();
        $this->checkDocument($debate);

        $debate->setNbViews($debate->getNbViews() + 1);
        $debate->save();

        // Global comment form
        $formType = new PDDCommentType();
        $comment = new PDDComment();
        $comment->setParagraphNo(0);
        $formComment = $this->createForm($formType, $comment);

        // Tracking
        $visitor = $this->getUser();
        if ($visitor) {
            $uTrackd = new PUTrackDD();

            $uTrackd->setPUserId($visitor->getId());
            $uTrackd->setPDDebateId($debate->getId());

            $uTrackd->save();
        }

        // Cut text if user not logged and content setted as not public
        $private = $this->get('politizr.tools.global')->isPrivateMode($visitor, $debate, $this->getParameter('private_mode'), $this->getParameter('public_user_ids'));
        $description = $debate->getDescription();
        if ($private) {
            $description = $this->get('politizr.tools.global')->truncate($description, 800, ['html' => true]);
        }

        // Paragraphs explode
        $utilsManager = $this->get('politizr.tools.global');
        $paragraphs = $utilsManager->explodeParagraphs($description);


        // Debate's reactions
        $reactions = $debate->getChildrenReactions(true, true);

        // Debate's similar debates
        $similars = PDDebateQuery::create()
            ->filterById($debate->getId(), \Criteria::NOT_EQUAL)
            ->usePDDTaggedTQuery()
                ->filterByPTag($debate->getTags([TagConstants::TAG_TYPE_THEME, TagConstants::TAG_TYPE_FAMILY]), \Criteria::IN)
            ->endUse()
            ->distinct()
            ->filterByOnline(true)
            ->filterByPublished(true)
            ->limit(ListingConstants::LISTING_DEBATE_SIMILARS)
            ->orderByNotePos('desc')
            ->orderByNoteNeg('asc')
            ->find();

        return $this->render('PolitizrFrontBundle:Debate:detail.html.twig', array(
            'private' => $private,
            'debate' => $debate,
            'formComment' => $formComment->createView(),
            'paragraphs' => $paragraphs,
            'reactions' => $reactions,
            'similars' => $similars,
        ));
    }


    /**
     * Détail réaction
     * beta
     */
    public function reactionDetailAction($slug)
    {
        // $logger = $this->get('logger');
        // $logger->info('*** reactionDetailAction');
        // $logger->info('$slug = '.print_r($slug, true));

        $reaction = PDReactionQuery::create()->filterBySlug($slug)->findOne();
        $this->checkDocument($reaction);

        $debate = $reaction ->getDebate();
        $this->checkDocument($debate, false, false);

        $reaction->setNbViews($reaction->getNbViews() + 1);
        $reaction->save();

        // Global comment form
        $formType = new PDRCommentType();
        $comment = new PDRComment();
        $comment->setParagraphNo(0);
        $formComment = $this->createForm($formType, $comment);

        // Tracking
        $visitor = $this->getUser();
        if ($visitor) {
            $uTrackr = new PUTrackDR();

            $uTrackr->setPUserId($visitor->getId());
            $uTrackr->setPDReactionId($reaction->getId());

            $uTrackr->save();
        }

        // Cut text if user not logged
        $private = $this->get('politizr.tools.global')->isPrivateMode($visitor, $reaction, $this->getParameter('private_mode'), $this->getParameter('public_user_ids'));

        $description = $reaction->getDescription();
        if ($private) {
            $description = $this->get('politizr.tools.global')->truncate($description, 800, ['html' => true]);
        }

        // Paragraphs explode
        $utilsManager = $this->get('politizr.tools.global');
        $paragraphs = $utilsManager->explodeParagraphs($description);

        // Reaction's reactions
        $reactions = $reaction->getChildrenReactions(true, true);

        // Menu elements
        $parentReaction = $reaction->getParentReaction(true, true);

        $queryAncestors = PDReactionQuery::create()
            ->filterByTreeLevel(0, \Criteria::NOT_EQUAL)    // Exclusion du root node
            ->orderByTreeLevel('asc')
        ;
        // + exclusion parent immédiat s'il existe
        if ($parentReaction) {
            $queryAncestors =  $queryAncestors->filterById($parentReaction->getId(), \Criteria::NOT_EQUAL);
        }

        $ancestors = $reaction->getAncestors($queryAncestors);

        $querySiblings = PDReactionQuery::create()
            ->filterByTreeLevel(0, \Criteria::NOT_EQUAL)    // Exclusion du root node
            ->orderByTreeLevel('asc')
        ;
        $siblings = $reaction->getSiblings(true, $querySiblings);

        // Find current reaction slide number
        $currentSlide = 0;
        if (count($siblings) > 3) {
            $position = 1;
            foreach ($siblings as $sibling) {
                if ($sibling->getId() == $reaction->getId()) {
                    break;
                }
                $position++;
            }
            $currentSlide = ceil($position / 3) - 1;
        }

        // Reaction's similar debates
        $similars = PDDebateQuery::create()
            ->filterById($reaction->getPDDebateId(), \Criteria::NOT_EQUAL)
            ->usePDDTaggedTQuery()
                ->filterByPTag($reaction->getTags([TagConstants::TAG_TYPE_THEME, TagConstants::TAG_TYPE_FAMILY]), \Criteria::IN)
            ->endUse()
            ->distinct()
            ->filterByOnline(true)
            ->filterByPublished(true)
            ->limit(ListingConstants::LISTING_DEBATE_SIMILARS)
            ->orderByNotePos('desc')
            ->orderByNoteNeg('asc')
            ->find();

        return $this->render('PolitizrFrontBundle:Reaction:detail.html.twig', array(
            'private' => $private,
            'debate' => $debate,
            'reaction' => $reaction,
            'formComment' => $formComment->createView(),
            'paragraphs' => $paragraphs,
            'reactions' => $reactions,
            'parentReaction' => $parentReaction,
            'ancestors' => $ancestors,
            'siblings' => $siblings,
            'currentSlide' => $currentSlide,
            'similars' => $similars,
        ));
    }

    /* ######################################################################################################## */
    /*                                             DEBATE EDITION                                               */
    /* ######################################################################################################## */

    /**
     * Create new debate
     * beta
     */
    public function debateNewAction(Request $request)
    {
        // $logger = $this->get('logger');
        // $logger->info('*** debateNewAction');


        $user = $this->getUser();
        if (!$user) {
            throw new InconsistentDataException('Current user not found.');
        }

        // unplug / conflicts w. "op"
        // search "as new" already created debate
        // $debate = PDDebateQuery::create()
        //             ->filterByPUserId($user->getId())
        //             ->where('p_d_debate.created_at = p_d_debate.updated_at')
        //             ->findOne();

        $debate = null;
        if (!$debate) {
            $debate = $this->get('politizr.functional.document')->createDebate();
        }

        return $this->redirect(
            $this->generateUrl(
                'DebateDraftEdit'.$this->get('politizr.tools.global')->computeProfileSuffix(),
                array(
                    'uuid' => $debate->getUuid(),
                    'op' => $request->get('op')
                )
            )
        );
    }

    /**
     * Debate edition
     * beta
     *
     * @param $uuid
     */
    public function debateEditAction(Request $request, $uuid)
    {
        // $logger = $this->get('logger');
        // $logger->info('*** debateEditAction');
        // $logger->info('$uuid = '.print_r($uuid, true));

        $user = $this->getUser();

        $debate = PDDebateQuery::create()->filterByUuid($uuid)->findOne();
        $this->checkDocumentEditable($debate, $user->getId());
        
        $form = $this->createForm(new PDDebateType(), $debate, array('user' => $user));

        $opUuid = $request->get('op');
        $operation = null;
        if ($opUuid) {
            $operation = PEOperationQuery::create()
                ->filterByUuid($opUuid)
                ->findOne();
            if (!$operation) {
                throw new InconsistentDataException(sprintf('Operation %s not found.', $opUuid));
            }
        }

        // get geo debate informations
        $debateLocType = $this->get('politizr.form.type.document_localization');
        $formLocalization = $this->createForm(
            $debateLocType,
            $debate,
            array(
                'data_class' => ObjectTypeConstants::TYPE_DEBATE,
                'user' => $user,
            )
        );

        // op preset tags
        if ($operation) {
            $tags = $operation->getPTags();
            foreach ($tags as $tag) {
                $this->get('politizr.manager.tag')->createDebateTag($debate->getId(), $tag->getId());
            }
        }

        return $this->render('PolitizrFrontBundle:Debate:edit.html.twig', array(
            'debate' => $debate,
            'form' => $form->createView(),
            'formLocalization' => $formLocalization->createView(),
        ));
    }

    /* ######################################################################################################## */
    /*                                           REACTION EDITION                                               */
    /* ######################################################################################################## */

    /**
     * Création d'une nouvelle réaction
     * beta
     */
    public function reactionNewAction($debateUuid, $parentUuid)
    {
        // $logger = $this->get('logger');
        // $logger->info('*** reactionNewAction');

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
     * beta
     *
     * @param $uuid
     */
    public function reactionEditAction($uuid)
    {
        // $logger = $this->get('logger');
        // $logger->info('*** reactionEditAction');
        // $logger->info('$uuid = '.print_r($uuid, true));

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

        // get geo reaction informations
        $reactionLocType = $this->get('politizr.form.type.document_localization');
        $formLocalization = $this->createForm(
            $reactionLocType,
            $reaction,
            array(
                'user' => $user,
                'data_class' => ObjectTypeConstants::TYPE_REACTION
            )
        );

        return $this->render('PolitizrFrontBundle:Reaction:edit.html.twig', array(
            'reaction' => $reaction,
            'parent' => $parent,
            'paragraphs' => $paragraphs,
            'form' => $form->createView(),
            'formLocalization' => $formLocalization->createView(),
        ));
    }

    /* ######################################################################################################## */
    /*                                        DRAFTS & BOOKMARKS                                                */
    /* ######################################################################################################## */

    /**
     * Drafts
     * beta
     */
    public function draftsAction()
    {
        // $logger = $this->get('logger');
        // $logger->info('*** draftsAction');

        return $this->render('PolitizrFrontBundle:Document:drafts.html.twig', array(
        ));
    }

    /**
     * Bookmarks
     * beta
     */
    public function bookmarksAction()
    {
        // $logger = $this->get('logger');
        // $logger->info('*** bookmarksAction');

        return $this->render('PolitizrFrontBundle:Document:bookmarks.html.twig', array(
        ));
    }
}
