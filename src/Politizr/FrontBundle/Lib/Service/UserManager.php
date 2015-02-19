<?php
namespace Politizr\FrontBundle\Lib\Service;

use Symfony\Component\EventDispatcher\GenericEvent;

use StudioEcho\Lib\StudioEchoUtils;

use Politizr\Exception\InconsistentDataException;
use Politizr\Exception\FormValidationException;

use Politizr\FrontBundle\Lib\SimpleImage;

use Politizr\Model\PUser;
use Politizr\Model\PDocument;
use Politizr\Model\PUFollowU;
use Politizr\Model\PQType;
use Politizr\Model\PUCurrentQO;
use Politizr\Model\PUMandate;

use Politizr\Model\PUserQuery;
use Politizr\Model\PUFollowUQuery;
use Politizr\Model\PUNotificationsQuery;
use Politizr\Model\PUCurrentQOQuery;
use Politizr\Model\PUMandateQuery;

use Politizr\FrontBundle\Form\Type\PUserIdentityType;
use Politizr\FrontBundle\Form\Type\PUserEmailType;
use Politizr\FrontBundle\Form\Type\PUserBiographyType;
use Politizr\FrontBundle\Form\Type\PUserConnectionType;
use Politizr\FrontBundle\Form\Type\PUCurrentQOType;
use Politizr\FrontBundle\Form\Type\PUMandateType;

/**
 * Services métiers associés aux utilisateurs. 
 *
 * @author Lionel Bouzonville
 */
class UserManager
{
    private $sc;

    /**
     *
     */
    public function __construct($serviceContainer) {
        $this->sc = $serviceContainer;
    }

    /* ######################################################################################################## */
    /*                                            SUIVI (FONCTIONS AJAX)                                        */
    /* ######################################################################################################## */


    /**
     *
     *
     */
    public function follow() {
        $logger = $this->sc->get('logger');
        $logger->info('*** follow');
        
        // Récupération user
        $user = $this->sc->get('security.context')->getToken()->getUser();

        // Récupération args
        $request = $this->sc->get('request');

        $objectId = $request->get('objectId');
        $logger->info('$objectId = ' . print_r($objectId, true));
        $way = $request->get('way');
        $logger->info('$way = ' . print_r($way, true));

        // MAJ suivre / ne plus suivre
        if ($way == 'follow') {
            $object = PUserQuery::create()->findPk($objectId);

            // Insertion nouvel élément
            $pUFollowU = new PUFollowU();
            $pUFollowU->setPUserId($object->getId());
            $pUFollowU->setPUserFollowerId($user->getId());
            $pUFollowU->save();

            // Réputation
            $event = new GenericEvent($object, array('user_id' => $user->getId(),));
            $dispatcher = $this->sc->get('event_dispatcher')->dispatch('r_user_follow', $event);

            // Notification
            $event = new GenericEvent($object, array('author_user_id' => $user->getId(),));
            $dispatcher = $this->sc->get('event_dispatcher')->dispatch('n_user_follow', $event);

            // Badges associés
            $event = new GenericEvent($object, array('author_user_id' => $user->getId(), 'target_user_id' => $object->getId()));
            $dispatcher = $this->sc->get('event_dispatcher')->dispatch('b_user_follow', $event);
        } elseif ($way == 'unfollow') {
            $object = PUserQuery::create()->findPk($objectId);

            // Suppression élément(s)
            $pUFollowUList = PUFollowUQuery::create()
                            ->filterByPUserId($object->getId())
                            ->filterByPUserFollowerId($user->getId())
                            ->find();
            foreach ($pUFollowUList as $pUFollowU) {
                $pUFollowU->delete();
            }

            // Réputation
            $event = new GenericEvent($object, array('user_id' => $user->getId(),));
            $dispatcher = $this->sc->get('event_dispatcher')->dispatch('r_user_unfollow', $event);
        }

        // Construction rendu
        $templating = $this->sc->get('templating');
        $html = $templating->render(
                            'PolitizrFrontBundle:Fragment\\Follow:glSubscribe.html.twig', array(
                                'object' => $object,
                                'type' => PDocument::TYPE_USER
                                )
                    );
        $followers = $templating->render(
                            'PolitizrFrontBundle:Fragment\\Follow:glFollowers.html.twig', array(
                                'object' => $object,
                                'type' => PDocument::TYPE_USER
                                )
                    );



        // Renvoi de l'ensemble des blocs HTML maj
        return array(
            'html' => $html,
            'followers' => $followers
            );
    }


