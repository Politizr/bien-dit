<?php

namespace Politizr\AdminBundle\Controller\PCircle;

use Admingenerated\PolitizrAdminBundle\BasePCircleController\ActionsController as BaseActionsController;

use Politizr\Model\PCircle;
/**
 * ActionsController
 */
class ActionsController extends BaseActionsController
{
    /**
     *
     */
    protected function executeObjectMoveUp(PCircle $PCircle)
    {
        $PCircle->moveUp();
        $PCircle->save();
    }

    /**
     *
     */
    protected function successObjectMoveup(PCircle $PCircle)
    {
        $this->get('session')->getFlashBag()->add(
            'success',
            'La position a été mise à jour avec succès'
        );

        return new \Symfony\Component\HttpFoundation\RedirectResponse($this->generateUrl("Politizr_AdminBundle_PCircle_list"));
    }
    
    /**
     *
     */
    protected function executeObjectMoveDown(PCircle $PCircle)
    {
        $PCircle->moveDown();
        $PCircle->save();
    }
    
    /**
     *
     */
    protected function successObjectMovedown(PCircle $PCircle)
    {
        $this->get('session')->getFlashBag()->add(
            'success',
            'La position a été mise à jour avec succès'
        );

        return new \Symfony\Component\HttpFoundation\RedirectResponse($this->generateUrl("Politizr_AdminBundle_PCircle_list"));
    }
}
