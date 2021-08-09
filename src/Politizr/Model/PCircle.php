<?php

namespace Politizr\Model;


use Politizr\Model\om\BasePCircle;

use Politizr\Model\PDocumentInterface;
use Politizr\Model\PDCommentInterface;
use Politizr\FrontBundle\Lib\Tools\StaticTools;
use Politizr\Constant\CircleConstants;

class PCircle extends BasePCircle
{
    /**
     *
     * @return string
     */
    public function __toString()
    {
        $title = $this->getTitle();

        if (!empty($title)) {
            return $this->getTitle();
        }

        return 'Pas de titre';
    }


    /**
     * 
     * @return boolean
     */
    public function isReadOnly()
    {

        return parent::getReadOnly();
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
        $toSlug =  StaticTools::transliterateString($this->getTitle());
        $slug = $this->cleanupSlugPart($toSlug);
        return $slug;
    }

    /**
     * Count number of topics related to this circle
     *
     * @param $online boolean
     * @return int
     */
    public function getNbTopics($online = true)
    {
        $nbTopics = PCTopicQuery::create()
            ->filterByPCircleId($this->getId())
            ->_if($online)
                ->filterByOnline(true)
            ->_endif()
            ->count();

        return $nbTopics;
    }

    /**
     * Compute if context allows new debate to be create or not
     *
     * @return boolean
     */
    public function canCreateDebate()
    {
        if ($this->isReadOnly()) {
            return false;
        } elseif ($this->getPCircleTypeId() == CircleConstants::CIRCLE_TYPE_BUDGETPART && ($this->getStep() == CircleConstants::CIRCLE_BUDGETPART_STEP2 || $this->getStep() == CircleConstants::CIRCLE_BUDGETPART_STEP3)) {
            return false;
        }

        return true;
    }

    /**
     * Compute if context allows new reaction to be create or not
     *
     * @return boolean
     */
    public function canCreateReaction()
    {
        if ($this->isReadOnly()) {
            return false;
        }

        return true;
    }

    /**
     * Compute if context allows new comment to be create or not
     *
     * @return boolean
     */
    public function canCreateComment()
    {
        if ($this->isReadOnly()) {
            return false;
        }

        return true;
    }


    /**
     * Compute if context allows document to be noted
     *
     * @param PDocumentInterface|PDCommentInterface $document
     * @return boolean
     */
    public function canNote($document = null)
    {
        // document in circle's in read only mode
        if ($this->getReadOnly()) {
                return false;
        }

        if ($document instanceof PDDebate) {
            if ($this->getPCircleTypeId() == CircleConstants::CIRCLE_TYPE_BUDGETPART
                    && ($this->getStep() == CircleConstants::CIRCLE_BUDGETPART_STEP1 || $this->getStep() == CircleConstants::CIRCLE_BUDGETPART_STEP2)
            ) {
                return false;
            }
        }

        return true;
    }
}
