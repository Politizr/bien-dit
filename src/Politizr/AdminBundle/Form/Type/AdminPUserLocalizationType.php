<?php

namespace Politizr\AdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\Validator\Constraints\NotBlank;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Politizr\FrontBundle\Form\Type\LocalizationChoiceType;

use Politizr\Model\PUser;

/**
 * User localization
 * beta
 *
 * @author Lionel Bouzonville
 */
class AdminPUserLocalizationType extends AbstractType
{
    protected $user;

    /**
     *
     */
    public function __construct(PUser $user)
    {
        $this->user = $user;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // Attributs cachés obligatoires
        $builder->add('p_user_id', 'hidden', array(
            'data' => $this->user->getId(),
        ));

        // Localization type
        $builder->add('localization', LocalizationChoiceType::class, array(
            'required' => true,
            'city_id' => $this->user->getPLCityId(),
            'label_department' => 'Département',
            'label_city' => 'Ville',
        ));
    }

    /**
     *
     * @return string
     */
    public function getName()
    {
        return 'admin_user';
    }
}
