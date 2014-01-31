<?php

namespace StudioEcho\StudioEchoMediaBundle\Model;

use StudioEcho\StudioEchoMediaBundle\Model\om\BaseSeMediaObject;

use StudioEcho\StudioEchoMediaBundle\Model\SeMediaObjectQuery;
use StudioEcho\StudioEchoMediaBundle\Model\SeMediaFileQuery;

use StudioEcho\StudioEchoMediaBundle\Model\SeMediaFile;

class SeMediaObject extends BaseSeMediaObject
{
  
  /**
   * Return associated collection of media files
   * 
   * @return collection of StudioEcho\StudioEchoMediaBundle\Model\SeMediaFile
   */
  public function getSortedSeMediaFiles($locale = 'fr', $category_id = 1) {
    return SeMediaFileQuery::create()
            ->joinWithI18n($locale)
            ->filterByCategoryId($category_id)
            ->useSeObjectHasFileQuery()
                ->filterBySeMediaObjectId($this->getId())
            ->orderByRank()
            ->endUse()
            ->find();
  }
}
