<?php

namespace Politizr\Model;

use Politizr\FrontBundle\Lib\Tools\StaticTools;

use Politizr\Model\om\BasePCircle;

class PCircle extends BasePCircle
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
        $toSlug =  StaticTools::transliterateString($this->getTitle());
        $slug = $this->cleanupSlugPart($toSlug);
        return $slug;
    }

    /**
     * Count number of topics related to this circle
     *
     * @param $online boolean
     * @return int
     */
    public function getNbTopics($online = true)
    {
        $nbTopics = PCTopicQuery::create()
            ->filterByPCircleId($this->getId())
            ->_if($online)
                ->filterByOnline(true)
            ->_endif()
            ->count();

        return $nbTopics;
    }
}
