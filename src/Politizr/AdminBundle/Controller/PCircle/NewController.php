<?php

namespace Politizr\AdminBundle\Controller\PCircle;

use Admingenerated\PolitizrAdminBundle\BasePCircleController\NewController as BaseNewController;

use Symfony\Component\Form\Form;

use Politizr\Model\PCircle;

/**
 * NewController
 */
class NewController extends BaseNewController
{

    /**
     * Manage public / private circle
     *
     * @param Form $form the valid form
     * @param PCircle $circle
     */
    protected function preSave(Form $form, PCircle $circle)
    {
        $isPrivate = $form['private_access'];
        if ($isPrivate) {
            $circle->setPublicCircle(false);
        }
    }

    /**
     * Automaticaly add existing and authorized users in circle
     *
     * @param Form $form
     * @param Politizr\Model\PCircle $circle
     */
    protected function postSave(Form $form, PCircle $circle)
    {
        // cf https://github.com/Politizr/bien-dit/issues/52
        $users = $this->get('politizr.functional.circle')->updateCircleAuthorizedUsers($circle);
    }

}
