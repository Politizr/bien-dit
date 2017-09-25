<?php

namespace Politizr\Model;

/**
 * Common methods for PDDComment & PDRComment
 *
 * @author Lionel Bouzonville
 */
interface PDCommentInterface
{
    /**
     * Get the object type
     *
     * @return string
     */
    public function getType();

    /**
     * Get associated document's type
     *
     * @return string
     */
    public function getPDocumentType();

    /**
     * Get associated document
     *
     * @return ObjectTypeConstants
     */
    public function getPDocument();

    /**
     * Get associated topic id
     *
     * @return ObjectTypeConstants
     */
    public function getTopicId();
}
