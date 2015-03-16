<?php

namespace Politizr\Model;

use Politizr\Model\om\BasePTTagTypeQuery;

class PTTagTypeQuery extends BasePTTagTypeQuery
{
    /**
     *
     */
    public function __toString() {
        return $this->getTitle();
    }
}
