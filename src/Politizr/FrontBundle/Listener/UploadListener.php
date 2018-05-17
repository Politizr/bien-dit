<?php
namespace Politizr\FrontBundle\Listener;

use Oneup\UploaderBundle\Event\PostPersistEvent;

use Symfony\Component\HttpFoundation\File\Exception\UploadException;

use Symfony\Component\Validator\Constraints\Image;

use Politizr\Constant\PathConstants;
use Politizr\Constant\DocumentConstants;

use Politizr\FrontBundle\Lib\Tools\StaticTools;


/**
 *
 * @see https://github.com/1up-lab/OneupUploaderBundle/blob/master/Resources/doc/custom_logic.md
 * @author Lionel Bouzonville
 */
class UploadListener
{
    private $securityTokenStorage;
    private $validator;
    private $documentService;
    private $globalTools;
    private $logger;

    /**
     *
     * @param @security.token_storage
     * @param @validator
     * @param @politizr.functional.document
     * @param @politizr.tools.global
     * @param @logger
     */
    public function __construct(
        $securityTokenStorage,
        $validator,
        $documentService,
        $globalTools, $logger
    ) {
        $this->securityTokenStorage = $securityTokenStorage;
        $this->validator = $validator;

        $this->documentService = $documentService;

        $this->globalTools = $globalTools;

        $this->logger = $logger;
    }

    /**
     *
     */
    public function onUpload(PostPersistEvent $event)
    {
        $request = $event->getRequest();
        $uuid = $request->get('uuid');
        $type = $request->get('type');
        
        $file = $event->getFile();

        // SF image validator
        $mimeTypes = 'image/*';
        $imageConstraint = new Image(array(
            'mimeTypes' => $mimeTypes
        ));
        $errors = $this->validator->validateValue(
            $file,
            $imageConstraint
        );

        $msgErrors = array();
        foreach ($errors as $error) {
            $msgErrors['error'] = $error->getMessage();
        }

        if (!empty($msgErrors)) {
            return $this->manageError($event, $msgErrors);
        }

        // resize image max width and/or max height
        try {
            $image = $this->globalTools->resizeImage(
                $file->getRealPath(),
                1200,
                1200
            );

            // create thumbnail
            $thumbnailRealPath = $file->getPath() . '/' . DocumentConstants::DOC_THUMBNAIL_PREFIX . $file->getFilename();
            $this->globalTools->copyFile(
                $file->getRealPath(),
                $thumbnailRealPath
            );
            $this->globalTools->resizeImage(
                $thumbnailRealPath,
                150,
                150
            );

            // persist media
            $media = $this->documentService->createMediaFromSimpleImageByDocUuid($image, $uuid, $type);
        } catch (\Exception $e) {
            return $this->manageError($event, 'Désolé, une erreur s\'est produite pendant le traitement de votre image.');
        }

        // everything went fine
        $response = $event->getResponse();
        $response['files'] = [[
            'url' => $request->getSchemeAndHttpHost() . PathConstants::DEBATE_UPLOAD_WEB_PATH . $file->getFilename(),
            'thumbnail_url'=> $request->getSchemeAndHttpHost() . PathConstants::DEBATE_UPLOAD_WEB_PATH . 'thb-' . $file->getFilename(),
            'name'=> $image->getBaseName(),
            'type'=> $file->getType(),
            'size'=> $image->getSize(),
        ]];
        $response['media_uuid'] = $media->getUuid();

        return $response;
    }

    /**
     *
     * @param PostPersistEvent $event
     * @param array|string $msgErrors
     * @return Response
     */
    public function manageError(PostPersistEvent $event, $msgErrors)
    {
        // error response
        $request = $event->getRequest();
        $response = $event->getResponse();
        $response['files'] = [[
            'url' => $request->getSchemeAndHttpHost() . PathConstants::DEBATE_UPLOAD_WEB_PATH . DocumentConstants::DOC_IMAGE_UPLOAD_FAILED,
            'thumbnail_url'=> $request->getSchemeAndHttpHost() . PathConstants::DEBATE_UPLOAD_WEB_PATH . DocumentConstants::DOC_IMAGE_UPLOAD_FAILED,
            'name'=> DocumentConstants::DOC_IMAGE_UPLOAD_FAILED,
        ]];
        
        if (is_array($msgErrors)) {
            $response['error'] = $this->globalTools->multiImplode($msgErrors, ' <br/> ');
        } else {
            $response['error'] = $msgErrors;
        }
        return $response;
    }
}
