<?php
namespace Politizr\FrontBundle\Lib\Xhr;

use Symfony\Component\EventDispatcher\GenericEvent;

use Politizr\Exception\InconsistentDataException;
use Politizr\Exception\FormValidationException;

use StudioEcho\Lib\StudioEchoUtils;

use Politizr\Model\POPaymentType;

use Politizr\Model\POSubscriptionQuery;
use Politizr\Model\POrderQuery;

use Politizr\FrontBundle\Form\Type\LoginType;
use Politizr\FrontBundle\Form\Type\LostPasswordType;

/**
 * XHR service for security management.
 *
 * @author Lionel Bouzonville
 */
class XhrSecurity
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
    /*                                                  CONNECTION                                              */
    /* ######################################################################################################## */

    /**
     *  Connexion
     */
    public function login()
    {
        $logger = $this->sc->get('logger');
        $logger->info('*** login');
        
        // Formulaire
        $formLogin = $this->sc->get('form.factory')->create(new LoginType());
        $formLostPassword = $this->sc->get('form.factory')->create(new LostPasswordType());

        // Construction rendu
        $templating = $this->sc->get('templating');
        $html = $templating->render(
            'PolitizrFrontBundle:Public:_login.html.twig',
            array(
                'formLogin' => $formLogin->createView(),
                'formLostPassword' => $formLostPassword->createView(),
            )
        );

        return array(
            'html' => $html,
            );
    }

    /**
     *  Validation de la connexion
     *
     */
    public function loginCheck()
    {
        $logger = $this->sc->get('logger');
        $logger->info('*** loginCheck');

        // Retrieve used services
        $securityService = $this->sc->get('politizr.functional.security');
        
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
                    $redirectUrl = $securityService->doPublicConnection($user);

                    // Check rôles / activ > redirection
                    $redirectUrl = $securityService->computeRedirectUrl($user);

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
    public function lostPasswordCheck()
    {
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
    /*                                                  INSCRIPTION                                             */
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
    public function paymentProcess()
    {
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
}
