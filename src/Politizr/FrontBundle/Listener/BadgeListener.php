<?php

namespace Politizr\FrontBundle\Listener;

// use Symfony\Component\HttpKernel\Event\PostResponseEvent;
use Symfony\Component\EventDispatcher\GenericEvent;

use Politizr\Constant\ReputationConstants;

use Politizr\Model\PRBadge;
use Politizr\Model\PUBadge;
use Politizr\Model\PDReaction;

use Politizr\Model\PUBadgeQuery;
use Politizr\Model\PUReputationQuery;
use Politizr\Model\PDDebateQuery;
use Politizr\Model\PDReactionQuery;
use Politizr\Model\PDDCommentQuery;
use Politizr\Model\PDRCommentQuery;
use Politizr\Model\PUFollowUQuery;

/**
 *  Gestion des badges
 *
 *  @author Lionel Bouzonville
 */
class BadgeListener
{

    protected $logger;

    /**
     *
     */
    public function __construct($logger, $eventDispatcher)
    {
        $this->logger = $logger;
        $this->eventDispatcher = $eventDispatcher;
    }


    /**
     *  Publication d'une réaction
     *
     *  @param  GenericEvent
     */
    public function onBReactionPublish(GenericEvent $event)
    {
        $this->logger->info('*** onBReactionPublish');

        $subject = $event->getSubject();
        $authorUserId = $event->getArgument('author_user_id');
        $parentUserId = $event->getArgument('parent_user_id');

        // Badges Querellé / Controversé / Polémiqué
        $this->checkQuerelle($parentUserId, ReputationConstants::BADGE_ID_QUERELLE, ReputationConstants::QUERELLE_NB_REACTIONS);
        $this->checkQuerelle($parentUserId, ReputationConstants::BADGE_ID_CONTROVERSE, ReputationConstants::CONTROVERSE_NB_REACTIONS);
        $this->checkQuerelle($parentUserId, ReputationConstants::BADGE_ID_POLEMIQUE, ReputationConstants::POLEMIQUE_NB_REACTIONS);

        // Badges Eclaireur / Avant Garde / Guide
        $this->checkEclaireur($subject, $authorUserId, ReputationConstants::BADGE_ID_ECLAIREUR, ReputationConstants::ECLAIREUR_NB_DEBATES);
        $this->checkEclaireur($subject, $authorUserId, ReputationConstants::BADGE_ID_AVANT_GARDE, ReputationConstants::AVANT_GARDE_NB_DEBATES);
        $this->checkEclaireur($subject, $authorUserId, ReputationConstants::BADGE_ID_GUIDE, ReputationConstants::GUIDE_NB_DEBATES);

    }

    /**
     * Note positive déclenchée sur un document (débat ou réaction)
     *
     * @param GenericEvent
     */
    public function onBDocumentNotePos(GenericEvent $event)
    {
        $this->logger->info('*** onBDocumentNotePos');

        $subject = $event->getSubject();
        $authorUserId = $event->getArgument('author_user_id');
        $targetUserId = $event->getArgument('target_user_id');

        // Badges Rédacteur / Auteur / Ecrivain
        $this->checkRedacteur($targetUserId, ReputationConstants::BADGE_ID_REDACTEUR, ReputationConstants::REDACTEUR_NB_DOCUMENTS, ReputationConstants::REDACTEUR_NB_NOTEPOS);
        $this->checkRedacteur($targetUserId, ReputationConstants::BADGE_ID_AUTEUR, ReputationConstants::AUTEUR_NB_DOCUMENTS, ReputationConstants::AUTEUR_NB_NOTEPOS);
        $this->checkRedacteur($targetUserId, ReputationConstants::BADGE_ID_ECRIVAIN, ReputationConstants::ECRIVAIN_NB_DOCUMENTS, ReputationConstants::ECRIVAIN_NB_NOTEPOS);

        // Badges Fougueux / Enthousiaste / Passionné
        $this->checkFougueux($authorUserId, ReputationConstants::BADGE_ID_FOUGUEUX, ReputationConstants::FOUGUEUX_NB_NOTEPOS);
        $this->checkFougueux($authorUserId, ReputationConstants::BADGE_ID_ENTHOUSIASTE, ReputationConstants::ENTHOUSIASTE_NB_NOTEPOS);
        $this->checkFougueux($authorUserId, ReputationConstants::BADGE_ID_PASSIONNE, ReputationConstants::PASSIONNE_NB_NOTEPOS);
    }

