<?php

namespace Politizr\AdminBundle\Controller\PDReaction;

use Admingenerated\PolitizrAdminBundle\BasePDReactionController\NewController as BaseNewController;

use Politizr\AdminBundle\Form\Type\PDReaction\NewType;

use Politizr\Model\PDReactionQuery;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * NewController
 */
class NewController extends BaseNewController
{

    /**
     * This method is here to make your life better, so overwrite  it
     *
     * @param \Politizr\Model\PDReaction $PDReaction your \Politizr\Model\PDReaction object
     */
    public function preBindRequest(\Politizr\Model\PDReaction $PDReaction)
    {
    	// Récupération de l'ID de débat en cours
        $session = $this->get('session');
        $pk = $session->get('PDDebate/id');

        if (!$pk) {
            throw new NotFoundHttpException("The pk of Politizr\Model\PDDebate can't be retrieve from session: reload reaction page from debate page.");
        }

    	$PDReaction->setPDDebateId($pk);
    }


    /**
     * This method is here to make your life better, so overwrite  it
     *
     * @param \Symfony\Component\Form\Form $form the valid form
     * @param \Politizr\Model\PDReaction $PDReaction your \Politizr\Model\PDReaction object
     */
    public function preSave(\Symfony\Component\Form\Form $form, \Politizr\Model\PDReaction $PDReaction)
    {
        $logger = $this->get('logger');
        $parentNodeId = $form['parent_node']->getData();
        
        $logger->debug('*** preSave');
        $logger->debug('parentNodeId = '.print_r($parentNodeId, true));

        if ($parentNodeId) {
            $node = PDReactionQuery::create()->findPk($parentNodeId);

            $node->insertAsLastChildOf($PDReaction);
        } 
    }
}
