<?php

namespace Politizr\Model;

use Politizr\Model\om\BasePTTagTypeQuery;

/**
 *
 * @author Lionel Bouzonville
 */
class PTTagTypeQuery extends BasePTTagTypeQuery
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
