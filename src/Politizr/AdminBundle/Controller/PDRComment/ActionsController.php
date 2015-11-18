<?php

namespace Politizr\AdminBundle\Controller\PDRComment;

use Admingenerated\PolitizrAdminBundle\BasePDRCommentController\ActionsController as BaseActionsController;

use Symfony\Component\HttpFoundation\RedirectResponse;

use Politizr\Model\PMRCommentHistoric;

use Politizr\Model\PDRCommentQuery;

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
        $comment = PDRCommentQuery::create()->findPk($pk);
        if (!$comment) {
            throw new InconsistentDataException('PDRComment pk-'.$pk.' not found.');
        }

        try {
            $mComment = new PMRCommentHistoric();

            $mComment->setPUserId($comment->getPUserId());
            $mComment->setPDRCommentId($comment->getId());
            $mComment->setPObjectId($comment->getId());
            $mComment->setDescription($comment->getDescription());

            $mComment->save();

            $this->get('session')->getFlashBag()->add('success', 'L\'archive a bien été créée.');
        } catch (\Exception $e) {
            $this->get('session')->getFlashBag()->add('error', $e->getMessage());
        }

        return new RedirectResponse($this->generateUrl("Politizr_AdminBundle_PDRComment_edit", array('pk' => $pk)));
    }
}
