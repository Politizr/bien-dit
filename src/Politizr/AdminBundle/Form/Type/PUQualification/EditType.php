<?php

namespace Politizr\AdminBundle\Form\Type\PUQualification;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Admingenerated\PolitizrAdminBundle\Form\BasePUQualificationType\EditType as BaseEditType;

/**
 * EditType
 */
class EditType extends BaseEditType
{
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        // parent::setDefaultOptions($resolver); 
        $resolver->setDefaults(array(
            'data_class' => 'Politizr\Model\PUQualification' 
            ));
    }

  	public function getName()
    {
        return 'p_u_qualifications';
    }

}
