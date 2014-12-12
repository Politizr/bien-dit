<?php

namespace Politizr\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Symfony\Component\EventDispatcher\GenericEvent;

use Politizr\Model\PDDebateQuery;
use Politizr\Model\PDReactionQuery;
use Politizr\Model\PDocumentQuery;
use Politizr\Model\PDCommentQuery;
use Politizr\Model\PUserQuery;
use Politizr\Model\PUFollowDDQuery;
use Politizr\Model\PUFollowUQuery;

use Politizr\Model\PDDebate;
use Politizr\Model\PDReaction;
use Politizr\Model\PDocument;
use Politizr\Model\PDComment;
use Politizr\Model\PUser;
use Politizr\Model\PUFollowDD;
use Politizr\Model\PUFollowU;

use Politizr\FrontBundle\Form\Type\PDCommentType;


/**
 * Gestion des documents: débats, réactions, commentaires.
 *
 * TODO:
 *  - 
 *
 * @author Lionel Bouzonville
 */
class DocumentController extends Controller {

    /* ######################################################################################################## */
    /*                                                 ROUTING CLASSIQUE                                        */
    /* ######################################################################################################## */

    /**
     * Fil débat
     */
    public function debateFeedAction($id, $slug)
    {
        $logger = $this->get('logger');
        $logger->info('*** debateFeedAction');
        $logger->info('$id = '.print_r($id, true));
        $logger->info('$slug = '.print_r($slug, true));

        // *********************************** //
        //      Récupération objet
        // *********************************** //
        $debate = PDDebateQuery::create()->findPk($id);
        if (!$debate) {
            throw new NotFoundHttpException('pDDebate n°'.$id.' not found.');
        }
        if (!$debate->getOnline()) {
            throw new NotFoundHttpException('pDDebate n°'.$id.' not online.');
        }

        // *********************************** //
        //      Affichage de la vue
        // *********************************** //
        return $this->render('PolitizrFrontBundle:Document:debateFeed.html.twig', array(
                    'debate' => $debate
        ));
    }

    /**
     * Détail débat
     */
    public function debateDetailAction($id, $slug)
    {
        $logger = $this->get('logger');
        $logger->info('*** debateDetailAction');
        $logger->info('$id = '.print_r($id, true));
        $logger->info('$slug = '.print_r($slug, true));

        // *********************************** //
        //      Récupération objet
        // *********************************** //
        $debate = PDDebateQuery::create()->findPk($id);
        if (!$debate) {
            throw new NotFoundHttpException('Debate n°'.$id.' not found.');
        }
        if (!$debate->getOnline()) {
            throw new NotFoundHttpException('Debate n°'.$id.' not online.');
        }
        if (!$debate->getPublished()) {
            throw new NotFoundHttpException('Debate n°'.$id.' not published.');
        }

        $debate->setNbViews($debate->getNbViews() + 1);
        $debate->save();

        // Explosion des paragraphes / http://stackoverflow.com/questions/8757826/i-need-to-split-text-delimited-by-paragraph-tag
        $description = str_replace('</p>', '', $debate->getDescription());
        $paragraphs = explode('<p>', $description);
        array_shift($paragraphs);

        // *********************************** //
        //      Affichage de la vue
        // *********************************** //
        return $this->render('PolitizrFrontBundle:Document:debateDetail.html.twig', array(
        			'debate' => $debate,
                    'paragraphs' => $paragraphs,
        ));
    }


    /**
     * Détail brouillon débat
     */
    public function debateDraftAction($id)
    {
        $logger = $this->get('logger');
        $logger->info('*** debateDraftAction');
        $logger->info('$id = '.print_r($id, true));

        // Récupération user courant
        $pUser = $this->getUser();

        // *********************************** //
        //      Récupération objet
        // *********************************** //
        $debate = PDDebateQuery::create()->findPk($id);
        if (!$debate) {
            throw new NotFoundHttpException('Debate n°'.$id.' not found.');
        }
        if (!$debate->getOnline()) {
            throw new NotFoundHttpException('Debate n°'.$id.' not online.');
        }
        if ($debate->getPublished()) {
            throw new NotFoundHttpException('Debate n°'.$id.' has been published.');
        }

        // *********************************** //
        //      Affichage de la vue
        // *********************************** //
        return $this->render('PolitizrFrontBundle:Document:debateDraft.html.twig', array(
                    'debate' => $debate
        ));
    }