    /* ######################################################################################################## */
    /*                                              SUGGESTIONS (FONCTIONS AJAX)                                */
    /* ######################################################################################################## */


    /**
     *  Listing de users ordonnancés suivant l'argument récupéré
     *
     */
    public function userList() {
        $logger = $this->sc->get('logger');
        $logger->info('*** userList');
        
        // Récupération user
        $user = $this->sc->get('security.context')->getToken()->getUser();

        // Récupération args
        $request = $this->sc->get('request');

        $order = $request->get('order');
        $logger->info('$order = ' . print_r($order, true));

        // Dates début / fin
        $now = new \DateTime();
        $nowMin24 = new \DateTime();

        // -24h tant qu'il n'y a pas de résultats significatifs
        $nb = 0;
        while($nb < 10) {
            $nb = PUserQuery::create()->online()->filterByCreatedAt(array('min' => $nowMin24, 'max' => $now))->count();
            $logger->info('$nb = ' . print_r($nb, true));
            $nowMin24->modify('-1 day');
        }

        // Requête suivant order
        $users = PUserQuery::create()
                            // ->filterByQualified(true)
                            ->online()
                            ->filterByCreatedAt(array('min' => $nowMin24, 'max' => $now))
                            ->_if($order == 'mostFollowed')
                                ->mostFollowed()
                            ->_elseif($order == 'last')
                                ->last()
                            ->_endif()
                            ->find();

        // Construction rendu
        $templating = $this->sc->get('templating');
        $html = $templating->render(
                            'PolitizrFrontBundle:Fragment\\User:glSuggestionList.html.twig', array(
                                'users' => $users
                                )
                    );

        // Renvoi de l'ensemble des blocs HTML maj
        return array(
            'html' => $html,
            );
    }


    /* ######################################################################################################## */
    /*                                       EDITIONS INFO PERSO (FONCTIONS AJAX)                               */
    /* ######################################################################################################## */

    /**
     *  Mise à jour des informations du profil du user
     *
     */
    public function userProfileUpdate() {
        $logger = $this->sc->get('logger');
        $logger->info('*** userProfileUpdate');
        
        // Récupération user
        $user = $this->sc->get('security.context')->getToken()->getUser();

        // Récupération args
        $request = $this->sc->get('request');

        $form = $this->sc->get('form.factory')->create(new PUserBiographyType($user), $user);

        // *********************************** //
        //      Traitement du POST
        // *********************************** //
        $form->bind($request);
        if ($form->isValid()) {
            $userProfile = $form->getData();
            $logger->info('userProfile = '.print_r($userProfile, true));

            // enregistrement object user
            $userProfile->save();
        } else {
            $errors = StudioEchoUtils::getAjaxFormErrors($form);
            throw new FormValidationException($errors);
        }

        return true;
    }



    /**
     *  Upload de la photo de profil du user
     *
     */
    public function userPhotoUpload() {
        $logger = $this->sc->get('logger');
        $logger->info('*** userPhotoUpload');

        // Récupération user
        $user = $this->sc->get('security.context')->getToken()->getUser();

        // Chemin des images
        $path = $this->sc->get('kernel')->getRootDir() . '/../web' . PUser::UPLOAD_WEB_PATH;

        // Appel du service d'upload ajax
        $fileName = $this->sc->get('politizr.utils')->uploadImageAjax(
            'file-name',
            $path,
            150, 150
            );

        // Suppression photo déjà uploadée
        $oldFilename = $user->getFilename();
        if ($oldFilename && $fileExists = file_exists($path . $oldFilename)) {
            unlink($path . $oldFilename);
        }

        // MAJ du modèle
        $user->setFilename($fileName);
        $user->save();

        // Renvoi de l'ensemble des blocs HTML maj
        return array(
            'filename' => $fileName,
            );
    }

