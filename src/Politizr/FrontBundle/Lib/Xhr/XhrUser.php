<?php
namespace Politizr\FrontBundle\Lib\Xhr;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\EventDispatcher\GenericEvent;

use StudioEcho\Lib\StudioEchoUtils;

use Politizr\Exception\InconsistentDataException;
use Politizr\Exception\FormValidationException;

use Politizr\Constant\ObjectTypeConstants;
use Politizr\Constant\QualificationConstants;
use Politizr\Constant\PathConstants;
use Politizr\Constant\ReputationConstants;
use Politizr\Constant\ListingConstants;

use Politizr\Model\PUser;
use Politizr\Model\PUCurrentQO;
use Politizr\Model\PUMandate;

use Politizr\Model\PRBadgeQuery;
use Politizr\Model\PUBadgeQuery;
use Politizr\Model\PRBadgeTypeQuery;
use Politizr\Model\PUserQuery;
use Politizr\Model\PUReputationQuery;
use Politizr\Model\PUMandateQuery;

use Politizr\FrontBundle\Form\Type\PUserIdentityType;
use Politizr\FrontBundle\Form\Type\PUserEmailType;
use Politizr\FrontBundle\Form\Type\PUserBiographyType;
use Politizr\FrontBundle\Form\Type\PUserConnectionType;
use Politizr\FrontBundle\Form\Type\PUCurrentQOType;
use Politizr\FrontBundle\Form\Type\PUMandateType;
use Politizr\FrontBundle\Form\Type\PUserAffinitiesType;
use Politizr\FrontBundle\Form\Type\PUserBackPhotoInfoType;

/**
 * XHR service for user management.
 *
 * @author Lionel Bouzonville
 */
class XhrUser
{
    private $securityTokenStorage;
    private $encoderFactory;
    private $kernel;
    private $eventDispatcher;
    private $templating;
    private $formFactory;
    private $emailCanonicalizer;
    private $userManager;
    private $reputationManager;
    private $timelineService;
    private $globalTools;
    private $logger;

    /**
     *
     * @param @security.token_storage
     * @param @security.encoder_factory
     * @param @kernel
     * @param @event_dispatcher
     * @param @templating
     * @param @form.factory
     * @param @fos_user.util.email_canonicalizer
     * @param @politizr.manager.user
     * @param @politizr.manager.reputation
     * @param @politizr.functional.timeline
     * @param @politizr.tools.global
     * @param @logger
     */
    public function __construct(
        $securityTokenStorage,
        $encoderFactory,
        $kernel,
        $eventDispatcher,
        $templating,
        $formFactory,
        $emailCanonicalizer,
        $userManager,
        $reputationManager,
        $timelineService,
        $globalTools,
        $logger
    ) {
        $this->securityTokenStorage = $securityTokenStorage;
        $this->encoderFactory = $encoderFactory;

        $this->kernel = $kernel;

        $this->eventDispatcher = $eventDispatcher;

        $this->templating = $templating;
        $this->formFactory = $formFactory;

        $this->emailCanonicalizer = $emailCanonicalizer;

        $this->userManager = $userManager;
        $this->reputationManager = $reputationManager;
        $this->timelineService = $timelineService;

        $this->globalTools = $globalTools;

        $this->logger = $logger;
    }

    /* ######################################################################################################## */
    /*                                                  FOLLOWING                                               */
    /* ######################################################################################################## */

