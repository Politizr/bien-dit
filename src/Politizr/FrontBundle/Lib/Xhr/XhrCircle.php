<?php
namespace Politizr\FrontBundle\Lib\Xhr;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\EventDispatcher\GenericEvent;

use StudioEcho\Lib\StudioEchoUtils;

use Politizr\Exception\InconsistentDataException;
use Politizr\Exception\BoxErrorException;

use Politizr\Constant\XhrConstants;
use Politizr\Constant\ListingConstants;

use Politizr\Model\PCircleQuery;

/**
 * XHR service for document management.
 * beta
 *
 * @author Lionel Bouzonville
 */
class XhrCircle
{
    private $securityTokenStorage;
    private $securityAuthorizationChecker;
    private $kernel;
    private $session;
    private $eventDispatcher;
    private $templating;
    private $formFactory;
    private $router;
    private $circleService;
    private $documentService;
    private $globalTools;
    private $logger;

    /**
     *
     * @security.token_storage
     * @security.authorization_checker
     * @kernel
     * @session
     * @event_dispatcher
     * @templating
     * @form.factory
     * @router
     * @politizr.functional.circle
     * @politizr.functional.document
     * @politizr.manager.circle
     * @politizr.tools.global
     * @logger
     */
    public function __construct(
        $securityTokenStorage,
        $securityAuthorizationChecker,
        $kernel,
        $session,
        $eventDispatcher,
        $templating,
        $formFactory,
        $router,
        $circleService,
        $documentService,
        $circleManager,
        $globalTools,
        $logger
    ) {
        $this->securityTokenStorage = $securityTokenStorage;
        $this->securityAuthorizationChecker = $securityAuthorizationChecker;

        $this->kernel = $kernel;
        $this->session = $session;

        $this->eventDispatcher = $eventDispatcher;

        $this->templating = $templating;
        $this->formFactory = $formFactory;
        $this->router = $router;

        $this->circleService = $circleService;
        $this->documentService = $documentService;

        $this->circleManager = $circleManager;

        $this->globalTools = $globalTools;

        $this->logger = $logger;
    }

    /* ######################################################################################################## */
    /*                                       SUBSCRIPTION                                                       */
    /* ######################################################################################################## */


    /**
     * Filtered publications by topic
     * code beta
     */
    public function subscribeCircle(Request $request)
    {
        // $this->logger->info('*** publicationsByFilters');
        
        // Request arguments
        $uuid = $request->get('uuid');
        // $this->logger->info('$uuid = ' . print_r($uuid, true));
        $way = $request->get('way');
        // $this->logger->info('$way = ' . print_r($way, true));

        // get current user
        $user = $this->securityTokenStorage->getToken()->getUser();

        // get circle
        $circle = PCircleQuery::create()->filterByUuid($uuid)->findOne();

        // update membership
        if ($way == 'subscribe') {
            $this->circleManager->createUserInCircle($user->getId(), $circle->getId());
            $redirectUrl = $this->router->generate('DetailCircle', array('slug' => $circle->getSlug()));
            $eventName = 'c_subscribe';
        } elseif ($way == 'unsubscribe') {
            $this->circleManager->deleteUserInCircle($user->getId(), $circle->getId());
            $redirectUrl = null;
            $eventName = 'c_unsubscribe';
        } else {
            throw new InconsistentDataException(sprintf('Subscribe\'s way %s not managed', $way));
        }

        // get membership
        $circle = PCircleQuery::create()->filterByUuid($uuid)->findOne();     
        $isMember = $this->circleService->isUserMemberOfCircle($user->getId(), $circle->getId());

        $html = $this->templating->render(
            'PolitizrFrontBundle:Circle:_menuActions.html.twig',
            array(
                'isMember' => $isMember,
                'circle' => $circle,
            )
        );

        // events
        $event = new GenericEvent($circle, array('p_user' => $user,));
        $dispatcher = $this->eventDispatcher->dispatch($eventName, $event);

        return array(
            'html' => $html,
            'redirectUrl' => $redirectUrl,
        );
    }

    /* ######################################################################################################## */
    /*                                          LISTING                                                         */
    /* ######################################################################################################## */

    /**
     * Filtered publications by topic
     * code beta
     */
    public function publicationsByTopic(Request $request)
    {
        // $this->logger->info('*** publicationsByFilters');
        
        // Request arguments
        $offset = $request->get('offset');
        // $this->logger->info('$offset = ' . print_r($offset, true));
        $topicUuid = $request->get('topicUuid');
        // $this->logger->info('$topicUuid = ' . print_r($topicUuid, true));
        $filterPublication = $request->get('filterPublication');
        // $this->logger->info('$filterPublication = ' . print_r($filterPublication, true));
        $filterProfile = $request->get('filterProfile');
        // $this->logger->info('$filterProfile = ' . print_r($filterProfile, true));
        $filterActivity = $request->get('filterActivity');
        // $this->logger->info('$filterActivity = ' . print_r($filterActivity, true));
        $filterDate = $request->get('filterDate');
        // $this->logger->info('$filterDate = ' . print_r($filterDate, true));

        if (!$topicUuid) {
            throw new InconsistentDataException(sprintf('Topic %s not found', $topicUuid));
        }
        if (empty($filterPublication)) {
            $filterPublication = ListingConstants::FILTER_KEYWORD_ALL_PUBLICATIONS;
        }
        if (empty($filterProfile)) {
            $filterProfile = ListingConstants::FILTER_KEYWORD_ALL_USERS;
        }
        if (empty($filterActivity)) {
            $filterActivity = ListingConstants::ORDER_BY_KEYWORD_LAST;
        }
        if (empty($filterDate)) {
            $filterDate = ListingConstants::FILTER_KEYWORD_ALL_DATE;
        }

        $publications = $this->documentService->getPublicationsByFilters(
            null,
            null,
            $topicUuid,
            $filterPublication,
            $filterProfile,
            $filterActivity,
            $filterDate,
            $offset,
            ListingConstants::LISTING_CLASSIC_PAGINATION
        );

        // @todo create function for code above
        $moreResults = false;
        if (sizeof($publications) == ListingConstants::LISTING_CLASSIC_PAGINATION) {
            $moreResults = true;
        }

        if ($offset == 0 && count($publications) == 0) {
            $html = $this->templating->render(
                'PolitizrFrontBundle:PaginatedList:_noResult.html.twig'
            );
        } else {
            $html = $this->templating->render(
                'PolitizrFrontBundle:PaginatedList:_publications.html.twig',
                array(
                    'publications' => $publications,
                    'offset' => intval($offset) + ListingConstants::LISTING_CLASSIC_PAGINATION,
                    'moreResults' => $moreResults,
                    'jsFunctionKey' => XhrConstants::JS_KEY_LISTING_DOCUMENTS_BY_TOPIC
                )
            );
        }

        return array(
            'html' => $html
        );
    }
}