<?php

namespace Politizr\AdminBundle\Controller\CmsContent;

use Admingenerated\PolitizrAdminBundle\BaseCmsContentController\ActionsController as BaseActionsController;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

use Politizr\Constant\PathConstants;

use Politizr\Model\CmsContent;

/**
 * ActionsController
 */
class ActionsController extends BaseActionsController
{
    /**
     *
     */
    protected function executeObjectMoveUp(CmsContent $CmsContent)
    {
        $CmsContent->moveUp();
        $CmsContent->save();
    }

    /**
     *
     */
    protected function successObjectMoveup(CmsContent $CmsContent)
    {
        $this->get('session')->getFlashBag()->add(
            'success',
            'La position a été mise à jour avec succès'
        );

        return new RedirectResponse($this->generateUrl("Politizr_AdminBundle_CmsContent_list"));
    }
    
    /**
     *
     */
    protected function executeObjectMoveDown(CmsContent $CmsContent)
    {
        $CmsContent->moveDown();
        $CmsContent->save();
    }
    
    /**
     *
     */
    protected function successObjectMovedown(CmsContent $CmsContent)
    {
        $this->get('session')->getFlashBag()->add(
            'success',
            'La position a été mise à jour avec succès'
        );

        return new RedirectResponse($this->generateUrl("Politizr_AdminBundle_CmsContent_list"));
    }
}
