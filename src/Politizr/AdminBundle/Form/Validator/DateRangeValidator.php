<?php

namespace Politizr\AdminBundle\Form\Validator;

use DateTime;

use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DateRangeValidator implements EventSubscriberInterface
{
    protected $options = array();

    public function __construct(OptionsResolverInterface $resolver, array $options = array())
    {
        $this->setDefaultOptions($resolver);
        $this->options = $resolver->resolve($options);
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'allow_end_in_past' => false,
            'allow_single_day' => true,
        ));

        $resolver->setAllowedValues(array(
            'allow_end_in_past' => array(true, false),
            'allow_single_day' => array(true, false),
        ));
    }

    public function onPostBind(FormEvent $event)
    {
        $form = $event->getForm();

        /* @var $dateRange \Ormigo\Bundle\OrmigoBundle\Form\Model\DateRange */
        $dateRange = $form->getNormData();

        if ($dateRange->start > $dateRange->end) {
            $form->addError(new FormError('date_range.invalid.end_before_start'));
        }

        if (!$this->options['allow_single_day'] and ($dateRange->start->format('Y-m-d') === $dateRange->end->format('Y-m-d'))) {
            $form->addError(new FormError('date_range.invalid.single_day'));
        }

        if (!$this->options['allow_end_in_past'] and ($dateRange->end < new DateTime())) {
            $form->addError(new FormError('date_range.invalid.end_in_past'));
        }
    }

    public static function getSubscribedEvents()
    {
        return array(
            FormEvents::POST_BIND => 'onPostBind',
        );
    }
}
