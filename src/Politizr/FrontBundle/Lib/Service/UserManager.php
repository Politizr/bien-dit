<?php
namespace Politizr\FrontBundle\Lib\Service;

use Symfony\Component\EventDispatcher\GenericEvent;

use StudioEcho\Lib\StudioEchoUtils;

use Politizr\FrontBundle\Lib\SimpleImage;

use Politizr\Model\PUser;
use Politizr\Model\PDocument;
use Politizr\Model\PUFollowU;

use Politizr\Model\PUserQuery;
use Politizr\Model\PUFollowUQuery;

use Politizr\FrontBundle\Form\Type\PUserIdentityType;
use Politizr\FrontBundle\Form\Type\PUserEmailType;
use Politizr\FrontBundle\Form\Type\PUserBiographyType;
use Politizr\FrontBundle\Form\Type\PUserConnectionType;


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
            $form = $this->sc->get('form.factory')->create(new PUserBiographyType(), $user);
        } elseif($formTypeId == 4) {
            $form = $this->sc->get('form.factory')->create(new PUserConnectionType(), $user);
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
            } elseif($formTypeId == 4) {
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


    /**
     *  Upload de la photo de profil du user
     *
     */
    public function userPhotoUpload() {
        $logger = $this->sc->get('logger');
        $logger->info('*** userPhotoUpload');
        
        // Récupération user
        $user = $this->sc->get('security.context')->getToken()->getUser();

        // Récupération args
        $request = $this->sc->get('request');

        // Chemin des images
        $path = $this->sc->get('kernel')->getRootDir() . '/../web' . PUser::UPLOAD_WEB_PATH;

        // Taille max 5Mo
        $sizeLimit = 5 * 1024 * 1024;

        $myRequestedFile = $request->files->get('file-name');
        // $logger->info(print_r($myRequestedFile, true));

        if ($myRequestedFile == null) {
            throw new FormValidationException('Fichier non existant.');
        } else if ($myRequestedFile->getError() > 0) {
            throw new FormValidationException('Erreur upload n°'.$myRequestedFile->getError(), 1);
        } else {
            // Contrôle extension
            $allowedExtensions = array('jpg', 'jpeg', 'png');
            $ext = $myRequestedFile->guessExtension();
            if ($allowedExtensions && !in_array(strtolower($ext), $allowedExtensions)) {
                throw new FormValidationException('Type de fichier non autorisé.');
            }

            // Construction du nom du fichier
            $destName = md5(uniqid()) . '.' . $ext;

            //move the uploaded file to uploads folder;
            // $move = move_uploaded_file($pathNameTmp, $path . $destName);
            $movedFile = $myRequestedFile->move($path, $destName);
            $logger->info('$movedFile = '.print_r($movedFile, true));
        }

        // Suppression photo déjà uploadée
        $filename = $user->getFilename();
        if ($filename && $fileExists = file_exists($path . $filename)) {
            unlink($path . $filename);
        }

        // Resize de la photo 640*640px max
        $resized = false;                
        $image = new SimpleImage();
        $image->load($path . $destName);
        if ($width = $image->getWidth() > 150) {
            $image->resizeToWidth(150);
            $resized = true;
        }
        if ($height = $image->getHeight() > 150) {
            $image->resizeToHeight(150);
            $resized = true;
        }
        if ($resized) {
            $image->save($path . $destName);
        }

        // MAJ du modèle
        $user->setFilename($destName);
        $user->save();

        // Renvoi de l'ensemble des blocs HTML maj
        return array(
            'filename' => $destName,
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


}