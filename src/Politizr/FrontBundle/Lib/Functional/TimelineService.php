<?php
namespace Politizr\FrontBundle\Lib\Functional;

use StudioEcho\Lib\StudioEchoUtils;

use Politizr\Exception\InconsistentDataException;
use Politizr\Exception\BoxErrorException;

use Politizr\Constant\ObjectTypeConstants;

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

    /**
     * User's "My Politizr" timeline
     *
     * @param integer $userId
     * @param integer $offset
     * @param integer $count
     * @return string
     */
    private function generateTimelineRawSql($userId, $offset, $count = 10)
    {
        $this->logger->info('*** generateTimelineRawSql');
        $this->logger->info('userId = '.print_r($userId, true));
        $this->logger->info('offset = '.print_r($offset, true));
        $this->logger->info('count = '.print_r($count, true));
        
        // Récupération d'un tableau des ids des débats suivis
        $debateIds = $this->getFollowedDebatesIdsArray($userId);
        $inQueryDebateIds = implode(',', $debateIds);
        if (empty($inQueryDebateIds)) {
            $inQueryDebateIds = 0;
        }
        $this->logger->info('inQueryDebateIds = '.print_r($inQueryDebateIds, true));

        // Récupération d'un tableau des ids des users suivis
        $userIds = $this->getFollowedUsersIdsArray($userId);
        $inQueryUserIds = implode(',', $userIds);
        if (empty($inQueryUserIds)) {
            $inQueryUserIds = 0;
        }
        $this->logger->info('inQueryUserIds = '.print_r($inQueryUserIds, true));

        // Récupération d'un tableau des ids de mes débats
        $myDebateIds = $this->getMyDebateIdsArray($userId);
        $inQueryMyDebateIds = implode(',', $myDebateIds);
        if (empty($inQueryMyDebateIds)) {
            $inQueryMyDebateIds = 0;
        }
        $this->logger->info('inQueryMyDebateIds = '.print_r($inQueryMyDebateIds, true));

        // Récupération d'un tableau des ids de mes réactions
        $myReactionIds = $this->getMyReactionIdsArray($userId);
        $inQueryMyReactionIds = implode(',', $myReactionIds);
        if (empty($inQueryMyReactionIds)) {
            $inQueryMyReactionIds = 0;
        }
        $this->logger->info('inQueryMyReactionIds = '.print_r($inQueryMyReactionIds, true));

        $sql = $this->userManager->createTimelineRawSql(
            $userId,
            $inQueryDebateIds,
            $inQueryUserIds,
            $inQueryMyDebateIds,
            $inQueryMyReactionIds,
            $offset,
            $count
        );

        return $sql;
    }

   /**
     * Debate feed timeline
     *
     * @param integer $debateId
     * @return string
     */
    private function generateDebateFeedRawSql($debateId)
    {
        $this->logger->info('*** getSql');

        if ($this->securityAuthorizationChecker->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            $user = $this->securityTokenStorage->getToken()->getUser();
            $userId = $user->getId();
            $this->logger->info('userId = '.print_r($userId, true));

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

        $this->logger->info('inQueryUserIds = '.print_r($inQueryUserIds, true));
        $sql = $this->documentManager->createDebateFeedRawSql(
            $debateId,
            $inQueryUserIds
        );

        return $sql;
    }

   /**
     * User's detail timeline
     *
     * @param integer $userId
     * @param integer $offset
     * @param integer $count
     * @return string
     */
    private function generateUserDetailTimelineRawSql($userId, $offset, $count = 10)
    {
        $this->logger->info('*** getSql');

        if ($this->securityAuthorizationChecker->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            $user = $this->securityTokenStorage->getToken()->getUser();
            $currentUserId = $user->getId();
            $this->logger->info('currentUserId = '.print_r($currentUserId, true));
        } else {
            $inQueryUserIds = 0;
        }

        $sql = $this->userManager->createUserDetailTimelineRawSql(
            $userId,
            $offset,
            $count
        );

        return $sql;
    }

    /**
     * Execute SQL and hydrate TimelineRow model
     *
     * @param string $sql
     * @return array[TimelineRow]
     */
    private function hydrateTimelineRows($sql)
    {
        $this->logger->info('*** hydrateTimelineRows');

        $timeline = array();

        if ($sql) {
            $con = \Propel::getConnection('default', \Propel::CONNECTION_READ);

            // dump($sql);

            $stmt = $con->prepare($sql);
            $stmt->execute();

            $result = $stmt->fetchAll();

            // dump($result);

            foreach ($result as $row) {
                $timelineRow = new TimelineRow();

                $timelineRow->setId($row['id']);
                $timelineRow->setTitle($row['title']);
                $timelineRow->setPublishedAt($row['published_at']);
                $timelineRow->setType($row['type']);

                $timeline[] = $timelineRow;
            }
        }

        return $timeline;
    }

    /* ######################################################################################################## */
    /*                                              GENERIC TIMELINE                                            */
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
     * Get the "My Politizr" timeline
     *
     * @param integer $userId
     * @param integer $offset
     * @return array[TimelineRow]
     */
    public function generateMyPolitizrTimeline($userId, $offset = 0)
    {
        $this->logger->info('*** generateMyPolitizrTimeline');
        
        $sql = $this->generateTimelineRawSql($userId, $offset);
        $timeline = $this->hydrateTimelineRows($sql);

        return $timeline;
    }

    /* ######################################################################################################## */
    /*                                            DEBATE FEED TIMELINE                                          */
    /* ######################################################################################################## */

    /**
     * Get the debate feed timeline
     *
     * @param integer $debateId
     * @return array[TimelineRow]
     */
    public function generateDebateFeedTimeline($debateId)
    {
        $this->logger->info('*** generateDebateFeedTimeline');
        
        $sql = $this->generateDebateFeedRawSql($debateId);
        $timeline = $this->hydrateTimelineRows($sql);

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
    public function generateUserDetailTimeline($userId, $offset = 0)
    {
        $this->logger->info('*** generateUserDetailTimeline');
        
        $sql = $this->generateUserDetailTimelineRawSql($userId, $offset);
        $timeline = $this->hydrateTimelineRows($sql);

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
