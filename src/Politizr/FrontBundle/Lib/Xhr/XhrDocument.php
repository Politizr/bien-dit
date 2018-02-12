<?php
namespace Politizr\FrontBundle\Lib\Xhr;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\EventDispatcher\GenericEvent;

use StudioEcho\Lib\StudioEchoUtils;

use Politizr\Exception\InconsistentDataException;
use Politizr\Exception\BoxErrorException;

use Politizr\Constant\XhrConstants;
use Politizr\Constant\ObjectTypeConstants;
use Politizr\Constant\PathConstants;
use Politizr\Constant\ListingConstants;
use Politizr\Constant\TagConstants;
use Politizr\Constant\LocalizationConstants;
use Politizr\Constant\DocumentConstants;

use Politizr\Model\PDDebate;
use Politizr\Model\PDReaction;
use Politizr\Model\PDDComment;
use Politizr\Model\PDRComment;
use Politizr\Model\PUBookmarkDD;
use Politizr\Model\PUBookmarkDR;

use Politizr\Model\PDDebateQuery;
use Politizr\Model\PDReactionQuery;
use Politizr\Model\PDDCommentQuery;
use Politizr\Model\PDRCommentQuery;
use Politizr\Model\PTagQuery;
use Politizr\Model\PQOrganizationQuery;
use Politizr\Model\PUserQuery;
use Politizr\Model\PUBookmarkDDQuery;
use Politizr\Model\PUBookmarkDRQuery;
use Politizr\Model\PLCountryQuery;

use Politizr\FrontBundle\Form\Type\PDDCommentType;
use Politizr\FrontBundle\Form\Type\PDRCommentType;
use Politizr\FrontBundle\Form\Type\PDDebateType;
use Politizr\FrontBundle\Form\Type\PDocumentTagTypeType;
use Politizr\FrontBundle\Form\Type\PDocumentTagFamilyType;
use Politizr\FrontBundle\Form\Type\PDDebatePhotoInfoType;
use Politizr\FrontBundle\Form\Type\PDReactionType;
use Politizr\FrontBundle\Form\Type\PDReactionPhotoInfoType;
use Politizr\FrontBundle\Form\Type\PDDebateLocalizationType;

/**
 * XHR service for document management.
 * beta
 *
 * @author Lionel Bouzonville
 */
class XhrDocument
{
    private $securityTokenStorage;
    private $securityAuthorizationChecker;
    private $kernel;
    private $session;
    private $eventDispatcher;
    private $templating;
    private $formFactory;
    private $router;
    private $twigEnv;
    private $userManager;
    private $documentManager;
    private $documentService;
    private $userService;
    private $localizationService;
    private $tagService;
    private $facebookService;
    private $circleService;
    private $globalTools;
    private $documentTwigExtension;
    private $documentLocalizationFormType;
    private $logger;

    /**
     *
     * @param @security.token_storage
     * @param @security.authorization_checker
     * @param @kernel
     * @param @session
     * @param @event_dispatcher
     * @param @templating
     * @param @form.factory
     * @param @router
     * @param @twig
     * @param @politizr.manager.user
     * @param @politizr.manager.document
     * @param @politizr.functional.document
     * @param @politizr.functional.user
     * @param @politizr.functional.localization
     * @param @politizr.functional.tag
     * @param @politizr.functional.facebook
     * @param @politizr.functional.circle
     * @param @politizr.tools.global
     * @param @politizr.twig.document
     * @param @politizr.form.type.document_localization
     * @param @logger
     */
    public function __construct(
        $securityTokenStorage,
        $securityAuthorizationChecker,
        $kernel,
        $session,
        $eventDispatcher,
        $templating,
        $formFactory,
        $router,
        $twigEnv,
        $userManager,
        $documentManager,
        $documentService,
        $userService,
        $localizationService,
        $tagService,
        $facebookService,
        $circleService,
        $globalTools,
        $documentTwigExtension,
        $documentLocalizationFormType,
        $logger
    ) {
        $this->securityTokenStorage = $securityTokenStorage;
        $this->securityAuthorizationChecker = $securityAuthorizationChecker;

        $this->kernel = $kernel;
        $this->session = $session;

        $this->eventDispatcher = $eventDispatcher;

        $this->templating = $templating;
        $this->formFactory = $formFactory;
        $this->router = $router;
        $this->twigEnv = $twigEnv;

        $this->userManager = $userManager;
        $this->documentManager = $documentManager;

        $this->documentService = $documentService;
        $this->userService = $userService;
        $this->localizationService = $localizationService;
        $this->tagService = $tagService;
        $this->facebookService = $facebookService;
        $this->circleService = $circleService;

        $this->globalTools = $globalTools;

        $this->documentTwigExtension = $documentTwigExtension;

        $this->documentLocalizationFormType = $documentLocalizationFormType;

        $this->logger = $logger;
    }


    /* ######################################################################################################## */
    /*                                   FOLLOWING, NOTATION, COMMENTS                                          */
    /* ######################################################################################################## */

    /**
     * Follow/Unfollow a debate by current user
     * beta
     */
    public function follow(Request $request)
    {
        // $this->logger->info('*** follow');
        
        // Request arguments
        $uuid = $request->get('uuid');
        // $this->logger->info('$uuid = ' . print_r($uuid, true));
        $way = $request->get('way');
        // $this->logger->info('$way = ' . print_r($way, true));

        // get current user
        $user = $this->securityTokenStorage->getToken()->getUser();
        
        $debate = PDDebateQuery::create()->filterByUuid($uuid)->findOne();
        if ('follow' == $way) {
            $this->userService->followDebate($user, $debate);
        } elseif ('unfollow' == $way) {
            $this->userService->unfollowDebate($user, $debate);
        } else {
            throw new InconsistentDataException(sprintf('Follow\'s way %s not managed', $way));
        }

        // Rendering
        $html = $this->templating->render(
            'PolitizrFrontBundle:Follow:_subscribeAction.html.twig',
            array(
                'subject' => $debate,
            )
        );

        return array(
            'html' => $html,
        );
    }

    /**
     * Follow automaticaly a debate, relative to another interactive action (note, comment), by current user
     * beta
     */
    public function followRelativeDebate(Request $request)
    {
        // $this->logger->info('*** followRelativeDebate');
        
        // Request arguments
        $uuid = $request->get('uuid');
        // $this->logger->info('$uuid = ' . print_r($uuid, true));
        $type = $request->get('type');
        // $this->logger->info('$type = ' . print_r($type, true));

        // get current user
        $user = $this->securityTokenStorage->getToken()->getUser();
        
        if ($type == ObjectTypeConstants::TYPE_DEBATE) {
            $debate = PDDebateQuery::create()->filterByUuid($uuid)->findOne();
        } elseif ($type == ObjectTypeConstants::TYPE_REACTION) {
            $reaction = PDReactionQuery::create()->filterByUuid($uuid)->findOne();
            $debate = $reaction->getPDDebate();
        } elseif ($type == ObjectTypeConstants::TYPE_DEBATE_COMMENT) {
            $comment = PDDCommentQuery::create()->filterByUuid($uuid)->findOne();
            $debate = $comment->getPDDebate();
        } elseif ($type == ObjectTypeConstants::TYPE_REACTION_COMMENT) {
            $comment = PDRCommentQuery::create()->filterByUuid($uuid)->findOne();
            $reaction = $comment->getPDReaction();
            $debate = $reaction->getPDDebate();
        } else {
            throw new InconsistentDataException(sprintf('Type %s not managed', $type));
        }

        if (!$debate) {
            throw new InconsistentDataException(sprintf('Relative Debate %s not found', $uuid));
        }

        // No link if user is author
        if ($debate->getPUserId() == $user->getId()) {
            return true;
        }

        $this->userManager->createUserFollowDebate($user->getId(), $debate->getId());

        // Events
        // upd > no emails events
        $event = new GenericEvent($debate, array('user_id' => $user->getId(),));
        $dispatcher = $this->eventDispatcher->dispatch('r_debate_follow', $event);
        // $event = new GenericEvent($debate, array('author_user_id' => $user->getId(),));
        // $dispatcher = $this->eventDispatcher->dispatch('n_debate_follow', $event);

        return true;
    }

