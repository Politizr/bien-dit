<?php

namespace Politizr\FrontBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Inscription user élu / choix de la formule
 *
 * @author Lionel Bouzonville
 */
class POrderSubscriptionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // Liste des formules
        $builder->add('p_o_subscription', 'model', array(
            'required' => true,
            'label' => 'Formule',
            'class' => 'Politizr\\Model\\POSubscription',
            'property' => 'titleAndPrice',
            'multiple' => false,
            'expanded' => true,
            'constraints' => new NotBlank(array('message' => 'Choix de la formule obligatoire.')),
        ));

        $builder->add('cgv', 'checkbox', array(
            'required' => true,
            'mapped' => false
        ));
    }

    /**
     *
     * @return string
     */
    public function getName()
    {
        return 'order_subscription';
    }
}
