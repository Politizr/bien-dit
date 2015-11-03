<?php

namespace Politizr\FrontBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Url;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * User profile edit
 *
 * @author Lionel Bouzonville
 */
class PUserBiographyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // Attributs cachés obligatoires
        $builder->add('id', 'hidden');
        $builder->add('qualified', 'hidden');
        $builder->add('p_u_status_id', 'hidden');

        $builder->add('subtitle', 'hidden', array(
            'required' => false,
            'label' => 'Résumé',
        ));

        $builder->add('biography', 'hidden', array(
            'required' => false,
            'label' => 'Biographie',
        ));
        
        $builder->add('website', 'text', array(
            'required' => false,
            'label' => 'Site internet',
            'attr' =>   array(
                'size' => 25,
                'placeholder' => 'Site web',
                ),
            'constraints' => new Url(array('message' => 'L\'url n\'a pas un format valide.'))
        ));

        $builder->add('twitter', 'text', array(
            'required' => false,
            'label' => 'Twitter',
            'attr' =>   array(
                'size' => 25,
                'placeholder' => 'Twitter',
                ),
            'constraints' => new Url(array('message' => 'L\'url n\'a pas un format valide.'))
        ));

        $builder->add('facebook', 'text', array(
            'required' => false,
            'label' => 'Facebook',
            'attr' =>   array(
                'size' => 25,
                'placeholder' => 'Facebook',
                ),
            'constraints' => new Url(array('message' => 'L\'url n\'a pas un format valide.'))
        ));

        // $builder->add('phone', 'text', array(
        //     'required' => false,
        //     'label' => 'Téléphone',
        //     )
        // );
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
