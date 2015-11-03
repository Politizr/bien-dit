<?php

namespace Politizr\Model;

use Politizr\Model\om\BasePQType;

/**
 *
 * @author Lionel Bouzonville
 */
class PQType extends BasePQType
{
    /**
     *
     */
    public function __toString()
    {
        return $this->getTitle();
    }
}