    /**
     * Détail réaction
     */
    public function reactionDetailAction($id, $slug)
    {
        $logger = $this->get('logger');
        $logger->info('*** reactionDetailAction');
        $logger->info('$id = '.print_r($id, true));
        $logger->info('$slug = '.print_r($slug, true));

        // *********************************** //
        //      Récupération objet
        // *********************************** //
        $reaction = PDReactionQuery::create()->findPk($id);
        if (!$reaction) {
            throw new NotFoundHttpException('Reaction n°'.$id.' not found.');
        }
        if (!$reaction->getOnline()) {
            throw new NotFoundHttpException('Reaction n°'.$id.' not online.');
        }

        $debate = $reaction ->getDebate();
        if (!$debate) {
            throw new NotFoundHttpException('Debate n°'.$id.' not found.');
        }
        if (!$debate->getOnline()) {
            throw new NotFoundHttpException('Debate n°'.$id.' not online.');
        }

        $reaction->setNbViews($reaction->getNbViews() + 1);
        $reaction->save();

        // Explosion des paragraphes / http://stackoverflow.com/questions/8757826/i-need-to-split-text-delimited-by-paragraph-tag
        $description = str_replace('</p>', '', $reaction->getDescription());
        $paragraphs = explode('<p>', $description);
        array_shift($paragraphs);

        // *********************************** //
        //      Affichage de la vue
        // *********************************** //
        return $this->render('PolitizrFrontBundle:Document:reactionDetail.html.twig', array(
                    'reaction' => $reaction,
                    'debate' => $debate,
                    'paragraphs' => $paragraphs,
        ));
    }

    /**
     * Détail réaction
     */
    public function reactionDraftAction($id)
    {
        $logger = $this->get('logger');
        $logger->info('*** reactionDraftAction');
        $logger->info('$id = '.print_r($id, true));

        // *********************************** //
        //      Récupération objet
        // *********************************** //
        $reaction = PDReactionQuery::create()->findPk($id);
        if (!$reaction) {
            throw new NotFoundHttpException('Reaction n°'.$id.' not found.');
        }
        if (!$reaction->getOnline()) {
            throw new NotFoundHttpException('Reaction n°'.$id.' not online.');
        }
        if ($reaction->getPublished()) {
            throw new NotFoundHttpException('Debate n°'.$id.' has been published.');
        }

        // TODO > UseCase à gérer = suppression d'un débat pour lesquels des réaction sont en cours de rédaction => via event sur suppression debat?
        $debate = $reaction ->getDebate();
        if (!$debate) {
            throw new NotFoundHttpException('Debate n°'.$id.' not found.');
        }
        if (!$debate->getOnline()) {
            throw new NotFoundHttpException('Debate n°'.$id.' not online.');
        }


        // *********************************** //
        //      Affichage de la vue
        // *********************************** //
        return $this->render('PolitizrFrontBundle:Document:reactionDraft.html.twig', array(
                    'reaction' => $reaction,
                    'debate' => $debate
        ));
    }

