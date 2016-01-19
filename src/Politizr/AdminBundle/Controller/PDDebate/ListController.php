<?php

namespace Politizr\AdminBundle\Controller\PDDebate;

use Admingenerated\PolitizrAdminBundle\BasePDDebateController\ListController as BaseListController;

/**
 * ListController
 */
class ListController extends BaseListController
{
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
//             ->usePDDTaggedTQuery()
//                 ->filterByPTagId($ids, \Criteria::IN)
//             ->endUse()
//         ;
//     }
}
