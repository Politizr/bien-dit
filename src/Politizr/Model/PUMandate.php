<?php

namespace Politizr\Model;

use Politizr\Model\om\BasePUMandate;

class PUMandate extends BasePUMandate
{
    /**
     *
     */
    public function __toString()
    {
        $mandate =  $this->getPQMandate() . "     (" . $this->getOrganizationInitials() . ")";

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
     *    Vérifie si l'objet courant est le mandat courant ou pas en se basant sur la date de fin, qui
     *    doit être null ou > à la date du jour si c'est le cas.
     *
     *    @return     boolean
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
     *    Renvoie les initiales du parti associé à la qualification
     *
     *  @return     string
     */
    public function getOrganizationInitials() {
        $pqOrganization = $this->getPQOrganization();

        $initials = "Non défini";
        if ($pqOrganization) {
            $initials = $pqOrganization->getInitials();
        }

        return $initials;
    }

    /**
     *    Renvoie le titre du parti associé à la qualification
     *
     *  @return     string
     */
    public function getOrganizationTitle() {
        $pqOrganization = $this->getPQOrganization();

        $title = "Non défini";
        if ($pqOrganization) {
            $title = $pqOrganization->getTitle();
        }

        return $title;
    }

    /**
     *    Renvoie le logo du parti associé à la qualification
     *
     *  @return     string         Nom du fichier image
     */
    public function getOrganizationLogo() {
        $pqOrganization = $this->getPQOrganization();

        $fileName = null;
        if ($pqOrganization) {
            $fileName = $pqOrganization->getFileName();
        }

        return $fileName;
    }

    /**
     *    Renvoie le nom du mandat associé à la qualification
     *
     *  @return     string
     */
    public function getMandateTypeTitle() {
        $pqMandate = $this->getPQMandate();

        $title = "Non défini";
        if ($pqMandate) {
            $title = $pqMandate->getTitle();
        }

        return $title;
    }

}
