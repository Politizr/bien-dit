<?php

namespace Politizr\FrontBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Email;
use Rollerworks\Bundle\PasswordStrengthBundle\Validator\Constraints\PasswordStrength;

use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Politizr\Constant\UserConstants;

/**
 * Citizen inscription form step 1
 *
 * @author Lionel Bouzonville
 */
class PUserRegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('username', 'hidden', array(
            'attr'     => array( 'value' => '' )
        ));

        $builder->add('email', 'email', array(
            'required' => true,
            'label' => 'Renseignez votre e-mail',
            'constraints' => array(
                new NotBlank(array('message' => 'E-mail obligatoire.')),
                new Email(array('message' => 'Le format de l\'e-mail n\'est pas valide.')),
            ),
            'attr' => array('placeholder' => 'Email')
        ));

        $builder->add('plainPassword', 'password', array(
            'required' => true,
            'label' => 'Choisissez votre mot de passe (min. 8 caratères)',
            'constraints' => array(
                new NotBlank(array('message' => 'Mot de passe obligatoire.')),
                new PasswordStrength(
                    array(
                        'message' => 'Le mot de passe doit contenir au moins 8 caractères',
                        'minLength' => 8,
                        'minStrength' => 1
                    )
                ),
            ),
            'attr' => array('placeholder' => 'Mot de passe')
        ));
        
//         $builder->add('plainPassword', 'repeated', array(
//             'required' => true,
//             'first_options' =>   array(
//                 'label' => 'Choisissez votre mot de passe (min. 8 caratères)',
//                 'attr' => array('placeholder' => 'Mot de passe')
//             ),
//             'second_options' =>   array(
//                 'label' => 'Confirmation de votre mot de passe',
//                 'attr' => array('placeholder' => 'Mot de passe')
//             ),
//             'type' => 'password',
//             'constraints' => array(
//                 new NotBlank(array('message' => 'Mot de passe obligatoire.')),
//                 new PasswordStrength(
//                     array(
//                         'message' => 'Le mot de passe doit contenir au moins 8 caractères',
//                         'minLength' => 8,
//                         'minStrength' => 1
//                     )
//                 ),
//             )
//         ));

        $builder->add('cgu', 'checkbox', array(
            'required' => true,
            'mapped' => false,
            'constraints' => new IsTrue(
                array(
                    'message' => 'Vous devez accepter les conditions générales d\'utilisation.'
                )
            )
        ));

        $builder->add('rgpd', 'checkbox', array(
            'required' => true,
            'mapped' => false,
            'constraints' => new IsTrue(
                array(
                    'message' => 'Vous devez accepter les conditions relatives à vos données personnelles.'
                )
            )
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
        return 'user_register';
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
