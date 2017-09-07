<?php

namespace Politizr\AdminBundle\Controller\PEOperation;

use Admingenerated\PolitizrAdminBundle\BasePEOperationController\EditController as BaseEditController;

use Symfony\Component\Form\Form;

use Politizr\Model\PEOperation;

/**
 * EditController
 */
class EditController extends BaseEditController
{
    /**
     * Manage file upload
     *
     * @param \Symfony\Component\Form\Form $form the valid form
     * @param \App\Model\PEOperation $currentObject object
     */
    public function preSave(Form $form, PEOperation $currentObject)
    {
        $file = $form['uploadedFileName']->getData();
        if ($file) {
            $currentObject->removeUpload(true);
            $fileName = $currentObject->upload($file);
            $currentObject->setFileName($fileName);
        }
    }
}