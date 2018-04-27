<?php

namespace Politizr\Model;

use Politizr\Model\om\BasePCircleType;

class PCircleType extends BasePCircleType
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
