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
use Politizr\Model\PNotificationQuery;
use Politizr\Model\PNTypeQuery;
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
use Politizr\FrontBundle\Form\Type\PUserBackPhotoInfoType;

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
    /*                                                    MON COMPTE                                            */
    /* ######################################################################################################## */

    /**
     * Edit profile
     */
    public function editProfileAction()
    {
        $logger = $this->get('logger');
        $logger->info('*** editProfileAction');

        $user = $this->getUser();

        // Biography
        $formBio = $this->createForm(new PUserBiographyType($user), $user);

        // Photos
        $formBackPhotoInfo = $this->createForm(new PUserBackPhotoInfoType(), $user);

        // Dedicated elected fields
        $formOrga = null;
        $formMandate = null;
        $formMandateViews = null;
        if ($this->get('security.authorization_checker')->isGranted('ROLE_ELECTED')) {
            // Current organization
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

            // Mandates form views
            $formMandateViews = $this->get('politizr.tools.global')->getFormMandateViews($user->getId());

            // New mandate
            $mandate = new PUMandate();
            $mandate->setPUserId($user->getId());
            $mandate->setPQTypeId(QualificationConstants::TYPE_ELECTIV);

            // Current organization & new mandate forms
            $formOrga = $this->createForm(new PUCurrentQOType(QualificationConstants::TYPE_ELECTIV), $puCurrentQo);
            $formMandate = $this->createForm(new PUMandateType(QualificationConstants::TYPE_ELECTIV), $mandate);
        }

        return $this->render('PolitizrFrontBundle:User:editProfile.html.twig', array(
            'profileSuffix' => $this->get('politizr.tools.global')->computeProfileSuffix(),
            'user' => $user,
            'formBio' => $formBio->createView(),
            'formBackPhotoInfo' => $formBackPhotoInfo->createView(),
            'formOrga' => $formOrga?$formOrga->createView():null,
            'formMandate' => $formMandate?$formMandate->createView():null,
            'formMandateViews' => $formMandateViews?$formMandateViews:null,
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
     * Edit notifications
     */
    public function editNotificationsAction()
    {
        $logger = $this->get('logger');
        $logger->info('*** editNotificationsAction');

        $user = $this->getUser();

        $notificationsType = PNTypeQuery::create()
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
            'notificationsType' => $notificationsType,
            'emailNotifIds' => $emailNotifIds,
        ));
    }
}
