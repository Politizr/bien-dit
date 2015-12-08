<?php
namespace Politizr\FrontBundle\Listener;

use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;

use Politizr\Exception\SendEmailException;

use Politizr\Model\PMAppException;

/**
 *
 * @see http://stackoverflow.com/questions/10336665/overriding-symfony-2-exceptions
 * @author Lionel Bouzonville
 */
class ExceptionListener
{
    private $securityTokenStorage;
    private $monitoringManager;
    private $logger;

    /**
     *
     * @param @security.token_storage
     * @param @politizr.manager.monitoring
     * @param @logger
     */
    public function __construct($securityTokenStorage, $monitoringManager, $logger)
    {
        $this->securityTokenStorage = $securityTokenStorage;

        $this->monitoringManager = $monitoringManager;

        $this->logger = $logger;
    }

    /**
     *
     */
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $exception =  $event->getException();
        
        // get current user / bug
        $userId = null;
        $token = $this->securityTokenStorage->getToken();
        if ($token && $user = $token->getUser()) {
            $className = 'Politizr\Model\PUser';
            if ($user && $user instanceof $className) {
                $userId = $user->getId();
            }
        }

        try {
            $pmAppException = $this->monitoringManager->createAppException($exception, $userId);
        } catch (\Exception $e) {
            $this->logger->error(sprintf('Exception onKernelException %s', $e->getMessage()));
        }
    }
}
