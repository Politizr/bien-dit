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
        $filters = $queryParams[1];

        // Retrieve subject
        if (!$geoTagUuid) {
            $tag = PTagQuery::create()->findPK(TagConstants::TAG_GEO_FRANCE_ID);
            $geoTagUuid = $tag->getUuid();
        } else {
            $tag = PTagQuery::create()->filterByUuid($geoTagUuid)->findOne();
        }

        // Compute relative geo tag ids
        $tagIds = $this->tagService->computeGeotagRelativeIds($tag->getId());

        // 3 top debates
        $debates = PDDebateQuery::create()
                    ->distinct()
                    ->online()
                    ->usePDDTaggedTQuery()
                        ->filterByPTagId($tagIds)
                    ->endUse()
                    ->filterByKeywords($filters)
                    ->orderWithKeyword('bestNote')
                    ->limit(ListingConstants::DASHBOARD_MAP_LIMIT)
                    ->find();

        // 3 top users
        $users = PUserQuery::create()
                    ->distinct()
                    ->online()
                    ->usePuTaggedTPUserQuery()
                        ->filterByPTagId($tagIds)
                    ->endUse()
                    ->filterByKeywords($filters)
                    ->orderWithKeyword('bestNote')
                    ->limit(ListingConstants::DASHBOARD_MAP_LIMIT)
                    ->find();

        // Map ids
        $worldTag = PTagQuery::create()->findPk(TagConstants::TAG_GEO_WORLD_ID);
        $europeTag = PTagQuery::create()->findPk(TagConstants::TAG_GEO_EUROPE_ID);
        
        // FOM ids
        $fomTag['guadeloupe'] = PTagQuery::create()->findPk(TagConstants::TAG_GEO_DEPARTMENT_ID_GUADELOUPE);
        $fomTag['martinique'] = PTagQuery::create()->findPk(TagConstants::TAG_GEO_DEPARTMENT_ID_MARTINIQUE);
        $fomTag['guyane'] = PTagQuery::create()->findPk(TagConstants::TAG_GEO_DEPARTMENT_ID_GUYANE);
        $fomTag['laReunion'] = PTagQuery::create()->findPk(TagConstants::TAG_GEO_DEPARTMENT_ID_LA_REUNION);
        $fomTag['mayotte'] = PTagQuery::create()->findPk(TagConstants::TAG_GEO_DEPARTMENT_ID_MAYOTTE);
        $fomTag['polynesieFrancaise'] = PTagQuery::create()->findPk(TagConstants::TAG_GEO_DEPARTMENT_ID_POLYNESIE_FRANCAISE);
        $fomTag['saintBarthelemy'] = PTagQuery::create()->findPk(TagConstants::TAG_GEO_DEPARTMENT_ID_SAINT_BARTHELEMY);
        $fomTag['saintMartin'] = PTagQuery::create()->findPk(TagConstants::TAG_GEO_DEPARTMENT_ID_SAINT_MARTIN);
        $fomTag['saintPierreEtMiquelon'] = PTagQuery::create()->findPk(TagConstants::TAG_GEO_DEPARTMENT_ID_SAINT_PIERRE_ET_MIQUELON);
        $fomTag['wallisEtFutuma'] = PTagQuery::create()->findPk(TagConstants::TAG_GEO_DEPARTMENT_ID_WALLIS_ET_FUTUMA);
        $fomTag['nouvelleCaledonie'] = PTagQuery::create()->findPk(TagConstants::TAG_GEO_DEPARTMENT_ID_NOUVELLE_CALEDONIE);

        if ($tag->getId() == TagConstants::TAG_GEO_FRANCE_ID) {
            $mapTags = $this->tagService->getRegionUuids();
        } elseif (in_array($tag->getId(), TagConstants::getGeoRegionIds())) {
            $mapTags = $this->tagService->getDepartmentsUuids($tag->getId());
        } elseif (in_array($tag->getId(), TagConstants::getGeoDepartmentMetroIds())) {
            // parent id = region id
            $mapTags = $this->tagService->getDepartmentsUuids($tag->getPTParentId());
        } else {
            $mapTags = [];
        }

        $html = $this->templating->render(
            'PolitizrFrontBundle:Dashboard:_map.html.twig',
            array(
                'tag' => $tag,
                'filters' => $filters,
                'debates' => $debates,
                'users' => $users,
                'worldTag' => $worldTag,
                'fomTag' => $fomTag,
                'europeTag' => $europeTag,
                'mapTags' => $mapTags,
            )
        );

        return array(
            'html' => $html,
            );
    }
}
