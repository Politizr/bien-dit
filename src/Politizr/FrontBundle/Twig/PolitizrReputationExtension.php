<?php
namespace Politizr\FrontBundle\Twig;

use Politizr\Exception\InconsistentDataException;

use Politizr\Model\PDDebateQuery;
use Politizr\Model\PDReactionQuery;
use Politizr\Model\PDDCommentQuery;
use Politizr\Model\PDRCommentQuery;
use Politizr\Model\PUserQuery;

use Politizr\Model\PUReputation;
use Politizr\Model\PRBadge;

use Politizr\Constant\ObjectTypeConstants;
use Politizr\Constant\ReputationConstants;

/**
 * User's reputation twig extension
 *
 * @author Lionel Bouzonville
 */
class PolitizrReputationExtension extends \Twig_Extension
{
    private $securityTokenStorage;
    private $securityAuthorizationChecker;

    private $router;
    private $templating;

    private $globalTools;

    private $logger;

    /**
     * @security.token_storage
     * @security.authorization_checker
     * @router
     * @templating
     * @politizr.tools.global
     * @logger
     */
    public function __construct(
        $securityTokenStorage,
        $securityAuthorizationChecker,
        $router,
        $templating,
        $globalTools,
        $logger
    ) {
        $this->securityTokenStorage = $securityTokenStorage;
        $this->securityAuthorizationChecker =$securityAuthorizationChecker;

        $this->router = $router;
        $this->templating = $templating;

        $this->globalTools = $globalTools;

        $this->logger = $logger;
    }

    /* ######################################################################################################## */
    /*                                              FONCTIONS ET FILTRES                                        */
    /* ######################################################################################################## */

