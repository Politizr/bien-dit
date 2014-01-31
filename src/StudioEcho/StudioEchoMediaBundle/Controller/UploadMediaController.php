<?php

/* * ******************************************
 * TODO / avancement:
 * - gestion des loading ajax messages de succès / 90%
 * - gestion des exceptions erreurs Ajax / 90%
 * - dispatcher la gestion entre les évènements "success" et/ou "complete"? / 0%
 * - gestion galerie de documents / 0%
 * - stylage css / 75%
 * - gestion des types MIME / 90%
 * - gestion des transactions / 100%
 * - refactoring / 50%
 * - intégration i18n de tous les libellés, messages d'erreur, etc. / 0%
 * - écriture de tests unitaires / 0%
 *
 * - gestion injection de paramètres via config.yml : / 100%
 *      - gestion des types de fichiers et/ou extensions autorisées / 50%
 *      - gestion des chemins / 0%
 *      - gestion des category / 0%
 *      - gestion nombre max d'upload / 80%
 *      - gestion intégration optionnelle de TinyMCE / 0%
 *      - gestion culture / 100%
 * 
 * Intégration de Fineuploader
 * http://fineuploader.com/fine-uploader-with-jquery-wrapper-demo.html
 * 
 * @author Lionel Bouzonville / Studio Echo
 * ***************************************** */

namespace StudioEcho\StudioEchoMediaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use StudioEcho\StudioEchoMediaBundle\Model\SeMediaObject;
use StudioEcho\StudioEchoMediaBundle\Model\SeObjectHasFile;
use StudioEcho\StudioEchoMediaBundle\Model\SeMediaFile;
use StudioEcho\StudioEchoMediaBundle\Model\SeMediaObjectQuery;
use StudioEcho\StudioEchoMediaBundle\Model\SeObjectHasFileQuery;
use StudioEcho\StudioEchoMediaBundle\Model\SeMediaFileQuery;
use StudioEcho\StudioEchoMediaBundle\Model\SeMediaObjectPeer;

use StudioEcho\StudioEchoMediaBundle\Lib\FileUploader;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

// use Symfony\Component\Security\Core\Exception;
// use Symfony\Component\HttpKernel\Exception\FlattenException;


class UploadMediaController extends Controller {

    /**
     * 
     * @param type $objectName
     * @param type $objectId
     * @param type $mediaParameterConfigKey
     * @return type
     */
    public function indexAction(
            $objectName, 
            $objectId, 
            $mediaParameterConfigKey
    ) {
        $logger = $this->get('logger');
        $logger->info('UploadMediaController');
        $logger->info('indexAction');
        $logger->info('$objectName = '.print_r($objectName, true));
        $logger->info('$objectId = '.print_r($objectId, true));
        $logger->info('$mediaParameterConfigKey = '.print_r($mediaParameterConfigKey, true));
        
        // Get the current edit object
        $seMediaObject = SeMediaObjectQuery::create()->filterByObjectClassname($objectName)->filterByObjectId($objectId)->findOne();
        if (!$seMediaObject) {
            throw $this->createNotFoundException('SeMediaObject n°'.$objectId.'/ name '.$objectName.' not found.');
        }

        // Load config from session or from config if not found
        $session = $this->getRequest()->getSession();
        $mediaConfig = $session->get('studio_echo_media_bundle/'.$mediaParameterConfigKey);
        if ($mediaConfig == null) {
            // Get the parameters
            $medias = $this->container->getParameter('studio_echo_media');
            $logger->info('$medias = '.print_r($medias, true));
            $medias = $medias['medias'];
            $logger->info('$medias[medias] = '.print_r($medias, true));
            $mediaConfig = $medias[$mediaParameterConfigKey];
            $logger->info('$mediaConfig = '.print_r($mediaConfig, true));
            
            $session->set('studio_echo_media_bundle/'.$mediaParameterConfigKey, $mediaConfig);
        } else {
            $logger->info('$mediaConfig not null / '.print_r($mediaConfig, true));
        }
        
        // Render the view
        return $this->render('StudioEchoMediaBundle:UploadMedia:index.html.twig', array(
                    'currentMediaObjectId' => $seMediaObject->getId(),
                    'mediaParameterConfigKey' => $mediaParameterConfigKey
                ));
    }

