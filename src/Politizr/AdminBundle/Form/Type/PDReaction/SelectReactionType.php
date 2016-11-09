<?php

namespace Politizr\AdminBundle\Form\Type\PDReaction;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Politizr\Model\PDReactionQuery;

/**
 * SelectReactionType
 */
class SelectReactionType extends AbstractType
{
    private $debateId;

    public function __construct($debateId)
    {
        $this->debateId = $debateId;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('parent_reaction', 'Admingenerator\\FormExtensionsBundle\\Form\\Type\\Select2ModelType', array(
            'class' => 'Politizr\\Model\\PDReaction',
            'mapped' => false,
            'multiple' => false,
            'query' => PDReactionQuery::create()->online()->filterByPDDebateId($this->debateId)->orderByTitle(),
            'required' => false,
            'empty_data' => null,
            'label' => 'Réaction associée',
        ));
    }

    /**
     *
     * @return string
     */
    public function getName()
    {
        return 'select_reaction';
    }
}
