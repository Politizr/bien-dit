<?php
namespace Politizr\FrontBundle\Lib\Functional;

use Symfony\Component\EventDispatcher\GenericEvent;

use Politizr\Exception\InconsistentDataException;

use Politizr\Model\PUser;
use Politizr\Model\PCircle;
use Politizr\Model\PCTopic;

use Politizr\Model\PCircleQuery;
use Politizr\Model\PCTopicQuery;
use Politizr\Model\PUInPCQuery;
use Politizr\Model\PDDebateQuery;

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

    /**
     *
     * @param @security.token_storage
     * @param @security.authorization_checker
     * @param @politizr.manager.circle
     * @param @event_dispatcher
     * @param @logger
     */
    public function __construct(
        $securityTokenStorage,
        $securityAuthorizationChecker,
        $circleManager,
        $eventDispatcher,
        $logger
    ) {
        $this->securityTokenStorage = $securityTokenStorage;
        $this->securityAuthorizationChecker =$securityAuthorizationChecker;

        $this->circleManager = $circleManager;

        $this->eventDispatcher = $eventDispatcher;

        $this->logger = $logger;
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
     * Get authorized circles by user
     *
     * @param PUser $user
     * @return PropelCollection[PCircle]
     */
    public function getCirclesByUser(PUser $user = null)
    {
        // $this->logger->info('*** getCirclesByUserId');
        // $this->logger->info('$user = '.print_r($user, true));
        // $this->logger->info('$user->getPLCityId = '.print_r($user->getPLCityId(), true));

        if (!$user || !$user->getPLCityId()) {
            return null;
        }

        $circles = PCircleQuery::create()
                        ->filterByOnline(true)
                        ->usePCGroupLCQuery()
                            ->filterByPLCityId($user->getPLCityId())
                        ->endUse()
                        ->find();

        return $circles;
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
     * Return users by filtering those who are not in circle
     *
     * @param \PropelCollection $users
     * @param int $circleId
     * @return \PropelCollection
     */
    public function filterUsersNotInCircle(\PropelCollection $users, PCircle $circle) {
        foreach ($users as $key => $user) {
            if (!$this->isUserMemberOfCircle($user, $circle)) {
                $users->remove($key);
            }
        }
        return $users;
    }
}
