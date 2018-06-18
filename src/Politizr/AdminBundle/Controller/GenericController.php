<?php

namespace Politizr\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\JsonResponse;

use Politizr\Constant\PathConstants;
use Politizr\Constant\DocumentConstants;
use Politizr\Constant\ObjectTypeConstants;

use Politizr\Exception\InconsistentDataException;

/**
 * GenericController
 */
class GenericController extends Controller
{
    /**
     * XHR TinyMCE file upload
     *
     * @param $request
     * @param $pk
     * @return JsonResponse
     */
    public function tmceFileUploadAction(Request $request, $pk)
    {
        $modelClass = $request->get('modelClass');
        $maxWidth = $request->get('maxWidth');
        $maxHeight = $request->get('maxHeight');
        try {
            $queryClass = $modelClass . 'Query';
            if (!class_exists($queryClass)) {
                throw new InconsistentDataException('Query class not found.');
            }

            $content = $queryClass::create()->findPk($pk);
            if (!$content) {
                throw new InconsistentDataException('CmsContent pk-'.$pk.' not found.');
            }

            // Chemin des images
            $uploadWebPath = PathConstants::DOCUMENT_UPLOAD_WEB_PATH;
            $path = $this->get('kernel')->getRootDir() . '/../web' . $uploadWebPath;

            // Appel du service d'upload ajax
            $fileName = $this->get('politizr.tools.global')->uploadXhrImage(
                $request,
                'file',
                $path,
                $maxWidth,
                $maxHeight
            );

            $location = $uploadWebPath . $fileName;

            // { location : '/uploaded/image/path/image.png' }
            $data = ['location' => $location ];
        } catch(\Exception $e) {
            $data = ['error' => $e->getMessage()];
            return new JsonResponse($data, 500);
        }

        return new JsonResponse($data);
    }
}
