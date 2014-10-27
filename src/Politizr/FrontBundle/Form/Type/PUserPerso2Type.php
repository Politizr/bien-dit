<?php

namespace Politizr\FrontBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Email;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * TODO: commentaires
 * 
 * @author Lionel Bouzonville
 */
class PUserPerso2Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // Attributs cachés obligatoires
        $builder->add('id', 'hidden');
        $builder->add('p_u_type_id', 'hidden');
        $builder->add('p_u_status_id', 'hidden');
        $builder->add('form_type_id', 'hidden', array(
            'mapped' => false,
            'data' => 2,
            ));

        // TODO: + contrainte email
        $builder->add('email', 'repeated', array(
            'required' => false,
            'first_options' =>   array(
                'label' => 'Email', 
                ),
            'second_options' =>   array(
                'label' => 'Confirmation email', 
                ),
            'type' => 'email',
            'constraints' => new Email(array('message' => 'L\'email n\'a pas un format valide.'))
            )
        );

        $builder->add('newsletter', 'checkbox', array(  
            'required' => false,
            'label' => 'Je souhaite recevoir les news de Politizr', 
            'attr'     => array( 'checked' => 'checked', 'align_with_widget' => true )
            )
        );


        $builder->add('actions', 'form_actions', [
            'buttons' => [
                'save' => ['type' => 'button', 'options' => ['label' => 'Mettre à jour', 'attr' => [ 'class' => 'btn-success', 'action' => 'btn-submit-perso', 'form-id-name' => 'form-perso2' ] ]],
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
