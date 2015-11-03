<?php

namespace Politizr\Model;

use Politizr\Model\om\BasePRBadgeType;

/**
 *
 * @author Lionel Bouzonville
 */
class PRBadgeType extends BasePRBadgeType
{
    /**
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getTitle();
    }

    /**
     *
     * @return PropelCollection[PRBadge]
     */
    public function getBadgeFamilies()
    {
        $query = PRBadgeFamilyQuery::create()
            ->orderByRank();

        return parent::getPRBadgeFamilies($query);
    }
}
