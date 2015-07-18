<?php

namespace Politizr\Model;

use Politizr\Model\om\BasePOPaymentState;

/**
 *
 * @author Lionel Bouzonville
 */
class POPaymentState extends BasePOPaymentState
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
