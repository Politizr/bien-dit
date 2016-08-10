<?php

namespace Politizr\FrontBundle\Listener;
 
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\HttpKernel;

use Politizr\Model\PUser;

/**
 * http://www.symfony-grenoble.fr/en/238/list-online-users/
 *
 * @author Lionel Bouzonville
 */
class ActivityListener
{
    protected $securityTokenStorage;

    /**
     *
     * @param @security.token_storage
     */
    public function __construct(TokenStorage $securityTokenStorage)
    {
        $this->securityTokenStorage = $securityTokenStorage;
    }
 
    /**
     * Update the user "lastActivity" on each request
     *
     * @param FilterControllerEvent $event
     */
    public function onCoreController(FilterControllerEvent $event)
    {
        // Here we are checking that the current request is a "MASTER_REQUEST", and ignore any subrequest in the process (for example when doing a render() in a twig template)
        if ($event->getRequestType() !== HttpKernel::MASTER_REQUEST) {
            return;
        }
 
        // We are checking a token authentification is available before using the User
        if ($this->securityTokenStorage->getToken()) {
            $user = $this->securityTokenStorage->getToken()->getUser();
 
            // Update the user "lastActivity" on each request
            // use a delay during wich the user will be considered as still active, in order to avoid too much upd in db
            $delay = new \DateTime();
            $delay->setTimestamp(strtotime('2 minutes ago'));
 
            // We are checking the User class in order to be certain we can call "getLastActivity".
            if ($user instanceof PUser && $user->getLastActivity() < $delay) {
                $user->setLastActivity(new \DateTime());
                $user->save();
            }
        }
    }
}
