<?php
namespace Politizr\FrontBundle\Twig;

use Politizr\Constant\ReputationConstants;
use Politizr\Constant\ObjectTypeConstants;
use Politizr\Constant\UserConstants;
use Politizr\Constant\PathConstants;

use Politizr\Model\PDDebate;
use Politizr\Model\PNotification;
use Politizr\Model\PUNotification;
use Politizr\Model\PUser;

use Politizr\Model\PUFollowUQuery;
use Politizr\Model\PRBadgeQuery;
use Politizr\Model\PDDebateQuery;
use Politizr\Model\PDReactionQuery;
use Politizr\Model\PDDCommentQuery;
use Politizr\Model\PDRCommentQuery;
use Politizr\Model\PUserQuery;

/**
 * User's twig extension
 *
 * @author Lionel Bouzonville
 */
class PolitizrUserExtension extends \Twig_Extension
{
    private $sc;

    private $logger;
    private $router;
    private $templating;
    private $securityTokenStorage;
    private $securityAuthorizationChecker;

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
        $this->securityAuthorizationChecker =$serviceContainer->get('security.authorization_checker');

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
                'icon',
                array($this, 'icon'),
                array('is_safe' => array('html'))
            ),
            new \Twig_SimpleFilter(
                'photo',
                array($this, 'photo'),
                array('is_safe' => array('html'))
            ),
            new \Twig_SimpleFilter(
                'photoBack',
                array($this, 'photoBack'),
                array('is_safe' => array('html'))
            ),
            new \Twig_SimpleFilter(
                'followTags',
                array($this, 'followTags'),
                array('is_safe' => array('html'))
            ),
            new \Twig_SimpleFilter(
                'userTags',
                array($this, 'userTags'),
                array('is_safe' => array('html'))
            ),
            new \Twig_SimpleFilter(
                'linkSubscribeUser',
                array($this, 'linkSubscribeUser'),
                array('is_safe' => array('html'))
            ),
            new \Twig_SimpleFilter(
                'followersUser',
                array($this, 'followersUser'),
                array('is_safe' => array('html'))
            ),
            new \Twig_SimpleFilter(
                'linkedNotification',
                array($this, 'linkedNotification'),
                array('is_safe' => array('html'))
            ),
            new \Twig_SimpleFilter(
                'isAuthorizedToReact',
                array($this, 'isAuthorizedToReact'),
                array('is_safe' => array('html'))
            ),
            new \Twig_SimpleFilter(
                'isAuthorizedToReportAbuse',
                array($this, 'isAuthorizedToReportAbuse'),
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
            'isGrantedC'  => new \Twig_Function_Method($this, 'isGrantedC', array('is_safe' => array('html'))),
            'isGrantedE'  => new \Twig_Function_Method($this, 'isGrantedE', array('is_safe' => array('html'))),
        );
    }


    /* ######################################################################################################## */
    /*                                             FILTRES                                                      */
    /* ######################################################################################################## */

    /**
     * User's profile default icon
     *
     * @param PUser $user
     * @return html
     */
    public function icon(PUser $user)
    {
        // $this->logger->info('*** photo');
        // $this->logger->info('$user = '.print_r($user, true));

        return $this->photo($user, 'user_15', 'profil15_default.png');
    }

    /**
     * User's profile photo
     *
     * @param PUser $user
     * @return html
     */
    public function photo(PUser $user, $filterName = 'user_bio', $default = 'profil_default.png')
    {
        // $this->logger->info('*** photo');
        // $this->logger->info('$user = '.print_r($user, true));

        $path = 'bundles/politizrfront/images/'.$default;
        if ($user && $fileName = $user->getFileName()) {
            $path = 'uploads/users/'.$fileName;
        }

        // Construction du rendu du tag
        $html = $this->templating->render(
            'PolitizrFrontBundle:User:_photo.html.twig',
            array(
                'user' => $user,
                'path' => $path,
                'filterName' => $filterName,
                )
        );

        return $html;
    }

    /**
     * Load an <img> html tag with the back profile photo of user and apply it a filter.
     *
     * @param PUser $user
     * @return html
     */
    public function photoBack(PUser $user, $filterName = 'user_bio_back')
    {
        // $this->logger->info('*** photoBack');
        // $this->logger->info('$user = '.print_r($user, true));

        $path = '/bundles/politizrfront/images/default_user_back.jpg';
        if ($user && $fileName = $user->getBackFileName()) {
            $path = PathConstants::USER_UPLOAD_WEB_PATH.$fileName;
        }

        // Construction du rendu du tag
        $html = $this->templating->render(
            'PolitizrFrontBundle:User:_imageHeader.html.twig',
            array(
                'title' => $user->getFullName(),
                'path' => $path,
                'filterName' => $filterName,
            )
        );

        return $html;
    }


   /**
     * Display user's following tags
     *
     * @param PUser $user
     * @param integer $tagTypeId
     * @return string
     */
    public function followTags(PUser $user, $tagTypeId = null)
    {
        $this->logger->info('*** followTags');
        // $this->logger->info('$user = '.print_r($user, true));
        // $this->logger->info('$pTTagType = '.print_r($pTTagType, true));

        $tags = $user->getFollowTags($tagTypeId);
 
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
     * Display user's tags
     *
     * @param PUser $user
     * @param integer $tagTypeId
     * @return string
     */
    public function userTags(PUser $user, $tagTypeId = null)
    {
        $this->logger->info('*** userTags');
        // $this->logger->info('$uiser = '.print_r($uiser, true));
        // $this->logger->info('$pTTagType = '.print_r($pTTagType, true));

        $tags = $user->getTaggedTags($tagTypeId);

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
     * Follow / unfollow user
     *
     * @param PUser $user
     * @return string
     */
    public function linkSubscribeUser(PUser $user)
    {
        // $this->logger->info('*** linkSubscribeUser');
        // $this->logger->info('$debate = '.print_r($user, true));

        $follower = false;
        if ($this->user) {
            $follow = PUFollowUQuery::create()
                ->filterByPUserFollowerId($this->user->getId())
                ->filterByPUserId($user->getId())
                ->findOne();
            
            if ($follow) {
                $follower = true;
            }
        }

        // Construction du rendu du tag
        $html = $this->templating->render(
            'PolitizrFrontBundle:Follow:_subscribeUser.html.twig',
            array(
                'object' => $user,
                'follower' => $follower
            )
        );

        return $html;
    }

    /**
     *  Affiche le bloc des followers
     *
     * @param PUser $user
     * @return string
     */
    public function followersUser(PUser $user)
    {
        // $this->logger->info('*** followersUser');
        // $this->logger->info('$debate = '.print_r($user, true));

        $nbC = 0;
        $nbQ = 0;
        $followersC = array();
        $followersQ = array();

        $nbC = $user->countFollowersC();
        $nbQ = $user->countFollowersQ();
        $followersC = $user->getFollowersC();
        $followersQ = $user->getFollowersQ();

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


    /**
     * Notification HTML rendering
     * @todo add test on returning objects + throw InconsistentDataException (InconsistentDataEventException?)
     *
     * @param PUNotification $notification
     * @param boolean $absolute render absolute URL link
     * @return html
     */
    public function linkedNotification(PUNotification $notification, $absolute = false)
    {
        $this->logger->info('*** linkedNotification');
        $this->logger->info('$notification = '.print_r($notification, true));

        // Récupération de l'objet d'interaction
        $title = '';
        $url = '#';
        $commentDoc = '';
        $reactionParentTitle = null;
        $reactionParentUrl = null;
        switch ($notification->getPObjectName()) {
            case ObjectTypeConstants::TYPE_DEBATE:
                $subject = PDDebateQuery::create()->findPk($notification->getPObjectId());

                if ($subject) {
                    $title = $subject->getTitle();
                    $url = $this->router->generate('DebateDetail', array('slug' => $subject->getSlug()), $absolute);
                }
                break;
            case ObjectTypeConstants::TYPE_REACTION:
                $subject = PDReactionQuery::create()->findPk($notification->getPObjectId());
                $title = $subject->getTitle();
                $url = $this->router->generate('ReactionDetail', array('slug' => $subject->getSlug()), $absolute);
                
                if ($subject) {
                    // Document parent associée à la réaction
                    if ($subject->getTreeLevel() > 1) {
                        // Réaction parente
                        $parent = $subject->getParent();
                        $reactionParentTitle = $parent->getTitle();
                        $reactionParentUrl = $this->router->generate('ReactionDetail', array('slug' => $parent->getSlug()), $absolute);
                    } else {
                        // Débat
                        $debate = $subject->getDebate();
                        $reactionParentTitle = $debate->getTitle();
                        $reactionParentUrl = $this->router->generate('DebateDetail', array('slug' => $debate->getSlug()), $absolute);
                    }
                }

                break;
            case ObjectTypeConstants::TYPE_DEBATE_COMMENT:
                $subject = PDDCommentQuery::create()->findPk($notification->getPObjectId());
                
                if ($subject) {
                    $title = $subject->getDescription();
                    $document = $subject->getPDocument();
                    $commentDoc = $document->getTitle();
                    $url = $this->router->generate('DebateDetail', array('slug' => $document->getSlug()), $absolute);
                }
                break;
            case ObjectTypeConstants::TYPE_REACTION_COMMENT:
                $subject = PDRCommentQuery::create()->findPk($notification->getPObjectId());
                
                if ($subject) {
                    $title = $subject->getDescription();
                    $document = $subject->getPDocument();
                    $commentDoc = $document->getTitle();
                    $url = $this->router->generate('ReactionDetail', array('slug' => $document->getSlug()), $absolute);
                }
                break;
            case ObjectTypeConstants::TYPE_USER:
                $subject = PUserQuery::create()->findPk($notification->getPObjectId());

                if ($subject) {
                    $title = $subject->getFirstname().' '.$subject->getName();
                    $url = $this->router->generate('UserDetail', array('slug' => $subject->getSlug()), $absolute);
                }
                break;
            case ObjectTypeConstants::TYPE_BADGE:
                $subject = PRBadgeQuery::create()->findPk($notification->getPObjectId());

                if ($subject) {
                    $title = $subject->getTitle();
                    $url = $this->router->generate('ReputationC', array(), $absolute);
                    if ($this->isGrantedE()) {
                        $url = $this->router->generate('ReputationE', array(), $absolute);
                    }
                }
                
                break;
        }

        // Récupération de l'auteur de l'interaction
        $author = PUserQuery::create()->findPk($notification->getPAuthorUserId());

        $authorUrl = null;
        if ($author) {
            $authorUrl = $this->router->generate('UserDetail', array('slug' => $author->getSlug()), $absolute);
        }

        // Construction du rendu du tag
        $html = $this->templating->render(
            'PolitizrFrontBundle:Notification:_notification.html.twig',
            array(
                'notification' => $notification,
                'notificationId' => $notification->getPNotificationId(),
                'author' => $author,
                'authorUrl' => $authorUrl,
                'title' => $title,
                'url' => $url,
                'commentDoc' => $commentDoc,
                'reactionParentTitle' => $reactionParentTitle,
                'reactionParentUrl' => $reactionParentUrl,
            )
        );

        return $html;
    }

    /**
     * Test if the user can publish a reaction to the debate
     *
     * @param PUser $user
     * @param PDDebate $debate
     * @return boolean
     */
    public function isAuthorizedToReact(PUser $user, PDDebate $debate)
    {
        // $this->logger->info('*** isAuthorizedToReact');
        // $this->logger->info('$user = '.print_r($user, true));
        // $this->logger->info('$debate = '.print_r($debate, true));

        // elected profile can react
        if ($this->securityAuthorizationChecker->isGranted('ROLE_ELECTED')) {
            return true;
        }

        // author of the debate can react
        // + min reputation to reach
        $debateUser = $debate->getUser();
        $id = $user->getId();
        $score = $user->getReputationScore();
        if ($debateUser->getId() === $id && $score >= ReputationConstants::ACTION_REACTION_WRITE) {
            return true;
        }

        return false;
    }

    /**
     * Test if the user can report an abuse
     *
     * @param PUser $user
     * @return boolean
     */
    public function isAuthorizedToReportAbuse(PUser $user)
    {
        // $this->logger->info('*** isAuthorizedToReportAbuse');
        // $this->logger->info('$user = '.print_r($user, true));

        $score = $user->getReputationScore();
        if ($score >= ReputationConstants::ACTION_ABUSE_REPORT) {
            return true;
        }

        return false;
    }


    /* ######################################################################################################## */
    /*                                             FONCTIONS                                                    */
    /* ######################################################################################################## */

    /**
     *  Test l'autorisation d'un user citoyen et de l'état de son inscription
     *
     * @param $user         PUser à tester
     *
     * @return string
     */
    public function isGrantedC()
    {
        $this->logger->info('*** isGrantedC');

        if ($this->securityAuthorizationChecker->isGranted('ROLE_CITIZEN') &&
            $this->user &&
            $this->user->getOnline()) {
            return true;
        }

        return false;
    }


    /**
     * Test l'autorisation d'un user débatteur et de l'état de son inscription
     *
     * @param $user         PUser à tester
     *
     * @return string
     */
    public function isGrantedE()
    {
        $this->logger->info('*** isGrantedE');

        if ($this->securityAuthorizationChecker->isGranted('ROLE_ELECTED') &&
            $this->user &&
            $this->user->getPUStatusId() == UserConstants::STATUS_ACTIVED &&
            $this->user->getOnline()) {
            return true;
        }

        return false;
    }

    /**
     * Nom de l'extension.
     *
     * @return string
     */
    public function getName()
    {
        return 'p_e_user';
    }
}
