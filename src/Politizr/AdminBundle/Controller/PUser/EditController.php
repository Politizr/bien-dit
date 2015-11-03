<?php

namespace Politizr\AdminBundle\Controller\PUser;

use Admingenerated\PolitizrAdminBundle\BasePUserController\EditController as BaseEditController;

use Symfony\Component\Form\Form;

use Politizr\Model\PUser;

/**
 * EditController
 */
class EditController extends BaseEditController
{
    /**
     * @see Admingenerated\PolitizrAdminBundle\BasePUserController\EditController
     */
    protected function preSave(Form $form, PUser $user)
    {
        // $logger = $this->get('logger');
        // $logger->info('*** post datas '.print_r($form->getData(), true));

        $userManager = $this->get('politizr.manager.user');

        $userManager->updateCanonicalFields($user);
        $userManager->updatePassword($user);

        // File upload management
        $file = $form['uploadedFileName']->getData();
        if ($file) {
            $currentObject->removeUpload(true);
            $fileName = $currentObject->upload($file);
            $currentObject->setFileName($fileName);
        }
    }
}
