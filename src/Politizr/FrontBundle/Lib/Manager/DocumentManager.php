<?php
namespace Politizr\FrontBundle\Lib\Manager;

use Politizr\Exception\InconsistentDataException;

use Politizr\Model\PDDebate;
use Politizr\Model\PDReaction;

use Politizr\Model\PDDebateQuery;
use Politizr\Model\PDReactionQuery;

/**
 * DB manager service for document.
 *
 * @author Lionel Bouzonville
 */
class DocumentManager
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
    /*                                                  RAW SQL                                                 */
    /* ######################################################################################################## */

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
     * Publish reaction w. relative nested set update
     *
     * @param PDDebate $debate
     * @return PDDebate
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
                $rootNode = PDReactionQuery::create()->findOrCreateRoot($debate->getId());
                if ($nbReactions = $debate->countReactions() == 0) {
                    $reaction->insertAsFirstChildOf($rootNode);
                } else {
                    $reaction->insertAsNextSiblingOf($debate->getLastReaction(1));
                }
            }

            $reaction->setPublished(true);
            $reaction->setPublishedAt(time());
            $reaction->save();
        }

        return $debate;
    }

    /**
     * Delete reaction
     *
     * @param PDDebate $reaction
     * @return integer
     */
    public function deleteReaction(PDDebate $reaction)
    {
        $result = $reaction->delete();

        return $result;
    }
}
