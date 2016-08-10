<?php
namespace Politizr\FrontBundle\Lib\Xhr;

use Symfony\Component\HttpFoundation\Request;

use Politizr\Constant\ObjectTypeConstants;
use Politizr\Constant\ListingConstants;

use StudioEcho\Lib\StudioEchoUtils;

use Politizr\Exception\InconsistentDataException;
use Politizr\Exception\BoxErrorException;

use Politizr\Model\PLRegionQuery;
use Politizr\Model\PLDepartmentQuery;
use Politizr\Model\PLCityQuery;

use Politizr\FrontBundle\Form\Type\PLDepartmentChoiceType;

/**
 * XHR service for localization management.
 * beta
 *
 * @author Lionel Bouzonville
 */
class XhrLocalization
{
    private $securityTokenStorage;
    private $templating;
    private $formFactory;
    private $localizationManager;
    private $logger;

    /**
     *
     * @param @security.token_storage
     * @param @templating
     * @param @form.factory
     * @param @politizr.localization.tag
     * @param @logger
     */
    public function __construct(
        $securityTokenStorage,
        $templating,
        $formFactory,
        $localizationManager,
        $logger
    ) {
        $this->securityTokenStorage = $securityTokenStorage;

        $this->templating = $templating;

        $this->formFactory = $formFactory;
        $this->localizationManager = $localizationManager;

        $this->logger = $logger;
    }

    /**
     * Refresh select city widget
     * beta
     */
    public function citiesSelectList(Request $request)
    {
        // Request arguments
        $departmentUuid = $request->get('departmentUuid');
        // $this->logger->info('$departmentUuid = ' . print_r($departmentUuid, true));

        $cities = $this->localizationManager->getCityChoices($departmentUuid);
        $optionValues[] = '<option value="">Choisissez votre ville</option>';
        foreach ($cities as $cityName => $cityUuid) {
            $optionValues[] = '<option value="' . $cityUuid . '">' . $cityName . '</option>';
        }

        return array(
            'html' => $optionValues
        );
    }
}
