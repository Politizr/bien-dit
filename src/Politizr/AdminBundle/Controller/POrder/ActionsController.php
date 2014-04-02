<?php

namespace Politizr\AdminBundle\Controller\POrder;

use Admingenerated\PolitizrAdminBundle\BasePOrderController\ActionsController as BaseActionsController;

use Politizr\AdminBundle\PolitizrAdminEvents;
use Politizr\AdminBundle\Event\EmailEvent;
use Politizr\AdminBundle\Event\PDFEvent;

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

        // génération du pdf
        $event = new PDFEvent();
        $event->setPOrder($order);
        $event->setInvoiceDir($this->get('kernel')->getRootDir() . '/../web/uploads/invoices/');
        $this->get('event_dispatcher')->dispatch(
            PolitizrAdminEvents::PDF_ORDER_CUSTOMER, $event
        );
            
        $this->get('session')->getFlashBag()->add('success', 'La facture a bien été générée.');
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

        // envoi de l'email
        $event = new EmailEvent();
        $event->setPOrder($order);
        $event->setInvoiceDir($this->get('kernel')->getRootDir() . '/../web/uploads/invoices/');
        $this->get('event_dispatcher')->dispatch(
            PolitizrAdminEvents::EMAIL_ORDER_CUSTOMER, $event
        );

        $this->get('session')->getFlashBag()->add('success', 'L\'email a bien été envoyé.');
        return new RedirectResponse($this->generateUrl("Politizr_AdminBundle_POrder_edit", array('pk' => $pk) ));
    }
}
