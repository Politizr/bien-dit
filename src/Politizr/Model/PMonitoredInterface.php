<?php

namespace Politizr\Model;

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
