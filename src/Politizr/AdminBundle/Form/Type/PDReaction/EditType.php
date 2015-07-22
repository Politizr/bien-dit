<?php

namespace Politizr\AdminBundle\Form\Type\PDReaction;

use Admingenerated\PolitizrAdminBundle\Form\BasePDReactionType\EditType as BaseEditType;

use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;

/**
 * EditType
 */
class EditType extends BaseEditType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);


        // http://symfony.com/doc/2.3/cookbook/form/dynamic_form_modification.html
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($options) {
            $form = $event->getForm();
            $pdReaction = $event->getData();

            $form->add('p_d_debate_id', $this->getTypePDDebateId(), array_merge(['data' => $pdReaction->getPDDebateId()], $this->getOptionsPDDebateId($options)));
        });

    }
}
