<?php

namespace Politizr\AdminBundle\Controller\PUPoliticalParty;

use Admingenerated\PolitizrAdminBundle\BasePUPoliticalPartyController\EditController as BaseEditController;

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
    public function preSave(\Symfony\Component\Form\Form $form, \Politizr\Model\PUPoliticalParty $currentObject)
    {
    	// $logger = $this->get('logger');
    	// $logger->info('*** post datas '.print_r($form->getData(), true));

        $file = $form['uploadedFileName']->getData();
        if ($file) {
          $currentObject->removeUpload(true);
          $fileName = $currentObject->upload($file);
          $currentObject->setFileName($fileName);
        }
    }
}