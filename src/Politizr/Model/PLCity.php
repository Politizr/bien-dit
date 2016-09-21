<?php

namespace Politizr\Model;

use Politizr\Model\om\BasePLCity;

class PLCity extends BasePLCity
{
    /**
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getNameReal();
    }

    /**
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->getNameReal();
    }
}
