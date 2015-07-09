<?php
namespace Politizr\FrontBundle\Twig;

use Politizr\Model\PDocumentQuery;
use Politizr\Model\PDDebateQuery;
use Politizr\Model\PDReactionQuery;
use Politizr\Model\PDCommentQuery;
use Politizr\Model\PUserQuery;

use Politizr\Model\PRBadgeMetal;
use Politizr\Model\PUReputation;
use Politizr\Model\PDocument;

/**
 * Extension Twig / Gestion réputation
 *
 * @author Lionel Bouzonville
 */
class PolitizrReputationExtension extends \Twig_Extension
{
    private $sc;

    private $logger;
    private $router;
    private $templating;

    private $user;

    /**
     *
     */
    public function __construct($serviceContainer)
    {
        $this->sc = $serviceContainer;
        
        $this->logger = $serviceContainer->get('logger');
        $this->router = $serviceContainer->get('router');
        $this->templating = $serviceContainer->get('templating');

        // Récupération du user en session
        $token = $serviceContainer->get('security.context')->getToken();
        if ($token && $user = $token->getUser()) {
            $className = 'Politizr\Model\PUser';
            if ($user && $user instanceof $className) {
                $this->user = $user;
            } else {
                $this->user = null;
            }
        } else {
            $this->user = null;
        }

    }

    /* ######################################################################################################## */
    /*                                              FONCTIONS ET FILTRES                                        */
    /* ######################################################################################################## */


    /**
     *  Renvoie la liste des filtres
     */
    /**
     *  Renvoie la liste des filtres
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
                'scoreEvolution',
                array($this, 'scoreEvolution'),
                array('is_safe' => array('html'))
            ),
        );
    }

    /**
     *  Renvoie la liste des fonctions
     */
    public function getFunctions()
    {
        return array(
            'badgeMetalTwBootClass'  => new \Twig_Function_Method(
                $this,
                'badgeMetalTwBootClass',
                array('is_safe' => array('html'))
            ),
        );
    }


    /* ######################################################################################################## */
    /*                                             FILTRES                                                      */
    /* ######################################################################################################## */

    /**
     *  Construit un lien vers le débat / réaction / commentaire sur lequel il y a eu interaction.
     *
     *  @param $reputation          PUReputation
     *
     *  @return html
     */
    public function linkedReputation(PUReputation $reputation)
    {
        // $this->logger->info('*** linkedReputation');
        // $this->logger->info('$reputation = '.print_r($reputation, true));

        $url = '#';
        $title = 'Action inconnue';

        switch ($reputation->getPObjectName()) {
            case PDocument::TYPE_DEBATE:
                $subject = PDDebateQuery::create()->findPk($reputation->getPObjectId());
                if ($subject) {
                    $title = $subject->getTitle();
                    $url = $this->router->generate('DebateDetail', array('slug' => $subject->getSlug()));
                } else {
                    $title = 'Débat supprimé';
                    $url = '#';
                }
                break;
            case PDocument::TYPE_REACTION:
                $subject = PDReactionQuery::create()->findPk($reputation->getPObjectId());
                if ($subject) {
                    $title = $subject->getTitle();
                    $url = $this->router->generate('ReactionDetail', array('slug' => $subject->getSlug()));
                } else {
                    $title = 'Réaction supprimée';
                    $url = '#';
                }
                break;
            case PDocument::TYPE_COMMENT:
                $subject = PDCommentQuery::create()->findPk($reputation->getPObjectId());
                if ($subject) {
                    $title = $subject->getDescription();
                    $document = PDocumentQuery::create()->findPk($subject->getPDocumentId());
                    if ($document->getDescendantClass() == PDocument::TYPE_DEBATE) {
                        $url = $this->router->generate('DebateDetail', array('slug' => $document->getDebate()->getSlug()));
                    } else {
                        $url = $this->router->generate('ReactionDetail', array('slug' => $document->getReaction()->getSlug()));
                    }
                } else {
                    $title = 'Commentaire supprimé';
                    $url = '#';
                }
                break;
            case PDocument::TYPE_USER:
                $subject = PUserQuery::create()->findPk($reputation->getPObjectId());
                if ($subject) {
                    $title = $subject->getFirstname().' '.$subject->getName();
                    $url = $this->router->generate('UserDetail', array('slug' => $subject->getSlug()));
                } else {
                    $title = 'Utilisateur supprimé';
                    $url = '#';
                }
                break;
        }

        // Construction du rendu du tag
        $html = '<a href="'.$url.'">'.$title.'</a>';

        return $html;
    }

    /**
     *  Renvoie l'évolution du score de réputation formatté.
     *
     *  @param $reputation          PUReputation
     *
     *  @return html
     */
    public function scoreEvolution(PUReputation $reputation)
    {
        // $this->logger->info('*** scoreEvolution');
        // $this->logger->info('$reputation = '.print_r($reputation, true));

        $action = $reputation->getPRAction();
        if (null === $action) {
            throw new \Politizr\Exception\InconsistentDataException(sprintf('PUReputation id-%s has no associated PRAction', $reputation->getId()));
        } else {
            $score = $action->getScoreEvolution();

            // Construction du rendu du tag
            if ($score > 0) {
                $html = '<strong>+' . $score . '</strong>';
            } else {
                $html = '<strong>' . $score . '</strong>';
            }
        }

        return $html;
    }




    /* ######################################################################################################## */
    /*                                             FONCTIONS                                                    */
    /* ######################################################################################################## */


   /**
     *  Renvoit une classe de label twitter bootstrap 3 en fonction de l'id BadgeMetal
     *
     *  @param $uiser        uiser       PDDebate
     *  @param $tagTypeId  integer     ID type de tag
     *
     *  @return string
     */
    public function badgeMetalTwBootClass($badgeMetalId)
    {
        $this->logger->info('*** badgeMetalTwBootClass');
        // $this->logger->info('$badgeMetalId = '.print_r($badgeMetalId, true));

        $twClass = 'label-info';
        if ($badgeMetalId == PRBadgeMetal::GOLD) {
            $twClass = 'label-warning';
        } elseif ($badgeMetalId == PRBadgeMetal::SILVER) {
            $twClass = 'label-default';
        } elseif ($badgeMetalId == PRBadgeMetal::BRONZE) {
            $twClass = 'label-danger';
        }

        return $twClass;
    }

    public function getName()
    {
        return 'p_e_reputation';
    }
}
