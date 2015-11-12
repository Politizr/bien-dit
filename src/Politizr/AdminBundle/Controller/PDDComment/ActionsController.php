<?php

namespace Politizr\AdminBundle\Controller\PDDComment;

use Admingenerated\PolitizrAdminBundle\BasePDDCommentController\ActionsController as BaseActionsController;

use Symfony\Component\HttpFoundation\RedirectResponse;

use Politizr\Model\PMDCommentHistoric;

use Politizr\Model\PDDCommentQuery;

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
        $comment = PDDCommentQuery::create()->findPk($pk);
        if (!$comment) {
            throw new InconsistentDataException('PDDComment pk-'.$pk.' not found.');
        }

        try {
            $mComment = new PMDCommentHistoric();

            $mComment->setPUserId($comment->getPUserId());
            $mComment->setPDDCommentId($comment->getId());
            $mComment->setPObjectId($comment->getId());
            $mComment->setDescription($comment->getDescription());

            $mComment->save();

            $this->get('session')->getFlashBag()->add('success', 'L\'archive a bien été créée.');
        } catch (\Exception $e) {
            $this->get('session')->getFlashBag()->add('error', $e->getMessage());
        }

        return new RedirectResponse($this->generateUrl("Politizr_AdminBundle_PDDComment_edit", array('pk' => $pk)));
    }
}
