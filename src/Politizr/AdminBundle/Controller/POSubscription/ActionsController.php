<?php

namespace Politizr\AdminBundle\Controller\POSubscription;

use Admingenerated\PolitizrAdminBundle\BasePOSubscriptionController\ActionsController as BaseActionsController;

/**
 * ActionsController
 */
class ActionsController extends BaseActionsController
{
    /**
     * 
     */
    protected function executeObjectMoveUp(\Politizr\Model\POSubscription $POSubscription) {
        $POSubscription->moveUp();
        $POSubscription->save();
    }

    /**
     * 
     */
    protected function successObjectMoveup(\Politizr\Model\POSubscription $POSubscription)
    {
        $this->get('session')->getFlashBag()->add(
            'success',
            'La position a été mise à jour avec succès'
        );

        return new \Symfony\Component\HttpFoundation\RedirectResponse($this->generateUrl("Politizr_AdminBundle_POSubscription_list"));
    }
    
    /**
     * 
     */
    protected function executeObjectMoveDown(\Politizr\Model\POSubscription $POSubscription) {
        $POSubscription->moveDown();
        $POSubscription->save();
    }
    
    /**
     * 
     */
    protected function successObjectMovedown(\Politizr\Model\POSubscription $POSubscription)
    {
        $this->get('session')->getFlashBag()->add(
            'success',
            'La position a été mise à jour avec succès'
        );

        return new \Symfony\Component\HttpFoundation\RedirectResponse($this->generateUrl("Politizr_AdminBundle_POSubscription_list"));
    }
}
