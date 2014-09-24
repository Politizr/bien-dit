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
        $users = $this->addLinkRoute($menu, 'Débat', 'Politizr_AdminBundle_PDDebate_list');

        // Commentaires
        $comments = $this->addDropdown($menu, 'Commentaire');
        $this->addLinkRoute(
            $comments,
            'Débat',
            'Politizr_AdminBundle_PDDComment_list'
        );
        $this->addLinkRoute(
            $comments,
            'Réaction',
            'Politizr_AdminBundle_PDRComment_list'
        );

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

        // Réglages des types:  tags, badges, mode & statut paiement, statut commande, partis politiques, mandats électifs
        $documents = $this->addDropdown($menu, 'Type');
        $this->addLinkRoute(
            $documents,
            'Parti politique',
            'Politizr_AdminBundle_PUPoliticalParty_list'
        );
        $this->addLinkRoute(
            $documents,
            'Mandat électif',
            'Politizr_AdminBundle_PUMandateType_list'
        );
        $this->addLinkRoute(
            $documents,
            'Tag',
            'Politizr_AdminBundle_PTTagType_list'
        );
        $this->addLinkRoute(
            $documents,
            'Badge',
            'Politizr_AdminBundle_PRBadgeType_list'
        );
        $this->addLinkRoute(
            $documents,
            'Formule d\'abonnement',
            'Politizr_AdminBundle_POSubscription_list'
        );
        $this->addLinkRoute(
            $documents,
            'Mode Paiement',
            'Politizr_AdminBundle_POPaymentType_list'
        );
        $this->addLinkRoute(
            $documents,
            'Statut Paiement',
            'Politizr_AdminBundle_POPaymentState_list'
        );
        $this->addLinkRoute(
            $documents,
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
