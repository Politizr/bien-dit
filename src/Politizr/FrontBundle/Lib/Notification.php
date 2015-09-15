<?php

namespace Politizr\Model;

/**
 * Minified version of PUNotification object used to select distinct results from PUNotification.
 *
 * @author Lionel Bouzonville
 */
class Notification
{
    private $pObjectName;
    private $pObjectId;
    private $pAuthorUserId;

    /**
     *
     * @return string
     */
    public function getPObjectName()
    {
        return $this->pObjectName;
    }

    /**
     *
     * @return string
     */
    public function getPObjectId()
    {
        return $this->pObjectId;
    }

    /**
     *
     * @return string
     */
    public function getPAuthorId()
    {
        return $this->pAuthorId;
    }

    /**
     *
     * @param string $pObjectName
     * @return PUNotificationView
     */
    public function setPObjectName($pObjectName)
    {
        $this->pObjectName = $pObjectName;

        return $this;
    }

    /**
     *
     * @param string $pObjectId
     * @return PUNotificationView
     */
    public function setPObjectId($pObjectId)
    {
        $this->pObjectId = $pObjectId;

        return $this;
    }

    /**
     *
     * @param string $pAuthorId
     * @return PUNotificationView
     */
    public function setPAuthorId($pAuthorId)
    {
        $this->pAuthorId = $pAuthorId;

        return $this;
    }
}
