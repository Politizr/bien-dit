<?php

namespace Politizr\AdminBundle\Controller\PUser;

use Admingenerated\PolitizrAdminBundle\BasePUserController\NewController as BaseNewController;

use Politizr\Constant\EmailConstants;
use Politizr\Constant\ReputationConstants;

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

    /**
     * @see Admingenerated\PolitizrAdminBundle\BasePUserController\NewController
     */
    protected function postSave(Form $form, PUser $user)
    {
        $userManager = $this->get('politizr.manager.user');

        // notif email subscribe
        $userManager->createUserSubscribeNotifEmail($user->getId(), EmailConstants::getDefaultNotificationSubscribeIds());

        // Reputation evolution update
        if ($user->isQualified()) {
            $user->updateReputation(ReputationConstants::DEFAULT_ELECTED_REPUTATION);
        } else {
            $user->updateReputation(ReputationConstants::DEFAULT_CITIZEN_REPUTATION);
        }

        // default online
        $user->setOnline(true);
        $user->save();
    }
}
