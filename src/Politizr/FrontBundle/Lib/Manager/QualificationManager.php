<?php
namespace Politizr\FrontBundle\Lib\Manager;

/**
 * DB manager service for qualification.
 *
 * @author Lionel Bouzonville
 */
class QualificationManager
{
    private $logger;

    /**
     *
     * @param @logger
     */
    public function __construct($logger)
    {
        $this->logger = $logger;
    }
}
