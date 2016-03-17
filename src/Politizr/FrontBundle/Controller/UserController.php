<?php

namespace Politizr\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Politizr\Constant\QualificationConstants;

use Politizr\Model\PUserQuery;
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
     * beta
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

    /* ######################################################################################################## */
    /*                                                    TIMELINE                                              */
    /* ######################################################################################################## */

    /**
     * Timeline
     * beta
     */
    public function timelineAction()
    {
        $logger = $this->get('logger');
        $logger->info('*** timelineAction');

        return $this->render('PolitizrFrontBundle:Timeline:user.html.twig', array(
            'homepage' => true,
        ));
    }

    /* ######################################################################################################## */
    /*                                                    MON COMPTE                                            */
    /* ######################################################################################################## */

    /**
     * Edit profile
     * beta
     */
    public function editProfileAction()
    {
        $logger = $this->get('logger');
        $logger->info('*** editProfileAction');

        $user = $this->getUser();

        // Biography
        $formBio = $this->createForm(new PUserBiographyType($user), $user);

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
            }

            // Mandates form views
            $formMandateViews = $this->get('politizr.tools.global')->getFormMandateViews($user->getId());

            // New mandate
            $mandate = new PUMandate();
            $mandate->setPQTypeId(QualificationConstants::TYPE_ELECTIV);

            // Current organization & new mandate forms
            $formOrga = $this->createForm(new PUCurrentQOType(QualificationConstants::TYPE_ELECTIV), $puCurrentQo);
            $formMandate = $this->createForm(new PUMandateType(QualificationConstants::TYPE_ELECTIV), $mandate);
        }

        return $this->render('PolitizrFrontBundle:User:editProfile.html.twig', array(
            'user' => $user,
            'formBio' => $formBio->createView(),
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
