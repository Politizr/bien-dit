<?php

namespace Politizr\FrontBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Comment type for debate & reaction
 * /!\ do not use directly, use PDDCommentType or PDRComment type instead
 *
 * @author Lionel Bouzonville
 */
class PDCommentType extends AbstractType
{
    /**
     *
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('p_user_id', 'hidden', array(
            'required' => true,
        ));
        
        $builder->add('paragraph_no', 'hidden', array(
            'required' => false,
        ));
        
        $builder->add('online', 'hidden', array(
            'required' => true,
            'data' => true,
        ));
        
        $builder->add('description', 'textarea', array(
            'required' => false,
            'label' => 'Commentaire',
            'constraints' => new NotBlank(array('message' => 'Commentaire obligatoire.')),
            'attr' => array(
                'placeholder' => 'Votre commentaire...',
                )
        ));
    }

    /**
     *
     * @return string
     */
    public function getName()
    {
        return 'comment';
    }
}
