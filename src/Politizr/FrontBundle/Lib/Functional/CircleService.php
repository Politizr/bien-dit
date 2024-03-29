<?php
namespace Politizr\FrontBundle\Lib\Functional;

use Symfony\Component\EventDispatcher\GenericEvent;

use Politizr\Exception\InconsistentDataException;

use Politizr\Model\PUser;
use Politizr\Model\PCOwner;
use Politizr\Model\PCircle;
use Politizr\Model\PCTopic;

use Politizr\Model\PCOwnerQuery;
use Politizr\Model\PCircleQuery;
use Politizr\Model\PUserQuery;
use Politizr\Model\PCTopicQuery;
use Politizr\Model\PUInPCQuery;
use Politizr\Model\PDDebateQuery;
use Politizr\Model\PDReactionQuery;
use Politizr\Model\PDDCommentQuery;
use Politizr\Model\PDRCommentQuery;

use \PropelCollection;

/**
 * Functional service for circle management.
 *
 * @author Lionel Bouzonville
 */
class CircleService
{
    private $securityTokenStorage;
    private $securityAuthorizationChecker;
    
    private $circleManager;

    private $eventDispatcher;

    private $logger;

    protected $geoActive;

    /**
     *
     * @param @security.token_storage
     * @param @security.authorization_checker
     * @param @politizr.manager.circle
     * @param @event_dispatcher
     * @param @logger
     * @param %geo_active%
     */
    public function __construct(
        $securityTokenStorage,
        $securityAuthorizationChecker,
        $circleManager,
        $eventDispatcher,
        $logger,
        $geoActive
    ) {
        $this->securityTokenStorage = $securityTokenStorage;
        $this->securityAuthorizationChecker =$securityAuthorizationChecker;

        $this->circleManager = $circleManager;

        $this->eventDispatcher = $eventDispatcher;

        $this->logger = $logger;

        $this->geoActive = $geoActive;
    }

    /* ######################################################################################################## */
    /*                                              CIRCLE FUNCTIONS                                            */
    /* ######################################################################################################## */

    /**
     * Add a user in a circle
     *
     * @param PUser $user
     * @param PCircle $circle
     */    
    public function addUserInCircle(PUser $user = null, PCircle $circle = null)
    {
        if (!$user || !$circle) {
            throw new InconsistentDataException('User or circle null');
        }
        $this->circleManager->createUserInCircle($user->getId(), $circle->getId());

        // events
        $event = new GenericEvent($circle, array('p_user' => $user,));
        $dispatcher = $this->eventDispatcher->dispatch('c_subscribe', $event);
    }

    /**
     * Remove a user from a circle
     *
     * @param PUser $user
     * @param PCircle $circle
     */    
    public function removeUserFromCircle(PUser $user = null, PCircle $circle = null)
    {
        if (!$user || !$circle) {
            throw new InconsistentDataException('User or circle null');
        }
        $this->circleManager->deleteUserInCircle($user->getId(), $circle->getId());

        // events
        $event = new GenericEvent($circle, array('p_user' => $user,));
        $dispatcher = $this->eventDispatcher->dispatch('c_unsubscribe', $event);
    }

    /**
     * Remove a user authorization to publish reaction from a circle
     *
     * @param PUser $user
     * @param PCircle $circle
     * @param boolean $isAuthorizedReaction
     */    
    public function updateUserIsAuthorizedReactionInCircle(PUser $user = null, PCircle $circle = null, $isAuthorizedReaction)
    {
        if (!$user || !$circle) {
            throw new InconsistentDataException('User or circle null');
        }
        $this->circleManager->updateUserIsAuthorizedReactionInCircle($user->getId(), $circle->getId(), $isAuthorizedReaction);
    }

    /**
     * Get first public circle
     * Used in global_mode "oneshot"
     *
     * @return PCircle
     */
    public function getOneCircle()
    {
        // $this->logger->info('*** getOneCircle');

        $circle = PCircleQuery::create()
                        ->distinct()
                        ->filterByOnline(true)
                        ->filterByPrivateAccess(false)
                        ->findOne();

        return $circle;
    }


