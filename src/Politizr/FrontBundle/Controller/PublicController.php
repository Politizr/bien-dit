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
use Politizr\Model\PDDCommentQuery;

use Politizr\Model\PUser;

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
        $homeDebates = PDDebateQuery::create()->online()->popularity(10)->find();

        // profils les plus populaires
        $homeUsers = PUserQuery::create()->online()->popularity(10)->find();

        // commentaires les plus populaires
        $homeComments = PDDCommentQuery::create()->online()->last(10)->find();

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
                'homeDebates' => $homeDebates,
                'homeUsers' => $homeUsers,
                'homeComments' => $homeComments,
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

    /**
     *  Profil en cours de validation
     */
    public function eluActivationProcessAction()
    {
        $logger = $this->get('logger');
        $logger->info('*** eluActivationProcessAction');

        return $this->render('PolitizrFrontBundle:Public:eluActivationProcess.html.twig', array(
            ));
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