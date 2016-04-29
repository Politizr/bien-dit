<?php

namespace Politizr\FrontBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Politizr\Constant\ObjectTypeConstants;

/**
 * Reaction's comment type
 * beta
 *
 * @author Lionel Bouzonville
 */
class PDRCommentType extends PDCommentType
{
    /**
     *
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder->add('type', 'hidden', array(
            'required' => true,
            'data' => ObjectTypeConstants::TYPE_REACTION_COMMENT,
        ));
    }

    /**
     *
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Politizr\Model\PDRComment',
        ));
    }
}
