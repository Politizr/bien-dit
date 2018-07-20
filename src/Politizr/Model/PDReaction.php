<?php

namespace Politizr\Model;

use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Count;

use Politizr\FrontBundle\Lib\Tools\StaticTools;

use Politizr\Constant\PathConstants;
use Politizr\Constant\ObjectTypeConstants;
use Politizr\Constant\TagConstants;
use Politizr\Constant\LabelConstants;

use Politizr\Model\om\BasePDReaction;

/**
 * Reaction model object
 *
 * @author Lionel Bouzonville
 */
class PDReaction extends BasePDReaction implements PDocumentInterface
{
    // simple upload management
    public $uploadedFileName;

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
     * @see PDocumentInterface::getType
     */
    public function getType()
    {
        return ObjectTypeConstants::TYPE_REACTION;
    }

    /**
     * @see PDocumentInterface::getDebateId
     */
    public function getDebateId()
    {
        $debate = $this->getDebate();

        if (!$debate) {
            return null;
        }
        
        return $debate->getId();
    }

    /**
     * @see PDocumentInterface::getCircle
     */
    public function getCircle()
    {
        $topic = $this->getPCTopic();
        if ($topic) {
            return $topic->getPCircle();
        }

        return null;
    }

    /**
     * @see PDocumentInterface::getCircle
     */
    public function getTopicId()
    {
        return $this->getPCTopicId();
    }

    /**
     * Check if reaction is active
     *
     * @return boolean
     */
    public function isActive()
    {
        $active = PDReactionQuery::create()
                    ->online()
                    ->filterById($this->getId())
                    ->count()
                    ;

        if ($active) {
            return true;
        }

        return false;
    }

    /**
     * @deprecated
     */
    public function getPathFileName()
    {
        $default = 'default_document.jpg';
        $path = PathConstants::REACTION_UPLOAD_WEB_PATH.$default;
        if ($fileName = $this->getFileName()) {
            $path = PathConstants::REACTION_UPLOAD_WEB_PATH.$fileName;
        }

        return $path;
    }

    /**
     * Return constraints to be applied before publication
     *
     * @param boolean $geoActive Geo constraint
     * @return Collection
     */
    public function getPublishConstraints($geoActive = true)
    {
        $constraints = array(
            'title' => array(
                new NotBlank(['message' => 'Le titre ne doit pas être vide.']),
                new Length(['max' => 100, 'maxMessage' => 'Le titre doit contenir {{ limit }} caractères maximum.']),
            ),
            'description' => array(
                new NotBlank(['message' => 'Le texte de votre document ne doit pas être vide.']),
                // new Length(['min' => 140, 'minMessage' => 'Le corps de la publication doit contenir au moins {{ limit caractères.']),
            ),
            'tags' => new Count(['max' => 5, 'maxMessage' => 'Saisissez au maximum {{ limit }} thématiques.']),
        );

        if ($geoActive) {
            $constraints['localization'] = new Count(['min' => 1, 'minMessage' => 'Le document doit être associé à une localisation.']);
        }

        $collectionConstraint = new Collection($constraints);

        return $collectionConstraint;
    }

    /**
     * Overide to manage update published doc without updating slug
     *
     * @see BasePDReaction::createSlug
     */
    protected function createSlug()
    {
        if ($this->getPublished()) {
            return $this->getSlug();
        }

        return parent::createSlug();
    }

    /**
     * Override to manage accented characters
     *
     * @see BasePDReaction::createRawSlug
     */
    protected function createRawSlug()
    {
        $toSlug =  StaticTools::transliterateString($this->getTitle());
        $slug = $this->cleanupSlugPart($toSlug);
        return $slug;
    }

    /**
     * Manage publisher information
     *
     * @param \PropelPDO $con
     */
    public function preSave(\PropelPDO $con = null)
    {
        $publisher = $this->getPUser();
        if ($publisher) {
            $this->setPublishedBy($publisher->getFullName());
        } else {
            $this->setPublishedBy(LabelConstants::USER_UNKNOWN);
        }

        return parent::preSave($con);
    }

    /**
     * Compute a reaction file name
     *
     * @return string
     */
    public function computeFileName()
    {
        $fileName = 'politizr-reaction-' . StaticTools::randomString();

        return $fileName;
    }
 
    /* ######################################################################################################## */
    /*                                                  DEBATE                                                  */
    /* ######################################################################################################## */

    /**
     * @see PDocumentInterface::getDebate
     */
    public function getDebate()
    {
        return parent::getPDDebate();
    }

    /* ######################################################################################################## */
    /*                                                      TAGS                                                */
    /* ######################################################################################################## */

    /**
     * Reaction's array tags
     *
     * @return array[string]
     */
    public function getArrayTags($tagTypeId = null, $online = true)
    {
        $query = PTagQuery::create()
            ->select('Title')
            ->filterIfTypeId($tagTypeId)
            ->filterIfOnline($online)
            ->orderByTitle()
            ->setDistinct();

        return parent::getPTags($query)->toArray();
    }

    /**
     * Reaction's array tags
     *
     * @return array[id => string]
     */
    public function getIndexedArrayTags($tagTypeId = null, $online = true)
    {
        $query = PTagQuery::create()
            ->filterIfTypeId($tagTypeId)
            ->filterIfOnline($online)
            ->orderByTitle()
            ->setDistinct();

        return parent::getPTags($query)->toKeyValue('Uuid', 'Title');
    }

    /**
     * @see PDocumentInterface::getTags
     */
    public function getTags($tagTypeId = null, $online = true)
    {
        $query = PTagQuery::create()
            ->filterIfTypeId($tagTypeId)
            ->filterIfOnline($online)
            // ->orderByTitle()
            ->setDistinct();

        return parent::getPTags($query);
    }

