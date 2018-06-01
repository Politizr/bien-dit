<?php

namespace Politizr\AdminBundle\Form\Type\PUser;

use Admingenerated\PolitizrAdminBundle\Form\BasePUserType\EditType as BaseEditType;

use Politizr\Model\PCircleQuery;

/**
 * EditType
 */
class EditType extends BaseEditType
{

    /**
     * Get options for roles field.
     *
     * @param  array $builderOptions The builder options.
     * @return array Field options.
     */
    protected function getOptionsRoles(array $builderOptions = array())
    {
        $optionsClass = 'Politizr\AdminBundle\Form\Type\PUser\Options';
        $options = class_exists($optionsClass) ? new $optionsClass() : null;

        $securityRoles = array(
            // 'ROLE_OAUTH_USER' => 'Connexion OpenID',
            // 'ROLE_CITIZEN_INSCRIPTION' => 'Inscription Citoyen en cours',
            // 'ROLE_ELECTED_INSCRIPTION' => 'Inscription Élu en cours',
            'ROLE_CITIZEN' => 'Utilisateur',
            'ROLE_ELECTED' => 'Élu',
            // 'ROLE_PROFILE_COMPLETED' => 'Inscription terminée',
        );

        $circles = PCircleQuery::create()->find();
        $circleRoles = [];
        foreach ($circles as $circle) {
            $circleRoles['ROLE_CIRCLE_' . $circle->getId()] = 'Membre du groupe '.$circle->getTitle().' ('.$circle->getPCOwner().')';
        }

        $choices =array_merge($securityRoles, $circleRoles);

        return $this->resolveOptions('roles', array(
            'label' => 'Liste des rôles de l\'utilisateur',
            'translation_domain' => 'Admin',
            'required' => false,
            'expanded' => true,
            'multiple' => true,
            'choices' => $choices,
        ), $builderOptions, $options);
    }
}
