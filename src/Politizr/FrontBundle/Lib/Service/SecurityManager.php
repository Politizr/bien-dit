<?php
namespace Politizr\FrontBundle\Lib\Service;

use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Core\AuthenticationEvents;
use Symfony\Component\Security\Core\Event\AuthenticationEvent;

use Symfony\Component\Validator\Constraints\NotBlank;

use Symfony\Component\EventDispatcher\GenericEvent;

use Politizr\Exception\InconsistentDataException;
use Politizr\Exception\FormValidationException;

use StudioEcho\Lib\StudioEchoUtils;

use Politizr\Model\PUser;
use Politizr\Model\PUStatus;
use Politizr\Model\POPaymentType;

use Politizr\Model\POSubscriptionQuery;
use Politizr\Model\POrderQuery;
use Politizr\Model\PUserQuery;

use Politizr\FrontBundle\Form\Type\LoginType;
use Politizr\FrontBundle\Form\Type\LostPasswordType;
use Politizr\FrontBundle\Form\Type\PUserRegisterType;
use Politizr\FrontBundle\Form\Type\PUserContactType;
use Politizr\FrontBundle\Form\Type\PUserElectedRegisterType;
use Politizr\FrontBundle\Form\Type\PUserElectedMigrationType;
use Politizr\FrontBundle\Form\Type\POrderSubscriptionType;


/**
 * Services métiers associés aux process d'inscription et de connexion. 
 *
 * @author Lionel Bouzonville
 */
class SecurityManager
{
    private $sc;

    /**
     *
     */
    public function __construct($serviceContainer) {
        $this->sc = $serviceContainer;
    }

    /* ######################################################################################################## */
    /*                                                  FONCTIONS PRIVEES                                       */
    /* ######################################################################################################## */

    /**
     *  Connexion "logiciel" au firewall public (citoyen)
     *
     * @param $user    PUser object
     */
    private function doPublicConnection($user) {
        $providerKey = 'public';

        $token = new UsernamePasswordToken($user, null, $providerKey, $user->getRoles());
        $this->sc->get('security.context')->setToken($token);
        $this->sc->get('event_dispatcher')->dispatch(AuthenticationEvents::AUTHENTICATION_SUCCESS, new AuthenticationEvent($token));
    }

    /**
     *  Renvoie la liste des URLs de connexion oAuth disponibles
     */
    private function getOauthUrls() {
        $ret = array();
        foreach($this->sc->get('hwi_oauth.security.oauth_utils')->getResourceOwners() as $name)
            $ret[$name] = $this->sc->get('router')->generate('hwi_oauth_service_redirect', array('service'=>$name));
        return $ret;
    }

    /**
     *  Renvoie l'URL de redirection en fonction de l'état / des rôles du user courant.
     *
     * @param $user    PUser object
     * @return string   Redirect URL
     */
    private function computeRedirectUrl($user) {
        if ($user->hasRole('ROLE_PROFILE_COMPLETED')) {
            $user->setLastLogin(new \DateTime());
            $user->save();

            if($user->hasRole('ROLE_ELECTED') && $user->getPUStatusId() == PUStatus::ACTIVED) {
                $redirectUrl = $this->sc->get('router')->generate('HomepageE');
            } elseif ($user->hasRole('ROLE_CITIZEN')) {
                $redirectUrl = $this->sc->get('router')->generate('HomepageC');
            }
        } elseif ($user->hasRole('ROLE_CITIZEN_INSCRIPTION')) {
            $redirectUrl = $this->sc->get('router')->generate('InscriptionStep2');
        } elseif ($user->hasRole('ROLE_ELECTED_INSCRIPTION')) {
            $redirectUrl = $this->sc->get('router')->generate('InscriptionElectedStep2');
        }

        return $redirectUrl;
    }
    


    /* ######################################################################################################## */
    /*                                                  CONNEXION                                               */
    /* ######################################################################################################## */