    /**
     * Notation plus/minus of debate, reaction or comment
     * beta
     * @todo refactoring
     */
    public function note(Request $request)
    {
        // $this->logger->info('*** note');
        
        // Request arguments
        $uuid = $request->get('uuid');
        // $this->logger->info('$uuid = ' . print_r($uuid, true));
        $type = $request->get('type');
        // $this->logger->info('$type = ' . print_r($type, true));
        $way = $request->get('way');
        // $this->logger->info('$way = ' . print_r($way, true));

        $user = $this->securityTokenStorage->getToken()->getUser();

        // Function process
        switch($type) {
            case ObjectTypeConstants::TYPE_DEBATE:
                $subject = PDDebateQuery::create()->filterByUuid($uuid)->findOne();
                break;
            case ObjectTypeConstants::TYPE_REACTION:
                $subject = PDReactionQuery::create()->filterByUuid($uuid)->findOne();
                break;
            case ObjectTypeConstants::TYPE_DEBATE_COMMENT:
                $subject = PDDCommentQuery::create()->filterByUuid($uuid)->findOne();
                break;
            case ObjectTypeConstants::TYPE_REACTION_COMMENT:
                $subject = PDRCommentQuery::create()->filterByUuid($uuid)->findOne();
                break;
            default:
                throw new InconsistentDataException(sprintf('Note on type %s not allowed', $type));
        }

        $isAuthorizedToNote = $this->userService->isAuthorizedToNote($user, $subject);
        if (!$isAuthorizedToNote) {
            // throw new InconsistentDataException('You can\'t note this publication.');

            // Rendering
            $html = $this->templating->render(
                'PolitizrFrontBundle:Reputation:_noteAction.html.twig',
                array(
                    'subject' => $subject
                )
            );

            return array(
                'html' => $html
            );
        }

        // update note
        if ('up' == $way) {
            $subject->setNotePos($subject->getNotePos() + 1);
            $subject->save();

            // Events
            $event = new GenericEvent($subject, array('user_id' => $user->getId(),));
            $dispatcher = $this->eventDispatcher->dispatch('r_note_pos', $event);
            $event = new GenericEvent($subject, array('author_user_id' => $user->getId(),));
            $dispatcher = $this->eventDispatcher->dispatch('n_note_pos', $event);
            switch($type) {
                case ObjectTypeConstants::TYPE_DEBATE:
                case ObjectTypeConstants::TYPE_REACTION:
                    $event = new GenericEvent($subject, array('author_user_id' => $user->getId(), 'target_user_id' => $subject->getPUserId()));
                    $dispatcher = $this->eventDispatcher->dispatch('b_document_note_pos', $event);
                    break;
                case ObjectTypeConstants::TYPE_DEBATE_COMMENT:
                case ObjectTypeConstants::TYPE_REACTION_COMMENT:
                    $event = new GenericEvent($subject, array('author_user_id' => $user->getId(), 'target_user_id' => $subject->getPUserId()));
                    $dispatcher = $this->eventDispatcher->dispatch('b_comment_note_pos', $event);
                    break;
            }
        } elseif ('down' == $way) {
            $subject->setNoteNeg($subject->getNoteNeg() + 1);
            $subject->save();

            // Events
            $event = new GenericEvent($subject, array('user_id' => $user->getId(),));
            $dispatcher = $this->eventDispatcher->dispatch('r_note_neg', $event);
            $event = new GenericEvent($subject, array('author_user_id' => $user->getId(),));
            $dispatcher = $this->eventDispatcher->dispatch('n_note_neg', $event);
            switch($type) {
                case ObjectTypeConstants::TYPE_DEBATE:
                case ObjectTypeConstants::TYPE_REACTION:
                    $event = new GenericEvent($subject, array('author_user_id' => $user->getId(), 'target_user_id' => $subject->getPUserId()));
                    $dispatcher = $this->eventDispatcher->dispatch('b_document_note_neg', $event);
                    break;
                case ObjectTypeConstants::TYPE_DEBATE_COMMENT:
                case ObjectTypeConstants::TYPE_REACTION_COMMENT:
                    $event = new GenericEvent($subject, array('author_user_id' => $user->getId(), 'target_user_id' => $subject->getPUserId()));
                    $dispatcher = $this->eventDispatcher->dispatch('b_comment_note_neg', $event);
                    break;
            }
        } else {
            throw new InconsistentDataException(sprintf('Notation\'s way %s not managed', $way));
        }

        // Rendering
        $html = $this->templating->render(
            'PolitizrFrontBundle:Reputation:_noteAction.html.twig',
            array(
                'subject' => $subject
            )
        );

        return array(
            'html' => $html
            );
    }

    /* ######################################################################################################## */
    /*                                              DEBATE EDITION                                              */
    /* ######################################################################################################## */

    /**
     * Debate update
     * beta
     */
    public function debateUpdate(Request $request)
    {
        // $this->logger->info('*** debateUpdate');

        // Request arguments
        $uuid = $request->get('debate')['uuid'];
        // $this->logger->info('$uuid = ' . print_r($uuid, true));

        // get current user
        $user = $this->securityTokenStorage->getToken()->getUser();
        
        $debate = PDDebateQuery::create()->filterByUuid($uuid)->findOne();
        if (!$debate) {
            throw new InconsistentDataException('Debate '.$uuid.' not found.');
        }
        if (!$debate->isOwner($user->getId())) {
            throw new InconsistentDataException('Debate '.$uuid.' is not yours.');
        }
        if ($debate->getPublished()) {
            throw new InconsistentDataException('Debate '.$uuid.' is published and cannot be edited anymore.');
        }

        // Debate
        $formDebate = $this->formFactory->create(new PDDebateType(), $debate);
        $formDebate->bind($request);

        // No validator tests, always save
        $debate = $formDebate->getData();
        $debate->save();

        return true;
    }

