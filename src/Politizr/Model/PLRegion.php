<?php

namespace Politizr\Model;

use Politizr\FrontBundle\Lib\Tag;

use Politizr\Constant\LocalizationConstants;
use Politizr\Constant\ObjectTypeConstants;
use Politizr\Constant\TagConstants;

use Politizr\FrontBundle\Lib\PLocalization;

use Politizr\Model\om\BasePLRegion;

class PLRegion extends BasePLRegion implements Tag, PLocalization
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
     *
     * @return string
     */
    public function getLocType()
    {
        return LocalizationConstants::TYPE_REGION;
    }

    /**
     *
     * @return string
     */
    public function getType()
    {
        return ObjectTypeConstants::TYPE_LOCALIZATION_REGION;
    }

    /**
     *
     * @return int
     */
    public function getTagType()
    {
        return TagConstants::TAG_TYPE_GEO;
    }

    /**
     *
     * @param boolean $online
     * @param PUserQuery $query
     * @return PropelCollection[PUser]
     */
    public function getUsers($online = true, $query = null)
    {
        if (null === $query) {
            $query = PUserQuery::create();
        }

        $users = $query
            ->filterIfOnline($online)
            ->usePLCityQuery()
                ->usePLDepartmentQuery()
                    ->filterByPLRegionId($this->getId())
                ->endUse()
            ->endUse()
            ->find();

        return $users;
    }

    /**
     *
     * @param boolean $online
     * @param PUserQuery $query
     * @return int
     */
    public function countUsers($online = true, $query = null)
    {
        if (null === $query) {
            $query = PUserQuery::create();
        }

        $nbUsers = $query
            ->filterIfOnline($online)
            ->usePLCityQuery()
                ->usePLDepartmentQuery()
                    ->filterByPLRegionId($this->getId())
                ->endUse()
            ->endUse()
            ->count();

        return $nbUsers;
    }

    /**
     * Sum of count debates & reactions
     *
     * @param boolean $onlyPublished
     * @return integer
     * @return int
     */
    public function countDocuments($onlyPublished = true)
    {
        $queryDebate = PDDebateQuery::create()
            ->_if($onlyPublished)
                ->online()
            ->_endif()
        ;

        $queryReaction = PDReactionQuery::create()
            ->_if($onlyPublished)
                ->online()
            ->_endif()
        ;

        return parent::countPDDebates($queryDebate) + parent::countPDDebates($queryReaction);
    }
}
