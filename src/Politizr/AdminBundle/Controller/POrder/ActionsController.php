<?php

namespace Politizr\AdminBundle\Controller\POrder;

use Admingenerated\PolitizrAdminBundle\BasePOrderController\ActionsController as BaseActionsController;

use Symfony\Component\EventDispatcher\GenericEvent;

use Politizr\Model\POrderQuery;

use Politizr\Exception\InconsistentDataException;

use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * ActionsController
 */
class ActionsController extends BaseActionsController
{
    /**
     * Generation facture.
     * 
     * @return \Politizr\AdminBundle\Controller\Customer\Response
     */
    public function invoiceAction($pk) {
        $logger = $this->get('logger');
        $logger->info('*** invoiceAction');

        $order = POrderQuery::create()->findPk($pk);
        if (!$order) {
            throw new InconsistentDataException('POrder pk-'.$pk.' not found.');
        }

        try {
            // Gestion des emails de confirmation
            $dispatcher = $this->get('event_dispatcher')->dispatch('order_pdf', new GenericEvent($order));        
            $this->get('session')->getFlashBag()->add('success', 'La facture a bien été générée.');
        } catch (\Exception $e) {
            $this->get('session')->getFlashBag()->add('error', $e->getMessage());
        }

        return new RedirectResponse($this->generateUrl("Politizr_AdminBundle_POrder_edit", array('pk' => $pk) ));
    }

    /**
     * Mail client
     * 
     * @return \Politizr\AdminBundle\Controller\Customer\Response
     */
    public function mailAction($pk) {
        $logger = $this->get('logger');
        $logger->info('*** mailAction');

        $order = POrderQuery::create()->findPk($pk);
        if (!$order) {
            throw new InconsistentDataException('POrder pk-'.$pk.' not found.');
        }

        try {
            // Gestion des emails de confirmation
            $dispatcher = $this->get('event_dispatcher')->dispatch('order_email', new GenericEvent($order));
            $this->get('session')->getFlashBag()->add('success', 'L\'email a bien été envoyé.');
        } catch (\Exception $e) {
            $this->get('session')->getFlashBag()->add('error', $e->getMessage());
        }

        return new RedirectResponse($this->generateUrl("Politizr_AdminBundle_POrder_edit", array('pk' => $pk) ));
    }
}
