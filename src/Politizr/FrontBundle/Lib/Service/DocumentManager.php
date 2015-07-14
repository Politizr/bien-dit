<?php
namespace Politizr\FrontBundle\Lib\Service;

use Symfony\Component\EventDispatcher\GenericEvent;

use StudioEcho\Lib\StudioEchoUtils;

use Politizr\Exception\InconsistentDataException;
use Politizr\Exception\FormValidationException;

use Politizr\FrontBundle\Lib\SimpleImage;

use Politizr\Model\PDocumentInterface;
use Politizr\Model\PDDebate;
use Politizr\Model\PDReaction;
use Politizr\Model\PUFollowDD;
use Politizr\Model\PDDComment;
use Politizr\Model\PDRComment;

use Politizr\Model\PDDebateQuery;
use Politizr\Model\PDReactionQuery;
use Politizr\Model\PUFollowDDQuery;
use Politizr\Model\PDDCommentQuery;
use Politizr\Model\PDRCommentQuery;

use Politizr\FrontBundle\Form\Type\PDDCommentType;
use Politizr\FrontBundle\Form\Type\PDRCommentType;
use Politizr\FrontBundle\Form\Type\PDDebateType;
use Politizr\FrontBundle\Form\Type\PDDebatePhotoInfoType;
use Politizr\FrontBundle\Form\Type\PDReactionType;
use Politizr\FrontBundle\Form\Type\PDReactionPhotoInfoType;

/**
 * Services métiers associés aux documents (débats / réactions / commentaires).
 *
 * @author Lionel Bouzonville
 */