    /**
     *  Suppression de la photo de profil du user
     *
     */
    public function userPhotoDelete() {
        $logger = $this->sc->get('logger');
        $logger->info('*** userPhotoDelete');
        
        // Récupération user
        $user = $this->sc->get('security.context')->getToken()->getUser();

        // Récupération args
        $request = $this->sc->get('request');

        // Chemin des images
        $path = $this->sc->get('kernel')->getRootDir() . '/../web' . PUser::UPLOAD_WEB_PATH;

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
     *  Upload de la photo de fond du profil du user
     *
     */
    public function userBackPhotoUpload() {
        $logger = $this->sc->get('logger');
        $logger->info('*** userBackPhotoUpload');
        
        // Récupération user
        $user = $this->sc->get('security.context')->getToken()->getUser();

        // Chemin des images
        $path = $this->sc->get('kernel')->getRootDir() . '/../web' . PUser::UPLOAD_WEB_PATH;

        // Appel du service d'upload ajax
        $fileName = $this->sc->get('politizr.utils')->uploadImageAjax(
            'back-file-name',
            $path,
            1280, 600
            );

        // Suppression photo déjà uploadée
        $oldFilename = $user->getBackFilename();
        if ($oldFilename && $fileExists = file_exists($path . $oldFilename)) {
            unlink($path . $oldFilename);
        }

        // MAJ du modèle
        $user->setBackFilename($fileName);
        $user->save();

        // Renvoi de l'ensemble des blocs HTML maj
        return array(
            'filename' => $fileName,
            );
    }

    /**
     *  Suppression de la photo de fond du profil du user
     *
     */
    public function userBackPhotoDelete() {
        $logger = $this->sc->get('logger');
        $logger->info('*** userBackPhotoDelete');
        
        // Récupération user
        $user = $this->sc->get('security.context')->getToken()->getUser();

        // Récupération args
        $request = $this->sc->get('request');

        // Chemin des images
        $path = $this->sc->get('kernel')->getRootDir() . '/../web' . PUser::UPLOAD_WEB_PATH;

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
     *  Mise à jour des informations "organisation en cours" du user
     *
     */
    public function orgaProfileUpdate() {
        $logger = $this->sc->get('logger');
        $logger->info('*** orgaProfileUpdate');
        
        // Récupération user
        $user = $this->sc->get('security.context')->getToken()->getUser();

        // Récupération args
        $request = $this->sc->get('request');

        // Récupération orga courante
        $puCurrentQo = PUCurrentQOQuery::create()
            ->filterByPUserId($user->getId())
            ->usePUCurrentQOPQOrganizationQuery()
                ->filterByPQTypeId(PQType::ID_ELECTIF)
            ->endUse()
            ->findOne();

        if (!$puCurrentQo) {
            $puCurrentQo = new PUCurrentQO();
        }

        $form = $this->sc->get('form.factory')->create(new PUCurrentQOType(PQType::ID_ELECTIF), $puCurrentQo);

        // *********************************** //
        //      Traitement du POST
        // *********************************** //
        $form->bind($request);
        if ($form->isValid()) {
            $puCurrentQo = $form->getData();
            $logger->info('puCurrentQo = '.print_r($puCurrentQo, true));

            $puCurrentQo->save();
        } else {
            $errors = StudioEchoUtils::getAjaxFormErrors($form);
            throw new FormValidationException($errors);
        }

        return true;
    }

    /**
     *  Création d'un mandat pour un user
     *
     */
    public function mandateProfileCreate() {
        $logger = $this->sc->get('logger');
        $logger->info('*** mandateProfileCreate');
        
        // Récupération user
        $user = $this->sc->get('security.context')->getToken()->getUser();

        // Récupération args
        $request = $this->sc->get('request');

        // Mandat
        $mandate = new PUMandate();
        $mandate->setPUserId($user->getId());
        $mandate->setPQTypeId(PQType::ID_ELECTIF);

        $form = $this->sc->get('form.factory')->create(new PUMandateType(PQType::ID_ELECTIF), new PUMandate());

        // *********************************** //
        //      Traitement du POST
        // *********************************** //
        $form->bind($request);
        if ($form->isValid()) {
            $mandate = $form->getData();
            $logger->info('mandate = '.print_r($mandate, true));

            $mandate->save();
        } else {
            $errors = StudioEchoUtils::getAjaxFormErrors($form);
            throw new FormValidationException($errors);
        }

        // Création d'un nouveau formulaire vierge
        $mandate = new PUMandate();
        $mandate->setPUserId($user->getId());
        $mandate->setPQTypeId(PQType::ID_ELECTIF);

        $formMandateViews = $this->getFormMandateViews($user->getId());
        $form = $this->sc->get('form.factory')->create(new PUMandateType(PQType::ID_ELECTIF), new PUMandate());

        // Construction rendu
        $templating = $this->sc->get('templating');
        $html = $templating->render(
                            'PolitizrFrontBundle:Fragment\\User:glMandateEdit.html.twig', array(
                                'formMandate' => $form->createView(),
                                'formMandateViews' => $formMandateViews
                                )
                    );

        // Renvoi de l'ensemble des blocs HTML maj
        return array(
            'html' => $html,
            );
    }

    /**
     *  MAJ d'un mandat pour un user
     *
     */
    public function mandateProfileUpdate() {
        $logger = $this->sc->get('logger');
        $logger->info('*** mandateProfileUpdate');
        
        // Récupération user
        $user = $this->sc->get('security.context')->getToken()->getUser();

        // Récupération args
        $request = $this->sc->get('request');

        // Mandat
        $mandate = PUMandateQuery::create()->findPk($request->get('mandate')['id']);

        $form = $this->sc->get('form.factory')->create(new PUMandateType(PQType::ID_ELECTIF), $mandate);

        // *********************************** //
        //      Traitement du POST
        // *********************************** //
        $form->bind($request);
        if ($form->isValid()) {
            $mandate = $form->getData();
            $logger->info('mandate = '.print_r($mandate, true));

            $mandate->save();
        } else {
            $errors = StudioEchoUtils::getAjaxFormErrors($form);
            throw new FormValidationException($errors);
        }

        return true;
    }

    /**
     *  MAJ d'un mandat pour un user
     *
     */
    public function mandateProfileDelete() {
        $logger = $this->sc->get('logger');
        $logger->info('*** mandateProfileUpdate');
        
        // Récupération user
        $user = $this->sc->get('security.context')->getToken()->getUser();

        // Récupération args
        $request = $this->sc->get('request');

        $id = $request->get('id');

        $mandate = PUMandateQuery::create()->findPk($id);
        $mandate->delete();

        return true;
    }

    /**
     *  Construction des vues des formulaires associés à chaque mandat du user
     *  Code sous forme de fonction car utilisé à plusieurs endroits.
     *
     *  @param  integer     $userId     ID PUser
     *
     *  @return     array Form views PUMandateType
     */
    public function getFormMandateViews($userId) {
        // Mandats
        $mandates = PUMandateQuery::create()
            ->filterByPUserId($userId)
            ->filterByPQTypeId(PQType::ID_ELECTIF)
            ->orderByEndAt('desc')
            ->find();

        // Création des form + vues associées pour MAJ des mandats
        $formMandateViews = array();
        foreach($mandates as $mandate) {
            $formMandate = $this->sc->get('form.factory')->create(new PUMandateType(PQType::ID_ELECTIF), $mandate);
            $formMandateViews[] = $formMandate->createView();
        }

        return $formMandateViews;
    }

    /**
     *  Mise à jour des informations personnelles du user
     *
     */
    public function userPersoUpdate() {
        $logger = $this->sc->get('logger');
        $logger->info('*** userPersoUpdate');
        
        // Récupération user
        $user = $this->sc->get('security.context')->getToken()->getUser();

        // Récupération args
        $request = $this->sc->get('request');

        $formTypeId = $request->get('user')['form_type_id'];
        $logger->info('$formTypeId = '.print_r($formTypeId, true));

        // Création du formulaire soumis
        if ($formTypeId == 1) {
            $form = $this->sc->get('form.factory')->create(new PUserIdentityType($user), $user);
        } elseif($formTypeId == 2) {
            $form = $this->sc->get('form.factory')->create(new PUserEmailType(), $user);
        } elseif($formTypeId == 3) {
            $form = $this->sc->get('form.factory')->create(new PUserConnectionType(), $user);
        } else {
            throw new InconsistentDataException('Form invalid.');
        }

        // *********************************** //
        //      Traitement du POST
        // *********************************** //
        $form->bind($request);
        if ($form->isValid()) {
            $userPerso = $form->getData();
            $logger->info('userPerso = '.print_r($userPerso, true));

            // enregistrement object user
            $userPerso->save();

            if ($formTypeId == 1) {
                // Nickname & realname
                $user->setNickname($userPerso->getFirstname() . ' ' . $userPerso->getName());
                $user->setRealname($userPerso->getFirstname() . ' ' . $userPerso->getName());
                $user->save();
            } elseif($formTypeId == 2) {
                // Canonicalization
                $canonicalizeEmail = $this->sc->get('fos_user.util.email_canonicalizer');
                $user->setEmailCanonical($canonicalizeEmail->canonicalize($userPerso->getEmail()));
                $user->save();
            } elseif($formTypeId == 3) {
                $password = $userPerso->getPassword();
                $logger->info('password = '.print_r($password, true));
                if ($password) {
                    // Encodage MDP
                    $encoderFactory = $this->sc->get('security.encoder_factory');

                    $encoder = $encoderFactory->getEncoder($user);
                    $user->setPassword($encoder->encodePassword($password, $user->getSalt()));
                    // $user->eraseCredentials();

                    $user->setPlainPassword($password);
                    $user->save();

                    // Envoi email
                    $dispatcher = $this->sc->get('event_dispatcher')->dispatch('upd_password_email', new GenericEvent($user));
                }
            }
        } else {
            $errors = StudioEchoUtils::getAjaxFormErrors($form);
            throw new FormValidationException($errors);
        }

        return true;
    }


    /* ######################################################################################################## */
    /*                                            NOTIFICATIONS (FONCTIONS AJAX)                                */
    /* ######################################################################################################## */


    /**
     *  Notifications
     *
     */
    public function notificationsLoad() {
        $logger = $this->sc->get('logger');
        $logger->info('*** notificationsLoad');

        // Récupération user
        $user = $this->sc->get('security.context')->getToken()->getUser();

        // Requête notifs
        $lastWeek = new \DateTime();
        $lastWeek->modify('-7 day');
        $logger->info('lastWeek = '.print_r($lastWeek, true));

        // Notifications de moins d'une semaine ou non checkées
        $notifs = PUNotificationsQuery::create()
                            ->filterByPUserId($user->getId())
                            ->filterByCreatedAt(array('min' => $lastWeek))
                            ->_or()
                            ->filterByChecked(false)
                            ->orderByCreatedAt('desc')
                            ->find();

        $nbNotifs = PUNotificationsQuery::create()
                            ->filterByPUserId($user->getId())
                            ->filterByChecked(false)
                            ->count();

        // Construction rendu
        $templating = $this->sc->get('templating');
        $html = $templating->render(
                            'PolitizrFrontBundle:Fragment\\User:glNotificationList.html.twig', array(
                                'notifs' => $notifs,
                                )
                    );

        // Renvoi de l'ensemble des blocs HTML maj
        return array(
            'html' => $html,
            'nb' => $nbNotifs > 0 ? $nbNotifs:'-',
            );
    }

    /**
     *  Notification checkée
     *
     */
    public function notificationCheck() {
        $logger = $this->sc->get('logger');
        $logger->info('*** notificationChek');

        // Récupération user
        $user = $this->sc->get('security.context')->getToken()->getUser();

        // Récupération args
        $request = $this->sc->get('request');

        $subjectId = $request->get('subjectId');
        $logger->info('$subjectId = ' . print_r($subjectId, true));

        // MAJ checked
        $puNotif = PUNotificationsQuery::create()->findPk($subjectId);
        $puNotif->setChecked(true);
        $puNotif->setCheckedAt(new \DateTime());
        $puNotif->save();

        return true;
    }

    /**
     *  Notifications
     *
     */
    public function notificationsCheckAll() {
        $logger = $this->sc->get('logger');
        $logger->info('*** notificationsLoad');
        
        // Récupération user
        $user = $this->sc->get('security.context')->getToken()->getUser();

        // Check / Uncheck all
        $notifs = PUNotificationsQuery::create()
                            ->filterByPUserId($user->getId())
                            ->find();

        foreach ($notifs as $puNotif) {
            $puNotif->setChecked(true);
            $puNotif->setCheckedAt(new \DateTime());
            $puNotif->save();
        }

        return true;
    }
}