    /**
     * Get authorized circles by user
     *
     * @param PUser $user
     * @return PropelCollection[PCircle]
     */
    public function getAuthorizedCirclesByUser(PUser $user = null)
    {
        // $this->logger->info('*** getAuthorizedCirclesByUser');
        // $this->logger->info('$user = '.print_r($user, true));
        // $this->logger->info('$user->getPLCityId = '.print_r($user->getPLCityId(), true));

        if (!$user /*|| ($this->geoActive && !$user->getPLCityId())*/) {
            return null;
        }

        $circles = PCircleQuery::create()
                        ->distinct()
                        ->filterByOnline(true)
                        ->filterByPrivateAccess(false)
                        ->_or()
                        ->usePUInPCQuery()
                            ->filterByPUserId($user->getId())                    
                        ->endUse()
                        // ->_if($this->geoActive)
                        //     ->usePCGroupLCQuery()
                        //         ->filterByPLCityId($user->getPLCityId())
                        //     ->endUse()
                        // ->_endif()
                        ->orderByRank('desc')
                        ->find();

        return $circles;
    }

    /**
     * Get owners containing authorized circles by user
     * @todo more tests!
     *
     * @param PUser $user
     * @return PropelCollection[PCircle]
     */
    public function getAuthorizedOwnersByUser(PUser $user = null)
    {
        // $this->logger->info('*** getAuthorizedOwnersByUser');
        // $this->logger->info('$user = '.print_r($user, true));
        // $this->logger->info('$user->getPLCityId = '.print_r($user->getPLCityId(), true));

        $query = PCOwnerQuery::create();
        if ($user) {
            // public or authorized private
            $query = $query->usePCircleQuery()
                    ->filterByOnline(true)
                    ->filterByPrivateAccess(false)
                    ->_or()
                    ->usePUInPCQuery()
                        ->filterByPUserId($user->getId())                    
                    ->endUse()
                ->endUse()
                ->orderByRank('desc')
                ;

            // public w. geo scope authorized
            if ($this->geoActive) {
                $query = $query->usePCircleQuery()
                    ->filterByOnline(true)
                    ->usePCGroupLCQuery()
                        ->filterByPLCityId($user->getPLCityId())
                    ->endUse()
                ->endUse()
                ->orderByRank('desc')
                ;
            }
        } else {
            // public only (without geo scope)
            $query = $query->usePCircleQuery()
                    ->filterByOnline(true)
                    ->filterByPrivateAccess(false)
                ->endUse()
                ->orderByRank('desc')
                ;
        }

        $owners = $query
                    ->distinct()
                    ->find();

        return $owners;
    }

    /**
     * Get owner's circles by user
     *
     * @param PCOwner $owner
     * @param PUser $user
     * @return PropelCollection[PCircle]
     */
    public function getOwnerCirclesByUser(PCOwner $owner, PUser $user = null)
    {
        // $this->logger->info('*** getPCOwnersByUser');
        // $this->logger->info('$user = '.print_r($user, true));

        if (!$owner) {
            throw new InconsistentDataException('Owner null');
        }

        $query = PCircleQuery::create();
        if ($user) {
            $query = $query
                    ->usePUinPCQuery()
                        ->filterByPUserId($user->getId())
                    ->endUse();
        } else {
            $query = $query
                    ->filterByPrivateAccess(false);
        }

        $circles = $query
                    ->filterByOnline(true)
                    ->filterByPCOwnerId($owner->getId())
                    ->orderByRank('desc')
                    ->distinct()
                    ->find();

        return $circles;
    }

    /**
     * Get users in circle by circle id or all users if no circle id is specified
     *
     * @param int $circleId
     * @param boolean $isAuthorizedReaction
     * @param array $filters ['only_elected' => boolean, 'city_insee_code' => string, 'department_code' => string ]
     * @return PropelCollection[PUser]
     */
    public function getUsersInCircleByCircleId($circleId = null, $isAuthorizedReaction = null, $filters = null)
    {
        $users = PUserQuery::create()
            ->filterByCustomFilters($filters)
            ->_if($circleId)
                ->usePUinPCQuery()
                    ->filterByPCircleId($circleId)
                    ->_if($isAuthorizedReaction)
                        ->filterByIsAuthorizedReaction($isAuthorizedReaction)
                    ->_endif()
                ->endUse()
            ->_endif()
            ->orderByName()
            ->distinct()
            ->find();

        return $users;
    }

