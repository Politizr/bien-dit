<?php
namespace Politizr\FrontBundle\Lib\Functional;

use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\AuthenticationEvents;
use Symfony\Component\Security\Core\Event\AuthenticationEvent;

use Symfony\Component\EventDispatcher\GenericEvent;

use Politizr\Exception\InconsistentDataException;

use Politizr\Constant\OrderConstants;
use Politizr\Constant\UserConstants;

use Politizr\Model\PUser;
use Politizr\Model\POPaymentType;

use Politizr\Model\POrderQuery;
use Politizr\Model\PUserQuery;

/**
 * Functional service for security management.
 *
 * @author Lionel Bouzonville
 */
class SecurityService
{
    private $securityTokenStorage;
    private $encoderFactory;
    
    private $session;
    
    private $router;
    
    private $eventDispatcher;
    
    private $usernameCanonicalizer;
    private $emailCanonicalizer;
    
    private $userManager;
    private $orderManager;

    private $logger;

    /**
     *
     * @param @security.token_storage
     * @param @security.encoder_factory
     * @param @session
     * @param @router
     * @param @event_dispatcher
     * @param @fos_user.util.username_canonicalizer
     * @param @fos_user.util.email_canonicalizer
     * @param @politizr.manager.user
     * @param @politizr.manager.order
     * @param @logger
     */
    public function __construct(
        $securityTokenStorage,
        $encoderFactory,
        $session,
        $router,
        $eventDispatcher,
        $usernameCanonicalizer,
        $emailCanonicalizer,
        $userManager,
        $orderManager,
        $logger
    ) {
        $this->securityTokenStorage = $securityTokenStorage;
        $this->encoderFactory = $encoderFactory;

        $this->session = $session;

        $this->router = $router;

        $this->eventDispatcher = $eventDispatcher;

        $this->usernameCanonicalizer = $usernameCanonicalizer;
        $this->emailCanonicalizer = $emailCanonicalizer;

        $this->userManager = $userManager;
        $this->orderManager = $orderManager;

        $this->logger = $logger;
    }

    /* ######################################################################################################## */
    /*                                        PRIVATE FUNCTIONS                                                 */
    /* ######################################################################################################## */

    /**
     * Soft firewall public connection
     *
     * @param PUser $user
     */
    private function doPublicConnection($user)
    {
        $providerKey = 'public';

        $token = new UsernamePasswordToken($user, null, $providerKey, $user->getRoles());
        $this->securityTokenStorage->setToken($token);
        $this->eventDispatcher->dispatch(AuthenticationEvents::AUTHENTICATION_SUCCESS, new AuthenticationEvent($token));
    }

    /**
     * Compute redirection URL depending on user role
     *
     * @param PUser $user
     * @return string
     */
    private function computeRedirectUrl($user)
    {
        $redirectUrl = null;
        if ($user->hasRole('ROLE_PROFILE_COMPLETED')) {
            $user->setLastLogin(new \DateTime());
            $user->save();

            if ($user->getQualified() && $user->getPUStatusId() == UserConstants::STATUS_ACTIVED) {
                $redirectUrl = $this->router->generate('HomepageE');
            } elseif ($user->hasRole('ROLE_CITIZEN')) {
                $redirectUrl = $this->router->generate('HomepageC');
            } else {
                throw new InconsistentDataException('Qualified user is not activ and has no citizen role');
            }
        } elseif ($user->hasRole('ROLE_CITIZEN_INSCRIPTION')) {
            $redirectUrl = $this->router->generate('InscriptionStep2');
        } elseif ($user->hasRole('ROLE_ELECTED_INSCRIPTION')) {
            $redirectUrl = $this->router->generate('InscriptionElectedStep2');
        } else {
            throw new InconsistentDataException('No valid role for user');
        }

        return $redirectUrl;
    }

    /* ######################################################################################################## */
    /*                                              CONNECTION                                                  */
    /* ######################################################################################################## */

    /**
     * Connect & compute the redirect url
     *
     * @param PUser $user
     * @return string Redirect URL
     */
    public function connectUser($user)
    {
        // connect user
        $this->doPublicConnection($user);

        // redirect user
        $redirectUrl = $this->computeRedirectUrl($user);

        return $redirectUrl;
    }

    /**
     * OAuth connection or inscription
     */
    public function oauthRegister()
    {
        $this->logger->info('*** oauthRegister');

        // get user
        $user = $this->securityTokenStorage->getToken()->getUser();

        // get oAuth data
        $oAuthData = $this->session->getFlashBag()->get('oAuthData');
        if (!$oAuthData
            || !is_array($oAuthData)
            || !isset($oAuthData['provider'])
            || !isset($oAuthData['providerId'])
            ) {
            // unexpected oauth data, back to homepage
            return $this->router->generate('Homepage');
        }
        
        // get db user
        $user = PUserQuery::create()
            ->filterByProvider($oAuthData['provider'])
            ->filterByProviderId($oAuthData['providerId'])
            ->findOne();

        if ($user) {
            // update user
            $user = $this->userManager->updateOAuthData($user, $oAuthData);

            // connect and redirect user
            $redirectUrl = $this->connectUser($user);

            return $redirectUrl;
        } else {
            // citizen inscription roles
            $roles = [ 'ROLE_CITIZEN_INSCRIPTION' ];

            // create new user & update it
            $user = new PUser();
            $user = $this->userManager->updateOAuthData($user, $oAuthData);

            if ($user->getEmail()) {
                $username = $user->getEmail();
                $canonicalizer = $this->emailCanonicalizer;
            } elseif ($user->getNickname()) {
                $username = $user->getNickname();
                $canonicalizer = $this->usernameCanonicalizer;
            } else {
                throw new InconsistentDataException('No email or nickname found in OAuth data, cannot create app profile.');
            }

            // update user
            $user = $this->userManager->updateForInscriptionStart(
                $user,
                $roles,
                $canonicalizer->canonicalize($username),
                null
            );

            // connect user
            $this->doPublicConnection($user);

            // redirect to inscription next step
            return $this->router->generate('InscriptionContact');
        }
    }
    
