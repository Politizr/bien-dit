<?php

namespace Politizr\Model;

use Politizr\Model\om\BasePUStatus;

/**
 *
 * @author Lionel Bouzonville
 */
class PUStatus extends BasePUStatus
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
