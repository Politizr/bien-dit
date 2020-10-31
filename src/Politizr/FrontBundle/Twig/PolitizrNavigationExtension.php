<?php
namespace Politizr\FrontBundle\Twig;

use Politizr\Exception\InconsistentDataException;

use Politizr\Constant\LabelConstants;
use Politizr\Constant\CmsConstants;

use Politizr\Model\PDocumentInterface;
use Politizr\Model\PCircle;
use Politizr\Model\PCTopic;

use Politizr\Model\CmsContentQuery;
use Politizr\Model\CmsCategoryQuery;
use Politizr\Model\CmsInfoQuery;
use Politizr\Model\PCTopicQuery;

/**
 * App's navigation twig extension
 *
 * @author Lionel Bouzonville
 */
class PolitizrNavigationExtension extends \Twig_Extension
{
    private $securityTokenStorage;
    private $securityAuthorizationChecker;

    private $router;

    private $circleService;
    
    private $globalTools;

    private $logger;

    private $withGroup;

    private $topMenuCms;
    private $topMenuPublications;
    private $topMenuCommunity;

    /**
     * @param @security.token_storage
     * @param @security.authorization_checker
     * @param @router
     * @param @politizr.functional.circle
     * @param @politizr.tools.global
     * @param @logger
     * @param "%with_group%"
     * @param "%top_menu_cms%"
     * @param "%top_menu_publications%"
     * @param "%top_menu_community%"
     */
    public function __construct(
        $securityTokenStorage,
        $securityAuthorizationChecker,
        $router,
        $circleService,
        $globalTools,
        $logger,
        $withGroup,
        $topMenuCms,
        $topMenuPublications,
        $topMenuCommunity
    ) {
        $this->securityTokenStorage = $securityTokenStorage;
        $this->securityAuthorizationChecker =$securityAuthorizationChecker;

        $this->router = $router;

        $this->circleService = $circleService;

        $this->globalTools = $globalTools;
        
        $this->logger = $logger;
        
        $this->withGroup = $withGroup;

        $this->topMenuCms = $topMenuCms;
        $this->topMenuPublications = $topMenuPublications;
        $this->topMenuCommunity = $topMenuCommunity;
    }

    /* ######################################################################################################## */
    /*                                              FONCTIONS ET FILTRES                                        */
    /* ######################################################################################################## */

