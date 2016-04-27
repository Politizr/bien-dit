<?php

namespace Politizr\Model;

use Politizr\Model\om\BasePNType;

class PNType extends BasePNType
{
    /**
     *
     */
    public function __toString()
    {
        return $this->getTitle();
    }

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
