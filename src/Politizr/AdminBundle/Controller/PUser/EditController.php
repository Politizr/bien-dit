<?php

namespace Politizr\AdminBundle\Controller\PUser;

use Admingenerated\PolitizrAdminBundle\BasePUserController\EditController as BaseEditController;

/**
 * EditController
 */
class EditController extends BaseEditController
{
    /**
     * Manage file upload
     *
     * @param \Symfony\Component\Form\Form $form the valid form
     * @param \AgneauPyrenees\Model\ContentDish $currentObject object
     */
    public function preSave(\Symfony\Component\Form\Form $form, \Politizr\Model\PUser $currentObject)
    {
    	$logger = $this->get('logger');
    	$logger->info('*** post datas '.print_r($form->getData(), true));

        $file = $form['uploaded_file_name']->getData();
        if ($file) {
          $currentObject->removeUpload(true, false);
          $fileName = $currentObject->upload($file);
          $currentObject->setFileName($fileName);
        }

        $file = $form['uploaded_supporting_document']->getData();
        if ($file) {
          $currentObject->removeUpload(false, true);
          $fileName = $currentObject->upload($file);
          $currentObject->setSupportingDocument($fileName);
        }
    }
}
