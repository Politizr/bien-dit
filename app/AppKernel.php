<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            // Core
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Symfony\Bundle\AsseticBundle\AsseticBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            new JMS\AopBundle\JMSAopBundle(),
            new JMS\SecurityExtraBundle\JMSSecurityExtraBundle(),

            // FOSUserBundle
            new FOS\UserBundle\FOSUserBundle(),
            
            // OAuthBundle
            new HWI\Bundle\OAuthBundle\HWIOAuthBundle(),
            
            // HTML2PDF
            new Ensepar\Html2pdfBundle\EnseparHtml2pdfBundle(),

            // Propel
            new Propel\PropelBundle\PropelBundle(),
            new Bazinga\Bundle\FakerBundle\BazingaFakerBundle(),
            new Glorpen\Propel\PropelBundle\GlorpenPropelBundle(),
            
            // Admin Generator & dependencies
            new Admingenerator\GeneratorBundle\AdmingeneratorGeneratorBundle(),
            new JMS\DiExtraBundle\JMSDiExtraBundle($this),
            new Knp\Bundle\MenuBundle\KnpMenuBundle(),
            new WhiteOctober\PagerfantaBundle\WhiteOctoberPagerfantaBundle(),
    
            // Liip Imagine
            new Liip\ImagineBundle\LiipImagineBundle(),

            // Géolocalisation
            new Bazinga\Bundle\GeocoderBundle\BazingaGeocoderBundle(),

            // FOSElastica
            new FOS\ElasticaBundle\FOSElasticaBundle(),

            // Project Bundle,
            new Politizr\FrontBundle\PolitizrFrontBundle(),
            new Politizr\AdminBundle\PolitizrAdminBundle(),
        );

        if (in_array($this->getEnvironment(), array('dev', 'test', 'stage'))) {
            $bundles[] = new Symfony\Bundle\DebugBundle\DebugBundle();
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
            $bundles[] = new Elao\WebProfilerExtraBundle\WebProfilerExtraBundle();
        }

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config/config_'.$this->getEnvironment().'.yml');
    }
}
