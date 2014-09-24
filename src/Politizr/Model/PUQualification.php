<?php

namespace Politizr\Model;

use Politizr\Model\om\BasePUQualification;

class PUQualification extends BasePUQualification
{
	/**
	 *
	 */
	public function __toString()
	{
		return $this->getTitle() . " du " . $this->getBeginAt('d/m/Y') . " au " . $this->getEndAt('d/m/Y');
	}

    // ************************************************************************************ //
    //                      METHODES PUBLIQUES
    // ************************************************************************************ //


    // *****************************    RACCOURCIS PARTIS / MANDATS    ************************* //

	/**
	 *	Renvoie les initiales du parti associé à la qualification
	 *
	 *  @return 	string
	 */
	public function getPoliticalPartyInitials() {
		$puPoliticalParty = $this->getPUPoliticalParty();

		$initials = "Non défini";
		if ($puPoliticalParty) {
			$initials = $puPoliticalParty->getInitials();
		}

		return $initials;
	}

	/**
	 *	Renvoie le titre du parti associé à la qualification
	 *
	 *  @return 	string
	 */
	public function getPoliticalPartyTitle() {
		$puPoliticalParty = $this->getPUPoliticalParty();

		$title = "Non défini";
		if ($puPoliticalParty) {
			$title = $puPoliticalParty->getTitle();
		}

		return $title;
	}

	/**
	 *	Renvoie le logo du parti associé à la qualification
	 *
	 *  @return 	string 		Nom du fichier image
	 */
	public function getPoliticalPartyLogo() {
		$puPoliticalParty = $this->getPUPoliticalParty();

		$fileName = null;
		if ($puPoliticalParty) {
			$fileName = $puPoliticalParty->getFileName();
		}

		return $fileName;
	}

	/**
	 *	Renvoie le nom du mandat associé à la qualification
	 *
	 *  @return 	string
	 */
	public function getMandateTypeTitle() {
		$puMandateType = $this->getPUMandateType();

		$title = "Non défini";
		if ($puMandateType) {
			$title = $puMandateType->getTitle();
		}

		return $title;
	}






}
