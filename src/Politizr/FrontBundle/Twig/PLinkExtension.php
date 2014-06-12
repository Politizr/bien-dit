<?php
namespace Politizr\Frontbundle\Twig;

use Politizr\Constant\ReputationConstants;

use Politizr\Model\PUFollowDDQuery;


/**
 * Gestion des liens proposés à l'utilisateur en fonction de:
 *  - ses droits (citoyen / élu)
 *  - sa réputation
 *
 * @author Lionel Bouzonville
 */
class PLinkExtension extends \Twig_Extension
{
    private $logger;

    private $router;

    private $pUser;

    /**
     *
     */
    public function __construct($serviceContainer) {
        $this->logger = $serviceContainer->get('logger');

        $this->router = $serviceContainer->get('router');

        // Récupération du user en session
        $token = $serviceContainer->get('security.context')->getToken();
        if ($token && $user = $token->getUser()) {

            $className = 'Politizr\Model\PUser';
            if ($user && $user instanceof $className) {
                $this->pUser = $user;
            } else {
                $this->pUser = null;
            }
        } else {
            $this->pUser = null;
        }

    }

    /* ######################################################################################################## */
    /*                                              FONCTIONS ET FILTRES                                        */
    /* ######################################################################################################## */


    /**
     *  Renvoie la liste des filtres
     */
//     public function getFilters()
//     {
//         return array(
//             new \Twig_SimpleFilter('price', array($this, 'priceFilter')),
//         );
//     }


    /**
     *  Renvoie la liste des fonctions
     */
    public function getFunctions()
    {
        return array(
            'linkFollowDebate'  => new \Twig_Function_Method($this, 'linkFollowDebate', array(
                    'is_safe' => array('html')
                    )
            ),
        );
    }

    /**
     *  Formate un lien "Suivre le débat" / "Ne plus suivre le débat"
     *
     * @param $id   ID objet PDDebate
     *
     * @return string
     */
    public function linkFollowDebate($id)
    {
        $this->logger->info('*** linkFollowDebate');
        $this->logger->info('$id = '.print_r($id, true));

        // Lien par défaut > inscription
        $linkHref = $this->router->generate('Inscription');
        $linkText = 'Suivre le débat';

        if ($this->pUser) {
            $nbDebates = PUFollowDDQuery::create()
                ->filterByPUserId($this->pUser->getId())
                ->filterByPDDebateId($id)
                ->count();

            if ($nbDebates > 0) {
                $linkText = 'Ne plus suivre';
                
                $html = '<a class="unfollowPDebate" objectId="'.$id.'">'.$linkText.'</a>';
            } else {
                $html = '<a class="followPDebate" objectId="'.$id.'">'.$linkText.'</a>';
            }
        } else {
            $html = '<a href="'.$linkHref.'">'.$linkText.'</a>';
        }

        

        return $html;
    }

    public function getName()
    {
        return 'p_link_extension';
    }


    /* ######################################################################################################## */
    /*                                                 FONCTIONS PRIVÉES                                        */
    /* ######################################################################################################## */


}