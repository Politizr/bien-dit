<?php

namespace Politizr\FrontBundle\Lib;

/**
 * Interface to manage tag-like objects: PTag and PLCity, PLDepartment, PLRegion, PLCountry
 *
 */
interface Tag
{
    /**
     *
     * @return string
     */
    public function getTitle();

    /**
     *
     * @return string
     */
    public function getUuid();

    /**
     *
     * @return string
     */
    public function getSlug();

    /**
     *
     * @return int
     */
    public function countUsers();

    /**
     *
     * @return int
     */
    public function countDocuments();

    /**
     *
     * @return string
     */
    public function getType();

    /**
     *
     * @return int
     */
    public function getTagType();
}
