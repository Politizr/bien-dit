<?php

namespace Politizr\AdminBundle\Form\Type\Homepage;

use Politizr\Model\PUserQuery;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Propel\Bundle\PropelBundle\Form\Type\ModelType;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;

/**
 * Admin notification form type
 * beta
 *
 * @author Lionel Bouzonville
 */
class AdminNotificationType extends AbstractType
{
    /**
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $users = $options['users'];

        // Msg
        $builder->add('description', 'textarea', array(
            'required' => true,
            'label' => 'Notification',
            'constraints' => new NotBlank(array('message' => 'Texte de la notification obligatoire.')),
            'attr' => array(
                'class' => 'tinymce'
            )
        ));

        // Users
        $builder->add('p_users', ModelType::class, array(
            'required' => true,
            'label' => 'Utilisateur(s)',
            'class' => 'Politizr\\Model\\PUser',
            'choices' => $users,
            'choice_label' => 'NameFirstName',
            'multiple' => true,
            'expanded' => false,
            // 'constraints' => new NotNull(array('message' => 'Choix d\'un utilisateur cible obligatoire.')),
            'attr' => array(
                'class' => 'default',
                'size' => 10,
            )
        ));
    }

    /**
     *
     * @return string
     */
    public function getName()
    {
        return 'admin_notification';
    }

    /**
     *
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'users' => null,
        ));
    }
}
