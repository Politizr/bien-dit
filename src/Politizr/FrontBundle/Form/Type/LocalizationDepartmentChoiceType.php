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

/**
 * Localization custom form type
 * /!\ instanciation must be created by service only
 *
 * @author Lionel Bouzonville
 */
class LocalizationDepartmentChoiceType extends AbstractType
{
    private $localizationManager;

    /**
     *
     * @param @politizr.manager.localization
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
        if ($options['current_uuid']) {
            $departmentUuid = $options['current_uuid'];
        } else {
            $departmentUuid = $this->localizationManager->getDepartmentUuidByCityId($options['user_city_id']);
        }

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
            'attr' => array('class' => 'select2_choice department'),
            // 'constraints' => new NotBlank(array('message' => 'Choix d\'un département obligatoire.')),
            'data' => $departmentUuid,
        ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'current_uuid' => null,
            'user_city_id' => null,
            'label_department' => 'Département',
        ));
    }

    /**
     *
     * @return string
     */
    public function getName()
    {
        return 'localization_department_choice';
    }
}
