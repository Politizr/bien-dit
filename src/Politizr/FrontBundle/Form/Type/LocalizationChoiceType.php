<?php

namespace Politizr\FrontBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;

use Symfony\Component\Validator\Constraints\NotBlank;

use Politizr\Model\PLDepartmentQuery;
use Politizr\Model\PLCityQuery;

/**
 * Localization custom form type
 * /!\ instanciation must be created by service only
 *
 * @author Lionel Bouzonville
 */
class LocalizationChoiceType extends AbstractType
{
    private $localizationManager;

    /**
     *
     * @param @politizr.localization.tag
     */
    public function __construct(
        $localizationManager
    ) {
        $this->localizationManager = $localizationManager;
    }

    /**
     *
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // prefill
        $cityUuid = $this->localizationManager->getCityUuidByCityId($options['city_id']);
        $departmentUuid = $this->localizationManager->getDepartmentUuidByCityId($options['city_id']);

        // Department type list
        $departmentChoices = $this->localizationManager->getDepartmentChoices();
        $builder->add('department', 'choice', array(
            'label' => 'Votre département',
            'required' => true,
            'choices' => $departmentChoices,
            'choices_as_values' => true,
            'multiple' => false,
            'expanded' => false,
            'placeholder' => 'Choisissez votre département',
            'empty_data'  => null,
            'attr' => array('class' => 'select2_choice department_choice'),
            'constraints' => new NotBlank(array('message' => 'Choix d\'un département obligatoire.')),
            'data' => $departmentUuid,
        ));

        // see http://symfony.com/doc/2.8/cookbook/form/dynamic_form_modification.html#cookbook-form-events-underlying-data
        $formModifier = function (FormInterface $form, $departmentUuid = null, $cityUuid = null) {
            // City type list / 30438
            $cityChoices = $this->localizationManager->getCityChoices($departmentUuid);
            $form->add('city', 'choice', array(
                'label' => 'Votre ville',
                'required' => true,
                'choices' => $cityChoices,
                'choices_as_values' => true,
                'multiple' => false,
                'expanded' => false,
                'placeholder' => 'Choisissez votre ville',
                'empty_data'  => null,
                'attr' => array('class' => 'select2_choice city_choice'),
                'constraints' => new NotBlank(array('message' => 'Choix d\'une ville obligatoire.')),
                'data' => $cityUuid,
            ));
        };

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) use ($formModifier, $departmentUuid, $cityUuid) {
                $formModifier($event->getForm(), $departmentUuid, $cityUuid);
            }
        );

        $builder->get('department')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) use ($formModifier, $departmentUuid, $cityUuid) {
                // It's important here to fetch $event->getForm()->getData(), as
                // $event->getData() will get you the client data (that is, the ID)
                $departmentUuid = $event->getForm()->getData();
        
                // since we've added the listener to the child, we'll have to pass on
                // the parent to the callback functions!
                $formModifier($event->getForm()->getParent(), $departmentUuid, $cityUuid);
            }
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'city_id' => null,
        ));
    }

    /**
     *
     * @return string
     */
    public function getName()
    {
        return 'localization_choice';
    }
}
