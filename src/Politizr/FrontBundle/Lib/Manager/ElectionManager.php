<?php
namespace Politizr\FrontBundle\Lib\Manager;

use Politizr\Model\PEOScopePLCQuery;
use Politizr\Model\PEOScopePLC;

/**
 * DB manager service for operation.
 *
 * @author Lionel Bouzonville
 */
class ElectionManager
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
    /*                                    RELATED TABLES OPERATIONS                                             */
    /* ######################################################################################################## */

    /**
     * Create a new PEOScopePLC association
     *
     * @param integer $operationId
     * @param integer $cityId
     * @return PEOScopePLC
     */
    public function createOperationCityScope($operationId, $cityId)
    {
        $scope = PEOScopePLCQuery::create()
            ->filterByPEOperationId($operationId)
            ->filterByPLCityId($cityId)
            ->findOne();

        if (!$scope) {
            $scope = new PEOScopePLC();

            $scope->setPEOperationId($operationId);
            $scope->setPLCityId($cityId);
            $scope->save();
            
            return $scope;
        }

        return null;
    }

    /**
     * Delete PEOScopePLC
     *
     * @param integer $operationId
     * @param integer $cityId
     * @return integer
     */
    public function deleteOperationCityScope($operationId, $cityId)
    {
        // Suppression élément(s)
        $result = PEOScopePLCQuery::create()
            ->filterByPEOperationId($operationId)
            ->filterByPLCityId($cityId)
            ->delete();

        return $result;
    }
}