    /**
     * Note négative déclenchée sur un document (débat ou réaction)
     *
     * @param GenericEvent
     */
    public function onBDocumentNoteNeg(GenericEvent $event)
    {
        $this->logger->info('*** onBDocumentNoteNeg');

        $subject = $event->getSubject();
        $authorUserId = $event->getArgument('author_user_id');

        // Badges Persifleur / Réprobateur / Critique
        $this->checkPersifleur($authorUserId, ReputationConstants::BADGE_ID_PERSIFLEUR, ReputationConstants::PERSIFLEUR_NB_NOTENEG);
        $this->checkPersifleur($authorUserId, ReputationConstants::BADGE_ID_REPROBATEUR, ReputationConstants::REPROBATEUR_NB_NOTENEG);
        $this->checkPersifleur($authorUserId, ReputationConstants::BADGE_ID_CRITIQUE, ReputationConstants::CRITIQUE_NB_NOTENEG);
    }


    /**
     * Publication d'un commentaire
     *
     * @param GenericEvent
     */
    public function onBCommentPublish(GenericEvent $event)
    {
        $this->logger->info('*** onBCommentPublish');

        $subject = $event->getSubject();
        $authorUserId = $event->getArgument('author_user_id');

        // Badges Rédacteur / Auteur / Ecrivain
        $this->checkAnnotateur($authorUserId, ReputationConstants::BADGE_ID_ANNOTATEUR, ReputationConstants::ANNOTATEUR_NB_COMMENTS);
        $this->checkAnnotateur($authorUserId, ReputationConstants::BADGE_ID_GLOSSATEUR, ReputationConstants::GLOSSATEUR_NB_COMMENTS);
        $this->checkAnnotateur($authorUserId, ReputationConstants::BADGE_ID_COMMENTATEUR, ReputationConstants::COMMENTATEUR_NB_COMMENTS);
    }


    /**
     * Note positive déclenchée sur un commentaire
     *
     * @param GenericEvent
     */
    public function onBCommentNotePos(GenericEvent $event)
    {
        $this->logger->info('*** onBCommentNotePos');

        $subject = $event->getSubject();
        $authorUserId = $event->getArgument('author_user_id');
        $targetUserId = $event->getArgument('target_user_id');

        // Badges Effronté / Impertinent / Audacieux
        $this->checkEffronte($targetUserId, ReputationConstants::BADGE_ID_EFFRONTE, ReputationConstants::EFFRONTE_NOTEPOS);
        $this->checkEffronte($targetUserId, ReputationConstants::BADGE_ID_IMPERTINENT, ReputationConstants::IMPERTINENT_NOTEPOS);
        $this->checkEffronte($targetUserId, ReputationConstants::BADGE_ID_AUDACIEUX, ReputationConstants::AUDACIEUX_NOTEPOS);

        // Badges Fougueux / Enthousiaste / Passionné
        $this->checkFougueux($authorUserId, ReputationConstants::BADGE_ID_FOUGUEUX, ReputationConstants::FOUGUEUX_NB_NOTEPOS);
        $this->checkFougueux($authorUserId, ReputationConstants::BADGE_ID_ENTHOUSIASTE, ReputationConstants::ENTHOUSIASTE_NB_NOTEPOS);
        $this->checkFougueux($authorUserId, ReputationConstants::BADGE_ID_PASSIONNE, ReputationConstants::PASSIONNE_NB_NOTEPOS);
    }

    /**
     * Note négative déclenchée sur un commentaire
     *
     * @param GenericEvent
     */
    public function onBCommentNoteNeg(GenericEvent $event)
    {
        $this->logger->info('*** onBCommentNoteNeg');

        $subject = $event->getSubject();
        $authorUserId = $event->getArgument('author_user_id');

        // Badges Persifleur / Réprobateur / Critique
        $this->checkPersifleur($authorUserId, ReputationConstants::BADGE_ID_PERSIFLEUR, ReputationConstants::PERSIFLEUR_NB_NOTENEG);
        $this->checkPersifleur($authorUserId, ReputationConstants::BADGE_ID_REPROBATEUR, ReputationConstants::REPROBATEUR_NB_NOTENEG);
        $this->checkPersifleur($authorUserId, ReputationConstants::BADGE_ID_CRITIQUE, ReputationConstants::CRITIQUE_NB_NOTENEG);
    }



