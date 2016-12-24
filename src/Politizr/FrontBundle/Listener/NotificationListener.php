<?php

namespace Politizr\FrontBundle\Listener;

use Symfony\Component\EventDispatcher\GenericEvent;

use Politizr\Exception\InconsistentDataException;

use Politizr\Constant\NotificationConstants;
use Politizr\Constant\ObjectTypeConstants;
use Politizr\Constant\UserConstants;

use Politizr\Model\PDocumentInterface;
use Politizr\Model\PUNotification;

use Politizr\Model\PUserQuery;
use Politizr\Model\PRBadgeQuery;

/**
 * Notification listener
 *
 * @author Lionel Bouzonville
 */
class NotificationListener
{

    protected $eventDispatcher;
    protected $logger;

    /**
     *
     * @param @event_dispatcher
     * @param @logger
     */
    public function __construct(
        $eventDispatcher,
        $logger
    ) {
        $this->eventDispatcher = $eventDispatcher;
        
        $this->logger = $logger;
    }

    /**
     * Attribution d'une note positive sur un document ou un commentaire.
     *
     * Notifications associées à gérer:
     * - Note positive sur un de vos documents ou commentaires
     *
     * @param GenericEvent
     */
    public function onNNotePos(GenericEvent $event)
    {
        // $this->logger->info('*** onNNotePos');

        $subject = $event->getSubject();
        $authorUserId = $event->getArgument('author_user_id');

        $objectName = get_class($subject);
        $objectId = $subject->getId();

        // Document associé
        $targetUserId = $subject->getPUserId();

        switch($objectName) {
            case 'Politizr\Model\PDDebate':
            case 'Politizr\Model\PDReaction':
                $pNotificationId = NotificationConstants::ID_D_NOTE_POS;
                break;
            case 'Politizr\Model\PDDComment':
            case 'Politizr\Model\PDRComment':
                $pNotificationId = NotificationConstants::ID_D_C_NOTE_POS;
                break;
            default:
                throw new InconsistentDataException(sprintf('Object name %s not managed', $objectName));
        }

        $puNotification = $this->insertPUNotification($targetUserId, $authorUserId, $pNotificationId, $objectName, $objectId);

        // Alerte email
        $event = new GenericEvent($puNotification);
        $dispatcher =  $this->eventDispatcher->dispatch('n_e_check', $event);
    }

    /**
     * Attribution d'une note négative sur un document ou un commentaire.
     *
     * Notifications associées à gérer:
     * - Note négative sur un de vos documents ou commentaires
     *
     * @param GenericEvent
     */
    public function onNNoteNeg(GenericEvent $event)
    {
        // $this->logger->info('*** onNNoteNeg');

        $subject = $event->getSubject();
        $authorUserId = $event->getArgument('author_user_id');

        $objectName = get_class($subject);
        $objectId = $subject->getId();

        // Document associé
        $targetUserId = $subject->getPUserId();

        switch ($objectName) {
            case 'Politizr\Model\PDDebate':
            case 'Politizr\Model\PDReaction':
                $pNotificationId = NotificationConstants::ID_D_NOTE_NEG;
                break;
            case 'Politizr\Model\PDDComment':
            case 'Politizr\Model\PDRComment':
                $pNotificationId = NotificationConstants::ID_D_C_NOTE_NEG;
                break;
            default:
                throw new InconsistentDataException(sprintf('Object name %s not managed', $objectName));
        }

        $puNotification = $this->insertPUNotification($targetUserId, $authorUserId, $pNotificationId, $objectName, $objectId);

        // Alerte email
        $event = new GenericEvent($puNotification);
        $dispatcher =  $this->eventDispatcher->dispatch('n_e_check', $event);
    }

