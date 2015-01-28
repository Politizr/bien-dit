<?php

namespace Politizr\FrontBundle\Listener;

// use Symfony\Component\HttpKernel\Event\PostResponseEvent;
use Symfony\Component\EventDispatcher\GenericEvent;

use Politizr\Model\PRBadge;
use Politizr\Model\PUReputationRB;

use Politizr\Model\PUReputationRBQuery;
use Politizr\Model\PDocumentQuery;
use Politizr\Model\PDDebateQuery;


/**
 * 	Gestion des badges
 *
 *  @author Lionel Bouzonville
 */
class BadgeListener {

    protected $logger;

    /**
     *
     */
    public function __construct($logger) {
    	$this->logger = $logger;
    }


    /**
     *  Publication d'une réaction
     *
     *  @param  GenericEvent
     */
    public function onBReactionPublish(GenericEvent $event) {
        $this->logger->info('*** onBReactionPublish');

        $subject = $event->getSubject();
        $authorUserId = $event->getArgument('author_user_id');
        $parentUserId = $event->getArgument('parent_user_id');

        // Badges Querellé / Controversé / Polémiqué
        $this->checkQuerelle($parentUserId, PRBadge::ID_QUERELLE, PRBadge::QUERELLE_NB_DOCUMENTS, PRBadge::QUERELLE_NB_REACTIONS);
        $this->checkQuerelle($parentUserId, PRBadge::ID_CONTROVERSE, PRBadge::CONTROVERSE_NB_DOCUMENTS, PRBadge::CONTROVERSE_NB_REACTIONS);
        $this->checkQuerelle($parentUserId, PRBadge::ID_POLEMIQUE, PRBadge::POLEMIQUE_NB_DOCUMENTS, PRBadge::POLEMIQUE_NB_REACTIONS);

        // Badges Eclaireur / Avant Garde / Guide
        $this->checkEclaireur($subject, $authorUserId, PRBadge::ID_ECLAIREUR, PRBadge::ECLAIREUR_NB_DEBATES);
        $this->checkEclaireur($subject, $authorUserId, PRBadge::ID_AVANT_GARDE, PRBadge::AVANT_GARDE_NB_DEBATES);
        $this->checkEclaireur($subject, $authorUserId, PRBadge::ID_GUIDE, PRBadge::GUIDE_NB_DEBATES);

    }

    /**
     * Note positive déclenchée sur un document (débat ou réaction)
     *
     * @param GenericEvent
     */
    public function onBDocumentNotePos(GenericEvent $event) {
        $this->logger->info('*** onBDocumentNotePos');

        $subject = $event->getSubject();
        $authorUserId = $event->getArgument('author_user_id');
        $targetUserId = $event->getArgument('target_user_id');

        // Badges Rédacteur / Auteur / Ecrivain
        $this->checkRedacteur($targetUserId, PRBadge::ID_REDACTEUR, PRBadge::REDACTEUR_NB_DOCUMENTS, PRBadge::REDACTEUR_NB_NOTEPOS);
        $this->checkRedacteur($targetUserId, PRBadge::ID_AUTEUR, PRBadge::AUTEUR_NB_DOCUMENTS, PRBadge::AUTEUR_NB_NOTEPOS);
        $this->checkRedacteur($targetUserId, PRBadge::ID_ECRIVAIN, PRBadge::ECRIVAIN_NB_DOCUMENTS, PRBadge::ECRIVAIN_NB_NOTEPOS);
    }


    // ******************************************************************************************************************** //

    /**
     *  Test si un user possède un badge
     *
     *
     */
    private function hasBadge($userId, $badgeId) {
        $nbBadges = PUReputationRBQuery::create()
                    ->filterByPUserId($userId)
                    ->filterByPRBadgeId($badgeId)
                    ->count();

        if ($nbBadges > 0) { 
            return true;
        }

        return false;
    }


    /**
     *  Gain du badge pour un user
     *
     *
     */
    private function addUserBadge($userId, $badgeId) {
        $userBadge = new PUReputationRB();
        $userBadge->setPUserId($userId);
        $userBadge->setPRBadgeId($badgeId);
        $userBadge->save();
    }


    // ***************************  FONCTIONS LOGIQUES ASSOCIEES A LA CREATION DES BADGES ********************************* //

    /**
     *  Badges Querellé / Controversé / Polémiqué
     *  Être l’auteur de X contenus "débat" ou "réaction" ayant obtenu au moins X réactions
     *
     *  @param  $userId     integer     ID user
     *  @param  $badgeId    integer     ID badge
     *  @param  $nbDebates  integer     Nombre de documents
     *  @param  $nbReactions  integer   Nombre de réactions
     */
    private function checkQuerelle($userId, $badgeId, $nbDocuments, $nbReactions) {
        if (!$this->hasBadge($userId, $badgeId)) {

            // TODO: requête à mettre au point...

            if (false) {
                $this->addUserBadge($userId, $badgeId);
            }
        }
    }

    /**
     *  Badges Rédacteur / Auteur / Ecrivain
     *  Être l’auteur de X contenus "débat" ou "réaction" ayant obtenu au moins +X
     *
     *  @param  $userId     integer     ID user
     *  @param  $badgeId    integer     ID badge
     *  @param  $nbDocuments  integer     Nombre de documents
     *  @param  $nbNotePos  integer     Note positive atteinte
     */
    private function checkRedacteur($userId, $badgeId, $nbDocuments, $nbNotePos) {
        if (!$this->hasBadge($userId, $badgeId)) {

            $nb = PDocumentQuery::create()
                        ->filterByPUserId($userId)
                        ->filterByNotePos(array('min' => $nbNotePos))
                        ->count();

            if ($nb >= $nbDebates) {
                $this->addUserBadge($userId, $badgeId);
            }
        }
    }

    /**
     *  Badges Eclaireur / Avant Garde / Guide
     *  Être l’auteur de la 1ère réaction sur X débats
     *
     *  @param  $reaction   PDReaction  
     *  @param  $userId     integer     ID user
     *  @param  $badgeId    integer     ID badge
     *  @param  $nbDebates  integer     Nombre de débats
     */
    private function checkEclaireur($reaction, $userId, $badgeId, $nbDebates) {
        if ($reaction->getTreeLevel() === 1 && $reaction->getTreeLeft() === 2 && !$this->hasBadge($userId, $badgeId)) {

            $sql = "
SELECT id
FROM p_d_reaction 
WHERE 
 p_user_id = ".$userId."
 and tree_level = 1
 and tree_left = 2
GROUP BY p_d_debate_id
";

            // Exécution de la requête
            $con = \Propel::getConnection('default', \Propel::CONNECTION_READ);
            $stmt = $con->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll();

            if (count($result) >= $nbDebates) {
                $this->addUserBadge($userId, $badgeId);
            }
        }
    }



}