class DocumentManager
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
    /*                     SERVICES METIERS LIES AU CRUD DOCUMENT (DEBAT / REACTION)                            */
    /* ######################################################################################################## */


    /**
     *  Création d'un nouveau débat
     *
     *  @return PDDebate  Objet débat créé
     */
    public function createDebate()
    {
        // Récupération user
        $user = $this->sc->get('security.context')->getToken()->getUser();

        // Création d'un nouvel objet et redirection vers l'édition
        $debate = new PDDebate();
        
        $debate->setPUserId($user->getId());

        $debate->setNotePos(0);
        $debate->setNoteNeg(0);

        $debate->setOnline(true);
        $debate->setPublished(false);
        
        $debate->save();

        return $debate;
    }


    /**
     *  Création d'une nouvelle réaction
     *
     *  @param  integer     $debateId       Débat associé
     *  @param  integer     $parentId       Réaction parente associée
     *
     *  @return PDReaction  Objet réaction créé
     */
    public function createReaction($debateId, $parentId)
    {
        // Récupération user
        $user = $this->sc->get('security.context')->getToken()->getUser();

        // Récupération du débat sur lequel la réaction a lieu
        $debate = PDDebateQuery::create()->findPk($debateId);
        if (!$debate) {
            throw new InconsistentDataException('Debate n°'.$debateId.' not found.');
        }

        // Récupération de la réaction parente sur laquelle la réaction a lieu
        $parent = null;
        if ($parentId) {
            $parent = PDReactionQuery::create()->findPk($parentId);
            if (!$parent) {
                throw new InconsistentDataException('Parent reaction n°'.$parentId.' not found.');
            }
        }

        // Création d'un nouvel objet et redirection vers l'édition
        $reaction = new PDReaction();

        $reaction->setPDDebateId($debate->getId());
        
        $reaction->setPUserId($user->getId());

        $reaction->setNotePos(0);
        $reaction->setNoteNeg(0);
        
        $reaction->setOnline(true);
        $reaction->setPublished(false);

        if ($parent) {
            $reaction->setParentReactionId($parentId);
        }

        $reaction->save();

        return $reaction;
    }



    /* ######################################################################################################## */
    /*                            SUIVI, NOTES, COMMENTAIRES (FONCTIONS AJAX)                                   */
    /* ######################################################################################################## */

    /**
     *  Gestion du suivre / ne plus suivre un débat par le user courant
     *
     */
    public function follow()
    {
        $logger = $this->sc->get('logger');
        $logger->info('*** follow');
        
        // Récupération user
        $user = $this->sc->get('security.context')->getToken()->getUser();

        // Récupération args
        $request = $this->sc->get('request');

        $objectId = $request->get('objectId');
        $logger->info('$objectId = ' . print_r($objectId, true));
        $way = $request->get('way');
        $logger->info('$way = ' . print_r($way, true));

        // MAJ suivre / ne plus suivre
        if ($way == 'follow') {
            $object = PDDebateQuery::create()->findPk($objectId);

            // Insertion nouvel élément
            $pUFollowDD = new PUFollowDD();
            $pUFollowDD->setPUserId($user->getId());
            $pUFollowDD->setPDDebateId($object->getId());
            $pUFollowDD->save();

            // Réputation
            $event = new GenericEvent($object, array('user_id' => $user->getId(),));
            $dispatcher = $this->sc->get('event_dispatcher')->dispatch('r_debate_follow', $event);

            // Notification
            $event = new GenericEvent($object, array('author_user_id' => $user->getId(),));
            $dispatcher = $this->sc->get('event_dispatcher')->dispatch('n_debate_follow', $event);
        } elseif ($way == 'unfollow') {
            $object = PDDebateQuery::create()->findPk($objectId);

            // Suppression élément(s)
            $pUFollowDDList = PUFollowDDQuery::create()
                            ->filterByPUserId($user->getId())
                            ->filterByPDDebateId($object->getId())
                            ->find();
            foreach ($pUFollowDDList as $pUFollowDD) {
                $pUFollowDD->delete();
            }

            // Réputation
            $event = new GenericEvent($object, array('user_id' => $user->getId(),));
            $dispatcher = $this->sc->get('event_dispatcher')->dispatch('r_debate_unfollow', $event);
        }

        // Construction rendu
        $templating = $this->sc->get('templating');
        $html = $templating->render(
            'PolitizrFrontBundle:Follow:_subscribe.html.twig',
            array(
                'object' => $object,
                'type' => PDocumentInterface::TYPE_DEBATE
            )
        );

        // Renvoi de l'ensemble des blocs HTML maj
        return array(
            'html' => $html,
            );
    }


    /**
     *  Gestion note +/- d'un débat / réaction / commentaire par le user courant
     *
     */
    public function note()
    {
        $logger = $this->sc->get('logger');
        $logger->info('*** note');
        
        // Récupération user
        $user = $this->sc->get('security.context')->getToken()->getUser();

        // Récupération args
        $request = $this->sc->get('request');

        // Récupération args
        $objectId = $request->get('objectId');
        $logger->info('$objectId = ' . print_r($objectId, true));
        $type = $request->get('type');
        $logger->info('$type = ' . print_r($type, true));
        $way = $request->get('way');
        $logger->info('$way = ' . print_r($way, true));

        // Récupération objet
        switch($type) {
            case PDocumentInterface::TYPE_DEBATE:
                $object = PDDebateQuery::create()->findPk($objectId);
                break;
            case PDocumentInterface::TYPE_REACTION:
                $object = PDReactionQuery::create()->findPk($objectId);
                break;
            case PDocumentInterface::TYPE_DEBATE_COMMENT:
                $object = PDDCommentQuery::create()->findPk($objectId);
                break;
            case PDocumentInterface::TYPE_REACTION_COMMENT:
                $object = PDRCommentQuery::create()->findPk($objectId);
                break;
        }

        // MAJ note
        if ($way == 'up') {
            $object->setNotePos($object->getNotePos() + 1);
            $object->save();

            // Réputation
            $event = new GenericEvent($object, array('user_id' => $user->getId(),));
            $dispatcher = $this->sc->get('event_dispatcher')->dispatch('r_note_pos', $event);
            
            // Notification
            $event = new GenericEvent($object, array('author_user_id' => $user->getId(),));
            $dispatcher = $this->sc->get('event_dispatcher')->dispatch('n_note_pos', $event);

            // Badges associés
            switch($type) {
                case PDocumentInterface::TYPE_DEBATE:
                case PDocumentInterface::TYPE_REACTION:
                    $event = new GenericEvent($object, array('author_user_id' => $user->getId(), 'target_user_id' => $object->getPUserId()));
                    $dispatcher = $this->sc->get('event_dispatcher')->dispatch('b_document_note_pos', $event);
                    break;
                case PDocumentInterface::TYPE_DEBATE_COMMENT:
                case PDocumentInterface::TYPE_REACTION_COMMENT:
                    $event = new GenericEvent($object, array('author_user_id' => $user->getId(), 'target_user_id' => $object->getPUserId()));
                    $dispatcher = $this->sc->get('event_dispatcher')->dispatch('b_comment_note_pos', $event);
                    break;
            }
        } elseif ($way == 'down') {
            $object->setNoteNeg($object->getNoteNeg() + 1);
            $object->save();

            // Réputation
            $event = new GenericEvent($object, array('user_id' => $user->getId(),));
            $dispatcher = $this->sc->get('event_dispatcher')->dispatch('r_note_neg', $event);
            
            // Notification
            $event = new GenericEvent($object, array('author_user_id' => $user->getId(),));
            $dispatcher = $this->sc->get('event_dispatcher')->dispatch('n_note_neg', $event);

            // Badges associés
            switch($type) {
                case PDocumentInterface::TYPE_DEBATE:
                case PDocumentInterface::TYPE_REACTION:
                    $event = new GenericEvent($object, array('author_user_id' => $user->getId(), 'target_user_id' => $object->getPUserId()));
                    $dispatcher = $this->sc->get('event_dispatcher')->dispatch('b_document_note_neg', $event);
                    break;
                case PDocumentInterface::TYPE_DEBATE_COMMENT:
                case PDocumentInterface::TYPE_REACTION_COMMENT:
                    $event = new GenericEvent($object, array('author_user_id' => $user->getId(), 'target_user_id' => $object->getPUserId()));
                    $dispatcher = $this->sc->get('event_dispatcher')->dispatch('b_comment_note_neg', $event);
                    break;
            }
        }

        // Construction rendu
        $templating = $this->sc->get('templating');
        $html = $templating->render(
            'PolitizrFrontBundle:Reputation:_noteAction.html.twig',
            array(
                'object' => $object,
                'type' => $type,
            )
        );

        // Renvoi de l'ensemble des blocs HTML maj
        return array(
            'html' => $html
            );
    }

    /* ######################################################################################################## */
    /*                                EDITION DEBAT  (FONCTIONS AJAX)                                           */
    /* ######################################################################################################## */


    /**
     * Debate update
     */
    public function debateUpdate()
    {
        $logger = $this->sc->get('logger');
        $logger->info('*** debateUpdate');
        
        // Récupération user
        $user = $this->sc->get('security.context')->getToken()->getUser();

        // Récupération args
        $request = $this->sc->get('request');

        // Récupération id objet édité
        $id = $request->get('debate')['id'];
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

        $form = $this->sc->get('form.factory')->create(new PDDebateType(), $debate);
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
        
        // Récupération user
        $user = $this->sc->get('security.context')->getToken()->getUser();

        // Récupération args
        $request = $this->sc->get('request');

        // Récupération id objet édité
        $id = $request->get('debate_photo_info')['id'];
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

        $form = $this->sc->get('form.factory')->create(new PDDebatePhotoInfoType(), $debate);

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

        // Construction rendu
        $templating = $this->sc->get('templating');

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
        
        // Récupération user
        $user = $this->sc->get('security.context')->getToken()->getUser();

        // Récupération args
        $request = $this->sc->get('request');

        // Récupération id objet édité
        $id = $request->get('id');
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

        // Récupération URL redirection
        $redirectUrl = $request->get('url');

        // MAJ de l'objet
        $debate->setPublished(true);
        $debate->setPublishedAt(time());
        $debate->save();

        $this->sc->get('session')->getFlashBag()->add('success', 'Objet publié avec succès.');

        // Réputation & badges
        $event = new GenericEvent($debate, array('user_id' => $user->getId(),));
        $dispatcher = $this->sc->get('event_dispatcher')->dispatch('r_debate_publish', $event);

        // Notification
        $event = new GenericEvent($debate, array('author_user_id' => $user->getId(),));
        $dispatcher = $this->sc->get('event_dispatcher')->dispatch('n_debate_publish', $event);

        // Renvoi de l'url de redirection
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
        
        // Récupération user
        $user = $this->sc->get('security.context')->getToken()->getUser();

        // Récupération args
        $request = $this->sc->get('request');

        // Récupération id objet édité
        $id = $request->get('id');
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

        // Récupération URL redirection
        $redirectUrl = $request->get('url');

        // MAJ de l'objet
        $debate = PDDebateQuery::create()->findPk($id);
        $debate->delete();

        $this->sc->get('session')->getFlashBag()->add('success', 'Objet supprimé avec succès.');

        // Renvoi de l'url de redirection
        return array(
            'redirectUrl' => $redirectUrl,
            );
    }

    /* ######################################################################################################## */
    /*                                          EDITION REACTION (FONCTIONS AJAX)                               */
    /* ######################################################################################################## */

    /**
     * Reaction update
     */
    public function reactionUpdate()
    {
        $logger = $this->sc->get('logger');
        $logger->info('*** reactionUpdate');
        
        // Récupération user
        $user = $this->sc->get('security.context')->getToken()->getUser();

        // Récupération args
        $request = $this->sc->get('request');

        // Récupération id objet édité
        $id = $request->get('reaction')['id'];
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

        $form = $this->sc->get('form.factory')->create(new PDReactionType(), $reaction);

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
        
        // Récupération user
        $user = $this->sc->get('security.context')->getToken()->getUser();

        // Récupération args
        $request = $this->sc->get('request');

        // Récupération id objet édité
        $id = $request->get('reaction_photo_info')['id'];
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

        $form = $this->sc->get('form.factory')->create(new PDReactionPhotoInfoType(), $reaction);

        // Retrieve actual file name
        $oldFileName = $reaction->getFileName();

        $form->bind($request);
        if ($form->isValid()) {
            $reaction = $form->getData();
            $reaction->save();

            // Remove old file if new upload or deletion has been done
            $fileName = $reaction->getFileName();
            if ($fileName != $oldFileName) {
                $path = $this->sc->get('kernel')->getRootDir() . '/../web' . PDReaction::UPLOAD_WEB_PATH;
                if ($oldFileName && $fileExists = file_exists($path . $oldFileName)) {
                    unlink($path . $oldFileName);
                }
            }
        } else {
            $errors = StudioEchoUtils::getAjaxFormErrors($form);
            throw new FormValidationException($errors);
        }

        // Construction rendu
        $templating = $this->sc->get('templating');

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
        
        // Récupération user
        $user = $this->sc->get('security.context')->getToken()->getUser();

        // Récupération args
        $request = $this->sc->get('request');

        // Récupération id objet édité
        $id = $request->get('id');
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

        // Récupération du débat sur lequel la réaction a lieu
        $debate = PDDebateQuery::create()->findPk($reaction->getPDDebateId());
        if (!$debate) {
            throw new InconsistentDataException('Debate n°'.$debateId.' not found.');
        }

        // Récupération URL redirection
        $redirectUrl = $request->get('url');

        // Gestion nested set
        // Récupération de la réaction parente sur laquelle la réaction a lieu
        $parent = null;
        $parentId = $reaction->getParentReactionId();
        if ($parentId) {
            $parent = PDReactionQuery::create()->findPk($parentId);
            if (!$parent) {
                throw new InconsistentDataException('Parent reaction n°'.$parentId.' not found.');
            }

            $reaction->insertAsLastChildOf($parent);
        } else {
            $rootNode = PDReactionQuery::create()->findOrCreateRoot($debate->getId());
            if ($nbReactions = $debate->countReactions() == 0) {
                $reaction->insertAsFirstChildOf($rootNode); // pas de niveau 0
            } else {
                $reaction->insertAsNextSiblingOf($debate->getLastReaction(1));
            }
        }

        $reaction->setPublished(true);
        $reaction->setPublishedAt(time());
        $reaction->save();

        $this->sc->get('session')->getFlashBag()->add('success', 'Objet publié avec succès.');

        // Réputation
        $event = new GenericEvent($reaction, array('user_id' => $user->getId(),));
        $dispatcher = $this->sc->get('event_dispatcher')->dispatch('r_reaction_publish', $event);
   
        // Notification
        $event = new GenericEvent($reaction, array('author_user_id' => $user->getId(),));
        $dispatcher = $this->sc->get('event_dispatcher')->dispatch('n_reaction_publish', $event);

        // Badges associés
        if ($reaction->getTreeLevel() > 1) {
            $parentUserId = $reaction->getParent()->getPUserId();
        } else {
            $parentUserId = $reaction->getDebate()->getPUserId();
        }
        $event = new GenericEvent($reaction, array('author_user_id' => $user->getId(), 'parent_user_id' => $parentUserId));
        $dispatcher = $this->sc->get('event_dispatcher')->dispatch('b_reaction_publish', $event);

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
        
        // Récupération user
        $user = $this->sc->get('security.context')->getToken()->getUser();

        // Récupération args
        $request = $this->sc->get('request');

        // Récupération id objet édité
        $id = $request->get('id');
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

        // Récupération URL redirection
        $redirectUrl = $request->get('url');

        // // MAJ de l'objet
        $reaction->delete();

        $this->sc->get('session')->getFlashBag()->add('success', 'Objet supprimé avec succès.');

        // Renvoi de l'url de redirection
        return array(
            'redirectUrl' => $redirectUrl,
            );
    }

    /* ######################################################################################################## */
    /*                                          EDITION DEBAT / REACTION (FONCTIONS AJAX)                       */
    /* ######################################################################################################## */

    /**
     * Upload du bandeau photo du document (débat ou réaction)
     *
     */
    public function documentPhotoUpload()
    {
        $logger = $this->sc->get('logger');
        $logger->info('*** documentPhotoUpload');

        // Récupération user
        $user = $this->sc->get('security.context')->getToken()->getUser();

        // Récupération args
        $request = $this->sc->get('request');

        $id = $request->get('id');
        $logger->info(print_r($id, true));
        $type = $request->get('type');
        $logger->info(print_r($type, true));

        // Récupération débat courant
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
        $path = $this->sc->get('kernel')->getRootDir() . '/../web' . $uploadWebPath;

        // Appel du service d'upload ajax
        $fileName = $this->sc->get('politizr.utils')->uploadImageAjax(
            'fileName',
            $path,
            1024,
            1024
        );

        // Construction rendu
        $templating = $this->sc->get('templating');
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
    /*                                          COMMENTAIRE (FONCTIONS AJAX)                                    */
    /* ######################################################################################################## */


    /**
     * Create a new comment
     */
    public function commentNew()
    {
        $logger = $this->sc->get('logger');
        $logger->info('*** commentNew');
        
        // Récupération user
        $user = $this->sc->get('security.context')->getToken()->getUser();

        // Récupération args
        $request = $this->sc->get('request');

        // Récupération args
        $type = $request->get('comment')['type'];
        $logger->info('$type = ' . print_r($type, true));

        // Récupération objet
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


        $form = $this->sc->get('form.factory')->create($formType, $comment);

        $form->bind($request);
        if ($form->isValid()) {
            $comment = $form->getData();
            $comment->save();

            // @todo regrouper la construction d'un seul objet GenericEvent + homogénéisation / normalisation
            // Réputation
            $event = new GenericEvent($comment, array('user_id' => $user->getId(),));
            $dispatcher = $this->sc->get('event_dispatcher')->dispatch('r_comment_publish', $event);

            // Badges associés
            $event = new GenericEvent($comment, array('author_user_id' => $user->getId()));
            $dispatcher = $this->sc->get('event_dispatcher')->dispatch('b_comment_publish', $event);

            // Notification
            $event = new GenericEvent($comment, array('author_user_id' => $user->getId(),));
            $dispatcher = $this->sc->get('event_dispatcher')->dispatch('n_comment_publish', $event);

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
            $form = $this->sc->get('form.factory')->create($formType, $comment);

            // Construction rendu
            $templating = $this->sc->get('templating');
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
        } else {
            $errors = StudioEchoUtils::getAjaxFormErrors($form);
            throw new FormValidationException($errors);
        }

        // Renvoi de l'ensemble des blocs HTML maj
        return array(
            'html' => $html,
            'counter' => $counter,
            );
    }

    /**
     * Affichage des commentaires d'un paragraphe (ou globaux) d'un document (débat / réaction).
     */
    public function comments()
    {
        $logger = $this->sc->get('logger');
        $logger->info('*** comments');
        
        // Récupération user
        $user = $this->sc->get('security.context')->getToken()->getUser();

        // Récupération args
        $request = $this->sc->get('request');

        // Récupération args
        $subjectId = $request->get('subjectId');
        $logger->info('$subjectId = ' . print_r($subjectId, true));
        $type = $request->get('type');
        $logger->info('$type = ' . print_r($type, true));
        $noParagraph = $request->get('noParagraph');
        $logger->info('$noParagraph = ' . print_r($noParagraph, true));

        // Récupération objet
        switch ($type) {
            case PDocumentInterface::TYPE_DEBATE:
                $document = PDDebateQuery::create()->findPk($subjectId);
                $comment = new PDDComment();
                $formType = new PDDCommentType();
                break;
            case PDocumentInterface::TYPE_REACTION:
                $document = PDReactionQuery::create()->findPk($subjectId);
                $comment = new PDRComment();
                $formType = new PDRCommentType();
                break;
            default:
                throw new InconsistentDataException('Object type not managed');
        }

        // Récupération des commentaires du paragraphe
        $comments = $document->getComments(true, $noParagraph);

        // Form saisie commentaire
        if ($this->sc->get('security.context')->isGranted('ROLE_PROFILE_COMPLETED')) {
            $comment->setPUserId($user->getId());
            $comment->setPDocumentId($document->getId());
            $comment->setParagraphNo($noParagraph);
        }
        $formComment = $this->sc->get('form.factory')->create($formType, $comment);

        // Construction rendu
        $templating = $this->sc->get('templating');
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

        // Renvoi de l'ensemble des blocs HTML maj
        return array(
            'html' => $html,
            'counter' => $counter,
            );
    }
}
