<?php

namespace Politizr\Model;

use Politizr\Model\om\BasePDDebate;


use Politizr\Model\PUser;

use Politizr\Model\PUserQuery;

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
}
