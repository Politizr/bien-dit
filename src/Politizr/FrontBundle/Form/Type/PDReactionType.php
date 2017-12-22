<?php

namespace Politizr\FrontBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Reaction edition form
 * beta
 *
 * @author Lionel Bouzonville
 */
class PDReactionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('uuid', 'hidden', array(
            'required' => true,
        ));
        
        $builder->add('title', 'textarea', array(
            'required' => true,
            'attr' => array(
                'maxlength' => 100
            )
        ));
        
        $builder->add('description', 'hidden', array(
            'required' => true,
        ));
    }

    /**
     *
     * @return string
     */
    public function getName()
    {
        return 'reaction';
    }
    
    /**
     *
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Politizr\Model\PDReaction',
        ));
    }
}
