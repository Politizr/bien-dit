<?php

namespace Politizr\FrontBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\Validator\Constraints\NotBlank;

use Propel\PropelBundle\Validator\Constraints\UniqueObject;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Photo info debate edition form
 *
 * @author Lionel Bouzonville
 */
class PDDebatePhotoInfoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('uuid', 'hidden', array(
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
        return 'debate_photo_info';
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
