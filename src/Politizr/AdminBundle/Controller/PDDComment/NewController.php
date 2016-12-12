<?php

namespace Politizr\AdminBundle\Controller\PDDComment;

use Admingenerated\PolitizrAdminBundle\BasePDDCommentController\NewController as BaseNewController;

use Symfony\Component\Form\Form;
use Symfony\Component\EventDispatcher\GenericEvent;

use Politizr\Model\PDDComment;

/**
 * NewController
 */
class NewController extends BaseNewController
{
    /**
     * This method is here to make your life better, so overwrite  it
     *
     * @param \Symfony\Component\Form\Form $form the valid form
     * @param \Politizr\Model\PDDComment $comment
     */
    protected function postSave(Form $form, PDDComment $comment)
    {
        if ($online = $comment->getOnline()) {
            // Events
            $event = new GenericEvent($comment, array('user_id' => $comment->getPUserId(),));
            $dispatcher = $this->get('event_dispatcher')->dispatch('r_comment_publish', $event);
            $event = new GenericEvent($comment, array('author_user_id' => $comment->getPUserId(),));
            $dispatcher = $this->get('event_dispatcher')->dispatch('n_comment_publish', $event);
            $event = new GenericEvent($comment, array('author_user_id' => $comment->getPUserId()));
            $dispatcher = $this->get('event_dispatcher')->dispatch('b_comment_publish', $event);
        }
    }
}