    /* ######################################################################################################## */
    /*                                              INSCRIPTION                                                 */
    /* ######################################################################################################## */

    /**
     * Citizen inscription process start
     *
     * @param  PUser $user
     */
    public function inscriptionCitizenStart(PUser $user)
    {
        $this->logger->info('*** inscriptionCitizenStart');

        // citizen inscription roles
        $roles = [ 'ROLE_CITIZEN_INSCRIPTION' ];

        // update user
        $user = $this->userManager->updateForInscriptionStart(
            $user,
            $roles,
            $this->usernameCanonicalizer->canonicalize($user->getUsername()),
            $this->encoderFactory->getEncoder($user)->encodePassword($user->getPlainPassword(), $user->getSalt())
        );

        // connect user
        $this->doPublicConnection($user);
    }

    /**
     * Citizen inscription process finish
     *
     * @param PUser $user
     */
    public function inscriptionCitizenFinish(PUser $user)
    {
        $this->logger->info('*** inscriptionCitizenFinish');

        // citizen inscription roles
        $roles = [ 'ROLE_CITIZEN', 'ROLE_PROFILE_COMPLETED' ];

        // update user
        $user = $this->userManager->updateForInscriptionFinish($user, $roles, UserConstants::STATUS_ACTIVED, false);
        
        // (re)connect user
        $this->doPublicConnection($user);
    }

    /**
     * Elected inscription process start
     *
     * @param PUser $user
     */
    public function inscriptionElectedStart(PUser $user)
    {
        $this->logger->info('*** inscriptionElectedStart');
        
        // elected inscription roles
        $roles = [ 'ROLE_ELECTED_INSCRIPTION' ];

        // update user
        $user = $this->userManager->updateForInscriptionStart(
            $user,
            $roles,
            $this->usernameCanonicalizer->canonicalize($user->getUsername()),
            $this->encoderFactory->getEncoder($user)->encodePassword($user->getPlainPassword(), $user->getSalt())
        );

        // connect user
        $this->doPublicConnection($user);
    }


    /**
     * Citizen to elected migration process start
     *
     * @param  PUser $user
     */
    public function migrationElectedStart(PUser $user)
    {
        $this->logger->info('*** migrationElectedStart');

        // update role
        $user->addRole('ROLE_ELECTED_INSCRIPTION');
        $user->save();

        // connect user
        $this->doPublicConnection($user);
    }


    /**
     * Order payment completed
     */
    public function updateOrderPaymentCompleted()
    {
        $this->logger->info('*** updateOrderPaymentCompleted');

        // Session arguments
        $orderId = $this->session->get('p_order_id');

        // get order
        $order = POrderQuery::create()->findPk($orderId);
        if (!$order) {
            throw new \InconsistentDataException(sprintf('Order %s not found', $orderId));
        }

        // update order & payment states
        switch($order->getPOPaymentTypeId()) {
            case OrderConstants::PAYMENT_TYPE_BANK_TRANSFER:
            case OrderConstants::PAYMENT_TYPE_CHECK:
                $order->setPOOrderStateId(OrderConstants::ORDER_WAITING);
                $order->setPOPaymentStateId(OrderConstants::PAYMENT_WAITING);
                $order->save();

                $this->eventDispatcher->dispatch('order_email', new GenericEvent($order));

                break;
            case OrderConstants::PAYMENT_TYPE_CREDIT_CARD:
            case OrderConstants::PAYMENT_TYPE_PAYPAL:
                // management via asynchronous apis response
                break;
            default:
                break;
        }
    }

    /**
     * Order canceled
     */
    public function updateOrderPaymentCanceled()
    {
        $this->logger->info('*** updateOrderPaymentCanceled');

        // Session arguments
        $orderId = $this->session->get('p_order_id');

        // get order
        $order = POrderQuery::create()->findPk($orderId);
        if (!$order) {
            throw new \InconsistentDataException(sprintf('Order %s not found', $orderId));
        }

        $this->orderManager->deleteOrder($order);
    }

    /**
     *  Finalisation du process d'inscription débatteur
     *
     */
    public function inscriptionFinishElected(PUser $user)
    {
        $this->logger->info('*** inscriptionFinishElected');

        // citizen inscription roles
        $roles = [ 'ROLE_ELECTED', 'ROLE_CITIZEN' /* during waiting for validation */, 'ROLE_PROFILE_COMPLETED' ];

        // update user
        $user = $this->userManager->updateForInscriptionFinish($user, $roles, UserConstants::STATUS_VALIDATION_PROCESS, true);
        
        $this->doPublicConnection($user);
    }
}
