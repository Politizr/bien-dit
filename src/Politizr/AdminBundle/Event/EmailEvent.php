<?php
namespace Politizr\AdminBundle\Event;

use Symfony\Component\EventDispatcher\Event;


/**
 *
 */
class EmailEvent extends Event
{
    protected $pOrder = null;
    protected $invoiceDir = null;

    public function setPOrder($pOrder)
    {
        $this->pOrder = $pOrder;
    }

    public function setInvoiceDir($invoiceDir)
    {
        $this->invoiceDir = $invoiceDir;
    }


    public function getPOrder()
    {
        return $this->pOrder;
    }

    public function getInvoiceDir()
    {
        return $this->invoiceDir;
    }
}