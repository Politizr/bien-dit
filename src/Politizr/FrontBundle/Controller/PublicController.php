<?php

namespace Politizr\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\PropelAdapter;

use Politizr\Model\PDDebateQuery;
use Politizr\Model\PUserQuery;
use Politizr\Model\PDCommentQuery;
use Politizr\Model\PTagQuery;

use Politizr\Model\PUser;
use Politizr\Model\PUType;

/**
 *  Gestion du routing tout public
 *
 *  TODO:
 *      - gestion redirection / connexion + droits + activ
 *
 * @author  Lionel Bouzonville
 */
class PublicController extends Controller
{

    /* ######################################################################################################## */
    /*                                                  ROUTING CLASSIQUE                                       */
    /* ######################################################################################################## */


    /**
     *  Accueil
     */
    public function homepageAction()
    {
        $logger = $this->get('logger');
        $logger->info('*** homepageAction');

        // *********************************** //
        //      Récupération objets vue
        // *********************************** //

        // débats les plus populaires
        $debates = PDDebateQuery::create()->online()->popularity(5)->find();

        // profils les plus populaires
        $users = PUserQuery::create()->filterByPUTypeId(PUType::TYPE_QUALIFIE)->online()->popularity(5)->find();

        // commentaires les plus populaires
        $comments = PDCommentQuery::create()->online()->last(10)->find();

        // débats locaux / adresse IP
        // $request = $this->get('request');
        // $result = $this->container
        //     ->get('bazinga_geocoder.geocoder')
        //     ->using('free_geo_ip')
        //     ->geocode($request->server->get('REMOTE_ADDR'));
        // $logger->info('$result = '.print_r($result, true));
 
        // $homeGeoDebates = PDDebateQuery::create()->geolocalized($result, 10)->find();

        // *********************************** //
        //      Affichage de la vue
        // *********************************** //

        return $this->render('PolitizrFrontBundle:Public:homepage.html.twig', array(
                'debates' => $debates,
                'users' => $users,
                'comments' => $comments,
                // 'homeGeoDebates' => $homeGeoDebates
            ));
    }

    /**
     *  Présentation
     */
    public function presentationAction()
    {
        $logger = $this->get('logger');
        $logger->info('*** presentationAction');

        return $this->render('PolitizrFrontBundle:Public:presentation.html.twig', array(
            ));
    }

    /* ######################################################################################################## */
    /*                                                  FONCTIONS AJAX                                          */
    /* ######################################################################################################## */

    /**
     *  Renseigne un tableau contenant les tags
     */
    public function getTagsAction(Request $request) {
        $logger = $this->get('logger');
        $logger->info('*** getTagsAction');

        $jsonResponse = $this->get('politizr.routing.ajax')->createJsonHtmlResponse(
            'politizr.service.tag',
            'getTags'
        );

        return $jsonResponse;
    }

    /* ######################################################################################################## */
    /*                                                  FONCTIONS PRIVEES                                       */
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