<?php
namespace Politizr\FrontBundle\Lib\Service;

use Symfony\Component\EventDispatcher\GenericEvent;

use StudioEcho\Lib\StudioEchoUtils;

use Politizr\Exception\InconsistentDataException;
use Politizr\Exception\FormValidationException;

use Pagerfanta\Exception\NotIntegerCurrentPageException;
use Pagerfanta\Exception\LessThan1CurrentPageException;
use Pagerfanta\Exception\OutOfRangeCurrentPageException;

use Politizr\FrontBundle\Lib\SimpleImage;

/**
 * Services métiers associés à la recherche. 
 *
 * @author Lionel Bouzonville
 */
class SearchManager
{
    private $sc;

    /**
     *
     */
    public function __construct($serviceContainer) {
        $this->sc = $serviceContainer;
    }

    /* ######################################################################################################## */
    /*                            					REQUÊTE DE RECHERCHE                                  		*/
    /* ######################################################################################################## */

    /**
     *  Exécution de la requête de recherche paginée
     *
     */
    public function pagerQuery($query) {
        $logger = $this->sc->get('logger');
        $logger->info('*** pagerQuery');

        if (!$query) {
        	throw new InconsistentDataException('Requête vide.');
        } else {
            // $finder = $this->container->get('fos_elastica.finder.politizr.p_document');
            $finder = $this->sc->get('fos_elastica.finder.politizr');

            // $query = new \Elastica\Query\Bool();
            // $fieldQuery = new \Elastica\Query\Text();
            // $fieldQuery->setFieldQuery('name', $querystring);
            // $query->addShould($fieldQuery);
            // 
            // $elasticaQuery = new \Elastica\Query();
            // $elasticaQuery->setQuery($query);
            // 
            // $elasticaResultSet = $finder->search($elasticaQuery);
            // $fruits = $elasticaResultSet->getResults();


            // $matchQuery = new \Elastica\Query\Match();
            // $matchQuery->setField('title', $query);
            // 
            // $searchQuery = new \Elastica\Query();
            // $searchQuery->setQuery($matchQuery);
            // 
            // $searchQuery->setHighlight(array(
            //     // 'pre_tags' => array('<p>'),
            //     // 'post_tags' => array('<p>'),
            //     'fields' => array('title' => new \stdClass())
            // ));

            // https://gist.github.com/tchapi/1ac99f757e0f336c1e1b
            $boolQuery = new \Elastica\Query\Bool();
            $queryString = new \Elastica\Query\QueryString();
            $queryString->setDefaultField('_all');
            $queryString->setQuery($query);
            $boolQuery->addMust($queryString);
            
            $querySearch = new \Elastica\Query($boolQuery);
            $querySearch->setHighlight(array(
                "fields" => array("*" => new \stdClass)
            ));

            $pager = $finder->findPaginated($querySearch);

        	return $pager;
        }

    }



    /* ######################################################################################################## */
    /*                            					FONCTIONS AJAX                               				*/
    /* ######################################################################################################## */

    /**
     *  Recherche
     *
     */
    public function search() {
        $logger = $this->sc->get('logger');
        $logger->info('*** search');

        $request = $this->sc->get('request');

        // Récupération args
        $query = $request->get('query');
        $logger->info('query = '.print_r($query, true));
        $page = $request->get('next-page');
        $logger->info('page = '.print_r($page, true));

        // Exécution de la requête paginée
        $pager = $this->pagerQuery($query);

        // TODO > gestion plus fine des exceptions
        try {
            $pager->setMaxPerPage(10)->setCurrentPage($page);
        } catch (NotIntegerCurrentPageException $e) {
            throw new InconsistentDataException($e->getMessage());
        } catch (Pagerfanta\Exception\LessThan1CurrentPageException $e) {
            throw new InconsistentDataException($e->getMessage());
        } catch (Pagerfanta\Exception\OutOfRangeCurrentPageException $e) {
            throw new InconsistentDataException($e->getMessage());
        }

        // Construction rendu
        $templating = $this->sc->get('templating');
        $html = $templating->render(
                            'PolitizrFrontBundle:Navigation:searchResultPage.html.twig', array(
				                    'query' => $query,
				                    'pager' => $pager,
                                )
                    );

        return array(
            'html' => $html,
            );
    }

}