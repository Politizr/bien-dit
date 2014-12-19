<?php

namespace Politizr\AdminBundle\Menu;

use Admingenerator\GeneratorBundle\Menu\AdmingeneratorMenuBuilder;
use Knp\Menu\FactoryInterface;

class AdminMenu extends AdmingeneratorMenuBuilder
{
    protected $translation_domain = 'Admin';
    
    /**
     * @param Request $requestaddNavLinkURI
     * @param Router $router
     */
    public function navbarMenu(FactoryInterface $factory, array $options)
    {
        $user = $this->container->get('security.context')->getToken()->getUser();
      
        $menu = $factory->createItem('root');
        $menu->setChildrenAttributes(array('id' => 'main_navigation', 'class' => 'nav navbar-nav'));
        
        // Order
        $orders = $this->addLinkRoute($menu, 'Commande', 'Politizr_AdminBundle_POrder_list');

        // User 
        $users = $this->addLinkRoute($menu, 'Utilisateur', 'Politizr_AdminBundle_PUser_list');

        // Document
        $documents = $this->addLinkRoute($menu, 'Débat', 'Politizr_AdminBundle_PDDebate_list');

        // Commentaires
        $comments = $this->addLinkRoute($menu, 'Commentaire', 'Politizr_AdminBundle_PDComment_list');

        // Tags
        $tags = $this->addLinkRoute($menu, 'Tag', 'Politizr_AdminBundle_PTag_list');


        // Réputation
        $reputation = $this->addDropdown($menu, 'Réputation');
        $this->addLinkRoute(
            $reputation,
            'Badge',
            'Politizr_AdminBundle_PRBadge_list'
        );
        $this->addLinkRoute(
            $reputation,
            'Action',
            'Politizr_AdminBundle_PRAction_list'
        );

        // Réglages des types:  tags, badges, mode & statut paiement, statut commande
        $types = $this->addDropdown($menu, 'Type');
        $this->addLinkRoute(
            $types,
            'Tag',
            'Politizr_AdminBundle_PTTagType_list'
        );
        $this->addLinkRoute(
            $types,
            'Badge',
            'Politizr_AdminBundle_PRBadgeType_list'
        );
        $this->addLinkRoute(
            $types,
            'Niveau des badge',
            'Politizr_AdminBundle_PRBadgeMetal_list'
        );
        $this->addLinkRoute(
            $types,
            'Formule d\'abonnement',
            'Politizr_AdminBundle_POSubscription_list'
        );
        $this->addLinkRoute(
            $types,
            'Mode Paiement',
            'Politizr_AdminBundle_POPaymentType_list'
        );
        $this->addLinkRoute(
            $types,
            'Statut Paiement',
            'Politizr_AdminBundle_POPaymentState_list'
        );
        $this->addLinkRoute(
            $types,
            'Statut Commande',
            'Politizr_AdminBundle_POOrderState_list'
        );


        // Réglages techniques: notifications utilisateurs
        $regulations = $this->addDropdown($menu, 'Réglage');
        $this->addLinkRoute(
            $regulations,
            'Administrateurs',
            'Politizr_AdminBundle_User_list'
        );


        
        return $menu;
    }
}
