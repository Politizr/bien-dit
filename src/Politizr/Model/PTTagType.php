<?php

namespace Politizr\Model;

use Politizr\Model\om\BasePTTagType;

/**
 *
 * @author Lionel Bouzonville
 */
class PTTagType extends BasePTTagType
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
