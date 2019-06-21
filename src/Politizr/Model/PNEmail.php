<?php

namespace Politizr\Model;

use Politizr\Model\om\BasePNEmail;

class PNEmail extends BasePNEmail
{
    public function __toString() {
        return $this->getTitle();
    }
}