    /**
     * Check if user is member of circle
     *
     * @param PUser $user
     * @param PCircle $circle
     * @return boolean
     */
    public function isUserMemberOfCircle(PCircle $circle, PUser $user)
    {
        if (!$circle || !$user) {
            throw new InconsistentDataException('Circle or user null');
        }

        $nb = PUInPCQuery::create()
            ->filterByPUserId($user->getId())
            ->filterByPCircleId($circle->getId())
            ->count();

        if ($nb > 0) {
            return true;
        }

        return false;
    }

    /**
     * Return number of user's circles
     *
     * @param PUser $user
     * @return int
     */
    public function countUserCircles(PUser $user)
    {
        if (!$user) {
            throw new InconsistentDataException('User null');
        }

        $nb = PUInPCQuery::create()
            ->filterByPUserId($user->getId())
            ->count();

        return $nb;
    }

    /**
     * Get debates relative to a circle
     *
     * @param PCircle $circle
     * @return PropelCollection[PDDebate]
     */
    public function getDebatesByCircle(PCircle $circle = null)
    {
        if (!$circle) {
            throw new InconsistentDataException('Circle null');
        }

        $debates = PDDebateQuery::create()
            ->distinct()
            ->usePCTopicQuery()
                ->usePCircleQuery()
                    ->filterById($circle->getId())
                ->endUse()
            ->endUse()
            ->find();

        return $debates;
    }


    /**
     * Check if user has role to publish reaction in the circle
     *
     * @param PCircle $circle
     * @param PUser $user
     * @return boolean
     */
    public function isUserAuthorizedReaction(PCircle $circle, PUser $user)
    {
        if (!$circle || !$user) {
            throw new InconsistentDataException('Circle or user null');
        }

        $nb = PUInPCQuery::create()
            ->filterByPUserId($user->getId())
            ->filterByPCircleId($circle->getId())
            ->filterByIsAuthorizedReaction(true)
            ->count();

        if ($nb > 0) {
            return true;
        }

        return false;
    }

    /**
     * Get nb authorized reaction users by circle
     *
     * @param PUser $user
     * @return int
     */
    public function getNbAuthorizedReactionUsersByCircle(PCircle $circle)
    {
        // $this->logger->info('*** getAuthorizedReactionUsersByCircle');
        // $this->logger->info('$circle = '.print_r($circle, true));

        if (!$circle) {
            return null;
        }

        $nbUsers = PUserQuery::create()
            ->usePUInPCQuery()
                ->filterByPCircleId($circle->getId())
                ->filterByIsAuthorizedReaction(true)
            ->endUse()
            ->count();

        return $nbUsers;
    }

    /**
     * Get authorized reaction users by circle
     *
     * @param PUser $user
     * @return PropelCollection[PUser]
     */
    public function getAuthorizedReactionUsersByCircle(PCircle $circle)
    {
        // $this->logger->info('*** getAuthorizedReactionUsersByCircle');
        // $this->logger->info('$circle = '.print_r($circle, true));

        if (!$circle) {
            return null;
        }

        $users = PUserQuery::create()
            ->usePUInPCQuery()
                ->filterByPCircleId($circle->getId())
                ->filterByIsAuthorizedReaction(true)
            ->endUse()
            ->find();

        return $users;
    }

    /**
     * Get authorized reaction users ids by circle
     *
     * @param PUser $user
     * @return array
     */
    public function getAuthorizedReactionUsersIdsByCircle(PCircle $circle)
    {
        $users = $this->getAuthorizedReactionUsersByCircle($circle);

        $ids = array();
        foreach ($users as $user) {
            $ids[] = $user->getId();
        }

        return $ids;
    }

    /**
     * Return users authorized to join circle depending of its attributes
     *
     * @param PCircle $cirlce
     * @return $users
     */
    public function getCircleAuthorizedUsers($circle)
    {
        if (!$circle) {
            throw new InconsistentDataException('Circle null');
        }

        // Private circle > users are managed manually
        if ($circle->getPrivateAccess()) {
            return null;
        }

        // Public circle > all users
        if ($circle->getPublicCircle()) {
            $users = PUserQuery::create()->find();
        }

        // @todo geolocalized circle > users who match geo zone
        return $users;
    }

