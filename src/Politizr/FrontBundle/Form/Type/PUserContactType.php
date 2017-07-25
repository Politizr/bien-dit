<?php
namespace Politizr\FrontBundle\Form\Type;

use Symfony\Component\Form\AbstractType;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Email;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Politizr\Constant\UserConstants;

use Politizr\FrontBundle\Form\Type\LocalizationChoiceType;

/**
 * Citizen inscription form step 2
 *
 * @author Lionel Bouzonville
 */
class PUserContactType extends AbstractType
{
    private $withEmail;

    /**
     *
     * @param $withEmail boolean
     * @param $oAuth boolean
     */
    public function __construct($withEmail = false, $oAuth = false)
    {
        $this->withEmail = $withEmail;
        $this->oAuth = $oAuth;
    }

    /**
     *
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('gender', 'choice', array(
            'required' => true,
            'label' => 'Civilité',
            'placeholder' => 'Sélectionnez',
            'choices' => array('Madame' => 'Madame', 'Monsieur' => 'Monsieur'),
            'multiple' => false,
            'expanded' => false,
            'constraints' => new NotBlank(array('message' => 'Civilité obligatoire.')),
        ));

        $builder->add('name', 'text', array(
            'required' => true,
            'label' => 'Votre nom',
            'constraints' => new NotBlank(array('message' => 'Nom obligatoire.')),
            'attr' => array('placeholder' => 'Nom')
        ));

        $builder->add('firstname', 'text', array(
            'required' => true,
            'label' => 'Votre prénom',
            'constraints' => new NotBlank(array('message' => 'Prénom obligatoire.')),
            'attr' => array('placeholder' => 'Prénom')
        ));

        // $builder->add('newsletter', 'checkbox', array(
        //     'required' => false,
        //     'label' => 'Je m\'inscris à la newsletter de Politizr'
        // ));

        if ($this->withEmail) {
            $builder->add('username', 'hidden', array(
                'attr'     => array( 'value' => '' )
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
            
            // update username same as email field
            $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
                $data = $event->getData();
                if (isset($data['email'])) {
                    $data['username'] = $data['email'];
                }
                $event->setData($data);
            });
        }

        // Localization type
        $builder->add('localization', LocalizationChoiceType::class, array(
            'required' => true,
            'mapped' => false,
        ));

        if ($this->oAuth) {
            $builder->add('cgu', 'checkbox', array(
                'required' => true,
                'mapped' => false,
                'constraints' => new IsTrue(
                    array(
                        'message' => 'Vous devez accepter les conditions générales d\'utilisation.'
                    )
                )
            ));
        }
    }

    /**
     *
     * @return string
     */
    public function getName()
    {
        return 'user_contact';
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
