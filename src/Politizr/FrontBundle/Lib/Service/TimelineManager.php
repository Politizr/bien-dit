<?php
namespace Politizr\FrontBundle\Lib\Service;

use Symfony\Component\EventDispatcher\GenericEvent;

use StudioEcho\Lib\StudioEchoUtils;

use Politizr\Exception\InconsistentDataException;
use Politizr\Exception\FormValidationException;

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
    public function __construct($serviceContainer) {
        $this->sc = $serviceContainer;
    }

    /* ######################################################################################################## */
    /*                                               FONCTIONS PRIVÉES                                          */
    /* ######################################################################################################## */

    /**
     *  Renvoit un tableau des ids des débats suivis
     *
     *  @param  integer     $userId
     *  
     *  @return array
     */
    private function getFollowedDebatesIdsArray($userId) {
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
     *  Renvoit un tableau des ids des users suivis
     *
     *  @param  integer     $userId
     *  
     *  @return array
     */
    private function getFollowedUsersIdsArray($userId) {
        $userIds = PUFollowUQuery::create()
                        ->filterByPUserFollowerId($userId)
                        ->find()
                        ->toKeyValue('PUserId', 'PUserId')
                        // ->getPrimaryKeys()
                        ;
        $userIds = array_keys($userIds);

        return $userIds;        
    }

    /* ######################################################################################################## */
    /*                           SERVICES METIERS LIES A L'INSCRIPTION                                          */
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
     *  #  Réactions aux débats suivis
     *  ( SELECT p_document.id as id, p_document.title as title, p_document.summary as summary, p_document.published_at as published_at, 'p_d_reaction' as   *  type
     *  FROM p_document
     *      LEFT JOIN p_d_reaction 
     *          ON p_document.id = p_d_reaction.id
     *  WHERE 
     *      p_d_reaction.p_d_debate_id IN (1, 2, 3)
     *      AND p_d_reaction.tree_level > 0 )
     *  
     *  UNION DISTINCT
     *  
     *  # Débats & réactions des users suivis
     *  ( SELECT p_document.id as id, p_document.title as title, p_document.summary as summary, p_document.published_at as published_at, 'p_document' as type
     *  FROM p_document
     *  WHERE p_document.p_user_id IN (1, 72) )
     *  
     *  
     *  UNION DISTINCT
     *  
     *  # Commentaires des users suivis
     *  ( SELECT p_d_comment.id as id, "commentaire" as title, p_d_comment.description as summary, p_d_comment.published_at as published_at, 'p_d_comment' as    *  type
     *  FROM p_d_comment
     *  WHERE p_d_comment.p_user_id IN (1, 72) )
     *  
     *  ORDER BY published_at DESC
     *  
     *
     *  @param  PUser $user
     */
    public function getSql() {
        $logger = $this->sc->get('logger');
        $logger->info('*** getSql');
        
        // Récupération user
        $user = $this->sc->get('security.context')->getToken()->getUser();

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

        // Préparation requête SQL
        if (!empty($debateIds) || !empty($userIds)) {
            $sql = "

#  Réactions aux débats suivis
( SELECT p_document.id as id, p_document.title as title, p_document.summary as summary, p_document.published_at as published_at, descendant_class as type
FROM p_document
    LEFT JOIN p_d_reaction 
        ON p_document.id = p_d_reaction.id
WHERE 
    p_d_reaction.p_d_debate_id IN (".$inQueryDebateIds.")
    AND p_d_reaction.tree_level > 0 )

UNION DISTINCT

# Débats & réactions des users suivis
( SELECT p_document.id as id, p_document.title as title, p_document.summary as summary, p_document.published_at as published_at, descendant_class as type
FROM p_document
WHERE p_document.p_user_id IN (".$inQueryUserIds.") )

UNION DISTINCT

# Commentaires des users suivis
( SELECT p_d_comment.id as id, 'commentaire' as title, p_d_comment.description as summary, p_d_comment.published_at as published_at, 'Politizr\\\Model\\\PDComment' as type
FROM p_d_comment
WHERE p_d_comment.p_user_id IN (".$inQueryUserIds.") )

ORDER BY published_at DESC
        ";
        } else {
            $sql = null;
            $listPKs = array();
        }

        return $sql;
    }

    /*
     *  Exécution de la requête et construction du modèle objet TimelineRow
     *
     *
     *  @param  string $sql
     */
    public function getTimeline($sql) {
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

            foreach($result as $row) {
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

}