<?php

namespace Politizr\AdminBundle\Controller\CmsInfo;

use Admingenerated\PolitizrAdminBundle\BaseCmsInfoController\ActionsController as BaseActionsController;

use Symfony\Component\HttpFoundation\RedirectResponse;

use Politizr\Model\CmsInfo;

/**
 * ActionsController
 */
class ActionsController extends BaseActionsController
{
    /**
     *
     */
    protected function executeObjectMoveUp(CmsInfo $CmsInfo)
    {
        $CmsInfo->moveUp();
        $CmsInfo->save();
    }

    /**
     *
     */
    protected function successObjectMoveup(CmsInfo $CmsInfo)
    {
        $this->get('session')->getFlashBag()->add(
            'success',
            'La position a été mise à jour avec succès'
        );

        return new RedirectResponse($this->generateUrl("Politizr_AdminBundle_CmsInfo_list"));
    }
    
    /**
     *
     */
    protected function executeObjectMoveDown(CmsInfo $CmsInfo)
    {
        $CmsInfo->moveDown();
        $CmsInfo->save();
    }
    
    /**
     *
     */
    protected function successObjectMovedown(CmsInfo $CmsInfo)
    {
        $this->get('session')->getFlashBag()->add(
            'success',
            'La position a été mise à jour avec succès'
        );

        return new RedirectResponse($this->generateUrl("Politizr_AdminBundle_CmsInfo_list"));
    }
}
