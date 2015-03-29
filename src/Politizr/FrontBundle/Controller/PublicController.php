<?php

namespace Politizr\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Politizr\Model\PDDebateQuery;
use Politizr\Model\PUserQuery;
use Politizr\Model\PDCommentQuery;

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
    /**
     *  Accueil
     */
    public function homepageAction()
    {
        $logger = $this->get('logger');
        $logger->info('*** homepageAction');

        // derniers débats publiés
        $debates = PDDebateQuery::create()->online()->orderByLast()->setLimit(5)->find();

        // profils les plus populaires
        $users = PUserQuery::create()->filterByQualified(true)->online()->orderByMostFollowed()->setLimit(5)->find();
        
        // commentaires les plus populaires
        $comments = PDCommentQuery::create()->online()->last()->setLimit(5)->find();

        // débats locaux / adresse IP
        // $request = $this->get('request');
        // $result = $this->container
        //     ->get('bazinga_geocoder.geocoder')
        //     ->using('free_geo_ip')
        //     ->geocode($request->server->get('REMOTE_ADDR'));
        // $logger->info('$result = '.print_r($result, true));
 
        // $homeGeoDebates = PDDebateQuery::create()->geolocalized($result, 10)->find();

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
}
