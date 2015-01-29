<?php

namespace Politizr\FrontBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\Validator\Constraints\NotBlank;

use Propel\PropelBundle\Validator\Constraints\UniqueObject;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * form minimaliste > token, id
 * 
 * @author Lionel Bouzonville
 */
class PDReactionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add('id', 'hidden', array(
            'required' => true, 
            )
        );
        
        $builder->add('p_d_debate_id', 'hidden', array(
            'required' => true, 
            )
        );
        
        $builder->add('p_user_id', 'hidden', array(
            'required' => true, 
            )
        );
        

        $builder->add('title', 'text', array(
            'required' => false,
            'label' => 'Titre', 
            )
        );
        
        $builder->add('summary', 'hidden', array(
            'required' => false,
            'label' => 'Résumé', 
            )
        );
        
        $builder->add('description', 'hidden', array(
            'required' => false,
            'label' => 'Description', 
            'attr' =>   array(
                'class' => 'editor',
                )
            )
        );

        
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
