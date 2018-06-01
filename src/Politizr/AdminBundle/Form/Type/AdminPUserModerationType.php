<?php

namespace Politizr\AdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

use Propel\Bundle\PropelBundle\Form\Type\ModelType;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Range;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Politizr\Model\PUser;

use Politizr\Model\PMModerationTypeQuery;

/**
 * User moderation
 * beta
 *
 * @author Lionel Bouzonville
 */
class AdminPUserModerationType extends AbstractType
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
        $builder->add('p_user_id', HiddenType::class, array(
            'data' => $this->user->getId(),
        ));

        // Moderation type list
        $builder->add('p_m_moderation_type', ModelType::class, array(
                'required' => true,
                'label' => 'Type de modération',
                'class' => 'Politizr\\Model\\PMModerationType',
                'query' => PMModerationTypeQuery::create()->orderById('asc'),
                'empty_data'  => null,
                'index_property' => 'id',
                'multiple' => false,
                'expanded' => false,
            ));
        
        $builder->add('score_evolution', TextType::class, array(
            'required' => false,
            'label' => 'Evolution de réputation',
            'constraints' => array(
                new Range(array('max' => '0', 'maxMessage' => 'Nombre négatif')),
            ),
            'attr' => array('placeholder' => 'Nombre négatif soustrait à la réputation, par exemple "-10"'),
        ));

        $builder->add('ban', CheckboxType::class, array(
            'label'    => 'Utilisateur banni',
            'required' => false,
        ));

        $builder->add('firstname', TextType::class, array(
            'label'    => 'Prénom',
            'required' => false,
            'data' => $this->user->getFirstname(),
        ));

        $builder->add('name', TextType::class, array(
            'label'    => 'Nom',
            'required' => false,
            'data' => $this->user->getName(),
        ));

        $builder->add('biography', TextareaType::class, array(
            'label'    => 'Biographie',
            'required' => false,
            'data' => $this->user->getBiography(),
        ));

        $builder->add('website', TextType::class, array(
            'label'    => 'Site web',
            'required' => false,
            'data' => $this->user->getWebsite(),
        ));

        $builder->add('twitter', TextType::class, array(
            'label'    => 'Twitter',
            'required' => false,
            'data' => $this->user->getTwitter(),
        ));

        $builder->add('facebook', TextType::class, array(
            'label'    => 'Facebook',
            'required' => false,
            'data' => $this->user->getFacebook(),
        ));

        $builder->add('send_email', CheckboxType::class, array(
            'label'    => 'Envoyer email',
            'required' => false,
        ));
    }

    /**
     *
     * @return string
     */
    public function getName()
    {
        return 'admin_user_moderation';
    }
}
