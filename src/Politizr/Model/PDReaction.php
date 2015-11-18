<?php

namespace Politizr\Model;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\File\File;

use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Count;

use FOS\ElasticaBundle\Transformer\HighlightableModelInterface;

use StudioEcho\Lib\StudioEchoUtils;

use Politizr\Exception\InconsistentDataException;

use Politizr\Constant\ObjectTypeConstants;
use Politizr\Constant\PathConstants;
use Politizr\Constant\TagConstants;

use Politizr\Model\om\BasePDReaction;

/**
 * Reaction model object
 *
 * @author Lionel Bouzonville
 */
class PDReaction extends BasePDReaction implements PDocumentInterface, ContainerAwareInterface, HighlightableModelInterface
{
    // simple upload management
    public $uploadedFileName;

    // elastica search
    private $elasticaPersister;
    private $highlights;

    /**
     *
     * @return string
     */
    public function __toString()
    {
        $title = $this->getTitle();

        if (!empty($title)) {
            return $this->getTitle();
        }

        return 'Pas de titre';
    }

    /**
     * @see PDocumentInterface::getType
     */
    public function getType()
    {
        return ObjectTypeConstants::TYPE_REACTION;
    }


    /**
     * @see PDocumentInterface::isDisplayed
     */
    public function isDisplayed()
    {
        return $this->getOnline() && $this->getPublished();
    }

    /**
     * Return constraints to be applied before publication
     *
     * @return Collection
     */
    public function getPublishConstraints()
    {
        $collectionConstraint = new Collection(array(
            'title' => array(
                new NotBlank(['message' => 'Le titre ne doit pas être vide']),
                new Length(['max' => 100, 'maxMessage' => 'Le titre doit contenir {{ limit }} caractères maximum.']),
            ),
            'description' => array(
                new NotBlank(['message' => 'La description ne doit pas être vide']),
                new Length(['min' => 140, 'minMessage' => 'Le corps de la publication doit contenir {{ limit }} caractères minimum.']),
            ),
            'geoTags' => new Count(['min' => 1, 'minMessage' => 'Saisissez au moins {{ limit }} thématique géographique parmi les départements, les régions, "France", "Europe" ou "Monde".']),
            'allTags' => new Count(['min' => 3, 'minMessage' => 'Saisissez au moins {{ limit }} thématiques au total.']),
        ));

        return $collectionConstraint;
    }

    /**
     * @param ContainerInterface $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        if ($container) {
            $this->elasticaPersister = $container->get('fos_elastica.object_persister.politizr.p_d_reaction');
        }
    }

    /**
     *
     */
    public function getHighlights()
    {
        return $this->highlights;
    }

    /**
     * Set ElasticSearch highlight data.
     *
     * @param array $highlights array of highlight strings
     */
    public function setElasticHighlights(array $highlights)
    {
        $this->highlights = $highlights;
    }


    /**
     * @todo: gestion d'une exception spécifique à ES
     *
     */
    public function postInsert(\PropelPDO $con = null)
    {
        if ($this->elasticaPersister) {
            if ($this->isIndexable()) {
                // $this->elasticaPersister->insertOne($this);
            }
        } else {
            throw new \Exception('Indexation service not found');
        }
    }

    /**
     * @todo: gestion d'une exception spécifique à ES
     *
     */
    public function postUpdate(\PropelPDO $con = null)
    {
        if ($this->elasticaPersister) {
            if ($this->isIndexable()) {
                // $this->elasticaPersister->insertOne($this);
            }
        } else {
            throw new \Exception('Indexation service not found');
        }
    }

    /**
     * @todo: gestion d'une exception spécifique à ES
     *
     */
    public function postDelete(\PropelPDO $con = null)
    {
        if ($this->elasticaPersister) {
            // $this->elasticaPersister->deleteOne($this);
        } else {
            throw new \Exception('Indexation service not found');
        }
    }

    /**
     * Indexation process call to know if object is indexable
     *
     * @return boolean
     */
    public function isIndexable()
    {
        return $this->getOnline() && $this->getPublished();
    }

    /**
     * Override to manage accented characters
     * @return string
     */
    protected function createRawSlug()
    {
        $toSlug =  StudioEchoUtils::transliterateString($this->getTitle());
        $slug = $this->cleanupSlugPart($toSlug);
        return $slug;
    }

    /**
     * Manage publisher information
     *
     * @param \PropelPDO $con
     */
    public function preSave(\PropelPDO $con = null)
    {
        $publisher = $this->getPUser();
        if ($publisher) {
            $this->setPublishedBy($publisher->getFullName());
        } else {
            // @todo label in constant
            $this->setPublishedBy('Auteur inconnu');
        }

        return parent::preSave($con);
    }

    /**
     * Compute a reaction file name
     * @todo not used for the moment
     *
     * @return string
     */
    public function computeFileName()
    {
        $fileName = 'politizr-reaction-' . StudioEchoUtils::randomString();

        return $fileName;
    }
 
