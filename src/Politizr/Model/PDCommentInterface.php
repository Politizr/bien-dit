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
     * Set the associated document id
     *
     * @param $documentId
     */
    public function setPDocumentId($documentId);
    
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
}
