<?php

namespace Politizr\Model;

use Politizr\Model\om\BasePDDebate;


use Politizr\Model\PUser;
use Politizr\Model\PUType;
use Politizr\Model\PUStatus;

use Politizr\Model\PUserQuery;
use Politizr\Model\PDReactionQuery;
use Politizr\Model\PDDCommentQuery;

class PDDebate extends BasePDDebate
{
	// ************************************************************************************ //
	//										CONSTANTES
	// ************************************************************************************ //
  	const UPLOAD_PATH = '/../../../web/uploads/documents/';
  	const UPLOAD_WEB_PATH = '/uploads/documents/';

	/*************** ADMIN GENERATOR VIRTUAL FIELDS HACK **************************/

	public function getBlockReactions() {
	}
	public function getBlockComments() {
	}
	public function getBlockTagsGeo() {
	}
	public function getBlockTagsTheme() {
	}
	public function getBlockFollowersQ() {
	}
	public function getBlockFollowersC() {
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
	 *	Surcharge pour gérer la date et l'auteur de la publication.
	 *
	 *
	 */
    public function save(\PropelPDO $con = null)
    {
    	// Date publication
    	if ($this->published && in_array(PDDebatePeer::PUBLISHED, $this->modifiedColumns)) {
    		$this->setPublishedAt(time());
    	} else {
    		$this->setPublishedAt(null);
    	}

    	// User associé
    	// TODO: /!\ chaine en dur
		$publisher = $this->getPUser();
		if ($publisher) {
			$this->setPublishedBy($publisher->getFirstname().' '.$publisher->getName());
		} else {
			$this->setPublishedBy('Auteur inconnu');
		}

    	parent::save($con);
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
	
	/******************************************************************************/

	/**
	 * Renvoie les abonnés qualifiés
	 *
     * @return     PropelObjectCollection PUser[] List
	 */
	public function getPUserFollowersQ() {
		$query = PUserQuery::create()->filterByPUTypeId(PUType::TYPE_QUALIFIE);
		$pUsers = parent::getPuFollowDdPUsers($query);

		return $pUsers;
	}

	/**
	 * Renvoie les abonnés citoyens
	 *
     * @return     PropelObjectCollection PUser[] List
	 */
	public function getPUserFollowersC() {
		$query = PUserQuery::create()->filterByPUTypeId(PUType::TYPE_CITOYEN);
		$pUsers = parent::getPuFollowDdPUsers($query);

		return $pUsers;
	}



	// ************************************************************************************ //
	//										METHODES
	// ************************************************************************************ //

    // *****************************    TAGS   ************************* //

	/**
	 * Renvoit les tags associés au document
	 *
	 * @return PropelCollection d'objets PTag
	 */
	public function getPTags($ptTagTypeId = null, $online = true) {
		$query = PTagQuery::create()
                    ->_if($ptTagTypeId)
                        ->filterByPTTagTypeId($ptTagTypeId)
                    ->_endif()
                    ->filterByOnline($online)
                    ;

		return parent::getPddTaggedTPTags($query);
	}


    // *****************************    COMMENTAIRES   ************************* //

	/**
	 *	Renvoit le nombre de commentaires du débat.
	 *
	 * @return 	integer 	Nombre de commentaires
	 */
	public function countComments() {
		$query = PDDCommentQuery::create()
					->filterByOnline(true);
		
		return parent::countPDDComments($query);
	}


	/**
	 *	Renvoit les commentaires associés au débat
	 *
	 * @return PropelCollection d'objets PDDComment 
	 */
	public function getComments($online = true) {
		$query = PDDCommentQuery::create()
					->filterByOnline($online)
					->orderByNotePos(\Criteria::DESC);

		return parent::getPDDComments($query);
	}
	

	/**
	 *	Renvoit les commentaires généraux au débat (non associés à un paragraphe en particulier)
	 *
	 * @return PropelCollection d'objets PDDComment 
	 */
	public function getGlobalComments() {
		$query = PDDCommentQuery::create()
					->filterByOnline(true)
					->filterByParagraphNo(0)
						->_or()
					->filterByParagraphNo(null)
					->orderByNotePos(\Criteria::DESC);

		return parent::getPDDComments($query);
	}
	

	/**
	 *	Renvoit les commentaires du débat associés à un paragraphe
	 *
	 * @param $paragraphNo 	Numéro du paragraphe ou null pour tous
	 *
	 * @return PropelCollection d'objets PDDComment 
	 */
	public function getParagraphComments($paragraphNo = null) {
		$query = PDDCommentQuery::create()
					->filterByOnline(true)
					->_if($paragraphNo)
						->filterByParagraphNo($paragraphNo)
					->_else()
						->filterByParagraphNo(array('min' => 1))
					->_endif()
					->orderByNotePos(\Criteria::DESC);

		return parent::getPDDComments($query);
	}

    // *****************************    DOCUMENTS   ************************* //

	/**
	 *	Renvoit les réactions associées au débat
	 *
	 * @return PropelCollection d'objets PDReaction
	 */
	public function getReactions($online = true, $published = true) {
		$query = PDReactionQuery::create()
					->_if($online)
						->filterByOnline(true)
					->_endif()
					->_if($published)
						->filterByPublished(true)
						->orderByPublishedAt(\Criteria::DESC)
					->_endif()
					->_if(!$published)
						->orderByCreatedAt(\Criteria::DESC)
					->_endif()
					;

		return parent::getPDReactions($query);
	}

	/**
	 *	Renvoit le nombre de réactions publiées associées au débat
	 *
	 * @return PropelCollection d'objets PDReaction
	 */
	public function countReactions() {
		$query = PDReactionQuery::create()
					->filterByPDDebateId($this->getId())
					->filterByOnline(true)
					->orderByPublishedAt(\Criteria::DESC);

		return parent::countPDReactions($query);
	}

    // *****************************    COMMENTAIRES   ************************* //


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
					->filterByOnline(true);
		
		return parent::countPuFollowDdPUsers($query);
	}

}
