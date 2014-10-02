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
		$mandate =  $this->getPUMandateType() . " 	(" . $this->getPoliticalPartyInitials() . ")";

		if ($this->getBeginAt() && $this->getEndAt()) {
			$mandate .= " du " . $this->getBeginAt('d/m/Y') . " au " . $this->getEndAt('d/m/Y');
		} elseif ($this->getBeginAt()) {
			$mandate .= " depuis le " . $this->getBeginAt('d/m/Y');
		}

		return $mandate;
	}

    // ************************************************************************************ //
    //                      METHODES PUBLIQUES
    // ************************************************************************************ //

    // *****************************    RACCOURCIS    ************************* //

	/**
	 *	Vérifie si l'objet courant est le mandat courant ou pas en se basant sur la date de fin, qui
	 *	doit être null ou > à la date du jour si c'est le cas.
	 *
	 *	@return 	boolean
	 */
	public function isCurrent() {
		$now = new \DateTime('now');

		if ($this->getEndAt() == null || $this->getEndAt() > $now) {
			return true;
		} else {
			return false;
		}
	}

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
