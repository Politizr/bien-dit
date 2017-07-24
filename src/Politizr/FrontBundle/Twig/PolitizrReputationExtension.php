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

    private $globalTools;

    private $logger;

    /**
     * @security.token_storage
     * @security.authorization_checker
     * @router
     * @politizr.tools.global
     * @logger
     */
    public function __construct(
        $securityTokenStorage,
        $securityAuthorizationChecker,
        $router,
        $globalTools,
        $logger
    ) {
        $this->securityTokenStorage = $securityTokenStorage;
        $this->securityAuthorizationChecker =$securityAuthorizationChecker;

        $this->router = $router;

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


    /* ######################################################################################################## */
    /*                                             FONCTIONS                                                    */
    /* ######################################################################################################## */


    public function getName()
    {
        return 'p_e_reputation';
    }
}
