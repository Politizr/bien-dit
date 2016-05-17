<?php
namespace Politizr\FrontBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Email;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Politizr\Constant\UserConstants;

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
     */
    public function __construct($withEmail = false)
    {
        $this->withEmail = $withEmail;
    }

    /**
     *
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('id', 'hidden', array(
            'required' => true
        ));

        $builder->add('qualified', 'hidden', array(
            'attr'     => array( 'value' => false )
        ));

        $builder->add('p_u_status_id', 'hidden', array(
            'attr'     => array( 'value' => UserConstants::STATUS_ACTIVED )
        ));

        $builder->add('online', 'hidden', array(
            'attr'     => array( 'value' => false )
        ));

        $builder->add('gender', 'choice', array(
            'required' => true,
            'label' => 'Civilité',
            // 'placeholder' => 'Civilité',
            'choices' => array('Madame' => 'Madame', 'Monsieur' => 'Monsieur'),
            'multiple' => false,
            'expanded' => false,
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

        $builder->add('newsletter', 'checkbox', array(
            'required' => false,
            'label' => 'Je souhaite recevoir les news de Politizr'
        ));

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
