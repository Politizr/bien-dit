<?php

namespace Politizr\Model;

use Politizr\Model\om\BasePDocument;

use Politizr\Model\PDCommentQuery;

/**
 *	Classe mère de débat et réaction
 *
 *
 *
 * 	@author Lionel Bouzonville / Studio Echo
 */
class PDocument extends BasePDocument
{
	const TYPE_DEBATE = 'débat';
	const TYPE_REACTION = 'réaction';

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
    	if ($this->published && in_array(PDocumentPeer::PUBLISHED, $this->modifiedColumns)) {
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

	// *****************************  DEBAT / REACTION  ****************** //


	/**
	 *	Renvoit le type du document courant
	 *
	 * 	@return string
	 */
	public function getType() {
		$debates = parent::countPDDebates();
		if ($debates > 0) {
			return PDocument::TYPE_DEBATE;
		}

		$reactions = parent::countPDReactions();
		if ($reactions > 0) {
			return PDocument::TYPE_REACTION;
		}

		// TODO > Exception?
		return 'Type non défini';
	}

	/**
	 *	Renvoit l'objet débat associé
	 *
	 * 	@return string
	 */
	public function getPDDebate() {
		$type = $this->getType();
		if ($type == PDocument::TYPE_DEBATE) {
			return $this->getPDocument()->getPDDebates()->getFirst();
		} else {
			return null;
		}
	}

	/**
	 *	Renvoit l'objet réaction associé
	 *
	 * 	@return string
	 */
	public function getPDReaction() {
		$type = $this->getType();
		if ($type == PDocument::TYPE_REACTION) {
			return $this->getPDocument()->getPDReactions()->getFirst();
		} else {
			return null;
		}
	}

    // *****************************    COMMENTAIRES   ************************* //

	/**
	 *	Renvoit le nombre de commentaires du débat.
	 *
	 * @return 	integer 	Nombre de commentaires
	 */
	public function countComments() {
		$query = PDCommentQuery::create()
					->filterByOnline(true);
		
		return parent::countPDComments($query);
	}


	/**
	 *	Renvoit les commentaires associés au débat
	 *
	 * @return PropelCollection d'objets PDDComment 
	 */
	public function getComments($online = true) {
		$query = PDCommentQuery::create()
					->filterByOnline($online)
					->orderByNotePos(\Criteria::DESC);

		return parent::getPDComments($query);
	}
	

	/**
	 *	Renvoit les commentaires généraux au débat (non associés à un paragraphe en particulier)
	 *
	 * @return PropelCollection d'objets PDDComment 
	 */
	public function getGlobalComments() {
		$query = PDCommentQuery::create()
					->filterByOnline(true)
					->filterByParagraphNo(0)
						->_or()
					->filterByParagraphNo(null)
					->orderByNotePos(\Criteria::DESC);

		return parent::getPDComments($query);
	}
	

	/**
	 *	Renvoit les commentaires du débat associés à un paragraphe
	 *
	 * @param $paragraphNo 	Numéro du paragraphe ou null pour tous
	 *
	 * @return PropelCollection d'objets PDDComment 
	 */
	public function getParagraphComments($paragraphNo = null) {
		$query = PDCommentQuery::create()
					->filterByOnline(true)
					->_if($paragraphNo)
						->filterByParagraphNo($paragraphNo)
					->_else()
						->filterByParagraphNo(array('min' => 1))
					->_endif()
					->orderByNotePos(\Criteria::DESC);

		return parent::getPDComments($query);
	}

}
