<?php

namespace Politizr\Model;

use Politizr\Model\om\BasePDDTaggedTQuery;

/**
 *
 *
 *  @author Lionel Bouzonville
 */
class PDDTaggedTQuery extends BasePDDTaggedTQuery
{
    /**
     *    Création d'une nouvelle entrée
     *
     *    @param     $pdDebateId ID débat
     *  @param  $pTagId     ID tag
     *
     *  @return     integer     ID de l'entrée créé, ou false si l'entrée n'a pas pu être créée
     */
    public function addElement($pdDebateId = null, $pTagId = null) {
        $pddTaggedT = PDDTaggedTQuery::create()->filterByPDDebateId($pdDebateId)->filterByPTagId($pTagId)->findOne();
        if (!$pddTaggedT && $pdDebateId != null && $pTagId != null) {
            $pddTaggedT = new PDDTaggedT();

            $pddTaggedT->setPDDebateId($pdDebateId);
            $pddTaggedT->setPTagId($pTagId);

            $pddTaggedT->save();
        } else {
            return false;
        }

        return $pddTaggedT->getId();
    }


    /**
     *    Suppression d'une entrée PDDTaggedT tag / user.
     *
     *    @param     $pdDebateId ID débat
     *  @param  $pTagId     ID tag
     *
     *  @return     boolean     Vrai si l'entrée a pu être supprimée, faux sinon    
     */
    public function deleteElement($pdDebateId = null, $pTagId = null) {
        $pddTaggedT = PDDTaggedTQuery::create()->filterByPDDebateId($pdDebateId)->filterByPTagId($pTagId)->findOne();
        if (!$pddTaggedT) {
            return false;
        } else {
            $pddTaggedT->delete();
            return true;
        }
    }

}
