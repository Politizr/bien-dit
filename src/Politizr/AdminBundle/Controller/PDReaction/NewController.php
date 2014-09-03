<?php

namespace Politizr\AdminBundle\Controller\PDReaction;

use Admingenerated\PolitizrAdminBundle\BasePDReactionController\NewController as BaseNewController;

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

    	$PDReaction->setPDDebateId($pk);
    }


}
