<?php

namespace Politizr\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Symfony\Component\Security\Core\SecurityContext;

use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

use Symfony\Component\Security\Core\AuthenticationEvents;
use Symfony\Component\Security\Core\Event\AuthenticationEvent;

use Politizr\Model\PUserQuery;

use Politizr\Model\PUser;

use Politizr\FrontBundle\Form\Type\PUserStep1Type;
use Politizr\FrontBundle\Form\Type\PUserStep2Type;

use Politizr\FrontBundle\Form\Type\PUserElectedStep1Type;

use Politizr\FrontBundle\Form\Type\LoginType;
use Politizr\FrontBundle\Form\Type\LostPasswordType;


/**
 * Gestion des inscriptions / connexions
 *
 * http://nyrodev.info/fr/posts/286/Connexions-OAuth-Multiple-avec-Symfony-2-3
 *
 * TODO:
 *  - sortir les envois d'emails dans une classe dédiée
 *  - implémentation BaseFacebook pour exploitation API et récupération infos annexes supplémentaires (photo)
 *  - personnaliser les exception
 *
 * @author Lionel Bouzonville
 */
class SecurityController extends Controller {

    /* ######################################################################################################## */
    /*                                                 CONNEXION                                      */
    /* ######################################################################################################## */

    /**
     * Connexion
     */
    public function loginAction()
    {
        $logger = $this->get('logger');
        $logger->info('*** loginAction');

        // *********************************** //
        //      Formulaires
        // *********************************** //
        $formLogin = $this->createForm(new LoginType());
        $formLostPassword = $this->createForm(new LostPasswordType());

        // *********************************** //
        //      Affichage de la vue
        // *********************************** //
        return $this->render('PolitizrFrontBundle:Navigation:login.html.twig', array(
                        'formLogin' => $formLogin->createView(),
                        'formLostPassword' => $formLostPassword->createView()
            ));
    }

    /**
     * Connexion validation
     * AJAX
     */
    public function loginCheckAction(Request $request)
    {
        $logger = $this->get('logger');
        $logger->info('*** loginCheckAction');

        try {
            if ($request->isXmlHttpRequest()) {
                $formLogin = $this->createForm(new LoginType());

                // *********************************** //
                //      Traitement du POST
                // *********************************** //
                $formLogin->bind($request);
                if ($formLogin->isValid()) {
                    $login = $formLogin->getData();
                    $logger->info('login = '.print_r($login, true));

                    // Récupération du user
                    $userManager = $this->container->get('politizr.login_user_provider');
                    $pUser = $userManager->loadUserByUsername($login['username']);

                    if (!$pUser) {
                        $logger->info('User / username not found.');

                        $message = 'Utilisateur inconnu.';
                        $jsonResponse = array(
                            'error' => $message
                            );
                    } else {
                        // Contrôle user/password
                        $password = $pUser->getPassword();

                        $encoderService = $this->get('security.encoder_factory');
                        $encoder = $encoderService->getEncoder($pUser);
                        $encodedPass = $encoder->encodePassword($login['password'], $pUser->getSalt());
                        $logger->info('encodedPass = '.print_r($encodedPass, true));
                        $logger->info('$password = '.print_r($password, true));

                        if ($password  != $encodedPass) {
                            $logger->info('Incorrect password.');

                            $message = 'Mot de passe incorrect.';
                            $jsonResponse = array(
                                'error' => $message
                                );
                        } else {
                            // check process d'inscription finalisé
                            if ($pUser->hasRole('ROLE_CITIZEN')) {
                                $logger->info('ROLE_CITIZEN ok');

                                // MAJ objet
                                $pUser->setLastLogin(new \DateTime());
                                $pUser->save();

                                $redirectUrl = $this->generateUrl('Homepage');
                            } else {
                                $logger->info('ROLE_CITIZEN pas ok');

                                $redirectUrl = $this->generateUrl('InscriptionStep2');
                            }

                            // Connexion
                            $this->doPublicConnection($pUser);

                            // Construction de la réponse
                            $jsonResponse = array (
                                'success' => true,
                                'redirectUrl' => $redirectUrl
                            );
                        }
                    }
                } else {
                    $message = 'Champs obligatoires.';
                    $jsonResponse = array(
                        'error' => $message
                        );
                }
            } else {
                throw $this->createNotFoundException('Not a XHR request');
            }
        } catch (NotFoundHttpException $e) {
            $logger->info('Exception = ' . print_r($e->getMessage(), true));
            $jsonResponse = array('error' => $e->getMessage());
        } catch (\Exception $e) {
            $logger->info('Exception = ' . print_r($e->getMessage(), true));
            $jsonResponse = array('error' => $e->getMessage());
        }

        // JSON formatted success/error message
        $response = new Response(json_encode($jsonResponse));
        return $response;
    }

