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
use Politizr\Model\PUBadgeQuery;
use Politizr\Model\PUReputationQuery;
use Politizr\Model\PUCurrentQOQuery;
use Politizr\Model\PUMandateQuery;
use Politizr\Model\PNotificationQuery;
use Politizr\Model\PUSubscribeEmailQuery;

use Politizr\Model\PUser;
use Politizr\Model\PTag;
use Politizr\Model\PDDebate;
use Politizr\Model\PDReaction;
use Politizr\Model\PTTagType;
use Politizr\Model\PUTaggedT;
use Politizr\Model\PUFollowT;
use Politizr\Model\PRBadgeMetal;
use Politizr\Model\PQType;
use Politizr\Model\PUCurrentQO;
use Politizr\Model\PUMandate;

use Politizr\FrontBundle\Form\Type\PUserBiographyType;

use Politizr\FrontBundle\Form\Type\PUserIdentityType;
use Politizr\FrontBundle\Form\Type\PUserEmailType;
use Politizr\FrontBundle\Form\Type\PUserConnectionType;
use Politizr\FrontBundle\Form\Type\PUCurrentQOType;
use Politizr\FrontBundle\Form\Type\PUMandateType;

/**
 * Gestion profil débatteur
 *
 * TODO:
 *  - gestion des erreurs / levés d'exceptions à revoir/blindés pour les appels Ajax
 *  - refactorisation pour réduire les doublons de code entre les tables PUTaggedT et PUFollowT
 *  - refactoring gestion des tags > gestion des doublons / admin + externalisation logique métier dans les *Query class
 *
 * @author Lionel Bouzonville
 */
