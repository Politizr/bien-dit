<?php
namespace Politizr\FrontBundle\Twig;

use Symfony\Component\Form\FormView;

use Politizr\Constant\NotificationConstants;
use Politizr\Constant\ReputationConstants;
use Politizr\Constant\ObjectTypeConstants;
use Politizr\Constant\UserConstants;
use Politizr\Constant\PathConstants;
use Politizr\Constant\TagConstants;
use Politizr\Constant\LocalizationConstants;

use Politizr\Model\PDocumentInterface;
use Politizr\Model\PDDebate;
use Politizr\Model\PUNotification;
use Politizr\Model\PUser;
use Politizr\Model\PEOperation;

use Politizr\Model\PUFollowUQuery;
use Politizr\Model\PUserQuery;
use Politizr\Model\PDReactionQuery;
use Politizr\Model\PEOperationQuery;

use Politizr\FrontBundle\Form\Type\PUserLocalizationType;

/**
 * User's twig extension
 *
 * @author Lionel Bouzonville
 */
class PolitizrUserExtension extends \Twig_Extension
{
    private $securityTokenStorage;
    private $securityAuthorizationChecker;

    private $router;
    private $templating;

    private $documentService;
    
    private $formFactory;

    private $globalTools;

    private $logger;

    /**
     * @security.token_storage
     * @security.authorization_checker
     * @router
     * @templating
     * @politizr.functional.document
     * @form.factory
     * @politizr.tools.global
     * @logger
     */
    public function __construct(
        $securityTokenStorage,
        $securityAuthorizationChecker,
        $router,
        $templating,
        $documentService,
        $formFactory,
        $globalTools,
        $logger
    ) {
        $this->securityTokenStorage = $securityTokenStorage;
        $this->securityAuthorizationChecker =$securityAuthorizationChecker;

        $this->router = $router;
        $this->templating = $templating;

        $this->documentService = $documentService;

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
                'photo',
                array($this, 'photo'),
                array('is_safe' => array('html'))
            ),
            new \Twig_SimpleFilter(
                'typeGender',
                array($this, 'typeGender'),
                array('is_safe' => array('html'))
            ),
            new \Twig_SimpleFilter(
                'nbFollowers',
                array($this, 'nbFollowers'),
                array('is_safe' => array('html'))
            ),
            new \Twig_SimpleFilter(
                'nbSubscribers',
                array($this, 'nbSubscribers'),
                array('is_safe' => array('html'))
            ),
            new \Twig_SimpleFilter(
                'nbPublications',
                array($this, 'nbPublications'),
                array('is_safe' => array('html'))
            ),
            new \Twig_SimpleFilter(
                'userTags',
                array($this, 'userTags'),
                array('is_safe' => array('html'))
            ),
            new \Twig_SimpleFilter(
                'userPublicationsTags',
                array($this, 'userPublicationsTags'),
                array('is_safe' => array('html'))
            ),
            new \Twig_SimpleFilter(
                'userOperation',
                array($this, 'userOperation'),
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
            new \Twig_SimpleFilter(
                'isAuthorizedToAskOperation',
                array($this, 'isAuthorizedToAskOperation'),
                array('is_safe' => array('html'))
            ),
            new \Twig_SimpleFilter(
                'localization',
                array($this, 'localization'),
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
            'isGrantedC'  => new \Twig_SimpleFunction(
                'isGrantedC',
                array($this, 'isGrantedC'),
                array('is_safe' => array('html'))
            ),
            'isGrantedE'  => new \Twig_SimpleFunction(
                'isGrantedE',
                array($this, 'isGrantedE'),
                array('is_safe' => array('html'))
            ),
            'profileSuffix'  => new \Twig_SimpleFunction(
                'profileSuffix',
                array($this, 'profileSuffix'),
                array('is_safe' => array('html'))
            ),
            'fillLocalization'  => new \Twig_SimpleFunction(
                'fillLocalization',
                array($this, 'fillLocalization'),
                array('is_safe' => array('html'))
            ),
        );
    }


