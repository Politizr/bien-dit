<?php

namespace Politizr\Model;

use Politizr\Exception\InconsistentDataException;


use Politizr\Model\om\BasePDReaction;

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
	
	/**
	 * 	Surcharge simplification du nom de la méthode
	 */
	public function getPDReaction(\PropelPDO $con = null, $doQuery = true)
	{
		return parent::getPDReactionRelatedByPDReactionId($con, $doQuery);
	}

	/******************************************************************************/

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
}
