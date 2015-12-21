<?php
namespace Politizr\FrontBundle\Lib\Xhr;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\EventDispatcher\GenericEvent;

use Politizr\Exception\InconsistentDataException;
use Politizr\Exception\BoxErrorException;

use StudioEcho\Lib\StudioEchoUtils;

use Politizr\Constant\OrderConstants;

use Politizr\Model\PUserQuery;
use Politizr\Model\POSubscriptionQuery;

use Politizr\FrontBundle\Form\Type\LostPasswordType;

/**
 * XHR service for security management.
 *
 * @author Lionel Bouzonville
 */
class XhrSecurity
{
    private $encoderFactory;
    private $session;
    private $eventDispatcher;
    private $templating;
    private $formFactory;
    private $router;
    private $securityService;
    private $orderManager;
    private $logger;

    /**
     *
     * @param @security.encoder_factory
     * @param @session
     * @param @event_dispatcher
     * @param @templating
     * @param @form.factory
     * @param @router
     * @param @politizr.functional.security
     * @param @politizr.manager.order
     * @param @logger
     */
    public function __construct(
        $encoderFactory,
        $session,
        $eventDispatcher,
        $templating,
        $formFactory,
        $router,
        $securityService,
        $orderManager,
        $logger
    ) {
        $this->encoderFactory = $encoderFactory;

        $this->session = $session;

        $this->eventDispatcher = $eventDispatcher;

        $this->templating = $templating;
        $this->formFactory = $formFactory;
        $this->router = $router;

        $this->securityService = $securityService;
        $this->orderManager = $orderManager;

        $this->logger = $logger;
    }

    /* ######################################################################################################## */
    /*                                                  CONNECTION                                              */
    /* ######################################################################################################## */

    /**
     * Lost password
     */
    public function lostPasswordCheck(Request $request)
    {
        $this->logger->info('*** lostPasswordCheck');
        
        // Function process
        $form = $this->formFactory->create(new LostPasswordType());

        $form->bind($request);
        if ($form->isValid()) {
            $lostPassword = $form->getData();

            // get db user
            $user = PUserQuery::create()
                ->filterByEmail($lostPassword['email'])
                ->findOne();

            // check user exists
            if (!$user) {
                throw new BoxErrorException('Utilisateur inconnu');
            }

            // new random password
            $password = substr(md5(uniqid(mt_rand(), true)), 0, 6);
            $this->logger->info('password = '.$password);
            $user->setPlainPassword($password);
            $user->setPassword($this->encoderFactory->getEncoder($user)->encodePassword($password, $user->getSalt()));
            $user->save();

            // Event
            $dispatcher = $this->eventDispatcher->dispatch('lost_password_email', new GenericEvent($user));
        } else {
            $errors = StudioEchoUtils::getAjaxFormErrors($form);
            throw new BoxErrorException($errors);
        }

        return true;
    }


    /* ######################################################################################################## */
    /*                                                  INSCRIPTION                                             */
    /* ######################################################################################################## */

    /**
     * Process to payment
     */
    public function paymentProcess(Request $request)
    {
        $this->logger->info('*** paymentProcess');
        
        // Function process
        $user = $securityTokenStorage->getToken()->getUser();

        // Request arguments
        $paymentTypeId = $request->get('pOPaymentTypeId');
        $this->logger->info('$paymentTypeId = ' . print_r($paymentTypeId, true));
        
        // Session arguments
        $subscriptionId = $this->session->get('p_o_subscription_id');
        $this->logger->info('$subscriptionId = ' . print_r($subscriptionId, true));
        $supportingDocument = $this->session->get('p_o_supporting_document');
        $this->logger->info('$supportingDocument = ' . print_r($supportingDocument, true));
        $electiveMandates = $this->session->get('p_o_elective_mandates');
        $this->logger->info('$electiveMandates = ' . print_r($electiveMandates, true));

        // get subscription
        $subscription = POSubscriptionQuery::create()->findPk($subscriptionId);
        if (!$subscription) {
            throw new InconsistentDataException('Subscription not found');
        }

        // create order
        $order = $this->orderManager->createOrder(
            $user,
            $subscription,
            $paymentTypeId,
            $supportingDocument,
            $electiveMandates
        );

        // put order id in session
        $this->session->set('p_order_id', $order->getId());

        // payment type rendering
        switch ($paymentTypeId) {
            case OrderConstants::PAYMENT_TYPE_BANK_TRANSFER:
                $htmlForm = '';
                $redirectUrl = $this->router->generate('InscriptionElectedPaymentFinished');
                $redirect = true;

                break;
            case OrderConstants::PAYMENT_TYPE_CREDIT_CARD:
                // construct the atos sips form
                // $sipsAtosManager = $this->sc->get('studio_echo_sips_atos');
                // $htmlForm = $sipsAtosManager->computeAtosRequest($order->getId());

                $htmlForm = '<form id="atos" action="'.$this->router->generate('InscriptionElectedPaymentFinished').'">Formulaire ATOS<br/><input type="submit" value="Valider"></form>';
                $redirectUrl = '';
                $redirect = false;
                
                break;
            case OrderConstants::PAYMENT_TYPE_CHECK:
                $htmlForm = '';
                $redirectUrl = $this->router->generate('InscriptionElectedPaymentFinished');
                $redirect = true;

                break;
            case OrderConstants::PAYMENT_TYPE_PAYPAL:
                // construct the paypal form
                // $paypalManager = $this->sc->get('studio_echo_paypal');
                // $htmlForm = $paypalManager->computePaypalRequest($order->getId());

                $htmlForm = '<form id="paypal" action="'.$this->router->generate('InscriptionElectedPaymentFinished').'">Formulaire ATOS<br/><input type="submit" value="Valider"></form>';
                $redirectUrl = '';
                $redirect = false;

                break;
            default:
                throw new InconsistentDataException(sprintf('Payment type id %s not managed', $paymentTypeId));
        }

        return array(
            'success' => true,
            'htmlForm' => $htmlForm,
            'redirectUrl' => $redirectUrl,
            'redirect' => $redirect,
            );
    }
}