    /* ######################################################################################################## */
    /*                                             FILTRES                                                      */
    /* ######################################################################################################## */

    /**
     * User's profile photo
     *
     * @param PUser $user
     * @param string $filterName
     * @param boolean $withLink
     * @param boolean $withBubble
     * @param boolean $email
     * @param string $default
     * @return html
     */
    public function photo(
        PUser $user,
        $filterName = 'user_bio',
        $withLink = true,
        $withBubble = false,
        $email = false,
        $default = 'default_avatar.jpg'
    ) {
        // $this->logger->info('*** photo');
        // $this->logger->info('$user = '.print_r($user, true));

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
            $url = $this->router->generate('UserDetail', array('slug' => $user->getSlug()));
        }

        // Construction du rendu du tag
        $html = $this->templating->render(
            'PolitizrFrontBundle:User:'.$template,
            array(
                'user' => $user,
                'path' => $path,
                'url' => $url,
                'withBubble' => $withBubble,
                'filterName' => $filterName,
                )
        );

        return $html;
    }

    /**
     * User's meta title
     *
     * @param PUser $user
     * @return html
     */
    public function typeGender(PUser $user)
    {
        // $this->logger->info('*** typeGender');
        // $this->logger->info('$user = '.print_r($user, true));

        if ($user->isQualified()) {
            $html = 'Élu';
            if ($user->getGender() == 'Madame') {
                $html .= 'e';
            }
        } else {
            $html = 'Citoyen';
            if ($user->getGender() == 'Madame') {
                $html .= 'ne';
            }
        }

        return $html;
    }

    /**
     * User's number of followers
     *
     * @param PUser $user
     * @return html
     */
    public function nbFollowers(PUser $user)
    {
        // $this->logger->info('*** nbFollowers');
        // $this->logger->info('$user = '.print_r($user, true));

        $nbFollowers = $user->countFollowers();

        if (0 === $nbFollowers) {
            $html = 'Aucun abonné';
        } elseif (1 === $nbFollowers) {
            $html = '1 abonné';
        } else {
            $html = $this->globalTools->readeableNumber($nbFollowers).' abonnés';
        }

        return $html;
    }

    /**
     * User's number of subscribers
     *
     * @param PUser $user
     * @return html
     */
    public function nbSubscribers(PUser $user)
    {
        // $this->logger->info('*** nbSubscribers');
        // $this->logger->info('$user = '.print_r($user, true));

        $nbSubscribers = $user->countSubscribers();

        if (0 === $nbSubscribers) {
            $html = 'Aucun abonnement';
        } elseif (1 === $nbSubscribers) {
            $html = '1 abonnement';
        } else {
            $html = $this->globalTools->readeableNumber($nbSubscribers).' abonnements';
        }

        return $html;
    }

    /**
     * User's number of publications
     *
     * @param PUser $user
     * @return html
     */
    public function nbPublications(PUser $user)
    {
        // $this->logger->info('*** nbPublications');
        // $this->logger->info('$user = '.print_r($user, true));

        $nbPublications = $user->countPublications();

        if (0 === $nbPublications) {
            $html = 'Aucune publication';
        } elseif (1 === $nbPublications) {
            $html = '1 publication';
        } else {
            $html = $this->globalTools->readeableNumber($nbPublications).' publications';
        }

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
        // $this->logger->info('*** userTags');
        // $this->logger->info('$user = '.print_r($user, true));
        // $this->logger->info('$pTTagType = '.print_r($pTTagType, true));

        // get current user
        $currentUser = $this->securityTokenStorage->getToken()->getUser();
        
        // get hidden tags for current user only
        if ($currentUser && $currentUser->getId() == $user->getId()) {
            $tags = $user->getTags($tagTypeId, null);
        } else {
            $tags = $user->getTags($tagTypeId);
        }

        // Construction du rendu du tag
        $html = $this->templating->render(
            'PolitizrFrontBundle:Tag:_list.html.twig',
            array(
                'tags' => $tags,
            )
        );

        return $html;
    }

   /**
     * Display user's publication tags
     *
     * @param PUser $user
     * @param integer $tagTypeId
     * @return string
     */
    public function userPublicationsTags(PUser $user, $tagTypeId = null)
    {
        // $this->logger->info('*** userPublicationsTags');
        // $this->logger->info('$user = '.print_r($user, true));
        // $this->logger->info('$pTTagType = '.print_r($pTTagType, true));

        // get current user
        $currentUser = $this->securityTokenStorage->getToken()->getUser();
        
        $tags = array();

        $debates = $user->getDebates();
        foreach ($debates as $debate) {
            $documentTags = $debate->getIndexedArrayTags($tagTypeId);
            $tags = array_replace($tags, $documentTags);
        }

        $reactions = $user->getReactions();
        foreach ($reactions as $reaction) {
            $documentTags = $reaction->getIndexedArrayTags($tagTypeId);
            $tags = array_replace($tags, $documentTags);
        }

        $comments = $user->getDComments();
        foreach ($comments as $comment) {
            $document = $comment->getPDocument();
            $documentTags = $document->getIndexedArrayTags($tagTypeId);
            $tags = array_replace($tags, $documentTags);
        }

        $comments = $user->getRComments();
        foreach ($comments as $comment) {
            $document = $comment->getPDocument();
            $documentTags = $document->getIndexedArrayTags($tagTypeId);
            $tags = array_replace($tags, $documentTags);
        }

        // Construction du rendu du tag
        $html = $this->templating->render(
            'PolitizrFrontBundle:Tag:_filterList.html.twig',
            array(
                'tags' => $tags,
            )
        );

        return $html;
    }

   /**
     * Display user's operation
     *
     * @param PUser $user
     * @return string
     */
    public function userOperation(PUser $user)
    {
        // $this->logger->info('*** userOperation');
        // $this->logger->info('$user = '.print_r($user, true));

        // get op for user
        $operation = PEOperationQuery::create()
            ->filterByOnline(true)
            ->filterByPUserId($user->getId())
            ->findOne();

        if (!$operation) {
            return null;
        }

        // Construction du rendu du tag            
        $html = $this->templating->render(
            'PolitizrFrontBundle:User:_opBanner.html.twig',
            array(
                'operation' => $operation,
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
    public function linkSubscribeUser(PUser $followUser)
    {
        // $this->logger->info('*** linkSubscribeUser');
        // $this->logger->info('$debate = '.print_r($user, true));

        // get current user
        $user = $this->securityTokenStorage->getToken()->getUser();
        if (is_string($user)) {
            $user = null;
        }

        $follower = false;
        if ($user && $followUser) {
            $follow = PUFollowUQuery::create()
                ->filterByPUserFollowerId($user->getId())
                ->filterByPUserId($followUser->getId())
                ->findOne();
            
            if ($follow) {
                $follower = true;
            }
        }

        // Construction du rendu du tag
        $html = $this->templating->render(
            'PolitizrFrontBundle:Follow:_subscribeUserLink.html.twig',
            array(
                'object' => $followUser,
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
     *
     * @param PUNotification $notification
     * @param int $type NotificationConstants
     * @return html
     */
    public function linkedNotification(PUNotification $notification, $type = NotificationConstants::TYPE_SCREEN)
    {
        // $this->logger->info('*** linkedNotification');
        // $this->logger->info('$notification = '.print_r($notification, true));
        // $this->logger->info('$type = '.print_r($type, true));

        // absolute URL for email notif
        $absolute = false;
        if (NotificationConstants::TYPE_EMAIL === $type) {
            $absolute = true;
        }

        // Update attributes depending of context
        $attr = $this->documentService->computeDocumentContextAttributes(
            $notification->getPObjectName(),
            $notification->getPObjectId()
        );

        $subject = $attr['subject'];
        $title = $attr['title'];
        $url = $attr['url'];
        $document = $attr['document'];
        $documentUrl = $attr['documentUrl'];

        // Récupération de l'auteur de l'interaction
        $author = PUserQuery::create()->findPk($notification->getPAuthorUserId());

        $authorUrl = null;
        if ($author) {
            $authorUrl = $this->router->generate('UserDetail', array('slug' => $author->getSlug()), $absolute);
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
        } elseif (NotificationConstants::TYPE_EMAIL_SUBJECT === $type) {
            $html = $this->templating->render(
                'PolitizrFrontBundle:Notification:_notificationMessageSubject.html.twig',
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
        } else {
            $html = $this->templating->render(
                'PolitizrFrontBundle:Notification:_notificationScreen.html.twig',
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
     * Test if the user has role to react to the document
     *
     * @param PUser $user
     * @param PDDebate $document
     * @return boolean
     */
    public function isAuthorizedToReact(PUser $user, PDocumentInterface $document)
    {
        // $this->logger->info('*** isAuthorizedToReact');
        // $this->logger->info('$user = '.print_r($user, true));
        // $this->logger->info('$document = '.print_r($document, true));

        // elected profile can react if document ha no private tags
        if (!$document->isWithPrivateTag() && $this->securityAuthorizationChecker->isGranted('ROLE_ELECTED')) {
            return true;
        }

        // owner of private tag can react
        if ($document->isWithPrivateTag()) {
            $tags = $document->getTags(TagConstants::TAG_TYPE_PRIVATE);
            foreach ($tags as $tag) {
                $tagOwner = $tag->getPOwner();
                if ($tagOwner && $tagOwner->getId() == $user->getId()) {
                    return true;
                }
            }
        }

        // author of the debate can react
        // + min reputation to reach
        $score = $user->getReputationScore();
        if ($document->isDebateOwner($user->getId()) && $score >= ReputationConstants::ACTION_REACTION_WRITE) {
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
     * @param string $uuid Associated document's uuid
     * @param string $type Associated document's type
     * @return string
     */
    public function isAuthorizedToNewComment(PUser $user, FormView $formComment, $uuid, $type)
    {
        // $this->logger->info('*** isAuthorizedToNewComment');
        // $this->logger->info('$user = '.print_r($user, true));
        // $this->logger->info('$uuid = '.print_r($uuid, true));

        $score = $user->getReputationScore();
        if ($score >= ReputationConstants::ACTION_COMMENT_WRITE) {
            $html = $this->templating->render(
                'PolitizrFrontBundle:Comment:_form.html.twig',
                array(
                    'formComment' => $formComment,
                    'uuid' => $uuid,
                    'type' => $type,
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
     * Display the publish link - or not - depending of the reputation score and if elected user is validated
     *
     * @param PUser $user
     * @param string $uuid
     * @return string
     */
    public function isAuthorizedToPublishDebate(PUser $user, $uuid)
    {
        // $this->logger->info('*** isAuthorizedToPublishDebate');
        // $this->logger->info('$user = '.print_r($user, true));

        $score = $user->getReputationScore();

        if ($score >= ReputationConstants::ACTION_DEBATE_WRITE) {
            $html = $this->templating->render(
                'PolitizrFrontBundle:Debate:_publishLink.html.twig',
                array(
                    'uuid' => $uuid,
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
     * @param string $uuid
     * @return string
     */
    public function isAuthorizedToPublishReaction(PUser $user, $uuid)
    {
        // $this->logger->info('*** isAuthorizedToPublishReaction');
        // $this->logger->info('$user = '.print_r($user, true));

        $score = $user->getReputationScore();
        
        if ($this->securityAuthorizationChecker->isGranted('ROLE_ELECTED') && !$user->isValidated()) {
            // case: own subject > certification not needed
            $reaction = PDReactionQuery::create()->filterByUuid($uuid)->findOne();
            $debate = $reaction->getDebate();
            if ($debate) {
                $debateUser = $debate->getPUser();
                if ($debateUser && $debateUser->getId() == $user->getId()) {
                    if ($score >= ReputationConstants::ACTION_REACTION_WRITE) {
                        $html = $this->templating->render(
                            'PolitizrFrontBundle:Reaction:_publishLink.html.twig',
                            array(
                                'uuid' => $uuid,
                            )
                        );
                    } else {
                        $html = $this->templating->render(
                            'PolitizrFrontBundle:Reputation:_cannotPublishReaction.html.twig',
                            array(
                                'case' => ReputationConstants::SCORE_NOT_REACHED,
                                'score' => $score,
                            )
                        );
                    }

                    return $html;
                }
            }

            // case: other subject > certification needed
            $html = $this->templating->render(
                'PolitizrFrontBundle:Reputation:_cannotPublishReaction.html.twig',
                array(
                    'case' => ReputationConstants::USER_ELECTED_NOT_VALIDATED,
                    'score' => $score,
                )
            );
        } elseif ($score >= ReputationConstants::ACTION_REACTION_WRITE) {
            $html = $this->templating->render(
                'PolitizrFrontBundle:Reaction:_publishLink.html.twig',
                array(
                    'uuid' => $uuid,
                )
            );
        } else {
            $html = $this->templating->render(
                'PolitizrFrontBundle:Reputation:_cannotPublishReaction.html.twig',
                array(
                    'case' => ReputationConstants::SCORE_NOT_REACHED,
                    'score' => $score,
                )
            );
        }

        return $html;
    }

    /**
     * Check if user is authorized to create a new subject for an operation
     *
     * @param PUser $user
     * @param PEOperation $operation
     * @return boolean
     */
    public function isAuthorizedToAskOperation(PUser $user, PEOperation $operation)
    {
        // $this->logger->info('*** isAuthorizedToAskOperation');
        // $this->logger->info('$user = '.print_r($user, true));
        // $this->logger->info('$operation = '.print_r($operation, true));
        
        if (!$operation->getGeoScoped()) {
            return true;
        } else {
            $cities = $operation->getPLCities()->toKeyValue('Id', 'Title');
            if (array_key_exists($user->getPLCityId(), $cities)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Display user's localization
     *
     * @param PUser $user
     * @return string
     */
    public function localization(PUser $user)
    {
        // $this->logger->info('*** localization');
        // $this->logger->info('$user = '.print_r($user, true));

        $department = null;
        $city = $user->getPLCity();
        if ($city) {
            $department = $city->getPLDepartment();
        }

        $outOfFrance = false;
        if ($department && in_array($department->getId(), LocalizationConstants::getOutOfFranceDepartmentIds())) {
            $outOfFrance = true;
        }

        $html = $this->templating->render(
            'PolitizrFrontBundle:User:_localization.html.twig',
            array(
                'city' => $city,
                'department' => $department,
                'outOfFrance' => $outOfFrance,
            )
        );

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
        // $this->logger->info('*** isGrantedC');

        // get current user
        $user = $this->securityTokenStorage->getToken()->getUser();
        
        if ($this->securityAuthorizationChecker->isGranted('ROLE_CITIZEN') &&
            $user &&
            $user->getOnline()) {
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
        // $this->logger->info('*** isGrantedE');

        // get current user
        $user = $this->securityTokenStorage->getToken()->getUser();
        
        if ($this->securityAuthorizationChecker->isGranted('ROLE_ELECTED') &&
            $user &&
            // $user->getPUStatusId() == UserConstants::STATUS_ACTIVED &&
            $user->getOnline()) {
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
        // $this->logger->info('*** profileSuffix');

        return $this->globalTools->computeProfileSuffix();
    }


    /**
     * Display an alert box to fill localization if user doesn't have already
     *
     * @param PUser $user
     * @return string
     */
    public function fillLocalization()
    {
        // $this->logger->info('*** fillLocalization');

        // get current user
        $user = $this->securityTokenStorage->getToken()->getUser();
        
        if (!$user->getPLCityId()) {
            $form = $this->formFactory->create(new PUserLocalizationType($user), $user);
            $html = $this->templating->render(
                'PolitizrFrontBundle:User:_alertFillLocalization.html.twig',
                array(
                    'form' => $form->createView(),
                )
            );

            return $html;
        }

        return;
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
