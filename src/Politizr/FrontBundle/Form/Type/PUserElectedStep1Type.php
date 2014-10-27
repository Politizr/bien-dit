<?php

namespace Politizr\FrontBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Email;

use Propel\PropelBundle\Validator\Constraints\UniqueObject;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Politizr\Model\PUser;
use Politizr\Model\PUStatus;
use Politizr\Model\PUType;

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
        $builder->add('p_u_type_id', 'hidden', array(
            'attr'     => array( 'value' => PUType::TYPE_QUALIFIE )
            )
        );
        $builder->add('p_u_status_id', 'hidden', array(
            'attr'     => array( 'value' => PUStatus::STATUS_ACTIV )
            )
        );
        $builder->add('online', 'hidden', array(
            'attr'     => array( 'value' => false )
            )
        );


        // Nom, prénom, etc.
        $builder->add('gender', 'choice', array(
            'required' => true,
            'label' => 'Civilité', 
            'choices' => array('Madame' => 'Madame', 'Monsieur' => 'Monsieur'),
            'empty_value' => 'Civilité',
            'constraints' => new NotBlank(array('message' => 'Civilité obligatoire.'))
        ));

        $builder->add('name', 'text', array(
            'required' => true,
            'label' => 'Nom', 
            'constraints' => new NotBlank(array('message' => 'Nom obligatoire.'))
            )
        );
        $builder->add('firstname', 'text', array(
            'required' => true,
            'label' => 'Prénom', 
            'constraints' => new NotBlank(array('message' => 'Prénom obligatoire.'))
            )
        );
        $builder->add('birthday', 'date', array(
            'required' => true,
            'label' => 'Date de naissance', 
            'widget' => 'single_text',
            'format' => 'dd/MM/yyyy',
            'invalid_message' => 'La date de naissance doit être au format JJ/MM/AAAA',
            'constraints' => new NotBlank(array('message' => 'Date de naissance obligatoire.'))
            )
        );

        // TODO: + contrainte email
        $builder->add('email', 'repeated', array(
            'required' => true,
            'first_options' =>   array(
                'label' => 'Email', 
                ),
            'second_options' =>   array(
                'label' => 'Confirmation email', 
                ),
            'type' => 'email',
            'constraints' => new NotBlank(array('message' => 'Email obligatoire.'))
            )
        );

        $builder->add('newsletter', 'checkbox', array(  
            'required' => false,
            'label' => 'Je souhaite recevoir les news de Politizr', 
            'attr'     => array( 'checked' => 'checked', 'align_with_widget' => true )
            )
        );


        // Username / Password
        $builder->add('username', 'text', array(
            'required' => true,
            'label' => 'Identifiant', 
            'constraints' => new NotBlank(array('message' => 'Identifiant obligatoire.'))
            )
        );
        
        # TODO > contraintes en plus mot de passe "fort"
        $builder->add('plainPassword', 'repeated', array(
            'required' => true,
            'first_options' =>   array(
                'label' => 'Mot de passe', 
                ),
            'second_options' =>   array(
                'label' => 'Confirmation', 
                ),
            'type' => 'password',
            'constraints' => new NotBlank(array('message' => 'Mot de passe obligatoire.'))
            )
        );


        // Justificatif + mandats électifs
        $builder->add('uploaded_supporting_document', 'file', array(
            'required' => true,
            'label' => 'Pièce justificative', 
            'mapped' => false,
            'attr' => array('help_text' => 'Scan de votre pièce d\'identité.'),
            'constraints' => new NotBlank(array('message' => 'Scan d\'une pièce justificative obligatoire.'))
            )
        );

        $builder->add('elective_mandates', 'textarea', array(
            'required' => true,
            'label' => 'Mandats électifs', 
            'mapped' => false,
            'attr' => array('help_text' => 'Liste des mandats électifs exercés aujourd\'hui.'),
            'constraints' => new NotBlank(array('message' => 'Liste de vos mandats électifs en cours obligatoire.'))
            )
        );


        $builder->add('actions', 'form_actions', [
            'buttons' => [
                'save' => ['type' => 'submit', 'options' => ['label' => 'Valider', 'attr' => [ 'class' => 'btn-success' ] ]],
                ]
            ]);
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
