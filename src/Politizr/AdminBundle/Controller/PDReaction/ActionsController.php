<?php

namespace Politizr\AdminBundle\Controller\PDReaction;

use Admingenerated\PolitizrAdminBundle\BasePDReactionController\ActionsController as BaseActionsController;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;

use Politizr\Constant\PathConstants;

use Politizr\Model\PMReactionHistoric;

use Politizr\Model\PDReactionQuery;
use Politizr\Model\PDReaction;

use Politizr\Exception\InconsistentDataException;


use Politizr\AdminBundle\Form\Type\PDReaction\SelectReactionType;

/**
 * ActionsController
 */
class ActionsController extends BaseActionsController
{
    /**
     * XHR
     */
    public function updatePDReactionAction(Request $request)
    {
        $logger = $this->get('logger');
        $logger->info('*** updatePDReactionAction');

        try {
            if ($request->isXmlHttpRequest()) {
                $logger->info('isXmlHttpRequest');

                $debateId = $request->get('debateId');
                $logger->info('debateId = '.$debateId);

                $form = $this->createForm(new SelectReactionType($debateId));
                $formView = $form->createView();

                $selectRendering = $this->renderView(
                    'PolitizrAdminBundle:PDReactionNew:_parent_reaction.html.twig',
                    array(
                        'form' => $form->createView()
                    )
                );

                $data = array (
                    'parent_reaction' => $selectRendering
                );

                $jsonSuccess = array (
                    'success' => true
                );

                $jsonResponse = array_merge($jsonSuccess, $data);
            } else {
                throw new \Exception('Not a XHR request');
            }
        } catch (\Exception $e) {
            throw $e;
        }

        // JSON formatted success/error message
        $response = new Response(json_encode($jsonResponse));
        return $response;
    }

    /**
     *
     * @param PDReaction $reaction
     */
    public function executeObjectHomepage(PDReaction $reaction)
    {
        $reaction->setHomepage(!$reaction->getHomepage());
        $reaction->save();
    }

    /**
     *
     * @param array $reactionsId
     */
    protected function executeBatchHomepage(array $reactionsId)
    {
        foreach ($reactionsId as $pk) {
            $reaction = PDReactionQuery::create()->findPk($pk);
            if (!$reaction) {
                throw new InconsistentDataException('PDReactionQuery pk-'.$pk.' not found.');
            }
            $reaction->setHomepage(!$reaction->getHomepage());
            $reaction->save();
        }
    }

    /**
     *
     * @param int $pk
     */
    public function archiveAction($pk)
    {
        $reaction = PDReactionQuery::create()->findPk($pk);
        if (!$reaction) {
            throw new InconsistentDataException('PDReactionQuery pk-'.$pk.' not found.');
        }

        try {
            $mReaction = new PMReactionHistoric();

            $mReaction->setPUserId($reaction->getPUserId());
            $mReaction->setPDReactionId($reaction->getId());
            $mReaction->setPObjectId($reaction->getId());
            $mReaction->setTitle($reaction->getTitle());
            $mReaction->setDescription($reaction->getDescription());
            $mReaction->setCopyright($reaction->getCopyright());

            // File copy
            if ($reaction->getFileName()) {
                $destFileName = $this->get('politizr.tools.global')->copyFile(
                    $this->get('kernel')->getRootDir() .
                    PathConstants::KERNEL_PATH_TO_WEB .
                    PathConstants::REACTION_UPLOAD_WEB_PATH .
                    $reaction->getFileName()
                );
                $mReaction->setFileName($destFileName);
            }

            $mReaction->save();

            $this->get('session')->getFlashBag()->add('success', 'L\'archive a bien été créée.');
        } catch (\Exception $e) {
            $this->get('session')->getFlashBag()->add('error', $e->getMessage());
        }

        return new RedirectResponse($this->generateUrl("Politizr_AdminBundle_PDReaction_edit", array('pk' => $pk)));
    }

    /**
     * Redirection listing commentaires avec filtre ID réaction préfixé.
     *
     * @return \Politizr\AdminBundle\Controller\Customer\Response
     */
    public function commentsAction($pk)
    {
        $logger = $this->get('logger');
        $logger->info('*** commentsAction');

        $filterObject = array();
        $filterObject['p_d_reaction_id'] = $pk;
        $this->get('session')->set('Politizr\AdminBundle\PDRCommentList\Filters', $filterObject);

        return new RedirectResponse($this->generateUrl("Politizr_AdminBundle_PDRComment_list"));
    }
}