    /**
     * Follow/Unfollow a user by current user
     */
    public function follow(Request $request)
    {
        $this->logger->info('*** follow');
        
        // Request arguments
        $id = $request->get('subjectId');
        $this->logger->info('$id = ' . print_r($id, true));
        $way = $request->get('way');
        $this->logger->info('$way = ' . print_r($way, true));

        // Function process
        $user = $this->securityTokenStorage->getToken()->getUser();
        if ($way == 'follow') {
            $targetUser = PUserQuery::create()->findPk($id);
            $this->userManager->createUserFollowUser($user->getId(), $targetUser->getId());

            // Events
            $event = new GenericEvent($targetUser, array('user_id' => $user->getId(),));
            $dispatcher = $this->eventDispatcher->dispatch('r_user_follow', $event);
            $event = new GenericEvent($targetUser, array('author_user_id' => $user->getId(),));
            $dispatcher = $this->eventDispatcher->dispatch('n_user_follow', $event);
            $event = new GenericEvent($targetUser, array('author_user_id' => $user->getId(), 'target_user_id' => $targetUser->getId()));
            $dispatcher = $this->eventDispatcher->dispatch('b_user_follow', $event);
        } elseif ($way == 'unfollow') {
            $targetUser = PUserQuery::create()->findPk($id);
            $this->userManager->deleteUserFollowUser($user->getId(), $targetUser->getId());

            // Events
            $event = new GenericEvent($targetUser, array('user_id' => $user->getId(),));
            $dispatcher = $this->eventDispatcher->dispatch('r_user_unfollow', $event);
        }

        // Rendering
        $html = $this->templating->render(
            'PolitizrFrontBundle:Follow:_subscribe.html.twig',
            array(
                'object' => $targetUser,
                'type' => ObjectTypeConstants::TYPE_USER
            )
        );

        return array(
            'html' => $html,
            );
    }


    /* ######################################################################################################## */
    /*                                                  USER EDITION                                            */
    /* ######################################################################################################## */

    /**
     * Profile update
     */
    public function userProfileUpdate(Request $request)
    {
        $this->logger->info('*** userProfileUpdate');
        
        // Function process
        $user = $this->securityTokenStorage->getToken()->getUser();
        $form = $this->formFactory->create(new PUserBiographyType($user), $user);
        $form->bind($request);
        if ($form->isValid()) {
            $userProfile = $form->getData();
            $userProfile->save();
        } else {
            $errors = StudioEchoUtils::getAjaxFormErrors($form);
            throw new FormValidationException($errors);
        }

        return true;
    }

    /**
     * Update user back photo info
     */
    public function userBackPhotoInfoUpdate(Request $request)
    {
        $this->logger->info('*** userBackPhotoInfoUpdate');
        
        // Function process
        $user = $this->securityTokenStorage->getToken()->getUser();

        $form = $this->formFactory->create(new PUserBackPhotoInfoType(), $user);

        // Retrieve actual file name
        $oldFileName = $user->getBackFileName();

        $form->bind($request);
        if ($form->isValid()) {
            $user = $form->getData();
            $user->save();

            // Remove old file if new upload or deletion has been done
            $fileName = $user->getBackFileName();
            if ($fileName != $oldFileName) {
                $path = $this->kernel->getRootDir() . '/../web' . PathConstants::USER_UPLOAD_WEB_PATH;
                if ($oldFileName && $fileExists = file_exists($path . $oldFileName)) {
                    unlink($path . $oldFileName);
                }
            }
        } else {
            $errors = StudioEchoUtils::getAjaxFormErrors($form);
            throw new FormValidationException($errors);
        }

        // Rendering
        $path = '/bundles/politizrfront/images/default_user_back.jpg';
        if ($user && $fileName = $user->getBackFileName()) {
            $path = PathConstants::USER_UPLOAD_WEB_PATH.$fileName;
        }
        $imageHeader = $this->templating->render(
            'PolitizrFrontBundle:User:_imageHeader.html.twig',
            array(
                'title' => $user->__toString(),
                'path' => $path,
                'filterName' => 'user_bio_back',
            )
        );

        return array(
            'imageHeader' => $imageHeader,
            'copyright' => $user->getCopyright(),
            );
    }