    /**
     * Publication d'un débat.
     *
     * Notifications associées à gérer:
     * - Un débat ou une réaction a été publié par un utilisateur suivi
     * - Un débat ou une réaction contenant une thématique suivi a été publié
     * - Un débat sur ma ville a été publié
     * - Un débat sur mon département (ou une ville du département) a été publié
     * - Un débat localisé sur ma région (ou un département de la région ou une ville de la région) a été publié
     *
     * @param GenericEvent
     */
    public function onNDebatePublish(GenericEvent $event)
    {
        // $this->logger->info('*** onNDebatePublish');

        $debate = $event->getSubject();
        $authorUserId = $event->getArgument('author_user_id');
        $objectName = get_class($debate);
        $objectId = $debate->getId();

        // retrieve user
        $authorUser = PUserQuery::create()->findPk($authorUserId);

        // User ids array to manage notification exclusion
        // exclude users who had the previous notification
        // exclude users who already have a tag notification (case multi tag match user)
        $usersIds = array();
        $usersIds[] = $authorUserId;
        
        // get debate's user followers
        $users = $authorUser->getFollowers();
        foreach ($users as $user) {
            $usersIds[] = $user->getId();

            $pNotificationId = NotificationConstants::ID_S_U_DEBATE_PUBLISH;
            $puNotification = $this->insertPUNotification($user->getId(), $authorUserId, $pNotificationId, $objectName, $objectId);

            // email
            $event = new GenericEvent($puNotification);
            $dispatcher = $this->eventDispatcher->dispatch('n_e_check', $event);
        }

        // get debate's tags
        $tags = $debate->getTags();
        $notInUsersIdFromTag = array();
        foreach ($tags as $tag) {
            // get tag's users
            $query = PUserQuery::create()->filterById($usersIds, " NOT IN ");
            $users = $tag->getUsers(true, $query);

            foreach ($users as $user) {
                $usersIds[] = $user->getId();

                $pNotificationId = NotificationConstants::ID_S_T_DOCUMENT;
                $puNotification = $this->insertPUNotification($user->getId(), $authorUserId, $pNotificationId, $objectName, $objectId);

                // email
                $event = new GenericEvent($puNotification);
                $dispatcher = $this->eventDispatcher->dispatch('n_e_check', $event);
            }
        }

        // localization
        $this->locDocNotificationsManagement($debate, $usersIds, $authorUserId, $objectName, $objectId);
    }

    /**
     * Publication d'une réaction.
     *
     * Notifications associées à gérer:
     * - Une réaction a été publiée sur un de vos débats / une de vos réactions
     * - Une réaction a été publié par un auteur suivi
     * - Une réaction a été publié sur un débat suivi
     * - Un débat ou une réaction contenant une thématique suivi a été publié
     * - Une réaction sur ma ville a été publié
     * - Une réaction sur mon département (ou une ville du département) a été publié
     * - Une réaction localisé sur ma région (ou un département de la région ou une ville de la région) a été publié
     *
     * @param GenericEvent
     */
    public function onNReactionPublish(GenericEvent $event)
    {
        // $this->logger->info('*** onNDebateReactionPublish');
        
        $reaction = $event->getSubject();
        $authorUserId = $event->getArgument('author_user_id');
        $objectName = get_class($reaction);
        $objectId = $reaction->getId();

        // retrieve reaction's debate
        $debate = $reaction->getPDDebate();
        $debateUserId = $debate->getPUserId();
        $debateUser = $debate->getPUser();
        $pNotificationId = NotificationConstants::ID_D_D_REACTION_PUBLISH;

        // User ids array to manage notification exclusion
        $usersIds = array();
        $usersIds[] = $authorUserId;
        
        // reaction published on my debate
        // don't notif if same user
        if ($debateUserId != $authorUserId) {
            $usersIds[] = $debateUserId;

            $puNotification = $this->insertPUNotification($debateUserId, $authorUserId, $pNotificationId, $objectName, $objectId);

            // email
            $event = new GenericEvent($puNotification);
            $dispatcher = $this->eventDispatcher->dispatch('n_e_check', $event);
        }

        // reaction published on my reaction
        if ($reaction->getTreeLevel() > 1) {
            // retrieve parent's reaction
            $parent = $reaction->getParent();

            $targetUserId = $parent->getPUserId();
            $targetUser = $parent->getPUser();
            $pNotificationId = NotificationConstants::ID_D_R_REACTION_PUBLISH;

            // don't notif if same user
            if ($targetUserId != $authorUserId) {
                $usersIds[] = $targetUserId;
                $puNotification = $this->insertPUNotification($targetUserId, $authorUserId, $pNotificationId, $objectName, $objectId);

                // email
                $event = new GenericEvent($puNotification);
                $dispatcher = $this->eventDispatcher->dispatch('n_e_check', $event);
            }
        }

        // reaction published by followed user
        // retrieve debate's author
        $authorUser = PUserQuery::create()->findPk($authorUserId);

        // get debate's user followers
        $query = PUserQuery::create()->filterById($usersIds, " NOT IN ");
        $users = $authorUser->getFollowers($query);
        foreach ($users as $user) {
            $usersIds[] = $user->getId();

            $pNotificationId = NotificationConstants::ID_S_U_REACTION_PUBLISH;
            $puNotification = $this->insertPUNotification($user->getId(), $authorUserId, $pNotificationId, $objectName, $objectId);

            // email
            $event = new GenericEvent($puNotification);
            $dispatcher = $this->eventDispatcher->dispatch('n_e_check', $event);
        }

        // reaction published on followed debate
        // retrieve debate's followers
        $query = PUserQuery::create()->filterById($usersIds, " NOT IN ");
        $users = $debate->getFollowers(null, true, $query);
        foreach ($users as $user) {
            $usersIds[] = $user->getId();

            $pNotificationId = NotificationConstants::ID_S_D_REACTION_PUBLISH;
            $puNotification = $this->insertPUNotification($user->getId(), $authorUserId, $pNotificationId, $objectName, $objectId);

            // email
            $event = new GenericEvent($puNotification);
            $dispatcher = $this->eventDispatcher->dispatch('n_e_check', $event);
        }

        // reaction with followed tags
        // get reaction's tags
        $tags = $reaction->getTags();
        foreach ($tags as $tag) {
            // get tag's users
            $query = PUserQuery::create()->filterById($usersIds, " NOT IN ");
            $users = $tag->getUsers(true, $query);

            foreach ($users as $user) {
                $usersIds[] = $user->getId();

                $pNotificationId = NotificationConstants::ID_S_T_DOCUMENT;
                $puNotification = $this->insertPUNotification($user->getId(), $authorUserId, $pNotificationId, $objectName, $objectId);

                // email
                $event = new GenericEvent($puNotification);
                $dispatcher = $this->eventDispatcher->dispatch('n_e_check', $event);
            }
        }

        // localization
        $this->locDocNotificationsManagement($reaction, $usersIds, $authorUserId, $objectName, $objectId);
    }

