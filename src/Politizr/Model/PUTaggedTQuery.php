<?php

namespace Politizr\Model;

use Politizr\Model\om\BasePUTaggedTQuery;

/**
 *
 *
 *  @author Lionel Bouzonville
 */
class PUTaggedTQuery extends BasePUTaggedTQuery
{
    /**
     *    Création d'une nouvelle entrée PUTaggedT tag / user.
     *
     *    @param     $pUserId     ID user
     *  @param  $pTagId     ID tag
     *
     *  @return     integer     ID de l'entrée créé, ou false si l'entrée n'a pas pu être créée
     */
    public function addElement($pUserId = null, $pTagId = null) {
        $pUTaggedT = PUTaggedTQuery::create()->filterByPUserId($pUserId)->filterByPTagId($pTagId)->findOne();
        if (!$pUTaggedT && $pUserId != null && $pTagId != null) {
            $pUTaggedT = new PUTaggedT();

            $pUTaggedT->setPUserId($pUserId);
            $pUTaggedT->setPTagId($pTagId);

            $pUTaggedT->save();
        } else {
            return false;
        }

        return $pUTaggedT->getId();
    }


    /**
     *    Suppression d'une entrée PUTaggedT tag / user.
     *
     *    @param     $pUserId     ID user
     *  @param  $pTagId     ID tag
     *
     *  @return     boolean     Vrai si l'entrée a pu être supprimée, faux sinon    
     */
    public function deleteElement($pUserId = null, $pTagId = null) {
        $pUTaggedT = PUTaggedTQuery::create()->filterByPUserId($pUserId)->filterByPTagId($pTagId)->findOne();
        if (!$pUTaggedT) {
            return false;
        } else {
            $pUTaggedT->delete();
            return true;
        }
    }

}
