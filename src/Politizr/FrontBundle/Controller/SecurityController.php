<?php

namespace Politizr\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;

use Politizr\Model\PUser;
use Politizr\Model\POrderQuery;
use Politizr\Model\POPaymentTypeQuery;

use Politizr\FrontBundle\Form\Type\PUserRegisterType;
use Politizr\FrontBundle\Form\Type\PUserContactType;

use Politizr\FrontBundle\Form\Type\PUserElectedRegisterType;
use Politizr\FrontBundle\Form\Type\PUserElectedMigrationType;

use Politizr\FrontBundle\Form\Type\LostPasswordType;

use Politizr\FrontBundle\Form\Type\POrderSubscriptionType;

/**
 * Security controller
 *
 * http://nyrodev.info/fr/posts/286/Connexions-OAuth-Multiple-avec-Symfony-2-3
 * http://sirprize.me/scribble/under-the-hood-of-symfony-security/
 * http://www.reecefowell.com/2011/10/26/redirecting-on-loginlogout-in-symfony2-using-loginhandlers/
 *
 * @author Lionel Bouzonville
 */
class SecurityController extends Controller
{

    /* ######################################################################################################## */
    /*                                                 CONNEXION                                                */
    /* ######################################################################################################## */

    /**
     * Login
     * http://symfony.com/doc/current/cookbook/security/form_login_setup.html
     */
    public function loginAction()
    {
        $logger = $this->get('logger');
        $logger->info('*** loginAction');

        $authenticationUtils = $this->get('security.authentication_utils');

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        // lost password form
        $formLostPassword = $this->createForm(new LostPasswordType());

        return $this->render(
            'PolitizrFrontBundle:Security:login.html.twig',
            array(
                // last username entered by the user
                'last_username' => $lastUsername,
                'error'         => $error,
                'formLostPassword' => $formLostPassword->createView(),
            )
        );
    }

    /**
     * Cancel inscription
     */
    public function cancelInscriptionAction()
    {
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

        // Objet & formulaire
        $user = new PUser();
        $form = $this->createForm(new PUserRegisterType(), $user);
        
        return $this->render('PolitizrFrontBundle:Public:inscription.html.twig', array(
                    'form' => $form->createView()
                    ));
    }

    /**
     *  Validation inscription / Etape 1
     */
    public function inscriptionCheckAction(Request $request)
    {
        $logger = $this->get('logger');
        $logger->info('*** inscriptionCheckAction');

        $user = new PUser();
        $form = $this->createForm(new PUserRegisterType(), $user);

        $form->bind($request);
        if ($form->isValid()) {
            $user = $form->getData();

            // load user inscription start process
            $this->get('politizr.functional.security')->inscriptionCitizenStart($user);

            return $this->redirect($this->generateUrl('InscriptionContact'));
        }

        return $this->render('PolitizrFrontBundle:Public:inscription.html.twig', array(
                'form' => $form->createView(),
                ));
    }

    /**
     * Page d'inscription / Etape 2 / Contact
     */
    public function inscriptionContactAction()
    {
        $logger = $this->get('logger');
        $logger->info('*** inscriptionContactAction');

        $user = $this->getUser();
        $form = $this->createForm(new PUserContactType(), $user);
        
        return $this->render('PolitizrFrontBundle:Security:inscriptionContact.html.twig', array(
                    'form' => $form->createView()
                    ));
    }

    /**
     *  Validation inscription / Etape 2
     */
    public function inscriptionContactCheckAction(Request $request)
    {
        $logger = $this->get('logger');
        $logger->info('*** inscriptionContactCheckAction');

        $user = $this->getUser();
        $form = $this->createForm(new PUserContactType(), $user);
        
        $form->bind($request);
        if ($form->isValid()) {
            $user = $form->getData();

            // Canonicalization email
            $canonicalizeEmail = $this->get('fos_user.util.email_canonicalizer');
            $user->setEmailCanonical($canonicalizeEmail->canonicalize($user->getEmail()));
            $user->save();

            // Service associé à la finalisation de l'inscription
            $this->get('politizr.functional.security')->inscriptionCitizenFinish($user);

            return $this->redirect($this->generateUrl('HomepageC'));
        }

        return $this->render('PolitizrFrontBundle:Security:inscriptionContact.html.twig', array(
                    'form' => $form->createView()
                    ));
    }


    /* ######################################################################################################## */
    /*                           INSCRIPTION ELU + MIGRATION CIOYEN VERS ELU                                    */
    /* ######################################################################################################## */

