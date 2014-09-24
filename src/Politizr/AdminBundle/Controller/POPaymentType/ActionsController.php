<?php

namespace Politizr\AdminBundle\Controller\POPaymentType;

use Admingenerated\PolitizrAdminBundle\BasePOPaymentTypeController\ActionsController as BaseActionsController;

/**
 * ActionsController
 */
class ActionsController extends BaseActionsController
{
    /**
     * 
     */
    protected function executeObjectMoveUp(\Politizr\Model\POPaymentType $POPaymentType) {
        $POPaymentType->moveUp();
        $POPaymentType->save();
    }

    /**
     * 
     */
    protected function successObjectMoveup(\Politizr\Model\POPaymentType $POPaymentType)
    {
        $this->get('session')->getFlashBag()->add(
            'success',
            'La position a été mise à jour avec succès'
        );

        return new \Symfony\Component\HttpFoundation\RedirectResponse($this->generateUrl("Politizr_AdminBundle_POPaymentType_list"));
    }
    
    /**
     * 
     */
    protected function executeObjectMoveDown(\Politizr\Model\POPaymentType $POPaymentType) {
        $POPaymentType->moveDown();
        $POPaymentType->save();
    }
    
    /**
     * 
     */
    protected function successObjectMovedown(\Politizr\Model\POPaymentType $POPaymentType)
    {
        $this->get('session')->getFlashBag()->add(
            'success',
            'La position a été mise à jour avec succès'
        );

        return new \Symfony\Component\HttpFoundation\RedirectResponse($this->generateUrl("Politizr_AdminBundle_POPaymentType_list"));
    }
}
