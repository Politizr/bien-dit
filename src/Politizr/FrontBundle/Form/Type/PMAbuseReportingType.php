<?php

namespace Politizr\FrontBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Abuse reporting edition form
 *
 * @author Lionel Bouzonville
 */
class PMAbuseReportingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('p_user_id', 'hidden', array(
            'required' => true,
        ));

        $builder->add('p_object_name', 'hidden', array(
            'required' => true,
        ));

        $builder->add('p_object_uuid', 'hidden', array(
            'required' => true,
        ));

        $builder->add('message', 'textarea', array(
            'required' => false,
        ));
    }

    /**
     *
     * @return string
     */
    public function getName()
    {
        return 'abuse_reporting';
    }
    
    /**
     *
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Politizr\Model\PMAbuseReporting',
        ));
    }
}