class ProfileEController extends Controller
{


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
     *  Suggestions
     */
    public function suggestionsAction()
    {
        $logger = $this->get('logger');
        $logger->info('*** suggestionsAction');

        // Récupération user courant
        $user = $this->getUser();

        $debates = $user->getTaggedDebates();
        $users = $user->getTaggedPUsers();

        return $this->render('PolitizrFrontBundle:ProfileE:suggestions.html.twig', array(
            'debates' => $debates,
            'users' => $users,
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
        $debateDrafts = PDDebateQuery::create()->filterByPUserId($pUser->getId())->filterByPublished(false)->orderByCreatedAt('desc')->find();

        // Réactions brouillons en attente de finalisation
        $reactionDrafts = PDReactionQuery::create()->filterByPUserId($pUser->getId())->filterByPublished(false)->orderByCreatedAt('desc')->find();

        // Débats rédigés
        $debates = PDDebateQuery::create()->filterByPUserId($pUser->getId())->online()->orderByPublishedAt('desc')->find();

        // Réactions rédigées
        $reactions = PDReactionQuery::create()->filterByPUserId($pUser->getId())->online()->orderByPublishedAt('desc')->find();

        // Commentaires rédigés
        $comments = PDCommentQuery::create()->filterByPUserId($pUser->getId())->online()->orderByPublishedAt('desc')->find();

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
        $reputationHistory = PUReputationQuery::create()
                                ->filterByPUserId($user->getId())
                                ->orderByCreatedAt(\Criteria::DESC)
                                ->find();
        
        // badges
        $badgesGold = PRBadgeQuery::create()
                        ->filterByPRBadgeMetalId(PRBadgeMetal::GOLD)
                        ->filterByOnline(true)
                        ->usePRBadgeTypeQuery()
                            ->orderByRank()
                        ->endUse()
                        ->find();
        $badgesSilver = PRBadgeQuery::create()
                        ->filterByPRBadgeMetalId(PRBadgeMetal::SILVER)
                        ->filterByOnline(true)
                        ->usePRBadgeTypeQuery()
                            ->orderByRank()
                        ->endUse()
                        ->find();
        $badgesBronze = PRBadgeQuery::create()
                        ->filterByPRBadgeMetalId(PRBadgeMetal::BRONZE)
                        ->filterByOnline(true)
                        ->usePRBadgeTypeQuery()
                            ->orderByRank()
                        ->endUse()
                        ->find();

        // ids des badges du user
        $badgeIds = array();
        $badgeIds = PUBadgeQuery::create()
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
     *  Mon compte - Mon profil
     */
    public function myProfileAction()
    {
        $logger = $this->get('logger');
        $logger->info('*** myProfileAction');

        // User courant
        $user = $this->getUser();

        // Photos profil
        $backFileName = $user->getBackFileName();
        $fileName = $user->getFileName();

        // Organisation courante
        $puCurrentQo = PUCurrentQOQuery::create()
            ->filterByPUserId($user->getId())
            ->usePUCurrentQOPQOrganizationQuery()
                ->filterByPQTypeId(PQType::ID_ELECTIF)
            ->endUse()
            ->findOne();

        if (!$puCurrentQo) {
            $puCurrentQo = new PUCurrentQO();
            $puCurrentQo->setPUserId($user->getId());
        }

        // Mandats
        $formMandateViews = $this->get('politizr.service.user')->getFormMandateViews($user->getId());

        // Form vierge pour création mandat
        $mandate = new PUMandate();
        $mandate->setPUserId($user->getId());
        $mandate->setPQTypeId(PQType::ID_ELECTIF);

        // *********************************** //
        //      Formulaires
        // *********************************** //
        $formBio = $this->createForm(new PUserBiographyType($user), $user);
        $formOrga = $this->createForm(new PUCurrentQOType(PQType::ID_ELECTIF), $puCurrentQo);
        $formMandate = $this->createForm(new PUMandateType(PQType::ID_ELECTIF), $mandate);

        // *********************************** //
        //      Affichage de la vue
        // *********************************** //
        return $this->render('PolitizrFrontBundle:ProfileE:myProfile.html.twig', array(
                        'user' => $user,
                        'backFileName' => $backFileName,
                        'fileName' => $fileName,
                        'formBio' => $formBio->createView(),
                        'formOrga' => $formOrga->createView(),
                        'formMandate' => $formMandate->createView(),
                        'formMandateViews' => $formMandateViews,
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

        // *********************************** //
        //      Formulaires
        // *********************************** //
        $formPerso1 = $this->createForm(new PUserIdentityType($user), $user);
        $formPerso2 = $this->createForm(new PUserEmailType(), $user);
        $formPerso3 = $this->createForm(new PUserConnectionType(), $user);

        // *********************************** //
        //      Affichage de la vue
        // *********************************** //
        return $this->render('PolitizrFrontBundle:ProfileE:myPerso.html.twig', array(
                        'user' => $user,
                        'formPerso1' => $formPerso1->createView(),
                        'formPerso2' => $formPerso2->createView(),
                        'formPerso3' => $formPerso3->createView()
            ));
    }

    /**
     *  Gestion des notifications par email
     */
    public function myNotificationsAction()
    {
        $logger = $this->get('logger');
        $logger->info('*** myNotificationsAction');


        // Récupération user courant
        $user = $this->getUser();

        // Récupération liste des notifications
        $notifications = PNotificationQuery::create()
                        ->filterByOnline(true)
                        ->orderById()
                        ->find();

        // ids des notifs email du user
        $emailNotifIds = array();
        $emailNotifIds = PUSubscribeEmailQuery::create()
                        ->select('PNotificationId')
                        ->filterByPUserId($user->getId())
                        ->find();

        // *********************************** //
        //      Affichage de la vue
        // *********************************** //
        return $this->render('PolitizrFrontBundle:ProfileE:myNotifications.html.twig', array(
                        'notifications' => $notifications,
                        'emailNotifIds' => $emailNotifIds,
            ));

    }


    /* ######################################################################################################## */
    /*                                                  FONCTIONS AJAX                                          */
    /* ######################################################################################################## */



    /* ######################################################################################################## */
    /*                                                  FONCTIONS PRIVÉES                                             */
    /* ######################################################################################################## */


    /**
     * Gestion de la pagination
     *
     * @param type $query
     * @return \Pagerfanta\Pagerfanta
     * @throws type
     */
    private function preparePagination($query, $maxPerPage = 5)
    {
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