    /**
     * Détail auteur
     */
    public function userDetailAction($id, $slug)
    {
        $logger = $this->get('logger');
        $logger->info('*** userDetailAction');
        $logger->info('$id = '.print_r($id, true));
        $logger->info('$slug = '.print_r($slug, true));

        // *********************************** //
        //      Récupération objet
        // *********************************** //
        $pUser = PUserQuery::create()->findPk($id);
        if (!$pUser) {
            throw new NotFoundHttpException('pUser n°'.$id.' not found.');
        }
        if (!$pUser->getOnline()) {
            throw new NotFoundHttpException('pUser n°'.$id.' not online.');
        }

        $pUser->setNbViews($pUser->getNbViews() + 1);
        $pUser->save();


        // *********************************** //
        //      Récupération des objets associés
        // *********************************** //

        // PDDebate (collection)
        $debates = $pUser->getDebates();

        // PDReaction (collection)
        $reactions = $pUser->getReactions();

        // *********************************** //
        //      Affichage de la vue
        // *********************************** //
        return $this->render('PolitizrFrontBundle:Document:userDetail.html.twig', array(
                    'pUser' => $pUser,
                    'debates' => $debates,
                    'reactions' => $reactions
            ));
    }

    /* ######################################################################################################## */
    /*                                                  FONCTIONS AJAX                                          */
    /* ######################################################################################################## */

    /**
     *  Suivre / Ne plus suivre debat / user
     */
    public function followAction(Request $request) {
        $logger = $this->get('logger');
        $logger->info('*** followAction');
        
        try {
            if ($request->isXmlHttpRequest()) {
                // Récupération user
                $user = $this->getUser();

                // Récupération args
                $objectId = $request->get('objectId');
                $logger->info('$objectId = ' . print_r($objectId, true));
                $context = $request->get('context');
                $logger->info('$context = ' . print_r($context, true));
                $way = $request->get('way');
                $logger->info('$way = ' . print_r($way, true));

                // MAJ suivre / ne plus suivre
                if ($way == 'follow') {
                    switch($context) {
                        case PDocument::TYPE_DEBATE:
                            $object = PDDebateQuery::create()->findPk($objectId);

                            // Insertion nouvel élément
                            $pUFollowDD = new PUFollowDD();
                            $pUFollowDD->setPUserId($user->getId());
                            $pUFollowDD->setPDDebateId($object->getId());
                            $pUFollowDD->save();

                            // Réputation
                            $event = new GenericEvent($object, array('user_id' => $user->getId(),));
                            $dispatcher = $this->get('event_dispatcher')->dispatch('debate_follow', $event);

                            break;
                        case PDocument::TYPE_USER:
                            $object = PUserQuery::create()->findPk($objectId);

                            // Insertion nouvel élément
                            $pUFollowU = new PUFollowU();
                            $pUFollowU->setPUserId($object->getId());
                            $pUFollowU->setPUserFollowerId($user->getId());
                            $pUFollowU->save();

                            // Réputation
                            $event = new GenericEvent($object, array('user_id' => $user->getId(),));
                            $dispatcher = $this->get('event_dispatcher')->dispatch('user_follow', $event);

                            break;
                    }
                } elseif ($way == 'unfollow') {
                    switch($context) {
                        case PDocument::TYPE_DEBATE:
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
                            $dispatcher = $this->get('event_dispatcher')->dispatch('debate_unfollow', $event);

                            break;
                        case PDocument::TYPE_USER:
                            $object = PUserQuery::create()->findPk($objectId);

                            // Suppression élément(s)
                            $pUFollowUList = PUFollowUQuery::create()
                                            ->filterByPUserId($object->getId())
                                            ->filterByPUserFollowerId($user->getId())
                                            ->find();
                            foreach ($pUFollowUList as $pUFollowU) {
                                $pUFollowU->delete();
                            }

                            // Réputation
                            $event = new GenericEvent($object, array('user_id' => $user->getId(),));
                            $dispatcher = $this->get('event_dispatcher')->dispatch('user_unfollow', $event);

                            break;
                    }
                }

                // Construction rendu
                $templating = $this->get('templating');
                $html = $templating->render(
                                    'PolitizrFrontBundle:Fragment:LinkFollow.html.twig', array(
                                        'object' => $object,
                                        'context' => $context
                                        )
                            );

                // Construction de la réponse
                $jsonResponse = array (
                    'success' => true,
                    'html' => $html
                );
            } else {
                throw $this->createNotFoundException('Not a XHR request');
            }
        } catch (NotFoundHttpException $e) {
            $logger->info('Exception = ' . print_r($e->getMessage(), true));
            $jsonResponse = array('error' => $e->getMessage());
        } catch (\Exception $e) {
            $logger->info('Exception = ' . print_r($e->getMessage(), true));
            $jsonResponse = array('error' => $e->getMessage());
        }

        // JSON formatted success/error message
        $response = new Response(json_encode($jsonResponse));
        return $response;
    }

