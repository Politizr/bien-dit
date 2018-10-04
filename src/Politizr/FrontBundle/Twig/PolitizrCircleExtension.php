<?php
namespace Politizr\FrontBundle\Twig;

use Politizr\Exception\InconsistentDataException;

use Politizr\Model\PCircle;
use Politizr\Model\PCTopic;
use Politizr\Model\PDocumentInterface;
use Politizr\Model\PDCommentInterface;

use Politizr\Model\PCircleQuery;
use Politizr\Model\PCTopicQuery;
use Politizr\Model\PUserQuery;
use Politizr\Model\PUInPCQuery;

use Politizr\Constant\CircleConstants;
    
/**
 * User's circle twig extension
 *
 * @author Lionel Bouzonville
 */
class PolitizrCircleExtension extends \Twig_Extension
{
    private $securityTokenStorage;
    private $securityAuthorizationChecker;

    private $router;

    private $circleService;
    
    private $globalTools;

    private $logger;

    /**
     * @security.token_storage
     * @security.authorization_checker
     * @router
     * @politizr.functional.circle
     * @politizr.tools.global
     * @logger
     */
    public function __construct(
        $securityTokenStorage,
        $securityAuthorizationChecker,
        $router,
        $circleService,
        $globalTools,
        $logger
    ) {
        $this->securityTokenStorage = $securityTokenStorage;
        $this->securityAuthorizationChecker =$securityAuthorizationChecker;

        $this->router = $router;

        $this->circleService = $circleService;

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
                'circleDetail',
                array($this, 'circleDetail'),
                array('is_safe' => array('html'), 'needs_environment' => true)
            ),
            new \Twig_SimpleFilter(
                'circleActions',
                array($this, 'circleActions'),
                array('is_safe' => array('html'), 'needs_environment' => true)
            ),
            new \Twig_SimpleFilter(
                'circleMenuTop',
                array($this, 'circleMenuTop'),
                array('is_safe' => array('html'), 'needs_environment' => true)
            ),
            new \Twig_SimpleFilter(
                'circleFooter',
                array($this, 'circleFooter'),
                array('is_safe' => array('html'), 'needs_environment' => true)
            ),
            new \Twig_SimpleFilter(
                'topicStats',
                array($this, 'topicStats'),
                array('is_safe' => array('html'), 'needs_environment' => true)
            ),
            new \Twig_SimpleFilter(
                'topicBriefing',
                array($this, 'topicBriefing'),
                array('is_safe' => array('html'), 'needs_environment' => true)
            ),
            new \Twig_SimpleFilter(
                'topicQuestion',
                array($this, 'topicQuestion'),
                array('is_safe' => array('html'), 'needs_environment' => true)
            ),
            new \Twig_SimpleFilter(
                'authorizedReactionUsers',
                array($this, 'authorizedReactionUsers'),
                array('is_safe' => array('html'), 'needs_environment' => true)
            ),
            new \Twig_SimpleFilter(
                'readOnlyMessage',
                array($this, 'readOnlyMessage'),
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
            'circleBreadcrumb'  => new \Twig_SimpleFunction(
                'circleBreadcrumb',
                array($this, 'circleBreadcrumb'),
                array('is_safe' => array('html'), 'needs_environment' => true)
            ),
            'circleCardHeader'  => new \Twig_SimpleFunction(
                'circleCardHeader',
                array($this, 'circleCardHeader'),
                array('is_safe' => array('html'), 'needs_environment' => true)
            ),
        );
    }


    /* ######################################################################################################## */
    /*                                             FILTRES                                                      */
    /* ######################################################################################################## */

    /**
     * Compute right interact links between user and circle: join or consult/quit
     *
     * @param PCircle $circle
     * @return html
     */
    public function circleDetail(\Twig_Environment $env, PCircle $circle, $topics)
    {
        // $this->logger->info('*** circleDetail');
        // $this->logger->info('$circle = '.print_r($circle, true));

        // get template path > generic or dedicated
        $templatePath = 'Circle\\standard';
        // if ($circle->getPCircleTypeId() == CircleConstants::CIRCLE_TYPE_STANDARD) {
        //     $templatePath = 'Circle';
        // } else {
        //     $templatePath = 'Circle';
        // }

        // Construction du rendu du tag
        $html = $env->render(
            'PolitizrFrontBundle:'.$templatePath.':_detail.html.twig',
            array(
                'circle' => $circle,
                'topics' => $topics,
            )
        );

        return $html;
    }

    /**
     * Compute right interact links between user and circle: join or consult/quit
     *
     * @param PCircle $circle
     * @return html
     */
    public function circleActions(\Twig_Environment $env, PCircle $circle)
    {
        // $this->logger->info('*** circleActions');
        // $this->logger->info('$circle = '.print_r($circle, true));

        // get current user
        $user = $this->securityTokenStorage->getToken()->getUser();
        if (is_string($user)) {
            $user = null;
            return null;
        }

        $isMember = $this->circleService->isUserMemberOfCircle($circle, $user);

        // Construction du rendu du tag
        $html = $env->render(
            'PolitizrFrontBundle:Circle:_menuActions.html.twig',
            array(
                'isMember' => $isMember,
                'circle' => $circle
            )
        );

        return $html;
    }

    /**
     * Manage circle menu
     *
     * @param PCircle $circle
     * @return html
     */
    public function circleMenuTop(\Twig_Environment $env, PCircle $circle)
    {
        // $this->logger->info('*** circleMenuTop');
        // $this->logger->info('$circle = '.print_r($circle, true));

        // get circle's topics
        $topics = PCTopicQuery::create()
                    ->filterByPCircleId($circle->getId())
                    ->filterByOnline(true)
                    ->orderByRank()
                    ->find();

        // Construction du rendu du tag
        $html = $env->render(
            'PolitizrFrontBundle:Circle:_menuTop.html.twig',
            array(
                'circle' => $circle,
                'topics' => $topics,
            )
        );

        return $html;
    }

    /**
     * Manage circle footer
     *
     * @param PCircle $circle
     * @return html
     */
    public function circleFooter(\Twig_Environment $env, PCircle $circle)
    {
        // $this->logger->info('*** circleFooter');
        // $this->logger->info('$circle = '.print_r($circle, true));

        // get template path > generic or dedicated
        $templatePath = 'Circle\\standard';

        // Construction du rendu du tag
        $html = $env->render(
            'PolitizrFrontBundle:'.$templatePath.':_footer.html.twig',
            array(
                'circle' => $circle
            )
        );

        return $html;
    }

    /**
     * Compute topic stats
     *
     * @param PCTopic $topic
     * @return html
     */
    public function topicStats(\Twig_Environment $env, PCTopic $topic)
    {
        // $this->logger->info('*** topicStats');
        // $this->logger->info('$topic = '.print_r($topic, true));

        $nbDebates = $this->circleService->countDebatesByTopic($topic);
        $nbReactions = $this->circleService->countReactionsByTopic($topic);
        $nbComments = $this->circleService->countCommentsByTopic($topic);

        // Construction du rendu du tag
        $html = $env->render(
            'PolitizrFrontBundle:Topic:_topicStats.html.twig',
            array(
                'circle' => $topic->getPCircle(),
                'topic' => $topic,
                'nbDebates' => $nbDebates,
                'nbReactions' => $nbReactions,
                'nbComments' => $nbComments,
            )
        );

        return $html;
    }

    /**
     * Display topic briefing
     *
     * @param PCTopic $topic
     * @return html
     */
    public function topicBriefing(\Twig_Environment $env, PCTopic $topic)
    {
        // $this->logger->info('*** topicBriefing');
        // $this->logger->info('$topic = '.print_r($topic, true));

        $circle = $topic->getPCircle();

        // Construction du rendu du tag
        $html = $env->render(
            'PolitizrFrontBundle:Topic:_briefing.html.twig',
            array(
                'topic' => $topic,
                'circle' => $circle,
            )
        );

        return $html;
    }

    /**
     * Display topic "I've a new question"
     *
     * @param PCTopic $topic
     * @return html
     */
    public function topicQuestion(\Twig_Environment $env, PCTopic $topic)
    {
        // $this->logger->info('*** topicQuestion');
        // $this->logger->info('$topic = '.print_r($topic, true));

        // Construction du rendu du tag
        $html = $env->render(
            'PolitizrFrontBundle:Topic:_question.html.twig',
            array(
                'circle' => $topic->getPCircle(),
                'topic' => $topic,
            )
        );

        return $html;
    }

    /**
     * Display listing of users authorized to react in this circle
     *
     * @param PCTopic $topic
     * @return html
     */
    public function authorizedReactionUsers(\Twig_Environment $env, PCircle $circle)
    {
        // $this->logger->info('*** authorizedReactionUsers');
        // $this->logger->info('$topic = '.print_r($topic, true));

        $users = $this->circleService->getAuthorizedReactionUsersByCircle($circle);

        // get template path > generic or dedicated
        $templatePath = 'Circle\\standard';

        // Construction du rendu du tag
        $html = $env->render(
            'PolitizrFrontBundle:'.$templatePath.':_authorizedReactionUsers.html.twig',
            array(
                'users' => $users,
            )
        );

        return $html;
    }

    /**
     * Display banner if circle is in "read only" mode
     *
     * @param PDocumentInterface|PCircle|PCTopic $subject
     * @return string
     */
    public function readOnlyMessage(\Twig_Environment $env, $subject)
    {
        $html = null;

        if ($subject instanceof PDocumentInterface) {
            $topic = $subject->getPCTopic();
            if ($topic) {
                $circle = $topic->getPCircle();
            } else {
                return null;
            }
        } elseif ($subject instanceof PCTopic) {
            $circle = $subject->getPCircle();
        } elseif ($subject instanceof PCircle) {
            $circle = $subject;
        } else {
            throw new InconsistentDataException('Class not managed');
        }

        if (! $circle->getReadOnly()) {
            return null;
        }

        $html = $env->render(
            'PolitizrFrontBundle:Circle:_readOnlyBanner.html.twig',
            array(
                'circle' => $circle,
            )
        );

        return $html;
    }

    /* ######################################################################################################## */
    /*                                             FONCTIONS                                                    */
    /* ######################################################################################################## */

    /**
     * Breadcrumb
     *
     * @param PCircle $circle
     * @param PCTopic $topic
     * @param PDocument $document
     * @return html
     */
    public function circleBreadcrumb(
        \Twig_Environment $env,
        PCircle $circle = null,
        PCTopic $topic = null,
        PDocumentInterface $document = null
    ) {
        // $this->logger->info('*** circleBreadcrumb');
        // $this->logger->info('$circle = '.print_r($circle, true));

        $circleLevel = false;
        $topicLevel = false;
        $documentLevel = false;

        if ($circle) {
            $circleLevel = true;
            
            $owner = $circle->getPCOwner();
        } elseif ($topic) {
            $topicLevel = true;

            $circle = $topic->getPCircle();
            $owner = $circle->getPCOwner();
        } elseif ($document) {
            $documentLevel = true;

            $topic = $document->getPCTopic();

            if ($topic) {
                $circle = $topic->getPCircle();
                $owner = $circle->getPCOwner();
            } else {
                return null;
            }
        }

        // Construction du rendu du tag
        $html = $env->render(
            'PolitizrFrontBundle:Circle:_breadcrumb.html.twig',
            array(
                'circleLevel' => $circleLevel,
                'topicLevel' => $topicLevel,
                'documentLevel' => $documentLevel,
                'owner' => $owner,
                'circle' => $circle,
                'topic' => $topic,
                'document' => $document,
            )
        );

        return $html;
    }

    /**
     * Header for document's card (debate, reaction, comment) in circle context
     *
     * @param PDocumentInterface $document
     * @param PDCommentInterface $comment
     * @return html
     */
    public function circleCardHeader(
        \Twig_Environment $env,
        PDocumentInterface $document = null,
        PDCommentInterface $comment = null
    ) {
        // $this->logger->info('*** circleCardHeader');
        // $this->logger->info('$document = '.print_r($document, true));
        // $this->logger->info('$comment = '.print_r($comment, true));
        if ($document && $topic = $document->getPCTopic()) {
            $circle = $topic->getPCircle();
            $owner = $circle->getPCOwner();
        } elseif ($comment && $comment->getPDocument() && $topic = $comment->getPDocument()->getPCTopic()) {
            $circle = $topic->getPCircle();
            $owner = $circle->getPCOwner();
        } else {
            return null;
        }

        // Construction du rendu du tag
        $html = $env->render(
            'PolitizrFrontBundle:Document:_circleHeader.html.twig',
            array(
                'owner' => $owner,
                'circle' => $circle,
            )
        );

        return $html;
    }

    public function getName()
    {
        return 'p_e_circle';
    }
}
