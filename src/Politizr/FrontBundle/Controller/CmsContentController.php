<?php

namespace Politizr\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use StudioEchoBundles\StudioEchoMediaBundle\Lib\StudioEchoMediaManager;

use Politizr\Model\CmsContentQuery;

/**
 *
 *
 * @author Lionel Bouzonville
 */
class CmsContentController extends Controller
{
    /**
     * Page contenu CMS
     *
     * @param string $slug
     */
    public function detailAction($slug)
    {
        $logger = $this->get('logger');
        $logger->info('*** detailAction');

        $content = CmsContentQuery::create()
            ->filterByOnline(true)
            ->filterBySlug($slug)
            ->findOne();
        
        if (!$content) {
            throw $this->createNotFoundException('Content not found.');
        }

        // Diaporamas associés
        $medias = StudioEchoMediaManager::getMediaList($content->getId(), 'Politizr\Model\CmsContent', 'fr', 1);

        // Documents associés
        $documents = StudioEchoMediaManager::getMediaList($content->getId(), 'Politizr\Model\CmsContent', 'fr', 2);

        return $this->render('PolitizrFrontBundle:CmsContent:detail.html.twig', array(
            'content' => $content,
            'medias' => $medias,
            'documents' => $documents,
        ));
    }
}
