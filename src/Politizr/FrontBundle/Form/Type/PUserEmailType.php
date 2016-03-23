<?php

namespace Politizr\FrontBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Email;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Gestion de la MAJ des données personnelles
 *
 * @author Lionel Bouzonville
 */
class PUserEmailType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // Attributs cachés obligatoires
        $builder->add('form_type_id', 'hidden', array(
            'mapped' => false,
            'data' => 2,
            ));

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
            'constraints' => array(
                new NotBlank(array('message' => 'Email obligatoire.')),
                new Email(array('message' => 'Le format de l\'email n\'est pas valide.'))
                )
        ));

        // $builder->add('newsletter', 'checkbox', array(
        //     'required' => false
        // ));
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
