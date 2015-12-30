<?php
namespace Politizr\FrontBundle\Lib\Functional;

use Politizr\Constant\ObjectTypeConstants;
use Politizr\Constant\ListingConstants;

use Politizr\Model\PDDebateQuery;
use Politizr\Model\PDReactionQuery;
use Politizr\Model\PDDCommentQuery;
use Politizr\Model\PDRCommentQuery;
use Politizr\Model\PUFollowDDQuery;
use Politizr\Model\PUFollowUQuery;

use Politizr\FrontBundle\Lib\TimelineRow;

/**
 * Functional service for timeline management.
 *
 * @author Lionel Bouzonville
 */
class TimelineService
{
    private $securityTokenStorage;
    private $securityAuthorizationChecker;

    private $templating;

    private $userManager;
    private $documentManager;

    private $logger;

    /**
     *
     * @param @security.token_storage
     * @param @security.authorization_checker
     * @param @templating
     * @param @politizr.manager.user
     * @param @politizr.manager.document
     * @param @logger
     */
    public function __construct(
        $securityTokenStorage,
        $securityAuthorizationChecker,
        $templating,
        $userManager,
        $documentManager,
        $logger
    ) {
        $this->securityTokenStorage = $securityTokenStorage;
        $this->securityAuthorizationChecker =$securityAuthorizationChecker;

        $this->templating = $templating;

        $this->userManager = $userManager;
        $this->documentManager = $documentManager;
        
        $this->logger = $logger;
    }

    /* ######################################################################################################## */
    /*                                               PRIVATE FUNCTIONS                                          */
    /* ######################################################################################################## */

    /**
     * Get array of user's PUFollowDD's ids
     *
     * @param integer $userId
     * @return array
     */
    private function getFollowedDebatesIdsArray($userId)
    {
        $debateIds = PUFollowDDQuery::create()
            ->select('PDDebateId')
            ->filterByPUserId($userId)
            ->find()
            ->toArray();

        return $debateIds;
    }

    /**
     * Get array of user's PUFollowU's ids
     *
     * @param integer $userId
     * @return array
     */
    private function getFollowedUsersIdsArray($userId)
    {
        $userIds = PUFollowUQuery::create()
            ->select('PUserId')
            ->filterByPUserFollowerId($userId)
            ->find()
            ->toArray();

        return $userIds;
    }

    /**
     * Get array of user's PDDebate's ids
     *
     * @param integer $userId
     * @return array
     */
    private function getMyDebateIdsArray($userId)
    {
        $myDebateIds = PDDebateQuery::create()
            ->select('Id')
            ->filterByPUserId($userId)
            ->find()
            ->toArray();

        return $myDebateIds;
    }

    /**
     * Get array of user's PDReaction's ids
     *
     * @param integer $userId
     * @return array
     */
    private function getMyReactionIdsArray($userId)
    {
        $myReactionIds = PDReactionQuery::create()
            ->select('Id')
            ->filterByPUserId($userId)
            ->find()
            ->toArray();

        return $myReactionIds;
    }

    /* ######################################################################################################## */
    /*                                        USEFUL GENERIC FUNCTIONS                                          */
    /* ######################################################################################################## */
    
    /**
     * Add a date key to TimelineRow array for indexing the associated published TimelineRow elements.
     *
     * @param array TimelineRow
     * @return array TimelineRow
     */
    public function generateTimelineDateKey($timeline)
    {
        $timelineDateKey = array();

        foreach ($timeline as $timelineRow) {
            $publishedAt = new \DateTime($timelineRow->getPublishedAt());
            $publishedAt->setTime(0, 0, 0);
            $dateKey = $publishedAt->format('Y-m-d H:i:s');

            $timelineDateKey[$dateKey][] = $timelineRow;
        }

        return $timelineDateKey;
    }
    
    /* ######################################################################################################## */
    /*                                            MY POLITIZR TIMELINE                                          */
    /* ######################################################################################################## */
    
