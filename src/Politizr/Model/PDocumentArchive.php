<?php

namespace Politizr\Model;

use Politizr\Model\om\BasePDocumentArchive;

class PDocumentArchive extends BasePDocumentArchive
{
	/**
	 *	/!\ TODO > Hack
	 *	Contournement du conflit entre les Propel Behaviors Archivable & ConcreteInheritance
	 * 	https://github.com/propelorm/Propel/issues/278
	 */
	public function setDescendantClass() {
	}
}
