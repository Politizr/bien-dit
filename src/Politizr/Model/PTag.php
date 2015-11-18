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
     * Debate's tags
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
    public function countTaggedTagUsers($query = null)
    {
        return parent::countPuTaggedTPTags($query);
    }

    /**
     * Tagged tag's users
     *
     * @param $online
     * @return PropelCollection[PUser]
     */
    public function getTaggedTagUsers($online = null)
    {
        $users = PUserQuery::create()
            ->usePuTaggedTPUserQuery()
                ->filterByPTagId($this->getId())
            ->endUse()
            ->filterIfOnline($online)
            ->find();

        return $users;
    }

    /**
     * @see parent::countPuTaggedTPTags
     */
    public function countFollowedTagUsers($query = null)
    {
        return parent::countPuFollowTPUsers($query);
    }

    /**
     * Follow tag's users
     *
     * @param $online
     * @return PropelCollection[PUser]
     */
    public function getFollowTagUsers($online = null)
    {
        $users = PUserQuery::create()
            ->usePuFollowTPUserQuery()
                ->filterByPTagId($this->getId())
            ->endUse()
            ->filterIfOnline($online)
            ->find();

        return $users;
    }
}
