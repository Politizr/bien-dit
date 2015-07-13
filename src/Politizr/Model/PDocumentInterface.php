<?php

namespace Politizr\Model;

use Politizr\Model\om\BasePDocument;

use Politizr\Model\PDCommentQuery;
use Politizr\Model\PDDebateQuery;
use Politizr\Model\PDReactionQuery;

use Politizr\Exception\InconsistentDataException;

/**
 * Interface for managing document: debate, reaction
 *
 * @author Lionel Bouzonville
 */
interface PDocumentInterface
{
    // ************************************************************************************ //
    //                                        CONSTANTES
    // ************************************************************************************ //
    // @todo > migrer le stockage des types sur un objet dédié
    const TYPE_DEBATE = 'Politizr\Model\PDDebate';
    const TYPE_REACTION = 'Politizr\Model\PDReaction';
    const TYPE_DEBATE_COMMENT = 'Politizr\Model\PDDComment';
    const TYPE_REACTION_COMMENT = 'Politizr\Model\PDRComment';
    const TYPE_USER = 'Politizr\Model\PUser';
    const TYPE_BADGE = 'Politizr\Model\PRBadge';

    /**
     * Test if the document is owned by the parameter's userId
     *
     * @param int $userId
     * @return boolean
     */
    public function isOwner($userId);

    /**
     * Get the object type
     *
     * @return string
     */
    public function getType();

    /**
     * Filter the document's comments
     *
     * @param boolean $online
     * @param int $paragraphNo
     * @param array $orderBy
     * @return PropelCollection d'objets PDDComment
     */
    public function getComments($online = true, $paragraphNo = null, $orderBy = null);

    /**
     * Count the number of comments
     *
     * @param boolean $online
     * @param int $paragraphNo
     * @return int
     */
    public function countComments($online = true, $paragraphNo = null);

//     // *****************************    COMMENTAIRES   ************************* //
// 
// 
// 
//     /**
//      * Renvoit les commentaires généraux au débat (non associés à un paragraphe en particulier)
//      *
//      * @return PropelCollection d'objets PDDComment
//      */
//     public function getGlobalComments($online = true, $orderBy = null)
//     {
//         return $this->getComments($online, 0, $orderBy);
//     }
}
