<?php

namespace Politizr\AdminBundle\Controller\PDRComment;

use Admingenerated\PolitizrAdminBundle\BasePDRCommentController\ListController as BaseListController;

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
     * Add the filters to the query for topic
     *
     * @param queryFilter The queryFilter
     * @param mixed The value
     */
    protected function filterPCTopic($queryFilter, $pcTopic)
    {
        $queryFilter->getQuery()
            ->_if($pcTopic)
                ->usePDReactionQuery()
                    ->usePDDebateQuery()
                        ->usePCTopicQuery()
                            ->filterById($pcTopic->getId())
                        ->endUse()
                    ->endUse()
                ->endUse()
            ->_endif()
        ;
    }
    /**
     * Add the filters to the query for operation
     *
     * @param queryFilter The queryFilter
     * @param mixed The value
     */
    protected function filterPEOPeration($queryFilter, $peOperation)
    {
        $queryFilter->getQuery()
            ->_if($peOperation)
                ->usePDReactionQuery()
                    ->usePDDebateQuery()
                        ->usePEOperationQuery()
                            ->filterById($peOperation->getId())
                        ->endUse()
                    ->endUse()
                ->endUse()
            ->_endif()
        ;
    }
}
