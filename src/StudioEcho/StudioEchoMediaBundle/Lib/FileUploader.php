<?php

namespace StudioEcho\StudioEchoMediaBundle\Lib;

use StudioEcho\StudioEchoMediaBundle\Lib\UploadedFileForm;
use StudioEcho\StudioEchoMediaBundle\Lib\UploadedFileXhr;

/**
 * Class that encapsulates the file-upload internals
 */
class FileUploader {

    private $allowedExtensions;
    private $sizeLimit;
    private $file;
    
    private $originalName;
    private $uploadName;
    private $name;
    private $size;
    private $extension;
    private $mimeType;
    private $width;
    private $height;
    
    private $logger;

    /**
     * Get the original name of the uploaded file
     * @return string
     */
    public function getOriginalName() {
        return $this->originalName;
    }

    /**
     * Get the name of the uploaded file
     * @return string
     */
    public function getUploadName() {
        return $this->uploadName;
    }

    /**
     * @return string filename
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @return string size
     */
    public function getSize() {
        return $this->size;
    }

    /**
     * @return string
     */
    public function getExtension() {
        return $this->extension;
    }

    /**
     * @return string
     */
    public function getMimeType() {
        return $this->mimeType;
    }

    /**
     * @return string
     */
    public function getWidth() {
        return $this->width;
    }

    /**
     * @return string
     */
    public function getHeight() {
        return $this->height;
    }

    /**
     * @param array $allowedExtensions; defaults to an empty array
     * @param int $sizeLimit; defaults to the server's upload_max_filesize setting
     */
    function __construct(array $allowedExtensions = null, $sizeLimit = null, $logger) {
        $this->logger = $logger;
        $this->logger->info('FileUploader');
        if ($allowedExtensions === null) {
            $allowedExtensions = array();
        }
        if ($sizeLimit === null) {
            $sizeLimit = $this->toBytes(ini_get('upload_max_filesize'));
        }

        $allowedExtensions = array_map("strtolower", $allowedExtensions);

        $this->allowedExtensions = $allowedExtensions;
        $this->sizeLimit = $sizeLimit;
//        $this->logger->info('$allowedExtensions = ' . print_r($allowedExtensions, true));
//        $this->logger->info('$sizeLimit = ' . print_r($sizeLimit, true));

        $this->checkServerSettings();

        if (strpos(strtolower($_SERVER['CONTENT_TYPE']), 'multipart/') === 0) {
//            $this->file = new UploadedFileForm($this->logger);
            throw new \Exception('Multipart form not managed.');
        } else {
            $this->file = new UploadedFileXhr($this->logger);
        }
    }

    /**
     * Internal function that checks if server's may sizes match the
     * object's maximum size for uploads
     */
    private function checkServerSettings() {
        $postSize = $this->toBytes(ini_get('post_max_size'));
        $uploadSize = $this->toBytes(ini_get('upload_max_filesize'));
        if ($postSize < $this->sizeLimit || $uploadSize < $this->sizeLimit) {
            $size = max(1, $this->sizeLimit / 1024 / 1024) . 'M';
            throw new \Exception("Augmenter la taille de post_max_size et upload_max_filesize à $size.");
        }
    }

    /**
     * Convert a given size with units to bytes
     * @param string $str
     */
    private function toBytes($str) {
        $val = trim($str);
        $last = strtolower($str[strlen($str) - 1]);
        switch ($last) {
            case 'g': $val *= 1024;
            case 'm': $val *= 1024;
            case 'k': $val *= 1024;
        }
        return $val;
    }

    /**
     * Handle the uploaded file
     * @param string $uploadDirectory
     * @param string $keepFileName=false
     * @param string $replaceOldFile=false
     * @returns array('success'=>true) or array('error'=>'error message')
     */
    function handleUpload($uploadDirectory, $keepFileName = false, $replaceOldFile = false) {
//        $this->logger->info('handleUpload');
//        $this->logger->info('$uploadDirectory = ' . print_r('\'' . $uploadDirectory . '\'', true));
//        $this->logger->info('$keepFileName = ' . print_r('\'' . $keepFileName . '\'', true));
//        $this->logger->info('$replaceOldFile = ' . print_r($replaceOldFile, true));

        if (!is_writable($uploadDirectory)) {
            throw new \Exception("Problème de droits en écriture sur le répertoire de destination.");
        }
        if (!$this->file) {
            throw new \Exception('Aucun fichier à télécharger.');
        }
        $size = $this->file->getSize();

        if ($size == 0) {
            throw new \Exception('Le fichier est vide.');
        }
        if ($size > $this->sizeLimit) {
            throw new \Exception('Le fichier est trop volumineux.');
        }

        // Get path & file info
        $pathinfo = pathinfo($this->file->getName());
        
        // Set & check extension
        $ext = @$pathinfo['extension'];  // hide notices if extension is empty
        if ($this->allowedExtensions && !in_array(strtolower($ext), $this->allowedExtensions)) {
            $these = implode(', ', $this->allowedExtensions);
            throw new \Exception('Type de fichier non autorisé.');
        }
        $ext = ($ext == '') ? $ext : '.' . $ext;

        // Set file name
        $filename = $pathinfo['filename'];
        if ($keepFileName) {
            if (!$replaceOldFile) {
                // don't overwrite previous files that were uploaded
                while (file_exists($uploadDirectory . DIRECTORY_SEPARATOR . $filename . $ext)) {
                    $filename .= rand(10, 99);
                }
            }
        } else {
            $filename = md5(uniqid());
        }
        
        $pathFileName = $uploadDirectory . DIRECTORY_SEPARATOR . $filename . $ext;

        // Set files attributes
        $this->originalName = $pathinfo['filename'];
        $this->uploadName = $filename . $ext;
        $this->name = $filename;
        $this->extension = $ext;
        $this->size = $size;

        // Save file
        $saved = $this->file->save($pathFileName);

        if ($saved) {
            // Update MIME type
            $finfo = new \finfo(FILEINFO_MIME);
            if ($finfo) {
                $this->mimeType = $finfo->file($pathFileName);
            }

            // Set width/height if file is image
            if ($pos = strpos($this->mimeType, 'image') !== false) {
                list($this->width, $this->height) = getimagesize($pathFileName);
            }
            
            return true;
        } else {
            return false;
        }

    }

}