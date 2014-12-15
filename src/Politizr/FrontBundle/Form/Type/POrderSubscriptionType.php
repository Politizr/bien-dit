<?php

namespace Politizr\FrontBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * TODO: commentaires
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

        $builder->add('actions', 'form_actions', [
                'buttons' => [
                    'save' => ['type' => 'submit', 'options' => ['label' => 'Valider', 'attr' => [ 'class' => 'btn-success' ] ]],
                ]
            ]);
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
