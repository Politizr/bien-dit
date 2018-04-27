<?php
namespace Politizr\FrontBundle\Lib;

use Politizr\Constant\ObjectTypeConstants;
use Politizr\Constant\PathConstants;

use Politizr\Exception\InconsistentDataException;

use Politizr\Model\PDDebateQuery;
use Politizr\Model\PDReactionQuery;
use Politizr\Model\PDDCommentQuery;
use Politizr\Model\PDRCommentQuery;

use Eko\FeedBundle\Item\Writer\RoutedItemInterface;

/**
 * Virtual object to manage publication
 *
 * @author Lionel Bouzonville
 */
class Publication implements RoutedItemInterface
{
    protected $globalTools;
    protected $requestStack;

    protected $id;
    protected $title;
    protected $description;
    protected $fileName;
    protected $slug;
    protected $publishedAt;
    protected $type;

    /**
     *
     * @param politizr.tools.global
     */
    public function __construct($globalTools = null, $requestStack = null)
    {
        $this->globalTools = $globalTools;
        $this->requestStack = $requestStack;
    }

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
    public function getFileName()
    {
        return $this->fileName;
    }

    /**
     *
     */
    public function setFileName($val)
    {
        $this->fileName = $val;
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
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     *
     */
    public function setSlug($val)
    {
        $this->slug = $val;
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

    // ******************************************************************************************* //

    /**
     * Return "real" object relative to this abstract publication
     *
     * @return PDDebate|PDReaction|PDDComment|PDRComment
     */
    public function getRelativeObject()
    {
        switch ($this->getType()) {
            case ObjectTypeConstants::TYPE_DEBATE:
                return PDDebateQuery::create()->findPk($this->getId());
                break;
            case ObjectTypeConstants::TYPE_REACTION:
                return PDReactionQuery::create()->findPk($this->getId());
                break;
            case ObjectTypeConstants::TYPE_DEBATE_COMMENT:
                return PDDCommentQuery::create()->findPk($this->getId());
                break;
            case ObjectTypeConstants::TYPE_REACTION_COMMENT:
                return PDRCommentQuery::create()->findPk($this->getId());
                break;
            default:
                throw new InconsistentDataException(sprintf('Cannot retrieve relative object for Publication ID-', $this->getId()));
        }
    }

    // ******************************************************************************************* //

    /**
     * Returns entity item title
     *
     * @return string
     */
    public function getFeedItemTitle()
    {
        return $this->getTitle();
    }

    /**
     * Returns entity item description
     *
     * @return string
     */
    public function getFeedItemDescription()
    {
        $description = $this->getDescription();
        if ($this->globalTools) {
            $paragraphs = $this->globalTools->explodeParagraphs($description);
            $paragraphs = array_slice($paragraphs, 0, 1);
            $description = '';
            foreach ($paragraphs as $paragraph) {
                $description .= sprintf('<p>%s</p>', $paragraph);
            }
        }

        return $description;
    }

    /**
     * Returns entity item publication date
     *
     * @return DateTime
     */
    public function getFeedItemPubDate()
    {
        return $this->getPublishedAt();
    }

    /**
     * Returns the name of the route
     *
     * @return string
     */
    public function getFeedItemRouteName()
    {
        if ($this->getType() == ObjectTypeConstants::TYPE_DEBATE) {
            return 'DebateDetail';
        } elseif ($this->getType() == ObjectTypeConstants::TYPE_REACTION) {
            return 'ReactionDetail';
        } else {
            throw new InconsistentDataException(sprintf('Object type %s does not managed in feed.', $this->getType()));
        }
    }

    /**
     * Return an array with the parameters that are required for the route
     *
     * @return array
     */
    public function getFeedItemRouteParameters()
    {
        return array('slug' => $this->slug);
    }

    /**
     * Returns the anchor that will be appended to the router-generated url
     *
     * @return string|empty
     */
    public function getFeedItemUrlAnchor()
    {
        return null;
    }

    /**
     * Returns a custom media field
     *
     * @return string
     */
    public function getFeedMediaItem()
    {
        $schemeAndHttpHost = 'https://www.politizr.com';
        if ($this->requestStack) {
            $schemeAndHttpHost = $this->requestStack->getCurrentRequest()->getSchemeAndHttpHost();
        }

        $defaultMediaItem = array(
            'type'   => 'image/jpeg',
            'length' => 1200,
            'value'  => $schemeAndHttpHost . '/bundles/politizrfront/images/share_rss.jpg'
        );

        $fileName = $this->getFileName();
        if (!$fileName) {
            return $defaultMediaItem;
        } else {
            if ($this->getType() == ObjectTypeConstants::TYPE_DEBATE) {
                $uploadWebPath = PathConstants::DEBATE_UPLOAD_WEB_PATH;
                $absolutePath = __DIR__ . '/..' . PathConstants::DEBATE_UPLOAD_PATH;
            } elseif ($this->getType() == ObjectTypeConstants::TYPE_REACTION) {
                $uploadWebPath = PathConstants::REACTION_UPLOAD_WEB_PATH;
                $absolutePath = __DIR__ . '/..' . PathConstants::REACTION_UPLOAD_PATH;
            } else {
                return $defaultMediaItem;
            }
        }

        $webPath = $uploadWebPath.$fileName;
        $absolutePath = $absolutePath.$fileName;

        $width = 500;
        if (file_exists($absolutePath) && $size = getimagesize($absolutePath)) {
            $width = $size[0];
        } else {
            return $defaultMediaItem;
        }

        $mimeType = 'image/jpeg';
        if ($type = exif_imagetype($absolutePath)) {
            $mimeType = image_type_to_mime_type($type);
        } else {
            return $defaultMediaItem;
        }

        $mediaItem = array(
            'type'   => $mimeType,
            'length' => $width,
            'value'  => $schemeAndHttpHost . $webPath
        );

        return $mediaItem;
    }
}
