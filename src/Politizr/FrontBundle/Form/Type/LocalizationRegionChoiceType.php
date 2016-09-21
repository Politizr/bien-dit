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
class LocalizationRegionChoiceType extends AbstractType
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
        $regionUuid = $this->localizationManager->getRegionUuidByCityId($options['city_id']);

        // Region type list
        $regionChoices = $this->localizationManager->getRegionChoices();
        $builder->add('region', 'choice', array(
            'label' => $options['label_region'],
            'required' => true,
            'choices' => $regionChoices,
            'choices_as_values' => true,
            'multiple' => false,
            'expanded' => false,
            'placeholder' => 'Choisissez une région',
            'empty_data'  => null,
            'attr' => array('class' => 'select2_choice department_choice'),
            // 'constraints' => new NotBlank(array('message' => 'Choix d\'un département obligatoire.')),
            'data' => $regionUuid,
        ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'city_id' => null,
            'label_region' => 'Région',
        ));
    }

    /**
     *
     * @return string
     */
    public function getName()
    {
        return 'localization_region_choice';
    }
}
