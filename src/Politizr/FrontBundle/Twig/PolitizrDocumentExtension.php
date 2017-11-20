<?php
namespace Politizr\FrontBundle\Twig;

use Politizr\Exception\InconsistentDataException;

use Politizr\Constant\ObjectTypeConstants;
use Politizr\Constant\PathConstants;
use Politizr\Constant\ReputationConstants;
use Politizr\Constant\TagConstants;
use Politizr\Constant\DocumentConstants;

use Politizr\Model\PDocumentInterface;
use Politizr\Model\PDDebate;
use Politizr\Model\PDReaction;
use Politizr\Model\PDCommentInterface;
use Politizr\Model\PCOwner;
use Politizr\Model\PCircle;
use Politizr\Model\PCTopic;

use Politizr\Model\PDDebateQuery;
use Politizr\Model\PDReactionQuery;
use Politizr\Model\PDDCommentQuery;
use Politizr\Model\PDRCommentQuery;
use Politizr\Model\PUReputationQuery;
use Politizr\Model\PUFollowDDQuery;
use Politizr\Model\PUBookmarkDDQuery;
use Politizr\Model\PUBookmarkDRQuery;
use Politizr\Model\PEOperationQuery;

use Politizr\FrontBundle\Lib\TimelineRow;
use Politizr\FrontBundle\Lib\Publication;

use Politizr\FrontBundle\Form\Type\PDocumentTagTypeType;
use Politizr\FrontBundle\Form\Type\PDocumentTagFamilyType;

/**
 * Document's twig extension
 *
 * @author Lionel Bouzonville
 */
class PolitizrDocumentExtension extends \Twig_Extension
{
    private $securityTokenStorage;
    private $securityAuthorizationChecker;

    private $router;

    private $timelineService;
    private $userService;

    private $formFactory;

    private $globalTools;

    private $logger;

