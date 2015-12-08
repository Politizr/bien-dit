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
     * Comment's user
     *
     * @return PUser
     */
    public function getUser()
    {
        return parent::getPUser();
    }
}
