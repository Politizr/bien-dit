<?php

namespace Politizr\AdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Politizr\AdminBundle\Form\DataTransformer\DateRangeViewTransformer;
use Politizr\AdminBundle\Form\Form\Validator\DateRangeValidator;

class DateRangeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('start_date', 'date', array_merge_recursive(array(
                'property_path' => 'start',
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'model_timezone' => 'UTC',
                'view_timezone' => 'UTC',
                'attr' => array(
                    'data-type' => 'start',
                ),
            ), $options['start_options']))
            ->add('end_date', 'date', array_merge_recursive(array(
                'property_path' => 'end',
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'model_timezone' => 'UTC',
                'view_timezone' => 'UTC',
                'attr' => array(
                    'data-type' => 'end',
                ),
            ), $options['end_options']))
        ;

        $builder->addViewTransformer($options['transformer']);
        $builder->addEventSubscriber($options['validator']);
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Politizr\AdminBundle\Form\Model\DateRange',
            'end_options' => array(),
            'start_options' => array(),
            'transformer' => null,
            'validator' => null,
        ));

        $resolver->setAllowedTypes(array(
            'transformer' => 'Symfony\Component\Form\DataTransformerInterface',
            'validator' => 'Symfony\Component\EventDispatcher\EventSubscriberInterface',
        ));

        // Those normalizers lazily create the required objects, if none given.
        $resolver->setNormalizers(array(
            'transformer' => function (Options $options, $value) {
                if (!$value) {
                    $value = new DateRangeViewTransformer(new OptionsResolver());
                }

                return $value;
            },
            'validator' => function (Options $options, $value) {
                if (!$value) {
                    $value = new DateRangeValidator(new OptionsResolver());
                }

                return $value;
            },
        ));
    }

    public function getName()
    {
        return 'date_range';
    }
}