    /**
     * @security.token_storage
     * @security.authorization_checker
     * @router
     * @politizr.functional.timeline
     * @form.factory
     * @politizr.tools.global
     * @logger
     */
    public function __construct(
        $securityTokenStorage,
        $securityAuthorizationChecker,
        $router,
        $timelineService,
        $userService,
        $formFactory,
        $globalTools,
        $logger
    ) {
        $this->securityTokenStorage = $securityTokenStorage;
        $this->securityAuthorizationChecker =$securityAuthorizationChecker;

        $this->router = $router;

        $this->timelineService = $timelineService;
        $this->userService = $userService;

        $this->formFactory = $formFactory;

        $this->globalTools = $globalTools;

        $this->logger = $logger;
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
                array('is_safe' => array('html'), 'needs_environment' => true)
            ),
            new \Twig_SimpleFilter(
                'nbViews',
                array($this, 'nbViews'),
                array('is_safe' => array('html'))
            ),
            new \Twig_SimpleFilter(
                'nbElectedPublications',
                array($this, 'nbElectedPublications'),
                array('is_safe' => array('html'), 'needs_environment' => true)
            ),
            new \Twig_SimpleFilter(
                'itemContextReaction',
                array($this, 'itemContextReaction'),
                array('is_safe' => array('html'), 'needs_environment' => true)
            ),
            new \Twig_SimpleFilter(
                'itemContextComment',
                array($this, 'itemContextComment'),
                array('is_safe' => array('html'), 'needs_environment' => true)
            ),
            new \Twig_SimpleFilter(
                'nbComments',
                array($this, 'nbComments'),
                array('is_safe' => array('html'))
            ),
            new \Twig_SimpleFilter(
                'statsComments',
                array($this, 'statsComments'),
                array('is_safe' => array('html'))
            ),
            new \Twig_SimpleFilter(
                'readingTime',
                array($this, 'readingTime'),
                array('is_safe' => array('html'))
            ),
            new \Twig_SimpleFilter(
                'statsAvailable',
                array($this, 'statsAvailable'),
                array('is_safe' => array('html'))
            ),
            new \Twig_SimpleFilter(
                'localizations',
                array($this, 'localizations'),
                array('is_safe' => array('html'), 'needs_environment' => true)
            ),
            new \Twig_SimpleFilter(
                'docTags',
                array($this, 'docTags'),
                array('is_safe' => array('html'), 'needs_environment' => true)
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
                'linkParentDocument',
                array($this, 'linkParentDocument'),
                array('is_safe' => array('html'), 'needs_environment' => true)
            ),
            new \Twig_SimpleFilter(
                'linkNoteDebate',
                array($this, 'linkNoteDebate'),
                array('is_safe' => array('html'), 'needs_environment' => true)
            ),
            new \Twig_SimpleFilter(
                'linkNoteReaction',
                array($this, 'linkNoteReaction'),
                array('is_safe' => array('html'), 'needs_environment' => true)
            ),
            new \Twig_SimpleFilter(
                'linkNoteComment',
                array($this, 'linkNoteComment'),
                array('is_safe' => array('html'), 'needs_environment' => true)
            ),
            new \Twig_SimpleFilter(
                'linkSubscribeDebate',
                array($this, 'linkSubscribeDebate'),
                array('is_safe' => array('html'), 'needs_environment' => true)
            ),
            new \Twig_SimpleFilter(
                'linkCharte',
                array($this, 'linkCharte'),
                array('is_safe' => array('html'), 'needs_environment' => true)
            ),
            new \Twig_SimpleFilter(
                'followersDebate',
                array($this, 'followersDebate'),
                array('is_safe' => array('html'), 'needs_environment' => true)
            ),
            new \Twig_SimpleFilter(
                'footer',
                array($this, 'footer'),
                array('is_safe' => array('html'), 'needs_environment' => true)
            ),
            new \Twig_SimpleFilter(
                'bookmark',
                array($this, 'bookmark'),
                array('is_safe' => array('html'), 'needs_environment' => true)
            ),
            new \Twig_SimpleFilter(
                'editTagTypeForm',
                array($this, 'editTagTypeForm'),
                array('is_safe' => array('html'), 'needs_environment' => true)
            ),
            new \Twig_SimpleFilter(
                'editTagFamilyForm',
                array($this, 'editTagFamilyForm'),
                array('is_safe' => array('html'), 'needs_environment' => true)
            ),
            new \Twig_SimpleFilter(
                'documentOperation',
                array($this, 'documentOperation'),
                array('is_safe' => array('html'), 'needs_environment' => true)
            ),
            new \Twig_SimpleFilter(
                'editDocumentBanner',
                array($this, 'editDocumentBanner'),
                array('is_safe' => array('html'), 'needs_environment' => true)
            ),
            new \Twig_SimpleFilter(
                'boostQuestion',
                array($this, 'boostQuestion'),
                array('is_safe' => array('html'), 'needs_environment' => true)
            ),
            new \Twig_SimpleFilter(
                'circleContext',
                array($this, 'circleContext'),
                array('is_safe' => array('html'), 'needs_environment' => true)
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
                array('is_safe' => array('html'), 'needs_environment' => true)
            ),
            'timelineRow'  => new \Twig_SimpleFunction(
                'timelineRow',
                array($this, 'timelineRow'),
                array('is_safe' => array('html'), 'needs_environment' => true)
            ),
            'publicationRow'  => new \Twig_SimpleFunction(
                'publicationRow',
                array($this, 'publicationRow'),
                array('is_safe' => array('html'), 'needs_environment' => true)
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
     * @param boolean $email
     * @return html
     */
    public function image(\Twig_Environment $env, PDocumentInterface $document, $filterName = 'debate_header', $email = false)
    {
        // // $this->logger->info('*** image');
        // // $this->logger->info('$document = '.print_r($document, true));

        $fileName = $document->getFileName();
        
        if (!$fileName) {
            return;
        }

        switch ($document->getType()) {
            case ObjectTypeConstants::TYPE_DEBATE:
                $uploadWebPath = PathConstants::DEBATE_UPLOAD_WEB_PATH;
                $path = $uploadWebPath.$fileName;
                break;
            case ObjectTypeConstants::TYPE_REACTION:
                $uploadWebPath = PathConstants::REACTION_UPLOAD_WEB_PATH;
                $path = $uploadWebPath.$fileName;
                break;
            default:
                throw new InconsistentDataException(sprintf('Object type %s not managed', $document->getType()));
        }

        $template= '_imageHeader.html.twig';
        if ($email) {
            $template = '_imageEmail.html.twig';
        }

        // Construction du rendu du tag
        $html = $env->render(
            'PolitizrFrontBundle:Document:'.$template,
            array(
                'title' => $document->getTitle(),
                'path' => $path,
                'filterName' => $filterName,
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
        // // $this->logger->info('*** nbViews');
        // // $this->logger->info('$document = '.print_r($document, true));

        $nbViews = $document->getNbViews();

        if (!$nbViews) {
            $html = 'Aucune vue';
        } elseif (1 === $nbViews) {
            $html = '1 vue';
        } else {
            $html = $this->globalTools->readeableNumber($nbViews).' vues';
        }

        return $html;
    }

    /**
     * Document's number of elected publications
     *
     * @param PDocumentInterface $document
     * @return html
     */
    public function nbElectedPublications(\Twig_Environment $env, PDocumentInterface $document)
    {
        // // $this->logger->info('*** nbElectedPublications');
        // // $this->logger->info('$document = '.print_r($document, true));

        $nbElectedPublications = 0;
        switch ($document->getType()) {
            case ObjectTypeConstants::TYPE_DEBATE:
                $nbElectedPublications = $document->countReactions(true, true, true);

                // add elected's debate's comments + descendants
                $nbElectedPublications += $document->countComments(true, null, true);
                $reactions = $document->getChildrenReactions(true, true);
                if ($reactions) {
                    foreach ($reactions as $reaction) {
                        $nbElectedPublications += $reaction->countComments(true, null, true);
                    }
                }

                break;
            case ObjectTypeConstants::TYPE_REACTION:
                $nbElectedPublications = $document->countDescendantsReactions(true, true, true);

                // add elected's debate's comments + descendants
                $nbElectedPublications += $document->countComments(true, null, true);
                $reactions = $document->getChildrenReactions(true, true);
                if ($reactions) {
                    foreach ($reactions as $reaction) {
                        $nbElectedPublications += $reaction->countComments(true, null, true);
                    }
                }

                break;
            default:
                throw new InconsistentDataException(sprintf('Object type %s not managed', $document->getType()));
        }

        // compute labels
        if (1 === $nbElectedPublications) {
            $labelElectedPublications = '1 réaction d\'élu-e';
        } else {
            $labelElectedPublications = $this->globalTools->readeableNumber($nbElectedPublications).' réactions d\'élu-e-s';
        }

        // Construction du rendu du tag
        $html = $env->render(
            'PolitizrFrontBundle:Document:_nbElectedPublications.html.twig',
            array(
                'document' => $document,
                'nbElectedPublications' => $nbElectedPublications,
                'labelElectedPublications' => $labelElectedPublications,
            )
        );
        
        return $html;
    }
 
   /**
     * Render the item reaction context
     *
     * @param PDReaction $reaction
     * @param boolean $withContext
     * @return string
     */
    public function itemContextReaction(\Twig_Environment $env, PDReaction $reaction)
    {
        $parentReaction = null;

        if ($parentReactionId = $reaction->getParentReactionId()) {
            $parentReaction = PDReactionQuery::create()->findPk($parentReactionId);
        }
        $parentDebate = $reaction->getDebate();

        // Construction du rendu du tag
        $html = $env->render(
            'PolitizrFrontBundle:Document:_itemContext.html.twig',
            array(
                'parentReaction' => $parentReaction,
                'parentDebate' => $parentDebate,
            )
        );

        return $html;
    }

    /**
     * Render the item comment context
     *
     * @param PDCommentInterface $comment
     * @param boolean $withContext
     * @return string
     */
    public function itemContextComment(\Twig_Environment $env, PDCommentInterface $comment, $withContext = false)
    {
        $parentReaction = null;

        switch ($comment->getPDocumentType()) {
            case ObjectTypeConstants::TYPE_DEBATE:
                $parentDebate = $comment->getPDocument();
                $parentReaction = null;
                break;
            case ObjectTypeConstants::TYPE_REACTION:
                $parentReaction = $comment->getPDocument();
                $parentDebate = $parentReaction->getDebate();
                break;
            default:
                throw new InconsistentDataException(sprintf('Object type %s not managed', $comment->getPDocumentType()));
        }

        // Construction du rendu du tag
        $html = $env->render(
            'PolitizrFrontBundle:Document:_itemContext.html.twig',
            array(
                'parentReaction' => $parentReaction,
                'parentDebate' => $parentDebate,
                'withContext' => $withContext
            )
        );

        return $html;
    }

    /**
     * Nombre de commentaires d'un document.
     *
     * @param PDocumentInterface $document
     * @param integer $paragraphNo
     * @param boolean $label
     * @return string
     */
    public function nbComments(PDocumentInterface $document, $paragraphNo = null, $label = false)
    {
        // // $this->logger->info('*** nbComments');
        // // $this->logger->info('$document = '.print_r($document, true));

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
                $html = $this->globalTools->readeableNumber($nbComments);
            }
            if ($label) {
                $html = 'Voir les commentaires';
            }
        }

        return $html;
    }

    /**
     * Stats du nombre de commentaires d'un document.
     *
     * @param PDocumentInterface $document
     * @return string
     */
    public function statsComments(PDocumentInterface $document)
    {
        // // $this->logger->info('*** statsComments');
        // // $this->logger->info('$document = '.print_r($document, true));

        $nbComments = $document->countComments(true);

        switch ($document->getType()) {
            case ObjectTypeConstants::TYPE_DEBATE:
                $url = $this->router->generate('DebateDetail', array('slug' => $document->getSlug()));
                break;
            case ObjectTypeConstants::TYPE_REACTION:
                $url = $this->router->generate('ReactionDetail', array('slug' => $document->getSlug()));
                break;
            default:
                throw new InconsistentDataException(sprintf('Object type %s not managed', $document->getType()));
        }

        $url .= '#p-0';

        if (0 === $nbComments) {
            $html = 'Aucun commentaire';
        } elseif (1 === $nbComments) {
            $html = '<a href="' . $url . '">1 commentaire</a>';
        } else {
            $html = '<a href="' . $url . '">' . $nbComments . ' commentaires';
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
        // // $this->logger->info('*** readingTime');
        // // $this->logger->info('$document = '.print_r($document, true));

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
     * Stats available - or not - for a document
     *
     * @param PDocumentInterface $document
     * @return boolean
     */
    public function statsAvailable(PDocumentInterface $document)
    {
        // // $this->logger->info('*** statsAvailable');
        // // $this->logger->info('$document = '.print_r($document, true));

        $today = new \DateTime();
        if ($publishedAt = $document->getPublishedAt()) {
            $publishedAt->modify('+1 week');
            if ($publishedAt > $today) {
                return false;
            }
            return true;
        } else {
            return false;
        }

    }

    /**
     * Debate's localizations
     *
     * @param PDocumentInterface $document
     * @param boolean $inMail
     * @return string
     */
    public function localizations(\Twig_Environment $env, PDocumentInterface $document, $inMail = false)
    {
        // // $this->logger->info('*** localizations');
        // // $this->logger->info('$document = '.print_r($document, true));

        $localizations = $document->getPLocalizations();

        // Construction du rendu du tag
        $html = $env->render(
            'PolitizrFrontBundle:Document:_localizations.html.twig',
            array(
                'localizations' => $localizations,
                'inMail' => $inMail
            )
        );

        return $html;
    }

    /**
     * Debate's tags
     *
     * @param PDocumentInterface $document
     * @param boolean $displayOnly deactivate link & bubble on tags if true
     * @param integer|array $tagTypeId
     * @return string
     */
    public function docTags(\Twig_Environment $env, PDocumentInterface $document, $displayOnly = false, $tagTypeId = null)
    {
        // $this->logger->info('*** doctags');
        // $this->logger->info('$document = '.print_r($document, true));
        // $this->logger->info('$tagTypeId = '.print_r($tagTypeId, true));

        $tags = $document->getTags($tagTypeId);

        // Construction du rendu du tag
        $html = $env->render(
            'PolitizrFrontBundle:Tag:_list.html.twig',
            array(
                'tags' => $tags,
                'displayOnly' => $displayOnly,
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
        // // $this->logger->info('*** excerpt');
        // // $this->logger->info('$document = '.print_r($text, true));
        // // $this->logger->info('$nbParagraph = '.print_r($nbParagraph, true));
        // // $this->logger->info('$onlyP = '.print_r($onlyP, true));

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
     * Generate a link to the related comment's document
     *
     * @param PDCommentInterface $comment
     * @return string
     */
    public function linkParentDocument(\Twig_Environment $env, PDCommentInterface $comment)
    {
        // // $this->logger->info('*** linkParentDocument');
        // // $this->logger->info('$comment = '.print_r($comment, true));

        switch ($comment->getPDocumentType()) {
            case ObjectTypeConstants::TYPE_DEBATE:
                $parentDebate = $comment->getPDocument();
                $url = $this->router->generate('DebateDetail', array('slug' => $parentDebate->getSlug()));
                break;
            case ObjectTypeConstants::TYPE_REACTION:
                $parentReaction = $comment->getPDocument();
                $url = $this->router->generate('ReactionDetail', array('slug' => $parentReaction->getSlug()));
                break;
            default:
                throw new InconsistentDataException(sprintf('Object type %s not managed', $comment->getPDocumentType()));
        }

        $url .= '#p-'.$comment->getParagraphNo();

        // Construction du rendu du tag
        $html = $env->render(
            'PolitizrFrontBundle:Comment:_linkParentDocument.html.twig',
            array(
                'comment' => $comment,
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
    public function linkNoteDebate(\Twig_Environment $env, PDDebate $debate)
    {
        // // $this->logger->info('*** linkNoteDebate');
        // // $this->logger->info('$debate = '.print_r($debate, true));

        // get current user
        $user = $this->securityTokenStorage->getToken()->getUser();
        if (is_string($user)) {
            $user = null;
        }

        $pos = false;
        $neg = false;

        $score = null;
        $isAuthorizedToNotateNeg = false;
        $isOwnDocument = false;
        $hasAlreadyNotePos = false;
        $hasAlreadyNoteNeg = false;

        if ($user) {
            $ownDebate = PDDebateQuery::create()
                ->filterByPUserId($user->getId())
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

                $notePos = $queryPos->filterByPUserId($user->getId())
                    ->filterByPObjectId($debate->getId())
                    ->findOne();
                if ($notePos) {
                    $pos = true;
                    $hasAlreadyNotePos = true;
                }

                $noteNeg = $queryNeg->filterByPUserId($user->getId())
                    ->filterByPObjectId($debate->getId())
                    ->findOne();
                if ($noteNeg) {
                    $neg = true;
                    $hasAlreadyNoteNeg = true;
                }

                // min score management
                $score = $user->getReputationScore();
                if ($score >= ReputationConstants::ACTION_DEBATE_NOTE_NEG) {
                    $isAuthorizedToNotateNeg = true;
                }
            }
        }

        // Construction du rendu du tag
        $html = $env->render(
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
    public function linkNoteReaction(\Twig_Environment $env, PDReaction $reaction)
    {
        // // $this->logger->info('*** linkNoteReaction');
        // // $this->logger->info('$reaction = '.print_r($reaction, true));

        // get current user
        $user = $this->securityTokenStorage->getToken()->getUser();
        if (is_string($user)) {
            $user = null;
        }

        $pos = false;
        $neg = false;

        $score = null;
        $isAuthorizedToNotateNeg = false;
        $isOwnDocument = false;
        $hasAlreadyNotePos = false;
        $hasAlreadyNoteNeg = false;

        if ($user) {
            $ownReaction = PDReactionQuery::create()
                ->filterByPUserId($user->getId())
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

                $notePos = $queryPos->filterByPUserId($user->getId())
                    ->filterByPObjectId($reaction->getId())
                    ->findOne();
                if ($notePos) {
                    $pos = true;
                    $hasAlreadyNotePos = true;
                }

                $noteNeg = $queryNeg->filterByPUserId($user->getId())
                    ->filterByPObjectId($reaction->getId())
                    ->findOne();
                if ($noteNeg) {
                    $neg = true;
                    $hasAlreadyNoteNeg = true;
                }

                // min score management
                $score = $user->getReputationScore();
                if ($score >= ReputationConstants::ACTION_REACTION_NOTE_NEG) {
                    $isAuthorizedToNotateNeg = true;
                }
            }
        }

        // Construction du rendu du tag
        $html = $env->render(
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
    public function linkNoteComment(\Twig_Environment $env, PDCommentInterface $comment)
    {
        // // $this->logger->info('*** linkNoteComment');
        // // $this->logger->info('$comment = '.print_r($comment, true));

        // get current user
        $user = $this->securityTokenStorage->getToken()->getUser();
        if (is_string($user)) {
            $user = null;
        }

        $pos = false;
        $neg = false;

        $score = null;
        $isAuthorizedToNotateNeg = false;
        $isOwnDocument = false;
        $hasAlreadyNotePos = false;
        $hasAlreadyNoteNeg = false;

        if ($user) {
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
                ->filterByPUserId($user->getId())
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

                $notePos = $queryPos->filterByPUserId($user->getId())
                    ->filterByPObjectId($comment->getId())
                    ->findOne();
                if ($notePos) {
                    $pos = true;
                    $hasAlreadyNotePos = true;
                }

                $noteNeg = $queryNeg->filterByPUserId($user->getId())
                    ->filterByPObjectId($comment->getId())
                    ->findOne();
                if ($noteNeg) {
                    $neg = true;
                    $hasAlreadyNoteNeg = true;
                }

                // min score management
                $score = $user->getReputationScore();
                if ($score >= ReputationConstants::ACTION_COMMENT_NOTE_NEG) {
                    $isAuthorizedToNotateNeg = true;
                }
            }
        }

        // Construction du rendu du tag
        $html = $env->render(
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
    public function linkSubscribeDebate(\Twig_Environment $env, PDDebate $debate)
    {
        // // $this->logger->info('*** linkSubscribeDebate');
        // // $this->logger->info('$debate = '.print_r($debate, true));

        // get current user
        $user = $this->securityTokenStorage->getToken()->getUser();
        if (is_string($user)) {
            $user = null;
        }

        $owner = false;
        $follower = false;
        if ($user) {
            if ($debate->isOwner($user->getId())) {
                $owner = true;
            } else {
                $follow = PUFollowDDQuery::create()
                    ->filterByPUserId($user->getId())
                    ->filterByPDDebateId($debate->getId())
                    ->findOne();
                
                if ($follow) {
                    $follower = true;
                }
            }
        }

        // Construction du rendu du tag
        $html = $env->render(
            'PolitizrFrontBundle:Follow:_subscribeDebateLink.html.twig',
            array(
                'object' => $debate,
                'owner' => $owner,
                'follower' => $follower
            )
        );

        return $html;
    }

    /**
     * Global / specific link for charte
     *
     * @param PDocumentInterface $document
     * @return string
     */
    public function linkCharte(\Twig_Environment $env, PDocumentInterface $document)
    {
        // // $this->logger->info('*** linkCharte');
        // // $this->logger->info('$document = '.print_r($document, true));

        $uuid = null;
        if ($topic = $document->getPCTopic()) {
            $circle = $topic->getPCircle();
            $uuid = $circle->getUuid();
        }

        // Construction du rendu du tag
        $html = $env->render(
            'PolitizrFrontBundle:Document:_charteLink.html.twig',
            array(
                'uuid' => $uuid,
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
    public function followersDebate(\Twig_Environment $env, PDDebate $debate)
    {
        // // $this->logger->info('*** followersDebate');
        // // $this->logger->info('$debate = '.print_r($debate, true));

        $nbC = 0;
        $nbQ = 0;
        $followersC = array();
        $followersQ = array();

        $nbC = $debate->countFollowersC();
        $nbQ = $debate->countFollowersQ();
        $followersC = $debate->getFollowersC();
        $followersQ = $debate->getFollowersQ();

        // Construction du rendu du tag
        $html = $env->render(
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

    /**
     * Document footer explanations
     * @todo check logical code duplicate w. PolitizrUserExtension->isAuthorizedToReact
     *
     * @param PDocumentInterface $document
     * @return string
     */
    public function footer(\Twig_Environment $env, PDocumentInterface $document)
    {
        // // $this->logger->info('*** footer');
        // // $this->logger->info('$document = '.print_r($document, true));

        // get current user
        $user = $this->securityTokenStorage->getToken()->getUser();
        if (is_string($user)) {
            $user = null;
        }

        $reason = $this->userService->isAuthorizedToReact($user, $document, true);

        // Construction du rendu du tag
        $html = $env->render(
            'PolitizrFrontBundle:Document:_footer.html.twig',
            array(
                'reason' => $reason,
                'document' => $document,
            )
        );

        return $html;
    }

    /**
     * User's document bookmark link
     *
     * @param PDocumentInterface $document
     * @return string
     */
    public function bookmark(\Twig_Environment $env, PDocumentInterface $document)
    {
        // // $this->logger->info('*** bookmark');
        // // $this->logger->info('$document = '.print_r($document, true));

        // get current user
        $user = $this->securityTokenStorage->getToken()->getUser();
        if (is_string($user)) {
            $user = null;
        }

        $type = $document->getType();
        $bookmarked = false;
        if ($user) {
            switch ($type) {
                case ObjectTypeConstants::TYPE_DEBATE:
                    $query = PUBookmarkDDQuery::create()
                        ->filterByPDDebateId($document->getId())
                        ;
                    break;
                case ObjectTypeConstants::TYPE_REACTION:
                    $query = PUBookmarkDRQuery::create()
                        ->filterByPDReactionId($document->getId())
                        ;
                    break;
                default:
                    throw new InconsistentDataException(sprintf('Object type %s not managed', $document->getType()));
            }

            $puBookmark = $query->filterByPUserId($user->getId())->findOne();
            if ($puBookmark) {
                $bookmarked = true;
            }
        }

        // Construction du rendu du tag
        $html = $env->render(
            'PolitizrFrontBundle:Document:_bookmark.html.twig',
            array(
                'document' => $document,
                'type' => $type,
                'bookmarked' => $bookmarked,
            )
        );

        return $html;
    }

    /**
     * User's document edit type form
     *
     * @param PDocumentInterface $document
     * @return string
     */
    public function editTagTypeForm(\Twig_Environment $env, PDocumentInterface $document)
    {
        // $this->logger->info('*** editTagTypeForm');
        // $this->logger->info('$document = '.print_r($document, true));

        // get current user
        $user = $this->securityTokenStorage->getToken()->getUser();
        if (is_string($user)) {
            $user = null;
        }

        $form = $this->formFactory->create(
            new PDocumentTagTypeType(),
            $document,
            array('elected_mode' => $user->getQualified())
        );

        // Construction du rendu du tag
        $html = $env->render(
            'PolitizrFrontBundle:Document:_editTagTypeForm.html.twig',
            array(
                'document' => $document,
                'form' => $form->createView(),
            )
        );

        return $html;
    }

    /**
     * User's document edit family form
     *
     * @param PDocumentInterface $document
     * @return string
     */
    public function editTagFamilyForm(\Twig_Environment $env, PDocumentInterface $document)
    {
        // $this->logger->info('*** editTagFamilyForm');
        // $this->logger->info('$document = '.print_r($document, true));

        // get current user
        $user = $this->securityTokenStorage->getToken()->getUser();
        if (is_string($user)) {
            $user = null;
        }

        $form = $this->formFactory->create(
            new PDocumentTagFamilyType(),
            $document
        );

        // Construction du rendu du tag
        $html = $env->render(
            'PolitizrFrontBundle:Document:_editTagFamilyForm.html.twig',
            array(
                'document' => $document,
                'form' => $form->createView(),
            )
        );

        return $html;
    }

   /**
     * Display user's operation
     *
     * @param PDocument $subject
     * @return string
     */
    public function documentOperation(\Twig_Environment $env, PDocumentInterface $document)
    {
        // $this->logger->info('*** documentOperation');
        // $this->logger->info('$user = '.print_r($document, true));

        $user = $document->getUser();
        if (is_string($user)) {
            $user = null;
        }

        // get op for user
        $operation = null;
        if ($user) {
            $operation = PEOperationQuery::create()
                ->filterByOnline(true)
                ->filterByPUserId($user->getId())
                ->findOne();
        }

        // if none, check if document has op
        if (!$operation) {
            $debate = $document->getDebate();
            $operation = $debate->getPEOperation();
        }

        if (!$operation || $operation->getOnline() != true) {
            return null;
        }

        // Construction du rendu du tag            
        $html = $env->render(
            'PolitizrFrontBundle:User:_opBanner.html.twig',
            array(
                'operation' => $operation,
            )
        );

        return $html;
    }

   /**
     * Display context edition's document banner
     *
     * @param PDocument $subject
     * @return string
     */
    public function editDocumentBanner(\Twig_Environment $env, PDocumentInterface $document)
    {
        // $this->logger->info('*** editDocumentBanner');
        // $this->logger->info('$user = '.print_r($document, true));

        $debate = $document->getDebate();
        $operation = $debate->getPEOperation();
        $topic = $debate->getPCTopic();

        if ($operation) {
            // Operation banner
            $html = $env->render(
                'PolitizrFrontBundle:Document:_opBannerEdit.html.twig',
                array(
                    'operation' => $operation,
                )
            );
        } elseif ($topic) {
            $circle = $topic->getPCircle();
            $owner = $circle->getPCOwner();
            
            // Topic banner
            $html = $env->render(
                'PolitizrFrontBundle:Document:_topicBannerEdit.html.twig',
                array(
                    'owner' => $owner,
                    'circle' => $circle,
                    'topic' => $topic,
                )
            );
        } else {
            // Classic banner
            $html = $env->render(
                'PolitizrFrontBundle:Document:_bannerEdit.html.twig',
                array(
                )
            );
        }

        return $html;
    }

    /**
     * Display document(s circle information (breadcrumb)
     *
     * @param PDocument $subject
     * @return string
     */
    public function circleContext(\Twig_Environment $env, PDocumentInterface $document)
    {
        $html = null;

        $topic = $document->getPCTopic();
        if ($topic) {
            $html = $env->render(
                'PolitizrFrontBundle:Document:_circleContext.html.twig',
                array(
                    'circle' => $topic->getPCircle(),
                    'topic' => $topic,
                    'document' => $document,
                )
            );
        }

        return $html;
    }

    /**
     * Display boost question
     *
     * @param PDocument $subject
     * @return string
     */
    public function boostQuestion(\Twig_Environment $env, PDocumentInterface $document)
    {
        // $this->logger->info('*** boostQuestion');
        // $this->logger->info('$user = '.print_r($document, true));

        $html = null;

        // get current user
        $user = $this->securityTokenStorage->getToken()->getUser();
        if (is_string($user)) {
            $user = null;
        }

        // no boost available for circle's documents
        if ($document->getPCTopicId()) {
            return null;
        }

        $author = $document->getUser();

        if ($user && $author && $user->getId() == $author->getId() && $document->getWantBoost() == DocumentConstants::WB_NO_RESPONSE) {
            $html = $env->render(
                'PolitizrFrontBundle:Document:_boostQuestion.html.twig',
                array(
                    'document' => $document
                )
            );
        }

        return $html;
    }


    /* ######################################################################################################## */
    /*                                             FONCTIONS                                                    */
    /* ######################################################################################################## */

    /**
     * Render an item timeline row
     *
     * @param TimelineRow $timelineRow
     * @param boolean $withContext
     * @return string
     */
    public function timelineRow(\Twig_Environment $env, TimelineRow $timelineRow, $withContext = true)
    {
        // // $this->logger->info('*** timelineRow');
        // // $this->logger->info('$timelineRow = '.print_r($timelineRow, true));

        $html = '';
        $this->timelineService->setTemplatingService($env);

        switch ($timelineRow->getType()) {
            case ObjectTypeConstants::TYPE_ACTION:
                switch($actionId = $timelineRow->getId()) {
                    case ReputationConstants::ACTION_ID_D_AUTHOR_DEBATE_NOTE_POS:
                    case ReputationConstants::ACTION_ID_D_AUTHOR_DEBATE_NOTE_NEG:
                    case ReputationConstants::ACTION_ID_D_AUTHOR_REACTION_NOTE_POS:
                    case ReputationConstants::ACTION_ID_D_AUTHOR_REACTION_NOTE_NEG:
                        try {
                            $html = $this->timelineService->generateRenderingItemActionNoteDocument($timelineRow, $withContext);
                        } catch (\Exception $e) {
                            // catch rendering exception to only trace log
                            $this->logger->error($e->getMessage());
                        }
                        break;
                    case ReputationConstants::ACTION_ID_D_AUTHOR_COMMENT_NOTE_POS:
                    case ReputationConstants::ACTION_ID_D_AUTHOR_COMMENT_NOTE_NEG:
                        try {
                            $html = $this->timelineService->generateRenderingItemActionNoteComment($timelineRow, $withContext);
                        } catch (\Exception $e) {
                            // catch rendering exception to only trace log
                            $this->logger->error($e->getMessage());
                        }
                        break;
                    case ReputationConstants::ACTION_ID_U_AUTHOR_USER_FOLLOW:
                    case ReputationConstants::ACTION_ID_U_AUTHOR_USER_UNFOLLOW:
                        try {
                            $html = $this->timelineService->generateRenderingItemActionFollowUser($timelineRow, $withContext);
                        } catch (\Exception $e) {
                            // catch rendering exception to only trace log
                            $this->logger->error($e->getMessage());
                        }
                        break;
                    case ReputationConstants::ACTION_ID_D_AUTHOR_DEBATE_FOLLOW:
                    case ReputationConstants::ACTION_ID_D_AUTHOR_DEBATE_UNFOLLOW:
                        try {
                            $html = $this->timelineService->generateRenderingItemActionFollowDebate($timelineRow, $withContext);
                        } catch (\Exception $e) {
                            // catch rendering exception to only trace log
                            $this->logger->error($e->getMessage());
                        }
                        break;
                    case ReputationConstants::ACTION_ID_U_TARGET_USER_FOLLOW:
                        try {
                            $html = $this->timelineService->generateRenderingItemActionSubscribeMe($timelineRow, $withContext);
                        } catch (\Exception $e) {
                            // catch rendering exception to only trace log
                            $this->logger->error($e->getMessage());
                        }
                        break;
                    case ReputationConstants::ACTION_ID_D_TARGET_DEBATE_FOLLOW:
                        try {
                            $html = $this->timelineService->generateRenderingItemActionSubscribeMyDebate($timelineRow, $withContext);
                        } catch (\Exception $e) {
                            // catch rendering exception to only trace log
                            $this->logger->error($e->getMessage());
                        }
                        break;
                    case ReputationConstants::ACTION_ID_D_TARGET_DEBATE_NOTE_POS:
                    case ReputationConstants::ACTION_ID_D_TARGET_DEBATE_NOTE_NEG:
                    case ReputationConstants::ACTION_ID_D_TARGET_REACTION_NOTE_POS:
                    case ReputationConstants::ACTION_ID_D_TARGET_REACTION_NOTE_NEG:
                        try {
                            $html = $this->timelineService->generateRenderingItemActionNoteMyDocument($timelineRow, $withContext);
                        } catch (\Exception $e) {
                            // catch rendering exception to only trace log
                            $this->logger->error($e->getMessage());
                        }
                        break;
                    case ReputationConstants::ACTION_ID_D_TARGET_COMMENT_NOTE_POS:
                    case ReputationConstants::ACTION_ID_D_TARGET_COMMENT_NOTE_NEG:
                        try {
                            $html = $this->timelineService->generateRenderingItemActionNoteMyComment($timelineRow, $withContext);
                        } catch (\Exception $e) {
                            // catch rendering exception to only trace log
                            $this->logger->error($e->getMessage());
                        }
                        break;
                    default:
                        throw new InconsistentDataException(sprintf('Timeline action id %s not managed', $timelineRow->getId()));
                }

                break;
            case ObjectTypeConstants::TYPE_DEBATE:
                try {
                    $html = $this->timelineService->generateRenderingItemDebate($timelineRow->getId(), $withContext);
                } catch (\Exception $e) {
                    // catch rendering exception to only trace log
                    $this->logger->error($e->getMessage());
                }
                break;
            case ObjectTypeConstants::TYPE_REACTION:
                try {
                    $html = $this->timelineService->generateRenderingItemReaction($timelineRow->getId(), $withContext);
                } catch (\Exception $e) {
                    // catch rendering exception to only trace log
                    $this->logger->error($e->getMessage());
                }
                break;
            case ObjectTypeConstants::TYPE_DEBATE_COMMENT:
                try {
                    $html = $this->timelineService->generateRenderingItemDebateComment($timelineRow->getId(), $withContext);
                } catch (\Exception $e) {
                    // catch rendering exception to only trace log
                    $this->logger->error($e->getMessage());
                }
                break;
            case ObjectTypeConstants::TYPE_REACTION_COMMENT:
                try {
                    $html = $this->timelineService->generateRenderingItemReactionComment($timelineRow->getId(), $withContext);
                } catch (\Exception $e) {
                    // catch rendering exception to only trace log
                    $this->logger->error($e->getMessage());
                }
                break;
            case ObjectTypeConstants::TYPE_BADGE:
                try {
                    $html = $this->timelineService->generateRenderingItemBadge($timelineRow);
                } catch (\Exception $e) {
                    // catch rendering exception to only trace log
                    $this->logger->error($e->getMessage());
                }
                break;
            case ObjectTypeConstants::TYPE_USER:
                try {
                    $html = $this->timelineService->generateRenderingItemUser($timelineRow->getId());
                } catch (\Exception $e) {
                    // catch rendering exception to only trace log
                    $this->logger->error($e->getMessage());
                }
                break;
            default:
                throw new InconsistentDataException(sprintf('Object type %s not managed', $timelineRow->getType()));
        }

        return $html;
    }

    /**
     * Render an item timeline row
     *
     * @param Publication $publicationRow
     * @param boolean $withContext
     * @return string
     */
    public function publicationRow(\Twig_Environment $env, Publication $publicationRow, $withContext = true)
    {
        // $this->logger->info('*** publicationRow');
        // // $this->logger->info('$publicationRow = '.print_r($publicationRow, true));

        $html = '';
        $this->timelineService->setTemplatingService($env);

        switch ($publicationRow->getType()) {
            case ObjectTypeConstants::TYPE_DEBATE:
                try {
                    $html = $this->timelineService->generateRenderingItemDebate($publicationRow->getId(), $withContext);
                } catch (\Exception $e) {
                    // catch rendering exception to only trace log
                    $this->logger->error($e->getMessage());
                }
                break;
            case ObjectTypeConstants::TYPE_REACTION:
                try {
                    $html = $this->timelineService->generateRenderingItemReaction($publicationRow->getId(), $withContext);
                } catch (\Exception $e) {
                    // catch rendering exception to only trace log
                    $this->logger->error($e->getMessage());
                }
                break;
            case ObjectTypeConstants::TYPE_DEBATE_COMMENT:
                try {
                    $html = $this->timelineService->generateRenderingItemDebateComment($publicationRow->getId(), $withContext);
                } catch (\Exception $e) {
                    // catch rendering exception to only trace log
                    $this->logger->error($e->getMessage());
                }
                break;
            case ObjectTypeConstants::TYPE_REACTION_COMMENT:
                try {
                    $html = $this->timelineService->generateRenderingItemReactionComment($publicationRow->getId(), $withContext);
                } catch (\Exception $e) {
                    // catch rendering exception to only trace log
                    $this->logger->error($e->getMessage());
                }
                break;
            default:
                throw new InconsistentDataException(sprintf('Object type %s not managed', $publicationRow->getType()));
        }

        return $html;
    }


    public function getName()
    {
        return 'p_e_document';
    }
}
