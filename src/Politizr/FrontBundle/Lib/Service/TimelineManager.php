<?php
namespace Politizr\FrontBundle\Lib\Service;

use Symfony\Component\EventDispatcher\GenericEvent;

use StudioEcho\Lib\StudioEchoUtils;

use Politizr\Exception\InconsistentDataException;
use Politizr\Exception\FormValidationException;

use Politizr\Model\PDocumentQuery;
use Politizr\Model\PDReactionQuery;
use Politizr\Model\PUFollowDDQuery;
use Politizr\Model\PUFollowUQuery;

use Politizr\FrontBundle\Lib\TimelineRow;

/**
 * Services métiers associés à la gestion de la timeline
 *
 * @author Lionel Bouzonville
 */
class TimelineManager
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
    /*                                               FONCTIONS PRIVÉES                                          */
    /* ######################################################################################################## */

    /**
     * Renvoit un tableau des ids des débats suivis
     *
     * @param  integer     $userId
     *
     * @return array
     */
    private function getFollowedDebatesIdsArray($userId)
    {
        $debateIds = PUFollowDDQuery::create()
                        ->filterByPUserId($userId)
                        ->find()
                        ->toKeyValue('PDDebateId', 'PDDebateId')
                        // ->getPrimaryKeys()
                        ;
        $debateIds = array_keys($debateIds);

        return $debateIds;
    }

    /**
     * Renvoit un tableau des ids des users suivis
     *
     * @param  integer     $userId
     *
     * @return array
     */
    private function getFollowedUsersIdsArray($userId)
    {
        $userIds = PUFollowUQuery::create()
                        ->filterByPUserFollowerId($userId)
                        ->find()
                        ->toKeyValue('PUserId', 'PUserId')
                        // ->getPrimaryKeys()
                        ;
        $userIds = array_keys($userIds);

        return $userIds;
    }


    /**
     * Renvoit un tableau des ids des réactions du userId
     *
     * @param  integer     $userId
     *
     * @return array
     */
    private function getMyReactionIdsArray($userId)
    {
        $myReactionIds = PDReactionQuery::create()
                        ->filterByPUserId($userId)
                        ->find()
                        ->toKeyValue('Id', 'Id')
                        // ->getPrimaryKeys()
                        ;
        $myReactionIds = array_keys($myReactionIds);

        return $myReactionIds;
    }


    /**
     * Renvoit un tableau des ids des documents du userId
     *
     * @param  integer     $userId
     *
     * @return array
     */
    private function getMyDocumentIdsArray($userId)
    {
        $myDocumentIds = PDocumentQuery::create()
                        ->filterByPUserId($userId)
                        ->find()
                        ->toKeyValue('Id', 'Id')
                        // ->getPrimaryKeys()
                        ;
        $myDocumentIds = array_keys($myDocumentIds);

        return $myDocumentIds;
    }

    /* ######################################################################################################## */
    /*                                           REQUÊTES SQL TIMELINE                                          */
    /* ######################################################################################################## */


    /**
     *  Construction de la requête SQL renvoyant la timeline d'un user.
     *
     *
     *  TODO:
     *   > + réactions sur les débats / réactions rédigés par le user courant
     *   > + commentaires sur les débats / réactions rédigés par le user courant
     *
     *
     *
     *           #  Réactions aux débats suivis
     *           ( SELECT p_document.id as id, p_document.title as title, p_document.summary as summary, p_document.published_at as published_at, descendant_class as type
     *           FROM p_document
     *               LEFT JOIN p_d_reaction
     *                   ON p_document.id = p_d_reaction.id
     *           WHERE
     *               p_d_reaction.published = 1
     *               AND p_d_reaction.online = 1
     *               AND p_d_reaction.p_d_debate_id IN (5,1)
     *               AND p_d_reaction.tree_level > 0 )
     *
     *           UNION DISTINCT
     *
     *           # Débats & réactions des users suivis
     *           ( SELECT p_document.id as id, p_document.title as title, p_document.summary as summary, p_document.published_at as published_at, descendant_class as type
     *           FROM p_document
     *           WHERE
     *               p_document.published = 1
     *               AND p_document.online = 1
     *               AND p_document.p_user_id IN (6,9,60) )
     *
     *           UNION DISTINCT
     *
     *           # Commentaires des users suivis
     *           ( SELECT p_d_comment.id as id, "commentaire" as title, p_d_comment.description as summary, p_d_comment.published_at as published_at, 'Politizr\\Model\\PDComment' as type
     *           FROM p_d_comment
     *           WHERE
     *               p_d_comment.online = 1
     *               AND p_d_comment.p_user_id IN (6,9,60) )
     *
     *           UNION DISTINCT
     *
     *           # Réactions sur mes débats
     *           ( SELECT p_d_reaction.id as id, p_d_reaction.title as title, p_d_reaction.summary as summary, p_d_reaction.published_at as published_at, 'Politizr\\Model\\PDReaction' as type
     *           FROM p_d_reaction
     *               LEFT JOIN p_d_debate
     *                   ON p_d_reaction.p_d_debate_id = p_d_debate.id
     *           WHERE
     *               p_d_reaction.published = 1
     *               AND p_d_reaction.online = 1
     *               AND p_d_debate.p_user_id = 72
     *               AND p_d_reaction.tree_level > 0 )
     *
     *           UNION DISTINCT
     *
     *           # Réactions sur mes réactions
     *           ( SELECT p_d_reaction.id as id, p_d_reaction.title as title, p_d_reaction.summary as summary, p_d_reaction.published_at as published_at, 'Politizr\\Model\\PDReaction' as type
     *           FROM p_d_reaction as p_d_reaction
     *               LEFT JOIN p_d_reaction as my_reaction
     *                   ON p_d_reaction.p_d_debate_id = my_reaction.p_d_debate_id
     *           WHERE
     *               p_d_reaction.published = 1
     *               AND p_d_reaction.online = 1
     *               AND my_reaction.id IN (12, 16)
     *               AND p_d_reaction.tree_left > my_reaction.tree_left
     *               AND p_d_reaction.tree_left < my_reaction.tree_right
     *               AND p_d_reaction.tree_level > my_reaction.tree_level
     *               AND p_d_reaction.tree_level > 1 )
     *
     *           UNION DISTINCT
     *
     *           # Commentaires sur mes débats & réactions
     *           ( SELECT p_d_comment.id as id, "commentaire" as title, p_d_comment.description as summary, p_d_comment.published_at as published_at, 'Politizr\\Model\\PDComment' as type
     *           FROM p_d_comment
     *           WHERE
     *               p_d_comment.online = 1
     *               AND p_d_comment.p_document_id IN (1, 12, 16) )
     *
     *
     *           ORDER BY published_at DESC
     *
     *
     *
     *  @param  integer     $offset
     *  @param  integer     $count
     *
     *  @return string
     */
    public function getTimelineSql($offset, $count = 10)
    {
        $logger = $this->sc->get('logger');
        $logger->info('*** getSql');
        
        // Récupération user
        $user = $this->sc->get('security.context')->getToken()->getUser();
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
        

        // Préparation requête SQL
        $sql = "
#  Réactions aux débats suivis
( SELECT p_document.id as id, p_document.title as title, p_document.summary as summary, p_document.published_at as published_at, descendant_class as type
FROM p_document
    LEFT JOIN p_d_reaction 
        ON p_document.id = p_d_reaction.id
WHERE 
    p_d_reaction.published = 1  
    AND p_d_reaction.online = 1 
    AND p_d_reaction.p_d_debate_id IN (".$inQueryDebateIds.")
    AND p_d_reaction.tree_level > 0 )

UNION DISTINCT

# Débats & réactions des users suivis
( SELECT p_document.id as id, p_document.title as title, p_document.summary as summary, p_document.published_at as published_at, descendant_class as type
FROM p_document
WHERE 
    p_document.published = 1    
    AND p_document.online = 1   
    AND p_document.p_user_id IN (".$inQueryUserIds.") )

UNION DISTINCT

# Commentaires des users suivis
( SELECT p_d_comment.id as id, 'commentaire' as title, p_d_comment.description as summary, p_d_comment.published_at as published_at, 'Politizr\\\Model\\\PDComment' as type
FROM p_d_comment
WHERE 
    p_d_comment.online = 1  
    AND p_d_comment.p_user_id IN (".$inQueryUserIds.") )

UNION DISTINCT

# Réactions sur mes débats
( SELECT p_d_reaction.id as id, p_d_reaction.title as title, p_d_reaction.summary as summary, p_d_reaction.published_at as published_at, 'Politizr\\\Model\\\PDReaction' as type
FROM p_d_reaction
    LEFT JOIN p_d_debate 
        ON p_d_reaction.p_d_debate_id = p_d_debate.id
WHERE 
    p_d_reaction.published = 1  
    AND p_d_reaction.online = 1 
    AND p_d_debate.p_user_id = ".$userId."
    AND p_d_reaction.tree_level > 0 )

UNION DISTINCT

# Réactions sur mes réactions
( SELECT p_d_reaction.id as id, p_d_reaction.title as title, p_d_reaction.summary as summary, p_d_reaction.published_at as published_at, 'Politizr\\\Model\\\PDReaction' as type
FROM p_d_reaction as p_d_reaction
    LEFT JOIN p_d_reaction as my_reaction
        ON p_d_reaction.p_d_debate_id = my_reaction.p_d_debate_id
WHERE 
    p_d_reaction.published = 1  
    AND p_d_reaction.online = 1 
    AND my_reaction.id IN (".$inQueryMyReactionIds.") 
    AND p_d_reaction.tree_left > my_reaction.tree_left
    AND p_d_reaction.tree_left < my_reaction.tree_right
    AND p_d_reaction.tree_level > my_reaction.tree_level
    AND p_d_reaction.tree_level > 1 )

UNION DISTINCT

# Commentaires sur mes débats & réactions
( SELECT p_d_comment.id as id, 'commentaire' as title, p_d_comment.description as summary, p_d_comment.published_at as published_at, 'Politizr\\\Model\\\PDComment' as type
FROM p_d_comment
WHERE
    p_d_comment.online = 1  
    AND p_d_comment.p_document_id IN (".$inQueryMyDocumentIds.") )

ORDER BY published_at DESC
LIMIT ".$offset.", ".$count."
        ";

        return $sql;
    }

    /**
     *  Construction de la requête SQL renvoyant la timeline d'un user.
     *
     *
     *  TODO:
     *   > + réactions sur les débats / réactions rédigés par le user courant
     *   > + commentaires sur les débats / réactions rédigés par le user courant
     *
     *
     *
     *
     *      #  Réactions descendantes au débat courant
     *      ( SELECT p_document.id as id, p_document.title as title, p_document.summary as summary, p_document.published_at as published_at, descendant_class as type
     *      FROM p_document
     *          LEFT JOIN p_d_reaction
     *              ON p_document.id = p_d_reaction.id
     *      WHERE
     *          p_d_reaction.published = 1
     *          AND p_d_reaction.online = 1
     *          AND p_d_reaction.p_d_debate_id = 3
     *          AND p_d_reaction.tree_level > 0
     *      )
     *
     *      UNION DISTINCT
     *
     *      # Commentaires du débat courant des users suivis + ses propres commentaires
     *      ( SELECT p_d_comment.id as id, "commentaire" as title, p_d_comment.description as summary, p_d_comment.published_at as published_at, 'Politizr\\Model\\PDComment' as type
     *      FROM p_d_comment
     *      WHERE
     *          p_d_comment.online = 1
     *          AND p_d_comment.p_document_id = 3
     *          AND p_d_comment.p_user_id IN (73, 36, 42)
     *      )
     *
     *      UNION DISTINCT
     *
     *      # Commentaires sur une des réactions descendantes du débat courant des users suivis + ses propres commentaires
     *      ( SELECT p_d_comment.id as id, "commentaire" as title, p_d_comment.description as summary, p_d_comment.published_at as published_at, 'Politizr\\Model\\PDComment' as type
     *      FROM p_d_comment
     *      WHERE
     *          p_d_comment.online = 1
     *          AND p_d_comment.p_document_id IN (
     *              # Requête "Réactions descendantes au débat courant"
     *              SELECT p_document.id as id
     *              FROM p_document
     *                  LEFT JOIN p_d_reaction
     *                  ON p_document.id = p_d_reaction.id
     *              WHERE
     *                  p_d_reaction.published = 1
     *                  AND p_d_reaction.online = 1
     *                  AND p_d_reaction.p_d_debate_id = 3
     *                  AND p_d_reaction.tree_level > 0
     *                  )
     *                  AND p_d_comment.p_user_id IN (73, 36, 42)
     *          )
     *
     *      ORDER BY published_at ASC
     *
     *
     *
     *  @param  integer     $debateId
     *
     *  @return string
     */
    public function getDebateFeedSql($debateId)
    {
        $logger = $this->sc->get('logger');
        $logger->info('*** getSql');
        
        // Récupération user
        $user = $this->sc->get('security.context')->getToken()->getUser();
        $userId = $user->getId();
        $logger->info('userId = '.print_r($userId, true));

        // Récupération d'un tableau des ids des users suivis
        $userIds = $this->getFollowedUsersIdsArray($user->getId());
        $inQueryUserIds = implode(',', $userIds);

        // Ajout du user courant
        if (empty($inQueryUserIds)) {
            $inQueryUserIds = $userId;
        } else {
            $inQueryUserIds .= ',' . $userId;
        }
        $logger->info('inQueryUserIds = '.print_r($inQueryUserIds, true));

        // Préparation requête SQL
        $sql = "
#  Réactions descendantes au débat courant
( SELECT p_document.id as id, p_document.title as title, p_document.summary as summary, p_document.published_at as published_at, descendant_class as type
FROM p_document
    LEFT JOIN p_d_reaction
        ON p_document.id = p_d_reaction.id
WHERE
    p_d_reaction.published = 1
    AND p_d_reaction.online = 1
    AND p_d_reaction.p_d_debate_id = ".$debateId."
    AND p_d_reaction.tree_level > 0
)

UNION DISTINCT

# Commentaires du débat courant des users suivis + ses propres commentaires
( SELECT p_d_comment.id as id, 'commentaire' as title, p_d_comment.description as summary, p_d_comment.published_at as published_at, 'Politizr\\\Model\\\PDComment' as type
FROM p_d_comment
WHERE
    p_d_comment.online = 1
    AND p_d_comment.p_document_id = ".$debateId."
    AND p_d_comment.p_user_id IN (".$inQueryUserIds.")
)

UNION DISTINCT

# Commentaires sur une des réactions descendantes du débat courant des users suivis + ses propres commentaires
( SELECT p_d_comment.id as id, 'commentaire' as title, p_d_comment.description as summary, p_d_comment.published_at as published_at, 'Politizr\\\Model\\\PDComment' as type
FROM p_d_comment
WHERE
    p_d_comment.online = 1
    AND p_d_comment.p_document_id IN (
        # Requête 'Réactions descendantes au débat courant'
        SELECT p_document.id as id
        FROM p_document
            LEFT JOIN p_d_reaction
            ON p_document.id = p_d_reaction.id
        WHERE
            p_d_reaction.published = 1
            AND p_d_reaction.online = 1
            AND p_d_reaction.p_d_debate_id = ".$debateId."
            AND p_d_reaction.tree_level > 0
            )
            AND p_d_comment.p_user_id IN (".$inQueryUserIds.")
    )

ORDER BY published_at ASC
    ";

        return $sql;
    }


    /* ######################################################################################################## */
    /*                                  HYDRATATION DU MODELE TIMELINE                                          */
    /* ######################################################################################################## */


    /*
     * Exécution de la requête et construction du modèle objet TimelineRow
     *
     * @param  string $sql
     * @return array TimelineRow
     */
    public function hydrateTimelineRows($sql)
    {
        $logger = $this->sc->get('logger');
        $logger->info('*** getTimeline');

        $timeline = array();

        if ($sql) {
            // Exécution de la requête timeline brute
            $con = \Propel::getConnection('default', \Propel::CONNECTION_READ);
            $stmt = $con->prepare($sql);
            $stmt->execute();

            $result = $stmt->fetchAll();

            // var_dump($result);
            // die();

            foreach ($result as $row) {
                $timelineRow = new TimelineRow();

                $timelineRow->setId($row['id']);
                $timelineRow->setTitle($row['title']);
                $timelineRow->setSummary($row['summary']);
                $timelineRow->setPublishedAt($row['published_at']);
                $timelineRow->setType($row['type']);

                $timeline[] = $timelineRow;
            }
        }

        return $timeline;
    }

    /**
     * Reconstruit un tableau d'objet TimelineRow en ajoutant une clef "date" contenant
     * les éléments publiés à cette date.
     *
     * @param array TimelineRow
     * @return array TimelineRow
     */
    public function addDateKey($timeline)
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
    /*                            METHODES FONCTIONNELLES DEPUIS CONTROLLER                                     */
    /* ######################################################################################################## */

    /**
     *  Liste paginée du la timeline "mon politizr"
     *
     */
    public function timelinePaginated()
    {
        $logger = $this->sc->get('logger');
        $logger->info('*** timelinePaginated');
        
        // Récupération user
        $user = $this->sc->get('security.context')->getToken()->getUser();

        // Récupération args
        $request = $this->sc->get('request');

        $offset = $request->get('offset');
        $logger->info('$offset = ' . print_r($offset, true));

        // Récupération de la requête SQL
        $sql = $this->getTimelineSql($offset);

        // Exécution de la requête SQL & préparation du modèle
        $timeline = $this->hydrateTimelineRows($sql);

        // Construction rendu
        $templating = $this->sc->get('templating');
        $html = $templating->render(
            'PolitizrFrontBundle:Fragment\\Global:Timeline.html.twig',
            array(
                'timeline' => $timeline,
                'offset' => intval($offset) + 10,
            )
        );

        // Renvoi de l'ensemble des blocs HTML maj
        return array(
            'html' => $html,
            );
    }

    /**
     * Fil du débat
     *
     * @param integer $debateId
     * @return array TimelineRow
     */
    public function debateFeed($debateId)
    {
        $logger = $this->sc->get('logger');
        $logger->info('*** debateFeed');
        
        // Récupération de la requête SQL
        $sql = $this->getDebateFeedSql($debateId);

        // Exécution de la requête SQL & préparation du modèle
        $timeline = $this->hydrateTimelineRows($sql);

        // Ajout d'une clef date
        $timelineDateKey = $this->addDateKey($timeline);

        return $timelineDateKey;
    }
}
