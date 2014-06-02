<?php

namespace Politizr\FrontBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Email;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Description
 *
 * @author Studio Echo / Lionel Bouzonville
 */
class LoginType extends AbstractType {
    /**
     * 
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('username', 'text', array(
            'required' => true, 
            'label' => 'Identifiant', 
            'constraints' => new NotBlank(array('message' => 'Champ obligatoire.'))
            ));
        $builder->add('password', 'password', array(
            'required' => true, 
            'label' => 'Mot de passe',
            'constraints' => new NotBlank(array('message' => 'Champ obligatoire.'))
            ));

        $builder->add('remember_me', 'checkbox', array(
            'required' => false, 
            'label' => 'Se souvenir de moi',
            ));
    }

    /**
     * 
     * @return string
     */
    public function getName()
    {
        return 'login';
    }    

}