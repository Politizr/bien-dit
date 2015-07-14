<?php
namespace Politizr\FrontBundle\Lib\Xhr;

use Symfony\Component\EventDispatcher\GenericEvent;

use StudioEcho\Lib\StudioEchoUtils;

use Politizr\Exception\InconsistentDataException;
use Politizr\Exception\FormValidationException;

use Politizr\Model\PDReactionQuery;
use Politizr\Model\PUFollowDDQuery;
use Politizr\Model\PUFollowUQuery;

use Politizr\FrontBundle\Lib\TimelineRow;

/**
 * Services métiers associés à la gestion de la timeline
 *
 * @author Lionel Bouzonville
 */
class XhrTimeline
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
        // @todo refactoring
        // $results = Query->select('Id')->find()
        // $results->getData()
        // => tableau d'Ids
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


    /* ######################################################################################################## */
    /*                                           REQUÊTES SQL TIMELINE                                          */
    /* ######################################################################################################## */


    /**
     * User "my politizr" timeline
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
( SELECT p_d_reaction.id as id, p_d_reaction.title as title, p_d_reaction.published_at as published_at, 'Politizr\\\Model\\\PDReaction' as type
FROM p_d_reaction
WHERE
    p_d_reaction.published = 1
    AND p_d_reaction.online = 1
    AND p_d_reaction.p_d_debate_id IN (".$inQueryDebateIds.")
    AND p_d_reaction.tree_level > 0 )

UNION DISTINCT

( SELECT p_d_debate.id as id, p_d_debate.title as title, p_d_debate.published_at as published_at, 'Politizr\\\Model\\\PDDebate' as type
FROM p_d_debate
WHERE
    p_d_debate.published = 1
    AND p_d_debate.online = 1
    AND p_d_debate.p_user_id IN (".$inQueryUserIds.") )

UNION DISTINCT

( SELECT p_d_reaction.id as id, p_d_reaction.title as title, p_d_reaction.published_at as published_at, 'Politizr\\\Model\\\PDReaction' as type
FROM p_d_reaction
WHERE
    p_d_reaction.published = 1
    AND p_d_reaction.online = 1
    AND p_d_reaction.p_user_id IN (".$inQueryUserIds.") )

UNION DISTINCT

( SELECT p_d_reaction.id as id, p_d_reaction.title as title, p_d_reaction.published_at as published_at, 'Politizr\\\Model\\\PDReaction' as type
FROM p_d_reaction
    LEFT JOIN p_d_debate
        ON p_d_reaction.p_d_debate_id = p_d_debate.id
WHERE
    p_d_reaction.published = 1
    AND p_d_reaction.online = 1
    AND p_d_debate.p_user_id = ".$userId."
    AND p_d_reaction.tree_level > 0 )

UNION DISTINCT

( SELECT p_d_reaction.id as id, p_d_reaction.title as title, p_d_reaction.published_at as published_at, 'Politizr\\\Model\\\PDReaction' as type
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

( SELECT p_d_d_comment.id as id, \"commentaire\" as title, p_d_d_comment.published_at as published_at, 'Politizr\\\Model\\\PDDComment' as type
FROM p_d_d_comment
WHERE
    p_d_d_comment.online = 1
    AND p_d_d_comment.p_user_id IN (".$inQueryUserIds.") )

UNION DISTINCT

( SELECT p_d_r_comment.id as id, \"commentaire\" as title, p_d_r_comment.published_at as published_at, 'Politizr\\\Model\\\PDRComment' as type
FROM p_d_r_comment
WHERE
    p_d_r_comment.online = 1
    AND p_d_r_comment.p_user_id IN (".$inQueryUserIds.") )

UNION DISTINCT

( SELECT p_d_d_comment.id as id, \"commentaire\" as title, p_d_d_comment.published_at as published_at, 'Politizr\\\Model\\\PDDComment' as type
FROM p_d_d_comment
WHERE
    p_d_d_comment.online = 1
    AND p_d_d_comment.p_d_debate_id IN (".$inQueryMyDocumentIds.") )

UNION DISTINCT

( SELECT p_d_r_comment.id as id, \"commentaire\" as title, p_d_r_comment.published_at as published_at, 'Politizr\\\Model\\\PDRComment' as type
FROM p_d_r_comment
WHERE
    p_d_r_comment.online = 1
    AND p_d_r_comment.p_d_reaction_id IN (".$inQueryMyDocumentIds.") )

ORDER BY published_at DESC
LIMIT ".$offset.", ".$count."
        ";

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
    public function getDebateFeedSql($debateId)
    {
        $logger = $this->sc->get('logger');
        $logger->info('*** getSql');
        
        $user = null;
        $securityContext = $this->sc->get('security.context');
        if ($securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            // Récupération user
            $user = $this->sc->get('security.context')->getToken()->getUser();

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
        $logger->info('*** hydrateTimelineRows');

        $timeline = array();

        if ($sql) {
            // Exécution de la requête brute
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

        // @todo gérer les "limit" dans une variable
        $moreResults = false;
        if (sizeof($timeline) == 10) {
            $moreResults = true;
        }

        // Ajout d'une clef date
        $timelineDateKey = $this->addDateKey($timeline);

        // Construction rendu
        $templating = $this->sc->get('templating');
        $html = $templating->render(
            'PolitizrFrontBundle:Timeline:_paginatedTimeline.html.twig',
            array(
                'timelineDateKey' => $timelineDateKey,
                'offset' => intval($offset) + 10,
                'moreResults' => $moreResults,
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
