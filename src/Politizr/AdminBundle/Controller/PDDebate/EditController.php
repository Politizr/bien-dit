<?php

namespace Politizr\AdminBundle\Controller\PDDebate;

use Symfony\Component\HttpFoundation\Request;

use Admingenerated\PolitizrAdminBundle\BasePDDebateController\EditController as BaseEditController;

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
    public function preSave(\Symfony\Component\Form\Form $form, \Politizr\Model\PDDebate $currentObject)
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
