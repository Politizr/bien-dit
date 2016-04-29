<?php

namespace Politizr\FrontBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Politizr\Constant\ObjectTypeConstants;

/**
 * Debate's comment type
 * beta
 *
 * @author Lionel Bouzonville
 */
class PDDCommentType extends PDCommentType
{
    /**
     *
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder->add('type', 'hidden', array(
            'required' => true,
            'data' => ObjectTypeConstants::TYPE_DEBATE_COMMENT,
        ));
    }

    /**
     *
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Politizr\Model\PDDComment',
        ));
    }
}
