<?php
namespace Politizr\FrontBundle\Lib\Xhr;

use Symfony\Component\HttpFoundation\Request;

use Politizr\Exception\InconsistentDataException;
use Politizr\Exception\FormValidationException;

use Politizr\Constant\ListingConstants;
use Politizr\Constant\TagConstants;

use Politizr\Model\PDDebateQuery;
use Politizr\Model\PDReactionQuery;
use Politizr\Model\PUserQuery;
use Politizr\Model\PTagQuery;
use Politizr\Model\PQOrganizationQuery;

/**
 * XHR service for dashboard management.
 *
 * @author Lionel Bouzonville
 */
class XhrDashboard
{
    private $securityTokenStorage;
    private $templating;
    private $tagService;
    private $logger;

    /**
     *
     * @param @security.token_storage
     * @param @templating
     * @param @politizr.functional.tag
     * @param @logger
     */
    public function __construct(
        $securityTokenStorage,
        $templating,
        $tagService,
        $logger
    ) {
        $this->securityTokenStorage = $securityTokenStorage;

        $this->templating = $templating;
        
        $this->tagService = $tagService;

        $this->logger = $logger;
    }

    /* ######################################################################################################## */
    /*                                         PRIVATE FUNCTIONS                                                */
    /* ######################################################################################################## */

    /**
     * Return an array with request listing information:
     *    - filters,
     *
     * @return array[order,filters,offset,associatedObjectId]
     */
    private function getFiltersFromRequest(Request $request)
    {
        $geoTagUuid = $request->get('geoTagUuid');
        $this->logger->info('$geoTagUuid = ' . print_r($geoTagUuid, true));
        $filterDate = $request->get('filterDate');
        $this->logger->info('$filterDate = ' . print_r($filterDate, true));

        return [ $geoTagUuid, $filterDate ];
    }

    /* ######################################################################################################## */
    /*                                   DASHBOARD MAP LOADING                                                  */
    /* ######################################################################################################## */

    /**
     *
     */
    public function map(Request $request)
    {
        $this->logger->info('*** map');
        
        // Request arguments
        $queryParams = $this->getFiltersFromRequest($request);
        $geoTagUuid = $queryParams[0];
        $filterDate = $queryParams[1];

        // Function process
        $debates = PDDebateQuery::create()
                    ->distinct()
                    ->online()
                    ->limit(ListingConstants::DASHBOARD_MAP_LIMIT)
                    ->find();

        $users = PUserQuery::create()
                    ->distinct()
                    ->online()
                    ->limit(ListingConstants::DASHBOARD_MAP_LIMIT)
                    ->find();

        // Map ids
        $worldTag = PTagQuery::create()->findPk(TagConstants::TAG_GEO_WORLD_ID);
        $europeTag = PTagQuery::create()->findPk(TagConstants::TAG_GEO_EUROPE_ID);
        
        $regionACAL = PTagQuery::create()->findPk(TagConstants::TAG_GEO_REGION_ID_1);
        $regionALPC = PTagQuery::create()->findPk(TagConstants::TAG_GEO_REGION_ID_2);
        $regionARA = PTagQuery::create()->findPk(TagConstants::TAG_GEO_REGION_ID_3);
        $regionBFC = PTagQuery::create()->findPk(TagConstants::TAG_GEO_REGION_ID_4);
        $regionB = PTagQuery::create()->findPk(TagConstants::TAG_GEO_REGION_ID_5);
        $regionCVL = PTagQuery::create()->findPk(TagConstants::TAG_GEO_REGION_ID_6);
        $regionC = PTagQuery::create()->findPk(TagConstants::TAG_GEO_REGION_ID_7);
        $regionIDF = PTagQuery::create()->findPk(TagConstants::TAG_GEO_REGION_ID_8);
        $regionLRMP = PTagQuery::create()->findPk(TagConstants::TAG_GEO_REGION_ID_9);
        $regionNPDCP = PTagQuery::create()->findPk(TagConstants::TAG_GEO_REGION_ID_10);
        $regionN = PTagQuery::create()->findPk(TagConstants::TAG_GEO_REGION_ID_11);
        $regionPDLL = PTagQuery::create()->findPk(TagConstants::TAG_GEO_REGION_ID_12);
        $regionPACA = PTagQuery::create()->findPk(TagConstants::TAG_GEO_REGION_ID_13);


        $html = $this->templating->render(
            'PolitizrFrontBundle:Dashboard:_map.html.twig',
            array(
                'geoTagUuid' => $geoTagUuid,
                'filterDate' => $filterDate,
                'debates' => $debates,
                'users' => $users,
                'worldTag' => $worldTag,
                'europeTag' => $europeTag,
                'regionACAL' => $regionACAL,
                'regionALPC' => $regionALPC,
                'regionARA' => $regionARA,
                'regionBFC' => $regionBFC,
                'regionB' => $regionB,
                'regionCVL' => $regionCVL,
                'regionC' => $regionC,
                'regionIDF' => $regionIDF,
                'regionLRMP' => $regionLRMP,
                'regionNPDCP' => $regionNPDCP,
                'regionN' => $regionN,
                'regionPDLL' => $regionPDLL,
                'regionPACA' => $regionPACA,
            )
        );

        return array(
            'html' => $html,
            );
    }
}
