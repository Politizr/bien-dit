<?php

namespace Politizr\AdminBundle\Form\Type\PDDComment;

use Admingenerated\PolitizrAdminBundle\Form\BasePDDCommentType\FiltersType as BaseFiltersType;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

/**
 * FiltersType
 */
class FiltersType extends BaseFiltersType
{
   /**
    * Get form type for active field.
    *
    * @return string|FormTypeInterface Field form type.
    */
    protected function getTypeActive()
    {
        return ChoiceType::class;
    }

   /**
    * Get options for active field.
    *
    * @param  array $builderOptions The builder options.
    * @return array Field options.
    */
    protected function getOptionsActive(array $builderOptions = array())
    {
        return array(
            'label' => 'Actif',
            'required' => false,
            'choices' => array('Oui' => 1, 'Non' => 0),
            'placeholder' => 'Oui ou Non',
            'choices_as_values' => true
        );
    }
}
