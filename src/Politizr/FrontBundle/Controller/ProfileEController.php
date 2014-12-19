<?php

namespace Politizr\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Politizr\Exception\InconsistentDataException;

use Politizr\Model\PDocumentPeer;

use Politizr\Model\PUserQuery;
use Politizr\Model\PDocumentQuery;
use Politizr\Model\PDDebateQuery;
use Politizr\Model\PDReactionQuery;
use Politizr\Model\PTagQuery;
use Politizr\Model\PUTaggedTQuery;
use Politizr\Model\PUFollowTQuery;
use Politizr\Model\PUFollowDDQuery;
use Politizr\Model\PUFollowUQuery;
use Politizr\Model\PDCommentQuery;
use Politizr\Model\PRBadgeTypeQuery;
use Politizr\Model\PUReputationRBQuery;

use Politizr\Model\PUser;
use Politizr\Model\PTag;
use Politizr\Model\PDDebate;
use Politizr\Model\PDReaction;
use Politizr\Model\PTTagType;
use Politizr\Model\PUTaggedT;
use Politizr\Model\PUFollowT;

use Politizr\FrontBundle\Form\Type\PUserIdentityType;
use Politizr\FrontBundle\Form\Type\PUserEmailType;
use Politizr\FrontBundle\Form\Type\PUserBiographyType;
use Politizr\FrontBundle\Form\Type\PUserConnectionType;

/**
 * Gestion profil débatteur
 *
 * TODO:
 *	- gestion des erreurs / levés d'exceptions à revoir/blindés pour les appels Ajax
 *  - refactorisation pour réduire les doublons de code entre les tables PUTaggedT et PUFollowT
 *  - refactoring gestion des tags > gestion des doublons / admin + externalisation logique métier dans les *Query class
 *
 * @author Lionel Bouzonville
 */
class ProfileEController extends Controller {


    /* ######################################################################################################## */
    /*                                                 ROUTING CLASSIQUE                                        */
    /* ######################################################################################################## */

    /* ######################################################################################################## */
    /*                                                    ACTUALITES                                            */
    /* ######################################################################################################## */

    /**
     *  Accueil
     */
    public function homepageAction()
    {
        $logger = $this->get('logger');
        $logger->info('*** homepageAction');

        return $this->redirect($this->generateUrl('TimelineE'));
    }

    /**
     *  Timeline
     */
    public function timelineAction()
    {
        $logger = $this->get('logger');
        $logger->info('*** timelineAction');

        // Récupération de la requête SQL
        $sql = $this->get('politizr.service.timeline')->getSql();

        // Exécution de la requête SQL & préparation du modèle
        $timeline = $this->get('politizr.service.timeline')->getTimeline($sql);

        return $this->render('PolitizrFrontBundle:ProfileE:timeline.html.twig', array(
                    'timeline' => $timeline
            ));
    }

    /**
     *  Suggestions
     */
    public function suggestionsAction()
    {
        $logger = $this->get('logger');
        $logger->info('*** suggestionsAction');

        // Récupération user courant
        $pUser = $this->getUser();

        // *********************************** //
        //      Récupération objets vue
        // *********************************** //

        //      Suggestions de documents

        // Récupération listing débat "match" tags géo
        // TODO: algo "match" à définir
        // TODO > pagination ajax vis scrolling
        $debatesGeo = $pUser->getTaggedDebates(PTTagType::TYPE_GEO);
        $debatesTheme = $pUser->getTaggedDebates(PTTagType::TYPE_THEME);

        //      Suggestions d'utilisateurs débatteurs
        $usersGeo = $pUser->getTaggedPUsers(PTTagType::TYPE_GEO, true);

        // *********************************** //
        //      Affichage de la vue
        // *********************************** //
        return $this->render('PolitizrFrontBundle:ProfileE:suggestions.html.twig', array(
                    'debatesGeo' => $debatesGeo,
                    'debatesTheme' => $debatesTheme,
                    'usersGeo' => $usersGeo
            ));
    }

    /**
     *  Populaires
     */
    public function popularsAction()
    {
        $logger = $this->get('logger');
        $logger->info('*** popularsAction');

        // *********************************** //
        //      Récupération objets vue
        // *********************************** //

        // débats les plus populaires
        $debates = PDDebateQuery::create()->online()->popularity(5)->find();

        // profils les plus populaires
        $users = PUserQuery::create()->filterByQualified(true)->online()->popularity(5)->find();

        // commentaires les plus populaires
        $comments = PDCommentQuery::create()->online()->last(10)->find();

        // *********************************** //
        //      Affichage de la vue
        // *********************************** //

        return $this->render('PolitizrFrontBundle:ProfileE:populars.html.twig', array(
                'debates' => $debates,
                'users' => $users,
                'comments' => $comments,
            ));
    }

