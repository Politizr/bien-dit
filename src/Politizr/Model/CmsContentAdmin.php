<?php

namespace Politizr\Model;

use Politizr\Model\om\BaseCmsContentAdmin;

class CmsContentAdmin extends BaseCmsContentAdmin
{

    /**
     * Override to manage accented characters
     * @return string
     */
    protected function createRawSlug()
    {
        $toSlug =  \StudioEchoBundles\Lib\StudioEchoUtils::transliterateString($this->getTitle());
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