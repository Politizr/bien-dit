<?php

namespace Politizr\FrontBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Debate edition form
 * beta
 *
 * @author Lionel Bouzonville
 */
class PDDebateType extends AbstractType
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

        $builder->add('file_name', 'hidden', array(
            'required' => false,
        ));

        $builder->add('copyright', 'hidden', array(
            'required' => false,
        ));
    }

    /**
     *
     * @return string
     */
    public function getName()
    {
        return 'debate';
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
