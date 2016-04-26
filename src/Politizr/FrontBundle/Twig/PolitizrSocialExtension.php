<?php
namespace Politizr\FrontBundle\Twig;

use Politizr\Exception\InconsistentDataException;

use Politizr\Constant\PathConstants;
use Politizr\Constant\ObjectTypeConstants;

use Politizr\Model\PDocumentInterface;

/**
 * Social network twig extension
 *
 * @author Lionel Bouzonville
 */
class PolitizrSocialExtension extends \Twig_Extension
{
    private $securityTokenStorage;
    private $securityAuthorizationChecker;

    private $router;
    private $templating;

    private $globalTools;

    private $logger;

    /**
     * @param @security.token_storage
     * @param @security.authorization_checker
     * @param @router
     * @param @templating
     * @param @politizr.tools.global
     * @param @logger
     */
    public function __construct(
        $securityTokenStorage,
        $securityAuthorizationChecker,
        $router,
        $templating,
        $globalTools,
        $logger
    ) {
        $this->securityTokenStorage = $securityTokenStorage;
        $this->securityAuthorizationChecker =$securityAuthorizationChecker;

        $this->router = $router;
        $this->templating = $templating;

        $this->globalTools = $globalTools;

        $this->logger = $logger;
    }

    /* ######################################################################################################## */
    /*                                              FONCTIONS ET FILTRES                                        */
    /* ######################################################################################################## */

    /**
     *  Renvoie la liste des filtres
     */
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter(
                'ogImage',
                array($this, 'ogImage'),
                array('is_safe' => array('html'))
            ),
            new \Twig_SimpleFilter(
                'tcImage',
                array($this, 'tcImage'),
                array('is_safe' => array('html'))
            ),
            new \Twig_SimpleFilter(
                'share',
                array($this, 'share'),
                array('is_safe' => array('html'))
            ),
        );
    }

    /* ######################################################################################################## */
    /*                                             FILTERS                                                      */
    /* ######################################################################################################## */

    /**
     * Compute images for og facebook tag
     *
     * @param PDDebate|PDReaction|PUser $subject
     * @param string $baseUrl
     * @return html
     */
    public function ogImage($subject, $baseUrl)
    {
        // $this->logger->info('*** ogImage');
        // $this->logger->info('$subject = '.print_r($subject, true));
        // $this->logger->info('$baseUrl = '.print_r($baseUrl, true));

        if ($fileName = $subject->getFileName()) {
            $fileWebPath = PathConstants::DEBATE_UPLOAD_WEB_PATH;
            if ($subject->getType() == ObjectTypeConstants::TYPE_REACTION) {
                $fileWebPath = PathConstants::REACTION_UPLOAD_WEB_PATH;
            } elseif ($subject->getType() == ObjectTypeConstants::TYPE_USER) {
                $fileWebPath = PathConstants::USER_UPLOAD_WEB_PATH;
            }
            $imageUrls[] = $this->globalTools->filterImage($baseUrl, $fileName, $fileWebPath, 'facebook_share');
        }

        $imageUrls[] = $baseUrl.'/bundles/politizrfront/images/share_facebook.jpg';

        // Construction du rendu du tag
        $html = $this->templating->render(
            'PolitizrFrontBundle:Navigation\\Social:_ogImage.html.twig',
            array(
                'imageUrls' => $imageUrls,
            )
        );
        $this->logger->info('$html = '.print_r($html, true));

        return $html;
    }

    /**
     * Compute image for twitter card
     *
     * @param PDDebate|PDReaction|PUser $subject
     * @param string $baseUrl
     * @return string
     */
    public function tcImage($subject, $baseUrl)
    {
        // $this->logger->info('*** tcImage');
        // $this->logger->info('$subject = '.print_r($subject, true));
        // $this->logger->info('$baseUrl = '.print_r($baseUrl, true));

        if ($fileName = $subject->getFileName()) {
            $fileWebPath = PathConstants::DEBATE_UPLOAD_WEB_PATH;
            if ($subject->getType() == ObjectTypeConstants::TYPE_REACTION) {
                $fileWebPath = PathConstants::REACTION_UPLOAD_WEB_PATH;
            } elseif ($subject->getType() == ObjectTypeConstants::TYPE_USER) {
                $fileWebPath = PathConstants::USER_UPLOAD_WEB_PATH;
            }

            $imageUrl = $this->globalTools->filterImage($baseUrl, $fileName, $fileWebPath, 'twitter_share');
        } else {
            $imageUrl = $baseUrl.'/bundles/politizrfront/images/share_twitter.jpg';
        }

        return $imageUrl;
    }

    /**
     * Share content
     *
     * @param PDDebate|PDReaction|PUser $subject
     * @param string $baseUrl
     * @return html
     */
    public function share($subject, $uri)
    {
        $tinyUri = $this->globalTools->getTinyUrl($uri);

        if ($subject->getType() == ObjectTypeConstants::TYPE_DEBATE || $subject->getType() == ObjectTypeConstants::TYPE_REACTION) {
            $tweet = strip_tags($subject->getTitle());

            $toTweet = '«'.$tweet.'»';
            $this->logger->info('$toTweet = '.print_r($toTweet, true));

            // Paragraphs explode
            // $toTweet = $this->globalTools->explodeParagraphs($subject->getDescription());
            // $this->logger->info('$toTweet = '.print_r($toTweet, true));
            // $toTweet = array_slice($toTweet, 0, 1);
            // $this->logger->info('$toTweet = '.print_r($toTweet, true));
            // $toTweet = $toTweet[0];
            // $this->logger->info('$toTweet = '.print_r($toTweet, true));
            // $toTweet = strip_tags($toTweet);
            // $this->logger->info('$toTweet = '.print_r($toTweet, true));
            // $toTweet = html_entity_decode($toTweet);
            // $this->logger->info('$toTweet = '.print_r($toTweet, true));
            // $tweet = substr($toTweet, 0, 100) . '...';
        } elseif ($subject->getType() == ObjectTypeConstants::TYPE_USER) {
            if ($subject->isQualified()) {
                $toTweet = 'Élu';
                if ($subject->getGender() == 'Madame') {
                    $toTweet .= 'e';
                }
            } else {
                $toTweet = 'Citoyen';
                if ($subject->getGender() == 'Madame') {
                    $toTweet .= 'ne';
                }
            }
            $this->logger->info('$toTweet = '.print_r($toTweet, true));

            $toTweet .= ' ' . $subject->getFullName();
            $this->logger->info('$toTweet = '.print_r($toTweet, true));

            if ($biography = $subject->getBiography()) {
                $toTweet .=  ', ' . $subject->getBiography();
                $this->logger->info('$toTweet = '.print_r($toTweet, true));
            }

            $toTweet = strip_tags($toTweet);
            $this->logger->info('$toTweet = '.print_r($toTweet, true));
            $toTweet = html_entity_decode($toTweet);
            $this->logger->info('$toTweet = '.print_r($toTweet, true));
            $tweet = substr($toTweet, 0, 100) . '...';
        }

        // Construction du rendu du tag
        $html = $this->templating->render(
            'PolitizrFrontBundle:Navigation\\Social:_share.html.twig',
            array(
                'uri' => $uri,
                'tinyUri' => $tinyUri,
                'tweet' => $tweet,
            )
        );
        $this->logger->info('$html = '.print_r($html, true));

        return $html;
    }

    /* ######################################################################################################## */
    /*                                              FUNCTIONS                                                   */
    /* ######################################################################################################## */




    public function getName()
    {
        return 'p_e_social';
    }
}
