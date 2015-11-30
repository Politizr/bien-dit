<?php

namespace Politizr\Model;

use Politizr\Model\om\BasePTag;

use StudioEcho\Lib\StudioEchoUtils;

/**
 * Tag object model
 *
 * @author Lionel Bouzonville
 */
class PTag extends BasePTag
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
     * Override to manage accented characters
     * @return string
     */
    protected function createRawSlug()
    {
        $toSlug = StudioEchoUtils::transliterateString($this->getTitle());
        $slug = $this->cleanupSlugPart($toSlug);
        return $slug;
    }

    /**
     * @see parent::getPTagRelatedByPTParentId
     */
    public function getPTParent(\PropelPDO $con = null, $doQuery = true)
    {
        return parent::getPTagRelatedByPTParentId($con, $doQuery);
    }
    
    /* ######################################################################################################## */
    /*                                                 DEBATES                                                  */
    /* ######################################################################################################## */
    
    /**
     * @see parent::countPDDTaggedTs
     */
    public function countDebates($query = null)
    {
        return parent::countPDDTaggedTs($query);
    }

    /**
     * Tagged debates
     */
    public function getDebates($online = null)
    {
        $debates = PDDebateQuery::create()
            ->usePDDTaggedTQuery()
                ->filterByPTagId($this->getId())
            ->endUse()
            ->filterIfOnline($online)
            ->find();

        return $debates;
    }
    
    /* ######################################################################################################## */
    /*                                                   USERS                                                  */
    /* ######################################################################################################## */
    
    /**
     * @see parent::countPuTaggedTPTags
     */
    public function countUsers($query = null)
    {
        return parent::countPuTaggedTPTags($query);
    }

    /**
     * Tagged users
     *
     * @param $online
     * @return PropelCollection[PUser]
     */
    public function getUsers($online = null)
    {
        $users = PUserQuery::create()
            ->usePuTaggedTPUserQuery()
                ->filterByPTagId($this->getId())
            ->endUse()
            ->filterIfOnline($online)
            ->find();

        return $users;
    }
}