    /**
     *  Note débat / réaction / commentaire
     */
    public function noteAction(Request $request) {
        $logger = $this->get('logger');
        $logger->info('*** noteAction');
        
        try {
            if ($request->isXmlHttpRequest()) {
                // Récupération user
                $user = $this->getUser();

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
                    $dispatcher = $this->get('event_dispatcher')->dispatch('note_pos', $event);
                } elseif ($way == 'down') {
                    $object->setNoteNeg($object->getNoteNeg() + 1);
                    $object->save();

                    // Réputation
                    $event = new GenericEvent($object, array('user_id' => $user->getId(),));
                    $dispatcher = $this->get('event_dispatcher')->dispatch('note_neg', $event);
                }

                // Construction rendu
                $templating = $this->get('templating');
                $html = $templating->render(
                                    'PolitizrFrontBundle:Fragment:LinkNote.html.twig', array(
                                        'object' => $object,
                                        'context' => $context,
                                        )
                            );

                // Construction de la réponse
                $jsonResponse = array (
                    'success' => true,
                    'html' => $html
                );
            } else {
                throw $this->createNotFoundException('Not a XHR request');
            }
        } catch (NotFoundHttpException $e) {
            $logger->info('Exception = ' . print_r($e->getMessage(), true));
            $jsonResponse = array('error' => $e->getMessage());
        } catch (\Exception $e) {
            $logger->info('Exception = ' . print_r($e->getMessage(), true));
            $jsonResponse = array('error' => $e->getMessage());
        }

        // JSON formatted success/error message
        $response = new Response(json_encode($jsonResponse));
        return $response;
    }

    /**
     *  Commentaires d'un document
     */
    public function commentsAction(Request $request) {
        $logger = $this->get('logger');
        $logger->info('*** commentsAction');
        
        try {
            if ($request->isXmlHttpRequest()) {
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
                // Récupération user
                $user = $this->getUser();

                $comment = new PDComment();
                if ($user) {
                    $comment->setPUserId($user->getId());
                    $comment->setPDocumentId($document->getId());
                    $comment->setParagraphNo($noParagraph);
                }
                $formComment = $this->createForm(new PDCommentType(), $comment);

                // Construction rendu
                $templating = $this->get('templating');
                $html = $templating->render(
                                    'PolitizrFrontBundle:Fragment:Comments.html.twig', array(
                                        'document' => $document,
                                        'comments' => $comments,
                                        'formComment' => $formComment->createView(),
                                        )
                            );
                $counter = $templating->render(
                                    'PolitizrFrontBundle:Fragment:NbComments.html.twig', array(
                                        'document' => $document,
                                        'paragraphNo' => $noParagraph,
                                        )
                            );

                // Construction de la réponse
                $jsonResponse = array (
                    'success' => true,
                    'html' => $html,
                    'counter' => $counter,
                );
            } else {
                throw $this->createNotFoundException('Not a XHR request');
            }
        } catch (NotFoundHttpException $e) {
            $logger->info('Exception = ' . print_r($e->getMessage(), true));
            $jsonResponse = array('error' => $e->getMessage());
        } catch (\Exception $e) {
            $logger->info('Exception = ' . print_r($e->getMessage(), true));
            $jsonResponse = array('error' => $e->getMessage());
        }

        // JSON formatted success/error message
        $response = new Response(json_encode($jsonResponse));
        return $response;
    }

}