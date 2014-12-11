<?php

namespace Politizr\FrontBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\Validator\Constraints\NotBlank;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * form minimaliste > token, id
 * 
 * @author Lionel Bouzonville
 */
class PDCommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add('p_document_id', 'hidden', array(
            'required' => true, 
            )
        );
        $builder->add('p_user_id', 'hidden', array(
            'required' => true, 
            )
        );
        $builder->add('paragraph_no', 'hidden', array(
            'required' => false, 
            )
        );
        $builder->add('online', 'hidden', array(
            'required' => true, 
            'data' => true,
            )
        );
        

        $builder->add('description', 'textarea', array(  
            'required' => false,
            'label' => 'Commentaire',
            'constraints' => new NotBlank(array('message' => 'Commentaire obligatoire.')),
            'attr' => array(
                'placeholder' => 'Votre commentaire...',
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
        return 'comment';
    }    
    
    /**
     * 
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Politizr\Model\PDComment',
        ));
    }

}
