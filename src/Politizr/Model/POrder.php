<?php

namespace Politizr\Model;

use Politizr\Model\om\BasePOrder;

use Symfony\Component\HttpFoundation\File\File;

use Politizr\Constant\PathConstants;

/**
 * Order object model
 *
 * @author Lionel Bouzonville
 */
class POrder extends BasePOrder
{
    // simple upload management
    public $uploadedSupportingDocument;

    /**
     * @todo migrate physical deletion in special command instead of save
     */
    public function postDelete(\PropelPDO $con = null)
    {
        $this->removeUpload();
    }

    /* ######################################################################################################## */
    /*                                      SIMPLE UPLOAD MANAGEMENT                                            */
    /* ######################################################################################################## */

    /**
     *
     * @param string $uploadedFileName
     */
    public function setUploadedSupportingDocument($uploadedFileName)
    {
        $this->uploadedSupportingDocument = $uploadedFileName;
    }

    /**
     *
     * @return string
     */
    public function getUploadedSupportingDocumentWebPath()
    {
        return PathConstants::ORDER_UPLOAD_WEB_PATH . $this->supporting_document;
    }
    
    /**
     *
     * @return File
     */
    public function getUploadedSupportingDocument()
    {
        // inject file into property (if uploaded)
        if ($this->supporting_document) {
            return new File(
                __DIR__ . PathConstants::ORDER_UPLOAD_PATH . $this->supporting_document
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
        $fileName = 'politizr-order-' . \StudioEcho\Lib\StudioEchoUtils::randomString() . '.' . $extension;

        // move takes the target directory and then the target filename to move to
        $fileUploaded = $file->move(__DIR__ . PathConstants::ORDER_UPLOAD_PATH, $fileName);

        // file name
        return $fileName;
    }


    /**
     * @todo migrate physical deletion in special command instead of save
     */
    public function setSupportingDocument($fileName)
    {
        if (!$fileName) {
            $this->removeUpload();
        }
        parent::setSupportingDocument($fileName);
    }

    /**
     *
     * @param $uploadedSupportingDocument
     */
    public function removeUpload($uploadedSupportingDocument = true)
    {
        if ($uploadedSupportingDocument && $this->supporting_document && file_exists(__DIR__ . PathConstants::ORDER_UPLOAD_PATH . $this->supporting_document)) {
            unlink(__DIR__ . PathConstants::ORDER_UPLOAD_PATH . $this->supporting_document);
        }
    }
}
