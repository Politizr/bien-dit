<?php
namespace Politizr\FrontBundle\Lib;

use Politizr\Constant\ObjectTypeConstants;
use Politizr\Constant\PathConstants;

use Politizr\Exception\InconsistentDataException;

use Eko\FeedBundle\Item\Writer\RoutedItemInterface;

/**
 * Virtual object to manage publication (subjects + reactions + comments) w. optionnaly interacted stats nbReactions / nbComments / nbNotifications
 *
 * @author Lionel Bouzonville
 */
class InteractedPublication
{
    protected $id;
    protected $authorId;
    protected $title;
    protected $description;
    protected $publishedAt;
    protected $type;

    protected $beginAt;
    protected $endAt;
    protected $nbReactions;
    protected $nbComments;
    protected $nbNotifications;


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
    public function getAuthorId()
    {
        return $this->author_id;
    }

    /**
     *
     */
    public function setAuthorId($val)
    {
        $this->author_id = $val;
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
    public function getDescription()
    {
        return $this->description;
    }

    /**
     *
     */
    public function setDescription($val)
    {
        $this->description = $val;
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


    /**
     *
     */
    public function getBeginAt()
    {
        return $this->beginAt;
    }

    /**
     *
     */
    public function setBeginAt($val)
    {
        $this->beginAt = $val;
    }

    /**
     *
     */
    public function getEndAt()
    {
        return $this->endAt;
    }

    /**
     *
     */
    public function setEndAt($val)
    {
        $this->endAt = $val;
    }

    /**
     *
     */
    public function getNbReactions()
    {
        return $this->nbReactions;
    }

    /**
     *
     */
    public function setNbReactions($val)
    {
        $this->nbReactions = $val;
    }

    /**
     *
     */
    public function getNbComments()
    {
        return $this->nbComments;
    }

    /**
     *
     */
    public function setNbComments($val)
    {
        $this->nbComments = $val;
    }

    /**
     *
     */
    public function getNbNotifications()
    {
        return $this->nbNotifications;
    }

    /**
     *
     */
    public function setNbNotifications($val)
    {
        $this->nbNotifications = $val;
    }
}
