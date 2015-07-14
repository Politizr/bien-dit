<?php
namespace Politizr\FrontBundle\Lib\Xhr;

use Symfony\Component\EventDispatcher\GenericEvent;

use StudioEcho\Lib\StudioEchoUtils;

use Politizr\Exception\InconsistentDataException;
use Politizr\Exception\FormValidationException;

use Politizr\Model\PDocumentInterface;
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
    private $sc;

    /**
     *
     */
    public function __construct($serviceContainer)
    {
        $this->sc = $serviceContainer;
    }


    /* ######################################################################################################## */
    /*                                   FOLLOWING, NOTATION, COMMENTS                                          */
    /* ######################################################################################################## */

    /**
     * Follow/Unfollow a debate by current user
     */
    public function follow()
    {
        $logger = $this->sc->get('logger');
        $logger->info('*** follow');
        
        // Retrieve used services
        $request = $this->sc->get('request');
        $securityContext = $this->sc->get('security.context');
        $user = $securityContext->getToken()->getUser();
        $userManager = $this->sc->get('politizr.manager.user');
        $eventDispatcher = $this->sc->get('event_dispatcher');
        $templating = $this->sc->get('templating');

        // Request arguments
        $id = $request->get('subjectId');
        $logger->info('$id = ' . print_r($id, true));
        $way = $request->get('way');
        $logger->info('$way = ' . print_r($way, true));

        // Get debate
        $debate = PDDebateQuery::create()->findPk($id);
        if ('follow' == $way) {
            $userManager->createUserFollowDebate($user->getId(), $debate->getId());

            // Events
            $event = new GenericEvent($debate, array('user_id' => $user->getId(),));
            $dispatcher = $eventDispatcher->dispatch('r_debate_follow', $event);
            $event = new GenericEvent($debate, array('author_user_id' => $user->getId(),));
            $dispatcher = $eventDispatcher->dispatch('n_debate_follow', $event);
        } elseif ('unfollow' == $way) {
            $userManager->deleteUserFollowDebate($user->getId(), $debate->getId());

            // Events
            $event = new GenericEvent($debate, array('user_id' => $user->getId(),));
            $dispatcher = $eventDispatcher->dispatch('r_debate_unfollow', $event);
        } else {
            throw new InconsistentDataException(sprintf('Follow\'s way %s not managed', $way));
        }

        // Rendering
        $html = $templating->render(
            'PolitizrFrontBundle:Follow:_subscribe.html.twig',
            array(
                'object' => $debate,
                'type' => PDocumentInterface::TYPE_DEBATE
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
    public function note()
    {
        $logger = $this->sc->get('logger');
        $logger->info('*** note');
        
        // Retrieve used services
        $request = $this->sc->get('request');
        $securityContext = $this->sc->get('security.context');
        $eventDispatcher = $this->sc->get('event_dispatcher');
        $templating = $this->sc->get('templating');

        // Request arguments
        $id = $request->get('subjectId');
        $logger->info('$id = ' . print_r($id, true));
        $type = $request->get('type');
        $logger->info('$type = ' . print_r($type, true));
        $way = $request->get('way');
        $logger->info('$way = ' . print_r($way, true));

        $user = $securityContext->getToken()->getUser();

        // Récupération objet
        switch($type) {
            case PDocumentInterface::TYPE_DEBATE:
                $object = PDDebateQuery::create()->findPk($id);
                break;
            case PDocumentInterface::TYPE_REACTION:
                $object = PDReactionQuery::create()->findPk($id);
                break;
            case PDocumentInterface::TYPE_DEBATE_COMMENT:
                $object = PDDCommentQuery::create()->findPk($id);
                break;
            case PDocumentInterface::TYPE_REACTION_COMMENT:
                $object = PDRCommentQuery::create()->findPk($id);
                break;
        }

        // MAJ note
        if ('up' == $way) {
            $object->setNotePos($object->getNotePos() + 1);
            $object->save();

            // Events
            $event = new GenericEvent($object, array('user_id' => $user->getId(),));
            $dispatcher = $eventDispatcher->dispatch('r_note_pos', $event);
            $event = new GenericEvent($object, array('author_user_id' => $user->getId(),));
            $dispatcher = $eventDispatcher->dispatch('n_note_pos', $event);
            switch($type) {
                case PDocumentInterface::TYPE_DEBATE:
                case PDocumentInterface::TYPE_REACTION:
                    $event = new GenericEvent($object, array('author_user_id' => $user->getId(), 'target_user_id' => $object->getPUserId()));
                    $dispatcher = $eventDispatcher->dispatch('b_document_note_pos', $event);
                    break;
                case PDocumentInterface::TYPE_DEBATE_COMMENT:
                case PDocumentInterface::TYPE_REACTION_COMMENT:
                    $event = new GenericEvent($object, array('author_user_id' => $user->getId(), 'target_user_id' => $object->getPUserId()));
                    $dispatcher = $eventDispatcher->dispatch('b_comment_note_pos', $event);
                    break;
            }
        } elseif ('down' == $way) {
            $object->setNoteNeg($object->getNoteNeg() + 1);
            $object->save();

            // Events
            $event = new GenericEvent($object, array('user_id' => $user->getId(),));
            $dispatcher = $eventDispatcher->dispatch('r_note_neg', $event);
            $event = new GenericEvent($object, array('author_user_id' => $user->getId(),));
            $dispatcher = $eventDispatcher->dispatch('n_note_neg', $event);
            switch($type) {
                case PDocumentInterface::TYPE_DEBATE:
                case PDocumentInterface::TYPE_REACTION:
                    $event = new GenericEvent($object, array('author_user_id' => $user->getId(), 'target_user_id' => $object->getPUserId()));
                    $dispatcher = $eventDispatcher->dispatch('b_document_note_neg', $event);
                    break;
                case PDocumentInterface::TYPE_DEBATE_COMMENT:
                case PDocumentInterface::TYPE_REACTION_COMMENT:
                    $event = new GenericEvent($object, array('author_user_id' => $user->getId(), 'target_user_id' => $object->getPUserId()));
                    $dispatcher = $eventDispatcher->dispatch('b_comment_note_neg', $event);
                    break;
            }
        } else {
            throw new InconsistentDataException(sprintf('Notation\'s way %s not managed', $way));
        }

        // Rendering
        $html = $templating->render(
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
    public function debateUpdate()
    {
        $logger = $this->sc->get('logger');
        $logger->info('*** debateUpdate');
        
        // Retrieve used services
        $request = $this->sc->get('request');
        $securityContext = $this->sc->get('security.context');
        $formFactory = $this->sc->get('form.factory');

        // Request arguments
        $id = $request->get('debate')['id'];
        $logger->info('$id = ' . print_r($id, true));

        $user = $securityContext->getToken()->getUser();

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

        $form = $formFactory->create(new PDDebateType(), $debate);
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
    public function debatePhotoInfoUpdate()
    {
        $logger = $this->sc->get('logger');
        $logger->info('*** debatePhotoInfoUpdate');
        
        // Retrieve used services
        $request = $this->sc->get('request');
        $securityContext = $this->sc->get('security.context');
        $formFactory = $this->sc->get('form.factory');
        $templating = $this->sc->get('templating');

        // Request arguments
        $id = $request->get('debate_photo_info')['id'];
        $logger->info('$id = ' . print_r($id, true));

        // Function process
        $user = $securityContext->getToken()->getUser();
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

        $form = $formFactory->create(new PDDebatePhotoInfoType(), $debate);

        // Retrieve actual file name
        $oldFileName = $debate->getFileName();

        $form->bind($request);
        if ($form->isValid()) {
            $debate = $form->getData();
            $debate->save();

            // Remove old file if new upload or deletion has been done
            $fileName = $debate->getFileName();
            if ($fileName != $oldFileName) {
                $path = $this->sc->get('kernel')->getRootDir() . '/../web' . PDDebate::UPLOAD_WEB_PATH;
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
            $path = PDDebate::UPLOAD_WEB_PATH.$fileName;
        }
        $imageHeader = $templating->render(
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
    public function debatePublish()
    {
        $logger = $this->sc->get('logger');
        $logger->info('*** debatePublish');
        
        // Retrieve used services
        $request = $this->sc->get('request');
        $securityContext = $this->sc->get('security.context');
        $documentManager = $this->sc->get('politizr.manager.document');
        $eventDispatcher = $this->sc->get('event_dispatcher');
        $session = $this->sc->get('session')->getFlashBag();

        // Request arguments
        $id = $request->get('id');
        $logger->info('$id = ' . print_r($id, true));
        $redirectUrl = $request->get('url');
        $logger->info('$redirectUrl = ' . print_r($redirectUrl, true));

        // Function process
        $user = $securityContext->getToken()->getUser();
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

        $documentManager->publishDebate();
        $session->getFlashBag()->add('success', 'Objet publié avec succès.');

        // Events
        $event = new GenericEvent($debate, array('user_id' => $user->getId(),));
        $dispatcher = $eventDispatcher->dispatch('r_debate_publish', $event);
        $event = new GenericEvent($debate, array('author_user_id' => $user->getId(),));
        $dispatcher = $eventDispatcher->dispatch('n_debate_publish', $event);

        return array(
            'redirectUrl' => $redirectUrl,
            );
    }

    /**
     * Debate deletion
     */
    public function debateDelete()
    {
        $logger = $this->sc->get('logger');
        $logger->info('*** debateDelete');
        
        // Retrieve used services
        $request = $this->sc->get('request');
        $securityContext = $this->sc->get('security.context');
        $documentManager = $this->sc->get('politizr.manager.document');
        $session = $this->sc->get('session')->getFlashBag();

        // Request arguments
        $id = $request->get('id');
        $logger->info('$id = ' . print_r($id, true));
        $redirectUrl = $request->get('url');
        $logger->info('$redirectUrl = ' . print_r($redirectUrl, true));

        // Function process
        $user = $securityContext->getToken()->getUser();
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

        $documentManager->deleteDebate($debate);
        $session->getFlashBag()->add('success', 'Objet supprimé avec succès.');

        return array(
            'redirectUrl' => $redirectUrl,
            );
    }

    /* ######################################################################################################## */
    /*                                                  REACTION EDITION                                        */
    /* ######################################################################################################## */

    /**
     * Reaction update
     */
    public function reactionUpdate()
    {
        $logger = $this->sc->get('logger');
        $logger->info('*** reactionUpdate');
        
        // Retrieve used services
        $request = $this->sc->get('request');
        $securityContext = $this->sc->get('security.context');
        $formFactory = $this->sc->get('form.factory');

        // Request arguments
        $id = $request->get('reaction')['id'];
        $logger->info('$id = ' . print_r($id, true));

        // Function process
        $user = $securityContext->getToken()->getUser();
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

        $form = $formFactory->create(new PDReactionType(), $reaction);

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
    public function reactionPhotoInfoUpdate()
    {
        $logger = $this->sc->get('logger');
        $logger->info('*** reactionPhotoInfoUpdate');
        
        // Retrieve used services
        $request = $this->sc->get('request');
        $securityContext = $this->sc->get('security.context');
        $formFactory = $this->sc->get('form.factory');
        $templating = $this->sc->get('templating');
        $kernel = $this->sc->get('kernel');

        // Request arguments
        $id = $request->get('reaction_photo_info')['id'];
        $logger->info('$id = ' . print_r($id, true));

        // Function process
        $user = $securityContext->getToken()->getUser();
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

        $form = $formFactory->create(new PDReactionPhotoInfoType(), $reaction);

        // Retrieve actual file name
        $oldFileName = $reaction->getFileName();

        $form->bind($request);
        if ($form->isValid()) {
            $reaction = $form->getData();
            $reaction->save();

            // Remove old file if new upload or deletion has been done
            $fileName = $reaction->getFileName();
            if ($fileName != $oldFileName) {
                $path = $kernel->getRootDir() . '/../web' . PDReaction::UPLOAD_WEB_PATH;
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
            $path = PDReaction::UPLOAD_WEB_PATH.$fileName;
        }
        $imageHeader = $templating->render(
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
    public function reactionPublish()
    {
        $logger = $this->sc->get('logger');
        $logger->info('*** reactionPublish');
        
        // Retrieve used services
        $request = $this->sc->get('request');
        $securityContext = $this->sc->get('security.context');
        $documentManager = $this->sc->get('politizr.manager.document');
        $eventDispatcher = $this->sc->get('event_dispatcher');
        $session = $this->sc->get('session');

        // Request arguments
        $id = $request->get('id');
        $logger->info('$id = ' . print_r($id, true));
        $redirectUrl = $request->get('url');
        $logger->info('$redirectUrl = ' . print_r($redirectUrl, true));

        // Function process
        $user = $securityContext->getToken()->getUser();
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

        $documentManager->publishReaction($reaction);
        $session->getFlashBag()->add('success', 'Objet publié avec succès.');

        // Events
        $parentUserId = $reaction->getDebate()->getPUserId();
        if ($reaction->getTreeLevel() > 1) {
            $parentUserId = $reaction->getParent()->getPUserId();
        }
        $event = new GenericEvent($reaction, array('user_id' => $user->getId(),));
        $dispatcher = $eventDispatcher->dispatch('r_reaction_publish', $event);
        $event = new GenericEvent($reaction, array('author_user_id' => $user->getId(),));
        $dispatcher = $eventDispatcher->dispatch('n_reaction_publish', $event);
        $event = new GenericEvent($reaction, array('author_user_id' => $user->getId(), 'parent_user_id' => $parentUserId));
        $dispatcher = $eventDispatcher->dispatch('b_reaction_publish', $event);

        // Renvoi de l'url de redirection
        return array(
            'redirectUrl' => $redirectUrl,
            );
    }


    /**
     * Reaction deletion
     */
    public function reactionDelete()
    {
        $logger = $this->sc->get('logger');
        $logger->info('*** reactionDelete');
        
        // Retrieve used services
        $securityContext = $this->sc->get('security.context');
        $documentManager = $this->sc->get('politizr.manager.document');
        $session = $this->sc->get('session');

        // Request arguments
        $request = $this->sc->get('request');
        $id = $request->get('id');
        $logger->info('$id = ' . print_r($id, true));
        $redirectUrl = $request->get('url');
        $logger->info('$redirectUrl = ' . print_r($redirectUrl, true));

        // Function process
        $user = $securityContext->getToken()->getUser();
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

        $documentManager->deleteReaction($reaction);
        $session->getFlashBag()->add('success', 'Objet supprimé avec succès.');

        // Renvoi de l'url de redirection
        return array(
            'redirectUrl' => $redirectUrl,
            );
    }

    /* ######################################################################################################## */
    /*                                 DEBATE & REACTION COMMON EDITION FUNCTIONS                               */
    /* ######################################################################################################## */

    /**
     * Document's photo upload
     */
    public function documentPhotoUpload()
    {
        $logger = $this->sc->get('logger');
        $logger->info('*** documentPhotoUpload');

        // Retrieve used services
        $securityContext = $this->sc->get('security.context');
        $session = $this->sc->get('session');
        $kernel = $this->sc->get('kernel');
        $request = $this->sc->get('request');
        $utilsManager = $this->sc->get('politizr.utils');
        $templating = $this->sc->get('templating');

        // Request arguments
        $id = $request->get('id');
        $logger->info(print_r($id, true));
        $type = $request->get('type');
        $logger->info(print_r($type, true));

        // Récupération débat courant
        $user = $securityContext->getToken()->getUser();
        switch ($type) {
            case PDocumentInterface::TYPE_DEBATE:
                $document = PDDebateQuery::create()->findPk($id);
                $uploadWebPath = PDDebate::UPLOAD_WEB_PATH;
                break;
            case PDocumentInterface::TYPE_REACTION:
                $document = PDReactionQuery::create()->findPk($id);
                $uploadWebPath = PDReaction::UPLOAD_WEB_PATH;
                break;
            default:
                throw new InconsistentDataException('Object type not managed');
        }

        // Chemin des images
        $path = $kernel->getRootDir() . '/../web' . $uploadWebPath;

        // Appel du service d'upload ajax
        $fileName = $utilsManager->uploadImageAjax(
            'fileName',
            $path,
            1024,
            1024
        );

        // Rendering
        $html = $templating->render(
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
    public function commentNew()
    {
        $logger = $this->sc->get('logger');
        $logger->info('*** commentNew');
        
        // Retrieve used services
        $securityContext = $this->sc->get('security.context');
        $request = $this->sc->get('request');
        $formFactory = $this->sc->get('form.factory');
        $templating = $this->sc->get('templating');

        // Request arguments
        $type = $request->get('comment')['type'];
        $logger->info('$type = ' . print_r($type, true));

        // Function process
        $user = $securityContext->getToken()->getUser();
        switch ($type) {
            case PDocumentInterface::TYPE_DEBATE_COMMENT:
                $comment = new PDDComment();
                $commentNew = new PDDComment();
                $formType = new PDDCommentType();
                break;
            case PDocumentInterface::TYPE_REACTION_COMMENT:
                $comment = new PDRComment();
                $commentNew = new PDRComment();
                $formType = new PDRCommentType();
                break;
            default:
                throw new InconsistentDataException('Object type not managed');
        }

        $form = $formFactory->create($formType, $comment);

        $form->bind($request);
        if ($form->isValid()) {
            $comment = $form->getData();
            $comment->save();
        } else {
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
        $form = $formFactory->create($formType, $comment);

        // Events
        $event = new GenericEvent($comment, array('user_id' => $user->getId(),));
        $dispatcher = $eventDispatcher->dispatch('r_comment_publish', $event);
        $event = new GenericEvent($comment, array('author_user_id' => $user->getId(),));
        $dispatcher = $eventDispatcher->dispatch('n_comment_publish', $event);
        $event = new GenericEvent($comment, array('author_user_id' => $user->getId()));
        $dispatcher = $eventDispatcher->dispatch('b_comment_publish', $event);

        // Rendering
        $html = $templating->render(
            'PolitizrFrontBundle:Comment:_paragraphComments.html.twig',
            array(
                'document' => $document,
                'comments' => $comments,
                'formComment' => $form->createView(),
            )
        );
        $counter = $templating->render(
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
    public function comments()
    {
        $logger = $this->sc->get('logger');
        $logger->info('*** comments');
        
        // Retrieve used services
        $securityContext = $this->sc->get('security.context');
        $formFactory = $this->sc->get('form.factory');
        $request = $this->sc->get('request');
        $templating = $this->sc->get('templating');

        // Request arguments
        $id = $request->get('subjectId');
        $logger->info('$id = ' . print_r($id, true));
        $type = $request->get('type');
        $logger->info('$type = ' . print_r($type, true));
        $noParagraph = $request->get('noParagraph');
        $logger->info('$noParagraph = ' . print_r($noParagraph, true));

        // Function process
        $user = $securityContext->getToken()->getUser();
        switch ($type) {
            case PDocumentInterface::TYPE_DEBATE:
                $document = PDDebateQuery::create()->findPk($id);
                $comment = new PDDComment();
                $formType = new PDDCommentType();
                break;
            case PDocumentInterface::TYPE_REACTION:
                $document = PDReactionQuery::create()->findPk($id);
                $comment = new PDRComment();
                $formType = new PDRCommentType();
                break;
            default:
                throw new InconsistentDataException('Object type not managed');
        }

        $comments = $document->getComments(true, $noParagraph);

        if ($securityContext->isGranted('ROLE_PROFILE_COMPLETED')) {
            $comment->setPUserId($user->getId());
            $comment->setPDocumentId($document->getId());
            $comment->setParagraphNo($noParagraph);
        }
        $formComment = $formFactory->create($formType, $comment);

        // Rendering
        $html = $templating->render(
            'PolitizrFrontBundle:Comment:_paragraphComments.html.twig',
            array(
                'document' => $document,
                'comments' => $comments,
                'formComment' => $formComment->createView(),
            )
        );
        $counter = $templating->render(
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
