<?php

namespace Politizr\Model;

use Politizr\FrontBundle\Lib\Tools\StaticTools;

use Politizr\Model\om\BasePCTopic;

class PCTopic extends BasePCTopic
{
    /**
     *
     * @return string
     */
    public function __toString()
    {
        $title = $this->getTitle();
        if (empty($title)) {
            $title = 'Pas de titre';
        }

        $circle = $this->getPCircle();
        $circleTitle = $circle->getTitle();
        if (empty($circleTitle)) {
            $circleTitle = 'Groupe inconnu';
        }

        return $title . '(' . $circleTitle . ')';
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
}
