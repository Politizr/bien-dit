<?php

namespace Politizr\Model;

/**
 * Interface for managing document: debate, reaction
 *
 * @author Lionel Bouzonville
 */
interface PDocumentInterface
{
    /**
     * Test if the document is owned by the parameter's userId
     *
     * @param int $userId
     * @return boolean
     */
    public function isOwner($userId);

    /**
     * Test if the document is displayed in front: check online && published attributes
     *
     * @return boolean
     */
    public function isDisplayed();

    /**
     * Get the object type
     *
     * @return string
     */
    public function getType();

    /**
     * Filter the document's comments
     *
     * @param boolean $online
     * @param int $paragraphNo
     * @param array $orderBy
     * @return PropelCollection d'objets PDDComment
     */
    public function getComments($online = true, $paragraphNo = null, $orderBy = null);

    /**
     * Count the number of comments
     *
     * @param boolean $online
     * @param int $paragraphNo
     * @return int
     */
    public function countComments($online = true, $paragraphNo = null);

    /**
     * Get the tagged tags objects
     *
     * @return PropelCollection[PTag]
     */
    public function getTags();
}
