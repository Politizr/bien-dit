<?php

namespace Politizr\Model;

use Politizr\Model\om\BasePMAbuseReporting;

use Politizr\Constant\ObjectTypeConstants;

class PMAbuseReporting extends BasePMAbuseReporting implements PMonitoredInterface
{

    /**
     * @see PMonitoredInterface::getType()
     */
    public function getType()
    {
        return ObjectTypeConstants::TYPE_ABUSE;
    }
}