    /**
     * Filters list
     *
     * @return array
     */
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter(
                'linkedReputation',
                array($this, 'linkedReputation'),
                array('is_safe' => array('html'))
            ),
            new \Twig_SimpleFilter(
                'howToReach',
                array($this, 'howToReach'),
                array('is_safe' => array('html'))
            ),
        );
    }


    /* ######################################################################################################## */
    /*                                             FILTRES                                                      */
    /* ######################################################################################################## */

    /**
     * Construit un lien vers le débat / réaction / commentaire sur lequel il y a eu interaction.
     *
     * @param PUReputation $reputation
     * @return html
     */
    public function linkedReputation(PUReputation $reputation)
    {
        // $this->logger->info('*** linkedReputation');
        // $this->logger->info('$reputation = '.print_r($reputation, true));

        $url = '#';
        $title = 'Action inconnue';

        switch ($reputation->getPObjectName()) {
            case ObjectTypeConstants::TYPE_DEBATE:
                $subject = PDDebateQuery::create()->findPk($reputation->getPObjectId());
                if ($subject) {
                    $title = $subject->getTitle();
                    $url = $this->router->generate('DebateDetail', array('slug' => $subject->getSlug()));
                } else {
                    $title = 'Débat supprimé';
                    $url = '#';
                }
                break;
            case ObjectTypeConstants::TYPE_REACTION:
                $subject = PDReactionQuery::create()->findPk($reputation->getPObjectId());
                if ($subject) {
                    $title = $subject->getTitle();
                    $url = $this->router->generate('ReactionDetail', array('slug' => $subject->getSlug()));
                } else {
                    $title = 'Réaction supprimée';
                    $url = '#';
                }
                break;
            case ObjectTypeConstants::TYPE_DEBATE_COMMENT:
                $subject = PDDCommentQuery::create()->findPk($reputation->getPObjectId());

                if ($subject) {
                    $title = $subject->getDescription();
                    $document = $subject->getPDocument();
                    $url = $this->router->generate('DebateDetail', array('slug' => $document->getSlug()));
                } else {
                    $title = 'Commentaire supprimé';
                    $url = '#';
                }
                break;
            case ObjectTypeConstants::TYPE_REACTION_COMMENT:
                $subject = PDRCommentQuery::create()->findPk($reputation->getPObjectId());

                if ($subject) {
                    $title = $subject->getDescription();
                    $document = $subject->getPDocument();
                    $url = $this->router->generate('ReactionDetail', array('slug' => $document->getSlug()));
                } else {
                    $title = 'Commentaire supprimé';
                    $url = '#';
                }
                break;
            case ObjectTypeConstants::TYPE_USER:
                $subject = PUserQuery::create()->findPk($reputation->getPObjectId());
                if ($subject) {
                    $title = $subject->getFirstname().' '.$subject->getName();
                    $url = $this->router->generate('UserDetail', array('slug' => $subject->getSlug()));
                } else {
                    $title = 'Utilisateur supprimé';
                    $url = '#';
                }
                break;
            default:
                throw new InconsistentDataException(sprintf('Object name %s not managed', $reputation->getPObjectName()));
        }

        // Construction du rendu du tag
        $html = '<a href="'.$url.'">'.$title.'</a>';

        return $html;
    }

    /**
     * Return explanation about how to reach a badge
     * beta
     *
     * @param PRBadge $badge
     * @return html
     */
    public function howToReach(PRBadge $badge)
    {
        // $this->logger->info('*** howToReach');
        // $this->logger->info('$badge = '.print_r($badge, true));

        $family = $badge->getPRBadgeFamily();

        switch($badge->getId()) {
            case ReputationConstants::BADGE_ID_QUERELLE:
                $html = sprintf($family->getDescription(), ReputationConstants::QUERELLE_NB_REACTIONS);
                break;
            case ReputationConstants::BADGE_ID_CONTROVERSE:
                $html = sprintf($family->getDescription(), ReputationConstants::CONTROVERSE_NB_REACTIONS);
                break;
            case ReputationConstants::BADGE_ID_POLEMIQUE:
                $html = sprintf($family->getDescription(), ReputationConstants::POLEMIQUE_NB_REACTIONS);
                break;
            case ReputationConstants::BADGE_ID_REDACTEUR:
                $html = sprintf($family->getDescription(), ReputationConstants::REDACTEUR_NB_DOCUMENTS, ReputationConstants::REDACTEUR_NB_NOTEPOS);
                break;
            case ReputationConstants::BADGE_ID_REDACTEUR:
                $html = sprintf($family->getDescription(), ReputationConstants::REDACTEUR_NB_DOCUMENTS, ReputationConstants::REDACTEUR_NB_NOTEPOS);
                break;
            case ReputationConstants::BADGE_ID_AUTEUR:
                $html = sprintf($family->getDescription(), ReputationConstants::AUTEUR_NB_DOCUMENTS, ReputationConstants::AUTEUR_NB_NOTEPOS);
                break;
            case ReputationConstants::BADGE_ID_ECRIVAIN:
                $html = sprintf($family->getDescription(), ReputationConstants::ECRIVAIN_NB_DOCUMENTS, ReputationConstants::ECRIVAIN_NB_NOTEPOS);
                break;
            case ReputationConstants::BADGE_ID_ECLAIREUR:
                $html = sprintf($family->getDescription(), ReputationConstants::ECLAIREUR_NB_DEBATES);
                break;
            case ReputationConstants::BADGE_ID_AVANT_GARDE:
                $html = sprintf($family->getDescription(), ReputationConstants::AVANT_GARDE_NB_DEBATES);
                break;
            case ReputationConstants::BADGE_ID_GUIDE:
                $html = sprintf($family->getDescription(), ReputationConstants::GUIDE_NB_DEBATES);
                break;
            case ReputationConstants::BADGE_ID_ANNOTATEUR:
                $html = sprintf($family->getDescription(), ReputationConstants::ANNOTATEUR_NB_COMMENTS);
                break;
            case ReputationConstants::BADGE_ID_GLOSSATEUR:
                $html = sprintf($family->getDescription(), ReputationConstants::GLOSSATEUR_NB_COMMENTS);
                break;
            case ReputationConstants::BADGE_ID_COMMENTATEUR:
                $html = sprintf($family->getDescription(), ReputationConstants::COMMENTATEUR_NB_COMMENTS);
                break;
            case ReputationConstants::BADGE_ID_EFFRONTE:
                $html = sprintf($family->getDescription(), ReputationConstants::EFFRONTE_NOTEPOS);
                break;
            case ReputationConstants::BADGE_ID_IMPERTINENT:
                $html = sprintf($family->getDescription(), ReputationConstants::IMPERTINENT_NOTEPOS);
                break;
            case ReputationConstants::BADGE_ID_AUDACIEUX:
                $html = sprintf($family->getDescription(), ReputationConstants::AUDACIEUX_NOTEPOS);
                break;
            case ReputationConstants::BADGE_ID_STUDIEUX:
                $html = $family->getDescription();
                break;
            case ReputationConstants::BADGE_ID_TAGUEUR:
                $html = sprintf($family->getDescription(), ReputationConstants::TAGUEUR_NB_TAGS);
                break;
            case ReputationConstants::BADGE_ID_SURVEILLANT:
                $html = sprintf($family->getDescription(), ReputationConstants::SURVEILLANT_NB_MODERATIONS);
                break;
            case ReputationConstants::BADGE_ID_FOUGUEUX:
                $html = sprintf($family->getDescription(), ReputationConstants::FOUGUEUX_NB_NOTEPOS);
                break;
            case ReputationConstants::BADGE_ID_ENTHOUSIASTE:
                $html = sprintf($family->getDescription(), ReputationConstants::ENTHOUSIASTE_NB_NOTEPOS);
                break;
            case ReputationConstants::BADGE_ID_PASSIONNE:
                $html = sprintf($family->getDescription(), ReputationConstants::PASSIONNE_NB_NOTEPOS);
                break;
            case ReputationConstants::BADGE_ID_PERSIFLEUR:
                $html = sprintf($family->getDescription(), ReputationConstants::PERSIFLEUR_NB_NOTENEG);
                break;
            case ReputationConstants::BADGE_ID_REPROBATEUR:
                $html = sprintf($family->getDescription(), ReputationConstants::REPROBATEUR_NB_NOTENEG);
                break;
            case ReputationConstants::BADGE_ID_CRITIQUE:
                $html = sprintf($family->getDescription(), ReputationConstants::CRITIQUE_NB_NOTENEG);
                break;
            case ReputationConstants::BADGE_ID_ATTENTIF:
                $html = sprintf($family->getDescription(), ReputationConstants::ATTENTIF_NB_DAYS);
                break;
            case ReputationConstants::BADGE_ID_ASSIDU:
                $html = sprintf($family->getDescription(), ReputationConstants::ASSIDU_NB_DAYS);
                break;
            case ReputationConstants::BADGE_ID_FIDELE:
                $html = sprintf($family->getDescription(), ReputationConstants::FIDELE_NB_DAYS);
                break;
            case ReputationConstants::BADGE_ID_SUIVEUR:
                $html = sprintf($family->getDescription(), ReputationConstants::SUIVEUR_NB_SUBSCRIBES);
                break;
            case ReputationConstants::BADGE_ID_DISCIPLE:
                $html = sprintf($family->getDescription(), ReputationConstants::DISCIPLE_NB_SUBSCRIBES);
                break;
            case ReputationConstants::BADGE_ID_INCONDITIONNEL:
                $html = sprintf($family->getDescription(), ReputationConstants::INCONDITIONNEL_NB_SUBSCRIBES);
                break;
            case ReputationConstants::BADGE_ID_IMPORTANT:
                $html = sprintf($family->getDescription(), ReputationConstants::IMPORTANT_NB_FOLLOWERS);
                break;
            case ReputationConstants::BADGE_ID_INFLUENT:
                $html = sprintf($family->getDescription(), ReputationConstants::INFLUENT_NB_FOLLOWERS);
                break;
            case ReputationConstants::BADGE_ID_INCONTOURNABLE:
                $html = sprintf($family->getDescription(), ReputationConstants::INCONTOURNABLE_NB_FOLLOWERS);
                break;
            case ReputationConstants::BADGE_ID_PORTE_VOIX:
                $html = sprintf($family->getDescription(), ReputationConstants::PORTE_VOIX_NB_SHARE);
                break;
            case ReputationConstants::BADGE_ID_FAN:
                $html = sprintf($family->getDescription(), ReputationConstants::FAN_NB_SHARE);
                break;
            case ReputationConstants::BADGE_ID_AMBASSADEUR:
                $html = sprintf($family->getDescription(), ReputationConstants::AMBASSADEUR_NB_SHARE);
                break;
            default:
                throw new InconsistentDataException(sprintf('Badge ID-%s not recognized', $badge->getId()));
        }

        return $html;
    }


    /* ######################################################################################################## */
    /*                                             FONCTIONS                                                    */
    /* ######################################################################################################## */


    public function getName()
    {
        return 'p_e_reputation';
    }
}
