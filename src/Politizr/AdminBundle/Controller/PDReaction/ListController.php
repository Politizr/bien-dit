<?php

namespace Politizr\AdminBundle\Controller\PDReaction;

use Admingenerated\PolitizrAdminBundle\BasePDReactionController\ListController as BaseListController;

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
}
