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
            
            // Menu
            new Knp\Bundle\MenuBundle\KnpMenuBundle(),

            // Pager
            new WhiteOctober\PagerfantaBundle\WhiteOctoberPagerfantaBundle(),
            
            // HTML2PDF
            new Ensepar\Html2pdfBundle\EnseparHtml2pdfBundle(),

            // Generator
            new Avocode\FormExtensionsBundle\AvocodeFormExtensionsBundle(),
            new Admingenerator\GeneratorBundle\AdmingeneratorGeneratorBundle(),
            new Admingenerator\UserBundle\AdmingeneratorUserBundle(),
            new Liuggio\ExcelBundle\LiuggioExcelBundle(),

            // Propel
            new Propel\PropelBundle\PropelBundle(),
            new Bazinga\Bundle\FakerBundle\BazingaFakerBundle(),
            
            // Liip Imagine
            new Liip\ImagineBundle\LiipImagineBundle(),

            // Géolocalisation
            new Bazinga\Bundle\GeocoderBundle\BazingaGeocoderBundle(),

            // Project Bundle,
            new Politizr\FrontBundle\PolitizrFrontBundle(),
            new Politizr\AdminBundle\PolitizrAdminBundle(),
        );

        if (in_array($this->getEnvironment(), array('dev', 'test', 'stage'))) {
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
        }

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config/config_'.$this->getEnvironment().'.yml');
    }
}
