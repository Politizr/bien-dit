<?php
namespace Politizr\FrontBundle\Lib\Xhr;

use Symfony\Component\HttpFoundation\Request;

use Politizr\Model\PUserQuery;
use Politizr\Model\PTagQuery;

/**
 * XHR service for bubble management.
 * beta
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

    /**
     * User's bubble
     */
    public function user(Request $request)
    {
        // $this->logger->info('*** user');
        
        // Request arguments
        $uuid = $request->get('uuid');
        // $this->logger->info('$uuid = ' . print_r($uuid, true));

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

    /**
     * Tag's bubble
     */
    public function tag(Request $request)
    {
        // $this->logger->info('*** tag');
        
        // Request arguments
        $uuid = $request->get('uuid');
        // $this->logger->info('$uuid = ' . print_r($uuid, true));

        // get tag
        $tag = PTagQuery::create()->filterByUuid($uuid)->findOne();

        $html = $this->templating->render(
            'PolitizrFrontBundle:Tag:_bubbleTagContent.html.twig',
            array(
                'tag' => $tag,
            )
        );

        return array(
            'html' => $html,
            );
    }
}
