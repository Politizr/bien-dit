<?php

namespace Politizr\Model;

use Politizr\Model\om\BasePMAskForUpdate;

use Politizr\Constant\ObjectTypeConstants;

class PMAskForUpdate extends BasePMAskForUpdate implements PMonitoredInterface
{
    /**
     * @see PMonitoredInterface::getType()
     */
    public function getType()
    {
        return ObjectTypeConstants::TYPE_ASK_FOR_UPDATE;
    }
}
