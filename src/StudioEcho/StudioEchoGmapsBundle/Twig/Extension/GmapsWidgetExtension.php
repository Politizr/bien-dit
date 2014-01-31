<?php

/*
 * This file is part of the FOSFacebookBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace StudioEcho\StudioEchoGmapsBundle\Twig\Extension;

use FOS\FacebookBundle\Templating\Helper\FacebookHelper;
use Symfony\Component\DependencyInjection\ContainerInterface;

class GmapsWidgetExtension extends \Twig_Extension
{
    protected $container;
    protected $templating;
    protected $logger;

    /**
     * Constructor.
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container, $templating, $logger)
    {
        $this->container = $container;
        $this->templating = $templating;
        $this->logger = $logger;
    }

    /**
     * Returns a list of global functions to add to the existing list.
     *
     * @return array An array of global functions
     */
    public function getFunctions()
    {
        return array(
            'gmaps_init' => new \Twig_Function_Method($this, 'renderGmapsInit', array('is_safe' => array('html'))),
            'gmaps_widget' => new \Twig_Function_Method($this, 'renderGmapsWidget', array('is_safe' => array('html'))),
            'gmaps_pickercode' => new \Twig_Function_Method($this, 'renderGmapsPickerCode', array('is_safe' => array('html'))),
        );
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'gmaps_widget';
    }

    /**
     *
     */
    public function renderGmapsInit()
    {
        $this->logger->info('*** renderGmapsInit');

        // Load config
        $config = $this->container->getParameter('studio_echo_gmaps');
        $this->logger->info('$config = '.print_r($config, true));
        $apiInit = $config['api_init'];
        $this->logger->info('$apiInit = '.print_r($apiInit, true));

        $sensor = $apiInit['sensor']?'true':'false';
        $apiKey = $apiInit['api_key'];
        $region = $apiInit['region'];
        $version = $apiInit['version'];

        return $this->templating->render('StudioEchoGmapsBundle:Form:init.js.twig', array(
                    'sensor' => $sensor,
                    'apiKey' => $apiKey,
                    'region' => $region,
                    'version' => $version
                    ));
    }

    /**
     *
     */
    public function renderGmapsWidget()
    {
        $this->logger->info('*** renderGmapsWidget');

        return $this->templating->render('StudioEchoGmapsBundle:Form:widget.html.twig', array());
    }

    /**
     *
     */
    public function renderGmapsPickerCode($keyConfig)
    {
        $this->logger->info('*** renderGmapsPickerCode');
        $this->logger->info('$keyConfig = '.print_r($keyConfig, true));

        // Load config
        $config = $this->container->getParameter('studio_echo_gmaps');
        $this->logger->info('$config = '.print_r($config, true));
        $mapOptions = $config['options'][$keyConfig];
        $this->logger->info('$mapOptions = '.print_r($mapOptions, true));

        $scroolWheel = $mapOptions['scrool_wheel']?'true':'false';
        $center = $mapOptions['center'];
        $zoomLevel = $mapOptions['zoom_level'];
        $mapTypeId = $mapOptions['map_type_id'];

        return $this->templating->render('StudioEchoGmapsBundle:Form:pickercode.js.twig', array(
                    'scroolWheel' => $scroolWheel,
                    'center' => $center,
                    'zoomLevel' => $zoomLevel,
                    'mapTypeId' => $mapTypeId
                    ));
    }

}