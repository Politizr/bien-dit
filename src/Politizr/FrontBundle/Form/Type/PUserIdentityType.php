<?php

namespace Politizr\FrontBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\Validator\Constraints\NotBlank;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Politizr\Model\PUser;

/**
 * User account / name firstname birthdate
 * beta
 *
 * @author Lionel Bouzonville
 */
class PUserIdentityType extends AbstractType
{
    protected $user;

    /**
     *
     */
    public function __construct(PUser $user)
    {
        $this->user = $user;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // Attributs cachés obligatoires
        $builder->add('form_type_id', 'hidden', array(
            'mapped' => false,
            'data' => 1,
            ));

        // Nom, prénom, etc. > non modifiable par l'utilisateur directement
        $builder->add('gender', 'choice', array(
            'required' => true,
            'label' => 'Civilité',
            'choices' => array('Madame' => 'Madame', 'Monsieur' => 'Monsieur'),
            'empty_value' => 'Civilité',
            'disabled' => $this->user->getValidated()? true : false ,
            'constraints' => new NotBlank(array('message' => 'Civilité obligatoire.'))
        ));

        $builder->add('name', 'text', array(
            'required' => true,
            'label' => 'Nom',
            'disabled' => $this->user->getValidated()? true : false ,
            'constraints' => new NotBlank(array('message' => 'Nom obligatoire.')),
        ));

        $builder->add('firstname', 'text', array(
            'required' => true,
            'label' => 'Prénom',
            'disabled' => $this->user->getValidated()? true : false ,
            'constraints' => new NotBlank(array('message' => 'Prénom obligatoire.')),
        ));

        $builder->add('birthday', 'date', array(
            'required' => true,
            'label' => 'Date de naissance',
            'widget' => 'single_text',
            'format' => 'dd/MM/yyyy',
            'disabled' => $this->user->getValidated()? true : false ,
            'constraints' => new NotBlank(array('message' => 'Date de naissance obligatoire.')),
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
