<?php
namespace Politizr\FrontBundle\Lib\Xhr;

use Symfony\Component\EventDispatcher\GenericEvent;

use Politizr\Exception\InconsistentDataException;
use Politizr\Exception\FormValidationException;

use StudioEcho\Lib\StudioEchoUtils;

use Politizr\Model\POPaymentType;

use Politizr\Model\PUserQuery;
use Politizr\Model\POSubscriptionQuery;

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
     *  Connection init screen
     */
    public function login()
    {
        $logger = $this->sc->get('logger');
        $logger->info('*** login');

        // Retrieve used services
        $formFactory = $this->sc->get('form.factory');
        $templating = $this->sc->get('templating');

        // Function process
        $formLogin = $formFactory->create(new LoginType());
        $formLostPassword = $formFactory->create(new LostPasswordType());

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
     * Connection
     */
    public function loginCheck()
    {
        $logger = $this->sc->get('logger');
        $logger->info('*** loginCheck');

        // Retrieve used services
        $request = $this->sc->get('request');
        $securityService = $this->sc->get('politizr.functional.security');
        $formFactory = $this->sc->get('form.factory');
        $encoderFactory = $this->sc->get('security.encoder_factory');

        // Function process
        $form = $formFactory->create(new LoginType());

        $form->bind($request);
        if ($form->isValid()) {
            $login = $form->getData();

            // get db user
            $user = PUserQuery::create()
                ->filterByUsername($login['username'])
                ->findOne();

            // check user exists
            if (!$user) {
                throw new FormValidationException('Utilisateur inconnu');
            }

            // check user/password validity
            $password = $user->getPassword();
            $encoder = $encoderFactory->getEncoder($user);
            $encodedPassword = $encoderFactory->getEncoder($user)->encodePassword($login['password'], $user->getSalt());
            if ($password  != $encodedPassword) {
                // @todo manage a password fail counter
                throw new FormValidationException('Mot de passe incorrect');
            }

            // connect & redirect user
            $redirectUrl = $securityService->connectUser($user);

            $jsonResponse = array (
                'success' => true,
                'redirectUrl' => $redirectUrl
            );
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
     * Lost password
     */
    public function lostPasswordCheck()
    {
        $logger = $this->sc->get('logger');
        $logger->info('*** lostPasswordCheck');
        
        // Retrieve used services
        $request = $this->sc->get('request');
        $formFactory = $this->sc->get('form.factory');
        $encoderFactory = $this->sc->get('security.encoder_factory');
        $eventDispatcher = $this->sc->get('event_dispatcher');

        // Function process
        $form = $formFactory->create(new LostPasswordType());

        $form->bind($request);
        if ($form->isValid()) {
            $lostPassword = $form->getData();

            // get db user
            $user = PUserQuery::create()
                ->filterByEmail($login['email'])
                ->findOne();

            // check user exists
            if (!$user) {
                throw new FormValidationException('Utilisateur inconnu');
            }

            // new random password
            $password = substr(md5(uniqid(mt_rand(), true)), 0, 6);
            $user->setPassword($encoderFactory->getEncoder($user)->encodePassword($password, $user->getSalt()));
            $user->save();

            // Event
            $dispatcher = $eventDispatcher->dispatch('lost_password_email', new GenericEvent($user));

            $jsonResponse = array (
                'success' => true
            );
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
     * Process to payment
     */
    public function paymentProcess()
    {
        $logger = $this->sc->get('logger');
        $logger->info('*** paymentProcess');
        
        // Retrieve used services
        $request = $this->sc->get('request');
        $session = $this->sc->get('session');
        $orderManager = $this->sc->get('politizr.manager.order');
        $router = $this->sc->get('router');

        // Function process
        $user = $securityContext->getToken()->getUser();

        // Request arguments
        $paymentTypeId = $request->get('pOPaymentTypeId');
        $logger->info('$paymentTypeId = ' . print_r($paymentTypeId, true));
        
        // Session arguments
        $subscriptionId = $session->get('p_o_subscription_id');
        $logger->info('$subscriptionId = ' . print_r($subscriptionId, true));
        $supportingDocument = $session->get('p_o_supporting_document');
        $logger->info('$supportingDocument = ' . print_r($supportingDocument, true));
        $electiveMandates = $session->get('p_o_elective_mandates');
        $logger->info('$electiveMandates = ' . print_r($electiveMandates, true));

        // get subscription
        $subscription = POSubscriptionQuery::create()->findPk($subscriptionId);
        if (!$subscription) {
            throw new InconsistentDataException('Subscription not found');
        }

        // create order
        $order = $orderManager->createOrder(
            $user,
            $subscription,
            $paymentTypeId,
            $supportingDocument,
            $electiveMandates
        );

        // put order id in session
        $session->set('p_order_id', $order->getId());

        // payment type rendering
        switch($paymentTypeId) {
            case POPaymentType::BANK_TRANSFER:
                $htmlForm = '';
                $redirectUrl = $router->generate('InscriptionElectedPaymentFinished');
                $redirect = true;

                break;
            case POPaymentType::CREDIT_CARD:
                // construct the atos sips form
                // $sipsAtosManager = $this->sc->get('studio_echo_sips_atos');
                // $htmlForm = $sipsAtosManager->computeAtosRequest($order->getId());

                $htmlForm = '<form id="atos" action="'.$router->generate('InscriptionElectedPaymentFinished').'">Formulaire ATOS<br/><input type="submit" value="Valider"></form>';
                $redirectUrl = '';
                $redirect = false;
                
                break;
            case POPaymentType::CHECK:
                $htmlForm = '';
                $redirectUrl = $router->generate('InscriptionElectedPaymentFinished');
                $redirect = true;

                break;
            case POPaymentType::PAYPAL:
                // construct the paypal form
                // $paypalManager = $this->sc->get('studio_echo_paypal');
                // $htmlForm = $paypalManager->computePaypalRequest($order->getId());

                $htmlForm = '<form id="paypal" action="'.$router->generate('InscriptionElectedPaymentFinished').'">Formulaire ATOS<br/><input type="submit" value="Valider"></form>';
                $redirectUrl = '';
                $redirect = false;

                break;
            default:
                break;
        }

        return array(
            'success' => true,
            'htmlForm' => $htmlForm,
            'redirectUrl' => $redirectUrl,
            'redirect' => $redirect,
            );
    }
}
