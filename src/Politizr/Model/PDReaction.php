<?php

namespace Politizr\Model;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

use FOS\ElasticaBundle\Transformer\HighlightableModelInterface;

use Politizr\Exception\InconsistentDataException;

use Politizr\Model\om\BasePDReaction;

class PDReaction extends BasePDReaction implements ContainerAwareInterface, HighlightableModelInterface
{
    // ************************************************************************************ //
    //                                        CONSTANTES
    // ************************************************************************************ //
      const UPLOAD_PATH = '/../../../web/uploads/documents/';
      const UPLOAD_WEB_PATH = '/uploads/documents/';


      /**
       *
       */
    public function getClassName()
    {
        return PDocument::TYPE_REACTION;
    }

    // *****************************  ELASTIC SEARCH  ****************** //
       private $elasticaPersister;
    private $highlights;

       /**
        *
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
     * TODO: gestion d'une exception spécifique à ES
     *
     */
    public function postInsert(\PropelPDO $con = null)
    {
        if ($this->elasticaPersister) {
            if ($this->isIndexable()) {
                $this->elasticaPersister->insertOne($this);
            }
        } else {
            throw new \Exception('Service d\'indexation non dispo');
        }
    }

    /**
     * TODO: gestion d'une exception spécifique à ES
     *
     */
    public function postUpdate(\PropelPDO $con = null)
    {
        if ($this->elasticaPersister) {
            if ($this->isIndexable()) {
                $this->elasticaPersister->insertOne($this);
            }
        } else {
            throw new \Exception('Service d\'indexation non dispo');
        }
    }

    /**
     * TODO: gestion d'une exception spécifique à ES
     *
     */
    public function postDelete(\PropelPDO $con = null)
    {
        if ($this->elasticaPersister) {
            $this->elasticaPersister->deleteOne($this);
        } else {
            throw new \Exception('Service d\'indexation non dispo');
        }

        // + gestion de l'upload
        $this->removeUpload();
    }


    /**
     *  Appel au moment de l'indexation pour vérifier que l'objet est indexable
     *
     *  @return boolean
     */
    public function isIndexable()
    {
        return $this->getOnline() && $this->getPublished();
    }



    // *****************************  OBJET / STRING  ****************** //

    /**
     *
     */
    public function __toString()
    {
        return $this->getTitle();
    }

     /**
     * Override to manage accented characters
     * @return string
     */
    protected function createRawSlug()
    {
        $toSlug =  \StudioEcho\Lib\StudioEchoUtils::transliterateString($this->getTitle());
        $slug = $this->cleanupSlugPart($toSlug);
        return $slug;
    }

    /**
     *    Surcharge pour gérer la date et l'auteur de la publication.
     *
     *
     */
    public function preSave(\PropelPDO $con = null)
    {
        // TODO > en commentaire pour avoir des fixtures variées (à supprimer)
        // if ($this->published && ($this->isNew() || in_array(PDReactionPeer::PUBLISHED, $this->modifiedColumns))) {
        //     $this->setPublishedAt(time());
        // } else {
        //     $this->setPublishedAt(null);
        // }

        // User associé
        // TODO > chaine en dur
        $publisher = $this->getPUser();
        if ($publisher) {
            $this->setPublishedBy($publisher->getFirstname().' '.$publisher->getName());
        } else {
            $this->setPublishedBy('Auteur inconnu');
        }

        return parent::preSave($con);
    }

    /**
     * Surcharge pour gérer les conflits entre les behaviors Archivable et ConcreteInheritance
     * https://github.com/propelorm/Propel/issues/366
     *
     * @param PropelPDO $con Optional connection object
     *
     * @return     PDDebate The current object (for fluent API support)
     */
    public function deleteWithoutArchive(PropelPDO $con = null)
    {
        $this->archiveOnDelete = false;
        $this->getParentOrCreate($con)->archiveOnDelete = false;

        return $this->delete($con);
    }


    // ******************* SIMPLE UPLOAD MANAGEMENT **************** //
    // https://github.com/avocode/FormExtensions/blob/master/Resources/doc/single-upload/overview.md

    // Colonnes virtuelles / fichiers
    public $uploadedFileName;
    public function setUploadedFileName($uploadedFileName)
    {
        $this->uploadedFileName = $uploadedFileName;
    }

    /**
     *
     */
    public function getUploadedFileNameWebPath()
    {
        return PDReaction::UPLOAD_WEB_PATH . $this->file_name;
    }
    
