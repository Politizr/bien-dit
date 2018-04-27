<?php

namespace Politizr\FrontBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Politizr\Constant\ListingConstants;
use Politizr\Constant\GlobalConstants;
use Politizr\Constant\LocalizationConstants;

use Politizr\Model\PDDirect;

use Politizr\Model\PDDebateQuery;
use Politizr\Model\PDReactionQuery;
use Politizr\Model\PUserQuery;
use Politizr\Model\PTagQuery;
use Politizr\Model\PQOrganizationQuery;
use Politizr\Model\PMCguQuery;
use Politizr\Model\PMCharteQuery;

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
            ->limit(6)
            ->online()
            ->orderByMostViews()
            ;
        $usersQuery = PUserQuery::create()
            ->limit(6)
            ->online()
            ->orderByMostActive()
            ;

        /*
            $users = $usersQuery
                ->usePuTaggedTPUserQuery()
                    ->usePuTaggedTPTagQuery()
                        ->filterBySlug('democratie-participative')
                    ->endUse()
                ->endUse()
                ->find();
        */

        $directMessage = new PDDirect();
        $form = $this->createForm(new PDDirectType(), $directMessage);

        if ($theme == 'civic-tech') {
            $template = 'civictech.html.twig';
        } elseif ($theme == 'elus-locaux')  {
            $template = 'eluLocal.html.twig';
        } elseif ($theme == 'dialogue-citoyen')  {
            $template = 'dialogueCitoyen.html.twig';
        } elseif ($theme == 'democratie-locale')  {
            $template = 'democratieLocale.html.twig';
        } elseif ($theme == 'democratie-participative')  {
            $template = 'democratieParticipative.html.twig';
        } elseif ($theme == 'reseau-social-politique')  {
            $template = 'reseauSocial.html.twig';
        } elseif ($theme == 'primaires-presidentielle-2017')  {
            $template = 'presidentielle.html.twig';
        } elseif ($theme == 'charlotte-marchandise')  {
            $template = 'charlotte.html.twig';
        } elseif ($theme == 'concertation-publique')  {
            $template = 'concertationPublique.html.twig';
        } elseif ($theme == 'budget-participatif')  {
            $template = 'budgetParticipatif.html.twig';
        } elseif ($theme == 'entreprise-liberee')  {
            $template = 'entrepriseLiberee.html.twig';
        } elseif ($theme == 'dialogue-entreprise-public')  {
            $template = 'dialogueEntreprisePublic.html.twig';
        } elseif ($theme == 'offre-candidat-legislatives-2017')  {
            $template = 'offreCandidat.html.twig';
        } elseif ($theme == 'offre-candidat-senatoriales-2017')  {
            $template = 'offreCandidatSenatoriales.html.twig';
        } elseif ($theme == 'offres-collectivites')  {
            $template = 'offresCollectivites.html.twig';
        } elseif ($theme == 'boite-a-idees-numerique')  {
            $template = 'boiteAIdees.html.twig';
        } elseif ($theme == 'actus-ariege')  {
            $documents = $this->get('politizr.functional.document')->getPublicationsByFilters(
                null,
                '0ee7c5fc-cd8a-4089-92d4-f1caf8751c0d',
                LocalizationConstants::TYPE_DEPARTMENT,
                null,
                ListingConstants::FILTER_KEYWORD_DEBATES_AND_REACTIONS,
                ListingConstants::FILTER_KEYWORD_ALL_USERS,
                ListingConstants::ORDER_BY_KEYWORD_LAST,
                ListingConstants::FILTER_KEYWORD_ALL_DATE,
                0,
                6
            );

            $users = $this->get('politizr.functional.user')->getUsersByFilters(
                '0ee7c5fc-cd8a-4089-92d4-f1caf8751c0d',
                LocalizationConstants::TYPE_DEPARTMENT,
                ListingConstants::FILTER_KEYWORD_ALL_USERS,
                ListingConstants::ORDER_BY_KEYWORD_MOST_ACTIVE,
                ListingConstants::FILTER_KEYWORD_ALL_DATE,
                $offset = 0,
                6
            );

            $template = 'actusAriege.html.twig';
        } elseif ($theme == 'actus-toulouse')  {
            $documents = $this->get('politizr.functional.document')->getPublicationsByFilters(
                null,
                '06813a50-839a-48b7-98d6-c9f3606894ce',
                LocalizationConstants::TYPE_DEPARTMENT,
                null,
                ListingConstants::FILTER_KEYWORD_DEBATES_AND_REACTIONS,
                ListingConstants::FILTER_KEYWORD_ALL_USERS,
                ListingConstants::ORDER_BY_KEYWORD_LAST,
                ListingConstants::FILTER_KEYWORD_ALL_DATE,
                0,
                6
            );

            $users = $this->get('politizr.functional.user')->getUsersByFilters(
                '06813a50-839a-48b7-98d6-c9f3606894ce',
                LocalizationConstants::TYPE_DEPARTMENT,
                ListingConstants::FILTER_KEYWORD_ALL_USERS,
                ListingConstants::ORDER_BY_KEYWORD_MOST_ACTIVE,
                ListingConstants::FILTER_KEYWORD_ALL_DATE,
                $offset = 0,
                6
            );

            $template = 'actusToulouse.html.twig';
        } elseif ($theme == 'actus-paris')  {
            $documents = $this->get('politizr.functional.document')->getPublicationsByFilters(
                null,
                'e423798f-7ed5-4c08-82d1-ddc41a5e0b91',
                LocalizationConstants::TYPE_REGION,
                null,
                ListingConstants::FILTER_KEYWORD_DEBATES_AND_REACTIONS,
                ListingConstants::FILTER_KEYWORD_ALL_USERS,
                ListingConstants::ORDER_BY_KEYWORD_LAST,
                ListingConstants::FILTER_KEYWORD_ALL_DATE,
                0,
                6
            );

            $users = $this->get('politizr.functional.user')->getUsersByFilters(
                'e423798f-7ed5-4c08-82d1-ddc41a5e0b91',
                LocalizationConstants::TYPE_REGION,
                ListingConstants::FILTER_KEYWORD_ALL_USERS,
                ListingConstants::ORDER_BY_KEYWORD_MOST_FOLLOWED,
                ListingConstants::FILTER_KEYWORD_ALL_DATE,
                $offset = 0,
                6
            );

            $template = 'actusParis.html.twig';
        } elseif ($theme == 'actus-bordeaux')  {
            $documents = $this->get('politizr.functional.document')->getPublicationsByFilters(
                null,
                '836f7d34-f836-44e9-89b2-6e6932d735c3',
                LocalizationConstants::TYPE_REGION,
                null,
                ListingConstants::FILTER_KEYWORD_DEBATES_AND_REACTIONS,
                ListingConstants::FILTER_KEYWORD_ALL_USERS,
                ListingConstants::ORDER_BY_KEYWORD_LAST,
                ListingConstants::FILTER_KEYWORD_ALL_DATE,
                0,
                6
            );

            $users = $this->get('politizr.functional.user')->getUsersByFilters(
                '836f7d34-f836-44e9-89b2-6e6932d735c3',
                LocalizationConstants::TYPE_REGION,
                ListingConstants::FILTER_KEYWORD_ALL_USERS,
                ListingConstants::ORDER_BY_KEYWORD_MOST_ACTIVE,
                ListingConstants::FILTER_KEYWORD_ALL_DATE,
                $offset = 0,
                6
            );

            $template = 'actusBordeaux.html.twig';
        } elseif ($theme == 'actus-bretagne')  {
            $documents = $this->get('politizr.functional.document')->getPublicationsByFilters(
                null,
                '8c9e3199-9cef-462e-bc48-0850b91fdf1d',
                LocalizationConstants::TYPE_REGION,
                null,
                ListingConstants::FILTER_KEYWORD_DEBATES_AND_REACTIONS,
                ListingConstants::FILTER_KEYWORD_CITIZEN,
                ListingConstants::ORDER_BY_KEYWORD_LAST,
                ListingConstants::FILTER_KEYWORD_ALL_DATE,
                0,
                6
            );

            $users = $this->get('politizr.functional.user')->getUsersByFilters(
                '8c9e3199-9cef-462e-bc48-0850b91fdf1d',
                LocalizationConstants::TYPE_REGION,
                ListingConstants::FILTER_KEYWORD_ALL_USERS,
                ListingConstants::ORDER_BY_KEYWORD_MOST_ACTIVE,
                ListingConstants::FILTER_KEYWORD_ALL_DATE,
                $offset = 0,
                6
            );

            $template = 'actusBretagne.html.twig';
        } elseif ($theme == 'actus-normandie')  {
            $documents = $this->get('politizr.functional.document')->getPublicationsByFilters(
                null,
                '6703c4ab-c59c-4de4-9945-4ba0c8e41f68',
                LocalizationConstants::TYPE_REGION,
                null,
                ListingConstants::FILTER_KEYWORD_DEBATES_AND_REACTIONS,
                ListingConstants::FILTER_KEYWORD_ALL_USERS,
                ListingConstants::ORDER_BY_KEYWORD_LAST,
                ListingConstants::FILTER_KEYWORD_ALL_DATE,
                0,
                6
            );

            $users = $this->get('politizr.functional.user')->getUsersByFilters(
                '6703c4ab-c59c-4de4-9945-4ba0c8e41f68',
                LocalizationConstants::TYPE_REGION,
                ListingConstants::FILTER_KEYWORD_ALL_USERS,
                ListingConstants::ORDER_BY_KEYWORD_MOST_ACTIVE,
                ListingConstants::FILTER_KEYWORD_ALL_DATE,
                $offset = 0,
                6
            );

            $template = 'actusNormandie.html.twig';
        } elseif ($theme == 'actus-hauts-de-france')  {
            $users = $this->get('politizr.functional.user')->getUsersByFilters(
                'fb8cd4a1-ae02-45ab-90a2-25aaa7494abb',
                LocalizationConstants::TYPE_REGION,
                ListingConstants::FILTER_KEYWORD_ALL_USERS,
                ListingConstants::ORDER_BY_KEYWORD_MOST_ACTIVE,
                ListingConstants::FILTER_KEYWORD_ALL_DATE,
                $offset = 0,
                6
            );

            $template = 'actusHautsDeFrance.html.twig';
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
     * CGU
     */
    public function cguAction()
    {
        $logger = $this->get('logger');
        $logger->info('*** cguAction');

        $legal = PMCguQuery::create()->filterByOnline(true)->orderByCreatedAt('desc')->findOne();

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
