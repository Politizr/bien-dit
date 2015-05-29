<?php
namespace Politizr\FrontBundle\Lib\Service;

use Symfony\Component\EventDispatcher\GenericEvent;

use StudioEcho\Lib\StudioEchoUtils;

use Politizr\Exception\InconsistentDataException;
use Politizr\Exception\FormValidationException;

use Politizr\FrontBundle\Lib\SimpleImage;

use Politizr\Model\PDocument;
use Politizr\Model\PDDebate;
use Politizr\Model\PDReaction;

use Politizr\Model\PDocumentQuery;
use Politizr\Model\PDDebateQuery;
use Politizr\Model\PDReactionQuery;
use Politizr\Model\PUserQuery;

/**
 * Services métiers associés aux modal.
 *
 * @author Lionel Bouzonville
 */
class ModalManager
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
    /*                                         FONCTIONS PRIVEES                                                */
    /* ######################################################################################################## */

    /**
     * Retourne un tableau contenant 4 éléments: l'odonnancement, les filtres, l'offset à appliquer à la recherche
     * et éventuellement l'id de l'objet associé
     *
     * @return array
     */
    private function getFiltersFromRequest()
    {
        $logger = $this->sc->get('logger');
        $request = $this->sc->get('request');

        $order = $request->get('order');
        $logger->info('$order = ' . print_r($order, true));
        $filtersDate = $request->get('filtersDate');
        $logger->info('$filtersDate = ' . print_r($filtersDate, true));
        $filtersUserType = $request->get('filtersUserType');
        $logger->info('$filtersUserType = ' . print_r($filtersUserType, true));
        $offset = $request->get('offset');
        $logger->info('$offset = ' . print_r($offset, true));
        $subjectId = $request->get('subjectId');
        $logger->info('$subjectId = ' . print_r($subjectId, true));

        // regroupement des filtres
        $filters = array_merge($filtersDate, $filtersUserType);

        return [ $order, $filters, $offset, $subjectId ];
    }

    /* ######################################################################################################## */
    /*                                FONCTIONS MODAL GENERIQUES                                                */
    /* ######################################################################################################## */


    /**
     * Chargement d'une box modal contenant une liste paginée
     */
    public function modalPaginatedList()
    {
        $logger = $this->sc->get('logger');
        $logger->info('*** modalPaginatedList');
        
        // Récupération args
        $request = $this->sc->get('request');

        $twigTemplate = $request->get('twigTemplate');
        $model = $request->get('model');
        $slug = $request->get('slug');

        // Récupération objet associé à la modal
        $subject = null;
        if ($model && $slug) {
            $queryModel = 'Politizr\Model\\' . $model . 'Query';
            $subject = $queryModel::create()
                ->filterBySlug($slug)
                ->findOne();
        }

        // Construction rendu
        $templating = $this->sc->get('templating');
        $html = $templating->render(
            'PolitizrFrontBundle:PaginatedList:'.$twigTemplate,
            array(
                'subject' => $subject
            )
        );

        // Renvoi de l'ensemble des blocs HTML maj
        return array(
            'html' => $html,
            );
    }

    /**
     * Chargement des filtres
     */
    public function filters()
    {
        $logger = $this->sc->get('logger');
        $logger->info('*** filters');
        
        // Récupération args
        $request = $this->sc->get('request');
        $type = $request->get('type');

        // Construction rendu
        $templating = $this->sc->get('templating');
        if ('debate' === $type) {
            $listOrder = $templating->render(
                'PolitizrFrontBundle:PaginatedList:_formOrderByDebate.html.twig'
            );
            $listFilter = $templating->render(
                'PolitizrFrontBundle:PaginatedList:_formFiltersByDebate.html.twig'
            );
        } elseif ('user' === $type) {
            $listOrder = $templating->render(
                'PolitizrFrontBundle:PaginatedList:_formOrderByUser.html.twig'
            );
            $listFilter = $templating->render(
                'PolitizrFrontBundle:PaginatedList:_formFiltersByUser.html.twig'
            );
        }

        // Renvoi de l'ensemble des blocs HTML maj
        return array(
            'listOrder' => $listOrder,
            'listFilter' => $listFilter,
            );
    }


    /* ######################################################################################################## */
    /*                                              CLASSEMENT                                                  */
    /* ######################################################################################################## */

    /**
     * Liste des débats type "classement"
     *
     */
    public function rankingDebateList()
    {
        $logger = $this->sc->get('logger');
        $logger->info('*** rankingDebateList');
        
        // Récupération paramètres requête
        $queryParams = $this->getFiltersFromRequest();
        $order = $queryParams[0];
        $filters = $queryParams[1];
        $offset = $queryParams[2];

        // @todo gérer les "limit" dans une variable
        $debates = PDDebateQuery::create()
                    ->distinct()
                    ->online()
                    ->filterByKeywords($filters)
                    ->orderWithKeyword($order)
                    ->limit(10)
                    ->offset($offset)
                    ->find();

        $moreResults = false;
        if (sizeof($debates) == 10) {
            $moreResults = true;
        }

        // Construction rendu
        $templating = $this->sc->get('templating');
        $html = $templating->render(
            'PolitizrFrontBundle:PaginatedList:_debates.html.twig',
            array(
                'debates' => $debates,
                'offset' => intval($offset) + 10,
                'moreResults' => $moreResults,
            )
        );

        // Renvoi de l'ensemble des blocs HTML maj
        return array(
            'html' => $html,
            );
    }

    /**
     * Liste des profils type "classement"
     *
     */
    public function rankingUserList()
    {
        $logger = $this->sc->get('logger');
        $logger->info('*** rankingUserList');
        
        // Récupération paramètres requête
        $queryParams = $this->getFiltersFromRequest();
        $order = $queryParams[0];
        $filters = $queryParams[1];
        $offset = $queryParams[2];

        // @todo gérer les "limit" dans une variable
        $users = PUserQuery::create()
                    ->distinct()
                    ->online()
                    ->filterByKeywords($filters)
                    ->orderWithKeyword($order)
                    ->limit(10)
                    ->offset($offset)
                    ->find();

        $moreResults = false;
        if (sizeof($users) == 10) {
            $moreResults = true;
        }

        // Construction rendu
        $templating = $this->sc->get('templating');
        $html = $templating->render(
            'PolitizrFrontBundle:PaginatedList:_users.html.twig',
            array(
                'users' => $users,
                'offset' => intval($offset) + 10,
                'moreResults' => $moreResults,
                )
        );

        // Renvoi de l'ensemble des blocs HTML maj
        return array(
            'html' => $html,
            );
    }


    /**
     * Liste des débats type "suggestion"
     *
     */
    public function suggestionDebateList()
    {
        $logger = $this->sc->get('logger');
        $logger->info('*** suggestionDebateList');
        
        // Récupération user
        $user = $this->sc->get('security.context')->getToken()->getUser();

        // Récupération args
        $request = $this->sc->get('request');
        $offset = $request->get('offset');

        // @todo gérer les "limit" dans une variable
        $debates = PDDebateQuery::create()->findBySuggestion($user->getId(), $offset, 10);
        
        $moreResults = false;
        if (sizeof($debates) == 10) {
            $moreResults = true;
        }

        // Construction rendu
        $templating = $this->sc->get('templating');
        $html = $templating->render(
            'PolitizrFrontBundle:PaginatedList:_debates.html.twig',
            array(
                'debates' => $debates,
                'offset' => intval($offset) + 10,
                'moreResults' => $moreResults,
            )
        );

        // Renvoi de l'ensemble des blocs HTML maj
        return array(
            'html' => $html,
            );
    }

    /**
     * Liste des profils type "suggestion"
     *
     */
    public function suggestionUserList()
    {
        $logger = $this->sc->get('logger');
        $logger->info('*** suggestionUserList');
        
        // Récupération user
        $user = $this->sc->get('security.context')->getToken()->getUser();

        // Récupération args
        $request = $this->sc->get('request');
        $offset = $request->get('offset');

        // @todo gérer les "limit" dans une variable
        $users = PUserQuery::create()->findBySuggestion($user->getId(), $offset, 10);
        
        $moreResults = false;
        if (sizeof($users) == 10) {
            $moreResults = true;
        }

        // Construction rendu
        $templating = $this->sc->get('templating');
        $html = $templating->render(
            'PolitizrFrontBundle:PaginatedList:_users.html.twig',
            array(
                'users' => $users,
                'offset' => intval($offset) + 10,
                'moreResults' => $moreResults,
                )
        );

        // Renvoi de l'ensemble des blocs HTML maj
        return array(
            'html' => $html
            );
    }


    /**
     * Liste de débats par tag
     *
     */
    public function tagDebateList()
    {
        $logger = $this->sc->get('logger');
        $logger->info('*** tagDebateList');
        
        // Récupération paramètres requête
        $queryParams = $this->getFiltersFromRequest();
        $order = $queryParams[0];
        $filters = $queryParams[1];
        $offset = $queryParams[2];

        // paramètre supplémentaire
        $request = $this->sc->get('request');
        $tagId = $request->get('subjectId');
        $logger->info('$tagId = ' . print_r($tagId, true));

        // @todo gérer les "limit" dans une variable
        $debates = PDDebateQuery::create()
                    ->distinct()
                    ->online()
                    ->usePDDTaggedTQuery()
                        ->filterByPTagId($tagId)
                    ->endUse()
                    ->filterByKeywords($filters)
                    ->orderWithKeyword($order)
                    ->limit(10)
                    ->offset($offset)
                    ->find();

        $moreResults = false;
        if (sizeof($debates) == 10) {
            $moreResults = true;
        }

        // Construction rendu
        $templating = $this->sc->get('templating');
        $html = $templating->render(
            'PolitizrFrontBundle:PaginatedList:_debates.html.twig',
            array(
                'debates' => $debates,
                'offset' => intval($offset) + 10,
                'moreResults' => $moreResults,
            )
        );

        // Renvoi de l'ensemble des blocs HTML maj
        return array(
            'html' => $html,
            );
    }

    /**
     * Liste de profils par tag
     *
     */
    public function tagUserList()
    {
        $logger = $this->sc->get('logger');
        $logger->info('*** tagUserList');
        
        // Récupération paramètres requête
        $queryParams = $this->getFiltersFromRequest();
        $order = $queryParams[0];
        $filters = $queryParams[1];
        $offset = $queryParams[2];
        $subjectId = $queryParams[3];

        // @todo gérer les "limit" dans une variable
        $users = PUserQuery::create()
                    ->distinct()
                    ->online()
                    ->usePuTaggedTPUserQuery()
                        ->filterByPTagId($subjectId)
                    ->endUse()
                    ->filterByKeywords($filters)
                    ->orderWithKeyword($order)
                    ->limit(10)
                    ->offset($offset)
                    ->find();

        $moreResults = false;
        if (sizeof($users) == 10) {
            $moreResults = true;
        }

        // Construction rendu
        $templating = $this->sc->get('templating');
        $html = $templating->render(
            'PolitizrFrontBundle:PaginatedList:_users.html.twig',
            array(
                'users' => $users,
                'offset' => intval($offset) + 10,
                'moreResults' => $moreResults,
                )
        );

        // Renvoi de l'ensemble des blocs HTML maj
        return array(
            'html' => $html,
            );
    }

    /**
     * Liste de users associé à l'organisation
     *
     */
    public function organizationUserList()
    {
        $logger = $this->sc->get('logger');
        $logger->info('*** organizationUserList');
        
        // Récupération paramètres requête
        $queryParams = $this->getFiltersFromRequest();
        $order = $queryParams[0];
        $filters = $queryParams[1];
        $offset = $queryParams[2];
        $subjectId = $queryParams[3];

        // @todo gérer les "limit" dans une variable
        $users = PUserQuery::create()
                    ->distinct()
                    ->online()
                    ->usePUCurrentQOPUserQuery(null, \Criteria::LEFT_JOIN)
                        ->filterByPQOrganizationId($subjectId)
                    ->endUse()
                    ->_or()
                    ->usePUAffinityQOPUserQuery(null, \Criteria::LEFT_JOIN)
                        ->filterByPQOrganizationId($subjectId)
                    ->endUse()
                    ->filterByKeywords($filters)
                    ->orderWithKeyword($order)
                    ->limit(10)
                    ->offset($offset)
                    ->find()
                    ;

        $moreResults = false;
        if (sizeof($users) == 10) {
            $moreResults = true;
        }

        // Construction rendu
        $templating = $this->sc->get('templating');
        $html = $templating->render(
            'PolitizrFrontBundle:PaginatedList:_users.html.twig',
            array(
                'users' => $users,
                'offset' => intval($offset) + 10,
                'moreResults' => $moreResults,
                )
        );

        // Renvoi de l'ensemble des blocs HTML maj
        return array(
            'html' => $html,
            );
    }

    /**
     * Liste des débats suivis
     *
     */
    public function followedDebateList()
    {
        $logger = $this->sc->get('logger');
        $logger->info('*** followedDebateList');
        
        // Récupération user
        $user = $this->sc->get('security.context')->getToken()->getUser();

        // Récupération paramètres requête
        $queryParams = $this->getFiltersFromRequest();
        $order = $queryParams[0];
        $filters = $queryParams[1];
        $offset = $queryParams[2];

        // @todo gérer les "limit" dans une variable
        $debates = PDDebateQuery::create()
                    ->distinct()
                    ->online()
                    ->usePuFollowDdPDDebateQuery()
                        ->filterByPUserId($user->getId())
                    ->endUse()
                    ->filterByKeywords($filters)
                    ->orderWithKeyword($order)
                    ->limit(10)
                    ->offset($offset)
                    ->find();

        $moreResults = false;
        if (sizeof($debates) == 10) {
            $moreResults = true;
        }

        // Construction rendu
        $templating = $this->sc->get('templating');
        $html = $templating->render(
            'PolitizrFrontBundle:PaginatedList:_debates.html.twig',
            array(
                'debates' => $debates,
                'offset' => intval($offset) + 10,
                'moreResults' => $moreResults,
            )
        );

        // Renvoi de l'ensemble des blocs HTML maj
        return array(
            'html' => $html,
            );
    }

    /**
     * Liste des users suivis
     */
    public function followedUserList()
    {
        $logger = $this->sc->get('logger');
        $logger->info('*** followedUserList');
        
        // Récupération user
        $user = $this->sc->get('security.context')->getToken()->getUser();

        // Récupération paramètres requête
        $queryParams = $this->getFiltersFromRequest();
        $order = $queryParams[0];
        $filters = $queryParams[1];
        $offset = $queryParams[2];

        // @todo gérer les "limit" dans une variable
        $query = PUserQuery::create()
                    ->distinct()
                    ->online()
                    ->filterByKeywords($filters)
                    ->orderWithKeyword($order)
                    ->limit(10)
                    ->offset($offset)
                    ;

        $users = $user->getSubscribers($query);

        $moreResults = false;
        if (sizeof($users) == 10) {
            $moreResults = true;
        }

        // Construction rendu
        $templating = $this->sc->get('templating');
        $html = $templating->render(
            'PolitizrFrontBundle:PaginatedList:_users.html.twig',
            array(
                'users' => $users,
                'order' => $order,
                'offset' => intval($offset) + 10,
                'moreResults' => $moreResults,
                )
        );

        // Renvoi de l'ensemble des blocs HTML maj
        return array(
            'html' => $html,
            );
    }
}
