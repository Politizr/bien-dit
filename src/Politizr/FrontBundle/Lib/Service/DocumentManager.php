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

use Politizr\Model\PDDebateQuery;
use Politizr\Model\PDReactionQuery;
use Politizr\Model\PDocumentQuery;
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
    public function __construct($serviceContainer) {
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
    public function debateNew() {
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
    public function reactionNew($debateId, $parentId) {
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

        // Gestion nested set
        if ($parent) {
            $reaction->insertAsLastChildOf($parent);
        } else {
            $rootNode = PDReactionQuery::create()->findOrCreateRoot($debate->getId());
            if ($nbReactions = $debate->countReactions() == 0) {
                $reaction->insertAsFirstChildOf($rootNode); // pas de niveau 0
            } else {
                $reaction->insertAsNextSiblingOf($debate->getLastReaction(1));
            }
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
    public function follow() {
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
            $dispatcher = $this->sc->get('event_dispatcher')->dispatch('debate_follow', $event);
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
            $dispatcher = $this->sc->get('event_dispatcher')->dispatch('debate_unfollow', $event);
        }

        // Construction rendu
        $templating = $this->sc->get('templating');
        $html = $templating->render(
                            'PolitizrFrontBundle:Fragment\\Follow:glSubscribe.html.twig', array(
                                'object' => $object,
                                'context' => PDocument::TYPE_DEBATE
                                )
                    );


        // Renvoi de l'ensemble des blocs HTML maj
        return array(
            'html' => $html
            );
    }


    /**
     *  Gestion note +/- d'un débat / réaction / commentaire par le user courant
     *
     */
    public function note() {
        $logger = $this->sc->get('logger');
        $logger->info('*** note');
        
        // Récupération user
        $user = $this->sc->get('security.context')->getToken()->getUser();

        // Récupération args
        $request = $this->sc->get('request');

        // Récupération args
        $objectId = $request->get('objectId');
        $logger->info('$objectId = ' . print_r($objectId, true));
        $context = $request->get('context');
        $logger->info('$context = ' . print_r($context, true));
        $way = $request->get('way');
        $logger->info('$way = ' . print_r($way, true));

        // Récupération objet
        switch($context) {
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
            $dispatcher = $this->sc->get('event_dispatcher')->dispatch('note_pos', $event);
        } elseif ($way == 'down') {
            $object->setNoteNeg($object->getNoteNeg() + 1);
            $object->save();

            // Réputation
            $event = new GenericEvent($object, array('user_id' => $user->getId(),));
            $dispatcher = $this->sc->get('event_dispatcher')->dispatch('note_neg', $event);
        }

        // Construction rendu
        $templating = $this->sc->get('templating');
        $html = $templating->render(
                            'PolitizrFrontBundle:Fragment\\Reputation:glNotation.html.twig', array(
                                'object' => $object,
                                'context' => $context,
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
    public function comments() {
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
        if ($this->sc->get('security.context')->isGranted('ROLE_PROFILE_COMPLETED')){
            $comment->setPUserId($user->getId());
            $comment->setPDocumentId($document->getId());
            $comment->setParagraphNo($noParagraph);
        }
        $formComment = $this->sc->get('form.factory')->create(new PDCommentType(), $comment);

        // Construction rendu
        $templating = $this->sc->get('templating');
        $html = $templating->render(
                            'PolitizrFrontBundle:Fragment\\Comment:glFormList.html.twig', array(
                                'document' => $document,
                                'comments' => $comments,
                                'formComment' => $formComment->createView(),
                                )
                    );
        $counter = $templating->render(
                            'PolitizrFrontBundle:Fragment\\Comment:Counter.html.twig', array(
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
    public function debateUpdate() {
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
    public function debatePublish() {
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

        // Réputation
        $event = new GenericEvent($debate, array('user_id' => $user->getId(),));
        $dispatcher = $this->sc->get('event_dispatcher')->dispatch('debate_publish', $event);

        // Renvoi de l'url de redirection
        return array(
            'redirectUrl' => $redirectUrl,
            );
    }

    /**
     *  Suppression du débat
     *
     */
    public function debateDelete() {
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
     *  Upload du bandeau photo du débat
     *
     */
    public function debatePhotoUpload() {
        $logger = $this->sc->get('logger');
        $logger->info('*** debatePhotoUpload');
        
        // Récupération user
        $user = $this->sc->get('security.context')->getToken()->getUser();

        // Récupération args
        $request = $this->sc->get('request');

        // Récupération débat courant
        $id = $request->get('id');
        $logger->info(print_r($id, true));
        $debate = PDDebateQuery::create()->findPk($id);

        // Chemin des images
        $path = $this->sc->get('kernel')->getRootDir() . '/../web' . PDDebate::UPLOAD_WEB_PATH;

        // Taille max 5Mo
        $sizeLimit = 5 * 1024 * 1024;

        $myRequestedFile = $request->files->get('file-name');
        // $logger->info(print_r($myRequestedFile, true));

        if ($myRequestedFile == null) {
            throw new FormValidationException('Fichier non existant.');
        } else if ($myRequestedFile->getError() > 0) {
            throw new FormValidationException('Erreur upload n°'.$myRequestedFile->getError(), 1);
        } else {
            // Contrôle extension
            $allowedExtensions = array('jpg', 'jpeg', 'png');
            $ext = $myRequestedFile->guessExtension();
            if ($allowedExtensions && !in_array(strtolower($ext), $allowedExtensions)) {
                throw new FormValidationException('Type de fichier non autorisé.');
            }

            // Construction du nom du fichier
            $destName = md5(uniqid()) . '.' . $ext;

            //move the uploaded file to uploads folder;
            // $move = move_uploaded_file($pathNameTmp, $path . $destName);
            $movedFile = $myRequestedFile->move($path, $destName);
            $logger->info('$movedFile = '.print_r($movedFile, true));
        }

        // Suppression photo déjà uploadée
        $filename = $debate->getFilename();
        if ($filename && $fileExists = file_exists($path . $filename)) {
            unlink($path . $filename);
        }

        // TODO > ajout d'une contrainte sur une taille minimum
        // Resize de la photo 1024*1024px max
        $resized = false;                
        $image = new SimpleImage();
        $image->load($path . $destName);
        if ($width = $image->getWidth() > 1024) {
            $image->resizeToWidth(1024);
            $resized = true;
        }
        if ($height = $image->getHeight() > 1024) {
            $image->resizeToHeight(1024);
            $resized = true;
        }
        if ($resized) {
            $image->save($path . $destName);
        }

        // MAJ du modèle
        $debate->setFilename($destName);
        $debate->save();

        // Renvoi de l'ensemble des blocs HTML maj
        return array(
            'filename' => $destName,
            );
    }


    /**
     *  Upload du bandeau photo du débat
     *
     */
    public function debatePhotoDelete() {
        $logger = $this->sc->get('logger');
        $logger->info('*** debatePhotoDelete');
        
        // Récupération user
        $user = $this->sc->get('security.context')->getToken()->getUser();

        // Récupération args
        $request = $this->sc->get('request');

        // Récupération débat courant
        $id = $request->get('id');
        $logger->info(print_r($id, true));
        $debate = PDDebateQuery::create()->findPk($id);

        // Chemin des images
        $path = $this->sc->get('kernel')->getRootDir() . '/../web' . PDDebate::UPLOAD_WEB_PATH;

        // Suppression photo déjà uploadée
        $filename = $debate->getFilename();
        if ($filename && $fileExists = file_exists($path . $filename)) {
            unlink($path . $filename);
        }

        // MAJ du modèle
        $debate->setFilename(null);
        $debate->save();

        return true;
    }

    /* ######################################################################################################## */
    /*                                          EDITION REACTION (FONCTIONS AJAX)                               */
    /* ######################################################################################################## */

    /**
     *  Enregistre la réaction
     *
     */
    public function reactionUpdate() {
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
    public function reactionPublish() {
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

        // Récupération URL redirection
        $redirectUrl = $request->get('url');

        // MAJ de l'objet
        $reaction = PDReactionQuery::create()->findPk($id);
        $reaction->setPublished(true);
        $reaction->setPublishedAt(time());
        $reaction->save();

        $this->sc->get('session')->getFlashBag()->add('success', 'Objet publié avec succès.');

        // Réputation
        $event = new GenericEvent($reaction, array('user_id' => $user->getId(),));
        $dispatcher = $this->sc->get('event_dispatcher')->dispatch('reaction_publish', $event);

        // Renvoi de l'url de redirection
        return array(
            'redirectUrl' => $redirectUrl,
            );
    }


    /**
     *  Suppression du brouillon de la réaction
     *
     */
    public function reactionDelete() {
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
    public function commentNew() {
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

            // Réputation
            $event = new GenericEvent($comment, array('user_id' => $user->getId(),));
            $dispatcher = $this->sc->get('event_dispatcher')->dispatch('comment_publish', $event);

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
                                'PolitizrFrontBundle:Fragment\\Comment:glFormList.html.twig', array(
                                    'document' => $document,
                                    'comments' => $comments,
                                    'formComment' => $form->createView(),
                                    )
                        );
            $counter = $templating->render(
                                'PolitizrFrontBundle:Fragment\\Comment:Counter.html.twig', array(
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
}