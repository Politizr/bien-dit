<?php

namespace Politizr\Model;

use Politizr\FrontBundle\Lib\Tools\StaticTools;

use Politizr\Model\om\BasePCTopic;

class PCTopic extends BasePCTopic
{
    /**
     *
     * @return string
     */
    public function __toString()
    {
        $title = $this->getTitle();
        if (empty($title)) {
            $title = 'Pas de titre';
        }

        $circle = $this->getPCircle();
        $circleTitle = $circle->getTitle();
        if (empty($circleTitle)) {
            $circleTitle = 'Groupe inconnu';
        }

        return $title . '(' . $circleTitle . ')';
    }

     /**
     * Override to manage accented characters
     * @return string
     */
    protected function createRawSlug()
    {
        $toSlug =  StaticTools::transliterateString($this->getTitle());
        $slug = $this->cleanupSlugPart($toSlug);
        return $slug;
    }

    // ******************* SIMPLE UPLOAD MANAGEMENT **************** //
    const UPLOAD_PATH = '/../../../web/uploads/circles/';
    const UPLOAD_WEB_PATH = '/uploads/circles/';

    // Colonnes virtuelles / fichiers
    public $uploadedFileName;
    public function setUploadedFileName($uploadedFileName)
    {
        $this->uploadedFileName = $uploadedFileName;
    }

    /**
     *
     */
    public function getUploadedFileNameWebPath()
    {
        return PCTopic::UPLOAD_WEB_PATH . $this->file_name;
    }
    
    /**
     *
     */
    public function getUploadedFileName()
    {
        // inject file into property (if uploaded)
        if ($this->file_name) {
            return new \Symfony\Component\HttpFoundation\File\File(
                __DIR__ . PCTopic::UPLOAD_PATH . $this->file_name
            );
        }

        return null;
    }

    /**
     *  Gestion physique de l'upload
     */
    public function upload($file = null)
    {
        if (null === $file) {
              return;
        }

        // Extension et nom de fichier
        $extension = $file->guessExtension();
        if (!$extension) {
              $extension = 'bin';
        }
        $fileName = 'top-' . StaticTools::randomString() . '.' . $extension;

        // move takes the target directory and then the target filename to move to
        $fileUploaded = $file->move(__DIR__ . PCTopic::UPLOAD_PATH, $fileName);

        // file_name
        return $fileName;
    }

    /**
     *    Surcharge pour gérer la suppression physique.
     */
    public function setFileName($v)
    {
        if (!$v) {
            $this->removeUpload(true);
        }
        parent::setFileName($v);
    }

    /**
     *     Suppression physique des fichiers.
     */
    public function removeUpload($uploadedFileName = true)
    {
        if ($uploadedFileName && $this->file_name && file_exists(__DIR__ . PCTopic::UPLOAD_PATH . $this->file_name)) {
            unlink(__DIR__ . PCTopic::UPLOAD_PATH . $this->file_name);
        }
    }
    // ******************* END SIMPLE UPLOAD MANAGEMENT **************** //

}
