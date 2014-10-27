<?php

namespace Politizr\Model;

use Politizr\Exception\InconsistentDataException;

use Politizr\Model\om\BasePDReaction;


use Politizr\Model\PUser;

use Politizr\Model\PDRCommentQuery;


class PDReaction extends BasePDReaction
{	
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
    public function preSave(\PropelPDO $con = null)
    {
    	// TODO > en commentaire pour avoir des fixtures variées (à supprimer)
    	// if ($this->published && ($this->isNew() || in_array(PDReactionPeer::PUBLISHED, $this->modifiedColumns))) {
    	// 	$this->setPublishedAt(time());
    	// } else {
    	// 	$this->setPublishedAt(null);
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


	// *****************************  DEBAT / REACTION  ****************** //

	/**
	 * Renvoit le document associé à la réaction
	 *
	 * @return 	PDDebate 	Objet débat
	 */
	public function getDocument() {
		return parent::getPDocument();
	}

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
	 *	Renvoit le nombre de réactions publiées associées à la réaction courante.
	 *
	 * 	@param 	integer 	$online 		réactions en ligne
	 * 	@param 	integer 	$published  	réactions publiées
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

	/**
	 * 	@see countChildrenReactions
	 */
	public function countReactions() {
		return $this->countChildrenReactions(true, true);
	}

}
