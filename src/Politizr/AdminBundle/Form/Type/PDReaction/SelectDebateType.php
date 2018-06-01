<?php

namespace Politizr\AdminBundle\Form\Type\PDReaction;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Politizr\Model\PDDebateQuery;

/**
 * SelectDebateType
 */
class SelectDebateType extends AbstractType
{
    private $topicId;

    public function __construct($topicId)
    {
        $this->topicId = $topicId;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('p_d_debate', 'Admingenerator\\FormExtensionsBundle\\Form\\Type\\Select2ModelType', array(
            'class' => 'Politizr\\Model\\PDDebate',
            'mapped' => false,
            'multiple' => false,
            'query' => PDDebateQuery::create()->online()->filterByPCTopicId($this->topicId)->orderByTitle(),
            'required' => true,
            'empty_data' => null,
            'label' => 'Débat associée',
        ));
    }

    /**
     *
     * @return string
     */
    public function getName()
    {
        return 'select_debate';
    }
}
