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

use Symfony\Component\Validator\Constraints\NotBlank;

use Symfony\Component\EventDispatcher\GenericEvent;

use StudioEcho\Lib\StudioEchoUtils;

use Politizr\Model\PUserQuery;
use Politizr\Model\POrderQuery;
use Politizr\Model\POPaymentTypeQuery;
use Politizr\Model\POSubscriptionQuery;

use Politizr\Model\PUser;
use Politizr\Model\PUType;
use Politizr\Model\PUStatus;
use Politizr\Model\POrder;
use Politizr\Model\POOrderState;
use Politizr\Model\POPaymentState;
use Politizr\Model\POPaymentType;

use Politizr\FrontBundle\Form\Type\PUserRegisterType;
use Politizr\FrontBundle\Form\Type\PUserContactType;

use Politizr\FrontBundle\Form\Type\PUserElectedRegisterType;
use Politizr\FrontBundle\Form\Type\PUserElectedMigrationType;

use Politizr\FrontBundle\Form\Type\LoginType;
use Politizr\FrontBundle\Form\Type\LostPasswordType;


/**
 * Gestion des inscriptions / connexions
 *
 * http://nyrodev.info/fr/posts/286/Connexions-OAuth-Multiple-avec-Symfony-2-3
 *
 * TODO:
 *  - lire:
 *          http://sirprize.me/scribble/under-the-hood-of-symfony-security/
 *          http://www.reecefowell.com/2011/10/26/redirecting-on-loginlogout-in-symfony2-using-loginhandlers/
 *  - sortir les envois d'emails dans une classe dédiée
 *  - implémentation BaseFacebook pour exploitation API et récupération infos annexes supplémentaires (photo)
 *  - personnaliser les exception
 *  - ajout de nouvelles étapes d'inscription > proposition de suggestions + gestion des affinités politiques (citoyen)
 *
 * @author Lionel Bouzonville
 */
class SecurityController extends Controller {

    /* ######################################################################################################## */
    /*                                                 CONNEXION                                                */
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
                    $user = $userManager->loadUserByUsername($login['username']);

