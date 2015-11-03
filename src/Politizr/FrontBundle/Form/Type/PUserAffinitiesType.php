<?php

namespace Politizr\FrontBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\Validator\Constraints\NotBlank;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Politizr\Constant\QualificationConstants;

use Politizr\Model\PQOrganizationQuery;

/**
 * Affinités d'organisation d'un profil
 *
 * @author Lionel Bouzonville
 */
class PUserAffinitiesType extends AbstractType
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

        // Liste des organisations politiques
        $builder->add('PUAffinityQOPQOrganizations', 'model', array(
                'required' => false,
                'label' => 'Partis Politiques',
                'class' => 'Politizr\\Model\\PQOrganization',
                'query' => PQOrganizationQuery::create()->filterByPQTypeId($this->pqTypeId)->filterByOnline(true)->orderByRank(),
                'property' => 'title',
                'multiple' => true,
                'expanded' => true,
                // 'constraints' => new NotBlank(array('message' => 'Choix obligatoire.')),
            ));

    }

    /**
     *
     * @return string
     */
    public function getName()
    {
        return 'user_affinities';
    }
    
    /**
     *
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Politizr\Model\PUser',
        ));
    }
}
