<?php

namespace Politizr\AdminBundle\Controller\PDReaction;

use Admingenerated\PolitizrAdminBundle\BasePDReactionController\ListController as BaseListController;

use Politizr\Model\PLCityQuery;
use Politizr\Model\PLDepartmentQuery;

/**
 * ListController
 */
class ListController extends BaseListController
{
    /**
     *
     */
    protected function scopeNotPublished($queryFilter)
    {
        $queryFilter->getQuery()
            ->filterByPublished(false)
            ->filterByTreeLevel(null)
        ;
    }

//     /**
//      * Add the filters to the query for PTags
//      *
//      * @param queryFilter The queryFilter
//      * @param mixed The value
//      */
//     protected function filterPTags($queryFilter, $values)
//     {
//         $ids = [];
//         foreach ($values as $tag) {
//             $ids[] = $tag->getId();
//         }
// 
//         $queryFilter->getQuery()
//             ->usePDRTaggedTQuery()
//                 ->filterByPTagId($ids, \Criteria::IN)
//             ->endUse()
//         ;
//     }

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
}