    /**
     * Debate publication
     * beta
     */
    public function debatePublish(Request $request)
    {
        // $this->logger->info('*** debatePublish');
        
        // Request arguments
        $uuid = $request->get('uuid');
        // $this->logger->info('$uuid = ' . print_r($uuid, true));

        // get current user
        $user = $this->securityTokenStorage->getToken()->getUser();
        
        $debate = PDDebateQuery::create()->filterByUuid($uuid)->findOne();
        if (!$debate) {
            throw new InconsistentDataException('Debate '.$uuid.' not found.');
        }
        if (!$debate->isOwner($user->getId())) {
            throw new InconsistentDataException('Debate '.$uuid.' is not yours.');
        }
        if ($debate->getPublished()) {
            throw new InconsistentDataException('Debate '.$uuid.' is published and cannot be edited anymore.');
        }

        // Validation
        $errorString = array();
        $valid = $this->globalTools->validateConstraints(
            array(
                'title' => $debate->getTitle(),
                'description' => strip_tags($debate->getDescription()),
                'tags' => $debate->getArrayTags([TagConstants::TAG_TYPE_FAMILY, TagConstants::TAG_TYPE_THEME]),
                'localization' => $debate->getPLocalizations(),
            ),
            $debate->getPublishConstraints(),
            $errorString
        );
        if (!$valid) {
            throw new BoxErrorException($errorString);
        }

        // Publication
        $this->documentManager->publishDebate($debate);
        $this->session->getFlashBag()->add('success', 'Objet publié avec succès.');

        // Events
        $event = new GenericEvent($debate, array('user_id' => $user->getId(),));
        $dispatcher = $this->eventDispatcher->dispatch('r_debate_publish', $event);
        $event = new GenericEvent($debate, array('author_user_id' => $user->getId(),));
        $dispatcher = $this->eventDispatcher->dispatch('n_debate_publish', $event);

        $redirectUrl = $this->router->generate('DebateDetail', array('slug' => $debate->getSlug()));

        return array(
            'redirectUrl' => $redirectUrl,
        );
    }

    /**
     * Debate deletion
     * beta
     */
    public function debateDelete(Request $request)
    {
        // $this->logger->info('*** debateDelete');
        
        // Request arguments
        $uuid = $request->get('uuid');
        // $this->logger->info('$uuid = ' . print_r($uuid, true));

        // get current user
        $user = $this->securityTokenStorage->getToken()->getUser();
        
        $debate = PDDebateQuery::create()->filterByUuid($uuid)->findOne();
        if (!$debate) {
            throw new InconsistentDataException('Debate '.$uuid.' not found.');
        }
        if ($debate->getPublished()) {
            throw new InconsistentDataException('Debate '.$uuid.' is published and cannot be edited anymore.');
        }
        if (!$debate->isOwner($user->getId())) {
            throw new InconsistentDataException('Debate '.$uuid.' is not yours.');
        }

        $this->documentManager->deleteDebate($debate);
        $this->session->getFlashBag()->add('success', 'Objet supprimé avec succès.');

        return array(
            'redirectUrl' => $this->router->generate('Drafts'.$this->globalTools->computeProfileSuffix()),
        );
    }

    /**
     * Debate update tags zone
     * beta
     */
    public function updateDebateTagsZone(Request $request)
    {
        // Request arguments
        $uuid = $request->get('uuid');
        // $this->logger->info('$uuid = ' . print_r($uuid, true));

        // get current user
        $user = $this->securityTokenStorage->getToken()->getUser();
        
        $debate = PDDebateQuery::create()->filterByUuid($uuid)->findOne();
        if (!$debate) {
            throw new InconsistentDataException('Debate '.$uuid.' not found.');
        }
        if ($debate->getPublished()) {
            throw new InconsistentDataException('Debate '.$uuid.' is published and cannot be edited anymore.');
        }
        if (!$debate->isOwner($user->getId())) {
            throw new InconsistentDataException('Debate '.$uuid.' is not yours.');
        }

        $html = $this->templating->render(
            'PolitizrFrontBundle:Tag:_docTags.html.twig',
            array(
                'document' => $debate,
                'displayOnly' => true,
                'tagTypeId' => null,
            )
        );

        return array(
            'html' => $html,
        );
    }

    /* ######################################################################################################## */
    /*                                                  REACTION EDITION                                        */
    /* ######################################################################################################## */

    /**
     * Reaction update
     * beta
     */
    public function reactionUpdate(Request $request)
    {
        // $this->logger->info('*** reactionUpdate');
        
        // Request arguments
        $uuid = $request->get('reaction')['uuid'];
        // $this->logger->info('$uuid = ' . print_r($uuid, true));

        // get current user
        $user = $this->securityTokenStorage->getToken()->getUser();
        
        $reaction = PDReactionQuery::create()->filterByUuid($uuid)->findOne();
        if (!$reaction) {
            throw new InconsistentDataException('Reaction '.$uuid.' not found.');
        }
        if (!$reaction->isOwner($user->getId())) {
            throw new InconsistentDataException('Reaction '.$uuid.' is not yours.');
        }
        if ($reaction->getPublished()) {
            throw new InconsistentDataException('Reaction '.$uuid.' is published and cannot be edited anymore.');
        }

        $form = $this->formFactory->create(new PDReactionType(), $reaction);
        $form->bind($request);

        // No validator tests, always save
        $reaction = $form->getData();
        $reaction->save();

        return true;
    }

    /**
     * Reaction publication
     * beta
     */
    public function reactionPublish(Request $request)
    {
        // $this->logger->info('*** reactionPublish');
        
        // Request arguments
        $uuid = $request->get('uuid');
        // $this->logger->info('$uuid = ' . print_r($uuid, true));

        // get current user
        $user = $this->securityTokenStorage->getToken()->getUser();
        
        $reaction = PDReactionQuery::create()->filterByUuid($uuid)->findOne();
        if (!$reaction) {
            throw new InconsistentDataException('Reaction '.$uuid.' not found.');
        }
        if (!$reaction->isOwner($user->getId())) {
            throw new InconsistentDataException('Reaction '.$uuid.' is not yours.');
        }
        if ($reaction->getPublished()) {
            throw new InconsistentDataException('Reaction '.$uuid.' is published and cannot be edited anymore.');
        }

        // Validation
        $errorString = array();
        $valid = $this->globalTools->validateConstraints(
            array(
                'title' => $reaction->getTitle(),
                'description' => strip_tags($reaction->getDescription()),
                'tags' => $reaction->getArrayTags([TagConstants::TAG_TYPE_FAMILY, TagConstants::TAG_TYPE_THEME]),
                'localization' => $reaction->getPLocalizations(),
            ),
            $reaction->getPublishConstraints(),
            $errorString
        );
        if (!$valid) {
            throw new BoxErrorException($errorString);
        }

        // Publication
        $this->documentManager->publishReaction($reaction);
        $this->session->getFlashBag()->add('success', 'Objet publié avec succès.');

        // Events
        $parentUserId = $reaction->getDebate()->getPUserId();
        if ($reaction->getTreeLevel() > 1) {
            $parentUserId = $reaction->getParent()->getPUserId();
        }
        $event = new GenericEvent($reaction, array('user_id' => $user->getId(),));
        $dispatcher = $this->eventDispatcher->dispatch('r_reaction_publish', $event);
        $event = new GenericEvent($reaction, array('author_user_id' => $user->getId(),));
        $dispatcher = $this->eventDispatcher->dispatch('n_reaction_publish', $event);
        $event = new GenericEvent($reaction, array('author_user_id' => $user->getId(), 'parent_user_id' => $parentUserId));
        $dispatcher = $this->eventDispatcher->dispatch('b_reaction_publish', $event);

        $redirectUrl = $this->router->generate('ReactionDetail', array('slug' => $reaction->getSlug()));

        // Renvoi de l'url de redirection
        return array(
            'redirectUrl' => $redirectUrl,
        );
    }


