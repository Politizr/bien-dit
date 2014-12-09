<?php

namespace Politizr\FrontBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Url;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Gestion de la MAJ des données personnelles
 * 
 * @author Lionel Bouzonville
 */
class PUserBiographyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // Attributs cachés obligatoires
        $builder->add('id', 'hidden');
        $builder->add('p_u_type_id', 'hidden');
        $builder->add('p_u_status_id', 'hidden');
        $builder->add('form_type_id', 'hidden', array(
            'mapped' => false,
            'data' => 3,
            ));

        $builder->add('biography', 'textarea', array(  
            'required' => false,
            'label' => 'Biographie',
            )
        );

        $builder->add('website', 'text', array(  
            'required' => false,
            'label' => 'Site internet', 
            'constraints' => new Url(array('message' => 'L\'url n\'a pas un format valide.'))
            )
        );

        $builder->add('twitter', 'text', array(  
            'required' => false,
            'label' => 'Twitter', 
            'constraints' => new Url(array('message' => 'L\'url n\'a pas un format valide.'))
            )
        );

        $builder->add('facebook', 'text', array(  
            'required' => false,
            'label' => 'Facebook', 
            'constraints' => new Url(array('message' => 'L\'url n\'a pas un format valide.'))
            )
        );

        $builder->add('phone', 'text', array(  
            'required' => false,
            'label' => 'Téléphone',
            )
        );


        $builder->add('actions', 'form_actions', [
            'buttons' => [
                'save' => ['type' => 'button', 'options' => ['label' => 'Mettre à jour', 'attr' => [ 'class' => 'btn-success', 'action' => 'btn-submit-perso', 'form-id-name' => 'form-perso3' ] ]],
                ]
            ]);

    }

    /**
     * 
     * @return string
     */
    public function getName()
    {
        return 'user';
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
