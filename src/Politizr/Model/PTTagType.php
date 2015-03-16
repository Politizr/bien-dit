<?php

namespace Politizr\Model;

use Politizr\Model\om\BasePTTagType;

class PTTagType extends BasePTTagType
{
    // ************************************************************************************ //
    //                                        CONSTANTES
    // ************************************************************************************ //
    const TYPE_GEO = 1;
    const TYPE_THEME = 2;

    // ************************************************************************************ //

    /**
     *
     */
    public function __toString() {
        return $this->getTitle();
    }

    /*************** ADMIN GENERATOR VIRTUAL FIELDS HACK **************************/
    public function getBlockTags() {
    }
}
