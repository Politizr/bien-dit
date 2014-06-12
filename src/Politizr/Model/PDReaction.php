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
	
	/******************************************************************************/

	/**
	 * 	Surcharge simplification du nom de la méthode
	 */
	public function countPDReactions(\PropelPDO $con = null, $doQuery = true)
	{
		return parent::countPDReactionsRelatedById($con, $doQuery);
	}

	/**
	 * 	Surcharge simplification du nom de la méthode
	 */
	public function getPDReactions(\PropelPDO $con = null, $doQuery = true)
	{
		return parent::getPDReactionRelatedByPDReactionId($con, $doQuery);
	}

	/**
	 * Renvoie les abonnés qualifiés - au débat associé à la réaction courante.
	 *
     * @return     PropelObjectCollection PUser[] List
	 */
	public function getPUsersQ() {
		// TODO: exception à retravailler
		$pdDebate = $this->getPDDebate();
		if ($pdDebate == null) {
			throw new InconsistentDataException('PDReaction pk-'.$this->getId().' PDDebate object not found.');
		} else {
			$pUsers = $this->getPDDebate()->getPUsersQ();
		}

		return $pUsers;
	}

	/**
	 * Renvoie les abonnés citoyens - au débat associé à la réaction courante.
	 *
     * @return     PropelObjectCollection PUser[] List
	 */
	public function getPUsersC() {
		// TODO: exception à retravailler
		$pdDebate = $this->getPDDebate();
		if ($pdDebate == null) {
			throw new InconsistentDataException('PDReaction pk-'.$this->getId().' PDDebate object not found.');
		} else {
			$pUsers = $this->getPDDebate()->getPUsersC();
		}

		return $pUsers;
	}


	/*************** ADMIN GENERATOR VIRTUAL FIELDS HACK **************************/
	public function getBlockReactions() {
	}
	public function getBlockComments() {
	}
	public function getBlockFollowersQ() {
	}
	public function getBlockFollowersC() {
	}



	// ************************************************************************************ //
	//										METHODES 
	// ************************************************************************************ //

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

	/**
	 *	Renvoit les réactions publiées associées à la réaction
	 *
	 * @return PropelCollection d'objets PDReaction
	 */
	public function getReactions() {
		$query = PDReactionQuery::create()
					->filterByPDReactionId($this->getId())
					->filterByOnline(true)
					->filterByPublished(true)
					->orderByPublishedAt(\Criteria::DESC);

		return parent::getPDReactions($query);
	}

	/**
	 *	Renvoir l'objet PDReaction associé à la réaction courante
	 *
	 * @return PDReaction
	 */
	public function getReactionTo() {
		$query = PDReactionQuery::create()
					->filterByOnline(true)
					->filterByPublished(true)
					->filterByPublished(true);

		return parent::getPDReactionRelatedByPDReactionId(null, $query);
	}

}
