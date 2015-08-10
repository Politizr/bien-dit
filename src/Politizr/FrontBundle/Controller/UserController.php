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

use Politizr\Model\PUserQuery;
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
 * User controller
 *
 * @author Lionel Bouzonville
 */
class UserController extends Controller
{

    /* ######################################################################################################## */
    /*                                                    DISPLAY                                               */
    /* ######################################################################################################## */

    /**
     * Detail
     */
    public function detailAction($slug)
    {
        $logger = $this->get('logger');
        $logger->info('*** detailAction');
        $logger->info('$slug = '.print_r($slug, true));

        $user = PUserQuery::create()->filterBySlug($slug)->findOne();
        if (!$user) {
            throw new NotFoundHttpException('User "'.$slug.'" not found.');
        }
        if (!$user->getOnline()) {
            throw new NotFoundHttpException('User "'.$slug.'" not online.');
        }

        $user->setNbViews($user->getNbViews() + 1);
        $user->save();

        return $this->render('PolitizrFrontBundle:User:detail.html.twig', array(
            'profileSuffix' => $this->get('politizr.tools.global')->computeProfileSuffix(),
            'user' => $user,
        ));
    }

    /**
     * Profile
     */
    public function profileAction()
    {
        $logger = $this->get('logger');
        $logger->info('*** profileAction');

        $user = $this->getUser();

        return $this->render('PolitizrFrontBundle:User:profile.html.twig', array(
            'profileSuffix' => $this->get('politizr.tools.global')->computeProfileSuffix(),
            'user' => $user,
        ));
    }

    /* ######################################################################################################## */
    /*                                                    TIMELINE                                              */
    /* ######################################################################################################## */

    /**
     * Homepage
     */
    public function homepageAction()
    {
        $logger = $this->get('logger');
        $logger->info('*** homepageAction');

        return $this->redirect($this->generateUrl(sprintf('Timeline%s', $this->get('politizr.tools.global')->computeProfileSuffix())));
    }

    /**
     * Timeline
     */
    public function timelineAction()
    {
        $logger = $this->get('logger');
        $logger->info('*** timelineAction');

        return $this->render('PolitizrFrontBundle:Timeline:user.html.twig', array(
            'profileSuffix' => $this->get('politizr.tools.global')->computeProfileSuffix(),
        ));
    }


    /* ######################################################################################################## */
    /*                                                 CONTRIBUTIONS                                            */
    /* ######################################################################################################## */

    /**
     * Contributions
     */
    public function contributionAction()
    {
        $logger = $this->get('logger');
        $logger->info('*** contributionAction');

        $user = $this->getUser();

        // Débats rédigés
        $debates = PDDebateQuery::create()->filterByPUserId($user->getId())->online()->orderByPublishedAt('desc')->find();

        // Réactions rédigées
        $reactions = PDReactionQuery::create()->filterByPUserId($user->getId())->online()->orderByPublishedAt('desc')->find();

        return $this->render('PolitizrFrontBundle:Timeline:contribution.html.twig', array(
            'profileSuffix' => $this->get('politizr.tools.global')->computeProfileSuffix(),
            'debates' => $debates,
            'reactions' => $reactions,
        ));
    }

    /* ######################################################################################################## */
    /*                                                    REPUTATION                                            */
    /* ######################################################################################################## */

    /**
     * Reputation
     */
    public function reputationAction()
    {
        $logger = $this->get('logger');
        $logger->info('*** reputationAction');

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
        return $this->render('PolitizrFrontBundle:Reputation:detail.html.twig', array(
            'profileSuffix' => $this->get('politizr.tools.global')->computeProfileSuffix(),
            'reputationScore' => $reputationScore,
            'badgesGold' => $badgesGold,
            'badgesSilver' => $badgesSilver,
            'badgesBronze' => $badgesBronze,
            'badgeIds' => $badgeIds,
        ));
    }

    /* ######################################################################################################## */
    /*                                                    MON COMPTE                                            */
    /* ######################################################################################################## */

    /**
     * Edit profile
     */
    public function editProfileAction()
    {
        $logger = $this->get('logger');
        $logger->info('*** editProfileAction');

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

        return $this->render('PolitizrFrontBundle:User:editProfile.html.twig', array(
            'profileSuffix' => $this->get('politizr.tools.global')->computeProfileSuffix(),
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
    public function editPersoAction()
    {
        $logger = $this->get('logger');
        $logger->info('*** editPersoAction');

        // Récupération user courant
        $user = $this->getUser();

        // Formulaire
        $formPerso1 = $this->createForm(new PUserIdentityType($user), $user);
        $formPerso2 = $this->createForm(new PUserEmailType(), $user);
        $formPerso3 = $this->createForm(new PUserConnectionType(), $user);

        return $this->render('PolitizrFrontBundle:User:editPerso.html.twig', array(
            'profileSuffix' => $this->get('politizr.tools.global')->computeProfileSuffix(),
            'user' => $user,
            'formPerso1' => $formPerso1->createView(),
            'formPerso2' => $formPerso2->createView(),
            'formPerso3' => $formPerso3->createView()
        ));
    }

    /**
     * Gestion des notifications par email
     */
    public function editNotificationsAction()
    {
        $logger = $this->get('logger');
        $logger->info('*** editNotificationsAction');

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

        return $this->render('PolitizrFrontBundle:User:editNotifications.html.twig', array(
            'profileSuffix' => $this->get('politizr.tools.global')->computeProfileSuffix(),
            'notifications' => $notifications,
            'emailNotifIds' => $emailNotifIds,
        ));
    }
}
