<?php
namespace Politizr\FrontBundle\Lib\Xhr;

use Symfony\Component\HttpFoundation\Request;

use Politizr\Constant\ObjectTypeConstants;
use Politizr\Constant\ListingConstants;
use Politizr\Constant\LocalizationConstants;

use StudioEcho\Lib\StudioEchoUtils;

use Politizr\Exception\InconsistentDataException;
use Politizr\Exception\BoxErrorException;

use Politizr\Model\PLCountryQuery;
use Politizr\Model\PLRegionQuery;
use Politizr\Model\PLDepartmentQuery;
use Politizr\Model\PLCityQuery;

use Politizr\FrontBundle\Form\Type\PLDepartmentChoiceType;

/**
 * XHR service for localization management.
 * beta
 *
 * @author Lionel Bouzonville
 */
class XhrLocalization
{
    private $securityTokenStorage;
    private $templating;
    private $formFactory;
    private $localizationService;
    private $localizationManager;
    private $logger;

    /**
     *
     * @param @security.token_storage
     * @param @templating
     * @param @form.factory
     * @param @politizr.functional.localization
     * @param @politizr.localization.manager
     * @param @logger
     */
    public function __construct(
        $securityTokenStorage,
        $templating,
        $formFactory,
        $localizationService,
        $localizationManager,
        $logger
    ) {
        $this->securityTokenStorage = $securityTokenStorage;

        $this->templating = $templating;

        $this->formFactory = $formFactory;

        $this->localizationService = $localizationService;
        $this->localizationManager = $localizationManager;

        $this->logger = $logger;
    }

    /* ######################################################################################################## */
    /*                                               SELECT2 FUNCTIONS                                          */
    /* ######################################################################################################## */

    /**
     * Refresh select city widget
     * beta
     */
    public function citiesSelectList(Request $request)
    {
        // Request arguments
        $departmentUuid = $request->get('departmentUuid');
        // $this->logger->info('$departmentUuid = ' . print_r($departmentUuid, true));

        $cities = $this->localizationManager->getCityChoices($departmentUuid);
        $optionValues[] = '<option value="">Choisissez votre ville</option>';
        foreach ($cities as $cityName => $cityUuid) {
            $optionValues[] = '<option value="' . $cityUuid . '">' . $cityName . '</option>';
        }

        return array(
            'html' => $optionValues
        );
    }

    /**
     * Refresh select out of france departments widget
     * beta
     */
    public function circonscriptionsSelectList(Request $request)
    {
        $circonscriptions = $this->localizationManager->getCirconscriptionChoices();
        $optionValues[] = '<option value="">Choisissez votre circonscription</option>';
        foreach ($circonscriptions as $circonscriptionName => $circonscriptionUuid) {
            $optionValues[] = '<option value="' . $circonscriptionUuid . '">' . $circonscriptionName . '</option>';
        }

        return array(
            'html' => $optionValues
        );
    }

    /* ######################################################################################################## */
    /*                                               MAP TAG FUNCTIONS                                          */
    /* ######################################################################################################## */

    /**
     * Map menu (france / outre mer / hors de france )
     * /!\ only used w. shorcut 'my region' / 'my department' / 'my city'
     * beta
     */
    public function mapMenu(Request $request)
    {
        // $this->logger->info('*** mapMenu');

        // Request arguments
        $uuid = $request->get('uuid');
        // $this->logger->info('$uuid = ' . print_r($uuid, true));
        $type = $request->get('type');
        // $this->logger->info('$type = ' . print_r($type, true));

        // get current user
        $user = $this->securityTokenStorage->getToken()->getUser();

        $france = PLCountryQuery::create()->findPk(LocalizationConstants::FRANCE_ID);
        $fom = PLRegionQuery::create()->findPk(LocalizationConstants::REGION_ID_FOM);
        $world = PLRegionQuery::create()->findPk(LocalizationConstants::REGION_ID_WORLD);

        $isFom = false;
        $isWorld = false;
        
        if ($user && !is_string($user) && $city = $user->getPLCity()) {
            $department = $city->getPLDepartment();
            // check if user is in dom/tom or out of france
            if (in_array($department->getId(), LocalizationConstants::getGeoDepartmentOMIds())) {
                $isFom = true;
            } elseif (in_array($department->getId(), LocalizationConstants::getOutOfFranceDepartmentIds())) {
                $isWorld = true;
            }
        }

        $html = $this->templating->render(
            'PolitizrFrontBundle:Search\\Map:_menu.html.twig',
            array(
                'france' => $france,
                'fom' => $fom,
                'world' => $world,
                'isFom' => $isFom,
                'isWorld' => $isWorld,
            )
        );

        return array(
            'html' => $html,
        );
    }