    /**
     * Filters list
     *
     * @return array
     */
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter(
                'newSubject',
                array($this, 'newSubject'),
                array('is_safe' => array('html'), 'needs_environment' => true)
            ),
        );
    }

    /**
     *  Renvoie la liste des fonctions
     */
    public function getFunctions()
    {
        return array(
            'topLeftMenu'  => new \Twig_SimpleFunction(
                'topLeftMenu',
                array($this, 'topLeftMenu'),
                array('is_safe' => array('html'), 'needs_environment' => true)
            ),
            'topGroupMenu'  => new \Twig_SimpleFunction(
                'topGroupMenu',
                array($this, 'topGroupMenu'),
                array('is_safe' => array('html'), 'needs_environment' => true)
            ),
            'footerGroupMenu'  => new \Twig_SimpleFunction(
                'footerGroupMenu',
                array($this, 'footerGroupMenu'),
                array('is_safe' => array('html'), 'needs_environment' => true)
            ),
            'sliderInfos'  => new \Twig_SimpleFunction(
                'sliderInfos',
                array($this, 'sliderInfos'),
                array('is_safe' => array('html'), 'needs_environment' => true)
            ),
        );
    }


    /* ######################################################################################################## */
    /*                                             FILTRES                                                      */
    /* ######################################################################################################## */

    /**
     * Compute & display contextualized link for "je m'exprime"
     * @todo refactoring manage circle w. 1 topic
     *
     * @param PDocumentInterface|PCTopic $subject
     * @return string
     */
    public function newSubject(\Twig_Environment $env, $subject)
    {
        $html = null;

        $display = true;
        $url = null;
        $label = "je m'exprime";

        if ($subject instanceof PDocumentInterface) {
            $topic = $subject->getPCTopic();
        } elseif ($subject instanceof PCircle) {
            $nbTopics = $subject->getNbTopics(true);
            if ($nbTopics == 1) {
                $topic = PCTopicQuery::create()
                    ->filterByPCircleId($subject->getId())
                    ->filterByOnline(true)
                    ->findOne();    
            } else {
                $topic = null;
                $display = false;
            }
        } elseif ($subject instanceof PCTopic) {
            $topic = $subject;
        } else {
            throw new InconsistentDataException('Class not managed');
        }

        if ($topic) {
            $circle = $topic->getPCircle();
            if ($circle->getReadOnly()) {
                $display = false;
            } else {
                $url = $this->router->generate('DebateDraftNew', array('topic' => $topic->getUuid()));

                $nbTopics = $circle->getNbTopics(true);
                if ($nbTopics == 1) {
                    $label = "je m'exprime sur \"".$circle->getTitle()."\"";
                } else {
                    $label = "je m'exprime sur \"".$topic->getTitle()."\"";
                }
            }
        } else {
            $url = $this->router->generate('DebateDraftNew');
        }

        $html = $env->render(
            'PolitizrFrontBundle:Navigation\\Menu:_topNewSubject.html.twig',
            array(
                'display' => $display,
                'url' => $url,
                'label' => $label,
            )
        );

        return $html;
    }


    /* ######################################################################################################## */
    /*                                             FONCTIONS                                                    */
    /* ######################################################################################################## */

    /**
     * Compute top left menu
     *
     * @return string
     */
    public function topLeftMenu(\Twig_Environment $env)
    {
        // $this->logger->info('*** topLeftMenu');

        // arrays of ['url' => '/myroute', 'label' => 'My Label']
        $cmsRoutes = array();
        $publicationsRoutes = array();
        $communityRoutes = array();

        // CMS
        if ($this->topMenuCms) {
            $cmsCategories = CmsCategoryQuery::create()->filterByOnline(true)->find();

            foreach ($cmsCategories as $cmsCategory) {
                $cmsContents = CmsContentQuery::create()
                                    ->filterByOnline(true)
                                    ->filterByCmsCategoryId($cmsCategory->getId())
                                    ->find();

                foreach ($cmsContents as $cmsContent) {
                    $url = $this->router->generate('CmsContent', array('slug' => $cmsContent->getSlug()));
                    $label = $cmsContent->getTitle();

                    $cmsRoutes[$cmsCategory->getTitle()][] = ['url' => $url, 'label' => $label];
                }
            }
        }
        // dump($cmsRoutes);

        // Contributions
        if ($this->topMenuPublications) {
            // get publications listing route
            $url = $this->router->generate('ListingSearchPublications', array('slug' => null));
            $label = LabelConstants::MENU_PUBLICATIONS_LISTING;

            $publicationsRoutes[] = ['url' => $url, 'label' => $label];
        }

        // Community
        if ($this->topMenuCommunity) {
            // get users listing route
            $url = $this->router->generate('ListingSearchUsers', array('slug' => null));
            $label = LabelConstants::MENU_USERS_LISTING;

            $communityRoutes[] = ['url' => $url, 'label' => $label];
        }

        $html = $env->render(
            'PolitizrFrontBundle:Navigation\\Menu:_topLeftMenu.html.twig',
            array(
                'cmsRoutes' => $cmsRoutes,
                'publicationsRoutes' => $publicationsRoutes,
                'communityRoutes' => $communityRoutes,
            )
        );

        return $html;
    }

    /**
     * Compute top group menu
     *
     * @return string
     */
    public function topGroupMenu(\Twig_Environment $env)
    {
        // $this->logger->info('*** topGroupMenu');

        // get current user
        $user = $this->securityTokenStorage->getToken()->getUser();
        if (is_string($user)) {
            $user = null;
        }

        $ownersCircles = array();
        $owners = $this->circleService->getAuthorizedOwnersByUser(null);
        foreach ($owners as $owner) {
            $circles = $this->circleService->getOwnerCirclesByUser($owner, $user);
            $ownersCircles[] = array($owner, $circles);
        }

        $html = $env->render(
            'PolitizrFrontBundle:Navigation\\Menu:_topGroupMenu.html.twig',
            array(
                'ownersCircles' => $ownersCircles,
            )
        );

        return $html;
    }

    /**
     * Compute footer group menu (public groups only)
     *
     * @return string
     */
    public function footerGroupMenu(\Twig_Environment $env)
    {
        // $this->logger->info('*** footerGroupMenu');

        // get current user
        $user = $this->securityTokenStorage->getToken()->getUser();
        if (is_string($user)) {
            $user = null;
        }

        $ownersCircles = array();
        $owners = $this->circleService->getAuthorizedOwnersByUser(null);
        foreach ($owners as $owner) {
            $circles = $this->circleService->getOwnerCirclesByUser($owner, $user);
            $ownersCircles[] = array($owner, $circles);
        }

        $html = $env->render(
            'PolitizrFrontBundle:Navigation\\Menu:_footerGroupMenu.html.twig',
            array(
                'ownersCircles' => $ownersCircles,
            )
        );

        return $html;
    }

    /**
     * Display cms infos slider
     *
     * @return string
     */
    public function sliderInfos(\Twig_Environment $env)
    {
        // $this->logger->info('*** sliderInfos');

        $infos = CmsInfoQuery::create()->filterByOnline(true)->orderByRank()->find();
        if (sizeof($infos) == 0) {
            return null;
        }

        $html = $env->render(
            'PolitizrFrontBundle:CmsInfo:_slider.html.twig',
            array(
                'infos' => $infos,
            )
        );

        return $html;
    }

    public function getName()
    {
        return 'p_e_navigation';
    }
}
