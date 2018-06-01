<?php

namespace Politizr\AdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Range;

use Politizr\Model\PMModerationTypeQuery;

/**
 * @deprecated
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

        $builder->add('p_object_id', 'hidden', array(
            'required' => true,
        ));
        
        $builder->add('p_object_name', 'hidden', array(
            'required' => true,
        ));

        // Moderation type list
        $builder->add('p_m_moderation_type', 'Propel\Bundle\PropelBundle\Form\Type\ModelType', array(
                'required' => true,
                'label' => 'Type de modération',
                'class' => 'Politizr\\Model\\PMModerationType',
                'query' => PMModerationTypeQuery::create()->orderById('asc'),
                'index_property' => 'id',
                'multiple' => false,
                'expanded' => false,
                'constraints' => new NotBlank(array('message' => 'Choix d\'un type obligatoire.')),
            ));
        
        $builder->add('score_evolution', 'text', array(
            'required' => false,
            'label' => 'Évolution de réputation',
            'constraints' => array(
                new Range(array('max' => '0', 'maxMessage' => 'Nombre négatif')),
            ),
            'attr' => array('placeholder' => 'Nombre négatif soustrait à la réputation, par exemple "-10"'),
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
