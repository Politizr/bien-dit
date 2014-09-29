<?php

namespace Politizr\FrontBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Email;

use Propel\PropelBundle\Validator\Constraints\UniqueObject;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Politizr\Model\PUser;
use Politizr\Model\PUStatus;
use Politizr\Model\PUType;

/**
 * form minimaliste > token, id
 * 
 * @author Lionel Bouzonville
 */
class PDDebateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
//         $builder->add('uploadedFileName', 'afe_single_upload', array(
//             'required' => false,
//             'label' => 'Image d\'illustration', 
//             'deleteable' => 'fileName',
//             'data_class' => 'Symfony\\Component\\HttpFoundation\\File\\File',
//             // TODO: contrainte sur la taille minimum
//             // 'constraints' => new NotBlank(array('message' => 'Titre obligatoire.'))
//             )
//         );
//         
        $builder->add('title', 'text', array(
            'required' => false,
            'label' => 'Titre', 
            )
        );
        
        $builder->add('summary', 'hidden', array(
            'required' => false,
            'label' => 'Résumé', 
            )
        );
        
        $builder->add('description', 'hidden', array(
            'required' => false,
            'label' => 'Description', 
            'attr' =>   array(
                'class' => 'editor',
                )
            )
        );

        $builder->add('more_info', 'hidden', array(
            'required' => false,
            'label' => 'Pour en savoir plus', 
            'attr' =>   array(
                'class' => 'editor',
                )
            )
        );


        $builder->add('id', 'hidden', array(
            'required' => true, 
            )
        );
        
        $builder->add('p_user_id', 'hidden', array(
            'required' => true, 
            )
        );
        
        
    }

    /**
     * 
     * @return string
     */
    public function getName()
    {
        return 'debate';
    }    
    
    /**
     * 
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Politizr\Model\PDDebate',
        ));
    }

}
