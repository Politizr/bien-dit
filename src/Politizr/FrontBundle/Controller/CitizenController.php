<?php

namespace Politizr\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Politizr\Exception\InconsistentDataException;

use Politizr\Constant\QualificationConstants;
use Politizr\Constant\ReputationConstants;

use Politizr\Model\PDDebateQuery;
use Politizr\Model\PRBadgeQuery;
use Politizr\Model\PUBadgeQuery;
use Politizr\Model\PNotificationQuery;
use Politizr\Model\PUSubscribeEmailQuery;
use Politizr\Model\PUAffinityQOQuery;

use Politizr\FrontBundle\Form\Type\PUserIdentityType;
use Politizr\FrontBundle\Form\Type\PUserEmailType;
use Politizr\FrontBundle\Form\Type\PUserBiographyType;
use Politizr\FrontBundle\Form\Type\PUserAffinitiesType;
use Politizr\FrontBundle\Form\Type\PUserConnectionType;

/**
 * Gestion profil citoyen
 *
 * TODO:
 *  - gestion des erreurs / levés d'exceptions à revoir/blindés pour les appels Ajax
 *  - refactorisation pour réduire les doublons de code entre les tables PUTaggedT et PUFollowT
 *  - refactoring gestion des tags > gestion des doublons / admin + externalisation logique métier dans les *Query class
 *  - refactoring > à renommer en CitizenController > contient les fonctions spécifiques au profil citoyen, sinon utiliser ProfileController
 *
 * @author Lionel Bouzonville
 */
class CitizenController extends Controller
{

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
        
        return $this->redirect($this->generateUrl('TimelineC'));
    }

    /**
     *  Timeline
     */
    public function timelineAction()
    {
        $logger = $this->get('logger');
        $logger->info('*** timelineAction');

        return $this->render('PolitizrFrontBundle:ProfileC:timeline.html.twig', array(
            ));
    }

    /**
     *  Trouver des débats
     */
    public function findDebatesAction()
    {
        $logger = $this->get('logger');
        $logger->info('*** findDebatesAction');

        return $this->render('PolitizrFrontBundle:ProfileC:findDebates.html.twig', array(
            ));
    }

    /**
     *  Trouver des users
     */
    public function findUsersAction()
    {
        $logger = $this->get('logger');
        $logger->info('*** findUsersAction');

        return $this->render('PolitizrFrontBundle:ProfileC:findUsers.html.twig', array(
            ));
    }


    /* ######################################################################################################## */
    /*                                                 CONTRIBUTIONS                                            */
    /* ######################################################################################################## */


    /**
     *  Mes contributions - Accueil
     */
    public function contribDashboardAction()
    {
        $logger = $this->get('logger');
        $logger->info('*** contribDashboardAction');

        // Récupération user courant
        $user = $this->getUser();

        // Débats brouillons en attente de finalisation
        $drafts = PDDebateQuery::create()->filterByPUserId($user->getId())->filterByPublished(false)->orderByCreatedAt('desc')->find();

        // Débats rédigés
        $debates = PDDebateQuery::create()->filterByPUserId($user->getId())->online()->orderByPublishedAt('desc')->find();

        return $this->render('PolitizrFrontBundle:ProfileC:contribDashboard.html.twig', array(
            'drafts' => $drafts,
            'debates' => $debates
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

        // Débats brouillons en attente de finalisation
        $drafts = PDDebateQuery::create()->filterByPUserId($pUser->getId())->filterByPublished(false)->find();

        return $this->render('PolitizrFrontBundle:ProfileC:myDrafts.html.twig', array(
            'drafts' => $drafts,
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

        return $this->render('PolitizrFrontBundle:ProfileC:myTags.html.twig', array(
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

        // badges
        $badgesGold = PRBadgeQuery::create()
                        ->filterByPRBadgeMetalId(ReputationConstants::BADGE_METAL_GOLD)
                        ->filterByOnline(true)
                        ->usePRBadgeTypeQuery()
                            ->orderByRank()
                        ->endUse()
                        ->find();
        $badgesSilver = PRBadgeQuery::create()
                        ->filterByPRBadgeMetalId(ReputationConstants::BADGE_METAL_SILVER)
                        ->filterByOnline(true)
                        ->usePRBadgeTypeQuery()
                            ->orderByRank()
                        ->endUse()
                        ->find();
        $badgesBronze = PRBadgeQuery::create()
                        ->filterByPRBadgeMetalId(ReputationConstants::BADGE_METAL_BRONZE)
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
        return $this->render('PolitizrFrontBundle:ProfileC:myReputation.html.twig', array(
            'reputationScore' => $reputationScore,
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

        // Récupération user courant
        $user = $this->getUser();

        // Récupération photos profil
        $backFileName = $user->getBackFileName();
        $fileName = $user->getFileName();

        // Formulaire
        $formBio = $this->createForm(new PUserBiographyType($user), $user);

        // Affinités organisations
        $formAffinities = $this->createForm(new PUserAffinitiesType(QualificationConstants::TYPE_ELECTIV), $user);

        return $this->render('PolitizrFrontBundle:ProfileC:myProfile.html.twig', array(
                        'user' => $user,
                        'formBio' => $formBio->createView(),
                        'formAffinities' => $formAffinities->createView(),
                        'backFileName' => $backFileName,
                        'fileName' => $fileName,
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

        // Formulaire
        $formPerso1 = $this->createForm(new PUserIdentityType($user), $user);
        $formPerso2 = $this->createForm(new PUserEmailType(), $user);
        $formPerso3 = $this->createForm(new PUserConnectionType(), $user);

        return $this->render('PolitizrFrontBundle:ProfileC:myPerso.html.twig', array(
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

        return $this->render('PolitizrFrontBundle:ProfileC:myNotifications.html.twig', array(
                        'notifications' => $notifications,
                        'emailNotifIds' => $emailNotifIds,
            ));

    }

    /**
     *  Gestion des débats suivis
     */
    public function myFollowedDebatesAction()
    {
        $logger = $this->get('logger');
        $logger->info('*** myFollowedDebatesAction');

        return $this->render('PolitizrFrontBundle:ProfileC:myFollowedDebates.html.twig', array(
            ));

    }

    /**
     *  Gestion des profils suivis
     */
    public function myFollowedUsersAction()
    {
        $logger = $this->get('logger');
        $logger->info('*** myFollowedUsersAction');

        return $this->render('PolitizrFrontBundle:ProfileC:myFollowedUsers.html.twig', array(
            ));

    }
}
