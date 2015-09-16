<?php

namespace Politizr\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Politizr\FrontBundle\Form\Type\SearchType;

/**
 * Search controller
 *
 * @author Lionel Bouzonville
 */
class SearchController extends Controller
{
    /**
     *  Init Recherche
     */
    public function searchInitAction(Request $request)
    {
        $logger = $this->get('logger');
        $logger->info('*** searchInitAction');

        $query = $request->query->get('recherche')['query'];
        $logger->info('query = '.print_r($query, true));

        $form = $this->createForm(
            new SearchType($query)
        );

        return $this->render('PolitizrFrontBundle:Navigation:searchForm.html.twig', array(
                    'form' => $form->createView(),
            ));
    }

    /**
     *  Recherche
     */
    public function searchAction(Request $request)
    {
        $logger = $this->get('logger');
        $logger->info('*** searchAction');

        $form = $this->createForm(
            new SearchType()
        );

        $query = $request->query->get('recherche')['query'];
        $pager = $this->get('politizr.xhr.search')->pagerQuery($query);
        
        return $this->render('PolitizrFrontBundle:Navigation:searchResult.html.twig', array(
                    'query' => $query,
                    'pager' => $pager,
            ));

    }
}
