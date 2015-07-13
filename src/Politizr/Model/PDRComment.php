<?php

namespace Politizr\Model;

use Politizr\Model\om\BasePDRComment;

class PDRComment extends BasePDRComment implements PDCommentInterface
{
    /**
     * Manage published information
     *
     * @param \PropelPDO $con
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
    
    /**
     * @see PDComment::getPDocumentType
     */
    public function getType()
    {
        return PDocumentInterface::TYPE_REACTION_COMMENT;
    }

    /**
     * @see PDComment::setPDocumentId
     */
    public function setPDocumentId($documentId)
    {
        parent::setPDReactionId($documentId);
    }
    
    /**
     * @see PDComment::getParentType
     */
    public function getPDocumentType()
    {
        return PDocumentInterface::TYPE_REACTION;
    }

    /**
     * @see PDComment::getPDocument
     */
    public function getPDocument()
    {
        return parent::getPDReaction();
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
