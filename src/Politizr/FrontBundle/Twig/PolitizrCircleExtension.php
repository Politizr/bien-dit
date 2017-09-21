<?php
namespace Politizr\FrontBundle\Twig;

use Politizr\Exception\InconsistentDataException;

use Politizr\Model\PCircle;

use Politizr\Model\PUInPCQuery;

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
        );
    }


    /* ######################################################################################################## */
    /*                                             FILTRES                                                      */
    /* ######################################################################################################## */

    /**
     * Compute right interact links between user and circle: join or consult/quit
     *
     * @param PUReputation $reputation
     * @return html
     */
    public function circleActions(\Twig_Environment $env, PCircle $circle)
    {
        // $this->logger->info('*** circleActions');
        // $this->logger->info('$reputation = '.print_r($reputation, true));

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


    /* ######################################################################################################## */
    /*                                             FONCTIONS                                                    */
    /* ######################################################################################################## */


    public function getName()
    {
        return 'p_e_reputation';
    }
}
