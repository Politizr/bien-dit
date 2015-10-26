<?php

namespace Politizr\AdminBundle\Controller\PQMandate;

use Admingenerated\PolitizrAdminBundle\BasePQMandateController\ActionsController as BaseActionsController;

/**
 * ActionsController
 */
class ActionsController extends BaseActionsController
{
    /**
     * 
     */
    protected function executeObjectMoveUp(\Politizr\Model\PQMandate $PQMandate) {
        $PQMandate->moveUp();
        $PQMandate->save();
    }

    /**
     * 
     */
    protected function successObjectMoveup(\Politizr\Model\PQMandate $PQMandate)
    {
        $this->get('session')->getFlashBag()->add(
            'success',
            'La position a été mise à jour avec succès'
        );

        return new \Symfony\Component\HttpFoundation\RedirectResponse($this->generateUrl("Politizr_AdminBundle_PQMandate_list"));
    }
    
    /**
     * 
     */
    protected function executeObjectMoveDown(\Politizr\Model\PQMandate $PQMandate) {
        $PQMandate->moveDown();
        $PQMandate->save();
    }
    
    /**
     * 
     */
    protected function successObjectMovedown(\Politizr\Model\PQMandate $PQMandate)
    {
        $this->get('session')->getFlashBag()->add(
            'success',
            'La position a été mise à jour avec succès'
        );

        return new \Symfony\Component\HttpFoundation\RedirectResponse($this->generateUrl("Politizr_AdminBundle_PQMandate_list"));
    }
}
