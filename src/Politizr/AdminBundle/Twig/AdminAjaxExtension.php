<?php
namespace Politizr\Adminbundle\Twig;

use Politizr\Constant\ReputationConstants;

use Politizr\Model\PUFollowDDQuery;


/**
 * Gestion des liens proposés à l'utilisateur en fonction de:
 *  - ses droits (citoyen / élu)
 *  - sa réputation
 *
 * @author Lionel Bouzonville
 */
class AdminAjaxExtension extends \Twig_Extension
{
    private $logger;

    private $router;
    private $templating;

    /**
     *
     */
    public function __construct($serviceContainer) {
        $this->logger = $serviceContainer->get('logger');

        $this->router = $serviceContainer->get('router');
        $this->templating = $serviceContainer->get('templating');
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
            'adminUserTags'  => new \Twig_Function_Method($this, 'adminUserTags', array(
                    'is_safe' => array('html')
                    )
            ),
            'adminUserDebates'  => new \Twig_Function_Method($this, 'adminUserDebates', array(
                    'is_safe' => array('html')
                    )
            ),
            'adminUserReactions'  => new \Twig_Function_Method($this, 'adminUserReactions', array(
                    'is_safe' => array('html')
                    )
            ),
            'adminUserCommentsD'  => new \Twig_Function_Method($this, 'adminUserCommentsD', array(
                    'is_safe' => array('html')
                    )
            ),
            'adminUserCommentsR'  => new \Twig_Function_Method($this, 'adminUserCommentsR', array(
                    'is_safe' => array('html')
                    )
            ),
            'adminUserFollowersQ'  => new \Twig_Function_Method($this, 'adminUserFollowersQ', array(
                    'is_safe' => array('html')
                    )
            ),
            'adminUserFollowersC'  => new \Twig_Function_Method($this, 'adminUserFollowersC', array(
                    'is_safe' => array('html')
                    )
            ),
            'adminUserSubscribersQ'  => new \Twig_Function_Method($this, 'adminUserSubscribersQ', array(
                    'is_safe' => array('html')
                    )
            ),
            'adminUserSubscribersC'  => new \Twig_Function_Method($this, 'adminUserSubscribersC', array(
                    'is_safe' => array('html')
                    )
            ),
            'adminUserBadges'  => new \Twig_Function_Method($this, 'adminUserBadges', array(
                    'is_safe' => array('html')
                    )
            ),
            'adminDebateReactions'  => new \Twig_Function_Method($this, 'adminDebateReactions', array(
                    'is_safe' => array('html')
                    )
            ),
            'adminDebateComments'  => new \Twig_Function_Method($this, 'adminDebateComments', array(
                    'is_safe' => array('html')
                    )
            ),
            'adminDebateTags'  => new \Twig_Function_Method($this, 'adminDebateTags', array(
                    'is_safe' => array('html')
                    )
            ),
            'adminDebateFollowersQ'  => new \Twig_Function_Method($this, 'adminDebateFollowersQ', array(
                    'is_safe' => array('html')
                    )
            ),
            'adminDebateFollowersC'  => new \Twig_Function_Method($this, 'adminDebateFollowersC', array(
                    'is_safe' => array('html')
                    )
            ),
            'adminReactionComments'  => new \Twig_Function_Method($this, 'adminReactionComments', array(
                    'is_safe' => array('html')
                    )
            ),
        );
    }

    // ****************************************  GESTION USER ******************************************* //


    /**
     *  Gestion des tags d'un user
     *
     * @param $pUser        PUser       PUser
     * @param $ptTagTypeId  integer     ID type de tag
     * @param $zoneId       integer     ID de la zone CSS
     * @param $mode         string      edit (default) / show
     *
     * @return string
     */
    public function adminUserTags($pUser, $ptTagTypeId, $zoneId = 1, $mode = 'edit')
    {
        $this->logger->info('*** adminUserTags');
        // $this->logger->info('$pUser = '.print_r($pUser, true));
        // $this->logger->info('$ptTagTypeId = '.print_r($ptTagTypeId, true));
        // $this->logger->info('$zoneId = '.print_r($zoneId, true));
        // $this->logger->info('$mode = '.print_r($mode, true));


        if ($mode == 'edit') {
            $template = "UserTagsEdit.html.twig";
        } else {
            $template = "UserTagsShow.html.twig";
        }

        // Construction du rendu du tag
        $html = $this->templating->render(
                            'PolitizrAdminBundle:Fragment:'.$template, array(
                                'pUser' => $pUser,
                                'ptTagTypeId' => $ptTagTypeId,
                                'zoneId' => $zoneId,
                                'pTags' => $pUser->getTaggedPTags($ptTagTypeId)
                                )
                    );

        return $html;

    }

    /**
     *  Gestion des débats d'un user
     *
     * @param $pUser        PUser
     *
     * @return string
     */
    public function adminUserDebates($pUser)
    {
        $this->logger->info('*** adminUserDebates');
        // $this->logger->info('$pUser = '.print_r($pUser, true));

        // Construction du rendu du tag
        $html = $this->templating->render(
                            'PolitizrAdminBundle:Fragment:UserDebates.html.twig', array(
                                'pUser' => $pUser,
                                'pdDebates' => $pUser->getPDDebates(),
                                )
                    );

        return $html;

    }


    /**
     *  Gestion des réactions d'un user
     *
     * @param $pUser        PUser
     *
     * @return string
     */
    public function adminUserReactions($pUser)
    {
        $this->logger->info('*** adminUserReactions');
        // $this->logger->info('$pUser = '.print_r($pUser, true));

        // Construction du rendu du tag
        $html = $this->templating->render(
                            'PolitizrAdminBundle:Fragment:UserReactions.html.twig', array(
                                'pUser' => $pUser,
                                'pdReactions' => $pUser->getPDReactions(),
                                )
                    );

        return $html;

    }


    /**
     *  Gestion des commentaires sur un débat d'un user
     *
     * @param $pUser        PUser
     *
     * @return string
     */
    public function adminUserCommentsD($pUser)
    {
        $this->logger->info('*** adminUserCommentsD');
        // $this->logger->info('$pUser = '.print_r($pUser, true));

        // Construction du rendu du tag
        $html = $this->templating->render(
                            'PolitizrAdminBundle:Fragment:UserCommentsD.html.twig', array(
                                'pUser' => $pUser,
                                'pddComments' => $pUser->getCommentsD(),
                                )
                    );

        return $html;

    }


    /**
     *  Gestion des commentaires sur une réaction d'un user
     *
     * @param $pUser        PUser
     *
     * @return string
     */
    public function adminUserCommentsR($pUser)
    {
        $this->logger->info('*** adminUserCommentsR');
        // $this->logger->info('$pUser = '.print_r($pUser, true));

        // Construction du rendu du tag
        $html = $this->templating->render(
                            'PolitizrAdminBundle:Fragment:UserCommentsR.html.twig', array(
                                'pUser' => $pUser,
                                'pdrComments' => $pUser->getCommentsR(),
                                )
                    );

        return $html;

    }

    /**
     *  Gestion des followers qualifiés d'un user
     *
     * @param $pUser        PUser
     *
     * @return string
     */
    public function adminUserFollowersQ($pUser)
    {
        $this->logger->info('*** adminUserFollowersQ');
        // $this->logger->info('$pUser = '.print_r($pUser, true));

        // Construction du rendu du tag
        $html = $this->templating->render(
                            'PolitizrAdminBundle:Fragment:UserFollowersSubscribers.html.twig', array(
                                'pUser' => $pUser,
                                'pUsers' => $pUser->getFollowersQ(),
                                )
                    );

        return $html;
    }


    /**
     *  Gestion des followers citoyens d'un user
     *
     * @param $pUser        PUser
     *
     * @return string
     */
    public function adminUserFollowersC($pUser)
    {
        $this->logger->info('*** adminUserFollowersC');
        // $this->logger->info('$pUser = '.print_r($pUser, true));

        // Construction du rendu du tag
        $html = $this->templating->render(
                            'PolitizrAdminBundle:Fragment:UserFollowersSubscribers.html.twig', array(
                                'pUser' => $pUser,
                                'pUsers' => $pUser->getFollowersC(),
                                )
                    );

        return $html;
    }


    /**
     *  Gestion des abonnements qualifiés d'un user
     *
     * @param $pUser        PUser
     *
     * @return string
     */
    public function adminUserSubscribersQ($pUser)
    {
        $this->logger->info('*** adminUserSubscribersQ');
        // $this->logger->info('$pUser = '.print_r($pUser, true));

        // Construction du rendu du tag
        $html = $this->templating->render(
                            'PolitizrAdminBundle:Fragment:UserFollowersSubscribers.html.twig', array(
                                'pUser' => $pUser,
                                'pUsers' => $pUser->getPUserSubscribersQ(),
                                )
                    );

        return $html;
    }


    /**
     *  Gestion des abonnements citoyens d'un user
     *
     * @param $pUser        PUser
     *
     * @return string
     */
    public function adminUserSubscribersC($pUser)
    {
        $this->logger->info('*** adminUserSubscribersC');
        // $this->logger->info('$pUser = '.print_r($pUser, true));

        // Construction du rendu du tag
        $html = $this->templating->render(
                            'PolitizrAdminBundle:Fragment:UserFollowersSubscribers.html.twig', array(
                                'pUser' => $pUser,
                                'pUsers' => $pUser->getPUserSubscribersC(),
                                )
                    );

        return $html;
    }

    /**
     *  Gestion des badges d'un user
     *
     * @param $pUser        PUser
     * @param $prBadgeType  integer     ID type de badge
     * @param $zoneId       integer     ID de la zone CSS
     *
     * @return string
     */
    public function adminUserBadges($pUser, $prBadgeType, $zoneId = 1)
    {
        $this->logger->info('*** adminUserBadges');
        // $this->logger->info('$pUser = '.print_r($pUser, true));
        // $this->logger->info('$prBadgeType = '.print_r($prBadgeType, true));

        // Construction du rendu du tag
        $html = $this->templating->render(
                            'PolitizrAdminBundle:Fragment:UserBadges.html.twig', array(
                                'pUser' => $pUser,
                                'prBadges' => $pUser->getPRBadges($prBadgeType),
                                'zoneId' => $zoneId,
                                )
                    );

        return $html;

    }



    // ****************************************  GESTION DEBAT ******************************************* //

    /**
     *  Gestion des réactions associées à un débat
     *
     * @param $pdDebate     PDDDebate
     * @param $mode         string      edit (default) / show
     *
     * @return string
     */
    public function adminDebateReactions($pdDebate, $mode = 'edit')
    {
        $this->logger->info('*** adminDebateReactions');
        // $this->logger->info('$pUser = '.print_r($pUser, true));
        // $this->logger->info('$mode = '.print_r($mode, true));

        if ($mode == 'edit') {
            $template = "DebateReactionsEdit.html.twig";
        } else {
            $template = "DebateReactionsShow.html.twig";
        }


        // Construction du rendu du tag
        $html = $this->templating->render(
                            'PolitizrAdminBundle:Fragment:'.$template, array(
                                'pdDebate' => $pdDebate,
                                'PDReactions' => $pdDebate->getReactions(false, false),
                                )
                    );

        return $html;
    }


    /**
     *  Gestion des commentaires associés à un débat
     *
     * @param $pdDebate    PDDDebate
     *
     * @return string
     */
    public function adminDebateComments($pdDebate)
    {
        $this->logger->info('*** adminDebateComments');
        // $this->logger->info('$pUser = '.print_r($pUser, true));

        // Construction du rendu du tag
        $html = $this->templating->render(
                            'PolitizrAdminBundle:Fragment:DebateComments.html.twig', array(
                                'pdDebate' => $pdDebate,
                                'pddComments' => $pdDebate->getComments(),
                                )
                    );

        return $html;
    }

    /**
     *  Gestion des tags d'un débat
     *
     * @param $pdDebate     PDDebate    PDDebate
     * @param $ptTagTypeId  integer     ID type de tag
     * @param $zoneId       integer     ID de la zone CSS
     * @param $mode         string      edit (default) / show
     *
     * @return string
     */
    public function adminDebateTags($pdDebate, $ptTagTypeId, $zoneId = 1, $mode = 'edit')
    {
        $this->logger->info('*** adminDebateTags');
        // $this->logger->info('$pdDebate = '.print_r($pdDebate, true));
        // $this->logger->info('$pTTagType = '.print_r($pTTagType, true));
        // $this->logger->info('$zoneId = '.print_r($zoneId, true));
        // $this->logger->info('$mode = '.print_r($mode, true));


        if ($mode == 'edit') {
            $template = "DebateTagsEdit.html.twig";
        } else {
            $template = "DebateTagsShow.html.twig";
        }


        // Construction du rendu du tag
        $html = $this->templating->render(
                            'PolitizrAdminBundle:Fragment:'.$template, array(
                                'pdDebate' => $pdDebate,
                                'ptTagTypeId' => $ptTagTypeId,
                                'zoneId' => $zoneId,
                                'pTags' => $pdDebate->getPTags($ptTagTypeId)
                                )
                    );

        return $html;

    }

    /**
     *  Gestion des followers qualifiés d'un débat
     *
     * @param $pdDebate     PDDebate    PDDebate
     *
     * @return string
     */
    public function adminDebateFollowersQ($pdDebate)
    {
        $this->logger->info('*** adminDebateFollowersQ');
        // $this->logger->info('$pdDebate = '.print_r($pdDebate, true));

        // Construction du rendu du tag
        $html = $this->templating->render(
                            'PolitizrAdminBundle:Fragment:DebateFollowers.html.twig', array(
                                'pdDebate' => $pdDebate,
                                'pUsers' => $pdDebate->getFollowersQ(),
                                )
                    );

        return $html;
    }


    /**
     *  Gestion des followers citoyens d'un débat
     *
     * @param $pdDebate     PDDebate    PDDebate
     *
     * @return string
     */
    public function adminDebateFollowersC($pdDebate)
    {
        $this->logger->info('*** adminUserFollowersC');
        // $this->logger->info('$pdDebate = '.print_r($pdDebate, true));

        // Construction du rendu du tag
        $html = $this->templating->render(
                            'PolitizrAdminBundle:Fragment:DebateFollowers.html.twig', array(
                                'pdDebate' => $pdDebate,
                                'pUsers' => $pdDebate->getFollowersC(),
                                )
                    );

        return $html;
    }


    // ****************************************  GESTION REACTION ******************************************* //


    /**
     *  Gestion des commentaires associés à un débat
     *
     * @param $pdReaction   PDReaction
     *
     * @return string
     */
    public function adminReactionComments($pdReaction)
    {
        $this->logger->info('*** adminReactionComments');
        // $this->logger->info('$pUser = '.print_r($pUser, true));

        // Construction du rendu du tag
        $html = $this->templating->render(
                            'PolitizrAdminBundle:Fragment:ReactionComments.html.twig', array(
                                'pdReaction' => $pdReaction,
                                'pdrComments' => $pdReaction->getComments(),
                                )
                    );

        return $html;
    }




    public function getName()
    {
        return 'admin_ajax_extension';
    }


    /* ######################################################################################################## */
    /*                                                 FONCTIONS PRIVÉES                                        */
    /* ######################################################################################################## */


}