<?php

namespace Politizr\AdminBundle\Controller\PUser;

use Admingenerated\PolitizrAdminBundle\BasePUserController\NewController as BaseNewController;

use Symfony\Component\Form\Form;

use Politizr\Model\PUser;

/**
 * NewController
 */
class NewController extends BaseNewController
{
    /**
     * @see Admingenerated\PolitizrAdminBundle\BasePUserController\NewController
     */
    protected function preSave(Form $form, PUser $user)
    {
        $userManager = $this->get('politizr.manager.user');

        $userManager->updateCanonicalFields($user);
        $userManager->updatePassword($user);
    }
}