    /**
     * 
     * @param type $mediaObjectId
     * @return type
     */
    public function displayZoneAction(
            $mediaObjectId,
            $mediaParameterConfigKey
    ) {
        $logger = $this->get('logger');
        $logger->info('UploadMediaController');
        $logger->info('displayZoneAction');
        $logger->info('$mediaObjectId = ' . print_r($mediaObjectId, true));
        $logger->info('$mediaParameterConfigKey = ' . print_r($mediaParameterConfigKey, true));

        // Get the current edit object
        $seMediaObject = SeMediaObjectQuery::create()->findPk($mediaObjectId);

        // Check config variable in session
        $session = $this->getRequest()->getSession();
        $mediaConfig = $session->get('studio_echo_media_bundle/'.$mediaParameterConfigKey);
        if ($mediaConfig == null) {
            throw $this->createNotFoundException('MediaConfig not found in session or session has been lost.');
        }
        
        // Get media files
        if ($seMediaObject) {
            $mediaFiles = $seMediaObject->getSortedSeMediaFiles($mediaConfig['culture'], $mediaConfig['category_id']);
        } else {
            throw $this->createNotFoundException('SeMediaObject n°' . $mediaObjectId . ' not found.');
        }

        // Render the view
        return $this->render('StudioEchoMediaBundle:UploadMedia:displayZone.html.twig', array(
                    'mediaObjectId' => $mediaObjectId,
                    'mediaParameterConfigKey' => $mediaParameterConfigKey,
                    'mediaFiles' => $mediaFiles
                ));
    }

    /**
     * 
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return type
     */
    public function uploadAction(Request $request) {
        $logger = $this->get('logger');
        $logger->info('UploadMediaController');
        $logger->info('uploadAction');

        $mediaObjectId = $request->get('mediaObjectId');
        $logger->info('mediaObjectId = ' . $mediaObjectId);
        $mediaParameterConfigKey = $request->get('mediaParameterConfigKey');
        $logger->info('mediaParameterConfigKey = ' . $mediaParameterConfigKey);

        $session = $this->getRequest()->getSession();
        $mediaConfig = $session->get('studio_echo_media_bundle/'.$mediaParameterConfigKey);
        $logger->info('$mediaConfig = '.print_r($mediaConfig, true));
        
        try {
            // gestion transaction
            $con = \Propel::getConnection(SeMediaObjectPeer::DATABASE_NAME);
            $con->beginTransaction();
            
            // check if max files upload has not been reached
            $seMediaObject = SeMediaObjectQuery::create()->findPk($mediaObjectId);
            $mediaFiles = $seMediaObject->getSortedSeMediaFiles($mediaConfig['culture'], $mediaConfig['category_id']);
            
            if (count($mediaFiles) >= $mediaConfig['max_files']) {
                throw new \Exception('Nombre maximum de fichiers atteint.');
            }
            
            // list of valid extensions, ex. array("jpeg", "xml", "bmp")
            $allowedExtensions = $mediaConfig['extension_list'];

            // max file size in bytes
            $sizeLimit = $mediaConfig['max_size'] * 1024 * 1024;

            // upload file
            $uploader = new FileUploader($allowedExtensions, $sizeLimit, $logger);
            
            $path = $this->get('kernel')->getRootDir() . '/../web';
            $handleUpload = $uploader->handleUpload($path . '/uploads', $mediaConfig['keep_file_name']);
            if (!$handleUpload) {
                $jsonResponse = array('error' => 'Impossible de sauvegarder le fichier sélectionné.');
            } else {
                $jsonResponse = array('success' => true);

                // save model
                $seMediaObject = SeMediaObjectQuery::create()->findPk($mediaObjectId);
                //        $logger->info('$seMediaObject = '.print_r($seMediaObject, true));
                // + transaction à faire

                $seMediaFile = new SeMediaFile();

                $seMediaFile->setLocale($mediaConfig['culture']);
                $seMediaFile->setCategoryId($mediaConfig['category_id']);
                $seMediaFile->setOnline(true);
                $seMediaFile->setTitle($uploader->getOriginalName());
                $seMediaFile->setName($uploader->getUploadName());
                $seMediaFile->setSize($uploader->getSize());
                $seMediaFile->setExtension($uploader->getExtension());
                $seMediaFile->setMimeType($uploader->getMimeType());
                $seMediaFile->setWidth($uploader->getWidth());
                $seMediaFile->setHeight($uploader->getHeight());

                $seMediaFile->save($con);
                        $logger->info('$seMediaFile = '.print_r($seMediaFile, true));

                $seMediaObject->addSeMediaFile($seMediaFile);
                $seMediaObject->save($con);
                //        $logger->info('$seMediaObject = '.print_r($seMediaObject, true));

                $con->commit();
            }
        } catch (\Exception $e) {
//            $logger->info('Exception! Message = ' . print_r($e->getMessage(), true));

            $con->rollback();
            $jsonResponse = array('error' => $e->getMessage());
        }

        // JSON formatted success/error message
        $response = new Response(htmlspecialchars(json_encode($jsonResponse), ENT_NOQUOTES));
        return $response;
    }

