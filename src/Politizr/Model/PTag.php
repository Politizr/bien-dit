<?php

namespace Politizr\Model;

use Politizr\FrontBundle\Lib\Tag;

use Politizr\Model\om\BasePTag;

use Politizr\Constant\ObjectTypeConstants;

use Politizr\FrontBundle\Lib\Tools\StaticTools;

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
        $toSlug = StaticTools::transliterateString($this->getTitle());
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
    
    /**
     *
     * @return PUser
     */
    public function getOwner()
    {
        return $this->getPOwner();
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
     * @param PUserQuery $query
     * @return PropelCollection[PUser]
     */
    public function getUsers($online = null, $query = null)
    {
        if (null === $query) {
            $query = PUserQuery::create();
        }

        $users = $query
            ->usePuTaggedTPUserQuery()
                ->filterByPTagId($this->getId())
            ->endUse()
            ->filterIfOnline($online)
            ->find();

        return $users;
    }
}
