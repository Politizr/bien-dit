<?php

namespace Politizr\Model;

use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Count;

use StudioEcho\Lib\StudioEchoUtils;

use Politizr\Model\om\BasePDDebate;

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
     * @see PDocumentInterface::isDisplayed
     */
    public function isDisplayed()
    {
        return $this->getOnline() && $this->getPublished();
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
                new NotBlank(['message' => 'Le titre ne doit pas être vide']),
                new Length(['max' => 100, 'maxMessage' => 'Le titre doit contenir {{ limit }} caractères maximum.']),
            ),
            // 'description' => array(
            //     new NotBlank(['message' => 'La description ne doit pas être vide']),
            //     new Length(['min' => 140, 'minMessage' => 'Le corps de la publication doit contenir au moins {{ limit }} caractères.']),
            // ),
            'geoTags' => new Count(['min' => 1, 'minMessage' => 'Saisissez au moins {{ limit }} thématique géographique parmi les départements, les régions, "France", "Europe" ou "Monde".']),
            'allTags' => new Count(['max' => 10, 'maxMessage' => 'Saisissez au maximum {{ limit }} thématiques.']),
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
     * Debate's array tags / geo tags world, europe, france, regions, departments
     * - used by publish constraints
     *
     * @return array[string]
     */
    public function getWorldToDepartmentGeoArrayTags()
    {
        $query = PTagQuery::create()
            ->select('Title')
            ->filterIfTypeId(TagConstants::TAG_TYPE_GEO)
            ->filterIfOnline(true)
            ->where('p_tag.id <= ?', TagConstants::TAG_GEO_DEPARTMENT_LAST_ID)
            ->orderByTitle()
            ->setDistinct();

        return parent::getPTags($query)->toArray();
    }

    /**
     * Debate's array tags
     * - used by elastica indexation
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

    /* ######################################################################################################## */
    /*                                                  COMMENTS                                                */
    /* ######################################################################################################## */

    /**
     * @see ObjectTypeConstants::countComments
     */
    public function countComments($online = true, $paragraphNo = null)
    {
        $query = PDDCommentQuery::create()
            ->filterIfOnline($online)
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
     * @param boolean $notifReaction notification subscribe
     * @param boolean $online
     * @return PropelCollection[PUser]
     */
    public function getFollowers($qualified = null, $notifReaction = null, $online = true)
    {
        $query = PUserQuery::create()
            ->filterIfQualified($qualified)
            ->filterIfNotifReaction($notifReaction)
            ->filterIfOnline($online)
            ->setDistinct();
        
        return parent::getPuFollowDdPUsers($query);
    }

    /**
     * Debate's followers who subscribe debate's reaction
     *
     * @return PropelCollection[PUser]
     */
    public function getNotifReactionFollowers()
    {
        return $this->getFollowers(null, true);
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
    /*                                           NOTIFICATIONS                                                  */
    /* ######################################################################################################## */

    /**
     * Check if follower $userId wants to be notified of debate update in the scope of $context
     *
     * @param integer $userId
     * @param string $context   @todo refactor migrate constant
     * @return boolean
     */
    public function isNotified($userId, $context = ObjectTypeConstants::CONTEXT_REACTION)
    {
        $puFollowDD = PUFollowDDQuery::create()
            ->filterByPUserId($userId)
            ->filterByPDDebateId($this->getId())
            ->findOne();

        if ($context == ObjectTypeConstants::CONTEXT_REACTION && $puFollowDD && $puFollowDD->getNotifReaction()) {
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
    public function countChildrenReactions($online = null, $published = null)
    {
        $query = PDReactionQuery::create()
            ->filterByTreeLevel(1) // only first level
            ->filterIfOnline($online)
            ->filterIfPublished($published);

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
    public function countReactions($online = null, $published = null)
    {
        $query = PDReactionQuery::create()
            ->filterByTreeLevel(0, \Criteria::NOT_EQUAL) // no root node
            ->filterIfOnline($online)
            ->filterIfPublished($published);

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