    /* ######################################################################################################## */
    /*                                                  DEBATE                                                  */
    /* ######################################################################################################## */

    /**
     * Renvoit le débat associé à la réaction
     *
     * @return     PDDebate     Objet débat
     */
    public function getDebate()
    {
        return parent::getPDDebate();
    }

    /* ######################################################################################################## */
    /*                                                      TAGS                                                */
    /* ######################################################################################################## */

    /**
     * Reaction's array tags / geo tags world, europe, france, regions, departments
     * - used by publish constraints
     *
     * @return array[string]
     */
    public function getWorldToDepartmentGeoArrayTags()
    {
        $query = PTagQuery::create()
            ->select('Title')
            ->filterIfTypeId(TagConstants::TAG_TYPE_GEO)
            ->filterIfOnline(true)
            ->where('p_tag.id <= ?', TagConstants::TAG_GEO_DEPARTMENT_LAST_ID)
            ->orderByTitle()
            ->setDistinct();

        return parent::getPTags($query)->toArray();
    }

    /**
     * Reaction's array tags
     * - used by elastica indexation
     *
     * @return array[string]
     */
    public function getArrayTags($tagTypeId = null, $online = true)
    {
        $query = PTagQuery::create()
            ->select('Title')
            ->filterIfTypeId($tagTypeId)
            ->filterIfOnline($online)
            ->orderByTitle()
            ->setDistinct();

        return parent::getPTags($query)->toArray();
    }

    /**
     * @see PDocumentInterface::getTags
     */
    public function getTags($tagTypeId = null, $online = true)
    {
        $query = PTagQuery::create()
            ->filterIfTypeId($tagTypeId)
            ->filterIfOnline($online)
            // ->orderByTitle()
            ->setDistinct();

        return parent::getPTags($query);
    }

    /* ######################################################################################################## */
    /*                                                  COMMENTS                                                */
    /* ######################################################################################################## */

    /**
     * @see ObjectTypeConstants::countComments
     */
    public function countComments($online = true, $paragraphNo = null)
    {
        $query = PDRCommentQuery::create()
            ->filterIfOnline($online)
            ->filterIfParagraphNo($paragraphNo);
        
        return parent::countPDRComments($query);
    }

    /**
     * @see ObjectTypeConstants::getComments
     */
    public function getComments($online = true, $paragraphNo = null, $orderBy = null)
    {
        $query = PDRCommentQuery::create()
            ->filterIfOnline($online)
            ->filterIfParagraphNo($paragraphNo)
            ->_if($orderBy)
                ->orderBy($orderBy[0], $orderBy[1])
            ->_else()
                ->orderBy('p_d_r_comment.created_at', 'desc')
            ->_endif();

        return parent::getPDRComments($query);
    }
    
    /* ######################################################################################################## */
    /*                                                   USERS                                                  */
    /* ######################################################################################################## */

    /**
     * @see parent::getPUser
     */
    public function getUser()
    {
        return $this->getPUser();
    }

    /**
     * @see PDocumentInterface::isOwner
     */
    public function isOwner($userId)
    {
        if ($this->getPUserId() == $userId) {
            return true;
        }

        return false;
    }

    /* ######################################################################################################## */
    /*                                               REACTIONS                                                  */
    /* ######################################################################################################## */

    /**
     * Nested tree children
     *
     * @param boolean $online
     * @param boolean $published
     * @return PropelCollection[PDReaction]
     */
    public function getChildrenReactions($online = null, $published = null)
    {
        $query = PDReactionQuery::create()
            ->filterIfOnline($online)
            ->filterIfPublished($published);

        return parent::getChildren($query);
    }

    /**
     * Nested tree descendants
     *
     * @param boolean $online
     * @param boolean $published
     * @return PropelCollection[PDReaction]
     */
    public function getDescendantsReactions($online = null, $published = null)
    {
        $query = PDReactionQuery::create()
            ->filterIfOnline($online)
            ->filterIfPublished($published);
                    
        return parent::getDescendants($query);
    }

    /**
     * Reaction's descendant count
     *
     * @param boolean $online
     * @param boolean $published
     * @return int
     */
    public function countDescendantsReactions($online = null, $published = null)
    {
        $query = PDReactionQuery::create()
            ->filterIfOnline($online)
            ->filterIfPublished($published)
            ->orderByPublishedAt('desc');

        return parent::countDescendants($query);
    }

    /**
     * Reaction's children count
     *
     * @param boolean $online
     * @param boolean $published
     * @return int
     */
    public function countChildrenReactions($online = null, $published = null)
    {
        $query = PDReactionQuery::create()
            ->filterIfOnline($online)
            ->filterIfPublished($published)
            ->orderByPublishedAt('desc');

        return parent::countChildren($query);
    }

    /**
     * @see PDReaction::countChildrenReactions
     */
    public function countReactions($online = null, $published = null)
    {
        return $this->countChildrenReactions($online, $published);
    }
}
