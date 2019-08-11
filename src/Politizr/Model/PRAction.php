<?php

namespace Politizr\Model;

use Politizr\Model\om\BasePRAction;

/**
 *
 * @author Lionel Bouzonville
 */
class PRAction extends BasePRAction
{
    /**
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getInitials();
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

}
