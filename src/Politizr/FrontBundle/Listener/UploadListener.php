<?php
namespace Politizr\FrontBundle\Listener;

use Oneup\UploaderBundle\Event\PostPersistEvent;

use Symfony\Component\HttpFoundation\File\Exception\UploadException;

use Politizr\Constant\PathConstants;
use Politizr\Constant\DocumentConstants;

/**
 *
 * @see https://github.com/1up-lab/OneupUploaderBundle/blob/master/Resources/doc/custom_logic.md
 * @author Lionel Bouzonville
 */
class UploadListener
{
    private $securityTokenStorage;
    private $documentService;
    private $globalTools;
    private $logger;

    /**
     *
     * @param @security.token_storage
     * @param @politizr.functional.document
     * @param @politizr.tools.global
     * @param @logger
     */
    public function __construct($securityTokenStorage, $documentService, $globalTools, $logger)
    {
        $this->securityTokenStorage = $securityTokenStorage;

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

        // // error tests
        // throw new UploadException('Oooh no, error occurs');
        // 
        // $response = $event->getResponse();
        // $response = [[
        //     "error" => "Image must be in JPG format",
        //     "url" => "", 
        //     "thumbnail_url" => "", 
        //     "delete_url" => "", 
        //     "delete_type" => "DELETE", 
        //     "name" => "broken_image.jpg", 
        //     "size" => 78191
        // ]];
        // return $response;

        // resize image max width and/or max height
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
}
