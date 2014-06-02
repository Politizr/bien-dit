<?php

namespace Politizr\FrontBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Email;

use Propel\PropelBundle\Validator\Constraints\UniqueObject;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Politizr\Model\PUser;

class PUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('id', 'hidden', array(
            'required' => false
            )
        );
        $builder->add('type', 'hidden', array(
            'attr'     => array( 'value' => PUser::TYPE_CITOYEN )
            )
        );
        $builder->add('status', 'hidden', array(
            'attr'     => array( 'value' => PUser::STATUS_ACTIV )
            )
        );


        $builder->add('gender', 'choice', array(
            'required' => true,
            'choices' => array('Madame' => 'Madame', 'Monsieur' => 'Monsieur'),
            'empty_value' => 'Civilité*',
            'constraints' => new NotBlank(array('message' => 'Civilité obligatoire.'))
        ));

        $builder->add('name', 'text', array(
            'required' => true,
            'constraints' => new NotBlank(array('message' => 'Nom obligatoire.'))
            )
        );
        $builder->add('firstname', 'text', array(
            'required' => true,
            'constraints' => new NotBlank(array('message' => 'Prénom obligatoire.'))
            )
        );
        $builder->add('birthday', 'date', array(
            'required' => true,
            'widget' => 'single_text',
            'format' => 'dd/MM/yyyy',
            'invalid_message' => 'La date de naissance doit être au format JJ/MM/AAAA',
            'constraints' => new NotBlank(array('message' => 'Date de naissance obligatoire.'))
            )
        );

        $builder->add('email', 'repeated', array(
            'required' => true,
            'first_options' =>   array(
                ),
            'second_options' =>   array(
                ),
            'type' => 'email',
            'constraints' => new NotBlank(array('message' => 'Email obligatoire.'))
            )
        );  
        
        $builder->add('plainPassword', 'repeated', array(
            'required' => true,
            'first_options' =>   array(
                ),
            'second_options' =>   array(
                ),
            'type' => 'password',
            'constraints' => new NotBlank(array('message' => 'Mot de passe obligatoire.'))
            )
        );

        $builder->add('newsletter', 'checkbox', array(  
            'required' => false,
            'attr'     => array( 'checked' => 'checked' )
            )
        );

    }

    /**
     * 
     * @return string
     */
    public function getName()
    {
        return 'pUser';
    }    
    
    /**
     * 
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Politizr\Model\PUser',
        ));
    }

}
