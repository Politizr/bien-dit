<?php

namespace Politizr\FrontBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Email;
use Rollerworks\Bundle\PasswordStrengthBundle\Validator\Constraints\PasswordStrength;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * User account / id password
 * beta
 *
 * @author Lionel Bouzonville
 */
class PUserConnectionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // Attributs cachés obligatoires
        $builder->add('form_type_id', 'hidden', array(
            'mapped' => false,
            'data' => 3,
            ));

        // Username / Password
        $builder->add('username', 'text', array(
            'required' => true,
            'label' => 'Email de connexion',
            'constraints' => array(
                new NotBlank(array('message' => 'Email de connexion obligatoire.')),
                new Email(array('message' => 'Le format de l\'email n\'est pas valide.')),
            ),
        ));

        $builder->add('plainPassword', 'repeated', array(
            // 'required' => true,
            'first_options' => array(
                'label' => 'Mot de passe',
            ),
            'second_options' =>   array(
                'label' => 'Confirmation',
            ),
            'type' => 'password',
            'constraints' => array(
                // new NotBlank(array('message' => 'Mot de passe obligatoire.')),
                new PasswordStrength(
                    array(
                        'message' => 'Le mot de passe doit contenir au moins 8 caractères',
                        'minLength' => 8,
                        'minStrength' => 1
                    )
                ),
            )
        ));
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
