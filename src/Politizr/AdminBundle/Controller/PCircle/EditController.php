<?php

namespace Politizr\AdminBundle\Controller\PCircle;

use Admingenerated\PolitizrAdminBundle\BasePCircleController\EditController as BaseEditController;

use Symfony\Component\Form\Form;

use Politizr\Model\PCircle;

/**
 * EditController
 */
class EditController extends BaseEditController
{
    /**
     * Manage file upload
     *
     * @param \Symfony\Component\Form\Form $form the valid form
     * @param \App\Model\PCircle $currentObject object
     */
    public function preSave(Form $form, PCircle $currentObject)
    {
        $file = $form['uploadedLogoFileName']->getData();
        if ($file) {
            $currentObject->removeUpload(true);
            $fileName = $currentObject->upload($file);
            $currentObject->setLogoFileName($fileName);
        }
    }
}
