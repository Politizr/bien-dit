<?php

namespace Politizr\AdminBundle\Controller\User;

use Admingenerated\PolitizrAdminBundle\BaseUserController\EditController as BaseEditController;

/**
 * EditController
 */
class EditController extends BaseEditController
{
    /**
     * This method is here to make your life better, so overwrite  it
     *
     * @param \Symfony\Component\Form\Form $form the valid form
     * @param \FOS\UserBundle\Propel\User $User your \FOS\UserBundle\Propel\User object
     */
    public function preSave(\Symfony\Component\Form\Form $form, \FOS\UserBundle\Propel\User $User)
    {
        $userManager = $this->container->get('fos_user.user_manager');
        $userManager->updateUser($User);
    }


}
