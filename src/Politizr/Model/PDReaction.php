<?php

namespace Politizr\Model;

use Politizr\Exception\InconsistentDataException;

use Politizr\Model\om\BasePDReaction;


use Politizr\Model\PUser;

use Politizr\Model\PDRCommentQuery;


class PDReaction extends BasePDReaction
{	
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
					->filterByPublished($published);

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
					->filterByPublished($published);

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