    /**
     * Reaction deletion
     * beta
     */
    public function reactionDelete(Request $request)
    {
        // $this->logger->info('*** reactionDelete');
        
        // Request arguments
        $uuid = $request->get('uuid');
        // $this->logger->info('$uuid = ' . print_r($uuid, true));

        // get current user
        $user = $this->securityTokenStorage->getToken()->getUser();
        
        $reaction = PDReactionQuery::create()->filterByUuid($uuid)->findOne();
        if (!$reaction) {
            throw new InconsistentDataException('Reaction '.$uuid.' not found.');
        }
        if ($reaction->getPublished()) {
            throw new InconsistentDataException('Reaction '.$uuid.' is published and cannot be edited anymore.');
        }
        if (!$reaction->isOwner($user->getId())) {
            throw new InconsistentDataException('Reaction '.$uuid.' is not yours.');
        }

        $this->documentManager->deleteReaction($reaction);
        $this->session->getFlashBag()->add('success', 'Objet supprimé avec succès.');

        // Renvoi de l'url de redirection
        return array(
            'redirectUrl' => $this->router->generate('Drafts'.$this->globalTools->computeProfileSuffix()),
        );
    }

    /**
     * Debate update tags zone
     * beta
     */
    public function updateReactionTagsZone(Request $request)
    {
        // Request arguments
        $uuid = $request->get('uuid');
        // $this->logger->info('$uuid = ' . print_r($uuid, true));

        // get current user
        $user = $this->securityTokenStorage->getToken()->getUser();
        
        $reaction = PDReactionQuery::create()->filterByUuid($uuid)->findOne();
        if (!$reaction) {
            throw new InconsistentDataException('Reaction '.$uuid.' not found.');
        }
        if ($reaction->getPublished()) {
            throw new InconsistentDataException('Reaction '.$uuid.' is published and cannot be edited anymore.');
        }
        if (!$reaction->isOwner($user->getId())) {
            throw new InconsistentDataException('Reaction '.$uuid.' is not yours.');
        }

        $html = $this->templating->render(
            'PolitizrFrontBundle:Tag:_docTags.html.twig',
            array(
                'document' => $reaction,
                'displayOnly' => true,
                'tagTypeId' => null,
            )
        );

        return array(
            'html' => $html,
        );
    }

    /* ######################################################################################################## */
    /*                                 DEBATE & REACTION COMMON EDITION FUNCTIONS                               */
    /* ######################################################################################################## */

    /**
     * Document attributes update
     * beta
     */
    public function documentAttrUpdate(Request $request)
    {
        // $this->logger->info('*** debateAttrUpdate');

        // Request arguments
        $uuid = $request->get('document_localization')['uuid'];
        // $this->logger->info('$uuid = ' . print_r($uuid, true));
        $type = $request->get('document_localization')['type'];
        // $this->logger->info('$type = ' . print_r($type, true));

        // get current user
        $user = $this->securityTokenStorage->getToken()->getUser();
        
        // get current document
        if ($type == ObjectTypeConstants::TYPE_DEBATE) {
            $document = PDDebateQuery::create()->filterByUuid($uuid)->findOne();
        } elseif ($type == ObjectTypeConstants::TYPE_REACTION) {
            $document = PDReactionQuery::create()->filterByUuid($uuid)->findOne();
        } else {
            throw new InconsistentDataException('Document '.$type.' unknown.');
        }

        if (!$document) {
            throw new InconsistentDataException('Debate '.$uuid.' not found.');
        }
        if (!$document->isOwner($user->getId())) {
            throw new InconsistentDataException('Debate '.$uuid.' is not yours.');
        }
        if ($document->getPublished()) {
            throw new InconsistentDataException('Debate '.$uuid.' is published and cannot be edited anymore.');
        }

        // Document's localization
        $options = array(
                'data_class' => $type,
                'user' => $user,
        );
        if ($document->getPCTopicId()) {
            $this->circleService->updateDocumentLocalizationTypeOptions($document->getPCTopic(), $options);
        }
        $formLocalization = $this->formFactory->create(
            $this->documentLocalizationFormType,
            $document,
            $options
        );
        $formLocalization->bind($request);
        $document = $formLocalization->getData();
        $document->save();

        // Document's tags type
        $formTagType = $this->formFactory->create(
            new PDocumentTagTypeType(), 
            null, 
            array('elected_mode' => $user->getQualified())
        );
        $formTagType->bind($request);

        $tags = $formTagType->getData()['p_tags'];
        if ($type == ObjectTypeConstants::TYPE_DEBATE) {
            $this->tagService->updateDebateTags($document, $tags, TagConstants::TAG_TYPE_TYPE);
        } elseif ($type == ObjectTypeConstants::TYPE_REACTION) {
            $this->tagService->updateReactionTags($document, $tags, TagConstants::TAG_TYPE_TYPE);
        } else {
            throw new InconsistentDataException('Document '.$type.' unknown.');
        }

        // Document's tags family
        $formTagFamily = $this->formFactory->create(
            new PDocumentTagFamilyType(), 
            null
        );
        $formTagFamily->bind($request);

        $tags = $formTagFamily->getData()['p_tags'];
        if ($type == ObjectTypeConstants::TYPE_DEBATE) {
            $this->tagService->updateDebateTags($document, $tags, TagConstants::TAG_TYPE_FAMILY);
        } elseif ($type == ObjectTypeConstants::TYPE_REACTION) {
            $this->tagService->updateReactionTags($document, $tags, TagConstants::TAG_TYPE_FAMILY);
        } else {
            throw new InconsistentDataException('Document '.$type.' unknown.');
        }

        return true;
    }

    /* ######################################################################################################## */
    /*                                                  COMMENTS                                                */
    /* ######################################################################################################## */

    /**
     * Create a new comment
     * code beta
     */
    public function commentNew(Request $request)
    {
        // $this->logger->info('*** commentNew');
        
        // Request arguments
        $type = $request->get('comment')['type'];
        // $this->logger->info('$type = ' . print_r($type, true));
        $uuid = $request->get('uuid');
        // $this->logger->info('$uuid = ' . print_r($uuid, true));

        // get current user
        $user = $this->securityTokenStorage->getToken()->getUser();
        
        switch ($type) {
            case ObjectTypeConstants::TYPE_DEBATE_COMMENT:
                $comment = new PDDComment();
                $document = PDDebateQuery::create()->filterByUuid($uuid)->findOne();
                $comment->setPDDebateId($document->getId());
                $comment->setOnline(true);
                $comment->setPUserId($user->getId());

                $commentNew = new PDDComment();
                $formType = new PDDCommentType();
                break;
            case ObjectTypeConstants::TYPE_REACTION_COMMENT:
                $comment = new PDRComment();
                $document = PDReactionQuery::create()->filterByUuid($uuid)->findOne();
                $comment->setOnline(true);
                $comment->setPUserId($user->getId());
                $comment->setPDReactionId($document->getId());

                $commentNew = new PDRComment();
                $formType = new PDRCommentType();
                break;
            default:
                throw new InconsistentDataException(sprintf('Object type %s not managed', $type));
        }

        $form = $this->formFactory->create($formType, $comment);

        $form->bind($request);
        if ($form->isValid()) {
            // $this->logger->info('*** isValid');
            $comment = $form->getData();

            $comment->save();
        } else {
            // $this->logger->info('*** not valid');
            $errors = StudioEchoUtils::getAjaxFormErrors($form);
            throw new BoxErrorException($errors);
        }

        // Events
        $event = new GenericEvent($comment, array('user_id' => $user->getId(),));
        $dispatcher = $this->eventDispatcher->dispatch('r_comment_publish', $event);
        $event = new GenericEvent($comment, array('author_user_id' => $user->getId(),));
        $dispatcher = $this->eventDispatcher->dispatch('n_comment_publish', $event);
        $event = new GenericEvent($comment, array('author_user_id' => $user->getId()));
        $dispatcher = $this->eventDispatcher->dispatch('b_comment_publish', $event);

        return true;
    }

