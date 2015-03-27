<?php

namespace Politizr\Model;

use Politizr\Model\om\BasePTagQuery;


use Politizr\Model\PTag;
use Politizr\Model\PTagType;

class PTagQuery extends BasePTagQuery
{
    /**
     * Création d'un nouveau tag.
     * Pas de création si un slug équivalent est trouvé pour le même type.
     *
     * @param string $title     Titre
     * @param integer $typeId   ID Type
     * @param boolean $online   En ligne
     * @return integer          ID du nouveau tag, ou false si aucun tag créé
     */
    public function addTag($title = '', $typeId = null, $userId = null, $online = true)
    {
        $slug = \StudioEcho\Lib\StudioEchoUtils::generateSlug($title);
        $tag = PTagQuery::create()
                    // ->_if($typeId)
                    //     ->filterByPTTagTypeId($typeId)
                    // ->_endif()
                    ->filterBySlug($slug)
                    ->findOne();

        if ($tag) {
            return false;
        } else {
            // Création du nouveau tag
            $tag = new PTag();

            // Type non défini > tag thématique
            if (null === $typeId) {
                $typeId = PTTagType::TYPE_THEME;
            }

            $tag->setTitle($title);
            $tag->setPTTagTypeId($typeId);
            $tag->setPUserId($userId);
            $tag->setOnline($online);

            $tag->save();
        }

        return $tag->getId();
    }
}