    /**
     *  Validation de la connexion
     *
     */
    public function loginCheck() {
        $logger = $this->sc->get('logger');
        $logger->info('*** loginCheck');
        
        // Récupération args
        $request = $this->sc->get('request');

        // Form
        $form = $this->sc->get('form.factory')->create(new LoginType());

        // *********************************** //
        //      Traitement du POST
        // *********************************** //
        $form->bind($request);
        if ($form->isValid()) {
            $login = $form->getData();
            $logger->info('login = '.print_r($login, true));

            // Récupération du user
            $userManager = $this->sc->get('politizr.login_user_provider');
            $user = $userManager->loadUserByUsername($login['username']);

            if (!$user) {
                $logger->info('User / username not found.');
                $message = 'Utilisateur inconnu.';
                throw new FormValidationException($message);
            } else {
                // Contrôle user/password
                $password = $user->getPassword();

                $encoderService = $this->sc->get('security.encoder_factory');
                $encoder = $encoderService->getEncoder($user);
                $encodedPass = $encoder->encodePassword($login['password'], $user->getSalt());
                $logger->info('encodedPass = '.print_r($encodedPass, true));
                $logger->info('$password = '.print_r($password, true));

                if ($password  != $encodedPass) {
                    $logger->info('Incorrect password.');
                    $message = 'Mot de passe incorrect.';
                    throw new FormValidationException($message);
                } else {
                    // Connexion
                    $redirectUrl = $this->doPublicConnection($user);

                    // Check rôles / activ > redirection 
                    $redirectUrl = $this->computeRedirectUrl($user);

                    // Construction de la réponse
                    $jsonResponse = array (
                        'success' => true,
                        'redirectUrl' => $redirectUrl
                    );
                }
            }
        } else {
            $errors = StudioEchoUtils::getAjaxFormErrors($form);
            throw new FormValidationException($errors);
        }

        // Renvoi de l'url de redirection
        return array(
            'redirectUrl' => $redirectUrl,
            );
    }


    /**
     *  Validation de la connexion
     *
     */
    public function lostPasswordCheck() {
        $logger = $this->sc->get('logger');
        $logger->info('*** lostPasswordCheck');
        
        // Récupération args
        $request = $this->sc->get('request');

        // Form
        $form = $this->sc->get('form.factory')->create(new LostPasswordType());

        // *********************************** //
        //      Traitement du POST
        // *********************************** //
        $form->bind($request);
        if ($form->isValid()) {
            $lostPassword = $form->getData();
            $logger->info('lostPassword = '.print_r($lostPassword, true));

            // Récupération du user
            $userManager = $this->sc->get('politizr.login_user_provider');
            $user = $userManager->loadUserByEmail($lostPassword['email']);

            if (!$user) {
                $logger->info('User / email not found.');

                $message = 'Utilisateur inconnu.';
                throw new FormValidationException($message);
            } else {
                // Génération d'un nouveau mot de passe
                $password = substr(md5(uniqid(mt_rand(), true)), 0, 6);

                // Encodage MDP
                $encoderFactory = $this->sc->get('security.encoder_factory');

                $encoder = $encoderFactory->getEncoder($user);
                $user->setPassword($encoder->encodePassword($password, $user->getSalt()));
                $user->eraseCredentials();

                $user->setPlainPassword($password);
                $user->save();

                // Envoi email
                $dispatcher = $this->sc->get('event_dispatcher')->dispatch('lost_password_email', new GenericEvent($user));

                // Construction de la réponse
                $jsonResponse = array (
                    'success' => true
                );
            }
        } else {
            $errors = StudioEchoUtils::getAjaxFormErrors($form);
            throw new FormValidationException($errors);
        }

        return true;
    }


    /* ######################################################################################################## */
    /*                                     INSCRIPTION / AJAX                                               */
    /* ######################################################################################################## */


