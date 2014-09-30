<?php

namespace Politizr\Model;

use Politizr\Model\om\BasePTagQuery;


use Politizr\Model\PTag;
use Politizr\Model\PTagType;

class PTagQuery extends BasePTagQuery
{
	/**
	 *	Création d'un nouveau tag.
	 *	Pas de création si un slug équivalent est trouvé pour le même type.
	 *
	 *	@param 	$title 		Titre
	 *  @param  $typeId 	ID Type
	 * 	@param 	$online 	En ligne
	 *
	 *  @return 	integer 	ID du nouveau tag, ou false si aucun tag créé
	 */
	public function addTag($title = '', $typeId = PTTagType::TYPE_GEO, $online = true) {
        $slug = \StudioEcho\Lib\StudioEchoUtils::generateSlug($title);
        $pTag = PTagQuery::create()->filterByPTTagTypeId($typeId)->filterBySlug($slug)->findOne();

        if ($pTag) {
            return false;
        } else {
            // Création du nouveau tag
            $pTag = new PTag();

            $pTag->setTitle($title);
            $pTag->setPTTagTypeId($typeId);
            $pTag->setOnline($online);

            $pTag->save();
        }

        return $pTag->getId();
	}
}
