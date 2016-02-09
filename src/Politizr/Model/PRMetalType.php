<?php

namespace Politizr\Model;

use Politizr\Model\om\BasePRMetalType;

class PRMetalType extends BasePRMetalType
{
    /**
     *
     */
    public function __toString()
    {
        return $this->getTitle();
    }
}
