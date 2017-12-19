<?php
namespace Politizr\FrontBundle\Listener;

use Oneup\UploaderBundle\Event\PostPersistEvent;

use Symfony\Component\HttpFoundation\File\Exception\UploadException;

use Politizr\Constant\PathConstants;

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
        dump($uuid);
        dump($type);

        //if everything went fine
        $response = $event->getResponse();
        
        $file = $event->getFile();
        dump($file);

        // resize image max width and/or max height
        $this->globalTools->resizeImage(
            $file->getRealPath(),
            1200,
            1200
        );

        // create thumbnail
        $thumbnailRealPath = $file->getPath() . '/' . 'thb-' . $file->getFilename();
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
        $this->documentService->createMediaFromFileByDocUuid($file, $uuid, $type);

        $response['files'] = [[
            'url' => $request->getSchemeAndHttpHost() . PathConstants::DEBATE_UPLOAD_WEB_PATH . $file->getFilename(),
            'thumbnail_url'=> $request->getSchemeAndHttpHost() . PathConstants::DEBATE_UPLOAD_WEB_PATH . 'thb-' . $file->getFilename(),
            'name'=> $file->getBaseName(),
            'type'=> $file->getType(),
            'size'=> $file->getSize(),
        ]];

        return $response;
    }
}
