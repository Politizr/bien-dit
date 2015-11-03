<?php

namespace Politizr\Model;

use Politizr\Model\om\BasePOSubscription;

/**
 *
 * @author Lionel Bouzonville
 */
class POSubscription extends BasePOSubscription
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
     * Format title & price
     *
     * @return string
     */
    public function getTitleAndPrice()
    {
        return $this->getTitle() . ' - ' . number_format($this->getPrice(), 2, ',', ' ') .'€';
    }
}