    /**
     * Page d'inscription élu  / Etape 1 / Inscription
     */
    public function inscriptionElectedAction()
    {
        $logger = $this->get('logger');
        $logger->info('*** inscriptionElectedAction');

        $user = new PUser();
        $form = $this->createForm(new PUserElectedRegisterType(), $user);
        
        return $this->render('PolitizrFrontBundle:Public:inscriptionElected.html.twig', array(
                    'form' => $form->createView()
                    ));
    }

    /**
     * Page d'inscription élu  / Etape 1 / Validation inscription
     */
    public function inscriptionElectedCheckAction(Request $request)
    {
        $logger = $this->get('logger');
        $logger->info('*** inscriptionElectedCheckAction');

        $user = new PUser();
        $form = $this->createForm(new PUserElectedRegisterType(), $user);

        $form->bind($request);
        if ($form->isValid()) {
            $user = $form->getData();

            // Service associé au démarrage de l'inscription élu
            $this->get('politizr.functional.security')->inscriptionElectedStart($user);

            // gestion upload pièce ID
            $file = $form['uploaded_supporting_document']->getData();
            if ($file) {
                $supportingDocument = $file->move($this->get('kernel')->getRootDir() . '/../web/uploads/supporting/', $file->getClientOriginalName());
                $this->get('session')->set('p_o_supporting_document', $supportingDocument->getBasename());
            }

            // gestion mandats électifs
            $electiveMandates = $form['elective_mandates']->getData();
            $this->get('session')->set('p_o_elective_mandates', $electiveMandates);

            return $this->redirect($this->generateUrl('InscriptionElectedOrder'));
        }
        
        return $this->render('PolitizrFrontBundle:Public:inscriptionElected.html.twig', array(
                    'form' => $form->createView()
                    ));
    }


    /**
     * Page d'inscription élu  / Etape 1 / Migration de compte
     */
    public function migrationElectedAction()
    {
        $logger = $this->get('logger');
        $logger->info('*** migrationElectedAction');

        // Mise en session d'une variable spéciale
        $this->get('session')->set('migration', true);

        // profil déjà validé => étape 2 directement
        $user = $this->getUser();
        if ($user->getValidated()) {
            $this->get('politizr.functional.security')->migrationElectedStart($user);

            return $this->redirect($this->generateUrl('InscriptionElectedOrder'));
        }

        // Inscription depuis un compte citoyen
        $form = $this->createForm(new PUserElectedMigrationType(), $user);
        
        return $this->render('PolitizrFrontBundle:Security:migrationElected.html.twig', array(
                    'form' => $form->createView()
                    ));
    }


    /**
     * Page d'inscription élu  / Etape 1 / Validation migration de compte
     */
    public function migrationElectedCheckAction(Request $request)
    {
        $logger = $this->get('logger');
        $logger->info('*** migrationElectedCheckAction');

        $user = $this->getUser();
        $form = $this->createForm(new PUserElectedMigrationType(), $user);

        $form->bind($request);
        if ($form->isValid()) {
            $user = $form->getData();

            // Service associé au démarrage de la migration vers un compte élu
            $this->get('politizr.functional.security')->migrationElectedStart($user);

            // gestion upload pièce ID
            $file = $form['uploaded_supporting_document']->getData();
            if ($file) {
                $supportingDocument = $file->move($this->get('kernel')->getRootDir() . '/../web/uploads/supporting/', $file->getClientOriginalName());
                $this->get('session')->set('p_o_supporting_document', $supportingDocument->getBasename());
            }

            // gestion mandats électifs
            $electiveMandates = $form['elective_mandates']->getData();
            $this->get('session')->set('p_o_elective_mandates', $electiveMandates);

            return $this->redirect($this->generateUrl('InscriptionElectedOrder'));
        }

        return $this->render('PolitizrFrontBundle:Security:migrationElected.html.twig', array(
                    'form' => $form->createView()
                    ));
    }


    /**
     * Page d'inscription élu / Etape 2 / Choix de la formule
     */
    public function inscriptionElectedOrderAction(Request $request)
    {
        $logger = $this->get('logger');
        $logger->info('*** inscriptionElectedOrderAction');

        $user = $this->getUser();
        $form = $this->createForm(new POrderSubscriptionType());

        // Cas migration formule > MAJ du layout
        $layout = 'PolitizrFrontBundle::layoutPublic.html.twig';
        if ($user->hasRole('ROLE_CITIZEN')) {
            $layout = 'PolitizrFrontBundle::layoutConnected.html.twig';
        }

        return $this->render('PolitizrFrontBundle:Security:inscriptionElectedOrder.html.twig', array(
                    'form' => $form->createView(),
                    'layout' => $layout,
            ));
    }


