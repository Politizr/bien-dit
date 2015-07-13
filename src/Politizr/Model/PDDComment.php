<?php

namespace Politizr\Model;

use Politizr\Model\om\BasePDDComment;

class PDDComment extends BasePDDComment implements PDCommentInterface
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
        return PDocumentInterface::TYPE_DEBATE_COMMENT;
    }

    /**
     * @see PDComment::setPDocumentId
     */
    public function setPDocumentId($documentId)
    {
        parent::setPDDebateId($documentId);
    }
    
    /**
     * @see PDComment::getPDocumentType
     */
    public function getPDocumentType()
    {
        return PDocumentInterface::TYPE_DEBATE;
    }

    /**
     * @see PDComment::getPDocument
     */
    public function getPDocument()
    {
        return parent::getPDDebate();
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
