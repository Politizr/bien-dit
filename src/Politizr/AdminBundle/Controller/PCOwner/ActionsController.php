<?php

namespace Politizr\AdminBundle\Controller\PCOwner;

use Admingenerated\PolitizrAdminBundle\BasePCOwnerController\ActionsController as BaseActionsController;

use Symfony\Component\HttpFoundation\RedirectResponse;

use Politizr\Model\PCOwner;

/**
 * ActionsController
 */
class ActionsController extends BaseActionsController
{
    /**
     *
     */
    protected function executeObjectMoveUp(PCOwner $PCOwner)
    {
        $PCOwner->moveUp();
        $PCOwner->save();
    }

    /**
     *
     */
    protected function successObjectMoveup(PCOwner $PCOwner)
    {
        $this->get('session')->getFlashBag()->add(
            'success',
            'La position a été mise à jour avec succès'
        );

        return new RedirectResponse($this->generateUrl("Politizr_AdminBundle_PCOwner_list"));
    }
    
    /**
     *
     */
    protected function executeObjectMoveDown(PCOwner $PCOwner)
    {
        $PCOwner->moveDown();
        $PCOwner->save();
    }
    
    /**
     *
     */
    protected function successObjectMovedown(PCOwner $PCOwner)
    {
        $this->get('session')->getFlashBag()->add(
            'success',
            'La position a été mise à jour avec succès'
        );

        return new RedirectResponse($this->generateUrl("Politizr_AdminBundle_PCOwner_list"));
    }

}
