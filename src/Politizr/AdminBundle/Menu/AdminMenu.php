<?php
namespace Politizr\AdminBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;

use Admingenerator\GeneratorBundle\Menu\AdmingeneratorMenuBuilder;

/**
 *
 * @author Lionel Bouzonville
 */
class AdminMenu extends AdmingeneratorMenuBuilder
{
    private $securityTokenStorage;

    /**
     * @see Admingenerator\GeneratorBundle\Menu\AdmingeneratorMenuBuilder
     * @param @security.token_storage
     */
    public function __construct(FactoryInterface $factory, RequestStack $requestStack, $dashboardRoute, $securityTokenStorage)
    {
        $this->factory = $factory;
        $this->requestStack = $requestStack;
        $this->dashboardRoute = $dashboardRoute;
        $this->securityTokenStorage = $securityTokenStorage;
    }

    /**
     * @param Request $requestaddNavLinkURI
     * @param Router $router
     */
    public function sidebarMenu(array $options)
    {
        $menu = $this->factory->createItem('root');
        $menu->setChildrenAttributes(array('class' => 'sidebar-menu'));

        // get current user
        $currentUser = $this->securityTokenStorage->getToken()->getUser();

        // Homepage
        if ($dashboardRoute = $this->dashboardRoute) {
            $this
                ->addLinkRoute($menu, 'Accueil', $dashboardRoute)
                ->setExtra('icon', 'fa fa-dashboard');
        }

        // Order
        $orders = $this->addDropdown($menu, 'Commande');
        $this->addLinkRoute(
            $orders,
            'Opération',
            'Politizr_AdminBundle_PEOperation_list'
        );
        $this->addLinkRoute(
            $orders,
            'Premium',
            'Politizr_AdminBundle_POrder_list'
        );

        // User
        $users = $this->addLinkRoute($menu, 'Utilisateur', 'Politizr_AdminBundle_PUser_list');

        // Document
        $reputation = $this->addDropdown($menu, 'Document');
        $this->addLinkRoute(
            $reputation,
            'Sujet',
            'Politizr_AdminBundle_PDDebate_list'
        );
        $this->addLinkRoute(
            $reputation,
            'Réponse',
            'Politizr_AdminBundle_PDReaction_list'
        );
        $this->addLinkRoute(
            $reputation,
            'Message direct',
            'Politizr_AdminBundle_PDDirect_list'
        );


        // Commentaires
        $reputation = $this->addDropdown($menu, 'Commentaire');
        $this->addLinkRoute(
            $reputation,
            'Sujet',
            'Politizr_AdminBundle_PDDComment_list'
        );
        $this->addLinkRoute(
            $reputation,
            'Réponse',
            'Politizr_AdminBundle_PDRComment_list'
        );

        // Groupes
        $circle = $this->addDropdown($menu, 'Groupes');
        $this->addLinkRoute(
            $circle,
            'Groupe',
            'Politizr_AdminBundle_PCircle_list'
        );
        $this->addLinkRoute(
            $circle,
            'Discussions',
            'Politizr_AdminBundle_PCTopic_list'
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
            'Emailing',
            'Politizr_AdminBundle_PMEmailing_list'
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
            'Notification',
            'Politizr_AdminBundle_PNotification_list'
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

        // Documents juridiques
        $legals = $this->addDropdown($menu, 'Juridique');
        $this->addLinkRoute(
            $legals,
            'CGU',
            'Politizr_AdminBundle_PMCgu_list'
        );
        $this->addLinkRoute(
            $legals,
            'CGV',
            'Politizr_AdminBundle_PMCgv_list'
        );
        $this->addLinkRoute(
            $legals,
            'Charte',
            'Politizr_AdminBundle_PMCharte_list'
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
