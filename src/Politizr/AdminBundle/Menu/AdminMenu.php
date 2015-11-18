<?php
namespace Politizr\AdminBundle\Menu;

use Admingenerator\GeneratorBundle\Menu\AdmingeneratorMenuBuilder;
use Knp\Menu\FactoryInterface;

/**
 *
 * @author Lionel Bouzonville
 */
class AdminMenu extends AdmingeneratorMenuBuilder
{
    protected $translation_domain = 'Admin';
   
    
    /**
     * @param Request $requestaddNavLinkURI
     * @param Router $router
     */
    public function sidebarMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root');
        $menu->setChildrenAttributes(array('class' => 'sidebar-menu'));

        if ($dashboardRoute = $this->container->getParameter('admingenerator.dashboard_route')) {
            $this
                ->addLinkRoute($menu, 'Accueil', $dashboardRoute)
                ->setExtra('icon', 'fa fa-dashboard');
        }

        $user = $this->container->get('security.context')->getToken()->getUser();
              
        // Order
        $orders = $this->addLinkRoute($menu, 'Commande', 'Politizr_AdminBundle_POrder_list');

        // User
        $users = $this->addLinkRoute($menu, 'Utilisateur', 'Politizr_AdminBundle_PUser_list');

        // Document
        $reputation = $this->addDropdown($menu, 'Document');
        $this->addLinkRoute(
            $reputation,
            'Débat',
            'Politizr_AdminBundle_PDDebate_list'
        );
        $this->addLinkRoute(
            $reputation,
            'Réaction',
            'Politizr_AdminBundle_PDReaction_list'
        );


        // Commentaires
        $reputation = $this->addDropdown($menu, 'Commentaire');
        $this->addLinkRoute(
            $reputation,
            'Débat',
            'Politizr_AdminBundle_PDDComment_list'
        );
        $this->addLinkRoute(
            $reputation,
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

        // Monitoring
        $monitoring = $this->addDropdown($menu, 'Suivi');
        $this->addLinkRoute(
            $monitoring,
            'Exception',
            'Politizr_AdminBundle_PMAppException_list'
        );
        $this->addLinkRoute(
            $monitoring,
            'Demande de modification',
            'Politizr_AdminBundle_PMAskForUpdate_list'
        );
        $this->addLinkRoute(
            $monitoring,
            'Abus',
            'Politizr_AdminBundle_PMAbuseReporting_list'
        );

        // Réglages des types:  tags, badges, mode & statut paiement, statut commande
        $regulations = $this->addDropdown($menu, 'Réglage');
        $this->addLinkRoute(
            $regulations,
            'Tag',
            'Politizr_AdminBundle_PTTagType_list'
        );
        $this->addLinkRoute(
            $regulations,
            'Badge (famille)',
            'Politizr_AdminBundle_PRBadgeFamily_list'
        );
        $this->addLinkRoute(
            $regulations,
            'Badge (type)',
            'Politizr_AdminBundle_PRBadgeType_list'
        );
        $this->addLinkRoute(
            $regulations,
            'Organisation',
            'Politizr_AdminBundle_PQOrganization_list'
        );
        $this->addLinkRoute(
            $regulations,
            'Mandat',
            'Politizr_AdminBundle_PQMandate_list'
        );
        $this->addLinkRoute(
            $regulations,
            'Modération',
            'Politizr_AdminBundle_PMModerationType_list'
        );
        $this->addLinkRoute(
            $regulations,
            'Formule d\'abonnement',
            'Politizr_AdminBundle_POSubscription_list'
        );
        $this->addLinkRoute(
            $regulations,
            'Mode Paiement',
            'Politizr_AdminBundle_POPaymentType_list'
        );
        $this->addLinkRoute(
            $regulations,
            'Statut Paiement',
            'Politizr_AdminBundle_POPaymentState_list'
        );
        $this->addLinkRoute(
            $regulations,
            'Statut Commande',
            'Politizr_AdminBundle_POOrderState_list'
        );
        $this->addLinkRoute(
            $regulations,
            'Administrateurs',
            'Politizr_AdminBundle_User_list'
        );

        // Archives
        $archive = $this->addDropdown($menu, 'Archive');
        $this->addLinkRoute(
            $archive,
            'Utilisateur',
            'Politizr_AdminBundle_PMUserHistoric_list'
        );
        $this->addLinkRoute(
            $archive,
            'Débat',
            'Politizr_AdminBundle_PMDebateHistoric_list'
        );
        $this->addLinkRoute(
            $archive,
            'Réaction',
            'Politizr_AdminBundle_PMReactionHistoric_list'
        );
        $this->addLinkRoute(
            $archive,
            'Commentaire débat',
            'Politizr_AdminBundle_PMDCommentHistoric_list'
        );
        $this->addLinkRoute(
            $archive,
            'Commentaire réaction',
            'Politizr_AdminBundle_PMRCommentHistoric_list'
        );
        
        return $menu;
    }
}