    /**
     * User's photo upload
     */
    public function userPhotoUpload(Request $request)
    {
        $this->logger->info('*** userPhotoUpload');
        
        // Function process
        $user = $this->securityTokenStorage->getToken()->getUser();
        $path = $this->kernel->getRootDir() . '/../web' . PathConstants::USER_UPLOAD_WEB_PATH;

        // XHR upload
        $fileName = $this->globalTools->uploadXhrImage(
            $request,
            'fileName',
            $path,
            150,
            150
        );

        // Suppression photo déjà uploadée
        $oldFilename = $user->getFilename();
        if ($oldFilename && $fileExists = file_exists($path . $oldFilename)) {
            unlink($path . $oldFilename);
        }

        // MAJ du modèle
        $user->setFilename($fileName);
        $user->save();

        $path = 'bundles/politizrfront/images/profil_default.png';
        if ($user && $fileName = $user->getFileName()) {
            $path = 'uploads/users/'.$fileName;
        }

        // Rendering
        $html = $this->templating->render(
            'PolitizrFrontBundle:User:_photo.html.twig',
            array(
                'user' => $user,
                'path' => $path,
                'filterName' => 'user_bio',
            )
        );

        return array(
            'fileName' => $fileName,
            'html' => $html,
            );
    }

    /**
     * Users's photo deletion
     */
    public function userPhotoDelete(Request $request)
    {
        $this->logger->info('*** userPhotoDelete');
        
        // Function process
        $user = $this->securityTokenStorage->getToken()->getUser();
        $path = $this->kernel->getRootDir() . '/../web' . PathConstants::USER_UPLOAD_WEB_PATH;

        // Suppression photo déjà uploadée
        $filename = $user->getFilename();
        if ($filename && $fileExists = file_exists($path . $filename)) {
            unlink($path . $filename);
        }

        // MAJ du modèle
        $user->setFilename(null);
        $user->save();

        // Rendering
        $path = 'bundles/politizrfront/images/profil_default.png';
        $html = $this->templating->render(
            'PolitizrFrontBundle:User:_photo.html.twig',
            array(
                'user' => $user,
                'path' => $path,
                'filterName' => 'user_bio',
            )
        );

        return array(
            'html' => $html,
            );
    }

    /**
     * User's background photo upload
     */
    public function userBackPhotoUpload(Request $request)
    {
        $this->logger->info('*** userBackPhotoUpload');

        // Request arguments
        $id = $request->get('id');
        $this->logger->info(print_r($id, true));

        // get current user & args
        $user = $this->securityTokenStorage->getToken()->getUser();
        $uploadWebPath = PathConstants::USER_UPLOAD_WEB_PATH;

        // Chemin des images
        $path = $this->kernel->getRootDir() . '/../web' . $uploadWebPath;

        // Appel du service d'upload ajax
        $fileName = $this->globalTools->uploadXhrImage(
            $request,
            'backFileName',
            $path,
            1024,
            1024
        );

        // Rendering
        $html = $this->templating->render(
            'PolitizrFrontBundle:User:_imageHeader.html.twig',
            array(
                'path' => $uploadWebPath . $fileName,
                'filterName' => 'user_bio_back',
                'title' => $user->__toString(),
            )
        );

        return array(
            'fileName' => $fileName,
            'html' => $html,
            );
    }

    /**
     * User's background photo deletion
     */
    public function userBackPhotoDelete(Request $request)
    {
        $this->logger->info('*** userPhotoDelete');
        
        // Function process
        $user = $this->securityTokenStorage->getToken()->getUser();
        $path = $this->kernel->getRootDir() . '/../web' . PathConstants::USER_UPLOAD_WEB_PATH;

        // Suppression photo déjà uploadée
        $filename = $user->getBackFilename();
        if ($filename && $fileExists = file_exists($path . $filename)) {
            unlink($path . $filename);
        }

        // MAJ du modèle
        $user->setBackFilename(null);
        $user->save();

        return true;
    }