    /**
     * Mot de passe oublié
     * AJAX
     */
    public function lostPasswordCheckAction(Request $request)
    {
        $logger = $this->get('logger');
        $logger->info('*** lostPasswordCheckAction');

        try {
            if ($request->isXmlHttpRequest()) {
                // *********************************** //
                //      Formulaires
                // *********************************** //
                $formLostPassword = $this->createForm(new LostPasswordType());

                // *********************************** //
                //      Traitement du POST
                // *********************************** //
                $formLostPassword->bind($request);
                if ($formLostPassword->isValid()) {
                    $lostPassword = $formLostPassword->getData();
                    $logger->info('lostPassword = '.print_r($lostPassword, true));

                    // Récupération du user
                    $userManager = $this->container->get('politizr.login_user_provider');
                    $user = $userManager->loadUserByEmail($lostPassword['email']);

                    if (!$user) {
                        $logger->info('User / email not found.');

                        $message = 'Utilisateur inconnu.';
                        $jsonResponse = array(
                            'error' => $message
                            );
                    } else {
                        // Génération d'un nouveau mot de passe
                        $password = substr(md5(uniqid(mt_rand(), true)), 0, 6);

                        // Encodage MDP
                        $encoderFactory = $this->get('security.encoder_factory');

                        $encoder = $encoderFactory->getEncoder($user);
                        $user->setPassword($encoder->encodePassword($password, $user->getSalt()));
                        $user->eraseCredentials();

                        $user->save();

                        // Envoi email
                        $mailer = $this->get('mailer');
                        $templating = $this->get('templating');

                        $htmlBody = $templating->render(
                                            'PolitizrFrontBundle:Email:lostPassword.html.twig', array('username' => $user->getUsername(), 'password' => $password)
                                    );
                        $txtBody = $templating->render(
                                            'PolitizrFrontBundle:Email:lostPassword.txt.twig', array('username' => $user->getUsername(), 'password' => $password)
                                    );

                        $message = \Swift_Message::newInstance()
                                ->setSubject('Politizr - Mot de passe oublié')
                                ->setFrom('admin@politizr.fr')
                                ->setTo($lostPassword['email'])
                                // ->setBcc(array('lionel.bouzonville@gmail.com'))
                                ->setBody($htmlBody, 'text/html', 'utf-8')
                                ->addPart($txtBody, 'text/plain', 'utf-8')
                        ;

                        $send = $mailer->send($message);
                        $logger->info('$send = '.print_r($send, true));
                        if (!$send) {
                            throw new \Exception('Erreur dans l\'envoi de l\'email');
                        }

                        // Construction de la réponse
                        $jsonResponse = array (
                            'success' => true
                        );
                    }
                } else {
                    $message = 'Champs obligatoires.';
                    $jsonResponse = array(
                        'error' => $message
                        );
                }
            } else {
                throw $this->createNotFoundException('Not a XHR request');
            }
        } catch (NotFoundHttpException $e) {
            $logger->info('Exception = ' . print_r($e->getMessage(), true));
            $jsonResponse = array('error' => $e->getMessage());
        } catch (\Exception $e) {
            $logger->info('Exception = ' . print_r($e->getMessage(), true));
            $jsonResponse = array('error' => $e->getMessage());
        }

        // JSON formatted success/error message
        $response = new Response(json_encode($jsonResponse));
        return $response;
    }


    /* ######################################################################################################## */
    /*                                               INSCRIPTION CLASSIQUE                                      */
    /* ######################################################################################################## */

    /**
     *     Page d'inscription
     */
    public function inscriptionAction()
    {
        $logger = $this->get('logger');
        $logger->info('*** inscriptionAction');

        // *********************************** //
        //      Formulaire
        // *********************************** //
        // TODO / redirection si connecté
        $pUser = $this->getUser();
        if ($pUser && $pUser->hasRole('ROLE_USER')) {
            if ($pUser->hasRole('ROLE_CITIZEN')) {
                $redirectUrl = $this->generateUrl('Homepage', array());
                return $this->redirect($redirectUrl);
            }
        }

        // Objet & formulaire
        $pUser = new PUser();
        $pUserFormType = new PUserStep1Type();
        $pUserForm = $this->createForm($pUserFormType, $pUser);
        
        // *********************************** //
        //      Affichage de la vue
        // *********************************** //

        return $this->render('PolitizrFrontBundle:Public:inscription.html.twig', 
                array(
                    'pUserForm' => $pUserForm->createView()
                    ));
    }

