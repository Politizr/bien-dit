<?php

namespace Politizr\Model;

use Politizr\Model\om\BasePDDebate;


use Politizr\Model\PUser;

use Politizr\Model\PUserQuery;
use Politizr\Model\PDReactionQuery;

class PDDebate extends BasePDDebate
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
	
	/******************************************************************************/

	/**
	 * Renvoie les abonnés qualifiés
	 *
     * @return     PropelObjectCollection PUser[] List
	 */
	public function getPUsersQ() {
		$criteria = PUserQuery::create()->filterByType(PUser::TYPE_QUALIFIE);
		$pUsers = parent::getPuFollowDdPUsers($criteria);

		return $pUsers;
	}

	/**
	 * Renvoie les abonnés citoyens
	 *
     * @return     PropelObjectCollection PUser[] List
	 */
	public function getPUsersC() {
		$criteria = PUserQuery::create()->filterByType(PUser::TYPE_CITOYEN);
		$pUsers = parent::getPuFollowDdPUsers($criteria);

		return $pUsers;
	}


	/*************** ADMIN GENERATOR VIRTUAL FIELDS HACK **************************/
	public function getBlockReactions() {
	}
	public function getBlockComments() {
	}
	public function getBlockTags() {
	}
	public function getBlockFollowersQ() {
	}
	public function getBlockFollowersC() {
	}


	// ************************************************************************************ //
	//										METHODES
	// ************************************************************************************ //

	/**
	 * Renvoit les tags associés au document
	 *
	 * @return PropelCollection d'objets PTag
	 */
	public function getPTags() {
		$query = PTagQuery::create()->filterByOnline(true);

		return parent::getPddTaggedTPTags($query);
	}

	/**
	 *	Renvoit les commentaires généraux au débat (non associés à un paragraphe en particulier)
	 *
	 * @return PropelCollection d'objets PDDComment 
	 */
	public function getGlobalComments() {
		$query = PDDCommentQuery::create()
					->filterByPDDebateId($this->getId())
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
					->filterByPDDebateId($this->getId())
					->filterByOnline(true)
					->_if($paragraphNo)
						->filterByParagraphNo($paragraphNo)
					->_else()
						->filterByParagraphNo(array('min' => 1))
					->_endif()
					->orderByNotePos(\Criteria::DESC);

		return parent::getPDDComments($query);
	}

	/**
	 *	Renvoit les réactions publiées associées au débat
	 *
	 * @return PropelCollection d'objets PDReaction
	 */
	public function getReactions() {
		$query = PDReactionQuery::create()
					->filterByPDDebateId($this->getId())
					->filterByOnline(true)
					->orderByPublishedAt(\Criteria::DESC);

		return parent::getPDReactions($query);
	}

}
