<?php

namespace Politizr\AdminBundle\Controller\PRBadge;

use Admingenerated\PolitizrAdminBundle\BasePRBadgeController\EditController as BaseEditController;

/**
 * EditController
 */
class EditController extends BaseEditController
{
    /**
     * Manage file upload
     *
     * @param \Symfony\Component\Form\Form $form the valid form
     * @param \Politizr\Model\PRBadge $currentObject object
     */
    public function preSave(\Symfony\Component\Form\Form $form, \Politizr\Model\PRBadge $currentObject)
    {
        $file = $form['uploadedFileName']->getData();
        if ($file) {
            $currentObject->removeUpload(true);
            $fileName = $currentObject->upload($file);
            $currentObject->setFileName($fileName);
        } elseif ($file['delete']) {
            $currentObject->removeUpload(true);
        }
    }
}