    /**
     * Page d'inscription élu / Etape 2 / Validation choix de la formule
     */
    public function inscriptionElectedOrderCheckAction(Request $request)
    {
        $logger = $this->get('logger');
        $logger->info('*** inscriptionElectedOrderCheckAction');

        $user = $this->getUser();
        $form = $this->createForm(new POrderSubscriptionType());

        $form->bind($request);
        if ($form->isValid()) {
            $datas = $form->getData();
            $subscription = $datas['p_o_subscription'];

            // Mise en session de la formule choisie
            $this->get('session')->set('p_o_subscription_id', $subscription->getId());

            return $this->redirect($this->generateUrl('InscriptionElectedPayment'));
        }

        // Cas migration formule > MAJ du layout
        $layout = 'PolitizrFrontBundle::layoutPublic.html.twig';
        if ($user->hasRole('ROLE_CITIZEN')) {
            $layout = 'PolitizrFrontBundle::layoutConnected.html.twig';
        }

        return $this->render('PolitizrFrontBundle:Security:inscriptionElectedOrder.html.twig', array(
                    'form' => $form->createView(),
                    'layout' => $layout,
            ));
    }


    /**
     * Page d'inscription élu / Etape 3 / Paiement
     */
    public function inscriptionElectedPaymentAction()
    {
        $logger = $this->get('logger');
        $logger->info('*** inscriptionElectedPaymentAction');

        $user = $this->getUser();

        // Listes des moyens de paiement / gestion hors form pour chargement dynamique des formulaires paypal/banque & pavés d'informations spécifiques
        $payments = POPaymentTypeQuery::create()->filterByOnline(true)->orderByRank()->find();
        
        // Cas migration formule > MAJ du layout
        $layout = 'PolitizrFrontBundle::layoutPublic.html.twig';
        if ($user->hasRole('ROLE_CITIZEN')) {
            $layout = 'PolitizrFrontBundle::layoutConnected.html.twig';
        }

        return $this->render('PolitizrFrontBundle:Security:inscriptionElectedPayment.html.twig', array(
                    'payments' => $payments,
                    'layout' => $layout
                ));
    }

    /**
     * Page d'inscription élu / Etape 3 / Paiement terminé
     */
    public function inscriptionElectedPaymentFinishedAction(Request $request)
    {
        $logger = $this->get('logger');
        $logger->info('*** inscriptionElectedPaymentFinishedAction');

        // Mise à jour de la commande
        $this->get('politizr.functional.security')->updateOrderPaymentCompleted();

        return $this->redirect($this->generateUrl('InscriptionElectedThanking'));
    }

    /**
     * Page d'inscription élu / Etape 3 / Annulation paiement
     */
    public function inscriptionElectedPaymentCanceledAction(Request $request)
    {
        $logger = $this->get('logger');
        $logger->info('*** inscriptionElectedPaymentCanceledAction');

        // Mise à jour de la commande
        $this->get('politizr.functional.security')->updateOrderPaymentCanceled();

        // Suppression des valeurs en session
        $this->get('session')->remove('p_order_id');

        // Affichage de la vue ou redirection
        return $this->redirect($this->generateUrl('InscriptionElectedPayment'));
    }

    /**
     * Page d'inscription élu / Etape 4 / Remerciement
     */
    public function inscriptionElectedThankingAction(Request $request)
    {
        $logger = $this->get('logger');
        $logger->info('*** inscriptionElectedThankingAction');

        $user = $this->getUser();

        // Récupération de la commande en cours
        $orderId = $this->get('session')->get('p_order_id');
        $order = POrderQuery::create()->findPk($orderId);
        if (!$order) {
            $this->get('session')->getFlashBag()->add('error', 'Session expirée.');
            return $this->redirect($this->generateUrl('Homepage'));
        }

        // Suppression des valeurs en session
        $this->get('session')->remove('p_o_subscription_id');
        $this->get('session')->remove('p_order_id');
        
        // Finalisation du process d'inscription élu
        $this->get('politizr.functional.security')->inscriptionFinishElected($user);

        // Cas migration formule > MAJ du layout
        $layout = 'PolitizrFrontBundle::layoutPublic.html.twig';
        if ($user->hasRole('ROLE_CITIZEN')) {
            $layout = 'PolitizrFrontBundle::layoutConnected.html.twig';
        }

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
     */
    public function oauthTargetAction()
    {
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
    public function oauthRegisterAction(Request $request)
    {
        $logger = $this->get('logger');
        $logger->info('*** oauthRegisterAction');

        // Appel du service connexion oauth ou création user + connexion oauth suivant les cas
        $redirectUrl = $this->get('politizr.functional.security')->oauthRegister();

        // Redirection
        return $this->redirect($redirectUrl);
    }
}