    /**
     * Manage localization publication notifications
     *
     * @param PDocumentInterface debate or reaction
     * @param array $usersIds users' ids to exclude
     * @param int $authorUserId notification author id
     * @param string $objectName notification object name
     * @param int $objectId notification object id
     */
    private function locDocNotificationsManagement(PDocumentInterface $document, &$usersIds, $authorUserId, $objectName, $objectId)
    {
        $city = $department = $region = null;
        if ($cityId = $document->getPLCityId()) {
            $city = $document->getPLCity();
            $department = $city->getPLDepartment();
            $region = $department->getPLRegion();
        } elseif ($departmentId = $document->getPLDepartmentId()) {
            $department = $document->getPLDepartment();
            $region = $department->getPLRegion();
        } elseif ($regionId = $document->getPLRegionId()) {
            $region = $department->getPLRegion();
        }

        // retrieve users of city
        if ($city) {
            $query = PUserQuery::create()->filterById($usersIds, " NOT IN ");
            $users = $city->getUsers(true, $query);

            foreach ($users as $user) {
                $pNotificationId = NotificationConstants::ID_L_D_CITY;
                $puNotification = $this->insertPUNotification($user->getId(), $authorUserId, $pNotificationId, $objectName, $objectId);

                // email
                $event = new GenericEvent($puNotification);
                $dispatcher = $this->eventDispatcher->dispatch('n_e_check', $event);

                $usersIds[] = $user->getId();
            }
        }

        // retrieve users of department
        if ($department) {
            $query = PUserQuery::create()->filterById($usersIds, " NOT IN ");
            $users = $department->getUsers(true, $query);

            foreach ($users as $user) {
                $pNotificationId = NotificationConstants::ID_L_D_DEPARTMENT;
                $puNotification = $this->insertPUNotification($user->getId(), $authorUserId, $pNotificationId, $objectName, $objectId);

                // email
                $event = new GenericEvent($puNotification);
                $dispatcher = $this->eventDispatcher->dispatch('n_e_check', $event);

                $usersIds[] = $user->getId();
            }
        }

        // retrieve users of region
        if ($region) {
            $query = PUserQuery::create()->filterById($usersIds, " NOT IN ");
            $users = $region->getUsers(true, $query);

            foreach ($users as $user) {
                $pNotificationId = NotificationConstants::ID_L_D_REGION;
                $puNotification = $this->insertPUNotification($user->getId(), $authorUserId, $pNotificationId, $objectName, $objectId);

                // email
                $event = new GenericEvent($puNotification);
                $dispatcher = $this->eventDispatcher->dispatch('n_e_check', $event);
            }
        }
    }

