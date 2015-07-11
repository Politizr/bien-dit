<?php

namespace Politizr\Model;

use Politizr\Model\om\BasePOrderQuery;

use Politizr\Model\POSubscriptionQuery;
use Politizr\Model\POPaymentTypeQuery;

use Politizr\Model\POrder;
use Politizr\Model\POOrderState;
use Politizr\Model\POPaymentState;
use Politizr\Model\POPaymentType;

/**
 *
 *
 *
 * @author Lionel Bouzonville
 */
class POrderQuery extends BasePOrderQuery
{

    /**
     *  Commande / Construction d'un objet POrder
     *
     * @param $user
     * @param $subscription
     * @param $paymentTypeId
     * @param $supportingDocument
     * @param $electiveMandates
     * 
     * @return  POrder     newly created object
     */
    public static function createOrder($user, $subscription, $paymentTypeId, $supportingDocument, $electiveMandates) {
        // Création objet POrder & MAJ des attributs
        $order = new POrder();

        $order->setPUserId($user->getId());
        $order->setPOSubscriptionId($subscription->getId());
        $order->setPOPaymentTypeId($paymentTypeId);

        $order->setPOOrderStateId(POOrderState::CREATED);
        $order->setPOOrderStateId(POPaymentState::PROCESSING);

        $order->setSubscriptionTitle($subscription->getTitle());
        $order->setSubscriptionDescription($subscription->getDescription());

        $order->setGender($user->getGender());
        $order->setFirstname($user->getFirstname());
        $order->setName($user->getName());
        $order->setPhone($user->getPhone());
        $order->setEmail($user->getEmail());

        $order->setElectiveMandates($electiveMandates);
        $order->setSupportingDocument($supportingDocument);

        // @todo > gestion prix & promo
        $order->setPrice($subscription->getPrice());
        $order->setPromotion(0);
        $order->setTotal($subscription->getPrice());

        $order->save();

        return $order;
    }

}
