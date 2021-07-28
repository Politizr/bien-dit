<?php

namespace Politizr\FrontBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Politizr\Constant\ListingConstants;
use Politizr\Constant\GlobalConstants;
use Politizr\Constant\LocalizationConstants;
use Politizr\Constant\CmsConstants;

use Politizr\Model\PDDirect;

use Politizr\Model\CmsContentQuery;
use Politizr\Model\PCircleQuery;
use Politizr\Model\PCTopicQuery;
use Politizr\Model\PDDebateQuery;
use Politizr\Model\PDReactionQuery;
use Politizr\Model\PUserQuery;
use Politizr\Model\PTagQuery;
use Politizr\Model\PQOrganizationQuery;
use Politizr\Model\PMCguQuery;
use Politizr\Model\PMCharteQuery;
use Politizr\Model\CmsContentAdminQuery;

use Politizr\FrontBundle\Form\Type\PDDirectType;

use Eko\FeedBundle\Field\Item\MediaItemField;

use StudioEchoBundles\StudioEchoMediaBundle\Lib\StudioEchoMediaManager;

/**
 * Public controller
 *
 * @author  Lionel Bouzonville
 */
class PublicController extends Controller
{
    /**
     * Homepage
     */
    public function homepageAction()
    {
        $logger = $this->get('logger');
        $logger->info('*** homepageAction');

        // redirect if connected
        // if ($profileSuffix = $this->get('politizr.tools.global')->computeProfileSuffix()) {
        //     return $this->redirect($this->generateUrl(sprintf('Homepage%s', $profileSuffix)));
        // }

        // Test global mode
        $globalMode = $this->getParameter('global_mode');
        if ($globalMode == 'oneshot') {
            $circle = $this->get('politizr.functional.circle')->getOneCircle();
            if (!$circle) {
                throw new NotFoundHttpException('No circle has been found');
            }
            return $this->redirect($this->generateUrl('CircleDetail', array(
                'slug' => $circle->getSlug(),
            )));
        }

        $content = CmsContentAdminQuery::create()->findPk(CmsConstants::CMS_CONTENT_ADMIN_HOMEPAGE);

        // Diaporamas associés
        if ($content) {
            $medias = StudioEchoMediaManager::getMediaList($content->getId(), 'Politizr\Model\CmsContentAdmin', 'fr', 1);
        }

        return $this->render('PolitizrFrontBundle:Public:homepage.html.twig', array(
            'homepage' => true,
            'content' => $content,
            'medias' => $medias,
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
     * Notre concept
     * code beta
     */
    public function conceptAction()
    {
        $logger = $this->get('logger');
        $logger->info('*** conceptAction');

        return $this->render('PolitizrFrontBundle:Public:concept.html.twig', array(
        ));
    }

    /**
     * CGU
     */
    public function cguAction()
    {
        $logger = $this->get('logger');
        $logger->info('*** cguAction');

        $legal = PMCguQuery::create()->findPk(GlobalConstants::GLOBAL_CGU_ID);

        return $this->render('PolitizrFrontBundle:Public:cgu.html.twig', array(
            'legal' => $legal,
        ));
    }

    /**
     * Policies
     */
    public function policiesAction()
    {
        $logger = $this->get('logger');
        $logger->info('*** policiesAction');

        $legal = PMCguQuery::create()->findPk(GlobalConstants::GLOBAL_POLICIES_ID);

        return $this->render('PolitizrFrontBundle:Public:cgu.html.twig', array(
            'legal' => $legal,
        ));
    }

    /**
     * Charte publique
     */
    public function charteAction()
    {
        $logger = $this->get('logger');
        $logger->info('*** charteAction');

        $charte = PMCharteQuery::create()->findPk(GlobalConstants::GLOBAL_CHARTE_ID);

        return $this->render('PolitizrFrontBundle:Public:charte.html.twig', array(
            'charte' => $charte,
        ));
    }

    /**
     * RSS feed
     */
    public function rssFeedAction()
    {
        $publications = $this->get('politizr.functional.document')->getPublicationsByFilters(
            null,
            null,
            null,
            null,
            ListingConstants::FILTER_KEYWORD_DEBATES_AND_REACTIONS,
            ListingConstants::FILTER_KEYWORD_ALL_USERS,
            ListingConstants::ORDER_BY_KEYWORD_LAST,
            ListingConstants::FILTER_KEYWORD_ALL_DATE,
            0,
            ListingConstants::LISTING_RSS
        );

        $feed = $this->get('eko_feed.feed.manager')->get('debates');
        $feed->addFromArray((array) $publications);
        $feed->addItemField(new MediaItemField('getFeedMediaItem'));

        return new Response($feed->render('rss')); // or 'atom'
    }

    /**
     * Generate robots.txt
     */
    public function robotsTxtAction()
    {
        // Render robots.txt
        $response = new Response();
        $response->headers->set('Content-Type', 'text/plain');
        $response->sendHeaders();
        
        return $this->render(
            'PolitizrFrontBundle:Navigation:robots.txt.twig',
            array(),
            $response
        );
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

        // pages cms
        $contents = CmsContentQuery::create()
            ->filterByOnline(true)
            ->orderByCreatedAt('desc')
            ->find();

        foreach ($contents as $content) {
            $url = $this->generateUrl('CmsContent', array(
                'slug' => $content->getSlug(),
                ));
            $urls[] = $this->generateUrlItem($url, 'weekly', '0.3');
        }

        // pages circles
        $circles = PCircleQuery::create()
            ->filterByOnline(true)
            ->filterByPublicCircle(true)
            ->orderByRank()
            ->find();

        foreach ($circles as $circle) {
            $url = $this->generateUrl('CircleDetail', array(
                'slug' => $circle->getSlug(),
                ));
            $urls[] = $this->generateUrlItem($url, 'weekly', '0.6');
        }

        $topics = PCTopicQuery::create()
            ->usePCircleQuery()
                ->filterByOnline(true)
                ->filterByPublicCircle(true)
            ->endUse()
            ->filterByOnline(true)
            ->orderByPCircleId()
            ->orderByRank()
            ->find();

        foreach ($topics as $topic) {
            $circle = $topic->getPCircle();
            if ($circle) {
                $url = $this->generateUrl('TopicDetail', array(
                    'circleSlug' => $circle->getSlug(),
                    'slug' => $topic->getSlug(),
                    ));
                $urls[] = $this->generateUrlItem($url, 'daily', '0.6');
            }
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
        // /!\ Users not indexed
        /**
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
        */

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