    /**
     * 
     * @param type $mediaFileId
     */
    public function deleteAction(
        $mediaFileId
    ) {
//      $logger = $this->get('logger');
//      $logger->info('UploadMediaController');
//      $logger->info('deleteAction');
//      $logger->info('$mediaFileId = '.print_r($mediaFileId, true));

        $session = $this->getRequest()->getSession();
        $mediaConfig = $session->get('studio_echo_media_bundle/'.$this->getRequest()->get('mediaParameterConfigKey'));
//        $logger->info('$mediaConfig = '.print_r($mediaConfig, true));
        
        try {
            // Get the current edit object
            $seMediaFile = SeMediaFileQuery::create()->joinWithI18n($mediaConfig['culture'])->findPk($mediaFileId);

            // Get file name
            $fileName = $seMediaFile->getName();

            // Remove object
            $seMediaFile->delete();

            // Remove file
            $path = $this->get('kernel')->getRootDir() . '/../web';
            $path .= '/uploads';
            unlink($path . DIRECTORY_SEPARATOR . $fileName);
//        $logger->info('unlink ok');

            $jsonResponse = array('success' => true);
        } catch (\Exception $e) {
//            $logger->info('Exception! Message = ' . print_r($e->getMessage(), true));

            $jsonResponse = array('error' => $e->getMessage());
        }

        // JSON formatted success/error message
        $response = new Response(json_encode($jsonResponse));
        return $response;
    }

    /**
     * 
     * @param type $mediaFileId
     */
    public function onlineAction(
    $mediaFileId
    ) {
//      $logger = $this->get('logger');
//      $logger->info('UploadMediaController');
//      $logger->info('onlineAction');
//      $logger->info('$mediaFileId = '.print_r($mediaFileId, true));

        try {
            // throw new \Exception('Une exception de test');
            // Get the current edit object
            $seMediaFile = SeMediaFileQuery::create()->findPk($mediaFileId);

            // Set online/offline object
            $seMediaFile->setOnline(!$seMediaFile->getOnline());
            $seMediaFile->save();

            $jsonResponse = array('success' => $seMediaFile->getOnline());
        } catch (\Exception $e) {
//            $logger->info('Exception! Message = ' . print_r($e->getMessage(), true));

            $jsonResponse = array('error' => $e->getMessage());
        }

        // JSON formatted success/error message
        $response = new Response(json_encode($jsonResponse));
        return $response;
    }

    /**
     * 
     * @param type $mediaFileId
     */
    public function editAction(
        $mediaFileId
    ) {
//      $logger = $this->get('logger');
//      $logger->info('UploadMediaController');
//      $logger->info('editAction');
//      $logger->info('$mediaFileId = '.print_r($mediaFileId, true));

        $request = $this->getRequest();
        
        $session = $this->getRequest()->getSession();
        $mediaConfig = $session->get('studio_echo_media_bundle/'.$request->get('mediaParameterConfigKey'));
//        $logger->info('$mediaConfig = '.print_r($mediaConfig, true));

        try {
            // Get the current edit object
            $seMediaFile = SeMediaFileQuery::create()->findPk($mediaFileId);

            // Set title / description / copyright fields
            $title = $request->get('title');
            //      $logger->info('title = '.$title);
            $description = $request->get('description');
            //      $logger->info('description = '.$description);
            $copyright = $request->get('copyright');
            //      $logger->info('copyright = '.$copyright);

            $seMediaFile->setLocale($mediaConfig['culture']);
            $seMediaFile->setTitle($title);
            $seMediaFile->setDescription($description);
            $seMediaFile->setCopyright($copyright);

            $seMediaFile->save();

            $jsonResponse = array('success' => true);
        } catch (\Exception $e) {
//            $logger->info('Exception! Message = ' . print_r($e->getMessage(), true));

            $jsonResponse = array('error' => $e->getMessage());
        }

        // JSON formatted success/error message
        $response = new Response(json_encode($jsonResponse));
        return $response;
    }

    /**
     * 
     */
    public function sortAction(
    ) {
        $request = $this->getRequest();

      $logger = $this->get('logger');
      $logger->info('UploadMediaController');
      $logger->info('sortAction');

        try {
            // gestion transaction
            $con = \Propel::getConnection(SeMediaObjectPeer::DATABASE_NAME);
            $con->beginTransaction();

            // Set sortable ranks
            $sorted_ids = $request->get('sort');
        $logger->info('sorted_ids = '.print_r($sorted_ids, true));
            $objectMediaId = $request->get('objectMediaId');
        $logger->info('objectMediaId = '.$objectMediaId);
            // Update sortable ranks
            // transaction
            $i = 1;
            foreach ($sorted_ids as $sorted_id) {
                $objectFile = SeObjectHasFileQuery::create()->filterBySeMediaObjectId($objectMediaId)->filterBySeMediaFileId($sorted_id)->findOne();
                
                // objectFile null if previously ajax deleted for example.
                if (!$objectFile)   break;
                
                $objectFile->setSortableRank($i);
                $objectFile->save($con);
                $i++;
            }

            $con->commit();
            $jsonResponse = array('success' => true);
        } catch (\Exception $e) {
            $logger->info('Exception! Message = ' . print_r($e->getMessage(), true));

            $con->rollback();
            $jsonResponse = array('error' => $e->getMessage());
        }

        // JSON formatted success/error message
        $response = new Response(json_encode($jsonResponse));
        return $response;
    }

}
