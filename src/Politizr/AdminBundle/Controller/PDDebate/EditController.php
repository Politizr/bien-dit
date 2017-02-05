<?php

namespace Politizr\AdminBundle\Controller\PDDebate;

use Admingenerated\PolitizrAdminBundle\BasePDDebateController\EditController as BaseEditController;

use Symfony\Component\Form\Form;
use Symfony\Component\EventDispatcher\GenericEvent;

use Politizr\Model\PDDebate;

/**
 * EditController
 */
class EditController extends BaseEditController
{

    /**
     * This method is here to make your life better, so overwrite  it
     *
     * @param \Symfony\Component\Form\Form $form the valid form
     * @param \Politizr\Model\PDDebate $PDDebate your \Politizr\Model\PDDebate object
     */
    protected function postSave(Form $form, PDDebate $debate)
    {
        // Upd "parent_reaction_id" form data to "parent_reaction_id" db info
        $sendNotifications = $form['block_notifications']->getViewData();

        // Events
        if ($sendNotifications) {
            $user = $debate->getPUser();
            
            $event = new GenericEvent($debate, array('user_id' => $user->getId(),));
            $dispatcher = $this->get('event_dispatcher')->dispatch('r_debate_publish', $event);
            $event = new GenericEvent($debate, array('author_user_id' => $user->getId(),));
            $dispatcher = $this->get('event_dispatcher')->dispatch('n_debate_publish', $event);
        }
    }
}
