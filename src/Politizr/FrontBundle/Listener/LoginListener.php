<?php

namespace Politizr\FrontBundle\Listener;

use Monolog\Logger;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\Router;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

/**
 * Listener used to redirect to the right URL depending of user roles
 */
class LoginListener
{
    /** @var Router */
    protected $router;

    /** @var TokenStorage */
    protected $token;

    /** @var EventDispatcherInterface */
    protected $dispatcher;

    /** @var GlobalTools */
    protected $globalTools;

    /** @var Logger */
    protected $logger;

    /**
     * @param Router $router
     * @param TokenStorage $token
     * @param EventDispatcherInterface $dispatcher
     * @param @politizr.tools.global
     * @param Logger $logger
     */
    public function __construct(Router $router, TokenStorage $token, EventDispatcherInterface $dispatcher, $globalTools, Logger $logger)
    {
        $this->router       = $router;
        $this->token        = $token;
        $this->dispatcher   = $dispatcher;
        $this->globalTools  = $globalTools;
        $this->logger       = $logger;
    }

    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event)
    {
        $this->dispatcher->addListener(KernelEvents::RESPONSE, [$this, 'onKernelResponse']);
    }

    public function onKernelResponse(FilterResponseEvent $event)
    {
        $roles = $this->token->getToken()->getRoles();

        $rolesTab = array_map(function ($role) {
            return $role->getRole();
        }, $roles);

        // $this->logger->info(var_export($rolesTab, true));

        if (in_array('ROLE_ADMIN', $rolesTab)) {
            $route = $this->router->generate('PolitizrAdminHomepage');
        } elseif (in_array('ROLE_CITIZEN', $rolesTab) || in_array('ROLE_ELECTED', $rolesTab)) {
            $profileSuffix = $this->globalTools->computeProfileSuffix();
            $route = $this->router->generate(sprintf('Homepage%s', $profileSuffix));
        } elseif (in_array('ROLE_CITIZEN_INSCRIPTION', $rolesTab)) {
            $route = $this->router->generate('InscriptionContact');
        } elseif (in_array('ROLE_ELECTED_INSCRIPTION', $rolesTab)) {
            $route = $this->router->generate('InscriptionElectedContact');
        } elseif (in_array('ROLE_OAUTH_USER', $rolesTab)) {
            $route = $this->router->generate('oauth_target');
        } else {
            $route = $this->router->generate('Homepage');
        }

        $event->getResponse()->headers->set('Location', $route);
    }
}
