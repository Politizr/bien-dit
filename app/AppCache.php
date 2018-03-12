<?php

require_once __DIR__.'/AppKernel.php';

use FOS\HttpCache\SymfonyCache\CacheInvalidation;
use FOS\HttpCache\SymfonyCache\EventDispatchingHttpCache;

use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\HttpCache\HttpCache;
use Symfony\Component\HttpKernel\HttpCache\StoreInterface;

use FOS\HttpCache\SymfonyCache\DebugListener;
use FOS\HttpCache\SymfonyCache\CustomTtlListener;
use FOS\HttpCache\SymfonyCache\PurgeListener;
use FOS\HttpCache\SymfonyCache\RefreshListener;
use FOS\HttpCache\SymfonyCache\UserContextListener;

class AppCache extends HttpCache implements CacheInvalidation
{
    use EventDispatchingHttpCache;

    /**
     * Overwrite constructor to register event listeners for FOSHttpCache.
     */
    public function __construct(
        HttpKernelInterface $kernel,
        StoreInterface $store,
        SurrogateInterface $surrogate = null,
        array $options = []
    ) {
        parent::__construct($kernel, $store, $surrogate, $options);

        $this->addSubscriber(new CustomTtlListener());
        $this->addSubscriber(new PurgeListener());
        $this->addSubscriber(new RefreshListener());
        $this->addSubscriber(new UserContextListener());
        if (isset($options['debug']) && $options['debug']) {
            $this->addSubscriber(new DebugListener());
        }
    }
    /**
     * Made public to allow event listeners to do refresh operations.
     *
     * {@inheritDoc}
     */
    public function fetch(Request $request, $catch = false)
    {
        return parent::fetch($request, $catch);
    }
}