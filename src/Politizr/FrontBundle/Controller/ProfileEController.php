<?php

namespace Politizr\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Politizr\Exception\InconsistentDataException;

use Politizr\Model\PDocumentPeer;

use Politizr\Model\PUserQuery;
use Politizr\Model\PDocumentQuery;
use Politizr\Model\PDDebateQuery;
use Politizr\Model\PDReactionQuery;
use Politizr\Model\PTagQuery;
use Politizr\Model\PUTaggedTQuery;
use Politizr\Model\PUFollowTQuery;
use Politizr\Model\PUFollowDDQuery;
use Politizr\Model\PUFollowUQuery;
use Politizr\Model\PDCommentQuery;
use Politizr\Model\PRBadgeQuery;
use Politizr\Model\PUReputationRBQuery;
use Politizr\Model\PUReputationRAQuery;

use Politizr\Model\PUser;
use Politizr\Model\PTag;
use Politizr\Model\PDDebate;
use Politizr\Model\PDReaction;
use Politizr\Model\PTTagType;
use Politizr\Model\PUTaggedT;
use Politizr\Model\PUFollowT;
use Politizr\Model\PRBadgeMetal;

use Politizr\FrontBundle\Form\Type\PUserIdentityType;
use Politizr\FrontBundle\Form\Type\PUserEmailType;
use Politizr\FrontBundle\Form\Type\PUserBiographyType;
use Politizr\FrontBundle\Form\Type\PUserConnectionType;

/**
 * Gestion profil débatteur
 *
 * TODO:
 *	- gestion des erreurs / levés d'exceptions à revoir/blindés pour les appels Ajax
 *  - refactorisation pour réduire les doublons de code entre les tables PUTaggedT et PUFollowT
 *  - refactoring gestion des tags > gestion des doublons / admin + externalisation logique métier dans les *Query class
 *
 * @author Lionel Bouzonville
 */
class ProfileEController extends Controller {


    /* ######################################################################################################## */
    /*                                                 ROUTING CLASSIQUE                                        */
    /* ######################################################################################################## */

    /* ######################################################################################################## */
    /*                                                    ACTUALITES                                            */
    /* ######################################################################################################## */

    /**
     *  Accueil
     */
    public function homepageAction()
    {
        $logger = $this->get('logger');
        $logger->info('*** homepageAction');

        return $this->redirect($this->generateUrl('TimelineE'));
    }

    /**
     *  Timeline
     */
    public function timelineAction()
    {
        $logger = $this->get('logger');
        $logger->info('*** timelineAction');

        return $this->render('PolitizrFrontBundle:ProfileE:timeline.html.twig', array(
            ));
    }

    /**
     *  Trouver des débats
     */
    public function findDebatesAction()
    {
        $logger = $this->get('logger');
        $logger->info('*** findDebatesAction');

        return $this->render('PolitizrFrontBundle:ProfileE:findDebates.html.twig', array(
            ));
    }

    /**
     *  Trouver des users
     */
    public function findUsersAction()
    {
        $logger = $this->get('logger');
        $logger->info('*** findUsersAction');

        return $this->render('PolitizrFrontBundle:ProfileE:findUsers.html.twig', array(
            ));
    }

    /* ######################################################################################################## */
    /*                                                 CONTRIBUTIONS                                            */
    /* ######################################################################################################## */


    /**
     *  Mes contributions - Tableau de bord
     */
    public function contribDashboardAction()
    {
        $logger = $this->get('logger');
        $logger->info('*** contribDashboardAction');

        // Récupération user courant
        $pUser = $this->getUser();

        // *********************************** //
        //      Récupération objets vue
        // *********************************** //

        // Débats brouillons en attente de finalisation
        $debateDrafts = PDDebateQuery::create()->filterByPUserId($pUser->getId())->filterByPublished(false)->find();

        // Réactions brouillons en attente de finalisation
        $reactionDrafts = PDReactionQuery::create()->filterByPUserId($pUser->getId())->filterByPublished(false)->find();

        // Débats rédigés
        $debates = PDDebateQuery::create()->filterByPUserId($pUser->getId())->online()->find();

        // Réactions rédigées
        $reactions = PDReactionQuery::create()->filterByPUserId($pUser->getId())->online()->find();

        // Commentaires rédigés
        $comments = PDCommentQuery::create()->filterByPUserId($pUser->getId())->online()->find();

        // *********************************** //
        //      Affichage de la vue
        // *********************************** //

        return $this->render('PolitizrFrontBundle:ProfileE:contribDashboard.html.twig', array(
            'debateDrafts' => $debateDrafts,
            'reactionDrafts' => $reactionDrafts,
            'debates' => $debates,
            'reactions' => $reactions,
            'comments' => $comments,
            ));
    }

    /**
     *  Mes contributions - Brouillons
     */
    public function myDraftsAction()
    {
        $logger = $this->get('logger');
        $logger->info('*** myDraftsAction');

        // Récupération user courant
        $pUser = $this->getUser();

        // *********************************** //
        //      Récupération objets vue
        // *********************************** //

        // Débats brouillons en attente de finalisation
        $debateDrafts = PDDebateQuery::create()->filterByPUserId($pUser->getId())->filterByPublished(false)->find();

        // Réactions brouillons en attente de finalisation
        $reactionDrafts = PDReactionQuery::create()->filterByPUserId($pUser->getId())->filterByPublished(false)->find();

        // *********************************** //
        //      Affichage de la vue
        // *********************************** //

        return $this->render('PolitizrFrontBundle:ProfileE:myDrafts.html.twig', array(
            'debateDrafts' => $debateDrafts,
            'reactionDrafts' => $reactionDrafts,
            ));
    }


