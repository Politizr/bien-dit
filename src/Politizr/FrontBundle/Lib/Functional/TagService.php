<?php
namespace Politizr\FrontBundle\Lib\Functional;

use Politizr\Exception\InconsistentDataException;

use Politizr\Constant\TagConstants;
use Politizr\Constant\ListingConstants;

use Politizr\Model\PDDebate;
use Politizr\Model\PDReaction;
use Politizr\Model\PTag;

use Politizr\Model\PTagQuery;
use Politizr\Model\PDDTaggedTQuery;
use Politizr\Model\PDRTaggedTQuery;

use \PropelCollection;

/**
 * Functional service for tag management.
 *
 * @author Lionel Bouzonville
 */
class TagService
{
    private $securityTokenStorage;
    private $securityAuthorizationChecker;
    
    private $tagManager;
    
    private $logger;

    /**
     *
     * @param @security.token_storage
     * @param @security.authorization_checker
     * @param @politizr.manager.tag
     * @param @logger
     */
    public function __construct(
        $securityTokenStorage,
        $securityAuthorizationChecker,
        $tagManager,
        $logger
    ) {
        $this->securityTokenStorage = $securityTokenStorage;
        $this->securityAuthorizationChecker =$securityAuthorizationChecker;

        $this->tagManager = $tagManager;

        $this->logger = $logger;
    }

    /* ######################################################################################################## */
    /*                                              PUBLIC FUNCTIONS                                            */
    /* ######################################################################################################## */
    
    /**
     * Get "alphabetical tags" listing
     * beta
     * @return PropelCollection[PTag]
     */
    public function getAlphabeticalTagsListing()
    {
        $tags = $this->tagManager->generateAlphabeticalTags();

        return $tags;
    }

    /**
     * Get the most popular tags
     *
     * @param string $keywords
     * @param int $tagTypeId
     * @return array[PTag]
     */
    public function getMostPopularTags($keywords = null, $tagTypeId = null)
    {
        // $this->logger->info('*** getMostPopularTags');
        // $this->logger->info('$keywords = '.print_r($keywords, true));
        // $this->logger->info('$tagTypeId = '.print_r($tagTypeId, true));

        $interval = null;
        if ($keywords && (in_array(ListingConstants::FILTER_KEYWORD_LAST_DAY, $keywords))) {
            $interval = 1;
        } elseif ($keywords && (in_array(ListingConstants::FILTER_KEYWORD_LAST_WEEK, $keywords))) {
            $interval = 7;
        } elseif ($keywords && (in_array(ListingConstants::FILTER_KEYWORD_LAST_MONTH, $keywords))) {
            $interval = 30;
        }

        $tagIds = $this->tagManager->generateMostPopularTagIds($tagTypeId, $interval);

        $tags = [];
        $counter = 1;
        foreach ($tagIds as $tagId) {
            $tags[] = PTagQuery::create()->findPk($tagId);
        
            if ($counter == ListingConstants::LISTING_TOP_TAGS_LIMIT) {
                break;
            }
            $counter++;
        }

        return $tags;
    }

    /**
     * Update debate tags
     *
     * @param PDDebate $debate
     * @param PropelCollection(PTag) $tags
     * @param int $tagTypeId
     */
    public function updateDebateTags(PDDebate $debate, PropelCollection $tags, $tagTypeId) {
        // remove existing tags
        $existingTaggedTags = PDDTaggedTQuery::create()
            ->_if($tagTypeId)
                ->usePTagQuery()
                    ->filterByPTTagTypeId($tagTypeId)
                ->endUse()
            ->_endif()
            ->filterByPDDebateId($debate->getId())
            ->find();

        foreach ($existingTaggedTags as $taggedTag) {
            $taggedTag->delete();
        }

        // add new ones
        foreach ($tags as $tag) {
            $this->addDebateTag($debate, $tag);
        }

        return true;
    }

    /**
     * Add a tag to a debate (if not already exist)
     *
     * @param PDDebate $debate
     * @param PTag $tag
     */
    public function addDebateTag(PDDebate $debate, PTag $tag)
    {
        // associate tag to debate
        $pddTaggedT = PDDTaggedTQuery::create()
            ->filterByPDDebateId($debate->getId())
            ->filterByPTagId($tag->getId())
            ->findOne();

        if (!$pddTaggedT) {
            return $this->tagManager->createDebateTag($debate->getId(), $tag->getId());
        }

        return null;
    }


    /**
     * Update reaction tags
     *
     * @param PDReaction $reaction
     * @param PropelCollection(PTag) $tags
     * @param int $tagTypeId
     */
    public function updateReactionTags(PDReaction $reaction, PropelCollection $tags, $tagTypeId) {
        // remove existing tags
        $existingTaggedTags = PDRTaggedTQuery::create()
            ->_if($tagTypeId)
                ->usePTagQuery()
                    ->filterByPTTagTypeId($tagTypeId)
                ->endUse()
            ->_endif()
            ->filterByPDReactionId($reaction->getId())
            ->find();

        foreach ($existingTaggedTags as $taggedTag) {
            $taggedTag->delete();
        }

        // add new ones
        foreach ($tags as $tag) {
            $this->addReactionTag($reaction, $tag);
        }

        return true;
    }

    /**
     * Add a tag to a reaction (if not already exist)
     *
     * @param PDReaction $reaction
     * @param PTag $tag
     */
    public function addReactionTag(PDReaction $reaction, PTag $tag)
    {
        // associate tag to reaction
        $pddTaggedT = PDRTaggedTQuery::create()
            ->filterByPDReactionId($reaction->getId())
            ->filterByPTagId($tag->getId())
            ->findOne();

        if (!$pddTaggedT) {
            return $this->tagManager->createReactionTag($reaction->getId(), $tag->getId());
        }

        return null;
    }

    /* ######################################################################################################## */
    /*                                              STATS FUNCTIONS                                             */
    /* ######################################################################################################## */
    
    /**
     * Get stats user data
     *
     * @param int $limit Max results returned
     * @return array([nb users], [tag labels])
     */
    public function getUserTopTagsStats($limit = 10) 
    {
        $stats = $this->tagManager->generateUserTopTagsStats($limit);
        $nbUsers = array();
        $labels = array();
        foreach ($stats as $key => $value) {
            $nbUsers[] = $value['nb_users'];
            $labels[] = $value['title'];
        }
        return [$nbUsers, $labels];
    }
    
    /**
     * Get stats debate data
     *
     * @param int $limit Max results returned
     * @return array([nb users], [tag labels])
     */
    public function getDebateTopTagsStats($limit = 10) 
    {
        $stats = $this->tagManager->generateDebateTopTagsStats($limit);
        $nbDebates = array();
        $labels = array();
        foreach ($stats as $key => $value) {
            $nbDebates[] = $value['nb_debates'];
            $labels[] = $value['title'];
        }
        return [$nbDebates, $labels];
    }
}
