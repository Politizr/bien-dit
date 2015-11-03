<?php

namespace Politizr\Model;

use Politizr\Model\om\BasePOPaymentType;

/**
 *
 * @author Lionel Bouzonville
 */
class POPaymentType extends BasePOPaymentType
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