    /**
     * Display comments
     * code beta
     */
    public function comments(Request $request)
    {
        // $this->logger->info('*** comments');
        
        // Request arguments
        $uuid = $request->get('uuid');
        // $this->logger->info('$uuid = ' . print_r($uuid, true));
        $type = $request->get('type');
        // $this->logger->info('$type = ' . print_r($type, true));
        $noParagraph = $request->get('noParagraph');
        // $this->logger->info('$noParagraph = ' . print_r($noParagraph, true));

        // get current user
        $currentUser = $this->securityTokenStorage->getToken()->getUser();
        if (is_string($currentUser)) {
            $currentUser = null;
        }

        switch ($type) {
            case ObjectTypeConstants::TYPE_DEBATE:
                $document = PDDebateQuery::create()->filterByUuid($uuid)->findOne();
                break;
            case ObjectTypeConstants::TYPE_REACTION:
                $document = PDReactionQuery::create()->filterByUuid($uuid)->findOne();
                break;
            default:
                throw new InconsistentDataException(sprintf('Object type %s not managed', $type));
        }

        $comments = $document->getComments(true, $noParagraph);

        // Rendering
        $paragraphContext = 'global';
        if ($noParagraph > 0) {
            $paragraphContext = 'paragraph';
        }

        $form = null;
        if ($paragraphContext == 'global') {
            $reason = $this->userService->isAuthorizedToPublishComment($currentUser, $document, true);
            $form = $this->templating->render(
                'PolitizrFrontBundle:Comment:_comment.html.twig',
                array(
                    'document' => $document,
                    'reason' => $reason,
                    'noParagraph' => $noParagraph,
                )
            );
        }

        $list = $this->templating->render(
            'PolitizrFrontBundle:Comment:_list.html.twig',
            array(
                'paragraphContext' => $paragraphContext,
                'document' => $document,
                'comments' => $comments,
                'noParagraph' => $noParagraph,
            )
        );
        $counter = $this->templating->render(
            'PolitizrFrontBundle:Comment:_counter.html.twig',
            array(
                'document' => $document,
                'noParagraph' => $noParagraph,
                'active' => true,
                'paragraphContext' => $paragraphContext,
            )
        );

        return array(
            'list' => $list,
            'counter' => $counter,
            'form' => $form,
        );
    }

    /* ######################################################################################################## */
    /*                                            DRAFTS & BOOKMARKS                                            */
    /* ######################################################################################################## */

    /**
     * User's drafts
     * beta
     */
    public function myDraftsPaginated(Request $request)
    {
        // $this->logger->info('*** myDraftsPaginated');

        // Request arguments
        $offset = $request->get('offset');
        // $this->logger->info('$offset = ' . print_r($offset, true));

        // get current user
        $user = $this->securityTokenStorage->getToken()->getUser();

        // get drafts
        $documents = $this->documentService->getMyDraftsPaginatedListing($user->getId(), $offset, ListingConstants::LISTING_CLASSIC_PAGINATION);

        // @todo create function for code above
        $moreResults = false;
        if (sizeof($documents) == ListingConstants::LISTING_CLASSIC_PAGINATION) {
            $moreResults = true;
        }

        if ($offset == 0 && count($documents) == 0) {
            $html = $this->templating->render(
                'PolitizrFrontBundle:PaginatedList:_noResult.html.twig'
            );
        } else {
            $html = $this->templating->render(
                'PolitizrFrontBundle:PaginatedList:_drafts.html.twig',
                array(
                    'documents' => $documents,
                    'offset' => intval($offset) + ListingConstants::LISTING_CLASSIC_PAGINATION,
                    'moreResults' => $moreResults,
                    'jsFunctionKey' => XhrConstants::JS_KEY_LISTING_DOCUMENTS_BY_USER_DRAFTS
                )
            );
        }

        return array(
            'html' => $html,
        );
    }

    /**
     * User's bookmarks
     * beta
     */
    public function myBookmarksPaginated(Request $request)
    {
        // $this->logger->info('*** myBookmarksPaginated');

        // Request arguments
        $offset = $request->get('offset');
        // $this->logger->info('$offset = ' . print_r($offset, true));

        // get current user
        $user = $this->securityTokenStorage->getToken()->getUser();

        // get drafts
        $documents = $this->documentService->getMyBookmarksPaginatedListing($user->getId(), $offset, ListingConstants::LISTING_CLASSIC_PAGINATION);

        // @todo create function for code above
        $moreResults = false;
        if (sizeof($documents) == ListingConstants::LISTING_CLASSIC_PAGINATION) {
            $moreResults = true;
        }

        if ($offset == 0 && count($documents) == 0) {
            $html = $this->templating->render(
                'PolitizrFrontBundle:PaginatedList:_noResult.html.twig'
            );
        } else {
            $html = $this->templating->render(
                'PolitizrFrontBundle:PaginatedList:_documents.html.twig',
                array(
                    'documents' => $documents,
                    'offset' => intval($offset) + ListingConstants::LISTING_CLASSIC_PAGINATION,
                    'moreResults' => $moreResults,
                    'jsFunctionKey' => XhrConstants::JS_KEY_LISTING_DOCUMENTS_BY_USER_BOOKMARKS
                )
            );
        }

        return array(
            'html' => $html,
        );
    }

    /* ######################################################################################################## */
    /*                                          LISTING                                                         */
    /* ######################################################################################################## */

    /**
     * Most popular documents
     * code beta
     */
    public function topDocuments(Request $request)
    {
        // $this->logger->info('*** topDocuments');
        
        // Request arguments
        $filters = $request->get('documentFilterDate');
        // $this->logger->info('$filters = ' . print_r($filters, true));

        // @todo dynamic filters implementation
        $documents = $this->documentService->getTopDocumentsBestNote(
            ListingConstants::LISTING_TOP_DOCUMENTS_LIMIT
        );

        $html = $this->templating->render(
            'PolitizrFrontBundle:Document:_sidebarList.html.twig',
            array(
                'documents' => $documents
            )
        );

        return array(
            'html' => $html,
        );
    }

    /**
     * Suggestion documents
     * code beta
     */
    public function suggestionDocuments(Request $request)
    {
        // $this->logger->info('*** suggestionDocuments');
        
        // get current user
        $user = $this->securityTokenStorage->getToken()->getUser();
        
        $documents = $this->documentService->getUserDocumentsSuggestion($user->getId(), ListingConstants::LISTING_SUGGESTION_DOCUMENTS_LIMIT);
        // $documents = $this->documentService->getDocumentsLastPublished(ListingConstants::LISTING_SUGGESTION_DOCUMENTS_LIMIT);

        $html = $this->templating->render(
            'PolitizrFrontBundle:Document:_sliderSuggestions.html.twig',
            array(
                'documents' => $documents
            )
        );

        return array(
            'html' => $html,
        );
    }