    /* ######################################################################################################## */
    /*                                                    MON COMPTE                                            */
    /* ######################################################################################################## */

    /**
     *  Mon compte - Mes Tags
     */
    public function myTagsAction()
    {
        $logger = $this->get('logger');
        $logger->info('*** myTagsAction');

        // Récupération user courant
        $pUser = $this->getUser();

        return $this->render('PolitizrFrontBundle:ProfileE:myTags.html.twig', array(
                'pUser' => $pUser,
            ));
    }


    /**
     *  Mon compte - Ma réputation
     */
    public function myReputationAction()
    {
        $logger = $this->get('logger');
        $logger->info('*** myReputationAction');

        // Récupération user courant
        $user = $this->getUser();

        // score de réputation
        $reputationScore = $user->getReputationScore();

        // historique de réputation
        $reputationHistory = PUReputationRAQuery::create()
                                ->filterByPUserId($user->getId())
                                ->orderByCreatedAt(\Criteria::DESC)
                                ->find();
        
        // badges
        $badgesGold = PRBadgeQuery::create()
                        ->filterByPRBadgeMetalId(PRBadgeMetal::GOLD)
                        ->usePRBadgeTypeQuery()
                            ->orderByRank()
                        ->endUse()
                        ->find();
        $badgesSilver = PRBadgeQuery::create()
                        ->filterByPRBadgeMetalId(PRBadgeMetal::SILVER)
                        ->usePRBadgeTypeQuery()
                            ->orderByRank()
                        ->endUse()
                        ->find();
        $badgesBronze = PRBadgeQuery::create()
                        ->filterByPRBadgeMetalId(PRBadgeMetal::BRONZE)
                        ->usePRBadgeTypeQuery()
                            ->orderByRank()
                        ->endUse()
                        ->find();

        // ids des badges du user
        $badgeIds = array();
        $badgeIds = PUReputationRBQuery::create()
                        ->filterByPUserId($user->getId())
                        ->find()
                        ->toKeyValue('PRBadgeId', 'PRBadgeId');
        $badgeIds = array_keys($badgeIds);

        // Affichage de la vue
        return $this->render('PolitizrFrontBundle:ProfileE:myReputation.html.twig', array(
            'reputationScore' => $reputationScore,
            'reputationHistory' => $reputationHistory,
            'badgesGold' => $badgesGold,
            'badgesSilver' => $badgesSilver,
            'badgesBronze' => $badgesBronze,
            'badgeIds' => $badgeIds,
            ));
    }


    /**
     *  Mon compte - Mes informations personnelles
     */
    public function myPersoAction()
    {
        $logger = $this->get('logger');
        $logger->info('*** myPersoAction');

        // Récupération user courant
        $user = $this->getUser();

        // Récupération photo profil
        $fileName = $user->getFileName();

        // *********************************** //
        //      Formulaires
        // *********************************** //
        $formPerso1 = $this->createForm(new PUserIdentityType($user), $user);
        $formPerso2 = $this->createForm(new PUserEmailType(), $user);
        $formPerso3 = $this->createForm(new PUserBiographyType(), $user);
        $formPerso4 = $this->createForm(new PUserConnectionType(), $user);

        // *********************************** //
        //      Affichage de la vue
        // *********************************** //
        return $this->render('PolitizrFrontBundle:ProfileE:myPerso.html.twig', array(
                        'formPerso1' => $formPerso1->createView(),
                        'formPerso2' => $formPerso2->createView(),
                        'formPerso3' => $formPerso3->createView(),
                        'formPerso4' => $formPerso4->createView(),
                        'fileName' => $fileName,
            ));
    }


    /* ######################################################################################################## */
    /*                                                  FONCTIONS AJAX                                          */
    /* ######################################################################################################## */



    /* ######################################################################################################## */
    /*                                                  FONCTIONS PRIVÉES                                             */
    /* ######################################################################################################## */


    /**
     * 
     * @param type $query
     * @return \Pagerfanta\Pagerfanta
     * @throws type
     */
    private function preparePagination($query, $maxPerPage = 5) {
        $adapter = new PropelAdapter($query);
        $pagerfanta = new Pagerfanta($adapter);

        try {
            $pagerfanta->setMaxPerPage($maxPerPage)->setCurrentPage($this->getRequest()->get('page'));
        } catch (Pagerfanta\Exception\NotIntegerCurrentPageException $e) {
            throw $this->createNotFoundException('PagerFanta NotIntegerCurrentPageException.');
        } catch (Pagerfanta\Exception\LessThan1CurrentPageException $e) {
            throw $this->createNotFoundException('PagerFanta LessThan1CurrentPageException.');
        } catch (Pagerfanta\Exception\OutOfRangeCurrentPageException $e) {
            throw $this->createNotFoundException('PagerFantaOutOfRangeCurrentPageException.');
        }
        
        return $pagerfanta;
    }
}