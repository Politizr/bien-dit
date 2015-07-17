<?php
namespace Politizr\FrontBundle\Lib\Manager;

use Politizr\Exception\InconsistentDataException;
use Politizr\Exception\FormValidationException;

/**
 * DB manager service for reputation.
 *
 * @author Lionel Bouzonville
 */
class ReputationManager
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
