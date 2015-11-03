<?php
namespace Politizr\FrontBundle\Lib;

/**
 * Virtual object to manage timeline with various object type
 * PDDebate / PDReaction / PDDComment / PDRComment
 *
 * @author Lionel Bouzonville
 */
class TimelineRow
{

    protected $id;
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