    /**
     * User's current organization update
     */
    public function orgaProfileUpdate(Request $request)
    {
        $this->logger->info('*** orgaProfileUpdate');

        // Function process
        $user = $this->securityTokenStorage->getToken()->getUser();

        // get current linked user's organization
        $puCurrentQo = $user->getPUCurrentQO();
        if (!$puCurrentQo) {
            $puCurrentQo = new PUCurrentQO();
        }

        $form = $this->formFactory->create(new PUCurrentQOType(QualificationConstants::TYPE_ELECTIV), $puCurrentQo);
        $form->bind($request);
        if ($form->isValid()) {
            $puCurrentQo = $form->getData();
            $puCurrentQo->save();
        } else {
            $errors = StudioEchoUtils::getAjaxFormErrors($form);
            throw new FormValidationException($errors);
        }

        return true;
    }

    /**
     * User's affinities organizations update
     */
    public function affinitiesProfile(Request $request)
    {
        $this->logger->info('*** affinitiesProfile');

        // Function process
        $user = $this->securityTokenStorage->getToken()->getUser();
        $form = $this->formFactory->create(new PUserAffinitiesType(QualificationConstants::TYPE_ELECTIV), $user);
        $form->bind($request);
        if ($form->isValid()) {
            $user = $form->getData();
            $user->save();
        } else {
            $errors = StudioEchoUtils::getAjaxFormErrors($form);
            throw new FormValidationException($errors);
        }

        return true;
    }

    /**
     * User's mandate creation
     */
    public function mandateProfileCreate(Request $request)
    {
        $this->logger->info('*** mandateProfileCreate');

        // Function process
        $user = $this->securityTokenStorage->getToken()->getUser();

        $form = $this->formFactory->create(new PUMandateType(QualificationConstants::TYPE_ELECTIV), new PUMandate());
        $form->bind($request);
        if ($form->isValid()) {
            $mandate = $form->getData();
            $mandate->save();
        } else {
            $errors = StudioEchoUtils::getAjaxFormErrors($form);
            throw new FormValidationException($errors);
        }

        // New empty form
        $mandate = new PUMandate();
        $mandate->setPUserId($user->getId());
        $mandate->setPQTypeId(QualificationConstants::TYPE_ELECTIV);

        $form = $this->formFactory->create(new PUMandateType(QualificationConstants::TYPE_ELECTIV), $mandate);

        // @todo to refactor
        $formMandateViews = $this->globalTools->getFormMandateViews($user->getId());

        // Rendering
        $newMandate = $this->templating->render(
            'PolitizrFrontBundle:User:_newMandate.html.twig',
            array(
                'formMandate' => $form->createView()
            )
        );

        // Rendering
        $editMandates = $this->templating->render(
            'PolitizrFrontBundle:User:_editMandates.html.twig',
            array(
                'formMandateViews' => $formMandateViews
            )
        );

        return array(
            'newMandate' => $newMandate,
            'editMandates' => $editMandates,
            );
    }

    /**
     * User's mandate update
     */
    public function mandateProfileUpdate(Request $request)
    {
        $this->logger->info('*** mandateProfileCreate');

        // Request arguments
        $id = $request->get('mandate')['id'];
        $this->logger->info('$id = ' . print_r($id, true));

        // Function process
        $user = $this->securityTokenStorage->getToken()->getUser();
        $mandate = PUMandateQuery::create()->findPk($id);

        $form = $this->formFactory->create(new PUMandateType(QualificationConstants::TYPE_ELECTIV), $mandate);
        $form->bind($request);
        if ($form->isValid()) {
            $mandate = $form->getData();
            $mandate->save();
        } else {
            $errors = StudioEchoUtils::getAjaxFormErrors($form);
            throw new FormValidationException($errors);
        }

        // @todo to refactor
        $formMandateViews = $this->globalTools->getFormMandateViews($user->getId());

        // Rendering
        $editMandates = $this->templating->render(
            'PolitizrFrontBundle:User:_editMandates.html.twig',
            array(
                'formMandateViews' => $formMandateViews
            )
        );

        return array(
            'editMandates' => $editMandates,
            );
    }

