<?php

namespace Politizr\Model;

use Politizr\Model\om\BasePDDebate;


use Politizr\Model\PUser;
use Politizr\Model\PUType;
use Politizr\Model\PUStatus;

use Politizr\Model\PUserQuery;
use Politizr\Model\PDReactionQuery;

class PDDebate extends BasePDDebate
{
	// ************************************************************************************ //
	//										CONSTANTES
	// ************************************************************************************ //
  	const UPLOAD_PATH = '/../../../web/uploads/documents/';
  	const UPLOAD_WEB_PATH = '/uploads/documents/';

	/*************** ADMIN GENERATOR VIRTUAL FIELDS HACK **************************/

	/**
	 *
	 */
	public function __toString() {
		return $this->getTitle();
	}

	/**
	 *	Getter magique pour gérer l'héritage PDocument
	 */
    public function __get($name)
    {
    	$name = \Symfony\Component\DependencyInjection\Container::camelize($name);
        return parent::__call('get'.ucfirst($name), array());
    }

	/**
	 *	Setter magique pour gérer l'héritage PDocument
	 */
    public function __set($name, $value)
    {
    	$name = \Symfony\Component\DependencyInjection\Container::camelize($name);
        return parent::__call('set'.ucfirst($name), array($value));
    }

	// ******************* SIMPLE UPLOAD MANAGEMENT **************** //
	// https://github.com/avocode/FormExtensions/blob/master/Resources/doc/single-upload/overview.md

	// Colonnes virtuelles / fichiers
    public $uploadedFileName;
    public function setUploadedFileName($uploadedFileName) {
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
	 *	Surcharge pour gérer la suppression physique.
	 */
	public function setFileName($v)
	{
		if (!$v) {
			$this->removeUpload();
		}
		parent::setFileName($v);
	}

	/**
	 *  Surcharge pour gérer la suppression physique.
	 */
	public function postDelete(\PropelPDO $con = null)
	{
		$this->removeUpload();
	}

	/**
	 * 	Suppression physique des fichiers.
	 */
	public function removeUpload($uploadedFileName = true)
	{
		if ($uploadedFileName && $this->file_name && file_exists(__DIR__ . PDDebate::UPLOAD_PATH . $this->file_name)) {
		  	unlink(__DIR__ . PDDebate::UPLOAD_PATH . $this->file_name);
		}
	}
	

	// ************************************************************************************ //
	//										METHODES
	// ************************************************************************************ //

    // *****************************    TAGS   ************************* //

	/**
	 * Renvoit les tags associés au débat
	 *
	 * @return PropelCollection d'objets PTag
	 */
	public function getPTags($ptTagTypeId = null, $online = true) {
		$query = PTagQuery::create()
                    ->_if($ptTagTypeId)
                        ->filterByPTTagTypeId($ptTagTypeId)
                    ->_endif()
                    ->filterByOnline($online)
                    ->setDistinct()
                    ;

		return parent::getPddTaggedTPTags($query);
	}


    // *****************************    DOCUMENTS   ************************* //

	/**
	 *	Renvoit les réactions associées en mode arbre / nested set
	 *
	 * @return PropelCollection d'objets PDReaction
	 */
	public function getTreeReactions($online = false, $published = false) {
		$treeReactions = PDReactionQuery::create()
					->_if($online)
						->filterByOnline(true)
					->_endif()
					->_if($published)
						->filterByPublished(true)
					->_endif()
					->filterByPDDebateId($this->getId())
					->findTree($this->getId())
					;

		return $treeReactions;
	}


    // *****************************    FOLLOWERS   ************************* //


	/**
	 *	Renvoit le nombre de followers du débat.
	 *
	 * @param 	$puStatusId 	integer 	Filtrage par rapport au status
	 * @param 	$puTypeId 		integer 	Filtrage par rapport au type
	 *
	 * @return 	integer 	Nombre de followers
	 */
	public function getFollowers($puStatusId = PUStatus::STATUS_ACTIV, $puTypeId = null) {
		$query = PUserQuery::create()
					->_if($puStatusId)
						->filterByPUStatusId($puStatusId)
					->_endif()
					->_if($puTypeId)
						->filterByPUTypeId($puTypeId)
					->_endif()
					->filterByOnline(true)
					->setDistinct();
		
		return parent::getPuFollowDdPUsers($query);
	}

	/**
	 *	Renvoit le nombre de followers du débat.
	 *
	 * @param 	$puStatusId 	integer 	Filtrage par rapport au status
	 * @param 	$puTypeId 		integer 	Filtrage par rapport au type
	 *
	 * @return 	integer 	Nombre de followers
	 */
	public function countFollowers($puStatusId = PUStatus::STATUS_ACTIV, $puTypeId = null) {
		$query = PUserQuery::create()
					->_if($puStatusId)
						->filterByPUStatusId($puStatusId)
					->_endif()
					->_if($puTypeId)
						->filterByPUTypeId($puTypeId)
					->_endif()
					->filterByOnline(true)
					->setDistinct();
		
		return parent::countPuFollowDdPUsers($query);
	}

	/**
     * Renvoie les followers qualifiés (élus)
     *
     * @return     PropelObjectCollection PUser[] List
	 */
	public function getFollowersQ() {
		$pUsers = $this->getFollowers(PUStatus::STATUS_ACTIV, PUType::TYPE_QUALIFIE);

		return $pUsers;
	}

    /**
     * Nombre de followers qualifiés (élus)
     *
     * @return     integer
     */
    public function countFollowersQ() {
        return $this->countFollowers(PUStatus::STATUS_ACTIV, PUType::TYPE_QUALIFIE);
    }

	/**
	 * Renvoie les followers citoyens
	 *
     * @return     PropelObjectCollection PUser[] List
	 */
	public function getFollowersC() {
		$pUsers = $this->getFollowers(PUStatus::STATUS_ACTIV, PUType::TYPE_CITOYEN);

		return $pUsers;
	}

    /**
     * Nombre de followers citoyens
     *
     * @return     integer
     */
    public function countFollowersC() {
        return $this->countFollowers(PUStatus::STATUS_ACTIV, PUType::TYPE_CITOYEN);
    }


}
