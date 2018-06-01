<?php
namespace Politizr\AdminBundle\Twig;

use Politizr\Exception\InconsistentDataException;

use Politizr\Constant\PathConstants;

use Politizr\Model\PCircle;
use Politizr\Model\PCTopic;

/**
 * Circle admin twig extension
 *
 * @author Lionel Bouzonville
 */
class PolitizrAdminCircleExtension extends \Twig_Extension
{
    protected $kernel;

    private $globalTools;

    private $router;
    private $logger;

    /**
     *
       @param @kernel
     * @param politizr.functional.document
     * @param politizr.tools.global
     * @param form.factory
     * @param router
     * @param logger
     */
    public function __construct(
        $kernel,
        $globalTools,
        $router,
        $logger
    ) {
        $this->kernel = $kernel;

        $this->globalTools = $globalTools;

        $this->router = $router;
        $this->logger = $logger;
    }

    /* ######################################################################################################## */
    /*                                              FONCTIONS ET FILTRES                                        */
    /* ######################################################################################################## */
    /**
     * Filters list
     *
     * @return array
     */
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter(
                'circleFileSize',
                array($this, 'circleFileSize'),
                array('is_safe' => array('html'), 'needs_environment' => true)
            ),
            new \Twig_SimpleFilter(
                'topicFileSize',
                array($this, 'topicFileSize'),
                array('is_safe' => array('html'), 'needs_environment' => true)
            ),
        );
    }

    /* ######################################################################################################## */

    /* ######################################################################################################## */
    /*                                              FILTERS                                                     */
    /* ######################################################################################################## */

    /**
     * Compute circle's file size
     *
     * @param PCircle $circle
     * @return html
     */
    public function circleFileSize(\Twig_Environment $env, PCircle $circle)
    {
        if ($fileName = $circle->getLogoFileName()) {
            if (file_exists($this->kernel->getRootDir() . '/../web' . PathConstants::CIRCLE_UPLOAD_WEB_PATH.$fileName)) {
                $fileSize = fileSize($this->kernel->getRootDir() . '/../web' . PathConstants::CIRCLE_UPLOAD_WEB_PATH.$fileName);
                
                return $fileSize;
            }

        }

        return null;
    }

    /**
     * Compute topic's file size
     *
     * @param PCTopic $topic
     * @return html
     */
    public function topicFileSize(\Twig_Environment $env, PCTopic $topic)
    {
        if ($fileName = $topic->getFileName()) {
            if (file_exists($this->kernel->getRootDir() . '/../web' . PathConstants::CIRCLE_UPLOAD_WEB_PATH.$fileName)) {
                $fileSize = fileSize($this->kernel->getRootDir() . '/../web' . PathConstants::CIRCLE_UPLOAD_WEB_PATH.$fileName);
                
                return $fileSize;
            }

        }

        return null;
    }


    /* ######################################################################################################## */
    /*                                              FUNCTIONS                                                   */
    /* ######################################################################################################## */



    public function getName()
    {
        return 'admin_circle_extension';
    }
}
