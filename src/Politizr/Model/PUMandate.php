<?php

namespace Politizr\Model;

use Politizr\Model\om\BasePUMandate;

/**
 * User's mandate object model
 *
 * @author Lionel Bouzonville
 */
class PUMandate extends BasePUMandate
{
    /**
     *
     * @return string
     */
    public function __toString()
    {
        $mandate =  $this->getPQMandate() . " (" . $this->getOrganizationInitials() . ")";

        if ($this->getBeginAt() && $this->getEndAt()) {
            $mandate .= " du " . $this->getBeginAt('d/m/Y') . " au " . $this->getEndAt('d/m/Y');
        } elseif ($this->getBeginAt()) {
            $mandate .= " depuis le " . $this->getBeginAt('d/m/Y');
        }

        return $mandate;
    }

    /**
     * Check if object is current user's mandate
     *
     * @return boolean
     */
    public function isCurrent()
    {
        $now = new \DateTime('now');

        if ($this->getEndAt() == null || $this->getEndAt() > $now) {
            return true;
        }

        return false;
    }

    /* ######################################################################################################## */
    /*                                             ORGANIZATIONS                                                */
    /* ######################################################################################################## */
    
    /**
     *
     * @return PQOrganization
     */
    public function getOrganization()
    {
        $pqOrganization = $this->getPQOrganization();

        return $pqOrganization;
    }

    /**
     * @todo dead code?
     *
     * @return string
     */
    public function getOrganizationInitials()
    {
        $pqOrganization = $this->getPQOrganization();

        // @todo label constant
        $initials = "Non défini";
        if ($pqOrganization) {
            $initials = $pqOrganization->getInitials();
        }

        return $initials;
    }

    /**
     * @todo dead code?
     *
     * @return string
     */
    public function getOrganizationTitle()
    {
        $pqOrganization = $this->getPQOrganization();

        // @todo label constant
        $title = "Non défini";
        if ($pqOrganization) {
            $title = $pqOrganization->getTitle();
        }

        return $title;
    }

    /**
     * @todo dead code?
     *
     * @return string file name
     */
    public function getOrganizationLogo()
    {
        $pqOrganization = $this->getPQOrganization();

        $fileName = null;
        if ($pqOrganization) {
            $fileName = $pqOrganization->getFileName();
        }

        return $fileName;
    }

    /**
     * @todo dead code?
     *
     * @return string
     */
    public function getOrganizationUrl()
    {
        $pqOrganization = $this->getPQOrganization();

        $url = null;
        if ($pqOrganization) {
            $url = $pqOrganization->getUrl();
        }

        return $url;
    }

    /**
     * @todo dead code?
     *
     * @return string
     */
    public function getOrganizationSlug()
    {
        $pqOrganization = $this->getPQOrganization();

        $slug = null;
        if ($pqOrganization) {
            $slug = $pqOrganization->getSlug();
        }

        return $slug;
    }

    /* ######################################################################################################## */
    /*                                                  MANDATES                                                */
    /* ######################################################################################################## */

    /**
     *
     * @return string
     */
    public function getMandateNaming()
    {
        $pqMandate = $this->getPQMandate();

        // @todo label constant
        $title = "Non défini";
        if ($pqMandate) {
            $title = $pqMandate->getTitle();
        }

        return $title;
    }
}
