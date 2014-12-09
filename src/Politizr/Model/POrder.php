<?php

namespace Politizr\Model;

use Politizr\Model\om\BasePOrder;

class POrder extends BasePOrder
{

	// ************************************************************************************ //
	//										CONSTANTES
	// ************************************************************************************ //
  	const UPLOAD_PATH = '/../../../web/uploads/supporting/';
  	const UPLOAD_WEB_PATH = '/uploads/supporting/';


	// ******************* SIMPLE UPLOAD MANAGEMENT **************** //
	// https://github.com/avocode/FormExtensions/blob/master/Resources/doc/single-upload/overview.md

	// Colonnes virtuelles / fichiers
	public $uploadedSupportingDocument;
    public function setUploadedSupportingDocument($uploadedFileName) {
        $this->uploadedSupportingDocument = $uploadedFileName;
    }

    /**
     *
     */
    public function getUploadedSupportingDocumentWebPath()
    {
        return POrder::UPLOAD_WEB_PATH . $this->supporting_document;
    }
    
	/**
	 * 
	 */
    public function getUploadedSupportingDocument()
    {
        // inject file into property (if uploaded)
        if ($this->supporting_document) {
            return new \Symfony\Component\HttpFoundation\File\File(
                __DIR__ . POrder::UPLOAD_PATH . $this->supporting_document
            );
        }

        return null;
    }

    /**
     *	Gestion physique de l'upload
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
		$fileName = 'p-o-' . \StudioEcho\Lib\StudioEchoUtils::randomString() . '.' . $extension;
		// $fileName = $file->getClientOriginalName();

		// move takes the target directory and then the target filename to move to
		$fileUploaded = $file->move(__DIR__ . POrder::UPLOAD_PATH, $fileName);

		// file_name
		return $fileName;
	}    

	/**
	 *	Surcharge pour gérer la suppression physique.
	 */
	public function setSupportingDocument($v)
	{
		if (!$v) {
			$this->removeUpload();
		}
		parent::setSupportingDocument($v);
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
	public function removeUpload($uploadedSupportingDocument = true)
	{
        if ($uploadedSupportingDocument && $this->supporting_document && file_exists(__DIR__ . POrder::UPLOAD_PATH . $this->supporting_document)) {
            unlink(__DIR__ . POrder::UPLOAD_PATH . $this->supporting_document);
        }
	}



	/*************** ADMIN GENERATOR VIRTUAL FIELDS HACK **************************/
	public function getBlockInvoice() {
	}
	public function getBlockMail() {
	}
}
