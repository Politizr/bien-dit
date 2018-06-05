<?php

namespace Politizr\AdminBundle\Controller\PDReaction;

use Admingenerated\PolitizrAdminBundle\BasePDReactionController\EditController as BaseEditController;

use Symfony\Component\Form\Form;
use Symfony\Component\EventDispatcher\GenericEvent;

use Politizr\Model\PDReaction;

/**
 * EditController
 */
class EditController extends BaseEditController
{

    /**
     * This method is here to make your life better, so overwrite  it
     *
     * @param \Symfony\Component\Form\Form $form the valid form
     * @param \Politizr\Model\PDReaction $PDReaction your \Politizr\Model\PDReaction object
     */
    protected function postSave(Form $form, PDReaction $reaction)
    {
        // Upd "parent_reaction_id" form data to "parent_reaction_id" db info
        if (isset($form['block_notifications'])) {
            $sendNotifications = $form['block_notifications']->getViewData();

            // Events
            if ($sendNotifications) {
                $parentUserId = $reaction->getDebate()->getPUserId();
                if ($reaction->getTreeLevel() > 1) {
                    $parentUserId = $reaction->getParent()->getPUserId();
                }
                $event = new GenericEvent($reaction, array('user_id' => $reaction->getPUserId(),));
                $dispatcher = $this->get('event_dispatcher')->dispatch('r_reaction_publish', $event);
                $event = new GenericEvent($reaction, array('author_user_id' => $reaction->getPUserId(),));
                $dispatcher = $this->get('event_dispatcher')->dispatch('n_reaction_publish', $event);
                $event = new GenericEvent($reaction, array('author_user_id' => $reaction->getPUserId(), 'parent_user_id' => $parentUserId));
                $dispatcher = $this->get('event_dispatcher')->dispatch('b_reaction_publish', $event);
            }
        }
    }
}