    /**
     *  Derniers
     */
    public function lastAction()
    {
        $logger = $this->get('logger');
        $logger->info('*** lastAction');

        // *********************************** //
        //      Récupération objets vue
        // *********************************** //

        // deniers débats publiés
        $debates = PDDebateQuery::create()
                    ->online()
                    ->last()
                    ->find();

        // derniers profils qualifiés enregistrés
        $users = PUserQuery::create()
                    ->filterByQualified(true)
                    ->online()
                    ->last()
                    ->find();

        // commentaires les plus populaires
        $comments = PDCommentQuery::create()
                    ->online()
                    ->last()
                    ->find();

        // *********************************** //
        //      Affichage de la vue
        // *********************************** //

        return $this->render('PolitizrFrontBundle:ProfileE:last.html.twig', array(
                'debates' => $debates,
                'users' => $users,
                'comments' => $comments,
            ));
    }

    /* ######################################################################################################## */
    /*                                                 CONTRIBUTIONS                                            */
    /* ######################################################################################################## */


    /**
     *  Mes contributions - Accueil
     */
    public function myRatesAction()
    {
        $logger = $this->get('logger');
        $logger->info('*** myRatesAction');

        // Récupération user courant
        $pUser = $this->getUser();

        // *********************************** //
        //      Récupération objets vue
        // *********************************** //

        // Débats brouillons en attente de finalisation
        $debateDrafts = PDDebateQuery::create()->filterByPUserId($pUser->getId())->filterByPublished(false)->find();

        // Débats brouillons en attente de finalisation
        $reactionDrafts = PDReactionQuery::create()->filterByPUserId($pUser->getId())->filterByPublished(false)->find();

        // Débats rédigés
        $debates = PDDebateQuery::create()->filterByPUserId($pUser->getId())->online()->find();

        // Réactions rédigées
        $reactions = PDReactionQuery::create()->filterByPUserId($pUser->getId())->online()->find();

        // Commentaires rédigés
        $comments = PDCommentQuery::create()->filterByPUserId($pUser->getId())->online()->find();

        // *********************************** //
        //      Affichage de la vue
        // *********************************** //

        return $this->render('PolitizrFrontBundle:ProfileE:myRates.html.twig', array(
            'debateDrafts' => $debateDrafts,
            'reactionDrafts' => $reactionDrafts,
            'debates' => $debates,
            'reactions' => $reactions,
            'comments' => $comments,
            ));
    }

    /**
     *  Mes contributions - Brouillons
     */
    public function myDraftsAction()
    {
        $logger = $this->get('logger');
        $logger->info('*** myDraftsAction');

        // Récupération user courant
        $pUser = $this->getUser();

        // *********************************** //
        //      Récupération objets vue
        // *********************************** //

        // Débats brouillons en attente de finalisation
        $debateDrafts = PDDebateQuery::create()->filterByPUserId($pUser->getId())->filterByPublished(false)->find();

        // Réactions brouillons en attente de finalisation
        $reactionDrafts = PDReactionQuery::create()->filterByPUserId($pUser->getId())->filterByPublished(false)->find();

        // *********************************** //
        //      Affichage de la vue
        // *********************************** //

        return $this->render('PolitizrFrontBundle:ProfileE:myDrafts.html.twig', array(
            'debateDrafts' => $debateDrafts,
            'reactionDrafts' => $reactionDrafts,
            ));
    }


    /**
     *  Mes contributions - Débats
     */
    public function myDebatesAction()
    {
        $logger = $this->get('logger');
        $logger->info('*** myDebatesAction');

        // Récupération user courant
        $pUser = $this->getUser();

        // *********************************** //
        //      Récupération objets vue
        // *********************************** //
        // Débats rédigés
        $debates = PDDebateQuery::create()->filterByPUserId($pUser->getId())->online()->find();

        // *********************************** //
        //      Affichage de la vue
        // *********************************** //

        return $this->render('PolitizrFrontBundle:ProfileE:myDebates.html.twig', array(
            'debates' => $debates
            ));
    }

    /**
     *  Mes contributions - Réactions
     */
    public function myReactionsAction()
    {
        $logger = $this->get('logger');
        $logger->info('*** myReactionsAction');

        // Récupération user courant
        $pUser = $this->getUser();

        // *********************************** //
        //      Récupération objets vue
        // *********************************** //
        // Réactions rédigées
        $reactions = PDReactionQuery::create()->filterByPUserId($pUser->getId())->online()->find();

        // *********************************** //
        //      Affichage de la vue
        // *********************************** //

        return $this->render('PolitizrFrontBundle:ProfileE:myReactions.html.twig', array(
            'reactions' => $reactions
            ));
    }

    /**
     *  Mes contributions - Commentaires
     */
    public function myCommentsAction()
    {
        $logger = $this->get('logger');
        $logger->info('*** myCommentsAction');

        // Récupération user courant
        $pUser = $this->getUser();

        // *********************************** //
        //      Récupération objets vue
        // *********************************** //

        // Commentaires rédigés
        $comments = PDCommentQuery::create()->filterByPUserId($pUser->getId())->online()->find();

        // *********************************** //
        //      Affichage de la vue
        // *********************************** //

        return $this->render('PolitizrFrontBundle:ProfileE:myComments.html.twig', array(
            'comments' => $comments
            ));
    }


