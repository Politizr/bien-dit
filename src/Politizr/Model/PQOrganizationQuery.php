<?php

namespace Politizr\Model;

use Politizr\Model\om\BasePQOrganizationQuery;

/**
 *
 * @author Lionel Bouzonville
 */
class PQOrganizationQuery extends BasePQOrganizationQuery
{

    /* ######################################################################################################## */
    /*                                              FILTERBY IF                                                 */
    /* ######################################################################################################## */

    /**
     *
     * @param boolean $online
     * @return PQOrganizationQuery
     */
    public function filterIfOnline($online = null)
    {
        return $this
            ->_if(null !== $online)
                ->filterByOnline($online)
            ->_endif();
    }

    /**
     *
     * @param boolean $pqTypeId
     * @return PQOrganizationQuery
     */
    public function filterIfPQTypeId($pqTypeId = null)
    {
        return $this
            ->_if(null !== $pqTypeId)
                ->filterByPQTypeId($pqTypeId)
            ->_endif();
    }
}
