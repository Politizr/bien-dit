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
use Politizr\Model\PDReactionQuery;
use Politizr\Model\PRBadgeQuery;
use Politizr\Model\PUBadgeQuery;
use Politizr\Model\PNotificationQuery;
use Politizr\Model\PUSubscribeEmailQuery;
use Politizr\Model\PUCurrentQOQuery;

use Politizr\Model\PUCurrentQO;
use Politizr\Model\PUMandate;

use Politizr\FrontBundle\Form\Type\PUserIdentityType;
use Politizr\FrontBundle\Form\Type\PUserEmailType;
use Politizr\FrontBundle\Form\Type\PUserBiographyType;
use Politizr\FrontBundle\Form\Type\PUserConnectionType;
use Politizr\FrontBundle\Form\Type\PUCurrentQOType;
use Politizr\FrontBundle\Form\Type\PUMandateType;

/**
 * Common "connected" (citizen + elected) controller
 *
 * @author Lionel Bouzonville
 */
class ConnectedController extends Controller
{
    // add C (for citizen) / E (for elected) to the template name
    protected $profileSuffix;

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

        $this->profileSuffix = $this->get('politizr.tools.global')->computeProfileSuffix();

        return $this->redirect($this->generateUrl(sprintf('Timeline%s', $this->profileSuffix)));
    }

    /**
     *  Timeline
     */
    public function timelineAction()
    {
        $logger = $this->get('logger');
        $logger->info('*** timelineAction');

        $this->profileSuffix = $this->get('politizr.tools.global')->computeProfileSuffix();

        return $this->render(sprintf('PolitizrFrontBundle:Profile%s:timeline.html.twig', $this->profileSuffix), array(
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

        $this->profileSuffix = $this->get('politizr.tools.global')->computeProfileSuffix();

        // Récupération user courant
        $pUser = $this->getUser();

        // Débats brouillons en attente de finalisation
        $debateDrafts = PDDebateQuery::create()->filterByPUserId($pUser->getId())->filterByPublished(false)->orderByCreatedAt('desc')->find();

        // Réactions brouillons en attente de finalisation
        $reactionDrafts = PDReactionQuery::create()->filterByPUserId($pUser->getId())->filterByPublished(false)->orderByCreatedAt('desc')->find();

        // Débats rédigés
        $debates = PDDebateQuery::create()->filterByPUserId($pUser->getId())->online()->orderByPublishedAt('desc')->find();

        // Réactions rédigées
        $reactions = PDReactionQuery::create()->filterByPUserId($pUser->getId())->online()->orderByPublishedAt('desc')->find();

        return $this->render(sprintf('PolitizrFrontBundle:Profile%s:contribDashboard.html.twig', $this->profileSuffix), array(
            'debateDrafts' => $debateDrafts,
            'reactionDrafts' => $reactionDrafts,
            'debates' => $debates,
            'reactions' => $reactions,
        ));
    }

    /**
     *  Mes contributions - Brouillons
     */
    public function myDraftsAction()
    {
        $logger = $this->get('logger');
        $logger->info('*** myDraftsAction');

        $this->profileSuffix = $this->get('politizr.tools.global')->computeProfileSuffix();

        // Récupération user courant
        $pUser = $this->getUser();

        // Débats brouillons en attente de finalisation
        $debateDrafts = PDDebateQuery::create()->filterByPUserId($pUser->getId())->filterByPublished(false)->find();

        // Réactions brouillons en attente de finalisation
        $reactionDrafts = PDReactionQuery::create()->filterByPUserId($pUser->getId())->filterByPublished(false)->find();

        return $this->render(sprintf('PolitizrFrontBundle:Profile%s:myDrafts.html.twig', $this->profileSuffix), array(
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

        $this->profileSuffix = $this->get('politizr.tools.global')->computeProfileSuffix();

        // Récupération user courant
        $pUser = $this->getUser();

        return $this->render(sprintf('PolitizrFrontBundle:Profile%s:myTags.html.twig', $this->profileSuffix), array(
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

        $this->profileSuffix = $this->get('politizr.tools.global')->computeProfileSuffix();

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
        return $this->render(sprintf('PolitizrFrontBundle:Profile%s:myReputation.html.twig', $this->profileSuffix), array(
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

        $this->profileSuffix = $this->get('politizr.tools.global')->computeProfileSuffix();

        // User courant
        $user = $this->getUser();

        // Photos profil
        $backFileName = $user->getBackFileName();
        $fileName = $user->getFileName();

        // Organisation courante
        $puCurrentQo = PUCurrentQOQuery::create()
            ->filterByPUserId($user->getId())
            ->usePUCurrentQOPQOrganizationQuery()
                ->filterByPQTypeId(QualificationConstants::TYPE_ELECTIV)
            ->endUse()
            ->findOne();

        if (!$puCurrentQo) {
            $puCurrentQo = new PUCurrentQO();
            $puCurrentQo->setPUserId($user->getId());
        }

        // Mandats
        $formMandateViews = $this->get('politizr.tools.global')->getFormMandateViews($user->getId());

        // Form vierge pour création mandat
        $mandate = new PUMandate();
        $mandate->setPUserId($user->getId());
        $mandate->setPQTypeId(QualificationConstants::TYPE_ELECTIV);

        // Formulaire
        $formBio = $this->createForm(new PUserBiographyType($user), $user);
        $formOrga = $this->createForm(new PUCurrentQOType(QualificationConstants::TYPE_ELECTIV), $puCurrentQo);
        $formMandate = $this->createForm(new PUMandateType(QualificationConstants::TYPE_ELECTIV), $mandate);

        return $this->render(sprintf('PolitizrFrontBundle:Profile%s:myProfile.html.twig', $this->profileSuffix), array(
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

        $this->profileSuffix = $this->get('politizr.tools.global')->computeProfileSuffix();

        // Récupération user courant
        $user = $this->getUser();

        // Formulaire
        $formPerso1 = $this->createForm(new PUserIdentityType($user), $user);
        $formPerso2 = $this->createForm(new PUserEmailType(), $user);
        $formPerso3 = $this->createForm(new PUserConnectionType(), $user);

        return $this->render(sprintf('PolitizrFrontBundle:Profile%s:myPerso.html.twig', $this->profileSuffix), array(
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

        $this->profileSuffix = $this->get('politizr.tools.global')->computeProfileSuffix();

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

        return $this->render(sprintf('PolitizrFrontBundle:Profile%s:myNotifications.html.twig', $this->profileSuffix), array(
            'notifications' => $notifications,
            'emailNotifIds' => $emailNotifIds,
        ));
    }
}
