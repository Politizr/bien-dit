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

use Politizr\FrontBundle\Form\Type\POrderSubscriptionType;


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
     *  Déconnexion
     */
    public function logoutAction() {
        $this->get('session')->clear();
        $this->get('security.context')->setToken(null);

        return $this->redirect($this->generateUrl('Homepage'));
    }

    /**
     *  Annulation inscription
     */
    public function cancelInscriptionAction() {

        // Récupération user
        $user = $this->get('security.context')->getToken()->getUser();

        // Cas spécial migration profil à gérer: pas de suppression du profil.
        $migration = $this->get('session')->get('migration');
        if ($migration) {
            return $this->redirect($this->generateUrl('HomepageC'));
        }

        $user->deleteWithoutArchive();
        return $this->redirect($this->generateUrl('Logout'));
    }

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
     *  Validation de la connexion
     */
    public function loginCheckAction(Request $request) {
        $logger = $this->get('logger');
        $logger->info('*** loginCheckAction');

        $jsonResponse = $this->get('politizr.routing.ajax')->createJsonHtmlResponse(
            'politizr.service.security',
            'loginCheck'
        );

        return $jsonResponse;
    }

    /**
     *  Validation mot de passe oublié
     */
    public function lostPasswordCheckAction(Request $request) {
        $logger = $this->get('logger');
        $logger->info('*** lostPasswordCheckAction');

        $jsonResponse = $this->get('politizr.routing.ajax')->createJsonResponse(
            'politizr.service.security',
            'lostPasswordCheck'
        );

        return $jsonResponse;
    }

    /* ######################################################################################################## */
    /*                                               INSCRIPTION CLASSIQUE                                      */
    /* ######################################################################################################## */

    /**
     * Page d'inscription / Etape 1
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
     *  Validation inscription / Etape 1
     */
    public function inscriptionCheckAction(Request $request) {
        $logger = $this->get('logger');
        $logger->info('*** inscriptionCheckAction');

        // Appel du service
        $viewAttr = $this->get('politizr.service.security')->inscriptionCheck();

        // Affichage de la vue ou redirection
        if (array_key_exists('redirectUrl', $viewAttr)) {
            return $this->redirect($viewAttr['redirectUrl']);
        }
        return $this->render('PolitizrFrontBundle:Public:inscription.html.twig', $viewAttr);
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
     *  Validation inscription / Etape 2
     */
    public function inscriptionContactCheckAction(Request $request) {
        $logger = $this->get('logger');
        $logger->info('*** inscriptionContactCheckAction');

        // Appel du service
        $viewAttr = $this->get('politizr.service.security')->inscriptionContactCheck();

        // Affichage de la vue ou redirection
        if (array_key_exists('redirectUrl', $viewAttr)) {
            return $this->redirect($viewAttr['redirectUrl']);
        }
        return $this->render('PolitizrFrontBundle:Public:inscriptionContact.html.twig', $viewAttr);
    }


    /* ######################################################################################################## */
    /*                           INSCRIPTION ELU + MIGRATION CIOYEN VERS ELU                                    */
    /* ######################################################################################################## */

    /**
     *     Page d'inscription débatteur  / Etape 1 / Inscription
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
     *  Page d'inscription débatteur  / Etape 1 / Validation inscription
     */
    public function inscriptionElectedCheckAction(Request $request) {
        $logger = $this->get('logger');
        $logger->info('*** inscriptionElectedCheckAction');

        // Appel du service
        $viewAttr = $this->get('politizr.service.security')->inscriptionElectedCheck();

        // Affichage de la vue ou redirection
        if (array_key_exists('redirectUrl', $viewAttr)) {
            return $this->redirect($viewAttr['redirectUrl']);
        }
        return $this->render('PolitizrFrontBundle:Public:inscriptionElected.html.twig', $viewAttr);
    }


    /**
     *     Page d'inscription débatteur  / Etape 1 / Migration de compte
     */
    public function migrationElectedAction()
    {
        $logger = $this->get('logger');
        $logger->info('*** migrationElectedAction');

        // *********************************** //
        //      Formulaire
        // *********************************** //
        $user = $this->getUser();

        // Mise en session d'une variable spéciale
        $this->get('session')->set('migration', true);

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
     *  Page d'inscription débatteur  / Etape 1 / Validation migration de compte
     */
    public function migrationElectedCheckAction(Request $request) {
        $logger = $this->get('logger');
        $logger->info('*** migrationElectedCheckAction');

        // Appel du service
        $viewAttr = $this->get('politizr.service.security')->migrationElectedCheck();

        // Affichage de la vue ou redirection
        if (array_key_exists('redirectUrl', $viewAttr)) {
            return $this->redirect($viewAttr['redirectUrl']);
        }
        return $this->render('PolitizrFrontBundle:Security:migrationElected.html.twig', $viewAttr);
    }


    /**
     *     Page d'inscription débatteur / Etape 2 / Choix de la formule
     */
    public function inscriptionElectedOrderAction()
    {
        $logger = $this->get('logger');
        $logger->info('*** inscriptionElectedOrderAction');

        $user = $this->getUser();

        // *********************************** //
        //      Formulaire
        // *********************************** //
        $subscriptionForm = $this->createForm(new POrderSubscriptionType());

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
     *  Page d'inscription débatteur / Etape 2 / Validation choix de la formule
     */
    public function inscriptionElectedOrderCheckAction(Request $request) {
        $logger = $this->get('logger');
        $logger->info('*** inscriptionElectedOrderCheckAction');

        // Appel du service
        $viewAttr = $this->get('politizr.service.security')->inscriptionElectedOrderCheck();

        // Affichage de la vue ou redirection
        if (array_key_exists('redirectUrl', $viewAttr)) {
            return $this->redirect($viewAttr['redirectUrl']);
        }
        return $this->render('PolitizrFrontBundle:Security:inscriptionElected.html.twig', $viewAttr);
    }


    /**
     *     Page d'inscription débatteur / Etape 3 / Paiement
     */
    public function inscriptionElectedPaymentAction()
    {
        $logger = $this->get('logger');
        $logger->info('*** inscriptionElectedPaymentAction');

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
     *  Page d'inscription débatteur / Etape 3 / Paiement terminé
     */
    public function inscriptionElectedPaymentFinishedAction(Request $request) {
        $logger = $this->get('logger');
        $logger->info('*** inscriptionElectedPaymentFinishedAction');

        // Appel du service
        $redirectUrl = $this->get('politizr.service.security')->inscriptionElectedPaymentFinished();

        // Affichage de la vue ou redirection
        return $this->redirect($redirectUrl);
    }

    /**
     *  Page d'inscription débatteur / Etape 3 / Annulation paiement
     */
    public function inscriptionElectedPaymentCanceledAction(Request $request) {
        $logger = $this->get('logger');
        $logger->info('*** inscriptionElectedPaymentCanceledAction');

        // Appel du service
        $redirectUrl = $this->get('politizr.service.security')->inscriptionElectedPaymentCanceled();

        // Affichage de la vue ou redirection
        return $this->redirect($redirectUrl);
    }


    /**
     *  Page d'inscription débatteur / Etape 4 / Remerciement
     */
    public function inscriptionElectedThankingAction(Request $request) {
        $logger = $this->get('logger');
        $logger->info('*** inscriptionElectedThankingAction');

        // Appel du service
        $viewAttr = $this->get('politizr.service.security')->inscriptionElectedThanking();

        // Affichage de la vue ou redirection
        if (array_key_exists('redirectUrl', $viewAttr)) {
            return $this->redirect($viewAttr['redirectUrl']);
        }
        return $this->render('PolitizrFrontBundle:Security:inscriptionElectedThanking.html.twig', $viewAttr);
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
     *  Check l'état d'un utilisateur suite à une connexion oAuth
     */
    public function oauthRegisterAction(Request $request) {
        $logger = $this->get('logger');
        $logger->info('*** oauthRegisterAction');

        // Appel du service
        $redirectUrl = $this->get('politizr.service.security')->oauthRegister();

        // Redirection
        return $this->redirect($redirectUrl);
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

        $jsonResponse = $this->get('politizr.routing.ajax')->createJsonHtmlResponse(
            'politizr.service.security',
            'paymentProcess'
        );

        return $jsonResponse;
    }    
}