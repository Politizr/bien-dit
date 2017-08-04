<?php

namespace Politizr\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Politizr\Constant\QualificationConstants;

use Politizr\Model\PUserQuery;
use Politizr\Model\PNEmailQuery;
use Politizr\Model\PUSubscribePNEQuery;
use Politizr\Model\PUCurrentQOQuery;
use Politizr\Model\PEOperationQuery;

use Politizr\Model\PUCurrentQO;
use Politizr\Model\PUMandate;
use Politizr\Model\PUTrackU;

use Politizr\FrontBundle\Form\Type\PUserIdentityType;
use Politizr\FrontBundle\Form\Type\PUserEmailType;
use Politizr\FrontBundle\Form\Type\PUserBiographyType;
use Politizr\FrontBundle\Form\Type\PUserIdCheckType;
use Politizr\FrontBundle\Form\Type\PUserConnectionType;
use Politizr\FrontBundle\Form\Type\PUCurrentQOType;
use Politizr\FrontBundle\Form\Type\PUMandateType;
use Politizr\FrontBundle\Form\Type\PUserBackPhotoInfoType;
use Politizr\FrontBundle\Form\Type\PUserLocalizationType;

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
     * Redirect 301
     * @todo w. htaccess
     */
    public function detailClassicAction($slug)
    {
        return $this->redirect($this->generateUrl('UserDetail', array('slug' => $slug)));
    }

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

        // Tracking
        $visitor = $this->getUser();
        if ($visitor && $visitor->getId() != $user->getId()) {
            $uTracku = new PUTrackU();

            $uTracku->setPUserIdSource($visitor->getId());
            $uTracku->setPUserIdDest($user->getId());

            $uTracku->save();
        }

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

        // Redirect to page before login
        $refererUrl = $this->get('politizr.tools.global')->getRefererUrl();
        if ($refererUrl) {
            return $this->redirect($refererUrl);
        }

        // get actives ops for current user
        $user = $this->getUser();
        $operations = PEOperationQuery::create()
            ->distinct()
            ->filterByOnline(true)
            ->filterByTimeline(true)
            ->filterByGeoScoped(false)
            ->_or()
            ->usePEOScopePLCQuery(null, "LEFT JOIN")
                ->filterByPLCityId($user->getPLCityId())
            ->endUse()
            ->find();

        return $this->render('PolitizrFrontBundle:Timeline:user.html.twig', array(
            'homepage' => true,
            'operations' => $operations
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
     * Account info
     * beta
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
        $formPerso4 = $this->createForm(new PUserLocalizationType($user), $user);

        // $formIdCheck = $this->createForm(new PUserIdCheckType(), $user);

        return $this->render('PolitizrFrontBundle:User:editPerso.html.twig', array(
            'user' => $user,
            'formPerso1' => $formPerso1->createView(),
            'formPerso2' => $formPerso2->createView(),
            'formPerso3' => $formPerso3->createView(),
            'formPerso4' => $formPerso4->createView(),
            // 'formIdCheck' => $formIdCheck->createView(),
        ));
    }

    /**
     * Edit notifications
     * beta
     */
    public function editNotificationsAction()
    {
        $logger = $this->get('logger');
        $logger->info('*** editNotificationsAction');

        $user = $this->getUser();

        $notifications = PNEmailQuery::create()
                        ->orderById()
                        ->find();

        // ids des notifs email du user
        $emailNotifIds = array();
        $emailNotifIds = PUSubscribePNEQuery::create()
                        ->select('PNEmailId')
                        ->filterByPUserId($user->getId())
                        ->find();

        return $this->render('PolitizrFrontBundle:User:editNotifications.html.twig', array(
            'notifications' => $notifications,
            'emailNotifIds' => $emailNotifIds,
        ));
    }

    /* ######################################################################################################## */
    /*                                                      ID CHECK                                            */
    /* ######################################################################################################## */

    /**
     * Id Check / step 1
     */
    public function idCheckDataReviewAction(Request $request)
    {
        $logger = $this->get('logger');
        $logger->info('*** idCheckDataReviewAction');

        $user = $this->getUser();

        // user already validated
        if ($user->isValidated()) {
            $request->getSession()->getFlashBag()->add('idcheck/success', true);
            return $this->redirect($this->generateUrl('Homepage').$this->get('politizr.tools.global')->computeProfileSuffix());
        }

        // id check form
        $formIdentity = $this->createForm(new PUserIdentityType($user), $user);

        return $this->render('PolitizrFrontBundle:User:idCheckDataReview.html.twig', array(
            'formIdentity' => $formIdentity->createView(),
        ));
    }

    /**
     * Id Check / step 1 check
     */
    public function idCheckDataReviewCheckAction(Request $request)
    {
        $logger = $this->get('logger');
        $logger->info('*** idCheckDataReviewCheckAction');

        $user = $this->getUser();

        // user already validated
        if ($user->isValidated()) {
            $request->getSession()->getFlashBag()->add('idcheck/success', true);
            return $this->redirect($this->generateUrl('Homepage').$this->get('politizr.tools.global')->computeProfileSuffix());
        }

        // get current user
        $user = $this->getUser();
        $formIdentity = $this->createForm(new PUserIdentityType($user), $user);

        $formIdentity->bind($request);
        if ($formIdentity->isValid()) {
            $user = $formIdentity->getData();

            $user->setNickname($user->getFirstname() . ' ' . $user->getName());
            $user->setRealname($user->getFirstname() . ' ' . $user->getName());
            $user->save();

            return $this->redirect($this->generateUrl('IdCheckE'));
        }

        return $this->render('PolitizrFrontBundle:User:idCheckDataReview.html.twig', array(
            'formIdentity' => $formIdentity->createView(),
        ));
    }

    /**
     * Id Check / step 2
     */
    public function idCheckAction(Request $request)
    {
        $logger = $this->get('logger');
        $logger->info('*** idCheckAction');

        $user = $this->getUser();

        // user already validated
        if ($user->isValidated()) {
            $request->getSession()->getFlashBag()->add('idcheck/success', true);
            return $this->redirect($this->generateUrl('Homepage').$this->get('politizr.tools.global')->computeProfileSuffix());
        }

        // id check form
        $formIdCheck = $this->createForm(new PUserIdCheckType(), $user);

        return $this->render('PolitizrFrontBundle:User:idCheck.html.twig', array(
            'formIdCheck' => $formIdCheck->createView(),
        ));
    }
}
