<?php

namespace Politizr\Model;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

use Politizr\Model\om\BasePDDebate;

use FOS\ElasticaBundle\Transformer\HighlightableModelInterface;

/**
 * Debate model object
 *
 * @author Lionel Bouzonville
 */
class PDDebate extends BasePDDebate implements PDocumentInterface, ContainerAwareInterface, HighlightableModelInterface
{
    // ************************************************************************************ //
    //                                        CONSTANTES
    // ************************************************************************************ //
    const UPLOAD_PATH = '/../../../web/uploads/documents/';
    const UPLOAD_WEB_PATH = '/uploads/documents/';

    // *****************************  ELASTIC SEARCH  ****************** //
    private $elasticaPersister;
    private $highlights;

       /**
        *
        */
    public function setContainer(ContainerInterface $container = null)
    {
        if ($container) {
            $this->elasticaPersister = $container->get('fos_elastica.object_persister.politizr.p_d_debate');
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
                $this->elasticaPersister->insertOne($this);
            }
        } else {
            throw new \Exception('Service d\'indexation non dispo');
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
                $this->elasticaPersister->insertOne($this);
            }
        } else {
            throw new \Exception('Service d\'indexation non dispo');
        }
    }

    /**
     * @todo: gestion d'une exception spécifique à ES
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
     *     Renvoit la liste des tags associés au débat sous forme de tableau de chaines.
     *
     *    @return string
     */
    public function getArrayTags($tagTypeId = null, $online = true)
    {
        $query = PTagQuery::create()
                    ->select('Title')
                    ->filterByOnline(true)
                    ->setDistinct()
                    ;

        $tags = parent::getPTags($query)->toArray();
        return $tags;
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
        // @todo > en commentaire pour avoir des fixtures variées (à supprimer)
        // if ($this->published && ($this->isNew() || in_array(PDDebatePeer::PUBLISHED, $this->modifiedColumns))) {
        //     $this->setPublishedAt(time());
        // } else {
        //     $this->setPublishedAt(null);
        // }

        // User associé
        // @todo > chaine en dur
        $publisher = $this->getPUser();
        if ($publisher) {
            $this->setPublishedBy($publisher->getFirstname().' '.$publisher->getName());
        } else {
            $this->setPublishedBy('Auteur inconnu');
        }

        return parent::preSave($con);
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
        return PDDebate::UPLOAD_WEB_PATH . $this->file_name;
    }
    
    /**
     *
     */
    public function getUploadedFileName()
    {
        // inject file into property (if uploaded)
        if ($this->file_name) {
            return new \Symfony\Component\HttpFoundation\File\File(
                __DIR__ . PDDebate::UPLOAD_PATH . $this->file_name
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
        $fileName = 'p-d-d-' . \StudioEcho\Lib\StudioEchoUtils::randomString() . '.' . $extension;

        // move takes the target directory and then the target filename to move to
        $fileUploaded = $file->move(__DIR__ . PDDebate::UPLOAD_PATH, $fileName);

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
        if ($uploadedFileName && $this->file_name && file_exists(__DIR__ . PDDebate::UPLOAD_PATH . $this->file_name)) {
              unlink(__DIR__ . PDDebate::UPLOAD_PATH . $this->file_name);
        }
    }
    

    // ************************************************************************************ //
    //                                        METHODES
    // ************************************************************************************ //

    /**
     * @see PDocumentInterface::getType
     */
    public function getType()
    {
        return PDocumentInterface::TYPE_DEBATE;
    }

    // *****************************    TAGS   ************************* //

    /**
     * Renvoit les tags associés au débat
     *
     * @return PropelCollection d'objets PTag
     */
    public function getTags($tagTypeId = null, $online = true)
    {
        $query = PTagQuery::create()
                    ->_if($tagTypeId)
                        ->filterByPTTagTypeId($tagTypeId)
                    ->_endif()
                    ->filterByOnline($online)
                    ->_if(null === $tagTypeId)
                        ->orderByPTTagTypeId()
                    ->_endif()
                    ->orderByTitle()
                    ->setDistinct()
                    ;

        return parent::getPTags($query);
    }


    // *****************************    COMMENTS    ************************** //

    /**
     * @see PDocumentInterface::countComments
     */
    public function countComments($online = true, $paragraphNo = null)
    {
        $query = PDDCommentQuery::create()
                    ->filterByOnline($online)
                    ->_if($paragraphNo)
                        ->filterByParagraphNo($paragraphNo)
                    ->_endif()
                    ;
        
        return parent::countPDDComments($query);
    }

    /**
     * @see PDocumentInterface::getComments
     */
    public function getComments($online = true, $paragraphNo = null, $orderBy = null)
    {
        $query = PDDCommentQuery::create()
                    ->filterByOnline($online)
                    ->_if($paragraphNo)
                        ->filterByParagraphNo($paragraphNo)
                    ->_else()
                        ->filterByParagraphNo(0)
                            ->_or()
                        ->filterByParagraphNo(null)
                    ->_endif()
                    ->_if($orderBy)
                        ->orderBy($orderBy[0], $orderBy[1])
                    ->_else()
                        ->orderBy('p_d_d_comment.created_at', 'desc')
                    ->_endif()
                    ;

        return parent::getPDDComments($query);
    }
    


    // *****************************    NESTED SET   ************************* //

    /**
     * Renvoit les réactions associées en mode arbre / nested set
     *
     * @param boolean $online
     * @param boolean $published
     * @return PropelCollection PDReaction
     */
    public function getTreeReactions($online = null, $published = null)
    {
        $treeReactions = PDReactionQuery::create()
                    ->filterByTreeLevel(0, \Criteria::NOT_EQUAL)    // Exclusion du root node
                    ->onlinePublished($online, $published)
                    ->filterByPDDebateId($this->getId())
                    ->findTree($this->getId())
                    ;

        return $treeReactions;
    }

    /**
     * Renvoit les réactions filles immédiates en mode arbre / nested set
     *
     * @param boolean $online
     * @param boolean $published
     * @return PropelCollection PDReaction
     */
    public function getChildrenReactions($online = null, $published = null)
    {
        $rootNode = PDReactionQuery::create()->findRoot($this->getId());
        $children = $rootNode->getChildrenReactions($online, $published);

        return $children;
    }

    /**
     * Renvoit le nombre de réactions associées au débat
     *
     * @param boolean $online
     * @param boolean $published
     * @return int
     */
    public function countReactions($online = null, $published = null)
    {
        $query = PDReactionQuery::create()
                    ->filterByTreeLevel(0, \Criteria::NOT_EQUAL)    // Exclusion du root node
                    ->_if(null !== $online)
                        ->filterByOnline(true)
                    ->_endif()
                    ->_if(null !== $published)
                        ->filterByPublished(true)
                    ->_endif()
                    ;

        return parent::countPDReactions($query);
    }

    /**
     *    Renvoit la dernière réaction associée au débat
     *
     *     @return     PDReaction
     */
    public function getLastReaction($treeLevel = false, $online = false, $published = false)
    {
        return PDReactionQuery::create()
                    ->_if($treeLevel)
                        ->filterByTreeLevel($treeLevel)
                    ->_endif()
                    ->_if($online)
                        ->filterByOnline(true)
                    ->_endif()
                    ->_if($published)
                        ->filterByPublished(true)
                    ->_endif()
                    ->filterByPDDebateId($this->getId())
                    ->orderByCreatedAt(\Criteria::DESC)
                    ->findOne();
    }


    // *****************************    FOLLOWERS   ************************* //


    /**
     * Renvoit les followers du débat.
     *
     * @param     $qualified        boolean     Filtrage par rapport à la qualification
     * @param     $notifReaction    boolean     Filtrage par rapport à la souscription à la notif des réactions
     *
     * @return    PropelCollection  Liste des followers
     */
    public function getFollowers($qualified = null, $notifReaction = null)
    {
        $query = PUserQuery::create()
                    ->_if(null !== $qualified)
                        ->filterByQualified($qualified)
                    ->_endif()
                    ->_if($notifReaction)
                        ->usePuFollowDdPUserQuery()
                            ->filterByNotifReaction(true)
                        ->endUse()
                    ->_endif()
                    ->filterByOnline(true)
                    ->setDistinct();
        
        return parent::getPuFollowDdPUsers($query);
    }

    /**
     * Renvoit les followers filtrés par souscription à la notif des réactions.
     *
     * @return    PropelCollection  Liste des followers
     */
    public function getNotifReactionFollowers()
    {
        return $this->getFollowers(null, true);
    }

    /**
     *    Renvoit le nombre de followers du débat.
     *
     * @param     $puStatusId     integer     Filtrage par rapport au status
     * @param     $qualified         boolean     Filtrage par rapport à la qualification
     *
     * @return     integer     Nombre de followers
     */
    public function countFollowers($qualified = false)
    {
        $query = PUserQuery::create()
                    ->filterByQualified($qualified)
                    ->filterByOnline(true)
                    ->setDistinct();
        
        return parent::countPuFollowDdPUsers($query);
    }

    /**
     * Renvoie les followers qualifiés (élus)
     *
     * @return     PropelObjectCollection PUser[] List
     */
    public function getFollowersQ()
    {
        $pUsers = $this->getFollowers(true);

        return $pUsers;
    }

    /**
     * Nombre de followers qualifiés (élus)
     *
     * @return     integer
     */
    public function countFollowersQ()
    {
        return $this->countFollowers(true);
    }

    /**
     * Renvoie les followers citoyens
     *
     * @return     PropelObjectCollection PUser[] List
     */
    public function getFollowersC()
    {
        $pUsers = $this->getFollowers(false);

        return $pUsers;
    }

    /**
     * Nombre de followers citoyens
     *
     * @return     integer
     */
    public function countFollowersC()
    {
        return $this->countFollowers(false);
    }


    // *****************************    USERS   ************************* //
    
    /**
     * @see PDocumentInterface::isOwner
     */
    public function isOwner($userId)
    {
        if ($this->getPUserId() == $userId) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @see getPUser
     */
    public function getUser()
    {
        return $this->getPUser();
    }

    /**
     * Check si le <user id> passé en argument est l'auteur du débat courant.
     *
     * @param integer $userId
     * @return boolean
     */
    public function isUserId($userId)
    {
        $user = $this->getUser();

        if ($user && $userId === $user->getId()) {
            return true;
        }

        return false;
    }

    /**
     * Check si le <user id> passé en argument est abonné au débat courant.
     *
     * @param integer $userId
     * @return boolean
     */
    public function isFollowedByUserId($userId)
    {
        $followers = PUFollowDDQuery::create()
            ->filterByPUserId($userId)
            ->filterByPDDebateId($this->getId())
            ->count();

        if ($followers > 0) {
            return true;
        }

        return false;
    }

    // *****************************    NOTIFICATIONS    ************************* //

    /**
     * Renvoit vrai / faux si le follower en argument veut être notifié des MAJ du débat courant
     * suivant le contexte entré en argument.
     *
     * @param $userFollowerId   integer
     * @param $context          string
     *
     * @return boolean
     */
    public function isNotified($userId, $context = 'reaction')
    {
        $puFollowU = PUFollowDDQuery::create()
            ->filterByPUserId($userId)
            ->filterByPDDebateId($this->getId())
            ->findOne();

        if ($context == 'reaction' && $puFollowU && $puFollowU->getNotifReaction()) {
            return true;
        }

        return false;
    }
}