    /**
     * Publication d'un commentaire.
     *
     * Notifications associées à gérer:
     * - Un commentaire a été publié sur un de vos documents
     * - Un commentaire a été publié par un utilisateur suivi
     * - Un commentaire a été publié sur un sujet suivi
     * - Un commentaire a été publié sur une réponse à un sujet suivi
     *
     * @param GenericEvent
     */
    public function onNCommentPublish(GenericEvent $event)
    {
        // $this->logger->info('*** onNCommentPublish');

        $subject = $event->getSubject();
        $authorUserId = $event->getArgument('author_user_id');
        $pNotificationId = NotificationConstants::ID_D_COMMENT_PUBLISH;
        $objectName = get_class($subject);
        $objectId = $subject->getId();

        // Document associé
        $document = $subject->getPDocument();
        $targetUserId = $document->getPUserId();
        $targetUser = $document->getPUser();

        // Emails notification array
        $neCheck = [];

        // Comment published on my debate or reaction
        // don't notif if same user
        if ($targetUserId != $authorUserId) {
            $puNotification = $this->insertPUNotification($targetUserId, $authorUserId, $pNotificationId, $objectName, $objectId);

            // email
            $event = new GenericEvent($puNotification);

            // store user's email notification to prevent duplicate sending
            if ($targetUser && $targetUser->isEmailNotificationSubscriber($puNotification->getPNotificationId())) {
                if (!isset($neCheck[$targetUser->getId()])) {
                    $neCheck[$targetUser->getId()] = $event;
                }
            }
        }

        // Retrieve comment's user
        $authorUser = PUserQuery::create()->findPk($authorUserId);

        // get comment's user followers
        $users = $authorUser->getFollowers();
        $userFollowerIds = [];
        foreach ($users as $user) {
            if ($user->getId() != $authorUserId) {
                $pNotificationId = NotificationConstants::ID_S_U_COMMENT_PUBLISH;
                $puNotification = $this->insertPUNotification($user->getId(), $authorUserId, $pNotificationId, $objectName, $objectId);

                // email
                $event = new GenericEvent($puNotification);

                // store user's email notification to prevent duplicate sending
                if ($user->isEmailNotificationSubscriber($puNotification->getPNotificationId())) {
                    if (!isset($neCheck[$user->getId()])) {
                        $neCheck[$user->getId()] = $event;
                    }
                }
            }
        }

        // new comments on followed debate and/or reaction of followed debate
        if ($document->getType() == ObjectTypeConstants::TYPE_DEBATE) {
            $debate = $document;
            $pNotificationId = NotificationConstants::ID_S_D_D_COMMENT_PUBLISH;
        } elseif ($document->getType() == ObjectTypeConstants::TYPE_REACTION) {
            $debate = $document->getDebate();
            $pNotificationId = NotificationConstants::ID_S_D_R_COMMENT_PUBLISH;
        } else {
            throw new InconsistentDataException(sprintf('Object type %s not managed', $document->getType()));
        }

        // get debate's followers
        $users = $debate->getFollowers();
        foreach ($users as $user) {
            if ($user->getId() != $authorUserId) {
                $puNotification = $this->insertPUNotification($user->getId(), $authorUserId, $pNotificationId, $objectName, $objectId);

                // email
                $event = new GenericEvent($puNotification);

                // store user's email notification to prevent duplicate sending
                if ($user->isEmailNotificationSubscriber($puNotification->getPNotificationId())) {
                    if (!isset($neCheck[$user->getId()])) {
                        $neCheck[$user->getId()] = $event;
                    }
                }
            }
        }

        // email notification dispatching
        foreach ($neCheck as $userId => $event) {
            $dispatcher = $this->eventDispatcher->dispatch('n_e_check', $event);
        }
    }

