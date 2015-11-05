<?php
namespace Politizr\FrontBundle\Twig;

use Symfony\Component\Form\FormView;

use Politizr\Constant\NotificationConstants;
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
        $this->securityAuthorizationChecker =$serviceContainer->get('security.authorization_checker');

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
            new \Twig_SimpleFilter(
                'isAuthorizedToNewComment',
                array($this, 'isAuthorizedToNewComment'),
                array('is_safe' => array('html'))
            ),
            new \Twig_SimpleFilter(
                'isAuthorizedToPublishDebate',
                array($this, 'isAuthorizedToPublishDebate'),
                array('is_safe' => array('html'))
            ),
            new \Twig_SimpleFilter(
                'isAuthorizedToPublishReaction',
                array($this, 'isAuthorizedToPublishReaction'),
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
            'profileSuffix'  => new \Twig_Function_Method($this, 'profileSuffix', array('is_safe' => array('html'))),
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

        return $this->photo($user, 'user_15', false, false, 'profil15_default.png');
    }

    /**
     * User's profile photo
     *
     * @param PUser $user
     * @param string $filterName
     * @param boolean $withLink
     * @param boolean $email
     * @param string $default
     * @return html
     */
    public function photo(PUser $user, $filterName = 'user_bio', $withLink = true, $email = false, $default = 'profil_default.png')
    {
        // $this->logger->info('*** photo');
        // $this->logger->info('$user = '.print_r($user, true));

        $profileSuffix = $this->globalTools->computeProfileSuffix();

        $path = 'bundles/politizrfront/images/'.$default;
        if ($user && $fileName = $user->getFileName()) {
            $path = 'uploads/users/'.$fileName;
        }

        $template= '_photo.html.twig';
        if ($email) {
            $template = '_photoEmail.html.twig';
        }

        // URL detail
        $url = null;
        if ($withLink && $user) {
            $url = $this->router->generate('UserDetail'.$profileSuffix, array('slug' => $user->getSlug()));
        }

        // Construction du rendu du tag
        $html = $this->templating->render(
            'PolitizrFrontBundle:User:'.$template,
            array(
                'user' => $user,
                'path' => $path,
                'url' => $url,
                'filterName' => $filterName,
                )
        );

        return $html;
    }

    /**
     * Load an <img> html tag with the back profile photo of user and apply it a filter.
     *
     * @param PUser $user
     * @param string $filterName
     * @param boolean $withShadow
     * @return html
     */
    public function photoBack(PUser $user, $filterName = 'user_bio_back', $withShadow = true)
    {
        // $this->logger->info('*** photoBack');
        // $this->logger->info('$user = '.print_r($user, true));

        $path = '/bundles/politizrfront/images/default_profile.jpg';
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
                'withShadow' => $withShadow,
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
     * @param int $type NotificationConstants
     * @return html
     */
    public function linkedNotification(PUNotification $notification, $type = NotificationConstants::TYPE_SCREEN)
    {
        $this->logger->info('*** linkedNotification');
        $this->logger->info('$notification = '.print_r($notification, true));
        $this->logger->info('$type = '.print_r($type, true));

        $profileSuffix = $this->globalTools->computeProfileSuffix();

        // absolute URL for email notif
        $absolute = false;
        if (NotificationConstants::TYPE_EMAIL === $type) {
            $absolute = true;
        }

        // Récupération de l'objet d'interaction
        $title = '';
        $url = '#';
        $document = null;
        $documentUrl = '#';
        switch ($notification->getPObjectName()) {
            case ObjectTypeConstants::TYPE_DEBATE:
                $subject = PDDebateQuery::create()->findPk($notification->getPObjectId());

                if ($subject) {
                    $title = $subject->getTitle();
                    $url = $this->router->generate('DebateDetail'.$profileSuffix, array('slug' => $subject->getSlug()), $absolute);
                }
                break;
            case ObjectTypeConstants::TYPE_REACTION:
                $subject = PDReactionQuery::create()->findPk($notification->getPObjectId());
                
                if ($subject) {
                    $title = $subject->getTitle();
                    $url = $this->router->generate('ReactionDetail'.$profileSuffix, array('slug' => $subject->getSlug()), $absolute);

                    // Document parent associée à la réaction
                    if ($subject->getTreeLevel() > 1) {
                        // Réaction parente
                        $document = $subject->getParent();
                        $documentUrl = $this->router->generate('ReactionDetail'.$profileSuffix, array('slug' => $document->getSlug()), $absolute);
                    } else {
                        // Débat
                        $document = $subject->getDebate();
                        $documentUrl = $this->router->generate('DebateDetail'.$profileSuffix, array('slug' => $document->getSlug()), $absolute);
                    }
                }

                break;
            case ObjectTypeConstants::TYPE_DEBATE_COMMENT:
                $subject = PDDCommentQuery::create()->findPk($notification->getPObjectId());
                
                if ($subject) {
                    $document = $subject->getPDocument();
                    $title = $subject->getDescription();
                    $url = $this->router->generate('DebateDetail'.$profileSuffix, array('slug' => $document->getSlug()), $absolute) . '#p-' . $subject->getParagraphNo();
                    $documentUrl = $this->router->generate('DebateDetail'.$profileSuffix, array('slug' => $document->getSlug()), $absolute);
                }
                break;
            case ObjectTypeConstants::TYPE_REACTION_COMMENT:
                $subject = PDRCommentQuery::create()->findPk($notification->getPObjectId());
                
                if ($subject) {
                    $document = $subject->getPDocument();
                    $title = $subject->getDescription();
                    $url = $this->router->generate('ReactionDetail'.$profileSuffix, array('slug' => $document->getSlug()), $absolute) . '#p-' . $subject->getParagraphNo();
                    $documentUrl = $this->router->generate('ReactionDetail'.$profileSuffix, array('slug' => $document->getSlug()), $absolute);
                }
                break;
            case ObjectTypeConstants::TYPE_USER:
                $subject = PUserQuery::create()->findPk($notification->getPObjectId());

                if ($subject) {
                    $title = $subject->getFirstname().' '.$subject->getName();
                    $url = $this->router->generate('UserDetail'.$profileSuffix, array('slug' => $subject->getSlug()), $absolute);
                }
                break;
            case ObjectTypeConstants::TYPE_BADGE:
                $subject = PRBadgeQuery::create()->findPk($notification->getPObjectId());

                if ($subject) {
                    $title = $subject->getTitle();
                }
                
                break;
        }

        // Récupération de l'auteur de l'interaction
        $author = PUserQuery::create()->findPk($notification->getPAuthorUserId());

        $authorUrl = null;
        if ($author) {
            $authorUrl = $this->router->generate('UserDetail'.$profileSuffix, array('slug' => $author->getSlug()), $absolute);
        }

        // Screen / Email rendering
        if (NotificationConstants::TYPE_EMAIL === $type || NotificationConstants::TYPE_EMAIL_TXT === $type) {
            $html = $this->templating->render(
                'PolitizrFrontBundle:Notification:_notificationMessage.html.twig',
                array(
                    'type' => $type,
                    'notification' => $notification,
                    'notificationId' => $notification->getPNotificationId(),
                    'subject' => $subject,
                    'title' => $title,
                    'url' => $url,
                    'author' => $author,
                    'authorUrl' => $authorUrl,
                    'document' => $document,
                    'documentUrl' => $documentUrl,
                )
            );
        } else {
            $html = $this->templating->render(
                'PolitizrFrontBundle:Notification:_notification.html.twig',
                array(
                    'notification' => $notification,
                    'notificationId' => $notification->getPNotificationId(),
                    'subject' => $subject,
                    'title' => $title,
                    'url' => $url,
                    'author' => $author,
                    'authorUrl' => $authorUrl,
                    'document' => $document,
                    'documentUrl' => $documentUrl,
                )
            );
        }

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

    /**
     * Display the new comment form - or not - depending of the reputation score
     *
     * @param PUser $user
     * @param FormView $formComment
     * @return string
     */
    public function isAuthorizedToNewComment(PUser $user, FormView $formComment)
    {
        // $this->logger->info('*** isAuthorizedToNewComment');
        // $this->logger->info('$user = '.print_r($user, true));

        $score = $user->getReputationScore();
        if ($score >= ReputationConstants::ACTION_COMMENT_WRITE) {
            $html = $this->templating->render(
                'PolitizrFrontBundle:Comment:_new.html.twig',
                array(
                    'formComment' => $formComment,
                )
            );
        } else {
            $html = $this->templating->render(
                'PolitizrFrontBundle:Reputation:_cannotComment.html.twig',
                array(
                    'score' => $score,
                )
            );
        }

        return $html;
    }

    /**
     * Display the publish link - or not - depending of the reputation score
     *
     * @param PUser $user
     * @param int $debateId
     * @return string
     */
    public function isAuthorizedToPublishDebate(PUser $user, $debateId)
    {
        // $this->logger->info('*** isAuthorizedToPublishDebate');
        // $this->logger->info('$user = '.print_r($user, true));

        $score = $user->getReputationScore();
        if ($score >= ReputationConstants::ACTION_DEBATE_WRITE) {
            $html = $this->templating->render(
                'PolitizrFrontBundle:Debate:_publishLink.html.twig',
                array(
                    'debateId' => $debateId,
                )
            );
        } else {
            $html = $this->templating->render(
                'PolitizrFrontBundle:Reputation:_cannotPublishDebate.html.twig',
                array(
                    'score' => $score,
                )
            );
        }

        return $html;
    }

    /**
     * Display the publish link - or not - depending of the reputation score
     *
     * @param PUser $user
     * @param int $reactionId
     * @return string
     */
    public function isAuthorizedToPublishReaction(PUser $user, $reactionId)
    {
        // $this->logger->info('*** isAuthorizedToPublishReaction');
        // $this->logger->info('$user = '.print_r($user, true));

        $score = $user->getReputationScore();
        if ($score >= ReputationConstants::ACTION_REACTION_WRITE) {
            $html = $this->templating->render(
                'PolitizrFrontBundle:Reaction:_publishLink.html.twig',
                array(
                    'reactionId' => $reactionId,
                )
            );
        } else {
            $html = $this->templating->render(
                'PolitizrFrontBundle:Reputation:_cannotPublishReaction.html.twig',
                array(
                    'score' => $score,
                )
            );
        }

        return $html;
    }

    /* ######################################################################################################## */
    /*                                             FONCTIONS                                                    */
    /* ######################################################################################################## */

    /**
     * Test current user granted ROLE_ELECTED
     *
     * @return boolean
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
     * Test current user granted ROLE_ELECTED
     *
     * @return boolean
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
     * Get suffix profile for routing / profiles
     *
     * @return string
     */
    public function profileSuffix()
    {
        $this->logger->info('*** profileSuffix');

        return $this->globalTools->computeProfileSuffix();
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