    /**
     *      Validation inscription
     */
    public function inscriptionCheckAction()
    {
        $logger = $this->get('logger');
        $logger->info('*** inscriptionCheckAction');

        // *********************************** //
        //      Formulaire
        // *********************************** //
        $pUser = $this->getUser();
        $pUser = new PUser();
        $pUserFormType = new PUserStep1Type();
        $pUserForm = $this->createForm($pUserFormType, $pUser);
        
        // *********************************** //
        //      Traitement du POST
        // *********************************** //
        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            $pUserForm->bind($this->getRequest());

            if ($pUserForm->isValid()) {
                $pUser = $pUserForm->getData();
                // $logger->info('pUser = '.print_r($pUser, true));

                // MAJ droits
                $pUser->addRole('ROLE_USER');

                $canonicalizeUsername = $this->get('fos_user.util.username_canonicalizer');
                $pUser->setUsernameCanonical($canonicalizeUsername->canonicalize($pUser->getUsername()));

                // Encodage MDP
                $encoderFactory = $this->get('security.encoder_factory');

                if (0 !== strlen($password = $pUser->getPlainPassword())) {
                    $encoder = $encoderFactory->getEncoder($pUser);
                    $pUser->setPassword($encoder->encodePassword($password, $pUser->getSalt()));
                    $pUser->eraseCredentials();
                }

                // Save user
                $pUser->save();

                // Connexion
                $this->doPublicConnection($pUser);

                // redirection
                $url = $this->container->get('router')->generate('InscriptionStep2');
                return $this->redirect($url);
            } else {
                $logger->info('form is not valid');
            }
        } else {
            $logger->info('method is not POST');
        }

        // *********************************** //
        //      Affichage de la vue
        // *********************************** //

