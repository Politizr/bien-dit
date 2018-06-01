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
    protected function preSave(Form $form, PDReaction $reaction)
    {
        // Upd debate id
        $reaction->setPDDebateId($form['p_d_debate']->getViewData());

        // Upd "parent_reaction_id" form data to "parent_reaction_id" db info
        $parentReactionId = $form['parent_reaction']->getViewData();
        if ($parentReactionId) {
            $reaction->setParentReactionId($parentReactionId);
        }
    }

    /**
     * This method is here to make your life better, so overwrite  it
     *
     * @param Form $form the valid form
     * @param PDReaction $PDReaction
     */
    protected function postSave(Form $form, PDReaction $reaction)
    {
        // Init default reaction's tagged tags
        $this->get('politizr.manager.document')->initReactionTaggedTags($reaction);

        // Publication
        $this->get('politizr.manager.document')->publishReaction($reaction);
    }
}
