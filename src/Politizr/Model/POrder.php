<?php

namespace Politizr\Model;

use Politizr\Model\om\BasePOrder;

class POrder extends BasePOrder
{
	/*************** ADMIN GENERATOR VIRTUAL FIELDS HACK **************************/
	public function getBlockInvoice() {
	}
	public function getBlockMail() {
	}
}
