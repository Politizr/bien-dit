<?php
# Test/MyBundle/Twig/Extension/MyBundleExtension.php

namespace StudioEcho\StudioEchoMediaBundle\Twig\Extension;

use Symfony\Component\HttpKernel\KernelInterface;

use StudioEcho\StudioEchoMediaBundle\Lib\StudioEchoMediaManager;

class MediaExtension extends \Twig_Extension
{
    public function __construct()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            'seMediaGetName' => new \Twig_Function_Method($this, 'seMediaGetName'),
            'seMediaGetList' => new \Twig_Function_Method($this, 'seMediaGetList')
        );
    }
    
    /**
     * 
     */
    public function seMediaGetName($objectId = 1, $objectClassname = 'My\Object\Model', $locale = 'fr', $categoryId = 1, $rank = null)
    {
        $seMediaFile = StudioEchoMediaManager::getMedia($objectId, $objectClassname, $locale, $categoryId, $rank);
        
        if ($seMediaFile) {
            return $seMediaFile->getName();
        } else {
            return '';
        }
    }

    /**
     * 
     */
    public function seMediaGetList($objectId = 1, $objectClassname = 'My\Object\Model', $locale = 'fr', $categoryId = 1)
    {
        $seMediaList = StudioEchoMediaManager::getMediaList($objectId, $objectClassname, $locale, $categoryId);
        
        return $seMediaList;
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'mediaextension';
    }
}