<?php

namespace Politizr\FrontBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Email;

use Propel\PropelBundle\Validator\Constraints\UniqueObject;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * TODO: commentaires
 * 
 * @author Lionel Bouzonville
 */
class PUserPerso4Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // Attributs cachés obligatoires
        $builder->add('id', 'hidden');
        $builder->add('p_u_type_id', 'hidden');
        $builder->add('p_u_status_id', 'hidden');
        $builder->add('form_type_id', 'hidden', array(
            'mapped' => false,
            'data' => 4,
            ));

        // Username / Password
        $builder->add('username', 'text', array(
            'required' => false,
            'label' => 'Identifiant', 
            // 'constraints' => new NotBlank(array('message' => 'Identifiant obligatoire.'))
            )
        );

        # TODO > contraintes en plus mot de passe "fort"
        $builder->add('password', 'repeated', array(
            'required' => false,
            'first_options' =>   array(
                'label' => 'Mot de passe', 
                ),
            'second_options' =>   array(
                'label' => 'Confirmation', 
                ),
            'type' => 'password',
            // 'constraints' => new NotBlank(array('message' => 'Mot de passe obligatoire.'))
            )
        );


        $builder->add('actions', 'form_actions', [
            'buttons' => [
                'save' => ['type' => 'button', 'options' => ['label' => 'Mettre à jour', 'attr' => [ 'class' => 'btn-success', 'action' => 'btn-submit-perso', 'form-id-name' => 'form-perso4' ] ]],
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
