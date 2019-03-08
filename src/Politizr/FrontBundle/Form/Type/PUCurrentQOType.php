<?php

namespace Politizr\FrontBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Propel\Bundle\PropelBundle\Form\Type\ModelType;

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
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // Liste des organisations politiques
        $builder->add('PUCurrentQOPQOrganization', ModelType::class, array(
                'required' => false,
                'label' => 'Organisation',
                'class' => 'Politizr\\Model\\PQOrganization',
                'query' => PQOrganizationQuery::create()->filterByOnline(true)->orderByRank(),
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
    
//     /**
//      *
//      */
//     public function setDefaultOptions(OptionsResolverInterface $resolver)
//     {
//         $resolver->setDefaults(array(
//             'data_class' => 'Politizr\Model\PUCurrentQO',
//         ));
//    }
}
