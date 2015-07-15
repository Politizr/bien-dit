<?php
namespace Politizr\FrontBundle\Lib\Xhr;

use Politizr\Exception\InconsistentDataException;
use Politizr\Exception\FormValidationException;

use Politizr\Model\PDDebateQuery;
use Politizr\Model\PUserQuery;

/**
 * XHR service for modal management.
 *
 * @author Lionel Bouzonville
 */
class XhrModal
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
    /*                                         PRIVATE FUNCTIONS                                                */
    /* ######################################################################################################## */

    /**
     * Return an array with request listing information:
     *    - order,
     *    - filters,
     *    - offset,
     *    - associated object id (option),
     *
     * @return array[order,filters,offset,associatedObjectId]
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
    /*                                   GENERIC MODAL FUNCTIONS                                                */
    /* ######################################################################################################## */

    /**
     * Paginated modal listing loading
     */
    public function modalPaginatedList()
    {
        $logger = $this->sc->get('logger');
        $logger->info('*** modalPaginatedList');
        
        // Retrieve used services
        $request = $this->sc->get('request');
        $templating = $this->sc->get('templating');

        // Request arguments
        $twigTemplate = $request->get('twigTemplate');
        $logger->info('$twigTemplate = ' . print_r($twigTemplate, true));
        $model = $request->get('model');
        $logger->info('$model = ' . print_r($model, true));
        $slug = $request->get('slug');
        $logger->info('$slug = ' . print_r($slug, true));

        // Function process
        $subject = null;
        if ($model && $slug) {
            $queryModel = 'Politizr\Model\\' . $model . 'Query';
            $subject = $queryModel::create()
                ->filterBySlug($slug)
                ->findOne();
        }

        $html = $templating->render(
            'PolitizrFrontBundle:PaginatedList:'.$twigTemplate,
            array(
                'subject' => $subject
            )
        );

        return array(
            'html' => $html,
            );
    }

    /**
     * Filters loading
     */
    public function filters()
    {
        $logger = $this->sc->get('logger');
        $logger->info('*** filters');
        
        // Retrieve used services
        $request = $this->sc->get('request');
        $templating = $this->sc->get('templating');

        // Request arguments
        $type = $request->get('type');
        $logger->info('$type = ' . print_r($type, true));

        // @todo constant management refactor
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
    /*                                                 RANKING                                                  */
    /* ######################################################################################################## */

    /**
     * Debate ranking listing
     */
    public function rankingDebateList()
    {
        $logger = $this->sc->get('logger');
        $logger->info('*** rankingDebateList');

        // Retrieve used services
        $templating = $this->sc->get('templating');
        
        // Request arguments
        $queryParams = $this->getFiltersFromRequest();
        $order = $queryParams[0];
        $filters = $queryParams[1];
        $offset = $queryParams[2];

        // Function process
        // @todo constant management refactoring
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

        $html = $templating->render(
            'PolitizrFrontBundle:PaginatedList:_debates.html.twig',
            array(
                'debates' => $debates,
                'offset' => intval($offset) + 10,
                'moreResults' => $moreResults,
            )
        );

        return array(
            'html' => $html,
            );
    }

    /**
     * Ranking user listing
     */
    public function rankingUserList()
    {
        $logger = $this->sc->get('logger');
        $logger->info('*** rankingUserList');
        
        // Retrieve used services
        $templating = $this->sc->get('templating');
        
        // Request arguments
        $queryParams = $this->getFiltersFromRequest();
        $order = $queryParams[0];
        $filters = $queryParams[1];
        $offset = $queryParams[2];

        // Function process
        // @todo constant management refactoring
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

        $html = $templating->render(
            'PolitizrFrontBundle:PaginatedList:_users.html.twig',
            array(
                'users' => $users,
                'offset' => intval($offset) + 10,
                'moreResults' => $moreResults,
                )
        );

        return array(
            'html' => $html,
            );
    }

    /**
     * Suggestion debate listing
     */
    public function suggestionDebateList()
    {
        $logger = $this->sc->get('logger');
        $logger->info('*** suggestionDebateList');
        
        // Retrieve used services
        $request = $this->sc->get('request');
        $securityContext = $this->sc->get('security.context');
        $templating = $this->sc->get('templating');

        // Request arguments
        $offset = $request->get('offset');
        $logger->info('$offset = ' . print_r($offset, true));
        
        // Function process
        $user = $securityContext->getToken()->getUser();

        // @todo constant management refactoring
        $debates = PDDebateQuery::create()->findBySuggestion($user->getId(), $offset, 10);
        
        $moreResults = false;
        if (sizeof($debates) == 10) {
            $moreResults = true;
        }

        $html = $templating->render(
            'PolitizrFrontBundle:PaginatedList:_debates.html.twig',
            array(
                'debates' => $debates,
                'offset' => intval($offset) + 10,
                'moreResults' => $moreResults,
            )
        );

        return array(
            'html' => $html,
            );
    }

    /**
     * Suggestion user listing
     */
    public function suggestionUserList()
    {
        $logger = $this->sc->get('logger');
        $logger->info('*** suggestionUserList');
        
        // Retrieve used services
        $request = $this->sc->get('request');
        $securityContext = $this->sc->get('security.context');
        $templating = $this->sc->get('templating');

        // Request arguments
        $offset = $request->get('offset');
        $logger->info('$offset = ' . print_r($offset, true));
        
        // Function process
        $user = $securityContext->getToken()->getUser();

        // @todo constant management refactoring
        $users = PUserQuery::create()->findBySuggestion($user->getId(), $offset, 10);
        
        $moreResults = false;
        if (sizeof($users) == 10) {
            $moreResults = true;
        }

        $html = $templating->render(
            'PolitizrFrontBundle:PaginatedList:_users.html.twig',
            array(
                'users' => $users,
                'offset' => intval($offset) + 10,
                'moreResults' => $moreResults,
                )
        );

        return array(
            'html' => $html
            );
    }


    /**
     * Tag debate listing
     */
    public function tagDebateList()
    {
        $logger = $this->sc->get('logger');
        $logger->info('*** tagDebateList');
        
        // Retrieve used services
        $templating = $this->sc->get('templating');
        
        // Request arguments
        $queryParams = $this->getFiltersFromRequest();
        $order = $queryParams[0];
        $filters = $queryParams[1];
        $offset = $queryParams[2];
        $subjectId = $queryParams[3];

        // Function process
        // @todo constant management refactoring
        $debates = PDDebateQuery::create()
                    ->distinct()
                    ->online()
                    ->usePDDTaggedTQuery()
                        ->filterByPTagId($subjectId)
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

        $html = $templating->render(
            'PolitizrFrontBundle:PaginatedList:_debates.html.twig',
            array(
                'debates' => $debates,
                'offset' => intval($offset) + 10,
                'moreResults' => $moreResults,
            )
        );

        return array(
            'html' => $html,
            );
    }

    /**
     * Tag user listing
     */
    public function tagUserList()
    {
        $logger = $this->sc->get('logger');
        $logger->info('*** tagUserList');
        
        // Retrieve used services
        $templating = $this->sc->get('templating');
        
        // Request arguments
        $queryParams = $this->getFiltersFromRequest();
        $order = $queryParams[0];
        $filters = $queryParams[1];
        $offset = $queryParams[2];
        $subjectId = $queryParams[3];

        // Function process
        // @todo constant management refactoring
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

        $html = $templating->render(
            'PolitizrFrontBundle:PaginatedList:_users.html.twig',
            array(
                'users' => $users,
                'offset' => intval($offset) + 10,
                'moreResults' => $moreResults,
                )
        );

        return array(
            'html' => $html,
            );
    }

    /**
     * Organization user listing
     */
    public function organizationUserList()
    {
        $logger = $this->sc->get('logger');
        $logger->info('*** organizationUserList');
        
        // Retrieve used services
        $templating = $this->sc->get('templating');
        
        // Request arguments
        $queryParams = $this->getFiltersFromRequest();
        $order = $queryParams[0];
        $filters = $queryParams[1];
        $offset = $queryParams[2];
        $subjectId = $queryParams[3];

        // Function process
        // @todo constant management refactoring
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

        $html = $templating->render(
            'PolitizrFrontBundle:PaginatedList:_users.html.twig',
            array(
                'users' => $users,
                'offset' => intval($offset) + 10,
                'moreResults' => $moreResults,
                )
        );

        return array(
            'html' => $html,
            );
    }

    /**
     * Followed debates listing
     */
    public function followedDebateList()
    {
        $logger = $this->sc->get('logger');
        $logger->info('*** followedDebateList');
  
        // Retrieve used services
        $securityContext = $this->sc->get('security.context');
        $templating = $this->sc->get('templating');
        
        // Request arguments
        $queryParams = $this->getFiltersFromRequest();
        $order = $queryParams[0];
        $filters = $queryParams[1];
        $offset = $queryParams[2];

        // Function process
        $user = $securityContext->getToken()->getUser();

        // @todo constant management refactoring
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

        $html = $templating->render(
            'PolitizrFrontBundle:PaginatedList:_debates.html.twig',
            array(
                'debates' => $debates,
                'offset' => intval($offset) + 10,
                'moreResults' => $moreResults,
            )
        );

        return array(
            'html' => $html,
            );
    }

    /**
     * Followed users listing
     */
    public function followedUserList()
    {
        $logger = $this->sc->get('logger');
        $logger->info('*** followedUserList');
        
        // Retrieve used services
        $securityContext = $this->sc->get('security.context');
        $templating = $this->sc->get('templating');
        
        // Request arguments
        $queryParams = $this->getFiltersFromRequest();
        $order = $queryParams[0];
        $filters = $queryParams[1];
        $offset = $queryParams[2];

        // Function process
        $user = $securityContext->getToken()->getUser();

        // @todo constant management refactoring
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

        $html = $templating->render(
            'PolitizrFrontBundle:PaginatedList:_users.html.twig',
            array(
                'users' => $users,
                'order' => $order,
                'offset' => intval($offset) + 10,
                'moreResults' => $moreResults,
                )
        );

        return array(
            'html' => $html,
            );
    }
}
