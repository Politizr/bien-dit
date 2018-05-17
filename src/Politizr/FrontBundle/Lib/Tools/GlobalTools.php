<?php
namespace Politizr\FrontBundle\Lib\Tools;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Form\Form;

use GuzzleHttp;

use Politizr\Exception\BoxErrorException;

use Politizr\FrontBundle\Lib\SimpleImage;
use Politizr\FrontBundle\Lib\TimelineRow;
use Politizr\FrontBundle\Lib\Publication;
use Politizr\FrontBundle\Lib\InteractedPublication;
use Politizr\FrontBundle\Form\Type\PUMandateType;

use Politizr\Constant\QualificationConstants;
use Politizr\Constant\ObjectTypeConstants;

use Politizr\Model\PDocumentInterface;
use Politizr\Model\PUser;
use Politizr\Model\PUMandateQuery;

use Politizr\FrontBundle\Lib\Tools\StaticTools;

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
    private $session;

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
     * @param @session
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
        $session,
        $formFactory,
        $validator,
        $liipImagineController,
        $liipImagineCacheManager,
        $logger
    ) {
        $this->securityAuthorizationChecker = $securityAuthorizationChecker;
        $this->securityContext = $securityContext;

        $this->requestStack = $requestStack;
        $this->session = $session;
        
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
        // $this->logger->info('*** uploadXhrImage');

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
                throw new BoxErrorException($this->multiImplode($msgErrors, ' <br/> '));
            }

            // Construct file name
            $ext = $myRequestedFile->guessExtension();
            $fileName = md5(uniqid()) . '.' . $ext;

            //move the uploaded file to uploads folder;
            $movedFile = $myRequestedFile->move($destPath, $fileName);
            // $this->logger->info('$movedFile = '.print_r($movedFile, true));
        }

        // Resize de la photo
        $resized = $this->resizeImage($destPath . $fileName, $maxWidth, $maxHeight);

        return $fileName;
    }

    /**
     * Resize an image
     *
     * @param string $absolutePath
     * @param int $maxWidth
     * @param int $maxHeight
     * @return SimpleImage
     */
    public function resizeImage($absolutePath, $maxWidth, $maxHeight)
    {
        // $this->logger->info('*** resizeImage');

        // Resize de la photo
        $resized = false;
        $image = new SimpleImage();
        $image->load($absolutePath);
        if ($width = $image->getWidth() > $maxWidth) {
            $image->resizeToWidth($maxWidth);
            $resized = true;
        }
        if ($height = $image->getHeight() > $maxHeight) {
            $image->resizeToHeight($maxHeight);
            $resized = true;
        }
        if ($resized) {
            $image->save($absolutePath);
        }

        return $image;
    }

    /**
     * Copy a file to a destFile
     *
     * @param string $file folder & file name
     * @param string $destFile folder & file name
     * @param $force true to override if destFile already exists
     */
    public function copyFile($file, $destFile = null, $force = true)
    {
        $fileInfo = pathinfo($file);
        $orgDirname = $fileInfo['dirname'];
        $orgExtension = $fileInfo['extension'];

        $newFileName = uniqid();

        if(!$destFile) {
            $destFile = $orgDirname . '/' . $newFileName . '.' . $orgExtension;
        }

        $fs = new Filesystem();
        $fs->copy($file, $destFile, $force);

        return $newFileName . '.' . $orgExtension;
    }

    /**
     * Explode HTML text in an array of containing all the paragraphs
     *
     * @param string $htmlText
     * @param boolean $onlyP Extract only <p></p> elements
     * @return array
     */
    public function explodeParagraphs($htmlText, $onlyP = false)
    {
        if (empty($htmlText)) {
            return array();
        }

        $paragraphs = array();
        $crawler = new Crawler('<meta charset="utf-8">' . $htmlText);

        $rule = 'body > *';
        if ($onlyP) {
            $rule = 'body > p';
        }

        $nodes = $crawler->filter($rule);

        // manage medium left / right thumbnail to not create new paragraph
        $nodesToConcat = null;
        foreach ($nodes as $node) {
            if (
                strpos($node->getAttribute('class'), 'medium-insert-images-left') !== false
                || strpos($node->getAttribute('class'), 'medium-insert-images-right') !== false
                || strpos($node->getAttribute('class'), 'medium-insert-embeds-left') !== false
                || strpos($node->getAttribute('class'), 'medium-insert-embeds-right') !== false
            ) {
                $nodesToConcat[] = $node;
            } else {
                if ($nodesToConcat && !empty($nodesToConcat)) {
                    $concatenedNodes = null;
                    foreach ($nodesToConcat as $nodeToConcat) {
                        $concatenedNodes .= $nodeToConcat->ownerDocument->saveHtml($nodeToConcat);
                    }
                    $paragraphs[] = $concatenedNodes . $node->ownerDocument->saveHtml($node);
                    $nodesToConcat = null;
                } else {
                    // cf. https://github.com/symfony/symfony/issues/18609#issuecomment-212952371
                    $paragraphs[] = $node->ownerDocument->saveHtml($node);
                }
            }
        }

        // medium left / right in last position
        if ($nodesToConcat && !empty($nodesToConcat)) {
            foreach ($nodesToConcat as $nodeToConcat) {
                $concatenedNodes .= $nodeToConcat->ownerDocument->saveHtml($nodeToConcat);
            }
            $paragraphs[] = $concatenedNodes;
        }

        return $paragraphs;
    }

    /**
    * Truncates text.
    * cf http://stackoverflow.com/questions/16583676/shorten-text-without-splitting-words-or-breaking-html-tags
    * 
    * Cuts a string to the length of $length and replaces the last characters
    * with the ending if the text is longer than length.
    *
    * ### Options:
    *
    * - `ending` Will be used as Ending and appended to the trimmed string
    * - `exact` If false, $text will not be cut mid-word
    * - `html` If true, HTML tags would be handled correctly
    *
    * @param string  $text String to truncate.
    * @param integer $length Length of returned string, including ellipsis.
    * @param array $options An array of html attributes and options.
    * @return string Trimmed string.
    */
    public function truncate($text, $length = 100, $options = array()) {
        $default = array(
            'ending' => '...', 'exact' => true, 'html' => false
        );
        $options = array_merge($default, $options);
        extract($options);

        if ($html) {
            if (mb_strlen(preg_replace('/<.*?>/', '', $text)) <= $length) {
                return $text;
            }
            $totalLength = mb_strlen(strip_tags($ending));
            $openTags = array();
            $truncate = '';

            preg_match_all('/(<\/?([\w+]+)[^>]*>)?([^<>]*)/', $text, $tags, PREG_SET_ORDER);
            foreach ($tags as $tag) {
                if (!preg_match('/img|br|input|hr|area|base|basefont|col|frame|isindex|link|meta|param/s', $tag[2])) {
                    if (preg_match('/<[\w]+[^>]*>/s', $tag[0])) {
                        array_unshift($openTags, $tag[2]);
                    } else if (preg_match('/<\/([\w]+)[^>]*>/s', $tag[0], $closeTag)) {
                        $pos = array_search($closeTag[1], $openTags);
                        if ($pos !== false) {
                            array_splice($openTags, $pos, 1);
                        }
                    }
                }
                $truncate .= $tag[1];

                $contentLength = mb_strlen(preg_replace('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|&#x[0-9a-f]{1,6};/i', ' ', $tag[3]));
                if ($contentLength + $totalLength > $length) {
                    $left = $length - $totalLength;
                    $entitiesLength = 0;
                    if (preg_match_all('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|&#x[0-9a-f]{1,6};/i', $tag[3], $entities, PREG_OFFSET_CAPTURE)) {
                        foreach ($entities[0] as $entity) {
                            if ($entity[1] + 1 - $entitiesLength <= $left) {
                                $left--;
                                $entitiesLength += mb_strlen($entity[0]);
                            } else {
                                break;
                            }
                        }
                    }

                    $truncate .= mb_substr($tag[3], 0 , $left + $entitiesLength);
                    break;
                } else {
                    $truncate .= $tag[3];
                    $totalLength += $contentLength;
                }
                if ($totalLength >= $length) {
                    break;
                }
            }
        } else {
            if (mb_strlen($text) <= $length) {
                return $text;
            } else {
                $truncate = mb_substr($text, 0, $length - mb_strlen($ending));
            }
        }
        if (!$exact) {
            $spacepos = mb_strrpos($truncate, ' ');
            if (isset($spacepos)) {
                if ($html) {
                    $bits = mb_substr($truncate, $spacepos);
                    preg_match_all('/<\/([a-z]+)>/', $bits, $droppedTags, PREG_SET_ORDER);
                    if (!empty($droppedTags)) {
                        foreach ($droppedTags as $closingTag) {
                            if (!in_array($closingTag[1], $openTags)) {
                                array_unshift($openTags, $closingTag[1]);
                            }
                        }
                    }
                }
                $truncate = mb_substr($truncate, 0, $spacepos);
            }
        }
        $truncate .= $ending;

        if ($html) {
            foreach ($openTags as $tag) {
                $truncate .= '</'.$tag.'>';
            }
        }

        return $truncate;
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
        // $this->logger->info('*** hydrateTimelineRows');

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
        // $this->logger->info('*** hydratePublication');

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
     * Hydrate "InteractedPublication" objects from raw sql results
     *
     * @param array|false $result
     * @return array[InteractedPublication]
     */
    public function hydrateInteractedPublication($result, $beginAt, $endAt)
    {
        // $this->logger->info('*** hydrateInteractedPublication');

        $publications = array();
        if ($result) {
            foreach ($result as $row) {
                $publication = new InteractedPublication();

                $publication->setId($row['id']);
                $publication->setAuthorId($row['author_id']);
                $publication->setTitle($row['title']);
                $publication->setDescription($row['description']);
                $publication->setPublishedAt(new \DateTime($row['published_at']));
                $publication->setType($row['type']);

                $publication->setBeginAt($beginAt);
                $publication->setEndAt($endAt);
                if (isset($row['nb_reactions'])) {
                    $publication->setNbReactions($row['nb_reactions']);
                }
                if (isset($row['nb_comments'])) {
                    $publication->setNbComments($row['nb_comments']);
                }
                if (isset($row['nb_notifications'])) {
                    $publication->setNbNotifications($row['nb_notifications']);
                }

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
     * @param $pathFileName     Relative web path + file name
     * @param $filterName
     * @return string url
     */
    public function filterImage($pathFileName, $filterName = 'facebook_share')
    {
        // $this->logger->info('*** getFilteredImageUrl');
        // $this->logger->info('$pathFileName = '.print_r($pathFileName, true));
        // $this->logger->info('$filterName = '.print_r($filterName, true));

        $this->liipImagineController->filterAction(
            new Request(),
            $pathFileName,
            $filterName
        );

        $imageUrl = $this->liipImagineCacheManager->getBrowserPath($pathFileName, $filterName);
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

    /**
     * Prepare string for injection into MySQL "IN" query
     *
     * @param array|int|string $ids
     * @return string|false
     */
    public function getInQuery($ids)
    {
        if (is_array($ids)) {
            $inQueryIds = implode(',', $ids);
            if (empty($inQueryIds)) {
                $inQueryIds = 0;
            }
        } else {
            $inQueryIds = $ids;
        }
        return $inQueryIds;
    }

    /**
     * Check if Politizr is in public or private mode
     *
     * @param $visitor PUser current connected user
     * @param PDocumentInterface Document
     * @param $mode public|private|debate|we|<nb of days>
     * @return boolean
     */
    public function isPrivateMode($visitor, PDocumentInterface $document, $mode, $userIds)
    {
        $publishedAt = $document->getPublishedAt();
        $author =  $document->getPUser();
        $topic = $document->getPCTopic();
        $type = $document->getType();

        // public if
        if ($visitor) {
            // user is connected
            return false;
        } elseif ($author && in_array($author->getId(), $userIds) && $topic == null) {
            // author in list of public users
            return false;
        } elseif ($author && $author->isWithOperation() && $topic == null) {
            // author has subscribe an "OP"
            return false;
        } elseif ($document && $document->isWithPrivateTag() && $topic == null) {
            // document has private tag
            return false;
        } elseif ($mode == 'debate' && $type == ObjectTypeConstants::TYPE_DEBATE && $topic == null) {
            // app in public mode
            return false;
        } elseif ($mode == 'public' && $topic == null) {
            // app in public mode
            return false;
        } elseif ($mode == 'we' && $topic == null) {
            // app in we mode and datetime is we
            $dayOfWeek = date('w');
            if ($dayOfWeek == 0 || $dayOfWeek == 6) {
                return false;
            }
        } elseif (is_int($mode) && $publishedAt instanceof \DateTime && $topic == null) {
            // app in X days mode and document is older than X days
            $now = new \DateTime();
            $diff = $now->diff($publishedAt);
            if ($diff->days > $mode) {
                return false;
            }
        }

        return true;
    }

    /**
     * Post inscription/login URL if set else null
     *
     * @return string
     */
    public function getRefererUrl()
    {
        $referer = $this->session->get('inscription/referer');

        // remove from session
        $this->session->remove('inscription/referer');

        if (strpos($referer, 'debat') || // debate detail 
            strpos($referer, 'reaction') || // reaction detail
            strpos($referer, '@') || // user detail
            strpos($referer, 'groupes') // circles pages
        ) {
            return $referer; 
        }

        return null;
    }

    /**
     * Get string formatted errors
     *
     * @param Form $form
     * @return string
     */
    public function getAjaxFormErrors(Form $form)
    {
        $errors = array();

        foreach ($form->getErrors() as $key => $error) {
            $errors['error'] = $error->getMessage();
        }

        foreach ($form->all() as $child) {
            if (!$child->isValid()) {
                $errors[] = $this->getAjaxFormErrors($child);
            }
        }

        return $this->multiImplode($errors, ' <br/> ');
    }


    /**
     * Get string from multidim array
     * cf. http://stackoverflow.com/questions/3899971/implode-and-explode-multi-dimensional-arrays
     *
     * @param array $array
     * @param string $glue
     * @return string
     */
    public function multiImplode($array, $glue)
    {
        $ret = '';

        foreach ($array as $item) {
            if (is_array($item)) {
                $ret .= $this->multiImplode($item, $glue) . $glue;
            } else {
                $ret .= $item . $glue;
            }
        }

        $ret = substr($ret, 0, 0-strlen($glue));

        return $ret;
    }
}
