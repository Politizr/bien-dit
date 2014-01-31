<?php

namespace StudioEcho\StudioEchoMediaBundle\Lib;

use StudioEcho\StudioEchoMediaBundle\Model\SeMediaFileQuery;

/*
 * Gestionnaire / méthodes statiques renvoyant les objets médias.
 * 
 * @author Lionel Bouzonville / Studio Echo
 */
class StudioEchoMediaManager {
    /**
     * Retourne 1 fichier média associé à l'objet identifié en paramètre.
     * 
     * @param type $objectId
     * @param type $objectClass
     * @param type $category
     * @param type $fileNumber
     * @return string
     */
    public static function getMedia($objectId = 1, $objectClassname = 'My\Object\Model', $locale = 'fr', $categoryId = 1, $rank = null) {
        return SeMediaFileQuery::create()
                ->joinWithI18n($locale)
                ->useSeObjectHasFileQuery()
                    ->useSeMediaObjectQuery()
                        ->filterByObjectId($objectId)
                        ->filterByObjectClassname($objectClassname)
                    ->endUse()
                    ->_if($rank)
                        ->filterBySortableRank($rank)
                    ->_else()
                        ->orderBySortableRank()
                    ->_endif()
                    
                ->endUse()
                ->filterByOnline(true)
                ->filterByCategoryId($categoryId)
                ->findOne();
    }
    
    /**
     * Retourne tous les fichiers médias associés à l'objet identifié en paramètre.
     * 
     * @param type $objectId
     * @param type $objectClass
     * @param type $category
     * @return string
     */
    public static function getMediaList($objectId = 1, $objectClassname = 'My\Object\Model', $locale = 'fr', $categoryId = 1) {
        return SeMediaFileQuery::create()
                ->joinWithI18n($locale)
                ->useSeObjectHasFileQuery()
                    ->useSeMediaObjectQuery()
                        ->filterByObjectId($objectId)
                        ->filterByObjectClassname($objectClassname)
                    ->endUse()
                    ->orderBySortableRank()
                ->endUse()
                ->filterByOnline(true)
                ->filterByCategoryId($categoryId)
                ->find();
    }
}
?>
