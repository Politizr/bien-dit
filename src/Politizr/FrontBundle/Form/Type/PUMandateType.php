<?php

namespace Politizr\FrontBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\Validator\Constraints\NotBlank;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Politizr\Constant\QualificationConstants;

use Politizr\Model\PQOrganizationQuery;
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

    public function __construct($pqTypeId = QualificationConstants::TYPE_ELECTIV)
    {
        $this->pqTypeId = $pqTypeId;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('id', 'hidden');
        $builder->add('p_user_id', 'hidden');
        $builder->add('p_q_type_id', 'hidden');

        // Mandates type list
        $builder->add('p_q_mandate', 'model', array(
                'required' => true,
                'label' => 'Type de mandat',
                'class' => 'Politizr\\Model\\PQMandate',
                'query' => PQMandateQuery::create()->filterByPQTypeId($this->pqTypeId)->filterByOnline(true)->orderByRank(),
                // @todo https://github.com/propelorm/PropelBundle/issues/358
                // 'group_by' => 'selectTitle',
                'property' => 'title',
                'index_property' => 'id',
                'multiple' => false,
                'expanded' => false,
                'constraints' => new NotBlank(array('message' => 'Choix d\'un mandat obligatoire.')),
            ));

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

        // Date de début
        $builder->add('begin_at', 'date', array(
                'required' => true,
                'label' => 'Date de début',
                'widget' => 'single_text',
                'format' => 'dd/MM/yyyy',
                'constraints' => new NotBlank(array('message' => 'Saisie d\'une date de début obligatoire.')),
                'invalid_message' => 'La date doit être au format JJ/MM/AAAA',
                'attr' => array('placeholder' => 'JJ/MM/AAAA')
            ));
        
        // Date de fin
        $builder->add('end_at', 'date', array(
                'required' => false,
                'label' => 'Date de fin',
                'widget' => 'single_text',
                'format' => 'dd/MM/yyyy',
                'invalid_message' => 'La date doit être au format JJ/MM/AAAA',
                'attr' => array('placeholder' => 'JJ/MM/AAAA')
            ));
        
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
