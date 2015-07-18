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
     * @param boolean $online
     * @return PropelCollection[PRBadge]
     */
    public function getBadges($online = true)
    {
        $query = PRBadgeQuery::create()
            ->filterIfOnline($online)
            ->orderByTitle();

        return parent::getPRBadges($query);
    }
}
