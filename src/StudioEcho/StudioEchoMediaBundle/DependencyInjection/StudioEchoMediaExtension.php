<?php

namespace StudioEcho\StudioEchoMediaBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 * 
 * TODO / avancement:
 *  - définir la structure & tester la validité des arguments fournis (classe Extension) / 80%
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class StudioEchoMediaExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        // Validate & prepare config array
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        // Bundle configuration
//        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
//        $loader->load('services.yml');
        
        // Twig methods extension
        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('twig.xml');
        
        
        $container->setParameter('studio_echo_media', $config);
    }
    
//    public function getXsdValidationBasePath()
//    {
//        return __DIR__.'/../Resources/config/';
//    }
//
//    public function getNamespace()
//    {
//        return 'http://www.example.com/symfony/schema/';
//    }
}
