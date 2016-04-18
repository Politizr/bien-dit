<?php
namespace Politizr\FrontBundle\Lib\Xhr;

use Symfony\Component\HttpFoundation\Request;

use Politizr\Constant\ObjectTypeConstants;
use Politizr\Constant\ListingConstants;

use Politizr\Model\PDDebateQuery;
use Politizr\Model\PDReactionQuery;
use Politizr\Model\PUserQuery;
use Politizr\Model\PTagQuery;
use Politizr\Model\PQOrganizationQuery;

/**
 * XHR service for modal management.
 *
 * @author Lionel Bouzonville
 */
class XhrModal
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

    /**
     * Help us
     * beta
     */
    public function helpUs(Request $request)
    {
        $this->logger->info('*** helpUs');
        
        $request->getSession()->set('helpUs', true);
        // $request->getSession()->remove('helpUs');

        $html = $this->templating->render(
            'PolitizrFrontBundle:Navigation\\Helper:_helpUs.html.twig'
        );

        return array(
            'html' => $html,
        );
    }
}
