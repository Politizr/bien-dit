<?php
namespace Politizr\FrontBundle\Lib;

/**
 * Virtual object to manage timeline with various object type
 * PDDebate / PDReaction / PDDComment / PDRComment / PRAction / PRBadge / PUser
 *
 * @author Lionel Bouzonville
 */
class TimelineRow
{

    protected $id;
    protected $targetId;
    protected $targetUserId;
    protected $targetObjectName;
    protected $title;
    protected $publishedAt;
    protected $type;

    /**
     *
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     *
     */
    public function setId($val)
    {
        $this->id = $val;
    }

    /**
     *
     */
    public function getTargetId()
    {
        return $this->targetId;
    }

    /**
     *
     */
    public function setTargetId($val)
    {
        $this->targetId = $val;
    }

    /**
     *
     */
    public function getTargetUserId()
    {
        return $this->targetUserId;
    }

    /**
     *
     */
    public function setTargetUserId($val)
    {
        $this->targetUserId = $val;
    }

    /**
     *
     */
    public function getTargetObjectName()
    {
        return $this->targetObjectName;
    }

    /**
     *
     */
    public function setTargetObjectName($val)
    {
        $this->targetObjectName = $val;
    }

    /**
     *
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     *
     */
    public function setTitle($val)
    {
        $this->title = $val;
    }

    /**
     *
     */
    public function getPublishedAt()
    {
        return $this->publishedAt;
    }

    /**
     *
     */
    public function setPublishedAt($val)
    {
        $this->publishedAt = $val;
    }

    /**
     *
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     *
     */
    public function setType($val)
    {
        $this->type = $val;
    }
}
