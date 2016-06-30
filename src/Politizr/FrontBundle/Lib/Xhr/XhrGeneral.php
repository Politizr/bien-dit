<?php
namespace Politizr\FrontBundle\Lib\Xhr;

use Symfony\Component\HttpFoundation\Request;

use Politizr\Constant\ObjectTypeConstants;
use Politizr\Constant\ListingConstants;

use Politizr\Model\PMCguQuery;
use Politizr\Model\PMCgvQuery;
use Politizr\Model\PMCharteQuery;

/**
 * XHR service for general management.
 * beta
 *
 * @author Lionel Bouzonville
 */
class XhrGeneral
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
     * Show/Hide suggestion timeline by default
     * beta
     */
    public function showSuggestion(Request $request)
    {
        // $this->logger->info('*** showSuggestion');

        // Request arguments
        $show = $request->get('show');
        // $this->logger->info('$show = ' . print_r($show, true));
        
        if ($show == "true") {
            $show = true;
        } else {
            $show = false;
        }
        $request->getSession()->set('showSuggestion', $show);

        return true;
    }
}
