<?php

namespace Politizr\FrontBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Email;

use Propel\PropelBundle\Validator\Constraints\UniqueObject;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Gestion de la MAJ des données personnelles
 *
 * @author Lionel Bouzonville
 */
class PUserConnectionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // Attributs cachés obligatoires
        $builder->add('id', 'hidden');
        $builder->add('qualified', 'hidden');
        $builder->add('p_u_status_id', 'hidden');
        $builder->add('form_type_id', 'hidden', array(
            'mapped' => false,
            'data' => 3,
            ));

        // Username / Password
        $builder->add('username', 'text', array(
            'required' => true,
            'label' => 'Identifiant',
            'constraints' => new NotBlank(array('message' => 'Identifiant obligatoire.'))
        ));

        # TODO > contraintes en plus mot de passe "fort"
        $builder->add('plainPassword', 'repeated', array(
            // 'required' => true,
            'first_options' =>   array(
                'label' => 'Mot de passe',
                ),
            'second_options' =>   array(
                'label' => 'Confirmation',
                ),
            'type' => 'password',
            // 'constraints' => new NotBlank(array('message' => 'Mot de passe obligatoire.'))
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
