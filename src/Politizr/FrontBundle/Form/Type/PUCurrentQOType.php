<?php

namespace Politizr\FrontBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Politizr\Constant\QualificationConstants;

use Politizr\Model\PQOrganizationQuery;

/**
 * Organisation courante
 * beta
 *
 * @author Lionel Bouzonville
 */
class PUCurrentQOType extends AbstractType
{
    // Permet de filtrer sur le type d'organisation
    private $pqTypeId;

    public function __construct($pqTypeId = QualificationConstants::TYPE_ELECTIV)
    {
        $this->pqTypeId = $pqTypeId;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // Liste des organisations politiques
        $builder->add('PUCurrentQOPQOrganization', 'Propel\Bundle\PropelBundle\Form\Type\ModelType', array(
                'required' => true,
                'label' => 'Parti Politique',
                'class' => 'Politizr\\Model\\PQOrganization',
                'query' => PQOrganizationQuery::create()->filterByPQTypeId($this->pqTypeId)->filterByOnline(true)->orderByRank(),
                'property' => 'title',
                'multiple' => false,
                'expanded' => false,
            ));

    }

    /**
     *
     * @return string
     */
    public function getName()
    {
        return 'current_organization';
    }
    
    /**
     *
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Politizr\Model\PUCurrentQO',
        ));
    }
}
