<?php

namespace Politizr\AdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Propel\Bundle\PropelBundle\Form\Type\ModelType;

use Symfony\Component\Form\Extension\Core\Type\HiddenType;

use Politizr\Model\PUserQuery;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;

/**
 * Admin users management form type for circle
 * beta
 *
 * @author Lionel Bouzonville
 */
class PCirclePUsersSelectListType extends AbstractType
{
    /**
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $circleId = $options['circle_id'];
        $users = $options['users'];

        // Circle Id
        $builder->add('p_circle_id', HiddenType::class, array(
            'data' => $circleId,
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
        return 'admin_circle_users_list';
    }

    /**
     *
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'circle_id' => null,
            'users' => null,
        ));
    }
}
