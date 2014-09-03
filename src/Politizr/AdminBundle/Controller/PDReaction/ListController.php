<?php

namespace Politizr\AdminBundle\Controller\PDReaction;

use Admingenerated\PolitizrAdminBundle\BasePDReactionController\ListController as BaseListController;

use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * ListController
 */
class ListController extends BaseListController
{
	/**
	 *	Redirection vers le show de l'objet PDDebate associé
	 */
    public function indexAction()
    {
    	// Récupération de l'ID de débat en cours
        $session = $this->get('session');
        $pk = $session->get('PDDebate/id');
        $action = $session->get('PDDebate/action');

        if (!$pk) {
            throw new NotFoundHttpException("The pk of Politizr\Model\PDDebate can't be retrieve from session.");
        }

        if (!$action) {
            $action = 'show';
        }

        return new RedirectResponse($this->generateUrl("Politizr_AdminBundle_PDDebate_".$action, array('pk' => $pk)));
    }
}
