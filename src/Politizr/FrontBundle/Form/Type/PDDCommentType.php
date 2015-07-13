<?php

namespace Politizr\FrontBundle\Form\Type;

/**
 * Debate's comment type
 *
 * @author Lionel Bouzonville
 */
class PDCommentType extends PDCommentType
{
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
