<?php

namespace Politizr\Model;

use Politizr\FrontBundle\Lib\Tag;

use Politizr\Model\om\BasePTag;

use Politizr\Constant\ObjectTypeConstants;

use StudioEcho\Lib\StudioEchoUtils;

/**
 * Tag object model
 *
 * @author Lionel Bouzonville
 */
class PTag extends BasePTag implements Tag
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

    /**
     *
     * @return string
     */
    public function getType()
    {
        return ObjectTypeConstants::TYPE_TAG;
    }

    /**
     *
     * @return int
     */
    public function getTagType()
    {
        return $this->getPTTagTypeId();
    }
    
    /* ######################################################################################################## */
    /*                                               DOCUMENTS                                                  */
    /* ######################################################################################################## */
    
    /**
     * Sum of count debates & reactions
     *
     * @param boolean $onlyPublished
     * @return integer
     */
    public function countDocuments($onlyPublished = true)
    {
        $queryDebate = PDDTaggedTQuery::create()
            ->_if($onlyPublished)
                ->usePDDebateQuery()
                    ->filterByPublished(true)
                ->endUse()
            ->_endif();

        $queryReaction = PDRTaggedTQuery::create()
            ->_if($onlyPublished)
                ->usePDReactionQuery()
                    ->filterByPublished(true)
                ->endUse()
            ->_endif();

        return $this->countDebates($queryDebate) + $this->countReactions($queryReaction);
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
    /*                                               REACTIONS                                                  */
    /* ######################################################################################################## */
    
    /**
     * @see parent::countPDDTaggedTs
     */
    public function countReactions($query = null)
    {
        return parent::countPDRTaggedTs($query);
    }

    /**
     * Tagged reactions
     */
    public function getReactions($online = null)
    {
        $reactions = PDReactionQuery::create()
            ->usePDRTaggedTQuery()
                ->filterByPTagId($this->getId())
            ->endUse()
            ->filterIfOnline($online)
            ->find();

        return $reactions;
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
     * @param boolean $online
     * @param array $notInUsersId
     * @return PropelCollection[PUser]
     */
    public function getUsers($online = null, $notInUsersId = null)
    {
        $users = PUserQuery::create()
            ->usePuTaggedTPUserQuery()
                ->filterByPTagId($this->getId())
            ->endUse()
            ->filterIfOnline($online)
            ->filterIfNotInIds($notInUsersId)
            ->find();

        return $users;
    }
}
