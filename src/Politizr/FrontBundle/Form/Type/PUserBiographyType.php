<?php

namespace Politizr\FrontBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\Validator\Constraints\Url;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Politizr\Model\PUser;

/**
 * User profile edit
 * beta
 *
 * @author Lionel Bouzonville
 */
class PUserBiographyType extends AbstractType
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
        $builder->add('biography', 'textarea', array(
            'required' => false,
            'label' => 'Biographie',
            'attr' => array(
                'maxlength' => 140
            )
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

        $builder->add('file_name', 'hidden', array(
            'required' => false,
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
