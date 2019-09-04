<?php

namespace Politizr\Model;

use Politizr\FrontBundle\Lib\Tools\StaticTools;

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
     * Overide to manage update published doc without updating slug
     * Overwrite to fully compatible MySQL 5.7
     * note: original "makeSlugUnique" throws Syntax error or access violation: 1055 Expression #1 of SELECT list is not in GROUP BY clause and contains nonaggregated column
     *
     * @see BasePDDebate::createSlug
     */
    protected function createSlug()
    {
        $slug = $this->createRawSlug();
        $slug = $this->limitSlugSize($slug);
        $slug = $slug . '-' . uniqid();

        return $slug;
    }

    /**
     * Override to manage accented characters
     * @return string
     */
    protected function createRawSlug()
    {
        $toSlug =  StaticTools::transliterateString($this->getTitle());
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
