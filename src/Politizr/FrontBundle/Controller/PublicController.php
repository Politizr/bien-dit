<?php

namespace Politizr\FrontBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Politizr\Model\PDDebateQuery;
use Politizr\Model\PDReactionQuery;
use Politizr\Model\PUserQuery;
use Politizr\Model\PTagQuery;
use Politizr\Model\PQOrganizationQuery;

/**
 * Public controller
 *
 * @author  Lionel Bouzonville
 */
class PublicController extends Controller
{
    /**
     * Homepage
     * code beta
     */
    public function homepageAction()
    {
        $logger = $this->get('logger');
        $logger->info('*** homepageAction');

        // redirect if connected
        if ($profileSuffix = $this->get('politizr.tools.global')->computeProfileSuffix()) {
            return $this->redirect($this->generateUrl(sprintf('Homepage%s', $profileSuffix)));
        }

        return $this->render('PolitizrFrontBundle:Public:homepage.html.twig', array(
            'homepage' => true,
        ));
    }

    /**
     * Inscription temp
     * code beta
     */
    public function inscriptionAction($type)
    {
        $logger = $this->get('logger');
        $logger->info('*** inscriptionAction');

        if ($type == 'citoyen') {
            $template = "inscriptionTempCitizen.html.twig";
        } elseif ($type == 'elu') {
            $template = "inscriptionTempElected.html.twig";
        } else {
            $template = "inscriptionTemp.html.twig";
        }

        return $this->render('PolitizrFrontBundle:Public:'.$template, array(
        ));
    }

    /**
     * Inscription temp finish
     * code beta
     */
    public function inscriptionTempFinishAction()
    {
        $logger = $this->get('logger');
        $logger->info('*** inscriptionTempFinishAction');

        return $this->render('PolitizrFrontBundle:Public:inscriptionTempFinish.html.twig', array(
        ));
    }

    /**
     * Qui sommes nous
     * code beta
     */
    public function whoWeAreAction()
    {
        $logger = $this->get('logger');
        $logger->info('*** whoWeAreAction');

        return $this->render('PolitizrFrontBundle:Public:whoWeAre.html.twig', array(
        ));
    }

    /**
     * A propos
     */
    public function aboutAction()
    {
        $logger = $this->get('logger');
        $logger->info('*** aboutAction');

        return $this->render('PolitizrFrontBundle:Public:about.html.twig', array(
        ));
    }

    /**
     * Generate sitemap
     */
    public function sitemapXmlAction()
    {
        $urls = [];

        // homepage
        $url = $this->generateUrl('Homepage');
        $urls[] = $this->generateUrlItem($url, 'weekly', '0.3');

        // top
        $url = $this->generateUrl('ListingByRecommend');
        $urls[] = $this->generateUrlItem($url, 'weekly', '0.8');

        // listing thématiques
        $contents = PTagQuery::create()
            ->filterByOnline(true)
            ->joinPDDTaggedT(null, 'left join')
            ->distinct()
            ->orderById('desc')
            ->find();

        foreach ($contents as $content) {
            $url = $this->generateUrl('ListingByTag', array(
                'slug' => $content->getSlug(),
                ));
            $urls[] = $this->generateUrlItem($url, 'weekly', '0.5');
        }

        // listing par organisations
        $contents = PQOrganizationQuery::create()
            ->filterByOnline(true)
            ->orderByRank()
            ->find();

        foreach ($contents as $content) {
            $url = $this->generateUrl('ListingByOrganization', array(
                'slug' => $content->getSlug(),
                ));
            $urls[] = $this->generateUrlItem($url, 'weekly', '0.5');
        }

        // pages debats
        $contents = PDDebateQuery::create()
            ->filterByOnline(true)
            ->filterByPublished(true)
            ->orderByPublishedAt('desc')
            ->find();

        foreach ($contents as $content) {
            $url = $this->generateUrl('DebateDetail', array(
                'slug' => $content->getSlug(),
                ));
            $urls[] = $this->generateUrlItem($url, 'weekly', '0.7');
        }

        // pages réactions
        $contents = PDReactionQuery::create()
            ->filterByOnline(true)
            ->filterByPublished(true)
            ->orderByPublishedAt('desc')
            ->find();

        foreach ($contents as $content) {
            $url = $this->generateUrl('ReactionDetail', array(
                'slug' => $content->getSlug(),
                ));
            $urls[] = $this->generateUrlItem($url, 'weekly', '0.7');
        }

        // pages users
        $contents = PUserQuery::create()
            ->filterByOnline(true)
            ->orderByCreatedAt('desc')
            ->find();

        foreach ($contents as $content) {
            $url = $this->generateUrl('UserDetail', array(
                'slug' => $content->getSlug(),
                ));
            $urls[] = $this->generateUrlItem($url, 'weekly', '0.3');
        }

        // Render XML Sitemap
        $response = new Response();
        $response->headers->set('Content-Type', 'xml');
        
        return $this->render(
            'PolitizrFrontBundle:Navigation:sitemap.xml.twig',
            array(
                'urls' => $urls
            ),
            $response
        );
    }

    /**
     * Generate the url item
     * @return array
     */
    private function generateUrlItem($url, $changefreq = 'monthly', $priority = '0.3', $subdomain = false)
    {
        if ($subdomain) {
            $loc = $this->getRequest()->getScheme() . ':' . $url;
        } else {
            $loc = $this->getRequest()->getScheme() . '://' . $this->getRequest()->getHost() . $url;
        }
        return array(
            'loc'        => $loc,
            'changefreq' => $changefreq,
            'priority'   => $priority
        );
    }
}