    /**
     * @see PDocumentInterface::isWithPrivateTag
     */
    public function isWithPrivateTag()
    {
        $query = PTagQuery::create()
            ->filterByPTTagTypeId(TagConstants::TAG_TYPE_PRIVATE)
            ->setDistinct();

        $nbResults = parent::countPTags($query);
        
        if ($nbResults > 0) {
            return true;
        }

        return false;
    }

    /**
     * @see PDocumentInterface::getPLocalizations
     */
    public function getPLocalizations()
    {
        $country = parent::getPLCountry();
        $region = parent::getPLRegion();
        $department = parent::getPLDepartment();
        $city = parent::getPLCity();

        $localizations = array();

        if ($country) {
            $localizations[] = $country;
        }
        if ($region) {
            $localizations[] = $region;
        }
        if ($department) {
            $localizations[] = $department;
        }
        if ($city) {
            $localizations[] = $city;
        }

        return $localizations;
    }


    /**
     * Stringifier of localizations
     * 
     * @return string
     */
    public function getLocalizations()
    {
        $localizations = $this->getPLocalizations();

        if (count($localizations) > 1) {
            return implode(' - ', $localizations);
        } elseif (count($localizations) == 1) {
            return $localizations[0];
        }

        return 'Aucune';
    }

    /* ######################################################################################################## */
    /*                                                  COMMENTS                                                */
    /* ######################################################################################################## */

    /**
     * @see PDocumentInterface::countComments
     */
    public function countComments($online = true, $paragraphNo = null, $onlyElected = null, $usersIds = null)
    {
        $query = PDRCommentQuery::create()
            ->filterIfOnline($online)
            ->filterIfOnlyElected($onlyElected)
            ->filterIfParagraphNo($paragraphNo);

        if ($usersIds) {
            $query = $query->filterByPUserId($usersIds);
        }
        
        return parent::countPDRComments($query);
    }

    /**
     * @see PDocumentInterface::getComments
     */
    public function getComments($online = true, $paragraphNo = null, $orderBy = null)
    {
        $query = PDRCommentQuery::create()
            ->filterIfOnline($online)
            ->filterIfParagraphNo($paragraphNo)
            ->_if($orderBy)
                ->orderBy($orderBy[0], $orderBy[1])
            ->_else()
                ->orderBy('p_d_r_comment.created_at', 'desc')
            ->_endif();

        return parent::getPDRComments($query);
    }
    
    /* ######################################################################################################## */
    /*                                                   USERS                                                  */
    /* ######################################################################################################## */

    /**
     * @see parent::getPUser
     */
    public function getUser()
    {
        return $this->getPUser();
    }

    /**
     * @see PDocumentInterface::isOwner
     */
    public function isDebateOwner($userId)
    {
        $debate = $this->getDebate();
        if ($debate && $debate->getPUserId() == $userId) {
            return true;
        }

        return false;
    }

    /**
     * @see PDocumentInterface::isOwner
     */
    public function isOwner($userId)
    {
        if ($this->getPUserId() == $userId) {
            return true;
        }

        return false;
    }

    /* ######################################################################################################## */
    /*                                               REACTIONS                                                  */
    /* ######################################################################################################## */

    /**
     * Parent reaction of exists
     *
     * @param boolean $online
     * @param boolean $published
     * @return PDReaction
     */
    public function getParentReaction($online = null, $published = null)
    {
        $parentReaction = null;
        if ($parentReactionId = $this->getParentReactionId()) {
            $parentReaction = PDReactionQuery::create()
                ->filterIfOnline($online)
                ->filterIfPublished($published)
                ->findPk($parentReactionId);
        }

        return $parentReaction;
    }

    /**
     * @see PDocumentInterface::getChildrenReactions
     */
    public function getChildrenReactions($online = null, $published = null, $usersIds = null)
    {
        $query = PDReactionQuery::create()
            ->filterIfOnline($online)
            ->filterIfPublished($published);

        if ($usersIds) {
            $query = $query->filterByPUserId($usersIds);
        }

        return parent::getChildren($query);
    }

    /**
     * Nested tree descendants
     *
     * @param boolean $online
     * @param boolean $published
     * @return PropelCollection[PDReaction]
     */
    public function getDescendantsReactions($online = null, $published = null)
    {
        $query = PDReactionQuery::create()
            ->filterIfOnline($online)
            ->filterIfPublished($published);

        return parent::getDescendants($query);
    }


    /**
     * Reaction's descendant count
     *
     * @param boolean $online
     * @param boolean $published
     * @return int
     */
    public function countDescendantsReactions($online = null, $published = null, $onlyElected = false)
    {
        $query = PDReactionQuery::create()
            ->filterIfOnline($online)
            ->filterIfPublished($published)
            ->orderByPublishedAt('desc');

        if ($onlyElected) {
            $query = $query->onlyElected();
        }

        return parent::countDescendants($query);
    }

    /**
     * Reaction's children count
     *
     * @param boolean $online
     * @param boolean $published
     * @param boolean $onlyElected
     * @param array $usersIds   Users ids
     * @return int
     */
    public function countChildrenReactions($online = null, $published = null, $onlyElected = false, $usersIds = null)
    {
        $query = PDReactionQuery::create()
            ->filterIfOnline($online)
            ->filterIfPublished($published)
            ->orderByPublishedAt('desc');

        if ($onlyElected) {
            $query = $query->onlyElected();
        }

        if ($usersIds) {
            $query = $query->filterByPUserId($usersIds);
        }

        return parent::countChildren($query);
    }

    /**
     * @see PDocumentInterface::countReactions
     */
    public function countReactions($online = null, $published = null, $onlyElected = false, $usersIds = null)
    {
        return $this->countChildrenReactions($online, $published, $onlyElected, $usersIds);
    }
}
