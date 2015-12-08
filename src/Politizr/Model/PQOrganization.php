<?php

namespace Politizr\Model;

use Politizr\Model\om\BasePQOrganization;

use Politizr\Constant\PathConstants;

/**
 * Organization object model
 *
 * @author Lionel Bouzonville
 */
class PQOrganization extends BasePQOrganization
{
    // simple upload management
    public $uploadedFileName;

    /**
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getInitials();
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

    /* ######################################################################################################## */
    /*                                      SIMPLE UPLOAD MANAGEMENT                                            */
    /* ######################################################################################################## */

    /**
     *
     * @param string $uploadedFileName
     */
    public function setUploadedFileName($uploadedFileName)
    {
        $this->uploadedFileName = $uploadedFileName;
    }

    /**
     *
     * @return string
     */
    public function getUploadedFileNameWebPath()
    {
        return PathConstants::ORGANIZATION_UPLOAD_WEB_PATH . $this->file_name;
    }
    
    /**
     *
     * @return File
     */
    public function getUploadedFileName()
    {
        // inject file into property (if uploaded)
        if ($this->file_name) {
            return new File(
                __DIR__ . PathConstants::ORGANIZATION_UPLOAD_PATH . $this->file_name
            );
        }

        return null;
    }

    /**
     *
     * @param File $file
     * @return string file name
     */
    public function upload($file = null)
    {
        if (null === $file) {
              return;
        }

        // extension
        $extension = $file->guessExtension();
        if (!$extension) {
              $extension = 'bin';
        }

        // file name
        $fileName = 'politizr-orga-' . \StudioEcho\Lib\StudioEchoUtils::randomString() . '.' . $extension;

        // move takes the target directory and then the target filename to move to
        $fileUploaded = $file->move(__DIR__ . PathConstants::ORGANIZATION_UPLOAD_PATH, $fileName);

        // file name
        return $fileName;
    }

    /**
     *
     * @param string $fileName
     */
    public function setFileName($fileName)
    {
        if (null !== $fileName) {
            $this->removeUpload();
        }
        parent::setFileName($fileName);
    }

    /**
     *
     * @param $uploadedFileName
     */
    public function removeUpload($uploadedFileName = true)
    {
        if ($uploadedFileName && $this->file_name && file_exists(__DIR__ . PathConstants::ORGANIZATION_UPLOAD_PATH . $this->file_name)) {
            unlink(__DIR__ . PathConstants::ORGANIZATION_UPLOAD_PATH . $this->file_name);
        }
    }
}
