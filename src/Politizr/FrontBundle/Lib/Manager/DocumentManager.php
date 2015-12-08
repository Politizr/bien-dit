<?php
namespace Politizr\FrontBundle\Lib\Manager;

use Politizr\Exception\InconsistentDataException;

use Politizr\Model\PDDebate;
use Politizr\Model\PDReaction;
use Politizr\Model\PDRTaggedT;

use Politizr\Model\PDDebateQuery;
use Politizr\Model\PDReactionQuery;

/**
 * DB manager service for document.
 *
 * @author Lionel Bouzonville
 */
class DocumentManager
{
    private $globalTools;

    private $logger;

    /**
     *
     * @param politizr.tools.global
     * @param @logger
     */
    public function __construct($globalTools, $logger)
    {
        $this->globalTools = $globalTools;
        
        $this->logger = $logger;
    }


    /* ######################################################################################################## */
    /*                                                  RAW SQL                                                 */
    /* ######################################################################################################## */

   /**
     * Debate feed timeline
     *
     * @see app/sql/debateFeed.sql
     *
     * @param integer $debateId
     * @param array $inQueryUserIds
     * @return string
     */
    public function createDebateFeedRawSql($debateId, $inQueryUserIds)
    {
        // Préparation requête SQL
        $sql = "
( SELECT p_d_reaction.id as id, p_d_reaction.title as title, p_d_reaction.description as summary, p_d_reaction.published_at as published_at, 'Politizr\\\Model\\\PDReaction' as type
FROM p_d_reaction
WHERE
    p_d_reaction.published = 1
    AND p_d_reaction.online = 1
    AND p_d_reaction.p_d_debate_id = ".$debateId."
    AND p_d_reaction.tree_level > 0
)

UNION DISTINCT

( SELECT p_d_d_comment.id as id, \"commentaire\" as title, p_d_d_comment.description as summary, p_d_d_comment.published_at as published_at, 'Politizr\\\Model\\\PDDComment' as type
FROM p_d_d_comment
WHERE
    p_d_d_comment.online = 1
    AND p_d_d_comment.p_d_debate_id = ".$debateId."
    AND p_d_d_comment.p_user_id IN (".$inQueryUserIds.")
)

UNION DISTINCT

( SELECT p_d_r_comment.id as id, \"commentaire\" as title, p_d_r_comment.description as summary, p_d_r_comment.published_at as published_at, 'Politizr\\\Model\\\PDRComment' as type
FROM p_d_r_comment
WHERE 
    p_d_r_comment.online = 1
    AND p_d_r_comment.p_d_reaction_id IN (
        # Requête \"Réactions descendantes au débat courant\"
        SELECT p_d_reaction.id as id
        FROM p_d_reaction
        WHERE
            p_d_reaction.published = 1
            AND p_d_reaction.online = 1
            AND p_d_reaction.p_d_debate_id = ".$debateId."
            AND p_d_reaction.tree_level > 0
            )
            AND p_d_r_comment.p_user_id IN (".$inQueryUserIds.")
    )

ORDER BY published_at ASC
    ";

        return $sql;
    }

   /**
     * My documents (drafts / publications)
     *
     * @see app/sql/myDocuments.sql
     *
     * @param integer $userId
     * @param boolean $published
     * @param integer $offset
     * @param integer $count
     * @return string
     */
    public function createMyDocumentsRawSql($userId, $published, $offset, $count = 10)
    {
        if ($published) {
            $published = 1;
        } else {
            $published = 0;
        }
        
        // Préparation requête SQL
        $sql = "
#  Réactions
( SELECT p_d_reaction.id as id, p_d_reaction.title as title, p_d_reaction.published_at as published_at, 'Politizr\\\Model\\\PDReaction' as type
FROM p_d_reaction
WHERE
    p_user_id = ".$userId."
    AND p_d_reaction.published = ".$published."
    AND p_d_reaction.online = 1
)

UNION DISTINCT

#  Débats
( SELECT p_d_debate.id as id, p_d_debate.title as title, p_d_debate.published_at as published_at, 'Politizr\\\Model\\\PDDebate' as type
FROM p_d_debate
WHERE
    p_user_id = ".$userId."
    AND p_d_debate.published = ".$published."
    AND p_d_debate.online = 1
)

ORDER BY published_at DESC
LIMIT ".$offset.", ".$count."
    ";

        return $sql;
    }

    /* ######################################################################################################## */
    /*                                            CRUD OPERATIONS                                               */
    /* ######################################################################################################## */

