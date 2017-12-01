<?php

namespace Politizr\AdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

use Politizr\Model\PUserQuery;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;

/**
 * Admin users management form type for circle
 * beta
 *
 * @author Lionel Bouzonville
 */
class PCirclePUsersFiltersType extends AbstractType
{
    /**
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('only_elected', CheckboxType::class, array(
            'label'    => 'Élus uniquement',
            'required' => false,
        ));

        $builder->add('city_insee_code', TextType::class, array(
            'label'    => 'Code ville INSEE',
            'required' => false,
        ));

        $builder->add('department_code', TextType::class, array(
            'label'    => 'Code département',
            'required' => false,
        ));
    }

    /**
     *
     * @return string
     */
    public function getName()
    {
        return 'admin_circle_users_filter';
    }
}
