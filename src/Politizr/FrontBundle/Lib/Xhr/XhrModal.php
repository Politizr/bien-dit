<?php
namespace Politizr\FrontBundle\Lib\Xhr;

use Symfony\Component\HttpFoundation\Request;

use Politizr\Constant\ObjectTypeConstants;
use Politizr\Constant\ListingConstants;

use Politizr\Model\PMCguQuery;
use Politizr\Model\PMCgvQuery;
use Politizr\Model\PMCharteQuery;

/**
 * XHR service for modal management.
 * beta
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
        // $this->logger->info('*** helpUs');
        
        $request->getSession()->set('helpUs', true);
        // $request->getSession()->remove('helpUs');

        $html = $this->templating->render(
            'PolitizrFrontBundle:Navigation\\Helper:_helpUs.html.twig'
        );

        return array(
            'html' => $html,
        );
    }

    /**
     * Help us
     * beta
     */
    public function createAccountToComment(Request $request)
    {
        // $this->logger->info('*** createAccountToComment');
        
        $html = $this->templating->render(
            'PolitizrFrontBundle:Navigation\\Helper:_createAccountToComment.html.twig'
        );

        return array(
            'html' => $html,
        );
    }

    /**
     * CGU
     * beta
     */
    public function cgu(Request $request)
    {
        // $this->logger->info('*** cgu');

        $legal = PMCguQuery::create()->filterByOnline(true)->orderByCreatedAt('desc')->findOne();
        
        $html = $this->templating->render(
            'PolitizrFrontBundle:Monitoring:_legal.html.twig',
            array(
                'legal' => $legal
            )
        );

        return array(
            'html' => $html,
        );
    }

    /**
     * CGV
     * beta
     */
    public function cgv(Request $request)
    {
        // $this->logger->info('*** cgv');

        $legal = PMCgvQuery::create()->filterByOnline(true)->orderByCreatedAt('desc')->findOne();
        
        $html = $this->templating->render(
            'PolitizrFrontBundle:Monitoring:_legal.html.twig',
            array(
                'legal' => $legal
            )
        );

        return array(
            'html' => $html,
        );
    }

    /**
     * Charte
     * beta
     */
    public function charte(Request $request)
    {
        // $this->logger->info('*** charte');

        $legal = PMCharteQuery::create()->filterByOnline(true)->orderByCreatedAt('desc')->findOne();
        
        $html = $this->templating->render(
            'PolitizrFrontBundle:Monitoring:_legal.html.twig',
            array(
                'legal' => $legal
            )
        );

        return array(
            'html' => $html,
        );
    }
}
