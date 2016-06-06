<?php
namespace Politizr\FrontBundle\Lib\Xhr;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\EventDispatcher\GenericEvent;

use Politizr\Exception\InconsistentDataException;
use Politizr\Exception\BoxErrorException;
use Politizr\Exception\PolitizrException;

use StudioEcho\Lib\StudioEchoUtils;

use Politizr\Constant\OrderConstants;
use Politizr\Constant\PathConstants;
use Politizr\Constant\IdCheckConstants;

use Politizr\FrontBundle\Lib\SimpleImage;

use Politizr\Model\PUser;

use Politizr\Model\PUserQuery;
use Politizr\Model\POSubscriptionQuery;

use Politizr\FrontBundle\Form\Type\LostPasswordType;
use Politizr\FrontBundle\Form\Type\PUserIdCheckType;

use Politizr\FrontBundle\Lib\Client\Ariad;

/**
 * XHR service for security management.
 *
 * @author Lionel Bouzonville
 */
class XhrSecurity
{
    private $securityTokenStorage;
    private $encoderFactory;
    private $kernel;
    private $session;
    private $eventDispatcher;
    private $templating;
    private $formFactory;
    private $router;
    private $securityService;
    private $orderManager;
    private $globalTools;
    private $idcheck;
    private $logger;

