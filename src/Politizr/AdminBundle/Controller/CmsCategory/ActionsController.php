<?php

namespace Politizr\AdminBundle\Controller\CmsCategory;

use Symfony\Component\HttpFoundation\RedirectResponse;

use Politizr\Model\CmsCategory;

use Admingenerated\PolitizrAdminBundle\BaseCmsCategoryController\ActionsController as BaseActionsController;

/**
 * ActionsController
 */
class ActionsController extends BaseActionsController
{
    /**
     *
     */
    protected function executeObjectMoveUp(CmsCategory $CmsCategory)
    {
        $CmsCategory->moveUp();
        $CmsCategory->save();
    }

    /**
     *
     */
    protected function successObjectMoveup(CmsCategory $CmsCategory)
    {
        $this->get('session')->getFlashBag()->add(
            'success',
            'La position a été mise à jour avec succès'
        );

        return new RedirectResponse($this->generateUrl("Politizr_AdminBundle_CmsCategory_list"));
    }
    
    /**
     *
     */
    protected function executeObjectMoveDown(CmsCategory $CmsCategory)
    {
        $CmsCategory->moveDown();
        $CmsCategory->save();
    }
    
    /**
     *
     */
    protected function successObjectMovedown(CmsCategory $CmsCategory)
    {
        $this->get('session')->getFlashBag()->add(
            'success',
            'La position a été mise à jour avec succès'
        );

        return new RedirectResponse($this->generateUrl("Politizr_AdminBundle_CmsCategory_list"));
    }
}
