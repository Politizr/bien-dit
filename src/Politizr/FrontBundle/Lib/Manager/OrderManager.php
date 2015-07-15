<?php
namespace Politizr\FrontBundle\Lib\Manager;

use Politizr\Exception\InconsistentDataException;

use Politizr\Model\POrder;

/**
 * DB manager service for order.
 *
 * @author Lionel Bouzonville
 */
class OrderManager
{
    private $sc;

    /**
     *
     */
    public function __construct($serviceContainer)
    {
        $this->sc = $serviceContainer;
    }

    /**
     * Delete user's order
     *
     * @param POrder $order
     * @return integer
     */
    public function deleteOrder(POrder $order)
    {
        $result = $order->delete();

        return $result;
    }
}