    /**
     *      Action "Procéder au paiement":
     *          1/  génération de la commande
     *          2/  suivant le type de paiement > création des formulaires (ATOS. Paypal)
     *          3/  construction de la réponse
     *
     *      TODO / + de contrôles exceptions
     *
     */
    public function paymentProcess() {
        $logger = $this->sc->get('logger');
        $logger->info('*** paymentProcess');
        
        // Récupération user
        $user = $this->sc->get('security.context')->getToken()->getUser();

        // Récupération args
        $request = $this->sc->get('request');

        // Récupération args
        $paymentTypeId = $request->get('pOPaymentTypeId');
        $logger->info('$paymentTypeId = ' . print_r($paymentTypeId, true));
        
        // Récupération formule commandée
        $subscription = POSubscriptionQuery::create()->findPk($this->sc->get('session')->get('p_o_subscription_id'));

        // Création de la commande & mise en session de son ID
        $order = POrderQuery::create()->createOrder(
                                $user, 
                                $subscription, 
                                $paymentTypeId, 
                                $this->sc->get('session')->get('p_o_supporting_document'), 
                                $this->sc->get('session')->get('p_o_elective_mandates')
                                );
        $this->sc->get('session')->set('p_order_id', $order->getId());

        // Construction de la structure
        switch($paymentTypeId) {
            case POPaymentType::BANK_TRANSFER:
                $htmlForm = '';
                $redirectUrl = $this->sc->get('router')->generate('InscriptionElectedPaymentFinished');
                $redirect = true;

                break;
            
            case POPaymentType::CREDIT_CARD:
                // construct the atos sips form
                // $sipsAtosManager = $this->sc->get('studio_echo_sips_atos');
                // $htmlForm = $sipsAtosManager->computeAtosRequest($order->getId());

                $htmlForm = '<form id="atos" action="'.$this->sc->get('router')->generate('InscriptionElectedPaymentFinished').'">Formulaire ATOS<br/><input type="submit" value="Valider"></form>';
                $redirectUrl = '';
                $redirect = false;
                break;
            
            case POPaymentType::CHECK:
                $htmlForm = '';
                $redirectUrl = $this->sc->get('router')->generate('InscriptionElectedPaymentFinished');
                $redirect = true;

                break;
            
            case POPaymentType::PAYPAL:
                // construct the paypal form
                // $paypalManager = $this->sc->get('studio_echo_paypal');
                // $htmlForm = $paypalManager->computePaypalRequest($order->getId());

                $htmlForm = '<form id="paypal" action="'.$this->sc->get('router')->generate('InscriptionElectedPaymentFinished').'">Formulaire ATOS<br/><input type="submit" value="Valider"></form>';
                $redirectUrl = '';
                $redirect = false;

                break;
            
            default:
                break;
        }

        // Renvoi de l'ensemble des blocs HTML maj
        return array(
            'success' => true,
            'htmlForm' => $htmlForm,
            'redirectUrl' => $redirectUrl,
            'redirect' => $redirect,
            );
    }



    /* ######################################################################################################## */
    /*                                     INSCRIPTION / NON AJAX                                               */
    /* ######################################################################################################## */



    /**
     *  Validation inscription / Etape 1
     *
     */
    public function inscriptionCheck() {
        $logger = $this->sc->get('logger');
        $logger->info('*** inscriptionCheck');
        
        // Récupération args
        $request = $this->sc->get('request');

        // *********************************** //
        //      Formulaire
        // *********************************** //
        $user = new PUser();
        $userFormType = new PUserRegisterType();
        $form = $this->sc->get('form.factory')->create(new PUserRegisterType(), $user);

        // *********************************** //
        //      Traitement du POST
        // *********************************** //        
        $form->bind($request);
        if ($form->isValid()) {
            $user = $form->getData();
            // $logger->info('pUser = '.print_r($user, true));

            // MAJ droits
            $user->addRole('ROLE_CITIZEN_INSCRIPTION');

            $canonicalizeUsername = $this->sc->get('fos_user.util.username_canonicalizer');
            $user->setUsernameCanonical($canonicalizeUsername->canonicalize($user->getUsername()));

            // Encodage MDP
            $encoderFactory = $this->sc->get('security.encoder_factory');

            if (0 !== strlen($password = $user->getPlainPassword())) {
                $encoder = $encoderFactory->getEncoder($user);
                $user->setPassword($encoder->encodePassword($password, $user->getSalt()));
                $user->eraseCredentials();
            }

            // Save user
            $user->save();

            // Connexion
            $this->doPublicConnection($user);

            // redirection
            return array(
                'redirectUrl' => $this->sc->get('router')->generate('InscriptionContact'),
                );
        }

        return array(
            'pUserForm' => $form->createView(),
            );
    }

    /**
     *  Validation inscription / Etape 1
     *
     */
    public function inscriptionContactCheck() {
        $logger = $this->sc->get('logger');
        $logger->info('*** inscriptionContactCheck');
        
        // Récupération user
        $user = $this->sc->get('security.context')->getToken()->getUser();

        // Récupération args
        $request = $this->sc->get('request');

        // *********************************** //
        //      Formulaire
        // *********************************** //
        $form = $this->sc->get('form.factory')->create(new PUserContactType(), $user);
        
        // *********************************** //
        //      Traitement du POST
        // *********************************** //
        $form->bind($request);

        if ($form->isValid()) {
            $user = $form->getData();
            // $logger->info('pUser = '.print_r($user, true));

            // Canonicalization
            $canonicalizeEmail = $this->sc->get('fos_user.util.email_canonicalizer');
            $user->setEmailCanonical($canonicalizeEmail->canonicalize($user->getEmail()));

            // MAJ objet
            $user->setEnabled(true);
            $user->setLastLogin(new \DateTime());

            // MAJ droits
            $user->addRole('ROLE_CITIZEN');
            $user->addRole('ROLE_PROFILE_COMPLETED');
            $user->removeRole('ROLE_CITIZEN_INSCRIPTION');

            // Save user
            $user->save();

            // (re)Connexion (/ maj droits)
            $this->doPublicConnection($user);

            // redirection
            return array(
                'redirectUrl' => $this->sc->get('router')->generate('HomepageC'),
                );
        }

        return array(
            'userForm' => $form->createView()
            );
    }


