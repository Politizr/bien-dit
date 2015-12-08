<?php

namespace Politizr\AdminBundle\Form\Model;

use DateTime;

class DateRange
{
    /**
     * @var DateTime
     */
    public $start;

    /**
     * @var DateTime
     */
    public $end;

    public function __construct(DateTime $start = null, DateTime $end = null)
    {
        if (!$start) {
            $start = new DateTime();
            $start->setTime(0, 0, 0);
        }

        if (!$end) {
            $end = new DateTime();
            $end->setTime(23, 59, 59);
        }

        $this->start = $start;
        $this->end = $end;
    }
}
