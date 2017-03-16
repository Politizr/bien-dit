<?php
namespace Politizr\FrontBundle\Lib\Manager;

use Politizr\Model\PLRegionQuery;
use Politizr\Model\PLDepartmentQuery;
use Politizr\Model\PLCityQuery;

use Politizr\Model\PUser;

use  Politizr\Constant\LocalizationConstants;

/**
 * DB manager service for localization.
 *
 * @author Lionel Bouzonville
 */
class LocalizationManager
{
    private $logger;

    /**
     *
     * @param @logger
     */
    public function __construct($logger)
    {
        $this->logger = $logger;
    }


    /* ######################################################################################################## */
    /*                                            CRUD OPERATIONS                                               */
    /* ######################################################################################################## */

    /**
     * Get array of [title] => [uuid] departments
     *
     * @return array
     */
    public function getDepartmentChoices()
    {
        // department list
        $departments = PLDepartmentQuery::create()
            ->distinct()
            ->select(array('uuid', 'title'))
            ->orderBy('title')
            ->filterById(LocalizationConstants::getOutOfFranceDepartmentIds(), ' NOT IN ')
            ->find()
            ->toArray();

        $choices = array();
        foreach ($departments as $department) {
            $choices[$department['title']] = $department['uuid'];
        }

        return $choices;
    }

    /**
     * Get array of [title] => [uuid] cities
     *
     * @param int departmentUuid
     * @return array
     */
    public function getCirconscriptionChoices()
    {
        // department out of france list
        $departments = PLDepartmentQuery::create()
            ->distinct()
            ->select(array('uuid', 'title'))
            ->orderBy('id')
            ->filterById(LocalizationConstants::getOutOfFranceDepartmentIds())
            ->find()
            ->toArray();

        $choices = array();
        foreach ($departments as $department) {
            $choices[$department['title']] = $department['uuid'];
        }

        return $choices;
    }

    /**
     * Get array of [title] => [uuid] departments
     *
     * @return array
     */
    public function getRegionChoices()
    {
        // department list
        $regions = PLRegionQuery::create()
            ->distinct()
            ->select(array('uuid', 'title'))
            ->orderBy('title')
            ->find()
            ->toArray();

        $choices = array();
        foreach ($regions as $region) {
            $choices[$region['title']] = $region['uuid'];
        }

        return $choices;
    }

    /**
     * Get array of [title] => [uuid] cities
     *
     * @param int departmentUuid
     * @return array
     */
    public function getCityChoices($departmentUuid)
    {
        if ($departmentUuid == null) {
            return array();
        }

        // department list
        $cities = PLCityQuery::create()
            ->distinct()
            ->withColumn('p_l_city.name_real', 'title')
            ->select(array('uuid', 'title'))
            ->usePLDepartmentQuery()
                ->filterByUuid($departmentUuid)
            ->endUse()
            ->orderBy('title')
            ->find()
            ->toArray();

        $choices = array();
        foreach ($cities as $city) {
            $choices[$city['title']] = $city['uuid'];
        }

        return $choices;
    }

    /**
     * Get city uuid by city id
     *
     * @param int $cityId
     * @return string
     */
    public function getCityUuidByCityId($cityId)
    {
        if (!$cityId) {
            return null;
        }

        $city = PLCityQuery::create()->findPk($cityId);

        if (!$city) {
            return null;
        }

        return $city->getUuid();
    }

    /**
     * Get city id by city uuid
     *
     * @param int $cityUuid
     * @return string
     */
    public function getCityIdByCityUuid($cityUuid)
    {
        if (!$cityUuid) {
            return null;
        }

        $city = PLCityQuery::create()->filterByUuid($cityUuid)->findOne();

        if (!$city) {
            return null;
        }

        return $city->getId();
    }

    /**
     * Get department uuid by department id
     *
     * @param int $departmentId
     * @return string
     */
    public function getDepartmentUuidByDepartmentId($departmentId)
    {
        if (!$departmentId) {
            return null;
        }

        $department = PLDepartmentQuery::create()->findPk($departmentId);

        if (!$department) {
            return null;
        }

        return $department->getUuid();
    }

