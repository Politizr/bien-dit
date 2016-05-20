<?php

namespace Politizr\FrontBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

use Symfony\Component\Validator\Constraints\NotBlank;

use Politizr\FrontBundle\Form\DataTransformer\YearToDateTransformer;

use Politizr\Constant\QualificationConstants;

use Politizr\Model\PQMandateQuery;

/**
 * User mandate edit form
 *
 * @author Lionel Bouzonville
 */
class PUMandateType extends AbstractType
{
    // Permet de filtrer sur le type d'organisation
    private $pqTypeId;

    // special admin
    private $userId;

    public function __construct($pqTypeId = QualificationConstants::TYPE_ELECTIV, $userId = null)
    {
        $this->pqTypeId = $pqTypeId;
        $this->userId = $userId;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('uuid', 'hidden');
        $builder->add('p_q_type_id', 'hidden', array('data' => $this->pqTypeId));

        if ($this->userId) {
            $builder->add('p_user_id', 'hidden', array('data' => $this->userId));
        }

        // Mandates type list
        $builder->add('p_q_mandate', 'model', array(
                'required' => true,
                'label' => 'Type de mandat',
                'class' => 'Politizr\\Model\\PQMandate',
                'query' => PQMandateQuery::create()->filterByPQTypeId($this->pqTypeId)->filterByOnline(true)->orderByRank(),
                // https://github.com/propelorm/PropelBundle/issues/358
                // http://stackoverflow.com/questions/32602183/propel-form-type-model-w-group-by-is-rendered-without-property-display
                // 'group_by' => 'selectTitle',
                'property' => 'title',
                'index_property' => 'id',
                'multiple' => false,
                'expanded' => false,
                'constraints' => new NotBlank(array('message' => 'Choix d\'un mandat obligatoire.')),
            ));


        // $builder->addEventListener(FormEvents::POST_SET_DATA, function (FormEvent $event) {
        //     $form = $event->getForm();
        //     dump($form);
        // });

        // Localization
        $builder->add('localization', 'text', array(
                'required' => true,
                'label' => 'Localisation',
                'constraints' => new NotBlank(array('message' => 'Localisation obligatoire.')),
                'attr' => array('placeholder' => 'Ville, circonscriptrion, etc...')
            ));
        
        // // Liste des organisations politiques
        // $builder->add('p_q_organization', 'model', array(
        //         'required' => false,
        //         'label' => 'Parti Politique',
        //         'class' => 'Politizr\\Model\\PQOrganization',
        //         'query' => PQOrganizationQuery::create()->filterByPQTypeId($this->pqTypeId)->filterByOnline(true)->orderByRank(),
        //         'property' => 'title',
        //         'multiple' => false,
        //         'expanded' => false,
        //     ));

        // Begin date
        $builder->add(
            $builder->create(
                'begin_at',
                'text',
                array(
                    'required' => true,
                    'constraints' => array(
                        new NotBlank(array('message' => 'Saisie d\'une année de début obligatoire.')),
                    )
                )
            )->addModelTransformer(new YearToDateTransformer())
        );
        
        // Date de fin
        $builder->add(
            $builder->create(
                'end_at',
                'text'
            )->addModelTransformer(new YearToDateTransformer())
        );
    }

    /**
     *
     * @return string
     */
    public function getName()
    {
        return 'mandate';
    }
    
    /**
     *
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Politizr\Model\PUMandate',
        ));
    }
}
