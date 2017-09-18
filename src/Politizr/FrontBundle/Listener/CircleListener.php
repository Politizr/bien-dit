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
    protected $logger;
    protected $eventDispatcher;

    /**
     * @event_dispatcher
     * @logger
     */
    public function __construct($logger, $eventDispatcher)
    {
        $this->logger = $logger;
        $this->eventDispatcher = $eventDispatcher;
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

        // remove circle role
        $user->removeRole('ROLE_CIRCLE_'.$circle->getId());
        $user->save();
    }
}