    /**
     * Create a new PDDebate associated with userId
     *
     * @param int $userId
     * @return PDDebate
     */
    public function createDebate($userId)
    {
        $debate = new PDDebate();
        
        $debate->setPUserId($userId);

        $debate->setNotePos(0);
        $debate->setNoteNeg(0);

        $debate->setOnline(true);
        $debate->setPublished(false);
        
        $debate->save();

        return $debate;
    }

    /**
     * Publish debate
     *
     * @param PDDebate $debate
     * @return PDDebate
     */
    public function publishDebate(PDDebate $debate)
    {
        if ($debate) {
            $description = $this->globalTools->removeEmptyParagraphs($debate->getDescription());

            $debate->setDescription($description);
            $debate->setPublished(true);
            $debate->setPublishedAt(time());

            $debate->save();
        }

        return $debate;
    }

    /**
     * Delete debate
     *
     * @param PDDebate $debate
     * @return integer
     */
    public function deleteDebate(PDDebate $debate)
    {
        $result = $debate->delete();

        return $result;
    }

    /**
     * Create a new PDReaction associated with userId, debateId and optionnaly parentId
     *
     * @param int $userId
     * @param int $debateId
     * @param int $parentId
     * @return PDReaction
     */
    public function createReaction($userId, $debateId, $parentId = null)
    {
        $reaction = new PDReaction();

        $reaction->setPDDebateId($debateId);
        
        $reaction->setPUserId($userId);

        $reaction->setNotePos(0);
        $reaction->setNoteNeg(0);
        
        $reaction->setOnline(true);
        $reaction->setPublished(false);

        $reaction->setParentReactionId($parentId);

        $reaction->save();

        return $reaction;
    }

    /**
     * Init reaction's tags by default = parent's ones
     *
     * @param PDReaction $reaction
     * @return PDReaction
     */
    public function initReactionTaggedTags($reaction)
    {
        $parentReactionId = $reaction->getParentReactionId();
        if ($parentReactionId) {
            $parent = PDReactionQuery::create()->findPk($parentReactionId);
        } else {
            $parent = $reaction->getDebate();
        }

        $tags = $parent->getTags();
        foreach ($tags as $tag) {
            $pdrTaggedT = new PDRTaggedT();

            $pdrTaggedT->setPTagId($tag->getId());
            $pdrTaggedT->setPDReactionId($reaction->getId());

            $pdrTaggedT->save();
        }

        return $reaction;
    }

    /**
     * Publish reaction w. relative nested set update
     *
     * @param PDReaction $reaction
     * @return PDReaction
     */
    public function publishReaction(PDReaction $reaction)
    {
        if ($reaction) {
            // get associated debate
            $debate = PDDebateQuery::create()->findPk($reaction->getPDDebateId());
            if (!$debate) {
                throw new InconsistentDataException('Debate n°'.$debateId.' not found.');
            }

            // nested set management
            if ($parentId = $reaction->getParentReactionId()) {
                $parent = PDReactionQuery::create()->findPk($parentId);
                if (!$parent) {
                    throw new InconsistentDataException('Parent reaction n°'.$parentId.' not found.');
                }
                $reaction->insertAsLastChildOf($parent);
            } else {
                $rootNode = PDReactionQuery::create()->findRoot($debate->getId());
                if (!$rootNode) {
                    $rootNode = $this->createReactionRootNode($debate->getId());
                }

                if ($nbReactions = $debate->countReactions() == 0) {
                    $reaction->insertAsFirstChildOf($rootNode);
                } else {
                    $reaction->insertAsNextSiblingOf($debate->getLastPublishedReaction(1));
                }
            }

            $description = $this->globalTools->removeEmptyParagraphs($reaction->getDescription());

            $reaction->setDescription($description);

            $reaction->setPublished(true);
            $reaction->setPublishedAt(time());
            $reaction->save();
        }

        return $reaction;
    }

    /**
     * Reaction nested set root node
     *
     * @param integer $debateId
     * @return PDReaction
     */
    public function createReactionRootNode($debateId)
    {
        $rootNode = new PDReaction();

        $rootNode->setPDDebateId($debateId);
        $rootNode->setTitle('ROOT NODE');
        $rootNode->setOnline(false);
        $rootNode->setPublished(false);

        $rootNode->makeRoot();
        $rootNode->save();

        return $rootNode;
    }

    /**
     * Delete reaction
     *
     * @param PDDebate $reaction
     * @return integer
     */
    public function deleteReaction(PDReaction $reaction)
    {
        $result = $reaction->delete();

        return $result;
    }
}
