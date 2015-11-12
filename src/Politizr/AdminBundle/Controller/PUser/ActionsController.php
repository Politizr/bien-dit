<?php

namespace Politizr\AdminBundle\Controller\PUser;

use Admingenerated\PolitizrAdminBundle\BasePUserController\ActionsController as BaseActionsController;

use Symfony\Component\HttpFoundation\RedirectResponse;

use Politizr\Constant\PathConstants;

use Politizr\Model\PMUserHistoric;

use Politizr\Model\PUserQuery;

use Politizr\Exception\InconsistentDataException;

/**
 * ActionsController
 */
class ActionsController extends BaseActionsController
{
    /**
     *
     * @param int $pk
     */
    public function archiveAction($pk)
    {
        $user = PUserQuery::create()->findPk($pk);
        if (!$user) {
            throw new InconsistentDataException('PUserQuery pk-'.$pk.' not found.');
        }

        try {
            $mUser = new PMUserHistoric();

            $mUser->setPUserId($user->getId());
            $mUser->setPObjectId($user->getId());
            $mUser->setSubtitle($user->getSubtitle());
            $mUser->setBiography($user->getBiography());
            $mUser->setCopyright($user->getCopyright());

            // File copy
            if ($user->getFileName()) {
                $destFileName = $this->get('politizr.tools.global')->copyFile(
                    $this->get('kernel')->getRootDir() .
                    PathConstants::KERNEL_PATH_TO_WEB .
                    PathConstants::USER_UPLOAD_WEB_PATH .
                    $user->getFileName()
                );
                $mUser->setFileName($destFileName);
            }
            if ($user->getBackFileName()) {
                $destFileName = $this->get('politizr.tools.global')->copyFile(
                    $this->get('kernel')->getRootDir() .
                    PathConstants::KERNEL_PATH_TO_WEB .
                    PathConstants::USER_UPLOAD_WEB_PATH .
                    $user->getBackFileName()
                );
                $mUser->setBackFileName($destFileName);
            }

            $mUser->save();

            $this->get('session')->getFlashBag()->add('success', 'L\'archive a bien été créée.');
        } catch (\Exception $e) {
            $this->get('session')->getFlashBag()->add('error', $e->getMessage());
        }

        return new RedirectResponse($this->generateUrl("Politizr_AdminBundle_PUser_edit", array('pk' => $pk)));
    }
}
