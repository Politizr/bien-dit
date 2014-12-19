<?php

namespace Politizr\Model;

use Politizr\Model\om\BasePQOrganization;

class PQOrganization extends BasePQOrganization
{
	// ************************************************************************************ //
	//										CONSTANTES
	// ************************************************************************************ //
  	const UPLOAD_PATH = '/../../../web/uploads/organizations/';
  	const UPLOAD_WEB_PATH = '/uploads/organizations/';

	// *****************************  OBJET / STRING  ****************** //



	/**
	 *
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
	// ************************************************************************************ //
	//										METHODES ADMIN GENERATOR
	// ************************************************************************************ //



	// ******************* SIMPLE UPLOAD MANAGEMENT **************** //
	// https://github.com/avocode/FormExtensions/blob/master/Resources/doc/single-upload/overview.md

	// Colonnes virtuelles / fichiers
    public $uploadedFileName;
    public function setUploadedFileName($uploadedFileName) {
        $this->uploadedFileName = $uploadedFileName;
    }

    /**
     *
     */
    public function getUploadedFileNameWebPath()
    {
        return PQOrganization::UPLOAD_WEB_PATH . $this->file_name;
    }
    
	/**
	 * 
	 */
    public function getUploadedFileName()
    {
        // inject file into property (if uploaded)
        if ($this->file_name) {
            return new \Symfony\Component\HttpFoundation\File\File(
                __DIR__ . PQOrganization::UPLOAD_PATH . $this->file_name
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
		$fileName = 'pol-' . \StudioEcho\Lib\StudioEchoUtils::randomString() . '.' . $extension;

		// move takes the target directory and then the target filename to move to
		$fileUploaded = $file->move(__DIR__ . PQOrganization::UPLOAD_PATH, $fileName);

		// file_name
		return $fileName;
    }    

    /**
     *	Surcharge pour gérer la suppression physique.
     */
    public function setFileName($v)
    {
      	if (!$v) {
      		$this->removeUpload();
      	}
      	parent::setFileName($v);
    }

    /**
     *  Surcharge pour gérer la suppression physique.
     */
    public function postDelete(\PropelPDO $con = null)
    {
    	 $this->removeUpload();
    }

    /**
     * 	Suppression physique des fichiers.
     */
    public function removeUpload($uploadedFileName = true)
    {
      	if ($uploadedFileName && $this->file_name && file_exists(__DIR__ . PQOrganization::UPLOAD_PATH . $this->file_name)) {
      	  	unlink(__DIR__ . PQOrganization::UPLOAD_PATH . $this->file_name);
      	}
    }

}
