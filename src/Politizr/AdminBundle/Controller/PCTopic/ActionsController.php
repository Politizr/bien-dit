<?php

namespace Politizr\AdminBundle\Controller\PCTopic;

use Admingenerated\PolitizrAdminBundle\BasePCTopicController\ActionsController as BaseActionsController;

use Politizr\Model\PCTopic;

/**
 * ActionsController
 */
class ActionsController extends BaseActionsController
{
    /**
     *
     */
    protected function executeObjectMoveUp(PCTopic $PCTopic)
    {
        $PCTopic->moveUp();
        $PCTopic->save();
    }

    /**
     *
     */
    protected function successObjectMoveup(PCTopic $PCTopic)
    {
        $this->get('session')->getFlashBag()->add(
            'success',
            'La position a été mise à jour avec succès'
        );

        return new \Symfony\Component\HttpFoundation\RedirectResponse($this->generateUrl("Politizr_AdminBundle_PCTopic_list"));
    }
    
    /**
     *
     */
    protected function executeObjectMoveDown(PCTopic $PCTopic)
    {
        $PCTopic->moveDown();
        $PCTopic->save();
    }
    
    /**
     *
     */
    protected function successObjectMovedown(PCTopic $PCTopic)
    {
        $this->get('session')->getFlashBag()->add(
            'success',
            'La position a été mise à jour avec succès'
        );

        return new \Symfony\Component\HttpFoundation\RedirectResponse($this->generateUrl("Politizr_AdminBundle_PCTopic_list"));
    }
}