    /**
     * Get the "My Politizr" paginated timeline
     *
     * @param integer $userId
     * @param integer $offset
     * @param integer $count
     * @return array[TimelineRow]
     */
    public function getMyTimelinePaginatedListing($userId, $offset = 0, $count = ListingConstants::TIMELINE_CLASSIC_PAGINATION)
    {
        $this->logger->info('*** getMyTimelineDateKeyPaginatedListing');
        $this->logger->info('userId = '.print_r($userId, true));
        $this->logger->info('offset = '.print_r($offset, true));
        $this->logger->info('count = '.print_r($count, true));
        
        // Récupération d'un tableau des ids des débats suivis
        $debateIds = $this->getFollowedDebatesIdsArray($userId);
        $inQueryDebateIds = implode(',', $debateIds);
        if (empty($inQueryDebateIds)) {
            $inQueryDebateIds = 0;
        }

        // Récupération d'un tableau des ids des users suivis
        $userIds = $this->getFollowedUsersIdsArray($userId);
        $inQueryUserIds = implode(',', $userIds);
        if (empty($inQueryUserIds)) {
            $inQueryUserIds = 0;
        }

        // Récupération d'un tableau des ids de mes débats
        $myDebateIds = $this->getMyDebateIdsArray($userId);
        $inQueryMyDebateIds = implode(',', $myDebateIds);
        if (empty($inQueryMyDebateIds)) {
            $inQueryMyDebateIds = 0;
        }

        // Récupération d'un tableau des ids de mes réactions
        $myReactionIds = $this->getMyReactionIdsArray($userId);
        $inQueryMyReactionIds = implode(',', $myReactionIds);
        if (empty($inQueryMyReactionIds)) {
            $inQueryMyReactionIds = 0;
        }

        $timeline = $this->userManager->generateMyTimelinePaginatedListing(
            $userId,
            $inQueryDebateIds,
            $inQueryUserIds,
            $inQueryMyDebateIds,
            $inQueryMyReactionIds,
            $offset,
            $count
        );

        return $timeline;
    }

    /* ######################################################################################################## */
    /*                                            DEBATE FEED TIMELINE                                          */
    /* ######################################################################################################## */

    /**
     * Get the debate feed timeline indexed by date key
     *
     * @param integer $debateId
     * @return array[TimelineRow]
     */
    public function getDebateFeedTimeline($debateId)
    {
        $this->logger->info('*** getDebateFeedTimeline');
        $this->logger->info('$debateId = ' . print_r($debateId, true));
        
        if ($this->securityAuthorizationChecker->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            $user = $this->securityTokenStorage->getToken()->getUser();
            $userId = $user->getId();

            // Récupération d'un tableau des ids des users suivis
            $userIds = $this->getFollowedUsersIdsArray($user->getId());

            $inQueryUserIds = implode(',', $userIds);
            if (empty($inQueryUserIds)) {
                $inQueryUserIds = $userId;
            } else {
                $inQueryUserIds .= ',' . $userId;
            }
        } else {
            $inQueryUserIds = 0;
        }

        $timeline = $this->documentManager->generateDebateFeedTimeline(
            $debateId,
            $inQueryUserIds
        );

        return $timeline;
    }


    /* ######################################################################################################## */
    /*                                            USER DETAIL TIMELINE                                          */
    /* ######################################################################################################## */

    /**
     * Get the user detail timeline
     *
     * @param integer $userId
     * @param integer $offset
     * @return array[TimelineRow]
     */
    public function getUserDetailTimelinePaginatedListing($userId, $offset = 0, $count = ListingConstants::TIMELINE_USER_CLASSIC_PAGINATION)
    {
        $this->logger->info('*** getUserDetailTimelinePaginatedListing');
        $this->logger->info('userId = '.print_r($userId, true));
        $this->logger->info('offset = '.print_r($offset, true));
        $this->logger->info('count = '.print_r($count, true));

        $timeline = $this->userManager->generateUserDetailTimelinePaginatedListing($userId, $offset, $count);

        return $timeline;
    }


    /* ######################################################################################################## */
    /*                                             RENDERING FUNCTIONS                                          */
    /* ######################################################################################################## */

    /**
     * Generate the rendering of an item debate timeline row
     *
     * @param integer $debateId
     * @param boolean $debateContext
     * @return string
     */
    public function generateRenderingItemDebate($debateId, $debateContext)
    {
        $user = $this->securityTokenStorage->getToken()->getUser();
        $debate = PDDebateQuery::create()->findPk($debateId);

        $authorIsMe = false;
        $authorIsFollowed = false;
        $debateIsFollowed = false;
        if ($user) {
            $authorIsMe = ($debate->getPUserId() === $user->getId());
            if ($authorIsMe) {
                $author = $debate->getUser();
                if ($author) {
                    $authorIsFollowed = $author->isFollowedBy($author->getId());
                }
                $debateIsFollowed = $debate->isFollowedBy($user->getId());
            }
        }

        $html = $this->templating->render(
            'PolitizrFrontBundle:Timeline:_itemDebate.html.twig',
            array(
                'debate' => $debate,
                'debateContext' => $debateContext,
                'authorIsMe' => $authorIsMe,
                'authorIsFollowed' => $authorIsFollowed,
                'debateIsFollowed' => $debateIsFollowed,
            )
        );

        return $html;
    }

