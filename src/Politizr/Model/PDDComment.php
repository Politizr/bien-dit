<?php

namespace Politizr\Model;

use Politizr\Model\om\BasePDDComment;

use Politizr\Constant\LabelConstants;
use Politizr\Constant\ObjectTypeConstants;

/**
 *
 * @author Lionel Bouzonville
 */
class PDDComment extends BasePDDComment implements PDCommentInterface
{
    /**
     * Manage publisher information
     *
     * @param \PropelPDO $con
     */
    public function preInsert(\PropelPDO $con = null)
    {
        $this->setPublishedAt(time());

        $publisher = $this->getPUser();
        if ($publisher) {
            $this->setPublishedBy($publisher->getFullName());
        } else {
            $this->setPublishedBy(LabelConstants::USER_UNKNOWN);
        }

        return parent::preInsert($con);
    }

    /**
     * @see PDComment::getPDocumentType
     */
    public function getType()
    {
        return ObjectTypeConstants::TYPE_DEBATE_COMMENT;
    }

    /**
     * @see PDComment::getPDocumentType
     */
    public function getPDocumentType()
    {
        return ObjectTypeConstants::TYPE_DEBATE;
    }

    /**
     * @see PDComment::getPDocument
     */
    public function getPDocument()
    {
        return parent::getPDDebate();
    }

    /**
     * @see PDComment::getPDocument
     */
    public function getDocumentUuid()
    {
        $debate = parent::getPDDebate();
        if ($debate) {
            return $debate->getUuid();
        }
        return null;
    }
    /**
     * Return "strip_tag"ged description
     *
     * @return string
     */
    public function getStripTaggedDescription()
    {
        return html_entity_decode(strip_tags($this->getDescription()));
    }

    /**
     * Comment's user
     *
     * @return PUser
     */
    public function getUser()
    {
        return parent::getPUser();
    }

    /**
     * @see getPUser
     */
    public function getUserUuid()
    {
        $user = $this->getPUser();
        if ($user) {
            return $user->getUuid();
        }
        return null;
    }

    /**
     * @see PDCommentInterface::getTopicId
     */
    public function getTopicId()
    {
        return $this->getPDocument()->getTopicId();
    }

    /**
     * @see PDCommentInterface::getCircle
     */
    public function getCircle()
    {
        return $this->getPDocument()->getCircle();
    }

    /**
     * Check if comment is active
     *
     * @return boolean
     */
    public function isActive()
    {
        $active = PDDCommentQuery::create()
                    ->online()
                    ->filterById($this->getId())
                    ->count()
                    ;

        if ($active) {
            return true;
        }

        return false;
    }
}
