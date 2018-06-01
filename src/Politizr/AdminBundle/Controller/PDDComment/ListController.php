<?php

namespace Politizr\AdminBundle\Controller\PDDComment;

use Admingenerated\PolitizrAdminBundle\BasePDDCommentController\ListController as BaseListController;

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

}
