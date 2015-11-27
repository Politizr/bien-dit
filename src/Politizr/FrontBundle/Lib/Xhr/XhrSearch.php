<?php
namespace Politizr\FrontBundle\Lib\Xhr;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\EventDispatcher\GenericEvent;

use StudioEcho\Lib\StudioEchoUtils;

use Politizr\Exception\InconsistentDataException;
use Politizr\Exception\FormValidationException;

use Pagerfanta\Exception\NotIntegerCurrentPageException;
use Pagerfanta\Exception\LessThan1CurrentPageException;
use Pagerfanta\Exception\OutOfRangeCurrentPageException;

use Politizr\Model\PTagQuery;

use Politizr\FrontBundle\Lib\SimpleImage;

/**
 * @todo service by service constructor injection
 *
 * @author Lionel Bouzonville
 */
class XhrSearch
{
    private $fosElasticaFinder; // @todo
    private $templating;
    private $logger;

    /**
     * @param @fos_elastica.finder.politizr
     * @param @templating
     * @param @logger
     */
    public function __construct(
        $templating,
        $logger
    ) {
        $this->templating = $templating;

        $this->logger = $logger;
    }

    /* ######################################################################################################## */
    /*                                         PRIVATE FUNCTIONS                                                */
    /* ######################################################################################################## */

    /**
     * Search request w. pagination
     */
    private function pagerQuery($query)
    {
        $this->logger->info('*** pagerQuery');

        // Retrieve used services
        if (!$query) {
            throw new InconsistentDataException('Empty search request');
        } else {
            // https://gist.github.com/tchapi/1ac99f757e0f336c1e1b
            $boolQuery = new \Elastica\Query\Bool();
            $queryString = new \Elastica\Query\QueryString();
            $queryString->setDefaultField('_all');
            $queryString->setQuery($query);
            $boolQuery->addMust($queryString);
            
            $querySearch = new \Elastica\Query($boolQuery);
            $querySearch->setHighlight(array(
                'fields' => array("*" => new \stdClass),
                'pre_tags' => array('<b>'),
                'post_tags' => array('</b>'),
            ));

            $pager = $this->fosElasticaFinder->findPaginated($querySearch);

            return $pager;
        }

    }

    /* ######################################################################################################## */
    /*                                               ES SEARCH                                                  */
    /* ######################################################################################################## */

    /**
     * Fulltext search w. Elastic Search
     */
    public function elasticSearch(Request $request)
    {
        $this->logger->info('*** elasticSearch');

        // Request arguments
        $query = $request->get('query');
        $logger->info('query = '.print_r($query, true));
        $page = $request->get('page');
        $logger->info('page = '.print_r($page, true));

        // Function process
        $pager = $this->pagerQuery($query);

        // @todo refactor exception management
        try {
            $pager->setMaxPerPage(10)->setCurrentPage($page);
        } catch (NotIntegerCurrentPageException $e) {
            throw new InconsistentDataException($e->getMessage());
        } catch (Pagerfanta\Exception\LessThan1CurrentPageException $e) {
            throw new InconsistentDataException($e->getMessage());
        } catch (Pagerfanta\Exception\OutOfRangeCurrentPageException $e) {
            throw new InconsistentDataException($e->getMessage());
        }

        $html = $this->templating->render(
            'PolitizrFrontBundle:Navigation:searchResultPage.html.twig',
            array(
                    'query' => $query,
                    'pager' => $pager,
                )
        );

        return array(
            'html' => $html,
            );
    }

    /* ######################################################################################################## */
    /*                                             TAGS SEARCH                                                  */
    /* ######################################################################################################## */

    /**
     * Add tag to search
     */
    public function addSearchTag(Request $request)
    {
        // Request arguments
        $tagUuid = $request->get('tagUuid');
        $this->logger->info('$tagUuid = ' . print_r($tagUuid, true));

        $tag = PTagQuery::create()->filterByUuid($tagUuid)->findOne();
        if (!$tag) {
            throw new InconsistentDataException(sprintf('Tag id-%s does not exist', $tag->getId()));
        }

        // Put tag in search session
        $session = $request->getSession();
        $session->set('search/tag/'.$tagUuid, $tagUuid);

        $xhrPathDelete = $this->templating->render(
            'PolitizrFrontBundle:Navigation\\Xhr:_xhrPath.html.twig',
            array(
                'xhrRoute' => 'ROUTE_SEARCH_TAG_DELETE',
                'xhrService' => 'search',
                'xhrMethod' => 'deleteSearchTag',
                'xhrType' => 'RETURN_BOOLEAN',
            )
        );

        $htmlTag = $this->templating->render(
            'PolitizrFrontBundle:Tag:_searchEditable.html.twig',
            array(
                'tag' => $tag,
                'path' => $xhrPathDelete
            )
        );

        // Renvoi de l'ensemble des blocs HTML maj
        return array(
            'htmlTag' => $htmlTag
            );
    }

    /**
     * Delete tag from search
     */
    public function deleteSearchTag(Request $request)
    {
        $this->logger->info('*** deleteSearchTag');
        
        // Request arguments
        $tagUuid = $request->get('tagUuid');
        $this->logger->info('$tagUuid = ' . print_r($tagUuid, true));

        // Remove tag from search session
        $session = $request->getSession();
        $session->remove('search/tag/'.$tagUuid);

        return true;
    }


    /**
     * Clear tags session
     */
    public function clearSession(Request $request)
    {
        $this->logger->info('*** clearSession');
        
        // Remove all tags from search session
        $session = $request->getSession();
        $session->remove('search/tag');

        return true;
    }
}
