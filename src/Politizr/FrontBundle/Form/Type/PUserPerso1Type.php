<?php

namespace Politizr\FrontBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Email;

use Propel\PropelBundle\Validator\Constraints\UniqueObject;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Politizr\Model\PUser;

/**
 * TODO: commentaires
 * 
 * @author Lionel Bouzonville
 */
class PUserPerso1Type extends AbstractType
{
    protected $user;

    /**
     *
     */
    public function __construct(PUser $user) {
        $this->user = $user;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // Attributs cachés obligatoires
        $builder->add('id', 'hidden');
        $builder->add('p_u_type_id', 'hidden');
        $builder->add('p_u_status_id', 'hidden');
        $builder->add('form_type_id', 'hidden', array(
            'mapped' => false,
            'data' => 1,
            ));

        // Nom, prénom, etc. > non modifiable par l'utilisateur directement
        $builder->add('gender', 'choice', array(
            'required' => false,
            'label' => 'Civilité', 
            'choices' => array('Madame' => 'Madame', 'Monsieur' => 'Monsieur'),
            'empty_value' => 'Civilité',
            'disabled' => $this->user->getValidated()?true:false
        ));

        $builder->add('name', 'text', array(
            'required' => false,
            'label' => 'Nom', 
            'disabled' => $this->user->getValidated()?true:false
            )
        );
        $builder->add('firstname', 'text', array(
            'required' => false,
            'label' => 'Prénom', 
            'disabled' => $this->user->getValidated()?true:false
            )
        );
        $builder->add('birthday', 'date', array(
            'required' => false,
            'label' => 'Date de naissance', 
            'widget' => 'single_text',
            'format' => 'dd/MM/yyyy',
            'disabled' => $this->user->getValidated()?true:false
            )
        );
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
