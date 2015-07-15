<?php
namespace Politizr\FrontBundle\Lib\Xhr;

use Symfony\Component\EventDispatcher\GenericEvent;

use StudioEcho\Lib\StudioEchoUtils;

use Politizr\Exception\InconsistentDataException;
use Politizr\Exception\FormValidationException;

use Pagerfanta\Exception\NotIntegerCurrentPageException;
use Pagerfanta\Exception\LessThan1CurrentPageException;
use Pagerfanta\Exception\OutOfRangeCurrentPageException;

use Politizr\FrontBundle\Lib\SimpleImage;

/**
 * XHR service for search management.
 *
 * @author Lionel Bouzonville
 */
class XhrSearch
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
     * Search request w. pagination
     */
    private function pagerQuery($query)
    {
        $logger = $this->sc->get('logger');
        $logger->info('*** pagerQuery');

        // Retrieve used services
        $finder = $this->sc->get('fos_elastica.finder.politizr');

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

            $pager = $finder->findPaginated($querySearch);

            return $pager;
        }

    }

    /* ######################################################################################################## */
    /*                                                  SEARCH                                                  */
    /* ######################################################################################################## */

    /**
     * Search
     */
    public function search()
    {
        $logger = $this->sc->get('logger');
        $logger->info('*** search');

        // Retrieve used services
        $request = $this->sc->get('request');
        $templating = $this->sc->get('templating');

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

        $html = $templating->render(
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
}