    /**
     *  Suivi d'un utilisateur
     *
     *  @param  GenericEvent
     */
    public function onBUserFollow(GenericEvent $event)
    {
        $this->logger->info('*** onBUserFollow');

        $subject = $event->getSubject();
        $authorUserId = $event->getArgument('author_user_id');
        $targetUserId = $event->getArgument('target_user_id');

        // Badges Suiveur / Disciple / Inconditionnel
        $this->checkSuiveur($authorUserId, ReputationConstants::BADGE_ID_SUIVEUR, ReputationConstants::SUIVEUR_NB_SUBSCRIBES);
        $this->checkSuiveur($authorUserId, ReputationConstants::BADGE_ID_DISCIPLE, ReputationConstants::DISCIPLE_NB_SUBSCRIBES);
        $this->checkSuiveur($authorUserId, ReputationConstants::BADGE_ID_INCONDITIONNEL, ReputationConstants::INCONDITIONNEL_NB_SUBSCRIBES);

        // Badges Important / Influent / Incontournable
        $this->checkImportant($targetUserId, ReputationConstants::BADGE_ID_IMPORTANT, ReputationConstants::IMPORTANT_NB_FOLLOWERS);
        $this->checkImportant($targetUserId, ReputationConstants::BADGE_ID_INFLUENT, ReputationConstants::INFLUENT_NB_FOLLOWERS);
        $this->checkImportant($targetUserId, ReputationConstants::BADGE_ID_INCONTOURNABLE, ReputationConstants::INCONTOURNABLE_NB_FOLLOWERS);

    }



    // ******************************************************************************************************************** //

