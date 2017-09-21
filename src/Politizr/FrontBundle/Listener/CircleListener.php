<?php

namespace Politizr\FrontBundle\Listener;

use Symfony\Component\EventDispatcher\GenericEvent;

use Politizr\Exception\InconsistentDataException;

/**
 * Circle listener
 *
 * @author Lionel Bouzonville
 */
class CircleListener
{
    protected $circleService;
    protected $userService;

    protected $eventDispatcher;

    protected $logger;

    /**
     * @politizr.functional.circle
     * @politizr.functional.user
     * @event_dispatcher
     * @logger
     */
    public function __construct(
        $circleService,
        $userService,
        $eventDispatcher,
        $logger
    ) {
        $this->circleService = $circleService;
        $this->userService = $userService;

        $this->eventDispatcher = $eventDispatcher;

        $this->logger = $logger;
    }

    /**
     * Politizr user inscription
     *
     * @param GenericEvent
     */
    public function onUInscription(GenericEvent $event)
    {
        $user = $event->getSubject();

        if (!$user) {
            throw new InconsistentDataException('Subscribe event cannot manage circle or user null');
        }

        // get all circle for user's geoloc
        $circles = $this->circleService->getCirclesByUser($user);

        // default add user to his local circles
        foreach ($circles as $circle) {
            $this->circleService->addUserInCircle($user, $circle);
        }
    }

    /**
     * User subscribes to Circle
     *
     * @param GenericEvent
     */
    public function onCSubscribe(GenericEvent $event)
    {
        $circle = $event->getSubject();
        $user = $event->getArgument('p_user');

        if (!$circle || !$user) {
            throw new InconsistentDataException('Subscribe event cannot manage circle or user null');
        }

        // add circle role
        $user->addRole('ROLE_CIRCLE_'.$circle->getId());
        $user->save();
    }

    /**
     * User unsubscribes to Circle
     *
     * @param GenericEvent
     */
    public function onCUnsubscribe(GenericEvent $event)
    {
        $circle = $event->getSubject();
        $user = $event->getArgument('p_user');

        if (!$circle || !$user) {
            throw new InconsistentDataException('Unsubscribe event cannot manage circle or user null');
        }

        // unfollow all circle debates
        $debates = $this->circleService->getDebatesByCircle($circle);
        foreach ($debates as $debate) {
            $this->userService->unfollowDebate($user, $debate);
        }

        // remove circle role
        $user->removeRole('ROLE_CIRCLE_'.$circle->getId());
        $user->save();
    }
}