    /**
     * User's mandate deletion
     */
    public function mandateProfileDelete(Request $request)
    {
        $this->logger->info('*** mandateProfileDelete');
        
        // Request arguments
        $id = $request->get('id');
        $this->logger->info('$id = ' . print_r($id, true));

        // Function process
        $user = $this->securityTokenStorage->getToken()->getUser();
        $mandate = PUMandateQuery::create()->findPk($id);

        // @todo valid ownership of mandate before deletion
        $this->userManager->deleteMandate($mandate);

        // @todo to refactor
        $formMandateViews = $this->globalTools->getFormMandateViews($user->getId());

        // Rendering
        $editMandates = $this->templating->render(
            'PolitizrFrontBundle:User:_editMandates.html.twig',
            array(
                'formMandateViews' => $formMandateViews
            )
        );

        return array(
            'editMandates' => $editMandates,
            );
    }

    /**
     * User's personal information update
     */
    public function userPersoUpdate(Request $request)
    {
        $this->logger->info('*** userPersoUpdate');

        // Request arguments
        $formTypeId = $request->get('user')['form_type_id'];
        $this->logger->info('$formTypeId = '.print_r($formTypeId, true));

        // Function process
        $user = $this->securityTokenStorage->getToken()->getUser();

        // @todo use form type constant
        if ($formTypeId == 1) {
            $form = $this->formFactory->create(new PUserIdentityType($user), $user);
        } elseif ($formTypeId == 2) {
            $form = $this->formFactory->create(new PUserEmailType(), $user);
        } elseif ($formTypeId == 3) {
            $form = $this->formFactory->create(new PUserConnectionType(), $user);
        } else {
            throw new InconsistentDataException(sprintf('Invalid form type %s', $formTypeId));
        }

        $form->bind($request);
        if ($form->isValid()) {
            $userPerso = $form->getData();
            $userPerso->save();

            // @todo use form type constant
            if ($formTypeId == 1) {
                // @todo migrate to puser->preSave
                $user->setNickname($userPerso->getFirstname() . ' ' . $userPerso->getName());
                $user->setRealname($userPerso->getFirstname() . ' ' . $userPerso->getName());
                $user->save();
            } elseif ($formTypeId == 2) {
                // @todo migrate to puser->preSave
                $user->setEmailCanonical($this->emailCanonicalizer->canonicalize($userPerso->getEmail()));
                $user->save();
            } elseif ($formTypeId == 3) {
                // @todo migrate to puser->preSave
                $password = $userPerso->getPassword();
                if ($password) {
                    $encoder = $this->encoderFactory->getEncoder($user);
                    $user->setPassword($encoder->encodePassword($password, $user->getSalt()));
                    $user->setPlainPassword($password);
                    $user->save();

                    // Envoi email
                    $dispatcher = $this->eventDispatcher->dispatch('upd_password_email', new GenericEvent($user));
                }
            }
        } else {
            $errors = StudioEchoUtils::getAjaxFormErrors($form);
            throw new FormValidationException($errors);
        }

        return true;
    }

    /* ######################################################################################################## */
    /*                                                REPUTATION                                                */
    /* ######################################################################################################## */

    /**
     * User's reputation listing
     */
    public function reputation(Request $request)
    {
        $this->logger->info('*** reputation');

        // Récupération user courant
        $user = $this->securityTokenStorage->getToken()->getUser();

        // score de réputation
        $reputationScore = $user->getReputationScore();

        // badges
        $badgesType = PRBadgeTypeQuery::create()
                        ->orderByRank()
                        ->find();

        // ids des badges du user
        $badgeIds = array();
        $badgeIds = PUBadgeQuery::create()
                        ->filterByPUserId($user->getId())
                        ->find()
                        ->toKeyValue('PRBadgeId', 'PRBadgeId');
        $badgeIds = array_keys($badgeIds);

        // Rendering
        $html = $this->templating->render(
            'PolitizrFrontBundle:Reputation:_detail.html.twig',
            array(
                'reputationScore' => $reputationScore,
                'badgesType' => $badgesType,
                'badgeIds' => $badgeIds,
            )
        );

        return array(
            'html' => $html,
            );
    }

