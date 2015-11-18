<?php

namespace Politizr\AdminBundle\Controller\PDDebate;

use Admingenerated\PolitizrAdminBundle\BasePDDebateController\ActionsController as BaseActionsController;

use Symfony\Component\HttpFoundation\RedirectResponse;

use Politizr\Constant\PathConstants;

use Politizr\Model\PMDebateHistoric;

use Politizr\Model\PDDebateQuery;

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
        $debate = PDDebateQuery::create()->findPk($pk);
        if (!$debate) {
            throw new InconsistentDataException('PDDebateQuery pk-'.$pk.' not found.');
        }

        try {
            $mDebate = new PMDebateHistoric();

            $mDebate->setPUserId($debate->getPUserId());
            $mDebate->setPDDebateId($debate->getId());
            $mDebate->setPObjectId($debate->getId());
            $mDebate->setTitle($debate->getTitle());
            $mDebate->setDescription($debate->getDescription());
            $mDebate->setCopyright($debate->getCopyright());

            // File copy
            if ($debate->getFileName()) {
                $destFileName = $this->get('politizr.tools.global')->copyFile(
                    $this->get('kernel')->getRootDir() .
                    PathConstants::KERNEL_PATH_TO_WEB .
                    PathConstants::DEBATE_UPLOAD_WEB_PATH .
                    $debate->getFileName()
                );
                $mDebate->setFileName($destFileName);
            }

            $mDebate->save();

            $this->get('session')->getFlashBag()->add('success', 'L\'archive a bien été créée.');
        } catch (\Exception $e) {
            $this->get('session')->getFlashBag()->add('error', $e->getMessage());
        }

        return new RedirectResponse($this->generateUrl("Politizr_AdminBundle_PDDebate_edit", array('pk' => $pk)));
    }

    /**
     * Redirection listing commentaires avec filtre ID débat préfixé.
     *
     * @return \Politizr\AdminBundle\Controller\Customer\Response
     */
    public function commentsAction($pk)
    {
        $logger = $this->get('logger');
        $logger->info('*** commentsAction');

        $filterObject = array();
        $filterObject['p_d_debate_id'] = $pk;
        $this->get('session')->set('Politizr\AdminBundle\PDDCommentList\Filters', $filterObject);

        return new RedirectResponse($this->generateUrl("Politizr_AdminBundle_PDDComment_list"));
    }
}
