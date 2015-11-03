<?php

namespace Politizr\AdminBundle\Controller\PQOrganization;

use Admingenerated\PolitizrAdminBundle\BasePQOrganizationController\ActionsController as BaseActionsController;

/**
 * ActionsController
 */
class ActionsController extends BaseActionsController
{
    /**
     *
     */
    protected function executeObjectMoveUp(\Politizr\Model\PQOrganization $PQOrganization)
    {
        $PQOrganization->moveUp();
        $PQOrganization->save();
    }

    /**
     *
     */
    protected function successObjectMoveup(\Politizr\Model\PQOrganization $PQOrganization)
    {
        $this->get('session')->getFlashBag()->add(
            'success',
            'La position a été mise à jour avec succès'
        );

        return new \Symfony\Component\HttpFoundation\RedirectResponse($this->generateUrl("Politizr_AdminBundle_PQOrganization_list"));
    }
    
    /**
     *
     */
    protected function executeObjectMoveDown(\Politizr\Model\PQOrganization $PQOrganization)
    {
        $PQOrganization->moveDown();
        $PQOrganization->save();
    }
    
    /**
     *
     */
    protected function successObjectMovedown(\Politizr\Model\PQOrganization $PQOrganization)
    {
        $this->get('session')->getFlashBag()->add(
            'success',
            'La position a été mise à jour avec succès'
        );

        return new \Symfony\Component\HttpFoundation\RedirectResponse($this->generateUrl("Politizr_AdminBundle_PQOrganization_list"));
    }
}