    /**
     * Get department id by department uuid
     *
     * @param int $departmentUuid
     * @return string
     */
    public function getDepartmentIdByDepartmentUuid($departmentUuid)
    {
        if (!$departmentUuid) {
            return null;
        }

        $department = PLDepartmentQuery::create()->filterByUuid($departmentUuid)->findOne();

        if (!$department) {
            return null;
        }

        return $department->getId();
    }

    /**
     * Get region uuid by region id
     *
     * @param int $regionId
     * @return string
     */
    public function getRegionUuidByRegionId($regionId)
    {
        if (!$regionId) {
            return null;
        }

        $region = PLRegionQuery::create()->findPk($regionId);

        if (!$region) {
            return null;
        }

        return $region->getUuid();
    }

    /**
     * Get region id by region uuid
     *
     * @param int $regionUuid
     * @return string
     */
    public function getRegionIdByRegionUuid($regionUuid)
    {
        if (!$regionUuid) {
            return null;
        }

        $region = PLRegionQuery::create()->filterByUuid($regionUuid)->findOne();

        if (!$region) {
            return null;
        }

        return $region->getId();
    }

    /**
     * Get country uuid by country id
     *
     * @param int $countryId
     * @return string
     */
    public function getCountryUuidByCountryId($countryId)
    {
        if (!$countryId) {
            return null;
        }

        $country = PLCountryQuery::create()->findPk($countryId);

        if (!$country) {
            return null;
        }

        return $country->getUuid();
    }

    /**
     * Get department uuid by city id
     *
     * @param int $cityId
     * @return string
     */
    public function getDepartmentUuidByCityId($cityId)
    {
        if (!$cityId) {
            return null;
        }

        $department = PLDepartmentQuery::create()
            ->usePLCityQuery()
                ->filterById($cityId)
            ->endUse()
            ->findOne();

        if (!$department) {
            return null;
        }

        return $department->getUuid();
    }

    /**
     * Get department uuid by city uuid
     *
     * @param string $cityUuid
     * @return string
     */
    public function getDepartmentUuidByCityUuid($cityUuid)
    {
        if (!$cityUuid) {
            return null;
        }

        $department = PLDepartmentQuery::create()
            ->usePLCityQuery()
                ->filterByUuid($cityUuid)
            ->endUse()
            ->findOne();

        if (!$department) {
            return null;
        }

        return $department->getUuid();
    }


    /**
     * Get region uuid by city id
     *
     * @param int $cityId
     * @return string
     */
    public function getRegionUuidByCityId($cityId)
    {
        if (!$cityId) {
            return null;
        }

        $region = PLRegionQuery::create()
            ->usePLDepartmentQuery()
                ->usePLCityQuery()
                    ->filterById($cityId)
                ->endUse()
            ->endUse()
            ->findOne();

        if (!$region) {
            return null;
        }

        return $region->getUuid();
    }

    /**
     * Check if city is out of france (circonscription)
     *
     * @param int $cityId
     * @return boolean
     */
    public function isOutOfFranceByCityId($cityId)
    {
        if (!$cityId) {
            return false;
        }

        $city = PLCityQuery::create()->findPk($cityId);
        if (!$city) {
            return false;
        }

        if (in_array($city->getPLDepartmentId(), LocalizationConstants::getOutOfFranceDepartmentIds())) {
            return true;
        }

        return false;
    }

    /**
     * Upd user w. his city id
     *
     * @param PUser $user
     * @param string $cityUuid
     * @return PUser
     */
    public function updateUserCity(PUser $user, $cityUuid)
    {
        $city = PLCityQuery::create()
            ->filterByUuid($cityUuid)
            ->findOne();

        if ($city) {
            $user->setPLCityId($city->getId());
            $user->save();
        }

        return $user;
    }
}
