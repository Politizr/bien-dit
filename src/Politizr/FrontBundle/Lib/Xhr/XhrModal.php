<?php
namespace Politizr\FrontBundle\Lib\Xhr;

use Symfony\Component\HttpFoundation\Request;

use Politizr\Constant\ObjectTypeConstants;
use Politizr\Constant\ListingConstants;
use Politizr\Constant\GlobalConstants;
use Politizr\Constant\CmsConstants;

use Politizr\Model\PMCguQuery;
use Politizr\Model\PMCgvQuery;
use Politizr\Model\PMCharteQuery;
use Politizr\Model\PCircleQuery;
use Politizr\Model\CmsContentAdminQuery;

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

        $content = CmsContentAdminQuery::create()->findPk(CmsConstants::CMS_CONTENT_ADMIN_POPUP_HELPUS);
        if (!$content) {
            throw new InconsistentDataException('Contenu popup non disponible');
        }

        $html = $this->templating->render(
            'PolitizrFrontBundle:Navigation\\Helper:_helpUs.html.twig',
            array(
                'content' => $content,
            )
        );

        return array(
            'html' => $html,
        );
    }

    /**
     * Getting started
     * beta
     */
    public function gettingStarted(Request $request)
    {
        $this->logger->info('*** gettingStarted');
        
        $request->getSession()->remove('gettingStarted');
        $request->getSession()->set('helpUs', true);

        $content = CmsContentAdminQuery::create()->findPk(CmsConstants::CMS_CONTENT_ADMIN_POPUP_WELCOME);
        if (!$content) {
            throw new InconsistentDataException('Contenu popup non disponible');
        }

        $html = $this->templating->render(
            'PolitizrFrontBundle:Navigation\\Helper:_gettingStarted.html.twig',
            array(
                'content' => $content,
            )
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

        $uuid = $request->get('uuid');

        if ($uuid) {
            $circle = PCircleQuery::create()->filterByUuid($uuid)->findOne();
            if (!$circle) {
                throw new InconsistentDataException(sprintf('Circle %s not found', $uuid));
            }

            $legal = PMCharteQuery::create()->filterByPCircleId($circle->getId())->findOne();

            // global if none found
            if (!$legal) {
                $legal = PMCharteQuery::create()->findPk(GlobalConstants::GLOBAL_CHARTE_ID);
            }
        } else {
            $legal = PMCharteQuery::create()->findPk(GlobalConstants::GLOBAL_CHARTE_ID);
        }

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
