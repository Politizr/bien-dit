<?php
namespace Politizr\FrontBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Email;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Politizr\Constant\UserConstants;

/**
 * Inscription user citoyen étape 2
 *
 * @author Lionel Bouzonville
 */
class PUserContactType extends AbstractType
{
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
            'choices' => array('Madame' => 'Madame', 'Monsieur' => 'Monsieur'),
            'expanded' => true,
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
