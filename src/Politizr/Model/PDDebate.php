<?php

namespace Politizr\Model;

use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Count;

use StudioEcho\Lib\StudioEchoUtils;

use Politizr\Model\om\BasePDDebate;

use Politizr\Constant\PathConstants;
use Politizr\Constant\ObjectTypeConstants;
use Politizr\Constant\TagConstants;
use Politizr\Constant\LabelConstants;

/**
 * Debate model object
 *
 * @author Lionel Bouzonville
 */
class PDDebate extends BasePDDebate implements PDocumentInterface
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
        return ObjectTypeConstants::TYPE_DEBATE;
    }

    /**
     * @see PDocumentInterface::getDebateId
     */
    public function getDebateId()
    {
        return $this->getId();
    }

    /**
     * @see PDocumentInterface::getDebate
     */
    public function getDebate()
    {
        return $this;
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
     * @see PDocumentInterface::isDisplayed
     */
    public function isDisplayed()
    {
        return $this->getOnline() && $this->getPublished();
    }

    /**
     *
     */
    public function getPathFileName()
    {
        $default = 'default_document.jpg';
        $path = PathConstants::DEBATE_UPLOAD_WEB_PATH.$default;
        if ($fileName = $this->getFileName()) {
            $path = PathConstants::DEBATE_UPLOAD_WEB_PATH.$fileName;
        }

        return $path;
    }

    /**
     * Return constraints to be applied before publication
     *
     * @return Collection
     */
    public function getPublishConstraints()
    {
        $collectionConstraint = new Collection(array(
            'title' => array(
                new NotBlank(['message' => 'Le titre ne doit pas être vide.']),
                new Length(['max' => 100, 'maxMessage' => 'Le titre doit contenir {{ limit }} caractères maximum.']),
            ),
            'description' => array(
                new NotBlank(['message' => 'Le texte de votre document ne doit pas être vide.']),
                // new Length(['min' => 140, 'minMessage' => 'Le corps de la publication doit contenir au moins {{ limit caractères.']),
            ),
            'tags' => new Count(['max' => 5, 'maxMessage' => 'Saisissez au maximum {{ limit }} thématiques.']),
            'localization' => new Count(['min' => 1, 'minMessage' => 'Le document doit être associé à une localisation.']),
        ));

        return $collectionConstraint;
    }

     /**
     * Override to manage accented characters
     * @return string
     */
    protected function createRawSlug()
    {
        $toSlug =  StudioEchoUtils::transliterateString($this->getTitle());
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
     * Compute a debate file name
     * @todo not used for the moment
     *
     * @return string
     */
    public function computeFileName()
    {
        $fileName = 'politizr-debat-' . StudioEchoUtils::randomString();

        return $fileName;
    }
    
    /* ######################################################################################################## */
    /*                                                      TAGS                                                */
    /* ######################################################################################################## */

    /**
     * Debate's array tags
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
     * Debate's array tags
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

    /* ######################################################################################################## */
    /*                                                  COMMENTS                                                */
    /* ######################################################################################################## */

    /**
     * @see ObjectTypeConstants::countComments
     */
    public function countComments($online = true, $paragraphNo = null, $onlyElected = null)
    {
        $query = PDDCommentQuery::create()
            ->filterIfOnline($online)
            ->filterIfOnlyElected($onlyElected)
            ->filterIfParagraphNo($paragraphNo);
        
        return parent::countPDDComments($query);
    }

    /**
     * @see ObjectTypeConstants::getComments
     */
    public function getComments($online = true, $paragraphNo = null, $orderBy = null)
    {
        $query = PDDCommentQuery::create()
            ->filterIfOnline($online)
            ->filterIfParagraphNo($paragraphNo)
            ->_if($orderBy)
                ->orderBy($orderBy[0], $orderBy[1])
            ->_else()
                ->orderBy('p_d_d_comment.created_at', 'desc')
            ->_endif();

        return parent::getPDDComments($query);
    }
    
    /* ######################################################################################################## */
    /*                                                 FOLLOWERS                                                */
    /* ######################################################################################################## */

    /**
     * Debate's followers
     *
     * @param boolean $qualified
     * @param boolean $online
     * @param PUserQuery $query
     * @return PropelCollection[PUser]
     */
    public function getFollowers($qualified = null, $online = true, $query = null)
    {
        if (null === $query) {
            $query = PUserQuery::create();
        }

        $query = $query
            ->filterIfQualified($qualified)
            ->filterIfOnline($online)
            ->setDistinct();
        
        return parent::getPuFollowDdPUsers($query);
    }

    /**
     * Debate's followers count
     *
     * @param boolean $qualified
     * @param boolean $online
     * @return integer
     */
    public function countFollowers($qualified = null, $online = true)
    {
        $query = PUserQuery::create()
            ->filterIfQualified($qualified)
            ->filterIfOnline($online)
            ->setDistinct();
        
        return parent::countPuFollowDdPUsers($query);
    }

    /**
     * Debate's qualified followers
     *
     * @return PropelCollection[PUser]
     */
    public function getFollowersQ()
    {
        return $this->getFollowers(true);
    }

    /**
     * Debate's qualified followers count
     *
     * @return integer
     */
    public function countFollowersQ()
    {
        return $this->countFollowers(true);
    }

    /**
     * Debate's citizen followers
     *
     * @return PropelCollection[PUser]
     */
    public function getFollowersC()
    {
        return $this->getFollowers(false);
    }

    /**
     * Debate's citizen followers count
     *
     * @return integer
     */
    public function countFollowersC()
    {
        return $this->countFollowers(false);
    }

    /* ######################################################################################################## */
    /*                                                   USERS                                                  */
    /* ######################################################################################################## */
    
    /**
     * @see getPUser
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
        return $this->isOwner($userId);
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

    /**
     *
     * @param integer $userId
     * @return boolean
     */
    public function isFollowedBy($userId)
    {
        $followers = PUFollowDDQuery::create()
            ->filterByPUserId($userId)
            ->filterByPDDebateId($this->getId())
            ->count();

        if ($followers > 0) {
            return true;
        }

        return false;
    }

    /* ######################################################################################################## */
    /*                                        REACTIONS / NESTEDSET                                             */
    /* ######################################################################################################## */

    /**
     * Nested tree reactions
     *
     * @param boolean $online
     * @param boolean $published
     * @return PropelCollection[PDReaction]
     */
    public function getTreeReactions($online = null, $published = null)
    {
        $treeReactions = PDReactionQuery::create()
            ->filterByTreeLevel(0, \Criteria::NOT_EQUAL)    // Exclusion du root node
            ->filterIfOnline($online)
            ->filterIfPublished($published)
            ->filterByPDDebateId($this->getId())
            ->findTree($this->getId())
            ;

        return $treeReactions;
    }

    /**
     * Debate's reactions children count
     *
     * @param boolean $online
     * @param boolean $published
     * @return int
     */
    public function countChildrenReactions($online = null, $published = null, $onlyElected = false)
    {
        $query = PDReactionQuery::create()
            ->filterByTreeLevel(1) // only first level
            ->filterIfOnline($online)
            ->filterIfPublished($published);

        if ($onlyElected) {
            $query = $query->onlyElected();
        }

        return parent::countPDReactions($query);
    }

    /**
     * Nested tree children
     *
     * @param boolean $online
     * @param boolean $published
     * @return PropelCollection[PDReaction]
     */
    public function getChildrenReactions($online = null, $published = null)
    {
        $rootNode = PDReactionQuery::create()->findRoot($this->getId());
        
        if ($rootNode) {
            $children = $rootNode->getChildrenReactions($online, $published);
            return $children;
        }

        return null;
    }

    /**
     * Debate's reactions count
     *
     * @param boolean $online
     * @param boolean $published
     * @return int
     */
    public function countReactions($online = null, $published = null, $onlyElected = false)
    {
        $query = PDReactionQuery::create()
            ->filterByTreeLevel(0, \Criteria::NOT_EQUAL) // no root node
            ->filterIfOnline($online)
            ->filterIfPublished($published);

        if ($onlyElected) {
            $query = $query->onlyElected();
        }

        return parent::countPDReactions($query);
    }


    /**
     * Last debate's published reaction
     *
     * @param integer $treeLevel
     * @return PDReaction
     */
    public function getLastPublishedReaction($treeLevel = null)
    {
        $reaction = PDReactionQuery::create()
            ->filterIfTreeLevel($treeLevel)
            ->filterByPDDebateId($this->getId())
            ->orderByPublishedAt('desc')
            ->findOne();

        return $reaction;
    }
}
