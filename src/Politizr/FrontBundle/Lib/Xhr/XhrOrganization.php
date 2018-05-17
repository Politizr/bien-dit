<?php
namespace Politizr\FrontBundle\Lib\Xhr;

use Symfony\Component\HttpFoundation\Request;

use Politizr\FrontBundle\Lib\Tools\StaticTools;

use Politizr\Exception\InconsistentDataException;
use Politizr\Exception\BoxErrorException;

use Politizr\Model\PQOrganization;

use Politizr\Model\PQOrganizationQuery;

/**
 * XHR service for organization management.
 * beta
 *
 * @author Lionel Bouzonville
 */
class XhrOrganization
{
    private $securityTokenStorage;
    private $router;
    private $templating;
    private $logger;

    /**
     *
     * @param @security.token_storage
     * @param @router
     * @param @templating
     * @param @logger
     */
    public function __construct(
        $securityTokenStorage,
        $router,
        $templating,
        $logger
    ) {
        $this->securityTokenStorage = $securityTokenStorage;

        $this->router = $router;
        $this->templating = $templating;

        $this->logger = $logger;
    }

    /**
     * List organization
     * beta
     */
    public function listing(Request $request)
    {
        // $this->logger->info('*** listing');
        
        // Request arguments
        $uuid = $request->get('uuid');
        // $this->logger->info('$uuid = ' . print_r($uuid, true));

        // top tags
        $organizations = PQOrganizationQuery::create()
            ->filterByOnline(true)
            ->orderByRank()
            ->find();

        $html = $this->templating->render(
            'PolitizrFrontBundle:Organization:_list.html.twig',
            array(
                'organizations' => $organizations,
                'uuid' => $uuid
            )
        );

        return array(
            'html' => $html,
        );
    }
}
