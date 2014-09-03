<?php

namespace Politizr\FrontBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Email;

use Propel\PropelBundle\Validator\Constraints\UniqueObject;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Politizr\Model\PUser;

/**
 * TODO: commentaires
 * 
 * @author Lionel Bouzonville
 */
class PUserElectedStep1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // Attributs cachés
        $builder->add('type', 'hidden', array(
            'attr'     => array( 'value' => '0' )
            )
        );
        $builder->add('status', 'hidden', array(
            'attr'     => array( 'value' => '0' )
            )
        );
        $builder->add('online', 'hidden', array(
            'attr'     => array( 'value' => '0' )
            )
        );

        // Nom, prénom, etc.
        $builder->add('gender', 'choice', array(
            'required' => true,
            'choices' => array('Madame' => 'Madame', 'Monsieur' => 'Monsieur'),
            'empty_value' => 'Civilité',
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

        $builder->add('newsletter', 'checkbox', array(  
            'required' => false,
            'attr'     => array( 'checked' => 'checked' )
            )
        );

        // Username / Password
        $builder->add('username', 'text', array(
            'required' => true,
            'constraints' => new NotBlank(array('message' => 'Identifiant obligatoire.'))
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


        // Justificatif + mandats électifs
        $builder->add('uploaded_supporting_document', 'file', array(
            'required' => true,
            'mapped' => false,
            'constraints' => new NotBlank(array('message' => 'Scan d\'une pièce justificative obligatoire.'))
            )
        );

        $builder->add('elective_mandates', 'textarea', array(
            'required' => true,
            'mapped' => false,
            'constraints' => new NotBlank(array('message' => 'Liste de vos mandats électifs en cours obligatoire.'))
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
