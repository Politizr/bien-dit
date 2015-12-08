<?php
namespace Politizr\FrontBundle\Twig;

use Politizr\Exception\InconsistentDataException;

use Politizr\Constant\ObjectTypeConstants;
use Politizr\Constant\PathConstants;
use Politizr\Constant\ReputationConstants;

use Politizr\Model\PDocumentInterface;
use Politizr\Model\PDDebate;
use Politizr\Model\PDReaction;
use Politizr\Model\PDCommentInterface;

use Politizr\Model\PDDebateQuery;
use Politizr\Model\PDReactionQuery;
use Politizr\Model\PDDCommentQuery;
use Politizr\Model\PDRCommentQuery;
use Politizr\Model\PUReputationQuery;
use Politizr\Model\PUFollowDDQuery;

use Politizr\FrontBundle\Lib\TimelineRow;

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
    private $globalTools;

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
        $this->globalTools = $serviceContainer->get('politizr.tools.global');

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
                'nbViews',
                array($this, 'nbViews'),
                array('is_safe' => array('html'))
            ),
            new \Twig_SimpleFilter(
                'nbReactions',
                array($this, 'nbReactions'),
                array('is_safe' => array('html'))
            ),
            new \Twig_SimpleFilter(
                'itemContextReaction',
                array($this, 'itemContextReaction'),
                array('is_safe' => array('html'))
            ),
            new \Twig_SimpleFilter(
                'nbComments',
                array($this, 'nbComments'),
                array('is_safe' => array('html'))
            ),
            new \Twig_SimpleFilter(
                'readingTime',
                array($this, 'readingTime'),
                array('is_safe' => array('html'))
            ),
            new \Twig_SimpleFilter(
                'docTags',
                array($this, 'docTags'),
                array('is_safe' => array('html'))
            ),
            new \Twig_SimpleFilter(
                'excerpt',
                array($this, 'excerpt'),
                array('is_safe' => array('html'))
            ),
            new \Twig_SimpleFilter(
                'removeLinks',
                array($this, 'removeLinks'),
                array('is_safe' => array('html'))
            ),
            new \Twig_SimpleFilter(
                'removeSpans',
                array($this, 'removeSpans'),
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
            'linkNote'  => new \Twig_SimpleFunction(
                'linkNote',
                array($this, 'linkNote'),
                array('is_safe' => array('html'))
            ),
            'timelineRow'  => new \Twig_SimpleFunction(
                'timelineRow',
                array($this, 'timelineRow'),
                array('is_safe' => array('html'))
            ),
        );
    }

    /* ######################################################################################################## */
    /*                                             FILTERS                                                      */
    /* ######################################################################################################## */

    /**
     * Load an <img> html tag with the image of document and apply it a filter.
     *
     * @param PDocumentInterface $document
     * @param string $filterName
     * @param boolean $withShadow
     * @param boolean $email
     * @return html
     */
    public function image(PDocumentInterface $document, $filterName = 'debate_header', $withShadow = true, $email = false)
    {
        // $this->logger->info('*** image');
        // $this->logger->info('$document = '.print_r($document, true));

        switch ($document->getType()) {
            case ObjectTypeConstants::TYPE_DEBATE:
                $uploadWebPath = PathConstants::DEBATE_UPLOAD_WEB_PATH;
                $fileName = $document->getFileName();
                $path = $uploadWebPath.$fileName;
                if (empty($fileName)) {
                    $path = PathConstants::DEBATE_DEFAULT_PATH . 'default_debate.jpg';
                }
                break;
            case ObjectTypeConstants::TYPE_REACTION:
                $uploadWebPath = PathConstants::REACTION_UPLOAD_WEB_PATH;
                $fileName = $document->getFileName();
                $path = $uploadWebPath.$fileName;
                if (empty($fileName)) {
                    $path = PathConstants::REACTION_DEFAULT_PATH . 'default_reaction.jpg';
                }
                break;
            default:
                throw new InconsistentDataException(sprintf('Object type %s not managed', $document->getType()));
        }

        $template= '_imageHeader.html.twig';
        if ($email) {
            $template = '_imageEmail.html.twig';
        }

        // Construction du rendu du tag
        $html = $this->templating->render(
            'PolitizrFrontBundle:Document:'.$template,
            array(
                'title' => $document->getTitle(),
                'path' => $path,
                'filterName' => $filterName,
                'withShadow' => $withShadow
            )
        );

        return $html;
    }

    /**
     * Document's number of views
     *
     * @param PDocumentInterface $document
     * @return html
     */
    public function nbViews(PDocumentInterface $document)
    {
        // $this->logger->info('*** nbViews');
        // $this->logger->info('$document = '.print_r($document, true));

        $nbViews = $document->getNbViews();

        if (0 === $nbViews) {
            $html = 'Aucune vue';
        } elseif (1 === $nbViews) {
            $html = '1 vue';
        } else {
            $html = $nbViews.' vues';
        }

        return $html;
    }

    /**
     * Document's number of reactions
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
                // 1st level only:
                // $nbReactions = $document->countChildrenReactions(true, true);
                $nbReactions = $document->countDescendantsReactions(true, true);
                break;
            default:
                throw new InconsistentDataException(sprintf('Object type %s not managed', $document->getType()));
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
     * Render the item reaction context
     *
     * @param PDReaction $reaction
     * @param boolean $debateContext
     * @return string
     */
    public function itemContextReaction(PDReaction $reaction, $debateContext)
    {
        $parentReaction = null;

        if ($parentReactionId = $reaction->getParentReactionId()) {
            $parentReaction = PDReactionQuery::create()->findPk($parentReactionId);
        }
        $parentDebate = $reaction->getDebate();

        $debateIsFollowed = false;
        if ($this->user) {
            $debateIsFollowed = $parentDebate->isFollowedBy($this->user->getId());
        }

        // Construction du rendu du tag
        $html = $this->templating->render(
            'PolitizrFrontBundle:Reaction:_itemContext.html.twig',
            array(
                'reaction' => $reaction,
                'parentReaction' => $parentReaction,
                'parentDebate' => $parentDebate,
                'debateIsFollowed' => $debateIsFollowed,
                'debateContext' => $debateContext
            )
        );

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
                $html = '&nbsp;';
            } else {
                $html = $nbComments;
            }
        }

        return $html;
    }

    /**
     * Reading time of a document
     *
     * @param PDocumentInterface $document
     * @return string
     */
    public function readingTime(PDocumentInterface $document)
    {
        // $this->logger->info('*** readingTime');
        // $this->logger->info('$document = '.print_r($document, true));

        $nbWords = $this->globalTools->countWords($document->getDescription());

        // https://medium.com/the-story/read-time-and-you-bc2048ab620c
        // average reading time = 275WPM
        $minutes = round($nbWords/275);

        if (0 == $minutes) {
            $html = '< 1 minute';
        } elseif (1 == $minutes) {
            $html = '1 minute';
        } else {
            $html = sprintf('%d minutes', $minutes);
        }

        return $html;
    }

    /**
     * Debate's tags
     *
     * @param PDocumentInterface $document
     * @param integer $tagTypeId
     * @param boolean $withHidden display hidden tags
     * @param string $modalDefaultType debate|reaction|user
     * @return string
     */
    public function docTags(PDocumentInterface $document, $tagTypeId = null, $withHidden = false, $modalDefaultType = ObjectTypeConstants::CONTEXT_DEBATE)
    {
        // $this->logger->info('*** doctags');
        // $this->logger->info('$document = '.print_r($document, true));
        // $this->logger->info('$tagTypeId = '.print_r($tagTypeId, true));

        $tags = $document->getTags($tagTypeId);

        // Construction du rendu du tag
        $html = $this->templating->render(
            'PolitizrFrontBundle:Tag:_list.html.twig',
            array(
                'tags' => $tags,
                'modalDefaultType' => $modalDefaultType,
            )
        );

        return $html;
    }

    /**
     * HTML text excerpt of X first paragraphs
     *
     * @param string $text
     * @param integer $nbParagraph
     * @param boolean $onlyP Extract only <p></p> elements
     * @return string
     */
    public function excerpt($text, $nbParagraph = 1, $onlyP = false)
    {
        // $this->logger->info('*** excerpt');
        // $this->logger->info('$document = '.print_r($text, true));
        // $this->logger->info('$nbParagraph = '.print_r($nbParagraph, true));
        // $this->logger->info('$onlyP = '.print_r($onlyP, true));

        // Paragraphs explode
        $paragraphs = $this->globalTools->explodeParagraphs($text, $onlyP);

        // Extract the first nbParagrpah
        $paragraphs = array_slice($paragraphs, 0, $nbParagraph);

        // Paragraphs to string reconstruction
        $html = '';
        foreach ($paragraphs as $paragraph) {
            $html .= sprintf('<p>%s</p>', $paragraph);
        }

        return $html;
    }

    /**
     * Remove <a href=""></a> from input text
     *
     * @param string $text
     * @return string
     */
    public function removeLinks($text)
    {
        $text = preg_replace('/<a href=\"(.*?)\">(.*?)<\/a>/', "\\2", $text);
        return $text;
    }

    /**
     * Remove <span ...></span> from input text
     *
     * @param string $text
     * @return string
     */
    public function removeSpans($text)
    {
        $text = preg_replace('#</?span[^>]*>#is', '', $text);
        return $text;
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
     * @param boolean $edit
     * @return string
     */
    public function linkParentReaction(PDReaction $reaction, $edit = false)
    {
        // $this->logger->info('*** linkParentReaction');
        // $this->logger->info('$debate = '.print_r($reaction, true));

        $profileSuffix = $this->globalTools->computeProfileSuffix();

        if ($edit) {
            if (null == $reaction->getParentReactionId()) {
                $parent = $reaction->getDebate();
                $url = $this->router->generate('DebateDetail'.$profileSuffix, array('slug' => $parent->getSlug()));
            } else {
                $parent = PDReactionQuery::create()->findPk($reaction->getParentReactionId());
                $url = $this->router->generate('ReactionDetail'.$profileSuffix, array('slug' => $parent->getSlug()));
            }
        } else {
            if ($reaction->getLevel() > 1) {
                $parent = $reaction->getParent();
                $url = $this->router->generate('ReactionDetail'.$profileSuffix, array('slug' => $parent->getSlug()));
            } else {
                $parent = $reaction->getDebate();
                $url = $this->router->generate('DebateDetail'.$profileSuffix, array('slug' => $parent->getSlug()));
            }
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
     * @todo to refactor check w. DocumentService->canUserNoteDocument
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

        $score = null;
        $isAuthorizedToNotateNeg = false;
        $isOwnDocument = false;
        $hasAlreadyNotePos = false;
        $hasAlreadyNoteNeg = false;

        if ($this->user) {
            $ownDebate = PDDebateQuery::create()
                ->filterByPUserId($this->user->getId())
                ->filterById($debate->getId())
                ->findOne();

            if ($ownDebate) {
                $pos = true;
                $neg = true;

                $isOwnDocument = true;
            } else {
                $queryPos = PUReputationQuery::create()
                    ->filterByPRActionId(ReputationConstants::ACTION_ID_D_AUTHOR_DEBATE_NOTE_POS)
                    ->filterByPObjectName('Politizr\Model\PDDebate');
                $queryNeg = PUReputationQuery::create()
                    ->filterByPRActionId(ReputationConstants::ACTION_ID_D_AUTHOR_DEBATE_NOTE_NEG)
                    ->filterByPObjectName('Politizr\Model\PDDebate');

                $notePos = $queryPos->filterByPUserId($this->user->getId())
                    ->filterByPObjectId($debate->getId())
                    ->findOne();
                if ($notePos) {
                    $pos = true;
                    $hasAlreadyNotePos = true;
                }

                $noteNeg = $queryNeg->filterByPUserId($this->user->getId())
                    ->filterByPObjectId($debate->getId())
                    ->findOne();
                if ($noteNeg) {
                    $neg = true;
                    $hasAlreadyNoteNeg = true;
                }

                // min score management
                $score = $this->user->getReputationScore();
                if ($score >= ReputationConstants::ACTION_DEBATE_NOTE_NEG) {
                    $isAuthorizedToNotateNeg = true;
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
                'score' => $score,
                'minScore' => ReputationConstants::ACTION_DEBATE_NOTE_NEG,
                'isAuthorizedToNotateNeg' => $isAuthorizedToNotateNeg,
                'isOwnDocument' => $isOwnDocument,
                'hasAlreadyNotePos' => $hasAlreadyNotePos,
                'hasAlreadyNoteNeg' => $hasAlreadyNoteNeg,
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

        $score = null;
        $isAuthorizedToNotateNeg = false;
        $isOwnDocument = false;
        $hasAlreadyNotePos = false;
        $hasAlreadyNoteNeg = false;

        if ($this->user) {
            $ownReaction = PDReactionQuery::create()
                ->filterByPUserId($this->user->getId())
                ->filterById($reaction->getId())
                ->findOne();

            if ($ownReaction) {
                $pos = true;
                $neg = true;

                $isOwnDocument = true;
            } else {
                $queryPos = PUReputationQuery::create()
                    ->filterByPRActionId(ReputationConstants::ACTION_ID_D_AUTHOR_REACTION_NOTE_POS)
                    ->filterByPObjectName('Politizr\Model\PDReaction');
                $queryNeg = PUReputationQuery::create()
                    ->filterByPRActionId(ReputationConstants::ACTION_ID_D_AUTHOR_REACTION_NOTE_NEG)
                    ->filterByPObjectName('Politizr\Model\PDReaction');

                $notePos = $queryPos->filterByPUserId($this->user->getId())
                    ->filterByPObjectId($reaction->getId())
                    ->findOne();
                if ($notePos) {
                    $pos = true;
                    $hasAlreadyNotePos = true;
                }

                $noteNeg = $queryNeg->filterByPUserId($this->user->getId())
                    ->filterByPObjectId($reaction->getId())
                    ->findOne();
                if ($noteNeg) {
                    $neg = true;
                    $hasAlreadyNoteNeg = true;
                }

                // min score management
                $score = $this->user->getReputationScore();
                if ($score >= ReputationConstants::ACTION_REACTION_NOTE_NEG) {
                    $isAuthorizedToNotateNeg = true;
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
                'score' => $score,
                'minScore' => ReputationConstants::ACTION_REACTION_NOTE_NEG,
                'isAuthorizedToNotateNeg' => $isAuthorizedToNotateNeg,
                'isOwnDocument' => $isOwnDocument,
                'hasAlreadyNotePos' => $hasAlreadyNotePos,
                'hasAlreadyNoteNeg' => $hasAlreadyNoteNeg,
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

        $score = null;
        $isAuthorizedToNotateNeg = false;
        $isOwnDocument = false;
        $hasAlreadyNotePos = false;
        $hasAlreadyNoteNeg = false;

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
                    throw new InconsistentDataException(sprintf('Object type %s not managed', $comment->getType()));
            }
            $document = $query
                ->filterByPUserId($this->user->getId())
                ->filterById($comment->getId())
                ->findOne();

            if ($document) {
                $pos = true;
                $neg = true;

                $isOwnDocument = true;
            } else {
                $queryPos = PUReputationQuery::create()
                    ->filterByPRActionId(ReputationConstants::ACTION_ID_D_AUTHOR_COMMENT_NOTE_POS)
                    ->filterByPObjectName($comment->getType());
                $queryNeg = PUReputationQuery::create()
                    ->filterByPRActionId(ReputationConstants::ACTION_ID_D_AUTHOR_COMMENT_NOTE_NEG)
                    ->filterByPObjectName($comment->getType());

                $notePos = $queryPos->filterByPUserId($this->user->getId())
                    ->filterByPObjectId($comment->getId())
                    ->findOne();
                if ($notePos) {
                    $pos = true;
                    $hasAlreadyNotePos = true;
                }

                $noteNeg = $queryNeg->filterByPUserId($this->user->getId())
                    ->filterByPObjectId($comment->getId())
                    ->findOne();
                if ($noteNeg) {
                    $neg = true;
                    $hasAlreadyNoteNeg = true;
                }

                // min score management
                $score = $this->user->getReputationScore();
                if ($score >= ReputationConstants::ACTION_COMMENT_NOTE_NEG) {
                    $isAuthorizedToNotateNeg = true;
                }
            }
        }

        // Construction du rendu du tag
        $html = $this->templating->render(
            'PolitizrFrontBundle:Reputation:_notation.html.twig',
            array(
                'object' => $comment,
                'type' => $comment->getType(),
                'pos' => $pos,
                'neg' => $neg,
                'score' => $score,
                'minScore' => ReputationConstants::ACTION_COMMENT_NOTE_NEG,
                'isAuthorizedToNotateNeg' => $isAuthorizedToNotateNeg,
                'isOwnDocument' => $isOwnDocument,
                'hasAlreadyNotePos' => $hasAlreadyNotePos,
                'hasAlreadyNoteNeg' => $hasAlreadyNoteNeg,
            )
        );

        return $html;

    }

    /**
     * Follow / unfollow debate
     *
     * @param PDDebate $debate
     * @return string
     */
    public function linkSubscribeDebate(PDDebate $debate)
    {
        // $this->logger->info('*** linkSubscribeDebate');
        // $this->logger->info('$debate = '.print_r($debate, true));

        $owner = false;
        $follower = false;
        if ($this->user) {
            if ($debate->isOwner($this->user->getId())) {
                $owner = true;
            } else {
                $follow = PUFollowDDQuery::create()
                    ->filterByPUserId($this->user->getId())
                    ->filterByPDDebateId($debate->getId())
                    ->findOne();
                
                if ($follow) {
                    $follower = true;
                }
            }
        }

        // Construction du rendu du tag
        $html = $this->templating->render(
            'PolitizrFrontBundle:Follow:_subscribeDebate.html.twig',
            array(
                'object' => $debate,
                'owner' => $owner,
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
            default:
                throw new InconsistentDataException(sprintf('Object type %s not managed', $timelineRow->getType()));
        }

        return $html;
    }

    public function getName()
    {
        return 'p_e_document';
    }
}