    /**
     *  Test si un user possède un badge
     *
     *
     */
    private function hasBadge($userId, $badgeId)
    {
        $nbBadges = PUBadgeQuery::create()
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
    private function addUserBadge($userId, $badgeId)
    {
        $userBadge = new PUBadge();
        $userBadge->setPUserId($userId);
        $userBadge->setPRBadgeId($badgeId);
        $userBadge->save();

        // Notification
        $event = new GenericEvent($userBadge);
        $dispatcher = $this->eventDispatcher->dispatch('n_badge_win', $event);
    }


    // ***************************  FONCTIONS LOGIQUES ASSOCIEES A LA CREATION DES BADGES ********************************* //

    /**
     *  Badges Querellé / Controversé / Polémiqué
     *  Être l’auteur de contenus "débat" ou "réaction" ayant obtenu au moins X réactions
     *
     *  @param  $userId     integer     ID user
     *  @param  $badgeId    integer     ID badge
     *  @param  $nbReactions  integer   Nombre de réactions
     */
    private function checkQuerelle($userId, $badgeId, $nbReactions)
    {
        if (!$this->hasBadge($userId, $badgeId)) {
            $sql = "
SELECT COUNT(*) as nb
FROM
(
# Liste des réactions filles de 1er niveau pour les réactions d un user
SELECT child.id
FROM p_d_reaction parent, p_d_reaction child
WHERE
    parent.p_user_id = ".$userId."
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
    parent.p_user_id = ".$userId."
    AND child.p_d_debate_id = parent.id
    AND child.tree_level = 1
GROUP BY child.p_d_debate_id
) x
";

            // Exécution de la requête
            $con = \Propel::getConnection('default', \Propel::CONNECTION_READ);
            $stmt = $con->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll();

            if ($result[0]['nb'] >= $nbReactions) {
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
    private function checkRedacteur($userId, $badgeId, $nbDocuments, $nbNotePos)
    {
        if (!$this->hasBadge($userId, $badgeId)) {
            $nbDebate = PDDebateQuery::create()
                        ->filterByPUserId($userId)
                        ->filterByNotePos(array('min' => $nbNotePos))
                        ->count();
            $nbReaction = PDReactionQuery::create()
                        ->filterByPUserId($userId)
                        ->filterByNotePos(array('min' => $nbNotePos))
                        ->count();

            $nbTotal = $nbDebate + $nbReaction;

            if ($nbTotal >= $nbDocuments) {
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
    private function checkEclaireur(PDReaction $reaction, $userId, $badgeId, $nbDebates)
    {
        if ($reaction->getTreeLevel() === 1 && $reaction->getTreeLeft() === 2 && !$this->hasBadge($userId, $badgeId)) {
            $sql = "
SELECT id
FROM p_d_reaction 
WHERE 
    p_user_id = ".$userId."
    AND tree_level = 1
    AND tree_left = 2
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


    /**
     *  Badges Annotateur / Glossateur / Commentateur
     *  Écrire X commentaires
     *
     *  @param  $userId     integer     ID user
     *  @param  $badgeId    integer     ID badge
     *  @param  $nbComments  integer     Nombre de commentaires
     */
    private function checkAnnotateur($userId, $badgeId, $nbComments)
    {
        if (!$this->hasBadge($userId, $badgeId)) {
            $nbDebate = PDDCommentQuery::create()
                        ->filterByPUserId($userId)
                        ->count();

            $nbReaction = PDRCommentQuery::create()
                        ->filterByPUserId($userId)
                        ->count();

            $nbTotal = $nbDebate + $nbReaction;

            if ($nbTotal >= $nbComments) {
                $this->addUserBadge($userId, $badgeId);
            }
        }
    }

    /**
     *  Badges Effronté / Impertinent / Audacieux
     *  Atteindre un score de +X à un de ses commentaires
     *
     *  @param  $userId     integer     ID user
     *  @param  $badgeId    integer     ID badge
     *  @param  $nbNotePos  integer     Note positive atteinte
     */
    private function checkEffronte($userId, $badgeId, $nbNotePos)
    {
        if (!$this->hasBadge($userId, $badgeId)) {
            $nbDebate = PDDCommentQuery::create()
                        ->filterByPUserId($userId)
                        ->filterByNotePos(array('min' => $nbNotePos))
                        ->count();

            $nbReaction = PDRCommentQuery::create()
                        ->filterByPUserId($userId)
                        ->filterByNotePos(array('min' => $nbNotePos))
                        ->count();

            if ($nbDebate > 0 || $nbReaction > 0) {
                $this->addUserBadge($userId, $badgeId);
            }
        }
    }


    /**
     *  Badges Fougueux / Enthousiaste / Passionné
     *  Avoir attribué X notes positives
     *
     *  @param  $userId     integer     ID user
     *  @param  $badgeId    integer     ID badge
     *  @param  $nbNotePos  integer     Nombre de notes positives
     */
    private function checkFougueux($userId, $badgeId, $nbNotePos)
    {
        if (!$this->hasBadge($userId, $badgeId)) {
            $nb = PUReputationQuery::create()
                        ->filterByPUserId($userId)
                        ->filterByPRActionId(array(ReputationConstants::ACTION_ID_D_AUTHOR_DEBATE_NOTE_POS, ReputationConstants::ACTION_ID_D_AUTHOR_REACTION_NOTE_POS, ReputationConstants::ACTION_ID_D_AUTHOR_COMMENT_NOTE_POS))
                        ->count();

            if ($nb >= $nbNotePos) {
                $this->addUserBadge($userId, $badgeId);
            }
        }
    }

    /**
     *  Badges Persifleur / Réprobateur / Critique
     *  Avoir attribué X notes négatives
     *
     *  @param  $userId     integer     ID user
     *  @param  $badgeId    integer     ID badge
     *  @param  $nbNoteNeg  integer     Nombre de notes négatives
     */
    private function checkPersifleur($userId, $badgeId, $nbNoteNeg)
    {
        if (!$this->hasBadge($userId, $badgeId)) {
            $nb = PUReputationQuery::create()
                        ->filterByPUserId($userId)
                        ->filterByPRActionId(array(ReputationConstants::ACTION_ID_D_AUTHOR_DEBATE_NOTE_NEG, ReputationConstants::ACTION_ID_D_AUTHOR_REACTION_NOTE_NEG, ReputationConstants::ACTION_ID_D_AUTHOR_COMMENT_NOTE_NEG))
                        ->count();

            if ($nb >= $nbNoteNeg) {
                $this->addUserBadge($userId, $badgeId);
            }
        }
    }



    /**
     *  Badges Suiveur / Disciple / Inconditionnel
     *  Suivre X profils
     *
     *  @param  $userId     integer     ID user
     *  @param  $badgeId    integer     ID badge
     *  @param  $nbNoteNeg  integer     Nombre de notes négatives
     */
    private function checkSuiveur($userId, $badgeId, $nbFollow)
    {
        if (!$this->hasBadge($userId, $badgeId)) {
            $nb = PUFollowUQuery::create()
                            ->filterByPUserFollowerId($userId)
                            ->count();

            if ($nb >= $nbFollow) {
                $this->addUserBadge($userId, $badgeId);
            }
        }
    }

    /**
     *  Badges Important / Influent / Incontournable
     *  Être suivi par X profils
     *
     *  @param  $userId     integer     ID user
     *  @param  $badgeId    integer     ID badge
     *  @param  $nbNoteNeg  integer     Nombre de notes négatives
     */
    private function checkImportant($userId, $badgeId, $nbFollowers)
    {
        if (!$this->hasBadge($userId, $badgeId)) {
            $nb = PUFollowUQuery::create()
                            ->filterByPUserId($userId)
                            ->count();

            if ($nb >= $nbFollowers) {
                $this->addUserBadge($userId, $badgeId);
            }
        }
    }
}
