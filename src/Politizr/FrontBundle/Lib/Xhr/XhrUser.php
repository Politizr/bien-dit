<?php
namespace Politizr\FrontBundle\Lib\Xhr;

use Symfony\Component\EventDispatcher\GenericEvent;

use StudioEcho\Lib\StudioEchoUtils;

use Politizr\Exception\InconsistentDataException;
use Politizr\Exception\FormValidationException;

use Politizr\Model\PUser;
use Politizr\Model\PDocumentInterface;
use Politizr\Model\PQType;
use Politizr\Model\PUCurrentQO;
use Politizr\Model\PUMandate;

use Politizr\Model\PUserQuery;
use Politizr\Model\PUNotificationQuery;
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
    private $sc;

    /**
     *
     */
    public function __construct($serviceContainer)
    {
        $this->sc = $serviceContainer;
    }

    /* ######################################################################################################## */
    /*                                                  FOLLOWING                                               */
    /* ######################################################################################################## */

    /**
     * Follow/Unfollow a user by current user
     */
    public function follow()
    {
        $logger = $this->sc->get('logger');
        $logger->info('*** follow');
        
        // Retrieve used services
        $request = $this->sc->get('request');
        $securityContext = $this->sc->get('security.context');
        $userManager = $this->sc->get('politizr.manager.user');
        $eventDispatcher = $this->sc->get('event_dispatcher');
        $templating = $this->sc->get('templating');

        // Request arguments
        $id = $request->get('subjectId');
        $logger->info('$id = ' . print_r($id, true));
        $way = $request->get('way');
        $logger->info('$way = ' . print_r($way, true));

        // Function process
        $user = $securityContext->getToken()->getUser();
        if ($way == 'follow') {
            $targetUser = PUserQuery::create()->findPk($id);
            $userManager->createUserFollowUser($user->getId(), $targetUser->getId());

            // Events
            $event = new GenericEvent($targetUser, array('user_id' => $user->getId(),));
            $dispatcher = $eventDispatcher->dispatch('r_user_follow', $event);
            $event = new GenericEvent($targetUser, array('author_user_id' => $user->getId(),));
            $dispatcher = $eventDispatcher->dispatch('n_user_follow', $event);
            $event = new GenericEvent($targetUser, array('author_user_id' => $user->getId(), 'target_user_id' => $targetUser->getId()));
            $dispatcher = $eventDispatcher->dispatch('b_user_follow', $event);
        } elseif ($way == 'unfollow') {
            $targetUser = PUserQuery::create()->findPk($id);
            $userManager->deleteUserFollowUser($user->getId(), $targetUser->getId());

            // Events
            $event = new GenericEvent($targetUser, array('user_id' => $user->getId(),));
            $dispatcher = $eventDispatcher->dispatch('r_user_unfollow', $event);
        }

        // Rendering
        $html = $templating->render(
            'PolitizrFrontBundle:Follow:_subscribe.html.twig',
            array(
                'object' => $targetUser,
                'type' => PDocumentInterface::TYPE_USER
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
    public function userProfileUpdate()
    {
        $logger = $this->sc->get('logger');
        $logger->info('*** userProfileUpdate');
        
        // Retrieve used services
        $request = $this->sc->get('request');
        $securityContext = $this->sc->get('security.context');
        $formFactory = $this->sc->get('form.factory');

        // Function process
        $user = $securityContext->getToken()->getUser();
        $form = $formFactory->create(new PUserBiographyType($user), $user);
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
    public function userPhotoUpload()
    {
        $logger = $this->sc->get('logger');
        $logger->info('*** userPhotoUpload');
        
        // Retrieve used services
        $securityContext = $this->sc->get('security.context');
        $kernel = $this->sc->get('kernel');
        $politizrUtils = $this->sc->get('politizr.utils');

        // Function process
        $user = $securityContext->getToken()->getUser();
        $path = $kernel->getRootDir() . '/../web' . PUser::UPLOAD_WEB_PATH;

        // XHR upload
        $fileName = $politizrUtils->uploadXhrImage(
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
    public function userPhotoDelete()
    {
        $logger = $this->sc->get('logger');
        $logger->info('*** userPhotoDelete');
        
        // Retrieve used services
        $securityContext = $this->sc->get('security.context');
        $kernel = $this->sc->get('kernel');

        // Function process
        $user = $securityContext->getToken()->getUser();
        $path = $kernel->getRootDir() . '/../web' . PUser::UPLOAD_WEB_PATH;

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
    public function userBackPhotoUpload()
    {
        $logger = $this->sc->get('logger');
        $logger->info('*** userBackPhotoUpload');
        
        // Retrieve used services
        $securityContext = $this->sc->get('security.context');
        $kernel = $this->sc->get('kernel');
        $politizrUtils = $this->sc->get('politizr.utils');

        // Function process
        $user = $securityContext->getToken()->getUser();
        $path = $kernel->getRootDir() . '/../web' . PUser::UPLOAD_WEB_PATH;

        // Appel du service d'upload ajax
        $fileName = $politizrUtils->uploadXhrImage(
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
    public function userBackPhotoDelete()
    {
        $logger = $this->sc->get('logger');
        $logger->info('*** userPhotoDelete');
        
        // Retrieve used services
        $securityContext = $this->sc->get('security.context');
        $kernel = $this->sc->get('kernel');

        // Function process
        $user = $securityContext->getToken()->getUser();
        $path = $kernel->getRootDir() . '/../web' . PUser::UPLOAD_WEB_PATH;

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
    public function orgaProfileUpdate()
    {
        $logger = $this->sc->get('logger');
        $logger->info('*** orgaProfileUpdate');

        // Retrieve used services
        $request = $this->sc->get('request');
        $securityContext = $this->sc->get('security.context');
        $formFactory = $this->sc->get('form.factory');

        // Function process
        $user = $securityContext->getToken()->getUser();

        // get current linked user's organization
        $puCurrentQo = $user->getPUCurrentQO();
        if (!$puCurrentQo) {
            $puCurrentQo = new PUCurrentQO();
        }

        $form = $formFactory->create(new PUCurrentQOType(PQType::ID_ELECTIF), $puCurrentQo);
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
    public function affinitiesProfile()
    {
        $logger = $this->sc->get('logger');
        $logger->info('*** affinitiesProfile');

        // Retrieve used services
        $request = $this->sc->get('request');
        $securityContext = $this->sc->get('security.context');
        $formFactory = $this->sc->get('form.factory');

        // Function process
        $user = $securityContext->getToken()->getUser();
        $form = $formFactory->create(new PUserAffinitiesType(PQType::ID_ELECTIF), $user);
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
    public function mandateProfileCreate()
    {
        $logger = $this->sc->get('logger');
        $logger->info('*** mandateProfileCreate');

        // Retrieve used services
        $request = $this->sc->get('request');
        $securityContext = $this->sc->get('security.context');
        $formFactory = $this->sc->get('form.factory');
        $templating = $this->sc->get('templating');
        $politizrUtils = $this->sc->get('politizr.utils');

        // Function process
        $user = $securityContext->getToken()->getUser();

        $form = $formFactory->create(new PUMandateType(PQType::ID_ELECTIF), new PUMandate());
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
        $mandate->setPQTypeId(PQType::ID_ELECTIF);

        $form = $formFactory->create(new PUMandateType(PQType::ID_ELECTIF), $mandate);

        // @todo to refactor
        $formMandateViews = $politizrUtils->getFormMandateViews($user->getId());

        // Rendering
        $html = $templating->render(
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
    public function mandateProfileUpdate()
    {
        $logger = $this->sc->get('logger');
        $logger->info('*** mandateProfileCreate');

        // Retrieve used services
        $request = $this->sc->get('request');
        $formFactory = $this->sc->get('form.factory');

        // Request arguments
        $id = $request->get('mandate')['id'];
        $logger->info('$id = ' . print_r($id, true));

        // Function process
        $mandate = PUMandateQuery::create()->findPk($id);

        $form = $formFactory->create(new PUMandateType(PQType::ID_ELECTIF), $mandate);
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
    public function mandateProfileDelete()
    {
        $logger = $this->sc->get('logger');
        $logger->info('*** mandateProfileDelete');
        
        // Retrieve used services
        $request = $this->sc->get('request');
        $userManager = $this->sc->get('politizr.manager.user');

        // Request arguments
        $id = $request->get('mandate')['id'];
        $logger->info('$id = ' . print_r($id, true));

        // Function process
        $mandate = PUMandateQuery::create()->findPk($id);

        // @todo valid ownership of mandate before deletion
        $userManager->deleteMandate($mandate);

        return true;
    }

    /**
     * User's personal information update
     */
    public function userPersoUpdate()
    {
        $logger = $this->sc->get('logger');
        $logger->info('*** userPersoUpdate');

        // Retrieve used services
        $request = $this->sc->get('request');
        $securityContext = $this->sc->get('security.context');
        $formFactory = $this->sc->get('form.factory');
        $eventDispatcher = $this->sc->get('event_dispatcher');
        $emailCanonicalizer = $this->sc->get('fos_user.util.email_canonicalizer');
        $encoderFactory = $this->sc->get('security.encoder_factory');

        // Request arguments
        $formTypeId = $request->get('user')['form_type_id'];
        $logger->info('$formTypeId = '.print_r($formTypeId, true));

        // Function process
        $user = $securityContext->getToken()->getUser();

        // @todo use form type constant
        if ($formTypeId == 1) {
            $form = $formFactory->create(new PUserIdentityType($user), $user);
        } elseif ($formTypeId == 2) {
            $form = $formFactory->create(new PUserEmailType(), $user);
        } elseif ($formTypeId == 3) {
            $form = $formFactory->create(new PUserConnectionType(), $user);
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
                $user->setEmailCanonical($emailCanonicalizer->canonicalize($userPerso->getEmail()));
                $user->save();
            } elseif ($formTypeId == 3) {
                // @todo migrate to puser->preSave
                $password = $userPerso->getPassword();
                if ($password) {
                    $encoder = $encoderFactory->getEncoder($user);
                    $user->setPassword($encoder->encodePassword($password, $user->getSalt()));
                    $user->setPlainPassword($password);
                    $user->save();

                    // Envoi email
                    $dispatcher = $eventDispatcher->dispatch('upd_password_email', new GenericEvent($user));
                }
            }
        } else {
            $errors = StudioEchoUtils::getAjaxFormErrors($form);
            throw new FormValidationException($errors);
        }

        return true;
    }


    /* ######################################################################################################## */
    /*                                                      NOTIFICATIONS                                       */
    /* ######################################################################################################## */

    /**
     * Notifications loading
     */
    public function notificationsLoad()
    {
        $logger = $this->sc->get('logger');
        $logger->info('*** notificationsLoad');

        // Retrieve used services
        $securityContext = $this->sc->get('security.context');
        $notificationManager = $this->sc->get('politizr.manager.notification');
        $templating = $this->sc->get('templating');

        // Function process
        $user = $securityContext->getToken()->getUser();

        $notifs = $notificationManager->getUserNotifications($user->getId());
        $nbNotifs = count($notifs);

        // Rendering
        $html = $templating->render(
            'PolitizrFrontBundle:Notification:_list.html.twig',
            array(
                'notifs' => $notifs,
            )
        );

        return array(
            'html' => $html,
            'nb' => $nbNotifs > 0 ? $nbNotifs:'-',
            );
    }

    /**
     * Notification check
     */
    public function notificationCheck()
    {
        $logger = $this->sc->get('logger');
        $logger->info('*** notificationChek');

        // Retrieve used services
        $request = $this->sc->get('request');
        $securityContext = $this->sc->get('security.context');
        $notificationManager = $this->sc->get('politizr.manager.notification');

        // Request arguments
        $id = $request->get('subjectId');
        $logger->info('$id = ' . print_r($id, true));

        // Function process
        $user = $securityContext->getToken()->getUser();

        $notification = PUNotificationQuery::create()->findPk($id);
        $notificationManager->checkUserNotification($id);

        return true;
    }

    /**
     * Notification check all
     */
    public function notificationsCheckAll()
    {
        $logger = $this->sc->get('logger');
        $logger->info('*** notificationsLoad');
        
        // Retrieve used services
        $securityContext = $this->sc->get('security.context');
        $notificationManager = $this->sc->get('politizr.manager.notification');

        // Function process
        $user = $securityContext->getToken()->getUser();

        $notifs = PUNotificationQuery::create()
                            ->filterByPUserId($user->getId())
                            ->find();
        foreach ($notifs as $notif) {
            $notificationManager->checkUserNotification($notif);
        }

        return true;
    }


    /* ######################################################################################################## */
    /*                                      NOTIFICATIONS' SUBSCRIPTIONS                                        */
    /* ######################################################################################################## */

    /**
     * Notification email subscription
     */
    public function notifEmailSubscribe()
    {
        $logger = $this->sc->get('logger');
        $logger->info('*** notifEmailSubscribe');

        // Retrieve used services
        $request = $this->sc->get('request');
        $securityContext = $this->sc->get('security.context');
        $notificationManager = $this->sc->get('politizr.manager.notification');

        // Request arguments
        $id = $request->get('subjectId');
        $logger->info('$id = ' . print_r($id, true));

        // Function process
        $user = $securityContext->getToken()->getUser();

        $notificationManager->createUserSubscribeEmail($user->getId(), $id);

        return true;
    }


    /**
     * Notification email unsubscription
     */
    public function notifEmailUnsubscribe()
    {
        $logger = $this->sc->get('logger');
        $logger->info('*** notifEmailUnsubscribe');

        // Retrieve used services
        $request = $this->sc->get('request');
        $securityContext = $this->sc->get('security.context');
        $notificationManager = $this->sc->get('politizr.manager.notification');

        // Request arguments
        $id = $request->get('subjectId');
        $logger->info('$id = ' . print_r($id, true));

        // Function process
        $user = $securityContext->getToken()->getUser();

        $notificationManager->deleteUserSubscribeEmail($user->getId(), $id);

        return true;
    }

    /**
     * Contextual user notification email subscription
     */
    public function notifUserContextSubscribe()
    {
        $logger = $this->sc->get('logger');
        $logger->info('*** notifUserContextSubscribe');

        // Retrieve used services
        $request = $this->sc->get('request');
        $securityContext = $this->sc->get('security.context');
        $userManager = $this->sc->get('politizr.manager.user');

        // Request arguments
        $id = $request->get('subjectId');
        $logger->info('$id = ' . print_r($id, true));
        $context = $request->get('context');
        $logger->info('$context = ' . print_r($context, true));

        // Function process
        $user = $securityContext->getToken()->getUser();

        $userManager->updateFollowUserContextEmailNotification($user->getId(), $id, true, $context);

        return true;
    }

    /**
     * Contextual user notification email unsubscription
     */
    public function notifUserContextUnsubscribe()
    {
        $logger = $this->sc->get('logger');
        $logger->info('*** notifUserContextUnsubscribe');

        // Retrieve used services
        $request = $this->sc->get('request');
        $securityContext = $this->sc->get('security.context');
        $userManager = $this->sc->get('politizr.manager.user');

        // Request arguments
        $id = $request->get('subjectId');
        $logger->info('$id = ' . print_r($id, true));
        $context = $request->get('context');
        $logger->info('$context = ' . print_r($context, true));

        // Function process
        $user = $securityContext->getToken()->getUser();

        $userManager->updateFollowUserContextEmailNotification($user->getId(), $id, false, $context);

        return true;
    }

    /**
     * Contextual user's debate notification email subscription
     */
    public function notifDebateContextSubscribe()
    {
        $logger = $this->sc->get('logger');
        $logger->info('*** notifDebateContextSubscribe');

        // Retrieve used services
        $request = $this->sc->get('request');
        $securityContext = $this->sc->get('security.context');
        $userManager = $this->sc->get('politizr.manager.user');

        // Request arguments
        $id = $request->get('subjectId');
        $logger->info('$id = ' . print_r($id, true));
        $context = $request->get('context');
        $logger->info('$context = ' . print_r($context, true));

        // Function process
        $user = $securityContext->getToken()->getUser();

        $userManager->updateFollowDebateContextEmailNotification($user->getId(), $id, true, $context);

        return true;
    }

    /**
     *  Désouscrit une notification contextuelle à un débat
     *
     */
    public function notifDebateContextUnsubscribe()
    {
        $logger = $this->sc->get('logger');
        $logger->info('*** notifDebateContextUnsubscribe');

        // Retrieve used services
        $request = $this->sc->get('request');
        $securityContext = $this->sc->get('security.context');
        $userManager = $this->sc->get('politizr.manager.user');

        // Request arguments
        $id = $request->get('subjectId');
        $logger->info('$id = ' . print_r($id, true));
        $context = $request->get('context');
        $logger->info('$context = ' . print_r($context, true));

        // Function process
        $user = $securityContext->getToken()->getUser();

        $userManager->updateFollowDebateContextEmailNotification($user->getId(), $id, false, $context);

        return true;
    }

    /* ######################################################################################################## */
    /*                                                REPUTATION                                                */
    /* ######################################################################################################## */

    /**
     * User's reputation listing
     */
    public function historyActionsList()
    {
        $logger = $this->sc->get('logger');
        $logger->info('*** historyActionsList');

        // Retrieve used services
        $request = $this->sc->get('request');
        $securityContext = $this->sc->get('security.context');
        $userManager = $this->sc->get('politizr.manager.user');
        $templating = $this->sc->get('templating');

        // Request arguments
        $offset = $request->get('offset');
        $logger->info('$offset = ' . print_r($offset, true));
        $order = $request->get('order');
        $logger->info('$order = ' . print_r($order, true));
        $filters = $request->get('filters');
        $logger->info('$filters = ' . print_r($filters, true));

        // Function process
        $user = $securityContext->getToken()->getUser();

        $historyActions = PUReputationQuery::create()
                            ->filterByPUserId($user->getId())
                            ->orderByCreatedAt(\Criteria::DESC)
                            ->limit(10)
                            ->offset($offset)
                            ->find();

        // Rendering
        $html = $templating->render(
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
}
