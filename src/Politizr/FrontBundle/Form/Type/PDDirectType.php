<?php

namespace Politizr\FrontBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Email;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;


/**
 * Direct message edition form
 * beta
 *
 * @author Lionel Bouzonville
 */
class PDDirectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', 'text', array(
            'required' => true,
            'label' => 'Nom',
            'constraints' => array(
                new NotBlank(array('message' => 'Nom obligatoire.')),
            ),
            'attr' => array(
                'maxlength' => 250
            )
        ));
        
        $builder->add('city', 'text', array(
            'required' => false,
            'label' => 'Téléphone',
            // 'constraints' => array(
            //     new NotBlank(array('message' => 'Lieu obligatoire.')),
            // ),
            'attr' => array(
                'maxlength' => 250
            )
        ));
        
        $builder->add('email', 'text', array(
            'required' => true,
            'label' => 'Email',
            'constraints' => array(
                new NotBlank(array('message' => 'Email obligatoire.')),
                new Email(array('message' => 'Le format de l\'email n\'est pas valide.')),
            ),
            'attr' => array(
                'maxlength' => 250
            )
        ));
        
        $builder->add('description', 'textarea', array(
            'label' => 'Message',
            'required' => false,
            // 'constraints' => array(
            //     new NotBlank(array('message' => 'Message obligatoire.')),
            // ),
        ));
    }

    /**
     *
     * @return string
     */
    public function getName()
    {
        return 'direct';
    }
    
    /**
     *
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Politizr\Model\PDDirect',
            'user' => null,
        ));
    }
}
