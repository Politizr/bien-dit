<?php

namespace Politizr\Model;

use Politizr\Exception\InconsistentDataException;

use Politizr\Model\om\BasePDReaction;


use Politizr\Model\PUser;

use Politizr\Model\PDRCommentQuery;


class PDReaction extends BasePDReaction
{
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
    	if ($this->published && in_array(PDReactionPeer::PUBLISHED, $this->modifiedColumns)) {
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
	
	/*************** ADMIN GENERATOR VIRTUAL FIELDS HACK **************************/

	public function getBlockComments() {
	}


	// ************************************************************************************ //
	//										METHODES 
	// ************************************************************************************ //

    // *****************************    DEBAT   ************************* //

	/**
	 * Renvoit le débat associé à la réaction
	 *
	 * @return 	PDDebate 	Objet débat
	 */
	public function getDebate() {
		return parent::getPDDebate();
	}

    // *****************************    USERS   ************************* //

	/**
	 * Renvoie les abonnés qualifiés - au débat associé à la réaction courante.
	 *
     * @return     PropelObjectCollection PUser[] List
	 */
	public function getPUserFollowersQ() {
		$pdDebate = parent::getPDDebate();

		$pUsers = null;
		if ($pdDebate) {
			$pUsers = $this->getPDDebate()->getPUserFollowersQ();
		}

		return $pUsers;
	}

	/**
	 * Renvoie les abonnés citoyens - au débat associé à la réaction courante.
	 *
     * @return     PropelObjectCollection PUser[] List
	 */
	public function getPUserFollowersC() {
		$pdDebate = parent::getPDDebate();

		$pUsers = null;
		if ($pdDebate) {
			$pUsers = $this->getPDDebate()->getPUserFollowersC();
		}

		return $pUsers;
	}


    // *****************************    COMMENTAIRES   ************************* //

	/**
	 *	Renvoit le nombre de commentaires de la réaction courante.
	 *
	 * @return 	integer 	Nombre de commentaires
	 */
	public function countComments() {
		$query = PDRCommentQuery::create()
					->filterByOnline(true);
		
		return parent::countPDRComments($query);
	}

	/**
	 *	Renvoit les commentaires associés à la réaction
	 *
	 * @return PropelCollection d'objets PDDComment 
	 */
	public function getComments($online = true) {
		$query = PDRCommentQuery::create()
					->filterByOnline($online)
					->orderByNotePos(\Criteria::DESC);

		return parent::getPDRComments($query);
	}
	

	/**
	 *	Renvoit les commentaires généraux au débat (non associés à un paragraphe en particulier)
	 *
	 * @return PropelCollection d'objets PDRComment 
	 */
	public function getGlobalComments() {
		$query = PDRCommentQuery::create()
					->filterByPDReactionId($this->getId())
					->filterByOnline(true)
					->filterByParagraphNo(0)
						->_or()
					->filterByParagraphNo(null)
					->orderByNotePos(\Criteria::DESC);

		return parent::getPDRComments($query);
	}
	

	/**
	 *	Renvoit les commentaires du débat associés à un paragraphe
	 *
	 * @param $paragraphNo 	Numéro du paragraphe ou null pour tous
	 *
	 * @return PropelCollection d'objets PDDComment 
	 */
	public function getParagraphComments($paragraphNo = null) {
		$query = PDRCommentQuery::create()
					->filterByPDReactionId($this->getId())
					->filterByOnline(true)
					->_if($paragraphNo)
						->filterByParagraphNo($paragraphNo)
					->_else()
						->filterByParagraphNo(array('min' => 1))
					->_endif()
					->orderByNotePos(\Criteria::DESC);

		return parent::getPDRComments($query);
	}

    // *****************************    REACTIONS   ************************* //

	/**
	 *	Renvoit les réactions enfants associées à la réaction courante.
	 *
	 *
	 * @return PropelCollection d'objets PDReaction
	 */
	public function getChildrenReactions($online = true, $published = true) {
		$query = PDReactionQuery::create()
					->filterByOnline($online)
					->filterByPublished($published)
					->orderByPublishedAt(\Criteria::DESC);

		return parent::getChildren($query);
	}

	/**
	 *	Renvoit les réactions descendantes associées à la réaction courante.
	 *
	 *
	 * @return PropelCollection d'objets PDReaction
	 */
	public function getDescendantsReactions($online = true, $published = true) {
		$query = PDReactionQuery::create()
					->filterByOnline($online)
					->filterByPublished($published)
					->orderByPublishedAt(\Criteria::DESC);

		return parent::getDescendants($query);
	}

	/**
	 *Renvoit le nombre de réactions publiées associées à la réaction courante.
	 * TODO: niveau d'inspection à gérer
	 *
	 * @param 	integer 	$level 		Niveau d'inspection
	 *
	 * @return PropelCollection d'objets PDReaction
	 */
	public function countChildrenReactions($online = true, $published = true) {
		$query = PDReactionQuery::create()
					->filterByOnline($online)
					->filterByPublished($published)
					->orderByPublishedAt(\Criteria::DESC);

		return parent::countChildren($query);
	}

}
