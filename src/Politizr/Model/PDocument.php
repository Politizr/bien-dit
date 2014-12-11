<?php

namespace Politizr\Model;

use Politizr\Model\om\BasePDocument;

use Politizr\Model\PDCommentQuery;
use Politizr\Model\PDDebateQuery;
use Politizr\Model\PDReactionQuery;

use Politizr\Exception\InconsistentDataException;

/**
 *	Classe mère de débat et réaction
 *
 *
 *
 * 	@author Lionel Bouzonville / Studio Echo
 */
class PDocument extends BasePDocument
{
	// TODO > migrer le stockage des types sur un objet dédié
	const TYPE_DEBATE = 'debate';
	const TYPE_REACTION = 'reaction';
	const TYPE_COMMENT = 'comment';
	const TYPE_USER = 'user';

	// *****************************  OBJET / STRING  ****************** //

	/**
	 *
	 */
	public function __toString()
	{
		return $this->getTitle();
	}


	// *****************************  USER  ****************** //

	/**
	 *	Vérifie que le document courant a été rédigé par le user dont l'ID est passé en argument.
	 *
	 * 	@return boolean
	 */
	public function isOwner($pUserId) {
		if ($this->getPUserId() == $pUserId) {
			return true;
		} else {
			return false;
		}
	}

	// *****************************  DEBAT / REACTION  ****************** //


	/**
	 * Renvoit le type "enfant" du document associé
	 *
	 * @return 	string
	 */
	public function getType() {
		$object = $this->getDescendantClass();

		if ($object == 'Politizr\Model\PDDebate') {
			return PDocument::TYPE_DEBATE;
		} elseif ($object == 'Politizr\Model\PDReaction') {
			return PDocument::TYPE_REACTION;
		} else {
			throw new InconsistentDataException('PDocument child object unknown or null.');
		}
	}

	/**
	 *	Renvoit l'objet PDDebate associé.
	 *
	 * 	@return 	PDDebate
	 */
	public function getDebate() {
		if ($type = $this->getType() == PDocument::TYPE_DEBATE) {
			return PDDebateQuery::create()->findPk($this->getId());
		} else {
			throw new InconsistentDataException('PDocument child object is not of PDDebate type.');
		}
	}

	/**
	 *	Renvoit l'objet PDReaction associé.
	 *
	 * 	@return 	PDReaction
	 */
	public function getReaction() {
		if ($type = $this->getType() == PDocument::TYPE_REACTION) {
			return PDReactionQuery::create()->findPk($this->getId());
		} else {
			throw new InconsistentDataException('PDocument child object is not of PDReaction type.');
		}
	}

    // *****************************    COMMENTAIRES   ************************* //

	/**
	 *	Renvoit le nombre de commentaires du débat.
	 *
	 * @return 	integer 	Nombre de commentaires
	 */
	public function countComments($online = true, $paragraphNo = null) {
		$query = PDCommentQuery::create()
					->filterByOnline($online)
					->_if($paragraphNo)
						->filterByParagraphNo($paragraphNo)
					->_endif()
					;
		
		return parent::countPDComments($query);
	}


	/**
	 *	Renvoit les commentaires associés au débat
	 *
	 * @return PropelCollection d'objets PDDComment 
	 */
	public function getComments($online = true, $paragraphNo = null) {
		$query = PDCommentQuery::create()
					->filterByOnline($online)
					->_if($paragraphNo)
						->filterByParagraphNo($paragraphNo)
					->_endif()
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
