<?php

namespace Politizr\AdminBundle\Form\Type\PDReaction;

use Admingenerated\PolitizrAdminBundle\Form\BasePDReactionType\NewType as BaseNewType;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

use Politizr\Model\PDReactionQuery;

/**
 * NewType
 */
class NewType extends BaseNewType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        if ($this->canDisplayParentReaction()) {
            // see http://symfony.com/doc/2.8/cookbook/form/dynamic_form_modification.html#cookbook-form-events-underlying-data
            $formModifier = function (FormInterface $form, $debateId = null) {
                $formOptions = array(
                    'class' => 'Politizr\\Model\\PDReaction',
                    'mapped' => false,
                    'multiple' => false,
                    'query' => PDReactionQuery::create()->online()->filterByPDDebateId($debateId)->orderByTitle(),
                    'required' => false,
                    'empty_data' => null,
                    'label' => 'Réaction associée',
                    'translation_domain' => 'Admin',
                );
                $form->add('parent_reaction', 'Admingenerator\\FormExtensionsBundle\\Form\\Type\\Select2ModelType', $formOptions);
            };

            $builder->addEventListener(
                FormEvents::PRE_SET_DATA,
                function (FormEvent $event) use ($formModifier) {
                    $formModifier($event->getForm(), null);
                }
            );

            $builder->get('p_d_debate')->addEventListener(
                FormEvents::POST_SUBMIT,
                function (FormEvent $event) use ($formModifier) {
                    // It's important here to fetch $event->getForm()->getData(), as
                    // $event->getData() will get you the client data (that is, the ID)
                    $debate = $event->getForm()->getData();
            
                    // since we've added the listener to the child, we'll have to pass on
                    // the parent to the callback functions!
                    $formModifier($event->getForm()->getParent(), $debate?$debate->getId():null);
                }
            );
        }
    }
}
