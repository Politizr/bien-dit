<?php

namespace Politizr\FrontBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Email;
use Rollerworks\Bundle\PasswordStrengthBundle\Validator\Constraints\PasswordStrength;

use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Politizr\Constant\UserConstants;

/**
 * Elected inscription form step 1
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


        $builder->add('email', 'email', array(
            'required' => true,
            'label' => 'Email',
            'constraints' => array(
                new NotBlank(array('message' => 'Email obligatoire.')),
                new Email(array('message' => 'Le format de l\'email n\'est pas valide.')),
            ),
            'attr' => array('placeholder' => 'Email')
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
            'constraints' => array(
                new NotBlank(array('message' => 'Mot de passe obligatoire.')),
                new PasswordStrength(
                    array(
                        'message' => 'Le mot de passe doit contenir au moins 8 caractères',
                        'minLength' => 8,
                        'minStrength' => 1
                    )
                ),
            )
        ));

        $builder->add('elected', 'checkbox', array(
            'required' => true,
            'label' => 'Je certifie être un élu',
            'mapped' => false
        ));

        $builder->add('cgu', 'checkbox', array(
            'required' => true,
            'label' => 'J\'accepte les conditions générales d\'utilisation',
            'mapped' => false
        ));

        // update username same as email field
        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
            $data = $event->getData();
            if (isset($data['email'])) {
                $data['username'] = $data['email'];
            }
            $event->setData($data);
        });
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
