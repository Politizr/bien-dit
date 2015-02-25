<?php

namespace Politizr\FrontBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\Validator\Constraints\NotBlank;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * TODO: commentaires
 * 
 * @author Lionel Bouzonville
 */
class SearchType extends AbstractType
{
    private $query;

    public function __construct($query = '') {
        $this->query = $query;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('query', 'text', array(
            'required' => true, 
            'label' => 'Recherche', 
            'constraints' => new NotBlank(array('message' => 'Champ obligatoire.')),
            'data' => $this->query,
            'attr' => array('placeholder' => 'Recherche')
            ));
    }
 
 
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);
        $resolver->setDefaults(
            array(
                // avoid to pass the csrf token in the url (but it's not protected anymore)
                'csrf_protection' => false,
            )
        );
    }
 
 
    public function getName()
    {
        return 'recherche';
    }
}