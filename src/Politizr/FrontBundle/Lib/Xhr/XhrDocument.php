<?php
namespace Politizr\FrontBundle\Lib\Xhr;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\EventDispatcher\GenericEvent;

use StudioEcho\Lib\StudioEchoUtils;

use Politizr\Exception\InconsistentDataException;
use Politizr\Exception\FormValidationException;

use Politizr\Constant\ObjectTypeConstants;
use Politizr\Constant\PathConstants;

use Politizr\Model\PDDebate;
use Politizr\Model\PDReaction;
use Politizr\Model\PDDComment;
use Politizr\Model\PDRComment;

use Politizr\Model\PDDebateQuery;
use Politizr\Model\PDReactionQuery;
use Politizr\Model\PDDCommentQuery;
use Politizr\Model\PDRCommentQuery;

use Politizr\FrontBundle\Form\Type\PDDCommentType;
use Politizr\FrontBundle\Form\Type\PDRCommentType;
use Politizr\FrontBundle\Form\Type\PDDebateType;
use Politizr\FrontBundle\Form\Type\PDDebatePhotoInfoType;
use Politizr\FrontBundle\Form\Type\PDReactionType;
use Politizr\FrontBundle\Form\Type\PDReactionPhotoInfoType;

/**
 * XHR service for document management.
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
    private $userManager;
    private $documentManager;
    private $globalTools;
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
     * @param @politizr.manager.user
     * @param @politizr.manager.document
     * @param @politizr.tools.global
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
        $userManager,
        $documentManager,
        $globalTools,
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

        $this->userManager = $userManager;
        $this->documentManager = $documentManager;

        $this->globalTools = $globalTools;

        $this->logger = $logger;
    }


    /* ######################################################################################################## */
    /*                                   FOLLOWING, NOTATION, COMMENTS                                          */
    /* ######################################################################################################## */

    /**
     * Follow/Unfollow a debate by current user
     */
    public function follow(Request $request)
    {
        $this->logger->info('*** follow');
        
        // Request arguments
        $id = $request->get('subjectId');
        $this->logger->info('$id = ' . print_r($id, true));
        $way = $request->get('way');
        $this->logger->info('$way = ' . print_r($way, true));

        // Function process
        $user = $this->securityTokenStorage->getToken()->getUser();
        $debate = PDDebateQuery::create()->findPk($id);
        if ('follow' == $way) {
            $this->userManager->createUserFollowDebate($user->getId(), $debate->getId());

            // Events
            $event = new GenericEvent($debate, array('user_id' => $user->getId(),));
            $dispatcher = $this->eventDispatcher->dispatch('r_debate_follow', $event);
            $event = new GenericEvent($debate, array('author_user_id' => $user->getId(),));
            $dispatcher = $this->eventDispatcher->dispatch('n_debate_follow', $event);
        } elseif ('unfollow' == $way) {
            $this->userManager->deleteUserFollowDebate($user->getId(), $debate->getId());

            // Events
            $event = new GenericEvent($debate, array('user_id' => $user->getId(),));
            $dispatcher = $this->eventDispatcher->dispatch('r_debate_unfollow', $event);
        } else {
            throw new InconsistentDataException(sprintf('Follow\'s way %s not managed', $way));
        }

        // Rendering
        $html = $this->templating->render(
            'PolitizrFrontBundle:Follow:_subscribe.html.twig',
            array(
                'object' => $debate,
                'type' => ObjectTypeConstants::TYPE_DEBATE
            )
        );

        return array(
            'html' => $html,
            );
    }


    /**
     * Notation plus/minus of debate, comment or user
     * @todo refactoring
     */
    public function note(Request $request)
    {
        $this->logger->info('*** note');
        
        // Request arguments
        $id = $request->get('subjectId');
        $this->logger->info('$id = ' . print_r($id, true));
        $type = $request->get('type');
        $this->logger->info('$type = ' . print_r($type, true));
        $way = $request->get('way');
        $this->logger->info('$way = ' . print_r($way, true));

        $user = $this->securityTokenStorage->getToken()->getUser();

        // Function process
        switch($type) {
            case ObjectTypeConstants::TYPE_DEBATE:
                $object = PDDebateQuery::create()->findPk($id);
                break;
            case ObjectTypeConstants::TYPE_REACTION:
                $object = PDReactionQuery::create()->findPk($id);
                break;
            case ObjectTypeConstants::TYPE_DEBATE_COMMENT:
                $object = PDDCommentQuery::create()->findPk($id);
                break;
            case ObjectTypeConstants::TYPE_REACTION_COMMENT:
                $object = PDRCommentQuery::create()->findPk($id);
                break;
        }

        // MAJ note
        if ('up' == $way) {
            $object->setNotePos($object->getNotePos() + 1);
            $object->save();

            // Events
            $event = new GenericEvent($object, array('user_id' => $user->getId(),));
            $dispatcher = $this->eventDispatcher->dispatch('r_note_pos', $event);
            $event = new GenericEvent($object, array('author_user_id' => $user->getId(),));
            $dispatcher = $this->eventDispatcher->dispatch('n_note_pos', $event);
            switch($type) {
                case ObjectTypeConstants::TYPE_DEBATE:
                case ObjectTypeConstants::TYPE_REACTION:
                    $event = new GenericEvent($object, array('author_user_id' => $user->getId(), 'target_user_id' => $object->getPUserId()));
                    $dispatcher = $this->eventDispatcher->dispatch('b_document_note_pos', $event);
                    break;
                case ObjectTypeConstants::TYPE_DEBATE_COMMENT:
                case ObjectTypeConstants::TYPE_REACTION_COMMENT:
                    $event = new GenericEvent($object, array('author_user_id' => $user->getId(), 'target_user_id' => $object->getPUserId()));
                    $dispatcher = $this->eventDispatcher->dispatch('b_comment_note_pos', $event);
                    break;
            }
        } elseif ('down' == $way) {
            $object->setNoteNeg($object->getNoteNeg() + 1);
            $object->save();

            // Events
            $event = new GenericEvent($object, array('user_id' => $user->getId(),));
            $dispatcher = $this->eventDispatcher->dispatch('r_note_neg', $event);
            $event = new GenericEvent($object, array('author_user_id' => $user->getId(),));
            $dispatcher = $this->eventDispatcher->dispatch('n_note_neg', $event);
            switch($type) {
                case ObjectTypeConstants::TYPE_DEBATE:
                case ObjectTypeConstants::TYPE_REACTION:
                    $event = new GenericEvent($object, array('author_user_id' => $user->getId(), 'target_user_id' => $object->getPUserId()));
                    $dispatcher = $this->eventDispatcher->dispatch('b_document_note_neg', $event);
                    break;
                case ObjectTypeConstants::TYPE_DEBATE_COMMENT:
                case ObjectTypeConstants::TYPE_REACTION_COMMENT:
                    $event = new GenericEvent($object, array('author_user_id' => $user->getId(), 'target_user_id' => $object->getPUserId()));
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
                'object' => $object,
                'type' => $type,
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
     */
    public function debateUpdate(Request $request)
    {
        $this->logger->info('*** debateUpdate');
        
        // Request arguments
        $id = $request->get('debate')['id'];
        $this->logger->info('$id = ' . print_r($id, true));

        $user = $this->securityTokenStorage->getToken()->getUser();

        // Function process
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

        $form = $this->formFactory->create(new PDDebateType(), $debate);
        $form->bind($request);
        if ($form->isValid()) {
            $debate = $form->getData();
            $debate->save();
        } else {
            $errors = StudioEchoUtils::getAjaxFormErrors($form);
            throw new FormValidationException($errors);
        }

        return true;
    }

    /**
     * Update debate photo info
     */
    public function debatePhotoInfoUpdate(Request $request)
    {
        $this->logger->info('*** debatePhotoInfoUpdate');
        
        // Request arguments
        $id = $request->get('debate_photo_info')['id'];
        $this->logger->info('$id = ' . print_r($id, true));

        // Function process
        $user = $this->securityTokenStorage->getToken()->getUser();
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

        $form = $this->formFactory->create(new PDDebatePhotoInfoType(), $debate);

        // Retrieve actual file name
        $oldFileName = $debate->getFileName();

        $form->bind($request);
        if ($form->isValid()) {
            $debate = $form->getData();
            $debate->save();

            // Remove old file if new upload or deletion has been done
            $fileName = $debate->getFileName();
            if ($fileName != $oldFileName) {
                $path = $this->kernel->getRootDir() . '/../web' . PathConstants::DEBATE_UPLOAD_WEB_PATH;
                if ($oldFileName && $fileExists = file_exists($path . $oldFileName)) {
                    unlink($path . $oldFileName);
                }
            }
        } else {
            $errors = StudioEchoUtils::getAjaxFormErrors($form);
            throw new FormValidationException($errors);
        }

        // Rendering
        $path = 'bundles/politizrfront/images/default_debate.jpg';
        if ($fileName = $debate->getFileName()) {
            $path = PathConstants::DEBATE_UPLOAD_WEB_PATH.$fileName;
        }
        $imageHeader = $this->templating->render(
            'PolitizrFrontBundle:Debate:_imageHeader.html.twig',
            array(
                'withShadow' => $debate->getWithShadow(),
                'title' => $debate->getTitle(),
                'path' => $path,
                'filterName' => 'debate_header',
                'testShadow' => true,
            )
        );

        return array(
            'imageHeader' => $imageHeader,
            'copyright' => $debate->getCopyright(),
            );
    }

    /**
     * Debate publication
     */
    public function debatePublish(Request $request)
    {
        $this->logger->info('*** debatePublish');
        
        // Request arguments
        $id = $request->get('id');
        $this->logger->info('$id = ' . print_r($id, true));

        // Function process
        $user = $this->securityTokenStorage->getToken()->getUser();
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

        $this->documentManager->publishDebate($debate);
        $this->session->getFlashBag()->add('success', 'Objet publié avec succès.');

        // Events
        $event = new GenericEvent($debate, array('user_id' => $user->getId(),));
        $dispatcher = $this->eventDispatcher->dispatch('r_debate_publish', $event);
        $event = new GenericEvent($debate, array('author_user_id' => $user->getId(),));
        $dispatcher = $this->eventDispatcher->dispatch('n_debate_publish', $event);

        return array(
            'redirectUrl' => $this->router->generate('Contribution'.$this->globalTools->computeProfileSuffix()),
        );
    }

    /**
     * Debate deletion
     */
    public function debateDelete(Request $request)
    {
        $this->logger->info('*** debateDelete');
        
        // Request arguments
        $id = $request->get('id');
        $this->logger->info('$id = ' . print_r($id, true));

        // Function process
        $user = $this->securityTokenStorage->getToken()->getUser();
        $debate = PDDebateQuery::create()->findPk($id);
        if (!$debate) {
            throw new InconsistentDataException('Debate n°'.$id.' not found.');
        }
        if ($debate->getPublished()) {
            throw new InconsistentDataException('Debate n°'.$id.' is published and cannot be edited anymore.');
        }
        if (!$debate->isOwner($user->getId())) {
            throw new InconsistentDataException('Debate n°'.$id.' is not yours.');
        }

        $this->documentManager->deleteDebate($debate);
        $this->session->getFlashBag()->add('success', 'Objet supprimé avec succès.');

        return array(
            'redirectUrl' => $this->router->generate('Drafts'.$this->globalTools->computeProfileSuffix()),
        );
    }

    /* ######################################################################################################## */
    /*                                                  REACTION EDITION                                        */
    /* ######################################################################################################## */

    /**
     * Reaction update
     */
    public function reactionUpdate(Request $request)
    {
        $this->logger->info('*** reactionUpdate');
        
        // Request arguments
        $id = $request->get('reaction')['id'];
        $this->logger->info('$id = ' . print_r($id, true));

        // Function process
        $user = $this->securityTokenStorage->getToken()->getUser();
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

        $form = $this->formFactory->create(new PDReactionType(), $reaction);

        $form->bind($request);
        if ($form->isValid()) {
            $reaction = $form->getData();
            $reaction->save();
        } else {
            $errors = StudioEchoUtils::getAjaxFormErrors($form);
            throw new FormValidationException($errors);
        }

        return true;
    }

    /**
     * Update reaction photo info
     */
    public function reactionPhotoInfoUpdate(Request $request)
    {
        $this->logger->info('*** reactionPhotoInfoUpdate');
        
        // Request arguments
        $id = $request->get('reaction_photo_info')['id'];
        $this->logger->info('$id = ' . print_r($id, true));

        // Function process
        $user = $this->securityTokenStorage->getToken()->getUser();
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

        $form = $this->formFactory->create(new PDReactionPhotoInfoType(), $reaction);

        // Retrieve actual file name
        $oldFileName = $reaction->getFileName();

        $form->bind($request);
        if ($form->isValid()) {
            $reaction = $form->getData();
            $reaction->save();

            // Remove old file if new upload or deletion has been done
            $fileName = $reaction->getFileName();
            if ($fileName != $oldFileName) {
                $path = $this->kernel->getRootDir() . '/../web' . PathConstants::REACTION_UPLOAD_WEB_PATH;
                if ($oldFileName && $fileExists = file_exists($path . $oldFileName)) {
                    unlink($path . $oldFileName);
                }
            }
        } else {
            $errors = StudioEchoUtils::getAjaxFormErrors($form);
            throw new FormValidationException($errors);
        }

        // Rendering
        $path = 'bundles/politizrfront/images/default_reaction.jpg';
        if ($fileName = $reaction->getFileName()) {
            $path = PathConstants::REACTION_UPLOAD_WEB_PATH.$fileName;
        }
        $imageHeader = $this->templating->render(
            'PolitizrFrontBundle:Debate:_imageHeader.html.twig',
            array(
                'withShadow' => $reaction->getWithShadow(),
                'title' => $reaction->getTitle(),
                'path' => $path,
                'filterName' => 'debate_header',
                'testShadow' => true,
            )
        );

        return array(
            'imageHeader' => $imageHeader,
            'copyright' => $reaction->getCopyright(),
            );
    }

    /**
     * Reaction publication
     */
    public function reactionPublish(Request $request)
    {
        $this->logger->info('*** reactionPublish');
        
        // Request arguments
        $id = $request->get('id');
        $this->logger->info('$id = ' . print_r($id, true));

        // Function process
        $user = $this->securityTokenStorage->getToken()->getUser();
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

        // Renvoi de l'url de redirection
        return array(
            'redirectUrl' => $this->router->generate('Contribution'.$this->globalTools->computeProfileSuffix()),
        );
    }


    /**
     * Reaction deletion
     */
    public function reactionDelete(Request $request)
    {
        $this->logger->info('*** reactionDelete');
        
        // Request arguments
        $id = $request->get('id');
        $this->logger->info('$id = ' . print_r($id, true));

        // Function process
        $user = $this->securityTokenStorage->getToken()->getUser();
        $reaction = PDReactionQuery::create()->findPk($id);
        if (!$reaction) {
            throw new InconsistentDataException('Reaction n°'.$id.' not found.');
        }
        if ($reaction->getPublished()) {
            throw new InconsistentDataException('Reaction n°'.$id.' is published and cannot be edited anymore.');
        }
        if (!$reaction->isOwner($user->getId())) {
            throw new InconsistentDataException('Reaction n°'.$id.' is not yours.');
        }

        $this->documentManager->deleteReaction($reaction);
        $this->session->getFlashBag()->add('success', 'Objet supprimé avec succès.');

        // Renvoi de l'url de redirection
        return array(
            'redirectUrl' => $this->router->generate('Drafts'.$this->globalTools->computeProfileSuffix()),
        );
    }

    /* ######################################################################################################## */
    /*                                 DEBATE & REACTION COMMON EDITION FUNCTIONS                               */
    /* ######################################################################################################## */

    /**
     * Document's photo upload
     */
    public function documentPhotoUpload(Request $request)
    {
        $this->logger->info('*** documentPhotoUpload');

        // Request arguments
        $id = $request->get('id');
        $this->logger->info(print_r($id, true));
        $type = $request->get('type');
        $this->logger->info(print_r($type, true));

        // Récupération débat courant
        $user = $this->securityTokenStorage->getToken()->getUser();
        switch ($type) {
            case ObjectTypeConstants::TYPE_DEBATE:
                $document = PDDebateQuery::create()->findPk($id);
                $uploadWebPath = PathConstants::DEBATE_UPLOAD_WEB_PATH;
                break;
            case ObjectTypeConstants::TYPE_REACTION:
                $document = PDReactionQuery::create()->findPk($id);
                $uploadWebPath = PathConstants::REACTION_UPLOAD_WEB_PATH;
                break;
            default:
                throw new InconsistentDataException('Object type not managed');
        }

        // Chemin des images
        $path = $this->kernel->getRootDir() . '/../web' . $uploadWebPath;

        // Appel du service d'upload ajax
        $fileName = $this->globalTools->uploadXhrImage(
            $request,
            'fileName',
            $path,
            1024,
            1024
        );

        // Rendering
        $html = $this->templating->render(
            'PolitizrFrontBundle:Debate:_imageHeader.html.twig',
            array(
                'withShadow' => $document->getWithShadow(),
                'path' => $uploadWebPath . $fileName,
                'filterName' => 'debate_header',
                'title' => $document->getTitle(),
                'testShadow' => true,
            )
        );

        return array(
            'fileName' => $fileName,
            'html' => $html,
            );
    }

    /* ######################################################################################################## */
    /*                                                  COMMENTS                                                */
    /* ######################################################################################################## */

    /**
     * Create a new comment
     */
    public function commentNew(Request $request)
    {
        $this->logger->info('*** commentNew');
        
        // Request arguments
        $type = $request->get('comment')['type'];
        $this->logger->info('$type = ' . print_r($type, true));

        // Function process
        $user = $this->securityTokenStorage->getToken()->getUser();
        switch ($type) {
            case ObjectTypeConstants::TYPE_DEBATE_COMMENT:
                $comment = new PDDComment();
                $commentNew = new PDDComment();
                $formType = new PDDCommentType();
                break;
            case ObjectTypeConstants::TYPE_REACTION_COMMENT:
                $comment = new PDRComment();
                $commentNew = new PDRComment();
                $formType = new PDRCommentType();
                break;
            default:
                throw new InconsistentDataException('Object type not managed');
        }

        $form = $this->formFactory->create($formType, $comment);

        $form->bind($request);
        if ($form->isValid()) {
            $this->logger->info('*** isValid');
            $comment = $form->getData();
            $comment->save();
        } else {
            $this->logger->info('*** not valid');
            $errors = StudioEchoUtils::getAjaxFormErrors($form);
            throw new FormValidationException($errors);
        }

        // Get associated object
        $document = $comment->getPDocument();
        $noParagraph = $comment->getParagraphNo();

        // New form creation
        $comments = $document->getComments(true, $noParagraph);

        if ($user) {
            $commentNew->setPUserId($user->getId());
            $commentNew->setPDocumentId($document->getId());
            $commentNew->setParagraphNo($noParagraph);
        }
        $form = $this->formFactory->create($formType, $comment);

        // Events
        $event = new GenericEvent($comment, array('user_id' => $user->getId(),));
        $dispatcher = $this->eventDispatcher->dispatch('r_comment_publish', $event);
        $event = new GenericEvent($comment, array('author_user_id' => $user->getId(),));
        $dispatcher = $this->eventDispatcher->dispatch('n_comment_publish', $event);
        $event = new GenericEvent($comment, array('author_user_id' => $user->getId()));
        $dispatcher = $this->eventDispatcher->dispatch('b_comment_publish', $event);

        // Rendering
        $html = $this->templating->render(
            'PolitizrFrontBundle:Comment:_paragraphComments.html.twig',
            array(
                'document' => $document,
                'comments' => $comments,
                'formComment' => $form->createView(),
            )
        );
        $counter = $this->templating->render(
            'PolitizrFrontBundle:Comment:_counter.html.twig',
            array(
                'document' => $document,
                'paragraphNo' => $noParagraph,
            )
        );

        return array(
            'html' => $html,
            'counter' => $counter,
            );
    }

    /**
     * Display comments & create form comment
     */
    public function comments(Request $request)
    {
        $this->logger->info('*** comments');
        
        // Request arguments
        $id = $request->get('subjectId');
        $this->logger->info('$id = ' . print_r($id, true));
        $type = $request->get('type');
        $this->logger->info('$type = ' . print_r($type, true));
        $noParagraph = $request->get('noParagraph');
        $this->logger->info('$noParagraph = ' . print_r($noParagraph, true));

        // Function process
        $user = $this->securityTokenStorage->getToken()->getUser();
        switch ($type) {
            case ObjectTypeConstants::TYPE_DEBATE:
                $document = PDDebateQuery::create()->findPk($id);
                $comment = new PDDComment();
                $formType = new PDDCommentType();
                break;
            case ObjectTypeConstants::TYPE_REACTION:
                $document = PDReactionQuery::create()->findPk($id);
                $comment = new PDRComment();
                $formType = new PDRCommentType();
                break;
            default:
                throw new InconsistentDataException('Object type not managed');
        }

        $comments = $document->getComments(true, $noParagraph);

        if ($this->securityAuthorizationChecker->isGranted('ROLE_PROFILE_COMPLETED')) {
            $comment->setPUserId($user->getId());
            $comment->setPDocumentId($document->getId());
            $comment->setParagraphNo($noParagraph);
        }
        $formComment = $this->formFactory->create($formType, $comment);

        // Rendering
        $html = $this->templating->render(
            'PolitizrFrontBundle:Comment:_paragraphComments.html.twig',
            array(
                'document' => $document,
                'comments' => $comments,
                'formComment' => $formComment->createView(),
            )
        );
        $counter = $this->templating->render(
            'PolitizrFrontBundle:Comment:_counter.html.twig',
            array(
                'document' => $document,
                'paragraphNo' => $noParagraph,
            )
        );

        return array(
            'html' => $html,
            'counter' => $counter,
            );
    }
}
