<?php
namespace Politizr\FrontBundle\Lib\Manager;

use Politizr\Model\PLRegionQuery;
use Politizr\Model\PLDepartmentQuery;
use Politizr\Model\PLCityQuery;

use Politizr\Model\PUser;

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
     * Get array of ids of city included in the region tag id
     *
     * @param int $regionTagId
     * @return array
     */
    public function getCityIdsByRegionTagId($regionTagId)
    {
        $cityIds = PLCityQuery::create()
            ->select('id')
            ->usePLDepartmentQuery()
                ->usePLRegionQuery()
                    ->filterByPTagId($regionTagId)
                ->endUse()
            ->endUse()
            ->find()
            ->toArray();

        return $cityIds;
    }

    /**
     * Get array of ids of city included in the department tag id
     *
     * @param int $departmentTagId
     * @return array
     */
    public function getCityIdsByDepartmentTagId($departmentTagId)
    {
        $cityIds = PLCityQuery::create()
            ->select('id')
            ->usePLDepartmentQuery()
                ->filterByPTagId($departmentTagId)
            ->endUse()
            ->find()
            ->toArray();

        return $cityIds;
    }

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
