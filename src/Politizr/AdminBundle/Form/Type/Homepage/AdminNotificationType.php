<?php

namespace Politizr\AdminBundle\Form\Type\Homepage;

use Politizr\Model\PUserQuery;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

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
        $builder->add('description', 'textarea', array(
            'required' => true,
            'label' => 'Notification',
            'constraints' => new NotBlank(array('message' => 'Texte de la notification obligatoire.')),
            'attr' => array(
                'class' => 'tinymce'
            )
        ));

        // Users
        $builder->add('p_users', 'Propel\Bundle\PropelBundle\Form\Type\ModelType', array(
            'required' => true,
            'label' => 'Cible(s)',
            'class' => 'Politizr\\Model\\PUser',
            'query' => PUserQuery::create()->filterByOnline(true)->orderByName(),
            'multiple' => true,
            'expanded' => false,
            'constraints' => new NotNull(array('message' => 'Choix d\'un utilisateur cible obligatoire.')),
            'attr' => array(
                'class' => 'default'
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
}
