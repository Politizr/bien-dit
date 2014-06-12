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
    
    /* ######################################################################################################## */
    /*                                                  FONCTIONS AJAX                                          */
    /* ######################################################################################################## */


    /**
     *      Yellow Carpet Top
     *      Clic centrage d'une autre photo
     */
    public function yellowCarpetTopCenterPhotoAction(Request $request) {
        $logger = $this->get('logger');
        $logger->info('*** yellowCarpetTopCenterPhotoAction');
        
        try {
            if ($request->isXmlHttpRequest()) {
                // Récupération args
                $wuserId = $request->get('wuserId');
                $logger->info('$wuserId = ' . print_r($wuserId, true));
                $gap = $request->get('gap');
                $logger->info('$gap = ' . print_r($gap, true));
                $sortedWuserIds = json_decode($request->get('sortedWuserIds'));
                $logger->info('$sortedWuserIds = ' . print_r($sortedWuserIds, true));

                // Construction d'un tableau correspondant à l'ordre actuel
                $wusers = array();
                foreach ($sortedWuserIds as $wuserSortedId) {
                    $wusers[] = WUserQuery::create()->findPk($wuserSortedId);
                }

                // Réordonnancement
                $wusers = $this->centerPhoto($wusers, $gap);

                // Construction de la structure
                $templating = $this->get('templating');
                $newGalery = $templating->render(
                                    'WonderbraJDDFrontBundle:'.$this->getLayoutDir().':yellowCarpetTopGalery.html.twig', array('wusers' => $wusers)
                            );

                // Construction de la réponse
                $jsonResponse = array (
                    'success' => true,
                    'box_carousel' => $newGalery
                );
            } else {
                throw $this->createNotFoundException('Not a XHR request');
            }
        } catch (NotFoundHttpException $e) {
            $logger->info('Exception = ' . print_r($e->getMessage(), true));
            $jsonResponse = array('error' => $e->getMessage());
        } catch (\Exception $e) {
            $logger->info('Exception = ' . print_r($e->getMessage(), true));
            $jsonResponse = array('error' => $e->getMessage());
        }

        // JSON formatted success/error message
        $response = new Response(json_encode($jsonResponse));
        return $response;
    }

}