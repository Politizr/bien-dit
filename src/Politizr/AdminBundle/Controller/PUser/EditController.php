<?php

namespace Politizr\AdminBundle\Controller\PUser;

use Admingenerated\PolitizrAdminBundle\BasePUserController\EditController as BaseEditController;

use Symfony\Component\Form\Form;

use Politizr\Model\PUser;

use Politizr\Model\PUserPeer;

/**
 * EditController
 */
class EditController extends BaseEditController
{
    /**
     * Banned notification management
     *
     * @see BaseEditController::preSave
     */
    protected function preSave(Form $form, PUser $user)
    {
        if ($colUpd = $user->isColumnModified(PUserPeer::BANNED) && $user->getBanned()) {
            // @todo mail user

            // @todo logout user
            // http://stackoverflow.com/questions/27987089/invalidate-session-for-a-specific-user-in-symfony2
        }
    }
}