    /**
     * Most recommended documents nav (prev/next computing)
     * code beta
     */
    public function documentsByRecommendNav(Request $request)
    {
        // $this->logger->info('*** documentsByRecommendNav');
        
        // Request arguments
        $numMonth = $request->get('month');
        // $this->logger->info('$numMonth = ' . print_r($numMonth, true));
        $year = $request->get('year');
        // $this->logger->info('$year = ' . print_r($year, true));

        $now = new \DateTime();
        $search = new \DateTime();
        $search->setDate($year, $numMonth, 1);

        if ($search > $now) {
            throw new InconsistentDataException('Cannot recommend with future date');
        }

        $month = $this->globalTools->getLabelFromMonthNum($numMonth);

        // next / prev
        $search->modify('-1 month');
        $prevNumMonth = $search->format('n');
        $prevMonth = $this->globalTools->getLabelFromMonthNum($prevNumMonth);
        $prevYear = $search->format('Y');
        $prevLink = $this->router->generate('ListingByRecommendMonthYear', array('month' => $prevMonth, 'year' => $prevYear));

        $search->modify('+2 month');
        $nextLink = null;
        $nextNumMonth = null;
        $nextYear = null;
        if ($search <= $now) {
            $nextNumMonth = $search->format('n');
            $nextMonth = $this->globalTools->getLabelFromMonthNum($nextNumMonth);
            $nextYear = $search->format('Y');
            $nextLink = $this->router->generate('ListingByRecommendMonthYear', array('month' => $nextMonth, 'year' => $nextYear));
        }

        $html = $this->templating->render(
            'PolitizrFrontBundle:Document:listingByRecommendNav.html.twig',
            array(
                'month' => $month,
                'numMonth' => $numMonth,
                'year' => $year,
                'prevLink' => $prevLink,
                'nextLink' => $nextLink,
                'prevNumMonth' => $prevNumMonth,
                'prevYear' => $prevYear,
                'nextNumMonth' => $nextNumMonth,
                'nextYear' => $nextYear,
            )
        );

        return array(
            'html' => $html,
            'month' => $month,
            'numMonth' => $numMonth,
            'year' => $year,
        );
    }

    /**
     * Most recommended documents
     * code beta
     */
    public function documentsByRecommend(Request $request)
    {
        // $this->logger->info('*** documentsByRecommend');
        
        // Request arguments
        $offset = $request->get('offset');
        // $this->logger->info('$offset = ' . print_r($offset, true));
        $month = $request->get('month');
        // $this->logger->info('$month = ' . print_r($month, true));
        $year = $request->get('year');
        // $this->logger->info('$year = ' . print_r($year, true));

        $documents = $this->documentService->getDocumentsByRecommendPaginated(
            $month,
            $year,
            $offset,
            ListingConstants::LISTING_CLASSIC_PAGINATION
        );

        // @todo create function for code above
        $moreResults = false;
        if (sizeof($documents) == ListingConstants::LISTING_CLASSIC_PAGINATION) {
            $moreResults = true;
        }

        if ($offset == 0 && count($documents) == 0) {
            $html = $this->templating->render(
                'PolitizrFrontBundle:PaginatedList:_noResult.html.twig'
            );
        } else {
            $html = $this->templating->render(
                'PolitizrFrontBundle:PaginatedList:_documents.html.twig',
                array(
                    'documents' => $documents,
                    'offset' => intval($offset) + ListingConstants::LISTING_CLASSIC_PAGINATION,
                    'moreResults' => $moreResults,
                    'jsFunctionKey' => XhrConstants::JS_KEY_LISTING_DOCUMENTS_BY_RECOMMEND
                )
            );
        }

        return array(
            'html' => $html,
        );
    }

    /**
     * Documents by tag
     * code beta
     */
    public function documentsByTag(Request $request)
    {
        // $this->logger->info('*** documentsByTag');
        
        // Request arguments
        $uuid = $request->get('uuid');
        // $this->logger->info('$uuid = ' . print_r($uuid, true));
        $orderBy = $request->get('orderBy');
        // $this->logger->info('$orderBy = ' . print_r($orderBy, true));
        $offset = $request->get('offset');
        // $this->logger->info('$offset = ' . print_r($offset, true));

        // Retrieve subject
        $tag = PTagQuery::create()->filterByUuid($uuid)->findOne();
        if (!$tag) {
            throw new InconsistentDataException('Tag '.$uuid.' not found.');
        }

        $tagIds = array();
        $tagIds[] = $tag->getId();

        // get current user
        $currentUser = $this->securityTokenStorage->getToken()->getUser();
        if (is_string($currentUser)) {
            $currentUser = null;
        }

        $documents = $this->documentService->getDocumentsByTagsPaginated(
            $tagIds,
            $currentUser?$currentUser->getId():null,
            $orderBy,
            $offset,
            ListingConstants::LISTING_CLASSIC_PAGINATION
        );

        // @todo create function for code above
        $moreResults = false;
        if (sizeof($documents) == ListingConstants::LISTING_CLASSIC_PAGINATION) {
            $moreResults = true;
        }

        if ($offset == 0 && count($documents) == 0) {
            $html = $this->templating->render(
                'PolitizrFrontBundle:PaginatedList:_noResult.html.twig'
            );
        } else {
            $html = $this->templating->render(
                'PolitizrFrontBundle:PaginatedList:_documents.html.twig',
                array(
                    'uuid' => $uuid,
                    'documents' => $documents,
                    'offset' => intval($offset) + ListingConstants::LISTING_CLASSIC_PAGINATION,
                    'moreResults' => $moreResults,
                    'jsFunctionKey' => XhrConstants::JS_KEY_LISTING_DOCUMENTS_BY_TAG
                )
            );
        }

        return array(
            'html' => $html,
        );
    }

    /**
     * Documents tabs by organization
     * code beta
     */
    public function documentTabsByOrganization(Request $request)
    {
        // $this->logger->info('*** documentTabsByOrganization');
        
        // Request arguments
        $uuid = $request->get('uuid');
        // $this->logger->info('$uuid = ' . print_r($uuid, true));

        // Retrieve subject
        $organization = PQOrganizationQuery::create()->filterByUuid($uuid)->findOne();
        if (!$organization) {
            throw new InconsistentDataException('Organization '.$uuid.' not found.');
        }

        $html = $this->templating->render(
            'PolitizrFrontBundle:Document:_documentTabsByOrganization.html.twig',
            array(
                'organization' => $organization,
            )
        );

        return array(
            'html' => $html,
        );
    }

    /**
     * Publications by organization
     * code beta
     */
    public function publicationsByOrganization(Request $request)
    {
        // $this->logger->info('*** publicationsByOrganization');
        
        // Request arguments
        $uuid = $request->get('uuid');
        // $this->logger->info('$uuid = ' . print_r($uuid, true));
        $orderBy = $request->get('orderBy');
        // $this->logger->info('$orderBy = ' . print_r($orderBy, true));
        $offset = $request->get('offset');
        // $this->logger->info('$offset = ' . print_r($offset, true));

        // Retrieve subject
        $organization = PQOrganizationQuery::create()->filterByUuid($uuid)->findOne();
        if (!$organization) {
            throw new InconsistentDataException('Organization '.$uuid.' not found.');
        }

        $publications = $this->documentService->getPublicationsByOrganizationPaginated(
            $organization->getId(),
            $orderBy,
            $offset,
            ListingConstants::LISTING_CLASSIC_PAGINATION
        );

        // @todo create function for code above
        $moreResults = false;
        if (sizeof($publications) == ListingConstants::LISTING_CLASSIC_PAGINATION) {
            $moreResults = true;
        }

        if ($offset == 0 && count($publications) == 0) {
            $html = $this->templating->render(
                'PolitizrFrontBundle:PaginatedList:_noResult.html.twig'
            );
        } else {
            $html = $this->templating->render(
                'PolitizrFrontBundle:PaginatedList:_publications.html.twig',
                array(
                    'uuid' => $uuid,
                    'publications' => $publications,
                    'offset' => intval($offset) + ListingConstants::LISTING_CLASSIC_PAGINATION,
                    'moreResults' => $moreResults,
                    'jsFunctionKey' => XhrConstants::JS_KEY_LISTING_DOCUMENTS_BY_ORGANIZATION
                )
            );
        }

        return array(
            'html' => $html,
        );
    }