    /**
     *  Page d'inscription débatteur  / Etape 1 / Validation inscription
     *
     */
    public function inscriptionElectedCheck() {
        $logger = $this->sc->get('logger');
        $logger->info('*** inscriptionElectedCheck');
        
        // Récupération args
        $request = $this->sc->get('request');

        // *********************************** //
        //      Formulaire
        // *********************************** //
        $user = new PUser();
        $form = $this->sc->get('form.factory')->create(new PUserElectedRegisterType(), $user);
        
        // *********************************** //
        //      Traitement du POST
        // *********************************** //
        $form->bind($request);

        if ($form->isValid()) {
            $user = $form->getData();
            // $logger->info('pUser = '.print_r($user, true));

            // MAJ droits
            $user->addRole('ROLE_ELECTED_INSCRIPTION');

            $canonicalizeUsername = $this->sc->get('fos_user.util.username_canonicalizer');
            $user->setUsernameCanonical($canonicalizeUsername->canonicalize($user->getUsername()));

            // Encodage MDP
            $encoderFactory = $this->sc->get('security.encoder_factory');

            if (0 !== strlen($password = $user->getPlainPassword())) {
                $encoder = $encoderFactory->getEncoder($user);
                $user->setPassword($encoder->encodePassword($password, $user->getSalt()));
                $user->eraseCredentials();
            }

            // Canonicalization
            $canonicalizeEmail = $this->sc->get('fos_user.util.email_canonicalizer');
            $user->setEmailCanonical($canonicalizeEmail->canonicalize($user->getEmail()));

            // Save user
            $user->save();

            // *************************************** //
            //      Gestion des justificatifs
            // *************************************** //
            // gestion upload pièce ID
            $file = $form['uploaded_supporting_document']->getData();
            if ($file) {
                $supportingDocument = $file->move($this->sc->get('kernel')->getRootDir() . '/../web/uploads/supporting/', $file->getClientOriginalName());
                $this->sc->get('session')->set('p_o_supporting_document', $supportingDocument->getBasename());
            }

            // gestion mandats électifs
            $electiveMandates = $form['elective_mandates']->getData();
            $this->sc->get('session')->set('p_o_elective_mandates', $electiveMandates);

            // Connexion
            $this->doPublicConnection($user);

            // redirection
            return array(
                'redirectUrl' => $this->sc->get('router')->generate('InscriptionElectedOrder'),
                );
        }

        // *********************************** //
        //      Affichage de la vue
        // *********************************** //
        return array(
            'userForm' => $form->createView()
            );
    }


    /**
     *  Page d'inscription débatteur  / Etape 1 / Validation migration de compte
     *
     */
    public function migrationElectedCheck() {
        $logger = $this->sc->get('logger');
        $logger->info('*** migrationElectedCheck');
        
        // Récupération user
        $user = $this->sc->get('security.context')->getToken()->getUser();

        // Récupération args
        $request = $this->sc->get('request');

        // *********************************** //
        //      Formulaire
        // *********************************** //
        $form = $this->sc->get('form.factory')->create(new PUserElectedMigrationType(), $user);
        
        // *********************************** //
        //      Traitement du POST
        // *********************************** //
        $request = $this->sc->get('request');

        if ($form->isValid()) {
            $user = $form->getData();
            // $logger->info('pUser = '.print_r($user, true));

            // MAJ droits
            $user->addRole('ROLE_ELECTED_INSCRIPTION');

            // Save user
            $user->save();

            // *************************************** //
            //      Gestion des justificatifs
            // *************************************** //
            // gestion upload pièce ID
            $file = $form['uploaded_supporting_document']->getData();
            if ($file) {
                $supportingDocument = $file->move($this->sc->get('kernel')->getRootDir() . '/../web/uploads/supporting/', $file->getClientOriginalName());
                $this->sc->get('session')->set('p_o_supporting_document', $supportingDocument->getBasename());
            }

            // gestion mandats électifs
            $electiveMandates = $form['elective_mandates']->getData();
            $this->sc->get('session')->set('p_o_elective_mandates', $electiveMandates);

            // Connexion
            $this->doPublicConnection($user);

            // redirection
            return array(
                'redirectUrl' => $this->sc->get('router')->generate('InscriptionElectedOrder'),
                );
        }

        // *********************************** //
        //      Affichage de la vue
        // *********************************** //
        return array(
            'userForm' => $form->createView()
            );
    }

