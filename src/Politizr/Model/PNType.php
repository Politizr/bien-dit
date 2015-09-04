<?php

namespace Politizr\Model;

use Politizr\Model\om\BasePNType;

class PNType extends BasePNType
{
    /**
     *
     * @return PropelCollection[PNotification]
     */
    public function getNotifications()
    {
        $query = PNotificationQuery::create()
            ->filterByOnline(true);

        return parent::getPNotifications($query);
    }
}
