<?php

namespace Politizr\Model;

use Politizr\Model\om\BasePRBadgeFamily;

/**
 *
 * @author Lionel Bouzonville
 */
class PRBadgeFamily extends BasePRBadgeFamily
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
            ->orderByRank();

        return parent::getPRBadges($query);
    }
}
