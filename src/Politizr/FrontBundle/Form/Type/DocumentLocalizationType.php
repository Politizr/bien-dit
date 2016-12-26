<?php

namespace Politizr\FrontBundle\Form\Type;

use Politizr\Constant\LocalizationConstants;
use Politizr\FrontBundle\Lib\Manager\LocalizationManager;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

use Symfony\Component\Validator\Constraints\NotBlank;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;

use Politizr\Model\PUser;

/**
 * User localization
 * beta
 *
 * @author Lionel Bouzonville
 */
class DocumentLocalizationType extends AbstractType
{
    private $localizationManager;

    /**
     * @param politizr.manager.localization
     */
    public function __construct(LocalizationManager $localizationManager)
    {
        $this->localizationManager = $localizationManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $user = $options['user'];

        $builder->add('uuid', 'hidden', array(
            'required' => true,
        ));
        
        $builder->add('type', 'hidden', array(
            'mapped' => false,
            'required' => true,
            'data' => $options['data_class']
        ));
        
        // Preset the localization list
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($user) {
            $form = $event->getForm();
            $document = $event->getData();

            $currentType = LocalizationConstants::TYPE_CITY;
            $currentUuid = null;
            if ($cityId = $document->getPLCityId()) {
                $currentType = LocalizationConstants::TYPE_CITY;
                $currentUuid = $this->localizationManager->getCityUuidByCityId($cityId);
            } elseif ($departmentId = $document->getPLDepartmentId()) {
                $currentType = LocalizationConstants::TYPE_DEPARTMENT;
                $currentUuid = $this->localizationManager->getDepartmentUuidByDepartmentId($departmentId);
            } elseif ($regionId = $document->getPLRegionId()) {
                $currentType = LocalizationConstants::TYPE_REGION;
                $currentUuid = $this->localizationManager->getRegionUuidByRegionId($regionId);
            } elseif ($countryId = $document->getPLCountryId()) {
                $currentType = LocalizationConstants::TYPE_COUNTRY;
                // $currentUuid = $this->localizationManager->getCountryUuidByCountryId($countryId);
            }

            // Geo type list
            $choices = array(
                'Une ville' => LocalizationConstants::TYPE_CITY,
                'Un département' => LocalizationConstants::TYPE_DEPARTMENT,
                'Une région' => LocalizationConstants::TYPE_REGION,
                'Toute la France' => LocalizationConstants::TYPE_COUNTRY,
            );

            $form->add('loc_type', 'choice', array(
                'label' => 'Localisation de la publication',
                'required' => true,
                'mapped' => false,
                'choices' => $choices,
                'choices_as_values' => true,
                'multiple' => false,
                'expanded' => true,
                'empty_data'  => null,
                'data' => $currentType,
                'constraints' => new NotBlank(array('message' => 'Choix d\'une localisation obligatoire.')),
            ));

            // Localization city type
            $form->add('localization_city', LocalizationCityChoiceType::class, array(
                'required' => false,
                'mapped' => false,
                'current_uuid' => $currentUuid,
                'user_city_id' => $user->getPLCityId(),
            ));

            // Localization department type
            $form->add('localization_department', LocalizationDepartmentChoiceType::class, array(
                'required' => false,
                'mapped' => false,
                'current_uuid' => $currentUuid,
                'user_city_id' => $user->getPLCityId(),
            ));

            // Localization region type
            $form->add('localization_region', LocalizationRegionChoiceType::class, array(
                'required' => false,
                'mapped' => false,
                'current_uuid' => $currentUuid,
                'user_city_id' => $user->getPLCityId(),
            ));

            $event->setData($document);
        });

        $builder->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
            $form = $event->getForm();
            $document = $event->getForm()->getData();

            $type = $form->get('loc_type')->getData();
            if ($type == LocalizationConstants::TYPE_CITY) {
                $currentUuid = $form->get('localization_city')->get('city')->getData();
                $cityId = $this->localizationManager->getCityIdByCityUuid($currentUuid);
                
                $document->setPLCityId($cityId);
                $document->setPLDepartmentId(null);
                $document->setPLRegionId(null);
                $document->setPLCountryId(null);
            } elseif ($type == LocalizationConstants::TYPE_DEPARTMENT) {
                $currentUuid = $form->get('localization_department')->get('department')->getData();
                $departmentId = $this->localizationManager->getDepartmentIdByDepartmentUuid($currentUuid);
                
                $document->setPLCityId(null);
                $document->setPLDepartmentId($departmentId);
                $document->setPLRegionId(null);
                $document->setPLCountryId(null);
            } elseif ($type == LocalizationConstants::TYPE_REGION) {
                $currentUuid = $form->get('localization_region')->get('region')->getData();
                $regionId = $this->localizationManager->getRegionIdByRegionUuid($currentUuid);
                
                $document->setPLCityId(null);
                $document->setPLDepartmentId(null);
                $document->setPLRegionId($regionId);
                $document->setPLCountryId(null);
            } elseif ($type == LocalizationConstants::TYPE_COUNTRY) {
                $document->setPLCityId(null);
                $document->setPLDepartmentId(null);
                $document->setPLRegionId(null);
                $document->setPLCountryId(LocalizationConstants::FRANCE_ID);
            } 
        });
    }

    /**
     *
     * @return string
     */
    public function getName()
    {
        return 'document_localization';
    }

    /**
     *
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Politizr\Model\PDocumentInterface',
            'user' => null,
        ));
    }
}