    /* ######################################################################################################## */
    /*                                                    ACTUALITES                                            */
    /* ######################################################################################################## */

    /**
     *  Mon compte - Accueil
     */
    public function myAccountAction()
    {
        $logger = $this->get('logger');
        $logger->info('*** myAccountAction');

        // Récupération user courant
        $pUser = $this->getUser();

        // *********************************** //
        //      Récupération objets vue
        // *********************************** //

        // *********************************** //
        //      Affichage de la vue
        // *********************************** //

        return $this->render('PolitizrFrontBundle:ProfileE:myAccount.html.twig', array(
            ));
    }


    /**
     *  Mon compte - Mes Tags
     */
    public function myTagsAction()
    {
        $logger = $this->get('logger');
        $logger->info('*** myTagsAction');

        // Récupération user courant
        $pUser = $this->getUser();

        // *********************************** //
        //      Récupération objets vue
        // *********************************** //

        // *********************************** //
        //      Affichage de la vue
        // *********************************** //

        return $this->render('PolitizrFrontBundle:ProfileE:myTags.html.twig', array(
                'pUser' => $pUser,
            ));
    }


    /**
     *  Mon compte - Ma réputation
     */
    public function myReputationAction()
    {
        $logger = $this->get('logger');
        $logger->info('*** myReputationAction');

        // Récupération user courant
        $pUser = $this->getUser();

        // *********************************** //
        //      Récupération objets vue
        // *********************************** //

        // score de réputation
        $reputationScore = $pUser->getReputationScore();
        
        // badges
        // type
        $badgeTypes = PRBadgeTypeQuery::create()
                        ->orderByTitle()
                        ->find()
                        ;

        // ids des badges du user
        $badgeIds = array();
        $badgeIds = PUReputationRBQuery::create()
                        ->filterByPUserId($pUser->getId())
                        ->find()
                        ->toKeyValue('PRBadgeId', 'PRBadgeId')
                        // ->getPrimaryKeys()
                        ;
        $badgeIds = array_keys($badgeIds);

        // *********************************** //
        //      Affichage de la vue
        // *********************************** //

        return $this->render('PolitizrFrontBundle:ProfileE:myReputation.html.twig', array(
            'reputationScore' => $reputationScore,
            'badgeTypes' => $badgeTypes,
            'badgeIds' => $badgeIds,
            ));
    }


    /**
     *  Mon compte - Mes informations personnelles
     */
    public function myPersoAction()
    {
        $logger = $this->get('logger');
        $logger->info('*** myPersoAction');

        // Récupération user courant
        $user = $this->getUser();

        // Récupération photo profil
        $fileName = $user->getFileName();

        // *********************************** //
        //      Formulaires
        // *********************************** //
        $formPerso1 = $this->createForm(new PUserIdentityType($user), $user);
        $formPerso2 = $this->createForm(new PUserEmailType(), $user);
        $formPerso3 = $this->createForm(new PUserBiographyType(), $user);
        $formPerso4 = $this->createForm(new PUserConnectionType(), $user);

        // *********************************** //
        //      Affichage de la vue
        // *********************************** //
        return $this->render('PolitizrFrontBundle:ProfileE:myPerso.html.twig', array(
                        'formPerso1' => $formPerso1->createView(),
                        'formPerso2' => $formPerso2->createView(),
                        'formPerso3' => $formPerso3->createView(),
                        'formPerso4' => $formPerso4->createView(),
                        'fileName' => $fileName,
            ));
    }




    /* ######################################################################################################## */
    /*                                                  FONCTIONS AJAX                                          */
    /* ######################################################################################################## */



    /* ######################################################################################################## */
    /*                                                  FONCTIONS PRIVÉES                                             */
    /* ######################################################################################################## */


    /**
     * 
     * @param type $query
     * @return \Pagerfanta\Pagerfanta
     * @throws type
     */
    private function preparePagination($query, $maxPerPage = 5) {
        $adapter = new PropelAdapter($query);
        $pagerfanta = new Pagerfanta($adapter);

        try {
            $pagerfanta->setMaxPerPage($maxPerPage)->setCurrentPage($this->getRequest()->get('page'));
        } catch (Pagerfanta\Exception\NotIntegerCurrentPageException $e) {
            throw $this->createNotFoundException('PagerFanta NotIntegerCurrentPageException.');
        } catch (Pagerfanta\Exception\LessThan1CurrentPageException $e) {
            throw $this->createNotFoundException('PagerFanta LessThan1CurrentPageException.');
        } catch (Pagerfanta\Exception\OutOfRangeCurrentPageException $e) {
            throw $this->createNotFoundException('PagerFantaOutOfRangeCurrentPageException.');
        }
        
        return $pagerfanta;
    }
}