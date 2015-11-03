<?php
namespace Politizr\FrontBundle\Lib\Manager;

use Politizr\Exception\InconsistentDataException;

use Politizr\Constant\OrderConstants;

use Politizr\Model\PUser;
use Politizr\Model\POSubscription;
use Politizr\Model\POrder;
use Politizr\Model\POPaymentType;

/**
 * DB manager service for order.
 *
 * @author Lionel Bouzonville
 */
class OrderManager
{
    private $logger;

    /**
     *
     * @param @logger
     */
    public function __construct($logger)
    {
        $this->logger = $logger;
    }

    /**
     * Create new order
     *
     * @param PUser $user
     * @param POSubscription $subscription
     * @param integer $paymentTypeId
     * @param string $supportingDocument
     * @param string $electiveMandates
     * @return POrder
     */
    public static function createOrder(PUSer $user, POSubscription $subscription, $paymentTypeId, $supportingDocument, $electiveMandates)
    {
        if (!$user) {
            throw new InconsistentDataException('Cannot create order if user is null');
        }
        if (!$subscription) {
            throw new InconsistentDataException('Cannot create order if subscription is null');
        }

        $order = new POrder();

        $order->setPUserId($user->getId());
        $order->setPOSubscriptionId($subscription->getId());
        $order->setPOPaymentTypeId($paymentTypeId);

        $order->setPOOrderStateId(OrderConstants::ORDER_CREATED);
        $order->setPOOrderStateId(OrderConstants::PAYMENT_PROCESSING);

        $order->setSubscriptionTitle($subscription->getTitle());
        $order->setSubscriptionDescription($subscription->getDescription());

        $order->setGender($user->getGender());
        $order->setFirstname($user->getFirstname());
        $order->setName($user->getName());
        $order->setPhone($user->getPhone());
        $order->setEmail($user->getEmail());

        $order->setElectiveMandates($electiveMandates);
        $order->setSupportingDocument($supportingDocument);

        // @todo promo
        $order->setPrice($subscription->getPrice());
        $order->setPromotion(0);
        $order->setTotal($subscription->getPrice());

        $order->save();

        return $order;
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
