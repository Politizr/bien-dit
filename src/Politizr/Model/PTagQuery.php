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
	public function addTag($title = '', $typeId = PTTagType::TYPE_GEO, $userId = null, $online = true) {
        $slug = \StudioEcho\Lib\StudioEchoUtils::generateSlug($title);
        $tag = PTagQuery::create()->filterByPTTagTypeId($typeId)->filterBySlug($slug)->findOne();

        if ($tag) {
            return false;
        } else {
            // Création du nouveau tag
            $tag = new PTag();

            $tag->setTitle($title);
            $tag->setPTTagTypeId($typeId);
            $tag->setPUserId($userId);
            $tag->setOnline($online);

            $tag->save();
        }

        return $tag->getId();
	}
}
