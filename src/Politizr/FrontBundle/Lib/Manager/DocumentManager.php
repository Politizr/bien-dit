<?php
namespace Politizr\FrontBundle\Lib\Manager;

use Politizr\Exception\InconsistentDataException;

use Politizr\Constant\ObjectTypeConstants;
use Politizr\Constant\ReputationConstants;

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
     * @param @politizr.tools.global
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
     * Debates' suggestion for user.
     *
     * @see app/sql/suggestions.sql
     *
     * @return string
     */
    private function createUserSuggestedDebatesRawSql()
    {
        // Requête SQL
        $sql = "
SELECT DISTINCT
    id,
    uuid,
    p_user_id,
    title,
    file_name,
    copyright,
    description,
    note_pos,
    note_neg,
    nb_views,
    published,
    published_at,
    published_by,
    favorite,
    online,
    moderated,
    moderated_partial,
    moderated_at,
    created_at,
    updated_at,
    slug
FROM (
( SELECT DISTINCT p_d_debate.*, 0 as nb_users, 1 as unionsorting
FROM p_d_debate
    LEFT JOIN p_d_d_tagged_t
        ON p_d_debate.id = p_d_d_tagged_t.p_d_debate_id
WHERE
    p_d_d_tagged_t.p_tag_id IN (
                SELECT p_tag.id
                FROM p_tag
                    LEFT JOIN p_u_tagged_t
                        ON p_tag.id = p_u_tagged_t.p_tag_id
                WHERE
                    p_tag.online = true
                    AND p_u_tagged_t.p_user_id = :p_user_id
    )
    AND p_d_debate.online = 1
    AND p_d_debate.published = 1
    AND p_d_debate.id NOT IN (SELECT p_d_debate_id FROM p_u_follow_d_d WHERE p_user_id = :p_user_id2)
    AND p_d_debate.p_user_id <> :p_user_id3
)

UNION DISTINCT

( SELECT DISTINCT p_d_debate.*, COUNT(p_u_follow_d_d.p_d_debate_id) as nb_users, 2 as unionsorting
FROM p_d_debate
    LEFT JOIN p_u_follow_d_d
        ON p_d_debate.id = p_u_follow_d_d.p_d_debate_id
WHERE
    p_d_debate.online = 1
    AND p_d_debate.published = 1
    AND p_d_debate.id NOT IN (SELECT p_d_debate_id FROM p_u_follow_d_d WHERE p_user_id = :p_user_id4)
    AND p_d_debate.p_user_id <> :p_user_id5
GROUP BY p_d_debate.id
ORDER BY nb_users DESC
)

ORDER BY unionsorting ASC
) unionsorting

LIMIT :offset, :limit
";

        return $sql;
    }

    /**
     * Reactions' suggestion for user.
     *
     * @see app/sql/suggestions.sql
     *
     * @return string
     */
    private function createUserSuggestedReactionsRawSql()
    {
        // Requête SQL
        $sql = "
SELECT DISTINCT
    id,
    uuid,
    p_user_id,
    p_d_debate_id,
    parent_reaction_id,
    title,
    file_name,
    copyright,
    description,
    note_pos,
    note_neg,
    nb_views,
    published,
    published_at,
    published_by,
    favorite,
    online,
    moderated,
    moderated_partial,
    moderated_at,
    created_at,
    updated_at,
    slug,
    tree_left,
    tree_right,
    tree_level
FROM (
    ( SELECT DISTINCT p_d_reaction.*, 0 as nb_users, 1 as unionsorting
        FROM p_d_reaction
            LEFT JOIN p_d_r_tagged_t
                ON p_d_reaction.id = p_d_r_tagged_t.p_d_reaction_id
        WHERE
            p_d_r_tagged_t.p_tag_id IN (
                SELECT p_tag.id
                FROM p_tag
                    LEFT JOIN p_u_tagged_t
                        ON p_tag.id = p_u_tagged_t.p_tag_id
                WHERE
                    p_tag.online = true
                    AND p_u_tagged_t.p_user_id = :p_user_id
            )
        AND p_d_reaction.online = 1
        AND p_d_reaction.published = 1
        AND p_d_reaction.id NOT IN (SELECT p_d_reaction_id FROM p_u_follow_d_d WHERE p_user_id = :p_user_id2)
        AND p_d_reaction.p_user_id <> :p_user_id3
    )
) unionsorting

LIMIT :offset, :limit
";

        return $sql;
    }

   /**
     * Debate feed timeline
     *
     * @see app/sql/debateFeed.sql
     * @return string
     */
    public function createDebateFeedRawSql()
    {
        // Préparation requête SQL
        $sql = "
( SELECT p_d_reaction.id as id, p_d_reaction.title as title, p_d_reaction.description as summary, p_d_reaction.published_at as published_at, 'Politizr\\\Model\\\PDReaction' as type
FROM p_d_reaction
WHERE
    p_d_reaction.published = 1
    AND p_d_reaction.online = 1
    AND p_d_reaction.p_d_debate_id = :p_d_debate_id
    AND p_d_reaction.tree_level > 0
)

UNION DISTINCT

( SELECT p_d_d_comment.id as id, \"commentaire\" as title, p_d_d_comment.description as summary, p_d_d_comment.published_at as published_at, 'Politizr\\\Model\\\PDDComment' as type
FROM p_d_d_comment
WHERE
    p_d_d_comment.online = 1
    AND p_d_d_comment.p_d_debate_id = :p_d_debate_id2
    AND p_d_d_comment.p_user_id IN (:inQueryUserIds)
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
            AND p_d_reaction.p_d_debate_id = :p_d_debate_id3
            AND p_d_reaction.tree_level > 0
            )
            AND p_d_r_comment.p_user_id IN (:inQueryUserIds2)
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
     * @param string $orderBy
     * @return string
     */
    private function createMyDocumentsRawSql($orderBy = 'published_at')
    {
        $sql = "
( SELECT p_d_reaction.id as id, p_d_reaction.title as title, p_d_reaction.published_at as published_at, p_d_reaction.updated_at as updated_at, 'Politizr\\\Model\\\PDReaction' as type
FROM p_d_reaction
WHERE
    p_user_id = :p_user_id
    AND p_d_reaction.published = :published
    AND p_d_reaction.online = 1
)

UNION DISTINCT

( SELECT p_d_debate.id as id, p_d_debate.title as title, p_d_debate.published_at as published_at, p_d_debate.updated_at as updated_at, 'Politizr\\\Model\\\PDDebate' as type
FROM p_d_debate
WHERE
    p_user_id = :p_user_id2
    AND p_d_debate.published = :published2
    AND p_d_debate.online = 1
)

ORDER BY $orderBy DESC
LIMIT :offset, :limit
    ";

        return $sql;
    }

    /**
     * Return number of 1st level reactions for user publications
     *
     * cf sql/badges.sql
     *
     * @return string
     */
    private function createNbUserDocumentReactionsLevel1RawSql()
    {
        $sql = "
SELECT COUNT(*) as nb
FROM
(
# Liste des réactions filles de 1er niveau pour les réactions d un user
SELECT child.id
FROM p_d_reaction parent, p_d_reaction child
WHERE
    parent.p_user_id = :p_user_id
    AND child.p_user_id <> :p_user_id2
    AND child.p_d_debate_id = parent.p_d_debate_id
    AND child.tree_level = parent.tree_level + 1
    AND child.tree_left > parent.tree_left
    AND child.tree_right < parent.tree_right
GROUP BY child.p_d_debate_id

UNION

# Liste des réactions filles de 1er niveau pour les débats d un user
SELECT child.id
FROM p_d_debate parent, p_d_reaction child
WHERE
    parent.p_user_id = :p_user_id3
    AND child.p_user_id <> :p_user_id4
    AND child.p_d_debate_id = parent.id
    AND child.tree_level = 1
GROUP BY child.p_d_debate_id
) x
";

        return $sql;
    }

    /**
     * Return number of 1st level reactions for user publications
     *
     * @return string
     */
    public function createNbUserDebateFirstReaction()
    {
        $sql = "
SELECT id
FROM p_d_reaction 
WHERE 
    p_user_id = :p_user_id
    AND tree_level = 1
    AND tree_left = 2
GROUP BY p_d_debate_id
";

        return $sql;
    }

    /* ######################################################################################################## */
    /*                                            RAW SQL OPERATIONS                                            */
    /* ######################################################################################################## */

    /**
     * User's debates' suggestions paginated listing
     *
     * @param integer $userId
     * @param integer $offset
     * @param integer $limit
     * @return PropelCollection[PDDebate]
     */
    public function generateUserSuggestedDebatesPaginatedListing($userId, $offset, $limit)
    {
        $this->logger->info('*** generateUserSuggestedDebatesPaginatedListing');
        $this->logger->info('$userId = ' . print_r($userId, true));
        $this->logger->info('$offset = ' . print_r($offset, true));
        $this->logger->info('$limit = ' . print_r($limit, true));

        $con = \Propel::getConnection('default', \Propel::CONNECTION_READ);
        $stmt = $con->prepare($this->createUserSuggestedDebatesRawSql());

        $stmt->bindValue(':p_user_id', $userId, \PDO::PARAM_INT);
        $stmt->bindValue(':p_user_id2', $userId, \PDO::PARAM_INT);
        $stmt->bindValue(':p_user_id3', $userId, \PDO::PARAM_INT);
        $stmt->bindValue(':p_user_id4', $userId, \PDO::PARAM_INT);
        $stmt->bindValue(':p_user_id5', $userId, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);

        $stmt->execute();

        $result = $stmt->fetchAll();

        $documents = new \PropelCollection();
        foreach ($result as $row) {
            $debate = new PDDebate();
            $debate->hydrate($row);

            $documents->append($debate);
        }

        return $documents;
    }
    
    /**
     * User's reactions' suggestions paginated listing
     *
     * @param integer $userId
     * @param integer $offset
     * @param integer $limit
     * @return PropelCollection[PDReaction]
     */
    public function generateUserSuggestedReactionsPaginatedListing($userId, $offset, $limit)
    {
        $this->logger->info('*** generateUserSuggestedReactionsPaginatedListing');
        $this->logger->info('$userId = ' . print_r($userId, true));
        $this->logger->info('$offset = ' . print_r($offset, true));
        $this->logger->info('$limit = ' . print_r($limit, true));

        $con = \Propel::getConnection('default', \Propel::CONNECTION_READ);
        $stmt = $con->prepare($this->createUserSuggestedReactionsRawSql());

        $stmt->bindValue(':p_user_id', $userId, \PDO::PARAM_INT);
        $stmt->bindValue(':p_user_id2', $userId, \PDO::PARAM_INT);
        $stmt->bindValue(':p_user_id3', $userId, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);

        $stmt->execute();

        $result = $stmt->fetchAll();

        $documents = new \PropelCollection();
        foreach ($result as $row) {
            $reaction = new PDReaction();
            $reaction->hydrate($row);

            $documents->append($reaction);
        }

        return $documents;
    }
    
    /**
     * My documents paginated listing
     *
     * @param integer $userId
     * @param integer $published
     * @param string $orderBy
     * @param integer $offset
     * @param integer $limit
     * @return PropelCollection
     */
    public function generateMyDocumentsPaginatedListing($userId, $published, $orderBy, $offset, $limit)
    {
        $this->logger->info('*** generateMyDocumentsPaginatedListing');
        $this->logger->info('$userId = ' . print_r($userId, true));
        $this->logger->info('$published = ' . print_r($published, true));
        $this->logger->info('$orderBy = ' . print_r($orderBy, true));
        $this->logger->info('$offset = ' . print_r($offset, true));
        $this->logger->info('$limit = ' . print_r($limit, true));

        $con = \Propel::getConnection('default', \Propel::CONNECTION_READ);
        $stmt = $con->prepare($this->createMyDocumentsRawSql($orderBy));

        $stmt->bindValue(':p_user_id', $userId, \PDO::PARAM_INT);
        $stmt->bindValue(':p_user_id2', $userId, \PDO::PARAM_INT);
        $stmt->bindValue(':published', $published, \PDO::PARAM_INT);
        $stmt->bindValue(':published2', $published, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);

        $stmt->execute();

        $result = $stmt->fetchAll();

        $documents = new \PropelCollection();
        $i = 0;
        foreach ($result as $row) {
            $type = $row['type'];

            if ($type == ObjectTypeConstants::TYPE_DEBATE) {
                $document = PDDebateQuery::create()->findPk($row['id']);
            } elseif ($type == ObjectTypeConstants::TYPE_REACTION) {
                $document = PDReactionQuery::create()->findPk($row['id']);
            } else {
                throw new InconsistentDataException(sprintf('Object type %s unknown.', $type));
            }
            
            $documents->set($i, $document);
            $i++;
        }

        return $documents;
    }
    
    /**
     * My documents listing
     *
     * @param integer $debateId
     * @param array $inQueryUserIds
     * @return array
     */
    public function generateDebateFeedTimeline($debateId, $inQueryUserIds)
    {
        $this->logger->info('*** generateDebateFeedTimeline');
        $this->logger->info('$debateId = ' . print_r($debateId, true));
        $this->logger->info('$inQueryUserIds = ' . print_r($inQueryUserIds, true));

        $con = \Propel::getConnection('default', \Propel::CONNECTION_READ);
        $stmt = $con->prepare($this->createDebateFeedRawSql());

        $stmt->bindValue(':p_d_debate_id', $debateId, \PDO::PARAM_INT);
        $stmt->bindValue(':p_d_debate_id2', $debateId, \PDO::PARAM_INT);
        $stmt->bindValue(':p_d_debate_id3', $debateId, \PDO::PARAM_INT);
        $stmt->bindValue(':inQueryUserIds', $inQueryUserIds, \PDO::PARAM_STR);
        $stmt->bindValue(':inQueryUserIds2', $inQueryUserIds, \PDO::PARAM_STR);

        $stmt->execute();
        $result = $stmt->fetchAll();

        $timeline = $this->globalTools->hydrateTimelineRows($result);

        return $timeline;
    }
    
    /**
     * Return number of 1st level reactions for user publications
     *
     * @param int $userId
     * @return int
     */
    public function generateNbUserDocumentReactionsLevel1($userId)
    {
        $this->logger->info('*** generateNbUserDocumentReactionsLevel1');
        $this->logger->info('$userId = ' . print_r($userId, true));

        $con = \Propel::getConnection('default', \Propel::CONNECTION_READ);
        $stmt = $con->prepare($this->createNbUserDocumentReactionsLevel1RawSql());

        $stmt->bindValue(':p_user_id', $userId, \PDO::PARAM_INT);
        $stmt->bindValue(':p_user_id2', $userId, \PDO::PARAM_INT);
        $stmt->bindValue(':p_user_id3', $userId, \PDO::PARAM_INT);
        $stmt->bindValue(':p_user_id4', $userId, \PDO::PARAM_INT);

        $stmt->execute();
        $result = $stmt->fetchAll();

        $count = 0;
        if (isset($result[0]['nb'])) {
            $count = $result[0]['nb'];
        }

        return $count;
    }

    /**
     * Return number of debates' reactions written first by userId
     *
     * @param int $userId
     * @return int
     */
    public function generateNbUserDebateFirstReaction($userId)
    {
        $this->logger->info('*** generateNbUserDebateFirstReaction');
        $this->logger->info('$userId = ' . print_r($userId, true));

        $con = \Propel::getConnection('default', \Propel::CONNECTION_READ);
        $stmt = $con->prepare($this->createNbUserDebateFirstReaction());

        $stmt->bindValue(':p_user_id', $userId, \PDO::PARAM_INT);

        $stmt->execute();
        $result = $stmt->fetchAll();

        $count = count($result);

        return $count;
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
    public function initReactionTaggedTags(PDReaction $reaction)
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