    /**
     *  Page d'inscription débatteur / Etape 2 / Validation choix de la formule
     *
     */
    public function inscriptionElectedOrderCheck() {
        $logger = $this->sc->get('logger');
        $logger->info('*** inscriptionElectedOrderCheck');
        
        // Récupération user
        $user = $this->sc->get('security.context')->getToken()->getUser();

        // Récupération args
        $request = $this->sc->get('request');

        // *********************************** //
        //      Formulaire
        // *********************************** //
        $form = $this->sc->get('form.factory')->create(new POrderSubscriptionType());
        
        // *********************************** //
        //      Traitement du POST
        // *********************************** //
        $request = $this->sc->get('request');
        $form->bind($request);
        if ($form->isValid()) {
            $datas = $form->getData();
            $subscription = $datas['p_o_subscription'];

            // Mise en session de la formule choisie
            $this->sc->get('session')->set('p_o_subscription_id', $subscription->getId());

            // redirection
            return array(
                'redirectUrl' => $this->sc->get('router')->generate('InscriptionElectedPayment'),
                );
        }

        // Cas migration formule > MAJ du layout
        $layout = 'PolitizrFrontBundle::layout.html.twig';
        if ($user->hasRole('ROLE_CITIZEN')) {
            $layout = 'PolitizrFrontBundle::layoutC.html.twig';
        }

        return array(
            'subscriptionForm' => $form->createView(),
            'layout' => $layout
            );
    }



    /**
     *  Page d'inscription débatteur / Etape 3 / Paiement terminé
     *
     */
    public function inscriptionElectedPaymentFinished() {
        $logger = $this->sc->get('logger');
        $logger->info('*** inscriptionElectedPaymentFinished');
        
        // Récupération user
        $user = $this->sc->get('security.context')->getToken()->getUser();

        // Récupération de la commande en cours
        $orderId = $this->sc->get('session')->get('p_order_id');

        // Récupération commande
        $order = POrderQuery::create()->findPk($orderId);
        if (!$order) {
            throw new \Exception('Order id '.$orderId.' not found.');
        }

        // MAJ statut commande en fonction du type de paiement
        switch($order->getPOPaymentTypeId()) {
            case POPaymentType::BANK_TRANSFER:
            case POPaymentType::CHECK:
                // MAJ statut / état
                $order->setPOOrderStateId(POOrderState::WAITING);
                $order->setPOPaymentStateId(POPaymentState::WAITING);
                $order->save();

                // Email
                $dispatcher = $this->sc->get('event_dispatcher')->dispatch('order_email', new GenericEvent($order));

                break;
                        
            case POPaymentType::CREDIT_CARD:
            case POPaymentType::PAYPAL:
                // MAJ statut / etat / maj stocks / envoi email => via listener retour ATOS / Paypal
                break;
            
            default:
                break;
        }

        return $this->sc->get('router')->generate('InscriptionElectedThanking');
    }


    /**
     *  Page d'inscription débatteur / Etape 3 / Annulation paiement
     *
     */
    public function inscriptionElectedPaymentCanceled() {
        $logger = $this->sc->get('logger');
        $logger->info('*** inscriptionElectedPaymentCanceled');
        
        // Récupération de la commande en cours
        $orderId = $this->sc->get('session')->get('p_order_id');

        // Récupération commande
        $order = POrderQuery::create()->findPk($orderId);
        if (!$order) {
            throw new \Exception('Order id '.$orderId.' not found.');
        }

        // Suppression commande annulée
        if ($order) {
            $order->delete();
        }
        $session->remove('p_order_id');

        return $this->sc->get('router')->generate('InscriptionElectedPayment');
    }