    /**
     * User's reputation evolution datas for chart JS
     * Display the max last 30 actions
     * @todo smart management of startAt argument?
     */
    public function reputationEvolution(Request $request)
    {
        $this->logger->info('*** reputationEvolution');

        // Request arguments
        $jsStartAt = $request->get('startAt');
        $this->logger->info('$jsStartAt = ' . print_r($jsStartAt, true));

        // First & last day of month
        $startAt = new \DateTime($jsStartAt);
        $endAt = new \DateTime($jsStartAt);
        $endAt->modify('+1 month');

        $user = $this->securityTokenStorage->getToken()->getUser();
        $reputationByDates = $this->reputationManager->getReputationByDates($user->getId(), $startAt, $endAt);

        // Score evolution by date
        $score = $user->getReputationScore($startAt);

        $labels = [];
        $data = [];
        
        $interval = \DateInterval::createFromDateString('1 day');
        $period = new \DatePeriod($startAt, $interval, $endAt);
        foreach ($period as $day) {
            $find = false;
            foreach ($reputationByDates as $reputationByDate) {
                if ($day->format('Y-m-d') == $reputationByDate['created_at']) {
                    $score += $reputationByDate['sum_score'];
                    $find = true;
                    break;
                }
            }
            $data[] = $score;
            $labels[] = $day->format('d/m/Y');
        }

        // delete first / last labels for pagination
        $labels[0] = "";
        $labels[sizeof($labels) -1] = "";

        // next / prev
        $nextMonth = $endAt;
        $prevMonth = new \DateTime($jsStartAt);
        $prevMonth->modify('-1 month');

        return array(
            'labels' => $labels,
            'data' => $data,
            'datePrev' => $prevMonth->format('Y-m-d'),
            'dateNext' => $nextMonth->format('Y-m-d'),
        );
    }


    /* ######################################################################################################## */
    /*                                                TIMELINE                                                  */
    /* ######################################################################################################## */

    /**
     * Personal's user timeline "My Politizr"
     */
    public function timelinePaginated(Request $request)
    {
        $this->logger->info('*** timelinePaginated');

        // Request arguments
        $offset = $request->get('offset');
        $this->logger->info('$offset = ' . print_r($offset, true));

        // Function process
        $user = $this->securityTokenStorage->getToken()->getUser();
        
        $timeline = $this->timelineService->generateMyPolitizrTimeline($offset);

        // @todo use constant for "limit"
        $moreResults = false;
        if (sizeof($timeline) == 10) {
            $moreResults = true;
        }

        $timelineDateKey = $this->timelineService->generateTimelineDateKey($timeline);

        $html = $this->templating->render(
            'PolitizrFrontBundle:Timeline:_paginatedTimeline.html.twig',
            array(
                'timelineDateKey' => $timelineDateKey,
                'offset' => intval($offset) + 10,
                'moreResults' => $moreResults,
            )
        );

        return array(
            'html' => $html,
        );
    }

    /**
     * User's timeline (user's detail)
     */
    public function timelineUserPaginated(Request $request)
    {
        $this->logger->info('*** timelineUserPaginated');

        // Request arguments
        $offset = $request->get('offset');
        $this->logger->info('$offset = ' . print_r($offset, true));
        $userId = $request->get('userId');
        $this->logger->info('$userId = ' . print_r($userId, true));

        $timeline = $this->timelineService->generateUserDetailTimeline($userId, $offset);

        // @todo use constant for "limit"
        $moreResults = false;
        if (sizeof($timeline) == 10) {
            $moreResults = true;
        }

        $timelineDateKey = $this->timelineService->generateTimelineDateKey($timeline);

        $html = $this->templating->render(
            'PolitizrFrontBundle:Timeline:_paginatedUserTimeline.html.twig',
            array(
                'timelineDateKey' => $timelineDateKey,
                'offset' => intval($offset) + 10,
                'userId' => $userId,
                'moreResults' => $moreResults,
            )
        );

        return array(
            'html' => $html,
        );
    }
}
