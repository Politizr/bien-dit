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
     * @param $pUser
     * @param $pOSubscriptionId
     * @param $pOPaymentTypeId
     * @param $pOPaymentStateId
     * @param $pOOrderStateId
     * @param $pOSubscriptionId
     * 
     * @return  POrder     newly created object
     */
    public static function createPOrder($pUser, $pOSubscriptionId, $pOPaymentTypeId) {

    	// Contrôle objet user
    	if(!$pUser) {
    		// TODO: maj type exception
    		throw new \Exception('pUser null.');
    	}
    	if (!$pUser instanceof PUser) {
    		// TODO: maj type exception
    		throw new \Exception('pUser not instance of PUser.');
    	}

    	// Récupération type de souscription & contrôle validité
    	$pOSubscription = POSubscriptionQuery::create()->findPk($pOSubscriptionId);
    	if(!$pOSubscription) {
    		// TODO: maj type exception
    		throw new \Exception('POSubscription id '.$pOSubscriptionId.' not found.');
    	}
    	if(!$pOSubscription->getOnline()) {
    		// TODO: maj type exception
    		throw new \Exception('POSubscription id '.$pOSubscriptionId.' not online.');
    	}

    	// Récupération type de paiement & contrôle validité
    	$pOPaymentType = POPaymentTypeQuery::create()->findPk($pOPaymentTypeId);
    	if(!$pOPaymentType) {
    		// TODO: maj type exception
    		throw new \Exception('POPaymentType id '.$pOPaymentTypeId.' not found.');
    	}
    	if(!$pOPaymentType->getOnline()) {
    		// TODO: maj type exception
    		throw new \Exception('POPaymentType id '.$pOPaymentTypeId.' not online.');
    	}

    	// Création objet POrder & MAJ des attributs
    	$pOrder = new POrder();

    	$pOrder->setPUserId($pUser->getId());
    	$pOrder->setPOSubscriptionId($pOSubscription->getId());
    	$pOrder->setPOPaymentTypeId($pOPaymentType->getId());

    	$pOrder->setPOOrderStateId(POOrderState::STATE_CREATE);
    	$pOrder->setPOOrderStateId(POPaymentState::STATE_PROCESS);

    	$pOrder->setSubscriptionTitle($pOSubscription->getTitle());
    	$pOrder->setSubscriptionDescription($pOSubscription->getDescription());

    	$pOrder->setGender($pUser->getGender());
    	$pOrder->setFirstname($pUser->getFirstname());
    	$pOrder->setName($pUser->getName());
    	$pOrder->setPhone($pUser->getPhone());
    	$pOrder->setEmail($pUser->getEmail());
    	$pOrder->setElectiveMandates($pUser->getElectiveMandates());

    	$pOrder->save();

    	return $pOrder;
    }

}