    /**
     * Suivi d'un débat.
     *
     * Notifications associées à gérer:
     * - Un utilisateur suit un de vos débats
     *
     * @param GenericEvent
     */
    public function onNDebateFollow(GenericEvent $event)
    {
        // $this->logger->info('*** onNDebateFollow');

        $subject = $event->getSubject();
        $authorUserId = $event->getArgument('author_user_id');
        $pNotificationId = NotificationConstants::ID_D_D_FOLLOWED;

        $objectName = get_class($subject);
        $objectId = $subject->getId();

        // Auteur du débat
        $targetUserId = $subject->getPUserId();

        $puNotification = $this->insertPUNotification($targetUserId, $authorUserId, $pNotificationId, $objectName, $objectId);

        // Alerte email
        $event = new GenericEvent($puNotification);
        $dispatcher =  $this->eventDispatcher->dispatch('n_e_check', $event);
    }

    /**
     * Suivi d'un profil.
     *
     * Notifications associées à gérer:
     * - Un utilisateur suit votre profil
     *
     * @param GenericEvent
     */
    public function onNUserFollow(GenericEvent $event)
    {
        // $this->logger->info('*** onNUserFollow');

        $subject = $event->getSubject();
        $authorUserId = $event->getArgument('author_user_id');
        $pNotificationId = NotificationConstants::ID_U_FOLLOWED;
        
        $objectName = get_class($subject);
        $objectId = $subject->getId();

        // User suivi
        $targetUserId = $subject->getId();

        $puNotification = $this->insertPUNotification($targetUserId, $authorUserId, $pNotificationId, $objectName, $objectId);

        // Alerte email
        $event = new GenericEvent($puNotification);
        $dispatcher =  $this->eventDispatcher->dispatch('n_e_check', $event);
    }

    /**
     * Gain d'un débat.
     *
     * Notifications associées à gérer:
     * - Vous avez obtenu un badge
     *
     * @param GenericEvent
     */
    public function onNBadgeWin(GenericEvent $event)
    {
        // $this->logger->info('*** onNBadgeWin');

        $subject = $event->getSubject();
        $pNotificationId = NotificationConstants::ID_U_BADGE;
        
        $targetUserId = $subject->getPUserId();
        $authorUserId = $targetUserId;

        // Récupération de l'objet badge gagné
        $badgeId = $subject->getPRBadgeId();
        $badge = PRBadgeQuery::create()->findPk($badgeId);
        $objectName = get_class($badge);
        $objectId = $badge->getId();

        $puNotification = $this->insertPUNotification($targetUserId, $authorUserId, $pNotificationId, $objectName, $objectId);

        // Alerte email
        $event = new GenericEvent($puNotification);
        $dispatcher =  $this->eventDispatcher->dispatch('n_e_check', $event);
    }

    /**
     * Ajout d'un tag à un profil
     *
     * Notifications associées à gérer:
     * - Un utilisateur s'est associé une thématique suivi
     *
     * @param GenericEvent
     */
    public function onNUserTag(GenericEvent $event)
    {
        // $this->logger->info('*** onNUserTag');

        $subject = $event->getSubject();
        $authorUserId = $event->getArgument('author_user_id');
        $pNotificationId = NotificationConstants::ID_S_T_USER;

        $objectName = get_class($subject);
        $objectId = $subject->getId();

        // Emails notification array
        $neCheck = [];

        $users = $subject->getUsers();
        foreach ($users as $user) {
            if ($user->getId() != $authorUserId) {
                $pNotificationId = NotificationConstants::ID_S_T_USER;
                $puNotification = $this->insertPUNotification($user->getId(), $authorUserId, $pNotificationId, $objectName, $objectId);

                // email
                $event = new GenericEvent($puNotification);

                // store user's email notification to prevent duplicate sending
                if ($user && $user->isEmailNotificationSubscriber($puNotification->getPNotificationId())) {
                    if (!isset($neCheck[$user->getId()])) {
                        $neCheck[$user->getId()] = $event;
                    }
                }
            }
        }

        // Alerte email

        // email notification dispatching
        foreach ($neCheck as $userId => $event) {
            $dispatcher = $this->eventDispatcher->dispatch('n_e_check', $event);
        }
    }

