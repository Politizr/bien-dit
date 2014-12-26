<?php
namespace Politizr\Frontbundle\Twig;

use Politizr\Model\PDocument;
use Politizr\Model\PRAction;

use Politizr\Model\PDocumentQuery;
use Politizr\Model\PDDebateQuery;
use Politizr\Model\PDReactionQuery;
use Politizr\Model\PDCommentQuery;
use Politizr\Model\PUReputationRAQuery;

use Politizr\FrontBundle\Lib\TimelineRow;

/**
 * Extension Twig / Gestion des documents
 *
 * @author Lionel Bouzonville
 */
class PolitizrDocumentExtension extends \Twig_Extension
{
    private $sc;

    private $logger;
    private $router;
    private $templating;

    private $user;

    /**
     *
     */
    public function __construct($serviceContainer) {
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
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('image', array($this, 'image'), array(
                    'is_safe' => array('html')
                    )
            ),
            new \Twig_SimpleFilter('nbReactions', array($this, 'nbReactions'), array(
                    'is_safe' => array('html')
                    )
            ),
            new \Twig_SimpleFilter('docTags', array($this, 'docTags'), array(
                    'is_safe' => array('html')
                    )
            ),
            new \Twig_SimpleFilter('nbViewsFormat', array($this, 'nbViewsFormat'), array(
                    'is_safe' => array('html')
                    )
            ),
        );
    }

    /**
     *  Renvoie la liste des fonctions
     */
    public function getFunctions()
    {
        return array(
            'linkNote'  => new \Twig_Function_Method($this, 'linkNote', array(
                    'is_safe' => array('html')
                    )
            ),
            'timelineRow'  => new \Twig_Function_Method($this, 'timelineRow', array(
                    'is_safe' => array('html')
                    )
            ),
        );
    }


    /* ######################################################################################################## */
    /*                                             FILTRES                                                      */
    /* ######################################################################################################## */

    /**
     *  Image d'en-tête d'un document
     *
     *  @param $document          PDocument
     *
     *  @return html
     */
    public function image($document, $filterName = 'debate_header')
    {
        // $this->logger->info('*** image');
        // $this->logger->info('$document = '.print_r($document, true));

        $path = 'bundles/politizrfront/images/default_debate.jpg';
        if ($fileName = $document->getFileName()) {
            $path = 'uploads/documents/'.$fileName;
        }

        // Construction du rendu du tag
        $html = $this->templating->render(
                            'PolitizrFrontBundle:Fragment\\Global:Image.html.twig', array(
                                'document' => $document,
                                'path' => $path,
                                'filterName' => $filterName,
                                )
                    );

        return $html;
    }


    /**
     *  Nombre de réactions d'un document.
     *
     *  pour un débat: nombre de réaction total / pour une réaction: nombre de réactions filles
     *
     *  @param $document          PDocument
     *
     *  @return html
     */
    public function nbReactions($document)
    {
        // $this->logger->info('*** nbReactions');
        // $this->logger->info('$document = '.print_r($document, true));

        $nbReactions = 0;
        switch(get_class($document)) {
            case PDocument::TYPE_DEBATE:
                $nbReactions = $document->countReactions(true, true);
                $url = $this->router->generate('DebateFeed', array('id' => $document->getId(), 'slug' => $document->getSlug()));
                break;
            case PDocument::TYPE_REACTION:
                $nbReactions = $document->countChildrenReactions(true, true);
                $url = $this->router->generate('ReactionDetail', array('id' => $document->getId(), 'slug' => $document->getSlug()));
                break;
        }

        if ($nbReactions === 0) {
            $reactions = 'Aucune réaction';
        } elseif ($nbReactions === 1) {
            $reactions = '1 réaction';
        } else {
            $reactions = $nbReactions.' réactions';
        }

        // Construction du rendu du tag
        $html = $this->templating->render(
                            'PolitizrFrontBundle:Fragment\\Global:NbReactions.html.twig', array(
                                'document' => $document,
                                'url' => $url,
                                'nbReactions' => $nbReactions,
                                'reactions' => $reactions,
                                )
                    );

        return $html;

    }


    /**
     *  Tags d'un débat
     *
     *  @param $debate          PDDebate
     *  @param $tagTypeId       Type de tag
     *
     *  @return html
     */
    public function docTags($debate, $tagTypeId)
    {
        // $this->logger->info('*** doctags');
        // $this->logger->info('$debate = '.print_r($debate, true));
        // $this->logger->info('$tagTypeId = '.print_r($tagTypeId, true));

        $tags = $debate->getTags($tagTypeId);

        // Construction du rendu du tag
        $html = $this->templating->render(
                            'PolitizrFrontBundle:Fragment\\Tag:List.html.twig', array(
                                'tags' => $tags,
                                'tagTypeId' => $tagTypeId
                                )
                    );

        return $html;

    }

    /**
     *  Nombre de vues d'un document
     *
     *  @param $nbViews         integer
     *
     *  @return html
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
                            'PolitizrFrontBundle:Fragment\\Global:NbViews.html.twig', array(
                                'nbViews' => $nbViews,
                                )
                    );

        return $html;

    }


    /* ######################################################################################################## */
    /*                                             FONCTIONS                                                    */
    /* ######################################################################################################## */

    /**
     *  Affiche & active / désactive les Note + / Note -
     *
     *  @param $object          objet
     *  @param $context         PDocument::TYPE_DEBATE / TYPE_REACTION / TYPE_COMMENT
     *
     *  @return string
     */
    public function linkNote($object, $context)
    {
        // $this->logger->info('*** linkNote');
        // $this->logger->info('$object = '.print_r($object, true));
        // $this->logger->info('$context = '.print_r($context, true));

        $pos = false;
        $neg = false;

        if ($this->user) {
            // Le user courant est-il l'auteur du debate / réaction / commentaire?
            switch($context) {
                case PDocument::TYPE_DEBATE:
                case PDocument::TYPE_REACTION:
                    $document = PDocumentQuery::create()
                        ->filterByPUserId($this->user->getId())
                        ->filterById($object->getId())
                        ->findOne();
                    break;
                case PDocument::TYPE_COMMENT:
                    $document = PDCommentQuery::create()
                        ->filterByPUserId($this->user->getId())
                        ->filterById($object->getId())
                        ->findOne();
                    break;
            }

            if ($document) {
                $pos = true;
                $neg = true;
            } else {
                // Le document a-t-il déjà été noté pos et/ou neg?
                switch($context) {
                    case PDocument::TYPE_DEBATE:
                        $queryPos = PUReputationRAQuery::create()
                            ->filterByPRActionId(PRAction::ID_D_AUTHOR_DEBATE_NOTE_POS)
                            ->filterByPObjectName('Politizr\Model\PDDebate');
                        $queryNeg = PUReputationRAQuery::create()
                            ->filterByPRActionId(PRAction::ID_D_AUTHOR_DEBATE_NOTE_NEG)
                            ->filterByPObjectName('Politizr\Model\PDDebate');
                        break;
                    case PDocument::TYPE_REACTION:
                        $queryPos = PUReputationRAQuery::create()
                            ->filterByPRActionId(PRAction::ID_D_AUTHOR_REACTION_NOTE_POS)
                            ->filterByPObjectName('Politizr\Model\PDReaction');
                        $queryNeg = PUReputationRAQuery::create()
                            ->filterByPRActionId(PRAction::ID_D_AUTHOR_REACTION_NOTE_NEG)
                            ->filterByPObjectName('Politizr\Model\PDReaction');
                        break;
                    case PDocument::TYPE_COMMENT:
                        $queryPos = PUReputationRAQuery::create()
                            ->filterByPRActionId(PRAction::ID_D_AUTHOR_COMMENT_NOTE_POS)
                            ->filterByPObjectName('Politizr\Model\PDComment');
                        $queryNeg = PUReputationRAQuery::create()
                            ->filterByPRActionId(PRAction::ID_D_AUTHOR_COMMENT_NOTE_NEG)
                            ->filterByPObjectName('Politizr\Model\PDComment');
                        break;
                }

                $notePos = $queryPos->filterByPUserId($this->user->getId())
                    ->filterByPObjectId($object->getId())
                    ->findOne();
                if ($notePos) {
                    $pos = true;
                }

                $noteNeg = $queryNeg->filterByPUserId($this->user->getId())
                    ->filterByPObjectId($object->getId())
                    ->findOne();
                if ($noteNeg) {
                    $neg = true;
                }
            }
        }

        // Construction du rendu du tag
        $html = $this->templating->render(
                            'PolitizrFrontBundle:Fragment\\Reputation:Notation.html.twig', array(
                                'object' => $object,
                                'context' => $context,
                                'pos' => $pos,
                                'neg' => $neg,
                                )
                    );

        return $html;

    }


    /**
     *  Rendu d'une ligne de la timeline en fonction du type
     *
     * @param $timelineRow      Objet TimelineRow
     *
     * @return string
     */
    public function timelineRow(TimelineRow $timelineRow)
    {
        $this->logger->info('*** timelineRow');
        $this->logger->info('$timelineRow = '.print_r($timelineRow, true));

        $html = '';
        switch ($timelineRow->getType()) {
            case PDocument::TYPE_DEBATE:
                $debate = PDDebateQuery::create()->findPk($timelineRow->getId());
                $html = $this->templating->render(
                                    'PolitizrFrontBundle:Fragment\\Debate:TimelineRow.html.twig', array(
                                        'debate' => $debate
                                        )
                            );
                break;
            case PDocument::TYPE_REACTION:
                $reaction = PDReactionQuery::create()->findPk($timelineRow->getId());

                // MAJ de variables spécifiques aux réactions
                $parentReaction = null;
                $isParentReactionMine = false;
                $isParentDebateMine = false;
                if ($reaction->getLevel() > 1);
                    $parentReaction = $reaction->getParent();
                    if ($reaction->getDebate() && $this->user->getId() == $reaction->getDebate()->getPUserId()) {
                        $isParentDebateMine = true;
                    }
                    if ($this->user->getId() == $parentReaction->getPUserId()) {
                        $isParentReactionMine = true;
                    }
                elseif ($reaction->getDebate() && $this->user->getId() == $reaction->getDebate()->getPUserId()) {
                    $isParentDebateMine = true;
                }

                $html = $this->templating->render(
                                    'PolitizrFrontBundle:Fragment\\Reaction:TimelineRow.html.twig', array(
                                        'reaction' => $reaction,
                                        'parentReaction' => $parentReaction,
                                        'isParentReactionMine' => $isParentReactionMine,
                                        'isParentDebateMine' => $isParentDebateMine,
                                        )
                            );
                break;
            case PDocument::TYPE_COMMENT:
                $comment = PDCommentQuery::create()->findPk($timelineRow->getId());

                // MAJ de variables spécifiques aux commentaires
                $isParentMine = false;
                if ($comment->getPDocument() && $this->user->getId() == $comment->getPDocument()->getPUserId()) {
                    $isParentMine = true;
                }

                $html = $this->templating->render(
                                    'PolitizrFrontBundle:Fragment\\Comment:TimelineRow.html.twig', array(
                                        'comment' => $comment,
                                        'isParentMine' => $isParentMine,
                                        )
                            );
                break;
        }

        return $html;

    }





    public function getName()
    {
        return 'p_e_document';
    }



}