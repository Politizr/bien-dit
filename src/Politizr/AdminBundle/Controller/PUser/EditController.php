<?php

namespace Politizr\AdminBundle\Controller\PUser;

use Admingenerated\PolitizrAdminBundle\BasePUserController\EditController as BaseEditController;

use Symfony\Component\Form\Form;
use Symfony\Component\EventDispatcher\GenericEvent;

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
        $userManager = $this->get('politizr.manager.user');

        $userManager->updateCanonicalFields($user);
        if ($user->getPlainPassword()) {
            $userManager->updatePassword($user);
        }
    }
}
