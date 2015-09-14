<?php
namespace Politizr\FrontBundle\Lib\Tools;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Filesystem\Filesystem;

use Politizr\Exception\InconsistentDataException;
use Politizr\Exception\FormValidationException;

use GuzzleHttp;

use Politizr\FrontBundle\Lib\SimpleImage;

use Politizr\Constant\QualificationConstants;

use Politizr\Model\PUMandateQuery;

use Politizr\FrontBundle\Form\Type\PUMandateType;

/**
 * Various tools methods
 *
 * @author Lionel Bouzonville
 */
class GlobalTools
{
    private $securityAuthorizationChecker;
    private $securityContext;

    private $formFactory;

    private $logger;

    /**
     *
     * @param @security.authorization_checker
     * @param @security.context
     * @param @form.factory
     * @param @logger
     */
    public function __construct($securityAuthorizationChecker, $securityContext, $formFactory, $logger)
    {
        $this->securityAuthorizationChecker = $securityAuthorizationChecker;
        $this->securityContext = $securityContext;
        
        $this->formFactory = $formFactory;

        $this->logger = $logger;
    }

    /**
     * Get file extension from mime type
     *
     * @param string $mimeType
     * @return string
     */
    public function getExtensionByMimeType($mimeType)
    {
        $extensions = array(
            'image/bmp' => 'bmp',
            'image/x-ms-bmp' => 'bmp',
            'image/cgm' => 'cgm',
            'image/g3fax' => 'g3',
            'image/gif' => 'gif',
            'image/ief' => 'ief',
            'image/jpeg' => 'jpeg',
            'image/ktx' => 'ktx',
            'image/png' => 'png',
            'image/prs.btif' => 'btif',
            'image/sgi' => 'sgi',
            'image/svg+xml' => 'svg',
            'image/tiff' => 'tiff',
            'image/vnd.adobe.photoshop' => 'psd',
            'image/vnd.dece.graphic' => 'uvi',
            'image/vnd.dvb.subtitle' => 'sub',
            'image/vnd.djvu' => 'djvu',
            'image/vnd.dwg' => 'dwg',
            'image/vnd.dxf' => 'dxf',
            'image/vnd.fastbidsheet' => 'fbs',
            'image/vnd.fpx' => 'fpx',
            'image/vnd.fst' => 'fst',
            'image/vnd.fujixerox.edmics-mmr' => 'mmr',
            'image/vnd.fujixerox.edmics-rlc' => 'rlc',
            'image/vnd.ms-modi' => 'mdi',
            'image/vnd.ms-photo' => 'wdp',
            'image/vnd.net-fpx' => 'npx',
            'image/vnd.wap.wbmp' => 'wbmp',
            'image/vnd.xiff' => 'xif',
            'image/webp' => 'webp',
            'image/x-3ds' => '3ds',
            'image/x-cmu-raster' => 'ras',
            'image/x-cmx' => 'cmx',
            'image/x-freehand' => 'fh',
            'image/x-icon' => 'ico',
            'image/x-mrsid-image' => 'sid',
            'image/x-pcx' => 'pcx',
            'image/x-pict' => 'pic',
            'image/x-portable-anymap' => 'pnm',
            'image/x-portable-bitmap' => 'pbm',
            'image/x-portable-graymap' => 'pgm',
            'image/x-portable-pixmap' => 'ppm',
            'image/x-rgb' => 'rgb',
            'image/x-tga' => 'tga',
            'image/x-xbitmap' => 'xbm',
            'image/x-xpixmap' => 'xpm',
            'image/x-xwindowdump' => 'xwd',
        );

        return $extensions[$mimeType];
    }


