<?php
namespace Politizr\AdminBundle\Twig;

use Politizr\Exception\InconsistentDataException;

use Politizr\Constant\ObjectTypeConstants;

use Politizr\Model\PDocumentInterface;
use Politizr\Model\PEOperation;

use Politizr\Model\PLCityQuery;

/**
 * Document admin twig extension
 *
 * @author Lionel Bouzonville
 */
class PolitizrAdminLocalizationExtension extends \Twig_Extension
{
    private $documentService;
    private $documentLocalizationFormType;
    
    private $formFactory;
    private $router;
    private $logger;

    /**
     *
     * @param politizr.functional.document
     * @param politizr.form.type.document_localization
     * @param form.factory
     * @param router
     * @param logger
     */
    public function __construct(
        $documentService,
        $documentLocalizationFormType,
        $formFactory,
        $router,
        $logger
    ) {
        $this->documentService = $documentService;
        $this->documentLocalizationFormType = $documentLocalizationFormType;
        
        $this->formFactory = $formFactory;
        $this->router = $router;
        $this->logger = $logger;
    }

    /* ######################################################################################################## */
    /*                                              FONCTIONS ET FILTRES                                        */
    /* ######################################################################################################## */

    /**
     *  Renvoie la liste des fonctions
     */
    public function getFunctions()
    {
        return array(
            'adminDocumentLoc'  => new \Twig_SimpleFunction(
                'adminDocumentLoc',
                array($this, 'adminDocumentLoc'),
                array('is_safe' => array('html'), 'needs_environment' => true)
            ),
            'adminOperationCities'  => new \Twig_SimpleFunction(
                'adminOperationCities',
                array($this, 'adminOperationCities'),
                array('is_safe' => array('html'), 'needs_environment' => true)
            ),
        );
    }

    /* ######################################################################################################## */
    /*                                              FUNCTIONS                                                   */
    /* ######################################################################################################## */

    /**
     * User's document loc
     *
     * @param PDocumentInterface $document
     * @param string $type Object type (debate / reaction)
     * @return string
     */
    public function adminDocumentLoc(\Twig_Environment $env, PDocumentInterface $document, $type)
    {
        $this->logger->info('*** adminDocumentLoc');
        // $this->logger->info('$document = '.print_r($document, true));
        // $this->logger->info('$type = '.print_r($type, true));

        // Document's localization
        $form = $this->formFactory->create(
            $this->documentLocalizationFormType,
            $document,
            array(
                'data_class' => $type,
                'user' => $document->getUser(),
            )
        );

        // Construction du rendu du tag
        $html = $env->render(
            'PolitizrAdminBundle:Fragment\\Document:_localization.html.twig',
            array(
                'form' => $form->createView(),
            )
        );

        return $html;
    }


    /**
     * Operation's cities
     *
     * @param PEOperation $operation
     * @return string
     */
    public function adminOperationCities(\Twig_Environment $env, PEOperation $operation)
    {
        $this->logger->info('*** adminOperationCities');
        // $this->logger->info('$operation = '.print_r($operation, true));
        // $this->logger->info('$type = '.print_r($type, true));

        $cities = PLCityQuery::create()
            ->distinct()
            ->usePEOScopePLCQuery()
                ->filterByPEOperationId($operation->getId())
            ->endUse()
            ->find();

        $html = $env->render(
            'PolitizrAdminBundle:Fragment\\Localization:_cities.html.twig',
            array(
                'cities' => $cities,
            )
        );

        return $html;
    }


    public function getName()
    {
        return 'admin_localization_extension';
    }
}