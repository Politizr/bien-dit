<?php

namespace Politizr\FrontBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Email;

use Propel\PropelBundle\Validator\Constraints\UniqueObject;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Politizr\Constant\UserConstants;

use Politizr\Model\PUser;

/**
 * Inscription user elu / étape 1
 *
 * @author Lionel Bouzonville
 */
class PUserElectedRegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // Attributs cachés
        $builder->add('qualified', 'hidden', array(
            'attr'     => array( 'value' => true )
        ));

        $builder->add('p_u_status_id', 'hidden', array(
            'attr'     => array( 'value' => UserConstants::STATUS_ACTIVED )
        ));

        $builder->add('online', 'hidden', array(
            'attr'     => array( 'value' => false )
        ));

        // Nom, prénom, etc.
        $builder->add('gender', 'choice', array(
            'required' => true,
            'label' => 'Civilité',
            'choices' => array('Madame' => 'Madame', 'Monsieur' => 'Monsieur'),
            'expanded' => true,
            'constraints' => new NotBlank(array('message' => 'Civilité obligatoire.'))
        ));

        $builder->add('name', 'text', array(
            'required' => true,
            'label' => 'Nom',
            'constraints' => new NotBlank(array('message' => 'Nom obligatoire.')),
            'attr' => array('placeholder' => 'Nom')
        ));

        $builder->add('firstname', 'text', array(
            'required' => true,
            'label' => 'Prénom',
            'constraints' => new NotBlank(array('message' => 'Prénom obligatoire.')),
            'attr' => array('placeholder' => 'Prénom')
        ));

        $builder->add('birthday', 'date', array(
            'required' => true,
            'label' => 'Date de naissance',
            'widget' => 'single_text',
            'format' => 'dd/MM/yyyy',
            'invalid_message' => 'La date de naissance doit être au format JJ/MM/AAAA',
            'constraints' => new NotBlank(array('message' => 'Date de naissance obligatoire.')),
            'attr' => array('placeholder' => 'JJ/MM/AAAA')
        ));

        $builder->add('email', 'repeated', array(
            'required' => true,
            'first_options' =>   array(
                'label' => 'Email',
                'attr' => array('placeholder' => 'Email')
                ),
            'second_options' =>   array(
                'label' => 'Confirmation email',
                'attr' => array('placeholder' => 'Email')
                ),
            'type' => 'email',
            'constraints' => array(
                new NotBlank(array('message' => 'Email obligatoire.')),
                new Email(array('message' => 'Le format de l\'email n\'est pas valide.'))
                )
        ));

        $builder->add('newsletter', 'checkbox', array(
            'required' => false,
            'label' => 'Je souhaite recevoir les news de Politizr',
            'attr'     => array( 'checked' => 'checked', 'align_with_widget' => true )
        ));


        // Username / Password
        $builder->add('username', 'text', array(
            'required' => true,
            'label' => 'Identifiant',
            'constraints' => new NotBlank(array('message' => 'Identifiant obligatoire.')),
            'attr' => array('placeholder' => 'Identifiant')
        ));
        
        $builder->add('plainPassword', 'repeated', array(
            'required' => true,
            'first_options' =>   array(
                'label' => 'Mot de passe',
                'attr' => array('placeholder' => 'Mot de passe')
                ),
            'second_options' =>   array(
                'label' => 'Confirmation',
                'attr' => array('placeholder' => 'Mot de passe')
                ),
            'type' => 'password',
            'constraints' => new NotBlank(array('message' => 'Mot de passe obligatoire.'))
        ));


        // Justificatif + mandats électifs
        $builder->add('uploaded_supporting_document', 'file', array(
            'required' => true,
            'label' => 'Pièce justificative',
            'mapped' => false,
            'attr' => array('help_text' => 'Scan de votre pièce d\'identité.'),
            'constraints' => new NotBlank(array('message' => 'Scan d\'une pièce justificative obligatoire.'))
        ));

        $builder->add('elective_mandates', 'textarea', array(
            'required' => true,
            'label' => 'Mandats électifs',
            'mapped' => false,
            'attr' => array('help_text' => 'Liste des mandats électifs exercés.'),
            'constraints' => new NotBlank(array('message' => 'Liste de vos mandats électifs obligatoire.')),
            'attr' => array('placeholder' => 'Liste de vos mandats électifs passés ou présents')
        ));
    }

    /**
     *
     * @return string
     */
    public function getName()
    {
        return 'user_elected_register';
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
