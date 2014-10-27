<?php

namespace Politizr\Model;

use Politizr\Model\om\BasePDComment;

use Politizr\Model\PDocument;

class PDComment extends BasePDComment
{
	/**
	 *	Surcharge pour gérer la date et l'auteur de la publication.
	 *
	 *
	 */
    public function save(\PropelPDO $con = null)
    {
    	// Date publication
    	if ($this->isNew()) {
    		$this->setPublishedAt(time());

	    	// User associé
	    	// TODO: /!\ chaine en dur
			$publisher = $this->getPUser();
			if ($publisher) {
				$this->setPublishedBy($publisher->getFirstname().' '.$publisher->getName());
			} else {
				$this->setPublishedBy('Auteur inconnu');
			}
		}
		
    	parent::save($con);
	}
	
	/******************************************************************************/

	/**
	 * Renvoit le type de document associé à la réaction
	 *
	 * @return 	PDDebate 	Objet débat
	 */
	public function getType() {
		return parent::getPDocument()->getType();
	}

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
		return $this->getDocument()->getDebate();
	}

	/**
	 * Renvoit la réaction associé à la réaction
	 *
	 * @return 	PDDebate 	Objet débat
	 */
	public function getReaction() {
		return $this->getDocument()->getReaction();
	}

	/**
	 * Renvoit le user associé à la réaction
	 *
	 * @return 	PDDebate 	Objet débat
	 */
	public function getAuthor() {
		return parent::getPUser();
	}

}