<?php

namespace Politizr\AdminBundle\Controller\PDDComment;

use Admingenerated\PolitizrAdminBundle\BasePDDCommentController\ActionsController as BaseActionsController;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

use Politizr\Constant\PathConstants;
use Politizr\Constant\ObjectTypeConstants;

use Politizr\AdminBundle\Form\Type\AdminPDCommentModerationType;

use Politizr\Model\PDDComment;
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
     * @param PDDComment $comment
     * @return Response
     */
    protected function successObjectModeration(PDDComment $comment)
    {
        $form = $this->createForm(new AdminPDCommentModerationType($comment));

        return $this->render('PolitizrAdminBundle:PDDCommentActions:moderation.html.twig', array(
            'comment' => $comment,
            'form' => $form->createView(),
        ));
    }

    /**
     *
     * @param $request
     * @param $pk
     * @return Response
     */
    public function moderationUpdateAction(Request $request, $pk)
    {
        $comment = PDDCommentQuery::create()->findPk($pk);
        if (!$comment) {
            throw new InconsistentDataException('PDDComment pk-'.$pk.' not found.');
        }

        $form = $this->createForm(new AdminPDCommentModerationType($comment));

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $data = $form->getData();
                $moderationType = $data['p_m_moderation_type'];

                $this->get('politizr.functional.moderation')->archiveDComment($comment);

                // moderation updating
                if ($data['moderation_level'] == 1) {
                    $comment->setModeratedPartial(true);
                    $comment->setModeratedAt(new \DateTime());

                    $comment->setDescription($data['description']);

                    $comment->save();
                } elseif ($data['moderation_level'] == 2) {
                    $comment->setModerated(true);
                    $comment->setModeratedAt(new \DateTime());
                    $comment->setOnline(false);

                    $comment->save();
                }

                // user relative actions
                $user = $comment->getPUser();

                if ($user) {
                    $userModerated = $this->get('politizr.functional.moderation')->addUserModerated(
                        $user,
                        $moderationType->getId(),
                        ObjectTypeConstants::TYPE_DEBATE_COMMENT,
                        $comment->getId(),
                        $data['score_evolution']
                    );

                    $this->get('politizr.functional.moderation')->updateUserReputation($user, $data['score_evolution']);

                    if ($data['ban']) {
                        $this->get('politizr.functional.moderation')->banUser($user);
                    } 

                    if ($data['send_email']) {
                        $this->get('politizr.functional.moderation')->notifUser($user, $userModerated);
                    }

                    $abuseLevel = $user->getAbuseLevel();
                    if (!$abuseLevel) {
                        $abuseLevel = 0;
                    }
                    $user->setAbuseLevel($abuseLevel + 1);
                    $user->save();
                }

                $this->get('session')->getFlashBag()->add('success', 'Le document a été modéré avec succès.');

                return $this->redirect(
                    $this->generateUrl("Politizr_AdminBundle_PDDComment_list")
                );

            } catch (\Exception $e) {
                $this->get('session')->getFlashBag()->add('error', $e->getMessage());
            }
        }

        return $this->render('PolitizrAdminBundle:PDDCommentActions:moderation.html.twig', array(
            'comment' => $comment,
            'form' => $form->createView(),
        ));
    }

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
            $this->get('politizr.functional.moderation')->archiveDComment($comment);

            $this->get('session')->getFlashBag()->add('success', 'L\'archive a bien été créée.');
        } catch (\Exception $e) {
            $this->get('session')->getFlashBag()->add('error', $e->getMessage());
        }

        return new RedirectResponse($this->generateUrl("Politizr_AdminBundle_PDDComment_edit", array('pk' => $pk)));
    }
}
