<?php

namespace Politizr\AdminBundle\Form\Type\PDReaction;

use Admingenerated\PolitizrAdminBundle\Form\BasePDReactionType\NewType as BaseNewType;

use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;


/**
 * NewType
 */
class NewType extends BaseNewType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    	parent::buildForm($builder, $options);


    	// http://symfony.com/doc/2.3/cookbook/form/dynamic_form_modification.html
	    $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event)
	    {
		    $form = $event->getForm();
		    $pdReaction = $event->getData();

	    });

	}
}
