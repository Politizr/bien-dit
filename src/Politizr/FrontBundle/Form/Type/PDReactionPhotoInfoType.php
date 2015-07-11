<?php

namespace Politizr\FrontBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\Validator\Constraints\NotBlank;

use Propel\PropelBundle\Validator\Constraints\UniqueObject;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Photo info reaction edition form
 *
 * @author Lionel Bouzonville
 */
class PDReactionPhotoInfoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('id', 'hidden', array(
            'required' => true,
        ));
        
        $builder->add('p_d_debate_id', 'hidden', array(
            'required' => true,
        ));
        
        $builder->add('p_user_id', 'hidden', array(
            'required' => true,
        ));

        $builder->add('with_shadow', 'hidden', array(
            'required' => false,
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
        return 'reaction_photo_info';
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
