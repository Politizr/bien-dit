<?php
namespace Politizr\FrontBundle\Lib\Tools;

use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Filesystem\Filesystem;

use Symfony\Component\Validator\Constraints\Image;

use GuzzleHttp;

use Politizr\Exception\BoxErrorException;

use Politizr\FrontBundle\Lib\SimpleImage;
use Politizr\FrontBundle\Lib\TimelineRow;
use Politizr\FrontBundle\Lib\Publication;
use Politizr\FrontBundle\Form\Type\PUMandateType;

use Politizr\Constant\QualificationConstants;

use Politizr\Model\PUMandateQuery;

use StudioEcho\Lib\StudioEchoUtils;

/**
 * Various tools methods
 *
 * @author Lionel Bouzonville
 */
class GlobalTools
{
    private $securityAuthorizationChecker;
    private $securityContext;

    private $requestStack;

    private $formFactory;
    private $validator;

    private $liipImagineController;
    private $liipImagineCacheManager;

    private $logger;

    /**
     *
     * @param @security.authorization_checker
     * @param @security.context
     * @param @request_stack
     * @param @form.factory
     * @param @validator
     * @param @liip_imagine.controller
     * @param @liip_imagine.cache.manager
     * @param @logger
     */
    public function __construct(
        $securityAuthorizationChecker,
        $securityContext,
        $requestStack,
        $formFactory,
        $validator,
        $liipImagineController,
        $liipImagineCacheManager,
        $logger
    ) {
        $this->securityAuthorizationChecker = $securityAuthorizationChecker;
        $this->securityContext = $securityContext;

        $this->requestStack = $requestStack;
        
        $this->formFactory = $formFactory;

        $this->validator = $validator;

        $this->liipImagineController = $liipImagineController;
        $this->liipImagineCacheManager = $liipImagineCacheManager;

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
     * @param  Request     $request
     * @param  string      $inputName            nom du champ input
     * @param  string      $destPath             chemin absolu de destination
     * @param  int         $maxWidth             largeur max > utilisé pour resize
     * @param  int         $maxHeight            hauteur max > utilisé pour resize
     * @param  int         $sizeLimit            Taille limite du fichier > 20 * 1024 * 1024 (20Mo)
     * @param  array|string $mimeTypes Types MIME http://www.iana.org/assignments/media-types/media-types.xhtml
     *
     */
    public function uploadXhrImage(Request $request, $inputName, $destPath, $maxWidth, $maxHeight, $sizeLimit = 20971520, $mimeTypes = 'image/*')
    {
        $this->logger->info('*** uploadXhrImage');

        $myRequestedFile = $request->files->get($inputName);
        if ($myRequestedFile === null) {
            throw new BoxErrorException('Fichier non existant.');
        } else if ($myRequestedFile->getError() > 0) {
            throw new BoxErrorException('Erreur upload n°'.$myRequestedFile->getError());
        } else {
            // Contrôle SF2 Image
            $imageConstraint = new Image(array(
                'mimeTypes' => $mimeTypes
            ));
            $errors = $this->validator->validateValue(
                $myRequestedFile,
                $imageConstraint
            );

            $msgErrors = array();
            foreach ($errors as $error) {
                $msgErrors['error'] = $error->getMessage();
            }

            if (!empty($msgErrors)) {
                throw new BoxErrorException(StudioEchoUtils::multiImplode($msgErrors, ' <br/> '));
            }

            // Construct file name
            $ext = $myRequestedFile->guessExtension();
            $fileName = md5(uniqid()) . '.' . $ext;

            //move the uploaded file to uploads folder;
            $movedFile = $myRequestedFile->move($destPath, $fileName);
            $this->logger->info('$movedFile = '.print_r($movedFile, true));
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
     * Copy a file to a destFile
     *
     * @param string $file folder & file name
     * @param string $destFile folder & file name
     * @param $force true to override if destFile already exists
     */
    public function copyFile($file, $force = true)
    {
        $fileInfo = pathinfo($file);
        $orgDirname = $fileInfo['dirname'];
        $orgExtension = $fileInfo['extension'];

        $newFileName = uniqid();

        $destFile = $orgDirname . '/' . $newFileName . '.' . $orgExtension;

        $fs = new Filesystem();
        $fs->copy($file, $destFile, $force);

        return $newFileName . '.' . $orgExtension;
    }

    /**
     * Explode HTML text in an array of containing all the paragraphs
     * http://stackoverflow.com/questions/8757826/i-need-to-split-text-delimited-by-paragraph-tag
     * http://stackoverflow.com/questions/7509774/php-explode-string-by-html-tag
     *
     * @param string $htmlText
     * @param boolean $onlyP Extract only <p></p> elements
     * @return array
     */
    public function explodeParagraphs($htmlText, $onlyP = false)
    {
        // $htmlText = str_replace('</p>', '', $htmlText);
        // $paragraphs = explode('<p>', $htmlText);
        // array_shift($paragraphs);

        if (empty($htmlText)) {
            return array();
        }

        // // $dom = new \DOMDocument('1.0', 'UTF-8');
        // $dom = new \DOMDocument("4.0", "utf-8");
        // // $dom->loadHTML($htmlText);
        // $dom->loadHTML(mb_convert_encoding($htmlText, 'HTML-ENTITIES', 'UTF-8'));
        // $xPath = new \DOMXPath($dom);
        // $entries = $xPath->evaluate("//p|//h1|//h2|//blockquote|//ul//li");
        // $paragraphs = array();
        // foreach ($entries as $entry) {
        //     dump($entry->nodeValue);
        //     $paragraphs[] = '<' . $entry->tagName . '>' . $entry->nodeValue .  '</' . $entry->tagName . '>';
        // }

        $paragraphs = array();
        $count = preg_match_all('/<p[^>]*>(.*?)<\/p>|<h\d[^>]*>(.*?)<\/h\d>|<ul[^>]*>(.*?)<\/ul>|<blockquote[^>]*>(.*?)<\/blockquote>/is', $htmlText, $matches);
        for ($i = 0; $i < $count; ++$i) {
            if (!$onlyP) {
                $paragraphs[] = $matches[0][$i];
            } else {
                if (!empty($matches[1][$i])) {
                    $paragraphs[] = '<p>'.$matches[1][$i].'</p>';
                }
            }
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

    /**
     * Validate constraints to data and manage XHR formatting message
     *
     * @param array $data array to validate
     * @param Constraint\Collection $collectionConstraint
     * @param array $errorString
     * @return boolean|string
     */
    public function validateConstraints($data, $collectionConstraint, & $errorString)
    {
        $errors = $this->validator->validateValue(
            $data,
            $collectionConstraint
        );

        if (count($errors) > 0) {
            $errorString = 'Merci de corriger les erreurs:<br/><ul>';
            foreach ($errors as $error) {
                $errorString .= '<li>' . $error->getMessage() . '</li>';
            }
            $errorString .= '</ul>';

            return false;
        }

        return true;
    }

    /**
     * Hydrate "TimelineRow" objects from raw sql results
     *
     * @param array|false $result
     * @return array[TimelineRow]
     */
    public function hydrateTimelineRows($result)
    {
        $this->logger->info('*** hydrateTimelineRows');

        $timeline = array();
        if ($result) {
            foreach ($result as $row) {
                $timelineRow = new TimelineRow();

                $timelineRow->setId($row['id']);
                $timelineRow->setTargetId($row['target_id']);
                $timelineRow->setTargetUserId($row['target_user_id']);
                $timelineRow->setTargetObjectName($row['target_object_name']);
                $timelineRow->setTitle($row['title']);
                $timelineRow->setPublishedAt($row['published_at']);
                $timelineRow->setType($row['type']);

                $timeline[] = $timelineRow;
            }
        }

        return $timeline;
    }

    /**
     * Hydrate "Publication" objects from raw sql results
     *
     * @param array|false $result
     * @return array[Publication]
     */
    public function hydratePublication($result)
    {
        $this->logger->info('*** hydratePublication');

        $publications = array();
        if ($result) {
            foreach ($result as $row) {
                $publication = new Publication($this, $this->requestStack);

                $publication->setId($row['id']);
                $publication->setTitle($row['title']);
                $publication->setFileName($row['fileName']);
                $publication->setDescription($row['description']);
                $publication->setSlug($row['slug']);
                $publication->setPublishedAt(new \DateTime($row['published_at']));
                $publication->setType($row['type']);

                $publications[] = $publication;
            }
        }

        return $publications;
    }

    /**
     * Format high number to human readeable number (1k, 1M)
     *
     * @param integer $number
     * @return integer
     */
    public function readeableNumber($number)
    {
        if ($number < 10000) {
            $number = number_format($number, 0, ',', ' ');
        } else {
            $d = $number < 1000000 ? 1000 : 1000000;
            $f = round($number / $d, 1);
            $number = number_format($f, $f - intval($f) ? 1 : 0, ',', ' ') . ($d == 1000 ? 'k' : 'M');
        }

        return $number;
    }

    /**
     * Get french label from month num
     *
     * @param integer $monthNum
     * @return string
     */
    public function getLabelFromMonthNum($monthNum)
    {
        if ($monthNum == 1) {
            return 'janvier';
        } elseif ($monthNum == 2) {
            return 'février';
        } elseif ($monthNum == 3) {
            return 'mars';
        } elseif ($monthNum == 4) {
            return 'avril';
        } elseif ($monthNum == 5) {
            return 'mai';
        } elseif ($monthNum == 6) {
            return 'juin';
        } elseif ($monthNum == 7) {
            return 'juillet';
        } elseif ($monthNum == 8) {
            return 'août';
        } elseif ($monthNum == 9) {
            return 'septembre';
        } elseif ($monthNum == 10) {
            return 'octobre';
        } elseif ($monthNum == 11) {
            return 'novembre';
        } elseif ($monthNum == 12) {
            return 'décembre';
        }

        return null;
    }

    /**
     * Get month num from french label
     *
     * @param string $month
     * @return integer
     */
    public function getNumFromMonthLabel($month)
    {
        if ($month == 'janvier') {
            return 1;
        } elseif ($month == 'février') {
            return 2;
        } elseif ($month == 'mars') {
            return 3;
        } elseif ($month == 'avril') {
            return 4;
        } elseif ($month == 'mai') {
            return 5;
        } elseif ($month == 'juin') {
            return 6;
        } elseif ($month == 'juillet') {
            return 7;
        } elseif ($month == 'août') {
            return 8;
        } elseif ($month == 'septembre') {
            return 9;
        } elseif ($month == 'octobre') {
            return 10;
        } elseif ($month == 'novembre') {
            return 11;
        } elseif ($month == 'décembre') {
            return 12;
        }

        return null;
    }

    /**
     * Apply a LIIP Imagine filter to an image and return the image url
     *
     * @param $baseUrl
     * @param $fileName
     * @param $fileWebPath   Relative web path
     * @param $filterName
     * @return string url
     */
    public function filterImage($baseUrl, $fileName, $fileWebPath, $filterName = 'facebook_share')
    {
        // $this->logger->info('*** getFilteredImageUrl');
        // $this->logger->info('$baseUrl = '.print_r($baseUrl, true));
        // $this->logger->info('$fileName = '.print_r($fileName, true));
        // $this->logger->info('$fileWebPath = '.print_r($fileWebPath, true));
        // $this->logger->info('$filterName = '.print_r($filterName, true));

        $this->liipImagineController->filterAction(
            new Request(),
            $fileWebPath.$fileName,
            $filterName
        );

        $imageUrl = $this->liipImagineCacheManager->getBrowserPath($fileWebPath.$fileName, $filterName);
        // $this->logger->info('$imageUrl = '.print_r($imageUrl, true));

        return $imageUrl;
    }

    /**
     * Tinyfy URL
     *
     * @param $uri
     * @return string
     */
    public function getTinyUrl($uri)
    {
        // with tinyurl
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://tinyurl.com/api-create.php?url=".$uri);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $tinyUri = curl_exec($ch);
        curl_close($ch);

        return $tinyUri;
    }
}