    /**
     *  Page d'inscription débatteur / Etape 4 / Remerciement
     *
     */
    public function inscriptionElectedThanking() {
        $logger = $this->sc->get('logger');
        $logger->info('*** inscriptionElectedThanking');
        
        // Récupération user
        $user = $this->sc->get('security.context')->getToken()->getUser();

        // Récupération de la commande en cours
        $orderId = $this->sc->get('session')->get('p_order_id');

        // Récupération commande
        $order = POrderQuery::create()->findPk($orderId);
        if (!$order) {
            // throw new \Exception('Order id '.$orderId.' not found.');
            $this->sc->get('session')->getFlashBag()->add('error', 'Session expirée.');

            // redirection
            return array(
                'redirectUrl' => $this->sc->get('router')->generate('Homepage'),
                );
        }

        // Suppression rôle user / déconnexion
        $user->addRole('ROLE_ELECTED');
        $user->addRole('ROLE_PROFILE_COMPLETED');
        $user->removeRole('ROLE_ELECTED_INSCRIPTION');
        $user->setPUStatusId(PUStatus::VALIDATION_PROCESS);
        $user->save();

        // Suppression des valeurs en session
        $this->sc->get('session')->remove('p_o_subscription_id');
        $this->sc->get('session')->remove('p_order_id');

        // Droits citoyen en attendant la validation
        if (!$user->hasRole('ROLE_CITIZEN')) {
            $user->addRole('ROLE_CITIZEN');

            // Save user
            $user->save();

            // (re)Connexion (/ maj droits)
            $this->doPublicConnection($user);
        }

        // Cas migration formule > MAJ du layout
        $layout = 'PolitizrFrontBundle::layout.html.twig';
        if ($user->hasRole('ROLE_CITIZEN')) {
            $layout = 'PolitizrFrontBundle::layoutC.html.twig';
        }

        return array(
            'layout' => $layout,
            'order' => $order,
            );
    }


    /* ######################################################################################################## */
    /*                                 CONNEXION OAUTH / NON AJAX                                               */
    /* ######################################################################################################## */


    /**
     *  Check l'état d'un utilisateur suite à une connexion oAuth
     *
     */
    public function oauthRegister() {
        $logger = $this->sc->get('logger');
        $logger->info('*** oauthRegister');
        
        // Récupération user
        $user = $this->sc->get('security.context')->getToken()->getUser();

        // Récupération données oAuth
        $oAuthData = $this->sc->get('session')->getFlashBag()->get('oAuthData');
        $logger->info('$oAuthData = '.print_r($oAuthData, true));

        if (!$oAuthData || !is_array($oAuthData) || !isset($oAuthData['provider']) || !isset($oAuthData['providerId'])) {
            return $this->sc->get('router')->generate('Homepage');
        }
        
        // Récupération du user existant en base
        $user = PUserQuery::create()->filterByProvider($oAuthData['provider'])->filterByProviderId($oAuthData['providerId'])->findOne();

        if ($user) {
            // Utilisateur existant
            $logger->info('Utilisateur existant');

            // MAJ des infos relatives à la connexion
            $user->setOAuthData($oAuthData);

            // Save user
            $user->save();

            // Connexion
            $this->doPublicConnection($user);

            // Redirection
            $redirectUrl = $this->computeRedirectUrl($user);

            return $redirectUrl;
        } else {
            // Création d'un utilisateur
            $user = new PUser();
            $user->setOAuthData($oAuthData);

            // MAJ objet
            $user->setEnabled(true);
            $user->setPUStatusId(PUStatus::ACTIVED);
            $user->setPUTypeId(PUType::TYPE_CITOYEN);
            $user->setLastLogin(new \DateTime());

            // MAJ droits
            $user->addRole('ROLE_CITIZEN_INSCRIPTION');

            if ($email = $user->getEmail()) {
                // Canonicalization
                $canonicalizeEmail = $this->sc->get('fos_user.util.email_canonicalizer');
                $user->setEmailCanonical($canonicalizeEmail->canonicalize($user->getEmail()));

                // username = email
                $user->setUsername($user->getEmail());
                $user->setUsernameCanonical($user->getEmailCanonical());
            } elseif($nickname = $user->getNickname()) {
                // username = nickname
                $user->setUsername($user->getNickname());
                $user->setUsernameCanonical($user->getNickname());
            } else {
                throw new \Exception('Aucune des propriétés suivantes n\'existent: email, nickname');
            }

            // Connexion
            $this->doPublicConnection($user);

            // Save user
            $user->save();

            // Redirection process d'inscription étape 2
            return $this->sc->get('router')->generate('InscriptionContact');
        }
    }

}