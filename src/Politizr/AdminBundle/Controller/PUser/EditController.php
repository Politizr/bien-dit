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

    /**
     * This method is here to make your life better, so overwrite  it
     *
     * @param \Symfony\Component\Form\Form $form the valid form
     * @param \Politizr\Model\PUser $PUser your \Politizr\Model\PUser object
     */
    protected function postSave(Form $form, PUser $user)
    {
        // Upd "parent_reaction_id" form data to "parent_reaction_id" db info
        $sendNotifications = $form['block_notifications']->getViewData();

        // Events
        if ($sendNotifications) {
            if ($user->isQualified()) {
                $event = new GenericEvent($user);
                $dispatcher = $this->get('event_dispatcher')->dispatch('n_localization_user', $event);
            }
        }
    }
}
