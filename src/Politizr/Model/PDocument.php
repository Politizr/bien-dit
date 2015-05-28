<?php

namespace Politizr\Model;

use Politizr\Model\om\BasePDocument;

use Politizr\Model\PDCommentQuery;
use Politizr\Model\PDDebateQuery;
use Politizr\Model\PDReactionQuery;

use Politizr\Exception\InconsistentDataException;

/**
 *    Classe mère de débat et réaction
 *
 *
 *
 *     @author Lionel Bouzonville / Studio Echo
 */
class PDocument extends BasePDocument
{
    // ************************************************************************************ //
    //                                        CONSTANTES
    // ************************************************************************************ //
      const UPLOAD_PATH = '/../../../web/uploads/documents/';
      const UPLOAD_WEB_PATH = '/uploads/documents/';

    // TODO > migrer le stockage des types sur un objet dédié
    const TYPE_DOCUMENT = 'Politizr\Model\PDocument';
    const TYPE_DEBATE = 'Politizr\Model\PDDebate';
    const TYPE_REACTION = 'Politizr\Model\PDReaction';
    const TYPE_COMMENT = 'Politizr\Model\PDComment';
    const TYPE_USER = 'Politizr\Model\PUser';

    // *****************************  OBJET / STRING  ****************** //

    /**
     *
     */
    public function __toString()
    {
        return $this->getTitle();
    }


    /**
     *    Vérifie que le document courant a été rédigé par le user dont l'ID est passé en argument.
     *
     *     @return boolean
     */
    public function isOwner($pUserId)
    {
        if ($this->getPUserId() == $pUserId) {
            return true;
        } else {
            return false;
        }
    }

    // *****************************  DEBAT / REACTION  ****************** //


    /**
     * Renvoit le type "enfant" du document associé
     *
     * @return     string
     */
    public function getType()
    {
        $object = $this->getDescendantClass();

        if ($object == 'Politizr\Model\PDDebate') {
            return PDocument::TYPE_DEBATE;
        } elseif ($object == 'Politizr\Model\PDReaction') {
            return PDocument::TYPE_REACTION;
        } else {
            throw new InconsistentDataException('PDocument child object unknown or null.');
        }
    }

    /**
     *    Renvoit l'objet PDDebate associé.
     *
     *     @return     PDDebate
     */
    public function getDebate()
    {
        if ($type = $this->getType() == PDocument::TYPE_DEBATE) {
            return PDDebateQuery::create()->findPk($this->getId());
        } else {
            throw new InconsistentDataException('PDocument child object is not of PDDebate type.');
        }
    }

    /**
     *    Renvoit l'objet PDReaction associé.
     *
     *     @return     PDReaction
     */
    public function getReaction()
    {
        if ($type = $this->getType() == PDocument::TYPE_REACTION) {
            return PDReactionQuery::create()->findPk($this->getId());
        } else {
            throw new InconsistentDataException('PDocument child object is not of PDReaction type.');
        }
    }

    // *****************************    COMMENTAIRES   ************************* //

    /**
     * Renvoit le nombre de commentaires du document.
     *
     * @return integer Nombre de commentaires
     */
    public function countComments($online = true, $paragraphNo = null)
    {
        $query = PDCommentQuery::create()
                    ->filterByOnline($online)
                    ->_if($paragraphNo)
                        ->filterByParagraphNo($paragraphNo)
                    ->_endif()
                    ;
        
        return parent::countPDComments($query);
    }


    /**
     *    Renvoit les commentaires associés au débat
     *
     * @return PropelCollection d'objets PDDComment
     */
    public function getComments($online = true, $paragraphNo = null, $orderBy = null)
    {
        $query = PDCommentQuery::create()
                    ->filterByOnline($online)
                    ->_if($paragraphNo)
                        ->filterByParagraphNo($paragraphNo)
                    ->_else()
                        ->filterByParagraphNo(0)
                            ->_or()
                        ->filterByParagraphNo(null)
                    ->_endif()
                    ->_if($orderBy)
                        ->orderBy($orderBy[0], $orderBy[1])
                    ->_else()
                        ->orderBy('p_d_comment.created_at', 'desc')
                    ->_endif()
                    ;

        return parent::getPDComments($query);
    }
    

    /**
     *    Renvoit les commentaires généraux au débat (non associés à un paragraphe en particulier)
     *
     * @return PropelCollection d'objets PDDComment
     */
    public function getGlobalComments($online = true, $orderBy = null)
    {
        return $this->getComments($online, 0, $orderBy);
    }
}
