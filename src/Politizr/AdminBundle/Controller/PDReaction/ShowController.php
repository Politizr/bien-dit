<?php

namespace Politizr\AdminBundle\Controller\PDReaction;

use Symfony\Component\HttpFoundation\Request;

use Admingenerated\PolitizrAdminBundle\BasePDReactionController\ShowController as BaseShowController;

/**
 * ShowController
 */
class ShowController extends BaseShowController
{
    /**
     *    Surcharge pour gérer la mise en session de l'id du débat associé si non initialisé- gestion du lien "retour au débat"
     */
    public function indexAction(Request $request, $pk)
    {
        $PDReaction = $this->getObject($pk);

        if (!$PDReaction) {
            throw new NotFoundHttpException("The Politizr\Model\PDReaction with Id $pk can't be found");
        }

        // Récupération de l'ID de débat en cours
        $session = $this->get('session');
        if (! $pdDebateId  = $session->get('PDDebate/id')) {
            $session->set('PDDebate/id', $PDReaction->getPDDebateId());
        }

        return $this->render('PolitizrAdminBundle:PDReactionShow:index.html.twig', $this->getAdditionalRenderParameters($PDReaction) + array(
            "PDReaction" => $PDReaction
        ));
    }
}
