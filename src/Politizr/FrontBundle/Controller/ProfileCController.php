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
use Politizr\Model\PTagQuery;
use Politizr\Model\PUTaggedTQuery;
use Politizr\Model\PUFollowTQuery;
use Politizr\Model\PUFollowDDQuery;
use Politizr\Model\PUFollowUQuery;
use Politizr\Model\PDCommentQuery;
use Politizr\Model\PRBadgeTypeQuery;
use Politizr\Model\PUReputationRBQuery;

use Politizr\Model\PUser;
use Politizr\Model\PUType;
use Politizr\Model\PTag;
use Politizr\Model\PDDebate;
use Politizr\Model\PTTagType;
use Politizr\Model\PUTaggedT;
use Politizr\Model\PUFollowT;

use Politizr\FrontBundle\Form\Type\PUserPerso1Type;
use Politizr\FrontBundle\Form\Type\PUserPerso2Type;
use Politizr\FrontBundle\Form\Type\PUserPerso3Type;
use Politizr\FrontBundle\Form\Type\PUserPerso4Type;


/**
 * Gestion profil citoyen
 *
 * TODO:
 *	- gestion des erreurs / levés d'exceptions à revoir/blindés pour les appels Ajax
 *  - refactorisation pour réduire les doublons de code entre les tables PUTaggedT et PUFollowT
 *  - refactoring gestion des tags > gestion des doublons / admin + externalisation logique métier dans les *Query class
 *
 * @author Lionel Bouzonville
 */
class ProfileCController extends Controller {

    /* ######################################################################################################## */
    /*                                                 ROUTING CLASSIQUE                                        */
    /* ######################################################################################################## */

    /* ######################################################################################################## */
    /*                                                    ACTUALITES                                            */
    /* ######################################################################################################## */

    /**
     *  Accueil
     */
    public function homepageCAction()
    {
        $logger = $this->get('logger');
        $logger->info('*** homepageCAction');

        return $this->redirect($this->generateUrl('TimelineC'));
    }

    /**
     *  Timeline
     */
    public function timelineCAction()
    {
        $logger = $this->get('logger');
        $logger->info('*** timelineCAction');

        // Récupération user courant
        $pUser = $this->getUser();

        // *********************************** //
        //      Récupération objets vue
        // *********************************** //

        // TODO
        // + réactions sur les débats rédigés par le user courant


        // Requête MYSQL
        /*

( SELECT p_document.title, p_document.published_at
FROM p_document
    LEFT JOIN p_d_reaction 
        ON p_document.id = p_d_reaction.id
WHERE p_d_reaction.p_d_debate_id IN (1, 2, 3) )

UNION DISTINCT

( SELECT p_document.title, p_document.published_at
FROM p_document
WHERE p_document.p_user_id IN (1, 2, 3) )

ORDER BY published DESC

        */

        // Récupération d'un tableau des ids des débats suivis
        $debateIds = PUFollowDDQuery::create()
                        ->filterByPUserId($pUser->getId())
                        ->find()
                        ->toKeyValue('PDDebateId', 'PDDebateId')
                        // ->getPrimaryKeys()
                        ;
        $debateIds = array_keys($debateIds);
        $inQueryDebateIds = implode(',', $debateIds);
        $logger->info('inQueryDebateIds = '.print_r($inQueryDebateIds, true));

        // Récupération d'un tableau des ids des users suivis
        $userIds = PUFollowUQuery::create()
                        ->filterByPUserFollowerId($pUser->getId())
                        ->find()
                        ->toKeyValue('PUserId', 'PUserId')
                        // ->getPrimaryKeys()
                        ;
        $userIds = array_keys($userIds);
        $inQueryUserIds = implode(',', $userIds);
        $logger->info('inQueryUserIds = '.print_r($inQueryUserIds, true));

        // Préparation requête SQL
        if (!empty($debateIds) && !empty($userIds)) {
            $sql = "
    ( SELECT p_document.id
    FROM p_document
        LEFT JOIN p_d_reaction 
            ON p_document.id = p_d_reaction.id
    WHERE p_d_reaction.p_d_debate_id IN (".$inQueryDebateIds.") )

    UNION DISTINCT

    ( SELECT p_document.id
    FROM p_document
    WHERE p_document.p_user_id IN (".$inQueryUserIds.") )
        ";
        } elseif(!empty($debateIds)) {
            $sql = "
    SELECT p_document.id
    FROM p_document
        LEFT JOIN p_d_reaction 
            ON p_document.id = p_d_reaction.id
    WHERE p_d_reaction.p_d_debate_id IN (".$inQueryDebateIds.")
        ";
        } elseif(!empty($debateIds)) {
            $sql = "
    SELECT p_document.id
    FROM p_document
    WHERE p_document.p_user_id IN (".$inQueryUserIds.")
        ";
        } else {
            $sql = null;
            $listPKs = array();
        }

        if ($sql) {
            // Exécution de la requête timeline brute
            $con = \Propel::getConnection(PDocumentPeer::DATABASE_NAME, \Propel::CONNECTION_READ);
            $stmt = $con->prepare($sql);
            $stmt->execute();

            $listPKs = $stmt->fetchAll(\PDO::FETCH_COLUMN);
            $logger->info('listPKs = '.print_r($listPKs, true));
        }

        // Préparation d'une requête "objet" ordonnancée
        // TODO > pagination ajax vis scrolling
        $maxPerPage = 10;
        $query = PDocumentQuery::create()
                    ->addUsingAlias(PDocumentPeer::ID, $listPKs, \Criteria::IN)
                    ->orderByPublishedAt('desc')
                    ;
        $documents = $query->find();

        // *********************************** //
        //      Affichage de la vue
        // *********************************** //

        return $this->render('PolitizrFrontBundle:ProfileC:timelineC.html.twig', array(
                    'documents' => $documents
            ));
    }