    /**
     * Insert existing users authorized to join newly created circle
     *
     * @param PCircle $circle
     * @return $users
     */
    public function updateCircleAuthorizedUsers(PCircle $circle)
    {
        if (!$circle) {
            throw new InconsistentDataException('Circle null');
        }

        // get authorized users in circle
        $users = $this->getCircleAuthorizedUsers($circle);

        // insert these users in `p_u_in_p_c` table
        $this->circleManager->insertUsersInCircle($users, $circle->getId());

        // update users roles
        $this->circleManager->addUsersCircleRole($users, $circle->getId());

        // @todo special case public circle > raw sql to manager big insert / upd in db (every users)
        // if ($publicCircle = $circle->getPublicCircle()) {
        // } else {            
        // }
    }

    /* ######################################################################################################## */
    /*                                              TOPIC FUNCTIONS                                             */
    /* ######################################################################################################## */
    
    /**
     * Update form options if topic has geoloc preset values
     *
     * @param PCTopic $topic
     * @param array $options
     */
    public function updateDocumentLocalizationTypeOptions(PCTopic $topic = null, & $options)
    {
        if ($topic && $topic->getForceGeolocType() && $topic->getForceGeolocId()) {
            $options['force_geoloc'] = true;
            $options['force_geoloc_type'] = $topic->getForceGeolocType();
            $options['force_geoloc_id'] = $topic->getForceGeolocId();
        }
    }

    /**
     * Get topic id list by user id
     *
     * @param int $userId
     * @return array
     */
    public function getTopicIdsByUserId($userId)
    {
        $topicIds = PCTopicQuery::create()
            ->select('Id')
            ->usePCircleQuery()
                ->usePUInPCQuery()
                    ->filterByPUserId($userId)
                ->endUse()
            ->endUse()
            ->find()
            ->toArray();

        return $topicIds;
    }

    /**
     * Get topic id list by user id
     *
     * @param int $userId
     * @return array
     */
    public function getPublicCircleTopicIds()
    {
        $topicIds = PCTopicQuery::create()
            ->select('Id')
            ->usePCircleQuery()
                ->filterByPublicCircle(true)
            ->endUse()
            ->find()
            ->toArray();

        return $topicIds;
    }

    /**
     * Return users by filtering those who are not in circle
     *
     * @param \PropelCollection $users
     * @param int $circleId
     * @return \PropelCollection
     */
    public function filterUsersNotInCircle(\PropelCollection $users, PCircle $circle)
    {
        foreach ($users as $key => $user) {
            if (!$this->isUserMemberOfCircle($circle, $user)) {
                $users->remove($key);
            }
        }
        return $users;
    }

    /**
     * Count number of PDDebate in a topic
     *
     * @param PCTopic $topic
     * @return int
     */
    public function countDebatesByTopic(PCTopic $topic)
    {
        if (!$topic) {
            return null;
        }

        $nb = PDDebateQuery::create()
            ->online()
            ->filterByPCTopicId($topic->getId())
            ->count();

        return $nb;
    }

    /**
     * Count number of PDReaction in a topic
     *
     * @param PCTopic $topic
     * @return int
     */
    public function countReactionsByTopic(PCTopic $topic)
    {
        if (!$topic) {
            return null;
        }

        $nb = PDReactionQuery::create()
            ->online()
            ->filterByPCTopicId($topic->getId())
            ->count();

        return $nb;
    }

    /**
     * Count number of PDDComment & PDRComment in a topic
     *
     * @param PCTopic $topic
     * @return int
     */
    public function countCommentsByTopic(PCTopic $topic)
    {
        if (!$topic) {
            return null;
        }

        $nbDebateComments = PDDCommentQuery::create()
            ->online()
            ->usePDDebateQuery()
                ->online()
                ->filterByPCTopicId($topic->getId())
            ->endUse()
            ->count();

        $nbReactionComments = PDRCommentQuery::create()
            ->online()
            ->usePDReactionQuery()
                ->online()
                ->filterByPCTopicId($topic->getId())
            ->endUse()
            ->count();

        $nb = $nbDebateComments + $nbReactionComments;

        return $nb;
    }
}
