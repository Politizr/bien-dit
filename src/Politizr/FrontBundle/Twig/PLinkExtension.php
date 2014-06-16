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
    private $templating;

    private $pUser;

    /**
     *
     */
    public function __construct($serviceContainer) {
        $this->logger = $serviceContainer->get('logger');

        $this->router = $serviceContainer->get('router');
        $this->templating = $serviceContainer->get('templating');

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
            'linkFollow'  => new \Twig_Function_Method($this, 'linkFollow', array(
                    'is_safe' => array('html')
                    )
            ),
        );
    }

    /**
     *  Affiche le lien "Suivre" / "Ne plus suivre" / "M'inscrire" suivant le cas
     *
     * @param $id   ID objet
     * @param $type Object type (debate / puser)
     *
     * @return string
     */
    public function linkFollow($id, $type)
    {
        $this->logger->info('*** linkFollowDebate');
        $this->logger->info('$id = '.print_r($id, true));
        $this->logger->info('$type = '.print_r($type, true));

        $objectType = $type;
        $objectId = $id;
        $isFollower = false;

        if ($this->pUser) {
            $nbDebates = PUFollowDDQuery::create()
                ->filterByPUserId($this->pUser->getId())
                ->filterByPDDebateId($id)
                ->count();

            if ($nbDebates > 0) {
                $isFollower = true;
            }
        }

        // Construction du rendu du tag
        $html = $this->templating->render(
                            'PolitizrFrontBundle:Fragment:FollowAction.html.twig', array(
                                'objectId' => $objectId,
                                'objectType' => $objectType,
                                'isFollower' => $isFollower
                                )
                    );

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