<?php

namespace Politizr\FrontBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Email;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * TODO: commentaires
 * 
 * @author Lionel Bouzonville
 */
class LostPasswordType extends AbstractType {
    /**
     * 
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('email', 'text', array(
            'label' => 'E-mail', 
            'required' => true, 
            'constraints' => array(
                new NotBlank(array('message' => 'Champ obligatoire.')), 
                new Email(array('message' => 'L\'email n\'a pas un format valide.')))
            ));
    }

    /**
     * 
     * @return string
     */
    public function getName()
    {
        return 'lostPassword';
    }    

}