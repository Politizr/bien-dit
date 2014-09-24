<?php

namespace Politizr\AdminBundle\Controller\PUPoliticalParty;

use Admingenerated\PolitizrAdminBundle\BasePUPoliticalPartyController\ActionsController as BaseActionsController;

/**
 * ActionsController
 */
class ActionsController extends BaseActionsController
{
    /**
     * 
     */
    protected function executeObjectMoveUp(\Politizr\Model\POPoliticalParty $POPoliticalParty) {
        $POPoliticalParty->moveUp();
        $POPoliticalParty->save();
    }

    /**
     * 
     */
    protected function successObjectMoveup(\Politizr\Model\POPoliticalParty $POPoliticalParty)
    {
        $this->get('session')->getFlashBag()->add(
            'success',
            'La position a été mise à jour avec succès'
        );

        return new \Symfony\Component\HttpFoundation\RedirectResponse($this->generateUrl("Politizr_AdminBundle_POPoliticalParty_list"));
    }
    
    /**
     * 
     */
    protected function executeObjectMoveDown(\Politizr\Model\POPoliticalParty $POPoliticalParty) {
        $POPoliticalParty->moveDown();
        $POPoliticalParty->save();
    }
    
    /**
     * 
     */
    protected function successObjectMovedown(\Politizr\Model\POPoliticalParty $POPoliticalParty)
    {
        $this->get('session')->getFlashBag()->add(
            'success',
            'La position a été mise à jour avec succès'
        );

        return new \Symfony\Component\HttpFoundation\RedirectResponse($this->generateUrl("Politizr_AdminBundle_POPoliticalParty_list"));
    }
}
