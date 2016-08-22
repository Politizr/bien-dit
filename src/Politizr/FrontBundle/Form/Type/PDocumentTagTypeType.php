<?php

namespace Politizr\FrontBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Politizr\Constant\TagConstants;

use Politizr\Model\PTagQuery;

/**
 * Document tag type form
 * beta
 *
 * @author Lionel Bouzonville
 */
class PDocumentTagTypeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // Tag type list
        $builder->add('p_tags', 'model', array(
            'label' => 'Type',
            'class' => 'Politizr\\Model\\PTag',
            'query' => PTagQuery::create()->filterByPTTagTypeId(TagConstants::TAG_TYPE_TYPE)->filterByOnline(true)->orderByTitle(),
            'property' => 'title',
            'index_property' => 'uuid',
            'multiple' => true,
            'expanded' => true,
            // 'constraints' => new NotBlank(array('message' => 'Choix d\'un mandat obligatoire.')),
        ));

    }

    /**
     *
     * @return string
     */
    public function getName()
    {
        return 'debate_tag_type';
    }
}
