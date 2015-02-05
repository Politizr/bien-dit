<?php

namespace Politizr\Model;

use Politizr\Model\om\BasePRBadge;

class PRBadge extends BasePRBadge
{


	// ******************************************************** //
	//				  Constantes des badges / BDD				//
	// ******************************************************** //


	// ************ DEBATS ******* //
    const ID_QUERELLE = 1;
    const ID_CONTROVERSE = 2;
    const ID_POLEMIQUE = 3;
    const ID_REDACTEUR = 4;
    const ID_AUTEUR = 5;
    const ID_ECRIVAIN = 6;
    const ID_ECLAIREUR = 7;
    const ID_AVANT_GARDE = 8;
    const ID_GUIDE = 9;
    const ID_ANNOTATEUR = 10;
    const ID_GLOSSATEUR = 11;
    const ID_COMMENTATEUR = 12;
    const ID_EFFRONTE = 13;
    const ID_IMPERTINENT = 14;
    const ID_AUDACIEUX = 15;
    const ID_STUDIEUX = 16;
    const ID_TAGUEUR = 17;
    const ID_SURVEILLANT = 18;
    const ID_FOUGUEUX = 19;
    const ID_ENTHOUSIASTE = 20;
    const ID_PASSIONNE = 21;
    const ID_PERSIFLEUR = 22;
    const ID_REPROBATEUR = 23;
    const ID_CRITIQUE = 24;
    const ID_ATTENTIF = 25;
    const ID_ASSIDU = 26;
    const ID_FIDELE = 27;
    const ID_SUIVEUR = 28;
    const ID_DISCIPLE = 29;
    const ID_INCONDITIONNEL = 30;
    const ID_IMPORTANT = 31;
    const ID_INFLUENT = 32;
    const ID_INCONTOURNABLE = 33;
    const ID_PORTE_VOIX = 34;
    const ID_FAN = 35;
    const ID_AMBASSADEUR = 36;

	// ******************************************************** //
	//			Constantes fonctionnelles / badges				//
	// ******************************************************** //
    const QUERELLE_NB_REACTIONS = 3;
    const CONTROVERSE_NB_REACTIONS = 5;
    const POLEMIQUE_NB_REACTIONS = 10;

    const REDACTEUR_NB_DOCUMENTS = 3;
    const REDACTEUR_NB_NOTEPOS = 3;
    const AUTEUR_NB_DOCUMENTS = 5;
    const AUTEUR_NB_NOTEPOS = 5;
    const ECRIVAIN_NB_DOCUMENTS = 10;
    const ECRIVAIN_NB_NOTEPOS = 10;

    const ECLAIREUR_NB_DEBATES = 3;
    const AVANT_GARDE_NB_DEBATES = 10;
    const GUIDE_NB_DEBATES = 50;

    const ANNOTATEUR_NB_COMMENTS = 5;
    const GLOSSATEUR_NB_COMMENTS = 20;
    const COMMENTATEUR_NB_COMMENTS = 50;

    const EFFRONTE_NOTEPOS = 5;
    const IMPERTINENT_NOTEPOS = 20;
    const AUDACIEUX_NOTEPOS = 50;

    const TAGUEUR_NB_TAGS = 50;
    const SURVEILLANT_NB_MODERATIONS = 50;
    
    const FOUGUEUX_NB_NOTEPOS = 25;
    const ENTHOUSIASTE_NB_NOTEPOS = 100;
    const PASSIONNE_NB_NOTEPOS = 250;

    const PERSIFLEUR_NB_NOTENEG = 25;
    const REPROBATEUR_NB_NOTENEG = 100;
    const CRITIQUE_NB_NOTENEG = 250;

    const ATTENTIF_NB_DAYS = 5;
    const ASSIDU_NB_DAYS = 15;
    const FIDELE_NB_DAYS = 30;

    const SUIVEUR_NB_SUBSCRIBES = 5;
    const DISCIPLE_NB_SUBSCRIBES = 20;
    const INCONDITIONNEL_NB_SUBSCRIBES = 50;

    const IMPORTANT_NB_FOLLOWERS = 1;
    const INFLUENT_NB_FOLLOWERS = 20;
    const INCONTOURNABLE_NB_FOLLOWERS = 50;

    const PORTE_VOIX_NB_SHARE = 5;
    const FAN_NB_SHARE = 20;
    const AMBASSADEUR_NB_SHARE = 50;

	// ******************************************************** //


	/**
	 *
	 */
	public function __toString() {
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
	

}