    /**
     * Upload XHR d'une image
     *
     * @param  string      $inputName            nom du champ input
     * @param  string      $destPath             chemin absolu de destination
     * @param  int         $maxWidth             largeur max > utilisé pour resize
     * @param  int         $maxHeight            hauteur max > utilisé pour resize
     * @param  int         $sizeLimit            Taille limite du fichier > 5 * 1024 * 1024 (5Mo)
     * @param  array       $allowedExtensions    Extensions de fichier autorisées
     *
     */
    public function uploadXhrImage(Request $request, $inputName, $destPath, $maxWidth, $maxHeight, $sizeLimit = 5242880, $allowedExtensions = array('jpg', 'jpeg', 'png'))
    {
        $this->logger->info('*** uploadXhrImage');

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

    /**
     * Explode HTML text in an array of containing all the paragraphs
     * http://stackoverflow.com/questions/8757826/i-need-to-split-text-delimited-by-paragraph-tag
     * http://stackoverflow.com/questions/7509774/php-explode-string-by-html-tag
     *
     * @param string $htmlText
     * @return array
     */
    public function explodeParagraphs($htmlText)
    {
        // $htmlText = str_replace('</p>', '', $htmlText);
        // $paragraphs = explode('<p>', $htmlText);
        // array_shift($paragraphs);

        // $dom = new \DOMDocument('1.0', 'UTF-8');
        $dom = new \DOMDocument("4.0", "utf-8");
        // $dom->loadHTML($htmlText);
        $dom->loadHTML(mb_convert_encoding($htmlText, 'HTML-ENTITIES', 'UTF-8'));
        $xPath = new \DOMXPath($dom);
        $entries = $xPath->evaluate("//p|//h1|//h2|//blockquote|//ul//li");
        $paragraphs = array();
        foreach ($entries as $entry) {
            $paragraphs[] = '<' . $entry->tagName . '>' . $entry->nodeValue .  '</' . $entry->tagName . '>';
        }

        return $paragraphs;
    }

    /**
     * Create form views for each user's mandate
     * @todo to refactor in a form construction dedicated service
     *
     * @param integer $userId
     * @return array|FormViewsPUMandateType
     */
    public function getFormMandateViews($userId)
    {
        // Mandats
        $mandates = PUMandateQuery::create()
            ->filterByPUserId($userId)
            ->filterByPQTypeId(QualificationConstants::TYPE_ELECTIV)
            ->orderByBeginAt('desc')
            ->find();

        // Création des form + vues associées pour MAJ des mandats
        $formMandateViews = array();
        foreach ($mandates as $mandate) {
            $formMandate = $this->formFactory->create(new PUMandateType(QualificationConstants::TYPE_ELECTIV), $mandate);
            $formMandateViews[] = $formMandate->createView();
        }

        return $formMandateViews;
    }

    /**
     * Compute "C" or "E" suffix depending on connected user role
     *
     * @return string
     */
    public function computeProfileSuffix()
    {
        $user = $this->securityContext->getToken()->getUser();

        if ($this->securityAuthorizationChecker->isGranted('ROLE_ELECTED') &&
            $user &&
            $user->getOnline()) {
            return 'E';
        } elseif ($this->securityAuthorizationChecker->isGranted('ROLE_CITIZEN') &&
            $user &&
            $user->getOnline()) {
            return 'C';
        }

        return null;
    }

    /**
     * Count the number of words in in given HTML text
     *
     * @param $html
     * @return integer
     */
    public function countWords($html)
    {
        $text = strip_tags($html, '<br><br/>');
        $nbWords = str_word_count($text);

        return $nbWords;
    }

    /**
     * Download a file from url a paste it in the destPath with the destFileName
     *
     * @param string $url
     * @param string $destPath
     * @param string $destFileName
     * @return boolean
     */
    public function downloadFileFromUrl($url, $destPath, $destFileName)
    {
        $guzzleClient = new GuzzleHttp\Client();
        $fileSystem = new FileSystem();

        $image = $guzzleClient->get($url);
        $headers = $image->getHeaders();

        if (isset($headers['content-type'][0]) || isset($headers['Content-Type'][0])) {
            if (isset($headers['content-type'][0])) {
                $contentType = $headers['content-type'][0];
            } elseif (isset($headers['Content-Type'][0])) {
                $contentType = $headers['Content-Type'][0];
            }

            $extension = $this->getExtensionByMimeType($contentType);
            $fileName = $destFileName . '.' . $extension;

            if (!$fileSystem->exists($destPath . $fileName)) {
                $fileSystem->dumpFile($destPath . $fileName, $image->getBody());

                return $fileName;
            }
        }

        return false;
    }

    /**
     * Remove empty <p></p> in the html text
     *
     * @param string $html
     * @return string
     */
    public function removeEmptyParagraphs($html)
    {
        $pattern = "/<p[^>]*><\\/p[^>]*>/";
        $html = preg_replace($pattern, '', $html);

        $pattern = "/<p[^>]*><br><\\/p[^>]*>/";
        $html = preg_replace($pattern, '', $html);

        return $html;
    }
}
