<?php

namespace Politizr\AdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Politizr\Model\PCTopic;

/**
 * Topic localization
 *
 * @author Lionel Bouzonville
 */
class AdminPCTopicLocalizationType extends AbstractType
{
    protected $topic;

    /**
     *
     */
    public function __construct(PCTopic $topic)
    {
        $this->topic = $topic;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('p_c_topic_id', HiddenType::class, array(
            'data' => $this->topic->getId(),
        ));

        $builder->add('force_geoloc_type', ChoiceType::class, array(
            'label'    => 'Niveau du zonage géographique imposé',
            'required' => false,
            'multiple' => false,
            'choices'  => array(
                'Ville' => 'city',
                'Département' => 'department',
                'Région' => 'region',
            ),
            'choices_as_values' => true,
            'data' => $this->topic->getForceGeolocType(),
        ));

        $builder->add('force_geoloc_id', TextType::class, array(
            'label'    => 'ID Géoloc',
            'required' => false,
            'data' => $this->topic->getForceGeolocId(),
        ));
    }

    /**
     *
     * @return string
     */
    public function getName()
    {
        return 'admin_topic_localization';
    }
}