                    if (!$user) {
                        $logger->info('User / username not found.');

                        $message = 'Utilisateur inconnu.';
                        $jsonResponse = array(
                            'error' => $message
                            );
                    } else {
                        // Contrôle user/password
                        $password = $user->getPassword();

                        $encoderService = $this->get('security.encoder_factory');
                        $encoder = $encoderService->getEncoder($user);
                        $encodedPass = $encoder->encodePassword($login['password'], $user->getSalt());
                        $logger->info('encodedPass = '.print_r($encodedPass, true));
                        $logger->info('$password = '.print_r($password, true));

                        if ($password  != $encodedPass) {
                            $logger->info('Incorrect password.');

                            $message = 'Mot de passe incorrect.';
                            $jsonResponse = array(
                                'error' => $message
                                );
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
                    $errors = StudioEchoUtils::getAjaxFormErrors($formLogin);
                    $jsonResponse = array(
                        'error' => $errors
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

                        $user->setPlainPassword($password);
                        $user->save();

                        // Envoi email
                        $dispatcher = $this->get('event_dispatcher')->dispatch('lost_password_email', new GenericEvent($user));

                        // Construction de la réponse
                        $jsonResponse = array (
                            'success' => true
                        );
                    }
                } else {
                    $errors = StudioEchoUtils::getAjaxFormErrors($formLostPassword);
                    $jsonResponse = array(
                        'error' => $errors
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

        // Objet & formulaire
        $user = new PUser();
        $userFormType = new PUserRegisterType();
        $userForm = $this->createForm($userFormType, $user);
        
        // *********************************** //
        //      Affichage de la vue
        // *********************************** //

        return $this->render('PolitizrFrontBundle:Public:inscription.html.twig', 
                array(
                    'userForm' => $userForm->createView()
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
        $user = $this->getUser();
        $user = new PUser();
        $userFormType = new PUserRegisterType();
        $userForm = $this->createForm($userFormType, $user);
        
        // *********************************** //
        //      Traitement du POST
        // *********************************** //
        $request = $this->get('request');
        // TODO > migrer le contrôle POST au niveau du routing (_method = POST) + supprimer le contrôle if POST dans le code
        if ($request->getMethod() == 'POST') {
            $userForm->bind($this->getRequest());

            if ($userForm->isValid()) {
                $user = $userForm->getData();
                // $logger->info('pUser = '.print_r($user, true));

                // MAJ droits
                $user->addRole('ROLE_CITIZEN_INSCRIPTION');

                $canonicalizeUsername = $this->get('fos_user.util.username_canonicalizer');
                $user->setUsernameCanonical($canonicalizeUsername->canonicalize($user->getUsername()));

                // Encodage MDP
                $encoderFactory = $this->get('security.encoder_factory');

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
                $url = $this->container->get('router')->generate('InscriptionContact');
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
                        'pUserForm' => $userForm->createView()
            ));
    }

    /**
     *     Page d'inscription / Etape 2 / Contact
     */
    public function inscriptionContactAction()
    {
        $logger = $this->get('logger');
        $logger->info('*** inscriptionContactAction');

        // *********************************** //
        //      Formulaire
        // *********************************** //
        $user = $this->getUser();
        $userFormType = new PUserContactType();
        $userForm = $this->createForm($userFormType, $user);
        
        // *********************************** //
        //      Affichage de la vue
        // *********************************** //

        return $this->render('PolitizrFrontBundle:Security:inscriptionContact.html.twig', 
                array(
                    'userForm' => $userForm->createView()
                    ));
    }

    /**
     *      Page d'inscription / Etape 2 / Validation inscription
     */
    public function inscriptionContactCheckAction()
    {
        $logger = $this->get('logger');
        $logger->info('*** inscriptionContactCheckAction');

        // *********************************** //
        //      Formulaire
        // *********************************** //
        $user = $this->getUser();
        $userFormType = new PUserContactType();
        $userForm = $this->createForm($userFormType, $user);
        
        // *********************************** //
        //      Traitement du POST
        // *********************************** //
        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            $userForm->bind($this->getRequest());

            if ($userForm->isValid()) {
                $user = $userForm->getData();
                // $logger->info('pUser = '.print_r($user, true));

                // Canonicalization
                $canonicalizeEmail = $this->get('fos_user.util.email_canonicalizer');
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
                $url = $this->container->get('router')->generate('HomepageC');
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

        return $this->render('PolitizrFrontBundle:Security:inscriptionContact.html.twig', array(
                        'userForm' => $userForm->createView()
            ));
    }

    /* ######################################################################################################## */
    /*                           INSCRIPTION ELU + MIGRATION CIOYEN VERS ELU                                    */
    /* ######################################################################################################## */

    /**
     *     Page d'inscription élu  / Etape 1 / Inscription
     */
    public function inscriptionElectedAction()
    {
        $logger = $this->get('logger');
        $logger->info('*** inscriptionElectedAction');

        // *********************************** //
        //      Formulaire
        // *********************************** //

        $user = new PUser();
        $userFormType = new PUserElectedRegisterType();
        $userForm = $this->createForm($userFormType, $user);
        
        // *********************************** //
        //      Affichage de la vue
        // *********************************** //
        return $this->render('PolitizrFrontBundle:Public:inscriptionElected.html.twig', 
                array(
                    'userForm' => $userForm->createView()
                    ));
    }

    /**
     *      Page d'inscription élu  / Etape 1 / Validation inscription
     */
    public function inscriptionElectedCheckAction()
    {
        $logger = $this->get('logger');
        $logger->info('*** inscriptionElectedCheckAction');

        // *********************************** //
        //      Formulaire
        // *********************************** //
        $user = new PUser();
        $userFormType = new PUserElectedRegisterType();
        $userForm = $this->createForm($userFormType, $user);
        
        // *********************************** //
        //      Traitement du POST
        // *********************************** //
        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            $userForm->bind($this->getRequest());

            if ($userForm->isValid()) {
                $user = $userForm->getData();
                // $logger->info('pUser = '.print_r($user, true));

                // MAJ droits
                $user->addRole('ROLE_ELECTED_INSCRIPTION');

                $canonicalizeUsername = $this->get('fos_user.util.username_canonicalizer');
                $user->setUsernameCanonical($canonicalizeUsername->canonicalize($user->getUsername()));

                // Encodage MDP
                $encoderFactory = $this->get('security.encoder_factory');

                if (0 !== strlen($password = $user->getPlainPassword())) {
                    $encoder = $encoderFactory->getEncoder($user);
                    $user->setPassword($encoder->encodePassword($password, $user->getSalt()));
                    $user->eraseCredentials();
                }

                // Canonicalization
                $canonicalizeEmail = $this->get('fos_user.util.email_canonicalizer');
                $user->setEmailCanonical($canonicalizeEmail->canonicalize($user->getEmail()));

                // Save user
                $user->save();

                // *************************************** //
                //      Gestion des justificatifs
                // *************************************** //
                // gestion upload pièce ID
                $file = $userForm['uploaded_supporting_document']->getData();
                if ($file) {
                    $supportingDocument = $file->move($this->get('kernel')->getRootDir() . '/../web/uploads/supporting/', $file->getClientOriginalName());
                    $this->get('session')->set('p_o_supporting_document', $supportingDocument->getBasename());
                }

                // gestion mandats électifs
                $electiveMandates = $userForm['elective_mandates']->getData();
                $this->get('session')->set('p_o_elective_mandates', $electiveMandates);

                // Connexion
                $this->doPublicConnection($user);

                // redirection
                $url = $this->container->get('router')->generate('InscriptionElectedOrder');
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
                        'userForm' => $userForm->createView()
            ));
    }


    /**
     *     Page d'inscription élu  / Etape 1 / Migration de compte
     */
    public function migrationElectedAction()
    {
        $logger = $this->get('logger');
        $logger->info('*** migrationElectedAction');

        // *********************************** //
        //      Formulaire
        // *********************************** //
        $user = $this->getUser();


        // Test si le profil a déjà été validé => étape 2 directement
        if ($user->getValidated()) {
            // MAJ droits
            $user->addRole('ROLE_ELECTED_INSCRIPTION');

            // Connexion
            $this->doPublicConnection($user);

            // redirection
            $url = $this->container->get('router')->generate('InscriptionElectedOrder');
            return $this->redirect($url);
        }

        // Inscription depuis un compte citoyen
        $userFormType = new PUserElectedMigrationType();
        $userForm = $this->createForm($userFormType, $user);
        
        // *********************************** //
        //      Affichage de la vue
        // *********************************** //
        return $this->render('PolitizrFrontBundle:Security:migrationElected.html.twig', 
                array(
                    'userForm' => $userForm->createView()
                    ));
    }


    /**
     *      Page d'inscription élu  / Etape 1 / Validation migration de compte
     */
    public function migrationElectedCheckAction()
    {
        $logger = $this->get('logger');
        $logger->info('*** migrationElectedCheckAction');

        // *********************************** //
        //      Formulaire
        // *********************************** //
        $user = $this->getUser();

        $userFormType = new PUserElectedMigrationType();
        $userForm = $this->createForm($userFormType, $user);
        
        // *********************************** //
        //      Traitement du POST
        // *********************************** //
        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            $userForm->bind($this->getRequest());

            if ($userForm->isValid()) {
                $user = $userForm->getData();
                // $logger->info('pUser = '.print_r($user, true));

                // MAJ droits
                $user->addRole('ROLE_ELECTED_INSCRIPTION');

                // Save user
                $user->save();

                // *************************************** //
                //      Gestion des justificatifs
                // *************************************** //
                // gestion upload pièce ID
                $file = $userForm['uploaded_supporting_document']->getData();
                if ($file) {
                    $supportingDocument = $file->move($this->get('kernel')->getRootDir() . '/../web/uploads/supporting/', $file->getClientOriginalName());
                    $this->get('session')->set('p_o_supporting_document', $supportingDocument->getBasename());
                }

                // gestion mandats électifs
                $electiveMandates = $userForm['elective_mandates']->getData();
                $this->get('session')->set('p_o_elective_mandates', $electiveMandates);

                // Connexion
                $this->doPublicConnection($user);

                // redirection
                $url = $this->container->get('router')->generate('InscriptionElectedOrder');
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
        return $this->render('PolitizrFrontBundle:Security:migrationElected.html.twig', array(
                        'userForm' => $userForm->createView()
            ));
    }



    /**
     *     Page d'inscription élu / Etape 2 / Choix de la formule
     */
    public function inscriptionElectedOrderAction()
    {
        $logger = $this->get('logger');
        $logger->info('*** inscriptionElectedOrderAction');

        // *********************************** //
        //      Formulaire
        // *********************************** //
        $user = $this->getUser();

        // Formulaire "simple"
        $subscriptionForm = $this->createFormBuilder()
            ->add('p_o_subscription', 'model', array(
                'required' => true,
                'label' => 'Formule',
                'class' => 'Politizr\\Model\\POSubscription',
                'property' => 'titleAndPrice',
                'multiple' => false,
                'expanded' => true,
                'constraints' => new NotBlank(array('message' => 'Choix de la formule obligatoire.')),
            ))
            ->add('actions', 'form_actions', [
                'buttons' => [
                    'save' => ['type' => 'submit', 'options' => ['label' => 'Valider', 'attr' => [ 'class' => 'btn-success' ] ]],
                ]
            ])            
            ->getForm();

        // *********************************** //
        //      Affichage de la vue
        // *********************************** //

        // Cas migration formule > MAJ du layout
        $layout = 'PolitizrFrontBundle::layout.html.twig';
        if ($user->hasRole('ROLE_CITIZEN')) {
            $layout = 'PolitizrFrontBundle::layoutC.html.twig';
        }

        return $this->render('PolitizrFrontBundle:Security:inscriptionElectedOrder.html.twig', array(
                    'subscriptionForm' => $subscriptionForm->createView(),
                    'layout' => $layout,
            ));
    }


    /**
     *      Page d'inscription élu / Etape 2 / Validation choix de la formule
     */
    public function inscriptionElectedOrderCheckAction()
    {
        $logger = $this->get('logger');
        $logger->info('*** inscriptionElectedOrderCheckAction');

        // *********************************** //
        //      Formulaire
        // *********************************** //
        $user = $this->getUser();

        // Formulaire "simple"
        $subscriptionForm = $this->createFormBuilder()
            ->add('p_o_subscription', 'model', array(
                'required' => true,
                'label' => 'Formule',
                'class' => 'Politizr\\Model\\POSubscription',
                'property' => 'titleAndPrice',
                'multiple' => false,
                'expanded' => true,
                'constraints' => new NotBlank(array('message' => 'Choix de la formule obligatoire.')),
            ))
            ->add('actions', 'form_actions', [
                'buttons' => [
                    'save' => ['type' => 'submit', 'options' => ['label' => 'Valider', 'attr' => [ 'class' => 'btn-success' ] ]],
                ]
            ])            
            ->getForm();
        
        // *********************************** //
        //      Traitement du POST
        // *********************************** //
        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            $subscriptionForm->bind($this->getRequest());

            if ($subscriptionForm->isValid()) {
                $datas = $subscriptionForm->getData();
                $subscription = $datas['p_o_subscription'];

                // TODO + de contrôle sur $subscription?

                // Mise en session de la formule choisie
                // TODO normalisation des variables session type politizr/order/...
                $this->get('session')->set('p_o_subscription_id', $subscription->getId());

                // redirection
                $url = $this->container->get('router')->generate('InscriptionElectedPayment');
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

        // Cas migration formule > MAJ du layout
        $layout = 'PolitizrFrontBundle::layout.html.twig';
        if ($user->hasRole('ROLE_CITIZEN')) {
            $layout = 'PolitizrFrontBundle::layoutC.html.twig';
        }

        return $this->render('PolitizrFrontBundle:Security:inscriptionElectedOrder.html.twig', array(
                        'subscriptionForm' => $subscriptionForm->createView(),
                        'layout' => $layout
            ));
    }

    /**
     *     Page d'inscription élu / Etape 3 / Paiement
     */
    public function inscriptionElectedPaymentAction()
    {
        $logger = $this->get('logger');
        $logger->info('*** inscriptionElectedPaymentAction');

        // *********************************** //
        //      Formulaire
        // *********************************** //
        // TODO / redirection si connecté
        $user = $this->getUser();

        // Listes des moyens de paiement / gestion hors form pour chargement dynamique des formulaires paypal/banque & pavés d'informations spécifiques
        $payments = POPaymentTypeQuery::create()->filterByOnline(true)->orderByRank()->find();
        
        // *********************************** //
        //      Affichage de la vue
        // *********************************** //

        // Cas migration formule > MAJ du layout
        $layout = 'PolitizrFrontBundle::layout.html.twig';
        if ($user->hasRole('ROLE_CITIZEN')) {
            $layout = 'PolitizrFrontBundle::layoutC.html.twig';
        }

        return $this->render('PolitizrFrontBundle:Security:inscriptionElectedPayment.html.twig', array(
                    'payments' => $payments,
                    'layout' => $layout
                ));
    }


    /**
     *     Page d'inscription élu / Etape 3 / Paiement terminé
     */
    public function inscriptionElectedPaymentFinishedAction()
    {
        $logger = $this->get('logger');
        $logger->info('*** inscriptionElectedPaymentFinishedAction');

        // *********************************** //
        //      Formulaire
        // *********************************** //
        $user = $this->getUser();

        // Récupération de la commande en cours
        $orderId = $this->get('session')->get('p_order_id');

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
                $dispatcher = $this->get('event_dispatcher')->dispatch('order_email', new GenericEvent($order));

                break;
                        
            case POPaymentType::CREDIT_CARD:
            case POPaymentType::PAYPAL:
                // MAJ statut / etat / maj stocks / envoi email => via listener retour ATOS / Paypal
                break;
            
            default:
                break;
        }

        return $this->redirect($this->generateUrl('InscriptionElectedThanking'));
    }

    /**
     *  Page d'inscription élu / Etape 3 / Annulation paiement
     *
     */
    public function inscriptionElectedPaymentCanceledAction(Request $request)
    {
        $logger = $this->get('logger');
        $logger->info('*** inscriptionElectedPaymentCanceledAction');

        // Récupération de la commande en cours
        $orderId = $this->get('session')->get('p_order_id');

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

        return $this->redirect($this->generateUrl('InscriptionElectedPayment'));
    }



    /**
     *     Page d'inscription élu / Etape 4 / Remerciement
     */
    public function inscriptionElectedThankingAction()
    {
        $logger = $this->get('logger');
        $logger->info('*** inscriptionElectedThankingAction');

        // *********************************** //
        //      Gestion user / order
        // *********************************** //
        $user = $this->getUser();

        // Récupération de la commande en cours
        $orderId = $this->get('session')->get('p_order_id');

        // Récupération commande
        $order = POrderQuery::create()->findPk($orderId);
        if (!$order) {
            // throw new \Exception('Order id '.$orderId.' not found.');
            $this->get('session')->getFlashBag()->add('error', 'Session expirée.');
            return $this->redirect($this->generateUrl('Homepage'));
        }

        // Suppression rôle user / déconnexion
        $user->addRole('ROLE_ELECTED');
        $user->addRole('ROLE_PROFILE_COMPLETED');
        $user->removeRole('ROLE_ELECTED_INSCRIPTION');
        $user->setPUStatusId(PUStatus::VALIDATION_PROCESS);
        $user->save();

        // Suppression des valeurs en session
        $this->get('session')->remove('p_o_subscription_id');
        $this->get('session')->remove('p_order_id');

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

        // *********************************** //
        //      Affichage de la vue
        // *********************************** //
        return $this->render('PolitizrFrontBundle:Security:inscriptionElectedThanking.html.twig', array(
                    'layout' => $layout,
                    'order' => $order,
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

            return $this->redirect($redirectUrl);
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
                $canonicalizeEmail = $this->get('fos_user.util.email_canonicalizer');
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
            return $this->redirect($this->generateUrl('InscriptionContact'));
        }
    }

    
    /* ######################################################################################################## */
    /*                                                  FONCTIONS AJAX                                          */
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
    public function paymentProcessAction(Request $request) {
        $logger = $this->get('logger');
        $logger->info('*** paymentProcessAction');
        
        try {
            if ($request->isXmlHttpRequest()) {
                // Récupération args
                $paymentTypeId = $request->get('pOPaymentTypeId');
                $logger->info('$paymentTypeId = ' . print_r($paymentTypeId, true));
                
                // Récupération user session
                $user = $this->getUser();

                // Récupération formule commandée
                $subscription = POSubscriptionQuery::create()->findPk($this->get('session')->get('p_o_subscription_id'));

                // Création de la commande & mise en session de son ID
                $order = POrderQuery::create()->createOrder(
                                        $user, 
                                        $subscription, 
                                        $paymentTypeId, 
                                        $this->get('session')->get('p_o_supporting_document'), 
                                        $this->get('session')->get('p_o_elective_mandates')
                                        );
                $this->get('session')->set('p_order_id', $order->getId());

                // Construction de la structure
                switch($paymentTypeId) {
                    case POPaymentType::BANK_TRANSFER:
                        $htmlForm = '';
                        $redirectUrl = $this->generateUrl('InscriptionElectedPaymentFinished');
                        $redirect = true;

                        break;
                    
                    case POPaymentType::CREDIT_CARD:
                        // construct the atos sips form
                        // $sipsAtosManager = $this->get('studio_echo_sips_atos');
                        // $htmlForm = $sipsAtosManager->computeAtosRequest($order->getId());

                        $htmlForm = '<form id="atos" action="'.$this->generateUrl('InscriptionElectedPaymentFinished').'">Formulaire ATOS<br/><input type="submit" value="Valider"></form>';
                        $redirectUrl = '';
                        $redirect = false;
                        break;
                    
                    case POPaymentType::CHECK:
                        $htmlForm = '';
                        $redirectUrl = $this->generateUrl('InscriptionElectedPaymentFinished');
                        $redirect = true;

                        break;
                    
                    case POPaymentType::PAYPAL:
                        // construct the paypal form
                        // $paypalManager = $this->get('studio_echo_paypal');
                        // $htmlForm = $paypalManager->computePaypalRequest($order->getId());

                        $htmlForm = '<form id="paypal" action="'.$this->generateUrl('InscriptionElectedPaymentFinished').'">Formulaire ATOS<br/><input type="submit" value="Valider"></form>';
                        $redirectUrl = '';
                        $redirect = false;

                        break;
                    
                    default:
                        break;
                }

                // Construction de la réponse
                $jsonResponse = array (
                    'success' => true,
                    'htmlForm' => $htmlForm,
                    'redirectUrl' => $redirectUrl,
                    'redirect' => $redirect,
                );
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
                $redirectUrl = $this->generateUrl('HomepageE');
            } elseif ($user->hasRole('ROLE_CITIZEN')) {
                $redirectUrl = $this->generateUrl('HomepageC');
            }
        } elseif ($user->hasRole('ROLE_CITIZEN_INSCRIPTION')) {
            $redirectUrl = $this->generateUrl('InscriptionStep2');
        } elseif ($user->hasRole('ROLE_ELECTED_INSCRIPTION')) {
            $redirectUrl = $this->generateUrl('InscriptionElectedStep2');
        }

        return $redirectUrl;
    }
    

}