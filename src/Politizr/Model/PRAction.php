<?php

namespace Politizr\Model;

use Politizr\Model\om\BasePRAction;

/**
 *
 * @author Lionel Bouzonville
 */
class PRAction extends BasePRAction
{
    /**
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getInitials();
    }
}
