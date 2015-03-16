<?php

namespace Politizr\AdminBundle\Controller\PDReaction;

use Admingenerated\PolitizrAdminBundle\BasePDReactionController\EditController as BaseEditController;

/**
 * EditController
 */
class EditController extends BaseEditController
{
    /**
     *    Surcharge pour gérer la mise en session de l'id du débat associé si non initialisé- gestion du lien "retour au débat"
     */
    public function indexAction($pk)
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

        return parent::indexAction($pk);
    }

    /**
     * Manage file upload
     *
     * @param \Symfony\Component\Form\Form $form the valid form
     * @param \AgneauPyrenees\Model\ContentDish $currentObject object
     */
    public function preSave(\Symfony\Component\Form\Form $form, \Politizr\Model\PDReaction $currentObject)
    {
        // $logger = $this->get('logger');
        // $logger->info('*** post datas '.print_r($form->getData(), true));

        $file = $form['uploadedFileName']->getData();
        if ($file) {
          $currentObject->removeUpload(true);
          $fileName = $currentObject->upload($file);
          $currentObject->setFileName($fileName);
        }
    }
}
