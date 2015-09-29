<?php

namespace Politizr\AdminBundle\Controller\PDDebate;

use Admingenerated\PolitizrAdminBundle\BasePDDebateController\ListController as BaseListController;

/**
 * ListController
 */
class ListController extends BaseListController
{

    protected function processScopes($queryFilter)
    {
        $filters = $this->get('session')->get($this->getSessionPrefix().'List\Filters');


        // $filters['filterByPublished'] = '1';
        // $filters['filterByPublished'] = '';


        dump($queryFilter);
        dump($filters);
    }


}
