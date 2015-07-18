<?php

namespace Politizr\Model;

use Politizr\Model\om\BasePRBadgeMetal;

/**
 *
 * @author Lionel Bouzonville
 */
class PRBadgeMetal extends BasePRBadgeMetal
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
