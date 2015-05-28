<?php
namespace Politizr\FrontBundle\Lib\Service;

use Symfony\Component\EventDispatcher\GenericEvent;

use StudioEcho\Lib\StudioEchoUtils;

use Politizr\Exception\InconsistentDataException;
use Politizr\Exception\FormValidationException;

use Politizr\FrontBundle\Lib\SimpleImage;

use Politizr\Model\PDocument;
use Politizr\Model\PDDebate;
use Politizr\Model\PDReaction;
use Politizr\Model\PUFollowDD;
use Politizr\Model\PDComment;

use Politizr\Model\PDocumentQuery;
use Politizr\Model\PDDebateQuery;
use Politizr\Model\PDReactionQuery;
use Politizr\Model\PUFollowDDQuery;
use Politizr\Model\PDCommentQuery;

use Politizr\FrontBundle\Form\Type\PDCommentType;
use Politizr\FrontBundle\Form\Type\PDDebateType;
use Politizr\FrontBundle\Form\Type\PDReactionType;

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
    public function debateNew()
    {
        // Récupération user
        $user = $this->sc->get('security.context')->getToken()->getUser();

        // Création d'un nouvel objet et redirection vers l'édition
        $debate = new PDDebate();
        
        $debate->setTitle('Un nouveau débat');
        
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
    public function reactionNew($debateId, $parentId)
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
        
        $reaction->setTitle('Une nouvelle réaction');
        
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
                'type' => PDocument::TYPE_DEBATE
            )
        );
        $followers = $templating->render(
            'PolitizrFrontBundle:Fragment\\Follow:glFollowers.html.twig',
            array(
                'object' => $object,
                'type' => PDocument::TYPE_DEBATE
            )
        );

        // Renvoi de l'ensemble des blocs HTML maj
        return array(
            'html' => $html,
            'followers' => $followers,
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
            case PDocument::TYPE_DEBATE:
                $object = PDDebateQuery::create()->findPk($objectId);
                break;
            case PDocument::TYPE_REACTION:
                $object = PDReactionQuery::create()->findPk($objectId);
                break;
            case PDocument::TYPE_COMMENT:
                $object = PDCommentQuery::create()->findPk($objectId);
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
                case PDocument::TYPE_DEBATE:
                case PDocument::TYPE_REACTION:
                    $event = new GenericEvent($object, array('author_user_id' => $user->getId(), 'target_user_id' => $object->getPUserId()));
                    $dispatcher = $this->sc->get('event_dispatcher')->dispatch('b_document_note_pos', $event);
                    break;
                case PDocument::TYPE_COMMENT:
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
                case PDocument::TYPE_DEBATE:
                case PDocument::TYPE_REACTION:
                    $event = new GenericEvent($object, array('author_user_id' => $user->getId(), 'target_user_id' => $object->getPUserId()));
                    $dispatcher = $this->sc->get('event_dispatcher')->dispatch('b_document_note_neg', $event);
                    break;
                case PDocument::TYPE_COMMENT:
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

    /**
     *  Affichage des commentaires d'un paragraphe (ou globaux) d'un document (débat / réaction).
     *
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
        $objectId = $request->get('objectId');
        $logger->info('$objectId = ' . print_r($objectId, true));
        $noParagraph = $request->get('noParagraph');
        $logger->info('$noParagraph = ' . print_r($noParagraph, true));

        // Récupération objet
        $document = PDocumentQuery::create()->findPk($objectId);

        // Récupération des commentaires du paragraphe
        $comments = $document->getComments(true, $noParagraph);

        // Form saisie commentaire
        $comment = new PDComment();
        if ($this->sc->get('security.context')->isGranted('ROLE_PROFILE_COMPLETED')) {
            $comment->setPUserId($user->getId());
            $comment->setPDocumentId($document->getId());
            $comment->setParagraphNo($noParagraph);
        }
        $formComment = $this->sc->get('form.factory')->create(new PDCommentType(), $comment);

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

    /* ######################################################################################################## */
    /*                                EDITION DEBAT  (FONCTIONS AJAX)                                           */
    /* ######################################################################################################## */


    /**
     *  Enregistre le débat
     *
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
     *  Publication du débat
     *
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

        // Récupération URL redirection
        $redirectUrl = $request->get('url');

        // MAJ de l'objet
        $debate = PDDebateQuery::create()->findPk($id);
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
     *  Suppression du débat
     *
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
        $document = PDocumentQuery::create()->findPk($id);
        if (!$document) {
            throw new InconsistentDataException('Document n°'.$id.' not found.');
        }
        if ($document->getPublished()) {
            throw new InconsistentDataException('Document n°'.$id.' is published and cannot be edited anymore.');
        }
        if (!$document->isOwner($user->getId())) {
            throw new InconsistentDataException('Document n°'.$id.' is not yours.');
        }

        // Récupération URL redirection
        $redirectUrl = $request->get('url');

        // MAJ de l'objet
        $debate = PDDebateQuery::create()->findPk($id);
        $debate->deleteWithoutArchive(); // pas d'archive sur les brouillons

        $this->sc->get('session')->getFlashBag()->add('success', 'Objet supprimé avec succès.');

        // Renvoi de l'url de redirection
        return array(
            'redirectUrl' => $redirectUrl,
            );
    }


    /**
     *  Upload du bandeau photo du document (débat ou réaction)
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

        // Récupération débat courant
        $id = $request->get('id');
        $logger->info(print_r($id, true));
        $document = PDocumentQuery::create()->findPk($id);

        // Récupération de l'objet descendant
        $docChild = $document->getChildObject();

        // Chemin des images
        $path = $this->sc->get('kernel')->getRootDir() . '/../web' . PDocument::UPLOAD_WEB_PATH;

        // Appel du service d'upload ajax
        $fileName = $this->sc->get('politizr.utils')->uploadImageAjax(
            'file-name',
            $path,
            1024,
            1024
        );

        // Suppression photo déjà uploadée
        $oldFilename = $docChild->getFilename();
        if ($oldFilename && $fileExists = file_exists($path . $oldFilename)) {
            unlink($path . $oldFilename);
        }


        // MAJ du modèle
        $docChild->setFilename($fileName);
        $docChild->save();

        // Construction rendu
        $templating = $this->sc->get('templating');
        $html = $templating->render(
            'PolitizrFrontBundle:Fragment\\Global:Image.html.twig',
            array(
                'document' => $document,
                'path' => 'uploads/documents/'.$fileName,
                'filterName' => 'debate_header',
            )
        );

        return array(
            'html' => $html,
            );
    }


    /**
     *  Upload du bandeau photo du débat
     *
     */
    public function documentPhotoDelete()
    {
        $logger = $this->sc->get('logger');
        $logger->info('*** documentPhotoDelete');
        
        // Récupération user
        $user = $this->sc->get('security.context')->getToken()->getUser();

        // Récupération args
        $request = $this->sc->get('request');

        // Récupération débat courant
        $id = $request->get('id');
        $logger->info(print_r($id, true));
        $document = PDocumentQuery::create()->findPk($id);

        // Récupération de l'objet descendant
        $docChild = $document->getChildObject();

        // Chemin des images
        $path = $this->sc->get('kernel')->getRootDir() . '/../web' . PDocument::UPLOAD_WEB_PATH;

        // Suppression photo déjà uploadée
        $filename = $docChild->getFilename();
        if ($filename && $fileExists = file_exists($path . $filename)) {
            unlink($path . $filename);
        }

        // MAJ du modèle
        $docChild->setFilename(null);
        $docChild->save();

        // Construction rendu
        $templating = $this->sc->get('templating');
        $html = $templating->render(
            'PolitizrFrontBundle:Fragment\\Global:Image.html.twig',
            array(
                'document' => $document,
                'path' => 'bundles/politizrfront/images/default_debate.jpg',
                'filterName' => 'debate_header',
            )
        );

        return array(
            'html' => $html,
            );
    }

    /* ######################################################################################################## */
    /*                                          EDITION REACTION (FONCTIONS AJAX)                               */
    /* ######################################################################################################## */

    /**
     *  Enregistre la réaction
     *
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
     *  Publication de la réaction
     *
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

        // Récupération de l'objet réaction
        $reaction = PDReactionQuery::create()->findPk($id);

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
     *  Suppression du brouillon de la réaction
     *
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
        $document = PDocumentQuery::create()->findPk($id);
        if (!$document) {
            throw new InconsistentDataException('Document n°'.$id.' not found.');
        }
        if ($document->getPublished()) {
            throw new InconsistentDataException('Document n°'.$id.' is published and cannot be edited anymore.');
        }
        if (!$document->isOwner($user->getId())) {
            throw new InconsistentDataException('Document n°'.$id.' is not yours.');
        }

        // Récupération URL redirection
        $redirectUrl = $request->get('url');

        // // MAJ de l'objet
        $reaction = PDReactionQuery::create()->findPk($id);
        $reaction->deleteWithoutArchive(); // pas d'archive sur les brouillons

        $this->sc->get('session')->getFlashBag()->add('success', 'Objet supprimé avec succès.');

        // Renvoi de l'url de redirection
        return array(
            'redirectUrl' => $redirectUrl,
            );
    }

    /* ######################################################################################################## */
    /*                                          EDITION COMMENTAIRE (FONCTIONS AJAX)                            */
    /* ######################################################################################################## */


    /**
     *  Enregistre un nouveau commentaire
     *
     */
    public function commentNew()
    {
        $logger = $this->sc->get('logger');
        $logger->info('*** commentNew');
        
        // Récupération user
        $user = $this->sc->get('security.context')->getToken()->getUser();

        // Récupération args
        $request = $this->sc->get('request');

        $comment = new PDComment();
        $form = $this->sc->get('form.factory')->create(new PDCommentType(), $comment);

        $form->bind($request);
        if ($form->isValid()) {
            $comment = $form->getData();
            $comment->save();

            // TODO / regrouper la construction d'un seul objet GenericEvent + homogénéisation / normalisation
            // Réputation
            $event = new GenericEvent($comment, array('user_id' => $user->getId(),));
            $dispatcher = $this->sc->get('event_dispatcher')->dispatch('r_comment_publish', $event);

            // Badges associés
            $event = new GenericEvent($comment, array('author_user_id' => $user->getId()));
            $dispatcher = $this->sc->get('event_dispatcher')->dispatch('b_comment_publish', $event);

            // Notification
            $event = new GenericEvent($comment, array('author_user_id' => $user->getId(),));
            $dispatcher = $this->sc->get('event_dispatcher')->dispatch('n_comment_publish', $event);


            // Récupération objet
            $objectId = $comment->getPDocumentId();
            $noParagraph = $comment->getParagraphNo();

            $document = PDocumentQuery::create()->findPk($objectId);

            // Récupération des commentaires du paragraphe
            $comments = $document->getComments(true, $noParagraph);

            $comment = new PDComment();
            if ($user) {
                $comment->setPUserId($user->getId());
                $comment->setPDocumentId($document->getId());
                $comment->setParagraphNo($noParagraph);
            }
            $form = $this->sc->get('form.factory')->create(new PDCommentType(), $comment);

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

    /* ######################################################################################################## */
    /*                                            DEBATS SUIVIS (FONCTIONS AJAX)                                */
    /* ######################################################################################################## */


    /**
     * Listing de débats ordonnancés suivant l'argument récupéré
     *
     */
    public function followedDebateList()
    {
        $logger = $this->sc->get('logger');
        $logger->info('*** followedDebateList');
        
        // Récupération user
        $user = $this->sc->get('security.context')->getToken()->getUser();

        // Récupération args
        $request = $this->sc->get('request');

        $order = $request->get('order');
        $logger->info('$order = ' . print_r($order, true));
        $filters = $request->get('filters');
        $logger->info('$filters = ' . print_r($filters, true));
        $offset = $request->get('offset');
        $logger->info('$offset = ' . print_r($offset, true));

        $debates = PDDebateQuery::create()
                    ->online()
                    ->usePuFollowDdPDDebateQuery()
                        ->filterByPUserId($user->getId())
                    ->endUse()
                    ->filterByKeywords($filters)
                    ->orderWithKeyword($order)
                    ->limit(10)
                    ->offset($offset)
                    ->find();

        // Construction rendu
        $templating = $this->sc->get('templating');
        $html = $templating->render(
            'PolitizrFrontBundle:Fragment\\Debate:glListNotifSettings.html.twig',
            array(
                'debates' => $debates,
                'offset' => intval($offset) + 10,
            )
        );

        // Renvoi de l'ensemble des blocs HTML maj
        return array(
            'html' => $html,
            );
    }
}
