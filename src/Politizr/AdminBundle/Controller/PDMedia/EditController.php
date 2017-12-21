<?php

namespace Politizr\AdminBundle\Controller\PDMedia;

use Admingenerated\PolitizrAdminBundle\BasePDMediaController\EditController as BaseEditController;

use Symfony\Component\Form\Form;

use Politizr\Model\PDMedia;

/**
 * EditController
 */
class EditController extends BaseEditController
{
    /**
     * Manage file upload
     *
     * @param \Symfony\Component\Form\Form $form the valid form
     * @param \App\Model\PDMedia $currentObject object
     */
    public function preSave(Form $form, PDMedia $currentObject)
    {
        $file = $form['uploadedFileName']->getData();
        if ($file) {
            $currentObject->removeUpload(true);
            $fileName = $currentObject->upload($file);
            $currentObject->setFileName($fileName);
        }
    }
}
