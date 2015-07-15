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
    public function doPublicConnection($user)
    {
        $providerKey = 'public';

        $token = new UsernamePasswordToken($user, null, $providerKey, $user->getRoles());
        $this->sc->get('security.context')->setToken($token);
        $this->sc->get('event_dispatcher')->dispatch(AuthenticationEvents::AUTHENTICATION_SUCCESS, new AuthenticationEvent($token));
    }

    /**
     *  Renvoie l'URL de redirection en fonction de l'état / des rôles du user courant.
     *
     * @param $user    PUser object
     * @return string   Redirect URL
     */
    public function computeRedirectUrl($user)
    {
        $redirectUrl = null;
        if ($user->hasRole('ROLE_PROFILE_COMPLETED')) {
            $user->setLastLogin(new \DateTime());
            $user->save();

            if ($user->getQualified() && $user->getPUStatusId() == PUStatus::ACTIVED) {
                $redirectUrl = $this->sc->get('router')->generate('HomepageE');
            } elseif ($user->hasRole('ROLE_CITIZEN')) {
                $redirectUrl = $this->sc->get('router')->generate('HomepageC');
            }
        } elseif ($user->hasRole('ROLE_CITIZEN_INSCRIPTION')) {
            $redirectUrl = $this->sc->get('router')->generate('InscriptionStep2');
        } elseif ($user->hasRole('ROLE_ELECTED_INSCRIPTION')) {
            $redirectUrl = $this->sc->get('router')->generate('InscriptionElectedStep2');
        }

        if ($redirectUrl) {
            return $redirectUrl;
        } else {
            throw new InconsistentDataException('Aucun rôle / status / état n\'est cohérent pour l\'utilisateur');
        }
    }
    
    /* ######################################################################################################## */
    /*                                              INSCRIPTION                                                 */
    /* ######################################################################################################## */

    /**
     *  Démarrage du process d'inscription
     *
     *  @param  PUser $user
     */
    public function inscriptionStart(PUser $user)
    {
        $logger = $this->sc->get('logger');
        $logger->info('*** inscriptionStart');
        
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
    }

    /**
     *  Finalisation du process d'inscription
     *
     *  @param PUser $user
     */
    public function inscriptionFinish(PUser $user)
    {
        $logger = $this->sc->get('logger');
        $logger->info('*** inscriptionFinish');
        
        // MAJ objet
        $user->setOnline(true);
        $user->setPUStatusId(PUStatus::ACTIVED);
        $user->setQualified(false);
        $user->setLastLogin(new \DateTime());

        // MAJ droits
        $user->addRole('ROLE_CITIZEN');
        $user->addRole('ROLE_PROFILE_COMPLETED');
        $user->removeRole('ROLE_CITIZEN_INSCRIPTION');

        // Save user
        $user->save();

        // (re)Connexion (/ maj droits)
        $this->doPublicConnection($user);
    }


    /**
     *  Démarrage du process d'inscription débatteur
     *
     *  @param  PUser $user
     */
    public function inscriptionElectedStart(PUser $user)
    {
        $logger = $this->sc->get('logger');
        $logger->info('*** inscriptionElectedStart');
        
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

        // Connexion
        $this->doPublicConnection($user);
    }


    /**
     *  Démarrage du process de migration vers un compte débatteur
     *
     *  @param  PUser $user
     */
    public function migrationElectedStart(PUser $user)
    {
        $logger = $this->sc->get('logger');
        $logger->info('*** migrationElectedStart');

        // MAJ droits
        $user->addRole('ROLE_ELECTED_INSCRIPTION');
        $user->save();

        // Connexion
        $this->doPublicConnection($user);
    }


    /**
     *  Page d'inscription débatteur / Etape 3 / Paiement terminé
     *
     */
    public function updateOrderPaymentFinished()
    {
        $logger = $this->sc->get('logger');
        $logger->info('*** updateOrderPaymentFinished');
        
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
    }


    /**
     *  Page d'inscription débatteur / Etape 3 / Annulation paiement
     *
     */
    public function updateOrderPaymentCanceled()
    {
        $logger = $this->sc->get('logger');
        $logger->info('*** updateOrderPaymentCanceled');
        
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
    }

    /**
     *  Finalisation du process d'inscription débatteur
     *
     */
    public function inscriptionFinishElected(PUser $user)
    {
        $logger = $this->sc->get('logger');
        $logger->info('*** inscriptionFinishElected');
        
        // Suppression rôle user / déconnexion
        $user->setOnline(true);
        $user->setPUStatusId(PUStatus::VALIDATION_PROCESS);

        $user->setQualified(true);
        $user->setLastLogin(new \DateTime());

        $user->addRole('ROLE_ELECTED');
        $user->addRole('ROLE_PROFILE_COMPLETED');
        $user->removeRole('ROLE_ELECTED_INSCRIPTION');

        $user->save();

        // Droits citoyen en attendant la validation
        if (!$user->hasRole('ROLE_CITIZEN')) {
            $user->addRole('ROLE_CITIZEN');

            // Save user
            $user->save();

            // (re)Connexion (/ maj droits)
            $this->doPublicConnection($user);
        }
    }


    /* ######################################################################################################## */
    /*                         SERVICES METIERS LIES A LA CONNEXION OAUTH                                       */
    /* ######################################################################################################## */


    /**
     *  Check l'état d'un utilisateur suite à une connexion oAuth
     *
     */
    public function oauthRegister()
    {
        $logger = $this->sc->get('logger');
        $logger->info('*** oauthRegister');
        
        // Récupération user
        $user = $this->sc->get('security.context')->getToken()->getUser();

        // Récupération données oAuth
        $oAuthData = $this->sc->get('session')->getFlashBag()->get('oAuthData');
        if (!$oAuthData || !is_array($oAuthData) || !isset($oAuthData['provider']) || !isset($oAuthData['providerId'])) {
            return $this->sc->get('router')->generate('Homepage');
        }
        
        // Récupération du user existant en base
        $user = PUserQuery::create()->filterByProvider($oAuthData['provider'])->filterByProviderId($oAuthData['providerId'])->findOne();
        if ($user) {
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
            $user->setOnline(true);
            $user->setPUStatusId(PUStatus::ACTIVED);
            $user->setQualified(false);
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
            } elseif ($nickname = $user->getNickname()) {
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
