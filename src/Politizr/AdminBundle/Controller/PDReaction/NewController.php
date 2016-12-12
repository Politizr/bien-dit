<?php

namespace Politizr\AdminBundle\Controller\PDReaction;

use Admingenerated\PolitizrAdminBundle\BasePDReactionController\NewController as BaseNewController;

use Symfony\Component\Form\Form;
use Symfony\Component\EventDispatcher\GenericEvent;

use Politizr\Model\PDReaction;

/**
 * NewController
 */
class NewController extends BaseNewController
{

    /**
     * This method is here to make your life better, so overwrite  it
     *
     * @param Form $form the valid form
     * @param PDReaction $PDReaction
     */
    protected function postSave(Form $form, PDReaction $reaction)
    {
        // Upd "parent_reaction_id" form data to "parent_reaction_id" db info
        $parentReactionId = $form['parent_reaction']->getViewData();
        if ($parentReactionId) {
            $reaction->setParentReactionId($parentReactionId);
        }

        // Init default reaction's tagged tags
        $this->get('politizr.manager.document')->initReactionTaggedTags($reaction);

        // Publication
        $this->get('politizr.manager.document')->publishReaction($reaction);

        // Events
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
