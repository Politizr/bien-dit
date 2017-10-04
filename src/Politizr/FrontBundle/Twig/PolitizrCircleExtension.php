<?php
namespace Politizr\FrontBundle\Twig;

use Politizr\Exception\InconsistentDataException;

use Politizr\Model\PCircle;
use Politizr\Model\PCTopic;

use Politizr\Model\PUserQuery;
use Politizr\Model\PUInPCQuery;

use Politizr\Constant\CircleConstants;
    
/**
 * User's circle twig extension
 *
 * @author Lionel Bouzonville
 */
class PolitizrCircleExtension extends \Twig_Extension
{
    private $securityTokenStorage;
    private $securityAuthorizationChecker;

    private $router;

    private $circleService;
    
    private $globalTools;

    private $logger;

    /**
     * @security.token_storage
     * @security.authorization_checker
     * @router
     * @politizr.functional.circle
     * @politizr.tools.global
     * @logger
     */
    public function __construct(
        $securityTokenStorage,
        $securityAuthorizationChecker,
        $router,
        $circleService,
        $globalTools,
        $logger
    ) {
        $this->securityTokenStorage = $securityTokenStorage;
        $this->securityAuthorizationChecker =$securityAuthorizationChecker;

        $this->router = $router;

        $this->circleService = $circleService;

        $this->globalTools = $globalTools;
        
        $this->logger = $logger;
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
                'circleActions',
                array($this, 'circleActions'),
                array('is_safe' => array('html'), 'needs_environment' => true)
            ),
            new \Twig_SimpleFilter(
                'circleFooter',
                array($this, 'circleFooter'),
                array('is_safe' => array('html'), 'needs_environment' => true)
            ),
            new \Twig_SimpleFilter(
                'topicStats',
                array($this, 'topicStats'),
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
            'authorizedReactionUsersForCd09'  => new \Twig_SimpleFunction(
                'authorizedReactionUsersForCd09',
                array($this, 'authorizedReactionUsersForCd09'),
                array('is_safe' => array('html'), 'needs_environment' => true)
            ),
        );
    }


    /* ######################################################################################################## */
    /*                                             FILTRES                                                      */
    /* ######################################################################################################## */

    /**
     * Compute right interact links between user and circle: join or consult/quit
     *
     * @param PCircle $circle
     * @return html
     */
    public function circleActions(\Twig_Environment $env, PCircle $circle)
    {
        // $this->logger->info('*** circleActions');
        // $this->logger->info('$circle = '.print_r($circle, true));

        // get current user
        $user = $this->securityTokenStorage->getToken()->getUser();
        if (is_string($user)) {
            $user = null;
            return null;
        }

        $isMember = $this->circleService->isUserMemberOfCircle($circle, $user);

        // Construction du rendu du tag
        $html = $env->render(
            'PolitizrFrontBundle:Circle:_menuActions.html.twig',
            array(
                'isMember' => $isMember,
                'circle' => $circle
            )
        );

        return $html;
    }

    /**
     * Manage circle footer
     *
     * @param PCircle $circle
     * @return html
     */
    public function circleFooter(\Twig_Environment $env, PCircle $circle)
    {
        // $this->logger->info('*** circleFooter');
        // $this->logger->info('$circle = '.print_r($circle, true));

        // get template path > generic or dedicated
        $templatePath = 'Circle';
        if ($circle->getId() == CircleConstants::CD09_ID_CIRCLE) {
            $templatePath = 'Circle\\cd09';
        }

        // Construction du rendu du tag
        $html = $env->render(
            'PolitizrFrontBundle:'.$templatePath.':_footer.html.twig',
            array(
                'circle' => $circle
            )
        );

        return $html;
    }

    /**
     * Compute topic stats
     *
     * @param PCTopic $topic
     * @return html
     */
    public function topicStats(\Twig_Environment $env, PCTopic $topic)
    {
        // $this->logger->info('*** topicStats');
        // $this->logger->info('$topic = '.print_r($topic, true));

        $nbDebates = $this->circleService->countDebatesByTopic($topic);
        $nbReactions = $this->circleService->countReactionsByTopic($topic);
        $nbComments = $this->circleService->countCommentsByTopic($topic);

        // Construction du rendu du tag
        $html = $env->render(
            'PolitizrFrontBundle:Topic:_topicStats.html.twig',
            array(
                'circle' => $topic->getPCircle(),
                'topic' => $topic,
                'nbDebates' => $nbDebates,
                'nbReactions' => $nbReactions,
                'nbComments' => $nbComments,
            )
        );

        return $html;
    }

    /* ######################################################################################################## */
    /*                                             FONCTIONS                                                    */
    /* ######################################################################################################## */

    /**
     * Display a customized authorized reaction users for CD09 
     *
     * @param PUser $user
     * @return string
     */
    public function authorizedReactionUsersForCd09(\Twig_Environment $env)
    {
        // $this->logger->info('*** authorizedReactionUsersForCd09');

        $mainUser = PUserQuery::create()->findPk(CircleConstants::CD09_ID_USER_PRESIDENT);
        $users = PUserQuery::create()->filterById(CircleConstants::CD09_IDS_USER_RESPONSES)->find();
        
        $html = $env->render(
            'PolitizrFrontBundle:Circle\\cd09:_authorizedReactionUsers.html.twig',
            array(
                'mainUser' => $mainUser,
                'users' => $users,
            )
        );

        return $html;
    }


    public function getName()
    {
        return 'p_e_circle';
    }
}
