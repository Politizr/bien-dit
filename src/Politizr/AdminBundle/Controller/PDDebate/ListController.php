<?php

namespace Politizr\AdminBundle\Controller\PDDebate;

use Admingenerated\PolitizrAdminBundle\BasePDDebateController\ListController as BaseListController;

use Politizr\Model\PLCityQuery;
use Politizr\Model\PLDepartmentQuery;

/**
 * ListController
 */
class ListController extends BaseListController
{
   /**
    * Add filters to the query for active
    *
    * @param QueryFilterInterface The queryFilter
    * @param mixed The value
    */
    protected function filterActive($queryFilter, $value)
    {
        if ($value) {
            $queryFilter->getQuery()->online();
        } else {
            $queryFilter->getQuery()->offline();
        }
    }

    /**
     * Add the filters to the query for PLDepartment > includes cities in department
     *
     * @param queryFilter The queryFilter
     * @param mixed The value
     */
    protected function filterPLDepartment($queryFilter, $value)
    {
        $queryFilter->addModelFilter('p_l_department', $value);

        $cityIds = PLCityQuery::create()
            ->select('id')
            ->filterByPLDepartmentId($value->getId())
            ->find()
            ->toArray();

        $queryFilter->getQuery()
            ->_or()
            ->filterByPLCityId($cityIds, " IN ")
        ;
    }

    /**
     * Add the filters to the query for PLRegion > includes departements & cities in department
     *
     * @param queryFilter The queryFilter
     * @param mixed The value
     */
    protected function filterPLRegion($queryFilter, $value)
    {
        $queryFilter->addModelFilter('p_l_region', $value);

        $departmentIds = PLDepartmentQuery::create()
            ->select('id')
            ->filterByPLRegionId($value->getId())
            ->find()
            ->toArray();

        $cityIds = array();
        foreach ($departmentIds as $departmentId) {
            $ids = PLCityQuery::create()
                ->select('id')
                ->filterByPLDepartmentId($value->getId())
                ->find()
                ->toArray();

            $cityIds = array_merge($cityIds, $ids);
        }

        $queryFilter->getQuery()
            ->_or()
            ->filterByPLDepartmentId($departmentIds, " IN ")
            ->_or()
            ->filterByPLCityId($cityIds, " IN ")
        ;
    }

    /**
     * Add filters to active scope
     *
     * @param queryFilter
     */
    protected function scopeActive($queryFilter)
    {
      $queryFilter->getQuery()
        ->online();
    }

    /**
     * Add filters to moderated scope
     *
     * @param queryFilter
     */
    protected function scopeModerated($queryFilter)
    {
      $queryFilter->getQuery()
        ->filterByModerated(true)
        ->_or()
        ->filterByModeratedPartial(true);
    }

    /**
     * Add filters to draft scope
     *
     * @param queryFilter
     */
    protected function scopeDraft($queryFilter)
    {
      $queryFilter->getQuery()
        ->filterByOnline(true)
        ->filterByPublished(false)
        ->filterByPublishedAt(null);
    }

    /**
     * Add filters to inactive scope
     *
     * @param queryFilter
     */
    protected function scopeInactive($queryFilter)
    {
      $queryFilter->getQuery()
        ->offline();
    }
}
