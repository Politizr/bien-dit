<?php

namespace Politizr\AdminBundle\Controller\PDDebate;

use Admingenerated\PolitizrAdminBundle\BasePDDebateController\ShowController as BaseShowController;

/**
 * ShowController
 */
class ShowController extends BaseShowController
{
	/**
	 *	Surcharge pour mise en session de l'id de l'objet courant, utilisé pour les actions sur les réactions
	 */
    public function indexAction($pk)
    {
        // Mise en session de l'ID de l'objet
        $session = $this->get('session');
        $session->set('PDDebate/id', $pk);
        $session->set('PDDebate/action', 'show');

        return parent::indexAction($pk);
    }

}
