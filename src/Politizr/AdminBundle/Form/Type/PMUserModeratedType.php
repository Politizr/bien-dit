<?php

namespace Politizr\AdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Symfony\Component\Validator\Constraints\NotBlank;

use Politizr\Model\PMModerationTypeQuery;

/**
 *
 * @author Lionel Bouzonville
 */
class PMUserModeratedType extends AbstractType
{
    /**
     *
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('p_user_id', 'hidden', array(
            'required' => true,
        ));

        // Moderation type list
        $builder->add('p_m_moderation_type_id', 'model', array(
                'required' => true,
                'label' => 'Type de modération',
                'class' => 'Politizr\\Model\\PMModerationType',
                'query' => PMModerationTypeQuery::create()->orderById('asc'),
                'multiple' => false,
                'expanded' => false,
                'constraints' => new NotBlank(array('message' => 'Choix d\'un type obligatoire.')),
            ));
        
        $builder->add('p_object_id', 'hidden', array(
            'required' => true,
        ));
        
        $builder->add('p_object_name', 'hidden', array(
            'required' => true,
        ));
    }

    /**
     *
     * @return string
     */
    public function getName()
    {
        return 'user_moderated';
    }
    
    /**
     *
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Politizr\Model\PMUserModerated',
        ));
    }
}