    /**
     * Creation of representative user / city - department - region
     * 
     * Notifications associées à gérer:
     * - Un élu correspondant à votre ville/département/région vient de s'inscrire
     *
     * @param GenericEvent
     */
    public function onNLocalizationUser(GenericEvent $event)
    {
        $this->logger->info('*** onNLocalizationUser');

        $electedUser = $event->getSubject();
        $electedUserId = $electedUser->getId();

        $objectName = get_class($electedUser);
        $objectId = $electedUser->getId();

        $city = $electedUser->getPLCity();
        $department = $city->getPLDepartment();
        $region = $department->getPLRegion();

        // retrieve users of city
        $users = $city->getUsers(true);

        // Array to store user ids to avoid duplicate notifs
        $usersIds = [];
        $usersIds[] = $electedUserId;

        foreach ($users as $user) {
            if ($user->getId() != $electedUserId) {
                $pNotificationId = NotificationConstants::ID_L_U_CITY;
                $puNotification = $this->insertPUNotification($user->getId(), $electedUserId, $pNotificationId, $objectName, $objectId);

                // email
                $event = new GenericEvent($puNotification);
                $dispatcher = $this->eventDispatcher->dispatch('n_e_check', $event);

                $usersIds[] = $user->getId();
            }
        }

        // retrieve users of department
        $query = PUserQuery::create()->filterById($usersIds, " NOT IN ");
        $users = $department->getUsers(true, $query);

        foreach ($users as $user) {
            $pNotificationId = NotificationConstants::ID_L_U_DEPARTMENT;
            $puNotification = $this->insertPUNotification($user->getId(), $electedUserId, $pNotificationId, $objectName, $objectId);

            // email
            $event = new GenericEvent($puNotification);
            $dispatcher = $this->eventDispatcher->dispatch('n_e_check', $event);

            $usersIds[] = $user->getId();
        }

        // retrieve users of region
        $query = PUserQuery::create()->filterById($usersIds, " NOT IN ");
        $users = $region->getUsers(true, $query);

        foreach ($users as $user) {
            $pNotificationId = NotificationConstants::ID_L_U_REGION;
            $puNotification = $this->insertPUNotification($user->getId(), $electedUserId, $pNotificationId, $objectName, $objectId);

            // email
            $event = new GenericEvent($puNotification);
            $dispatcher = $this->eventDispatcher->dispatch('n_e_check', $event);
        }
    }

    /**
     * Admin notification.
     * 
     * Notifications associées à gérer:
     * - Le support a envoyé une notification
     *
     * @param GenericEvent
     */
    public function onNAdminMessage(GenericEvent $event)
    {
        $this->logger->info('*** onNAdminMessage');

        $subject = $event->getSubject();
        $pNotificationId = NotificationConstants::ID_ADM_MESSAGE;

        $adminMsg = $event->getArgument('admin_msg');
        
        $targetUserId = $subject->getId();
        $authorUserId = UserConstants::USER_ID_ADMIN;

        $objectName = get_class($subject);
        $objectId = $subject->getId();

        $puNotification = $this->insertPUNotification($targetUserId, $authorUserId, $pNotificationId, $objectName, $objectId, $adminMsg);

        // Alerte email
        $event = new GenericEvent($puNotification);
        $dispatcher =  $this->eventDispatcher->dispatch('n_e_check', $event);
    }

    /**

    // ******************************************************** //
    //                      Méthodes privées                    //
    // ******************************************************** //

    /**
     * Insertion en BDD
     *
     * @param $userId
     * @param $authorUserId
     * @param $notificationId
     * @param $objectName
     * @param $objectId
     *
     * @return PUNotification  Objet inséré
     */
    private function insertPUNotification($userId, $authorUserId, $notificationId, $objectName, $objectId, $description = null)
    {
        // $this->logger->info('*** insertPUNotification');
        // $this->logger->info('userId = '.print_r($userId, true));
        // $this->logger->info('authorUserId = '.print_r($authorUserId, true));
        // $this->logger->info('notificationId = '.print_r($notificationId, true));
        // $this->logger->info('objectName = '.print_r($objectName, true));
        // $this->logger->info('objectId = '.print_r($objectId, true));

        $notif = new PUNotification();

        $notif->setPUserId($userId);
        $notif->setPNotificationId($notificationId);
        $notif->setPObjectName($objectName);
        $notif->setPObjectId($objectId);
        $notif->setPAuthorUserId($authorUserId);
        $notif->setDescription($description);
        $notif->setChecked(false);
        
        $notif->save();

        return $notif;
    }
}
