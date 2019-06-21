<?php

namespace Politizr\FrontBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;

use Symfony\Component\Validator\Constraints\NotBlank;

use Politizr\Model\PLDepartmentQuery;
use Politizr\Model\PLCityQuery;

/**
 * Localization custom form type
 * /!\ instanciation must be created by service only
 *
 * @author Lionel Bouzonville
 */
class LocalizationChoiceType extends AbstractType
{
    private $localizationManager;

    /**
     *
     * @param @politizr.localization.tag
     */
    public function __construct(
        $localizationManager
    ) {
        $this->localizationManager = $localizationManager;
    }

    /**
     *
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // prefill
        $cityUuid = $this->localizationManager->getCityUuidByCityId($options['city_id']);
        $departmentUuid = $this->localizationManager->getDepartmentUuidByCityId($options['city_id']);
        $outOfFrance = $this->localizationManager->isOutOfFranceByCityId($options['city_id']);

        // Français hors de France
        $builder->add('out_of_france', 'checkbox', array(
            'label' => 'J\'habite HORS de France',
            'mapped' => false,
            'attr' => array('class' => 'out_of_france'),
            'data' => $outOfFrance,
        ));

        // Department (out of france) type list
        $circonscriptionChoices = $this->localizationManager->getCirconscriptionChoices();
        $builder->add('circonscription', 'choice', array(
            'label' => $options['label_circonscription'],
            'mapped' => false,
            'required' => true,
            'choices' => $circonscriptionChoices,
            'choices_as_values' => true,
            'multiple' => false,
            'expanded' => false,
            'placeholder' => 'Choisissez votre circonscription',
            'empty_data'  => null,
            'attr' => array('class' => 'select2_choice circonscription_choice'),
            'constraints' => new NotBlank(array('message' => 'Choix d\'une circonscription obligatoire.')),
            'data' => $departmentUuid,
        ));

        // Department (france) type list
        $departmentChoices = $this->localizationManager->getDepartmentChoices();
        $builder->add('department', 'choice', array(
            'label' => $options['label_department'],
            'mapped' => false,
            'required' => true,
            'choices' => $departmentChoices,
            'choices_as_values' => true,
            'multiple' => false,
            'expanded' => false,
            'placeholder' => 'Choisissez votre département',
            'empty_data'  => null,
            'attr' => array('class' => 'select2_choice department_choice'),
            'constraints' => new NotBlank(array('message' => 'Choix d\'un département obligatoire.')),
            'data' => $departmentUuid,
        ));

        // FORM MODIFIERS
        // see http://symfony.com/doc/2.8/cookbook/form/dynamic_form_modification.html#cookbook-form-events-underlying-data
        $formCityModifier = function (FormInterface $form, $departmentUuid = null, $cityUuid = null, $options) {
            // City type list / 30438
            $cityChoices = $this->localizationManager->getCityChoices($departmentUuid);
            $form->add('city', 'choice', array(
                'label' => $options['label_city'],
                'mapped' => false,
                'required' => true,
                'choices' => $cityChoices,
                'choices_as_values' => true,
                'multiple' => false,
                'expanded' => false,
                'placeholder' => 'Choisissez votre ville',
                'empty_data'  => null,
                'attr' => array('class' => 'select2_choice city_choice'),
                'constraints' => new NotBlank(array('message' => 'Choix d\'une ville obligatoire.')),
                'data' => $cityUuid,
            ));
        };

        // set required false / out of france checkbox
        $updateFieldsModifier = function (FormInterface $form, $outOfFrance = null, $departmentChoices, $circonscriptionChoices, $departmentUuid, $cityUuid, $options) {
            if ($outOfFrance) {
                $cityChoices = $this->localizationManager->getCityChoices($departmentUuid);
                $form->add('department', 'choice', array(
                    'label' => $options['label_department'],
                    'mapped' => false,
                    'required' => false,
                    'choices' => $departmentChoices,
                    'choices_as_values' => true,
                    'multiple' => false,
                    'expanded' => false,
                    'placeholder' => 'Choisissez votre département',
                    'empty_data'  => null,
                    'attr' => array('class' => 'select2_choice department_choice'),
                    'data' => $departmentUuid,
                ));
                $form->add('city', 'choice', array(
                    'label' => $options['label_city'],
                    'mapped' => false,
                    'required' => false,
                    'choices' => $cityChoices,
                    'choices_as_values' => true,
                    'multiple' => false,
                    'expanded' => false,
                    'placeholder' => 'Choisissez votre ville',
                    'empty_data'  => null,
                    'attr' => array('class' => 'select2_choice city_choice'),
                    'data' => $cityUuid,
                ));
            } else {        
                $form->add('circonscription', 'choice', array(
                    'label' => $options['label_circonscription'],
                    'mapped' => false,
                    'required' => false,
                    'choices' => $circonscriptionChoices,
                    'choices_as_values' => true,
                    'multiple' => false,
                    'expanded' => false,
                    'placeholder' => 'Choisissez votre circonscription',
                    'empty_data'  => null,
                    'attr' => array('class' => 'select2_choice circonscription_choice'),
                    'data' => $departmentUuid,
                ));
            }
        };

        // EVENT LISTENERS
        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) use ($formCityModifier, $departmentUuid, $cityUuid, $options) {
                $formCityModifier($event->getForm(), $departmentUuid, $cityUuid, $options);
            }
        );

        $builder->get('out_of_france')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) use ($updateFieldsModifier, $departmentChoices, $circonscriptionChoices, $departmentUuid, $cityUuid, $options) {
                $outOfFrance = $event->getForm()->getData();
                $updateFieldsModifier($event->getForm()->getParent(), $outOfFrance, $departmentChoices, $circonscriptionChoices, $departmentUuid, $cityUuid, $options);
            }
        );

        $builder->get('department')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) use ($formCityModifier, $departmentUuid, $cityUuid, $options) {
                $departmentUuid = $event->getForm()->getData();
                $formCityModifier($event->getForm()->getParent(), $departmentUuid, $cityUuid, $options);
            }
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'city_id' => null,
            'label_department' => 'Votre département',
            'label_circonscription' => 'Votre circonscription',
            'label_city' => 'Votre ville',
        ));
    }

    /**
     *
     * @return string
     */
    public function getName()
    {
        return 'localization_choice';
    }
}