    /**
     * Publications by user
     * code beta
     */
    public function publicationsByUser(Request $request)
    {
        // $this->logger->info('*** publicationsByUser');
        
        // Request arguments
        $uuid = $request->get('uuid');
        $this->logger->info('$uuid = ' . print_r($uuid, true));
        $orderBy = $request->get('orderBy');
        $this->logger->info('$orderBy = ' . print_r($orderBy, true));
        $tagUuid = $request->get('tagUuid');
        $this->logger->info('$tagUuid = ' . print_r($tagUuid, true));
        $offset = $request->get('offset');
        $this->logger->info('$offset = ' . print_r($offset, true));

        $user = PUserQuery::create()->filterByUuid($uuid)->findOne();
        if (!$user) {
            throw new InconsistentDataException(sprintf('User %s not found', $uuid));
        }
        $tagId = null;
        if ($tagUuid) {
            $tag = PTagQuery::create()->filterByUuid($tagUuid)->findOne();
            if (!$tag) {
                throw new InconsistentDataException(sprintf('Tag %s not found', $tagUuid));
            }
            $tagId = $tag->getId();
        }

        // get current user
        $currentUser = $this->securityTokenStorage->getToken()->getUser();
        if (is_string($currentUser)) {
            $currentUser = null;
        }

        // get publications
        $publications = $this->documentService->getUserPublicationsPaginatedListing(
            $user->getId(),
            $currentUser?$currentUser->getId():null,
            $orderBy,
            $tagId,
            $offset,
            ListingConstants::LISTING_CLASSIC_PAGINATION
        );

        // @todo create function for code above
        $moreResults = false;
        if (sizeof($publications) == ListingConstants::LISTING_CLASSIC_PAGINATION) {
            $moreResults = true;
        }

        if ($offset == 0 && count($publications) == 0) {
            $html = $this->templating->render(
                'PolitizrFrontBundle:PaginatedList:_noResult.html.twig'
            );
        } else {
            $html = $this->templating->render(
                'PolitizrFrontBundle:PaginatedList:_publications.html.twig',
                array(
                    'uuid' => $uuid,
                    'publications' => $publications,
                    'offset' => intval($offset) + ListingConstants::LISTING_CLASSIC_PAGINATION,
                    'moreResults' => $moreResults,
                    'jsFunctionKey' => XhrConstants::JS_KEY_LISTING_PUBLICATIONS_BY_USER_PUBLICATIONS
                )
            );
        }

        return array(
            'html' => $html,
        );
    }

    /**
     * Filtered publications > reload filters
     * code beta
     */
    public function reloadFilters(Request $request)
    {
        // $this->logger->info('*** reloadFilters');

        // Request arguments
        $filterCategory = $request->get('filterCategory');
        // $this->logger->info('$filterCategory = ' . print_r($filterCategory, true));

        if ($filterCategory == ObjectTypeConstants::CONTEXT_PUBLICATION) {
            $template = '_publicationsCategory.html.twig';
        } elseif ($filterCategory == ObjectTypeConstants::CONTEXT_USER) {
            $template = '_usersCategory.html.twig';
        } else {
            throw new InconsistentDataException(sprintf('Filter category %s not managed', $filterCategory));
        }

        $html = $this->templating->render(
            'PolitizrFrontBundle:Search\\Filters:'.$template,
            array(
            )
        );

        return array(
            'html' => $html
        );
    }

    /**
     * Filtered publications
     * code beta
     */
    public function publicationsByFilters(Request $request)
    {
        // $this->logger->info('*** publicationsByFilters');
        
        // Request arguments
        $offset = $request->get('offset');
        // $this->logger->info('$offset = ' . print_r($offset, true));
        $geoUuid = $request->get('geoUuid');
        // $this->logger->info('$geoUuid = ' . print_r($geoUuid, true));
        $type = $request->get('type');
        // $this->logger->info('$type = ' . print_r($type, true));
        $filterPublication = $request->get('filterPublication');
        // $this->logger->info('$filterPublication = ' . print_r($filterPublication, true));
        $filterProfile = $request->get('filterProfile');
        // $this->logger->info('$filterProfile = ' . print_r($filterProfile, true));
        $filterActivity = $request->get('filterActivity');
        // $this->logger->info('$filterActivity = ' . print_r($filterActivity, true));
        $filterDate = $request->get('filterDate');
        // $this->logger->info('$filterDate = ' . print_r($filterDate, true));

        // set default values if not set
        // upd > default = all
        // if (empty($geoUuid)) {
        //     $france = PLCountryQuery::create()->findPk(LocalizationConstants::FRANCE_ID);
        //     $geoUuid = $france->getUuid();
        //     $type = LocalizationConstants::TYPE_COUNTRY;
        // }
        if (empty($filterPublication)) {
            $filterPublication = ListingConstants::FILTER_KEYWORD_ALL_PUBLICATIONS;
        }
        if (empty($filterProfile)) {
            $filterProfile = ListingConstants::FILTER_KEYWORD_ALL_USERS;
        }
        if (empty($filterActivity)) {
            $filterActivity = ListingConstants::ORDER_BY_KEYWORD_LAST;
        }
        if (empty($filterDate)) {
            $filterDate = ListingConstants::FILTER_KEYWORD_ALL_DATE;
        }

        // get current user
        $currentUser = $this->securityTokenStorage->getToken()->getUser();
        if (is_string($currentUser)) {
            $currentUser = null;
        }

        $publications = $this->documentService->getPublicationsByFilters(
            $currentUser?$currentUser->getId():null,
            $geoUuid,
            $type,
            null,
            $filterPublication,
            $filterProfile,
            $filterActivity,
            $filterDate,
            $offset,
            ListingConstants::LISTING_CLASSIC_PAGINATION
        );

        // update url w. js
        $localization = $this->localizationService->getPLocalizationFromGeoUuid($geoUuid, $type);
        $url = $this->router->generate('ListingSearchPublications', array('slug' => $localization->getSlug()));

        // @todo create function for code above
        $moreResults = false;
        if (sizeof($publications) == ListingConstants::LISTING_CLASSIC_PAGINATION) {
            $moreResults = true;
        }

        if ($offset == 0 && count($publications) == 0) {
            $html = $this->templating->render(
                'PolitizrFrontBundle:PaginatedList:_noResult.html.twig'
            );
        } else {
            $html = $this->templating->render(
                'PolitizrFrontBundle:PaginatedList:_publications.html.twig',
                array(
                    'publications' => $publications,
                    'offset' => intval($offset) + ListingConstants::LISTING_CLASSIC_PAGINATION,
                    'moreResults' => $moreResults,
                    'jsFunctionKey' => XhrConstants::JS_KEY_LISTING_PUBLICATIONS_BY_FILTERS
                )
            );
        }

        return array(
            'html' => $html,
            'url' => $url
        );
    }

    /* ######################################################################################################## */
    /*                                          DETAIL                                                          */
    /* ######################################################################################################## */

