<?php

namespace Politizr\Model;

use Politizr\Model\om\BasePMModerationType;

class PMModerationType extends BasePMModerationType
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