    /**
     *
     * @param @security.token_storage
     * @param @security.encoder_factory
     * @param @kernel
     * @param @session
     * @param @event_dispatcher
     * @param @templating
     * @param @form.factory
     * @param @router
     * @param @politizr.functional.security
     * @param @politizr.manager.order
     * @param @politizr.tools.global
     * @param @politizr.tools.idcheck
     * @param @logger
     */
    public function __construct(
        $securityTokenStorage,
        $encoderFactory,
        $kernel,
        $session,
        $eventDispatcher,
        $templating,
        $formFactory,
        $router,
        $securityService,
        $orderManager,
        $globalTools,
        $idcheck,
        $logger
    ) {
        $this->securityTokenStorage = $securityTokenStorage;
        $this->encoderFactory = $encoderFactory;

        $this->kernel = $kernel;

        $this->session = $session;

        $this->eventDispatcher = $eventDispatcher;

        $this->templating = $templating;
        $this->formFactory = $formFactory;
        $this->router = $router;

        $this->securityService = $securityService;
        $this->orderManager = $orderManager;

        $this->globalTools = $globalTools;
        $this->idcheck = $idcheck;

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
        
        // get current user
        $user = $this->securityTokenStorage->getToken()->getUser();

        // Request arguments
        $paymentTypeId = $request->get('paymentTypeId');
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

    /* ######################################################################################################## */
    /*                                                 ARIAD IDCHECK                                            */
    /* ######################################################################################################## */

    /**
     * ARIAD ID CHECK ZLA
     * beta
     */
    private function isValidIdZla(Request $request, PUser $user, $updNbTry = true)
    {
        $this->logger->info('*** isValidIdZla');

        $form = $this->formFactory->create(new PUserIdCheckType($user), $user);

        $form->bind($request);
        if ($form->isValid()) {
            $zla1 = $form->get('zla1')->getData();
            $zla2 = $form->get('zla2')->getData();

            $checked = $this->idcheck->executeZlaChecking($zla1, $zla2);

            // upd nb try
            if ($updNbTry) {
                $user->setNbIdCheck($user->getNbIdCheck() + 1);
                $user->save();
            }

            if ($checked && $this->idcheck->isUserLastResult($user)) {
                return true;
            } else {
                return false;
            }
        } else {
            $errors = StudioEchoUtils::getAjaxFormErrors($form);
            throw new BoxErrorException($errors);
        }
    }

    /**
     * ARIAD ID CHECK PHOTO
     * beta
     */
    public function isValidIdPhoto(Request $request, PUser $user, $updNbTry = true)
    {
        $this->logger->info('*** isValidIdPhoto');

        $fileName = $request->get('fileName');
        $this->logger->info('$fileName = ' . print_r($fileName, true));

        $path = $this->kernel->getRootDir() . '/../web' . PathConstants::IDCHECK_UPLOAD_WEB_PATH;

        if (file_exists($path . $fileName)) {
            $image = new SimpleImage();
            $image->load($path . $fileName);
            $rawImg = $image->raw();

            $checked = $this->idcheck->executeImageIdCardChecking($rawImg);

            // upd nb try
            if ($updNbTry) {
                $user->setNbIdCheck($user->getNbIdCheck() + 1);
                $user->save();
            }

            if ($checked && $this->idcheck->isUserLastResult($user)) {
                return true;
            } else {
                return false;
            }
        } else {
            throw new PolitizrException('Le fichier image n\'existe pas.');
        }
    }

    /**
     * ARIAD ID CHECK ZLA
     * beta
     */
    public function validateIdZla(Request $request)
    {
        $this->logger->info('*** validateIdZla');

        // get current user
        $user = $this->securityTokenStorage->getToken()->getUser();
        $nbTry = $user->getNbIdCheck();

        // max try reach > redirect to account w. error msg
        if ($nbTry >= IdCheckConstants::MAX_USER_TRY) {
            $errors = null;
            $success = false;
            $redirect = true;
            $redirectUrl = $this->router->generate('EditPerso'.$this->globalTools->computeProfileSuffix());
        } else {
            if ($this->isValidIdZla($request, $user)) {
                $this->session->getFlashBag()->add('idcheck/success', true);

                $user->setValidated(true);
                $user->save();

                $errors = null;
                $success = true;
                $redirect = true;
                $redirectUrl = $this->router->generate('Homepage'.$this->globalTools->computeProfileSuffix());
            } else {
                $errors = StudioEchoUtils::multiImplode($this->idcheck->getErrorMsg(), ' <br/> ');
                $success = false;
                $redirect = false;
                $redirectUrl = false;

                $nbTry = $user->getNbIdCheck();
                if ($nbTry >= IdCheckConstants::MAX_USER_TRY) {
                    $redirect = true;
                    $redirectUrl = $this->router->generate('EditPerso'.$this->globalTools->computeProfileSuffix());
                }
            }
        }

        return array(
            'success' => $success,
            'errors' => $errors,
            'redirectUrl' => $redirectUrl,
            'redirect' => $redirect,
            'nbTryLeft' => IdCheckConstants::MAX_USER_TRY - $nbTry,
        );
    }

    /**
     * ARIAD ID CHECK PHOTO
     * beta
     */
    public function validateIdPhoto(Request $request)
    {
        $this->logger->info('*** validateIdPhoto');

        // get current user
        $user = $this->securityTokenStorage->getToken()->getUser();
        $nbTry = $user->getNbIdCheck();

        // max try reach > redirect to account w. error msg
        if ($nbTry >= IdCheckConstants::MAX_USER_TRY) {
            $errors = null;
            $success = false;
            $redirect = true;
            $redirectUrl = $this->router->generate('EditPerso'.$this->globalTools->computeProfileSuffix());
        } else {
            if ($this->isValidIdPhoto($request, $user)) {
                $this->session->getFlashBag()->add('idcheck/success', true);

                $user->setValidated(true);
                $user->save();

                $errors = null;
                $success = true;
                $redirect = true;
                $redirectUrl = $this->router->generate('Homepage'.$this->globalTools->computeProfileSuffix());
            } else {
                $errors = StudioEchoUtils::multiImplode($this->idcheck->getErrorMsg(), ' <br/> ');
                $success = false;
                $redirect = false;
                $redirectUrl = false;

                $nbTry = $user->getNbIdCheck();
                if ($nbTry >= IdCheckConstants::MAX_USER_TRY) {
                    $redirect = true;
                    $redirectUrl = $this->router->generate('EditPerso'.$this->globalTools->computeProfileSuffix());
                }
            }
        }

        return array(
            'success' => $success,
            'errors' => $errors,
            'redirectUrl' => $redirectUrl,
            'redirect' => $redirect,
            'nbTryLeft' => IdCheckConstants::MAX_USER_TRY - $nbTry,
        );
    }

    /**
     * ARIAD ID CHECK ZLA / ADMIN
     * beta
     */
    public function adminValidateIdZla(Request $request)
    {
        $this->logger->info('*** adminValidateIdZla');

        // Request arguments
        $userId = $request->get('user_id_check')['p_user_id'];
        $this->logger->info('userId = ' . print_r($userId, true));

        $user = PUserQuery::create()->findPk($userId);
        if (!$user) {
            throw new InconsistentDataException(sprintf('User id-%s not found', $userId));
        }

        $errors = null;
        if ($this->isValidIdZla($request, $user, false)) {
            $success = true;
        } else {
            $errors = StudioEchoUtils::multiImplode($this->idcheck->getErrorMsg(), ' <br/> ');
            $success = false;
        }

        return array(
            'success' => $success,
            'errors' => $errors,
        );
    }

    /**
     * ARIAD ID CHECK PHOTO / ADMIN
     * beta
     */
    public function adminValidateIdPhoto(Request $request)
    {
        $this->logger->info('*** adminValidateIdPhoto');

        // get user
        $userId = $request->get('userId');
        $this->logger->info('$userId = '.print_r($userId, true));

        $user = PUserQuery::create()->findPk($userId);
        if (!$user) {
            throw new InconsistentDataException(sprintf('User id-%s not found', $userId));
        }

        $errors = null;
        if ($this->isValidIdPhoto($request, $user, false)) {
            $success = true;
        } else {
            $errors = StudioEchoUtils::multiImplode($this->idcheck->getErrorMsg(), ' <br/> ');
            $success = false;
        }

        return array(
            'success' => $success,
            'errors' => $errors,
        );
    }

    /**
     * ARIAD ID CHECK UPLOAD PHOTO
     * beta
     */
    public function idCheckPhotoUpload(Request $request)
    {
        $this->logger->info('*** idCheckPhotoUpload');

        // get current user
        $user = $this->securityTokenStorage->getToken()->getUser();
        
        // Function process
        $path = $this->kernel->getRootDir() . '/../web' . PathConstants::IDCHECK_UPLOAD_WEB_PATH;

        // XHR upload
        $fileName = $this->globalTools->uploadXhrImage(
            $request,
            'fileName',
            $path,
            5000,
            5000,
            20971520,
            [ 'image/jpeg', 'image/pjpeg', 'image/jpeg', 'image/pjpeg' ]
        );

        return array(
            'fileName' => $fileName
        );
    }
}
