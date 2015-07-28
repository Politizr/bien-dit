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
     *
     * @param string $htmlText
     * @return array
     */
    public function explodeParagraphs($htmlText)
    {
        $htmlText = str_replace('</p>', '', $htmlText);
        $paragraphs = explode('<p>', $htmlText);
        array_shift($paragraphs);

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
        if (!$fileSystem->exists($destPath . $destFileName)) {
            $fileSystem->dumpFile($destPath . $destFileName, $image->getBody());

            return true;
        }

        return false;
    }
}
