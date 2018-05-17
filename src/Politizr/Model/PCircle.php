<?php

namespace Politizr\Model;

use Politizr\FrontBundle\Lib\Tools\StaticTools;

use Politizr\Model\om\BasePCircle;

class PCircle extends BasePCircle
{
    /**
     *
     * @return string
     */
    public function __toString()
    {
        $title = $this->getTitle();

        if (!empty($title)) {
            return $this->getTitle();
        }

        return 'Pas de titre';
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
    public $uploadedLogoFileName;
    public function setUploadedLogoFileName($uploadedLogoFileName)
    {
        $this->uploadedLogoFileName = $uploadedLogoFileName;
    }

    /**
     *
     */
    public function getUploadedLogoFileNameWebPath()
    {
        return PCircle::UPLOAD_WEB_PATH . $this->logo_file_name;
    }
    
    /**
     *
     */
    public function getUploadedLogoFileName()
    {
        // inject file into property (if uploaded)
        if ($this->logo_file_name) {
            return new \Symfony\Component\HttpFoundation\File\File(
                __DIR__ . PCircle::UPLOAD_PATH . $this->logo_file_name
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
        $fileName = 'cir-' . StaticTools::randomString() . '.' . $extension;

        // move takes the target directory and then the target filename to move to
        $fileUploaded = $file->move(__DIR__ . PCircle::UPLOAD_PATH, $fileName);

        // logo_file_name
        return $fileName;
    }

    /**
     *    Surcharge pour gérer la suppression physique.
     */
    public function setLogoFileName($v)
    {
        if (!$v) {
            $this->removeUpload(true);
        }
        parent::setLogoFileName($v);
    }

    /**
     *     Suppression physique des fichiers.
     */
    public function removeUpload($uploadedLogoFileName = true)
    {
        if ($uploadedLogoFileName && $this->logo_file_name && file_exists(__DIR__ . PCircle::UPLOAD_PATH . $this->logo_file_name)) {
            unlink(__DIR__ . PCircle::UPLOAD_PATH . $this->logo_file_name);
        }
    }
    // ******************* END SIMPLE UPLOAD MANAGEMENT **************** //

}
