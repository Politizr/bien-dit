<?php

namespace Politizr\FrontBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\Validator\Constraints\NotBlank;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Gestion de la MAJ des données personnelles
 *
 * @author Lionel Bouzonville
 */
class PUserIdCheckType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('zla1', 'text', array(
            'required' => true,
            'mapped' => false,
        ));
        $builder->add('zla2', 'text', array(
            'required' => false,
            'mapped' => false,
        ));
        $builder->add('zla3', 'text', array(
            'required' => false,
            'mapped' => false,
        ));
    }

    /**
     *
     * @return string
     */
    public function getName()
    {
        return 'user_id_check';
    }
    
    /**
     *
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Politizr\Model\PUser',
        ));
    }
}
