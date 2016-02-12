<?php
namespace Politizr\FrontBundle\Lib\Xhr;

use Symfony\Component\HttpFoundation\Request;

use Politizr\Model\PUserQuery;

/**
 * XHR service for dashboard management.
 *
 * @author Lionel Bouzonville
 */
class XhrBubble
{
    private $securityTokenStorage;
    private $templating;
    private $logger;

    /**
     *
     * @param @security.token_storage
     * @param @templating
     * @param @logger
     */
    public function __construct(
        $securityTokenStorage,
        $templating,
        $logger
    ) {
        $this->securityTokenStorage = $securityTokenStorage;

        $this->templating = $templating;
        
        $this->logger = $logger;
    }

    /* ######################################################################################################## */
    /*                                         PRIVATE FUNCTIONS                                                */
    /* ######################################################################################################## */

    /* ######################################################################################################## */
    /*                                   DASHBOARD MAP LOADING                                                  */
    /* ######################################################################################################## */

    /**
     * Map navigation
     */
    public function user(Request $request)
    {
        $this->logger->info('*** user');
        
        // Request arguments
        $uuid = $request->get('uuid');
        $this->logger->info('$uuid = ' . print_r($uuid, true));

        // get user
        $user = PUserQuery::create()->filterByUuid($uuid)->findOne();

        $html = $this->templating->render(
            'PolitizrFrontBundle:User:_bubbleUserContent.html.twig',
            array(
                'user' => $user,
            )
        );

        return array(
            'html' => $html,
            );
    }
}