    /**
     *
     */
    public function getUploadedFileName()
    {
        // inject file into property (if uploaded)
        if ($this->file_name) {
            return new \Symfony\Component\HttpFoundation\File\File(
                __DIR__ . PDReaction::UPLOAD_PATH . $this->file_name
            );
        }

        return null;
    }


    /**
     *  Gestion physique de l'upload
     */
    public function upload($file = null)
    {
        if (null === $file) {
              return;
        }

        // Extension et nom de fichier
        $extension = $file->guessExtension();
        if (!$extension) {
              $extension = 'bin';
        }
        $fileName = 'p-d-r-' . \StudioEcho\Lib\StudioEchoUtils::randomString() . '.' . $extension;

        // move takes the target directory and then the target filename to move to
        $fileUploaded = $file->move(__DIR__ . PDReaction::UPLOAD_PATH, $fileName);

        // file_name
        return $fileName;
    }

    /**
     *    Surcharge pour gérer la suppression physique.
     */
    public function setFileName($v)
    {
        if (!$v) {
            $this->removeUpload();
        }
        parent::setFileName($v);
    }

    /**
     *     Suppression physique des fichiers.
     */
    public function removeUpload($uploadedFileName = true)
    {
        if ($uploadedFileName && $this->file_name && file_exists(__DIR__ . PDReaction::UPLOAD_PATH . $this->file_name)) {
              unlink(__DIR__ . PDReaction::UPLOAD_PATH . $this->file_name);
        }
    }
    


    // *****************************  DEBAT / REACTION  ****************** //

    /**
     * Renvoit le document associé à la réaction
     *
     * @return     PDDebate     Objet débat
     */
    public function getDocument()
    {
        return parent::getPDocument();
    }

    /**
     * Renvoit le débat associé à la réaction
     *
     * @return     PDDebate     Objet débat
     */
    public function getDebate()
    {
        return parent::getPDDebate();
    }

    // *****************************    USERS   ************************* //

    /**
     * Renvoie les abonnés qualifiés - au débat associé à la réaction courante.
     *
     * @return     PropelObjectCollection PUser[] List
     */
    public function getFollowersQ()
    {
        $pdDebate = parent::getPDDebate();

        $pUsers = null;
        if ($pdDebate) {
            $pUsers = $this->getPDDebate()->getFollowersQ();
        }

        return $pUsers;
    }

    /**
     * Renvoie les abonnés citoyens - au débat associé à la réaction courante.
     *
     * @return     PropelObjectCollection PUser[] List
     */
    public function getFollowersC()
    {
        $pdDebate = parent::getPDDebate();

        $pUsers = null;
        if ($pdDebate) {
            $pUsers = $this->getPDDebate()->getFollowersC();
        }

        return $pUsers;
    }



    // *****************************    REACTIONS   ************************* //

    /**
     * Renvoit les réactions enfants associées à la réaction courante.
     *
     *
     * @return PropelCollection d'objets PDReaction
     */
    public function getChildrenReactions($online = null, $published = null)
    {
        $query = PDReactionQuery::create()
                    ->onlinePublished($online, $published)
                    ;

        return parent::getChildren($query);
    }

    /**
     * Renvoit les réactions descendantes associées à la réaction courante.
     *
     *
     * @return PropelCollection d'objets PDReaction
     */
    public function getDescendantsReactions($online = null, $published = null)
    {
        $query = PDReactionQuery::create()
                    ->onlinePublished($online, $published)
                    ;
                    
        return parent::getDescendants($query);
    }

    /**
     * Renvoit le nombre de réactions publiées associées à la réaction courante.
     *
     * @param integer $online Réactions en ligne
     * @param integer $published Réactions publiées
     *
     * @return PropelCollection d'objets PDReaction
     */
    public function countChildrenReactions($online = null, $published = null)
    {
        $query = PDReactionQuery::create()
                    ->onlinePublished($online, $published)
                    ->orderByPublishedAt(\Criteria::DESC);

        return parent::countChildren($query);
    }

    /**
     * @see countChildrenReactions
     */
    public function countReactions($online = null, $published = null)
    {
        return $this->countChildrenReactions($online, $published);
    }



    // *****************************    USERS   ************************* //
    
    /**
     *
     */
    public function getUser()
    {
        return $this->getPUser();
    }
}
