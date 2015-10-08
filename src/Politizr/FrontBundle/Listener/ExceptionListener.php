<?php
namespace Politizr\FrontBundle\Listener;

use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;

use Politizr\Model\PMAppException;

/**
 *
 * @see http://stackoverflow.com/questions/10336665/overriding-symfony-2-exceptions
 * @author Lionel Bouzonville
 */
class ExceptionListener
{
    private $monitoringManager;
    private $logger;

    /**
     *
     * @param @politizr.manager.monitoring
     * @param @logger
     */
    public function __construct($monitoringManager, $logger)
    {
        $this->monitoringManager = $monitoringManager;

        $this->logger = $logger;
    }

    /**
     *
     */
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $exception =  $event->getException();

        try {
            $pmAppException = $this->monitoringManager->createAppException($exception);
        } catch (\Exception $e) {
            // @todo more?
            $this->logger->error(sprintf('Exception onKernelException %s', $e->getMessage()));
        }
    }
}
