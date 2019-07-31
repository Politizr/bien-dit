<?php

namespace Politizr\Model;

use Politizr\Model\om\BasePOSubscription;

/**
 *
 * @author Lionel Bouzonville
 */
class POSubscription extends BasePOSubscription
{
    /**
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getTitle();
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
     * Format title & price
     *
     * @return string
     */
    public function getTitleAndPrice()
    {
        return $this->getTitle() . ' - ' . number_format($this->getPrice(), 2, ',', ' ') .'€';
    }
}