    /**
     *  Suggestions
     */
    public function suggestionsCAction()
    {
        $logger = $this->get('logger');
        $logger->info('*** suggestionsCAction');

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

        //      Suggestions d'utilisateurs élus
        $usersGeo = $pUser->getTaggedPUsers(PTTagType::TYPE_GEO, PUType::TYPE_QUALIFIE);

        // *********************************** //
        //      Affichage de la vue
        // *********************************** //
        return $this->render('PolitizrFrontBundle:ProfileC:suggestionsC.html.twig', array(
                    'debatesGeo' => $debatesGeo,
                    'debatesTheme' => $debatesTheme,
                    'usersGeo' => $usersGeo
            ));
    }

    /**
     *  Populaires
     */
    public function popularsCAction()
    {
        $logger = $this->get('logger');
        $logger->info('*** popularsCAction');

        // *********************************** //
        //      Récupération objets vue
        // *********************************** //

        // débats les plus populaires
        $debates = PDDebateQuery::create()->online()->popularity(5)->find();

        // profils les plus populaires
        $users = PUserQuery::create()->filterByPUTypeId(PUType::TYPE_QUALIFIE)->online()->popularity(5)->find();

        // commentaires les plus populaires
        $comments = PDCommentQuery::create()->online()->last(10)->find();

        // *********************************** //
        //      Affichage de la vue
        // *********************************** //

        return $this->render('PolitizrFrontBundle:ProfileC:popularsC.html.twig', array(
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
    public function myRatesCAction()
    {
        $logger = $this->get('logger');
        $logger->info('*** myRatesCAction');

        // Récupération user courant
        $pUser = $this->getUser();

        // *********************************** //
        //      Récupération objets vue
        // *********************************** //

        // Débats brouillons en attente de finalisation
        $drafts = PDDebateQuery::create()->filterByPUserId($pUser->getId())->filterByPublished(false)->find();

        // Débats rédigés
        $debates = PDDebateQuery::create()->filterByPUserId($pUser->getId())->online()->find();

        // Commentaires rédigés
        $comments = PDCommentQuery::create()->filterByPUserId($pUser->getId())->online()->find();

        // *********************************** //
        //      Affichage de la vue
        // *********************************** //

        return $this->render('PolitizrFrontBundle:ProfileC:myRatesC.html.twig', array(
            'drafts' => $drafts,
            'debates' => $debates,
            'comments' => $comments,
            ));
    }

    /**
     *  Mes contributions - Brouillons
     */
    public function myDraftsCAction()
    {
        $logger = $this->get('logger');
        $logger->info('*** myDraftsCAction');

        // Récupération user courant
        $pUser = $this->getUser();

        // *********************************** //
        //      Récupération objets vue
        // *********************************** //

        // Débats brouillons en attente de finalisation
        $drafts = PDDebateQuery::create()->filterByPUserId($pUser->getId())->filterByPublished(false)->find();

        // *********************************** //
        //      Affichage de la vue
        // *********************************** //

        return $this->render('PolitizrFrontBundle:ProfileC:myDraftsC.html.twig', array(
            'drafts' => $drafts,
            ));
    }


    /**
     *  Mes contributions - Débats
     */
    public function myDebatesCAction()
    {
        $logger = $this->get('logger');
        $logger->info('*** myDebatesCAction');

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

        return $this->render('PolitizrFrontBundle:ProfileC:myDebatesC.html.twig', array(
            'debates' => $debates
            ));
    }

    /**
     *  Mes contributions - Commentaires
     */
    public function myCommentsCAction()
    {
        $logger = $this->get('logger');
        $logger->info('*** myCommentsCAction');

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

        return $this->render('PolitizrFrontBundle:ProfileC:myCommentsC.html.twig', array(
            'comments' => $comments
            ));
    }


    /* ######################################################################################################## */
    /*                                                    ACTUALITES                                            */
    /* ######################################################################################################## */

    /**
     *  Mon compte - Accueil
     */
    public function myAccountCAction()
    {
        $logger = $this->get('logger');
        $logger->info('*** myAccountCAction');

        // Récupération user courant
        $pUser = $this->getUser();

        // *********************************** //
        //      Récupération objets vue
        // *********************************** //

        // *********************************** //
        //      Affichage de la vue
        // *********************************** //

        return $this->render('PolitizrFrontBundle:ProfileC:myAccountC.html.twig', array(
            ));
    }


    /**
     *  Mon compte - Mes Tags
     */
    public function myTagsCAction()
    {
        $logger = $this->get('logger');
        $logger->info('*** myTagsCAction');

        // Récupération user courant
        $pUser = $this->getUser();

        // *********************************** //
        //      Récupération objets vue
        // *********************************** //

        // *********************************** //
        //      Affichage de la vue
        // *********************************** //

        return $this->render('PolitizrFrontBundle:ProfileC:myTagsC.html.twig', array(
                'pUser' => $pUser,
            ));
    }


    /**
     *  Mon compte - Ma réputation
     */
    public function myReputationCAction()
    {
        $logger = $this->get('logger');
        $logger->info('*** myReputationCAction');

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

        return $this->render('PolitizrFrontBundle:ProfileC:myReputationC.html.twig', array(
            'reputationScore' => $reputationScore,
            'badgeTypes' => $badgeTypes,
            'badgeIds' => $badgeIds,
            ));
    }


    /**
     *  Mon compte - Mes informations personnelles
     */
    public function myPersoCAction()
    {
        $logger = $this->get('logger');
        $logger->info('*** myPersoCAction');

        // Récupération user courant
        $user = $this->getUser();

        // Récupération photo profil
        $fileName = $user->getFileName();

        // *********************************** //
        //      Formulaires
        // *********************************** //
        $formPerso1 = $this->createForm(new PUserPerso1Type(), $user);
        $formPerso2 = $this->createForm(new PUserPerso2Type(), $user);
        $formPerso3 = $this->createForm(new PUserPerso3Type(), $user);
        $formPerso4 = $this->createForm(new PUserPerso4Type(), $user);

        // *********************************** //
        //      Affichage de la vue
        // *********************************** //
        return $this->render('PolitizrFrontBundle:ProfileC:myPersoC.html.twig', array(
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