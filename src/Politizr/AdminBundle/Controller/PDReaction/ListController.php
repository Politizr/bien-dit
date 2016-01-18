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
        $queryFilter->getQuery()->filterByTreeLeft(1, \Criteria::NOT_EQUAL);
    }
}
