<?php

namespace Politizr\FrontBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

use Symfony\Component\Validator\Constraints\NotBlank;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Politizr\Model\PUser;

/**
 * User localization
 * beta
 *
 * @author Lionel Bouzonville
 */
class PDDebateLocalizationType extends AbstractType
{
    protected $user;

    /**
     *
     */
    public function __construct(PUser $user)
    {
        $this->user = $user;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // Geo type list
        $choices = array(
            'Choisissez une ville' => 'city',
            'et/ou un département' => 'department',
            'et/ou une région' => 'region',
            'et/ou France' => 'country',
        );

        $builder->add('geo', 'choice', array(
            'label' => 'Géolocalisation de la publication',
            'required' => true,
            'mapped' => false,
            'choices' => $choices,
            'choices_as_values' => true,
            'multiple' => false,
            'expanded' => true,
            'empty_data'  => null,
            'constraints' => new NotBlank(array('message' => 'Choix d\'une localisation obligatoire.')),
        ));

        // Localization city type
        $builder->add('localization_city', LocalizationCityChoiceType::class, array(
            'required' => false,
            'mapped' => false,
            'city_id' => $this->user->getPLCityId(),
        ));

        // Localization department type
        $builder->add('localization_department', LocalizationDepartmentChoiceType::class, array(
            'required' => false,
            'mapped' => false,
            'city_id' => $this->user->getPLCityId(),
        ));

        // Localization region type
        $builder->add('localization_region', LocalizationRegionChoiceType::class, array(
            'required' => false,
            'mapped' => false,
            'city_id' => $this->user->getPLCityId(),
        ));
    }

    /**
     *
     * @return string
     */
    public function getName()
    {
        return 'debate_localization';
    }
    
    /**
     *
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Politizr\Model\PDDebate',
        ));
    }
}
