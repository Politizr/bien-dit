<?php

namespace Politizr\FrontBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Politizr\Model\PDocumentInterface;

/**
 * Reaction's comment type
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

        $builder->add('p_d_reaction_id', 'hidden', array(
            'required' => true,
        ));

        $builder->add('type', 'hidden', array(
            'required' => true,
            'data' => PDocumentInterface::TYPE_REACTION_COMMENT,
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
