<?php
namespace Politizr\FrontBundle\Twig;

use Politizr\Constant\ObjectTypeConstants;
use Politizr\Constant\PathConstants;

use Politizr\Model\PDocumentInterface;
use Politizr\Model\PDDebate;
use Politizr\Model\PDReaction;
use Politizr\Model\PDCommentInterface;
use Politizr\Model\PRAction;

use Politizr\Model\PDDebateQuery;
use Politizr\Model\PDReactionQuery;
use Politizr\Model\PDDCommentQuery;
use Politizr\Model\PDRCommentQuery;
use Politizr\Model\PUReputationQuery;
use Politizr\Model\PUFollowDDQuery;

use Politizr\FrontBundle\Lib\TimelineRow;

use Politizr\Exception\InconsistentDataException;

/**
 * Document's twig extension
 *
 * @author Lionel Bouzonville
 */
class PolitizrDocumentExtension extends \Twig_Extension
{
    private $sc;

    private $logger;
    private $router;
    private $templating;
    private $securityTokenStorage;
    private $timelineService;

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
        $this->securityContext = $serviceContainer->get('security.context');
        $this->timelineService = $serviceContainer->get('politizr.functional.timeline');

        // get connected user
        $token = $this->securityContext->getToken();
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
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter(
                'image',
                array($this, 'image'),
                array('is_safe' => array('html'))
            ),
            new \Twig_SimpleFilter(
                'nbReactions',
                array($this, 'nbReactions'),
                array('is_safe' => array('html'))
            ),
            new \Twig_SimpleFilter(
                'nbComments',
                array($this, 'nbComments'),
                array('is_safe' => array('html'))
            ),
            new \Twig_SimpleFilter(
                'docTags',
                array($this, 'docTags'),
                array('is_safe' => array('html'))
            ),
            new \Twig_SimpleFilter(
                'nbViewsFormat',
                array($this, 'nbViewsFormat'),
                array('is_safe' => array('html'))
            ),
            new \Twig_SimpleFilter(
                'linkParentReaction',
                array($this, 'linkParentReaction'),
                array('is_safe' => array('html'))
            ),
            new \Twig_SimpleFilter(
                'linkNoteDebate',
                array($this, 'linkNoteDebate'),
                array('is_safe' => array('html'))
            ),
            new \Twig_SimpleFilter(
                'linkNoteReaction',
                array($this, 'linkNoteReaction'),
                array('is_safe' => array('html'))
            ),
            new \Twig_SimpleFilter(
                'linkNoteComment',
                array($this, 'linkNoteComment'),
                array('is_safe' => array('html'))
            ),
            new \Twig_SimpleFilter(
                'linkSubscribeDebate',
                array($this, 'linkSubscribeDebate'),
                array('is_safe' => array('html'))
            ),
            new \Twig_SimpleFilter(
                'followersDebate',
                array($this, 'followersDebate'),
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
            'linkNote'  => new \Twig_Function_Method(
                $this,
                'linkNote',
                array('is_safe' => array('html'))
            ),
            'timelineRow'  => new \Twig_Function_Method(
                $this,
                'timelineRow',
                array('is_safe' => array('html'))
            ),
        );
    }


    /* ######################################################################################################## */
    /*                                             FILTRES                                                      */
    /* ######################################################################################################## */

    /**
     * Load an <img> html tag with the image of document and apply it a filter.
     *
     * @param PDocumentInterface $document
     * @param string $filterName
     * @param boolean $testShadow
     * @return html
     */
    public function image(PDocumentInterface $document, $filterName = 'debate_header', $testShadow = true)
    {
        // $this->logger->info('*** image');
        // $this->logger->info('$document = '.print_r($document, true));

        $path = 'bundles/politizrfront/images/default_debate.jpg';
        if ($fileName = $document->getFileName()) {
            switch ($document->getType()) {
                case ObjectTypeConstants::TYPE_DEBATE:
                    $uploadWebPath = PathConstants::DEBATE_UPLOAD_WEB_PATH;
                    break;
                case ObjectTypeConstants::TYPE_REACTION:
                    $uploadWebPath = PDReaction::UPLOAD_WEB_PATH;
                    break;
            }

            $path = $uploadWebPath.$fileName;
        }

        // Construction du rendu du tag
        $html = $this->templating->render(
            'PolitizrFrontBundle:Debate:_imageHeader.html.twig',
            array(
                'withShadow' => $document->getWithShadow(),
                'title' => $document->getTitle(),
                'path' => $path,
                'filterName' => $filterName,
                'testShadow' => $testShadow,
            )
        );

        return $html;
    }


    /**
     * Nombre de réactions d'un document.
     * pour un débat: nombre de réaction total / pour une réaction: nombre de réactions filles
     *
     * @param PDocumentInterface $document
     * @return html
     */
    public function nbReactions(PDocumentInterface $document)
    {
        // $this->logger->info('*** nbReactions');
        // $this->logger->info('$document = '.print_r($document, true));

        $nbReactions = 0;
        switch ($document->getType()) {
            case ObjectTypeConstants::TYPE_DEBATE:
                $nbReactions = $document->countReactions(true, true);
                break;
            case ObjectTypeConstants::TYPE_REACTION:
                $nbReactions = $document->countChildrenReactions(true, true);
                break;
        }

        if (0 === $nbReactions) {
            $html = 'Aucune réaction';
        } elseif (1 === $nbReactions) {
            $html = '1 réaction';
        } else {
            $html = $nbReactions.' réactions';
        }

        return $html;
    }

    /**
     * Nombre de commentaires d'un document.
     *
     * @param PDocumentInterface $document
     * @param integer $paragraphNo
     * @return string
     */
    public function nbComments(PDocumentInterface $document, $paragraphNo = null)
    {
        // $this->logger->info('*** nbComments');
        // $this->logger->info('$document = '.print_r($document, true));

        $nbComments = $document->countComments(true, $paragraphNo);

        if (null === $paragraphNo) {
            // Affichage globale
            if (0 === $nbComments) {
                $html = 'Aucun commentaire';
            } elseif (1 === $nbComments) {
                $html = '1 commentaire';
            } else {
                $html = $nbComments.' commentaires';
            }
        } else {
            // Affichage par paragraphe
            if (0 === $nbComments) {
                $html = '';
            } else {
                $html = $nbComments;
            }
        }

        return $html;
    }


    /**
     * Tags d'un débat
     *
     * @param PDDebate $debate
     * @param integer $tagTypeId
     * @return string
     */
    public function docTags(PDDebate $debate, $tagTypeId = null)
    {
        // $this->logger->info('*** doctags');
        // $this->logger->info('$debate = '.print_r($debate, true));
        // $this->logger->info('$tagTypeId = '.print_r($tagTypeId, true));

        $tags = $debate->getTags($tagTypeId);

        // Construction du rendu du tag
        $html = $this->templating->render(
            'PolitizrFrontBundle:Tag:_list.html.twig',
            array(
                'tags' => $tags
            )
        );

        return $html;
    }

    /**
     * Nombre de vues d'un document
     *
     * @param int $nbViews
     * @return html
     */
    public function nbViewsFormat($nbViews)
    {
        // $this->logger->info('*** nbViewsFormat');
        // $this->logger->info('$nbViews = '.print_r($nbViews, true));

        if ($nbViews < 10000) {
            $nbViews = number_format($nbViews, 0, ',', ' ');
        } else {
            $d = $nbViews < 1000000 ? 1000 : 1000000;
            $f = round($nbViews / $d, 1);
            $nbViews = number_format($f, $f - intval($f) ? 1 : 0, ',', ' ') . ($d == 1000 ? 'k' : 'M');
        }

        // Construction du rendu du tag
        $html = $this->templating->render(
            'PolitizrFrontBundle:Fragment\\Global:NbViews.html.twig',
            array(
                'nbViews' => $nbViews,
            )
        );

        return $html;

    }

    /**
     * Affiche le lien vers le document parent (réaction ou débat) de la réaction courante
     *
     * @param PDReaction $reaction
     * @return string
     */
    public function linkParentReaction(PDReaction $reaction)
    {
        // $this->logger->info('*** linkParentReaction');
        // $this->logger->info('$debate = '.print_r($reaction, true));

        if ($reaction->getLevel() > 1) {
            $parent = $reaction->getParent();
            $url = $this->router->generate('ReactionDetail', array('slug' => $parent->getSlug()));
        } else {
            $parent = $reaction->getDebate();
            $url = $this->router->generate('DebateDetail', array('slug' => $parent->getSlug()));
        }

        // Construction du rendu du tag
        $html = $this->templating->render(
            'PolitizrFrontBundle:Reaction:_linkParentReaction.html.twig',
            array(
                'parent' => $parent,
                'url' => $url,
            )
        );

        return $html;

    }


    /**
     * Affiche & active / désactive les Note + / Note -
     *
     * @param PDDebate $debate
     * @return html
     */
    public function linkNoteDebate(PDDebate $debate)
    {
        // $this->logger->info('*** linkNoteDebate');
        // $this->logger->info('$debate = '.print_r($debate, true));

        $pos = false;
        $neg = false;

        if ($this->user) {
            $ownDebate = PDDebateQuery::create()
                ->filterByPUserId($this->user->getId())
                ->filterById($debate->getId())
                ->findOne();

            if ($ownDebate) {
                $pos = true;
                $neg = true;
            } else {
                $queryPos = PUReputationQuery::create()
                    ->filterByPRActionId(PRAction::ID_D_AUTHOR_DEBATE_NOTE_POS)
                    ->filterByPObjectName('Politizr\Model\PDDebate');
                $queryNeg = PUReputationQuery::create()
                    ->filterByPRActionId(PRAction::ID_D_AUTHOR_DEBATE_NOTE_NEG)
                    ->filterByPObjectName('Politizr\Model\PDDebate');

                $notePos = $queryPos->filterByPUserId($this->user->getId())
                    ->filterByPObjectId($debate->getId())
                    ->findOne();
                if ($notePos) {
                    $pos = true;
                }

                $noteNeg = $queryNeg->filterByPUserId($this->user->getId())
                    ->filterByPObjectId($debate->getId())
                    ->findOne();
                if ($noteNeg) {
                    $neg = true;
                }
            }
        }

        // Construction du rendu du tag
        $html = $this->templating->render(
            'PolitizrFrontBundle:Reputation:_notation.html.twig',
            array(
                'object' => $debate,
                'type' => ObjectTypeConstants::TYPE_DEBATE,
                'pos' => $pos,
                'neg' => $neg,
            )
        );

        return $html;

    }

    /**
     *  Affiche & active / désactive les Note + / Note -
     *
     *  @param $nbViews         integer
     *
     *  @return html
     */
    public function linkNoteReaction(PDReaction $reaction)
    {
        // $this->logger->info('*** linkNoteReaction');
        // $this->logger->info('$reaction = '.print_r($reaction, true));

        $pos = false;
        $neg = false;

        if ($this->user) {
            $ownReaction = PDReactionQuery::create()
                ->filterByPUserId($this->user->getId())
                ->filterById($reaction->getId())
                ->findOne();

            if ($ownReaction) {
                $pos = true;
                $neg = true;
            } else {
                $queryPos = PUReputationQuery::create()
                    ->filterByPRActionId(PRAction::ID_D_AUTHOR_REACTION_NOTE_POS)
                    ->filterByPObjectName('Politizr\Model\PDReaction');
                $queryNeg = PUReputationQuery::create()
                    ->filterByPRActionId(PRAction::ID_D_AUTHOR_REACTION_NOTE_NEG)
                    ->filterByPObjectName('Politizr\Model\PDReaction');

                $notePos = $queryPos->filterByPUserId($this->user->getId())
                    ->filterByPObjectId($reaction->getId())
                    ->findOne();
                if ($notePos) {
                    $pos = true;
                }

                $noteNeg = $queryNeg->filterByPUserId($this->user->getId())
                    ->filterByPObjectId($reaction->getId())
                    ->findOne();
                if ($noteNeg) {
                    $neg = true;
                }
            }
        }

        // Construction du rendu du tag
        $html = $this->templating->render(
            'PolitizrFrontBundle:Reputation:_notation.html.twig',
            array(
                'object' => $reaction,
                'type' => ObjectTypeConstants::TYPE_REACTION,
                'pos' => $pos,
                'neg' => $neg,
            )
        );

        return $html;

    }

    /**
     *  Affiche & active / désactive les Note + / Note -
     *
     *  @param $nbViews         integer
     *
     *  @return html
     */
    public function linkNoteComment(PDCommentInterface $comment)
    {
        // $this->logger->info('*** linkNoteComment');
        // $this->logger->info('$comment = '.print_r($comment, true));

        $pos = false;
        $neg = false;

        if ($this->user) {
            switch ($comment->getType()) {
                case ObjectTypeConstants::TYPE_DEBATE_COMMENT:
                    $type = ObjectTypeConstants::TYPE_DEBATE_COMMENT;
                    $query = PDDCommentQuery::create();
                    break;
                case ObjectTypeConstants::TYPE_REACTION_COMMENT:
                    $type = ObjectTypeConstants::TYPE_REACTION_COMMENT;
                    $query = PDRCommentQuery::create();
                    break;
                default:
                    throw new InconsistentDataException('Object type not managed');
            }
            $document = $query
                ->filterByPUserId($this->user->getId())
                ->filterById($comment->getId())
                ->findOne();

            if ($document) {
                $pos = true;
                $neg = true;
            } else {
                $queryPos = PUReputationQuery::create()
                    ->filterByPRActionId(PRAction::ID_D_AUTHOR_COMMENT_NOTE_POS)
                    ->filterByPObjectName($comment->getType());
                $queryNeg = PUReputationQuery::create()
                    ->filterByPRActionId(PRAction::ID_D_AUTHOR_COMMENT_NOTE_NEG)
                    ->filterByPObjectName($comment->getType());

                $notePos = $queryPos->filterByPUserId($this->user->getId())
                    ->filterByPObjectId($comment->getId())
                    ->findOne();
                if ($notePos) {
                    $pos = true;
                }

                $noteNeg = $queryNeg->filterByPUserId($this->user->getId())
                    ->filterByPObjectId($comment->getId())
                    ->findOne();
                if ($noteNeg) {
                    $neg = true;
                }
            }
        }

        // Construction du rendu du tag
        $html = $this->templating->render(
            'PolitizrFrontBundle:Reputation:_notation.html.twig',
            array(
                'object' => $comment,
                'type' => $type,
                'pos' => $pos,
                'neg' => $neg,
            )
        );

        return $html;

    }

    /**
     *  Affiche le lien "Suivre" / "Ne plus suivre" / "M'inscrire" suivant le cas
     *
     *  @param $debate       PDDebate
     *
     *  @return string
     */
    public function linkSubscribeDebate(PDDebate $debate)
    {
        // $this->logger->info('*** linkSubscribeDebate');
        // $this->logger->info('$debate = '.print_r($debate, true));

        $follower = false;
        if ($this->user) {
            $follow = PUFollowDDQuery::create()
                ->filterByPUserId($this->user->getId())
                ->filterByPDDebateId($debate->getId())
                ->findOne();
            
            if ($follow) {
                $follower = true;
            }
        }

        // Construction du rendu du tag
        $html = $this->templating->render(
            'PolitizrFrontBundle:Follow:_subscribeDebate.html.twig',
            array(
                'object' => $debate,
                'follower' => $follower
            )
        );

        return $html;

    }

    /**
     *  Affiche le bloc des followers
     *
     *  @param $debate       PDDebate
     *
     *  @return string
     */
    public function followersDebate(PDDebate $debate)
    {
        // $this->logger->info('*** followersDebate');
        // $this->logger->info('$debate = '.print_r($debate, true));

        $nbC = 0;
        $nbQ = 0;
        $followersC = array();
        $followersQ = array();

        $nbC = $debate->countFollowersC();
        $nbQ = $debate->countFollowersQ();
        $followersC = $debate->getFollowersC();
        $followersQ = $debate->getFollowersQ();

        // Construction du rendu du tag
        $html = $this->templating->render(
            'PolitizrFrontBundle:Fragment\\Follow:Followers.html.twig',
            array(
                'nbC' => $nbC,
                'nbQ' => $nbQ,
                'followersC' => $followersC,
                'followersQ' => $followersQ,
            )
        );

        return $html;

    }

    /* ######################################################################################################## */
    /*                                             FONCTIONS                                                    */
    /* ######################################################################################################## */

    /**
     * Render an item timeline row
     *
     * @param TimelineRow $timelineRow
     * @param boolean $debateContext
     * @return string
     */
    public function timelineRow(TimelineRow $timelineRow, $debateContext = false)
    {
        $this->logger->info('*** timelineRow');
        $this->logger->info('$timelineRow = '.print_r($timelineRow, true));

        $html = '';

        switch ($timelineRow->getType()) {
            case ObjectTypeConstants::TYPE_DEBATE:
                $html = $this->timelineService->generateRenderingItemDebate($timelineRow->getId(), $debateContext);

                break;
            case ObjectTypeConstants::TYPE_REACTION:
                $html = $this->timelineService->generateRenderingItemReaction($timelineRow->getId(), $debateContext);

                break;
            case ObjectTypeConstants::TYPE_DEBATE_COMMENT:
                $html = $this->timelineService->generateRenderingItemDebateComment($timelineRow->getId(), $debateContext);

                break;
            case ObjectTypeConstants::TYPE_REACTION_COMMENT:
                $html = $this->timelineService->generateRenderingItemReactionComment($timelineRow->getId(), $debateContext);

                break;
        }

        return $html;
    }

    public function getName()
    {
        return 'p_e_document';
    }
}