    /**
     * Generate the rendering of an item reaction timeline row
     *
     * @param integer $reactionId
     * @param boolean $debateContext
     * @return string
     */
    public function generateRenderingItemReaction($reactionId, $debateContext)
    {
        $user = $this->securityTokenStorage->getToken()->getUser();
        $reaction = PDReactionQuery::create()->findPk($reactionId);

        $parentReaction = null;
        if ($reaction->getLevel() > 1) {
            $parentReaction = $reaction->getParent();
        }
        $parentDebate = $reaction->getDebate();

        $authorIsMe = false;
        $authorIsFollowed = false;
        $debateIsFollowed = false;
        if ($user) {
            $debateIsFollowed = $parentDebate->isFollowedBy($user->getId());
            $authorIsMe = ($reaction->getPUserId() === $user->getId());
            if (!$authorIsMe) {
                $author = $reaction->getUser();
                if ($author) {
                    $authorIsFollowed = $author->isFollowedBy($user->getId());
                }
            }
        }

        $html = $this->templating->render(
            'PolitizrFrontBundle:Timeline:_itemReaction.html.twig',
            array(
                'reaction' => $reaction,
                'debateContext' => $debateContext,
                'parentDebate' => $parentDebate,
                'parentReaction' => $parentReaction,
                'authorIsMe' => $authorIsMe,
                'authorIsFollowed' => $authorIsFollowed,
                'debateIsFollowed' => $debateIsFollowed,
            )
        );

        return $html;
    }

    /**
     * Generate the rendering of an item debate comment timeline row
     *
     * @param integer $commentId
     * @param boolean $debateContext
     * @return string
     */
    public function generateRenderingItemDebateComment($commentId, $debateContext)
    {
        $user = $this->securityTokenStorage->getToken()->getUser();
        $comment = PDDCommentQuery::create()->findPk($commentId);
        $parentDebate = $comment->getPDocument();

        $authorIsMe = false;
        $authorIsFollowed = false;
        $debateIsFollowed = false;
        if ($user) {
            $debateIsFollowed = $parentDebate->isFollowedBy($user->getId());
            $authorIsMe = ($comment->getPUserId() === $user->getId());
            if (!$authorIsMe) {
                $author = $comment->getUser();
                if ($author) {
                    $authorIsFollowed = $author->isFollowedBy($user->getId());
                }
            }
        }

        $html = $this->templating->render(
            'PolitizrFrontBundle:Timeline:_itemComment.html.twig',
            array(
                'type' => ObjectTypeConstants::TYPE_DEBATE_COMMENT,
                'comment' => $comment,
                'debateContext' => $debateContext,
                'parentDebate' => $parentDebate,
                'parentReaction' => null,
                'authorIsMe' => $authorIsMe,
                'authorIsFollowed' => $authorIsFollowed,
                'debateIsFollowed' => $debateIsFollowed,
            )
        );

        return $html;
    }

    /**
     * Generate the rendering of an item reaction comment timeline row
     *
     * @param integer $commentId
     * @param boolean $debateContext
     * @return string
     */
    public function generateRenderingItemReactionComment($commentId, $debateContext)
    {
        $user = $this->securityTokenStorage->getToken()->getUser();
        $comment = PDRCommentQuery::create()->findPk($commentId);
        $parentReaction = $comment->getPDocument();
        $parentDebate = $parentReaction->getDebate();

        $authorIsMe = false;
        $authorIsFollowed = false;
        $debateIsFollowed = false;
        if ($user) {
            $debateIsFollowed = $parentDebate->isFollowedBy($user->getId());
            $authorIsMe = ($comment->getPUserId() === $user->getId());
            if (!$authorIsMe) {
                $author = $comment->getUser();
                if ($author) {
                    $authorIsFollowed = $author->isFollowedBy($user->getId());
                }
            }
        }

        $html = $this->templating->render(
            'PolitizrFrontBundle:Timeline:_itemComment.html.twig',
            array(
                'type' => ObjectTypeConstants::TYPE_REACTION_COMMENT,
                'comment' => $comment,
                'debateContext' => $debateContext,
                'parentDebate' => $parentDebate,
                'parentReaction' => $parentReaction,
                'authorIsMe' => $authorIsMe,
                'authorIsFollowed' => $authorIsFollowed,
                'debateIsFollowed' => $debateIsFollowed,
            )
        );

        return $html;
    }
}
