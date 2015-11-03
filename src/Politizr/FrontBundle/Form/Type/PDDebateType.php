<?php

namespace Politizr\FrontBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\Validator\Constraints\NotBlank;

use Propel\PropelBundle\Validator\Constraints\UniqueObject;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Debate edition form
 *
 * @author Lionel Bouzonville
 */
class PDDebateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('id', 'hidden', array(
            'required' => true,
        ));
        
        $builder->add('p_user_id', 'hidden', array(
            'required' => true,
        ));

        $builder->add('title', 'textarea', array(
            'required' => true,
            'attr' => array(
                'maxlength' => 100
            )
        ));
        
        $builder->add('description', 'hidden', array(
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