        return $this->render('PolitizrFrontBundle:Public:inscription.html.twig', array(
                        'pUserForm' => $pUserForm->createView()
            ));
    }

    /**
     *     Page d'inscription / Etape 2
     */
    public function inscriptionStep2Action()
    {
        $logger = $this->get('logger');
        $logger->info('*** inscriptionStep2Action');

        // *********************************** //
        //      Formulaire
        // *********************************** //
        $pUser = $this->getUser();
        $pUserFormType = new PUserStep2Type();
        $pUserForm = $this->createForm($pUserFormType, $pUser);
        
        // *********************************** //
        //      Affichage de la vue
        // *********************************** //

        return $this->render('PolitizrFrontBundle:Security:inscriptionStep2.html.twig', 
                array(
                    'pUserForm' => $pUserForm->createView()
                    ));
    }

    /**
     *      Validation inscription
     */
    public function inscriptionStep2CheckAction()
    {
        $logger = $this->get('logger');
        $logger->info('*** inscriptionStep2CheckAction');

        // *********************************** //
        //      Formulaire
        // *********************************** //
        $pUser = $this->getUser();
        $pUserFormType = new PUserStep2Type();
        $pUserForm = $this->createForm($pUserFormType, $pUser);
        
        // *********************************** //
        //      Traitement du POST
        // *********************************** //
        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            $pUserForm->bind($this->getRequest());

            if ($pUserForm->isValid()) {
                $pUser = $pUserForm->getData();
                // $logger->info('pUser = '.print_r($pUser, true));

                // Canonicalization
                $canonicalizeEmail = $this->get('fos_user.util.email_canonicalizer');
                $pUser->setEmailCanonical($canonicalizeEmail->canonicalize($pUser->getEmail()));

                // Save user
                $pUser->save();

                // redirection
                $url = $this->container->get('router')->generate('InscriptionStep3');
                return $this->redirect($url);
            } else {
                $logger->info('form is not valid');
            }
        } else {
            $logger->info('method is not POST');
        }

        // *********************************** //
        //      Affichage de la vue
        // *********************************** //

        return $this->render('PolitizrFrontBundle:Security:inscriptionStep2.html.twig', array(
                        'pUserForm' => $pUserForm->createView()
            ));
    }

    /**
     *     Page d'inscription / Etape 3
     */
    public function inscriptionStep3Action()
    {
        $logger = $this->get('logger');
        $logger->info('*** inscriptionStep3Action');

        // *********************************** //
        //      Affichage de la vue
        // *********************************** //

        return $this->render('PolitizrFrontBundle:Security:inscriptionStep3.html.twig', 
                array(
                    ));
    }


    /**
     *      Validation / Etape 3
     */
    public function inscriptionStep3CheckAction()
    {
        $logger = $this->get('logger');
        $logger->info('*** inscriptionStep2CheckAction');

        // *********************************** //
        //      MAJ des droits & connexion "citoyen"
        // *********************************** //
        $pUser = $this->getUser();
        
        // MAJ objet
        $pUser->setEnabled(true);
        $pUser->setLastLogin(new \DateTime());

        // MAJ droits
        $pUser->addRole('ROLE_CITIZEN');

        // (re)Connexion (/ maj droits)
        $this->doPublicConnection($pUser);

        // redirection
        $url = $this->container->get('router')->generate('Homepage');
        return $this->redirect($url);
    }

    /* ######################################################################################################## */
    /*                                                 INSCRIPTION ELU                                          */
    /* ######################################################################################################## */

    /**
     *     Page d'inscription élu
     */
    public function inscriptionElectedAction()
    {
        $logger = $this->get('logger');
        $logger->info('*** inscriptionElectedAction');

        // *********************************** //
        //      Formulaire
        // *********************************** //
        // TODO / redirection si connecté
        $pUser = $this->getUser();
        if ($pUser && $pUser->hasRole('ROLE_USER')) {
            if ($pUser->hasRole('ROLE_ELECTED')) {
                $redirectUrl = $this->generateUrl('Homepage', array());
                return $this->redirect($redirectUrl);
            }
        }

        // Objet & formulaire
        $pUser = new PUser();
        $pUserFormType = new PUserElectedStep1Type();
        $pUserForm = $this->createForm($pUserFormType, $pUser);
        
        // *********************************** //
        //      Affichage de la vue
        // *********************************** //

        return $this->render('PolitizrFrontBundle:Public:inscriptionElected.html.twig', 
                array(
                    'pUserForm' => $pUserForm->createView()
                    ));
    }

    /**
     *      Validation inscription élu
     */
    public function inscriptionElectedCheckAction()
    {
        $logger = $this->get('logger');
        $logger->info('*** inscriptionElectedCheckAction');

        // *********************************** //
        //      Formulaire
        // *********************************** //
        $pUser = $this->getUser();
        $pUser = new PUser();
        $pUserFormType = new PUserElectedStep1Type();
        $pUserForm = $this->createForm($pUserFormType, $pUser);
        
        // *********************************** //
        //      Traitement du POST
        // *********************************** //
        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            $pUserForm->bind($this->getRequest());

            if ($pUserForm->isValid()) {
                $pUser = $pUserForm->getData();
                // $logger->info('pUser = '.print_r($pUser, true));

                // MAJ droits
                $pUser->addRole('ROLE_USER');

                $canonicalizeUsername = $this->get('fos_user.util.username_canonicalizer');
                $pUser->setUsernameCanonical($canonicalizeUsername->canonicalize($pUser->getUsername()));

                // Encodage MDP
                $encoderFactory = $this->get('security.encoder_factory');

                if (0 !== strlen($password = $pUser->getPlainPassword())) {
                    $encoder = $encoderFactory->getEncoder($pUser);
                    $pUser->setPassword($encoder->encodePassword($password, $pUser->getSalt()));
                    $pUser->eraseCredentials();
                }

                // Canonicalization
                $canonicalizeEmail = $this->get('fos_user.util.email_canonicalizer');
                $pUser->setEmailCanonical($canonicalizeEmail->canonicalize($pUser->getEmail()));

                // Gestion upload
                $file = $pUserForm['uploaded_supporting_document']->getData();
                $logger->info('$file = '.print_r($file, true));
                if ($file) {
                    $pUser->removeUpload(false, true);
                    $fileName = $pUser->upload($file);
                    $pUser->setSupportingDocument($fileName);
                }

                // Save user
                $pUser->save();

                // Connexion
                $this->doPublicConnection($pUser);

                // redirection
                $url = $this->container->get('router')->generate('InscriptionElectedStep2');
                return $this->redirect($url);
            } else {
                $logger->info('form is not valid');
            }
        } else {
            $logger->info('method is not POST');
        }

        // *********************************** //
        //      Affichage de la vue
        // *********************************** //

        return $this->render('PolitizrFrontBundle:Public:inscriptionElected.html.twig', array(
                        'pUserForm' => $pUserForm->createView()
            ));
    }

    /**
     *     Page d'inscription / Etape 2
     */
    public function inscriptionElectedStep2Action()
    {
        $logger = $this->get('logger');
        $logger->info('*** inscriptionElectedStep2Action');

        return $this->render('PolitizrFrontBundle:Security:inscriptionElectedStep2.html.twig', 
                array(
                    ));
    }


    /* ######################################################################################################## */
    /*                                                 CONNEXION OAUTH                                          */
    /* ######################################################################################################## */


    /**
     *  Action suivant la connexion oAuth
     *
     */
    public function oauthTargetAction() {
        $logger = $this->get('logger');
        $logger->info('*** oauthTargetAction');

        // Récupération de l'objet OAuthUser & mise en session des éléments associés
        $oAuthUser = $this->getUser();
        $oAuthData = $oAuthUser->getData();
        $this->get('session')->getFlashBag()->set('oAuthData', $oAuthData);

        return $this->redirect($this->generateUrl('OAuthRegister'));
    }
    
    /**
     * Check l'état d'un utilisateur suite à une connexion oAuth
     *
     */
    public function oauthRegisterAction() {
        $logger = $this->get('logger');
        $logger->info('*** oauthRegisterAction');

        $oAuthData = $this->get('session')->getFlashBag()->get('oAuthData');
        $logger->info('$oAuthData = '.print_r($oAuthData, true));

        if (!$oAuthData || !is_array($oAuthData) || !isset($oAuthData['provider']) || !isset($oAuthData['providerId'])) {
            return $this->redirect($this->generateUrl('Homepage'));
        }
        
        // Récupération du PUser éventuellement existant en base
        $pUser = PUserQuery::create()->filterByProvider($oAuthData['provider'])->filterByProviderId($oAuthData['providerId'])->findOne();

        if ($pUser) {
            // Utilisateur existant
            $logger->info('Utilisateur existant');

            // MAJ des infos relatives à la connexion
            $pUser->setOAuthData($oAuthData);

            // Save user
            $pUser->save();

            // Connexion
            $this->doPublicConnection($pUser);

            // check process d'inscription finalisé
            if ($pUser->hasRole('ROLE_CITIZEN')) {
                $logger->info('ROLE_CITIZEN ok');

                // TODO redirection dernière action en cours
                $redirectUrl = $this->generateUrl('Homepage');
                return $this->redirect($redirectUrl);
            } else {
                $logger->info('ROLE_CITIZEN pas ok');

                $redirectUrl = $this->generateUrl('InscriptionStep2');
                return $this->redirect($redirectUrl);
            }
        } else {
            // Création d'un utilisateur
            $pUser = new PUser();
            $pUser->setOAuthData($oAuthData);

            // MAJ objet
            $pUser->setEnabled(true);
            $pUser->setLastLogin(new \DateTime());

            // MAJ droits
            $pUser->addRole('ROLE_USER');

            if ($email = $pUser->getEmail()) {
                // Canonicalization
                $canonicalizeEmail = $this->get('fos_user.util.email_canonicalizer');
                $pUser->setEmailCanonical($canonicalizeEmail->canonicalize($pUser->getEmail()));

                // username = email
                $pUser->setUsername($pUser->getEmail());
                $pUser->setUsernameCanonical($pUser->getEmailCanonical());
            } elseif($nickname = $pUser->getNickname()) {
                // username = nickname
                $pUser->setUsername($pUser->getNickname());
                $pUser->setUsernameCanonical($pUser->getNickname());
            } else {
                throw new \Exception('Aucune des propriétés suivantes n\'existent: email, nickname');
            }

            // Connexion
            $this->doPublicConnection($pUser);

            // Save user
            $pUser->save();

            // Redirection process d'inscription étape 2
            return $this->redirect($this->generateUrl('InscriptionStep2'));
        }
    }
    
    /* ######################################################################################################## */
    /*                                                  FONCTIONS PRIVEES                                       */
    /* ######################################################################################################## */

    /**
     *  Connexion "logiciel" au firewall public (citoyen)
     *
     */
    private function doPublicConnection($pUser) {
        $providerKey = 'public';

        $token = new UsernamePasswordToken($pUser, null, $providerKey, $pUser->getRoles());
        $this->get('security.context')->setToken($token);
        $this->get('event_dispatcher')->dispatch(AuthenticationEvents::AUTHENTICATION_SUCCESS, new AuthenticationEvent($token));
    }

    /**
     *  Renvoie la liste des URLs de connexion oAuth disponibles
     */
    private function getOauthUrls() {
        $ret = array();
        foreach($this->get('hwi_oauth.security.oauth_utils')->getResourceOwners() as $name)
            $ret[$name] = $this->generateUrl('hwi_oauth_service_redirect', array('service'=>$name));
        return $ret;
    }

}