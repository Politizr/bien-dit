<?php

namespace Politizr\AdminBundle\Controller\PCTopic;

use Admingenerated\PolitizrAdminBundle\BasePCTopicController\EditController as BaseEditController;

use Symfony\Component\Form\Form;

use Politizr\Model\PCTopic;

/**
 * EditController
 */
class EditController extends BaseEditController
{
    /**
     * Manage file upload
     *
     * @param \Symfony\Component\Form\Form $form the valid form
     * @param \App\Model\PCTopic $currentObject object
     */
    public function preSave(Form $form, PCTopic $currentObject)
    {
        $file = $form['uploadedFileName']->getData();
        if ($file) {
            $currentObject->removeUpload(true);
            $fileName = $currentObject->upload($file);
            $currentObject->setFileName($fileName);
        }
    }
}
