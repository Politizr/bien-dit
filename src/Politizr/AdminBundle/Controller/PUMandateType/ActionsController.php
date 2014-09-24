<?php

namespace Politizr\AdminBundle\Controller\PUMandateType;

use Admingenerated\PolitizrAdminBundle\BasePUMandateTypeController\ActionsController as BaseActionsController;

/**
 * ActionsController
 */
class ActionsController extends BaseActionsController
{
    /**
     * 
     */
    protected function executeObjectMoveUp(\Politizr\Model\PUMandateType $PUMandateType) {
        $PUMandateType->moveUp();
        $PUMandateType->save();
    }

    /**
     * 
     */
    protected function successObjectMoveup(\Politizr\Model\PUMandateType $PUMandateType)
    {
        $this->get('session')->getFlashBag()->add(
            'success',
            'La position a été mise à jour avec succès'
        );

        return new \Symfony\Component\HttpFoundation\RedirectResponse($this->generateUrl("Politizr_AdminBundle_PUMandateType_list"));
    }
    
    /**
     * 
     */
    protected function executeObjectMoveDown(\Politizr\Model\PUMandateType $PUMandateType) {
        $PUMandateType->moveDown();
        $PUMandateType->save();
    }
    
    /**
     * 
     */
    protected function successObjectMovedown(\Politizr\Model\PUMandateType $PUMandateType)
    {
        $this->get('session')->getFlashBag()->add(
            'success',
            'La position a été mise à jour avec succès'
        );

        return new \Symfony\Component\HttpFoundation\RedirectResponse($this->generateUrl("Politizr_AdminBundle_PUMandateType_list"));
    }
}
