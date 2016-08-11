<?php
namespace Politizr\FrontBundle\Lib\Functional;

use Politizr\Exception\InconsistentDataException;

use Politizr\Constant\TagConstants;

use Politizr\Model\PLCityQuery;
use Politizr\Model\PLDepartmentQuery;
use Politizr\Model\PLRegionQuery;

/**
 * Functional service for localization management.
 *
 * @author Lionel Bouzonville
 */
class LocalizationService
{
    private $securityTokenStorage;
    private $securityAuthorizationChecker;
    
    private $localizationManager;
    
    private $logger;

    /**
     *
     * @param @security.token_storage
     * @param @security.authorization_checker
     * @param @politizr.manager.localization
     * @param @logger
     */
    public function __construct(
        $securityTokenStorage,
        $securityAuthorizationChecker,
        $localizationManager,
        $logger
    ) {
        $this->securityTokenStorage = $securityTokenStorage;
        $this->securityAuthorizationChecker =$securityAuthorizationChecker;

        $this->localizationManager = $localizationManager;

        $this->logger = $logger;
    }

    /* ######################################################################################################## */
    /*                                              PUBLIC FUNCTIONS                                            */
    /* ######################################################################################################## */

    /**
     * Compute array of city ids included in geo tag ids
     *
     * @param array $tagIds
     * @return array
     */
    public function computeCityIdsFromTagIds($tagIds)
    {
        $cityIds = array();

        // special case tag "france"
        if (in_array(TagConstants::TAG_GEO_FRANCE_ID, $tagIds)) {
            return null;
        } else {
            foreach ($tagIds as $tagId) {
                if (in_array($tagId, TagConstants::getGeoRegionIds())) {
                    $cityIds = array_merge($cityIds, $this->localizationManager->getCityIdsByRegionTagId($tagId));
                } elseif (in_array($tagId, TagConstants::getGeoDepartmentIds())) {
                    $cityIds = array_merge($cityIds, $this->localizationManager->getCityIdsByDepartmentTagId($tagId));
                }
            }
        }

        return $cityIds;
    }

}
