<?php

namespace Politizr\Model;

use Politizr\Model\om\BasePDRComment;

class PDRComment extends BasePDRComment
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

}