    /**
     * Map breadcrumb
     * beta
     */
    public function mapBreadcrumb(Request $request)
    {
        // $this->logger->info('*** mapBreadcrumb');

        // Request arguments
        $uuid = $request->get('uuid');
        // $this->logger->info('$uuid = ' . print_r($uuid, true));
        $type = $request->get('type');
        // $this->logger->info('$type = ' . print_r($type, true));


        $geoTypeObjects = array();
        if ($type == LocalizationConstants::TYPE_REGION) {
            $region = PLRegionQuery::create()->filterByUuid($uuid)->findOne();

            if ($region && $region->getId() != LocalizationConstants::REGION_ID_FOM && $region->getId() != LocalizationConstants::REGION_ID_WORLD) {
                // special cases: domtom & out of france > no region display
                $geoTypeObjects = array(LocalizationConstants::TYPE_REGION => $region);
            }
        } elseif ($type == LocalizationConstants::TYPE_DEPARTMENT) {
            $department = PLDepartmentQuery::create()->filterByUuid($uuid)->findOne();

            if ($department && in_array($department->getId(), LocalizationConstants::getGeoDepartmentMetroIds())) {
                // classic france metro case
                $region = PLRegionQuery::create()->filterById($department->getPLRegionId())->findOne();

                $geoTypeObjects = array(
                    LocalizationConstants::TYPE_REGION => $region,
                    LocalizationConstants::TYPE_DEPARTMENT => $department,
                );
            } else {
                // special cases: domtom & out of france > no region display
                $geoTypeObjects = array(
                    LocalizationConstants::TYPE_DEPARTMENT => $department,
                );
            }
        } elseif ($type == LocalizationConstants::TYPE_CITY) {
            $city = PLCityQuery::create()->filterByUuid($uuid)->findOne();
            if (!$city) {
                throw new InconsistentDataException(sprintf('City uuid-%s not found', $uuid));
            }
            $department = $city->getPLDepartment();

            if ($department && in_array($department->getId(), LocalizationConstants::getGeoDepartmentMetroIds())) {
                // classic france metro case
                $region = PLRegionQuery::create()->filterById($department->getPLRegionId())->findOne();

                $geoTypeObjects = array(
                    LocalizationConstants::TYPE_REGION => $region,
                    LocalizationConstants::TYPE_DEPARTMENT => $department,
                    LocalizationConstants::TYPE_CITY => $city,
                );
            } elseif ($department && in_array($department->getId(), LocalizationConstants::getGeoDepartmentOMIds())) {
                // special case: domtom > no region display
                $region = PLRegionQuery::create()->filterById($department->getPLRegionId())->findOne();

                $geoTypeObjects = array(
                    LocalizationConstants::TYPE_DEPARTMENT => $department,
                    LocalizationConstants::TYPE_CITY => $city,
                );
            } elseif ($department && in_array($department->getId(), LocalizationConstants::getOutOfFranceDepartmentIds())) {
                // special case: out of france > no region & no city display
                $region = PLRegionQuery::create()->filterById($department->getPLRegionId())->findOne();

                $geoTypeObjects = array(
                    LocalizationConstants::TYPE_DEPARTMENT => $department,
                );
            }
        }

        $html = $this->templating->render(
            'PolitizrFrontBundle:Search\\Map:_breadcrumb.html.twig',
            array(
                'geoTypeObjects' => $geoTypeObjects,
            )
        );

        return array(
            'html' => $html,
        );
    }

    /**
     * Map schema
     * beta
     */
    public function mapSchema(Request $request)
    {
        // $this->logger->info('*** mapSchema');

        // Request arguments
        $uuid = $request->get('uuid');
        // $this->logger->info('$uuid = ' . print_r($uuid, true));
        $type = $request->get('type');
        // $this->logger->info('$type = ' . print_r($type, true));


        if ($type == LocalizationConstants::TYPE_COUNTRY) {
            $geoId = LocalizationConstants::FRANCE_ID;
            $mapUuids = $this->localizationService->getRegionUuids();
        } elseif ($type == LocalizationConstants::TYPE_REGION) {
            $region = PLRegionQuery::create()->filterByUuid($uuid)->findOne();
            if (!$region) {
                throw new InconsistentDataException(sprintf('Region uuid-%s not found', $uuid));
            }

            $geoId = $region->getId();
            $mapUuids = $this->localizationService->getDepartmentsUuids($region->getId());
        } elseif ($type == LocalizationConstants::TYPE_DEPARTMENT) {
            $department = PLDepartmentQuery::create()->filterByUuid($uuid)->findOne();
            if (!$department) {
                throw new InconsistentDataException(sprintf('Department uuid-%s not found', $uuid));
            }

            $geoId = $department->getId();
            $mapUuids = $this->localizationService->getDepartmentsUuids($department->getPLRegionId());
        } elseif ($type == LocalizationConstants::TYPE_CITY) {
            // retrieve department of current city
            $city = PLCityQuery::create()->filterByUuid($uuid)->findOne();
            if (!$city) {
                throw new InconsistentDataException(sprintf('City uuid-%s not found', $uuid));
            }

            $department = $city->getPLDepartment();
            $geoId = $department->getId();
            $mapUuids = $this->localizationService->getDepartmentsUuids($department->getPLRegionId());
        } else {
            throw new InconsistentDataException(sprintf('Geo type %s not found', $type));            
        }

        $html = $this->templating->render(
            'PolitizrFrontBundle:Search\\Map:_routing.html.twig',
            array(
                'type' => $type,
                'geoId' => $geoId,
                'mapUuids' => $mapUuids,
            )
        );

        return array(
            'html' => $html,
        );
    }
}