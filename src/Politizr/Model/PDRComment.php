<?php

namespace Politizr\Model;

use Politizr\Model\om\BasePDRComment;

use Politizr\Constant\ObjectTypeConstants;
use Politizr\Constant\LabelConstants;

class PDRComment extends BasePDRComment implements PDCommentInterface
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
        return ObjectTypeConstants::TYPE_REACTION_COMMENT;
    }

    /**
     * @see PDComment::getParentType
     */
    public function getPDocumentType()
    {
        return ObjectTypeConstants::TYPE_REACTION;
    }

    /**
     * @see PDComment::getPDocument
     */
    public function getPDocument()
    {
        return parent::getPDReaction();
    }

    /**
     * @see PDComment::getPDocument
     */
    public function getDocumentUuid()
    {
        $reaction = parent::getPDReaction();
        if ($reaction) {
            return $reaction->getUuid();
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
     * Renvoit le user associé à la réaction
     *
     * @return     PUser     Objet user
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
