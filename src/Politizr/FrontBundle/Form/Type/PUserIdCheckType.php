<?php

namespace Politizr\FrontBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Gestion de la MAJ des données personnelles
 *
 * @author Lionel Bouzonville
 */
class PUserIdCheckType extends AbstractType
{
    // special admin
    private $userId;

    public function __construct($userId = null)
    {
        $this->userId = $userId;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($this->userId) {
            $builder->add('p_user_id', 'hidden', array(
                'mapped' => false,
                'data' => $this->userId
            ));
        }

        $builder->add('zla1', 'text', array(
            'required' => true,
            'mapped' => false,
            'constraints' => array(
                new NotBlank(array('message' => 'Zone obligatoire.')),
                new Length(array(
                    'charset' => 'UTF-8',
                    'min' => 36,
                    'max' => 36,
                    'minMessage' => 'Ce champ doit faire exactement 36 caractères.',
                    'maxMessage' => 'Ce champ doit faire exactement 36 caractères.'
                )),
            )
        ));
        $builder->add('zla2', 'text', array(
            'required' => true,
            'mapped' => false,
            'constraints' => array(
                new NotBlank(array('message' => 'Zone obligatoire.')),
                new Length(array(
                    'charset' => 'UTF-8',
                    'min' => 36,
                    'max' => 36,
                    'minMessage' => 'Ce champ doit faire exactement 36 caractères.',
                    'maxMessage' => 'Ce champ doit faire exactement 36 caractères.'
                )),
            )
        ));
    }

    /**
     *
     * @return string
     */
    public function getName()
    {
        return 'user_id_check';
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
