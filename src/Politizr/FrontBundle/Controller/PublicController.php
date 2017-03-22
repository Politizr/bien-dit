<?php

namespace Politizr\FrontBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Politizr\Constant\ListingConstants;

use Politizr\Model\PDDirect;

use Politizr\Model\PDDebateQuery;
use Politizr\Model\PDReactionQuery;
use Politizr\Model\PUserQuery;
use Politizr\Model\PTagQuery;
use Politizr\Model\PQOrganizationQuery;

use Politizr\FrontBundle\Form\Type\PDDirectType;

use Eko\FeedBundle\Field\Item\MediaItemField;

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

        // 9 debates / reactions
        $documents = $this->get('politizr.functional.document')->getHomepagePublicationsListing(ListingConstants::LISTING_HOMEPAGE_DOCUMENTS_LIMIT);

        // 6 users
        $users = $this->get('politizr.functional.user')->getHomepagePublicationsListing(ListingConstants::LISTING_HOMEPAGE_USERS_LIMIT);

        return $this->render('PolitizrFrontBundle:Public:homepage.html.twig', array(
            'homepage' => true,
            'documents' => $documents,
            'users' => $users,
        ));
    }

    /**
     * Landing Page
     */
    public function landingPageAction($theme)
    {
        $logger = $this->get('logger');
        $logger->info('*** landingPageAction');

        $documents = null;
        $users = null;
        $form = null;

        $documentsQuery = PDDebateQuery::create()
            ->limit(9)
            ->online()
            ->orderByMostViews()
            ;
        $usersQuery = PUserQuery::create()
            ->limit(6)
            ->online()
            ->orderByMostActive()
            ;

        if ($theme == 'civic-tech') {
            $documents = $documentsQuery
                ->usePDDTaggedTQuery()
                    ->usePTagQuery()
                        ->filterBySlug('civic-tech')
                    ->endUse()
                ->endUse()
                ->find();
            $users = $usersQuery
                ->usePuTaggedTPUserQuery()
                    ->usePuTaggedTPTagQuery()
                        ->filterBySlug('civic-tech')
                    ->endUse()
                ->endUse()
                ->find();
            $template = 'civictech.html.twig';
        } elseif ($theme == 'elu-local')  {
            $documents = $documentsQuery
                ->usePDDTaggedTQuery()
                    ->usePTagQuery()
                        ->filterBySlug('elus-locaux')
                        ->_or()
                        ->filterBySlug('statut-de-l-elu')
                    ->endUse()
                ->endUse()
                ->find();
            $users = $usersQuery
                ->usePuTaggedTPUserQuery()
                    ->usePuTaggedTPTagQuery()
                        ->filterBySlug('elus-locaux')
                        ->_or()
                        ->filterBySlug('statut-de-l-elu')
                    ->endUse()
                ->endUse()
                ->find();
            $template = 'eluLocal.html.twig';
        } elseif ($theme == 'dialogue-citoyen')  {
            $documents = $documentsQuery
                ->usePDDTaggedTQuery()
                    ->usePTagQuery()
                        ->filterBySlug('citoyen')
                        ->_or()
                        ->filterBySlug('citoyenneté')
                    ->endUse()
                ->endUse()
                ->find();
            $users = $usersQuery
                ->usePuTaggedTPUserQuery()
                    ->usePuTaggedTPTagQuery()
                        ->filterBySlug('citoyen')
                        ->_or()
                        ->filterBySlug('citoyenneté')
                    ->endUse()
                ->endUse()
                ->find();
            $template = 'dialogueCitoyen.html.twig';
        } elseif ($theme == 'democratie-locale')  {
            $documents = $documentsQuery
                ->usePDDTaggedTQuery()
                    ->usePTagQuery()
                        ->filterBySlug('democratie-locale')
                    ->endUse()
                ->endUse()
                ->find();
            
            $template = 'democratieLocale.html.twig';
        } elseif ($theme == 'democratie-participative')  {
            $documents = $documentsQuery
                ->usePDDTaggedTQuery()
                    ->usePTagQuery()
                        ->filterBySlug('democratie-participative')
                    ->endUse()
                ->endUse()
                ->find();
            $users = $usersQuery
                ->usePuTaggedTPUserQuery()
                    ->usePuTaggedTPTagQuery()
                        ->filterBySlug('democratie-participative')
                    ->endUse()
                ->endUse()
                ->find();
            $template = 'democratieParticipative.html.twig';
        } elseif ($theme == 'reseau-social-politique')  {
            $documents = $documentsQuery
                ->usePDDTaggedTQuery()
                    ->usePTagQuery()
                        ->filterBySlug('%democratie%')
                    ->endUse()
                ->endUse()
                ->find();
            $users = $usersQuery
                ->usePuTaggedTPUserQuery()
                    ->usePuTaggedTPTagQuery()
                        ->filterBySlug('%democratie%')
                    ->endUse()
                ->endUse()
                ->find();
            $template = 'reseauSocial.html.twig';
        } elseif ($theme == 'primaires-presidentielle-2017')  {
            $documents = $documentsQuery
                ->usePDDTaggedTQuery()
                    ->usePTagQuery()
                        ->filterBySlug('presidentielle-2017')
                    ->endUse()
                ->endUse()
                ->find();
            $users = $usersQuery
                ->usePuTaggedTPUserQuery()
                    ->usePuTaggedTPTagQuery()
                        ->filterBySlug('presidentielle-2017')
                    ->endUse()
                ->endUse()
                ->find();
            $template = 'presidentielle.html.twig';
        } elseif ($theme == 'charlotte-marchandise')  {
            $directMessage = new PDDirect();
            $form = $this->createForm(new PDDirectType(), $directMessage);

            $documents = PDDebateQuery::create()
                ->limit(9)
                ->online()
                ->orderByPublishedAt('desc')
                ->usePDDTaggedTQuery()
                    ->usePTagQuery()
                        ->filterBySlug('presidentielle-2017')
                    ->endUse()
                ->endUse()
                // ->orderByMostViews()
                ->filterByPUserId(315)
                ->find();
            $template = 'charlotte.html.twig';
        } else {
            return $this->redirect($this->generateUrl('Homepage'));
        }

        return $this->render('PolitizrFrontBundle:Public\LandingPage:'.$template, array(
            'documents' => $documents,
            'users' => $users,
            'form' => $form?$form->createView():null,
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
     * RSS feed
     */
    public function rssFeedAction()
    {
        $publications = $this->get('politizr.functional.document')->getPublicationsByFilters(
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

        // landing pages
        $keywords = [ 'civic-tech', 'elu-local', 'dialogue-citoyen', 'democratie-locale', 'democratie-participative', 'reseau-social-politique', 'primaires-presidentielle-2017', 'charlotte-marchandise-franquet'];
        foreach ($keywords as $keyword) {
            $url = $this->generateUrl('LandingPage', array(
                'theme' => $keyword
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
