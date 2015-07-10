<?php
namespace Politizr\FrontBundle\Lib\Utils;

use Politizr\Exception\InconsistentDataException;
use Politizr\Exception\FormValidationException;

use Politizr\FrontBundle\Lib\SimpleImage;

/**
 * Services métiers associés aux utilisateurs.
 *
 * @author Lionel Bouzonville
 */
class UtilsManager
{
    private $sc;

    /**
     *
     */
    public function __construct($serviceContainer)
    {
        $this->sc = $serviceContainer;
    }


    /**
     * Upload Ajax d'une image
     *
     * @param  string      $inputName            nom du champ input
     * @param  string      $destPath             chemin absolu de destination
     * @param  int         $maxWidth             largeur max > utilisé pour resize
     * @param  int         $maxHeight            hauteur max > utilisé pour resize
     * @param  int         $sizeLimit            Taille limite du fichier > 5 * 1024 * 1024 (5Mo)
     * @param  array       $allowedExtensions    Extensions de fichier autorisées
     *
     */
    public function uploadImageAjax($inputName, $destPath, $maxWidth, $maxHeight, $sizeLimit = 5242880, $allowedExtensions = array('jpg', 'jpeg', 'png'))
    {
        // Récupération args
        $request = $this->sc->get('request');

        $myRequestedFile = $request->files->get($inputName);

        if ($myRequestedFile == null) {
            throw new FormValidationException('Fichier non existant.');
        } else if ($myRequestedFile->getError() > 0) {
            throw new FormValidationException('Erreur upload n°'.$myRequestedFile->getError(), 1);
        } else {
            // Contrôle extension
            // $allowedExtensions = array('jpg', 'jpeg', 'png');
            $ext = $myRequestedFile->guessExtension();
            if ($allowedExtensions && !in_array(strtolower($ext), $allowedExtensions)) {
                throw new FormValidationException('Type de fichier non autorisé.');
            }

            // Construction du nom du fichier
            $fileName = md5(uniqid()) . '.' . $ext;

            //move the uploaded file to uploads folder;
            $movedFile = $myRequestedFile->move($destPath, $fileName);
            // $logger->info('$movedFile = '.print_r($movedFile, true));
        }

        // Resize de la photo
        $resized = false;
        $image = new SimpleImage();
        $image->load($destPath . $fileName);
        if ($width = $image->getWidth() > $maxWidth) {
            $image->resizeToWidth($maxWidth);
            $resized = true;
        }
        if ($height = $image->getHeight() > $maxHeight) {
            $image->resizeToHeight($maxHeight);
            $resized = true;
        }
        if ($resized) {
            $image->save($destPath . $fileName);
        }

        return $fileName;
    }
}
