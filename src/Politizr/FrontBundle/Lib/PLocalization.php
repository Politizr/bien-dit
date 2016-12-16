<?php

namespace Politizr\FrontBundle\Lib;

/**
 * Interface to manage localization objects: PLCity, PLDepartment, PLRegion, PLCountry
 *
 */
interface PLocalization
{
    /**
     *
     * @return string
     */
    public function getUuid();

    /**
     *
     * @return string
     */
    public function getLocType();

    /**
     *
     * @return string
     */
    public function getType();

    /**
     *
     * @return string
     */
    public function getSlug();
}