    /**
     * Bookmark/Unbookmark debate / reaction
     * code beta
     */
    public function bookmark(Request $request)
    {
        // $this->logger->info('*** bookmark');
        
        // Request arguments
        $uuid = $request->get('uuid');
        // $this->logger->info('$uuid = ' . print_r($uuid, true));
        $type = $request->get('type');
        // $this->logger->info('$type = ' . print_r($type, true));

        // get current user
        $user = $this->securityTokenStorage->getToken()->getUser();

        if ($type == ObjectTypeConstants::TYPE_DEBATE) {
            $document = PDDebateQuery::create()->filterByUuid($uuid)->findOne();

            $query = PUBookmarkDDQuery::create()
                ->filterByPDDebateId($document->getId())
                ->filterByPUserId($user->getId());

            $puBookmark = $query->findOne();
            if ($puBookmark) {
                // un-bookmark
                $query->filterByPUserId($user->getId())->delete();
            } else {
                // bookmark
                $bookmark = new PUBookmarkDD();
                $bookmark->setPUserId($user->getId());
                $bookmark->setPDDebateId($document->getId());

                $bookmark->save();
            }
        } elseif ($type == ObjectTypeConstants::TYPE_REACTION) {
            $document = PDReactionQuery::create()->filterByUuid($uuid)->findOne();

            $query = PUBookmarkDRQuery::create()
                ->filterByPDReactionId($document->getId())
                ->filterByPUserId($user->getId());
            
            $puBookmark = $query->findOne();
            if ($puBookmark) {
                // un-bookmark
                $query->filterByPUserId($user->getId())->delete();
            } else {
                // bookmark
                $bookmark = new PUBookmarkDR();
                $bookmark->setPUserId($user->getId());
                $bookmark->setPDReactionId($document->getId());

                $bookmark->save();
            }
        } else {
            throw new InconsistentDataException(sprintf('Object type %s not managed', $document->getType()));
        }

        $html = $this->templating->render(
            'PolitizrFrontBundle:Document:_bookmarkBoxDocument.html.twig',
            array(
                'document' => $document,
            )
        );

        return array(
            'html' => $html,
        );
    }

    /**
     * Facebook's document insights
     *
     * @param PDocumentInterface $document
     * @return string
     */
    public function facebookInsights(Request $request)
    {
        // $this->logger->info('*** facebookInsights');

        // Request arguments
        $uuid = $request->get('uuid');
        // $this->logger->info('$uuid = ' . print_r($uuid, true));
        $type = $request->get('type');
        // $this->logger->info('$type = ' . print_r($type, true));

        if ($type == ObjectTypeConstants::TYPE_DEBATE) {
            $document = PDDebateQuery::create()->filterByUuid($uuid)->findOne();
        } elseif ($type == ObjectTypeConstants::TYPE_REACTION) {
            $document = PDReactionQuery::create()->filterByUuid($uuid)->findOne();
        } else {
            throw new InconsistentDataException(sprintf('Object type %s not managed', $document->getType()));
        }

        $fbAdId = $document->getFbAdId();
        if (!$fbAdId) {
            return array(
                'html' => ''
            );
        }

        try {
            $impressions = $this->facebookService->getImpressions($fbAdId);
            $interactions = $this->facebookService->getInteractions($fbAdId);
            $nbEmotions = $this->facebookService->getNbEmotions($fbAdId);
            $nbComments = $this->facebookService->getNbComments($fbAdId);
            $nbShares = $this->facebookService->getNbShares($fbAdId);
        } catch (\Exception $e) {
            $this->logger->error('Exception FB - msg = '.print_r($e->getMessage(), true));
            return array(
                'html' => ''
            );
        }

        // Construction du rendu du tag
        $html = $this->templating->render(
            'PolitizrFrontBundle:Document:_facebookInsights.html.twig',
            array(
                'impressions' => $impressions,
                'interactions' => $interactions,
                'nbEmotions' => $nbEmotions,
                'nbComments' => $nbComments,
                'nbShares' => $nbShares,
            )
        );

        return array(
            'html' => $html,
        );
    }

    /**
     * Facebook comments
     *
     * @param PDocumentInterface $document
     * @return string
     */
    public function facebookComments(Request $request)
    {
        // $this->logger->info('*** facebookComments');

        // Request arguments
        $uuid = $request->get('uuid');
        // $this->logger->info('$uuid = ' . print_r($uuid, true));
        $type = $request->get('type');
        // $this->logger->info('$type = ' . print_r($type, true));

        if ($type == ObjectTypeConstants::TYPE_DEBATE) {
            $document = PDDebateQuery::create()->filterByUuid($uuid)->findOne();
        } elseif ($type == ObjectTypeConstants::TYPE_REACTION) {
            $document = PDReactionQuery::create()->filterByUuid($uuid)->findOne();
        } else {
            throw new InconsistentDataException(sprintf('Object type %s not managed', $document->getType()));
        }

        $fbAdId = $document->getFbAdId();
        if (!$fbAdId) {
            return array(
                'html' => ''
            );
        }

        try {
            $impressions = $this->facebookService->getImpressions($fbAdId);
            $interactions = $this->facebookService->getInteractions($fbAdId);
            $emotions = $this->facebookService->getEmotions($fbAdId);
            $nbComments = $this->facebookService->getNbComments($fbAdId);
            $comments = $this->facebookService->getComments($fbAdId);
            $nbEmotions = $this->facebookService->getNbEmotions($fbAdId);
            $nbShares = $this->facebookService->getNbShares($fbAdId);
        } catch (\Exception $e) {
            return array(
                'html' => ''
            );
        }

        // Construction du rendu du tag
        $html = $this->templating->render(
            'PolitizrFrontBundle:Document:_facebookComments.html.twig',
            array(
                'fbAdId' => $fbAdId,
                'impressions' => $impressions,
                'interactions' => $interactions,
                'emotions' => $emotions,
                'nbComments' => $nbComments,
                'comments' => $comments,
                'nbEmotions' => $nbEmotions,
                'nbShares' => $nbShares,
            )
        );

        return array(
            'html' => $html,
        );
    }


    /**
     * Boost question
     * code beta
     */
    public function boostQuestion(Request $request)
    {
        // $this->logger->info('*** boostQuestion');
        
        // Request arguments
        $uuid = $request->get('uuid');
        // $this->logger->info('$uuid = ' . print_r($uuid, true));
        $type = $request->get('type');
        // $this->logger->info('$type = ' . print_r($type, true));
        $boost = $request->get('boost');
        // $this->logger->info('$boost = ' . print_r($boost, true));

        // get current user
        $user = $this->securityTokenStorage->getToken()->getUser();

        if ($type == ObjectTypeConstants::TYPE_DEBATE) {
            $document = PDDebateQuery::create()->filterByUuid($uuid)->findOne();
        } elseif ($type == ObjectTypeConstants::TYPE_REACTION) {
            $document = PDReactionQuery::create()->filterByUuid($uuid)->findOne();
        } else {
            throw new InconsistentDataException(sprintf('Object type %s not managed', $document->getType()));
        }

        $document->setWantBoost($boost);
        $document->save();

        if ($boost == DocumentConstants::WB_OK) {
            $event = new GenericEvent($document);
            $dispatcher =  $this->eventDispatcher->dispatch('boost_fb_email', $event);
        }

        $html = $this->templating->render(
            'PolitizrFrontBundle:Document:_boostQuestionResponse.html.twig',
            array(
                'boost' => $boost
            )
        );

        return array(
            'html' => $html,
        );
    }
}