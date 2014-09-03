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
	    $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event)
	    {
		    $form = $event->getForm();
		    $pdReaction = $event->getData();

		    // ID débat associé
	        $formOptions = $this->getFormOption('p_d_debate_id', array( 'data' => $pdReaction->getPDDebateId(), 'label' => 'ID Débat',  'translation_domain' => 'Admin',));
	        $form->add('p_d_reaction_related_by_p_d_reaction_id', 'hidden', $formOptions);

		    // MAJ liste des réactions associés possibles > seulement sur le débat courant
	        $formOptions = $this->getFormOption('p_d_reaction_related_by_p_d_reaction_id', array(  'class' => 'Politizr\\Model\\PDReaction',  'multiple' => false,  'empty_value' => '',  'required' => false,  'query' => \Politizr\Model\PDReactionQuery::create()->filterByPDDebateId($pdReaction->getPDDebateId())->filterById($pdReaction->getId(), \Criteria::NOT_EQUAL)->orderByCreatedAt(\Criteria::DESC),  'label' => 'Réaction associée',  'translation_domain' => 'Admin',));
	        $form->add('p_d_reaction_related_by_p_d_reaction_id', 'model', $formOptions);
	    });

	}
}
