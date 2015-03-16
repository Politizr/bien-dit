<?php

namespace Politizr\FrontBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\Validator\Constraints\NotBlank;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Politizr\Model\PQType;
use Politizr\Model\PQOrganizationQuery;

/**
 * Organisation courante
 * 
 * @author Lionel Bouzonville
 */
class PUCurrentQOType extends AbstractType
{
    // Permet de filtrer sur le type d'organisation
    private $pqTypeId;

    public function __construct($pqTypeId = PQType::ID_ELECTIF) {
        $this->pqTypeId = $pqTypeId;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('p_user_id', 'hidden');

        // Liste des organisations politiques
        $builder->add('PUCurrentQOPQOrganization', 'model', array(
                'required' => true,
                'label' => 'Parti Politique',
                'class' => 'Politizr\\Model\\PQOrganization',
                'query' => PQOrganizationQuery::create()->filterByPQTypeId($this->pqTypeId)->filterByOnline(true)->orderByRank(),
                'property' => 'title',
                'multiple' => false,
                'expanded' => false,
                // 'constraints' => new NotBlank(array('message' => 'Choix de la formule obligatoire.')),
                'attr' => array('action' => 'orga-save')
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
