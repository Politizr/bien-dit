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
class LocalizationCirconscriptionChoiceType extends AbstractType
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
            $circonscriptionUuid = $options['current_uuid'];
        } else {
            $circonscriptionUuid = $this->localizationManager->getDepartmentUuidByCityId($options['user_city_id']);
        }

        // Circonscription type list
        $circonscriptionChoices = $this->localizationManager->getCirconscriptionChoices();
        $builder->add('circonscription', 'choice', array(
            'label' => $options['label_circonscription'],
            'required' => true,
            'choices' => $circonscriptionChoices,
            'choices_as_values' => true,
            'multiple' => false,
            'expanded' => false,
            'placeholder' => 'Choisissez une circonscription',
            'empty_data'  => null,
            'attr' => array('class' => 'select2_choice circonscription_choice'),
            // 'constraints' => new NotBlank(array('message' => 'Choix d\'un département obligatoire.')),
            'data' => $circonscriptionUuid,
        ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'current_uuid' => null,
            'user_city_id' => null,
            'label_circonscription' => 'Circonscription',
        ));
    }

    /**
     *
     * @return string
     */
    public function getName()
    {
        return 'localization_circonscription_choice';
    }
}
