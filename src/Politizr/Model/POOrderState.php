<?php

namespace Politizr\Model;

use Politizr\Model\om\BasePOOrderState;

/**
 *
 * @author Lionel Bouzonville
 */
class POOrderState extends BasePOOrderState
{
    /**
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getTitle();
    }
}
