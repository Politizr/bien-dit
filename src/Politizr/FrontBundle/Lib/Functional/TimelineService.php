<?php
namespace Politizr\FrontBundle\Lib\Functional;

use Politizr\Exception\InconsistentDataException;

use Politizr\Constant\ObjectTypeConstants;
use Politizr\Constant\ReputationConstants;
use Politizr\Constant\ListingConstants;

use Politizr\Model\PDDebateQuery;
use Politizr\Model\PDReactionQuery;
use Politizr\Model\PDDCommentQuery;
use Politizr\Model\PDRCommentQuery;
use Politizr\Model\PUFollowDDQuery;
use Politizr\Model\PUFollowUQuery;
use Politizr\Model\PUserQuery;
use Politizr\Model\PRBadgeQuery;

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

        $reputationIds = ReputationConstants::getTimelineAuthorReputationIds();
        $inQueryReputationIds = implode(',', $reputationIds);
        if (empty($inQueryReputationIds)) {
            $inQueryReputationIds = 0;
        }

        $reputationIds2 = ReputationConstants::getTimelineTargetDebateReputationIds();
        $inQueryReputationIds2 = implode(',', $reputationIds2);
        if (empty($inQueryReputationIds2)) {
            $inQueryReputationIds2 = 0;
        }

        $reputationIds3 = ReputationConstants::getTimelineTargetReactionReputationIds();
        $inQueryReputationIds3 = implode(',', $reputationIds3);
        if (empty($inQueryReputationIds3)) {
            $inQueryReputationIds3 = 0;
        }

        $reputationIds4 = ReputationConstants::getTimelineTargetCommentReputationIds();
        $inQueryReputationIds4 = implode(',', $reputationIds4);
        if (empty($inQueryReputationIds4)) {
            $inQueryReputationIds4 = 0;
        }

        $timeline = $this->userManager->generateMyTimelinePaginatedListing(
            $userId,
            $inQueryDebateIds,
            $inQueryUserIds,
            $inQueryMyDebateIds,
            $inQueryMyReactionIds,
            $inQueryReputationIds,
            $inQueryReputationIds2,
            $inQueryReputationIds3,
            $inQueryReputationIds4,
            $offset,
            $count
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
     * Generate the rendering of an item action "note document" timeline row
     *
     * @param TimelineRow $timelineRow
     * @param boolean $withContext
     * @return string
     */
    public function generateRenderingItemActionNoteDocument($timelineRow, $withContext)
    {
        // get current user
        $user = $this->securityTokenStorage->getToken()->getUser();

        $actionId = $timelineRow->getId();
        $documentId = $timelineRow->getTargetId();

        $document = null;
        $author = null;

        if ($actionId == ReputationConstants::ACTION_ID_D_AUTHOR_DEBATE_NOTE_POS || $actionId == ReputationConstants::ACTION_ID_D_AUTHOR_DEBATE_NOTE_NEG) {
            $document = PDDebateQuery::create()->findPk($documentId);
        } else {
            $document = PDReactionQuery::create()->findPk($documentId);
        }

        if ($document === null) {
            return;
        }

        $author = PUserQuery::create()->findPk($document->getPUserId());

        $way = 'down';
        if ($actionId == ReputationConstants::ACTION_ID_D_AUTHOR_DEBATE_NOTE_POS || $actionId == ReputationConstants::ACTION_ID_D_AUTHOR_REACTION_NOTE_POS) {
            $way = 'up';
        }

        $html = $this->templating->render(
            'PolitizrFrontBundle:Reputation:_cardNoteDocument.html.twig',
            array(
                'timelineRow' => $timelineRow,
                'user' => $user,
                'document' => $document,
                'author' => $author,
                'way' => $way,
            )
        );

        return $html;
    }

    /**
     * Generate the rendering of an item action "note comment" timeline row
     *
     * @param TimelineRow $timelineRow
     * @param boolean $withContext
     * @return string
     */
    public function generateRenderingItemActionNoteComment($timelineRow, $withContext)
    {
        // get current user
        $user = $this->securityTokenStorage->getToken()->getUser();

        $actionId = $timelineRow->getId();
        $commentId = $timelineRow->getTargetId();
        $objectName = $timelineRow->getTargetObjectName();

        $comment = null;
        $author = null;

        if ($objectName == ObjectTypeConstants::TYPE_DEBATE_COMMENT) {
            $comment = PDDCommentQuery::create()->findPk($commentId);
        } else {
            $comment = PDRCommentQuery::create()->findPk($commentId);
        }

        if ($comment === null) {
            return;
        }

        $author = $comment->getPUser();

        $way = 'down';
        if ($actionId == ReputationConstants::ACTION_ID_D_AUTHOR_COMMENT_NOTE_POS) {
            $way = 'up';
        }

        $html = $this->templating->render(
            'PolitizrFrontBundle:Reputation:_cardNoteComment.html.twig',
            array(
                'timelineRow' => $timelineRow,
                'user' => $user,
                'comment' => $comment,
                'author' => $author,
                'way' => $way,
            )
        );

        return $html;
    }

    /**
     * Generate the rendering of an item action "follow user" timeline row
     *
     * @param TimelineRow $timelineRow
     * @param boolean $withContext
     * @return string
     */
    public function generateRenderingItemActionFollowUser($timelineRow, $withContext)
    {
        // get current user
        $user = $this->securityTokenStorage->getToken()->getUser();

        $actionId = $timelineRow->getId();
        $followId = $timelineRow->getTargetId();

        $followUser = null;
        $followUser = PUserQuery::create()->findPk($followId);

        if ($followUser === null) {
            return;
        }

        $way = 'down';
        if ($actionId == ReputationConstants::ACTION_ID_U_AUTHOR_USER_FOLLOW) {
            $way = 'up';
        }

        $html = $this->templating->render(
            'PolitizrFrontBundle:Follow:_cardFollowUser.html.twig',
            array(
                'timelineRow' => $timelineRow,
                'user' => $user,
                'followUser' => $followUser,
                'way' => $way,
            )
        );

        return $html;
    }

    /**
     * Generate the rendering of an item action "follow user" timeline row
     *
     * @param TimelineRow $timelineRow
     * @param boolean $withContext
     * @return string
     */
    public function generateRenderingItemActionFollowDebate($timelineRow, $withContext)
    {
        // get current user
        $user = $this->securityTokenStorage->getToken()->getUser();

        $actionId = $timelineRow->getId();
        $followId = $timelineRow->getTargetId();

        $followDebate = null;
        $followDebate = PDDebateQuery::create()->findPk($followId);
        if ($followDebate === null) {
            return;
        }

        $way = 'down';
        if ($actionId == ReputationConstants::ACTION_ID_D_AUTHOR_DEBATE_FOLLOW) {
            $way = 'up';
        }

        $html = $this->templating->render(
            'PolitizrFrontBundle:Follow:_cardFollowDebate.html.twig',
            array(
                'timelineRow' => $timelineRow,
                'user' => $user,
                'followDebate' => $followDebate,
                'way' => $way,
            )
        );

        return $html;
    }

    /**
     * Generate the rendering of an item action "followed by user" timeline row
     *
     * @param TimelineRow $timelineRow
     * @param boolean $withContext
     * @return string
     */
    public function generateRenderingItemActionSubscribeMe($timelineRow, $withContext)
    {
        // get current user
        $user = $this->securityTokenStorage->getToken()->getUser();

        $actionId = $timelineRow->getId();
        $subscriberId = $timelineRow->getTargetId();

        $subscriberUser = null;
        $subscriberUser = PUserQuery::create()->findPk($subscriberId);

        if ($subscriberUser === null) {
            return;
        }

        $html = $this->templating->render(
            'PolitizrFrontBundle:Follow:_cardSubscribeMe.html.twig',
            array(
                'timelineRow' => $timelineRow,
                'user' => $user,
                'subscriberUser' => $subscriberUser,
            )
        );

        return $html;
    }

    /**
     * Generate the rendering of an item action "someone subscribe my debate" timeline row
     *
     * @param TimelineRow $timelineRow
     * @param boolean $withContext
     * @return string
     */
    public function generateRenderingItemActionSubscribeMyDebate($timelineRow, $withContext)
    {
        // get current user
        $user = $this->securityTokenStorage->getToken()->getUser();

        $actionId = $timelineRow->getId();
        $debateId = $timelineRow->getTargetId();
        $subscriberId = $timelineRow->getTargetUserId();

        $debate = null;
        $debate = PDDebateQuery::create()->findPk($debateId);

        if ($debate === null) {
            return;
        }

        $subscriberUser = null;
        $subscriberUser = PUserQuery::create()->findPk($subscriberId);

        $html = $this->templating->render(
            'PolitizrFrontBundle:Follow:_cardSubscribeMyDebate.html.twig',
            array(
                'timelineRow' => $timelineRow,
                'user' => $user,
                'debate' => $debate,
                'subscriberUser' => $subscriberUser,
            )
        );

        return $html;
    }
    
    /**
     * Generate the rendering of an item action "someone note my debate / reaction" timeline row
     *
     * @param TimelineRow $timelineRow
     * @param boolean $withContext
     * @return string
     */
    public function generateRenderingItemActionNoteMyDocument($timelineRow, $withContext)
    {
        // get current user
        $user = $this->securityTokenStorage->getToken()->getUser();

        $actionId = $timelineRow->getId();
        $documentId = $timelineRow->getTargetId();
        $noteUserId = $timelineRow->getTargetUserId();

        $document = null;
        if ($actionId == ReputationConstants::ACTION_ID_D_TARGET_DEBATE_NOTE_POS || $actionId == ReputationConstants::ACTION_ID_D_TARGET_DEBATE_NOTE_NEG) {
            $document = PDDebateQuery::create()->findPk($documentId);
        } else {
            $document = PDReactionQuery::create()->findPk($documentId);
        }

        if ($document === null) {
            return;
        }

        $noteUser = null;
        $noteUser = PUserQuery::create()->findPk($noteUserId);

        $way = 'down';
        if ($actionId == ReputationConstants::ACTION_ID_D_TARGET_DEBATE_NOTE_POS || $actionId == ReputationConstants::ACTION_ID_D_TARGET_REACTION_NOTE_POS) {
            $way = 'up';
        }

        $html = $this->templating->render(
            'PolitizrFrontBundle:Reputation:_cardNoteMyDocument.html.twig',
            array(
                'timelineRow' => $timelineRow,
                'user' => $user,
                'document' => $document,
                'noteUser' => $noteUser,
                'way' => $way,
            )
        );

        return $html;
    }

    /**
     * Generate the rendering of an item action "someone note my comment" timeline row
     *
     * @param TimelineRow $timelineRow
     * @param boolean $withContext
     * @return string
     */
    public function generateRenderingItemActionNoteMyComment($timelineRow, $withContext)
    {
        // get current user
        $user = $this->securityTokenStorage->getToken()->getUser();

        $actionId = $timelineRow->getId();
        $objectName = $timelineRow->getTargetObjectName();

        $commentId = $timelineRow->getTargetId();
        $noteUserId = $timelineRow->getTargetUserId();

        $comment = null;
        if ($objectName == ObjectTypeConstants::TYPE_DEBATE_COMMENT) {
            $comment = PDDCommentQuery::create()->findPk($commentId);
        } else {
            $comment = PDRCommentQuery::create()->findPk($commentId);
        }

        if ($comment === null) {
            return;
        }

        $noteUser = null;
        $noteUser = PUserQuery::create()->findPk($noteUserId);

        $way = 'down';
        if ($actionId == ReputationConstants::ACTION_ID_D_TARGET_COMMENT_NOTE_POS) {
            $way = 'up';
        }

        $html = $this->templating->render(
            'PolitizrFrontBundle:Reputation:_cardNoteMyComment.html.twig',
            array(
                'timelineRow' => $timelineRow,
                'user' => $user,
                'comment' => $comment,
                'noteUser' => $noteUser,
                'way' => $way,
            )
        );

        return $html;
    }

    /**
     * Generate the rendering of an item badge timeline row
     *
     * @param TimelineRow $timelineRow
     * @return string
     */
    public function generateRenderingItemBadge($timelineRow)
    {
        // get current user
        $user = $this->securityTokenStorage->getToken()->getUser();

        $badgeId = $timelineRow->getId();
        $badge = PRBadgeQuery::create()->findPk($badgeId);

        if ($badge === null) {
            return;
        }

        $html = $this->templating->render(
            'PolitizrFrontBundle:Reputation:_cardBadge.html.twig',
            array(
                'timelineRow' => $timelineRow,
                'user' => $user,
                'badge' => $badge,
            )
        );

        return $html;
    }

    /**
     * Generate the rendering of an item "user" timeline row
     *
     * @param integer $userId
     * @return string
     */
    public function generateRenderingItemUser($userId)
    {
        $user = PUserQuery::create()->findPk($userId);

        if ($user === null) {
            return;
        }

        $html = $this->templating->render(
            'PolitizrFrontBundle:User:_cardUser.html.twig',
            array(
                'user' => $user,
            )
        );

        return $html;
    }

    /**
     * Generate the rendering of an item debate timeline row
     *
     * @param integer $debateId
     * @param boolean $withContext
     * @return string
     */
    public function generateRenderingItemDebate($debateId, $withContext)
    {
        $debate = PDDebateQuery::create()->findPk($debateId);

        if ($debate === null) {
            return;
        }

        $html = $this->templating->render(
            'PolitizrFrontBundle:Document:_card.html.twig',
            array(
                'document' => $debate,
                'mini' => false,
                'summary' => true,
            )
        );

        return $html;
    }

    /**
     * Generate the rendering of an item reaction timeline row
     *
     * @param integer $reactionId
     * @param boolean $withContext
     * @return string
     */
    public function generateRenderingItemReaction($reactionId, $withContext)
    {
        $reaction = PDReactionQuery::create()->findPk($reactionId);

        if ($reaction === null) {
            return;
        }

        $html = $this->templating->render(
            'PolitizrFrontBundle:Document:_card.html.twig',
            array(
                'document' => $reaction,
                'mini' => false,
                'withContext' => $withContext,
                'summary' => true,
            )
        );

        return $html;
    }

    /**
     * Generate the rendering of an item debate comment timeline row
     *
     * @param integer $commentId
     * @param boolean $withContext
     * @return string
     */
    public function generateRenderingItemDebateComment($commentId, $withContext)
    {
        $user = $this->securityTokenStorage->getToken()->getUser();
        $comment = PDDCommentQuery::create()->findPk($commentId);
        if ($comment === null) {
            return;
        }

        $parentDebate = $comment->getPDocument();
        if ($parentDebate === null) {
            return;
        }

        $html = $this->templating->render(
            'PolitizrFrontBundle:Comment:_card.html.twig',
            array(
                'comment' => $comment,
            )
        );

        return $html;
    }

    /**
     * Generate the rendering of an item reaction comment timeline row
     *
     * @param integer $commentId
     * @param boolean $withContext
     * @return string
     */
    public function generateRenderingItemReactionComment($commentId, $withContext)
    {
        $user = $this->securityTokenStorage->getToken()->getUser();
        $comment = PDRCommentQuery::create()->findPk($commentId);
        if ($comment === null) {
            return;
        }
        $parentReaction = $comment->getPDocument();
        if ($parentReaction === null) {
            return;
        }
        $parentDebate = $parentReaction->getDebate();
        if ($parentDebate === null) {
            return;
        }

        $html = $this->templating->render(
            'PolitizrFrontBundle:Comment:_card.html.twig',
            array(
                'comment' => $comment,
            )
        );

        return $html;
    }
}
