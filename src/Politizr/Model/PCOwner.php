<?php

namespace Politizr\Model;

use Politizr\Model\om\BasePCOwner;

class PCOwner extends BasePCOwner
{
    /**
     *
     * @return string
     */
    public function __toString()
    {
        $title = $this->getTitle();

        if (!empty($title)) {
            return $this->getTitle();
        }

        return 'Pas de titre';
    }

     /**
     * Override to manage accented characters
     * @return string
     */
    protected function createRawSlug()
    {
        $toSlug =  StudioEchoUtils::transliterateString($this->getTitle());
        $slug = $this->cleanupSlugPart($toSlug);
        return $slug;
    }

    /**
     * Retrieve owner's online circles
     *
     * @return PropelCollection[PCircle]
     */
    public function getCircles()
    {
        $query = PCircleQuery::create()
            ->filterByOnline(true)
            ->orderByRank();

        return parent::getPCircles($query);
    }
}
