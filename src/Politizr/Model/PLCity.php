<?php

namespace Politizr\Model;

use Politizr\FrontBundle\Lib\Tag;

use Politizr\Constant\LocalizationConstants;
use Politizr\Constant\ObjectTypeConstants;
use Politizr\Constant\TagConstants;

use Politizr\FrontBundle\Lib\PLocalization;

use Politizr\Model\om\BasePLCity;

class PLCity extends BasePLCity implements Tag, PLocalization
{
    /**
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getNameReal();
    }

    /**
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->getNameReal();
    }

    /**
     *
     * @return string
     */
    public function getLocType()
    {
        return LocalizationConstants::TYPE_CITY;
    }

    /**
     *
     * @return string
     */
    public function getType()
    {
        return ObjectTypeConstants::TYPE_LOCALIZATION_CITY;
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
    public function getUsers($online = null, $query = null)
    {
        if (null === $query) {
            $query = PUserQuery::create();
        }

        $query->filterIfOnline($online);

        return parent::getPUsers($query);
    }

    /**
     *
     * @param boolean $online
     * @param PUserQuery $query
     * @return int
     */
    public function countUsers($online = null, $query = null)
    {
        if (null === $query) {
            $query = PUserQuery::create();
        }

        $query->filterIfOnline($online);

        return parent::countPUsers($query);
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
