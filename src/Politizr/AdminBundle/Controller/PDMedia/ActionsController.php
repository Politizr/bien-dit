<?php

namespace Politizr\AdminBundle\Controller\PDMedia;

use Admingenerated\PolitizrAdminBundle\BasePDMediaController\ActionsController as BaseActionsController;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

use Politizr\Constant\PathConstants;
use Politizr\Constant\DocumentConstants;

use Politizr\Model\PDMediaQuery;

use Politizr\Exception\InconsistentDataException;

/**
 * ActionsController
 */
class ActionsController extends BaseActionsController
{
    /**
     * XHR Dropzone file upload
     *
     * @param $request
     * @param $pk
     * @return JsonResponse
     */
    public function filenameUploadAction(Request $request, $pk)
    {
        try {
            $media = PDMediaQuery::create()->findPk($pk);
            if (!$media) {
                throw new InconsistentDataException('PDMedia pk-'.$pk.' not found.');
            }

            // Chemin des images
            $uploadWebPath = PathConstants::DOCUMENT_UPLOAD_WEB_PATH;
            $path = $this->get('kernel')->getRootDir() . '/../web' . $uploadWebPath;

            // Appel du service d'upload ajax
            $fileName = $this->get('politizr.tools.global')->uploadXhrImage(
                $request,
                'file',
                $path,
                DocumentConstants::DOC_IMAGE_MAX_WIDTH,
                DocumentConstants::DOC_IMAGE_MAX_HEIGHT,
                $media->getFileName()
            );

            // create thumbnail
            $imagePath = $this->get('kernel')->getRootDir() . '/../web' . $uploadWebPath;
            $thbFileName = DocumentConstants::DOC_THUMBNAIL_PREFIX . $fileName;
            $this->get('politizr.tools.global')->copyFile(
                $imagePath . $fileName,
                $imagePath . $thbFileName
            );
            $this->get('politizr.tools.global')->resizeImage(
                $imagePath . $thbFileName,
                DocumentConstants::DOC_THUMBNAIL_MAX_WIDTH,
                DocumentConstants::DOC_THUMBNAIL_MAX_HEIGHT
            );

            // update media
            $media = $this->get('politizr.functional.document')->updateMediaFile($media, $path . $fileName);

            $data = ['success' => true, 'fileName' => $fileName, 'thbFileName' => $thbFileName, 'filePath' => $uploadWebPath ];
        } catch(\Exception $e) {
            $data = ['success' => false, 'error' => $e->getMessage()];
            return new JsonResponse($data, 500);
        }

        return new JsonResponse($data);
    }

    /**
     * XHR Dropzone file upload delete
     *
     * @param $request
     * @param $pk
     * @return JsonResponse
     */
    public function filenameDeleteAction(Request $request, $pk)
    {
        try {
            $media = PDMediaQuery::create()->findPk($pk);
            if (!$media) {
                throw new InconsistentDataException('PDMedia pk-'.$pk.' not found.');
            }

            // File syst deletion
            $fileName = $media->getFileName();
            if ($fileName) {
                $uploadWebPath = PathConstants::DOCUMENT_UPLOAD_WEB_PATH;
                $path = $this->get('kernel')->getRootDir() . '/../web' . $uploadWebPath;
                unlink($path . $fileName);
                unlink($path . DocumentConstants::DOC_THUMBNAIL_PREFIX . $fileName);

                // delete cache folder
                $this->get('politizr.tools.global')->clearFilesFromFolder($this->get('kernel')->getRootDir() . '/../web/uploads/cache', $fileName);
            }

            // /!\ keeping file name in object

            $data = ['success' => true];
        } catch(\Exception $e) {
            $data = ['success' => false, 'error' => 'Une erreur s\'est produite! Msg = '.$e->getMessage()];
            return new JsonResponse($data, 500);
        }

        return new JsonResponse($data);
    }
}