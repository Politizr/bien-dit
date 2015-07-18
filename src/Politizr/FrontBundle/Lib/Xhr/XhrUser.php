<?php
namespace Politizr\FrontBundle\Lib\Xhr;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\EventDispatcher\GenericEvent;

use StudioEcho\Lib\StudioEchoUtils;

use Politizr\Exception\InconsistentDataException;
use Politizr\Exception\FormValidationException;

use Politizr\Constant\ObjectTypeConstants;
use Politizr\Constant\QualificationConstants;

use Politizr\Model\PUser;
use Politizr\Model\PUCurrentQO;
use Politizr\Model\PUMandate;

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
     * User's photo upload
     */
    public function userPhotoUpload(Request $request)
    {
        $this->logger->info('*** userPhotoUpload');
        
        // Function process
        $user = $this->securityTokenStorage->getToken()->getUser();
        $path = $this->kernel->getRootDir() . '/../web' . PUser::UPLOAD_WEB_PATH;

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

        return array(
            'filename' => $fileName,
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
        $path = $this->kernel->getRootDir() . '/../web' . PUser::UPLOAD_WEB_PATH;

        // Suppression photo déjà uploadée
        $filename = $user->getFilename();
        if ($filename && $fileExists = file_exists($path . $filename)) {
            unlink($path . $filename);
        }

        // MAJ du modèle
        $user->setFilename(null);
        $user->save();

        return true;
    }

    /**
     * User's background photo upload
     */
    public function userBackPhotoUpload(Request $request)
    {
        $this->logger->info('*** userBackPhotoUpload');
        
        // Function process
        $user = $this->securityTokenStorage->getToken()->getUser();
        $path = $this->kernel->getRootDir() . '/../web' . PUser::UPLOAD_WEB_PATH;

        // Appel du service d'upload ajax
        $fileName = $this->globalTools->uploadXhrImage(
            $request,
            'backFileName',
            $path,
            1280,
            600
        );

        // Suppression photo déjà uploadée
        $oldFilename = $user->getBackFilename();
        if ($oldFilename && $fileExists = file_exists($path . $oldFilename)) {
            unlink($path . $oldFilename);
        }

        // MAJ du modèle
        $user->setBackFilename($fileName);
        $user->save();

        return array(
            'filename' => $fileName,
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
        $path = $this->kernel->getRootDir() . '/../web' . PUser::UPLOAD_WEB_PATH;

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
        $html = $this->templating->render(
            'PolitizrFrontBundle:Fragment\\User:glMandateEdit.html.twig',
            array(
                'formMandate' => $form->createView(),
                'formMandateViews' => $formMandateViews
            )
        );

        return array(
            'html' => $html,
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

        return true;
    }

    /**
     * User's mandate deletion
     */
    public function mandateProfileDelete(Request $request)
    {
        $this->logger->info('*** mandateProfileDelete');
        
        // Request arguments
        $id = $request->get('mandate')['id'];
        $this->logger->info('$id = ' . print_r($id, true));

        // Function process
        $mandate = PUMandateQuery::create()->findPk($id);

        // @todo valid ownership of mandate before deletion
        $this->userManager->deleteMandate($mandate);

        return true;
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
    public function historyActionsList(Request $request)
    {
        $this->logger->info('*** historyActionsList');

        // Request arguments
        $offset = $request->get('offset');
        $this->logger->info('$offset = ' . print_r($offset, true));
        $order = $request->get('order');
        $this->logger->info('$order = ' . print_r($order, true));
        $filters = $request->get('filters');
        $this->logger->info('$filters = ' . print_r($filters, true));

        // Function process
        $user = $this->securityTokenStorage->getToken()->getUser();

        $historyActions = PUReputationQuery::create()
                            ->filterByPUserId($user->getId())
                            ->orderByCreatedAt('desc')
                            ->limit(10)
                            ->offset($offset)
                            ->find();

        // Rendering
        $html = $this->templating->render(
            'PolitizrFrontBundle:Fragment\\Reputation:glListHistoryActions.html.twig',
            array(
                'historyActions' => $historyActions,
                'offset' => intval($offset) + 10,
                )
        );

        return array(
            'html' => $html,
            );
    }

    /* ######################################################################################################## */
    /*                                                TIMELINE                                                  */
    /* ######################################################################################################## */

    /**
     * User's timeline "My Politizr"
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
}
