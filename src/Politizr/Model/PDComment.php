<?php

namespace Politizr\Model;

use Politizr\Model\om\BasePDComment;

use Politizr\Model\PDocument;

class PDComment extends BasePDComment
{

    /**
     *    Surcharge pour gérer la date et l'auteur de la publication.
     *
     *
     */
    public function save(\PropelPDO $con = null)
    {
        // Date publication
        if ($this->isNew()) {
            $this->setPublishedAt(time());

            // User associé
            // @todo: /!\ chaine en dur
            $publisher = $this->getPUser();
            if ($publisher) {
                $this->setPublishedBy($publisher->getFirstname().' '.$publisher->getName());
            } else {
                $this->setPublishedBy('Auteur inconnu');
            }
        }
        
        parent::save($con);
    }
    
    /******************************************************************************/

    /**
     *     Gestion de la réputation / pas de slug pour les commentaires.
     *
     *     @return null
     */
    public function getSlug()
    {
        return null;
    }

    /**
     * Renvoit le type de document associé à la réaction
     *
     * @return     PDDebate     Objet débat
     */
    public function getType()
    {
        return parent::getPDocument()->getType();
    }

    /**
     * Renvoit le document associé à la réaction
     *
     * @return     PDDebate     Objet débat
     */
    public function getDocument()
    {
        return parent::getPDocument();
    }

    /**
     * Renvoit le débat associé à la réaction
     *
     * @return     PDDebate     Objet débat
     */
    public function getDebate()
    {
        return $this->getDocument()->getDebate();
    }

    /**
     * Renvoit la réaction associé à la réaction
     *
     * @return     PDDebate     Objet débat
     */
    public function getReaction()
    {
        return $this->getDocument()->getReaction();
    }

    /**
     * Renvoit le user associé à la réaction
     *
     * @return     PUser     Objet user
     */
    public function getAuthor()
    {
        return parent::getPUser();
    }

    /**
     * Renvoit le user associé à la réaction
     *
     * @return     PUser     Objet user
     */
    public function getUser()
    {
        return parent::getPUser();
    }

    /**
     * Check si le <user id> passé en argument est l'auteur du commentaire courant.
     *
     * @param integer $userId
     * @return boolean
     */
    public function isUserId($userId)
    {
        $user = $this->getUser();

        if ($user && $userId === $user->getId()) {
            return true;
        }

        return false;
    }
}
