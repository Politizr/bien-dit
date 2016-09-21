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
class LocalizationCityChoiceType extends AbstractType
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

        // Department type list
        $departmentChoices = $this->localizationManager->getDepartmentChoices();
        $builder->add('department', 'choice', array(
            'label' => $options['label_department'],
            'required' => true,
            'choices' => $departmentChoices,
            'choices_as_values' => true,
            'multiple' => false,
            'expanded' => false,
            'placeholder' => 'Choisissez un département',
            'empty_data'  => null,
            'attr' => array('class' => 'select2_choice department_choice'),
            // 'constraints' => new NotBlank(array('message' => 'Choix d\'un département obligatoire.')),
            'data' => $departmentUuid,
        ));

        // see http://symfony.com/doc/2.8/cookbook/form/dynamic_form_modification.html#cookbook-form-events-underlying-data
        $formModifier = function (FormInterface $form, $departmentUuid = null, $cityUuid = null, $options) {
            // City type list / 30438
            $cityChoices = $this->localizationManager->getCityChoices($departmentUuid);
            $form->add('city', 'choice', array(
                'label' => $options['label_city'],
                'required' => true,
                'choices' => $cityChoices,
                'choices_as_values' => true,
                'multiple' => false,
                'expanded' => false,
                'placeholder' => 'Choisissez une ville',
                'empty_data'  => null,
                'attr' => array('class' => 'select2_choice city_choice'),
                // 'constraints' => new NotBlank(array('message' => 'Choix d\'une ville obligatoire.')),
                'data' => $cityUuid,
            ));
        };

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) use ($formModifier, $departmentUuid, $cityUuid, $options) {
                $formModifier($event->getForm(), $departmentUuid, $cityUuid, $options);
            }
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'city_id' => null,
            'label_department' => 'Département',
            'label_city' => 'Ville',
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
