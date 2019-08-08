<?php

namespace Politizr\Model;

use Politizr\Model\om\BaseCmsContentAdmin;

use Politizr\FrontBundle\Lib\Tools\StaticTools;


class CmsContentAdmin extends BaseCmsContentAdmin
{

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
        $slug = $this->limitSlugSize($slug);
        $slug = $this->makeSlugUnique($slug);
        return $slug;
    }

    /**
     *
     */
    public function __toString()
    {
        return $this->getTitle();
    }

    /**
     *
     */
    public function preUpdate(\PropelPDO $con = null)
    {
        if ($colUpd = $this->isColumnModified(CmsContentAdminPeer::TITLE)) {
            $this->slug = $this->createRawSlug();
        }
        
        return parent::preUpdate($con);
    }
}
