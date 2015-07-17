<?php
namespace Politizr\FrontBundle\Lib\Functional;

use StudioEcho\Lib\StudioEchoUtils;

use Politizr\Exception\InconsistentDataException;
use Politizr\Exception\FormValidationException;

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
    private $sc;

    /**
     *
     */
    public function __construct($serviceContainer)
    {
        $this->sc = $serviceContainer;
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
            ->select('Id')
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
     * @see app/sql/timeline.sql
     *
     * @todo:
     *   > + réactions sur les débats / réactions rédigés par le user courant
     *   > + commentaires sur les débats / réactions rédigés par le user courant
     *
     * @param integer $offset
     * @param integer $count
     * @return string
     */
    private function generateTimelineRawSql($offset, $count = 10)
    {
        $logger = $this->sc->get('logger');
        $logger->info('*** generateTimelineRawSql');
        
        // Retrieve used services
        $securityTokenStorage = $this->sc->get('security.token_storage');
        $userManager = $this->sc->get('politizr.manager.user');

        // Function process
        $user = $securityTokenStorage->getToken()->getUser();

        // Récupération user
        $userId = $user->getId();
        $logger->info('userId = '.print_r($userId, true));

        // Récupération d'un tableau des ids des débats suivis
        $debateIds = $this->getFollowedDebatesIdsArray($user->getId());
        $inQueryDebateIds = implode(',', $debateIds);
        if (empty($inQueryDebateIds)) {
            $inQueryDebateIds = 0;
        }
        $logger->info('inQueryDebateIds = '.print_r($inQueryDebateIds, true));

        // Récupération d'un tableau des ids des users suivis
        $userIds = $this->getFollowedUsersIdsArray($user->getId());
        $inQueryUserIds = implode(',', $userIds);
        if (empty($inQueryUserIds)) {
            $inQueryUserIds = 0;
        }
        $logger->info('inQueryUserIds = '.print_r($inQueryUserIds, true));

        // Récupération d'un tableau des ids de mes réactions
        $myReactionIds = $this->getMyReactionIdsArray($user->getId());
        $inQueryMyReactionIds = implode(',', $myReactionIds);
        if (empty($inQueryMyReactionIds)) {
            $inQueryMyReactionIds = 0;
        }
        $logger->info('inQueryMyReactionIds = '.print_r($inQueryMyReactionIds, true));

        // Récupération d'un tableau des ids de mes documents
        $myDocumentIds = $this->getMyReactionIdsArray($user->getId());
        $inQueryMyDocumentIds = implode(',', $myDocumentIds);
        if (empty($inQueryMyDocumentIds)) {
            $inQueryMyDocumentIds = 0;
        }
        $logger->info('inQueryMyDocumentIds = '.print_r($inQueryMyDocumentIds, true));

        $sql = $userManager->createTimelineRawSql(
            $userId,
            $inQueryDebateIds,
            $inQueryUserIds,
            $inQueryMyReactionIds,
            $inQueryMyDocumentIds,
            $offset,
            $count
        );

        return $sql;
    }

   /**
     * Debate feed timeline
     *
     * @see app/sql/debateFeed.sql
     *
     * @todo:
     *   > + réactions sur les débats / réactions rédigés par le user courant
     *   > + commentaires sur les débats / réactions rédigés par le user courant
     *
     * @param integer $debateId
     * @return string
     */
    private function generateDebateFeedRawSql($debateId)
    {
        $logger = $this->sc->get('logger');
        $logger->info('*** getSql');

        // Retrieve used services
        $securityTokenStorage = $this->sc->get('security.token_storage');
        $securityAuthorizationChecker =$this->get('security.authorization_checker');
        $documentManager = $this->sc->get('politizr.manager.document');

        // Function process
        if ($securityAuthorizationChecker->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            $user = $securityTokenStorage->getToken()->getUser();
            $userId = $user->getId();
            $logger->info('userId = '.print_r($userId, true));

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

        $logger->info('inQueryUserIds = '.print_r($inQueryUserIds, true));
        $sql = $documentManager->createDebateFeedRawSql(
            $debateId,
            $inQueryUserIds
        );

        return $sql;
    }

    /*
     * Execute SQL and hydrate TimelineRow model
     *
     * @param string $sql
     * @return array[TimelineRow]
     */
    private function hydrateTimelineRows($sql)
    {
        $logger = $this->sc->get('logger');
        $logger->info('*** hydrateTimelineRows');

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
     * @param integer $offset
     * @return array[TimelineRow]
     */
    public function generateMyPolitizrTimeline($offset = 0)
    {
        $logger = $this->sc->get('logger');
        $logger->info('*** generateMyPolitizrTimeline');
        
        $sql = $this->generateTimelineRawSql($offset);
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
        $logger = $this->sc->get('logger');
        $logger->info('*** generateDebateFeedTimeline');
        
        $sql = $this->generateDebateFeedRawSql($debateId);
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
        // Retrieve used services
        $securityTokenStorage = $this->sc->get('security.token_storage');
        $templating = $this->sc->get('templating');

        // Function process
        $user = $securityTokenStorage->getToken()->getUser();
        $debate = PDDebateQuery::create()->findPk($debateId);

        $authorIsMe = false;
        $authorIsFollowed = false;
        $debateIsFollowed = false;
        if ($user) {
            $authorIsMe = $debate->isUserId($user->getId());
            if (!$authorIsMe) {
                $author = $debate->getUser();
                if ($author) {
                    $authorIsFollowed = $author->isFollowedByUserId($author->getId());
                }
                $debateIsFollowed = $debate->isFollowedByUserId($user->getId());
            }
        }

        $html = $templating->render(
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
        // Retrieve used services
        $securityTokenStorage = $this->sc->get('security.token_storage');
        $templating = $this->sc->get('templating');

        // Function process
        $user = $securityTokenStorage->getToken()->getUser();
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
            $debateIsFollowed = $parentDebate->isFollowedByUserId($user->getId());
            $authorIsMe = $reaction->isUserId($user->getId());
            if (!$authorIsMe) {
                $author = $reaction->getUser();
                if ($author) {
                    $authorIsFollowed = $author->isFollowedByUserId($user->getId());
                }
            }
        }

        $html = $templating->render(
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
        // Retrieve used services
        $securityTokenStorage = $this->sc->get('security.token_storage');
        $templating = $this->sc->get('templating');

        // Function process
        $user = $securityTokenStorage->getToken()->getUser();
        $comment = PDDCommentQuery::create()->findPk($commentId);
        $parentDebate = $comment->getPDocument();

        $authorIsMe = false;
        $authorIsFollowed = false;
        $debateIsFollowed = false;
        if ($user) {
            $debateIsFollowed = $parentDebate->isFollowedByUserId($user->getId());
            $authorIsMe = $comment->isUserId($user->getId());
            if (!$authorIsMe) {
                $author = $comment->getUser();
                if ($author) {
                    $authorIsFollowed = $author->isFollowedByUserId($user->getId());
                }
            }
        }

        $html = $templating->render(
            'PolitizrFrontBundle:Timeline:_itemComment.html.twig',
            array(
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
        // Retrieve used services
        $securityTokenStorage = $this->sc->get('security.token_storage');
        $templating = $this->sc->get('templating');

        // Function process
        $user = $securityTokenStorage->getToken()->getUser();
        $comment = PDRCommentQuery::create()->findPk($commentId);
        $parentReaction = $comment->getPDocument();
        $parentDebate = $parentReaction->getDebate();

        $authorIsMe = false;
        $authorIsFollowed = false;
        $debateIsFollowed = false;
        if ($user) {
            $debateIsFollowed = $parentDebate->isFollowedByUserId($user->getId());
            $authorIsMe = $comment->isUserId($user->getId());
            if (!$authorIsMe) {
                $author = $comment->getUser();
                if ($author) {
                    $authorIsFollowed = $author->isFollowedByUserId($user->getId());
                }
            }
        }

        $html = $templating->render(
            'PolitizrFrontBundle:Timeline:_itemComment.html.twig',
            array(
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
