<?php
namespace Politizr\FrontBundle\Lib\Xhr;

use Symfony\Component\HttpFoundation\Request;

use Politizr\Exception\InconsistentDataException;
use Politizr\Exception\FormValidationException;

use Politizr\Constant\ListingConstants;

use Politizr\Model\PDDebateQuery;
use Politizr\Model\PDReactionQuery;
use Politizr\Model\PUserQuery;

/**
 * XHR service for modal management.
 *
 * @author Lionel Bouzonville
 */
class XhrModal
{
    private $securityTokenStorage;
    private $templating;
    private $logger;

    /**
     *
     * @param @security.token_storage
     * @param @templating
     * @param @logger
     */
    public function __construct(
        $securityTokenStorage,
        $templating,
        $logger
    ) {
        $this->securityTokenStorage = $securityTokenStorage;

        $this->templating = $templating;

        $this->logger = $logger;
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
    private function getFiltersFromRequest(Request $request)
    {
        $order = $request->get('order');
        $this->logger->info('$order = ' . print_r($order, true));
        $filtersDate = $request->get('filtersDate');
        $this->logger->info('$filtersDate = ' . print_r($filtersDate, true));
        $filtersUserType = $request->get('filtersUserType');
        $this->logger->info('$filtersUserType = ' . print_r($filtersUserType, true));
        $offset = $request->get('offset');
        $this->logger->info('$offset = ' . print_r($offset, true));
        $subjectId = $request->get('subjectId');
        $this->logger->info('$subjectId = ' . print_r($subjectId, true));

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
    public function modalPaginatedList(Request $request)
    {
        $this->logger->info('*** modalPaginatedList');
        
        // Request arguments
        $twigTemplate = $request->get('twigTemplate');
        $this->logger->info('$twigTemplate = ' . print_r($twigTemplate, true));
        $model = $request->get('model');
        $this->logger->info('$model = ' . print_r($model, true));
        $slug = $request->get('slug');
        $this->logger->info('$slug = ' . print_r($slug, true));

        // Function process
        $subject = null;
        if ($model && $slug) {
            $queryModel = 'Politizr\Model\\' . $model . 'Query';
            $subject = $queryModel::create()
                ->filterBySlug($slug)
                ->findOne();
        }

        $html = $this->templating->render(
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
    public function filters(Request $request)
    {
        $this->logger->info('*** filters');
        
        // Request arguments
        $type = $request->get('type');
        $this->logger->info('$type = ' . print_r($type, true));

        if ('debate' === $type) {
            $listOrder = $this->templating->render(
                'PolitizrFrontBundle:PaginatedList:_formOrderByDebate.html.twig'
            );
            $listFilter = $this->templating->render(
                'PolitizrFrontBundle:PaginatedList:_formFiltersByDebate.html.twig'
            );
        } elseif ('reaction' === $type) {
            $listOrder = $this->templating->render(
                'PolitizrFrontBundle:PaginatedList:_formOrderByReaction.html.twig'
            );
            $listFilter = $this->templating->render(
                'PolitizrFrontBundle:PaginatedList:_formFiltersByReaction.html.twig'
            );
        } elseif ('user' === $type) {
            $listOrder = $this->templating->render(
                'PolitizrFrontBundle:PaginatedList:_formOrderByUser.html.twig'
            );
            $listFilter = $this->templating->render(
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
    /*                                   SEARCH MODAL FUNCTIONS                                                 */
    /* ######################################################################################################## */

   /**
     * Loading init complete struct filters + list for search
     */
    public function loadSearchFiltersList(Request $request)
    {
        $this->logger->info('*** loadSearchFiltersList');
        
        // Filters form rendering (default for debate)
        $listOrder = $this->templating->render(
            'PolitizrFrontBundle:PaginatedList:_formOrderByDebate.html.twig'
        );
        $listFilter = $this->templating->render(
            'PolitizrFrontBundle:PaginatedList:_formFiltersByDebate.html.twig'
        );

        // Global header + filter rendering
        $searchFilters = $this->templating->render(
            'PolitizrFrontBundle:PaginatedList:_searchFilters.html.twig',
            array(
                'listOrder' => $listOrder,
                'listFilter' => $listFilter,
            )
        );

        $this->logger->info($searchFilters);

        return array(
            'searchFilters' => $searchFilters,
            );
    }

    /**
     * Load search form
     */
    public function loadSearchForm(Request $request)
    {
        $this->logger->info('*** loadSearchForm');
        
        $html = $this->templating->render(
            'PolitizrFrontBundle:Search:_search.html.twig'
        );

        return array(
            'html' => $html,
            );
    }


    /* ######################################################################################################## */
    /*                                                 SEARCH                                                   */
    /* ######################################################################################################## */

    /**
     * Debate search by tags listing
     */
    public function searchDebateList(Request $request)
    {
        $this->logger->info('*** searchDebateList');

        // Request arguments
        $queryParams = $this->getFiltersFromRequest($request);
        $order = $queryParams[0];
        $filters = $queryParams[1];
        $offset = $queryParams[2];

        // Function process

        // Get tags from search session
        $session = $request->getSession();
        $tags = $session->get('search/tag');
        $this->logger->info('session tags = '.print_r($tags, true));

        // at least one tag
        if (empty($tags)) {
            $error = 'Vous devez saisir au moins un tag';
            throw new FormValidationException($error);
        }

        // @todo http://dba.stackexchange.com/questions/45512/how-do-i-select-items-from-a-table-where-a-single-column-must-contain-two-or-mo
        $debates = PDDebateQuery::create()
                    ->distinct()
                    ->online()
                    ->filterByTags($tags)
                    ->filterByKeywords($filters)
                    ->orderWithKeyword($order)
                    ->limit(ListingConstants::MODAL_CLASSIC_PAGINATION)
                    ->offset($offset)
                    ->find();

        $moreResults = false;
        if (sizeof($debates) == ListingConstants::MODAL_CLASSIC_PAGINATION) {
            $moreResults = true;
        }

        if ($offset == 0 && count($debates) == 0) {
            $html = $this->templating->render(
                'PolitizrFrontBundle:PaginatedList:_noResult.html.twig',
                array(
                    'type' => ListingConstants::MODAL_TYPE_SEARCH,
                    'context' => ListingConstants::MODAL_DEBATES,
                )
            );
        } else {
            $html = $this->templating->render(
                'PolitizrFrontBundle:PaginatedList:_debates.html.twig',
                array(
                    'debates' => $debates,
                    'offset' => intval($offset) + ListingConstants::MODAL_CLASSIC_PAGINATION,
                    'moreResults' => $moreResults,
                    'paginateNextAction' => 'paginateSearchNext'
                )
            );
        }

        return array(
            'html' => $html,
            );
    }

    /**
     * User search by tags listing
     */
    public function searchUserList(Request $request)
    {
        $this->logger->info('*** searchUserList');
        
        // Request arguments
        $queryParams = $this->getFiltersFromRequest($request);
        $order = $queryParams[0];
        $filters = $queryParams[1];
        $offset = $queryParams[2];

        // Function process

        // Get tags from search session
        $session = $request->getSession();
        $tags = $session->get('search/tag');

        $users = PUserQuery::create()
                    ->distinct()
                    ->online()
                    ->filterByTags($tags)
                    ->filterByKeywords($filters)
                    ->orderWithKeyword($order)
                    ->limit(ListingConstants::MODAL_CLASSIC_PAGINATION)
                    ->offset($offset)
                    ->find();

        $moreResults = false;
        if (sizeof($users) == ListingConstants::MODAL_CLASSIC_PAGINATION) {
            $moreResults = true;
        }

        if ($offset == 0 && count($users) == 0) {
            $html = $this->templating->render(
                'PolitizrFrontBundle:PaginatedList:_noResult.html.twig',
                array(
                    'type' => ListingConstants::MODAL_TYPE_SEARCH,
                    'context' => ListingConstants::MODAL_USERS,
                )
            );
        } else {
            $html = $this->templating->render(
                'PolitizrFrontBundle:PaginatedList:_users.html.twig',
                array(
                    'users' => $users,
                    'offset' => intval($offset) + ListingConstants::MODAL_CLASSIC_PAGINATION,
                    'moreResults' => $moreResults,
                    'paginateNextAction' => 'paginateSearchNext'
                    )
            );
        }

        return array(
            'html' => $html,
            );
    }

    /* ######################################################################################################## */
    /*                                                 RANKING                                                  */
    /* ######################################################################################################## */

    /**
     * Debate ranking listing
     */
    public function rankingDebateList(Request $request)
    {
        $this->logger->info('*** rankingDebateList');

        // Request arguments
        $queryParams = $this->getFiltersFromRequest($request);
        $order = $queryParams[0];
        $filters = $queryParams[1];
        $offset = $queryParams[2];

        // Function process
        $debates = PDDebateQuery::create()
                    ->distinct()
                    ->online()
                    ->filterByKeywords($filters)
                    ->orderWithKeyword($order)
                    ->limit(ListingConstants::MODAL_CLASSIC_PAGINATION)
                    ->offset($offset)
                    ->find();

        $moreResults = false;
        if (sizeof($debates) == ListingConstants::MODAL_CLASSIC_PAGINATION) {
            $moreResults = true;
        }

        if ($offset == 0 && count($debates) == 0) {
            $html = $this->templating->render(
                'PolitizrFrontBundle:PaginatedList:_noResult.html.twig',
                array(
                    'type' => ListingConstants::MODAL_TYPE_RANKING,
                    'context' => ListingConstants::MODAL_DEBATES,
                )
            );
        } else {
            $html = $this->templating->render(
                'PolitizrFrontBundle:PaginatedList:_debates.html.twig',
                array(
                    'debates' => $debates,
                    'offset' => intval($offset) + ListingConstants::MODAL_CLASSIC_PAGINATION,
                    'moreResults' => $moreResults,
                    'paginateNextAction' => 'paginateNext'
                )
            );
        }

        return array(
            'html' => $html,
            );
    }

    /**
     * Reaction ranking listing
     */
    public function rankingReactionList(Request $request)
    {
        $this->logger->info('*** rankingReactionList');

        // Request arguments
        $queryParams = $this->getFiltersFromRequest($request);
        $order = $queryParams[0];
        $filters = $queryParams[1];
        $offset = $queryParams[2];

        // Function process
        $reactions = PDReactionQuery::create()
                    ->distinct()
                    ->online()
                    ->filterByKeywords($filters)
                    ->orderWithKeyword($order)
                    ->limit(ListingConstants::MODAL_CLASSIC_PAGINATION)
                    ->offset($offset)
                    ->find();

        $moreResults = false;
        if (sizeof($reactions) == ListingConstants::MODAL_CLASSIC_PAGINATION) {
            $moreResults = true;
        }

        if ($offset == 0 && count($reactions) == 0) {
            $html = $this->templating->render(
                'PolitizrFrontBundle:PaginatedList:_noResult.html.twig',
                array(
                    'type' => ListingConstants::MODAL_TYPE_RANKING,
                    'context' => ListingConstants::MODAL_REACTIONS,
                )
            );
        } else {
            $html = $this->templating->render(
                'PolitizrFrontBundle:PaginatedList:_reactions.html.twig',
                array(
                    'reactions' => $reactions,
                    'offset' => intval($offset) + ListingConstants::MODAL_CLASSIC_PAGINATION,
                    'moreResults' => $moreResults,
                    'paginateNextAction' => 'paginateNext'
                )
            );
        }

        return array(
            'html' => $html,
            );
    }

    /**
     * Ranking user listing
     */
    public function rankingUserList(Request $request)
    {
        $this->logger->info('*** rankingUserList');
        
        // Request arguments
        $queryParams = $this->getFiltersFromRequest($request);
        $order = $queryParams[0];
        $filters = $queryParams[1];
        $offset = $queryParams[2];

        // Function process
        $users = PUserQuery::create()
                    ->distinct()
                    ->online()
                    ->filterByKeywords($filters)
                    ->orderWithKeyword($order)
                    ->limit(ListingConstants::MODAL_CLASSIC_PAGINATION)
                    ->offset($offset)
                    ->find();

        $moreResults = false;
        if (sizeof($users) == ListingConstants::MODAL_CLASSIC_PAGINATION) {
            $moreResults = true;
        }

        if ($offset == 0 && count($users) == 0) {
            $html = $this->templating->render(
                'PolitizrFrontBundle:PaginatedList:_noResult.html.twig',
                array(
                    'type' => ListingConstants::MODAL_TYPE_RANKING,
                    'context' => ListingConstants::MODAL_USERS,
                )
            );
        } else {
            $html = $this->templating->render(
                'PolitizrFrontBundle:PaginatedList:_users.html.twig',
                array(
                    'users' => $users,
                    'offset' => intval($offset) + ListingConstants::MODAL_CLASSIC_PAGINATION,
                    'moreResults' => $moreResults,
                    'paginateNextAction' => 'paginateNext'
                    )
            );
        }

        return array(
            'html' => $html,
            );
    }

    /* ######################################################################################################## */
    /*                                                 SUGGESTION                                                  */
    /* ######################################################################################################## */

    /**
     * Suggestion debate listing
     */
    public function suggestionDebateList(Request $request)
    {
        $this->logger->info('*** suggestionDebateList');
        
        // Request arguments
        $offset = $request->get('offset');
        $this->logger->info('$offset = ' . print_r($offset, true));
        
        // Function process
        $user = $this->securityTokenStorage->getToken()->getUser();

        $debates = PDDebateQuery::create()->findBySuggestion($user->getId(), $offset, ListingConstants::MODAL_CLASSIC_PAGINATION);
        
        $moreResults = false;
        if (sizeof($debates) == ListingConstants::MODAL_CLASSIC_PAGINATION) {
            $moreResults = true;
        }

        if ($offset == 0 && count($debates) == 0) {
            $html = $this->templating->render(
                'PolitizrFrontBundle:PaginatedList:_noResult.html.twig',
                array(
                    'type' => ListingConstants::MODAL_TYPE_SUGGESTION,
                    'context' => ListingConstants::MODAL_DEBATES,
                )
            );
        } else {
            $html = $this->templating->render(
                'PolitizrFrontBundle:PaginatedList:_debates.html.twig',
                array(
                    'debates' => $debates,
                    'offset' => intval($offset) + ListingConstants::MODAL_CLASSIC_PAGINATION,
                    'moreResults' => $moreResults,
                    'paginateNextAction' => 'paginateNext'
                )
            );
        }

        return array(
            'html' => $html,
            );
    }

    /**
     * Suggestion user listing
     */
    public function suggestionUserList(Request $request)
    {
        $this->logger->info('*** suggestionUserList');
        
        // Request arguments
        $offset = $request->get('offset');
        $this->logger->info('$offset = ' . print_r($offset, true));
        
        // Function process
        $user = $this->securityTokenStorage->getToken()->getUser();

        $users = PUserQuery::create()->findBySuggestion($user->getId(), $offset, ListingConstants::MODAL_CLASSIC_PAGINATION);
        
        $moreResults = false;
        if (sizeof($users) == ListingConstants::MODAL_CLASSIC_PAGINATION) {
            $moreResults = true;
        }

        if ($offset == 0) {
            // hack for managing user null
            $nbUsers = 0;
            foreach ($users as $user) {
                if ($user && $user->getSlug()) {
                    $nbUsers++;
                    break;
                }
            }

            if ($nbUsers == 0) {
                $html = $this->templating->render(
                    'PolitizrFrontBundle:PaginatedList:_noResult.html.twig',
                    array(
                        'type' => ListingConstants::MODAL_TYPE_SUGGESTION,
                        'context' => ListingConstants::MODAL_USERS,
                    )
                );
            }
        } else {
            $html = $this->templating->render(
                'PolitizrFrontBundle:PaginatedList:_users.html.twig',
                array(
                    'users' => $users,
                    'offset' => intval($offset) + ListingConstants::MODAL_CLASSIC_PAGINATION,
                    'moreResults' => $moreResults,
                    'paginateNextAction' => 'paginateNext'
                    )
            );
        }

        return array(
            'html' => $html
            );
    }


    /**
     * Tag debate listing
     */
    public function tagDebateList(Request $request)
    {
        $this->logger->info('*** tagDebateList');
        
        // Request arguments
        $queryParams = $this->getFiltersFromRequest($request);
        $order = $queryParams[0];
        $filters = $queryParams[1];
        $offset = $queryParams[2];
        $subjectId = $queryParams[3];

        // Function process
        $debates = PDDebateQuery::create()
                    ->distinct()
                    ->online()
                    ->usePDDTaggedTQuery()
                        ->filterByPTagId($subjectId)
                    ->endUse()
                    ->filterByKeywords($filters)
                    ->orderWithKeyword($order)
                    ->limit(ListingConstants::MODAL_CLASSIC_PAGINATION)
                    ->offset($offset)
                    ->find();

        $moreResults = false;
        if (sizeof($debates) == ListingConstants::MODAL_CLASSIC_PAGINATION) {
            $moreResults = true;
        }

        if ($offset == 0 && count($debates) == 0) {
            $html = $this->templating->render(
                'PolitizrFrontBundle:PaginatedList:_noResult.html.twig',
                array(
                    'type' => ListingConstants::MODAL_TYPE_TAG,
                    'context' => ListingConstants::MODAL_DEBATES,
                )
            );
        } else {
            $html = $this->templating->render(
                'PolitizrFrontBundle:PaginatedList:_debates.html.twig',
                array(
                    'debates' => $debates,
                    'offset' => intval($offset) + ListingConstants::MODAL_CLASSIC_PAGINATION,
                    'moreResults' => $moreResults,
                    'paginateNextAction' => 'paginateNext'
                )
            );
        }

        return array(
            'html' => $html,
            );
    }

    /**
     * Tag user listing
     */
    public function tagUserList(Request $request)
    {
        $this->logger->info('*** tagUserList');
        
        // Request arguments
        $queryParams = $this->getFiltersFromRequest($request);
        $order = $queryParams[0];
        $filters = $queryParams[1];
        $offset = $queryParams[2];
        $subjectId = $queryParams[3];

        // Function process
        $users = PUserQuery::create()
                    ->distinct()
                    ->online()
                    ->usePuTaggedTPUserQuery()
                        ->filterByPTagId($subjectId)
                    ->endUse()
                    ->filterByKeywords($filters)
                    ->orderWithKeyword($order)
                    ->limit(ListingConstants::MODAL_CLASSIC_PAGINATION)
                    ->offset($offset)
                    ->find();

        $moreResults = false;
        if (sizeof($users) == ListingConstants::MODAL_CLASSIC_PAGINATION) {
            $moreResults = true;
        }

        if ($offset == 0 && count($users) == 0) {
            $html = $this->templating->render(
                'PolitizrFrontBundle:PaginatedList:_noResult.html.twig',
                array(
                    'type' => ListingConstants::MODAL_TYPE_TAG,
                    'context' => ListingConstants::MODAL_USERS,
                )
            );
        } else {
            $html = $this->templating->render(
                'PolitizrFrontBundle:PaginatedList:_users.html.twig',
                array(
                    'users' => $users,
                    'offset' => intval($offset) + ListingConstants::MODAL_CLASSIC_PAGINATION,
                    'moreResults' => $moreResults,
                    'paginateNextAction' => 'paginateNext'
                    )
            );
        }

        return array(
            'html' => $html,
            );
    }

    /**
     * Organization user listing
     */
    public function organizationUserList(Request $request)
    {
        $this->logger->info('*** organizationUserList');
        
        // Request arguments
        $queryParams = $this->getFiltersFromRequest($request);
        $order = $queryParams[0];
        $filters = $queryParams[1];
        $offset = $queryParams[2];
        $subjectId = $queryParams[3];

        // Function process
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
                    ->limit(ListingConstants::MODAL_CLASSIC_PAGINATION)
                    ->offset($offset)
                    ->find()
                    ;

        $moreResults = false;
        if (sizeof($users) == ListingConstants::MODAL_CLASSIC_PAGINATION) {
            $moreResults = true;
        }

        if ($offset == 0 && count($users) == 0) {
            $html = $this->templating->render(
                'PolitizrFrontBundle:PaginatedList:_noResult.html.twig',
                array(
                    'type' => ListingConstants::MODAL_TYPE_ORGANIZATION,
                    'context' => ListingConstants::MODAL_USERS,
                )
            );
        } else {
            $html = $this->templating->render(
                'PolitizrFrontBundle:PaginatedList:_users.html.twig',
                array(
                    'users' => $users,
                    'offset' => intval($offset) + ListingConstants::MODAL_CLASSIC_PAGINATION,
                    'moreResults' => $moreResults,
                    'paginateNextAction' => 'paginateNext'
                    )
            );
        }

        return array(
            'html' => $html,
            );
    }

    /**
     * Followed debates listing
     */
    public function followedDebateList(Request $request)
    {
        $this->logger->info('*** followedDebateList');
  
        // Request arguments
        $queryParams = $this->getFiltersFromRequest($request);
        $order = $queryParams[0];
        $filters = $queryParams[1];
        $offset = $queryParams[2];

        // Function process
        $user = $this->securityTokenStorage->getToken()->getUser();

        $debates = PDDebateQuery::create()
                    ->distinct()
                    ->online()
                    ->usePuFollowDdPDDebateQuery()
                        ->filterByPUserId($user->getId())
                    ->endUse()
                    ->filterByKeywords($filters)
                    ->orderWithKeyword($order)
                    ->limit(ListingConstants::MODAL_CLASSIC_PAGINATION)
                    ->offset($offset)
                    ->find();

        $moreResults = false;
        if (sizeof($debates) == ListingConstants::MODAL_CLASSIC_PAGINATION) {
            $moreResults = true;
        }

        if ($offset == 0 && count($debates) == 0) {
            $html = $this->templating->render(
                'PolitizrFrontBundle:PaginatedList:_noResult.html.twig',
                array(
                    'type' => ListingConstants::MODAL_TYPE_FOLLOWED,
                    'context' => ListingConstants::MODAL_DEBATES,
                )
            );
        } else {
            $html = $this->templating->render(
                'PolitizrFrontBundle:PaginatedList:_debates.html.twig',
                array(
                    'debates' => $debates,
                    'offset' => intval($offset) + ListingConstants::MODAL_CLASSIC_PAGINATION,
                    'moreResults' => $moreResults,
                    'paginateNextAction' => 'paginateNext'
                )
            );
        }

        return array(
            'html' => $html,
            );
    }

    /**
     * Followed users listing
     */
    public function followedUserList(Request $request)
    {
        $this->logger->info('*** followedUserList');
        
        // Request arguments
        $queryParams = $this->getFiltersFromRequest($request);
        $order = $queryParams[0];
        $filters = $queryParams[1];
        $offset = $queryParams[2];

        // Function process
        $user = $this->securityTokenStorage->getToken()->getUser();

        $query = PUserQuery::create()
                    ->distinct()
                    ->online()
                    ->filterByKeywords($filters)
                    ->orderWithKeyword($order)
                    ->limit(ListingConstants::MODAL_CLASSIC_PAGINATION)
                    ->offset($offset)
                    ;

        $users = $user->getSubscribers($query);

        $moreResults = false;
        if (sizeof($users) == ListingConstants::MODAL_CLASSIC_PAGINATION) {
            $moreResults = true;
        }

        if ($offset == 0 && count($users) == 0) {
            $html = $this->templating->render(
                'PolitizrFrontBundle:PaginatedList:_noResult.html.twig',
                array(
                    'type' => ListingConstants::MODAL_TYPE_FOLLOWED,
                    'context' => ListingConstants::MODAL_USERS,
                )
            );
        } else {
            $html = $this->templating->render(
                'PolitizrFrontBundle:PaginatedList:_users.html.twig',
                array(
                    'users' => $users,
                    'order' => $order,
                    'offset' => intval($offset) + ListingConstants::MODAL_CLASSIC_PAGINATION,
                    'moreResults' => $moreResults,
                    'paginateNextAction' => 'paginateNext'
                    )
            );
        }

        return array(
            'html' => $html,
            );
    }

    /**
     * Follower listing
     */
    public function followerList(Request $request)
    {
        $this->logger->info('*** followerList');
        
        // Request arguments
        $queryParams = $this->getFiltersFromRequest($request);
        $order = $queryParams[0];
        $filters = $queryParams[1];
        $offset = $queryParams[2];
        $subjectId = $queryParams[3];

        // Function process
        $user = PUserQuery::create()->findPk($subjectId);

        $query = PUserQuery::create()
                    ->distinct()
                    ->online()
                    ->filterByKeywords($filters)
                    ->orderWithKeyword($order)
                    ->limit(ListingConstants::MODAL_CLASSIC_PAGINATION)
                    ->offset($offset)
                    ;

        $users = $user->getFollowers($query);

        $moreResults = false;
        if (sizeof($users) == ListingConstants::MODAL_CLASSIC_PAGINATION) {
            $moreResults = true;
        }

        if ($offset == 0 && count($users) == 0) {
            $html = $this->templating->render(
                'PolitizrFrontBundle:PaginatedList:_noResult.html.twig',
                array(
                    'type' => ListingConstants::MODAL_TYPE_FOLLOWER,
                    'context' => ListingConstants::MODAL_USERS,
                )
            );
        } else {
            $html = $this->templating->render(
                'PolitizrFrontBundle:PaginatedList:_users.html.twig',
                array(
                    'users' => $users,
                    'order' => $order,
                    'offset' => intval($offset) + ListingConstants::MODAL_CLASSIC_PAGINATION,
                    'moreResults' => $moreResults,
                    'paginateNextAction' => 'paginateNext'
                    )
            );
        }

        return array(
            'html' => $html,
            );
    }
}
