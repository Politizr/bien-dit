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
     * Test if the document's debate is owned by the parameter's userId
     *
     * @param int $userId
     * @return boolean
     */
    public function isDebateOwner($userId);

    /**
     * Get the object type
     *
     * @return string
     */
    public function getType();

    /**
     * Return debate id > id (PDDebate) or reaction's PDDebate id
     *
     * @return int
     */
    public function getDebateId();

    /**
     * Return debate > "this" or reaction's PDDebate
     *
     * @return PDDebate
     */
    public function getDebate();

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
     * @param int|array $tagTypeId
     * @param boolean $online
     * @return PropelCollection[PTag]
     */
    public function getTags($tagTypeId = null, $online = true);

    /**
     * Get the localizations objects
     *
     * @return array[PLocalization]
     */
    public function getPLocalizations();

    /**
     * Is document associated with a private tag
     *
     * @return boolean
     */
    public function isWithPrivateTag();

    /**
     * Get associated circle
     *
     * @return PCircle|null
     */
    public function getCircle();

    /**
     * Get associated topic id
     *
     * @return int|null
     */
    public function getTopicId();
}
