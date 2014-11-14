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
 * Formulaire associée à la migration d'un compte citoyen vers élu
 * 
 * @author Lionel Bouzonville
 */
class PUserElectedMigrationStep1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // Attributs cachés obligatoires
        $builder->add('id', 'hidden');
        $builder->add('p_u_type_id', 'hidden');
        $builder->add('p_u_status_id', 'hidden');

        // Nom, prénom, etc.
        $builder->add('gender', 'choice', array(
            'required' => true,
            'label' => 'Civilité', 
            'choices' => array('Madame' => 'Madame', 'Monsieur' => 'Monsieur'),
            'constraints' => new NotBlank(array('message' => 'Civilité obligatoire.')),
            'disabled' => true,
        ));

        $builder->add('name', 'text', array(
            'required' => true,
            'label' => 'Nom', 
            'constraints' => new NotBlank(array('message' => 'Nom obligatoire.')),
            'disabled' => true,
            )
        );
        $builder->add('firstname', 'text', array(
            'required' => true,
            'label' => 'Prénom', 
            'constraints' => new NotBlank(array('message' => 'Prénom obligatoire.')),
            'disabled' => true,
            )
        );
        $builder->add('birthday', 'date', array(
            'required' => true,
            'label' => 'Date de naissance', 
            'widget' => 'single_text',
            'format' => 'dd/MM/yyyy',
            'invalid_message' => 'La date de naissance doit être au format JJ/MM/AAAA',
            'constraints' => new NotBlank(array('message' => 'Date de naissance obligatoire.')),
            'disabled' => true,
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
            'attr' => array('help_text' => 'Liste des mandats électifs exercés.'),
            'constraints' => new NotBlank(array('message' => 'Liste de vos mandats électifs obligatoire.')),
            'attr' => array('placeholder' => 'Liste de vos mandats électifs passés ou présents')
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
