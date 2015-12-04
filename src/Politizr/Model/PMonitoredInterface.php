<?php

namespace Politizr\Model;

use Politizr\Model\om\BasePDocument;

use Politizr\Model\PDDebateQuery;
use Politizr\Model\PDReactionQuery;

use Politizr\Exception\InconsistentDataException;

/**
 * Interface for managing monitored objects: PMAbuseReporting, PmAskForUpdate
 *
 * @author Lionel Bouzonville
 */
interface PMonitoredInterface
{
    /**
     *
     * @return string
     */
    public function getType();

    /**
     *
     * @return string
     */
    public function getPObjectUuid();

    /**
     *
     * @return string
     */
    public function getPObjectName();

    /**
     *
     * @return string
     */
    public function getMessage();
}
