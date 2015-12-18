<?php

namespace Politizr\AdminBundle\Controller\PUser;

use Admingenerated\PolitizrAdminBundle\BasePUserController\ListController as BaseListController;

/**
 * ListController
 */
class ListController extends BaseListController
{
    /**
     * Add the filters to the query for created_at
     *
     * @param queryFilter The queryFilter
     * @param mixed The value
     */
    protected function filterCreatedAt($queryFilter, $value)
    {
        $filterObject = $this->getFilters();

        $queryFilter->addDateRangeFilter('created_at', $filterObject['created_at']);
    }

    /**
     * Add the filters to the query for created_at
     *
     * @param queryFilter The queryFilter
     * @param mixed The value
     */
    protected function filterLastActivity($queryFilter, $value)
    {
        $filterObject = $this->getFilters();

        $queryFilter->addDateRangeFilter('last_activity', $filterObject['last_activity']);
    }
}
