<?php

namespace Politizr\Model;

use Politizr\Model\om\BasePDMedia;

use Politizr\Constant\PathConstants;
use Politizr\Constant\DocumentConstants;

class PDMedia extends BasePDMedia
{
    /**
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getFileName();
    }

    /**
     * Code to be run after deleting the object in database
     *
     * @param PropelPDO $con
     */
    public function postDelete(\PropelPDO $con = null)
    {
        $this->removeUpload();
    }

    /**
     * Suppression physique des fichiers.
     */
    public function removeUpload()
    {
        if ($this->file_name && file_exists(__DIR__ . PathConstants::DEBATE_UPLOAD_PATH . $this->file_name)) {
            unlink(__DIR__ . PathConstants::DEBATE_UPLOAD_PATH . $this->file_name);
        }
        if (DocumentConstants::DOC_THUMBNAIL_PREFIX . $this->file_name && file_exists(__DIR__ . PathConstants::DEBATE_UPLOAD_PATH . DocumentConstants::DOC_THUMBNAIL_PREFIX . $this->file_name)) {
            unlink(__DIR__ . PathConstants::DEBATE_UPLOAD_PATH . DocumentConstants::DOC_THUMBNAIL_PREFIX . $this->file_name);
        }
    }

}
