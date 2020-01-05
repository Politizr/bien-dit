<?php

namespace Politizr\FrontBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

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
        $builder->add('p_tags', 'Propel\Bundle\PropelBundle\Form\Type\ModelType', array(
            'label' => 'Type',
            'class' => 'Politizr\\Model\\PTag',
            'query' => PTagQuery::create()
                ->filterByPTTagTypeId(TagConstants::TAG_TYPE_TYPE)
                ->filterByOnline(true)
                // ->_if(!$options['elected_mode'])
                //     ->filterById(TagConstants::TAG_TYPE_ACTION_CONCRETE_ID, \Criteria::NOT_EQUAL)
                // ->_endif()
                ->orderByTitle(),
            'property' => 'title',
            'index_property' => 'uuid',
            'multiple' => true,
            'expanded' => true,
            // 'constraints' => new NotBlank(array('message' => 'Choix d\'un mandat obligatoire.')),
        ));

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'elected_mode' => true,
        ));
    }

    /**
     *
     * @return string
     */
    public function getName()
    {
        return 'document_tag_type';
    }
}
