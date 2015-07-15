<?php
namespace Politizr\FrontBundle\Lib\Functional;

use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\AuthenticationEvents;
use Symfony\Component\Security\Core\Event\AuthenticationEvent;

use Symfony\Component\EventDispatcher\GenericEvent;

use Politizr\Exception\InconsistentDataException;

use Politizr\Model\PUser;
use Politizr\Model\PUStatus;
use Politizr\Model\POPaymentType;
use Politizr\Model\POOrderState;
use Politizr\Model\POPaymentState;

use Politizr\Model\POrderQuery;
use Politizr\Model\PUserQuery;

/**
 * Functional service for security management.
 *
 * @author Lionel Bouzonville
 */
class SecurityService
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
    /*                                              CONNECTION                                                  */
    /* ######################################################################################################## */

    /**
     * Soft firewall public connection
     *
     * @param PUser $user
     */
    private function doPublicConnection($user)
    {
        // Retrieve used services
        $securityContext = $this->sc->get('security.context');
        $eventDispatcher = $this->sc->get('event_dispatcher');

        $providerKey = 'public';

        $token = new UsernamePasswordToken($user, null, $providerKey, $user->getRoles());
        $securityContext->setToken($token);
        $eventDispatcher->dispatch(AuthenticationEvents::AUTHENTICATION_SUCCESS, new AuthenticationEvent($token));
    }

    /**
     * Compute redirection URL depending on user role
     *
     * @param PUser $user
     * @return string
     */
    private function computeRedirectUrl($user)
    {
        // Retrieve used services
        $router = $this->sc->get('router');

        $redirectUrl = null;
        if ($user->hasRole('ROLE_PROFILE_COMPLETED')) {
            $user->setLastLogin(new \DateTime());
            $user->save();

            if ($user->getQualified() && $user->getPUStatusId() == PUStatus::ACTIVED) {
                $redirectUrl = $router->generate('HomepageE');
            } elseif ($user->hasRole('ROLE_CITIZEN')) {
                $redirectUrl = $router->generate('HomepageC');
            } else {
                throw new InconsistentDataException('Qualified user is not activ and has no citizen role');
            }
        } elseif ($user->hasRole('ROLE_CITIZEN_INSCRIPTION')) {
            $redirectUrl = $router->generate('InscriptionStep2');
        } elseif ($user->hasRole('ROLE_ELECTED_INSCRIPTION')) {
            $redirectUrl = $router->generate('InscriptionElectedStep2');
        } else {
            throw new InconsistentDataException('No valid role for user');
        }

        return $redirectUrl;
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
        $logger = $this->sc->get('logger');
        $logger->info('*** inscriptionCitizenStart');

        // Retrieve used services
        $usernameCanonicalizer = $this->sc->get('fos_user.util.username_canonicalizer');
        $encoderFactory = $this->sc->get('security.encoder_factory');
        $userManager = $this->sc->get('politizr.manager.user');

        // citizen inscription roles
        $roles = [ 'ROLE_CITIZEN_INSCRIPTION' ];

        // update user
        $user = $userManager->updateForInscriptionStart(
            $user,
            $roles,
            $usernameCanonicalizer->canonicalize($user->getUsername()),
            $encoder->encodePassword($user->getPlainPassword(), $user->getSalt())
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
        $logger = $this->sc->get('logger');
        $logger->info('*** inscriptionCitizenFinish');

        // Retrieve used services
        $userManager = $this->sc->get('politizr.manager.user');

        // citizen inscription roles
        $roles = [ 'ROLE_CITIZEN', 'ROLE_PROFILE_COMPLETED' ];

        // update user
        $user = $userManager->updateForInscriptionFinish($user, $roles, PUStatus::ACTIVED, false);
        
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
        $logger = $this->sc->get('logger');
        $logger->info('*** inscriptionElectedStart');
        
        // Retrieve used services
        $usernameCanonicalizer = $this->sc->get('fos_user.util.username_canonicalizer');
        $encoderFactory = $this->sc->get('security.encoder_factory');
        $userManager = $this->sc->get('politizr.manager.user');

        // elected inscription roles
        $roles = [ 'ROLE_ELECTED_INSCRIPTION' ];

        // update user
        $user = $userManager->updateForInscriptionStart(
            $user,
            $roles,
            $usernameCanonicalizer->canonicalize($user->getUsername()),
            $encoder->encodePassword($user->getPlainPassword(), $user->getSalt())
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
        $logger = $this->sc->get('logger');
        $logger->info('*** migrationElectedStart');

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
        $logger = $this->sc->get('logger');
        $logger->info('*** updateOrderPaymentCompleted');

        // Retrieve used services
        $session = $this->sc->get('session');
        $eventDispatcher = $this->sc->get('event_dispatcher');

        // Session arguments
        $orderId = $session->get('p_order_id');

        // get order
        $order = POrderQuery::create()->findPk($orderId);
        if (!$order) {
            throw new \InconsistentDataException(sprintf('Order %s not found', $orderId));
        }

        // update order & payment states
        switch($order->getPOPaymentTypeId()) {
            case POPaymentType::BANK_TRANSFER:
            case POPaymentType::CHECK:
                $order->setPOOrderStateId(POOrderState::WAITING);
                $order->setPOPaymentStateId(POPaymentState::WAITING);
                $order->save();

                $eventDispatcher->dispatch('order_email', new GenericEvent($order));

                break;
            case POPaymentType::CREDIT_CARD:
            case POPaymentType::PAYPAL:
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
        $logger = $this->sc->get('logger');
        $logger->info('*** updateOrderPaymentCanceled');

        // Retrieve used services
        $session = $this->sc->get('session');
        $orderManager = $this->sc->get('politizr.manager.order');

        // Session arguments
        $orderId = $session->get('p_order_id');

        // get order
        $order = POrderQuery::create()->findPk($orderId);
        if (!$order) {
            throw new \InconsistentDataException(sprintf('Order %s not found', $orderId));
        }

        $orderManager->deleteOrder($order);
    }

    /**
     *  Finalisation du process d'inscription débatteur
     *
     */
    public function inscriptionFinishElected(PUser $user)
    {
        $logger = $this->sc->get('logger');
        $logger->info('*** inscriptionFinishElected');

        // Retrieve used services
        $userManager = $this->sc->get('politizr.manager.user');

        // citizen inscription roles
        $roles = [ 'ROLE_ELECTED', 'ROLE_CITIZEN' /* during waiting for validation */, 'ROLE_PROFILE_COMPLETED' ];

        // update user
        $user = $userManager->updateForInscriptionFinish($user, $roles, PUStatus::VALIDATION_PROCESS, true);
        
        $this->doPublicConnection($user);
    }


    /* ######################################################################################################## */
    /*                                          OAUTH CONNECTION                                                */
    /* ######################################################################################################## */

    /**
     * Check oAuth ok & do connection
     */
    public function oauthRegister()
    {
        $logger = $this->sc->get('logger');
        $logger->info('*** oauthRegister');

        // Retrieve used services
        $session = $this->sc->get('session');
        $securityContext = $this->sc->get('security.context');
        $router = $this->sc->get('router');
        $userManager = $this->sc->get('politizr.manager.user');
        $usernameCanonicalizer = $this->sc->get('fos_user.util.username_canonicalizer');
        $emailCanonicalizer = $this->sc->get('fos_user.util.email_canonicalizer');
        $encoderFactory = $this->sc->get('security.encoder_factory');

        // get user
        $user = $securityContext->getToken()->getUser();

        // get oAuth data
        $oAuthData = $session->getFlashBag()->get('oAuthData');
        if (!$oAuthData
            || !is_array($oAuthData)
            || !isset($oAuthData['provider'])
            || !isset($oAuthData['providerId'])
            ) {
            // unexpected oauth data, back to homepage
            return $router->generate('Homepage');
        }
        
        // get db user
        $user = PUserQuery::create()
            ->filterByProvider($oAuthData['provider'])
            ->filterByProviderId($oAuthData['providerId'])
            ->findOne();

        if ($user) {
            // update user
            $user = $userManager->updateOAuthData($user, $oAuthData);

            // connect user
            $this->doPublicConnection($user);

            // redirect
            $redirectUrl = $this->computeRedirectUrl($user);

            return $redirectUrl;
        } else {
            // citizen inscription roles
            $roles = [ 'ROLE_CITIZEN_INSCRIPTION' ];

            // create new user & update it
            $user = new PUser();
            $user = $userManager->updateOAuthData($user, $oAuthData);

            if ($user->getEmail()) {
                $username = $user->getEmail();
                $canonicalizer = $emailCanonicalizer;
            } elseif ($user->getNickname()) {
                $username = $user->getNickname();
                $canonicalizer = $usernameCanonicalizer;
            } else {
                throw new InconsistentDataException('No email or nickname found in OAuth data, cannot create app profile.');
            }

            // update user
            $user = $userManager->updateForInscriptionStart(
                $user,
                $roles,
                $canonicalizer->canonicalize($username),
                null
            );

            // connect user
            $this->doPublicConnection($user);

            // redirect to inscription next step
            return $router->generate('InscriptionContact');
        }
    }
}
